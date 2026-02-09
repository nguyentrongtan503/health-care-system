<?php
session_start();
// Include file dcFrontend.php (đã include dbconnect.php)
require_once './model/dcFrontend.php';
// Tạo kết nối cơ sở dữ liệu
$conn = connectDB();

// Include header
include __DIR__ . "/View/header.php";
?>
<link rel="stylesheet" href="css/goiy.css">
<!-- Main Content -->
<section class="section service-2">
    <div class="container">
        <div class="row">
            <!-- Cột bên trái - Lịch sử -->
            <div class="col-lg-3">
                <div class="history-section">
                    <h4 class="section-title">Lịch Sử Gợi Ý</h4>
                    <div class="history-list-container" id="history-list">
                        <!-- Lịch sử sẽ được thêm vào đây bằng JavaScript -->
                    </div>
                    <button id="clearHistory" class="btn btn-outline-danger w-100 mt-3">
                        <i class="fas fa-trash-alt me-2"></i>Xóa Lịch Sử
                    </button>
                </div>
            </div>

            <!-- Cột bên phải - Form và kết quả -->
            <div class="col-lg-9">
                <div class="suggestion-form mb-4">
                    <h2 class="text-center mb-4">Nhận Gợi Ý Sức Khỏe</h2>
                    <form id="healthForm">
                        <div class="mb-3">
                            <label for="loai_san_pham" class="form-label">Loại Sản Phẩm</label>
                            <select class="form-select" id="loai_san_pham" name="loai_san_pham" required>
                                <option value="">Chọn loại sản phẩm</option>
                                <option value="Thực phẩm chức năng">Thực phẩm chức năng</option>
                                <option value="Thuốc kê đơn">Thuốc kê đơn</option>
                                <option value="Thuốc Không kê đơn">Thuốc Không kê đơn</option>
                                <option value="Vitamin">Vitamin & Khoáng chất</option>
                                <option value="Protein">Protein & Thể thao</option>
                                <option value="Omega">Omega & Dầu cá</option>
                                <option value="Probiotics">Probiotics & Tiêu hóa</option>
                                <option value="Collagen">Collagen & Làm đẹp</option>
                                <option value="Giảm cân">Hỗ trợ giảm cân</option>
                                <option value="Tăng cân">Hỗ trợ tăng cân</option>
                                <option value="Tim mạch">Hỗ trợ tim mạch</option>
                                <option value="Xương khớp">Xương khớp</option>
                                <option value="Mắt">Bảo vệ mắt</option>
                                <option value="Gan">Bảo vệ gan</option>
                                <option value="Thận">Hỗ trợ thận</option>
                                <option value="Miễn dịch">Tăng cường miễn dịch</option>
                                <option value="Não bộ">Hỗ trợ não bộ</option>
                                <option value="Stress">Giảm stress</option>
                                <option value="Ngủ">Hỗ trợ giấc ngủ</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gioi_tinh" class="form-label">Giới Tính</label>
                                <select class="form-select" id="gioi_tinh" name="gioi_tinh" required>
                                    <option value="">Chọn giới tính</option>
                                    <option value="Nam">Nam</option>
                                    <option value="Nữ">Nữ</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tuoi" class="form-label">Tuổi: <span id="tuoi-value">30</span></label>
                                <input type="range" class="form-range" id="tuoi" name="tuoi" min="1" max="120" value="30" oninput="document.getElementById('tuoi-value').textContent = this.value">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="chieu_cao" class="form-label">Chiều Cao (cm): <span id="chieu_cao-value">170</span></label>
                                <input type="range" class="form-range" id="chieu_cao" name="chieu_cao" min="50" max="250" value="170" oninput="document.getElementById('chieu_cao-value').textContent = this.value">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="can_nang" class="form-label">Cân Nặng (kg): <span id="can_nang-value">60</span></label>
                                <input type="range" class="form-range" id="can_nang" name="can_nang" min="20" max="200" value="60" oninput="document.getElementById('can_nang-value').textContent = this.value">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tinh_trang_suc_khoe" class="form-label">Tình Trạng Sức Khỏe</label>
                            <textarea class="form-control" id="tinh_trang_suc_khoe" name="tinh_trang_suc_khoe" rows="3"
                                placeholder="Ví dụ: tiểu đường, huyết áp cao, dị ứng..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="so_thich" class="form-label">Sở Thích</label>
                            <textarea class="form-control" id="so_thich" name="so_thich" rows="2"
                                placeholder="Ví dụ: tập gym, yoga, chạy bộ..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Nhận Gợi Ý</button>
                        </div>
                    </form>
                </div>

                <!-- Kết quả gợi ý -->
                <div id="suggestion-result" class="result-card loading">
                    <div class="row">
                        <!-- BMI Section -->
                        <div class="col-lg-12 mb-4">
                            <div class="bmi-section">
                                <h3 class="text-center">Kết Quả Gợi Ý</h3>
                                <div class="text-center">
                                    <div class="bmi-circle">
                                        <p class="bmi-value" id="bmi-value"></p>
                                        <p class="bmi-status" id="bmi-status"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phần gợi ý -->
                        <div class="col-lg-12">
                            <div class="suggestion-section">
                                <h4 class="section-title">Gợi Ý Cho Bạn</h4>
                                <div class="suggestion-box">
                                    <h5><i class="fas fa-utensils me-2"></i>Chế Độ Ăn</h5>
                                    <ul class="suggestion-list" id="diet-suggestions"></ul>
                                </div>
                                <div class="suggestion-box">
                                    <h5><i class="fas fa-running me-2"></i>Bài Tập</h5>
                                    <ul class="suggestion-list" id="exercise-suggestions"></ul>
                                </div>
                                <div class="suggestion-box">
                                    <h5><i class="fas fa-pills me-2"></i>Thực Phẩm Chức Năng</h5>
                                    <ul class="suggestion-list" id="supplement-suggestions"></ul>
                                </div>
                            </div>
                        </div>

                        <!-- Phần sản phẩm -->
                        <div class="col-lg-12 mt-4">
                            <div class="product-section">
                                <h4 class="section-title">Sản Phẩm Phù Hợp</h4>
                                <div class="filter-section">
                                    <div class="filter-group">
                                        <div class="filter-item">
                                            <select id="categoryFilter">
                                                <option value="">Tất cả danh mục</option>
                                                <option value="vitamin">Vitamin & TPCN</option>
                                                <option value="thuoc">Thuốc</option>
                                                <option value="thietbi">Thiết bị y tế</option>
                                            </select>
                                        </div>
                                        <div class="filter-item">
                                            <select id="priceFilter">
                                                <option value="">Giá</option>
                                                <option value="0-100000">Dưới 100.000đ</option>
                                                <option value="100000-500000">100.000đ - 500.000đ</option>
                                                <option value="500000-1000000">500.000đ - 1.000.000đ</option>
                                                <option value="1000000">Trên 1.000.000đ</option>
                                            </select>
                                        </div>
                                        <div class="filter-item">
                                            <select id="sortFilter">
                                                <option value="">Sắp xếp</option>
                                                <option value="price-asc">Giá tăng dần</option>
                                                <option value="price-desc">Giá giảm dần</option>
                                                <option value="name-asc">Tên A-Z</option>
                                                <option value="name-desc">Tên Z-A</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="product-suggestions" class="product-grid"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log('jQuery đã sẵn sàng');

    // Load lịch sử khi trang được tải
    loadHistory();

    // Xử lý submit form
    $('#healthForm').on('submit', function(e) {
        console.log('Form được submit');
        e.preventDefault();
        $('#suggestion-result').addClass('loading').hide();

        // Validate form
        if (!validateForm()) {
            $('#suggestion-result').removeClass('loading');
            return;
        }

        $.ajax({
            url: './model/process_goi_y.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log('Phản hồi từ server:', response);
                $('#suggestion-result').removeClass('loading');
                if (response.success) {
                    displayResults(response.data);
                    loadHistory();
                } else {
                    alert(response.message || 'Có lỗi xảy ra');
                }
            },
            error: function(xhr, status, error) {
                $('#suggestion-result').removeClass('loading');
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi xử lý yêu cầu: ' + error);
            }
        });
    });

    // Xử lý click vào item lịch sử
    $(document).on('click', '.history-item', function() {
        const clickedItem = $(this);
        const id = clickedItem.data('id');

        if (!id) {
            console.error('Không tìm thấy ID của item lịch sử');
            return;
        }

        $('#suggestion-result').addClass('loading').hide();

        $.ajax({
            url: './model/process_goi_y.php',
            type: 'GET',
            data: { action: 'get_history', id: id },
            dataType: 'json',
            success: function(response) {
                console.log('Phản hồi từ server (lịch sử):', response);
                $('#suggestion-result').removeClass('loading');

                if (response.success) {
                    displayResults(response.data);
                    $('.history-item').removeClass('active');
                    clickedItem.addClass('active');
                } else {
                    alert(response.message || 'Có lỗi xảy ra khi tải dữ liệu lịch sử');
                }
            },
            error: function(xhr, status, error) {
                $('#suggestion-result').removeClass('loading');
                console.error('Lỗi:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);

                let errorMessage = 'Có lỗi xảy ra khi tải dữ liệu lịch sử';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch (e) {
                    console.error('Lỗi parse JSON:', e);
                }

                alert(errorMessage);
            }
        });
    });

    // Thêm xử lý sự kiện cho nút xóa tất cả
    $('#clearHistory').on('click', function() {
        if (confirm('Bạn có chắc chắn muốn xóa tất cả lịch sử gợi ý?')) {
            $.ajax({
                url: './model/process_goi_y.php',
                type: 'GET',
                data: { action: 'clear_history' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#history-list').html('<p class="text-muted">Chưa có lịch sử gợi ý</p>');
                        $('#clearHistory').hide();
                    } else {
                        alert(response.message || 'Có lỗi xảy ra khi xóa lịch sử');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error clearing history:', error);
                    alert('Có lỗi xảy ra khi xóa lịch sử');
                }
            });
        }
    });

    // Xử lý sự kiện thay đổi bộ lọc sản phẩm
    $('#categoryFilter').on('change', function() {
        const selectedType = $(this).val();
        filterProducts(selectedType);
    });

    $('#priceFilter').on('change', function() {
        const selectedRange = $(this).val();
        filterProductsByPrice(selectedRange);
    });

    $('#sortFilter').on('change', function() {
        const sortType = $(this).val();
        sortProducts(sortType);
    });

    // Xử lý click vào ảnh sản phẩm
    $(document).on('click', '.product-image', function() {
        const productId = $(this).closest('.product-card').data('id');
        window.location.href = `./chitietsanpham.php?id=${productId}`;
    });
});

