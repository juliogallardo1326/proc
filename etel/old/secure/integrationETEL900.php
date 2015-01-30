<?php 
chdir("..");
session_start();
include 'includes/dbconnection.php';
require_once('includes/function.php');
include 'includes/function1.php';


$reference_id = $_SESSION['mt_reference_id'];
$trans_type = $_SESSION['mt_transaction_type'];
$i_return_url = $_SESSION['mt_return_url'];
$mt_subAccount = $_SESSION['mt_subAccount'];
$mt_prod_desc = $_SESSION['mt_prod_desc'];
$mt_prod_price = $_SESSION['mt_prod_price'];
$companyid = $_SESSION['companyid'];

$mt_etel900_subAccount = $_SESSION['mt_etel900_subAccount'];

$blockrebill = (isset($HTTP_POST_VARS['hid_blockrebill'])?trim($HTTP_POST_VARS['hid_blockrebill']):"");
//Modifications to get user data automatically
$str_firstname = (isset($HTTP_POST_VARS['mt_first_name'])?trim($HTTP_POST_VARS['mt_first_name']):"");
$str_lastname = (isset($HTTP_POST_VARS['mt_last_name'])?trim($HTTP_POST_VARS['mt_last_name']):"");
$str_address = (isset($HTTP_POST_VARS['mt_address'])?trim($HTTP_POST_VARS['mt_address']):"");
$str_country = (isset($HTTP_POST_VARS['mt_country'])?trim($HTTP_POST_VARS['mt_country']):"United States");
$str_city = (isset($HTTP_POST_VARS['mt_city'])?trim($HTTP_POST_VARS['mt_city']):"");
$str_state = (isset($HTTP_POST_VARS['mt_state'])?trim($HTTP_POST_VARS['mt_state']):"select");
$i_zipcode = (isset($HTTP_POST_VARS['mt_zipcode'])?trim($HTTP_POST_VARS['mt_zipcode']):"");
$i_phonenumber = (isset($HTTP_POST_VARS['mt_phone_number'])?trim($HTTP_POST_VARS['mt_phone_number']):"");
$str_emailaddress = (isset($HTTP_POST_VARS['mt_email_address'])?trim($HTTP_POST_VARS['mt_email_address']):"");


	$sql = "SELECT c.*,b.transaction_type,b.cd_password_mgmt FROM `cs_rebillingdetails` as c, `cs_companydetails` as b WHERE b.userId = '$companyid' AND `rd_subName` = '$mt_etel900_subAccount' AND `company_user_id` = -1";
	if(!($result = mysql_query($sql,$cnn_cs)))
	{
	
		print(mysql_errno().": ".mysql_error()."<BR>");
		print ($qry_update."<br>");
		print("Failed to access company Product Database");
		exit();
	}
	else
	{
		if(mysql_num_rows($result) <= 0) 
		{
			$strMessage = "INV";
			$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>SubAccounts must be valid Etelegate900 (ETEL900) SubAccounts to work with ETEL900. Please contact an administrator.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
			die($msgtodisplay);
	
		}
		
		$subAcc = mysql_fetch_assoc($result);

		// Next Payment
		
		$schedule = "";
		$chargeAmount=$subAcc['recur_charge'];
		if($subAcc['rd_initial_amount'] > 0) $chargeAmount=$subAcc['rd_initial_amount'];
		$_SESSION['amount'] = $chargeAmount;
		$_SESSION['is_price_point'] = true;
				$chargeAmount = number_format($chargeAmount, 2, '.', '');
		
		if($subAcc['rd_initial_amount'] > 0) $schedule ="Initial Payment of '".number_format($subAcc['rd_initial_amount'], 2, '.', '')."' for a Trial Period of ".$subAcc['rd_trial_days']." day(s),";
		$schedule .= "<br>Recurring Payment of '".number_format($subAcc['recur_charge'], 2, '.', '')."' once every ".$subAcc['recur_day']." day(s),";
		if($subAcc['recur_charge'] <= 0) $schedule = "One Time Payment of '".number_format($subAcc['rd_initial_amount'], 2, '.', '')."'<br>";
		if (($subAcc['td_is_a_rebill'] == 1) || ($subAcc['rd_trial_days'] == 0)) 
		{
			$nextRecurDate=time()+60*60*24*$subAcc['recur_day'];
			$nextDateInfo = date("F j, Y",$nextRecurDate)." for ".number_format($subAcc['recur_charge'], 2, '.', '');
			
		}
		else
		{
			$nextRecurDate=time()+60*60*24*$subAcc['recur_day'];
			$nextDateInfo = date("F j, Y",$nextRecurDate)." for ".number_format($subAcc['rd_initial_amount'], 2, '.', '');
			
		}
		$_SESSION['rd_subaccount']=$subAcc['rd_subaccount'];	
		$_SESSION['td_recur_next_date']=date("Y-m-d",$nextRecurDate);
		$_SESSION['nextDateInfo']=$nextDateInfo;
	}


