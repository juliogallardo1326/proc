<?php
/**
* $Id: rss.php,v 1.7.2.11 2006/01/02 12:47:12 thorstenr Exp $
*
* The RSS feed with the news
*
* @package      phpMyFAQ
* @access       public
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
* @copyright    (c) 2004-2006 phpMyFAQ Team
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

define('PMF_ROOT_DIR', dirname(dirname(dirname(__FILE__))));
require_once(PMF_ROOT_DIR.'/inc/functions.php');
require_once(PMF_ROOT_DIR.'/inc/init.php');
PMF_Init::cleanRequest();

/* read configuration, include classes and functions */
require_once (PMF_ROOT_DIR."/inc/data.php");
require_once (PMF_ROOT_DIR."/inc/db.php");
define("SQLPREFIX", $DB["prefix"]);
$db = db::db_select($DB["type"]);
$db->connect($DB["server"], $DB["user"], $DB["password"], $DB["db"]);
require_once (PMF_ROOT_DIR."/inc/config.php");
require_once (PMF_ROOT_DIR."/inc/constants.php");
require_once (PMF_ROOT_DIR."/inc/category.php");
require_once (PMF_ROOT_DIR."/lang/".$PMF_CONF["language"]);

$result = $db->query("SELECT id, datum, header, artikel, link, linktitel, target FROM ".SQLPREFIX."faqnews ORDER BY datum desc");

$rss = "<?xml version=\"1.0\" encoding=\"".$PMF_LANG["metaCharset"]."\" standalone=\"yes\" ?>\n<rss version=\"2.0\">\n<channel>\n";
$rss .= "<title>".htmlspecialchars($PMF_CONF["title"])." - ".htmlspecialchars($PMF_LANG['msgNews'])."</title>\n";
$rss .= "<description>".htmlspecialchars($PMF_CONF["metaDescription"])."</description>\n";
$rss .= "<link>http".(isset($_SERVER['HTTPS']) ? 's' : '')."://".$_SERVER["HTTP_HOST"].str_replace ("/feed/news/rss.php", "", $_SERVER["PHP_SELF"])."</link>";

if ($db->num_rows($result) > 0) {
    while ($row = $db->fetch_object($result)) {
        $rss .= "\t<item>\n";
        $rss .= "\t\t<title><![CDATA[".$row->header."]]></title>\n";
        $rss .= "\t\t<description><![CDATA[".$row->artikel."]]></description>\n";
        $rss .= "\t\t<link>http".(isset($_SERVER['HTTPS']) ? 's' : '')."://".$_SERVER["HTTP_HOST"].str_replace ("/feed/news/rss.php", "", $_SERVER["PHP_SELF"])."#news_".$row->id."</link>\n";
        $rss .= "\t\t<pubDate>".makeRFC822Date($row->datum)."</pubDate>\n";
        $rss .= "\t</item>\n";
    }
}

$rss .= "</channel>\n</rss>";

header("Content-Type: text/xml");
header("Content-Length: ".strlen($rss));
print $rss;

$db->dbclose();