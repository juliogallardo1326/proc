<?php
/**
* $Id: category.edit.php,v 1.2.2.4.2.1 2006/02/09 13:48:35 thorstenr Exp $
*
* Edits a category
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-03-10
* @copyright    (c) 2003-2006 phpMyFAQ Team
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

if ($permission["editcateg"]) {
    $cat = new category;
    $categories = $cat->getAllCategories();
    $id = $_GET["cat"];
    print "<h2>".$PMF_LANG["ad_categ_edit_1"]." <em>".$categories[$id]["name"]."</em> ".$PMF_LANG["ad_categ_edit_2"]."</h2>";
?>
	<form action="<?php print $_SERVER["PHP_SELF"].$linkext; ?>" method="post">
    <input type="hidden" name="aktion" value="updatecategory" />
	<input type="hidden" name="cat" value="<?php print $id; ?>" />

	<fieldset>
        <legend><?php print $PMF_LANG["ad_categ_edit_1"]." <em>".$categories[$id]["name"]."</em> ".$PMF_LANG["ad_categ_edit_2"]; ?></legend>

        <label class="left"><?php print $PMF_LANG["ad_categ_titel"]; ?>:</label>
        <input type="text" name="name" size="40" value="<?php print $categories[$id]["name"]; ?>" /><br />

        <label class="left"><?php print $PMF_LANG["ad_categ_lang"]; ?>:</label>
        <select name="lang" size="1">
        <?php print languageOptions($categories[$id]["lang"]); ?>
        </select><br />

        <label class="left"><?php print $PMF_LANG["ad_categ_desc"]; ?>:</label>
        <input type="text" name="description" size="40" value="<?php print $categories[$id]["description"]; ?>" /><br />

        <input class="submit" style="margin-left: 190px;" type="submit" name="submit" value="<?php print $PMF_LANG["ad_categ_updatecateg"]; ?>" />
    </fieldset>

	</form>
<?php
} else {
	print $PMF_LANG["err_NotAuth"];
}
