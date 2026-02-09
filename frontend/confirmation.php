<?php
session_start();
// Include file dcFrontend.php (đã include dbconnect.php)
require_once './model/dcFrontend.php';

// Tạo kết nối cơ sở dữ liệu
$conn = connectDB();

// Include header
include __DIR__ . "/View/header.php"; 
?>

<section class="section confirmation">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
          <div class="confirmation-content text-center">
            <i class="icofont-check-circled text-lg text-color-2"></i>
              <h2 class="mt-3 mb-4">CẢM ƠN QUÝ KHÁCH</h2>
              <p>Chúng tôi sẽ liên hệ lại bạn.</p>
          </div>
      </div>
    </div>
  </div>
</section>

<!-- footer Start -->
<?php include __DIR__ . "/View/footer.php"; ?>

   

    <!-- 
    Essential Scripts
    =====================================-->

    
    <!-- Main jQuery -->
    <script src="plugins/jquery/jquery.js"></script>
    <!-- Bootstrap 4.3.2 -->
    <script src="plugins/bootstrap/js/popper.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/counterup/jquery.easing.js"></script>
    <!-- Slick Slider -->
    <script src="plugins/slick-carousel/slick/slick.min.js"></script>
    <!-- Counterup -->
    <script src="plugins/counterup/jquery.waypoints.min.js"></script>
    
    <script src="plugins/shuffle/shuffle.min.js"></script>
    <script src="plugins/counterup/jquery.counterup.min.js"></script>
    <!-- Google Map -->
    <script src="plugins/google-map/map.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>    
    
    <script src="js/script.js"></script>
    <script src="js/contact.js"></script>

  </body>
  </html>