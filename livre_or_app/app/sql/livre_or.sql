-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2025 at 04:34 AM
-- Server version: 8.0.33
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `livre_or`
--

-- --------------------------------------------------------

--
-- Table structure for table `livre_or_cards`
--

CREATE TABLE `livre_or_cards` (
  `livre_or_id` int UNSIGNED NOT NULL,
  `livre_or_name` varchar(255) NOT NULL,
  `livre_or_club_name` varchar(255) NOT NULL,
  `livre_or_message` text NOT NULL,
  `livre_or_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `livre_or_city` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `livre_or_cards`
--

INSERT INTO `livre_or_cards` (`livre_or_id`, `livre_or_name`, `livre_or_club_name`, `livre_or_message`, `livre_or_image`, `livre_or_city`, `created_at`) VALUES
(30, 'Lauviah VLAVONOU', 'Myahitcompany', 'MCA ACADEMY est une académie de formation en cybersécurité qui s’est donné pour mission de former les professionnels, étudiants, et passionnés de la cybersécurité au Bénin et dans le monde.', NULL, 'Agla', '2025-06-14 04:54:57'),
(31, 'Lauviah VLAVONOU', 'Myahitcompany', 'MCA ACADEMY est une académie de formation en cybersécurité qui s’est donné pour mission de former les professionnels, étudiants, et passionnés de la cybersécurité au Bénin et dans le monde.', 'livre_or_Mca-academy.png', 'Agla', '2025-06-14 04:55:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `livre_or_cards`
--
ALTER TABLE `livre_or_cards`
  ADD PRIMARY KEY (`livre_or_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `livre_or_cards`
--
ALTER TABLE `livre_or_cards`
  MODIFY `livre_or_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
