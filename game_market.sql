-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 04:38 PM
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
(1, 1, 3, 3, 'ยังขายอยู่ไหมครับ สนใจไอดี Genshin ครับ', '2026-01-08 17:57:46'),
(2, 1, 3, 1, 'ยังขายอยู่ครับ สนใจโอนช่องทางไหนครับ', '2026-01-08 17:57:46'),
(3, 1, 3, 3, 'มีลดได้อีกหน่อยไหมครับ ผมงบประมาณ 4000', '2026-01-08 17:57:46'),
(4, 2, 3, 3, 'ไอดี Valorant ยังใช้อีเมลแยกไหมครับ', '2026-01-08 17:57:46'),
(5, 2, 3, 1, 'ใช่ครับ อีเมลแยกให้พร้อมรหัสครับ', '2026-01-08 17:57:46');

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `game_name`, `title`, `description`, `price`, `status`, `image_url`, `created_at`) VALUES
(1, 1, 'Genshin Impact', 'ไอดี AR55 มีตัว 5★ หลายตัว', 'ไอดี AR55 มี Ganyu, Hu Tao, Zhongli ของเยอะ ไม่ได้เล่นแล้ว สนใจต่อรองได้', 4500.00, 'active', 'https://example.com/images/genshin_acc1.jpg', '2026-01-08 17:57:39'),
(2, 1, 'Valorant', 'ไอดี Valorant แร็งค์ Diamond', 'สกินปืนครบหลายเซ็ต แร็งค์ Diamond 2 เล่นน้อยแล้ว อยากปล่อยต่อ', 3200.00, 'active', 'https://example.com/images/valo_acc1.jpg', '2026-01-08 17:57:39'),
(3, 2, 'RoV', 'ไอดี RoV ฮีโร่ครบ สกินเยอะ', 'มีฮีโร่ครบเกือบทุกตัว สกิน Limited หลายสกิน ไม่ผูกเบอร์', 2500.00, 'active', 'https://example.com/images/rov_acc1.jpg', '2026-01-08 17:57:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `display_name`, `created_at`) VALUES
(1, 'a@gmail.com', 'asdd', 'Alice', '2026-01-08 17:57:31'),
(2, 'bob@example.com', '$2y$10$bbbbb', 'Bob', '2026-01-08 17:57:31'),
(3, 'charlie@example.com', '$2y$10$ccccccc', 'Charlie', '2026-01-08 17:57:31');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
