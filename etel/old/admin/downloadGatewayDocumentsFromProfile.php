<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,application.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//downloadDocumentsFromProfile.php:	The admin page functions for selecting the type of report view  for the company. 

include("includes/sessioncheck.php");


include("includes/zipclass.php");
include("includes/message.php");

set_time_limit(300);
ignore_user_abort(true);
ini_set("max_execution_time",0);

$company_name = isset($HTTP_GET_VARS['companyname'])?$HTTP_GET_VARS['companyname']:"";
$is_app = isset($HTTP_GET_VARS['chk_app'])?$HTTP_GET_VARS['chk_app']:"";
$is_doc = isset($HTTP_GET_VARS['chk_doc'])?$HTTP_GET_VARS['chk_doc']:"";
$gatewayCompanyId = isset($HTTP_GET_VARS['gatewayCompanies'])?$HTTP_GET_VARS['gatewayCompanies']:"A";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"tele";
$completed_uploading = isset($HTTP_GET_VARS['completed_uploading'])?quote_smart($HTTP_GET_VARS['completed_uploading']):"";
$completed_application = isset($HTTP_GET_VARS['completed_application'])?quote_smart($HTTP_GET_VARS['completed_application']):"";
$ready_to_wire = isset($HTTP_GET_VARS['ready_to_wire'])?quote_smart($HTTP_GET_VARS['ready_to_wire']):"";
if ($company_name == "") {
	$msgtodisplay = "Please select a company";
} else {
	$str_where_condition = "";
	$str_company_ids = "";
	$str_failure_companies = "";
	$error_msg = "";
	$arr_result = array();
	if ($company_name[0] == "A") {
		if ($companytype == "A") {
			if ($companytrans_type == "A") {
				$str_where_condition = "";
			} else {
				$str_where_condition = " transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "AC") {
			if ($companytrans_type == "A") {
				$str_where_condition = " activeuser = 1 ";
			} else {
				$str_where_condition = " activeuser = 1 and transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "NC") {
			if ($companytrans_type == "A") {
				$str_where_condition = " activeuser = 0 ";
			} else {
				$str_where_condition = " activeuser = 0 and transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "RE") {
			if ($companytrans_type == "A") {
				$str_where_condition = " reseller_id <> '' ";
			} else {
				$str_where_condition = " reseller_id <> '' and transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "ET") {
			if ($companytrans_type == "A") {
				$str_where_condition = " reseller_id is null ";
			} else {
				$str_where_condition = " reseller_id is null and transaction_type = '$companytrans_type' ";
			}
		}
		 $sExtra = "";
		 if($completed_uploading ==1) {
			if($sExtra =="") {
				$sExtra = " num_documents_uploaded=4";	
			}else {
				$sExtra .= " or num_documents_uploaded=4";	
			}
		 }
		 if($completed_application ==1) {
			if($sExtra =="") {
				$sExtra = " completed_merchant_application=1";
			} else {
				$sExtra .= " or completed_merchant_application=1";
			}
		 }

		 if($ready_to_wire ==1) {
			if($sExtra =="") {
				$sExtra = " completed_uploading='Y'";
			} else {
				$sExtra .= " or completed_uploading='Y'";
			}
		 }
		
		if ( $sExtra != "" ) {
			if ($str_where_condition != "") {
				$str_where_condition .=	" and (".$sExtra.")";
			} else {
				$str_where_condition .=	" ".$sExtra;
			}
		}
	} else {
		for ($i = 0; $i < count($company_name); $i++) {
			$str_company_ids .= $company_name[$i] . ", ";
		}
		$str_company_ids = substr($str_company_ids, 0, strlen($str_company_ids) - 2);
		$str_where_condition = " userId in ($str_company_ids)";
	}
	if ($str_where_condition != "") {
		$str_where_condition =	" and ".$str_where_condition;
	}
	$str_gateway_qry = "";
	if ($gatewayCompanyId == "A") {
		$str_gateway_qry = "gateway_id <> -1";
	} else {
		$str_gateway_qry = "gateway_id = '$gatewayCompanyId'";
	}
	$str_qry_company ="select companyname,userId from cs_companydetails where $str_gateway_qry $str_where_condition order by companyname";
	//$str_qry_company ="select * from cs_companydetails where userId = $company_name";
	//print($str_qry_company);
	$rst_qry_company = mysql_query($str_qry_company);
	$zipfile = new zipfile();  
	while($arr_qry_company = mysql_fetch_array($rst_qry_company))
	{
		$str_company_name =  $arr_qry_company['companyname'];
		$company_id = $arr_qry_company['userId'];
		$i_max_file_size_MB = 3; 
		$dir_company_name = "Documents/".func_replace_invalid_literals($str_company_name);

		$myLicenceFileArray = array();
		$myArticlesFileArray = array();
		$myHistoryFileArray = array();
		$myProfessionalReferenceFileArray = array();
		$str_qry = "select file_type, file_name from cs_uploaded_documents where user_id = $company_id and status = 'A'";
		if(!($show_sql =mysql_query($str_qry,$cnn_cs)))
		{
			print(mysql_errno().": ".mysql_error()."<BR>");
			print("Cannot execute query");
			print($str_qry);
			exit();
		}
		if(mysql_num_rows($show_sql)>0)
		{
			while($showval = mysql_fetch_row($show_sql)) 
			{
				if ($showval[0] == "License") {
					$myLicenceFileArray[] = $showval[1];
				} else if ($showval[0] == "Articles") {
					$myArticlesFileArray[] = $showval[1];
				} else if ($showval[0] == "History") {
					$myHistoryFileArray[] = $showval[1];
				} else if ($showval[0] == "Professional_Reference") {
					$myProfessionalReferenceFileArray[] = $showval[1];
				}
			}
		}

		if (count($myArticlesFileArray) > 0) {
			$zipfile -> add_dir($dir_company_name."/");
			for($i_loop=0;$i_loop<count($myArticlesFileArray);$i_loop++) 
			{	
				
				$filedata = func_read_file("../gateway/".$_SESSION['gw_folder']."UserDocuments/Articles/".$myArticlesFileArray[$i_loop]);
				$zipfile -> add_file($filedata, $dir_company_name."/Articles_".$myArticlesFileArray[$i_loop]);  
			}
		}

		if (count($myHistoryFileArray) > 0) {
			$zipfile -> add_dir($dir_company_name."/");
			for($i_loop=0;$i_loop<count($myHistoryFileArray);$i_loop++) 
			{	
				$filedata = func_read_file("../gateway/".$_SESSION['gw_folder']."UserDocuments/History/".$myHistoryFileArray[$i_loop]);
				$zipfile -> add_file($filedata, $dir_company_name."/History_".$myHistoryFileArray[$i_loop]);  
			}
		}

		if (count($myLicenceFileArray) > 0) {
			$zipfile -> add_dir($dir_company_name."/");
			for($i_loop=0;$i_loop<count($myLicenceFileArray);$i_loop++) 
			{	
				$filedata = func_read_file("../gateway/".$_SESSION['gw_folder']."UserDocuments/License/".$myLicenceFileArray[$i_loop] );
				$zipfile -> add_file($filedata, $dir_company_name."/License_".$myLicenceFileArray[$i_loop]);
			}
		}

		if (count($myProfessionalReferenceFileArray) > 0) {
			$zipfile -> add_dir($dir_company_name."/");
			for($i_loop=0;$i_loop<count($myProfessionalReferenceFileArray);$i_loop++) 
			{	
				$filedata = func_read_file("../gateway/".$_SESSION['gw_folder']."UserDocuments/Professional_Reference/".$myProfessionalReferenceFileArray[$i_loop] );
				$zipfile -> add_file($filedata, $dir_company_name."/Professional_Reference_".$myProfessionalReferenceFileArray[$i_loop]);
			}
		}
	}
	if ($zipfile -> get_file_size() > 0) {
		//$arr_result = func_send_documents($zipfile, $str_company_name, $other_email, $str_failure_companies, //$i_max_file_size_MB, $error_msg);
		//$zip_file_name = func_replace_invalid_literals($str_company_name);
		$zip_file_name = "Documents";
		$filename = "csv/".$zip_file_name.".zip";
		$fd = fopen ($filename, "wb");
		$out = fwrite ($fd, $zipfile -> file());
		fclose ($fd);
		$msgtodisplay = "<a href='csv/Documents.zip' target='_blank' onClick='window.close()'>Click here to Download the documents</a>";
	} else {
		$msgtodisplay = "No Documents to Download";
	}
}

function getDirList ($dirName,$i_company) 
{
	$d = dir($dirName);
	$filename="";
	while($entry = $d->read()) {	
		//echo strpos($entry,$i_company);
		if($entry != "." && $entry != "..")
		{
			if(strpos($entry,$i_company)>=0)
			{
				//echo $dirName."\\".$entry."\n";
				$filename= $entry;
				break;
			}
		}
		}	
	
$d->close();
return $filename;
}

function delete($file) {
 if (file_exists($file)) {
   chmod($file,0777);
   if (is_dir($file)) {
     $handle = opendir($file); 
     while($filename = readdir($handle)) {
       if ($filename != "." && $filename != "..") {
         delete($file."/".$filename);
       }
     }
     closedir($handle);
     rmdir($file);
   } else {
     unlink($file);
   }
 }
}

function func_read_file($filename)
{
	$handle = fopen ($filename, "rb"); 
	$contents = fread ($handle, filesize ($filename)); 
	fclose ($handle); 
	return $contents; 
}

function func_replace_invalid_literals($str_company_name) {
	$str_company_name = str_replace(" ","_",$str_company_name);
	$str_company_name = str_replace("/","_",$str_company_name);
	$str_company_name = str_replace("\\","_",$str_company_name);
	$str_company_name = str_replace(":","_",$str_company_name);
	$str_company_name = str_replace("*","_",$str_company_name);
	$str_company_name = str_replace("?","_",$str_company_name);
	$str_company_name = str_replace("\"","_",$str_company_name);
	$str_company_name = str_replace("<","_",$str_company_name);
	$str_company_name = str_replace(">","_",$str_company_name);
	$str_company_name = str_replace("|","_",$str_company_name);
	return $str_company_name;
}
?>

<html>
<head>
<link href="../styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="../styles/style.css" type="text/css" rel="stylesheet">
<link href="../styles/text.css" type="text/css" rel="stylesheet">
<title>
::Payment Gateway::
</title>
</head>
<body>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
<td width="83%" valign="top" align="center">&nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="50%" >
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Message</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
<form>
<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
<table width='400' border='0' cellpadding='0' >
<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php print $msgtodisplay; ?></font>
</td></tr></table></td></tr>
<tr><td height="50" valign="center" align="center">
<a href="#" onclick='javascript:window.close()'><img SRC="<?=$tmpl_dir?>/images/close1.gif" border="0"></a>
</td></tr></table>
<tr>
<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
</tr>
</form>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>