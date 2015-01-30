<?php
$export_data = $_REQUEST['frm_export_detail'];
if($export_data) ob_start();

include ("includes/sessioncheck.php");
$pageConfig['SubHeader']="affiliates";
include("includes/header.php");

/**************
Define functions to process form
**************/

?>
<script language="javascript">

	function check_additional(obj)
	{
		name = obj.name;
		id = name.split('_')[2];
		if(obj.value=='smart_updateMarkup')
		{
			$('res_trans_'+id).style.display = 'block';
			$('res_trans_'+id+'_div').style.display = 'none';
			$('res_disc_'+id).style.display = 'block';
			$('res_disc_'+id+'_div').style.display = 'none';
		}
		else
		{
			$('res_trans_'+id).style.display = 'none';
			$('res_trans_'+id+'_div').style.display = 'block';
			$('res_disc_'+id).style.display = 'none';
			$('res_disc_'+id+'_div').style.display = 'block';	
		}
	}

</script>
<?

if(isset($_POST['default_disc'])||isset($_POST['default_trans']))
{
	$ddm = floatval($_POST['default_disc']);
	$dtm = floatval($_POST['default_trans']);
	$update = array('Reseller'=>array('Default_Disc_Markup'=>round($ddm,2),'Default_Trans_Markup'=>round($dtm,2)));
	
	$result = etel_update_serialized_field('cs_entities','en_info'," en_ID = '".$curUserInfo['en_ID']."'",$update);
	if($result['updated']) $curUserInfo['en_info'] = $result['info'];
	$result = smart_updateMarkup(NULL,NULL,true);
}

function smart_processCompanies($form_res,$action,&$results)
{	
	$status = array();
	if(isset($form_res['entries']))
		foreach($form_res['entries'] as $key => $values)
			if($values['value'] !="")
				$status[] = $values['value']($values,$action);
	return $status;
}

function smart_updateMarkup($values,$action,$updateall=false)
{
	global $curUserInfo;
	$en_ID = $values['append'];
	if($updateall)
	{
		$res_disc = $_POST['default_disc'];
		$res_trans = $_POST['default_trans'];
	}
	else
	{
		$res_disc = $_POST['res_disc_'.$userid];
		$res_trans = $_POST['res_trans_'.$userid];
	}
	$sql = "select cb_ID,cb_config
			FROM 
				cs_entities as ce
			LEFT JOIN 
				cs_entities_affiliates as ea ON (ea.ea_en_ID = ce.en_ID)
			LEFT JOIN 
				cs_company_banks as cb ON (ce.en_ID = cb.cb_en_ID AND cb.bank_id = 0)
			WHERE
				ea.ea_affiliate_ID = '".$curUserInfo['en_ID']."'";
				
	if($en_ID || !$updateall)
		$sql .= " and ce.en_ID = '$en_ID'";
				
	$markupResult = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
	if(!mysql_num_rows($markupResult))
		return array("action"=>"Affiliate(s) Not Found.","status"=>"fail");
	
	while($markupInfo = mysql_fetch_assoc($markupResult))
	{
		if(!$markupInfo['cb_ID'])
		{
			$sql = "insert into cs_company_banks set cb_en_ID = '".intval($markupInfo['merchant_en_ID'])."',bank_id=0;";
			$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
			$cb_ID = mysql_insert_id();
		}
		else
		{
			$cb_ID = $markupInfo['cb_ID'];
			$markupInfo['cb_config'] = etel_unserialize($markupInfo['cb_config']);
		}
		
		$update = array('default'=>array('Reseller'=>array('disct'=>$res_disc,'trans'=>$res_trans,'en_ID'=>$curUserInfo['en_ID'])));
		
		if($updateall && $markupInfo['cb_config']['default']['Reseller'])
		{
			$update = NULL;
		}
		
		if($update)
		{
			$updated = etel_update_serialized_field('cs_company_banks','cb_config'," cb_ID = '$cb_ID'",$update);
			
			if(!$updateall)
			{
				if ($updated)
					return array("action"=>"Merchant Rates Updated Successfully.","status"=>"success");
				return array("action"=>"Error, Please contact support.","status"=>"fail");
			}
		}
	}
	return array("action"=>"Merchant(s) Rates Updated Successfully.","status"=>"success");
}

$Transtype = isset($HTTP_GET_VARS['trans_type'])?quote_smart($HTTP_GET_VARS['trans_type']):"";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"A";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$level = 'minimal';
if($adminInfo['li_level']=='full') $level = 'full';
if($adminInfo['username']=='etel1') {$_GET['showall']=1;$level = 'medium';}


