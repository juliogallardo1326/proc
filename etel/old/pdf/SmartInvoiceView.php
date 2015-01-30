<?php 

include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$pageConfig['Title'] = 'Invoice Information';
$headerInclude="reports";
$periodhead="Ledgers";
$display_stat_wait = true;
include 'includes/header.php';

if(!$_REQUEST['InvoiceID']) dieLog('No Invoice Selected','No Invoice Selected');

	$RF = new rates_fees();
	$Payouts=$RF->get_payouts(
		array('pa_ID'=>intval($_REQUEST['InvoiceID'])),
		$curUserInfo['en_ID']);
	if($Payouts['status']===false) dieLog($Payouts['msg'],$Payouts['msg']);
	$Payouts=array('InvoiceInfo' => array_pop($Payouts));
	$time = strtotime($Payouts['InvoiceInfo']['pa_date']);
	$Payouts['ThisPeriodEnd'] = date('Y-m-d',$time);
	$Payouts['LastPeriodEnd'] = en_get_payout_period($curUserInfo,'last',$time);
	$Payouts['ThisPeriodStart'] = date('Y-m-d',strtotime($Payouts['LastPeriodEnd'])+60*60*24);
	
	$Payouts['InvoiceProfit']=$RF->get_profit(
		array('EffectiveOnly'=>false, 'hidepayout'=>true, 'date_between'=>array('Start'=>$Payouts['ThisPeriodStart'],'End'=>$Payouts['ThisPeriodEnd']) ),$curUserInfo['en_ID']);
	//etelPrint($Payouts['InvoiceProfit']);
	$Payouts['PreviousProfit']=$RF->get_profit(
		array('EffectiveOnly'=>false, 'hidepayout'=>true, 'date_between'=>array('Start'=>'2000-01-01','End'=>$Payouts['LastPeriodEnd']) ),$curUserInfo['en_ID']);
	
	foreach($Payouts['InvoiceProfit']['Revenue'] as $key=>$type)
		$Payouts['InvoiceProfit']['Revenue'][$key]['Link']
			= "ProfitSmart.php?frm_pt_pt_type%5B%5D=".$key."&frm_pt_pt_date_effective_from=".$Payouts['ThisPeriodStart']."&frm_pt_pt_date_effective_to=".$Payouts['ThisPeriodEnd']."";
	
	foreach($Payouts['InvoiceProfit']['Deductions'] as $key=>$type)
		$Payouts['InvoiceProfit']['Deductions'][$key]['Link']
			= "ProfitSmart.php?frm_pt_pt_type%5B%5D=".$key."&frm_pt_pt_date_effective_from=".$Payouts['ThisPeriodStart']."&frm_pt_pt_date_effective_to=".$Payouts['ThisPeriodEnd']."";

	foreach($Payouts['PreviousProfit']['Revenue'] as $key=>$type)
		$Payouts['PreviousProfit']['Revenue'][$key]['Link']
			= "ProfitSmart.php?frm_pt_pt_type%5B%5D=".$key."&frm_pt_pt_date_effective_from=2000-01-01&frm_pt_pt_date_effective_to=".$Payouts['LastPeriodEnd']."";
	
	foreach($Payouts['PreviousProfit']['Deductions'] as $key=>$type)
		$Payouts['PreviousProfit']['Deductions'][$key]['Link']
			= "ProfitSmart.php?frm_pt_pt_type%5B%5D=".$key."&frm_pt_pt_date_effective_from=2000-01-01&frm_pt_pt_date_effective_to=".$Payouts['LastPeriodEnd']."";

	//$Payouts['InvoiceProfit']['Link']
	//	= "ProfitSmart.php?hideprofit=1&frm_pt_pt_date_effective_from=".$Payouts['ThisPeriodStart']."&frm_pt_pt_date_effective_to=".$Payouts['ThisPeriodEnd']."";
	
	
	$Difference = round($Payouts['InvoiceInfo']['Amount']-$Payouts['InvoiceProfit']['Total']['Amount'],2);
	
	if($Difference>0)	
	{
		$Payouts['InvoiceProfit']['Revenue']['Adjustments'] = array('Amount' => $Difference,'Count'=>1);
		$Payouts['InvoiceProfit']['Revenue']['Total']['Amount'] += $Difference;
		$Payouts['InvoiceProfit']['Revenue']['Total']['Count'] ++;
	}
	if($Difference<0)	
	{
		$Payouts['InvoiceProfit']['Deductions']['Adjustments'] = array('Amount' => $Difference,'Count'=>1);
		$Payouts['InvoiceProfit']['Deductions']['Total']['Amount'] += $Difference;
		$Payouts['InvoiceProfit']['Deductions']['Total']['Count'] ++;
	}
	
	if($Payouts['InvoiceProfit']['Revenue']['Sale Funds']) 
		$Payouts['InvoiceProfit']['Revenue']['Sale Funds']['Comments'] = "After Reserve and CS Fees";
	if($Payouts['InvoiceProfit']['Revenue']['Reserve Release'])
		$Payouts['InvoiceProfit']['Revenue']['Reserve Release']['Comments'] = "Reserve Released after hold period";
	if($Payouts['InvoiceProfit']['Revenue']['Funds Transfer Fee'])
		$Payouts['InvoiceProfit']['Revenue']['Funds Transfer Fee']['Comments'] = "Wire/ACH/Other Payment Transfer Fee";
	
	if($Payouts['PreviousProfit']['Revenue']['Sale Funds']) 
		$Payouts['PreviousProfit']['Revenue']['Sale Funds']['Comments'] = "After Reserve and CS Fees";
	if($Payouts['PreviousProfit']['Revenue']['Reserve Release'])
		$Payouts['PreviousProfit']['Revenue']['Reserve Release']['Comments'] = "Reserve Released after hold period";
	if($Payouts['PreviousProfit']['Revenue']['Funds Transfer Fee'])
		$Payouts['PreviousProfit']['Revenue']['Funds Transfer Fee']['Comments'] = "Wire/ACH/Other Payment Transfer Fee";
	
	$Payouts['InvoiceProfit']['Total'] = array('Amount'=>$Payouts['InvoiceInfo']['Amount'],'Count'=>1);
		
	$Payouts['InvoiceProfit']['Title'] = $Payouts['InvoiceInfo']['pa_desc']."<BR>";
	//$Payouts['InvoiceProfit']['Notes'] = "Date Range extends from ".date('m-d-Y',strtotime($Payouts['ThisPeriodStart']))." to ".date('m-d-Y',strtotime($Payouts['ThisPeriodEnd']));
	//$Payouts['InvoiceProfit']['Notes'] .= "<BR>Transaction Profit is effective days behind the transaction date.";
	if($Payouts['InvoiceInfo']['pa_info']['Notes']) $Payouts['InvoiceProfit']['Notes'] .= "<BR><BR>".nl2br($Payouts['InvoiceInfo']['pa_info']['Notes'])."<BR>";
	$Payouts['PreviousProfit']['Title'] = "Previous Profit Breakdown";
	//$Payouts['PreviousProfit']['Notes'] = "Date Range extends from First Transaction to ".date('m-d-Y',strtotime($Payouts['LastPeriodEnd']));
	//$Payouts['PreviousProfit']['Notes'] .= "<BR>Transaction Profit is effective days behind the transaction date.";
	
	beginTable();
	$smarty->assign("Profit", $Payouts['InvoiceProfit']);
	etel_smarty_display('cp_profitreport.tpl');
	endTable("Invoice Information");
	beginTable();
	$smarty->assign("Profit", $Payouts['PreviousProfit']);
	etel_smarty_display('cp_profitreport.tpl');
	endTable("Previous Profit Breakdown");

	//etelPrint($Payouts);	

include("includes/footer.php");
?>