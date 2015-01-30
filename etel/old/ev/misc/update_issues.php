<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Eventum - Issue Tracking System                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003, 2004, 2005 MySQL AB                              |
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
// @(#) $Id: s.download_emails.php 1.4 03/04/15 14:50:39-00:00 jpm $
//
include_once("../config.inc.php");
include_once(APP_INC_PATH . "class.support.php");
include_once(APP_INC_PATH . "class.project.php");
include_once(APP_INC_PATH . "class.issue.php");
include_once(APP_INC_PATH . "class.status.php");
include_once(APP_INC_PATH . "class.notification.php");
include_once(APP_INC_PATH . "class.note.php");
include_once(APP_INC_PATH . "db_access.php");

$day_limit = 4;


$sql = "SELECT 
			iss_id,iss_prj_id
		FROM 
			`ev_issue` 
			left join ev_status on sta_id = `iss_sta_id` 
		where 
			sta_is_closed = 0 
			and `iss_control_status` = 'Answered' 
			and iss_last_response_date < subdate(now(),interval $day_limit day);
		";
$issues = $GLOBALS["db_api"]->dbh->getAll($sql);


$closed_id = Status::getStatusID('Closed'); 
$c=0;
$k=0;
foreach($issues as $issue)
{
        
	
	$res = Issue::setStatus($issue[0],$closed_id);   
	if ($res == 1) {
        History::add($HTTP_GET_VARS["iss_id"], 0, History::getTypeID('status_changed'), 
                "Issue automatically set to status '" . Status::getStatusTitle(7) . "' due to ($day_limit) day inactivity ");
        Notification::notify($issue[0], 'closed');
   	}
	$c++;
}

$killed_id = Status::getStatusID('Killed'); 
$sql = "SELECT 
			iss_id
		FROM 
			`ev_issue` 
			left join ev_status on sta_id = `iss_sta_id` 
		where 
			sta_is_closed = 1
			and `iss_sta_id` = '$killed_id'
		";
		
$issues = $GLOBALS["db_api"]->dbh->getCol($sql);

foreach($issues as $issue)
{
	$GLOBALS["db_api"]->dbh->query("DELETE FROM `ev_issue` where iss_id = '".$issue."'");
	$GLOBALS["db_api"]->dbh->query("DELETE FROM `ev_subscription` where sub_iss_id = '".$issue."'");
	$GLOBALS["db_api"]->dbh->query("DELETE FROM `ev_issue_user` where isu_iss_id = '".$issue."'");
	$GLOBALS["db_api"]->dbh->query("DELETE FROM `ev_issue_history` where his_iss_id = '".$issue."'");
	$GLOBALS["db_api"]->dbh->query("DELETE FROM `ev_issue_user_replier` where iur_iss_id = '".$issue."'");

	$k++;
}

echo "($c) Closed. ($k) Deleted";
?>
