-- database/init.sql

-- Create the database if it doesn't exist and use it
CREATE DATABASE IF NOT EXISTS `bdls_db`;
USE `bdls_db`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Table structure for table `audit_logs`
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `document_types`
CREATE TABLE IF NOT EXISTS `document_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `document_types`
INSERT IGNORE INTO `document_types` (`id`, `name`) VALUES
(1, 'Barangay Clearance'),
(2, 'Certificate of Indigency');

-- Table structure for table `service_requests`
CREATE TABLE IF NOT EXISTS `service_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resident_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  `queue_number` varchar(20) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `resident_id` (`resident_id`),
  KEY `document_type_id` (`document_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `service_requests`
INSERT IGNORE INTO `service_requests` (`id`, `resident_id`, `document_type_id`, `queue_number`, `status`, `created_at`) VALUES
(9, 19, 2, 'W-20260323-989', 'Released', '2026-03-23 18:15:39'),
(18, 4, 1, 'O-20260324-001', 'Pending', '2026-03-24 00:00:00'),
(19, 5, 2, 'O-20260324-002', 'Processing', '2026-03-24 01:30:00'),
(20, 6, 1, 'O-20260324-003', 'Processing', '2026-03-24 02:15:00'),
(21, 7, 2, 'O-20260324-004', 'Released', '2026-03-24 03:00:00'),
(22, 20, 1, 'W-20260324-748', 'Processing', '2026-03-24 00:02:14');

-- Table structure for table `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL DEFAULT 'resident',
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `users`
INSERT IGNORE INTO `users` (`id`, `role`, `username`, `password`, `first_name`, `last_name`, `date_of_birth`, `created_at`) VALUES
(4, 'resident', NULL, NULL, 'Andres', 'Bonifacio', '1995-11-30', '2026-03-23 18:08:35'),
(5, 'resident', NULL, NULL, 'Jose', 'Rizal', '1998-06-19', '2026-03-23 18:08:35'),
(6, 'resident', NULL, NULL, 'Emilio', 'Aguinaldo', '2001-03-22', '2026-03-23 18:08:35'),
(7, 'resident', NULL, NULL, 'Apolinario', 'Mabini', '1990-07-23', '2026-03-23 18:08:35'),
(8, 'resident', NULL, NULL, 'Marcelo', 'Del Pilar', '1985-08-30', '2026-03-23 18:08:35'),
(9, 'resident', NULL, NULL, 'Juan', 'Luna', '1988-10-23', '2026-03-23 18:08:35'),
(10, 'resident', NULL, NULL, 'Antonio', 'Luna', '1992-10-29', '2026-03-23 18:08:35'),
(11, 'resident', NULL, NULL, 'Melchora', 'Aquino', '1950-01-06', '2026-03-23 18:08:35'),
(12, 'resident', NULL, NULL, 'Gabriela', 'Silang', '1999-03-19', '2026-03-23 18:08:35'),
(13, 'resident', NULL, NULL, 'Lapu', 'Lapu', '1980-04-27', '2026-03-23 18:08:35'),
(14, 'resident', NULL, NULL, 'Teresa', 'Magbanua', '1994-10-13', '2026-03-23 18:08:35'),
(15, 'resident', NULL, NULL, 'Gregoria', 'De Jesus', '2002-05-09', '2026-03-23 18:08:35'),
(16, 'resident', NULL, NULL, 'Miguel', 'Malvar', '1975-09-27', '2026-03-23 18:08:35'),
(17, 'resident', NULL, NULL, 'Macario', 'Sakay', '1982-03-01', '2026-03-23 18:08:35'),
(18, 'resident', NULL, NULL, 'Diego', 'Silang', '1996-12-16', '2026-03-23 18:08:35'),
(19, 'resident', NULL, NULL, 'Jose', 'Olinares', NULL, '2026-03-23 18:15:39'),
(20, 'resident', NULL, NULL, 'Charles', 'Pangilinan', NULL, '2026-03-24 00:02:14'),
(21, 'admin', 'jose', '$2y$10$xegkiv/doQwv4veVlj6b9eC9143u5LOn/QYOZGOgES25kldnoHAWO', 'Jose', 'Admin', NULL, '2026-03-24 00:24:11'),
(22, 'admin', 'maricar', '$2y$10$Qk5UScKnFlWq5iSF6EHHVeZnq8bbhZVMc8hN.2lPscU5uMoK9gnby', 'Maricar', 'Admin', NULL, '2026-03-24 00:24:11'),
(23, 'admin', 'beatriz', '$2y$10$Qk5UScKnFlWq5iSF6EHHVeZnq8bbhZVMc8hN.2lPscU5uMoK9gnby', 'Beatriz', 'Admin', NULL, '2026-03-24 00:24:11'),
(24, 'admin', 'annaleah', '$2y$10$Qk5UScKnFlWq5iSF6EHHVeZnq8bbhZVMc8hN.2lPscU5uMoK9gnby', 'Anna Leah', 'Admin', NULL, '2026-03-24 00:24:11');

-- Constraints for dumped tables
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_ibfk_2` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE CASCADE;

COMMIT;