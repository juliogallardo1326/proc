<?php
/***************************************************************************
File Name 	: demoredirect.php
Domain		: http://www.PHPSupportTickets.com/
\\--------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Redirects the User to the Demo Page - setting the Cookie.
Date Created	: Wednesday 19 January 2005 16:13:40
File Version	: 1.9
\\||************************************************************************/

	include('config.php');

	IF ($demomode == 'ON')
		{
		SetCookie('demomode', 'ON', '0', '/', '');

		IF (isset($_COOKIE['demomode']))
			{
			header('Location:index.php');
			}
		ELSE
			{
			echo '<meta http-equiv="refresh" content="0;URL=demoredirect.php" />';
			}

		Exit();
		}
	ELSE
		{
		header('Location:index.php');
		Exit();
		}
?>