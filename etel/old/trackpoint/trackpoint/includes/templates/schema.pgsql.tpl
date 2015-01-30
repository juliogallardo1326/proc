CREATE TABLE %%GLOBAL_TablePrefix%%users (
  userid integer NOT NULL default '0',
  username varchar(255) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  status char(1) default '0',
  admin char(1) default '0',
  fullname varchar(255) default NULL,
  emailaddress varchar(255) default NULL,
  quickstart char(1) default '1',
  settings text,
  usertimezone varchar(255),
  ignoreips text,
  ignoresites text,
  ignorekeywords text,
  PRIMARY KEY  (userid)
);
CREATE SEQUENCE %%GLOBAL_TablePrefix%%users_sequence;
SELECT nextval('%%GLOBAL_TablePrefix%%users_sequence');
INSERT INTO %%GLOBAL_TablePrefix%%users (userid, username, password, status, admin, quickstart, usertimezone) VALUES (1,'admin','5f4dcc3b5aa765d61d8327deb882cf99','1','1','1', 'GMT');

create table %%GLOBAL_TablePrefix%%cookies (
  sessionid varchar(32) default NULL,
  cookieid varchar(255) default NULL,
  cookietype varchar(10) default NULL,
  cookiefrom varchar(255) default NULL,
  cookiedetails varchar(255) default NULL,
  remove char(1) default '0',
  cookietime integer default 0
);

create table %%GLOBAL_TablePrefix%%referrers (
  referrerid integer NOT NULL default '0',
  domain varchar(255) default NULL,
  url varchar(255) default NULL,
  currtime integer default 0,
  convtime integer default 0,
  ip varchar(20) default NULL,
  landingpage varchar(255) default NULL,
  cookieid varchar(255) default NULL,
  userid integer default NULL,
  hasconversion integer default 0,
  amount float default 0,
  PRIMARY KEY  (referrerid)
);
CREATE SEQUENCE %%GLOBAL_TablePrefix%%referrers_sequence;

create table %%GLOBAL_TablePrefix%%search (
  searchid integer NOT NULL default '0',
  searchenginename varchar(255) default NULL,
  keywords varchar(255) default NULL,
  currtime integer default 0,
  convtime integer default 0,
  ip varchar(20) default NULL,
  landingpage varchar(255) default NULL,
  cookieid varchar(255) default NULL,
  userid integer default NULL,
  hasconversion integer default 0,
  amount float default 0,
  PRIMARY KEY  (searchid)
);
CREATE SEQUENCE %%GLOBAL_TablePrefix%%search_sequence;

create table %%GLOBAL_TablePrefix%%campaigns (
  campaignid integer NOT NULL default '0',
  campaignsite varchar(255) default NULL,
  campaignname varchar(255) default NULL,
  cost float default 0,
  period integer default 0,
  startdate integer default 0,
  hasconversion integer default '0',
  amount float default 0,
  currtime integer default 0,
  convtime integer default 0,
  userid integer default NULL,
  ip varchar(20),
  cookieid varchar(255) default NULL,
  PRIMARY KEY  (campaignid)
);
CREATE SEQUENCE %%GLOBAL_TablePrefix%%campaigns_sequence;

create table %%GLOBAL_TablePrefix%%payperclicks (
  ppcid integer NOT NULL default 0,
  searchenginename varchar(255) default NULL,
  ppcname varchar(255) default NULL,
  cost float default 0,
  hasconversion integer default 0,
  amount float default 0,
  currtime integer default 0,
  convtime integer default 0,
  userid integer default NULL,
  ip varchar(20),
  cookieid varchar(255) default NULL,
  PRIMARY KEY  (ppcid)
);
CREATE SEQUENCE %%GLOBAL_TablePrefix%%payperclicks_sequence;

create table %%GLOBAL_TablePrefix%%conversions (
  conversionid integer NOT NULL default '0',
  type varchar(10) default NULL,
  name varchar(255) default NULL,
  amount float default 0,
  cookieid varchar(255) default NULL,
  sessionid char(32) default NULL,
  currtime integer default 0,
  ip varchar(20) default NULL,
  origintype varchar(20) default NULL,
  originfrom varchar(255) default NULL,
  origindetails varchar(255) default NULL,
  userid integer default NULL,
  PRIMARY KEY  (conversionid)
);
CREATE SEQUENCE %%GLOBAL_TablePrefix%%conversions_sequence;

