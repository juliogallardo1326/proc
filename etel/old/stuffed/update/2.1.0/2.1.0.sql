
CREATE TABLE `{PREF}_tracker_country` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `CODE` char(3) NOT NULL default '',
  `NAME` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `CODE` (`CODE`)
) TYPE=MyISAM;

CREATE TABLE `{PREF}_tracker_country_ip` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `COUNTRY_CODE` char(3) NOT NULL default '',
  `IP_START` int(11) unsigned NOT NULL default '0',
  `IP_END` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `COUNTRY_CODE` (`COUNTRY_CODE`),
  KEY `IP_START` (`IP_START`),
  KEY `IP_END` (`IP_END`)
) TYPE=MyISAM;

ALTER TABLE `{PREF}_tracker_keyword` DROP INDEX `KEYWORD`;
ALTER TABLE `{PREF}_tracker_visitor_agent` DROP INDEX `USER_AGENT`;
ALTER TABLE `{PREF}_tracker_referer` DROP INDEX `REFERER`;
ALTER TABLE `{PREF}_tracker_query` DROP INDEX `QUERY_STRING`;
ALTER TABLE `{PREF}_tracker_site` DROP `NO_STAT`;
ALTER TABLE `{PREF}_tracker_site_host` DROP `NO_STAT`;

ALTER TABLE `{PREF}_tracker_client` ADD `MAX_SITES` int(11) unsigned NOT NULL DEFAULT '0' AFTER `CURRENCY_POSITION`;

ALTER TABLE `{PREF}_tracker_config` ADD `IP_TRACKING` enum('0','1') NOT NULL DEFAULT '0' AFTER `ONLINE_PERIOD`;
ALTER TABLE `{PREF}_tracker_config` ADD `IP_PERIOD` int(11) unsigned NOT NULL DEFAULT '0' AFTER `IP_TRACKING`;
ALTER TABLE `{PREF}_tracker_config` ADD `IP_NO_COOKIE` enum('0','1') NOT NULL DEFAULT '0' AFTER `IP_PERIOD`;
ALTER TABLE `{PREF}_tracker_config` ADD `FRAUD_COUNT` int(11) unsigned NOT NULL DEFAULT '0' AFTER `IP_NO_COOKIE`;
ALTER TABLE `{PREF}_tracker_config` ADD `FRAUD_PERIOD` int(11) unsigned NOT NULL DEFAULT '0' AFTER `FRAUD_COUNT`;
ALTER TABLE `{PREF}_tracker_config` ADD `FRAUD_ENABLE` enum('0','1') NOT NULL DEFAULT '0' AFTER `FRAUD_PERIOD`;
ALTER TABLE `{PREF}_tracker_config` ADD `VAR_CAMPAIGN` varchar(32) NOT NULL default '' AFTER `FRAUD_ENABLE`;
ALTER TABLE `{PREF}_tracker_config` ADD `VAR_CAMPAIGN_SOURCE` varchar(32) NOT NULL default '' AFTER `VAR_CAMPAIGN`;
ALTER TABLE `{PREF}_tracker_config` ADD `VAR_KW` varchar(32) NOT NULL default '' AFTER `VAR_CAMPAIGN_SOURCE`;
ALTER TABLE `{PREF}_tracker_config` ADD `VAR_KEYWORD` varchar(32) NOT NULL default '' AFTER `VAR_KW`;

ALTER TABLE `{PREF}_tracker_ip` ADD `IGNORED` enum('0','1') NOT NULL DEFAULT '0' AFTER `IP`;
ALTER TABLE `{PREF}_tracker_ip` ADD `DESCRIPTION` varchar(255) NOT NULL AFTER `IGNORED`;
ALTER TABLE `{PREF}_tracker_ip` ADD INDEX `IGNORE` (`IGNORED`);

