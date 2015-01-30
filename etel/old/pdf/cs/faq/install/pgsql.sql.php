<?php
/**
* $Id: pgsql.sql.php,v 1.6.2.5.2.7 2006/03/19 09:18:06 thorstenr Exp $
*
* CREATE TABLE instruction for PostgreSQL database
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Tom Rochester <tom.rochester@gmail.com>
* @since        2004-09-18
* @copyright    (c) 2001-2006 phpMyFAQ Team
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
id SERIAL NOT NULL,
name text NOT NULL,
pass varchar(64)  NOT NULL,
realname varchar(255) NOT NULL default '',
email varchar(255) NOT NULL default '',
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
id SERIAL NOT NULL,
lang varchar(5) NOT NULL,
solution_id bigint NOT NULL,
revision_id bigint NOT NULL DEFAULT 0,
active char(3) NOT NULL,
keywords text NOT NULL,
thema text NOT NULL,
content text NOT NULL,
author varchar(255) NOT NULL,
email varchar(255) NOT NULL,
comment char(1) NOT NULL default 'y',
datum varchar(15) NOT NULL,
PRIMARY KEY (id, lang))";

//faqdata_revisions
$query[] = "CREATE TABLE  ".$sqltblpre."faqdata_revisions (
id bigint NOT NULL,
lang varchar(5) NOT NULL,
solution_id bigint NOT NULL,
revision_id bigint NOT NULL DEFAULT 0,
active char(3) NOT NULL,
keywords text NOT NULL,
thema text NOT NULL,
content text NOT NULL,
author varchar(255) NOT NULL,
email varchar(255) NOT NULL,
comment char(1) NOT NULL default 'y',
datum varchar(15) NOT NULL,
PRIMARY KEY (id, lang, solution_id, revision_id))";

//faqadminlog
$query[] = "CREATE TABLE ".$sqltblpre."faqadminlog (
id SERIAL NOT NULL,
time bigint NOT NULL,
usr bigint NOT NULL REFERENCES ".$sqltblpre."faquser(id),
text text NOT NULL,
ip text NOT NULL,
PRIMARY KEY (id))";

//faqadminsessions
$query[] = "CREATE TABLE  ".$sqltblpre."faqadminsessions (
uin varchar(50)  NOT NULL,
usr text NOT NULL,
pass varchar(64)  NOT NULL,
ip text NOT NULL,
time bigint NOT NULL)";

//faqcategories
$query[] = "CREATE TABLE  ".$sqltblpre."faqcategories (
id SERIAL NOT NULL,
lang varchar(5) NOT NULL,
parent_id bigint NOT NULL,
name varchar(255) NOT NULL,
description varchar(255) NOT NULL ,
PRIMARY KEY (id, lang))";

//faqcategoryrelations
$query[] = "CREATE TABLE ".$sqltblpre."faqcategoryrelations (
category_id SERIAL NOT NULL,
category_lang VARCHAR(5) NOT NULL,
record_id bigint NOT NULL,
record_lang VARCHAR(5) NOT NULL,
PRIMARY KEY  (category_id,category_lang,record_id,record_lang)
)";

//faqchanges
$query[] = "CREATE TABLE  ".$sqltblpre."faqchanges (
id SERIAL NOT NULL,
beitrag bigint NOT NULL,
lang varchar(5) NOT NULL,
revision_id integer NOT NULL DEFAULT 0,
usr bigint NOT NULL REFERENCES ".$sqltblpre."faquser(id),
datum bigint NOT NULL,
what text NOT NULL,
PRIMARY KEY (id, lang))";

//faqcomments
$query[] = "CREATE TABLE  ".$sqltblpre."faqcomments (
id_comment SERIAL NOT NULL,
id bigint NOT NULL,
usr varchar(255) NOT NULL,
email varchar(255) NOT NULL,
comment text NOT NULL,
datum bigint NOT NULL,
helped text NOT NULL,
PRIMARY KEY (id_comment))";

//faqfragen
$query[] = "CREATE TABLE  ".$sqltblpre."faqfragen (
id SERIAL NOT NULL,
ask_username varchar(100) NOT NULL,
ask_usermail varchar(100) NOT NULL,
ask_rubrik varchar(100) NOT NULL,
ask_content text NOT NULL,
ask_date varchar(20) NOT NULL,
PRIMARY KEY (id))";

//faqnews
$query[] = "CREATE TABLE  ".$sqltblpre."faqnews (
id SERIAL NOT NULL,
header varchar(255) NOT NULL,
artikel text NOT NULL,
datum varchar(14) NOT NULL,
link varchar(255) NOT NULL,
linktitel varchar(255) NOT NULL,
target varchar(255) NOT NULL,
PRIMARY KEY (id))";

//faqvoting
$query[] = "CREATE TABLE  ".$sqltblpre."faqvoting (
id SERIAL NOT NULL,
artikel bigint NOT NULL,
vote bigint NOT NULL,
usr bigint NOT NULL REFERENCES ".$sqltblpre."faquser(id),
datum varchar(20) NOT NULL default '',
ip varchar(15) NOT NULL default '',
PRIMARY KEY (id))";

//faqsessions
$query[] = "CREATE TABLE  ".$sqltblpre."faqsessions (
sid SERIAL NOT NULL,
ip text NOT NULL,
time bigint NOT NULL,
PRIMARY KEY (sid))";

//faqvisits
$query[] = "CREATE TABLE  ".$sqltblpre."faqvisits (
id SERIAL NOT NULL,
lang varchar(5) NOT NULL,
visits bigint NOT NULL,
last_visit int8 NOT NULL,
PRIMARY KEY (id, lang))";

$query[] = "INSERT INTO ".$sqltblpre."faquser (id, name, pass, realname, email, rights) VALUES (1, 'admin', '".md5($password)."', '".$realname."', '".$email."', '11111111111111111111111')";