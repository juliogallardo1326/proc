<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// recurringTransacxtion.php:	This page functions for uploading the company transactions. 
$etel_debug_mode = 0;
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude = "transactions";

$value = $_POST['value'];
if (!$value) $value = 29.99;
$product = $_POST['product'];
if (!$product) $product = 'Prod_01';

if ($_POST['value'])
{
	$mt_checksum=md5($companyInfo['cd_secret_key'].$_POST['website'].$_POST['value'].$_POST['product']);

	$msg = "
	Amount to charge = ".$_POST['value']."<BR>
	Website Reference ID = ".$_POST['website']."<BR>
	Product ID = ".$_POST['product']."<BR>
	Secret Key = ".$curUserInfo['cd_secret_key']."<BR>
	MD5( Secret Key + Reference ID + Amount to Charge + Product ID )<BR>
	<strong>Your Checksum = $mt_checksum</strong><BR>
	";

}

$sql = "SELECT * FROM `cs_company_sites` WHERE `cs_en_ID` = '".$curUserInfo['en_ID']."' AND  cs_hide = '0'";
$result = mysql_query($sql,$cnn_cs)or dieLog(mysql_errno().": ".mysql_error()."<BR>");
while($site = mysql_fetch_assoc($result))
{
	$siteList.= "<option value='".$site['cs_reference_ID']."' >".str_replace('http://','',$site['cs_URL'])."</option>";
}

?>
<script language="javascript" src="scripts/general.js"></script>
<script language="javascript" src="scripts/formvalid.js"></script>
<script language="javascript">


</script>
<style type="text/css">
<!--
.style1 {
font-size: 14px;
font-weight:bold;
}
.style2 {font-size: 12px}
-->
</style>

      <div align="center">
      <table width="400" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="80%" background="images/menucenterbg.gif" ><span class="whitehd">Demonstrate Checksum Usage </span></td>
          <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" height="22"></td>
          <td height="22" align="left" valign="top" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
          <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
        </tr>
        <tr>
          <td class="lgnbd" colspan="5"><!--onsubmit="return validation()"-->
            <form action="" method="post" name="FrmDemo" id="FrmDemo" >
              <table  width="100%" border="1" cellpadding="0" cellspacing="0">
                <tr>
                  <td  width="100%" valign="center" align="center"><table width="100%" cellpadding="0"  >
                      <tr>
                        <td colspan="2" align="center"><span class="style2"><font face="verdana">
                        <?=$msg?>
                        <br>
                        </font></span></td>
                      </tr>
                      <tr>
                        <td width="200" height="30" align="right" valign="center"><font face="verdana" size="2">Your Secure Key: </font></td>
                        <td width="350" height="30" align="left"><span class="style1">
                        <?=$companyInfo['cd_secret_key']?></span></td>
                      </tr>
                      <tr>
                        <td height="30" align="right" valign="center"><font face="verdana" size="2">Choose a Price: </font></td>
                        <td width="350" height="30" align="left"><input name="value" type="text" id="value" value="<?=$value?>" src="between|3.95|99"></td>
                      </tr>
                      <tr>
                        <td height="30" align="right" valign="center"><font face="verdana" size="2">Website: </font></td>
                        <td width="350" height="30" align="left"><select name="website" id="website">
                          <option value="01234ABCDE">www.example.com</option>
						  <?=$siteList?>
                                                                        </select></td>
                      </tr>
                      <tr>
                        <td height="30" align="right" valign="center"><font face="verdana" size="2">Product: </font></td>
                        <td width="350" height="30" align="left"><input name="product" type="text" id="product" value="<?=$product?>" src="req"></td>
                      </tr>
                      <!--modification to include recurring details -->
                      <!-- -->
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><input type="image" src="images/submit.jpg">
                        </td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </form></td>
        </tr>
        <tr>
          <td width="1%"><img src="images/menubtmleft.gif"></td>
          <td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" height="11"></td>
          <td width="1%" ><img src="images/menubtmright.gif"></td>
        </tr>
      </table>
      <script language="javascript">
	setupForm(document.getElementById('FrmDemo'));
      </script>
</div>
