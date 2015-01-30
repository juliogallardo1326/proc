<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// resellerLedger.php:	The admin page functions for viewing the company transactions as a summary. 
include("includes/sessioncheck.php");

$headerInclude="ledgers";
$display_stat_wait = true;
include("includes/header.php");
include("../includes/companySubView.php");
require_once("../includes/projSetCalc.php");
require_once("../includes/JSON_functions.php");

$bank_ids = array();
$sql = "SELECT bank_id FROM cs_bank WHERE bk_ignore=0 order by bank_id asc";
$res = sql_query_read($sql) or dieLog(mysql_error());
while($bankInfo = mysql_fetch_assoc($res))
	$bank_ids[] = $bankInfo['bank_id'];
	
if($_POST['submit'] == 'SM Payout Response')
{	
	$csv = file_get_contents($_FILES["sm_payout_file"]["tmp_name"]);	
	$csv = str_replace("\r", "", $csv);
	$csv_info = csv_parse($csv);
	$error = 0;
	$count = 0;
	foreach ($csv_info as $info)
	{	
		list($company_id, $mi_paydate) = split("_",$info[2]);
		
		if($company_id && $company_id != "ETEL")
		{
			$count++;
			$invoice_sql = "select mi_ID,DATE_FORMAT(mi_paydate, '%W %M %D, %Y') as wiredate,companyname,email,username,password,ReferenceNumber,
			mi_title, mi_status, mi_deduction, mi_balance, mi_notes
			
			from `cs_merchant_invoice` left join cs_companydetails on mi_company_id = userId
			WHERE mi_paydate = '".quote_smart($mi_paydate)."' and mi_company_id = '".quote_smart($company_id)."'";
			$result = sql_query_read($invoice_sql);
			if($invoiceInfo = mysql_fetch_assoc($result))
			{
			
				$mi_status = 'Pending';
				$mi_ID = intval($invoiceInfo['mi_ID']);
				
				$emailData = array();
	
				$emailData["companyname"] = $invoiceInfo['companyname'];
				$emailData["username"] = $invoiceInfo['username'];
				$emailData["password"] = $invoiceInfo['password'];
				$emailData["Reference_ID"] = $invoiceInfo['ReferenceNumber'];
				$emailData["email"] = $invoiceInfo['email'];
				$emailData["wiredate"] = $invoiceInfo['wiredate'];
				$emailData["mi_title"] = $invoiceInfo['mi_title'];
				$emailData["mi_deduction"] = $invoiceInfo['mi_deduction'];
				$emailData["mi_status"] = $invoiceInfo['mi_status'];
				$emailData["mi_balance"] = $invoiceInfo['mi_balance'];
				$emailData["mi_notes"] = $invoiceInfo['mi_notes'];
				
				switch($info['57'])
				{
					case 'MD':
					case 'DP':
						$mi_status = 'WireFailure';
						$emailData["mi_status"] = $mi_status;
						if($invoiceInfo['mi_status']!= $mi_status) 
						{
							sql_query_write("update cs_merchant_invoice set mi_status = '$mi_status' where mi_ID = '$mi_ID'");
							send_email_template('merchant_wire_failure',$emailData);
						}
						else $count--;
						break;
					case 'PA':// – processed ACH
					case 'PW':// – processed wire – automated processing
					case 'MC':// – manual wire – auto processing failed / manually completed
					case 'MW':// – manual wire – new account, account not set up at bank
						$mi_status = 'WireSuccess';
						$emailData["mi_status"] = $mi_status;
						if($invoiceInfo['mi_status']!= $mi_status) 
						{
							sql_query_write("update cs_merchant_invoice set mi_status = '$mi_status' where mi_ID = '$mi_ID'");
							send_email_template('merchant_wire_success',$emailData);
						}
						else $count--;
						break;
					default:
						toLog('misc','merchant',"Invalid SM Payout Response Code in SM Payout File = '".$info['57']."'");
						$error++;
				}
		
			
			}
			else
			{
				toLog('misc','merchant',"Invalid SM Payout ID. $invoice_sql.");
				$error++;
			}
		}
	}
	$error = intval($error);
	
	doTable("<br>CSV Processed. ".intval($count-$error)."/".intval($count)." Invoices Updated.","SM CSV Result","paymentReport.php",true,true);
	die();
}

