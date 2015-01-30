<?php
/**
* $Id: user.list.php,v 1.4.2.6.2.2 2006/05/08 16:04:55 thorstenr Exp $
*
* show all users
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-02-21
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

printf('<h2>%s</h2>', $PMF_LANG['ad_menu_user_administration']);

// Save new user values
if ($permission['edituser'] && isset($_POST['aktion']) && $_POST['aktion'] == 'usersave') {
    $user_id = $_POST['id'];
	adminlog('Usersave, '.$user_id);
	if (isset($_POST['npass'])) {
		if ($_POST['npass'] != $_POST['nupass']) {
			printf('<p>%s</p>', $PMF_LANG['ad_msg_passmatch']);
			$error = 1;
		}
	}
	if (!isset($error)) {
        if (isset($_POST['right']) && is_array($_POST['right'])) {
            $arrRights = $_POST['right'];
        } else {
            $arrRights = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        }
        $rights = '';
		$numRights = count($faqrights);
		for ($i = 0; $i < $numRights; $i++) {
			if (isset($arrRights[$i])) {
				$rights .= '1';
		    } else {
				$rights .= '0';
		    }
		}
		$name = $db->escape_string($_POST['name']);
		$realname = $db->escape_string($_POST['realname']);
        $email = $db->escape_string($_POST['email']);
		$query = 'UPDATE '.SQLPREFIX.'faquser SET ';
		if (isset($_POST['npass']) && $_POST['npass'] != '') {
			$query .= sprintf("pass = '%s', ", md5($_POST['npass']));
		}
		$query .= sprintf("name = '%s', realname = '%s', email = '%s', rights = '%s' WHERE id = %d", $name, $realname, $email, $rights, $user_id);
		if ($db->query($query)) {
			printf('<p>%s <strong>%s</strong> %s</p>', $PMF_LANG['ad_msg_savedsuc_1'], $name, $PMF_LANG['ad_msg_savedsuc_2']);
		} else {
			printf('<p>%s</p>', $PMF_LANG['ad_msg_mysqlerr']);;
		}
	}
}
// Save new user values
if ($permission['adduser'] && isset($_POST['aktion']) && $_POST['aktion'] == 'addsave') {
    $query = "SELECT id FROM ".SQLPREFIX."faquser WHERE name = '".$_POST['name']."'";
	if ($db->num_rows($db->query($query)) == 0 && $_POST['npass'] == $_POST['bpass']) {
        $id = $db->nextID(SQLPREFIX.'faquser', 'id');
        $name = $db->escape_string($_POST['name']);
        $realname = $db->escape_string($_POST['realname']);
        $email = $db->escape_string($_POST['email']);
        $password = md5($db->escape_string($_POST['npass']));
        $rights = str_repeat('0', count($faqrights));
        $query = sprintf("INSERT INTO %sfaquser (id, name, realname, email, pass, rights) VALUES (%d, '%s', '%s', '%s', '%s', '%s')", SQLPREFIX, $id, $name, $realname, $email, $password, $rights);
		if ($db->query($query)) {
			printf('<p>%s</p>', $PMF_LANG['ad_adus_suc']);
		} else {
			printf('<p>%s</p>', $PMF_LANG['ad_adus_dberr'].$db->error());
		}
	} else {
		printf('<p>%s</p>', $PMF_LANG['ad_adus_exerr'].$db->error());
	}
}
// Delete user
if ($permission['deluser'] && isset($_POST['aktion']) && $_POST['aktion'] == 'deluser') {
	if ($_REQUEST['sicher'] == $PMF_LANG['ad_gen_yes']) {
	    $query = sprintf('DELETE FROM %sfaquser WHERE id = %d', SQLPREFIX, $_POST['id']);
		if ($db->query($query)) {
			printf('<p>%s</p>', $PMF_LANG['ad_user_deleted']);
		} else {
			printf('<p>%s</p>', $PMF_LANG['ad_adus_dberr'].$db->error());
		}
	}
}

// Show current users
if (($permission["edituser"] || $permission["deluser"] || $permission["adduser"])) {
    $perpage = 20;
    $pages = 1;
    if (!isset($_GET["pages"])) {
		$pages = ceil($db->num_rows($db->query("SELECT id FROM ".SQLPREFIX."faquser")) / $perpage);
		if ($pages < 1) {
			$pages = 1;
		}
	} else {
		$pages = $_GET["pages"];
	}
	if (!isset($_GET["page"])) {
		$page = 1;
	} else {
		$page = $_GET["page"];
	}
	$start = ($page - 1) * $perpage;
	$ende = $perpage;
?>
    <table class="list">
    <thead>
        <tr>
            <th class="list"><?php print $PMF_LANG["ad_user_username"]; ?></th>
            <th class="list"><?php print $PMF_LANG["ad_user_rights"]; ?></th>
            <th class="list"><?php print $PMF_LANG["ad_user_action"]; ?></th>
        </tr>
    </thead>
<?php
		if ($pages > 1) {
?>
    <tfoot>
        <tr>
		    <td colspan="4"><?php print PageSpan("<a href=\"".$_SERVER["PHP_SELF"].$linkext."&amp;aktion=user&amp;page=<NUM>&amp;pages=".$pages."\">", 1, $pages, $page); ?></td>
        </tr>
    </tfoot>
<?php
		}
?>
    <tbody>
<?php
		$result = $db->query("SELECT id, name, realname, rights FROM ".SQLPREFIX."faquser ORDER BY id");
		$counter = 0;
	  	$displayCounter = 0;
		while (($row = $db->fetch_object($result)) && $displayCounter < $perpage) {
      		$counter++;
			if ($counter <= $start) {
        		continue;
      		}
		  	$displayCounter++;
?>
        <tr>
    		<td class="list"><?php print $row->name; if (strlen($row->realname) > 0) { print " (".$row->realname.")"; } ?></td>
    		<td class="list"><?php print $row->rights; ?></td>
    		<td class="list"><a href="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=useredit&amp;id=<?php print $row->id; ?>" title="<?php print $PMF_LANG["ad_user_edit"]; ?>"><img src="images/edit.gif" width="18" height="18" alt="<?php print  $PMF_LANG["ad_user_edit"]; ?>" border="0" /></a><?php if (strtolower($row->name) != "admin") { ?>&nbsp;&nbsp;<a href="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=userdel&amp;id=<?php print $row->id; ?>" title="<?php print $PMF_LANG["ad_user_delete"]; ?>"><img src="images/delete.gif" width="17" height="18" alt="<?php print $PMF_LANG["ad_user_delete"]; ?>" border="0" /></a><?php } ?></td>
    	</tr>
<?php
		}
?>
	</tbody>
    </table>
    <p>[ <a href="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=useradd"><?php print $PMF_LANG["ad_user_add"]; ?></a> ]</p>
<?php
} else {
	print $PMF_LANG["err_NotAuth"];
}