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
// companyEdit.php:	The  page used to modify the company profile. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$headerInclude="customerservice";	
include 'includes/topheader.php';
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

if($sessionlogin!=""){
	$i_note_id = (isset($HTTP_GET_VARS['noteid'])?Trim($HTTP_GET_VARS['noteid']):"");

	$qry_select="Select A.note_id,A.transaction_id,A.call_date_time,A.service_notes,B.cancelstatus,A.customer_notes,A.solved,B.phonenumber  from  cs_callnotes A,cs_transactiondetails B where B.transactionid = A.transaction_id and B.userid = ".$_SESSION["sessionlogin"]." and A.note_id = ".$i_note_id;
	$show_sql =mysql_query($qry_select,$cnn_cs);	
?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%" height="303">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Edit&nbsp; 
            Call Notes</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>

      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
           <form action="inquiryeditfb.php" method="post" name="editinquiryfrm">
		  <table height="100%" width="70%" cellspacing="0" cellpadding="0" ><tr><td align="left"><?=$invalidlogin?>
		<?
			  if($showval = mysql_fetch_array($show_sql)){ 
			  ?>
				 <input type="hidden" name="username" value="<?=$showval[1]?>"></input>
			  <table width="400" border="0" cellpadding="0"  height="100"><input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
				   <tr><td align="right" valign="center" height="30" width="50%"><font face="verdana" size="2">Transaction Id &nbsp;</font></td><td align="left" height="30" width="50%"><font face="verdana" size="1"><?=$showval[1]?></font></td></tr>
				  <tr><td align="right" valign="center" height="30" width="50%"><font face="verdana" size="2">Phone Number &nbsp;</font></td><td align="left" height="30" width="50%"><font face="verdana" size="1"><?=$showval[7]?>&nbsp;</font></td></tr>
				   <tr><td align="right" valign="center" height="30" width="50%"><font face="verdana" size="2">Call Date Time &nbsp;</font></td><td align="left" height="30" width="50%"><font face="verdana" size="1"><?=$showval[2]?>&nbsp;</font></td></tr>
				 <tr><td align="right" valign="center" height="30" width="50%"><font face="verdana" size="2">Service Notes &nbsp;</font></td><td align="left" height="30" width="50%"><textarea rows="4" cols="20" name="txt_customer_notes" readonly><?=$showval[3]?></textarea></td></tr>
				<tr><td align="right" valign="center" height="30" width="50%"><font face="verdana" size="2">Cancel Status&nbsp;&nbsp;</font></td><td align="left" height="30" width="50%"><font face="verdana" size="1"><?=$showval[4] == "Y" ? "Cancelled" : ""?>&nbsp;</font></td></tr>
				<tr><td align="right" valign="center" height="30" width="50%"><font face="verdana" size="2">Customer notes&nbsp;&nbsp;</font></td><td align="left" height="30" width="50%"><font face="verdana" size="1"><textarea rows="4" cols="20" name="txt_customer_notes"><?=$showval[5]?></textarea></font></td></tr>
				<tr><td align="right" valign="center" height="30" width="50%"><font face="verdana" size="2">Solved &nbsp;</font></td><td align="left" height="30" width="50%"><font face="verdana" size="1"><input type="checkbox" name="chk_solved" value="1" <?=$showval[6] == "1" ? "checked" : ""?>></font></td></tr>
					<input type="hidden" name="noteid" value="<?=$showval[0]?>"></input>
				   <tr><td align="center" valign="center" height="30" colspan="2">
					 <input type="image" id="modifycompany" src="images/submit.jpg"></input></td>
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
</table>
<?
include 'includes/footer.php';
}
?>