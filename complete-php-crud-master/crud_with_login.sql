-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 16 يناير 2026 الساعة 04:39
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crud_with_login`
--

-- --------------------------------------------------------

--
-- بنية الجدول `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Category A'),
(2, 'Category B'),
(3, 'Category C'),
(4, 'Electronics'),
(5, 'Books'),
(6, 'Clothes'),
(7, 'Food');

-- --------------------------------------------------------

--
-- بنية الجدول `category_items`
--

CREATE TABLE `category_items` (
  `id` int(11) NOT NULL,
  `crud_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `category_items`
--

INSERT INTO `category_items` (`id`, `crud_id`, `category_id`) VALUES
(1, 2, 1),
(2, 3, 6),
(3, 4, 5),
(4, 5, 5),
(5, 6, 2);

-- --------------------------------------------------------

--
-- بنية الجدول `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `qty` int(5) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `login_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `products`
--

INSERT INTO `products` (`id`, `name`, `qty`, `price`, `login_id`) VALUES
(2, 'غغغغغ6', 7, 599.00, 1),
(4, 'غغغغغ6', 76, 33.00, 1),
(5, 'غغغغغ6', 7, 599.00, 16),
(6, 'غغغغغ6', 7, 599.00, 18);

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(9) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `is_admin`, `is_active`) VALUES
(1, 'yasser alrzagi', 'yasralrzaqy469@gmail.com', 'us', 'e807f1fcf82d132f9bb018ca6738a19f', 1, 1),
(2, 'yasser alrzagi', 'yasralrzaqy469@gmail.com', 'us', 'e807f1fcf82d132f9bb018ca6738a19f', 0, 1),
(3, 'ahmed', 'alrzagiyasser@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 0),
(4, 'ahmed', 'alrzagiyasser@gmail.com', 'ah', '56fe5581f6f0c52b3634bd29da9989e8', 0, 1),
(5, 'ahmed', 'alrzagiyasser@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(6, 'ahmed', 'alrzagiyasser@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(7, 'ahmed', 'alrzagiyasser@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(8, 'ahmed', 'alrzagiyasser@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(9, 'ahmed', 'alrzagiyasser@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(10, 'fff', 'eng.y779087329@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(11, 'fff', 'eng.y779087329@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(12, 'fff', 'eng.y779087329@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(13, 'fff', 'eng.y779087329@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(14, 'fff', 'eng.y779087329@gmail.com', 'ah', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(15, 'fff', 'eng.y779087329@gmail.com', 'ahj', '4fbfb9c413a6f4abfb1d182135176015', 0, 1),
(16, 'fff', 'eng.y779087329@gmail.com', 't', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(17, 'yasser alrzagi', 'yasralrzaqy469@gmail.com', 't', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1),
(18, 'fff', 'yasser15380@gmail.com', 'ty', '6fb42da0e32e07b61c9f0251fe627a9c', 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_items`
--
ALTER TABLE `category_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_products_1` (`login_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category_items`
--
ALTER TABLE `category_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `FK_products_1` FOREIGN KEY (`login_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
