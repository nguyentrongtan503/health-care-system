<?php
session_start();
include "./model/dcFrontend.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ./View/signin.php");
    exit();
}

$conn = connectDB();
$username = $_SESSION['username'];

// Lấy thông tin người dùng từ bảng LoaiTaiKhoan
$query = "SELECT * FROM LoaiTaiKhoan WHERE TenTaiKhoan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    echo "Không tìm thấy thông tin tài khoản!";
    exit();
}

$userID = $userData['UserID'];
$email = $userData['Email'];

// Lấy thông tin khách hàng từ bảng KhachHang
$query = "SELECT * FROM KhachHang WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$customerData = $result->fetch_assoc();

// Nếu không có thông tin khách hàng, tạo mảng trống
if (!$customerData) {
    $customerData = [
        'MaKhachHang' => '',
        'TenKhachHang' => $username,
        'DiaChi' => '',
        'Email' => $email,
        'SDT' => '',
        'UserID' => $userID
    ];
}

// Kiểm tra thông báo từ cập nhật email
$notificationMessage = '';
$notificationType = ''; // success, danger, info
if (isset($_SESSION['email_message'])) {
    $notificationMessage = $_SESSION['email_message'];
    $notificationType = 'info';
    unset($_SESSION['email_message']);
}

// Xử lý cập nhật thông tin cá nhân
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $tenKhachHang = $_POST['ten_khach_hang'];
    $diaChi = $_POST['dia_chi'];
    $sdt = $_POST['sdt'];

    // Kiểm tra xem đã có thông tin khách hàng chưa
    if ($customerData['MaKhachHang']) {
        // Cập nhật thông tin khách hàng
        $query = "UPDATE KhachHang SET TenKhachHang = ?, DiaChi = ?, SDT = ? WHERE UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $tenKhachHang, $diaChi, $sdt, $userID);
    } else {
        // Tạo mã khách hàng mới
        $query = "SELECT MAX(CAST(SUBSTRING(MaKhachHang, 3) AS UNSIGNED)) AS max_id FROM KhachHang";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $newId = ($row['max_id'] ?? 0) + 1;
        $maKhachHang = 'KH' . str_pad($newId, 3, '0', STR_PAD_LEFT);

        // Thêm thông tin khách hàng mới
        $query = "INSERT INTO KhachHang (MaKhachHang, TenKhachHang, DiaChi, Email, SDT, UserID) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $maKhachHang, $tenKhachHang, $diaChi, $email, $sdt, $userID);
    }

    if ($stmt->execute()) {
        $notificationMessage = 'Cập nhật thông tin thành công!';
        $notificationType = 'success';

        // Cập nhật lại thông tin khách hàng
        $query = "SELECT * FROM KhachHang WHERE UserID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $customerData = $result->fetch_assoc();
    } else {
        $notificationMessage = 'Cập nhật thông tin thất bại: ' . $conn->error;
        $notificationType = 'danger';
    }
}

// Xử lý cập nhật mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Kiểm tra mật khẩu hiện tại
    if ($currentPassword === $userData['Password']) {
        // Kiểm tra mật khẩu mới và xác nhận mật khẩu
        if ($newPassword === $confirmPassword) {
            // Cập nhật mật khẩu
            $query = "UPDATE LoaiTaiKhoan SET Password = ? WHERE UserID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $newPassword, $userID);

            if ($stmt->execute()) {
                $notificationMessage = 'Cập nhật mật khẩu thành công!';
                $notificationType = 'success';
            } else {
                $notificationMessage = 'Cập nhật mật khẩu thất bại: ' . $conn->error;
                $notificationType = 'danger';
            }
        } else {
            $notificationMessage = 'Mật khẩu mới và xác nhận mật khẩu không khớp!';
            $notificationType = 'danger';
        }
    } else {
        $notificationMessage = 'Mật khẩu hiện tại không đúng!';
        $notificationType = 'danger';
    }
}

// Đóng kết nối
$conn->close();

// Include header
include __DIR__ . "/View/header.php";
?>

