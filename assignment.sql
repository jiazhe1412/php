-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2024 at 10:01 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookinglist`
--

CREATE TABLE `bookinglist` (
  `bookingID` varchar(10) NOT NULL,
  `memberID` varchar(10) NOT NULL,
  `eventID` varchar(10) NOT NULL,
  `ticketNumberPurchase` int(10) NOT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `eventPurchaseDate` varchar(10) NOT NULL,
  `countPrice` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookingrecord`
--

CREATE TABLE `bookingrecord` (
  `ticketID` varchar(10) NOT NULL,
  `paymentID` varchar(10) NOT NULL,
  `eventID` varchar(10) NOT NULL,
  `ticketQty` int(3) NOT NULL,
  `totalPrice` decimal(8,2) NOT NULL,
  `bookingDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookingrecord`
--

INSERT INTO `bookingrecord` (`ticketID`, `paymentID`, `eventID`, `ticketQty`, `totalPrice`, `bookingDate`) VALUES
('T1001', 'P1001', 'E1006', 1, '12.00', '2023-05-15'),
('T1002', 'P1002', 'E1005', 1, '10.00', '2023-05-15'),
('T1003', 'P1004', 'E1006', 1, '12.00', '2023-05-16'),
('T1004', 'P1005', 'E1003', 1, '10.00', '2023-05-16'),
('T1005', 'P1007', 'E1001', 1, '10.00', '2023-05-17'),
('T1006', 'P1008', 'E1001', 1, '10.00', '2023-05-17'),
('T1007', 'P1010', 'E1003', 1, '10.00', '2023-05-09'),
('T1008', 'P1011', 'E1001', 1, '10.00', '2023-05-01'),
('T1009', 'P1013', 'E1001', 1, '10.00', '2023-05-01'),
('T1010', 'P1013', 'E1003', 1, '10.00', '2023-05-09'),
('T1011', 'P1014', 'E1001', -1, '-1.00', '2023-05-24'),
('T1012', 'P1014', 'E1001', -1, '-1.00', '2023-05-24'),
('T1013', 'P1015', 'E1009', 1, '1.00', '2023-05-24'),
('T1014', 'P1016', 'E1009', 1, '1.00', '2023-05-24');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `eventID` varchar(30) NOT NULL,
  `eventName` varchar(50) NOT NULL,
  `eventPhoto` varchar(30) NOT NULL,
  `startDay` date NOT NULL,
  `endDay` date NOT NULL,
  `startTime` int(20) NOT NULL,
  `endTime` int(20) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `price` decimal(4,2) NOT NULL,
  `ticketNumber` int(4) NOT NULL,
  `venue` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`eventID`, `eventName`, `eventPhoto`, `startDay`, `endDay`, `startTime`, `endTime`, `description`, `price`, `ticketNumber`, `venue`) VALUES
('E1001', 'alisa festival', '645ef3ab1a81a.jpg', '2023-05-01', '2023-05-04', 1683933300, 1683976560, 'come to our festival and get your punishment', '10.00', 88, '77, Lorong Lembah Permai 3 11200 Bandar Tanjung Bungah Penang'),
('E1003', 'summer festival', '645ef3bca9fd9.jpeg', '2023-05-09', '2023-05-09', 1683943200, 1683952200, 'come to our festival and get your punishment', '10.00', 95, '77, Lorong Lembah Permai 3 11200 Bandar Tanjung Bungah Penang'),
('E1004', 'zhe zhe festival', '64631f39dd820.png', '2023-05-01', '2023-05-01', 1684242000, 1684254600, 'come to our festival and get your punishment', '10.00', 34, '77, Lorong Lembah Permai 3 11200 Bandar Tanjung Bungah Penang'),
('E1005', 'jj concert', '64582b5324a16.jpg', '2023-05-01', '2023-05-01', 1683510840, 1683512820, 'come to our festival and get your punishment', '10.00', 96, '77, Lorong Lembah Permai 3 11200 Bandar Tanjung Bungah Penang'),
('E1006', 'attack on titan concert', '64582c0bb88d8.jpeg', '2023-05-01', '2023-05-02', 1684316640, 1684323960, 'The music team of movie \'attack on titan\' will present at tarumt college on first day of march. Come and enjoy the music, xin zhou shasha gei you.', '12.00', 30, '77, Lorong Lembah Permai 3 11200 Bandar Tanjung Bungah Penang'),
('E1007', 'night concert', '645ef69caa12a.jpeg', '2023-05-04', '2023-05-09', 1684278960, 1684279020, 'come and join the night concert to enjoy your life', '12.00', 90, '77, Lorong Lembah Permai 3 11200 Bandar Tanjung Bungah Penang'),
('E1008', 'Xiao Gou', '64645986d5a0d.jpg', '2023-05-17', '2023-05-18', 1684278000, 1684283400, 'the concert that have dog', '12.00', 10, '77, Lorong Lembah Permai 3 11200 Bandar Tanjung Bungah Penang'),
('E1009', 'genshin', '646480ff6891a.png', '2023-05-24', '2023-05-24', 1715743440, 1715786700, 'deedededed', '1.00', 100, '138,taman seri emas');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedbackID` varchar(10) NOT NULL,
  `eventID` varchar(10) NOT NULL,
  `memberID` varchar(10) NOT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(30) NOT NULL,
  `feedback` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedbackID`, `eventID`, `memberID`, `name`, `email`, `feedback`) VALUES
