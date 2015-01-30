<?php
/**
* $Id: record.save.php,v 1.23.2.11.2.6 2006/04/28 14:18:24 thorstenr Exp $
*
* Save or update a FAQ record
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-02-23
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

if (!defined('IS_VALID_PHPMYFAQ_ADMIN')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

$submit = $_POST["submit"];

if (isset($submit[2]) && isset($_POST["thema"]) && $_POST["thema"] != "" && isset($_POST['rubrik']) && is_array($_POST['rubrik'])) {
	// Preview
	$rubrik = $_POST["rubrik"];
    $cat = new Category;
    $cat->transform(0);
    $categorylist = '';
    foreach ($rubrik as $categories) {
        $categorylist .= $cat->getPath($categories).'<br />';
    }
?>
	<h2><?php print $PMF_LANG["ad_entry_preview"]; ?></h2>

	<h3><strong><em><?php print $categorylist; ?></em>
    <?php print $_POST["thema"]; ?></strong></h3>
    <?php print $_POST["content"]; ?>
    <p class="little"><?php print $PMF_LANG["msgLastUpdateArticle"].makeDate(date("YmdHis")); ?><br />
    <?php print $PMF_LANG["msgAuthor"].$_REQUEST["author"]; ?></p>

    <form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=editpreview" method="post">
    <input type="hidden" name="id" value="<?php print $_REQUEST["id"]; ?>" />
    <input type="hidden" name="thema" value="<?php print htmlspecialchars($_POST["thema"]); ?>" />
    <input type="hidden" name="content" value="<?php print htmlspecialchars($_POST["content"]); ?>" />
    <input type="hidden" name="lang" value="<?php print $_POST["language"]; ?>" />
    <input type="hidden" name="keywords" value="<?php print $_POST["keywords"]; ?>" />
    <input type="hidden" name="author" value="<?php print $_POST["author"]; ?>" />
    <input type="hidden" name="email" value="<?php print $_POST["email"]; ?>" />
<?php
    foreach ($rubrik as $key => $categories) {
        print '    <input type="hidden" name="rubrik['.$key.']" value="'.$categories.'" />';
    }
?>
    <input type="hidden" name="solution_id" value="<?php print $_POST["solution_id"]; ?>" />
    <input type="hidden" name="revision" value="<?php print $_POST["revision"]; ?>" />
    <input type="hidden" name="active" value="<?php print $_POST["active"]; ?>" />
    <input type="hidden" name="changed" value="<?php print $_POST["changed"]; ?>" />
    <input type="hidden" name="comment" value="<?php print $_POST["comment"]; ?>" />
    <p align="center"><input type="submit" name="submit" value="<?php print $PMF_LANG["ad_entry_back"]; ?>" /></p>
    </form>
<?php
}

if (isset($submit[1]) && isset($_POST["thema"]) && $_POST["thema"] != "" && isset($_POST['rubrik']) && is_array($_POST['rubrik'])) {
	// Wenn auf Speichern geklickt wurde...
	adminlog("Beitragsave", $_REQUEST["id"]);
    print "<h2>".$PMF_LANG["ad_entry_aor"]."</h2>\n";

    $currentId = intval($_REQUEST["id"]);
    $currentLang = $db->escape_string($_POST["language"]);

    $solution_id = intval($_POST['solution_id']);
    $revision_id = intval($_POST['revision_id']);

    $query = sprintf("INSERT INTO %sfaqchanges (id, beitrag, usr, datum, what, lang, revision_id) VALUES (%d, %d, '%s', %d, '%s', '%s', %d)", SQLPREFIX, $db->nextID(SQLPREFIX."faqchanges", "id"), $currentId, $auth_id, time(), nl2br($_POST["changed"]), $currentLang, $revision_id);
	$db->query($query);

	$thema = $db->escape_string($_POST["thema"]);
	$content = $db->escape_string($_POST["content"]);
	$keywords = $db->escape_string($_POST["keywords"]);
	$author = $db->escape_string($_POST["author"]);

    if (isset($_POST["comment"]) && $_POST["comment"] != "") {
        $comment = $_POST["comment"];
    } else {
        $comment = "n";
    }

    $revision = $_POST['revision'];

    $datum = date("YmdHis");
    $rubrik = $_POST["rubrik"];

	$_result = $db->query("SELECT id, lang FROM ".SQLPREFIX."faqdata WHERE id = ".$currentId." AND lang = '".$currentLang."'");
	$num = $db->num_rows($_result);

	if ('yes' == $revision) {
        // Add current version into revision table
        $query = sprintf("INSERT INTO %sfaqdata_revisions SELECT * FROM %sfaqdata WHERE id = %d AND lang = '%s'", SQLPREFIX, SQLPREFIX, $currentId, $currentLang);
        $db->query($query);
        $revision_id += 1;
	}

    // save or update the FAQ record
	if (1 == $num) {
		$query = "UPDATE ".SQLPREFIX."faqdata SET revision_id = ".$revision_id.", thema = '".$thema."', content = '".$content."', keywords = '".$keywords."', author = '".$author."', active = '".$_POST["active"]."', datum = '".$datum."', email = '".$db->escape_string($_POST["email"])."', comment = '".$comment."' WHERE id = ".$currentId." AND lang = '".$currentLang."'";
    } else {
		$query = sprintf("INSERT INTO %sfaqdata (id, lang, solution_id, revision_id, thema, content, keywords, author, active, datum, email, comment) VALUES (%d, '%s', %d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", SQLPREFIX, $currentId, $currentLang, $solution_id, 0, $thema, $content, $keywords, $author, $db->escape_string($_POST['active']), $datum, $db->escape_string($_POST['email']), $comment);
    }

	if ($db->query($query)) {
		print $PMF_LANG["ad_entry_savedsuc"];
    } else {
		print $PMF_LANG["ad_entry_savedfail"].$db->error();
    }

    // delete category relations
    $db->query("DELETE FROM ".SQLPREFIX."faqcategoryrelations WHERE record_id = ".$currentId." and record_lang = '".$currentLang."';");
	// save or update the category relations
    foreach ($rubrik as $categories) {
        $db->query("INSERT INTO ".SQLPREFIX."faqcategoryrelations VALUES (".$categories.", '".$currentLang."', ".$currentId.", '".$currentLang."');");
    }
}

if (isset($submit[0])) {
	if ($permission["delbt"])	{
        if (isset($_POST["thema"]) && $_POST["thema"] != "") {
            $thema = "<strong>".$_POST["thema"]."</strong>";
        } else {
            $thema = "";
        }
        if (isset($_POST["author"]) && $_POST["author"] != "") {
            $author = $PMF_LANG["ad_entry_del_2"]."<strong>".$_POST["author"]."</strong>";
        } else {
            $author = "";
        }
?>
	<p align="center"><?php print $PMF_LANG["ad_entry_del_1"]." ".$thema." ".$author." ".$PMF_LANG["ad_entry_del_3"]; ?></p>
	<div align="center">
    <form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="POST">
    <input type="hidden" name="aktion" value="delentry">
    <input type="hidden" name="referer" value="<?php print $_SERVER["HTTP_REFERER"]; ?>">
    <input type="hidden" name="id" value="<?php print $_REQUEST["id"]; ?>">
    <input type="hidden" name="language" value="<?php print $_POST["language"]; ?>">
    <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_gen_yes"] ?>" name="subm">
    <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_gen_no"] ?>" name="subm">
    </form>
    </div>
<?php
    } else {
		print $PMF_LANG["err_NotAuth"];
    }
}