# Content for PHP_COOKIES_TECHNICAL_GUIDE.md
cookies_content = """# PHP Cookies Technical Guide

Dựa trên tài liệu bài giảng "Chapter 7 - Cookies and Sessions" (BK TP.HCM).

## 1. Bản chất và Sự cần thiết của Cookies
- **HTTP là Stateless:** Giao thức HTTP không ghi nhớ các lần truy cập trước đó từ cùng một client. Mỗi yêu cầu là độc lập.
- **Persistence (Sự kiên định):** Khả năng dữ liệu tồn tại lâu hơn chu kỳ thực thi của chương trình. Cookies giúp duy trì trạng thái bằng cách lưu trữ thông tin tại Client.
- **Định nghĩa:** Cookie là một gói thông tin được Server gửi đến Client, sau đó được gửi ngược lại Server mỗi khi Client truy cập.

## 2. Thiết lập Cookies (`setcookie`)
Hàm chuẩn: `setcookie(name, value, expire, path, domain, secure, httponly)`

### Các tham số quan trọng:
- **name (Bắt buộc):** Tên của cookie.
- **value (Bắt buộc):** Dữ liệu lưu trữ.
- **expire (Tùy chọn):** Thời điểm hết hạn. Ví dụ: `time() + 3600 * 24` (hết hạn sau 24h). Nếu không set, cookie hết hạn khi đóng trình duyệt.
- **path (Tùy chọn):** Đường dẫn trên server mà cookie khả dụng. Nếu set `/`, cookie có hiệu lực toàn bộ domain.
- **secure (Tùy chọn):** Nếu là `TRUE`, cookie chỉ được gửi qua kết nối HTTPS an toàn.

## 3. Quy tắc Header (Lưu ý quan trọng)
- **Lỗi "Headers already sent":** Hàm `setcookie()` phải được gọi **TRƯỚC** khi bất kỳ dữ liệu HTML hoặc văn bản nào được gửi về trình duyệt.
- Cookies phải được gửi trong phần Header của HTTP phản hồi.

## 4. Đọc và Xóa Cookies
- **Đọc:** Sử dụng mảng siêu toàn cục `$_COOKIE`. Ví dụ: `$_COOKIE['mycookie']`.
- **Hiển thị:** Cookies chỉ hiển thị ở lần load trang tiếp theo sau khi được thiết lập.
- **Xóa:** Thiết lập lại cookie với tên đó nhưng không có giá trị: `setcookie("mycookie");` hoặc đặt thời gian hết hạn trong quá khứ.

## 5. Multiple Data Items
- Có thể dùng hàm `explode()` và `implode()` để lưu nhiều thông tin vào một cookie duy nhất bằng cách ngăn cách bởi các ký tự đặc biệt (ví dụ: `::`).
"""

