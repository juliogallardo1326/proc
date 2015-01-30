<?php
// Ver 1.0 11-08-2002
// PHP4 & Curl code to do a HTTPS RAW POST TO paymentsgateway.net system 
// coded by Lance Phillips lance@mediaengine.com
// PHP.net Curl Reference http://www.php.net/manual/en/ref.curl.php


// output transaction - this is dynamically created from form variables - for simplicity I have hard-coded it here.
$output_transaction = "pg_merchant_id=xxxx&pg_password=xxxxx&pg_transaction_type=20&pg_total_amount=0.40&pg_billto_postal_name_company=ACH&ecom_billto_postal_name_first=Test&ecom_billto_postal_name_last=Customer&ecom_billto_postal_street_line1=2350+Date+Palm&ecom_billto_postal_city=Cathedral+City&ecom_billto_postal_stateprov=CA&ecom_billto_postal_postalcode=92234&ecom_payment_check_account_type=C&ecom_payment_check_account=1234567891&ecom_payment_check_trn=111000614&pg_avs_method=00000&endofdata&";

// output url - i.e. the absolute url to the paymentsgateway.net script
$output_url = "https://www.paymentsgateway.net/cgi-bin/posttest.pl";
// Uncomment below for live
//$output_url = "https://www.paymentsgateway.net/cgi-bin/postauth.pl";

// start output buffer to catch curl return data
ob_start();

	// setup curl
		$ch = curl_init ($output_url);
	// set curl to use verbose output
		curl_setopt ($ch, CURLOPT_VERBOSE, 1);
	// set curl to use HTTP POST
		curl_setopt ($ch, CURLOPT_POST, 1);
	// set POST output
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $output_transaction);
	//execute curl and return result to STDOUT
		curl_exec ($ch);
	//close curl connection
		curl_close ($ch);

// set variable eq to output buffer
$process_result = ob_get_contents();

// close and clean output buffer
ob_end_clean();

// clean response data of whitespace, convert newline to ampersand for parse_str function and trim off endofdata
$clean_data = str_replace("\n","&",trim(str_replace("endofdata", "", trim($process_result))));

// parse the string into variablename=variabledata
parse_str($clean_data);
		
// output some of the variables
echo "Response Type = ".$pg_response_type."<br />"; 
echo "Response Code = ".$pg_response_code."<br />"; 
echo "Response Description = ".$pg_response_description."<br />"; 

?>
