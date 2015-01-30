<?php


function cc_NetMerchants_integration($transInfo,$bankInfo)
{
	$trans_response="";
	$trans_response['errormsg'] = "Transaction could not be processed.";



	if ($bankInfo['bk_cc_support']!=1){$trans_response['errormsg'] = "This bank does not support this Integration Function. Please contact an administrator."; return $response;}

	$expDate = explode("/",$transInfo['validupto']);
	$expYear = substr($expDate[0],-2,2);
	$expMonth = $expDate[1];
	$exp = $expMonth.$expYear;
	$bank_trans_id = substr(time(),0,9);

	foreach($transInfo as $key => $item)
		$transInfo[$key] = urlencode($item);

	$Pinfo="";
$Pinfo.="type=sale";  // Required sale / auth / credit, sale = Transaction Sale
$Pinfo.="&username=".$bankInfo['bk_username'];  // Required Username assigned to merchant account
$Pinfo.="&password=".$bankInfo['bk_password'];  // Required Password assigned to merchant account
$Pinfo.="&ccnumber=".$transInfo['CCnumber'];  // Required Credit card number
$Pinfo.="&ccexp=".$exp;  // Required MMYY Credit card expiration (ie. 0705 = 7/2005)
$Pinfo.="&amount=".number_format($transInfo['amount'],2,".",",");  // Required x.xx Total amount to be charged (i.e. 10.00)
$Pinfo.="&cvv=".$transInfo['cvv'];  // Recommended Card security code
$Pinfo.="&orderid=".$bank_trans_id;  // Recommended Order ID
$Pinfo.="&orderdescription=".$transInfo['productdescription'];  // Optional Order description
$Pinfo.="&ipaddress=".$transInfo['ipaddress'];  // Recommended xxx.xxx.xxx.xxx IP address of the cardholder
//$Pinfo.="&tax=".$transInfo['td_product_id'];  // Level II x.xx Total tax amount
//$Pinfo.="&shipping=".$transInfo['td_product_id'];  // Level II x.xx Total shipping amount
//$Pinfo.="&ponumber=".$transInfo['td_product_id'];  // Level II Original Purchase Order
$Pinfo.="&firstname=".$transInfo['name'];  // Recommended Cardholder’s first name
$Pinfo.="&lastname=".$transInfo['surname'];  // Recommended Cardholder’s last name
//$Pinfo.="&company=".$transInfo['td_product_id'];  // Optional Cardholder’s company
$Pinfo.="&address1=".$transInfo['address'];  // Recommended Card billing address
$Pinfo.="&address2=".$transInfo['address2'];  // Optional Card billing address – line 2
$Pinfo.="&city=".$transInfo['city'];  // Recommended Card billing city
$Pinfo.="&state=".$transInfo['state'];  // Recommended CC Card billing state (2 character abbrev.)
$Pinfo.="&zip=".$transInfo['zipcode'];  // Recommended Card billing zip code
$Pinfo.="&country=".$transInfo['country'];  // Recommended CC (ISO-3166) Card billing country (ie. US)
$Pinfo.="&phone=".$transInfo['phonenumber'];  // Recommended Billing phone number
//$Pinfo.="&fax=".$transInfo['td_product_id'];  // Optional Billing fax number
$Pinfo.="&email=".$transInfo['email'];  // Recommended Billing email address
//$Pinfo.="&website=".$transInfo['td_product_id'];  // Optional Website
//$Pinfo.="&shipping_firstname=".$transInfo['td_product_id'];  // Optional Shipping first name
//$Pinfo.="&shipping_lastname=".$transInfo['td_product_id'];  // Optional Shipping last name
//$Pinfo.="&shipping_company=".$transInfo['td_product_id'];  // Optional Shipping company
//$Pinfo.="&shipping_address1=".$transInfo['td_product_id'];  // Optional Shipping address
//$Pinfo.="&shipping_address2=".$transInfo['td_product_id'];  // Optional Shipping address – line 2
//$Pinfo.="&shipping_city=".$transInfo['td_product_id'];  // Optional Shipping city
//$Pinfo.="&shipping_state=".$transInfo['td_product_id'];  // Optional Shipping state
//$Pinfo.="&shipping_zip=".$transInfo['td_product_id'];  // Optional Shipping zip code
//$Pinfo.="&shipping_country=".$transInfo['td_product_id'];  // Optional CC (ISO-3166) Shipping country (ie. US)
//$Pinfo.="&shipping_email=".$transInfo['td_product_id'];  // Optional Shipping email address

	// Uncomment below for live
	$Pinfo = $Pinfo;
	$output_url = "https://secure.networkmerchants.com/gw/api/transact.php?".$Pinfo;

	$process_result = file_get_contents($output_url);
	//$process_result = http_post('ssl://secure.networkmerchants.com', 443, $output_url, $Pinfo);

	$clean_data = trim($process_result);

	parse_str($clean_data);

	$trans_response="";
	if(!$Mess) $trans_response['errormsg'] = "Credit Card Declined";
	else $trans_response['errormsg'] = $Mess;
	$trans_response['errorcode'] = $Error;
	$trans_response['td_bank_transaction_id'] = $transactionid;
	$trans_response['td_process_result'] = $process_result;
	$trans_response['td_process_query'] = $output_url;
	$trans_response['status'] = "D";
	$trans_response['success'] = false;
	if ($response == 1)
	{
		$trans_response['errormsg'] = "Credit Card Accepted";
		$trans_response['status'] = "A";
		$trans_response['success'] = true;
	}
	if ($response == 2)
	{
		$trans_response['errormsg'] = "Credit Card Declined";
		$trans_response['status'] = "D";
		$trans_response['success'] = true;
	}
	if ($response == 3)
	{
		$trans_response['errormsg'] = "Error: $responsetext";
		$trans_response['status'] = "D";
	}
	$trans_response['Invoiceid'] = $transactionid;
	//print_r($trans_response);
	//die();
	return $trans_response;
}

?>
