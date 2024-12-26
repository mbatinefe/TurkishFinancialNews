-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Dec 26, 2024 at 06:44 PM
-- Server version: 8.0.40
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `financial_news`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `news_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `news_url`, `user_id`, `comment`, `created_at`) VALUES
(1, 'https://www.hurriyet.com.tr/ekonomi/roberto-righi-turkiyeye-guveniyor-ve-yatirim-yapiyoruz-42555412', 3, 'Hello', '2024-12-24 13:50:12'),
(2, 'https://www.hurriyet.com.tr/ekonomi/roberto-righi-turkiyeye-guveniyor-ve-yatirim-yapiyoruz-42555412', 3, 'Vay be', '2024-12-24 13:58:38'),
(3, 'https://www.hurriyet.com.tr/ekonomi/roberto-righi-turkiyeye-guveniyor-ve-yatirim-yapiyoruz-42555412', 4, 'helal be', '2024-12-24 14:04:37'),
(4, 'https://www.hurriyet.com.tr/ekonomi/milliyet-executive-ile-ihracatin-yeni-yildizlari-42581927', 4, 'gelsin dolarlar', '2024-12-24 14:04:52'),
(5, 'https://www.hurriyet.com.tr/ekonomi/yasa-disi-bahis-ve-suc-gelirlerinin-aklanmasina-karsi-mucadele-42554788', 6, ':OOO', '2024-12-24 15:36:16'),
(6, 'https://www.hurriyet.com.tr/ekonomi/roberto-righi-turkiyeye-guveniyor-ve-yatirim-yapiyoruz-42555412', 6, 'Good Job', '2024-12-24 15:36:28'),
(7, 'https://www.hurriyet.com.tr/ekonomi/milliyet-executive-ile-ihracatin-yeni-yildizlari-42581927', 6, 'vay', '2024-12-26 08:21:04'),
(8, 'https://www.hurriyet.com.tr/ekonomi/milliyet-executive-ile-ihracatin-yeni-yildizlari-42581927', 8, 'hello', '2024-12-26 08:43:39'),
(10, 'https://www.hurriyet.com.tr/ekonomi/milliyet-executive-ile-ihracatin-yeni-yildizlari-42581927', 15, 'Amazing', '2024-12-26 18:42:34'),
(11, 'https://www.hurriyet.com.tr/ekonomi/yasa-disi-bahis-ve-suc-gelirlerinin-aklanmasina-karsi-mucadele-42554788', 15, 'Nice', '2024-12-26 18:42:43'),
(12, 'https://www.hurriyet.com.tr/ekonomi/trafikte-xenon-far-teroru-42554588', 15, 'yeter', '2024-12-26 18:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `created_at`) VALUES
(1, 'sql_mi_inject@gmail.com', '2024-12-24 14:13:33'),
(2, '1221321@gmail.com', '2024-12-26 08:28:07'),
(3, 'demo@gmail.com', '2024-12-26 08:40:24'),
(4, 'aaaa@gmail.com', '2024-12-26 18:39:28'),
(5, 'bbbbb@gmail.com', '2024-12-26 18:39:31'),
(6, 'ccccc@gmail.com', '2024-12-26 18:39:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_general_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`, `profile_picture`) VALUES
(2, 'user', '123456', 'user@example.com', 'user', '2024-12-24 13:04:52', NULL),
(3, 'user123', '123456', 'user123@gmail.com', 'user', '2024-12-24 13:31:26', ''),
(4, 'user456', '123456', '1221321@gmail.com', 'user', '2024-12-24 13:33:50', 'uploads/pte.png'),
(6, 'admin', '123456', 'admin@admin.com', 'admin', '2024-12-24 14:22:48', ''),
(8, 'demo', 'demo', 'demo@demo.com', 'user', '2024-12-26 08:39:54', ''),
(15, 'recep', 'recep', 'auth@auth.com', 'user', '2024-12-26 18:42:23', 'uploads/cluster-mozart.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
