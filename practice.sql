-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2021 at 11:12 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `practice`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_new` enum('0','1') NOT NULL DEFAULT '1',
  `status` enum('deleted','active') NOT NULL DEFAULT 'active',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `to_id`, `from_id`, `content`, `is_new`, `status`, `created`, `modified`) VALUES
(1, 2, 1, 'test', '0', 'active', '2021-01-12 17:53:21', '2021-01-12 17:53:21'),
(2, 2, 1, 'test 1', '0', 'deleted', '2021-01-12 17:53:25', '2021-01-12 17:53:25'),
(3, 1, 2, '1', '0', 'active', '2021-01-12 17:58:43', '2021-01-12 17:58:43'),
(4, 1, 2, '2', '0', 'active', '2021-01-12 17:58:44', '2021-01-12 17:58:44'),
(5, 1, 2, '3', '0', 'active', '2021-01-12 17:58:45', '2021-01-12 17:58:45'),
(6, 1, 2, '4', '0', 'active', '2021-01-12 17:58:46', '2021-01-12 17:58:46'),
(7, 1, 2, '5', '0', 'active', '2021-01-12 17:58:48', '2021-01-12 17:58:48'),
(8, 1, 2, '6', '0', 'active', '2021-01-12 17:58:49', '2021-01-12 17:58:49'),
(9, 1, 2, '7', '0', 'active', '2021-01-12 17:58:50', '2021-01-12 17:58:50'),
(10, 1, 2, '8', '0', 'active', '2021-01-12 17:58:52', '2021-01-12 17:58:52'),
(11, 1, 2, '9', '0', 'active', '2021-01-12 17:58:53', '2021-01-12 17:58:53'),
(12, 1, 2, '10', '1', 'deleted', '2021-01-12 17:58:57', '2021-01-12 17:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(50) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `hubby` text DEFAULT NULL,
  `last_login_time` datetime NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `created_ip` varchar(20) NOT NULL,
  `modified_ip` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `image`, `gender`, `birthdate`, `hubby`, `last_login_time`, `created`, `modified`, `created_ip`, `modified_ip`) VALUES
(1, 'Jermaine Maturan', 'jermaine@gmail.com', '$2a$10$KpKu3EMqHR6tbxmY6DOUMuPBIszQp.oaVwDQ/fCaBy8nEn8cTTdN.', '1610436553.jpg', '2', '2021-01-06', 'test hubby for jermaine', '2021-01-12 15:28:51', '2021-01-12 13:22:09', '2021-01-12 15:28:51', '::1', '::1'),
(2, 'Test Two', 'test2@gmail.com', '$2a$10$6KlTJhiR7mzrTD31UEVZpeZW99UZ.Eji9uJhEe0O7I5dUBXv5j6fu', '1610429026.jpg', '1', '2021-01-06', 'test hubby', '2021-01-12 15:29:34', '2021-01-12 13:22:39', '2021-01-12 15:29:34', '::1', '::1'),
(3, 'Test Three', 'test3@gmail.com', '$2a$10$/iexXjkcl8n3mS2w.aLiWOU.cK1.sSJOVPGj6mCGA2HIv7fOQxbIi', NULL, NULL, NULL, NULL, '2021-01-12 15:25:51', '2021-01-12 15:25:51', '2021-01-12 15:25:51', '::1', ''),
(4, 'test four', 'test4@gmail.com', '$2a$10$sHr/bjyLCn0xddYsBmkVGuDS/OnG4LiQD0PD4393gbMMv1VO9VZ0G', NULL, NULL, NULL, NULL, '2021-01-12 15:26:31', '2021-01-12 15:26:31', '2021-01-12 15:26:31', '::1', ''),
(5, 'test five', 'test5@gmail.com', '$2a$10$ElRGNWzurXnHZ8213SLATurEYkUq0f9t.9xaK3vrl7m337c4QUspi', NULL, NULL, NULL, NULL, '2021-01-12 15:27:03', '2021-01-12 15:27:03', '2021-01-12 15:27:03', '::1', ''),
(6, 'test six', 'test6@gmail.com', '$2a$10$AxGWTLJIN1XZG2ZpbRdaeOAH2l/zrNTefonJx0anAXk6bvETp3FBa', NULL, NULL, NULL, NULL, '2021-01-12 15:27:23', '2021-01-12 15:27:22', '2021-01-12 15:27:23', '::1', ''),
(7, 'test seven', 'test7@gmail.com', '$2a$10$Ycfogpl9gBNrg170CSwx7uCyRYmdkCD642rSG3sTv.ZMFEczZswtm', NULL, NULL, NULL, NULL, '2021-01-12 15:28:00', '2021-01-12 15:28:00', '2021-01-12 15:28:00', '::1', ''),
(8, 'test eight', 'test8@gmail.com', '$2a$10$0/qqpLs837myUI4nBihy8eT4QgKZekUV5FgtBE9PpLfc.3H50oowa', NULL, NULL, NULL, NULL, '2021-01-12 15:28:15', '2021-01-12 15:28:15', '2021-01-12 15:28:15', '::1', ''),
(9, 'test nine', 'test9@gmail.com', '$2a$10$FO7NHaWJMCJnPVh/xWgeXuxdl3dVr03sMqMViEt9MUiToA4FZcoCy', NULL, NULL, NULL, NULL, '2021-01-12 15:28:33', '2021-01-12 15:28:32', '2021-01-12 15:28:33', '::1', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
