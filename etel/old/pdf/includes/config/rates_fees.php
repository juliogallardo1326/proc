<?

class rates_fees
{
	var $access_mode;
	var $allowed_banks;

	function rates_fees($access_mode='1')
	{
		global $adminInfo;

		$this->allowed_banks = NULL;

		if(isset($adminInfo['li_level']))
			if($adminInfo['li_level'] != 'full')
				if($adminInfo['li_bank'] != -1)
					$this->allowed_banks = explode(",",$adminInfo['li_bank']);
	}

	function array_print($var)
	{
			echo "<table width='100%'><tr><td>";
			echo "<pre>";
			print_r($var);
			echo "</pre>";
			echo "</td></tr></table>";
	}

	function update_TransactionRates($merchant_id,$transInfo,$trans_type="cc",$mode="Test")
	{
		$company_rates = $this->get_MerchantRates($merchant_id);

		$trans_bank_id = $transInfo['bank_id'];
		$bank_rates = NULL;

		foreach($company_rates as $bank_name=>$bank_info)
			if($bank_info['bank_id'] == $trans_bank_id)
				$bank_rates = $bank_info['default'];

		if($bank_rates != NULL)
		{
			$sql="
				SELECT
					transaction_type,
					bk_fee_high_risk
				FROM
					cs_companydetails as cd
				LEFT JOIN cs_bank AS bk ON bank_id = '".$transInfo['bank_id']."'
				WHERE
					userId = $merchant_id
				";
			$result = sql_query_read($sql) or dieLog(mysql_error() . "<br>" . $sql);
			$high_risk = mysql_fetch_assoc($result);

			if($transInfo['status']=='A')
				$rates['r_bank_trans_fee']=$bank_rates['Bank']['trans'];
			else
				$rates['r_bank_trans_fee']=$bank_rates['Bank']['decln'];

			if($high_risk["transaction_type"]=='game' || $high_risk["transaction_type"]=='adlt')
				$rates['r_bank_discount_rate']=$high_risk["bk_fee_high_risk"];
			else
				$rates['r_bank_discount_rate']=$bank_rates['Bank']['disct'];


			$rates['r_chargeback']=$bank_rates['Processor']['chgbk'];
			$rates['r_reserve']=$bank_rates['Processor']['rserv'];

			$rates['r_reseller_trans_fees']=$bank_rates['Reseller']['trans'];
			$rates['r_reseller_discount_rate']=$bank_rates['Reseller']['disct'];

			$rates['r_merchant_trans_fees']=$bank_rates['Processor']['trans'];
			$rates['r_merchant_discount_rate']=$bank_rates['Processor']['disct'];
		}

		$typeArray = array('cc' => 'creditcard', 'ch'=>'check', 'web'=>'web900');
		$cr_transtype = $typeArray[$trans_type];

		$sql = "
			SELECT
			*
			FROM
				`cs_company_rates`
			WHERE
				`cr_userId` = '$merchant_id'
				AND (`cr_transtype` = '$cr_transtype' OR `cr_transtype` = 'all')
			";
		$result=sql_query_read($sql);

		// Apply Custom Rates
		while($customRates = mysql_fetch_assoc($result))
		{
			switch ($customRates['cr_feetype'])
			{
				case "decline transaction fee":
					if($transInfo['status']=='D')
					{
						$rates['r_reseller_trans_fees']=$customRates['cr_reseller'];
						$rates['r_merchant_trans_fees']=$customRates['cr_merchant'];
					}
					break;
			}
		}

		$insert = "";
		if(isset($rates) && is_array($rates))
			foreach($rates as $index => $value)
				$transInfo[$index] = $value;

		$transInfo['status'] = "P";
		$transInfo['transactionDate'] = date("Y-m-d H:i:s",time());
		$transInfo['billingDate'] = date("Y-m-d H:i:s",time());

		return $transInfo;
	}

