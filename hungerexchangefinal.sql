-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: vergil.u.washington.edu:2572
-- Generation Time: Dec 20, 2020 at 01:46 AM
-- Server version: 5.6.39
-- PHP Version: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hungerexchange`
--

-- --------------------------------------------------------

--
-- Table structure for table `contactinformation`
--

CREATE TABLE `contactinformation` (
  `idContactInformation` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phoneNumber` char(255) DEFAULT NULL,
  `FaxNumber` char(255) DEFAULT NULL,
  `socialMediaId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contactinformation`
--

INSERT INTO `contactinformation` (`idContactInformation`, `email`, `phoneNumber`, `FaxNumber`, `socialMediaId`) VALUES
(2, 'e@e.com', '111-111-1111', '111-111-1111', NULL),
(3, 'al@lol.com', '111-111-1111', '111-111-1111', NULL),
(9, '', '', '', NULL),
(11, '', '', '', NULL),
(18, '', '', '', NULL),
(26, '', '111-111-1111', '111-111-1111', NULL),
(27, '', '111-111-1111', '111-111-1111', NULL),
(28, '', '111-111-1111', '111-111-1111', NULL),
(29, '', '111-111-1111', '111-111-1111', NULL),
(30, '', '111-111-1111', '111-111-1111', NULL),
(31, '', '111-111-1111', '111-111-1111', NULL),
(32, 'abert67@gmail.com', '425-555-2020', '', NULL),
(33, '', '', '', NULL),
(34, 'markk@uw.edu', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contactsm`
--

CREATE TABLE `contactsm` (
  `idContactInformation` int(11) NOT NULL,
  `idSocialMedia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contactsm`
--

INSERT INTO `contactsm` (`idContactInformation`, `idSocialMedia`) VALUES
(32, 15),
(32, 16);

-- --------------------------------------------------------

--
-- Table structure for table `limage`
--

CREATE TABLE `limage` (
  `idLImage` int(11) NOT NULL,
  `imagePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

CREATE TABLE `listing` (
  `idListing` int(11) NOT NULL,
  `listingDesc` text,
  `listingDate` date NOT NULL,
  `bestByDate` date DEFAULT NULL,
  `city` char(255) DEFAULT NULL,
  `county` char(255) DEFAULT NULL,
  `state` char(255) DEFAULT NULL,
  `listingTitle` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `lsImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`idListing`, `listingDesc`, `listingDate`, `bestByDate`, `city`, `county`, `state`, `listingTitle`, `address1`, `address2`, `lsImage`) VALUES
(52, 'Final blackberries of the season.', '2020-12-11', '2020-12-13', 'Bothell', 'Snohomish', 'WA', 'Berry', NULL, NULL, NULL),
(53, 'Left over gourds from last harvest.', '2020-12-18', '2021-01-01', 'Greenberg', 'Chelan', 'WA', 'Assortment of Gourds', '8888 Baker Street', NULL, 'Listings/53/listingimage.jpg'),
(54, 'A large quantity of blueberries leftover after sale. This is probably the last of the season.', '2020-12-18', '2021-01-01', 'Greenberg', 'Chelan', 'WA', 'Spare Blueberries', '8888 Baker Street', NULL, 'Listings/54/listingimage.jpg'),
(55, 'There are legends told of a great potato king, and know for a fact he is very much real. Give respect while you still can.\\r\\nApply if you really like potatoes.', '2020-12-18', '2021-01-08', 'Salmon', 'Lemhi', 'ID', 'Leftover Potatoes', '6060 Sky Avenue', NULL, 'Listings/55/listingimage.jpg'),
(56, 'It seems beans aren\\\'t really in demand this year. It must be having to stay inside for so long. Either way, I can\\\'t stop thinking about those beans.', '2020-12-18', '2021-01-08', 'Greenberg', 'Chelan', 'WA', 'Beans', '8888 Baker Street', NULL, 'Listings/56/listingimage.jpg'),
(57, 'Posting for a friend from the east coast if you need them. \\r\\nContact me if you want to speak with him.', '2020-12-18', '2021-01-08', 'Mount Holly', 'Burlington', 'WA', 'Assorted Vegetables from New Jersey', '8888 Baker Street', NULL, 'Listings/57/listingimage.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `listinglimage`
--

CREATE TABLE `listinglimage` (
  `idListing` int(11) NOT NULL,
  `idLImage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `listingproduce`
--

CREATE TABLE `listingproduce` (
  `idListing` int(11) NOT NULL,
  `idProduce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `listingproduce`
--

INSERT INTO `listingproduce` (`idListing`, `idProduce`) VALUES
(52, 8),
(53, 9),
(53, 10),
(54, 11),
(55, 12),
(56, 13);

-- --------------------------------------------------------

--
-- Table structure for table `listinguser`
--

CREATE TABLE `listinguser` (
  `idUsers` varchar(36) NOT NULL,
  `idListing` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `listinguser`
--

INSERT INTO `listinguser` (`idUsers`, `idListing`) VALUES
('test', 1),
('2b3a4220-3c04-11eb-89b0-1e92d85c740f', 52),
('a6a1b951-3615-11eb-8f7c-7a7919389406', 53),
('a6a1b951-3615-11eb-8f7c-7a7919389406', 54),
('a6a1b951-3615-11eb-8f7c-7a7919389406', 55),
('a6a1b951-3615-11eb-8f7c-7a7919389406', 56),
('a6a1b951-3615-11eb-8f7c-7a7919389406', 57);

-- --------------------------------------------------------

--
-- Table structure for table `produce`
--

CREATE TABLE `produce` (
  `idProduce` int(11) NOT NULL,
  `produceType` varchar(255) DEFAULT NULL,
  `measurementType` varchar(255) DEFAULT NULL,
  `measurementValue` double DEFAULT NULL,
  `produceName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `produce`
--

INSERT INTO `produce` (`idProduce`, `produceType`, `measurementType`, `measurementValue`, `produceName`) VALUES
(6, '', '', 1, ''),
(8, 'Fruit', 'Pt', 20, 'Blackberry'),
(9, 'Fruit', 'Pounds', 2000, 'Pumpkins'),
(10, 'Fruit', 'Pounds', 100, 'Squash'),
(11, 'Fruit', 'pounds', 100, 'Blueberries'),
(12, 'Vegetable', 'Tons', 9999, 'Potatoes'),
(13, 'Legume', 'Pounds', 90, 'Pinto Beans');

-- --------------------------------------------------------

--
-- Table structure for table `socialmedia`
--

CREATE TABLE `socialmedia` (
  `idSocialMedia` int(11) NOT NULL,
  `socialMediaLink` varchar(255) DEFAULT NULL,
  `socialMediaName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `socialmedia`
--

INSERT INTO `socialmedia` (`idSocialMedia`, `socialMediaLink`, `socialMediaName`) VALUES
(15, 'facebook.com', 'facebook'),
(16, 'instagram.com', 'instagram');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idUsers` varchar(36) NOT NULL,
  `userName` varchar(24) DEFAULT NULL,
  `passHash` varchar(255) DEFAULT NULL,
  `userDesc` text,
  `profilePicPath` varchar(255) DEFAULT NULL,
  `contactId` int(11) DEFAULT NULL,
  `userType` int(11) DEFAULT NULL,
  `pImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idUsers`, `userName`, `passHash`, `userDesc`, `profilePicPath`, `contactId`, `userType`, `pImage`) VALUES
('2b3a4220-3c04-11eb-89b0-1e92d85c740f', 'markk', '$2y$10$JmgIrvChP57o0skR1QT3s.HG.rM.qMErLFC0GHPiNyuD4HC72C5Wm', '', NULL, 34, NULL, NULL),
('5629942d-2d68-11eb-8f7c-7a7919389406', 'test', 'test', 'sss', NULL, NULL, NULL, NULL),
('7afe0d27-34ec-11eb-8f7c-7a7919389406', 'john', '$2y$10$R61gc4i/gKmgkltmOMQW0e.KYChNoOiMAumenJoCTL.mjotZWFEte', NULL, NULL, NULL, NULL, NULL),
('a6a1b951-3615-11eb-8f7c-7a7919389406', 'abe', '$2y$10$axaGWZrEWT/Harq3hSdED.LyKgipi01jPpTmaAB4yTlMl7ta5Vupm', 'I\\\'m Albert a local farmer in Washington state.', NULL, 32, NULL, 'Users/a6a1b951-3615-11eb-8f7c-7a7919389406/profileimage.jpg'),
('e365c09a-3619-11eb-8f7c-7a7919389406', 'fred', '$2y$10$EstIBMwg4jolPfveAtqUH.PM7VL4UGi.JeqBJiOvy8Yv01t64LhcG', NULL, NULL, 31, NULL, NULL),
('f4745ba5-3b45-11eb-89b0-1e92d85c740f', 'jeb', '$2y$10$mPrV2tCcNu7pIPWs4T/kZ.v0oO/9gtV1Yob9QN.Q0aZ/s1a1vAuma', '', NULL, 33, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

CREATE TABLE `usertype` (
  `idUserType` int(11) NOT NULL,
  `userDesc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usertype`
--

INSERT INTO `usertype` (`idUserType`, `userDesc`) VALUES
(0, NULL),
(1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contactinformation`
--
ALTER TABLE `contactinformation`
  ADD PRIMARY KEY (`idContactInformation`),
  ADD KEY `contactGenIndex` (`email`,`phoneNumber`);

--
-- Indexes for table `contactsm`
--
ALTER TABLE `contactsm`
  ADD PRIMARY KEY (`idContactInformation`,`idSocialMedia`),
  ADD KEY `sm_fk_contactsm` (`idSocialMedia`);

--
-- Indexes for table `limage`
--
ALTER TABLE `limage`
  ADD PRIMARY KEY (`idLImage`);

--
-- Indexes for table `listing`
--
ALTER TABLE `listing`
  ADD PRIMARY KEY (`idListing`),
  ADD KEY `CITYINDEX` (`city`),
  ADD KEY `COUNTYINDEX` (`county`),
  ADD KEY `STATEINDEX` (`state`);

--
-- Indexes for table `listinglimage`
--
ALTER TABLE `listinglimage`
  ADD PRIMARY KEY (`idListing`,`idLImage`),
  ADD KEY `idLImage_idx` (`idLImage`);

--
-- Indexes for table `listingproduce`
--
ALTER TABLE `listingproduce`
  ADD PRIMARY KEY (`idListing`,`idProduce`),
  ADD KEY `idProduce_idx` (`idProduce`);

--
-- Indexes for table `listinguser`
--
ALTER TABLE `listinguser`
  ADD PRIMARY KEY (`idUsers`,`idListing`),
  ADD KEY `Listinghold_idx` (`idListing`);

--
-- Indexes for table `produce`
--
ALTER TABLE `produce`
  ADD PRIMARY KEY (`idProduce`);

--
-- Indexes for table `socialmedia`
--
ALTER TABLE `socialmedia`
  ADD PRIMARY KEY (`idSocialMedia`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUsers`),
  ADD UNIQUE KEY `idUsers_UNIQUE` (`idUsers`),
  ADD KEY `userType_idx` (`userType`),
  ADD KEY `contactInfo_idx` (`contactId`),
  ADD KEY `usernameIndex` (`userName`);

--
-- Indexes for table `usertype`
--
ALTER TABLE `usertype`
  ADD PRIMARY KEY (`idUserType`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contactinformation`
--
ALTER TABLE `contactinformation`
  MODIFY `idContactInformation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `listing`
--
ALTER TABLE `listing`
  MODIFY `idListing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `produce`
--
ALTER TABLE `produce`
  MODIFY `idProduce` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `socialmedia`
--
ALTER TABLE `socialmedia`
  MODIFY `idSocialMedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contactsm`
--
ALTER TABLE `contactsm`
  ADD CONSTRAINT `contactsm_ibfk_1` FOREIGN KEY (`idContactInformation`) REFERENCES `contactinformation` (`idContactInformation`),
  ADD CONSTRAINT `sm_fk_contactsm` FOREIGN KEY (`idSocialMedia`) REFERENCES `socialmedia` (`idSocialMedia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `listinglimage`
--
ALTER TABLE `listinglimage`
  ADD CONSTRAINT `idLImage` FOREIGN KEY (`idLImage`) REFERENCES `limage` (`idLImage`),
  ADD CONSTRAINT `idListingBridge` FOREIGN KEY (`idListing`) REFERENCES `listing` (`idListing`);

--
-- Constraints for table `listingproduce`
--
ALTER TABLE `listingproduce`
  ADD CONSTRAINT `FK_listing` FOREIGN KEY (`idListing`) REFERENCES `listing` (`idListing`),
  ADD CONSTRAINT `FK_produce` FOREIGN KEY (`idProduce`) REFERENCES `produce` (`idProduce`),
  ADD CONSTRAINT `idListing` FOREIGN KEY (`idListing`) REFERENCES `listing` (`idListing`),
  ADD CONSTRAINT `idProduce` FOREIGN KEY (`idProduce`) REFERENCES `produce` (`idProduce`);

--
-- Constraints for table `listinguser`
--
ALTER TABLE `listinguser`
  ADD CONSTRAINT `FK_listinglisting` FOREIGN KEY (`idListing`) REFERENCES `listing` (`idListing`),
  ADD CONSTRAINT `FK_listinguser` FOREIGN KEY (`idUsers`) REFERENCES `users` (`idUsers`),
  ADD CONSTRAINT `idUsers` FOREIGN KEY (`idUsers`) REFERENCES `users` (`idUsers`),
  ADD CONSTRAINT `listingId` FOREIGN KEY (`idListing`) REFERENCES `listing` (`idListing`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `contactInfo` FOREIGN KEY (`contactId`) REFERENCES `contactinformation` (`idContactInformation`),
  ADD CONSTRAINT `userType` FOREIGN KEY (`userType`) REFERENCES `usertype` (`idUserType`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
