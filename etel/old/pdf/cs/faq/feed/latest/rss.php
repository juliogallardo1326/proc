<?php
/**
* $Id: rss.php,v 1.9.2.13.2.1 2006/03/15 20:34:57 thorstenr Exp $
*
* The RSS feed with the latest five records
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

$query = 'SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqdata.content AS content, '.SQLPREFIX.'faqdata.datum AS datum, '.SQLPREFIX.'faqvisits.visits AS visits FROM '.SQLPREFIX.'faqvisits, '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang WHERE '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqvisits.id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqvisits.lang AND '.SQLPREFIX.'faqdata.active = \'yes\' ORDER BY '.SQLPREFIX.'faqdata.datum DESC';
$result = $db->query($query);

$rss = "<?xml version=\"1.0\" encoding=\"".$PMF_LANG["metaCharset"]."\" standalone=\"yes\" ?>\n<rss version=\"2.0\">\n<channel>\n";
$rss .= "<title>".htmlspecialchars($PMF_CONF["title"])." - ".htmlspecialchars($PMF_LANG['msgLatestArticles'])."</title>\n";
$rss .= "<description>".htmlspecialchars($PMF_CONF["metaDescription"])."</description>\n";
$rss .= "<link>http".(isset($_SERVER['HTTPS']) ? 's' : '')."://".$_SERVER["HTTP_HOST"].str_replace ("/feed/latest/rss.php", "", $_SERVER["PHP_SELF"])."</link>";
if ($num = $db->num_rows($result) > 0) {
    
    $counter = 0;
    $oldId = 0;
    while (($row = $db->fetch_object($result)) && $counter < 5) {
        $counter++;
        if ($oldId != $row->id) {
            $rss .= "\t<item>\n";
            $rss .= "\t\t<title><![CDATA[".makeShorterText($row->thema, 8)."]]></title>\n";
            // Get the content
            $content = $row->content;
            // Fix the content internal image references
            $content = str_replace("<img src=\"/", "<img src=\"http".(isset($_SERVER["HTTPS"]) ? "s" : "")."://".$_SERVER["HTTP_HOST"]."/", $content);
            $rss .= "\t\t<description><![CDATA[<p><b>".$row->thema."</b> <em>(".$row->visits." ".$PMF_LANG["msgViews"].")</em></p>".$content."]]></description>\n";
            $rss .= "\t\t<link>http".(isset($_SERVER['HTTPS']) ? 's' : '')."://".$_SERVER["HTTP_HOST"].str_replace("feed/latest/rss.php", "index.php", $_SERVER["PHP_SELF"])."?action=artikel&amp;cat=".$row->category_id."&amp;id=".$row->id."&amp;artlang=".$row->lang."</link>\n";
            $rss .= "\t\t<pubDate>".makeRFC822Date($row->datum)."</pubDate>\n";
            $rss .= "\t</item>\n";
		}
		$oldId = $row->id;
    }
}

$rss .= "</channel>\n</rss>";

header("Content-Type: text/xml");
header("Content-Length: ".strlen($rss));
print $rss;

$db->dbclose();