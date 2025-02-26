-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 26, 2025 at 05:51 PM
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
-- Table structure for table `yeu_cau_sua_chua`
--

CREATE TABLE `yeu_cau_sua_chua` (
  `id` int NOT NULL,
  `ma_don` varchar(10) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `dia_chi` text NOT NULL,
  `mo_ta` text NOT NULL,
  `thoi_gian_hen` datetime DEFAULT NULL,
  `nguoi_xu_ly` int DEFAULT NULL,
  `trang_thai` tinyint DEFAULT '0',
  `ghi_chu` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `yeu_cau_sua_chua`
--

INSERT INTO `yeu_cau_sua_chua` (`id`, `ma_don`, `user_id`, `ho_ten`, `so_dien_thoai`, `dia_chi`, `mo_ta`, `thoi_gian_hen`, `nguoi_xu_ly`, `trang_thai`, `ghi_chu`, `created_at`) VALUES
(1, 'SC2500001', 2, 'test', '0888889530', 'Tổ dân phố 5, Hoà Thuận, Quảng Hoà, Cao Bằng', 'scsacasc', NULL, NULL, 0, NULL, '2025-02-26 17:48:32');

--
-- Triggers `yeu_cau_sua_chua`
--
DELIMITER $$
CREATE TRIGGER `before_yeu_cau_insert` BEFORE INSERT ON `yeu_cau_sua_chua` FOR EACH ROW BEGIN
    DECLARE prefix VARCHAR(4);
    DECLARE year_suffix CHAR(2);
    DECLARE sequence INT;
    
    -- Tạo prefix SC + năm hiện tại (2 số cuối)
    SET prefix = 'SC';
    SET year_suffix = RIGHT(YEAR(CURRENT_DATE), 2);
    
    -- Lấy số sequence cuối cùng trong ngày
    SELECT IFNULL(MAX(CAST(RIGHT(ma_don, 5) AS UNSIGNED)), 0) + 1
    INTO sequence
    FROM yeu_cau_sua_chua
    WHERE ma_don LIKE CONCAT(prefix, year_suffix, '%');
    
    -- Tạo mã đơn mới: SCyy + số sequence 5 chữ số
    SET NEW.ma_don = CONCAT(
        prefix,
        year_suffix,
        LPAD(sequence, 5, '0')
    );
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `yeu_cau_sua_chua`
--
ALTER TABLE `yeu_cau_sua_chua`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

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
