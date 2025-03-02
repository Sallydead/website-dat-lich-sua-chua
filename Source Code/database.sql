-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 01, 2025 at 02:56 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `datlich`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` tinyint NOT NULL DEFAULT '0' COMMENT 'Chức vụ (0 khách hàng, 1 admin, 2 cskh, 3 ktv)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_infos`
--

CREATE TABLE `user_infos` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_vietnamese_ci,
  `gender` enum('nam','nu') COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `yeu_cau_sua_chua`
--

CREATE TABLE `yeu_cau_sua_chua` (
  `id` int NOT NULL,
  `ma_don` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `ho_ten` varchar(100) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `so_dien_thoai` varchar(20) COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `dia_chi` text COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `media` varchar(255) COLLATE utf8mb4_vietnamese_ci DEFAULT NULL,
  `thoi_gian_hen` datetime DEFAULT NULL,
  `nguoi_xu_ly` int DEFAULT NULL,
  `trang_thai` tinyint DEFAULT '0',
  `ghi_chu` text COLLATE utf8mb4_vietnamese_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `chi_phi` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Triggers `yeu_cau_sua_chua`
--
DELIMITER $$
CREATE TRIGGER `before_yeu_cau_insert` BEFORE INSERT ON `yeu_cau_sua_chua` FOR EACH ROW BEGIN
    DECLARE prefix VARCHAR(2);
    DECLARE date_part CHAR(6);
    DECLARE sequence INT;
    DECLARE random_part INT;
    
    SET prefix = 'YC';
    SET date_part = DATE_FORMAT(CURRENT_DATE, '%d%m%y');

    -- Lấy số thứ tự trong ngày, giới hạn 999, nếu quá thì reset về 1
    SELECT IFNULL(MAX(CAST(SUBSTRING(ma_don, 9, 3) AS UNSIGNED)), 0) + 1
    INTO sequence
    FROM yeu_cau_sua_chua
    WHERE ma_don LIKE CONCAT(prefix, date_part, '%');

    IF sequence > 999 THEN
        SET sequence = 1;
    END IF;

    -- Sinh số ngẫu nhiên từ 100-999
    SET random_part = FLOOR(RAND() * 900) + 100;

    -- Tạo mã YC + ddMMyy + 3 số thứ tự + 3 số ngẫu nhiên
    SET NEW.ma_don = CONCAT(
        prefix,
        date_part,
        LPAD(sequence, 3, '0'),
        random_part
    );
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_infos`
--
ALTER TABLE `user_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `yeu_cau_sua_chua`
--
ALTER TABLE `yeu_cau_sua_chua`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_don` (`ma_don`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `nguoi_xu_ly` (`nguoi_xu_ly`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_infos`
--
ALTER TABLE `user_infos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yeu_cau_sua_chua`
--
ALTER TABLE `yeu_cau_sua_chua`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_infos`
--
ALTER TABLE `user_infos`
  ADD CONSTRAINT `user_infos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `yeu_cau_sua_chua`
--
ALTER TABLE `yeu_cau_sua_chua`
  ADD CONSTRAINT `yeu_cau_sua_chua_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `yeu_cau_sua_chua_ibfk_2` FOREIGN KEY (`nguoi_xu_ly`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
