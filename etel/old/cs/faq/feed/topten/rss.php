<?php
/**
* $Id: rss.php,v 1.9.2.12.2.1 2006/03/15 20:34:57 thorstenr Exp $
*
* The RSS feed with the top ten
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

$query = 'SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqvisits.visits AS visits, '.SQLPREFIX.'faqdata.datum AS datum, '.SQLPREFIX.'faqvisits.last_visit AS last_visit FROM '.SQLPREFIX.'faqvisits, '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang WHERE '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqvisits.id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqvisits.lang AND '.SQLPREFIX.'faqdata.active = \'yes\' ORDER BY '.SQLPREFIX.'faqvisits.visits DESC';
    
$result = $db->query($query);

$rss = "<?xml version=\"1.0\" encoding=\"".$PMF_LANG["metaCharset"]."\" standalone=\"yes\" ?>\n<rss version=\"2.0\">\n<channel>\n";
$rss .= "<title>".htmlspecialchars($PMF_CONF["title"])." - ".htmlspecialchars($PMF_LANG["msgTopTen"])."</title>\n";
$rss .= "<description>".htmlspecialchars($PMF_CONF["metaDescription"])."</description>\n";
$rss .= "<link>http".(isset($_SERVER['HTTPS']) ? 's' : '')."://".$_SERVER["HTTP_HOST"].str_replace ("/feed/topten/rss.php", "", $_SERVER["PHP_SELF"])."</link>";

if ($db->num_rows($result) > 0) {
    
    $i = 1;
    $counter = 0;
    $oldId = 0;
    while ( ($row = $db->fetch_object($result)) && $counter < 10) {
        $counter++;
        if ($oldId != $row->id) {
            $rss .= "\t<item>\n";
            $rss .= "\t\t<title><![CDATA[".makeShorterText($row->thema, 8)." (".$row->visits." ".$PMF_LANG["msgViews"].")]]></title>\n";
            $rss .= "\t\t<description><![CDATA[[".$i.".] ".$row->thema." (".$row->visits." ".$PMF_LANG["msgViews"].")]]></description>\n";
            $rss .= "\t\t<link>http".(isset($_SERVER['HTTPS']) ? 's' : '')."://".$_SERVER["HTTP_HOST"].str_replace ("feed/topten/rss.php", "index.php", $_SERVER["PHP_SELF"])."?action=artikel&amp;cat=".$row->category_id."&amp;id=".$row->id."&amp;artlang=".$row->lang."</link>\n";
            $rss .= "\t\t<!-- The real FAQ publication date -->\n";
            // $$row->datum is a phpMyFAQ date
            $rss .= "\t\t<!-- ".makeRFC822Date($row->datum)." -->\n";
            // $row->last_visit is a mktime timpestamp date
            $rss .= "\t\t<pubDate>".makeRFC822Date($row->last_visit, false)."</pubDate>\n";
            $rss .= "\t</item>\n";
            $i++;
        }
		$oldId = $row->id;
    }
}

$rss .= "</channel>\n</rss>";

header("Content-Type: text/xml");
header("Content-Length: ".strlen($rss));
print $rss;

$db->dbclose();