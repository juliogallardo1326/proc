<?
function Process_Transaction_ApproveDecline($trans)
{
	/*
		//all these fields should be present in the POST
	$postdatafields = array(
	"ccscanid","PROT","HOST","Ecom_SchemaVersion","REQTYPE","SALETYPE","TRANSNUMBER","CHECK","ACCOUNT","DESTACCOUNT",
	"SUBSCRIPTIONID","HPGUID","HELLOPAGE","LANGUAGE","ENCODING","ORGREFERER","ADDRESS2","CCEMAIL1", "CCEMAIL2",
	"REVSHARERID", "Ecom_BillTo_Telecom_Phone_Number","REDISPLAYONUSERERROR","NEWSTYLE","EVENTCODE","EVENTDESCRIPTOR",
	"LastDigits","ENTITYID","VERSION","THECOUNTRY","SystemLanguage","WASBEG","TICKETTOKENDATA","CREFNUM","USERSTATE",
	"USERCOUNTRY","REBILLDATEEXT","SENDRECEIPT","CLIENT_PC_CLOCK","MCPOST","NEWLANGUAGE","Ecom_BillTo_Postal_Name_First",
	"Ecom_BillTo_Postal_Name_Last","Ecom_BillTo_Postal_Street_Line1","Ecom_BillTo_Postal_CountryCode","Ecom_BillTo_Postal_City",
	"Ecom_BillTo_Postal_StateProv","Ecom_BillTo_Postal_PostalCode","Ecom_ReceiptTo_Online_Email","checkdda","bcrtnum",
	"Ecom_IAgree","SUBMIT","token","trans_id","subscription_id","Ecom_BillTo_Postal_Street_Line2","amount","APPROVED",
	"AVSRESPONSECODE","FSRESPONSECODE","RESPONSECODE","SubOption","CCSUBS","CSUBS","W9SUBS","RETURN","SMID","SOURCEAPP",
	"PROOFOFPURCHASE"
		);
	
		//assemble an associative array with all the required field defaulted to empty
		$postdatavals = array();
		$m = sizeof($postdatafields);
		for($j=0;$j<$m;$j++)
			$postdatavals[$postdatafields[$j]] = NULL;
		
		//start filling in the data from the transaction
	*/
	
	$postdatavals['PROT'] = stristr($trans['cs_notify_url'],"https") ? "HTTPS:" : "HTTP:";
	$postdatavals['HOST'] = $_SERVER['HTTP_HOST'];
	$postdatavals['Ecom_SchemaVersion'] = "http://www.ietf.org/rfc/rfc3106.txt";
	$postdatavals['REQTYPE'] = "AUTHORIZE";
	$postdatavals['ORGREFERER'] = $_SERVER['HTTP_REFERER'];
	$postdatavals['Ecom_BillTo_Phone_Number'] = $trans['phonenumber'];
	$postdatavals['TRANSNUMBER'] = $trans['reference_number'];
	$postdatavals['HELLOPAGE'] = "Default";
	$postdatavals['LANGUAGE'] = "1";
	$postdatavals['ENCODING'] = "iso8859-1";
	$postdatavals['Econm_BillTo_Telecom_Phone_Number'] = "NOT ASKED";
	$postdatavals['REDISPLAYONUSERERROR'] = "YES";
	$postdatavals['NEWSTYLE'] = "YES";
	$postdatavals['THECOUNTRY'] = "US";
	$postdatavals['SystemLanguage'] = "en-us";
	$postdatavals['WASBEG'] = "0";
	$postdatavals['USERSTATE'] = $trans['state'];
	$postdatavals['USERCOUNTRY'] = $trans['country'];
	$postdatavals['CLIENT_PC_CLOCK'] = $trans['transactionDate'];
	$postdatavals['SENDRECEIPT'] = "1";
	$postdatavals['NEWLANGUAGE'] = "1";
	$postdatavals['Ecom_BillTo_Postal_Name_First'] = $trans['name'];
	$postdatavals['Ecom_BillTo_Postal_Name_Last'] = $trans['surname'];
	$postdatavals['Ecom_BillTo_Postal_Street_Line1'] = $trans['address'];
	$postdatavals['Ecom_BillTo_Postal_Street_Line2'] = "";
	$postdatavals['Ecom_BillTo_Postal_CountryCode'] = $trans['country'];
	$postdatavals['Ecom_BillTo_Postal_City'] = $trans['city'];
	$postdatavals['Ecom_BillTo_Postal_StateProv'] = $trans['state'];
	$postdatavals['Ecom_BillTo_Postal_PostalCode'] = $trans['zipcode'];
	$postdatavals['Ecom_ReceiptTo_Online_Email'] = $trans['email'];
	$postdatavals['trans_id'] = $trans['reference_number'];
	$postdatavals['subscription_id'] = $trans['td_subscription_id'];
	$postdatavals['SUBSCRIPTIONID'] = $trans['td_subscription_id'];
	$postdatavals['IPADDRESS'] = $trans['ipaddress'];
	$postdatavals['amount'] = $trans['amount'];
	$postdatavals['RETURN'] = $trans['return_url'];
	$postdatavals['Ecom_IAgree'] = "on";
	$postdatavals['REBILLDATEEXT'] = $trans['td_recur_next_date'];
	$postdatavals['APPROVED'] = ($trans['status'] == 'A' ? "1" : "0");
	$postdatavals['PENDING'] = ($trans['status'] == 'P' ? "1" : "0");
	$postdatavals['REBILL'] = ($trans['td_is_a_rebill'] ? "1" : "0");
	$postdatavals['AVSRESPONSECODE'] = "NONE";
	$postdatavals['FSRESPONSECODE'] = "NONE";
	$postdatavals['RESPONSECODE'] = "?";
	$postdatavals['ACCOUNT'] = $trans['cs_reference_id'];
	$postdatavals['SUBMIT'] = "Secure Purchase";
	$postdatavals['REDISPLAYONUSERERROR'] = "YES";
	$postdatavals['NEWSTYLE'] = "YES";
	$postdatavals['VERSION'] = "1";
	$postdatavals['WASBEG'] = "0";
	$postdatavals['CREFNUM'] = "0";
	$postdatavals['REQTYPE'] = "AUTHORIZE";
	$postdatavals['ACCOUNT'] = $trans['cs_reference_id'];
	
//	$postdatavals[''] = "";
//	$postdatavals[''] = "";
//	$postdatavals[''] = "";
//	$postdatavals[''] = "";
//	$postdatavals[''] = "";

	// assemble our POST data from the associative array
	//$postfield = "";
	//foreach($postdatavals as $key => $val)
		//$postfield .= ($postfield=="" ? "" : "&") . "$key=" . urlencode($val);

	//return the URL string
	return $postdatavals;	
}

