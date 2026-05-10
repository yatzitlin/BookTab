-- =========================================================
-- 0. Xoá database cũ và tạo mới
-- =========================================================
DROP DATABASE IF EXISTS BookTab;
CREATE DATABASE BookTab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE BookTab;
-- =========================================================
-- 1. Xoá bảng cũ
-- =========================================================
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS chi_tiet_don_hang, don_hang, chi_tiet_gio_hang, gio_hang, danh_gia, anh_san_pham, san_pham, loai_san_pham,
                     cau_tra_loi, cau_hoi, loai_cau_hoi, binh_luan, bai_viet, loai_bai_viet, lien_he,
                     member, `rank`, administrator, nguoi_dung;
SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE CompanyContact (
    PhoneNumber VARCHAR(20),            -- Số điện thoại
    Address NVARCHAR(500),              -- Địa chỉ (hỗ trợ tiếng Việt có dấu)
    Email VARCHAR(255)                  -- Địa chỉ Email
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 2. Nhóm người dùng & liên hệ
-- =========================================================
CREATE TABLE nguoi_dung (
    userid BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    mat_khau VARCHAR(255) NOT NULL,
    ho_va_ten_dem VARCHAR(50) NOT NULL,
    ten VARCHAR(50) NOT NULL,
    so_dien_thoai VARCHAR(20),
    email VARCHAR(100) UNIQUE NOT NULL, -- Thêm email
    avatar_url VARCHAR(500),            -- Thêm avatar
    vai_tro ENUM('guest','member','admin') DEFAULT 'member',    -- Thêm vai trò
    trang_thai VARCHAR(50),
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE administrator (
    userid BIGINT PRIMARY KEY,
    FOREIGN KEY (userid) REFERENCES nguoi_dung(userid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `rank` (
    ten_rank VARCHAR(50) PRIMARY KEY,
    diem_toi_thieu INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE member (
    userid BIGINT PRIMARY KEY,
    diem_tich_luy INT DEFAULT 0,
    ten_rank VARCHAR(50),
    FOREIGN KEY (userid) REFERENCES nguoi_dung(userid) ON DELETE CASCADE,
    FOREIGN KEY (ten_rank) REFERENCES `rank`(ten_rank) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE lien_he (
    ma_lien_he BIGINT AUTO_INCREMENT PRIMARY KEY,
    ho_va_ten VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    noi_dung TEXT NOT NULL,
    trang_thai ENUM('unread','read','replied') DEFAULT 'unread',
    thoi_gian_tao DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 3. Nhóm bài viết
-- =========================================================
CREATE TABLE loai_bai_viet (
    ma_loai BIGINT AUTO_INCREMENT PRIMARY KEY,
    ten_loai VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    trang_thai VARCHAR(50),
    loai_cha BIGINT,
    FOREIGN KEY (loai_cha) REFERENCES loai_bai_viet(ma_loai) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE bai_viet (
    ma_bai_viet BIGINT AUTO_INCREMENT PRIMARY KEY,
    tieu_de VARCHAR(255) NOT NULL,
    noi_dung TEXT NOT NULL,
    thumbnail_url VARCHAR(500),
    trang_thai VARCHAR(50),
    ngay_dang DATETIME DEFAULT CURRENT_TIMESTAMP,
    slug VARCHAR(255) UNIQUE,
    ma_loai BIGINT,
    administrator_userid BIGINT NOT NULL,
    FOREIGN KEY (ma_loai) REFERENCES loai_bai_viet(ma_loai) ON DELETE SET NULL,
    FOREIGN KEY (administrator_userid) REFERENCES administrator(userid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE binh_luan (
    ma_binh_luan BIGINT AUTO_INCREMENT PRIMARY KEY,
    noi_dung TEXT NOT NULL,
    ngay_sau DATETIME DEFAULT CURRENT_TIMESTAMP,
    luot_thich INT DEFAULT 0,
    trang_thai VARCHAR(50),
    url_anh VARCHAR(500),
    ma_bai_viet BIGINT NOT NULL,
    userid BIGINT NOT NULL,
    binh_luan_cha BIGINT,
    FOREIGN KEY (ma_bai_viet) REFERENCES bai_viet(ma_bai_viet) ON DELETE CASCADE,
    FOREIGN KEY (userid) REFERENCES nguoi_dung(userid) ON DELETE CASCADE,
    FOREIGN KEY (binh_luan_cha) REFERENCES binh_luan(ma_binh_luan) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE loai_cau_hoi (
    ma_loai BIGINT AUTO_INCREMENT PRIMARY KEY,
    ten_loai VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cau_hoi (
    ma_cau_hoi BIGINT AUTO_INCREMENT PRIMARY KEY,
    ten_cau_hoi VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    ma_loai BIGINT,
    userid BIGINT NOT NULL,
    FOREIGN KEY (ma_loai) REFERENCES loai_cau_hoi(ma_loai) ON DELETE SET NULL,
    FOREIGN KEY (userid) REFERENCES administrator(userid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cau_tra_loi (
    ma_cau_tra_loi BIGINT AUTO_INCREMENT PRIMARY KEY,
    ma_cau_hoi BIGINT NOT NULL,
    administrator_userid BIGINT NOT NULL,
    noi_dung TEXT NOT NULL,
    ngay_dang DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_cau_hoi) REFERENCES cau_hoi(ma_cau_hoi) ON DELETE CASCADE,
    FOREIGN KEY (administrator_userid) REFERENCES administrator(userid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- 4. Nhóm sản phẩm
-- =========================================================

CREATE TABLE loai_san_pham (
    ma_loai BIGINT AUTO_INCREMENT PRIMARY KEY,
    ten_loai VARCHAR(255) NOT NULL,
    loai_cha BIGINT,
    FOREIGN KEY (loai_cha) REFERENCES loai_san_pham(ma_loai) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE san_pham (
    ma_san_pham BIGINT AUTO_INCREMENT PRIMARY KEY,
    ten_san_pham VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    gia_san_pham DECIMAL(15, 2) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    ma_loai BIGINT,
    FOREIGN KEY (ma_loai) REFERENCES loai_san_pham(ma_loai) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE anh_san_pham (
    ma_anh BIGINT AUTO_INCREMENT PRIMARY KEY,
    ma_san_pham BIGINT NOT NULL,
    url_anh VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    is_primary BOOLEAN DEFAULT FALSE,
    so_thu_tu INT DEFAULT 0,
    FOREIGN KEY (ma_san_pham) REFERENCES san_pham(ma_san_pham) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE danh_gia (
    member_userid BIGINT NOT NULL,
    ma_san_pham BIGINT NOT NULL,
    diem INT NOT NULL,
    noi_dung TEXT,
    PRIMARY KEY (member_userid, ma_san_pham),
    FOREIGN KEY (member_userid) REFERENCES member(userid) ON DELETE CASCADE,
    FOREIGN KEY (ma_san_pham) REFERENCES san_pham(ma_san_pham) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE gio_hang (
    ma_gio_hang BIGINT AUTO_INCREMENT PRIMARY KEY,
    member_userid BIGINT UNIQUE NOT NULL,
    FOREIGN KEY (member_userid) REFERENCES member(userid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE chi_tiet_gio_hang (
    ma_gio_hang BIGINT NOT NULL,
    ma_san_pham BIGINT NOT NULL,
    so_luong INT NOT NULL DEFAULT 1,
    PRIMARY KEY (ma_gio_hang, ma_san_pham),
    FOREIGN KEY (ma_gio_hang) REFERENCES gio_hang(ma_gio_hang) ON DELETE CASCADE,
    FOREIGN KEY (ma_san_pham) REFERENCES san_pham(ma_san_pham) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE don_hang (
    ma_don BIGINT AUTO_INCREMENT PRIMARY KEY,
    member_userid BIGINT NOT NULL,
    ngay_dat DATETIME DEFAULT CURRENT_TIMESTAMP,
    thoi_gian_nhan_hang_du_kien DATETIME,
    thoi_gian_nhan_hang_thuc_te DATETIME,
    phuong_thuc_thanh_toan VARCHAR(50),
    tong_tien DECIMAL(15, 2) NOT NULL,
    dia_chi_giao_hang TEXT NOT NULL,
    trang_thai_don_hang VARCHAR(50),
    so_luong_san_pham INT NOT NULL,
    trang_thai_giao_dich VARCHAR(50),
    FOREIGN KEY (member_userid) REFERENCES member(userid) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE chi_tiet_don_hang (
    ma_don BIGINT NOT NULL,
    ma_san_pham BIGINT NOT NULL,
    so_luong INT NOT NULL,
    gia DECIMAL(15, 2) NOT NULL,
    PRIMARY KEY (ma_don, ma_san_pham),
    FOREIGN KEY (ma_don) REFERENCES don_hang(ma_don) ON DELETE CASCADE,
    FOREIGN KEY (ma_san_pham) REFERENCES san_pham(ma_san_pham) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;