<?

class rates_fees
{
	var $access_mode;
	var $allowed_banks;
	var $global_row;
	var $cache;

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

	function update_TransactionRates($en_ID,$transInfo,$trans_type="cc",$mode="Test")
	{
		// TODO: Replace this function with update_transaction_profit when live.
		$company_rates = $this->get_MerchantRates($en_ID);

		$trans_bank_id = $transInfo['bank_id'];
		$bank_rates = NULL;

		foreach($company_rates as $bank_id=>$bank_info)
			if($bank_info['bank_id'] == $trans_bank_id)
				$bank_rates = $bank_info['default'];

		if($bank_rates != NULL)
		{

			if($transInfo['status']=='A')
				$rates['r_bank_trans_fee']=$bank_rates['Bank']['trans'];
			else
				$rates['r_bank_trans_fee']=$bank_rates['Bank']['decln'];


			$rates['r_credit']=$bank_rates['Processor']['refnd'];
			$rates['r_chargeback']=$bank_rates['Processor']['chgbk'];
			$rates['r_reserve']=$bank_rates['Processor']['rserv'];
 
			$rates['r_reseller_trans_fees']=$bank_rates['Reseller']['trans'];
			$rates['r_reseller_discount_rate']=$bank_rates['Reseller']['disct'];

			$rates['r_merchant_trans_fees']=$bank_rates['Processor']['trans'];
			$rates['r_merchant_discount_rate']=$bank_rates['Processor']['disct'];
			
			$rates['r_bank_trans_fee']=$bank_rates['Bank']['trans'];
			$rates['r_bank_discount_rate']=$bank_rates['Bank']['disct'];

			$rates['r_total_trans_fees']=$rates['r_merchant_trans_fees']-$rates['r_reseller_trans_fees'];
			$rates['r_total_discount_rate']=$rates['r_merchant_discount_rate']-$rates['r_reseller_discount_rate'];
		}

		$typeArray = array('cc' => 'creditcard', 'ch'=>'check', 'web'=>'web900');
		$cr_transtype = $typeArray[$trans_type];

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

	function get_BanksById($bank_ids = NULL)
	{
		$sql_bank = (is_array($bank_ids)?" AND bank_id in (".implode(', ',$bank_ids).")":"");

		
		$sql = "SELECT * FROM cs_bank
			WHERE bk_hide=0 $sql_bank order by bank_id asc";
		$res = sql_query_read($sql) or dieLog(mysql_error());
		$banks = array();
		while($r = mysql_fetch_assoc($res))
		if($this->allowed_banks == NULL || in_array($r['bank_id'],$this->allowed_banks))
		{
			$r['bk_defaults'] = unserialize(stripslashes($r['bk_defaults']));
			
			if($r['bank_id'])
			{
				$bank_en_ID = $this->get_entity_id(array('en_type'=>'bank','en_type_ID'=>$r['bank_id']));
				if(!$bank_en_ID) $bank_en_ID = $this->create_entity(array('en_type'=>'bank','en_type_ID'=>$r['bank_id'],'en_company'=>$r['bank_name']));
				if(!$bank_en_ID) dieLog('Could not get/create Bank Entity ID: '.print_r($r,true));
				$r['en_ID'] = $bank_en_ID;
			}
			$banks[$r['bank_id']] = $r;
			
		}
		return $banks;
	}

	function get_Payees()
	{
		$payees = array();
		$payees['prcs'] = array("title"=>"Processor");
		$payees['rsll'] = array("title"=>"Reseller","display"=>"none","allowhide"=>true);
		$payees['bank'] = array("title"=>"Bank","allowdisable"=>true,"allowhide"=>true);

		return $payees;
	}

	function get_RateCategories()
	{
		$cats = array(
			"trans"=>array('title'=>"Trans",'before'=>'$'),
			"disct"=>array('title'=>"Discount",'after'=>'%'),
			"decln"=>array('title'=>"Decline",'before'=>'$'),
			"cstsv"=>array('title'=>"CS Fee.",'before'=>'$'),
			"refnd"=>array('title'=>"Refund",'before'=>'$'),
			"chgbk"=>array('title'=>"Chgback",'before'=>'$'),
			"hold"=>array('title'=>"Hold",'after'=>'%'),
			"rserv"=>array('title'=>"Reserve",'after'=>'%'),
			"rservhold"=>array('title'=>"ResHold",'after'=>' Days'),
			"monthly"=>array('title'=>"Monthly",'hidden'=>true),
			"setup"=>array('title'=>"Setup",'hidden'=>true),
			"wirefee"=>array('title'=>"Wire Fee",'hidden'=>true),
			"achfee"=>array('title'=>"ACH Fee",'hidden'=>true),
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
		
		foreach($cat as $abbr=>$info)
			$results[$abbr] = $this->calc_rates($settings,$abbr);
		return $results;
	}

	function calc_rates($settings,$calc)
	{
		$formulas = $this->get_Calculations();

		if(!isset($formulas[$calc]))
			return $undefined;

		foreach($settings as $actor => $info)
			if($info)
				foreach($info as $type => $value)
					${$actor . "_" . $type} = $value;

		eval('$fee = ' . $formulas[$calc] . ';');
		return $fee;
	}

	function post_MerchantRate($en_ID,$settings)
	{
		foreach($settings as $bank_name => $this_setting)
		if($this->allowed_banks == NULL || in_array($this_setting['bank_id'],$this->allowed_banks))
		{
			$bank_id = $this_setting['bank_id'];
			unset($this_setting['bank_id']);
			$this_setting = quote_smart(serialize($this_setting));
			
			$sql = "INSERT INTO
						cs_company_banks
					SET
						cb_config = '$this_setting',
						cb_en_ID = '$en_ID',
						bank_id = '$bank_id'
					ON DUPLICATE KEY UPDATE
						cb_config = '$this_setting';
				";
			$res = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
			$bank_list[] = $bank_id;
		}
		if(sizeof($bank_list))
		{
			$sql = "DELETE FROM cs_company_banks WHERE cb_en_ID = '$en_ID' AND bank_id not in (".implode(',',$bank_list).")";
			$res = sql_query_write($sql) or dieLog(mysql_error());
		}
	}

	function get_MerchantRates($en_ID,$bank_ids=NULL)
	{
		//$banks = $this->get_Banks();
		$banks_id = $this->get_BanksById($bank_ids);
		$merchant_info = merchant_getInfo($en_ID);
		$reseller_info = $this->get_ResellerInfo($en_ID);

		$sql = "SELECT * FROM cs_company_banks WHERE cb_en_ID = '$en_ID'";
		if($bank_ids) $sql .= " AND bank_id in (".implode(', ',$bank_ids).")";
		$res = sql_query_read($sql) or dieLog(mysql_error());

		while($r = mysql_fetch_assoc($res))
		if($this->allowed_banks == NULL || in_array($r['bank_id'],$this->allowed_banks))
			$company_rates[$r['bank_id']] = $r;

		$tiered_rates = NULL;

		$default_rates = unserialize(($company_rates[0]['cb_config']));
		foreach($banks_id as $bank_id => $info)
			if(isset($company_rates[$bank_id]))
			{
				$bank_rates = unserialize(($company_rates[$bank_id]['cb_config']));
	
				$tiers = $bank_rates;
				$proc_default = array(
					"en_ID"	=> 2,
					"from_entity" => $en_ID,
					"to_entity" => 2,
					"trans"	=> ($bank_id>0?$default_rates['default']['Processor']['trans']:0.00),
					"disct"	=> ($bank_id>0?$default_rates['default']['Processor']['disct']:0.00),
					"decln"	=> ($bank_id>0?$default_rates['default']['Processor']['decln']:0.00),
					"refnd"	=> ($bank_id>0?$default_rates['default']['Processor']['refnd']:15.00),
					"cstsv" => ($bank_id>0?$default_rates['default']['Processor']['cstsv']:1.95),
					"rserv"	=> ($bank_id>0?$default_rates['default']['Processor']['rserv']:10.00),
					"rservhold" => ($bank_id>0?$default_rates['default']['Processor']['rservhold']:180),
					"chgbk"	=> ($bank_id>0?$default_rates['default']['Processor']['chgbk']:50.00),
					"hold"	=> ($bank_id>0?$default_rates['default']['Processor']['hold']:15.00),
					"monthly"  => 0.00,
					"setup"  => 0.00,
					"wirefee"  => ($bank_id>0?0.00:50.00),
					"achfee"  => ($bank_id>0?0.00:5.00)
				);
				$bank_default = array(
					"en_ID"	=> $info['en_ID'],
					"from_entity" => 2,
					"to_entity" => $info['en_ID'],
					"trans"	=> $info['bk_fee_approve'],
					"disct"	=> $info['bk_fee_high_risk'],
					"decln"	=> $info['bk_fee_decline'],
					"refnd"	=> $info['bk_fee_refund'],
					"rserv"	=> $info['rollingreserve'],
					"chgbk"	=> $info['bk_fee_chargeback'],
					"hold"	=> $info['bk_days_behind']
				); 
				
				$res_default = array(
					"en_ID"	=> $reseller_info['en_ID'],
					"from_entity" => $en_ID,
					"to_entity" => $reseller_info['en_ID'],
					"trans"	=> $reseller_info['en_info']['Reseller']['Default_Trans_Markup'],
					"disct"	=> $reseller_info['en_info']['Reseller']['Default_Disc_Markup']
				);
	
				//if(!isset($tiers['default']['Processor']) && $default_rates) $tiers['default']['Processor'] = $default_rates['default']['Processor'];
				//if(!isset($tiers['default']['Reseller']) && $default_rates) $tiers['default']['Reseller'] = $default_rates['default']['Reseller'];
				if(!isset($tiers['default']['Reseller'])) $tiers['default']['Reseller']['default'] = true;	
				if(!isset($tiers['default']['Bank'])) $tiers['default']['Bank']['default'] = true;
					
				foreach($bank_default as $key=>$val)
					if(!isset($tiers['default']['Bank'][$key])) $tiers['default']['Bank'][$key] = $val;
						
				foreach($proc_default as $key=>$val)
					if(!isset($tiers['default']['Processor'][$key])) $tiers['default']['Processor'][$key] = $val;
	
				foreach($res_default as $key=>$val)
					if(!isset($tiers['default']['Reseller'][$key])) $tiers['default']['Reseller'][$key] = $val;
				
				if(!($tiers['default']['Bank']['en_ID']))
					$tiers['default']['Bank']['en_ID'] = $info['en_ID'];
					
				if(!($tiers['default']['Processor']['hold']))
					$tiers['default']['Processor']['hold'] = $merchant_info['cd_paydelay'];
					
				if(!($tiers['default']['Reseller']['en_ID']) && is_array($reseller_info))
					$tiers['default']['Reseller']['en_ID'] = $reseller_info['en_ID'];
					
				if(!($tiers['default']['Processor']['en_ID']))
					$tiers['default']['Processor']['en_ID'] = 2;
	
	
				$tiered_rates[$bank_id] = $tiers;
				$tiered_rates[$bank_id]['bank_id'] = $info['bank_id'];
				$tiered_rates[$bank_id]['bank_desc'] = $info['bank_description'];
				$tiered_rates[$bank_id]['trans_type'] = $info['bk_trans_types'];
			}
		return $tiered_rates;
	}
	
	function get_Merchant_Contract($en_ID)
	{
		$thisdate = time();
		if(is_array($en_ID))$en_ID = intval($en_ID['en_ID']);
		$sql = "
			select
				*
			from
				cs_entities
			Where
				en_ID = '$en_ID';
			";
			
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~$sql");
		$companyInfo = mysql_fetch_assoc($result);
		$companyInfo['en_info'] = @unserialize($companyInfo['en_info']);
		
		$PaySchedule = en_get_payout_schedule($companyInfo);
		
		$str_qry = "
			select 
				* 
			from 
				cs_company_sites 
			where 
				cs_en_ID = '".$companyInfo['en_ID']."'
			";
		$sql_select_val = sql_query_read($str_qry) or dieLog(mysql_errno().": ".mysql_error()."<BR>~$str_qry");

		while($site = mysql_fetch_assoc($sql_select_val))
				$websites[] = $site['cs_URL'];

		if (sizeof($websites)<1) $websites[] = "No Sites";
		
		
		$ratesInfo = $this->get_MerchantRates($companyInfo['en_ID']);
		$disp_array = array(	
								'banks1' => array('type'=>'banks'),
								'trans' => array('type'=>'trans','disp'=>'Transaction Fee','before'=>'$'),
								'disct' => array('type'=>'disct','disp'=>'Discount Rate','after'=>' %'),
								'decln' => array('type'=>'decln','disp'=>'Decline Fee','before'=>'$'),
								'cstsv' => array('type'=>'cstsv','disp'=>'Customer Service Fee','before'=>'$'),
								'banks2' => array('type'=>'banks'),
								'refnd' => array('type'=>'refnd','disp'=>'Refund Fee','before'=>'$'),
								'chgbk' => array('type'=>'chgbk','disp'=>'Chargeback Fee','before'=>'$'),
								'hold' => array('type'=>'hold','disp'=>'Payment Hold','after'=>' Days'),
								'rserv' => array('type'=>'rserv','disp'=>'Reserve Rate','after'=>' %'),
								'rservhold' => array('type'=>'rservhold','disp'=>'Reserve Hold Duration','after'=>' Days')
		);
		
		$contract_rates_table = '';
		$contract_rates_table .= "<table class='invoice' border='1' width='100%'>";
		$contract_rates_table .= "<tr class='infoHeader' align='center'><td><b>Transaction Rates and Fees</b></td></tr>";
		$contract_rates_table .= "<tr><td><table class='report' border='1' width='100%'>";
		$rates_array = array('Default'=>null,'Visa'=>null,'Mastercard'=>null,'Check'=>null,'Discover'=>null);
		foreach($ratesInfo as $key=>$banks)
			if($key && $banks['trans_type']) $rates_array[$banks['trans_type']] = $banks;
		if($ratesInfo[0]) 
		{
			$rates_array['Default'] = $ratesInfo[0];
			$rates_array['Default']['trans_type'] = "Default *";
			foreach($rates_array as $type=>$banks)
				if(!$banks) $rates_array[$type] = $ratesInfo[0];
				
			$data['wire_fee'] = 		"$".formatMoney($rates_array['Default']['default']['Processor']['wirefee']);
			$data['ach_fee'] = 			"$".formatMoney($rates_array['Default']['default']['Processor']['achfee']);    
			$data['monthly_fee'] = 		"$".formatMoney($rates_array['Default']['default']['Processor']['monthly']);         
			$data['transaction_fee'] = 	"$".formatMoney($companyInfo['cc_merchant_trans_fees']);    
			$data['refund_fee'] = 		"$".formatMoney($companyInfo['cc_discountrate']);          
			$data['chargebackover_fee'] = "$".formatMoney($companyInfo['cc_overchargeback']);       
			$data['chargebackunder_fee'] = "$".formatMoney($companyInfo['cc_underchargeback']);          
			$data['setup_fee'] = 		"$".formatMoney($rates_array['Default']['default']['Processor']['setup']);
				
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
		$data['email'] = 			$companyInfo['en_email'];
		$data['fax_number'] = 		$companyInfo['en_info']['General_Info']['Contact_Fax'];
		$data['phone_number'] = 	$companyInfo['en_info']['General_Info']['Contact_Phone'];
		$data['companyname'] = 		$companyInfo['en_company'];
		$data['full_name'] = 		$companyInfo['en_firstname']." ".$companyInfo['en_lastname'];
		$data['date'] = 			"the ".date("jS",$thisdate)." day of ".date("F",$thisdate).", the year ".date("Y",$thisdate); 
		$data['address'] = 			$companyInfo['en_info']['General_Info']['Address'].",".$companyInfo['en_info']['General_Info']['City'].",".$companyInfo['en_info']['General_Info']['State'].$companyInfo['en_info']['General_Info']['Country'].$companyInfo['en_info']['General_Info']['Zip_Code'];     
		    
                
		$data['reference_number'] = $companyInfo['en_ref'];                 
		$data['contract_rates_table'] = $contract_rates_table;      
		
		$data['days_behind'] = $PaySchedule['Schedule']; 
		
		$data['tmpl_custom_id'] = $companyInfo['en_type_ID'];
		
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
	
	function commit_monthly_fee($en_ID,$data = array())
	{
		if(!$data['date_entered']) $data['date_entered'] = date('Y-m-d 00:00:00');
		if(!$date) $date = strtotime($data['date_entered']);
		$sql = "SELECT * 
			from 
				cs_entities as en 
				Left Join cs_profit_action as pa on 
					pa_en_ID = en_ID and `pa_date` = '".date('Y-m%',$date)."' and pa_type='Monthly Fee' 
			WHERE
				 en_ID = '$en_ID'
		";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		if(!mysql_num_rows($result)) return array('status'=>false,'msg'=>'Could not get Entity Info');
		$payoutInfo = mysql_fetch_assoc($result);
		if($payoutInfo['pa_ID'] && $payoutInfo['pa_status'] !='pending') return array('status'=>false,'msg'=>'This Payment Already Exists');
		
		// Start Payout Data
		
		$data['final_status'] = 'payout_pending';
		$data['description'] = $payoutInfo['en_company']." - ".date('F jS Y',$date)." - Pending";
		$data['type'] = 'Payout';
		$data['en_ID'] = $en_ID;
		
		if($payoutInfo['pa_ID']) $data['pa_ID'] = $payoutInfo['pa_ID'];
		$proc_en_ID = $this->get_entity_id(array('en_ID'=>2));
		if(!$proc_en_ID) $proc_en_ID = $this->create_entity(array('en_ID'=>2,'en_type'=>'processor','en_type_ID'=>1,'en_company'=>"Etelegate.com",'en_gateway_ID'=>3));
		if(!$proc_en_ID) return array('status'=>false,'msg'=>'Could not get/create Processor ID');
		
		if($data['pending_only']) 
		{
			if($payoutInfo['pa_ID']) return array('status'=>false,'msg'=>'This Payment Already Exists as Pending');
			$this->commit_transfer($data,true);
			// Just create the entry as pending. The system will recalc the data later.
			return array('status'=>true,'msg'=>'Pending Payout Entry Created Successfully');
		}
		
		$Profit = $this->get_profit(array(),$en_ID);
		
		$newTransfer = array();
		$newTransfer['transfer_type'] = 'Payout';
		$newTransfer['amount'] = $Profit['Total']['Amount'];
		$newTransfer['from_entity'] = $en_ID;
		$newTransfer['to_entity'] = $proc_en_ID;
		$newTransfer['date_effective'] = date('Y-m-d 00:00:00',$date);
		$data['transfers'][] = $newTransfer;
		$data['description'] = $payoutInfo['en_company']." - ".date('F jS Y',$date)." - $".formatMoney($Profit['Total']['Amount']);
		
		if($payoutInfo['pa_ID'])
			$this->undo_transfer($payoutInfo['pa_ID']);
		$this->commit_transfer($data);
		return array('status'=>true,'msg'=>'Pending Payout Recorded Successfully');
	}
	
	function commit_adjustment($en_ID,$amount,$data = array())
	{
		// Start Adjustment Data
		$amount = floatval($amount);
		if(!is_numeric($en_ID)) return array('status'=>false,'msg'=>"Invalid Entity ID ($en_ID)");
		if(!$amount || !is_numeric($amount)) return array('status'=>false,'msg'=>"Invalid Amount ($amount)");
		if(!$data['date_entered']) $data['date_entered'] = date('Y-m-d 00:00:00');
		if(!$data['date_effective']) $data['date_effective'] = date('Y-m-d');
		if(!$date) $date = strtotime($data['date_entered']);
		
		if(!$data['description']) $data['description'] = "Adjustment - ".date('F jS Y',$date)." - $".formatMoney($amount);
		$data['type'] = 'Adjustment';
		$data['en_ID'] = $en_ID;
		
		if($payoutInfo['pa_ID']) $data['pa_ID'] = $payoutInfo['pa_ID'];
		$proc_en_ID = $this->get_entity_id(array('en_ID'=>2));
		if(!$proc_en_ID) $proc_en_ID = $this->create_entity(array('en_ID'=>2,'en_type'=>'processor','en_type_ID'=>1,'en_company'=>"Etelegate.com",'en_gateway_ID'=>3));
		if(!$proc_en_ID) return array('status'=>false,'msg'=>'Could not get/create Processor ID');
		
		$newTransfer = array();
		$newTransfer['transfer_type'] = 'Adjustment';
		$newTransfer['from_entity'] = ($amount>0?$proc_en_ID:$en_ID);
		$newTransfer['to_entity'] = ($amount>0?$en_ID:$proc_en_ID);
		$newTransfer['amount'] = abs($amount);
		$newTransfer['date_effective'] = $data['date_effective'];
		$data['transfers'][] = $newTransfer;
		
		$pa_ID = $this->commit_transfer($data);
		return array('status'=>true,'msg'=>"Adjustment Recorded Successfully ($pa_ID)");
	
	}
	
	function commit_payout($en_ID,$data = array())
	{
		if(!$data['date_entered']) $data['date_entered'] = date('Y-m-d 00:00:00');
		if(!$date) $date = strtotime($data['date_entered']);
		$sql = "SELECT * 
			from 
				cs_entities as en 
				Left Join cs_profit_action as pa on 
					pa_en_ID = en_ID and `pa_date` = '".date('Y-m-d 00:00:00',$date)."' and pa_type='Payout' 
			WHERE
				 en_ID = '$en_ID'
		";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$cnt = mysql_num_rows($result);
		if(!$cnt) return array('status'=>false,'msg'=>'Could not get Entity Info');
		if($cnt>1) dieLog("Payment Error: $cnt Payouts for same period:\n $sql");
		$payoutInfo = mysql_fetch_assoc($result);
		$payoutInfo['en_info'] = etel_unserialize($payoutInfo['en_info']);
		if($payoutInfo['pa_ID']) 
		{ 
			if($payoutInfo['pa_status'] !='pending') return array('status'=>false,'msg'=>'This Payment Already Exists (Pending)');
			//$this->undo_transfer($payoutInfo['pa_ID']); // Why?
		}
		// Start Payout Data
		
		$data['final_status'] = 'payout_pending';
		$data['description'] = $payoutInfo['en_company']." - ".date('F jS Y',$date)." - Pending";
		$data['type'] = 'Payout';
		$data['en_ID'] = $en_ID;
		
		if($payoutInfo['pa_ID']) $data['pa_ID'] = $payoutInfo['pa_ID'];
		$proc_en_ID = $this->get_entity_id(array('en_ID'=>2));
		if(!$proc_en_ID) $proc_en_ID = $this->create_entity(array('en_ID'=>2,'en_type'=>'processor','en_type_ID'=>1,'en_company'=>"Etelegate.com",'en_gateway_ID'=>3));
		if(!$proc_en_ID) return array('status'=>false,'msg'=>'Could not get/create Processor ID');
		
		if($data['pending_only']) 
		{
			if($payoutInfo['pa_ID']) return array('status'=>false,'msg'=>'This Payment Already Exists as Pending ('.$payoutInfo['pa_status'].')');
			$this->commit_transfer($data,true);
			// Just create the entry as pending. The system will recalc the data later.
			return array('status'=>true,'msg'=>'Pending Payout Entry Created Successfully');
		}
		$ratesInfo = $this->get_MerchantRates($en_ID,array(0));

		// Wire/ACH Fee
		$usefee = 'wirefee';
		if($payoutInfo['en_info']['Payment_Data']['Method']=='ACH') $usefee = 'achfee';
		$newTransfer = array();
		$wirefee = $ratesInfo[0]['default']['Processor'][$usefee];
		$newTransfer['amount'] = $wirefee;
		$newTransfer['transfer_type'] = 'Funds Transfer Fee';
		$newTransfer['from_entity'] = $en_ID;
		$newTransfer['to_entity'] = $proc_en_ID;
		$newTransfer['date_effective'] = date('Y-m-d',$date);
		if($newTransfer['amount']>0) 
			$data['transfers'][] = $newTransfer;	
				
		$newTransfer = array();
		if($data['amount']) $newTransfer['amount'] = floatval($data['amount']);
		if(!$newTransfer['amount'] || $newTransfer['amount']<1)
		{
			$Profit = $this->get_profit(array('EffectiveOnly'=>$data['date_entered']),$en_ID);
			$newTransfer['amount'] = round($Profit['Total']['Amount'],2);
		}
		$newTransfer['amount'] -= $wirefee;
		if($newTransfer['amount']<50) // TODO: Rollover!
		{
			if($payoutInfo['pa_status'] !='pending' || !$payoutInfo['pa_ID'])
				return array('status'=>false,'msg'=>"Payout Failed. Amount (".$newTransfer['amount'].") < 50");
			if($newTransfer['amount']+$wirefee==0)
			{
				$sql = "delete from cs_profit_action where pa_ID = '".$payoutInfo['pa_ID']."' LIMIT 1";
				$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
				return array('status'=>false,'msg'=>"Payout Deleted. Amount (".($newTransfer['amount']+$wirefee).") = 0");
			}
			$desc = $payoutInfo['en_company']." - ".date('F jS Y',$date)." - RollOver ($".formatMoney($newTransfer['amount']+$wirefee).")";
			$sql = "Update cs_profit_action set pa_status = 'payout_rollover', pa_desc = '".quote_smart($desc)."' where pa_ID = '".$payoutInfo['pa_ID']."' LIMIT 1";
				$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
			return array('status'=>false,'msg'=>"Payout Rolled Over. Amount (".$newTransfer['amount'].") < 50");
		}
		$data['description'] = $payoutInfo['en_company']." - ".date('F jS Y',$date)." - $".formatMoney($newTransfer['amount']);
		$newTransfer['transfer_type'] = 'Payout';
		$newTransfer['from_entity'] = $en_ID;
		$newTransfer['to_entity'] = $proc_en_ID;
		$newTransfer['date_effective'] = date('Y-m-d 00:00:00',$date);
		$data['transfers'][] = $newTransfer;
		
	
		
		if($payoutInfo['pa_ID'])
			$this->undo_transfer($payoutInfo['pa_ID']);
		$payoutInfo['pa_ID'] = $this->commit_transfer($data);
		return array('status'=>true,'msg'=>"Pending Payout Recorded Successfully Amount (".$newTransfer['amount'].")",'pa_ID'=>$payoutInfo['pa_ID']);
	}
	
	function void_transaction_profit($id)
	{
		$sql = "SELECT td.*,pa_ID FROM cs_transactiondetails as td 
		left join `cs_profit_action` as pa on td.transactionId =  pa_trans_id
		WHERE transactionId = '$id'";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		if(!mysql_num_rows($result)) return array('status'=>false,'msg'=>"Could not get Transaction Info \n $sql");
		$transInfo = mysql_fetch_assoc($result);
			
		if(!$transInfo['pa_ID'])
			return array('status'=>false,'msg'=>'Could not get Transaction Profit');
		$this->undo_transfer($transInfo['pa_ID']);
		return array('status'=>true,'msg'=>'Transaction Voided Successfully');
	}
	
	function update_transaction_profit($id,$pending_only=false)
	{
		$sql = "SELECT td.*,bk.*,pa_ID FROM cs_transactiondetails as td 
		left join `cs_bank` as bk on td.bank_id = bk.bank_id
		left join `cs_profit_action` as pa on td.transactionId =  pa_trans_id
		WHERE transactionId = '$id'";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		if(!mysql_num_rows($result)) 
		{
			$sql = "
				UPDATE 
					cs_profit_action
					set pa_status='void'
				WHERE pa_trans_id = '$id'
				LIMIT 2
				";
			if($id) 
			{
				$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
				$count = mysql_num_rows($result);
			}
			
			return array('status'=>false,'msg'=>"Could not get Transaction Info. Voiding (".intval($count).") Transaction Profit Entries. \n $sql");
		}
		$transInfo = mysql_fetch_assoc($result);
		
		// Start Profit Data
		
		$data = array('description'=>"Transaction Profit for ".$transInfo['reference_number'],
			'bank_ID'=>$transInfo['bank_id'],
			'trans_ID'=>$transInfo['transactionId'],	
			'date_entered'=>$transInfo['transactionDate'],
			'type'=>'Transaction'
		);

		// Create/Update Required Entities
		
		$proc_en_ID = $this->get_entity_id(array('en_ID'=>2));
		if(!$proc_en_ID) $proc_en_ID = $this->create_entity(array('en_ID'=>2,'en_type'=>'processor','en_type_ID'=>1,'en_company'=>"Etelegate.com",'en_gateway_ID'=>3));
		if(!$proc_en_ID) return array('status'=>false,'msg'=>'Could not get/create Processor ID');
		
		$bank_en_ID = $this->get_entity_id(array('en_type'=>'bank','en_type_ID'=>$transInfo['bank_id']));
		if(!$bank_en_ID) $bank_en_ID = $this->create_entity(array('en_type'=>'bank','en_type_ID'=>$transInfo['bank_id'],'en_company'=>$transInfo['bank_name'],'en_gateway_ID'=>$transInfo['gateway_id']));
		if(!$bank_en_ID) return array('status'=>false,'msg'=>'Could not get/create Bank Entity ID');

		$merc_en_ID = $this->get_entity_id(array('en_type'=>'merchant','en_type_ID'=>$transInfo['userId']));
		if(!$merc_en_ID) return array('status'=>false,'msg'=>'Could not get Merchant Entity ID (userId = '.$transInfo['userId'].')');
		
		$data['en_ID'] = $merc_en_ID;
		
		
		if($pending_only) 
		{
			$this->commit_transfer($data,true);
			// Just create the entry as pending. The system will recalc the data later.
			return array('status'=>true,'msg'=>'Pending Profit Entry Created Successfully');
		}
		
		// Setup Rate Info
		
		if($this->cache[$transInfo['userId'].'-'.$transInfo['bank_id']])
			$rates = $this->cache[$transInfo['userId'].'-'.$transInfo['bank_id']];
		else
		{
			$rates = $this->get_MerchantRates($merc_en_ID,array($transInfo['bank_id']));
			$rates = $rates[$transInfo['bank_id']];
			if(!is_array($rates)) 
			{
				$rates = $this->get_MerchantRates($merc_en_ID,array(0)); // No rates? Try default rates.
				$rates = $rates[0];
			}
			if(!is_array($rates)) return array('status'=>false,'msg'=>'Invalid Merchant Bank Rates: '.$merc_en_ID);
			$this->cache[$transInfo['userId'].'-'.$transInfo['bank_id']] = $rates;
			if(sizeof($this->cache)>20) array_shift($this->cache);
		}
		
		// Update IDs
		
		$rates['default']['Bank']['to_entity'] = $bank_en_ID;
		
		// Commit Profit
		
		$TransferDefault = array(
			'transfer_type'=>'Sale Profit',
			//'date_entered'=>$transInfo['transactionDate'],
			'date_effective'=>$transInfo['transactionDate']
		);
		$RefundEffectiveDate = $transInfo['cancellationDate'];
		if(!$RefundEffectiveDate) $RefundEffectiveDate = $TransferDefault['date_effective'];
		
		$SaleAmount = $transInfo['amount']-$transInfo['td_customer_fee'];
		$BankReserve = $transInfo['amount']*($rates['default']['Bank']['rserv']/100);
		$ProcReserve = $SaleAmount*($rates['default']['Processor']['rserv']/100);
		$SaleTimeStamp = strtotime($transInfo['transactionDate']);
		
		// Approves Only
		
		if($transInfo['status']=='A')
		{
			// Processor Funds
			
			$newTransfer = $TransferDefault;
			$newTransfer['amount'] = $SaleAmount-$BankReserve;
			$newTransfer['transfer_type'] = 'Bank Sale Funds';
			$newTransfer['from_entity'] = $bank_en_ID;
			$newTransfer['to_entity'] = $proc_en_ID;
			$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24*intval($rates['default']['Bank']['hold'])));
			
			if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
			
			// Merchant Funds
			
			$newTransfer = $TransferDefault;
			$newTransfer['amount'] = $SaleAmount-$ProcReserve;
			$newTransfer['transfer_type'] = 'Sale Funds';
			$newTransfer['from_entity'] = $proc_en_ID;
			$newTransfer['to_entity'] = $merc_en_ID;
			$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24*intval($rates['default']['Processor']['hold'])));
			
			if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
			
			// TODO: Verify this logic
			// This section provides reserve and customer fees that do not get deleted later.
			
				// Bank Reserve
				
				$newTransfer = $TransferDefault;
				$newTransfer['amount'] = $BankReserve;
				$newTransfer['transfer_type'] = 'Bank Reserve Release';
				$newTransfer['from_entity'] = $bank_en_ID;
				$newTransfer['to_entity'] = $proc_en_ID;
				$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24 * 180)); // TODO: Dynamic Reserve Release 
				
