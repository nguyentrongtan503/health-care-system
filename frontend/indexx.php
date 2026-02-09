<?php
session_start();
require_once './model/dcFrontend.php';

// Tạo kết nối cơ sở dữ liệu
$conn = connectDB();

// Include header
include __DIR__ . "/View/header.php"; 
?>


<!-- Slider Start -->
<section class="banner">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-12 col-xl-7">
				<div class="block">
					<div class="divider mb-3"></div>
					<span class="text-uppercase text-sm letter-spacing ">Giải pháp chăm sóc sức khỏe tổng thể</span>
					<h1 class="mb-3 mt-3">Đối tác sức khỏe đáng tin cậy nhất của bạn</h1>
					
					<p class="mb-4 pr-5">Mọi thứ đều diễn ra suôn sẻ và chuyên nghiệp. Đội ngũ hỗ trợ thân thiện, nhiệt tình đã giúp tôi giải quyết vấn đề nhanh chóng. </p>
					<div class="btn-container ">
						<a href="./goi_y_suc_khoe.php" class="btn btn-main-2 btn-icon btn-round-full">Gợi ý sức khỏe <i class="icofont-simple-right ml-2  "></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="features"> 
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="feature-block d-lg-flex">
					<div class="feature-item mb-5 mb-lg-0">
						<div class="feature-icon mb-4">
							<i class="icofont-surgeon-alt"></i>
						</div>
						<span>Dịch vụ 24 giờ</span>
						<h4 class="mb-3">Cuộc hẹn trực tuyến</h4>
						<p class="mb-4">Nhận tất cả thời gian hỗ trợ cho khẩn cấp. Chúng tôi đã giới thiệu nguyên tắc y học gia đình.</p>
						<a href="./appoinment.php" class="btn btn-main btn-round-full">Đặt lịch hẹn</a>
					</div>
				
					<div class="feature-item mb-5 mb-lg-0">
						<div class="feature-icon mb-4">
							<i class="icofont-ui-clock"></i>
						</div>
						<span>Lịch thời gian</span>
						<h4 class="mb-3">Giờ làm việc</h4>
						<ul class="w-hours list-unstyled">
		                    <li class="d-flex justify-content-between">Sun - Wed : <span>8:00 - 17:00</span></li>
		                    <li class="d-flex justify-content-between">Thu - Fri : <span>9:00 - 17:00</span></li>
		                    <li class="d-flex justify-content-between">Sat - sun : <span>10:00 - 17:00</span></li>
		                </ul>
					</div>
				
					<div class="feature-item mb-5 mb-lg-0">
						<div class="feature-icon mb-4">
							<i class="icofont-support"></i>
						</div>
						<span>Trường hợp khẩn cấp</span>
						<h4 class="mb-3">1-800-700-6200</h4>
						<p>Nhận tất cả thời gian hỗ trợ cho khẩn cấp. Chúng tôi đã giới thiệu nguyên tắc y học gia đình. .</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section service gray-bg">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 text-center">
				<div class="section-title">
					<h2>Dịch vụ chăm sóc bệnh nhân</h2>
					<div class="divider mx-auto my-4"></div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2 col-md-4 col-sm-6">
				<div class="service-item mb-4">
					<div class="icon d-flex align-items-center">
						<i class="icofont-laboratory text-lg"></i>
						<h4 class="mt-3 mb-3">Dịch vụ phòng</h4>
					</div>
				</div>
			</div>

			<div class="col-lg-2 col-md-4 col-sm-6">
				<div class="service-item mb-4">
					<div class="icon d-flex align-items-center">
						<i class="icofont-heart-beat-alt text-lg"></i>
						<h4 class="mt-3 mb-3">Bệnh tim</h4>
					</div>
				</div>
			</div>
			
			<div class="col-lg-2 col-md-4 col-sm-6">
				<div class="service-item mb-4">
					<div class="icon d-flex align-items-center">
						<i class="icofont-tooth text-lg"></i>
						<h4 class="mt-3 mb-3">Chăm sóc nha khoa</h4>
					</div>
				</div>
			</div>


			<div class="col-lg-2 col-md-4 col-sm-6">
				<div class="service-item mb-4">
					<div class="icon d-flex align-items-center">
						<i class="icofont-crutch text-lg"></i>
						<h4 class="mt-3 mb-3">Phẫu thuật cơ thể</h4>
					</div>
				</div>
			</div>

			<div class="col-lg-2 col-md-4 col-sm-6">
				<div class="service-item mb-4">
					<div class="icon d-flex align-items-center">
						<i class="icofont-brain-alt text-lg"></i>
						<h4 class="mt-3 mb-3">Thần kinh</h4>
					</div>
				</div>
			</div>
			
			<div class="col-lg-2 col-md-4 col-sm-6">
				<div class="service-item mb-4">
					<div class="icon d-flex align-items-center">
						<i class="icofont-dna-alt-1 text-lg"></i>
						<h4 class="mt-3 mb-3">Phụ khoa</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- sản phẩm nổi bật -->

