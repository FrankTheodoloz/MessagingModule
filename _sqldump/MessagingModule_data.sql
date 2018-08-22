-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 22, 2018 at 04:07 PM
-- Server version: 5.7.23-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `MessagingModule`
--

-- --------------------------------------------------------

--
-- Table structure for table `distribution`
--

CREATE TABLE `distribution` (
  `dis_subid` int(16) NOT NULL,
  `dis_usrid` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `distribution`
--

INSERT INTO `distribution` (`dis_subid`, `dis_usrid`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `mem_usrid` int(8) NOT NULL,
  `mem_grpid` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`mem_usrid`, `mem_grpid`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `msg_id` int(32) NOT NULL,
  `msg_subid` int(16) NOT NULL,
  `msg_from` int(8) NOT NULL,
  `msg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `msg_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`msg_id`, `msg_subid`, `msg_from`, `msg_date`, `msg_content`) VALUES
(1, 1, 1, '2018-07-31 22:00:00', 'Message 1.1'),
(2, 1, 2, '2018-08-02 11:22:12', 'Message 1.2'),
(3, 2, 3, '2018-07-31 22:00:00', 'Message 2.1'),
(4, 2, 1, '2018-08-03 11:22:12', 'Message 2.2');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `not_msgid` int(32) NOT NULL,
  `not_usrid` int(8) NOT NULL,
  `not_read` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`not_msgid`, `not_usrid`, `not_read`) VALUES
(1, 1, 0),
(1, 2, 0),
(1, 3, 0),
(2, 1, 0),
(2, 2, 0),
(2, 3, 0),
(3, 1, 0),
(3, 2, 0),
(3, 3, 0),
(4, 1, 0),
(4, 2, 0),
(4, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `set_id` int(2) NOT NULL,
  `set_type` varchar(255) NOT NULL,
  `set_name` varchar(255) NOT NULL,
  `set_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`set_id`, `set_type`, `set_name`, `set_value`) VALUES
(1, 'SITE_CONFIG', 'SITE_NAME', 'MessagingModule'),
(2, 'SITE_CONFIG', 'COPYRIGHT', '&copy; 2018 &mdash; Frank Théodoloz');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `sub_id` int(16) NOT NULL,
  `sub_name` varchar(128) NOT NULL,
  `sub_lastdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sub_lastusrid` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`sub_id`, `sub_name`, `sub_lastdate`, `sub_lastusrid`) VALUES
(1, 'Sujet1', '2018-08-02 11:22:12', 2),
(2, 'Sujet2', '2018-08-03 11:22:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ugroup`
--

CREATE TABLE `ugroup` (
  `grp_id` int(4) NOT NULL,
  `grp_name` varchar(32) NOT NULL,
  `grp_description` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ugroup`
--

INSERT INTO `ugroup` (`grp_id`, `grp_name`, `grp_description`) VALUES
(1, 'ADMIN', 'Administrators'),
(2, 'USER', 'Users');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `usr_id` int(8) NOT NULL,
  `usr_active` tinyint(1) NOT NULL DEFAULT '0',
  `usr_name` varchar(64) NOT NULL,
  `usr_lastname` varchar(64) NOT NULL,
  `usr_avatar` varchar(255) DEFAULT NULL,
  `usr_email` varchar(128) NOT NULL,
  `usr_pwdhash` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`usr_id`, `usr_active`, `usr_name`, `usr_lastname`, `usr_avatar`, `usr_email`, `usr_pwdhash`) VALUES
(1, 1, 'ADMIN', 'Administrator', NULL, 'admin@localhost', '$2y$12$0U7PXDBDK4y7BOy4Nrb1k.c9ZAlqU9qbQYJxDVWNkHTWJHnfaUarq'),
(2, 0, 'SUPER', 'Administrator', NULL, 'super@localhost', '$2y$12$9o3Hip9vmUnin3/frDA76ebDwWfn0JuvDK5fbHqTsa1/A12YqeRN2'),
(3, 1, 'Frank', 'Théodoloz', NULL, 'fthe@bluewin.ch', '$2y$12$kS6EuZmPFa061nD8ks5dGeDbdHe2BioPLNv.SricKh4moQmRMQ..6'),
(4, 1, 'User', '1', NULL, 'user1@localhost', '$2y$12$BV43C4k4yCOwxmyDny9sg.IqmALnyIX7bLrWIRdCrgcYMm6bOCya2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `distribution`
--
ALTER TABLE `distribution`
  ADD PRIMARY KEY (`dis_subid`,`dis_usrid`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`mem_usrid`,`mem_grpid`),
  ADD UNIQUE KEY `UQ_USRID_GRPID` (`mem_usrid`,`mem_grpid`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`not_msgid`,`not_usrid`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`set_id`),
  ADD UNIQUE KEY `UQ_SETTYPENAME` (`set_type`,`set_name`) USING BTREE;

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`sub_id`);

--
-- Indexes for table `ugroup`
--
ALTER TABLE `ugroup`
  ADD PRIMARY KEY (`grp_id`),
  ADD UNIQUE KEY `UQ_GRPNAME` (`grp_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`usr_id`),
  ADD UNIQUE KEY `UQ_USREMAIL` (`usr_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `msg_id` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `set_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `sub_id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ugroup`
--
ALTER TABLE `ugroup`
  MODIFY `grp_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
