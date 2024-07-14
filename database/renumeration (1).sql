-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2024 at 10:43 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `renumeration`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '1234');

--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `date` varchar(50) NOT NULL,
  `department` text(50) NOT NULL,
  `semester` varchar(50) NOT NULL,
  `sub_name` text(255) NOT NULL,
  `sub_code` text(40) NOT NULL,
  `students` varchar(255) NOT NULL,
  `student_reg` varchar(50) NOT NULL,
  `in_Id` varchar(15) NOT NULL,
  `in_renum` varchar(15) NOT NULL,
  `in_name` text(50) NOT NULL,
  `in_designation` text(50) NOT NULL,
  `in_college` varchar(50) NOT NULL,
  `in_acc_name` text(50) NOT NULL,
  `in_acc_no` varchar(50) NOT NULL,
  `in_tot`int(50) NOT NULL,
  `lab_Id` varchar(15) NOT NULL,
  `lab_renum` varchar(15) NOT NULL,
  `lab_name` text(50) NOT NULL,
  `lab_designation` text(50) NOT NULL,
  `lab_college` varchar(50) NOT NULL,
  `lab_acc_name` text(50) NOT NULL,
  `lab_acc_no` varchar(50) NOT NULL,
  `lab_tot`int(50) NOT NULL,
  `sk_Id` varchar(15) NOT NULL,
  `sk_renum` varchar(15) NOT NULL,
  `sk_tot`int(50) NOT NULL,
  `sk_name` text(50) NOT NULL,
  `sk_designation` text(50) NOT NULL,
  `sk_college` varchar(50) NOT NULL,
  `sk_acc_name` text(50) NOT NULL,
  `sk_acc_no` varchar(50) NOT NULL,
  `ex_tot`int(50) NOT NULL,
  `ex_renum`varchar(15) NOT NULL,
  `ex_lump` varchar(15) NOT NULL,
  `ex_session` text(50) NOT NULL,
  `ox_s`varchar(15) NOT NULL,
  `ex_name` text(50) NOT NULL,
  `ex_designation` text(50) NOT NULL,
  `ex_college` varchar(50) NOT NULL,
  `ex_acc_name` text(50) NOT NULL,
  `ex_acc_no` varchar(50) NOT NULL,
  `ex_bank_name` varchar(50) NOT NULL,
  `ex_branch` text(50) NOT NULL,
  `ex_ifsc` varchar(15) NOT NULL,
  `ex_num` varchar(15) NOT NULL,
  `lab_category` text(15) NOT NULL,
  `batch_no` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `internals`
--

CREATE TABLE `internals` (
  `iid` int(11) NOT NULL,
  `faculty_Id` varchar(50) NOT NULL,
  `fac_type` varchar(50) NOT NULL,
  `staff_name` text(100) NOT NULL,
  `designation` text(50) NOT NULL,
 `acc_name` text(50) NOT NULL,
 `acc_no` varchar(50) NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `internals`
--
CREATE TABLE `externals` (
  `eid` int(11) NOT NULL,
  `staff_name` text(100) NOT NULL,
  `designation` text(50) NOT NULL,
  `clg_name` varchar(50) NOT NULL,
 `acc_name` text(50) NOT NULL,
 `acc_no` varchar(50) NOT NULL,
 `bank_name` varchar(50) NOT NULL,
 `branch`text(50) NOT NULL,
 `ifsc` varchar(50) NOT NULL,
 `mob_no` varchar(15) NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `internals`
--
ALTER TABLE `internals`
  ADD PRIMARY KEY (`iid`);
  --
  -- Indexes for table `internals`
  --
ALTER TABLE `externals`
  ADD PRIMARY KEY (`eid`);
--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `internals`
--
ALTER TABLE `internals`
  MODIFY `iid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

ALTER TABLE `externals`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