function Process_Transaction_Rebill($trans)
{
	$postdata = array();
	$postdata['event_id']=2;
	$postdata['event_desc']="REBILL";
	$postdata['trans_id'] = ($trans['reference_number']);
	$postdata['subscription_id'] = ($trans['td_subscription_id']);
	$postdata['amount'] = ($trans['amount']);
	$postdata['date']=( $trans['transactionDate']);
	$postdata['account'] = ($trans['cs_reference_id'] . $trans['td_rebillingID']);
	$postdata['response_code'] = ("?");
	$postdata['approved'] = ("1");
	
	return $postdata;
}

function Process_Transaction_Refund($trans)
{
	$postdata = array();
	$postdata['event_id']=8;
	$postdata['event_desc'] = "REFUND";
	$postdata['trans_id'] = ($trans['reference_number']);
	$postdata['subscription_id'] = ($trans['td_subscription_id']);
	$postdata['amount'] = ($trans['amount']);
	$postdata['date']=( $trans['transactionDate']);
	$postdata['account'] = ($trans['cs_reference_id'] . $trans['td_rebillingID']);
	$postdata['response_code'] = ("?");
	$postdata['approved'] = ("1");
	
	return $postdata;
}

function Process_Transaction_Chargeback($trans)
{
	$postdata = array();
	$postdata['event_id']=16;
	$postdata['event_desc']="CHARGEBACK";
	$postdata['subscription_id'] = ($trans['td_subscription_id']);
	$postdata['amount'] = ($trans['amount']);
	$postdata['date'] = ( $trans['transactionDate']);
	$postdata['sale_date']=( $trans['transactionDate']);
	$postdata['sale_trans_id'] = ($trans['reference_number']);
	$postdata['account'] = ($trans['cs_reference_id'] . $trans['td_rebillingID']);
	
	return $postdata;
}

