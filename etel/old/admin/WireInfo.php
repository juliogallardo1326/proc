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
$etel_debug_mode = 0;
include("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");

//include("includes/header.php");



$Routing_Codes[1]="ABA";
$Routing_Codes[2]="SWIFT";
$Routing_Codes[3]="Chips ID";
$Routing_Codes[4]="Sort Code";
$Routing_Codes[5]="Transit Number";
$Routing_Codes[6]="BLZ Code";
$Routing_Codes[7]="BIC Code";
$Routing_Codes[8]="Other";

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$ptype = isset($HTTP_GET_VARS['ptype'])?quote_smart($HTTP_GET_VARS['ptype']):"";

$mi_ID=quote_smart($_REQUEST['mi_ID']);
$thisdate_id = date("Ymd");
	
if($mi_ID)
{
	$mi_ID_list = explode("|",$mi_ID);
	$mi_ID_sql = implode(",",$mi_ID_list);
	$mib_wire_type = 'non-us';
	$sql = "SELECT * FROM 
	cs_merchant_invoice as mi
	left join `cs_companydetails` as cs on cs.userId=mi.mi_company_id WHERE `mi_ID` in ($mi_ID_sql) order by userId";
	$inv_details=mysql_query($sql,$cnn_cs) or dieLog(" Cannot execute query. $sql Error:".mysql_error());
	
	if(!mysql_num_rows($inv_details)) break;
	$lastuserId = 0;
	$FinalBalance = 0;
	$FinalBreakdown = "";
	while($invoiceInfo=mysql_fetch_assoc($inv_details))
	{
		$mi_pay_info = unserialize($invoiceInfo['mi_pay_info']);
		$FinalBalance += $mi_pay_info['Balance'];
		$FinalBreakdown .= $invoiceInfo['userId']." - ".$invoiceInfo['mi_title']."<BR>";
		if($lastuserId != $invoiceInfo['userId'])
		{
		if($lastuserId != 0) echo "<div style='page-break-before:always; '></div>";
		$lastuserId = $invoiceInfo['userId'];
		?>
		<hr>
		<table width="690" height="135" border="1" >
          <tr valign="top">
            <td><strong>Company:</strong></td>
            <td colspan="2" align="center"><strong>Bank Info </strong></td>
            <td colspan="2" align="center"><strong>Wire Info </strong></td>
          </tr>
          <tr valign="top">
            <td rowspan="2"><span style="font-size: 16px; "><strong>
            <?=$invoiceInfo['companyname']?></strong></span></td>
            <td><strong>Bank:</strong></td>
            <td><?=$invoiceInfo['company_bank']?>
                <?=$invoiceInfo['other_company_bank']?></td>
            <td><strong>Bank Iban: </strong></td>
            <td><?=$invoiceInfo['bank_sort_code']?>&nbsp;</td>
          </tr>
          <tr>
            <td><strong>Beneficiary Name: </strong></td>
            <td><?=$invoiceInfo['beneficiary_name']?>
            &nbsp;</td>
            <td><strong>Bank Account Number: </strong></td>
            <td><?=$invoiceInfo['bank_account_number']?>
            &nbsp;</td>
          </tr>
          <tr valign="top">
            <td rowspan="5">&nbsp;</td>
            <td><strong>Bank Account Name:</strong></td>
            <td><?=$invoiceInfo['bank_account_name']?>
            &nbsp;</td>
            <td><strong>VAT Number </strong></td>
            <td><?=$invoiceInfo['VATnumber']?>
            &nbsp;</td>
          </tr>
          <tr valign="top">
            <td><strong>Bank Address: </strong></td>
            <td><?=$invoiceInfo['bank_address']?>
            &nbsp;</td>
            <td><strong>Registration No. </strong></td>
            <td><?=$invoiceInfo['registrationNo']?>
            &nbsp;</td>
          </tr>
          <tr valign="top">
            <td><strong>Bank Phone: </strong></td>
            <td><?=$invoiceInfo['bank_phone']?>
            &nbsp;</td>
            <td><strong>Routing Number </strong></td>
            <td><?=$invoiceInfo['cd_bank_routingnumber']?>
            &nbsp;</td>
          </tr>
          <tr valign="top">
            <td><strong>Bank City: </strong></td>
            <td><?=$invoiceInfo['bank_city']?>
            &nbsp;</td>
            <td><strong>Routing Type </strong></td>
            <td><?=$Routing_Codes[$invoiceInfo['cd_bank_routingcode']]?>
            &nbsp;</td>
          </tr>
          <tr valign="top">
            <td><strong>Bank Zipcode: </strong></td>
            <td><?=$invoiceInfo['bank_zipcode']?>
            &nbsp;</td>
            <td><strong>Intermediary Routing Code </strong></td>
            <td><?=$invoiceInfo['bank_IBRoutingCode']?>
            &nbsp;</td>
          </tr>
          <tr valign="top">
            <td colspan="3" rowspan="4"><strong>Wire Notes:</strong><br>              <?=nl2br($invoiceInfo['cd_bank_instructions'])?></td>
            <td><strong>Intermediary Routing Code Type </strong></td>
            <td>            <?=$Routing_Codes[$invoiceInfo['bank_IBRoutingCodeType']]?>
            &nbsp;</td>
          </tr>
          <tr>
            <td><strong>Intermediary Bank Name </strong></td>
            <td><?=$invoiceInfo['bank_IBName']?>
            &nbsp;</td>
          </tr>
          <tr>
            <td><strong>Intermediary Bank City </strong></td>
            <td><?=$invoiceInfo['bank_IBCity']?>
            &nbsp;</td>
          </tr>
          <tr>
            <td><strong>Intermediary Bank State </strong></td>
            <td><?=$invoiceInfo['bank_IBState']?>
            &nbsp;</td>
          </tr>
          <tr align="left" valign="top">
            <td colspan="5" valign="middle">Notes:<br>
              <hr >
              <br>
            <hr><br>
            <hr></td>
          </tr>
        </table>
		<?php } ?>
		<table width="690" height="135" border="1" >
          <tr valign="top">
            <td colspan="4" align="center"><strong><?=$invoiceInfo['userId']?> - Wire Info 
              <?=$invoiceInfo['mi_title']?>
            </strong></td>
          </tr>
          <tr valign="top">
            <td rowspan="2" align="center" valign="middle">Success:</td>
            <td rowspan="2" align="center" valign="middle"><table border="1" style="width:40; height:40; ">
              <tr>
                <th scope="col">&nbsp;</th>
              </tr>
            </table></td>
            <td><strong>Sales: </strong></td>
            <td>$<?=formatMoney($mi_pay_info['Sales'])?>
            &nbsp;</td>
          </tr>
          <tr>
            <td><strong>Range: </strong></td>
            <td><?=$mi_pay_info['RangeInfo']?>
            &nbsp;</td>
          </tr>
          <tr valign="top">
            <td rowspan="2" align="center" valign="middle">Failed:</td>
            <td rowspan="2" align="center" valign="middle"><table border="1" style="width:40; height:40; ">
              <tr>
                <th scope="col">&nbsp;</th>
              </tr>
            </table></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr valign="top">
            <td><strong>Total Balance </strong></td>
            <td style="font-size:16px; "><strong> $
                  <?=formatMoney($mi_pay_info['Balance'])?>
&nbsp; </strong></td>
          </tr>
		  <?php
		  
		  //if(sizeof($mi_pay_info['BankInfo'])>1)
		  //{
		  	foreach($mi_pay_info['BankInfo'] as $bInfo)
			{
		  
		  ?>
          <tr valign="top">
            <td align="center" valign="middle"></td>
            <td align="center" valign="middle"></td>
            <td>&nbsp;&nbsp;
                <?=$bInfo['bank_name']?></td>
            <td>&nbsp;&nbsp;$<?=formatMoney($bInfo['Balance'])?>
            &nbsp;</td>
          </tr>
		  <?php
		  	$totals[$bInfo['bank_name']]+=$bInfo['Balance'];
		  	}
		 // }
		  
		  ?>
        </table>
		<?php
	}
}

	?>
	<div style='page-break-before:always; '></div>
	<hr>
	<hr>
	<hr>
		<table width="690" border="1">
          <tr>
            <td>Total Balance All Wires: </td>
            <td>$<?=formatMoney($FinalBalance)?>&nbsp;</td>
          </tr>
		  <?php
		  foreach($totals as $bank=>$total)
		  {
		  ?>
		  
          <tr>
            <td>Total Balance for <?=$bank?>: </td>
            <td>$<?=formatMoney($total)?>&nbsp;</td>
          </tr>
		  <?php
		  }
		  ?>
		  <tr><td colspan="2"><?=$FinalBreakdown?></td></tr>
        </table>
