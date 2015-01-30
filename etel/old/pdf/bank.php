<?php 


//merchant_id & tr_id & tr_amount & tr_callback_url & 
 //& tr_max_amount & tr_customerdata_modify & tr_description & tr_testmode & cus_title & cus_firstname & cus_lastname & cus_address1 & cus_address2 & cus_city & cus_state & cus_zip & cus_country & cus_phone & cus_cellphone & cus_email & secret_key>

//$string="A12345D123456784995USDhttp://www.yoursite.com/scandorder_callback.aspsome softwareyesVisa41111111111111111205123MrsJulietBillinger641 Billing CircleApt. 206BillingtonVT44666US23170066662317225555juliet@billingeremail.com7019060570";
 $string="GBOFA"."D12345678"."4995"."USD"."http://www.etelegate.com/scandorder_callback.php"."some software"."yes"."Visa"."4111111111111111"."1205"."123"."Mrs"."Juliet"."Billinger"."641 Billing Circle"."Apt. 206"."Billington"."VT"."44666"."US"."2317006666"."2317225555"."juliet@billingeremail.com"."7019"."060570"."SWDJLMXCP7982361";

echo $chksum =md5($string);

?>
<form action="https://merchants.scandorderinc.com/pos/pos_entrypoint.cfm" method="post">
	<input type="Hidden" name="merchant_id" value="GBOFA">
	<input type="Hidden" name="tr_id" value="D12345678">
	<input type="Hidden" name="tr_amount" value = "4995">
	<input type="Hidden" name="tr_currency" value="USD">
	<input type="Hidden" name="tr_callback_url" value="https://www.etelegate.com/scandorder_callback.php">	
	<input type="Hidden" name="tr_description" value="some software">
	<input type="Hidden" name="tr_testmode" value="yes">
	<input type="Hidden" name="tr_cc_type" value="Visa">
	<input type="Hidden" name="tr_cc_number" value="4111111111111111">
	<input type="Hidden" name="tr_exp_date" value="1205">
	<input type="Hidden" name="tr_cvx2" value="123">
	<input type="Hidden" name="cus_title" value="Mrs">
	<input type="Hidden" name="cus_firstname" value="Juliet">
	<input type="Hidden" name="cus_lastname" value="Billinger">
	<input type="Hidden" name="cus_address1" value="641 Billing Circle">
	<input type="Hidden" name="cus_address2" value="Apt. 206">
	<input type="Hidden" name="cus_city" value="Billington">
	<input type="Hidden" name="cus_state" value="VT">
	<input type="Hidden" name="cus_zip" value="44666">
	<input type="Hidden" name="cus_country" value="US">
	<input type="Hidden" name="cus_phone" value="2317006666">
	<input type="Hidden" name="cus_cellphone" value="2317225555">
	<input type="Hidden" name="cus_email" value="juliet@billingeremail.com">
	<input type="Hidden" name="cus_ssn" value="7019">
	<input type="Hidden" name="cus_birthday" value="060570">
	<input type="Hidden" name="secret_key" value="SWDJLMXCP7982361">
	<input type="Hidden" name="checksum" value="<?=$chksum?>">
	<input type="Hidden" name="API_version" value="9">

	<input type="Submit" name="BSubmit" value="Submit Data">
</form> 
