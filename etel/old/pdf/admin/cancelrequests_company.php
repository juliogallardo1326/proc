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
// Cancelrequests_company.php: 
include("includes/sessioncheck.php");


include("../includes/function1.php");
$headerInclude = "transactions";
include("includes/header.php");

include("includes/message.php");
?>
<?php
$bname="";
$subqry="";
$old_tr_id= "";
$bank_trid="";
$Transtype = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";
$company_name = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
$bank = isset($HTTP_POST_VARS['bank'])?$HTTP_POST_VARS['bank']:"";
$ChkorCrd = isset($HTTP_POST_VARS['ChkorCrd'])?$HTTP_POST_VARS['ChkorCrd']:"";
$rejected = isset($HTTP_POST_VARS['rejected'])?$HTTP_POST_VARS['rejected']:"";



if($ChkorCrd=="C"){$bank="chk";}
$qrt_select_companies ="select distinct userId,companyname from cs_companydetails where 1 order by companyname";
$hid_company_ids="";
$i_refnum="";
$i_bnk_chek ="";
$i_bnk_creditcard ="";
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

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_report = (isset($HTTP_POST_VARS["hid_report"])?quote_smart($HTTP_POST_VARS["hid_report"]):"");


$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day." 00:00:00";
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day." 23:59:59";

?>
<script>
function showWindow(i_transid) {
	window.open ("viewreportpage.php?id="+i_transid+"&act=view",'','scrollbars=1,resizable=1,title=no,width=750, height=500');
}
function process(task){
	if (task == "export") {
		
		document.frm_cancel_requests.action = "exportcancelrequest.php";
	} else if (task == "update") {
		document.frm_cancel_requests.action = "updatecancelrequests_company.php";
	}
	document.frm_cancel_requests.submit();
}
</script>
<?php		 
if ($Transtype != "showResult")
{			  
?>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">

function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.frm_cancel_requests;
	if (obj_element.name == "from_date"){
		obj_form.opt_from_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_from_month.selectedIndex = monthSelected ;
		obj_form.opt_from_year.selectedIndex = func_returnselectedindex(yearSelected) ;
	}
	if (obj_element.name == "from_to"){
		obj_form.opt_to_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_to_month.selectedIndex = monthSelected ;
		obj_form.opt_to_year.selectedIndex = func_returnselectedindex(yearSelected);
	}
}
function func_returnselectedindex(par_selected)
{
	var dt_new =  new Date();
	var str_year = dt_new.getFullYear()
	for(i=2003,j=0;i<str_year+10;i++,j++)
	{
		if (i==par_selected)
		{
			return j;
		}
	}
}
function Displaycompanytype() {
	document.frm_cancel_requests.trans_type.value="Submit";
	document.frm_cancel_requests.action = "cancelrequests_company.php";
	document.frm_cancel_requests.submit();
}
/*function newwind(param){
window.open('viewreportpage.php?id=param&act=view',title)
}*/
var flag=0;
function func_disable(){
if (flag==0){
document.frm_cancel_requests.bank.disabled=true;flag=1;}
else {document.frm_cancel_requests.bank.disabled=false;flag=0;}
}

