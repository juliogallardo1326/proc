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
// Cancelrequests.php:
$allowBank=true;
include("includes/sessioncheck.php");


require_once('../includes/function2.php');
$headerInclude = "customerservice";
include("includes/header.php");
include("includes/message.php");

if($_POST['Action'] == "Delete" && $adminInfo['li_level'] == 'full')
{
	$sql="DELETE FROM `cs_callnotes` WHERE note_id = '".$_POST['note_id']."'";
	$result=mysql_query($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
}

if($_POST['Action'] == "Refund" && $adminInfo['li_level'] == 'full')
{
	$ref_no = func_Trans_Ref_No($_POST['transactionId']);
	$qry_details="UPDATE `cs_transactiondetails` SET `cancelstatus` = 'Y', `admin_approval_for_cancellation` = 'A', `cancellationDate` = CONCAT(CURDATE(),' ',CURRENT_TIME()), `reason` = 'Administrator Refund From Found Call', `cancel_refer_num` = '$ref_no' WHERE `transactionId` = '".$_POST['transactionId']."'";
	$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	//func_canceledTransaction_receipt('', $_POST['transactionId'],$cnn_cs);
	func_email_cancel_reciept($_POST['transactionId'],"customer_refund_confirmation");
}

$Transtype = isset($_GET['trans_type'])?quote_smart($_GET['trans_type']):"";
$companytype = isset($_GET['companymode'])?$_GET['companymode']:"A";
$companytrans_type = isset($_GET['companytrans_type'])?quote_smart($_GET['companytrans_type']):"A";
$company_name = isset($_GET['companyname'])?$_GET['companyname']:"";
$bank_id=$_GET['bank_id'];
if (!$bank_id) $bank_id=$_POST['bank_id'];
if($bank_id) $bank_sql = " AND bank_id = '$bank_id' ";
$qrt_select_companies ="select distinct userId,companyname from cs_companydetails as C where 1 $bank_sql_limit order by companyname";

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
		$str_total_query = " and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails as C where 1 $bank_sql_limit $str_total_query order by companyname";
}
	
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
	
