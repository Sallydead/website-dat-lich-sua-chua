-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 17, 2025 at 09:51 PM
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
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` tinyint NOT NULL DEFAULT '0' COMMENT 'Chức vụ (0 khách hàng, 1 admin, 2 cskh, 3 ktv)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `update_at`, `role`) VALUES
(1, 'vuchilinh', '$2y$10$8sg4hh2xeF0RLLAsU8Lx3.9dqWoSDRlTDgKCgs9IIvQphRAhbCl7y', 'admin@vuchilinh.id.vn', '2025-03-17 15:37:45', '2025-03-17 15:37:58', 1),
(2, 'cskh1', '$2y$10$./S6QGR./veb1kkZVWL8D.p5uKxUKE6F571oiqeA8Z5nbINOSGZDG', 'cskh1@vuchilinh.id.vn', '2025-03-17 15:41:21', '2025-03-17 16:02:49', 2),
(3, 'cskh2', '$2y$10$kW67LuFT7PVJ9Tydurbf0eydPkOGUkUU0r8F6UbohIZzOD4O.BRL2', 'cskh2@vuchilinh.id.vn', '2025-03-17 15:43:16', '2025-03-17 16:02:27', 2),
(4, 'cskh3', '$2y$10$kDQT6wz5.yf6kQcqj8P1TOZhDP2tX5cA7xuioWNglDy.ezOw5E0ry', 'cskh3@vuchilinh.id.vn', '2025-03-17 15:44:40', '2025-03-17 16:02:54', 2),
(5, 'cskh4', '$2y$10$v0ikl/iF0iHfounWiWMeXO7CnbZNwlHvaNOppEcz9viHW7xwylNwu', 'cskh4@vuchilinh.id.vn', '2025-03-17 15:45:54', '2025-03-17 16:02:25', 2),
(6, 'cskh5', '$2y$10$0QAFSZGjCx6g1uIoJ1DoBe3S9L5dkls36UVta8Q82nywLjgQWAJBC', 'cskh5@vuchilinh.id.vn', '2025-03-17 15:47:00', '2025-03-17 16:02:16', 2),
(7, 'ktv1', '$2y$10$ici.60ZkbggEg/DB7tQwEuSSQUAaR.RFUMZ8WU.WuRbfTIQf1Gy6O', 'ktv1@vuchilinh.id.vn', '2025-03-17 15:48:17', '2025-03-17 16:02:35', 3),
(8, 'ktv2', '$2y$10$flxokMlLv2Gx6QX4JgFq4.Sjm3SpXr3YWRkSwm/lq8IflTY8ZUCnS', 'ktv2@vuchilinh.id.vn', '2025-03-17 15:49:29', '2025-03-17 16:02:21', 3),
(9, 'ktv3', '$2y$10$CJRSUUlTqdnnDV1YQluGX.8vDOdU8SkWoSQpzsO4D0TCqh/ttcGWe', 'ktv3@vuchilinh.id.vn', '2025-03-17 15:51:38', '2025-03-17 16:02:01', 3),
(10, 'ktv4', '$2y$10$CVCkp0LXHObMba6h0LKVuuVN.vcm7kLcxZ9fG7YiHDiP/XvdOT9lq', 'ktv4@vuchilinh.id.vn', '2025-03-17 15:53:51', '2025-03-17 16:02:07', 3),
(11, 'ktv5', '$2y$10$cjDH7NQBYN6SSP18F3HREugSbDWTGQ1F.2k5QziCVtO.42zoNT3oK', 'ktv5@vuchilinh.id.vn', '2025-03-17 15:55:07', '2025-03-17 16:01:54', 3),
(12, 'khachhang1', '$2y$10$pq6b64E42xgJvj/D3nz7vuzExXq.zZzTCSHgO10DMt4EzrPsyjds.', 'khachhang1@vuchilinh.id.vn', '2025-03-17 15:57:10', '2025-03-17 15:57:10', 0),
(13, 'khachhang2', '$2y$10$x1w6vDYaePJwp57tFFat7ONQmzNDd25P5/7gDWpkqpHoUW192rfIS', 'khachhang2@vuchilinh.id.vn', '2025-03-17 15:58:07', '2025-03-17 15:58:07', 0),
(14, 'khachhang3', '$2y$10$6uPiVJlIKlPnnJsA4XQ8/edF02oZ154zRHF.yJHi9R7pTybapUETa', 'khachhang3@vuchilinh.id.vn', '2025-03-17 15:59:22', '2025-03-17 15:59:22', 0),
(15, 'khachhang4', '$2y$10$EbJHgwiFlAb5GjLmmND6meQ/huV9ogk9YyTFrhAGFNDBDldttPzLe', 'khachhang4@vuchilinh.id.vn', '2025-03-17 16:00:50', '2025-03-17 16:00:50', 0),
(16, 'khachhang5', '$2y$10$B4FI8wTEX/Nb4vzcTsCefeayH4psrBFTsW5Sr7Kx00LxMc7PoiL7K', 'khachhang5@vuchilinh.id.vn', '2025-03-17 16:01:42', '2025-03-17 16:01:42', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_infos`
--

