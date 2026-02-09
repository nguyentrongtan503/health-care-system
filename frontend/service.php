<?php
session_start();
require_once './model/dcFrontend.php';

$conn = connectDB();

$servicesPerPage = 3;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $servicesPerPage;

$totalQuery = "SELECT COUNT(*) as total FROM LoaiDichVu";
$totalResult = $conn->query($totalQuery);
if (!$totalResult) {
    error_log("Lỗi truy vấn tổng số dịch vụ: " . $conn->error);
    die("Lỗi cơ sở dữ liệu.");
}
$totalRow = $totalResult->fetch_assoc();
$totalServices = $totalRow['total'];

error_log("Tổng số dịch vụ: " . $totalServices);
$totalPages = ceil($totalServices / $servicesPerPage);

// Updated query to include GiaThamKhao field
$query = "SELECT MaLoaiDichVu, TenLoaiDichVu, Anh, MoTaDichVu, GiaThamKhao FROM LoaiDichVu LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $servicesPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

$services = [];
while ($row = $result->fetch_assoc()) {
    // Debug
    error_log("Dịch vụ từ danh sách: " . print_r($row, true));
    $services[] = $row;
}

$stmt->close();
$conn->close();

include __DIR__ . "/View/header.php";
?>

<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Dịch vụ của chúng tôi</span>
          <h1 class="text-capitalize mb-5 text-lg">Chăm sóc sức khỏe toàn diện</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section service-wrap">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="row">
          <?php if (empty($services)): ?>
            <div class="col-lg-12 col-md-12 mb-5">
              <p>Không có dịch vụ nào để hiển thị.</p>
            </div>
          <?php else: ?>
            <?php foreach ($services as $service): ?>
              <div class="col-lg-12 col-md-12 mb-5">
                <div class="service-item shadow-sm rounded">
                  <div class="row no-gutters">
                    <div class="col-md-5">
                      <div class="service-thumb h-100">
                        <img src="<?php echo htmlspecialchars($service['Anh']); ?>" alt="<?php echo htmlspecialchars($service['TenLoaiDichVu']); ?>" class="img-fluid rounded-left h-100 object-cover" onerror="this.src='images/service/service-default.jpg'">
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="service-item-content p-4">
                        <div class="service-icon mb-3">
                          <i class="icofont-heart-beat-alt text-color"></i>
                        </div>
                        <h3 class="mt-2 mb-3"><?php echo htmlspecialchars($service['TenLoaiDichVu']); ?></h3>
                        <p class="mb-3"><?php echo htmlspecialchars(substr($service['MoTaDichVu'], 0, 150)) . (strlen($service['MoTaDichVu']) > 150 ? '...' : ''); ?></p>
                        <?php if (isset($service['GiaThamKhao']) && $service['GiaThamKhao'] > 0): ?>
                        <p class="text-primary mb-3"><i class="icofont-money mr-1"></i> Giá tham khảo: <strong><?php echo number_format($service['GiaThamKhao'], 0, ',', '.'); ?> VNĐ</strong></p>
                        <?php endif; ?>
                        <a href="service-single.php?id=<?php echo htmlspecialchars($service['MaLoaiDichVu']); ?>" class="btn btn-main btn-round-full btn-sm" onclick="console.log('Đang chuyển đến dịch vụ với ID: <?php echo htmlspecialchars($service['MaLoaiDichVu']); ?>')">Xem chi tiết <i class="icofont-simple-right ml-2"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="sidebar-wrap pl-lg-4 mt-5 mt-lg-0">
          <div class="sidebar-widget search mb-4 p-4 rounded bg-white shadow-sm">
            <h4>Tìm kiếm dịch vụ</h4>
            <form action="#" class="search-form">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Nhập từ khóa...">
                <div class="input-group-append">
                  <button class="btn btn-main" type="submit"><i class="ti-search"></i></button>
                </div>
              </div>
            </form>
          </div>

          <div class="sidebar-widget category mb-4 p-4 rounded bg-white shadow-sm">
            <h4 class="mb-3">Danh mục dịch vụ</h4>
            <ul class="list-unstyled">
              <li><a href="#" class="d-flex justify-content-between align-items-center">
                <span><i class="icofont-heart-beat text-color mr-2"></i> Khám tổng quát</span>
                <span class="badge badge-pill badge-primary">3</span>
              </a></li>
              <li><a href="#" class="d-flex justify-content-between align-items-center">
                <span><i class="icofont-drug text-color mr-2"></i> Tư vấn dược phẩm</span>
                <span class="badge badge-pill badge-primary">2</span>
              </a></li>
              <li><a href="#" class="d-flex justify-content-between align-items-center">
                <span><i class="icofont-laboratory text-color mr-2"></i> Xét nghiệm</span>
                <span class="badge badge-pill badge-primary">4</span>
              </a></li>
              <li><a href="#" class="d-flex justify-content-between align-items-center">
                <span><i class="icofont-pills text-color mr-2"></i> Điều trị</span>
                <span class="badge badge-pill badge-primary">6</span>
              </a></li>
            </ul>
          </div>

          <div class="sidebar-widget schedule-widget mb-4 p-4 rounded bg-white shadow-sm">
            <h4 class="mb-3">Giờ làm việc</h4>
            <ul class="list-unstyled">
              <li class="d-flex justify-content-between align-items-center">
                <span><i class="icofont-clock-time text-color mr-2"></i>Thứ Hai - Thứ Sáu</span>
                <span>8:00 - 17:00</span>
              </li>
              <li class="d-flex justify-content-between align-items-center">
                <span><i class="icofont-clock-time text-color mr-2"></i>Thứ Bảy</span>
                <span>8:00 - 12:00</span>
              </li>
              <li class="d-flex justify-content-between align-items-center">
                <span><i class="icofont-clock-time text-color mr-2"></i>Chủ Nhật</span>
                <span>Đóng cửa</span>
              </li>
            </ul>
            <div class="sidebar-contatct-info mt-4">
              <div class="emergency-box p-3 bg-light-gray rounded">
                <h5 class="text-color">Cần hỗ trợ khẩn cấp?</h5>
                <h3><i class="icofont-phone-circle text-color mr-2"></i>0123-456-789</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if ($totalPages > 1): ?>
      <div class="row mt-5">
        <div class="col-lg-8">
          <nav class="pagination py-2 d-inline-block">
            <div class="nav-links">
              <?php if ($currentPage > 1): ?>
                <a class="page-numbers" href="service.php?page=<?php echo $currentPage - 1; ?>"><i class="icofont-thin-double-left"></i></a>
              <?php endif; ?>

              <?php
              $startPage = max(1, $currentPage - 2);
              $endPage = min($totalPages, $startPage + 4);
              if ($endPage - $startPage < 4) {
                $startPage = max(1, $endPage - 4);
              }
              ?>

              <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <?php if ($i == $currentPage): ?>
                  <span aria-current="page" class="page-numbers current"><?php echo $i; ?></span>
                <?php else: ?>
                  <a class="page-numbers" href="service.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
              <?php endfor; ?>

              <?php if ($currentPage < $totalPages): ?>
                <a class="page-numbers" href="service.php?page=<?php echo $currentPage + 1; ?>"><i class="icofont-thin-double-right"></i></a>
              <?php endif; ?>
            </div>
          </nav>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section cta-page">
  <div class="container">
    <div class="row">
      <div class="col-lg-7">
        <div class="cta-content">
          <div class="divider mb-4"></div>
          <h2 class="mb-5 text-lg">We are pleased to offer you the <span class="title-color">chance to have the healthy</span></h2>
          <a href="appoinment.html" class="btn btn-main-2 btn-round-full">Get appoinment<i class="icofont-simple-right ml-2"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . "/View/footer.php"; ?>

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
