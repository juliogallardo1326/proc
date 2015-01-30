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
include("includes/sessioncheck.php");


require_once('../includes/function2.php');
$headerInclude = "customerservice";
include("includes/header.php");

$sql = "SHOW COLUMNS FROM cs_log like 'lg_action'";
$result=sql_query_read($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$lg_action = mysql_fetch_assoc($result);
eval('$enum_action = '.str_replace("enum(","array(",$lg_action['Type']).';');
foreach($enum_action as $enum)
	$lg_action_list .= "<option value='$enum' >".ucfirst($enum)."</option>\n";
	
$sql = "SHOW COLUMNS FROM cs_log like 'lg_actor'";
$result=sql_query_read($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
$lg_actor = mysql_fetch_assoc($result);
eval('$enum_actor = '.str_replace("enum(","array(",$lg_actor['Type']).';');
foreach($enum_actor as $enum)
	$lg_actor_list .= "<option value='$enum' >".ucfirst($enum)."</option>\n";


if($_POST['Action'] == "Delete")
{
	$lg_id = isset($_POST['lg_id'])?quote_smart($_POST['lg_id']):"";
	$sql="DELETE FROM `cs_log` WHERE lg_id = '$lg_id'";
	$result=sql_query_write($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
}
if($_POST['Action'] == "Purge Log")
{
	$sql="TRUNCATE TABLE `cs_log` ";
	$result=sql_query_write($sql,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
}

$logFile = "/logs/EtelLog_".date("y-m")."_".$_SESSION["gw_id"].".txt";

$Transtype = isset($_GET['trans_type'])?quote_smart($_GET['trans_type']):"";
$companytype = isset($_GET['companymode'])?$_GET['companymode']:"A";
$companytrans_type = isset($_GET['companytrans_type'])?quote_smart($_GET['companytrans_type']):"A";
$company_name = isset($_GET['companyname'])?$_GET['companyname']:"";
$bank_id=$_GET['bank_id'];
if (!$bank_id) $bank_id=$_POST['bank_id'];
if($bank_id) $bank_sql = " AND bank_id = '$bank_id' ";
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
	
if(!($show_select_sql =sql_query_read($qrt_select_companies,$cnn_cs)))
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

$lg_actor = (isset($_GET["lg_actor"])?quote_smart($_GET["lg_actor"]):"");
$lg_action = (isset($_GET["lg_action"])?quote_smart($_GET["lg_action"]):"");
$lg_txt = (isset($_GET["lg_txt"])?quote_smart($_GET["lg_txt"]):"");

?>
<?php
			 
if ($Transtype != "showResult")
{			  
?>
<script language="javascript" src="../scripts/calendar_new.js"></script>

<form name="frm_cancel_requests" action="" method="get">
<input type="hidden" name="trans_type" value="showResult">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
<tr>
  <td width="95%" valign="top" align="center"><table width="50%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Log Viewer </span></td>
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
              <td align="center"><font face="verdana" size="1">Event Type &nbsp;</font>&nbsp;&nbsp;
                <select name="lg_action" id="lg_action" style="font-family:verdana;font-size:10px;WIDTH: 210px" >
                  <option value="" selected>Any</option>
				<?=$lg_action_list?>
                </select>
              </td>
            </tr>
            <tr>
              <td align="center"><font face="verdana" size="1">User Type &nbsp;</font>&nbsp;&nbsp;
                <select name="lg_actor" id="lg_actor" style="font-family:verdana;font-size:10px;WIDTH: 210px" >
                  <option value="" selected>Any</option>
					<?=$lg_actor_list?>
                </select>
              </td>
            </tr>
            <tr>
              <td align="center"><font face="verdana" size="1">Search (Wildcard: %) &nbsp;</font>&nbsp;&nbsp;
			  	<input name="lg_txt"  />
              </td>
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

if($lg_actor) $sql_actor = " AND lg_actor='$lg_actor' ";
if($lg_action) $sql_action = " AND lg_action='$lg_action' ";
if($lg_txt) $sql_txt = " AND lg_txt LIKE '%$lg_txt%' ";
$str_from_date = strtotime($i_from_year."-".$i_from_month."-".$i_from_day." 00:00:00");
$str_to_date = strtotime($i_to_year."-".$i_to_month."-".$i_to_day." 23:59:59");

$sql="SELECT SUM(`lg_actor`='customer') as customer,SUM(`lg_actor`='merchant') as merchant,SUM(`lg_actor`='reseller') as reseller,SUM(`lg_actor`='admin') as admin FROM `cs_log` WHERE lg_action='login' AND lg_timestamp between '$str_from_date' AND '$str_to_date' ORDER BY `lg_timestamp` DESC LIMIT 0 , 500";
$result = sql_query_read($sql) or dieLog(mysql_error()." $sql");
$loginInfo = mysql_fetch_assoc($result);

$sql="SELECT * FROM `cs_log` WHERE 1 $sql_actor $sql_action $sql_txt AND lg_timestamp between '$str_from_date' AND '$str_to_date' ORDER BY `lg_timestamp` DESC LIMIT 0 , 500";
$result = sql_query_read($sql) or dieLog(mysql_error()." $sql");
$i_count = mysql_num_rows($result);
if ($i_count==0)
{
	$msgtodisplay="No Log for this period.";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);	
	include("includes/footer.php");								
	exit();	   
}
if ($i_count>0)
{
  $str_data = "";
  $str_temp_company = "";
  $i_num_record = 0;
  while ($logInfo = mysql_fetch_assoc($result))
  {
		 $str_data .= "<tr><form method='post' name='Trans".$logInfo['transactionId']."'>";
		 $str_data .= "<td bgcolor='#E2E2E2' height='30'><input type='submit' name='Action' value='Delete' style='font:Arial, Helvetica, sans-serif; font-size:10px'></td>";
		 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$logInfo['lg_id']."</font></td>";
		 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$logInfo['lg_action']."</font></td>";
		 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".$logInfo['lg_actor']."</font></td>";
		 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >". htmlentities($logInfo['lg_txt'])."</font></td>";
		 $str_data .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >".date("F j, Y, g:i:s a",$logInfo['lg_timestamp'])." </font></td>";
		 $str_data .= "<input type='hidden' name='lg_id' value='".$logInfo['lg_id']."'>";
		 $str_data .= "</form></tr>";
	}					
		?>
    <table border="0" cellpadding="0" width="100%" cellspacing="0" height="55%" align="center">
    <tr>
      <td width="95%" valign="top" align="center"><table width="98%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Log Viewer </span></td>
          <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
          <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
          <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
        </tr>
        <tr>
          <td class="lgnbd" colspan="5"><table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">
            <tr>
              <td colspan="6">
<form name="form1" method="post" action="">
  <font size="1" face="Verdana" ><strong>
  &nbsp;&nbsp;&nbsp;&nbsp;Total Merchant Logins:
  <?=$loginInfo['merchant']?>
  &nbsp;&nbsp;&nbsp;&nbsp;Total Reseller Logins:
  <?=$loginInfo['reseller']?>
  &nbsp;&nbsp;&nbsp;&nbsp;Total Customer Logins:
  <?=$loginInfo['customer']?>
  &nbsp;&nbsp;&nbsp;&nbsp;Total Admin Logins:
  <?=$loginInfo['admin']?>
  &nbsp;&nbsp;</strong></font>
  <input name="Action" type="submit" id="Action" style='font:Arial, Helvetica, sans-serif; font-size:10px' value="Purge Log">
  </form>
  <font size="1" face="Verdana" ><strong> </strong></font>
</td>
</tr>
<tr>
  <td colspan='10' align='center' height='40'><font face='verdana' size='1'><strong> Log</strong></font></td>
</tr>
<tr>
  <td width='5%' bgcolor='#CCCCCC' height='30'><span class='subhd'>Remove Entry</span></td>
  <td width='1%' bgcolor='#CCCCCC' ><span class='subhd'>ID</span></td>
  <td width='5%' bgcolor='#CCCCCC' ><span class='subhd'>Action</span></td>
  <td width='5%' bgcolor='#CCCCCC' ><span class='subhd'>Actor</span></td>
  <td width='60%' bgcolor='#CCCCCC' ><span class='subhd'>Log Value</span></td>
  <td width='15%' bgcolor='#CCCCCC' ><span class='subhd'>Time Stamp</span></td>
</tr>
<?= $str_data?>
<tr>
  <td colspan="14" align="center" valign="middle" height="50"><a href="logView.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a>&nbsp; </td>
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
?>
</td>
</tr>
</table>
<?php
include("includes/footer.php");
?>
