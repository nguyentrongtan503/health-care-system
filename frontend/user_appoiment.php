<?php
session_start();
// Include file dcFrontend.php (đã include dbconnect.php)
require_once './model/dcFrontend.php';
// Tạo kết nối cơ sở dữ liệu
$conn = connectDB();

// Include header
include __DIR__ . "/View/header.php"; 

// // Kiểm tra đăng nhập
// if (!isset($_SESSION['username'])) {
//     header("Location: ./View/signin.php");
//     exit();
// }

// Lấy UserID từ bảng LoaiTaiKhoan dựa trên username
$username = $_SESSION['username'];
$query = "SELECT UserID FROM LoaiTaiKhoan WHERE TenTaiKhoan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_id = null;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['UserID'];
} else {
    die("Không tìm thấy người dùng với tên: " . htmlspecialchars($username));
}

// Xử lý hủy lịch
if (isset($_GET['cancel_id'])) {
    $appointment_id = $_GET['cancel_id'];

    // Xác minh đơn đặt lịch thuộc về người dùng
    $query = "SELECT TrangThai FROM DatLich WHERE MaDatLich = ? AND MaNguoiDung = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $appointment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $error = "Đơn đặt lịch không tồn tại hoặc không thuộc về bạn";
    } else {
        $row = $result->fetch_assoc();
        if ($row['TrangThai'] !== 'Chưa duyệt' && $row['TrangThai'] !== 'Đã duyệt') {
            $error = "Không thể hủy đơn đã được xử lý hoặc đã hủy";
        } else {
            // Cập nhật trạng thái thành "Đã hủy"
            $query = "UPDATE DatLich SET TrangThai = 'Đã hủy' WHERE MaDatLich = ? AND MaNguoiDung = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $appointment_id, $user_id);

            if ($stmt->execute()) {
                // Nếu sử dụng bảng ChiTietDatLich (Ý tưởng 2)
                $query = "INSERT INTO ChiTietDatLich (MaDatLich, NguoiHuy) VALUES (?, 'User')";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("s", $appointment_id);
                $stmt->execute();

                $message = "Hủy lịch thành công";
            } else {
                $error = "Lỗi khi hủy lịch: " . $stmt->error;
            }
        }
    }
}

// Lấy danh sách đơn đặt lịch của người dùng
$query = "SELECT dl.MaDatLich, dl.NgayHen, dl.GioHen, dl.HoTen, dl.SoDienThoai, dl.LoiNhan, dl.TrangThai, 
         ctdl.LyDoHuy, ctdl.SoDienThoaiBacSi, ctdl.PhongKham, ctdl.NguoiHuy,
         ld.TenLoaiDichVu, nv.TenNhanVien
         FROM DatLich dl 
         JOIN LoaiDichVu ld ON dl.MaLoaiDichVu = ld.MaLoaiDichVu 
         JOIN NhanVien nv ON dl.MaNhanVien = nv.MaNhanVien 
         LEFT JOIN ChiTietDatLich ctdl ON dl.MaDatLich = ctdl.MaDatLich
         WHERE dl.MaNguoiDung = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = [
        'id' => (string)$row['MaDatLich'],
        'service' => $row['TenLoaiDichVu'],
        'doctor' => $row['TenNhanVien'],
        'date' => date('d/m/Y', strtotime($row['NgayHen'])),
        'time' => $row['GioHen'],
        'patient' => $row['HoTen'],
        'phone' => $row['SoDienThoai'],
        'message' => $row['LoiNhan'],
        'status' => strtolower($row['TrangThai'] == 'Chưa duyệt' ? 'pending' : ($row['TrangThai'] == 'Đã duyệt' ? 'approved' : 'cancelled')),
        'cancel_reason' => $row['LyDoHuy'],
        'doctor_phone' => $row['SoDienThoaiBacSi'],
        'room' => $row['PhongKham'],
        'cancelled_by' => $row['NguoiHuy']
    ];
}
?>