('F1003', 'E1001', '', 'David', 'david@gmail.com', 'I had a blast at the Summer Music Festival! The lineup was amazing and there was such a great atmosphere. It was so nice to be able to bring my family along and there were plenty of activities for my kids to enjoy. The food trucks were also a great touch. Can\'t wait for next year\'s festival!'),
('F1004', 'E1001', '', 'Jason', 'jason123@gmail.com', 'I had an amazing time at the Summer Music Festival! The lineup was incredible, and I loved being able to see so many of my favorite artists perform live. The atmosphere was electric, and the food trucks and craft vendors were a great addition. I can\'t wait to attend next year\'s festival!'),
('F1005', 'E1001', '', 'Emily', 'emily957@hotmail.com', 'The Summer Music Festival was one of the highlights of my summer. The atmosphere was electric and the music was top-notch. It was great to be able to enjoy a live music event with other music lovers after a long time of no concerts. Overall, it was an unforgettable experience!'),
('F1006', 'E1001', '', 'Michael', 'michael402@hotmail.com', 'The Summer Music Festival was a fun family event. My kids loved the activities and the music was enjoyable for all ages. It was great to be able to bring our own blankets and chairs and relax on the lawn while listening to live music. We\'ll be back next year!'),
('F1007', 'E1002', '', 'Zoey', '929Zoey@hotmail.com', 'I had an absolute blast at the Country Music Jam! The performers were top-notch and the atmosphere was electric. I especially loved the fiddle and banjo players, they really added something special to the event'),
('F1008', 'E1002', '', 'Tyler', 'Ty23ler@hotmail.com', 'As a big fan of country music, I can confidently say that the Country Music Jam did not disappoint! The artists were fantastic and the energy in the crowd was contagious. I can\'t wait for next year\'s event!'),
('F1009', 'E1002', '', 'Jake', 'Jak23le@hotmail.com', 'I\'ve been to a lot of music festivals, but the Country Music Jam is definitely one of my favorites. The performers were amazing and the crowd was really friendly. I also appreciated how well-organized everything was. Overall, it was a fantastic experience.'),
('F1010', 'E1002', '', 'Sarah', 'Sa3l8ah@hotmail.com', 'I wasn\'t sure what to expect when I went to the Country Music Jam, but I ended up having a great time! The music was really good and it was great to see so many people enjoying themselves. I\'ll definitely be back next year!'),
('F1011', 'E1006', '', 'LOH JIA ZHE', 'lohjiazhe12345@gmail.com', 'i think attack on titan is a very good movie until now because it is very ganren and special'),
('F1012', 'E1006', 'M1005', 'LOH JIA ZHE', 'lohzhe12345@gmail.com', 'i think attack on titan is a very good movie until now'),
('F1013', 'E1006', 'M1005', 'LOH JIA ZHE', 'lohzhe12345@gmail.com', 'attack on titan is a very good movie'),
('F1014', 'E1006', 'M1005', 'LOH JIA ZHE', 'lohzhe12345@gmail.com', 'good job haha'),
('F1015', 'E1003', 'M1005', 'LOH JIA ZHE', 'lohzhe12345@gmail.com', 'good summer job haha'),
('F1016', 'E1006', 'M1001', 'Teo Wen Yong', 'zwenyong34@gmail.com', 'i think attack on titan is a very good movie');

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `noticeID` varchar(10) NOT NULL,
  `memberID` varchar(10) NOT NULL,
  `notice` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`noticeID`, `memberID`, `notice`) VALUES
