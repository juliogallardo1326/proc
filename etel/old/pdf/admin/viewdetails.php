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
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude="ledgers";
include 'includes/header.php';

$periodhead="Ledgers";

require_once( '../includes/function.php');
include '../includes/function1.php';
$admin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$iuserid="";
$iflag=0;
$icontentflag=0;
$imonth =isset($HTTP_POST_VARS["cbo_from_month"])?$HTTP_POST_VARS["cbo_from_month"]:"";
$iyear =isset($HTTP_POST_VARS["cbo_from_year"])?$HTTP_POST_VARS["cbo_from_year"]:"";
$iday =isset($HTTP_GET_VARS["day"])?$HTTP_GET_VARS["day"]:"";

if($iday=="")
$iday =isset($HTTP_POST_VARS["day"])?$HTTP_POST_VARS["day"]:"";
//echo formatMoney(424.025)."<br>";
//echo round(424.025,2);
$str_startdate=$iyear."-".$imonth."-".$iday." 00:00:00";
$str_enddate=$iyear."-".$imonth."-".$iday." 23:59:59";

?>
<script type="text/javascript">
function func_popup(invoice)
{
	
	window.open("modifymiscfee.php?invoiceid="+invoice,"MiscFees","'status=1,scrollbars=1,width=750,height=700,left=0,top=0");
}
function func_popup_wire(par_id)
{
   	advtWnd=window.open("view_wireinstruction.php?id="+par_id+"","advtWndName","'status=1,scrollbars=1,width=400,height=500");
	advtWnd.focus();
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="90%" >
  <tr>
       <td width="100%" valign="top" align="center"  height="85%" >
    &nbsp;
    <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Invoice&nbsp;Details</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5" align="center">

	<form name="summery" action="updateinvoice.php" method="post">
<?php
print("<table width='100%' border='0' align='center' cellspacing='0' cellpadding='0'>");
//echo $str_startdate.$str_enddate;
 $qry_details="select * from cs_invoicedetails where generateddate >='$str_startdate' and generateddate<='$str_enddate' and  gatewayid='-1' order by netAmount  desc ";
if(!$rst_details=mysql_query($qry_details,$cnn_cs)){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
else
{
	$icheck="";
	$icredit="";
	$rst_num=mysql_num_rows($rst_details);
	if ($rst_num>0){
	$icontentflag=1;
	//echo $rst_num."here";
	for($iloop=1;$iloop<=$rst_num;$iloop++)
	{
		$rst_display=mysql_fetch_array($rst_details);
		if($iuserid==$rst_display[2])
		{
			$approval=$rst_display[17];
			$invoiceid=$rst_display[0];
			$approvedamount=$rst_display[5];
			 $declientamount=$rst_display[6];
			 $creditamount=$rst_display[7];
			 $totalamount=$rst_display[4];
			 $approvedcount=$rst_display[24];
			 $declinedcount=$rst_display[25];
			 $creditcount=$rst_display[31];
			 $totalcount=$rst_display[23];
			 $pending=$rst_display[8];
			 $pendingcount=$rst_display[26];
			 $nopass=$rst_display[32];
			 $nopasscount=$rst_display[33];
			 $pass=$rst_display[34];
			 $passcount=$rst_display[35];
			 $voiceuploadcount=$rst_display[36];
			 $voiceuploadamount=$rst_display[13];
			 //deduced rates
			 $chargebackamount=$rst_display[9];
			 $creditedamount=$rst_display[10];
			 $discountamount=$rst_display[11];
			 $transactionfee=$rst_display[12];
			 $deducedamount=$rst_display[15];
			  $reservefee=$rst_display[14];
			 $netamount=$rst_display[16];
			 $processingcurrency=$rst_display[22];
			  $checkorcard=$rst_display[21];
			  $voiceauthoamount=$rst_display[28];
			  $rejected=$rst_display[44];
			 $rejecteamt=$rst_display[45];
			 $rejectedtransfee=$rst_display[48];
			 $rejectedcreditamt=$rst_display[46];
			 $rejectedchargebackamt=$rst_display[47];
			 $rejectedcreditcount=$rst_display[51];
			 $rejectedchargebackcount=	$rst_display[52];
		}
		else
		{
			$iuserid=$rst_display[2];
			 $show_sql="select companyname,chargeback,credit,discountrate,transactionfee,reserve,transaction_type,voiceauthfee from cs_companydetails where userId=$iuserid";
			if(!$rst_companydetails=mysql_query($show_sql,$cnn_cs))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			else
			{
				 $rst_compdetails=mysql_fetch_array($rst_companydetails);
				 $companyname=$rst_compdetails[0];
				 $chargeback=$rst_compdetails[1];
				 $creditfee=$rst_compdetails[2];
				 $discountrate=$rst_compdetails[3];
				 $transactionrate=$rst_compdetails[4];
				 $reserve=$rst_compdetails[5];
				 $transactiontype=$rst_compdetails[6];
				 $voiceauthofee=$rst_compdetails[7];
			}
			$iflag=1;
				$approval=$rst_display[17];
			  $invoiceid=$rst_display[0];
			  	
			 $approvedamount=$rst_display[5];
			 $declientamount=$rst_display[6];
			 $creditamount=$rst_display[7];
			 $totalamount=$rst_display[4];
			 $approvedcount=$rst_display[24];
			 $declinedcount=$rst_display[25];
			 $creditcount=$rst_display[31];
			 $totalcount=$rst_display[23];
			 $pending=$rst_display[8];
			 $pendingcount=$rst_display[26];
			 $nopass=$rst_display[32];
			 $nopasscount=$rst_display[33];
			 $pass=$rst_display[34];
			 $passcount=$rst_display[35];
			 $voiceuploadcount=$rst_display[36];
			 $voiceuploadamount=$rst_display[13];
			 //deduced rates
			 $chargebackamount=$rst_display[9];
			 $creditedamount=$rst_display[10];
			 $discountamount=$rst_display[11];
			 $transactionfee=$rst_display[12];
			 $deducedamount=$rst_display[15];
			 
			 $reservefee=$rst_display[14];
			 $netamount=$rst_display[16];
			 $processingcurrency=$rst_display[22];
			 $checkorcard=$rst_display[21];
			 $voiceauthoamount=$rst_display[28];
			 $rejected=$rst_display[44];
			 $rejecteamt=$rst_display[45];
			 $rejectedtransfee=$rst_display[48];
			 $rejectedcreditamt=$rst_display[46];
			 $rejectedchargebackamt=$rst_display[47];
			 $rejectedcreditcount=$rst_display[51];
			 $rejectedchargebackcount=	$rst_display[52];		 	
		}
		 	$approvedamount=func_roundoff($approvedamount);			
			 $declientamount=func_roundoff($declientamount);			 
			 $creditamount=func_roundoff($creditamount);			 
			 $totalamount=func_roundoff($totalamount);			 
			 $pending=func_roundoff($pending);			 
			 //deduced rates
			 $chargebackamount=func_roundoff($chargebackamount);			 
			 $creditedamount=func_roundoff($creditedamount);			
			 $discountamount=func_roundoff($discountamount);			 
			 $transactionfee=func_roundoff($transactionfee);	 
			 $reservefee=func_roundoff($reservefee);
			 $deducedamount=$transactionfee+$reservefee+$discountamount+$creditedamount+$chargebackamount+$creditamount;			 
			 $netamount=func_roundoff($netamount);
			 
			 			
		 $qry_chqprevinvoice="select adminApproved from cs_invoicedetails where generateddate < '$str_startdate' and userId=$iuserid and checkorcard='C' and netAmount >=500 order by generateddate desc  ";
		$qry_crdprevinvoice="select adminApproved from cs_invoicedetails where generateddate < '$str_startdate' and userId=$iuserid and checkorcard='H' and netAmount >=500 order by generateddate desc  ";
		if(!$rst_chqpreinvoice=mysql_query($qry_chqprevinvoice,$cnn_cs))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
			else {
					$rst_check=mysql_fetch_array($rst_chqpreinvoice);
					 $icheck=$rst_check[0];
					}
			if(!$rst_crdpreinvoice=mysql_query($qry_crdprevinvoice,$cnn_cs))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
			else {
					$rst_credit=mysql_fetch_array($rst_crdpreinvoice);
					 $icredit=$rst_credit[0];
					}
		if($iflag==1)
		print("<tr align='center'><td align='center' colspan='14'><br><P align='center'><font face='verdana' size='2'><B>$companyname</B></font><br></td><br></tr>");
		//to show currencydetails
		$qry_currency="select * from cs_invoicecurrencydetails where invoiceId=$invoiceid";
			if(! $rst_currency=mysql_query($qry_currency,$cnn_cs))
			{
				print("Cannot execute query");
			}
			else{
				$numdetails=mysql_num_rows($rst_currency);

				for($numloop=0;$numloop<$numdetails;$numloop++)
				{
					$rst_currencydetails=mysql_fetch_array($rst_currency);
					$curapproval=$rst_currencydetails[17];			  	
			 $curapprovedamount=$rst_currencydetails[5];
			 $curdeclientamount=$rst_currencydetails[6];
			 $curcreditamount=$rst_currencydetails[7];
			 $curtotalamount=$rst_currencydetails[4];
			 $curapprovedcount=$rst_currencydetails[24];
			 $curdeclinedcount=$rst_currencydetails[25];
			 $curcreditcount=$rst_currencydetails[31];
			 $curtotalcount=$rst_currencydetails[23];
			 $curpending=$rst_currencydetails[8];
			 $curpendingcount=$rst_currencydetails[26];
			 $curnopass=$rst_currencydetails[32];
			 $curnopasscount=$rst_currencydetails[33];
			 $curpass=$rst_currencydetails[34];
			 $curpasscount=$rst_currencydetails[35];
			 $curvoiceuploadcount=$rst_currencydetails[36];
			 $curvoiceuploadamount=$rst_currencydetails[13];
			 //deduced rates
			 $curchargebackamount=$rst_currencydetails[9];
			 $curcreditedamount=$rst_currencydetails[10];
			 $curdiscountamount=$rst_currencydetails[11];
			 $curtransactionfee=$rst_currencydetails[12];
			 $curdeducedamount=$rst_currencydetails[15];
			 
			 $curreservefee=$rst_currencydetails[14];
			 $curnetamount=$rst_currencydetails[16];
			 $curprocessingcurrency=$rst_currencydetails[22];
			 $curcheckorcard=$rst_currencydetails[21];
			 $curvoiceauthoamount=$rst_currencydetails[28];
			 $currejected=$rst_currencydetails[44];
			 $currejecteamt=$rst_currencydetails[45];
			 $currejectedtransfee=$rst_currencydetails[48];
			 $currejectedcreditamt=$rst_currencydetails[46];
			 $currejectedchargebackamt=$rst_currencydetails[47];
			 $currejectedcreditcount=$rst_currencydetails[51];
			 $currejectedchargebackcount=	$rst_currencydetails[52];
			 $curapprovedamount=func_roundoff($curapprovedamount);			
			 $curdeclientamount=func_roundoff($curdeclientamount);			 
			 $curcreditamount=func_roundoff($curcreditamount);			 
			 $curtotalamount=func_roundoff($curtotalamount);			 
			 $curpending=func_roundoff($curpending);			 
			 //deduced rates
			 $curchargebackamount=func_roundoff($curchargebackamount);			 
			 $curcreditedamount=func_roundoff($curcreditedamount);			
			 $curdiscountamount=func_roundoff($curdiscountamount);			 
			 $curtransactionfee=func_roundoff($curtransactionfee);	 
			 $curreservefee=func_roundoff($curreservefee);
			 $curdeducedamount=$curtransactionfee+$curreservefee+$curdiscountamount+$curcreditedamount+$curchargebackamount+$curcreditamount;			 
			 $curnetamount=func_roundoff($curnetamount);
		?>
										<tr align='center'><td align='center' colspan='14'><br><P ><font face='verdana' size='2'><B>Currency Details (<?=$curprocessingcurrency?>)</B></font><br></td></tr>

				<tr>
				<td align="center" colspan="20">
				<table align="center"><tr  height='30' bgcolor='#CCCCCC'>
				<td align='center' class='cl1'><span class='subhd'>Currency Details</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Approved</span></td>
			   <td align='right' class='cl1'><span class='subhd'>Declined </span></td>
				<td align='center' class='cl1'><span class='subhd'>Cancelled</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Total</span></td>
				
				
			   <td align='right' class='cl1'><span class='subhd'>Deduction </span></td>
				
				<td align='center' class='cl1'><span class='subhd'>Charge Back</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Credit</span></td>
			   <td align='right' class='cl1'><span class='subhd'>Discount </span></td>
				<td align='center' class='cl1'><span class='subhd'>Transaction Fees</span></td>
				<td align='center' class='cl1'><span class='subhd'>Reserve Fees</span></td>
					
			   <td align='center' class='cl1'><span class='subhd'>Deduced Amount</span></td>
			   	<td align='right' class='cl1'><span class='subhd'> Net Amount</span></td>
				
				</tr> <tr align='center'>
				
				<td align='center' class='cl1'><font face='verdana' size='1'>Quantity</font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$curapprovedcount?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$curdeclinedcount?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$curcreditcount?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$curtotalcount?></font></td>
				
			   <td align='center' class='cl1'><font face='verdana' size='1'>Rate </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($chargeback)?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($creditfee)?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($discountrate)?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($transactionrate)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($reserve)?></font></td>
					
			   <td align='center' class='cl1' rowspan='2'><font face='verdana' size='1' ><?=formatMoney($curdeducedamount)?></font></td>
			   <td align='center' class='cl1' rowspan='2'><font face='verdana' size='1' > <?=formatMoney($curnetamount)?></font></td>
				</tr> <tr align='center'>
				<td align='center' class='cl1'><font face='verdana' size='1'>Amount (<?=$curprocessingcurrency?>)</font></td>	
			  	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curapprovedamount)?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curdeclientamount)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curcreditamount)?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curtotalamount)?></font></td>
				
			   <td align='center' class='cl1'><font face='verdana' size='1'>Amount(<?=$curprocessingcurrency?>) </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curchargebackamount)?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curcreditedamount)?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curdiscountamount)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curtransactionfee)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($curreservefee)?></font></td>	
			   	
				</tr>
				<?php if ($rejected>0){?>
				<tr height='30' bgcolor='#CCCCCC'>
				<td align='center' class='cl1'><font face='verdana' size='1'>Rejected Details</font></td>
				<td align='center' class='cl1'><span class='subhd'>Cancel</span></td>	
			   <td align='center' class='cl1'><span class='subhd'> Credit</span></td>
			   <td align='right' class='cl1'><span class='subhd'> Chargeback </span></td>
				<td align='center' class='cl1'><span class='subhd'> Transaction</span></td>				
				</tr>
				<tr>
				<td align='center' class='cl1'><font face='verdana' size='1'>Quantity</font></td>				
					<td align='center' class='cl1'><font face='verdana' size='1'><?=$currejected?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$currejectedcreditcount?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$currejectedchargebackcount?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$currejected?> </font></td>				
				</tr>
				<tr>
				<td align='center' class='cl1'><font face='verdana' size='1'>Amount</font></td>
				
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($currejecteamt)?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($currejectedcreditamt)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($currejectedchargebackamt)?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($currejectedtransfee)?></font></td>
				
				
				</tr>
				<?php } ?>
				</table></td></tr>
				
		<?php }}
		
		if($checkorcard=='H')
		{?>	
				<tr align='center'><td align='center' colspan='14'><br><P ><font face='verdana' size='2'><B>Total Summary</B></font><br></td></tr>
				<tr height='30' bgcolor='#CCCCCC' align='center'>
				<td align='center' class='cl1'><span class='subhd'>Card Details</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Approved</span></td>
			   <td align='center' class='cl1'><span class='subhd'>Declined </span></td>
				<td align='center' class='cl1'><span class='subhd'>Cancelled</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Total</span></td>
			   
				<td align='center' class='cl1'><span class='subhd'>Deduction </span></td>				
				<td align='center' class='cl1'><span class='subhd'>Charge Back</span></td>	
			   <td align='center' class='cl1'><span class='subhd'>Credit</span></td>
			   	<td align='center' class='cl1'><span class='subhd'>Discount </span></td>
				<td align='center' class='cl1'><span class='subhd'>Transaction Fees</span></td>
				<td align='center' class='cl1'><span class='subhd'>Reserve Fees</span></td>					
			   <td align='center' class='cl1'><span class='subhd'>Deduced Amount</span></td>
			   <td align='center' class='cl1'><span class='subhd'> Net Amount</span></td>
				<td align='center' class='cl1'><span class='subhd'> Admin Approval</span></td>
				<td align='center' class='cl1'><span class='subhd'> Modify</span></td>
				<td align='center' class='cl1'><span class='subhd'> Wire Instruction</span></td>
				</tr> <tr align='center'>
				
				<td align='center' class='cl1'><font face='verdana' size='1'>Quantity</font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$approvedcount?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$declinedcount?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$creditcount?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$totalcount?></font></td>
			   
				
			   <td align='center' class='cl1'><font face='verdana' size='1'>Rate </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($chargeback)?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($creditfee)?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($discountrate)?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($transactionrate)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($reserve)?></font></td>
					
			   <td align='center' class='cl1' rowspan='2'><font face='verdana' size='1' ><?=formatMoney($deducedamount)?></font></td>
			   <td align='center' class='cl1' rowspan='2'><font face='verdana' size='1' > <?=formatMoney($netamount)?></font></td>	
				<?php if($icredit=="N"){ ?>
			   <td class='cl1' rowspan="2"	><input type='checkbox' name='approval<?=$iloop?>' value='A' disabled> </td>
				<?php }elseif($approval=="A"){ ?>
			   <td class='cl1' rowspan="2"	><font face='verdana' size='1'>Approved</font></td>
			   <?php } else { ?>
				<td  class='cl1' rowspan="2"><font face='verdana' size='1'><input type='checkbox' name='approval<?=$iloop?>' value='A' <?=$approval=="A"?"checked":""?>>
				
                    </font></td><?php } ?>
					<?php if($approval!="A" && $icredit!='N'){ ?>
				<td  class='cl1' rowspan='2'><a style='Text-decoration:none'  href='javascript:func_popup(<?=$invoiceid?>)'><font size='1' face='Verdana, Arial,Helvetica,sans-serif'>edit</font></a></td>
				<?php } else {?>
				<td  class='cl1' rowspan='2'>&nbsp;</td>
				<?php }?>
								<td  class='cl1' rowspan='2'><a style='Text-decoration:none'  href='javascript:func_popup_wire(<?=$iuserid?>)'><font size='1' face='Verdana, Arial,Helvetica,sans-serif'>view</font></a></td>

				</tr> <tr align='center'>
				
				<td align='center' class='cl1'><font face='verdana' size='1'>Amount</font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($approvedamount)?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($declientamount)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($creditamount)?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($totalamount)?></font></td>
				
			   <td align='center' class='cl1'><font face='verdana' size='1'>Amount </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($chargebackamount)?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($creditedamount)?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($discountamount)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($transactionfee)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($reservefee)?></font></td>	
			   	
				</tr>
				<?php if ($rejected>0){?>
				<tr height='30' bgcolor='#CCCCCC'>
				<td align='center' class='cl1'><font face='verdana' size='1'>Rejected Details</font></td>
				<td align='center' class='cl1'><span class='subhd'>Cancel</span></td>	
			   <td align='center' class='cl1'><span class='subhd'> Credit</span></td>
			   <td align='right' class='cl1'><span class='subhd'> Chargeback </span></td>
				<td align='center' class='cl1'><span class='subhd'> Transaction</span></td>				
				</tr>
				<tr>
				<td align='center' class='cl1'><font face='verdana' size='1'>Quantity</font></td>				
					<td align='center' class='cl1'><font face='verdana' size='1'><?=$rejected?> </font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=$rejectedcreditcount?></font></td>	
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=$rejectedchargebackcount?></font></td>
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=$rejected?> </font></td>				
				</tr><tr>
				
				<td align='center' class='cl1'><font face='verdana' size='1'>Amount</font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($rejecteamt)?></font></td>
			   <td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($rejectedcreditamt)?></font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($rejectedchargebackamt)?></font></td>	
			   	<td align='center' class='cl1'><font face='verdana' size='1'><?=formatMoney($rejectedtransfee)?></font></td>
				
				
				</tr>
				<?php } ?>
				<input type='hidden' name='hid_invoiceid<?=$iloop?>' value='<?= $invoiceid?>'>
		 <?php }
		$iflag=0;
	}
	print("<input type='hidden' name='hid_totalcount' value='$iloop'>"); 
	print("<input type='hidden' name='cbo_from_month' value='$imonth'>");
	print("<input type='hidden' name='cbo_from_year' value='$iyear'>");
	print("<input type='hidden' name='day' value='$iday'>");
	}
	else{
			print("<tr align='center'><td align='center' colspan='14'><br><P align='center'><font face='verdana' size='2'><B>No invoice for this Date</B></font><br></td></tr>");	
	}
}

?>
<center>
<table align="center"  ><tr>
		<td align="center" valign="center" height="30" colspan="2"><a href="projectedsettlement.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a>&nbsp;<?= $icontentflag==1?'<input type="image" id="submitmodify" SRC="<?=$tmpl_dir?>/images/submit.jpg"></input>':"";?>
                    </td>
                  </tr>	
</table></center>
	</form>
	</td>
      </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
    </table><br>
    </td>
     </tr>
</table>

<?
include 'includes/footer.php';
function func_roundoff($number)
{
$number=round($number*1000);
$number=$number/1000;
$number=(int)($number*100);
$number=$number/100;
return $number;
}
?>