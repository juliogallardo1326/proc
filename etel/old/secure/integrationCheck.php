<?php 
chdir("..");
session_start();
require_once("includes/dbconnection.php");
require_once('includes/function.php');
include 'includes/function1.php';

// Disable
$strMessage = "INV";
$msgtodisplay="<form name='Frmname' action='$mt_return_url' method='post'><input type='hidden' name='mt_transaction_result' value='$strMessage'><table width='350' height='100' align='center' valign='middle' style='border:1px solid black' cellpading='0' cellspacing='0'><tr><td align='center' valign='middle'>Check Processing is not available at this time. Please use Credit Card processing.</td></tr><tr><td align='center'><input type='image' border='0' src='https://www.etelegate.com/images/back.jpg'></td></tr></table></form>";				
die($msgtodisplay);
// END Disable


$reference_id = $_SESSION['mt_reference_id'];
$trans_type = $_SESSION['mt_transaction_type'];
$i_return_url = $_SESSION['mt_return_url'];
$mt_subAccount = $_SESSION['mt_subAccount'];
$mt_prod_desc = $_SESSION['mt_prod_desc'];
$mt_prod_price = $_SESSION['mt_prod_price'];
$companyid = $_SESSION['companyid'];

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

$chargeAmount = $_SESSION['amount'];
if (!$mt_subAccount) $mt_subAccount = -1;
if($chargeAmount <=0) die("Invalid Charge Amount");
if ($_SESSION['is_price_point'] == true)
{
	$sql = "SELECT c.*,b.transaction_type FROM `cs_rebillingdetails` as c, `cs_companydetails` as b WHERE b.userId = `company_user_id` AND `rd_subName` = '$mt_subAccount' AND `company_user_id` = " .$companyid ;
	if(!($result = mysql_query($sql,$cnn_cs)))
	{
		print(mysql_errno().": ".mysql_error()."<BR>");
		print ($qry_update."<br>");
		print("Failed to access company Product Database");
		exit();
	}
	else
	{
		$subAcc = mysql_fetch_assoc($result);

		// Next Payment
	
		if (($subAcc['td_is_a_rebill'] == 1) || ($subAcc['rd_trial_days'] == 0)) 
		{
			$nextDate = date("F j, Y",strtotime( $show_select_val['transactionDate'])+60*60*24*$subAcc['recur_day'])." for ".number_format($subAcc['recur_charge'], 2, '.', '');
		}
		else
		{
			$nextDate = date("F j, Y",strtotime( $show_select_val['transactionDate'])+60*60*24*$subAcc['rd_trial_days'])." for ".number_format($subAcc['rd_initial_amount'], 2, '.', '');
		}
		
	}
}


//modifications end here
//$trans_type ="tele";
//logo
$qry_select ="select gateway_id,ch_billingdescriptor ,companyname  from cs_companydetails where userId= $companyid";
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
<script language="javascript" src="https://www.etelegate.com/scripts/formvalid.js"></script>
<script language="javascript">
function updateCountry(obj)
{
	if(obj.value == "United States") 
	{
		document.getElementById('zip').src='zipcode';
		document.getElementById('state').disabled = false;
	}
	else
	{
		document.getElementById('zip').src='alphanumeric';
		document.getElementById('state').disabled = true;
	}
}
</script>
		  
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="650" height="407" align="center">
<tr>
<td width="100%" valign="top" align="left"> 
<form action="processCheck.php" method="post" name="chequeFrm" onsubmit="return submitform(this)">

  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr> 
            <td width="60%"><img alt='' border='0' src='<?=$logopath?>'></td>
    <td align="right"><img src="https://www.etelegate.com/images/visa.jpg" alt="v" width="30" height="18">&nbsp;&nbsp;<img border="0" src="https://www.etelegate.com/images/mastercard.jpg">&nbsp;<br><font size="2" face="Verdana">Etelegate.com is a designated Payment Service Provider for
        <?=$cs_URL?>
    </font>&nbsp;&nbsp;&nbsp; 
    </td>
  </tr>
