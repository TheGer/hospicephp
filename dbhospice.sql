-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2014 at 09:29 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dbhospice`
--

-- --------------------------------------------------------

--
-- Table structure for table `duration`
--

CREATE TABLE IF NOT EXISTS `duration` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) DEFAULT NULL,
  `Value` varchar(45) DEFAULT NULL,
  `Price` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `duration`
--

INSERT INTO `duration` (`ID`, `Name`, `Value`, `Price`) VALUES
(1, '1 Year', '1', '10'),
(2, '3 Years', '3', '30'),
(3, 'Lifetime', '99', '50');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Salt` varchar(70) NOT NULL,
  `Role_FK` int(2) NOT NULL,
  `UserID` int(5) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`UserID`),
  KEY `LoginRole` (`Role_FK`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`Username`, `Password`, `Salt`, `Role_FK`, `UserID`) VALUES
('danjohn', '123', 'abc', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `Title_FK` int(1) DEFAULT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Surname` varchar(50) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `Street` varchar(50) DEFAULT NULL,
  `Locality` varchar(50) DEFAULT NULL,
  `Postcode` varchar(50) DEFAULT NULL,
  `IDCard` varchar(9) DEFAULT NULL,
  `Gender` bit(2) DEFAULT NULL,
  `Landline` varchar(50) DEFAULT NULL,
  `Mobile` varchar(50) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `InContact` bit(2) DEFAULT NULL,
  `TimeDeleted` timestamp NULL DEFAULT NULL,
  `RecordDeletedBy` varchar(45) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `RecordDeletedReason` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `MemberTitles_FK` (`Title_FK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE IF NOT EXISTS `membership` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PaidDate` timestamp NULL DEFAULT NULL,
  `FromDate` timestamp NULL DEFAULT NULL,
  `ToDate` timestamp NULL DEFAULT NULL,
  `PaymentMethod` varchar(45) DEFAULT NULL,
  `NumberOfYears` varchar(45) DEFAULT NULL,
  `TotalPrice` varchar(45) DEFAULT NULL,
  `MemberID` int(4) DEFAULT NULL,
  `IsRenewal` bit(1) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `MemberID_idx` (`MemberID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UnitPrice` varchar(45) DEFAULT NULL,
  `Quantity` varchar(45) DEFAULT NULL,
  `UnitDuration` varchar(45) DEFAULT NULL,
  `MemberID` int(4) DEFAULT NULL,
  `MembershipID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `MemberID_idx` (`MemberID`),
  KEY `MembershipID_idx` (`MembershipID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE IF NOT EXISTS `reminder` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LastReminded` timestamp NULL DEFAULT NULL,
  `RemindedBy` varchar(255) DEFAULT NULL,
  `MembershipID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `memberID_idx` (`MembershipID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RoleName` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`ID`, `RoleName`) VALUES
(1, 'Admin'),
(2, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `titles`
--

CREATE TABLE IF NOT EXISTS `titles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `titles`
--

INSERT INTO `titles` (`ID`, `Title`) VALUES
(1, 'Mr.'),
(2, 'Mrs.'),
(3, 'Ms.'),
(4, 'Miss'),
(5, 'Dr.'),
(6, 'Prof.'),
(7, 'Rev.'),
(8, 'Sir.'),
(9, 'Other');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `membership`
--
ALTER TABLE `membership`
  ADD CONSTRAINT `mem` FOREIGN KEY (`MemberID`) REFERENCES `members` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `d` FOREIGN KEY (`MembershipID`) REFERENCES `membership` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `m` FOREIGN KEY (`MemberID`) REFERENCES `members` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `memberID` FOREIGN KEY (`MembershipID`) REFERENCES `membership` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
