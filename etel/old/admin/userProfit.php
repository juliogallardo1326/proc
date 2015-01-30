<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// viewcompany.php:	The admin page functions for viewing the company.
$allowBank=true;
$showCurrentProfit=false;
include("includes/sessioncheck.php");
$headerInclude = "companies";
include("includes/header.php");
require_once("../includes/projsetcalc.php");
$calcInfo = array();
$calcInfo['po_user_ID']=$adminInfo['userid'];
$userProfit = calcUserProfit($calcInfo);



foreach($userProfit['options'] as $option)
{


?>
<table width="200" border="1">
<tr>
<td colspan="5"><?=$option['description']?></td>
</tr>
  <tr>
  <td>Bank</td>
  <td>Total Wired</td>
  <td>Total Commission</td>
  <td>Percent Available</td>
  <td>Final Commission</td>
  </tr>


<?php

	foreach($option['bybank'] as $bybank)
	{
		echo "<tr><td>".$bybank['bank_name']."</td><td>".$bybank['Sales']."</td><td>".$bybank['TotalProfit']."</td><td>%".($bybank['Commission_Percent_Available']*100)."</td><td>".$bybank['CommissionProfit_Available']."</td></tr>";
	}
	echo "<tr><td>Total</td><td colspan='4'>".$option['CommissionProfit']."</td></tr></table>";
}
print_r($userProfit);

include("includes/footer.php");

?>