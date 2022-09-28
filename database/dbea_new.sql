-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 05, 2021 at 02:17 PM
-- Server version: 5.7.32
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `dbea_project` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `dbea_project`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbea_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `administration`
--

CREATE TABLE `administration` (
  `id` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL,
  `AccountType` varchar(255) NOT NULL,
  `pin` varchar(6) NOT NULL,
  `userid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `administration`
--

INSERT INTO `administration` (`id`, `AccountID`, `AccountType`, `pin`, `userid`) VALUES
(1, 8238, 'Disbursement', '000000', 'S19234567F'),
(2, 8239, 'Repayment', '000000', 'S19234567F'),
(3, 8240, 'Platform Fees', '000000', 'S19234567F');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `given_name` varchar(255) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `credit_score` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phoneNo` varchar(255) NOT NULL,
  `DOB` date NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `income` int(11) NOT NULL,
  `emp_length` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pword` varchar(255) NOT NULL,
  `pin` varchar(6) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `interest_rate` FLOAT NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `given_name`, `account_id`, `credit_score`, `email`, `phoneNo`, `DOB`, `occupation`, `gender`, `income`, `emp_length`, `username`, `pword`, `pin`, `userid`, `interest_rate`) VALUES
(1, 'Afiq', '8472', 750, 'Afiq0228@gmail.com', '6597591930', '1993-01-29', 'Developer', 'Male', 150000, '12', 'afiq0228', '$2y$10$pQ5o69QznDumNytZlMCclu4oxi9A3VhsSr4vbMOeRMa31TPRfL39i', '111111', 'S9711111A', 4.5),
(2, 'Jenny Poh', '8473', 780, 'JennyPOO@gmail.com', '6597591930', '1978-08-16', 'Consultant', 'Female', 230000, '4', 'JennyPoh08', '$2y$10$nBRks22Sb7eX61S/cl6zU..RO5IBBmMfL3Kiwtzu8IZK2Zx1BMZeq', '111111', 'S9711111A', 4.5),
(3, 'Jeremy Teo', '8474', 660, 'JeremyThePro07@gmail.com', '6597591930', '1996-07-07', 'Sportsman', 'Male', 70000, '6', 'jeremy123', '$2y$10$WtjXCXwI8xtv3eD9yo50OewudGxP55tpm2brid8yqxKx4fFJq6sAu', '111111', 'S9711111A', 5.2),
(4, 'John Ho', '8475', 870, 'JohnHo1993@gmail.com', '6597591930', '1978-02-28', 'Doctor', 'Male', 400000, '5', 'johnho1993', '$2y$10$7eBH8ZHLJSDeJmFrEGPIIeM01mFAlaxFnUgzc3KFrzAHwbYh.fsm2', '111111', 'S9711111A', 3.8),
(5, 'Joyce Ng', '8476', 844, 'JoyceWithJoy1027@gmail.com', '6597591930', '1985-10-27', 'Lawyer', 'Female', 7000, '4', 'JoyceJoyce223', '$2y$10$LQpv7wRzPo8YhG7xpgDYPOS2xCD3oveoNvPTf9LSJBGhPfb07BGfy', '111111', 'S9711111A', 4.2),
(6, 'Mary Tan', '8477', 840, 'MaryLoveCake123@gmail.com', '6597591930', '2001-05-04', 'Consultant', 'Female', 200000, '0', 'marytan123', '$2y$10$M8rwJKFoQtYb71fJsG2UReDf4G0JC8eXOQxdOHLjCioe6L5LuGVn.', '111111', 'S9711111A', 4.2),
(7, 'Paul Lim', '8478', 760, 'PaulLimHello20@gmail.com', '6597591930', '1977-10-29', 'Teacher', 'Male', 4000, '10', 'paullim20', '$2y$10$SqeLmaLKDge6rhOikfwcLe49A96QzDJCKYhkP1IXgGpLHYvP2JTCG', '111111', 'S9711111A', 4.5),
(8, 'Terry Choi', '8479', 700, 'TerryCC@gmail.com', '6597591930', '1986-06-20', 'Driver', 'Male', 2000, '2', 'TerryCC11', '$2y$10$2qFYVbm3yDZ.ZlBW1CbTEONGhXveJQIxibyecm5.l4bd1FpZe8fbW', '111111', 'S9711111A', 4.8);

-- --------------------------------------------------------
--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `loan_amount` int(11) NOT NULL,
  `loan_purpose` varchar(255) NOT NULL,
  `loan_term` int(11) NOT NULL,
  `loan_title` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `interest_rate` FLOAT NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `borrower_id`, `loan_amount`, `loan_purpose`, `loan_term`, `loan_title`, `currency`, `interest_rate`) VALUES
(1, 1, 100000, 'Debt Consolidation', 36, 'Debt Consolidation', 'SGD', 4.5),
(2, 2, 120000, 'Car', 24, 'Car', 'SGD', 4.5),
(3, 8, 70000, 'Home', 36, 'Home', 'SGD', 4.8),
(4, 5, 20000, 'Home', 24, 'Home', 'SGD', 4.2),
(5, 4, 10000, 'Debt Consolidation', 12, 'Debt Consolidation', 'SGD', 3.8),
(6, 7, 25000, 'Debt Consolidation', 24, 'Debt Consolidation', 'SGD', 4.5);

-- --------------------------------------------------------
--
-- Table structure for table `offer`
--

CREATE TABLE `offer` (
  `id` int(11) NOT NULL,
  `request_Id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `lender_id` int(11) NOT NULL,
  `offer_amt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `offer`
--

INSERT INTO `offer` (`id`, `request_Id`, `borrower_id`, `lender_id`, `offer_amt`) VALUES
(1, 1, 1, 3, 100000),
(2, 2, 2, 6, 80000),
(3, 3, 8, 6, 70000),
(4, 4, 5, 3, 10000),
(5, 6, 7, 4, 25000);
-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `lender_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `date_of_loan` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `interest_rate` FLOAT NOT NULL  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`id`, `request_id`, `lender_id`, `borrower_id`, `date_of_loan`, `status`, `interest_rate`) VALUES
(1, 1, 3, 1, '2019-12-04', 'Repayment', 4.5),
(2, 3, 6, 8, '2021-09-27', 'Repayment', 4.8),
(3, 6, 4, 7, '2021-05-25', 'Repayment', 4.5);

