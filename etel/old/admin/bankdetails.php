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
// bankdetails.php:	This admin page functions for adding  the bank details. 
include("includes/sessioncheck.php");


$headerInclude="emailReceipts";
include("includes/header.php");
include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
	$bank_name = "";
	$bank_routing = "";
	$bank_email = "";
	$bankname = (isset($HTTP_POST_VARS["bankname"])?$HTTP_POST_VARS["bankname"]:"");
	//$bankroutingcode =  (isset($HTTP_POST_VARS["routingcode"])?$HTTP_POST_VARS["routingcode"]:"");
	$bankemailid =  (isset($HTTP_POST_VARS["email"])?$HTTP_POST_VARS["email"]:"");
	$Mode = 	(isset($HTTP_POST_VARS["Mode"])?$HTTP_POST_VARS["Mode"]:"");
	$Mode_Type = (isset($HTTP_POST_VARS["Mode_Type"])?$HTTP_POST_VARS["Mode_Type"]:"");
	$Edit_id = (isset($HTTP_POST_VARS["Edit_id"])?$HTTP_POST_VARS["Edit_id"]:"");
	$Delete_id = (isset($HTTP_GET_VARS["Delete_id"])?$HTTP_GET_VARS["Delete_id"]:"");
	$companyid = (isset($HTTP_GET_VARS["companyname"])?$HTTP_GET_VARS["companyname"]:"");
	$bk_cc_bank_enabled = (isset($HTTP_GET_VARS["bk_cc_bank_enabled"])?$HTTP_GET_VARS["bk_cc_bank_enabled"]:"");
	if($companyid=="") {
		$companyid = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
	}
	if($Mode=="") {
		$Mode = 	(isset($HTTP_GET_VARS["Mode"])?$HTTP_GET_VARS["Mode"]:"");
	}
	if($Edit_id=="") {
		$Edit_id = (isset($HTTP_GET_VARS["Edit_id"])?$HTTP_GET_VARS["Edit_id"]:"");
	}
	//if(($bankname !="") && ($bankroutingcode !="") && ($bankemailid!="")) 
	if(($bankname !="") && ($bankemailid!="")) 
	{
		if($Mode_Type=="Edit") 
		{
			//$qry_update_bank = "Update cs_bankdetails set bank_name ='$bankname',bank_routing_code='$bankroutingcode',bank_email='$bankemailid' where bank_id =$Edit_id";
			$qry_update_bank = "Update cs_bankdetails set bk_cc_bank_enabled='$bk_cc_bank_enabled',bank_name ='$bankname',bank_email='$bankemailid' where bank_id =$Edit_id";
			if(!($show_sql =mysql_query($qry_update_bank)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			} 
			else 
			{	
				$Mode="";
				$Messagedata="New details of '".$bankname."' has been set.";		
				$outhtml="y";
				message($Messagedata,$outhtml,$headerInclude);					
				exit();
			}
		} else {
			
			//$qry_insert_bank = "insert into cs_bankdetails (bank_name,bank_routing_code,bank_email,bank_user_id) values('$bankname','$bankroutingcode','$bankemailid',$companyid)";
			$qry_insert_bank = "insert into cs_bankdetails (bk_cc_bank_enabled,bank_name,bank_email,bank_user_id) values('$bk_cc_bank_enabled','$bankname','$bankemailid',$companyid)";
			if(!($show_sql =mysql_query($qry_insert_bank)))
			{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

			}
			else
			{
				$Messagedata= "'".$bankname ."' bank details has been added.";		
				$outhtml="y";
				message($Messagedata,$outhtml,$headerInclude);					
				exit();
			}
			if(mysql_num_rows($show_sql)==0) {
				$Messagedata="'".$bankname ."' bank details exists";		
				$outhtml="y";
				message($Messagedata,$outhtml,$headerInclude);					
				exit();
			}
		}
	}
	
	if ($Mode == "Delete") {
		$qry_delete_bank = "Delete from cs_bankdetails where bank_id=$Delete_id";
		if(!($show_sql =mysql_query($qry_delete_bank)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");
		
		}
		else
		{
			$Messagedata= "Bank details has been deleted.";		
			$outhtml="y";
			message($Messagedata,$outhtml,$headerInclude);					
			exit();
		}
	}
	
	if($Mode =="Edit") {
		$qry_select_bank ="select * from cs_bankdetails where bank_id=$Edit_id";
		if(!($edit_sql_qry =mysql_query($qry_select_bank)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		} else {
			while($edit_val = mysql_fetch_array($edit_sql_qry)) 
			{		
				$bank_name = $edit_val[1];
				$bank_routing = $edit_val[2];
				$bank_email = $edit_val[3];
			}
		}
	}
	
	$qry_select_bank ="select * from cs_bankdetails where bank_user_id=$companyid and gateway_id = -1 order by bank_id";
	if(!($show_sql_qry =mysql_query($qry_select_bank)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	


?>
<script language="JavaScript" >
function validation() {
	trimSpace(document.frmaddBank.bankname)
	if (document.frmaddBank.bankname.value ==""){
		alert("Please enter the Bank name.");
		document.frmaddBank.bankname.focus();
		return false;
	}
	/*trimSpace(document.frmaddBank.routingcode)
	if (document.frmaddBank.routingcode.value =="") {
		alert("Please enter the Bank routing code.");
		document.frmaddBank.routingcode.focus();
		return false;
	}
	if (document.frmaddBank.routingcode.value.length < 9) {
		alert("Please enter the correct Bank routing code.");
		document.frmaddBank.routingcode.focus();
		return false;
	}*/

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
		        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd"><?= $Mode == "Edit" ? "Edit Email Receipt" : "Add Email Receipt" ?></span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5"><br>
		<form name="frmaddBank" action="bankdetails.php"  method="POST" onsubmit="javascript:return validation();">
		<input type="hidden" name="Mode_Type" value="<?=$Mode?>"></input>
		<input type="hidden" name="Edit_id" value="<?=$Edit_id?>"></input>
		<input type="hidden" name="companyname" value="<?=$companyid?>"></input>
	  <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%">  
		 <tr>
		  <td valign="center" align="center"  colspan="2">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Bank 
        Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="bankname" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$bank_name?>"></td>
		  </tr>
		  <!--<tr>
		  <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Bank 
			Routing Code:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="routingcode" type="text" maxlength="9" style="font-family:arial;font-size:10px;width:80px" value="<?=$bank_routing?>"></td>
		  </tr>-->
		  <tr>
		  <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Bank 
			Email Address:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="email" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$bank_email?>"></td>
		  </tr> 
		  <tr>
		  <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Bank 
			Email Address:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="" type="checkbox" value="<?=($bk_cc_bank_enabled?"checked":"")?>"></td>
		  </tr> 
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
	<table width="100%" valign="top" align="left" class="lgnbd" cellspacing="1">
<tr bgcolor="#CCCCCC">
			    <td><span class="subhd">No.</span></td>
				<td><span class="subhd">BankName</span></td>
			    <!--<td><span class="subhd">Routing Code</span></td>-->
			    <td><span class="subhd">Email Address</span></td>
		    	<td><span class="subhd">Edit</span></td>
			    <td><span class="subhd">Delete</span></td>
		</tr>
<?php
		$i=0;
		while($show_val = mysql_fetch_array($show_sql_qry)) 
		{
			$i=$i+1;	
?>
			<tr>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$i?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$show_val[1]?></font></td>
			<!--<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$show_val[2]?></font></td>-->
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><?=$show_val[3]?></font></td>
			<td valign="middle" class="ltbtbd"><font face="verdana" size="1"><a href="bankdetails.php?Mode=Edit&Edit_id=<?=$show_val[0]?>&companyname=<?=$companyid?>">Edit</a></font></td>
			<td valign="middle" class="ltbtbd1"><font face="verdana" size="1"><a href="bankdetails.php?Mode=Delete&Delete_id=<?=$show_val[0]?>&companyname=<?=$companyid?>">Delete</a></font></td>
			</tr>
<?php
		}
?>
		</table>
		</td></tr>
    </table>
	   </td>
	</tr>
</table>

<?php
include("includes/footer.php");
}
?>
