<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once './model/dcFrontend.php';

// Include header
include __DIR__ . "/View/header.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    echo '<div class="container mt-5"><p class="text-danger">Vui lòng đăng nhập để xem đơn hàng.</p></div>';
    include __DIR__ . "/View/footer.php";
    exit;
}

$userId = getUserIDByUsername($_SESSION['username']);
if (!$userId) {
    echo '<div class="container mt-5"><p class="text-danger">Không tìm thấy thông tin khách hàng. Vui lòng đăng nhập lại.</p></div>';
    include __DIR__ . "/View/footer.php";
    exit;
}

// Xử lý hủy đơn hàng
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['maDonHang'])) {
    $maDonHang = trim($_GET['maDonHang']);
    $order = getOrderByMaDonHang($maDonHang, $userId);
    if ($order) {
        if ($order['TenTrangThai'] === 'Chờ xử lý') {
            $result = deleteOrder($maDonHang);
            if ($result['success']) {
                $_SESSION['message'] = ['type' => 'success', 'text' => 'Xóa đơn hàng thành công.'];
            } else {
                $_SESSION['message'] = ['type' => 'error', 'text' => 'Xóa đơn hàng thất bại: ' . htmlspecialchars($result['message'])];
            }
        } else {
            $_SESSION['message'] = ['type' => 'error', 'text' => 'Chỉ có thể xóa đơn hàng ở trạng thái "Chờ xử lý".'];
        }
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Đơn hàng không tồn tại hoặc bạn không có quyền xóa đơn hàng này.'];
    }
    // Redirect về trang order_history với maDonHang rỗng
    header("Location: http://localhost:3000/Frontend/order_history.php?maDonHang=");
    exit;
}