<?php include 'View/featured_products.php';?>


<section class="section about">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-4 col-sm-6">
				<div class="about-img">
					<img src="images/about/img-1.jpg" alt="" class="img-fluid">
					<img src="images/about/img-2.jpg" alt="" class="img-fluid mt-4">
				</div>
			</div>
			<div class="col-lg-4 col-sm-6">
				<div class="about-img mt-4 mt-lg-0">
					<img src="images/about/img-3.jpg" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-4">
				<div class="about-content pl-4 mt-4 mt-lg-0">
					<h2 class="title-color">Chăm sóc cá nhân <br>& cuộc sống lành mạnh</h2>
					<p class="mt-4 mb-5">Chúng tôi cung cấp dịch vụ hàng đầu, chất lượng vượt trội, đáp ứng mọi nhu cầu của bạn với sự tận tâm và chuyên nghiệp.</p>

					<a href="./service.php" class="btn btn-main-2 btn-round-full btn-icon">Services<i class="icofont-simple-right ml-3"></i></a>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="cta-section ">
	<div class="container">
		<div class="cta position-relative">
			<div class="row">
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-doctor"></i>
						<span class="h3">58</span>k
						<p>Người dùng/p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-flag"></i>
						<span class="h3">700</span>+
						<p>Phẫu thuật hoàn thành</p>
					</div>
				</div>
				
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-badge"></i>
						<span class="h3">40</span>+
						<p>Chuyên gia bác sĩ</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="counter-stat">
						<i class="icofont-globe"></i>
						<span class="h3">20</span>
						<p>Chi nhánh trên toàn thế giới</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- cuộc hẹn  -->
<section class="section appoinment">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 ">
				<div class="appoinment-content">
					<img src="images/about/img-3.jpg" alt="" class="img-fluid">
					<div class="emergency">
						<h2 class="text-lg"><i class="icofont-phone-circle text-lg"></i>+23 345 67980</h2>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-10 ">
			<div class="appoinment-wrap mt-5 mt-lg-0 pl-lg-5">
            <h2 class="mb-2 title-color">Đặt một cuộc hẹn</h2>
            <p class="mb-4">các bác sĩ tại bệnh viện sẽ tìm cách giải quyết một cách hiệu quả và tương tự như các phương pháp đã được áp dụng.</p>
               <form id="#" class="appoinment-form" method="post" action="./model/process_appoiment.php">
                    <div class="row">
                         <div class="col-lg-6">
                            <div class="form-group">
                            <select class="form-control" id="service" name="service" required>
                                <option value="">Chọn dịch vụ</option>
                                <?php
                                $query = "SELECT MaLoaiDichVu, TenLoaiDichVu FROM LoaiDichVu";
                                $result = executeQuery($conn, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . htmlspecialchars($row['MaLoaiDichVu']) . "'>" . htmlspecialchars($row['TenLoaiDichVu']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Không có loại dịch vụ nào</option>";
                                }
                                ?>
                            </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                            <select class="form-control" id="doctor" name="doctor" required>
                                <option value="">Chọn bác sĩ</option>
                                <?php
                                $query = "SELECT MaNhanVien, TenNhanVien FROM NhanVien";
                                $result = executeQuery($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['MaNhanVien']) . "'>" . htmlspecialchars($row['TenNhanVien']) . "</option>";
                                }
                                ?>
                            </select>
                            </div>
                        </div>

                         <div class="col-lg-6">
                            <div class="form-group">
                                <input name="date" id="date" type="text" class="form-control" placeholder="dd/mm/yyyy">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <input name="time" id="time" type="text" class="form-control" placeholder="Time">
                            </div>
                        </div>
                         <div class="col-lg-6">
                            <div class="form-group">
                                <input name="name" id="name" type="text" class="form-control" placeholder="Full Name">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <input name="phone" id="phone" type="Number" class="form-control" placeholder="Phone Number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group-2 mb-4">
                        <textarea name="message" id="message" class="form-control" rows="6" placeholder="Your Message"></textarea>
                    </div>

                    <button type="submit" class="btn btn-main btn-round-full">Đặt lịch hẹn<i class="icofont-simple-right ml-2"></i></button>
                </form>
            </div>
        </div>
      </div>
    </div>
	</div>
