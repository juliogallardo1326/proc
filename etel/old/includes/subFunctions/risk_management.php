<?
class risk_management
{
	var $sql_options;
	
	var $trans_table;
	var $company_table;
	var $customerservice_sql;
	var $final_report;
	var $company_details;

	var $report_date;
	var $report_proj;
	var $report_calc;
	var $report_custom;
	
	var $global_row;
	
	function goto_sleep()
	{
		sleep(0);
	}
	
	function gen_row($change = false)
	{
		if($change) $this->global_row = 3-$this->global_row;
		return $this->global_row;
	}
	
	function array_print($text)
	{
		echo "<table width='100%'><tr><td><pre>";
		print_r($text);
		echo "</pre></td></tr></table>";
	}
	
	function risk_management_init()
	{
		$this->report = array();
		
		$this->trans_table = '
								cs_companydetails as cd 
								left join cs_transactiondetails as td on cd.userId = td.userId 
							';
		$this->company_table = 'cs_companydetails as cd ';
		$this->customerservice_sql = "(
								select 
									group_concat(issue,'\n') 
								from 
									(SELECT 
										concat(count(tickets_issue ),' x ',tickets_issue) as issue, 
										count(tickets_issue ) as cnt 
									FROM 
										cs_transactiondetails AS td
										left join  tickets_tickets on td_transactionId = td.transactionId
										left join cs_companydetails AS cd on cd.userId = td.userId 
									where 
										tickets_issue is not null 
										and [company_userId] 
										and [time_frame]
									group by 
										tickets_issue
									) 
									as issues 
								group by 
									null 
								order by 
									cnt desc
								)";

		$this->sql_options['sales_count']			= "SUM(td.status = 'A')";
		$this->sql_options['sales_amount'] 			= "SUM(IF(td.status = 'A',td.amount,0))"; 

		$this->sql_options['unique'] 				= "SUM(td.status = 'A' AND td.td_non_unique=0)"; 
		$this->sql_options['nonunique'] 			= "SUM(td.status = 'A' AND td.td_non_unique<>0)"; 
		$this->sql_options['decline'] 				= "SUM(td.status = 'D' AND td.td_bank_recieved = 'yes')"; 
		$this->sql_options['uniquedecline']		 	= "SUM(td.status = 'D' AND td.td_non_unique=0 AND td.td_bank_recieved = 'yes')"; 
		$this->sql_options['refund'] 				= "SUM(td.cancelstatus = 'Y')"; 
		$this->sql_options['chargebacks']		 	= "SUM(td.td_is_chargeback)"; 
		$this->sql_options['chargebacksVisa'] 		= "SUM(td.td_is_chargeback AND td.cardtype='visa')"; 
		$this->sql_options['chargebacksMastercard'] = "SUM(td.td_is_chargeback AND td.cardtype='master')"; 
		$this->sql_options['creditcard'] 			= "SUM(td.status = 'A' AND td.checkorcard='H')"; 
		$this->sql_options['check'] 				= "SUM(td.status = 'A' AND td.checkorcard='C')"; 
		$this->sql_options['web900'] 				= "SUM(td.status = 'A' AND td.checkorcard='W')"; 

		$this->sql_options['unique_amount'] 				= "SUM(IF(td.status = 'A' AND td.td_non_unique=0,td.amount,0))"; 
		$this->sql_options['nonunique_amount'] 				= "SUM(IF(td.status = 'A' AND td.td_non_unique<>0,td.amount,0))"; 
		$this->sql_options['decline_amount'] 				= "SUM(IF(td.status = 'D' AND td.td_bank_recieved = 'yes',td.amount,0))"; 
		$this->sql_options['uniquedecline_amount']		 	= "SUM(IF(td.status = 'D' AND td.td_non_unique=0 AND td.td_bank_recieved = 'yes',td.amount,0))"; 
		$this->sql_options['refund_amount'] 				= "SUM(IF(td.cancelstatus = 'Y',td.amount,0))"; 
		$this->sql_options['chargebacks_amount']		 	= "SUM(IF(td.td_is_chargeback,td.amount,0))"; 
		$this->sql_options['chargebacksVisa_amount'] 		= "SUM(IF(td.td_is_chargeback AND td.cardtype='visa',td.amount,0))"; 
		$this->sql_options['chargebacksMastercard_amount'] 	= "SUM(IF(td.td_is_chargeback AND td.cardtype='master',td.amount,0))"; 
		$this->sql_options['creditcard_amount'] 			= "SUM(IF(td.status = 'A' AND td.checkorcard='H',td.amount,0))"; 
		$this->sql_options['check_amount'] 					= "SUM(IF(td.status = 'A' AND td.checkorcard='C',td.amount,0))"; 
		$this->sql_options['web900_amount'] 				= "SUM(IF(td.status = 'A' AND td.checkorcard='W',td.amount,0))"; 