function Process_Transaction_Cancellation($trans)
{
	$postdata = array();
	$postdata['event_id']=4;
	$postdata['event_desc']="CANCELLATION";
	$postdata['subscription_id'] = ($trans['td_subscription_id']);
	$postdata['date'] = ( $trans['transactionDate']);
	$postdata['expiration_date']=( $trans['transactionDate']);
	$postdata['account'] = ($trans['cs_reference_id'] . $trans['td_rebillingID']);
	
	return $postdata;
}

function Process_Transaction_Expiration($trans)
{
	$postdata = array();
	$postdata['event_id']=128;
	$postdata['event_desc']="EXPIRATION";
	$postdata['subscription_id'] = ($trans['td_subscription_id']);
	$postdata['date'] = ( $trans['transactionDate']);
	$postdata['account'] = ($trans['cs_reference_id'] . $trans['td_rebillingID']);
	
	return $postdata;
}

function Process_Transaction_Revoke($trans)
{
	$postdata = array();
	$postdata['event_id']=32;
	$postdata['event_desc']="REVOKE";
	$postdata['trans_id'] = ($trans['reference_number']);
	$postdata['subscription_id'] = ($trans['td_subscription_id']);
	$postdata['amount'] = ($trans['amount']);
	$postdata['date'] = ( $trans['transactionDate']);
	$postdata['sale_date']=( $trans['transactionDate']);
	$postdata['sale_trans_id'] = ($trans['reference_number']);
	$postdata['account'] = ($trans['cs_reference_id'] . $trans['td_rebillingID']);
	
	return $postdata;
}

