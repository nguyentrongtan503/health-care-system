<?php
session_start();
include './model/dbconnect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: ./View/signin.php");
    exit();
}

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: user_profile.php");
    exit();
}

$conn = connectDB();
$username = $_SESSION['username'];
$newEmail = $_POST['new_email'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

// Kiểm tra dữ liệu đầu vào
if (empty($newEmail) || empty($passwordConfirm)) {
    $_SESSION['email_message'] = 'Vui lòng điền đầy đủ thông tin!';
    header("Location: user_profile.php");
    exit();
}

// Kiểm tra mật khẩu
$query = "SELECT * FROM LoaiTaiKhoan WHERE TenTaiKhoan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

if (!$userData) {
    $_SESSION['email_message'] = 'Không tìm thấy thông tin tài khoản!';
    header("Location: user_profile.php");
    exit();
}

// Kiểm tra mật khẩu
if ($passwordConfirm !== $userData['Password']) {
    $_SESSION['email_message'] = 'Mật khẩu xác nhận không đúng!';
    header("Location: user_profile.php");
    exit();
}

// Cập nhật email trong bảng LoaiTaiKhoan
$userID = $userData['UserID'];
$query = "UPDATE LoaiTaiKhoan SET Email = ? WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $newEmail, $userID);

if (!$stmt->execute()) {
    $_SESSION['email_message'] = 'Cập nhật email thất bại: ' . $conn->error;
    header("Location: user_profile.php");
    exit();
}

// Cập nhật email trong bảng KhachHang nếu có
$query = "SELECT * FROM KhachHang WHERE UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$customerData = $result->fetch_assoc();

if ($customerData) {
    $query = "UPDATE KhachHang SET Email = ? WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $newEmail, $userID);
    $stmt->execute();
}

// Đóng kết nối
$conn->close();

// Thông báo thành công
$_SESSION['email_message'] = 'Cập nhật email thành công!';
$_SESSION['email_success'] = true;
header("Location: user_profile.php");
exit();
?>
