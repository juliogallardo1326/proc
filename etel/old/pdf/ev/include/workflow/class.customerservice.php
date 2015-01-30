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
// | Authors: Bryan Alsdorf <bryan@mysql.com>                             |
// +----------------------------------------------------------------------+
//

include_once(APP_INC_PATH . "workflow/class.abstract_workflow_backend.php");

/**
 * Example workflow backend class. For example purposes it will print what
 * method is called.
 *
 * @author  Bryan Alsdorf <bryan@mysql.com>
 */
class CustomerService_Workflow_Backend extends Abstract_Workflow_Backend
{
    /**
     * Called when an issue is updated.
     *
     * @param integer $prj_id The project ID.
     * @param integer $issue_id The ID of the issue.
     * @param integer $usr_id The ID of the user.
     * @param array $old_details The old details of the issues.
     * @param array $changes The changes that were applied to this issue (the $HTTP_POST_VARS)
     */
    function handleIssueUpdated($prj_id, $issue_id, $usr_id, $old_details, $changes)
    {

        echo "Workflow: Issue Updated $prj_id, $issue_id, $usr_id <br />\n";
    }


    /**
     * Called when an issue is assigned.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @param   integer $usr_id The id of the user who assigned the issue.
     */
    function handleAssignment($prj_id, $issue_id, $usr_id)
    {

        echo "Workflow: Issue Assigned<br />\n";
    }


    /**
     * Called when a file is attached to an issue.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @param   integer $usr_id The id of the user who locked the issue.
     */
    function handleAttachment($prj_id, $issue_id, $usr_id)
    {

        echo "Workflow: File attached<br />\n";
    }


    /**
     * Called when the priority of an issue changes.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @param   integer $usr_id The id of the user who locked the issue.
     * @param   array $old_details The old details of the issue.
     * @param   array $changes The changes that were applied to this issue (the $HTTP_POST_VARS)
     */
    function handlePriorityChange($prj_id, $issue_id, $usr_id, $old_details, $changes)
    {

        echo "Workflow: Priority Changed<br />\n";
    }


    /**
     * Called when an email is blocked.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @param   array $email_details Details of the issue
     * @param   string $type What type of blocked email this is.
     */
    function handleBlockedEmail($prj_id, $issue_id, $email_details, $type)
    {

        echo "Workflow: Email '".$email_details['from']."' Blocked <br />\n";
    }


    /**
     * Called when a note is routed.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @param   integer $usr_id The user ID of the person posting this new note
     * @param   boolean $closing If the issue is being closed
     */
    function handleNewNote($prj_id, $issue_id, $usr_id, $closing)
    {

        echo "Workflow: New Note<br />\n";
    }


    /**
     * Called when the assignment on an issue changes.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @param   integer $usr_id The id of the user who locked the issue.
     * @param   array $issue_details The old details of the issue.
     * @param   array $new_assignees The new assignees of this issue.
     * @param   boolean $remote_assignment If this issue was remotely assigned.
     */
    function handleAssignmentChange($prj_id, $issue_id, $usr_id, $issue_details, $new_assignees, $remote_assignment)
    {

        echo "Workflow: Assignment changed<br />\n";
    }


    /**
     * Called when a new issue is created.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @param   boolean $has_TAM If this issue has a technical account manager.
     * @param   boolean $has_RR If Round Robin was used to assign this issue.
     */
    function handleNewIssue($prj_id, $issue_id, $has_TAM, $has_RR)
    {

        echo "Workflow: New Issue $prj_id, $issue_id, $has_TAM, $has_RR<br />\n";
    }




    /**
     * Updates the existing issue to a different status when an email is
     * manually associated to an existing issue.
     *
     * @access  public
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The issue ID
     */
    function handleManualEmailAssociation($prj_id, $issue_id)
    {

        echo "Workflow: Manually associating email to issue<br />\n";
    }


