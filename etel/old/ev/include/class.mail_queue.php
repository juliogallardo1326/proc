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
// | Authors: Jo�o Prado Maia <jpm@mysql.com>                             |
// +----------------------------------------------------------------------+
//
// @(#) $Id$
//


include_once(APP_INC_PATH . "class.error_handler.php");
include_once(APP_INC_PATH . "class.date.php");
include_once(APP_INC_PATH . "class.mime_helper.php");
include_once(APP_INC_PATH . "class.setup.php");
include_once(APP_INC_PATH . "class.lock.php");
include_once(APP_INC_PATH . "class.user.php");
include_once(APP_PEAR_PATH . 'Mail.php');

class Mail_Queue
{
    /**
     * Checks whether it is safe or not to run the mail queue script.
     *
     * @access  public
     * @return  boolean
     */
    function isSafeToRun()
    {
        return Lock::acquire('process_mail_queue');
    }


    /**
     * Clears the lock file for the next time this script runs again.
     *
     * @access  public
     * @return  void
     */
    function removeProcessFile()
    {
        Lock::release('process_mail_queue');
    }


    /**
     * Adds an email to the outgoing mail queue.
     *
     * @access  public
     * @param   string $recipient The recipient of this email
     * @param   array $headers The list of headers that should be sent with this email
     * @param   string $body The body of the message
     * @param   integer $save_email_copy Whether to send a copy of this email to a configurable address or not (eventum_sent@)
     * @param   integer $issue_id The ID of the issue. If false, email will not be associated with issue.
     * @param   string $type The type of message this is.
     * @param   integer $sender_usr_id The id of the user sending this email.
     * @param   integer $type_id The ID of the event that triggered this notification (issue_id, sup_id, not_id, etc)
     * @return  true, or a PEAR_Error object
     */
    function add($recipient, $headers, $body, $save_email_copy = 0, $issue_id = false, $type = '', $sender_usr_id = false, $type_id = false)
    {
        // avoid sending emails out to users with inactive status
        $recipient_email = Mail_API::getEmailAddress($recipient);
        $usr_id = User::getUserIDByEmail($recipient_email);
        if (!empty($usr_id)) {
            $user_status = User::getStatusByEmail($recipient_email);
            // if user is not set to an active status, then silently ignore
            if ((!User::isActiveStatus($user_status)) && (!User::isPendingStatus($user_status))) {
                return false;
            }
        }

        $to_usr_id = User::getUserIDByEmail($recipient_email);
        $recipient = Mail_API::fixAddressQuoting($recipient);

        $reminder_addresses = Reminder::_getReminderAlertAddresses();

        // add specialized headers
        if ((!empty($issue_id)) && ((!empty($to_usr_id)) && (User::getRoleByUser($to_usr_id, Issue::getProjectID($issue_id)) > User::getRoleID("Customer"))) ||
                (@in_array(Mail_API::getEmailAddress($to), $reminder_addresses))) {
            $headers += Mail_API::getSpecializedHeaders($issue_id, $type, $headers, $sender_usr_id);
        }

        if (empty($issue_id)) {
            $issue_id = 'null';
        }
        // if the Date: header is missing, add it.
        if (!in_array('Date', array_keys($headers))) {
            $headers['Date'] = MIME_Helper::encode(date('D, j M Y H:i:s O'));
        }
        if (!empty($headers['To'])) {
            $headers['To'] = Mail_API::fixAddressQuoting($headers['To']);
        }

        list(,$text_headers) = Mail_API::prepareHeaders($headers);

        $stmt = "INSERT INTO
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "mail_queue
                 (
                    maq_save_copy,
                    maq_queued_date,
                    maq_sender_ip_address,
                    maq_recipient,
                    maq_headers,
                    maq_body,
                    maq_iss_id,
                    maq_subject,
                    maq_type";
        if ($sender_usr_id != false) {
            $stmt .= ",\nmaq_usr_id";
        }
        if ($type_id != false) {
            $stmt .= ",\nmaq_type_id";
        }
        $stmt .= ") VALUES (
                    $save_email_copy,
                    '" . Date_API::getCurrentDateGMT() . "',
                    '" . getenv("REMOTE_ADDR") . "',
                    '" . Misc::escapeString($recipient) . "',
                    '" . Misc::escapeString($text_headers) . "',
                    '" . Misc::escapeString($body) . "',
                    " . Misc::escapeInteger($issue_id) . ",
                    '" . Misc::escapeString($headers["Subject"]) . "',
                    '$type'";
        if ($sender_usr_id != false) {
            $stmt .= ",\n" . $sender_usr_id;
        }
        if ($type_id != false) {
            $stmt .= ",\n" . $type_id;
        }
        $stmt .= ")";
        $res = $GLOBALS["db_api"]->dbh->query($stmt);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return $res;
        } else {
            return true;
        }
    }


