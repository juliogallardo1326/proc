<?php
//******************************************************************//
//  This file is part of the Zerone Consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone Consulting
// Description:     Payment Gateway
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php,
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// batchuploads.php:	This page functions for updating the company transaction details.
$headerInclude="startHere";
include("includes/header.php");
if($curUserInfo['cd_completion']<3) exit();

$file_status = array();
$company_id =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
if($company_id!="") {
	$gateway_id = func_get_value_of_field($cnn_cs,"cs_companydetails","gateway_id","userid",$company_id);
}
if(isset($_FILES['file_license'])) {
	$file_status[] =func_upload_file($cnn_cs,$_FILES['file_license'],$company_id,"License");
}
if(isset($_FILES['file_article'])) {
	$file_status[] =func_upload_file($cnn_cs,$_FILES['file_article'],$company_id,"Articles");
}
if(isset($_FILES['file_process_history'])) {
	$file_status[] =func_upload_file($cnn_cs,$_FILES['file_process_history'],$company_id,"History");
}
if(isset($_FILES['file_merchant_contract'])) {
	$file_status[] =func_upload_file($cnn_cs,$_FILES['file_merchant_contract'],$company_id,"Contract");
}
if(isset($_FILES['file_professional_reference'])) {
	$file_status[] =func_upload_file($cnn_cs,$_FILES['file_professional_reference'],$company_id,"Professional_Reference");
}

	$str_qry = "update cs_companydetails set cd_completion=6 where userId = '$company_id'";
	if($curUserInfo['cd_completion']==5) 
	{
		sql_query_write($str_qry) or dieLog(mysql_errno().": ".mysql_error()."<BR>$str_qry");
		en_status_change_notify($curUserInfo['en_ID']);
	}

	beginTable();

	print("<br><table align=center width='400' cellpadding='0' cellspacing='0'>");
	print"<tr bgcolor='#CCCCCC'><td class='cl1'><font class='subhd'>&nbsp;File Name</span></td><td class='cl1'><span class='subhd'>&nbsp;Status</span></td></tr>";
	for($i=0;$i<count($file_status);$i++) {
		if($file_status[$i] != "") {
			$file_details = split("@#@",$file_status[$i]);
			print"<tr height='20'><td class='leftbottomright'><font face='verdana' size='1'>&nbsp;$file_details[0]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$file_details[1]</font></td></tr>";
		}
	}

	print "</table>";

endTable("Upload Documents","viewuploads.php",NULL,0,1);

include("includes/footer.php");

function func_upload_file($cnn_cs,$file_object,$company_id,$file_type) {
	global $curUserInfo;
	$return_status = "";
	$b_file_exists = false;
	$str_current_date_time = func_get_current_date_time();
	extract($file_object, EXTR_PREFIX_ALL, 'uf2');
	if ($uf2_name != "" ) {
		$svr = $_SERVER["PATH_TRANSLATED"];
		$path_parts = pathinfo($svr);
		$str_current_path = $path_parts["dirname"];
		$str_file_name = $uf2_name;
		$str_current_path = "";
		$str_file_name = str_replace(" ","",$str_file_name);
		if($file_type=="License") {
			$str_current_path .= "gateway/".$_SESSION['gw_folder']."UserDocuments/License/".$company_id."_".$str_file_name;
		}else if($file_type=="Articles") {
			$str_current_path .= "gateway/".$_SESSION['gw_folder']."UserDocuments/Articles/".$company_id."_".$str_file_name;
		}else if($file_type=="History") {
			$str_current_path .= "gateway/".$_SESSION['gw_folder']."UserDocuments/History/".$company_id."_".$str_file_name;
		}else if($file_type=="Contract") {
			$str_current_path .= "gateway/".$_SESSION['gw_folder']."UserDocuments/Contract/".$company_id."_".$str_file_name;
		}else if($file_type=="Professional_Reference") {
			$str_current_path .= "gateway/".$_SESSION['gw_folder']."UserDocuments/Professional_Reference/".$company_id."_".$str_file_name;
		}
		if(filesize($uf2_tmp_name) != 0)
		{
			if (file_exists($str_current_path)) {
				$b_file_exists = true;
			}
			if(copy($uf2_tmp_name,$str_current_path))
			{
			
				$return_status="$str_file_name@#@Upload Success";
				if(!$b_file_exists) {
					$str_query = "insert into cs_uploaded_documents(user_id, ud_en_ID, file_type, file_name, date_uploaded) values( '".$curUserInfo['userId']."', '".$curUserInfo['en_ID']."', '$file_type', '".$company_id."_".$str_file_name."', '$str_current_date_time')";
					if(!mysql_query($str_query,$cnn_cs))
					{			dieLog(mysql_errno().": ".mysql_error()."<BR>$str_query");

					}
					$i_num_documents = 0;
					$str_query = "select count(distinct(file_type)) from cs_uploaded_documents where ud_en_ID = ".$curUserInfo['en_ID']."";
					if(!($show_sql =mysql_query($str_query,$cnn_cs)))
					{			dieLog(mysql_errno().": ".mysql_error()."<BR>$str_query");

					}
					if(mysql_num_rows($show_sql)>0)
					{
						$i_num_documents = mysql_result($show_sql, 0, 0);
					}
					$completion="";

				}
			}
			else
			{
				$return_status="$str_file_name@#@Upload Failed";
			}
		}
		else
		{
				$return_status="$str_file_name@#@Invalid file";
		}
	}
	return $return_status;

}

?>
