<?php
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
include 'includes/header.php';
require_once( '../includes/function.php');
$headerInclude="transactions";	
	


$merchant_id 		= (isset($HTTP_POST_VARS['MERCHANT_ID'])?quote_smart($HTTP_POST_VARS['MERCHANT_ID']):"");
$secret_key			= (isset($HTTP_POST_VARS['MERCHANT_CODE'])?quote_smart($HTTP_POST_VARS['MERCHANT_CODE']):"");
$tr_id 				= (isset($HTTP_POST_VARS['SHOP_NUMBER'])?quote_smart($HTTP_POST_VARS['SHOP_NUMBER']):"");
$tr_amount 			= (isset($HTTP_POST_VARS['TRANSAC_AMOUNT'])?quote_smart($HTTP_POST_VARS['TRANSAC_AMOUNT']):"");
$tr_currency 		= (isset($HTTP_POST_VARS['CURRENCY_CODE'])?quote_smart($HTTP_POST_VARS['CURRENCY_CODE']):"");
$tr_description		= (isset($HTTP_POST_VARS['PRODUCT_NAME'])?quote_smart($HTTP_POST_VARS['PRODUCT_NAME']):"");
$tr_cc_type 		= (isset($HTTP_POST_VARS['CB_TYPE'])?quote_smart($HTTP_POST_VARS['CB_TYPE']):"");//visa or MasterCard
$tr_cc_number 		= (isset($HTTP_POST_VARS['CB_NUMBER'])?quote_smart($HTTP_POST_VARS['CB_NUMBER']):"");
$cardExpire			= (isset($HTTP_POST_VARS['CB_EXPIRE'])?quote_smart($HTTP_POST_VARS['CB_EXPIRE']):"");
$tr_callback_url	= "https://www.etelegate.com/scandorder_callback.php";
$tr_cc_exp_date 	= $cardExpire;
$tr_cvx2 			= (isset($HTTP_POST_VARS['CB_CVC'])?quote_smart($HTTP_POST_VARS['CB_CVC']):"");
$tr_submerchant 	=  "";
$tr_testMode		=  "no";
$cus_title			= "Mr";//(isset($HTTP_POST_VARS['SHOP_ID'])?quote_smart($HTTP_POST_VARS['SHOP_ID']):"");
$cus_firstname 		= (isset($HTTP_POST_VARS['CUSTOMER_FIRST_NAME'])?quote_smart($HTTP_POST_VARS['CUSTOMER_FIRST_NAME']):"");
$cus_lastname 		= (isset($HTTP_POST_VARS['CUSTOMER_LAST_NAME'])?quote_smart($HTTP_POST_VARS['CUSTOMER_LAST_NAME']):"");
$cus_address1 		= (isset($HTTP_POST_VARS['CUSTOMER_ADRESS'])?quote_smart($HTTP_POST_VARS['CUSTOMER_ADRESS']):"");
$cus_address2		= (isset($HTTP_POST_VARS['CUSTOMER_CITY'])?quote_smart($HTTP_POST_VARS['CUSTOMER_CITY']):"");
$cus_city			= (isset($HTTP_POST_VARS['CUSTOMER_CITY'])?quote_smart($HTTP_POST_VARS['CUSTOMER_CITY']):"");
$cus_state			= (isset($HTTP_POST_VARS['CUSTOMER_STATE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_STATE']):"");
$cus_zip			= (isset($HTTP_POST_VARS['CUSTOMER_ZIP_CODE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_ZIP_CODE']):"");
$cus_country		= (isset($HTTP_POST_VARS['CUSTOMER_COUNTRY'])?quote_smart($HTTP_POST_VARS['CUSTOMER_COUNTRY']):"");
$cus_phone			= (isset($HTTP_POST_VARS['CUSTOMER_PHONE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_PHONE']):"");
$cus_cellphone		= "";
$cus_email			= (isset($HTTP_POST_VARS['CUSTOMER_EMAIL'])?quote_smart($HTTP_POST_VARS['CUSTOMER_EMAIL']):"");
$cus_ssn			= (isset($HTTP_POST_VARS['SECURITY_NO'])?quote_smart($HTTP_POST_VARS['SECURITY_NO']):"");//social security
$cus_birthday		= (isset($HTTP_POST_VARS['BIRTH_DATE'])?quote_smart($HTTP_POST_VARS['BIRTH_DATE']):"");

$string =$merchant_id.$tr_id.$tr_amount.$tr_currency.$tr_callback_url.$tr_description.$tr_testMode.$tr_cc_type.$tr_cc_number.$tr_cc_exp_date.$tr_cvx2.$cus_title.$cus_firstname.$cus_lastname.$cus_address1.$cus_address2.$cus_city.$cus_state.$cus_zip.$cus_country.$cus_phone.$cus_cellphone.$cus_email.$cus_ssn.$cus_birthday.$secret_key ;                                                                                          

