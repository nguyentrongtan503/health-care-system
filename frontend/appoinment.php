<?php
session_start();
// Include file dcFrontend.php (đã include dbconnect.php)
require_once './model/dcFrontend.php';

// Tạo kết nối cơ sở dữ liệu
$conn = connectDB();

// Include header
include __DIR__ . "/View/header.php";
?>
<!-- .......................... -->
<style>
/* Reset CSS để đảm bảo tất cả các trường form đều hiển thị */
.appoinment-form * {
  display: block;
  visibility: visible;
  opacity: 1;
}

.appoinment-form .form-group {
  margin-bottom: 20px;
  display: block !important;
  visibility: visible !important;
  opacity: 1 !important;
}

.appoinment-form .form-label {
  font-weight: bold;
  margin-bottom: 8px;
  display: block !important;
}

.appoinment-form .form-control {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  display: block !important;
}

.appoinment-form .row {
  display: flex !important;
  flex-wrap: wrap !important;
  margin-left: -15px;
  margin-right: -15px;
}

.appoinment-form .col-md-3,
.appoinment-form .col-6 {
  padding-left: 15px;
  padding-right: 15px;
  display: block !important;
}

.appoinment-form .form-check {
  display: block !important;
  margin-bottom: 10px;
}

.appoinment-form .form-check-input {
  display: inline-block !important;
  margin-right: 5px;
}

.appoinment-form .form-check-label {
  display: inline-block !important;
}

.appoinment-form .btn {
  display: inline-block !important;
  padding: 10px 20px;
  background-color: #223a66;
  color: white;
  border: none;
  border-radius: 30px;
  cursor: pointer;
}

.appoinment-form .text-danger {
  color: #dc3545 !important;
}
</style>

