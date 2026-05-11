-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 06:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booktab`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `userid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`userid`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `anh_san_pham`
--

CREATE TABLE `anh_san_pham` (
  `ma_anh` bigint(20) NOT NULL,
  `ma_san_pham` bigint(20) NOT NULL,
  `url_anh` varchar(500) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `so_thu_tu` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bai_viet`
--

CREATE TABLE `bai_viet` (
  `ma_bai_viet` bigint(20) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `noi_dung` text NOT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT NULL,
  `ngay_dang` datetime DEFAULT current_timestamp(),
  `slug` varchar(255) DEFAULT NULL,
  `ma_loai` bigint(20) DEFAULT NULL,
  `administrator_userid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `binh_luan`
--

CREATE TABLE `binh_luan` (
  `ma_binh_luan` bigint(20) NOT NULL,
  `noi_dung` text NOT NULL,
  `ngay_sau` datetime DEFAULT current_timestamp(),
  `luot_thich` int(11) DEFAULT 0,
  `trang_thai` varchar(50) DEFAULT NULL,
  `url_anh` varchar(500) DEFAULT NULL,
  `ma_bai_viet` bigint(20) NOT NULL,
  `userid` bigint(20) NOT NULL,
  `binh_luan_cha` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cau_hoi`
--

CREATE TABLE `cau_hoi` (
  `ma_cau_hoi` bigint(20) NOT NULL,
  `ten_cau_hoi` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `ma_loai` bigint(20) DEFAULT NULL,
  `userid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cau_tra_loi`
--

CREATE TABLE `cau_tra_loi` (
  `ma_cau_tra_loi` bigint(20) NOT NULL,
  `ma_cau_hoi` bigint(20) NOT NULL,
  `administrator_userid` bigint(20) NOT NULL,
  `noi_dung` text NOT NULL,
  `ngay_dang` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `ma_don` bigint(20) NOT NULL,
  `ma_san_pham` bigint(20) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `gia` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_gio_hang`
--

CREATE TABLE `chi_tiet_gio_hang` (
  `ma_gio_hang` bigint(20) NOT NULL,
  `ma_san_pham` bigint(20) NOT NULL,
  `so_luong` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companycontact`
--

CREATE TABLE `companycontact` (
  `PhoneNumber` varchar(20) DEFAULT NULL,
  `Address` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companycontact`
--

INSERT INTO `companycontact` (`PhoneNumber`, `Address`, `Email`) VALUES
('+84 (123) 456-789', 'Trường Đại học Bách khoa - ĐHQG-HCM, cơ sở Dĩ An, Bình Dương', 'support@BookTab.com');

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `member_userid` bigint(20) NOT NULL,
  `ma_san_pham` bigint(20) NOT NULL,
  `diem` int(11) NOT NULL,
  `noi_dung` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `ma_don` bigint(20) NOT NULL,
  `member_userid` bigint(20) NOT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `thoi_gian_nhan_hang_du_kien` datetime DEFAULT NULL,
  `thoi_gian_nhan_hang_thuc_te` datetime DEFAULT NULL,
  `phuong_thuc_thanh_toan` varchar(50) DEFAULT NULL,
  `tong_tien` decimal(15,2) NOT NULL,
  `dia_chi_giao_hang` text NOT NULL,
  `trang_thai_don_hang` varchar(50) DEFAULT NULL,
  `so_luong_san_pham` int(11) NOT NULL,
  `trang_thai_giao_dich` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gio_hang`
--

CREATE TABLE `gio_hang` (
  `ma_gio_hang` bigint(20) NOT NULL,
  `member_userid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lien_he`
--

CREATE TABLE `lien_he` (
  `ma_lien_he` bigint(20) NOT NULL,
  `ho_va_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `noi_dung` text NOT NULL,
  `trang_thai` enum('unread','read','replied') DEFAULT 'unread',
  `thoi_gian_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lien_he`
--

INSERT INTO `lien_he` (`ma_lien_he`, `ho_va_ten`, `email`, `noi_dung`, `trang_thai`, `thoi_gian_tao`) VALUES
(1, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'to thich cau', 'unread', '2026-05-07 22:24:57'),
(2, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'to thich cau', 'unread', '2026-05-07 22:25:07'),
(3, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'to thich cau', 'unread', '2026-05-07 22:25:48'),
(4, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'sach dep', 'unread', '2026-05-08 07:21:43'),
(5, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'sach dep', 'unread', '2026-05-08 07:23:19'),
(6, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'f', 'unread', '2026-05-08 07:23:36'),
(7, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'huy', 'unread', '2026-05-08 07:29:46'),
(8, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'huy', 'unread', '2026-05-08 07:30:12'),
(9, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 't', 'unread', '2026-05-08 07:31:01'),
(10, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 't', 'replied', '2026-05-08 07:34:22'),
(11, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'tim', 'unread', '2026-05-08 07:36:49'),
(12, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'h', 'unread', '2026-05-08 07:39:53'),
(13, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'h', 'unread', '2026-05-08 07:52:52'),
(14, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'h', 'unread', '2026-05-08 07:54:13'),
(15, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'g', 'unread', '2026-05-08 07:54:24'),
(16, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'g', 'unread', '2026-05-08 07:55:48'),
(17, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'h', 'unread', '2026-05-08 07:57:28'),
(18, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'h', 'unread', '2026-05-08 07:58:07'),
(19, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'ádsdfa', 'unread', '2026-05-08 08:02:06'),
(20, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'ádsdfa', 'replied', '2026-05-08 08:07:15'),
(22, 'Huỳnh Đức Huy', 'huynhduchuy10032005@gmail.com', 'huy', 'unread', '2026-05-10 16:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `loai_bai_viet`
--

CREATE TABLE `loai_bai_viet` (
  `ma_loai` bigint(20) NOT NULL,
  `ten_loai` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT NULL,
  `loai_cha` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loai_cau_hoi`
--

CREATE TABLE `loai_cau_hoi` (
  `ma_loai` bigint(20) NOT NULL,
  `ten_loai` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loai_san_pham`
--

CREATE TABLE `loai_san_pham` (
  `ma_loai` bigint(20) NOT NULL,
  `ten_loai` varchar(255) NOT NULL,
  `loai_cha` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `userid` bigint(20) NOT NULL,
  `diem_tich_luy` int(11) DEFAULT 0,
  `ten_rank` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`userid`, `diem_tich_luy`, `ten_rank`) VALUES
(2, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `userid` bigint(20) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `ho_va_ten_dem` varchar(50) NOT NULL,
  `ten` varchar(50) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `avatar_url` varchar(500) DEFAULT NULL,
  `vai_tro` enum('guest','member','admin') DEFAULT 'member',
  `trang_thai` varchar(50) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`userid`, `username`, `mat_khau`, `ho_va_ten_dem`, `ten`, `so_dien_thoai`, `email`, `avatar_url`, `vai_tro`, `trang_thai`, `ngay_tao`) VALUES
(1, 'admin', '$2a$12$UHRC2flycgjyh/bpatCJDO14bC/ap99VXEFV/WAXmXuCRbFlIDHyG', 'Quản Trị', 'Viên', '0123456789', 'admin@example.com', NULL, 'member', 'active', '2026-05-05 20:23:28'),
(2, 'user1', '$2y$10$UX5ZV4.g6Ayx5rn0pWoRI.ya575kadXDrMSf9ULjbIr/dGvnLgv6a', 'Huỳnh Đức', 'Khoa', '01222222222', 'user1@example.com', '6a009d26affd0_Cartethyia.jpeg', 'member', 'active', '2026-05-05 20:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `ten_rank` varchar(50) NOT NULL,
  `diem_toi_thieu` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `ma_san_pham` bigint(20) NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia_san_pham` decimal(15,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `ma_loai` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) DEFAULT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'site_name', 'ABC Store'),
(2, 'hotline', '0909999999'),
(3, 'address', 'Ben Tre'),
(4, 'about', 'Giới thiệu công ty'),
(5, 'logo', 'logo.png'),
(6, 'banner', 'banner.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `anh_san_pham`
--
ALTER TABLE `anh_san_pham`
  ADD PRIMARY KEY (`ma_anh`),
  ADD KEY `ma_san_pham` (`ma_san_pham`);

--
-- Indexes for table `bai_viet`
--
ALTER TABLE `bai_viet`
  ADD PRIMARY KEY (`ma_bai_viet`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `ma_loai` (`ma_loai`),
  ADD KEY `administrator_userid` (`administrator_userid`);

--
-- Indexes for table `binh_luan`
--
ALTER TABLE `binh_luan`
  ADD PRIMARY KEY (`ma_binh_luan`),
  ADD KEY `ma_bai_viet` (`ma_bai_viet`),
  ADD KEY `userid` (`userid`),
  ADD KEY `binh_luan_cha` (`binh_luan_cha`);

--
-- Indexes for table `cau_hoi`
--
ALTER TABLE `cau_hoi`
  ADD PRIMARY KEY (`ma_cau_hoi`),
  ADD KEY `ma_loai` (`ma_loai`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `cau_tra_loi`
--
ALTER TABLE `cau_tra_loi`
  ADD PRIMARY KEY (`ma_cau_tra_loi`),
  ADD KEY `ma_cau_hoi` (`ma_cau_hoi`),
  ADD KEY `administrator_userid` (`administrator_userid`);

--
-- Indexes for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`ma_don`,`ma_san_pham`),
  ADD KEY `ma_san_pham` (`ma_san_pham`);

--
-- Indexes for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD PRIMARY KEY (`ma_gio_hang`,`ma_san_pham`),
  ADD KEY `ma_san_pham` (`ma_san_pham`);

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`member_userid`,`ma_san_pham`),
  ADD KEY `ma_san_pham` (`ma_san_pham`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`ma_don`),
  ADD KEY `member_userid` (`member_userid`);

--
-- Indexes for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`ma_gio_hang`),
  ADD UNIQUE KEY `member_userid` (`member_userid`);

--
-- Indexes for table `lien_he`
--
ALTER TABLE `lien_he`
  ADD PRIMARY KEY (`ma_lien_he`);

--
-- Indexes for table `loai_bai_viet`
--
ALTER TABLE `loai_bai_viet`
  ADD PRIMARY KEY (`ma_loai`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `loai_cha` (`loai_cha`);

--
-- Indexes for table `loai_cau_hoi`
--
ALTER TABLE `loai_cau_hoi`
  ADD PRIMARY KEY (`ma_loai`);

--
-- Indexes for table `loai_san_pham`
--
ALTER TABLE `loai_san_pham`
  ADD PRIMARY KEY (`ma_loai`),
  ADD KEY `loai_cha` (`loai_cha`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `ten_rank` (`ten_rank`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`ten_rank`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`ma_san_pham`),
  ADD KEY `ma_loai` (`ma_loai`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anh_san_pham`
--
ALTER TABLE `anh_san_pham`
  MODIFY `ma_anh` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bai_viet`
--
ALTER TABLE `bai_viet`
  MODIFY `ma_bai_viet` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `binh_luan`
--
ALTER TABLE `binh_luan`
  MODIFY `ma_binh_luan` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cau_hoi`
--
ALTER TABLE `cau_hoi`
  MODIFY `ma_cau_hoi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cau_tra_loi`
--
ALTER TABLE `cau_tra_loi`
  MODIFY `ma_cau_tra_loi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `ma_don` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `ma_gio_hang` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lien_he`
--
ALTER TABLE `lien_he`
  MODIFY `ma_lien_he` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `loai_bai_viet`
--
ALTER TABLE `loai_bai_viet`
  MODIFY `ma_loai` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loai_cau_hoi`
--
ALTER TABLE `loai_cau_hoi`
  MODIFY `ma_loai` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loai_san_pham`
--
ALTER TABLE `loai_san_pham`
  MODIFY `ma_loai` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `userid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `ma_san_pham` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrator`
--
ALTER TABLE `administrator`
  ADD CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `nguoi_dung` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `anh_san_pham`
--
ALTER TABLE `anh_san_pham`
  ADD CONSTRAINT `anh_san_pham_ibfk_1` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE;

--
-- Constraints for table `bai_viet`
--
ALTER TABLE `bai_viet`
  ADD CONSTRAINT `bai_viet_ibfk_1` FOREIGN KEY (`ma_loai`) REFERENCES `loai_bai_viet` (`ma_loai`) ON DELETE SET NULL,
  ADD CONSTRAINT `bai_viet_ibfk_2` FOREIGN KEY (`administrator_userid`) REFERENCES `administrator` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `binh_luan`
--
ALTER TABLE `binh_luan`
  ADD CONSTRAINT `binh_luan_ibfk_1` FOREIGN KEY (`ma_bai_viet`) REFERENCES `bai_viet` (`ma_bai_viet`) ON DELETE CASCADE,
  ADD CONSTRAINT `binh_luan_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `nguoi_dung` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `binh_luan_ibfk_3` FOREIGN KEY (`binh_luan_cha`) REFERENCES `binh_luan` (`ma_binh_luan`) ON DELETE CASCADE;

--
-- Constraints for table `cau_hoi`
--
ALTER TABLE `cau_hoi`
  ADD CONSTRAINT `cau_hoi_ibfk_1` FOREIGN KEY (`ma_loai`) REFERENCES `loai_cau_hoi` (`ma_loai`) ON DELETE SET NULL,
  ADD CONSTRAINT `cau_hoi_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `administrator` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `cau_tra_loi`
--
ALTER TABLE `cau_tra_loi`
  ADD CONSTRAINT `cau_tra_loi_ibfk_1` FOREIGN KEY (`ma_cau_hoi`) REFERENCES `cau_hoi` (`ma_cau_hoi`) ON DELETE CASCADE,
  ADD CONSTRAINT `cau_tra_loi_ibfk_2` FOREIGN KEY (`administrator_userid`) REFERENCES `administrator` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`ma_don`) REFERENCES `don_hang` (`ma_don`) ON DELETE CASCADE,
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`);

--
-- Constraints for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD CONSTRAINT `chi_tiet_gio_hang_ibfk_1` FOREIGN KEY (`ma_gio_hang`) REFERENCES `gio_hang` (`ma_gio_hang`) ON DELETE CASCADE,
  ADD CONSTRAINT `chi_tiet_gio_hang_ibfk_2` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE;

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`member_userid`) REFERENCES `member` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`ma_san_pham`) REFERENCES `san_pham` (`ma_san_pham`) ON DELETE CASCADE;

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`member_userid`) REFERENCES `member` (`userid`);

--
-- Constraints for table `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `gio_hang_ibfk_1` FOREIGN KEY (`member_userid`) REFERENCES `member` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `loai_bai_viet`
--
ALTER TABLE `loai_bai_viet`
  ADD CONSTRAINT `loai_bai_viet_ibfk_1` FOREIGN KEY (`loai_cha`) REFERENCES `loai_bai_viet` (`ma_loai`) ON DELETE SET NULL;

--
-- Constraints for table `loai_san_pham`
--
ALTER TABLE `loai_san_pham`
  ADD CONSTRAINT `loai_san_pham_ibfk_1` FOREIGN KEY (`loai_cha`) REFERENCES `loai_san_pham` (`ma_loai`) ON DELETE SET NULL;

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `nguoi_dung` (`userid`) ON DELETE CASCADE,
  ADD CONSTRAINT `member_ibfk_2` FOREIGN KEY (`ten_rank`) REFERENCES `rank` (`ten_rank`) ON DELETE SET NULL;

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`ma_loai`) REFERENCES `loai_san_pham` (`ma_loai`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
