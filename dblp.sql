
-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 11, 2013 at 07:37 AM
-- Server version: 5.1.57
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `a6940219_dblp`
--

-- --------------------------------------------------------

--
-- Table structure for table `Article`
--

CREATE TABLE `Article` (
  `DBLP_KEY_PUBL` int(11) NOT NULL,
  `volume` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `number` varchar(15) COLLATE latin1_general_ci DEFAULT NULL,
  `pages` varchar(15) COLLATE latin1_general_ci DEFAULT NULL,
  `journal_name` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `journal_year` year(4) DEFAULT NULL,
  PRIMARY KEY (`DBLP_KEY_PUBL`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `Article`
--


-- --------------------------------------------------------

--
-- Table structure for table `Author`
--

CREATE TABLE `Author` (
  `DBLP_WWW_KEY` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  PRIMARY KEY (`DBLP_WWW_KEY`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `Author`
--


-- --------------------------------------------------------

--
-- Table structure for table `Book`
--

CREATE TABLE `Book` (
  `DBLP_KEY` int(11) NOT NULL,
  `isbn` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`DBLP_KEY`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `Book`
--


-- --------------------------------------------------------

--
-- Table structure for table `Editor`
--

CREATE TABLE `Editor` (
  `DBLP_KEY` int(11) NOT NULL AUTO_INCREMENT,
  `Ename` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`DBLP_KEY`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `Editor`
--


-- --------------------------------------------------------

--
-- Table structure for table `Publications`
--

CREATE TABLE `Publications` (
  `DBLP_KEY` int(11) NOT NULL,
  `title` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `url` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `publisher` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`DBLP_KEY`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `Publications`
--


-- --------------------------------------------------------

--
-- Table structure for table `PublicationsAuthor`
--

CREATE TABLE `PublicationsAuthor` (
  `DBLP_KEY_PUBL` int(11) NOT NULL,
  `DBLP_KEY_AUTHOR` int(11) DEFAULT NULL,
  PRIMARY KEY (`DBLP_KEY_PUBL`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `PublicationsAuthor`
--


-- --------------------------------------------------------

--
-- Table structure for table `School`
--

CREATE TABLE `School` (
  `DBLP_KEY` int(11) NOT NULL AUTO_INCREMENT,
  `Sname` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`DBLP_KEY`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `School`
--


-- --------------------------------------------------------

--
-- Table structure for table `Thesis`
--

CREATE TABLE `Thesis` (
  `DBLP_KEY` int(11) NOT NULL,
  `masterifTrue` tinyint(1) NOT NULL,
  `isbnPhd` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`DBLP_KEY`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `Thesis`
--


-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `email` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `password` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `administrator` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` VALUES('root', 'toor', 1);