function Process_Transaction($id,$event="approve",$isonlytest = false,$idfield='reference_number')
{
	//approve - declined - rebill
	//refund
	//cancel
	//chargeback
	$trans = getTransactionInfo($id,$isonlytest,$idfield);
	if($trans == -1) 
	{
		toLog("error","system","Process_Transaction Invalid Transaction ID, $idfield=$id, Test=$isonlytest ",$id);
		return ;
	}
	
	$id = $trans['transactionId'];
	
	toLog("notify","system","Process_Transaction Found Transaction ID, ReferenceId=".$trans['reference_number'].", Test=$isonlytest, Site ID:".$trans['cs_ID']."  ",$id);
	
	$password_management_action = NULL;
	$notify_action = NULL;

	if(!strcasecmp($event,"approve")) 
	{
		
		$password_management_action = "add";
		if(strcasecmp($trans['cs_notify_type'],"both") == 0 || strcasecmp($trans['cs_notify_type'],"approve only") == 0)
			$notify_action = 1;
	}
	else
	if(!strcasecmp($event,"decline"))
	{
		
		if(strcasecmp($trans['cs_notify_type'],"both") == 0 || strcasecmp($trans['cs_notify_type'],"decline only") == 0)
			$notify_action = 1;
	}
	else
	if(!strcasecmp($event,"rebill"))
	{
		if(($trans['cs_notify_event'] & 1) == 1) $notify_action = 2;
	}
	else
	if(!strcasecmp($event,"refund")) 
	{
		$password_management_action = "delete";
		if(($trans['cs_notify_event'] & 8) == 8) $notify_action = 8;
	}
	else
	if(!strcasecmp($event,"cancellation")) 
	{
		if(($trans['cs_notify_event'] & 4) == 4)  $notify_action = 4;
	}
	else
	if(!strcasecmp($event,"chargeback"))
	{
		$password_management_action = "delete";
		if(($trans['cs_notify_event'] & 16) == 16)	$notify_action = 16;
	}
	else
	if(!strcasecmp($event,"revoke")) 
	{
		$password_management_action = "delete";
		if(($trans['cs_notify_event'] & 32) == 32)	$notify_action = 32;
	}
	else
	if(!strcasecmp($event,"expiration")) 
	{
		$password_management_action = "delete";
		if(($trans['cs_notify_event'] & 128) == 128) $notify_action = 128;			
	}
	else 
	{
		toLog("erroralert","system","Process_Transaction FAIL: $event",$id);
		return 0;
	}


	$m = isset($trans['cs_notify_retry']) && $trans['cs_notify_retry'] > -1 ? $trans['cs_notify_retry'] : "0";
	$posturl = $trans['cs_notify_url'];
	$done = 0;
	$succ = array("url"=>$posturl,"head"=>"Post Notification Not Enabled","body"=>"Post Notification Not Enabled");

	toLog("notify","system","Process_Transaction PARAMETERS Notify:$notify_action Post URL:$posturl PassManage:$password_management_action Trans:$id Event:$event",$id);

	if($notify_action)
	{
		$postdata = array();
		
		switch($notify_action)
		{
			case 1: //approve decline
				$postdata = Process_Transaction_ApproveDecline($trans);
				$posturl = $trans['cs_notify_url'];
				break;
			case 2: //rebill
				$postdata = Process_Transaction_Rebill($trans);
				$posturl = $trans['cs_notify_eventurl'];
				break;
			case 8: //refund
				$postdata = Process_Transaction_Refund($trans);
				$posturl = $trans['cs_notify_eventurl'];
				break;
			case 4: //cancel
				$postdata = Process_Transaction_Cancellation($trans);
				$posturl = $trans['cs_notify_eventurl'];
				break;
			case 16: //chargeback
				$postdata = Process_Transaction_Chargeback($trans);
				$posturl = $trans['cs_notify_eventurl'];
				break;
			case 32: //revoke
				$postdata = Process_Transaction_Revoke($trans);
				$posturl = $trans['cs_notify_eventurl'];
				break;
			case 128: //expiration
				$postdata = Process_Transaction_Expiration($trans);
				$posturl = $trans['cs_notify_eventurl'];
				break;
			default: //error
			break;
		}

		if($trans['td_merchant_fields'])
		{
			$merchantvars = unserialize($trans['td_merchant_fields']);
			foreach($merchantvars as $key => $val)
				if(!isset($postdata[$key])) $postdata[$key] = ($val);
		}
	
	
		$postdata['USERNAME'] = $trans['td_username'];
		$postdata['PASSWORD'] = $trans['td_password'];
		$postdata['FIRSTNAME'] = $trans['name'];
		$postdata['LASTNAME'] = $trans['surname'];
		$postdata['PASSWORD'] = $trans['td_password'];
		$postdata['ADDRESS'] = $trans['address'];
		$postdata['COUNTRY'] = $trans['country'];
		$postdata['CITY'] = $trans['city'];
		$postdata['STATE'] = $trans['state'];
		$postdata['ZIPCODE'] = $trans['zipcode'];
		$postdata['PHONENUMBER'] = $trans['phonenumber'];
		$postdata['EMAIL'] = $trans['email'];
		$postdata['TRANSACTIONDATE'] = $trans['transactionDate'];
		
		$postquery = "";
		foreach($postdata as $key => $val)
			$postquery .= ($postquery=="" ? "" : "&") . "$key=" . urlencode($val);
	
		$parseurl =	parse_url($posturl);
		$postmet = $parseurl['scheme'];
		$postser = $parseurl['host'];
		$postport = stristr($postmet,"https") !== FALSE ? "443" : "80";
		$postser = (stristr($postmet,"https") !== FALSE ? "ssl://" : "") . $postser;

		$done = 0;


		if($isonlytest) $postquery .= "&testmode=true";

		//toLog("notify","system","Posting to: $posturl values: $postquery",$id);
		$succ['body'] = http_post2($postser,$postport,$posturl ,$postquery);
		$succ['url'] = $posturl;
		$succ['data'] = $postdata;

		//if($succ < 0)
			$done = 1; //could not connect to server
		//else
		//	if(stristr($succ['head'],"HTTP/1.1") === FALSE || stristr($succ['head'],"HTTP/1.0") === FALSE || stristr($succ['head'],"HTTP/1.1 200 OK")!==FALSE || stristr($succ['head'],"HTTP/1.0 200 OK")!==FALSE)
		//		$done = 1; //successfully posted data
		//	else							
		//		$done = 0; //problem with destination

		if($done != 1)
			toLog("notify","system","Notify Error: ".$succ['body'] . " $posturl with data $postquery",$id);
		else
			toLog("notify","system","Notify Success: ".$succ['body'] . " $posturl with data $postquery",$id);
	}
	
	$ret[0] = array("succeeded" => $done, "response" => $succ);
	
	if($password_management_action)
		$ret[1] = post_passwordmgmt($trans,$password_management_action,$id);
	else
		toLog("notify","system","Notify No Password Management: $posturl with data $postquery",$id);

	return $ret;
}

