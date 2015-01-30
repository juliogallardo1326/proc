<?php
$headerInclude="profile";
$_REQUEST['step']=2;
include('application.php');
die();

include ("includes/sessioncheck.php");
$headerInclude="profile";
include("includes/header.php");
include("includes/topheader.php"); 
include("includes/message.php");
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
if($resellerLogin!="")
{
	if($HTTP_GET_VARS['login']){

		$currentBank = (isset($HTTP_GET_VARS['currentBank'])?quote_smart($HTTP_GET_VARS['currentBank']):"");
		$bank_other = (isset($HTTP_GET_VARS['bank_other'])?quote_smart($HTTP_GET_VARS['bank_other']):"");
		$beneficiary_name = (isset($HTTP_GET_VARS['beneficiary_name'])?quote_smart($HTTP_GET_VARS['beneficiary_name']):"");
		$bank_account_name = (isset($HTTP_GET_VARS['bank_account_name'])?quote_smart($HTTP_GET_VARS['bank_account_name']):"");
		$bank_address = (isset($HTTP_GET_VARS['bank_address'])?quote_smart($HTTP_GET_VARS['bank_address']):"");
		$bank_country = (isset($HTTP_GET_VARS['bank_country'])?quote_smart($HTTP_GET_VARS['bank_country']):"");
		$bank_phone = (isset($HTTP_GET_VARS['bank_phone'])?quote_smart($HTTP_GET_VARS['bank_phone']):"");
		$bank_sort_code = (isset($HTTP_GET_VARS['bank_sort_code'])?quote_smart($HTTP_GET_VARS['bank_sort_code']):"");
		$bank_account_number = (isset($HTTP_GET_VARS['bank_account_number'])?quote_smart($HTTP_GET_VARS['bank_account_number']):"");
		$bank_routing_no = (isset($HTTP_GET_VARS['bank_routing_no'])?quote_smart($HTTP_GET_VARS['bank_routing_no']):"");
		$bank_swift_code = (isset($HTTP_GET_VARS['bank_swift_code'])?quote_smart($HTTP_GET_VARS['bank_swift_code']):"");

			$show_sql =mysql_query("update cs_resellerdetails set bank_routing_no='$bank_routing_no',reseller_email='$email',reseller_bankname = '$currentBank', reseller_otherbank = '$bank_other', bank_address = '$bank_address', bank_country = '$bank_country', bank_telephone = '$bank_phone', bank_sortcode = '$bank_sort_code', bank_accountno = '$bank_account_number', bank_swiftcode = '$bank_swift_code',bank_benificiaryname='$beneficiary_name', bank_accountname='$bank_account_name' where reseller_id=$resellerLogin");
			$msgtodisplay="Profile has been changed for reseller.";			



	}
	
		
	$qry_select_user = "select reseller_username,reseller_companyname,reseller_contactname,reseller_email,reseller_bankname,reseller_otherbank,bank_benificiaryname,bank_accountname,bank_address,bank_country,bank_telephone,bank_sortcode,bank_accountno,bank_swiftcode,bank_routing_no from cs_resellerdetails where reseller_id=$resellerLogin";
	if(!($show_sql = mysql_query($qry_select_user,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}	
	if($show_val = mysql_fetch_array($show_sql)) 
	{	
		$usernameO = $show_val[0];
		$companyname = $show_val[1];
		$conatctname = $show_val[2];
		$reselleremail = $show_val[3];
	}	
?>
<script language="javascript">
function validation(){ 
	return true;
}
</script>
<script language="javascript" src="../scripts/general.js"></script>

 <table border="0" cellpadding="0" width="100%" cellspacing="0" >
  <tr><td width="83%" valign="top" align="center"  >
<br>	<form action="bankProfile.php" method="GET" onsubmit="return validation()" name="Frmlogin" >
	<table width="50%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Edit&nbsp;Payment Details </span></td>
	        <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
		<?php
			if(!isset($invalidlogin))
			{
				$invalidlogin = "";
			}	
		?>
	  <table height="100%" width="100%" cellspacing="0" cellpadding="0"><tr><td  width="100%" valign="center" align="center">
		  <table width="400" border="0" cellpadding="0"  height="100">	  <?=$invalidlogin?>
			          <tr align="center" valign="middle"> 
                        <td height="30" colspan="2"><font face="verdana" size="1" color="#FF0000"><strong>"Banking 
                          information used to wire reseller commissions."</strong></font></td>
</tr>
			          <tr align="center" valign="middle"> 
                        <td height="30" colspan="2"><font face="verdana" size="1" ><strong><?=$msgtodisplay?>
                          </strong></font></td>
</tr>

			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">With 
				which bank do you hold a company account? &nbsp;</font></td>
				<td align="left" height="30" width="250">
				<select name="currentBank" style="font-family:arial;font-size:10px;width:240px" >
					<?=func_get_bank_select($show_val[4])?>
					<option value="other">other</option>
				</select> 
				</td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">If 'Other', please specify &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text" name="bank_other" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[5]?>'></td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Beneficiary Name &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text"  name="beneficiary_name" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[6]?>'></td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Name On Bank Account &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text"  name="bank_account_name" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[7]?>'></td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Bank Address &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text"  name="bank_address" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[8]?>'></td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Bank Country &nbsp;</font></td>
				<td align="left" height="30" width="250">
				<select name="bank_country"  style="font-family:arial;font-size:10px;width:150px">
                    <?=func_get_country_select($show_val[9],1)?>
				</select>
				</td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Bank Telephone Number &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text" name="bank_phone" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[10]?>'></td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Sort Code/Branch Number &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text" name="bank_sort_code" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[11]?>'></td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Bank Account Number &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text" name="bank_account_number" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[12]?>'></td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Bank Routing Number &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text" name="bank_routing_no" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[14]?>'></td>
			  </tr>
			  <tr>
				<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Bank Swift Code &nbsp;</font></td>
				<td align="left" height="30" width="250"><input type="text" name="bank_swift_code" style="font-family:arial;font-size:10px;width:160px" value='<?=$show_val[13]?>'></td>
			  </tr>

			  <tr><td align="center" valign="center" height="30" colspan="2">
			   <input type="image" name="add" id="useaccount" src="../images/submit.jpg"></td></tr>
			   <input type="hidden" name="login" value="login">
		  </table>
	  </td></tr>
	  </table>
	  <br>
	  </td>
      </tr>
	<tr>
	<td width="1%"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
    </table></form>
    </td>
     </tr>
</table>
<?php
include("includes/footer.php");
}
?>	
