-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2026 at 04:59 PM
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
(4, 2, 3, 3, 'ไอดี Valorant ยังใช้อีเมลแยกไหมครับ', '2026-01-08 17:57:46'),
(5, 2, 3, 1, 'ใช่ครับ อีเมลแยกให้พร้อมรหัสครับ', '2026-01-08 17:57:46'),
(6, 26, 2, 2, 'Hi', '2026-01-26 22:05:00'),
(7, 26, 2, 2, 'Hello', '2026-01-26 22:11:47'),
(8, 26, 2, 2, 'asd', '2026-01-26 22:39:54'),
(9, 26, 2, 1, 'Eyyy ไม่ขายให้หรอก', '2026-01-26 22:44:58'),
(10, 26, 2, 1, 'ควยๆๆๆๆ', '2026-01-26 22:47:13');

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
  `rank` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `game_name`, `title`, `description`, `price`, `status`, `image_url`, `created_at`, `platform`, `rank`) VALUES
(1, 1, 'Genshin Impact', 'ไอดี AR55 มีตัว 5★ หลายตัว', '-', 600.00, 'soldout', 'https://preview.redd.it/wts-genshin-asia-accounts-well-built-well-maintained-c6-v0-avho85m0wk2g1.jpg?width=948&format=pjpg&auto=webp&s=1101a3136f41450c1afaa5aa05e76506a674d039', '2026-01-08 17:57:39', '', ''),
(2, 1, 'Valorant', 'ไอดี Valorant แร็งค์ Diamond', 'สกินปืนครบหลายเซ็ต แร็งค์ Diamond 2 เล่นน้อยแล้ว อยากปล่อยต่อ', 590.00, 'active', 'https://scontent.fphs1-1.fna.fbcdn.net/v/t1.6435-9/169109245_134064185338785_6020444063684085481_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=127cfc&_nc_ohc=xBcmgpqiAiAQ7kNvwGfPrUX&_nc_oc=AdlupLkjA72Zuvh3W2wRWbw-OfAw6c0RcFk7lgpKfA3Pc6sc7I_5_lCsBU8UXXqjhoY&_nc_zt=23&_nc_ht=scontent.fphs1-1.fna&_nc_gid=m4s3NPpBbamKhoUEXmH4vA&oh=00_Afqpk2BgGZKSz5tkk6NvmaEjDYt5E55mNp4i_Y-ClZSNZw&oe=6995A65A', '2026-01-08 17:57:39', '', ''),
(3, 2, 'RoV', 'ไอดี RoV ฮีโร่ครบ สกินเยอะ', 'มีฮีโร่ครบเกือบทุกตัว สกิน Limited หลายสกิน ไม่ผูกเบอร์', 450.00, 'active', 'https://scontent.fphs1-1.fna.fbcdn.net/v/t1.6435-9/72612003_105037550908878_4313528263457636352_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=833d8c&_nc_ohc=Gi99yyEiu70Q7kNvwGezCR0&_nc_oc=AdmECSea9esZ9u9cw2qDN47q5rHksBsd_Jjt4uNNJmQiTmISa19HFiTaTEN65ywoG5A&_nc_zt=23&_nc_ht=scontent.fphs1-1.fna&_nc_gid=5zdGx8HM4ds16yC7L5UjKw&oh=00_AfqQuIJaK5IW4xhz8zTLZ1DrU3M4hojKp2y5edT1URQiNA&oe=6995B1B4', '2026-01-08 17:57:39', '', ''),
(4, 1, 'Line Ranger', '(เกมเกลือๆ)', '(เกมเกลือๆ)', 200.00, 'soldout', 'https://scontent.fphs1-1.fna.fbcdn.net/v/t39.30808-6/619599500_122203629584339600_2941375376258755188_n.jpg?stp=dst-jpg_s960x960_tt6&_nc_cat=107&ccb=1-7&_nc_sid=aa7b47&_nc_ohc=YpYmot9bKV4Q7kNvwHYmWEB&_nc_oc=AdmV1rInC8Ox00rfe-18_CHIGNbvSH1u6hTZLMyP-LQnuTvLAEwnt0N-MSeEGSi7zu0&_nc_zt=23&_nc_ht=scontent.fphs1-1.fna&_nc_gid=AVbz_TNbVAFA6IbO47amJg&oh=00_Afpz7mhy14ycD0YN4E9aFDpH9Ukuy9vhdlvfC4iC49gOeQ&oe=69740818', '2026-01-14 20:02:27', '', ''),
(6, 2, 'auhsdkus', 'sdiofsoidjf', 'aposkjd;oajisf', 123123.00, 'soldout', 'https://scontent.fphs1-1.fna.fbcdn.net/v/t39.30808-6/616991564_1561140578266821_3159889903761540627_n.jpg?stp=dst-jpg_p526x296_tt6&_nc_cat=105&ccb=1-7&_nc_sid=833d8c&_nc_ohc=GFE8pEUu02MQ7kNvwEKMru7&_nc_oc=Admp5AYFbyT97OrxnbZBAMov3_NrCgf3Big1HqcTFuPViwV2xEbDpX7rJxIW9HsMKlg&_nc_zt=23&_nc_ht=scontent.fphs1-1.fna&_nc_gid=8I1JXsAh0UAVcbx7jcmuiw&oh=00_AfofnULWW1uJrM55ipP7ovPMzaoecrVBxlv3cwDurk9E1g&oe=6973FAE3', '2026-01-19 21:08:44', '', ''),
(7, 5, 'hasdf', 'sdfsdg', 'dfgsdf', 123.00, 'active', '-', '2026-01-20 20:59:46', '', ''),
(15, 3, 'Roblox', 'Bloxfruit ไอดีไก่ไซบอร์กT10', 'ของตามรูป', 85.00, 'soldout', 'https://scontent.fphs1-1.fna.fbcdn.net/v/t39.30808-6/618251584_122228356688263863_2268057390154555076_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=aa7b47&_nc_eui2=AeE4yOtGFLFQTHpYqduBZIJ3yCezTPWom1fIJ7NM9aibV2pjfkFrw6ykiCI9W9eD1l1vbitD5akNTJPnANMxQMqT&_nc_ohc=UFEVIpC-P0sQ7kNvwHjar_S&_nc_oc=AdlmQxdPQPbzAjJyys-3aR06tytdVELZNHi_wV8-r7Y3LjARGeFS_erOwewkLxOCuVg&_nc_zt=23&_nc_ht=scontent.fphs1-1.fna&_nc_gid=Ce2A_2z95sz9H8NO3NgC0w&oh=00_AfrL1QOMNwbMngfUS3w-53lJxd29VhtZtEIdqiwLS2YL7w&oe=6976A1F7', '2026-01-21 20:59:25', 'Roblox', '2800'),
(26, 1, 'a', 'a', 'a', 1.00, 'active', 'uploads/img_1769172028_4257.jpg', '2026-01-23 19:40:28', 'a', 'a'),
(27, 1, 'Bloxfruit', 'ไก่ตันผลมังกร', 'สุ่ม 6-7 หมัด สุ่มดาบคู่-กีตาร์ การันตีผลมังกร สุ่มผลแดงในกล่อง', 300.00, 'active', 'uploads/img_1769175740_3063.jpg', '2026-01-23 20:42:20', 'Roblox', '2800'),
(28, 18, 'rov', 'rov', 'มี 1 สกิน', 9999.00, 'soldout', 'uploads/img_1769175933_7356.jpg', '2026-01-23 20:45:33', '', 'ไดม่อน');

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
(1, 'a@gmail.com', '$2y$10$WV0remO9IXhWFtK7Z.mgq.DB5l/cu8L2CmO6tiUWVskSN0Db7gRoW', 'AcsSama', 'user', '2026-01-08 17:57:31', 0.00),
(2, 'b@gmail.com', '$2y$10$WV0remO9IXhWFtK7Z.mgq.DB5l/cu8L2CmO6tiUWVskSN0Db7gRoW', 'Bob', 'user', '2026-01-08 17:57:31', 0.00),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
