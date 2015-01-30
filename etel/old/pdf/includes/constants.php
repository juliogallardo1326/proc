<?php

// Security Constants
$etel_access = array();
	// General
	$etel_access['READ_ONLY']['Value'] = 1;
	
	// Admin
	$etel_access['DEBUG_MODE']['Value'] = 			33;
	$etel_access['AUTH_ENTITY_ADMIN']['Value'] = 	34;
	$etel_access['AUTH_ENTITY_ADMIN']['Name'] = 	'View/Manage All Users and Access Rights';
	$etel_access['AUTH_PAYMENTS']['Value'] = 		35;
	$etel_access['AUTH_ADJUSTMENTS']['Value'] = 	36;
	$etel_access['AUTH_RATES']['Value'] = 			37;
	$etel_access['AUTH_RISK_REVIEW']['Value'] = 	38;
	$etel_access['AUTH_REFUNDS']['Value'] = 		39;
	$etel_access['AUTH_TRANS_MOD']['Value'] = 		40;
	$etel_access['AUTH_TRANS_MOD']['Name'] = 		'Transaction Change Status/Void';

foreach($etel_access as $key=>$data)
	define( 'ACCESS_'.$key,$data['Value']);

$etel_transaction_search=array('search_func'=>'getTransactionList');
$etel_transaction_search['groups'] = array(
	'qd'=>array('g'=>'Quick Data'),
	'td'=>array('g'=>'Transaction Data'),
	'ss'=>array('g'=>'Subscription Data'),
	'rd'=>array('g'=>'Rebill Data')
);
$etel_transaction_search['joins'] = array(
	'ss'=>array('join'=>" left join `cs_subscription` as ss on ss.`ss_ID` = td.`td_ss_ID` "),
	'rd'=>array('join'=>" left join `cs_rebillingdetails` as rd on rd.rd_subaccount = td.`td_rebillingID` ")
);
$etel_transaction_search['options'] = array(
	'all'=>array('n'=>'All Transaction Info','g'=>'qd')
);

