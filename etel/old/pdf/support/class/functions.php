<?php
/***************************************************************************
File Name 	: functions.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Holds the connection info and any functions.
Date Created	: Wednesday 19 January 2005 16:13:30
File Version	: 1.9
\\||************************************************************************/

	$version = 'v1.9';
//	error_reporting(E_ALL ^ E_NOTICE);



// function to update the status of a thread whenever it is called
function update_thread_status($id) {
/*
	$logfile = 'update_thread_status.log';
	if (is_writeable($logfile)) 
		if (!$handle = fopen($logfile, 'a')) {
			echo "Cannot open file ($filename)";
			exit;
		} // end if
	else
		echo "The filename is not writeable";
		
	fwrite($handle, "\n\nBegin Log\n".time()."\n");
*/
	// select the information from the ticket that was passed to us
	$sql = "
		SELECT 
			tickets_id as id, 
			tickets_status as status, 
			tickets_child as child,	
			tickets_responses
		FROM 
			tickets_tickets 
		WHERE 
			tickets_id=$id
		";	
	
	$result = mysql_query($sql);
	
	if (!$result) {
		$error = "Could not successfully run query ($sql) from DB: " . mysql_error()."\n";
		dieLog( $error);
		die;
	} // end if
	
	if (mysql_num_rows($result) == 0) {
		$error = "ERROR! No such entry found!\n<pre>$sql</pre>";
		dieLog( $error);
		die;
	} // end if
	
	// we should only pull one entry
	if (mysql_num_rows($result) > 1) {
		$error = "GASP! Query found duplicate entries for that ID! We should never get here!\n";
		dieLog( $error);
		die;
	} // end if

	// create an array out of the row we just pulled
	$parent = mysql_fetch_assoc($result); 
	
	// if this is not the parent of the thread, then call the function again using the parent
	if ($parent["child"] != 0) {
		update_thread_status($parent["child"]);
		return;
	} // end if
	
	$sql = "SELECT tickets_id as id, tickets_timestamp as timestamp, tickets_admin as admin 
			FROM tickets_tickets WHERE tickets_child = $id ORDER BY tickets_timestamp DESC";
	$result = mysql_query($sql) or dieLog(mysql_error());
	$parent["tickets_responses"] = mysql_num_rows($result);
	
	$child = mysql_fetch_assoc($result); 
	
	// if the last post was made by an admin, change its status to answered
	if ($child["admin"] == "Admin")
		$parent["status"] = 'Answered';
	else
		$parent["status"] = 'Open';
			
	$sql = "UPDATE tickets_tickets SET tickets_status='" . $parent['status'] . 
			"', tickets_responses=" . $parent['tickets_responses'] . ", tickets_latest=" . $child['timestamp'] . 
			" WHERE tickets_id = " . $parent['id'];
	
	$result = mysql_query($sql) or dieLog($sql."\n".mysql_error());
		
} // end function update_thread_status



	function updateUserInfo()
	{
		global $adminInfo;
		global $resellerInfo;
		global $companyInfo;
		if ($_SESSION["userType"]=="CustomerService")
		{
			$sql = "select * from tickets_users WHERE cs_csuser='".$_SESSION['sessionServiceUserId']."'";
			$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
			if($userInfo = mysql_fetch_assoc($result)) 
			{
				$_SESSION['stu_username'] = $userInfo['tickets_users_username'];
				$_SESSION['stu_password'] = $userInfo['tickets_users_password'];
			}
			else
			{
				$sql = "select * from cs_customerserviceusers WHERE id='".$_SESSION['sessionServiceUserId']."'";
				$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
				if($userInfo = mysql_fetch_assoc($result )) 
				{
					$sql = "INSERT INTO `tickets_users` ( `tickets_users_id` , `tickets_users_name` , `tickets_users_username` , `tickets_users_password` , `tickets_users_email` , `tickets_users_lastlogin` , `tickets_users_newlogin` , `tickets_users_admin` , `tickets_users_status`, `cs_csuser` )
					VALUES ('', 'Service - $userInfo[username]', '$userInfo[username]', '$userInfo[password]', '$userInfo[cs_email]', '0', '0', 'Admin', '1', '$_SESSION[sessionServiceUserId]')
					ON DUPLICATE KEY UPDATE `cs_csuser` = '$_SESSION[sessionServiceUserId]'
					
					;";
					$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
					$_SESSION['stu_username'] = $userInfo['username'];
					$_SESSION['stu_password'] = $userInfo['password'];
				}
	
			}
		}
		else if ($_SESSION["userType"]=="Admin")
		{
			$sql = "select * from tickets_users WHERE tickets_users_username='".$adminInfo['username']."'";
			$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
			if($userInfo = mysql_fetch_assoc($result)) 
			{
				$_SESSION['sta_username'] = $userInfo['tickets_users_username'];
				$_SESSION['sta_name'] = $userInfo['tickets_users_name'];
				$_SESSION['sta_password'] = $userInfo['tickets_users_password'];
				$_SESSION['stu_username'] = $userInfo['tickets_users_username'];
				$_SESSION['stu_password'] = $userInfo['tickets_users_password'];
				$_SESSION['sta_type'] = "Admin";
			}
			else
			{
				$sql = "INSERT INTO `tickets_users` ( `tickets_users_id` , `tickets_users_name` , `tickets_users_username` , `tickets_users_password` , `tickets_users_email` , `tickets_users_lastlogin` , `tickets_users_newlogin` , `tickets_users_admin` , `tickets_users_status`, `cs_userId` )
				VALUES ('', 'Admin - $adminInfo[username]', '$adminInfo[username]', '$adminInfo[password]', '$adminInfo[li_email]', '0', '0', 'Admin', '1', '$_SESSION[sessionlogin]')
				ON DUPLICATE KEY UPDATE `cs_userId` = '$_SESSION[sessionlogin]'
				;";
				$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
				$_SESSION['stu_username'] = $adminInfo['username'];
				$_SESSION['stu_password'] = $adminInfo['password'];
				$_SESSION['sta_username'] = $adminInfo['username'];
				$_SESSION['sta_name'] = "Admin - ".$adminInfo['username'];
				$_SESSION['sta_password'] = $adminInfo['password'];
			}
		}
		else if($_SESSION["userType"]=="Merchant")
		{
			$sql = "select * from tickets_users WHERE cs_userId='".$_SESSION['sessionlogin']."'";
			$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
			if($userInfo = mysql_fetch_assoc($result)) 
			{
				$_SESSION['stu_username'] = $userInfo['tickets_users_username'];
				$_SESSION['stu_password'] = $userInfo['tickets_users_password'];
				$_SESSION['stu_name'] = $userInfo['tickets_users_name'];
				
				if($userInfo['tickets_users_email'] != $companyInfo['email'])
				{
					$sql = "update `tickets_users` set `tickets_users_email`='$companyInfo[email]' where `tickets_users_id`='$userInfo[tickets_users_id]';";
					$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
				}
			}
			else
			{
				foreach($companyInfo as $key=>$data)
					$companyInfo[$key] = quote_smart($data);
				$sql = "INSERT INTO `tickets_users` ( `tickets_users_id` , `tickets_users_name` , `tickets_users_username` , `tickets_users_password` , `tickets_users_email` , `tickets_users_lastlogin` , `tickets_users_newlogin` , `tickets_users_admin` , `tickets_users_status`, `cs_userId`, `cs_gateway_id` )
				VALUES ('', '$companyInfo[companyname]', '$companyInfo[username]', '$companyInfo[password]', '$companyInfo[email]', '0', '0', 'User', '1', '$companyInfo[userId]', '$companyInfo[gateway_id]')
				ON DUPLICATE KEY UPDATE `cs_userId` = '$_SESSION[sessionlogin]'
				;";
				$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
				$_SESSION['stu_username'] = $companyInfo['username'];
				$_SESSION['stu_name'] = $companyInfo['companyname'];
				$_SESSION['stu_password'] = $companyInfo['password'];
			}
		} else if($_SESSION["userType"]=="Reseller")
		{
			$sql = "select * from tickets_users WHERE cs_reseller_id='".$_SESSION['sessionReseller']."'";
			$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
			if($userInfo = mysql_fetch_assoc($result)) 
			{
				$_SESSION['stu_username'] = $userInfo['tickets_users_username'];
				$_SESSION['stu_name'] = $userInfo['tickets_users_name'];
				$_SESSION['stu_password'] = $userInfo['tickets_users_password'];
			}
			else
			{
				foreach($resellerInfo as $key=>$data)
					$resellerInfo[$key] = quote_smart($data);
				$sql = "INSERT INTO `tickets_users` ( `tickets_users_id` , `tickets_users_name` , `tickets_users_username` , `tickets_users_password` , `tickets_users_email` , `tickets_users_lastlogin` , `tickets_users_newlogin` , `tickets_users_admin` , `tickets_users_status`, `cs_reseller_id` )
				VALUES ('', '$resellerInfo[reseller_companyname]', '$resellerInfo[reseller_username]', '$resellerInfo[reseller_password]', '$resellerInfo[reseller_email]', '0', '0', 'User', '1', '$_SESSION[sessionReseller]')
				ON DUPLICATE KEY UPDATE `cs_reseller_id` = '$_SESSION[sessionReseller]'
				;";
				$result = mysql_query($sql) or dieLog(mysql_error()." $sql");
				$_SESSION['stu_username'] = $resellerInfo['reseller_username'];
				$_SESSION['stu_name'] = $resellerInfo['reseller_companyname'];
				$_SESSION['stu_password'] = $resellerInfo['reseller_password'];

			}
		}
	}

