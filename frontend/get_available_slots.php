<?php
// Include database connection
include __DIR__ . "/model/dbconnect.php";

// Get parameters
$date = $_GET['date'] ?? '';
$doctor = $_GET['doctor'] ?? '';

// Debug
error_log("Received parameters - date: $date, doctor: $doctor");

// Validate input
if (empty($date) || empty($doctor)) {
    error_log("Missing required parameters - date: $date, doctor: $doctor");
    echo json_encode([
        'success' => false,
        'message' => 'Missing required parameters',
        'available_slots' => []
    ]);
    exit;
}

// Connect to database
$conn = connectDB();

// Debug
error_log("Database connection established: " . ($conn ? "Yes" : "No"));

// Get day of week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
$dayOfWeek = date('w', strtotime($date));

// Define available time slots based on day of week
$availableSlots = [];

if ($dayOfWeek == 0) {
    // Sunday - No slots available
    $availableSlots = [];
} else if ($dayOfWeek == 6) {
    // Saturday - 8:00 to 12:00
    $availableSlots = [
        '08:00 - 08:30',
        '08:30 - 09:00',
        '09:00 - 09:30',
        '09:30 - 10:00',
        '10:00 - 10:30',
        '10:30 - 11:00',
        '11:00 - 11:30',
        '11:30 - 12:00'
    ];
} else {
    // Monday to Friday - 8:00 to 17:00
    $availableSlots = [
        '08:00 - 08:30',
        '08:30 - 09:00',
        '09:00 - 09:30',
        '09:30 - 10:00',
        '10:00 - 10:30',
        '10:30 - 11:00',
        '11:00 - 11:30',
        '11:30 - 12:00',
        '13:00 - 13:30',
        '13:30 - 14:00',
        '14:00 - 14:30',
        '14:30 - 15:00',
        '15:00 - 15:30',
        '15:30 - 16:00',
        '16:00 - 16:30',
        '16:30 - 17:00'
    ];
}

// Query to get booked time slots for the selected date and doctor
// Only consider appointments that are not canceled (status is 'Chưa duyệt' or 'Đã duyệt')
$query = "SELECT GioHen, TrangThai FROM DatLich WHERE NgayHen = ? AND MaNhanVien = ? AND TrangThai != 'Đã hủy'";
error_log("Query: $query with params - date: $date, doctor: $doctor");

// Thêm log để kiểm tra định dạng ngày và bác sĩ
error_log("Date format: " . $date);
error_log("Doctor ID: " . $doctor);

// Debug query directly - show all appointments for this date/doctor regardless of status
$debugStmt = $conn->prepare("SELECT GioHen, TrangThai, MaNguoiDung FROM DatLich WHERE NgayHen = ? AND MaNhanVien = ?");
$debugStmt->bind_param("ss", $date, $doctor);
$debugStmt->execute();
$debugResult = $debugStmt->get_result();
$debugData = [];
while ($row = $debugResult->fetch_assoc()) {
    $debugData[] = $row;
}
error_log("Debug data for all appointments on this date/doctor: " . json_encode($debugData));

// Debug query for non-canceled appointments only
$debugStmt = $conn->prepare("SELECT GioHen, TrangThai, MaNguoiDung FROM DatLich WHERE NgayHen = ? AND MaNhanVien = ? AND TrangThai != 'Đã hủy'");
$debugStmt->bind_param("ss", $date, $doctor);
$debugStmt->execute();
$debugResult = $debugStmt->get_result();
$debugData = [];
while ($row = $debugResult->fetch_assoc()) {
    $debugData[] = $row;
}
error_log("Debug data for non-canceled appointments on this date/doctor: " . json_encode($debugData));

$stmt = $conn->prepare($query);
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $conn->error,
        'available_slots' => []
    ]);
    exit;
}

$stmt->bind_param("ss", $date, $doctor);
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $stmt->error,
        'available_slots' => []
    ]);
    exit;
}

$result = $stmt->get_result();

$bookedSlots = [];
$bookedSlotsWithStatus = [];
while ($row = $result->fetch_assoc()) {
    // Lấy thời gian bắt đầu từ cơ sở dữ liệu và chuyển đổi định dạng
    $dbTime = trim($row['GioHen']);

    // Cắt bỏ phần giây nếu có (11:00:00 -> 11:00)
    $startTime = substr($dbTime, 0, 5);

    // Log để debug
    error_log("Found booked time in database: '" . $dbTime . "', converted to: '" . $startTime . "'");

    // Tìm khung giờ tương ứng trong availableSlots
    $matchingSlot = null;
    foreach ($availableSlots as $slot) {
        $slotStartTime = trim(explode(' - ', $slot)[0]);
        error_log("Comparing with available slot: '" . $slotStartTime . "' from '" . $slot . "'");

        if ($slotStartTime === $startTime) {
            $matchingSlot = $slot;
            error_log("MATCH FOUND: " . $slot);
            break;
        }
    }

    // Nếu tìm thấy khung giờ phù hợp, sử dụng nó; nếu không, sử dụng thời gian gốc
    $timeToUse = $matchingSlot ?? $startTime;
    error_log("Using time: " . $timeToUse . " for booked slot");

    // Thêm vào danh sách các khung giờ đã đặt
    $bookedSlots[] = $timeToUse;
    $bookedSlotsWithStatus[] = [
        'time' => $timeToUse,
        'status' => $row['TrangThai']
    ];
}

// Debug
error_log("Booked slots for date $date and doctor $doctor: " . json_encode($bookedSlots));
error_log("Booked slots with status: " . json_encode($bookedSlotsWithStatus));
error_log("Number of booked slots: " . count($bookedSlots));

// Get all slots (both available and booked)
$allSlots = $availableSlots;

// Remove booked slots from available slots
$finalAvailableSlots = array_diff($availableSlots, $bookedSlots);

// Close connection
$stmt->close();
$conn->close();

// Debug
error_log("Final available slots: " . json_encode(array_values($finalAvailableSlots)));
error_log("Booked slots: " . json_encode($bookedSlots));
error_log("All slots: " . json_encode($allSlots));

// Return JSON response
echo json_encode([
    'success' => true,
    'message' => 'Successfully retrieved available slots',
    'available_slots' => array_values($finalAvailableSlots),
    'booked_slots' => $bookedSlots,
    'booked_slots_with_status' => $bookedSlotsWithStatus,
    'all_slots' => $allSlots,
    'day_of_week' => $dayOfWeek,
    'date' => $date,
    'doctor' => $doctor
]);
?>