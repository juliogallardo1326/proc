<?
//require_once('entities.class.php');
require_once("subFunctions/rates_fees.php");
class profit_class
{

	var $rates;
	//var $entities;
	
	function profit_class()
	{
		$this->rates =  new rates_fees();
		//$this->entities = new entities_class();
	}
	
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
		
		// Merchant Profit
	//	$newTransfer = array('amount' => $transInfo['amount']-$transInfo['td_customer_fee'],'from_entity'=>$bank_en_ID,'to_entity'=>$merc_en_ID,'date_effective'=>
		
		$data['transfers'][] = $newTransfer;
		
		// Merchant Profit
		echo $proc_en_ID;
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
	
	function post_sale($trans_info,$bank_info)
	{
		$user_id = $trans_info['user_id'];
		$bank_id = $trans_info['bank_id'];
		$trans_id = $trans_info['trans_id'];
		$date_entered = $trans_info['date_entered'];
		$amount = $trans_info['amount'];
		$status = $trans_info['status'];
			
		$proc_fee_trans = $status == 'A' ? $bank_info['default']['Processor']['trans'] : 0;
		$proc_fee_trans = $status == 'D' ? $bank_info['default']['Processor']['decln'] : 0;
		$proc_fee_disct = $status == 'A' ? number_format($bank_info['default']['Processor']['disct'] / 100 * $amount,2) : 0;
		$proc_fee_cstsv = $status == 'A' ? $bank_info['default']['Processor']['cstsv'] : 0;
		$proc_fee_rserv = $status == 'A' ? number_format($bank_info['default']['Processor']['rserv'] / 100 * $amount,2) : 0;

		$bank_fee_trans = $status == 'A' ? $bank_info['default']['Bank']['trans'] : 0;
		$bank_fee_trans = $status == 'D' ? $bank_info['default']['Bank']['decln'] : 0;
		$bank_fee_disct = $status == 'A' ? $bank_info['default']['Bank']['disct'] / 100 * $amount : 0;
			
		$params['date_entered'] = $date_entered;
		$params['bank_ID'] = $bank_id;
		$params['trans_ID'] = $trans_id;
		$params['invoice_ID'] = 0;
		$params['info'] = "";
					
		$params['type'] = "sale";

		$params['amount'] = $amount;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = -$amount;
		$params['entity_ID'] = $this->entities->get_entity_id("bank",$bank_id);
		$this->post_profit($params);
		
		$params['type'] = "transaction fee";

		$params['amount'] = -$proc_fee_trans;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = $proc_fee_trans;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);		

		$params['type'] = "discount fee";

