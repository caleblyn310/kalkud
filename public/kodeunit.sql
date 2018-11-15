-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 06, 2018 at 06:37 AM
-- Server version: 5.7.19
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akuntansi`
--

-- --------------------------------------------------------

--
-- Table structure for table `kodeunit`
--

CREATE TABLE `kodeunit` (
  `id` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `kepsek` varchar(100) DEFAULT NULL,
  `plafon` int(11) DEFAULT NULL,
  `short` varchar(5) NOT NULL,
  `middle` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kodeunit`
--

INSERT INTO `kodeunit` (`id`, `unit`, `kepsek`, `plafon`, `short`, `middle`) VALUES
(0, 'Yayasan', 'Teguh Jaya', 50000000, 'YA', 'YYSN'),
(1, 'TK Ciateul', 'Ehrlena Zakaria Lamech', 16000000, 'TK1', 'TK CTL'),
(2, 'TK Kopo Permai', 'Priska Santoso', 15000000, 'TK2', 'TK Koper'),
(3, 'TK Taman Kopo Indah', 'Ellya Tiurlan Mardiana Tambunan', 15000000, 'TK3', 'TK TKI'),
(4, 'SD Ciateul', 'Lili, S.S', 25000000, 'SD1', 'SD CTL'),
(5, 'SD Kopo Permai', 'Leny Tuwendi', 20000000, 'SD2', 'SD Koper'),
(6, 'SMP Mekar Wangi', 'Sie Andreas William', 10000000, 'SMP1', 'SMP MW'),
(7, 'SMP Kopo Permai', 'Drs. Joshua', 20000000, 'SMP2', 'SMP Koper'),
(8, 'SMA Mekar Wangi', 'Kristanto, S.Psi', 25000000, 'SMA', 'SMA MW'),
(9, 'SD Taman Kopo Indah', 'Agustiati Handayani', 15000000, 'SD3', 'SD TKI'),
(10, 'Ciateul', '', 0, 'CTL', 'Ciateul'),
(11, 'Kopo Permai', '', 0, 'KPP', 'Koper'),
(12, 'Taman Kopo Indah 2', '', 0, 'TKI', 'TKI'),
(13, 'MEKAR WANGI', '', 0, 'MW', 'MW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kodeunit`
--
ALTER TABLE `kodeunit`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
