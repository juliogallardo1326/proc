<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// MerchantURL.php:	The page functions to display the merchants URL.

include ("includes/sessioncheck.php");
$nosub = $_GET['nosub'];
if(!$nosub) $headerInclude="profile";
include("includes/header.php");
require_once("../includes/function.php");


	$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";

	$referURL = $_SESSION['gw_domain']."/rms.php?ref=".urlencode($companyInfo['en_ref']);

	$bannerInfo = get_email_template("available_banners",NULL);
	$banners = $bannerInfo['et_htmlformat'];
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="55%">
    <tr>
   		 <td width="100%" align="center">

		 <table width="85%" border="0" cellspacing="0" cellpadding="0" height="168" >
<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Links</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
	    <tr align="center">
          <td colspan="5" valign="middle" class="lgnbd"><table width="96%" height="25" border=1 align="center" cellpadding="0" cellspacing="0" valign="middle">
			<tr>
                <td height="22" colspan="2" align="center" valign="center">
					<p><font face="verdana" size="2">Please use this link to recieve credit for all of your signups. </font></p>
                  <p>&nbsp;</p></td>
              </tr>
				<tr>
                <td  width="22%" height="43" align="center" valign="center">
					<p><font face="verdana" size="2">Reseller Merchant URL: </font></p></td>
                <td  width="78%" valign="center" align="center">
                  <p><font face="verdana" size="1"><a href="<?=$referURL?>" target="_blank"><?=$referURL?></a></font></p></td>
              </tr>

              <tr>
                <td  width="22%" height="40" align="center" valign="center">
				<p><font face="verdana" size="2">Banner Code:</font></p></td>
                <td  width="78%" valign="center" align="center">
                  <p><font face="verdana" size="1">

                  In order to put this banner on your website, please copy the following html code and paste it on your webpage.<br>Download one of the banners below. Do Not link to our banner.<br>

                  <textarea rows="5" cols="50" >&lt;a
href=&quot;<?=$referURL?>&quot;
target=&quot;_blank&quot;&gt;&lt;img
src=&quot;[Your Image File]&quot;&gt;&lt;/a&gt;</font></p></td></textarea>
              </tr>
              <tr>
                <td  width="22%" height="40" align="center" valign="center">
				<p><font face="verdana" size="2">Available Banners:</font></p></td>
                <td  width="78%" valign="center" align="center">
                  <p><?=$banners?></td>
              </tr>
            </table></td>
      </tr>
	<tr>
	      <td width="1%" height="12"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
    </table>
		 </td>
  	</tr>
	</table>
<?php
	include("includes/footer.php");
?>