('N1001', '', 'halo'),
('N1002', '', 'halo boy');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentID` varchar(10) NOT NULL,
  `memberID` varchar(10) NOT NULL,
  `paymentMethod` varchar(10) NOT NULL,
  `cardNum` bigint(17) NOT NULL,
  `cardName` varchar(50) NOT NULL,
  `cardCVV` int(3) NOT NULL,
  `cardEpiryDate` varchar(10) NOT NULL,
  `tngPhone` int(11) NOT NULL,
  `tngPIN` int(10) NOT NULL,
  `totalPayment` decimal(4,2) NOT NULL,
  `paymentDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`paymentID`, `memberID`, `paymentMethod`, `cardNum`, `cardName`, `cardCVV`, `cardEpiryDate`, `tngPhone`, `tngPIN`, `totalPayment`, `paymentDate`) VALUES
('P1001', 'M1001', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '12.00', '0000-00-00'),
('P1002', 'M1003', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '10.00', '0000-00-00'),
('P1003', 'M1005', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '10.00', '0000-00-00'),
('P1004', 'M1005', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '12.00', '0000-00-00'),
('P1005', 'M1005', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '10.00', '0000-00-00'),
('P1006', 'M1005', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '12.00', '0000-00-00'),
('P1007', 'M1001', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '10.00', '0000-00-00'),
('P1008', 'M1001', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '10.00', '0000-00-00'),
('P1009', 'M1001', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '99.99', '0000-00-00'),
('P1010', 'M1004', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '10.00', '2023-05-17'),
('P1011', 'M1005', 'Card', 1234567812345678, 'LOH JIA ZHE', 113, '12/23', 0, 0, '10.00', '2023-05-17'),
('P1012', 'M1002', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '99.99', '2023-05-17'),
('P1013', 'M1002', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '20.00', '2023-05-17'),
('P1014', 'M1001', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '-2.00', '2023-05-17'),
('P1015', 'M1001', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '1.00', '2023-05-17'),
('P1016', 'M1001', 'TNG', 0, 'NULL', 0, 'NULL', 1156994938, 123456, '1.00', '2023-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `memberID` varchar(5) NOT NULL,
  `password` varchar(15) NOT NULL,
  `memberName` varchar(30) NOT NULL,
  `memberAge` int(11) NOT NULL,
  `memberGender` varchar(1) NOT NULL,
  `memberTel` int(11) NOT NULL,
  `profilePhoto` varchar(30) NOT NULL,
  `memberGmail` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`memberID`, `password`, `memberName`, `memberAge`, `memberGender`, `memberTel`, `profilePhoto`, `memberGmail`) VALUES
('M1002', '123', 'LOH JIA ZHE', 18, 'M', 1156994938, '646350d82ea94.png', 'lohjiazhe12345@gmail.com'),
('M1003', '12345', 'desmund', 18, 'M', 1111111111, '64645b99a4859.jpg', 'shuige1412@gmail.com'),
('M1004', '123', 'dd', 18, 'M', 1111111111, '64645b49aab1d.webp', 'lohjiazhe123@gmail.com'),
('M1005', '12', 'LOH JIA ZHE', 18, 'F', 1156994938, '646350d82ea94.png', 'lohzhe12345@gmail.com'),
('M1006', '12', 'LOH JIA ZHE', 12, 'M', 1156994938, '6464801a5099a.jpg', 'she12@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookinglist`
--
ALTER TABLE `bookinglist`
  ADD PRIMARY KEY (`bookingID`);

--
-- Indexes for table `bookingrecord`
--
ALTER TABLE `bookingrecord`
  ADD PRIMARY KEY (`ticketID`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`eventID`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedbackID`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`noticeID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentID`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`memberID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
