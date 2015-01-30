<?
require_once("JSON.php");

function JSON_getEntityList_build_where($search_opts,&$sql_info,$by,$val,$logic = 'OR')
{
	global $etel_entity_search;
	
	$opts = $search_opts['options'][$by];
	$group = $search_opts['joins'][$opts['j']];
	$search = " = '".$val."'";
	if($opts['c']=='like') $search = " LIKE '%".$val."%'";
	else if($opts['c']=='in') $search = " IN ('".preg_replace('/[^a-zA-Z0-9]+/',"','",$val)."')";
	else if($opts['c']) $search = " ".$opts['c']." '".$val."'";
	if($opts && $val!='' && $opts['f']) 
	{
		$sql_info['where'] .= (!$sql_info['where']?'':($logic=='OR'?' OR':' AND'))." ".$opts['f']." $search";
		$sql_info['join'][$opts['j']] = $group['join'];
	}
}

function JSON_get_data($qry)
{
	global $etel_completion_array,$etel_entity_search,$etel_transaction_search;
	global $curUserInfo;
			
	$start_time = microtime_float();
	$data = NULL;
	$data['output'] = NULL;
	$data['func'] = $qry['func'];
	switch($qry['func'])
	{
		case 'getTransactionSearchOptions':
			$search_opts = $etel_transaction_search;
		case 'getEntitySearchOptions':
			if(!$search_opts) $search_opts = $etel_entity_search;
			foreach($search_opts['options'] as $k=>$s)
			{
				$sa[$s['g']]['o'][]=array('k'=>$k,'n'=>$s['n'],'t'=>$s['t'],'c'=>$s['c']);
				$sa[$s['g']]['g']=$search_opts['groups'][$s['g']]['g'];
			}
			$data['search_options'] = array_values($sa);
			$data['search_func'] = $search_opts['search_func'];
			break;
		case 'getTransactionList':
			$search_opts = $etel_transaction_search;
		case 'getEntityList':	
			if($qry['silent']) $data['silent']=$qry['silent'];
			if(!$search_opts) $search_opts = $etel_entity_search;
			$sql_info['join'] = array();
			$search_conditions = array();
			$sql_info['where'] = '';
			
			foreach($qry['en_search_by'] as $key=>$search_by)
			{
				if($search_by=='er') $qry['en_search'][$key] = $_SESSION["gw_user_en_ID"];
				$search_conditions[$key] = array('search'=>quote_smart($qry['en_search'][$key]),'searchby'=>quote_smart($search_by));
				if($search_by=='all')
				{
					foreach($search_opts['options'] as $by=>$opt)
						if($opt['allinfo']) JSON_getEntityList_build_where($search_opts,$sql_info,$by,quote_smart($qry['en_search'][$key]),'OR');
					if($sql_info['where']) $sql_info['where'] = "( ".$sql_info['where']." )";
				}
			}
			
			foreach($search_conditions as $sc)
				JSON_getEntityList_build_where($search_opts,$sql_info,$sc['searchby'],$sc['search'],$qry['logic']);
			
			if($qry['sortby'] && $search_opts['options'][$qry['sortby']]['f'])	
				$sql_info['sort'] = " ORDER BY ".$search_opts['options'][$qry['sortby']]['f']." ".($qry['sortdir']=='ASC'?'ASC ':'DESC ');
				
			if(!$sql_info['sort']) $sql_info['sort'] = " ORDER BY en.en_ID DESC ";
			
			if($sql_info['where']) $sql_info['where'] = " AND ( ".$sql_info['where']. " )";
			if(!$curUserInfo['en_access'][ACCESS_AUTH_ENTITY_ADMIN]) $sql_info['where'] .= " AND en.en_type != 'admin' ";
			$data['limit'] = intval($qry['limit']);
			if($data['limit']<10) $data['limit'] = 50;
			if($data['limit']>300) $data['limit'] = 300;
			$data['limitfrom'] = intval($qry['limitfrom']);
			if($data['limitfrom']<1) $data['limitfrom'] = 0;
			$data['entity_list'] = array();
			if($sql_info['join']) $sql_info['group'] = " Group by en.en_ID ";
			$info['TimeOut']=5;
			$sql = "select SQL_CALC_FOUND_ROWS en.en_ID as id,en.en_company as cn,en.en_email as em,en.en_username as un, en.en_password as pw, en.en_type as ty from cs_entities as en ".implode(" ",$sql_info['join'])." WHERE 1 ".$sql_info['where'].$sql_info['group'].$sql_info['sort']." limit ".$data['limitfrom'].','.$data['limit'];
			//etelPrint($sql);
			if(in_array('wp',$qry['en_search_by']))	$data['cmd'][] = array('id'=>'all','type'=>'ws','st'=>'pending');
			if(in_array('dp',$qry['en_search_by']))	$data['cmd'][] = array('id'=>'all','type'=>'ud','st'=>'pending');
			if(in_array('pp',$qry['en_search_by']))	$data['cmd'][] = array('id'=>'all','type'=>'pa','st'=>'pending');
			if(in_array('ip',$qry['en_search_by']))	$data['cmd'][] = array('id'=>'all','type'=>'is','st'=>'Unanswered');
			//$data['sql'] = $sql;
			$result = sql_query_read($sql,$info) or dieLog(mysql_error()." ~ $sql");
			while($entity = mysql_fetch_assoc($result))
			{
				$entity['il'] = 'editCompanyProfileAccess.php?entity_id='.$entity['id'];
				$entity['li'] = 'EntityManager.php?loginas=1&type='.$entity['ty'].'&username='.$entity['un'].'&hash='.$entity['pw'].'&entity_id='.$entity['id'];
				if(strlen($entity['cn'])>35) $entity['cn'] = substr($entity['cn'],0,34)."...";
				$data['entity_list'][] = $entity;
			}
			$sql = "select FOUND_ROWS()";
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			$data['num_rows'] = mysql_result($result,0,0);
			
			$data['search_func'] = $search_opts['info_func'];
			$data['display_fields'] = array(
			array('k'=>'id','chk'=>1),array('k'=>'id','n'=>'ID'),array('k'=>'cn','n'=>'Company Name','ar'=>array(array('k'=>'lg','n'=>'(Login as)','btn'=>array('li')),array('k'=>'cn','dl'=>array('il')))),
			array('k'=>'op','n'=>'Options','opar'=>array('info|(Information)',($curUserInfo['en_access'][ACCESS_AUTH_ENTITY_ADMIN]?'ac|(Access Rights)':NULL),'pr|(Profit)','pa|(Payouts)','is|(Issues)','ws|(Websites)','ud|(Documents)','pp|(PricePoints)','all|(Open All)')));
			//$data['display_options'] = array();//,'em|Email'
			break;
		case 'setEntityInfo':
			$id = $qry['id'];
			$res = array('msg'=>'Failed to Update','status'=>false);
			switch($qry['f'])
			{
				case 'update_general':	
					
					$update = array('General_Notes'=>html_entity_decode(stripslashes($qry['nst'])));
					unset($qry['nst']);
					$update_result = etel_update_serialized_field('cs_entities','en_info'," en_ID = '".$id."'",$update);
					if(!$update_result)
						$res = array('msg'=>'Invalid Entry','status'=>false);
					else if($update_result['updated'])
						$res = array('msg'=>'Merchant Updated Successfully','status'=>true);
					else 
						$res = array('msg'=>'No Updates Detected','status'=>true);
					$res['update'] = array('id'=>$id,'type'=>'info'); 
					$data['result'][] = $res;					
					break;
				case 'update_site':	
					$conf_array = array('a'=>'approved','d'=>'declined','i'=>'ignored','n'=>'non-compliant');
					if($conf_array[$qry['nvr']]) $res = en_confirm_website($qry['wi'],$conf_array[$qry['nvr']],$qry['nc']);
					else $res = array('msg'=>'Invalid Entry','status'=>false);
					if($res['status']) $res['update'] = array('id'=>$id,'type'=>'ws','hl'=>$qry['wi'],'st'=>$qry['st']);
					$data['result'][] = $res;
					break;
				case 'update_doc':	
					$conf_array = array('a'=>'Approved','d'=>'Declined');
					if($conf_array[$qry['nst']]) $res = en_confirm_documents($qry['fi'],$conf_array[$qry['nst']],$qry['nc']);
					else $res = array('msg'=>'Invalid Entry','status'=>false);
					$res['update'] = array('id'=>$id,'type'=>'ud','hl'=>$qry['fi'],'st'=>$qry['st']);
					$data['result'][] = $res;
					break;
				case 'update_invoice':	
					$res = en_update_invoice($qry['ii'],$qry['nst'],$qry['in'],$qry['em']);
					if($res['status']) $res['update'] = array('id'=>$id,'type'=>'pa','hl'=>$qry['ii'],'st'=>$qry['st']);
					$data['result'][] = $res;
					break;
				case 'update_access':	
					$res = en_update_access($id,array($qry['al']=>$qry['nst']));
					$data['result'][] = $res;
					break;
				case 'add_adjustment':	
					$res = en_adjust_profit($id,$qry['ia'],$qry['ie'],$qry['in'],$qry['ic']);
					if($res['status']) $res['update'] = array('id'=>$id,'type'=>'pr');
					$data['result'][] = $res;
					break;
				case 'commit_payout':	
					$RF = new rates_fees();
					if($curUserInfo['en_access'][ACCESS_AUTH_PAYMENTS])
						$res = $RF->commit_payout($id,array('date_entered'=>$qry['pd'],'amount'=>$qry['pa']));
					if($res['status']) $res['update'] = array('id'=>$id,'type'=>'pa','hl'=>$res['pa_ID']);
					$data['result'][] = $res;
					break;
			}
			break;
		case 'getEntityInfo':
			$qry['id'] = explode(',',$qry['id']);
			$data['num_rows']=0;
			if($qry['silent']) $data['silent']=$qry['silent'];
			$highlight_format = array('tr|class|rowhighlight');
			foreach($qry['id'] as $i=>$en_ID)
			{
				$en_ID = intval($en_ID);
				if(!is_array($qry['type'])) $request = $qry['type'];
				else $request = $qry['type'][$i];
				switch($request)
				{
					case 'all':
					case 'info':
						$entity = array('id'=>$en_ID,'msg'=>'No General Info Available','type'=>'info','info' => array(),'use_tab'=>true);
						$entityInfo = en_get_general_info($en_ID);
						//$entity['stats'] = array('n'=>"\n".$etel_completion_array[$entityInfo['cd_completion']]['txt']);
						$entity['display_fields'] = array(
							array('ar'=>
								array(
									array('k'=>'st','edit'=>'textarea','tstamp'=>true),
									array('k'=>'upd','edit'=>'button','f'=>array('s|f|update_general|','fld|nst|st'),'n'=>'(Update)')
								),
							'n'=>'General Info'
							)
						);//,'em|Email'
						$entity['info'][] = array('sid'=>$en_ID,'st'=>$entityInfo['en_info']['General_Notes']);
						$data['entity_info'][] = $entity;
						if($request!='all') break;
					case 'is':
						$entity = array('id'=>$en_ID,'msg'=>'No Issues Available','type'=>'is','info' => array(),'use_tab'=>true);
						$issues = en_get_issues($en_ID,array('AnsweredStatus'=>$qry['st']));
						$entity['display_fields'] = array(
							array('k'=>'sid','n'=>'ID','dl'=>array('il')),
							//array('ar'=> array(
							//	array('k'=>'ti','pre'=>true)
							//),'n'=>'Description','attrib'=>array('obj|style|overflow: scroll; height: 100px; width: 100%;')),
							array('k'=>'ti','n'=>'Summary','dl'=>array('il')),
							array('k'=>'is','n'=>'Respond','pl'=>array('el')),
							array('k'=>'st','n'=>'Status'),
							array('k'=>'cs','n'=>'Answered')
						);
						foreach($issues as $id=>$issue)
							$entity['info'][] = array('sid'=>$id,'ti'=>$issue['iss_created_date']." - ".$issue['iss_summary'],'st'=>$issue['sta_title'],'cs'=>$issue['iss_control_status'],'il'=>"/ev/view.php?id=".$issue['iss_id'],'el'=>"/ev/send.php?issue_id=".$issue['iss_id']."&ema_id=".$issue['ema_id']."&id=".$issue['sup_id']);
						$data['entity_info'][] = $entity;
						if($request!='all') break;
					case 'ac':
						global $etel_access;
						$entityInfo = en_get_general_info($en_ID);
						$entity = array('id'=>$en_ID,'msg'=>'No Access Info Available','type'=>'ac','info' => array(),'use_tab'=>true);
						$entity['display_fields'] = array(array('k'=>'na','n'=>'Access Level'),
							array('ar'=>
								array(
									array('k'=>'st','edit'=>'checkbox','f'=>array('s|f|update_access|','k|al|sid','fld|nst|st'))
								),
							'n'=>'Status' 
							)
						);
						foreach($etel_access as $key=>$acinfo)
							$entity['info'][] = array('sid'=>$acinfo['Value'],'na'=>$acinfo['Value'].": ".etel_format_variable($key).($acinfo['Name']?" (".$acinfo['Name'].")":''),'st'=>$entityInfo['en_access'][$acinfo['Value']]);
						if($curUserInfo['en_access'][ACCESS_AUTH_ENTITY_ADMIN]) $data['entity_info'][] = $entity;
						if($request!='all') break;
					case 'pr':
						$entity = array('id'=>$en_ID,'msg'=>'No Profit Data Available','type'=>'pr','info' => array(),'use_tab'=>true);
						$RF = new rates_fees();
						$entityInfo = en_get_general_info($en_ID);
						$datestamp = $_REQUEST['date']?strtotime($_REQUEST['date']):0;
						$date = $datestamp>1?date('Y-m-d',$datestamp):$entityInfo['PaySchedule']['NextPayDay'];
						$profit_info = $RF->get_profit(array('EffectiveOnly'=>$date),$en_ID);
						$entity['display_fields'] = array(array('k'=>'tp','n'=>'Profit Type'),array('k'=>'am','n'=>'Amount'));//,'em|Email'
						$entity['stats']['opts'] = array('n'=>"Actions",'ar'=>
							array(
								array('txt'=>"Make an Adjustment",'attrib'=>array('obj|style|font-weight:bold;text-align:center;')),
								array('node'=>"hr"),
								array('txt'=>"Amount:  "),
								array('k'=>'ia','edit'=>'textfield','attrib'=>array('obj|style|width:50px;')),
								array('txt'=>"\nEffective: "),
								array('k'=>'ie','edit'=>'textfield','attrib'=>array('obj|style|width:70px;')),
								array('txt'=>"\nTitle:\n"),
								array('k'=>'in','edit'=>'textfield','attrib'=>array('obj|style|width:180px;')),
								array('txt'=>"\nComments:\n"),
								array('k'=>'ic','edit'=>'textarea','attrib'=>array('obj|style|height:45px;width:180px;')),
								array('txt'=>"\n"),
								array('k'=>'adj','edit'=>'button','f'=>array('s|f|add_adjustment','fld|ia|ia','fld|ie|ie','fld|in|in','fld|ic|ic'),'n'=>'(Create)'),
								array('node'=>"hr"),
							),'attrib'=>array('tdcol|align|left')
						);
						$entity['stats']['data'] = array('sid'=>$en_ID,'am'=>$profit_info['Total']['Amount'],'ic'=>'','ia'=>'0.00','in'=>date('Y-m-d').' Adjustment','ie'=>date('Y-m-d'));
						
						if(!$curUserInfo['en_access'][ACCESS_AUTH_ADJUSTMENTS]) unset($entity['stats']);
						$total_section_format = array('tr|style|font-weight:bold;font-style:italic;','td|class|row0');
						$total_format = array('tr|style|font-weight:bold;font-size:11px;font-style:italic;','td|class|row0');
						//,"td|onclick|"
						if(sizeof($profit_info['Revenue'])) 
							foreach ($profit_info['Revenue'] as $type=>$val)
								$entity['info'][] = array('sid'=>'d_'.$type,'tp'=>"(Revenue) $type",'am'=>'$'.formatMoney($val['Amount'])." (".$val['Count'].")",'attrib'=>($type=='Total'?$total_section_format:NULL));
						if(sizeof($profit_info['Deductions'])) 
							foreach ($profit_info['Deductions'] as $type=>$val)
								$entity['info'][] = array('sid'=>'d_'.$type,'tp'=>"(Deductions) $type",'am'=>'$'.formatMoney($val['Amount'])." (".$val['Count'].")",'attrib'=>($type=='Total'?$total_section_format:NULL));
						$entity['info'][] = array('sid'=>$en_ID,'tp'=>"Total Owed (".$date.")",'am'=>'$'.formatMoney($profit_info['Total']['Amount'])." (".$profit_info['Total']['Count'].")",'attrib'=>$total_format,'opts'=>array('tp'=>array('k'=>'tp','dl'=>array('chg','Click to Change Date'))),'chg'=>"javascript:en_get_info({'id':'$en_ID','type':'pr','date':prompt('Enter New Date','$date')})");
						$data['num_rows']++;
						$data['entity_info'][] = $entity;
						if($request!='all') break;
					case 'pa':
						$entity = array('id'=>$en_ID,'msg'=>'No Payout Data Available','type'=>'pa','info' => array(),'use_tab'=>true);
						
						$RF = new rates_fees();
						$payouts = $RF->get_payouts(array('where'=>($qry['st']?array('pa_status'=>$qry['st']):NULL)),$en_ID);
						$entityInfo = en_get_general_info($en_ID);
						if(($payouts['status']!==false))
							foreach ($payouts as $key=>$payout)
								$entity['info'][] = array('sid'=>$payout['pa_ID'],'am'=>'$'.formatMoney($payout['balance']),'in'=>$payout['pa_info']['Notes'],'ti'=>($payout['pa_ID']==$qry['hl']?'Recently Updated - ':'').$payout['pa_desc'],'nst'=>$payout['pa_status'],'attrib'=>($payout['pa_ID']==$qry['hl']?$highlight_format:NULL));
						
						$entity['display_fields'] = array(
							array('ar'=>array(
								array('k'=>'ti','attrib'=>array('obj|style|font-weight:bold')),
								array('node'=>"br"),
								array('k'=>'nst','edit'=>'select','selopts'=>array('payout_pending|Payment Pending','payout_sent|Payment Sent','success|Successful Payment','payout_failed|Payment Failed'),'n'=>'Status: ')
								),'n'=>'Invoice Info'),
							array('ar'=>array(
								array('k'=>'in','edit'=>'textarea','attrib'=>array('obj|style|height:45px;width:250px;'))
							),'n'=>'Comments'),
							array('ar'=>array(
								array('k'=>'upd','edit'=>'button','f'=>array('s|f|update_invoice','k|ii|sid','fld|nst|nst','fld|in|in','fld|em|em','s|st|'.$qry['st']),'n'=>'(Update)'),
								array('k'=>'em','edit'=>'checkbox','n'=>' Email?')
							),'n'=>'Update','attrib'=>array('obj|style|width:50px;'))
						);//,'em|Email'
											
						$entity['stats']['opts'] = array('n'=>"Actions",'ar'=>
							array(
								array('txt'=>"Payout",'attrib'=>array('obj|style|font-weight:bold;text-align:center;','tdcol|style|width:40px;')),
								array('node'=>"hr"),
								array('txt'=>"Amount:\n"),
								array('k'=>'pa','edit'=>'textfield','attrib'=>array('obj|style|width:60px;')),
								array('txt'=>"\nPayday:\n"),
								array('k'=>'pd','edit'=>'textfield','attrib'=>array('obj|style|width:60px;')),
								array('txt'=>"\n"),
								array('k'=>'pay','edit'=>'button','f'=>array('s|f|commit_payout','fld|pa|pa','fld|pd|pd'),'n'=>'(Pay)'),
							),'attrib'=>array('tdcol|align|left')
						);
						$entity['stats']['data'] = array('sid'=>$en_ID,'pa'=>'0.0','pd'=>$entityInfo['PaySchedule']['NextPayDay']);
						if(!$curUserInfo['en_access'][ACCESS_AUTH_PAYMENTS]) unset($entity['stats']);
						
						
						$data['entity_info'][] = $entity;
						if($request!='all') break;
					case 'ws':
						$entity = array('id'=>$en_ID,'msg'=>'No Website Data Available','type'=>'ws','info' => array(),'use_tab'=>true);
						$site_list = en_get_websites($en_ID,false,$qry['st']);
						$entity['display_fields'] = array(
						array('k'=>'na','n'=>'Website'),
						array('ar'=>array(
							array('k'=>'edt','edit'=>'button','f'=>array('s|f|update_site','k|wi|sid','p|nvr|Please Enter New Status (a=Approved, d=Declined, i=Ignored, n=Non-Compliant)','p|nc|Please Enter Comments (Required for Decline)','s|st|'.$qry['st']),'n'=>'(Change)'),
							array('k'=>'vr')
							),'n'=>'Status'),
						array('ar'=>array(
							array('k'=>'cu','n'=>'(Website)','pl'=>array('cu')),
							array('k'=>'op','n'=>'(Order Page)','pl'=>array('op')),
							array('k'=>'rp','n'=>'(Return Page)','pl'=>array('rp')),
							array('k'=>'mem','n'=>'(Members Section)','pl'=>array('mu','mun','mpw')),
							array('k'=>'ftp','n'=>'(Ftp)','pl'=>array('ft','fun','fpw')),
							array('k'=>'2257','n'=>'(2257 Page)','pl'=>array('2257'))
							),'n'=>'Links','attrib'=>array('td|width|144px'))
						);
						foreach ($site_list as $key=>$site)
							$entity['info'][] = array('sid'=>$site['cs_ID'],'rf'=>$site['cs_reference_ID'],'na'=>$site['cs_name'],'cu'=>$site['cs_URL'],'op'=>$site['cs_order_page'],'rp'=>$site['cs_return_page'],'mu'=>$site['cs_member_url'],'mun'=>$site['cs_member_username'],'mpw'=>$site['cs_member_password'],'ft'=>$site['cs_ftp'],'fun'=>$site['cs_ftp_user'],'fpw'=>$site['cs_ftp_pass'],'vr'=>ucfirst($site['cs_verified']).' ('.$site['cs_reason'].')','2257'=>$site['cs_2257_page']);
						
						$data['num_rows']++;
						$data['entity_info'][] = $entity;
						if($request!='all') break;
					case 'ud':
						$entity = array('id'=>$en_ID,'msg'=>'No Document Data Available','type'=>'ud','info' => array(),'use_tab'=>true);
						$doc_list = en_get_documents($en_ID,$qry['st']);
						$entity['display_fields'] = array(
						array('k'=>'ft','n'=>'File Type'),
						array('ar'=>array(
							array('k'=>'fl','n'=>'File Link','pl'=>array('fl')),
							array('k'=>'up','n'=>'Upload New File','pl'=>array('up')),
							array('k'=>'fn','n'=>'File Name')
						),'n'=>'File Name'),
						array('ar'=>array(
							array('k'=>'edt','edit'=>'button','f'=>array('s|f|update_doc','k|fi|sid','p|nst|Please Enter New Status (a=Approved, d=Declined)','p|nc|Please Enter Comments (Required for Decline)','s|st|'.$qry['st']),'n'=>'(Change)'),
							array('k'=>'st'),
							array('k'=>'rr')
							),'n'=>'Status')
						);
						foreach ($doc_list as $key=>$doc)
							$entity['info'][] = array('sid'=>$doc['file_id'],'ft'=>$doc['file_type'],'fn'=>$doc['file_name'],'du'=>$doc['date_uploaded'],
							'rr'=>'('.$doc['reject_reason'].')','st'=>$doc['status'],
							'fl' => "../gateway/".$_SESSION['gw_folder']."UserDocuments/".$doc['file_type']."/".$doc['file_name'],
							'up' => "uploadDocuments.php?company=".$doc['user_id']
							);
						$data['num_rows']++;
						$data['entity_info'][] = $entity;
						if($request!='all') break;
					case 'pp':
						$entity = array('id'=>$en_ID,'msg'=>'No Price Point Data','type'=>'pp','info' => array(),'use_tab'=>true);		
						$pp_list = en_get_pricepoints($en_ID);
						foreach ($pp_list as $key=>$pp)
							$entity['info'][] = array('sid'=>$pp['rd_subaccount'],'sn'=>$pp['rd_subName'],'de'=>$pp['rd_description'],'sc'=>$pp['schedule'],'st'=>$pp['active']."/".$pp['total']);		
						$entity['display_fields'] = array(
						array('k'=>'sn','n'=>'Name'),
						array('k'=>'de','n'=>'Description'),
						array('k'=>'sc','n'=>'Price Point Schedule'),
						array('k'=>'st','n'=>'Active/Total')
						);
						$data['entity_info'][] = $entity;
						if($request!='all') break;
				
				}
			}
			$data['entity_ids']=$qry['id'];
			break;
		case 'getCompanyInfo': 
					
			$sql_info = JSON_getCompanyInfo_build($qry);
			$limit_to = $sql_info['limit_to'];
		
			$sql = $sql_info['sql_full']. " order by companyname asc limit $limit_to";
			
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			$i=0;
			$website_search_ids = "-1";
			$company_list = array();
			while($company = mysql_fetch_assoc($result))
			{
				$website_search_ids.=",".$company['ui'];
				$company_list[] = $company;
			}
			
			$sql = "select FOUND_ROWS()";
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			$data['num_rows'] = mysql_result($result,0,0);
			
			$sql = "select cs_ID as ci,cs_name as cn,cs_company_ID as cui FROM `cs_company_sites` as cs 
			 where cs_company_ID in ($website_search_ids) order by cs_name";
			 //	left join cs_transactiondetails td on cd . userId =td . userId 
			 // group by cd.userId
			
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			
			$site_list = array();
			while($site = mysql_fetch_assoc($result))
				$site_list[] = $site;
				
			$data['company_list'] = $company_list;
			$data['show_option_all'] = $sql_info['show_option_all'];
			$data['site_list'] = $site_list;
			$data['completion'] = $etel_completion_array;
		break;
		case 'getEVTransactionResults': 
			  
			$search_array = array(
				'em'=>'email',
				'cc'=>'CCNumber',
				'rn'=>'reference_number',
				'ss'=>'td_ss_ID',
			);
			
			$limit_to = 50;
			
			if($qry['search'] && $qry['searchby']=='cc')
				$qry['search'] = etelEnc($qry['search']);
				
			if($qry['search'] && $qry['searchby'] && $search_array[$qry['searchby']])
				$sql_where .=" and ".$search_array[$qry['searchby']]." = '".$qry['search']."'";
		
			$sql = "
				select 
					Date_Format(transactionDate,'%W %b %D %Y %H:%i:%s') as 'Date',
					reference_number as 'Reference ID',
					concat(name,' ',surname) as 'Full Name' ,
					email as 'Email Address',
					CONCAT(	
						if(status!='D',
							if(status='P','Pending', 'Approved'),
							'Declined'
						)		
					) as 'Status',
					if(td_is_a_rebill=1,' Rebilled Transaction',' New Order') as 'Type'
				from cs_transactiondetails as td
				where 
					1 $sql_where 
				order by transactionDate desc
				limit $limit_to
				";
				
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			
			$data['output'] = "<table >\n";
			$i=0;
			while($trans = mysql_fetch_assoc($result))
			{
				if($i==0)
				{
					$data['output'].= "  <tr class='default'>\n";
					foreach($trans as $field=>$value)
						$data['output'].= "    <td><b>$field</b></td>\n";
					$data['output'].= "  </tr>\n";			
				}
				$data['output'].= "  <tr class='default' onclick='td_updateWith(\"".$trans['Reference ID']."\")' onmouseout='td_highlightRow(this,0)' onmousemove='td_highlightRow(this,1)'>\n";
				foreach($trans as $field=>$value)
					$data['output'].= "    <td>$value</td>\n";
				$data['output'].= "  </tr>\n";
				$i++;
			}
			$data['output'] .= "  <tr class='default'>\n    <td>($i) Results</td>\n  </tr>\n</table>";
		break;
		default:
			$data['func'] = NULL;
		break;
	}
	
	foreach($qry as $key => $value)
		$data['json_query'] .= ($data['json_query']?"&":"").$key.'='.$value;
	
	//print_r($data);
	if(!$data['output']) unset($data['output']);
	$data['duration'] = round(microtime_float()-$start_time,4);
	return $data;
}
function JSON_getCompanyInfo_build($qry)
{
		$search_array = array(

			'cn'=>'companyname','mn'=>'cd_notes','ri'=>'ReferenceNumber','un'=>'username','em'=>'email',
			'bn'=>'beneficiary_name','an'=>'bank_account_number','st'=>'if(activeuser,"Yes","No")','ps'=>'if(cd_pay_status="payable","Yes","No")',
			'wn'=>'cs_name','wr'=>'cs_reference_ID','ws'=>'cs_verified');
		$limit_array = array('tt'=>'transaction_type','cp'=>'cd_completion','bi'=>'bk.bank_id','bp'=>'td.bank_id','gi'=>'gateway_id');
		
		$ignore = " and cd_ignore=0 ";
		if($qry['ig']) $ignore = "";
		if($qry['limit_to']>=1) $limit_to = intval($qry['limit_to']);
		else $limit_to = 300;
		
		$sql_where = "";
		$sql_group = ""; //  group by cd.userId
		$sql_site_join = "";
		
		if($qry['search'] && $qry['searchby'] && $search_array[$qry['searchby']])
			$sql_where .=" and ".$search_array[$qry['searchby']]." like '%".$qry['search']."%'";
			
		if($qry['search'] && $qry['searchby'] == 'ca')
			$sql_where .=" and (
			  					   cd.userId = '".$qry['search']."' 
			  					or companyname like '%".$qry['search']."%' 
								or ReferenceNumber like '%".$qry['search']."%' 
								or username like '%".$qry['search']."%' 
								or email like '%".$qry['search']."%' 
								or beneficiary_name like '%".$qry['search']."%' 
								or bank_account_number = '".$qry['search']."' 
								or cs_name like '%".$qry['search']."%' 
								or cs_reference_ID like '%".$qry['search']."%' 
								)
			";

		if($qry['search'] && in_array($qry['searchby'],array('wn','wr','ws','ca'))) $sql_site_join .= "left join `cs_company_sites` cs on cs.`cs_company_id` = cd.`userId` ";
		if($qry['search'] && in_array($qry['searchby'],array('lp'))) $sql_site_join .= "left join cs_transactiondetails as td on td.userId= cd.`userId` ";
		if($qry['bi']) $sql_site_join .= "left join `cs_company_banks` as bk on bk.`userId` = cd.`userId` ";

		if($qry['search'] && $qry['searchby']=='lp')
			$sql_where .= " and transactiondate>subdate(now(),interval ".intval($qry['search'])." day)";
			
		if($qry['jl'])
			$sql_where .= " and date_added>subdate(now(),interval ".intval($qry['jl'])." day)";
			
		if($qry['search'] && $qry['searchby']=='id')
		{
			$batch_list = $qry['search'];
			$batch_array = preg_split('/[^0-9]+/',$batch_list);
			$sql_user_list = "";
			foreach($batch_array as $key=>$val)
				$sql_user_list .= ','.intval($val);//$batch_array[$key] = intval($data);

			$sql_where .=" and cd.userId in (-1$sql_user_list)";
			
		}
	
		foreach($limit_array as $var=>$key)
			if($qry[$var] != '') $sql_where .= " and $key='".quote_smart($qry[$var])."'";

		if($sql_site_join) $sql_group = " group by cd.userId";
		$sql_info['sql_where'] = $sql_where;
		$sql_info['sql_site_join'] = $sql_site_join;
		$sql_info['limit_to'] = $limit_to;
		$sql_info['ignore'] = $ignore;
		
		$sql_info['sql_select'] = "select SQL_CALC_FOUND_ROWS cd.userId as ui,companyname as cn,cd_completion as cp ";
		$sql_info['sql_from'] = " cs_companydetails cd $sql_site_join ";
		$sql_info['sql_where'] = " 1 $ignore $bank_sql_limit $sql_where $sql_group";
		$sql_info['sql_full'] = $sql_info['sql_select']." from ".$sql_info['sql_from']. " Where ".$sql_info['sql_where'];
		$sql_info['show_option_all'] = !$sql_where;
		
		 //		left join cs_transactiondetails td on cd . userId =td . userId 
		 //group by cd.userId
		// die($sql);
		
		return $sql_info;
}


?>