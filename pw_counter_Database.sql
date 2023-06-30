-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2023 at 03:28 PM
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
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `recipient_id` int NOT NULL,
  `body` varchar(255) NOT NULL,
  `sent` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `viewed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `sender_id`, `recipient_id`, `body`, `sent`, `viewed`) VALUES
(1, 2, 1, 'Hello Martan van Verseveld. \n\nThis is a teacher account!\n\n🙂👍', '2023-06-25 12:13:30', NULL),
(2, 3, 2, 'Hello there.', '2023-06-25 14:31:07', NULL),
(3, 3, 1, 'AAAAAAAAAAAAAA', '2023-06-25 14:31:24', NULL),
(4, 1, 3, 'test', '2023-06-25 22:34:38', NULL),
(5, 1, 3, 'test', '2023-06-25 22:35:35', NULL),
(6, 1, 3, 'test', '2023-06-25 22:36:02', NULL),
(7, 1, 4, 'ddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd\n\ndddddddd', '2023-06-27 22:58:46', NULL),
(8, 1, 4, 'ddddddddddddddddd\n\nddddddddddddddddd\n\nddddddddddddddddd', '2023-06-27 22:59:02', NULL),
(9, 1, 4, 'aaaa', '2023-06-27 23:00:03', NULL),
(10, 1, 4, 'ddd', '2023-06-27 23:01:39', NULL),
(11, 2, 3, 'd', '2023-06-30 12:01:13', NULL),
(12, 2, 3, 'ddd', '2023-06-30 12:01:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int NOT NULL,
  `name` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`) VALUES
(1, 'GO2E-HSW1'),
(2, 'Test-Klas');

-- --------------------------------------------------------

--
-- Table structure for table `class_member`
--

