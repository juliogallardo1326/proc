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
include("includes/sessioncheck.php");


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
<title>eTelegate.com</title>
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
<!--header-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="top" align="left" bgcolor="#ffffff" width="35%"><?=$_SESSION["sessionAdminLogged"]=="OutAdmin"?"<img alt='' border='0' src='../images/spacer.gif'>":"<img alt='' border='0' src='../images/logo2os_L.gif'>"?></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top1.jpg" width="238" height="63" ><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top2.jpg" width="217" height="63"><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top3.jpg" width="138" height="63"><br>
<img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top4.jpg" width="238" height="63"><img  alt="" border="0" SRC="<?=$tmpl_dir?>/images/top5.jpg" width="217" height="63"><img alt="" border="0" SRC="<?=$tmpl_dir?>/images/top6.jpg" width="138" height="63"></td>
</tr>
</table>
<!--header ends here-->
<br>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="70%" align="center">
  <tr>
       <td width="95%" valign="top" align="center">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
				<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd"><?= funcGetValueByQuery("select companyname from cs_companydetails where  userId  = $iCompanyId",$cnn_cs) ?></span></td>
				<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
				<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
				<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
				<td colspan="5">
				<br>
				<form name="frm3VT" method="post" action="vtusersprocess.php"  onSubmit="return funcValidate(document.frm3VT)">
				<input type="hidden" name="hdId" value="<?= $iUserId ?>">
				<input type="hidden" name="hdCompanyId" value="<?= $iCompanyId ?>">
				<table cellpadding="4" cellspacing="0" border="1" align="center" width="60%">
					<tr>
						<td><font size="1" face="Geneva, Arial, Helvetica, sans-serif">User Name</font></td>
						<td><input type="text" name="txtUserName" size="30" maxlength="50" value="<?= $sUserName ?>"></td>
					</tr>
					<tr>
						<td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Password</font></td>
						<td><input type="text" name="txtPassword" size="30" maxlength="50" value="<?= $sPassWord ?>"></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="image" SRC="<?=$tmpl_dir?>/images/submit.jpg"></td>
					</tr>
				</table>
				<br>
				
              <table border="1" cellpadding="3" cellspacing="0" align="center" width="100%">
                <tr> 
                  <td background="../images/menucenterbg.gif"><span class="whitehd">No</span></td>
                  <td background="../images/menucenterbg.gif"><span class="whitehd">User Name</span></td>
                  <td background="../images/menucenterbg.gif"><span class="whitehd">Password</span></td>
                  <td background="../images/menucenterbg.gif"><span class="whitehd">Action</span></td>
                  <td background="../images/menucenterbg.gif"><span class="whitehd">Select</span></td>
                </tr>
                <?php
					$qrySelect = "select * from cs_companyusers where userid = $iCompanyId";
					$rstSelect = mysql_query($qrySelect,$cnn_cs);
					for($iLoop = 0;$iLoop<mysql_num_rows($rstSelect);$iLoop++) {
						$iUserId	=	mysql_result($rstSelect,$iLoop,0);	
						$sUserName	=	mysql_result($rstSelect,$iLoop,2);
						$sPassWord	=	mysql_result($rstSelect,$iLoop,3); ?>
                <tr> 
                  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                    <?= $iLoop+1 ?>
                    </font></td>
                  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                    <?= $sUserName ?>
                    </font></td>
                  <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                    <?= $sPassWord ?>
                    </font></td>
                  <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><a href="vtusers.php?uid=<?= $iUserId ?>&id=<?=$iCompanyId?>">Edit</a></font></td>
                  <td align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                    <input type="checkbox" name="chk<?=$iLoop+1?>" value="<?= $iUserId?>">
                    </font></td>
                </tr>
                <?	
					}
				?>
				<tr>
					<td colspan="5" align="right">
					<a href="javascript:funcDelete()"><img SRC="<?=$tmpl_dir?>/images/delete.jpg" border="0"></a>
					</td>
				</tr>
              </table>
			  	<input type="hidden" name="hdCount" value="<?= $iLoop ?>">
				</form>
				</td>			
			</tr>
			<tr>
				<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
				<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
				<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
			</tr>
		</table>
   </td>
  </tr>
</table> 
</body>
</html>
