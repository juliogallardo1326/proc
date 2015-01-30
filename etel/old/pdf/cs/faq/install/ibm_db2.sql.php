<?php
/**
* $Id: ibm_db2.sql.php,v 1.1.2.3 2006/05/08 06:46:06 thorstenr Exp $
*
* CREATE TABLE instruction for IBM DB2 Universal Database, IBM Cloudscape,
* and Apache Derby databases
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2005-07-31
* @copyright    (c) 2006 phpMyFAQ Team
*
* The contents of this file are subject to the Mozilla Public License
* Version 1.1 (the "License"); you may not use this file except in
* compliance with the License. You may obtain a copy of the License at
* http://www.mozilla.org/MPL/
*
* Software distributed under the License is distributed on an "AS IS"
* basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
* License for the specific language governing rights and limitations
* under the License.
*/

$uninst[] = "DROP TABLE ".$sqltblpre."faqadminlog";
$uninst[] = "DROP TABLE ".$sqltblpre."faqadminsessions";
$uninst[] = "DROP TABLE ".$sqltblpre."faqcategories";
$uninst[] = "DROP TABLE ".$sqltblpre."faqcategoryrelations";
$uninst[] = "DROP TABLE ".$sqltblpre."faqcaptcha";
$uninst[] = "DROP TABLE ".$sqltblpre."faqchanges";
$uninst[] = "DROP TABLE ".$sqltblpre."faqcomments";
$uninst[] = "DROP TABLE ".$sqltblpre."faqdata";
$uninst[] = "DROP TABLE ".$sqltblpre."faqdata_revisions";
$uninst[] = "DROP TABLE ".$sqltblpre."faqfragen";
$uninst[] = "DROP TABLE ".$sqltblpre."faqnews";
$uninst[] = "DROP TABLE ".$sqltblpre."faqvoting";
$uninst[] = "DROP TABLE ".$sqltblpre."faqsessions";
$uninst[] = "DROP TABLE ".$sqltblpre."faquser";
$uninst[] = "DROP TABLE ".$sqltblpre."faqvisits";

//faquser
$query[] = "CREATE TABLE  ".$sqltblpre."faquser (
id integer NOT NULL,
name varchar(64) NOT NULL,
pass varchar(64)  NOT NULL,
realname varchar(255) DEFAULT '',
email varchar(255) DEFAULT '',
rights varchar(255) NOT NULL,
PRIMARY KEY (id))";

//faqcaptcha
$query[] = "CREATE TABLE ".$sqltblpre."faqcaptcha (
id varchar(6) NOT NULL,
useragent varchar(255) NOT NULL,
language varchar(2) NOT NULL,
ip varchar(64) NOT NULL,
captcha_time integer NOT NULL,
PRIMARY KEY (id))";

//faqdata
$query[] = "CREATE TABLE  ".$sqltblpre."faqdata (
id integer NOT NULL,
lang varchar(5) NOT NULL,
solution_id integer NOT NULL,
revision_id integer NOT NULL DEFAULT 0,
active char(3) NOT NULL,
keywords CLOB NOT NULL,
thema CLOB NOT NULL,
content CLOB NOT NULL,
author varchar(255) NOT NULL,
email varchar(255) NOT NULL,
comment char(1) default 'y',
datum varchar(15) NOT NULL,
PRIMARY KEY (id, lang))";

//faqdata_revisions
$query[] = "CREATE TABLE  ".$sqltblpre."faqdata_revisions (
id integer NOT NULL,
lang varchar(5) NOT NULL,
solution_id integer NOT NULL,
revision_id integer NOT NULL DEFAULT 0,
active char(3) NOT NULL,
keywords CLOB NOT NULL,
thema CLOB NOT NULL,
content CLOB NOT NULL,
author varchar(255) NOT NULL,
email varchar(255) NOT NULL,
comment char(1) default 'y',
datum varchar(15) NOT NULL,
PRIMARY KEY (id, lang, solution_id, revision_id))";

