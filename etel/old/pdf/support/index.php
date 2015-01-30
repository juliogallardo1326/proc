<?
/***************************************************************************
File Name 	: index.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Brings together all the elements of the Support Tickets app.
Date Created	: Wednesday 19 January 2005 16:13:23
File Version	: 1.9
\\||************************************************************************/

#############################################################################################
############################### CURRENT CASEID'S ON THIS PAGE ###############################
#############################################################################################

	// home		- LINE 401
	// view		- LINE 669
	// NewTicket	- LINE 1018


#############################################################################################
################## INCLUDE THE CONFIG, FUNCTIONS, LANGUAGE AND HEADER FILE ##################
#############################################################################################

	// STARTS THE SESSION FOR THE USERS SO LOGIN IS TRACKED THROUGH THE PAGES

	session_start();

	$disablePostChecks=true;
	// INCLUDE THE CONFIG AND FUNCTIONS AND LANGUAGE FILE
	include_once ('config.php');
	include_once ('class/functions.php');
	include_once ("../includes/dbconnection.php");
	include_once ('../includes/function.php');
	

	if (!isset($_REQUEST['lang']))
		$_REQUEST['lang'] = $langdefault;
	
	if (!isset($_GET['action']))
		$_GET['action'] = 'Login';

	if (!isset($_GET['issue']))
	{
		if (!isset($_POST['issue']))
			$issue = 0;
		else
			$issue = $_POST['issue'];
	}
	else
		$issue = $_GET['issue'];
		
	$transactionId=$_SESSION['cs_found_reference_number'];
	$transactionInfo=getTransactionInfo($transactionId,false,"reference_number","");
		
	include_once ('language/'.$_REQUEST['lang'].'.php');
	include_once ('header.php');

	updateUserInfo();


