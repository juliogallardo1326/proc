<?php
$transId = "54122";
$cardType = "visa";
$cardNumber = "4667066265038874";
$cardExpire = "12/04";
$cardCvv = "121";
$cardHolderName = "Abish";
$cardHolderSurName = "Asharaf";
$cardHolderStreet = "Kaloor";
$cardHolderHouseNumber ="GCDA Shopping Complex";
$cardHolderZip ="686537";
$cardHolderCity ="Cochin";
$cardHolderCountry ="IN";
$cardHolderState ="OT";
$cardHolderEmail ="abish.asharaf@zerone-projects.co.uk";
$paymentCurrency ="EUR";
$paymentAmount ="100";
$customerPhone="6574567456";


$xml_string = "<epxml>
	<header>
		<responsetype>direct</responsetype>
		<mid>67</mid>
		<password>yu54gh0</password>
		<type>refund</type>
	</header>
	<request>
		<refund>
			<etan>$transId</etan>
			<tan>104805</tan>
			<card>
				<cctype>$cardType</cctype>
				<cc>$cardNumber</cc>
				<expire>$cardExpire</expire>
				<cvv>$cardCvv</cvv>
			</card>
			<cardholder>
				<name>$cardHolderName</name>
				<surname>$cardHolderSurName</surname>
				<street>$cardHolderStreet</street>
				<housenumber>$cardHolderHouseNumber</housenumber>
				<zip>$cardHolderZip</zip>
				<city>$cardHolderCity</city>
				<country>$cardHolderCountry</country>
				<telephone>$customerPhone</telephone>
				<state>$cardHolderState</state>
				<email>$cardHolderEmail</email>
			</cardholder>
			<amount>
				<currency>$paymentCurrency</currency>
				<value>$paymentAmount</value>
			</amount>
		</refund>
	</request>
	</epxml>";
				
$return_value = func_volpaybank_integration_result($xml_string);
print $return_value ."<br><br>";

/*************************XML Return values*********************************/
	
	$data=$return_value;
	$Nodes = array();
	$count = 0;
	$pos = 0;
	
	// Goes throw XML file and creates an array of all <XML_TAG> tags.
	while ($node = GetElementByName($data, "<epxml>", "</epxml>")) {
	   $Nodes[$count] = $node;
	   $count++;
	   $data = substr($data, $pos);
	}
	
	// Gets infomation from tag siblings.
	
	for ($i=0; $i<$count; $i++) {
			$headerCode = GetElementByName($Nodes[$i], "<header>", "</header>");
			$responseDesc = GetElementByName($Nodes[$i], "<response>", "</response>");
	
			$responseType = GetElementByName($Nodes[$i], "<responsetype>", "</responsetype>");
			$merchantId = GetElementByName($Nodes[$i], "<mid>", "</mid>");
			$merchantType = GetElementByName($Nodes[$i], "<type>", "</type>");
	
			if(strpos($responseDesc,"error")) {
				$errorCode = GetElementByName($Nodes[$i], "<errorcode>", "</errorcode>");
				$errorMessage = GetElementByName($Nodes[$i], "<errormessage>", "</errormessage>");
				$transNo = GetElementByName($Nodes[$i], "<etan>", "</etan>");
				print $errorCode."-".$errorMessage;
			}
			
			if(strpos($responseDesc,"success")) {
				$successMessage = GetElementByName($Nodes[$i], "<message>", "</message>");
				$successNumber = GetElementByName($Nodes[$i], "<tan>", "</tan>");
				$transNo = GetElementByName($Nodes[$i], "<etan>", "</etan>");
				print $successNumber."-".$successMessage;
			}
	}
	
/*************************************************************************************/

// Extracts content from XML tag

function GetElementByName ($xml, $start, $end) {

   global $pos;
   $startpos = strpos($xml, $start);
   if ($startpos === false) {
       return false;
   }
   $endpos = strpos($xml, $end);
   $endpos = $endpos+strlen($end);   
   $pos = $endpos;
   $endpos = $endpos-$startpos;
   $endpos = $endpos - strlen($end);
   $tag = substr ($xml, $startpos, $endpos);
   $tag = substr ($tag, strlen($start));

   return $tag;

}


/************************Volpay Integration Process ***********************************/

function func_volpaybank_integration_result($xml_value) 
{
	// Uncomment below for live
	$output_url = "http://62.209.40.97/supermaxxx/gateway_v2.php";
	// start output buffer to catch curl return data
	ob_start();

	// setup curl
		$ch = curl_init ($output_url);
	// set curl to use verbose output
		curl_setopt ($ch, CURLOPT_VERBOSE, 1);
	// set curl to use HTTP POST
		curl_setopt ($ch, CURLOPT_POST, 1);
	// set POST output
		curl_setopt ($ch, CURLOPT_POSTFIELDS,"xml=$xml_value");
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
	return($process_result);
//	print $process_result;
}		
?>
