<?
$global_row =1;

function smart_array_path($array,$path)
{
	$target = &$array;
	foreach($path as $k)
		$target = &$target[$k];
	return $target;
}

function smart_scrub_params($params)
{
	foreach($params as $index=>$param)
		if(is_array($param))
			$params[$index] = smart_scrub_params($param);
		else
			$params[$index] = quote_smart($param);
	return $params;
}

function gen_row($change = false,$set=false)
{
	global $global_row;
	if($set!==false) $global_row = $set;
	if($change) $global_row = 3-$global_row;
	return $global_row;
}

function smart_getSites($params)
{
	$sql = "SELECT cs_ID,cs_name FROM cs_company_sites WHERE cs_company_id = \"" . $params['userid'] . "\" ORDER BY cs_URL;";
	$pairs = array();
	$pairs[] = array("value"=>"","display"=>"All Websites");
	$result = sql_query_read($sql) or dieLog(mysql_error() . "<b>$sql</b>");
	while($sql_row = mysql_fetch_assoc($result))
		$pairs[] = array("value" => $sql_row['cs_ID'], "display" => $sql_row['cs_name']);
	return $pairs;
}

function smart_getBanks($params)
{
	$sql = "SELECT bank_id, bank_name FROM cs_bank WHERE 1 ORDER BY `bank_id` DESC"; 
	$pairs = array();
	$pairs[] = array("value"=>"","display"=>"Any Bank");
	$result = sql_query_read($sql) or dieLog(mysql_error() . "<b>$sql</b>");
	while($sql_row = mysql_fetch_assoc($result))
		$pairs[] = array("value" => $sql_row['bank_id'], "display" => $sql_row['bank_name']);
	return $pairs;
}

function smart_getChargeTypes($params)
{
	$types = func_get_enum_data('cs_transactiondetails','cardtype');
	$pairs = array();
	//$pairs[] = array("value"=>"","display"=>"Any Type");
	foreach($types as $key=>$type)
		$pairs[] = array("value" => $type, "display" => $type);
	return $pairs;
}

function smart_getMercBanks($params)
{
	$sql = "SELECT group_concat(distinct bk.bank_id) as bank_id, bank_description FROM cs_bank as bk left join `cs_transactiondetails` as td on td.`bank_id` = bk.`bank_id` WHERE bk_hide=0 and td.userId='".$params['userId']."' GROUP BY `bank_description` ORDER BY `bank_description` ASC"; 
	$pairs = array();
	$pairs[] = array("value"=>"","display"=>"Any Bank");
	$result = sql_query_read($sql) or dieLog(mysql_error() . "<b>$sql</b>");
	while($sql_row = mysql_fetch_assoc($result))
		$pairs[] = array("value" => $sql_row['bank_id'], "display" => $sql_row['bank_description']);
	return $pairs;
}

function smart_getStatus($params)
{
	global $etel_completion_array;
	$sql = "
		select 
			`cd_completion`,
			count(*) as cnt 
		from 
			cs_companydetails 
		GROUP BY `cd_completion` 
		ORDER BY `cd_completion` DESC
		";
	$result = sql_query_read($sql) or dieLog(mysql_error());
	$pairs = array();
	$pairs[] = array("value"=>"","display"=>"Any Status");
	while($cpl = mysql_fetch_assoc($result))
	{
		$key = $cpl['cd_completion'];
		$data = $etel_completion_array[intval($key)];
		if(!$data) $data = array('txt'=>'Invalid Status');
		$pairs[] = array("value" => $key, "display" => $data['txt']." (".$cpl['cnt'].")");
	}
	return $pairs;
}

function smart_getMerchantTypes($params)
{
	$sql = "
		select 
			transaction_type, 
			CONCAT(UPPER(transaction_type),' (',count(userId),')') as out 
		from 
			cs_companydetails 
		WHERE
			cd_ignore=0
		GROUP BY transaction_type
	";
	$pairs = array();
	$pairs[] = array("value"=>"","display"=>"All Merchant Types");
	$result = sql_query_read($sql) or dieLog(mysql_error() . "<b>$sql</b>");
	while($sql_row = mysql_fetch_assoc($result))
		if($sql_row['transaction_type'] != "")
			$pairs[] = array("value" => $sql_row['transaction_type'], "display" => $sql_row['out']);
	return $pairs;
}

