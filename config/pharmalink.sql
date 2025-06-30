-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 07:32 AM
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
-- Database: `pharmalink`
--

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `donor_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `donation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `batch_number` varchar(50) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `medicine_id`, `quantity`, `expiry_date`, `batch_number`, `date_added`) VALUES
(1, 1, 2, '2025-06-03', '21233', '2025-06-29 18:00:23'),
(2, 1, 2, '2025-06-03', '21233', '2025-06-29 18:01:36'),
(3, 1, 2, '2025-06-03', '21233', '2025-06-29 18:02:20');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `email` varchar(255) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `last_attempt` datetime DEFAULT current_timestamp(),
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`email`, `attempts`, `last_attempt`, `locked_until`) VALUES
('\' or 1=1--', 1, '2025-06-16 14:24:00', NULL),
('test@gmail.com', 2, '2025-06-11 15:12:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `match_id` int(11) NOT NULL,
  `donation_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `quantity_matched` int(11) NOT NULL,
  `match_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `name`, `description`, `category`, `unit`) VALUES
(1, 'robust ', 'for adults only yes', 'stimulant ', 'tablet '),
(4, 'paracetamol ', '10 old and above ', 'pain reliever ', 'tablet ');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `requester_name` varchar(100) DEFAULT NULL,
  `quantity_requested` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `fName` varchar(100) NOT NULL,
  `lName` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','donor','recipient','staff') NOT NULL,
  `email` varchar(255) NOT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `lockout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `fName`, `lName`, `password`, `role`, `email`, `failed_attempts`, `lockout_time`) VALUES
(1, 'arvin', 'padua', '12345678', 'admin', 'a@gmail.com', 0, NULL),
(2, 'arvs', 'pads', '$2y$10$cVzI0yLaqjz5LBGamIII3.ZhB9wK40NO7H91kh1WxOujjijvoWk0e', 'admin', 'ar@gmail.com', 0, NULL),
(3, 'rico', 'joshy', '$2y$10$lt4YWUbyiYwvCHHR2zQ39elvWgTiImABO.Y.veehzV6bJYI.uIkXq', 'admin', 'jr@gmail.com', 0, NULL),
(4, 'test', 'testes', '$2y$10$KZSrd.g6XbfdW4lwn06k0.wLkz73YSvz3fIFEBOTgkjWxnSnYgzLC', 'admin', 'test@gmail.com', 0, NULL),
(5, 'Johnny', 'Sins', '$2y$10$yV/It8/gYNEXTNkpIgyKEeVwJUl33hoG5e.aePkQINKetNpxTStkG', 'admin', 'johnny@gmail.com', 0, NULL),
(6, 'Joshua ', 'rico', '$2y$10$7NZ41YkFqkWR00qzW8u4MuyLG/Ypmv.6HuO8JM6mVGrgcYLYBHd.C', 'admin', 'joshuarico927@gmail.com', 0, NULL),
(7, 'kad', 'kad', '$2y$10$65oge8YAmovc2Gwl35nNwukTHiPqtsvSyHRDByeGwbd04e0VsDUFC', 'admin', 'kad@gmail.com', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `donation_id` (`donation_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`);

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`donation_id`) REFERENCES `donations` (`donation_id`),
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `requests` (`request_id`);

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
