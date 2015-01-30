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
$headerInclude = "mail";
include 'includes/header.php';


require_once( '../includes/function.php');
include 'includes/mailbody_replytemplate.php';

$qry_company_type="";
$qry_select_user="";
$strCompanyCondition="";
$querycc= "";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_email_content = func_getecommerce_mailbody();
$b_mail = false;
if($sessionAdmin!="")
{ 
?>
	
		<br>
		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Ecommerce letter - Status </span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<form name="FrmatmVerify" action="submitbank.php" method="post" onSubmit="javascript: return validation();">
		<tr>
		<td class="lgnbd" colspan="5"><br>
		  <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">
		 <tr><td bgcolor="#CCCCCC" height="30"><span class="subhd"></span></td>
		 <td  bgcolor="#CCCCCC"><span class="subhd">Transaction id</span></td>
		 <td bgcolor="#CCCCCC"><span class="subhd">First name</span></td>
		 <td bgcolor="#CCCCCC"><span class="subhd">Transaction</span></td>		 
		 <td bgcolor="#CCCCCC"><span class="subhd">Amount</span></td>		 
		 <td bgcolor="#CCCCCC"><span class="subhd">Cheque/Card number</span></td>		 
		 <td bgcolor="#CCCCCC"><span class="subhd">Date</span></td>		 
		 <td bgcolor="#CCCCCC"><span class="subhd">Company name</span></td>	
		 <td bgcolor="#CCCCCC"><span class="subhd">Mail status</span></td>		
		 </tr>
		
		<?
		$i_count = isset($HTTP_POST_VARS["count_id"])?$HTTP_POST_VARS["count_id"]:"0";
		//echo $i_count;
		if($i_count != "")	
		{
		//$i_count = intval($i_count);
		}
		for($i_loop=1;$i_loop<=$i_count;$i_loop++)	
		{
		$i_trans_id =isset($HTTP_POST_VARS["chkid".$i_loop])?$HTTP_POST_VARS["chkid".$i_loop]:"";
		
		$first_name = isset($HTTP_POST_VARS["first_name".$i_loop])?$HTTP_POST_VARS["first_name".$i_loop]:"";

		$last_name = isset($HTTP_POST_VARS["last_name".$i_loop])?$HTTP_POST_VARS["last_name".$i_loop]:"";

		$trans_type = isset($HTTP_POST_VARS["trans_type".$i_loop])?$HTTP_POST_VARS["trans_type".$i_loop]:"";

		$total_amt = isset($HTTP_POST_VARS["total_amt".$i_loop])?$HTTP_POST_VARS["total_amt".$i_loop]:"";

		$account_numb = isset($HTTP_POST_VARS["account_numb".$i_loop])?$HTTP_POST_VARS["account_numb".$i_loop]:"";
		$tran_date = isset($HTTP_POST_VARS["tran_date".$i_loop])?$HTTP_POST_VARS["tran_date".$i_loop]:"";
		
		$company_name = isset($HTTP_POST_VARS["company_name".$i_loop])?$HTTP_POST_VARS["company_name".$i_loop]:"";

		$str_select_user = "select  B.billingdescriptor,B.address,B.city,B.state,B.zipcode,B.email, A.email from ";
		$str_select_user .="  cs_transactiondetails A, cs_companydetails B where ";
		$str_select_user .= " B.userId= A.userId  and A.transactionId =". $i_trans_id ;

		//echo $i_trans_id . "fdfdfdf";

		if (trim($i_trans_id) != "")
		{
			//echo $str_select_user;
		$sql_select_result = mysql_query($str_select_user);
		
		$billingdescriptor ="";
		$address ="";
		$city ="";
		$state = "";
		$zipcode = "";
		$fromaddress  =$_SESSION['gw_emails_sales'];
		$to_id = "";

		if($show_select_result = mysql_fetch_array($sql_select_result)) 
		{
			$billingdescriptor = $show_select_result[0];
			$address = $show_select_result[1];
			$city = $show_select_result[2];
			$state = $show_select_result[3];
			$zipcode =$show_select_result[4];
		//	$fromaddress  = $show_select_result[5];
			$to_id = $show_select_result[6];
		}
		$str_email_content = str_replace("[customername]", $first_name." ".$last_name, $str_email_content );

		$str_email_content = str_replace("[companyname]", $company_name, $str_email_content );

		$str_email_content = str_replace("[amount]", $total_amt, $str_email_content );

		$str_email_content = str_replace("[billingdescriptor]", $billingdescriptor, $str_email_content );

		$str_email_content = str_replace("[companyemailaddress]", $fromaddress, $str_email_content );
		$str_email_content = str_replace("[chargeamount]", $total_amt, $str_email_content );
		$str_email_content = str_replace("[cardtype]", $trans_type, $str_email_content );
		$str_email_content = str_replace("[name]", $first_name, $str_email_content );
		$str_email_content = str_replace("[address]", $address, $str_email_content );
		$str_email_content = str_replace("[city]", $city, $str_email_content );
		$str_email_content = str_replace("[state]", $state, $str_email_content );
		$str_email_content = str_replace("[zip]", $zipcode, $str_email_content );
		$str_email_content = str_replace("[ccnumber]", substr($account_numb,strlen($account_numb)-4,4) , $str_email_content);

		
		//echo $str_email_content;
		$b_mail = func_send_mail($fromaddress,$to_id,"Ecommerce letter",$str_email_content);
		if($b_mail){
			$mail_status = "Mail Send";
		}else {
			$mail_status = "Failed";
		}
		?>
		 
		<tr>
		<td bgcolor="#E2E2E2" height="30"><font size="1" face="Verdana" ><?=$i_loop; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$i_trans_id; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$first_name; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$trans_type; ?>&nbsp;</font></td>		 
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$total_amt; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$account_numb; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$tran_date; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$company_name; ?>&nbsp;</font></td>
		<td bgcolor="#E2E2E2"><font size="1" face="Verdana" ><?=$mail_status; ?>&nbsp;</font></td>
		</tr>		
		
		<?php
			}
			}				
		?>
		
		<tr><td  height="40" colspan="10" align="center"><a href="ecommerce_letter.php"><img SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a></td></tr>
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