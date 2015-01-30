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
$headerInclude="startHere";
include("includes/header.php");
if($companyInfo['cd_completion']<3) exit();
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
?>
<script language="javascript">
function validation(){
	var objForm = document.FrmUploadDocuments;
  if(objForm.file_license.value=="" && objForm.file_article.value=="" && objForm.file_process_history.value=="" ) {
    alert("Please select the file(s) to be uploaded.")
    document.FrmUploadDocuments.file_license.focus();
	return false;
  } else {
	return true;
  }
}
</script>

<?php beginTable() ?>
	<input type="hidden" name="company" value="<?=$sessionlogin?>">
      <table width="540" border="0" cellpadding="0"  class="mertd">
	  <tr>
                  <td colspan="2" align="center" height="30"><p><font face="verdana" size="1" color="#FF0000"><strong>Each
                    file uploaded should be of size less than 2 MB.<br>You may upload multiple files for each section. </strong></font></p></td>
</tr>
       <tr><td align="right" height="30" width="170" bgcolor="#F8FAFC"><font face="verdana" size="1">Drivers License/Passport :</font></td>
		          <td width="350" height="30" align="left" valign="middle" bgcolor="#F8FAFC">
                    <input type="file" name="file_license" size="30">
                    &nbsp;<a href="#" onclick="javascript:window.open('example.htm','Example','width=650,height=250');" style="font-family:verdana;font-size:11px;font-weight:bold;color:red"><img border="0" src="images/thumb.jpg"></a>
                  </td>
	  </tr>
       <tr><td align="right" valign="center" height="30" width="170" bgcolor="#F8FAFC"><font face="verdana" size="1">Articles of Incorporation :</font></td>
		          <td width="350" height="30" align="left" valign="middle" bgcolor="#F8FAFC">
                    <input type="file" name="file_article" size="30">
                  </td>
	  </tr>
       <tr>
         <td align="right" valign="center" height="30" width="170" bgcolor="#F8FAFC"><font face="verdana" size="1">Previous Processing history&nbsp;or One Month's Bank Statement  :</font></td>
		          <td width="350" height="30" align="left" valign="middle" bgcolor="#F8FAFC">
                    <input type="file" name="file_process_history" size="30">
                  </td>
	  </tr>
	  <?php if ($companyInfo['merchant_contract_agree']) { ?>
       <tr>
         <td align="right" valign="center" height="30" width="170" bgcolor="#F8FAFC"><font size="1" face="verdana">Please Print, Sign, and Upload your Contract </font></td>
		          <td width="350" height="30" align="left" valign="middle" bgcolor="#F8FAFC">
                    <input name="file_merchant_contract" type="file" id="file_merchant_contract" size="30">
                  </td>
	  </tr>
	  <?php } ?>

<!--       <tr><td align="right" valign="center" height="30" width="170" bgcolor="#F8FAFC"><font face="verdana" size="1">Signed merchant Contract :</font></td>
		<td align="left" height="30" width="350" bgcolor="#F8FAFC"><input type="file" name="file_merchant_contract" size="30">
		</td>
	  </tr> -->
		        <tr valign="middle">
                  <td align="center" height="30" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>&nbsp;&nbsp;
				<input type="image" id="submitupload" src="images/submit.jpg"></td></tr>
		        <tr valign="middle">
                  <td align="center" height="30" colspan="2"><p align="center"><font face="verdana" size="1" color="#FF0000"><a href="viewuploads.php" target="_blank">View Uploaded Documents</a></font></p></td>
</td></tr>
			  </table>
<?php endTable("Upload Documents","fileUploader.php") ?>
<?php include("includes/footer.php");
?>