#############################################################################################
####################### AUTH LOGIN AND LOGOUT SYSTEM REQUIRES SESSIONS ######################
#############################################################################################

	// LOGOUT
	if (isset($_GET['action']) && $_GET['action'] == 'Logout')
	{
		unset($_SESSION['stu_username']);
		unset($_SESSION['stu_password']);
		$_GET['action'] = 'Login';
	}

	// WHAT TO DO if NO USERNAME OR PASSWORD IS SET

	if (!isset($_SESSION['stu_username']) && !isset($_SESSION['stu_password']))
	{
?>
<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
	<tr>
		<td class="boxborder text" bgcolor="<? echo $background ?>"><? echo $_GET['action'] ?></td>
		<td class="boxborder list-menu" width="15%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?action=Login"><? echo $text_login ?></a></td>
<?
		if (isset($allowreg) && $allowreg == 'ON')
		{
?>
		<td class="boxborder list-menu" width="15%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?action=Register"><? echo $text_register ?></a></td>
<?
		}
?>
		<td class="boxborder list-menu" width="15%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?action=Resend"><? echo $text_resend ?></a></td>
		<td class="boxborder list-menu" width="10%"><a href="javascript:popwindow('help.php#userpage','top=150,left=300,width=400,height=400,buttons=no,scrollbars=YES,location=no,menubar=no,resizable=no,status=no,directories=no,toolbar=no')"><? echo $text_help ?></a></td>
	</tr>
</table>

<?
// CREATE LOGIN AREA

	if ($_GET['action'] == 'Login')
	{
		if (isset($_GET['sub']))
		{
			if ((AuthUser($_REQUEST['username'], $_REQUEST['password'])) || (isset($_COOKIE['demomode']) && $demomode == 'ON' && $_POST['username'] == 'demo' && $_POST['password'] == 'demo'))
			{
				$_SESSION['stu_username'] = $_POST['username'];
				$_SESSION['stu_password'] = $_POST['password'];

				// LOG THE LOGIN TIMES ONLY DO THIS WHEN NOT IN DEMO MODE
				if (!isset($_COOKIE['demomode']) || $demomode != 'ON')
				{
					// SELECT THE LAST LOGGED IN FIELD
					$query = "
							SELECT tickets_users_newlogin
							FROM tickets_users
							WHERE tickets_users_username = '".$_SESSION['stu_username']."'";

					$result = mysql_query($query);
					$row    = mysql_fetch_array($result);

					// UPDATE THE NEW LOGGED IN FIELD IN THE USER ACCOUNT

					$query = "	UPDATE tickets_users
					SET
					tickets_users_newlogin	     = '".mktime()."',
					tickets_users_lastlogin	     = '".$row['0']."'
					WHERE tickets_users_username = '".$_SESSION['stu_username']."'";
					
					$result = mysql_query($query);
				}
?>
<meta http-equiv="refresh" content="0;url=<? echo $_SERVER['PHP_SELF'] ?>" />
<?
			}
			else
			{
?>
<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
	<tr>
		<td class="text">
<? echo $text_loginpage ?>
			<input type="button" value="<? echo $text_loginback ?>" onclick="history.back()" />
		</td>
	</tr>
</table>
<?
			}
		}
		else
		{
?>
<form action="<? echo $_SERVER['PHP_SELF'] ?>?action=Login&amp;sub=verify" method="post">
<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
	<tr>
		<td class="text" align="center"><br />
<? echo $text_username ?>: <input name="username" size="20"
<?
				if (isset($_COOKIE['demomode']) && $demomode == 'ON')
					echo 'value="demo"';
?>
					/> 
<? echo $text_password ?>: <input type="password" name="password" size="20"
<?
				if (isset($_COOKIE['demomode']) && $demomode == 'ON')
					echo 'value="demo"';
?>
					/> <input type="submit" name="form" value="<? echo $text_login ?>" /><br /><br />
		</td>
	</tr>
</table>
</form>
<?
		}
	}

	// DEAL WITH THE RESEND REQUESTS
	if ($_GET['action'] == 'Resend')
	{
		if (isset($_GET['sub']))
		{
			$query = "	SELECT tickets_users_name, tickets_users_username, tickets_users_password
						FROM tickets_users
						WHERE tickets_users_email = '".$_POST['email']."'";
	
			$result = mysql_query($query);
	
			if (mysql_num_rows($result) > '0')
			{
				$row = mysql_fetch_array($result);
	// OUTGOING EMAIL MESSAGE TO USERS WHO REQUEST RESEND DETAILS

					$message  = "Dear ".$row['tickets_users_name']."\n\n";
					$message .= "Below are the requested Account Details.\n";
					$message .= "Username: ".$row['tickets_users_username']."\n";
					$message .= "Password: ".$row['tickets_users_password']."\n\n";
					$message .= "Kind Regards\n";
					$message .= "Customer Care at ".$socketfromname."\n";
?>
<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
	<tr>
		<td class="text"><? echo supportSendMail($_POST['email'], $row['tickets_users_name'], 'Account Details Request', $message) ?></td>
	</tr>
</table>
<?
			}
			else
			{
?>
<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
	<tr>
		<td class="text"><? echo $text_resenderror ?><input type="button" value="<? echo $text_resendback ?>" onclick="history.back()" /></td>
	</tr>
</table>
<?
			}
		}
		else
		{
?>
<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
	<tr>
		<td class="text"><? echo $text_resendpage ?></td>
	</tr>
</table>
<br />

<form action="<? echo $_SERVER['PHP_SELF'] ?>?action=Resend&amp;sub=verify" method="post">
<table width="300" cellspacing="1" cellpadding="1" class="boxborder" align="center">
	<tr>
		<td width="100%" class="boxborder text"><? echo $text_email ?>:</td>
		<td class="boxborder"><input name="email"
<?
			if (isset($_POST['email']))
			{
?>
					value="<? echo $_POST['email'] ?>"
<?
			}
?>
					size="42" /></td>
	</tr>
</table>

<table width="300" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<td align="right"><input type="submit" value="<? echo $text_submit ?>" /></td>
	</tr>
</table>
</form>
<?
		}
	}

	// DEAL WITH THE USER SELF REGISTRY OPTIONS
	if ($_GET['action'] == 'Register')
	{
		if (isset($_GET['sub']) && $allowreg == 'ON')
		{
			if ($_POST['name'] == '' || !eregi('^[0-9a-z]{6,16}$', $_POST['username']) || !eregi('^[0-9a-z]{6,16}$', $_POST['password']) || $_POST['email'] == '' || !ereg('^..*\@.+\..+[A-Za-z0-9]$', $_POST['email']))
			{
?>
<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
	<tr>
		<td class="text"><? echo $text_regpageerr ?>
			<input type="button" value="<? echo $text_regpagback ?>" onclick="history.back()" />
		</td>
	</tr>
</table>
<?
					}
				else
					{
	// TEST FOR DUPLICATE USERNAME
					$query = "	SELECT tickets_users_id
							FROM tickets_users
							WHERE tickets_users_username = '".$_POST['username']."'
							LIMIT 0,1";

					$result = mysql_query($query);

					if (mysql_num_rows($result) <= '0')
						{
						$query = "	INSERT INTO tickets_users
								SET
								tickets_users_name     = '".$_POST['name']."',
								tickets_users_username = '".$_POST['username']."',
								tickets_users_password = '".$_POST['password']."',
								tickets_users_email    = '".$_POST['email']."'";

						if ($result = mysql_query($query))
							{
	// REGISTRATION EMAIL
							$message  = 'Dear '.$_POST['name']."\n\n";
							$message .= "Thank you for registering.\n";
							$message .= 'Username: '.$_POST['username']."\n";
							$message .= 'Password: '.$_POST['password']."\n";
							$message .= 'Email: '.$_POST['email']."\n\n";
							$message .= "Kind Regards\n";
							$message .= 'Customer Care at '.$socketfromname."\n";

							$msg = $text_regconf.supportSendMail($_POST['email'], $_POST['name'], $text_regsubject, $message);
							}
						}
					else
						{
						$msg = $text_regusererr.'<input type="button" value="'.$text_regpagback.'" onclick="history.back()" />';
						}
?>
					<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
					  <tr>
						<td class="text"><? echo $msg ?></td>
					  </tr>
					</table><br />
<?
					}
				}
			else
				{
?>
				<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
				  <tr>
					<td class="text"><? echo $text_regpage ?></td>
				  </tr>
				</table><br />

				<form action="<? echo $_SERVER['PHP_SELF'] ?>?action=Register&amp;sub=verify" method="post">
				<table width="300" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td class="boxborder text"><? echo $text_regname ?></td>
					<td class="boxborder"><input name="name" size="35" /></td>
				  </tr>
				  <tr>
					<td class="boxborder text"><? echo $text_reguser ?></td>
					<td class="boxborder"><input name="username" size="35" /></td>
				  </tr>
				  <tr>
					<td class="boxborder text"><? echo $text_regpass ?></td>
					<td class="boxborder"><input type="password" name="password" size="35" /></td>
				  </tr>
				  <tr>
					<td width="100%" class="boxborder text"><? echo $text_regemail ?></td>
					<td class="boxborder"><input name="email" size="35" /></td>
				  </tr>
				</table>

				<table width="300" cellspacing="0" cellpadding="2" align="center">
				  <tr>
					<td align="right"><input type="submit" value="<? echo $text_regsubmit ?>" /></td>
				  </tr>
				</table>
				</form>
<?
				}
			}

		include_once ('footer.php');

		Exit();
		}


#############################################################################################
################ MAKE SURE THE RIGHT CASEID IS ENTERED OR DEFAULT TO HOME ID ################
#############################################################################################

	if (!isset($_GET['caseid']) || $_GET['caseid'] == '' || $_GET['caseid'] != 'home' && $_GET['caseid'] != 'view' && $_GET['caseid'] != 'NewTicket')
		{
		$_GET['caseid'] = 'home';
		}


