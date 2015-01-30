<?php 
	include("includes/sessioncheck.php");
	
	include 'includes/header.php';
    
	$type = (isset($HTTP_GET_VARS['type'])?Trim($HTTP_GET_VARS['type']):"edit");
	$headerInclude= $type == "testMode" ? "testMode" : "profile";	
	include 'includes/topheader.php';
	$sessionid =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
	
    $username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
	$currentBank = (isset($HTTP_POST_VARS['currentBank'])?Trim($HTTP_POST_VARS['currentBank']):"");
	$bank_other = (isset($HTTP_POST_VARS['bank_other'])?Trim($HTTP_POST_VARS['bank_other']):"");
	$beneficiary_name = (isset($HTTP_POST_VARS['beneficiary_name'])?Trim($HTTP_POST_VARS['beneficiary_name']):"");
	$bank_account_name = (isset($HTTP_POST_VARS['bank_account_name'])?Trim($HTTP_POST_VARS['bank_account_name']):"");
	$bank_address = (isset($HTTP_POST_VARS['bank_address'])?Trim($HTTP_POST_VARS['bank_address']):"");
	$bank_country = (isset($HTTP_POST_VARS['bank_country'])?Trim($HTTP_POST_VARS['bank_country']):"");
	$bank_phone = (isset($HTTP_POST_VARS['bank_phone'])?Trim($HTTP_POST_VARS['bank_phone']):"");
	$bank_sort_code = (isset($HTTP_POST_VARS['bank_sort_code'])?Trim($HTTP_POST_VARS['bank_sort_code']):"");
	$bank_account_number = (isset($HTTP_POST_VARS['bank_account_number'])?Trim($HTTP_POST_VARS['bank_account_number']):"");
	$bank_swift_code = (isset($HTTP_POST_VARS['bank_swift_code'])?Trim($HTTP_POST_VARS['bank_swift_code']):"");
	$BIC_code = (isset($HTTP_POST_VARS['biccode'])?Trim($HTTP_POST_VARS['biccode']):"");
	$vatmumber = (isset($HTTP_POST_VARS['vatnum'])?Trim($HTTP_POST_VARS['vatnum']):"");
	$regnumber = (isset($HTTP_POST_VARS['regnum'])?Trim($HTTP_POST_VARS['regnum']):"");
	
	$sql_update_qry = "update cs_companydetails set company_bank = '$currentBank', other_company_bank = '$bank_other', bank_address = '$bank_address', bank_country = '$bank_country', bank_phone = '$bank_phone', bank_sort_code = '$bank_sort_code', bank_account_number = '$bank_account_number', bank_swift_code = '$bank_swift_code',beneficiary_name='$beneficiary_name',bank_account_name='$bank_account_name',BICcode='$BIC_code',VATnumber='$vatmumber',registrationNo='$regnumber',completed_merchant_application = 1 where userid=$sessionid";
	
	if(!($run_update_qry =mysql_query($sql_update_qry,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}
	$msgtodisplay="Merchant Details for '".$username."' has been modified";
?>
		



<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Company Profile</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>      
	<tr>
          <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"> 
            <table align="center" cellpadding="8" cellspacing="0" width="100%" height="100%" border="0">
              <!--DWLayoutTable-->
              <form name="frmcompany" action="companyBankEdit.php" method="POST">
                <tr> 
                  <td width="100%" height="50" align="center" valign="middle"> 
                    <?=$msgtodisplay ?>
                  </td>
                </tr>
                <tr> 
                  <td height="38" align="center" valign="top"> <input name="image" type="image" src="images/back.jpg" alt="View"> 
                  </td>
                  </tr>
              </form>
            </table></td>
 </tr>
<tr>
<td width="1%"><img src="images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="images/menubtmright.gif"></td>
</tr>
</table>
</td>
</tr>
</table>
	
<?php
include("includes/footer.php");
		
?>     