<div class="container">
    <div class="header">
        <h1>Lịch sử đặt lịch khám</h1>
    </div>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="filter-section">
        <button class="filter-btn active" data-filter="all">Tất cả</button>
        <button class="filter-btn" data-filter="approved">Đã xác nhận</button>
        <button class="filter-btn" data-filter="pending">Chờ xác nhận</button>
        <button class="filter-btn" data-filter="cancelled">Hủy lịch</button>
    </div>

    <table class="appointment-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Dịch vụ</th>
                <th>Bác sĩ</th>
                <th>Ngày hẹn</th>
                <th>Giờ hẹn</th>
                <th>Bệnh nhân</th>
                <th>Điện thoại</th>
                <th>Lời nhắn</th>
                <th>Trạng thái</th>
                <th>Tùy chọn</th>
            </tr>
        </thead>
        <tbody id="appointment-body">
            <!-- Dữ liệu sẽ được thêm bằng JavaScript -->
        </tbody>
    </table>

    <div class="pagination" id="pagination"></div>
</div>

<!-- Modal xem lý do hủy lịch -->
<div id="cancelReasonModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Thông báo hủy lịch</h3>
            <button class="modal-close">×</button>
        </div>
        <div class="modal-body">
            <div class="info-item">
                <div class="info-label">Kính gửi quý khách:</div>
                <div class="cancel-reason" id="cancelReasonText"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-btn close">Đóng</button>
        </div>
    </div>
</div>

<!-- Modal chi tiết lịch hẹn -->
<div id="appointmentDetailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Chi tiết lịch hẹn</h3>
            <button class="modal-close">×</button>
        </div>
        <div class="modal-body" id="appointmentDetailContent"></div>
        <div class="modal-footer">
            <button class="action-btn close">Đóng</button>
            <button class="action-btn cancel">Hủy lịch</button>
        </div>
    </div>
</div>

<!-- Modal xác nhận hủy lịch -->
<div id="confirmCancelModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Xác nhận hủy lịch</h3>
            <button class="modal-close">×</button>
        </div>
        <div class="modal-body">
            <p id="confirmCancelMessage"></p>
        </div>
        <div class="modal-footer">
            <button class="action-btn close">Quay lại</button>
            <button class="action-btn confirm">Xác nhận</button>
        </div>
    </div>
</div>

<!-- Modal thông báo hủy thành công -->
<div id="cancelSuccessModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Thông báo</h3>
            <button class="modal-close">×</button>
        </div>
        <div class="modal-body">
            <p>Đã hủy lịch khám thành công!</p>
        </div>
        <div class="modal-footer">
            <button class="action-btn close">Đóng</button>
        </div>
    </div>
</div>