// Lấy tham số từ form hoặc URL
$maDonHang = isset($_GET['maDonHang']) ? trim($_GET['maDonHang']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;

// Gọi hàm filterOrders để lấy danh sách đơn hàng
$result = filterOrders($userId, $maDonHang, $status, $page, $limit);

if (!$result['success']) {
    echo '<div class="container mt-5"><p class="text-danger">' . htmlspecialchars($result['message']) . '</p></div>';
    include __DIR__ . "/View/footer.php";
    exit;
}

$orders = $result['orders'];
$totalPages = $result['totalPages'];
$currentPage = $result['currentPage'];
?>

<div class="order-history">
    <?php
    // Hiển thị thông báo nếu có
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        echo '<div class="container mt-5"><p class="text-' . ($message['type'] === 'success' ? 'success' : 'danger') . '">' . $message['text'] . '</p></div>';
        unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị
    }
    ?>
    <h2>Lịch sử đơn hàng</h2>

    <!-- Thanh tìm kiếm -->
    <form method="GET" action="order_history.php" class="search-form" id="searchForm">
        <div class="search-bar">
            <input type="text" id="searchMaDonHang" name="maDonHang" placeholder="Tìm kiếm theo mã đơn hàng..." value="<?php echo htmlspecialchars($maDonHang); ?>" onkeypress="if(event.key === 'Enter') { event.preventDefault(); document.getElementById('searchForm').submit(); }">
            <i class="fas fa-search search-icon"></i>
        </div>
    </form>

    <!-- Bộ lọc trạng thái -->
    <div class="status-filter">
        <form method="GET" action="order_history.php">
            <input type="hidden" name="maDonHang" value="<?php echo htmlspecialchars($maDonHang); ?>">
            <button type="submit" name="status" value="all" class="filter-btn <?php echo $status === 'all' ? 'active' : ''; ?>">Tất cả</button>
            <button type="submit" name="status" value="Chờ xử lý" class="filter-btn <?php echo $status === 'Chờ xử lý' ? 'active' : ''; ?>">Chờ xử lý</button>
            <button type="submit" name="status" value="Đang xử lý" class="filter-btn <?php echo $status === 'Đang xử lý' ? 'active' : ''; ?>">Đang xử lý</button>
            <button type="submit" name="status" value="Đã hoàn thành" class="filter-btn <?php echo $status === 'Đã hoàn thành' ? 'active' : ''; ?>">Đã hoàn thành</button>
            <button type="submit" name="status" value="Đã hủy" class="filter-btn <?php echo $status === 'Đã hủy' ? 'active' : ''; ?>">Đã hủy</button>
        </form>
    </div>

    <!-- Danh sách đơn hàng -->
    <div id="orderList">
        <?php if (empty($orders)): ?>
            <p class="no-orders">Chưa có đơn hàng nào.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-item">
                    <!-- Mã đơn hàng, trạng thái, ngày đặt hàng trên cùng một hàng -->
                    <div class="order-header">
                        <span class="order-id">Mã đơn hàng: <?php echo htmlspecialchars($order['MaDonHang']); ?></span>
                        <span class="order-status" style="color: <?php
                            echo $order['TenTrangThai'] == 'Chờ xử lý' ? 'orange' :
                                 ($order['TenTrangThai'] == 'Đang xử lý' ? 'blue' :
                                 ($order['TenTrangThai'] == 'Đã hủy' ? 'red' : 'green'));
                        ?>;">
                            <?php echo htmlspecialchars($order['TenTrangThai']); ?>
                        </span>
                        <span class="order-date">Ngày đặt: <?php echo htmlspecialchars($order['NgayDatHang']); ?></span>
                        <?php if ($order['TenTrangThai'] === 'Chờ xử lý'): ?>
                            <a href="order_history.php?action=delete&maDonHang=<?php echo urlencode($order['MaDonHang']); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $currentPage; ?>" class="delete-order" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này? Hành động này không thể hoàn tác!');">Xóa đơn hàng</a>
                        <?php endif; ?>
                    </div>

                    <!-- Danh sách sản phẩm -->
                    <div class="order-products">
                        <table class="no-header-no-border">
                            <tbody>
                                <?php
                                $stt = 1;
                                if ($order['TenTrangThai'] === 'Đã hủy') {
                                    // echo '<tr><td colspan="5">Đơn hàng đã bị hủy, Xem chi tiết đơn hủy.</td></tr>';
                                } elseif (empty($order['details'])) {
                                    echo '<tr><td colspan="5">Không có sản phẩm trong đơn hàng này.</td></tr>';
                                } else {
                                    foreach ($order['details'] as $detail):
                                ?>
                                        <tr>
                                            <td><img src="<?php echo htmlspecialchars($detail['HinhAnhSanPham']); ?>" alt="<?php echo htmlspecialchars($detail['TenSanPham']); ?>"></td>
                                            <td><?php echo htmlspecialchars($detail['TenSanPham']); ?></td>
                                            <td><?php echo number_format($detail['Gia'], 0, ',', '.'); ?>đ</td>
                                            <td>x<?php echo $detail['SoLuong']; ?></td>
                                            <td class="total-price"><?php echo number_format($detail['TongGia'], 0, ',', '.'); ?>đ</td>
                                        </tr>
                                <?php endforeach;
                                } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="order-footer">
                        <p class="total">Thành tiền: <?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</p>
                        <a href="javascript:void(0)" class="toggle-details" onclick="toggleDetails('<?php echo $order['MaDonHang']; ?>')">Xem chi tiết</a>
                    </div>

                    <!-- Chi tiết đơn hàng -->
                    <div id="details-<?php echo $order['MaDonHang']; ?>" class="order-details">
                        <h4>Chi tiết đơn hàng</h4>
                        <p><strong>Mã đơn hàng:</strong> <?php echo htmlspecialchars($order['MaDonHang']); ?></p>
                        <p><strong>Ngày đặt hàng:</strong> <?php echo htmlspecialchars($order['NgayDatHang']); ?></p>
                        <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($order['TenTrangThai']); ?></p>
                        <p><strong>Tên khách hàng:</strong> <?php echo htmlspecialchars($order['HoTenNguoiNhan']); ?></p>
                        <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['SoDienThoai']); ?></p>
                        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['DiaChi']); ?></p>
                        <p><strong>Hình thức thanh toán:</strong> <?php echo htmlspecialchars($order['PhuongThucThanhToan'] == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản'); ?></p>
                        <p><strong>Hình thức nhận hàng:</strong> <?php echo htmlspecialchars($order['HinhThucNhanHang'] == 'delivery' ? 'Giao hàng tận nơi' : 'Nhận tại cửa hàng'); ?></p>
                        <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($order['GhiChu'] ?? 'Không có'); ?></p>

                        <!-- Danh sách sản phẩm -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Hình ảnh</th>
                                    <th>Tiêu đề sản phẩm</th>
                                    <th>Giá bán</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($order['TenTrangThai'] === 'Đã hủy'): ?>
                                    <tr><td colspan="5">Đơn hàng đã bị hủy, không có chi tiết sản phẩm.</td></tr>
                                <?php elseif (empty($order['details'])): ?>
                                    <tr><td colspan="5">Không có sản phẩm trong đơn hàng này.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($order['details'] as $detail): ?>
                                        <tr>
                                            <td><img src="<?php echo htmlspecialchars($detail['HinhAnhSanPham']); ?>" alt="<?php echo htmlspecialchars($detail['TenSanPham']); ?>"></td>
                                            <td><?php echo htmlspecialchars($detail['TenSanPham']); ?></td>
                                            <td><?php echo number_format($detail['Gia'], 0, ',', '.'); ?>đ</td>
                                            <td>x<?php echo $detail['SoLuong']; ?></td>
                                            <td><?php echo number_format($detail['TongGia'], 0, ',', '.'); ?>đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <p><strong>Tổng:</strong> <?php echo number_format($order['TongTien'], 0, ',', '.'); ?>đ</p>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Phân trang -->
            <?php if ($totalPages > 1): ?>
                <nav class="pagination-nav" id="pagination">
                    <ul>
                        <?php if ($currentPage > 1): ?>
                            <li><a href="order_history.php?maDonHang=<?php echo urlencode($maDonHang); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $currentPage - 1; ?>">« Trang trước</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li><a href="order_history.php?maDonHang=<?php echo urlencode($maDonHang); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $i; ?>" class="<?php echo $i == $currentPage ? 'active' : ''; ?>"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                        <?php if ($currentPage < $totalPages): ?>
                            <li><a href="order_history.php?maDonHang=<?php echo urlencode($maDonHang); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $currentPage + 1; ?>">Trang sau »</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<?php include __DIR__ . "/View/footer.php"; ?>



<script>
    function toggleDetails(maDonHang) {
        const details = document.getElementById('details-' + maDonHang);
        if (details.style.display === 'block') {
            details.style.display = 'none';
        } else {
            details.style.display = 'block';
        }
    }

    // Xử lý tìm kiếm khi nhấn Enter hoặc nhấp vào icon
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchMaDonHang');
        const searchForm = document.getElementById('searchForm');
        const searchIcon = document.querySelector('.search-icon');

        searchInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchForm.submit();
            }
        });

        // Cho phép nhấp vào icon để tìm kiếm
        searchIcon.addEventListener('click', function() {
            searchForm.submit();
        });
    });
</script>

<!-- Scripts -->
<script src="plugins/jquery/jquery.js"></script>
<script src="plugins/bootstrap/js/popper.js"></script>
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/counterup/jquery.easing.js"></script>
<script src="plugins/slick-carousel/slick/slick.min.js"></script>
<script src="plugins/counterup/jquery.waypoints.min.js"></script>
<script src="plugins/shuffle/shuffle.min.js"></script>
<script src="plugins/counterup/jquery.counterup.min.js"></script>
<script src="plugins/google-map/map.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>
<script src="js/script.js"></script>
<script src="js/contact.js"></script>
</body>
</html>
<?php
ob_end_flush();
?>