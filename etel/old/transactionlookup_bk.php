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

$check_card_no= (isset($HTTP_POST_VARS['checkcardno'])?quote_smart($HTTP_POST_VARS['checkcardno']):"");
$voice_auth_id = (isset($HTTP_POST_VARS['voiceauthno'])?quote_smart($HTTP_POST_VARS['voiceauthno']):"");
$telephone_no = (isset($HTTP_POST_VARS['telephoneno'])?quote_smart($HTTP_POST_VARS['telephoneno']):"");
$transaction_no = (isset($HTTP_POST_VARS['transactionno'])?quote_smart($HTTP_POST_VARS['transactionno']):"");
$whereqry ="";
$msgtodisplay="";
$select_trans_details ="";
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
		$select_trans_details ="Select a.transactionId,b.companyname,a.name,a.surname,a.checkorcard,a.amount,a.transactionDate,a.voiceAuthorizationno,a.CCnumber,a.cvv,a.cardtype,a.validupto,a.billingDate,a.shippingTrackingno,a.socialSecurity,a.driversLicense,a.checktype,a.accounttype,a.bankname,a.bankroutingcode,a.bankaccountnumber from cs_transactiondetails  as a,cs_companydetails as b where a.userId=b.userId and a.cancelstatus='N' $whereqry ";
	} else {
		$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'><strong>No transactions were found based on your search.</strong></font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
	}
	//print $select_trans_details;
	if($select_trans_details !="") {
		if(!($qrt_select_run = mysql_query($select_trans_details))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
	}
	 if(mysql_affected_rows()==0) 
	 {
		$msgtodisplay="<table width='370' height='100' align='center' valign='top' style='border:2px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='2' color='red'><strong>No transactions were found based on your search.</strong></font></td></tr><tr><td align='center'><a href='javascript:window.history.back();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
	 } 

	
?>

<body topmargin="0" leftmargin="0" bgcolor="#ffffff"  marginheight="0" marginwidth="0">
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="middle" align="center" bgcolor="#ffffff" width="35%"><img src="images/spacer.gif" width="180" height="46" border="0" alt=""></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" src="images/top1.jpg" width="238" height="63" ><img alt="" border="0" src="images/top2.jpg" width="217" height="63"><img alt="" border="0" src="images/top3.jpg" width="138" height="63"><br>
<img alt="" border="0" src="images/top4.jpg" width="238" height="63"><img  alt="" border="0" src="images/top5.jpg" width="217" height="63"><img alt="" border="0" src="images/top6.jpg" width="138" height="63"></td>
</tr>
</table>
<!-- Sub header-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
<tr>
    <td height="9" align="center" background="images/menutopbg.gif"></td>
</tr>
<tr>
    <td background="images/midbg.gif" align="center">&nbsp;</td>
</tr>
</table>
<!-- sub header -->
<!--submenu starts-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td height="5" background="images/menubtmbg.gif"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
</tr>
<tr>
<td bgcolor="#CCCCCC" height="25" class="blackbd" valign="middle" align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="10">
<tr>
<td width="100%" height="20" align="left">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
<!--submenu ends-->
<form name="FrmTransaction" action="merchantcancel.php" method="post">
<table width="100%" cellpadding="0" cellspacing="0" align="center" height="56%"><tr><td>
<?php
if($msgtodisplay !="") {
	print $msgtodisplay;
} else {
	while($show_select_val = mysql_fetch_array($qrt_select_run)) {
	$trans_id = $show_select_val[0];
	if($show_select_val[4]=="H"){

?>
<table width="600" cellpadding="2" cellspacing="0" style="border:1px solid black" dwcopytype="CopyTableCell" align="center">
<tr align="center" valign="middle" bgcolor="#CCCCCC"> 
<td colspan="2" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Customer 
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
  <tr bgcolor="#CCCCCC"> 
	<td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Payment 
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
	<td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing 
	  Date :</font></td><td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[12];?>
	  </font></td>
  </tr>
  
<tr bgcolor="#CCCCCC"> 
	<td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Shipping 
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
    <tr align="center" valign="middle" bgcolor="#CCCCCC"> 
      <td colspan="2" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Customer 
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
    <tr bgcolor="#CCCCCC"> 
      <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Payment 
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
      <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing 
        Date :</font></td>
      <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $show_select_val[12];?> 
        </font></td>
    </tr>
    <tr bgcolor="#CCCCCC"> 
      <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Bank 
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
    <tr bgcolor="#CCCCCC"> 
      <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Shipping 
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
<tr><td height="30" align="center" valign="bottom"><a href="#" onclick="window.history.back()"><img   src="images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" src="images/canceltransaction.jpg"></td></tr>
<? 
	}
}
?>
</table>
</form>

<?php 
include("includes/footer.php");
?>
</body>
</html>