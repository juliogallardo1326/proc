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
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$headerInclude = "home";
include 'includes/topheader.php';
require_once( 'includes/function.php');
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
?>

<script language="javascript">
function validation(){
  if(document.FrmBatch.fle_attachment.value==""){
    alert("Please select the batch file.")
    document.FrmBatch.fle_attachment.focus();
	return false;
  } else if(document.FrmBatch.fle_attachment.value.indexOf('.csv')== -1 ) {
	alert("Please enter the valid csv file");
	return false;
  }else {
		return true;
  }
}
function func_batchtemplate(trans_type) {
	if(trans_type=="check") {
		document.FrmBatch.action="batchtemplate.php?cctype=check";
		document.FrmBatch.submit();
	} else {
		document.FrmBatch.action="batchtemplate.php?cctype=credit";
		document.FrmBatch.submit();
	}

}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table width="50%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Batch 
            Processing</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5">
  <form action="batchprocessing.php" method="post" onsubmit="return validation()" name="FrmBatch"  enctype="multipart/form-data" >
<input type="hidden" name="company" value="<?=$sessionlogin?>">
<br>  <table  width="100%" cellspacing="0" cellpadding="0">
  <tr><td  width="100%" valign="center" align="center">     
      <table width="500" border="0" cellpadding="0"  >	  
       <tr><td align="right" valign="center" height="30" width="170"><font face="verdana" size="1">Batch File :</font></td>
		<td align="left" height="30" width="330"><input type="file" name="fle_attachment" size="30"></input>
		</td>
	  </tr>
       <tr>
	    <td align="right" valign="center" height="30" width="170"><font face="verdana" size="1">Check :</font></td>
		<td align="left" height="30" width="330"><font face="verdana" size="1"><input type="radio" name="trans_type" value="Check" checked>&nbsp;&nbsp;&nbsp;Credit Card :<input type="radio" name="trans_type" value="Credit"></font>
		</td>
	  </tr><?php
		  		if ( trim($_SESSION["sessionactivity_type"]) != "Test Mode" ) {?>
		  <tr><td align="center" valign="center" height="30" colspan="2"><input type="image" id="submitupload" src="images/submit.jpg"></input></td></tr><?php } ?>
  <tr><td align="left" valign="center" height="30"  colspan="2">
<!--  <font face="verdana" size="2">Click here to download the <a href="#" onClick="javascript:func_batchtemplate('check');">Check template</a>&nbsp;/&nbsp;<a href="#" onClick="javascript:func_batchtemplate('credit');">Credit card template</a></font> -->
  <font face="verdana" size="2">Click here to download the <a href="downloads/check_report.csv" onClick="" target="_blank">Check template</a>&nbsp;/&nbsp;<a href="downloads/creditcard_report.csv" onClick="" target="_blank">Credit card template</a></font>
  </td></tr>
	  </table>
  </td></tr>
  </table></form>
	</td>
      </tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
    </table>
    </td>
     </tr>
</table>
<?php include("includes/footer.php");
?>