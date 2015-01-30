<?

	function en_get_general_info($en_ID)
	{
		$entityInfo=array();
		$sql="SELECT en.*,CONV( en_access,10,2) as en_access_bin,cd.*
		FROM 
			`cs_entities` as en 
			left join cs_companydetails cd on en_type='merchant' and en_type_ID = userId
		where 
			en_ID = '$en_ID'";	
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$entityInfo = mysql_fetch_assoc($result);
		$entityInfo['en_info'] = etel_unserialize($entityInfo['en_info']);
		$entityInfo['PaySchedule'] = en_get_payout_schedule($entityInfo);
		$entityInfo['Affiliates'] = en_get_affiliates($en_ID);
		
		$access_bin = strrev(base_convert($entityInfo['en_access'],10,2));
		$entityInfo['en_access'] = array();
		for($i=0;$i<64;$i++) $entityInfo['en_access'][$i]=substr($access_bin,$i,1)==1;
		if($entityInfo['en_info']['General_Notes']) $entityInfo['en_info']['General_Notes'] = html_entity_decode($entityInfo['en_info']['General_Notes']);
		/*
		$sql="SELECT count(*) as Total, count(distinct file_type) as 'Distinct', sum(file_type='Articles') as Articles, sum(file_type='Contract') as Contracts, sum(file_type='History') as History, sum(file_type='License') as License, sum(file_type='Professional_Reference') as Reference FROM `cs_uploaded_documents` where status='Approved' and ud_en_ID = '$en_ID'";	
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$entityInfo['docs'] = mysql_fetch_assoc($result);
		foreach($entityInfo['docs'] as $type => $doc)
			if($doc) $entityInfo['docs']['stats'] .= "$type:$doc ";
		
		$sql="SELECT count(*) as Total, sum(cs_verified='pending') as Pending, sum(cs_verified='non-compliant') as 'Non-Compliant', 
		sum(cs_verified='approved') as Approved, sum(cs_verified='ignored') as Ignored FROM cs_company_sites where cs_en_ID = '$en_ID'";	
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$entityInfo['sites'] = mysql_fetch_assoc($result);
		foreach($entityInfo['sites'] as $type => $site)
			if($site) $entityInfo['sites']['stats'] .= "$type:$site ";
		*/
		return $entityInfo;
	}
	
	function en_get_affiliates($en_ID,$conditions = array())
	{
		$affiliates = array();
		if($conditions['types']) $add_sql = " And ea_type in ('".implode("','",$conditions['types'])."') ";
		$sql = "Select en_ID,ea_type,en_company,en_email,en_type From cs_entities, cs_entities_affiliates 
			where en_ID = ea_affiliate_ID and ea_en_ID = '".intval($en_ID)."' $add_sql";
		$result = sql_query_read($sql) or dieLog($sql);
		while($aff = mysql_fetch_assoc($result))
			$affiliates[$aff['ea_type']][$aff['en_ID']]=$aff;
		return $affiliates;
	}
	
	function en_get_issues($en_ID,$conditions=NULL)
	{
		if(!$conditions['ShowClosed']) $sql_add .= " AND sta_is_closed = 0 ";
		if($conditions['AnsweredStatus']) $sql_add .= " AND iss_control_status = '".quote_smart($conditions['AnsweredStatus'])."' ";
		$sql = "SELECT ei.*, es.sta_title, ea.ema_id, max(ee.sup_id) as sup_id
			FROM 
				etel_eventum.`ev_issue` as ei
				left join etel_eventum.ev_status es on iss_sta_id = sta_id
				left join etel_eventum.ev_email_account as ea on ema_prj_id = iss_prj_id
				left join etel_eventum.ev_support_email as ee on sup_iss_id = iss_id
			where 
				iss_usr_id = '$en_ID'
				$sql_add
			group by 
				iss_id
			order by
				iss_control_status asc, sta_is_closed asc, iss_last_response_date asc
				";

		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		while($row = mysql_fetch_assoc($result))
			$issues[$row['iss_id']] = $row;
		return $issues;
	}
	
	function en_update_access($en_ID,$newLevels)
	{
		global $curUserInfo;
		if(!$curUserInfo['en_access'][ACCESS_AUTH_ENTITY_ADMIN])
			return array('msg'=>'Invalid Access','status'=>false);
		$entityInfo = en_get_general_info($en_ID);
		$return = array('msg'=>'Entity Access Level Failed to Update (No Changes)','status'=>false);
		$LevelInfo = '';
		foreach($newLevels as $level=>$st)
			if($level>0 && $level<=64) 
				$entityInfo['en_access'][$level] = intval($st);
				
		for($i=0;$i<64;$i++)
			$LevelInfo .= ($entityInfo['en_access'][$i]?1:0);
		$sql="Update `cs_entities` set en_access = '".base_convert(strrev($LevelInfo),2,10)."' where en_ID = '$en_ID'";	

		$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		if(mysql_affected_rows()) 
			$return = array('msg'=>'Entity Access Level Updated ('.sizeof($newLevels).' Change(s))','status'=>true);
		return $return;
	}
	
	function en_get_payout_period(&$entityInfo,$method='next',$time=NULL)
	{
		if(!$time) $time = time();
		$sign = 1;
		if($method=='last') $sign = -1;
		// Next Date
		$bin = strrev(decbin($entityInfo['en_pay_data']));
		for($i=0;$i<=28;$i++) $binarray[$i]=substr($bin,$i,1);
		for($i=1;$i<365;$i++)
		{
			if($entityInfo['en_pay_type']=='Weekly') $dayval = date('w',$time+(60*60*24*$i*$sign));
			if($entityInfo['en_pay_type']=='Monthly') $dayval = date('d',$time+(60*60*24*$i*$sign));
			if($binarray[intval($dayval)])
			{
				return date('Y-m-d',$time+(60*60*24*$i*$sign));
				break;
			}
		}
		return false;
	}
	
	function en_get_payout_schedule(&$entityInfo)
	{
		$week_replace =array('0'=>'Sunday','1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday');
		$bin = strrev(decbin($entityInfo['en_pay_data']));
		for($i=0;$i<=28;$i++) $binarray[$i]=substr($bin,$i,1);
		if($entityInfo['en_pay_type']=='Weekly')
			foreach($binarray as $key=>$val)
				if($key>=0 && $key<=6 && $val && $week_replace[$key]) $days .= (isset($days)?", ":'').$week_replace[$key].'s';	
		if($entityInfo['en_pay_type']=='Monthly')
			foreach($binarray as $key=>$val)
				if($key>0 && $key<=28 && $val) $days .= (isset($days)?", ":'').$key.date('S',strtotime('2000-01-'.$key));
		$return['DayArray'] = $binarray;
		$return['Days'] = $days;
		if(strrpos($return['Days'],',')) 
			$return['Days'] = substr_replace($return['Days']," and",strrpos($return['Days'],','),1);
		$return['Schedule'] = "* Pay Days are ".$entityInfo['en_pay_type']." on ".$return['Days'];
		
		$return['NextPayDay'] = en_get_payout_period($entityInfo);
		$return['LastPayDay'] = en_get_payout_period($entityInfo,'last');
		
		return $return;
	}
	
	function en_get_payout_data($en_ID,$conditions=NULL)
	{
		if($conditions)
			foreach($conditions as $key=>$val)
				$where_sql .= " AND $key = '".quote_smart($val)."' "; 
		$payouts = array();
		$sql="SELECT pa.*,sum(pt_amount) as balance 
		FROM 
			`cs_profit_action` as pa 
			left join cs_profit as pt on pt_action_ID = pa_ID
		where 
			pa_en_ID = '$en_ID' and pa_type = 'Payout' $where_sql
		GROUP BY
			pa_ID
		ORDER BY pa_date desc	
			";	
		
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		while($row = mysql_fetch_assoc($result))
		{
			$row['pa_info'] = @unserialize($row['pa_info']);
			//$row['pa_info']['Notes'] = $sql;
			$payouts[] = $row;
		}
		return $payouts;
	}
	
	function en_update_invoice($pa_ID,$status,$comments='',$email=false)
	{
		global $curUserInfo;
		if(!$curUserInfo['en_access'][ACCESS_AUTH_PAYMENTS])
			return array('msg'=>'Invalid Access','status'=>false);
		$pa_ID = intval($pa_ID);
		$return = array('msg'=>'Payment Invoice Failed to Update (No Changes)','status'=>false);
		$update = ($comments?array('Notes'=>$comments):false);
		if(!in_array($status,array('pending','fail','success','void','payout_pending','payout_sent','payout_failed'))) return array('msg'=>'Invalid Entry','status'=>false);
		if($update) $res = etel_update_serialized_field('cs_profit_action','pa_info'," pa_ID = '$pa_ID'",$update);
	
			$sql="
		UPDATE
			`cs_profit_action`
		SET
		 	pa_status = '$status'
		where 
			pa_ID = '$pa_ID' and pa_type = 'Payout'
			";	
		$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		if(mysql_affected_rows() || $res['updated'] || $email) $return = array('msg'=>"Payment Invoice '$pa_ID' Updated Successfully (".ucfirst($status).")",'status'=>true);
		
		$sql = "
		select 
			pa.*,sum(pt_amount) as balance,en.* 
		from 
			`cs_profit_action` as pa 
			left join `cs_entities` as en on pa.pa_en_ID=en.en_ID
			left join cs_profit as pt on pt_action_ID = pa_ID
		where pa.pa_ID='$pa_ID'
			Group by pa_ID
		";

		$result = mysql_query($sql) or dieLog(mysql_error());
		if(!mysql_num_rows($result)) return array('msg'=>'Payment Invoice or Entity Could not be found','status'=>false);
		
		$companyInfo = mysql_fetch_assoc($result);
		$companyInfo['pa_info'] = @unserialize($companyInfo['pa_info']);
		$letterTempate = 'merchant_wire_success';
		if($status=='payout_failed') $letterTempate = 'merchant_wire_failure';
					
		$emailData["email"] = $companyInfo['en_email'];
		$emailData["companyname"] = $companyInfo['en_company'];
		$emailData["username"] = $companyInfo['en_username'];
		$emailData["Reference_ID"] = $companyInfo['en_ref'];
		$emailData["Message"] = $reject_reason;
		$emailData["gateway_select"] = $companyInfo['en_gateway_ID'];
		
		$emailData["wiredate"] = date('F jS Y',strtotime($companyInfo['pa_date']));
		$emailData["mi_status"] = etel_format_variable($companyInfo['pa_status']);
		$emailData["mi_balance"] = '$'.formatMoney($companyInfo['balance']);
		$emailData["mi_notes"] = $companyInfo['pa_info']['Notes'];		
		$emailData["mi_title"] = $companyInfo['pa_desc'];		
		
	
		toLog('misc','merchant', "Merchant Invoice ".$companyInfo['pa_desc']." has been set ".ucfirst($status)." by IP:".getRealIp(), $_SESSION["sessionlogin"]);
		if($email && $status!='pending') 
		{
			$status = send_email_template($letterTempate,$emailData);
			$return['msg'].=". ".$status['msg'];
		}
		return $return;
	}
	
	function en_adjust_profit($en_ID,$amount,$effective_date=NULL,$title=NULL,$details=NULL)
	{
		global $curUserInfo;
		if(!$curUserInfo['en_access'][ACCESS_AUTH_ADJUSTMENTS])
			return array('msg'=>'Invalid Access','status'=>false);
		$amount = preg_replace('/[^0-9.]/','',$amount);
		$data['date_effective'] = $effective_date;
		$data['description'] = $title;
		if($details) $data['information'] = array('Notes'=>$details);
		
		$RF = new rates_fees();
		$res = $profit_info = $RF->commit_adjustment($en_ID,$amount,$data);
		return $res;
	}

	function en_get_pricepoints($en_ID,$hide=true)
	{
		$en_ID = intval($en_ID);
		if($hide) $sql_hide = " AND rd_hide=0 ";
		$sql="SELECT rd.*,sum(ss_rebill_status='active') as active,count(ss_rebill_status) as total 
		FROM cs_rebillingdetails as rd left join cs_subscription on rd_subaccount = ss_rebill_ID 
		WHERE `rd_en_ID` = '$en_ID' AND rd_hide=0 GROUP BY rd_subaccount ORDER BY `rd_subName` DESC";
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$list = array();
		while($row = mysql_fetch_assoc($result))
		{
			$row['active'] = intval($row['active']);
			$schedule = "$".formatMoney($row['recur_charge'])." Every ".$row['recur_day']." day(s). \n";
			if($row['rd_initial_amount'] > 0) $schedule .="Trial Amount $".formatMoney($row['rd_initial_amount'])." For ".$row['rd_trial_days']." day(s)\n";
			if($row['recur_charge'] <= 0) 
			{
				$schedule = "One Time Payment of $".formatMoney($row['rd_initial_amount']).".";
				if($row['rd_trial_days']) $schedule .= "Subscription time is ".$row['rd_trial_days']." days";
			}
			$row['schedule'] = $schedule;
			$list[$row['rd_subaccount']] = $row;
		}
		return $list;
	}
	

	function en_get_documents($en_ID,$status=false)
	{
		$en_ID = intval($en_ID);
		if($status) $sql_add .= " AND status='$status' ";
		$sql="SELECT * FROM cs_uploaded_documents WHERE `ud_en_ID` = '$en_ID' $sql_add ORDER BY `status` ASC,`file_type` ASC";	
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$list = array();
		while($row = mysql_fetch_assoc($result))
			$list[$row['file_id']] = $row;
		return $list;
	}
	

	function en_confirm_documents($file_id,$status,$reject_reason)
	{
		global $curUserInfo;
		if(!$curUserInfo['en_access'][ACCESS_AUTH_RISK_REVIEW])
			return array('msg'=>'Invalid Access','status'=>false);
		$file_id = intval($file_id);
		$return = array('msg'=>'Merchant File failed to Update','status'=>false);
		if($status=='Declined' && strlen($reject_reason)<3) return array('msg'=>'Website Failed to Update: Invalid Reason','status'=>false);
		$cs_ID = intval($cs_ID);
	
		$sql = "select * from `cs_uploaded_documents` as ud where ud.file_id='$file_id'";

		$result = sql_query_read($sql) or dieLog(mysql_error());
		$docInfo = mysql_fetch_assoc($result);
		$entityInfo = en_get_general_info($docInfo['ud_en_ID']);
		
		$letterTempate = 'merchant_document_approved';
		if($status=='Declined') $letterTempate = 'merchant_document_declined';
					
		$emailData["email"] = $entityInfo['en_email'];
		$emailData["companyname"] = $entityInfo['en_company'];
		$emailData["Reference_ID"] = $entityInfo['en_ref'];
		$emailData["Message"] = $reject_reason;
		$emailData["gateway_select"] = $entityInfo['en_gateway_ID'];
		
		if(!sizeof($entityInfo['Affiliates']['Representative'])) $entityInfo['Affiliates']['Representative'][] = array('en_email'=>'justin@etelegate.com','en_company'=>'Default Rep');
		if($entityInfo['Affiliates'])
			foreach($entityInfo['Affiliates'] as $type=>$group)
				if(in_array($type,array('Reseller','Representative')))
					foreach($group as $id=>$data);
						$emailData["email"] .= ', '.$data['en_email'];
		
		$documentFormat = $docInfo['file_type'].": ".$docInfo['file_name'];
		$emailData["Document"] = $documentFormat;
		
		$sql = "Update `cs_uploaded_documents` set `status` = '$status',`reject_reason` = '$reject_reason' where `file_id` = '$file_id'";
		$result = sql_query_write($sql) or dieLog(mysql_error());
		
		if(mysql_affected_rows()) $return = array('msg'=>"Merchant File '".$docInfo['file_name']."' Updated Successfully (".ucfirst($status)."). Email sent to '".$emailData["email"]."'",'status'=>true);

		toLog('misc','merchant', "Merchant File $documentFormat has been set ".ucfirst($status)." by IP:".getRealIp(), $_SESSION["sessionlogin"]);
		send_email_template($letterTempate,$emailData);
		return $return;
	
	
	}
	
	function en_get_websites($en_ID,$hide=true,$status=false)
	{
		$en_ID = intval($en_ID);
		if($hide) $sql_add .= " AND cs_hide=0 ";
		if($status) $sql_add .= " AND cs_verified='$status' ";
		$sql="SELECT * FROM `cs_company_sites`   WHERE `cs_en_ID` = '$en_ID' && `cs_hide`=0 $sql_add ORDER BY `cs_verified` ASC,`cs_name` ASC";	
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$list = array();
		while($row = mysql_fetch_assoc($result))
			$list[$row['cs_ID']] = $row;
		return $list;
	}

	function en_status_change_notify($en_ID,$emailData=array())
	{
		global $etel_completion_array;
		$entityInfo = en_get_general_info($en_ID);
		$letterTempate = "company_status_changed";
		$emailData['en_email'] = $entityInfo['en_email'];
		$emailData['comments'] = '';
		$edit_link = 'https://www.etelegate.com/admin/editCompanyProfileAccess.php?entity_id='.$entityInfo['en_ID'];
		$emailData['en_phone'] = $entityInfo['en_info']['General_Info']['Contact_Phone'];
		$emailData['en_company'] = $entityInfo['en_company'];
		$emailData['new_status'] = $etel_completion_array[intval($entityInfo['cd_completion'])]['txt'];
		if(!sizeof($entityInfo['Affiliates']['Representative'])) $entityInfo['Affiliates']['Representative'][] = array('en_email'=>'justin@etelegate.com','en_company'=>'Default Rep');
		if($entityInfo['Affiliates'])
			foreach($entityInfo['Affiliates'] as $type=>$group)
				if(in_array($type,array('Reseller','Representative')))
					foreach($group as $id=>$data);
					{
						$emailData['edit_link'] = '';
						if($data['en_type']=='admin') $emailData['edit_link'] = $edit_link;
						$emailData['toemail'] = $data['en_email'];
						$emailData['toname'] = $data['en_company'];
						send_email_template($letterTempate,$emailData);
					}
	}

	function en_confirm_website($cs_ID,$cs_verified,$cs_reason)
	{
		global $curUserInfo;
		if(!$curUserInfo['en_access'][ACCESS_AUTH_RISK_REVIEW])
			return array('msg'=>'Invalid Access','status'=>false);
		$return = array('msg'=>'Website Failed to Update','status'=>false);
		if($cs_verified=='declined' && strlen($cs_reason)<3) return array('msg'=>'Website Failed to Update: Invalid Reason','status'=>false);
		$cs_ID = intval($cs_ID);
		$cs_verified = quote_smart($cs_verified);
		$cs_reason = quote_smart($cs_reason);
		$sql = "select * from `cs_company_sites` as cs where cs.cs_ID='$cs_ID' ";
		
		$result = sql_query_read($sql) or dieLog(mysql_error()." $sql");
		$siteInfo = mysql_fetch_assoc($result);
		$entityInfo = en_get_general_info($siteInfo['cs_en_ID']);
		
		$letterTempate = 'merchant_website_approved';
		if($cs_verified=='declined') $letterTempate = 'merchant_website_declined';
		$cs_URL = $siteInfo['cs_URL'];
					
		$emailData["email"] = $entityInfo['en_email'];
		
		if(!sizeof($entityInfo['Affiliates']['Representative'])) $entityInfo['Affiliates']['Representative'][] = array('en_email'=>'justin@etelegate.com','en_company'=>'Default Rep');
		if($entityInfo['Affiliates'])
			foreach($entityInfo['Affiliates'] as $type=>$group)
				if(in_array($type,array('Reseller','Representative')))
					foreach($group as $id=>$data);
						$emailData["email"] .= ', '.$data['en_email'];
					
		$emailData["companyname"] = $entityInfo['en_company'];
		$emailData["Reference_ID"] = $entityInfo['en_ref'];
		$emailData["gateway_select"] = $entityInfo['en_gateway_ID'];
		$emailData["Message"] = $cs_reason;
		$emailData["site_URL"] = $siteInfo['cs_URL'];
	
		$sql = "Update `cs_company_sites` as cs set `cs_verified` = '$cs_verified',`cs_reason` = '$cs_reason' where `cs_ID` = '$cs_ID'";
		$result = sql_query_write($sql) or dieLog(mysql_error());
		if(mysql_affected_rows()) $return = array('msg'=>"Site '".$entityInfo['cs_name']."' Updated Successfully (".ucfirst($cs_verified)."). Email sent to '".$emailData["email"]."'",'status'=>true);
		
		toLog('misc','merchant', "Merchant Site $cs_URL has been set ".ucfirst($cs_verified)." by IP:".getRealIp(), $_SESSION["sessionlogin"]);
		if($cs_verified!='ignored') send_email_template($letterTempate,$emailData);
		return $return;
	}

	function check_merchant_conflict($companyInfo,$en_ID=NULL)
	{
		$ar = array('en_username','en_ref','en_email','en_company');
		$sql = "SELECT \n";
		foreach($ar as $fld)
			if(isset($companyInfo[$fld])) $sql .= " SUM($fld = '".$companyInfo[$fld]."') as $fld, \n";
		$sql .= "count(*) as cnt
		FROM 
		`cs_entities` 
		WHERE (0
		";		
		foreach($ar as $fld)
			if(isset($companyInfo[$fld])) $sql .= " OR $fld = '".$companyInfo[$fld]."' \n";
		$sql .= ")";
		if($en_ID) $sql .= " AND en_ID != '$en_ID' ";
		
		$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$val = mysql_fetch_assoc($result);
	
		if($val['en_username']) return array('status'=>false,'res'=>$val,'msg'=>"This Username is already in use, please choose another one.");
		if($val['en_email']) return array('status'=>false,'res'=>$val,'msg'=>"This Email Address is already in use, please choose another one.");
		if($val['en_company']) return array('status'=>false,'res'=>$val,'msg'=>"This Company Name is already in use, please choose another one.");
		return array('status'=>true,'res'=>$val);
	}

	function add_new_merchant($companyInfo,$sendmail=true,$getmail=false)
	{
		$returnInfo = array('status'=>true,'msg'=>'Company Created Successfully');
	
		//foreach($companyInfo as $key=>$data)
		//	$companyInfo[$key] = quote_smart($data);
		
		$res = check_merchant_conflict($companyInfo);
		if(!$res['status']) return $res;
		if($res['res']['ref']) $companyInfo['en_ref'] = substr(strtoupper(md5(time()+rand(1,1000000))),0,8);
		
		$qry_insert_user  = " insert into cs_companydetails (username,password,companyname,ReferenceNumber,email,volumenumber,activeuser,transaction_type,how_about_us,date_added,phonenumber,contact_phone,cd_contact_im,gateway_id,url1,cd_timezone)";
		$qry_insert_user .= " values('".$companyInfo['en_username']."','".$companyInfo['en_password']."','".$companyInfo['en_company']."','".$companyInfo['en_ref']."','".$companyInfo['en_email']."','".$companyInfo['volumenumber']."',0,'".$companyInfo['transaction_type']."','".$companyInfo['how_about_us']."',NOW(),'".$companyInfo['phonenumber']."','".$companyInfo['contact_phone']."','".$companyInfo['cd_contact_im']."','".$companyInfo['en_gateway_ID']."','".$companyInfo['url1']."','".$companyInfo['cd_timezone']."')";

		$show_sql =sql_query_write($qry_insert_user) or dieLog(mysql_error()." ~ $str_qry");

		$is_success=0;
		$user_id=mysql_insert_id();
		$user_reference_num=func_User_Ref_No($user_id);
		$is_success=func_update_single_field('cs_companydetails','ReferenceNumber',$user_reference_num,false,'userId',$user_id);

		$sql = "Insert into cs_entities
			set 
				en_username = '".($companyInfo['en_username'])."',
				en_password = MD5('".($companyInfo['en_username'].$companyInfo['en_password'])."'),
				en_ref = '".$companyInfo['en_ref']."',
				en_email = '".$companyInfo['en_email']."',
				en_company = '".$companyInfo['en_company']."',
				en_gateway_ID = '".$companyInfo['en_gateway_ID']."',
				en_signup = NOW(),
				en_type = 'merchant',
				en_type_id = '".quote_smart($user_id)."'
			";
		sql_query_write($sql) or dieLog(mysql_error()." ~ $str_qry");
		$en_ID = mysql_insert_id();
		$returnInfo['en_ID'] = $en_ID;
		
		$sql = "insert into cs_company_banks set cb_en_ID = '$en_ID',bank_id=0;";
		$result = sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
		$cb_ID = mysql_insert_id();
		
		if($companyInfo['etel_reseller_ref'])
		{
			$sql = "SELECT 
				en_ID,en_info from cs_entities where
				en_ref = '".$companyInfo['etel_reseller_ref']."'";
		
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			$resellerInfo = mysql_fetch_assoc($result);
			$resellerInfo['en_info'] = etel_unserialize($resellerInfo['en_info']);
			$returnInfo['ea_affiliate_ID'] = $resellerInfo['en_ID'];
	
			set_affiliate($en_ID,$resellerInfo['en_ID'],'Reseller',array('Disc_Markup'=>$resellerInfo['en_info']['Reseller']['Default_Disc_Markup'],'Trans_Markup'=>$resellerInfo['en_info']['Reseller']['Default_Trans_Markup']));

		}
		
		if($companyInfo['etel_affiliate_ref'])
		{
			$sql = "SELECT 
				en_ID from cs_entities where
				en_ref = '".$companyInfo['etel_affiliate_ref']."'";
		
			$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			$affiliateMerchantInfo = mysql_fetch_assoc($result);
			
			set_affiliate($affiliateMerchantInfo['en_ID'],$en_ID,'Affiliate',array('Disc_Markup'=>$companyInfo['discount_markup'],'Trans_Markup'=>$companyInfo['transaction_markup']));

		}
		
		if($sendmail || $getmail)
		{
			$emailData["email"] = $companyInfo['en_email'];
			$emailData["full_name"] = "Merchant";
			$emailData["companyname"] = $companyInfo['en_company'];
			$emailData["username"] = $companyInfo['en_username'];
			$emailData["password"] = $companyInfo['en_password'];
			$emailData["Reference_ID"] = $companyInfo['en_ref'];
			$emailData["gateway_select"] = $companyInfo['en_gateway_ID'];
			$emailData['tmpl_custom_id'] = $companyInfo['en_gateway_ID'];
			$letterTempate = 'merchant_welcome_letter';
		}
		
		if($sendmail)
			send_email_template($letterTempate,$emailData);
		
		if($getmail)
			$returnInfo['email_info']= get_email_template($letterTempate,$emailData);
			
		return $returnInfo;
	}

	function set_affiliate($en_ID,$affiliate_en_ID,$type='Affiliate',$info = array())
	{
		$return = array('status'=>false,'msg'=>'Unable to add affiliate association');
	
		$sql = "Insert ignore into  
			cs_entities_affiliates set ea_en_ID = '$en_ID', ea_affiliate_ID = '".$affiliate_en_ID."', ea_type = '$type' ";
		sql_query_write($sql) or dieLog(mysql_error()." ~ $str_qry");
		if (mysql_insert_id()>0)
		{
			$return['status'] = true;
			$return['msg'] = $type.' associated successfully';
		}
		$key = $type;
		if($key=='Affiliate') $key .= "_".$affiliate_en_ID;
		$update = array('default'=>array($key=>array('disct'=>$info['Disc_Markup'],'trans'=>$info['Trans_Markup'],'en_ID'=>$affiliate_en_ID)));
		etel_update_serialized_field('cs_company_banks','cb_config'," cb_en_ID = '$en_ID' and bank_id=0",$update);

		return $return;
	}

	function merchant_getGateways()
	{
		global $adminInfo, $etel_gw_list;
		
		$gw_options=NULL;

		if($adminInfo['li_level'] == 'full')
			foreach($etel_gw_list as $gw)
				if($gw['gw_database']==$etel_gw_list[$_SESSION['gw_id']]['gw_database']) 
					$gw_options[$gw['gw_id']]=$gw['gw_title'];

		if(is_array($gw_options)) 
			if(sizeof($gw_options)<2) 
				$gw_options = NULL;

		return $gw_options;
	}
	
	function merchant_getMonthVolume($userId,$time = -1)
	{
			$userId = mysql_real_escape_string($userId);

			if($time<0) $time = time();
			
			$thisMonth = date("Y/m/01 00:00:00",$time);
			$nextMonth = date("Y/m/01 00:00:00",mktime(0,0,0,date("m",$time)+1,1,date("Y",$time)));	
			$volume = 0;

			$sql = "SELECT * FROM cs_transactiondetails WHERE userId = " . $userId . " AND transactionDate >= \"$thisMonth\" AND transactionDate < \"$nextMonth\"";
			$res = sql_query_read($sql);
			if(!$res) exit(mysql_error());
			while($row = mysql_fetch_assoc($res))
				$volume +=$row['amount'];

			$volume = number_format($volume,2);
			return $volume;
	}
	
	function merchant_getTransactions($userId,$test = false)
	{
			$userId = mysql_real_escape_string($userId);

			$table = $test ? "cs_transactiondetails" : "cs_test_transactiondetails";
			$sql = "SELECT * FROM $table WHERE userId = " . $userId . ";";
			$res = sql_query_read($sql);
			if(!$res) exit(mysql_error());
			$list = array();
			while($row = mysql_fetch_assoc($res))
				$list[] = $row;
			return $list;
	}	
	
	function merchant_getBanks($en_ID)
	{
			$userId = mysql_real_escape_string($userId);

			$sql="
				SELECT 
					* 
				FROM 
					cs_company_banks AS a
					LEFT JOIN cs_bank AS b ON a.bank_id = b.bank_id
				WHERE 
					cb_en_ID = '$en_ID' 
					AND b.bk_ignore = 0
				";	
			$res = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			$list = array();
			while($row = mysql_fetch_assoc($res))
				$list[] = $row;
			return $list;
	}

	function merchant_getBank($en_ID,$bank_id)
	{
			$userId = mysql_real_escape_string($userId);
			$bank_id = mysql_real_escape_string($bank_id);

			$sql="
				SELECT 
					* 
				FROM 
					cs_company_banks AS a
					LEFT JOIN cs_bank AS b ON a.bank_id = b.bank_id
				WHERE 
					cb_en_ID = '$en_ID' 
					AND b.bk_ignore = 0
					AND a.bank_id = '$bank_id'
				";	
			$res = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
			if($row = mysql_fetch_assoc($res))
			{
				$row['cb_config'] = unserialize($row['cb_config']);
				return $row;
			}
			return NULL;
	}
	
	function merchant_bank_config(&$row,$order)
	{
		switch($row['bank_id'])
		{
			case 32:
			case 33:
				if($row['cb_config']['custom']['desc_sites'][$order['cs_ID']])
					$row['bank_billing_desc'] = $row['cb_config']['custom']['desc_sites'][$order['cs_ID']];
			break;
		}
	}
	
	function merchant_getTransTypes($en_ID,$order=NULL)
	{
			$userId = mysql_real_escape_string($userId);

			$sql="
				SELECT 
					a.bank_id,
					b.bank_description,
					b.bk_descriptor_visa as bank_billing_desc,
					CONCAT(IF(bk_gkard=1,'wallet','')) AS bank_wallet,
					LOWER(bk_trans_types) as bank_type,
					bk_hide as bank_disabled,
					cb_config
				FROM 
					cs_company_banks AS a
				LEFT JOIN cs_bank AS b ON a.bank_id = b.bank_id
				WHERE 
					a.cb_en_ID = '$en_ID'  
					AND b.bk_ignore = 0
				GROUP BY
					bank_type
				ORDER BY
					bank_type desc
				";	
			$res = sql_query_read($sql) or dieLog(mysql_error()."<pre>$sql</pre>");
			//if(!$res) exit(mysql_error());
			$list = array();
			while($row = mysql_fetch_assoc($res))
			{
				$row['cb_config'] = unserialize($row['cb_config']);
				merchant_bank_config($row,$order);
				if($row['bank_id'] && $row['bank_type']) $list[] = $row;
			}
			return $list;
	}
	
	function merchant_getBanksForTransType($en_ID,$transtype)
	{
		if(strcasecmp($transtype,"master")==0)
			$transtype = "mastercard";
		$transtypes = merchant_getTransTypes($en_ID);
		$bank_ids = $transtypes[strtolower($transtype)];
		return $bank_ids;
	}
	
	function merchant_getAllWebSites()
	{
			$sql="SELECT * FROM `cs_company_sites` WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]."  AND cs_hide = '0' ORDER BY `cs_URL` ASC";	
			$res = sql_query_read($sql);
			if(!$res) exit(mysql_error());
			$list = array();
			while($row = mysql_fetch_assoc($res))
				$list[] = $row;
			return $list;
	}
	
	function merchant_getInfo($en_ID)
	{
		$sql = "SELECT * FROM cs_entities as ce
			LEFT JOIN 
				cs_companydetails as cd ON (cd.userId = ce.en_type_ID  AND ce.en_type = 'merchant')
			WHERE en_ID = '$en_ID'";
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		return mysql_fetch_assoc($res);
	}
	
	
?>