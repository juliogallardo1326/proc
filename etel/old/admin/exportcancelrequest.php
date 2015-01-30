<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// Exportcancelrequest.php:	The admin page functions for selecting the company for adding company user. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
require_once( '../includes/function.php');
include '../includes/function1.php';
$headerInclude="transactions";
include 'includes/header.php';

 ?>
<script>
function funcDownload(){
	document.frmDownload.method="post";
	document.frmDownload.action="downloadcancelrequest.php";
	document.frmDownload.submit();
}
</script>
<?php
$bank_trid ="";
$Transtype = isset($HTTP_POST_VARS['hidtrans_type'])?quote_smart($HTTP_POST_VARS['hidtrans_type']):"";
$companytype = isset($HTTP_POST_VARS['hidcompanymode'])?$HTTP_POST_VARS['hidcompanymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['hidcompanytrans_type'])?quote_smart($HTTP_POST_VARS['hidcompanytrans_type']):"A";
$company_name = isset($HTTP_POST_VARS['hidcompanyname'])?$HTTP_POST_VARS['hidcompanyname']:"";
$str_from_date = isset($HTTP_POST_VARS['hidfromdate'])?$HTTP_POST_VARS['hidfromdate']:"";
$str_to_date = isset($HTTP_POST_VARS['hidtodate'])?$HTTP_POST_VARS['hidtodate']:"";
$chkorcrd = isset($HTTP_POST_VARS['hidchkorcrd'])?$HTTP_POST_VARS['hidchkorcrd']:"";
$bank = isset($HTTP_POST_VARS['hidbank'])?$HTTP_POST_VARS['hidbank']:"";
 $rejected = isset($HTTP_POST_VARS['rejected'])?$HTTP_POST_VARS['rejected']:"";
if($chkorcrd=="C"){$bank="chk";}
$subqry_rej= " and A.admin_approval_for_cancellation='P' ";
if ($rejected=="rejected") {$subqry_rej= " and A.admin_approval_for_cancellation='R' ";}
else if ($rejected=="accepted") {$subqry_rej= " and A.admin_approval_for_cancellation='A' ";}

if($bank=="b")
		{			
			$subqry=" and (A.bank_id=3)";
		}
		else if($bank=="s")
		{			
			$subqry=" and (A.bank_id=9 or A.bank_id=10)";
		}
		else if($bank=="v") 
		{			
			$subqry=" and (A.bank_id=6 or A.bank_id=7 or A.bank_id=8)"; 
		}
		else if($bank=="a")
		{$subqry="and checkorcard='H'";}
		else if  ($bank=="chk")
		{$subqry="and checkorcard='C' "; $bname="";}
$qrt_select_companies ="select distinct userId,companyname from cs_companydetails where 1 order by companyname";
if ($Transtype == "Submit")  {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companytype =="RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companytype =="ET") {
		$qrt_select_subqry = " reseller_id is null";	
	} else {
		$qrt_select_subqry = "";	
	}
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
	} else {
		if($qrt_select_subqry =="") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}

	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where 1 ";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
}
//print($qrt_select_companies);
	
