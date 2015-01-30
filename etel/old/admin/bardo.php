<html>
<head>
<title>Payment</title>
</head>
<body onLoad="this.submit();">
<form method="post" action="bardo_merchant.php" target="_blank">
<input type="hidden" name="SHOP_ID" value="TELEGATE">
<input type="hidden" name="SHOP_NUMBER" value="123456">
<input type="hidden" name="CUSTOMER_LAST_NAME" value="Asharaf">
<input type="hidden" name="CUSTOMER_FIRST_NAME" value="Abish">
<input type="hidden" name="CUSTOMER_EMAIL" value="abishasharaf@yahoo.com">
<input type="hidden" name="CUSTOMER_ADRESS" value="Zerone">
<input type="hidden" name="CUSTOMER_CITY" value="Cochin">
<input type="hidden" name="CUSTOMER_ZIP_CODE" value="686537">
<input type="hidden" name="CUSTOMER_STATE" value="Kerala">
<input type="hidden" name="CUSTOMER_COUNTRY" value="India">
<input type="hidden" name="CUSTOMER_PHONE" value="2404011">
<input type="hidden" name="CUSTOMER_IP" value="<?=$ip?>">
<input type="hidden" name="PRODUCT_NAME" value="Service">
<input type="hidden" name="TRANSAC_AMOUNT" value="100">
<input type="hidden" name="CURRENCY_CODE" value="EUR">
<input type="hidden" name="CB_TYPE" value="V">
<input type="hidden" name="CB_NUMBER" value="4015504397328242">
<input type="hidden" name="CB_MONTH" value="12">
<input type="hidden" name="CB_YEAR" value="05">
<input type="hidden" name="CB_CVC" value="123">
<input type="hidden" name="LANGUAGE_CODE" value="ENG">
</form>
</body>
</html>			
