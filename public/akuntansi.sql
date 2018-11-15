-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 20, 2018 at 01:08 AM
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
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `id` int(11) NOT NULL,
  `bank` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`id`, `bank`) VALUES
(1, 'BCA'),
(2, 'BDI');

-- --------------------------------------------------------

--
-- Table structure for table `invnolist`
--

CREATE TABLE `invnolist` (
  `invoices_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invnolist`
--

INSERT INTO `invnolist` (`invoices_no`) VALUES
(3406);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoices_no` varchar(20) NOT NULL,
  `bank` int(11) NOT NULL,
  `pay_to` varchar(50) NOT NULL,
  `give_to` varchar(50) NOT NULL,
  `dot` datetime NOT NULL,
  `nominal` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('s','p') NOT NULL DEFAULT 's',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoices_no`, `bank`, `pay_to`, `give_to`, `dot`, `nominal`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(9, '20180213014604', 1, 'abc', 'abc', '2018-02-12 00:00:00', 500000, 100, 'p', '2018-02-12 19:40:39', '2018-02-12 19:52:06'),
(10, '20180213025243', 1, 'caleb', 'caleb', '2018-02-01 00:00:00', 50000, 100, 'p', '2018-02-12 19:53:47', '2018-02-12 19:53:47'),
(11, '20180213031804', 2, 'abca', 'abca', '2018-02-02 00:00:00', 5000, 100, 'p', '2018-02-12 20:21:12', '2018-02-12 20:21:12'),
(12, '20180213033034', 1, 'bcar', 'bcar', '2018-02-13 00:00:00', 5004, 100, 'p', '2018-02-12 20:33:21', '2018-02-12 20:33:21'),
(13, '20180213063134', 1, 'queen', 'queen', '2018-02-13 00:00:00', 15000000, 100, 'p', '2018-02-12 23:32:47', '2018-02-12 23:32:47'),
(14, '20180222012216', 1, 'caleb', 'caleb', '2018-02-22 00:00:00', 100000, 100, 'p', '2018-02-21 20:31:26', '2018-02-21 20:31:26'),
(15, '20180222033301', 1, 'piepin', 'piepin', '2018-02-22 00:00:00', 25000, 100, 'p', '2018-02-21 20:33:25', '2018-02-21 20:33:25'),
(16, '20180222033414', 1, 'marria', 'marria', '2018-02-22 00:00:00', 50000, 100, 'p', '2018-02-21 20:34:32', '2018-02-21 20:34:32'),
(17, '20180222033525', 1, 'caleb', 'caleb', '2018-02-22 00:00:00', 100000, 100, 'p', '2018-02-21 20:35:44', '2018-02-21 20:35:44'),
(18, '20180222033624', 1, 'sulis', 'sulis', '2018-02-22 00:00:00', 15000, 100, 'p', '2018-02-21 20:36:40', '2018-02-21 20:36:40'),
(19, '20180222033718', 1, 'caleb', 'caleb', '2018-02-22 00:00:00', 10000, 100, 'p', '2018-02-21 20:37:32', '2018-02-21 20:37:32'),
(20, '20180222033936', 2, 'queen', 'queen', '2018-02-22 00:00:00', 124000, 100, 'p', '2018-02-21 20:39:56', '2018-02-21 20:39:56'),
(21, '20180222034924', 1, 'piepin', 'piepin', '2018-02-22 00:00:00', 100000, 100, 'p', '2018-02-21 20:49:40', '2018-02-21 20:49:40'),
(22, '20180222045529', 2, 'caleb', 'caleb', '2018-02-01 00:00:00', 5000000, 100, 'p', '2018-02-21 21:56:05', '2018-02-21 21:56:05'),
(23, '20180226044402', 2, 'piepin', 'piepin', '2018-02-05 00:00:00', 1500200, 100, 'p', '2018-02-25 21:44:37', '2018-02-25 21:44:37'),
(25, '3405', 1, 'marria', 'marria', '2018-03-02 00:00:00', 50000, 100, 's', '2018-03-01 21:00:13', '2018-03-01 21:00:13');

-- --------------------------------------------------------

--
-- Table structure for table `invoices_detail`
--

CREATE TABLE `invoices_detail` (
  `id` int(11) NOT NULL,
  `kode_coa` varchar(10) DEFAULT NULL,
  `invoices_no` varchar(14) NOT NULL,
  `description` varchar(150) NOT NULL,
  `nominal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoices_detail`
--

INSERT INTO `invoices_detail` (`id`, `kode_coa`, `invoices_no`, `description`, `nominal`, `created_at`, `updated_at`) VALUES
(1, NULL, '20180208025022', 'fsdfafsafafdsa', 12321312, '2018-02-07 20:01:56', '2018-02-07 20:01:56'),
(2, NULL, '20180208035151', 'fsdafdsafsa', 312312, '2018-02-07 20:52:05', '2018-02-07 20:52:05'),
(3, NULL, '20180208042659', 'fsafdafsa', 123456, '2018-02-07 21:27:08', '2018-02-07 21:27:08'),
(4, NULL, '20180208042659', 'fsafdafsa', 123456, '2018-02-07 21:29:00', '2018-02-07 21:29:00'),
(5, NULL, '20180208042914', 'dsfasf', 12321312, '2018-02-07 21:29:22', '2018-02-07 21:29:22'),
(6, NULL, '20180208045826', 'dsfasdfa', 1232132132, '2018-02-07 22:03:45', '2018-02-07 22:03:45'),
(7, NULL, '20180208045826', 'dsfasdfa', 1232132132, '2018-02-07 22:52:15', '2018-02-07 22:52:15'),
(8, NULL, '20180208055246', 'dsfadfsaf', 232131231, '2018-02-07 22:52:51', '2018-02-07 22:52:51'),
(9, NULL, '20180208055336', 'fsafa', 1312312312, '2018-02-07 22:53:41', '2018-02-07 22:53:41'),
(10, NULL, '20180208055443', 'fsafsadfa', 1232131231, '2018-02-07 22:54:48', '2018-02-07 22:54:48'),
(11, NULL, '20180208055550', '32fdsfsafdafsa', 423423, '2018-02-07 22:55:56', '2018-02-07 22:55:56'),
(43, NULL, '20180213014604', 'steak', 500000, '2018-02-12 19:18:13', '2018-02-12 19:52:05'),
(48, NULL, '20180213025243', 'parkir', 500, '2018-02-12 20:18:14', '2018-02-12 20:18:14'),
(49, NULL, '20180213031804', 'mangga', 5000, '2018-02-12 20:20:53', '2018-02-12 20:21:10'),
(51, NULL, '20180213033034', 'teh', 5004, '2018-02-12 20:31:41', '2018-02-12 20:33:15'),
(52, NULL, '20180213063134', 'makan', 5000000, '2018-02-12 23:32:10', '2018-02-12 23:32:10'),
(53, NULL, '20180213063134', 'minum', 10000000, '2018-02-12 23:32:41', '2018-02-12 23:32:41'),
(54, NULL, '20180222012216', 'makan', 100000, '2018-02-21 19:52:11', '2018-02-21 19:52:11'),
(55, NULL, '20180222033301', 'minum', 25000, '2018-02-21 20:33:20', '2018-02-21 20:33:20'),
(56, NULL, '20180222033414', 'tidur', 50000, '2018-02-21 20:34:29', '2018-02-21 20:34:29'),
(57, NULL, '20180222033525', 'cuci cuci', 100000, '2018-02-21 20:35:41', '2018-02-21 20:35:41'),
(58, NULL, '20180222033624', 'nasi goreng', 15000, '2018-02-21 20:36:39', '2018-02-21 20:36:39'),
(59, NULL, '20180222033718', 'minum', 10000, '2018-02-21 20:37:30', '2018-02-21 20:37:30'),
(60, NULL, '20180222033936', 'steak', 124000, '2018-02-21 20:39:52', '2018-02-21 20:39:52'),
(61, NULL, '20180222034924', 'ban bocor', 100000, '2018-02-21 20:49:38', '2018-02-21 20:49:38'),
(62, NULL, '20180222045529', 'tiket pesawat', 5000000, '2018-02-21 21:56:02', '2018-02-21 21:56:02'),
(63, NULL, '20180226044402', 'monitor komputer LG', 1500200, '2018-02-25 21:44:35', '2018-02-25 21:44:35'),
(65, NULL, '3405', 'juice', 50000, '2018-03-01 21:00:12', '2018-03-01 21:00:12');

-- --------------------------------------------------------

--
-- Table structure for table `inv_no_temp`
--

CREATE TABLE `inv_no_temp` (
  `id` int(11) NOT NULL,
  `invoices_no` varchar(14) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('o','c','s') NOT NULL DEFAULT 'o',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inv_no_temp`
--

INSERT INTO `inv_no_temp` (`id`, `invoices_no`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(11, '20180213014604', 100, 's', '2018-02-12 18:46:04', '2018-02-12 18:46:04'),
(18, '20180213025243', 100, 's', '2018-02-12 19:52:43', '2018-02-12 19:52:43'),
(19, '20180213031804', 100, 's', '2018-02-12 20:18:04', '2018-02-12 20:18:04'),
(20, '20180213033034', 100, 's', '2018-02-12 20:30:34', '2018-02-12 20:30:34'),
(23, '20180213063134', 100, 's', '2018-02-12 23:31:34', '2018-02-12 23:31:34'),
(24, '20180222012216', 100, 's', '2018-02-21 18:22:16', '2018-02-21 18:22:16'),
(25, '20180222033301', 100, 's', '2018-02-21 20:33:01', '2018-02-21 20:33:01'),
(26, '20180222033414', 100, 's', '2018-02-21 20:34:14', '2018-02-21 20:34:14'),
(27, '20180222033525', 100, 's', '2018-02-21 20:35:25', '2018-02-21 20:35:25'),
(28, '20180222033624', 100, 's', '2018-02-21 20:36:24', '2018-02-21 20:36:24'),
(29, '20180222033718', 100, 's', '2018-02-21 20:37:18', '2018-02-21 20:37:18'),
(30, '20180222033936', 100, 's', '2018-02-21 20:39:36', '2018-02-21 20:39:36'),
(31, '20180222034924', 100, 's', '2018-02-21 20:49:24', '2018-02-21 20:49:24'),
(33, '20180222045529', 100, 's', '2018-02-21 21:55:29', '2018-02-21 21:55:29'),
(34, '20180226044402', 100, 's', '2018-02-25 21:44:02', '2018-02-25 21:44:02'),
(39, '3405', 100, 's', '2018-03-01 20:59:59', '2018-03-01 20:59:59'),
(40, '3406', 100, 'o', '2018-03-01 21:00:26', '2018-03-01 21:00:26'),
(41, '3406', 0, 'o', '2018-03-19 18:00:17', '2018-03-19 18:00:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invnolist`
--
ALTER TABLE `invnolist`
  ADD PRIMARY KEY (`invoices_no`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_no` (`invoices_no`);

--
-- Indexes for table `invoices_detail`
--
ALTER TABLE `invoices_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_no_temp`
--
ALTER TABLE `inv_no_temp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invnolist`
--
ALTER TABLE `invnolist`
  MODIFY `invoices_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3407;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `invoices_detail`
--
ALTER TABLE `invoices_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `inv_no_temp`
--
ALTER TABLE `inv_no_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