    /**
     * Called when a new message is recieved.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @param   object $message An object containing the new email
     * @param   array $row The array of data that was inserted into the database.
     * @param   boolean $closing If we are closing the issue.
     */
    function handleNewEmail($prj_id, $issue_id, $message, $row = false, $closing = false)
    {
      	$subject = $row['subject'];
      	$body = $row['body'];
		preg_match('/(H|C)[A-Z0-9]{12,14}/',$subject,$header_matches);
		preg_match_all('/(H|C)[A-Z0-9]{12,14}/',$body,$body_matches);
		if($header_matches[0])
			$refs[] = $header_matches[0];
		foreach($body_matches[0] as $body_match)
			if($body_match)
				$refs[] = $body_match;
		$refs = @array_unique($refs);		
		
		$stmt = "Select reference_number,ss_subscription_id from ".ETEL_TRANS_SUBS_TABLE_NOSUB."
		WHERE
			reference_number in ('". @implode("','",$refs)."')";

		$res = $GLOBALS["db_api"]->dbh->getRow($stmt);
		
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
        } else {
			if($res[0])
				Custom_Field::associateIssue($issue_id, 1, $res[0]);
			if($res[1])
				Custom_Field::associateIssue($issue_id, 4, $res[1]);
        }
   		$res = Authorized_Replier::manualInsert($issue_id, $row['from']);
		Issue::updateControlStatus($issue_id);			
    }


    /**
     * Method is called to return the list of statuses valid for a specific issue.
     *
     * @param   integer $prj_id The projectID
     * @param   integer $issue_id The ID of the issue.
     * @return  array An associative array of statuses valid for this issue.
     */
    function getAllowedStatuses($prj_id, $issue_id)
    {
       $statuses = Status::getAssocStatusList($prj_id, true);
       // you should perform any logic and remove any statuses you need to here.
       return $statuses;
    }


    /**
     * Called when an attempt is made to add a user or email address to the
     * notification list.
     *
     * @param   integer $prj_id The project ID
     * @param   integer $issue_id The ID of the issue.
     * @param   integer $subscriber_usr_id The ID of the user to subscribe if this is a real user (false otherwise).
     * @param   string $email The email address to subscribe to subscribe (if this is not a real user).
     * @param   array $types The action types.
     * @return  mixed An array of information or true to continue unchanged or false to prevent the user from being added.
     */
    function handleSubscription($prj_id, $issue_id, &$subscriber_usr_id, &$email, &$actions)
    {
		if($prj_id!=5)
		{
			if(User::getRoleByUser($subscriber_usr_id, $prj_id) >= User::getRoleID('Developer'))
			{
				return false;			
			}
		}		
		return true;
    }


    /**
     * Called when issue is closed.
     *
     * @param   integer $prj_id The project ID
     * @param   integer $issue_id The ID of the issue.
     * @param   boolean $send_notification Whether to send a notification about this action or not
     * @param   integer $resolution_id The resolution ID
     * @param   integer $status_id The status ID
     * @param   string $reason The reason for closing this issue
     * @return  void
     */
    function handleIssueClosed($prj_id, $issue_id, $send_notification, $resolution_id, $status_id, $reason)
    {

        $sql = "UPDATE
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "issue
                SET
                    iss_percent_complete = '100%'
                WHERE
                    iss_id = $issue_id";
        $res = $GLOBALS["db_api"]->dbh->query($sql);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return false;
        }

        echo "Workflow: handleIssueClosed<br />\n";
    }


    /**
     * Called when custom fields are updated
     *
     * @param   integer $prj_id The project ID
     * @param   integer $issue_id The ID of the issue
     * @param   array $old The custom fields before the update.
     * @param   array $new The custom fields after the update.
     */
    function handleCustomFieldsUpdated($prj_id, $issue_id, $old, $new)
    {

        echo "Workflow: handleCustomFieldsUpdated<br />\n";
    }


    /**
     * Determines if the address should should be emailed.
     *
     * @param   integer $prj_id The project ID
     * @param   string $address The email address to check
     * @return  boolean
     */
    function shouldEmailAddress($prj_id, $address)
    {

        if ($address == "bad_email@example.com") {
            return false;
        } else {
    
        }
    }
}
?>