CREATE TABLE %%GLOBAL_TablePrefix%%sessions (
        sessionid char(32) NOT NULL default '',
        sessiontime int default NULL,
        sessionstart int default NULL,
        sessiondata text,
        PRIMARY KEY  (sessionid)
);

CREATE TABLE %%GLOBAL_TablePrefix%%loghistory (
  logid int,
  file varchar(255),
  line int,
  userid int,
  logtime int,
  logtype varchar(255),
  loglevel varchar(255),
  ip varchar(20),
  logentry text,
  sessionid varchar(255)
);
CREATE SEQUENCE %%GLOBAL_TablePrefix%%loghistory_sequence;

-- indexes go here..
create index conv_userid on %%GLOBAL_TablePrefix%%conversions(userid);
create index conv_origintype on %%GLOBAL_TablePrefix%%conversions(origintype);
create index conv_originfrom on %%GLOBAL_TablePrefix%%conversions(originfrom);
create index conv_origindetails on %%GLOBAL_TablePrefix%%conversions(origindetails);
create index conv_time on %%GLOBAL_TablePrefix%%conversions(currtime);
create index conv_cookie on %%GLOBAL_TablePrefix%%conversions(cookieid);
create index conv_session on %%GLOBAL_TablePrefix%%conversions(sessionid);

create index referrers_cookieid on %%GLOBAL_TablePrefix%%referrers(cookieid);
create index referrers_landingpage on %%GLOBAL_TablePrefix%%referrers(landingpage);
create index referrers_url on %%GLOBAL_TablePrefix%%referrers(url);
create index referrers_domain on %%GLOBAL_TablePrefix%%referrers(domain);
create index referrers_userid on %%GLOBAL_TablePrefix%%referrers(userid);
create index referrers_time on %%GLOBAL_TablePrefix%%referrers(currtime);
create index referrers_conv_time on %%GLOBAL_TablePrefix%%referrers(convtime);

create index session_index on %%GLOBAL_TablePrefix%%cookies(sessionid);
create index cookie_index on %%GLOBAL_TablePrefix%%cookies(cookieid);
create index remove_index on %%GLOBAL_TablePrefix%%cookies(remove);
create index cookietime_index on %%GLOBAL_TablePrefix%%cookies(cookietime);

create index ppc_searchengine on %%GLOBAL_TablePrefix%%payperclicks(searchenginename);
create index ppc_name on %%GLOBAL_TablePrefix%%payperclicks(ppcname);
create index ppc_cookieid on %%GLOBAL_TablePrefix%%payperclicks(cookieid);
create index ppc_time on %%GLOBAL_TablePrefix%%payperclicks(currtime);
create index ppc_conv_time on %%GLOBAL_TablePrefix%%payperclicks(convtime);

create index search_searchenginename on %%GLOBAL_TablePrefix%%search(searchenginename);
create index search_keywords on %%GLOBAL_TablePrefix%%search(keywords);
create index search_userid on %%GLOBAL_TablePrefix%%search(userid);
create index search_cookieid on %%GLOBAL_TablePrefix%%search(cookieid);
create index search_landingpage on %%GLOBAL_TablePrefix%%search(landingpage);
create index search_time on %%GLOBAL_TablePrefix%%search(currtime);
create index search_conv_time on %%GLOBAL_TablePrefix%%search(convtime);

create index campaign_site on %%GLOBAL_TablePrefix%%campaigns(campaignsite);
create index campaign_name on %%GLOBAL_TablePrefix%%campaigns(campaignname);
create index campaign_userid on %%GLOBAL_TablePrefix%%campaigns(userid);
create index campaign_cookieid on %%GLOBAL_TablePrefix%%campaigns(cookieid);
create index campaign_time on %%GLOBAL_TablePrefix%%campaigns(currtime);
create index campaign_conv_time on %%GLOBAL_TablePrefix%%campaigns(convtime);

create index session_time on %%GLOBAL_TablePrefix%%sessions(sessiontime);

create index loghistory_time on %%GLOBAL_TablePrefix%%loghistory(logtime);
