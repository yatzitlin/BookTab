# Authentication & Security Guidelines (PHP Native MVC)

## 1. Kết Nối Cơ Sở Dữ Liệu (Database Connection)
- **Công nghệ BẮT BUỘC:** Sử dụng PDO (PHP Data Objects). KHÔNG sử dụng `mysqli_query` hay `mysql_query` cũ.
- **Cấu trúc:** Viết kết nối trong file `app/core/Database.php` dưới dạng một Class.
- **Xử lý lỗi:** Phải thiết lập chế độ báo lỗi ngoại lệ (`PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION`) để dễ dàng debug.
- **Charset:** Bắt buộc set charset là `utf8mb4` để hỗ trợ hiển thị tiếng Việt và emoji không bị lỗi.

## 2. Tiêu Chuẩn Bảo Mật (Security Requirements)

Khi viết bất kỳ dòng code nào tương tác với Database hoặc hiển thị dữ liệu ra View, phải tuân thủ 3 quy tắc chống lỗ hổng bảo mật sau:

### 2.1. Chống lỗ hổng SQL Injection (SQLi)
- **Vấn đề:** Hacker nhập mã SQL độc hại vào form (VD: `' OR 1=1 --`) để thao túng cơ sở dữ liệu.
- **Cách xử lý BẮT BUỘC:** - KHÔNG BAO GIỜ nối chuỗi trực tiếp các biến `$_POST` hoặc `$_GET` vào câu lệnh SQL.
  - LUÔN LUÔN sử dụng Prepared Statements (`$stmt = $pdo->prepare(...)` và `$stmt->execute([...])`). Sử dụng dấu `?` hoặc tham số định danh (named parameters `:name`) cho các giá trị động.

### 2.2. Chống lỗ hổng Cross-Site Scripting (XSS)
- **Vấn đề:** Hacker nhập mã Javascript độc hại `<script>...</script>` vào ô input (VD: tên người dùng, nội dung bình luận sách) để ăn cắp Session/Cookie của người dùng khác.
- **Cách xử lý BẮT BUỘC:** - Mọi dữ liệu lấy từ Database trước khi in ra giao diện (View - HTML) đều phải được bọc qua hàm `htmlspecialchars($data, ENT_QUOTES, 'UTF-8')`.

### 2.3. Quản lý Mật khẩu An toàn (Password Security)
- **Vấn đề:** Lưu mật khẩu dạng text thường (plaintext) hoặc dùng các hàm băm cũ (như MD5, SHA1) rất dễ bị bẻ khóa nếu lộ Database.
- **Cách xử lý BẮT BUỘC:**
  - Mật khẩu lưu vào database PHẢI được mã hóa bằng hàm `password_hash($password, PASSWORD_BCRYPT)`.
  - Quá trình đăng nhập phải kiểm tra bằng hàm `password_verify($password_input, $hashed_password_from_db)`.

### 2.4. Xác thực dữ liệu đầu vào (Data Validation)
- Bắt buộc kiểm tra (validate) dữ liệu form ở cả 2 cấp độ:
  - **Client-side:** Sử dụng thuộc tính HTML5 (`required`, `type="email"`, `pattern`) và Javascript.
  - **Server-side (PHP):** Kiểm tra rỗng, độ dài mật khẩu tối thiểu (6 ký tự), định dạng email sử dụng hàm `filter_var($email, FILTER_VALIDATE_EMAIL)`.

## 3. Luồng Chức Năng Đăng Ký / Đăng Nhập (Auth Flow)

### 3.0. Quy Ước Hiện Tại Trong Code
- **Login / Register dùng `POST`** để gửi dữ liệu form.
- **URL chỉ chứa `action=login` hoặc `action=register`**, còn dữ liệu nhạy cảm nằm trong request body, không hiện trực tiếp trên URL.
- **Hệ thống đang dùng SESSION**, chưa dùng JWT/token đăng nhập riêng.
- **Admin route hiện tại đi qua** `public/index.php?page=admin` hoặc `page=admin_dashboard`.

### 3.1. Flow Đăng Ký (Register)
1. **Controller (`AuthController`):** Nhận request `POST`, kiểm tra `csrf_token`, rồi validate dữ liệu Server-side.
2. Kiểm tra username đã tồn tại trong database chưa. Nếu có, báo lỗi.
3. Nếu username chưa tồn tại: mã hóa mật khẩu bằng `password_hash()`.
4. **Model (`UserModel`):** Thực thi câu lệnh `INSERT` để tạo tài khoản mới (mặc định role là `member`).
5. Đăng ký thành công -> chuyển hướng về trang đăng nhập với query `registered=1`.

### 3.2. Flow Đăng Nhập (Login)
1. **Controller (`AuthController`):** Nhận username và password từ `POST`, kiểm tra `csrf_token`, rồi validate dữ liệu.
2. **Model (`UserModel`):** Truy vấn tìm user theo `username`.
3. Nếu tìm thấy user: dùng `password_verify()` so sánh mật khẩu nhập vào với mật khẩu trong DB.
4. Nếu đúng:
  - Lưu thông tin user vào `$_SESSION` gồm `userid`, `username`, `ho_va_ten_dem`, `ten`, `user_role`.
  - Nếu role là `administrator` thì chuyển hướng về `public/index.php?page=admin`.
  - Nếu role là `member` thì chuyển hướng về `public/index.php?page=home`.
5. Nếu sai: trả về thông báo lỗi "Tên đăng nhập hoặc mật khẩu không chính xác".

### 3.3. Bảo Vệ Admin Hiện Tại
- Trước khi render admin page, `public/index.php` kiểm tra `$_SESSION['user_role']`.
- Nếu không phải `administrator`, hệ thống redirect về login với `error=unauthorized`.
- Điều này ngăn truy cập trực tiếp vào admin khi chưa đủ quyền.

### 3.4. CSRF Token Hiện Tại
- Token được tạo ở `public/index.php` bằng `bin2hex(random_bytes(32))` và lưu trong `$_SESSION['csrf_token']`.
- Login/register form có hidden field `csrf_token`.
- `AuthController` kiểm tra token trước khi xử lý POST.
