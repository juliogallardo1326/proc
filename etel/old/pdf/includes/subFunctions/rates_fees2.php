<?

class rates_fees
{
	var $access_mode;
	var $allowed_banks;
	var $global_row;

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

		foreach($company_rates as $bank_id=>$bank_info)
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

			//if($high_risk["transaction_type"]=='game' || $high_risk["transaction_type"]=='adlt')
				$rates['r_bank_discount_rate']=$high_risk["bk_fee_high_risk"];
		//	else
		//		$rates['r_bank_discount_rate']=$bank_rates['Bank']['disct'];


			$rates['r_credit']=$bank_rates['Processor']['refnd'];
			$rates['r_chargeback']=$bank_rates['Processor']['chgbk'];
			$rates['r_reserve']=$bank_rates['Processor']['rserv'];
 
			$rates['r_reseller_trans_fees']=$bank_rates['Reseller']['trans'];
			$rates['r_reseller_discount_rate']=$bank_rates['Reseller']['disct'];

			$rates['r_merchant_trans_fees']=$bank_rates['Processor']['trans'];
			$rates['r_merchant_discount_rate']=$bank_rates['Processor']['disct'];

			$rates['r_total_trans_fees']=$rates['r_merchant_trans_fees']-$rates['r_reseller_trans_fees'];
			$rates['r_total_discount_rate']=$rates['r_merchant_discount_rate']-$rates['r_reseller_discount_rate'];
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
		"amount","bankname","bankroutingcode",
		"bankaccountnumber","accounttype","email",
		"cancelstatus","userid","cardtype",
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
		"transactiondate","billingdate","td_ss_ID"
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

	function get_ResellerInfo($en_ID)
	{
		$sql = "select re.*
			FROM 
				cs_entities as re,
				cs_entities_affiliates as ea
			WHERE
				ea.ea_en_ID = '$en_ID' and ea.ea_affiliate_ID = re.en_ID and ea_type='Reseller'";
		
		$result = sql_query_read($sql) or dieLog(mysql_error() . "<br>" . $sql);
		if(!mysql_num_rows($result)) return false;
		$resellerInfo = mysql_fetch_assoc($result);
		$resellerInfo['en_info'] = @unserialize($resellerInfo['en_info']);
		return $resellerInfo;	
	}

