<?

class rebill_class
{
	var $userid;
	var $subaccount;
	var $banks;
	var $siteid;
	var $sort_by;
	var $access_level;
	
	var $text_rebill_type;
	var $sql_rebill_type;
	
	var $transactions;
	var $rebill_details;
	var $rebill_summary;
	var $status_summary;

	var $hide_duplicates;
	
	var $min_rebill_date;
	var $max_rebill_date;
	
	var $date_from;
	var $date_to;
	
	var $account_status;
	var $rebill_status;
	var $frozen_status;
	
	var $search_limit;
	var $search_offset;
	
	var $PHP_SELF;
	var $trans_params;
	var $rebill_params;
	
	function rebill_class($userid = NULL,$subaccount=NULL,$siteid=NULL)
	{
		$this->PHP_SELF = $_SERVER['PHP_SELF'];
		$this->rebill_details = NULL;
		$this->rebill_summary = NULL;
		$this->transactions = NULL;

		$this->set_user($userid);
		$this->set_subaccount($subaccount);
		$this->set_pending_processed(TRUE,TRUE);
		$this->set_site_id($siteid);
		$this->set_rebill_status(NULL);
		$this->set_hide_dupes(NULL);
		$this->set_access(NULL);
		$this->set_account_status(NULL);
		$this->set_date_range(NULL,NULL);
		$this->set_limit_offset(50,0);
		
		$this->sql_rebill_type = array(
				"active" => "sub.ss_rebill_status = 'active'",
				"inactive" => "sub.ss_rebill_status = 'inactive'"
				//"processing" => "sub.ss_rebill_status = 'processing'"
		);

		$this->text_rebill_type = array(
				"active" => "01|active",
				"inactive" => "02|inactive"
			//	"processing" => "03|processing"
		);
	}
	
	function set_limit_offset($limit,$offset)
	{
		if($limit > 500) $limit = 500;
		if($limit == NULL) $limit = 500;
		if($offset == NULL) $offset = 0;
		$this->search_limit = $limit;
		$this->search_offset = $offset;
	}

	function set_banks($banks)
	{
		if($banks == "") $banks = NULL;
		$this->banks = $banks;
	}

	function set_access($access)
	{
		if($access == "") $access = NULL;
		$this->access_level = $access;
	}
	
	function set_account_status($status)
	{
		if($status == "") $status = NULL;

		if($status!=NULL)
		if(!is_array($status))
			$display = array(strtolower($status));
		else
			foreach($status as $index => $limit)
				$display[$index] = strtolower($limit);

		$this->account_status = $display;
	}
	
	function set_frozen_status($status)
	{
		if($status == "") $status = NULL;

		if($status!=NULL)
		if(!is_array($status))
			$display = array(strtolower($status));
		else
			foreach($status as $index => $limit)
				$display[$index] = strtolower($limit);

		$this->frozen_status = $display;
	}
	
	function get_sql_where()
	{
		$sql_date = "";
		if($this->date_from != NULL && $this->date_to != NULL)
			$sql_date = "AND (UNIX_TIMESTAMP(sub.ss_rebill_next_date) BETWEEN " . $this->date_from . " AND " . $this->date_to . ")";

		$sql_status = "";		

		if($this->account_status != NULL)
			if(!is_array($this->account_status))
				$sql_status .= ($sql_status != "" ? "\r\n" : "") . "AND sub.ss_account_status = '" . $this->rebill_status . "'";
			else
			{
				$list = implode("','",$this->account_status);
				if($list != "")
					$sql_status .= ($sql_status != "" ? "\r\n" : "") . "AND sub.ss_account_status IN ('" . $list . "')";
			}

		if($this->rebill_status != NULL)
			if(!is_array($this->rebill_status))
				$sql_status .= ($sql_status != "" ? "\r\n" : "") . "AND sub.ss_rebill_status = '" . $this->rebill_status . "'";
			else
			{
				$list = implode("','",$this->rebill_status);
				if($list != "")
					$sql_status .= ($sql_status != "" ? "\r\n" : "") . "AND sub.ss_rebill_status IN ('" . $list . "')";
			}
		
		if($this->frozen_status != NULL)
			if(!is_array($this->frozen_status))
				$sql_status .= ($sql_status != "" ? "\r\n" : "") . "AND sub.ss_rebill_frozen = '" . $this->frozen_status . "'";
			else
			{
				$list = implode("','",$this->frozen_status);
				if($list != "")
					$sql_status .= ($sql_status != "" ? "\r\n" : "") . "AND sub.ss_rebill_frozen IN ('" . $list . "')";
			}
		
		$sql_bank = "";
		if($this->banks != NULL)
			if(!is_array($this->banks))
				$sql_bank = "AND sub.ss_bank_id = " . $this->banks;
			else
			{
				$list = implode(",",$this->banks);
				if($list != "")
					$sql_bank = "AND sub.ss_bank_id IN ($list) ";
			}

		if($this->subaccount != NULL)  
			if(!is_array($this->subaccount))
				$sql_subaccount = "AND sub.ss_rebill_id = " . $this->subaccount;
			else
			{
				$list = implode(",",$this->subaccount);
				if($list != "")
					$sql_subaccount = "AND sub.ss_rebill_id IN ($list) ";
			}
			
		if($this->userid != NULL)  
			if(!is_array($this->userid))
				$sql_user = "AND sub.ss_user_id = " . $this->userid;
			else
			{
				$list = implode(",",$this->userid);
				if($list != "")
					$sql_user = "AND sub.ss_user_id IN ($list) ";
			}
			
		if($this->siteid != NULL)  
			if(!is_array($this->siteid))
				$sql_user = "AND sub.ss_site_id = " . $this->siteid;
			else
			{
				$list = implode(",",$this->siteid);
				if($list != "")
					$sql_user = "AND sub.ss_site_id IN ($list) ";
			}
			
		
		return "
				AND rd_enabled = 'Yes'
				AND activeuser = '1'
				AND cs_verified in ('approved','non-compliant')
				$sql_user
				$sql_site
				$sql_subaccount
				$sql_status
				$sql_bank
				$sql_date
		";
	}
	
