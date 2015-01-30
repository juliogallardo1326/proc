ALTER TABLE `{PREF}_tracker_config` ADD `WHITE_NO_LOGO` enum('0','1') NOT NULL DEFAULT '0';
ALTER TABLE `{PREF}_tracker_config` ADD `WHITE_NO_COPY` enum('0','1') NOT NULL DEFAULT '0';
ALTER TABLE `{PREF}_tracker_config` ADD `TRACKING_MODE` varchar(128) NOT NULL default '';

ALTER TABLE `{PREF}_tracker_client` ADD `ALLOW_PHP_TRACKING` enum('0','1') NOT NULL default '0';

ALTER TABLE `{PREF}_tracker_user` ADD `DEMO` enum('0','1') NOT NULL default '0';

ALTER TABLE `{PREF}_tracker_user_settings` ADD `DEF_PATH_ORDER` varchar(255) NOT NULL;
ALTER TABLE `{PREF}_tracker_user_settings` ADD `PAGE_ENCODING` varchar(128) NOT NULL;

ALTER TABLE `{PREF}_tracker_visitor` ADD `LAST_RESOLUTION` varchar(20) NOT NULL;
ALTER TABLE `{PREF}_tracker_visitor` ADD `PIXEL_DEPTH` smallint(4) NOT NULL default '0';
ALTER TABLE `{PREF}_tracker_visitor` ADD `FLASH_VERSION` varchar(20) NOT NULL default '';

ALTER TABLE `{PREF}_tracker_site` ADD `COOKIE_DOMAIN` varchar(255) NOT NULL default '';

ALTER TABLE `{PREF}_tracker_site_page` ADD `IGNORE_PAGE` enum('0','1') NOT NULL default '0';

CREATE TABLE `{PREF}_tracker_ip_ignore` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `TEMPLATE` varchar(15) NOT NULL default '',
  `START_INT` int(11) unsigned NOT NULL default '0',
  `END_INT` int(11) unsigned NOT NULL default '0',
  `DESCRIPTION` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `IP` (`START_INT`,`END_INT`),
  KEY `TEMPLATE` (`TEMPLATE`)
) TYPE=MyISAM;