#############################################################################################
########################### DISPLAY THE PAGE TITLE AND NAVIGATION ###########################
#############################################################################################
?>
	<form action="<? echo $_SERVER['PHP_SELF'] ?>?caseid=home" method="post">
	<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<? echo $maintablealign ?>">
	  <tr>
		<td valign="bottom" align="right" class="text" style="padding:2px">Search Tickets:
		<input name="keywords" size="24" onfocus="javascript:this.value=''" value="Search Ticket Subject" />
		<input type="submit" value="Go" />
		</td>
	  </tr>
	</table>
	</form>

	<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
	  <tr>
		<td class="boxborder text" bgcolor="<? echo $background ?>"><a href="<? echo $_SERVER['PHP_SELF'] ?>"><? echo $text_titlelink ?></a> - <? echo $text_title ?></td>
		<td class="boxborder list-menu" width="15%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket"><? echo $text_titlereq ?></a></td>
		<td class="boxborder list-menu" width="15%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Open"><? echo $text_titleope ?></a></td>
		<td class="boxborder list-menu" width="15%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Closed"><? echo $text_titleclo ?></a></td>
		<td class="boxborder list-menu" width="10%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?caseid=home&amp;action=Logout"><? echo $text_titlelog ?></a></td>
	  </tr>
	</table>

