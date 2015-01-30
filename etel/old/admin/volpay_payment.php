<?php 
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//volpay_payment.php:	The page functions for entering the creditcard details.
include 'includes/sessioncheck.php';
$headerInclude="transactions";	
include 'includes/header.php';
require_once( '../includes/function.php');

		
		$i_company_id 		= (isset($HTTP_POST_VARS['COMPANY_ID'])?quote_smart($HTTP_POST_VARS['COMPANY_ID']):"");
		$UserId 			= (isset($HTTP_POST_VARS['USER_ID'])?quote_smart($HTTP_POST_VARS['USER_ID']):"");
		$UserPassword		= (isset($HTTP_POST_VARS['USER_PASSWORD'])?quote_smart($HTTP_POST_VARS['USER_PASSWORD']):"");
		$TransNumber 		= (isset($HTTP_POST_VARS['TRAN_NUMBER'])?quote_smart($HTTP_POST_VARS['TRAN_NUMBER']):"");
		$customerLastName 	= (isset($HTTP_POST_VARS['CUSTOMER_LAST_NAME'])?quote_smart($HTTP_POST_VARS['CUSTOMER_LAST_NAME']):"");
		$customerFirstName 	= (isset($HTTP_POST_VARS['CUSTOMER_FIRST_NAME'])?quote_smart($HTTP_POST_VARS['CUSTOMER_FIRST_NAME']):"");
		$customerEmail 		= (isset($HTTP_POST_VARS['CUSTOMER_EMAIL'])?quote_smart($HTTP_POST_VARS['CUSTOMER_EMAIL']):"");
		$customerAddress 	= (isset($HTTP_POST_VARS['CUSTOMER_ADRESS'])?quote_smart($HTTP_POST_VARS['CUSTOMER_ADRESS']):"");
		$customerCity 		= (isset($HTTP_POST_VARS['CUSTOMER_CITY'])?quote_smart($HTTP_POST_VARS['CUSTOMER_CITY']):"");
		$customerZipCode 	= (isset($HTTP_POST_VARS['CUSTOMER_ZIP_CODE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_ZIP_CODE']):"");
		$customerState 		= (isset($HTTP_POST_VARS['CUSTOMER_STATE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_STATE']):"");
		$customerCountry 	= (isset($HTTP_POST_VARS['CUSTOMER_COUNTRY'])?quote_smart($HTTP_POST_VARS['CUSTOMER_COUNTRY']):"");
		$customerPhone 		= (isset($HTTP_POST_VARS['CUSTOMER_PHONE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_PHONE']):"");
		$customerIp 		= (isset($HTTP_POST_VARS['CUSTOMER_IP'])?quote_smart($HTTP_POST_VARS['CUSTOMER_IP']):"");
		$productName 		= (isset($HTTP_POST_VARS['PRODUCT_NAME'])?quote_smart($HTTP_POST_VARS['PRODUCT_NAME']):"");
		$transactAmount 	= (isset($HTTP_POST_VARS['TRANSAC_AMOUNT'])?quote_smart($HTTP_POST_VARS['TRANSAC_AMOUNT']):"");
		$currencyCode 		= (isset($HTTP_POST_VARS['CURRENCY_CODE'])?quote_smart($HTTP_POST_VARS['CURRENCY_CODE']):"");
		$cardType 			= (isset($HTTP_POST_VARS['CB_TYPE'])?quote_smart($HTTP_POST_VARS['CB_TYPE']):"");
		$cardNumber 		= (isset($HTTP_POST_VARS['CB_NUMBER'])?quote_smart($HTTP_POST_VARS['CB_NUMBER']):"");
		$cardExpire 		= (isset($HTTP_POST_VARS['CB_EXPIRE'])?quote_smart($HTTP_POST_VARS['CB_EXPIRE']):"");
		$cardCvcNumber 		= (isset($HTTP_POST_VARS['CB_CVC'])?quote_smart($HTTP_POST_VARS['CB_CVC']):"");
		$transactionType 	= (isset($HTTP_POST_VARS['TRANS_TYPE'])?quote_smart($HTTP_POST_VARS['TRANS_TYPE']):"");