$etel_entity_search=array('search_func'=>'getEntityList');
$etel_entity_search['groups'] = array(
	'qd'=>array('g'=>'Quick Data'),
	'cd'=>array('g'=>'Company Data'),
	'cs'=>array('g'=>'Website Data'),
	'ud'=>array('g'=>'Document Data'),
	'pa'=>array('g'=>'Payment Data')
);
$etel_entity_search['joins'] = array(
	'cd'=>array('join'=>" left join `cs_companydetails` cd on cd.`userId` = en.`en_type_ID` && en.`en_type` = 'merchant'"),
	'cs'=>array('join'=>" left join `cs_company_sites` cs on cs.`cs_en_ID` = en.`en_ID` && `cs_hide`=0"),
	'ud'=>array('join'=>" left join cs_uploaded_documents as ud on ud.ud_en_ID = en.`en_ID` "),
	'ea'=>array('join'=>" left join cs_entities_affiliates as ea on ea.ea_en_ID = en.`en_ID` "),
	'er'=>array('join'=>" left join cs_entities_affiliates as ea on ea.ea_en_ID = en.`en_ID` and ea_type = 'Reseller' left join cs_entities as ena on ena.en_ID = ea.ea_affiliate_ID "),
	'pa'=>array('join'=>" left join cs_profit_action as pa on pa.pa_en_ID = en.`en_ID` "),
	'ei'=>array('join'=>" left join etel_eventum.`ev_issue` as ei on ei.iss_usr_id = en.`en_ID` left join etel_eventum.ev_status as es on ei.iss_sta_id = es.sta_id ")
);
$etel_entity_search['options'] = array(
	'all'=>array('n'=>'All Entity Info','g'=>'qd'),
	'er'=>array('f'=>'ea.ea_affiliate_ID','n'=>'All My Rep Accounts','t'=>'select|My Accounts','g'=>'qd','j'=>'ea'),
	'ea'=>array('f'=>'ena.en_company','n'=>'Company Reseller','g'=>'qd','j'=>'er','c'=>'like'),
	'wp'=>array('f'=>'cs.cs_verified','n'=>'Pending Websites','t'=>'select|pending','g'=>'qd','j'=>'cs'),
	'dp'=>array('f'=>'ud.status','n'=>'Pending Documents','t'=>'select|Pending','g'=>'qd','j'=>'ud'),
	'pp'=>array('f'=>'pa.pa_status','n'=>'Pending Payments','t'=>'select|payout_pending','g'=>'qd','j'=>'pa'),
	'ip'=>array('f'=>'es.sta_is_closed = 0 && iss_control_status','n'=>'Open Issues','t'=>'select|Unanswered','g'=>'qd','j'=>'ei'),
	
	'cn'=>array('f'=>'en.en_company','n'=>'Company Name','g'=>'cd','c'=>'like','allinfo'=>true),
	'cs'=>array('f'=>'cd.cd_completion','n'=>'Company Status','g'=>'cd','j'=>'cd','c'=>'like'),
	'tt'=>array('f'=>'cd.transaction_type','n'=>'Company Transaction Type','g'=>'cd','j'=>'cd','c'=>'like'),
	'et'=>array('f'=>'en.en_type','n'=>'Company Type','t'=>'select|merchant,reseller,bank,admin,service,processor','g'=>'cd'),
	'ri'=>array('f'=>'en.en_ref','n'=>'Reference ID','g'=>'cd','c'=>'like','allinfo'=>true),
	'md'=>array('f'=>'en.en_type = "merchant" && en.en_type_id','n'=>'Merchant ID (CSV List)','g'=>'cd','c'=>'in'),
	'id'=>array('f'=>'en.en_ID','n'=>'Entity ID (CSV List)','g'=>'cd','c'=>'in','allinfo'=>true),
	'un'=>array('f'=>'en.en_username','n'=>'Login UserName','g'=>'cd','c'=>'like','allinfo'=>true),
	'em'=>array('f'=>'en.en_email','n'=>'Contact Email','g'=>'cd','c'=>'like','allinfo'=>true),
	'es'=>array('f'=>'en.en_signup','n'=>'Signed Up Since (Y-m-d h:m:s)','g'=>'cd','c'=>'>='),
	'in'=>array('f'=>'en.en_info','n'=>'Company Information','g'=>'cd','c'=>'like'),
	
	'ws'=>array('f'=>'cs.cs_verified','n'=>'Website Status','t'=>'select|pending,non-compliant,approved,declined,ignored','g'=>'cs','j'=>'cs'),
	'wn'=>array('f'=>'cs.cs_name','n'=>'Website Name','g'=>'cs','j'=>'cs','c'=>'like','allinfo'=>true),
	'wr'=>array('f'=>'cs.cs_reference_ID','n'=>'Website Reference ID','g'=>'cs','j'=>'cs','c'=>'like','allinfo'=>true),
	'wc'=>array('f'=>'cs.cs_reason','n'=>'Reject Reason/Comments','g'=>'cs','j'=>'cs','c'=>'like','allinfo'=>true),
	
	'ds'=>array('f'=>'ud.status','n'=>'Document Status','t'=>'select|Pending,Approved,Declined','g'=>'ud','j'=>'ud'),
	'dt'=>array('f'=>'ud.file_type','n'=>'Document Type','t'=>'select|Articles,Contract,History,License,Professional_Reference','g'=>'ud','j'=>'ud'),
	'dn'=>array('f'=>'ud.file_name','n'=>'Document Name','g'=>'ud','j'=>'ud','c'=>'like'),
	'dc'=>array('f'=>'ud.reject_reason','n'=>'Reject Reason/Comments','g'=>'ud','j'=>'ud','c'=>'like')
);
	
$etel_completion_array=array();
$etel_completion_array[-1]['txt']="Old Company [No Status]";
$etel_completion_array[0]['txt']="Just Joined";
$etel_completion_array[1]['txt']="Filling out Merchant Application";
$etel_completion_array[2]['txt']="Needs to Request Rates and Fees";
$etel_completion_array[3]['txt']="Requested Rates and Fees";
$etel_completion_array[3]['style']="font-weight:bold;";
$etel_completion_array[4]['txt']="Merchant Contract Now Available";
$etel_completion_array[5]['txt']="Has Signed Merchant Contract";
$etel_completion_array[6]['txt']="Is Integrating Website";
$etel_completion_array[7]['txt']="Completed Integration";
$etel_completion_array[9]['txt']="Has Requested to go Live";
$etel_completion_array[9]['style']="font-weight:bold;";
$etel_completion_array[10]['txt']="Is Live";
$etel_completion_array[10]['style']="font-weight:bold;";

