<?php
session_start();
// Include file dcFrontend.php (đã include dbconnect.php)
require_once './model/dcFrontend.php';

// Tạo kết nối cơ sở dữ liệu
$conn = connectDB();

// Lấy MaLoaiDichVu từ tham số URL
$serviceId = isset($_GET['id']) ? $_GET['id'] : null;
error_log("ID dịch vụ được yêu cầu: " . $serviceId);

if (!$serviceId) {
    // Nếu không có ID trong URL, lấy dịch vụ đầu tiên từ bảng
    $firstServiceQuery = "SELECT MaLoaiDichVu FROM LOAIDICHVU LIMIT 1";
    $firstServiceResult = $conn->query($firstServiceQuery);
    if ($firstServiceResult && $firstServiceResult->num_rows > 0) {
        $serviceId = $firstServiceResult->fetch_assoc()['MaLoaiDichVu'];
    } else {
        die("Không tìm thấy dịch vụ nào trong hệ thống.");
    }
}

// Truy vấn chi tiết dịch vụ từ bảng LOAIDICHVU
$query = "SELECT MaLoaiDichVu, TenLoaiDichVu, Anh, MoTaDichVu, GiaThamKhao FROM LOAIDICHVU WHERE MaLoaiDichVu = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    error_log("Lỗi chuẩn bị truy vấn: " . $conn->error);
    die("Lỗi cơ sở dữ liệu.");
}
$stmt->bind_param("s", $serviceId);
if (!$stmt->execute()) {
    error_log("Lỗi thực thi truy vấn: " . $stmt->error);
    die("Lỗi cơ sở dữ liệu.");
}
$result = $stmt->get_result();
$service = $result->fetch_assoc();

// Debug
if ($service) {
    error_log("Dịch vụ được tìm thấy: " . print_r($service, true));
} else {
    error_log("Không tìm thấy dịch vụ với ID: " . $serviceId);
}

// Kiểm tra nếu không tìm thấy dịch vụ
if (!$service) {
    // Nếu không tìm thấy dịch vụ, lấy dịch vụ đầu tiên từ bảng
    $fallbackQuery = "SELECT MaLoaiDichVu, TenLoaiDichVu, Anh, MoTaDichVu, GiaThamKhao FROM LOAIDICHVU LIMIT 1";
    $fallbackResult = $conn->query($fallbackQuery);
    if ($fallbackResult && $fallbackResult->num_rows > 0) {
        $service = $fallbackResult->fetch_assoc();
    } else {
        die("Không tìm thấy dịch vụ nào trong hệ thống.");
    }
}

// Đóng statement
$stmt->close();

// Giả lập danh sách bình luận (dữ liệu tĩnh, bạn có thể thay bằng truy vấn từ bảng Comment nếu có)
$comments = [
    [
        'name' => 'trọngtấn',
        'country' => 'Hà Nội',
        'date' => 'April 7, 2019',
        'avatar' => 'images/blog/testimonial1.jpg',
        'content' => 'Dịch vụ y tế tận tâm, mang đến sự chăm sóc chu đáo và hỗ trợ bệnh nhân vượt qua khó khăn, đảm bảo an toàn và phục hồi sức khỏe hiệu quả.'
    ],
    [
        'name' => 'tan',
        'country' => 'Hà Nội',
        'date' => 'June 7, 2019',
        'avatar' => 'images/blog/testimonial2.jpg',
        'content' => 'Dịch vụ y tế tận tâm, mang đến sự chăm sóc chu đáo và hỗ trợ bệnh nhân vượt qua khó khăn, đảm bảo an toàn và phục hồi sức khỏe hiệu quả.'
    ]
];

// Include header
include __DIR__ . "/View/header.php";

?>

