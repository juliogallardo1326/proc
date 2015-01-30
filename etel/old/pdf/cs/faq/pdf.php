<?php
/**
* $Id: pdf.php,v 1.13.2.9.2.2 2006/04/08 20:42:31 matteo Exp $
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Peter Beauvain <pbeauvain@web.de>
* @author       Olivier Plathey <olivier@fpdf.org>
* @author       Krzysztof Kruszynski <thywolf@wolf.homelinux.net>
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
* @since        2003-02-12
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

require_once('inc/functions.php');
require_once('inc/init.php');
PMF_Init::cleanRequest();
require_once('inc/data.php');
require_once('inc/db.php');
require_once('inc/config.php');
require_once('inc/category.php');
require_once('inc/pdf.php');

define("SQLPREFIX", $DB["prefix"]);
$db = db::db_select($DB["type"]);
$db->connect($DB["server"], $DB["user"], $DB["password"], $DB["db"]);
$tree = new Category;

// get language (default: english)
$pmf = new PMF_Init();
$LANGCODE = $pmf->setLanguage((isset($PMF_CONF['detection']) ? true : false), $PMF_CONF['language']);

if (isset($LANGCODE) && PMF_Init::isASupportedLanguage($LANGCODE)) {
    require_once("lang/language_".$LANGCODE.".php");
} else {
    $LANGCODE = "en";
    require_once ("lang/language_en.php");
}

if (isset($_GET['cat']) && is_numeric($_GET['cat']) == true) {
    $currentCategory = $_REQUEST['cat'];
}
if (isset($_GET["id"]) && is_numeric($_GET["id"]) == true) {
    $id = $_GET["id"];
    }
if (isset($_GET["lang"]) && PMF_Init::isASupportedLanguage($_GET["lang"])) {
    $lang = $_GET["lang"];
    }

$result = $db->query("SELECT id, lang, solution_id, thema, content, datum, author FROM ".SQLPREFIX."faqdata WHERE id = ".$id." AND lang = '".$lang."' AND active = 'yes'");
if ($db->num_rows($result) > 0) {
    while ($row = $db->fetch_object($result)) {
        $lang = $row->lang;
        $solution_id = $row->solution_id;
        $thema = $row->thema;
        $content = $row->content;
        $date = $row->datum;
        $author = $row->author;
    }
} else {
    print "Error!";
}

$pdf = new PDF($currentCategory, $thema, $tree->categoryName, $orientation = "P", $unit = "mm", $format = "A4");
$pdf->Open();
$pdf->SetAutoPageBreak(true, 2*(40/$pdf->k));
$pdf->SetTitle($thema);
$pdf->SetCreator($PMF_CONF["title"]);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont("Arial", "", 12);
$pdf->SetDisplayMode("real");
$pdf->WriteHTML(str_replace("../", "", $content));
$pdf->Ln();
$pdf->Ln();
$pdf->SetStyle('I', true);
$pdf->Write(5, unhtmlentities($PMF_LANG['ad_entry_solution_id']).': #'.$solution_id);
$pdf->SetAuthor($author);
$pdf->Ln();
$pdf->Write(5, unhtmlentities($PMF_LANG["msgAuthor"]).$author);
$pdf->SetAuthor($author);
$pdf->Ln();
$pdf->Write(5, unhtmlentities($PMF_LANG["msgLastUpdateArticle"]).makeDate($date));
$pdf->SetStyle('I', false);

$pdfFile = "pdf/".$id.".pdf";
$pdf->Output($pdfFile);

$file = basename($pdfFile);
$size = filesize($pdfFile);
session_cache_limiter('private');
header("Pragma: public");
header("Expires: 0"); // set expiration time
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

if (preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"])) {
    header("Content-type: application/pdf");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".filesize($pdfFile));
    header("Content-Disposition: Attachment; filename=".$id.".pdf" );
    readfile($pdfFile);
} else {
    header("Location: ".$pdfFile."");
    header("Content-Type: application/pdf");
    header("Content-Length: ".filesize($pdfFile));
    readfile($pdfFile);
}
?>