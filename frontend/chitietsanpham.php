<?php
session_start();
require_once './model/dcFrontend.php';

// Lấy UserID từ username
$userId = null;
if (isset($_SESSION['username'])) {
    $userId = getUserIDByUsername($_SESSION['username']);
}

include './View/header.php';

// Lấy MaSanPham từ URL
$maSanPham = isset($_GET['id']) ? $_GET['id'] : '';
$product = getProductById($maSanPham);

if (!$product) {
    echo "<p class='text-center'>Sản phẩm không tồn tại.</p>";
    include './View/footer.php';
    exit;
}
?>
<link rel="stylesheet" href="css/detail_medicine.css">

<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <h1 class="text-capitalize mb-5 text-lg">Chi Tiết Sản Phẩm</h1>
          <ul class="list-inline breadcumb-nav">
            <li class="list-inline-item"><a href="./indexx.php" class="text-white">Trang Chủ</a></li>
            <li class="list-inline-item"><span class="text-white">/</span></li>
            <li class="list-inline-item"><span class="text-white-50">Chi Tiết Sản Phẩm</span></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section Chi tiết sản phẩm -->
<section class="section product-detail">
    <div class="container">
        <div class="row align-items-center">
            <!-- Hình ảnh sản phẩm -->
            <div class="col-lg-6 col-md-6 mb-4 mb-lg-0">
                <div class="product-image-container">
                    <img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>" alt="<?php echo htmlspecialchars($product['TenSanPham']); ?>" class="product-image">
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-lg-6 col-md-6">
                <div class="product-info-simple">
                    <h1 class="product-title-simple"><?php echo htmlspecialchars($product['TenSanPham']); ?></h1>
                    <div class="price-simple"><?php echo number_format($product['Gia'], 0, ',', '.'); ?> VNĐ</div>

                    <div class="product-details-simple">
                        <div class="detail-row">
                            <span class="label">Đối tượng sử dụng:</span>
                            <span class="value">
                                <?php
                                switch ($product['DoiTuong']) {
                                    case 'TreEm':
                                        echo 'Trẻ em';
                                        break;
                                    case 'NguoiLon':
                                        echo 'Người lớn';
                                        break;
                                    case 'CaHai':
                                        echo 'Cả hai';
                                        break;
                                    default:
                                        echo 'Không xác định';
                                }
                                ?>
                            </span>
                        </div>

                        <div class="detail-row">
                            <span class="label">Số lượng còn lại:</span>
                            <span class="value stock"><?php echo htmlspecialchars($product['SoLuongTon']); ?> sản phẩm</span>
                        </div>
                    </div>

                    <!-- Form chọn số lượng -->
                    <div class="quantity-section-simple">
                        <span class="quantity-label-simple">Số lượng:</span>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn" onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" name="quantity" min="1" max="<?php echo htmlspecialchars($product['SoLuongTon']); ?>" value="1" class="qty-input">
                            <button type="button" class="qty-btn" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>

                    <!-- Nút hành động -->
                    <div class="action-buttons-simple">
                        <button type="button" id="buy-now" class="btn-add-cart">
                            <i class="icofont-cart me-2"></i>THÊM VÀO GIỎ HÀNG
                        </button>
                        <button type="button" class="btn-back-simple" onclick="history.back()">
                            <i class="icofont-arrow-left me-2"></i>QUAY LẠI
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phần thông tin chi tiết sản phẩm -->
        <div class="row product-info-tables">
            <!-- Bảng thông tin sản phẩm -->
            <div class="col-lg-8 col-md-12">
                <!-- Bảng Mô tả -->
                <div class="info-table">
                    <div class="table-header">
                        <i class="icofont-info-circle me-2"></i>Mô Tả
                    </div>
                    <div class="table-content">
                        <?php
                        $moTa = htmlspecialchars($product['MoTa']);
                        $paragraphs = explode("\n", $moTa);
                        foreach ($paragraphs as $paragraph) {
                            if (trim($paragraph)) {
                                echo "<p>" . trim($paragraph) . "</p>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Bảng Cách dùng -->
                <div class="info-table">
                    <div class="table-header">
                        <i class="icofont-pills me-2"></i>Cách Dùng
                    </div>
                    <div class="table-content">
                        <?php
                        $cachDung = htmlspecialchars($product['CachDung']);
                        $paragraphs = explode("\n", $cachDung);
                        foreach ($paragraphs as $paragraph) {
                            if (trim($paragraph)) {
                                echo "<p>" . trim($paragraph) . "</p>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Bảng Thành phần -->
                <div class="info-table">
                    <div class="table-header">
                        <i class="icofont-molecule me-2"></i>Thành Phần
                    </div>
                    <div class="table-content">
                        <?php
                        $thanhPhan = htmlspecialchars($product['ThanhPhan']);
                        $paragraphs = explode("\n", $thanhPhan);
                        foreach ($paragraphs as $paragraph) {
                            if (trim($paragraph)) {
                                echo "<p>" . trim($paragraph) . "</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Cột bên phải: Lưu ý và hỗ trợ -->
            <div class="col-lg-4 col-md-12">
                <!-- Lưu ý quan trọng -->
                <div class="warning-horizontal">
                    <div class="warning-header">
                        <i class="icofont-warning-alt me-2"></i>Lưu Ý Quan Trọng
                    </div>

                    <div class="warning-content-horizontal">
                        <div class="warning-column">
                            <h6>Chống Chỉ Định</h6>
                            <ul>
                                <li>Quá mẫn với bất kỳ thành phần nào của sản phẩm</li>
                                <li>Phụ nữ có thai và cho con bú</li>
                                <li>Trẻ em dưới 12 tuổi</li>
                            </ul>
                        </div>

                        <div class="warning-column">
                            <h6>Thận Trọng Khi Sử Dụng</h6>
                            <p>Nên tham khảo ý kiến bác sĩ trước khi sử dụng, đặc biệt với người có tiền sử bệnh lý.</p>
                            <p>Ngừng sử dụng nếu có phản ứng bất thường.</p>
                        </div>

                        <div class="warning-column">
                            <h6>Bảo Quản</h6>
                            <p>Bảo quản nơi khô ráo, thoáng mát, tránh ánh sáng trực tiếp.</p>
                            <p>Để xa tầm tay trẻ em.</p>
                        </div>
                    </div>
                </div>

                <!-- Thông tin hỗ trợ -->
                <div class="support-simple">
                    <div class="support-header">
                        <i class="icofont-support-faq me-2"></i>Cần Hỗ Trợ?
                    </div>
                    <div class="support-content">
                        <div class="support-row">
                            <i class="icofont-phone"></i>
                            <span>Hotline: 1900-xxxx</span>
                        </div>
                        <div class="support-row">
                            <i class="icofont-email"></i>
                            <span>Email: support@healthcare.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php
include './View/footer.php';
?>


<script>
    // Quantity control functions
    function increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        const maxValue = parseInt(quantityInput.max);

        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
        }
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        const minValue = parseInt(quantityInput.min);

        if (currentValue > minValue) {
            quantityInput.value = currentValue - 1;
        }
    }

    // Buy now functionality
    document.getElementById('buy-now').addEventListener('click', function(event) {
        event.preventDefault();
        const quantity = document.getElementById('quantity').value;
        const maSanPham = '<?php echo $maSanPham; ?>';

        // Show loading state
        const button = this;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="icofont-spinner-alt-3 icofont-spin me-2"></i>Đang thêm...';
        button.disabled = true;

        const formData = new FormData();
        formData.append('maSanPham', maSanPham);
        formData.append('soLuong', quantity);

        fetch('./model/add_to_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message with better styling
                showNotification('Đã thêm sản phẩm vào giỏ hàng thành công!', 'success');

                // Update cart count
                fetch('./model/get_cart_count.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(countData => {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = countData.count;
                        // Add animation to cart icon
                        cartCountElement.parentElement.classList.add('cart-updated');
                        setTimeout(() => {
                            cartCountElement.parentElement.classList.remove('cart-updated');
                        }, 1000);
                    }
                })
                .catch(error => {
                    console.error('Lỗi lấy số lượng giỏ hàng:', error);
                });
            } else {
                showNotification(data.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra khi thêm vào giỏ hàng.', 'error');
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalText;
            button.disabled = false;
        });
    });

    // Notification function
    function showNotification(message, type) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `custom-notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="icofont-${type === 'success' ? 'check-circled' : 'warning-alt'} me-2"></i>
                ${message}
            </div>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scrolling to info tables
        const infoTables = document.querySelectorAll('.info-table');
        infoTables.forEach(table => {
            table.addEventListener('click', function() {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    });
</script>

<!-- Additional CSS for notifications and animations -->
<style>
    .custom-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        z-index: 9999;
        transform: translateX(400px);
        transition: transform 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .custom-notification.success {
        background: linear-gradient(45deg, #28a745, #20c997);
    }

    .custom-notification.error {
        background: linear-gradient(45deg, #dc3545, #c82333);
    }

    .custom-notification.show {
        transform: translateX(0);
    }

    .cart-updated {
        animation: cartBounce 0.6s ease;
    }

    @keyframes cartBounce {
        0%, 20%, 60%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        80% {
            transform: translateY(-5px);
        }
    }

    .icofont-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>