$i_from_year = (isset($_GET["opt_from_year"])?quote_smart($_GET["opt_from_year"]):$i_from_year);
$i_from_month = (isset($_GET["opt_from_month"])?quote_smart($_GET["opt_from_month"]):$i_from_month);
$i_from_day = (isset($_GET["opt_from_day"])?quote_smart($_GET["opt_from_day"]):$i_from_day);
$i_to_year = (isset($_GET["opt_to_year"])?quote_smart($_GET["opt_to_year"]):$i_to_year);
$i_to_month = (isset($_GET["opt_to_month"])?quote_smart($_GET["opt_to_month"]):$i_to_month);
$i_to_day = (isset($_GET["opt_to_day"])?quote_smart($_GET["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);
	
$i_from_year = (isset($_GET["opt_from_year"])?quote_smart($_GET["opt_from_year"]):$i_from_year);
$i_from_month = (isset($_GET["opt_from_month"])?quote_smart($_GET["opt_from_month"]):$i_from_month);
$i_from_day = (isset($_GET["opt_from_day"])?quote_smart($_GET["opt_from_day"]):$i_from_day);
$i_to_year = (isset($_GET["opt_to_year"])?quote_smart($_GET["opt_to_year"]):$i_to_year);
$i_to_month = (isset($_GET["opt_to_month"])?quote_smart($_GET["opt_to_month"]):$i_to_month);
$i_to_day = (isset($_GET["opt_to_day"])?quote_smart($_GET["opt_to_day"]):$i_to_day);

$str_report = (isset($_GET["hid_report"])?quote_smart($_GET["hid_report"]):"");


$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day." 00:00:00";
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day." 23:59:59";
?>
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
	document.frm_cancel_requests.action = "cancelrequests.php";
	document.frm_cancel_requests.submit();
}

</script>

<form name="frm_cancel_requests" action="" method="get">
<input type="hidden" name="trans_type" value="showResult">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
<tr>
  <td width="95%" valign="top" align="center"><table width="50%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Refund Requests</span></td>
        <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
        <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
        <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
      </tr>
      <tr>
        <td class="lgnbd" colspan="5"><table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">
            <tr>
              <td   height="10"  valign="middle" align="center" width="50%"></td>
            </tr>
            <tr>
              <td height="30"  valign="middle" align="center" width="50%"><font face="verdana" size="1">Start Date</font>&nbsp;
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
              </td>
            </tr>
            <tr>
              <td height="30"  valign="middle" align="center"><font face="verdana" size="1">End Date&nbsp;</font>&nbsp; &nbsp;
                <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
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
              <td height="30" valign="middle" align="center"><table align="center" width="70%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td height="30" valign="middle" align="right" width="23%"><font face="verdana" size="1">Company Type </font></td>
                    <td align="left"  width="60%">&nbsp;&nbsp;
                      <select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
                        <?php print func_select_mailcompanytype($companytype); ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td height="30" valign="middle" align="right" width="23%"><font face="verdana" size="1">Merchant Type </font></td>
                    <td align="left"  width="60%">&nbsp;&nbsp;
                      <select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
                        <?php print func_select_companytrans_type($companytrans_type); ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td height="60" valign="middle" align="right" width="23%"><font face="verdana" size="1">Company Name </font></td>
                    <td align="left"  width="60%">&nbsp;&nbsp;
                      <select name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
                        <?php print func_multiselect_transaction($qrt_select_companies); ?>
                      </select></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td height="35" valign="middle" align="center"><input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/view.jpg">
                </input>
              </td>
            </tr>
          </table></td>
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
		if ($companytype == "A") {
			if ($companytrans_type == "A") {
				$str_where_condition = "";
			} else {
				$str_where_condition = "where C.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "AC") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where C.activeuser = 1 ";
			} else {
				$str_where_condition = "where C.activeuser = 1 and C.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "NC") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where C.activeuser = 0 ";
			} else {
				$str_where_condition = "where C.activeuser = 0 and C.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "RE") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where C.reseller_id <> '' ";
			} else {
				$str_where_condition = "where C.reseller_id <> '' and C.transaction_type = '$companytrans_type' ";
			}
		} else if ($companytype == "ET") {
			if ($companytrans_type == "A") {
				$str_where_condition = "where C.reseller_id is null ";
			} else {
				$str_where_condition = "where C.reseller_id is null and C.transaction_type = '$companytrans_type' ";
			}
		}
	} else {
		for ($i = 0; $i < count($company_name); $i++) {
			$str_company_ids .= $company_name[$i] . ", ";
		}
		$str_company_ids = substr($str_company_ids, 0, strlen($str_company_ids) - 2);
		$str_where_condition = "where C.userId in ($str_company_ids)";
	}
	if ($str_from_date != "")
	{
			//SELECT * FROM `cs_callnotes` WHERE `cn_type` = 'refundrequest'
		   $qry_select="Select N.*,B.*,C.userId,C.companyname from `cs_callnotes` N,cs_transactiondetails B,cs_companydetails C ";
		   $qry_select .= $str_where_condition == "" ? " where " : $str_where_condition ." $bank_sql_limit and ";
		   $qry_select .= " N.transaction_id = B.transactionId $bank_sql_limit AND N.`cn_type` = 'refundrequest' $bank_sql and B.userId = C.userId ";
			if($str_to_date != "")
			{
				$qry_select .= " AND N.call_date_time BETWEEN '$str_from_date' AND '$str_to_date' ";
			}
			$qry_select .= " Order by C.companyname, N.call_date_time desc";
			$rssel_report = mysql_query($qry_select) or dieLog(mysql_error()." $qry_select");
			$i_count = mysql_num_rows($rssel_report);
			if ($i_count==0)
			{
				$msgtodisplay="No Cancel Requests for this period.";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();	   
			}
			if ($i_count>0)
			{
			  $str_data = "";
			  $str_temp_company = "";
			  $i_num_record = 0;
			  while ($transInfo = mysql_fetch_assoc($rssel_report))
			  {
				if ($userId != $transInfo['userId']) {
					  $userId = $transInfo['userId'];
					  $i_num_record = 0;
					  $str_data .= "<tr><td colspan='10' align='center' height='40'><font face='verdana' size='1'><b>".$transInfo['companyname']."</b></font></td></tr>";
					  $str_data .= "<tr>";
					  $str_data .= "<td width='3%' bgcolor='#CCCCCC' height='30'><span class='subhd'>No.</span></td>";
					  $str_data .= "<td width='4%' bgcolor='#CCCCCC' ><span class='subhd'>Reference Number</span></td>";
					  $str_data .= "<td width='4%' bgcolor='#CCCCCC' ><span class='subhd'>Charge</span></td>";
					  $str_data .= "<td width='5%' bgcolor='#CCCCCC' ><span class='subhd'>Customer Name</span></td>";
					  $str_data .= "<td width='6%' bgcolor='#CCCCCC' ><span class='subhd'>Contact</span></td>";
					  $str_data .= "<td width='12%' bgcolor='#CCCCCC'><span class='subhd'>Request DateTime</span></td>";
					  $str_data .= "<td width='19%' bgcolor='#CCCCCC' ><span class='subhd'>Reason</span></td>";
					  $str_data .= "<td width='8%' bgcolor='#CCCCCC' ><span class='subhd'>Bill Date</span></td>";
					  $str_data .= "<td width='5%' bgcolor='#CCCCCC' ><span class='subhd'>Action</span></td>";		 
					  $str_data .= "</tr>";
			   }
					 $str_data .= "<tr><form method='post' name='Trans".$transInfo['transactionId']."'>";
					 $str_data .= "<td bgcolor='#E2E2E2' height='30'><font size='1' face='Verdana' >". ++$i_num_record ."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' ><a href='viewreportpage.php?id=".$transInfo['transactionId']."'>".$transInfo['reference_number']."</a></font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".formatMoney($transInfo['amount'])."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$transInfo['name']." &nbsp;".$transInfo['surname']."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >Phone: ".$transInfo['phonenumber']." <br><a href='mailto:".$transInfo['email']."'>Email</a><br>Contact by ".$transInfo['cn_contactmethod']."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".func_get_date_time_12hr($transInfo['call_date_time'])."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$transInfo['service_notes']."<br>".$transInfo['customer_notes']."</font></td>";		 
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".func_get_date_inmmddyy($transInfo['transactionDate'])."</font></td>";
					 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >";
					 if($transInfo['cancelstatus'] == 'N') $str_data .= "<input type='submit' name='Action' value='Refund' >";
					 else $str_data .= "<strong>REFUNDED</strong><BR>";
					 $str_data .= "<input type='submit' name='Action' value='Delete' >";
					 $str_data .= "</font></td>";
					 
					 $str_data .= "<input type='hidden' name='transactionId' value='".$transInfo['transactionId']."'>";
					 $str_data .= "<input type='hidden' name='note_id' value='".$transInfo['note_id']."'>";
					 $str_data .= "</form></tr>";
				}					
		?>
    <table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
    <tr>
      <td width="95%" valign="top" align="center"><table width="98%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Refund Requests Details</span></td>
          <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
          <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
          <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
        </tr>
        <tr>
          <td class="lgnbd" colspan="5"><table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">
            <tr>
              <td colspan="6">
			  <?php if ($adminInfo['li_level'] != 'bank') { ?>
			  <form name="frm_cancel_requests" action="" method="post"><font size="1" face="Verdana" ><strong>Total Records :
                <?=$i_count; ?>
                </strong></font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Show

  <select name="bank_id">
    <option value="">All Banks</option>
    <?php func_fill_combo_conditionally("Select * from cs_bank where bk_hide=0 ",$bank_id,$cnn_cs) ?>
  </select>
  <input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/submit.jpg" width="49" height="20">
  <input type="hidden" name="hid_count" value="<?=$i_count?>">
  <input type="hidden" name="hid_report" value="report">
</form>
<?php } ?>

</td>
</tr>
<?= $str_data?>
<tr>
  <td colspan="14" align="center" valign="middle" height="50"><a href="cancelrequests.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a>&nbsp;    </td>
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
<?php
include("includes/footer.php");
?>