function smart_getGateways($params)
{
	global $database;
	$sql = "
		select 
			gateway_id, 
			concat(gw_title,' - (',count(*),')') as gateway_name 
		from 
			cs_companydetails 
		left join 
			{$database["database_main"]}.etel_gateways on gw_id = gateway_id 
		where 
			gw_database = '".$_SESSION['gw_database']."' 
		GROUP BY 
			`gateway_id
		"; 
	$pairs = array();
	$pairs[] = array("value"=>"","display"=>"Any Gateway");


	$result = sql_query_read($sql) or dieLog(mysql_error() . "<b>$sql</b>");
	while($sql_row = mysql_fetch_assoc($result))
		$pairs[] = array("value" => $sql_row['gateway_id'], "display" => $sql_row['gateway_name']);
	return $pairs;
}

function smart_getCompanies($params)
{
	$sql = "SELECT companyname,userId FROM cs_companydetails ORDER BY LOWER(companyname);";
	$pairs = array();
	$result = sql_query_read($sql) or dieLog(mysql_error() . "<b>$sql</b>");
	while($sql_row = mysql_fetch_assoc($result))
		$pairs[] = array("value" => $sql_row['userId'], "display" => $sql_row['companyname']);
	return $pairs;
}

function smart_getCompanyTypes($params)
{
		$qrt_select_company = "select COUNT(activeuser) as total, SUM(activeuser=1) as active, sum(activeuser!=1) as nonactive, sum(reseller_id >0) as reseller_ref, sum(reseller_id <=0 || reseller_id is NULL) as etelegate_ref from cs_companydetails";
		if(!($show_sql = sql_query_read($qrt_select_company)))
			dieLog(mysql_errno().": ".mysql_error()."<BR>");
		$show_val = mysql_fetch_assoc($show_sql);
		$pairs = array();
		$pairs[] = array("value"=>"A","display" =>"All Companies ($show_val[total])");
		$pairs[] = array("value"=>"AC","display" =>"Active Companies ($show_val[active])");
		$pairs[] = array("value"=>"NC","display" =>"Non-active Companies ($show_val[nonactive])");

		return $pairs;
}

function smart_getCustomReports($params)
{
	$sql = "SELECT rr_report_name FROM cs_risk_report ORDER BY LOWER(rr_report_name);";
	$pairs = array();
	$pairs[] = array("value" => "", "display" => "Default Report");
	$result = sql_query_read($sql) or dieLog(mysql_error() . "<b>$sql</b>");
	while($sql_row = mysql_fetch_assoc($result))
		$pairs[] = array("value" => $sql_row['rr_report_name'], "display" => $sql_row['rr_report_name']);
	return $pairs;
}


function smart_AJAX_company_search($params,$render_js = true)
{
	global $etel_domain_path;
	if($render_js)
	{
?>
	<script language="JavaScript" type="text/JavaScript">
		function <?=$params['ajax_response']?>(response)
		{
			var data = JSON.parse(response.responseText);
			obj_element = document.search_form.<?="frm_" . str_replace(".","_",$params['ajax_form_element'])?>;
			<? 
			if(isset($params['ajax_form_element_b']))
			{
			?>			
			cs_URL_select = document.search_form.<?="frm_" . str_replace(".","_",$params['ajax_form_element_b'])?>;
			<?
			}
			?>
			obj_element.options.length=0;
			
		
			if(data['show_option_all'] && <?=(isset($params['all companies']) ? $params['all companies'] : 1)?>)
			{
				obj_element.options.length=obj_element.options.length+1;
				obj_element.options[obj_element.options.length-1].value="";
				obj_element.options[obj_element.options.length-1].text="All Companies";
				obj_element.options[0].selected=true;
			}
			else
			{
				//obj_element.options.length=obj_element.options.length+1;
				//obj_element.options[obj_element.options.length-1].value="AL";
				//obj_element.options[obj_element.options.length-1].text="All Companies In List";
				//obj_element.options[0].selected=true;
			}		
			var cp_ar = data['completion'];
		
			var len =data['company_list'].length;
			for (var i = 0;i<len;i++)
			{
				if(data['company_list'][i]['ui'])
				{
				obj_element.options.length=obj_element.options.length+1;
				obj_element.options[obj_element.options.length-1].value=data['company_list'][i]['ui'];
				obj_element.options[obj_element.options.length-1].text=data['company_list'][i]['cn'];
				obj_element.options[obj_element.options.length-1].title=cp_ar[data['company_list'][i]['cp']]['txt'];
				<?
					if(isset($_REQUEST["frm_" . str_replace(".","_",$params['ajax_form_element'])]))
					{
						$options = "";
						if(!is_array($_REQUEST["frm_" . str_replace(".","_",$params['ajax_form_element'])]))
							 $options .= "obj_element.options[obj_element.options.length-1].value==\"" . $_REQUEST["frm_" . str_replace(".","_",$params['ajax_form_element'])] . "\"";
						else
							foreach($_REQUEST["frm_" . str_replace(".","_",$params['ajax_form_element'])] as $index => $value)
								$options .= ($options == "" ? "" : " || ") . "obj_element.options[obj_element.options.length-1].value==\"$value\"\r\n";
						echo "if($options){ obj_element.options[obj_element.options.length-1].selected = true; }";
					}
				?>


				}
			}
			
			<? 
			if(isset($params['ajax_form_element_b']))
			{
			?>
			cs_URL_select.options.length=0;
			<?
				if(!isset($params['all sites']) || $params['all sites'] == 1)
				{
				echo "
				cs_URL_select.options.length=cs_URL_select.options.length+1;
				cs_URL_select.options[cs_URL_select.options.length-1].value=\"\";
				cs_URL_select.options[cs_URL_select.options.length-1].text=\"All\";
				";
				if(!isset($_REQUEST["frm_" . str_replace(".","_",$params['ajax_form_element_b'])]) || $_REQUEST["frm_" . str_replace(".","_",$params['ajax_form_element_b'])] == "")
					echo "cs_URL_select.options[cs_URL_select.options.length-1].selected=true;";
				}
			?>
			for (var ci in data['site_list'])
			{
				if(data['site_list'][ci]['ci'] && data['site_list'][ci]['cn'] != "")
				{
					cs_URL_select.options.length=cs_URL_select.options.length+1;
					cs_URL_select.options[cs_URL_select.options.length-1].value=data['site_list'][ci]['ci'];
					cs_URL_select.options[cs_URL_select.options.length-1].text=data['site_list'][ci]['cn'];
					<?
						if(isset($_REQUEST["frm_" . str_replace(".","_",$params['ajax_form_element_b'])]))
						{
							$options = "";
							foreach($_REQUEST["frm_" . str_replace(".","_",$params['ajax_form_element_b'])] as $index => $value)
								$options .= ($options == "" ? "" : " || ") . "cs_URL_select.options[cs_URL_select.options.length-1].value==\"$value\"\r\n";
							echo "if($options){ cs_URL_select.options[cs_URL_select.options.length-1].selected = true; }";
						}
					?>
				}
			}
			<?
			}
			?>
		}
	
		var refresh_timeout = null;
		function <?=$params['ajax_call']?>()
		{
			clearInterval(refresh_timeout);
			refresh_timeout = setTimeout('<?=$params['ajax_call']?>_sub()',500);
		}
		
		function <?=$params['ajax_call']?>_sub()
		{
			<?
				$url_param = "";
				foreach($params['ajax_url']['parameters'] as $index=>$param)
				{
					if($param['source'] == "form")
					{
						echo "var " . $param['name'] . " = document.search_form.frm_" . $param['value'] . ".value;\n";
						echo "if(typeof " . $param['name'] . " == 'undefined') " . $param['name'] . " = '';\n";
					}
					else
						echo "var " . $param['name'] . " = \"" . $param['value'] . "\";\n";
					$url_param .= ($url_param == "" ? "'" : "+'&") . $param['url_name'] . "='+" . $param['name'];
				}
			?>
			var url = '<?=$etel_domain_path . $params['ajax_url']['location']?>';
			var pars = <?=$url_param?>;
			var myAjax = new Ajax.Request( url, { method: 'post', parameters: pars, onComplete: <?=$params['ajax_response']?> });
		}	
	</script>
<?
	}
	return NULL;
}

function smart_search(&$sql_query, $is_safe = true)
{
	global $etel_query_info;
	
	if(!$sql_query['sql_config']) $sql_query['sql_config'] = array('TimeOut'=>10);
	$sql_tables = isset($sql_query['tables']) ? $sql_query['tables'] : NULL;
	$sql_fields = isset($sql_query['return']) ? $sql_query['return'] : NULL;
	$sql_manip = isset($sql_query['manip']) ? $sql_query['manip'] : NULL;
	$sql_search = isset($sql_query['search']) ? $sql_query['search'] : NULL;
	$sql_where= isset($sql_query['where']) ? $sql_query['where'] : NULL;
	$sql_order= isset($sql_query['orderby']) ? $sql_query['orderby'] : NULL;
	$sql_group= isset($sql_query['groupby']) ? $sql_query['groupby'] : NULL;
	$sql_limit= isset($sql_query['limit']) ? $sql_query['limit'] : NULL;
	$sql_joins= isset($sql_query['joins']) ? $sql_query['joins'] : NULL;
	$sql_key= isset($sql_query['key']) ? $sql_query['key'] : NULL;
	
	
	$use_columns = "";
	
	if($sql_fields != NULL)
	foreach($sql_fields as $name => $settings)
		if($settings['in_query'] !== false) $use_columns .= ($use_columns != "" ? ",\n" : "") . $settings['source'];
	if($sql_key != NULL)
	foreach($sql_key as $name => $settings)
		$use_columns .= ($use_columns != "" ? ",\n" : "") . $name;
	
	$use_tables = "";
	if($sql_tables != NULL)
	foreach($sql_tables as $table)
		$use_tables .= ($use_tables != "" ? ",\n " : "") . ($table);
	
	$use_search = "1";
	if($sql_search != NULL)
	foreach($sql_search as $field => $settings)
		if(!isset($settings['in_query']) || $settings['in_query'])
			if($settings['value'] != "" && $settings['value'] != "\"\"")
				$use_search .= ($use_search != "" ? " AND " : "") . "(" . (isset($settings['convert']) ? $settings['convert'] . "(" . quote_smart($field) . ")" : quote_smart($field)) . " " . $settings['compare'] . " " . $settings['value'] . ")\n";
	if($sql_where != NULL)
	foreach($sql_where as $field => $settings)
		$use_search .= ($use_search != "" ? " AND " : "") . "(" . (isset($settings['convert']) ? $settings['convert'] . "(" . quote_smart($field) . ")" : quote_smart($field)) . " " . $settings['compare'] . " " . $settings['value'] . ")\n";

	if(isset($sql_query['user_orderby']) && is_array($sql_query['user_orderby']))
		if(isset($_REQUEST['smart_order']) && in_array($_REQUEST['smart_order'],$sql_query['user_orderby']))
			$sql_order[] = $_REQUEST['smart_order'] . ($_REQUEST['smart_order_dir'] ? " DESC" : " ASC");
	
	$use_order = "";
	if($sql_group)
	{
		foreach($sql_group as $group)
			$use_group = ($group) . ($use_group != "" ? ", " : "") . $use_group . "\n";
		if($use_group != "")
			$use_group = "GROUP BY\n $use_group ";
	}
	if($sql_query['subgroupby'])
	{
		if(is_array($sql_query['subgroupby']))
			$use_subgroup = implode($sql_query['subgroupby'],', ');
		else
			$use_subgroup = $sql_query['subgroupby']; 
			
		$sql_query['subquery']['queries']['00|GroupBy'] = array("name"=>"subgroup_by", "source" => $use_subgroup,'hidden'=>1);	
		if($sql_query['subgrouptitle'])	
			$sql_query['subquery']['queries']['00|GroupTitle'] = array("name"=>"subgroup_title", "source" => $sql_query['subgrouptitle'],'hidden'=>1);
		ksort($sql_query['subquery']['queries']);
			
		if($use_subgroup != "")
			$use_subgroup = "GROUP BY\n subgroup_by ";
		if($sql_query['subrollup'])
			$use_subgroup .= "WITH ROLLUP\n";
	}
	if($sql_order)
	{
		foreach($sql_order as $order)
			$use_order = ($order) . ($use_order != "" ? ", " : "") . $use_order . "\n";
		if($use_order != "")
			$use_order = "ORDER BY\n $use_order";
	}
	if($sql_query['suborderby'])
	{
		if(is_array($sql_query['suborderby']))
			$use_suborder = implode($sql_query['suborderby'],', ');
		else
			$use_suborder = $sql_query['suborderby']; 
			
		if($use_suborder != "")
			$use_suborder = "ORDER BY\n $use_suborder";
	}
	$use_joins = "";
	if($sql_joins != NULL)
	foreach($sql_joins as $join)
	{
		$use_compare = "";
		foreach($join['on'] as $compare)
			if(!isset($compare['type']) || $compare['type'] != 'fixed')
				$use_compare .= ($use_compare == "" ? "" : " AND ") . $compare['field_a'] . " " . $compare['compare'] . " " . $compare['field_b'];
			else
				$use_compare .= ($use_compare == "" ? "" : " AND ") . $compare['sql'];
				
		$use_joins .= "LEFT JOIN " . $join['table'] . " ON ($use_compare)\n";
	}

	
	// Do Subquery
	
	$row = array();
	$row['num_rows'] = false;
	$sub_result = NULL;
	if($sql_query['subquery']['queries'] != NULL)
	{
		$sub_sql = "";
		foreach($sql_query['subquery']['queries'] as $display => $info)
			$sub_sql .= ($sub_sql != "" ? ", " : "") . $info['source'] . " AS " . $info['name'];
		$sub_sql = "SELECT\n $sub_sql \n FROM\n $use_tables \n $use_joins WHERE $use_search $use_subgroup"; 
		
		if($sql_query['subrollup'])
		{
			$rolluptitle = "";
			
			if($sql_query['subgrouprolluptitle']) 
				$rolluptitle = "if(subgroup_by is null,".$sql_query['subgrouprolluptitle'].",subgroup_title) as subgroup_grouptitle, ";
			
			$sub_sql = "Select $rolluptitle sq.*, if(subgroup_by is null,1,0) as is_rollup  from ($sub_sql) as sq $use_suborder";
		}
		
		if($sql_query['skip_subquery']) $sub_sql = "select 1 limit 0";
			
		$sub_result = sql_query_read($sub_sql,$sql_query['sql_config']);
		if(!$sub_result && $sql_query['sql_nodie']) return false;
		if(!$sub_result) dieLog(mysql_error() . " ~ $sub_sql","Search Aborted. Please try a more specific Search.");
		
		//etelPrint($sub_sql);
		if(!$sql_query['skip_subquery'])
			while($sql_row = mysql_fetch_assoc($sub_result))
			{
				if($sql_query['subgrouptitlekey'])
					$row['sub_row'][$sql_row['subgroup_title']] = $sql_row;
				else
					$row['sub_row'][] = $sql_row;
				
				if(!strcasecmp($sql_limit['max_offset_source'],"result") && !$use_calc_rows)
				{
					$row['num_rows'] += $sql_row[$sql_limit['max_offset']];
					if($sql_row['is_rollup']) 
						$row['num_rows_byrollup'] = $sql_row[$sql_limit['max_offset']];
				}
			}
	}
	if($row['num_rows_byrollup'])
		$row['num_rows'] = $row['num_rows_byrollup']; // There was a rollup, use that.
		
	if($row['num_rows']===false)
		$use_calc_rows = 1;	// If subquery failed to provide row count, ask the Transaction Query to do it.
	else
		if(isset($_REQUEST["frm_" . $sql_limit['offset_source']]) && $_REQUEST["frm_" . $sql_limit['offset_source']]>$row['num_rows'])
				 $_REQUEST["frm_" . $sql_limit['offset_source']] = $row['num_rows'] - $_REQUEST["frm_" . $sql_limit['count_source']];
	// Do Transactions
	
	$use_limit = "";
	if(isset($sql_limit))
	{
		if(isset($_REQUEST["frm_" . $sql_limit['count_source']]))
		{
			$limit = $_REQUEST["frm_" . $sql_limit['count_source']];
			if($limit > 500 && $is_safe) $limit = 500;
			if(isset($_REQUEST["frm_" . $sql_limit['offset_source']]))
				$limit = $_REQUEST["frm_" . $sql_limit['offset_source']] . ", $limit";
		}
		
		if($sql_limit['forcelimit'])
			$limit = $sql_limit['forcelimit'];
		if($is_safe)
			$use_limit = $limit != "" ? "LIMIT " . $limit : "LIMIT 500";
	//$my_sql['limit'] = array("offset_source" => "page_offset","count_source" => "page_count");
	//quote_smart($sql_limit) . "\n";
	}
	else
		if($is_safe)
			$use_limit = "LIMIT 500";
	
	$calcrows = "";
	if($use_calc_rows)
		$calcrows = "SQL_CALC_FOUND_ROWS";
		
	$sql = "SELECT $calcrows\n $use_columns \n FROM\n $use_tables \n $use_joins WHERE $use_search $use_group $use_order " . ($use_limit != "" ? " $use_limit" : "") . ";";
	if($sql_query['skip_query'] || $limit<0) $sql = "select 1 limit 0";

	$result = sql_query_read($sql,$sql_query['sql_config']);
	if(!$result && $sql_query['sql_nodie']) return false;
	if(!$result) dieLog(mysql_error() . " ~ $sql","Search Aborted. Please try a more specific Search.");

	$j=0;
	while($sql_row = mysql_fetch_assoc($result))
	{
		if($sql_key == NULL) $key = " ";
		else
		{	
			$key = "";
			foreach($sql_key as $use_key => $settings)
				$key .= ($key == "" ? "" : ", ") . (isset($settings['display']) ? $settings['display'] : "") . $sql_row[$use_key];
		}
		
		if(sizeof($sql_manip))
			foreach($sql_manip as $k=>$i)
				$sql_row[$k] = (  isset($i['params'])   ?   $i['function']($sql_row[$i['source']],$i['params'])   :   $i['function']($sql_row[$i['source']])  );
					
		
		$row['rows'][$key][] = $sql_row;
		$j++;
	}	
	
	if($use_calc_rows && !$sql_query['skip_query'])
	{
		etelPrint("Inefficiency: Using SQL_CALC_FOUND_ROWS to calculate row count");
		$sql = "select FOUND_ROWS()";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$row['num_rows'] = mysql_result($result,0,0);
	}
	//if($sql_subrollup)
	//	if(!strcasecmp($sql_limit['max_offset_source'],"result") && !$use_calc_rows)
	//		$row['num_rows'] = $row['sub_row'][sizeof($row['sub_row'])-1][$sql_limit['max_offset']];
//	foreach($etel_query_info['results'] as $info)
//	{
//		$row['time'][] = $info['duration'];	
//		$row['query'][] = $info['sql'];	
//	}
	
	return $row;
}

function smart_process_action_form($action)
{
	$is_form_complete = 1;
	$form_vars = array();
	
	foreach($_REQUEST as $entry => $value)
	{
		$pieces = explode("_",$entry);
		if($pieces[1] != "" && $value != "")
		{
			$form_vars[$pieces[1]][] = array("append" => $pieces[2], "value" => $value);
			if(isset($action['actions'][$pieces[1]]))
				unset($_REQUEST[$entry]);
		}
	}

	if(isset($action['actions']))
	{
		foreach($action['actions'] as $form_entry => $values)
			if(strcasecmp($values['input_type'],"hidden") != 0 && isset($values['required']) && $values['required'] == 1)
				if(!isset($form_vars[$form_entry]))
					$is_form_complete = 0;
	}
	else
		$is_form_complete = 0;
		
	$results = array();
	if($is_form_complete) // <- Exploit: allows user to execute any function. Ensure command is in allowed array.
		if(isset($action['process']))
			$results = $action['process']($form_vars,$action,$results);
	$res = array("form_complete" => $is_form_complete,"results" => $results);
	return $res;
}

function smart_process_mysql_form(&$sql)
{
	$search = &$sql['search'];
	$sql_pairs = &$sql['pairs'];

	$posts_not_required = &$sql['posts_not_required'];

	$posts_set = 0;
	
	$is_form_complete = 1;
	foreach($search as $field => $elements)
	{	
		$this_element = "frm_" . str_replace(".","_",$field);
		//check if field is required
		$is_required = isset($search[$field]['required']) ? $search[$field]['required'] : 0;
		$compare = isset($search[$field]['compare']) ? $search[$field]['compare'] : 0;
		if(!strcasecmp($compare,"between")) 
		{
			$this_element_to = $this_element . "_to";	
			$this_element .= "_from";
		}
		
		//check to see if field is hidden and if so, make sure the value hasn't changed.
		if(!strcasecmp($search[$field]['input_type'],"hidden") && (!isset($search[$field]['locked']) || $search[$field]['locked'] == true))
			$_REQUEST[$this_element] = $search[$field]['value'];
				
		//if a field has a source, make sure the returned value is on of the possible options
		$pairs = 0;
		if(isset($search[$field]['options']['source']['script']))
			$pairs = $search[$field]['options']['source']['script']($search[$field]['options']['source']['parameters']);
		//if(isset($search[$field]['options']['source']['ajax']))
		//	$pairs = $search[$field]['options']['source']['ajax']($search[$field]['options']['source']['parameters'],false);
		if(isset($search[$field]['options']['source']['pairs']))
			$pairs = $sql_pairs[$search[$field]['options']['source']['pairs']];
		
		if($pairs != 0)
		{
			if(is_array($_REQUEST[$this_element]))
			{
				foreach($_REQUEST[$this_element] as $index => $post_element)
				{
					if($post_element != "")
					{
						$found = false;
						foreach($pairs as $index => $info)
							if(!strcasecmp($info['value'],$post_element))
								$found = true;
						if(!$found) 
						{
							echo $post_element . " is not an option";
//							print_r($pairs);
//							echo "</pre>";
							return false;
						}
					}
				}
			}
			else
				if($_REQUEST[$this_element] != "")
				{
					$found = false;
					foreach($pairs as $index => $info)
						if(!strcasecmp($info['value'],$_REQUEST[$this_element]))
							$found = true;
					if(!$found) 
					{
						echo $_REQUEST[$this_element] . " is not an option";
//						echo "<pre>";
		//				print_r($pairs);
						//echo "</pre>";
						return false;
					}
				}
		}
		
		if( 
			(!$is_required) 
			|| ($is_required && isset($_REQUEST[$this_element]) && $_REQUEST[$this_element] != "")
		)
		{
			if($_REQUEST[$this_element] != NULL)
			{
				$posts_set++;
				
				$search[$field]['value'] = "";
				$post_element = $_REQUEST[$this_element];
				$post_element_to = isset($_REQUEST[$this_element_to]) ? $_REQUEST[$this_element_to] : NULL;
				if(isset($search[$field]['swap']))
				{
					$post_element = $search[$field]['swap']($post_element);
					$post_element_to = $search[$field]['swap']($post_element_to);
				}
				


				if(is_array($post_element))
				{
					if(strcasecmp($search[$field]['compare'],"BETWEEN") != 0)
					foreach($post_element as $element)
					{
						
						if($element == NULL) continue;
						//echo $this_element . " => " . $element . "<br>";
						if($search[$field]['enclose'])
							$element = $search[$field]['enclose'].$element.$search[$field]['enclose'];
							
						if(isset($search[$field]['date_format']))
							$element = date($search[$field]['date_format'],strtotime($element));

						$this_value = "\"" . quote_smart($element) . "\"";

						if(isset($search[$field]['convert']))
							$this_value = $search[$field]['convert'] . "($this_value)";
						
						$search[$field]['value'] .= ($search[$field]['value'] == "" ? "" : ",") . $this_value;
					}

					if($search[$field]['value'])
						$search[$field]['value'] = "(" . $search[$field]['value'] . ")";
				}
				else
				{
					$element = $post_element;
					
					if($search[$field]['enclose'])
						$element = $search[$field]['enclose'].$element.$search[$field]['enclose'];
					
					if(($search[$field]['compare'] == "like") && strpos($element,'%')===false)
						$search[$field]['compare'] = '=';
						
					if(isset($search[$field]['date_format']))
					{
						if(!strcasecmp($search[$field]['compare'],"BETWEEN"))
						{
							$element = date($search[$field]['date_format'] . " 00:00:00",strtotime($element));
							$post_element_to = date($search[$field]['date_format'] . " 23:59:59",strtotime($post_element_to));
						}
						else
							$element = date($search[$field]['date_format'],strtotime($element));
					}

					if($search[$field]['compare']=="LIKE")
						$this_value = "\"" . quote_smart($element) . "\"";
					elseif(!strcasecmp($search[$field]['compare'],"BETWEEN"))
						$this_value = "\"" . quote_smart($element) . "\" AND \"" . quote_smart($post_element_to) . "\"";
					else
						$this_value = "\"" . quote_smart($element) . "\"";

					if(isset($search[$field]['convert']))
						$this_value = $search[$field]['convert'] . "($this_value)";
					

					if(!strcasecmp($search[$field]['compare'],"IN"))
						$search[$field]['value'] = "('" . implode("','",preg_split('/[,\'" ]+/',$this_value)) . "')";
					else
						$search[$field]['value'] = $this_value;
				}
			}
		}
		else
		{
			etelPrint("$this_element must be set");
			$is_form_complete = 0;
		}
	}
	if($posts_set==0 && !$posts_not_required)
		return false;

	return $is_form_complete;
}

function smart_verifyFormValue($vals,$pair)
{
	return in_array($pair['value'],$vals);
}

function smart_search_elements($form_elements,$sql_pairs,$name_append="",$sql_results = "")
{
	foreach($form_elements as $input => $values)
	{
		$style = "";
		if(isset($values['style']))
			foreach($values['style'] as $element => $value)
				$style .= ($style == "" ? "" : " ") . "$element = \"$value\"";

		$action = "";
		if(isset($values['options']['source']['ajax']))
			$action = $values['options']['source']['parameters']['on_action'] . "=\"" . $values['options']['source']['parameters']['ajax_call'] . "(this.value)\"";
		if(isset($values['action']))
			$action = $values['action'];
		$this_element = "frm_" . str_replace(".","_",$input) . ($name_append !="" ? "_" . $name_append : "");
		
		$display =  (!isset($values['display']) ? $input : ( $values['display']!="" ? $values['display'] : ""));
		
		
		$pairs = NULL;
		if(isset($values['options']['source']['script']))
			$pairs = $values['options']['source']['script']($values['options']['source']['parameters']);
		if(isset($values['options']['source']['ajax']))
			$pairs = $values['options']['source']['ajax']($values['options']['source']['parameters']);
		if(isset($values['options']['source']['pairs']))
			$pairs = $sql_pairs[$values['options']['source']['pairs']];

		if($pairs != NULL)
		foreach($pairs as $index=>$pair)
			if(isset($pair['condition_var']))
				if($sql_results[$pair['condition_var']] != $pair['condition_val'])
				{
					$pairs[$index]['disabled'] = true;
					//unset($pairs[$index]);
				}
		$options = "";

		switch($values['input_type'])
		{
			case "hidden":
			break;
			case "company_search":
				$frm_entity[]  = array("input" => "<b>$display:</b>\r\n".genCompanyViewTable('','','full',false));
	
			break;
			case "select":
				if($display != "") $display = "<b>$display:</b>\r\n";
				if($pairs != NULL)
				foreach($pairs as $pair)
					$options .= "<option value='" . $pair['value'] . "' " . ($pair['disabled']?'disabled ':'') . ((isset($_REQUEST[$this_element]) && !strcasecmp($_REQUEST[$this_element],$pair['value'])) || (!isset($_REQUEST[$this_element]) && isset($pair['default'])) ? " selected " : ""). ">" . $pair['display'] . "</option>\r\n";
					
				$frm_entity[]  = array("name" => "$display&nbsp;",
								"input" => "<select id='$this_element' name='$this_element' $style $action>" . $options . "</select>");
			break;
			case "selectmulti":
				if($display != "") $display = "<b>$display:</b>";
				if($pairs != NULL)
				foreach($pairs as $pair)
					$options .= "<option value=\"" . $pair['value'] . "\"" . ((isset($_REQUEST[$this_element]) && smart_verifyFormValue($_REQUEST[$this_element],$pair)) ? " selected " : ""). ">" . $pair['display'] . "</option>\r\n";
				$frm_entity[]  = array("name" => "$display&nbsp;",
								"input" => "<select $action $style multiple id='$this_element' name='" . $this_element . "[]'>" . $options . "</select>");
			break;
			case "radio":
				if($display != "") $display = "<b>$display:</b>";
				if($pairs != NULL)
				foreach($pairs as $pair)
				{
					$checked = "";
					if(isset($_REQUEST[$this_element]) && !strcasecmp($_REQUEST[$this_element],$pair['value']))
						$checked = "checked";
					if(!isset($_REQUEST[$this_element]) && isset($pair['default']))
						$checked = "checked";
						
					$options .= "<input $action $style type='radio' id='$this_element' name='$this_element' value=\"" . $pair['value'] . "\" " . $checked . ">" . $pair['display'] . "</input>\r\n";
				}
				$frm_entity[]  = array("name" => "$display&nbsp;",
								"input" => $options);
			break;
			case "checkbox":
				if($display != "") $display = "<b>$display:</b>";
				if($pairs == NULL)
					$options .= "<input $action $style type='checkbox' id='$this_element' name='" . $this_element . "' value=\"" . $values['value'] . "\"" . ((isset($_REQUEST[$this_element]) && !strcasecmp($_REQUEST[$this_element],$values['value'])) ? " checked " : ""). ">" . $values['display'] . "</input><br>\r\n";
				else
				{
					$chkcnt = 0;
					foreach($pairs as $pair)
					{
						$checked = "";
						if(isset($_REQUEST[$this_element]) && smart_verifyFormValue($_REQUEST[$this_element],$pair))
							$checked = "checked";
						if(!isset($_REQUEST[$this_element]) && isset($pair['default']))
							$checked = "checked";
							
						$options .= ((!($chkcnt++%4) && $chkcnt!=1)?"<BR>":"")."<input $action $style type='checkbox' id='$this_element' name='" . $this_element . "[]' value=\"" . $pair['value'] . "\" $checked>" . $pair['display'] . "</input>\r\n";
					}
				}
				if($pairs != NULL)
					$frm_entity[]  = array("name" => "$display&nbsp;","input" => $options);
				else
					$frm_entity[]  = array("name" => "&nbsp;","input" => $options);
			break;
			case "between":
				if($display != "") $displayb = "<b>$display To:</b>"; else $displayb = "";
				if($display != "") $display = "<b>$display From:</b>";
				
				$frm_entity[]  = array("name" => "$display&nbsp;",
								"input" => "<input $action $style type='text' id='" . $this_element . "_to' name='" . $this_element . "_from' value=\"" . (isset($_REQUEST[$this_element . "_from"]) ? $_REQUEST[$this_element . "_from"] : date("Y-m-d 00:00:00")) . "\"></input>");
				$frm_entity[]  = array("name" => "$displayb&nbsp;",
								"input" => "<input $action $style type='text' id='" . $this_element . "_from' name='" . $this_element . "_to' value=\"" . (isset($_REQUEST[$this_element . "_to"]) ? $_REQUEST[$this_element . "_to"] : date("Y-m-d 23:59:59")) . "\"></input>");
			break;
			case "date_simple":
				$sel_month = isset($_REQUEST[$this_element . "_month"]) ? $_REQUEST[$this_element . "_month"] : date("n");
				$sel_year = isset($_REQUEST[$this_element . "_year"]) ? $_REQUEST[$this_element . "_year"] : date("Y");
	
				$months = array(
							"January",
							"February",
							"March",
							"April",
							"May",
							"June",
							"July",
							"August",
							"September","
							October",
							"November",
							"December"
							);
				$min_year =	!isset($values['min_year']) ? date("Y")-10 : $values['min_year'];
				$max_year =	!isset($values['max_year']) ? date("Y")+2: $values['max_year'];
				
				for($j=$min_year;$j<$max_year;$j++)
					$years[$j]=1;
			
				$month_html = "<select name='" . $this_element . "_month'>";
				foreach($months as $index => $month)
					if($index+1 != $sel_month)
						$month_html .= "<option value='" . ($index+1) . "'>$month</option>";
					else
						$month_html .= "<option selected value='" . ($index+1) . "'>$month</option>";
				$month_html .= "</select>";

				$year_html = "<select name='" . $this_element . "_year'>";
				foreach($years as $year => $value)
					if($sel_year != $year)
						$year_html .= "<option value='$year'>$year</option>";
					else
						$year_html .= "<option selected value='$year'>$year</option>";
				$year_html .= "</select>";
				
				$frm_entity[]  = array("name" => "<b>$display&nbsp;</b>","input"=>"$month_html&nbsp;$year_html");
				
			break;
			case "date":
				if($display != "") $displayb = "<b>$display To:</b>"; else $displayb = "";
				if($display != "") $display = "<b>$display From:</b>";

				if(!isset($values['default_from'])) $values['default_from'] = time();
				if(!isset($values['default_to'])) $values['default_to'] = time();
				
				$from_value = isset($_REQUEST[$this_element . "_from"]) ? $_REQUEST[$this_element . "_from"] : date("n/j/Y",$values['default_from']);
				$to_value = isset($_REQUEST[$this_element . "_to"]) ? $_REQUEST[$this_element . "_to"] : date("n/j/Y",$values['default_to']);
				
				if($to_value == "" || $from_value == "")
					break;

				$this_time = strtotime($from_value);
				
				$options_years = func_fill_year((isset($_REQUEST[$this_element . "_from_year"]) ? $_REQUEST[$this_element . "_from_year"] : date("Y",$this_time)),true);
				$options_months = func_fill_month((isset($_REQUEST[$this_element . "_from_month"]) ? $_REQUEST[$this_element . "_from_month"] : date("n",$this_time)),true);
				$options_days = func_fill_day((isset($_REQUEST[$this_element . "_from_day"]) ? $_REQUEST[$this_element . "_from_day"] : date("j",$this_time)),32,true);

				$frm_entity[]  = array("name" => "$display&nbsp;","input"=>"
						<script language=\"javascript\">
							function func_returnselectedindex(par_selected)
							{
								var dt_new =  new Date();
								var str_year = dt_new.getFullYear()
								for(i=2003,j=0;i<str_year+10;i++,j++)
								{
									if (i==par_selected)
									{
										return j;
									}
								}
							}						
							function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
							{
								if (obj_element.name == \"" . $this_element . "_from\")
								{
									document.search_form." . $this_element . "_from_day.selectedIndex = dateSelected-1 ;
									document.search_form." . $this_element . "_from_month.selectedIndex = monthSelected ;
									document.search_form." . $this_element . "_from_year.selectedIndex = func_returnselectedindex(yearSelected) ;
								}
								if (obj_element.name == \"" . $this_element . "_to\")
								{
									document.search_form." . $this_element . "_to_day.selectedIndex = dateSelected-1 ;
									document.search_form." . $this_element . "_to_month.selectedIndex = monthSelected ;
									document.search_form." . $this_element . "_to_year.selectedIndex = func_returnselectedindex(yearSelected) ;
								}
							}							
							function func_" . $this_element . "_from()
							{
								var year = document.search_form." . $this_element . "_from_year.value;
								var month = document.search_form." . $this_element . "_from_month.value;
								var day = document.search_form." . $this_element . "_from_day.value;
								document.search_form." . $this_element . "_from.value = month + '/' + day + '/' + year;
							}
						</script>
						<select onChange=\"func_" . $this_element . "_from()\" id=\"" . $this_element . "_from_month\" name=\"" . $this_element . "_from_month\" style=\"font-size:10px\" disableOnSubmit='1' >
						$options_months
						</select>
						<select onChange=\"func_" . $this_element . "_from()\" id=\"" . $this_element . "_from_day\" name=\"" . $this_element . "_from_day\" class=\"lineborderselect\" style=\"font-size:10px\" disableOnSubmit='1'>
						$options_days
						</select> 
						<select onChange=\"func_" . $this_element . "_from()\" id=\"" . $this_element . "_from_year\" name=\"" . $this_element . "_from_year\" style=\"font-size:10px\" disableOnSubmit='1'>
						$options_years
						</select>
						<input type=\"hidden\"  id='" . $this_element . "_from' name='" . $this_element . "_from' size=\"17\" value=\"" . $from_value . "\">
						<input style=\"font-family:verdana;font-size:10px;\" type=\"button\" value=\"...\" onclick=\"init(\$F('MousePointX'),\$F('MousePointY'),\$('" . $this_element . "_from'))\">
						");										


				$this_time = strtotime($to_value);
				$options_years = func_fill_year((isset($_REQUEST[$this_element . "_to_year"]) ? $_REQUEST[$this_element . "_to_year"] : date("Y",$this_time)),true);
				$options_months = func_fill_month((isset($_REQUEST[$this_element . "_to_month"]) ? $_REQUEST[$this_element . "_to_month"] : date("n",$this_time)),true);
				$options_days = func_fill_day((isset($_REQUEST[$this_element . "_to_day"]) ? $_REQUEST[$this_element . "_to_day"] : date("j",$this_time)),32,true);

				$frm_entity[]  = array("name" => "$displayb&nbsp;","input"=>"
						<script language=\"javascript\">
							function func_" . $this_element . "_to()
							{
								var year = document.search_form." . $this_element . "_to_year.value;
								var month = document.search_form." . $this_element . "_to_month.value;
								var day = document.search_form." . $this_element . "_to_day.value;
								document.search_form." . $this_element . "_to.value = month + '/' + day + '/' + year;
							}
						</script>						
						<select onChange=\"func_" . $this_element . "_to()\" id=\"" . $this_element . "_to_month\" name=\"" . $this_element . "_to_month\" style=\"font-size:10px\" disableOnSubmit='1'>
						$options_months
						</select>
						<select onChange=\"func_" . $this_element . "_to()\" id=\"" . $this_element . "_to_day\" name=\"" . $this_element . "_to_day\" class=\"lineborderselect\" style=\"font-size:10px\" disableOnSubmit='1'>
						$options_days
						</select> 
						<select onChange=\"func_" . $this_element . "_to()\" id=\"" . $this_element . "_to_year\" name=\"" . $this_element . "_to_year\" style=\"font-size:10px\" disableOnSubmit='1'>
						$options_years
						</select>
						<input type=\"hidden\"  id='" . $this_element . "_to' name='" . $this_element . "_to' size=\"17\" value=\"" . $to_value . "\">
						<input style=\"font-family:verdana;font-size:10px;\" type=\"button\" value=\"...\" onclick=\"init(\$F('MousePointX'),\$F('MousePointY'),\$('" . $this_element . "_to'))\">
						");										
			break;
			case "textarea":
				if($display != "") $display = "<b>$display:</b>";
				$frm_entity[]  = array("name" => "$display&nbsp;",
								"input" => "<textarea $action $style id='$this_element' name='$this_element' $style>" . (isset($_REQUEST[$this_element]) ? $_REQUEST[$this_element] : $values['value']) . "</textarea>");
			break;
			case "text":
			default:
				if($display != "") $display = "<b>$display:</b>";
				$frm_entity[]  = array("name" => "$display&nbsp;",
								"input" => "<input $action $style type='text' id='$this_element' name='$this_element' value=\"" . (isset($_REQUEST[$this_element]) ? $_REQUEST[$this_element] : $values['value']) . "\"></input>");
			break;
		}
	}
	return $frm_entity;
}

function smart_search_form($sql)
{
	global $tmpl_dir;
	
	$limit = isset($sql['limit']) ? $sql['limit'] : NULL;
	$search = $sql['search'];
	$action = $sql['postpage'];
	$search_title = (isset($sql['title']) ? $sql['title'] : "Search Form");
	
	$frm_entity = smart_search_elements($search,$sql['pairs']);
	
	beginTable();
?>
	<script language="javascript" src="../scripts/calendar_new.js"></script>
	<script>

	<!-- Idea by:  Nic Wolfe (Nic@TimelapseProductions.com) -->
	<!-- Web URL:  http://fineline.xs.mw -->
	
	<!-- This script and many more are available free online at -->
	<!-- The JavaScript Source!! http://javascript.internet.com -->
	
	<!-- Begin
	function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=800,height=600');");
	}
	// End -->

	<!-- Original:  CodeLifter.com (support@codelifter.com) -->
	<!-- Web Site:  http://www.codelifter.com -->
	
	<!-- This script and many more are available free online at -->
	<!-- The JavaScript Source!! http://javascript.internet.com -->
	
	var IE = document.all?true:false;
	if (!IE) document.captureEvents(Event.MOUSEMOVE)
	document.onmousemove = getMouseXY;
	var tempX = 0;
	var tempY = 0;
	function getMouseXY(e) 
	{
		if (IE) 
		{ // grab the x-y pos.s if browser is IE
			tempX = event.clientX + document.body.scrollLeft;
			tempY = event.clientY + document.body.scrollTop;
		}
		else 
		{  // grab the x-y pos.s if browser is NS
			tempX = e.pageX;
			tempY = e.pageY;
		}  
		if (tempX < 0){tempX = 0;}
		if (tempY < 0){tempY = 0;}  
		$('MousePointX').value = tempX;
		$('MousePointY').value = tempY;
		return true;
	}
	
	function submit_results_form(order,dir,smart_anchor)
	{
		var old_order = '<?=$_REQUEST['smart_order']?>';
		if(old_order != order) dir = 1;
		document.getElementById('smart_order').value = order;	
		document.getElementById('smart_order_dir').value = dir;	
		document.getElementById('smart_anchor').value = smart_anchor;	
		document.getElementById("search_form").submit();
	}
	function jump_to_anchor(name)
	{
		location.href = ("#" + name);
	}
	function validateForm(form)
	{
		elements = Form.getElements(form);
		elements.each( function(el){
		
			switch(el.type)
			{
				case 'checkbox':
					if(!el.checked) 
						el.disabled=true;
						
					break;
				case 'radio':
					if(!el.checked || el.value==0) 
						el.disabled=true;
					
					break;
				default:
					if(!el.value || el.value==0) el.disabled=true;
					
			
			}
			if(el.getAttribute('disableOnSubmit')) el.disabled = true;
		});
		self.setTimeout('resetForm($("'+form.id+'"))', 3000);
		return true;
	}
	function resetForm(form)
	{
		elements = Form.getElements(form);
		elements.each( function(el){
			el.disabled = false;
		});
		return true;
	}
	</script>
	<?
		/*
		if(isset($sql['onload']))
			echo "<body onLoad=\"" . $sql['onload'] . "\">";
		if(isset($_REQUEST['smart_anchor']) && $_REQUEST['smart_anchor'] != "")
			echo "<body onLoad=\"jump_to_anchor('" . $_REQUEST['smart_anchor'] . "')\">";
		*/
	?>
	<input type='hidden' id='MousePointX'>
	<input type='hidden' id='MousePointY'>
	<input type='hidden' id='smart_order' name='smart_order' value='<?=(isset($_REQUEST['smart_order']) ? $_REQUEST['smart_order'] : "")?>'>
	<input type='hidden' id='smart_order_dir' name='smart_order_dir' value='<?=(isset($_REQUEST['smart_order_dir']) ? $_REQUEST['smart_order_dir'] : "")?>'>
	<input type='hidden' id='smart_anchor' name='smart_anchor' value=''>
	<? if($limit != NULL) { ?>
	<input type="hidden" id="<?="frm_" . $limit['offset_source']?>" name="<?="frm_" . $limit['offset_source']?>" value="<?=(isset($_REQUEST["frm_" . $limit['offset_source']]) ? $_REQUEST["frm_" . $limit['offset_source']] : "0")?>">
	<? } ?>
			<table class="invoice" width="100%" border="0" cellspacing="0" cellpadding="0">
<?		
	foreach($frm_entity as $entity)
	{
		echo "<tr>\r\n";
		if(isset($entity["name"]) && $entity["name"] != "")
		{
			echo "<td valign='top'>" . $entity["name"] . "</td>\r\n";
			echo "<td valign='top'>" . $entity["input"] . "</td>\r\n";
		}
		else
			echo "<td colspan=2 align=left>" . $entity["input"] . "</td>\r\n";
		echo "</tr>\r\n";
		echo "<tr><td colspan=2 height='7px'></td></tr>";
	}
	echo "</table>\r\n";
	endTable($search_title,$action,NULL,NULL,TRUE,"search_form",true,'get');
}

function smart_display_type($param,&$info,&$row)
{

	$final_param = $param;

	if($info['disp_decimal'] && $param)
		$final_param = number_format($final_param,2);
		
	if($info['disp_append_front'] && $param)
		$final_param = $info['disp_append_front'].$final_param;
	if($info['disp_append_back'] && $param)
		$final_param = $final_param.$info['disp_append_back'];
	
	if($info['disp_clip'])
	{
		$extra_options = "";
		$w = $info['disp_clip']['w']; $h = $info['disp_clip']['h'];
		if(!$w) $w='160px'; 
		if(!$h) $h='56px';
		if($info['disp_clip']['overflow']) 
		{
			if($info['disp_clip']['resize']) $newheight = "this.style.height=".$info['disp_clip']['resize'].";";
			$extra_options .= "onmousemove='this.style.overflow = \"auto\"; $newheight' onmouseout='this.style.overflow = \"hidden\";'";
		}
		
		$final_param = "<div style='overflow: hidden; width:$w; height:$h;' $extra_options >".$final_param."</div>";
	}

	if($info['disp_editable'])
	{
		$key = $info['source'].'_'.$row[$info['disp_editable']['src']];
		$size = $info['disp_editable']['size'];
		if(!$size) $size = '10';
		$final_param = "<input type='textfield' name='$key' id='$key' size='$size' value='$param' style='display:none'><div id='$key"."_div'>".$final_param."</div>";				
	}
	return $final_param;
}

function smart_render_results(&$results,$sql)
{
	global $tmpl_dir,$etel_debug_mode;
	
	$fields = $sql['return'];
	$num_rows = $results['num_rows'];
	$actions = isset($sql['result_actions']) ? $sql['result_actions'] : 0;
	
	$key = $sql['key'];
	
	@ksort($fields);
	@reset($fields);

	if(isset($sql['limit']))
	{	

		$max_offset = $num_rows;
		$per_page = $_REQUEST["frm_" . $sql['limit']['count_source']];
		if(!$per_page) $per_page = 50;
?>
	<script>
	function submit_page_form(dir)
	{
		var old_offset = <?=(isset($_REQUEST["frm_" . $sql['limit']['offset_source']]) ? $_REQUEST["frm_" . $sql['limit']['offset_source']] : 0)?>;
		var new_offset = parseInt(dir);
		
		if(new_offset > <?=$max_offset?>) new_offset = <?=(intval($max_offset/$per_page))*$per_page?>;
		if(new_offset < 0) new_offset = 0;

		document.search_form.<?="frm_" . $sql['limit']['offset_source']?>.value = new_offset;	
		document.search_form.submit();
	}
	</script>
<?
	}
	if(sizeof($sql['subquery']['queries']))
	{
		echo "<a name='subquery_header' id='subquery_header'></a>\n";
		beginTable();
		ksort($sql['subquery']['queries']);
		reset($sql['subquery']['queries']);
		
		if(sizeof($results['sub_row'])>6)
			echo "<div style=' #ff0000; height : 500px; overflow : auto; '>";	
			
		if(is_array($results['sub_row']))
		foreach($results['sub_row'] as $sub_row)
		{
			$query_disp = array();
			$query_title = NULL;
			foreach($sql['subquery']['queries'] as $title => $info)
			{
				$display = $sub_row[$info['name']];
				$display = smart_display_type($display,$info,$sub_row);
					
				if(!isset($info['hidden']) || $info['hidden'] == 0)
				{
					$name = explode("|",$title);
					$name = $name[1];
					
					$query_disp[] = array("name" => $name, "value" => $display);
				}
			}
			
			if($sub_row['subgroup_grouptitle']) $sub_row['subgroup_title'] = $sub_row['subgroup_grouptitle'];
			if($sub_row['subgroup_title'])
				$query_title = array("name" => "Title", "value" => $sub_row['subgroup_title'],'class'=>($sub_row['is_rollup']?'infoHeader2':'infoHeader'));
				
			if(sizeof($query_disp)>0)
			{
		?>
		  <table class="invoice" width="100%" border="0" cellspacing="0" cellpadding="0">
		
		<?	
			$total_stats = sizeof($query_disp);
			$per_page = 5;
			$num_pages = ceil($total_stats / $per_page);
			if($query_title)
			{
				echo "<tr>";
					echo "<td colspan='$per_page' align='center'><div class='".$query_title['class']." border'>".$query_title['value']."</div></td>";
				echo "</tr>";
			}
			$subwidth = intval(100/$per_page);
			
			for($j=0;$j<$num_pages;$j++)
			{
				echo "<tr>";
				for($k=$j * $per_page;$k < $j * $per_page + $per_page && $k<$total_stats;$k++)
					echo "<td width='$subwidth%'><b>" . $query_disp[$k]['name'] . "</b></td>";
				for($l = $k - $j * $per_page;$l<$per_page;$l++)
					echo "<td width='$subwidth%'></td>";
				echo "</tr>";
				echo "<tr class='row" . gen_row(0,2) . "'>";
				for($k=$j * $per_page;$k < $j * $per_page + $per_page && $k<$total_stats;$k++)
					echo "<td width='$subwidth%'>" . $query_disp[$k]['value'] . "</td>";
				for($l = $k - $j * $per_page;$l<$per_page;$l++)
					echo "<td width='$subwidth%'></td>";
				echo "</tr>";
			}
		?>
			</table>
			
		<?
			}
		}
		if(sizeof($results['sub_row'])>4)
			echo "</div>";
		endTable($sql['subquery']['title']);
	}

	if(isset($sql['limit']))
	{
		$curr_offset = $_REQUEST["frm_" . $sql['limit']['offset_source']];
		$max_pages = $num_rows;
		//if(!strcasecmp($sql['limit']['max_offset_source'],"result"))
		//	$max_pages = $results['sub_row'][0][$max_pages];
		$per_page = $_REQUEST["frm_" . $sql['limit']['count_source']];
		if(!$per_page) $per_page = 50;
		if($curr_offset>$max_pages) $curr_offset=$max_pages;
		if($curr_offset<0)$curr_offset=0;
		$nav_data='';
		if($max_pages > 0)
		{
			$size = 16;
			$this_page = floor($curr_offset/$per_page);
			$nav_data .= "Displaying Results " . ($curr_offset+1) . " - " . (($curr_offset + $per_page) <= $max_pages ? $curr_offset + $per_page : $max_pages) . " of $max_pages in ".ceil($max_pages/$per_page)." pages.<br>";
			$pages = array();
			for($j=0;$j<ceil($max_pages/$per_page);$j++)
				$pages[$j] = $j * $per_page;
			
			if($this_page > 0)
				$nav_data .=  "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_page_form(" . ($this_page-1) * $per_page . ");\"><u>Prev</u></a>";
					
			$m = ceil($max_pages/$per_page);
			if($m > 1)
			{
				if($this_page > 0)
					$nav_data .=  "&nbsp;|&nbsp;";
				$startnum = ($this_page < intval($size/2)?0:$this_page-intval($size/2));
				$finishnum = ($this_page > $m-intval($size/2)?$m:$this_page+intval($size/2));
				
				for($j=$startnum;$j<$finishnum;$j++)
				{
					if($this_page != $j)
						$nav_data .=  "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_page_form(" . $pages[$j] . ");\"><u>" . ($j+1) . "</u></a>";
					else
						$nav_data .=  "<b>" . ($j+1) . "</b>";
					if($j<$finishnum-1)
						$nav_data .=  "&nbsp;|&nbsp;";
				}
			}
			
			if($this_page < $m - 1)
			{
				if($this_page > 0 || $m > 1)
					$nav_data .=  "&nbsp;|&nbsp;";
				$nav_data .=  "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_page_form(" . ($this_page+1) * $per_page . ");\"><u>Next</u></a>";
			}	
				
			if($this_page > 0 || $m > 1)
			{	
				$nav_data .=  "&nbsp;|&nbsp;";
				$nav_data .=  "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_page_form(parseInt(".'$F'."('goto_field')-1)*parseInt(" .  $per_page . "));\"><u>Goto</u></a>&nbsp;<textarea style='width:12px;height:16px;font-size:12px; overflow:hidden; border: 1px solid #000; ' id='goto_field' /></textarea>";
			}
		}
	}
		
	if(!$sql['skip_query'])
	{
		
		echo "<a name='query_header' id='query_header'></a>\n";
		beginTable();
			
		if(sizeof($results['rows']))
		{
		//if(isset($_REQUEST))
		//	foreach($_REQUEST as $element => $values)
		//		if(!is_array($values))
		//			echo "<input type='hidden' name='$element' value='$values'>\r\n";
		//		else
		//			foreach($values as $index => $value)
		//				echo "<input type='hidden' name='" . $element . "[]' value='$value'>\r\n";
	
		
			echo $nav_data;		
	?>
	  <table class="invoice" width="100%" border="0" cellspacing="0" cellpadding="0">
	<?	
			$table_columns = sizeof($fields)*2;
		
			
			echo "<tr class='infoHeader'><td colspan=$table_columns><a name=\"$name\"></a><div class='border'>&nbsp;&nbsp;&nbsp;&nbsp;</div></td></tr>\r\n";
			
			$row_contents = "";
			foreach($fields as $this_key=>$column)
			{
				$this_key = explode("|",$this_key);
				$this_key = $this_key[1];
	
				if(!isset($column['hidden']) || $column['hidden'] == 0)
				{
					$column_name = (isset($column['display']) && $column['display'] != "" ? $column['display'] : $this_key);
					
					if(isset($sql['user_orderby'][$column['column']]))				
					{
						$dir = ($_REQUEST['smart_order_dir']+1)%2;
						$column_name = '<u>'.$column_name.'</u>';
						if(!strcasecmp($sql['user_orderby'][$column['column']],$_REQUEST['smart_order']))
							$column_name = ($dir ? "&uarr;" : "&darr;").$column_name;
						$column_name = "<a name=\"\" onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" onClick=\"submit_results_form('" . $sql['user_orderby'][$column['column']] . "','" . (!isset($_REQUEST['smart_order_dir']) ? 0 : $dir) . "','$name');\">" . $column_name . "</a>";
					}
					
					$row_contents .= ($row_contents != "" ? "<td>&nbsp;&nbsp;&nbsp;</td>" : "") . "<td>" . $column_name . "</td>";
				}
			}
			if($actions['actions']!=NULL)
				$row_contents .= ($row_contents != "" ? "<td>&nbsp;&nbsp;&nbsp;</td>" : "") . "<td><b>Actions</b></td>";
	
			$header_fields = "<tr class='infoBold' style='text-align:center'>" . $row_contents . "</tr>\r\n";
			$rows_num = 0;
			echo $header_fields;
								
			foreach($results['rows'] as $this_key=>$rows)
			{
						
				$header = trim($this_key);
				if($header)
					$name = md5($header);
				else
					$name = md5("header");
				if($header)
					echo "<tr><td>&nbsp;</td></tr><tr class='infoHeader'><td colspan=$table_columns><a name=\"$name\"></a><div class='border'>$header</div></td></tr>\r\n";
		

				foreach($rows as $this_key => $row)
				{
					$row_contents = "";
					if($rows_num && $rows_num % 50 == 0)
						echo $header_fields;
					$rows_num++;
						
					foreach($fields as $name=>$info)
						if(!isset($info['hidden']) || $info['hidden'] == 0)
						{
						
							$style = "";
							if(isset($info['style']))
								foreach($info['style'] as $element => $value)
									$style .= ($style == "" ? "" : " ") . "$element = \"$value\"";
									
							//echo "<td>\n";
							
							$display = "";
		
							$display = $row[$info['column']];
						
							if(isset($info['link']))
								$display = preg_replace('/(http[s]?:\/\/)?(www\.)?/','',$display);
								
							if(isset($info['crop']))
							{
								$temp = substr($display,0,$info['crop']);
								if(strcmp($display,$temp)!=0)
									$display = $temp . "...";
								else
									$display = $temp;
							}
							if(isset($info['link']))
							{
								if(is_array($info['link']['destination']))
								{
									if($info['link']['destination']['external'] && strpos($destination,'http')===false) $destination ="http://".$destination;
									$destination = (strcasecmp($info['link']['destination']['source'],"result") == 0 ? ($row[$info['link']['destination']['value']]) : $info['link']['destination']['value']) ;
								}
								else
									$destination = $info['link']['destination'];
								
									
								$parameters = "";
								if(isset($info['link']['parameters']))
								{
									foreach($info['link']['parameters'] as $index => $settings)
										$parameters .= ($parameters == "" ? "?" : "&") . $settings['name'] . "=" . (strcasecmp($settings['source'],"result") == 0 ? urlencode($row[$settings['value']]) : $settings['value']);
								}
								if(!isset($info['link']['popup']))
									$display = "<a href='$destination$parameters'>$display</a>";
								else
									$display = "<a href=\"javascript:" . $info['link']['popup']['script'] . "('$destination?$parameters')\">$display</a>";
							}
							$display = smart_display_type($display,$info,$row);
							if(isset($info['mouseover']))
								if(!isset($info['mouseover_source']) || !strcasecmp($info['mouseover_source'],"query"))
									$display = "<a name='' onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" title='" . $row[$info['mouseover']] . "'>$display</a>";
								else
									$display = "<a name='' onMouseOver=\"this.style.cursor='pointer'\" onMouseOut=\"this.style.cursor='default'\" title='" . $info['mouseover'] . "'>$display</a>";
							
							$row_contents .= ($row_contents != "" ? "<td></td>" : "") . "<td $style>" . $display . "</td>";
						}
						
					if($actions['actions']!=NULL)
					{
						$append = "";
						if(isset($actions['append']))
							if(!strcasecmp($actions['append']['source'],"result"))
								$append = $row[$actions['append']['name']];
							else
								$append = $actions['append']['name'];
		
						$sec_form_elements = smart_search_elements($actions['actions'],$sql['pairs'],$append,$row);
						$sec_form = "";
						foreach($sec_form_elements as $sec_form_element)
							$sec_form .= ($sec_form == "" ? "" : "<br>") . $sec_form_element['input'];
		
						$row_contents .= ($row_contents != "" ? "<td></td>" : "") . "<td>" . $sec_form . "</td>";
					}
					echo "<tr class='row" . gen_row(1) . "'>" . $row_contents . "</tr>\r\n";
				}
			}
			echo "<tr class='infoHeader'><td colspan=$table_columns><a name=\"$name\"></a><div class='border'>&nbsp;&nbsp;&nbsp;&nbsp;</div></td></tr>\r\n";
			
			echo "</table></p>\r\n";
			
			echo $nav_data;		
		
		}
		else
			echo "<div class='report'><div class='infoHeader'>No Results</div></div>\r\n";
			
		$title = isset($actions['title']) ? $actions['title'] : "Search Results";
		if($actions!=0)
			endTable($title,"",NULL,NULL,TRUE,"results_form");
		else
			endTable($title);
	}
	
	echo "<script>jump_to_anchor('subquery_header');</script>\r\n";

}

function smart_render_action_results($results,$title)
{
	if($results['form_complete'] != 0)
	{
		beginTable();
		echo "<table class='report' width='100%' border='0' cellspacing='0' cellpadding='0'>";
		foreach($results['results'] as $result)
		{
			echo "<tr class='row" . gen_row(1) . "'>";
			echo "<td>" . $result['action'] . "</td><td>" . $result['status'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		endTable($title);
	}
}

function smart_render_export(&$result,&$my_sql)
{
	$visiblesubkeys = array('subgroup_title'=>'Title');
	ksort($my_sql['subquery']['queries']);
	foreach($my_sql['subquery']['queries'] as $title => $info)
		if(!isset($info['hidden']) || $info['hidden'] == 0) 
			{ $title = explode("|",$title); $visiblesubkeys[$info['name']] = $title[1];}
				
	if($result['sub_row'])
	{
		$line = '';
		
		foreach($visiblesubkeys as $key=>$name)
			$line .= ($line?",":"").'"'.$visiblesubkeys[$key].'"';
		echo $line."\n";
		
		
		//foreach($result['sub_row'][0] as $key=>$row)
		//{
		//	if($visiblesubkeys[$key])
		//		$line .= ($line?",":"").'"'.$visiblesubkeys[$key].'"';
		//}
		flush();
		foreach($result['sub_row'] as $row)
		{
			if($row['subgroup_grouptitle']) $row['subgroup_title'] = $row['subgroup_grouptitle'];	
			$line = '';
			
			foreach($visiblesubkeys as $key=>$name)
				$line .= ($line?",":"").'"'.smart_fix_export_value($row[$key]).'"';
			
			//foreach($row as $key=>$data)
			//{
			//	if($visiblesubkeys[$key])
			//		$line .= ($line?",":"").'"'.smart_fix_export_value($data).'"';
			//}
			echo $line."\n";
			flush();
		}
		echo "\n\n";
		flush();
	}
	ksort($my_sql['return']);
	$visiblesubkeys = array();
	foreach($my_sql['return'] as $title => $info)
		if(!isset($info['hidden']) || $info['hidden'] == 0) 
			{ $title = explode("|",$title); $visiblesubkeys[$info['column']] = $title[1];}
	
	// TODO: Sort by Visible Keys
	if($result['rows'])
	{
		$line = '';
		foreach($result['rows'] as $krkey=>$keyrow)
		{
			foreach($keyrow as $rowkey=>$row)
				foreach($row as $key=>$data)
					$keyar[$key]=$key;
				
			foreach($keyar as $key)
				if($visiblesubkeys[$key])
					$line .= ($line?",":"").'"'.$visiblesubkeys[$key].'"';
					
			echo $line."\n";
			flush();
			foreach($keyrow as $rowkey=>$row)
			{
				$line = '';
				foreach($keyar as $key)
					if($visiblesubkeys[$key])
						$line .= ($line?",":"").'"'.smart_fix_export_value($result['rows'][$krkey][$rowkey][$key]).'"';
				echo $line."\n";
				flush();
			}
		}
	}
}

function smart_fix_export_value($str)
{
	$str = str_replace('"','`',$str);
	$str = preg_replace('/ ?\([0-9]*\) ?/','',$str);
	$str = preg_replace('/<BR[^>]*>/i'," - ",$str);
	$str = strip_tags($str);
	return $str;
}

?>