		$this->sql_options['customerservice'] 		= "CONCAT(" . $this->customerservice_sql . ")"; 
		$this->sql_options['spider'] 				= "CONCAT((select MAX(cs.cs_spider_report_score) from cs_company_sites as cs where cs.cs_company_id = cd.userID AND [company_userId]))"; 
	}
	
	function create_query()
	{
		$company_details = array();

		$sql_query = "SELECT userId,companyname FROM cs_companydetails";
		$result = sql_query_read($sql_query);
		if(!$result)
				etelPrint("<p><b>" . mysql_error() . "</b><br>$sql_query</p>");
		else
			while($row = mysql_fetch_assoc($result))
				$this->company_details[$row['userId']] = $row;

		$sql_from = $this->trans_table;

		$sql_queries = array();

		foreach($this->company_details as $company_id => $details)
		{
			$sql_queries[$company_id] = array();
			
			foreach($this->report_date as $range_name => $range_values)
			{
				$sql_select = "";
				foreach($this->sql_options as $key => $info)
					$sql_select .= ($sql_select == "" ? "" : ",\r\n") . $info . " AS " . $key . "_" . $range_name;
			
				$date_range = " (td.transactionDate between '".$range_values['from']."' and '".$range_values['to']."')";
				
				$sql_where = "cd.userId = \"$company_id\"";
				$sql_query = "SELECT $sql_select FROM $sql_from WHERE $sql_where AND $date_range GROUP BY td.userId";
				$sql_query = str_replace("[company_userId]",$sql_where,$sql_query);
				$sql_query = str_replace("[time_frame]",$date_range,$sql_query);
				
				$sql_queries[$company_id][$range_name] = $sql_query;
			}
		}
		return $sql_queries;
	}
	
	function run_cron()
	{
		if($this->sql_options == NULL)
			return;
			
		$sql_queries = $this->create_query();

	
		foreach($sql_queries as $company_id => $date_ranges)
		{
			$this->final_report[$company_id]['rc_results'] = array();
			foreach($date_ranges as $date_range => $sql_query)
			{
				$result = sql_query_read($sql_query);
				if(!$result)
					echo "<p><b>" . mysql_error() . "</b><br>$sql_query</p>";
				else
					if($row = mysql_fetch_assoc($result))
						$this->final_report[$company_id]['rc_results'] += $row;
					else
						$this->final_report[$company_id]['rc_results'][$date_range] = 0;
			}
			$risk_calc = get_merchant_quick_status($company_id);
			$this->final_report[$company_id]['rc_risk_value'] = $risk_calc['percent'];
			$this->final_report[$company_id]['rc_date_time'] = time();
			$this->final_report[$company_id]['rc_results'] = serialize($this->final_report[$company_id]['rc_results']);
			$this->goto_sleep();
		}		

		
		$sql = array();
		foreach($this->final_report as $company_id => $fields)
		{
			$values = "rc_company_id = $company_id";
			foreach($fields as $field => $value)
				$values .= ($values != "" ? ", " : "") . "$field = \"" . quote_smart($value) . "\"";

			$sql = "INSERT cs_risk_cron SET $values ON DUPLICATE KEY UPDATE $values";
			$res = sql_query_write($sql) or dieLog("error" . mysql_error() . "<pre>$sql</pre>");
			$this->goto_sleep();
		}
	}
	
	function get_cron_data($company_ids = 0)
	{
		$where = "";
		if($company_ids != 0);
		foreach($company_ids as $index => $company_id)
			$where .= ($where == "" ? "" : ", ") . $company_id;

		if($where != "")
			$where = "WHERE c.userId IN ($where)";
							
		$sql = "SELECT 
					r.rc_results,
					r.rc_date_time,
					c.userId,
					r.rc_risk_value,
					c.companyname 
				FROM 
					cs_risk_cron AS r
				LEFT JOIN cs_companydetails AS c ON c.userId = r.rc_company_id
				$where
				ORDER BY 
					rc_risk_value DESC
				";

		$res = sql_query_read($sql) or dieLog("error" . mysql_error() . "<pre>$sql</pre>");
		$list = array();
		while($r = mysql_fetch_assoc($res))
			$list[] = $r;
		return $list;
	}
	
	function get_projections()
	{
		$sql = "SELECT * FROM cs_risk_report_projections";
		
		$res = sql_query_read($sql) or dieLog($sql . "<br>" . mysql_error());
		
		$list = array();
		while($r=mysql_fetch_assoc($res))
			$list[$r['rrp_name']] = array("title" => $r['rrp_title'], "equation" => $r['rrp_equation']);
		return $list;
	}

	function get_calculations()
	{
		$sql = "SELECT * FROM cs_risk_report_calc";
		
		$res = sql_query_read($sql) or dieLog($sql . "<br>" . mysql_error());
		
		$list = array();
		while($r=mysql_fetch_assoc($res))
			$list[$r['rrc_title']] = array("desc" => $r['rrc_desc'], "equation" => $r['rrc_equation'], "display" => $r['rrc_display'], "label" => $r['rrc_label']);
		return $list;
	}
	
	function get_date_ranges()
	{
		$sql = "SELECT * FROM cs_risk_report_dates";
		
		$res = sql_query_read($sql) or dieLog($sql . "<br>" . mysql_error());
		
		$list = array();
		while($r=mysql_fetch_assoc($res))
			$list[$r['rrd_name']][] = $r;
			
		$this_year = date("Y",time());
		$this_month = date("n",time());
		$this_day = date("j",time());
		$this_hour = date("h",time());
		$this_minute = date("i",time());
		$this_second = date("s",time());
		
		foreach($list as $date_name => $date_info)
		{
			eval('$to_month=' . $date_info[0]['rrd_to_month'] . ';');
			eval('$to_day=' . $date_info[0]['rrd_to_day'] . ';');
			eval('$to_year=' . $date_info[0]['rrd_to_year'] . ';');
		
			eval('$from_month=' . $date_info[0]['rrd_from_month'] . ';');
			eval('$from_day=' . $date_info[0]['rrd_from_day'] . ';');
			eval('$from_year=' . $date_info[0]['rrd_from_year'] . ';');
		
			$range_a['title'] = $date_info[0]['rrd_title'];
			$range_a['from'] = date("Y-m-d 00:00:00",mktime(0,0,0,$from_month,$from_day,$from_year));
			$range_a['to'] = date("Y-m-d 23:59:59",mktime(0,0,0,$to_month,$to_day,$to_year));
			
			eval('$to_month=' . $date_info[1]['rrd_to_month'] . ';');
			eval('$to_day=' . $date_info[1]['rrd_to_day'] . ';');
			eval('$to_year=' . $date_info[1]['rrd_to_year'] . ';');
		
			eval('$from_month=' . $date_info[1]['rrd_from_month'] . ';');
			eval('$from_day=' . $date_info[1]['rrd_from_day'] . ';');
			eval('$from_year=' . $date_info[1]['rrd_from_year'] . ';');
			
			$range_b['title'] = $date_info[1]['rrd_title'];
			$range_b['from'] = date("Y-m-d 00:00:00",mktime(0,0,0,$from_month,$from_day,$from_year));
			$range_b['to'] = date("Y-m-d 23:59:59",mktime(0,0,0,$to_month,$to_day,$to_year));
			
			if(strtotime($range_b['to']) > strtotime($range_a['to']))
			{
				$date['this'.$date_name] = $range_b;
				$date['last'.$date_name] = $range_a;
			}
			else
			{
				$date['this'.$date_name] = $range_a;
				$date['last'.$date_name] = $range_b;
			}
		}
		
		return $date;
	}

	function get_custom_report($report_name="")
	{
		if($report_name != "")
		{
			$sql = "SELECT * FROM cs_risk_report WHERE LOWER(rr_report_name) = LOWER('$report_name');";
			$res = sql_query_read($sql) or dieLog(mysql_error());
			$r = mysql_fetch_assoc($res);
			return array("name"=>$r['rr_report_name'],"settings"=>unserialize(stripslashes($r['rr_report_settings'])));
		}		
		return NULL;
	}

	function generate_summary($company_ids = 0)
	{
		$data = $this->get_cron_data($company_ids);

		$summary_results = array();
		
		foreach($data as $index => $info)
		{
			$cron_time = $info['rc_date_time'];
			
			$results = unserialize($info['rc_results']);
			foreach($results as $key => $value)
				if(!isset($summary_results[$key]))
					$summary_results[$key] = $value;
				else
					$summary_results[$key] += $value;
		}

		$company_id = 0;
		$company_name = "Summary";

		$results = $summary_results;
		$results = $this->generate_company_report($company_id,$company_name,$cron_time,$results);
		$reports[] = $results;

		return $reports;
	}
	
	function generate_report($company_ids = 0)
	{
		$data = $this->get_cron_data($company_ids);
		$reports = array();
		foreach($data as $index => $info)
		{
			$results = unserialize($info['rc_results']);
			$company_id = $info['userId'];
			$company_name = $info['companyname'];
			$cron_time = $info['rc_date_time'];

			$results = $this->generate_company_report($company_id,$company_name,$cron_time,$results);
			$reports[] = $results;
		}
		return $reports;
	}

	function generate_company_report($company_id,$company_name,$cron_time,$results)
	{
		$report_email = "";
		
		$temp_report = array();
		$company_risk = 0;
		
		foreach($this->report_calc as $report_title => $report_info)
			if(!isset($this->report_custom['settings']['calculations']) || isset($this->report_custom['settings']['calculations'][$report_title]))
		{
			if(!isset($this->report_custom['settings']['calculations'][$report_title]['labels']))
				$label_info = unserialize(stripslashes($report_info['label']));
			else
				$label_info = $this->report_custom['settings']['calculations'][$report_title]['labels'];
			
			$total_score = 0;
			$total_risk = 0;
			$total_elements = 0;

			foreach($this->report_date as $report_name => $report_range)
				if(isset($report_info['equation']))
				{
					foreach($this->sql_options as $sql_name => $sql_query)
						${$sql_name} = $results[$sql_name . "_" . $report_name];

					eval('$value=' . $report_info['equation'] . ';');
					
					
					$warn = "";
					$score = 0;
					$color = "FFFFFF";

					if(isset($label_info) && is_array($label_info))
						foreach($label_info as $label_index => $label_def)
							if($value >= $label_def['limit']) 
							{
								$warn = $label_def['text'];
								$score = $label_def['score'];
								$color = $label_def['color'];
							}

					$total_score+=$value;
					$total_risk+=$score;
					$total_elements++;
					
					$display = "";
					if(isset($report_info['display']) && $report_info['display'] != "")
						eval('$display=' . $report_info['display'] . ';');
					
					$temp_report[$report_title][$report_name] = array("display"=>$display,"value"=>$value,"text"=>$warn,"score"=>$score,"color"=>$color);
				}
				else
					$temp_report[$report_title][$report_name] = array("value"=>0,"text"=>"no data");

			foreach($this->report_proj as $report_proj_title => $report_proj_info)
			{
				foreach($temp_report[$report_title] as $source_name => $source_info)
					${$source_name} = $source_info['value'];
					
				eval('$value=' . $report_proj_info['equation'] . ';');
				
				$warn = "";
				$color = "FFFFFF";
				$score = 0;

				if(isset($label_info) && is_array($label_info))
					foreach($label_info as $label_index => $label_def)
						if(str_replace(",","",$value) >= $label_def['limit']) 
						{
							$warn = $label_def['text'];
							$score = $label_def['score'];
							$color = $label_def['color'];
						}

				$total_score+=$value;
				$total_risk+=$score;
				$total_elements++;

				$display = "";
				if(isset($report_proj_info['display']) && $report_proj_info['display'] != "")
					eval('$display=' . $report_proj_info['display'] . ';');

				$temp_report[$report_title][$report_proj_title] = array("display"=>$display,"value"=>$value,"text"=>$warn,"score"=>$score,"color"=>$color);
			}
			
			$plaintext = "";
			if(isset($label_info) && is_array($label_info))
				foreach($label_info as $label_index => $label_def)
					if($total_score >= $label_def['limit']) 
					{
						$warn = $label_def['text'];
						$color = $label_def['color'];
						$plaintext = $label_def['plaintext'];
					}
			
			if($plaintext != "")
				$report_email .= "  " . $plaintext;	
										
			$temp_report[$report_title]['total'] = array("score"=>($total_elements > 0 ? number_format($total_score/$total_elements,2) : 0),"risk"=>($total_elements > 0 ? number_format($total_risk/$total_elements,2) : 0),"color"=>$color,"text"=>$warn);
			$company_risk += $temp_report[$report_title]['total']['risk'];

			$key = str_pad($temp_report[$report_title]['total']['risk'],10,"0",STR_PAD_LEFT);
			$key .= str_pad($temp_report[$report_title]['total']['score'],10,"0",STR_PAD_LEFT);
			$temp_report_order[$key . "|" . $report_title] = $temp_report[$report_title];
		}
		$results = quote_smart(serialize($temp_report_order));
	
		$time = time();
		$values = array("companyname"=>"$company_name","rr_report_email" => "$report_email", "rr_company_id" => $company_id, "rr_risk_value" => $company_risk, "rr_report_time" => $time, "rr_cron_time" => $cron_time, "rr_results" => "$results");
		return $values;
	}
}

?>