//modifications end here
//$trans_type ="tele";
//logo
$qry_select ="select gateway_id,we_billingdescriptor,companyname  from cs_companydetails where userId= $companyid";
$select =mysql_query($qry_select);
$show_val = mysql_fetch_array($select);
$gateway_id=$show_val[0];
$bill_des=$show_val[1];
$companyname=$show_val[2];


if($gateway_id==-1){$logopath="images/logo2os_L.gif"; }
else {$qry_select_logo ="select logo_filename  from cs_logo where logo_company_id = $gateway_id";
$select_name =mysql_query($qry_select_logo);
$show_name = mysql_fetch_array($select_name);
$logopath=$show_name[0];
$logopath="GatewayLogo/".$logopath; 	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>::Payment Gateway::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.normaltext {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #009999;
	text-decoration: none;
}
.fieldname {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #009999;
	text-decoration: underline;
}
.terms {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #009999;
	font-style: italic;
}
.style1 {color: #009999}
.fieldname1 {font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #009999;
	text-decoration: underline;
}
.style12 {font-size: 12px}
.style5 {color: #009999; font-weight: bold; }
.style13 {font-size: 10px}
-->
</style>
<script language="javascript" src="https://www.etelegate.com/scripts/general.js"></script>
<script language="javascript" src="https://www.etelegate.com/scripts/creditcard.js"></script>
<script language="javascript" src="https://www.etelegate.com/scripts/formvalid.js"></script>
<script language="javascript" >

function submitOrder(thisform)
{
	var validated = submitform(thisform);

	if(validated)
	{
		thisform.style.display='none';
		document.getElementById('processMessage').style.display='block';
	}
	return validated;

}

</script>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="650" height="407" align="center">
<tr>
<td width="100%" valign="top" align="left"> 
<form action="processETEL900.php" method="post" onsubmit="return submitOrder(this)" name="chequeFrm" >
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr> 
            <td width="60%"><img alt='' border='0' src='https://www.etelegate.com/<?=$logopath?>'></td>
    <td align="right"><font size="2" face="Verdana">Etelegate.com is a designated Payment Service Provider for
        <?=$cs_URL?>
    </font></td>
  </tr>
  </table>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="10" align="left" valign="top"><span class="normaltext">PRODUCT INFORMATION</span></td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#009999"><img src="images/spacer.gif" alt="sp" width="20" height="4"></td>
      <td align="right" bgcolor="#009999"><img src="images/spacer.gif" alt="sp" width="20" height="4"></td>
    </tr>
    <tr>
      <td width="85%" align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
          <tr>
            <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> Website: </font></span></div></td>
            <td width="50%">&nbsp;
                <?=$_SESSION['cs_URL']?>
            </td>
          </tr>
          <tr>
            <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> Product Name: </font></span></div></td>
            <td width="50%">&nbsp;
                <?=$subAcc['rd_description']?>
            </td>
          </tr>
          <tr>
            <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> Product Description: </font></span></div></td>
            <td width="50%">&nbsp;
                <?=$mt_prod_desc?>
            </td>
          </tr>
                  <?php if ($_SESSION['is_price_point'] == true) { ?>
				<tr>
                  <?php if ($subAcc['rd_initial_amount']) {?>
                  <td width="50%" align="right" valign="top"><div align="right"><span class="style5"><font size="2" face="Verdana"> Transaction Detail:</font></span>
                          
						  <table width="200" border="2">
                            <tr bgcolor="#B9FDCF">
                              <td width="100"><span class="style12">Amount:</span></td>
                              <td><span class="style12">$<?=$subAcc['rd_initial_amount']?>
                              </span></td>
                            </tr>
							<?php if ($subAcc['rd_trial_days']>0){ ?>
                            <tr>
                              <td><span class="style12">Trial Period</span></td>
                              <td><span class="style12">
                                <?=$subAcc['rd_trial_days']?>
                          Days</span></td>
							<?php } ?>
                            </tr>
                          </table>
                          <span class="style1"><font size="2" face="Verdana"> </font></span></div></td>
                  <?php }?>
                  <?php if ($subAcc['recur_charge']) {?>
				  <td width="50%"><font size="1" face="Verdana" color="#000000"><span class="style5"><font size="2" face="Verdana">Recurring Billing: </font></span></font>
                      <table width="200" border="2">
                        <tr bgcolor="#B9FDCF">
                          <td width="100"><span class="style12">Amount:</span></td>
                          <td><span class="style12">$<?=$subAcc['recur_charge']?>
                          </span></td>
                        </tr>
                        <tr>
                          <td><span class="style12">Billed every </span></td>
                          <td><span class="style12">
                            <?=$subAcc['recur_day']?>
                        Days</span></td>
                        </tr>
                        <tr>
                          <td><span class="style12">Next bill on </span></td>
                          <td><span class="style12">
                            <?=$nextDate?>
                          </span> </td>
                        </tr>
                    </table></td>
                  <?php }?>
                </tr>
                <?php } ?>

      </table></td>
      <td width="15%" align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="center">Total charge:
          $<?=number_format($chargeAmount,2,".",",")?>
      </td>
    </tr>
  </table>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60%">&nbsp;</td>
    <td align="right">&nbsp;</td>
	<tr><td class="normaltext" valign="middle" height="10">BILLING INFORMATION as it appears on your card</td></tr>
  <tr> 
    <td bgcolor="#009999"><img src="images/spacer.gif" alt="sp" width="20" height="4"></td>
    <td align="right" bgcolor="#009999"><img src="images/spacer.gif" alt="sp" width="20" height="4"></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td width="60%" align="left" valign="top"> <table width="100%"  border="0" cellspacing="4" cellpadding="0">
        <tr> 
          <td width="25%" class="fieldname">First Name / Last Name</td>
          <td> <input type="text" name="firstname" size="19" maxlength="75" value="<?=$str_firstname?>" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> <strong><span class="terms"> 
            /</span></strong> <input type="text" name="lastname" size="19" maxlength="75" value="<?=$str_lastname?>" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> 
          </td>
        </tr>
				<?php if ($_SESSION['cs_enable_passmgmt']==1) { ?>      
		 <tr> 
          <td width="25%" class="fieldname">Choose UserName </td>
          <td> <input name="td_username" type="text" id="td_username" value="<?=$td_username?>" size="30" maxlength="30"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> <strong><span class="terms"> 
            </span></strong></td>
        </tr>      
		 <tr> 
          <td width="25%" class="fieldname">Choose Password </td>
          <td> <input name="td_password" type="password" id="td_password" value="<?=$td_password?>" size="30" maxlength="30" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> 
          <strong><span class="terms"> 
            </span></strong></td>
        </tr>
		<?php } ?> 
		<tr> 
			  <td width="25%" class="fieldname">Phone Number</td>
			  <td> <input type="text" name="phonenumber" size="25" maxlength="30" value="<?=$i_phonenumber?>" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="phone"></td>
			</tr>
		
        <?php if($trans_type=="tele"){ ?>
							<tr> 
								<td   width="25%" class="fieldname">An 
								  email confirmation of<br>
            this order will be sent to</td>
								<td  width="75%" class="fieldname">
								  <input type="text" name="email" size="40" maxlength="100" value="<?=$str_emailaddress?>" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="email">
							  </td>
			    </tr>
					<?php }else{?>
							 <tr> 
								
          <td width="25%" class="fieldname" >Customer email address</td>
								<td >
								  <input type="text" name="email" id="email" size="40" maxlength="100" value="<?=$str_emailaddress?>" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="email">
							   </td>
			    </tr>
							  <tr>
                                <td class="fieldname">Confirm email address</td>
                                <td><input type="text" name="emailconfirm" id="emailconfirm" size="40" value="<?=$str_emailaddress?>" maxlength="100" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="confirm" title="email">
                                </td>
			    </tr>
					<?php } ?>
		<tr> 
			  <td class="fieldname">Billing Descriptor</td>
			  <td><font size="1" face="Verdana"><?=$bill_des?></font></td>
			</tr>
      </table></td>
    <td width="40%" align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
        <tr> 
                  <td class="fieldname">&nbsp;</td>
        </tr>
        <tr> 
                  <td class="terms">&nbsp;</td>
        </tr>
        <tr> 
                  <td class="terms">&nbsp;</td>
        </tr>
        <tr> 
                  <td class="terms">&nbsp;</td>
        </tr>
        <tr> 
                  <td class="terms">&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
        <tr> 
                  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="60%">&nbsp;</td>
    <td width="40%" align="right">&nbsp;</td>
  </tr>
  <tr> 
    <td bgcolor="#009999"><img src="images/spacer.gif" alt="sp" width="20" height="4"></td>
    <td align="right" bgcolor="#009999"><img src="images/spacer.gif" alt="sp" width="20" height="4"></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr> 	<td height="25" align="center"></td> </tr>
  <tr>
	    <tr>   <td height="25" align="center"><input type="image" name="add" src="https://www.etelegate.com/images/submit.jpg"></td>
        </tr> 
 <tr>
            <td align="center"><font size="2" face="Verdana"><strong>Your purchase will be billed and appear on your billing statement as<font size="2" face="Verdana">: &quot;<?=$bill_des?>&quot;</font></strong><font size="2" face="Verdana"><strong><br>
              </strong></font></font><font face="Verdana"><font face="Verdana"><font face="Verdana"><span class="style13">Customer Support is our main focus which is available 24/7/365<br>
You can cancel Your subscription at ANY time!<br>
Etelegate Online Customer Service is available online at <a href="www.etelegate.net">www.etelegate.net</a> USA and Canada Toll free customer support tel. line (24/7/365): 1 (866)-633-7467 (ext. 1). <br>
OR Direct Dial: 1 (212)-631-4223<br>
Email support: <a href="etelegate.com">customerservice@Etelegate.com</a> </span></font></font></font></td>
</tr>  	
</table>
</form>
</td>
  </tr>
</table>