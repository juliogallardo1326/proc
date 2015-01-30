<?
class fraud_class
{
	var $bl_types;
	var $wl_actions;
	var $wl_types;
	
	
	function fraud_class()
	{
		$this->bl_types['CCnumber'] = 'Credit Card Number';
		$this->bl_types['phonenumber'] = 'Phone Number';
		$this->bl_types['email'] = 'Email Address';
		$this->bl_types['address'] = 'Billing Address';
		$this->bl_types['country'] = 'Country (ISO)';
		$this->bl_types['city'] = 'City';
		$this->bl_types['state'] = 'State';
		$this->bl_types['name'] = 'First Name';
		$this->bl_types['surname'] = 'Last Name';
		$this->bl_types['ipaddress'] = 'IP Address';
		$this->bl_types['userId'] = 'Company ID';
		$this->wl_actions['banfull'] = 'Full Ban on Customer';
		$this->wl_actions['bancard'] = 'Ban Customer Card';
		$this->wl_actions['banip'] = 'Ban Ip Address';
		$this->wl_actions['banemail'] = 'Ban Email Address';
		$this->wl_actions['delayrebill15'] = 'Delay Rebill for 15 days';
		$this->wl_actions['delayrebill30'] = 'Delay Rebill for 30 days';
		$this->wl_actions['erroralertonrebill'] = 'Error Alert if Rebill';
		$this->wl_types['td_process_msg'] = 'Decline Message';
	}
	
	function commit_watchaction($data,&$transInfo)
	{
		switch($data['wl_action'])
		{
			case 'banfull':
				$this->update_banlist(array('CCnumber' => $transInfo['CCnumber']));
				$this->update_banlist(array('email' => $transInfo['email']));
				$this->update_banlist(array('address' => $transInfo['address']));
				//$this->update_banlist(array('name' => $transInfo['name'],'surname' => $transInfo['surname']));
				$this->update_banlist(array('ipaddress' => $transInfo['ipaddress']));
				$this->update_banlist(array('phonenumber' => $transInfo['phonenumber']));
				$this->update_banlist(array('CCnumber' => $transInfo['CCnumber']));
				break;
			case 'bancard':
				$this->update_banlist(array('CCnumber' => $transInfo['CCnumber']));
				break;
			case 'banip':
				$this->update_banlist(array('ipaddress' => $transInfo['ipaddress']));
				break;
			case 'banemail':
				$this->update_banlist(array('email' => $transInfo['email']));
				break;
			case 'delayrebill15':
				$transInfo['ss_rebill_delay'] = 15;
				break;
			case 'delayrebill30':
				$transInfo['ss_rebill_delay'] = 30;
				break;
			case 'erroralertonrebill':			
				toLog('erroralert','customer', "Alert Watch Found For '".$transInfo['reference_number']."' Watch Info: ".print_r($data,true).", Transaction Info: ".print_r($transInfo,true),$transInfo['transactionId']);
				break;
		
		}
	
	}
	
	function update_watchlist($data)
	{
		$wl_ID = $data['wl_ID'];
		$wl_data = $data['wl_data'];
		$wl_type = $data['wl_type'];
		$wl_action = $data['wl_action'];
		if($wl_ID<1)
		{
			$sql = "select wl1.wl_ID+1 as nextAvailable from `cs_watchlist` as wl1 left join `cs_watchlist` as wl2 on wl1.wl_ID = wl2.wl_ID-1 where wl2.wl_ID is null limit 1";
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			$wl_ID = @mysql_result($result,0,0);
			if($wl_ID<1)$wl_ID=1;
		}
		$sql = "Replace into cs_watchlist set wl_data = '$wl_data', wl_type = '$wl_type', wl_action = '$wl_action'";
		if($wl_ID) $sql .= ", wl_ID = '$wl_ID'";
		if($this->wl_actions[$wl_action] && $wl_data && $this->wl_types[$wl_type])
			sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		$data['wl_ID'] = $wl_ID;
		return $data;
	}
	
