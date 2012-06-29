-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 29, 2012 at 12:11 PM
-- Server version: 5.5.23-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `wap4`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `meta`
--

CREATE TABLE IF NOT EXISTS `meta` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1603 ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `news` text NOT NULL,
  `date` datetime DEFAULT NULL,
  `lang` varchar(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `setting_name` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` mediumint(8) unsigned NOT NULL,
  `ip_address` char(16) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1603 ;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` mediumint(8) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20729 ;

-- --------------------------------------------------------

--
-- Table structure for table `videos2`
--

CREATE TABLE IF NOT EXISTS `videos2` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uniqid` varchar(13) NOT NULL,
  `users_id` mediumint(8) NOT NULL,
  `file_body` varchar(100) NOT NULL DEFAULT 'file',
  `file_size` int(12) NOT NULL COMMENT 'in bytes',
  `original_extension` varchar(6) NOT NULL DEFAULT 'flv',
  `converted_extension` varchar(6) NOT NULL DEFAULT 'mp3',
  `is_uploaded` tinyint(1) NOT NULL DEFAULT '0',
  `is_converted` tinyint(1) NOT NULL DEFAULT '0',
  `source_type` enum('upload','youtube','vimeo','direct') NOT NULL,
  `description` text NOT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_details`
--

CREATE TABLE IF NOT EXISTS `video_details` (
  `uniqid` varchar(20) NOT NULL,
  `video_title` varchar(164) NOT NULL,
  `video_description` text NOT NULL,
  `video_size` int(10) unsigned NOT NULL,
  `uploaded_video_body` varchar(164) NOT NULL,
  `converted_video_body` varchar(164) NOT NULL,
  `uploaded_video_extension` varchar(5) NOT NULL,
  `converted_video_extension` varchar(5) NOT NULL,
  `percents_uploaded` int(3) unsigned NOT NULL,
  `percents_converted` int(3) unsigned NOT NULL,
  `source_type` enum('upload','known','direct') NOT NULL,
  `requested_link` varchar(200) NOT NULL,
  `modified_link` varchar(200) NOT NULL,
  `converter_option` varchar(45) NOT NULL,
  `convert_quality` enum('low','normal','high') NOT NULL,
  `ffmpeg_command` varchar(625) NOT NULL,
  `ffmpeg_output` text NOT NULL,
  `wget_output` text NOT NULL,
  `download_count` int(10) unsigned NOT NULL,
  `is_uploaded` tinyint(1) NOT NULL,
  `is_converted` tinyint(1) NOT NULL,
  `is_failed` tinyint(1) NOT NULL,
  `fail_log` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ffmpeg_log_date` varchar(15) NOT NULL COMMENT 'ffmpeg log file creation date',
  PRIMARY KEY (`uniqid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;