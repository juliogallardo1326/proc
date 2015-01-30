<?php
/**
* $Id: xml.php,v 1.3.2.5 2006/01/02 12:47:09 thorstenr Exp $
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2002-08-29
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

if (!defined('IS_VALID_PHPMYFAQ')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

Tracking("create_xml", $_GET["id"]);

if (isset($_GET["id"]) && is_numeric($_GET["id"]) == true) {
	$id = (int)$_GET["id"];
}
if (isset($_GET["lang"])) {
	$lang = $_GET["lang"];
}
if (generateXMLExport($id, $lang) == true) {
    header("Location: ./xml/article_".$id."_".$lang.".xml");
    header("Content-Type: text/xml");
    header("Content-Length: ".filesize("./xml/article_".$id."_".$lang.".xml"));
}
?>