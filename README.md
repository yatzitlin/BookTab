# Bài tập lớn Lập trình Web - MobileS

Dự án xây dựng website thương mại điện tử, phục vụ môn học Lập trình web (HK2 2025-2026). Dự án được phát triển bằng PHP thuần áp dụng kiến trúc MVC tự xây dựng, hoàn toàn không sử dụng bất kỳ PHP Framework hay CMS.

## 🛠 Công nghệ sử dụng
* **Front-end:** HTML5, CSS3 (chuẩn W3C), JavaScript.
* **Back-end:** PHP (Phiên bản >= 7.0).
* **Cơ sở dữ liệu:** MySQL.
* **UI/UX:** Responsive Design,....
* **Admin Template:** Srtdash Dashboard.

## 🌟 Chức năng chính
Hệ thống phân quyền 3 cấp độ người dùng:

### 1. Khách truy cập (Guest)
* Xem các trang thông tin public: Trang chủ, Giới thiệu, Sản phẩm, Bảng giá, Liên hệ, Hỏi/đáp, Tin tức.
* Tìm kiếm sản phẩm, tin tức theo từ khóa.
* Thêm sản phẩm vào giỏ hàng.
* Đăng ký, Đăng nhập.

### 2. Thành viên (User)
* Thay đổi thông tin cá nhân, mật khẩu, hình đại diện.
* Viết bình luận, đánh giá sản phẩm và bài viết tin tức.

### 3. Quản trị viên (Admin)
* **Quản lý hệ thống:** Quản lý nội dung tĩnh (số điện thoại, địa chỉ, logo, banner).
* **Quản lý người dùng:** Xem thông tin, khóa/mở khóa tài khoản, reset mật khẩu.
* **Quản lý cửa hàng:** Thêm/sửa/xóa sản phẩm, phân loại danh mục.
* **Quản lý đơn hàng:** Xem giỏ hàng, cập nhật trạng thái đơn hàng.
* **Quản lý nội dung:** Thêm/sửa/xóa bài viết tin tức, hỏi đáp, duyệt bình luận.
* **Quản lý liên hệ:** Xem và phản hồi tin nhắn từ trang Liên hệ.

## 📂 Cấu trúc thư mục (Mô hình MVC)
```text
.
├── app/                # Mã nguồn chính
│   ├── models/         # Tương tác với CSDL (MySQL)
│   ├── controllers/    # Xử lý logic
│   └── views/          # Giao diện
│       ├── admin/      # Giao diện Quản trị viên
│       ├── layouts/    # Template chung
│       └── pages/      # Giao diện Client
├── config/             # Cấu hình kết nối CSDL
├── core/               # Lõi xử lý MVC (Router, Database, Base Controller)
├── public/             # Thư mục gốc truy cập web
├── database.sql        # File Cơ sở dữ liệu
└── README.md
```

## 👥 Danh sách thành viên và Phân công

### Phần công việc chung:
* Thiết kế mô hình ứng dụng (không sử dụng PHP framework) và thiết kế cơ sở dữ liệu quan hệ.
* Thiết kế các mẫu (template) chung cho website.
* Hiện thực giao diện & tính năng đăng ký / đăng nhập người dùng, phân quyền cho người dùng.
* Xây dựng tính năng cho phép người dùng thay đổi thông tin cá nhân, mật khẩu, avatar,... sau khi đã đăng ký.
* Xây dựng tính năng quản lý người dùng dành cho quản trị viên (xem thông tin, reset mật khẩu, khoá người dùng...).

### Phần công việc riêng:

#### 1. Huỳnh Đức Huy (Công việc #1)
* **Giao diện:** Thiết kế Trang chủ và Trang Liên hệ.
* **Tính năng quản lý (Admin):**
    * Quản lý thông tin trên các trang đã thiết kế (cho phép thay đổi các nội dung giới thiệu, số điện thoại, địa chỉ công ty, hình ảnh, logo,...).
    * Quản lý các liên hệ của khách hàng (xem thông tin, đánh dấu đã đọc/chưa đọc/đã phản hồi, xoá liên hệ,...).

#### 2. Nguyễn Văn Hiệp (Công việc #2)
* **Giao diện:** Thiết kế Trang Giới thiệu và Trang Hỏi/đáp.
* **Tính năng quản lý (Admin):**
    * Quản lý thông tin trên các trang đã thiết kế (thay đổi các nội dung giới thiệu, hình ảnh...).
    * Quản lý các câu hỏi/đáp (xem, thêm, sửa, xoá).

#### 3. Nguyễn Minh Thái (Công việc #3)
* **Giao diện:** Thiết kế Trang danh sách sản phẩm (có tìm kiếm theo từ khoá) và Trang thông tin chi tiết sản phẩm / giỏ hàng.
* **Tính năng quản lý (Admin):**
    * Quản lý sản phẩm (tìm kiếm, xem, thêm, sửa, xoá thông tin).
    * Quản lý giỏ hàng, đơn hàng (xem thông tin, đánh dấu trạng thái cho giỏ hàng, đơn hàng).

#### 4. Bành Phú Hội (Công việc #4)
* **Giao diện:** Thiết kế Trang danh sách bài viết (có tìm kiếm tin tức theo từ khoá) và Trang đọc bài viết.
* **Tính năng quản lý (Admin):**
    * Quản lý tin tức (tìm kiếm, xem, thêm, sửa, xoá thông tin bài viết).
    * Quản lý bình luận/đánh giá của người dùng trên bài viết.