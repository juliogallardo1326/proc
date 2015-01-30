<?php

/***************************************************************************
File Name 	: index.html
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Admin page
Date Created	: Wednesday 19 January 2005 16:16:09
File Version	: 1.9
\\||************************************************************************/

#############################################################################################
############################### CURRENT CASEID'S ON THIS PAGE ###############################
#############################################################################################

	// home	  	- LINE 213
	// AdminView  	- LINE 501
	// adduser	- LINE 791
	// document	- LINE 1071
	// categories	- LINE 1113
	// status	- LINE 1353
	// newticket	- LINE 1421
	// footer	- LINE 1715


#############################################################################################
############################ INCLUDE THE CONFIG AND HEADER FILE #############################
#############################################################################################

	// STARTS THE SESSION FOR THE USERS SO LOGIN IS TRACKED THROUGH THE PAGES

	session_start();

	include_once ('header.php');
	include_once ('../config.php');
	include_once ('../class/functions.php');
	
	updateUserInfo();
	
	if ($adminInfo['li_level'] != 'full') $sql_user_limit = 'Error '.$adminInfo['userid'];
	if ($adminInfo['li_level'] == 'gateway') $sql_user_limit = 'and cs_gateway_id = '.$adminInfo['li_gw_ID'];
	if ($adminInfo['li_level'] == 'customerservice') 
	{
		$sql_ticket_limit = 'and a.tickets_category = 1';
		$sql_user_limit = '';
	}
#############################################################################################
###################### AUTH LOGIN AND LOGOUT SYSTEM REQUIRES SESSIONS #######################
#############################################################################################

	// LOGOUT
	
	IF (isset($_GET['action']) && $_GET['action'] == 'Logout')
		{
		unset($_SESSION['sta_username']);
		unset($_SESSION['sta_type']);
		}

	// CHECK THE THE ENTERED USERNAME AND PASSWORD ARE CORRECT

	IF (isset($_POST['form']) && isset($_POST['username']) && isset($_POST['password']))
		{

	// CHECK AGAINST THE DB FOR MODERATORS AND ADMINS WITH THE SAME USER AND PASS

		$query = "	SELECT tickets_users_admin
				FROM tickets_users
				WHERE tickets_users_username = '".$_POST['username']."'
				AND tickets_users_password = '".$_POST['password']."'
				AND (tickets_users_admin = 'Admin'
				OR tickets_users_admin = 'Mod')
				LIMIT 0,1";

		$result = sql_query_read($query);
		$row    = mysql_fetch_array($result);

		IF (mysql_num_rows($result) > '0')
			{
			$_SESSION['sta_username'] = $_POST['username'];
			$_SESSION['sta_type']	  = $row['tickets_users_admin'];
			}
		ELSE
			{
			$_SESSION = array();
?>

<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
  <tr>
    <td><a href="<?php echo $_SERVER['PHP_SELF'] ?>"><img src="../images/support_tickets_logo.gif" width="83" height="61" title="Triangle Solutions PHP Support Tickets" alt="Triangle Solutions PHP Support Tickets" vspace="1" border="0" /></td>
  </tr>
</table>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="<?php echo $background ?>">
    <td class="text">Access Denied</td>
  </tr>
</table>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="text"> Your Username or Password is incorrect, or you are not a
      registered user on this site. Please Try logging in again.
      <input type="button" value="Back" onclick="history.back()" />
      <br />
      <br />
    </td>
  </tr>
</table>
<?php
			include_once ('footer.php');
			Exit();
			}
		}

	// IF NO USER OR PASS SESSION ARE ACTIVE THEN SHOW THE LOG IN AREA

	IF (!isset($_SESSION['sta_username']) && !isset($_SESSION['sta_type']))
	{
		die("Redirecting to Index...<script>document.location.href='/index.php'</script>");
	}


#############################################################################################
################ MAKE SURE THE RIGHT CASEID IS ENTERED OR DEFAULT TO HOME ID ################
#############################################################################################

	IF (		!isset($_GET['caseid']) || $_GET['caseid'] == '' || $_GET['caseid'] != 'home'
			&& $_GET['caseid'] != 'AdminView' && $_GET['caseid'] != 'document'
			&& $_GET['caseid'] != 'AddUser'   && $_GET['caseid'] != 'cats'
			&& $_GET['caseid'] != 'status'    && $_GET['caseid'] != 'NewTicket'
		)
		{
		$_GET['caseid'] = 'home';
		}

	IF (($_GET['caseid'] == 'AddUser' && $_SESSION['sta_type'] != 'Admin') || ($_GET['caseid'] == 'cats' && $_SESSION['sta_type'] != 'Admin') || ($_GET['caseid'] == 'status' && $_SESSION['sta_type'] != 'Admin'))
		{
		$_GET['caseid'] = 'home';
		}


#############################################################################################
########################### DISPLAY THE PAGE TITLE AND NAVIGATION ###########################
#############################################################################################
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=home" method="post">
  <table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
    <tr>
      <td><a href="<?php echo $_SERVER['PHP_SELF'] ?>"><img src="../images/support_tickets_logo.gif" width="83" height="61" title="Triangle Solutions PHP Support Tickets" alt="Triangle Solutions PHP Support Tickets" vspace="2" border="0" /></td>
      <td valign="bottom" align="right" class="text" style="padding:2px">Search Tickets:
        <input name="keywords" size="24" onfocus="javascript:this.value=''" value="Search Ticket Subject" />
        <input type="submit" value="Go" />
      </td>
    </tr>
  </table>
</form>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="boxborder list-menu" width="10%"><a href="<?=$_SERVER['PHP_SELF'] ?>?caseid=home">Home</td>
    <td class="boxborder list-menu" width="10%"><a href="<?=$_SERVER['PHP_SELF'] ?>?caseid=NewTicket">New Ticket</td>
    <td class="boxborder list-menu" width="10%"><a href="<?=$_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Open">Unanswered</td>
    <td class="boxborder list-menu" width="10%"><a href="<?=$_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Answered">Answered</td>
    <td class="boxborder list-menu" width="10%"><a href="<?=$_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Closed">Closed</td>
    <?php
	IF ($_SESSION['sta_type'] == 'Admin')
		{
?>
    <td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser">Users</td>
    <?php
		}

	IF ($_SESSION['sta_type'] == 'Admin')
		{
?>
    <td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=cats">Departments</td>
    <?php
		}

	IF ($_SESSION['sta_type'] == 'Admin')
		{
?>
    <td class="boxborder list-menu" width="10%" ><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=status">Urgency's</td>
    <?php
		}
?>
    <td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document">Documents</td>
    <td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=Logout">Logout</td>
  </tr>