	function get_sql_from()
	{
		return "
				cs_subscription AS sub
				LEFT JOIN cs_rebillingdetails AS r ON r.rd_subaccount = sub.ss_rebill_id
				LEFT JOIN cs_transactiondetails AS t ON sub.ss_transaction_id = t.transactionid
				LEFT JOIN cs_companydetails AS cd on cd.userId = ss_user_ID
				LEFT JOIN cs_company_sites AS cs on cs_ID = ss_site_ID
		";
	}

	function get_sql_limit()
	{
		$limit = "";
		
		if($this->search_limit != 0)
		if($this->search_limit != "")
		{
			$limit = "LIMIT " . $this->search_offset;
			if($this->search_limit)
				$limit .= ", " . $this->search_limit;
				
		}
		return $limit;
	}

	function request_params($limit=NULL)
	{
		$params = "";
		foreach($_REQUEST as $var => $val)
		if($limit == NULL || stristr($var,$limit)!== FALSE)
			if(!is_array($val))
				$params .= ($params == "" ? "" : "&") . $var . "=" . urlencode($val);
			else
				foreach($val as $index => $v)
					$params .= ($params == "" ? "" : "&") . $var . "[]=" . urlencode($v);
		return $params;
	}

	function request_form($limit=NULL)
	{
		$params = "";
		foreach($_REQUEST as $var => $val)
		if($limit == NULL || stristr($var,$limit)!== FALSE)
			if(!is_array($val))
				$params .= "<input type='hidden' name='$var' value='" . $val . "'>";
			else
				foreach($val as $index => $v)
					$params .= "<input type='hidden' name='" . $var. "[]' value='" . $v . "'>";
		return $params;
	}
	
	function array_print($text)
	{
		echo "<table width='100%'><tr><td><pre>";
		print_r($text);
		echo "</pre></td></tr></table>";
	}
	
	function set_date_range($from,$to)
	{
		if($from != NULL)
			$from = strtotime(date("m/d/Y 00:00:00",strtotime($from)));
		if($to != NULL)
			$to = strtotime(date("m/d/Y 23:59:59",strtotime($to)));
		
		$this->date_from=$from;
		$this->date_to=$to;
	}

	function set_month($month,$year)
	{
		if($month != NULL && $year != NULL)
		{
			$from = strtotime(date("m/01/Y 00:00:00",strtotime("$month/01/$year 00:00:00")));
			$to = strtotime(date("m/t/Y 23:59:59",strtotime("$month/01/$year 00:00:00")));
			$this->date_from=$from;
			$this->date_to=$to;
		}
	}
	
	function set_hide_dupes($hide_duplicates)
	{
		if($hide_duplicates == "") $hide_duplicates = NULL;
		$this->hide_duplicates = $hide_duplicates;
	}
		
	function set_rebill_status($limit_display)
	{
		if($limit_display == "") $limit_display = NULL;

		if($limit_display!=NULL)
		if(!is_array($limit_display))
			$display = array(strtolower($limit_display));
		else
			foreach($limit_display as $index => $limit)
				$display[$index] = strtolower($limit);

		$this->rebill_status = $display;
	}

	function set_pending_processed($pending,$processed)
	{
		if($pending == "") $pending = NULL;
		if($processed == "") $processed = NULL;

		$this->show_pending = $pending;
		$this->show_processed = $processed;
	}
	
	function set_sort_by($sort_by)
	{
		if($sort_by == "") $sort_by = NULL;
		$this->sort_by = $sort_by;
	}
	
	function set_user($userid)
	{
		if($userid == "") $userid = NULL;
		$this->userid = $userid;
	}

	function set_subaccount($subaccount)
	{
		if($subaccount == "") $subaccount = NULL;
		$this->subaccount = $subaccount;
	}
	
	function set_subaccount_byname($subaccount)
	{
		if($subaccount == "") 
			$subaccount = NULL;
		else
			$subaccount = $this->get_subaccount_id($subaccount);
		
		$this->subaccount = $subaccount;
	}
	
	function set_site_id($siteid)
	{
		if($siteid == "") $siteid = NULL;
		$this->siteid = $siteid;
	}
	
	function cancel_rebills($sub_ids,$sub_notes="",$sub_status_text="")
	{
		if(!isset($this->userid) && $this->access_level != "admin")
			return;
		$status = array();
		foreach($sub_ids as $key => $id)
		{
			$status[] = subscription_cancel($id,$this->userid,$sub_notes,$sub_status_text);
		}
	}
	
