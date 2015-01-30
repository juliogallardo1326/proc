<html>
<head>
<title>Payment Processing</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?  
//The string $params is the string which contains the fields posted to Bardo Server

$params="SHOP_ID=".$_POST["SHOP_ID"]."&SHOP_NUMBER=".$_POST["SHOP_NUMBER"]."&LANGUAGE_CODE=".$_POST["LANGUAGE_CODE"]."&TRANSAC_AMOUNT=".$_POST["TRANSAC_AMOUNT"]."&CURRENCY_CODE=".$_POST["CURRENCY_CODE"]."&CUSTOMER_EMAIL=".$_POST["CUSTOMER_EMAIL"]."&CUSTOMER_LAST_NAME=".$_POST["CUSTOMER_LAST_NAME"]."&CUSTOMER_FIRST_NAME=".$_POST["CUSTOMER_FIRST_NAME"]."&CUSTOMER_ADRESS=".$_POST["CUSTOMER_ADRESS"]."&CUSTOMER_ZIP_CODE=".$_POST["CUSTOMER_ZIP_CODE"]."&CUSTOMER_STATE=".$_POST["CUSTOMER_STATE"]."&CUSTOMER_COUNTRY=".$_POST["CUSTOMER_COUNTRY"]."&CUSTOMER_CITY=".$_POST["CUSTOMER_CITY"]."&CUSTOMER_PHONE=".$_POST["CUSTOMER_PHONE"]."&CB_TYPE=".$_POST["CB_TYPE"]."&PRODUCT_NAME=".$_POST["PRODUCT_NAME"]."&CUSTOMER_IP=".$_POST["CUSTOMER_IP"]."&CB_NUMBER=".$_POST["CB_NUMBER"]."&CB_MONTH=".$_POST["CB_MONTH"]."&CB_YEAR=".$_POST["CB_YEAR"]."&CB_CVC=".$_POST["CB_CVC"]."CUSTOMER_ADDRESS=".$_POST["CUSTOMER_ADDRESS"];

//$url is the URL where the data are posted 
   $url = "https://www.bardo-secured-transactions.com/cpe/receive.asp";
   $user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
$ch = curl_init();
   curl_setopt($ch, CURLOPT_POST,1);
   curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
   curl_setopt($ch, CURLOPT_URL,$url);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
   curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // this line makes it work under https

   $result=curl_exec ($ch);
   curl_close ($ch);
 //The  result is displayed to the user. 
 echo($result);

?>
</body>
</html>
