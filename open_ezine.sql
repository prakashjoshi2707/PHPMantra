-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--   @
-- Host: localhost:3306
-- Generation Time: Jul 01, 2020 at 10:53 AM
-- Server version: 8.0.20-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `open_ezine`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblpayment`
--

CREATE TABLE `tblpayment` (
  `id` int NOT NULL,
  `payment_request_id` varchar(250)   DEFAULT NULL,
  `payment_id` varchar(250)   DEFAULT NULL,
  `buyer_name` varchar(255)   DEFAULT NULL,
  `buyer_email` varchar(200)   DEFAULT NULL,
  `buyer_phone` varchar(20)   DEFAULT NULL,
  `currency` varchar(50)   DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `fees` int DEFAULT NULL,
  `purpose` text  ,
  `longurl` text  ,
  `mac` text  ,
  `shorturl` text  ,
  `status` varchar(50)   DEFAULT NULL,
  `email_status` varchar(50) DEFAULT NULL,
  `sms_status` varchar(50) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `createdFrom` varchar(80) DEFAULT NULL,
  `createdBy` varchar(200) DEFAULT NULL
);

--
-- Dumping data for table `tblpayment`
--



--
-- Indexes for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblpayment`
--
ALTER TABLE `tblpayment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
