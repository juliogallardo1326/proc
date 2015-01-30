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
// viewMerchant.php:	The page functions for the view companies. 


include ("includes/sessioncheck.php");
$headerInclude="merchant";
include("includes/header.php");
require_once("../includes/function.php");

$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
if($resellerLogin!="")
{
$i_discount_rate =0; 
$i_commission_amount=0; 
$i_total_transactions=0;
$i_total_amount=0;
$chargeback_amount = 0;
$i_cancelled_count = 0;
	$qry_select = "select companyname, username, password, email, transaction_type, volumenumber, userId,phonenumber from cs_companydetails where reseller_id = $resellerLogin";
	//print($qry_select);
	$rst_select = mysql_query($qry_select,$cnn_cs);
	if (mysql_num_rows($rst_select) == 0) {
	?>
		<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
		<tr>
		<td width="83%" valign="top" align="center">&nbsp;
		<table border="0" cellpadding="0" cellspacing="0" width="50%" >
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Message</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
		<form>
		<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
		<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
		<table width='400' border='0' cellpadding='0' height="100">
		<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php print "No pending merchants" ?></font>
		</td></tr></table></td></tr>
		</table>
		<tr>
		<td width="1%"><img src="../images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="../images/menubtmright.gif"></td>
		</tr>
		</form>
		</td></tr>
		</table>
		</td></tr>
		</table>
		<?php
		include("includes/footer.php");
		exit();
	} else {
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" height="60%" >
	 <tr>
       <td width="83%" valign="top" align="center"  >
		&nbsp;
		<table border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
			<td width="100%" height="22">&nbsp;
			</td>
		</tr>
		<tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0" height="100%">
		<tr>
	<td height="22" align="left" valign="top" width="3%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	<td height="22" align="center" valign="middle" width="29%" background="../images/menucenterbg.gif" ><span class="whitehd">Merchant&nbsp;Details</span></td>
	            <td height="22" align="left" valign="top" width="8%" nowrap><img border="0" src="../images/menutopcurve.gif" width="63" height="22"></td>
	<td height="22" align="left" valign="top" width="57%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="3%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
</tr>

		<tr>
		        <td class="lgnbd" colspan="5"> 
<form name="adduser" action="addcallcenteruserfb.php"  method="post" onsubmit="javascript:return validation();">
	 <input type="hidden" name="companyid" value="<?=$sessioncompanyid?>">
	 <table width="100%" valign="top" align="left" class="lgnbd" cellspacing="0" cellpadding="0" border="0">
	 <tr>
		<td valign="middle" class="ltbtbd"  colspan="9">&nbsp;</td>
	 </tr>
	 <tr bgcolor="#78B6C2" height="25">
		<td class="cl1"><span class="subhd">&nbsp;No.</span></td>
		<td class="cl1" width="200"><span class="subhd">&nbsp;Company Name</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Email</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Phone Number</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Merchant Type</span></td>
		<td class="cl1" width="175"><span class="subhd">&nbsp;Expected Monthly Volume ($)</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Total Transaction</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Total Amount</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Transaction Rate</span></td>
		<td class="cl1"><span class="subhd">&nbsp;Discount Rate</span></td>		
	</tr>
<?php
		for($i=0;$i<mysql_num_rows($rst_select);$i++)
		{
			$str_company_name = mysql_result($rst_select,$i,0);
			$str_username = mysql_result($rst_select,$i,1);
			$str_password = mysql_result($rst_select,$i,2);
			$str_email = mysql_result($rst_select,$i,3);
			$str_pnNum = mysql_result($rst_select,$i,7);
			$str_merchant_type = mysql_result($rst_select,$i,4);
			$i_monthly_volume = mysql_result($rst_select,$i,5);
			$i_userId = mysql_result($rst_select,$i,6);
			$qry_reseller_total			="select r_reseller_trans_fees,r_reseller_discount_rate,amount,r_chargeback,status,cancelstatus,reason,r_credit from cs_transactiondetails where userid=$i_userId";
			if(!$rst_reseller_total			=mysql_query($qry_reseller_total,$cnn_cs))
			{
				print("cannot execute query");
			}
			else{
				$cancelcharge=0;
				$i_total_amount=0;
				$i_total_transactions=0; 
				$i_approved_amount=0;
				$discountamount=0;
				$i_approved_amount=0;
				$transnum        			= mysql_num_rows($rst_reseller_total);
				
				for($i_loop=0;$i_loop<$transnum;$i_loop++)
				{
					$rst_result=mysql_fetch_array($rst_reseller_total);
					 $i_reseller_transfee=$rst_result[0];
					$i_reseller_discountrate=$rst_result[1];
					 $transamount=$rst_result[2];
					
					$i_chargeback=$rst_result[3];
					$trans_status=$rst_result[4];
					$trans_cancelstatus=$rst_result[5];
					$adminapproval=$rst_result[6];
					$cancel_reason=$rst_result[7];
					$i_credit=$rst_result[8];
					if($trans_cancelstatus=='N')
					{
						$i_total_amount+=$transamount;
						$i_total_transactions+=1; 
						if($trans_status=='A')
						{
							$i_approved_amount+=$transamount;
							$discountamount+=(($transamount * $i_reseller_discountrate)/100);
							$i_commission_amount+=$i_reseller_transfee;
						}
					}
					else{
						if($adminapproval!='R'){
							if($trans_status=='A')
							{
								$i_total_amount+=$transamount;
								$i_total_transactions+=1; 
								if($cancel_reason=="Chargeback")
								{
									$cancelcharge+=$i_chargeback;
								}
								else
								{
									$cancelcharge+=$i_credit;
								}
							}
						}				
					}			
				}
				
			}
			
			$qry_reseller_fees 			= "select reseller_trans_fees from cs_companydetails where userid=$i_userId";
			$i_reseller_transfee 		= funcGetValueByQuery($qry_reseller_fees,$cnn_cs);
			
			$qry_reseller_discount 		= "select reseller_discount_rate from cs_companydetails where userid=$i_userId";
			$i_reseller_discountrate 	= funcGetValueByQuery($qry_reseller_discount,$cnn_cs);
			
			$qry_approved_transaction 	= "select count(*) from cs_transactiondetails where userid=$i_userId and status='A' and cancelstatus='N'";
			$i_approved_count		 	= funcGetValueByQuery($qry_approved_transaction,$cnn_cs);
			
			$qry_approved_transamount 	= "select sum(amount) from cs_transactiondetails where userid=$i_userId and status='A' and cancelstatus='N'";
			//$i_approved_amount			= funcGetValueByQuery($qry_approved_transamount,$cnn_cs);
			
			$qry_total_transaction 		= "select count(*) from cs_transactiondetails where userid=$i_userId";
			//$i_total_transactions 		= funcGetValueByQuery($qry_total_transaction,$cnn_cs);
			
			$qry_total_amount 			= "select sum(amount) from cs_transactiondetails where userid=$i_userId";
			//$i_total_amount 			= funcGetValueByQuery($qry_total_amount,$cnn_cs);
			
			$qry_cancelled_trans		= "select count(*) from cs_transactiondetails where userid=$i_userId and cancelstatus='Y'";
			$i_cancelled_count 			= funcGetValueByQuery($qry_cancelled_trans,$cnn_cs);
			
			$qry_chargeback_trans		= "select chargeback from cs_companydetails where userid=$i_userId";
			$chargeback_amount			= funcGetValueByQuery($qry_chargeback_trans,$cnn_cs);
			
			//$i_discount_amount 			= ((($i_approved_amount * $i_reseller_discountrate)/100)- ($i_cancelled_count * $chargeback_amount) ); 
			$i_discount_amount			=$discountamount-$cancelcharge;
			//$i_commission_amount		= $i_approved_count * $i_reseller_transfee; 
?>
			<tr height="25">
				<td valign="middle" class='cl1'><font face="verdana" size="1">&nbsp;<?=$i+1?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=$str_company_name?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=nl2br($str_email)?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=nl2br($str_pnNum)?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;
				  <?php if($str_merchant_type == "ecom") print"Ecommerce"; else if($str_merchant_type == "pmtg") print "Gateway"; else if($str_merchant_type == "tele") print"Telemarketing"; else if($str_merchant_type == "trvl") print"Travel"; else if($str_merchant_type == "phrm") print"Pharmacy"; else if($str_merchant_type == "game") print"Gaming"; else if($str_merchant_type == "adlt") print"Adult";  else print"&nbsp;";?>
				</font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=$i_monthly_volume?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=$i_total_transactions?></font></td> 
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=number_format($i_total_amount,2,".",",")?></font></td> 
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=$i_commission_amount?></font></td>
				<td valign="middle" class="cl1"><font face="verdana" size="1">&nbsp;<?=$i_discount_amount?></font></td>
			
			</tr>
<?php 	}
	}
?>
	<tr>
		<td valign="middle" class="ltbtbd" colspan="9">&nbsp;</td>
	 </tr>
	</table>	
	</form>
	</td>
	</tr>
	<tr>
	            <td width="10"><img src="../images/menubtmleft.gif"></td>
	            <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	            <td width="11" ><img src="../images/menubtmright.gif"></td>
	</tr>
	</table>
	<br>
	</td>
    </tr>
	 </table>
	 </td>
	</tr>
</table>

<?php
}
include("includes/footer.php");
?>