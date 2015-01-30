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
?>
<script language="javascript1.1">
function viewtemplate()
{
   advtWnd=window.open("reply_registrationmailview.php","advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
   advtWnd.focus();
}
</script>
<?php
$str_checked = "";
$mail_id = 0;
$qry_select = "Select mail_id,mail_sent from cs_registrationmail";
$rst_select = mysql_query($qry_select,$cnn_cs);
if (mysql_num_rows($rst_select)>0)
{
	$mail_id = mysql_result($rst_select,0,0);
	$mail_sent = mysql_result($rst_select,0,1);
	if ($mail_sent == 1)
	{
		$str_checked = "checked";
	}
}

?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%" align="center">
<form name="frm_reply" action="reply_registrationmailfb.php" method="post">
<tr>
   <td width="95%" valign="top" align="center" ><br>
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		     <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Billing Descriptor</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="left" cellpadding="0" cellspacing="0" width="500" height="10"><br>
				<tr>
                  <td align="right" valign="center" height="30" ><font face="verdana" size="1">Company 
                    Name &nbsp;</font></td>
					<td align="left" height="30"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px"></input></td>
				</tr>	
				<tr>
                  <td align="right" valign="center" height="30" ><font face="verdana" size="1">Customer 
                    Name &nbsp;</font></td>
					<td align="left" height="30"><input type="text" maxlength="100" name="customer" style="font-family:arial;font-size:10px;width:240px"></input></td>
				</tr>	
				<tr>
                  <td align="right" valign="center" height="30" ><font face="verdana" size="1">Card 
                    Type &nbsp;</font></td>
					<td align="left" height="30"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px"></input></td>
				</tr>	
			    <tr>
                  <td align="right" valign="center" height="30" ><font face="verdana" size="1">Amount 
                    &nbsp;</font></td>
					<td align="left" height="30"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px"></input></td>
				</tr>
				<tr>
                  <td align="right" valign="center" height="30" ><font face="verdana" size="1">Billing 
                    Descriptor &nbsp;</font></td>
					<td align="left" height="30"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px"></input></td>
				</tr>
				<tr>
                  <td align="right" valign="center" height="30" ><font face="verdana" size="1">Billing 
                    Descriptor &nbsp;</font></td>
					<td align="left" height="30"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px"></input></td>
				</tr>
				<tr>
			  		
                  <td height="10"  valign="middle" align="center" width="100%">	
                    <font face="verdana" size="1"><a href="javascript:viewtemplate();">View ecommerce template</a></font>
				  </td>
				</tr>
		       <tr>
		          <td height="30"  valign="middle" align="center">&nbsp;&nbsp;&nbsp;
				   <font face="verdana" size="1">Send Reply</font>&nbsp;
					<input name="chk_sent" type="checkbox" value="1" <?=$str_checked?>>			
				  </td>
		      </tr>
			  <tr>
		          <td height="30"  valign="middle" align="center">&nbsp;&nbsp;&nbsp;
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