#############################################################################################
######## FUNCTION TO CLEAN INPUTTED USER DATA  BEFORE It GOES LIVE OR IN A DATABASE #########
#############################################################################################

	Function Clean_It($vName)
		{
		$vName = stripslashes(trim($vName));
		$vName = htmlspecialchars($vName);
		return $vName;
		}


#############################################################################################
############################ USECOLOR FUNCTION FOR COLOURED ROWS ############################
#############################################################################################

	Function UseColor()
		{
		$trcolor1 = '#F4FAFF';
		$trcolor2 = '#FFFFFF';
		static $colorvalue;

		IF($colorvalue == $trcolor1)
			{
			$colorvalue = $trcolor2;
			}
		ELSE
			{
			$colorvalue = $trcolor1;
			}

		return($colorvalue);
		}


#############################################################################################
#################################### DATABASE CONNECTION ####################################
#############################################################################################

	IF ($link = mysql_connect($host, $user, $pass))
		{
		IF (!mysql_select_db($data))
			{
			echo 'This script has connected to the MySQL but could not connect to the Database - change database name in config.';
			exit();
			}
		}
	ELSE
		{
		echo 'This script could not connect to the MySQL server change host/user/pass values in config.';
		exit();
		}


#############################################################################################
#################################### SEND EMAIL FUNCTION ####################################
#############################################################################################

	Function supportSendMail(	$email,
				$name,
				$subject,
				$message,
				$response_flag = false
				)
		{
		Global $sendmethod, $sockethost, $smtpauth, $smtpauthuser, $smtpauthpass, $socketfrom, $socketfromname, $socketreply, $socketreplyname;

		include_once ('class.phpmailer.php');

		$mail  = new phpmailer();

		IF (file_exists('class/language/phpmailer.lang-en.php'))
			{
			$mail -> SetLanguage('en', 'class/language/');
			}
		ELSE
			{
			$mail -> SetLanguage('en', '../class/language/');
			}

		IF (isset($sendmethod) && $sendmethod == 'sendmail')
			{
			$mail -> IssupportSendMail();
			}
		ELSEIF (isset($sendmethod) && $sendmethod == 'smtp')
			{
			$mail -> IsSMTP();
			}
		ELSEIF (isset($sendmethod) && $sendmethod == 'mail')
			{
			$mail -> IsMail();
			}
		ELSEIF (isset($sendmethod) && $sendmethod == 'qmail')
			{
			$mail -> IsQmail();
			}

		$mail -> Host = $sockethost;

		IF ($smtpauth == 'TRUE')
			{
			$mail -> SMTPAuth = true;
			$mail -> Username = $smtpauthuser;
			$mail -> Password = $smtpauthpass;
			}

		IF (!$response_flag && isset($_GET['caseid']) && ($_GET['caseid'] == 'NewTicket' || $_GET['caseid'] == 'view'))
			{
			$mail -> From     = $email;
			$mail -> FromName = $name;
			$mail -> AddReplyTo($email, $name);
			}
		ELSE
			{
			$mail -> From     = $socketfrom;
			$mail -> FromName = $socketfromname;
			$mail -> AddReplyTo($socketreply, $socketreplyname);
			}

		$mail -> IsHTML(False);
		$mail -> Body    = $message;
		$mail -> Subject = $subject;

		IF (!$response_flag && isset($_GET['caseid']) && ($_GET['caseid'] == 'NewTicket' || $_GET['caseid'] == 'view'))
			{
			$mail -> AddAddress($socketfrom, $socketfromname);
			}
		ELSE
			{
			$mail -> AddAddress($email, $name);
			}

		IF(!$mail -> Send())
			{
			return ('Error: '.$mail -> ErrorInfo);
			}
		ELSE
			{
			return ('Email Sent. '.$mail -> ErrorInfo);
			}

		$mail -> ClearAddresses();
		}


