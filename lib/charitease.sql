-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2023 at 03:42 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `charitease`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmins`
--

CREATE TABLE `tbladmins` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `admin_email` varchar(50) NOT NULL,
  `admin_password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblconvo`
--

CREATE TABLE `tblconvo` (
  `convo_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `initiate_by` varchar(10) NOT NULL,
  `message` varchar(4096) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblconvo`
--

INSERT INTO `tblconvo` (`convo_id`, `donor_id`, `org_id`, `initiate_by`, `message`, `timestamp`) VALUES
(1, 3, 7, 'donor', 'hello may tao po ba dito?', '2023-05-20 08:27:05'),
(2, 3, 7, 'donor', 'pwede po magtanong about sa charity po na ginagamit nyo?', '2023-05-20 08:27:56'),
(4, 3, 7, 'charity', 'yes? how may i help you sir?', '2023-05-20 09:37:16'),
(5, 3, 7, 'donor', 'how to locate your location sir?', '2023-05-20 12:12:32'),
(6, 3, 7, 'donor', 'pwede po magtanong about sa charity po na ginagamit nyo?', '2023-05-20 12:14:23'),
(7, 3, 7, 'donor', 'pwede po magtanong about sa charity po na ginagamit nyo?', '2023-05-20 12:14:32'),
(8, 3, 7, 'donor', 'hello may tao po ba dito?', '2023-05-20 12:14:38');

-- --------------------------------------------------------

--
-- Table structure for table `tbldonationprogress`
--

CREATE TABLE `tbldonationprogress` (
  `org_id` int(11) NOT NULL,
  `current_value_donated_monetary` int(11) NOT NULL,
  `target_value_monetary` int(11) NOT NULL,
  `current_value_donated_inkind` int(11) NOT NULL,
  `target_value_inkind` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldonationprogress`
--

