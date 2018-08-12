-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 12, 2018 at 12:48 AM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`frank`@`%` PROCEDURE `P_NotifUserAdd` (IN `usrId` INT, IN `subId` INT)  BEGIN
    -- Declare local variables
    DECLARE done BOOLEAN DEFAULT 0;
    DECLARE msgId INT;

    -- Declare the cursor
    DECLARE cuMessages CURSOR FOR
      SELECT msg_msgid FROM message WHERE msg_subId = subId;
    -- Declare continue handler
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

    -- Open the cursor
    OPEN cuMessages;
    REPEAT
      -- Get message number
      FETCH cuMessages
      INTO msgId;

      if !done
      then
        INSERT INTO notification (not_msgid, not_usrid) VALUES (msgId, usrId);
      end IF;

      -- End of looop
    UNTIL done END REPEAT;
    -- Close the cursor
    CLOSE cuMessages;
  end$$

CREATE DEFINER=`frank`@`%` PROCEDURE `P_NotifUserRemove` (IN `usrId` INT, IN `subId` INT)  BEGIN
    -- Declare local variables
    DECLARE done BOOLEAN DEFAULT 0;
    DECLARE msgId INT;

    -- Declare the cursor
    DECLARE cuMessages CURSOR FOR
      SELECT msg_id FROM message WHERE msg_subId = subId;
    -- Declare continue handler
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

    -- Open the cursor
    OPEN cuMessages;
    REPEAT
      -- Get message number
      FETCH cuMessages
      INTO msgId;

      if !done
      then
        DELETE
        FROM notification
        WHERE not_msgid = msgId
          AND not_usrid = usrId;
      end IF;

      -- End of looop
    UNTIL done END REPEAT;
    -- Close the cursor
    CLOSE cuMessages;
  end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `distribution`
--

CREATE TABLE `distribution` (
  `dis_subid` int(16) NOT NULL,
  `dis_usrid` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `mem_usrid` int(8) NOT NULL,
  `mem_grpid` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Triggers `message`
--
DELIMITER $$
CREATE TRIGGER `T_MessageNotifications` AFTER INSERT ON `message` FOR EACH ROW begin
    -- Declare local variables
    DECLARE done BOOLEAN DEFAULT 0;
    DECLARE userId INT;

    -- Declare the cursor
    DECLARE cuDistribution CURSOR FOR
      SELECT dis_usrid FROM distribution WHERE dis_subId = NEW.msg_subid;
    -- Declare continue handler
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

    UPDATE subject s
    SET s.sub_lastdate  = NEW.msg_date,
        s.sub_lastusrid = NEW.msg_from
    WHERE s.sub_id = NEW.msg_subid;

    -- Open the cursor
    OPEN cuDistribution;
    REPEAT
      -- Get order number
      FETCH cuDistribution
      INTO userId;

      if !done
      then
        INSERT INTO notification (not_msgid, not_usrid) VALUES (NEW.msg_id, userId);
      end IF;

      -- End of looop
    UNTIL done END REPEAT;
    -- Close the cursor
    CLOSE cuDistribution;
  END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `not_msgid` int(32) NOT NULL,
  `not_usrid` int(8) NOT NULL,
  `not_read` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `set_type` varchar(255) NOT NULL,
  `set_name` varchar(255) NOT NULL,
  `set_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `ugroup`
--

CREATE TABLE `ugroup` (
  `grp_id` int(4) NOT NULL,
  `grp_name` varchar(32) NOT NULL,
  `grp_description` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  MODIFY `msg_id` int(32) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `sub_id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ugroup`
--
ALTER TABLE `ugroup`
  MODIFY `grp_id` int(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `usr_id` int(8) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
