<?
function etel_record_click(&$data)
{
	$data['tc_clicker_ID'] = etel_get_clicker_ID($data);
	$data['tc_this_tu_ID'] = etel_get_url_ID($data['this_url']);
	if($data['refer_url']) 
		$data['tc_refer_tu_ID'] = etel_get_url_ID($data['refer_url']);
	if($data['Affiliate_Ref'])
		$data['tc_affiliate_ID'] = etel_get_affiliate_ID($data['Affiliate_Ref']);
	if($data['tc_affiliate_ID']==false)
		unset($data['Affiliate_Ref']);
	if($data['Merchant_Ref'])
		$data['tc_en_ID'] = etel_get_affiliate_ID($data['Merchant_Ref']);
		
	$sql = "Insert Into cs_tracking_click set";
	if($data['tc_refer_tu_ID'])  $sql .= " tc_refer_tu_ID = '".intval($data['tc_refer_tu_ID'])."', ";
	if($data['tc_affiliate_ID']) $sql .= " tc_affiliate_ID = '".intval($data['tc_affiliate_ID'])."', ";
	if($data['tc_en_ID']) $sql .= " tc_en_ID = '".intval($data['tc_en_ID'])."', ";
	$sql .= "	
		tc_clicker_ID = '".intval($data['tc_clicker_ID'])."', 
	 	tc_this_tu_ID = '".intval($data['tc_this_tu_ID'])."', 
		tc_time = NOW() "; 
	$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
	$data['tc_ID'] = mysql_insert_id();
}

function etel_get_host_ID($host)
{
	$host = preg_replace('/[^0-9a-z._-]/','',strtolower($host));
	$host = str_replace('www.','',$host);
	if($host=='localhost') $host = '';
	$sql = "select th_ID from cs_tracking_host where 
		th_host = '$host' 
	";
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	if(mysql_num_rows($result))
		return mysql_result($result,0,0);
		
	$sql = "INSERT INTO cs_tracking_host set 
		th_host = '$host'
	";	
	$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
	return mysql_insert_id();
}

function etel_get_url_ID($url)
{
	$url_info = parse_url($url);
	$full_path = $url_info['path'].($url_info['query']?"?".$url_info['query']:'');
	$url_info['tu_th_ID'] = etel_get_host_ID($url_info['host']);
	
	$sql = "select tu_ID from cs_tracking_url where 
		tu_th_ID = '".intval($url_info['tu_th_ID'])."' &&
		tu_URL =  '".quote_smart($full_path)."' 
	";
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	if(mysql_num_rows($result))
		return mysql_result($result,0,0);
		
	$sql = "INSERT INTO cs_tracking_url set 
		tu_th_ID = '".intval($url_info['tu_th_ID'])."',
		tu_URL =  '".quote_smart($full_path)."'
	";	
	$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
	return mysql_insert_id();
}

function etel_get_affiliate_ID($affiliate_ID)
{
	$sql = "select en_ID from cs_entities where 
		en_ref = '".quote_smart($affiliate_ID)."' ";
		
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	if(mysql_num_rows($result))
		return mysql_result($result,0,0);
	return false;
}

function etel_get_clicker_ID(&$data)
{
	$sql_select = " select tk_ID,tk_ref from cs_tracking_clicker ";
	if($data['Clicker_Ref']) 
	$sql .= "$sql_select where tk_ref = '".quote_smart($data['Clicker_Ref'])."' 
		UNION
		";
		
	$sql .= "$sql_select where tk_host = '".quote_smart($data['host_name'])."'
		UNION
		$sql_select where tk_IP = INET_ATON( '".$data['ip_address']."' ) ";
		
	$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	if(mysql_num_rows($result))
	{
		$clicker = mysql_fetch_assoc($result);
		$data['tc_clicker_ID'] = $clicker['tk_ID'];
		$data['Clicker_Ref'] = $clicker['tk_ref'];
		return $clicker['tk_ID'];
	}	
	$new_tk_ref = substr(md5(serialize($data)),0,32);
	$sql = "INSERT INTO cs_tracking_clicker set 
		tk_IP = INET_ATON( '".$data['ip_address']."' ),
		tk_host = '".quote_smart($data['host_name'])."', 
		tk_ref = '$new_tk_ref' 
	";	
	$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
	$data['tc_clicker_ID'] = mysql_insert_id();
	$data['Clicker_Ref'] = $new_tk_ref;
	return $data['tc_clicker_ID'];
}



?>