    /**
     * Sends the queued up messages to their destinations. This can either try
     * to send emails that couldn't be sent before (status = 'error'), or just
     * emails just recently queued (status = 'pending').
     *
     * @access  public
     * @param   string $status The status of the messages that need to be sent
     * @param   integer $limit The limit of emails that we should send at one time
     */
    function send($status, $limit)
    {
        // get list of emails to send
        $emails = Mail_Queue::_getList($status, $limit);
        // foreach email
        for ($i = 0; $i < count($emails); $i++) {
            $result = Mail_Queue::_sendEmail($emails[$i]['recipient'], $emails[$i]['headers'], $emails[$i]['body']);
            if (PEAR::isError($result)) {
                Mail_Queue::_saveLog($emails[$i]['id'], 'error', Mail_Queue::_getErrorMessage($result));
            } else {
                Mail_Queue::_saveLog($emails[$i]['id'], 'sent', '');
                if ($emails[$i]['save_copy']) {
                    // send a copy of this email to eventum_sent@
                    Mail_API::saveEmailInformation($emails[$i]);
                }
            }
        }
    }


    /**
     * Connects to the SMTP server and sends the queued message.
     *
     * @access  private
     * @param   string $recipient The recipient of this message
     * @param   string $text_headers The full headers of this message
     * @param   string $body The full body of this message
     * @return  true, or a PEAR_Error object
     */
    function _sendEmail($recipient, $text_headers, $body)
    {
        $header_names = Mime_Helper::getHeaderNames($text_headers);
        $_headers = Mail_Queue::_getHeaders($text_headers, $body);
        $headers = array();
        foreach ($_headers as $lowercase_name => $value) {
            // need to remove the quotes to avoid a parsing problem
            // on senders that have extended characters in the first
            // or last words in their sender name
            if ($lowercase_name == 'from') {
                $value = Mime_Helper::removeQuotes($value);
            }
            $value = Mime_Helper::encode($value);
            // add the quotes back
            if ($lowercase_name == 'from') {
                $value = Mime_Helper::quoteSender($value);
            }
            $headers[$header_names[$lowercase_name]] = $value;
        }
        // remove any Reply-To:/Return-Path: values from outgoing messages
        unset($headers['Reply-To']);
        unset($headers['Return-Path']);
        // mutt sucks, so let's remove the broken Mime-Version header and add the proper one
        if (in_array('Mime-Version', array_keys($headers))) {
            unset($headers['Mime-Version']);
            $headers['MIME-Version'] = '1.0';
        }
        $mail =& Mail::factory('smtp', Mail_API::getSMTPSettings());
        $res = $mail->send($recipient, $headers, $body);
        if (PEAR::isError($res)) {
            // special handling of errors when the mail server is down
            if (strstr($res->getMessage(), 'unable to connect to smtp server')) {
                Error_Handler::logToFile(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            } else {
                Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            }
            return $res;
        } else {
            return true;
        }
    }


    /**
     * Parses the full email message and returns an array of the headers
     * contained in it.
     *
     * @access  private
     * @param   string $text_headers The full headers of this message
     * @param   string $body The full body of this message
     * @return  array The list of headers
     */
    function _getHeaders($text_headers, $body)
    {
        $message = $text_headers . "\n\n" . $body;
        $structure = Mime_Helper::decode($message, FALSE, FALSE);
        return $structure->headers;
    }


    /**
     * Retrieves the list of queued email messages, given a status.
     *
     * @access  private
     * @param   string $status The status of the messages
     * @param   integer $limit The limit on the number of messages that need to be returned
     * @return  array The list of queued email messages
     */
    function _getList($status, $limit = 50)
    {
        $stmt = "SELECT
                    maq_id id,
                    maq_iss_id,
                    maq_save_copy save_copy,
                    maq_recipient recipient,
                    maq_headers headers,
                    maq_body body,
                    maq_type,
                    maq_usr_id
                 FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "mail_queue
                 WHERE
                    maq_status='$status'
                 ORDER BY
                    maq_id ASC
                 LIMIT
                    0, $limit";
        $res = $GLOBALS["db_api"]->dbh->getAll($stmt, DB_FETCHMODE_ASSOC);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return array();
        } else {
            return $res;
        }
    }