CREATE TABLE `user_infos` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `gender` enum('nam','nu') DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_infos`
--

INSERT INTO `user_infos` (`id`, `user_id`, `fullname`, `phone`, `address`, `gender`, `avatar`, `created_at`, `update_at`) VALUES
(1, 1, 'Vũ Chí Linh', '0856564665', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317153745.jpg', '2025-03-17 15:37:45', '2025-03-17 15:37:45'),
(2, 2, 'Chăm sóc khách hàng 1', '0987654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nu', '_20250317154121.jpg', '2025-03-17 15:41:21', '2025-03-17 15:41:21'),
(3, 3, 'Chăm sóc khách hàng 2', '0897654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nu', '_20250317154316.jpg', '2025-03-17 15:43:16', '2025-03-17 15:43:16'),
(4, 4, 'Chăm sóc khách hàng 3', '0978654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nu', '_20250317154440.jpg', '2025-03-17 15:44:40', '2025-03-17 15:44:40'),
(5, 5, 'Chăm sóc khách hàng 4', '0879654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nu', '_20250317154554.jpg', '2025-03-17 15:45:54', '2025-03-17 15:45:54'),
(6, 6, 'Chăm sóc khách hàng 5', '0967854321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317154700.jpg', '2025-03-17 15:47:00', '2025-03-17 15:47:00'),
(7, 7, 'Kỹ thuật viên 1', '0789654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317154817.jpg', '2025-03-17 15:48:17', '2025-03-17 15:48:17'),
(8, 8, 'Kỹ thuật viên 2', '0798654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317154929.jpg', '2025-03-17 15:49:29', '2025-03-17 15:49:29'),
(9, 9, 'Kỹ thuật viên 3', '0799654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nu', '_20250317155138.jpg', '2025-03-17 15:51:38', '2025-03-17 15:51:38'),
(10, 10, 'Kỹ thuật viên 4', '0788654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317155351.jpg', '2025-03-17 15:53:51', '2025-03-17 15:53:51'),
(11, 11, 'Kỹ thuật viên 5', '0779654321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317155507.jpg', '2025-03-17 15:55:07', '2025-03-17 15:55:07'),
(12, 12, 'Khách hàng 1', '0678954321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nu', '_20250317155710.jpg', '2025-03-17 15:57:10', '2025-03-17 15:57:10'),
(13, 13, 'Khách hàng 2', '0679854321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317155807.jpg', '2025-03-17 15:58:07', '2025-03-17 15:58:07'),
(14, 14, 'Khách hàng 3', '0697854321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317155922.jpg', '2025-03-17 15:59:22', '2025-03-17 15:59:22'),
(15, 15, 'Khách hàng 4', '0698754321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317160050.jpg', '2025-03-17 16:00:50', '2025-03-17 16:00:50'),
(16, 16, 'Khách hàng 5', '0689754321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'nam', '_20250317160142.jpg', '2025-03-17 16:01:42', '2025-03-17 16:01:42');

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
(1, 'YC170325001510', 12, 'Khách hàng 1', '0678954321', 'TDP Minh Lập, Thị trấn Chũ, Lục Ngạn, Bắc Giang', 'Máy tính của tôi đang gặp vấn đề nghiêm trọng: màn hình xuất hiện các sọc ngang dọc rất khó chịu, ảnh hưởng lớn đến công việc. Tôi đã thử khởi động lại máy nhưng tình trạng không cải thiện. Mong quý cửa hàng có thể kiểm tra và báo giá sửa chữa sớm nhất có thể. Tôi xin cảm ơn.', '[\"khachhang1-20250317160614-0.jpg\"]', '2025-03-20 13:30:00', 7, 1, 'thay màn hình cho khách hàng', '2025-03-17 16:06:14', '2025-03-17 16:06:42', NULL);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user_infos`
--
ALTER TABLE `user_infos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `yeu_cau_sua_chua`
--
ALTER TABLE `yeu_cau_sua_chua`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
