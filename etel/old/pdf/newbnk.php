<?php
	$str="12345"."112"."100"."USD"."https://www.etelegate.com/scandorder_callback.php"."test"."yes"."visa"."4444333322221111"."0206"."123"."Mr"."Chris"."Wesson"."225 West 78 Street"."New York"."NY"."10024"."US"."(212) 785 6684".""."test@scandorderinc.com"."1234"."050670"."v7iTT5yq6_66eQ";
    /*  $string =$merchant_id ,$tr_id,$tr_amount,
	  if(
	  $,$, $,$,$, $,$,$, $,$,$, */                                                                                                     
md5($str);
?>
<html>
<head>
	<title>Demo Payment</title>
	



</head>

<body>
<form name="Scandorder" method="post" action="https://merchants.scandorderinc.com/pos/pos_entrypoint.cfm">
<table>

	<tr><td>debug_output: </td><td><input type="text" size="80" name="debug_output" value="Yes"></td></tr>
	
	<tr><td>merchant_id: </td><td><input type="text" size="80" name="merchant_id" value="12345"></td></tr>
	<tr><td>tr_id: </td><td><input type="text" size="80" name="tr_id" value="112"></td></tr>
	<tr><td>tr_amount: </td><td><input type="text" size="80" name="tr_amount" value="100"></td></tr>
	<tr><td>tr_currency: </td><td><input type="text" size="80" name="tr_currency" value="USD"></td></tr>
	
	<tr><td>tr_callback_url: </td><td><input type="text" size="80" name="tr_callback_url" value="https://www.etelegate.com/scandorder_callback.php"></td></tr>
	
	<tr><td>tr_cc_type: </td><td><input type="text" size="80" name="tr_cc_type" value="visa"></td></tr>
	<tr><td>tr_cc_number: </td><td><input type="text" size="80" name="tr_cc_number" value="4444333322221111"></td></tr>
	<tr><td>tr_cc_exp_date: </td><td><input type="text" size="80" name="tr_cc_exp_date" value="0206"></td></tr>
	<tr><td>tr_cvx2: </td><td><input type="text" size="80" name="tr_cvx2" value="123"></td></tr>
	
	<tr><td>tr_submerchant: </td><td><input type="text" size="80" name="tr_submerchant" value=""></td></tr>
	
	<tr><td>tr_description: </td><td><input type="text" size="80" name="tr_description" value="test"></td></tr>
	<tr><td>tr_testMode: </td><td><input type="text" size="80" name="tr_testMode" value="yes"></td></tr>
	<tr><td>cus_title: </td><td><input type="text" size="80" name="cus_title" value="Mr"></td></tr>
	<tr><td>cus_firstname: </td><td><input type="text" size="80" name="cus_firstname" value="Chris"></td></tr>
	<tr><td>cus_lastname: </td><td><input type="text" size="80" name="cus_lastname" value="Wesson"></td></tr>
	<tr><td>cus_address1: </td><td><input type="text" size="80" name="cus_address1" value="225 West 78 Street"></td></tr>
	
	<tr><td>cus_address2: </td><td><input type="text" size="80" name="cus_address2" value=""></td></tr>
	<tr><td>cus_city: </td><td><input type="text" size="80" name="cus_city" value="New York"></td></tr>
	<tr><td>cus_state: </td><td><input type="text" size="80" name="cus_state" value="NY"></td></tr>
	<tr><td>cus_zip: </td><td><input type="text" size="80" name="cus_zip" value="10024"></td></tr>
	<tr><td>cus_country: </td><td><input type="text" size="80" name="cus_country" value="US"></td></tr>
	<tr><td>cus_phone: </td><td><input type="text" size="80" name="cus_phone" value="(212) 785 6684"></td></tr>
	<tr><td>cus_cellphone: </td><td><input type="text" size="80" name="cus_cellphone" value=""></td></tr>
	<tr><td>cus_email: </td><td><input type="text" size="80" name="cus_email" value="test@scandorderinc.com"></td></tr>
	
	<tr><td>cus_ssn: </td><td><input type="text" size="80" name="cus_ssn" value="1234"></td></tr>
	<tr><td>cus_birthday: </td><td><input type="text" size="80" name="cus_birthday" value="050670"></td></tr>
	
	<tr><td>secret_key: </td><td><input type="text" size="80" name="secret_key" value="v7iTT5yq6_66eQ"></td></tr>
	
	
	<tr><td>API_version: </td><td><input type="text" size="80" name="API_version" value="10"></td></tr>
	
	
	<td><input type="text" size="80" name="checksum" value="<?=md5($str);?>"></td></tr>
	<tr><td colspan=2 align="center"><input type="submit" name="pay" alt="Pay" value="Scandorder"></td></tr>
</table>
</form>