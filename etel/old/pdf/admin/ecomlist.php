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
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// accounts.php:	The admin page functions for selecting the company for adding company user. 

$allowBank=true;
include("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");


$iCompanyId	=	(isset($HTTP_GET_VARS["id"])?quote_smart($HTTP_GET_VARS["id"]):"")	;	
$iUserId	=	"";	
$sUserName	=	"";
$sPassWord	=	"";
$iUserId	=	(isset($HTTP_GET_VARS["uid"])?quote_smart($HTTP_GET_VARS["uid"]):"")	;
if ( $iUserId != "" ) {
	$qrySelect = "select * from cs_companyusers where id = $iUserId";
	$rstSelect = mysql_query($qrySelect,$cnn_cs);
	if(mysql_num_rows($rstSelect) > 0) {
		$sUserName	=	mysql_result($rstSelect,0,2);
		$sPassWord	=	mysql_result($rstSelect,0,2);
	}


}



?>
<html>
<head>
<title><?=$_SESSION['gw_title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="../styles/style.css" type="text/css" rel="stylesheet">
<link href="../styles/text.css" type="text/css" rel="stylesheet">
<script language="JavaScript">
function funcValidate(objForm) {
	var bCorrect	=	true;
	var objElement	=	objForm.txtUserName;
	if ( bCorrect && objElement.value == "" ) {
		alert("Please enter user name");
		bCorrect = false;
		objElement.focus();
	}
	var objElement	=	objForm.txtPassword;
	if ( bCorrect && objElement.value == "" ) {
		alert("Please enter password");
		bCorrect = false;
		objElement.focus();
	}
	return(bCorrect);
}
function funcDelete() {
	document.frm3VT.action = "vtusersprocess.php?action=delete";
	document.frm3VT.submit();
}

</script>
</head>
<body topmargin="0" leftmargin="0" bgcolor="#ffffff">

<br>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="70%" align="center">
  <tr>
    <td width="95%" valign="top" align="center"><table border="0" cellpadding="0" cellspacing="0" width="95%" >
        <tr>
          <td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Website Associations</span></td>
          <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
          <td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
          <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
        </tr>
        <tr>
          <td width="100%"  valign="top" align="left" class="lgnbd" colspan="5"><table height="100%" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td  width="100%" valign="center" align="center"><table width="100%"  height="100" border="0" cellpadding="0">
                    <tr align="center" valign="middle">
                      <td height="30" width="10%"><font face="verdana" size="1">URL</font></td>
                      <td height="30" width="10%"><font face="verdana" size="1">FTP</font></td>
                      <td height="30" width="10%"><font face="verdana" size="1">Credit Cards</font></td>
                      <td height="30" width="10%"><font face="verdana" size="1">E-Check</font></td>
                      <td height="30" width="10%"><font face="verdana" size="1">ETEL900</font></td>
                      <td height="30" width="10%"><font face="verdana" size="1">Reference ID </font></td>
                      <td height="30" width="30%"><font face="verdana" size="1">Password Management </font></td>
                    </tr>
                    <?php
$sql = "SELECT * FROM `etel_dbsmain`.`cs_company_sites` WHERE `cs_company_id` = '$iCompanyId'  AND cs_hide = '0' AND cs_verified in('approved','non-compliant')";
if(!($result = mysql_query($sql,$cnn_cs)))
{
	print(mysql_errno().": ".mysql_error()."<BR>");
	print ($qry_update."<br>");
	print("Failed to access company URLs");
	exit();
}
else
{
	while ($url = mysql_fetch_assoc($result))
	{	
	?>
                    <tr align="center" valign="middle">
                      <td height="30"><font face="verdana" size="1"> <a target='_blank' href='<?=$url['cs_URL']?>'>
                        <?=$url['cs_URL']?>
                        </a><br> <?php if($url['cs_order_page']){?><a target='_blank' href='<?=$url['cs_order_page']?>'>
                        Order Page
                        </a><?php } ?><br> <?php if($url['cs_return_page']){?><a target='_blank' href='<?=$url['cs_return_page']?>'>
                        Return Page
                        </a><?php } ?><br> <?php if($url['cs_2257_page']){?><a target='_blank' href='<?=$url['cs_2257_page']?>'>
                        2257 Compliance
                        </a><?php } ?> </font></td>
                      <td height="30"><font face="verdana" size="1"> <?php if($url['cs_ftp']){?><a target='_blank' href='<?=$url['cs_ftp']?>'>
                        Ftp Access
                        </a><BR>Username:  <?=$url['cs_ftp_user']?><BR>Password:  <?=$url['cs_ftp_pass']?><?php } ?></font></td>
                      <td height="30"><font face="verdana" size="1">
                        <?=($url['cs_creditcards']?"Enabled":"Disabled")?>
                        </font></td>
                      <td height="30"><font face="verdana" size="1">
                        <?=($url['cs_echeck']?"Enabled":"Disabled")?>
                        </font></td>
                      <td height="30"><font face="verdana" size="1">
                        <?=($url['cs_web900']?"Enabled":"Disabled")?>
                        </font></td>
                      <td height="30"><font color="#CC3300" size="1" face="verdana">
                        <?=($url['cs_reference_ID'])?><BR>
                        <?=($url['cs_verified'])?>
                        </font></td>
                      <td height="30"><font color="#CC3300" size="1" face="verdana">
                        <?php 
						if ($url['cs_enable_passmgmt'] || $url['cs_member_url'])
							echo "Username: ".$url['cs_member_username']."<br>Password: ".$url['cs_member_password']."<br><a href='".$url['cs_member_url']."'  target='_blank' >Link</a>";
						else
							echo "This website either does not have an access section, or is missing the required information.";
						?>
                        </font>
					  </td>
                    </tr>
                    <?php
	
	}


}

?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td width="1%"><img src="images/menubtmleft.gif"></td>
          <td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
          <td width="1%" ><img src="images/menubtmright.gif"></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