<script>
    // Dữ liệu từ PHP
    const appointments = <?php echo json_encode($appointments); ?>;
    console.log('Appointments:', appointments);

    // Biến toàn cục
    let currentPage = 1;
    let currentFilter = "all";
    const recordsPerPage = 10;
    let currentAppointmentId = null;

    // DOM Elements
    const cancelReasonModal = document.getElementById('cancelReasonModal');
    const appointmentDetailModal = document.getElementById('appointmentDetailModal');
    const confirmCancelModal = document.getElementById('confirmCancelModal');
    const cancelSuccessModal = document.getElementById('cancelSuccessModal');
    const cancelReasonText = document.getElementById('cancelReasonText');
    const appointmentDetailContent = document.getElementById('appointmentDetailContent');
    const confirmCancelMessage = document.getElementById('confirmCancelMessage');

    // Hàm hiển thị dữ liệu
    function displayAppointments() {
        const tbody = document.getElementById('appointment-body');
        tbody.innerHTML = '';

        // Lọc dữ liệu theo trạng thái
        let filteredData = appointments;
        if (currentFilter !== 'all') {
            filteredData = appointments.filter(app => app.status === currentFilter);
        }

        // Tính toán dữ liệu cho trang hiện tại
        const totalPages = Math.ceil(filteredData.length / recordsPerPage);
        const startIndex = (currentPage - 1) * recordsPerPage;
        const endIndex = Math.min(startIndex + recordsPerPage, filteredData.length);
        const paginatedData = filteredData.slice(startIndex, endIndex);

        // Hiển thị thông báo nếu không có dữ liệu
        if (paginatedData.length === 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td colspan="10" class="no-records">Không có dữ liệu đặt lịch</td>`;
            tbody.appendChild(tr);
            displayPagination(0);
            return;
        }

        // Hiển thị dữ liệu
        paginatedData.forEach((app, index) => {
            const tr = document.createElement('tr');
            const stt = startIndex + index + 1;

            // Xác định trạng thái và nút hành động
            let statusClass, statusText, actionButtons;

            if (app.status === 'approved') {
                statusClass = 'status-approved';
                statusText = 'Đã xác nhận';
                actionButtons = `
                    <div class="action-buttons">
                        <button class="action-btn detail" data-id="${app.id}">
                            <i class="fas fa-info-circle"></i> Chi tiết
                        </button>
                    </div>
                `;
            } else if (app.status === 'pending') {
                statusClass = 'status-pending';
                statusText = 'Chờ xác nhận';
                actionButtons = `
                    <div class="action-buttons">
                        <button class="action-btn cancel" data-id="${app.id}">
                            <i class="fas fa-times-circle"></i> Hủy lịch
                        </button>
                    </div>
                `;
            } else {
                statusClass = 'status-cancelled';
                statusText = 'Hủy lịch';
                actionButtons = `
                    <div class="action-buttons">
                        <button class="action-btn review" data-id="${app.id}">
                            <i class="fas fa-eye"></i> Xem lại
                        </button>
                    </div>
                `;
            }

            tr.innerHTML = `
                <td>${stt}</td>
                <td>${app.service}</td>
                <td>${app.doctor}</td>
                <td>${app.date}</td>
                <td>${app.time}</td>
                <td>${app.patient}</td>
                <td>${app.phone}</td>
                <td class="patient-message" title="${app.message}">${app.message}</td>
                <td><span class="status ${statusClass}">${statusText}</span></td>
                <td>${actionButtons}</td>
            `;

            tbody.appendChild(tr);
        });

        // Hiển thị phân trang
        displayPagination(filteredData.length);

        // Gắn lại sự kiện cho các nút sau khi render
        addEventListeners();
    }

    // Hàm hiển thị phân trang
    function displayPagination(totalRecords) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        const totalPages = Math.ceil(totalRecords / recordsPerPage);

        if (totalPages <= 1) return;

        // Nút Previous
        const prevLi = document.createElement('div');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevLi.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                displayAppointments();
            }
        });
        pagination.appendChild(prevLi);

        // Các nút trang
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        if (startPage > 1) {
            const firstLi = document.createElement('div');
            firstLi.className = `page-item ${currentPage === 1 ? 'active' : ''}`;
            firstLi.textContent = '1';
            firstLi.addEventListener('click', () => {
                currentPage = 1;
                displayAppointments();
            });
            pagination.appendChild(firstLi);

            if (startPage > 2) {
                const dotsLi = document.createElement('div');
                dotsLi.className = 'page-item disabled';
                dotsLi.textContent = '...';
                pagination.appendChild(dotsLi);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageLi = document.createElement('div');
            pageLi.className = `page-item ${currentPage === i ? 'active' : ''}`;
            pageLi.textContent = i;
            pageLi.addEventListener('click', () => {
                currentPage = i;
                displayAppointments();
            });
            pagination.appendChild(pageLi);
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                const dotsLi = document.createElement('div');
                dotsLi.className = 'page-item disabled';
                dotsLi.textContent = '...';
                pagination.appendChild(dotsLi);
            }

            const lastLi = document.createElement('div');
            lastLi.className = `page-item ${currentPage === totalPages ? 'active' : ''}`;
            lastLi.textContent = totalPages;
            lastLi.addEventListener('click', () => {
                currentPage = totalPages;
                displayAppointments();
            });
            pagination.appendChild(lastLi);
        }

        // Nút Next
        const nextLi = document.createElement('div');
        nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
        nextLi.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextLi.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                displayAppointments();
            }
        });
        pagination.appendChild(nextLi);
    }

    // Hàm thêm sự kiện cho các nút
    function addEventListeners() {
        // Nút xem lại lý do hủy lịch
        const reviewButtons = document.querySelectorAll('.action-btn.review');
        console.log('Review buttons found:', reviewButtons.length);
        reviewButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const appointmentId = this.dataset.id;
                console.log('Review button clicked, ID:', appointmentId);
                showCancelReason(appointmentId);
            });
        });

        // Nút xem chi tiết lịch hẹn
        const detailButtons = document.querySelectorAll('.action-btn.detail');
        console.log('Detail buttons found:', detailButtons.length);
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const appointmentId = this.dataset.id;
                console.log('Detail button clicked, ID:', appointmentId);
                showAppointmentDetail(appointmentId);
            });
        });

        // Nút hủy lịch (trong trạng thái Chờ xác nhận)
        const cancelButtons = document.querySelectorAll('.action-btn.cancel');
        console.log('Cancel buttons found:', cancelButtons.length);
        cancelButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const appointmentId = this.dataset.id;
                console.log('Cancel button clicked, ID:', appointmentId);
                const appointment = appointments.find(a => a.id === appointmentId);

                if (appointment) {
                    if (appointment.status === 'approved') {
                        confirmCancelMessage.innerHTML = `
                            Bạn có chắc chắn muốn hủy lịch khám đã được xác nhận?<br>
                            <small>Lưu ý: Hủy lịch khám đã xác nhận có thể ảnh hưởng đến lịch trình của bác sĩ.</small>
                        `;
                    } else {
                        confirmCancelMessage.textContent = 'Bạn có chắc chắn muốn hủy lịch khám này?';
                    }

                    currentAppointmentId = appointmentId;
                    showModal(confirmCancelModal);
                } else {
                    console.error('Appointment not found for cancel, ID:', appointmentId);
                }
            });
        });

        // Nút hủy lịch từ modal chi tiết
        const cancelFromDetailBtn = document.querySelector('#appointmentDetailModal .action-btn.cancel');
        if (cancelFromDetailBtn) {
            cancelFromDetailBtn.addEventListener('click', function () {
                console.log('Cancel from detail modal clicked, ID:', currentAppointmentId);
                const appointment = appointments.find(a => a.id === currentAppointmentId);
                if (appointment) {
                    confirmCancelMessage.innerHTML = `
                        Bạn có chắc chắn muốn hủy lịch khám đã được xác nhận?<br>
                        <small>Lưu ý: Hủy lịch khám đã xác nhận có thể ảnh hưởng đến lịch trình của bác sĩ.</small>
                    `;
                    showModal(confirmCancelModal);
                } else {
                    console.error('Appointment not found for cancel from detail, ID:', currentAppointmentId);
                }
            });
        }

        // Nút đóng modal
    const closeButtons = document.querySelectorAll('.modal-close, .action-btn.close');
    console.log('Close buttons found:', closeButtons.length);
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            console.log('Close modal clicked');
            closeAllModals();

            // Nếu đóng modal cancelSuccessModal, chuyển hướng để làm mới trang
            if (this.closest('#cancelSuccessModal')) {
                window.location.href = 'user_appoiment.php';
            }
        });
    });

        // Nút xác nhận hủy lịch
    const confirmCancelBtn = document.querySelector('.action-btn.confirm');
    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener('click', function () {
            console.log('Confirm cancel clicked, ID:', currentAppointmentId);

            // Sử dụng AJAX để gửi yêu cầu hủy lịch
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `user_appoiment.php?cancel_id=${currentAppointmentId}`, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Đóng modal xác nhận
                        closeAllModals();
                        // Hiển thị modal thông báo thành công
                        showModal(cancelSuccessModal);
                    } else {
                        console.error('Error cancelling appointment:', xhr.statusText);
                        alert('Có lỗi xảy ra khi hủy lịch. Vui lòng thử lại.');
                    }
                }
            };
            xhr.send();
        });
    }
}

    // Hàm hiển thị lý do hủy lịch
    function showCancelReason(appointmentId) {
        const appointment = appointments.find(a => a.id === appointmentId);
        if (appointment) {
            console.log('Showing cancel reason for:', appointment);
            if (appointment.cancelled_by === 'User') {
                cancelReasonText.innerHTML = `
                    Chào bạn <strong>${appointment.patient}</strong>, chúng tôi rất tiếc phải thông báo rằng lịch khám của bạn ngày <strong>${appointment.date}</strong> 
                    đã bị hủy. Vui lòng đặt lịch khám mới hoặc liên hệ hotline 1800-xxxx để được hỗ trợ.
                `;
            } else {
                cancelReasonText.innerHTML = `
                    Chào bạn <strong>${appointment.patient}</strong>, lịch khám của bạn ngày <strong>${appointment.date}</strong> đã bị hủy với lý do: <br>
                    <strong>${appointment.cancel_reason || 'Không có lý do cụ thể'}</strong>
                `;
            }
            showModal(cancelReasonModal);
        } else {
            console.error('Appointment not found for ID:', appointmentId);
        }
    }

    // Hàm hiển thị chi tiết lịch hẹn
    function showAppointmentDetail(appointmentId) {
        const appointment = appointments.find(a => a.id === appointmentId);
        if (appointment) {
            console.log('Showing appointment detail for:', appointment);
            appointmentDetailContent.innerHTML = `
                <div class="info-item">
                    <div class="info-label">Dịch vụ khám</div>
                    <div class="info-value">${appointment.service}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Bác sĩ phụ trách</div>
                    <div class="info-value">${appointment.doctor}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Số điện thoại bác sĩ</div>
                    <div class="info-value">${appointment.doctor_phone || 'Chưa có thông tin'}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phòng khám</div>
                    <div class="info-value">${appointment.room || 'Chưa có thông tin'}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Ngày hẹn</div>
                    <div class="info-value">${appointment.date}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Giờ hẹn</div>
                    <div class="info-value">${appointment.time}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Lời nhắn của bạn</div>
                    <div class="info-value">${appointment.message}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Hướng dẫn</div>
                    <div class="info-value">
                        Vui lòng đến trước 15 phút để làm thủ tục. Mang theo CMND/CCCD và thẻ BHYT (nếu có).
                    </div>
                </div>
            `;
            currentAppointmentId = appointmentId;
            showModal(appointmentDetailModal);
        } else {
            console.error('Appointment not found for ID:', appointmentId);
        }
    }

    // Hàm hiển thị modal
    function showModal(modal) {
        closeAllModals();
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Hàm đóng tất cả modal
    function closeAllModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = 'auto';
    }

    // Khởi tạo
    document.addEventListener('DOMContentLoaded', function () {
        // Thêm sự kiện cho các nút lọc
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                console.log('Filter button clicked:', this.dataset.filter);
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                currentPage = 1; // Reset về trang 1 khi thay đổi bộ lọc
                displayAppointments();
            });
        });

        // Hiển thị dữ liệu ban đầu
        displayAppointments();

        // Đóng modal khi click bên ngoài
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    console.log('Clicked outside modal to close');
                    closeAllModals();
                }
            });
        });
    });
</script>

<?php include __DIR__ . "/View/footer.php"; ?>