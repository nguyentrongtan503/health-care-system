<?php
session_start();
require_once './model/dcFrontend.php';
include './View/header.php';

// Xử lý các tham số lọc và phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 12;

// Xử lý bộ lọc giá
$priceRange = isset($_GET['price_range']) ? $_GET['price_range'] : '';
$minPrice = null;
$maxPrice = null;

if ($priceRange) {
    $prices = explode('-', $priceRange);
    $minPrice = (int)$prices[0];
    $maxPrice = isset($prices[1]) && $prices[1] != '0' ? (int)$prices[1] : PHP_INT_MAX;
}

// Xử lý bộ lọc loại thuốc
$medicineTypes = isset($_GET['medicine_type']) ? $_GET['medicine_type'] : ['all'];
if (!is_array($medicineTypes)) {
    $medicineTypes = [$medicineTypes];
}

// Xử lý bộ lọc đối tượng sử dụng
$userTypes = isset($_GET['user_type']) ? $_GET['user_type'] : ['all'];
if (!is_array($userTypes)) {
    $userTypes = [$userTypes];
}

// Xử lý sắp xếp
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
?>

<section class="page-title bg-1">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="block text-center">
                    <span class="text-white">Tất Cả Thuốc</span>
                    <h1 class="text-capitalize mb-5 text-lg">Danh Mục Thuốc</h1>
                    <ul class="list-inline breadcumb-nav">
                        <li class="list-inline-item"><a href="./indexx.php" class="text-white">Trang chủ</a></li>
                        <li class="list-inline-item"><span class="text-white">/</span></li>
                        <li class="list-inline-item"><a href="./all_thuoc.php" class="text-white-50">Tất cả</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Tất cả sản phẩm thuốc -->
