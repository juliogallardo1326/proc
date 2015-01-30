<?php
	$headerInclude="blank";
	$noheader=true;
	include("quickstats.php");
	exit();
	
	
	include 'includes/sessioncheckuser.php';
	include("includes/header.php");
	include("includes/topheader.php"); 
	$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
	$select_login_count ="select login_count from cs_companydetails where userId = $sessionlogin";
	if(!($run_select_qry = mysql_query($select_login_count))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$companyid = $sessionlogin;
	$qrt_update_login ="update cs_companydetails set login_count=login_count+1 where userId = $sessionlogin";
	if(!($run_update_qry = mysql_query($qrt_update_login))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	 if(mysql_result($run_select_qry,0,0)==0) {
?>
	<script language="JavaScript"> 
//		window.open("reply_registrationmailview.php?company="+<?=$companyid?>,"LoginLetter","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
	</script> 	
<?	 }
	
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
    <tr>
   		 <td width="100%">&nbsp;</td>
  	</tr>
	</table>
<?php
	include("includes/footer.php");
?>