<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Thông tin cá nhân</span>
          <h1 class="text-capitalize mb-5 text-lg">Hồ sơ của tôi</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section profile">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="card shadow">
          <div class="card-body">
            <h3 class="mb-4">Thông tin cá nhân</h3>

            <form method="post" action="">
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="ten_khach_hang">Họ và tên</label>
                    <input type="text" class="form-control" id="ten_khach_hang" name="ten_khach_hang" value="<?php echo htmlspecialchars($customerData['TenKhachHang']); ?>" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="sdt">Số điện thoại</label>
                    <input type="tel" class="form-control" id="sdt" name="sdt" value="<?php echo htmlspecialchars($customerData['SDT']); ?>">
                  </div>
                </div>
              </div>

              <div class="form-group mb-4">
                <label for="dia_chi">Địa chỉ</label>
                <input type="text" class="form-control" id="dia_chi" name="dia_chi" value="<?php echo htmlspecialchars($customerData['DiaChi']); ?>">
              </div>

              <div class="form-group mb-4">
                <label for="email">Email</label>
                <div class="input-group">
                  <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                  <div class="input-group-append">
                    <a href="#" class="btn btn-outline-primary" data-toggle="modal" data-target="#emailModal">Cập nhật</a>
                  </div>
                </div>
              </div>

              <div class="form-group mb-4">
                <label>Mật khẩu</label>
                <div class="input-group">
                  <input type="password" class="form-control" value="••••••••" readonly>
                  <div class="input-group-append">
                    <a href="#" class="btn btn-outline-primary" data-toggle="modal" data-target="#passwordModal">Cập nhật</a>
                  </div>
                </div>
              </div>

              <div class="text-center">
                <button type="submit" name="update_profile" class="btn btn-main btn-round-full">Lưu thay đổi</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal thông báo -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notificationModalLabel">Thông báo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body" id="notificationMessage">
        <!-- Nội dung thông báo sẽ được thêm bằng JavaScript -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal cập nhật Email -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emailModalLabel">Cập nhật Email</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form method="post" action="update_email.php">
        <div class="modal-body">
          <div class="form-group">
            <label for="new_email">Email mới</label>
            <input type="email" class="form-control" id="new_email" name="new_email" required>
          </div>
          <div class="form-group">
            <label for="password_confirm">Mật khẩu xác nhận</label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal cập nhật Mật khẩu -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordModalLabel">Cập nhật Mật khẩu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form method="post" action="">
        <div class="modal-body">
          <div class="form-group">
            <label for="current_password">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
          </div>
          <div class="form-group">
            <label for="new_password">Mật khẩu mới</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
          </div>
          <div class="form-group">
            <label for="confirm_password">Xác nhận mật khẩu mới</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
          <button type="submit" name="update_password" class="btn btn-primary">Cập nhật</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.profile .card {
  border-radius: 10px;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  margin-bottom: 30px;
}

.profile .form-control {
  border-radius: 5px;
  padding: 10px 15px;
  height: auto;
}

.profile label {
  font-weight: 600;
  margin-bottom: 8px;
}

.btn-outline-primary {
  color: #223a66;
  border-color: #223a66;
}

.btn-outline-primary:hover {
  background-color: #223a66;
  color: white;
}

/* Style cho modal thông báo */
#notificationModal .modal-content {
  border-radius: 10px;
}

#notificationModal .modal-header {
  background-color: #223a66;
  color: white;
}

#notificationModal .modal-body {
  font-size: 16px;
}

#notificationModal.success .modal-header {
  background-color: #28a745;
}

#notificationModal.danger .modal-header {
  background-color: #dc3545;
}

#notificationModal.info .modal-header {
  background-color: #17a2b8;
}
</style>

<?php include __DIR__ . "/View/footer.php"; ?>

<script>
// Hiển thị modal thông báo nếu có thông báo
document.addEventListener('DOMContentLoaded', function() {
    const notificationMessage = <?php echo json_encode($notificationMessage); ?>;
    const notificationType = <?php echo json_encode($notificationType); ?>;

    if (notificationMessage) {
        const modal = document.getElementById('notificationModal');
        const messageElement = document.getElementById('notificationMessage');
        
        // Đặt nội dung thông báo
        messageElement.textContent = notificationMessage;

        // Thêm lớp tương ứng với loại thông báo
        modal.classList.remove('success', 'danger', 'info');
        if (notificationType) {
            modal.classList.add(notificationType);
        }

        // Hiển thị modal
        $(modal).modal('show');

        // Tự động ẩn sau 3 giây
        setTimeout(function() {
            $(modal).modal('hide');
        }, 3000);
    }
});
</script>