-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 10:58 AM
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
-- Database: `join_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile`, `role`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ratikant Pradhan', 'bikipp6@gmail.com', '$2y$10$vYKI8jZ6od2YNCR4K0pDyeBtQv2VnPU.PY2Gi/GZinyeOeecnWQRa', 'uploads/mi polo.jpg', 'admin', 0, NULL, NULL, NULL),
(3, 'Biki Pradhan', 'pradhancomputersciencerati2005@gmail.com', '$2y$10$5GMxYD6/Wrpt3KeZ.tra8OmLV6qc0T6IouoC.ilq6mmaCcSdINgtO', 'uploads/mouse.jpg', 'user', 0, '2025-07-02 12:21:05', NULL, NULL),
(4, 'Anup', 'anup@gmail.com', '$2y$10$a7B1o1BJb70.w.PH36rkAujQFTprr9KJ2A289kUzByrhBiGNDkCiy', 'uploads/Keychron-K8-Pro-QMK-VIA-Wireless-Mechanical-Keyboard-for-Mac-Windows-OSA-profile-PBT-keycaps-PCB-screw-in-stabilizer-with-hot-swappable-Gateron-G-Pro-mechanical-red-switch-compatible_8603f64b-59fd-4.jpg', 'user', 1, '2025-07-03 12:51:52', NULL, NULL),
(5, 'Admin', 'admin@gmail.com', '$2y$10$CCjaCDQsoX8Rwb33WYD2Qexo3m.21rt8Tg98LHouN2J5mVMeZ3gzq', 'uploads/php.png', 'admin', 0, '2025-07-03 13:25:20', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