	function check_watchlist(&$transInfo)
	{
		$watch_sql = " 0 ";
		foreach($this->wl_types as $key=>$name)
			if($transInfo[$key]) $watch_sql .= " OR (wl_type='$key' AND '".quote_smart($transInfo[$key])."' LIKE wl_data) \n";
	
		$sql = "
				SELECT
					wl.*
				FROM
					`cs_watchlist` as wl
				WHERE
					$watch_sql
				";
		$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
		$watchs = array('watchsfound'=>intval(mysql_num_rows($result)));
		$watchText=$watchs['watchsfound']." Watch(s) Found: \n";
		while($data = mysql_fetch_assoc($result))
		{
			$watchText.="  Watch #".$data['wl_ID']." found ".$this->wl_types[$data['wl_type']]." (".$data['wl_data'].")='".$transInfo[$data['wl_type']]."' and is taking action ".$this->wl_actions[$data['wl_action']].".\n";
			$data['source_text'] = $transInfo[$data['wl_type']];
			$watchs[$data['wl_ID']] = $data;
			$this->commit_watchaction($data,&$transInfo);
			if($transInfo['ss_rebill_delay']) $watchs['ss_rebill_delay'] = $transInfo['ss_rebill_delay'];
		}
		$watchs['watchText'] = $watchText;
		$transInfo['watchInfo'] = $watchs;
		//if($watchs['watchsfound']) 
		//	toLog('erroralert','customer', "Watch Found For '".$transInfo['reference_number']."' Watch Info: ".print_r($watchs,true),$transInfo['transactionId']);
		return ($watchs);
	}
	
	
	function update_banlist($data,$bl_group=0)
	{
		if($bl_group) 
		{
			$sql = "Delete from cs_banlist where bl_group = '$bl_group'";
			sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		}
		else
		{
			//$sql = "select bl1.bl_group+1 as nextAvailable from `cs_banlist` as bl1 left join `cs_banlist` as bl2 on bl1.bl_group = bl2.bl_group-1 where bl2.bl_group is null limit 1";
			$sql = "select max(bl_group)+1 as nextAvailable from `cs_banlist`";
			
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			if(mysql_num_rows($result)) $bl_group = @mysql_result($result,0,0);
			if($bl_group<1)$bl_group=1;
		}
		$cnt=0;
		foreach( $data as $bl_type => $bl_data)
			if($this->bl_types[$bl_type] && $bl_data) $cnt++;
		if($cnt==sizeof($data))
		{
			foreach( $data as $bl_type => $bl_data)
			{
				$sql = "Insert into cs_banlist set bl_group = '$bl_group', bl_type = '$bl_type', bl_data = '$bl_data'";
				if($this->bl_types[$bl_type] && $bl_data)
					sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
			}
		}
		return array('bl_group'=>$bl_group);
	}
	
	function check_banlist(&$transInfo, $viewonly = false)
	{
		$ban_sql = " 0 ";
		foreach($this->bl_types as $key=>$name)
		{
			$val = quote_smart($transInfo[$key]);
			if(in_array($key,array('CCnumber','bankaccountnumber','bankroutingnumber')) && !is_numeric($val)) $val = preg_replace("/[^0-9]/",'',etelDec($val));
			if($transInfo[$key]) $ban_sql .= " OR (bl_type='$key' AND '$val' LIKE bl_data) \n";
		}
		$sql = "
			select
				sum(ban) as bansfound ,
				group_concat(if(ban,banInfo,NULL)) as banInfo
			from (
					SELECT
						count(bl_ID) = sum($ban_sql) as ban,
						concat('bl_group=',`bl_group`,'&',group_concat(`bl_type`,'=',`bl_data` SEPARATOR  '&')) as banInfo
					FROM
						`cs_banlist`
					group by
						`bl_group`
					) as bans
				";
		$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
		$bans = mysql_fetch_assoc($result);
		$banText=intval($bans['bansfound'])." Ban(s) Found. \n";
		$banarray = explode(",",$bans['banInfo']);
		$bans['sql'] = $sql;
		foreach($banarray as $data)
		{
			if(!$data) continue;
			parse_str($data,$data);
			if(!$data['bl_group']) continue;
			$banText.="  Ban ".$data['bl_group']." found ";
			unset($data['bl_group']);
			foreach($data as $bl_type => $bl_data)
				$banText.=$this->bl_types[$bl_type]."='".$bl_data."' and ";
			$banText = substr($banText,0,-5);
			$banText.="\n";
		}
		$bans['banText'] = $banText;
		$transInfo['banInfo'] = $bans;
		if($bans['bansfound'] && !$viewonly) 
			toLog('misc','customer', "Ban List Info For '".$transInfo['reference_number']."' Ban Info: ".print_r($bans,true),$transInfo['transactionId']);
		return ($bans);
	}
	