$my_sql['tables'] = array("cs_companydetails as cd");

$my_sql['joins'] = array(
		array("table"=>"cs_entities as ce",
				"on"=>
					array(
						array("field_a"=>"cd.userId","field_b"=>"ce.en_type_ID ","compare"=>"="),
						array("field_a"=>"ce.en_type","field_b"=>"'merchant'","compare"=>"=")
					)
				),
		array("table"=>"cs_entities_affiliates as ea",
				"on"=>
					array(
						array("field_a"=>"ea.ea_en_ID","field_b"=>"ce.en_ID","compare"=>"=")
					)
				),
		array("table"=>"cs_company_banks as cb",
				"on"=>
					array(
						array("field_a"=>"cb.cb_en_ID","field_b"=>"ce.en_ID","compare"=>"="),
						array("field_a"=>"cb.bank_id","field_b"=>"0","compare"=>"=")
					)
				)
		);

$my_sql['subquery']['title'] = "Transaction Summary";
$my_sql['subquery']['queries']['01|Total Companys'] = array("name"=>"total_companys", "source" => "sum(cd.userId is not null)");
$my_sql['subquery']['queries']['02|Signed Contract'] = array("name"=>"signed_companys", "source" => "sum(merchant_contract_agree=1)");
$my_sql['subquery']['queries']['03|Live Companys'] = array("name"=>"active_companys", "source" => "sum(activeuser=1)");

$my_sql['manip']["cb_config"] = array("source" => "cb_config","function"=>"unserialize");
$my_sql['manip']["res_disc"] = array("source" => "cb_config","function"=>"smart_array_path","params"=>array('default','Reseller','disct'));
$my_sql['manip']["res_trans"] = array("source" => "cb_config","function"=>"smart_array_path","params"=>array('default','Reseller','trans'));

$my_sql['return']["00|Company ID"] = array("source" => "cd.userId","column"=>"cd.userId","hidden"=>1);
$my_sql['return']["00|Info"] = array("source" => "cb_config","column"=>"cb_config","hidden"=>1);

$my_sql['return']["01|Ref ID"] = array("source" => "ce.en_ref","column"=>"en_ref");
$my_sql['return']["02|Company Name"] = array("source" => "cd.companyname","column"=>"companyname","crop"=>25);

$my_sql['return']["03|Sites"] = array("source" => "(select count(cs_ID) as sites from cs_company_sites AS cs where cs.cs_en_ID = ce.en_ID group by cs.cs_en_ID) as sites","column"=>"sites");

$my_sql['return']["04|Docs"] = array("source" => "(select count(distinct file_type) as docs from cs_uploaded_documents AS ud where ud.ud_en_ID = ce.en_ID group by ud.ud_en_ID) as docs","column"=>"docs");

$my_sql['return']["05|Signed Up"] = array("source" => "Date_Format(date_added,'%m-%d-%y') as date_added","column"=>"date_added");
$my_sql['return']["06|Source"] = array("source" => "concat(how_about_us,'/',reseller_other) as source","column"=>"source","disp_clip"=>array('w'=>60,'h'=>14,'overflow'=>true));


$my_sql['return']["07|Contract Signed"] = array("source" => "if(merchant_contract_agree,'Yes','No') as signed_contract","column"=>"signed_contract");
$my_sql['return']["08|Discount Markup"] = array("source" => "res_disc","column"=>"res_disc","in_query"=>false,"disp_editable"=>array('src'=>'userId','size'=>2),'disp_append_back'=>'%','disp_decimal'=>true);
$my_sql['return']["09|Transaction Markup"] = array("source" => "res_trans","column"=>"res_trans","in_query"=>false,"disp_editable"=>array('src'=>'userId','size'=>2),'disp_append_front'=>'$','disp_decimal'=>true);


$my_sql['limit'] = array("offset_source" => "page_offset",
						"count_source" => "page_count",
						"max_offset"=>"total_companys",
						"max_offset_source"=>"result");
						
