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

INSERT INTO `nguoi_dung` (`userid`, `username`, `mat_khau`, `ho_va_ten_dem`, `ten`, `so_dien_thoai`, `trang_thai`, `ngay_tao`) VALUES
(1, 'admin', '$2a$12$UHRC2flycgjyh/bpatCJDO14bC/ap99VXEFV/WAXmXuCRbFlIDHyG', 'Khá Là', 'Bảnh', '0123456789', 'active', '2026-05-05 20:23:28'),
(2, 'user1', '$2y$10$J1nTsf6mrr0hEbfODkCFHOdZfkdMRy.UOjJM4pHYPXz1Ocutm0dyi', 'Bành Phú', 'Hội', '1234567899', 'active', '2026-05-05 20:24:39');

--
-- Đang đổ dữ liệu cho bảng `administrator`
--

INSERT INTO `administrator` (`userid`) VALUES
(1);

--
-- Đang đổ dữ liệu cho bảng `member`
--

INSERT INTO `member` (`userid`, `diem_tich_luy`, `ten_rank`) VALUES
(2, 0, NULL);

--
-- Đang đổ dữ liệu cho bảng `thong_tin`
--

INSERT INTO `thong_tin` (`loai`, `noi_dung`, `hinh_anh_nen`, `so_thu_tu`, `trang_thai`) VALUES
('about',
'<div class="not-prose" style="text-align:center; padding: 40px 20px; background: linear-gradient(135deg, #ef4444, #f97316); border-radius: 16px; color: white; margin-bottom: 32px;">
<p style="text-transform:uppercase; letter-spacing:2px; font-size:14px; margin-bottom:8px; opacity:0.9;">Về chúng tôi</p>
<h1 style="font-size:36px; font-weight:bold; margin:0;">BookTab &mdash; Nơi sách gặp công nghệ</h1>
<p style="margin-top:12px; font-size:18px; opacity:0.9;">Xây dựng trải nghiệm mua sách trực tuyến nhanh, rõ ràng và đáng tin cậy cho mọi độc giả.</p>
</div>

<h2>BookTab l&agrave; g&igrave;?</h2>
<p>BookTab l&agrave; dự &aacute;n thương mại điện tử s&aacute;ch được ph&aacute;t triển theo kiến tr&uacute;c MVC tự x&acirc;y dựng. Ch&uacute;ng t&ocirc;i tập trung v&agrave;o trải nghiệm t&igrave;m kiếm dễ d&ugrave;ng, th&ocirc;ng tin minh bạch v&agrave; quy tr&igrave;nh đặt h&agrave;ng đơn giản để ai cũng c&oacute; thể mua s&aacute;ch thuận tiện.</p>
<p>Với kho s&aacute;ch đa dạng từ văn học trong nước đến s&aacute;ch ngoại văn, từ s&aacute;ch gi&aacute;o khoa đến tiểu thuyết, BookTab mong muốn trở th&agrave;nh điểm đến tin cậy cho mọi đối tượng độc giả &mdash; từ học sinh, sinh vi&ecirc;n đến những người y&ecirc;u s&aacute;ch ở mọi lứa tuổi.</p>

<h2>Sứ mệnh của ch&uacute;ng t&ocirc;i</h2>
<p>Ch&uacute;ng t&ocirc;i mong muốn r&uacute;t ngắn khoảng c&aacute;ch giữa người đọc v&agrave; những đầu s&aacute;ch chất lượng th&ocirc;ng qua một nền tảng ổng định, dễ truy cập v&agrave; th&acirc;n thiện với cả người d&ugrave;ng mới.</p>
<p>Mỗi ng&agrave;y, h&agrave;ng ng&agrave;n cuốn s&aacute;ch mới được xuất bản tr&ecirc;n thế giới. Nhiệm vụ của BookTab l&agrave; gi&uacute;p bạn tiếp cận những cuốn s&aacute;ch đ&oacute; một c&aacute;ch nhanh nhất, với gi&aacute; cả hợp l&yacute; nhất v&agrave; dịch vụ chuy&ecirc;n nghiệp nhất.</p>

<h2>Gi&aacute; trị cốt l&otilde;i</h2>
<p>BookTab ưu ti&ecirc;n ba gi&aacute; trị ch&iacute;nh trong mọi hoạt động:</p>
<ul>
<li><strong>Minh bạch th&ocirc;ng tin:</strong> M&otilde;i cuốn s&aacute;ch đều c&oacute; m&ocirc; tả chi tiết, h&igrave;nh ảnh thực tế v&agrave; đ&aacute;nh gi&aacute; từ người mua. Kh&ocirc;ng c&oacute; th&ocirc;ng tin ẩn hay ph&iacute; ph&aacute;t sinh.</li>
<li><strong>Tối ưu hiệu năng:</strong> Trang web tải nhanh, t&igrave;m kiếm ch&iacute;nh x&aacute;c, quy tr&iacute;nh đặt h&agrave;ng gọn g&agrave;ng. Ch&uacute;ng t&ocirc;i li&ecirc;n tục cải thiện tốc độ v&agrave; trải nghiệm người d&ugrave;ng.</li>
<li><strong>Cải tiến li&ecirc;n tục:</strong> Phản hồi từ người d&ugrave;ng l&agrave; nguồn cảm hứng cho mọi cập nhật. Ch&uacute;ng t&ocirc;i lắng nghe, học hỏi v&agrave; cải thiện mỗi ng&agrave;y.</li>
</ul>

<h2>Đội ngũ đằng sau BookTab</h2>
<p>BookTab được x&acirc;y dựng bởi đội ngũ nhỏ gọn nhưng đam m&ecirc;, bao gồm c&aacute;c lập tr&igrave;nh vi&ecirc;n, thiết kế vi&ecirc;n v&agrave; những người y&ecirc;u s&aacute;ch. Ch&uacute;ng t&ocirc;i tin rằng c&ocirc;ng nghệ phải phục vụ con người, v&agrave; m&otilde;i d&ograve;ng code đều hướng tới trải nghiệm tốt hơn cho người đọc.</p>

<h2>Li&ecirc;n hệ với ch&uacute;ng t&ocirc;i</h2>
<p>Bạn c&oacute; thắ̂c măc, đề xuất hay muốn hợp t&aacute;c? Đừng ngần ngại li&ecirc;n hệ:</p>
<ul>
<li><strong>Email:</strong> support@booktab.vn</li>
<li><strong>Hotline:</strong> 0123 456 789 (8:00 &ndash; 21:00 h&agrave;ng ng&agrave;y)</li>
<li><strong>Địa chỉ:</strong> 123 Đường S&aacute;ch, Quận 1, TP. Hồ Ch&iacute; Minh</li>
</ul>

<blockquote><p>&ldquo;Mỗi cuốn s&aacute;ch l&agrave; một c&aacute;nh cửa. BookTab gi&uacute;p bạn mở c&aacute;nh cửa đ&oacute; dễ d&agrave;ng hơn.&rdquo;</p></blockquote>',
NULL, 1, 'active'),
('footer', '<p>Nhà sách trực tuyến - Nơi sách gặp công nghệ.</p>', NULL, 1, 'active'),
('footer', '<p>Email: support@booktab.vn<br>Hotline: 0123 456 789</p>', NULL, 2, 'active');