</table>
<?php
#############################################################################################
############## HOME DEFAULT CASE THIS DEALS WITH THE DISPLAYING OF ANY TICKETS ##############
#############################################################################################

	SWITCH ($_GET['caseid'])
		{
		CASE 'home':

			IF (!isset($_GET['order']) && !isset($_POST['keywords']))
				{
				$_GET['order'] = 'Open';
				}

	// PROCESS THE FUNCTIONS WHEN THE CHECKBOXES ARE CHECKED - IE OPEN / CLOSE / DELETE TICKET

			IF (isset($_POST['status']))
				{
				IF (isset($_POST['ticket']))
					{
					FOREACH ($_POST['ticket'] AS $ticketid)
						{
						IF ($_POST['status'] == 'Deleted')
							{
							$query = "	DELETE FROM tickets_tickets
									WHERE tickets_id   = '".$ticketid."'";
							}
						ELSE
							{
							$query = "	UPDATE tickets_tickets
									SET tickets_status = '".$_POST['status']."'
									WHERE tickets_id   = '".$ticketid."'";
							}

	// IF $emailclose IS TRUE THEN EMAIL THE USER WHEN THE ADMIN CLOSES THE TICKET

						IF (isset($emailclose) && $emailclose == 'TRUE' && $_POST['status'] == 'Closed')
							{

	// GET THE USER DETAILS - EMAIL / NAME OF THIS TICKET

							$query_em = "	SELECT tickets_users_name, tickets_users_email
									FROM tickets_users a, tickets_tickets b
									WHERE b.tickets_id = '".$ticketid."'
									AND b.tickets_username = a.tickets_users_username
									$sql_user_limit
									";

							$result_em = sql_query_read($query_em);
							$row_em    = mysql_fetch_array($result_em);

							$message  = "Ticket ID:\t ".$ticketid." - has changed status to ".$_POST['status']."\n";
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="text"><?php echo supportSendMail($row_em['tickets_users_email'], $row_em['tickets_users_name'], 'Ticket ID - '.$ticketid.' Closed', $message) ?></td>
  </tr>
</table>
<?php
							}

						IF (sql_query_read($query))
							{
							$msg = 'Ticket '.$_POST['status'];
							}
						ELSE
							{
							$msg = 'This could not be done at this time';
							}
						}
					}
				ELSE
					{
					$msg = 'Please select a Ticket.';
					}
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="#AACCEE">
    <td class="text"><?php echo $msg ?></td>
  </tr>
</table>
<?php
				}

	// QUERY TO GET THE LATEST OPEN - CLOSED OR SEARCH ON SUBJECT

			$tickets_categories_id =intval($_REQUEST['tickets_categories_id']);
			if(isset($_REQUEST['tickets_categories_id'])) $sql_cond = "and a.tickets_category='$tickets_categories_id'";
					
			IF (isset($_GET['order']))
			{
				$sql_cond .= " AND a.tickets_status = '".$_GET['order']."'";
				$addon  = '&amp;order='.$_GET['order'];
			}
			ELSE IF (isset($_POST['keywords']))
			{
				$sql_cond .= " AND a.tickets_subject LIKE '%".$_POST['keywords']."%'";
				$addon  = '';
			}
			
			
			$sub_query = "SELECT a.tickets_child, COUNT( * ) AS ticket_total, MAX( tickets_timestamp ) ,  SUBSTRING( MAX( CONCAT( LPAD( `tickets_id` , 8, '0' ) , `tickets_admin` ) ) , 9 ) AS admin
FROM tickets_tickets a
GROUP BY tickets_child
";
			
			$query_cnt = "	SELECT count(*) as cnt
					FROM tickets_tickets a left join tickets_users d on a.tickets_username = d.tickets_users_username
					WHERE a.tickets_child = '0'
					$sql_cond
					$sql_user_limit
					$sql_ticket_limit
					";	
							
					//left join cs_companydetails cd on cs_userId = cd.userId		
			$query = "	SELECT a.tickets_id, a.tickets_username, a.tickets_name, a.tickets_subject, a.tickets_timestamp, a.tickets_status, tickets_status_name, tickets_status_color, tickets_categories_name, cs_userId,  tickets_timestamp as latest_ticket, a.tickets_reference,  d.cs_gateway_id,
					 a.tickets_responses AS ticket_total
					FROM tickets_tickets a, tickets_status b, tickets_categories c, tickets_users d
					
					
					WHERE a.tickets_child = '0'
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id
					AND a.tickets_username = d.tickets_users_username
					$sql_cond
					$sql_user_limit
					$sql_ticket_limit
					GROUP BY a.`tickets_id` ORDER BY latest_ticket DESC, a.tickets_timestamp DESC
					";




	// SET PAGE NUMBER IF NONE SPECIFIED ASSUME IT IS EQUAL TO ONE
		//print($query);
			$result_cnt       = sql_query_read($query_cnt) or dieLog(mysql_error()." ~ $query");
			$totaltickets = mysql_result($result_cnt,0);
			

			$per_page = $ticket_display;

			IF (!isset($_GET['display']))
				{
		   		$_GET['display'] = '1';
				}

			$prev_page = $_GET['display'] - 1;
			$next_page = $_GET['display'] + 1;
	// SET UP PAGE
			$page_start = ($per_page * $_GET['display']) - $per_page;

			$num_rows = $totaltickets;

			IF ($num_rows <= $per_page)
				{
				$num_pages = '1';
				}
			ELSEIF (($num_rows % $per_page) == '0')
				{
				$num_pages = ($num_rows / $per_page);
				}
			ELSE
				{
				$num_pages = ($num_rows / $per_page) + 1;
				}

			$num_pages = (int) $num_pages;

	// DISPLAY RESULTS
	
			$query  = $query . " LIMIT $page_start, $per_page";
			
			$result = sql_query_read($query) or die(mysql_error()." ~ $query");

	// ADD THE HOME VIEW TITLE TABLE
?>
<div style="padding-top:5px"></div>
<?php
			IF ($totaltickets > '0')
				{
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="<?php echo $background ?>">
    <td class="text">Recent Tickets: <?php echo $totaltickets ?> - Click on the
      Ticket ID to read the ticket.</td>
    <td align="right"><form action="" method="get">
        <input type="hidden" value="<?=$_REQUEST['order']?>" name="order" />
        <select name="tickets_categories_id" id="tickets_categories_id" onchange="this.form.submit();">
          <option value=''>Any Category</option>
          <?=get_fill_combo_conditionally(
		"select 
		  	tickets_categories_id, concat(tickets_categories_name,' (',count(*),')')
		from 
			tickets_categories c
			left join tickets_tickets a on 
				a.tickets_category = c.tickets_categories_id
		WHERE 
			tickets_child =0
			AND tickets_status = 'Open'
		Group by
			tickets_categories_id
					",$_REQUEST['tickets_categories_id'])?>
        </select>
      </form></td>
  </tr>
</table>
<?php
				ShowPaging(	$_SERVER['PHP_SELF'].'?'.htmlentities($_SERVER['QUERY_STRING']),
						$prev_page,
						$next_page,
						$num_pages,
						$_GET['display']
						);
				}

			IF ($totaltickets > '0')
				{
?>
<script language="javascript">
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
<form name="myform" action="index.php?caseid=home<?php echo $addon ?>" method="post">
  <table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
    <tr align="center" bgcolor="<?php echo $background ?>">
      <td class="boxborder text" onclick="check_all();" style="cursor:pointer"><b><u>All</u></b></td>
      <td class="boxborder text"><b>Ticket ID</b></td>
      <td class="boxborder text"><b>Replies</b></td>
      <td class="boxborder text"><b>Username</b></td>
      <td class="boxborder text"><b>Subject</b></td>
      <td class="boxborder text"><b>Date / Time</b></td>
      <td class="boxborder text"><b>Urgency</b></td>
      <td class="boxborder text"><b>Department</b></td>
      <td class="boxborder text"><b>Status</b></td>
    </tr>
    <?php
				WHILE ($row = mysql_fetch_assoc($result))
					{

	// QUERY TO GET THE AMOUNT OF REPLIES TO A CERTAIN TICKET AND DATE OF LAST ENTRY

					
					$gw_ID = $row['gateway_id'];
					$tickets_reference = $row['tickets_reference'];
					if(!$gw_ID)$gw_ID=$row['cs_gateway_id'];

					IF ($row['ticket_total'] <= '0')
						{
						$row['ticket_total'] = '0';
						}
?>
    <tr align="center" bgcolor="<?php echo UseColor() ?>">
      <td class="boxborder"><input type="checkbox" name="ticket[]" value="<?php echo $row['tickets_id'] ?>" /></td>
      <td class="boxborder list-menu"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&ticketid=<?=$row['tickets_id']?>&gateway_id=<?=$gw_ID?>&tickets_reference=<?=$tickets_reference?>"><?php echo $row['tickets_id'] ?></a></td>
      <td class="boxborder text">[<?php echo $row['ticket_total'] ?>]</td>
      <td class="boxborder text">
	  <?php if ($row['cs_userId']>0) { ?>
        <a href="<?=$etel_domain_path?>/admin/editCompanyProfileAccess.php?company_id=<?=$row['cs_userId']?>"><?=$row['tickets_name']?></a>
        <?php } 
		else
		if ($row['cs_reseller_id']>0) { ?>
        <a href="<?=$etel_domain_path?>/admin/modifyReseller.php?reseller_id==<?=$row['cs_reseller_id']?>"><?=$row['tickets_name']?></a>
        <?php }
		else echo $row['tickets_name']; ?>
	  
	  </td>
      <td class="boxborder text"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&ticketid=<?=$row['tickets_id']?>&gateway_id=<?=$gw_ID?>&tickets_reference=<?=$tickets_reference?>"> <span <?=$row['admin']!='Admin'?'style="font-weight:bold; "':''?> >
        <?=$row['tickets_subject']?$row['tickets_subject']:"No Subject"?>
        </span> </a> </td>
      <td class="boxborder text"><?php echo date($dformat.' H:i:s', $row['latest_ticket']) ?></td>
      <td class="boxborder text" bgcolor="#<?php echo $row['tickets_status_color'] ?>"><?php echo $row['tickets_status_name'] ?></td>
      <td class="boxborder text"><?php echo $row['tickets_categories_name'] ?></td>
      <td class="boxborder text"><?php
					IF ($row['tickets_status'] == 'Closed')
						{
						echo '<span style="color:#FF0000">';
						}
					ELSE
						{
						echo '<span>';
						}

					echo 		$row['tickets_status'].'</span></td>
						  </tr>';
					}
?>
      </td>
    </tr>
    <tr>
      <td colspan="8"><select name="status">
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
          <option value="Deleted">Delete</option>
        </select>
        <input type="submit" name="sub" value="Go">
      </td>
    </tr>
  </table>
</form>
<?php
				ShowPaging(	$_SERVER['PHP_SELF'].'?'.htmlentities($_SERVER['QUERY_STRING']),
						$prev_page,
						$next_page,
						$num_pages,
						$_GET['display']
						);
				}
			ELSE
				{
				IF (isset($_POST['keywords']))
					{
					$msg = 'Sorry but the search returned Zero results please try again.';
					}
				ELSE
					{
					$msg = 'You have no recent tickets for your account.';
					}
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="text"><?php echo $msg ?></td>
  </tr>
</table>
<?php
				}
		BREAK;


#############################################################################################
################################ VIEW THE INDIVIDUAL TICKET #################################
#############################################################################################

		CASE 'AdminView':

	// CLOSE AND REOPEN THE TICKET SECTION

			IF (isset($_GET['closesub']) && ($_GET['closesub'] == 'Closed' || $_GET['closesub'] == 'Open'))
				{
				IF (isset($emailclose) && $emailclose == 'TRUE' && $_GET['closesub'] == 'Closed')
					{
					$message  = "Ticket ID:\t ".$_GET['ticketid']." - has changed status to ".$_GET['closesub']."\n";
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="text"><?php echo supportSendMail($_GET['email'], $_GET['name'], 'Ticket ID - '.$_GET['ticketid'].' Closed', $message) ?></td>
  </tr>
</table>
<?php
					}

				$query = "	UPDATE tickets_tickets
						SET tickets_status = '".$_GET['closesub']."'
						WHERE tickets_id   = '".$_GET['ticketid']."'";

				IF (sql_query_write($query))
					{
					$msg = 'Ticket '.$_GET['closesub'];
					}
				ELSE
					{
					$msg = 'This could not be done at this time';
					}
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="#AACCEE">
    <td class="text"><?php echo $msg ?></td>
  </tr>
</table>
<?php
				}

	// CHANGE THE URGENCY

			IF ($_REQUEST['sub'] == 'updatestatus')
				{
					
				if($_REQUEST['tickets_et_custom_id'] && $_REQUEST['btn_template'])
				{
					$et_custom_id = intval($_REQUEST['tickets_et_custom_id']);
					$sql = "select et_textformat from `cs_email_templates` where et_name = 'support_ticket_template' and et_custom_id='$et_custom_id' ";
		
					$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
					if (mysql_num_rows($result))
					{
						$emailInfo = mysql_fetch_assoc($result);
						$custom_response_template = $emailInfo['et_textformat'];
					}
				}
				
				
				
				if($_REQUEST['et_title'])
				{
					$et_custom_id = intval($_REQUEST['tickets_et_custom_id']);
					$et_custom_title = quote_smart($_REQUEST['et_title']);
					$et_custom_text = quote_smart($_REQUEST['message']);
					$sql = "select max(et_custom_id) from `cs_email_templates` where et_name = 'support_ticket_template' ";
					if(!$et_custom_id)
					{
						$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
						$et_custom_id = mysql_result($result,0,0);
						$et_custom_id++;
					}
					$sql = " `cs_email_templates` set  et_custom_id = '$et_custom_id', et_name = 'support_ticket_template', et_title='$et_custom_title', et_textformat='$et_custom_text', et_catagory='Support' ";
					if($_REQUEST['tickets_et_custom_id'])
						$sql = "update $sql where et_custom_id = '$et_custom_id'";
					else
						$sql = "insert into $sql";
					$custom_response_template = $_REQUEST['message'];
					$result = sql_query_write($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
			
				}					
				
				$td_transactionId=trim($_REQUEST['td_transactionId']);
				if($td_transactionId && is_numeric($td_transactionId))
				{
					$sql = "select reference_number from `cs_transactiondetails` where transactionId = '$td_transactionId' ";
					$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
					$td_transactionId = mysql_result($result,0,0);
				}
				
				$query = "	UPDATE tickets_tickets
						SET tickets_urgency = '".$_REQUEST['tickets_urgency']."',
						tickets_issue = '".$_REQUEST['tickets_issue']."',
						td_transactionId = '$td_transactionId',
						tickets_category = '".$_REQUEST['tickets_category']."'
						WHERE tickets_id    = '".$_REQUEST['ticketid']."'";

				sql_query_write($query) or dieLog(mysql_error(),mysql_error());
					$msg = 'Ticket Updated';

				$service_notes = quote_smart($_REQUEST['service_notes']);
				$transID = intval($_REQUEST['td_transactionId']);
				
				IF ($_POST['submit'] == 'Refund Order')
				{	
					if($td_transactionId && $service_notes)
					{
						$trans = new transaction_class(false);
						$trans->pull_transaction($td_transactionId,'reference_number');
						$status = $trans->process_refund_request(array("actor"=>'Customer Service','notes'=>"$service_notes"));
						$msg = "Refund Request ".$status['status'];

					}
				}
					
				IF ($_POST['submit'] == 'Cancel Rebill')
				{		
					//$ref_no = func_Trans_Ref_No($td_transactionId);
					//$qry_details="UPDATE `cs_transactiondetails` SET `td_enable_rebill` = '0', `reason` = 'Admininstrator Cancel', `cancel_refer_num` = '$ref_no'  WHERE `transactionId` = '$td_transactionId'";
					if($td_transactionId && $service_notes)
					{				
						require_once("../../includes/transaction.class.php");
						$trans = new transaction_class(false);
						$trans->pull_transaction($td_transactionId,'reference_number');
						$status = $trans->process_cancel_request(array("actor"=>'Customer Service'));
						$msg = "Subscription Cancel ".($status?"Succeeded":"Failed");
					}
				}
				
				


					
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="#AACCEE">
    <td class="text"><?php echo $msg ?></td>
  </tr>
</table>
<?php
				}

	// AND A NEW RESPONSE AND ATTACHMENT TO THE SYSTEM

			IF ($_POST['submit'] == 'Submit Ticket')
				{
				IF ($_POST['message'] == '')
					{
					$msg = 'Please complete all the fields';
					}
				ELSE
					{
					$urgency  = explode('|', $_POST['posturgency']);
					$category = explode('|', $_POST['postdept']);

					$tickets_reference  = $_REQUEST['tickets_reference'];
					if(!$tickets_reference) dieLog("No Reference ID");
					$query = "	INSERT INTO tickets_tickets
							SET
							tickets_username  = '".$_SESSION['sta_name']."',
							tickets_subject   = '".$_POST['postsubject']."',
							tickets_timestamp = '".mktime()."',
							tickets_urgency   = '".$urgency['0']."',
							tickets_category  = '".$category['0']."',
							tickets_admin     = 'Admin',
							tickets_child     = '".$_GET['ticketid']."',
							tickets_question  = '".$_POST['message']."'";

					IF ($result = sql_query_write($query))
						{
						$tickets_id = mysql_insert_id();
				update_thread_status(intval($_GET['ticketid']));
	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

						IF ($allowattachments == 'TRUE')
							{
							FileUploadsVerification("$_FILES(userfile)", mysql_insert_id());
							}
	// MAIL THE PERSON WHO STARTED THE TICKET
	
$socketfrom = "Ticket-".$tickets_reference."@".$socketdomain;

						$message  = "Ticket ID:\t $tickets_reference Response\n";
						$message .= "Name:\t\t ".$_POST['name']."\n";
						$message .= "Email:\t ".$_POST['email']."\n";
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

						FOR ($i = count($_POST['ticketquestion']) - 1; $i >= 0; $i--)
							{
							$message .= $_POST['postedby'][$i]." - ".$_POST['postdate'][$i]."\n";
							$message .= stripslashes($_POST['ticketquestion'][$i]);

							IF (isset($_POST['attachment'][$i]) && $_POST['attachment'][$i] != '')
								{
								$message .= "\nAttachment - ".$_POST['attachment'][$i];
								}

							$message .= "\n----------------------------------------------------------------------\n";
							}

						$message .= "\nRegards\n\n";
						$message .= $socketfromname;
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="text"><?php echo supportSendMail($_POST['email'], $_POST['name'], 'Response to your Support Ticket ID - '.$tickets_reference, $message) ?></td>
  </tr>
</table>
<?php
						}
					}
				}

	// QUERY TO GET THE TICKET INFORMATION

			$query = "	SELECT a.*, tickets_status_id, tickets_status_name, tickets_status_color, tickets_categories_id, tickets_categories_name, cs_userId,cs_reseller_id, cd.gateway_id, d.cs_gateway_id, d.tickets_users_name,
					name, surname, td.userId as td_userId, cancelstatus, td_enable_rebill, td_transactionId
			
					FROM tickets_tickets a, tickets_status b, tickets_categories c, tickets_users d
					left join cs_companydetails cd on cs_userId = cd.userId
					left join cs_transactiondetails td on transactionId = a.td_transactionId
					WHERE (a.tickets_id = '".intval($_GET['ticketid'])."'
					AND tickets_child = '0')
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id
					AND a.tickets_username = d.tickets_users_username
					$sql_user_limit
					$sql_ticket_limit
					ORDER BY tickets_id ASC";
					
			$result       = sql_query_read($query) or dieLog(mysql_error());
			$totaltickets = mysql_num_rows($result);
			$row          = mysql_fetch_array($result);
?>
  <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?=$_GET['ticketid']?>&amp;sub=updatestatus" >
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
  
  <td class="boxborder" width="50%" valign="top" style="padding-top:5px">
  
  <script language="javascript" src="../../php_scripts/spell_checker/cpaint2.inc.compressed.js"></script>
  <script language="javascript" src="../../php_scripts/spell_checker/spell_checker.js"></script>
  
  <table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
    <tr bgcolor="#AABBDD">
      <td class="boxborder text"><b>Ticket #<?php echo $_GET['ticketid'] ?></b></td>
      <td class="boxborder list-menu"><?php
				IF ($row['tickets_status'] == 'Open')
					{
?>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;closesub=Closed&amp;name=<?php echo urlencode($row['tickets_name']) ?>&amp;email=<?php echo $row['tickets_email'] ?>">Close Ticket</a>
        <?php
					}
				ELSE
					{
?>
        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;closesub=Open">Reopen Ticket</a>
        <?php
					}
?>      </td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Account:</b></td>
      <td class="boxborder text"><?php echo $row['tickets_username'] ?></td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Name:</b></td>
      <td class="boxborder text">
	  <?php if ($row['cs_userId']>0) { ?>
        <a href="<?=$etel_domain_path?>/admin/editCompanyProfileAccess.php?company_id=<?=$row['cs_userId']?>"><?=$row['tickets_name']?></a>
        <?php } 
		else
		if ($row['cs_reseller_id']>0) { ?>
        <a href="<?=$etel_domain_path?>/admin/modifyReseller.php?reseller_id==<?=$row['cs_reseller_id']?>"><?=$row['tickets_name']?></a>
        <?php }
		else echo $row['tickets_name']; ?>      </td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Email:</b></td>
      <td class="boxborder text">
	    <input name="email" size="40" value="<?=$row['tickets_email'] ?>" /></td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Subject:</b></td>
      <td class="boxborder text"><?php echo $row['tickets_subject'] ?></td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Department:</b></td>
      <td class="boxborder text"><select name="tickets_category" id="tickets_category">
        <?=get_fill_combo_conditionally("select tickets_categories_id, tickets_categories_name from tickets_categories",$row['tickets_categories_id'])?>
      </select></td>
    </tr>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Category:</b></td>
      <td class="boxborder text"><select name="tickets_urgency" id="tickets_urgency">
          <?=get_fill_combo_conditionally("SELECT tickets_status_id, tickets_status_name, concat('background-color:#',tickets_status_color) as style FROM tickets_status	ORDER BY tickets_status_order ASC",$row['tickets_urgency'])?>
        </select>
        <input type="hidden" name="gateway_id" value="<?=$_REQUEST['gateway_id']?>" />
        <input type="hidden" name="tickets_reference" value="<?=$row['tickets_reference']?>" />      </td>
    </tr>
	<tr>
	  <td bgcolor="#DDDDDD" colspan="2" style="text-align:center; font-size:10px;"> - Transaction Info - </td>
	</tr>
	    <?php if ($row['td_transactionId']) { 
		$transactionInfo = getTransactionInfo($row['td_transactionId'],false,'reference_number');
		}
	?>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Transaction:</b></td>
      <td class="boxborder text">
	  
	  <select name="td_transactionId" id="td_transactionId" onchange="document.getElementById('transInfoID').style.visibility=(this.value?'visible':'hidden');document.getElementById('InfoLink').href='https://www.etelegate.com/admin/viewTransaction.php?ref='+this.value;" >
		  <?php //if ($transactionInfo['reference_number']) {  ?>
		  <?php //} else { ?>
          <option value="">None</option>
		  <?php //} ?>
          <?=get_fill_combo_conditionally("SELECT reference_number, concat(reference_number,' - ',if(status='A',concat('$',amount),'Declined'), DATE_FORMAT(transactionDate, ' (%b %D %Y %k:%i)')) as info FROM cs_transactiondetails where (email = '".$row['tickets_email']."') ORDER BY status DESC ,reference_number ASC",$transactionInfo['reference_number']);?>
		  
          <option value="" onclick="this.value=prompt('Please Enter the Transaction Reference ID:',''); this.text=this.value; if(!this.value) this.text='Add Reference';" >Add Reference ID</option>
        </select>
		<label id='transInfoID' style="visibility:<?=$row['td_transactionId']?'hidden':'hidden'?>">
        <a id="InfoLink" href="https://www.etelegate.com/admin/viewTransaction.php?ref=<?=$row['reference_number']?>">Info</a>
		- <a href="https://www.etelegate.com/admin/report_Smart.php?email=<?=$row['tickets_email']?>" >All</a>
		- <a href="https://www.etelegate.com/admin/editCompanyProfileAccess.php?company_id=<?=$transactionInfo['userId']?>" >Merchant</a>		</label>
		<br />
		<script>document.getElementById('td_transactionId').onchange();</script>
        <input type="hidden" name="service_notes" id="service_notes" value="" />    </tr>
<?php if(is_array($transactionInfo)) { ?>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Status:</b></td>
      <td class="boxborder text" ><?=$transactionInfo['userActiveMsg'].($transactionInfo['ss_ID']?" - <a href='../../admin/viewSubscription.php?subscription_ID=".$transactionInfo['ss_subscription_ID']."'>View Subscription: ".$transactionInfo['ss_subscription_ID']."</a>":"")?></td>
    </tr>	
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>First/Last Name: </b></td>
      <td class="boxborder text" ><input name="name" type="text" id="name" value="<?=$transactionInfo['name']?>" size="12" style="font-size:10px" />
	    - 
	      <input name="surname" type="text" id="surname" value="<?=$transactionInfo['surname']?>" size="13" style="font-size:10px" /></td>
    </tr>	
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Username/Password:</b></td>
      <td class="boxborder text" ><input name="td_username" type="text" id="td_username" value="<?=$transactionInfo['ss_cust_username']?>" size="12" style="font-size:10px" />
	    - 
	      <input name="td_password" type="text" id="td_password" value="<?=$transactionInfo['ss_cust_password']?>" size="13" style="font-size:10px" /></td>
    </tr>	
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Email/Phone:</b></td>
      <td class="boxborder text" ><input name="email" type="text" id="email" value="<?=$transactionInfo['email']?>" size="24" style="font-size:10px" />
         -
	      <input name="phonenumber" type="text" id="phonenumber" value="<?=$transactionInfo['phonenumber']?>" size="24" style="font-size:10px" /></td>
    </tr>	
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>City/St/Cntry/Zip:</b></td>
      <td class="boxborder text" ><input name="city" type="text" id="city" value="<?=$transactionInfo['city']?>" size="12" style="font-size:10px" /> 
	    -  
	      <input name="state" type="text" id="state" value="<?=$transactionInfo['state']?>" size="12" style="font-size:10px" /> 
	    - 
	    <input name="country" type="text" id="country" value="<?=$transactionInfo['country']?>" size="5" style="font-size:10px" /> 
	    - 	  
	    <input name="zipcode" type="text" id="zipcode" value="<?=$transactionInfo['zipcode']?>" size="7" style="font-size:10px" /></td>
    </tr>	
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Order:</b></td>
      <td class="boxborder text" >$<?=formatMoney($transactionInfo['amount'])?> - <?=$transactionInfo['td_product_id']?> <?=$transactionInfo['productdescription']?>&nbsp;</td>
    </tr>	
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Purchase Info:</b></td>
      <td class="boxborder text" ><?=$transactionInfo['transaction_date_formatted']?>, Expires: <?=$transactionInfo['expires']?>, Expired:<?=$transactionInfo['expired']?>&nbsp;</td>
    </tr>	

    <? } ?>
		
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Action:</b></td>
      <td class="boxborder text" >
	    <?php if($row['cancelstatus']=='N') { ?><input name="submit" type="submit" id="submit" value="Refund Order" onclick="document.getElementById('service_notes').value=prompt('Please enter the Refund Reason:',document.getElementById('tickets_issue').value); return document.getElementById('service_notes').value;" /><? } 
	    else { echo "Refunded: ".$transactionInfo['cancellationDate']; } ?>
		<?php if($transactionInfo['userActiveCode'] == "ACT") { ?><input name="submit" type="submit" id="submit2" value="Cancel Rebill" onclick="document.getElementById('service_notes').value=prompt('Please enter the Cancelation Reason:',document.getElementById('tickets_issue').value); return document.getElementById('service_notes').value;" /><? } ?>
		<?php if($_GET['test']) { ?>
		<input name="submit" type="submit" id="submit" value="Redirect to Merchant" />
		<? } ?></td>
    </tr>
	<tr><td bgcolor="#DDDDDD" colspan="2" style="text-align:center; font-size:10px;"> - Ticket Status - </td></tr>
    
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Issue:</b></td>
      <td class="boxborder text" >
	    <select name="tickets_issue" id="tickets_issue" >
          <option value="" onclick="this.value=prompt('Please Enter A Brief Description of the Issue:\n(Do this only if this issue is not already in the issue list)',''); this.text=this.value;" >Add New</option>
          <?=get_fill_combo_conditionally("select tickets_issue, tickets_issue as t2 from `tickets_tickets` Group BY `tickets_issue`",$row['tickets_issue'])?>
		</select>
      </td>
    </tr>
	<tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Click to Update: </b></td>
      <td class="boxborder text" style="color:<?=($row['tickets_status']=='Closed'?'#FF0000':'')?>"><input name="submit" type="submit" id="submit" value="Update Ticket" /></td>
    </tr>
	<tr><td bgcolor="#DDDDDD" colspan="2" style="text-align:center; font-size:10px;"> - Response - </td></tr>
    <tr>
      <td bgcolor="#EEEEEE" class="boxborder text"><b>Template:</b></td>
      <td class="boxborder text" ><select name="tickets_et_custom_id" id="tickets_et_custom_id" >
          <option value="" >No Template</option>
          <?=get_fill_combo_conditionally("select distinct et_custom_id, et_title from `cs_email_templates` where `et_name` = 'support_ticket_template' ORDER BY `et_title` ASC",$_REQUEST['tickets_et_custom_id'])?>
        </select>
        <br />
        <input type="hidden" name="et_title" id="et_title" value="" />
        <input type="submit" name="btn_template" value="Use Template" />
        <input type="submit" value="Save As Template" onclick="document.getElementById('et_title').value = prompt('Please Enter The Template Name:',document.getElementById('tickets_et_custom_id').options[document.getElementById('tickets_et_custom_id').selectedIndex].text); return(document.getElementById('et_title').value)" />      </td>
    </tr>
  </table>
  <div style="padding-top:5px"></div>
  <?php
			IF ($row['tickets_status'] != 'Closed')
				{
$name = $row['tickets_users_name'];
if ($row['name']) $name = $row['name'];
$post_content="Dear $name,

$custom_response_template

Thank you,
$socketfromname";
$_REQUEST['message'] = stripslashes($_REQUEST['message']);
if($_REQUEST['message'] && !$custom_response_template) $post_content=$_REQUEST['message'];
				
?>
  <table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
    <tr bgcolor="#AABBDD">
      <td class="boxborder text"><b>Respond</b></td>
    </tr>
    <tr>
      <td align="left"><textarea title="spellcheck" accesskey="../../php_scripts/spell_checker/spell_checker.php" id="message" name="message" type="text" style="width: 500px; height: 200px;"/><?=$post_content?></textarea></td>
    </tr>
    <tr>
      <td align="right"><input type="hidden" name="gateway_id" value="<?=$_REQUEST['gateway_id']?>" />
        <input type="hidden" name="tickets_reference" value="<?=$row['tickets_reference']?>" />
        <input type="hidden" name="name" value="<?php echo $row['tickets_name'] ?>" />
        <input type="hidden" name="postuser" value="<?php echo $row['tickets_username'] ?>" />
        <input type="hidden" name="email_" value="<?php echo $row['tickets_email'] ?>" />
        <input type="hidden" name="postsubject" value="<?php echo $row['tickets_subject'] ?>" />
        <input type="hidden" name="posturgency" value="<?php echo $row['tickets_status_id'] ?>|<?php echo $row['tickets_status_name'] ?>" />
        <input type="hidden" name="postdept" value="<?php echo $row['tickets_categories_id'] ?>|<?php echo $row['tickets_categories_name'] ?>" />
        <input type="submit" name="submit" value="Submit Ticket" />
      </td>
    </tr>
  </table>
  <div style="padding-top:5px"></div>
  <?php
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

				IF ($allowattachments == 'TRUE')
					{
					FileUploadForm();
					}
				}
?>
  <br />
  </td>
  
  <td width="50%" valign="top" style="padding-top:5px"><?php
	// LIST THE ASSOCIATED RESPONSES TO THIS TICKET

			$query = "	SELECT a.*
					FROM tickets_tickets a
					WHERE (a.tickets_id = '".intval($_GET['ticketid'])."'
					OR tickets_child = '".intval($_GET['ticketid'])."')
					ORDER BY tickets_timestamp DESC";

			$result = sql_query_read($query);
			$ticket_num = mysql_num_rows($result)-1;
			$j = $ticket_num;
			WHILE ($row = mysql_fetch_array($result))
				{
?>
    <table width="280" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
      <tr bgcolor="#AABBDD">
        <td class="boxborder text"><b>
          <?php
				IF ($j == '0')
					{
					echo '	Initial Message';
					}
				ELSE IF ($j == $ticket_num)
					{
					echo '	Latest Response';
					}
				ELSE
					{
					echo '	Response '.$j;
					}
?>
          </b></td>
        <td class="boxborder text" bgcolor="#AACCDD" width="50%" align="right"><?php echo date($dformat.' H:i:s', $row['tickets_timestamp']) ?></td>
      </tr>
      <?php
				IF ($row['tickets_admin'] == 'Admin')
					{
					$bgcolor = '#FFF000';
					}
				ELSE
					{
					$bgcolor = '#AACCEE';
					}
?>
      <tr>
        <td class="boxborder text" colspan="2" ><?=nl2br( wordwrap($row['tickets_question'],70,"\n",1)); ?></td>
      </tr>
      <tr bgcolor="<?php echo $bgcolor ?>">
        <td class="boxborder text">Posted By: <?php echo $row['tickets_username'] ?></td>
        <td class="boxborder text" align="right"><?php
	// SCAN THE UPLOAD DIRECTORY FOR ATTACHMENTS TO THIS POST

				$d = dir($uploadpath);

				WHILE (false !== ($files = $d -> read()))
					{
					IF ($files != '.' && $files != '..')
						{
						$files = explode('.', $files);

						IF ($files['0'] == $row['tickets_id'])
							{
?>
          <b>Attachment:</b> <?php echo $files['0'] ?>.<?php echo $files['1'] ?> <a href="<?php echo $relativepath.$files['0'] ?>.<?php echo $files['1'] ?>" target="_blank"> <img src="../images/download.gif" width="13" height="13" align="absmiddle" border="0" /></a>
          <?php
							$filename = $files['0'].'.'.$files['1'];
?>
          <input type="hidden" name="attachment[<?php echo $tickets_id - 1 ?>]" value="<?php echo $filename ?>" />
          <?php
							}
						ELSE
							{
							$filename = '';
							}
						}
					}

				$d -> close();
?>
        </td>
      </tr>
    </table>
    <div style="padding-top:5px"></div>
    <input type="hidden" name="ticketquestion[]" value="<?php echo $tickets_question ?>" />
    <input type="hidden" name="postedby[]" value="<?php echo $tickets_admin ?>" />
    <input type="hidden" name="postdate[]" value="<?php echo date($dformat.' H:i:s', $tickets_timestamp) ?>" />
    <?php

				$j --;
				}

	// IF ATTACHMENTS ARE TRUE THEN SHOW ALLOWED FILETYPES

			IF ($allowattachments == 'TRUE')
				{
?>
    <table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
      <tr>
        <td class="text" colspan="2"><b>Allowed FILE TYPES for attachments:</b><br />
          <?php
				FOR ($i = '0'; $i <= COUNT($allowedtypes) - 1; $i++)
					{
					echo $allowedtypes[$i].'<br />';
					}
?>
        </td>
      </tr>
    </table>
    <?php
				}
?>

    <table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
	<tr>
	  <td class="text" >
	    <p><strong>Support Ticket Instructions:</strong></p>
	    <ul>
	      <li>Verify the ticket is in the correct <strong>Department</strong>.</li>
	      <li>	        If the ticket involves a Transaction, enter the <strong>Reference ID</strong> of that transaction.</li>
	      <li> Update the <strong>Issue</strong> this transaction involves. If no issue is found, create a new issue.</li>
	      <li> If a <strong>Template</strong> for this issue exists, use the template to answer.<br />
	        Answer the support ticket. </li>
	    </ul></td>
	</tr>
	</table>

  </td>
  </tr>
  
</table>
</form>
<?php
			IF ($_POST['submit'] == 'Submit Ticket')
				{
?>
<meta http-equiv="refresh" content="2;url=<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>" />
<?php
				}

		BREAK;


#############################################################################################
############################## AREA TO ADD USER ADMINISTRATION ##############################
#############################################################################################

		CASE 'AddUser':

	// EDIT OR DELETE USER SETTINGS

			IF (isset($_REQUEST['sub']) && isset($_REQUEST['memberid']))
				{
				IF ($_REQUEST['sub'] == '1' || $_REQUEST['sub'] == '2')
					{
					$query = "	UPDATE tickets_users
							SET tickets_users_status = '".$_GET['sub']."'
							WHERE tickets_users_id   = '".$_GET['memberid']."' $sql_user_limit";

					IF ($_REQUEST['sub'] == '1')
						{
						$actiontaken = 'Activated';
						}
					ELSE
						{
						$actiontaken = 'Suspended';
						}
					}

				ELSEIF ($_POST['sub'] == 'Delete')
					{
					$query = "	DELETE FROM tickets_users
							WHERE tickets_users_id = '".$_POST['memberid']."' $sql_user_limit";

					$actiontaken = 'User Deleted';
					}

				ELSEIF ($_REQUEST['sub'] == 'Edit')
					{
					$query = "	UPDATE tickets_users
							SET
							tickets_users_name     = '".$_POST['name']."',
							tickets_users_password = '".$_POST['password']."',
							tickets_users_email    = '".$_POST['email']."',
							tickets_users_admin    = '".$_POST['type']."'
							WHERE tickets_users_id = '".$_REQUEST['memberid']."' $sql_user_limit";

					$actiontaken = 'Edited User';
					}

				$result = sql_query_write($query);

				PageTitle('Support Tickets User '.$_REQUEST['memberid'].' '.$actiontaken);
				}
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="#AACCEE">
    <td class="text">Please add in all the details below for the user.</td>
  </tr>
</table>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="text"> The username and password must be between 6 and 16 characters in length. They cannot contain any spaces or unusual characters.<br />
      <br />
      You cannot edit a Username once this has been set, as the application depends on this. The username must be Unique within the system.<br />
      <br />
      Choose the type of the user you wish to add, selecting USER will allow a user to login to the client side only.<br />
      <br />
      Moderators are able to browse tickets in there department only, they are not allowed to perform admin activities.<br />
      <br />
      Only Admins can perform admin activities and add other users/moderators or admins.<br />
      <br />
      <form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser" Method="post">
        <table width="300" cellpadding="1" cellspacing="1" align="center">
          <tr>
            <td class="text">Name:</td>
            <td><input name="name" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['name']) && $_POST['name'] != '')
				{
				echo ' value="'.$_POST['name'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/></td>
          </tr>
          <tr>
            <td class="text">Username:</td>
            <td><input name="username" size="30"
<?php
			IF (isset($_POST['userform']) && (isset($_POST['username']) && $_POST['username'] != ''))
				{
				IF (!eregi('^[0-9a-z]{6,16}$', $_POST['username']))
					{
					echo ' style="background-color:#FDD3D4"';
					$error = 'T';
					}
				ELSE
					{
					$query = "	SELECT tickets_users_id
							FROM tickets_users
							WHERE tickets_users_username = '".$_POST['username']."' $sql_user_limit
							LIMIT 0,1";

					$result = sql_query_read($query);
					$total  = mysql_num_rows($result);

					IF ($total > '0')
						{
						echo ' style="background-color:#FDD3D4"';
						$error = 'T';
						}
					ELSE
						{
						echo ' value="'.$_POST['username'].'"';
						}
					}
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/></td>
          </tr>
          <tr>
            <td class="text">Password:</td>
            <td><input name="password" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['password']) && eregi('^[0-9a-z]{6,16}$', $_POST['password']) && strlen($_POST['password']) >= '6')
				{
				echo ' value="'.$_POST['password'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/></td>
          </tr>
          <tr>
            <td class="text">Email:</td>
            <td><input name="email" size="30"
<?php
			IF (isset($_POST['userform']) && ereg('^.+@.+\\..+$', $_POST['email']))
				{
				echo ' value="'.$_POST['email'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/></td>
          </tr>
          <tr>
            <td  class="text" align="center" colspan="2"> User:
              <input checked type="radio" name="type" value="User" />
              Mod:
              <input type="radio" name="type" value="Mod" />
              Admin:
              <input type="radio" name="type" value="Admin" />
            </td>
          </tr>
          <tr>
            <td align="center" colspan="2"><input type="submit" name="userform" value="Submit" /></td>
          </tr>
          <?php
			IF (!isset($error))
				{
				$query = "	INSERT INTO tickets_users
						SET
						tickets_users_name     = '".$_POST['name']."',
						tickets_users_username = '".$_POST['username']."',
						tickets_users_password = '".$_POST['password']."',
						tickets_users_email    = '".$_POST['email']."',
						tickets_users_admin    = '".$_POST['type']."'";

				$result = sql_query_write($query);
?>
          <tr>
            <td colspan="2"><br />
              <b>Everythings OK User added.</b></td>
          </tr>
          <?php
				}
?>
        </table>
        <br />
      </form></td>
  </tr>
</table>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="boxborder text" colspan="8" bgcolor="#AACCEE">Users Already In The System.</td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td class="boxborder text"><b>No.</b></td>
    <td class="boxborder text"><b>Name</b></td>
    <td class="boxborder text"><b>Username</b></td>
    <td class="boxborder text"><b>Password</b></td>
    <td class="boxborder text"><b>Email</b></td>
    <td class="boxborder text"><b>Type</b></td>
    <td class="boxborder text"><b>Status</b></td>
    <td class="boxborder text"><b>Action</b></td>
  </tr>
  <?php
	// LOOP THROUGH ALL EXISTING USERS IN THE DATABASE AND GIVE OPTIONS TO SUSPEND - DELETE ETC

			$query = '	SELECT  tickets_users_id, tickets_users_name, tickets_users_username,
						tickets_users_password, tickets_users_email, tickets_users_admin,
						tickets_users_status
					FROM tickets_users where 1 $sql_user_limit
					ORDER BY tickets_users_name';

			$result = sql_query_write($query);

			$j = '1';

			WHILE ($row = mysql_fetch_array($result))
				{
				IF ($row['tickets_users_status'] == '1')
					{
					$status = 'Active';
					}
				ELSE
					{
					$status = 'Suspended';
					}
?>
  <form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser" Method="post">
    <tr bgcolor="<?php echo UseColor() ?>">
      <td class="boxborder text"><?php echo $j ?></td>
      <td class="boxborder"><input name="name" value="<?php echo $row['tickets_users_name'] ?>" size="15" /></td>
      <td class="boxborder text"><?php echo $row['tickets_users_username'] ?></td>
      <td class="boxborder"><input name="password" value="<?php echo $row['tickets_users_password'] ?>" size="17" /></td>
      <td class="boxborder"><input name="email" value="<?php echo $row['tickets_users_email'] ?>" size="35" /></td>
      <td class="boxborder"><input name="type" value="<?php echo $row['tickets_users_admin'] ?>" size="10" /></td>
      <td class="boxborder text"><?php echo $status ?></td>
      <td class="boxborder"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser&amp;
<?php
				IF ($row['tickets_users_status'] == '1')
					{
?>
					sub=2&amp;memberid=<?php echo $row['tickets_users_id'] ?>">Suspend
        <?php
					}
				ELSE
					{
?>
        sub=1&amp;memberid=<?php echo $row['tickets_users_id'] ?>">Activate
        <?php
					}
?>
        </a>
        <input type="submit" name="sub" value="Delete" onclick="return deletemember()" />
        <input type="hidden" name="memberid" value="<?php echo $row['tickets_users_id'] ?>">
        <input type="submit" name="sub" value="Edit" />
      </td>
    </tr>
  </form>
  <?php
				$j++;
				}
?>
</table>
<?php
		BREAK;


#############################################################################################
####################### SHOW THE USER THE CHOICE OF DOCUMENTS TO READ #######################
#############################################################################################

		CASE 'document':

	// DEFAULT TO CHANGELOG

			IF (!isset($_GET['f']))
				{
				$_GET['f'] = 'ChangeLog';
				}
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="#AACCEE">
    <td class="text"> [Please select a file to view from the list below. Simply click the
      radio button and hit submit.] </span>
      </h1></td>
  </tr>
  <tr>
    <td class="boxborder"><p> <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=ChangeLog">| Change Log File |</a> <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=Install">Install File |</a> <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=Licence">Licence File |</a> <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=ReadMe">ReadMe File |</a> <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=Todo">Todo File |</a> <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=Version">Version File |</a>
      <p></td>
  </tr>
</table>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td><iframe	src="../documents/<?php echo $_GET['f'] ?>.txt"
					frameborder="0"
					framespacing="0"
					width="100%"
					height="450"> </iframe></td>
  </tr>
</table>
<?php
		BREAK;


#############################################################################################
############################## DISPLAY THE CATEGORIES SECTION ###############################
#############################################################################################

		CASE 'cats':

	// EDIT OR DELETE USER SETTINGS

			IF (isset($_POST['sub']) && isset($_POST['depid']))
				{
				IF ($_POST['sub'] == 'Delete')
					{
					$query = "	DELETE FROM tickets_categories
							WHERE tickets_categories_id = '".$_POST['depid']."'";

					$actiontaken = 'Department Deleted';
					}

				ELSEIF ($_POST['sub'] == 'Edit')
					{
					$query = "	UPDATE tickets_categories
							SET
							tickets_categories_name     = '".$_POST['department']."',
							tickets_categories_email     = '".$_POST['tickets_categories_email']."',
							tickets_categories_emailname     = '".$_POST['tickets_categories_emailname']."'
							WHERE tickets_categories_id = '".$_POST['depid']."'";

					$actiontaken = 'Edited';
					}

				$result = sql_query_write($query);

				PageTitle('Department '.$_POST['depid'].' '.$actiontaken);
				}
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="#AACCEE">
    <td class="text">Please add in all the details below for the each department.</td>
  </tr>
</table>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=cats" method="post">
  <table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
    <tr>
      <td class="text"><br />
        Be careful about deleting departments. Deleting them will cause
        errors with all tickets assigned to that particular department. Therefore be careful
        when you add them, make sure they are concise and what you want.<br />
        <br />
        <table width="350" cellpadding="0" cellspacing="0" align="center">
          <tr>
            <td class="text">Department Name:</td>
            <td><input name="name" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['name']) && $_POST['name'] != '')
				{
				echo ' value="'.$_POST['name'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/>
              <input type="submit" value="Submit" name="userform" /></td>
          </tr>
          <?php
			IF (!isset($error))
				{
				$query = "	INSERT INTO tickets_categories
						SET
						tickets_categories_name = '".$_POST['name']."'";

				$result = sql_query_write($query);
?>
          <tr>
            <td class="text" colspan="2"><b>Everythings OK Department added.</b></td>
          </tr>
          <?php
				}
?>
        </table>
        <br />
      </td>
    </tr>
  </table>
</form>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="boxborder text" colspan="3" bgcolor="#AACCEE">Departments Already In The System.</td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td class="boxborder text"><b>ID.</b></td>
    <td class="boxborder text"><b>Department</b></td>
    <td class="boxborder text"><b>Email</b></td>
    <td class="boxborder text"><b>EmailName</b></td>
    <td class="boxborder text"><b>Action</b></td>
  </tr>
  <?php
	// LOOP THROUGH ALL EXISTING USERS IN THE DATABASE AND GIVE OPTIONS TO SUSPEND - DELETE ETC

			$query = '	SELECT *
					FROM tickets_categories
					ORDER BY tickets_categories_id ASC';

			$result = sql_query_read($query);

			WHILE ($row = mysql_fetch_array($result))
				{
?>
  <form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=cats" Method="post">
    <tr bgcolor="<?php echo UseColor() ?>">
      <td class="boxborder"><?php echo $row['tickets_categories_id'] ?></td>
      <td class="boxborder"><input name="department" value="<?php echo $row['tickets_categories_name'] ?>" size="40" /></td>
      <td class="boxborder"><input name="tickets_categories_email" value="<?php echo $row['tickets_categories_email'] ?>" size="40" /></td>
      <td class="boxborder"><input name="tickets_categories_emailname" value="<?php echo $row['tickets_categories_emailname'] ?>" size="40" /></td>
      <td class="boxborder"><input type="submit" name="sub" value="Delete" onclick="return deletemember()" />
        <input type="hidden" name="depid" value="<?php echo $row['tickets_categories_id'] ?>">
        <input type="submit" name="sub" value="Edit" />
      </td>
    </tr>
  </form>
  <?php
				}
?>
</table>
<?php
		BREAK;


#############################################################################################
################################ DISPLAY THE STATUS SECTION #################################
#############################################################################################

		CASE 'status':

	// EDIT OR DELETE USER SETTINGS

			IF (isset($_POST['sub']) && isset($_POST['depid']))
				{
				IF ($_POST['sub'] == 'Delete')
					{
					$query = "	DELETE FROM tickets_status
							WHERE tickets_status_id = '".$_POST['depid']."'";

					$actiontaken = 'Status Deleted';
					}

				ELSEIF ($_POST['sub'] == 'Edit')
					{
					$query = "	UPDATE tickets_status
							SET
							tickets_status_name     = '".$_POST['status']."',
							tickets_status_order    = '".$_POST['order']."',
							tickets_status_color    = '".$_POST['color']."'
							WHERE tickets_status_id = '".$_POST['depid']."'";

					$actiontaken = 'Edited';
					}

				$result = sql_query_write($query);

				PageTitle('Status '.$_POST['depid'].' '.$actiontaken);
				}
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="#AACCEE">
    <td class="text">Please add in all the details below for the each Urgency.</td>
  </tr>
</table>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=status" method="post">
  <table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
    <tr>
      <td class="text"><br />
        Be careful about deleting Urgency's. Deleting them will cause
        errors with all tickets assigned to that particular status. Therefore be careful
        when you add them, make sure they are concise and what you want. Order refers
        to where in the list it will appear, 1 being first.<br />
        <br />
        <table width="300" cellpadding="0" cellspacing="0" align="center">
          <tr>
            <td class="text">Urgency Name:</td>
            <td><input name="name" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['name']) && $_POST['name'] != '')
				{
				echo ' value="'.$_POST['name'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/>
              <input type="submit" value="Submit" name="userform" /></td>
          </tr>
          <?php
			IF (!isset($error))
				{
				$query = "	INSERT INTO tickets_status
						SET
						tickets_status_name = '".$_POST['name']."'";

				$result = sql_query_write($query);
?>
          <tr>
            <td class="text" colspan="2"><b>Everythings OK Status added.</b></td>
          </tr>
          <?php
				}
?>
        </table>
        <br />
      </td>
    </tr>
  </table>
</form>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="boxborder text" colspan="5" bgcolor="#AACCEE">Urgent Elements Already In The System.</td>
  </tr>
  <tr bgcolor="#EEEEEE">
    <td class="boxborder text"><b>ID.</b></td>
    <td class="boxborder text"><b>Urgency</b></td>
    <td class="boxborder text"><b>Order</b></td>
    <td class="boxborder text"><b>Color</b></td>
    <td class="boxborder text"><b>Action</b></td>
  </tr>
  <?php
	// LOOP THROUGH ALL EXISTING USERS IN THE DATABASE AND GIVE OPTIONS TO SUSPEND - DELETE ETC

			$query = '	SELECT tickets_status_id, tickets_status_name, tickets_status_order, tickets_status_color
					FROM tickets_status
					ORDER BY tickets_status_id ASC';

			$result = sql_query_read($query);

			WHILE ($row = mysql_fetch_array($result))
				{
?>
  <form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=status" Method="post">
    <tr bgcolor="<?php echo UseColor() ?>">
      <td class="boxborder text"><?php echo $row['tickets_status_id'] ?></td>
      <td class="boxborder"><input name="status" value="<?php echo $row['tickets_status_name'] ?>" size="40" /></td>
      <td class="boxborder"><input name="order" value="<?php echo $row['tickets_status_order'] ?>" size="20" /></td>
      <td class="boxborder text" bgcolor="#<?php echo $row['tickets_status_color'] ?>"><input name="color" value="<?php echo $row['tickets_status_color'] ?>" size="20" /></td>
      <td class="boxborder"><input type="submit" name="sub" value="Delete" onclick="return deletemember()" />
        <input type="hidden" name="depid" value="<?php echo $row['tickets_status_id'] ?>">
        <input type="submit" name="sub" value="Edit" />
      </td>
    </tr>
  </form>
  <?php
				}
?>
</table>
<?php
		BREAK;


#############################################################################################
#################################### CREATE A NEWTICKET #####################################
#############################################################################################

		CASE 'NewTicket':

	// IF THE FORM IS SUBMITTED THEN VERIFY SOME CONTENTS

			IF (isset($_GET['sub']))
				{

	// IF FORM IS NOT FILLED OUT CORRECTLY THEN SHOW ERROR MESSAGES

				IF ($_POST['message'] == '' || $_POST['name'] == '' || $_POST['email'] == '' || !ereg('^..*\@.+\..+[A-Za-z0-9]$', $_POST['email']) || $_POST['ticketsubject'] == '')
					{
?>
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr bgcolor="#AACCEE">
    <td class="text">Please complete all the fields.</td>
  </tr>
</table>
<?php
					}

	// IF FORM IS OK THEN INSERT INTO THE DATABASE

				ELSE
					{
					IF (!isset($_POST['ticket_status']) || $_POST['ticket_status'] == '')
						{
						$_POST['ticket_status'] = 'Open';
						}

					$_POST['account']	= Clean_It($_POST['account']);
					$_POST['name']		= Clean_It($_POST['name']);
					$_POST['email']		= Clean_It($_POST['email']);
					$_POST['ticketsubject']	= Clean_It($_POST['ticketsubject']);
					$_POST['category']	= Clean_It($_POST['category']);
					$_POST['urgency']	= Clean_It($_POST['urgency']);
					$_POST['ticket_status']	= Clean_It($_POST['ticket_status']);
					$_POST['message']	= Clean_It($_POST['message']);

					$urgency  = explode('|', $_POST['urgency']);
					$category = explode('|', $_POST['category']);

					$tickets_reference  = strtoupper(substr(md5(time().rand(0,1000)),0,16));
					
					$query = "	INSERT INTO tickets_tickets
							SET
							tickets_username  = '".$_POST['account']."',
							tickets_subject	  = '".$_POST['ticketsubject']."',
							tickets_timestamp = '".mktime()."',
							tickets_status    = '".$_POST['ticket_status']."',
							tickets_name	  = '".$_POST['name']."',
							tickets_email	  = '".$_POST['email']."',
							tickets_urgency	  = '".$urgency['0']."',
							tickets_category  = '".$category['0']."',
							tickets_reference  = '$tickets_reference',
							tickets_question  = '".addslashes($_POST['message'])."'";

					IF ($result = sql_query_write($query))
						{
						$lastinsertid = mysql_insert_id();

	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

						IF ($allowattachments == 'TRUE' && !isset($_COOKIE['demomode']) || $demomode != 'ON')
							{
							FileUploadsVerification("$_FILES(userfile)", mysql_insert_id());
							}
	// EMAIL ADMINISTRATOR THE TICKET NOTIFICATION
	
$query = "	SELECT *
		FROM tickets_categories WHERE tickets_categories_id = '$category[0]'
		ORDER BY tickets_categories_name ASC";

$result = sql_query_read($query);
$cat_info = mysql_fetch_assoc($result);
$socketfrom = "Ticket-".$tickets_reference."@".$socketdomain;
//if($cat_info['tickets_categories_emailname']) $socketfromname = $cat_info['tickets_categories_emailname'];


						$message  = "Ticket ID:\t $tickets_reference:".$_GET['ticketid']."\n";
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
<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
  <tr>
    <td class="text"><?php
						echo supportSendMail(	$_POST['email'],
								$_POST['name'],
								'Support Ticket Written By - '.$_POST['account'],
								$message);

						IF ($emailuser == 'TRUE')
							{
							echo supportSendMail(	$_POST['email'],
									$_POST['name'],
									'Support Ticket Written By - '.$_POST['account'],
									$message,
									'1');
							}
?>
    </td>
  </tr>
</table>
<?php
						$refresh = 'TRUE';
						}
					}
				}

	// SELECT THE DIFFERENT FROM LOCATIONS ON WHETHER THE ACCOUNT IS CHOSEN

			IF (!isset($_POST['account']))
				{
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket" method="post">
<?php
				}
			ELSE
				{
?>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket&amp;sub=add" method="post">
  <?php
				}
?>
  <table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
    <tr>
      <td class="boxborder" width="50%" valign="top" style="padding-top:5px"><table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
          <tr bgcolor="#AABBDD">
            <td class="boxborder text" colspan="2"><b>New Support Ticket - All Fields Required</b></td>
          </tr>
          <tr>
            <td bgcolor="#EEEEEE" class="boxborder text"><b>Account:</b></td>
            <td class="boxborder text"><select name="account">
                <?php
	// LIST THE AVAILABLE ACCOUNT MEMBERS

			$query = "	SELECT tickets_users_name, tickets_users_username, tickets_users_email
					FROM tickets_users";

			IF (isset($_POST['account']))
				{
				$query .= " WHERE tickets_users_username = '".$_POST['account']."' $sql_user_limit
					    LIMIT 0,1";
				}
			ELSE
				{
				$query .= " WHERE tickets_users_admin = 'User' $sql_user_limit";
				$query .= " order by cs_userId !=0 desc, tickets_users_name Limit 50";
				}

			$result = sql_query_read($query);

			WHILE ($row = mysql_fetch_array($result))
				{
?>
                <option value="<?php echo $row['tickets_users_username'] ?>"><?php echo $row['tickets_users_name'] ?></option>
                <?php
				}
?>
              </select>
            </td>
          </tr>
          <?php
	// DONT SHOW THE REST OF THE FORM UNTIL THEY HAVE SELECTED THE ACCOUNT

			IF (isset($_POST['account']))
				{
				$result = sql_query_read($query);
				$row    = mysql_fetch_array($result);
?>
          <tr>
            <td bgcolor="#EEEEEE" class="boxborder text"><b>Name:</b></td>
            <td class="boxborder text"><input name="name" size="40" value="<?php echo $row['tickets_users_name'] ?>" /></td>
          </tr>
          <tr>
            <td bgcolor="#EEEEEE" class="boxborder text"><b>Email:</b></td>
            <td class="boxborder text"><input name="email" size="40" value="<?php echo $row['tickets_users_email'] ?>" /></td>
          </tr>
          <tr>
            <td bgcolor="#EEEEEE" class="boxborder text"><b>Subject:</b></td>
            <td class="boxborder text"><input name="ticketsubject" size="40"
<?php
				IF (isset($_POST['ticketsubject']) && $_POST['ticketsubject'] != '')
					{
					echo ' value="'.$_POST['ticketsubject'].'"';
					}
?>
					></td>
          </tr>
          <tr>
            <td bgcolor="#EEEEEE" class="boxborder text"><b>Department:</b></td>
            <td class="boxborder text"><select name="category">
                <?php
				$query = "	SELECT tickets_categories_id, tickets_categories_name
						FROM tickets_categories
						ORDER BY tickets_categories_name ASC";

				$result = sql_query_read($query);

				WHILE ($row = mysql_fetch_array($result))
					{
					echo '<option value="'.$row['tickets_categories_id'].'|'.$row['tickets_categories_name'].'">'.$row['tickets_categories_name'].'</option>';
					}
?>
              </select>
            </td>
          </tr>
          <tr>
            <td bgcolor="#EEEEEE" class="boxborder text"><b>Urgency:</b></td>
            <td class="boxborder text"><select name="urgency">
                <?php
				$query = "	SELECT tickets_status_id, tickets_status_name, tickets_status_color
						FROM tickets_status
						ORDER BY tickets_status_order ASC";

				$result = sql_query_read($query);

				WHILE ($row = mysql_fetch_array($result))
					{
					echo '<option style="background-color:#'.$row['tickets_status_color'].'" value="'.$row['tickets_status_id'].'|'.$row['tickets_status_name'].'">'.$row['tickets_status_name'].'</option>';
					}
?>
              </select></td>
          </tr>
          <tr>
            <td bgcolor="#EEEEEE" class="boxborder text"><b>Pre-Close:</b></td>
            <td class="boxborder text"><input type="checkbox" name="ticket_status" value="Closed" /></td>
          </tr>
        </table>
        <div style="padding-top:5px"></div>
        <table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
          <tr bgcolor="#AABBDD">
            <td class="boxborder text"><b>Question</b></td>
          </tr>
          <tr>
            <td align="center"><textarea name="message" cols="80" rows="10"><?=$_POST['message']?>
</textarea>
            </td>
          </tr>
          <?php
				}
?>
          <tr>
            <td align="right" <?php IF (!isset($_POST['account'])) echo 'colspan="2"'; ?>><input type="submit" value="Submit" /></td>
          </tr>
          <?php
	// TEXT TO TELL ADMIN WHAT TO DO WITH A NEW TICKET

			IF (!isset($_POST['account']))
				{
?>
          <tr>
            <td class="text" colspan="2"> Firstly you must assign this ticket to an already active account. If the
              user is not active then please add them <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser" title="add user">here</a>. </td>
          </tr>
          <?php
				}
?>
        </table>
        <div style="padding-top:5px"></div>
        <?php
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

			IF (isset($_POST['account']) && $allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
				{
				FileUploadForm();
				}
?>
        <br /></td>
      <td class="boxborder" width="50%" valign="top" style="padding-top:5px"><table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
          <tr>
            <td class="text">Please fill in all the information. And make sure the question is very
              explicit as to what the problem is, some guidelines follow:
              <ul>
                <li>Type of question (bug / content / Other)</li>
                <li>When did you see this (date and time)</li>
                <li>Is there a location to see this bug (URL / Media)</li>
                <li>Description (detailed but concise)</li>
              </ul>
              Make sure all fields are filled in; the script will check for
              a correctly formed email address. Please choose the category that
              best suits this ticket. <br />
              <br /></td>
          </tr>
          <?php
	// IF ATTACHMENTS ARE TRUE THEN SHOW ALLOWED FILETYPES

			IF ($allowattachments == 'TRUE')
				{
?>
          <tr>
            <td class="text"><b>Allowed FILE TYPES for attachments:</b><br />
              <?php
				FOR ($i = '0'; $i <= COUNT($allowedtypes) - 1; $i++)
					{
					echo $allowedtypes[$i].'<br />';
					}
?>
            </td>
          </tr>
          <?php
				}
?>
        </table>
        <br />
      </td>
    </tr>
  </table>
</form>
<?php
			IF (isset($refresh) && $refresh == 'TRUE')
				{
?>
<meta http-equiv="refresh" content="2;URL=<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $lastinsertid ?>" />
<?php
				}

		BREAK;
		}


#############################################################################################
################################ ADD THE FOOTER INFORMATION #################################
#############################################################################################

	include_once ('footer.php');
	//include_once ('../../includes/footer.php');

	IF (isset($result))
		{
		mysql_free_result($result);
		mysql_close();
		}
?>
