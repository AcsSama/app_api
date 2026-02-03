-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 07:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `game_market`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `post_id`, `buyer_id`, `sender_id`, `text`, `created_at`) VALUES
(14, 31, 2, 1, 'ขอโทษครับพรี่😭😭😭', '2026-01-29 00:18:10'),
(13, 31, 2, 2, 'แจ้งแอดมินแปป', '2026-01-29 00:17:31'),
(11, 31, 2, 2, 'พี่ๆ ของผมหล่ะ', '2026-01-29 00:16:23'),
(12, 31, 2, 1, 'คามุย 🫣🫣', '2026-01-29 00:16:58'),
(4, 2, 3, 3, 'ไอดี Valorant ยังใช้อีเมลแยกไหมครับ', '2026-01-08 17:57:46'),
(5, 2, 3, 1, 'ใช่ครับ อีเมลแยกให้พร้อมรหัสครับ', '2026-01-08 17:57:46'),
(6, 26, 2, 2, 'Hi', '2026-01-26 22:05:00'),
(7, 26, 2, 2, 'Hello', '2026-01-26 22:11:47'),
(8, 26, 2, 2, 'asd', '2026-01-26 22:39:54'),
(9, 26, 2, 1, 'Eyyy ไม่ขายให้หรอก', '2026-01-26 22:44:58'),
(10, 26, 2, 1, 'ควยๆๆๆๆ', '2026-01-26 22:47:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'completed',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `post_id`, `price`, `status`, `created_at`) VALUES
(1, 2, 31, 123.00, 'completed', '2026-01-29 00:09:22');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_name` varchar(100) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `platform` varchar(100) NOT NULL DEFAULT '',
  `rank` varchar(100) NOT NULL DEFAULT '',
  `image_base64` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `game_name`, `title`, `description`, `price`, `status`, `image_url`, `created_at`, `platform`, `rank`, `image_base64`) VALUES
(34, 1, 'testbase64', 'testbase64', 'testbase64', 123.00, 'active', '-', '2026-02-04 00:50:50', 'testbase64', 'testbase64', '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');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `display_name`, `role`, `created_at`, `balance`) VALUES
(1, 'a@gmail.com', '$2y$10$WV0remO9IXhWFtK7Z.mgq.DB5l/cu8L2CmO6tiUWVskSN0Db7gRoW', 'AcsSa', 'user', '2026-01-08 17:57:31', 173.00),
(2, 'b@gmail.com', '$2y$10$WV0remO9IXhWFtK7Z.mgq.DB5l/cu8L2CmO6tiUWVskSN0Db7gRoW', 'Bob', 'user', '2026-01-08 17:57:31', 877.00),
(3, 'c@gmail.com', '$2y$10$WV0remO9IXhWFtK7Z.mgq.DB5l/cu8L2CmO6tiUWVskSN0Db7gRoW', 'Charlie', 'user', '2026-01-08 17:57:31', 0.00),
(17, 'test@gmail.com', '$2y$10$0qK1GyAecwYIWVrpG1AxL.ktIYnKj1X0AF3xU573Nzf.xZxSufAZS', 'GameTest', 'admin', '2026-01-20 22:17:10', 0.00),
(18, 'notwasser@gmail.com', '$2y$10$rQKsmz9bNNRRVNDTAJGx4.lQ/6zQm/gO6CkIUocEAtx1VSI89zfRi', 'NOTTO', 'user', '2026-01-23 20:41:25', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