// Hàm validate form
function validateForm() {
    const form = $('#healthForm');
    const requiredFields = ['loai_san_pham', 'gioi_tinh', 'tuoi', 'chieu_cao', 'can_nang'];
    let isValid = true;

    requiredFields.forEach(field => {
        const value = form.find(`[name="${field}"]`).val();
        console.log(`Giá trị của ${field}:`, value);
        if (!value) {
            alert(`Vui lòng điền đầy đủ thông tin ${field}`);
            isValid = false;
            return false;
        }
    });

    return isValid;
}

// Hàm load lịch sử
function loadHistory() {
    $.ajax({
        url: './model/process_goi_y.php',
        type: 'GET',
        data: { action: 'get_history_list' },
        dataType: 'json',
        success: function(response) {
            console.log('Phản hồi từ server:', response);

            if (response.success && Array.isArray(response.data)) {
                let output = '';
                if (response.data.length > 0) {
                    response.data.forEach(item => {
                        output += `
                            <div class="history-item mb-3" data-id="${item.id}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>${item.loai_san_pham}</strong>
                                        <br>
                                        <small class="text-muted">
                                            ${item.gioi_tinh}, ${item.tuoi} tuổi
                                            <br>BMI: ${item.bmi}
                                            <br>Chiều cao: ${item.chieu_cao}cm
                                            <br>Cân nặng: ${item.can_nang}kg
                                        </small>
                                        ${item.tinh_trang_suc_khoe ? `
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-heartbeat me-1"></i>
                                                ${item.tinh_trang_suc_khoe}
                                            </small>
                                        ` : ''}
                                        ${item.so_thich ? `
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-star me-1"></i>
                                                ${item.so_thich}
                                            </small>
                                        ` : ''}
                                        <br>
                                        <small class="date">
                                            <i class="far fa-clock me-1"></i>
                                            ${item.ngay_tao}
                                        </small>
                                    </div>
                                    <i class="fas fa-chevron-right mt-2"></i>
                                </div>
                            </div>
                        `;
                    });
                    $('#history-list').html(output);
                    $('#clearHistory').show();
                } else {
                    $('#history-list').html(showEmptyState());
                    $('#clearHistory').hide();
                }
            } else {
                const errorMessage = response.message || 'Có lỗi xảy ra khi tải lịch sử';
                console.error('Lỗi:', errorMessage);
                $('#history-list').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${errorMessage}
                    </div>
                `);
                $('#clearHistory').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error('Lỗi AJAX:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);

            let errorMessage = 'Có lỗi xảy ra khi tải lịch sử';
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {
                console.error('Lỗi parse JSON:', e);
            }

            $('#history-list').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${errorMessage}
                </div>
            `);
            $('#clearHistory').hide();
        }
    });
}