$my_sql['sql_config'] = array('TimeOut'=>10);
if($export_data)
{
	if(in_array($export_data,array('full','company','company2')))
	{
		$my_sql['limit']['forcelimit']=30000;
		
		if(in_array($export_data,array('company2')))
		{
			$my_sql['return']["02|SiteInfo"] = array("source" => "(select group_concat(distinct `cs_name` SEPARATOR ', ') as site_info from cs_company_sites AS cs where cs_company_id = cd.userId group by cs_company_id) as site_info","column"=>"site_info");
			$my_sql['return']["03|Submitted Docs"] = array("source" => "(select group_concat(distinct file_type SEPARATOR ', ') as doc_info from cs_uploaded_documents AS ud where ud.user_Id = cd.userId group by ud.user_Id) as doc_info","column"=>"doc_info");
			$my_sql['return']["10|Username"] = array("source" => "cd.username","column"=>"username");
			$my_sql['return']["10|Password"] = array("source" => "cd.password","column"=>"password");
			$my_sql['return']["10|Phonenumber"] = array("source" => "cd.phonenumber","column"=>"phonenumber");
			$my_sql['return']["10|Address"] = array("source" => "cd.address","column"=>"address");
			$my_sql['return']["10|City"] = array("source" => "cd.city","column"=>"city");
			$my_sql['return']["10|State"] = array("source" => "cd.state","column"=>"state");
			$my_sql['return']["10|Country"] = array("source" => "cd.country","column"=>"country");
			$my_sql['return']["10|Zipcode"] = array("source" => "cd.zipcode","column"=>"zipcode");
			$my_sql['return']["10|General Email"] = array("source" => "cd.email","column"=>"email");
			$my_sql['return']["10|Contact Name"] = array("source" => "cd.contactname","column"=>"contactname");
			$my_sql['return']["10|First Name"] = array("source" => "cd.first_name","column"=>"first_name");
			$my_sql['return']["10|Family Name"] = array("source" => "cd.family_name","column"=>"family_name");
			$my_sql['return']["10|Job Title"] = array("source" => "cd.job_title","column"=>"job_title");
			$my_sql['return']["10|Contact Email"] = array("source" => "cd.contact_email","column"=>"contact_email");
			$my_sql['return']["10|Contact Phone"] = array("source" => "cd.contact_phone","column"=>"contact_phone");
			$my_sql['return']["10|Time Zone"] = array("source" => "cd.cd_timezone","column"=>"cd_timezone");
			$my_sql['return']["10|Instant Messenger"] = array("source" => "cd.cd_contact_im","column"=>"cd_contact_im");
		}
	}
	$my_sql['sql_config'] = array('TimeOut'=>30);
	
}


$detail = intval($_REQUEST['frm_subquery_detail']);
$subquery_group = quote_smart($_REQUEST['frm_subquery_group']);
if($subquery_group) 
{
	$my_sql['subgroupby'] = "subgroup_by";
	$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
	$my_sql['subrollup'] = true;
	$my_sql['subgrouprolluptitle'] = "CONCAT('Total - ',daterange)";
	
	switch($subquery_group)
	{
		case 'M':
			$my_sql['subgroupby'] = "cd.transaction_type";
			$my_sql['subgrouptitle'] = "CONCAT('(',count(*),') ',cd.transaction_type)";
			$my_sql['subgrouprolluptitle'] = "CONCAT('(',total_companys,') Total Companys')";
			$my_sql['suborderby'] = "is_rollup desc";
			$export_subname.="ByMerchantType";
			$detail=1;
			break;
	}
}
$my_sql['subquery']['title'] = "Company Listing";



$my_sql['orderby'] = array("cd.userId desc");
$my_sql['groupby'] = array("cd.userId");
$my_sql['user_orderby']['companyname'] = "companyname";
$my_sql['user_orderby']['source'] = "source";
$my_sql['user_orderby']['date_added'] = "date_added";
$my_sql['user_orderby']['url1'] = "url1";
$my_sql['user_orderby']['sites'] = "sites";
$my_sql['user_orderby']['docs'] = "docs";
$my_sql['user_orderby']['ReferenceNumber'] = "ReferenceNumber";



$my_sql['search']['en_company'] = array("input_type" => "text", "compare"=>"LIKE", "enclose"=>"%", "required"=>0,"display"=>"Company Name");
$my_sql['search']['en_email'] = array("input_type" => "text", "compare"=>"LIKE", "enclose"=>"%","required"=>0,"display"=>"Contact Email");
$my_sql['search']['en_ref'] = array("input_type" => "text", "compare"=>"LIKE", "enclose"=>"%","required"=>0,"display"=>"Reference ID");

$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";

$my_sql['search']['subquery_group'] = array("input_type" => "select", "in_query" => false,"display"=>"Summary Format");
$my_sql['search']['subquery_group']['options']['source']['pairs'] = "SubGroupTypes";

$my_sql['search']['subquery_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Summary Detail");
$my_sql['search']['subquery_detail']['options']['source']['pairs'] = "SubGroupDetails";

$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);

