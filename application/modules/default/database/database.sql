-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 05, 2012 at 03:22 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `offercalculator`
--
CREATE DATABASE `offercalculator` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `offercalculator`;

--
-- User: offercalculator
--
CREATE USER 'offercalculator'@'localhost' IDENTIFIED BY  'offercalculator';

GRANT ALL PRIVILEGES ON * . * TO  'offercalculator'@'localhost' IDENTIFIED BY  'offercalculator' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON  `offercalculator\_%` . * TO  'offercalculator'@'localhost';

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE IF NOT EXISTS `codes` (
  `id_pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_fk` int(10) unsigned DEFAULT NULL,
  `scope_id_fk` int(10) unsigned DEFAULT NULL,
  `type` bit(2) NOT NULL,
  `code` char(32) COLLATE utf8_bin NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id_pk`),
  KEY `user_id_fk` (`user_id_fk`),
  KEY `scope_id_fk` (`scope_id_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `user_id_fk_pk` int(10) unsigned NOT NULL,
  `scope_id_fk_pk` int(10) unsigned NOT NULL,
  `type` bit(5) NOT NULL,
  PRIMARY KEY (`user_id_fk_pk`,`scope_id_fk_pk`),
  KEY `scope_id_fk_pk` (`scope_id_fk_pk`),
  KEY `user_id_fk_pk` (`user_id_fk_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `user_id_fk_pk` int(10) unsigned NOT NULL,
  `scope_id_fk_pk` int(10) unsigned NOT NULL DEFAULT '0',
  `type` bit(5) NOT NULL,
  `status` bit(2) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`user_id_fk_pk`,`scope_id_fk_pk`),
  KEY `scope_id_fk_pk` (`scope_id_fk_pk`),
  KEY `user_id_fk_pk` (`user_id_fk_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `scopes`
--

CREATE TABLE IF NOT EXISTS `scopes` (
  `id_pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `department_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_bin NOT NULL,
  `password` char(32) COLLATE utf8_bin NOT NULL,
  `salt` char(21) COLLATE utf8_bin NOT NULL,
  `real_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `company_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `department_name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `unverified_email` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id_pk`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

-- username=username&password=password
INSERT INTO `users` (`username`, `password`, `salt`, `real_name`, `company_name`, `department_name`, `email`, `unverified_email`) VALUES
('username', '.SebWiROuH0cAUcz.GdFfVz8j6oJZ3Hy', '3Daf4jGas5ja2hlDf7hsj', NULL, NULL, NULL, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `codes`
--
ALTER TABLE `codes`
  ADD CONSTRAINT `codes_ibfk_1` FOREIGN KEY (`user_id_fk`) REFERENCES `users` (`id_pk`) ON DELETE CASCADE,
  ADD CONSTRAINT `codes_ibfk_2` FOREIGN KEY (`scope_id_fk`) REFERENCES `scopes` (`id_pk`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`user_id_fk_pk`) REFERENCES `users` (`id_pk`) ON DELETE CASCADE,
  ADD CONSTRAINT `permissions_ibfk_2` FOREIGN KEY (`scope_id_fk_pk`) REFERENCES `scopes` (`id_pk`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id_fk_pk`) REFERENCES `users` (`id_pk`) ON DELETE CASCADE,
  ADD CONSTRAINT `requests_ibfk_3` FOREIGN KEY (`scope_id_fk_pk`) REFERENCES `scopes` (`id_pk`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
