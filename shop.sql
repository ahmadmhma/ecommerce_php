-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2020 at 12:37 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `visibility` tinyint(4) NOT NULL DEFAULT 0,
  `allowcomment` tinyint(4) NOT NULL DEFAULT 0,
  `allowads` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `name`, `description`, `parent`, `ordering`, `visibility`, `allowcomment`, `allowads`) VALUES
(2, 'Computers', 'computer gaming', 0, 2, 0, 0, 0),
(4, 'Clothing', 'clothing and fashion', 0, 4, 0, 0, 0),
(5, 'Tools', 'tools shop', 0, 5, 0, 0, 0),
(6, 'Hand made', 'hand made', 0, 4, 0, 0, 0),
(7, 'Puma', 'clothing and fashion', 4, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `coid` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `codate` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`coid`, `comment`, `status`, `codate`, `item_id`, `user_id`) VALUES
(2, 'this is a good game', 1, '2020-12-06', 7, 5),
(3, 'this is a good', 1, '2020-12-06', 4, 5),
(4, 'hi', 1, '2020-12-06', 7, 5),
(5, 'new jacket from diadora', 1, '2020-12-06', 8, 5),
(6, 'new jacket from diadora', 1, '2020-12-06', 8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `country_made` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `rating` smallint(6) NOT NULL,
  `approve` tinyint(4) NOT NULL DEFAULT 0,
  `cat_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `itemimg` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `name`, `description`, `price`, `date`, `country_made`, `status`, `rating`, `approve`, `cat_id`, `member_id`, `tags`, `itemimg`) VALUES
(1, 'pes 2015', 'computer gaming', '$10', '2020-12-06', 'سوريا', '1', 0, 1, 2, 2, '', ''),
(2, 'pes 2017', 'computer gaming', '$20', '2020-12-06', 'china', '1', 0, 1, 2, 4, '', ''),
(3, 'pes 2018', 'computer gaming', '$50', '2020-12-06', 'usa', '2', 0, 1, 2, 4, '', ''),
(4, 'pes 2020', 'computer gaming', '$55', '2020-12-06', 'europe', '1', 0, 1, 2, 1, '', ''),
(5, 'T-shirt', 'adiddas', '$30', '2020-12-06', 'europe', '1', 0, 1, 4, 2, '', ''),
(6, 'Short', 'adiddas', '$20', '2020-12-06', 'syria', '1', 0, 1, 4, 1, '', ''),
(7, 'Fifa 2017', 'computer gaming', '$50', '2020-12-06', 'syria', '1', 0, 1, 5, 2, '', ''),
(8, 'Jacket', 'clothing and fashion', '100', '2020-12-06', 'syria', '1', 0, 1, 4, 5, '', ''),
(9, 'Swit shirt', 'clothing and fashion', '30', '2020-12-07', 'syria', '1', 0, 1, 4, 5, '', ''),
(10, 'pes 2021', 'computer gaming', '$100', '2020-12-07', 'syria', '1', 0, 1, 2, 1, 'game, computer, descount ', ''),
(11, 'Spider Man', 'playstation 4 games', '75', '2020-12-08', 'usa', '1', 0, 1, 2, 5, 'game, computer, descount, playstation, ', ''),
(12, 'pes 2012', 'computer gaming', '$50', '2020-12-08', 'china', '1', 0, 1, 2, 1, 'game, computer, descount, playstation, ', '225231641_ava.jpg'),
(13, 'pes2019', 'playstation 4 games', '55', '2020-12-09', 'japan', '1', 0, 1, 2, 5, 'discount, computer, ', '235554833_pes19.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL COMMENT 'to id users',
  `username` varchar(255) NOT NULL COMMENT 'username to login',
  `password` varchar(255) NOT NULL COMMENT 'password to login',
  `email` varchar(255) NOT NULL COMMENT 'email to login',
  `fullname` varchar(255) NOT NULL,
  `groupID` int(11) NOT NULL DEFAULT 0,
  `truststatus` int(11) NOT NULL DEFAULT 0 COMMENT 'seller rank',
  `regstatus` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `email`, `fullname`, `groupID`, `truststatus`, `regstatus`, `date`, `avatar`) VALUES
(1, 'ahmad', '8cb2237d0679ca88db6464eac60da96345513964', 'ahmad@ahmad.com', 'ahmad mhm', 1, 1, 1, '0000-00-00', ''),
(2, 'hasan', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'hasan@ha.com', 'hasan ha', 0, 0, 1, '2020-12-06', ''),
(3, 'amar', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'amr@am.com', 'amar am', 0, 0, 1, '2020-12-06', ''),
(4, 'wisam', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'wesam@w.com', 'wesam w', 0, 0, 1, '2020-12-06', ''),
(5, 'adnan', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'adnan_95@yahoo.com', '', 0, 0, 1, '2020-12-06', ''),
(6, 'fahed', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'fahed@fahed.net', 'fahed fa', 0, 0, 1, '2020-12-08', '460211683_128839606_2754304158221359_7336871548304094920_n.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`coid`),
  ADD KEY `comment_item` (`item_id`),
  ADD KEY `user_com` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemID`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `member_1` (`member_id`),
  ADD KEY `cat_1` (`cat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `coid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'to id users', AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`itemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_com` FOREIGN KEY (`user_id`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`member_id`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
