<?php
/**
* $Id: record.show.php,v 1.14.2.12.2.13 2006/05/08 20:41:27 thorstenr Exp $
*
* Shows the list of records ordered by categories
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-02-23
* @copyright    (c) 2001 - 2006 phpMyFAQ Team
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

print "<h2>".$PMF_LANG["ad_entry_aor"]."</h2>\n";

if ($permission["editbt"] || $permission["delbt"]) {
    // (re)evaluate the Category object w/o passing the user language
    $tree = new Category();
    $tree->transform(0);
    // 1. Count the comments for each faq
    $resultComments = $db->query("SELECT count(id) as anz, id FROM ".SQLPREFIX."faqcomments GROUP BY id ORDER BY id;");
    if ($db->num_rows($resultComments) > 0) {
        while ($row = $db->fetch_object($resultComments)) {
            $numCommentsByFaq[$row->id] = $row->anz;
        }
    }
    // 2. Count the comments for each category
    // 2.1 Create a matrix for representing categories and faq records
    $query  = 'SELECT '.SQLPREFIX.'faqcategoryrelations.category_id AS id_cat, '.SQLPREFIX.'faqdata.id AS id';
    $query .= ' FROM '.SQLPREFIX.'faqdata';
    $query .= ' INNER JOIN '.SQLPREFIX.'faqcategoryrelations';
    $query .= ' ON '.SQLPREFIX.'faqdata.id='.SQLPREFIX.'faqcategoryrelations.record_id';
    $query .= ' AND '.SQLPREFIX.'faqdata.lang='.SQLPREFIX.'faqcategoryrelations.category_lang';
    $query .= ' ORDER BY '.SQLPREFIX.'faqcategoryrelations.category_id, '.SQLPREFIX.'faqdata.id';
    $resultFaq = $db->query($query);
    if ($db->num_rows($resultFaq) > 0) {
        while ($row = $db->fetch_object($resultFaq)) {
            $matrix[$row->id_cat][$row->id] = true;
        }
    }
    // 2.2 Evaluate the comments for each category using the matrix above
    foreach ($matrix as $catkey => $value) {
        $numCommentsByCat[$catkey] = 0;
        foreach ($value as $faqkey => $value) {
            if (isset($numCommentsByFaq[$faqkey])) {
                $numCommentsByCat[$catkey] += $numCommentsByFaq[$faqkey];
            }
        }
    }
    // 3. Count the entries
    if (isset($_REQUEST['aktion']) && $_REQUEST['aktion'] == 'accept') {
        $active = 'no';
    } else {
        $active = 'yes';
    }
    $query = "SELECT ".SQLPREFIX."faqcategoryrelations.category_id AS category_id, count(".SQLPREFIX."faqcategoryrelations.category_id) AS number FROM ".SQLPREFIX."faqcategoryrelations, ".SQLPREFIX."faqdata WHERE ".SQLPREFIX."faqcategoryrelations.record_id = ".SQLPREFIX."faqdata.id AND ".SQLPREFIX."faqdata.active = '".$active."' GROUP BY ".SQLPREFIX."faqcategoryrelations.category_id";

    $result = $db->query($query);
    if ($db->num_rows($result) > 0) {
        while ($row = $db->fetch_object($result)) {
            $numRecordsByCat[$row->category_id] = $row->number;
        }
    }

    if (isset($_REQUEST["aktion"]) && $_REQUEST["aktion"] == "view" && !isset($_REQUEST["suchbegriff"])) {
        // No search requested
        $query = 'SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.thema AS thema FROM '.SQLPREFIX.'faqdata INNER JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang ='.SQLPREFIX.'faqcategoryrelations.record_lang AND '.SQLPREFIX.'faqdata.active = \'yes\' ORDER BY '.SQLPREFIX.'faqcategoryrelations.category_id, '.SQLPREFIX.'faqdata.id ';
        $result = $db->query($query);
        $laktion = 'view';
        $internalSearch = '';
    } else if (isset($_REQUEST["aktion"]) && $_REQUEST["aktion"] == "view" && isset($_REQUEST["suchbegriff"]) && $_REQUEST["suchbegriff"] != "") {
        // Search for:
        // a. solution id
        // b. full text search
        // TODO: Decide if the search will be performed upon all entries or upon the active ones.
        $begriff = strip_tags($_REQUEST["suchbegriff"]);
        if (is_numeric($begriff)) {
            // a. solution id
            $result = $db->search(SQLPREFIX.'faqdata',
                        array(SQLPREFIX.'faqdata.id AS id',
                            SQLPREFIX.'faqdata.lang AS lang',
                            SQLPREFIX.'faqcategoryrelations.category_id AS category_id',
                            SQLPREFIX.'faqdata.thema AS thema',
                            SQLPREFIX.'faqdata.content AS content'),
                        SQLPREFIX.'faqcategoryrelations',
                        array(SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id',
                            SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang'),
                        array(SQLPREFIX.'faqdata.solution_id'),
                        $begriff);
        } else {
            // b. full text search
            $result = $db->search(SQLPREFIX."faqdata",
                        array(SQLPREFIX.'faqdata.id AS id',
                            SQLPREFIX.'faqdata.lang AS lang',
                            SQLPREFIX.'faqcategoryrelations.category_id AS category_id',
                            SQLPREFIX.'faqdata.thema AS thema',
                            SQLPREFIX.'faqdata.content AS content'),
                        SQLPREFIX.'faqcategoryrelations',
                        array(SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id',
                            SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang'),
                        array(SQLPREFIX.'faqdata.thema',
                            SQLPREFIX.'faqdata.content',
                            SQLPREFIX.'faqdata.keywords'),
                        $begriff);
        }
        $laktion = 'view';
        $internalSearch = '&amp;search='.$begriff;

    } elseif (isset($_REQUEST["aktion"]) && $_REQUEST["aktion"] == "accept") {
        $query = 'SELECT '.SQLPREFIX.'faqdata.id AS id,'.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.thema AS thema FROM '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang ='.SQLPREFIX.'faqcategoryrelations.record_lang WHERE '.SQLPREFIX.'faqdata.active = \'no\' ORDER BY '.SQLPREFIX.'faqcategoryrelations.category_id, '.SQLPREFIX.'faqdata.id';
        $result = $db->query($query);
        $laktion = 'accept';
        $internalSearch = '';
    }

    if ($db->num_rows($result) > 0) {
?>
    <form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=view" method="post">
    <fieldset>
    <legend><?php print $PMF_LANG["msgSearch"]; ?></legend>
        <label class="left"><?php print $PMF_LANG["msgSearchWord"]; ?>:</label>
        <input type="text" name="suchbegriff" size="35" />&nbsp;<input class="submit" type="submit" name="submit" value="<?php print $PMF_LANG["msgSearch"]; ?>" />
    </fieldset>

    <fieldset>
    <legend><?php print ((isset($_REQUEST['aktion']) && 'accept' == $_REQUEST['aktion']) ? $PMF_LANG['ad_menu_entry_aprove'] : $PMF_LANG['ad_menu_entry_edit']); ?></legend>
<?php
        $old = 0;

        while ($row = $db->fetch_object($result)) {
            $cid = $row->category_id;
            $catInfo = ' (';
            if (isset($numRecordsByCat[$cid]) && ($numRecordsByCat[$cid] > 0)) {
                $catInfo .= sprintf('%d %s', $numRecordsByCat[$cid], $PMF_LANG['msgEntries']);
            }
            if (isset($numCommentsByCat[$cid]) && ($numCommentsByCat[$cid] > 0) && $laktion != 'accept') {
                $catInfo .= sprintf(', %d %s', $numCommentsByCat[$cid], $PMF_LANG['ad_start_comments']);
            }
            $catInfo .= ')';
            if ($cid != $old) {
                if ($old == 0) {
?>
    <!--<a name="cat_<?php print $cid; ?>" />--><div class="categorylisting"><a href="#cat_<?php print $cid; ?>" onclick="showhideCategory('category_<?php print $cid; ?>');"><img src="../images/more.gif" width="11" height="11" alt="" /> <?php print $tree->getPath($cid); ?></a><?php print $catInfo;?></div>
    <div id="category_<?php print $cid; ?>" class="categorybox" style="display: none;">
    <table class="listrecords">
<?php
                } else {
?>
    </table>
    </div>
    <!--<a name="cat_<?php print $cid; ?>" />--><div class="categorylisting"><a href="#cat_<?php print $cid; ?>" onclick="showhideCategory('category_<?php print $cid; ?>');"><img src="../images/more.gif" width="11" height="11" alt="" /> <?php print $tree->getPath($cid); ?></a><?php print $catInfo;?></div>
    <div id="category_<?php print $cid; ?>" class="categorybox" style="display: none;">
    <table class="listrecords">
<?php
                }
?>
    <tbody>
<?php
            }
?>
    <tr>
        <td class="list" style="width: 24px; text-align: right;"><?php print $row->id; ?></td>
        <td class="list" style="width: 16px;"><?php print $row->lang; ?></td>
        <td class="list"><a href="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=editentry&amp;id=<?php print $row->id; ?>&amp;lang=<?php print $row->lang; ?>" title="<?php print $PMF_LANG["ad_user_edit"]; ?> '<?php print str_replace("\"", "´", $row->thema); ?>'"><?php print PMF_htmlentities($row->thema, ENT_NOQUOTES, $PMF_LANG['metaCharset']); ?></a>
<?php
        if (isset($numCommentsByFaq[$row->id])) {
            print " (".$numCommentsByFaq[$row->id]." ".$PMF_LANG["ad_start_comments"].")";
        }
?></td>
        <td class="list" width="17"><a href="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=saveentry&amp;id=<?php print $row->id; ?>&amp;language=<?php print $row->lang; ?>&amp;submit[0]=<?php print $PMF_LANG["ad_entry_delete"]; ?>" title="<?php print $PMF_LANG["ad_user_delete"]; ?> '<?php print str_replace("\"", "´", $row->thema); ?>'"><img src="images/delete.gif" width="17" height="18" alt="<?php print $PMF_LANG["ad_entry_delete"]; ?>" /></a></td>
    </tr>
<?php
            $old = $cid;
        }
?>
    </tbody>
    </table>
    </div>
    </fieldset>
    </form>
<?php
    } else {
        print "n/a";
    }
} else {
    print $PMF_LANG["err_NotAuth"];
}
