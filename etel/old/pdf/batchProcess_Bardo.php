<?php 
die();
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//


require_once('includes/function.php');
		
		$shopeId 			= (isset($HTTP_POST_VARS['SHOP_ID'])?trim($HTTP_POST_VARS['SHOP_ID']):"");
		$shopeNumber 		= (isset($HTTP_POST_VARS['SHOP_NUMBER'])?trim($HTTP_POST_VARS['SHOP_NUMBER']):"");
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
		$cardExpMonth 		= (isset($HTTP_POST_VARS['CB_MONTH'])?trim($HTTP_POST_VARS['CB_MONTH']):"");
		$cardExpYear 		= (isset($HTTP_POST_VARS['CB_YEAR'])?trim($HTTP_POST_VARS['CB_YEAR']):"");
		$cardCvcNumber 		= (isset($HTTP_POST_VARS['CB_CVC'])?trim($HTTP_POST_VARS['CB_CVC']):"");
		$transactionType 	= (isset($HTTP_POST_VARS['TRANS_TYPE'])?trim($HTTP_POST_VARS['TRANS_TYPE']):"");
		$str_3DS 			= (isset($HTTP_POST_VARS['3DS'])?trim($HTTP_POST_VARS['3DS']):"");
	
?>

<title>Payment</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
	self.name="receive"
</script>
</head>
<body>
<form name="frmPayment" method="post" action="https://www.bardo-secured-transactions.com/cpe/receive.asp" target="_blank">
<input type="hidden" name="SHOP_ID" value="<?=$shopeId?>">
<input type="hidden" name="CUSTOMER_IP" value="<?=$customerIp?>">
<input type="hidden" name="PRODUCT_NAME" value="Service">
<input type="hidden" name="LANGUAGE_CODE" value="ENG">
<input type="hidden" name="CURRENCY_CODE" value="<?=$currencyCode?>">
<input type="hidden" name="SHOP_NUMBER" value="<?=$shopeNumber?>">
<input type="hidden" name="CUSTOMER_LAST_NAME" value="<?=$customerLastName?>">
<input type="hidden" name="CUSTOMER_FIRST_NAME" value="<?=$customerFirstName?>">
<input type="hidden" name="CUSTOMER_EMAIL" value="<?=$customerEmail?>">
<input type="hidden" name="CUSTOMER_ADDRESS" value="<?=$customerAddress?>">
<input type="hidden" name="CUSTOMER_CITY" value="<?=$customerCity?>">
<input type="hidden" name="CUSTOMER_ZIP_CODE" value="<?=$customerZipCode?>">
<input type="hidden" name="CUSTOMER_STATE" value="<?=$customerState?>">
<input type="hidden" name="CUSTOMER_COUNTRY" value="<?=$customerCountry?>">
<input type="hidden" name="CUSTOMER_PHONE" value="<?=$customerPhone?>">
<input type="hidden" name="TRANSAC_AMOUNT" value="<?=str_replace(",","",number_format($transactAmount,0))?>">
<input type="hidden" name="CB_TYPE" value="<?=$cardType?>">
<input type="hidden" name="CB_NUMBER" value="<?=$cardNumber?>">
<input type="hidden" name="CB_MONTH" value="<?=$cardExpMonth?>">
<input type="hidden" name="CB_YEAR" value="<?=substr($cardExpYear,2,3)?>">
<input type="hidden" name="CB_CVC" value="<?=$cardCvcNumber?>">
<input type="hidden" name="3DS" value="<?=$str_3DS?>">
</form>
</body>
