<?php
//******************************************************************//
//  This file is part of the Zerone-Consulting development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//viewreportpage.php:	The client page functions for viewing the company transaction details.
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

include 'includes/function2.php';
require_once( 'includes/function.php');
$headerInclude="reports";
include("includes/topheader.php");
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$transactionId=$_GET['id'];
$transactionInfo=getTransactionInfo($transactionId);
?>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>
 <table border="0" cellpadding="0" width="800" cellspacing="0" align="center">
  <tr>
    <td width="100%" valign="top" align="center">
<form name="view" action="viewreportpage.php" method="get">
<br>
    	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Credit&nbsp;
              Card&nbsp;Transaction</span></td>
		<td height="22" align="left" valign="top" width="49" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="55%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr align="center">
		<td width="987" colspan="5" class="lgnbd">

					  <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="50%" valign="top"><p><strong> Client Information:</strong><br>
        Transaction ID: <?=$transactionInfo['transactionId']?><br>
        URL: <a href="<?=$transactionInfo['cs_URL']?>" ><?=$transactionInfo['cs_URL']?></a><br>
                          </p></td>
                          <td width="50%" valign="top"><p><strong> Signup Information:</strong><br>
        Time: <?=date("F j, Y, g:i a",strtotime($transactionInfo['transactionDate']))?><br>
        Email: <a href="<?=$transactionInfo['email']?>"><?=$transactionInfo['email']?></a><br>
        IP Address: <?=$transactionInfo['ipaddress']?><br>
        Phone: <?=$transactionInfo['phone']?></p></td>
                        </tr>
                        <tr>
                          <td valign="top"><p><strong> Subscription Information:</strong><br>
                                  <strong><?=$transactionInfo['userActiveMsg?']?></strong><br>
        <?=$transactionInfo['charge_type_info']?><br>
        SubAccount: <?=$transactionInfo['subAccountName']?><br>
      	 <?=$transactionInfo['schedule']?> <br>
      	 <br>
                              <table width="400" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="135"><p> Next Payment Date: </p></td>
                                  <td width="262"><p> &nbsp;<?=$transactionInfo['nextRecurDate']?></p></td>
                                </tr>
                                <tr>
                                  <td width="135"><p> Member Since: </p></td>
                                  <td width="262"><p> &nbsp;<?=date("F j, Y, g:i a",strtotime($transactionInfo['transactionDate']))?> </p></td>
                                </tr>
                                <tr>
                                  <td><p> Expires: </p></td>
                                  <td><p> &nbsp;<?=$transactionInfo['expires']?> </p></td>
                                </tr>
								<?php if ($expired) { ?>
                                <tr>
                                  <td><p> Expired: </p></td>
                                  <td><p> &nbsp;<?=$transactionInfo['expired']?> </p></td>
                                </tr>
								<?php } ?>
                                <tr>
                                  <td><p> Cancel Date: </p></td>
                                  <td><p> &nbsp;<?=date("F j, Y, g:i a",strtotime($transactionInfo['cancellationDate']))?> </p></td>
                                </tr>
                                <tr>
                                  <td><p> Cancel Reason: </p></td>
                                  <td><p> &nbsp;<?=$transactionInfo['reason']?> </p></td>
                                </tr>
                                <tr>
                                  <td><p> Affiliate: </p></td>
                                  <td><p>&nbsp;<?=($transactionInfo['td_is_affiliate']==1?"Yes":"No")?></p></td>
                                </tr>
                            </table></td>
                          <td valign="top"><p><strong> Customer Information:</strong><br>
        <?=$transactionInfo['surname']?>, <?=$transactionInfo['name']?><br>
        <?=$transactionInfo['address']?><br>
        <?=$transactionInfo['city']?>, <?=$transactionInfo['state']?> <?=$transactionInfo['zipcode']?><br>
        <?=$transactionInfo['country']?><br>
        <br>
                            </p>
                            <p>&nbsp;</p></td>
                        </tr>
                      </table>
					  <table align="center" height="50" ><tr><td><a href="#" onclick="window.history.back()"><img   src="images/back.jpg" border="0"></a>&nbsp;<?php if($status=="A"){if($cancel_count==0){?><input type="image" id="reportview" src="images/canceltransaction.jpg"></input><?php }} ?></td></tr></table>

    </td>
     </tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
</table>
        <table border="0" cellpadding="0" width="100%" cellspacing="0"  align="center">
  <tr>
       <td width="90%" valign="top" align="center" >&nbsp;
         </td>
     </tr>
</table>
</form>	</td></tr></table>
<?php
include 'includes/footer.php';

?>

