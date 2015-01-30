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
// modifybankdetails.php:	This admin page functions for adding  the bank details. 
include("includes/sessioncheck.php");


$headerInclude="bank1";
include("includes/header.php");

include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$bankname = (isset($HTTP_GET_VARS["editbankname"])?$HTTP_GET_VARS["editbankname"]:"");
	$orig_bankname = (isset($HTTP_GET_VARS["orig_bankname"])?$HTTP_GET_VARS["orig_bankname"]:"");
	$bankid = (isset($HTTP_GET_VARS["bankname"])?$HTTP_GET_VARS["bankname"]:"");
	$bankemail =  (isset($HTTP_GET_VARS["email"])?$HTTP_GET_VARS["email"]:"");
	$transaction_type = (isset($HTTP_GET_VARS["transactiontype"])?$HTTP_GET_VARS["transactiontype"]:"");
	$companyid = (isset($HTTP_GET_VARS["companyname"])?$HTTP_GET_VARS["companyname"]:"");
	if($companyid=="") {
		$companyid = (isset($HTTP_GET_VARS["companyid"])?$HTTP_GET_VARS["companyid"]:"");
	}
	if($transaction_type!="") {
		$qrt_select_type = "and bank_transaction_type='$transaction_type'";
	}
	if($bankname !="") 
	{
		$qrt_select_insert = "select * from cs_company_bankdetails where bank_user_id=$companyid and bank_name='$bankname' and bank_name <> '$orig_bankname' $qrt_select_type";
			if(!($show_select_val= mysql_query($qrt_select_insert))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			if(mysql_num_rows($show_select_val)!=0) {
				$Messagedata="'".$bankname ."' bank details exists";		
				$outhtml="y";
				message($Messagedata,$outhtml,$headerInclude);					
				exit();
			} else {
				$qry_update_bank = "Update cs_company_bankdetails set bank_name ='$bankname', bank_email = '$bankemail' where bank_id=$bankid";
				if(!($show_sql =mysql_query($qry_update_bank)))
				{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

				} 
				else 
				{	
					$Messagedata="New details of '".$bankname."' bank has been set.";		
					$outhtml="y";
					message($Messagedata,$outhtml,$headerInclude);					
					exit();
				}
			}
	}

	$qrt_select_bank = "select bank_name,bank_email from cs_company_bankdetails where bank_id=$bankid";
	if(!($show_select_val= mysql_query($qrt_select_bank))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(mysql_num_rows($show_select_val) >0)
	{
		$bank_name = mysql_result($show_select_val,0,0);
		$bank_email = mysql_result($show_select_val,0,1);
	}
}
?>
<script language="JavaScript" >
function validation() {
	trimSpace(document.frmaddBank.editbankname);
	if (document.frmaddBank.editbankname.value ==""){
		alert("Please enter the Bank name.");
		document.frmaddBank.editbankname.focus();
		return false;
	}
	trimSpace(document.frmaddBank.email)
	if (document.frmaddBank.email.value =="") {
		alert("Please enter the Bank email.");
		document.frmaddBank.email.focus();
		return false;
	}

}

function trimSpace(frmElement)
{
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);

     frmElement.value = stringToTrim.substring(front, back);
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="80%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
      <tr>
        <td width="100%" height="22">&nbsp;
         
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0">
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
		<form name="frmaddBank" action="modifybankdetails.php"  method="GET" onsubmit="javascript:return validation();">
		<input type="hidden" name="companyname" value="<?=$companyid?>"></input>
		<input type="hidden" name="transactiontype" value="<?=$transaction_type?>"></input>
		<input type="hidden" name="bankname" value="<?=$bankid?>"></input>
		<input type="hidden" name="orig_bankname" value="<?=$bank_name?>"></input>
	 <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">  
		 <tr>
		  <td valign="center" align="center"  colspan="2">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Bank 
        Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="editbankname" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$bank_name?>"></td>
		  </tr>
		  <tr>
		  <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Bank 
			Email Address:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="email" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$bank_email?>"></td>
		  </tr>
		  <!--<tr>
		  <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Bank 
			Routing Code:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="routingcode" type="text" maxlength="9" style="font-family:arial;font-size:10px;width:80px" value="<?=$bank_routing?>"></td>
		  </tr>
		  <tr>
		  <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Bank 
			Email Address:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="email" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$bank_email?>"></td>
		  </tr>-->
		  </table>
		  </td>
		  </tr>
		  <tr><td align="center" colspan="2">&nbsp;&nbsp;&nbsp;<input type="image" id="addbankdetails" SRC="<?=$tmpl_dir?>/images/submit.jpg"></input>
		   </td>
		</tr>
		</table>
	</form>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
	</table>

	</td>
      </tr>
	 <tr><td>
	<br>
	
		</td></tr>
    </table>
	   </td>
	</tr>
</table>

<?php
include("includes/footer.php");
?>