$my_sql['search']['export_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Export");
$my_sql['search']['export_detail']['options']['source']['pairs'] = "ExportDetails";

$my_sql['where']['ea.ea_affiliate_ID'] = array("value" => $curUserInfo['en_ID'], "compare" => "=");



$my_sql['result_actions']['postpage'] = "";
$my_sql['result_actions']['title'] = "Companys Found";
$my_sql['result_actions']['resulttitle'] = "Companys Processed";


$my_sql['result_actions']['actions']['entries'] = array("input_type"=>"select","display"=>"","required" => 1);
$my_sql['result_actions']['actions']['entries']['options']['source']['pairs'] = "Actions";
$my_sql['result_actions']['actions']['entries']['style']['style'] = "width:88;";
$my_sql['result_actions']['actions']['entries']['style']['onchange'] = "check_additional(this);";

$my_sql['result_actions']['process'] = "smart_processCompanies";
$my_sql['result_actions']['append'] = array("name"=>"userId","source"=>"result");





$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50"),
	array("display" => "All", "value"=>"1000000"),
	array("display" => "10", "value"=>"10"),
	array("display" => "25", "value"=>"25"),
	array("display" => "100", "value"=>"100")
	);


$my_sql['pairs']['SubGroupTypes'] = array(
	array("display" => "All", "value"=>"0"),
	array("display" => "By Merchant Type", "value"=>"M")
	);

$my_sql['pairs']['SubGroupDetails'] = array(
	array("display" => "Full", "value"=>"2"),
	array("display" => "Minimal", "value"=>"1"),
	array("display" => "None", "value"=>"0")
	//array("display" => "Extended", "value"=>"4")
	);
	

$my_sql['pairs']['Actions'] = array(
	array("display" => "No Action", "value"=>""),
	array("display" => "Enter Rate Markup", "value"=>"smart_updateMarkup","condition_var"=>"signed_contract","condition_val"=>'No',"condition_src"=>"result")
	);

$my_sql['pairs']['ExportDetails'] = array(
	array("display" => "Disabled", "value"=>""),
	array("display" => "Full Export", "value"=>"full"),
	array("display" => "Summary", "value"=>"summary"),
	array("display" => "Company Data", "value"=>"company"),
	array("display" => "Company Data (Extended)", "value"=>"company2")
	//array("display" => "Extended", "value"=>"4")
	);
	
//and (td.td_non_unique=0 or status!='D')
$my_sql['subquery']['title'] = "Company Info";

$my_sql['postpage'] = "";
$my_sql['title'] = "Find Companys";

$my_sql['result_actions']['postpage'] = "";
$my_sql['result_actions']['title'] = "Companys Found";

	
/****************
Process and Render Forms
****************/


smart_render_action_results(smart_process_action_form($my_sql['result_actions']),$my_sql['result_actions']['resulttitle']);

smart_search_form($my_sql);

if(smart_process_mysql_form($my_sql))
{

	if($export_data)
	{	
		if(in_array($export_data,array('summary'))) $my_sql['skip_query']=true;
		if(in_array($export_data,array('company','company2'))) {$my_sql['skip_subquery']=true;  $export_subname = '';}
	}
	
	$result = smart_search($my_sql);
	
	if($export_data)
	{	
		ob_clean();
		$filename = 'Export'.$export_subname.'.csv';
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		
		flush();
		smart_render_export($result, $my_sql);
		die();
	}
	smart_render_results($result, $my_sql);
	
}
else
{
	$update['en_info']['Reseller']['Default_Merchant_Trans_Markup'] = '.10';
	$update['en_info']['Reseller']['Default_Merchant_Disc_Markup'] = '1';
	beginTable();
	?>
	<table class="report" width="100%">
	<tr><td>Update Default Discount Rate Markup:<br /><span style="font-size:smaller">(ex. '0.1' is 0.10% of approved transaction amount per transaction)</span> </td><td><input name="default_disc" size="5" value="<?=$curUserInfo['en_info']['Reseller']['Default_Disc_Markup']?>" /></td></tr>
	<tr><td>Update Default Transaction Fee Markup:<br /><span style="font-size:smaller">(ex. '0.05' is $0.05 per approved transaction)</span> </td><td><input name="default_trans" size="5" value="<?=$curUserInfo['en_info']['Reseller']['Default_Trans_Markup']?>" /></td></tr>
	</table>
	<?php
	endTable("Update Default Settings","",false,false,true,'update_defaults');
}

include("includes/footer.php");

?>