<?php
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//
 
include("includes/sessioncheck.php");

$headerInclude = "mail";
include("includes/header.php");

//$headerInclude="companies";

include("includes/message.php");

$submit = isset($HTTP_POST_VARS['submitvalue'])?quote_smart($HTTP_POST_VARS['submitvalue']):"";

$sent_login = isset($HTTP_POST_VARS['chk_sent_login'])?quote_smart($HTTP_POST_VARS['chk_sent_login']):"";

$sent_eccom = isset($HTTP_POST_VARS['chk_sent_eccom'])?quote_smart($HTTP_POST_VARS['chk_sent_eccom']):"0";
if($submit=="submit"){
	if($sent_eccom !="") {
		$qrt_update_mail = "Update cs_registrationmail set mail_sent=$sent_eccom where mail_id=2";
		if(!($run_update_sql =mysql_query($qrt_update_mail,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
	if($sent_login !="") {
		$qrt_update_email = "Update cs_registrationmail set mail_sent=$sent_login where mail_id=1";
		if(!($run_update_sql =mysql_query($qrt_update_email,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
}



$str_checked = "";
$str_checked_eccom="";
$qry_select = "Select mail_id,mail_sent from cs_registrationmail";
$rst_select = mysql_query($qry_select,$cnn_cs);
if (mysql_num_rows($rst_select)>0)
{
	$mail_id = mysql_result($rst_select,0,0);
	$mail_sent_login = mysql_result($rst_select,0,1);
	$mail_sent_eccom = mysql_result($rst_select,1,1);
	if ($mail_sent_login == 1)
	{
		$str_checked = "checked";
	}
	if ($mail_sent_eccom == 1)
	{
		$str_checked_eccom = "checked";
	}
}

?>
<script>
function viewtemplate(type)
{
   advtWnd=window.open("reply_registrationmailview.php?type=ecom","advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
   advtWnd.focus();
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%" align="center">
<form name="frm_reply" action="unsubscribe_mail.php" method="post">
<input type="hidden" name="submitvalue" value="submit">
<tr>
   <td width="95%" valign="top" align="center" ><br>
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		     <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Unsubscribe Mails</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">
			     <tr>
				 	<td height="10"  valign="middle" align="center" width="50%"></td> 
			    </tr>  
<!--				<tr>
			  <td height="30"  valign="middle" align="center">
				  <font face="verdana" size="1"><input name="chk_sent_login" type="checkbox" value="1" <?=$str_checked?>> Send Login Letter </font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  </td>
				  </tr> -->
			<tr>
			      <td height="30"  valign="middle" align="center"> <font face="verdana" size="1"> 
                    <a href="javascript:viewtemplate('ecom');">View Ecommerce Letter</a></font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
				  </tr>				  <tr>
			  <td height="30"  valign="middle" align="center">&nbsp;&nbsp;&nbsp; 
				  <font face="verdana" size="1"><input name="chk_sent_eccom" type="checkbox" value="1" <?=$str_checked_eccom?>> Send Ecommerce Letter </font> &nbsp; 
				  </td>
				  </tr>
			  <tr>
		          <td height="50"  valign="middle" align="center">&nbsp;&nbsp;&nbsp;
				  <input type="image" name="Submit" id="Submit" SRC="<?=$tmpl_dir?>/images/submit.jpg">
				  </td>
		      </tr>		 		  
		  	</table>      
		</td>
		</tr>
		<tr>
		<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
		</tr>
		</table>
	</td>
</tr>
<input type="hidden" name="hid_id" value="<?=$mail_id?>">
</form>
</table>
<?php 
include("includes/footer.php");
?>
