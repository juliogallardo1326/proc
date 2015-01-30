<?php
/***************************************************************************
File Name 	: header.html
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Displays the HEAD elements for the HTML pages.
Date Created	: Wednesday 19 January 2005 16:16:05
File Version	: 1.9
\\||************************************************************************/
$rootdir = "../../";
//chdir("..");
$headerInclude = "service";
//$etel_debug_mode=0;
$disableInjectionChecks=true;
include '../../includes/dbconnection.php';
include("../../includes/header.php");
?>
<!-- PHP Support Tickets Manager - Triangle Solutions Ltd /-->
<!-- START OF HEADER FILE -->

	<link rel="stylesheet" type="text/css" href="../../php_scripts/spell_checker/spell_checker.css">
	<link rel="stylesheet" href="../style.css" type="text/css" />

	<script language="javascript" type="text/javascript">
	<!-- Hide script from old browsers

	function deletemember()
		{
		if (confirm('Before we continue are you sure you want to action this.'))
			{
			return true;
			}
			else
				{
				return false;
				}
		}

	function placeFocus()
		{
		if (document.forms.length > 0)
			{
			var field = document.forms[0];
			for (i = 0; i < field.length; i++)
				{
			if ((field.elements[i].type == "text") || (field.elements[i].type == "textarea") || (field.elements[i].type.toString().charAt(0) == "s"))
					{
					document.forms[0].elements[i].focus();
					break;
					}
				}
			}
		}

	// End hiding script from old browsers -->
	</script>


<!-- PHP Support Tickets Manager - Triangle Solutions Ltd /-->
<!-- END OF HEADER FILE -->