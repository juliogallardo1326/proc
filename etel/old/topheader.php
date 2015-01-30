<?php
if (!isset($headerInclude)) {
	$headerInclude = "blank";
}
if ($headerInclude=="blank") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td width="100%" height="20" align="left">&nbsp;</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->

<?php
}
if($headerInclude=="home"){
	if(isset($_SESSION["sessionlogin"]))
	{
	
	?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="225" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/cheque.php" class="maintx">Check</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/creditcard.php" class="maintx">Credit Card</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/batchuploads.php" class="maintx">Batch Processing</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?
	}
	if(isset($_SESSION["sessionCompanyUser"]))
	{ ?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="125" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td  height="20" class="linkblack" valign="middle" align="center"><a href="includes/cheque.php" class="maintx">Check</a></td>
		<td  height="20" class="linkwhite" valign="middle" align="center"><a href="includes/creditcard.php" class="maintx">Credit Card</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?php
	}
	if(isset($_SESSION["sessionService"]) || isset($_SESSION["sessionServiceUser"]))
	{ ?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td  height="20" valign="middle" align="center">&nbsp;<?=$_SESSION["sessionactivity_type"]?></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?php
	}
}
if($headerInclude=="rebillhome"){	
	?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="250" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td  height="20" class="linkblack" valign="middle" align="center"><a href="includes/rebill_creditcard.php" class="maintx">Add New</a></td>
		<td  height="20" class="blackrgt" valign="middle" align="center"><a href="includes/viewRebillingDetails.php?Type=Edit" class="maintx">Edit Details</a></td>
		<td  height="20" class="linkwhite" valign="middle" align="center"><a href="includes/viewRebillingDetails.php?Type=View" class="maintx">View Details</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?
}
?>
<?php
if($headerInclude=="profile"){	
	?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
	    <td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
<?php
if(!(isset($_SESSION["sessionactivity_type"]))) {
	if($_SESSION["sessionlogin_type"] == "tele") { ?>
		<table width="520" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/useraccount.php" class="maintx">Change Password</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/companyEdit.php" class="maintx">View Profile</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/integrate_active.php" class="maintx">Integration Guide</a></td>
<!--		<td height="20" class="blackrgt" valign="middle" align="center"><a href="viewuploads.php" class="maintx">Uploaded Documents</a></td> -->
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/telescript.php" class="maintx">Create Script</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/showscript_blank.php" class="maintx">View Script</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/ordermanagement_blank.php" class="maintx">Users</a></td>
		</tr>
		</table>
<?php   }else {?>		
		<table width="380" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/useraccount.php" class="maintx">Change Password</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/companyEdit.php" class="maintx">View Profile</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/addcompany_3vtusers.php" class="maintx">User</a></td>
		</tr>
		</table>
<?php    } 
} else {
		if($_SESSION["sessionlogin_type"] == "tele") { ?>
		
		<table width="470" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
	<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/useraccount.php" class="maintx">Change Password</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/companyEdit.php" class="maintx">Edit Profile</a></td>
<!--		<td height="20" class="blackrgt" valign="middle" align="center"><a href="integrate.php" class="maintx">Integration Details</a></td>
-->		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/viewuploads.php" class="maintx">Uploaded Documents</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/telescript.php" class="maintx">Create Script</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/showscript_blank.php" class="maintx">View Script</a></td>
		</tr>
		</table>
<?php   }else {?>		
		<table  width="380" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/useraccount.php" class="maintx">Change Password</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/companyEdit.php" class="maintx">Edit Profile</a></td>
<!--		<td height="20" class="blackrgt" valign="middle" align="center"><a href="integrate.php" class="maintx">Integration Details</a></td>
-->		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/viewuploads.php" class="maintx">Uploaded Documents</a></td>
		</tr>
		</table>
<?php    } 
 }
?>
	</td>
	</tr>
	</table>
		<!--submenu ends-->
<?
}
?>

