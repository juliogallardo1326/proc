<?php
/***************************************************************************
File Name 	: help.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Provides the Help File for all Items.
Date Created	: Wednesday 19 January 2005 16:14:24
File Version	: 1.9
\\||************************************************************************/

############ INCLUDE THE CONFIG, LANGUAGE AND HEADER FILE ############

	include_once ('config.php');
	include_once ('class/functions.php');
	include_once ('header.php');
?>
	<a name="userpage"></a><p>

	<table width="99%" cellspacing="1" cellpadding="1" border="1" class="boxborder" align="center">
	  <tr bgcolor="#AABBDD">
		<td class="boxborder text">User Front Page</td>
	  </tr>
	  <tr>
		<td class="text">
		When you first arrive at the Ticket center you are given several choices:
		<ul>
		<li>Login</li>
		<li>Register (This may not be available in some setups)</li>
		<li>Resend Details</li>
		</ul>

		<b>Login</b><br />
		If you are already signed up at this Ticket Center then please just enter your
		username and password and hit the Log In button. You will be notified of an incorrect Login
		and asked to repeat this process again.<br /><br />

		<b>Register</b><br />
		On some systems it may be possible to Self Register. Complete all the required fields and
		make sure that you follow the instructions. Failure to do so will bring up an error page
		and you will have to repeat the process. If there is no register area then this system requires
		that the administrator create your account, please contact them to do this.<br /><br />

		<b>Resend Details</b><br />
		If you have forgotten your username and password, then simply enter the email address you
		used when you signed up, or when the administrator created your account. You will recieve an
		email shortly containing your account details.<br /><br />
		</td>
	  </tr>
	</table>
<?php
	include_once ('footer.php');
?>