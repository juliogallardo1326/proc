<?php 

include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$pageConfig['Title'] = 'Projected Profit';
$headerInclude="reports";
$periodhead="Ledgers";
$display_stat_wait = true;
include 'includes/header.php';

//get_profit
//$smarty->assign("Calendar" );
function get_month_profit($en_ID,$Month_Stamp=NULL)
{
	global $companyInfo;
	
	if(!$Month_Stamp) $Month_Stamp = time();
	$RF = new rates_fees();
	
	$Month_FirstDay_Stamp = strtotime(date('Y-m-01',$Month_Stamp));
	$Month_FirstDay_WeekDay = intval(date('w',$Month_FirstDay_Stamp));
	$Month_NumberOfDays = intval(date('t',$Month_FirstDay_Stamp));
	
	$Month_Start_Stamp = $Month_FirstDay_Stamp - 60*60*24*$Month_FirstDay_WeekDay;
	$Month_End_Stamp = $Month_FirstDay_Stamp+60*60*24*(ceil(($Month_NumberOfDays)/7)*7);
	$Current_Stamp = $Month_Start_Stamp;
	
	$Calendar = array();
	$Calendar['Notes'] = "This Calendar shows how much profit (after fees) is owed on each day.\n";
	$Calendar['PayDayInfo'] = en_get_payout_schedule($companyInfo);
	$PayDays = $Calendar['PayDayInfo']['DayArray'];
	
	$Calendar['MonthName'] = date('F',$Month_FirstDay_Stamp);
	$Profit=$RF->get_profit(
		array('hidepayout'=>true,'EffectiveOnly'=>false, 'group_date'=>true, 'date_between'=>array('Start'=>date('Y-m-d',$Month_Start_Stamp),'End'=>date('Y-m-d',$Month_End_Stamp-1)) ),
		$en_ID);
	$Calendar['PayoutHistory']=$RF->get_payouts(
		array('date_between'=>array('Start'=>date('Y-m-d',$Month_Start_Stamp),'End'=>date('Y-m-d',$Month_End_Stamp-1)) ),
		$en_ID);
		
	$NextPayday = $Calendar['PayDayInfo']['NextPayDay'];

	$ProjectedPayment = $RF->get_profit(array('EffectiveOnly'=>$NextPayday),$en_ID);
	if($ProjectedPayment['Total']['Amount']>0)
	{
		$Calendar['Notes'].= " Next Projected Settlement Date is on ".date('l F d, Y',strtotime($NextPayday))." for $".formatMoney($ProjectedPayment['Total']['Amount'])."\n";
		$ProjectedPayment['Title'] = date('l F d, Y',strtotime($NextPayday))." Retroactive Projection Breakdown";
		
		foreach($ProjectedPayment['Revenue'] as $key=>$type)
			$ProjectedPayment['Revenue'][$key]['Link']
				= "ProfitSmart.php?frm_pt_pt_type%5B%5D=".$key."&frm_pt_pt_date_effective_from=2000-01-01&frm_pt_pt_date_effective_to=".$NextPayday."";
	
		foreach($ProjectedPayment['Deductions'] as $key=>$type)
			$ProjectedPayment['Deductions'][$key]['Link']
				= "ProfitSmart.php?frm_pt_pt_type%5B%5D=".$key."&frm_pt_pt_date_effective_from=2000-01-01&frm_pt_pt_date_effective_to=".$NextPayday."";
		$ProjectedPayment['Link'] 
			= "ProfitSmart.php?frm_pt_pt_date_effective_from=2000-01-01&frm_pt_pt_date_effective_to=".$NextPayday."";
	}
	while($Current_Stamp < $Month_FirstDay_Stamp+60*60*24*$Month_NumberOfDays)
	{
		for($i=0;$i<7;$i++)
		{
			
			$Day = array('Date'=>date('Y-m-d',$Current_Stamp),'Num'=>date('d',$Current_Stamp),'CurMonth'=>(date('m',$Current_Stamp)==date('m',$Month_FirstDay_Stamp)));
			if($companyInfo['en_pay_type']=='Weekly') $Day['PayDay'] = ($PayDays[intval(date('w',$Current_Stamp))]?true:false);
			if($companyInfo['en_pay_type']=='Monthly') $Day['PayDay'] = ($PayDays[intval(date('d',$Current_Stamp))]?true:false);
			$ProfitDay = $Profit['ByDate'][$Day['Date']];
			//$Day['Text'] = '<strong>Revenue</strong>:<br>&nbsp;$'.formatMoney($ProfitDay['Revenue']['Total']['Amount']).'<br>';
			//$Day['Text'] .= '<strong>Deductions</strong>:<br>&nbsp;$'.formatMoney($ProfitDay['Deductions']['Total']['Amount']).'<br>';
			$Day['Text'] .= "<a href='ProfitSmart.php?hideprofit=1&showdate=".($Day['Date'])."'>$".formatMoney($ProfitDay['Total']['Amount'])."</a>";
			if($Calendar['PayoutHistory'][$Day['Date']]) $Day['Text'] .= '<br><b>Payment: <a href=\'SmartInvoiceView.php?InvoiceID='.$Calendar['PayoutHistory'][$Day['Date']]['pa_ID'].'\'>$'.formatMoney($Calendar['PayoutHistory'][$Day['Date']]['Amount']).'</a></b>';
			else if($Day['Date']==$NextPayday && $ProjectedPayment['Total']['Amount']>0) $Day['Text'] .= '<br><b>Projected Payment: <a href=\'#Projection\'>$'.formatMoney($ProjectedPayment['Total']['Amount']).'</a></b>';
			else if(!$ProfitDay['Total']['Count']) $Day['Text'] = "No Activity";
			
			$Calendar['Week'][date('W',$Current_Stamp+60*60*24)]['Day'][$i]=$Day;
			$Current_Stamp += 60*60*24;
		}
	}
	$Calendar['Profit'] = $Profit;
	$Calendar['ProjectedPayment'] = $ProjectedPayment;
	return $Calendar;
}


if(!$_REQUEST['SelectMonth']) $_REQUEST['SelectMonth'] = date('Y-m-01');
$This_Month_Stamp = strtotime(date('Y-m-01'));
$Selected_Month_Stamp = strtotime($_REQUEST['SelectMonth']);

$Calendar = get_month_profit($companyInfo['en_ID'],$Selected_Month_Stamp);

for($i=6;$i>-30;$i--)
{
	$DateOptions['Names'][] =date('Y F',$This_Month_Stamp+(60*60*24*($i*30+15)));
	$DateOptions['Values'][] =date('Y-m-01',$This_Month_Stamp+(60*60*24*($i*30+15)));
}
$DateOptions['Selected'] = $_REQUEST['SelectMonth'];

$smarty->assign("Calendar", $Calendar);
$smarty->assign("DateOptions", $DateOptions);

beginTable();
etel_smarty_display('cp_calendar.tpl');
endTable("Daily Profit View for ".$Calendar['MonthName'],"SmartProjection.php",false,false,false,false,true,'get');

if($Calendar['ProjectedPayment']['Total']['Amount']>0)
{
	echo "<a name='Projection' id='Projection'></a>";
	beginTable();
	$smarty->assign("Profit", $Calendar['ProjectedPayment']);
	etel_smarty_display('cp_profitreport.tpl');
	endTable("Projection Breakdown ");
}


include("includes/footer.php");
?>