$value=md5($string);
$strMessage = "<center><br><img src='images/progressbar.gif'><br><h3>Please wait. Transaction in Progress....</center>";
?>
<html>
<head>
<title>:: :: Payment Gateway</title>
	
<script language="JavaScript">
function dosubmit(){
document.Scandorder.submit();

}

self.name="receive"
function SubmitPage()
{
	document.Scandorder.submit();
	navigate("display_scan_result.php?SHOP_NUMBER=<?=$tr_id?>","receive");
}


</script>


</head>
<!--onLoad="dosubmit();"-->
<body onLoad="javascript:SubmitPage();" > 

<table width="100%" align="center" height="169">
<tr>
    <td width="100%" align="center"><br>
<form name="Scandorder" method="post" action="https://merchants.scandorderinc.com/pos/pos_entrypoint.cfm"  target="_blank">
        <table width="60%" border="0" align="center" cellspacing="0" cellpadding="0" height="50%" style="border:1px solid black">
         <tr align="center" valign="middle">
		   <td height="20" colspan="2" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please submit this page to complete the transaction and bank process</font></td>
			</tr>
	 <tr align="center" valign="middle" bgcolor="#CCCCCC"> 
            <td height="20" colspan="2" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<strong>Transaction 
              Details</strong></font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Transaction 
              Number</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$tr_id ?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;First 
              Name</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_firstname ?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Last 
              Name</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_lastname?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Email</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_email?>
              </font></td>
          </tr>
         
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Address</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_address1?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;City</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_city?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;State</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_state?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Country</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_country?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Zipcode</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_zip?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Phone 
              Number</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_phone?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Transaction 
              Amount</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=number_format(($tr_amount/100),2)?>
              </font></td>
			  
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Card 
              Type</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?php  print "$tr_cc_type "; ?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Card 
              Number</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$tr_cc_number?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Security 
              Code</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=$cus_ssn?>
              </font></td>
          </tr>
          <tr align="left" valign="middle"> 
            <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Card 
              Expire Date</font></td>
            <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
              <?=substr($tr_cc_exp_date,0,2);?>
              / 
              <?=substr($tr_cc_exp_date,2,3);?>
              </font></td>
          </tr>
          <tr>
            <td align="center" valign="middle" colspan="2" height="50" class="cl1">&nbsp;</td>
          </tr>
        </table><br>
<!--<tr><td align="center"><input type="submit" name="pay" alt="Pay" value="Submit"></td></tr>-->


	<input type="hidden"  name="debug_output" value="Yes">
	<input type="hidden"  name="merchant_id" value="<?=$merchant_id?>" > 
	<input type="hidden"  name="tr_id" value="<?=$tr_id?>"> 
	<input type="hidden"  name="tr_amount" value="<?=$tr_amount?>">
	<input type="hidden"  name="tr_currency" value="<?=$tr_currency?>">
	<input type="hidden"  name="tr_callback_url" value="https://www.etelegate.com/scandorder_callback.php">
	<input type="hidden"  name="tr_cc_type" value="<?=$tr_cc_type?>">
	<input type="hidden"  name="tr_cc_number" value="<?=$tr_cc_number?>">
	<input type="hidden"  name="tr_cc_exp_date" value="<?=$tr_cc_exp_date?>">
	<input type="hidden"  name="tr_cvx2" value="<?=$tr_cvx2?>">
	<input type="hidden"  name="tr_submerchant" value="<?=$tr_submerchant?>">
	<input type="hidden"  name="tr_description" value="<?=$tr_description?>">
	<input type="hidden"  name="tr_testMode" value="no">
	<input type="hidden"  name="cus_title" value="<?=$cus_title?>">
	<input type="hidden"  name="cus_firstname" value="<?=$cus_firstname?>">
	<input type="hidden"  name="cus_lastname" value="<?=$cus_lastname?>">
	<input type="hidden"  name="cus_address1" value="<?=$cus_address1?>">
	<input type="hidden"  name="cus_address2" value="<?=$cus_address2?>">
	<input type="hidden"  name="cus_city" value="<?=$cus_city?>">
	<input type="hidden"  name="cus_state" value="<?=$cus_state?>">
	<input type="hidden"  name="cus_zip" value="<?=$cus_zip?>">
	<input type="hidden"  name="cus_country" value="<?=$cus_country?>">
	<input type="hidden"  name="cus_phone" value="<?=$cus_phone?>">
	<input type="hidden"  name="cus_cellphone" value="<?=$cus_cellphone?>">
	<input type="hidden"  name="cus_email" value="<?=$cus_email?>">
	<input type="hidden"  name="cus_ssn" value="<?=$cus_ssn?>">
	<input type="hidden"  name="cus_birthday" value="<?=$cus_birthday?>">
	<input type="hidden"  name="secret_key" value="<?=$secret_key?>">
	<input type="hidden"  name="API_version" value="10">
	<input type="hidden"  name="checksum" value="<?=$value?>">
      </form>
	  
</td></tr></table>
<?php
include 'includes/footer.php';
?>