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
SET FOREIGN_KEY_CHECKS = 0;

--
-- Dumping data for table `companycontact`
--

INSERT INTO `companycontact` (`PhoneNumber`, `Address`, `Email`) VALUES
('+84 (123) 456-789', 'Trường Đại học Bách khoa - ĐHQG-HCM, cơ sở Dĩ An, Bình Dương', 'support@BookTab.com');

-- --------------------------------------------------------

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

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;