CREATE TABLE `class_member` (
  `id` int NOT NULL,
  `class_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role` enum('student','teacher') NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_member`
--

INSERT INTO `class_member` (`id`, `class_id`, `user_id`, `role`) VALUES
(1, 1, 2, 'teacher'),
(2, 1, 3, 'student'),
(3, 1, 1, 'student'),
(5, 2, 5, 'student'),
(6, 2, 3, 'student'),
(7, 1, 4, 'student');

-- --------------------------------------------------------

--
-- Table structure for table `group_feedback`
--

CREATE TABLE `group_feedback` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `title` varchar(32) NOT NULL,
  `description` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_feedback`
--

INSERT INTO `group_feedback` (`id`, `group_id`, `teacher_id`, `title`, `description`) VALUES
(1, 3, 2, 'REDO', 0x5445535420574f524b204e4f5720504c45415345);

-- --------------------------------------------------------

--
-- Table structure for table `group_info`
--

CREATE TABLE `group_info` (
  `id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_info`
--

INSERT INTO `group_info` (`id`, `name`, `description`) VALUES
(3, 'Privet', 'Privet moy pusiki :3\r\n\r\nThis was edited in by a teacher'),
(5, 'A', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `group_member`
--

CREATE TABLE `group_member` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `group_id` int NOT NULL,
  `role` enum('member','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_member`
--

INSERT INTO `group_member` (`id`, `user_id`, `group_id`, `role`) VALUES
(8, 3, 3, 'owner'),
(10, 1, 5, 'owner'),
(24, 4, 5, 'member');

-- --------------------------------------------------------

--
-- Table structure for table `group_request`
--

CREATE TABLE `group_request` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `group_id` int NOT NULL,
  `type` enum('request','invite') NOT NULL,
  `status` enum('pending','invited') NOT NULL,
  `sent` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_theme`
--

CREATE TABLE `group_theme` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `theme_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_theme`
--

INSERT INTO `group_theme` (`id`, `group_id`, `theme_id`) VALUES
(8, 3, 4),
(11, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `inbox`
--

CREATE TABLE `inbox` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inbox`
--

INSERT INTO `inbox` (`id`, `user_id`, `title`, `body`) VALUES
(6, 3, 'Score update', 0x596f75722067726f757027732073636f726520776173207570646174656420746f2032),
(21, 1, 'Score update', 0x596f75722067726f757027732073636f726520776173207570646174656420746f20343231),
(22, 4, 'Score update', 0x596f75722067726f757027732073636f726520776173207570646174656420746f20343231),
(23, 3, 'Score update', 0x596f75722067726f757027732073636f726520776173207570646174656420746f20393939);

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `score` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`id`, `group_id`, `score`) VALUES
(1, 1, 0),
(2, 2, 0),
(3, 3, 999),
(4, 4, 0),
(5, 5, 421),
(6, 1, 0),
(7, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `notification` enum('all','none') NOT NULL DEFAULT 'all',
  `invite` tinyint(1) NOT NULL DEFAULT '1',
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `email` tinyint(1) NOT NULL DEFAULT '1',
  `lastname` tinyint(1) NOT NULL DEFAULT '1',
  `chat` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `user_id`, `notification`, `invite`, `private`, `email`, `lastname`, `chat`) VALUES
(1, 1, 'all', 1, 1, 1, 1, 1),
(2, 2, 'all', 1, 0, 1, 1, 1),
(3, 3, 'all', 1, 0, 1, 1, 1),
(4, 4, 'all', 1, 0, 1, 1, 1),
(5, 5, 'all', 1, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE `theme` (
  `id` int NOT NULL,
  `title` varchar(32) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL,
  `description` blob NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `hidden` tinyint(1) NOT NULL DEFAULT '1',
  `max` tinyint NOT NULL DEFAULT '10'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `theme`
--

INSERT INTO `theme` (`id`, `title`, `description`, `active`, `hidden`, `max`) VALUES
(1, 'First theme 2', 0x42727568, 1, 0, 1),
(2, 'd', 0x6464646464, 0, 1, 10),
(3, 'Please dontddddd', 0x536f20697420646f65736e277420776f726b210d0a0d0a546869732069732061207665727920676f6f64207468696e6720626563617573653a0d0a2d20492077616e74656420697420746f206e6f7420776f726b2e0d0a2d204f746865727769736520616c6f74206f66206572726f727320776f756c6420636f6d6520616c6f6e672e0d0a2d2057686963682069736e277420676f6f642e0d0a0d0a266c743b7363726970742667743b77696e646f772e616c657274282671756f743b5853532671756f743b29266c743b2f7363726970742667743b, 0, 0, 69),
(4, 'New test lol', 0x4163746976653f204f722048696464656e2e2e2e, 0, 1, 10),
(5, 'Thema 1', 0x54657374, 0, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(32) NOT NULL,
  `firstname` varchar(8) NOT NULL,
  `lastname` varchar(24) NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT ' ',
  `password` varchar(128) NOT NULL,
  `class` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('student','teacher','roleless') DEFAULT 'roleless'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `firstname`, `lastname`, `description`, `password`, `class`, `role`) VALUES
(1, 'martan@vverseveld.nl', 'Martan', 'van Verseveld', ' ', '$2y$10$OEt4b12T5zJthkINCzwdoebZWIJGAUCW06qbHV58fZiZKi/3QINGS', NULL, 'student'),
(2, 'teacher@mborijnland.nl', 'teacher', 'mborijnland', 'This is a teacher account!', '$2y$10$6w69aSew5xBlzE7gDXRZTOWHB/dBobwa3obRTm3rM.mo5QYeUX1A6', NULL, 'teacher'),
(3, 'test@account.com', 'test', 'account', ' ', '$2y$10$Io2sZryw7hRXaW1KhCky4urBfKVsJvRF/Oe735An9i.cCTPI0YbAO', NULL, 'student'),
(4, 'test2@account.com', 'test2', 'account', ' ', '$2y$10$7nUTmWisifx5DfFvgZlG0um4p/vMudQFzCL7fqRS2EgLFZ9XMO3ty', NULL, 'student'),
(5, 'admin@netfish.nl', 'a', 'b', ' ', '$2y$10$4ECD9J1WoKeM1S8L9WXGW.lGAC.P8NEuohLJtW0xKgWVe3T4xRAPG', NULL, 'teacher');

-- --------------------------------------------------------

--
-- Table structure for table `user_feedback`
--

CREATE TABLE `user_feedback` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  `title` varchar(32) NOT NULL,
  `description` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_feedback`
--

INSERT INTO `user_feedback` (`id`, `user_id`, `teacher_id`, `title`, `description`) VALUES
(1, 1, 2, 'Sorry for the feedback Martan', 0x576f6f70736965);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `group_feedback`
--
ALTER TABLE `group_feedback`
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
-- Indexes for table `group_theme`
--
ALTER TABLE `group_theme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inbox`
--
ALTER TABLE `inbox`
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
-- Indexes for table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_feedback`
--
ALTER TABLE `user_feedback`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `class_member`
--
ALTER TABLE `class_member`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `group_feedback`
--
ALTER TABLE `group_feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_info`
--
ALTER TABLE `group_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `group_member`
--
ALTER TABLE `group_member`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `group_request`
--
ALTER TABLE `group_request`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `group_theme`
--
ALTER TABLE `group_theme`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inbox`
--
ALTER TABLE `inbox`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `theme`
--
ALTER TABLE `theme`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_feedback`
--
ALTER TABLE `user_feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

