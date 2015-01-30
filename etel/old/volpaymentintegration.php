<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//

require_once('includes/function.php');
include('includes/dbconnection.php');
		
		$i_company_id 		= (isset($HTTP_POST_VARS['COMPANY_ID'])?trim($HTTP_POST_VARS['COMPANY_ID']):"");
		$UserId 			= (isset($HTTP_POST_VARS['USER_ID'])?trim($HTTP_POST_VARS['USER_ID']):"");
		$UserPassword		= (isset($HTTP_POST_VARS['USER_PASSWORD'])?trim($HTTP_POST_VARS['USER_PASSWORD']):"");
		$TransNumber 		= (isset($HTTP_POST_VARS['TRAN_NUMBER'])?trim($HTTP_POST_VARS['TRAN_NUMBER']):"");
		$customerLastName 	= (isset($HTTP_POST_VARS['CUSTOMER_LAST_NAME'])?trim($HTTP_POST_VARS['CUSTOMER_LAST_NAME']):"");
		$customerFirstName 	= (isset($HTTP_POST_VARS['CUSTOMER_FIRST_NAME'])?trim($HTTP_POST_VARS['CUSTOMER_FIRST_NAME']):"");
		$customerEmail 		= (isset($HTTP_POST_VARS['CUSTOMER_EMAIL'])?trim($HTTP_POST_VARS['CUSTOMER_EMAIL']):"");
		$customerAddress 	= (isset($HTTP_POST_VARS['CUSTOMER_ADRESS'])?trim($HTTP_POST_VARS['CUSTOMER_ADRESS']):"");
		$customerCity 		= (isset($HTTP_POST_VARS['CUSTOMER_CITY'])?trim($HTTP_POST_VARS['CUSTOMER_CITY']):"");
		$customerZipCode 	= (isset($HTTP_POST_VARS['CUSTOMER_ZIP_CODE'])?trim($HTTP_POST_VARS['CUSTOMER_ZIP_CODE']):"");
		$customerState 		= (isset($HTTP_POST_VARS['CUSTOMER_STATE'])?trim($HTTP_POST_VARS['CUSTOMER_STATE']):"");
		$customerCountry 	= (isset($HTTP_POST_VARS['CUSTOMER_COUNTRY'])?trim($HTTP_POST_VARS['CUSTOMER_COUNTRY']):"");
		$customerPhone 		= (isset($HTTP_POST_VARS['CUSTOMER_PHONE'])?trim($HTTP_POST_VARS['CUSTOMER_PHONE']):"");
		$customerIp 		= (isset($HTTP_POST_VARS['CUSTOMER_IP'])?trim($HTTP_POST_VARS['CUSTOMER_IP']):"");
		$productName 		= (isset($HTTP_POST_VARS['PRODUCT_NAME'])?trim($HTTP_POST_VARS['PRODUCT_NAME']):"");
		$transactAmount 	= (isset($HTTP_POST_VARS['TRANSAC_AMOUNT'])?trim($HTTP_POST_VARS['TRANSAC_AMOUNT']):"");
		$currencyCode 		= (isset($HTTP_POST_VARS['CURRENCY_CODE'])?trim($HTTP_POST_VARS['CURRENCY_CODE']):"");
		$cardType 			= (isset($HTTP_POST_VARS['CB_TYPE'])?trim($HTTP_POST_VARS['CB_TYPE']):"");
		$cardNumber 		= (isset($HTTP_POST_VARS['CB_NUMBER'])?trim($HTTP_POST_VARS['CB_NUMBER']):"");
		$cardExpire 		= (isset($HTTP_POST_VARS['CB_EXPIRE'])?trim($HTTP_POST_VARS['CB_EXPIRE']):"");
		$cardCvcNumber 		= (isset($HTTP_POST_VARS['CB_CVC'])?trim($HTTP_POST_VARS['CB_CVC']):"");
		$transactionType 	= (isset($HTTP_POST_VARS['TRANS_TYPE'])?trim($HTTP_POST_VARS['TRANS_TYPE']):"");
		
		$order_id 			= (isset($HTTP_POST_VARS['mt_order_id'])?trim($HTTP_POST_VARS['mt_order_id']):"");
		$voiceauth 			= (isset($HTTP_POST_VARS['mt_voiceauth_id'])?trim($HTTP_POST_VARS['mt_voiceauth_id']):"");			
		$return_url 		= (isset($HTTP_POST_VARS['mt_return_url'])?trim($HTTP_POST_VARS['mt_return_url']):"");			

?>

<title>Payment</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="styles/style.css" type="text/css" rel="stylesheet">
<link href="styles/text.css" type="text/css" rel="stylesheet">
</head>
<body topmargin="0" leftmargin="0" bgcolor="#ffffff"  marginheight="0" marginwidth="0">
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="middle" align="center" bgcolor="#ffffff" width="35%"><img src="images/spacer.gif" width="180" height="46" border="0" alt=""></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" src="images/top1.jpg" width="238" height="63" ><img alt="" border="0" src="images/top2.jpg" width="217" height="63"><img alt="" border="0" src="images/top3.jpg" width="138" height="63"><br>
<img alt="" border="0" src="images/top4.jpg" width="238" height="63"><img  alt="" border="0" src="images/top5.jpg" width="217" height="63"><img alt="" border="0" src="images/top6.jpg" width="138" height="63"></td>
</tr>
</table>
<!-- Sub header-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
<tr>
    <td height="9" align="center" background="images/menutopbg.gif"></td>
</tr>
<tr>
    <td background="images/midbg.gif" align="center">&nbsp;</td>
</tr>
</table>
<!-- sub header -->
	<!--submenu starts-->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td height="5" background="images/menubtmbg.gif"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
	<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" height="10">
	<tr>
	  <td width="100%" height="20" align="left">&nbsp;</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<!--submenu ends-->
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  
  <tr>
    <td width="100%" valign="top" align="left">
	<form name="FrmPayment" method="post" action="volresultintegration.php" target="_self" >
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
		<input type="hidden" name="mt_transaction_id" value="<?=$TransNumber?>">
		<input type="hidden" name="mt_voiceauth_id" value="<?=$voiceauth?>">
		<input type="hidden" name="mt_order_id" value="<?=$order_id?>">
		<input type="hidden" name="mt_return_url" value="<?=$return_url?>">

<br> 
  <table width="60%" border="0" align="center" cellspacing="0" cellpadding="0" height="50%" style="border:1px solid black">
	<tr align="center" valign="middle" bgcolor="#78B6C2">
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
	<tr>
            <td align="center" valign="middle" colspan="2" height="50" class="cl1"><!--DWLayoutEmptyCell-->&nbsp;</td>
</tr>
  </table>
<br>
</form>
	</td>
  </tr>
</table>
<script>
	document.forms[0].submit();
</script>

<?php
include("includes/footer.php");
?>
</body>
</html>