<html>
<head>
<title>Payment</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
self.name="receive"
</script>
</head>
<body>
<?php $transactionType 	="TELEGATE"; ?>
//Example with SHOP_NUMBER “123456”, you have to send your shop number
<?$SHOP_NUMBER="123456"?>

<form method="post" action="testbard.php" onSubmit="javascript:location.replace('display_result.php','receive')" target="_blank">
<input type="text" name="SHOP_ID" value="TELEGATE">
<input type="text" name="SHOP_NUMBER" value="121880">
<input type="text" name="CUSTOMER_LAST_NAME" value="LSTNAME">
<input type="text" name="CUSTOMER_FIRST_NAME" value="FNAME">
<input type="text" name="CUSTOMER_EMAIL" value="email@email.com">
<input type="text" name="CUSTOMER_ADDRESS" value="ADDRESS">
<input type="text" name="CUSTOMER_CITY" value="CITY">
<input type="text" name="CUSTOMER_ZIP_CODE" value="14528">
<input type="text" name="CUSTOMER_STATE" value="UT ">
<input type="text" name="CUSTOMER_COUNTRY" value="US">
<input type="text" name="CUSTOMER_PHONE" value="1234567890">
<input type="text" name="CUSTOMER_IP" value="123.45.67.9">
<input type="text" name="PRODUCT_NAME" value="PRODUCTNAME">
<input type="text" name="TRANSAC_AMOUNT" value="100">
<input type="text" name="CURRENCY_CODE" value="EUR">
<input type="text" name="CB_TYPE" value="V">
<input type="text" name="CB_NUMBER" value="4015504397328242">
<input type="text" name="CB_MONTH" value="12">
<input type="text" name="CB_YEAR" value="05">
<input type="text" name="CB_CVC" value="123">
<input type="text" name="LANGUAGE_CODE" value="ENG">
<input type="submit" value="send">
</form>
</body>
</html>