<?php
if($headerInclude=="reports"){	
	?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="205" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
		  <td  height="20" align="center" valign="middle" class="linkblack"><a href="includes/ledger.php" class="maintx">Ledger</a></td>
		 <!--<td  height="20" align="center" valign="middle" class="blackrgt"><a href="invoice.php" class="maintx">Invoice</a></td>-->
		  <td  height="20" align="center" valign="middle" class="linkwhite"><a href="includes/projectedsettlement.php" class="maintx">Projected 1
            Settlement</a></td>
			  <td  height="20" align="center" valign="middle" class="linkwhite"><a href="includes/quickstats.php" class="maintx">Quick Stats </a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?
}
?>
<?php
if($headerInclude=="transactions"){	
	?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<?php if($_SESSION["sessionlogin_type"] == "tele") { ?>
		
      <table width="573" border="0" cellpadding="0" cellspacing="0" height="10">
        <tr>
			<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/report.php?period=p" class="maintx">Transactions</a></td>
			<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/VT_blank.php" class="maintx">Virtual Terminal</a></td>
			<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/completeAccounting.php" class="maintx">Complete Accounting</a></td>
			<!--td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/rebillinglist.php" class="maintx">Recurring Transactions</a></td-->
			
          <td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/recurringTransaction.php" class="maintx">Recurring 
            Section</a></td>
		</tr>
		</table>
		<?php }else{ ?>
		
      <table width="406" border="0" cellpadding="0" cellspacing="0" height="10">
        <tr>
			
          <td  height="20" align="center" valign="middle" class="linkblack"><a href="includes/report.php?period=p" class="maintx">Transactions</a></td>
			
          <td  height="20" align="center" valign="middle" class="blackrgt"><a href="includes/addwebsiteuser.php" class="maintx">Websites</a></td>
			 
          <!--td  height="20" align="center" valign="middle" class="blackrgt"><a href="includes/rebillinglist.php" class="maintx">Recurring 
            Transactions</a></td-->
          <td  height="20" align="center" valign="middle" class="linkwhite"><a href="includes/recurringTransaction.php" class="maintx">Recurring 
            Section</a></td>
		</tr>
		</table>
		<?php }?>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?
}

if($headerInclude =="ordermanagement") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		
		
    <td  height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="150" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td  height="20" class="linkblack" valign="middle" align="center"><a href="includes/addtsruser.php" class="maintx">TSR</a></td>
		<td  height="20" class="linkwhite" valign="middle" align="center"><a href="includes/addcallcenteruser.php" class="maintx">Call Center</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}

if($headerInclude =="sessionCompanyUser") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
    <td  height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="150" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td  height="20" class="linkblack" valign="middle" align="center"><a href="includes/cheque.php" class="maintx">Check</a></td>
		<td  height="20" class="linkwhite" valign="middle" align="center"><a href="includes/creditcard.php" class="maintx">Credit Card</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}

if($headerInclude =="customerservice") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="150" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td  height="20" class="linkblack" valign="middle" align="center"><a href="includes/report_custom.php" class="maintx">Found Calls</a></td>
		<td  height="20" class="linkwhite" valign="middle" align="center"><a href="includes/callback.php" class="maintx">Call Back</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}?>
<?php
if($headerInclude=="sessionService"){	
	?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="185" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td  height="20" valign="middle" align="center">&nbsp;</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?
}

if($headerInclude == "voicesystem") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="130" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/cheque.php" class="maintx">Check</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/creditcard.php" class="maintx">Credit Card</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}

if($headerInclude == "script") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="130" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/showscript.php?type=check" class="maintx">Check</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/showscript.php?type=credit" class="maintx">Credit Card</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}
if($headerInclude == "testMode") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="<?= $_SESSION["sessionlogin_type"] == "tele" ? 530 : 430 ?>" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/merchantApplication.php" class="maintx">Merchant Application</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/uploadDocuments.php" class="maintx">Upload Documents</a></td>
		<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/merchantContract.php" class="maintx">Merchant Contract</a></td>
		<? if($_SESSION["sessionlogin_type"] == "tele") {
		?>
			<td height="20" class="blackrgt" valign="middle" align="center"><a href="includes/integrate.php?type=testMode" class="maintx">Integration Guide</a></td>
			<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/verification_blank.php?type=credit" class="maintx">Verification Script</a></td>
		<? } else {
		?>
			<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/integrate.php?type=testMode" class="maintx">Integration Guide</a></td>
		<? }
		?>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}
if($headerInclude == "verification") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="150" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/telescript.php?type=testMode" class="maintx">Create Script</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/showscript_blank.php?type=testMode" class="maintx">View Script</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}
if($headerInclude == "callcenter") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="310" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/addcallcenteruser.php" class="maintx">Add Call Center user</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/editcallcenteruserview.php" class="maintx">View / Modify Call Center user</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}

if($headerInclude == "tsr") {
?>
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="includes/images/menubtmbg.gif"><img alt="" src="includes/images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="240" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td height="20" class="linkblack" valign="middle" align="center"><a href="includes/addtsruser.php" class="maintx">Add TSR user</a></td>
		<td height="20" class="linkwhite" valign="middle" align="center"><a href="includes/edittsruser.php" class="maintx">View / Modify TSR user</a></td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
<?	
}
?>