$etel_entity_search['options']['cs']['t']='select|';
foreach($etel_completion_array as $key=>$data)
	$etel_entity_search['options']['cs']['t'].=$key.":".$data['txt'].",";

// Times Zones
$etel_timezone=array();
$etel_timezone['-7:00'] = "Select Time Zone Below";
$etel_timezone['-5:00'] = "U.S. Eastern";
$etel_timezone['-6:00'] = "U.S. Central";
$etel_timezone['-7:00'] = "U.S. Mountain";
$etel_timezone['-8:00'] = "U.S. Pacific";
$etel_timezone['-9:00'] = "U.S. Alaska";
$etel_timezone['-10:00'] = "U.S. Hawaii";
$etel_timezone[''] = "----------------";
$etel_timezone['+0:00'] = "GMT +00:00 Britain, Ireland, Portugal, Western Africa ";
$etel_timezone['+0:30'] = "GMT +00:30 ";
$etel_timezone['+1:00'] = "GMT +01:00 Western Europe, Central Africa";
$etel_timezone['+1:30'] = "GMT +01:30 ";
$etel_timezone['+2:00'] = "GMT +02:00 Eastern Europe, Eastern Africa";
$etel_timezone['+2:30'] = "GMT +02:30 ";
$etel_timezone['+3:00'] = "GMT +03:00 Russia, Saudi Arabia";
$etel_timezone['+3:30'] = "GMT +03:30 ";
$etel_timezone['+4:00'] = "GMT +04:00 Arabian";
$etel_timezone['+4:30'] = "GMT +04:30 ";
$etel_timezone['+5:00'] = "GMT +05:00 West Asia, Pakistan";
$etel_timezone['+5:30'] = "GMT +05:30 India";
$etel_timezone['+6:00'] = "GMT +06:00 Central Asia";
$etel_timezone['+6:30'] = "GMT +06:30 ";
$etel_timezone['+7:00'] = "GMT +07:00 Bangkok, Hanoi, Jakarta";
$etel_timezone['+7:30'] = "GMT +07:30 ";
$etel_timezone['+8:00'] = "GMT +08:00 China, Singapore, Taiwan";
$etel_timezone['+8:30'] = "GMT +08:30 ";
$etel_timezone['+9:00'] = "GMT +09:00 Korea, Japan";
$etel_timezone['+9:30'] = "GMT +09:30 Central Australia";
$etel_timezone['+10:00'] = "GMT +10:00 Eastern Australia";
$etel_timezone['+10:30'] = "GMT +10:30 ";
$etel_timezone['+11:00'] = "GMT +11:00 Central Pacific";
$etel_timezone['+11:30'] = "GMT +11:30 ";
$etel_timezone['+12:00'] = "GMT +12:00 Fiji, New Zealand";
$etel_timezone['-12:00'] = "GMT -12:00 Dateline ";
$etel_timezone['-11:30'] = "GMT -11:30 ";
$etel_timezone['-11:00'] = "GMT -11:00 Samoa";
$etel_timezone['-10:30'] = "GMT -10:30 ";
$etel_timezone['-10:00'] = "GMT -10:00 Hawaiian";
$etel_timezone['-9:30'] = "GMT -09:30 ";
$etel_timezone['-9:00'] = "GMT -09:00 Alaska/Pitcairn Islands";
$etel_timezone['-8:30'] = "GMT -08:30 ";
$etel_timezone['-8:00'] = "GMT -08:00 US/Canada/Pacific";
$etel_timezone['-7:30'] = "GMT -07:30 ";
$etel_timezone['-7:00'] = "GMT -07:00 US/Canada/Mountain";
$etel_timezone['-6:30'] = "GMT -06:30 ";
$etel_timezone['-6:00'] = "GMT -06:00 US/Canada/Central";
$etel_timezone['-5:30'] = "GMT -05:30 ";
$etel_timezone['-5:00'] = "GMT -05:00 US/Canada/Eastern, Colombia, Peru";
$etel_timezone['-4:30'] = "GMT -04:30 ";
$etel_timezone['-4:00'] = "GMT -04:00 Bolivia, Western Brazil, Chile, Atlantic";
$etel_timezone['-3:30'] = "GMT -03:30 Newfoundland";
$etel_timezone['-3:00'] = "GMT -03:00 Argentina, Eastern Brazil, Greenland";
$etel_timezone['-2:30'] = "GMT -02:30 ";
$etel_timezone['-2:00'] = "GMT -02:00 Mid-Atlantic";
$etel_timezone['-1:30'] = "GMT -01:30 ";
$etel_timezone['-1:00'] = "GMT -01:00 Azores/Eastern Atlantic";
$etel_timezone['-0:30'] = "GMT -00:30 ";


