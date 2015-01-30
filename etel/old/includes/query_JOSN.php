<?php
$etel_debug_mode=0;
$etel_singleview_allowed=1;
$allowBank=true;
$noHeaderOutput=true;
require_once("includes/sessioncheck.php");
$rootdir = "../";

require_once("includes/header.php");
require_once($rootdir."includes/JSON.php");
require_once($rootdir."includes/completion.php");



$data = NULL;
switch($_REQUEST['func'])
{
	case 'getCompanyInfo': 
	{            
		$search_array = array('cn'=>'companyname','ri'=>'ReferenceNumber','un'=>'username','em'=>'email');
		$limit_array = array('tt'=>'transaction_type','cp'=>'cd_completion','bi'=>'bank_Creditcard','bp'=>'td.bank_id','gi'=>'gateway_id');
		
		$ignore = "cd_ignore=0 ";
		if($_REQUEST['showall']) $ignore = "1";
		if($_REQUEST['limit_to']>=1) $limit_to = intval($_REQUEST['limit_to']);
		else $limit_to = 100;
		
		$sql_where = "";
		
		if($_REQUEST['search'] && $_REQUEST['searchby'] && $search_array[$_REQUEST['searchby']])
			$sql_where .=" and ".$search_array[$_REQUEST['searchby']]." like '%".quote_smart($_REQUEST['search'])."%'";
					
		if($_REQUEST['search'] && $_REQUEST['searchby']=='id')
		{
			$batch_list = quote_smart($_REQUEST['search']);
			$batch_array = preg_split('/[^0-9]+/',$batch_list);
			$sql_user_list = "";
			foreach($batch_array as $key=>$val)
				$sql_user_list .= ','.intval($val);//$batch_array[$key] = intval($data);

			$sql_where .=" and cd.userId in (-1$sql_user_list)";
		}
		
		foreach($limit_array as $var=>$key)
			if($_REQUEST[$var]) $sql_where .= " and $key='".quote_smart($_REQUEST[$var])."'";
	
		$sql = "select cd.userId as ui,companyname as cn,cd_completion as cp from cs_companydetails cd
		 where $ignore $bank_sql_limit $sql_where   order by companyname limit $limit_to";
		 //		left join cs_transactiondetails td on cd . userId =td . userId 
		 //group by cd.userId


		$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
		$i=0;
		while($company = mysql_fetch_assoc($result))
		{
			$company_list[] = $company;
		}
		$sql = "select cs_ID as ci,cs_name as cn,cs_company_ID as cui FROM `cs_company_sites` as cs 
		left join `cs_companydetails` cd on `cs_company_id` = cd.`userId`
		 where $ignore $bank_sql_limit $sql_where and cs_verified='approved' order by cs_URL limit $limit_to";
		 //	left join cs_transactiondetails td on cd . userId =td . userId 
		 // group by cd.userId
		
		$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
		
		while($site = mysql_fetch_assoc($result))
			$site_list[] = $site;
			
		$data['company_list'] = $company_list;
		$data['show_option_all'] = !$sql_where;
		$data['site_list'] = $site_list;
		$data['completion'] = $etel_completion_array;
	}

}
$json = new Services_JSON();
$output = $json->encode($data);
print($output);
?>