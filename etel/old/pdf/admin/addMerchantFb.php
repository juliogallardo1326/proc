<?php
$allowBank=true;
include("includes/sessioncheck.php");

if(!$headerInclude) $headerInclude="companies";
include("includes/header.php");
beginTable();
foreach($HTTP_POST_VARS as $k => $c)
	$postback.= "<input type='hidden' name='$k' value='$c' >";


	$user_reference_num="";
	$newcompany = (isset($HTTP_POST_VARS['newcompany'])?quote_smart($HTTP_POST_VARS['newcompany']):"");
	$username = strtolower(quote_smart($HTTP_POST_VARS['username']));
	$username = str_replace(" ","",$username);
	
	if(strlen($username)<5)
	{
		$result = mysql_query("select max(userid) as max from cs_companydetails") or dieLog(mysql_error());;
		$max=mysql_fetch_assoc($result);
		$username .= "_".$max['max']+1;
	}
	
	//$password = (isset($HTTP_POST_VARS['password'])?quote_smart($HTTP_POST_VARS['password']):"");
	$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
	$ref = (isset($HTTP_POST_VARS['ref'])?quote_smart($HTTP_POST_VARS['ref']):"");
	$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");	//$username = str_replace("'","",$username);
	//$password = str_replace("'","",$password);
	//$companyname = str_replace("'","",$companyname);

	$transaction_type = (isset($HTTP_POST_VARS['rad_order_type'])?quote_smart($HTTP_POST_VARS['rad_order_type']):"");
	if(!$transaction_type) $transaction_type = 'Adult';
	$how_about_us = (isset($HTTP_POST_VARS['how_about_us'])?quote_smart($HTTP_POST_VARS['how_about_us']):"");
	$voulmeNumber = (isset($HTTP_POST_VARS['merchant_volume'])?quote_smart($HTTP_POST_VARS['merchant_volume']):"");
	$reseller = (isset($HTTP_POST_VARS['reseller'])?quote_smart($HTTP_POST_VARS['reseller']):"");
	$phonenumber = (isset($HTTP_POST_VARS['phonenumber'])?quote_smart($HTTP_POST_VARS['phonenumber']):"");
	$contact_phone = $phonenumber;
	$cd_contact_im = (isset($HTTP_POST_VARS['cd_contact_im'])?quote_smart($HTTP_POST_VARS['cd_contact_im_type'].$HTTP_POST_VARS['cd_contact_im']):"");
	
	
	$bank_select="-1";
	if($adminInfo['li_type'] == 'credit') $bank_select=" bank_Creditcard = '$bank_id', ";
	if($adminInfo['li_type'] == 'check') $bank_select=" bank_check = '$bank_id', ";
	
	$gateway_id=$_SESSION['gw_id'];
	$password = strtolower(substr(md5(time),0,6));
	$cd_th_ID = $_COOKIE['referal_th_id'];
	if(!$cd_ts_ID) $cd_ts_ID = -1;
	
	$emailData["email"] = $email;
	$emailData["full_name"] = "Merchant";
	$emailData["companyname"] = $newcompany;
	$emailData["resellername"] = $companyname;
	$emailData["username"] = $username;
	$emailData["password"] = $password;
	$resellerUserId=-1;
	
	$ref = $resellerInfo['rd_referenceNumber'];
	
	$qry_select="SELECT * FROM cs_resellerdetails WHERE reseller_companyname='$companyname' OR rd_referenceNumber='$ref' ";
	$result=mysql_query($qry_select,$cnn_cs) or dieLog(mysql_error());

	if(mysql_num_rows($result)>0)
	{	
		$rs=mysql_fetch_array($result);
		$resellerCoName=$rs['reseller_companyname'];
		$resellerUserId=$rs['reseller_id'];
		$emailData["resellername"] = $resellerCoName;
		$emailData["reselleremail"] = $rs['reseller_email'];
	}
	$msgtodisplay = "";
	
	if ($transaction_type == "tele")
	{
		$send_ecommercemail = 0;
		$block_virtual_terminal = 0;
	}
	else
	{
		$send_ecommercemail = 1;
		$block_virtual_terminal = 1;
	}

	$current_date_time = func_get_current_date_time();
	$user_nameexist=0;
	if($newcompany)
	{
    	$user_nameexist =func_checkUsernameExistInAnyTable($username,$cnn_cs);
		$user_emailexist=func_checkEmailExistInAnyTable($email,$cnn_cs);
		$user_companyexist=func_checkCompanynameExistInAnyTable($newcompany,$cnn_cs);
		$qry_select_user = "select username  from cs_companydetails where ( companyname='$newcompany' or email='$email' )";
		//print $qry_select_user;
		if(!($show_sql = mysql_query($qry_select_user)))
		{			
		dieLog(mysql_errno().": ".mysql_error()."<BR>");
		}elseif($user_nameexist==1) {
			message("Existing username. Please choose another".$postback,"","Existing username","addMerchant.php",false);
		}
		elseif($user_companyexist==1){
			message("Existing company name. Please choose another".$postback,"","Existing Company","addMerchant.php",false);
		}
		elseif($user_emailexist==1){
			message("Existing email id. Please choose another".$postback,"","Existing Email","addMerchant.php",false);
		}
        else
		{
			$cd_subgateway_id = 'NULL';	// Gateway Reseller?
			if($resellerInfo['reseller_id']==$resellerInfo['rd_subgateway_id'] && $resellerInfo['rd_subgateway_id']>0) $cd_subgateway_id = "'".$resellerInfo['rd_subgateway_id']."'";
			
			$qry_insert_user  = " insert into cs_companydetails (username,password,companyname,email,volumenumber,activeuser,transaction_type,how_about_us,reseller_other,date_added,send_ecommercemail,block_virtualterminal,reseller_id,cd_th_ID,phonenumber,contact_phone,cd_contact_im,cd_subgateway_id,gateway_id)";
			$qry_insert_user .= " values('$username','$password','$newcompany','$email','$voulmeNumber',0,'$transaction_type','$how_about_us','$reseller','$current_date_time',$send_ecommercemail,$block_virtual_terminal,'$resellerUserId','$cd_th_ID','$phonenumber','$contact_phone','$cd_contact_im',$cd_subgateway_id,'$gateway_id')";

			$show_sql =mysql_query($qry_insert_user) or dieLog(mysql_error()." ~ $qry_insert_user");
	
			$is_success=0;
			$user_id=mysql_insert_id();
			$user_reference_num=func_User_Ref_No($user_id);
			$is_success=func_update_single_field('cs_companydetails','ReferenceNumber',$user_reference_num,'','userId',$user_id);
			
			$sql = "Insert into cs_entities
				set 
					en_username = '".($username)."',
					en_password = MD5('".($username.$password)."'),
					en_email = '".quote_smart($email)."',
					en_gateway_ID = '".quote_smart($gateway_id)."',
					en_type = 'merchant',
					en_type_id = '".quote_smart($user_id)."'
				";
			sql_query_write($sql) or dieLog(mysql_error()." ~ $str_qry");
			
			
			
			$emailData["Reference_ID"] = $user_reference_num;
			$emailData["gateway_select"] = $companyInfo['gateway_id'];
			$letterTempate = 'merchant_welcome_letter';
			if($resellerUserId>-1 && $resellerCoName) $letterTempate = 'merchant_referral_letter';

			send_email_template($letterTempate,$emailData);
		
			message("Merchant: $companyname Created Successfully. Email sent to $email","Success","Success",false);
			

		}
	}
include("includes/footer.php");
?>
