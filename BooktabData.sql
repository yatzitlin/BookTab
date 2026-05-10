-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 06, 2026 lúc 06:49 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `booktab`
--

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT IGNORE INTO `nguoi_dung` (`userid`, `username`, `mat_khau`, `ho_va_ten_dem`, `ten`, `so_dien_thoai`, `trang_thai`, `ngay_tao`) VALUES
(1, 'admin', '$2a$12$UHRC2flycgjyh/bpatCJDO14bC/ap99VXEFV/WAXmXuCRbFlIDHyG', 'Quản Trị', 'Viên', '0123456789', 'active', '2026-05-05 20:23:28'),
(2, 'user1', '$2y$10$J1nTsf6mrr0hEbfODkCFHOdZfkdMRy.UOjJM4pHYPXz1Ocutm0dyi', 'Bành Phú', 'Hội', '1234567899', 'active', '2026-05-05 20:24:39');

--
-- Đang đổ dữ liệu cho bảng `administrator`
--

INSERT IGNORE INTO `administrator` (`userid`) VALUES
(1);

--
-- Đang đổ dữ liệu cho bảng `member`
--

INSERT IGNORE INTO `member` (`userid`, `diem_tich_luy`, `ten_rank`) VALUES
(2, 0, NULL);

-- TEST DATA

--
-- Đang đổ dữ liệu cho bảng `loai_san_pham`
--

INSERT IGNORE INTO `loai_san_pham` (`ma_loai`, `ten_loai`, `loai_cha`) VALUES
(1, 'Văn học', NULL),
(2, 'Kinh tế - Quản lý', NULL),
(3, 'Kỹ năng sống', NULL),
(4, 'Khoa học - Công nghệ', NULL),
(5, 'Thiếu nhi', NULL);

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT IGNORE INTO `san_pham` (`ma_san_pham`, `ten_san_pham`, `mo_ta`, `gia_san_pham`, `ma_loai`, `is_active`) VALUES
(1, 'Đắc Nhân Tâm', 'Cuốn sách kinh điển của Dale Carnegie về nghệ thuật giao tiếp và tạo dựng mối quan hệ. Được xuất bản lần đầu năm 1936, đây là một trong những cuốn sách bán chạy nhất mọi thời đại.', 68000, 3, 1),
(2, 'Nhà Giả Kim', 'Tiểu thuyết nổi tiếng của Paulo Coelho kể về hành trình của một cậu bé chăn cừu người Tây Ban Nha tên Santiago đi theo giấc mơ của mình.', 79000, 1, 1),
(3, 'Sapiens: Lược Sử Loài Người', 'Tác phẩm của Yuval Noah Harari khám phá lịch sử loài người từ thời tiền sử đến thế kỷ 21. Cuốn sách trả lời câu hỏi: Điều gì đã làm cho Homo sapiens trở thành loài thống trị Trái Đất?', 139000, 4, 1),
(4, 'Tư Duy Nhanh Và Chậm', 'Daniel Kahneman, người đoạt giải Nobel Kinh tế, giải thích hai hệ thống tư duy: Hệ thống 1 nhanh, bản năng và cảm xúc; Hệ thống 2 chậm hơn, có chủ đích và logic hơn.', 115000, 2, 1),
(5, 'Atomic Habits - Thói Quen Nguyên Tử', 'James Clear chia sẻ phương pháp xây dựng thói quen tốt và loại bỏ thói quen xấu. Cuốn sách cung cấp một framework thực tế dựa trên khoa học thần kinh và tâm lý học.', 89000, 3, 1),
(6, 'Người Giàu Có Nhất Thành Babylon', 'George S. Clason chia sẻ những nguyên tắc tài chính cổ đại thông qua những câu chuyện ngụ ngôn về cuộc sống ở Babylon cổ đại.', 59000, 2, 1),
(7, 'Cây Cam Ngọt Của Tôi', 'Tiểu thuyết của José Mauro de Vasconcelos kể về cậu bé Zezé 5 tuổi với một tuổi thơ đau khổ nhưng đầy ắp trí tưởng tượng. Tác phẩm đã được dịch ra hơn 50 thứ tiếng.', 75000, 1, 1),
(8, 'Dám Bị Ghét', 'Tác phẩm của Ichiro Kishimi và Fumitake Koga trình bày triết học của Alfred Adler qua cuộc đối thoại giữa một chàng trai trẻ và một triết gia.', 99000, 3, 1),
(9, 'Zero to One', 'Peter Thiel chia sẻ về cách xây dựng các công ty startup tạo ra điều gì đó thực sự mới mẻ, không chỉ cạnh tranh trong thị trường đã có sẵn.', 109000, 2, 1),
(10, 'Hoàng Tử Bé', 'Tác phẩm kinh điển của Antoine de Saint-Exupéry. Câu chuyện về một hoàng tử nhỏ từ hành tinh xa xôi đến thăm Trái Đất và những cuộc gặp gỡ đầy ý nghĩa.', 45000, 1, 1),
(11, 'Thinking in Systems', 'Donella H. Meadows giới thiệu tư duy hệ thống - một cách tiếp cận mạnh mẽ để hiểu thế giới phức tạp. Cuốn sách giúp bạn nhận ra và phân tích các hệ thống xung quanh.', 125000, 4, 1),
(12, 'Doraemon - Tập 1', 'Manga nổi tiếng của Fujiko F. Fujio về chú mèo máy Doraemon từ tương lai đến giúp đỡ cậu bé Nobita. Phù hợp cho mọi lứa tuổi, đặc biệt là thiếu nhi.', 25000, 5, 1),
(13, 'Bố Già', 'Tiểu thuyết kinh điển của Mario Puzo về gia đình Corleone, một trong những gia đình mafia quyền lực nhất nước Mỹ. Tác phẩm đã được chuyển thể thành bộ phim bất hủ.', 135000, 1, 1),
(14, 'Rèn Luyện Tư Duy Phản Biện', 'Cuốn sách giúp bạn nhận diện và tránh các lỗi suy nghĩ thường gặp, từ đó đưa ra quyết định sáng suốt hơn trong cuộc sống và công việc.', 85000, 3, 1),
(15, 'Sản Phẩm Ngừng Kinh Doanh', 'Đây là sản phẩm dùng để kiểm tra tính năng ngừng kinh doanh', 50000, 2, 0);

