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
require_once("includes/dbconnection.php");
require_once('includes/function.php');
include 'includes/function1.php';

?>
<html>
<head>
<title>:: Payment Gateway ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="styles/style.css" type="text/css" rel="stylesheet">
<link href="styles/text.css" type="text/css" rel="stylesheet">
</head>
<?php
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$iuserid="";
$iflag=0;

$imonth =isset($HTTP_GET_VARS["frommonth"])?$HTTP_GET_VARS["frommonth"]:"";
$iyear =isset($HTTP_GET_VARS["fromyear"])?$HTTP_GET_VARS["fromyear"]:"";
$iday =isset($HTTP_GET_VARS["day"])?$HTTP_GET_VARS["day"]:"";

$crdapproval=0;
$crdinvoiceid=0;	
$crdapprovedamount=0;
$crddeclientamount=0;
$crdcreditamount=0;
$crdtotalamount=0;
$crdapprovedcount=0;
$crddeclinedcount=0;
$crdcreditcount=0;
$crdtotalcount=0;
$crdpending=0;
$crdpendingcount=0;
$crdnopass=0;
$crdnopasscount=0;
$crdpass=0;
$crdpasscount=0;
$crdvoiceuploadcount=0;
$crdvoiceuploadamount=0;
//deduced rates
$crdchargebackamount=0;
$crdcreditedamount=0;
$crddiscountamount=0;
$crdtransactionfee=0;
$crddeducedamount=0;
$crdreservefee=0;
$crdnetamount=0;
$crdcheckorcard=0;
$crdvoiceauthoamount=0;
$chqapproval=0;
$chqinvoiceid=0;	
$chqapprovedamount=0;
$chqdeclientamount=0;
$chqcreditamount=0;
$chqtotalamount=0;
$chqapprovedcount=0;
$chqdeclinedcount=0;
$chqcreditcount=0;
$chqtotalcount=0;
$chqpending=0;
$chqpendingcount=0;
$chqnopass=0;
$chqnopasscount=0;
$chqpass=0;
$chqpasscount=0;
$chqvoiceuploadcount=0;
$chqvoiceuploadamount=0;
//deduced rates
$chqchargebackamount=0;
$chqcreditedamount=0;
$chqdiscountamount=0;
$chqtransactionfee=0;
$chqdeducedamount=0;
$chqreservefee=0;
$chqnetamount=0;
$processingcurrency="";
$checkorcard="";
$chqvoiceauthoamount=0;
$chqwirefee=0;
$chqmiscadd=0;
$chqmiscsub=0;
$chqmiscadddisc="";
$chqmiscsubdesc="";
$crdwirefee=0;
$crdmiscadd=0;
$crdmiscsub=0;
$crdmiscadddisc="";
$crdmiscsubdesc="";

$str_startdate=$iyear."-".$imonth."-".$iday." 00:00:00";
$str_enddate=$iyear."-".$imonth."-".$iday." 23:59:59";
?>
<table align="center" cellpadding="0" cellspacing="0" border="0" width="90%">
	<tr>
		<td align="left"><img src="images/invoicelogo.jpg" border="0"></td>
		<td align="right">
				<font face="verdana" size="1" color="#3399FF">
				<?=$_SESSION['gw_title']?> PAYMENT PROCESSING<br>
				</font>
		</td>
	</tr>
</table>
<table border="0" cellpadding="0" width="90%" cellspacing="0" height="61%" align="center">
<tr>
<td class="lgnbd" colspan="5" valign="top">
	<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>