</table>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="10" align="left" valign="top"><span class="normaltext">PRODUCT INFORMATION</span></td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
            <td align="right" bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
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
                            <tr>
                              <td><span class="style12">Trial Period </span></td>
                              <td><span class="style12">
                                <?=$subAcc['rd_trial_days']?>
                          Days</span></td>
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
      </table></td>
    </tr>
	  <tr><td width="100%" height="30" valign="middle" ><div align="center"><font size="2" face="Verdana"><font size="2" face="Verdana">Your purchase will be billed and appear on your billing statement as: <strong>&quot;<?=$bill_des?>&quot;</strong></font></font></div></td>
	  </tr>
    <tr>
      <td class="normaltext" valign="middle" height="10">BILLING INFORMATION as it appears on your card</td>
    </tr>
    <tr>
      <td bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
      <td align="right" bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
    </tr>
  </table>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr> 
    <td align="left" valign="top"> <table width="100%"  border="0" cellspacing="4" cellpadding="0">
        <tr> 
          <td width="25%" valign="top" class="fieldname">First Name / Last Name</td>
          <td> <input type="text" name="firstname" size="19" maxlength="75" value="<?=$str_firstname?>" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req" > <strong><span class="terms"> 
            <br></span></strong> <input type="text" name="lastname" size="19" maxlength="75" value="<?=$str_lastname?>"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> 
          </td>
        </tr> 
		<?php if ($_SESSION['cs_enable_passmgmt']==1) { ?>      
		 <tr> 
          <td width="25%" valign="top" class="fieldname">Choose UserName </td>
          <td> <input name="td_username" type="text" id="td_username" value="<?=$td_username?>" size="30" maxlength="30"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> <strong><span class="terms"> 
            </span></strong></td>
        </tr>      
		 <tr> 
          <td width="25%" valign="top" class="fieldname">Choose Password </td>
          <td> <input name="td_password" type="password" id="td_password" value="<?=$td_password?>" size="30" maxlength="30" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> 
          <strong><span class="terms"> 
            </span></strong></td>
        </tr>
		<?php } ?>   
		
        <tr> 
          <td width="25%" valign="top" class="fieldname">Address</td>
          <td> <input type="text" name="address" size="35" maxlength="100" value="<?=$str_address?>"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> </td>
        </tr>
        <tr> 
          <td width="25%" valign="top" class="fieldname">Country</td>
          <td> <select name="country" id="country" style="font-size:11px;width:180px;font-height:10px;font-face:verdana;" onChange="updatevalid(this);updateCountry(this);" title="reqmenu" >
                <option value="select" selected>- - -Select- - -</option>
                <script language="JavaScript">showCountries();
							document.getElementById('country').value="<?=$str_country?>";
							</script>
              </select> </td>
        </tr>
        <tr> 
          <td width="25%" valign="top" class="fieldname">City</td>
          <td> <input type="text" name="city" size="35" maxlength="50" value="<?=$str_city?>"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"> </td>
        </tr>
        <tr> 
          <td width="25%" valign="top" class="fieldname">State</td>
          <td> <select name="state" id="state" style="font-size:11px;width:140px;font-height:10px;font-face:verdana;" onChange="updatevalid(this)" title="reqmenu" >
				<?=func_get_state_select($str_state) ?>
			  </select> 
		 </td>
        </tr>
		<tr> 
			  <td width="25%" valign="top" class="fieldname">Other State</td>
			    <td><input name="otherstate" type="text" size="25"  onFocus="updatevalid(this);" >
                 </td>
				 
			</tr>
        <tr> 
          <td width="25%" valign="top" class="fieldname">Zip</td>
          <td> <input name="zip" id="zip" type="text" size="15" maxlength="15" value="<?=$i_zipcode?>"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="zipcode"> </td>
        </tr>
		
		<tr> 
			  <td width="25%" valign="top" class="fieldname">Phone Number</td>
			  <td> <input type="text" name="phonenumber" size="25" maxlength="30" value="<?=$i_phonenumber?>"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req"></td>
			</tr>
		
        <?php if($trans_type=="tele"){ ?>
							<tr> 
								<td   width="25%" valign="top" class="fieldname">An 
								  email confirmation of<br>
            this order will be sent to</td>
								<td  width="75%" class="fieldname">
								  <input type="text" name="email" size="35" maxlength="100" value="<?=$str_emailaddress?>"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="email">
							  </td>
			    </tr>
					<?php }else{?>
							 <tr>
                               <td valign="top" class="fieldname">Your email address</td>
                               <td><input type="text" id="email" name="email" size="35" maxlength="100" value="<?=$str_emailaddress?>" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="email">
                               </td>
			    </tr>
							 <tr>
                               <td valign="top" class="fieldname">Confirm email address</td>
                               <td><input type="text" name="emailconfirm" id="emailconfirm" size="35" maxlength="100" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="confirm" title="email"></td>
			    </tr>
					<?php } ?>
		<tr> 
			  <td valign="top" class="fieldname">Billing Descriptor</td>
			  <td><font size="1" face="Verdana"><?=$bill_des?></font></td>
			</tr>
      </table></td>
    <td align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
      <tr>
        <td width="40%" valign="top" class="fieldname">Check #</td>
        <td><input type="text" name="chequenumber" size="30" maxlength="50"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="numeric">
        </td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">Check Type</td>
        <td><input name="chequetype" type="radio" value="personal" checked>
            <font size="1" face="Verdana" color="#000000">Personal</font>&nbsp;&nbsp;
            <input type="radio" name="chequetype" value="business" >
            <font size="1" face="Verdana" color="#000000">Business</font></td>
      </tr>
      <tr>
        <td valign="top" class="fieldname1">Amount of Money</td>
        <td valign="middle" class="normaltext"><font size="2" face="Verdana" color="#000000">
          <?php			
							print "<b>".$chargeAmount."</b>";
			 ?>
          </font></td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">Account Type</td>
        <td><input name="accounttype" type="radio" value="checking" checked>
            <font size="1" face="Verdana" color="#000000">Checking</font>&nbsp;&nbsp;
            <input type="radio" name="accounttype" value="savings" >
            <font size="1" face="Verdana" color="#000000">Savings</font> </td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">Bank Name</td>
        <td><input type="text" name="bankname" size="30" maxlength="100"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
        </td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">Bank Routing Code</td>
        <td><input type="text" name="bankroutingcode" size="30" maxlength="100"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
        </td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">Bank Account #</td>
        <td><input type="text" name="bankaccountno" size="30" maxlength="100"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
        </td>
      </tr>
      <? if(0){ ?>
      <tr>
        <td width="40%" valign="top" class="fieldname">Billing Date</td>
        <td><select name="opt_bill_month" class="lineborderselect" style="font-size:10px"  onChange="updatevalid(this)" title="reqmenu">
            <option value="select">- MM -</option>
            <?php func_fill_month($i_to_month); ?>
          </select>
            <select name="opt_bill_day" class="lineborderselect" style="font-size:10px"  onChange="updatevalid(this)" title="reqmenu">
              <option value="select">- MM -</option>
              <?php func_fill_day($i_to_day); ?>
            </select>
            <select name="opt_bill_year" class="lineborderselect" style="font-size:10px"  onChange="updatevalid(this)" title="reqmenu">
              <option value="select">- MM -</option>
              <?php func_fill_year($i_to_year); ?>
            </select>
        </td>
      </tr>
      <? } ?>
      <tr>
        <td width="40%" valign="top" class="fieldname">Date/Time</td>
        <td><font size="1" face="Verdana" color="#000000"><?php print func_get_date_time_12hr($dateToEnter);?> </font> </td>
      </tr>
      <tr>
        <td valign="top" class="fieldname">IP Address</td>
        <td><font size="1" face="Verdana" color="#000000">
          <?=$domain?>
        </font> </td>
      </tr>
      <?php if ($tran_login_type == "tele") { ?>
      <tr>
        <td width="40%" valign="top" class="fieldname">Voice Authorization # </td>
        <td><input type="text" name="authorizationno" size="25" maxlength="50"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
        </td>
      </tr>
      <?php  } ?>
      <tr>
        <td width="40%" valign="top" class="fieldname">Shipping Tracking # </td>
        <td><input type="text" name="shippingno" size="25" maxlength="50"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
        </td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">Social Security # </td>
        <td><input type="text" name="securityno" size="25" maxlength="50"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
        </td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">License state # </td>
        <td><select id="licensestate" name="licensestate" style="font-size:11px;width:120px;font-height:10px;font-face:verdana;" onChange="updatevalid(this)" title="reqmenu">
            <option value="select" selected>- - -Select- - -</option>
            <option>Alabama</option>
            <option> Alaska</option>
            <option> Arizona</option>
            <option> Arkansas</option>
            <option> California</option>
            <option> Colorado</option>
            <option> Connecticut</option>
            <option> DC</option>
            <option> Delaware</option>
            <option> Florida</option>
            <option> Georgia</option>
            <option> Hawaii</option>
            <option> Idaho </option>
            <option> Illinois</option>
            <option> Indiana</option>
            <option> Iowa</option>
            <option> Kansas</option>
            <option> Kentucky </option>
            <option> Louisiana </option>
            <option> Maine</option>
            <option> Maryland</option>
            <option> Massachusetts</option>
            <option> Michigan</option>
            <option> Minnesota</option>
            <option> Mississippi</option>
            <option> Missouri</option>
            <option> Montana</option>
            <option> Nebraska</option>
            <option> Nevada</option>
            <option> New Hampshire</option>
            <option> New Jersey</option>
            <option> New Mexico</option>
            <option> New York</option>
            <option> North Carolina</option>
            <option> North Dakota</option>
            <option> Ohio</option>
            <option> Oklahoma </option>
            <option> Oregon</option>
            <option> Pennsylvania</option>
            <option> Rhode Island</option>
            <option> South Carolina</option>
            <option> South Dakota</option>
            <option> Tennessee</option>
            <option> Texas</option>
            <option> Utah</option>
            <option> Vermont</option>
            <option> Virginia</option>
            <option> Washington</option>
            <option> Washington DC</option>
            <option> West Virginia</option>
            <option> Wisconsin</option>
            <option> Wyoming </option>
          </select>
        </td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">Drivers License # </td>
        <td><input type="text" name="driverlicense" size="25" maxlength="50"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
        </td>
      </tr>
      <tr>
        <td width="40%" valign="top" class="fieldname">Misc :</td>
        <td><input type="text" name="misc" size="25" maxlength="50"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)"></td>
      </tr>
      <?php if($blockrebill!=1){ ?>
      <?php } ?>
    </table></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="60%">&nbsp;</td>
    <td width="40%" align="right">&nbsp;</td>
  </tr>
  <tr> 
    <td bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
    <td align="right" bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr> 	<td height="25" align="center">Please Submit only one time. </td> 
 </tr>
  <tr>
	    <tr>   <td height="25" align="center"><input type="image" name="add" src="https://www.etelegate.com/images/submit.jpg" ></td>
        </tr> 
 <tr>
            <td align="center"><font size="2" face="Verdana"><strong>Your purchase will be billed and appear on your billing statement as<font size="2" face="Verdana">: &quot;<?=$bill_des?>&quot;</font></strong><font size="2" face="Verdana"><strong><br>
              </strong></font></font><font face="Verdana"><font face="Verdana"><span class="style13">Customer Support is our main focus which is available 24/7/365<br>
You can cancel Your subscription at ANY time!<br>
Etelegate Online Customer Service is available online at <a href="www.etelegate.net">www.etelegate.net</a> USA and Canada Toll free customer support tel. line (24/7/365): 1 (866)-633-7467 (ext. 1). <br>
OR Direct Dial: 1 (212)-631-4223<br>
Email support: <a href="etelegate.com">customerservice@Etelegate.com</a> </span></font></font></td>
</tr>  	
</table>
</form>
</td>
  </tr>
</table>