if($_POST['submit'] == 'Set Status' && $_POST['set_mi_status'])
{	
	if(!$_POST['mi_ID']) dieLog("Invalid Status Command: No Selected Invoices.");
	$mi_IDList = $_POST['mi_ID'];
	if(!is_array($mi_IDList) || sizeof($mi_IDList)<=0) dieLog("Invalid Status Command: Invalid Selected Invoices");
	foreach($mi_IDList as $k => $data)
		{$data = explode("|",$data); $mi_IDList[$k] = $data[0];}
	$mi_IDList_sql = " and mi_ID in (".quote_smart(implode(",",$mi_IDList)).")";
	$sql = "update cs_merchant_invoice set `mi_status` = '".quote_smart($_POST['set_mi_status'])."' where 1 $mi_IDList_sql";

	sql_query_write($sql) or dieLog("Cannot execute query: $sql" );
}
if($_POST['Submit'] == 'Pay Companys')
{
	$msg = "";
	
	if(!$_POST['chk_userId']) dieLog("Invalid Pay Command: No Selected Companys.");
	$userIdList = $_POST['chk_userId'];
	if(!is_array($userIdList) || sizeof($userIdList)<=0) dieLog("Invalid Pay Command: Invalid Selected Companys");
	$userList_sql = " and userId in (".quote_smart(implode(", ",$userIdList)).")";

	$qry_company="select * from cs_companydetails where 1 $userList_sql AND cd_pay_status='payable' and cd_ignore=0";
	$company_details=sql_query_read($qry_company,$cnn_cs) or dieLog("Cannot execute query: $qry_company" );
	while($companyInfo=mysql_fetch_assoc($company_details))
	{
		$date_hold=0;
		$mi_notes = "";
		$paydate = $companyInfo['cd_next_pay_day'];
		if ($_POST['custom_paydate']) $paydate = $_POST['custom_paydate'];
		$paid = payCompany(strtotime($paydate));
		$mi_ID = $paid;
		if ($paid!=-1) $msg .= "Merchant ID=".$companyInfo['userId']." Paid Successfully (Invoice #$mi_ID Created)\n";
		else $msg .= "Error paying Merchant ID=".$companyInfo['userId']."! ~ $error_msg\n";
	}
	$userIdList=NULL;
	$userList_sql=NULL;
	doTable("<div align='center' style='font-size:10'>".nl2br($msg)."</div>","Payment Result","paymentReport.php",true,true);
	die();
}

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$str_adminapproval="";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
//print_r($_REQUEST);
$wireDisplayLimit = " Limit 1";
$wireDisplay = "";
$wireDisplayLastPaid = "Last Paid: ";

$userList_sql="";

$company_table_sql = "`cs_companydetails`";

if($_REQUEST['cd_view'] == 'A')
{

}
else if($_REQUEST['cd_view'] == 'AL')
{
	$sql_info = JSON_getCompanyInfo_build($_REQUEST);
	$company_table_sql = " (Select cd.* from ". $sql_info['sql_from']." Where ".$sql_info['sql_where'].")";
	$displayAllStatus = true;
	$recentIDsql = NULL;
}
else if($_REQUEST['userIdList'])
{
	if($_REQUEST['userIdList']) $userIdList = @explode("|",quote_smart($_REQUEST['userIdList']));
	if(is_array($userIdList) && sizeof($userIdList)>0) $userList_sql = " and userId in (".implode(", ",$userIdList).")";
}


if(!$mi_status) $mi_status = false;
if(!$show_rollover) $show_rollover = 'p';
if(!$show_active) $show_active = 'a';

if($mi_status)
{
	$wireDisplay = " and mi_status = '$mi_status'";
	$wireDisplayLimit = "";
	$wireDisplayLastPaid = "-";
}
require_once ("../includes/projSetCalc.php");
?>
<script language="javascript">
function changeStatus(status)
{
	var obj = document.getElementById('mi_ID');
	var length = obj.options.length;
	for(i=0;i<length;i++)
	{
		info =obj.options[i].value.split("|");
		if(info[1]==status)obj.options[i].display='none';
	}
}
function generateBatchPayout()
{
	var obj = document.getElementById('mi_ID');
	var length = obj.options.length;
	var mi_ID;
	for(i=0;i<length;i++)
	{
		if(obj.options[i].selected)
		{
			info =obj.options[i].value.split("|");
			if(info[0])
			{
				if(mi_ID) mi_ID+="|";
				else mi_ID = "";
				mi_ID+=info[0];
			}
		}
	}
	document.location.href='PayoutGenerate.php?mi_ID='+mi_ID;
}
function generateWireInfo()
{
	var obj = document.getElementById('mi_ID');
	var length = obj.options.length;
	var mi_ID;
	for(i=0;i<length;i++)
	{
		if(obj.options[i].selected)
		{
			info =obj.options[i].value.split("|");
			if(info[0])
			{
				if(mi_ID) mi_ID+="|";
				else mi_ID = "";
				mi_ID+=info[0];
			}
		}
	}
	document.location.href='WireInfo.php?mi_ID='+mi_ID;
}
function viewInvoice()
{
	var obj = document.getElementById('mi_ID');
	var length = obj.options.length;
	var mi_ID;
	for(i=0;i<length;i++)
	{
		if(obj.options[i].selected)
		{
			info =obj.options[i].value.split("|");
			if(info[0])
				document.location.href='viewCompanyInvoice.php?mi_ID='+info[0];
			break;
		}
	}
}
</script>