	function get_rebill_info()
	{
		if($this->userid != NULL)  
			if(!is_array($this->userid))
				$sql_user = "AND r.company_user_id = " . $this->userid;
			else
			{
				$list = implode(",",$this->userid);
				if($list != "")
					$sql_user = "AND r.company_user_id IN ($list) ";
			}
			
		$sql = "
			SELECT
				r.rd_subname,
				r.rd_subaccount
			FROM
				cs_rebillingdetails AS r
			WHERE
				1
				$sql_user
			ORDER BY
				LOWER(r.rd_subname)
		";
		$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");

		$list = array();

		while($row = mysql_fetch_assoc($res))
			$list[] = $row;
		return $list;
	}
	
	function get_subaccount_id($subaccount)
	{
		if($this->userid != NULL)  
			if(!is_array($this->userid))
				$sql_user = "AND r.company_user_id = " . $this->userid;
			else
			{
				$list = implode(",",$this->userid);
				if($list != "")
					$sql_user = "AND r.company_user_id IN ($list) ";
			}
			
		$sql = "
			SELECT
				r.rd_subaccount
			FROM
				cs_rebillingdetails AS r
			WHERE
				1
				$sql_user
				AND r.rd_subname = '$subaccount'
		";
		$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");
		$r = mysql_fetch_assoc($res);
		$id = isset($r['rd_subaccount']) ? $r['rd_subaccount'] : -1;
		return $id;
	}
	
	function get_status_summary()
	{
		$temp_acc_status = $this->account_status;
		$temp_reb_status = $this->rebill_status;
		
		$this->account_status = "";
		$this->rebill_status = "";
		
		$sql_where = $this->get_sql_where();
		$sql_from = $this->get_sql_from();

		$this->account_status = $temp_acc_status;
		$this->rebill_status = $temp_reb_status;
		
		$sql_select = "";
		foreach($this->sql_rebill_type as $type => $sql_rebill)
		{
			$sql_select .= $sql_select != "" ? "," : "";
			$sql_select .= "SUM(IF($sql_rebill,1,0)) AS $type,\r\n";
			$sql_select .= "SUM(IF($sql_rebill,sub.ss_rebill_amount,0)) AS " . $type . "_amount\r\n";
		}
		
		$sql = "
			SELECT
				$sql_select
			FROM 
				$sql_from
			WHERE
				1
				$sql_where
			";
		$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");

		$summary = NULL;
		
		if($row = mysql_fetch_assoc($res))
			foreach($this->text_rebill_type as $type => $text)
				if(isset($row[$type]))
					$summary[$text] = array("count"=>$row[$type],"amount"=>$row[$type . "_amount"]);

		$this->status_summary = $summary;
	}
	
	function get_subscription_summary()
	{
		$temp_acc_status = $this->account_status;
		$temp_reb_status = $this->rebill_status;
		
		$this->account_status = "";
		$this->rebill_status = "";
		
		$sql_where = $this->get_sql_where();
		$sql_from = $this->get_sql_from();

		$this->account_status = $temp_acc_status;
		$this->rebill_status = $temp_reb_status;

		$sql_rebill = "AND " . $this->sql_rebill_type['active'];
		
		$sql_select = "SUM(" . $this->sql_rebill_type['active'] . ")";
		
		$sql = "
			SELECT
				r.rd_subaccount,
				r.rd_subName,
				r.rd_description,
							
				$sql_select AS rebill_count,
				SUM(UNIX_TIMESTAMP(sub.ss_account_expire_date) - UNIX_TIMESTAMP(sub.ss_account_start_date)) as duration
			FROM 
				$sql_from
			WHERE
				1
				$sql_where
				$sql_rebill
			GROUP BY 
				r.rd_subName
			";
		//echo "<pre>$sql</pre>";
		$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");

		$list = array();

		$this->rebill_details = array();
		
		while($row = mysql_fetch_assoc($res))
			$this->rebill_details[] = array(
										"rd_subaccount" => $row['rd_subaccount'],
										"rd_subName" => $row['rd_subName'],
										"rd_description" => $row['rd_description'],
										"num_rebills" => $row['rebill_count'],
										"avg_duration" => $row['duration'] / $row['rebill_count'] /60/60/24
									);
	}	
	
	function get_transaction_details($use_limit = true)
	{
		$sql_where = $this->get_sql_where($this->hide_duplicates);
		$sql_from = $this->get_sql_from();
		
		if($use_limit)
			$sql_limit = $this->get_sql_limit();
		
		$sql_select = "";
		$status_select = "";
		
		$close = "0";
		foreach($this->sql_rebill_type as $type => $sql_rebill)
			if($this->rebill_status == NULL || in_array($type,$this->rebill_status))
			{
				$status_select .= ($status_select == "" ? "" : " OR ") . "(" . $sql_rebill . ")";
				$sql_select .= "IF(" . $sql_rebill . ",'$type',";
				$close .= ")";
			}
		$sql_select .= $close;
		$status_select = "AND ($status_select)";
		if($this->sort_by)
			$sql_order = "ORDER BY $this->sort_by";
		
		$sql = "
			SELECT
				t.reference_number,
				
				sub.ss_id,
				sub.ss_billing_firstname,
				sub.ss_billing_lastname,
				sub.ss_cust_email,
				
				sub.ss_bank_id,
				sub.ss_transaction_id,
				sub.ss_subscription_id,
				sub.ss_billing_type,

				sub.ss_rebill_amount,
				sub.ss_rebill_next_date,
				UNIX_TIMESTAMP(sub.ss_rebill_next_date) AS next_rebill_timestamp,

				sub.ss_account_status,
				sub.ss_rebill_status,
				
				r.recur_day,
				r.rd_subaccount,
				r.rd_subName,
				r.rd_description,
				
				$sql_select AS rebill_type,
				
				(UNIX_TIMESTAMP(sub.ss_account_expire_date) - UNIX_TIMESTAMP(sub.ss_account_start_date)) as duration
			FROM 
				$sql_from
			WHERE
				1
				$sql_where
				$status_select
			$sql_order
			$sql_limit
			";
		//echo "<pre>$sql</pre>";
		$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");

		$list = array();

		while($row = mysql_fetch_assoc($res))
			$list[$row['rd_subaccount']][] = $row;
		return $list;
	}
	