#############################################################################################
########################### CHECK USER IS LOGGED IN FOR CUSTOMERS ###########################
#############################################################################################

	Function AuthUser($user, $pass)
		{
		$query = "	SELECT tickets_users_password
				FROM tickets_users
				WHERE tickets_users_username = '$user'
				AND tickets_users_status = '1'";

		$result = mysql_query($query);

		IF (!$result)
			{
			return 0;
			}

		IF (($row = mysql_fetch_array($result)) && ($pass == $row['tickets_users_password'] && $pass != ''))
			{
			return 1;
			}
		ELSE
			{
			return 0;
			}
		}


#############################################################################################
################################## FUNCTION PAGE TITLE BAR ##################################
#############################################################################################

	Function PageTitle($text)
		{
		Global $maintablewidth, $maintablealign, $background;
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr bgcolor="<?php echo $background ?>">
			<td class="text"><?php echo $text ?></td>
		  </tr>
		</table>
<?php
		}


#############################################################################################
############################# SHOW THE NEXT AND PREVIOUS LINKS ##############################
#############################################################################################

	Function ShowPaging($page, $prevpage, $nextpage, $numpages, $display)
		{
		Global $maintablewidth, $maintablealign, $background;

		$page = explode('&amp;display', $page);
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr bgcolor="#EEEEEE" valign="middle" align="center">
			<td class="boxborder text" width="75">
<?php
		IF ($prevpage)
			{
?>
			<a href="<?php echo $page['0'] ?>&amp;display=<?php echo $prevpage ?>">&#171;&nbsp;Previous</a>
<?php
			}
		ELSE
			{
?>
			&#171;&nbsp;Previous
<?php
			}
?>
			</td>
			<td class="boxborder text">
<?php
		FOR ($i = '1'; $i <= $numpages; $i++)
			{
			IF ($i != $display)
				{
?>
				<a href="<?php echo $page['0'] ?>&amp;display=<?php echo $i ?>" class="pagelinks"><?php echo $i ?></a>
<?php
				}
			ELSE
				{
?>
				[<b><?php echo $i ?></b>]
<?php
				}
			}
?>
			</td>
			<td class="boxborder text" width="75">
<?php
		IF ($display != $numpages)
			{
?>
			<a href="<?php echo $page['0'] ?>&amp;display=<?php echo $nextpage ?>">Next&nbsp;&#187;</a>
<?php
			}
		ELSE
			{
?>
			Next&nbsp;&#187;
<?php
			}
?>
			</td>
		  </tr>
		</table>
<?php
		}


