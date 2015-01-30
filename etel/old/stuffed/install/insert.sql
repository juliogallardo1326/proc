
INSERT INTO `{PREF}_system_product` (`ID`, `NAME`, `FOLDER`, `DEFAULT_LANG`, `STRT_INT`, `DEFAULT_SKIN`, `VERSION`) VALUES 
(1, '{PRODUCT_NAME}', '{FOLDER}', '{C_LANG}', 0, 'default', '{P_VERSION}');

INSERT INTO `{PREF}_system_user` (`ID`, `LOGIN`, `PWD`, `NAME`, `EMAIL`) VALUES 
(1, '{REG_LOGIN}', '{REG_PASS}', '{REG_NAME}', '{REG_EMAIL}');

INSERT INTO `{PREF}_system_user2lang` (`ID`, `UID`, `PROD_ID`, `LANG`) VALUES 
(1, 1, 1, '{C_LANG}');

INSERT INTO `{PREF}_system_user2skin` (`ID`, `UID`, `PROD_ID`, `SKIN`) VALUES 
(1, 1, 1, 'default');

INSERT INTO `{PREF}_tracker_admin` (`ID`, `USER_ID`, `SUPER_ADMIN`, `MODIFIED`, `DEMO`, `ADVANCED_MODE`) VALUES 
(1, 1, '1', NOW(), '0', '0');

INSERT INTO `{PREF}_tracker_client` (`ID`, `NAME`, `DESCRIPTION`, `MODIFIED`, `HIDDEN`) VALUES 
(1, '{CLIENT_NAME}', '{CLIENT_DESCR}', NOW(), '0');


INSERT INTO `{PREF}_tracker_config` 
(`ID`, `COMPANY_ID`, `SITE_ID`, `KEEP_VISITOR_PATH`, `KEEP_NO_REF`, 
`TIME_DBL_PAGE_LOAD`, `TIME_DBL_ADV_CLICK`, `TIME_DBL_EVENT`, `TIME_DBL_SALE`, 
`STOP_DBL_PAGE_LOAD`, `STOP_DBL_ADV_CLICK`, `STOP_DBL_EVENT`, `STOP_DBL_SALE`,
`VAR_CAMPAIGN`, `VAR_CAMPAIGN_SOURCE`, `VAR_KW`, `VAR_KEYWORD`) VALUES 

(1, 0, 0, '1', '1', 0, 0, 0, 0, '0', '0', '0', '0', '', '', '', ''),
(2, 1, 0, '2', '2', 0, 0, 0, 0, '2', '2', '2', '2', '', '', '', ''),
(3, 1, 1, '2', '2', 0, 0, 0, 0, '2', '2', '2', '2', '', '', '', '');


UPDATE `{PREF}_tracker_config` SET `AGENTS_LAST_UPDATED`=NOW(), `ALLOW_SEND_INFO`='{SEND_USAGE}', `ONLINE_PERIOD`='30'  WHERE `COMPANY_ID`=0 AND `SITE_ID`=0;



INSERT INTO `{PREF}_tracker_const_group` 
(`CONST_TYPE`, `COMPANY_ID`, `KEY_NAME`, `POSITION`, `ORDER_BY`, `ORDER_TO`) 
VALUES 
('NATURAL', 0, 'Site', 0, 'NAME', 'ASC'),
('NATURAL', 0, 'SourceGrp', 1, 'CNT', 'DESC'),
('NATURAL', 0, 'Source', 2, 'CNT', 'DESC'),
('NATURAL', 0, 'Ref', 3, 'CNT', 'DESC'),
('NATURAL', 0, 'Key', 4, 'CNT', 'DESC'),
('NATURAL', 0, 'Month', 5, 'NAME', 'DESC'),
('NATURAL', 0, 'Date', 6, 'NAME', 'DESC'),
('NATURAL', 0, 'AgentGrp', 7, 'CNT', 'DESC'),
('NATURAL', 0, 'Agent', 8, 'CNT', 'DESC'),
('NATURAL', 0, 'Vis', 9, 'CNT', 'DESC'),
('NATURAL', 0, 'Page', 10, 'CNT', 'DESC'),
('NATURAL', 0, 'Action', 11, 'CNT', 'DESC'),
('NATURAL', 0, 'Order', 12, 'NAME', 'ASC'),
('NATURAL', 0, 'ActionItem', 13, 'CNT', 'DESC'),
('NATURAL', 0, 'Sale', 14, 'CNT', 'DESC'),
('NATURAL', 0, 'Year', 15, 'CNT', 'DESC'),
('NATURAL', 0, 'WeekDay', 16, 'CNT', 'DESC'),
('NATURAL', 0, 'Time', 17, 'CNT', 'DESC'),
('NATURAL', 0, 'Split', 18, 'CNT', 'DESC'),
('NATURAL', 0, 'Host', 19, 'CNT', 'DESC'),

