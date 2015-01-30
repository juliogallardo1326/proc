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
$headerInclude = "autoLetters";
include 'includes/header.php';


require_once( '../includes/function.php');

$qry_company_type="";
$qry_select_user="";
$strCompanyCondition="";
$querycc= "";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";

if($sessionAdmin!="")
{ 

	$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
	$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
	$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
	$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
	$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
	$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

	$str_type =(isset($HTTP_POST_VARS['type'])?quote_smart($HTTP_POST_VARS['type']):"");

	$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
	$companyname = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");
	$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"";
	


	if(!$companyname)
	{
		$outhtml="y";
		$msgtodisplay="Select a Company";
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();
	}
	else
	{
		
			/*$i_from_day = date("d");
			$i_from_month = date("m");
			$i_from_year = date("Y");
			$i_to_day = date("d");
			$i_to_month = date("m");
			$i_to_year = date("Y");*/
			$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
			$dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";
			
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
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Ecommerce letter - Transaction list</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<form name="FrmatmVerify" action="send_ecommerce_letter.php" method="post" onSubmit="javascript: return validation();">
		<tr>
		<td class="lgnbd" colspan="5"><br>
		  <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">
		 <tr><td bgcolor="#CCCCCC" height="30"><span class="subhd"><input type="checkbox" name="selectall" value="yes" onclick="func_SelectAll();"></span></td>
		 <td  bgcolor="#CCCCCC"><span class="subhd">Transaction id</span></td>
		 <td bgcolor="#CCCCCC"><span class="subhd">First name</span></td>
		 <td bgcolor="#CCCCCC"><span class="subhd">Last name</span></td>
		 <td bgcolor="#CCCCCC"><span class="subhd">Transaction</span></td>		 
		 <td bgcolor="#CCCCCC"><span class="subhd">Amount</span></td>		 
		 <td bgcolor="#CCCCCC"><span class="subhd">Cheque/Card number</span></td>		 
		 <td bgcolor="#CCCCCC"><span class="subhd">Date</span></td>		 
		 <td bgcolor="#CCCCCC"><span class="subhd">Company name</span></td>		 
		 </tr>
		
		<?
			$i=0;	
			for($i_loop=0;$i_loop<count($companyname);$i_loop++)	
			{

			$querystr= "select A.transactionId, A.name, A.surname, A.checkorcard, A.amount, A.CCnumber, A.transactionDate,  B.companyname from  cs_transactiondetails A, cs_companydetails B where A.userId = B.userId and ";
			$b_sub= false;
			if($companyname[0] == "A")
			{
				if($companytype == "AC"){
					$querystr .= " B.activeuser = 1 " ;
					$b_sub= true;	
				}
				elseif($companytype == "NC"){
					$querystr .= " B.activeuser = 0 " ;
					$b_sub= true;	
				}
				else{
			
				}
				
				if($b_sub){
						$querystr .= " and ";
					}

				if ($companytrans_type=="A"){					
					
				}else{
					
					if($companytype == "A")	{
						$querystr .= " B.transaction_type = '" . $companytrans_type. "' and" ;
					}
					else{
						$querystr .= " B.transaction_type = '" . $companytrans_type. "' and" ;
					}
				}
				//$b_sub = true;
			}
			else
			{
				
				$querystr .= " A.userId=".$companyname[$i_loop]. " and";
			}
				$querystr .=  " (transactionDate >= '" .$dateToEnter . "' and transactionDate <= '" . $dateToEnter1. "' )";

			//echo $querystr;

				
			$sql_select_result = mysql_query($querystr);
			while($show_select_result = mysql_fetch_array($sql_select_result)) {
			$i=$i+1;
			if($show_select_result[3]=="C"){
				$trans_type ="Check";
			}else{
				$trans_type ="Credit card";
			}
			?>
		 
		<tr>
		<td bgcolor="#E2E2E2" height="30"><font size="1" face="Verdana" ><input type="checkbox" name="chkid<?=$i?>" value="<?=$show_select_result[0]?>"><?=$i; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[0]; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[1]; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[2]; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$trans_type; ?>&nbsp;</font></td>		 
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[4]; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[5]; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[6]; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$show_select_result[7]; ?>&nbsp;</font></td>
		</tr>

		<input type="hidden" name="trans_id<?=$i?>" value="<?=$show_select_result[0]?>">
		<input type="hidden" name="first_name<?=$i?>" value="<?=$show_select_result[1]?>">
		<input type="hidden" name="last_name<?=$i?>" value="<?=$show_select_result[2]?>">
		<input type="hidden" name="trans_type<?=$i?>" value="<?=$trans_type?>">
		<input type="hidden" name="total_amt<?=$i?>" value="<?=$show_select_result[4]; ?>">
		<input type="hidden" name="account_numb<?=$i?>" value="<?=$show_select_result[5]; ?>">
		<input type="hidden" name="tran_date<?=$i?>" value="<?=$show_select_result[6]; ?>">
		<input type="hidden" name="company_name<?=$i?>" value="<?=$show_select_result[7]; ?>">
		
		<?php
				}
			}				
		?>

		<input type="hidden" name="count_id" value="<?=$i?>">
		<tr><td  height="40" colspan="10" align="center"><a href="ecommerce_letter.php"><img SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a>&nbsp;&nbsp;<input type="image" SRC="<?=$tmpl_dir?>/images/send.jpg"></td></tr>
		</table>							
		</td>
		</tr>
		</form>
		<tr>
		<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
		</tr>
		</table><br>
<?
}
include("includes/footer.php");

?>