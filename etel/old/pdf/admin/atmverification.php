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
$qry_company_type="";
$qry_select_user="";
$strCompanyCondition="";
$querycc= "";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!=""){ 
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$str_type =(isset($HTTP_POST_VARS['type'])?quote_smart($HTTP_POST_VARS['type']):"");
// $crorcq = (isset($HTTP_POST_VARS['crorcq'])?quote_smart($HTTP_POST_VARS['crorcq']):"");
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
$companyname = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"";
$set_to_billdate = isset($HTTP_POST_VARS['set_to_billdate'])?quote_smart($HTTP_POST_VARS['set_to_billdate']):"";
$yet_to_be_passed = isset($HTTP_POST_VARS['yet_to_be_passed'])?quote_smart($HTTP_POST_VARS['yet_to_be_passed']):"";
$period = $HTTP_POST_VARS['period'];
/*
if($crorcq =="") {
	$crorcq = $HTTP_GET_VARS['crorcq'];
}
*/
if($str_type =="") {
	$str_type = (isset($HTTP_GET_VARS['type'])?quote_smart($HTTP_GET_VARS['type']):"");
}
if($companyname =="") {
	$companyname = $HTTP_GET_VARS['companyname'];
}
if($period =="") {
	$period = $HTTP_GET_VARS['period'];
}

if(!$companyname){
	$outhtml="y";
	$msgtodisplay="Select a Company";
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();
}
/*	
if($crorcq){
	  if($crorcq =="A"){
			  $querycc="";
	  } elseif($crorcq == "C") {
			if($str_type == "A") {
				  $querycc="and checkorcard='C'";
			} elseif($str_type == "S") {
				  $querycc="and checkorcard='C' and accounttype='savings'";
			} elseif($str_type == "C") {
				  $querycc="and checkorcard='C' and accounttype='checking'";
			}
	  } elseif($crorcq =="H") {
			if($str_type == "A") {
				  $querycc="and checkorcard='H'";
			} elseif($str_type == "M") {
				  $querycc="and checkorcard='H' and cardtype='Master'";
			} elseif($str_type == "V") {
				  $querycc="and checkorcard='H' and cardtype='Visa'";
			}
	  } else {
			$querycc= "";
	  }
}
*/ 
	if($set_to_billdate =="Yes") {
		$set_date_range = "billingDate";
	} else {
		$set_date_range = "transactionDate";
	
	}
	
	if($yet_to_be_passed =="Yes") {
		$qrt_concat_str = " and passStatus='PA' and status ='P' ";
	} else {
		$qrt_concat_str = " and passStatus='PE' and status ='P' ";
	}
	
	if(!$period){
		$i_from_day = date("d");
		$i_from_month = date("m");
		$i_from_year = date("Y");
		$i_to_day = date("d");
		$i_to_month = date("m");
		$i_to_year = date("Y");
		$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
		$dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";
		$querycc="and checkorcard='C'";
		$querystr="SELECT a.userId,name,surname,checkorcard,amount,bankname,bankroutingcode,accounttype,bankaccountnumber,cardtype,voiceAuthorizationno,b.companyname,transactionId FROM cs_transactiondetails as a, cs_companydetails as b WHERE ";
	   $querystr.=" gateway_id = -1 and a.userid = b.userid and $set_date_range >= '$dateToEnter' and $set_date_range <= '$dateToEnter1' $qrt_concat_str $querycc"; // GROUP BY checkorcard, STATUS , cancelstatus ORDER BY checkorcard"; 
	}
	
	
	if($period =="p" ){
	  $dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
	  $dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";
		$querycc="and checkorcard='C'";
	   $querystr="SELECT a.userId,name,surname,checkorcard,amount,bankaccountnumber,bankname,bankroutingcode,accounttype,cardtype,voiceAuthorizationno,b.companyname,transactionId FROM cs_transactiondetails as a, cs_companydetails as b WHERE ";
	   $querystr.=" gateway_id = -1 and a.userid = b.userid and $set_date_range >= '$dateToEnter' and $set_date_range <= '$dateToEnter1' $qrt_concat_str $querycc"; // GROUP BY checkorcard, STATUS , cancelstatus ORDER BY checkorcard"; 
	}

	if($companytype=="AC"){
		if ($companytrans_type == "A") {
			$qry_company_type=" and b.activeuser=1";
		} else {
			$qry_company_type=" and b.activeuser=1 and b.transaction_type = '$companytrans_type'";
		}
	} else if($companytype=="NC") {
		if ($companytrans_type == "A") {
			$qry_company_type=" and b.activeuser=0";
		} else {
			$qry_company_type=" and b.activeuser=0 and b.transaction_type = '$companytrans_type'";
		}
	} else if($companytype=="RE") {
		if ($companytrans_type == "A") {
			$qry_company_type=" and b.reseller_id <> ''";
		} else {
			$qry_company_type=" and b.reseller_id <> '' and b.transaction_type = '$companytrans_type'";
		}
	} else if($companytype=="ET") {
		if ($companytrans_type == "A") {
			$qry_company_type=" and b.reseller_id is null";
		} else {
			$qry_company_type=" and b.reseller_id is null and b.transaction_type = '$companytrans_type'";
		}
	} else {
		if ($companytrans_type == "A") {
			$qry_company_type="";
		} else {
			$qry_company_type=" and b.transaction_type = '$companytrans_type'";
		}
	}
		
		if($companyname[0]!="A"){
		   if($companyname)
		   {
			   $str_company_ids = "";
			   for($i_loop=0;$i_loop<count($companyname);$i_loop++)
			   {
					if($strCompanyCondition == ""){
						$strCompanyCondition .= " a.userid = $companyname[$i_loop]";
					}else{
						$strCompanyCondition .= " or a.userid = $companyname[$i_loop]";
					}	
			   }
		   }
		} 

	if($strCompanyCondition=="") {
		$qrt_select_transaction =  $querystr.$qry_company_type;
	}else {
		$qrt_select_transaction =  $querystr.$qry_company_type. " and ($strCompanyCondition)" ;
	}
	//print $qrt_select_transaction;
	if(!($sql_select_result = mysql_query($qrt_select_transaction))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}else {
		if(mysql_num_rows($sql_select_result)==0) {
			$Messagedata="No transaction for ATM Verification.";		
			$outhtml="y";
			message($Messagedata,$outhtml,$headerInclude);					
			exit();		
		}
	}
?>
<script>
function validation() {
	if(document.FrmatmVerify.count_id.value != "") {
		var countval;
		 countval = document.FrmatmVerify.count_id.value;
		for(var i=1;i<=countval;i++) {
			if(eval("document.FrmatmVerify.chkid"+i+".checked")) {
				iflag = 1;
				break;
			} else {
				iflag = 0;
			}
		}
		if(iflag==0) {
			alert("Please select the transactions send to bank.");
			return false;
		}else {
			return true;
		}
	}
}
function func_SelectAll() {
	if(document.FrmatmVerify.count_id.value != "") {
			var countval;
			 countval = document.FrmatmVerify.count_id.value;
			 if(document.FrmatmVerify.selectall.checked) {
				for(var i=1;i<=countval;i++) {
					if(eval("document.FrmatmVerify.chkid"+i+".checked")) {
						iflag = 1;
					} else {
					eval("document.FrmatmVerify.chkid"+i).checked=true
					}
				}
			} else {
				for(var i=1;i<=countval;i++) {
					eval("document.FrmatmVerify.chkid"+i).checked=false
				}
			}
	}
}
</script>
	<br>
	<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">ATM Verification</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<form name="FrmatmVerify" action="submitbank.php" method="post" onSubmit="javascript: return validation();">
	<tr>
	<td class="lgnbd" colspan="5"><br>
	  <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">
	 <tr><td bgcolor="#CCCCCC" height="30"><span class="subhd"><input type="checkbox" name="selectall" value="yes" onclick="func_SelectAll();"></span></td>
	 <td  bgcolor="#CCCCCC"><span class="subhd">Voice authid.</span></td>
  	 <td bgcolor="#CCCCCC"><span class="subhd">Company name</span></td>		 	 	 
     <td bgcolor="#CCCCCC"><span class="subhd">First name</span></td>
     <td bgcolor="#CCCCCC"><span class="subhd">Last name</span></td>
	 <td bgcolor="#CCCCCC"><span class="subhd">Transaction</span></td>		 
	 <td bgcolor="#CCCCCC"><span class="subhd">Amount</span></td>		 
	 <td bgcolor="#CCCCCC"><span class="subhd">Account/Card number</span></td>		 
	 <td bgcolor="#CCCCCC"><span class="subhd">Bank name</span></td>		 
	 <td bgcolor="#CCCCCC"><span class="subhd">Routing code</span></td>		 
	 </tr>
	
	<?	$i=0;				
		while($show_select_result = mysql_fetch_array($sql_select_result)) {
		$i=$i+1;
		if($show_select_result[3]=="C"){
			$trans_type ="Check";
		}else{
			$trans_type ="Credit card";
		}
		?>
	 
	<tr>
	<td bgcolor="#E2E2E2" height="30"><font size="1" face="Verdana" ><input type="checkbox" name="chkid<?=$i?>" value="<?=$show_select_result[12]?>"><?=$i; ?>&nbsp;</font></td>
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[10]; ?>&nbsp;</font></td>
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[11]; ?>&nbsp;</font></td>
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[1]; ?>&nbsp;</font></td>
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[2]; ?>&nbsp;</font></td>
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$trans_type; ?>&nbsp;</font></td>		 
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[4]; ?>&nbsp;</font></td>
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[5]; ?>&nbsp;</font></td>
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[6]; ?>&nbsp;</font></td>
	<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[7]; ?>&nbsp;</font></td>
	</tr>
	<input type="hidden" name="trans_id<?=$i?>" value="<?=$show_select_result[12]?>">
	<input type="hidden" name="first_name<?=$i?>" value="<?=$show_select_result[1]?>">
	<input type="hidden" name="last_name<?=$i?>" value="<?=$show_select_result[2]?>">
	<input type="hidden" name="trans_type<?=$i?>" value="<?=$trans_type?>">
	<input type="hidden" name="total_amt<?=$i?>" value="<?=$show_select_result[4]; ?>">
	<input type="hidden" name="account_numb<?=$i?>" value="<?=$show_select_result[5]; ?>">
	<input type="hidden" name="account_type<?=$i?>" value="<?=$show_select_result[8]; ?>">
	<input type="hidden" name="routing_code<?=$i?>" value="<?=$show_select_result[7]; ?>">
	
	<?php
	}				
	?>
	<input type="hidden" name="count_id" value="<?=$i?>">
	<tr><td  height="40" colspan="10" align="center"><a href="transactionverification.php"><img SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" SRC="<?=$tmpl_dir?>/images/atm_verify.gif"></td></tr>
	</table>							
	</td>
	</tr>
	</form>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table>
<?
}
?>