('PAID', 0, 'Camp', 0, 'CNT', 'DESC'),
('PAID', 0, 'CampSource', 1, 'CNT', 'DESC'),
('PAID', 0, 'CampKey', 2, 'CNT', 'DESC'),
('PAID', 0, 'Action', 3, 'CNT', 'DESC' ),
('PAID', 0, 'Order', 4, 'NAME', 'DESC'),
('PAID', 0, 'Month', 5, 'NAME', 'DESC'),
('PAID', 0, 'Date', 6, 'NAME', 'DESC'),
('PAID', 0, 'Page', 7, 'CNT', 'ASC'),
('PAID', 0, 'Vis', 8, 'CNT', 'DESC'),
('PAID', 0, 'CampRef', 9, 'CNT', 'DESC' ),
('PAID', 0, 'Site', 10, 'CNT', 'DESC'),
('PAID', 0, 'Grp', 11, 'CNT', 'DESC'),
('PAID', 0, 'ActionItem', 12, 'CNT', 'DESC'),
('PAID', 0, 'Sale', 13, 'CNT', 'DESC'),
('PAID', 0, 'Year', 14, 'NAME', 'DESC'),
('PAID', 0, 'WeekDay', 15, 'NAME', 'DESC'),
('PAID', 0, 'Time', 16, 'NAME', 'DESC'),
('PAID', 0, 'AgentGrp', 17, 'CNT', 'DESC'),
('PAID', 0, 'Agent', 18, 'CNT', 'DESC'),
('PAID', 0, 'Split', 19, 'CNT', 'DESC'),
('PAID', 0, 'Host', 20, 'CNT', 'DESC');


#{LICENSE}

INSERT INTO `{PREF}_tracker_site` (`ID`, `COMPANY_ID`, `HOST`, `SHOW_PAGE_TITLES`) VALUES 
(1, 1, '{SITE_DOMAIN}', '0');

INSERT INTO `{PREF}_tracker_site_host` (`HOST`, `SITE_ID`) VALUES ('{SITE_DOMAIN}', 1);

INSERT INTO `{PREF}_tracker_site_page` (`ID`, `SITE_ID`, `NAME`, `PATH`) VALUES 
(1, 1, '', '/');

INSERT INTO `{PREF}_tracker_user_column` (`ID`, `USER_ID`, `HITS`, `SALES`, `ACTIONS`, `CLICKS`, `ROI`, `CONVERSIONS`, `GRAPHS`) 
VALUES 
(1, 1, '1', '1', '1', '1', '1', '1', '1');

INSERT INTO `{PREF}_tracker_user_settings` (`ID`, `USER_ID`, `ADVANCED_MODE`, `HELP_MODE`) VALUES 
(1, 1, '0', '2');        

INSERT INTO `{PREF}_system_config` 
	(`ID`, `PRODUCT_ID`, `PLUGIN_ID`, `NAME`, `CALLNAME`, `DATATYPE`, `INTVAL`, `STRVAL`, `MEMO`) 
	VALUES 
	(1, 1, 0, 'Privacy Policy', 'P3P', 'STRVAL', NULL, 'CUR ADM OUR NOR STA NID', NULL),
	(2, 1, 0, '', 'P3P_REF', 'STRVAL', NULL, '', NULL);
        