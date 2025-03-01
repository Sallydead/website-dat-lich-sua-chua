-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 01, 2025 at 01:57 PM
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
  `ma_don` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `dia_chi` text NOT NULL,
  `mo_ta` text NOT NULL,
  `media` varchar(255) DEFAULT NULL,
  `thoi_gian_hen` datetime DEFAULT NULL,
  `nguoi_xu_ly` int DEFAULT NULL,
  `trang_thai` tinyint DEFAULT '0',
  `ghi_chu` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `chi_phi` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `yeu_cau_sua_chua`
--

INSERT INTO `yeu_cau_sua_chua` (`id`, `ma_don`, `user_id`, `ho_ten`, `so_dien_thoai`, `dia_chi`, `mo_ta`, `media`, `thoi_gian_hen`, `nguoi_xu_ly`, `trang_thai`, `ghi_chu`, `created_at`, `update_at`, `chi_phi`) VALUES
(1, 'SC2500001', 2, 'test', '0888889530', 'Tổ dân phố 5, Hoà Thuận, Quảng Hoà, Cao Bằng', 'scsacasc', NULL, NULL, NULL, 0, NULL, '2025-02-26 17:48:32', '2025-03-01 13:49:34', NULL),
(2, 'SC2500002', 2, '122312312', '0888889530', 'Tổ dân phố 5, Hoà Thuận, Quảng Hoà, Cao Bằng', 'test', '[\"khachhang1-20250226192908-0.png\",\"khachhang1-20250226192908-1.png\"]', '2025-02-27 02:33:00', NULL, 1, NULL, '2025-02-26 19:29:08', '2025-03-01 13:49:34', NULL),
(3, 'SC2500003', 2, 'test', '0888889530', 'Tổ dân phố 5, Hoà Thuận, Quảng Hoà, Cao Bằng', 'testgtt', '[\"khachhang1-20250226193401-0.mp4\"]', NULL, NULL, 2, NULL, '2025-02-26 19:34:01', '2025-03-01 13:49:34', NULL),
(4, 'SC2500004', 2, 'test', '0888889530', 'Tổ dân phố 5, Hoà Thuận, Quảng Hoà, Cao Bằng', 'abc', NULL, NULL, NULL, 3, NULL, '2025-02-26 19:50:16', '2025-03-01 13:49:34', NULL),
(5, 'SC2500005', 2, 'test', '0888889530', 'Tổ dân phố 5, Hoà Thuận, Quảng Hoà, Cao Bằng', 'xxx', NULL, NULL, NULL, 4, NULL, '2025-02-26 19:50:23', '2025-03-01 13:49:34', NULL),
(6, 'YC270225001365', 1, 'Đàm Minh Giang', '0333332444', 'Tổ dân phố 5, Hoà Thuận, Quảng Hoà, Cao Bằng', '1324e43346', NULL, NULL, 1, 3, 'test', '2025-02-28 20:42:27', '2025-03-01 13:49:34', '500000');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
