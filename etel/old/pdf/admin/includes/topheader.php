<?php


	//*********** Header creation page *************
	if(!isset($headerInclude))
	{
		$headerInclude = "blank";
	}	
	//*************** if the header include is blank or nothing is given then ********
	//********************************************************************************
if($headerInclude == "blank")
{ /* ?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="400" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td width="100%" height="20" valign="middle" align="center"></td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}
if($headerInclude == "administration")
{ 
		$link['href'] = "changePassword.php";
		$link['text'] = "Change Password";
		$sub_header['links'][] = $link;
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="220" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" class="linkblack" valign="middle" align="center" nowrap><A href="useraccount.php" class="maintx">Change Password</a></td>
<td height="20" valign="middle" align="center" class="linkwhite" nowrap><a href="negativedatabase.php" class="maintx">Negative Database</a></td>
<!-- <td height="20" valign="middle" align="center" class="linkwhite"><a href="bankcompany.php" class="maintx">Bank Details</a></td> -->
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}
if($headerInclude == "mail")
{ ?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="550" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" class="linkblack" valign="middle" align="center" ><a href="massmail1.php" class="maintx">Mass Mail</a></td>
<td height="20" class="blackrgt" valign="middle" align="center" ><a href="downloadDocuments.php" class="maintx">Document / Application</a></td>
<td height="20" class="blackrgt" valign="middle" align="center"><A href="labels.php" class="maintx">Labels</a></td>
<td height="20" class="blackrgt" valign="middle" align="center"><a href="printemailsform.php" class="maintx">Printable Letters</a></td>
<td height="20" class="blackrgt" valign="middle" align="center" ><a href="emailrecipt_blank.php" class="maintx">Email Receipts</a></td>
<td height="20" class="linkwhite" valign="middle" align="center" ><a href="bad_emails.php" class="maintx">Invalid Emails</a></td>
<!--
<td height="20" class="blackrgt" valign="middle" align="center" ><a href="unsubscribe_mail.php" class="maintx">Auto Ecommerce Letter</a></td>
-->
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}

if($headerInclude == "companies")
	{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="540" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" class="linkblack" valign="middle" align="center"><a href="companyAdd.php" class="maintx">Add Company</a></td>
<td height="20" class="blackrgt" valign="middle" align="center"><a href="viewCompany.php" class="maintx">Company List</a></td>
<td height="20" class="blackrgt" valign="middle" align="center"><a href="companydetails.php" class="maintx">Company Legend</a></td>
<td height="20" valign="middle" align="center" class="blackrgt" nowrap><a href="reseller_blank.php" class="maintx">Reseller</a></td>
<td height="20" valign="middle" align="center" class="blackrgt" nowrap><a href="setupfee.php" class="maintx">Setup Fee</a></td>
<td height="20" valign="middle" align="center" class="linkwhite" nowrap><a href="viewGatewayCompany.php" class="maintx">Gateway Company List</a></td>
</tr>
</table>
    </td>
</tr>
</table>
<!--submenu ends-->
<?php
}
?>
<?php
	if($headerInclude == "adminemail")
	{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="160" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" class="linkblack" valign="middle" align="center"><a href="massmail1.php" class="maintx">Mass Mail</a></td>
<td height="20"  class="linkwhite" valign="middle" align="center"><a href="massmail2.php" class="maintx">Blank Mailer</a></td>
<!--<td height="20"  class="linkwhite" valign="middle" align="center"><a href="reply_registrationmail.php" class="maintx">Auto Letters</a></td> -->
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}
?>	
<?php
	if($headerInclude == "emailReceipts")
	{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="360" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" class="linkblack" valign="middle" align="center" ><a href="dnc_emails.php" class="maintx">DNC Emails</a></td>
<td height="20" class="blackrgt" valign="middle" align="center" ><a href="ordermail.php" class="maintx">Email 
Receipt for Orders</a></td>
<td height="20" class="linkwhite" valign="middle" align="center" ><a href="bankcompany.php" class="maintx">Email 
Receipt for Cancels</a></td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}
if($headerInclude == "customerservice")
{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="320" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" class="linkblack" valign="middle" align="center"><a href="enquires.php" class="maintx">Unfound Calls</a></td>
<td height="20" valign="middle" align="center" class="blackrgt"><a href="report_custom.php" class="maintx">Found Calls</a></td>
<td height="20" valign="middle" align="center" class="blackrgt"><a href="cancelrequests.php" class="maintx">Refund Requests</a></td>
<td  height="20" valign="middle" align="center" class="blackrgt"><a href="service_users.php" class="maintx">Users</a></td>
<td  height="20" valign="middle" align="center" class="linkwhite"><a href="logView.php" class="maintx">Log Viewer</a></td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}

if($headerInclude == "ledgers")
{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
  <table width="205" border="0" cellpadding="0" cellspacing="0" height="10">
    <tr>
      <td  height="20" align="center" valign="middle" class="linkblack"><a href="ledger.php" class="maintx">Ledger</a></td>
      <td  height="20" align="center" valign="middle" class="blackrgt"><a href="projectedsettlement.php" class="maintx">Projected Settlemsent</a></td>
      <td  height="20" align="center" valign="middle" class="blackrgt"><a href="paymentReport.php" class="maintx">Merchant Invoice/Payments</a></td>
      <td  height="20" align="center" valign="middle" class="blackrgt"><a href="ResellerPaymentReport.php" class="maintx">Reseller Invoice/Payments</a></td>
      <td  height="20" align="center" valign="middle" class="linkwhite"><a href="quickstats.php" class="maintx">Quick Stats </a></td>
    </tr>
  </table></td>
</tr>
</table>
<!--submenu ends-->
<?php
	}
?>	
<?php
if($headerInclude == "transactions")
{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="820" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
          <td  height="20" align="center" valign="middle" class="linkblack"><a  href="report.php" class="maintx">Transactions</a></td>
          <td  height="20" align="center" valign="middle" class="blackrgt"><a href="export.php" class="maintx">Export 
            Transactions</a></td>
          <td  height="20" align="center" valign="middle" class="blackrgt" ><a href="virtualterminal.php" class="maintx">Virtual 
            Terminal</a></td>
          <td  height="20" align="center" valign="middle" class="blackrgt"><a href="batchuploads.php" class="maintx">Batch 
            Processing</a></td>
          <!--td  height="20" align="center" valign="middle" class="blackrgt"><a href="cancelrequests_company.php" class="maintx">Refund 
            Requests</a></td-->
          <td  height="20" align="center" valign="middle" class="blackrgt"><a href="gatewayList.php" class="maintx">Gateway 
            Transactions</a></td>
<td height="20" valign="middle" align="center" class="blackrgt"><a href="refundslist.php" class="maintx">Refunded Transactions</a></td>
        <td  height="20" align="center" valign="middle" class="linkwhite"><a href="rebillinglist.php" class="maintx">Recurring 
            Transaction </a></td>
 <!-- <td height="20" class="linkwhite" valign="middle" align="center"><a  href="accounts.php" class="maintx">Complete Accounting</a></td>-->
</tr>
</table>
</td>
</tr>
</table> 
<!--submenu ends-->

<!--submenu starts-->
<!--
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="400" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" valign="middle" align="center" class="linkblack"><a href="export.php" class="maintx">Export Transactions</a></td>
<td height="20" class="blackrgt" valign="middle" align="center" ><a href="virtualterminal.php" class="maintx">Virtual Terminal</a></td>
<td height="20" valign="middle" align="center" class="blackrgt"><a href="batchuploads.php" class="maintx">Batch Processing</a></td>
<td height="20" class="linkwhite" valign="middle" align="center"><a  href="report.php" class="maintx">Transactions</a></td>
</tr>
</table>
</td>
</tr>
</table>-->
<!--submenu ends-->
<?php
	}
?>

<?php
if($headerInclude == "service")
{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="240" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}
?>	
<?php
if($headerInclude == "lettertemp")
{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="200" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" class="linkblack" valign="middle" align="center"><a  href="printemailsform.php" class="maintx">Print Letters</a></td>
<td height="20" valign="middle" align="center" class="linkwhite"><a href="maileditor.php" class="maintx">Letter Template</a></td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}
?>	

<?php
	
if($headerInclude == "negativedatabase")
{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="400" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td width="100%" height="20" valign="middle" align="center"></td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}

if($headerInclude == "voicesystem") {
?>
	<!--submenu starts-->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
	<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
	<table width="190" border="0" cellpadding="0" cellspacing="0" height="10">
	<tr>
	<td height="20" class="linkblack" valign="middle" align="center"><a href="voicesystem.php" class="maintx">Upload Reports</a></td>
	<td height="20" class="linkwhite" valign="middle" align="center"><a href="voicesystemreport.php" class="maintx">View Reports</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<!--submenu ends-->
<?	
}
if($headerInclude == "bank") {
?>
	<!--submenu starts-->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
	<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
	<table width="190" border="0" cellpadding="0" cellspacing="0" height="10">
	<tr>
	<td height="20" class="linkblack" valign="middle" align="center"><a href="company_banklist.php" class="maintx">Bank Details</a></td> 
	<td height="20" class="linkwhite" valign="middle" align="center"><a href="transactionverification.php" class="maintx">ATM Verification</a></td> 
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<!--submenu ends-->
<?	
}
if($headerInclude == "bank1") {
?>
	<!--submenu starts-->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
	<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
	<table width="350" border="0" cellpadding="0" cellspacing="0" height="10">
	<tr>
	<td height="20" class="linkblack" valign="middle" align="center"><a href="company_banklist.php" class="maintx">Add Bank Details</a></td> 
	<td height="20" class="blackrgt" valign="middle" align="center"><a href="modifycompany_bankdetails.php" class="maintx">Modify Bank Details</a></td> 
	<td height="20" class="linkwhite" valign="middle" align="center"><a href="viewcompany_banklist.php" class="maintx">View Bank Details</a></td> 
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<!--submenu ends-->
<?	
}
if($headerInclude == "autoLetters")
{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="190" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" valign="middle" align="center" class="linkblack" nowrap><a href="reply_registrationmail.php" class="maintx">Login Letter</a></td>
<td height="20" class="linkwhite" valign="middle" align="center" ><a href="ecommerce_letter.php" class="maintx">Ecommerce Letter</a></td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}
?>	
<?php
	if($headerInclude == "reseller")
	{
?>
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="../images/menubtmbg.gif"><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="220" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td height="20" class="linkblack" valign="middle" align="center"><a href="addReseller.php" class="maintx">Add Reseller</a></td>
<td height="20"  class="linkwhite" valign="middle" align="center"><a href="viewSelectReseller.php" class="maintx">View / Modify Reseller</a></td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<?php
	}

if ($display_stat_wait == true){
?>
<div id="hidewait" align="center"><br><img SRC="<?=$tmpl_dir?>/images/stats_wait.gif" width="355" height="33"></div>
<?php */ } ?>