-- --------------------------------------------------------


--
-- Table structure for table `repaymentrecord`
--

CREATE TABLE IF NOT EXISTS `repaymentrecord` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `loan_id` int(255) NOT NULL,
  `borrower_id` int(255) NOT NULL,
  `payment_amt` FLOAT NOT NULL,
  `payment_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `repaymentrecord`
--

INSERT INTO `repaymentrecord` (`id`, `loan_id`, `borrower_id`, `payment_amt`, `payment_date`) VALUES
(1, 1, 1, 4383.33, '2020-01-04'),
(2, 1, 1, 4383.33, '2020-02-04'),
(3, 1, 1, 4383.33, '2020-03-04'),
(4, 1, 1, 4383.33, '2020-04-04'),
(5, 1, 1, 4383.33, '2020-05-04'),
(6, 1, 1, 4383.33, '2020-06-04'),
(7, 1, 1, 4383.33, '2020-07-04'),
(8, 1, 1, 4383.33, '2020-08-04'),
(9, 1, 1, 4383.33, '2020-09-04'),
(10, 1, 1, 4383.33, '2020-10-04'),
(11, 1, 1, 4383.33, '2020-11-04'),
(12, 1, 1, 4383.33, '2020-12-04'),
(13, 1, 1, 4383.33, '2021-01-04'),
(14, 1, 1, 4383.33, '2021-02-04'),
(15, 1, 1, 4383.33, '2021-03-04'),
(16, 1, 1, 4383.33, '2021-04-04'),
(18, 1, 1, 4383.33, '2021-05-04'),
(19, 1, 1, 4383.33, '2021-06-04'),
(20, 1, 1, 4383.33, '2021-07-04'),
(21, 1, 1, 4383.33, '2021-08-04'),
(22, 1, 1, 4383.33, '2021-09-04'),
(23, 1, 1, 4383.33, '2021-10-04'),
(24, 1, 1, 4383.33, '2021-11-04'),
(25, 2, 8, 2620, '2021-10-27'),
(26, 6, 7, 1088.54, '2021-05-25'),
(27, 6, 7, 1088.54, '2021-06-25'),
(28, 6, 7, 1088.54, '2021-07-25'),
(29, 6, 7, 1088.54, '2021-08-25'),
(30, 6, 7, 1088.54, '2021-09-25'),
(31, 6, 7, 1088.54, '2021-10-25');
-- --------------------------------------------------------




--
-- Indexes for dumped tables
--

--
-- Indexes for table `administration`
--
ALTER TABLE `administration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loan_fk_1` (`lender_id`),
  ADD KEY `loan_fk_2` (`borrower_id`),
  ADD KEY `loan_fk_3` (`request_id`);

--
-- Indexes for table `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offer_fk_1` (`borrower_id`),
  ADD KEY `offer_fk_2` (`lender_id`),
  ADD KEY `offer_fk_3` (`request_Id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_fk_1` (`borrower_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administration`
--
ALTER TABLE `administration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offer`
--
ALTER TABLE `offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `loan_fk_1` FOREIGN KEY (`lender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `loan_fk_2` FOREIGN KEY (`borrower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `loan_fk_3` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`);

--
-- Constraints for table `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_fk_1` FOREIGN KEY (`borrower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `offer_fk_2` FOREIGN KEY (`lender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `offer_fk_3` FOREIGN KEY (`request_Id`) REFERENCES `request` (`id`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_fk_1` FOREIGN KEY (`borrower_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
