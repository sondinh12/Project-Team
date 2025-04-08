-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 08, 2025 at 03:43 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_team`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id_cart`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, '2025-03-19 21:25:11', '2025-04-04 20:41:24'),
(3, 3, 3, 4, '2025-03-19 21:25:11', '2025-04-04 20:41:38'),
(12, 2, 1, 12, '2025-04-06 20:42:39', '2025-04-07 00:15:42'),
(13, 2, 2, 4, '2025-04-06 22:22:25', '2025-04-06 23:35:28'),
(15, 2, 3, 2, '2025-04-07 00:13:55', '2025-04-07 00:14:04');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_category` int NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 'Áo thun', '2025-03-19 21:24:14', '2025-03-19 21:24:14'),
(2, 'Quần jean', '2025-03-19 21:24:14', '2025-03-19 21:24:14'),
(3, 'Giày thể thao', '2025-03-19 21:24:14', '2025-03-19 21:24:14'),
(10, 'Acer', '2025-04-03 09:27:24', '2025-04-03 09:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id_cmt` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_pro` int DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id_cmt`, `id_user`, `id_pro`, `content`, `created_at`) VALUES
(1, 1, 1, 'Sản phẩm rất đẹp, chất lượng tốt!', '2025-03-19 21:25:34'),
(2, 2, 2, 'Quần jean rất bền, sẽ ủng hộ tiếp!', '2025-03-19 21:25:34'),
(3, 3, 3, 'Giày thể thao nhẹ, đi êm chân.', '2025-03-19 21:25:34');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `total` double(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `payment` enum('cod','credit_card','paypal') DEFAULT 'cod',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `total`, `status`, `payment`, `created_at`, `updated_at`) VALUES
(1, 1, 650000.00, 'completed', 'cod', '2025-03-19 21:25:19', '2025-03-19 21:25:19'),
(2, 2, 350000.00, 'pending', 'credit_card', '2025-03-19 21:25:19', '2025-03-19 21:25:19'),
(3, 3, 500000.00, 'cancelled', 'paypal', '2025-03-19 21:25:19', '2025-03-19 21:25:19');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id_order_detail` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `pro_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id_order_detail`, `order_id`, `pro_id`, `quantity`, `created_at`) VALUES
(1, 1, 1, 2, '2025-03-19 21:25:26'),
(2, 2, 2, 1, '2025-03-19 21:25:26'),
(3, 3, 3, 1, '2025-03-19 21:25:26');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id_product` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_img` varchar(255) DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `description` text,
  `category_id` int DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id_product`, `product_name`, `product_img`, `price`, `quantity`, `description`, `category_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Áo thun nam', 'uploads/products/1743647534_ẢNH 5.png', 150000.00, 50, 'Áo thun chất cotton cao cấp', 1, 'active', '2025-04-03 09:32:14', '2025-04-03 09:32:14'),
(2, 'Quần jean nam', 'uploads/products/1743647523_Screenshot 2023-12-03 103726.png', 350000.00, 30, 'Quần jean cao cấp, co giãn tốt', 2, 'active', '2025-04-03 09:32:03', '2025-04-03 09:32:03'),
(3, 'Giày thể thao', 'uploads/products/1743087865_Screenshot 2023-12-03 103716.png', 500000.00, 20, 'Giày thể thao nhẹ, bền đẹp', 3, 'active', '2025-03-27 22:04:25', '2025-03-27 22:04:25'),
(4, 'sss', 'uploads/products/1743087421_Screenshot 2023-11-19 205308.png', 89457.00, 1, 'ưkmkg', 1, 'inactive', '2025-03-27 21:57:46', '2025-03-27 21:57:46');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id_user` int DEFAULT NULL,
  `reset_token` varchar(255) NOT NULL,
  `token_expiry` datetime NOT NULL,
  `count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id_user`, `reset_token`, `token_expiry`, `count`) VALUES
(1, 'abc123', '2025-12-31 23:59:59', 0),
(2, 'def456', '2025-12-31 23:59:59', 0),
(3, 'ghi789', '2025-12-31 23:59:59', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_img` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `name`, `user_name`, `user_img`, `email`, `phone`, `address`, `password`, `status`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Văn A', 'nguyenvana', 'user1.jpg', 'nguyenvana@example.com', '0987654321', 'Hà Nội', '482c811da5d5b4bc6d497ffa98491e38', 'active', 'user', '2025-03-19 21:24:51', '2025-03-19 21:24:51'),
(2, 'Trần Thị B', 'tranthib', 'user2.jpg', 'tranthib@example.com', '0978123456', 'TP.HCM', '482c811da5d5b4bc6d497ffa98491e38', 'active', 'user', '2025-03-19 21:24:51', '2025-03-19 21:24:51'),
(3, 'Admin', 'admin', 'admin.jpg', 'admin@example.com', '0909090909', 'Đà Nẵng', '0192023a7bbd73250516f069df18b500', 'active', 'admin', '2025-03-19 21:24:51', '2025-03-19 21:24:51');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id_voucher` int NOT NULL,
  `name_voucher` varchar(255) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `minimum_order` double(10,2) NOT NULL,
  `maximum_order` double(10,2) DEFAULT NULL,
  `status` enum('active','expired') DEFAULT 'active',
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id_voucher`, `name_voucher`, `discount_amount`, `minimum_order`, `maximum_order`, `status`, `start_at`, `end_at`, `created_at`) VALUES
(1, 'SALE10', '10.00', 100000.00, 500000.00, 'active', '2025-01-01 00:00:00', '2025-12-31 23:59:59', '2025-03-19 21:25:41'),
(2, 'FREESHIP', '50000.00', 200000.00, NULL, 'active', '2025-02-01 00:00:00', '2025-12-31 23:59:59', '2025-03-19 21:25:41'),
(3, 'BIGSALE', '20.00', 500000.00, 2000000.00, 'expired', '2024-01-01 00:00:00', '2024-12-31 23:59:59', '2025-03-19 21:25:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_cmt`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_pro` (`id_pro`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id_order_detail`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `pro_id` (`pro_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id_voucher`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id_cmt` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id_order_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id_voucher` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id_product`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`id_pro`) REFERENCES `products` (`id_product`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`pro_id`) REFERENCES `products` (`id_product`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id_category`) ON DELETE CASCADE;

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `delete_expired_tokens` ON SCHEDULE EVERY 10 MINUTE STARTS '2025-04-08 21:22:22' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM tokens 
  WHERE token_expiry < NOW() - INTERVAL 10 MINUTE$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
