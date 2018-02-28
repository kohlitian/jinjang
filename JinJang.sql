-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 28, 2018 at 03:52 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `JinJang`
--

-- --------------------------------------------------------

--
-- Table structure for table `JobFinder`
--

CREATE TABLE `JobFinder` (
  `userID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contactNo` int(11) NOT NULL,
  `experienceHistory` varchar(255) NOT NULL,
  `educationLevel` enum('Primary School','High School','Diploma','Degree','Master','PhD','','ALevel','Foundation') NOT NULL,
  `expectedSalary` double NOT NULL,
  `skills` varchar(255) NOT NULL,
  `languages` enum('Mandarin','Bahasa Malaysia','English','Indonesian','Cantonese','Hokkien','Hakka') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `JobProvider`
--

CREATE TABLE `JobProvider` (
  `userID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contactNo` varchar(255) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `companyAddress` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Jobs`
--

CREATE TABLE `Jobs` (
  `jobID` int(11) NOT NULL,
  `jobTitle` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `requirement` varchar(255) NOT NULL,
  `hourlyRate` double NOT NULL,
  `location` varchar(255) NOT NULL,
  `postDateTime` int(11) NOT NULL,
  `startDateTime` int(11) NOT NULL,
  `endDateTime` int(11) NOT NULL,
  `deadlineDays` int(11) NOT NULL,
  `maxParticipant` int(11) NOT NULL,
  `noParticipant` int(11) NOT NULL,
  `status` enum('Available','Cancelled','Passed') NOT NULL,
  `jpID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `requestedJobs`
--

CREATE TABLE `requestedJobs` (
  `requestID` int(11) NOT NULL,
  `jfID` int(11) NOT NULL,
  `jobID` int(11) NOT NULL,
  `status` enum('Requested','Accepted','Rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Review`
--

CREATE TABLE `Review` (
  `reviewID` int(11) NOT NULL,
  `timeStamp` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `jfID` int(11) NOT NULL,
  `jobID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `JobFinder`
--
ALTER TABLE `JobFinder`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `JobProvider`
--
ALTER TABLE `JobProvider`
  ADD PRIMARY KEY (`userID`);

--
-- Indexes for table `Jobs`
--
ALTER TABLE `Jobs`
  ADD PRIMARY KEY (`jobID`);

--
-- Indexes for table `requestedJobs`
--
ALTER TABLE `requestedJobs`
  ADD PRIMARY KEY (`requestID`);

--
-- Indexes for table `Review`
--
ALTER TABLE `Review`
  ADD PRIMARY KEY (`reviewID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `JobFinder`
--
ALTER TABLE `JobFinder`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `JobProvider`
--
ALTER TABLE `JobProvider`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Jobs`
--
ALTER TABLE `Jobs`
  MODIFY `jobID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `requestedJobs`
--
ALTER TABLE `requestedJobs`
  MODIFY `requestID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Review`
--
ALTER TABLE `Review`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