    /**
     * Saves a log entry about the attempt, successful or otherwise, to send the
     * queued email message.
     *
     * @access  private
     * @param   integer $maq_id The queued email message ID
     * @param   string $status The status of the attempt ('sent' or 'error')
     * @param   string $server_message The full message from the SMTP server, in case of an error
     * @return  boolean
     */
    function _saveLog($maq_id, $status, $server_message)
    {
        $stmt = "INSERT INTO
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "mail_queue_log
                 (
                    mql_maq_id,
                    mql_created_date,
                    mql_status,
                    mql_server_message
                 ) VALUES (
                    $maq_id,
                    '" . Date_API::getCurrentDateGMT() . "',
                    '$status',
                    '$server_message'
                 )";
        $res = $GLOBALS["db_api"]->dbh->query($stmt);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return false;
        } else {
			if($status=='error') $sql_incattempt = ", maq_attempts = maq_attempts + 1";
            $stmt = "UPDATE
                        " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "mail_queue
                     SET
                        maq_status = if(maq_attempts>2,'gaveup','$status')
						$sql_incattempt
                     WHERE
                        maq_id=$maq_id";
            $GLOBALS["db_api"]->dbh->query($stmt);
            return true;
        }
    }


    /**
     * Handles the PEAR_Error object returned from the SMTP server, and returns
     * an appropriate error message string.
     *
     * @access  private
     * @param   object $error The PEAR_Error object
     * @return  string The error message
     */
    function _getErrorMessage($error)
    {
        return $error->getMessage() . "/" . $error->getDebugInfo();
    }


    /**
     * Returns the mail queue for a specific issue.
     *
     * @access  public
     * @param   integer $issue_is The issue ID
     * @return  array An array of emails from the queue
     */
    function getListByIssueID($issue_id)
    {
        $issue_id = Misc::escapeInteger($issue_id);
        $stmt = "SELECT
                    maq_id,
                    maq_queued_date,
                    maq_status,
                    maq_recipient,
                    maq_subject
                 FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "mail_queue
                 WHERE
                    maq_iss_id = " . Misc::escapeInteger($issue_id) . "
                 ORDER BY
                    maq_queued_date ASC";
        $res = $GLOBALS["db_api"]->dbh->getAll($stmt, DB_FETCHMODE_ASSOC);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return false;
        }
        if (count($res) > 0) {
            for ($i = 0; $i < count($res); $i++) {
                $res[$i]['maq_recipient'] = Mime_Helper::decodeAddress($res[$i]['maq_recipient']);
                $res[$i]['maq_queued_date'] = Date_API::getFormattedDate(Date_API::getUnixTimestamp($res[$i]['maq_queued_date'], 'GMT'));
                $res[$i]['maq_subject'] = Mime_Helper::fixEncoding($res[$i]['maq_subject']);
            }
        }
        return $res;
    }


    /**
     * Returns the mail queue entry based on ID.
     *
     * @acess   public
     * @param   integer $maq_id The id of the mail queue entry.
     * @return  array An array of information
     */
    function getEntry($maq_id)
    {
        $stmt = "SELECT
                    maq_iss_id,
                    maq_queued_date,
                    maq_status,
                    maq_recipient,
                    maq_subject,
                    maq_headers,
                    maq_body
                 FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "mail_queue
                 WHERE
                    maq_id = " . Misc::escapeInteger($maq_id);
        $res = $GLOBALS["db_api"]->dbh->getRow($stmt, DB_FETCHMODE_ASSOC);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return false;
        } else {
            return $res;
        }
    }


    function getMessageRecipients($type, $type_id)
    {
        $sql = "SELECT
                    maq_recipient
                FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "mail_queue
                WHERE
                    maq_type = '" . Misc::escapeString($type) . "' AND
                    maq_type_id = " . Misc::escapeInteger($type_id);
        $res = $GLOBALS["db_api"]->dbh->getCol($sql);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return false;
        } else {
            for ($i = 0; $i < count($res); $i++) {
                $res[$i] = Mime_Helper::decodeAddress(str_replace('"', '', $res[$i]));
            }
            return $res;
        }
    }
}

// benchmarking the included file (aka setup time)
if (APP_BENCHMARK) {
    $GLOBALS['bench']->setMarker('Included Mail_Queue Class');
}
?>