<?php
$qry_details="select * from cs_invoicedetails where generateddate >='$str_startdate' and generateddate<='$str_enddate' and userId='$sessionlogin'  order by userId and  checkorcard  ";
	if(!$rst_details=mysql_query($qry_details,$cnn_cs)){			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	else
	{
		$rst_num=mysql_num_rows($rst_details);
		if($rst_num>0)
		{
			for($iloop=1;$iloop<=$rst_num;$iloop++)
			{
			$rst_display=mysql_fetch_array($rst_details);
			 $invoiceid=$rst_display[0];
			$qry_currencydetails="select * from cs_invoicecurrencydetails where invoiceId =$invoiceid";
			$iuserid=$rst_display[2];
			$checkorcard=$rst_display[21];
				
				   if($checkorcard=='H')
				  {
					$generateddate=$rst_display[18];	
					$crdapproval+=$rst_display[17];
					//$crdinvoiceid+=$rst_display[0];	
					$crdapprovedamount+=$rst_display[5];
					 $crddeclientamount+=$rst_display[6];
					 $crdcreditamount+=$rst_display[7];
					 $crdtotalamount+=$rst_display[4];
					 $crdapprovedcount+=$rst_display[24];
					 $crddeclinedcount+=$rst_display[25];
					 $crdcreditcount+=$rst_display[31];
					 $crdtotalcount+=$rst_display[23];
					 $crdpending+=$rst_display[8];
					 $crdpendingcount+=$rst_display[26];
					 $crdnopass+=$rst_display[32];
					 $crdnopasscount+=$rst_display[33];
					 $crdpass+=$rst_display[34];
					 $crdpasscount+=$rst_display[35];
					 $crdvoiceuploadcount+=$rst_display[36];
					 $crdvoiceuploadamount+=$rst_display[13];
					 //deduced rates
					 $crdchargebackamount+=$rst_display[9];
					 $crdcreditedamount+=$rst_display[10];
					 $crddiscountamount+=$rst_display[11];
					 $crdtransactionfee+=$rst_display[12];
					 $crddeducedamount=round(($crddeducedamount+$rst_display[15]),2);;
					  $crdreservefee+=$rst_display[14];
					 $crdnetamount= round(( $crdnetamount+$rst_display[16]),2);
					 $processingcurrency=$rst_display[22];
					  
					  $crdvoiceauthoamount+=$rst_display[28];
					  $crdwirefee+=$rst_display[41];
					  $crdmiscadd+=$rst_display[37];
					  $crdmiscsub+=$rst_display[38];
					  $crdmiscadddisc=$rst_display[39];
					  $crdmiscsubdesc=$rst_display[40];
						$rejected=$rst_display[44];
						$qry_netamount="select netAmount from cs_invoicedetails where userId =$iuserid and checkorcard ='H'  and generateddate <' $generateddate' order by generateddate desc";
															$rst_execute=mysql_query($qry_netamount,$cnn_cs);
															$rst_netamount=mysql_fetch_array($rst_execute);
															 $pevnetamountcrd=$rst_netamount[0];
															if($pevnetamountcrd >= 500 ){
																	$pevnetamountcrd="";														
																}
															
				}
				
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
					 $reserverate=$rst_compdetails[5];
					 $transactiontype=$rst_compdetails[6];
					 $voiceauthofee=$rst_compdetails[7];
				}
			$iflag=1;
		    }	
		//Total Calculation
		$approvedcount=$chqapprovedcount+$crdapprovedcount;
		$declinedcount=$chqdeclinedcount+$crddeclinedcount;
		$approvedamount=$chqapprovedamount+$crdapprovedamount;
		$declientamount=$chqdeclientamount+$crddeclientamount;
		$creditcount=$chqcreditcount+$crdcreditcount;
		$creditamount=$chqcreditamount+$crdcreditamount;
		$totalcount=$chqtotalcount+$crdtotalcount;
		$totalamount=$chqtotalamount+$crdtotalamount;
		$deducedamount=round(($chqdeducedamount+$crddeducedamount),2);
		$netamount=round(($chqnetamount+$crdnetamount),2);
		$chargeback=$crdchargebackamount+$chqchargebackamount;
		$credit=$crdcreditedamount+$crdcreditedamount;
		$discount=$crddiscountamount+$chqdiscountamount;
		$transaction=$crdtransactionfee+$chqtransactionfee;
		$reserve=$crdreservefee+$chqreservefee;
	if($iflag==1)
	{
?>  	<tr align='center'><td align='center' colspan='3' ><P align='center'><br><font face='verdana' size='2'><B><?=$companyname?></B></font></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td valign='top' colspan='3' >
		<!--Modification to include currency Details -->
		
		<?php
		$qrtcurrencydetails="Select * from cs_invoicecurrencydetails where invoiceid=$invoiceid";
		if(!$rst_currencydetails=mysql_query($qrtcurrencydetails,$cnn_cs))
		{
			print("cannot execute Query");
		}
		else
		{?>
			<table width="100%"><tr >
		<?
			$num_records=mysql_num_rows($rst_currencydetails);
			for($i_num=0;$i_num<$num_records;$i_num++)
			{
				$rst_curdetails=mysql_fetch_array($rst_currencydetails);
				$curprocessingcurrency=$rst_curdetails[22];
				?>
				<td width="30%" align="center">
					<table width="32%" height="286"  bordercolor="#999999" cellpadding="0" cellspacing="0" bgColor='#E0E0E0'>
<tr bgcolor="#FFFFFF"><td colspan="3" align="center"><font  size="+1" face="Verdana, Arial, Helvetica, sans-serif" > Currency Details</font><strong> (<?=$curprocessingcurrency?>)</strong></td></tr>
					  <tr height='25' bgcolor="#99CCFF" >
						
                      <td width="48%" height="25" class='cl1' align="center"><font color="#FFFFFF">Currency 
                        Details</font></td>
						
                      <td width="26%" class='cl1' align="center"><font color="#FFFFFF">Quantity</font></td>
						
                      <td width="26%" class='cl1' align="center"><font color="#FFFFFF">Amount</font></td>
					  </tr>
					  <tr>
							  <td height="22" class='cl1'><font face='verdana' size='1'>&nbsp;Approved</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[24]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[5])?></font></td>
					  </tr>
					  <tr>
							  <td height="26" class='cl1'><font face='verdana' size='1'>&nbsp;Declined</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[25]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[6])?></font></td>
					  </tr>
					  <tr>
							  <td height="21" class='cl1'><font face='verdana' size='1'>&nbsp;Cancelled</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[31]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[7])?></font></td>
					  </tr>
					  <tr>
							  <td height="24" class='cl1'><font face='verdana' size='1'>&nbsp;Credit</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[27]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[10])?></font></td>
					  </tr>
					  <tr>
							  <td height="23" class='cl1'><font face='verdana' size='1'>&nbsp;Charge Back</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[29]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[9])?></font></td>
					  </tr>
					  <tr>
							  <td height="21" class='cl1'><font face='verdana' size='1'>&nbsp;Total</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[23]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[4])?></font></td>
					  </tr>
					  <? if($rejected>0){ ?>
					  <tr height='25' bgcolor="#99CCFF" >
						
                      <td height="25" class='cl1' align="center" colspan="3"><font color="#FFFFFF" size="+1">Rejected 
                        Details</font></td>
						
					  </tr>
					  <tr>
							  <td height="22" class='cl1'><font face='verdana' size='1'>&nbsp;Rejected</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[44]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[45])?></font></td>
					  </tr>
					  <tr>
							  <td height="21" class='cl1'><font face='verdana' size='1'>&nbsp;Credit</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[46]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[51])?></font></td>
					  </tr>
					  <tr>
							  <td height="20" class='cl1'><font face='verdana' size='1'>&nbsp;Charge Back</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[47]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[52])?></font></td>
					  </tr>
					  <tr>
						<td class='cl1'><font face='verdana' size='1'>&nbsp;Transaction</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=$rst_curdetails[44]?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[48])?></font></td>
					  </tr>
					  <? } ?>
					  <tr height='20' bgcolor="#99CCFF" >
						
                      <td width="48%" height="25" class='cl1' align="center"><font color="#FFFFFF">Deductions</font></td>
						
                      <td width="26%" class='cl1' align="center"><font color="#FFFFFF">Rate</font></td>
						
                      <td width="26%" class='cl1' align="center"><font color="#FFFFFF">Amount</font></td>
					  </tr>
					  <tr>
							  <td height="22" class='cl1'><font face='verdana' size='1'>Credit</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($creditfee)?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[10])?></font></td>
					  </tr>
					  <tr>
							  <td height="26" class='cl1'><font face='verdana' size='1'>&nbsp;Chargeback</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($chargeback)?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[9])?></font></td>
					  </tr>
					  <tr>
							  <td height="21" class='cl1'><font face='verdana' size='1'>&nbsp;Discount</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($discountrate)?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[11])?></font></td>
					  </tr>
					  <tr>
							  <td height="24" class='cl1'><font face='verdana' size='1'>&nbsp;Reserve</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($reserverate)?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[14])?></font></td>
					  </tr>
					  <tr>
							  <td height="23" class='cl1'><font face='verdana' size='1'>&nbsp;Transaction Fee</font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($transactionrate)?></font></td>
						<td class='cl1' align="right"><font face='verdana' size='1'><?=formatMoney($rst_curdetails[12])?></font></td>
					  </tr>
					</table>
					</td>
			<?php }?>
			</tr></table>
		<? }
		 ?>
		<!-- Modification ends here-->
		</tr>
		<tr><br><td valign='top' colspan='3' >
		<table cellpadding='0' cellspacing='0' width="100%">
		<tr><br>
