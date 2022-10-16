-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2022 at 08:49 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boarding_house_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `id` int(11) NOT NULL,
  `boarder_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `balance`
--

INSERT INTO `balance` (`id`, `boarder_id`, `amount`, `status`) VALUES
(1, 1, 2000, 'UNPAID'),
(2, 2, 2000, 'UNPAID'),
(3, 3, 2000, 'UNPAID'),
(5, 5, 0, 'PAID');

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `date_created` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `due_date` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `date_created`, `category`, `amount`, `due_date`, `status`) VALUES
(1, '06-14-2022', 'Water', 500, '2022-06-30', 'PAID'),
(2, '06-14-2022', 'Electricity', 500, '2022-06-30', 'PAID');

-- --------------------------------------------------------

--
-- Table structure for table `boarders`
--

CREATE TABLE `boarders` (
  `boarder_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `birthdate` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `contact_number` varchar(11) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `permanent_add` varchar(255) NOT NULL,
  `email_add` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `imgname` varchar(255) NOT NULL,
  `imgsrc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `boarders`
--

INSERT INTO `boarders` (`boarder_id`, `first_name`, `last_name`, `sex`, `birthdate`, `year`, `contact_number`, `contact_person`, `permanent_add`, `email_add`, `username`, `password`, `imgname`, `imgsrc`) VALUES
(1, 'Jodelmar', 'Beltran', 'Male', '2000-10-16', '3', '09278706504', '09069189220', 'Sta Ana Cagayan', 'jodel@gmail.com', 'jodel', 'jodel', '', ''),
(2, 'Aldrae Nichole', 'Frace', 'Female', '2001-10-10', '3', '09183562781', '09587349532', 'Solana Cagayan', 'niks@gmail.com', 'niks', 'niks', '', ''),
(3, 'Kristina', 'Maguddayao', 'Female', '2001-10-26', '3', '09867483768', '09127635263', 'Solana Cagayan', 'yna@gmail.com', 'yna', 'yna', '', ''),
(5, 'Kae', 'Battuing', 'Female', '2022-06-22', '3', '1111', '2222', 'cagayan', 'kae@gmail.com', 'kae', 'kae', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL,
  `email` varchar(32) NOT NULL,
  `facebook` varchar(32) NOT NULL,
  `twitter` varchar(32) NOT NULL,
  `instagram` varchar(32) NOT NULL,
  `mobile` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`contact_id`, `email`, `facebook`, `twitter`, `instagram`, `mobile`) VALUES
(1, 'boardinghousems@gmail.com', 'Boarding_House_Management_System', '@bhousemanagementsystem', '@boardinghousems', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(55) NOT NULL,
  `questions` varchar(255) NOT NULL,
  `answers` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `questions`, `answers`) VALUES
(1, 'Where is the boarding house located?', '-The boarding house is located at Carig Sur, Tuguegarao City near Cagayan State University - Carig Campus'),
(2, 'How many rooms and how many beds in a room?', '-The boarding house have 12 rooms and there are 4 beds per room.'),
(3, 'How much is the rent of a room? Is the electric bill and water bill included in the rent?', '-The rent of a room is P6,000. No it is separated.'),
(4, 'Do you provide WIFI Connection?', '-No'),
(5, 'How should payment be made?', '-It can be Cash or online payment.'),
(6, 'How can we do our laundry?', '-The boarding house provides a laundry area at the rooftop.'),
(7, 'Can we have visitors to our rooms?', '-Visitors are allowed but limited on time.\r\nVisitor Hours:\r\nWeekdays(Monday - Friday) 4pm - 8 pm only\r\nWeekends(Saturday-Sunday) 10 am - 10 pm'),
(8, 'What other matters should we observe?', '-We thank our guests for noting that our Boarding House is a non-smoking premise and alcohol consumption is prohibited.');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(100) NOT NULL,
  `payment_number` int(100) NOT NULL,
  `boarder_id` int(100) NOT NULL,
  `amount` int(100) NOT NULL,
  `payment_date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `payment_number`, `boarder_id`, `amount`, `payment_date`) VALUES
(1, 92557045, 5, 1000, '2022-06-14'),
(2, 48857312, 5, 1000, '2022-06-14');

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE `suggestions` (
  `s_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suggestions`
--

INSERT INTO `suggestions` (`s_id`, `title`, `message`, `date`, `status`) VALUES
(2, 'TEST', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Deleniti repudiandae error quas quidem tem', '2022-06-14 09:22:09', 'read'),
(3, 'TEST', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Minus eum eaque quia nobis voluptas corpori', '2022-06-14 11:44:24', 'read');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boarders`
--
ALTER TABLE `boarders`
  ADD PRIMARY KEY (`boarder_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`s_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `balance`
--
ALTER TABLE `balance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `boarders`
--
ALTER TABLE `boarders`
  MODIFY `boarder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `s_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
