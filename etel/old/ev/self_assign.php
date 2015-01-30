<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Eventum - Issue Tracking System                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003, 2004, 2005, 2006 MySQL AB                        |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Authors: João Prado Maia <jpm@mysql.com>                             |
// +----------------------------------------------------------------------+
//
// @(#) $Id$
//
include_once("config.inc.php");
include_once(APP_INC_PATH . "class.template.php");
include_once(APP_INC_PATH . "class.misc.php");
include_once(APP_INC_PATH . "class.auth.php");
include_once(APP_INC_PATH . "class.issue.php");
include_once(APP_INC_PATH . "class.notification.php");
include_once(APP_INC_PATH . "db_access.php");

$tpl = new Template_API();
$tpl->setTemplate("self_assign.tpl.html");

Auth::checkAuthentication(APP_COOKIE, 'index.php?err=5', true);
$usr_id = Auth::getUserID();
$target_usr_id = $usr_id;
$prj_id = Auth::getCurrentProject();
$issue_id = $_REQUEST["iss_id"];
$tpl->assign("issue_id", $issue_id);

if($_REQUEST['assigntomerc'])
{
	$custom_fields = Custom_Field::getListByIssue($prj_id, $issue_id);
	$ref_id = '';
	foreach($custom_fields as $field)
		if($field['fld_title'] == 'Reference ID')
			$ref_id = trim($field['icf_value']);
	
	if($ref_id)
	{
		$stmt = "SELECT
				en_ID
			 FROM
				" . ETEL_USER_TRANS_TABLE_NOSUB . "
			 WHERE
				reference_number='" . Misc::escapeString($ref_id) . "'";
		$info = $GLOBALS["db_api"]->dbh->getOne($stmt);
		if (PEAR::isError($info)) {
			Error_Handler::logError(array($info->getMessage(), $info->getDebugInfo()), __FILE__, __LINE__);
			return false;
		} else {
			$target_usr_id = $info;
			$newstatus = 8;
			$_REQUEST["target"] = "replace";
		}
	}
}
// check if issue is assigned to someone else and if so, confirm change.
$assigned_user_ids = Issue::getAssignedUserIDs($issue_id);
if ((count($assigned_user_ids) > 0) && (empty($_REQUEST["target"])) && (!$_REQUEST['assigntomerc'])) {
    $tpl->assign(array(
        "prompt_override"   =>  1,
        "assigned_users"    =>  Issue::getAssignedUsers($issue_id)
    ));
} else {
    // force assignment change
    if (@$_REQUEST["target"] == "replace") {
        // remove current user(s) first
        Issue::deleteUserAssociations($issue_id, $usr_id);
    }
    $res = Issue::addUserAssociation($usr_id, $issue_id, $target_usr_id);
    $tpl->assign("self_assign_result", $res);
    if($newstatus)
		$res = Issue::setStatus($issue_id, $newstatus, true);
    Notification::subscribeUser($usr_id, $issue_id, $target_usr_id, Notification::getDefaultActions());
    Workflow::handleAssignment($prj_id, $issue_id, $target_usr_id);
}


$tpl->assign("current_user_prefs", Prefs::get($usr_id));

$tpl->displayTemplate();
?>