	function check_previous_24h_approve(&$transInfo,$hours=24)
	{
		$check = "`CCnumber` = '".etelEnc($transInfo['CCnumber'])."'";
		if($transInfo['checkorcard'] == 'C' && $transInfo['bankroutingcode'] && $transInfo['bankaccountnumber']) 
			$check = "(`bankroutingcode` = '".etelEnc($transInfo['bankroutingcode'])."' and `bankaccountnumber` = '".etelEnc($transInfo['bankaccountnumber'])."')";
		//if($transInfo['td_is_a_rebill']) return 0 ;
		$hours = intval($hours);
		if($hours==0) return 0 ;
		if($hours<=1) $hours=1;
		$sql="
			SELECT 
				transactionDate
			FROM 
				`cs_transactiondetails`
			WHERE 
				($check or `ipaddress` = '".$transInfo['ipaddress']."')
				AND (`status` = 'A' || (`status` = 'P' AND checkorcard='C')) 
				AND transactionDate > DATE_SUB(Now(),Interval $hours HOUR) 
				AND userId = '".$transInfo['userId']."'
			";
		$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
		$numrows = mysql_num_rows($result);
		//if($numrows > 0) toLog('erroralert','customer',$hours." ~ ".$sql);
		return ($numrows > 0);
	
	}
	
	function check_previous_decline(&$transInfo,$hours=24)
	{
		if($hours<1)$hours = 1;
		$check = "`CCnumber` = '".etelEnc($transInfo['CCnumber'])."'";
		if($transInfo['checkorcard'] == 'C' && $transInfo['bankroutingcode'] && $transInfo['bankaccountnumber']) 
			$check = "(`bankroutingcode` = '".etelEnc($transInfo['bankroutingcode'])."' and `bankaccountnumber` = '".etelEnc($transInfo['bankaccountnumber'])."')";
	
		if($transInfo['td_is_a_rebill']) return 0 ;
		$sql="
			SELECT 
				*
			FROM 
				`cs_transactiondetails`
			WHERE 
				($check  or `ipaddress` = '".$transInfo['ipaddress']."')
				AND transactionDate > DATE_SUB(Now(),Interval $hours HOUR)
				AND (`status` != 'A' or `cancelstatus` = 'Y' or `td_is_chargeback`=1) 
				AND (`td_bank_recieved` = 'yes' or `td_bank_recieved` = 'fraudscrubbing')
			";
			
		$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
		$numrows = mysql_num_rows($result);
		return ($numrows >= 3);
	}
	
	function check_unique($table,&$transInfo)
	{
		$check = "`CCnumber` = '".etelEnc($transInfo['CCnumber'])."'";
		if($transInfo['checkorcard'] == 'C') 
			$check = "(`bankroutingcode` = '".etelEnc($transInfo['bankroutingcode'])."' and `bankaccountnumber` = '".etelEnc($transInfo['bankaccountnumber'])."')";
	
		$sql="
		SELECT 
			least(
				(
					SELECT min(transactionId)
						FROM `$table`
						WHERE 
						$check
				) , 
				(
				
					SELECT min(transactionId)
						FROM `cs_transactiondetails`
						WHERE 
						`ipaddress` = '".$transInfo['ipaddress']."'
				) 
			) AS num 
		";
			
		$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<pre>$sql</pre>");
		$trans = mysql_result($result,0,0);
		if($trans == $transInfo['transactionId']) $trans = 0;
		return $trans;
	}
	
		
	function execute_scrub_tests(&$transInfo,$bankInfo,$companyInfo)
	{
		global $etel_fraud_limit;
		global $etel_fraud_response;
		global $etel_disable_fraud_scrubbing;
	
		$etel_fraud_response=NULL;
	
		if($transInfo['td_is_a_rebill']) return -1;
		if($etel_disable_fraud_scrubbing) return -1;
		if($companyInfo['cd_orderpage_disable_fraud_checks']) return -1;
	
		$response['errormsg'] = "No Error (FS)";
	
		$bans = $this->check_banlist($transInfo);
		if($bans['bansfound'] > 0)
		{
			$response['errormsg'] = "Charge Declined. ";
			$response['td_process_msg']=intval($bans['bansfound'])." Ban(s) Found. Transaction Denied";
			$response['success'] = true;
			$response['td_process_result']=$bans['banInfo'];
			$response['td_process_query']="Checking Ban List";
			$response['status'] = "D";
			$response['td_bank_recieved'] = 'banlist';
	
			return $response;
		}
	
		if($this->check_previous_decline(&$transInfo,$companyInfo['cd_approve_timelimit']))
		{
			$response['td_process_msg'] = "Charge Declined. (Decline Limit Reached)";
			$response['errormsg'] = "Charge Declined.";
			$response['success'] = true;
			$response['td_process_result']="Charge Previously Declined in the last ".$companyInfo['cd_approve_timelimit']." hours. Will not try again.";
			$response['td_process_query']="Checking for Previous Declines";
			$response['status'] = "D";
			$response['td_bank_recieved'] = 'previousdecline';
	
			return $response;
		}
	
		if($etel_fraud_limit>0)	$transInfo['td_fraud_score'] = $this->fraud_scrub($transInfo,$bankInfo,$companyInfo);
		if($transInfo['td_fraud_score']>$etel_fraud_limit)
		{
			$response['td_process_result']="Fraud Score: ".$transInfo['td_fraud_score']." Response: $etel_fraud_response";
			$response['td_process_query']="Fraud Scrubbing...";
			$response['td_bank_transaction_id']="";
			$response['status'] = "D";
			$response['success'] = true;
			$response['errormsg'] = "Charge Declined.";
			$response['td_process_msg'] = "Charge Declined. (Fraudscrubbing (".floatval($transInfo['td_fraud_score'])."))";
			$response['td_bank_recieved'] = 'fraudscrubbing';
	
			return $response;
		}
		return -1;
	
	}
		