<section class="section service-2">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="section-title">
                    <h2>Tất Cả Sản Phẩm Thuốc</h2>
                    <div class="divider mx-auto my-4"></div>
                    <p>Khám phá tất cả các sản phẩm thuốc của chúng tôi.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar bộ lọc -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h4 class="mb-4">Bộ lọc nâng cao</h4>
                    <form action="" method="GET" class="filter-form" id="filterForm">
                        <!-- Khoảng giá -->
                        <div class="filter-section mb-4">
                            <h5 class="filter-title">Giá bán</h5>
                            <div class="filter-content">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="price0" value="" <?php echo empty($priceRange) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="price0">Tất cả</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="price1" value="0-100000" <?php echo $priceRange == '0-100000' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="price1">Dưới 100.000đ</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="price2" value="100000-300000" <?php echo $priceRange == '100000-300000' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="price2">100.000đ - 300.000đ</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="price3" value="300000-500000" <?php echo $priceRange == '300000-500000' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="price3">300.000đ - 500.000đ</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="price_range" id="price4" value="500000-0" <?php echo $priceRange == '500000-0' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="price4">Trên 500.000đ</label>
                                </div>
                            </div>
                        </div>

                        <!-- Loại thuốc -->
                        <div class="filter-section mb-4">
                            <h5 class="filter-title">Loại thuốc</h5>
                            <div class="filter-content">
                                <div class="form-check mb-2">
                                    <input class="form-check-input medicine-type" type="checkbox" name="medicine_type[]" id="type0" value="all" <?php echo in_array('all', $medicineTypes) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="type0">Tất cả</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input medicine-type" type="checkbox" name="medicine_type[]" id="type1" value="prescription" <?php echo in_array('prescription', $medicineTypes) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="type1">Thuốc kê đơn</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input medicine-type" type="checkbox" name="medicine_type[]" id="type2" value="non_prescription" <?php echo in_array('non_prescription', $medicineTypes) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="type2">Thuốc không kê đơn</label>
                                </div>
                            </div>
                        </div>

                        <!-- Đối tượng sử dụng -->
                        <div class="filter-section mb-4">
                            <h5 class="filter-title">Đối tượng sử dụng <i class="icofont-simple-down float-end"></i></h5>
                            <div class="filter-content">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="user_type[]" id="user0" value="all" checked>
                                    <label class="form-check-label" for="user0">Tất cả</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="user_type[]" id="user1" value="children">
                                    <label class="form-check-label" for="user1">Trẻ em</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="user_type[]" id="user2" value="elderly">
                                    <label class="form-check-label" for="user2">Người cao tuổi</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="user_type[]" id="user3" value="adult">
                                    <label class="form-check-label" for="user3">Người lớn</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="user_type[]" id="user4" value="special">
                                    <label class="form-check-label" for="user4">Người suy gan, thận</label>
                                </div>
                                <div class="show-more">
                                    <a href="#" class="text-primary">
                                        <i class="icofont-simple-down"></i> Xem thêm
                                    </a>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-main-2 btn-block w-100">Áp dụng</button>
                    </form>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="col-lg-9">
                <div class="row product-grid">
                    <?php
                    $allProducts = getProductsForChildren($page, $itemsPerPage, $minPrice, $maxPrice, $medicineTypes, $userTypes, $sort);
                    $totalProducts = getTotalProductsCount($minPrice, $maxPrice, $medicineTypes, $userTypes);
                    $totalPages = ceil($totalProducts / $itemsPerPage);

                    if (empty($allProducts)) {
                        echo "<div class='col-12'><p class='text-center'>Không tìm thấy sản phẩm phù hợp.</p></div>";
                    } else {
                        foreach ($allProducts as $product) {
                            ?>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="product-card mb-4">
                                    <div class="product-image">
                                        <img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>"
                                             alt="<?php echo htmlspecialchars($product['TenSanPham']); ?>"
                                             class="img-fluid">
                                    </div>
                                    <div class="product-details p-3">
                                        <div class="product-title-wrap">
                                            <h4 class="product-title"><?php echo htmlspecialchars($product['TenSanPham']); ?></h4>
                                        </div>
                                        <p class="price text-center mb-3"><?php echo number_format($product['Gia'], 0, ',', '.'); ?> VNĐ</p>
                                        <div class="product-actions d-flex flex-column gap-2">
                                            <a href="./chitietsanpham.php?id=<?php echo htmlspecialchars($product['MaSanPham']); ?>"
                                               class="btn btn-outline-primary btn-sm w-100">Xem chi tiết</a>
                                            <button onclick="addToCart('<?php echo htmlspecialchars($product['MaSanPham']); ?>')"
                                                    class="btn btn-main-2 btn-sm w-100">
                                                <i class="icofont-shopping-cart"></i> Mua
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>

                <!-- Phân trang -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination-wrapper text-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo ($page - 1); ?>&price_range=<?php echo $priceRange; ?>&sort=<?php echo $sort; ?>">
                                        <i class="icofont-arrow-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&price_range=<?php echo $priceRange; ?>&sort=<?php echo $sort; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>&price_range=<?php echo $priceRange; ?>&sort=<?php echo $sort; ?>">
                                        <i class="icofont-arrow-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>




<script>
// Xử lý sự kiện click vào tiêu đề bộ lọc
document.querySelectorAll('.filter-title').forEach(title => {
    title.addEventListener('click', () => {
        const content = title.nextElementSibling;
        const icon = title.querySelector('i');

        // Toggle hiển thị nội dung
        if(content.style.display === 'none') {
            content.style.display = 'block';
            icon.classList.remove('icofont-simple-right');
            icon.classList.add('icofont-simple-down');
        } else {
            content.style.display = 'none';
            icon.classList.remove('icofont-simple-down');
            icon.classList.add('icofont-simple-right');
        }
    });
});

// Xử lý checkbox "Tất cả"
document.querySelectorAll('input[value="all"]').forEach(checkbox => {
    checkbox.addEventListener('change', (e) => {
        const parent = e.target.closest('.filter-content');
        const otherCheckboxes = parent.querySelectorAll('input[type="checkbox"]:not([value="all"])');

        otherCheckboxes.forEach(cb => {
            cb.checked = false;
            cb.disabled = e.target.checked;
        });
    });
});

// Xử lý checkbox "Tất cả" cho loại thuốc
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý checkbox "Tất cả" cho loại thuốc
    const typeAllCheckbox = document.getElementById('type0');
    const otherTypeCheckboxes = document.querySelectorAll('.medicine-type:not(#type0)');

    typeAllCheckbox.addEventListener('change', function() {
        otherTypeCheckboxes.forEach(cb => {
            cb.checked = false;
            cb.disabled = this.checked;
        });
    });

    otherTypeCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (this.checked) {
                typeAllCheckbox.checked = false;
            }
            // Nếu không có checkbox nào được chọn, tự động chọn "Tất cả"
            const anyChecked = Array.from(otherTypeCheckboxes).some(cb => cb.checked);
            if (!anyChecked) {
                typeAllCheckbox.checked = true;
                otherTypeCheckboxes.forEach(cb => cb.disabled = true);
            }
        });
    });

    // Khởi tạo trạng thái ban đầu
    if (typeAllCheckbox.checked) {
        otherTypeCheckboxes.forEach(cb => cb.disabled = true);
    }
});
</script>

<!-- Thêm JavaScript để xử lý nút "Mua" -->
<script src="./js/addcart.js"></script>