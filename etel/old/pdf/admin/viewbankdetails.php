<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// viewbankdetails.php:	This admin page functions for adding  the bank details. 
include("includes/sessioncheck.php");


$headerInclude="bank1";
include("includes/header.php");

include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$qrt_select_type ="";
if($sessionAdmin!="")
{
	$companyid = (isset($HTTP_GET_VARS["companyname"])?$HTTP_GET_VARS["companyname"]:"");
	$transaction_type = (isset($HTTP_GET_VARS["transactiontype"])?$HTTP_GET_VARS["transactiontype"]:"");
	if($transaction_type!="") {
		$qrt_select_type = "and bank_transaction_type='$transaction_type'";
	}
	
	$qry_select_bank ="select bank_user_id,bank_name,bank_transaction_type,companyname,bank_email from cs_company_bankdetails as a, cs_companydetails  as b where a.bank_user_id=userId and bank_user_id=$companyid $qrt_select_type order by bank_id";
	if(!($show_sql_qry =mysql_query($qry_select_bank)))
	{
		print(mysql_errno().": ".mysql_error());
		print("Cannot execute query");
		exit();
	}
	


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="67%"  >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="100%" height="85%">
      <tr>
        <td width="100%" height="22">&nbsp;
         
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="center" >
<?php 	if(mysql_num_rows($show_sql_qry) >0 ) { ?>
		<table border="0" cellspacing="0" cellpadding="0" width="50%">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Bank 
                  Details </span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5"><br>
		<table width="100%" valign="top"align="center" class="lgnbd" cellspacing="1" cellpadding="1">
		<tr bgcolor="#CCCCCC">
			    <td height='20'><span class="subhd">No.</span></td>
				<td><span class="subhd">BankName</span></td>
			    <!--<td><span class="subhd">Routing Code</span></td>-->
			    <td><span class="subhd">Email Address</span></td>
			    <td><span class="subhd">Transaction Type</span></td>
		    	<td><span class="subhd">Company Name</span></td>
		</tr>
<?php
		$i=0;
		while($show_val = mysql_fetch_array($show_sql_qry)) 
		{
			$i=$i+1;	
?>
			<tr>
			<td valign="middle" class="leftbottomright"  height='20'><font face="verdana" size="1"><?=$i?></font></td>
			<td valign="middle" class="rightbottomtd"><font face="verdana" size="1"><?=$show_val[1]?></font></td>
			<!--<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$show_val[2]?></font></td>-->
			<td valign="middle" class="rightbottomtd"><font face="verdana" size="1"><?=$show_val[4]?></font></td>
			<td valign="middle" class="rightbottomtd"><font face="verdana" size="1"><?=$show_val[2]?></font></td>
			<td valign="middle" class="rightbottomtd"><font face="verdana" size="1"><?=$show_val[3]?></font></td>
			</tr>
<?php
		}
 ?>
<tr><td colspan="5" align="center" height="40"><a href="viewcompany_banklist.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td></tr>
</table></td></tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
</table>
<?php } else { 
	print"<table border='0' cellspacing='0' cellpadding='0' width='100%' height='550'><tr><td>";
		$Messagedata= "No banks assigned for this company.";		
		$outhtml="y";
		message($Messagedata,$outhtml,$headerInclude);					
		exit();
	print "</td></tr></table>";
}
?>
</td></tr></table>
<?php
include("includes/footer.php");
}
?>