// How did you hear about us?
$etel_hear_about_us=array();
$etel_hear_about_us['other'] = "Others";
$etel_hear_about_us['about.com'] = "About.com";
$etel_hear_about_us['altavista.com'] = "AltaVista";
$etel_hear_about_us['alltheweb.com'] = "AllTheWeb.com";
$etel_hear_about_us['aolsearch.aol.com'] = "AOL Search";
$etel_hear_about_us['askjeeves.com'] = "Ask Jeeves";
$etel_hear_about_us['britannica.com'] = "Britannica.com";
$etel_hear_about_us['excite.com'] = "Excite";
$etel_hear_about_us['google.com'] = "Google";
$etel_hear_about_us['hotbot.com'] = "HotBot";
$etel_hear_about_us['inktomi.com'] = "Inktomi";
$etel_hear_about_us['iwon.com'] = "iWon";
$etel_hear_about_us['looksmart.com'] = "LookSmart";
$etel_hear_about_us['lycos.com'] = "Lycos";
$etel_hear_about_us['search.msn.com'] = "MSN Search";
$etel_hear_about_us['search.netscape.com'] = "Netscape Search";
$etel_hear_about_us['overture.com'] = "Overture";
$etel_hear_about_us['searchking.com'] = "SearchKing";
$etel_hear_about_us['teoma.com'] = "Teoma";
$etel_hear_about_us['webcrawler.com'] = "WebCrawler";
$etel_hear_about_us['wisenut.com'] = "WiseNut";
$etel_hear_about_us['yahoo.com'] = "Yahoo";
$etel_hear_about_us['rsel'] = "Reseller";
												  
$etel_process_volume['2500'] = "0-$5,000";
$etel_process_volume['10000'] = "$5,000-$10,000";
$etel_process_volume['25000'] = "$10,000-$25,000";
$etel_process_volume['50000'] = "$25,000-$50,000";
$etel_process_volume['100000'] = "$50,000-$100,000";
$etel_process_volume['250000'] = "$100,000-$250,000";
$etel_process_volume['500000'] = "$250,000-$500,000";
$etel_process_volume['1000000'] = "$500,000-1MIL";
$etel_process_volume['2000000'] = "1Mil-2Mil";
$etel_process_volume['5000000'] = "2Mil-5Mil";
$etel_process_volume['10000000'] = "5 Mil+";

$etel_routing_types[1] = 'ABA';
$etel_routing_types[2] = 'SWIFT';
$etel_routing_types[3] = 'Chips ID';
$etel_routing_types[4] = 'Sort Code';
$etel_routing_types[5] = 'Transit Number';
$etel_routing_types[6] = 'BLZ Code';
$etel_routing_types[7] = 'BIC Code';
$etel_routing_types[8] = 'Other';

// Password Management 

$etel_PW_response = array(
		201 => 'User Added Successfully',
		202 => 'User Deleted Successfully',
		203 => 'User`s Password Changed Successfully',
		501 => 'Authentication Failed',
		502 => 'Invalid Request Type',
		503 => 'Failed to locate Password file',
		503 => 'Failed to locate Password file',
		504 => 'Failed to open Password file',
		505 => 'Specified User already exists',
		506 => 'Specified User does not exist',
		507 => 'Invalid Username',
		508 => 'Invalid Password');
?>