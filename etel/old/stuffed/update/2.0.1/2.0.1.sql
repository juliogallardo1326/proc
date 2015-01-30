ALTER TABLE {PREF}_tracker_config  ADD `FROM_EMAIL` varchar(128) NOT NULL default '';
ALTER TABLE {PREF}_tracker_config  ADD `ONLINE_PERIOD` smallint(6) unsigned NOT NULL default '0';
UPDATE {PREF}_tracker_config SET `ONLINE_PERIOD`=30 WHERE COMPANY_ID=0 AND SITE_ID=0;