	function get_rebilldetails($use_limit = true)
	{
		$details = $this->get_transaction_details($use_limit);
		
		$this->rebill_details = array();
		$this->transactions = array();
		$this->min_rebill_date = -1;
		$this->max_rebill_date = -1;
		
		
		foreach($details as $sub_account => $transactions)
		{	
			$num_rebills = 0;
			
			foreach($transactions as $row)
			{
				if($row['rebill_type'] == 'rebill')
				{
						if($row['next_rebill_timestamp']!="")
						{
							if($row['next_rebill_timestamp'] > $this->max_rebill_date || $this->max_rebill_date == -1)
								$this->max_rebill_date = $row['next_rebill_timestamp'];
							if($row['next_rebill_timestamp'] < $this->min_rebill_date || $this->min_rebill_date == -1)
								$this->min_rebill_date = $row['next_rebill_timestamp'];
						}
				}

				
				if($this->rebill_status == NULL || in_array($row['rebill_type'],$this->rebill_status))
					if($this->hide_duplicates == NULL || !$row['td_non_unique'])
					{
						$row['text_status'] = $this->text_rebill_type[$row['rebill_type']];
					
						$row['ss_billing_firstname'] = ucwords(strtolower($row['ss_billing_firstname']));
						$row['ss_billing_lastname'] = ucwords(strtolower($row['ss_billing_lastname']));
						if($row['ss_rebill_next_date'] != "")
						{
							$row['ss_rebill_next_time'] = strtotime($row['ss_rebill_next_date']);
							$row['ss_rebill_next_date'] = date("M jS Y",$row['ss_rebill_next_time']);
						}

						$this->transactions[] = $row;
					}
			}
		}
	}
	
	function get_rebills_to_run()
	{
		$sql_where = $this->get_sql_where();
		$sql_limit = $this->get_sql_limit();
		if($this->sort_by)
			$sql_order = "ORDER BY $this->sort_by";		

		$sql = "	
				SELECT 
					COUNT(ss_id) as rebill_count,
					SUM(sub.ss_rebill_amount) as rebill_amount
				FROM
					cs_subscription AS sub
				left join cs_companydetails as cd on userId = ss_user_ID
				left join cs_company_sites as cs on cs_ID = ss_site_ID
				LEFT JOIN cs_rebillingdetails AS r ON r.rd_subaccount = sub.ss_rebill_id
				WHERE
					sub.ss_rebill_next_date <= NOW()
					AND	sub.ss_rebill_status = 'active'
					AND	sub.ss_rebill_attempts < 3
					AND  activeuser = '1'
					AND  cs_verified in ('approved','non-compliant')
					$sql_where
				";
		$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");
		$this->rebill_summary = mysql_fetch_assoc($res);
		
		$sql = "	
				SELECT 
					ss_id,
					ss_rebill_status,
					ss_rebill_attempts,
					
					sub.ss_billing_firstname,
					sub.ss_billing_lastname,
					
					sub.ss_bank_id,
					sub.ss_transaction_id,
					sub.ss_subscription_id,
	
					sub.ss_rebill_amount,
					sub.ss_rebill_next_date,
					UNIX_TIMESTAMP(sub.ss_rebill_next_date) AS next_rebill_timestamp,
	
					sub.ss_account_status,
					sub.ss_rebill_status,
					
					b.bk_int_function,
					t.CCnumber,
					t.cvv,
					t.bankaccountnumber,
					t.bankroutingcode,
					t.validupto,
					t.td_username,
					
					(UNIX_TIMESTAMP(sub.ss_account_expire_date) - UNIX_TIMESTAMP(sub.ss_account_start_date)) as duration
				FROM
					cs_subscription AS sub
				LEFT JOIN cs_companydetails as cd on cd.userId = ss_user_ID
				LEFT JOIN cs_company_sites as cs on cs_ID = ss_site_ID
				LEFT JOIN cs_rebillingdetails AS r ON r.rd_subaccount = sub.ss_rebill_id
				LEFT JOIN cs_bank AS b ON b.bank_id = sub.ss_bank_id
				LEFT JOIN cs_transactiondetails AS t ON t.transactionId = sub.ss_transaction_id
				WHERE
					sub.ss_rebill_next_date <= NOW()
					AND	sub.ss_rebill_status = 'active'
					AND	sub.ss_rebill_attempts < 3
					AND  activeuser = '1'
					AND  cs_verified in ('approved','non-compliant')
					$sql_where
				$sql_order
				$sql_limit
				";
		$res = sql_query_read($sql) or dieLog("error " . mysql_error() . "<pre>$sql</pre>");

		$this->transactions = array();

		while($row = mysql_fetch_assoc($res))
			$this->transactions[] = $row;
	}
	
