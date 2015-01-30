CREATE TABLE %%GLOBAL_TablePrefix%%users (
  userid int(11) NOT NULL primary key,
  username varchar(255) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  status char(1) default '0',
  admin char(1) default '0',
  fullname varchar(255) default NULL,
  emailaddress varchar(255) default NULL,
  quickstart char(1) default 1,
  settings text,
  usertimezone varchar(255),
  ignoreips text,
  ignoresites text,
  ignorekeywords text
) TYPE=MyISAM;

CREATE TABLE %%GLOBAL_TablePrefix%%users_sequence (
	id int not null auto_increment primary key
);

INSERT INTO %%GLOBAL_TablePrefix%%users_sequence VALUES(1);
INSERT INTO %%GLOBAL_TablePrefix%%users (userid, username, password, status, admin, quickstart, usertimezone) VALUES (1,'admin','5f4dcc3b5aa765d61d8327deb882cf99','1','1','1', 'GMT');

create table %%GLOBAL_TablePrefix%%cookies (
	sessionid char(32),
	cookieid varchar(255),
	cookietype varchar(10),
	cookiefrom varchar(255),
	cookiedetails varchar(255),
	remove char(1) default '0',
	cookietime int DEFAULT 0
);

create table %%GLOBAL_TablePrefix%%referrers (
	referrerid int not null primary key,
	domain varchar(255),
	url varchar(255),
	currtime int DEFAULT 0,
	convtime int DEFAULT 0,
	ip varchar(20),
	landingpage varchar(255),
	cookieid varchar(255),
	userid int,
	hasconversion int default '0',
	amount float DEFAULT 0
);

create table %%GLOBAL_TablePrefix%%referrers_sequence (
	id int not null auto_increment primary key
);
insert into %%GLOBAL_TablePrefix%%referrers_sequence values(0);

create table %%GLOBAL_TablePrefix%%search (
	searchid int not null primary key,
	searchenginename varchar(255),
	keywords varchar(255),
	currtime int DEFAULT 0,
	convtime int DEFAULT 0,
	ip varchar(20),
	landingpage varchar(255),
	cookieid varchar(255),
	userid int,
	hasconversion int default '0',
	amount float DEFAULT 0
);

create table %%GLOBAL_TablePrefix%%search_sequence (
	id int not null auto_increment primary key
);
insert into %%GLOBAL_TablePrefix%%search_sequence values(0);

create table %%GLOBAL_TablePrefix%%campaigns (
  campaignid int not null primary key,
  campaignsite varchar(255),
  campaignname varchar(255),
  cost float DEFAULT 0,
  period int DEFAULT 0,
  startdate int DEFAULT 0,
  hasconversion int default '0',
  amount float DEFAULT 0,
  currtime int,
  convtime int,
  userid int,
  ip varchar(20),
  cookieid varchar(255)
);
create table %%GLOBAL_TablePrefix%%campaigns_sequence (
	id int not null auto_increment primary key
);
insert into %%GLOBAL_TablePrefix%%campaigns_sequence values(0);

create table %%GLOBAL_TablePrefix%%payperclicks (
  ppcid int not null primary key,
  searchenginename varchar(255),
  ppcname varchar(255),
  cost float DEFAULT 0,
  hasconversion int default '0',
  amount float DEFAULT 0,
  currtime int DEFAULT 0,
  convtime int DEFAULT 0,
  userid int,
  ip varchar(20),
  cookieid varchar(255)
);
create table %%GLOBAL_TablePrefix%%payperclicks_sequence (
	id int not null auto_increment primary key
);
insert into %%GLOBAL_TablePrefix%%payperclicks_sequence values(0);

create table %%GLOBAL_TablePrefix%%conversions (
	conversionid int not null primary key,
	type varchar(10),
	name varchar(255),
	amount float DEFAULT 0,
	cookieid varchar(255),
	sessionid char(32),
	currtime int DEFAULT 0,
	ip varchar(20),
	origintype varchar(20),
	originfrom varchar(255),
	origindetails varchar(255),
	userid int
);
create table %%GLOBAL_TablePrefix%%conversions_sequence (
	id int not null auto_increment primary key
);
insert into %%GLOBAL_TablePrefix%%conversions_sequence values(1);


CREATE TABLE %%GLOBAL_TablePrefix%%sessions (
	sessionid varchar(32) NOT NULL default '',
	sessiontime int(11) default NULL,
	sessionstart int(11) default NULL,
	sessiondata text,
	PRIMARY KEY  (sessionid)
);

CREATE TABLE %%GLOBAL_TablePrefix%%loghistory_sequence (
	id int not null auto_increment primary key
);
INSERT INTO %%GLOBAL_TablePrefix%%loghistory_sequence VALUES(1);

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