</section>
<section class="section testimonial-2 gray-bg">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="section-title text-center">
					<h2>Khách hàng nói gì</h2>
					<div class="divider mx-auto my-4"></div>
					<p>Những đánh giá chân thực từ khách hàng đã sử dụng dịch vụ từ chúng tôi.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-12 testimonial-wrap-2">
				<div class="testimonial-block style-2  gray-bg">
					<i class="icofont-quote-right"></i>

					<div class="testimonial-thumb">
						<img src="images/team/test-thumb1.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info ">
						<h4>Dịch vụ tuyệt vời!</h4>
						<span>Nguyễn Trọng Tấn</span>
						<p>
						Tôi rất hài lòng với dịch vụ mà các bạn cung cấp. Mọi thứ đều diễn ra suôn sẻ và chuyên nghiệp. Đội ngũ hỗ trợ thân thiện, nhiệt tình đã giúp tôi giải quyết vấn đề nhanh chóng. Cảm ơn vì đã tạo điều kiện để tôi có trải nghiệm tốt và dễ dàng gửi phản hồi!
						</p>
					</div>
				</div>

				<div class="testimonial-block style-2  gray-bg">
					<div class="testimonial-thumb">
						<img src="images/team/test-thumb2.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Bác sĩ chuyên nghiệp!</h4>
						<span>Nguyễn Trọng Tấn</span>
						<p>
						Tôi rất hài lòng với dịch vụ mà các bạn cung cấp. Mọi thứ đều diễn ra suôn sẻ và chuyên nghiệp. Đội ngũ hỗ trợ thân thiện, nhiệt tình đã giúp tôi giải quyết vấn đề nhanh chóng. Cảm ơn vì đã tạo điều kiện để tôi có trải nghiệm tốt và dễ dàng gửi phản hồi!
						</p>
					</div>
					
					<i class="icofont-quote-right"></i>
				</div>

				<div class="testimonial-block style-2  gray-bg">
					<div class="testimonial-thumb">
						<img src="images/team/test-thumb3.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Hỗ Trợ tốt</h4>
						<span>Nguyễn Trọng Tấn</span>
						<p>
						Tôi rất hài lòng với dịch vụ mà các bạn cung cấp. Mọi thứ đều diễn ra suôn sẻ và chuyên nghiệp. Đội ngũ hỗ trợ thân thiện, nhiệt tình đã giúp tôi giải quyết vấn đề nhanh chóng. Cảm ơn vì đã tạo điều kiện để tôi có trải nghiệm tốt và dễ dàng gửi phản hồi!
						</p>
					</div>
					
					<i class="icofont-quote-right"></i>
				</div>

				<div class="testimonial-block style-2  gray-bg">
					<div class="testimonial-thumb">
						<img src="images/team/test-thumb4.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Môi trường đẹp</h4>
						<span>Nguyễn Trọng Tấn</span>
						<p class="mt-4">
						Tôi rất hài lòng với dịch vụ mà các bạn cung cấp. Mọi thứ đều diễn ra suôn sẻ và chuyên nghiệp. Đội ngũ hỗ trợ thân thiện, nhiệt tình đã giúp tôi giải quyết vấn đề nhanh chóng. Cảm ơn vì đã tạo điều kiện để tôi có trải nghiệm tốt và dễ dàng gửi phản hồi!
						</p>
					</div>
					<i class="icofont-quote-right"></i>
				</div>

				<div class="testimonial-block style-2  gray-bg">
					<div class="testimonial-thumb">
						<img src="images/team/test-thumb1.jpg" alt="" class="img-fluid">
					</div>

					<div class="client-info">
						<h4>Dịch vụ hiện đại</h4>
						<span>Trọng Tấn</span>
						<p>
						Tôi rất hài lòng với dịch vụ mà các bạn cung cấp. Mọi thứ đều diễn ra suôn sẻ và chuyên nghiệp. Đội ngũ hỗ trợ thân thiện, nhiệt tình đã giúp tôi giải quyết vấn đề nhanh chóng. Cảm ơn vì đã tạo điều kiện để tôi có trải nghiệm tốt và dễ dàng gửi phản hồi!
						</p>
					</div>
					<i class="icofont-quote-right"></i>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section clients">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7">
				<div class="section-title text-center">
					<h2>Các đối tác hỗ trợ chúng tôi</h2>
					<div class="divider mx-auto my-4"></div>
					<p>Chúng tôi đánh giá cao sự hợp tác với các đối tác hỗ trợ chuyên nghiệp và tận tâm.</p>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row clients-logo">
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/1.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/2.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/3.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/4.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/5.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/6.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/3.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/4.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/5.png" alt="" class="img-fluid">
				</div>
			</div>
			<div class="col-lg-2">
				<div class="client-thumb">
					<img src="images/about/6.png" alt="" class="img-fluid">
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
	<script src="./js/cart.js"></script>

  </body>
  </html>
   