<?
#############################################################################################
############## HOME DEFAULT CASE THIS DEALS WITH THE DISPLAYING OF ANY TICKETS ##############
#############################################################################################

	SWITCH ($_GET['caseid'])
		{
		CASE 'home':

			if (!isset($_GET['order']) && !isset($_POST['keywords']))
				{
				$_GET['order'] = 'Open';
				}

	// PROCESS THE FUNCTIONS WHEN THE CHECKBOXES ARE CHECKED - IE OPEN CLOSE TICKET

			if (isset($_POST['status']))
				{
				if (isset($_POST['ticket']))
					{
					FOREACH ($_POST['ticket'] AS $ticketid)
						{
						$query = "	UPDATE tickets_tickets
								SET tickets_status = '".$_POST['status']."'
								WHERE tickets_id   = '".$ticketid."'";

						if (mysql_query($query))
							{
							$msg = 'Ticket '.$_POST['status'];
							}
						else
							{
							$msg = 'This could not be done at this time';
							}
						}
					}
				else
					{
					$msg = 'Please select a Ticket.';
					}
?>
				<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
				  <tr bgcolor="#AACCEE">
					<td class="text"><? echo $msg ?></td>
				  </tr>
				</table>
<?
				}

	// QUERY TO SELECT THE TICKETS LISTING - THIS CAN BE CHANGED TO OPEN OR CLOSED ONLY
	// DEPENDING ON THE LINK THAT IS HIT ON THE NAV BAR - HOME PAGE DEFAULTS TO BOTH.

			$query = "	SELECT  tickets_id, tickets_subject, tickets_timestamp, tickets_status,
						tickets_status_name, tickets_status_color, tickets_categories_name
					FROM tickets_tickets a, tickets_status b, tickets_categories c
					WHERE a.tickets_username = '".$_SESSION['stu_username']."'
					AND a.tickets_child = '0'
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id";
			if (isset($_GET['order']))
			{
				if($_GET['order']!='Closed') $order = "a.tickets_status != 'Closed'";
				else $order = "a.tickets_status = 'Closed'";
				$query .= " AND $order";
				$addon  = '&amp;order='.$_GET['order'];
			}

			elseif (isset($_POST['keywords']))
			{
				$query .= " AND a.tickets_subject LIKE '%".$_POST['keywords']."%'";
				$addon  = '';
			}

			$query .= '	ORDER BY a.tickets_id DESC, a.tickets_timestamp DESC';

	// SET PAGE NUMBER if NONE SPECifIED ASSUME IT IS EQUAL TO ONE

			$result       = mysql_query($query);
			$totaltickets = mysql_num_rows($result);

			$per_page = $ticket_display;

			if (!isset($_GET['display']))
				{
		   		$_GET['display'] = '1';
				}

			$prev_page = $_GET['display'] - 1;
			$next_page = $_GET['display'] + 1;
	// SET UP PAGE
			$page_start = ($per_page * $_GET['display']) - $per_page;

			$num_rows = $totaltickets;

			if ($num_rows <= $per_page)
				{
				$num_pages = '1';
				}
			elseif (($num_rows % $per_page) == '0')
				{
				$num_pages = ($num_rows / $per_page);
				}
			else
				{
				$num_pages = ($num_rows / $per_page) + 1;
				}

			$num_pages = (int) $num_pages;

	// DISPLAY RESULTS

			$query  = $query . " LIMIT $page_start, $per_page";
			$result = mysql_query($query);
?>
			<div style="padding-top:5px"></div>
<?
			if ($totaltickets > '0')
				{
				ShowPaging(	$_SERVER['PHP_SELF'].'?'.htmlentities($_SERVER['QUERY_STRING']),
						$prev_page,
						$next_page,
						$num_pages,
						$_GET['display']
						);
				}
?>
			<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
			  <tr bgcolor="<? echo $background ?>">
				<td class="boxborder text"><? echo $text_listtitle ?>
<?
			if ($totaltickets > '0')
				{
				echo ' '.$totaltickets.' - '.$text_listmsg;
				}
			else
				{
				echo ' 0';
				}
?>
				</td>
			  </tr>
			</table>
<?
			if ($totaltickets > '0')
				{
?>
				<script language="javascript" type="text/javascript">
				<!--
				function check_all()
					{
					for (var c = 0; c < document.myform.elements.length; c++)
					  	{
				  		if (document.myform.elements[c].type == 'checkbox')
						    	{
							if(document.myform.elements[c].checked == true)
								{
								document.myform.elements[c].checked = false;
								}
								else
									{
									document.myform.elements[c].checked = true;
									}
							}
						}
					}
				// -->
				</script>

				<form name="myform" action="index.php?caseid=home<? echo $addon ?>" method="post">
				<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
				  <tr align="center" bgcolor="<? echo $background ?>">
					<td class="boxborder text" onclick="check_all();" style="cursor:pointer"><b><u>All</u></b></td>
					<td class="boxborder text"><b>Ticket ID</b></td>
					<td class="boxborder text"><b>Replies</b></td>
					<td class="boxborder text"><b><? echo $text_listsub ?></b></td>
					<td class="boxborder text"><b>Date / Time</b></td>
					<td class="boxborder text"><b><? echo $text_listurg ?></b></td>
					<td class="boxborder text"><b>Department</b></td>
					<td class="boxborder text"><b><? echo $text_liststa ?></b></td>
				  </tr>
<?
	// LOOP THROUGH THE REQUESTS FOR THE USERS ACCOUNT

				WHILE ($row = mysql_fetch_array($result))
					{

	// QUERY TO GET THE AMOUNT OF REPLIES TO A CERTAIN TICKET AND DATE OF LAST ENTRY

					$queryA = "	SELECT COUNT(*) AS ticket_count, MAX(tickets_timestamp) AS date, tickets_users_lastlogin
							FROM tickets_tickets a, tickets_users b
							WHERE tickets_child = '".$row['tickets_id']."'
							AND a.tickets_username = b.tickets_users_username
							GROUP BY tickets_child";

					$resultA = mysql_query($queryA);
					$rowA    = mysql_fetch_array($resultA);

					if ($rowA['ticket_count'] <= '0')
						{
						$rowA['ticket_count'] = '0';
						}
?>
					<tr align="center" bgcolor="<? echo UseColor() ?>">
						<td class="boxborder"><input type="checkbox" name="ticket[]" value="<? echo $row['tickets_id'] ?>" /></td>
						<td class="boxborder list-menu"><a href="<? echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<? echo $row['tickets_id'] ?>"><? echo $row['tickets_id'] ?></a></td>
						<td class="boxborder text">[<? echo $rowA['ticket_count'] ?>]
<?
					if (isset($rowA['date']) && ($rowA['date'] > $rowA['tickets_users_lastlogin']))
						{
						echo '<img src="images/new_reply.gif" border="0" />';
						}
?>
						</td>
						<td class="boxborder text"><? echo $row['tickets_subject'] ?></td>
						<td class="boxborder text"><? echo date($dformat, $row['tickets_timestamp']).' '.date('H:i:s', $row['tickets_timestamp']) ?></td>
						<td class="boxborder text" bgcolor="#<? echo $row['tickets_status_color'] ?>"><? echo $row['tickets_status_name'] ?></td>
						<td class="boxborder text"><? echo $row['tickets_categories_name'] ?></td>
						<td class="boxborder text">
<?
					if ($row['tickets_status'] == 'Closed')
						{
						echo '<span style="color:#FF0000">';
						}
					else
						{
						echo '<span style="color:#000000">';
						}
?>
					<? echo $row['tickets_status'] ?></span></td>
						  </tr>
<?
					}
?>
				  <tr>
					<td colspan="8">
					<select name="status">
					<option value="Open">Open</option>
					<option value="Closed">Closed</option>
					</select>
					<input type="submit" name="sub" value="Go">
					</td>
				  </tr>
				</table>
				</form>
<?
				ShowPaging(	$_SERVER['PHP_SELF'].'?'.htmlentities($_SERVER['QUERY_STRING']),
						$prev_page,
						$next_page,
						$num_pages,
						$_GET['display']
						);
				}

	// if THERE ARE NO TICKETS TO SHOW THEN PLACE A DEFAULT MESSAGE

			else
				{
				if (isset($_POST['keywords']))
					{
					$msg = $text_searcherr;
					}
				else
					{
					$msg = $text_listnon;
					}
?>
				<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
				  <tr>
					<td class="text"><? echo $msg ?></td>
				  </tr>
				</table>
<?
				}
		BREAK;


#############################################################################################
######## VIEW A TICKET - VALID if THE USER CLICKS LINK FROM SEARCH OR HOME LISTINGS #########
#############################################################################################

		CASE 'view':

	// CLOSE OR REOPEN A TICKET

			if (isset($_GET['closesub']))
				{
				$query = "	UPDATE tickets_tickets
						SET
						tickets_status	 = '".$_GET['closesub']."'
						WHERE tickets_id = '".$_GET['ticketid']."'";

				if (mysql_query($query))
					{
					$msg = 'Ticket '.$_GET['closesub'];
					}
				else
					{
					$msg = 'This could not be done at this time';
					}
?>
				<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
				  <tr bgcolor="#AACCEE">
					<td class="text"><? echo $msg ?></td>
				  </tr>
				</table>
<?
				}

	// INSERT THE TICKET INTO THE DATABASE AND SEND THE EMAIL

	

			if (isset($_GET['sub']))
				{
				if ($_POST['message'] == '')
					{
					$msg = 'Please complete all the fields';
					}
				else
					{
					$_POST['postsubject']  = Clean_It($_POST['postsubject']);
					$_POST['tickets_urgency']  = Clean_It($_POST['tickets_urgency']);
					$_POST['tickets_category'] = Clean_It($_POST['tickets_category']);
					$_GET['ticketid']      = Clean_It($_GET['ticketid']);
					$_POST['message']      = Clean_It($_POST['message']);
					$_POST['tickets_issue']= Clean_It($_POST['tickets_issue']);
					$_POST['tickets_resolved']= intval($_POST['tickets_resolved']);
					$sql_tickets_status = "";
					if($_POST['tickets_resolved']) $sql_tickets_status = "tickets_status = 'Closed',";
					
					$sql_issue = "";
					if($_POST['tickets_issue']) $sql_issue="tickets_issue = '".addslashes($_POST['tickets_issue'])."',";
					
					$urgency  = explode('|', $_POST['tickets_urgency']);
					$category = explode('|', $_POST['tickets_category']);

					$query = "	INSERT INTO tickets_tickets
							SET
							tickets_username  = '".addslashes($_SESSION['stu_username'])."',
							tickets_subject   = '".addslashes($_POST['postsubject'])."',
							tickets_timestamp = '".mktime()."',
							tickets_urgency   = '".$urgency['0']."',
							tickets_category  = '".$category['0']."',
							tickets_child 	  = '".intval($_GET['ticketid'])."',
							$sql_issue
							$sql_tickets_status
							tickets_question  = '".addslashes($_POST['message'])."'";
					$result = mysql_query($query) or dieLog(mysql_error());
					
					update_thread_status(intval($_GET['ticketid']));
					if (1)
						{

						
				// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

						if ($allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
							{
							FileUploadsVerification("$_FILES(userfile)", mysql_insert_id());
							}

	// EMAIL ADMINISTRATOR THE TICKET NOTifICATION

$query = "	SELECT *
		FROM tickets_categories WHERE tickets_categories_id = '$category[0]'
		ORDER BY tickets_categories_name ASC";

$result = mysql_query($query);
$cat_info = mysql_fetch_assoc($result);
if($cat_info['tickets_categories_email']) $socketfrom = $cat_info['tickets_categories_email'];
if($cat_info['tickets_categories_emailname']) $socketfromname = $cat_info['tickets_categories_emailname'];



						$message  = "Ticket ID:\t ".$_GET['ticketid']."\n";
						$message .= "Name:\t\t ".$_POST['name']."\n";
						$message .= "Subject:\t ".$_POST['postsubject']."\n";
						$message .= "Urgency:\t ".$urgency['1']."\n";
						$message .= "Department:\t ".$category['1']."\n";
						$message .= "Post Date:\t ".date($dformatemail)."\n";
						$message .= "----------------------------------------------------------------------\n";
						$message .= "Message:\n";
						$message .= stripslashes($_POST['message'])."\n";
						$message .= "----------------------------------------------------------------------\n\n\n";
						$message .= "Previous Thread Messages (Latest First):\n";
						$message .= "----------------------------------------------------------------------\n";

	// LOOP THROUGH THE PREVIOUS MESSAGES AND ADD DATA REGARDING QUESTION TIME AND ATTACHMENT

						FOR ($i = COUNT($_POST['ticketquestion']) - 1; $i >= '0'; $i--)
							{
							$message .= $_POST['postedby'][$i]." - ".$_POST['postdate'][$i]."\n";
							$message .= stripslashes($_POST['ticketquestion'][$i]);

							if (isset($_POST['attachment'][$i]) && $_POST['attachment'][$i] != '')
								{
								$message .= "\nAttachment - ".$_POST['attachment'][$i];
								}

							$message .= "\n----------------------------------------------------------------------\n";
							}

						$message .= "\nRegards\n\n";
						$message .= $socketfromname;
?>
						<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
						  <tr>
							<td class="text">
<?
if(0)
{
						echo supportSendMail(	$_POST['email'],
								$_POST['name'],
								'Response To Ticket '.$_GET['ticketid'].' Written By - '.$_SESSION['stu_username'],
								$message);

						if ($emailuser == 'TRUE')
							{
							echo supportSendMail(	$_POST['email'],
									$_POST['name'],
									'Response To Ticket '.$_GET['ticketid'].' Written By - '.$_SESSION['stu_username'],
									$message,
									'1');
							}
}
?>
							</td>
						  </tr>
						</table>
<?
						$refresh = 'TRUE';
						}
					}
				}

	// QUERY TO GET THE TICKET IN QUESTION AND ANY REPLIES TO THAT TICKET

			$query = "	SELECT td_transactionId,tickets_id, tickets_subject, tickets_timestamp, tickets_status, tickets_name, tickets_email, tickets_admin, tickets_child, tickets_question, tickets_status_id, tickets_status_name, tickets_status_color, c.*
					FROM tickets_tickets a, tickets_status b, tickets_categories c
					WHERE (a.tickets_id = '".intval($_GET['ticketid'])."'
					OR tickets_child = '".intval($_GET['ticketid'])."')
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id
					AND (tickets_username = '".$_SESSION['stu_username']."'
					OR tickets_admin = 'Admin')
					ORDER BY tickets_id ASC";
					
			$result	      = mysql_query($query);
			$totaltickets = mysql_num_rows($result);
			$row	      = mysql_fetch_array($result);
			
			if($row['tickets_categories_email']) $socketfrom = $row['tickets_categories_email'];
			if($row['tickets_categories_emailname']) $socketfromname = $row['tickets_categories_emailname'];

	// PRINT OUT THE TABLES TO HOLD THE TICKET INFO - REPLY SUBMISSION AND TOPIC AND ANY REPLIES AND ATTACHMENTS
?>
			<form enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<? echo $_GET['ticketid'] ?>&amp;sub=add" method="post">
			<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
			  <tr>
				<td class="boxborder" width="50%" valign="top" style="padding-top:5px">

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td bgcolor="#AABBDD" class="boxborder text"><b>Ticket #<? echo $_GET['ticketid'] ?> Information</b></td>
<?
			if ($row['tickets_status'] == 'Open')
				{
?>
				<td class="boxborder list-menu" width="30%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<? echo $_GET['ticketid'] ?>&amp;closesub=Closed">Close Ticket</a></td>
<?
				}
			else
				{
?>
				<td class="boxborder list-menu" width="30%"><a href="<? echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<? echo $_GET['ticketid'] ?>&amp;closesub=Open">Reopen Ticket</a></td>
<?
				}
?>
				  </tr>
				</table>

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Account:</b></td>
					<td class="boxborder text"><? echo $_SESSION['stu_username'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Name:</b></td>
					<td class="boxborder text"><? echo $row['tickets_name'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Email:</b></td>
					<td class="boxborder text"><? echo $row['tickets_email'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Subject:</b></td>
					<td class="boxborder text"><? echo $row['tickets_subject'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Department:</b></td>
					<td class="boxborder text"><? echo $row['tickets_categories_name'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Urgency:</b></td>
					<td class="boxborder text" bgcolor="#<? echo $row['tickets_status_color'] ?>"><b><? echo $row['tickets_status_name'] ?></b></td>
				  </tr>

				<tr>
					<td bgcolor="#DDDDDD" colspan="2" style="text-align:center; font-size:10px;"> - Transaction Info - </td>
				</tr>

    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Issue:</b></td>
      <td class="boxborder text" >
	    <select name="tickets_issue" id="tickets_issue" >
          <?=get_fill_combo_conditionally("select tickets_issue, tickets_issue as t2 from `tickets_tickets` where tickets_id = " . $_GET['ticketid'] . " Group BY `tickets_issue`",$row['tickets_issue'])?>
		</select>
      </td>
    </tr>	

			  
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Status:</b></td>
					<td class="boxborder text">
<?
			if ($row['tickets_status'] == 'Closed')
				{
				echo '<span style="color:#FF0000">';
				}
			else
				{
				echo '<span style="color:#000000">';
				}

			echo		$row['tickets_status'];
?>
					</span></td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?
			if ($row['tickets_status'] != 'Closed')
				{
?>
				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text"><b>Respond</b></td>
				  </tr>
				  <tr>
					<td align="center"><textarea name="message" cols="50" rows="10"></textarea></td>
				  </tr>
				  <tr>
					<td align="right">
					<input type="hidden" name="name" value="<? echo $row['tickets_name'] ?>" />
					<input type="hidden" name="email" value="<? echo $row['tickets_email'] ?>" />
					<input type="hidden" name="postsubject" value="<? echo $row['tickets_subject'] ?>" />
					<input type="hidden" name="tickets_urgency" value="<? echo $row['tickets_status_id'] ?>|<? echo $row['tickets_status_name'] ?>" />
					<input type="hidden" name="tickets_category" value="<? echo $row['tickets_categories_id'] ?>|<? echo $row['tickets_categories_name'] ?>|<? echo $row['tickets_categories_email'] ?>|<? echo $row['tickets_categories_emailname'] ?>" />
					<input type="submit" value="Submit" />
					</td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?

	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

				if ($allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
					{
					FileUploadForm();
					}
				}
?>
				<br /></td>
				<td width="50%" valign="top" style="padding-top:5px">
<?
			$j = '0';
			$subresult = mysql_query($query);

	// LOOP THROUGH THE QUESTIOSN AND RESPONSES TO THIS QUESTION

			WHILE ($row = mysql_fetch_array($subresult))
				{
?>
				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text"><b>
<?
				if ($j == '0')
					{
					echo '	Dialog Question';
					}
				else
					{
					echo '	Response '.$j;
					}
?>
					</b></td>
					<td class="boxborder text" bgcolor="#AACCDD" width="50%" align="right"><? echo date($dformat.' H:i:s', $row['tickets_timestamp']) ?></td>
				  </tr>
<?
				if ($row['tickets_admin'] == 'Admin')
					{
					$bgcolor = '#FFF000';
					}
				else
					{
					$bgcolor = '#AACCEE';
					}
?>
				  <tr>
					<td class="boxborder text" colspan="2"><? echo nl2br($row['tickets_question']) ?></td>
				  </tr>
				  <tr bgcolor="<? echo $bgcolor ?>">
					<td class="boxborder text">Posted By: <? echo $row['tickets_admin'] ?></td>
					<td class="text">
<?
	// SCAN THE UPLOAD DIRECTORY FOR ATTACHMENTS TO THIS POST if ATTACHMENTS ARE OFF THEN THIS WONT DO IT

				if ($allowattachments == 'TRUE' and 0)
					{
					$d = dir($uploadpath);

					WHILE (false !== ($files = $d -> read()))
						{
						$files = explode('.', $files);

						if ($files['0'] == $row['tickets_id'])
							{
?>
						  	<b>Attachment:</b> <? echo $files['0'] ?>.<? echo $files['1'] ?>
							<a href="<? echo $relativepath.$files['0'] ?>.<? echo $files['1'] ?>" target="_blank">
							<img src="images/download.gif" width="13" height="13" align="absmiddle" border="0" /></a>
<?
							$filename = $files['0'].'.'.$files['1'];
?>
							<input type="hidden" name="attachment[<? echo $_GET['ticketid'] - 1 ?>]" value="<? echo $filename ?>" />
<?
							}
						else
							{
							$filename = '';
							}
						}

					$d -> close();
					}
?>
					</td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?
				$j ++;
?>
				<input type="hidden" name="ticketquestion[]" value="<? echo $row['tickets_question'] ?>" />
				<input type="hidden" name="postedby[]" value="<? echo $row['tickets_admin'] ?>" />
				<input type="hidden" name="postdate[]" value="<? echo date($dformat.' H:i:s', $row['tickets_timestamp']) ?>" />
<?
				}
?>
				</td>
			  </tr>
			</table>
			</form>
<?
			if (isset($refresh) && $refresh == 'TRUE')
				{
?>
				<meta http-equiv="refresh" content="2;URL=<? echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<? echo $_GET['ticketid'] ?>" />
<?
				}

		BREAK;


#############################################################################################
#################################### CREATE A NEWTICKET #####################################
#############################################################################################

		CASE 'NewTicket':

	// if THE FORM IS SUBMITTED THEN VERifY SOME CONTENTS

			if (isset($_GET['sub']))
				{

	// if FORM IS NOT FILLED OUT CORRECTLY THEN SHOW ERROR MESSAGES


				if ($_POST['message'] == '' || $_POST['name'] == '' || $_POST['email'] == '' || $_POST['email'] == '' || $_POST['ticketsubject'] == '')
					{
?>
					<table width="<? echo $maintablewidth; ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
					  <tr bgcolor="#AACCEE">
						<td class="text">Please complete all the fields.</td>
					  </tr>
					</table>
<?
					}

	// if FORM IS OK THEN INSERT INTO THE DATABASE

				else
					{
					$_POST['ticketsubject']	= Clean_It($_POST['ticketsubject']);
					$_POST['name']		= Clean_It($_POST['name']);
					$_POST['email']		= Clean_It($_POST['email']);
					$_POST['urgency']	= Clean_It($_POST['urgency']);
					$_POST['category']	= Clean_It($_POST['category']);
					$_POST['message']	= Clean_It($_POST['message']);

					$urgency  = explode('|', $_POST['tickets_urgency']);
					$category = explode('|', $_POST['tickets_category']);
					
					$sql_tickets_status = "";
					if($_POST['tickets_resolved']) $sql_tickets_status = "tickets_status = 'Closed',";
					
					$sql_issue = "";
					if($_POST['tickets_issue']) $sql_issue="tickets_issue = '".addslashes($_POST['tickets_issue'])."',";

					$query = "	INSERT INTO tickets_tickets
							SET
							tickets_username  = '".$_SESSION['stu_username']."',
							tickets_subject	  = '".addslashes($_POST['ticketsubject'])."',
							tickets_timestamp = '".mktime()."',
							tickets_name	  = '".addslashes($_POST['name'])."',
							tickets_email	  = '".addslashes($_POST['email'])."',
							tickets_urgency	  = '".$urgency['0']."',
							tickets_category  = '".$category['0']."',
							$sql_issue
							$sql_tickets_status
							tickets_reference  = '".strtoupper(substr(md5(time().rand(0,1000)),0,16))."',
							tickets_question  = '".addslashes($_POST['message'])."'";
							
					if ($result = mysql_query($query) or dieLog(mysql_error()))
						{

						$lastinsertid = mysql_insert_id();

						$td_transactionId=trim($_SESSION['cs_found_reference_number']);
						if($td_transactionId && !is_numeric($td_transactionId))
						{
							$sql = "select transactionId from `cs_transactiondetails` where reference_number = '$td_transactionId' ";
							$result = mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
							$td_transactionId = mysql_result($result,0,0);
						}
						
						$query = "SELECT lt_option_text FROM cs_live_tree WHERE lt_ID = $issue";
						$result = mysql_query($query) or die("<b>" . $query . "</b><br>" . mysql_error());
						$row = mysql_fetch_row($result);
						if(!row)
							$row[0] = "";
							
						if(strcasecmp($_SESSION["userType"],"CustomerService"))
							$source = "client"; //client
						else
							if($_SESSION['cs_found_reference_number'] == NULL)
								$source = "unfoundcall"; //unfound
							else
								$source = "foundcall"; //found
	//					echo "$source";
			//			exit();
						$query = "	UPDATE tickets_tickets
						SET td_transactionId = '$td_transactionId',
						tickets_time = '" . (microtime_float() - $_SESSION['ticket_start_time']) . "',
						tickets_source = '" . $source . "',
						tickets_issue = '" . $row[0] . "'
						WHERE tickets_id    = '".$lastinsertid."'";
						$result = mysql_query($query) or die("<b>" . $query . "</b><br>" . mysql_error());
						unset($_SESSION['ticket_start_time']);

	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

						if ($allowattachments == 'TRUE' && !isset($_COOKIE['demomode']) || $demomode != 'ON')
							{
							FileUploadsVerification("$_FILES(userfile)", mysql_insert_id());
							}
	// EMAIL ADMINISTRATOR THE TICKET NOTifICATION

$query = "	SELECT *
		FROM tickets_categories WHERE tickets_categories_id = '$category[0]'
		ORDER BY tickets_categories_name ASC";

$result = mysql_query($query);
$cat_info = mysql_fetch_assoc($result);
if($cat_info['tickets_categories_email']) $socketfrom = $cat_info['tickets_categories_email'];
if($cat_info['tickets_categories_emailname']) $socketfromname = $cat_info['tickets_categories_emailname'];


						$message  = "Ticket ID:\t ".mysql_insert_id()."\n";
						$message .= "Name:\t\t ".$_POST['name']."\n";
						$message .= "Email:\t ".$_POST['email']."\n";
						$message .= "Subject:\t ".$_POST['ticketsubject']."\n";
						$message .= "Urgency:\t ".$urgency['1']."\n";
						$message .= "Department:\t ".$category['1']."\n";
						$message .= "Post Date:\t ".date($dformatemail)."\n";
						$message .= "----------------------------------------------------------------------\n";
						$message .= "Message:\n";
						$message .= stripslashes($_POST['message'])."\n";
						$message .= "----------------------------------------------------------------------\n";
?>
						<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
						  <tr>
							<td class="text">
<? /*
						echo supportSendMail(	$_POST['email'],
								$_POST['name'],
								'Support Ticket Written By - '.$_SESSION['stu_username'],
								$message);

						if ($emailuser == 'TRUE')
							{
							echo supportSendMail(	$_POST['email'],
									$_POST['name'],
									'Support Ticket Written By - '.$_SESSION['stu_username'],
									$message,
									'1');
							}
*/
?>
							</td>
						  </tr>
						</table>
<?
						$refresh = 'TRUE';
						}
					}
				}

	// PRODUCE THE FORM SO THE PERSON CAN WRITE THE NEW TICKET

			if ($_SESSION['stu_username'] != 'demo')
				{
				$query = "	SELECT tickets_users_name, tickets_users_email
						FROM tickets_users
						WHERE tickets_users_username = '".$_SESSION['stu_username']."'
						LIMIT 0,1";

				$result = mysql_query($query);
				$row    = mysql_fetch_array($result);
				}
			else
				{
				$row['tickets_users_name']  = '';
				$row['tickets_users_email'] = '';
				}
				
				
			if(!$_POST['message']) $_POST['message'] = $_SESSION['cs_found_call_log'];
			if(!$_POST['name']) $_POST['name'] = $transactionInfo['fullname'];
			if(!$_POST['email']) $_POST['email'] = $transactionInfo['email'];
			if(!$_POST['ticketsubject']) $_POST['ticketsubject'] = $_SESSION['cs_found_call_subject'];
			if(!$_POST['tickets_resolved']) $_POST['tickets_resolved'] = $_SESSION['cs_found_call_resolved'];
				
?>
			<form enctype="multipart/form-data" action="<? echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket&amp;sub=add" method="post">
			<input type="hidden" name="issue" value="<?=$issue?>">
			<table width="<? echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<? echo $maintablealign ?>">
			  <tr>
				<td class="boxborder" width="50%" valign="top" style="padding-top:5px">

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text" colspan="2"><b>New Support Ticket - All Fields Required</b></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Account:</b></td>
					<td class="boxborder text"><? echo $_SESSION['stu_username'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Name:</b></td>
					<td class="boxborder text"><input name="name" size="40" value="<? echo $_POST['name'] ?>" style="background-color:#FFCC66" /></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Email:</b></td>
					<td class="boxborder text"><input name="email" size="40" value="<? echo $_POST['email'] ?>" style="  background-color:#FFCC66" /></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Subject:</b></td>
					<td class="boxborder text"><input name="ticketsubject" size="40" value='<?=$_POST['ticketsubject']?>' style="  background-color:#FFCC66">					</td>
				  </tr>
				  <? if($_SESSION["userType"]=="CustomerService"){?>
				<tr>
				  <td bgcolor="#EEEEEE" class="boxborder text"><b>Issue:</b></td>
				  <td class="boxborder text" >
					<select name="tickets_issue" id="tickets_issue" >
					  <option value="" onclick="this.value=prompt('Please Enter A Brief Description of the Issue:\n(Do this only if this issue is not already in the issue list)',''); this.text=this.value;" >Add New</option>
					  <?=get_fill_combo_conditionally("select tickets_issue, tickets_issue as t2 from `tickets_tickets` Group BY `tickets_issue`",$_POST['tickets_issue'])?>
					</select>				  </td>
				</tr>
				 <tr>
				  <td bgcolor="#EEEEEE" class="boxborder text"><b>Issue Resolved?</b></td>
				  <td class="boxborder text" ><select name="tickets_resolved" id="tickets_resolved" >
					<option value="0">No</option>
					<option value="1" <?=($_POST['tickets_resolved']?'selected="selected"':'')?>>Yes</option>
					
				  </select></td>
				</tr>	 
				<? } ?>
				<tr>
				  <td bgcolor="#EEEEEE" class="boxborder text"><b>Department:</b></td>
				  <td class="boxborder text"><select name="tickets_category" id="tickets_category">
					<?=get_fill_combo_conditionally("select tickets_categories_id, tickets_categories_name from tickets_categories",$_POST['tickets_category'])?>
				  </select></td>
				</tr>
				<tr>
				  <td bgcolor="#EEEEEE" class="boxborder text"><b>Urgency:</b></td>
				  <td class="boxborder text"><select name="urgency" id="urgency">
					  <?=get_fill_combo_conditionally("SELECT concat(tickets_status_id,'|',tickets_status_name) as tickets_status_id, tickets_status_name, concat('background-color:#',tickets_status_color) as style FROM tickets_status	ORDER BY tickets_status_order ASC",$_POST['urgency'])?>
					</select>
					</td>
				</tr>	
				</table>
				<div style="padding-top:5px"></div>

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text"><b>Question</b></td>
				  </tr>
				  <tr>
					<td align="center">
					<textarea name="message" cols="80" rows="10">
<?=$_POST['message']?></textarea>
					</td>
				  </tr>
				  <tr>
					<td align="right"><input type="submit" value="Submit" /></td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

			if ($allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
				{
				FileUploadForm();
				}
?>
				<br /></td>
				<td class="boxborder" width="50%" valign="top" style="padding-top:5px">

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td class="text">Please fill in all the information. And make sure the question is very
					explicit as to what the problem is, some guidelines follow:
					<ul>
					<li>Type the Name and Email of the person this issue/call involves </li>
					<li>Type the Subject of the issue (Requesting Refund. Found a bug. etc)</li>
				  <? if($_SESSION["userType"]=="CustomerService"){?>
					<li>Select the issue from the dropdown menu.
					  <ul>
					    <li>If you cannot find the issue, select 'Add New' </li>
			          </ul>
					</li>
					<? } ?>
					<li>Enter a description of the issue (detailed but concise)</li>
					</ul>					</td>
				  </tr>
<?
	// if ATTACHMENTS ARE TRUE THEN SHOW ALLOWED FILETYPES

			if ($allowattachments == 'TRUE')
				{
?>
				  <tr>
					<td class="text"><b>Allowed FILE TYPES for attachments:</b><br />
<?
				FOR ($i = '0'; $i <= COUNT($allowedtypes) - 1; $i++)
					{
					echo $allowedtypes[$i].'<br />';
					}
?>
					</td>
				  </tr>
<?
				}
?>
				</table><br />

				</td>
			  </tr>
			</table>
			</form>
<?
			if (isset($refresh) && $refresh == 'TRUE')
				{
?>
				<meta http-equiv="refresh" content="2;URL=<? echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<? echo $lastinsertid ?>" />
<?
				}

		BREAK;
		}


#############################################################################################
################################ ADD THE FOOTER INFORMATION #################################
#############################################################################################

	include_once ('footer.php');
	include_once ('../includes/footer.php');

	if (isset($result))
		{
		mysql_free_result($result);
		mysql_close();
		}
?>