	function insert_TransactionWithRates($transInfo,$mode = "Test")
	{
		$int_table = "cs_test_transactiondetails";
		if ($mode == "Live") $int_table = "cs_transactiondetails";

		//$transInfo['userId'] = $merchant_id;

		$trans_fields = array(
		"name","surname","phonenumber","address",
		"ccnumber","cvv","checkorcard","country",
		"city","td_bank_number","state","zipcode",
		"amount","signature","bankname","bankroutingcode",
		"bankaccountnumber","accounttype","email",
		"cancelstatus","userid","checkto","cardtype",
		"checktype","validupto","ipaddress","productdescription",
		"reference_number","currencytype","r_reseller_discount_rate",
		"r_total_discount_rate","td_fraud_score","r_chargeback",
		"r_credit","r_transactionfee","r_reserve",
		"r_merchant_discount_rate","r_total_trans_fees",
		"r_reseller_trans_fees","r_discountrate",
		"r_merchant_trans_fees","cancel_refer_num",
		"return_url","from_url","bank_id","td_rebillingID",
		"td_is_a_rebill","td_enable_rebill","td_voided_check",
		"td_returned_checks","td_site_id","td_is_affiliate",
		"td_send_email","td_customer_fee","td_is_pending_check",
		"td_is_chargeback","td_recur_processed","td_recur_next_date",
		"td_username","td_password","td_product_id",
		"td_non_unique","td_merchant_fields","td_subscription_id",
		"r_bank_trans_fee","r_bank_discount_rate","r_chargeback,r_reserve",
		"r_reseller_trans_fees","r_reseller_discount_rate",
		"r_merchant_trans_fees","r_merchant_discount_rate",
		"transactiondate","billingdate"
		);

		//$this->array_print($transInfo);

		if(is_numeric($transInfo['CCnumber']))	$transInfo['CCnumber'] = etelEnc($transInfo['CCnumber']);
		if(is_numeric($transInfo['bankroutingcode']))	$transInfo['bankroutingcode'] = etelEnc($transInfo['bankroutingcode']);
		if(is_numeric($transInfo['bankaccountnumber'])) $transInfo['bankaccountnumber'] = etelEnc($transInfo['bankaccountnumber']);

		$insert = "";
		foreach($transInfo as $field => $value)
			if(in_array(strtolower($field),$trans_fields))
					$insert .= ($insert == "" ? "" : ", ") . $field . " = \"" . quote_smart($transInfo[$field]) . "\"\r\n";

		$sql = "
			INSERT INTO
				$int_table
			SET
				status = 'P',
				$insert
			;";
		sql_query_write($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>".$sql);
		$trans_id = mysql_insert_id();
		return $trans_id;
	}

	function get_Banks()
	{
		$sql = "SELECT * FROM cs_bank WHERE bk_hide=0";
		$res = sql_query_read($sql) or dieLog(mysql_error());
		$banks = array();
		while($r = mysql_fetch_assoc($res))
		if($this->allowed_banks == NULL || in_array($r['bank_id'],$this->allowed_banks))
		{
			$r['bk_defaults'] = unserialize(stripslashes($r['bk_defaults']));
			$banks[$r['bank_name']] = $r;
		}
		return $banks;
	}

	function get_BanksById()
	{
		$sql = "SELECT * FROM cs_bank WHERE bk_hide=0";
		$res = sql_query_read($sql) or dieLog(mysql_error());
		$banks = array();
		while($r = mysql_fetch_assoc($res))
		if($this->allowed_banks == NULL || in_array($r['bank_id'],$this->allowed_banks))
		{
			$r['bk_defaults'] = unserialize(stripslashes($r['bk_defaults']));
			$banks[$r['bank_id']] = $r;
		}
		return $banks;
	}

	function get_Payees()
	{
		$payees = array();
		$payees['prcs'] = array("title"=>"Processor","display"=>"");
		$payees['rsll'] = array("title"=>"Reseller","display"=>"none");
		$payees['bank'] = array("title"=>"Bank","display"=>"none");

		return $payees;
	}

	function get_RateCategories()
	{
		$cats = array(
			"Transaction"=>"trans",
			"Discount"=>"disct",
			"Decline"=>"decln",
			"Refund"=>"refnd",
			"Chargeback"=>"chgbk",
			"Reserve"=>"rserv",
			"Return"=>"retrn",
			"Cust.&nbsp;Serv."=>"cstsv"
			);
		return $cats;
	}

	function calc_Discount($Bank_disct,$Processor_disct,$Bank_trans,$Processor_trans)
	{
		if ($Bank_disct - $Processor_disct != 0)
		{
			$a = ($Processor_trans - $Bank_trans) / ($Bank_disct/100.0 - $Processor_disct/100.0);
			$b = ($Processor_trans - $Bank_trans + 1000) / ($Bank_disct/100.0 - $Processor_disct/100.0);
			$c = ($Processor_trans - $Bank_trans - 1000) / ($Bank_disct/100.0 - $Processor_disct/100.0);

			if($a == 0)
				if($b >= $c)
					$res = "$" . number_format($a,2);
				else
 					return "";

			if($a>0)
				$res = "$" . number_format($a,2);
			else
				if($b >= $c)
					$res = "-$" . number_format(-$a,2);
				else
					return "";


			return $b >= $c ? $res . "&nbsp;&uarr;" : $res . "&nbsp;&darr;";
		}
		$diff = $Processor_trans - $Bank_trans;
		if($diff > 0 || $diff == 0)
			return "";
		return "$" . number_format(-$diff,2);

	}

	function calc_Difference($Processor, $Bank, $Reseller = 0)
	{
		$a = $Processor - $Bank - $Reseller;
		if($a < 0)
			return "<font color='#FF0000'>-$" . number_format(-$a,2) . "</font>";
		return "";
	}

	function get_Calculations()
	{
		$calc = array(
			"trans"=>'$this->calc_Difference($Processor_trans, $Bank_trans)',
			"disct"=>'$this->calc_Discount($Bank_disct,$Processor_disct,$Bank_trans,$Processor_trans)',
			"decln"=>'$this->calc_Difference($Processor_decln, $Bank_decln)',
			"refnd"=>'$this->calc_Difference($Processor_refnd, $Bank_refnd)',
			"chgbk"=>'$this->calc_Difference($Processor_chgbk, $Bank_chgbk)',
			"rserv"=>'$this->calc_Difference($Processor_rserv, $Bank_rserv)',
			"retrn"=>'$this->calc_Difference($Processor_retrn, $Bank_retrn)',
			"cstsv"=>'$this->calc_Difference($Processor_cstsv, $Bank_cstsv)'
		);
		return $calc;
	}
	
	function calc_all_rates($settings)
	{
		$cat = $this->get_RateCategories();
		$results = array();
		
		foreach($cat as $title=>$abbr)
			$results[$abbr] = $this->calc_rates($settings,$abbr);
		return $results;
	}

	function calc_rates($settings,$calc)
	{
		$formulas = $this->get_Calculations();

		if(!isset($formulas[$calc]))
			return $undefined;

		foreach($settings as $actor => $info)
			foreach($info as $type => $value)
				${$actor . "_" . $type} = $value;

		eval('$fee = ' . $formulas[$calc] . ';');
		return $fee;
	}

	function post_MerchantRate($merchant_id,$settings)
	{
		// Dangerous

		$bank_ids = array();
		
		foreach($settings as $bank_name => $this_setting)
		if($this->allowed_banks == NULL || in_array($this_setting['bank_id'],$this->allowed_banks))
		{
			$bank_id = $this_setting['bank_id'];
			$bank_ids[] = $bank_id;
			
			unset($this_setting['bank_id']);
			$this_setting = quote_smart(serialize($this_setting));
			$sql = "INSERT INTO
						cs_company_banks
					SET
						cb_config = '$this_setting',
						userId = '$merchant_id',
						bank_id = '$bank_id'
					ON DUPLICATE KEY UPDATE
						cb_config = '$this_setting';
				";
			$res = sql_query_write($sql) or dieLog(mysql_error());
		}

		$bank_ids = implode(",",$bank_ids);
		$sql = "DELETE FROM cs_company_banks WHERE userId = '$merchant_id' AND bank_id NOT IN($bank_ids)";
		$res = sql_query_write($sql) or dieLog(mysql_error());

	}

	function get_MerchantRates($merchant_id)
	{
		$banks = $this->get_Banks();
		$banks_id = $this->get_BanksById();
		$merchant_info = merchant_getInfo($merchant_id);

		$sql = "SELECT * FROM cs_company_banks WHERE userId = \"$merchant_id\"";
		$res = sql_query_read($sql) or dieLog(mysql_error());

		while($r = mysql_fetch_assoc($res))
		if($this->allowed_banks == NULL || in_array($r['bank_id'],$this->allowed_banks))
			$company_rates[$banks_id[$r['bank_id']]['bank_name']] = $r;

		$tiered_rates = array();

		foreach($banks as $bank_name => $info)
		if(isset($company_rates[$bank_name]))
		{
			$bank_rates = unserialize(stripslashes($company_rates[$bank_name]['cb_config']));

			$tiers = $bank_rates;

			if(!isset($tiers['default']['Bank']))
			{
				$tiers['default']['Bank'] = array(
							"trans"	=> $info['transactionfee'],
							"disct"	=> $info['discountrate'],
							"decln"	=> $info['bk_fee_decline'],
							"refnd"	=> $info['bk_fee_refund'],
							"chgbk"	=> $info['bk_fee_chargeback'],
							"default" => true
						);
			}

			if(!isset($tiers['default']['Processor']))
			{
				$tiers['default']['Processor'] = array(
							"trans"	=> $merchant_info['transactionfee'],
							"disct"	=> $merchant_info['discountrate'],
							"decln"	=> 0,
							"refnd"	=> 0,
							"cstsv" => $merchant_info['cc_customer_fee'],
							"resrv"	=> $merchant_info['cc_reserve'],
							"chgbk"	=> $merchant_info['cc_chargeback']
						);
			}

			$tiered_rates[$bank_name] = $tiers;
			$tiered_rates[$bank_name]['bank_id'] = $info['bank_id'];
			$tiered_rates[$bank_name]['bank_desc'] = $info['bank_description'];
			$tiered_rates[$bank_name]['trans_type'] = $info['bk_trans_types'];
		}
		return $tiered_rates;
	}
	
	function get_MerchantOldRates($merchant_id)
	{
		$merchant_info = merchant_getInfo($merchant_id);
		
		return array(
			"trans"	=> $merchant_info['transactionfee'],
			"disct"	=> $merchant_info['discountrate'],
			"cstsv" => $merchant_info['cc_customer_fee'],
			"resrv"	=> $merchant_info['cc_reserve'],
			"chgbk"	=> $merchant_info['cc_chargeback'],
			"wiref"	=> $merchant_info['cd_wirefee'],
			"uchgb"	=> $merchant_info['cc_underchargeback'],
			"ochgb"	=> $merchant_info['cc_overchargeback'],
			"cusfe" => $merchant_info['cc_customer_fee'],
			"upfrn" => $merchant_info['cd_appfee_upfront']
		);
	}
}

?>