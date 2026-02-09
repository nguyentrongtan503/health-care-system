-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jun 08, 2025 at 04:26 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `health`
--

-- --------------------------------------------------------

--
-- Table structure for table `chitietdatlich`
--

CREATE TABLE `chitietdatlich` (
  `MaChiTiet` int(11) NOT NULL,
  `MaDatLich` char(10) NOT NULL,
  `LyDoHuy` text DEFAULT NULL,
  `SoDienThoaiBacSi` varchar(15) DEFAULT NULL,
  `PhongKham` varchar(100) DEFAULT NULL,
  `HuongDan` text DEFAULT NULL,
  `NguoiHuy` enum('User','Admin') DEFAULT NULL,
  `ThoiGianCapNhat` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chitietdatlich`
--

INSERT INTO `chitietdatlich` (`MaChiTiet`, `MaDatLich`, `LyDoHuy`, `SoDienThoaiBacSi`, `PhongKham`, `HuongDan`, `NguoiHuy`, `ThoiGianCapNhat`) VALUES
(1, '17', 'trùng lịch, hẹn khách hàng hôm khác', NULL, NULL, NULL, 'Admin', '2025-04-25 03:52:53'),
(2, '18', NULL, NULL, NULL, NULL, 'User', '2025-04-24 16:40:38'),
(3, '16', NULL, NULL, NULL, NULL, 'User', '2025-04-24 17:09:32'),
(4, '19', NULL, NULL, NULL, NULL, 'User', '2025-04-24 17:09:48'),
(5, '41', 'chắc không phải ', NULL, NULL, NULL, 'Admin', '2025-05-19 11:58:26'),
(6, '42', 'trùng lịch hẹn ', NULL, NULL, NULL, 'Admin', '2025-05-16 16:54:21'),
(7, '28', NULL, '0868366503', 'PHÒNG 2 tầng 2', 'mang CCCD', NULL, '2025-05-19 11:41:27'),
(8, '44', NULL, NULL, NULL, NULL, 'User', '2025-05-23 10:49:51'),
(9, '43', 'hủy', '0868366503', 'PHÒNG 2 tầng 2', 'cccd', 'Admin', '2025-05-23 10:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `MaChiTietDonHang` char(10) NOT NULL,
  `MaDonHang` char(10) NOT NULL,
  `MaSanPham` varchar(50) NOT NULL,
  `HinhAnhSanPham` varchar(255) DEFAULT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `Gia` decimal(10,2) NOT NULL,
  `TongGia` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`MaChiTietDonHang`, `MaDonHang`, `MaSanPham`, `HinhAnhSanPham`, `TenSanPham`, `SoLuong`, `Gia`, `TongGia`) VALUES
('CTDH001', 'DH001', 'SP005', './images/child/child5.png', 'Thuốc Berberine 100mg', 1, 30000.00, 30000.00),
('CTDH003', 'DH001', 'SP002', './images/child/child2.png', 'Thực phẩm chức năng Vitamin C 1000mg', 2, 50000.00, 100000.00),
('CTDH004', 'DH003', 'SP006', './images/adult/adult1.png', 'Thuốc Calci-D 600mg', 1, 80000.00, 80000.00);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `CommentID` int(11) NOT NULL,
  `ServiceID` char(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Comment` text NOT NULL,
  `DatePosted` datetime DEFAULT current_timestamp(),
  `Country` varchar(50) DEFAULT 'Unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `datlich`
--

CREATE TABLE `datlich` (
  `MaDatLich` int(11) NOT NULL,
  `MaLoaiDichVu` varchar(10) NOT NULL,
  `MaNhanVien` varchar(10) NOT NULL,
  `NgayHen` date NOT NULL,
  `GioHen` time NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `SoDienThoai` varchar(15) NOT NULL,
  `LoiNhan` text DEFAULT NULL,
  `TrangThai` varchar(20) DEFAULT 'Chưa duyệt',
  `MaNguoiDung` varchar(10) DEFAULT NULL,
  `NgayTao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `datlich`
--

INSERT INTO `datlich` (`MaDatLich`, `MaLoaiDichVu`, `MaNhanVien`, `NgayHen`, `GioHen`, `HoTen`, `SoDienThoai`, `LoiNhan`, `TrangThai`, `MaNguoiDung`, `NgayTao`) VALUES
(27, 'DV006', 'NV001', '2025-05-05', '09:30:00', 'nat', '093283743', 'không có gì', 'Đã hủy', '4', '2025-05-05 09:05:39'),
(28, 'DV001', 'NV001', '2025-05-05', '16:30:00', 'uh', '0987654321', 'không', 'Đã duyệt', '4', '2025-05-05 10:02:19'),
(41, 'DV001', 'NV001', '2025-05-07', '16:30:00', 'uh', '0586402997', 'ò', 'Đã hủy', '4', '2025-05-07 16:00:30'),
(42, 'DV001', 'NV001', '2025-05-15', '16:30:00', 'uhm', '0586402997', 'ờ', 'Chưa duyệt', '4', '2025-05-15 16:44:10'),
(43, 'DV007', 'NV001', '2025-05-23', '10:30:00', 'nguyễn tấn', '0868366503', 'xác nhận nhanh ', 'Đã duyệt', '4', '2025-05-22 13:53:19'),
(44, 'DV001', 'NV001', '2025-05-23', '08:00:00', 'tan', '0123456789', 'ok', 'Đã hủy', '4', '2025-05-23 03:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `giohang`
--

CREATE TABLE `giohang` (
  `MaGioHang` int(11) NOT NULL,
  `MaNguoiDung` varchar(10) NOT NULL,
  `MaSanPham` varchar(10) NOT NULL,
  `SoLuong` int(11) NOT NULL DEFAULT 1,
  `ThanhTien` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `giohang`
--

INSERT INTO `giohang` (`MaGioHang`, `MaNguoiDung`, `MaSanPham`, `SoLuong`, `ThanhTien`) VALUES
(80, '2', 'SP008', 2, 180000.00),
(81, '2', 'SP005', 3, 90000.00);

-- --------------------------------------------------------

--
-- Table structure for table `hinhthucthanhtoan`
--

CREATE TABLE `hinhthucthanhtoan` (
  `MaThanhToan` char(10) NOT NULL,
  `TenHinhThucThanhToan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hinhthucthanhtoan`
--

INSERT INTO `hinhthucthanhtoan` (`MaThanhToan`, `TenHinhThucThanhToan`) VALUES
('TT001', 'Thẻ tín dụng'),
('TT002', 'Tiền mặt');

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKhachHang` char(10) NOT NULL,
  `TenKhachHang` varchar(30) DEFAULT NULL,
  `DiaChi` varchar(225) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL,
  `SDT` int(11) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`MaKhachHang`, `TenKhachHang`, `DiaChi`, `Email`, `SDT`, `UserID`) VALUES
('KH001', 'tannguyen', ', Phường Phúc Tân, Quận Hoàn Kiếm, Thành phố Hà Nội', 'tan123@gmail.com', 868366503, 4),
('KH002', 'tan', 'ok, Phường Nhật Tân, Quận Tây Hồ, Thành phố Hà Nội', 'user@gmail.com', 123456789, 2);

-- --------------------------------------------------------

--
-- Table structure for table `lichsudonhang`
--

CREATE TABLE `lichsudonhang` (
  `MaDonHang` char(10) NOT NULL,
  `MaNguoiDung` char(10) DEFAULT NULL,
  `NgayDatHang` datetime DEFAULT current_timestamp(),
  `TongTien` decimal(10,2) DEFAULT NULL,
  `PhuongThucThanhToan` varchar(20) DEFAULT NULL,
  `HinhThucNhanHang` varchar(20) DEFAULT NULL,
  `HoTenNguoiNhan` varchar(50) DEFAULT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `GhiChu` text DEFAULT NULL,
  `MaTrangThai` char(10) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lichsudonhang`
--

INSERT INTO `lichsudonhang` (`MaDonHang`, `MaNguoiDung`, `NgayDatHang`, `TongTien`, `PhuongThucThanhToan`, `HinhThucNhanHang`, `HoTenNguoiNhan`, `SoDienThoai`, `Email`, `GhiChu`, `MaTrangThai`, `DiaChi`) VALUES
('DH001', 'KH001', '2025-05-22 21:42:33', 130000.00, 'cod', 'delivery', 'tannguyen', '0123456789', 'nguyentrongtan503@gmail.com', '', 'TT003', 'nhà 1, Phường Phúc Xá, Quận Ba Đình, Thành phố Hà Nội'),
('DH003', 'KH001', '2025-05-23 09:36:42', 80000.00, 'cod', 'delivery', 'nguyen tan', '0868366503', 'nguyentrongtan503@gmail.com', '', 'TT001', 'nha1, Phường Đồng Xuân, Quận Hoàn Kiếm, Thành phố Hà Nội');

-- --------------------------------------------------------

--
-- Table structure for table `lichsugoiy`
--

CREATE TABLE `lichsugoiy` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `loai_san_pham` varchar(255) DEFAULT NULL,
  `tuoi` int(11) DEFAULT NULL,
  `gioi_tinh` varchar(50) DEFAULT NULL,
  `chieu_cao` float DEFAULT NULL,
  `can_nang` float DEFAULT NULL,
  `tinh_trang_suc_khoe` varchar(255) DEFAULT NULL,
  `so_thich` varchar(255) DEFAULT NULL,
  `goi_y_che_do_an` text DEFAULT NULL,
  `goi_y_bai_tap` text DEFAULT NULL,
  `goi_y_thuc_pham_chuc_nang` text DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lichsugoiy`
--

INSERT INTO `lichsugoiy` (`id`, `userID`, `loai_san_pham`, `tuoi`, `gioi_tinh`, `chieu_cao`, `can_nang`, `tinh_trang_suc_khoe`, `so_thich`, `goi_y_che_do_an`, `goi_y_bai_tap`, `goi_y_thuc_pham_chuc_nang`, `ngay_tao`) VALUES
(43, 4, 'Thực phẩm chức năng', 22, 'Nam', 151, 50, 'đau đầu, hoa mắt', 'thể dục, thể thao', '[\"Duy trì chế độ ăn cân bằng với nhiều rau xanh và trái cây.\",\"Ưu tiên protein nạc (ức gà, cá) và ngũ cốc nguyên hạt.\",\"Hạn chế đường và chất béo bão hòa (đồ chiên rán, bánh kẹo).\"]', '[\"Tập cardio cường độ cao (HIIT) 3-4 lần\\/tuần.\",\"Tập tạ để phát triển cơ bắp (ngực, vai, lưng) 3 lần\\/tuần.\",\"Yoga hoặc pilates để tăng độ dẻo dai và giảm căng thẳng.\"]', '[\"Vitamin D3 và Canxi để duy trì sức khỏe xương.\",\"Omega-3 để hỗ trợ sức khỏe tim mạch.\",\"Vitamin C và kẽm để tăng cường miễn dịch.\"]', '2025-05-22 19:43:33'),
(44, 4, 'Vitamin', 22, 'Nam', 151, 50, 'đau đầu, mệt mỏi  sau làm việc , thiếu sức , hụt hơi', 'thể dục, thể thao', '[\"Duy trì chế độ ăn cân bằng với nhiều rau xanh và trái cây.\",\"Ưu tiên protein nạc (ức gà, cá) và ngũ cốc nguyên hạt.\",\"Hạn chế đường và chất béo bão hòa (đồ chiên rán, bánh kẹo).\"]', '[\"Tập cardio cường độ cao (HIIT) 3-4 lần\\/tuần.\",\"Tập tạ để phát triển cơ bắp (ngực, vai, lưng) 3 lần\\/tuần.\",\"Yoga hoặc pilates để tăng độ dẻo dai và giảm căng thẳng.\"]', '[\"Vitamin D3 và Canxi để duy trì sức khỏe xương.\",\"Omega-3 để hỗ trợ sức khỏe tim mạch.\",\"Vitamin C và kẽm để tăng cường miễn dịch.\"]', '2025-05-22 19:44:21'),
(45, 4, 'Omega', 30, 'Nam', 170, 60, 'đau lưng, ', 'chạy bộ', '[\"Duy trì chế độ ăn cân bằng với nhiều rau xanh và trái cây.\",\"Ưu tiên protein nạc (ức gà, cá) và ngũ cốc nguyên hạt.\",\"Hạn chế đường và chất béo bão hòa (đồ chiên rán, bánh kẹo).\"]', '[\"Đi bộ nhanh hoặc chạy bộ nhẹ 30-45 phút mỗi ngày.\",\"Tập tạ nhẹ để duy trì cơ bắp (2-3 lần\\/tuần).\",\"Tập các bài tập kéo giãn để cải thiện tư thế.\",\"Tăng thời gian chạy bộ lên 45-60 phút mỗi buổi, 3-4 lần\\/tuần.\"]', '[\"Bổ sung Omega-3 để hỗ trợ tim mạch và giảm cholesterol.\",\"Vitamin B-complex để tăng cường năng lượng.\",\"Kẽm và Selen để hỗ trợ sức khỏe sinh lý nam giới.\",\"Bổ sung BCAA để tăng sức bền.\"]', '2025-05-23 01:50:30'),
(46, 4, 'Probiotics', 30, 'Nam', 185, 87, 'đầu đầu', '', '[\"Tăng cường rau xanh và thực phẩm giàu chất xơ.\",\"Giảm khẩu phần tinh bột (cơm, bánh mì) và đường.\",\"Ưu tiên protein nạc, hạn chế thực phẩm chiên rán.\"]', '[\"Đi bộ nhanh hoặc chạy bộ nhẹ 30-45 phút mỗi ngày.\",\"Tập tạ nhẹ để duy trì cơ bắp (2-3 lần\\/tuần).\",\"Tập các bài tập kéo giãn để cải thiện tư thế.\"]', '[\"Bổ sung Omega-3 để hỗ trợ tim mạch và giảm cholesterol.\",\"Vitamin B-complex để tăng cường năng lượng.\",\"Kẽm và Selen để hỗ trợ sức khỏe sinh lý nam giới.\"]', '2025-05-23 03:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `loaidichvu`
--

CREATE TABLE `loaidichvu` (
  `MaLoaiDichVu` char(10) NOT NULL,
  `TenLoaiDichVu` varchar(30) DEFAULT NULL,
  `Anh` varchar(50) DEFAULT NULL,
  `MoTaDichVu` varchar(255) DEFAULT NULL,
  `GiaThamKhao` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loaidichvu`
--

INSERT INTO `loaidichvu` (`MaLoaiDichVu`, `TenLoaiDichVu`, `Anh`, `MoTaDichVu`, `GiaThamKhao`) VALUES
('DV001', 'Dịch vụ phòng', './images/service/service-1.jpg', 'Dịch vụ y tế tận tâm, mang đến sự chăm sóc chu đáo và hỗ trợ bệnh nhân vượt qua khó khăn, đảm bảo an toàn và phục hồi sức khỏe hiệu quả', 1000000.00),
('DV002', 'Dịch vụ tim mạch', './images/service/service-2.jpg', 'Dịch vụ bệnh viện cung cấp chăm sóc sức khỏe toàn diện', 200000.00),
('DV003', 'Dịch vụ nha khoa', './images/service/service-3.jpg', 'Dịch vụ bệnh viện cung cấp chăm sóc sức khỏe toàn diện', 30000000.00),
('DV004', 'Dịch vụ phẫu thuật', './images/service/service-4.jpg', 'Dịch vụ bệnh viện cung cấp chăm sóc sức khỏe toàn diện', 400000.00),
('DV005', 'Dịch vụ thần kinh', './images/service/service-6.jpg', 'Dịch vụ bệnh viện cung cấp chăm sóc sức khỏe toàn diện', 150000.00),
('DV006', 'Dịch vụ phụ khoa', 'images/682c57df53432_1747736543.jpg', 'Dịch vụ bệnh viện cung cấp chăm sóc sức khỏe toàn diện', 250000.00),
('DV007', 'Dịch vụ siêu âm', 'images/682c58070795d_1747736583.jpg', 'Dịch vụ siêu âm từ ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loaitaikhoan`
--

CREATE TABLE `loaitaikhoan` (
  `UserID` int(11) NOT NULL,
  `TenTaiKhoan` varchar(30) DEFAULT NULL,
  `Password` varchar(10) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `role` tinyint(4) DEFAULT NULL CHECK (`role` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `loaitaikhoan`
--

INSERT INTO `loaitaikhoan` (`UserID`, `TenTaiKhoan`, `Password`, `Email`, `role`) VALUES
(1, 'Admin', 'admin123', 'admin@gmail.com', 1),
(2, 'User', 'user123', 'user@gmail.com', 0),
(3, 'trọng tấn', 'tantrong12', 'tan123@gmail.com', 0),
(4, 'Trọng Tấn', '05052003', 'nguyentrongtan503@gmail.com', 0),
(17, 'nguyentrongtan', '123456', '20212906@eaut.edu.vn', 0),
(18, 'doctorcare', 'admin123', 'eaut2008@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaNhanVien` char(10) NOT NULL,
  `TenNhanVien` varchar(30) DEFAULT NULL,
  `Email` varchar(30) DEFAULT NULL,
  `UserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`MaNhanVien`, `TenNhanVien`, `Email`, `UserID`) VALUES
('NV001', 'admin', 'admin@gmail.com', 1),
('NV002', 'doctorcare', 'eaut2008@gmail.com', 18);

-- --------------------------------------------------------

--
-- Table structure for table `phanhoi`
--

CREATE TABLE `phanhoi` (
  `MaPhanHoi` char(10) NOT NULL,
  `NoiDungPhanHoi` varchar(255) DEFAULT NULL,
  `MaKhachHang` char(10) DEFAULT NULL,
  `MaNhanVien` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSanPham` varchar(10) NOT NULL,
  `TenSanPham` varchar(100) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `Gia` decimal(10,2) NOT NULL,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `SoLuongTon` int(11) DEFAULT 0,
  `SoLuongBan` int(11) DEFAULT 0,
  `DoiTuong` enum('TreEm','NguoiLon','CaHai') DEFAULT 'CaHai',
  `CachDung` text DEFAULT NULL,
  `ThanhPhan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSanPham`, `TenSanPham`, `MoTa`, `Gia`, `HinhAnh`, `SoLuongTon`, `SoLuongBan`, `DoiTuong`, `CachDung`, `ThanhPhan`) VALUES
('SP001', 'Thuốc Paracetamol 500mg', 'Paracetamol 500mg là một loại thuốc giảm đau và hạ sốt thuộc nhóm thuốc không steroid, được sử dụng rộng rãi trong y tế để làm dịu các cơn đau nhẹ đến trung bình như đau đầu do căng thẳng, đau cơ do vận động quá mức, đau răng sau nhổ, đau bụng kinh, hoặc sốt do cảm cúm, nhiễm virus, hoặc các bệnh lý thông thường khác. Thuốc hoạt động bằng cách ức chế enzym cyclooxygenase trong não, giúp giảm sự tổng hợp prostaglandin – một chất gây viêm và đau. Paracetamol đặc biệt phù hợp cho người lớn và trẻ em trên 12 tuổi, kể cả những người có tiền sử nhạy cảm với aspirin hoặc các thuốc chống viêm không steroid khác vì nó ít gây kích ứng dạ dày hơn khi sử dụng đúng liều. Tuy nhiên, việc lạm dụng thuốc có thể dẫn đến nguy cơ tổn thương gan nghiêm trọng, đặc biệt ở những người nghiện rượu hoặc có bệnh lý gan sẵn có. Ngoài ra, thuốc cũng hỗ trợ cải thiện chất lượng giấc ngủ khi được dùng để giảm sốt vào ban đêm, giúp bệnh nhân nghỉ ngơi tốt hơn trong quá trình hồi phục', 120000.00, './images/child/child1.png', 110, 250, 'TreEm', 'rẻ em 12-16 tuổi: Uống 1 viên mỗi 4-6 giờ, không quá 4 viên (2000mg) trong 24 giờ, chỉ sử dụng dưới sự giám sát của bác sĩ hoặc người lớn.\r\n\r\nThời gian sử dụng: Uống sau bữa ăn để giảm thiểu nguy cơ kích ứng dạ dày, kèm theo một ly nước đầy (khoảng 200ml) để thuốc tan đều và hấp thụ tốt hơn. Không dùng liên tục quá 5 ngày cho đau hoặc 3 ngày cho sốt mà không có chỉ định từ bác sĩ.\r\n\r\nCảnh báo: Tránh dùng đồng thời với rượu bia hoặc các thuốc chứa paracetamol khác để tránh quá liều. Nếu có dấu hiệu vàng da, buồn nôn kéo dài, hoặc đau bụng dữ dội, ngừng dùng và đi khám ngay.\r\n\r\nBảo quản: Lưu trữ ở nơi khô ráo, tránh ánh nắng trực tiếp, nhiệt độ lý tưởng từ 15°C đến 30°C. Giữ xa tầm tay trẻ em và không để thuốc trong môi trường ẩm ướt như nhà tắm.', 'Hoạt chất chính: Paracetamol 500mg (tương đương 0.5g), được chiết xuất từ nguyên liệu đạt tiêu chuẩn USP/BP.\r\n\r\nTá dược: Tinh bột mì (giúp định hình viên), cellulose vi tinh thể (điều chỉnh độ cứng), povidone (đảm bảo tan đều), natri starch glycolate (tăng khả năng phân hủy trong dạ dày), magnesi stearate (chất bôi trơn để dễ dàng sản xuất).'),
('SP002', 'Thực phẩm chức năng Vitamin C 1000mg', 'Vitamin C 1000mg là một thực phẩm chức năng cao cấp được thiết kế để tăng cường hệ miễn dịch, chống lại các tác nhân gây bệnh như virus cảm cúm hoặc nhiễm khuẩn thông thường, đặc biệt hữu ích trong mùa đông hoặc khi cơ thể bị căng thẳng do làm việc quá sức. Sản phẩm cung cấp liều lượng cao acid ascorbic (Vitamin C), giúp kích thích sản xuất bạch cầu, hỗ trợ cơ thể hấp thụ sắt từ thực phẩm, từ đó cải thiện tình trạng thiếu máu do thiếu sắt. Ngoài ra, Vitamin C còn là một chất chống oxy hóa mạnh mẽ, giúp làm chậm quá trình lão hóa da, giảm thâm nám, và bảo vệ tế bào khỏi tác hại của gốc tự do do ô nhiễm môi trường hoặc tia UV. Sản phẩm phù hợp cho người lớn có nguy cơ thiếu hụt vitamin C (do chế độ ăn nghèo chất dinh dưỡng), người thường xuyên mệt mỏi, phụ nữ mang thai (với liều lượng được bác sĩ tư vấn), hoặc người dễ bị cảm cúm. Tuy nhiên, người có tiền sử sỏi thận hoặc dị ứng với các thành phần cần thận trọng và tham khảo ý kiến chuyên gia trước khi sử dụng.', 50000.00, './images/child/child2.png', 146, 300, 'TreEm', 'Người lớn: Uống 1 viên mỗi ngày, tốt nhất vào buổi sáng sau bữa ăn để tối ưu hóa hấp thụ và tránh kích ứng dạ dày. Có thể chia làm 2 lần (nửa viên sáng, nửa viên chiều) nếu cảm thấy khó chịu với liều cao.\r\n\r\nTrẻ em trên 14 tuổi: Uống 1/2 viên/ngày, dưới sự giám sát của phụ huynh hoặc bác sĩ.\r\n\r\nCảnh báo: Không vượt quá 2000mg Vitamin C/ngày để tránh tác dụng phụ như tiêu chảy, buồn nôn, hoặc nguy cơ hình thành sỏi thận. Uống nhiều nước để hỗ trợ thải trừ dư thừa.\r\n\r\nBảo quản: Bảo quản ở nơi khô ráo, thoáng mát, nhiệt độ từ 15°C đến 25°C, tránh ánh nắng trực tiếp hoặc môi trường ẩm ướt như tủ lạnh mở. Giữ xa tầm tay trẻ em dưới 6 tuổi.', 'Hoạt chất chính: Acid ascorbic (Vitamin C) 1000mg, đạt tiêu chuẩn dược phẩm quốc tế.\r\n\r\nThành phần khác: Chất tạo ngọt tự nhiên sorbitol (giảm vị chua), hương cam tự nhiên (tăng mùi vị dễ chịu), magnesi stearate (chất bôi trơn để sản xuất), và một lượng nhỏ silicon dioxide (chất chống vón cục).'),
('SP003', 'Thuốc Omeprazole 20mg', 'Omeprazole 20mg là một loại thuốc thuộc nhóm ức chế bơm proton (PPI), được sử dụng để điều trị các vấn đề liên quan đến dạ dày như trào ngược dạ dày thực quản (GERD), loét dạ dày hoặc tá tràng do vi khuẩn Helicobacter pylori, hoặc hội chứng Zollinger-Ellison (tăng tiết acid dạ dày bất thường). Thuốc hoạt động bằng cách ức chế trực tiếp các tế bào tiết acid trong niêm mạc dạ dày, giúp giảm đau rát, ợ nóng, và hỗ trợ quá trình lành vết loét. Sản phẩm phù hợp cho người lớn và trẻ em trên 16 tuổi bị bệnh lý dạ dày mãn tính, đặc biệt những người phải dùng thuốc chống viêm lâu dài (như ibuprofen) làm tăng nguy cơ loét. Omeprazole cũng được dùng trong phác đồ điều trị kết hợp với kháng sinh để loại bỏ H. pylori, từ đó ngăn ngừa tái phát loét. Tuy nhiên, sử dụng lâu dài có thể gây giảm hấp thụ vitamin B12 hoặc tăng nguy cơ gãy xương, vì vậy cần theo dõi định kỳ bởi bác sĩ.', 350000.00, './images/child/child3.png', 79, 120, 'TreEm', 'Người lớn: Uống 1 viên (20mg) mỗi ngày, 30 phút trước bữa ăn sáng để đạt hiệu quả tối ưu. Thời gian điều trị thường từ 4-8 tuần tùy mức độ bệnh, hoặc theo chỉ định của bác sĩ.\r\n\r\nTrẻ em trên 16 tuổi: Uống 1 viên/ngày, chỉ dùng khi có chỉ định y tế.\r\n\r\nHướng dẫn: Nuốt nguyên viên với nước, không nhai hoặc nghiền nát để tránh giảm hiệu quả. Tránh dùng với thức ăn giàu chất béo vì có thể làm chậm tác dụng.\r\n\r\nBảo quản: Lưu trữ ở nơi khô ráo, nhiệt độ từ 15°C đến 30°C, tránh ánh nắng trực tiếp. Đậy kín lọ thuốc để tránh ẩm, giữ xa tầm tay trẻ em.', 'Hoạt chất chính: Omeprazole 20mg, dạng vi hạt bao tan trong ruột để bảo vệ dạ dày.\r\n\r\nTá dược: Lactose monohydrate (điều chỉnh độ tan), cellulose vi tinh thể (tăng độ bền viên), natri lauryl sulfate (tăng khả năng hấp thụ), magnesi stearate (chất bôi trơn), và gelatin (vỏ viên bảo vệ).'),
('SP004', 'Thực phẩm chức năng Men vi sinh BioGaia Protectis', 'BioGaia Protectis là thực phẩm chức năng chứa lợi khuẩn Lactobacillus reuteri DSM 17938, được phát triển để cải thiện sức khỏe đường ruột và tăng cường hệ miễn dịch. Sản phẩm đặc biệt hiệu quả trong việc cân bằng hệ vi sinh đường ruột sau khi sử dụng kháng sinh, hỗ trợ điều trị tiêu chảy cấp do nhiễm khuẩn (như rotavirus), hoặc giảm tình trạng đầy hơi, táo bón, và khó tiêu ở người lớn và trẻ em. Với nguồn gốc từ vi khuẩn tự nhiên, BioGaia Protectis giúp kích thích sản sinh các enzyme tiêu hóa, tăng cường hàng rào bảo vệ ruột, và giảm viêm ruột do chế độ ăn không lành mạnh hoặc căng thẳng kéo dài. Sản phẩm phù hợp cho trẻ em trên 3 tuổi, phụ nữ mang thai, người lớn bị rối loạn tiêu hóa mãn tính, hoặc người muốn duy trì sức khỏe đường ruột lâu dài. Tuy nhiên, người bị dị ứng với sữa hoặc lactose cần thận trọng vì sản phẩm có thể chứa dấu vết sữa.', 180000.00, './images/child/child4.png', 87, 200, 'TreEm', 'Người lớn: Uống 1-2 viên/ngày, sau bữa ăn để tối ưu hóa sự sống của lợi khuẩn trong dạ dày.\r\n\r\nTrẻ em (trên 3 tuổi): Uống 1 viên/ngày, có thể nhai hoặc hòa tan trong nước ấm (dưới 40°C để bảo vệ vi khuẩn).\r\n\r\nCảnh báo: Tránh dùng nước nóng hoặc thức ăn có nhiệt độ cao khi pha vì sẽ tiêu diệt lợi khuẩn. Không dùng quá 4 tuần liên tục mà không tham khảo ý kiến bác sĩ nếu triệu chứng không cải thiện.\r\n\r\nBảo quản: Bảo quản ở nơi khô ráo, nhiệt độ từ 2°C đến 25°C, tránh ánh nắng trực tiếp. Đậy kín lọ sau mỗi lần sử dụng để bảo vệ lợi khuẩn.', 'Hoạt chất chính: Lactobacillus reuteri DSM 17938 (ít nhất 100 triệu CFU/viên), một chủng vi khuẩn được nghiên cứu lâm sàng.\r\n\r\nThành phần khác: Isomalt (chất độn tự nhiên), hương vani tự nhiên (tăng mùi vị), acid citric (điều chỉnh độ pH), magnesi stearate (chất bôi trơn).'),
('SP005', 'Thuốc Berberine 100mg', 'Berberine 100mg là một loại thuốc có nguồn gốc từ rễ cây hoàng liên, được sử dụng chủ yếu để điều trị tiêu chảy cấp do nhiễm khuẩn đường ruột (như E. coli, Salmonella), rối loạn tiêu hóa do ăn uống không vệ sinh, hoặc viêm ruột nhẹ. Hoạt chất Berberine có tác dụng kháng khuẩn, chống viêm mạnh mẽ, giúp làm dịu niêm mạc ruột bị tổn thương và giảm co thắt dạ dày. Ngoài ra, Berberine còn được nghiên cứu hỗ trợ kiểm soát đường huyết ở người tiền tiểu đường hoặc béo phì nhờ khả năng cải thiện độ nhạy insulin, làm cho sản phẩm trở thành lựa chọn bổ sung cho người có nguy cơ bệnh lý chuyển hóa. Thuốc phù hợp cho người lớn và trẻ em trên 6 tuổi, nhưng không khuyến khích dùng lâu dài vì có thể gây tác dụng phụ như tiêu chảy nặng hoặc giảm hấp thụ vitamin B. Người bị bệnh gan hoặc phụ nữ mang thai cần tham khảo ý kiến bác sĩ trước khi sử dụng.', 30000.00, './images/child/child5.png', 189, 400, 'TreEm', 'Người lớn: Uống 2-3 viên/lần, 2 lần/ngày, sau bữa ăn để giảm kích ứng dạ dày và tăng hiệu quả kháng khuẩn.\r\n\r\nTrẻ em (6-12 tuổi): Uống 1 viên/lần, 2 lần/ngày, dưới sự giám sát của phụ huynh.\r\n\r\nCảnh báo: Không dùng quá 7 ngày liên tục mà không có chỉ định y tế. Tránh dùng cùng lúc với thuốc trị tiểu đường vì có thể gây hạ đường huyết. Uống nhiều nước để hỗ trợ thải trừ.\r\n\r\nBảo quản: Lưu trữ ở nơi khô ráo, nhiệt độ từ 15°C đến 30°C, tránh ánh nắng trực tiếp. Đậy kín lọ để tránh ẩm.', 'Hoạt chất chính: Berberine chloride 100mg, chiết xuất tự nhiên từ hoàng liên.\r\n\r\nTá dược: Tinh bột mì (định hình viên), lactose (điều chỉnh độ tan), povidone (tăng độ kết dính), magnesi stearate (chất bôi trơn).'),
('SP006', 'Thuốc Calci-D 600mg', 'Calci-D 600mg là một sản phẩm bổ sung canxi kết hợp với vitamin D3, được thiết kế để hỗ trợ sức khỏe xương và răng, đặc biệt hữu ích cho người lớn tuổi, phụ nữ sau mãn kinh, hoặc trẻ em trong giai đoạn phát triển chiều cao. Canxi trong sản phẩm giúp tăng cường mật độ xương, giảm nguy cơ loãng xương và gãy xương do thiếu hụt khoáng chất, trong khi vitamin D3 hỗ trợ hấp thu canxi tối ưu từ ruột và duy trì mức canxi ổn định trong máu. Sản phẩm cũng phù hợp cho người có chế độ ăn thiếu hụt canxi (như người ăn chay), hoặc những người ít tiếp xúc với ánh nắng mặt trời – nguồn tự nhiên của vitamin D. Ngoài ra, Calci-D còn hỗ trợ chức năng cơ bắp và giảm chuột rút ở phụ nữ mang thai hoặc người làm việc nặng. Tuy nhiên, lạm dụng lâu dài có thể dẫn đến sỏi thận hoặc táo bón, vì vậy cần sử dụng theo chỉ định và theo dõi định kỳ.', 80000.00, './images/adult/adult1.png', 117, 350, 'NguoiLon', 'Người lớn: Uống 1-2 viên/ngày, tốt nhất sau bữa ăn sáng hoặc tối để tăng hấp thu, không vượt quá 2000mg canxi/ngày từ tất cả các nguồn.\r\n\r\nTrẻ em (trên 6 tuổi): Uống 1 viên/ngày, dưới sự giám sát của phụ huynh hoặc bác sĩ.\r\n\r\nCảnh báo: Tránh dùng cùng lúc với thuốc sắt hoặc tetracycline vì có thể giảm hiệu quả hấp thu. Nếu có dấu hiệu táo bón kéo dài, giảm liều hoặc tham khảo ý kiến bác sĩ.\r\n\r\nBảo quản: Lưu trữ ở nơi khô ráo, nhiệt độ từ 15°C đến 30°C, tránh ánh nắng trực tiếp. Đậy kín lọ để tránh ẩm và giữ xa tầm tay trẻ em.', 'Hoạt chất chính: Calci carbonate 600mg (cung cấp 240mg canxi nguyên tố), Cholecalciferol (Vitamin D3) 200IU.\r\n\r\nTá dược: Tinh bột ngô (định hình viên), stearic acid (chất bôi trơn), croscarmellose sodium (tăng khả năng tan), và silicon dioxide (chống vón cục).'),
('SP007', 'Thuốc Melatonin 3mg', 'Melatonin 3mg là một loại thực phẩm chức năng hỗ trợ giấc ngủ, được chiết xuất từ các hợp chất tự nhiên giúp điều hòa nhịp sinh học cơ thể. Thuốc đặc biệt hiệu quả cho người bị mất ngủ mãn tính, rối loạn giấc ngủ do chênh lệch múi giờ (jet lag), hoặc người lớn tuổi có mức melatonin nội sinh giảm. Melatonin hoạt động bằng cách kích thích cơ thể sản sinh hormone ngủ, giúp người dùng dễ dàng chìm vào giấc ngủ sâu, cải thiện chất lượng nghỉ ngơi, và giảm tình trạng thức giấc giữa đêm. Sản phẩm phù hợp cho người lớn và trẻ em trên 12 tuổi (với liều thấp hơn), nhưng không nên dùng lâu dài vì có thể làm giảm khả năng tự điều hòa giấc ngủ tự nhiên. Người bị bệnh tự miễn, trầm cảm nặng, hoặc phụ nữ mang thai cần tham khảo ý kiến bác sĩ trước khi sử dụng để tránh tác dụng phụ không mong muốn.', 150000.00, './images/adult/adult2.png', 66, 180, 'NguoiLon', 'Người lớn: Uống 1-2 viên (3mg/viên) khoảng 30-60 phút trước khi đi ngủ, không vượt quá 6mg/ngày.\r\n\r\nTrẻ em (trên 12 tuổi): Uống 1/2-1 viên, dưới sự giám sát y tế.\r\n\r\nCảnh báo: Tránh dùng ban ngày hoặc kết hợp với rượu bia vì có thể gây buồn ngủ quá mức. Ngừng dùng sau 1-2 tháng nếu không cải thiện, và tham khảo bác sĩ nếu có triệu chứng buồn ngủ kéo dài vào ban ngày.\r\n\r\nBảo quản: Bảo quản ở nơi khô ráo, nhiệt độ từ 15°C đến 25°C, tránh ánh nắng trực tiếp. Đậy kín lọ để bảo vệ chất lượng.', 'Hoạt chất chính: Melatonin 3mg, tổng hợp từ nguyên liệu đạt tiêu chuẩn dược phẩm.\r\n\r\nTá dược: Lactose (điều chỉnh độ tan), cellulose vi tinh thể (tăng độ bền), magnesi stearate (chất bôi trơn), và hương bạc hà tự nhiên (tăng mùi vị).'),
('SP008', 'Thực phẩm chức năng Glucosamine 1500mg', 'Glucosamine 1500mg là thực phẩm chức năng hỗ trợ sức khỏe xương khớp, được thiết kế đặc biệt cho người lớn tuổi, người bị thoái hóa khớp, viêm khớp dạng thấp, hoặc những người thường xuyên vận động nặng như vận động viên. Sản phẩm cung cấp Glucosamine sulfate – một thành phần tự nhiên trong sụn khớp, giúp tái tạo mô sụn, giảm ma sát giữa các khớp, và giảm đau do viêm. Ngoài ra, Glucosamine còn hỗ trợ cải thiện độ linh hoạt của khớp, đặc biệt ở người bị cứng khớp buổi sáng, và có thể kết hợp với chondroitin để tăng hiệu quả (tùy phiên bản sản phẩm). Sản phẩm phù hợp cho người từ 40 tuổi trở lên hoặc người bị chấn thương khớp cũ, nhưng không khuyến khích dùng cho người bị dị ứng hải sản (vì Glucosamine thường chiết xuất từ vỏ tôm cua). Người bị đái tháo đường cần theo dõi đường huyết vì Glucosamine có thể ảnh hưởng nhẹ đến mức đường.', 90000.00, './images/adult/adult3.png', 59, 150, 'NguoiLon', 'Người lớn: Uống 1-2 viên/ngày, tốt nhất sau bữa ăn để giảm kích ứng dạ dày, không vượt quá 3000mg/ngày.\r\n\r\nCảnh báo: Uống nhiều nước để hỗ trợ thải trừ và tránh khô miệng. Không dùng quá 3 tháng liên tục mà không tham khảo ý kiến bác sĩ.\r\n\r\nBảo quản: Lưu trữ ở nơi khô ráo, nhiệt độ từ 15°C đến 30°C, tránh ánh nắng trực tiếp. Đậy kín lọ để tránh ẩm.', 'Hoạt chất chính: Glucosamine sulfate 1500mg (từ nguồn hải sản tự nhiên).\r\n\r\nThành phần khác: Chondroitin sulfate 200mg (tùy phiên bản), cellulose vi tinh thể (định hình viên), magnesi stearate (chất bôi trơn), và silicon dioxide (chống vón cục).'),
('SP009', 'Thuốc Ibuprofen 400mg', 'Ibuprofen 400mg là thuốc chống viêm không steroid (NSAID) có tác dụng giảm đau, hạ sốt, và chống viêm hiệu quả, được sử dụng để điều trị các cơn đau vừa đến nặng như đau cơ, đau khớp, đau đầu migraine, đau bụng kinh dữ dội, hoặc sốt cao do nhiễm khuẩn. Thuốc hoạt động bằng cách ức chế enzym COX-1 và COX-2, giảm sản xuất prostaglandin – nguyên nhân gây viêm và đau. Ibuprofen đặc biệt hữu ích cho người bị viêm khớp dạng thấp hoặc thoái hóa khớp trong giai đoạn cấp, nhưng không nên dùng lâu dài vì có thể gây tổn thương dạ dày, loét, hoặc xuất huyết tiêu hóa. Sản phẩm phù hợp cho người lớn và trẻ em trên 12 tuổi (với liều thấp hơn), nhưng người có tiền sử hen suyễn, loét dạ dày, hoặc suy gan thận cần thận trọng và tham khảo ý kiến bác sĩ trước khi sử dụng.', 110000.00, './images/adult/adult4.png', 97, 220, 'NguoiLon', 'Người lớn: Uống 1-2 viên mỗi 6-8 giờ khi cần, không vượt quá 3200mg (8 viên) trong 24 giờ.\r\n\r\nTrẻ em (trên 12 tuổi): Uống 1 viên mỗi 6-8 giờ, không quá 4 viên/ngày, dưới sự giám sát y tế.\r\n\r\nCảnh báo: Uống sau ăn để giảm kích ứng dạ dày, tránh dùng với rượu bia. Ngừng dùng nếu có dấu hiệu đau dạ dày, nôn ra máu, hoặc khó thở, và đi khám ngay.\r\n\r\nBảo quản: Bảo quản ở nơi khô ráo, nhiệt độ từ 15°C đến 30°C, tránh ánh nắng trực tiếp. Đậy kín lọ và giữ xa trẻ em.', 'Hoạt chất chính: Ibuprofen 400mg, đạt tiêu chuẩn dược phẩm USP.\r\n\r\nTá dược: Tinh bột ngô (định hình viên), povidone (tăng độ tan), magnesi stearate (chất bôi trơn), và natri lauryl sulfate (tăng hấp thu).'),
('SP010', 'Thực phẩm chức năng Omega-3 1000mg', 'Omega-3 1000mg là thực phẩm chức năng chứa dầu cá giàu acid béo không bão hòa (EPA và DHA), được thiết kế để hỗ trợ sức khỏe tim mạch, não bộ, và mắt. Sản phẩm giúp giảm triglyceride trong máu, ngăn ngừa xơ vữa động mạch, và cải thiện trí nhớ ở người lớn tuổi hoặc người làm việc trí óc căng thẳng. Omega-3 cũng có tác dụng chống viêm tự nhiên, hỗ trợ giảm triệu chứng viêm khớp và khô mắt do thiếu hụt chất béo lành mạnh. Sản phẩm phù hợp cho người trưởng thành từ 18 tuổi trở lên, đặc biệt là người có nguy cơ tim mạch cao, phụ nữ mang thai (để phát triển não bộ thai nhi), hoặc người ăn chay không bổ sung đủ nguồn omega-3 từ cá. Tuy nhiên, người bị dị ứng hải sản hoặc đang dùng thuốc chống đông máu cần tham khảo ý kiến bác sĩ để tránh nguy cơ chảy máu.', 200000.00, './images/adult/adult4.png', 50, 90, 'NguoiLon', 'Người lớn: Uống 1-2 viên/ngày, cùng bữa ăn để tăng hấp thu và giảm mùi tanh, không vượt quá 3000mg/ngày.\r\n\r\nCảnh báo: Tránh dùng trước khi phẫu thuật vì có thể ảnh hưởng đến đông máu. Uống nhiều nước để giảm nguy cơ ợ chua.\r\n\r\nBảo quản: Bảo quản ở nơi khô ráo, nhiệt độ từ 15°C đến 25°C, tránh ánh nắng trực tiếp. Đậy kín lọ và giữ trong tủ mát để bảo vệ chất lượng dầu.', 'Hoạt chất chính: Omega-3 1000mg (EPA 400mg, DHA 300mg), chiết xuất từ dầu cá sâu biển.\r\n\r\nThành phần khác: Gelatin (vỏ viên), glycerol (chất làm mềm), và vitamin E tự nhiên (chất chống oxy hóa).'),
('SP011', 'Thuốc Amoxicillin 500mge', 'Amoxicillin 500mg là một loại kháng sinh thuộc nhóm penicillin, được sử dụng để điều trị các nhiễm khuẩn do vi khuẩn nhạy cảm như viêm họng do liên cầu khuẩn, viêm tai giữa, viêm xoang, nhiễm khuẩn đường tiết niệu, hoặc nhiễm khuẩn da nhẹ. Thuốc hoạt động bằng cách ức chế tổng hợp thành tế bào vi khuẩn, dẫn đến sự tiêu diệt chúng, đặc biệt hiệu quả với các bệnh lý do Streptococcus, Staphylococcus, hoặc E. coli. Sản phẩm phù hợp cho người lớn và trẻ em trên 10 tuổi (với liều lượng điều chỉnh), nhưng không dùng cho người dị ứng penicillin vì có nguy cơ phản ứng dị ứng nghiêm trọng như sốc phản vệ. Amoxicillin cũng thường được kết hợp với acid clavulanic (như Augmentin) để tăng hiệu quả chống vi khuẩn kháng thuốc, nhưng phiên bản này chỉ chứa Amoxicillin đơn thuần. Sử dụng không đúng liều hoặc không đủ thời gian có thể dẫn đến kháng kháng sinh, vì vậy cần tuân thủ chỉ định của bác sĩ.', 300000.00, './images/adult/adult5.png', 40, 80, 'NguoiLon', 'Người lớn: Uống 1 viên (500mg) mỗi 8 giờ hoặc 2 viên mỗi 12 giờ, trong 7-10 ngày tùy theo chỉ định.\r\n\r\nTrẻ em (trên 10 tuổi): Uống 1/2-1 viên mỗi 8 giờ, dưới sự giám sát y tế.\r\n\r\nCảnh báo: Uống sau ăn để giảm kích ứng dạ dày. Ngừng dùng nếu có phát ban, khó thở, hoặc sưng mặt, và đi cấp cứu ngay.\r\n\r\nBảo quản: Lưu trữ ở nơi khô ráo, nhiệt độ từ 15°C đến 25°C, tránh ánh nắng trực tiếp. Đậy kín lọ và giữ xa trẻ em.', 'Hoạt chất chính: Amoxicillin trihydrate 500mg, đạt tiêu chuẩn BP.\r\nTá dược: Tinh bột mì (định hình viên), magnesi stearate (chất bôi trơn), và sodium starch glycolate (tăng khả năng tan).');

-- --------------------------------------------------------

--
-- Table structure for table `trangthai`
--

CREATE TABLE `trangthai` (
  `MaTrangThai` char(10) NOT NULL,
  `TenTrangThai` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `trangthai`
--

INSERT INTO `trangthai` (`MaTrangThai`, `TenTrangThai`) VALUES
('TT001', 'Chờ xử lý'),
('TT002', 'Đang xử lý'),
('TT003', 'Đã hoàn thành'),
('TT004', 'Đã hủy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietdatlich`
--
ALTER TABLE `chitietdatlich`
  ADD PRIMARY KEY (`MaChiTiet`);

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`MaChiTietDonHang`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentID`);

--
-- Indexes for table `datlich`
--
ALTER TABLE `datlich`
  ADD PRIMARY KEY (`MaDatLich`);

--
-- Indexes for table `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`MaGioHang`);

--
-- Indexes for table `hinhthucthanhtoan`
--
ALTER TABLE `hinhthucthanhtoan`
  ADD PRIMARY KEY (`MaThanhToan`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKhachHang`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `lichsudonhang`
--
ALTER TABLE `lichsudonhang`
  ADD PRIMARY KEY (`MaDonHang`);

--
-- Indexes for table `lichsugoiy`
--
ALTER TABLE `lichsugoiy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loaidichvu`
--
ALTER TABLE `loaidichvu`
  ADD PRIMARY KEY (`MaLoaiDichVu`);

--
-- Indexes for table `loaitaikhoan`
--
ALTER TABLE `loaitaikhoan`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaNhanVien`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `phanhoi`
--
ALTER TABLE `phanhoi`
  ADD PRIMARY KEY (`MaPhanHoi`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSanPham`);

--
-- Indexes for table `trangthai`
--
ALTER TABLE `trangthai`
  ADD PRIMARY KEY (`MaTrangThai`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitietdatlich`
--
ALTER TABLE `chitietdatlich`
  MODIFY `MaChiTiet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `datlich`
--
ALTER TABLE `datlich`
  MODIFY `MaDatLich` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `giohang`
--
ALTER TABLE `giohang`
  MODIFY `MaGioHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `lichsugoiy`
--
ALTER TABLE `lichsugoiy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD CONSTRAINT `khachhang_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `loaitaikhoan` (`UserID`);

--
-- Constraints for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `loaitaikhoan` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