--
-- Đang đổ dữ liệu cho bảng `loai_cau_hoi`
--

INSERT INTO `loai_cau_hoi` (`ten_loai`, `so_thu_tu`) VALUES
('Đặt hàng', 1000),
('Thanh toán', 2000),
('Vận chuyển', 3000),
('Sản phẩm & sách', 4000),
('Tài khoản & đơn hàng', 5000),
('Ưu đãi & khuyến mãi', 6000),
('Khác', 7000);

--
-- Đang đổ dữ liệu cho bảng `cau_hoi`
--

INSERT INTO `cau_hoi` (`ma_cau_hoi`, `ten_cau_hoi`, `trang_thai`, `ma_loai`, `userid`) VALUES
(1, 'Làm sao để đặt mua một cuốn sách trên BookTab?', 'da_tra_loi', 1, 2),
(2, 'BookTab hỗ trợ những hình thức thanh toán nào?', 'da_tra_loi', 2, 2),
(3, 'Bao lâu tôi sẽ nhận được hàng sau khi đặt?', 'da_tra_loi', 3, 2),
(4, 'BookTab có giao hàng toàn quốc không?', 'da_tra_loi', 3, 1),
(5, 'Có thể đổi trả sách trong bao lâu?', 'da_tra_loi', 1, 1),
(6, 'Tôi có thể thay đổi địa chỉ giao hàng sau khi đặt không?', 'da_tra_loi', 3, 2),
(7, 'BookTab có bọc sách plastic không?', 'da_tra_loi', 1, 1),
(8, 'Làm sao để biết sách còn hàng hay hết?', 'da_tra_loi', 4, 2),
(9, 'Tôi quên mật khẩu, làm thế nào để lấy lại?', 'da_tra_loi', 5, 1),
(10, 'Làm sao để sử dụng mã giảm giá?', 'da_tra_loi', 6, 1),
(11, 'BookTab có bán sách điện tử (ebook) không?', 'da_tra_loi', 4, 2),
(12, 'Phí vận chuyển được tính như thế nào?', 'da_tra_loi', 3, 1),
(13, 'Tôi có được kiểm tra hàng trước khi thanh toán không?', 'da_tra_loi', 2, 1),
(14, 'BookTab có xuất hóa đơn đỏ (VAT) không?', 'da_tra_loi', 7, 2),
(15, 'Làm sao để xóa tài khoản?', 'da_tra_loi', 5, 2),
(16, 'Sách mua về bị rách trang, tôi phải làm sao?', 'da_tra_loi', 1, 1),
(17, 'BookTab có chương trình khách hàng thân thiết không?', 'da_tra_loi', 6, 2),
(18, 'Tôi có thể hủy đơn hàng đã đặt được không?', 'da_tra_loi', 5, 1),
(19, 'Sách ngoại văn trên BookTab có phải bản gốc không?', 'da_tra_loi', 4, 1),
(20, 'Tôi muốn hợp tác bán sách trên BookTab thì liên hệ ai?', 'da_tra_loi', 7, 2),
(21, 'Làm sao để đăng ký nhận bản tin khuyến mãi từ BookTab?', 'da_tra_loi', 6, 2),
(22, 'Tôi có thể mua sách số lượng lớn để làm quà tặng không?', 'da_tra_loi', 1, 2),
(23, 'BookTab có xuất bản sách không hay chỉ phân phối?', 'da_tra_loi', 7, 2),
(24, 'Nếu tôi nhận được sách không đúng như mô tả, tôi cần làm gì?', 'da_tra_loi', 4, 2),
(25, 'Thời gian làm việc của tổng đài chăm sóc khách hàng là khi nào?', 'da_tra_loi', 7, 2),
(26, 'Tôi có thể bảo lưu giỏ hàng cho lần đăng nhập sau không?', 'da_tra_loi', 5, 2),
(27, 'Có phụ phí gì khi thanh toán bằng thẻ tín dụng không?', 'da_tra_loi', 2, 2),
(28, 'Tôi có thể thay đổi số điện thoại nhận hàng được không?', 'da_tra_loi', 3, 2),
(29, 'Sách combo có được tách lẻ ra để đổi trả không?', 'da_tra_loi', 4, 2),
(30, 'Làm sao để đánh giá và viết nhận xét cho sách đã mua?', 'da_tra_loi', 5, 2);

