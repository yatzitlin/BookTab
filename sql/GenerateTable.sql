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
                     cau_tra_loi, cau_hoi, loai_cau_hoi, binh_luan, bai_viet, loai_bai_viet, thong_tin, lien_he,
                     member, `rank`, administrator, nguoi_dung, anh_cau_hoi, anh;
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

-- Bảng Loại bài viết (Danh mục)
CREATE TABLE loai_bai_viet (
    ma_loai BIGINT AUTO_INCREMENT PRIMARY KEY,
    ten_loai VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    trang_thai VARCHAR(50) DEFAULT 'active',
    loai_cha BIGINT,
    FOREIGN KEY (loai_cha) REFERENCES loai_bai_viet(ma_loai) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng Bài viết
CREATE TABLE bai_viet (
    ma_bai_viet BIGINT AUTO_INCREMENT PRIMARY KEY,
    tieu_de VARCHAR(255) NOT NULL,
    tom_tat TEXT,
    noi_dung TEXT NOT NULL,
    thumbnail_url VARCHAR(500),
    luot_xem INT DEFAULT 0,
    trang_thai VARCHAR(50) DEFAULT 'ban_nhap',
    ngay_dang DATETIME DEFAULT CURRENT_TIMESTAMP,
    ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    slug VARCHAR(255) UNIQUE,
    ma_loai BIGINT,
    administrator_userid BIGINT NOT NULL,
    FOREIGN KEY (ma_loai) REFERENCES loai_bai_viet(ma_loai) ON DELETE SET NULL,
    FOREIGN KEY (administrator_userid) REFERENCES administrator(userid) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE thong_tin (
    ma_thong_tin BIGINT AUTO_INCREMENT PRIMARY KEY,
    loai_thong_tin VARCHAR(50) NOT NULL,
    type ENUM('text','link') NOT NULL DEFAULT 'text',
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    ngay_cap_nhat DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE thong_tin_chi_tiet (
    ma_chi_tiet BIGINT AUTO_INCREMENT PRIMARY KEY,
    ma_thong_tin BIGINT NOT NULL,
    noi_dung TEXT NOT NULL,
    url VARCHAR(500) DEFAULT NULL,
    FOREIGN KEY (ma_thong_tin) REFERENCES thong_tin(ma_thong_tin) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng Bình luận
CREATE TABLE binh_luan (
    ma_binh_luan BIGINT AUTO_INCREMENT PRIMARY KEY,
    noi_dung TEXT NOT NULL,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    luot_thich INT DEFAULT 0,
    trang_thai VARCHAR(50) DEFAULT 'ban_nhap',
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
    ten_loai VARCHAR(255) NOT NULL UNIQUE,
    so_thu_tu INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cau_hoi (
    ma_cau_hoi BIGINT AUTO_INCREMENT PRIMARY KEY,
    ten_cau_hoi VARCHAR(255) NOT NULL,
    trang_thai ENUM('cho_duyet','chua_tra_loi','da_tra_loi','da_an') NOT NULL DEFAULT 'chua_tra_loi',
    is_faq ENUM('Yes','No') NOT NULL DEFAULT 'No',
    ma_loai BIGINT,
    userid BIGINT NOT NULL,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_loai) REFERENCES loai_cau_hoi(ma_loai) ON DELETE SET NULL,
    FOREIGN KEY (userid) REFERENCES nguoi_dung(userid) ON DELETE CASCADE
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

DELIMITER $$
CREATE TRIGGER trg_after_insert_cau_tra_loi
AFTER INSERT ON cau_tra_loi
FOR EACH ROW
BEGIN
    UPDATE cau_hoi SET trang_thai = 'da_tra_loi'
    WHERE ma_cau_hoi = NEW.ma_cau_hoi AND trang_thai NOT IN ('da_an');
END$$

CREATE TRIGGER trg_after_delete_cau_tra_loi
AFTER DELETE ON cau_tra_loi
FOR EACH ROW
BEGIN
    IF NOT EXISTS (SELECT 1 FROM cau_tra_loi WHERE ma_cau_hoi = OLD.ma_cau_hoi) THEN
        UPDATE cau_hoi SET trang_thai = 'da_an'
        WHERE ma_cau_hoi = OLD.ma_cau_hoi
          AND trang_thai IN ('da_tra_loi', 'chua_tra_loi')
          AND is_faq = 'Yes';
        UPDATE cau_hoi SET trang_thai = 'chua_tra_loi'
        WHERE ma_cau_hoi = OLD.ma_cau_hoi
          AND trang_thai = 'da_tra_loi'
          AND is_faq = 'No';
    END IF;
END$$
DELIMITER ;

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

CREATE TABLE `anh` (
    `ma_anh` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `url_anh` VARCHAR(500) NOT NULL,
    `ten_file` VARCHAR(255) DEFAULT NULL,
    `ngay_tao` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `anh_cau_hoi` (
    `ma_cau_hoi` BIGINT NOT NULL,
    `ma_anh` BIGINT NOT NULL,
    `so_thu_tu` INT DEFAULT 0,
    PRIMARY KEY (`ma_cau_hoi`, `ma_anh`),
    KEY `idx_acq_ma_anh` (`ma_anh`),
    CONSTRAINT `fk_anh_cau_hoi_cau_hoi` FOREIGN KEY (`ma_cau_hoi`) REFERENCES `cau_hoi`(`ma_cau_hoi`) ON DELETE CASCADE,
    CONSTRAINT `fk_anh_cau_hoi_anh` FOREIGN KEY (`ma_anh`) REFERENCES `anh`(`ma_anh`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;