<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Đặt lịch khám</span>
          <h1 class="text-capitalize mb-5 text-lg">Đặt lịch hẹn</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="appoinment section">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
          <div class="mt-3">
            <div class="feature-icon mb-3">
              <i class="icofont-support text-lg"></i>
            </div>
             <span class="h3">Gọi cho một dịch vụ khẩn cấp!</span>
              <h2 class="text-color mt-3">+84 868366503 </h2>
          </div>
      </div>

      <div class="col-lg-8">
           <div class="appoinment-wrap mt-5 mt-lg-0 pl-lg-5">
            <h2 class="mb-2 title-color">Đặt lịch khám</h2>
            <p class="mb-4">Vui lòng điền đầy đủ thông tin để đặt lịch khám với bác sĩ. Chúng tôi sẽ liên hệ với bạn để xác nhận lịch hẹn.</p>
               <form id="appointment-form" class="appoinment-form shadow-sm rounded p-4" method="post" action="./model/process_appoiment.php">
                    <!-- Dịch vụ -->
                    <div class="form-group mb-4">
                        <label for="service" class="form-label fw-bold">Chọn dịch vụ <span class="text-danger">*</span></label>
                        <select class="form-control" id="service" name="service" required>
                            <option value="">Chọn dịch vụ</option>
                            <?php
                            // Check if service_id is passed from service-single.php
                            $preselectedService = isset($_GET['service_id']) ? $_GET['service_id'] : '';

                            $query = "SELECT MaLoaiDichVu, TenLoaiDichVu FROM LoaiDichVu";
                            $result = executeQuery($conn, $query);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = ($row['MaLoaiDichVu'] == $preselectedService) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($row['MaLoaiDichVu']) . "' $selected>" . htmlspecialchars($row['TenLoaiDichVu']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>Không có loại dịch vụ nào</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Bác sĩ -->
                    <div class="form-group mb-4">
                        <label for="doctor" class="form-label fw-bold">Chọn bác sĩ <span class="text-danger">*</span></label>
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

                    <!-- Ngày hẹn -->
                    <div class="form-group mb-4">
                        <label for="date" class="form-label fw-bold">Ngày hẹn <span class="text-danger">*</span></label>
                        <input name="date" id="date" type="date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <!-- Thời gian -->
                    <div class="form-group mb-4">
                        <label class="form-label fw-bold">Thời gian <span class="text-danger">*</span></label>
                        <div id="timeSlotMessage" class="alert alert-info d-none">
                            Vui lòng chọn bác sĩ và ngày hẹn để xem các khung giờ khả dụng.
                        </div>
                        <div id="noSlotsMessage" class="alert alert-warning d-none">
                            Không có khung giờ nào khả dụng cho ngày đã chọn. Vui lòng chọn ngày khác.
                        </div>
                        <div id="allBookedMessage" class="alert alert-danger d-none">
                            Tất cả các khung giờ đã được đặt cho ngày và bác sĩ này. Vui lòng chọn ngày khác hoặc bác sĩ khác.
                        </div>
                        <div class="time-slots-container">
                            <div class="row" id="timeSlotsContainer">
                                <!-- Time slots will be dynamically populated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Họ tên -->
                    <div class="form-group mb-4">
                        <label for="name" class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                        <input name="name" id="name" type="text" class="form-control" placeholder="Nhập họ tên của bạn" required>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="form-group mb-4">
                        <label for="phone" class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                        <input name="phone" id="phone" type="tel" class="form-control" placeholder="Nhập số điện thoại" required>
                    </div>

                    <!-- Lời nhắn -->
                    <div class="form-group mb-4">
                        <label for="message" class="form-label fw-bold">Lời nhắn</label>
                        <textarea name="message" id="message" class="form-control" rows="4" placeholder="Nhập lời nhắn hoặc triệu chứng của bạn"></textarea>
                    </div>

                    <!-- Nút đặt lịch -->
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-main btn-round-full">Đặt lịch hẹn</button>
                    </div>
                </form>
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
        background-color: #ffeeee;
        color: #dc3545;
        cursor: not-allowed;
        border-color: #dc3545;
        position: relative;
        opacity: 0.8;
        font-weight: bold;
        background-color: #dc3545;
        color: white;
      }

      .time-slot-input:disabled + .time-slot-label::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 2px solid white;
      }

      /* Đảm bảo tất cả các trường form đều hiển thị */
      .form-group {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
      }

      .appoinment-form .row {
        display: flex !important;
        flex-wrap: wrap !important;
      }

      .appoinment-form .col-lg-6 {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
      }
    </style>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Đảm bảo tất cả các trường form đều hiển thị
        const form = document.querySelector('.appoinment-form');
        if (form) {
          // Hiển thị form
          form.style.display = 'block';
          form.style.visibility = 'visible';
          form.style.opacity = '1';

          // Hiển thị tất cả các phần tử con của form
          const formElements = form.querySelectorAll('*');
          formElements.forEach(element => {
            if (element.tagName !== 'OPTION') { // Không áp dụng cho option trong select
              element.style.display = element.tagName === 'INPUT' || element.tagName === 'SELECT' || element.tagName === 'TEXTAREA' ? 'block' : '';
              element.style.visibility = 'visible';
              element.style.opacity = '1';
            }
          });

          // Hiển thị các form-group
          const formGroups = form.querySelectorAll('.form-group');
          formGroups.forEach(group => {
            group.style.display = 'block';
            group.style.visibility = 'visible';
            group.style.opacity = '1';

            // Hiển thị label và input trong form-group
            const labels = group.querySelectorAll('label');
            const inputs = group.querySelectorAll('input, select, textarea');

            labels.forEach(label => {
              label.style.display = 'block';
              label.style.visibility = 'visible';
              label.style.opacity = '1';
            });

            inputs.forEach(input => {
              if (input.type !== 'radio' && input.type !== 'checkbox') {
                input.style.display = 'block';
              } else {
                input.style.display = 'inline-block';
              }
              input.style.visibility = 'visible';
              input.style.opacity = '1';
            });
          });
        }

        // Get form elements
        const dateInput = document.getElementById('date');
        const doctorSelect = document.getElementById('doctor');
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const timeSlotMessage = document.getElementById('timeSlotMessage');
        const noSlotsMessage = document.getElementById('noSlotsMessage');
        const allBookedMessage = document.getElementById('allBookedMessage');

        // Pre-fill form if parameters are passed from service-single.php
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('quick_name')) {
          document.getElementById('name').value = urlParams.get('quick_name');
        }
        if (urlParams.has('quick_phone')) {
          document.getElementById('phone').value = urlParams.get('quick_phone');
        }
        if (urlParams.has('service_id')) {
          const serviceSelect = document.getElementById('service');
          if (serviceSelect) {
            serviceSelect.value = urlParams.get('service_id');
          }
        }

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
              allBookedMessage.classList.add('d-none');
              timeSlotsContainer.innerHTML = '';
              return;
            }

            // Hide messages while loading
            timeSlotMessage.classList.add('d-none');
            noSlotsMessage.classList.add('d-none');
            allBookedMessage.classList.add('d-none');

            // Show loading indicator
            timeSlotsContainer.innerHTML = '<div class="col-12 text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';

            // Make AJAX request to get available time slots
            console.log(`Fetching available slots for date: ${selectedDate}, doctor: ${selectedDoctor}`);
            fetch(`./get_available_slots.php?date=${selectedDate}&doctor=${selectedDoctor}`)
              .then(response => {
                console.log('Response status:', response.status);
                return response.json();
              })
              .then(data => {
                // Clear container
                timeSlotsContainer.innerHTML = '';

                // Debug
                console.log('Response data:', data);
                console.log('Booked slots:', data.booked_slots);
                console.log('Booked slots with status:', data.booked_slots_with_status);
                console.log('Available slots:', data.available_slots);
                console.log('All slots:', data.all_slots);
                console.log('Date:', data.date);
                console.log('Doctor:', data.doctor);

                if (data.success && data.available_slots && Array.isArray(data.available_slots)) {
                  if (data.all_slots.length === 0) {
                    // No slots available for this day
                    noSlotsMessage.classList.remove('d-none');
                    console.log('No slots available for this day');
                  } else if (data.available_slots.length === 0 && data.all_slots.length > 0) {
                    // All slots are booked
                    allBookedMessage.classList.remove('d-none');
                    console.log('All slots are booked');

                    // Still show all slots but disabled
                    data.all_slots.forEach((slot, index) => {
                      // Get the start time of the slot
                      const slotStartTime = slot.split(' - ')[0];

                      // Debug the slot and booked slots
                      console.log(`Checking disabled slot: ${slot}, start time: ${slotStartTime}`);
                      console.log(`Booked slots with status: ${JSON.stringify(data.booked_slots_with_status)}`);

                      // Get status if booked
                      let status = '';
                      if (data.booked_slots_with_status) {
                        const bookedSlot = data.booked_slots_with_status.find(s => {
                          // Extract the start time from the booked slot
                          let bookedStartTime;
                          if (s.time.includes(' - ')) {
                            bookedStartTime = s.time.split(' - ')[0].trim();
                          } else {
                            // Nếu là thời gian từ DB, có thể có định dạng "11:00:00"
                            // Cắt bỏ phần giây nếu có
                            if (s.time.trim().length > 5) {
                              bookedStartTime = s.time.trim().substring(0, 5);
                            } else {
                              bookedStartTime = s.time.trim(); // If it's already just the start time
                            }
                          }

                          // Trim the slot start time to ensure exact comparison
                          const trimmedSlotStartTime = slotStartTime.trim();

                          console.log(`Comparing disabled slot start time: '${trimmedSlotStartTime}' with booked start time: '${bookedStartTime}'`);

                          // Check if the slot is in the booked slots array
                          const exactMatch = data.booked_slots.includes(slot);
                          if (exactMatch) {
                            console.log(`EXACT MATCH FOUND (disabled): ${slot} is in booked slots array`);
                          }

                          // Return true if either the start times match or the exact slot is in the booked slots array
                          return bookedStartTime === trimmedSlotStartTime || exactMatch || s.time === slot;
                        });

                        if (bookedSlot) {
                          status = bookedSlot.status;
                        }
                      }

                      const slotId = `time_${slot.replace(/[:\s-]/g, '_')}`;
                      const slotHtml = `
                        <div class="col-md-3 col-6 mb-2">
                          <div class="time-slot">
                            <input class="time-slot-input" type="radio" name="time" id="${slotId}" value="${slot}" disabled required>
                            <label class="time-slot-label" for="${slotId}">${slot}</label>
                          </div>
                        </div>
                      `;
                      timeSlotsContainer.innerHTML += slotHtml;
                      console.log(`Added disabled slot: ${slot} (start time: ${slotStartTime}) ${status ? '- Status: ' + status : ''}`);
                    });
                  } else {
                    // Generate all time slots (both available and booked)
                    data.all_slots.forEach((slot, index) => {
                      // Check if slot is booked - only compare the first part of the time slot (e.g., "08:00" from "08:00 - 08:30")
                      // This is because the database stores only the starting time
                      const slotStartTime = slot.split(' - ')[0];

                      // Debug the slot and booked slots
                      console.log(`Checking slot: ${slot}, start time: ${slotStartTime}`);
                      console.log(`Booked slots: ${JSON.stringify(data.booked_slots)}`);

                      // Check if any booked slot starts with this time
                      const isBooked = data.booked_slots && Array.isArray(data.booked_slots) &&
                                      data.booked_slots.some(bookedSlot => {
                                        // Extract the start time from the booked slot
                                        let bookedStartTime;
                                        if (bookedSlot.includes(' - ')) {
                                          bookedStartTime = bookedSlot.split(' - ')[0].trim();
                                        } else {
                                          // Nếu là thời gian từ DB, có thể có định dạng "11:00:00"
                                          // Cắt bỏ phần giây nếu có
                                          if (bookedSlot.trim().length > 5) {
                                            bookedStartTime = bookedSlot.trim().substring(0, 5);
                                          } else {
                                            bookedStartTime = bookedSlot.trim();
                                          }
                                        }

                                        // Trim the slot start time to ensure exact comparison
                                        const trimmedSlotStartTime = slotStartTime.trim();

                                        console.log(`Comparing slot start time: '${trimmedSlotStartTime}' with booked start time: '${bookedStartTime}'`);

                                        // Check if the slot is in the booked slots array
                                        const exactMatch = data.booked_slots.includes(slot);
                                        if (exactMatch) {
                                          console.log(`EXACT MATCH FOUND: ${slot} is in booked slots array`);
                                        }

                                        // Return true if either the start times match or the exact slot is in the booked slots array
                                        return bookedStartTime === trimmedSlotStartTime || exactMatch;
                                      });

                      // Get status if booked
                      let status = '';
                      if (isBooked && data.booked_slots_with_status) {
                        const bookedSlot = data.booked_slots_with_status.find(s => {
                          let bookedStartTime;
                          if (s.time.includes(' - ')) {
                            bookedStartTime = s.time.split(' - ')[0].trim();
                          } else {
                            // Nếu là thời gian từ DB, có thể có định dạng "11:00:00"
                            // Cắt bỏ phần giây nếu có
                            if (s.time.trim().length > 5) {
                              bookedStartTime = s.time.trim().substring(0, 5);
                            } else {
                              bookedStartTime = s.time.trim();
                            }
                          }

                          const trimmedSlotStartTime = slotStartTime.trim();

                          console.log(`Comparing status slot time: '${trimmedSlotStartTime}' with booked time: '${bookedStartTime}'`);

                          // Check for exact match or start time match
                          return bookedStartTime === trimmedSlotStartTime || s.time === slot;
                        });

                        if (bookedSlot) {
                          status = bookedSlot.status;
                        }
                      }

                      // Debug
                      console.log(`Slot ${slot} (start time: ${slotStartTime}) is booked: ${isBooked}${status ? ', Status: ' + status : ''}`);

                      // Create slot HTML
                      const slotId = `time_${slot.replace(/[:\s-]/g, '_')}`;
                      const slotHtml = `
                        <div class="col-md-3 col-6 mb-2">
                          <div class="time-slot">
                            <input class="time-slot-input" type="radio" name="time" id="${slotId}" value="${slot}" ${isBooked ? 'disabled' : ''} required>
                            <label class="time-slot-label" for="${slotId}">${slot}</label>
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

          // If date is pre-filled, trigger change event
          if (urlParams.has('quick_date')) {
            dateInput.value = urlParams.get('quick_date');
            // Only trigger if doctor is also selected
            if (doctorSelect.value) {
              const event = new Event('change');
              dateInput.dispatchEvent(event);
            }
          }
        }

        // Thêm sự kiện submit cho form
        const appointmentForm = document.getElementById('appointment-form');
        if (appointmentForm) {
          appointmentForm.addEventListener('submit', function(e) {
            // Kiểm tra xem đã chọn đủ thông tin chưa
            const service = document.getElementById('service').value;
            const doctor = document.getElementById('doctor').value;
            const date = document.getElementById('date').value;
            const time = document.querySelector('input[name="time"]:checked');
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;

            if (!service || !doctor || !date || !time || !name || !phone) {
              e.preventDefault();
              alert('Vui lòng điền đầy đủ thông tin để đặt lịch.');
              return false;
            }
          });
        }
      });
    </script>

  </body>
  </html>