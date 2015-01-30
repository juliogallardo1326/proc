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
// atmverification.php:	The admin page functions for viewing the company transactions as a summary. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude = "bank";
include 'includes/header.php';


require_once( '../includes/function.php');

$count_num = isset($HTTP_POST_VARS['count_id'])?$HTTP_POST_VARS['count_id']:"";
$return_bank_resultarray1="";
if($count_num !="") {
?>
	<br>
	<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">ATM Verification</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5"><br>
	 <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">
	 <tr>
	 <td  bgcolor="#CCCCCC"><span class="subhd">Trans. id</span></td>
     <td bgcolor="#CCCCCC"><span class="subhd">First name</span></td>
     <td bgcolor="#CCCCCC"><span class="subhd">Last name</span></td>
	 <td bgcolor="#CCCCCC"><span class="subhd">Transaction</span></td>		 
	 <td bgcolor="#CCCCCC"><span class="subhd">Amount</span></td>		 
	 <td bgcolor="#CCCCCC"><span class="subhd">Account number</span></td>		 
	 <td bgcolor="#CCCCCC"><span class="subhd">Routing code</span></td>		 
 	 <td bgcolor="#CCCCCC"><span class="subhd">ATM Verification result</span></td>		 
	 </tr>
	<?php 
	for($iLoop=0;$iLoop<=$count_num;$iLoop++) {
		$trans_id = isset($HTTP_POST_VARS["chkid$iLoop"])?quote_smart($HTTP_POST_VARS["chkid$iLoop"]):"";
		$fname = isset($HTTP_POST_VARS["first_name$iLoop"])?quote_smart($HTTP_POST_VARS["first_name$iLoop"]):"";
		$lname = isset($HTTP_POST_VARS["last_name$iLoop"])?quote_smart($HTTP_POST_VARS["last_name$iLoop"]):"";
		$transtype = isset($HTTP_POST_VARS["trans_type$iLoop"])?quote_smart($HTTP_POST_VARS["trans_type$iLoop"]):"";
		$tot_amount = isset($HTTP_POST_VARS["total_amt$iLoop"])?quote_smart($HTTP_POST_VARS["total_amt$iLoop"]):"";
		$account_no = isset($HTTP_POST_VARS["account_numb$iLoop"])?quote_smart($HTTP_POST_VARS["account_numb$iLoop"]):"";
		$account_type = isset($HTTP_POST_VARS["account_type$iLoop"])?quote_smart($HTTP_POST_VARS["account_type$iLoop"]):"";
		$bank_routing_no = isset($HTTP_POST_VARS["routing_code$iLoop"])?quote_smart($HTTP_POST_VARS["routing_code$iLoop"]):"";
		if($trans_id != "") {
			$return_bank_result = func_bank_integration_result($fname,$lname,$tot_amount,$account_type,$account_no,$bank_routing_no);
			$return_bank_resultarray = split(",",$return_bank_result);
			if($return_bank_resultarray[0] =="A") {
				$return_bank_resultarray1="Approved";
			}else if($return_bank_resultarray[0]=="D") {
				$return_bank_resultarray1="Declined";
			}else {
				$return_bank_resultarray1="Error";
			}
?>			
			<tr>
			<td bgcolor="#E2E2E2" height="30"><font size="1" face="Verdana" ><?=$trans_id?>&nbsp;</font></td>
			<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$fname; ?>&nbsp;</font></td>
			<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$lname; ?>&nbsp;</font></td>
			<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$transtype; ?>&nbsp;</font></td>
			<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$tot_amount; ?>&nbsp;</font></td>
			<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$account_no; ?>&nbsp;</font></td>
			<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$bank_routing_no; ?>&nbsp;</font></td>
			<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$return_bank_resultarray1; ?>&nbsp;</font></td>
			</tr>
<?php   }
	}
?>
	<tr>
          <td  height="40" colspan="10" align="center"><a href="transactionverification.php"><img SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;</td>
</tr>
	</table>							
	</td>
	</tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table>

<?php 
}
?>