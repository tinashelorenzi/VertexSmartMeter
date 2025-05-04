-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 01, 2025 at 04:15 PM
-- Server version: 8.0.42
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vertexcg_VSM_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `KWusage`
--

CREATE TABLE `KWusage` (
  `id` int NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  `KWusage` float NOT NULL,
  `UnitsLeft` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `KWusage`
--

INSERT INTO `KWusage` (`id`, `device_id`, `timestamp`, `KWusage`, `UnitsLeft`) VALUES
(47, '2C:BC:BB:22:6A:00', '2025-04-29T23:52:57+02:00', 0.092, 0),
(48, '2C:BC:BB:22:6A:00', '2025-04-29T23:54:48+02:00', 0.092, 0),
(49, '2C:BC:BB:22:6A:00', '2025-04-29T23:57:52+02:00', 0.092, 0),
(50, '2C:BC:BB:22:6A:00', '2025-04-30T00:00:07+02:00', 0.092, 0),
(51, '2C:BC:BB:22:6A:00', '2025-04-30T00:03:11+02:00', 0.092, 0),
(52, '2C:BC:BB:22:6A:00', '2025-04-30T00:05:48+02:00', 0.092, 0),
(53, '2C:BC:BB:22:6A:00', '2025-04-30T00:07:27+02:00', 0.092, 0),
(54, '2C:BC:BB:22:6A:00', '2025-04-30T00:08:49+02:00', 0.092, 0),
(55, '2C:BC:BB:22:6A:00', '2025-04-30T00:29:59+02:00', 0.092, 0),
(56, '2C:BC:BB:22:6A:00', '2025-04-30T00:55:45+02:00', 0.092, 0),
(57, '2C:BC:BB:22:6A:00', '2025-04-30T01:55:38+02:00', 0.092, 0),
(58, '2C:BC:BB:22:6A:00', '2025-04-30T02:55:39+02:00', 0.092, 0),
(59, '2C:BC:BB:22:6A:00', '2025-04-30T03:55:42+02:00', 0.092, 0),
(60, '2C:BC:BB:22:6A:00', '2025-04-30T04:55:42+02:00', 0.092, 0),
(61, '2C:BC:BB:22:6A:00', '2025-04-30T05:55:42+02:00', 0.092, 0),
(62, '2C:BC:BB:22:6A:00', '2025-04-30T06:55:43+02:00', 0.092, 0),
(63, '2C:BC:BB:22:6A:00', '2025-04-30T07:22:53+02:00', 0.092, 0),
(64, '2C:BC:BB:22:6A:00', '2025-04-30T08:22:46+02:00', 0.092, 0),
(65, '2C:BC:BB:22:6A:00', '2025-04-30T09:22:47+02:00', 0.092, 0),
(66, '2C:BC:BB:22:6A:00', '2025-04-30T10:22:49+02:00', 0.092, 0),
(67, '2C:BC:BB:22:6A:00', '2025-04-30T11:22:51+02:00', 0.092, 0),
(68, '2C:BC:BB:22:6A:00', '2025-04-30T12:22:52+02:00', 0.092, 0),
(69, '2C:BC:BB:22:6A:00', '2025-04-30T13:22:53+02:00', 0.092, 0),
(70, '2C:BC:BB:22:6A:00', '2025-04-30T14:22:55+02:00', 0.092, 0),
(71, '2C:BC:BB:22:6A:00', '2025-04-30T14:31:01+02:00', 0.092, 0),
(72, '2C:BC:BB:22:6A:00', '2025-04-30T14:35:38+02:00', 0.092, 0),
(73, '2C:BC:BB:22:6A:00', '2025-04-30T14:40:04+02:00', 0.092, 0),
(74, '2C:BC:BB:22:6A:00', '2025-04-30T15:39:58+02:00', 0.092, 0),
(75, '2C:BC:BB:22:6A:00', '2025-04-30T16:39:59+02:00', 0.092, 0),
(76, '2C:BC:BB:22:6A:00', '2025-04-30T17:40:01+02:00', 0.092, 0),
(77, '2C:BC:BB:22:6A:00', '2025-04-30T18:40:02+02:00', 0.092, 0),
(78, '2C:BC:BB:22:6A:00', '2025-04-30T19:40:03+02:00', 0.092, 0),
(79, '2C:BC:BB:22:6A:00', '2025-04-30T20:40:03+02:00', 0.092, 0),
(80, '2C:BC:BB:22:6A:00', '2025-04-30T21:40:05+02:00', 0.092, 0),
(81, '2C:BC:BB:22:6A:00', '2025-04-30T22:18:10+02:00', 0.092, 0),
(82, '2C:BC:BB:22:6A:00', '2025-05-01T01:18:11+02:00', 0.092, 0),
(83, '2C:BC:BB:22:6A:00', '2025-05-01T02:18:14+02:00', 0.092, 0),
(84, '2C:BC:BB:22:6A:00', '2025-05-01T03:18:16+02:00', 0.092, 0),
(85, '2C:BC:BB:22:6A:00', '2025-05-01T04:18:17+02:00', 0.092, 0),
(86, '2C:BC:BB:22:6A:00', '2025-05-01T05:18:19+02:00', 0.092, 0),
(87, '2C:BC:BB:22:6A:00', '2025-05-01T06:18:19+02:00', 0.092, 0),
(88, '2C:BC:BB:22:6A:00', '2025-05-01T06:58:02+02:00', 0.092, 0),
(89, '2C:BC:BB:22:6A:00', '2025-05-01T07:57:56+02:00', 0.092, 0),
(90, '2C:BC:BB:22:6A:00', '2025-05-01T08:23:35+02:00', 0.092, 0);

-- --------------------------------------------------------

--
-- Table structure for table `smart_meters`
--

CREATE TABLE `smart_meters` (
  `id` int NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `last_update_id` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `smart_meters`
--

INSERT INTO `smart_meters` (`id`, `device_id`, `last_update_id`) VALUES
(1, '9C:9C:1F:D0:76:94', 1),
(5, '2C:BC:BB:22:6A:00', 4);

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `id` int NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `units` int NOT NULL,
  `update_id` int NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `updates`
--

INSERT INTO `updates` (`id`, `device_id`, `units`, `update_id`, `status`) VALUES
(1, '9C:9C:1F:D0:76:94', 100, 1, 'completed'),
(14, '2C:BC:BB:22:6A:00', 1, 1, 'completed'),
(19, '2C:BC:BB:22:6A:00', 10, 2, 'completed'),
(20, '2C:BC:BB:22:6A:00', -5, 3, 'completed'),
(21, '2C:BC:BB:22:6A:00', 3, 4, 'completed');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `KWusage`
--
ALTER TABLE `KWusage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `smart_meters`
--
ALTER TABLE `smart_meters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_id` (`device_id`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `KWusage`
--
ALTER TABLE `KWusage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `smart_meters`
--
ALTER TABLE `smart_meters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `KWusage`
--
ALTER TABLE `KWusage`
  ADD CONSTRAINT `KWusage_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `smart_meters` (`device_id`);

--
-- Constraints for table `updates`
--
ALTER TABLE `updates`
  ADD CONSTRAINT `updates_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `smart_meters` (`device_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
