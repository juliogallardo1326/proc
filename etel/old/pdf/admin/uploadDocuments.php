<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// batchuploads.php:	This page functions for uploading the company transactions. 
$etel_debug_mode=0;
$headerInclude="startHere";
$pageConfig['HideHeader']=true;
include("includes/header.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($_REQUEST['upload']=='yes')
{
	$upload_status = 'Fail';
	$company_id = intval($_REQUEST['company']);
	
	$document_type = isset($HTTP_POST_VARS['document_type'])?quote_smart($HTTP_POST_VARS['document_type']):"";
	if ($document_type == "") {
		$document_type = isset($HTTP_GET_VARS['document_type'])?quote_smart($HTTP_GET_VARS['document_type']):"";
	}
	$upload = isset($HTTP_POST_VARS['upload'])?quote_smart($HTTP_POST_VARS['upload']):"";
	
	if(isset($_FILES['file_document']) && $company_id) {
		$upload_status =func_upload_file($cnn_cs,$_FILES['file_document'],$company_id,$document_type);
		if($document_type == "Contract")
		{	
			$str_qry = "update cs_companydetails set cd_completion=6 where userId = '$company_id' AND cd_completion=5";
			sql_query_write($str_qry,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>$str_qry");
		}
	}
	if ($upload_status=='Success') {
	?>
	<script language="javascript">
			window.opener.location.reload();
			window.close();
	</script>
	<?
	}
}

?>
<title>
eTelegate.com
</title>
<script language="javascript">
function validation(){
	var objForm = document.FrmUploadDocuments;
  if(objForm.file_document.value=="") {
    alert("Please select the file to be uploaded.")
    document.FrmUploadDocuments.file_document.focus();
	return false;
  } else {
	return true;
  }
}
</script>
<link href="../styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="../styles/style.css" type="text/css" rel="stylesheet">
<link href="../styles/text.css" type="text/css" rel="stylesheet">
<?php beginTable() ?>
	<input type="hidden" name="company" value="<?=$company_id?>">
	<select name="document_type" >
	<?=func_get_enum_values('cs_uploaded_documents','file_type',$document_type)?>
	</select>
	<input type="hidden" name="upload" value="yes">
      <table width="350" border="0" cellpadding="0">	 
	  <tr>
                  <td colspan="2" align="center"><font face="verdana" size="1" color="#FF0000"><strong><?= $upload_status?></strong></font></td>
</tr> 
	  <tr>
                  <td colspan="2" align="center" height="30"><font face="verdana" size="1" color="#FF0000"><strong>Each 
                    file uploaded should be of size less than 2 MB.</strong></font></td>
</tr> 
       <tr>
		          <td height="30" align="center" valign="middle" bgcolor="#F8FAFC">
                       <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
					    <input type="file" name="file_document" size="30">
                  </td>
	  </tr>
 
		        <tr valign="middle"> 
                  <td align="center" height="30" colspan="1"><a href="javascript:window.close();"><img border="0" SRC="<?=$tmpl_dir?>/images/close1.gif"></a>&nbsp;&nbsp; 
				<input type="image" id="submitupload" SRC="<?=$tmpl_dir?>/images/submit.jpg"></td></tr>
				</table>
<?php endTable("Upload Documents","uploadDocuments.php") ?>
<?
function func_upload_file($cnn_cs,$file_object,$company_id,$file_type) {
	$return_status = "Success";
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
		$str_current_path .= "../gateway/".$_SESSION['gw_folder']."UserDocuments/$file_type/".$company_id."_".$str_file_name;
		//print ("path=$str_current_path");
		if(filesize($uf2_tmp_name) != 0)
		{
			if (file_exists($str_current_path)) {
				$b_file_exists = true;
			}
			if(copy($uf2_tmp_name,$str_current_path))
			{
				if(!$b_file_exists) {
					$str_query = "insert into cs_uploaded_documents(user_id, file_type, file_name, date_uploaded, status) values($company_id, '$file_type', '".$company_id."_".$str_file_name."', '$str_current_date_time', 'P')";
					if(!sql_query_write($str_query,$cnn_cs))
					{			dieLog(mysql_errno().": ".mysql_error()."<BR>$str_query");

					}
				}
			}
			else
			{
				$return_status="Upload failed, please try again";
			}
		}
		else
		{
				$return_status="Invalid file, please upload a valid file";
		}
	}
	return $return_status;

}
?>