<?php
beginTable();
echo genCompanyViewTable('paymentReport.php','paymentReport.php',$adminInfo['li_level']);
endTable("Company Payment");	  

if($userList_sql || $_REQUEST['cd_view'] == 'AL')
{
	$sql = "SELECT count(`transactionId`) as key1 FROM `cs_transactiondetails` where 1 $userList_sql limit 500";
	$result=sql_query_read($sql) or dieLog(mysql_error());
	$key=mysql_result($result,0,0);
	if(beginCacheTable("MI_".$key."_".getRequestHash(),time()+60*6))
	{
?>
	
	</td>
    </tr>
<tr>
    <td colspan="5" class="lgnbd"><table width="100%" border="0" cellpadding="2" align="center"  bgcolor="#EEEEEE">
        <tr align="center" valign="middle" >
          <td class="whitehd">
		  
		  
		  <table width="100%" border="0" class="invoice">
            <tr bgcolor="#CCCCCC">
              <td class="infoHeader" bgcolor="#CCCCCC">Company</td>
              <td class="infoHeader">Payday</td>
              <td class="infoHeader">Balance</td>
              <td class="infoHeader">Status</td>
              <td class="infoHeader">Action</td>
              <td class="infoHeader">Score</td>
            </tr>
      <?php  
	 
$companyInfo = NULL;

$display_none = "1";
//if($no_display==true) $display_none = "0";
$qry_company="select * from $company_table_sql as cd where $display_none $userList_sql $bank_sql_limit and cd_pay_status='payable' and cd_ignore=0 order by cd_next_pay_day ASC";
//etelPrint($qry_company);
$gatewayid=-1;
$company_details=sql_query_read($qry_company) or dieLog("Cannot execute query: $qry_company" );
while($companyInfo=mysql_fetch_assoc($company_details))
{
	$sql = 0;
	$error=0;
	//sleep(1);
	$quick_status = get_merchant_quick_status($companyInfo['userId']);
	
	while(pastPayPeriod()&& $error<1000)
	{
		$pushedBack = pushBackOnePeriod();
		$sql="Update cs_companydetails set `cd_next_pay_day` = '$pushedBack' where userId = '".$companyInfo['userId']."'";

		$companyInfo['cd_next_pay_day']=$pushedBack;
		$error++;
		if($error==1000) print "Error ".$companyInfo['userId']."<BR>";
	}
	if($sql) sql_query_write($sql) or dieLog(mysql_error()." ~ $sql");
$date_hold=0;

$mi_pay_info = calcReal(strtotime($companyInfo['cd_next_pay_day']));

$pay_checked = '';
if($mi_pay_info['Status']=='Payable' && $quick_status['actual']>0) 
{
	$pay_checked = 'checked';
	$est_payable += $mi_pay_info['Balance'];
}
$est_total += $mi_pay_info['Balance'];

//if($show_rollover=='s' && $mi_pay_info['Status']!='Rollover') continue;
//if($show_rollover=='p' && $mi_pay_info['Status']!='Payable') continue;
	?>
            <tr>
              <td class="info"><a href="editCompanyProfileAccess.php?company_id=<?=$companyInfo['userId']?>"><?=substr($companyInfo['companyname'],0,20)?></a></td>
              <td class="info"><?=$companyInfo['cd_next_pay_day']=="0000-00-00"?"Not Paid Yet":date("l, F j",strtotime($companyInfo['cd_next_pay_day']))?></td>
              <td class="info">
			  

                <?=($mi_pay_info['Balance']==0?"- None -":"<a href='payCompany.php?companyId=".$companyInfo['userId']."' >".formatMoney($mi_pay_info['Balance'])."</a>")?>
</a>



			  </td>
              <td class="<?=$mi_pay_info['Status']?>">
                <?=$mi_pay_info['Status']?>
              </td>
              <td>
			<input name="chk_userId[]" type="checkbox" id="chk_userId[]" <?=$pay_checked?> value="<?=$companyInfo['userId']?>">
			  </td>
              <td>
			  <?=$quick_status['percent']?>% (<?=$quick_status['actual']?>/<?=$quick_status['possible']?>)
			  </td>
            </tr>
			<?php 
			
			$sql = "SELECT * FROM `cs_merchant_invoice` WHERE `mi_company_id` = '".$companyInfo['userId']."' $wireDisplay ORDER BY `mi_date` DESC $wireDisplayLimit";
			$result = sql_query_read($sql) or dieLog(mysql_error() ." ~ $sql");
			
			while($last_invoice = mysql_fetch_assoc($result))
			{
				$last_mi_pay_info = @unserialize($last_invoice['mi_pay_info']);
			
			?>
			<tr>
			<td class="infoSubSection">
			<?=$wireDisplayLastPaid?>
			</td>
			<td class="infoSubSection">
			<?=date("l, F j",strtotime($last_invoice['mi_paydate']))?>
			</td>
              <td class="infoSubSection">
			  

                <?=($last_mi_pay_info['Balance']==0?"- None -":"<a href='viewCompanyInvoice.php?mi_ID=".$last_invoice['mi_ID']."' >".formatMoney($last_mi_pay_info['Balance'])."</a>")?>
</a>



			  </td>
              <td class='<?=$last_invoice['mi_status']?>'>&nbsp;&nbsp;<?=ucfirst($last_invoice['mi_status'])?>
              </td>
              <td>
			  <?php
			  		foreach ($last_mi_pay_info['BankInfo'] as $k=>$d) {
						if($d['bk_payout_support']) {
						
						if($batch_invoice_list) $batch_invoice_list.="|";
						$batch_invoice_list.=$d['mib_ID'];
						 ?>
				<!--input name="" type="button" value="Payout File" class="infoSubSection" onClick="document.location.href='PayoutGenerate.php?mib_ID=<?=$d['mib_ID']?>'"-->
						<? } ?>
					<? } ?>
			  </td>
			</tr>
			<?php } ?>

  <?php
	}
?>

			
          </table></td>
        </tr>
     <tr>
       <td align="right">			
			
			    Est Total: $<?=formatMoney($est_total)?> Est Payable: $<?=formatMoney($est_payable)?> 
			    <input type="hidden" name="userIdList" value="<?=$_REQUEST['userIdList']?>">
			    Custom Payday:
			    <input type="text" name="custom_paydate" />
			<input name="Submit" type="submit" class="infoSubSection" value="Pay Companys">
</td>
     </tr>
    </table></td>
<?php
	}
	endCacheTable(array('messageHeader' => "Company Payment", 'redir' => '', 'showTable' => true));

}
// List Reports
$statusSql = 1;
$mi_status = quote_smart($_REQUEST['mi_status']);
if($mi_status) $statusSql = "mi_status = '$mi_status'";
$invoiceSql = "select concat(mi_ID,'|',mi_status,'|',mib_ID) as mi_info , mi_title, mi_status as class from 
`cs_merchant_invoice` 
left join `cs_merchant_invoice_banksub` as mi on mib_mi_ID = mi_ID
left join `cs_companydetails` as cd on cd.userId = mi_company_id 
Where $statusSql $userList_sql and cd_pay_status='payable' and cd_ignore=0 GROUP BY `mi_ID` ORDER BY `mi_status`='WireSuccess' ASC , `mi_status`, `mi_date` DESC ";
ob_start();
?>

