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
BookTab/
├── app/                                # Mã nguồn chính của ứng dụng
│   ├── controllers/                    # Các Controller xử lý logic
│   │   ├── AuthController.php          # Xử lý đăng ký, đăng nhập, đăng xuất
│   │   ├── AdminController.php         # Trang chủ Admin
│   │   ├── AdminNewsController.php     # Quản lý tin tức (CRUD)
│   │   ├── AdminUserController.php     # Quản lý người dùng (CRUD, phân quyền)
│   │   ├── AdminCommentController.php  # Quản lý bình luận
│   │   ├── NewsController.php          # Hiển thị tin tức, bình luận
│   │   ├── HomeController.php          # Trang chủ website
│   │   ├── ProductController.php       # Quản lý sản phẩm
│   │   ├── CartController.php          # Giỏ hàng
│   │   ├── UserController.php          # Thông tin cá nhân người dùng
│   │   ├── AboutController.php         # Trang Giới thiệu
│   │   ├── ContactController.php       # Trang Liên hệ
│   │   ├── QnAController.php           # Quản lý Hỏi & Đáp
│   │   ├── CompanyContact.php          # Thông tin công ty
│   │   └── BaseController.php          # Lớp cha cho tất cả Controller
│   │
│   ├── models/                         # Các Model (tương tác với CSDL)
│   │   ├── UserModel.php               # Quản lý người dùng (đăng ký, đăng nhập, profile)
│   │   ├── NewsModel.php               # Quản lý tin tức/bài viết
│   │   ├── CommentModel.php            # Quản lý bình luận (nested comments)
│   │   ├── ProductModel.php            # Quản lý sản phẩm
│   │   ├── CartModel.php               # Quản lý giỏ hàng
│   │   ├── OrderModel.php              # Quản lý đơn hàng
│   │   ├── CategoryModel.php           # Danh mục sản phẩm
│   │   ├── NewsCategoryModel.php       # Danh mục tin tức
│   │   ├── QnAModel.php                # Hỏi & Đáp
│   │   ├── ContactModel.php            # Liên hệ khách hàng
│   │   ├── CompanyContactModel.php     # Thông tin công ty
│   │   └── ThongTinModel.php           # Thông tin tĩnh trên website
│   │
│   ├── views/                          # Các view (giao diện)
│   │   ├── template.php                # Template chung
│   │   ├── components/                 # Các component tái sử dụng
│   │   │   ├── Header.php              # Thanh header
│   │   │   └── Footer.php              # Thanh footer
│   │   ├── pages/                      # Các trang công khai (Client)
│   │   │   ├── Home.php                # Trang chủ
│   │   │   ├── News.php                # Trang danh sách tin tức
│   │   │   ├── NewsDetail.php          # Trang chi tiết bài viết (+ bình luận)
│   │   │   ├── NewsList.php            # Danh sách tin tức (hiển thị khác)
│   │   │   ├── Products.php            # Danh sách sản phẩm
│   │   │   ├── ProductDetail.php       # Chi tiết sản phẩm
│   │   │   ├── Cart.php                # Giỏ hàng
│   │   │   ├── Checkout.php            # Thanh toán
│   │   │   ├── My.php                  # Thông tin cá nhân người dùng
│   │   │   ├── Contact.php             # Trang liên hệ
│   │   │   ├── QnA.php                 # Hỏi & Đáp
│   │   │   ├── About.php               # Giới thiệu
│   │   │   ├── Ask.php                 # Đặt câu hỏi
│   │   │   ├── FAQ.php                 # Câu hỏi thường gặp
│   │   │   ├── 404.php                 # Trang lỗi 404
│   │   │   └── auth/                   # Trang xác thực
│   │   │       ├── login.php           # Đăng nhập
│   │   │       └── register.php        # Đăng ký
│   │   │
│   │   └── admin/                      # Giao diện Admin
│   │       ├── adminLayout.php         # Layout chủ của Admin
│   │       ├── adminComponents/        # Component tái sử dụng của Admin
│   │       │   ├── AdminHeader.php     # Header Admin
│   │       │   ├── AdminSidebar.php    # Menu Sidebar
│   │       │   ├── AdminCreateNews.php # Form tạo/sửa tin tức (TinyMCE)
│   │       │   └── ... (các form khác)
│   │       └── adminPages/             # Các trang Admin
│   │           ├── AdminDashboard.php  # Dashboard
│   │           ├── AdminNews.php       # Quản lý tin tức
│   │           ├── AdminUserManage.php # Quản lý người dùng (CRUD + vai trò)
│   │           ├── AdminComment.php    # Quản lý bình luận
│   │           ├── AdminProducts.php   # Quản lý sản phẩm
│   │           ├── AdminQnA.php        # Quản lý Hỏi & Đáp
│   │           ├── AdminContact.php    # Quản lý liên hệ
│   │           ├── AdminAbout.php      # Quản lý Giới thiệu
│   │           ├── AdminHome.php       # Quản lý Trang chủ
│   │           └── AdminInfo.php       # Quản lý Thông tin công ty
│   │
│   └── core/                         # Lõi xử lý ứng dụng
│       └── Database.php              # Lớp kết nối và xử lý CSDL (PDO)
│
├── public/                           # Thư mục công khai trên web
│   ├── index.php                     # Front Controller (Router chính)
│   ├── .htaccess                     # Cấu hình Apache (URL rewriting)
│   ├── upload/                       # Thư mục chứa hình ảnh tải lên từ server
│   │                                 #    (ảnh bài viết, ảnh sản phẩm)
│   ├── uploads/                      # Thư mục chứa hình ảnh tải lên từ server
│   │                                 #    (avatar người dùng)
│   └── admin_assets/                 # Assets cho Admin (Bootstrap, CSS, JS, icons)
│       ├── css/
│       ├── js/
│       ├── images/
│       └── fonts/
│
├── sql/                              # Thư mục chứa các file sql
│   ├── GenerateTable.sql             # Script khởi tạo cơ sở dữ liệu
│   ├── booktab.sql                   # Data của website
│  
├── WEB_SECURITY.md                   # Tài liệu bảo mật ứng dụng
├── COOKIES_SESSION.md                # Tài liệu về cookie và session
├── README.md                         # File README.md
└── .gitignore                        # File cấu hình Git ignore
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