<?php
		if($crdtotalamount!=0)
		{
?>	       
                <td width="42%" align="center" valign="top"> 
                  <table height="330" cellpadding='0' cellspacing='0' bgColor='#E0E0E0'>
<tr align='center' bgcolor="#FFFFFF" valign="top" ><td  height="25" align='center' colspan='3'><br><P ><font face='verdana' size='2'><B>Credit Card Details</B></font><br></td></tr>
				<tr height='30' bgcolor='#99CCFF'>
				      <td width="77" height="52" align='center' class='cl1'><span class='subhd'>Card 
                        Details</span></td>
				      <td width="67" align='center' class='cl1'><span class='subhd'>Quantity</span></td>
			          <td width="91" align='center' class='cl1'><span class='subhd'>Amount 
                        </span></td>
				</tr>
				<tr>
				      <td height="26"  class='cl1'><font face='verdana' size='1'>&nbsp;Approved</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=$crdapprovedcount?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($crdapprovedamount)?></font></td>	
				</tr>
				<tr>
				      <td height="26"  class='cl1'><font face='verdana' size='1'>&nbsp;Declined</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=$crddeclinedcount?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($crddeclientamount)?></font></td>	
				</tr>
				<tr>
				      <td height="26"  class='cl1'><font face='verdana' size='1'>&nbsp;Cancelled</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=$crdcreditcount?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($crdcreditamount)?></font></td>	
				</tr>
				<tr>
				      <td height="26"  class='cl1'><font face='verdana' size='1'>&nbsp;Total</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=$crdtotalcount?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($crdtotalamount)?></font></td>	
				</tr>
				<tr>
				      <td height="26"  class='cl1'><font face='verdana' size='1'>&nbsp;Misc 
                        ADD</font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?= $crdmiscadddisc?$crdmiscadddisc:"-"?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($crdmiscadd)?></font></td>	
				</tr>
				<tr>
				      <td height="26"  class='cl1'><font face='verdana' size='1'>&nbsp;Misc 
                        SUB</font></td>
				<td align='center' class='cl1'><font face='verdana' size='1'><?= $crdmiscsubdesc?$crdmiscsubdesc: "-"?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($crdmiscsub)?></font></td>	
				</tr>
				<tr>
				      <td height="26"  class='cl1'><font face='verdana' size='1'>&nbsp;Wire 
                        Fee</font></td>
				<td align='right' class='cl1' colspan="2"><font face='verdana' size='1'><?=formatMoney($crdwirefee)?></font></td>				
				</tr>
				
				</table></td>
<?php 	}?>
		
		        <td width="58%" align="center" valign="top"> 
                  <table bgColor='#E0E0E0' cellpadding='0' cellspacing='0' width="339" >