	function get_Banks()
	{
		$sql = "SELECT * FROM cs_bank WHERE bk_hide=0 order by bank_id asc";
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

	function get_BanksById($bank_id = NULL)
	{
		$sql_bank = ($bank_id?" AND bank_id = '$bank_id'":"");
		$sql = "SELECT * FROM cs_bank
			LEFT JOIN cs_entities on en_type = 'bank' and en_type_ID = '$bank_id'
			WHERE bk_hide=0 $sql_bank order by bank_id asc";
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
			"Trans"=>"trans",
			"Discount"=>"disct",
			"Decline"=>"decln",
			"CS Fee."=>"cstsv",
			"Refund"=>"refnd",
			"Chgback"=>"chgbk",
			"Reserve"=>"rserv",
			"Hold"=>"hold",
			"Return"=>"retrn"
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
		foreach($settings as $bank_name => $this_setting)
		if($this->allowed_banks == NULL || in_array($this_setting['bank_id'],$this->allowed_banks))
		{
			if(!$this_setting['default']) $this_setting['default'] = $settings['Default Rates']['default'];
			$bank_id = $this_setting['bank_id'];
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
			$res = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
			$bank_list[] = $bank_id;
		}
		if(sizeof($bank_list))
		{
			$sql = "DELETE FROM cs_company_banks WHERE userId = '$merchant_id' AND bank_id not in (".implode(',',$bank_list).")";
			$res = sql_query_write($sql) or dieLog(mysql_error());
		}
	}

	function get_MerchantRates($merchant_id,$bank_id=NULL)
	{
		//$banks = $this->get_Banks();
		$banks_id = $this->get_BanksById($bank_id);
		$merchant_info = merchant_getInfo($merchant_id);
		$reseller_info = $this->get_ResellerInfo($merchant_info['en_ID']);

		$sql = "SELECT * FROM cs_company_banks WHERE userId = \"$merchant_id\"";
		$res = sql_query_read($sql) or dieLog(mysql_error());

		while($r = mysql_fetch_assoc($res))
		if($this->allowed_banks == NULL || in_array($r['bank_id'],$this->allowed_banks))
			$company_rates[$r['bank_id']] = $r;

		$tiered_rates = array();

		foreach($banks_id as $bank_id => $info)
			if(isset($company_rates[$bank_id]))
			{
				$bank_rates = unserialize(stripslashes($company_rates[$bank_id]['cb_config']));
	
				$tiers = $bank_rates;
	
				if(!isset($tiers['default']['Bank']))
				{
					$tiers['default']['Bank'] = array(
								"en_ID"	=> $info['en_ID'],
								"trans"	=> $info['bk_fee_approve'],
								"disct"	=> $info['bk_fee_high_risk'],
								"decln"	=> $info['bk_fee_decline'],
								"refnd"	=> $info['bk_fee_refund'],
								"resrv"	=> $info['rollingreserve'],
								"chgbk"	=> $info['bk_fee_chargeback'],
								"hold"	=> $info['bk_days_behind'],
								"default" => true
							); 
				}
	
				if(!isset($tiers['default']['Processor']))
				{
					$tiers['default']['Processor'] = array(
								"en_ID"	=> 2,
								"trans"	=> $merchant_info['cc_total_trans_fees'],
								"disct"	=> $merchant_info['cc_total_discount_rate'],
								"decln"	=> $merchant_info['cc_total_trans_fees'],
								"refnd"	=> $merchant_info['discountrate'],
								"cstsv" => $merchant_info['cc_customer_fee'],
								"resrv"	=> $merchant_info['cc_reserve'],
								"chgbk"	=> $merchant_info['cc_chargeback'],
								"hold"	=> $merchant_info['cd_paydelay'],
								"oldstyle" => true
							);
				}
	
				if(!isset($tiers['default']['Reseller']) && is_array($reseller_info))
				{
					$tiers['default']['Reseller'] = array(
								"en_ID"	=> $reseller_info['en_ID'],
								"trans"	=> $reseller_info['en_info']['Reseller']['Default_Trans_Markup'],
								"disct"	=> $reseller_info['en_info']['Reseller']['Default_Disc_Markup']
							);
				}
				
				if(!isset($tiers['default']['Processor']['hold']))
					$tiers['default']['Processor']['hold'] = $merchant_info['cd_paydelay'];
					
				if(!isset($tiers['default']['Reseller']['en_ID']) && is_array($reseller_info))
					$tiers['default']['Reseller']['en_ID'] = $reseller_info['en_ID'];
					
				if(!isset($tiers['default']['Processor']['en_ID']))
					$tiers['default']['Processor']['en_ID'] = 2;
	
	
				$tiered_rates[$bank_id] = $tiers;
				$tiered_rates[$bank_id]['bank_id'] = $info['bank_id'];
				$tiered_rates[$bank_id]['bank_desc'] = $info['bank_description'];
				$tiered_rates[$bank_id]['trans_type'] = $info['bk_trans_types'];
			}
		unset($banks_id);
		unset($merchant_info);
		unset($reseller_info);
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
	
	function get_Merchant_Contract($userId)
	{
			$thisdate = time();
		if(is_array($userId))$userId = intval($userId['userId']);
		$sql = "
			select
				*
			from
				cs_companydetails
			Where
				userId = '$userId';
			";
			
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~$sql");
		$companyInfo = mysql_fetch_assoc($result);
		
		$str_qry = "
			select 
				* 
			from 
				cs_company_sites 
			where 
				`cs_gatewayId` = '".$_SESSION["gw_id"]."' 
				AND cs_company_id = '".$companyInfo['userId']."'
			";
		$sql_select_val = sql_query_read($str_qry) or dieLog(mysql_errno().": ".mysql_error()."<BR>~$str_qry");

		while($site = mysql_fetch_assoc($sql_select_val))
				$websites[] = $site['cs_URL'];

		if (sizeof($websites)<1) $websites[] = "No Sites";
		
		
		$ratesInfo = $this->get_MerchantRates($userId);
		$disp_array = array(	
								array('type'=>'banks'),
								array('type'=>'trans','disp'=>'Transaction Fee','before'=>'$'),
								array('type'=>'disct','disp'=>'Discount Rate','after'=>' %'),
								array('type'=>'decln','disp'=>'Decline Fee','before'=>'$'),
								array('type'=>'cstsv','disp'=>'Customer Service Fee','before'=>'$'),
								array('type'=>'banks'),
								array('type'=>'refnd','disp'=>'Refund Fee','before'=>'$'),
								array('type'=>'chgbk','disp'=>'Chargeback Fee','before'=>'$'),
								array('type'=>'rserv','disp'=>'Reserve Rate','after'=>' %')
		);
		
		$contract_rates_table = '';
		$contract_rates_table .= "<table class='report' border='1' width='100%'>";
		$contract_rates_table .= "<tr class='header' align='center'><td><b>Transaction Rates and Fees</b></td></tr>";
		$contract_rates_table .= "<tr><td><table class='report' border='1' width='100%'>";
		$rates_array = array('Visa'=>null,'Mastercard'=>null,'Check'=>null,'Discover'=>null);
		foreach($ratesInfo as $key=>$banks)
			if($key && $banks['trans_type']) $rates_array[$banks['trans_type']] = $banks;
		if($ratesInfo[0]) 
		{
			$rates_array['Default'] = $ratesInfo[0];
			$rates_array['Default']['trans_type'] = "Default *";
			foreach($rates_array as $type=>$banks)
				if(!$banks) $rates_array[$type] = $ratesInfo[0];
		}		
		
		foreach($disp_array as $display)
		{
			$contract_rates_table .= "<tr  class='row".$this->gen_row(1)."' ><td><b>".$display['disp']."&nbsp;</b></td>";
			if($display['type']=='banks')
				foreach($rates_array as $type=>$banks)
					$contract_rates_table .= "<td align='center'><b>".$type."</b></td>";
			else
				foreach($rates_array as $banks)
				{
					$total = $banks['default']['Processor'][$display['type']]+$banks['default']['Reseller'][$display['type']];
					if(is_array($banks)) $contract_rates_table .= "<td align='center'>".$display['before'].formatMoney($total).$display['after']." </td>";
					else $contract_rates_table .= "<td align='center' > - </td>";
				}
			$contract_rates_table .= "</tr>";
		}
		$contract_rates_table .= "</table ></td></tr>";
		$contract_rates_table .= "<tr  class='small' ><td><b>* The default Rates and fees for any future payment methods that become available are marked under 'Default'</b></td></tr>";
		$contract_rates_table .= "</table >";



	
		$companyInfo['websites'] = implode(", ",$websites);
		$data['email'] = 			$companyInfo['contact_email'];
		$data['fax_number'] = 		$companyInfo['fax_number'];
		$data['phone_number'] = 	$companyInfo['phonenumber'];
		$data['companyname'] = 		$companyInfo['companyname'];
		$data['full_name'] = 		$companyInfo['first_name']." ".$companyInfo['family_name'];
		$data['title'] = 			$companyInfo['job_title'];
		$data['date'] = 			"the ".date("jS",$thisdate)." day of ".date("F",$thisdate).", the year ".date("Y",$thisdate); 
		$data['address'] = 			$companyInfo['address'].",".$companyInfo['city'].",".$companyInfo['state'].$companyInfo['country'].$companyInfo['zipcode'];     
		$data['discount_fee'] = 	formatPerc($companyInfo['cc_merchant_discount_rate']);        
		$data['rolling_reserve_fee'] = formatPerc($companyInfo['cc_reserve']);        
		$data['wire_fee'] = 		"$".formatMoney($companyInfo['cd_wirefee']);        
		$data['monthly_fee'] = 		"$".formatMoney($companyInfo['cs_monthly_charge']);          
		$data['transaction_fee'] = 	"$".formatMoney($companyInfo['cc_merchant_trans_fees']);          
		$data['fraud_fee'] = 		"$0.00";          
		$data['refund_fee'] = 		"$".formatMoney($companyInfo['cc_discountrate']);          
		$data['chargebackover_fee'] = 	"$".formatMoney($companyInfo['cc_overchargeback']);       
		$data['chargebackunder_fee'] = 	"$".formatMoney($companyInfo['cc_underchargeback']);          
		$data['setup_fee'] = 		"$".formatMoney($companyInfo['cd_appfee']);             
		$data['setup_fee_upfront'] ="$".formatMoney($companyInfo['cd_appfee_upfront']);     
		$data['customer_fee'] = 	$companyInfo['cc_customer_fee'];                 
		$data['reference_number'] = $companyInfo['ReferenceNumber'];                 
		$data['contract_rates_table'] = $contract_rates_table;          
		
		if($companyInfo['cd_pay_bimonthly']=='bimonthly') $data['days_behind'] = "Paid on 1st and 15th of each month"; 
		else if($companyInfo['cd_pay_bimonthly']=='trimonthly') $data['days_behind'] = "Paid on 1st, 10th, and 20th of each month";  
		else $data['days_behind'] = "Paid every ".$companyInfo['cd_payperiod']." days on ".date('l',(4+$companyInfo['cd_paydaystartday'])*60*60*24);  
		$data['days_behind'] .= "<BR>".$companyInfo['cd_paydelay']." days behind";   
		
		$data['tmpl_custom_id'] = $companyInfo['userId'];
		
		$contract = get_email_template('merchant_contract',$data);
		return $contract;
		
	}
	
	// Profit Section
	
	
	function get_entity_id($search = NULL)
	{
		if(!is_array($search)) return false;
		$sql = "SELECT en_ID FROM cs_entities as en WHERE 1 ";
		foreach($search as $key=>$val)
			$sql .= " AND $key = '".quote_smart($val)."' ";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		if(mysql_num_rows($result)<1) return false;
		$en_ID = mysql_result($result,0,0);
		return $en_ID;
	}
	
	function create_entity($fields = NULL)
	{
		if(!is_array($fields)) return false;
		if(!$fields['en_type'] || !$fields['en_type_ID']) return false;
		
		if(!$fields['en_username']) $fields['en_username'] = $fields['en_type']."user_".rand(1000,9999);
		if(!$fields['en_password']) $fields['en_password'] = md5(rand(1000,9999));
		if(!$fields['en_firstname']) $fields['en_firstname'] = $fields['en_username'];
		if(!$fields['en_lastname']) $fields['en_lastname'] = $fields['en_type'];
		if(!$fields['en_company']) $fields['en_company'] = $fields['en_company'];
		if(!$fields['en_status']) $fields['en_status'] = 'inactive';
		if(!$fields['en_email']) $fields['en_email'] = $fields['en_username']."@nowhere.com";
		if(!$fields['en_signup']) $fields['en_signup'] = date('Y-m-d G:i:s');
		if(!$fields['en_ref']) $fields['en_ref'] = substr(strtoupper(md5(time()+rand(1,1000000))),0,12);
		
		foreach($fields as $key=>$val)
			$insert_sql .= ($insert_sql?", ":"")." $key = '".quote_smart($val)."' ";
			
		$sql = "Insert Into cs_entities SET $insert_sql";
		$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		$en_ID = mysql_insert_id();

		return $en_ID;
	}
	
	function update_transaction_profit($id)
	{
	
		$sql = "SELECT * FROM cs_transactiondetails as td 
		left join `cs_bank` as bk on td.bank_id = bk.bank_id
		WHERE transactionId = '$id'";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$transInfo = mysql_fetch_assoc($result);
		
		$rates = $this->rates->get_MerchantRates($transInfo['userId'],$transInfo['bank_id']);
		$rates = $rates[$transInfo['bank_id']];
		if(!is_array($rates)) return array('status'=>'fail','msg'=>'Invalid Merchant Bank Rates');
		
		etelPrint($rates);
		
		// Create/Update Required Entities
		
		$proc_en_ID = $this->get_entity_id(array('en_ID'=>2));
		if(!$proc_en_ID) $proc_en_ID = $this->create_entity(array('en_ID'=>2,'en_type'=>'processor','en_type_ID'=>1,'en_company'=>"Etelegate.com",'en_gateway_ID'=>3));
		if(!$proc_en_ID) return array('status'=>'fail','msg'=>'Could not get/create Processor ID');
		
		$bank_en_ID = $this->get_entity_id(array('en_type'=>'bank','en_type_ID'=>$transInfo['bank_id']));
		if(!$bank_en_ID) $bank_en_ID = $this->create_entity(array('en_type'=>'bank','en_type_ID'=>$transInfo['bank_id'],'en_company'=>$transInfo['bank_name'],'en_gateway_ID'=>$transInfo['gateway_id']));
		if(!$bank_en_ID) return array('status'=>'fail','msg'=>'Could not get/create Bank Entity ID');
		
		$merc_en_ID = $this->get_entity_id(array('en_type'=>'merchant','en_type_ID'=>$transInfo['userId']));
		if(!$merc_en_ID) return array('status'=>'fail','msg'=>'Could not get Merchant Entity ID');
		
		// Create/Update Required Entities
		
		
		// Commit Profit
		
		$data = array('description'=>"Transaction Profit for ".$transInfo['reference_number']);
		
		$TransferDefault = array(
			'transfer_type'=>'Sale Profit',
			'bank_ID'=>$transInfo['bank_id'],
			'trans_ID'=>$transInfo['transactionId'],
			'date_entered'=>$transInfo['transactionDate']
		);
		
		$SaleAmount = $transInfo['amount']-$transInfo['td_customer_fee'];
		$BankReserve = $transInfo['amount']*($rates['default']['Bank']['rserv']/100);
		$ProcReserve = $transInfo['amount']*($rates['default']['Processor']['rserv']/100);
		
		// Processor Funds
		
		$newTransfer = $TransferDefault;
		$newTransfer['amount'] = $SaleAmount-$BankReserve;
		$newTransfer['from_entity'] = $bank_en_ID;
		$newTransfer['to_entity'] = $proc_en_ID;
		$newTransfer['date_effective'] = date('Y-m-d G:i:s',time()+(60*60*24*intval($rates['default']['Bank']['hold'])));
		
		$data['transfers'][] = $newTransfer;
		
		// Merchant Funds
		
		$newTransfer = $TransferDefault;
		$newTransfer['amount'] = $SaleAmount-$ProcReserve;
		$newTransfer['from_entity'] = $proc_en_ID;
		$newTransfer['to_entity'] = $merc_en_ID;
		$newTransfer['date_effective'] = date('Y-m-d G:i:s',time()+(60*60*24*intval($rates['default']['Processor']['hold'])));
		
		$data['transfers'][] = $newTransfer;
		
		if($transInfo['cancelstatus']!='Y')
		{
			// Bank Reserve
			
			$newTransfer = $TransferDefault;
			$newTransfer['amount'] = $BankReserve;
			$newTransfer['from_entity'] = $bank_en_ID;
			$newTransfer['to_entity'] = $proc_en_ID;
			$newTransfer['date_effective'] = date('Y-m-d G:i:s',time()+(60*60*24 * 180)); // TODO: Dynamic Reserve Release
			
			$data['transfers'][] = $newTransfer;
			
			// Processor Reserve
			
			$newTransfer = $TransferDefault;
			$newTransfer['amount'] = $ProcReserve;
			$newTransfer['from_entity'] = $proc_en_ID;
			$newTransfer['to_entity'] = $merc_en_ID;
			$newTransfer['date_effective'] = date('Y-m-d G:i:s',time()+(60*60*24 * 180)); // TODO: Dynamic Reserve Release
			
			$data['transfers'][] = $newTransfer;		
			
			// Customer Fee
			
			if($transInfo['td_customer_fee'])
			{
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] = 'Customer Fee';
				$newTransfer['amount'] = $transInfo['td_customer_fee'];
				$newTransfer['from_entity'] = $bank_en_ID;
				$newTransfer['to_entity'] = $proc_en_ID; // Only processors get customer fees
				$newTransfer['date_effective'] = date('Y-m-d G:i:s',time()+(60*60*24*intval($rates['default']['Bank']['hold'])));
				
				$data['transfers'][] = $newTransfer;
			}
			
		}
		
		// Refund/Chargeback Amount
		if($transInfo['td_is_chargeback']=='1' || $transInfo['cancelstatus']=='Y')
		{
			// Refund/Chargeback Refund
			$newTransfer = $TransferDefault;
			$newTransfer['transfer_type'] = 'Refund Amount';
			$newTransfer['amount'] = $SaleAmount-$BankReserve;
			$newTransfer['from_entity'] = $proc_en_ID;
			$newTransfer['to_entity'] = $bank_en_ID;
			
			$data['transfers'][] = $newTransfer;
			
			// Refund/Chargeback Refund
			$newTransfer = $TransferDefault;
			$newTransfer['transfer_type'] = 'Refund Amount';
			$newTransfer['amount'] = $SaleAmount-$ProcReserve;
			$newTransfer['from_entity'] = $merc_en_ID;
			$newTransfer['to_entity'] = $proc_en_ID;
			
			$data['transfers'][] = $newTransfer;

		}
		
		// Affiliate/Processor/Bank Fees
		
		foreach($rates['default'] as $payees => $rateInfo)
		{
			
			// Transaction Fee
			if($rateInfo['trans'])
			{
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] = 'Transaction Fee';
				$newTransfer['amount'] = ($transInfo['status']=='A'?$rateInfo['trans']:$rateInfo['decln']);
				$newTransfer['from_entity'] = $merc_en_ID;
				$newTransfer['to_entity'] = $rateInfo['en_ID'];
				
				$data['transfers'][] = $newTransfer;
			}
			
			// Discount Fee
			if($rateInfo['trans'])
			{
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] = 'Discount Fee';
				$newTransfer['amount'] = $SaleAmount*($rateInfo['disct']/100);
				$newTransfer['from_entity'] = $merc_en_ID;
				$newTransfer['to_entity'] = $rateInfo['en_ID'];
				
				$data['transfers'][] = $newTransfer;
			}
			
			// Refund Fee
			if($rateInfo['trans'] && $transInfo['cancelstatus']=='Y')
			{
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] = 'Refund Fee';
				$newTransfer['amount'] = $rateInfo['refnd'];
				$newTransfer['from_entity'] = $merc_en_ID;
				$newTransfer['to_entity'] = $rateInfo['en_ID'];
				
				$data['transfers'][] = $newTransfer;
			}
		
