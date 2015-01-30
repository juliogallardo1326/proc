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
// telescript.php:	The  page used to create tha tele script. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$type = (isset($HTTP_GET_VARS['type'])?Trim($HTTP_GET_VARS['type']):"profile");
$headerInclude= $type == "testMode" ? "verification" : "profile";	
include 'includes/topheader.php';
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

	if($sessionlogin!=""){
		$company = (isset($HTTP_POST_VARS['company'])?Trim($HTTP_POST_VARS['company']):"");
		$userid = (isset($HTTP_POST_VARS['userid'])?Trim($HTTP_POST_VARS['userid']):"");
		$strDescription  = (isset($HTTP_POST_VARS['txtDescription'])?Trim($HTTP_POST_VARS['txtDescription']):"N");
		$strRefundPolicy  = (isset($HTTP_POST_VARS['txtRefundPolicy'])?Trim($HTTP_POST_VARS['txtRefundPolicy']):"N");
		$strPackagePrice  = (isset($HTTP_POST_VARS['txtPackagePrice'])?Trim($HTTP_POST_VARS['txtPackagePrice']):"");
		$strPackageProduct  = (isset($HTTP_POST_VARS['txtPackageProduct'])?Trim($HTTP_POST_VARS['txtPackageProduct']):"N");
		$strPackagename  = (isset($HTTP_POST_VARS['txtPackagename'])?Trim($HTTP_POST_VARS['txtPackagename']):"N");
		if($strPackagePrice==""){
			$strPackagePrice=0;
		}
		if($company)
		{
			$qrt_update_sql = "update cs_companydetails set telepackagename='$strPackagename',telepackageprod='$strPackageProduct ',telepackageprice=$strPackagePrice,telerefundpolicy='$strRefundPolicy',teledescription='$strDescription' where userid=$userid";
			if(!($show_sql =mysql_query($qrt_update_sql,$cnn_cs))) {
				echo mysql_errno().": ".mysql_error()."<BR>";
				exit();
			}
			$outhtml="y";
			$msgtodisplay="Verification script has been modified";
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}		     
$show_sql =mysql_query("select userId,username,telepackagename,telepackageprod,telepackageprice,telerefundpolicy,teledescription from cs_companydetails where userid=$sessionlogin",$cnn_cs);	
?>
<script language="javascript">
function validation() {
	if(document.Frmcompany.txtPackagename.value=="") {
		alert("Please enter the package name.");
		document.Frmcompany.txtPackagename.focus();
		return false;
	} 
	if(!document.Frmcompany.txtPackageProduct[0].checked && !document.Frmcompany.txtPackageProduct[1].checked && !document.Frmcompany.txtPackageProduct[2].checked) {
		alert("Please select the package or product or service.");
		return false;
	}
	if(document.Frmcompany.txtPackagePrice.value=="") {
		alert("Please enter the package price.");
		document.Frmcompany.txtPackagePrice.focus();
		return false;
	}
	if(document.Frmcompany.txtRefundPolicy.value=="") {
		alert("Please enter the refund policy.");
		document.Frmcompany.txtRefundPolicy.focus();
		return false;
	}

}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="59%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Verification 
            Script </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>

      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
           <form action="telescript.php?type=<?= $type?>" method="post" onsubmit="return validation()" name="Frmcompany">
		  <table width="70%" cellspacing="0" cellpadding="0" ><tr><td align="left"><?=$invalidlogin?>
		<?
			  if($showval = mysql_fetch_array($show_sql)){ 
			  ?>
				 <input type="hidden" name="username" value="<?=$showval[1]?>"></input>
			  <table width="500" border="0" cellpadding="0"  height="100">
                      <input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
                      <tr> 
                        <td align="right" valign="middle" height="30" width="50%"><font face="verdana" size="1">Package 
                          Name &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input name="txtPackagename" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[2]?>"></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" height="30" width="50%"><font face="verdana" size="1">Package 
                           &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="radio" name="txtPackageProduct"  value="Package" <?=$showval[3]=="Package"?"checked":""?>> <font face="verdana" size="1">Product &nbsp;</font><input type="radio" name="txtPackageProduct"  value="Product" <?=$showval[3]=="Product"?"checked":""?>> <font face="verdana" size="1">Service &nbsp;</font><input type="radio" name="txtPackageProduct"  value="Service" <?=$showval[3]=="Service"?"checked":""?>></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" height="30" width="50%"><font face="verdana" size="1">Package 
                          Price &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input name="txtPackagePrice" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[4]?>"></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" height="30" width="50%"><font face="verdana" size="1">Refund 
                          Policy &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input name="txtRefundPolicy" type="text" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[5]?>"></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" height="30" width="50%"><font face="verdana" size="1">Description 
                          &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><textarea name="txtDescription" style="font-family:arial;font-size:10px;width:325px" rows="15"><?=$showval[6]?></textarea></td>
                      </tr>
                      <tr> 
                        <input type="hidden" name="company" value="company"></input>
                      <tr> 
                        <td align="center" valign="center" height="30" colspan="2"> 
                          <input type="image" id="modifycompany" src="images/submit.jpg"></input> 
                        </td>
                      </tr>
                    </table>
		<?
		  }
					  ?>
		  </td></tr></table></form>
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
</table><br>
<?
include 'includes/footer.php';
}
?>