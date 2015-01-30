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
include_once(APP_INC_PATH . "class.lock.php");
include_once(APP_INC_PATH . "class.project.php");
include_once(APP_INC_PATH . "db_access.php");

ini_set("memory_limit", "256M");

// we need the IMAP extension for this to work
if (!function_exists('imap_open')) {
    echo "Error: Eventum requires the IMAP extension in order to download messages saved on a IMAP/POP3 mailbox.\n";
    echo "Please refer to the PHP manual for more details about how to enable the IMAP extension.\n";
    exit;
}

$emails = Email_Account::getList();
 
foreach ($emails as $email)
{
    $username = $email['ema_username'];
    $hostname = $email['ema_hostname'];
    $mailbox = $email['ema_folder'];

	
	// get the account ID since we need it for locking.
	$account_id = Email_Account::getAccountID($username, $hostname, $mailbox);
	if (($account_id == 0) && ($fix_lock != true)) {
		echo "Error: Could not find a email account with the parameter provided. Please verify your email account settings and try again.\n";
	}
	
	// check if there is another instance of this script already running
	if (!Lock::acquire('download_emails_' . $account_id)) {
		if ($type == 'cli') {
			echo "Error: Another instance of the script is still running for the specified account ($account_id). " .
						"If this is not accurate, you may fix it by running this script with '--fix-lock' " .
						"as the 4th parameter or you may unlock ALL accounts by running this script with '--fix-lock' " .
						"as the only parameter.\n";
		} else {
			echo "Error: Another instance of the script is still running for the specified account ($account_id). " .
						"If this is not accurate, you may fix it by running this script with 'fix-lock=1' " .
						"in the query string or you may unlock ALL accounts by running this script with 'fix-lock=1' " .
						"as the only parameter.<br />\n";
		}
	}
	else
	{
	
		$account = Email_Account::getDetails($account_id);
		$mbox = Support::connectEmailServer($account);
		if ($mbox == false) {
			echo "Error: Could not connect to the email server. Please verify your email account settings and try again.\n";
			Lock::release('download_emails_' . $account_id);
		} else {
			$total_emails = Support::getTotalEmails($mbox);
			if ($total_emails > 0) {
				for ($i = 1; $i <= $total_emails; $i++) {
					Support::getEmailInfo($mbox, $account, $i);
				}
			}
			imap_expunge($mbox);
			Support::clearErrors();
		}
		
		// clear the lock
		Lock::release('download_emails_' . $account_id);
	}
} 
?>
