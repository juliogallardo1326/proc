<?php
/***************************************************************************
File Name 	: eng.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: English language file.
Date Created	: Wednesday 19 January 2005 16:14:51
File Version	: 1.9
****************************************************************************/

	$charset = 'ISO-8859-1';


#############################################################################################
########################### USER AREA - LOGIN - RESEND - REGISTER ###########################
#############################################################################################

	// TOP NAVIGATION BAR

	$text_login    = 'Login';
	$text_register = 'Register';
	$text_resend   = 'Resend Details';
	$text_help     = 'Help';

	// LOGIN PAGE AND LOGIN ERROR

	$text_username  = 'Username';
	$text_password  = 'Password';
	$text_login     = 'Log In';
	$text_loginpage = '	<b>You have entered an invalid username/password
				combination. Please try again.</b><br /><br />
				If you are a registered user on this site then hit the
				button above that says Resend Details.<br /><br />Enter your
				email and we will email your details to you.';
	$text_loginback = 'Back';

	// RESEND DETAILS PAGE AND RESEND ERROR

	$text_resendpage  = '	The email must match the one you used when you created your account.<br />
				We will then send the username and password to the email given.';
	$text_email       = 'Email';
	$text_submit      = 'Submit';
	$text_resenderror = '	Sorry, the details you have entered do not match anything we have in our Database.<br /><br />
				Hit the back button to try again.';
	$text_resendback  = 'Back';

	// REGISTER PAGE AND REGISTER ERROR

	$text_regpage     = '	Please complete all fields. You will be sent a registration email.
				<ul>
				<li>Name - Keep this to Forename and Surname only please.</li>
				<li>Username - Use 6-16 Chars only, no spaces, [a-z][0-9] only.</li>
				<li>Password - Use 6-16 Chars only, no spaces, [a-z][0-9] only.</li>
				<li>Email - make sure this is a valid email address</li>
				</ul>';
	$text_regname     = 'Name:';
	$text_reguser     = 'Username:';
	$text_regpass     = 'Password:';
	$text_regemail    = 'Email:';
	$text_regsubmit   = 'Submit';
	$text_regpageerr  = '	Please complete all the fields and make sure the email is the correct format.
				<br /><br />Hit the back button to try again.';
	$text_regpagback  = 'Back';
	$text_regusererr  = '	Sorry someone else is already using that Username.<br /><br />
				Hit the back button to try again.';
	$text_regconf     = 'You have been registered and sent a confirmation email -  ';
	$text_regsubject  = 'Thank You For Registering';


#############################################################################################
################ USER AREA - ONCE LOGGED IN SHOW TICKETS - CREATE TICKETS ETC ###############
#############################################################################################

	// NAVIGATION TOP BAR

	$text_titlelink = $_SESSION["gw_title"];
	$text_title	= 'Support Tickets Manager';
	$text_titlereq	= 'New Ticket';
	$text_titleope	= 'Open Tickets';
	$text_titleclo	= 'Closed Tickets';
	$text_titlelog	= 'Logout';

	// HOME PAGE LISTING OF TICKETS

	$text_listtitle = 'Recent Tickets:';
	$text_listmsg   = 'Click on the Ticket Number to read the ticket.';
	$text_listtik   = 'Ticket ID [Replies]';
	$text_listsub   = 'Subject';
	$text_listdat   = 'Date';
	$text_listtim   = 'Time';
	$text_listurg   = 'Urgency';
	$text_liststa   = 'Status';
	$text_listnon   = 'You have no recent tickets for your account.';

	// SEARCH PAGE LISTINGS - TABLE HEADINGS SAME AS ABOVE

	$text_searchtitle = 'Search Results:';
	$text_searchmsg   = 'Click on the Ticket Number to read the ticket.';
	$text_searcherr   = 'Sorry but the search returned Zero results please try again.';

	// VIEW TICKET PAGE

	$test_view = '';
?>