//faqadminlog
$query[] = "CREATE TABLE ".$sqltblpre."faqadminlog (
id integer NOT NULL,
time integer NOT NULL,
usr varchar(255) NOT NULL,
text varchar(512) NOT NULL,
ip varchar(64) NOT NULL,
PRIMARY KEY (id))";

//faqadminsessions
$query[] = "CREATE TABLE  ".$sqltblpre."faqadminsessions (
uin varchar(50)  NOT NULL,
usr varchar(128) NOT NULL,
pass varchar(64)  NOT NULL,
ip varchar(64) NOT NULL,
time integer NOT NULL)";

//faqcategories
$query[] = "CREATE TABLE  ".$sqltblpre."faqcategories (
id integer NOT NULL,
lang varchar(5) NOT NULL,
parent_id INTEGER NOT NULL,
name varchar(255) NOT NULL,
description varchar(255) NOT NULL ,
PRIMARY KEY (id, lang))";

//faqcategoryrelations
$query[] = "CREATE TABLE ".$sqltblpre."faqcategoryrelations (
category_id INTEGER NOT NULL,
category_lang VARCHAR(5) NOT NULL,
record_id INTEGER NOT NULL,
record_lang VARCHAR(5) NOT NULL,
PRIMARY KEY  (category_id,category_lang,record_id,record_lang)
)";

//faqchanges
$query[] = "CREATE TABLE  ".$sqltblpre."faqchanges (
id integer NOT NULL,
beitrag INTEGER NOT NULL,
lang varchar(5) NOT NULL,
revision_id integer NOT NULL DEFAULT 0,
usr varchar(255) NOT NULL,
datum INTEGER NOT NULL,
what varchar(512) NOT NULL,
PRIMARY KEY (id, lang))";

//faqcomments
$query[] = "CREATE TABLE  ".$sqltblpre."faqcomments (
id_comment integer NOT NULL,
id integer NOT NULL,
usr varchar(255) NOT NULL,
email varchar(255) NOT NULL,
comment CLOB NOT NULL,
datum varchar(64) NOT NULL,
helped CLOB NOT NULL,
PRIMARY KEY (id_comment))";

//faqfragen
$query[] = "CREATE TABLE  ".$sqltblpre."faqfragen (
id integer NOT NULL,
ask_username varchar(100) NOT NULL,
ask_usermail varchar(100) NOT NULL,
ask_rubrik integer NOT NULL,
ask_content CLOB NOT NULL,
ask_date varchar(20) NOT NULL,
PRIMARY KEY (id))";

//faqnews
$query[] = "CREATE TABLE  ".$sqltblpre."faqnews (
id integer NOT NULL,
header varchar(255) NOT NULL,
artikel CLOB NOT NULL,
datum varchar(14) NOT NULL,
link varchar(255) NOT NULL,
linktitel varchar(255) NOT NULL,
target varchar(255) NOT NULL,
PRIMARY KEY (id))";

//faqvoting
$query[] = "CREATE TABLE  ".$sqltblpre."faqvoting (
id integer NOT NULL,
artikel INTEGER NOT NULL,
vote INTEGER NOT NULL,
usr INTEGER NOT NULL,
datum varchar(20) DEFAULT '',
ip varchar(15) DEFAULT '',
PRIMARY KEY (id))";

//faqsessions
$query[] = "CREATE TABLE  ".$sqltblpre."faqsessions (
sid integer NOT NULL,
ip varchar(64) NOT NULL,
time integer NOT NULL,
PRIMARY KEY (sid))";

//faqvisits
$query[] = "CREATE TABLE  ".$sqltblpre."faqvisits (
id integer NOT NULL,
lang varchar(5) NOT NULL,
visits INTEGER NOT NULL,
last_visit INTEGER NOT NULL,
PRIMARY KEY (id, lang))";

$query[] = "INSERT INTO ".$sqltblpre."faquser (id, name, pass, realname, email, rights) VALUES (1, 'admin', '".md5($password)."', '".$realname."', '".$email."', '11111111111111111111111')";
