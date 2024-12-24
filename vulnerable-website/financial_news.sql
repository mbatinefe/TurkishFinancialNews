-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 07:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
  `id` int(11) NOT NULL,
  `news_url` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
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
(6, 'https://www.hurriyet.com.tr/ekonomi/roberto-righi-turkiyeye-guveniyor-ve-yatirim-yapiyoruz-42555412', 6, 'Good Job', '2024-12-24 15:36:28');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `created_at`) VALUES
(1, 'sql_mi_inject@gmail.com', '2024-12-24 14:13:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`, `profile_picture`) VALUES
(2, 'user', '$2y$10$iKz.wz9p8f.j6v3p8kZ6Qz.1v0qP5z3.y5pd2E8pps1U7GrXw.wEXy', 'user@example.com', 'user', '2024-12-24 13:04:52', NULL),
(3, 'user123', '$2y$10$emPAxOqwIxFYkZwtW3mjxOwbIFQEMF/2PdaHTcsOp.tQzCduov9fC', 'user123@gmail.com', 'user', '2024-12-24 13:31:26', ''),
(4, 'user456', '$2y$10$a1oyS0NgBIbSISOUtkKi9.HiMWaAdSTKVUbLHQTLp8DoYifqUkF.S', '1221321@gmail.com', 'user', '2024-12-24 13:33:50', 'uploads/pte.png'),
(6, 'admin', '$2y$10$YUYVTPv12WaXpEmwHMktluYWBrgQcCSRh7kPehgG74lYSDCrz/W4i', 'admin@admin.com', 'admin', '2024-12-24 14:22:48', '');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