// Hàm lọc sản phẩm theo loại
function filterProducts(type) {
    const productContainer = $('#product-suggestions');
    const products = productContainer.find('.product-card');

    if (type === '' || type === 'all') {
        products.show();
    } else {
        products.each(function() {
            const productType = $(this).data('type');
            $(this).toggle(productType === type.toLowerCase().replace(/ /g, '_'));
        });
    }

    const visibleProducts = productContainer.find('.product-card:visible');
    if (visibleProducts.length === 0) {
        productContainer.append('<p class="text-muted text-center">Không có sản phẩm phù hợp với bộ lọc đã chọn.</p>');
    } else {
        productContainer.find('p.text-muted').remove();
    }
}

// Hàm lọc sản phẩm theo giá
function filterProductsByPrice(range) {
    const productContainer = $('#product-suggestions');
    const products = productContainer.find('.product-card');

    products.show(); // Reset trước khi áp dụng bộ lọc giá

    if (range) {
        const [min, max] = range.split('-').map(Number);
        products.each(function() {
            const price = parseFloat($(this).data('price')) || 0;
            const shouldShow = (max ? price >= min && price <= max : price >= min);
            $(this).toggle(shouldShow);
        });
    }

    const visibleProducts = productContainer.find('.product-card:visible');
    if (visibleProducts.length === 0) {
        productContainer.append('<p class="text-muted text-center">Không có sản phẩm trong phạm vi giá đã chọn.</p>');
    } else {
        productContainer.find('p.text-muted').remove();
    }
}