?>

<title>Payment</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<form name="FrmPayment" method="post" action="volpay_result.php" target="_self" >
		<input type="hidden" name="COMPANY_ID" value="<?=$i_company_id?>">
		<input type="hidden" name="USER_ID" value="<?=$UserId?>">
		<input type="hidden" name="USER_PASSWORD" value="<?=$UserPassword?>">
		<input type="hidden" name="TRAN_NUMBER" value="<?=$TransNumber?>">
		<input type="hidden" name="CUSTOMER_LAST_NAME" value="<?=$customerLastName?>">
		<input type="hidden" name="CUSTOMER_FIRST_NAME" value="<?=$customerFirstName?>">
		<input type="hidden" name="CUSTOMER_EMAIL" value="<?=$customerEmail?>">
		<input type="hidden" name="CUSTOMER_ADRESS" value="<?=$customerAddress?>">
		<input type="hidden" name="CUSTOMER_CITY" value="<?=$customerCity?>">
		<input type="hidden" name="CUSTOMER_ZIP_CODE" value="<?=$customerZipCode?>">
		<input type="hidden" name="CUSTOMER_STATE" value="<?=$customerState?>">
		<input type="hidden" name="CUSTOMER_COUNTRY" value="<?=$customerCountry?>">
		<input type="hidden" name="CUSTOMER_PHONE" value="<?=$customerPhone?>">
		<input type="hidden" name="CUSTOMER_IP" value="<?=$customerIp?>">
		<input type="hidden" name="PRODUCT_NAME" value="Service">
		<input type="hidden" name="TRANSAC_AMOUNT" value="<?=str_replace(",","",number_format($transactAmount,0))?>">
		<input type="hidden" name="CURRENCY_CODE" value="<?=$currencyCode?>">
		<input type="hidden" name="CB_TYPE" value="<?=$cardType?>">
		<input type="hidden" name="CB_NUMBER" value="<?=$cardNumber?>">
		<input type="hidden" name="CB_EXPIRE" value="<?=$cardExpire?>">
		<input type="hidden" name="CB_CVC" value="<?=$cardCvcNumber?>">
		<input type="hidden" name="TRANS_TYPE" value="<?=$transaction_type?>">

<br> 
  <table width="60%" border="0" align="center" cellspacing="0" cellpadding="0" height="50%" style="border:1px solid black">
	<tr align="center" valign="middle" bgcolor="#CCCCCC">
   <td height="20" colspan="2" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;<strong>Transaction 
        Details</strong></font></td>
	</tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Transaction 
        Number</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$TransNumber?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;First 
        Name</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerFirstName?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Last 
        Name</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerLastName?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Email</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerEmail?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Address</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerAddress?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;City</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerCity?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;State</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerState?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Country</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerCountry?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Zipcode</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerZipCode?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Phone 
        Number</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$customerPhone?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Transaction 
        Amount</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=number_format(($transactAmount/100),2)?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Card 
        Type</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?php if($cardType=="Visa") print"Visa Card"; else print"Master Card";?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Card 
        Number</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$cardNumber?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Security 
        Code</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$cardCvcNumber?>
        </font></td>
    </tr>
    <tr align="left" valign="middle"> 
      <td width="40%" height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Card 
        Expire Date</font></td>
      <td height="20" class="cl1"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp; 
        <?=$cardExpire?>
        </font></td>
    </tr>
	<tr><td align="center" valign="middle" colspan="2" height="50" class="cl1"><input type="image" name="send" SRC="<?=$tmpl_dir?>/images/submit.jpg"></td></tr>
  </table>
<br>
</form>
</body>
<script>
	document.forms[0].submit();
</script>
<?php
include 'includes/footer.php';
?>
