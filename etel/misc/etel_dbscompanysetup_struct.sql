-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 17, 2009 at 08:34 AM
-- Server version: 4.1.22
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `etel_dbscompanysetup`
--

-- --------------------------------------------------------

--
-- Table structure for table `cs_bad_emails`
--

CREATE TABLE IF NOT EXISTS `cs_bad_emails` (
  `user_id` int(11) NOT NULL default '0',
  `company_name` varchar(255) NOT NULL default '',
  `email_id` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_bank`
--

CREATE TABLE IF NOT EXISTS `cs_bank` (
  `bank_id` int(11) NOT NULL auto_increment,
  `bank_name` varchar(250) default NULL,
  `bank_email` varchar(250) default NULL,
  `bank_description` varchar(128) NOT NULL default '',
  `gateway_id` int(11) NOT NULL default '-1',
  `bank_paybackday` int(11) NOT NULL default '-1',
  `bank_payweekfrom` int(11) NOT NULL default '1',
  `bank_payweekto` int(11) NOT NULL default '7',
  `bank_payday` int(11) NOT NULL default '1',
  `discountrate` double default '0',
  `transactionfee` double default '0',
  `rollingreserve` double default '0',
  `chargebackfee` double default '0',
  `bk_int_function` varchar(50) NOT NULL default '',
  `bk_int_refund_function` varchar(100) NOT NULL default '',
  `bk_trans_types` enum('Credit','Check','Visa','Mastercard','Discover','JCB','Web900','EuroDebit') default NULL,
  `bk_cc_support` tinyint(4) NOT NULL default '0',
  `bk_ch_support` tinyint(4) NOT NULL default '0',
  `bk_w9_support` tinyint(4) NOT NULL default '0',
  `bk_username` varchar(50) NOT NULL default '',
  `bk_password` varchar(50) NOT NULL default '',
  `bk_additional_id` varchar(255) NOT NULL default '',
  `bk_cc_bank_enabled` tinyint(1) NOT NULL default '0',
  `bk_payout_support` tinyint(4) NOT NULL default '0',
  `bk_defaults` text NOT NULL,
  `bk_days_behind` tinyint(4) NOT NULL default '10',
  `bk_fee_chargeback` decimal(10,2) NOT NULL default '17.00',
  `bk_fee_low_risk` decimal(10,2) NOT NULL default '3.55',
  `bk_fee_high_risk` decimal(10,2) NOT NULL default '5.50',
  `bk_fee_approve` decimal(10,3) NOT NULL default '0.250',
  `bk_fee_decline` decimal(10,3) NOT NULL default '0.100',
  `bk_fee_refund` decimal(10,2) NOT NULL default '0.10',
  `bk_fee_us_wire` decimal(10,2) NOT NULL default '1.00',
  `bk_fee_nonus_wire` decimal(10,2) NOT NULL default '25.00',
  `bk_descriptor_visa` varchar(100) NOT NULL default '',
  `bk_descriptor_master` varchar(100) NOT NULL default '',
  `bk_rollover` smallint(6) NOT NULL default '50',
  `bk_payroll_discount` decimal(10,2) NOT NULL default '0.25',
  `bk_paydays_method` enum('alldays','weekdays','10-20-1') NOT NULL default 'weekdays',
  `bk_gkard` tinyint(4) NOT NULL default '0',
  `bk_payment_type` enum('profit','all','realprofit') NOT NULL default 'all',
  `bk_hide` tinyint(1) NOT NULL default '0',
  `bk_ignore` tinyint(4) NOT NULL default '0',
  `bk_total_deposit_actual` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`bank_id`),
  KEY `bk_ignore` (`bk_ignore`),
  KEY `bk_trans_types` (`bk_trans_types`),
  KEY `bk_hide` (`bk_hide`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_bankdetails`
--

CREATE TABLE IF NOT EXISTS `cs_bankdetails` (
  `bank_id` int(11) NOT NULL auto_increment,
  `bank_name` varchar(150) NOT NULL default '',
  `bank_routing_code` varchar(9) NOT NULL default '',
  `bank_email` varchar(100) NOT NULL default '',
  `bank_user_id` int(11) NOT NULL default '0',
  `gateway_id` int(11) default '-1',
  PRIMARY KEY  (`bank_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_bankrates`
--

CREATE TABLE IF NOT EXISTS `cs_bankrates` (
  `id` int(11) NOT NULL auto_increment,
  `bank_id` int(11) default '0',
  `transactionId` int(11) default '0',
  `discountrate` double default '0',
  `transactionfee` double default '0',
  `rollingreserve` double default '0',
  `chargebackfee` double default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2611 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_bank_company`
--

CREATE TABLE IF NOT EXISTS `cs_bank_company` (
  `bank_company_id` int(11) NOT NULL auto_increment,
  `company_id` int(11) default '0',
  `check_bank_id` int(11) default '0',
  `credit_bank_id` int(11) default '0',
  PRIMARY KEY  (`bank_company_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_bank_invoice`
--

CREATE TABLE IF NOT EXISTS `cs_bank_invoice` (
  `bi_ID` int(11) NOT NULL auto_increment,
  `bi_bank_id` int(11) NOT NULL default '0',
  `bi_date` date NOT NULL default '0000-00-00',
  `bi_title` varchar(255) NOT NULL default 'Invoice',
  `bi_balance` decimal(10,2) NOT NULL default '0.00',
  `bi_deduction` decimal(10,2) NOT NULL default '0.00',
  `bi_notes` text NOT NULL,
  `bi_pay_info` text NOT NULL,
  `bi_bank_info` text NOT NULL,
  `bi_transactions` text,
  `bi_deductions` text,
  `bi_mib_ID_list` text NOT NULL,
  `bi_download_count` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`bi_ID`),
  KEY `mi_company_id` (`bi_bank_id`,`bi_date`),
  KEY `ri_date` (`bi_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1585 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_banlist`
--

CREATE TABLE IF NOT EXISTS `cs_banlist` (
  `bl_ID` int(11) NOT NULL auto_increment,
  `bl_group` int(11) NOT NULL default '0',
  `bl_type` enum('CCnumber','phonenumber','email','address','country','city','state','name','surname','ipaddress','userId') NOT NULL default 'CCnumber',
  `bl_data` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`bl_ID`),
  KEY `bl_group` (`bl_group`),
  KEY `bl_type` (`bl_type`),
  KEY `bl_type_2` (`bl_type`,`bl_data`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64967 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_bardo`
--

CREATE TABLE IF NOT EXISTS `cs_bardo` (
  `iCode` int(11) NOT NULL auto_increment,
  `shop_number` int(11) default NULL,
  `bardo_number` varchar(250) default NULL,
  `transac_status` varchar(250) default NULL,
  `status_detailed` varchar(250) default NULL,
  `ds` varchar(250) default NULL,
  `trans_date` datetime default NULL,
  `status` char(1) default NULL,
  `reference_number` varchar(30) default NULL,
  PRIMARY KEY  (`iCode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1687 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_cache`
--

CREATE TABLE IF NOT EXISTS `cs_cache` (
  `ce_hash` varchar(32) NOT NULL default '',
  `ce_expire` int(10) unsigned default NULL,
  `ce_cache` longtext NOT NULL,
  PRIMARY KEY  (`ce_hash`),
  KEY `ce_expire` (`ce_expire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_callback`
--

CREATE TABLE IF NOT EXISTS `cs_callback` (
  `callBackId` int(11) NOT NULL auto_increment,
  `userid` int(11) default NULL,
  `transactionid` int(11) default NULL,
  `dateandtime` datetime default NULL,
  PRIMARY KEY  (`callBackId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_callcenterusers`
--

CREATE TABLE IF NOT EXISTS `cs_callcenterusers` (
  `cc_usersid` int(11) NOT NULL auto_increment,
  `company_id` int(11) NOT NULL default '0',
  `comany_name` varchar(250) NOT NULL default '',
  `company_conatct_no` varchar(100) NOT NULL default '',
  `address` text NOT NULL,
  `amount` double NOT NULL default '0',
  `user_name` varchar(50) NOT NULL default '',
  `user_password` varchar(50) NOT NULL default '',
  `voice_auth_fee` double default NULL,
  PRIMARY KEY  (`cc_usersid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_callnotes`
--

CREATE TABLE IF NOT EXISTS `cs_callnotes` (
  `note_id` int(11) NOT NULL auto_increment,
  `transaction_id` int(11) default NULL,
  `call_date_time` datetime default NULL,
  `service_notes` text,
  `cancel_status` char(1) default '0',
  `customer_notes` text,
  `solved` int(11) default '0',
  `is_bill_date_changed` char(1) default 'N',
  `call_duration` varchar(8) default NULL,
  `customer_service_id` int(11) default NULL,
  `prev_bill_date` varchar(10) default NULL,
  `dnc` char(1) default 'N',
  `cn_type` enum('refundrequest','foundcall') NOT NULL default 'foundcall',
  `cn_contactmethod` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`note_id`),
  UNIQUE KEY `transaction_id_2` (`transaction_id`,`cn_type`),
  KEY `call_date_time` (`call_date_time`),
  KEY `transaction_id` (`transaction_id`),
  KEY `cn_type` (`cn_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19266 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_companydetails`
--

CREATE TABLE IF NOT EXISTS `cs_companydetails` (
  `userId` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(30) NOT NULL default '',
  `companyname` varchar(100) NOT NULL default '',
  `phonenumber` varchar(25) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `zipcode` varchar(10) default NULL,
  `email` varchar(100) NOT NULL default '',
  `suspenduser` enum('YES','NO') NOT NULL default 'NO',
  `ostate` varchar(100) NOT NULL default '',
  `merchantName` varchar(250) default NULL,
  `tollFreeNumber` varchar(100) default NULL,
  `retrievalNumber` varchar(100) default NULL,
  `securityNumber` varchar(100) default NULL,
  `processor` varchar(100) default NULL,
  `chargeback` decimal(10,2) default '0.00',
  `credit` decimal(10,2) default '0.00',
  `discountrate` decimal(10,2) default '0.00',
  `transactionfee` decimal(10,2) default '0.00',
  `reserve` decimal(10,2) default '0.00',
  `voiceauthfee` decimal(10,2) default '0.00',
  `auto_cancel` char(1) NOT NULL default 'N',
  `time_frame` int(11) NOT NULL default '-1',
  `auto_approve` char(1) default 'N',
  `transaction_type` enum('Ecommerce','Travel','Pharmacy','Gaming','Adult','Extreme','Telemarketing','Other') NOT NULL default 'Adult',
  `activeuser` int(11) NOT NULL default '1',
  `contactname` varchar(100) default NULL,
  `volumenumber` int(11) default '10000',
  `shipping_cancel` char(1) NOT NULL default 'N',
  `shipping_timeframe` int(11) default '-1',
  `telepackagename` varchar(100) default NULL,
  `telepackageprod` varchar(100) default NULL,
  `telepackageprice` double default '0',
  `telerefundpolicy` text,
  `teledescription` text,
  `avgticket` decimal(10,2) default '0.00',
  `chargebackper` decimal(10,2) default '0.00',
  `preprocess` enum('Yes','No') default 'No',
  `recurbilling` enum('Yes','No') default 'No',
  `currprocessing` enum('Yes','No') default 'No',
  `url1` varchar(100) default NULL,
  `url2` varchar(100) default NULL,
  `url3` varchar(100) default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `how_about_us` varchar(100) default NULL,
  `billingdescriptor` varchar(150) default NULL,
  `completed_uploading` char(1) default 'N',
  `merchant_contract_agree` tinyint(1) NOT NULL default '0',
  `fax_number` varchar(25) default NULL,
  `company_type` enum('ltd','part','other','sole','corp') default NULL,
  `other_company_type` varchar(100) default NULL,
  `customer_service_phone` varchar(25) default NULL,
  `company_bank` varchar(100) default NULL,
  `other_company_bank` varchar(100) default NULL,
  `bank_address` varchar(100) default NULL,
  `bank_country` varchar(100) default NULL,
  `bank_city` varchar(50) NOT NULL default '',
  `bank_state` varchar(5) NOT NULL default '',
  `bank_zipcode` varchar(35) NOT NULL default '',
  `bank_phone` varchar(25) default NULL,
  `bank_sort_code` varchar(50) default NULL,
  `bank_account_number` varchar(25) default NULL,
  `bank_swift_code` varchar(25) default NULL,
  `bank_IBRoutingCode` varchar(32) NOT NULL default '0',
  `bank_IBName` varchar(100) NOT NULL default '',
  `bank_IBRoutingCodeType` tinyint(4) NOT NULL default '0',
  `bank_IBCity` varchar(40) NOT NULL default '',
  `bank_IBState` varchar(3) NOT NULL default '',
  `bank_wiremethod` enum('ACH','Wire') NOT NULL default 'ACH',
  `first_name` varchar(100) default NULL,
  `family_name` varchar(100) default NULL,
  `job_title` varchar(100) default NULL,
  `contact_email` varchar(100) default NULL,
  `contact_phone` varchar(25) default NULL,
  `login_count` int(11) NOT NULL default '0',
  `stitle` varchar(20) default NULL,
  `sdateofbirth` date default '1970-01-01',
  `ssex` enum('Male','Female') default 'Male',
  `saddress` text,
  `spostcode` varchar(20) default NULL,
  `sresidencetelephone` varchar(25) default NULL,
  `sfax` varchar(25) default NULL,
  `send_mail` int(11) default '1',
  `completed_merchant_application` int(11) NOT NULL default '0',
  `num_documents_uploaded` int(11) NOT NULL default '0',
  `beneficiary_name` varchar(150) default NULL,
  `bank_account_name` varchar(150) default NULL,
  `reseller_other` varchar(100) default NULL,
  `setupfees` decimal(10,2) default '0.00',
  `reseller_id` int(11) default NULL,
  `send_ecommercemail` int(11) default '0',
  `merchant_discount_rate` decimal(10,2) default '0.00',
  `reseller_discount_rate` decimal(10,2) default '0.00',
  `total_discount_rate` decimal(10,2) default '0.00',
  `merchant_trans_fees` decimal(10,2) default '0.00',
  `reseller_trans_fees` decimal(10,2) default '0.00',
  `total_trans_fees` decimal(10,2) default '0.00',
  `processing_currency` varchar(5) default 'USD',
  `legal_name` varchar(150) default NULL,
  `incorporated_country` varchar(100) default NULL,
  `incorporated_number` varchar(50) default NULL,
  `fax_dba` varchar(50) default NULL,
  `physical_address` text,
  `cellular` varchar(50) default NULL,
  `technical_contact_details` text,
  `admin_contact_details` text,
  `max_ticket_amt` decimal(10,2) default '0.00',
  `min_ticket_amt` decimal(10,2) default '0.00',
  `goods_list` text,
  `volume_last_month` int(11) default '0',
  `volume_prev_30days` int(11) default '0',
  `volume_prev_60days` int(11) default '0',
  `totals` varchar(50) default NULL,
  `forecast_volume_1month` int(11) default '0',
  `forecast_volume_2month` int(11) default '0',
  `forecast_volume_3month` int(11) default '0',
  `current_anti_fraud_system` text,
  `customer_service_program` text,
  `refund_policy` text,
  `atm_verify` char(1) default 'Y',
  `bank_shopId` varchar(100) default NULL,
  `bank_Username` varchar(150) default NULL,
  `bank_Password` varchar(100) default NULL,
  `bank_Creditcard` mediumint(6) NOT NULL default '-1',
  `bank_check` mediumint(6) NOT NULL default '-1',
  `cancel_ecommerce_letter` int(11) default '1',
  `gateway_id` int(11) default '-1',
  `cd_subgateway_id` int(11) default NULL,
  `integrate_check` int(11) default '0',
  `block_virtualterminal` int(11) default '1',
  `BICcode` varchar(50) default NULL,
  `VATnumber` varchar(50) default NULL,
  `registrationNo` varchar(50) default NULL,
  `url4` varchar(100) default NULL,
  `url5` varchar(100) default NULL,
  `block_recurtransaction` int(11) default '0',
  `block_rebilltransaction` int(11) default '0',
  `ReferenceNumber` varchar(12) default NULL,
  `gateway_licence` char(1) default 'Y',
  `cd_timezone` varchar(8) NOT NULL default '-07:00',
  `cd_ignore` tinyint(1) NOT NULL default '0',
  `cd_custom_recur` tinyint(1) unsigned NOT NULL default '0',
  `cd_enable_tracking` enum('off','on') NOT NULL default 'off',
  `cd_tracking_init_response` tinyint(4) NOT NULL default '7',
  `cd_tracking_shipping_limit` tinyint(4) NOT NULL default '14',
  `cd_fraudscore_limit` decimal(3,2) NOT NULL default '5.00',
  `cd_custom_contract` mediumint(9) default NULL,
  `cd_custom_orderpage` text,
  `cd_notes` text,
  `cd_previous_transaction_fee` decimal(10,2) NOT NULL default '0.00',
  `cd_previous_discount` decimal(10,2) NOT NULL default '0.00',
  `cd_contact_im` varchar(40) default NULL,
  `cc_customer_fee` double default '1.95',
  `cd_payperiod` smallint(6) NOT NULL default '7',
  `cd_pay_bimonthly` enum('bimonthly','trimonthly') default NULL,
  `cd_paystartday` smallint(6) NOT NULL default '2',
  `cd_paydelay` smallint(6) NOT NULL default '15',
  `cd_rollover` smallint(6) NOT NULL default '50',
  `cd_wirefee` smallint(6) NOT NULL default '50',
  `cd_appfee` smallint(6) NOT NULL default '0',
  `cd_appfee_upfront` int(11) NOT NULL default '0',
  `cd_paydaystartday` tinyint(4) NOT NULL default '6',
  `cd_enable_price_points` tinyint(1) NOT NULL default '1',
  `cd_enable_rand_pricing` tinyint(1) NOT NULL default '0',
  `cd_allow_rand_pricing` tinyint(1) NOT NULL default '1',
  `cc_chargeback` decimal(10,2) NOT NULL default '50.00',
  `cc_discountrate` decimal(10,2) NOT NULL default '15.00',
  `cc_reserve` decimal(10,2) NOT NULL default '10.00',
  `ch_chargeback` decimal(10,2) NOT NULL default '50.00',
  `ch_discountrate` decimal(10,2) NOT NULL default '15.00',
  `ch_reserve` decimal(10,2) NOT NULL default '10.00',
  `web_chargeback` decimal(10,2) NOT NULL default '50.00',
  `web_discountrate` decimal(10,2) NOT NULL default '15.00',
  `web_reserve` decimal(10,2) NOT NULL default '10.00',
  `cc_merchant_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `cc_reseller_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `cc_total_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `cc_merchant_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `cc_reseller_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `cc_total_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `ch_merchant_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `ch_reseller_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `ch_total_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `ch_merchant_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `ch_reseller_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `ch_total_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `web_merchant_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `web_reseller_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `web_total_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `cc_billingdescriptor` varchar(150) NOT NULL default 'www.eTeleGate.net',
  `cc_visa_billingdescriptor` varchar(255) NOT NULL default 'www.eTeleGate.net',
  `cc_master_billingdescriptor` varchar(255) NOT NULL default 'www.eTeleGate.net',
  `ch_billingdescriptor` varchar(150) NOT NULL default 'www.eTeleGate.net',
  `we_billingdescriptor` varchar(60) NOT NULL default '',
  `cs_monthly_charge` smallint(6) NOT NULL default '0',
  `cd_has_been_active` tinyint(4) NOT NULL default '0',
  `cd_password_mgmt` tinyint(4) NOT NULL default '0',
  `cd_web900bank` smallint(6) NOT NULL default '-1',
  `cd_merchant_show_contract` tinyint(1) NOT NULL default '0',
  `cd_contract_ip` varchar(15) NOT NULL default '',
  `cd_contract_date` int(11) NOT NULL default '0',
  `cd_previous_processor` varchar(80) NOT NULL default '',
  `cd_processing_reason` text NOT NULL,
  `cd_cc_bank_extra` varchar(32) NOT NULL default '',
  `cd_max_transaction` int(11) NOT NULL default '99',
  `cd_max_volume` bigint(20) NOT NULL default '25000',
  `cd_secret_key` varchar(32) NOT NULL default '',
  `cd_verify_rand_price` tinyint(4) NOT NULL default '1',
  `cd_allow_gatewayVT` tinyint(1) NOT NULL default '0',
  `cd_recieve_order_confirmations` varchar(60) NOT NULL default '',
  `cd_paid_setup_fee` tinyint(1) NOT NULL default '0',
  `cd_bank_routingnumber` varchar(250) NOT NULL default '',
  `cd_bank_routingcode` tinyint(4) NOT NULL default '0',
  `cd_bank_instructions` text NOT NULL,
  `cd_last_paid_monthlyfee` date NOT NULL default '0000-00-00',
  `cd_next_pay_day` date NOT NULL default '0000-00-00',
  `cd_completion` tinyint(4) NOT NULL default '0',
  `cc_overchargeback` smallint(6) NOT NULL default '150',
  `cc_underchargeback` smallint(6) NOT NULL default '50',
  `cd_reseller_rates_request` text,
  `cd_orderpage_settings` enum('default','autoforward') NOT NULL default 'default',
  `cd_orderpage_useraccount` tinyint(1) NOT NULL default '0',
  `cd_pay_status` enum('payable','unpayable') NOT NULL default 'payable',
  `cd_approve_timelimit` tinyint(4) NOT NULL default '24',
  `cd_th_ID` mediumint(9) default NULL,
  `cd_orderpage_disable_fraud_checks` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`userId`),
  UNIQUE KEY `ReferenceNumber` (`ReferenceNumber`),
  KEY `username` (`username`),
  KEY `companyname` (`companyname`),
  KEY `email` (`email`),
  KEY `cd_ignore` (`cd_ignore`),
  KEY `cd_th_ID` (`cd_th_ID`),
  KEY `company_bank` (`company_bank`),
  KEY `cd_completion` (`cd_completion`),
  KEY `transaction_type` (`transaction_type`),
  KEY `date_added` (`date_added`),
  KEY `activeuser` (`activeuser`),
  KEY `password` (`password`),
  KEY `reseller_id` (`reseller_id`),
  KEY `gateway_id` (`gateway_id`),
  KEY `how_about_us` (`how_about_us`),
  KEY `cd_pay_status` (`cd_pay_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=142544 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_companydetails_ext`
--

CREATE TABLE IF NOT EXISTS `cs_companydetails_ext` (
  `userId` int(11) NOT NULL default '0',
  `processingcurrency_master` varchar(5) default NULL,
  `processingcurrency_visa` varchar(5) default NULL,
  `scanorder_merchantid` varchar(50) default NULL,
  `scanorder_password` varchar(50) default NULL,
  `customerservice_email` varchar(100) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='extension of companydetails';

-- --------------------------------------------------------

--
-- Table structure for table `cs_companyusers`
--

CREATE TABLE IF NOT EXISTS `cs_companyusers` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `password` varchar(20) NOT NULL default '',
  `teleusertype` int(11) NOT NULL default '0',
  `website_url` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_company_bankdetails`
--

CREATE TABLE IF NOT EXISTS `cs_company_bankdetails` (
  `bank_id` int(11) NOT NULL auto_increment,
  `bank_user_id` int(11) NOT NULL default '0',
  `bank_name` varchar(150) NOT NULL default '',
  `bank_transaction_type` varchar(10) NOT NULL default '',
  `bank_email` varchar(100) default NULL,
  `date_added` datetime default NULL,
  `usertype` int(11) default '0',
  PRIMARY KEY  (`bank_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_company_banks`
--

CREATE TABLE IF NOT EXISTS `cs_company_banks` (
  `cb_ID` int(11) NOT NULL auto_increment,
  `cb_en_ID` int(11) NOT NULL default '0',
  `userId` int(11) NOT NULL default '0',
  `bank_id` int(11) NOT NULL default '0',
  `cb_config` text,
  PRIMARY KEY  (`cb_ID`),
  UNIQUE KEY `cb_en_ID_2` (`cb_en_ID`,`bank_id`),
  KEY `userId` (`userId`),
  KEY `bank_id_2` (`bank_id`),
  KEY `cb_en_ID` (`cb_en_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31700 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_company_rates`
--

CREATE TABLE IF NOT EXISTS `cs_company_rates` (
  `cr_ID` int(11) NOT NULL auto_increment,
  `cr_userId` int(11) NOT NULL default '-1',
  `cr_transtype` enum('new','all','creditcard','check','web900') NOT NULL default 'new',
  `cr_feetype` enum('discount','transactionfee','reserve','customerfee','chargeback (under 1%)','chargeback (over 1%)','refund (under 2%)','refund (over 2%)','decline transaction fee') NOT NULL default 'discount',
  `cr_merchant` float NOT NULL default '0',
  `cr_reseller` float NOT NULL default '0',
  `cr_total` float NOT NULL default '0',
  `cr_teir_top` bigint(20) NOT NULL default '1000000000',
  `cr_teir_bottom` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`cr_ID`),
  KEY `cr_userId` (`cr_userId`,`cr_transtype`,`cr_feetype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Merchant Teir Rates' AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_company_sites`
--

CREATE TABLE IF NOT EXISTS `cs_company_sites` (
  `cs_ID` int(11) NOT NULL auto_increment,
  `cs_en_ID` int(11) NOT NULL default '0',
  `cs_company_id` int(11) NOT NULL default '0',
  `cs_URL` varchar(150) NOT NULL default '',
  `cs_name` varchar(100) NOT NULL default '',
  `cs_title` varchar(40) default NULL,
  `cs_order_page` varchar(255) NOT NULL default '',
  `cs_return_page` varchar(255) NOT NULL default '',
  `cs_support_email` varchar(100) NOT NULL default '',
  `cs_support_phone` varchar(20) default NULL,
  `cs_reference_ID` varchar(12) NOT NULL default '',
  `cs_member_url` varchar(250) NOT NULL default '',
  `cs_2257_page` varchar(255) NOT NULL default '',
  `cs_member_username` varchar(64) NOT NULL default '',
  `cs_member_password` varchar(64) NOT NULL default '',
  `cs_enable_passmgmt` tinyint(1) NOT NULL default '0',
  `cs_ftp` varchar(100) NOT NULL default '',
  `cs_ftp_user` varchar(50) NOT NULL default '',
  `cs_ftp_pass` varchar(50) NOT NULL default '',
  `cs_hide` tinyint(1) NOT NULL default '0',
  `cs_gatewayId` tinyint(4) NOT NULL default '0',
  `cs_user_checksum` varchar(32) NOT NULL default '',
  `cs_verified` enum('pending','non-compliant','approved','declined','ignored') NOT NULL default 'pending',
  `cs_reason` text NOT NULL,
  `cs_created` datetime NOT NULL default '0000-00-00 00:00:00',
  `cs_allow_testmode` tinyint(1) NOT NULL default '1',
  `cs_ftp_last_check` datetime NOT NULL default '0000-00-00 00:00:00',
  `cs_search_frequency` smallint(6) NOT NULL default '10',
  `cs_search_depth` tinyint(4) NOT NULL default '3',
  `cs_enable_spider` tinyint(4) NOT NULL default '1',
  `cs_spider_report_type` tinyint(4) NOT NULL default '0',
  `cs_spider_report` text,
  `cs_spider_report_score` decimal(10,2) NOT NULL default '0.00',
  `cs_notify_type` enum('disabled','approve only','decline only','both') NOT NULL default 'disabled',
  `cs_notify_url` varchar(255) NOT NULL default '',
  `cs_notify_user` varchar(255) NOT NULL default '',
  `cs_notify_pass` varchar(255) NOT NULL default '',
  `cs_notify_key` varchar(255) NOT NULL default '',
  `cs_notify_event` int(11) NOT NULL default '0',
  `cs_notify_eventurl` varchar(255) NOT NULL default '',
  `cs_notify_eventuser` varchar(255) NOT NULL default '',
  `cs_notify_eventpass` varchar(255) NOT NULL default '',
  `cs_notify_eventdomain` varchar(255) NOT NULL default '',
  `cs_notify_eventlogintype` enum('anonymous','basic','ntlm') NOT NULL default 'anonymous',
  `cs_member_usedbmm` varchar(100) NOT NULL default '',
  `cs_member_data` text,
  `cs_member_secret` varchar(100) NOT NULL default '',
  `cs_member_updateurl` varchar(250) NOT NULL default '',
  `cs_notify_retry` tinyint(4) NOT NULL default '0',
  `cs_niche` smallint(6) default NULL,
  PRIMARY KEY  (`cs_ID`),
  UNIQUE KEY `cs_reference_ID` (`cs_reference_ID`),
  KEY `cs_company_id` (`cs_company_id`),
  KEY `cs_created` (`cs_created`),
  KEY `cs_name` (`cs_name`),
  KEY `cs_verified` (`cs_verified`),
  KEY `cs_niche` (`cs_niche`),
  KEY `cs_gatewayId` (`cs_gatewayId`),
  KEY `cs_en_ID` (`cs_en_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5399 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_country`
--

CREATE TABLE IF NOT EXISTS `cs_country` (
  `co_ISO` char(2) NOT NULL default '',
  `co_full` varchar(160) character set utf8 collate utf8_bin NOT NULL default '',
  `co_3dig` smallint(3) unsigned zerofill NOT NULL default '000',
  `co_3char` char(3) NOT NULL default '',
  `co_blacklisted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`co_ISO`),
  KEY `co_full` (`co_full`),
  KEY `co_blacklisted` (`co_blacklisted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cs_crosssales`
--

CREATE TABLE IF NOT EXISTS `cs_crosssales` (
  `cr_ID` int(11) NOT NULL auto_increment,
  `cr_userId` int(11) NOT NULL default '0',
  `cr_subAccount` mediumint(9) NOT NULL default '0',
  `cr_cs_ID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cr_ID`),
  KEY `cr_userId` (`cr_userId`,`cr_subAccount`),
  KEY `cr_cs_ID` (`cr_cs_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_customeraccount`
--

CREATE TABLE IF NOT EXISTS `cs_customeraccount` (
  `ca_ID` int(11) NOT NULL auto_increment,
  `ca_email` varchar(100) NOT NULL default '',
  `ca_password` varchar(100) NOT NULL default '',
  `ca_userInfo` text NOT NULL,
  `ca_name` varchar(50) NOT NULL default '',
  `ca_surname` varchar(50) NOT NULL default '',
  `ca_address` varchar(100) NOT NULL default '',
  `ca_address2` varchar(100) NOT NULL default '',
  `ca_city` varchar(50) NOT NULL default '',
  `ca_state` varchar(12) NOT NULL default '',
  `ca_zipcode` varchar(25) NOT NULL default '',
  `ca_country` varchar(3) NOT NULL default '',
  `ca_phone` varchar(30) NOT NULL default '',
  `ca_cvv2` smallint(6) NOT NULL default '0',
  `ca_cardtype` varchar(12) NOT NULL default '',
  `ca_CCNumber` varchar(64) NOT NULL default '',
  `ca_validto` varchar(9) NOT NULL default '',
  `ca_bankPhone` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`ca_ID`),
  UNIQUE KEY `ca_email` (`ca_email`),
  KEY `ca_password` (`ca_password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_customerserviceusers`
--

CREATE TABLE IF NOT EXISTS `cs_customerserviceusers` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(20) NOT NULL default '',
  `cs_type` enum('customer','merchant','all') NOT NULL default 'customer',
  `cs_email` varchar(80) NOT NULL default '',
  `company_ids` varchar(255) default 'A',
  `cs_gw_ID` tinyint(11) NOT NULL default '3',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_deletedcompanydetails`
--

CREATE TABLE IF NOT EXISTS `cs_deletedcompanydetails` (
  `userId` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(30) NOT NULL default '',
  `companyname` varchar(100) NOT NULL default '',
  `phonenumber` varchar(25) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `zipcode` varchar(10) default NULL,
  `email` varchar(100) NOT NULL default '',
  `suspenduser` varchar(3) NOT NULL default 'NO',
  `ostate` varchar(100) NOT NULL default '',
  `merchantName` varchar(250) default NULL,
  `tollFreeNumber` varchar(100) default NULL,
  `retrievalNumber` varchar(100) default NULL,
  `securityNumber` varchar(100) default NULL,
  `processor` varchar(100) default NULL,
  `chargeback` decimal(10,2) default '0.00',
  `credit` decimal(10,2) default '0.00',
  `discountrate` decimal(10,2) default '0.00',
  `transactionfee` decimal(10,2) default '0.00',
  `reserve` decimal(10,2) default '0.00',
  `voiceauthfee` decimal(10,2) default '0.00',
  `auto_cancel` char(1) NOT NULL default 'N',
  `time_frame` int(11) NOT NULL default '-1',
  `auto_approve` char(1) default 'N',
  `transaction_type` enum('ecom','trvl','phrm','game','adlt','tele','pmtg') NOT NULL default 'ecom',
  `activeuser` int(11) NOT NULL default '1',
  `contactname` varchar(100) default NULL,
  `volumenumber` int(11) default '10000',
  `shipping_cancel` char(1) NOT NULL default 'N',
  `shipping_timeframe` int(11) default '-1',
  `telepackagename` varchar(100) default NULL,
  `telepackageprod` varchar(100) default NULL,
  `telepackageprice` double default '0',
  `telerefundpolicy` text,
  `teledescription` text,
  `avgticket` float default '0',
  `chargebackper` float default '0',
  `preprocess` varchar(4) default 'No',
  `recurbilling` varchar(4) default 'No',
  `currprocessing` varchar(4) default 'No',
  `url1` varchar(100) default NULL,
  `url2` varchar(100) default NULL,
  `url3` varchar(100) default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `how_about_us` varchar(100) default NULL,
  `billingdescriptor` varchar(150) default NULL,
  `completed_uploading` char(1) default 'N',
  `merchant_contract_agree` tinyint(1) NOT NULL default '0',
  `fax_number` varchar(25) default NULL,
  `company_type` enum('ltd','part','other','sole','corp') default NULL,
  `other_company_type` varchar(100) default NULL,
  `customer_service_phone` varchar(25) default NULL,
  `company_bank` varchar(100) default NULL,
  `other_company_bank` varchar(100) default NULL,
  `bank_address` varchar(100) default NULL,
  `bank_country` varchar(100) default NULL,
  `bank_city` varchar(50) NOT NULL default '',
  `bank_zipcode` varchar(35) NOT NULL default '',
  `bank_phone` varchar(25) default NULL,
  `bank_sort_code` varchar(50) default NULL,
  `bank_account_number` varchar(25) default NULL,
  `bank_swift_code` varchar(25) default NULL,
  `bank_IBRoutingCode` varchar(32) NOT NULL default '0',
  `bank_IBName` varchar(100) NOT NULL default '',
  `bank_IBRoutingCodeType` tinyint(4) NOT NULL default '0',
  `bank_IBCity` varchar(40) NOT NULL default '',
  `bank_IBState` varchar(3) NOT NULL default '',
  `first_name` varchar(100) default NULL,
  `family_name` varchar(100) default NULL,
  `job_title` varchar(100) default NULL,
  `contact_email` varchar(100) default NULL,
  `contact_phone` varchar(25) default NULL,
  `login_count` int(11) NOT NULL default '0',
  `stitle` varchar(20) default NULL,
  `sdateofbirth` date default NULL,
  `ssex` varchar(20) default NULL,
  `saddress` text,
  `spostcode` varchar(20) default NULL,
  `sresidencetelephone` varchar(25) default NULL,
  `sfax` varchar(25) default NULL,
  `send_mail` int(11) default '1',
  `completed_merchant_application` int(11) NOT NULL default '0',
  `num_documents_uploaded` int(11) NOT NULL default '0',
  `beneficiary_name` varchar(150) default NULL,
  `bank_account_name` varchar(150) default NULL,
  `reseller_other` varchar(100) default NULL,
  `setupfees` decimal(10,2) default '0.00',
  `reseller_id` int(11) default NULL,
  `send_ecommercemail` int(11) default '0',
  `merchant_discount_rate` decimal(10,2) default '0.00',
  `reseller_discount_rate` decimal(10,2) default '0.00',
  `total_discount_rate` decimal(10,2) default '0.00',
  `merchant_trans_fees` decimal(10,2) default '0.00',
  `reseller_trans_fees` decimal(10,2) default '0.00',
  `total_trans_fees` decimal(10,2) default '0.00',
  `processing_currency` varchar(5) default 'USD',
  `legal_name` varchar(150) default NULL,
  `incorporated_country` varchar(100) default NULL,
  `incorporated_number` varchar(50) default NULL,
  `fax_dba` varchar(50) default NULL,
  `physical_address` text,
  `cellular` varchar(50) default NULL,
  `technical_contact_details` text,
  `admin_contact_details` text,
  `max_ticket_amt` decimal(10,2) default '0.00',
  `min_ticket_amt` decimal(10,2) default '0.00',
  `goods_list` text,
  `volume_last_month` int(11) default '10000',
  `volume_prev_30days` int(11) default '10000',
  `volume_prev_60days` int(11) default '10000',
  `totals` varchar(50) default NULL,
  `forecast_volume_1month` int(11) default '10000',
  `forecast_volume_2month` int(11) default '10000',
  `forecast_volume_3month` int(11) default '10000',
  `current_anti_fraud_system` text,
  `customer_service_program` text,
  `refund_policy` text,
  `atm_verify` char(1) default 'Y',
  `bank_shopId` varchar(100) default NULL,
  `bank_Username` varchar(150) default NULL,
  `bank_Password` varchar(100) default NULL,
  `bank_Creditcard` mediumint(6) NOT NULL default '-1',
  `bank_check` mediumint(6) NOT NULL default '-1',
  `cancel_ecommerce_letter` int(11) default '1',
  `gateway_id` int(11) default '-1',
  `cd_subgateway_id` int(11) default NULL,
  `integrate_check` int(11) default '0',
  `block_virtualterminal` int(11) default '1',
  `BICcode` varchar(50) default NULL,
  `VATnumber` varchar(50) default NULL,
  `registrationNo` varchar(50) default NULL,
  `url4` varchar(100) default NULL,
  `url5` varchar(100) default NULL,
  `block_recurtransaction` int(11) default '0',
  `block_rebilltransaction` int(11) default '0',
  `ReferenceNumber` varchar(12) default NULL,
  `gateway_licence` char(1) default 'Y',
  `cd_timezone` decimal(3,2) NOT NULL default '-7.50',
  `cd_ignore` tinyint(1) NOT NULL default '0',
  `cd_custom_recur` tinyint(1) unsigned NOT NULL default '0',
  `cd_enable_tracking` enum('off','on') NOT NULL default 'off',
  `cd_tracking_init_response` tinyint(4) NOT NULL default '7',
  `cd_tracking_shipping_limit` tinyint(4) NOT NULL default '14',
  `cd_fraudscore_limit` decimal(3,2) NOT NULL default '5.00',
  `cd_custom_contract` mediumint(9) default NULL,
  `cd_custom_orderpage` text,
  `cd_notes` text,
  `cd_previous_transaction_fee` decimal(10,2) NOT NULL default '0.00',
  `cd_previous_discount` decimal(10,2) NOT NULL default '0.00',
  `cd_contact_im` varchar(40) default NULL,
  `cc_customer_fee` double default '1.95',
  `cd_payperiod` smallint(6) NOT NULL default '7',
  `cd_pay_bimonthly` enum('bimonthly','trimonthly') default NULL,
  `cd_paystartday` smallint(6) NOT NULL default '2',
  `cd_paydelay` smallint(6) NOT NULL default '25',
  `cd_rollover` smallint(6) NOT NULL default '50',
  `cd_wirefee` smallint(6) NOT NULL default '50',
  `cd_appfee` smallint(6) NOT NULL default '0',
  `cd_appfee_upfront` int(11) NOT NULL default '0',
  `cd_paydaystartday` tinyint(4) NOT NULL default '6',
  `cd_enable_price_points` tinyint(1) NOT NULL default '1',
  `cd_enable_rand_pricing` tinyint(1) NOT NULL default '0',
  `cd_allow_rand_pricing` tinyint(1) NOT NULL default '1',
  `cc_chargeback` decimal(10,2) NOT NULL default '50.00',
  `cc_discountrate` decimal(10,2) NOT NULL default '0.00',
  `cc_reserve` decimal(10,2) NOT NULL default '10.00',
  `ch_chargeback` decimal(10,2) NOT NULL default '0.00',
  `ch_discountrate` decimal(10,2) NOT NULL default '0.00',
  `ch_reserve` decimal(10,2) NOT NULL default '10.00',
  `web_chargeback` decimal(10,2) NOT NULL default '0.00',
  `web_discountrate` decimal(10,2) NOT NULL default '0.00',
  `web_reserve` decimal(10,2) NOT NULL default '10.00',
  `cc_merchant_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `cc_reseller_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `cc_total_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `cc_merchant_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `cc_reseller_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `cc_total_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `ch_merchant_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `ch_reseller_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `ch_total_discount_rate` decimal(10,2) NOT NULL default '0.00',
  `ch_merchant_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `ch_reseller_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `ch_total_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `web_merchant_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `web_reseller_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `web_total_trans_fees` decimal(10,2) NOT NULL default '0.00',
  `cc_billingdescriptor` varchar(150) NOT NULL default 'www.eTeleGate.net',
  `cc_visa_billingdescriptor` varchar(255) NOT NULL default 'www.eTeleGate.net',
  `cc_master_billingdescriptor` varchar(255) NOT NULL default 'www.eTeleGate.net',
  `ch_billingdescriptor` varchar(150) NOT NULL default 'www.eTeleGate.net',
  `we_billingdescriptor` varchar(60) NOT NULL default '',
  `cs_monthly_charge` smallint(6) NOT NULL default '0',
  `cd_has_been_active` tinyint(4) NOT NULL default '0',
  `cd_password_mgmt` tinyint(4) NOT NULL default '0',
  `cd_web900bank` smallint(6) NOT NULL default '-1',
  `cd_merchant_show_contract` tinyint(1) NOT NULL default '0',
  `cd_contract_ip` varchar(15) NOT NULL default '',
  `cd_contract_date` int(11) NOT NULL default '0',
  `cd_previous_processor` varchar(80) NOT NULL default '',
  `cd_processing_reason` text NOT NULL,
  `cd_cc_bank_extra` varchar(32) NOT NULL default '',
  `cd_max_transaction` int(11) NOT NULL default '99',
  `cd_max_volume` bigint(20) NOT NULL default '25000',
  `cd_secret_key` varchar(32) NOT NULL default '',
  `cd_verify_rand_price` tinyint(1) NOT NULL default '1',
  `cd_allow_gatewayVT` tinyint(1) NOT NULL default '0',
  `cd_recieve_order_confirmations` varchar(60) NOT NULL default '',
  `cd_paid_setup_fee` tinyint(1) NOT NULL default '0',
  `cd_bank_routingnumber` varchar(250) NOT NULL default '',
  `cd_bank_routingcode` tinyint(4) NOT NULL default '0',
  `cd_bank_instructions` text NOT NULL,
  `cd_last_paid_monthlyfee` date NOT NULL default '0000-00-00',
  `cd_next_pay_day` date NOT NULL default '0000-00-00',
  `cd_completion` tinyint(4) NOT NULL default '0',
  `cc_overchargeback` smallint(6) NOT NULL default '150',
  `cc_underchargeback` smallint(6) NOT NULL default '50',
  `cd_reseller_rates_request` text,
  `cd_orderpage_settings` enum('default','autoforward') NOT NULL default 'default',
  `cd_orderpage_useraccount` tinyint(1) NOT NULL default '0',
  `cd_pay_status` enum('payable','unpayable') NOT NULL default 'payable',
  `cd_approve_timelimit` tinyint(4) NOT NULL default '24',
  `cd_th_ID` mediumint(9) default NULL,
  `cd_orderpage_disable_fraud_checks` tinyint(1) NOT NULL default '0',
  `cd_unassoc_fields` blob NOT NULL,
  PRIMARY KEY  (`userId`),
  UNIQUE KEY `ReferenceNumber` (`ReferenceNumber`),
  KEY `username` (`username`),
  KEY `companyname` (`companyname`),
  KEY `email` (`email`),
  KEY `cd_ignore` (`cd_ignore`),
  KEY `cd_th_ID` (`cd_th_ID`),
  KEY `company_bank` (`company_bank`),
  KEY `activeuser` (`activeuser`),
  KEY `cd_completion` (`cd_completion`),
  KEY `date_added` (`date_added`),
  KEY `password` (`password`),
  KEY `reseller_id` (`reseller_id`),
  KEY `transaction_type` (`transaction_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=140461 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_dnc_emails`
--

CREATE TABLE IF NOT EXISTS `cs_dnc_emails` (
  `dnc_id` int(11) NOT NULL auto_increment,
  `dnc_email` varchar(255) NOT NULL default '',
  `gateway_id` int(11) default '-1',
  PRIMARY KEY  (`dnc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_email_lists`
--

CREATE TABLE IF NOT EXISTS `cs_email_lists` (
  `ec_ID` mediumint(9) NOT NULL auto_increment,
  `ec_email` varchar(90) NOT NULL default '',
  `ec_action` enum('unsubscribe') NOT NULL default 'unsubscribe',
  `ec_type` enum('unknown','customer','merchant','reseller','misc') NOT NULL default 'unknown',
  `ec_item_ID` int(11) NOT NULL default '-1',
  `ec_reason` text,
  PRIMARY KEY  (`ec_ID`),
  UNIQUE KEY `ec_email` (`ec_email`),
  KEY `ec_type` (`ec_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=696 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_email_templates`
--

CREATE TABLE IF NOT EXISTS `cs_email_templates` (
  `et_id` mediumint(9) NOT NULL auto_increment,
  `et_name` varchar(45) NOT NULL default '',
  `et_language` enum('eng','spa','fre','ger','ita','por') NOT NULL default 'eng',
  `et_custom_id` int(11) default NULL,
  `et_title` varchar(50) NOT NULL default '',
  `et_access` enum('merchant','reseller','admin') NOT NULL default 'admin',
  `et_subject` varchar(200) NOT NULL default '',
  `et_from` varchar(100) NOT NULL default '',
  `et_from_title` varchar(60) NOT NULL default '',
  `et_to` varchar(255) NOT NULL default '[email]',
  `et_to_title` varchar(80) NOT NULL default '[full_name]',
  `et_textformat` text NOT NULL,
  `et_htmlformat` longtext NOT NULL,
  `et_vars` mediumtext NOT NULL,
  `et_catagory` enum('Customer','Merchant','Reseller','Admin','Support') NOT NULL default 'Customer',
  PRIMARY KEY  (`et_id`),
  KEY `et_name` (`et_name`),
  KEY `et_language` (`et_language`),
  KEY `et_catagory` (`et_catagory`),
  KEY `et_custom_id` (`et_custom_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Email Templates' AUTO_INCREMENT=209 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_entities`
--

CREATE TABLE IF NOT EXISTS `cs_entities` (
  `en_ID` int(11) NOT NULL auto_increment,
  `en_ref` varchar(32) character set latin1 collate latin1_general_ci default NULL,
  `en_username` varchar(64) character set latin1 collate latin1_general_ci NOT NULL default '',
  `en_password` varchar(64) character set latin1 collate latin1_general_ci NOT NULL default '',
  `en_password2` varchar(64) character set latin1 collate latin1_general_ci NOT NULL default '',
  `en_company` varchar(64) character set latin1 collate latin1_general_ci NOT NULL default '',
  `en_status` enum('active','inactive','ignored') NOT NULL default 'active',
  `en_firstname` varchar(64) character set latin1 collate latin1_general_ci NOT NULL default '',
  `en_mi` char(1) character set latin1 collate latin1_general_ci NOT NULL default '',
  `en_lastname` varchar(64) character set latin1 collate latin1_general_ci NOT NULL default '',
  `en_email` varchar(64) character set latin1 collate latin1_general_ci NOT NULL default '',
  `en_gateway_ID` tinyint(4) NOT NULL default '3',
  `en_signup` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `en_last_login` timestamp NULL default NULL,
  `en_last_IP` varchar(15) character set latin1 collate latin1_general_ci default NULL,
  `en_type` enum('merchant','reseller','bank','admin','service','processor') character set latin1 collate latin1_general_ci NOT NULL default 'merchant',
  `en_type_ID` int(11) default NULL,
  `en_info` text character set latin1 collate latin1_general_ci,
  `en_pay_type` enum('Weekly','Monthly','None') default 'Monthly',
  `en_pay_data` int(11) unsigned default '32770',
  `en_access` bigint(20) default NULL,
  `en_ev_pref` text character set latin1 collate latin1_general_ci,
  `en_ev_grp_id` int(11) default NULL,
  `en_ev_customer_id` int(11) default NULL,
  `en_ev_contact_id` int(11) default NULL,
  `en_ev_sms_email` varchar(64) character set latin1 collate latin1_general_ci default NULL,
  `en_ev_clocked_in` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`en_ID`),
  UNIQUE KEY `en_username` (`en_username`),
  UNIQUE KEY `en_email` (`en_email`),
  UNIQUE KEY `en_ref` (`en_ref`),
  KEY `en_type` (`en_type`,`en_type_ID`),
  KEY `en_username_2` (`en_username`,`en_password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15638 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_entities_affiliates`
--

CREATE TABLE IF NOT EXISTS `cs_entities_affiliates` (
  `ea_ID` int(11) NOT NULL auto_increment,
  `ea_en_ID` int(11) NOT NULL default '0',
  `ea_affiliate_ID` int(11) NOT NULL default '0',
  `ea_type` enum('Reseller','Affiliate','Representative') NOT NULL default 'Reseller',
  `ea_info` text,
  PRIMARY KEY  (`ea_ID`),
  UNIQUE KEY `ea_en_ID` (`ea_en_ID`,`ea_affiliate_ID`,`ea_type`),
  KEY `ea_en_ID_2` (`ea_en_ID`,`ea_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Affiliate Relation Between Entities' AUTO_INCREMENT=1461 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_errors`
--

CREATE TABLE IF NOT EXISTS `cs_errors` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime default NULL,
  `filename` varchar(150) default NULL,
  `error_num` varchar(100) default NULL,
  `error_desc` varchar(250) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_etel_invoices`
--

CREATE TABLE IF NOT EXISTS `cs_etel_invoices` (
  `ei_ID` int(11) NOT NULL auto_increment,
  `ei_user_ID` int(11) NOT NULL default '0',
  `ei_create_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ei_pay_date` date NOT NULL default '0000-00-00',
  `ei_title` varchar(100) NOT NULL default '',
  `ei_balance` decimal(10,2) NOT NULL default '0.00',
  `ei_deduction` decimal(10,2) NOT NULL default '0.00',
  `ei_pay_info` text NOT NULL,
  `ei_status` enum('Pending','WireSent','WireSuccess','WireFailure') NOT NULL default 'Pending',
  `ei_type` enum('merchant','reseller','bankprofit','admin') NOT NULL default 'admin',
  PRIMARY KEY  (`ei_ID`),
  KEY `ei_user_ID` (`ei_user_ID`),
  KEY `ei_pay_date` (`ei_pay_date`),
  KEY `ei_status` (`ei_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_invoicecurrencydetails`
--

CREATE TABLE IF NOT EXISTS `cs_invoicecurrencydetails` (
  `invoicecurrencyId` int(15) NOT NULL auto_increment,
  `invoiceId` int(15) default NULL,
  `userId` int(15) NOT NULL default '0',
  `gatewayid` int(15) NOT NULL default '-1',
  `totalAmt` double default '0',
  `approvedAmt` double default '0',
  `declinedAmt` double default '0',
  `creditAmt` double default '0',
  `pendingamt` double default '0',
  `chargeBack` double default '0',
  `credit` double default '0',
  `discount` double default '0',
  `transactionFee` double default '0',
  `voiceAuthorisation_fee` double default '0',
  `reserveFee` double default '0',
  `totalDeductions` double default '0',
  `netAmount` double default '0',
  `adminApproved` varchar(2) default 'N',
  `generateddate` datetime default '0000-00-00 00:00:00',
  `startdate` date default '0000-00-00',
  `enddate` date default '0000-00-00',
  `checkorcard` char(1) default NULL,
  `processingcurrency` varchar(4) NOT NULL default 'USD',
  `transactionno` int(11) default '0',
  `approvedno` int(11) default '0',
  `declinedno` int(11) default '0',
  `pendingno` int(11) default '0',
  `creditno` int(11) default '0',
  `voiceuploadfee` int(15) default '0',
  `chargebackno` int(11) default '0',
  `resellerid` int(15) default '0',
  `canceledno` int(15) default '0',
  `nopass` double default '0',
  `nopasscount` int(15) default '0',
  `passed` double default '0',
  `passedcount` int(15) default '0',
  `voiceuploadcount` int(15) default '0',
  `miscadd` double default '0',
  `miscsub` double default '0',
  `miscadd_disc` varchar(150) NOT NULL default '',
  `miscsub_disc` varchar(150) NOT NULL default '',
  `wirefee` int(11) NOT NULL default '0',
  `approveddate` datetime default '0000-00-00 00:00:00',
  `bank_id` int(11) default NULL,
  `reject_count` int(15) default '0',
  `reject_amt` double default '0',
  `reject_creditamt` double default '0',
  `reject_chargebackamt` double default '0',
  `reject_transfee` double default '0',
  `cancel_startdate` datetime default '0000-00-00 00:00:00',
  `cancel_enddate` datetime default '0000-00-00 00:00:00',
  `reject_creditcount` int(15) default '0',
  `reject_chargebackcount` int(15) default '0',
  PRIMARY KEY  (`invoicecurrencyId`),
  KEY `invoicecurrencyId` (`invoicecurrencyId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_invoicedetails`
--

CREATE TABLE IF NOT EXISTS `cs_invoicedetails` (
  `invoiceId` int(15) NOT NULL auto_increment,
  `invoiceReferenceNumber` varchar(30) default NULL,
  `userId` int(15) NOT NULL default '0',
  `gatewayid` int(15) NOT NULL default '-1',
  `totalAmt` double default '0',
  `approvedAmt` double default '0',
  `declinedAmt` double default '0',
  `creditAmt` double default '0',
  `pendingamt` double default '0',
  `chargeBack` double default '0',
  `credit` double default '0',
  `discount` double default '0',
  `transactionFee` double default '0',
  `voiceAuthorisation_fee` double default '0',
  `reserveFee` double default '0',
  `totalDeductions` double default '0',
  `netAmount` double default '0',
  `adminApproved` varchar(2) default 'N',
  `generateddate` datetime default '0000-00-00 00:00:00',
  `startdate` date default '0000-00-00',
  `enddate` date default '0000-00-00',
  `checkorcard` char(1) default NULL,
  `processingcurrency` varchar(4) NOT NULL default 'USD',
  `transactionno` int(11) default '0',
  `approvedno` int(11) default '0',
  `declinedno` int(11) default '0',
  `pendingno` int(11) default '0',
  `creditno` int(11) default '0',
  `voiceuploadfee` int(15) default '0',
  `chargebackno` int(11) default '0',
  `resellerid` int(15) default '0',
  `canceledno` int(15) default '0',
  `nopass` double default '0',
  `nopasscount` int(15) default '0',
  `passed` double default '0',
  `passedcount` int(15) default '0',
  `voiceuploadcount` int(15) default '0',
  `miscadd` double default '0',
  `miscsub` double default '0',
  `miscadd_disc` varchar(150) default NULL,
  `miscsub_disc` varchar(150) default NULL,
  `wirefee` double default '0',
  `approveddate` datetime default '0000-00-00 00:00:00',
  `bank_id` int(11) default NULL,
  `reject_count` int(15) default '0',
  `reject_amt` double default '0',
  `reject_creditamt` double default '0',
  `reject_chargebackamt` double default '0',
  `reject_transfee` double default '0',
  `cancel_startdate` datetime default '0000-00-00 00:00:00',
  `cancel_enddate` datetime default '0000-00-00 00:00:00',
  `reject_creditcount` int(15) default '0',
  `reject_chargebackcount` int(15) default '0',
  PRIMARY KEY  (`invoiceId`),
  KEY `invoiceId` (`invoiceId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=106 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_invoice_history`
--

CREATE TABLE IF NOT EXISTS `cs_invoice_history` (
  `ih_inv_ID` int(11) NOT NULL auto_increment,
  `userId` int(11) NOT NULL default '0',
  `ih_weekid` int(11) NOT NULL default '0',
  `ih_date` bigint(20) NOT NULL default '0',
  `ih_date_payed` date NOT NULL default '0000-00-00',
  `ih_net` float NOT NULL default '0',
  `ih_rollover` float NOT NULL default '0',
  `ih_monthlyfee` float NOT NULL default '0',
  `ih_wirefee` float NOT NULL default '0',
  `ih_balance` float NOT NULL default '0',
  `companyname` varchar(100) NOT NULL default '',
  `merchantName` varchar(250) default NULL,
  `credit` double default '0',
  `discountrate` double default '0',
  `transactionfee` double default '0',
  `reserve` double default '0',
  `voiceauthfee` double default '0',
  `contactname` varchar(100) default NULL,
  `cd_payperiod` smallint(6) NOT NULL default '7',
  `cd_paystartday` smallint(6) NOT NULL default '2',
  `cd_paydelay` smallint(6) NOT NULL default '21',
  `cd_rollover` smallint(6) NOT NULL default '500',
  `cd_wirefee` smallint(6) NOT NULL default '50',
  `cd_appfee` smallint(6) NOT NULL default '0',
  `cd_paydaystartday` tinyint(4) NOT NULL default '0',
  `cd_enable_price_points` tinyint(1) NOT NULL default '1',
  `cd_enable_rand_pricing` tinyint(1) NOT NULL default '0',
  `cc_chargeback` double NOT NULL default '0',
  `cc_discountrate` double NOT NULL default '0',
  `cc_reserve` double NOT NULL default '0',
  `ch_chargeback` double NOT NULL default '0',
  `ch_discountrate` double NOT NULL default '0',
  `ch_reserve` double NOT NULL default '0',
  `web_chargeback` double NOT NULL default '0',
  `web_discountrate` double NOT NULL default '0',
  `web_reserve` double NOT NULL default '0',
  `cc_merchant_discount_rate` double NOT NULL default '0',
  `cc_reseller_discount_rate` double NOT NULL default '0',
  `cc_total_discount_rate` double NOT NULL default '0',
  `cc_merchant_trans_fees` double NOT NULL default '0',
  `cc_reseller_trans_fees` double NOT NULL default '0',
  `cc_total_trans_fees` double NOT NULL default '0',
  `ch_merchant_discount_rate` double NOT NULL default '0',
  `ch_reseller_discount_rate` double NOT NULL default '0',
  `ch_total_discount_rate` decimal(10,0) NOT NULL default '0',
  `ch_merchant_trans_fees` double NOT NULL default '0',
  `ch_reseller_trans_fees` double NOT NULL default '0',
  `ch_total_trans_fees` double NOT NULL default '0',
  `web_merchant_trans_fees` double NOT NULL default '0',
  `web_reseller_trans_fees` double NOT NULL default '0',
  `web_total_trans_fees` double NOT NULL default '0',
  `cc_billingdescriptor` varchar(150) NOT NULL default '',
  `ch_billingdescriptor` varchar(150) NOT NULL default '',
  `cs_monthly_charge` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`ih_inv_ID`),
  KEY `ih_weekid` (`ih_weekid`),
  KEY `userId` (`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_invoice_setup`
--

CREATE TABLE IF NOT EXISTS `cs_invoice_setup` (
  `invoice_id` int(11) NOT NULL auto_increment,
  `company_id` int(11) default NULL,
  `freequency` char(1) default NULL,
  `no_days_back` int(11) default NULL,
  `from_week_day` int(11) default NULL,
  `to_week_day` int(11) default NULL,
  `misc_fee` double default NULL,
  PRIMARY KEY  (`invoice_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_ivr_log`
--

CREATE TABLE IF NOT EXISTS `cs_ivr_log` (
  `iv_call_id` varchar(255) default NULL,
  `iv_datetime` varchar(32) default NULL,
  `iv_phone` varchar(32) default NULL,
  `iv_page_name` varchar(255) default NULL,
  `iv_query` text,
  `iv_duration` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_live_tree`
--

CREATE TABLE IF NOT EXISTS `cs_live_tree` (
  `lt_ID` int(11) NOT NULL auto_increment,
  `lt_parent_ID` int(11) default NULL,
  `lt_subject` varchar(100) NOT NULL default '',
  `lt_option_text` varchar(255) NOT NULL default '',
  `lt_question_text` text NOT NULL,
  `lt_action` enum('branch','url','searchtrans','searchmerc','branchonsuccess','refund','cancel','transaction','changepass','redir') NOT NULL default 'branch',
  `lt_action_item` varchar(100) default NULL,
  `lt_status` enum('pending','complete') NOT NULL default 'pending',
  `lt_type` enum('all','customer','merchant') NOT NULL default 'all',
  PRIMARY KEY  (`lt_ID`),
  KEY `lt_parent_ID` (`lt_parent_ID`,`lt_action`),
  KEY `lt_status` (`lt_status`),
  KEY `lt_action` (`lt_action`),
  KEY `lt_subject` (`lt_subject`),
  KEY `lt_option_text` (`lt_option_text`),
  KEY `lt_type` (`lt_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_log`
--

CREATE TABLE IF NOT EXISTS `cs_log` (
  `lg_id` bigint(20) NOT NULL auto_increment,
  `lg_action` enum('rebill','email','misc','error','order','login','notify','hackattempt','erroralert','pendingwebsite','pendingdocuments','requestrates','resellerrequestrates','requestlive','completedapplication','turnedlive','requestmarkup') NOT NULL default 'misc',
  `lg_actor` enum('misc','customer','merchant','reseller','admin','system','service','bank') NOT NULL default 'system',
  `lg_item_id` int(11) default NULL,
  `lg_txt` text,
  `lg_timestamp` int(11) default NULL,
  PRIMARY KEY  (`lg_id`),
  KEY `lg_actor` (`lg_actor`),
  KEY `lg_item_id` (`lg_item_id`),
  KEY `lg_action` (`lg_action`),
  KEY `lg_timestamp` (`lg_timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2283267 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_login`
--

CREATE TABLE IF NOT EXISTS `cs_login` (
  `userid` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `li_email` varchar(100) NOT NULL default '',
  `li_level` enum('full','readonly','bank','gateway','risk','customerservice','singleview') NOT NULL default 'full',
  `li_gw_ID` tinyint(4) NOT NULL default '3',
  `li_bank` mediumint(9) NOT NULL default '-1',
  `li_type` enum('none','credit','check') NOT NULL default 'none',
  `li_singleview` varchar(255) NOT NULL default '',
  `li_singleview_allow` varchar(255) NOT NULL default '',
  `li_debug` tinyint(1) NOT NULL default '0',
  `li_show_all_profit` tinyint(1) NOT NULL default '0',
  `li_config` text,
  `li_user_view` enum('none','hide','show','all') NOT NULL default 'all',
  `li_commission_type` enum('none','some','all') NOT NULL default 'none',
  `li_commission` decimal(10,2) NOT NULL default '0.00',
  `li_commission_recieved` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `li_email` (`li_email`),
  KEY `li_level` (`li_level`),
  KEY `li_bank` (`li_bank`),
  KEY `li_type` (`li_type`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_logo`
--

CREATE TABLE IF NOT EXISTS `cs_logo` (
  `logo_id` int(25) NOT NULL auto_increment,
  `logo_filename` varchar(250) NOT NULL default '',
  `logo_company_id` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`logo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_mailtemplate`
--

CREATE TABLE IF NOT EXISTS `cs_mailtemplate` (
  `template_id` int(11) NOT NULL auto_increment,
  `template_name` varchar(250) default NULL,
  `template_content` text,
  `gateway_id` int(11) default '-1',
  PRIMARY KEY  (`template_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_merchantusers`
--

CREATE TABLE IF NOT EXISTS `cs_merchantusers` (
  `userId` int(11) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(30) NOT NULL default '',
  `companyname` varchar(100) NOT NULL default '',
  `phonenumber` varchar(25) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `zipcode` varchar(10) default NULL,
  `email` varchar(100) NOT NULL default '',
  `suspenduser` varchar(3) NOT NULL default '',
  `ostate` varchar(100) NOT NULL default '',
  `merchantName` varchar(250) default NULL,
  `tollFreeNumber` varchar(100) default NULL,
  `retrievalNumber` varchar(100) default NULL,
  `securityNumber` varchar(100) default NULL,
  `processor` varchar(100) default NULL,
  `chargeback` double default '0',
  `credit` double default '0',
  `discountrate` double default '0',
  `transactionfee` double default '0',
  `reserve` double default '0',
  `voiceauthfee` double default '0',
  `auto_cancel` char(1) default 'N',
  `time_frame` int(11) default '-1',
  `auto_approve` char(1) default 'N',
  `transaction_type` varchar(4) NOT NULL default '',
  `activeuser` int(11) NOT NULL default '1',
  `contactname` varchar(100) default NULL,
  `volumenumber` int(11) default '0',
  `shipping_cancel` char(1) NOT NULL default 'N',
  `shipping_timeframe` int(11) default '-1',
  `telepackagename` varchar(100) default NULL,
  `telepackageprod` varchar(100) default NULL,
  `telepackageprice` double default '0',
  `telerefundpolicy` varchar(100) default NULL,
  `teledescription` text,
  `avgticket` float default '0',
  `chargebackper` float default '0',
  `preprocess` varchar(4) default 'No',
  `recurbilling` varchar(4) default 'No',
  `currprocessing` varchar(4) default 'No',
  `url1` varchar(100) default NULL,
  `url2` varchar(100) default NULL,
  `url3` varchar(100) default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `how_about_us` varchar(100) default NULL,
  `companyadmin_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_merchant_invoice`
--

CREATE TABLE IF NOT EXISTS `cs_merchant_invoice` (
  `mi_ID` int(11) NOT NULL auto_increment,
  `mi_company_id` int(11) NOT NULL default '0',
  `mi_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `mi_paydate` date NOT NULL default '0000-00-00',
  `mi_title` varchar(255) NOT NULL default 'Invoice',
  `mi_balance` decimal(10,2) NOT NULL default '0.00',
  `mi_deduction` decimal(10,2) NOT NULL default '0.00',
  `mi_notes` text NOT NULL,
  `mi_pay_info` text NOT NULL,
  `mi_company_info` text NOT NULL,
  `mi_status` enum('Pending','WireSent','WireSuccess','WireFailure') NOT NULL default 'Pending',
  PRIMARY KEY  (`mi_ID`),
  KEY `mi_company_id` (`mi_company_id`,`mi_date`),
  KEY `mi_date` (`mi_date`),
  KEY `mi_paydate` (`mi_paydate`),
  KEY `mi_status` (`mi_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8488 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_merchant_invoice_banksub`
--

CREATE TABLE IF NOT EXISTS `cs_merchant_invoice_banksub` (
  `mib_ID` int(11) NOT NULL auto_increment,
  `mib_mi_ID` int(11) NOT NULL default '0',
  `mib_bank_id` smallint(6) NOT NULL default '0',
  `mib_company_id` int(11) NOT NULL default '0',
  `mib_wire_type` enum('us','non-us') NOT NULL default 'us',
  `mib_paid` int(9) NOT NULL default '0',
  `mib_balance` decimal(10,2) NOT NULL default '0.00',
  `mib_etelDeduction` decimal(10,2) NOT NULL default '0.00',
  `mib_wire_fee` decimal(10,2) NOT NULL default '0.00',
  `mib_monthly_fee` decimal(10,2) NOT NULL default '0.00',
  `mib_setup_fee` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`mib_ID`),
  KEY `mib_mi_ID` (`mib_mi_ID`,`mib_bank_id`,`mib_paid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35450 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_misc_fees`
--

CREATE TABLE IF NOT EXISTS `cs_misc_fees` (
  `mf_ID` int(11) NOT NULL auto_increment,
  `mf_entity` int(11) default NULL,
  `mf_amount` decimal(10,2) default NULL,
  `mf_date` date default NULL,
  `mf_description` varchar(100) default NULL,
  `mf_invoice_type` enum('merchant','reseller','bank') default NULL,
  `mf_paid` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`mf_ID`),
  KEY `mf_date` (`mf_date`,`mf_invoice_type`),
  KEY `mf_paid` (`mf_paid`),
  KEY `mf_entity` (`mf_entity`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1038 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_niches`
--

CREATE TABLE IF NOT EXISTS `cs_niches` (
  `ni_ID` int(11) NOT NULL auto_increment,
  `ni_desc` varchar(30) NOT NULL default '',
  `ni_cat` enum('Tangible','Adult WebCam','Adult General','Adult Services') NOT NULL default 'Adult General',
  `ni_bin_val` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ni_ID`),
  KEY `ni_cat` (`ni_cat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_orderemail`
--

CREATE TABLE IF NOT EXISTS `cs_orderemail` (
  `id` int(11) NOT NULL auto_increment,
  `emailaddress` varchar(50) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `gateway_id` int(11) default '-1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=118 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_pincodes`
--

CREATE TABLE IF NOT EXISTS `cs_pincodes` (
  `pc_ID` int(11) NOT NULL auto_increment,
  `pc_subName` varchar(25) NOT NULL default '',
  `pc_subAccount` int(11) NOT NULL default '0',
  `pc_type` enum('pincode','userpass') NOT NULL default 'pincode',
  `pc_code` varchar(60) NOT NULL default '',
  `pc_pass` varchar(60) default NULL,
  `pc_used` tinyint(1) NOT NULL default '0',
  `pc_trans_ID` int(11) default NULL,
  PRIMARY KEY  (`pc_ID`),
  KEY `pc_used` (`pc_used`),
  KEY `pc_trans_ID` (`pc_trans_ID`),
  KEY `pc_company_ID` (`pc_subName`),
  KEY `pc_type` (`pc_type`),
  KEY `pc_subAccount` (`pc_subAccount`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=725536 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_profit`
--

CREATE TABLE IF NOT EXISTS `cs_profit` (
  `pt_ID` bigint(20) NOT NULL auto_increment,
  `pt_amount` decimal(23,12) NOT NULL default '0.000000000000',
  `pt_date_effective` date NOT NULL default '0000-00-00',
  `pt_action_ID` int(11) NOT NULL default '-1',
  `pt_to_entity_ID` int(11) NOT NULL default '-1',
  `pt_from_entity_ID` int(11) NOT NULL default '-1',
  `pt_type` enum('Bank Chargeback Fee','Bank Customer Fee','Bank Customer Fee Returned','Bank Discount Fee','Bank Refund Fee','Bank Refund/CB Amount','Bank Sale Funds','Bank Transaction Fee','Bank Reserve Release','Bank Reserve Release Returned','Chargeback Fee','Discount Fee','Refund Fee','Refund/CB Amount','Reserve Release','Reserve Release Returned','Sale Funds','Transaction Fee','Payout','Adjustment','Monthly Fee','Setup Fee','Funds Transfer Fee','Bank Withheld') NOT NULL default 'Adjustment',
  PRIMARY KEY  (`pt_ID`),
  KEY `pt_type` (`pt_type`),
  KEY `pt_action_ID` (`pt_action_ID`),
  KEY `pt_to_entity_ID` (`pt_to_entity_ID`),
  KEY `pt_from_entity_ID` (`pt_from_entity_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=9076267 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_profit_action`
--

CREATE TABLE IF NOT EXISTS `cs_profit_action` (
  `pa_ID` int(11) NOT NULL auto_increment,
  `pa_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `pa_desc` varchar(250) NOT NULL default '',
  `pa_type` enum('Transaction','Payout','Adjustment','Monthly Fee','Setup Fee','Withheld') NOT NULL default 'Transaction',
  `pa_info` text,
  `pa_status` enum('pending','fail','success','void','payout_pending','payout_sent','payout_failed','payout_rollover','delete') NOT NULL default 'pending',
  `pa_bank_id` tinyint(4) default NULL,
  `pa_trans_id` int(11) default NULL,
  `pa_en_ID` int(11) default NULL,
  PRIMARY KEY  (`pa_ID`),
  UNIQUE KEY `pa_trans_id` (`pa_trans_id`),
  KEY `pa_date` (`pa_date`),
  KEY `pa_status` (`pa_status`),
  KEY `pa_type` (`pa_type`),
  KEY `pa_en_ID` (`pa_en_ID`),
  KEY `pa_type_2` (`pa_type`,`pa_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Profit Actions' AUTO_INCREMENT=958231 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_profit_options`
--

CREATE TABLE IF NOT EXISTS `cs_profit_options` (
  `po_ID` int(11) NOT NULL auto_increment,
  `po_user_ID` int(11) default NULL,
  `po_entity` enum('bankprofit','merchant','reseller') NOT NULL default 'bankprofit',
  `po_limit_type` enum('merchant','reseller','bank') default NULL,
  `po_limit_by` enum('all','include','exclude') NOT NULL default 'all',
  `po_limit_ID` int(11) default NULL,
  `po_limit_sdate` date default NULL,
  `po_limit_fdate` date default NULL,
  `po_comission_type` enum('profit') NOT NULL default 'profit',
  `po_commission_percent` decimal(3,2) default '0.00',
  PRIMARY KEY  (`po_ID`),
  KEY `po_user_ID` (`po_user_ID`,`po_entity`,`po_limit_type`,`po_limit_by`,`po_limit_ID`),
  KEY `po_commission_percent` (`po_commission_percent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_rateandfees`
--

CREATE TABLE IF NOT EXISTS `cs_rateandfees` (
  `id` int(11) NOT NULL auto_increment,
  `chargeback` double NOT NULL default '0',
  `credit` double NOT NULL default '0',
  `discountrate` double default '0',
  `transactionfee` double NOT NULL default '0',
  `reserve` double default '0',
  `merchant_discount_rate` double NOT NULL default '0',
  `reseller_discount_rate` double NOT NULL default '0',
  `total_trans_fees` double NOT NULL default '0',
  `date` datetime default NULL,
  `userId` int(11) NOT NULL default '0',
  `reseller_trans_fees` double NOT NULL default '0',
  `voiceauthfee` double NOT NULL default '0',
  `total_discount_rate` double default '0',
  `merchant_trans_fees` double default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=270 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_rebillingdetails`
--

CREATE TABLE IF NOT EXISTS `cs_rebillingdetails` (
  `rd_subaccount` int(11) unsigned NOT NULL auto_increment,
  `rd_subName` varchar(12) NOT NULL default '00101',
  `rd_en_ID` int(11) NOT NULL default '0',
  `recur_day` int(11) default NULL,
  `recur_charge` double default NULL,
  `rd_recur_enabled` tinyint(1) NOT NULL default '0',
  `company_user_id` int(11) default '0',
  `rd_initial_amount` double NOT NULL default '0',
  `rd_trial_days` smallint(3) NOT NULL default '0',
  `rd_trial_enabled` tinyint(1) NOT NULL default '0',
  `rd_enabled` enum('Yes','No') NOT NULL default 'Yes',
  `rd_description` varchar(60) NOT NULL default '',
  `rd_web900_support` tinyint(1) NOT NULL default '0',
  `rd_pin_coding_enabled` tinyint(1) NOT NULL default '0',
  `rd_ibill_landing_html` text,
  `rd_hide` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`rd_subaccount`),
  UNIQUE KEY `rd_subName` (`rd_subName`),
  KEY `company_user_id` (`company_user_id`),
  KEY `rd_hide` (`rd_hide`),
  KEY `recur_enabled` (`rd_recur_enabled`),
  KEY `rd_en_ID` (`rd_en_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37309 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_registrationmail`
--

CREATE TABLE IF NOT EXISTS `cs_registrationmail` (
  `mail_id` int(11) NOT NULL auto_increment,
  `mail_sent` int(1) NOT NULL default '0',
  PRIMARY KEY  (`mail_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_reports`
--

CREATE TABLE IF NOT EXISTS `cs_reports` (
  `rp_ID` smallint(6) NOT NULL auto_increment,
  `rp_title` varchar(100) NOT NULL default '',
  `rp_content` text NOT NULL,
  `rp_period` int(11) NOT NULL default '30',
  `rp_severity` enum('none','mild','moderate','severe') NOT NULL default 'none',
  `rp_projected_severity` enum('none','mild','moderate','severe') NOT NULL default 'none',
  `rp_source_query` text NOT NULL,
  `rp_destination_query` text NOT NULL,
  `rp_notify_severity` enum('none','mild','moderate','severe','never') NOT NULL default 'never',
  `rp_notify_email` varchar(255) NOT NULL default '',
  `rp_POST` text NOT NULL,
  `rp_bank_id` smallint(6) NOT NULL default '-1',
  PRIMARY KEY  (`rp_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Report Table' AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_resellerdetails`
--

CREATE TABLE IF NOT EXISTS `cs_resellerdetails` (
  `reseller_id` int(11) NOT NULL auto_increment,
  `reseller_name` varchar(150) NOT NULL default '',
  `reseller_address` text NOT NULL,
  `reseller_username` varchar(100) NOT NULL default '',
  `reseller_password` varchar(100) NOT NULL default '',
  `reseller_date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `reseller_companyname` varchar(150) default NULL,
  `reseller_contactname` varchar(100) default NULL,
  `reseller_email` varchar(150) default NULL,
  `reseller_phone` varchar(30) default NULL,
  `reseller_url` varchar(150) default NULL,
  `reseller_monthly_volume` varchar(10) default NULL,
  `reseller_title` varchar(6) default NULL,
  `reseller_firstname` varchar(100) default NULL,
  `reseller_lastname` varchar(100) default NULL,
  `reseller_sex` varchar(6) default NULL,
  `reseller_zipcode` varchar(10) default NULL,
  `reseller_jobtitle` varchar(150) default NULL,
  `reseller_res_phone` varchar(25) default NULL,
  `reseller_faxnumber` varchar(25) default NULL,
  `completed_reseller_application` int(11) default '0',
  `general_merchant_sign` int(11) default '0',
  `general_participant_sign` int(11) default '0',
  `schedule_merchant_sign` int(11) default '0',
  `schedule_participant_sign` int(11) default '0',
  `reseller_sendmail` int(11) default '1',
  `reseller_bankname` varchar(150) default NULL,
  `reseller_otherbank` varchar(150) default NULL,
  `bank_benificiaryname` varchar(150) default NULL,
  `bank_accountname` varchar(150) default NULL,
  `bank_address` varchar(150) default NULL,
  `bank_country` varchar(150) default NULL,
  `bank_telephone` varchar(30) default NULL,
  `bank_sortcode` varchar(50) default NULL,
  `bank_accountno` varchar(50) default NULL,
  `bank_routing_no` bigint(20) default NULL,
  `bank_swiftcode` varchar(50) default NULL,
  `merchant_discount_rate` double default '0',
  `reseller_discount_rate` double default '0',
  `total_discount_rate` double default '0',
  `merchant_trans_fees` double default '0',
  `reseller_trans_fees` double default '0',
  `total_trans_fees` double default '0',
  `reseller_url1` varchar(150) default NULL,
  `reseller_url2` varchar(150) default NULL,
  `suspend_reseller` int(11) default '0',
  `rd_gateway_id` int(11) default '-1',
  `rd_subgateway_id` int(11) default NULL,
  `rd_subgateway_bank_id` smallint(6) default NULL,
  `BICcode` varchar(50) default NULL,
  `VATnumber` varchar(50) default NULL,
  `registrationNo` varchar(50) default NULL,
  `rd_paystartday` smallint(6) NOT NULL default '1',
  `rd_paydelay` smallint(6) NOT NULL default '14',
  `rd_rollover` smallint(6) NOT NULL default '50',
  `rd_wirefee` smallint(6) NOT NULL default '50',
  `rd_referenceNumber` varchar(8) NOT NULL default '',
  `rd_next_pay_day` date NOT NULL default '0000-00-00',
  `rd_completion` tinyint(4) NOT NULL default '0',
  `rd_bank_instructions` text NOT NULL,
  `rd_bank_routingnumber` bigint(20) NOT NULL default '0',
  `rd_bank_routingcode` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`reseller_id`),
  KEY `rd_referenceNumber` (`rd_referenceNumber`),
  KEY `reseller_bankname` (`reseller_bankname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=254 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_reseller_invoice`
--

CREATE TABLE IF NOT EXISTS `cs_reseller_invoice` (
  `ri_ID` int(11) NOT NULL auto_increment,
  `ri_reseller_id` int(11) NOT NULL default '0',
  `ri_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ri_title` varchar(255) NOT NULL default 'Invoice',
  `ri_balance` decimal(10,2) NOT NULL default '0.00',
  `ri_deduction` decimal(10,2) NOT NULL default '0.00',
  `ri_notes` text NOT NULL,
  `ri_pay_info` text NOT NULL,
  `ri_company_info` text NOT NULL,
  `ri_status` enum('Pending','WireSent','WireSuccess','WireFailure') NOT NULL default 'Pending',
  PRIMARY KEY  (`ri_ID`),
  KEY `mi_company_id` (`ri_reseller_id`,`ri_date`),
  KEY `ri_status` (`ri_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_reseller_invoice_history`
--

CREATE TABLE IF NOT EXISTS `cs_reseller_invoice_history` (
  `ri_inv_ID` int(11) NOT NULL auto_increment,
  `ri_reseller_id` int(11) NOT NULL default '0',
  `ri_monthid` int(11) NOT NULL default '0',
  `ri_date` date NOT NULL default '0000-00-00',
  `ri_net` float NOT NULL default '0',
  `ri_rollover` float NOT NULL default '0',
  `ri_monthlyfee` float NOT NULL default '0',
  `ri_wirefee` float NOT NULL default '0',
  `ri_balance` float NOT NULL default '0',
  `ri_reseller_companyname` varchar(100) NOT NULL default '',
  `ri_reseller_contactname` varchar(250) default NULL,
  `rd_payperiod` smallint(6) NOT NULL default '7',
  `rd_paystartday` smallint(6) NOT NULL default '2',
  `rd_paydelay` smallint(6) NOT NULL default '21',
  `rd_rollover` smallint(6) NOT NULL default '500',
  `rd_wirefee` smallint(6) NOT NULL default '50',
  `rd_appfee` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`ri_inv_ID`),
  KEY `ih_weekid` (`ri_monthid`),
  KEY `userId` (`ri_reseller_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_risk_cron`
--

CREATE TABLE IF NOT EXISTS `cs_risk_cron` (
  `rc_company_id` int(11) NOT NULL default '0',
  `rc_results` blob NOT NULL,
  `rc_risk_value` int(11) NOT NULL default '0',
  `rc_date_time` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`rc_company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_risk_report`
--

CREATE TABLE IF NOT EXISTS `cs_risk_report` (
  `rr_report_settings` mediumblob NOT NULL,
  `rr_report_name` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`rr_report_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_risk_report_calc`
--

CREATE TABLE IF NOT EXISTS `cs_risk_report_calc` (
  `rrc_title` varchar(250) NOT NULL default '',
  `rrc_desc` varchar(250) NOT NULL default '',
  `rrc_equation` blob NOT NULL,
  `rrc_label` blob NOT NULL,
  `rrc_display` blob NOT NULL,
  PRIMARY KEY  (`rrc_title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_risk_report_dates`
--

CREATE TABLE IF NOT EXISTS `cs_risk_report_dates` (
  `rrd_name` varchar(100) NOT NULL default '',
  `rrd_title` varchar(100) NOT NULL default '',
  `rrd_from_day` varchar(100) NOT NULL default '0',
  `rrd_from_month` varchar(100) NOT NULL default '0',
  `rrd_from_year` varchar(100) NOT NULL default '0',
  `rrd_to_day` varchar(100) NOT NULL default '0',
  `rrd_to_month` varchar(100) NOT NULL default '0',
  `rrd_to_year` varchar(100) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_risk_report_projections`
--

CREATE TABLE IF NOT EXISTS `cs_risk_report_projections` (
  `rrp_name` varchar(250) NOT NULL default '',
  `rrp_title` varchar(250) NOT NULL default '',
  `rrp_equation` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`rrp_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_scanorder`
--

CREATE TABLE IF NOT EXISTS `cs_scanorder` (
  `id` int(11) NOT NULL auto_increment,
  `transactionId` varchar(30) default NULL,
  `amount` double default NULL,
  `transactionStatus` varchar(50) default NULL,
  `declineReason` varchar(250) default NULL,
  `checkSum` varchar(250) default NULL,
  `scanOrderId` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=293 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_setupfee`
--

CREATE TABLE IF NOT EXISTS `cs_setupfee` (
  `setupfee_id` int(11) NOT NULL auto_increment,
  `company_type_short` text NOT NULL,
  `company_type_long` text NOT NULL,
  `setupfee` double default '0',
  `gateway_setupfee` double default '0',
  PRIMARY KEY  (`setupfee_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_states`
--

CREATE TABLE IF NOT EXISTS `cs_states` (
  `st_full` char(40) NOT NULL default '',
  `st_abbrev` char(2) NOT NULL default '',
  PRIMARY KEY  (`st_abbrev`),
  KEY `st_full` (`st_full`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cs_subscription`
--

CREATE TABLE IF NOT EXISTS `cs_subscription` (
  `ss_ID` int(11) NOT NULL auto_increment,
  `ss_subscription_ID` varchar(32) NOT NULL default '',
  `ss_billing_firstname` varchar(32) default NULL,
  `ss_billing_mi` varchar(2) default NULL,
  `ss_billing_lastname` varchar(64) default NULL,
  `ss_billing_address` varchar(128) default NULL,
  `ss_billing_address2` varchar(128) default NULL,
  `ss_billing_city` varchar(64) default NULL,
  `ss_billing_state` varchar(3) default NULL,
  `ss_billing_country` varchar(3) default NULL,
  `ss_billing_zipcode` varchar(20) default NULL,
  `ss_billing_last_ip` varchar(15) default NULL,
  `ss_billing_type` enum('Credit','Check','Visa','Mastercard','Discover','JCB','Web900','EuroDebit') default NULL,
  `ss_billing_card` varchar(64) default NULL,
  `ss_billing_gkard` varchar(32) default NULL,
  `ss_billing_gkard_exp` date default NULL,
  `ss_billing_exp` date default NULL,
  `ss_billing_cvv2` varchar(4) default NULL,
  `ss_billing_check_account` varchar(64) default NULL,
  `ss_billing_check_routing` varchar(64) default NULL,
  `ss_salt` varchar(32) default NULL,
  `ss_cust_email` varchar(128) default NULL,
  `ss_cust_phone` varchar(24) default NULL,
  `ss_cust_username` varchar(64) default NULL,
  `ss_cust_password` varchar(64) default NULL,
  `ss_rebill_ID` int(11) default NULL,
  `ss_rebill_next_date` datetime default NULL,
  `ss_rebill_amount` decimal(10,2) default NULL,
  `ss_rebill_status` enum('inactive','active','processing') NOT NULL default 'inactive',
  `ss_rebill_status_text` varchar(255) default NULL,
  `ss_rebill_frozen` enum('no','yes','nocvv2','inactive_company') NOT NULL default 'no',
  `ss_rebill_attempts` tinyint(4) default NULL,
  `ss_rebill_count` tinyint(4) default NULL,
  `ss_account_status` enum('inactive','active') NOT NULL default 'inactive',
  `ss_account_start_date` datetime default NULL,
  `ss_account_expire_date` datetime default NULL,
  `ss_account_notes` text NOT NULL,
  `ss_cancel_id` varchar(32) default NULL,
  `ss_transaction_id` int(11) NOT NULL default '0',
  `ss_last_rebill` datetime default NULL,
  `ss_productdescription` varchar(128) default NULL,
  `ss_site_ID` int(11) default NULL,
  `ss_user_ID` int(11) NOT NULL default '0',
  `ss_bank_id` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`ss_ID`),
  UNIQUE KEY `ss_reference_ID` (`ss_subscription_ID`),
  KEY `ss_billing_lastname` (`ss_billing_lastname`),
  KEY `ss_cust_email` (`ss_cust_email`),
  KEY `ss_cust_username` (`ss_cust_username`),
  KEY `ss_rebill_ID` (`ss_rebill_ID`),
  KEY `ss_rebill_next_date` (`ss_rebill_next_date`),
  KEY `ss_rebill_status` (`ss_rebill_status`),
  KEY `ss_rebill_attempts` (`ss_rebill_attempts`),
  KEY `ss_account_status` (`ss_account_status`),
  KEY `ss_account_expire_date` (`ss_account_expire_date`),
  KEY `ss_cancel_id` (`ss_cancel_id`),
  KEY `ss_transaction_id` (`ss_transaction_id`),
  KEY `ss_user_ID` (`ss_user_ID`),
  KEY `ss_bank_id` (`ss_bank_id`),
  KEY `ss_rebill_frozen` (`ss_rebill_frozen`),
  KEY `ss_billing_type` (`ss_billing_type`),
  KEY `ss_cust_phone` (`ss_cust_phone`),
  KEY `ss_rebill_status_text` (`ss_rebill_status_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=119765 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_telemarketingusers`
--

CREATE TABLE IF NOT EXISTS `cs_telemarketingusers` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(250) NOT NULL default '',
  `password` varchar(250) NOT NULL default '',
  `company_id` int(11) NOT NULL default '0',
  `user_type` char(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='0 - TSR and 1- Call center' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_templates`
--

CREATE TABLE IF NOT EXISTS `cs_templates` (
  `tp_ID` int(11) NOT NULL auto_increment,
  `tp_userId` int(11) NOT NULL default '0',
  `tp_rd_subAccount` int(11) NOT NULL default '0',
  `tp_cs_ID` int(11) NOT NULL default '0',
  `tp_template_type` enum('order','approve','decline') NOT NULL default 'order',
  `tp_filename` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`tp_ID`),
  UNIQUE KEY `tp_userId` (`tp_userId`,`tp_rd_subAccount`,`tp_cs_ID`,`tp_template_type`),
  KEY `tp_userId_2` (`tp_userId`),
  KEY `tp_template_type` (`tp_template_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_test_transactiondetails`
--

CREATE TABLE IF NOT EXISTS `cs_test_transactiondetails` (
  `transactionId` int(11) NOT NULL auto_increment,
  `transactionDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(50) NOT NULL default '',
  `surname` varchar(50) default NULL,
  `phonenumber` bigint(18) default NULL,
  `address` varchar(100) default NULL,
  `CCnumber` varchar(40) default NULL,
  `cvv` varchar(4) default NULL,
  `checkorcard` enum('H','C','W') default NULL,
  `country` varchar(2) default NULL,
  `city` varchar(50) default NULL,
  `state` varchar(28) default NULL,
  `zipcode` varchar(25) default NULL,
  `amount` double NOT NULL default '0',
  `bankname` varchar(50) NOT NULL default '',
  `bankroutingcode` varchar(40) default NULL,
  `bankaccountnumber` varchar(40) default NULL,
  `accounttype` char(1) default NULL,
  `email` varchar(100) default NULL,
  `cancelstatus` enum('N','Y') NOT NULL default 'N',
  `status` enum('D','A','P') NOT NULL default 'D',
  `userId` int(11) NOT NULL default '0',
  `cardtype` enum('Visa','Master','Check','Mastercard','Discover','JCB','Web900','EuroDebit') default NULL,
  `checktype` enum('Personal','Company','Savings') default NULL,
  `validupto` varchar(50) default NULL,
  `reason` char(1) default NULL,
  `ipaddress` varchar(15) default NULL,
  `cancellationDate` datetime default NULL,
  `billingDate` date NOT NULL default '0000-00-00',
  `productdescription` varchar(250) default NULL,
  `reference_number` varchar(30) default NULL,
  `currencytype` enum('USD','EUR') default NULL,
  `r_reseller_discount_rate` double default '0',
  `r_total_discount_rate` double default '0',
  `r_chargeback` double default '0',
  `r_credit` double default '0',
  `r_transactionfee` double default '0',
  `r_reserve` double default '0',
  `r_merchant_discount_rate` double default '0',
  `r_bank_discount_rate` decimal(10,2) default NULL,
  `r_total_trans_fees` double default '0',
  `r_reseller_trans_fees` double default '0',
  `r_discountrate` double default '0',
  `r_merchant_trans_fees` double NOT NULL default '0',
  `r_bank_trans_fee` decimal(10,2) default NULL,
  `td_customer_fee` double NOT NULL default '0',
  `cancel_refer_num` varchar(30) default NULL,
  `return_url` varchar(80) default NULL,
  `from_url` varchar(80) default NULL,
  `bank_id` int(11) default NULL,
  `td_rebillingID` int(11) NOT NULL default '-1',
  `td_subscription_id` varchar(30) default NULL,
  `td_ss_ID` int(11) default NULL,
  `td_recur_charge` decimal(10,2) default NULL,
  `td_is_a_rebill` tinyint(1) NOT NULL default '0',
  `td_enable_rebill` tinyint(1) NOT NULL default '0',
  `td_voided_check` tinyint(1) NOT NULL default '0',
  `td_returned_checks` tinyint(1) NOT NULL default '0',
  `td_site_ID` int(11) NOT NULL default '-1',
  `td_is_affiliate` int(1) NOT NULL default '0',
  `td_is_pending_check` tinyint(4) NOT NULL default '0',
  `td_is_chargeback` tinyint(1) NOT NULL default '0',
  `td_recur_processed` tinyint(4) NOT NULL default '0',
  `td_recur_next_date` date NOT NULL default '0000-00-00',
  `td_username` varchar(30) default NULL,
  `td_password` varchar(30) default NULL,
  `td_enable_tracking` enum('off','on') NOT NULL default 'off',
  `td_tracking_id` varchar(20) default NULL,
  `td_tracking_link` varchar(100) default NULL,
  `td_tracking_status` enum('sent','recieved','not_recieved') default NULL,
  `td_tracking_company` varchar(50) default NULL,
  `td_tracking_order_id` varchar(32) NOT NULL default '',
  `td_tracking_ship_est` datetime default NULL,
  `td_tracking_ship_date` datetime default NULL,
  `td_tracking_info` text,
  `td_product_id` varchar(64) NOT NULL default '',
  `td_send_email` enum('yes','no') NOT NULL default 'yes',
  `td_bank_number` varchar(20) default NULL,
  `td_bank_transaction_id` bigint(20) NOT NULL default '0',
  `td_recur_attempts` tinyint(4) NOT NULL default '0',
  `td_recur_num` tinyint(4) NOT NULL default '0',
  `td_non_unique` tinyint(4) NOT NULL default '0',
  `td_gcard` varchar(64) default NULL,
  `td_process_result` text NOT NULL,
  `td_process_query` text NOT NULL,
  `td_process_duration` decimal(10,2) NOT NULL default '0.00',
  `td_process_msg` varchar(64) default NULL,
  `td_fraud_score` float NOT NULL default '-1',
  `td_merchant_paid` mediumint(9) NOT NULL default '0',
  `td_merchant_deducted` mediumint(9) NOT NULL default '0',
  `td_reseller_paid` mediumint(9) NOT NULL default '0',
  `td_reseller_deducted` mediumint(9) NOT NULL default '0',
  `td_bank_paid` mediumint(9) NOT NULL default '0',
  `td_bank_deducted` mediumint(9) NOT NULL default '0',
  `td_bank_decline` tinyint(1) NOT NULL default '0',
  `td_bank_recieved` enum('0','1','no','yes','fraudscrubbing','previousdecline','approvelimit','internalerror','banlist') NOT NULL default 'no',
  `td_merchant_fields` blob,
  `td_bank_invoice` int(11) NOT NULL default '-1',
  PRIMARY KEY  (`transactionId`),
  UNIQUE KEY `reference_number` (`reference_number`),
  KEY `userId` (`userId`),
  KEY `transactionDate` (`transactionDate`),
  KEY `td_is_a_rebill` (`td_is_a_rebill`),
  KEY `td_non_unique` (`td_non_unique`),
  KEY `td_merchant_deducted` (`td_merchant_deducted`),
  KEY `td_enable_tracking` (`td_enable_tracking`),
  KEY `bank_id` (`bank_id`),
  KEY `cancelstatus` (`cancelstatus`),
  KEY `status` (`status`),
  KEY `td_site_ID` (`td_site_ID`),
  KEY `ipaddress` (`ipaddress`),
  KEY `td_merchant_paid` (`td_merchant_paid`),
  KEY `td_reseller_deducted` (`td_reseller_deducted`),
  KEY `td_reseller_paid` (`td_reseller_paid`),
  KEY `td_bank_deducted` (`td_bank_deducted`),
  KEY `cancellationDate` (`cancellationDate`),
  KEY `td_bank_paid` (`td_bank_paid`),
  KEY `CCnumber` (`CCnumber`),
  KEY `email` (`email`),
  KEY `surname` (`surname`),
  KEY `phonenumber` (`phonenumber`),
  KEY `td_ss_ID` (`td_ss_ID`),
  KEY `bankroutingcode` (`bankroutingcode`,`bankaccountnumber`),
  KEY `cardtype` (`cardtype`),
  KEY `td_bank_transaction_id` (`td_bank_transaction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2519 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_tracking_click`
--

CREATE TABLE IF NOT EXISTS `cs_tracking_click` (
  `tc_ID` bigint(20) NOT NULL auto_increment,
  `tc_clicker_ID` int(11) NOT NULL default '0',
  `tc_en_ID` int(11) NOT NULL default '0',
  `tc_affiliate_ID` int(11) default NULL,
  `tc_this_tu_ID` int(11) NOT NULL default '0',
  `tc_refer_tu_ID` int(11) default NULL,
  `tc_time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`tc_ID`),
  KEY `tc_en_ID` (`tc_en_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=794149 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_tracking_clicker`
--

CREATE TABLE IF NOT EXISTS `cs_tracking_clicker` (
  `tk_ID` int(11) NOT NULL auto_increment,
  `tk_ref` varchar(32) collate latin1_general_ci NOT NULL default '',
  `tk_IP` int(10) unsigned NOT NULL default '0',
  `tk_host` varchar(50) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tk_ID`),
  UNIQUE KEY `tk_ref` (`tk_ref`),
  KEY `tk_IP` (`tk_IP`),
  KEY `tk_host` (`tk_host`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=73608 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_tracking_host`
--

CREATE TABLE IF NOT EXISTS `cs_tracking_host` (
  `th_ID` mediumint(9) NOT NULL auto_increment,
  `th_host` varchar(50) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`th_ID`),
  UNIQUE KEY `th_host` (`th_host`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2631 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_tracking_url`
--

CREATE TABLE IF NOT EXISTS `cs_tracking_url` (
  `tu_ID` int(11) NOT NULL auto_increment,
  `tu_th_ID` mediumint(9) NOT NULL default '0',
  `tu_URL` varchar(255) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tu_ID`),
  KEY `tu_host` (`tu_th_ID`),
  KEY `tu_URL` (`tu_URL`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=82561 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_transactiondetails`
--

CREATE TABLE IF NOT EXISTS `cs_transactiondetails` (
  `transactionId` int(11) NOT NULL auto_increment,
  `transactionDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(50) NOT NULL default '',
  `surname` varchar(50) default NULL,
  `phonenumber` bigint(18) default NULL,
  `address` varchar(100) default NULL,
  `CCnumber` varchar(40) default NULL,
  `cvv` varchar(4) default NULL,
  `checkorcard` enum('H','C','W') default NULL,
  `country` varchar(2) default NULL,
  `city` varchar(50) default NULL,
  `state` varchar(28) default NULL,
  `zipcode` varchar(25) default NULL,
  `amount` double NOT NULL default '0',
  `bankname` varchar(50) NOT NULL default '',
  `bankroutingcode` varchar(40) default NULL,
  `bankaccountnumber` varchar(40) default NULL,
  `accounttype` char(1) default NULL,
  `email` varchar(100) default NULL,
  `cancelstatus` enum('N','Y') NOT NULL default 'N',
  `status` enum('D','A','P') NOT NULL default 'D',
  `userId` int(11) NOT NULL default '0',
  `cardtype` enum('Visa','Mastercard','Check','Discover','JCB','Web900','EuroDebit') default NULL,
  `checktype` enum('Personal','Company','Savings') default NULL,
  `validupto` varchar(50) default NULL,
  `reason` char(1) default NULL,
  `ipaddress` varchar(15) default NULL,
  `cancellationDate` datetime default NULL,
  `billingDate` date NOT NULL default '0000-00-00',
  `productdescription` varchar(250) default NULL,
  `reference_number` varchar(30) default NULL,
  `currencytype` enum('USD','EUR') default NULL,
  `r_reseller_discount_rate` double default '0',
  `r_total_discount_rate` double default '0',
  `r_chargeback` double default '0',
  `r_credit` double default '0',
  `r_transactionfee` double default '0',
  `r_reserve` double default '0',
  `r_merchant_discount_rate` double default '0',
  `r_bank_discount_rate` decimal(10,2) default NULL,
  `r_total_trans_fees` double default '0',
  `r_reseller_trans_fees` double default '0',
  `r_discountrate` double default '0',
  `r_merchant_trans_fees` double NOT NULL default '0',
  `r_bank_trans_fee` decimal(10,2) default NULL,
  `td_customer_fee` double NOT NULL default '0',
  `cancel_refer_num` varchar(30) default NULL,
  `return_url` varchar(80) default NULL,
  `from_url` varchar(80) default NULL,
  `bank_id` int(11) default NULL,
  `td_rebillingID` int(11) NOT NULL default '-1',
  `td_subscription_id` varchar(30) default NULL,
  `td_ss_ID` int(11) default NULL,
  `td_recur_charge` decimal(10,2) default NULL,
  `td_is_a_rebill` tinyint(1) NOT NULL default '0',
  `td_enable_rebill` tinyint(1) NOT NULL default '0',
  `td_voided_check` tinyint(1) NOT NULL default '0',
  `td_returned_checks` tinyint(1) NOT NULL default '0',
  `td_site_ID` int(11) NOT NULL default '-1',
  `td_is_affiliate` int(1) NOT NULL default '0',
  `td_is_pending_check` tinyint(4) NOT NULL default '0',
  `td_is_chargeback` tinyint(1) NOT NULL default '0',
  `td_recur_processed` tinyint(4) NOT NULL default '0',
  `td_recur_next_date` date NOT NULL default '0000-00-00',
  `td_username` varchar(30) default NULL,
  `td_password` varchar(30) default NULL,
  `td_enable_tracking` enum('off','on') NOT NULL default 'off',
  `td_tracking_id` varchar(20) default NULL,
  `td_tracking_link` varchar(100) default NULL,
  `td_tracking_status` enum('sent','recieved','not_recieved') default NULL,
  `td_tracking_company` varchar(50) default NULL,
  `td_tracking_order_id` varchar(32) NOT NULL default '',
  `td_tracking_ship_est` datetime default NULL,
  `td_tracking_ship_date` datetime default NULL,
  `td_tracking_info` text,
  `td_product_id` varchar(64) NOT NULL default '',
  `td_send_email` enum('yes','no') NOT NULL default 'yes',
  `td_bank_number` varchar(20) default NULL,
  `td_bank_transaction_id` bigint(20) NOT NULL default '0',
  `td_recur_attempts` tinyint(4) NOT NULL default '0',
  `td_recur_num` tinyint(4) NOT NULL default '0',
  `td_non_unique` int(11) NOT NULL default '0',
  `td_gcard` varchar(64) default NULL,
  `td_process_result` text NOT NULL,
  `td_process_query` text NOT NULL,
  `td_process_duration` decimal(10,2) NOT NULL default '0.00',
  `td_process_msg` varchar(64) default NULL,
  `td_fraud_score` float NOT NULL default '-1',
  `td_merchant_paid` mediumint(9) NOT NULL default '0',
  `td_merchant_deducted` mediumint(9) NOT NULL default '0',
  `td_reseller_paid` mediumint(9) NOT NULL default '0',
  `td_reseller_deducted` mediumint(9) NOT NULL default '0',
  `td_bank_paid` mediumint(9) NOT NULL default '0',
  `td_bank_deducted` mediumint(9) NOT NULL default '0',
  `td_bank_decline` tinyint(1) NOT NULL default '0',
  `td_bank_recieved` enum('0','1','no','yes','fraudscrubbing','previousdecline','approvelimit','internalerror','banlist') NOT NULL default 'no',
  `td_merchant_fields` blob,
  `td_bank_invoice` int(11) NOT NULL default '-1',
  PRIMARY KEY  (`transactionId`),
  UNIQUE KEY `reference_number` (`reference_number`),
  KEY `userId` (`userId`),
  KEY `transactionDate` (`transactionDate`),
  KEY `td_is_a_rebill` (`td_is_a_rebill`),
  KEY `td_non_unique` (`td_non_unique`),
  KEY `td_merchant_deducted` (`td_merchant_deducted`),
  KEY `td_enable_tracking` (`td_enable_tracking`),
  KEY `bank_id` (`bank_id`),
  KEY `cancelstatus` (`cancelstatus`),
  KEY `status` (`status`),
  KEY `td_site_ID` (`td_site_ID`),
  KEY `ipaddress` (`ipaddress`),
  KEY `td_merchant_paid` (`td_merchant_paid`),
  KEY `td_reseller_deducted` (`td_reseller_deducted`),
  KEY `td_reseller_paid` (`td_reseller_paid`),
  KEY `td_bank_deducted` (`td_bank_deducted`),
  KEY `cancellationDate` (`cancellationDate`),
  KEY `td_bank_paid` (`td_bank_paid`),
  KEY `CCnumber` (`CCnumber`),
  KEY `email` (`email`),
  KEY `surname` (`surname`),
  KEY `phonenumber` (`phonenumber`),
  KEY `td_ss_ID` (`td_ss_ID`),
  KEY `bankroutingcode` (`bankroutingcode`,`bankaccountnumber`),
  KEY `cardtype` (`cardtype`),
  KEY `td_bank_transaction_id` (`td_bank_transaction_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=180528883 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_tsrusers`
--

CREATE TABLE IF NOT EXISTS `cs_tsrusers` (
  `tsr_user_id` int(11) NOT NULL auto_increment,
  `tsr_added_by` char(1) default NULL,
  `tsr_added_user_id` int(11) default NULL,
  `tsr_first_name` varchar(250) default NULL,
  `tsr_last_name` varchar(250) default NULL,
  `tsr_user_name` varchar(250) default NULL,
  `tsr_password` varchar(250) default NULL,
  `tsr_amount_per_sale` double default NULL,
  `tsr_voice_auth_fee` double default NULL,
  PRIMARY KEY  (`tsr_user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_unfound_calls`
--

CREATE TABLE IF NOT EXISTS `cs_unfound_calls` (
  `unfound_id` int(11) NOT NULL auto_increment,
  `customerName` varchar(250) default NULL,
  `customerAddress` text,
  `customerPhone` varchar(50) default NULL,
  `notes` text,
  `currentDateTime` datetime default NULL,
  `call_duration` varchar(8) default NULL,
  `customer_service_id` int(11) default NULL,
  `cancel_status` char(1) default 'N',
  `dnc` char(1) default 'N',
  PRIMARY KEY  (`unfound_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=249 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_uploaded_documents`
--

CREATE TABLE IF NOT EXISTS `cs_uploaded_documents` (
  `file_id` int(11) NOT NULL auto_increment,
  `ud_en_ID` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `file_type` enum('Articles','Contract','History','License','Professional_Reference') NOT NULL default 'Articles',
  `file_name` varchar(150) NOT NULL default '',
  `date_uploaded` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` enum('P','A','R','Pending','Approved','Declined') NOT NULL default 'Pending',
  `reject_reason` text,
  PRIMARY KEY  (`file_id`),
  KEY `file_type` (`file_type`),
  KEY `date_uploaded` (`date_uploaded`),
  KEY `status` (`status`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7106 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_voice_system_upload_log`
--

CREATE TABLE IF NOT EXISTS `cs_voice_system_upload_log` (
  `upload_id` int(11) NOT NULL auto_increment,
  `upload_batch_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `voice_authorization_id` varchar(50) NOT NULL default '',
  `telephone_number` varchar(25) NOT NULL default '',
  `pass_status` varchar(2) NOT NULL default '',
  `comments` text,
  `upload_date_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` char(1) default NULL,
  PRIMARY KEY  (`upload_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1913 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_volpay`
--

CREATE TABLE IF NOT EXISTS `cs_volpay` (
  `id` int(11) NOT NULL auto_increment,
  `trans_id` int(11) default NULL,
  `user_id` int(11) default NULL,
  `currency` varchar(30) default NULL,
  `amount` double default NULL,
  `trans_status` varchar(100) default NULL,
  `return_code` varchar(25) default NULL,
  `return_message` varchar(250) default NULL,
  `reference_number` varchar(30) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Volpay Payment table' AUTO_INCREMENT=7928 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_watchlist`
--

CREATE TABLE IF NOT EXISTS `cs_watchlist` (
  `wl_ID` int(11) NOT NULL default '0',
  `wl_type` enum('td_process_msg') NOT NULL default 'td_process_msg',
  `wl_data` varchar(150) NOT NULL default '',
  `wl_action` enum('banfull','bancard','banip','banemail','delayrebill15','delayrebill30','erroralertonrebill') NOT NULL default 'banfull',
  PRIMARY KEY  (`wl_ID`),
  UNIQUE KEY `wl_type_2` (`wl_type`,`wl_data`),
  KEY `wl_type` (`wl_type`),
  KEY `wl_data` (`wl_data`),
  KEY `wl_action` (`wl_action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dp_banks`
--

CREATE TABLE IF NOT EXISTS `dp_banks` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `bname` varchar(128) NOT NULL default '',
  `baddress` varchar(128) NOT NULL default '',
  `bcity` varchar(64) NOT NULL default '',
  `bzip` varchar(16) NOT NULL default '',
  `bcountry` varchar(2) NOT NULL default '',
  `bstate` varchar(32) NOT NULL default '',
  `bphone` varchar(32) NOT NULL default '',
  `bnameacc` varchar(128) NOT NULL default '',
  `baccount` varchar(32) NOT NULL default '',
  `btype` varchar(2) NOT NULL default '',
  `brtgnum` varchar(9) NOT NULL default '',
  `bswift` varchar(32) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `default` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_cards`
--

CREATE TABLE IF NOT EXISTS `dp_cards` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `ctype` varchar(8) NOT NULL default '',
  `cname` varchar(64) NOT NULL default '',
  `cnumber` varchar(32) NOT NULL default '',
  `ccvv` varchar(16) NOT NULL default '',
  `cmonth` tinyint(2) NOT NULL default '0',
  `cyear` smallint(6) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `default` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_confirms`
--

CREATE TABLE IF NOT EXISTS `dp_confirms` (
  `id` int(11) NOT NULL auto_increment,
  `newuser` varchar(32) NOT NULL default '',
  `newpass` varchar(32) NOT NULL default '',
  `newquestion` varchar(255) NOT NULL default '',
  `newanswer` varchar(255) NOT NULL default '',
  `newmail` varchar(255) NOT NULL default '',
  `newfname` varchar(32) NOT NULL default '',
  `newlname` varchar(32) NOT NULL default '',
  `newcompany` varchar(128) NOT NULL default '',
  `newregnum` varchar(32) NOT NULL default '',
  `newdrvnum` varchar(32) NOT NULL default '',
  `newaddress` varchar(128) NOT NULL default '',
  `newcity` varchar(64) NOT NULL default '',
  `newcountry` varchar(2) NOT NULL default '',
  `newstate` varchar(32) NOT NULL default '',
  `newzip` varchar(32) NOT NULL default '',
  `newphone` varchar(64) NOT NULL default '',
  `newfax` varchar(64) NOT NULL default '',
  `sponsor` int(11) NOT NULL default '0',
  `confirm` varchar(255) NOT NULL default '',
  `cdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `newuser` (`newuser`),
  KEY `newmail` (`newmail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_emails`
--

CREATE TABLE IF NOT EXISTS `dp_emails` (
  `id` int(11) NOT NULL auto_increment,
  `key` varchar(64) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `value` longtext,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `keyword` (`key`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='E-Mail Templates' AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_faq_cat_list`
--

CREATE TABLE IF NOT EXISTS `dp_faq_cat_list` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `title` char(200) NOT NULL default '',
  `parent` int(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_faq_list`
--

CREATE TABLE IF NOT EXISTS `dp_faq_list` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `cat` int(3) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_members`
--

CREATE TABLE IF NOT EXISTS `dp_members` (
  `id` int(11) NOT NULL auto_increment,
  `sponsor` int(11) NOT NULL default '0',
  `username` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `question` varchar(255) NOT NULL default '',
  `answer` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `active` tinyint(1) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  `empty` tinyint(1) NOT NULL default '1',
  `cdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `ldate` datetime NOT NULL default '0000-00-00 00:00:00',
  `adate` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_ip` varchar(255) default NULL,
  `fname` varchar(32) NOT NULL default '',
  `lname` varchar(32) NOT NULL default '',
  `company` varchar(128) NOT NULL default '',
  `regnum` varchar(32) NOT NULL default '',
  `drvnum` varchar(32) NOT NULL default '',
  `address` varchar(128) NOT NULL default '',
  `city` varchar(64) NOT NULL default '',
  `country` varchar(2) NOT NULL default '',
  `state` varchar(32) NOT NULL default '',
  `zip` varchar(32) NOT NULL default '',
  `phone` varchar(64) NOT NULL default '',
  `fax` varchar(64) NOT NULL default '',
  `ctype` varchar(8) NOT NULL default '',
  `cname` varchar(64) NOT NULL default '',
  `cnumber` varchar(32) NOT NULL default '',
  `ccvv` varchar(16) NOT NULL default '',
  `cmonth` tinyint(2) NOT NULL default '0',
  `cyear` smallint(6) NOT NULL default '0',
  `bname` varchar(128) NOT NULL default '',
  `baddress` varchar(128) NOT NULL default '',
  `bcity` varchar(64) NOT NULL default '',
  `bzip` varchar(16) NOT NULL default '',
  `bcountry` varchar(2) NOT NULL default '',
  `bstate` varchar(32) NOT NULL default '',
  `bphone` varchar(32) NOT NULL default '',
  `bnameacc` varchar(128) NOT NULL default '',
  `baccount` varchar(32) NOT NULL default '',
  `btype` varchar(2) NOT NULL default '',
  `brtgnum` varchar(9) NOT NULL default '',
  `bswift` varchar(32) NOT NULL default '',
  `description` longtext,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `fname` (`fname`),
  KEY `lname` (`lname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='System Members' AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_member_emails`
--

CREATE TABLE IF NOT EXISTS `dp_member_emails` (
  `owner` int(11) NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `active` tinyint(1) NOT NULL default '0',
  `primary` tinyint(1) NOT NULL default '0',
  `verifcode` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`owner`,`email`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dp_products`
--

CREATE TABLE IF NOT EXISTS `dp_products` (
  `id` int(11) NOT NULL auto_increment,
  `type` tinyint(4) NOT NULL default '0',
  `sold` int(11) NOT NULL default '0',
  `owner` int(11) NOT NULL default '0',
  `price` double(10,2) NOT NULL default '0.00',
  `period` int(11) NOT NULL default '0',
  `setup` double(10,2) NOT NULL default '0.00',
  `trial` double(10,2) NOT NULL default '0.00',
  `tax` double(10,2) NOT NULL default '0.00',
  `shipping` double(10,2) NOT NULL default '0.00',
  `button` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `ureturn` mediumtext NOT NULL,
  `unotify` mediumtext NOT NULL,
  `ucancel` mediumtext NOT NULL,
  `comments` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `owner` (`owner`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Products' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_shop_categories`
--

CREATE TABLE IF NOT EXISTS `dp_shop_categories` (
  `id` int(11) NOT NULL auto_increment,
  `parentid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` text,
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parentid`,`name`),
  KEY `parentid` (`parentid`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_shop_items`
--

CREATE TABLE IF NOT EXISTS `dp_shop_items` (
  `id` int(11) NOT NULL auto_increment,
  `categoryid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `url` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `categoryid` (`categoryid`,`name`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_subscriptions`
--

CREATE TABLE IF NOT EXISTS `dp_subscriptions` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL default '0',
  `member` int(11) NOT NULL default '0',
  `product` int(11) NOT NULL default '0',
  `sdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `pdate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `owner` (`owner`),
  KEY `member` (`member`),
  KEY `product` (`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Subscribers' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_temp_pays`
--

CREATE TABLE IF NOT EXISTS `dp_temp_pays` (
  `id` int(11) NOT NULL auto_increment,
  `tdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `sender` int(11) NOT NULL default '0',
  `receiver` varchar(255) NOT NULL default '',
  `amount` double(10,2) NOT NULL default '0.00',
  `status` tinyint(1) NOT NULL default '0',
  `comments` longtext,
  PRIMARY KEY  (`id`),
  KEY `tdate` (`tdate`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Pending payments for unregistered members' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_transactions`
--

CREATE TABLE IF NOT EXISTS `dp_transactions` (
  `id` int(11) NOT NULL auto_increment,
  `tdate` datetime default NULL,
  `sender` int(11) NOT NULL default '0',
  `receiver` int(11) NOT NULL default '0',
  `related` int(11) NOT NULL default '0',
  `amount` double(10,2) NOT NULL default '0.00',
  `fees` double(10,2) NOT NULL default '0.00',
  `type` tinyint(1) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  `comments` longtext,
  `ecomments` longtext,
  PRIMARY KEY  (`id`),
  KEY `tdate` (`tdate`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Transactions' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `dp_visits`
--

CREATE TABLE IF NOT EXISTS `dp_visits` (
  `id` int(11) NOT NULL auto_increment,
  `member` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `address` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `member` (`member`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='History of members IPs' AUTO_INCREMENT=106 ;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqadminlog`
--

CREATE TABLE IF NOT EXISTS `faq_faqadminlog` (
  `id` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `usr` int(11) NOT NULL default '0',
  `text` text NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqadminsessions`
--

CREATE TABLE IF NOT EXISTS `faq_faqadminsessions` (
  `uin` varchar(50) NOT NULL default '',
  `usr` tinytext NOT NULL,
  `pass` varchar(64) NOT NULL default '',
  `ip` text NOT NULL,
  `time` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqcaptcha`
--

CREATE TABLE IF NOT EXISTS `faq_faqcaptcha` (
  `id` varchar(6) NOT NULL default '',
  `useragent` varchar(255) NOT NULL default '',
  `language` varchar(2) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  `captcha_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqcategories`
--

CREATE TABLE IF NOT EXISTS `faq_faqcategories` (
  `id` int(11) NOT NULL default '0',
  `lang` varchar(5) NOT NULL default '',
  `parent_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqcategoryrelations`
--

CREATE TABLE IF NOT EXISTS `faq_faqcategoryrelations` (
  `category_id` int(11) NOT NULL default '0',
  `category_lang` varchar(5) NOT NULL default '',
  `record_id` int(11) NOT NULL default '0',
  `record_lang` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`category_id`,`category_lang`,`record_id`,`record_lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqchanges`
--

CREATE TABLE IF NOT EXISTS `faq_faqchanges` (
  `id` int(11) NOT NULL default '0',
  `beitrag` int(11) NOT NULL default '0',
  `lang` varchar(5) NOT NULL default '',
  `revision_id` int(11) NOT NULL default '0',
  `usr` int(11) NOT NULL default '0',
  `datum` int(11) NOT NULL default '0',
  `what` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqcomments`
--

CREATE TABLE IF NOT EXISTS `faq_faqcomments` (
  `id_comment` int(11) NOT NULL default '0',
  `id` int(11) NOT NULL default '0',
  `usr` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `comment` text NOT NULL,
  `datum` int(15) NOT NULL default '0',
  `helped` text NOT NULL,
  PRIMARY KEY  (`id_comment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqdata`
--

CREATE TABLE IF NOT EXISTS `faq_faqdata` (
  `id` int(11) NOT NULL default '0',
  `lang` varchar(5) NOT NULL default '',
  `solution_id` int(11) NOT NULL default '0',
  `revision_id` int(11) NOT NULL default '0',
  `active` varchar(3) NOT NULL default '',
  `keywords` text NOT NULL,
  `thema` text NOT NULL,
  `content` longtext NOT NULL,
  `author` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `comment` enum('y','n') NOT NULL default 'y',
  `datum` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`,`lang`),
  FULLTEXT KEY `keywords` (`keywords`,`thema`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqdata_revisions`
--

CREATE TABLE IF NOT EXISTS `faq_faqdata_revisions` (
  `id` int(11) NOT NULL default '0',
  `lang` varchar(5) NOT NULL default '',
  `solution_id` int(11) NOT NULL default '0',
  `revision_id` int(11) NOT NULL default '0',
  `active` varchar(3) NOT NULL default '',
  `keywords` text NOT NULL,
  `thema` text NOT NULL,
  `content` longtext NOT NULL,
  `author` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `comment` enum('y','n') NOT NULL default 'y',
  `datum` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`,`lang`,`solution_id`,`revision_id`),
  FULLTEXT KEY `keywords` (`keywords`,`thema`,`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqfragen`
--

CREATE TABLE IF NOT EXISTS `faq_faqfragen` (
  `id` int(11) unsigned NOT NULL default '0',
  `ask_username` varchar(100) NOT NULL default '',
  `ask_usermail` varchar(100) NOT NULL default '',
  `ask_rubrik` varchar(100) NOT NULL default '',
  `ask_content` text NOT NULL,
  `ask_date` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqnews`
--

CREATE TABLE IF NOT EXISTS `faq_faqnews` (
  `id` int(11) NOT NULL default '0',
  `header` varchar(255) NOT NULL default '',
  `artikel` text NOT NULL,
  `datum` varchar(14) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `linktitel` varchar(255) NOT NULL default '',
  `target` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqsessions`
--

CREATE TABLE IF NOT EXISTS `faq_faqsessions` (
  `sid` int(11) NOT NULL default '0',
  `ip` text NOT NULL,
  `time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faquser`
--

CREATE TABLE IF NOT EXISTS `faq_faquser` (
  `id` int(2) NOT NULL default '0',
  `name` text NOT NULL,
  `pass` varchar(64) NOT NULL default '',
  `realname` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `rights` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqvisits`
--

CREATE TABLE IF NOT EXISTS `faq_faqvisits` (
  `id` int(11) NOT NULL default '0',
  `lang` varchar(5) NOT NULL default '',
  `visits` int(11) NOT NULL default '0',
  `last_visit` int(15) NOT NULL default '0',
  PRIMARY KEY  (`id`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `faq_faqvoting`
--

CREATE TABLE IF NOT EXISTS `faq_faqvoting` (
  `id` int(11) unsigned NOT NULL default '0',
  `artikel` int(11) unsigned NOT NULL default '0',
  `vote` int(11) unsigned NOT NULL default '0',
  `usr` int(11) unsigned NOT NULL default '0',
  `datum` varchar(20) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sp_links`
--

CREATE TABLE IF NOT EXISTS `sp_links` (
  `li_ID` bigint(20) NOT NULL auto_increment,
  `li_parent_ID` bigint(20) NOT NULL default '-1',
  `li_hash` varchar(32) NOT NULL default '',
  `li_si_ID` int(11) NOT NULL default '0',
  `li_url` varchar(255) NOT NULL default '',
  `li_name` varchar(100) NOT NULL default '',
  `li_type` enum('http','ftp','ftp-recursion') NOT NULL default 'http',
  `li_page_hash` varchar(32) default NULL,
  `li_last_checked` datetime default NULL,
  `li_attempts` tinyint(4) NOT NULL default '0',
  `li_depth` tinyint(4) NOT NULL default '0',
  `li_external` tinyint(4) NOT NULL default '0',
  `li_score_required` smallint(6) default NULL,
  `li_score_disallowed` smallint(6) default NULL,
  `li_links_found` smallint(6) default NULL,
  PRIMARY KEY  (`li_ID`),
  UNIQUE KEY `li_hash` (`li_hash`),
  UNIQUE KEY `li_page_hash` (`li_page_hash`),
  KEY `li_external` (`li_external`),
  KEY `li_type` (`li_type`),
  KEY `li_parent_ID` (`li_parent_ID`),
  KEY `li_si_ID` (`li_si_ID`),
  KEY `li_last_checked` (`li_last_checked`),
  KEY `li_attempts` (`li_attempts`),
  KEY `li_score_required` (`li_score_required`,`li_score_disallowed`,`li_links_found`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9721 ;

-- --------------------------------------------------------

--
-- Table structure for table `sp_wl_wc`
--

CREATE TABLE IF NOT EXISTS `sp_wl_wc` (
  `wl_ID` mediumint(9) NOT NULL default '0',
  `wc_ID` mediumint(9) NOT NULL default '0',
  KEY `wl_ID` (`wl_ID`,`wc_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sp_word_category`
--

CREATE TABLE IF NOT EXISTS `sp_word_category` (
  `wc_ID` int(11) NOT NULL auto_increment,
  `wc_category` varchar(100) NOT NULL default 'Adult',
  PRIMARY KEY  (`wc_ID`),
  UNIQUE KEY `wc_category` (`wc_category`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `sp_word_list`
--

CREATE TABLE IF NOT EXISTS `sp_word_list` (
  `wl_ID` int(11) NOT NULL auto_increment,
  `wl_word` varchar(50) NOT NULL default '',
  `wl_weight` decimal(3,2) NOT NULL default '0.00',
  `wl_type` enum('disallowed','required') NOT NULL default 'disallowed',
  PRIMARY KEY  (`wl_ID`),
  UNIQUE KEY `sp_word` (`wl_word`),
  KEY `sp_weight` (`wl_weight`,`wl_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=185 ;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_categories`
--

CREATE TABLE IF NOT EXISTS `tickets_categories` (
  `tickets_categories_id` tinyint(3) unsigned NOT NULL auto_increment,
  `tickets_categories_name` varchar(20) NOT NULL default '',
  `tickets_categories_order` tinyint(3) unsigned NOT NULL default '1',
  `tickets_categories_email` varchar(80) NOT NULL default 'support@etelegate.com',
  `tickets_categories_emailname` varchar(80) NOT NULL default 'Etelegate Support',
  PRIMARY KEY  (`tickets_categories_id`),
  UNIQUE KEY `tickets_categories_name` (`tickets_categories_name`),
  KEY `tickets_categories_order` (`tickets_categories_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_status`
--

CREATE TABLE IF NOT EXISTS `tickets_status` (
  `tickets_status_id` tinyint(3) unsigned NOT NULL auto_increment,
  `tickets_status_name` varchar(20) NOT NULL default '',
  `tickets_status_order` tinyint(3) unsigned NOT NULL default '1',
  `tickets_status_color` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`tickets_status_id`),
  KEY `tickets_status_name` (`tickets_status_name`),
  KEY `tickets_status_order` (`tickets_status_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_tickets`
--

CREATE TABLE IF NOT EXISTS `tickets_tickets` (
  `tickets_id` int(10) unsigned NOT NULL auto_increment,
  `tickets_username` varchar(32) NOT NULL default '',
  `tickets_subject` varchar(50) NOT NULL default '',
  `tickets_timestamp` bigint(10) unsigned NOT NULL default '0',
  `tickets_status` enum('Open','Closed','Answered') NOT NULL default 'Open',
  `tickets_name` varchar(50) NOT NULL default '',
  `tickets_email` varchar(50) NOT NULL default '',
  `tickets_urgency` tinyint(3) unsigned NOT NULL default '1',
  `tickets_category` tinyint(3) unsigned NOT NULL default '1',
  `tickets_admin` enum('Client','Admin') NOT NULL default 'Client',
  `tickets_source` enum('client','foundcall','unfoundcall') default NULL,
  `tickets_child` int(10) unsigned NOT NULL default '0',
  `tickets_question` text NOT NULL,
  `tickets_reference` varchar(16) default NULL,
  `tickets_issue` varchar(100) default NULL,
  `tickets_time` double default NULL,
  `td_transactionId` varchar(30) default NULL,
  `tickets_responses` smallint(6) NOT NULL default '0',
  `tickets_latest` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`tickets_id`),
  UNIQUE KEY `tickets_reference` (`tickets_reference`),
  KEY `tickets_username` (`tickets_username`),
  KEY `tickets_urgency` (`tickets_urgency`),
  KEY `tickets_category` (`tickets_category`),
  KEY `tickets_child` (`tickets_child`),
  KEY `tickets_status` (`tickets_status`),
  KEY `tickets_admin` (`tickets_admin`),
  KEY `tickets_timestamp` (`tickets_timestamp`),
  KEY `tickets_subject` (`tickets_subject`),
  KEY `td_transactionId` (`td_transactionId`),
  KEY `tickets_issue` (`tickets_issue`),
  KEY `tickets_source` (`tickets_source`),
  KEY `tickets_email` (`tickets_email`),
  KEY `tickets_status_2` (`tickets_status`,`tickets_child`),
  KEY `tickets_latest` (`tickets_latest`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_users`
--

CREATE TABLE IF NOT EXISTS `tickets_users` (
  `tickets_users_id` int(10) unsigned NOT NULL auto_increment,
  `tickets_users_name` varchar(50) NOT NULL default '',
  `tickets_users_username` varchar(32) NOT NULL default '',
  `tickets_users_password` varchar(16) NOT NULL default '',
  `tickets_users_email` varchar(100) NOT NULL default '',
  `tickets_users_lastlogin` bigint(10) unsigned NOT NULL default '0',
  `tickets_users_newlogin` bigint(10) unsigned NOT NULL default '0',
  `tickets_users_admin` enum('User','Admin','Gateway Admin','Mod') NOT NULL default 'User',
  `tickets_users_status` tinyint(1) unsigned NOT NULL default '1',
  `cs_userId` int(11) default NULL,
  `cs_reseller_id` int(11) default NULL,
  `cs_csuser` int(11) default NULL,
  `cs_gateway_id` tinyint(4) default NULL,
  PRIMARY KEY  (`tickets_users_id`),
  UNIQUE KEY `tickets_users_username` (`tickets_users_username`),
  UNIQUE KEY `tickets_users_email` (`tickets_users_email`),
  KEY `tickets_users_admin` (`tickets_users_admin`),
  KEY `tickets_users_status` (`tickets_users_status`),
  KEY `cs_gateway_id` (`cs_gateway_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tp_campaigns`
--

CREATE TABLE IF NOT EXISTS `tp_campaigns` (
  `campaignid` int(11) NOT NULL default '0',
  `campaignsite` varchar(255) default NULL,
  `campaignname` varchar(255) default NULL,
  `cost` float default '0',
  `period` int(11) default '0',
  `startdate` int(11) default '0',
  `hasconversion` int(11) default '0',
  `amount` float default '0',
  `currtime` int(11) default NULL,
  `convtime` int(11) default NULL,
  `userid` int(11) default NULL,
  `ip` varchar(20) default NULL,
  `cookieid` varchar(255) default NULL,
  PRIMARY KEY  (`campaignid`),
  KEY `campaign_site` (`campaignsite`),
  KEY `campaign_name` (`campaignname`),
  KEY `campaign_userid` (`userid`),
  KEY `campaign_cookieid` (`cookieid`),
  KEY `campaign_time` (`currtime`),
  KEY `campaign_conv_time` (`convtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_campaigns_sequence`
--

CREATE TABLE IF NOT EXISTS `tp_campaigns_sequence` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `tp_conversions`
--

CREATE TABLE IF NOT EXISTS `tp_conversions` (
  `conversionid` int(11) NOT NULL default '0',
  `type` varchar(10) default NULL,
  `name` varchar(255) default NULL,
  `amount` float default '0',
  `cookieid` varchar(255) default NULL,
  `sessionid` varchar(32) default NULL,
  `currtime` int(11) default '0',
  `ip` varchar(20) default NULL,
  `origintype` varchar(20) default NULL,
  `originfrom` varchar(255) default NULL,
  `origindetails` varchar(255) default NULL,
  `userid` int(11) default NULL,
  PRIMARY KEY  (`conversionid`),
  KEY `conv_userid` (`userid`),
  KEY `conv_origintype` (`origintype`),
  KEY `conv_originfrom` (`originfrom`),
  KEY `conv_origindetails` (`origindetails`),
  KEY `conv_time` (`currtime`),
  KEY `conv_cookie` (`cookieid`),
  KEY `conv_session` (`sessionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_conversions_sequence`
--

CREATE TABLE IF NOT EXISTS `tp_conversions_sequence` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2439 ;

-- --------------------------------------------------------

--
-- Table structure for table `tp_cookies`
--

CREATE TABLE IF NOT EXISTS `tp_cookies` (
  `sessionid` varchar(32) default NULL,
  `cookieid` varchar(255) default NULL,
  `cookietype` varchar(10) default NULL,
  `cookiefrom` varchar(255) default NULL,
  `cookiedetails` varchar(255) default NULL,
  `remove` char(1) default '0',
  `cookietime` int(11) default '0',
  KEY `session_index` (`sessionid`),
  KEY `cookie_index` (`cookieid`),
  KEY `remove_index` (`remove`),
  KEY `cookietime_index` (`cookietime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_loghistory`
--

CREATE TABLE IF NOT EXISTS `tp_loghistory` (
  `logid` int(11) default NULL,
  `file` varchar(255) default NULL,
  `line` int(11) default NULL,
  `userid` int(11) default NULL,
  `logtime` int(11) default NULL,
  `logtype` varchar(255) default NULL,
  `loglevel` varchar(255) default NULL,
  `ip` varchar(20) default NULL,
  `logentry` text,
  `sessionid` varchar(255) default NULL,
  KEY `loghistory_time` (`logtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_loghistory_sequence`
--

CREATE TABLE IF NOT EXISTS `tp_loghistory_sequence` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tp_payperclicks`
--

CREATE TABLE IF NOT EXISTS `tp_payperclicks` (
  `ppcid` int(11) NOT NULL default '0',
  `searchenginename` varchar(255) default NULL,
  `ppcname` varchar(255) default NULL,
  `cost` float default '0',
  `hasconversion` int(11) default '0',
  `amount` float default '0',
  `currtime` int(11) default '0',
  `convtime` int(11) default '0',
  `userid` int(11) default NULL,
  `ip` varchar(20) default NULL,
  `cookieid` varchar(255) default NULL,
  PRIMARY KEY  (`ppcid`),
  KEY `ppc_searchengine` (`searchenginename`),
  KEY `ppc_name` (`ppcname`),
  KEY `ppc_cookieid` (`cookieid`),
  KEY `ppc_time` (`currtime`),
  KEY `ppc_conv_time` (`convtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_payperclicks_sequence`
--

CREATE TABLE IF NOT EXISTS `tp_payperclicks_sequence` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=775 ;

-- --------------------------------------------------------

--
-- Table structure for table `tp_referrers`
--

CREATE TABLE IF NOT EXISTS `tp_referrers` (
  `referrerid` int(11) NOT NULL default '0',
  `domain` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `currtime` int(11) default '0',
  `convtime` int(11) default '0',
  `ip` varchar(20) default NULL,
  `landingpage` varchar(255) default NULL,
  `cookieid` varchar(255) default NULL,
  `userid` int(11) default NULL,
  `hasconversion` int(11) default '0',
  `amount` float default '0',
  PRIMARY KEY  (`referrerid`),
  KEY `referrers_cookieid` (`cookieid`),
  KEY `referrers_landingpage` (`landingpage`),
  KEY `referrers_url` (`url`),
  KEY `referrers_domain` (`domain`),
  KEY `referrers_userid` (`userid`),
  KEY `referrers_time` (`currtime`),
  KEY `referrers_conv_time` (`convtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_referrers_sequence`
--

CREATE TABLE IF NOT EXISTS `tp_referrers_sequence` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3476 ;

-- --------------------------------------------------------

--
-- Table structure for table `tp_search`
--

CREATE TABLE IF NOT EXISTS `tp_search` (
  `searchid` int(11) NOT NULL default '0',
  `searchenginename` varchar(255) default NULL,
  `keywords` varchar(255) default NULL,
  `currtime` int(11) default '0',
  `convtime` int(11) default '0',
  `ip` varchar(20) default NULL,
  `landingpage` varchar(255) default NULL,
  `cookieid` varchar(255) default NULL,
  `userid` int(11) default NULL,
  `hasconversion` int(11) default '0',
  `amount` float default '0',
  PRIMARY KEY  (`searchid`),
  KEY `search_searchenginename` (`searchenginename`),
  KEY `search_keywords` (`keywords`),
  KEY `search_userid` (`userid`),
  KEY `search_cookieid` (`cookieid`),
  KEY `search_landingpage` (`landingpage`),
  KEY `search_time` (`currtime`),
  KEY `search_conv_time` (`convtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_search_sequence`
--

CREATE TABLE IF NOT EXISTS `tp_search_sequence` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `tp_sessions`
--

CREATE TABLE IF NOT EXISTS `tp_sessions` (
  `sessionid` varchar(32) NOT NULL default '',
  `sessiontime` int(11) default NULL,
  `sessionstart` int(11) default NULL,
  `sessiondata` text,
  PRIMARY KEY  (`sessionid`),
  KEY `session_time` (`sessiontime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_users`
--

CREATE TABLE IF NOT EXISTS `tp_users` (
  `userid` int(11) NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `status` char(1) default '0',
  `admin` char(1) default '0',
  `fullname` varchar(255) default NULL,
  `emailaddress` varchar(255) default NULL,
  `quickstart` char(1) default '1',
  `settings` text,
  `usertimezone` varchar(255) default NULL,
  `ignoreips` text,
  `ignoresites` text,
  `ignorekeywords` text,
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tp_users_sequence`
--

CREATE TABLE IF NOT EXISTS `tp_users_sequence` (
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