--
-- Đang đổ dữ liệu cho bảng `anh_san_pham`
--

INSERT IGNORE INTO `anh_san_pham` (`ma_anh`, `ma_san_pham`, `url_anh`, `alt_text`, `is_primary`, `so_thu_tu`) VALUES
(1,  1, 'https://salt.tikicdn.com/cache/w1200/ts/product/5e/18/24/2a6154ba08df6ce6161c13f4303fa19e.jpg', 'Đắc Nhân Tâm', 1, 1),
(2,  2, 'https://salt.tikicdn.com/cache/w1200/ts/product/45/5b/fc/ae06f4392c15f912f658a975ba0e7daa.jpg', 'Nhà Giả Kim', 1, 1),
(3,  3, 'https://salt.tikicdn.com/cache/w1200/ts/product/0b/f1/6a/1c808b8b26369b9e4a4f2d7e13e1fac7.jpg', 'Sapiens', 1, 1),
(4,  4, 'https://salt.tikicdn.com/cache/w1200/ts/product/df/7d/b4/6b8e7a9eb61f5e3b3d6c4e8f5a2c9d1e.jpg', 'Tư Duy Nhanh Và Chậm', 1, 1),
(5,  5, 'https://salt.tikicdn.com/cache/w1200/ts/product/4b/c9/49/1e853f3d2e4f5a6b7c8d9e0f1a2b3c4d.jpg', 'Atomic Habits', 1, 1),
(6,  6, 'https://salt.tikicdn.com/cache/w1200/ts/product/8c/d7/6f/2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e.jpg', 'Người Giàu Có Nhất Thành Babylon', 1, 1),
(7,  7, 'https://salt.tikicdn.com/cache/w1200/ts/product/3a/2b/1c/0d9e8f7a6b5c4d3e2f1a0b9c8d7e6f5a.jpg', 'Cây Cam Ngọt Của Tôi', 1, 1),
(8,  8, 'https://salt.tikicdn.com/cache/w1200/ts/product/7f/6e/5d/4c3b2a1b0c9d8e7f6a5b4c3d2e1f0a9b.jpg', 'Dám Bị Ghét', 1, 1),
(9,  9, 'https://salt.tikicdn.com/cache/w1200/ts/product/6e/5d/4c/3b2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c.jpg', 'Zero to One', 1, 1),
(10, 10, 'https://salt.tikicdn.com/cache/w1200/ts/product/5d/4c/3b/2a1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d.jpg', 'Hoàng Tử Bé', 1, 1),
(11, 11, 'https://salt.tikicdn.com/cache/w1200/ts/product/4c/3b/2a/1b0c9d8e7f6a5b4c3d2e1f0a9b8c7d6e.jpg', 'Thinking in Systems', 1, 1),
(12, 12, 'https://salt.tikicdn.com/cache/w1200/ts/product/3b/2a/1b/0c9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f.jpg', 'Doraemon', 1, 1),
(13, 13, 'https://salt.tikicdn.com/cache/w1200/ts/product/2a/1b/0c/9d8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a.jpg', 'Bố Già', 1, 1),
(14, 14, 'https://salt.tikicdn.com/cache/w1200/ts/product/1b/0c/9d/8e7f6a5b4c3d2e1f0a9b8c7d6e5f4a3b.jpg', 'Rèn Luyện Tư Duy Phản Biện', 1, 1),
(15, 15, 'https://salt.tikicdn.com/cache/w1200/ts/product/0c/9d/8e/7f6a5b4c3d2e1f0a9b8c7d6e5f4a3b2c.jpg', 'Sản Phẩm Test', 1, 1);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