				if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
				
				// Processor Reserve
				
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] = 'Reserve Release';
				$newTransfer['amount'] = $ProcReserve;
				$newTransfer['from_entity'] = $proc_en_ID;
				$newTransfer['to_entity'] = $merc_en_ID;
				$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24 * 180)); // TODO: Dynamic Reserve Release
				
				if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;		
				
				// Customer Fee
				
				if($transInfo['td_customer_fee'])
				{
					$newTransfer = $TransferDefault;
					$newTransfer['transfer_type'] = 'Bank Customer Fee';
					$newTransfer['amount'] = $transInfo['td_customer_fee'];
					$newTransfer['from_entity'] = $bank_en_ID;
					$newTransfer['to_entity'] = $proc_en_ID; // Only processors get customer fees
					$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24*intval($rates['default']['Bank']['hold'])));
					
					if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
				}
			
			// Refund/Chargeback Amount
			if($transInfo['td_is_chargeback']=='1' || $transInfo['cancelstatus']=='Y')
			{
				// Bank
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] = 'Bank Refund/CB Amount';
				$newTransfer['amount'] = $SaleAmount-$BankReserve;
				$newTransfer['from_entity'] = $proc_en_ID;
				$newTransfer['to_entity'] = $bank_en_ID;
				$newTransfer['date_effective'] = $RefundEffectiveDate;
				
				if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
				
				// Processor
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] = 'Refund/CB Amount';
				$newTransfer['amount'] = $SaleAmount-$ProcReserve;
				$newTransfer['from_entity'] = $merc_en_ID;
				$newTransfer['to_entity'] = $proc_en_ID;
				$newTransfer['date_effective'] = $RefundEffectiveDate;
				
				if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
	
				// TODO: Verify this logic
				// This section returns reserve and customer fees on refund instead of just deleting the entry.
	
					// Bank Reserve Returned on refund
					
					$newTransfer = $TransferDefault;
					$newTransfer['amount'] = $BankReserve;
					$newTransfer['transfer_type'] = 'Bank Reserve Release Returned';
					$newTransfer['from_entity'] = $proc_en_ID;
					$newTransfer['to_entity'] = $bank_en_ID;
					$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24 * 180)); // TODO: Dynamic Reserve Release 
					
					if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
					
					// Processor Reserve Returned on refund
					
					$newTransfer = $TransferDefault;
					$newTransfer['transfer_type'] = 'Reserve Release Returned';
					$newTransfer['amount'] = $ProcReserve;
					$newTransfer['from_entity'] = $merc_en_ID;
					$newTransfer['to_entity'] = $proc_en_ID;
					$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24 * 180)); // TODO: Dynamic Reserve Release 
					
					if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;		
					
					// Customer Fee Returned on refund
					
					if($transInfo['td_customer_fee'])
					{
						$newTransfer = $TransferDefault;
						$newTransfer['transfer_type'] = 'Bank Customer Fee Returned';
						$newTransfer['amount'] = $transInfo['td_customer_fee'];
						$newTransfer['from_entity'] = $proc_en_ID;
						$newTransfer['to_entity'] = $bank_en_ID; // Only processors get customer fees
						$newTransfer['date_effective'] = $RefundEffectiveDate;
						
						if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
					}
	
	
			}
		}
		// Affiliate/Processor/Bank Fees
		
		foreach($rates['default'] as $payee => $rateInfo)
		{
			if(strpos($payee,"Affiliate")=== 0)
			{
				if(!$transInfo['td_is_affiliate']) continue;
			 	if($payee != "Affiliate_".$transInfo['td_is_affiliate']) continue;
				// Skip Affiliates who didn't make this sale.
				$rateInfo['to_entity'] = $transInfo['td_is_affiliate'];
				$rateInfo['from_entity'] = $merc_en_ID;
			}	
			
			// Transaction Fee
			if($rateInfo['trans']) 
			{
				$newTransfer = $TransferDefault;
				$newTransfer['transfer_type'] =  ($payee=='Bank'?'Bank ':'').'Transaction Fee';
				$newTransfer['amount'] = ($transInfo['status']=='A'?$rateInfo['trans']:$rateInfo['decln']);
				$newTransfer['from_entity'] = $rateInfo['from_entity'];
				$newTransfer['to_entity'] = $rateInfo['to_entity'];
				$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24*intval($rates['default'][ ($payee=='Bank'?'Bank':'Processor') ]['hold'])));
				
				if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
			}
			
			if($transInfo['status']=='A')
			{
							
				// Discount Fee
				if($rateInfo['disct'])
				{
					$newTransfer = $TransferDefault;
					$newTransfer['transfer_type'] = ($payee=='Bank'?'Bank ':'').'Discount Fee';
					$newTransfer['amount'] = $SaleAmount*($rateInfo['disct']/100);
					$newTransfer['from_entity'] = $rateInfo['from_entity'];
					$newTransfer['to_entity'] = $rateInfo['to_entity'];
					$newTransfer['date_effective'] = date('Y-m-d G:i:s',$SaleTimeStamp+(60*60*24*intval($rates['default'][ ($payee=='Bank'?'Bank':'Processor') ]['hold'])));
					
					if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
				}
				
				// Refund Fee
				if($rateInfo['refnd'] && $transInfo['cancelstatus']=='Y')
				{
					$newTransfer = $TransferDefault;
					$newTransfer['transfer_type'] = ($payee=='Bank'?'Bank ':'').'Refund Fee';
					$newTransfer['amount'] = $rateInfo['refnd'];
					$newTransfer['from_entity'] = $rateInfo['from_entity'];
					$newTransfer['to_entity'] = $rateInfo['to_entity'];
					$newTransfer['date_effective'] = $RefundEffectiveDate;
					
					if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
				}
			
				// Chargeback Fee
				if($rateInfo['chgbk'] && $transInfo['td_is_chargeback']=='1')
				{
					$newTransfer = $TransferDefault;
					$newTransfer['transfer_type'] = ($payee=='Bank'?'Bank ':'').'Chargeback Fee';
					$newTransfer['amount'] = $rateInfo['chgbk'];
					$newTransfer['from_entity'] = $rateInfo['from_entity'];
					$newTransfer['to_entity'] = $rateInfo['to_entity'];
					$newTransfer['date_effective'] = $RefundEffectiveDate;
					
					if($newTransfer['amount']>0) $data['transfers'][] = $newTransfer;
				}
			}
		}
		if($transInfo['pa_ID'])
			$this->undo_transfer($transInfo['pa_ID']);
		$this->commit_transfer($data);
		return array('status'=>true,'msg'=>'Profit Recorded Successfully');

	}
	
	function undo_transfer($pa_ID)
	{
		$sql = "
		DELETE FROM 
			cs_profit
		WHERE pt_action_ID = '$pa_ID'
		";
		sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		
		$sql = "
		UPDATE 
			cs_profit_action
			set pa_status='void'
		WHERE pa_ID = '$pa_ID'
		";
		sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		if(mysql_affected_rows()>0)
			return array('status'=>true,'msg'=>'Entry Voiced Successfully');
		return array('status'=>false,'msg'=>'No Changes Detected: '.$sql);
	}
	
	function commit_transfer($data,$pending_only=false)
	{
		$add_sql = "";
		if($data['pa_ID']) $add_sql .= "pa_ID = '".$data['pa_ID']."',\n";
		if($data['en_ID']) $add_sql .= "pa_en_ID = '".$data['en_ID']."',\n";
		if($data['bank_ID']) $add_sql .= "pa_bank_id = '".$data['bank_ID']."',\n";
		if($data['trans_ID']) $add_sql .= "pa_trans_id = '".$data['trans_ID']."',\n";
		if($data['type']) $add_sql .= "pa_type = '".$data['type']."',\n";
		if($data['date_entered'] && strtotime($data['date_entered']) && ($data['date_entered']!= '0000-00-00')) 
			$add_sql .= "pa_date = '".$data['date_entered']."',\n";
		else $add_sql .= "pa_date = now(),\n";
		
		$transfers = $data['transfers'];
		unset($data['transfers']);
		if($data['information']) $add_sql .= "pa_info = '".quote_smart(serialize($data['information']))."',\n";
		
		$sql_set = "
			$add_sql
			pa_status = 'pending',
			pa_desc = '".quote_smart($data['description'])."'
			
			";
		
		$sql = "
		Insert into 
			cs_profit_action
		SET $sql_set
			ON DUPLICATE KEY 
		UPDATE $sql_set
			
		";
		
		$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		$pa_ID = mysql_insert_id();
		
		if(!$result || !$pa_ID)
			dieLog(mysql_error() . " ~ $sql");
	
		//$owed_updates = array();
	
		if($transfers)
		{
			sql_query_write("Start Transaction") or dieLog(mysql_error());					
			foreach($transfers as $transfer)
			{
				$transfer['amount'] = floatval($transfer['amount']);
				if($transfer['amount'] <= 0)
					toLog('erroralert','customer',"Profit Transfer Error: Amount <=0 ".print_r($transfer,true));
					
				if($transfer['from_entity'] < 1 || $transfer['to_entity'] < 1)
					toLog('erroralert','customer',"Profit Transfer Error: Entity ID < 1 ".print_r($transfer,true));
					
				if(!$transfer['date_effective'] || !strtotime($transfer['date_effective']) || ($data['date_effective'] == '0000-00-00'))
					$transfer['date_effective'] = date('Y-m-d G:i:s');
					
				if(!$transfer['transfer_type'])
					$transfer['transfer_type'] = 'General';
				
				
				$params = array();
				
				$params['pt_action_ID'] = $pa_ID;
				$params['pt_type'] = $transfer['transfer_type'];
				//$params['pt_bank_ID'] = $transfer['bank_ID'];
				//$params['pt_trans_ID'] = $transfer['trans_ID'];
				//$params['pt_date_entered'] = $transfer['date_entered'];
				$params['pt_date_effective'] = $transfer['date_effective'];
				
		
				// Transfer From - Not applicable anymore.
				/*
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
				*/
				// Transfer To			
				
				$params['pt_amount'] = $transfer['amount'];
				$params['pt_to_entity_ID'] = $transfer['to_entity'];
				$params['pt_from_entity_ID'] = $transfer['from_entity'];
				
				//$owed_updates[$transfer['to_entity']] = floatval($owed_updates[$transfer['to_entity']])+$transfer['amount'];
				//$owed_updates[$transfer['from_entity']] = floatval($owed_updates[$transfer['from_entity']])-$transfer['amount'];
				
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
			sql_query_write("Commit") or dieLog(mysql_error());
		}
		
		
		if(!$pending_only)
		{
			$final_status = 'success';
			if($data['final_status']) $final_status = $data['final_status'];
			$sql = "
				Update 
					cs_profit_action
				Set
					pa_status = '$final_status'
				Where
					pa_ID = '$pa_ID'
				";
				
			$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		}
		
		//foreach($owed_updates as $en_ID => $owed)
		//{
		//	$sql = "Update cs_entities set en_owed = if(en_owed,en_owed,0)+(".$owed.") where en_ID = '$en_ID'";
		//	$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		//}
		return $pa_ID;
	}
	
	function recalc_profit($conditions,$force=false)
	{
		if(!is_array($conditions['where'])) return array('status'=>false,'msg'=>'Invalid Conditions');
		if($conditions['where'])
			foreach($conditions['where'] as $key=>$val)
				$where_sql .= " AND $key = '".quote_smart($val)."' "; 
		$sql = "SELECT transactionId FROM `cs_transactiondetails` where 1 $where_sql limit 50000";
		$result = sql_query_read($sql) or dieLog($sql);
		while($transInfo = mysql_fetch_assoc($result))
		{
			$r = $this->update_transaction_profit($transInfo['transactionId'],!$force);
			if(!$r['status'])
				return array('status'=>false,'msg'=>'Error: '.print_r($r));
		}
		return array('status'=>true,'msg'=>'Recalculation Complete');
	}
	
	function get_profit($conditions,$entityId)
	{
		if(!is_array($conditions)) return array('status'=>false,'msg'=>'Invalid Conditions');
		if(!$entityId) return array('status'=>false,'msg'=>'Invalid Entity ID');
		$profit = array('Deductions'=>array(),'Revenue'=>array(),'ByDate'=>array(),'Total'=>array());
		$modes = array('Revenue'=>array('entity_source'=>'pt_to_entity_ID','sign'=>''), 'Deductions'=>array('entity_source'=>'pt_from_entity_ID','sign'=>'-'));
		
		if($conditions['where_trans'])
			foreach($conditions['where_trans'] as $key=>$val)
				$where_trans_sql .= " AND $key = '".quote_smart($val)."' "; 
				
		if($conditions['where'])
			foreach($conditions['where'] as $key=>$val)
				$where_sql .= " AND $key = '".quote_smart($val)."' "; 
		
		if($conditions['hidepayout'])
			$where_sql .= " AND pt_type != 'Payout' "; 
				
		if($conditions['date_between'])
			$where_sql .= " AND pt_date_effective Between '".quote_smart($conditions['date_between']['Start'])."' AND '".quote_smart($conditions['date_between']['End'])."' "; 
		
		if($conditions['EffectiveOnly'] !== false)
		{
			$pt_date_effective = ($conditions['EffectiveOnly']?strtotime($conditions['EffectiveOnly']):time());
			$where_sql .= " AND pt_date_effective <= '".date('Y-m-d',$pt_date_effective)."' "; 
		}	
		
		foreach($modes as $mode=>$data)
		{
			$sql_mode = "Select SQL_CACHE '$mode' as Mode,pt_type as Type,".$data['sign']."sum(pt_amount) as Amount, count(*) as Count, pt_date_effective as Date from 
			cs_profit as pt\n";
			
			if($conditions['where_trans'])
				$sql_mode .= ", (select pa_ID from cs_profit_action as pa LEFT JOIN cs_transactiondetails as td on transactionId = pa_trans_id where 1 $where_trans_sql) as t";
			  
			$sql_mode .= " WHERE 1 $where_sql";
			
			if($conditions['where_trans'])
				$sql_mode .= " AND pa_ID = pt_action_ID ";
				
			$sql_mode .= " AND (".$data['entity_source']." = '$entityId') Group by pt_type";
			
			if($conditions['group_date'])
				$sql_mode .= ",pt_date_effective order by pt_date_effective asc";
				
			$sql .= ($sql?' Union ':'')."( $sql_mode )";
		}
		
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql_rev");
		if(!mysql_num_rows($result)) return array('status'=>false,'msg'=>'Could not get Transaction Profit Info');
		while($row = mysql_fetch_assoc($result))
		{
			$profit['Total']['Amount'] += $row['Amount'];
			$profit['Total']['Count'] += $row['Count'];
			$profit[$row['Mode']]['Total']['Amount'] += $row['Amount'];
			$profit[$row['Mode']]['Total']['Count'] += $row['Count'];
			$profit[$row['Mode']][$row['Type']]['Amount'] += $row['Amount'];
			$profit[$row['Mode']][$row['Type']]['Count'] += $row['Count'];
			
			if($conditions['group_date'])
			{
				$profit['ByDate'][$row['Date']]['Total']['Amount'] += $row['Amount'];
				$profit['ByDate'][$row['Date']]['Total']['Count'] += $row['Count'];
				$profit['ByDate'][$row['Date']][$row['Mode']]['Total']['Amount'] += $row['Amount'];
				$profit['ByDate'][$row['Date']][$row['Mode']]['Total']['Count'] += $row['Count'];
				$profit['ByDate'][$row['Date']][$row['Mode']][$row['Type']]['Amount'] = $row['Amount'];
				$profit['ByDate'][$row['Date']][$row['Mode']][$row['Type']]['Count'] = $row['Count'];
			}
			else unset($profit['ByDate']);
		}
		$profit['status']=true;
		return $profit;
	}
	
	function get_payouts($conditions,$entityId)
	{
		if(!is_array($conditions)) return array('status'=>false,'msg'=>'Invalid Conditions');
		if(!$entityId) return array('status'=>false,'msg'=>'Invalid Entity ID');
		
		if($conditions['date_between'])
			$where_sql .= " AND pa_date Between '".quote_smart($conditions['date_between']['Start'])."' AND '".quote_smart($conditions['date_between']['End'])."' "; 
		if($conditions['pa_ID'])
			$where_sql .= " AND pa_ID = '".intval($conditions['pa_ID'])."' "; 
			
		$sql = "Select pa.*,pt_amount as Amount, DATE_FORMAT(pa_date, '%Y-%m-%d') as ByDate
		from cs_profit_action as pa
		left join cs_profit on pa_ID = pt_action_ID and pt_type = 'Payout'
		WHERE pa_type='Payout' and pa_en_ID = '$entityId' $where_sql
		ORDER BY pa_date desc	
		";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql_rev");
		if(!mysql_num_rows($result)) return array('status'=>false,'msg'=>'Could not get Transaction Profit Info');
		while($row = mysql_fetch_assoc($result))
		{
			$row['pa_info'] = etel_unserialize($row['pa_info']);
			$Payouts[$row['ByDate']] = $row;
		}
		return $Payouts;
	}
	
	function gen_row($change = false)
	{
		if(!$this->global_row) $this->global_row = 1;
		if($change) $this->global_row = 3-$this->global_row;
		return $this->global_row;
	}
}

?>