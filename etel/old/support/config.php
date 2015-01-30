<?php
/***************************************************************************
File Name 	: config.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Holds the connection info and any user editable items.
Date Created	: Wednesday 19 January 2005 16:13:34
File Version	: 1.9
\\||************************************************************************/

$gateway_id = intval($_REQUEST['gateway_id']);


$support_gateway = $gwInfo;
if($etel_gw_list[$gateway_id]) $support_gateway = $etel_gw_list[$gateway_id];

	if(!$_SESSION["gw_database"]) { 
		@ini_set("session.save_handler", "files"); @session_start();
		$index = $_SESSION["gw_index"];
		if(!$index) $index = "/index.php";
		if($_GET['nr']) header("location:".$index);
		else header("location:".$index."?login_redir=".base64_encode($_SERVER['REQUEST_URI']));
		die();
	}

############ SITE DETAILS ############

	$sitename = $support_gateway["gw_title"].' Support Tickets';	// THIS WILL APPEAR IN THE AUTORESPONDER EMAIL
	$siteurl  = $support_gateway["gw_domain"];		// THIS IS USED IN THE CONFIRMATION EMAILS TO PROVIDE UNSUBSCIRBE LINKS


############ ALLOW SELF REGISTRATION ############

	$allowreg = 'ON';					// ALLOW USER TO SIGN UP THEMSELVES - ON or OFF


############ MYSQL CONFIGURATION ############

	$host = 'localhost';					// HOST OF THE MYSQL OFTEN localhost
	$user = 'etel_root';						// YOUR USERNAME FOR MYSQL
	$pass = 'WSD%780=';						// YOUR PASSWORD FOR MYSQL

	// DATABASE CONFIGURATION

	$data = $_SESSION["gw_database"];				// YOUR DATABASE NAME


############ EMAIL SETTINGS ############

	// CHOOSE SEND METHOD

	$sendmethod	= 'smtp';				// SETS THE SEND METHOD FOR ALL THE MAILINGS
								// COMING OUT OF THIS APP - FOLLOWING ARE OPTIONS
								// IF YOU ARE GETTING ERRORS THEN TRY DIFFERENT OPTION
								// smtp - SENDS THE MAIL VIA SOCKETS THROUGH SOCKETHOST
								// sendmail - USES SENDMAIL TO SEND MAIL
								// mail - USES PHP INBUILT MAIL FUNCTION
								// qmail - USES QMAIL TO SEND THROUGH

	// SOCKET OPTIONS FILL THESE IN ANYWAY

	$domain = parse_url($support_gateway['gw_domain']);

	$socketdomain = str_replace("www.","",$domain['host']);
	$socketfrom	= $support_gateway["gw_emails_support"];		// EMAIL ADDRESS TO APPEAR IN FROM / REPLY FIELD
	$socketfromname	= $support_gateway["gw_title"].' Support';				// NAME TO APPEAR IN FROM FIELD / REPLY FIELD
	$sockethost	= 'localhost';			// SMTP HOST TO SEND THE EMAILS VIA THE SMTP SOCKET
								// THIS MAY SIMPLY BE localhost
								
	
	// USE SMTP AUTHENTICATION

	$smtpauth	= 'TRUE';				// SET THIS TO TRUE IF YOUR SMTP SERVER REQUIRES AUTHENTICATION
	$smtpauthuser	= 'mailer+etelegate.com';				// SMTP USERNAME - USUALLY THE SAME AS YOUR MAILBOX
	$smtpauthpass	= 'sda90f87sdfa';				// SMTP PASSWORD - USUALLY THE SAME AS YOUR MAILBOX

############ EMAIL FLAGS ############

	$emailclose = 'FALSE';					// EMAIL THE USER IF THE ADMIN CLOSES THE TICKET
	$emailuser  = 'FALSE';					// EMAIL THE USER IF THEY MAKE A NEW TICKET OR RESPONSE


############ DETERMINE SOME SITE DESIGN ELEMENTS ############

	$maintablewidth = '97%';				// DO IN '%' OR BY PIXELS IE '40%' OR '400'
	$maintablealign = 'center';				// OPTIONS - Center, Right etc.
	$ticket_display	= '50';					// NUMBER OF TICKETS TO SHOW ON A PAGE

	// MAIN TITLE BAR

	$background	= '#AABBDD';				// THIS WILL CHANGE THE COLOUR OF THE MAIN BAR
	$backover	= '#AABBDD';				// THIS WILL CHANGE THE COLOUR OF THE MAIN BAR
	$backout	= '#FFF000';				// THIS WILL CHANGE THE COLOUR OF THE MAIN BAR

	// DATE FORMATS

	$dformat	= 'm-d-Y';				// THIS GIVES DD-MM-YYYY - US SHOULD CHANGE TO m-d-Y
	$dformatemail	= 'D d M Y H:i:s';			// CHANGES THE DATE FORMAT WITHIN THE EMAILS


############ DYNAMIC LANGUAGE SELECTION ############

	$langdefault = 'eng';					// THIS WILL BE THE DEFAULT IF NO LANG VARIABLE IS FOUND

#	eng = English						// THESE ARE THE LANGAUGE SETTINGS THEY RESPOND TO THE


############ FILE ATTACHMENT ON TICKETS ############

	$allowattachments = 'TRUE';			// SET TO TRUE OR FALSE DEPENDING ON WHETHER YOU WANT TO ALLOW ATTACHMENTS
	$maxfilesize	  = '1024000';			// IN BYTES

	$uploadpath	  = $etel_root_path.'/support/upload/';

								// CHANGE THE 'DEMO' FOLDER TO YOUR OWN LOCATION
								// ABSOLUTE PATH TO THE UPLOAD FOLDER

	$relativepath	  = "https://".$_SERVER['HTTP_HOST'].'/support/upload/';

								// URL TO THE UPLOAD FOLDER

	$filetypes = 	array (
				'image/pjpeg'		=> '.jpg',
				'image/jpeg'		=> '.jpg',
				'image/gif'		=> '.gif',
				'image/bmp'		=> '.bmp',
				'image/png'		=> '.png',
				'application/text'	=> '.txt',
				'application/msword'	=> '.doc',
				'application/pdf'	=> '.pdf'
				);

	$allowedtypes = array(
				'image/pjpeg',			// ALLOWED TYPES COPY THE RELEVANT ITEM TO THIS STRING
				'image/jpeg',
				'image/gif',
				'image/bmp',
				'image/png',
				'application/text'	=> '.txt',
				'application/msword',
				'application/pdf'
				);


############ DEMO MODE ############

	$demomode = 'OFF';					// PUT THE DEMO MODE ON - ACCESS VIA demoredirect.php
?>