ALTER TABLE `{PREF}_tracker_keyword` ADD `MD5_SEARCH` varchar(32) NOT NULL AFTER `KEYWORD`;
ALTER TABLE `{PREF}_tracker_query` ADD `MD5_SEARCH` varchar(32) NOT NULL AFTER `QUERY_STRING`;
ALTER TABLE `{PREF}_tracker_referer` ADD `MD5_SEARCH` varchar(32) NOT NULL AFTER `REFERER`;
ALTER TABLE `{PREF}_tracker_visitor_agent` ADD `MD5_SEARCH` varchar(32) NOT NULL AFTER `BAN`;
ALTER TABLE `{PREF}_tracker_query` ADD INDEX `MD5_SEARCH` (`MD5_SEARCH`);
ALTER TABLE `{PREF}_tracker_visitor_agent` ADD INDEX `MD5_SEARCH` (`MD5_SEARCH`);
ALTER TABLE `{PREF}_tracker_keyword` ADD INDEX `MD5_SEARCH` (`MD5_SEARCH`);
ALTER TABLE `{PREF}_tracker_referer` ADD INDEX `MD5_SEARCH` (`MD5_SEARCH`);

ALTER TABLE `{PREF}_tracker_site` ADD `USE_HOSTS` enum('0','1') NOT NULL DEFAULT '0' AFTER `SHOW_PAGE_TITLES`;
ALTER TABLE `{PREF}_tracker_site_host` ADD `ENABLED` enum('0','1') NOT NULL DEFAULT '1' AFTER `SITE_ID`;
ALTER TABLE `{PREF}_tracker_site_host` ADD INDEX `ENABLE` (`ENABLED`);

ALTER TABLE `{PREF}_tracker_split_test` ADD `REMEMBER_PAGE` enum('0','1') NOT NULL DEFAULT '0' AFTER `COMPANY_ID`;
ALTER TABLE `{PREF}_tracker_split_page` DROP INDEX `SPLIT_ID`;
ALTER TABLE `{PREF}_tracker_split_page` ADD `FULL_PATH` text;
ALTER TABLE `{PREF}_tracker_split_page` ADD INDEX `SPLIT_ID` (`SPLIT_ID`);

ALTER TABLE `{PREF}_tracker_sub_campaign` ADD `SRC_ID` varchar(64) NOT NULL AFTER `TYPE`;
ALTER TABLE `{PREF}_tracker_sub_campaign` ADD INDEX `SRC_ID` (`SRC_ID`);

ALTER TABLE `{PREF}_tracker_user_settings` ADD `TIMEZONE` varchar(5) NOT NULL DEFAULT 'a' AFTER `HELP_MODE`;

ALTER TABLE `{PREF}_tracker_visitor` ADD `FIRST_COUNTRY_ID` int(11) unsigned NOT NULL DEFAULT '0' AFTER `LAST_STAMP`;
ALTER TABLE `{PREF}_tracker_visitor` ADD INDEX `FIRST_COUNTRY_ID` (`FIRST_COUNTRY_ID`);

ALTER TABLE `{PREF}_tracker_visitor_action` ADD `SITE_HOST_ID` int(11) unsigned NOT NULL DEFAULT '0' AFTER `SITE_ID`;
ALTER TABLE `{PREF}_tracker_visitor_action` ADD `CODE_ACTION` enum('0','1') NOT NULL DEFAULT '0' AFTER `ACTIVE`;
ALTER TABLE `{PREF}_tracker_visitor_action` ADD INDEX `CODE_ACTION` (`CODE_ACTION`);

UPDATE {PREF}_tracker_query SET MD5_SEARCH = MD5(QUERY_STRING);
UPDATE {PREF}_tracker_keyword SET MD5_SEARCH = MD5(KEYWORD);
UPDATE {PREF}_tracker_referer SET MD5_SEARCH = MD5(REFERER);
UPDATE {PREF}_tracker_visitor_agent SET MD5_SEARCH = MD5(USER_AGENT);

UPDATE {PREF}_tracker_config SET 
			IP_TRACKING='1',
			IP_NO_COOKIE='1',
			IP_PERIOD=1,
			FRAUD_COUNT=5,
			FRAUD_PERIOD=1,
			VAR_CAMPAIGN='c',
			VAR_KEYWORD='k',
			VAR_KW='kw' 
			WHERE COMPANY_ID=0 AND SITE_ID=0;