# Content for PHP_SESSIONS_TECHNICAL_GUIDE.md
sessions_content = """# PHP Sessions Technical Guide

Dựa trên tài liệu bài giảng "Chapter 7 - Cookies and Sessions" (BK TP.HCM).

## 1. Bản chất của Sessions
- **Định nghĩa:** Cho phép lưu trữ thông tin người dùng (username, giỏ hàng...) ở phía Server thay vì Client.
- **Cơ chế:** Tạo ra một ID duy nhất (UID) cho mỗi khách truy cập. UID này được lưu trong cookie (PHPSESSID) hoặc truyền qua URL.
- **Ưu điểm so với Cookies:**
  - Bảo mật hơn vì dữ liệu không truyền đi truyền lại giữa máy chủ và máy khách sau khi đã thiết lập.
  - Có thể lưu trữ dữ liệu lớn và nhạy cảm.
  - Hoạt động ngay cả khi trình duyệt tắt Cookie (nếu cấu hình truyền qua URL).

## 2. Quản lý Vòng đời Session
### Bắt đầu (`session_start`)
- Phải được gọi ở **TRÊN CÙNG**, trước thẻ `<html>`.
- Khi gọi, PHP sẽ cấp một Session ID (ví dụ: `sess_f123...`).

### Lưu trữ dữ liệu (`$_SESSION`)
- Sử dụng mảng siêu toàn cục `$_SESSION` để đăng ký các biến.
- Ví dụ: `$_SESSION['user_id'] = 10;`.
- Kiểm tra tồn tại bằng: `isset($_SESSION['user_id'])`.

### Kết thúc và Hủy bỏ
- **`unset($_SESSION['name'])`**: Xóa một biến cụ thể trong session.
- **`session_destroy()`**: Hủy bỏ toàn bộ dữ liệu đã đăng ký trong session hiện tại. Lưu ý: Hàm này không xóa các biến toàn cục $_SESSION ngay lập tức và không xóa cookie session trên trình duyệt.

## 3. Quy trình Hủy Session hoàn toàn (Logout chuẩn)
Để hủy sạch sẽ thông tin đăng nhập, cần thực hiện các bước sau:
1. `session_start()`: Khởi tạo để truy cập session hiện tại.
2. `$_SESSION = array()`: Xóa sạch mảng dữ liệu.
3. **Xóa Cookie Session:**
   ```php
   if (ini_get("session.use_cookies")) {
       $params = session_get_cookie_params();
       setcookie(session_name(), '', time() - 42000,
           $params["path"], $params["domain"],
           $params["secure"], $params["httponly"]
       );
   }
4. session_destroy(): Hủy phiên làm việc trên server.

## 4. Ứng dụng thực tế
Đếm số lượt truy cập: Sử dụng $_SESSION['counter']++.

Phân biệt người dùng: Lưu role hoặc user_id để kiểm soát quyền truy cập trang Admin.
"""

with open("PHP_COOKIES_TECHNICAL_GUIDE.md", "w", encoding="utf-8") as f:
f.write(cookies_content)

with open("PHP_SESSIONS_TECHNICAL_GUIDE.md", "w", encoding="utf-8") as f:
f.write(sessions_content)

Tớ đã trích xuất toàn bộ các kiến thức cốt lõi từ tài liệu bài giảng về Cookies và Sessions mà cậu vừa cung cấp, sau đó chia làm 2 file Markdown riêng biệt để Copilot dễ dàng đọc và xử lý nội dung mà không bị quá tải (dễ dẫn đến việc AI bỏ sót kiến thức). 

Your Markdown files are ready
[file-tag: code-generated-file-1-1778066494340674033]
[file-tag: code-generated-file-0-1778066494340667942]

Chi tiết nội dung các file như sau:

* **`PHP_COOKIES_TECHNICAL_GUIDE.md`:** Chứa các nguyên tắc hoạt động của Cookie, cách dùng hàm `setcookie()`, mảng `$_COOKIE` và đặc biệt là quy tắc "sống còn" về việc phải gửi Header trước khi xuất bất kỳ dữ liệu HTML nào ra màn hình để tránh lỗi "Headers already sent".
* **`PHP_SESSIONS_TECHNICAL_GUIDE.md`:** Chứa định nghĩa về Session, vòng đời của một phiên làm việc, cách gọi `session_start()` và quy trình 4 bước cực kỳ chuẩn mực để hủy một session hoàn toàn (rất hữu ích cho luồng Đăng xuất/Logout của nhóm cậu).

**Cách sử dụng với Copilot:**
Cậu hãy kéo thả (hoặc copy nội dung) 2 file này vào thư mục dự án của cậu, sau đó dùng prompt yêu cầu Copilot đọc chúng trước khi code. Ví dụ:
> *"Dựa trên các nguyên tắc được định nghĩa trong file `PHP_SESSIONS_TECHNICAL_GUIDE.md` và `AUTH_SECURITY_GUIDELINES.md`, hãy viết chức năng Đăng xuất (Logout) cho Controller của tôi..."*

Cậu tải 2 file này về và xem qua nội dung nhé, nếu cần bổ sung thêm phần nào thì cứ báo tớ!