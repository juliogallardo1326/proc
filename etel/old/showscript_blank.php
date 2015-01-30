<?php
	include("includes/sessioncheck.php");
	include('includes/dbconnection.php');
	include("includes/header.php");
	$mode = (isset($HTTP_GET_VARS['type'])?Trim($HTTP_GET_VARS['type']):"profile");
	$headerInclude = "script";	
	include("includes/topheader.php"); ?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
    <tr>
   		 <td width="100%">&nbsp;</td>
  	</tr>
	</table>
<?php
	include("includes/footer.php");
?>