<a name="InvoiceHistory"></a>
<select name="mi_ID[]" size="10" id="mi_ID" class="invoice" multiple>
  <option value="">Select an Invoice</option>
  <?php func_fill_combo_conditionally($invoiceSql,NULL,$cnn_cs); ?>
</select>
<BR><table width="200" border="1" class="invoice" >
  <tr>
    <td><input name="submit" type="button" class="infoSubSection" id="btn_sel" onClick="generateBatchPayout()" value="Select"></td>
    <td><input name="submit" type="button" value="View Invoice" onClick="viewInvoice()"></td>
    <td><input name="submit" type="button" class="infoSubSection" id="wirecreate2" onClick="generateWireInfo()" value="Generate Wire Info"></td>
    <td><input name="submit" type="submit" class="infoSubSection" value="Set Status"></td>
    <td><input name="submit" type="button" class="infoSubSection" onClick="generateBatchPayout()" value="SM Payout File"></td>
    <td><input name="submit" type="submit" class="infoSubSection" value="SM Payout Response"></td>
  </tr>
  <tr>
    <td><select name="mi_status" id="select2" class="invoice" onChange="changeStatus(this.value)">
      <?=func_get_enum_values('cs_merchant_invoice','mi_status',$mi_status,'Any Status','') ?>
    </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><select name="set_mi_status" id="set_mi_status" class="invoice" onChange="changeStatus(this.value)">
      <?=func_get_enum_values('cs_merchant_invoice','mi_status',$mi_status,'Any Status','') ?>
        </select></td>
    <td>&nbsp;</td>
    <td>
      <input type="file" name="sm_payout_file" />
    </td>
  </tr>
</table>
<input type="hidden" name="companyId" value="<?=$companyId?>">
  <?php 
$invoiceHistory= ob_get_contents();
ob_end_clean();
doTable($invoiceHistory,"Invoice History","");


include("includes/footer.php");
?>
