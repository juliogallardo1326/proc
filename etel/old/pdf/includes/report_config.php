<?
function get_report_config()
{

	$my_sql['tables'] = array("cs_transactiondetails AS td");
	$my_sql['joins'] = array(
			array("table"=>"cs_companydetails AS cd",
					"on"=>
						array(
							array("field_a"=>"td.userId","field_b"=>"cd.userId","compare"=>"=")
						)
					),
			array("table"=>"cs_callnotes AS cn",
					"on"=>
						array(
							array("field_a"=>"td.transactionId","field_b"=>"cn.transaction_id","compare"=>"=")
						)
					),
			array("table"=>"cs_company_sites AS cs",
					"on"=>
						array(
							array("field_a"=>"td.userId","field_b"=>"cs.cs_company_id","compare"=>"=")
						)
					),
			array("table"=>"cs_subscription AS ss",
					"on"=>
						array(
							array("field_a"=>"td.td_ss_ID","field_b"=>"ss.ss_ID","compare"=>"=")
						)
					)
			);
	
	//$my_sql['return']["01|Username"] = array("source" => "td.td_username","column"=>"td_username");
	//$my_sql['return']["04|Password"] = array("source" => "td.td_password","column"=>"td_password");
	//$my_sql['return']["03|Transaction ID"] = array("source" => "td.reference_number","column"=>"reference_number");
	
	$my_sql['return']["02|Transaction Date"] = array("source" => "DATE_FORMAT(td.transactionDate,'%m/%d/%Y %h:%i:%s %p') as transaction_date_formatted","column"=>"transaction_date_formatted");
	$my_sql['return']["00|td_process_msg"] = array("source" => "td.td_process_msg","column"=>"td_process_msg","hidden"=>1);
	$my_sql['return']["00|Amount"] = array("source" => "td.amount","column"=>"amount","hidden"=>1);
	$my_sql['return']["00|Subscription ID"] = array("source" => "ss.ss_rebill_status","column"=>"ss_rebill_status","hidden"=>1);
	$my_sql['return']["00|Transaction ID"] = array("source" => "td.transactionid","column"=>"transactionid","hidden"=>1);
	$my_sql['return']["00|Status Text"] = array("source" => "td.td_process_result","column"=>"td_process_result","hidden"=>1);
	//$my_sql['return']["00|Site ID"] = array("source" => "td.td_site_ID","column"=>"td_site_ID","hidden"=>1);
	
	$my_sql['return']["01|Reference Number"] = array("source" => "td.reference_number","column"=>"reference_number");
	$my_sql['return']["01|Reference Number"]["link"]["destination"] = "viewTransaction.php";
	$my_sql['return']["01|Reference Number"]["link"]["parameters"] = array(
			array("name"=>"ref","value"=>"reference_number","source"=>"result"),
			array("name"=>"test","value"=>"$test_mode"),
			);
	
	$my_sql['return']["04|Name"] = array("source" => "CONCAT(td.surname,', ',td.name) AS full_name","column"=>"full_name");
	$my_sql['return']["05|Description"] = 
	array("source" => "concat(
							if(td.td_username != '',CONCAT('U:',td.td_username,'<BR>P:',td.td_password,'<BR>'),''),
							if(td.from_url != '',CONCAT('URL:<a href=\"',td.from_url,'\">',td.from_url,'</a><BR>'),''),
							if(td.productdescription != '',CONCAT('Desc: ',td.productdescription,'<BR>'),'')
						) as description",
		"column"=>"description",
		"disp_clip"=>array('overflow'=>true)
	
		);
	$my_sql['return']["07|Amount"] = array("source" => "CONCAT('\$',format(td.amount,2),' ',td.cardtype) AS txt_amount","column"=>"txt_amount");
	$my_sql['return']["09|Status"] = array("source" => "CONCAT(	
			if(td.status!='D',
				if(td.status='P','<b>Pending</b>', '<b>Approved</b>'),
				'<b>Declined</b>'
			),
			if(td.td_is_a_rebill=1,' (Rebilled)',' (New)'),
			if(td.cancelstatus='Y', CONCAT('<BR><b> - Refunded - </b>'),''),
			if(td.td_is_chargeback=1, CONCAT('<BR><b> - Charged Back - </b>'),''),
			if(td.status='D', CONCAT('<BR>',td.td_process_msg),''),
			if(ss.ss_subscription_ID is not null,
				CONCAT(
					'<BR><a href=\"viewSubscription.php?subscription_ID=',ss_subscription_ID,'\">Subscription</a> is ',
					if(ss.ss_rebill_status='active',
						CONCAT(
							'Active<BR>Next Rebill Date: ' , 						              																		
							DATE_FORMAT( ss_rebill_next_date, '%m-%d-%y %H:%i:%s' ) 
						),
						'Inactive')
				),
				'<BR>No Subscription'
			)
		) as status","column"=>"status",
		"disp_clip"=>array('overflow'=>true)
		);
		
	
	$my_sql['orderby'] = array("transactionId desc");
	$my_sql['user_orderby']['txt_amount'] = "amount";
	$my_sql['user_orderby']['status'] = "status";
	$my_sql['user_orderby']['full_name'] = "full_name";
	$my_sql['user_orderby']['transaction_date_formatted'] = "transaction_date_formatted";
	
	
	//$my_sql['key']["cs_URL"] = array("display" => "Company Site: ");
	$my_sql['limit'] = array("offset_source" => "page_offset",
							"count_source" => "page_count",
							"max_offset"=>"number_transactions",
							"max_offset_source"=>"result");
	
	
	$my_sql['search']['td.td_site_ID'] = array("input_type" => "selectmulti", "compare"=> "IN","required"=>0,"display"=>"Web Site");
	$my_sql['search']['td.td_site_ID']['style'] = array("size"=>3);
	$my_sql['search']['td.td_site_ID']['options']['source']['script'] = "smart_getSites";
	$my_sql['search']['td.td_site_ID']['options']['source']['parameters']['userid'] = $sessionlogin;
	$my_sql['search']['td.td_site_ID']['style'] = array("size"=>10,
			"style"=>"width: 250px;height: 40px;",
			"onfocus"=>'this.style.height=150;'
	);
	
	
	
	$my_sql['search']['td.transactiondate'] = array("input_type" => "date", "compare"=> "BETWEEN","required"=>1,"display"=>"Date","date_format" => "Y-n-j");
	


	
	$my_sql['search']['td.bankaccountnumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Bank Account Number","swap"=>"etelEnc");
	$my_sql['search']['td.bankroutingcode'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Bank Routing Number","swap"=>"etelEnc");
	
	$my_sql['search']['td.CCnumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Credit Card Number","swap"=>"etelEnc");
	
	$my_sql['search']['td.reference_number'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Reference ID");
	$my_sql['search']['ss_subscription_id'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Subscription ID");
	
	$my_sql['search']['td.name'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"First Name");
	$my_sql['search']['td.surname'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Last Name");
	$my_sql['search']['td.email'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"E-Mail");
	$my_sql['search']['td.phonenumber'] = array("input_type" => "text", "compare"=>"=","required"=>0,"display"=>"Phone");
	
	$my_sql['search']['td.cardtype'] = array("input_type" => "checkbox", "compare"=> "IN","required"=>0,"display" => "Charge Type");
	$my_sql['search']['td.cardtype']['options']['source']['script'] = "smart_getChargeTypes";
	
	$my_sql['search']['td.status'] = array("input_type" => "checkbox", "compare"=> "IN","required"=>0,"display"=>"Status");
	$my_sql['search']['td.status']['options']['source']['pairs'] = "Status";
	
	$my_sql['search']['td.cancelstatus'] = array("input_type" => "checkbox", "compare"=> "=","required"=>0,"display"=>"");
	$my_sql['search']['td.cancelstatus']['options']['source']['pairs'] = "CancelStatus";
	
	$my_sql['search']['td.td_is_chargeback'] = array("input_type" => "checkbox", "compare"=> "=","required"=>0,"display"=>"");
	$my_sql['search']['td.td_is_chargeback']['options']['source']['pairs'] = "ChargeBack";
	
	$my_sql['search']['td.td_is_a_rebill'] = array("input_type" => "checkbox", "compare"=> "in","required"=>0,"display"=>"");
	$my_sql['search']['td.td_is_a_rebill']['options']['source']['pairs'] = "IsRebill";
	
	$my_sql['search']['testmode'] = array("input_type" => "radio", "in_query" => false,"display"=>"Transaction Mode");
	$my_sql['search']['testmode']['options']['source']['pairs'] = "TestModes";
	
	$my_sql['search']['page_count'] = array("input_type" => "select", "in_query" => false,"display"=>"Results Per Page");
	$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";
	
	$my_sql['search']['subquery_group'] = array("input_type" => "select", "in_query" => false,"display"=>"Summary Format");
	$my_sql['search']['subquery_group']['options']['source']['pairs'] = "SubGroupTypes";
	
	$my_sql['search']['subquery_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Summary Detail");
	$my_sql['search']['subquery_detail']['options']['source']['pairs'] = "SubGroupDetails";
	
	$my_sql['search']['page_offset'] = array("input_type" => "hidden", "in_query" => false,"value" => 0,"locked"=>false);
	
	$my_sql['search']['export_detail'] = array("input_type" => "select", "in_query" => false,"value" => 3,"display"=>"Export");
	$my_sql['search']['export_detail']['options']['source']['pairs'] = "ExportDetails";
	
	$my_sql['where']['cs.cs_ID'] = array("value" => "td.td_site_ID", "compare" => "=");
	//and (td.td_non_unique=0 or status!='D')
		
	$my_sql['subquery']['title'] = "Transaction Summary";

	$my_sql['title'] = "Find Transactions";
	
	$my_sql['result_actions']['title'] = "Transactions Found";
	$my_sql['result_actions']['resulttitle'] = "Transactions Processed";
	
	
	$my_sql['result_actions']['actions']['userid'] = array("input_type" =>"hidden", "compare"=> "=","required"=>1,"value" => $sessionlogin);
	$my_sql['result_actions']['actions']['entries'] = array("input_type"=>"select","display"=>"","required" => 1);
	$my_sql['result_actions']['actions']['entries']['options']['source']['pairs'] = "Actions";
	$my_sql['result_actions']['actions']['entries']['style']['style'] = "width:88;";
	$my_sql['result_actions']['actions']['entries']['style']['onchange'] = "check_additional(this);";
	
	$my_sql['result_actions']['actions']['refund_request']['input_type']='textarea';
	$my_sql['result_actions']['actions']['refund_request']['style']['style']='visibility:hidden;width:88;height:1;';
	
	$my_sql['result_actions']['process'] = "smart_processTransactions";
	$my_sql['result_actions']['append'] = array("name"=>"transactionid","source"=>"result");
	
	$my_sql['pairs']['PendingCheck'][] = array("display" => "Pending Check", "value"=>1);
	$my_sql['pairs']['CancelStatus'][] = array("display" => "Is Refunded", "value"=>"Y");
	$my_sql['pairs']['ChargeBack'][] = array("display" => "Is a Chargeback", "value"=>"1");
	$my_sql['pairs']['IsRebill'][] = array("display" => "Is A Rebill", "value"=>"1");
	$my_sql['pairs']['IsRebill'][] = array("display" => "Is Not Rebill", "value"=>"0");
	
	$my_sql['pairs']['Rebilling'] = array(
	//		array("display" => "Either", "value"=>""),
			array("display" => "Enabled", "value"=>"1"),
			array("display" => "Disabled", "value"=>"0")
			);
	
	
	$my_sql['pairs']['ResultsPerPage'] = array(
		array("display" => "50", "value"=>"50"),
		array("display" => "All", "value"=>"1000000"),
		array("display" => "10", "value"=>"10"),
		array("display" => "25", "value"=>"25"),
		array("display" => "100", "value"=>"100")
		);
	
	
	$my_sql['pairs']['SubGroupTypes'] = array(
		array("display" => "All", "value"=>"0"),
		array("display" => "By Day", "value"=>"1"),
		array("display" => "By Week", "value"=>"7"),
		array("display" => "By 2 Weeks", "value"=>"14"),
		array("display" => "By Month", "value"=>"30"),
		array("display" => "By 2 months", "value"=>"60"),
		array("display" => "By 3 months", "value"=>"90"),
		array("display" => "By 6 months", "value"=>"180"),
		array("display" => "By Year", "value"=>"360"),
		array("display" => "By Decline Reason", "value"=>"D"),
		array("display" => "By Website", "value"=>"S")
		);
	
	$my_sql['pairs']['SubGroupDetails'] = array(
		array("display" => "Full", "value"=>"2"),
		array("display" => "Minimal", "value"=>"1")
		//array("display" => "None", "value"=>"0")
		//array("display" => "Extended", "value"=>"4")
		);
	
	$my_sql['pairs']['ExportDetails'] = array(
		array("display" => "Disabled", "value"=>""),
		array("display" => "Full Export", "value"=>"full"),
		array("display" => "Summary", "value"=>"summary"),
		array("display" => "Transaction Data", "value"=>"transaction"),
		array("display" => "Transaction Data (Extended)", "value"=>"transaction2")
		//array("display" => "Extended", "value"=>"4")
		);
	
	$my_sql['pairs']['Status'] = array(
		array("display" => "Approved", "value"=>"A"),
		array("display" => "Declined", "value"=>"D"),
		array("display" => "Pending", "value"=>"P")
		);
		
	
	$my_sql['pairs']['Actions'] = array(
		array("display" => "No Action", "value"=>""),
		array("display" => "Create Refund Request", "value"=>"smart_processRefund","condition_var"=>"is_refunded","condition_val"=>0,"condition_src"=>"result"),
		array("display" => "Cancel Subscription", "value"=>"smart_cancelRebill","condition_var"=>"ss_rebill_status","condition_val"=>'active')
		);
	
	$my_sql['pairs']['TestModes'] = array(
		array("display" => "Live", "value"=>"0"),
		array("display" => "Test", "value"=>"1")
		);
	
	return $my_sql;

}
?>