</script>
			<form name="frm_cancel_requests" action="cancelrequests_company.php" method="post">
			<input type="hidden" name="trans_type" value="showResult">
	
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
			<tr>
			<td width="95%" valign="top" align="center">

			<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
			      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Cancel Requests</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">  
			<tr>
			   <td   height="10"  valign="middle" align="center" width="50%">
		  </td>
			</tr>
			<tr>
			<td height="30"  valign="middle" align="center" width="50%"> 
			  <font face="verdana" size="1">Start Date</font>&nbsp; 
		   <select name="opt_from_month" style="font-size:10px">
			<?php func_fill_month($i_from_month); ?>
		   </select>
			<select name="opt_from_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_from_day); ?>
		   </select>
		     <select name="opt_from_year" style="font-size:10px">
			<?php func_fill_year($i_from_year); ?>
		   </select>
		    <input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		    <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(625,280,document.frm_cancel_requests.from_date)">
		   </td></tr>
		   <tr>
		    <td height="30"  valign="middle" align="center"> <font face="verdana" size="1">End Date&nbsp;</font>&nbsp;  
		  &nbsp;<select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
		  </select>
		  <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
		  </select>
		  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
		  </select>
		   <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(625,320,document.frm_cancel_requests.from_to)">
		  </td>
		  </tr>		  		  
		 <tr>
		    <td height="30" valign="middle" align="center">
			<table align="center" width="70%" cellpadding="0" cellspacing="0" border="0">
			<tr>
			 <td height="30" valign="middle" align="right" width="23%"><font face="verdana" size="1">Company Type </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
			<?php print func_select_mailcompanytype($companytype); ?>
			</select></td>
			</tr>
			<tr>
			<td height="30" valign="middle" align="right" width="23%"><font face="verdana" size="1">Merchant Type </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
			<?php print func_select_companytrans_type($companytrans_type); ?>
			</select></td>
			</tr>
			<tr>
			 <td height="60" valign="middle" align="right" width="23%"><font face="verdana" size="1">Company Name </font></td>
			 <td align="left"  width="60%">&nbsp;&nbsp;<select name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
			<?php print func_multiselect_transaction($qrt_select_companies); ?>
			</select></td>
			</tr>
			<tr><td height="60" valign="middle" align="right" width="23%"><font face="verdana" size="1">Select bank </font></td>
			<td align="left"  width="60%">&nbsp;&nbsp;
			<select name="bank" style="font-family:verdana;font-size:10px"  >
			<option value= "a"  > Select All </option>
			<option value= "b" > Bardo </option>
			 <option value= "v" > Volpay </option>
			 <option value= "s" > Scandorder </option>
			</select>&nbsp;
			<select name= "ChkorCrd" style="font-family:verdana;font-size:10px" onChange="func_disable();">
			<option value= "H" >  CreditCard </option>
			<option value= "C" > Check </option>
			</select>
			</td></tr>
			<tr ><td height="20" valign="middle" align="right" width="23%">
			<font face="verdana" size="1">View Rejected </font></td> <td height="20" valign="middle" align="left" width="10"><INPUT TYPE="radio" NAME="rejected" value="rejected">
			</td>  </tr><tr> <td height="20" valign="middle" align="right" width="23%">
			<font face="verdana" size="1">View Accepted </font></td> <td height="20" valign="middle" align="left" width="10"><INPUT TYPE="radio" NAME="rejected" value="accepted">
			</td>   </tr>
			</table>
		  </td>
		 </tr>
		 <tr>
		    <td height="35" valign="middle" align="center"><input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/view.jpg"></input>
		</td>
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
} else {
	$str_where_condition = "";
	$str_company_ids = "";
	if ($company_name[0] == "A") {
		$hid_company_ids="A";
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
		for ($i = 0; $i < count($company_name); $i++) {
			$str_company_ids .= $company_name[$i] . ", ";
			$hid_company_ids.=$str_company_ids;
			
		}
		$str_company_ids = substr($str_company_ids, 0, strlen($str_company_ids) - 2);
		$str_where_condition = "where B.userId in ($str_company_ids)";
	}
	if ($str_from_date != "")
	{
	$subqry_rej="and A.admin_approval_for_cancellation='P'";

	if($rejected=="accepted"){
	$subqry_rej="and A.admin_approval_for_cancellation='A'";
	} else 	if($rejected=="rejected"){
	$subqry_rej="and A.admin_approval_for_cancellation='R'";
	}
	
		if($bank=="b")
		{
			$bname="( Bank : Bardo )"; 
			$subqry=" and (A.bank_id=3)";
		}
		else if($bank=="s")
		{
			$bname="( Bank : ScandOrder )";
			$subqry=" and (A.bank_id=9 or A.bank_id=10)";
		}
		else if($bank=="v") 
		{
			$bname="( Bank : Volpay )";
			$subqry=" and (A.bank_id=6 or A.bank_id=7 or A.bank_id=8)"; 
		}
		else if($bank=="a")
		{$subqry="and checkorcard='H'";}
		else if  ($bank=="chk")
		{$subqry="and checkorcard='C' "; $bname="";}

	  	   $qry_select="Select A.transactionid ,A.phonenumber,A.billingDate,A.name,A.surname,B.companyname,A.checkorcard,A.amount,A.reason,A.other,B.processing_currency,B.userId,A.reference_number ,A.bank_id,B.bank_check,A.cancel_refer_num,A.cardtype from cs_transactiondetails A, cs_companydetails B ";
		   $qry_select .= $str_where_condition == "" ? " where " : $str_where_condition ." and "; 
		   $qry_select .= " A.userId = B.userId and A.cancelstatus = 'Y' ".$subqry_rej." and A.transactionDate >= '".$str_from_date."' and gateway_id = -1 ";
			if($str_to_date != "")
			{
				$qry_select .= " AND A.transactionDate <= '".$str_to_date."' ";
			}
			$qry_select .= $subqry." Order by B.companyname, A.transactionDate desc";
			//print($qry_select); // exit();
			$rssel_report = mysql_query($qry_select);
			$i_count = mysql_num_rows($rssel_report);
			if ($i_count==0)
			{
				$msgtodisplay="No Cancel Requests for this period.";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();	   
			}
			$yes=0;
			if ($i_count>0)
			{
		      $yes=1;
			  $str_data = "";
			  $str_temp_company = "";
			  $i_num_record = 0;
			  for($i=0;$i<$i_count;$i++)
			  {
				$i_transid = mysql_result($rssel_report,$i,0);
				$str_phone = mysql_result($rssel_report,$i,1);
				$str_bill_date = mysql_result($rssel_report,$i,2);
				$str_first_name  = mysql_result($rssel_report,$i,3);
				$str_surname  = mysql_result($rssel_report,$i,4);
				$str_company	= mysql_result($rssel_report,$i,5);
				$str_checkorcard = mysql_result($rssel_report,$i,6) == "C" ? "Check" : "Credit Card";
				$i_amount = mysql_result($rssel_report,$i,7);
				$str_cancellation_reason = mysql_result($rssel_report,$i,9) == "" ? mysql_result($rssel_report,$i,8) : mysql_result($rssel_report,$i,9);
				$str_currency	= mysql_result($rssel_report,$i,10);
				$i_company_id = mysql_result($rssel_report,$i,11);
				$i_refnum = mysql_result($rssel_report,$i,12);
				$i_bnk_creditcard = mysql_result($rssel_report,$i,13);
				$i_bnk_chek = mysql_result($rssel_report,$i,14);
				$cancel_refer_num = mysql_result($rssel_report,$i,15);
				$cardtype= mysql_result($rssel_report,$i,16);
				$currency=func_get_cardcurrency($cardtype,$i_company_id,$cnn_cs);
				
				$len=strlen($cancel_refer_num);
				$bank_Creditcard=$i_bnk_creditcard;
				if ($bank_Creditcard==6||$bank_Creditcard==7||$bank_Creditcard==8){
				$bnk="v";}
				else if($bank_Creditcard==9||$bank_Creditcard==10)
				{$bnk="s";}
				if($bank_Creditcard==3){
				$bnk="b";}
				 $old_tr_id= substr($cancel_refer_num,4,$len-6);
				$str_qry="";
				$bank_trid	="";
				if($old_tr_id!=""){
					
				if($bank=="b"){$bname="Bardo";}else if($bank=="s"){$bname="ScandOrder";}else if($bank=="v") {$bname="Volpay";}
				if($bank_Creditcard==3){
				$bank_trid = func_get_value_of_field($cnn_cs,"cs_bardo","bardo_number","shop_number",$old_tr_id );
				}
				else if($bank_Creditcard==6||$bank_Creditcard==7||$bank_Creditcard==8){
				$bank_trid = func_get_value_of_field($cnn_cs,"cs_volpay","return_code","trans_id",$old_tr_id );}
				elseif ($bank_Creditcard==9||$bank_Creditcard==10){
				$bank_trid = func_get_value_of_field($cnn_cs,"cs_scanorder","scanOrderId","transactionId",$old_tr_id );}
				
				}//!=
					if ($str_temp_company != $str_company) {
					  $str_temp_company = $str_company;
					  $i_num_record = 0;
					  $str_data .= "<tr><td colspan='10' align='center' height='40'><font face='verdana' size='1'><b>$str_company  $bname</b></font></td></tr>";
					  $str_data .= "<tr>";
					  $str_data .= "<td width='2%' bgcolor='#CCCCCC' height='30' rowspan='2'><span class='subhd'>No.</span></td>";
					  $str_data .= "<td width='4%' bgcolor='#CCCCCC' rowspan='2'><span class='subhd'>Tran ID</span></td>";
					  $str_data .= "<td width='4%' bgcolor='#CCCCCC' rowspan='2'><span class='subhd'>Bank Tran ID</span></td>";
					  $str_data .= "<td width='10%' bgcolor='#CCCCCC' rowspan='2'><span class='subhd'>Customer Name</span></td>";
					  $str_data .= "<td width='6%' bgcolor='#CCCCCC' rowspan='2'><span class='subhd'>Phone Number</span></td>";
					  $str_data .= "<td width='6%' bgcolor='#CCCCCC' rowspan='2'><span class='subhd'>Check / Card</span></td>";
					  $str_data .= "<td width='8%' bgcolor='#CCCCCC' rowspan='2' align='right'><span class='subhd' >Amount</span></td>";
					  $str_data .= "<td width='6%' bgcolor='#CCCCCC' rowspan='2' align='center'><span class='subhd'>Bill Date</span></td>";		 
					  $str_data .= "<td width='10%' bgcolor='#CCCCCC' rowspan='2' align='center'><span class='subhd'>Cancellation Reason</span></td>";		 
if ($rejected==""){
					  $str_data .= "<td width='5%' bgcolor='#CCCCCC' colspan='2' align='center'><span class='subhd'>Cancel</span></td>";		 
					
					   } $str_data .= "</tr>";
					  $str_data .= "<tr>";
if ($rejected==""){
					  $str_data .= "<td width='2%' bgcolor='#CCCCCC'><span class='subhd'>Accept</span></td>";		$str_data .= "<td width='2%' bgcolor='#CCCCCC'><span class='subhd'>Reject</span></td>";		 
					 }
					  $str_data .= "</tr>";
			   }
					 $str_data .= "<tr>";
					 $str_data .= "<td bgcolor='#E2E2E2' height='30'><font size='1' face='Verdana' >". ++$i_num_record ."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' ><a href='#' onClick='showWindow($i_transid)'>".$i_refnum."</a> </font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$bank_trid." </font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$str_first_name." &nbsp;".$str_surname."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$str_phone." </font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$str_checkorcard." </font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2' align='right'><font size='1' face='Verdana' >(".$currency.") ".formatMoney($i_amount)."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".func_get_date_inmmddyy($str_bill_date)."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$str_cancellation_reason." </font></td>";
					if ($rejected==""){
					 $str_data .= "<td bgcolor='#E2E2E2' align='center'><input type='radio' name='cancel_status".$i."' value='accept'></td>";
					 $str_data .= "<td bgcolor='#E2E2E2' align='center'><input type='radio' name='cancel_status".$i."' value='reject'></td>";
					 }
					 $str_data .= "</tr>";
					 $str_data .= "<input type='hidden' name='hid_trans_id".$i."' value='".$i_transid."'>";
					 $str_data .= "<input type='hidden' name='payment_type".$i."' value='".$str_checkorcard."'>";
					 $str_data .= "<input type='hidden' name='company_id".$i."' value='".$i_company_id."'>";
					 $str_data .= "<input type='hidden' name='i_bnk_creditcard".$i."' value='".$i_bnk_creditcard."'>";
					 $str_data .= "<input type='hidden' name='i_bnk_chek".$i."' value='".$i_bnk_chek."'>";
					
		}
		if($yes==0){
				$msgtodisplay="No Cancel Requests under this Bank ($bname).";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);
				$bname="";									
				exit();	   
			}					
		?>
			<form name="frm_cancel_requests" action="updatecancelrequests_company.php" method="post">
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
			<tr>
			<td width="95%" valign="top" align="center">

			<table width="98%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
				  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Cancel Requests Details</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			  <table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">

				<tr>
				  <td colspan="6"><font size="1" face="Verdana" ><strong>Total Records :
					<?=$i_count; ?></strong></font>
				  </td>
			   </tr>
			   <?= $str_data?>
			   <input type="hidden" name="hid_count" value="<?=$i_count?>">
	<tr>
		<td colspan="113" align="center" valign="middle" height="50"><a href="cancelrequests_company.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a>&nbsp;<?php if ($rejected==""){ ?>
<a href="javascript: process('update')"><img border="0" SRC="<?=$tmpl_dir?>/images/submit.jpg"></a> <?php }?> </input>
		
		<a href="javascript: process('export')" id="export"><img SRC="<?=$tmpl_dir?>/images/exportfile.jpg" border="0"></a>
		<input type="hidden" name="hidcompanyname" value="<?php print($hid_company_ids) ?>">
		<input type="hidden" name="hidtrans_type" value="<?php print($Transtype) ?>">
		<input type="hidden" name="hidcompanymode" value="<?php print($companytype) ?>">
		<input type="hidden" name="hidcompanytrans_type" value="<?php print($companytrans_type) ?>">
		<input type="hidden" name="hidfromdate" value="<?php print($str_from_date) ?>">
		<input type="hidden" name="hidtodate" value="<?php print($str_to_date) ?>">
		<input type="hidden" name="hidchkorcrd" value="<?php print($ChkorCrd) ?>">
		<input type="hidden" name="hidbank" value="<?php print($bank) ?>">
		<input type="hidden" name="rejected" value="<?=$rejected?>">
		</td>
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
	}  
}				 
?>
   </td>
  </tr>
</table> 
<input type="hidden" name="hid_report" value="report">
	</form>

<?php
include("includes/footer.php");
?>
