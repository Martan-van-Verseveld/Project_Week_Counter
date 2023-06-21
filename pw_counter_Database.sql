-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 21, 2023 at 05:55 PM
-- Server version: 8.0.33-0ubuntu0.22.04.2
-- PHP Version: 8.1.2-1ubuntu2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pw_counter`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int NOT NULL,
  `name` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_member`
--

CREATE TABLE `class_member` (
  `id` int NOT NULL,
  `class_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role` enum('student','teacher') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_info`
--

CREATE TABLE `group_info` (
  `id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_info`
--

INSERT INTO `group_info` (`id`, `name`, `description`) VALUES
(1, 'Testing Group Great update', 'This group is made for testing purposes.'),
(2, 'test group 2', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'),
(3, 'Martan', 'Martan group :3'),
(4, 'aaa', 'aaaaaaaa'),
(5, 'Martanaaa', 'aaaaa'),
(6, 'ddddd', 'ddddd'),
(7, 'Test group', 'Tehee'),
(8, '111 test', 'teeeest'),
(9, '111 test', 'teeeest'),
(10, '22222222', '222'),
(11, '22222222', '222'),
(12, 'aaaaaaaaaaaaaa', 'aa'),
(13, 'aaaa', 'aaaaaaaaaaaa'),
(14, 'aa tehee', 'aa'),
(15, 'adddddddddd', 'aaaaaaaaaaaaaaaaaa'),
(16, '222222222222222222222', '22222222'),
(17, 'ddddddddddddddddddddd', 'dczxczxc');

-- --------------------------------------------------------

--
-- Table structure for table `group_member`
--

CREATE TABLE `group_member` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `group_id` int NOT NULL,
  `role` enum('member','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_member`
--

INSERT INTO `group_member` (`id`, `user_id`, `group_id`, `role`) VALUES
(30, 1, 2, 'owner'),
(32, 4, 6, 'owner'),
(43, 2, 17, 'owner');

-- --------------------------------------------------------

--
-- Table structure for table `group_request`
--

CREATE TABLE `group_request` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `group_id` int NOT NULL,
  `type` enum('request','invite') NOT NULL,
  `status` enum('pending','invited') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_request`
--

INSERT INTO `group_request` (`id`, `user_id`, `group_id`, `type`, `status`) VALUES
(76, 2, 1, 'request', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `score` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`id`, `group_id`, `score`) VALUES
(1, 2, 1337),
(2, 1, 69420);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `notification` enum('all','none') NOT NULL DEFAULT 'all',
  `invites` tinyint(1) NOT NULL DEFAULT '1',
  `private` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `notification`, `invites`, `private`) VALUES
(1, 5, 'all', 1, 0),
(2, 6, 'all', 1, 0),
(3, 2, 'all', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(32) NOT NULL,
  `firstname` varchar(8) NOT NULL,
  `lastname` varchar(24) NOT NULL,
  `password` varchar(128) NOT NULL,
  `class` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `role` enum('student','teacher','roleless') DEFAULT 'roleless'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `firstname`, `lastname`, `password`, `class`, `role`) VALUES
(1, 'test@account.com', 'test', 'account', '$2y$10$o.J8VD2/Fvg2t7jt0vbmyOlURWIvGVXUg4K3Fjbiwvv189bAX5RKm', NULL, 'student'),
(2, 'martan@vverseveld.nl', 'Martan', 'van Verseveld', '$2y$10$/4wclDOyk2849lrXYjEQN.4dKtgQnq8eFZx9j7zm59XeKrK7gBvhK', NULL, 'teacher'),
(3, 'test2@account.com', 'test2', 'account', '$2y$10$J97ZI348VL35QIoRhrTB.uAu6AO2YkCmV02nZ22eKlyr0RcD5cJ9O', NULL, 'student'),
(4, 'martan2@vverseveld.nl', 'Martan2', 'van Verseveld', '$2y$10$k8RDBjWWBL/8g27zTSVNWOhi6s2z9DLqRTSkCSCc2r6uMhfFNxBL6', NULL, 'teacher'),
(5, 'admin@netfish.nl', 'lol', 'go to hell bitch', '$2y$10$AIKDafm07T28A1m.Ka2M6u.uXIBQsYqGRI9Ziez1cK0XRGZ4Vdpe2', NULL, 'student'),
(6, 'teacher@mborijnland.nl', 'teacher', 'mborijnland', '$2y$10$F.XKIAPX7BLZ1Hn3NT1lAeL8/PEh9BPzUlXR1WG5fqjmsQeLri6Nm', NULL, 'teacher');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_member`
--
ALTER TABLE `class_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_info`
--
ALTER TABLE `group_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_member`
--
ALTER TABLE `group_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_request`
--
ALTER TABLE `group_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_member`
--
ALTER TABLE `class_member`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_info`
--
ALTER TABLE `group_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `group_member`
--
ALTER TABLE `group_member`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `group_request`
--
ALTER TABLE `group_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