			// Chargeback Fee
			if($rateInfo['chgbk'] && $transInfo['td_is_chargeback']=='1')
			{
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] = 'Chargeback Amount';
				$newTransfer['amount'] = $rateInfo['chgbk'];
				$newTransfer['from_entity'] = $merc_en_ID;
				$newTransfer['to_entity'] = $rateInfo['en_ID'];
				
				$data['transfers'][] = $newTransfer;
			}
		
		}
		
		etelPrint($data);
		
		// Merchant Profit
		
		//$data['transfers']
		
	}
	
	function commit_transfer($data)
	{
		
		$sql = "
		Insert into 
			cs_profit_action
		set
			pa_date = now(),
			pa_status = 'pending',
			pa_desc = '".$data['description']."',
			pa_info = '".quote_smart(serialize($data))."'
		";
		
		$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		$pa_ID = mysql_insert_id();
		
		if(!$result || !$pa_ID)
			dieLog(mysql_error() . " ~ $sql");
	
		sql_query_write("Start Transaction") or dieLog(mysql_error()." ~ $sql");
		
		foreach($data['transfers'] as $transfer)
		{
			$transfer['amount'] = floatval($transfer['amount']);
			if($transfer['amount'] <= 0)
				dieLog("Profit Transfer Error: Amount <=0 ".print_r($transfer,true));
				
			if($transfer['from_entity'] < 1 || $transfer['to_entity'] < 1)
				dieLog("Profit Transfer Error: Entity ID < 1 ".print_r($transfer,true));
				
			if(!$transfer['date_entered'] || !strtotime($transfer['date_entered']))
				$transfer['date_entered'] = date('Y-m-d G:i:s');
				
			if(!$transfer['date_effective'] || !strtotime($transfer['date_effective']))
				$transfer['date_effective'] = date('Y-m-d G:i:s');
				
			if(!$transfer['transfer_type'])
				$transfer['transfer_type'] = 'General';
			
			
			$params = array();
			
			$params['pt_action_ID'] = $pa_ID;
			$params['pt_type'] = $transfer['transfer_type'];
			$params['pt_bank_ID'] = $transfer['bank_ID'];
			$params['pt_trans_ID'] = $transfer['trans_ID'];
			$params['pt_date_entered'] = $transfer['date_entered'];
			$params['pt_date_effective'] = $transfer['date_effective'];
			
			// Transfer From
			
			$params['pt_amount'] = -$transfer['amount'];
			$params['pt_entity_ID'] = $transfer['from_entity'];
			
			$sql_set = "";
			foreach($params as $p_name => $p_value)
				$sql_set .= ($sql_set == "" ? "" : ",\r\n") . $p_name . " = '" . quote_smart($p_value) . "'";
			
			$sql = "
			INSERT INTO
				cs_profit
			SET
				$sql_set
			";
			
			$result = sql_query_write($sql);
			if(!$result)
			{
				sql_query_write("RollBack") or dieLog(mysql_error()." ~ $sql");
			 	dieLog(mysql_error()." ~ $sql");			
			}
			
			// Transfer To			
			
			$params['pt_amount'] = $transfer['amount'];
			$params['pt_entity_ID'] = $transfer['to_entity'];
			
			$sql_set = "";
			foreach($params as $p_name => $p_value)
				$sql_set .= ($sql_set == "" ? "" : ",\r\n") . $p_name . " = '" . quote_smart($p_value) . "'";
			
			$sql = "
			INSERT INTO
				cs_profit
			SET
				$sql_set
			";
			
			$result = sql_query_write($sql);
			if(!$result)
			{
				sql_query_write("RollBack") or dieLog(mysql_error()." ~ $sql");
			 	dieLog(mysql_error()." ~ $sql");			
			}
			
		
		}
		
		sql_query_write("Commit") or dieLog(mysql_error()." ~ $sql");
		
		$sql = "
			Update 
				cs_profit_action
			Set
				pa_status = 'success'
			Where
				pa_ID = '$pa_ID'
			";
			
		$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		
	}
	
	
	function gen_row($change = false)
	{
		if(!$this->global_row) $this->global_row = 1;
		if($change) $this->global_row = 3-$this->global_row;
		return $this->global_row;
	}
}

?>