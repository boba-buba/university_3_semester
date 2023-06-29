-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2016 at 02:07 PM
-- Server version: 5.7.14-log
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mff_examples`
--

-- --------------------------------------------------------

--
-- Table structure for table `todo_list`
--

CREATE TABLE IF NOT EXISTS `todo_list` (
  `id` int(10) unsigned NOT NULL,
  `todo_user_id` int(10) unsigned NOT NULL COMMENT 'FK to todo_users table. The user to which this task item belongs to.',
  `task_description` varchar(255) NOT NULL COMMENT 'The description of the TODO task.',
  `priority` int(11) NOT NULL DEFAULT '0' COMMENT 'Priority of the task (used for ordering).',
  `completed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the task was completed.'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='List of TODO items for all users.';

--
-- Dumping data for table `todo_list`
--

INSERT INTO `todo_list` (`id`, `todo_user_id`, `task_description`, `priority`, `completed`) VALUES
(1, 1, 'Finish updating lectures for web applications course', 3, 0),
(2, 1, 'Go shopping', 0, 1),
(3, 1, 'Announce examination dates', 2, 0),
(4, 1, 'Finish and submit paper', 5, 0),
(5, 2, 'Prepare next seminar', 1, 0),
(6, 2, 'Review CSS home assignment', 3, 1),
(7, 2, 'Review JS home assignment', 3, 0),
(8, 2, 'Fix last issue in our production code', 5, 0),
(9, 3, 'Prepare next lecture', 0, 0),
(10, 3, 'Finish and submit paper', 4, 1),
(11, 3, 'Get new PhD student(s)', 0, 0),
(12, 4, 'Team Fortress 2', 3, 0),
(13, 4, 'Orcs must die!', 0, 1),
(14, 4, 'Buy Couter Strike Global Offensive', 2, 0),
(15, 5, 'Prepare new GACR proposal', 3, 0),
(16, 5, 'Review my student''s Master thesis', 0, 0),
(17, 5, 'Attend computer science section meeting', 0, 1),
(18, 5, 'Start preparing schedule for summer term', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `todo_user`
--

CREATE TABLE IF NOT EXISTS `todo_user` (
  `id` int(11) NOT NULL,
  `given_name` varchar(64) NOT NULL COMMENT 'Given name of the user.',
  `surname` varchar(64) NOT NULL COMMENT 'Suname of the user.'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='List of users which use TODO list.';

--
-- Dumping data for table `todo_user`
--

INSERT INTO `todo_user` (`id`, `given_name`, `surname`) VALUES
(1, 'Martin', 'Kruliš'),
(2, 'Jan', 'Michelfeit'),
(3, 'David', 'Bednárek'),
(4, 'Jakub', 'Yaghob'),
(5, 'Filip', 'Zavoral');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `todo_list`
--
ALTER TABLE `todo_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`,`todo_user_id`);

--
-- Indexes for table `todo_user`
--
ALTER TABLE `todo_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `todo_list`
--
ALTER TABLE `todo_list`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `todo_user`
--
ALTER TABLE `todo_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
