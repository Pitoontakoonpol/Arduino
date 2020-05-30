-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 11, 2020 at 05:56 AM
-- Server version: 5.5.54
-- PHP Version: 5.3.10-1ubuntu3.26

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hhm`
--

-- --------------------------------------------------------

--
-- Table structure for table `online_machine`
--

CREATE TABLE IF NOT EXISTS `online_machine` (
  `ID` int(11) NOT NULL,
  `MachineID` int(10) NOT NULL,
  `CameraID1` int(10) DEFAULT NULL,
  `CameraID2` int(10) DEFAULT NULL,
  `CameraID3` int(10) DEFAULT NULL,
  `Play_Mode` int(2) DEFAULT NULL,
  `Grab_Weight` int(6) NOT NULL DEFAULT '666666',
  `Max_Second` int(4) NOT NULL DEFAULT '30',
  `Category` char(100) DEFAULT NULL,
  `Title` char(50) DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `Location` char(50) DEFAULT NULL,
  `Tag` char(255) DEFAULT NULL,
  `Active` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `online_machine`
--

INSERT INTO `online_machine` (`ID`, `MachineID`, `CameraID1`, `CameraID2`, `CameraID3`, `Play_Mode`, `Grab_Weight`, `Max_Second`, `Category`, `Title`, `Price`, `Location`, `Tag`, `Active`) VALUES
(1, 2000001, 0, 0, NULL, 1, 888888, 35, 'A', 'Marvel Memory Foam', 65, NULL, NULL, 1),
(2, 2000002, 0, 0, NULL, 1, 888888, 30, 'A', 'Sanrio Cute doll', 55, NULL, NULL, 1),
(3, 2000003, 0, 0, NULL, 1, 888888, 30, 'A', 'Merida Princess', 52, NULL, NULL, 1),
(4, 2000004, 20200002, 20200001, NULL, 1, 888888, 30, 'A', 'Stitch Candy Big', 16, NULL, NULL, 1),
(5, 2000005, 0, 0, NULL, 1, 888888, 30, 'A', 'Marvel LOKI', 33, NULL, NULL, 1),
(6, 2000006, 20200001, 20200002, NULL, 1, 888888, 30, 'A', 'Baby Bloo - Fluffy', 44, NULL, NULL, 1),
(7, 2000007, 20200001, 20200002, NULL, 1, 888888, 30, 'A', 'Rilakkuma - Fluffy Face', 55, NULL, NULL, 1),
(8, 2000008, 20200001, 20200002, NULL, 1, 888888, 30, 'A', 'Micky Mouse Apple Plushy', 55, NULL, NULL, 1),
(9, 2000009, 20200001, 20200002, NULL, 1, 888888, 30, 'A', 'Koupen Chan', 55, NULL, NULL, 1),
(10, 2000010, 20200001, 20200002, NULL, 1, 888888, 30, 'A', 'Dragon Ball Super', 66, NULL, NULL, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
