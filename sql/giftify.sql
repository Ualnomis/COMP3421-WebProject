-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-04-11 17:02:00
-- 伺服器版本： 10.4.24-MariaDB
-- PHP 版本： 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `giftify`
--

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `products`
--

INSERT INTO `products` (`id`, `seller_id`, `name`, `description`, `price`, `quantity`, `image_url`) VALUES
(2, 1, 'AI CUP', 'Ai design cup', '100.00', 10, '..\\assets\\images\\ProductImg\\CUP01_1.jpg'),
(3, 1, 'Bottlescape2', 'Ai design paint', '120.00', 20, '..\\assets\\images\\ProductImg\\Bottlescape2_10.jpg'),
(4, 1, 'Lunar Vision', 'Ai design paint', '200.00', 20, '..\\assets\\images\\ProductImg\\Lunar Vision XIII_12x16.jpg'),
(5, 1, 'Bottlescape2', 'Ai design paint', '120.00', 20, '..\\assets\\images\\ProductImg\\Bottlescape2_10.jpg'),
(6, 1, 'PhoneCase001', 'Ai Phone case', '200.00', 20, '..\\assets\\images\\ProductImg\\PhoneCase001_1.png'),
(7, 1, 'AI design sticker', 'AI design sticker', '120.00', 20, '..\\assets\\images\\ProductImg\\sticker_3.png'),
(8, 1, 'AI design tableware', 'AI design tableware', '200.00', 20, '..\\assets\\images\\ProductImg\\P1.png'),
(9, 1, 'Moon', 'AI design paint', '120.00', 20, '..\\assets\\images\\ProductImg\\photo_2023-04-10_17-20-01.jpg'),
(10, 1, 'AI design Phone Case 002', 'AI design Phone case', '200.00', 20, '..\\assets\\images\\ProductImg\\PhoneCase001_1.png');

-- --------------------------------------------------------

--
-- 資料表結構 `shopping_cart`
--

CREATE TABLE `shopping_cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `shopping_cart_items`
--

CREATE TABLE `shopping_cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `role` enum('seller','buyer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `icon_url`, `role`) VALUES
(1, 'admin', 'admin', 'admin', NULL, 'seller'),
(2, 'abc123', 'abc123', '$2y$10$ea5lEinMxspPJO1fdk12Ku1bGGi1BW2paeqQbpxWmiwhD9w2hJMmi', NULL, 'buyer');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- 資料表索引 `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- 資料表索引 `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- 資料表索引 `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- 資料表索引 `shopping_cart_items`
--
ALTER TABLE `shopping_cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `shopping_cart`
--
ALTER TABLE `shopping_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `shopping_cart_items`
--
ALTER TABLE `shopping_cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`);

--
-- 資料表的限制式 `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- 資料表的限制式 `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- 資料表的限制式 `shopping_cart`
--
ALTER TABLE `shopping_cart`
  ADD CONSTRAINT `shopping_cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- 資料表的限制式 `shopping_cart_items`
--
ALTER TABLE `shopping_cart_items`
  ADD CONSTRAINT `shopping_cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `shopping_cart` (`id`),
  ADD CONSTRAINT `shopping_cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
