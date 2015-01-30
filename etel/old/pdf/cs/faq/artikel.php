<?php
/**
* $Id: artikel.php,v 1.17.2.21.2.9 2006/05/10 20:12:34 thorstenr Exp $
*
* Shows the page with the FAQ record and - when available - the user
* comments
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Lars Tiedemann <larstiedemann@yahoo.de>
* @since        2002-08-27
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

$currentCategory = $cat;

if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
	$id = (int)$_REQUEST['id'];
}
if (isset($_REQUEST['solution_id']) && is_numeric($_REQUEST['solution_id'])) {
	$solution_id = $_REQUEST['solution_id'];
} else {
    $solution_id = 0;
}

Tracking("article_view", $id);

$comment = '';
if (0 == $solution_id) {
    $query = sprintf("SELECT * FROM %sfaqdata WHERE id = %d AND lang = '%s'", SQLPREFIX, $id, $lang);
} else {
    $query = sprintf("SELECT * FROM %sfaqdata WHERE solution_id = %s", SQLPREFIX, $solution_id);
}
$result = $db->query($query);
if ($row = $db->fetch_object($result)) {
    if ('yes' == $row->active) {
    	$id            = $row->id;
    	$solution_id   = $row->solution_id;
    	$revision_id   = $row->revision_id;
    	$comment       = $row->comment;
    	$content       = $row->content;
    	$writeDateMsg  = makeDate($row->datum);
    	$writeAuthor   = $row->author;
        $categoryName  = $tree->getPath($currentCategory, ' &raquo; ', true);
    	logViews($id, $lang);
    } else {
        $id            = $row->id;
    	$solution_id   = '';
    	$revision_id   = '';
        $comment       = 'n';
        $content       = $PMF_LANG['err_inactiveArticle'];
        $writeDateMsg  = makeDate($row->datum);
        $writeAuthor   = $row->author;
        $categoryName  = $tree->getPath($currentCategory, ' &raquo; ', true);
    }
} else {
    $id            = 0;
    $solution_id   = '0000';
    $revision_id   = '0';
    $comment       = 'n';
    $content       = $PMF_LANG['err_inactiveArticle'];
    $writeDateMsg  = 'n/a';
    $writeAuthor   = 'n/a';
    $categoryName  = $tree->getPath($currentCategory, ' &raquo; ', true);
}

$printMsg = sprintf(
    '<a href="#" onclick="javascript:window.print();">%s</a>',
    $PMF_LANG['msgPrintArticle']);
$writePDF = sprintf(
    '<a target="_blank" href="pdf.php?cat=%d&amp;id=%d&amp;lang=%s">%s</a>',
    $currentCategory, $id, $lang, $PMF_LANG['msgPDF']);
$printS2F = sprintf(
    '<a href="?%saction=send2friend&amp;cat=%d&amp;id=%d&amp;artlang=%s">%s</a>',
    $sids, $currentCategory, $id, $lang, $PMF_LANG['msgSend2Friend']);
$writeXml = sprintf(
    '<a target="_blank" href="?%saction=xml&amp;id=%s&amp;artlang=%s">%s</a>',
    $sids, $id, $lang, $PMF_LANG['msgMakeXMLExport']);
$changeLang = sprintf(
    '?%saction=artikel&amp;cat=%d&amp;id=%d',
    $sids, $currentCategory, $id);
$writeComment = sprintf(
    '%s<a href="?%saction=writecomment&amp;id=%d&amp;artlang=%s">%s</a>',
    $PMF_LANG['msgYouCan'], $sids, $id, $lang, $PMF_LANG['msgWriteComment']);
$writeCategory = $categoryName."<br />\n";
$votingPath = sprintf('?%saction=savevoting', $sids);

if (isset($_GET['highlight']) && $_GET['highlight'] != "/" && $_GET['highlight'] != "<" && $_GET['highlight'] != ">" && strlen($_GET['highlight']) > 1) {
    $highlight = strip_tags($_GET['highlight']);
    $highlight = str_replace("'", "´", $highlight);
    $highlight = str_replace(array('^', '.', '?', '*', '+', '{', '}', '(', ')', '[', ']'), '', $highlight);
    $highlight = preg_quote($highlight, '/');
    $searchItems = explode(' ', $highlight);
    foreach ($searchItems as $item) {
        $content = preg_replace_callback(
            '/('.$item.
            '="[^"]*")|((href|src|title|alt|class|style|id|name)="[^"]*'.$item.
            '[^"]*")|('.$item.
            ')/mis', "highlight_no_links", $content);
    }
} else {
    $highlight = '';
}

$arrLanguage = check4Language($id);
$switchLanguage = "";
$check4Lang = "";
$num = count($arrLanguage);
if ($num > 1) {
	foreach ($arrLanguage as $language) {
		$check4Lang .= "<option value=\"".$language."\">".$languageCodes[strtoupper($language)]."</option>\n";
	}
	$switchLanguage .= "<p>\n";
    $switchLanguage .= "<fieldset>\n";
    $switchLanguage .= "<legend>".$PMF_LANG["msgLangaugeSubmit"]."</legend>\n";
	$switchLanguage .= "<form action=\"".$changeLang."\" method=\"post\" style=\"display: inline;\">\n";
	$switchLanguage .= "<select name=\"artlang\" size=\"1\">\n";
	$switchLanguage .= $check4Lang;
	$switchLanguage .= "</select>\n";
	$switchLanguage .= "&nbsp;\n";
	$switchLanguage .= "<input class=\"submit\" type=\"submit\" name=\"submit\" value=\"".$PMF_LANG["msgLangaugeSubmit"]."\" />\n";
    $switchLanguage .= "</fieldset>\n";
	$switchLanguage .= "</form>\n";
	$switchLanguage .= "</p>\n";
}

if (@is_dir('attachments/')  && @is_dir('attachments/'.$id) && isset($PMF_CONF['disatt'])) {
    $files = 0;
    $outstr = "";
    $dir = opendir('attachments/'.$id);
    while ($dat = readdir($dir)) {
        if ($dat != '.' && $dat != '..') {
            $files++;
            $outstr .= '<a href="attachments/'.$id.'/'.$dat.'" target="_blank">'.$dat.'</a>, ';
        }
    }
    if ($files > 0) {
        $content .= '<p>'.$PMF_LANG['msgAttachedFiles'].' '.substr($outstr, 0, -2).'</p>';
    }
}

$writeMultiCategories = '';
$multiCats = $tree->getCategoriesFromArticle($id);

if (count($multiCats) > 1) {
    $writeMultiCategories .= '        <div id="article_categories">';
    $writeMultiCategories .= '        <fieldset>';
    $writeMultiCategories .= '                <legend>'.$PMF_LANG['msgArticleCategories'].'</legend>';
    $writeMultiCategories .= '            <ul>';
    foreach ($multiCats as $multiCat) {
        $writeMultiCategories .= sprintf('<li><a href="%s?%saction=show&amp;cat=%d">%s</a></li>', $_SERVER['PHP_SELF'], $sids, $multiCat['id'], $multiCat['name']);
        $writeMultiCategories .= "\n";
    }
    $writeMultiCategories .= '            </ul>';
    $writeMultiCategories .= '        </fieldset>';
    $writeMultiCategories .= '    </div>';
}

$tpl->processTemplate ("writeContent", array(
				"writeRubrik" => $writeCategory,
				'solution_id' => $solution_id,
				"writeThema" => preg_replace_callback('/('.$highlight.'="[^"]*")|((href|src|title|alt|class|style|id|name)="[^"]*'.$highlight.'[^"]*")|('.$highlight.')/mis', "highlight_no_links", getThema($id, $lang)),
				'writeArticleCategories' => $writeMultiCategories,
				"writeContent" => preg_replace_callback("/<code([^>]*)>(.*?)<\/code>/is", 'hilight', $content),
				"writeDateMsg" => $PMF_LANG["msgLastUpdateArticle"].$writeDateMsg,
				'writeRevision' => $PMF_LANG['ad_entry_revision'].': 1.'.$revision_id,
				"writeAuthor" => $PMF_LANG["msgAuthor"].$writeAuthor,
				"writePrintMsg" => $printMsg,
				"writePDF" => $writePDF,
				"writeSend2FriendMsg" => $printS2F,
				"writeXMLMsg" => $writeXml,
				"writePrintMsgTag" => $PMF_LANG["msgPrintArticle"],
				"writePDFTag" => $PMF_LANG["msgPDF"],
				"writeSend2FriendMsgTag" => $PMF_LANG["msgSend2Friend"],
				"writeXMLMsgTag" => $PMF_LANG["msgMakeXMLExport"],
				"saveVotingPATH" => $votingPath,
				"saveVotingID" => $id,
				"saveVotingIP" => $_SERVER["REMOTE_ADDR"],
				"msgAverageVote" => $PMF_LANG["msgAverageVote"],
				"printVotings" => generateVoting($id),
				"switchLanguage" => $switchLanguage,
				"msgVoteUseability" => $PMF_LANG["msgVoteUseability"],
				"msgVoteBad" => $PMF_LANG["msgVoteBad"],
				"msgVoteGood" => $PMF_LANG["msgVoteGood"],
				"msgVoteSubmit" => $PMF_LANG["msgVoteSubmit"],
				"writeCommentMsg" => ($comment == 'n') ? $PMF_LANG['msgWriteNoComment'] : $writeComment,
				"writeComments" => generateComments($id)
				));

$tpl->includeTemplate("writeContent", "index");