#############################################################################################
########## FILE UPLOAD FORM FUNCTION - USE FUNCTION AS THIS IS IN MULTIPLE PLACES ###########
#############################################################################################

	Function FileUploadForm()
		{
		GLOBAL $maxfilesize;
?>
		<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
		  <tr bgcolor="#AABBDD">
			<td class="boxborder text"><b>Optional File Attachment (Ignore this if not applicable)</b></td>
		  </tr>
		  <tr>
			<td class="boxborder" align="center">
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxfilesize ?>" />
			<input type="file"   name="userfile" size="30" />
			</td>
		  </tr>
		</table>
<?php
		}


#############################################################################################
######################## CHECK ERROR STATUSES ON THE UPLOADED FILES #########################
#############################################################################################

	Function FileUploadsVerification($userfile, $newfilename)
		{
		GLOBAL $filetypes, $allowedtypes, $uploadpath, $relativepath, $maintablewidth, $maintablealign;

	// CHECK ERROR STATUSES ON THE UPLOADED FILES

		IF ($_FILES['userfile']['error'] == '4')
			{
			$msg = 'No attachment uploaded';
			}
		ELSEIF ($_FILES['userfile']['error'] == '2')
			{
			$msg = 'This file exceeds the Maximum allowable size within this tool.';
			}
		ELSEIF ($_FILES['userfile']['error'] == '1')
			{
			$msg = 'This file exceeds the PHP upload size.';
			}
		ELSEIF ($_FILES['userfile']['error'] == '3')
			{
			$msg = 'Sorry we could only partially upload htis file please try again.';
			}

	// CHECK TO MAKE SURE THE UPLOADED FILE IS OF A FILE WE ALLOW AND GET THE NEWFILE EXTENSION

		ELSEIF (!in_array($_FILES['userfile']['type'], $allowedtypes))
			{
			$msg = 'The file that you uploaded was of type '.$_FILES['userfile']['type'].' which
				is not allowed,	you are only allowed to upload files of the type:';

			WHILE ($type = current($allowedtypes))
				{
				$msg .= '<br />'.$filetypes[$type].' ('.$type.')';
				next($allowedtypes);
				}
			}

	// IF FILE IS NOT OVER SIZE AND IS CORRECT TYPE THEN CONTINUE WITH PROCESS

		ELSEIF ($_FILES['userfile']['error'] == '0')
			{

	// GET THE EXTENSION FOR THE UPLOADED FILE

			$type1       = $_FILES['userfile']['type'];
			$extension   = $filetypes["$type1"];
			$newfilename = $newfilename.$extension;

	// PRINT OUT THE RESULTS

			$msg = '<p><b>Attachment Uploaded</b> - You submitted: '.$_FILES['userfile']['name'].'
				SIZE: '.$_FILES['userfile']['size'].' bytes -
				TYPE: '.$_FILES['userfile']['type'];

			move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadpath.$newfilename);
			}
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" Cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr bgcolor="#AACCEE">
			<td class="text"><?php echo $msg ?></td>
		  </tr>
		</table>
<?php
		}
		
?>