<tr align='center' bgcolor="#FFFFFF" ><td align='center' colspan='3'><br><P ><font face='verdana' size='2'><B>Total Summary</B></font><br></td></tr>
				<? if($rejected>0){ ?>
				<tr height='30' bgcolor='#99CCFF'>
				      <td height="30" align='center' class='cl1' colspan="3"><span class='subhd'>Rejected 
                        Cancel Details</span></td>
				
				</tr>
				 
				<tr>
				      <td width="112" height="38"  class='cl1'><font face='verdana' size='1'>&nbsp;Rejected</font></td>
				      <td width="103" align='right' class='cl1'><font face='verdana' size='1'> 
                        <?=$rst_display[44]?>
                        </font></td>
				      <td width="122" align='right' class='cl1'><font face='verdana' size='1'> 
                        <?=formatMoney($rst_display[45])?>
                        </font></td>	
				</tr>
				<tr>
				      <td height="31"  class='cl1'><font face='verdana' size='1'>&nbsp;Credit</font></td>
				      <td align='right' class='cl1'><font face='verdana' size='1'> 
                        <?=$rst_display[51]?>
                        </font></td>
                      <td align='right' class='cl1'><font face='verdana' size='1'>
                        <?=formatMoney($rst_display[46])?>
                        </font></td>	
				</tr>
				<tr>
				      <td height="34"  class='cl1'><font face='verdana' size='1'>&nbsp;Charge Back</font></td>
				      <td align='right' class='cl1'><font face='verdana' size='1'>
                        <?=$rst_display[52]?>
                        </font></td>      <td align='right' class='cl1'><font face='verdana' size='1'> 
                        <?=formatMoney($rst_display[47])?>
                        </font></td>	
				</tr>
				<tr>
				      <td height="33"  class='cl1'><font face='verdana' size='1'>&nbsp;Transaction</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=$rst_display[44]?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($rst_display[48])?></font></td>	
				</tr><? } ?>
				<tr height='30' bgcolor='#99CCFF'>
				<td align='center' class='cl1'><span class='subhd'>Deductions</span></td>
				<td align='right' class='cl1'><span class='subhd'>Deducted Amount </span></td>
			   	<td align='right' class='cl1'><span class='subhd'>Amount Per Transaction </span></td>
				<tr>
				<tr>
				      <td height="31"  class='cl1'><font face='verdana' size='1'>&nbsp;Charge 
                        Back</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($chargeback)?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($reserve)?></font></td>
				</tr>
				<tr>
				      <td height="33" class='cl1'><font face='verdana' size='1'>&nbsp;Credit</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($credit)?></font></td>	
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($creditfee)?></font></td>
				</tr>
				<tr>
				      <td height="32"  class='cl1'><font face='verdana' size='1'>&nbsp;Discount</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($discount)?></font></td>	
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($discountrate)?></font></td>				
				</tr>
				<tr>
				      <td height="33" class='cl1'><font face='verdana' size='1'>&nbsp;Transaction 
                        Fee</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($discountrate)?></font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($transactionrate)?></font></td>
				</tr>
				<tr>
				      <td height="32"  class='cl1'><font face='verdana' size='1'>&nbsp;Reserve 
                        Fee</font></td>
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($reserve)?></font></td>	
				<td align='right' class='cl1'><font face='verdana' size='1'><?=formatMoney($reserverate)?></font></td>
				</tr>
				<? if($pevnetamountcrd!=""){?>
				<tr>
				      <td height="31"  class='cl1'><font face='verdana' size='1'>&nbsp;Rollover Amount</font></td>
				<td align='right' class='cl1' colspan="2"><font face='verdana' size='1'><?=formatMoney($pevnetamountcrd)?></font></td>				
				</tr>
				<? } ?>
				<tr>
				      <td height="31"  class='cl1'><font face='verdana' size='1'>&nbsp;Deduction</font></td>
				<td align='right' class='cl1' colspan="2"><font face='verdana' size='1'><?=formatMoney($deducedamount)?></font></td>				
				</tr>
				<tr>
				      <td height="34"  class='cl1'><font face='verdana' size='1'>&nbsp;Net 
                        Amount</font></td>
				<td align='right' class='cl1' colspan="2"><font face='verdana' size='1'><?=formatMoney($netamount)?></font></td>				
				</tr>
				</table>
				</td>
<?php
	   } 
?>				
				</tr>
				<tr><td colspan="3">&nbsp;</td></tr>
				</table>
				
		</td></tr></table>		
	<?php
	}
}
?>
	</td>
      </tr>
	</table>