<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Chi tiết dịch vụ</span>
          <h1 class="text-capitalize mb-5 text-lg"><?php echo htmlspecialchars($service['TenLoaiDichVu']); ?></h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section blog-wrap">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="row">
          <div class="col-lg-12 mb-5">
            <div class="single-blog-item shadow-sm rounded p-4">
              <div class="service-image-container text-center mb-4">
                <?php if (!empty($service['Anh'])): ?>
                  <img src="<?php echo htmlspecialchars($service['Anh']); ?>" alt="<?php echo htmlspecialchars($service['TenLoaiDichVu']); ?>" class="img-fluid rounded" style="max-height: 400px; object-fit: cover;" onerror="this.src='images/service/service-default.jpg';">
                <?php else: ?>
                  <img src="images/service/service-default.jpg" alt="<?php echo htmlspecialchars($service['TenLoaiDichVu']); ?>" class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
                <?php endif; ?>
              </div>

              <div class="blog-item-content">
                <div class="blog-item-meta mb-3">
                  <span class="text-color-2 text-capitalize mr-3"><i class="icofont-book-mark mr-2"></i> Dịch vụ y tế</span>
                  <span class="text-muted text-capitalize mr-3"><i class="icofont-comment mr-2"></i><?php echo count($comments); ?> Bình luận</span>
                  <span class="text-muted text-capitalize mr-3"><i class="icofont-id-card mr-2"></i>Mã dịch vụ: <?php echo htmlspecialchars($service['MaLoaiDichVu']); ?></span>
                  <?php if (isset($service['GiaThamKhao']) && $service['GiaThamKhao'] > 0): ?>
                  <span class="text-primary text-capitalize mr-3"><i class="icofont-money mr-2"></i>Giá tham khảo: <?php echo number_format($service['GiaThamKhao'], 0, ',', '.'); ?> VNĐ</span>
                  <?php endif; ?>
                </div>

                <h2 class="mb-4 text-md"><?php echo htmlspecialchars($service['TenLoaiDichVu']); ?></h2>

                <div class="service-description mb-4">
                  <h4 class="mb-3">Mô tả dịch vụ</h4>
                  <?php if (!empty($service['MoTaDichVu'])): ?>
                    <p class="lead"><?php echo nl2br(htmlspecialchars($service['MoTaDichVu'])); ?></p>
                  <?php else: ?>
                    <p class="lead">Hiện tại chưa có mô tả chi tiết cho dịch vụ này. Vui lòng liên hệ với chúng tôi để biết thêm thông tin.</p>
                  <?php endif; ?>
                </div>

                <div class="service-benefits mb-4">
                  <h4 class="mb-3">Lợi ích của dịch vụ</h4>
                  <ul class="list-unstyled">
                    <li class="mb-2"><i class="icofont-check-circled text-success mr-2"></i> Được thực hiện bởi đội ngũ y bác sĩ chuyên nghiệp</li>
                    <li class="mb-2"><i class="icofont-check-circled text-success mr-2"></i> Trang thiết bị hiện đại, đảm bảo an toàn</li>
                    <li class="mb-2"><i class="icofont-check-circled text-success mr-2"></i> Quy trình chuẩn quốc tế</li>
                    <li class="mb-2"><i class="icofont-check-circled text-success mr-2"></i> Chăm sóc tận tâm, chu đáo</li>
                  </ul>
                </div>

                <div class="service-process mb-4">
                  <h4 class="mb-3">Quy trình thực hiện</h4>
                  <div class="row">
                    <div class="col-md-3 col-6 text-center mb-3">
                      <div class="process-step p-3 rounded bg-light">
                        <i class="icofont-calendar text-primary d-block mb-2" style="font-size: 2rem;"></i>
                        <h6>Đặt lịch</h6>
                      </div>
                    </div>
                    <div class="col-md-3 col-6 text-center mb-3">
                      <div class="process-step p-3 rounded bg-light">
                        <i class="icofont-stethoscope text-primary d-block mb-2" style="font-size: 2rem;"></i>
                        <h6>Thăm khám</h6>
                      </div>
                    </div>
                    <div class="col-md-3 col-6 text-center mb-3">
                      <div class="process-step p-3 rounded bg-light">
                        <i class="icofont-doctor text-primary d-block mb-2" style="font-size: 2rem;"></i>
                        <h6>Điều trị</h6>
                      </div>
                    </div>
                    <div class="col-md-3 col-6 text-center mb-3">
                      <div class="process-step p-3 rounded bg-light">
                        <i class="icofont-heart-beat text-primary d-block mb-2" style="font-size: 2rem;"></i>
                        <h6>Theo dõi</h6>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="service-cta text-center my-5">
                  <a href="./appoinment.php" class="btn btn-main btn-round-full">Đặt lịch ngay <i class="icofont-simple-right ml-2"></i></a>
                </div>

                <div class="mt-5 clearfix">
                  <ul class="float-left list-inline tag-option">
                    <li class="list-inline-item"><a href="#" class="btn btn-sm btn-outline-primary">Y tế</a></li>
                    <li class="list-inline-item"><a href="#" class="btn btn-sm btn-outline-primary">Sức khỏe</a></li>
                    <li class="list-inline-item"><a href="#" class="btn btn-sm btn-outline-primary">Chăm sóc</a></li>
                  </ul>

                  <ul class="float-right list-inline">
                    <li class="list-inline-item"> Chia sẻ: </li>
                    <li class="list-inline-item"><a href="#" target="_blank"><i class="icofont-facebook" aria-hidden="true"></i></a></li>
                    <li class="list-inline-item"><a href="#" target="_blank"><i class="icofont-twitter" aria-hidden="true"></i></a></li>
                    <li class="list-inline-item"><a href="#" target="_blank"><i class="icofont-pinterest" aria-hidden="true"></i></a></li>
                    <li class="list-inline-item"><a href="#" target="_blank"><i class="icofont-linkedin" aria-hidden="true"></i></a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="comment-area mt-4 mb-5 shadow-sm rounded p-4">
              <h4 class="mb-4"><?php echo count($comments); ?> Đánh giá về dịch vụ "<?php echo htmlspecialchars($service['TenLoaiDichVu']); ?>"</h4>
              <ul class="comment-tree list-unstyled">
                <?php foreach ($comments as $comment): ?>
                  <li class="mb-5">
                    <div class="comment-area-box p-3 border rounded">
                      <div class="comment-thumb float-left mr-3">
                        <img alt="<?php echo htmlspecialchars($comment['name']); ?>" src="<?php echo $comment['avatar']; ?>" class="img-fluid rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                      </div>

                      <div class="comment-info">
                        <h5 class="mb-1"><?php echo htmlspecialchars($comment['name']); ?></h5>
                        <div class="d-flex align-items-center mb-2">
                          <span class="text-muted mr-2"><i class="icofont-location-pin"></i> <?php echo htmlspecialchars($comment['country']); ?></span>
                          <span class="text-muted date-comm"><i class="icofont-calendar"></i> <?php echo $comment['date']; ?></span>
                          <div class="ml-2">
                            <i class="icofont-star text-warning"></i>
                            <i class="icofont-star text-warning"></i>
                            <i class="icofont-star text-warning"></i>
                            <i class="icofont-star text-warning"></i>
                            <i class="icofont-star text-warning"></i>
                          </div>
                        </div>
                      </div>
                      <div class="comment-meta mt-2">
                        <a href="#" class="btn btn-sm btn-outline-primary"><i class="icofont-reply mr-2"></i>Trả lời</a>
                      </div>

                      <div class="comment-content mt-3">
                        <p><?php echo htmlspecialchars($comment['content']); ?></p>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>

          <div class="col-lg-12">
            <form class="comment-form my-5 shadow-sm rounded p-4" id="comment-form" method="post" action="./model/submit-comment.php">
              <h4 class="mb-4">Viết đánh giá của bạn</h4>

              <div class="rating-select mb-4">
                <h6 class="mb-2">Đánh giá của bạn</h6>
                <div class="rating-stars">
                  <i class="icofont-star text-muted" data-rating="1"></i>
                  <i class="icofont-star text-muted" data-rating="2"></i>
                  <i class="icofont-star text-muted" data-rating="3"></i>
                  <i class="icofont-star text-muted" data-rating="4"></i>
                  <i class="icofont-star text-muted" data-rating="5"></i>
                </div>
                <input type="hidden" name="rating" id="rating" value="5">
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Họ tên</label>
                    <input class="form-control" type="text" name="name" id="name" placeholder="Nhập họ tên của bạn" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="mail">Email</label>
                    <input class="form-control" type="email" name="mail" id="mail" placeholder="Nhập email của bạn" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="comment">Nội dung đánh giá</label>
                <textarea class="form-control mb-4" name="comment" id="comment" cols="30" rows="5" placeholder="Chia sẻ trải nghiệm của bạn về dịch vụ này..." required></textarea>
              </div>

              <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($service['MaLoaiDichVu']); ?>">
              <button class="btn btn-main btn-round-full" type="submit" name="submit-comment" id="submit_comment">Gửi đánh giá <i class="icofont-paper-plane ml-2"></i></button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="sidebar-wrap pl-lg-4 mt-5 mt-lg-0">
          <!-- Đặt lịch nhanh -->
          <div class="sidebar-widget appointment-widget mb-4 shadow-sm rounded p-4">
            <h5 class="mb-4">Đặt lịch nhanh</h5>
            <form action="./model/process_appoiment.php" method="post" class="quick-appointment-form">
              <input type="hidden" name="service" value="<?php echo htmlspecialchars($service['MaLoaiDichVu']); ?>">

              <div class="form-group">
                <label for="doctor">Chọn bác sĩ</label>
                <select class="form-control" id="doctor" name="doctor" required>
                  <option value="">Chọn bác sĩ</option>
                  <?php
                  // Make sure we have a valid connection
                  if (!$conn) {
                      $conn = connectDB();
                  }
                  $query = "SELECT MaNhanVien, TenNhanVien FROM NhanVien";
                  $result = $conn->query($query);
                  if ($result) {
                      while ($row = $result->fetch_assoc()) {
                          echo "<option value='" . htmlspecialchars($row['MaNhanVien']) . "'>" . htmlspecialchars($row['TenNhanVien']) . "</option>";
                      }
                  }
                  ?>
                </select>
              </div>

              <div class="form-group">
                <label for="date">Ngày hẹn</label>
                <input type="date" class="form-control" id="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
              </div>

              <div class="form-group">
                <label for="time">Thời gian</label>
                <div id="timeSlotMessage" class="alert alert-info d-none">
                  Vui lòng chọn bác sĩ và ngày hẹn để xem các khung giờ khả dụng.
                </div>
                <div id="noSlotsMessage" class="alert alert-warning d-none">
                  Không có khung giờ nào khả dụng cho ngày đã chọn. Vui lòng chọn ngày khác.
                </div>
                <div class="time-slots" id="timeSlots">
                  <div class="row" id="timeSlotsContainer">
                    <!-- Time slots will be dynamically populated here -->
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="name">Họ tên</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập họ tên của bạn" required>
              </div>

              <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
              </div>

              <div class="form-group">
                <label for="message">Lời nhắn</label>
                <textarea class="form-control" id="message" name="message" rows="3" placeholder="Nhập lời nhắn của bạn"></textarea>
              </div>

              <button type="submit" class="btn btn-main btn-round-full btn-block">Đặt lịch ngay</button>
            </form>
          </div>

          <!-- Thời gian làm việc -->
          <div class="sidebar-widget schedule-widget mb-4 shadow-sm rounded p-4">
            <h5 class="mb-4">Thời gian làm việc</h5>
            <ul class="list-unstyled">
              <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span><i class="icofont-clock-time text-primary mr-2"></i>Thứ Hai - Thứ Sáu</span>
                <span class="badge badge-primary">8:00 - 17:00</span>
              </li>
              <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                <span><i class="icofont-clock-time text-primary mr-2"></i>Thứ Bảy</span>
                <span class="badge badge-primary">8:00 - 12:00</span>
              </li>
              <li class="d-flex justify-content-between align-items-center py-2">
                <span><i class="icofont-clock-time text-primary mr-2"></i>Chủ Nhật</span>
                <span class="badge badge-danger">Đóng cửa</span>
              </li>
            </ul>
            <div class="sidebar-contact-info mt-4 text-center bg-light p-3 rounded">
              <p class="mb-1">Cần hỗ trợ khẩn cấp?</p>
              <h4 class="text-color"><i class="icofont-phone-circle mr-2"></i>0123-456-789</h4>
            </div>
          </div>

          <!-- Dịch vụ liên quan -->
          <div class="sidebar-widget related-service-widget mb-4 shadow-sm rounded p-4">
            <h5 class="mb-4">Dịch vụ liên quan</h5>
            <ul class="list-unstyled">
              <?php
              // Lấy danh sách dịch vụ liên quan (trừ dịch vụ hiện tại)
              $relatedQuery = "SELECT MaLoaiDichVu, TenLoaiDichVu FROM LOAIDICHVU WHERE MaLoaiDichVu != ? LIMIT 4";
              $relatedStmt = $conn->prepare($relatedQuery);
              if ($relatedStmt) {
                  $relatedStmt->bind_param("s", $service['MaLoaiDichVu']);
                  $relatedStmt->execute();
                  $relatedResult = $relatedStmt->get_result();

                  // Mảng các icon cho từng dịch vụ
                  $icons = ['icofont-heart-beat-alt', 'icofont-drug', 'icofont-laboratory', 'icofont-pills'];
                  $descriptions = [
                      'Kiểm tra sức khỏe toàn diện',
                      'Tư vấn sử dụng thuốc an toàn',
                      'Xét nghiệm chính xác, nhanh chóng',
                      'Phương pháp điều trị tiên tiến'
                  ];

                  $i = 0;
                  while ($relatedService = $relatedResult->fetch_assoc()) {
                      $icon = $icons[$i % count($icons)];
                      $description = $descriptions[$i % count($descriptions)];
                      $isLast = ($i == $relatedResult->num_rows - 1) ? '' : 'border-bottom';

                      echo '<li class="media mb-3 pb-3 ' . $isLast . '">
                              <i class="' . $icon . ' text-color mr-3 mt-2" style="font-size: 1.5rem;"></i>
                              <div class="media-body">
                                <h6 class="mt-0 mb-1"><a href="service-single.php?id=' . htmlspecialchars($relatedService['MaLoaiDichVu']) . '">' . htmlspecialchars($relatedService['TenLoaiDichVu']) . '</a></h6>
                                <p class="text-sm text-muted mb-0">' . $description . '</p>
                              </div>
                            </li>';
                      $i++;
                  }

                  $relatedStmt->close();
              }

              // Nếu không có dịch vụ liên quan, hiển thị mặc định
              if (!isset($i) || $i == 0) {
                  echo '<li class="media mb-3 pb-3 border-bottom">
                          <i class="icofont-heart-beat-alt text-color mr-3 mt-2" style="font-size: 1.5rem;"></i>
                          <div class="media-body">
                            <h6 class="mt-0 mb-1"><a href="service-single.php">Khám tổng quát</a></h6>
                            <p class="text-sm text-muted mb-0">Kiểm tra sức khỏe toàn diện</p>
                          </div>
                        </li>
                        <li class="media">
                          <i class="icofont-drug text-color mr-3 mt-2" style="font-size: 1.5rem;"></i>
                          <div class="media-body">
                            <h6 class="mt-0 mb-1"><a href="service-single.php">Tư vấn dược phẩm</a></h6>
                            <p class="text-sm text-muted mb-0">Tư vấn sử dụng thuốc an toàn</p>
                          </div>
                        </li>';
              }
              ?>
            </ul>
          </div>

          <!-- Thông tin liên hệ -->
          <div class="sidebar-widget contact-widget mb-4 shadow-sm rounded p-4 bg-light">
            <h5 class="mb-4">Thông tin liên hệ</h5>
            <ul class="list-unstyled">
              <li class="d-flex mb-3">
                <i class="icofont-location-pin text-primary mr-3 mt-1" style="font-size: 1.2rem;"></i>
                <span>P. Trịnh Văn Bô, Hà Nội</span>
              </li>
              <li class="d-flex mb-3">
                <i class="icofont-phone text-primary mr-3 mt-1" style="font-size: 1.2rem;"></i>
                <span>0123-456-789</span>
              </li>
              <li class="d-flex mb-3">
                <i class="icofont-envelope text-primary mr-3 mt-1" style="font-size: 1.2rem;"></i>
                <span>EAUT@gmail.com</span>
              </li>
              <li class="d-flex">
                <i class="icofont-globe text-primary mr-3 mt-1" style="font-size: 1.2rem;"></i>
                <span>www.healthandcare.com</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- footer Start -->
