-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 17, 2009 at 08:32 AM
-- Server version: 4.1.22
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `etel_dbsmain`
--
CREATE DATABASE `etel_dbsmain` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `etel_dbsmain`;

--
-- Table structure for table `etel_gateways`
--

CREATE TABLE IF NOT EXISTS `etel_gateways` (
  `gw_id` tinyint(4) NOT NULL auto_increment,
  `gw_title` varchar(120) NOT NULL default '',
  `gw_abrev` varchar(12) NOT NULL default '',
  `gw_fulltitle` varchar(150) NOT NULL default '',
  `gw_emails_sales` varchar(100) NOT NULL default '',
  `gw_emails_support` varchar(80) NOT NULL default 'support@etelegate.com',
  `gw_emails_customerservice` varchar(80) NOT NULL default 'customerservice@etelegate.com',
  `gw_phone_support` varchar(80) NOT NULL default '',
  `gw_phone_customerservice` varchar(16) NOT NULL default '',
  `gw_database` varchar(70) NOT NULL default '',
  `gw_folder` varchar(70) NOT NULL default '',
  `gw_docs_folder` varchar(64) NOT NULL default 'main',
  `gw_active` char(1) NOT NULL default '1',
  `gw_template` varchar(90) NOT NULL default 'default',
  `gw_links` enum('all','ecom','demo') NOT NULL default 'all',
  `gw_index` varchar(60) NOT NULL default '/gateway/index.php',
  `gw_domain` varchar(80) NOT NULL default '',
  `gw_integration_site` varchar(255) NOT NULL default '',
  `gw_customerservice_site` varchar(80) NOT NULL default 'https://www.etelegate.net',
  `gw_debug_ip` int(11) NOT NULL default '0',
  PRIMARY KEY  (`gw_id`),
  KEY `gw_title` (`gw_title`,`gw_active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Gateway tables' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `etel_gateways`
--

INSERT INTO `etel_gateways` (`gw_id`, `gw_title`, `gw_abrev`, `gw_fulltitle`, `gw_emails_sales`, `gw_emails_support`, `gw_emails_customerservice`, `gw_phone_support`, `gw_phone_customerservice`, `gw_database`, `gw_folder`, `gw_docs_folder`, `gw_active`, `gw_template`, `gw_links`, `gw_index`, `gw_domain`, `gw_integration_site`, `gw_customerservice_site`, `gw_debug_ip`) VALUES
(3, 'Etelegate.com', 'ETEL', 'Etelegate LLC, a Delaware limited liability company', 'sales@etelegate.com', 'support@etelegate.com', 'customerservice@etelegate.com', '1-800-676-0127 (direct: 213-291-9051)', '1-800-961-3389', 'etel_dbscompanysetup', 'main/', 'etelegate/', '1', 'etelegate_1', 'all', '/index.php', 'https://www.etelegate.com', 'https://secure.etelegate.com/', 'https://www.etelegate.net', 1206702242),
(2, 'EcommerceGlobal.com', '', '', 'sales@ecommerceglobal.com', 'support@etelegate.com', 'customerservice@etelegate.com', '', '', 'etel_gwEcomGlobal', 'ecomglobal/', 'main/', '1', 'ecomglobal_1', 'ecom', '/gateway/ecomglobal/index.php', 'https://safe.ecommerceglobal.com', 'https://safe.ecommerceglobal.com/secure/', 'https://www.etelegate.net', 0),
(1, 'Etelegate Demo', '', '', 'support@etelegate.com', 'support@etelegate.com', 'customerservice@etelegate.com', '1-800-676-0127 (direct: 213-291-9051)', '', 'etel_gwDemo', 'demo/', 'main/', '1', 'etelegate_1', 'demo', 'https://www.etelegate.com/', 'https://www.etelegate.com', 'https://secure.etelegate.com/', 'https://www.etelegate.net', 0),
(4, 'MatureBill.com', 'MATURE', 'MatureBill Ltd', 'sales@maturebill.com', 'support@maturebill.com', 'customerservice@maturebill.com', '', '1-800-961-3389', 'etel_dbscompanysetup', 'main/', 'maturebill/', '1', 'maturebill_1', 'all', '/index.php', 'https://www.MatureBill.com', 'https://www.MatureBill.com/secure/', 'https://www.MatureBill.com/cs/', 1134608093),
(5, 'Etelegate.com (5)', 'ETEL', 'Etelegate LLC, a Delaware limited liability company', 'sales@etelegate.com', 'support@etelegate.com', 'customerservice@etelegate.com', '1-800-676-0127 (direct: 213-291-9051)', '1-800-961-3389', 'etel_dbscompanysetup', 'main/', 'main/', '1', 'etelegate_1', 'all', 'https://www.etelegate.com', 'https://www.etelegate.com', 'https://secure.etelegate.com', 'https://www.etelegate.net', 1194889316),
(6, 'Etelegate.com  (6)', 'ETEL', 'Etelegate LLC, a Delaware limited liability company', 'sales@etelegate.com', 'support@etelegate.com', 'customerservice@etelegate.com', '1-800-676-0127 (direct: 213-291-9051)', '1-800-961-3389', 'etel_dbscompanysetup', 'main/', 'main/', '1', 'etelegate_1', 'all', '/index.php', 'https://www.etelegate.com', 'https://secure.etelegate.com', 'https://www.etelegate.net', 1134608093),
(7, 'NicheBill.com', 'NICHE', 'NicheBill Ltd', 'sales@NicheBill.com', 'support@NicheBill.com', 'customerservice@NicheBill.com', '', '1-800-664-6557', 'etel_dbscompanysetup', 'main/', 'nichebill/', '1', 'nichebill_1', 'all', '/index.php', 'https://www.NicheBill.com', 'https://www.NicheBill.com/secure/', 'https://www.NicheBill.com/cs/', 1221088594);
