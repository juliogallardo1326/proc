

CREATE TABLE `{PREF}_system_auth_session` (
  `ID` int(11) NOT NULL auto_increment,
  `SESS_ID` varchar(128) NOT NULL default '0',
  `UID` int(11) NOT NULL default '0',
  `IP` varchar(16) NOT NULL default '0.0.0.0',
  `AGENT` varchar(255) NOT NULL default '',
  `CREATED` bigint(20) default NULL,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_system_config` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `PRODUCT_ID` int(11) unsigned NOT NULL default '0',
  `PLUGIN_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(128) NOT NULL default '',
  `CALLNAME` varchar(128) NOT NULL default '',
  `DATATYPE` enum('INTVAL','STRVAL','MEMO') NOT NULL default 'INTVAL',
  `INTVAL` int(11) default NULL,
  `STRVAL` varchar(255) default NULL,
  `MEMO` text,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `UNI` (`PRODUCT_ID`,`CALLNAME`),
  KEY `PRODUCT_ID` (`PRODUCT_ID`),
  KEY `PLUGIN_ID` (`PLUGIN_ID`),
  KEY `DATATYPE` (`DATATYPE`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_system_plugin` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `PRODUCT_ID` int(11) unsigned NOT NULL default '0',
  `EVENT_NAME` varchar(128) NOT NULL default '',
  `NAME` varchar(128) NOT NULL default '',
  `DESCRIPTION` varchar(255) default NULL,
  `ORD` int(11) default NULL,
  `DIRNAME` varchar(127) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `PRODUCT_ID` (`PRODUCT_ID`),
  KEY `EVENT_NAME` (`EVENT_NAME`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_system_product` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `NAME` varchar(128) NOT NULL default '',
  `FOLDER` varchar(128) NOT NULL default '',
  `DEFAULT_LANG` varchar(10) NOT NULL default '',
  `STRT_INT` int(11) unsigned NOT NULL default '0',
  `DEFAULT_SKIN` varchar(128) NOT NULL default '',
  `WHITE_LOGO` varchar(255) NOT NULL default '',
  `VERSION` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_system_user` (
  `ID` int(11) NOT NULL auto_increment,
  `LOGIN` varchar(64) NOT NULL default '',
  `PWD` varchar(255) NOT NULL default '',
  `NAME` varchar(255) default NULL,
  `EMAIL` varchar(255) default NULL,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_system_user2lang` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `UID` int(11) NOT NULL default '0',
  `PROD_ID` int(11) default NULL,
  `LANG` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `UID` (`UID`),
  KEY `PROD_ID` (`PROD_ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_system_user2skin` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `UID` int(11) unsigned NOT NULL default '0',
  `PROD_ID` int(11) unsigned NOT NULL default '0',
  `SKIN` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `UID` (`UID`),
  KEY `PROD_ID` (`PROD_ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_action_item` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `NAME` (`NAME`),
  KEY `COMPANY_ID` (`COMPANY_ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_action_set` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `STAT_ACTION_ID` int(11) unsigned NOT NULL default '0',
  `ACTION_ITEM_ID` int(11) unsigned NOT NULL default '0',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `STAT_ACTION_ID` (`STAT_ACTION_ID`),
  KEY `ACTION_ITEM_ID` (`ACTION_ITEM_ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_admin` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `USER_ID` int(11) unsigned NOT NULL default '0',
  `SUPER_ADMIN` enum('1','0') NOT NULL default '0',
  `MODIFIED` timestamp(14) NOT NULL,
  `DEMO` enum('0','1') NOT NULL default '0',
  `ADVANCED_MODE` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `USER_ID` (`USER_ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_camp_cost` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `SUB_CAMPAIGN` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(255) default NULL,
  `START_DATE` date default NULL,
  `END_DATE` date default NULL,
  `COST` float(9,2) unsigned NOT NULL default '0.00',
  `SUM_THIS` enum('1','0') NOT NULL default '1',
  `MODE` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `SUB_CAMPAIGN` (`SUB_CAMPAIGN`),
  KEY `START_DATE` (`START_DATE`),
  KEY `END_DATE` (`END_DATE`),
  KEY `SUM_THIS` (`SUM_THIS`),
  KEY `MODE` (`MODE`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_camp_piece` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `CAMPAIGN_ID` int(11) unsigned NOT NULL default '0',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(255) NOT NULL default '',
  `DESCRIPTION` text,
  `POSITION` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `CAMPAIGN_ID` (`CAMPAIGN_ID`)
) TYPE=MyISAM;

CREATE TABLE `{PREF}_tracker_campaign` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `PARENT_ID` int(11) unsigned NOT NULL default '0',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(255) NOT NULL default '',
  `DESCRIPTION` text,
  `POSITION` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `PARENT_ID` (`PARENT_ID`),
  KEY `MERCHANT_ID` (`COMPANY_ID`)
) TYPE=MyISAM;

CREATE TABLE `{PREF}_tracker_client` (
  `ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(128) NOT NULL default '',
  `DESCRIPTION` text,
  `MODIFIED` timestamp(14) NOT NULL,
  `HIDDEN` enum('1','0') NOT NULL default '0',
  `CURRENCY` varchar(64) NOT NULL default '',
  `CURRENCY_POSITION` enum('0','1') NOT NULL default '0',
  `MAX_SITES` int(11) unsigned NOT NULL DEFAULT '0',
  `ALLOW_PHP_TRACKING` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `HIDDEN` (`HIDDEN`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_client_visitor` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `VISITOR_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(255) NOT NULL default '',
  `DESCRIPTION` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `CLIENT_ID` (`COMPANY_ID`),
  KEY `VISITOR_ID` (`VISITOR_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_client_visitor_grp` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(255) NOT NULL default '',
  `DESCRIPTION` text NOT NULL,
  PRIMARY KEY  (`ID`)
) TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_client_visitor_grp_ip` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `GRP_ID` int(11) unsigned NOT NULL default '0',
  `IP` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `GRP_ID` (`GRP_ID`),
  KEY `IP` (`IP`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_client_visitor_ip` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `CLIENT_VISITOR_ID` int(11) unsigned NOT NULL default '0',
  `IP` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `CLIENT_VISITOR_ID` (`CLIENT_VISITOR_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_config` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `SITE_ID` int(11) unsigned NOT NULL default '0',
  `KEEP_VISITOR_PATH` enum('0','1','2') NOT NULL default '2',
  `KEEP_NO_REF` enum('0','1','2') NOT NULL default '2',
  `TIME_DBL_PAGE_LOAD` int(11) unsigned NOT NULL default '0',
  `TIME_DBL_ADV_CLICK` int(11) unsigned NOT NULL default '0',
  `TIME_DBL_EVENT` int(11) unsigned NOT NULL default '0',
  `TIME_DBL_SALE` int(11) unsigned NOT NULL default '0',
  `STOP_DBL_PAGE_LOAD` enum('0','1','2') NOT NULL default '2',
  `STOP_DBL_ADV_CLICK` enum('0','1','2') NOT NULL default '2',
  `STOP_DBL_EVENT` enum('0','1','2') NOT NULL default '2',
  `STOP_DBL_SALE` enum('0','1','2') NOT NULL default '2',
  `AGENTS_LAST_UPDATED` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ALLOW_SEND_INFO` enum('0','1') NOT NULL DEFAULT '0',
  `SSL_LINK` text NOT NULL,
  `USE_STORE` enum('0','1') NOT NULL default '0',
  `FROM_EMAIL` varchar(128) NOT NULL default '',
  `ONLINE_PERIOD` smallint(6) unsigned NOT NULL default '0',
  `IP_TRACKING` enum('0','1') NOT NULL default '1',
  `IP_PERIOD` int(11) unsigned NOT NULL default '1',
  `IP_NO_COOKIE` enum('0','1') NOT NULL default '1',
  `FRAUD_COUNT` int(11) unsigned NOT NULL default '5',
  `FRAUD_PERIOD` int(11) unsigned NOT NULL default '1',
  `FRAUD_ENABLE` enum('0','1') NOT NULL default '0',
  `VAR_CAMPAIGN` varchar(32) NOT NULL default '',
  `VAR_CAMPAIGN_SOURCE` varchar(32) NOT NULL default '',
  `VAR_KW` varchar(32) NOT NULL default '',
  `VAR_KEYWORD` varchar(32) NOT NULL default '', 
  `WHITE_NO_LOGO` enum('0','1') NOT NULL DEFAULT '0',
  `WHITE_NO_COPY` enum('0','1') NOT NULL DEFAULT '0',
  `TRACKING_MODE` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `UNI` (`COMPANY_ID`,`SITE_ID`),
  KEY `COMPANY_ID` (`COMPANY_ID`),
  KEY `SITE_ID` (`SITE_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_const_group` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `CONST_TYPE` enum('NATURAL','PAID') NOT NULL default 'NATURAL',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `KEY_NAME` varchar(32) NOT NULL default '',
  `POSITION` smallint(6) unsigned NOT NULL default '0',
  `ORDER_BY` enum('0','CNT','UNI','NAME','SALECNT','SALEUNI','ACTIONCNT','ACTIONUNI','ACTCONV','SALECONV','ROI','COST','INCOME') NOT NULL default '0',
  `ORDER_TO` enum('0','ASC','DESC') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `CONST_TYPE` (`CONST_TYPE`),
  KEY `COMPANY_ID` (`COMPANY_ID`),
  KEY `KEY_NAME` (`KEY_NAME`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_host` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `GRP_ID` int(11) unsigned NOT NULL default '0',
  `HOST` varchar(128) NOT NULL default '',
  `KEY_VAR` varchar(32) default NULL,
  `BAN` enum('0','1') NOT NULL default '0',
  `STAT_IGNORE` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `HOST` (`HOST`),
  KEY `STAT_IGNORE` (`STAT_IGNORE`),
  KEY `BAN` (`BAN`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_host_grp` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `NAME` varchar(128) NOT NULL default '',
  `KEY_VAR` varchar(32) default NULL,
  `BAN` enum('0','1') NOT NULL default '0',
  `REGULAR_EXPRESSION` text NOT NULL default '',
  `REGULAR_EXPRESSION2` text NOT NULL default '',
  PRIMARY KEY  (`ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_ip` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `IP` varchar(15) NOT NULL default '',
  `IGNORED` enum('0','1') NOT NULL default '0',
  `DESCRIPTION` varchar(255) NOT NULL default '', 
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `IP` (`IP`),
  KEY `IGNORE` (`IGNORED`)
)  TYPE=MyISAM;

CREATE TABLE `{PREF}_tracker_ip_ignore` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `TEMPLATE` varchar(15) NOT NULL default '',
  `START_INT` int(11) unsigned NOT NULL default '0',
  `END_INT` int(11) unsigned NOT NULL default '0',
  `DESCRIPTION` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `IP` (`START_INT`,`END_INT`),
  KEY `TEMPLATE` (`TEMPLATE`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_keyword` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `KEYWORD` varchar(255) NOT NULL default '',
  `MD5_SEARCH` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  INDEX `MD5_SEARCH` (`MD5_SEARCH`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_license` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `KEY_ID` varchar(64) NOT NULL default '',
  `LICENSE_KEY` text NOT NULL,
  `STAMP` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_query` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `QUERY_STRING` text NOT NULL,
  `MD5_SEARCH` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  INDEX `MD5_SEARCH` (`MD5_SEARCH`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_referer` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `REFERER` text NOT NULL,
  `MD5_SEARCH` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  INDEX `MD5_SEARCH` (`MD5_SEARCH`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_referer_set` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `REFERER_ID` int(11) unsigned NOT NULL default '0',
  `HOST_ID` int(11) unsigned NOT NULL default '0',
  `NATURAL_KEY` int(11) unsigned NOT NULL default '0',
  `PROCESSED` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `REFERER_ID` (`REFERER_ID`),
  KEY `HOST_ID` (`HOST_ID`),
  KEY `NATURAL_KEY` (`NATURAL_KEY`),
  KEY `PROCESSED` (`PROCESSED`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_sale_item` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(255) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `COMPANY_ID` (`COMPANY_ID`)
)  TYPE=MyISAM;

CREATE TABLE `{PREF}_tracker_sale_set` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `SALE_ID` int(11) unsigned NOT NULL default '0',
  `ITEM_ID` int(11) unsigned NOT NULL default '0',
  `QUANT` smallint(6) unsigned NOT NULL default '0',
  `COST` double(15,2) unsigned NOT NULL default '0.00',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `SALE_ID` (`SALE_ID`),
  KEY `ITEM_ID` (`ITEM_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_site` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `HOST` varchar(255) NOT NULL default '',
  `SHOW_PAGE_TITLES` enum('0','1') NOT NULL default '0',
  `USE_HOSTS` enum('0','1') NOT NULL DEFAULT '0',
  `COOKIE_DOMAIN` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `HOST` (`HOST`),
  KEY `COMPANY_ID` (`COMPANY_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_site_host` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `HOST` varchar(128) NOT NULL default '',
  `SITE_ID` int(11) unsigned NOT NULL default '0',
  `ENABLED` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY  (`ID`),
  KEY `HOST` (`HOST`),
  KEY `SITE_ID` (`SITE_ID`),
  INDEX `ENABLE` (`ENABLED`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_site_page` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `SITE_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(128) NOT NULL default '',
  `PATH` varchar(255) NOT NULL default '',
  `IGNORE_PAGE` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `SITE_ID` (`SITE_ID`),
  KEY `PATH` (`PATH`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_source_host` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `HOST_ID` int(11) unsigned NOT NULL default '0',
  `CAMP_ID` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `HOST_ID` (`HOST_ID`),
  KEY `CAMP_ID` (`CAMP_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_split_page` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `SPLIT_ID` int(11) unsigned NOT NULL default '0',
  `PAGE_ID` int(11) unsigned NOT NULL default '0',
  `QUERY_ID` int(11) unsigned NOT NULL default '0',
  `COUNTER` int(11) unsigned NOT NULL default '0',
  `FULL_PATH` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `SPLIT_ID` (`SPLIT_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_split_test` (
  `ID` int(11) NOT NULL auto_increment,
  `SUB_ID` int(11) unsigned NOT NULL default '0',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `REMEMBER_PAGE` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY  (`ID`),
  KEY `SUB_ID` (`SUB_ID`),
  KEY `COMPANY_ID` (`COMPANY_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_1_stat_action` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `LOG_ID` int(11) unsigned NOT NULL default '0',
  `ACTION_ID` int(11) unsigned NOT NULL default '0',
  `SITE_ID` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `LOG_ID` (`LOG_ID`),
  KEY `ACTION_ID` (`ACTION_ID`),
  KEY `SITE_ID` (`SITE_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_1_stat_click` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `LOG_ID` int(11) unsigned NOT NULL default '0',
  `KEYWORD_ID` int(11) unsigned NOT NULL default '0',
  `CAMP_ID` int(11) unsigned NOT NULL default '0',
  `SOURCE_HOST_ID` int(11) unsigned NOT NULL default '0',
  `FRAUD` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY  (`ID`),
  KEY `LOG_ID` (`LOG_ID`),
  KEY `KEYWORD_ID` (`KEYWORD_ID`),
  KEY `CAMP_ID` (`CAMP_ID`),
  KEY `SOURCE_HOST_ID` (`SOURCE_HOST_ID`),
  INDEX `FRAUD` (`FRAUD`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_1_stat_log` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `STAMP` datetime NOT NULL default '0000-00-00 00:00:00',
  `VISITOR_ID` int(11) unsigned NOT NULL default '0',
  `SITE_ID` int(11) unsigned NOT NULL default '0',
  `SITE_HOST_ID` int(11) unsigned NOT NULL default '0',
  `COOKIE_LOG` int(11) unsigned NOT NULL default '0',
  `PAGE_ID` int(11) unsigned NOT NULL default '0',
  `QUERY_ID` int(11) unsigned NOT NULL default '0',
  `REFERER_SET` int(11) unsigned NOT NULL default '0',
  `IP_ID` int(11) unsigned NOT NULL default '0',
  `AGENT_ID` int(11) unsigned NOT NULL default '0',
  `SCHEME` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `VISITOR_ID` (`VISITOR_ID`),
  KEY `SITE_ID` (`SITE_ID`),
  KEY `COOKIE_LOG` (`COOKIE_LOG`),
  KEY `DBL_1` (`SITE_ID`,`STAMP`),
  KEY `PAGE_ID` (`PAGE_ID`),
  KEY `QUERY_ID` (`QUERY_ID`),
  KEY `REFERER_SET` (`REFERER_SET`),
  KEY `SITE_HOST_ID` (`SITE_HOST_ID`),
  KEY `AGENT_ID` (`AGENT_ID`),
  KEY `IP_ID` (`IP_ID`)
)  TYPE=MyISAM;

CREATE TABLE `{PREF}_tracker_1_stat_sale` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `LOG_ID` int(11) unsigned NOT NULL default '0',
  `COST` float(9,2) unsigned NOT NULL default '0.00',
  `SITE_ID` int(11) unsigned NOT NULL default '0',
  `CUSTOM_ORDER_ID` varchar(64) NOT NULL default '',
  `ADDITIONAL` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `LOG_ID` (`LOG_ID`),
  KEY `SITE_ID` (`SITE_ID`),
  KEY `CUSTOM_ORDER_ID` (`CUSTOM_ORDER_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_1_stat_split` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `LOG_ID` int(11) unsigned NOT NULL default '0',
  `SPLIT_ID` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `LOG_ID` (`LOG_ID`),
  KEY `SPLIT_ID` (`SPLIT_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_1_stat_undef` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `LOG_ID` int(11) unsigned NOT NULL default '0',
  `ADDRESS` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `ADDRESS` (`ADDRESS`),
  KEY `LOG_ID` (`LOG_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_sub_campaign` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `SUB_ID` int(11) unsigned NOT NULL default '0',
  `TYPE` enum('0','1') NOT NULL default '0',
  `SRC_ID` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `CAMPAIGN_ID` (`SUB_ID`),
  INDEX `SRC_ID` (`SRC_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_user` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `USER_ID` int(11) unsigned NOT NULL default '0',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `SUPER_USER` enum('1','0') NOT NULL default '0',
  `MODIFIED` timestamp(14) NOT NULL,
  `ADVANCED_MODE` enum('0','1') NOT NULL default '0',
  `TIMEZONE` varchar(5) NOT NULL DEFAULT 'a',
  `DEMO` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `USER_ID` (`USER_ID`),
  KEY `COMPANY_ID` (`COMPANY_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_user_column` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `USER_ID` int(11) unsigned NOT NULL default '0',
  `HITS` enum('0','1') NOT NULL default '1',
  `SALES` enum('0','1') NOT NULL default '1',
  `ACTIONS` enum('0','1') NOT NULL default '1',
  `CLICKS` enum('0','1') NOT NULL default '1',
  `ROI` enum('0','1') NOT NULL default '1',
  `CONVERSIONS` enum('0','1') NOT NULL default '1',
  `GRAPHS` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`ID`),
  KEY `USER_ID` (`USER_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_user_report` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `CONST_TYPE` enum('NATURAL','PAID') NOT NULL default 'NATURAL',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(255) NOT NULL default '',
  `VIEW_DATE` date NOT NULL default '0000-00-00',
  `START_DATE` date NOT NULL default '0000-00-00',
  `END_DATE` date NOT NULL default '0000-00-00',
  `FILTER` varchar(255) NOT NULL default '',
  `PAGE_LIMIT` smallint(6) unsigned NOT NULL default '0',
  `SHOW_NO_REF` enum('0','1') NOT NULL default '0',
  `SORT_BY` varchar(128) NOT NULL default '',
  `SORT_ORDER` enum('ASC','DESC') NOT NULL default 'ASC',
  `USE_CURRENT_DATE` enum('0','1') NOT NULL default '0',
  `GROUP_BY` varchar(128) NOT NULL default '',
  `WHERE_ARR` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `COMPANY_ID` (`COMPANY_ID`),
  KEY `CONST_TYPE` (`CONST_TYPE`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_user_settings` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `USER_ID` int(11) unsigned NOT NULL default '0',
  `ADVANCED_MODE` enum('0','1') NOT NULL default '0',
  `HELP_MODE` enum('0','1','2') NOT NULL default '2',
  `TIMEZONE` varchar(5) NOT NULL default 'a',
  `DEF_PATH_ORDER` varchar(255) NOT NULL,
  `PAGE_ENCODING` varchar(128) NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `USER_ID` (`USER_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_visitor` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `COOKIE_ID` varchar(32) NOT NULL default '',
  `LAST_IP_ID` int(11) unsigned NOT NULL default '0',
  `LAST_AGENT_ID` int(11) unsigned NOT NULL default '0',
  `FIRST_STAMP` datetime NOT NULL default '0000-00-00 00:00:00',
  `LAST_STAMP` datetime NOT NULL default '0000-00-00 00:00:00',
  `FIRST_COUNTRY_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `LAST_RESOLUTION` varchar(20) NOT NULL,
  `FLASH_VERSION` varchar(20) NOT NULL default '',
  `PIXEL_DEPTH` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `COOKIE_ID` (`COOKIE_ID`),
  KEY `LAST_AGENT_ID` (`LAST_AGENT_ID`),
  KEY `LAST_IP_ID` (`LAST_IP_ID`),
  KEY `FIRST_STAMP` (`FIRST_STAMP`),
  KEY `LAST_STAMP` (`LAST_STAMP`),
  INDEX `FIRST_COUNTRY_ID` (`FIRST_COUNTRY_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_visitor_action` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `PAGE_ID` int(11) unsigned NOT NULL default '0',
  `SITE_ID` int(11) unsigned NOT NULL default '0',
  `NAME` varchar(128) NOT NULL default '',
  `QUERY` varchar(255) NOT NULL default '',
  `PATH` varchar(255) NOT NULL default '',
  `REDIRECT_URL` varchar(255) NOT NULL default '',
  `REDIRECT_CATCH` enum('0','1') NOT NULL default '0',
  `ITEM_VAR` varchar(32) NOT NULL default '',
  `ACTIVE` enum('0','1') NOT NULL default '0',
  `SITE_HOST_ID` int(11) unsigned NOT NULL DEFAULT '0',
  `CODE_ACTION` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY  (`ID`),
  KEY `PAGE_ID` (`PAGE_ID`),
  KEY `QUERY` (`QUERY`),
  KEY `SITE_ID` (`SITE_ID`),
  KEY `REDIRECT_CATCH` (`REDIRECT_CATCH`),
  KEY `ACTIVE` (`ACTIVE`),
  INDEX `CODE_ACTION` (`CODE_ACTION`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_visitor_agent` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `USER_AGENT` varchar(255) NOT NULL default '',
  `GRP_ID` int(11) unsigned NOT NULL default '0',
  `BAN` enum('0','1') NOT NULL default '0',
  `MD5_SEARCH` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  INDEX `MD5_SEARCH` (`MD5_SEARCH`),
  KEY `BAN` (`BAN`),
  KEY `GRP_ID` (`GRP_ID`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_visitor_agent_grp` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `NAME` varchar(128) NOT NULL default '',
  `BAN` enum('0','1') NOT NULL default '0',
  `REGULAR_EXPRESSION` text NOT NULL,
  `REGULAR_EXPRESSION2` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `BAN` (`BAN`)
)  TYPE=MyISAM;


CREATE TABLE `{PREF}_tracker_watch` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `USER_ID` int(11) unsigned NOT NULL default '0',
  `COMPANY_ID` int(11) unsigned NOT NULL default '0',
  `GRP_ID` int(11) unsigned NOT NULL default '0',
  `SITE_ID` int(11) unsigned NOT NULL default '0',
  `SUB_ID` int(11) unsigned NOT NULL default '0',
  `VISITOR_ID` int(11) unsigned NOT NULL default '0',
  `VISITOR_GRP_ID` int(11) unsigned NOT NULL default '0',
  `ACTION_ID` int(11) unsigned NOT NULL default '0',
  `ACTION_ITEM_ID` int(11) unsigned NOT NULL default '0',
  `SALE_ITEM_ID` int(11) unsigned NOT NULL default '0',
  `REPORT_ID` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `COMPANY_ID` (`COMPANY_ID`,`USER_ID`),
  KEY `GRP_ID` (`GRP_ID`,`USER_ID`),
  KEY `SITE_ID` (`SITE_ID`,`USER_ID`),
  KEY `SUB_ID` (`SUB_ID`,`USER_ID`),
  KEY `VISITOR_ID` (`VISITOR_ID`,`USER_ID`),
  KEY `ACTION_ID` (`ACTION_ID`,`USER_ID`),
  KEY `ACTION_ITEM_ID` (`ACTION_ITEM_ID`,`USER_ID`),
  KEY `REPORT_ID` (`REPORT_ID`,`USER_ID`),
  KEY `SALE_ITEM_ID` (`SALE_ITEM_ID`,`USER_ID`),
  KEY `VISITOR_GRP_ID` (`VISITOR_GRP_ID`,`USER_ID`)
)  TYPE=MyISAM;

CREATE TABLE `{PREF}_tracker_country` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `CODE` char(3) NOT NULL default '',
  `NAME` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `CODE` (`CODE`)
)  TYPE=MyISAM;

CREATE TABLE `{PREF}_tracker_country_ip` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `COUNTRY_CODE` char(3) NOT NULL default '',
  `IP_START` int(11) unsigned NOT NULL default '0',
  `IP_END` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `COUNTRY_CODE` (`COUNTRY_CODE`),
  KEY `IP_START` (`IP_START`),
  KEY `IP_END` (`IP_END`)
)  TYPE=MyISAM;