INSERT INTO `tbldonationprogress` (`org_id`, `current_value_donated_monetary`, `target_value_monetary`, `current_value_donated_inkind`, `target_value_inkind`) VALUES
(7, 5000, 100000, 0, 500),
(10, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbldonations`
--

CREATE TABLE `tbldonations` (
  `donation_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `donation_type` varchar(50) NOT NULL,
  `donation_amount` int(11) NOT NULL,
  `donation_name` varchar(50) DEFAULT NULL,
  `donation_description` varchar(150) DEFAULT NULL,
  `donation_category` varchar(10) DEFAULT NULL,
  `donation_date` date NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbldonorrating`
--

CREATE TABLE `tbldonorrating` (
  `rating_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldonorrating`
--

INSERT INTO `tbldonorrating` (`rating_id`, `donor_id`, `org_id`, `rating`, `review`, `timestamp`) VALUES
(1, 3, 7, 5, 'maganda yung hangarin ng charity na ito', '2023-05-20 01:25:06'),
(2, 3, 10, 3, 'mababa lang dahil kulang sa agenda at description', '2023-05-20 01:27:56'),
(3, 3, 10, 0, 'pwede pa ba ako magdagdag ng rating?', '2023-05-20 01:34:13'),
(4, 3, 10, 4, 'dapat siguro di na ko pwede magdagdag ng review no? HAHAHAHHAHA', '2023-05-20 01:38:25'),
(5, 3, 10, 2, 'dapat nga hindi na gagi', '2023-05-20 01:39:32'),
(6, 3, 10, 1, 'oo nga dapat di na, dapat isa lang tapos pwede iedit incase na may mali', '2023-05-20 01:41:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbldonors`
--

CREATE TABLE `tbldonors` (
  `donor_id` int(11) NOT NULL,
  `donor_name` varchar(50) NOT NULL,
  `donor_contact_name` varchar(50) DEFAULT NULL,
  `donor_address` varchar(150) NOT NULL,
  `donor_type` varchar(20) NOT NULL,
  `donor_phone` varchar(12) NOT NULL,
  `date_approved` date DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldonors`
--

INSERT INTO `tbldonors` (`donor_id`, `donor_name`, `donor_contact_name`, `donor_address`, `donor_type`, `donor_phone`, `date_approved`, `is_approved`) VALUES
(3, 'Allen Baluyot', 'Allen Baluyot', '#56 Legal St. Santa Rosa, Bataan', 'Individual', '09865485672', '2023-05-11', 0),
(8, 'James Baluyot', NULL, '#57 Rosal St. Groove Bataan', 'Individual', '098543245653', '2023-05-12', 0),
(9, 'ABCCompany', NULL, 'Donor Address', 'Organization', '09485437214', '2023-05-12', 0),
(13, 'Steve Jobs', 'null', 'Donor Address Steves', 'Individual', '09834758493', '0000-00-00', 0),
(14, 'Jobless LLC', 'null', 'Jobless LLC Address', 'Organization', '09834758493', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblimages`
--

CREATE TABLE `tblimages` (
  `image_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `permit_type` varchar(50) DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `image_name` varchar(100) NOT NULL,
  `image_type` varchar(50) NOT NULL,
  `image_data` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblorgedittimeline`
--

CREATE TABLE `tblorgedittimeline` (
  `edit_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `edit_title` varchar(50) NOT NULL,
  `edit_description` text NOT NULL,
  `edit_date` date NOT NULL,
  `edit_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblorgs`
--

CREATE TABLE `tblorgs` (
  `org_id` int(11) NOT NULL,
  `org_name` varchar(50) NOT NULL,
  `org_person_name` varchar(50) NOT NULL,
  `org_phone` varchar(12) NOT NULL,
  `org_address` varchar(100) NOT NULL,
  `is_approved` tinyint(1) NOT NULL,
  `org_description` text DEFAULT NULL,
  `date_founded` date NOT NULL,
  `date_approved` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblorgs`
--

INSERT INTO `tblorgs` (`org_id`, `org_name`, `org_person_name`, `org_phone`, `org_address`, `is_approved`, `org_description`, `date_founded`, `date_approved`) VALUES
(7, 'Green Cross', 'Allen Baluyot', '09865485672', '#49 Rosal St. New Santa Rosa Village, Zambales', 0, 'null', '2023-05-03', '0000-00-00'),
(10, 'XYZCompany', 'Allen Baluyot', '098543245653', '#43 Greenland Woods, Santa Rosa, Bataan', 0, 'null', '2023-05-12', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `tblorgtimeline`
--

CREATE TABLE `tblorgtimeline` (
  `event_id` int(11) NOT NULL,
  `org_id` int(11) NOT NULL,
  `event_title` varchar(50) NOT NULL,
  `event_description` text NOT NULL,
  `event_date` date NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `user_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `account_type` varchar(20) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `verification_pin` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`user_id`, `email`, `password`, `account_type`, `is_verified`, `verification_pin`) VALUES
(3, 'allenb@gmail.com', '123', 'donor', 0, 'PVrOW7mdJ4'),
(7, 'greencross@gmail.com', '123', 'charity', 0, 'CI7hr3lstz'),
(8, 'allenbaluyot@gmail.com', '123', 'donor', 0, 'cfhXH8RmuP'),
(9, 'abcom@gmail.com', '123', 'donor', 0, 'NpR1GLcfVI'),
(10, 'xyzcompany@gmail.com', '123', 'charity', 0, 'asvWKGw7d1'),
(13, 'stevejobs@gmail.com', '123', 'donor', 0, '4OrsHzSiQl'),
(14, 'joblessllc@gmail.com', '123', 'donor', 0, '35ZNTe0yGs'),
(15, 'donoremail@gmail.com', '123', 'donor', 0, 'syx3mklW8A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmins`
--
ALTER TABLE `tbladmins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tblconvo`
--
ALTER TABLE `tblconvo`
  ADD PRIMARY KEY (`convo_id`),
  ADD KEY `fk_tblcon_donor_id` (`donor_id`),
  ADD KEY `fk_tblcon_org_id` (`org_id`);

--
-- Indexes for table `tbldonationprogress`
--
ALTER TABLE `tbldonationprogress`
  ADD PRIMARY KEY (`org_id`);

--
-- Indexes for table `tbldonations`
--
ALTER TABLE `tbldonations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `fk_tbld_donor_id` (`donor_id`),
  ADD KEY `fk_tbld_org_id` (`org_id`);

--
-- Indexes for table `tbldonorrating`
--
ALTER TABLE `tbldonorrating`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `fk_dr-donor_id` (`donor_id`),
  ADD KEY `fk_dr-org_id` (`org_id`);

--
-- Indexes for table `tbldonors`
--
ALTER TABLE `tbldonors`
  ADD PRIMARY KEY (`donor_id`);

--
-- Indexes for table `tblimages`
--
ALTER TABLE `tblimages`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `tblorgedittimeline`
--
ALTER TABLE `tblorgedittimeline`
  ADD PRIMARY KEY (`edit_id`),
  ADD KEY `fk_tbloet_event_id` (`event_id`),
  ADD KEY `fk_tbloet_org_id` (`org_id`);

--
-- Indexes for table `tblorgs`
--
ALTER TABLE `tblorgs`
  ADD PRIMARY KEY (`org_id`);

--
-- Indexes for table `tblorgtimeline`
--
ALTER TABLE `tblorgtimeline`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `fk_tblot_org_id` (`org_id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmins`
--
ALTER TABLE `tbladmins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblconvo`
--
ALTER TABLE `tblconvo`
  MODIFY `convo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbldonations`
--
ALTER TABLE `tbldonations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbldonorrating`
--
ALTER TABLE `tbldonorrating`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblimages`
--
ALTER TABLE `tblimages`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblorgedittimeline`
--
ALTER TABLE `tblorgedittimeline`
  MODIFY `edit_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblorgtimeline`
--
ALTER TABLE `tblorgtimeline`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblconvo`
--
ALTER TABLE `tblconvo`
  ADD CONSTRAINT `fk_tblcon_donor_id` FOREIGN KEY (`donor_id`) REFERENCES `tbldonors` (`donor_id`),
  ADD CONSTRAINT `fk_tblcon_org_id` FOREIGN KEY (`org_id`) REFERENCES `tblorgs` (`org_id`);

--
-- Constraints for table `tbldonationprogress`
--
ALTER TABLE `tbldonationprogress`
  ADD CONSTRAINT `fk_tbldp_orgid` FOREIGN KEY (`org_id`) REFERENCES `tblorgs` (`org_id`);

--
-- Constraints for table `tbldonations`
--
ALTER TABLE `tbldonations`
  ADD CONSTRAINT `fk_tbld_donor_id` FOREIGN KEY (`donor_id`) REFERENCES `tbldonors` (`donor_id`),
  ADD CONSTRAINT `fk_tbld_org_id` FOREIGN KEY (`org_id`) REFERENCES `tblorgs` (`org_id`);

--
-- Constraints for table `tbldonorrating`
--
ALTER TABLE `tbldonorrating`
  ADD CONSTRAINT `fk_dr-donor_id` FOREIGN KEY (`donor_id`) REFERENCES `tbldonors` (`donor_id`),
  ADD CONSTRAINT `fk_dr-org_id` FOREIGN KEY (`org_id`) REFERENCES `tblorgs` (`org_id`);

--
-- Constraints for table `tbldonors`
--
ALTER TABLE `tbldonors`
  ADD CONSTRAINT `fk_duserid` FOREIGN KEY (`donor_id`) REFERENCES `tblusers` (`user_id`);

--
-- Constraints for table `tblorgedittimeline`
--
ALTER TABLE `tblorgedittimeline`
  ADD CONSTRAINT `fk_tbloet_event_id` FOREIGN KEY (`event_id`) REFERENCES `tblorgtimeline` (`event_id`),
  ADD CONSTRAINT `fk_tbloet_org_id` FOREIGN KEY (`org_id`) REFERENCES `tblorgtimeline` (`org_id`);

--
-- Constraints for table `tblorgs`
--
ALTER TABLE `tblorgs`
  ADD CONSTRAINT `fk_ouserid` FOREIGN KEY (`org_id`) REFERENCES `tblusers` (`user_id`);

--
-- Constraints for table `tblorgtimeline`
--
ALTER TABLE `tblorgtimeline`
  ADD CONSTRAINT `fk_tblot_org_id` FOREIGN KEY (`org_id`) REFERENCES `tblorgs` (`org_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
