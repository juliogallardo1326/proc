<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// useraccount.php:	The  page functions for the company account editing. 
include 'includes/sessioncheck.php';
$headerInclude="profile";	
include 'includes/header.php';

	$access = getAccessInfo("
	
	en_username as 'User Name', en_password as 'Enter_Old_Password' ,en_password, en_password as 'Confirm_New_Password'
	
	
	",
	
"cs_entities ",
	"en_ID = '".$companyInfo['en_ID']."'",
	array('Size'=>30,'Valid'=>'req','ExcludeQuery'=>true),$access);
	
	if($access==-1) dieLog("Invalid Company","Invalid Company");
	
	$access['Data']['User Name']['disable']=true;	
	$access['Data']['en_password']['DisplayName']='Enter New Password';
	
	$access['Data']['en_password']['Input']='password';
	$access['Data']['en_password']['Valid']='password';
	$access['Data']['Confirm_New_Password']['Input']='password';
	$access['Data']['Confirm_New_Password']['Valid']='confirm|en_password';
	$access['Data']['Enter_Old_Password']['Input']='password';
	$access['Data']['Enter_Old_Password']['Valid']='password';
	
	if($_POST['en_password'])
	{
		if($_POST['en_password'] != $_POST['Confirm_New_Password']) unset($_POST['en_password']); 
		if(md5($companyInfo['en_username'].$_POST['Enter_Old_Password']) != $companyInfo['en_password']) unset($_POST['en_password']);
		if(strlen($_POST['en_password']) < 5 || strlen($_POST['en_password']) > 15) unset($_POST['en_password']); 
		if($_POST['en_password'])
		{
			$access['Data']['en_password']['ExcludeQuery']=false;
			$_POST['en_password'] = md5($companyInfo['en_username'].$_POST['en_password']);
			$_SESSION['gw_user_hash'] = $_POST['en_password'];
			$result = processAccessForm(&$access);
			$access['HeaderMessage'].="Password Updated Successfully";
		}		
		else
			$access['HeaderMessage'].="Invalid Password Information<BR>";

	}
	else
		$access['HeaderMessage'].="Please Create a new Password.<BR>You may only use lowercase letters, 0-9, and underscore (a-z0-9_)";
	$access['Data']['en_password']['Value']='';
	$access['Data']['Confirm_New_Password']['Value']='';
	$access['Data']['Enter_Old_Password']['Value']='';
	
	beginTable();
	writeAccessForm(&$access);
	endTable("Step #1 - Personal Information","");
	
include 'includes/footer.php';
?>