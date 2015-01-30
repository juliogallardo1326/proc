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
// companyAdd.php:	The admin page functions for the New Company setup. 
require_once("includes/dbconnection.php");
require_once('includes/function.php');
$trans_type ="";
$whereqry ="";
$msgtodisplay="";
$select_trans_details ="";
$qrt_select_run="";
$cancel="";
$payment_mode = (isset($HTTP_POST_VARS['payment_mode'])?quote_smart($HTTP_POST_VARS['payment_mode']):"");
if ($payment_mode == "check") {
	$check_card_no= (isset($HTTP_POST_VARS['checkno'])?quote_smart($HTTP_POST_VARS['checkno']):"");
	$voice_auth_id = (isset($HTTP_POST_VARS['ch_voiceauthno'])?quote_smart($HTTP_POST_VARS['ch_voiceauthno']):"");
	$telephone_no = (isset($HTTP_POST_VARS['ch_telephoneno'])?quote_smart($HTTP_POST_VARS['ch_telephoneno']):"");
	$transaction_no = (isset($HTTP_POST_VARS['ch_transactionno'])?quote_smart($HTTP_POST_VARS['ch_transactionno']):"");
	$trans_type = "and a.checkorcard='C'";
} else {
	$trans_type = "and a.checkorcard='H'";
	$check_card_no= (isset($HTTP_POST_VARS['cardno'])?quote_smart($HTTP_POST_VARS['cardno']):"");
	$voice_auth_id = (isset($HTTP_POST_VARS['cc_voiceauthno'])?quote_smart($HTTP_POST_VARS['cc_voiceauthno']):"");
	$telephone_no = (isset($HTTP_POST_VARS['cc_telephoneno'])?quote_smart($HTTP_POST_VARS['cc_telephoneno']):"");
	$transaction_no = (isset($HTTP_POST_VARS['cc_transactionno'])?quote_smart($HTTP_POST_VARS['cc_transactionno']):"");
}

	if($transaction_no !="") {
		$whereqry = " and a.transactionId=$transaction_no";
	}
	if($check_card_no !="") {
		$whereqry .= " and a.CCnumber='".etelEnc($check_card_no)."'";
	}
	if($voice_auth_id !="") {
		$whereqry .= " and a.voiceAuthorizationno='$voice_auth_id'";
	}
	if($telephone_no !="") {
		$whereqry .= " and a.phonenumber='$telephone_no'";
	}
	if($whereqry !="") {
		$select_trans_details ="Select a.transactionId,b.companyname,a.name,a.surname,a.checkorcard,a.amount,a.transactionDate,a.voiceAuthorizationno,a.CCnumber,a.cvv,a.cardtype,a.validupto,a.billingDate,a.shippingTrackingno,a.socialSecurity,a.driversLicense,a.checktype,a.accounttype,a.bankname,a.bankroutingcode,a.bankaccountnumber,a.cancelstatus,b.billingdescriptor,a.userId from cs_transactiondetails  as a,cs_companydetails as b where a.userId=b.userId $whereqry $trans_type ";
	} else {
		$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'><strong>No transactions found based on your search.</strong></font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
	}
	if($select_trans_details !="") {
		if(!($qrt_select_run = mysql_query($select_trans_details))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
	 if(mysql_affected_rows()==0) 
	 {
		$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'><strong>No transactions found based on your search.</strong></font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
	 } 
//print $select_trans_details;

?>

<html>
<head>
<title>Merchant Transacton Lookup</title> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<style>
.Button
{
    BORDER-RIGHT: #D4D0C8 1px solid;
    BORDER-TOP: #D4D0C8 1px solid;
    BORDER-LEFT: #D4D0C8 1px solid;
    BORDER-BOTTOM: #D4D0C8 1px solid;
    FONT-SIZE: 8pt;
    FONT-FAMILY: verdana;
    COLOR: white;
	FONT-WEIGHT:bold;
    BACKGROUND-COLOR: #999999 
}
.TextBox
{
font-face:verdana;font-size:10px
}
</style>
</head>
<body topmargin="0" leftmargin="0">
<form name="FrmTransaction" action="merchantcancel.php" method="post">
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1">
			<tr>
				<td class="whitebtbd"><img border="0" src="images/logo_etelegate.gif" width="108" height="43"><img border="0" src="images/cards_tran.gif" width="199" height="23"></td>
			</tr>
		</table>
		<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" bgcolor="#658343" class="blkbd1">
			<tr>
				<td height="15" class="blackbtbd" bgcolor="#4A9FA6"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
			</tr>
		</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="460">
			<tr>
				<td height="25" valign="top" align="center" width="165" bgcolor="#FFFFFF">
					<table border="0" cellpadding="0" width="100%" height="249">
						<tr>
							<td width="99%" bgcolor="#B7D0DD" height="14"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="99%" bgcolor="#85AFBC" height="16"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="99%" bgcolor="#85AFBC" height="18"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
							<td width="1%"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
						</tr>
						<tr>
							<td width="100%" height="178" colspan="2" valign="top"><img border="0" src="images/service_pic.jpg" width="160" height="176"></td>
						</tr>
					</table>
				</td>
				<td height="25" valign="top" align="center" width="429">
				<br>
<?php
if($msgtodisplay !="") {
	print $msgtodisplay;
} else {
	while($show_select_val = mysql_fetch_array($qrt_select_run)) {
	$trans_id = $show_select_val[0];
	$cancel = $show_select_val[21];
	$user_id = $show_select_val[23];
	$crorcq1 = $show_select_val[4];
	if($crorcq1=="H"){

?>
<table width="580" cellpadding="2" cellspacing="0" style="border:1px solid black" dwcopytype="CopyTableCell" align="center">
<tr align="center" valign="middle" bgcolor="#3D8287"> 
<td colspan="2" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Customer 
  Information</strong></font></td>
</tr>
<tr> 
<td width="50%" align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First 
  Name : </font></td>
<td width="50%" valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[2];?>
</font></td>
</tr>
<tr> 
<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
  Name :</font></td>
<td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[3];?>
</font></td>
</tr>
<tr> 
<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Company Name :</font></td>
<td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[1];?>
</font></td>
</tr>
  <tr bgcolor="#3D8287"> 
	<td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Payment 
	  Information</strong></font></td>
  </tr>
  <tr> 
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card 
	  Number :</font></td><td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; <?php print $show_select_val[8]. "-". $show_select_val[9];?>
	</font></td>
  </tr>
  <tr> 
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card Type 
	  : </font></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[10];?></font></td>
  </tr>
  <tr> 
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Expiration 
	  Date :</font></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[11];?></font></td>
  </tr>
<tr> 
<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount 
  of Money :</font><br></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[5];?>
</font></td>
</tr>  
<tr> 
  <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing Descriptor :</font><br></td>
  <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[22];?> 
	</font></td>
</tr>

  <tr> 
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing 
	  Date :</font></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[12];?>
	  </font></td>
  </tr>
  
<tr bgcolor="#3D8287"> 
	<td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Shipping 
	  Information</strong></font></td>
  </tr>
  <tr>
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
	  Authorization # : </font></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[7];?>
	  </font></td>
  </tr>
  <tr> 
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
	  Tracking # : </font></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[13];?>
	  </font></td>
  </tr>
  <tr> 
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
	  Security # : </font></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[14];?>
	  </font></td>
  </tr>
  <tr> 
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
	  License : </font></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[15];?>
	  </font></td>
 </tr></table>
<?php } else {
?>
  <table width="600" cellpadding="2" cellspacing="0" style="border:1px solid black" dwcopytype="CopyTableCell" align="center">
    <tr align="center" valign="middle" bgcolor="#3D8287"> 
      <td colspan="2" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Customer 
        Information</strong></font></td>
    </tr>
    <tr> 
      <td width="50%" align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First 
        Name : </font></td>
      <td width="50%" valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[2];?> </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
        Name :</font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[3];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Company 
        Name :</font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[1];?> 
        </font></td>
    </tr>
    <tr bgcolor="#3D8287"> 
      <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Payment 
        Information</strong></font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Account 
        Type :</font><br></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[17];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Check 
        Number :</font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[8];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Check 
        Type : </font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[16];?></font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount 
        of Money :</font><br></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[5];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing Descriptor :</font><br></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[22];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing 
        Date :</font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[12];?> 
        </font></td>
    </tr>
    <tr bgcolor="#3D8287"> 
      <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Bank 
        Information</strong></font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank 
        Name :</font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[18];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank 
        Routing Code : </font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[19];?></font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank Account Number  :</font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[20];?></font></td>
    </tr>
    <tr bgcolor="#3D8287"> 
      <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Shipping 
        Information</strong></font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
        Authorization # : </font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[7];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
        Tracking # : </font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[13];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
        Security # : </font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[14];?> 
        </font></td>
    </tr>
    <tr> 
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
        License : </font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[15];?> 
        </font></td>
    </tr>
  </table>

<?php 
	}
}
	if($msgtodisplay =="") {
?>
</td></tr>
<input type="hidden" name="tid" value="<?=$trans_id?>">
<input type="hidden" name="cancel" value="Yes">
<input type="hidden" name="user_id" value="$user_id">
<tr><td>&nbsp;</td><td height="30" align="center" valign="bottom"><a href="#" onclick="window.history.back()"><img src="images/back_tran.gif" border="0"></a>&nbsp;&nbsp;
<?php if($cancel!="Y"){ ?>
<input type="image" src="images/canceltransaction_tran.gif">
<?php } ?>
</td></tr>
<? 
	}
}
?>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="20">
			<tr>
				<td height="25" bgcolor="#3D8287"></td>
			</tr>
		</table>
</form>
</body>
</html>