		$params['amount'] = -$proc_fee_disct;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = $proc_fee_disct;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);
		
		$params['type'] = "customer service fee";

		$params['amount'] = -$proc_fee_cstsv;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = $proc_fee_cstsv;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);

		$params['type'] = "reserve";

		$params['amount'] = -$proc_fee_rserv;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = $proc_fee_rserv;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);

		$params['type'] = "bank transaction fee";

		$params['amount'] = $bank_fee_trans;
		$params['entity_ID'] = $this->entities->get_entity_id("bank",$bank_id);
		$this->post_profit($params);

		$params['amount'] = -$bank_fee_trans;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);

		$params['type'] = "bank discount fee";

		$params['amount'] = $bank_fee_disct;
		$params['entity_ID'] = $this->entities->get_entity_id("bank",$bank_id);
		$this->post_profit($params);

		$params['amount'] = -$bank_fee_disct;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);
	}
	
	function post_refund($trans_info,$bank_info)
	{
		$user_id = $trans_info['user_id'];
		$bank_id = $trans_info['bank_id'];
		$trans_id = $trans_info['trans_id'];
		$date_entered = $trans_info['date_entered'];
		$amount = $trans_info['amount'];

		$proc_fee_refnd = $bank_info['default']['Processor']['refnd'];
		$bank_fee_refnd = $bank_info['default']['Bank']['refnd'];

		$params['date_entered'] = $date_entered;
		$params['bank_ID'] = $bank_id;
		$params['trans_ID'] = $trans_id;
		$params['invoice_ID'] = 0;
		$params['info'] = "";

		$params['type'] = "refund";
		
		$params['amount'] = -$amount;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = $amount;
		$params['entity_ID'] = $this->entities->get_entity_id("bank",$bank_id);
		$this->post_profit($params);
		
		$params['type'] = "refund fee";

		$params['amount'] = -$proc_fee_refnd;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = $proc_fee_refnd;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);		

		$params['type'] = "bank refund fee";

		$params['amount'] = $bank_fee_refnd;
		$params['entity_ID'] = $this->entities->get_entity_id("bank",$bank_id);
		$this->post_profit($params);

		$params['amount'] = -$bank_fee_refnd;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);		
	}	
	
	function post_chargeback($trans_info,$bank_info)
	{
		$user_id = $trans_info['user_id'];
		$bank_id = $trans_info['bank_id'];
		$trans_id = $trans_info['trans_id'];
		$date_entered = $trans_info['date_entered'];
		$amount = $trans_info['amount'];

		$proc_fee_chgbk = $bank_info['default']['Processor']['chgbk'];
		$bank_fee_chgbk = $bank_info['default']['Bank']['chgbk'];

		$params['date_entered'] = $date_entered;
		$params['bank_ID'] = $bank_id;
		$params['trans_ID'] = $trans_id;
		$params['invoice_ID'] = 0;
		$params['info'] = "";

		$params['type'] = "chargeback";
		
		$params['amount'] = -$amount;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = $amount;
		$params['entity_ID'] = $this->entities->get_entity_id("bank",$bank_id);
		$this->post_profit($params);
		
		$params['type'] = "chargeback fee";

		$params['amount'] = -$proc_fee_chgbk;
		$params['entity_ID'] = $this->entities->get_entity_id("merchant",$user_id);
		$this->post_profit($params);

		$params['amount'] = $proc_fee_chgbk;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);		

		$params['type'] = "bank chargeback fee";

		$params['amount'] = $bank_fee_chgbk;
		$params['entity_ID'] = $this->entities->get_entity_id("bank",$bank_id);
		$this->post_profit($params);

		$params['amount'] = -$bank_fee_chgbk;
		$params['entity_ID'] = $this->entities->get_entity_id_by_name("processor","etelegate");
		$this->post_profit($params);		
	}	
		
	function post_profit($params)
	{
		if($params['amount'] == 0)
			return;
			
		$params['date_entered'] = strtotime(str_replace("-","/",$params['date_entered']));
		$params['date_owed'] = $params['date_entered'] + 60*60*24*30; //30 day delay for payment
		
		$sql_set = "";
		foreach($params as $p_name => $p_value)
			$sql_set .= ($sql_set == "" ? "" : ",\r\n") . "pt_" . $p_name . " = '" . quote_smart($p_value) . "'";
		
		$sql = "
			INSERT INTO
				cs_profit
			SET
				$sql_set
			ON DUPLICATE KEY UPDATE
				$sql_set
		";
		$res = sql_query_read($sql);
		if(!$res)
			echo mysql_error() . "<pre>$sql</pre>";
		
	}
	
	function trans_profit($trans_id)
	{
		$sql = "
			SELECT
				transactionId AS trans_id,
				userId AS user_id,
				bank_id AS bank_id,
				transactionDate AS date_entered,
				amount AS amount,
				status AS status,
				td_is_chargeback AS chargeback,
				IF(cancelstatus = 'Y',1,0) AS refund
			FROM
				cs_transactiondetails
			WHERE
				transactionId = '$trans_id'
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$trans_info = mysql_fetch_assoc($res);
		
		$user_id = $trans_info['user_id'];
		$bank_id = $trans_info['bank_id'];
		$chargeback = $trans_info['chargeback'];
		$refund = $trans_info['refund'];
		$date_entered = $trans_info['date_entered'];
		$amount = $trans_info['amount'];
				
		$mer_rates = $this->rates->get_MerchantRates($user_id,$bank_id);
		
		echo "<pre>
			<b>Details</b>
				User id: $user_id
				Bank id: $bank_id
				Date: $date_entered
				Amount: $amount
		</pre>
		";
		
		list($bank_name,$bank_info) = each($mer_rates);
		
		$this->post_sale($trans_info,$bank_info);

		if($refund == 1)
			$this->post_refund($trans_info,$bank_info);

		if($chargeback == 1)
			$this->post_chargeback($trans_info,$bank_info);
	}
	
	function compile_profit()
	{
		$sql = "
			SELECT
				transactionId
			FROM
				cs_transactiondetails
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		while($r = mysql_fetch_assoc($res))
			$this->trans_profit($r['transactionId']);
	}
	
	function get_ledger_reconcile_entity($entity_id)
	{
		$sql = "
			SELECT
				et_type
			FROM
				cs_entities
			WHERE
				et_ID = '$entity_id'
		";
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");

		$trans = "";

		$r = mysql_fetch_assoc($res);
		if(!strcasecmp($r['et_type'],"merchant"))
		{
			$sql = "
				SELECT
					DISTINCT (pt_trans_ID)
				FROM
					cs_profit
				WHERE
					pt_entity_ID = '$entity_id'
			";
	
			$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
			$trans = "";
			while($r = mysql_fetch_assoc($res))
				$trans .= ($trans != "" ? ", " : "") . $r['pt_trans_ID'];
	
			$trans = "WHERE pt_trans_ID IN ($trans)";		
		}

		$sql = "
			SELECT
				pt_type,
				COUNT(*) AS count,
				SUM((pt_amount < 0) * pt_amount) AS debit,
				SUM((pt_amount > 0) * pt_amount) AS credit
			FROM
				cs_profit
			$trans
			GROUP BY 
				pt_type
		";

		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$reconcile = array();
		while($r = mysql_fetch_assoc($res))
			$reconcile[$r['pt_type']] = $r;
		return $reconcile;
	}
	
	
	function get_ledger_reconcile()
	{
		$sql = "
			SELECT
				pt_type,
				COUNT(*) AS count,
				SUM((pt_amount < 0) * pt_amount) AS debit,
				SUM((pt_amount > 0) * pt_amount) AS credit
			FROM
				cs_profit
			GROUP BY 
				pt_type
		";

		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$reconcile = array();
		while($r = mysql_fetch_assoc($res))
			$reconcile[$r['pt_type']] = $r;
		return $reconcile;
	}
	
	function get_entity_ledger($entity_id,$date_from,$date_to,$start=0,$limit=100)
	{
		if($start == "") $start = 0;
		
		$sql = "
			SELECT 
				COUNT(*) AS count,
				SUM((pt_amount < 0) * pt_amount) AS debit,
				SUM((pt_amount > 0) * pt_amount) AS credit
			FROM
				cs_profit
			WHERE
				pt_entity_ID = '$entity_id'
				AND pt_date_entered BETWEEN $date_from AND $date_to
		";

		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$summary = mysql_fetch_assoc($res);
		
		$sql = "
			SELECT 
				pt_type,
				COUNT(*) AS count,
				SUM((pt_amount < 0) * pt_amount) AS debit,
				SUM((pt_amount > 0) * pt_amount) AS credit
			FROM
				cs_profit
			WHERE
				pt_entity_ID = '$entity_id'
				AND pt_date_entered BETWEEN $date_from AND $date_to
			GROUP BY 
				pt_type
		";

		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$summary_details = array();
		while($r = mysql_fetch_assoc($res))
			$summary_details[$r['pt_type']] = $r;
				
		$sql = "
			SELECT 
				*
			FROM
				cs_profit
			WHERE
				pt_entity_ID = '$entity_id'
				AND pt_date_entered BETWEEN $date_from AND $date_to
			ORDER BY
				pt_date_entered DESC
			LIMIT
				$start,$limit
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$ledger = array();
		while($r = mysql_fetch_assoc($res))
			$ledger[] = $r;
		return array("summary" =>$summary,"summary_details" =>$summary_details,"ledger"=>$ledger);
	}
}
?>