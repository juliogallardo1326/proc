<?php
/**
* $Id: xmlrpc.php,v 1.3.2.4 2006/01/02 12:47:09 thorstenr Exp $
*
* This is the XML-RPC interface
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2004-05-14
* @copyright    (c) 2004 - 2006 phpMyFAQ Team
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

require_once('inc/functions.php');
require_once('inc/init.php');
define('IS_VALID_PHPMYFAQ', null);
PMF_Init::cleanRequest();

// Just for security reasons - thanks to Johannes for the hint
$_SERVER['PHP_SELF'] = str_replace('%2F', '/', rawurlencode($_SERVER['PHP_SELF']));
$_SERVER['HTTP_USER_AGENT'] = urlencode($_SERVER['HTTP_USER_AGENT']);

/* connect to the database server */
require_once("inc/data.php");
require_once("inc/db.php");
define("SQLPREFIX", $DB["prefix"]);
$db = db::db_select($DB["type"]);
$db->connect($DB["server"], $DB["user"], $DB["password"], $DB["db"]);

/* get configuration, constants, main functions, category class, XML-RPC classes */
require_once("inc/config.php");
require_once("inc/category.php");
include_once("inc/xmlrpc.php");
include_once("inc/xmlrpcs.php");

$tree = new Category();

function search($begriff)
{
	global $db, $tree, $PMF_LANG, $PMF_CONF;
	$output = "";
		$result = $db->search(SQLPREFIX."faqdata",
                          array(SQLPREFIX."faqdata.id AS id",
                                SQLPREFIX."faqdata.lang AS lang",
                                SQLPREFIX."faqcategoryrelations.category_id AS category_id",
                                SQLPREFIX."faqdata.thema AS thema",
                                SQLPREFIX."faqdata.content AS content"),
                          SQLPREFIX."faqcategoryrelations",
                          array(SQLPREFIX."faqdata.id = ".SQLPREFIX."faqcategoryrelations.record_id",
                                SQLPREFIX."faqdata.lang = ".SQLPREFIX."faqcategoryrelations.record_lang"),
                          array(SQLPREFIX."faqdata.thema",
                                SQLPREFIX."faqdata.content",
                                SQLPREFIX."faqdata.keywords"),
                          $begriff,
                          array(SQLPREFIX."faqdata.active"=>"yes"));
	if ($db->num_rows($result) > 0) {
        $output .= $num.$PMF_LANG["msgSearchAmounts"]."\n";
	    while ($row = $db->fetch_object($result)) {
			$rubriktext = $tree->categoryName[$row->rubrik]["name"];
			$thema = chopString($row->thema, 15);
            $output .= htmlentities($rubriktext).";".htmlentities($row->thema).";"."http://".$_SERVER['SERVER_NAME'].str_replace ("xmlrpc.php", "index.php", $_SERVER['PHP_SELF'])."?action=artikel&cat=".$row->category_id."&id=".$row->id."&artlang=".$row->lang."\n";
            }
        }
    else {
		$output = "No Articles found";
		}
    
	return $output;
}

function PMFSearch($params)
{
    $param = $params->getParam(0);
    if (isset($param)) {
        if ($param->scalartyp() == "string") {
            $searchString = $param->scalarval();
            $ret = new xmlrpcval(search($searchString), "string");
            return new xmlrpcresp($ret);
        }
        else {
            global $xmlrpcerruser;
            $ret = new xmlrpcresp(0, $xmlrpcerruser, "Wrong Parameter!");
            return $ret;
            }
        }
    else {
        global $xmlrpcerruser;
        $ret = new xmlrpcresp(0, $xmlrpcerruser, "No data received from client!");
        return $ret;
        }
}

$xmlrpc = new xmlrpc_server(array("phpmyfaq.PMFSearch" => array("function" => "PMFSearch")));

$db->dbclose();