if(!($show_select_sql =mysql_query($qrt_select_companies,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

$header = '"' ."Transaction Id". '"' . "," . '"' . "Cancellation Date". '"' . "," . '"' . "Company Name". '"' . "," . '"' . "Name". '"' . "," . '"' . "Surname". '"' . "," . '"' . "Amount". '"' . "," . '"' . "Status". '"' . "," . '"' . "Check/Card". '"' . "," . '"' ."Transaction Date". '"' . "," . '"' . "Cancel Reason".'"'."," . '"' ."Bank Trans. Id". '"' ."," . '"' ."Currency". '"' .  "\t";
$str_where_condition = "";
	$str_company_ids = "";
	if ($company_name == "A") {
		if ($companytype == "A") {
			if ($companytrans_type == "A") {
				$str_where_condition = "";
			} else {
				$str_where_condition = "where B.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "AC") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where B.activeuser = 1 ";
			} else {
				$str_where_condition = "where B.activeuser = 1 and B.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "NC") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where B.activeuser = 0 ";
			} else {
				$str_where_condition = "where B.activeuser = 0 and B.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "RE") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where B.reseller_id <> '' ";
			} else {
				$str_where_condition = "where B.reseller_id <> '' and B.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "ET") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where B.reseller_id is null ";
			} else {
				$str_where_condition = "where B.reseller_id is null and B.transaction_type = '$companytrans_type' ";
			}
		}
	} else {
//	$company_name = split($company_name,",");
		for ($i = 0; $i < count($company_name); $i++) {
			$str_company_ids .= $company_name.",";
		}
		$str_company_ids = substr($str_company_ids, 0, strlen($str_company_ids) - 3);
		
		$str_where_condition = "where B.userId in ($str_company_ids)";
		
	}
	if ($str_from_date != "")
	{	
		  $qry_select="Select A.transactionid,A.transactionDate,A.cancelstatus,A.checkorcard ,A.status,A.phonenumber,A.cancellationDate ,A.name,A.surname,B.companyname,B.processing_currency ,A.amount,A.reason,A.other,B.processing_currency,B.userId,A.cancel_refer_num,A.bank_id,A.cardtype from cs_transactiondetails A, cs_companydetails B ";
		  $qry_select .= $str_where_condition == "" ? " where 1 and " : $str_where_condition ." and gateway_id = -1 and ";
		 $qry_select .= " A.userId = B.userId and A.cancelstatus = 'Y' ".$subqry_rej.$subqry." and A.transactionDate >= '".$str_from_date."'";
		if($str_to_date != "")
		{
			$qry_select .= " AND A.transactionDate <= '".$str_to_date."'";
		}
	$qry_select .= " Order by B.companyname, A.transactionDate desc";	
			//print $qry_select;
	$rssel_report = mysql_query($qry_select,$cnn_cs);			
	$i_count = mysql_num_rows($rssel_report);			
}								
$data="";								
for($i=1;$i<=$i_count;$i++)
	{
	$rst_field = mysql_fetch_array($rssel_report);
	$file_transactionid= $rst_field["transactionid"];
	//for old trId
	$cancel_refer_num = func_get_value_of_field($cnn_cs,"cs_transactiondetails","cancel_refer_num","transactionId",$file_transactionid );
	$cancel_refer_num="'".$cancel_refer_num."'";
	$file_transactionid = func_get_value_of_field($cnn_cs,"cs_transactiondetails","transactionId","reference_number",$cancel_refer_num );
	//end for old trId
	$file_cancellationDate = $rst_field["cancellationDate"];
	$file_companyname= $rst_field["companyname"];
	$file_name= $rst_field["name"];
	$file_surname= $rst_field["surname"];
	$file_amount= $rst_field["amount"];
	$file_status= $rst_field["status"];
	$bank_id=$rst_field["bank_id"];
	$cardtype=$rst_field["cardtype"];
 	$i_company_id=$rst_field["userId"];
	
	$currency=func_get_cardcurrency($cardtype,$i_company_id,$cnn_cs);
	
	///////////////
$cancel_refer_num=$rst_field["cancel_refer_num"];
$len=strlen($cancel_refer_num);
$old_tr_id= substr($cancel_refer_num,4,$len-6);
if($old_tr_id!=""){
			if($bank_id==3){
			$bank_trid = func_get_value_of_field($cnn_cs,"cs_bardo","bardo_number","shop_number",$old_tr_id );}
			else if($bank_id==6||$bank_id==7||$bank_id==8){
			$bank_trid = func_get_value_of_field($cnn_cs,"cs_volpay","return_code","trans_id",$old_tr_id );}
			elseif ($bank_id==9||$bank_id==10){
			$bank_trid = func_get_value_of_field($cnn_cs,"cs_scanorder","scanOrderId","transactionId",$old_tr_id );}
}//!=""
	$file_bank_id=$bank_trid ;
	
	//////////////
	if($file_status=="A")
		$file_status="Approved";
	else
		$file_status="Declined";
	$file_type= $rst_field["checkorcard"];
	$file_processcurrency=$rst_field["processing_currency"];
	if($file_type=="C")
		$file_type="Check";
	else
		$file_type="Credit Card"."   (".$file_processcurrency.")";
	$file_transdate=$rst_field["transactionDate"];
	$file_reason=$rst_field["reason"];				
	$value		 =  '"' . $file_transactionid . '"' . "," . '"' . $file_cancellationDate. '"' . "," . '"' . $file_companyname. '"' . ",";
	$value		.= '"' . $file_name. '"' . "," . '"' . $file_surname. '"' . "," . '"' . $file_amount. '"' . "," . '"'; 
	$value		.=   $file_status. '"' . "," . '"' .$file_type. '"' . "," . '"' . $file_transdate . '"' . "," . '"' . $file_reason. '"' . "," . '"' . $file_bank_id. '"' . "," . '"' . $currency;
	$value		.=  "\t";	
	if(!isset($value) || $value == ""){
		$value = "\t";
	}else{
		$value = '"' . $value . '"' . "\t";
	}
		$line = '"' . $value;
		$data .= trim($line)."\n";				
	}
		$data = str_replace('"', "", $data);
		if ($data == "") {
			$data = "\n no matching records found\n";
}
							
					# this line is needed because returns embedded in the data have "\r"
					# and this looks like a "box character" in Excel
$data = str_replace("\t", "", $data);
$header = str_replace("\t", "", $header);
			//		  print $data;
			//		  exit();
					# Nice to let someone know that the search came up empty.
					# Otherwise only the column name headers will be output to Excel.
$file_content =  $header."\n".$data;
$str_current_path = "csv/cancelrequest.csv";
$create_file = fopen($str_current_path,'w');
fwrite($create_file,$file_content);
fclose($create_file);
					
?>
				
<form name="frmDownload" method="post" action="downloadcancelrequest.php" onload="document.frmDownload.submit();">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center">
	   <br><br>
		<table width="70%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Export</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5" align="center"><br>
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td align="center" valign="middle"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
				Please click on the button to download the file. 
                    </font></td>
			  </tr>
			  <tr>
				<td align="center" valign="middle"><a href="javascript:funcDownload()"><img SRC="<?=$tmpl_dir?>/images/download.jpg" width="68" height="20" border="0"></a></td>
			  </tr>
			  <tr>
				<td align="center" valign="middle">&nbsp;</td>
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
	</td>
	</tr>
</table>	
</form>					
<?php
	include("includes/footer.php");
?>