	function rebill_subscriptions($sub_ids)
	{
		dieLog('Not using this anymore.');
		$count = array();
		
		$logged = "<table>";
		
		$processor = new transaction_class(false);
		foreach($sub_ids as $sub_id)
		{
			$processor->pull_subscription($sub_id);
			if(!isset($count[$sub_id]))
			{
				$count[$sub_id] = 1;
				$res = $processor->processRebill();		
				$logged .= "
					<tr>
					<td>$id</td><td></td>
					<td>" . $count[$sub_id] . "</td><td></td>
					<td>" . $res['success'] . "</td><td></td>
					<td>" . $res['errormsg'] . "</td><td></td>
					<td>" . $res['status'] . "</td><td></td>
					<td>" . $res['transactionId'] . "</td><td></td>
					</tr>
				";				
			}
		}
		
		return $logged . "</table>";
	}
	
	function render_calendar($show_links = true, $show_banks = false)
	{
		$temp_trans = $this->transactions;

		$this->get_rebilldetails(false);
		
		$cal = new calendar_class();
		$color = new smart_colors();
		
		$cells = array();
		$months = array();
		$max_count = 0;
		
		foreach($this->transactions as $row)
			if($row['rebill_type'] == 'active')
			if($row['ss_rebill_next_time'] != "")
			{
				$key = date("Y/m/d",$row['ss_rebill_next_time']);
				$months[date("Y/m/01",$row['ss_rebill_next_time'])] = 1;
				if($show_banks[0] == NULL || in_array($row['ss_bank_id'],$show_banks))
				if(isset($cells[$key]))
				{
					$cells[$key]['count']++;
					$cells[$key]['amount'][$row['ss_billing_type']]+=$row['ss_rebill_amount'];
				}
				else
				{
					$cells[$key]['count'] = 1;
					$cells[$key]['amount'][$row['ss_billing_type']]=$row['ss_rebill_amount'];
				}
				if($cells[$key]['count'] > $max_count) $max_count = $cells[$key]['count'];
			}
			
		foreach($cells as $key => $cell)
		{
			$cells[$key]['color'] = "#" . $color->blend("44CC44","FFFFFF",$cell['count']/$max_count);
			$cells[$key]['text'] = $cell['count'] . ($cell['count'] != 1 ? " rebills" : " rebill");
			$total_amount = 0;
			foreach($cell['amount'] as $bank_id => $amount)
			{
				$cells[$key]['text'] .= "<br>$bank_id: $" . number_format($amount,2);
				$total_amount += $amount;
			}
			$cells[$key]['text'] .= "<br>Total: $" . number_format($total_amount,2);
			
			
			$_REQUEST['frm_displayrange'] = 1;

			if($show_links)
			{
				$temp = explode("/",$key);
				$_REQUEST['frm_tran_date_from'] = $key;
				$_REQUEST['frm_tran_date_from_month'] = $temp[1];
				$_REQUEST['frm_tran_date_from_day'] = $temp[2];
				$_REQUEST['frm_tran_date_from_year'] = $temp[0];
	
				$_REQUEST['frm_tran_date_to'] = $key;
				$_REQUEST['frm_tran_date_to_month'] = $temp[1];
				$_REQUEST['frm_tran_date_to_day'] = $temp[2];
				$_REQUEST['frm_tran_date_to_year'] = $temp[0];
				
				$temp = $_REQUEST['frm_display_type'];
				$_REQUEST['frm_display_type'] = array(1,2,4);
				
				$params = $this->request_params("frm_");
	
				$_REQUEST['frm_display_type'] = $temp;
	
				$PHP_SELF =  $this->PHP_SELF;
				$cells[$key]['link'] = "$PHP_SELF?$params";
			}
		}
		//$this->array_print($cells);
		ksort($months);
		reset($months);
		$cal->set_cells($cells);
		$cal->set_months($months);
		$cal->render();

		$this->transactions = $temp_trans;
	}
	
