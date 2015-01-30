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
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,application.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//transactionverification.php:	The admin page functions for selecting the type of report view  for the company. 

include("includes/sessioncheck.php");

include("includes/header.php");
require_once("../includes/function.php");
$headerInclude = "companies";
include("includes/topheader.php");
$str_chk_uploads="";
 $str_chk_application="";
$Transtype = isset($HTTP_POST_VARS['trans_type'])?trim($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?trim($HTTP_POST_VARS['companymode']):"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?trim($HTTP_POST_VARS['companytrans_type']):"A";
$completed_uploading = isset($HTTP_POST_VARS['completed_uploading'])?trim($HTTP_POST_VARS['completed_uploading']):"A";
$completed_application = isset($HTTP_POST_VARS['completed_application'])?trim($HTTP_POST_VARS['completed_application']):"A";

$qrt_select_companies ="select distinct userId,companyname from cs_companydetails order by companyname";

if ($Transtype == "Submit")  {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companytype =="RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companytype =="ET") {
		$qrt_select_subqry = " reseller_id is null";	
	} else {
		$qrt_select_subqry = "";	
	}
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
	} else {
		if($qrt_select_subqry =="") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}

	 if($completed_uploading ==1) {
	 	if($qrt_select_merchant_qry==""){
		    $qrt_select_merchant_qry = " num_documents_uploaded=4";	
		} else{
		    $qrt_select_merchant_qry .= "and num_documents_uploaded=4";	
		}
		$str_chk_uploads="checked";
	 }
	 
	 if($completed_application ==1) {
 		if($qrt_select_merchant_qry==""){
	 		 $qrt_select_merchant_qry = " completed_merchant_application=1";
		} else {
		  	$qrt_select_merchant_qry .= " and completed_merchant_application=1";
		}
		 $str_chk_application="checked";
	 }

	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where $qrt_select_subqry $qrt_select_merchant_qry";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
//print($qrt_select_companies);
}
	
if(!($show_select_sql =mysql_query($qrt_select_companies,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
if(!isset($period))
{
  $period="p";      
}
if($period=="p")
{
   $periodstring="Start Date";
   $endperiodstring = "End Date";
   
}
	
?>
<!--<script language="javascript" src="../scripts/calendar1.js"></script>  -->
<script language="javascript" src="../scripts/general.js"></script>

<script language="javascript">

// returns true if valid Email.


function datefn(){   
checkval=true          
datestring=document.forms[0].txtDate1.value  	
	 if(!checkDateString(datestring,'mdy','/')){
		 checkval=false
		 fname='txtDate1'
	   }
	 datestring=document.forms[0].txtDate.value
	 if(!checkDateString(datestring,'mdy','/')){
		 checkval=false
		 fname='txtDate'
	   }
	  if(!checkval){
		 alert("Please enter correct date") 
		 eval("document.forms[0]." + fname + ".focus()");
		 return false
	  }
	  else{
		return true
	  }
  
}

function showType(){
	if(document.application.crorcq.options[document.application.crorcq.selectedIndex].value=="C") {
		document.application.type[0] = new Option("All","A");
		document.application.type[1] = new Option("Savings Account","S");
		document.application.type[2] = new Option("Checking Account","C");
		document.application.type.disabled = false;
	} else if(document.application.crorcq.options[document.application.crorcq.selectedIndex].value=="H") {
		document.application.type[0] = new Option("All","A");
		document.application.type[1] = new Option("Master Card","M");
		document.application.type[2] = new Option("Visa","V");
		document.application.type.disabled = false;
	}
	else{
		document.application.type.value= "";
		document.application.type.disabled = true;
	}
	return false;
}
function Displaycompany(){
	if(document.application.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.application.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.application.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = -1;
	document.getElementById('activename').selectedIndex = -1;
	document.getElementById('nonactivename').selectedIndex = -1;
}

function validation() {

var obj_form = document.application

var obj_element = obj_form.companyname
	if(obj_form.elements[4].value == "")
	{
		alert("Please select the company");
		obj_form.elements[4].focus();
		return false
	}
	
var obj_element1 

obj_element = obj_form.chk_app
obj_element1 = obj_form.chk_doc
if( !(obj_element.checked) && !(obj_element1.checked))
	{
		alert("Please select application / documents ");
		obj_element.focus();
		return false
	}

	return true;
}
</script>
<script language="JavaScript" type="text/JavaScript">
function Displaycompanytype() {
	document.application.trans_type.value="Submit";
	document.application.action = "downloadDocuments.php";
	document.application.submit();
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Download Documents / Application</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">

		<form name="application" action="downloadDocumentsNext.php" method="POST" onsubmit="return validation();">
		<input type="hidden" name="period" value="<?=$period?>"></input>
		<input type="hidden" name="trans_type" value="">
	  <table align="center" cellpadding="0" cellspacing="0" width="100%">  
	<br>
	  
	
		<tr><td colspan="2" align="center" valign="middle" width="100%">
			<table  cellpadding="0" cellspacing="2" width="100%" border="0">
				<tr>
				 <td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1">Company Type :</font></td>
				 <td align="left"  width="60%">&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompanytype();">
				<?php print func_select_mailcompanytype($companytype); ?>
					</select></td>
				</tr>
				 <tr>
				<td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1">Merchant Type :</font></td>
				<td align="left"  width="60%">&nbsp;<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
					</select></td>
				</tr>
				<tr>
				<td height="20" valign="middle" align="right" width="40%"><font face="verdana" size="1">Uploaded Documents :</font></td>
				<td align="left"  width="60%"><input type="checkbox" name="completed_uploading" value="1" onClick="javascript:Displaycompanytype(); " <?=$str_chk_uploads?>></td>
				</tr>
				<tr>
				<td height="20" valign="middle" align="right" width="40%"><font face="verdana" size="1">Completed Application :</font></td>
				<td align="left"  width="60%"><input type="checkbox" name="completed_application" value="1" onClick="javascript:Displaycompanytype();" <?=$str_chk_application?>></td>
				</tr>
				<tr>
				<td valign="middle" align="right" width="40%"><font face="verdana" size="1">Company Name :</font></td>
				 <td align="left" width="60%">&nbsp;<select id="all" name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH:150px" multiple>
				<?php func_multiselect_transaction($qrt_select_companies);
				?>
				</select>
				</td></tr>
				<tr>
				<td valign="top" align="right" width="40%"><font face="verdana" size="1">Attach 
				  :</font></td>
				<td  width="60%" align="left" valign="middle">
				  <input type="checkbox" name="chk_app" value="0">&nbsp; <font face="verdana" size="1">Merchant Application </font>&nbsp; <br>
				<input type="checkbox" name="chk_doc" value="1">&nbsp; <font face="verdana" size="1">Company Documents 
				  </font>
				</td>
				</tr>
				</table>
			</td></tr>
				
	<input type="hidden" value="" name="id" ></input>
	<input type="hidden" value="" name="cnumber"></input>
	<tr>
	 <td  height="40"  valign="bottom" align="center"colspan='2'>
		 <input type="image" id="reportview" src="../images/submit.jpg"></input>
		</td>
	</tr>
	</table>
	</form>

	</td>
 </tr>
<tr>
<td width="1%"><img src="../images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="../images/menubtmright.gif"></td>
</tr>
</table><br>
</td>
</tr>
</table>
<?php
include("includes/footer.php");
?>