// Hàm sắp xếp sản phẩm
function sortProducts(sortType) {
    const productContainer = $('#product-suggestions');
    const products = productContainer.find('.product-card').get();

    products.sort((a, b) => {
        const aValue = sortType.includes('price') ? parseFloat($(a).data('price')) : $(a).data('name');
        const bValue = sortType.includes('price') ? parseFloat($(b).data('price')) : $(b).data('name');

        if (sortType.includes('asc')) {
            return aValue > bValue ? 1 : -1;
        } else if (sortType.includes('desc')) {
            return aValue < bValue ? 1 : -1;
        }
        return 0;
    });

    $.each(products, (index, product) => {
        productContainer.append(product);
    });
}

// Cập nhật hàm displayResults
function displayResults(data) {
    console.log('Received data:', data);

    if (!data.bmi || !Array.isArray(data.goi_y_che_do_an) || !Array.isArray(data.goi_y_bai_tap) || !Array.isArray(data.goi_y_thuc_pham_chuc_nang)) {
        console.error('Dữ liệu không hợp lệ:', data);
        alert('Dữ liệu gợi ý không hợp lệ');
        return;
    }

    // Hiển thị BMI và các gợi ý
    $('#bmi-value').text(data.bmi.toFixed(1));
    let bmiStatus = '';
    if (data.bmi < 18.5) {
        bmiStatus = 'Thiếu cân';
    } else if (data.bmi < 25) {
        bmiStatus = 'Bình thường';
    } else if (data.bmi < 30) {
        bmiStatus = 'Thừa cân';
    } else {
        bmiStatus = 'Béo phì';
    }
    $('#bmi-status').text(bmiStatus);

    // Hiển thị các gợi ý
    $('#diet-suggestions').empty().append(
        data.goi_y_che_do_an.map(item =>
            `<li><i class="fas fa-check me-2 text-success"></i>${item}</li>`
        ).join('')
    );

    $('#exercise-suggestions').empty().append(
        data.goi_y_bai_tap.map(item =>
            `<li><i class="fas fa-check me-2 text-success"></i>${item}</li>`
        ).join('')
    );

    $('#supplement-suggestions').empty().append(
        data.goi_y_thuc_pham_chuc_nang.map(item =>
            `<li><i class="fas fa-check me-2 text-success"></i>${item}</li>`
        ).join('')
    );

    // Hiển thị sản phẩm được gợi ý
    const productContainer = $('#product-suggestions');
    productContainer.empty();

    console.log('Sản phẩm gợi ý:', data.goi_y_san_pham);

    if (data.goi_y_san_pham && data.goi_y_san_pham.length > 0) {
        const productHTML = data.goi_y_san_pham.map(product => {
            console.log('Đang xử lý sản phẩm:', product);
            const defaultImage = 'image/illustration';
            return `
                <div class="product-card" data-type="${product.LoaiSanPham.toLowerCase()}" data-id="${product.MaSanPham}" data-price="${product.Gia}" data-name="${product.TenSanPham}">
                    <div class="product-image">
                        <img src="${product.HinhAnh || defaultImage}" alt="${product.TenSanPham}" onerror="this.src='${defaultImage}'">
                    </div>
                    <div class="product-info">
                        <h5 class="product-title">${product.TenSanPham}</h5>
                        <p class="product-price">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.Gia || 0)}</p>
                        
                        <div class="product-action">
                            <button onclick="addToCart('${product.MaSanPham}')" class="btn btn-primary btn-sm">
                                <i class="fas fa-shopping-cart me-1"></i> Mua ngay
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        productContainer.html(productHTML);
        console.log('Product HTML generated:', productHTML);
    } else {
        productContainer.html('<p class="text-center text-muted">Không tìm thấy sản phẩm phù hợp. Đang hiển thị các sản phẩm tương tự...</p>');

        // Gọi API để lấy sản phẩm mặc định theo loại
        $.ajax({
            url: './model/process_goi_y.php',
            type: 'GET',
            data: {
                action: 'get_default_products',
                loai_san_pham: $('#loai_san_pham').val()
            },
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    const defaultProductsHTML = response.data.map(product => {
                        const defaultImage = './assets/images/default-product.jpg';
                        return `
                            <div class="product-card" data-type="${product.LoaiSanPham.toLowerCase()}" data-id="${product.MaSanPham}" data-price="${product.Gia}" data-name="${product.TenSanPham}">
                                <div class="product-image">
                                    <img src="${product.HinhAnh || defaultImage}" alt="${product.TenSanPham}" onerror="this.src='${defaultImage}'">
                                </div>
                                <div class="product-info">
                                    <h5 class="product-title">${product.TenSanPham}</h5>
                                    <p class="product-price">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.Gia || 0)}</p>
                                    <p class="product-description">${product.MoTaSanPham || 'Không có mô tả'}</p>
                                    <div class="product-action">
                                        <button onclick="addToCart('${product.MaSanPham}')" class="btn btn-primary btn-sm">
                                            <i class="fas fa-shopping-cart me-1"></i> Mua ngay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                    productContainer.html(defaultProductsHTML);
                }
            }
        });
    }

    // Hiển thị kết quả
    $('#suggestion-result').show().removeClass('loading');
    $('html, body').animate({
        scrollTop: $('#suggestion-result').offset().top - 20
    }, 500);
}

// Thêm hàm addToCart
function addToCart(productId) {
    $.ajax({
        url: './model/add_to_cart.php',
        type: 'POST',
        data: {
            action: 'add',
            maSanPham: productId,
            quantity: 1
        },
        success: function(response) {
            if (response.success) {
                alert('Đã thêm sản phẩm vào giỏ hàng');
            } else {
                alert(response.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
            }
        },
        error: function() {
            alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
        }
    });
}

// Hàm hiển thị trạng thái trống
function showEmptyState() {
    return `
        <div class="history-empty-state">
            <i class="fas fa-history"></i>
            <p>Chưa có lịch sử gợi ý</p>
            <p class="text-muted">Hãy điền thông tin để nhận gợi ý sức khỏe</p>
        </div>
    `;
}
</script>

<?php include './View/footer.php'; ?>