	function render_pay_details()
	{
		if(!$this->transactions)
			return "<b>No Details Available</b>";
	
		$banks = bank_GetAllByID();
		$bank_trans = array();
		
		foreach($this->transactions as $trans)
			$bank_trans[$banks[$trans['ss_bank_id']]['bk_trans_types']][$trans['text_status']][] = $trans;

		ksort($bank_trans);
		reset($bank_trans);

		$html = "";
		
		foreach($bank_trans as $paytype => $status)
		{
			$html .= "
				<center><font style='size:12pt; font-weight: bold;'>" .  "</font></center> 
				";//ucwords($paytype) .

			ksort($status);
			reset($status);
			
			foreach($status as $stat => $trans)
			{

				$sorted_transactions = array();
				foreach($trans as $transaction)
				{
					$j=0;
					$base = $transaction[$this->sort_by];
					if(is_numeric($base))
						$base = str_pad($base,25,"0",STR_PAD_LEFT);
					$status = $transaction['text_status'];
					$key = $base . "|" . (++$j);;
					while(isset($sorted_transactions[$key]))
						$key = $base . "|" . (++$j);
					$sorted_transactions[$key] = $transaction;
				}
		
				ksort($sorted_transactions);
				reset($sorted_transactions);
				
				$html .= "
						<table width='550px'  style='border: 1px #000 solid;' cellpadding='0' cellspacing='0' height='0px' class='report'>
						";
				$status_title = explode("|",$stat);
				$html .= "
						<tr><td colspan=9 align='center'><font style='font-size:12pt; font-weight: bold;'>" . ucwords($status_title[1]) . "</font></td></tr>
						<tr>
							<td><b>Subscription ID</b></td>
							<td>&nbsp;</td>
							<td><b>Name</b></td>
							<td>&nbsp;</td>
							<td><b>Amount</b></td>
							<td>&nbsp;</td>
							<td><b>Email</b></td>
							<td>&nbsp;</td>
							<td><b>Rebill Status</b></td>
							<td>&nbsp;</td>
							<td><b>Account Status</b></td>
							<td>&nbsp;</td>
							<td><b>Next&nbsp;Rebill</b></td>
						</tr>
				";

				$total_amount = 0;
				foreach($sorted_transactions as $transaction)
				{
					$color = $color == '#CCCCCC' ? '#DDDDDD' : '#CCCCCC';
					if(!$transaction['td_non_unique'])
						$total_amount += $transaction['ss_rebill_amount'];
					else
						$color = "#FF6666";
		
					$html .= "
							<tr bgcolor='$color'>
								<td>
								<a href='viewSubscription.php?subscription_ID=" . $transaction['ss_subscription_id'] . "'>" . $transaction['ss_subscription_id'] . "</a>
								</td>
								<td></td>
								<td>" . $transaction['ss_billing_lastname'] . ", " . $transaction['ss_billing_firstname'] . "</td>
								<td></td>
								<td align='right'>$" . number_format($transaction['ss_rebill_amount'],2) . "</td>
								<td></td>
								<td><a href='mailto:".$transaction['ss_cust_email']."' class='small'>".$transaction['ss_cust_email']."</a></td>
								<td></td>
								<td>" . $transaction['ss_rebill_status'] . "</td>
								<td></td>
								<td>" . $transaction['ss_account_status'] . "</td>
								<td></td>
								<td>" . $transaction['ss_rebill_next_date'] . "</td>
							</tr>";
				}				
				
				$html .= "
						<tr><td colspan=9 align='right'><font style='font-size:12pt; font-weight: bold;'>Total Amount: $" . number_format($total_amount,2) . "</font></td></tr>
					</table><br><br>
				";
			}
		}
		return $html;
	}
	
	function render_transactions()
	{
		$PHP_SELF = $this->PHP_SELF;

		if(!$this->transactions)
			return "<b>No Details Available</b>";

		if(!$this->status_summary)
			$this->get_status_summary();

		$sorted_transactions = array();
		foreach($this->transactions as $transaction)
		{
			$j=0;
			$base = $transaction[$this->sort_by];
			if(is_numeric($base))
				$base = str_pad($base,25,"0",STR_PAD_LEFT);
			$status = $transaction['text_status'];
			$key = $base . "|" . (++$j);;
			while(isset($sorted_transactions[$status][$key]))
				$key = $base . "|" . (++$j);
			$sorted_transactions[$status][$key] = $transaction;
		}

		//$this->array_print($sorted_transactions);
		
		$form_hidden = $this->request_form("frm_");
		
		$html = "
				<form action='$PHP_SELF' method='post'>
				$form_hidden
				<input type='hidden' name='frm_cancel_rebills' value='1'>
				";
		ksort($sorted_transactions);
		reset($sorted_transactions);
		
		
		foreach($sorted_transactions as $status => $transactions)
		{
			if($dir)			
				krsort($transactions);
			else
				ksort($transactions);
			reset($transactions);
			$status_title = explode("|",$status);

			if($this->search_limit)
			{
				$total_trans = $this->status_summary[$status]['count'];
				$start_trans = $this->search_offset;
				$end_trans = $this->seach_offset + $this->search_limit;
				
				$show_next = true;
				$show_prev = true;
				
				if($end_trans > $total_trans)
					$show_next = false;
				
				if($start_trans == 0)
					$show_prev = false;
					
				if($show_prev || $show_next)
				{
					$page_links = "";
					$num_pages = ceil($total_trans / $this->search_limit);
					$cur_page = floor($this->search_offset / $this->search_limit);
					
					for($j=0;$j<$num_pages;$j++)
					{
						if($j==$cur_page)
							$page_link = "<b>" . ($j+1) . "</b>";
						else
						{
							$_REQUEST['frm_page_offset'] = $j * $this->search_limit;
							$params = $this->request_params("frm_");
							$page_link = "<a href='$PHP_SELF?$params'>" . ($j+1) . "</a>";
						}
					
						$page_links .= ($page_links == "" ? "" : " | " ) . $page_link;
					}
					
					$html .= "<p>$page_links</p>";
				}
				else
					$html .= "<p><b>All Records Displayed</b></p>";
			}

			$cancel_text = stristr($status,"|active") !== FALSE ? "<b>Cancel</b>" : "";

			$html .= "
				<table width='550px'  style='border: 1px #000 solid;' cellpadding='0' cellspacing='0' height='0px' class='report'>
					<tr><td colspan=11 align='center'><font style='font-size:12pt; font-weight: bold;'>" . ucwords($status_title[1]) . "</font></td></tr>
					<tr>
						<td>$cancel_text</td>
						<td>&nbsp;</td>
						<td><b>Subscription ID</b></td>
						<td>&nbsp;</td>
						<td><b>Name</b></td>
						<td>&nbsp;</td>
						<td><b>Email</b></td>
						<td>&nbsp;</td>
						<td><b>Rebill Status</b></td>
						<td>&nbsp;</td>
						<td><b>Account Status</b></td>
						<td>&nbsp;</td>
						<td><b>Amount</b></td>
						<td>&nbsp;</td>
						<td><b>Next&nbsp;Rebill</b></td>
					</tr>
			";
			
			$total_amount = 0;
			foreach($transactions as $transaction)
			{
				$color = $color == '#CCCCCC' ? '#DDDDDD' : '#CCCCCC';
				$total_amount += $transaction['ss_rebill_amount'];

				$cancel_text = stristr($transaction['rebill_type'],"active") !== FALSE ? "<input type='checkbox' name='frm_cancel_ids[]' value='" . $transaction['ss_subscription_id'] . "'></input>" : "";
	
				$html .= "
						<tr bgcolor='$color'>
							<td>$cancel_text</td>
							<td></td>
							<td>
							<a href='viewSubscription.php?subscription_ID=" . $transaction['ss_subscription_id'] . "'>" . $transaction['ss_subscription_id'] . "</a>
							</td>
							<td></td>
							<td>" . $transaction['ss_billing_lastname'] . ", " . $transaction['ss_billing_firstname'] . "</td>
							<td></td>
							<td><a href='mailto:".$transaction['ss_cust_email']."' class='small'>".$transaction['ss_cust_email']."</a></td>
							<td></td>
							<td>" . $transaction['ss_rebill_status'] . "</td>
							<td></td>
							<td>" . $transaction['ss_account_status'] . "</td>
							<td></td>
							<td align='right'>$" . number_format($transaction['ss_rebill_amount'],2) . "</td>
							<td></td>
							<td>" . $transaction['ss_rebill_next_date'] . "</td>
						</tr>";
			}
			
			$html .= "
					<tr><td colspan=11 align='right'><font style='font-size:12pt; font-weight: bold;'>Total Amount: $" . number_format($total_amount,2) . "</font></td></tr>
				</table><br>
				";
			
			if(stristr($status,"|active") !== FALSE)
			$html .= "
					<input type='submit' value='Cancel Rebills'>
					</form>
					<br>
				";
		}
		return $html;
	}