--
-- Đang đổ dữ liệu cho bảng `cau_tra_loi`
--

INSERT INTO `cau_tra_loi` (`ma_cau_tra_loi`, `ma_cau_hoi`, `administrator_userid`, `noi_dung`, `ngay_dang`) VALUES
(1, 1, 1, 'Bạn chọn sách ở trang Sản phẩm, thêm vào giỏ hàng rồi xác nhận đơn. Hệ thống sẽ lưu đơn hàng và gửi thông báo trạng thái sau khi xử lý.', '2026-05-06 09:00:00'),
(2, 2, 1, 'Hiện tại BookTab hỗ trợ thanh toán khi nhận hàng (COD). Các phương thức thanh toán trực tuyến sẽ được bổ sung ở các giai đoạn tiếp theo.', '2026-05-06 09:15:00'),
(3, 3, 1, 'Thời gian giao hàng dự kiến từ 2-5 ngày làm việc tùy khu vực nhận hàng. Trong giờ cao điểm, thời gian có thể thay đổi và sẽ được cập nhật trong đơn.', '2026-05-06 09:30:00'),
(4, 4, 1, 'Có. BookTab hỗ trợ giao hàng trên toàn quốc thông qua đối tác vận chuyển, thời gian nhận hàng tùy theo khu vực.', '2026-05-06 09:40:00'),
(5, 5, 1, 'Bạn có thể yêu cầu đổi trả trong vòng 7 ngày kể từ khi nhận hàng nếu sản phẩm lỗi hoặc sai đơn.', '2026-05-06 09:50:00'),
(6, 6, 1, 'Nếu đơn hàng chưa được xác nhận, bạn có thể vào mục Quản lý đơn hàng để cập nhật địa chỉ. Nếu đơn đã gửi đi, bạn vui lòng liên hệ hotline.', '2026-05-06 10:00:00'),
(7, 7, 1, 'Hiện tại BookTab có cung cấp dịch vụ bọc sách plastic (Bookcare). Bạn có thể tick chọn dịch vụ này ở bước thanh toán.', '2026-05-06 10:05:00'),
(8, 8, 1, 'Trạng thái còn hàng hay hết hàng sẽ được hiển thị trực tiếp trên trang chi tiết của mỗi cuốn sách.', '2026-05-06 10:10:00'),
(9, 9, 1, 'Bạn chọn nút "Quên mật khẩu" ở trang Đăng nhập và nhập email của mình. Hệ thống sẽ gửi link đặt lại mật khẩu vào email của bạn.', '2026-05-06 10:15:00'),
(10, 10, 1, 'Tại trang Giỏ hàng hoặc Thanh toán, bạn nhập mã vào ô "Mã giảm giá" và nhấn Áp dụng để hệ thống tính lại số tiền.', '2026-05-06 10:20:00'),
(11, 11, 1, 'BookTab hiện chỉ tập trung phân phối sách giấy vật lý và chưa có kế hoạch mở bán sách điện tử (ebook).', '2026-05-06 10:25:00'),
(12, 12, 1, 'Phí vận chuyển phụ thuộc vào khoảng cách và khối lượng đơn hàng. Bạn sẽ thấy phí ship chính xác tại bước thanh toán.', '2026-05-06 10:30:00'),
(13, 13, 1, 'Bạn hoàn toàn được quyền đồng kiểm (kiểm tra ngoại quan gói hàng) cùng với bưu tá trước khi thanh toán tiền.', '2026-05-06 10:35:00'),
(14, 14, 1, 'Có. Bạn vui lòng điền thông tin xuất hóa đơn (Tên công ty, MST, Địa chỉ) vào mục Ghi chú khi tiến hành đặt mua.', '2026-05-06 10:40:00'),
(15, 15, 1, 'Để xóa tài khoản, bạn vui lòng gửi email yêu cầu đến bộ phận CSKH bằng email đã đăng ký. Chúng tôi sẽ xử lý trong vòng 24h.', '2026-05-06 10:45:00'),
(16, 16, 1, 'Rất xin lỗi bạn về sự cố này. Bạn vui lòng chụp ảnh tình trạng sách và gửi cho CSKH trong vòng 7 ngày để được hỗ trợ đổi trả miễn phí.', '2026-05-06 10:50:00'),
(17, 17, 1, 'BookTab đang trong quá trình phát triển tính năng tích điểm đổi quà. Chúng tôi sẽ sớm ra mắt tính năng này trong tương lai gần.', '2026-05-06 10:55:00'),
(18, 18, 1, 'Bạn có thể tự hủy đơn hàng trong trạng thái "Chờ xác nhận". Nếu đơn đã chuyển sang "Đang giao", bạn không thể tự hủy trên web.', '2026-05-06 11:00:00'),
(19, 19, 1, 'Chúng tôi cam kết 100% sách ngoại văn đều là ấn bản chính hãng được nhập khẩu trực tiếp từ các nhà xuất bản quốc tế.', '2026-05-06 11:05:00'),
(20, 20, 1, 'Mọi đề xuất hợp tác kinh doanh, ký gửi sách, bạn vui lòng liên hệ trực tiếp qua email partner@booktab.vn nhé.', '2026-05-06 11:10:00'),
(21, 21, 1, 'Bạn chỉ cần kéo xuống cuối trang web, nhập email vào ô "Đăng ký nhận tin" và nhấn nút Đăng ký.', '2026-05-07 15:00:00'),
(22, 22, 1, 'Có, BookTab hỗ trợ mua sỉ với chiết khấu hấp dẫn. Vui lòng liên hệ mục Khách hàng doanh nghiệp hoặc gọi hotline để được báo giá.', '2026-05-07 15:10:00'),
(23, 23, 1, 'Hiện tại BookTab là nền tảng bán lẻ phân phối sách từ các nhà xuất bản uy tín trong và ngoài nước, chưa trực tiếp tham gia mảng xuất bản sách.', '2026-05-07 15:15:00'),
(24, 24, 1, 'Bạn vui lòng quay video/chụp ảnh tình trạng sách và mã đơn hàng gửi cho CSKH trong vòng 48h để chúng tôi tiến hành đổi mới miễn phí ngay lập tức.', '2026-05-07 15:20:00'),
(25, 25, 1, 'Tổng đài chăm sóc khách hàng của BookTab hoạt động từ 8h00 đến 21h00 tất cả các ngày trong tuần, kể cả ngày Lễ/Tết.', '2026-05-07 15:25:00'),
(26, 26, 1, 'Hệ thống sẽ tự động lưu giỏ hàng của bạn nếu bạn đã đăng nhập tài khoản. Lần tới bạn đăng nhập trên bất kỳ thiết bị nào, giỏ hàng vẫn sẽ hiển thị.', '2026-05-07 15:30:00'),
(27, 27, 1, 'BookTab miễn phí hoàn toàn phí quẹt thẻ và phí xử lý giao dịch cho mọi hình thức thanh toán trực tuyến (ATM, Visa, Mastercard).', '2026-05-07 15:35:00'),
(28, 28, 1, 'Nếu đơn hàng chưa được bàn giao cho đơn vị vận chuyển, bạn có thể tự đổi số điện thoại trong Quản lý đơn hàng. Nếu đơn đã gửi đi, vui lòng gọi CSKH để được hỗ trợ.', '2026-05-07 15:40:00'),
(29, 29, 1, 'Theo chính sách của BookTab, các sản phẩm bán theo dạng bộ/combo nguyên seal không hỗ trợ đổi trả lẻ từng cuốn nếu không có lỗi từ nhà sản xuất.', '2026-05-07 15:45:00'),
(30, 30, 1, 'Khi đơn hàng chuyển trạng thái "Giao hàng thành công", bạn vào "Đơn hàng của tôi", chọn đơn hàng tương ứng và nhấn vào nút "Đánh giá" để để lại nhận xét nhé.', '2026-05-07 15:50:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
