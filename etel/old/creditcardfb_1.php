<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//

//include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
include 'includes/integration.php';

$qrt_insert_details = "select * from `cs_transactiondetails` LIMIT 1";
$result =mysql_query($qrt_insert_details) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
$cc = mysql_fetch_assoc($result);

foreach($cc as $c => $k)
	echo "$"."transInfo['".$c."']='';\n";
die();

$cc['userId'] = 350;
$cc['name'] = "Name";
$cc['surname'] = "Last Name";
$cc['address'] = "Address";
$cc['city'] = "City";
$cc['state'] = "State";
$cc['zipcode'] = "ZipCode";
$cc['country'] = "Country";
$cc['email'] = "Email";
$cc['amount'] = "123";
$cc['ipaddress'] = "192.168.1.1";
$cc['productdescription'] = "Product Description";
$cc['transaction_tracking_id'] = "456123";
$cc['CCnumber'] = "1234567812345678";
$cc['cvv'] = "123";
$cc['validupto'] = "123";
$cc['td_rebillingID'] = -1;

?>