	function render_rebilling_transactions()
	{
		$PHP_SELF = $this->PHP_SELF;

		if(!$this->transactions)
			return "<b>No Details Available</b>";


		$sorted_transactions = array();
		foreach($this->transactions as $transaction)
		{
			$j=0;
			$base = $transaction[$this->sort_by];
			if(is_numeric($base))
				$base = str_pad($base,25,"0",STR_PAD_LEFT);
			$status = $transaction['text_status'];
			$key = $base . "|" . (++$j);;
			while(isset($sorted_transactions[$status][$key]))
				$key = $base . "|" . (++$j);
			$sorted_transactions[$status][$key] = $transaction;
		}

		$form_hidden = $this->request_form("frm_");
		
		$html = "
				<form action='$PHP_SELF' method='post'>
				$form_hidden
				<input type='hidden' name='frm_process_rebills' value='1'>
				";
		ksort($sorted_transactions);
		reset($sorted_transactions);
		
		
		foreach($sorted_transactions as $status => $transactions)
		{
			if($dir)			
				krsort($transactions);
			else
				ksort($transactions);
			reset($transactions);
			$status_title = explode("|",$status);

			if($this->search_limit)
			{
				$total_trans = $this->rebill_summary['rebill_count'];
				$start_trans = $this->search_offset;
				$end_trans = $this->seach_offset + $this->search_limit;
				
				$show_next = true;
				$show_prev = true;
				
				if($end_trans > $total_trans)
					$show_next = false;
				
				if($start_trans == 0)
					$show_prev = false;
					
				if($show_prev || $show_next)
				{
					$page_links = "";
					$num_pages = ceil($total_trans / $this->search_limit);
					$cur_page = floor($this->search_offset / $this->search_limit);
					
					for($j=0;$j<$num_pages;$j++)
					{
						if($j==$cur_page)
							$page_link = "<b>" . ($j+1) . "</b>";
						else
						{
							$_REQUEST['frm_page_offset'] = $j * $this->search_limit;
							$params = $this->request_params("frm_");
							$page_link = "<a href='$PHP_SELF?$params'>" . ($j+1) . "</a>";
						}
					
						$page_links .= ($page_links == "" ? "" : " | " ) . $page_link;
					}
					
					$html .= "<p>$page_links</p>";
				}
				else
					$html .= "<p><b>All Records Displayed</b></p>";
			}

			$cancel_text = stristr($status,"|active") !== FALSE ? "<b>Cancel</b>" : "";

			$html .= "
				<table width='550px'  style='border: 1px #000 solid;' cellpadding='0' cellspacing='0' height='0px'>
					<tr><td colspan=11 align='center'><font style='font-size:12pt; font-weight: bold;'>" . ucwords($status_title[1]) . "</font></td></tr>
					<tr>
						<td><b>Process Rebill</b></td>
						<td>&nbsp;</td>
						<td><b>Name</b></td>
						<td>&nbsp;</td>
						<td><b>Status</b></td>
						<td>&nbsp;</td>
						<td><b>Amount</b></td>
						<td>&nbsp;</td>
						<td><b>Next&nbsp;Rebill</b></td>
					</tr>
			";
			
			$total_amount = 0;
			foreach($transactions as $transaction)
			{
				$color = $color == '#CCCCCC' ? '#DDDDDD' : '#CCCCCC';
				$total_amount += $transaction['ss_rebill_amount'];

				$cancel_text = stristr($transaction['rebill_type'],"active") !== FALSE ? "<input type='checkbox' name='frm_cancel_ids[]' value='" . $transaction['ss_subscription_id'] . "'></input>" : "";
	
				$html .= "
						<tr bgcolor='$color'>
							<td>
							<input type='checkbox' checked name='frm_process_ids[]' value='" . $transaction['ss_id'] . "'></input>
							</td>
							<td></td>
							<td>" . $transaction['ss_billing_lastname'] . ", " . $transaction['ss_billing_firstname'] . "</td>
							<td></td>
							<td>" . $transaction['ss_account_status'] . "</td>
							<td></td>
							<td align='right'>$" . number_format($transaction['ss_rebill_amount'],2) . "</td>
							<td></td>
							<td>" . $transaction['ss_rebill_next_date'] . "</td>
						</tr>";
				$html .= "
					<tr bgcolor='$color'>
						<td colspan=9>
					<b>int function</b>: " . $transaction['bk_int_function'] . "<br>
					<b>cc number</b>: " . etelDec($transaction['CCnumber']) . " (" . $transaction['CCnumber'] . ")<br>
					<b>cvv</b>: " . $transaction['cvv'] . "<br>
					<b>expire</b>: " . $transaction['validupto'] . "<br>
					<b>bank account</b>: " . etelDec($transaction['bankaccountnumber']) . "<br>
					<b>routing number</b>: " . etelDec($transaction['bankroutingcode']) . "<br>
					<b>user name</b>: " . $transaction['td_username'] . "<br>
						</td>
					</tr>
					";
			}
			
			$html .= "
					<tr>
						<td colspan=11 align='right'>
							<font style='font-size:12pt; font-weight: bold;'>Page Amount: $" . number_format($total_amount,2) . "</font><br>
							<font style='font-size:12pt; font-weight: bold;'>Total Amount: $" . number_format($this->rebill_summary['rebill_amount'],2) . "</font>
						</td>
					</tr>
				</table><br>
				";
			
			$html .= "
					<input type='submit' value='Process Rebills'>
					</form>
					<br>
				";
		}
		return $html;
	}
	
