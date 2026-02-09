<?php
session_start();
require_once './model/dcFrontend.php';

// Include header
include 'View/header.php';

// Lấy user_id từ username
if (!isset($_SESSION['username'])) {
    header('Location: ./View/signin.php');
    exit;
}

$userId = getUserIDByUsername($_SESSION['username']);
if (!$userId) {
    header('Location: ./View/signin.php');
    exit;
}

// Khởi tạo các biến mặc định để tránh lỗi Undefined variable
$cartItems = [];
$totalAmount = 0;
$selectedCount = 0;

if ($userId !== null) {
    $cartItems = getCartItems($userId);
    if (count($cartItems) > 0) {
        $totalAmount = 0;
        $selectedCount = count($cartItems); // Số lượng sản phẩm được chọn ban đầu
        foreach ($cartItems as $item) {
            $totalAmount += $item['ThanhTien'];
        }
    }
}
?>

<input type="hidden" name="userId" value="<?php echo htmlspecialchars($userId); ?>">

<div class="container mt-5 cart-page">
<div class="d-flex custom-flex-column mb-4">
    <h1 id="cart-title" class="mb-0">Giỏ hàng của bạn</h1>
    <a href="#" id="back-to-cart-link" class="d-none text-primary"><i class="fas fa-arrow-left"></i> Quay lại giỏ hàng</a>
