-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 02:26 PM
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
-- Database: `cipher_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `susi_tb`
--

CREATE TABLE `susi_tb` (
  `id` int(11) NOT NULL,
  `letter` char(1) NOT NULL,
  `substitute` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `susi_tb`
--

INSERT INTO `susi_tb` (`id`, `letter`, `substitute`) VALUES
(1, 'a', '9'),
(2, 'b', '1'),
(3, 'c', 'e'),
(4, 'd', 'r'),
(5, 'e', 't'),
(6, 'f', 'y'),
(7, 'g', 'u'),
(8, 'h', '8'),
(9, 'i', 'o'),
(11, 'k', 'a'),
(12, 'l', 's'),
(13, 'm', '~'),
(14, 'n', '#'),
(15, 'o', 'g'),
(16, 'p', 'h'),
(17, 'q', '5'),
(18, 'r', 'k'),
(19, 's', 'l'),
(20, 't', 'z'),
(21, 'u', 'x'),
(22, 'v', 'c'),
(23, 'w', '2'),
(24, 'x', 'b'),
(25, 'y', '3'),
(26, 'z', '6'),
(27, '1', 'f'),
(28, '2', 'i'),
(29, '3', 'm'),
(30, '4', 'n'),
(31, '5', 'q'),
(32, '6', 'v'),
(33, '7', 'w'),
(34, '8', '4'),
(35, '9', '!'),
(45, '0', ';'),
(1695, 'j', '7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `susi_tb`
--
ALTER TABLE `susi_tb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `susi_tb`
--
ALTER TABLE `susi_tb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1696;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