	function render_rebill_summary()
	{
		$PHP_SELF = $this->PHP_SELF;
		$params = $this->rebill_params;

		$this->get_subscription_summary();
			
		if(!$this->rebill_details)
			return "<b>No Details Available</b>";
		$html = "
				<table width='550px'  style='border: 1px #000 solid;' cellpadding='0' cellspacing='0' height='0px'>
					<tr>
						<td><b>Sub&nbsp;Account</b></td>
						<td>&nbsp;</td>
						<td><b>Description</b></td>
						<td>&nbsp;</td>
						<td><b>#&nbsp;Rebills</b></td>
						<td>&nbsp;</td>
						<td><b>Average&nbsp;Duration</b></td>
					</tr>
				";

		foreach($this->rebill_details as $rebill)
		{
			$color = $color == '#CCCCCC' ? '#DDDDDD' : '#CCCCCC';

			$html .= "
					<tr bgcolor='$color'>
						<td>" . $rebill['rd_subName'] . "</td>
						<td></td>
						<td>" . $rebill['rd_description'] . "</td>
						<td></td>
						<td align='center'>" . $rebill['num_rebills'] . "</td>
						<td></td>
						<td>" . (isset($rebill['avg_duration'])?number_format($rebill['avg_duration'],2).'&nbsp;days':'No&nbsp;Subscriptions') . (isset($rebill['avg_duration'])? '&nbsp;(' . number_format($rebill['avg_duration']/30,2).'&nbsp;months)':'') . "</td>
					</tr>";
		}
		$html .= "
				</table>
			";
		return $html;
	}
	
	function render_status_summary()
	{
		$this->get_status_summary();
		//$this->array_print($this->rebill_summary);
		if(!$this->status_summary)
			return "<b>No Details Available</b>";
		
		ksort($this->status_summary);
		reset($this->status_summary);
		
		$html = "
				<table width='550px'  style='border: 1px #000 solid;' cellpadding='0' cellspacing='0' height='0px'>
					<tr>
						<td><b>Status</b></td>
						<td>&nbsp;</td>
						<td><b>Amount</b></td>
						<td>&nbsp;</td>
						<td><b>Count</b></td>
					</tr>
				";

		foreach($this->status_summary as $status => $summary)
		{
			$color = $color == '#CCCCCC' ? '#DDDDDD' : '#CCCCCC';
			$status_title = explode("|",$status);
			$status_title = $status_title[1];
			
			$html .= "
					<tr bgcolor='$color'>
 					 	<td>" . ucwords($status_title) ."</td>
						<td></td>
						<td>$" . number_format($summary['amount'],2) . "</td>
						<td></td>
						<td align='center'>" . $summary['count'] . "</td>
					</tr>";
		}
		$html .= "
				</table>
			";
		return $html;
	}
}

?>