<?php
// Close the connection if it's still open
if ($conn) {
    $conn->close();
}

include __DIR__ . "/View/footer.php";
?>

<!-- Essential Scripts -->
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

<style>
  /* Time slot styling */
  .time-slot {
    position: relative;
    width: 100%;
  }

  .time-slot-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
  }

  .time-slot-label {
    display: block;
    padding: 8px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
    width: 100%;
    margin-bottom: 0;
  }

  .time-slot-input:checked + .time-slot-label {
    background-color: #223a66;
    color: white;
    border-color: #223a66;
  }

  .time-slot-input:not(:disabled):hover + .time-slot-label {
    border-color: #223a66;
  }

  .time-slot-input:disabled + .time-slot-label {
    background-color: #f8f9fa;
    color: #adb5bd;
    cursor: not-allowed;
    text-decoration: line-through;
  }
</style>

<script>
  // Star rating functionality
  document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-stars i');
    const ratingInput = document.getElementById('rating');

    // Function to update stars
    function updateStars(rating) {
      stars.forEach(star => {
        const starRating = parseInt(star.getAttribute('data-rating'));
        if (starRating <= rating) {
          star.classList.remove('text-muted');
          star.classList.add('text-warning');
        } else {
          star.classList.remove('text-warning');
          star.classList.add('text-muted');
        }
      });
      ratingInput.value = rating;
    }

    // Set initial rating
    if (stars.length > 0 && ratingInput) {
      updateStars(5);

      // Add click event to stars
      stars.forEach(star => {
        star.addEventListener('click', function() {
          const rating = parseInt(this.getAttribute('data-rating'));
          updateStars(rating);
        });

        // Hover effects
        star.addEventListener('mouseenter', function() {
          const rating = parseInt(this.getAttribute('data-rating'));
          stars.forEach(s => {
            const r = parseInt(s.getAttribute('data-rating'));
            if (r <= rating) {
              s.classList.remove('text-muted');
              s.classList.add('text-warning');
            } else {
              s.classList.remove('text-warning');
              s.classList.add('text-muted');
            }
          });
        });

        star.addEventListener('mouseleave', function() {
          const currentRating = parseInt(ratingInput.value);
          updateStars(currentRating);
        });
      });
    }

    // Handle image error
    const serviceImage = document.querySelector('.service-image-container img');
    if (serviceImage) {
      serviceImage.onerror = function() {
        this.src = 'images/service/service-default.jpg';
      };
    }

    // Appointment date and time slot handling
    const dateInput = document.getElementById('date');
    const doctorSelect = document.getElementById('doctor');
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const timeSlotMessage = document.getElementById('timeSlotMessage');
    const noSlotsMessage = document.getElementById('noSlotsMessage');

    if (dateInput && doctorSelect && timeSlotsContainer) {
      // Show initial message
      timeSlotMessage.classList.remove('d-none');

      // Function to check availability of time slots
      function checkTimeSlotAvailability() {
        const selectedDate = dateInput.value;
        const selectedDoctor = doctorSelect.value;

        if (!selectedDate || !selectedDoctor) {
          timeSlotMessage.classList.remove('d-none');
          noSlotsMessage.classList.add('d-none');
          timeSlotsContainer.innerHTML = '';
          return;
        }

        // Hide messages while loading
        timeSlotMessage.classList.add('d-none');
        noSlotsMessage.classList.add('d-none');

        // Show loading indicator
        timeSlotsContainer.innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';

        // Make AJAX request to get available time slots
        fetch(`get_available_slots.php?date=${selectedDate}&doctor=${selectedDoctor}`)
          .then(response => response.json())
          .then(data => {
            // Clear container
            timeSlotsContainer.innerHTML = '';

            if (data.success && data.available_slots && Array.isArray(data.available_slots)) {
              if (data.available_slots.length === 0) {
                // No slots available
                noSlotsMessage.classList.remove('d-none');
              } else {
                // Generate time slots
                data.available_slots.forEach((slot, index) => {
                  // Create slot HTML
                  const slotId = `time_${slot.replace(/[:\s-]/g, '_')}`;
                  const slotHtml = `
                    <div class="col-6 mb-2">
                      <div class="time-slot">
                        <input type="radio" name="time" id="${slotId}" value="${slot}" class="time-slot-input" required>
                        <label for="${slotId}" class="time-slot-label">${slot}</label>
                      </div>
                    </div>
                  `;
                  timeSlotsContainer.innerHTML += slotHtml;
                });
              }
            } else {
              // Error handling
              timeSlotsContainer.innerHTML = '<div class="col-12"><div class="alert alert-danger">Không thể tải khung giờ. Vui lòng thử lại sau.</div></div>';
              console.error('Error loading time slots:', data.message || 'Unknown error');
            }
          })
          .catch(error => {
            timeSlotsContainer.innerHTML = '<div class="col-12"><div class="alert alert-danger">Không thể tải khung giờ. Vui lòng thử lại sau.</div></div>';
            console.error('Error checking availability:', error);
          });
      }

      // Add event listeners
      dateInput.addEventListener('change', checkTimeSlotAvailability);
      doctorSelect.addEventListener('change', checkTimeSlotAvailability);

      // Validate date selection to disable Sundays
      dateInput.addEventListener('input', function() {
        const selectedDate = new Date(this.value);
        const dayOfWeek = selectedDate.getDay(); // 0 = Sunday, 6 = Saturday

        if (dayOfWeek === 0) { // Sunday
          alert('Chúng tôi không làm việc vào Chủ Nhật. Vui lòng chọn ngày khác.');
          this.value = ''; // Clear the date
        }
      });
    }
  });
</script>

</body>
</html>