<?php
include("integrate_active.php");
die();
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// integrate.php:	This page functions for uploading the company transactions. 
include 'includes/sessioncheck.php';

include 'includes/header.php';

$headerInclude= "blank";	
include 'includes/topheader.php';
require_once( 'includes/function.php');
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

$gateway_id = func_get_value_of_field($cnn_cs,"cs_companydetails","gateway_id","gateway_id",$sessionlogin);


?>
<script language="javascript">
function func_batchtemplate() {
	document.FrmBatch.action="batchtemplate.php?cctype=pdf";
	document.FrmBatch.submit();
}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="600" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
              <img border="0" src="images/spacer.gif" width="1" height="1">
              </td>
            </tr>
             <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
            </tr>
             <tr>
              <td width="100%" valign="middle" align="left" height="35" class="disctxhd">
                           &nbsp; Integration Guide
                           </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center">
  <form action="batchprocessing.php" method="post" name="FrmBatch"  enctype="multipart/form-data" >
      <table width="500" border="0" cellpadding="0"  >
		  <tr> 
			<td width="500" height="60" align="center" valign="center" bgcolor="#F7F9FB" class="ratebd"><span class="intx">Click 
			  here to download the 
			  <!-- <a href="#" onClick="javascript:func_batchtemplate('pdf');" class="intx1">Integration Guide</a></span> -->
				<a href="/gateway/<?=$_SESSION['gw_folder']?>documents/PaymentIntegrationGuide.pdf" onClick="" class="intx1" target="_blank">Integration Guide</a></span> 
			  </td>
		  </tr>
			  <tr><td align="center" valign="middle" height="30"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a></td></tr>
		  </table>
 </form>
              </td>
            </tr>
          </table>    </td>
     </tr>
</table>
<?php include("includes/footer.php");
?>