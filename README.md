# Bài tập lớn Lập trình Web - BookTab

Dự án xây dựng website bán sách online, phục vụ môn học Lập trình web (HK2 2025-2026). Dự án được phát triển bằng PHP thuần áp dụng kiến trúc MVC tự xây dựng, hoàn toàn không sử dụng bất kỳ PHP Framework hay CMS.

## 🛠 Công nghệ sử dụng
* **Front-end (Client):** HTML5, CSS3, JavaScript, Tailwind CSS (CDN), Font Awesome.
* **Front-end (Admin):** Srtdash Dashboard, Bootstrap 5, MetisMenuJS, SwiperJS, TinyMCE.
* **Back-end:** PHP thuần theo mô hình MVC (không dùng framework/CMS), Session/Cookie, CSRF token.
* **Cơ sở dữ liệu:** MySQL/MariaDB, truy cập qua PDO (Prepared Statements, charset `utf8mb4`).
* **Web server:** Apache (mod_rewrite qua `.htaccess`).
* **Môi trường phát triển:** XAMPP.

## 🌟 Chức năng chính
Hệ thống phân quyền 3 cấp độ người dùng:

### 1. Khách truy cập (Guest)
* Xem các trang thông tin public: Trang chủ, Giới thiệu, Sản phẩm, Bài viết, Hỏi & Đáp, Liên hệ.
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
BookTab/
├── app/                                # Mã nguồn chính của ứng dụng
│   ├── controllers/                    # Các Controller xử lý logic
│   │   ├── AuthController.php          # Đăng ký, đăng nhập, đăng xuất
│   │   ├── BaseController.php          # Lớp cha cho toàn bộ controller
│   │   ├── HomeController.php          # Điều phối trang chủ
│   │   ├── AboutController.php         # Trang Giới thiệu
│   │   ├── ContactController.php       # Trang Liên hệ, gửi liên hệ
│   │   ├── QnAController.php           # Trang Hỏi & Đáp
│   │   ├── NewsController.php          # Hiển thị tin tức, bình luận
│   │   ├── ProductController.php       # Danh sách/chi tiết sản phẩm, đánh giá
│   │   ├── CartController.php          # Giỏ hàng
│   │   ├── OrderController.php         # Thanh toán, đơn hàng
│   │   ├── UserController.php          # Thông tin cá nhân người dùng
│   │   ├── AdminController.php         # Trang chủ admin
│   │   ├── AdminNewsController.php     # Quản lý tin tức (CRUD)
│   │   ├── AdminUserController.php     # Quản lý người dùng
│   │   └── AdminCommentController.php  # Quản lý bình luận
│   │
│   ├── models/                         # Các Model (tương tác với CSDL)
│   │   ├── UserModel.php               # Người dùng, đăng ký, đăng nhập
│   │   ├── NewsModel.php               # Tin tức/bài viết
│   │   ├── CommentModel.php            # Bình luận
│   │   ├── ProductModel.php            # Sản phẩm, ảnh, đánh giá
│   │   ├── CartModel.php               # Giỏ hàng
│   │   ├── OrderModel.php              # Đơn hàng
│   │   ├── CategoryModel.php           # Danh mục sản phẩm
│   │   ├── NewsCategoryModel.php       # Danh mục tin tức
│   │   ├── QnAModel.php                # Hỏi & Đáp
│   │   ├── ContactModel.php            # Liên hệ khách hàng
│   │   └── ThongTinModel.php           # Thông tin tĩnh trên website
│   │
│   ├── views/                          # Các view (giao diện)
│   │   ├── template.php                # Template chung cho client
│   │   ├── components/                 # Component tái sử dụng
│   │   │   ├── Header.php              # Header website
│   │   │   └── Footer.php              # Footer website
│   │   ├── pages/                      # Trang công khai
│   │   │   ├── 404.php                 # Trang lỗi 404
│   │   │   ├── About.php               # Trang Giới thiệu
│   │   │   ├── Ask.php                 # Đặt câu hỏi
│   │   │   ├── Cart.php                # Giỏ hàng
│   │   │   ├── Checkout.php            # Thanh toán
│   │   │   ├── Contact.php             # Trang liên hệ
│   │   │   ├── FAQ.php                 # Câu hỏi thường gặp
│   │   │   ├── Home.php                # Trang chủ
│   │   │   ├── My.php                  # Thông tin cá nhân người dùng
│   │   │   ├── News.php                # Trang tin tức nổi bật
│   │   │   ├── NewsDetail.php          # Chi tiết bài viết
│   │   │   ├── NewsList.php            # Danh sách tin tức
│   │   │   ├── OrderDetail.php         # Chi tiết đơn hàng
│   │   │   ├── OrderHistory.php        # Lịch sử đơn hàng
│   │   │   ├── ProductDetail.php       # Chi tiết sản phẩm
│   │   │   ├── Products.php            # Danh sách sản phẩm
│   │   │   ├── QnA.php                 # Hỏi & Đáp
│   │   │   └── auth/                   # Trang xác thực
│   │   │       ├── login.php           # Đăng nhập
│   │   │       └── register.php        # Đăng ký
│   │   └── admin/                      # Giao diện quản trị
│   │       ├── adminLayout.php         # Layout chính của admin
│   │       ├── adminComponents/        # Component của admin
│   │       │   ├── AdminCreateNews.php # Form tạo/sửa tin tức
│   │       │   ├── AdminFooter.php     # Footer admin
│   │       │   ├── AdminHeader.php     # Header admin
│   │       │   └── AdminSidebar.php    # Sidebar admin
│   │       └── adminPages/             # Các trang admin
│   │           ├── AdminAbout.php      # Quản lý Giới thiệu
│   │           ├── AdminComment.php    # Quản lý bình luận
│   │           ├── AdminContact.php    # Quản lý liên hệ
│   │           ├── AdminInfo.php       # Quản lý thông tin công ty
│   │           ├── AdminNews.php       # Quản lý tin tức
│   │           ├── AdminOrders.php     # Quản lý đơn hàng
│   │           ├── AdminProducts.php   # Quản lý sản phẩm
│   │           ├── AdminQnA.php        # Quản lý Hỏi & Đáp
│   │           └── AdminUserManage.php # Quản lý người dùng
│   │
│   └── core/                           # Lõi xử lý ứng dụng
│       └── Database.php                # Kết nối và xử lý CSDL (PDO)
│
├── public/                             # Thư mục công khai trên web
│   ├── .htaccess                       # Cấu hình Apache (URL rewriting cho bài viết)
│   ├── admin_assets/                   # Assets cho admin
│   │   ├── css/
│   │   ├── fonts/
│   │   ├── images/
│   │   ├── js/
│   │   └── home/
│   ├── css/                            # Folder css
│   │   ├── news.css/                   # CSS cho trang Bài viết
│   ├── index.php                       # Front Controller (router chính)
│   ├── upload/                         # Hình ảnh tải lên từ server (bài viết, hỏi đáp)
│   └── uploads/                        # Hình ảnh tải lên từ server (sản phẩm, avatar)
│
├── sql/                                # Thư mục chứa file SQL
│   ├── GenerateTable.sql               # Script khởi tạo CSDL
│   └── booktab.sql                     # Dữ liệu mẫu của website
│
├── COOKIES_PHP.md                      # Tài liệu cookie/session
├── WEB_SECURITY.md                     # Tài liệu bảo mật ứng dụng
├── README.md                           # Tài liệu mô tả dự án
└── .gitignore                          # File cấu hình Git ignore
```

### 📸 Thư mục Upload (Hình ảnh)
- **`/public/upload/`**: Chứa các hình ảnh được tải lên từ server (ảnh bài viết, ảnh sản phẩm)
- **`/public/uploads/`**: Chứa các hình ảnh được tải lên từ server (avatar, ảnh nội dung)
  > Cả hai thư mục đều được dùng để lưu trữ hình ảnh do người dùng hoặc Admin tải lên

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
