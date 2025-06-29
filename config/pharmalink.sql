-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2025 at 06:59 PM
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
(5, 'Johnny', 'Sins', '$2y$10$yV/It8/gYNEXTNkpIgyKEeVwJUl33hoG5e.aePkQINKetNpxTStkG', 'admin', 'johnny@gmail.com', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`email`);

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