</div>
    <div class="row">
        <!-- Phần danh sách sản phẩm và form thông tin bên trái -->
        <div class="col-md-8">
            <div id="cart-section">
                <?php
                if ($userId !== null) {
                    if (count($cartItems) > 0) {
                        ?>
                        <div class="cart-items-container">
                            <table class="table table-bordered cart-table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 40%;">
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" id="select-all" class="me-2")
                                                <label for="select-all"> Chọn tất cả (<?php echo $selectedCount; ?>)</label>
                                            </div>
                                        </th>
                                        <th scope="col" class="quantity-col" style="width: 20%;">Số lượng</th>
                                        <th scope="col" style="width: 10%;">Đơn vị</th>
                                        <th scope="col" style="width: 20%;">Thành tiền</th>
                                        <th scope="col" class="action-col" style="width: 10%;">Tùy chọn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($cartItems as $item) {
                                        ?>
                                        <tr data-thanh-tien="<?php echo $item['ThanhTien']; ?>">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox" class="select-item me-2" data-magiohang="<?php echo $item['MaGioHang']; ?>" checked>
                                                    <img src="<?php echo isset($item['HinhAnh']) ? htmlspecialchars($item['HinhAnh']) : 'default-image.jpg'; ?>"
                                                         alt="<?php echo isset($item['TenSanPham']) ? htmlspecialchars($item['TenSanPham']) : 'Sản phẩm không tên'; ?>"
                                                         class="mx-2" style="width: 50px; height: auto;">
                                                    <span><?php echo isset($item['TenSanPham']) ? htmlspecialchars($item['TenSanPham']) : 'Sản phẩm không tên'; ?></span>
                                                </div>
                                            </td>
                                            <td class="quantity-col">
                                                <div class="input-group quantity-control" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary decrease-quantity" type="button" data-magiohang="<?php echo $item['MaGioHang']; ?>">-</button>
                                                    <input type="text" class="form-control text-center quantity-input" value="<?php echo $item['SoLuong']; ?>" readonly>
                                                    <button class="btn btn-outline-secondary increase-quantity" type="button" data-magiohang="<?php echo $item['MaGioHang']; ?>">+</button>
                                                </div>
                                            </td>
                                            <td>Hộp</td>
                                            <td>
                                                <span class="thanh-tien"><?php echo number_format($item['ThanhTien'], 0, ',', '.'); ?>đ</span>
                                            </td>
                                            <td class="action-col">
                                                <button class="btn btn-link remove-item" data-magiohang="<?php echo $item['MaGioHang']; ?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    } else {
                        echo '<p>Giỏ hàng của bạn đang trống.</p>';
                    }
                } else {
                    echo '<p>Vui lòng đăng nhập để xem giỏ hàng.</p>';
                }
                ?>
            </div>

            <!-- Form thông tin địa chỉ nhận hàng và hình thức thanh toán (ẩn ban đầu) -->
            <div id="checkout-form-section" class="checkout-form-container d-none">
                <div class="delivery-options mb-4">
                    <h5 class="section-title">Chọn hình thức nhận hàng</h5>
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-delivery-option active" data-option="delivery">
                            <i class="fas fa-truck me-2"></i>Giao hàng tận nơi
                        </button>
                        <button class="btn btn-delivery-option" data-option="pickup">
                            <i class="fas fa-store me-2"></i>Nhận tại nhà thuốc
                        </button>
                    </div>
                </div>

                <div id="delivery-form">
                    <div class="address-section mt-4">
                        <h5 class="section-title">Địa chỉ nhận hàng</h5>
                        <form id="delivery-info-form">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="receiver-name" name="receiver-name" placeholder="Họ và tên người nhận" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="tel" class="form-control" id="receiver-phone" name="receiver-phone" placeholder="Số điện thoại" required>
                            </div>
                            <div class="form-group mb-3">
                                <div class="location-select">
                                    <div class="select-container">
                                        <input type="text" class="form-control location-input" id="province" name="province" placeholder="Chọn tỉnh/thành phố" readonly required>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                    <div class="location-dropdown province-dropdown" style="display: none;">
                                        <div class="search-box">
                                            <input type="text" class="form-control" placeholder="Nhập tìm tỉnh/thành phố">
                                        </div>
                                        <div class="location-list">
                                            <!-- Danh sách tỉnh/thành phố sẽ được điền bằng JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="location-select">
                                    <div class="select-container">
                                        <input type="text" class="form-control location-input" id="district" name="district" placeholder="Chọn quận/huyện" readonly disabled required>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                    <div class="location-dropdown district-dropdown" style="display: none;">
                                        <div class="location-list">
                                            <!-- Sẽ được điền bằng JavaScript khi chọn tỉnh/thành -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="location-select">
                                    <div class="select-container">
                                        <input type="text" class="form-control location-input" id="ward" name="ward" placeholder="Chọn phường/xã" readonly disabled required>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                    <div class="location-dropdown ward-dropdown" style="display: none;">
                                        <div class="location-list">
                                            <!-- Sẽ được điền bằng JavaScript khi chọn quận/huyện -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="specific-address" name="specific-address" placeholder="Nhập địa chỉ cụ thể">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Ghi chú (không bắt buộc)
                                    Ví dụ: Hãy gọi cho tôi 15 phút trước khi giao"></textarea>
                            </div>
                        </form>
                    </div>

                    <div class="invoice-section mt-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="invoice-request">
                            <label class="form-check-label" for="invoice-request">Yêu cầu xuất hóa đơn điện tử</label>
                        </div>
                    </div>
                </div>

                <div id="pickup-form" class="d-none">
                    <h5 class="section-title">Chọn nhà thuốc</h5>
                    <select class="form-select mb-3" name="pickup-store" required>
                        <option value="">Chọn nhà thuốc gần bạn nhất</option>
                        <!-- Thêm danh sách nhà thuốc -->
                        <option value="store1">Nhà thuốc eaut1 - Bắc Ninh</option>
                        <option value="store2">Nhà thuốc eaut2 - Hà Nội</option>
                    </select>
                </div>

                <div class="payment-section mt-4">
                    <h5 class="section-title">Chọn phương thức thanh toán</h5>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" class="form-check-input" name="payment-method" id="cod" value="cod" checked>
                            <label for="cod">
                                <span>Thanh toán khi nhận hàng</span>
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" class="form-check-input" name="payment-method" id="bank-transfer" value="transfer">
                            <label for="bank-transfer">
                                <span>Thanh toán chuyển khoản</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phần thanh toán bên phải -->
        <div class="col-md-4">
            <div class="checkout-summary">
                <div class="summary-item">
                    <span>Tổng tiền</span>
                    <span id="total-amount"><?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</span>
                </div>
                <div class="summary-item discount d-none">
                    <span>Giảm giá trực tiếp</span>
                    <span id="direct-discount">0đ</span>
                </div>
                <div class="summary-item voucher-section">
                    <div class="input-group">
                        <input type="text" class="form-control" id="voucher-code" placeholder="Nhập mã giảm giá">
                        <button class="btn btn-outline-primary apply-voucher" type="button">Áp dụng</button>
                    </div>
                    <small class="voucher-error text-danger d-none">Mã giảm giá không hợp lệ</small>
                </div>
                <div class="summary-item">
                    <span>Giảm giá voucher</span>
                    <span id="voucher-discount" data-discount="0">0đ</span>
                </div>
                <div class="summary-item shipping-fee d-none">
                    <span>Phí vận chuyển</span>
                    <span id="shipping-fee-amount">30,000đ</span>
                </div>
                <div class="summary-item savings">
                    <span>Tiết kiệm được</span>
                    <span id="savings">0đ</span>
                </div>
                <div class="summary-item final-amount">
                    <span>Thành tiền</span>
                    <span id="final-amount"><?php echo number_format($totalAmount, 0, ',', '.'); ?>đ</span>
                </div>
                <div id="action-buttons">
                    <button type="button" class="btn btn-primary btn-checkout" id="checkout-btn">Mua hàng</button>
                    <button type="button" class="btn btn-primary btn-confirm-order d-none" id="confirm-order-btn">Hoàn tất</button>
                </div>
                <p class="checkout-note">Bằng việc tiến hành đặt mua hàng, bạn đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách xử lý dữ liệu</a> của Nhà thuốc EAUT</p>
            </div>
        </div>
    </div>
</div>

<?php
include 'View/footer.php';
?>


<script src="./js/cart.js"></script>
<script src="./js/location.js"></script>