function post_passwordmgmt($trans,$password_management_action,$id)
{
	$fields['username'] = $trans['td_username'];
	$fields['password'] = $trans['td_password'];
	if($trans['td_product_id']) $fields['mt_product_id'] = preg_replace('/[^a-zA-Z0-9_]/','',$trans['subAcc']['td_product_id']);;
	$fields['groupaccess'] = preg_replace('/[^a-zA-Z0-9_]/','',$trans['subAcc']['rd_description']);
	if(!$fields['groupaccess']) $fields['groupaccess'] = preg_replace('/[^a-zA-Z0-9_]/','',$trans['subAcc']['rd_subName']);

	$fields['authpwd'] = $trans['cs_member_secret'];
	$fields['reqtype'] = $password_management_action;

	return(post_passwordmgmt_query($trans['cs_member_updateurl'],$fields,$id));
}

function post_passwordmgmt_query($url,$fields,$id)
{

	if($url != "")
	{
		$parseurl =	parse_url($url);
		$postmet = $parseurl['scheme'];
		$postser = $parseurl['host'];
		$postport = stristr($postmet,"https") !== FALSE ? "443" : "80";
		$postser = (stristr($postmet,"https") !== FALSE ? "ssl://" : "") . $postser;
		
		$postdata = NULL;
		foreach($fields as $key=>$data)
			$postdata .= ($postdata?"&":"")."$key=$data";
			
		
		$done = 0;
		$succ['query'] = $url . "?" . $postdata;
		$succ['body'] = http_post2($postser,$postport,$url . "?" . $postdata,$postdata);
		
		if(!$succ['body'])
			$done = 0; //could not connect to server
		else
			//if(stristr($succ['head'],"HTTP/1.1") === FALSE || stristr($succ['head'],"HTTP/1.0") === FALSE || stristr($succ['head'],"HTTP/1.1 200 OK")!==FALSE || stristr($succ['head'],"HTTP/1.0 200 OK")!==FALSE)
				$done = 1; //successfully posted data
			//else							
			//	$done = 0; //problem with destination
	
		if($done != 1)
			toLog("notify","system","Notify Error: ".$succ['body'] . " Query: ".$succ['query'],$id);
		else
			toLog("notify","system","Notify Success: ".$succ['body'] . " Query: ".$succ['query'],$id);

		return array("succeeded" => $done, "response" => $succ);
	}
	return array("succeeded" => "0", "response" => array("url"=>$posturl,"head" => "no url specified", "body"=>"no url specified"));
}

?>