	function fraud_scrub(&$transInfo,&$bankInfo,&$companyInfo)
	{
		if($transInfo['cardtype'] != 'Visa' && $transInfo['cardtype'] != 'Mastercard') return 0; 
		
		global $etel_fraud_response;
		require_once('fraud/CreditCardFraudDetection.php');
		$ccfs = new CreditCardFraudDetection;
		// Set inputs and store them in a hash
		// See http://www.maxmind.com/app/ccv for more details on the input fields
	
		// Enter your license key here (non registered users limited to 20 lookups per day)
		 $h["license_key"] = "UHccvlc5aVqk";
	
		// Required fields
	
		$h["i"] = $transInfo['ipaddress'];             // set the client ip address
		$h["city"] = $transInfo['city'];             // set the billing city
		$h["region"] = $transInfo['state'];                 // set the billing state
		$h["postal"] = $transInfo['zipcode'];              // set the billing zip code
		$h["country"] = $transInfo['country'];                // set the billing country
	
		// Recommended fields
		$h["domain"] = substr(strstr($transInfo['email'], '@'),1);		// Email domain
		$h["bin"] = substr($transInfo['CCnumber'],0,6);			// bank identification number
		$h["forwardedIP"] = $transInfo['ipaddress'];	// X-Forwarded-For or Client-IP HTTP Header
		$h["custPhone"] = substr($transInfo['phonenumber'],0,3)."-".substr($transInfo['phonenumber'],4,6);		// Area-code and local prefix of customer phone number
	
		// Optional fields
		//$h["binName"] = "MBNA America Bank";	// bank name
		$h["binPhone"] = $transInfo['td_bank_number'];	// bank customer service phone number on back of credit card
		$h["requested_type"] = "premium";	// Which level (free, city, premium) of CCFD to use
		$h["emailMD5"] = md5(strtolower($transInfo['email'])); // CreditCardFraudDetection.php will take
		// MD5 hash of e-mail address passed to emailMD5 if it detects '@' in the string
		$h["shipAddr"] = $transInfo['address'];	// Shipping Address
		//$h["txnID"] = "1234";			// Transaction ID
		$h["sessionID"] = session_id();		// Session ID
		// If you want to disable Secure HTTPS or don't have Curl and OpenSSL installed
		// uncomment the next line
		// $ccfs->isSecure = 0;
	
		//set the time out to be five seconds
		$ccfs->timeout = 5;
	
		//uncomment to turn on debugging
		$ccfs->debug = 0;
	
		//next we pass the input hash to the server
		$ccfs->input($h);
	
		//then we query the server
		$ccfs->query();
	
		//then we get the result from the server
		$ho = $ccfs->output();
		//then finally we print out the result
		$outputkeys = array_keys($ho);
		$numoutputkeys = count($ho);
		$noCity=0;
		for ($i = 0; $i < $numoutputkeys; $i++) {
		  $key = $outputkeys[$i];
		  $value = $ho[$key];
		  $tolog.= $key . " = " . $value . "\n";
		  if($key == 'err' && $value == 'CITY_NOT_FOUND')
		  {
			//toLog('erroralert','customer', "Fraud Scrubbing Can't find City '".$h["city"]."' ".serialize($h)." ".$tolog);
			$noCity=1;
		  }
		}
		toLog('order','customer', "Fraud Scrubbing Result for ".$transInfo['reference_number'].": ".$tolog,$transInfo['transactionId']);
		$etel_fraud_response=$tolog;
		return floatval($ho['score']-$noCity*2.60);
	}

}
?>