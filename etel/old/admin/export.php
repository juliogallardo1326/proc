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
// reportBottom.php:	The admin page functions for report view of the company. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude="transactions";
include 'includes/header.php';
require_once( '../includes/function.php');


$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);

$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2); 

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;
	
	if(!isset($period))
	{
	  $period="p";      
	}
    if($period=="p")
	{
	   $periodstring="Start Date";
	   $endperiodstring = "End Date";
       
	}

$Transtype = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";

$qrt_select_companies ="select distinct userId,companyname from cs_companydetails where 1 order by companyname";

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

	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where 1 ";
	}
$qrt_select_companies="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
}
//print($qrt_select_companies);
?>
<!-- <script language="javascript" src="../scripts/calendar1.js"></script>
<script language="javascript" src="../scripts/general.js"></script> -->
<script language="javascript">
function display_list(the_sub,the_sub1) {
var listno = document.getElementsByName("exportlist[]").length;
	  if(document.getElementById(the_sub).style.display=="none") {
	 	document.getElementById(the_sub1).style.display = "none";
	 	document.getElementById(the_sub).style.display = "";
		for (var i=0;i<listno;i++){
			document.getElementsByName("exportlist[]")[i].checked=false;
			document.getElementsByName("listnum[]")[i].disabled=true;
		}
		return false;
	}
}
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
function validate() {
	if(listvalidation()){
		document.exporter.submit();
	}
}
function listvalidation() {
var isValid = false;
var obj_element = document.exporter.elements[116];
for (i = 0; i < obj_element.length; i++) {
	if(obj_element[i].selected) {
		isValid = true;
	}
}
if (!isValid) {
	alert("Please select a company");
	return false;
}

var icount = document.getElementsByName("exportlist[]").length;
var scount = document.getElementsByName("listnum[]").length;
var flag=0;
var list_1,list_2

var err = 0;
var i_err = 0;

	for(var i=0;i<icount;i++){
		if(document.getElementsByName("exportlist[]")[i].checked == true) {
			flag=1;
		}
	} 
		
	for(var k=0;k<scount;k++)
	{	    
		for(var p=k+1;p<scount;p++)
		{
		  list_1 = document.getElementsByName("listnum[]")[k].options[document.getElementsByName("listnum[]")[k].selectedIndex].value
		  list_2 = document.getElementsByName("listnum[]")[p].options[document.getElementsByName("listnum[]")[p].selectedIndex].value	
		  if (list_1 != "" && list_2 != "")
		   { 
			 if (list_1 == list_2){
			  alert("Please select another order number");
			  document.getElementsByName("listnum[]")[k].focus();
			  return false;
				
			 }	
	 
			} 
		}
	}	
 	
	if(flag == 0 ) {
		alert("Please select the export list");
		return false;
	}
		
	 else {
		return true;
	}
	


}
function func_enable_select(list_numb) {
	if(document.getElementById(list_numb).disabled) {
		document.getElementById(list_numb).disabled=false;
	} else {
		document.getElementById(list_numb).disabled=true;
	}
}
function Displaycompany(){
	if(document.exporter.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.exporter.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.exporter.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = -1;
	document.getElementById('activename').selectedIndex = -1;
	document.getElementById('nonactivename').selectedIndex = -1;
}

function Displaycompanytype() {
	document.exporter.trans_type.value="Submit";
	document.exporter.action = "export.php";
	document.exporter.submit();
}

</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.exporter;
	if (obj_element.name == "from_date"){
		obj_form.opt_from_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_from_month.selectedIndex = monthSelected ;
		obj_form.opt_from_year.selectedIndex = func_returnselectedindex(yearSelected) ;
	}
	if (obj_element.name == "from_to"){
		obj_form.opt_to_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_to_month.selectedIndex = monthSelected ;
		obj_form.opt_to_year.selectedIndex = func_returnselectedindex(yearSelected);
	}
}
function func_returnselectedindex(par_selected)
{
	var dt_new =  new Date();
	var str_year = dt_new.getFullYear()
	for(i=2003,j=0;i<str_year+10;i++,j++)
	{
		if (i==par_selected)
		{
			return j;
		}
	}
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center">
    &nbsp;
    <table width="96%" border="0" cellspacing="0" cellpadding="0">
<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Export</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5"><br>
		<form name="exporter"  method="POST" action="excelout.php">
		<input type="hidden" name="trans_type" value="">
	  <table align="center" cellpadding="0" cellspacing="0" border="0" width="98%">  
	  <tr>
		          <td height="30" valign="middle"   align="left" width="118"><font face="verdana" size="1"> 
                    <?=$periodstring?>
                    </font></td>
		          <td align="left" width="225"  nowrap>&nbsp;
                    <!--<input type="text" name="txtDate" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input type="button" value="..." onclick="init()" style="font-family:verdana;font-size:10px;">-->
                    <select name="opt_from_month" style="font-size:10px">
			<?php func_fill_month($i_from_month); ?>
		   </select>
			<select name="opt_from_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_from_day); ?>
		   </select></font>
			 <select name="opt_from_year" style="font-size:10px">
			<?php func_fill_year($i_from_year); ?>
		   </select>
			<input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
			<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(380,110,document.exporter.from_date)">
		  </td>
		  <td rowspan="4" align="top" width="30"></td>
		  <td rowspan="4" align="top">
		  <table width="100%">
			<tr>
				<td height="20"  valign="top" align="left" width="151"><font face="verdana" size="1">Alphabetize</font>&nbsp; 
				<input type="checkbox" name="order" value="Y"></td>
			</tr>
			<tr>
				<td height="20"  align="left" width="468" valign="top"><font face="verdana" size="1">Export List</font></td>
			</tr>  
			
		  <tr><td valign="top" height="230">
	<div id="Check" style="display:yes">
	<table class="lgnbd" width="100%"><tr>
       <td><select name="listnum[]" id="list1" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox"name="exportlist[]" value="userId" onClick="javascript:func_enable_select('list1');" ><font face="Verdana" size="1">Company Name</font></td>
		<td><select name="listnum[]" id="list2" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="voiceAuthorizationno" onClick="javascript:func_enable_select('list2');" ><font face="Verdana" size="1">Voice Authorization #</font></td>
		<td><select name="listnum[]" id="list3" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="transactionDate" onClick="javascript:func_enable_select('list3');" ><font face="Verdana" size="1">Transaction Date</font></td></tr><tr>				  
		<td><select name="listnum[]" id="list4" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="name" onClick="javascript:func_enable_select('list4');" ><font face="Verdana" size="1">First Name</font></td>
		<td><select name="listnum[]" id="list5" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="surname" onClick="javascript:func_enable_select('list5');" ><font face="Verdana" size="1">Last Name</font></td>			  
		<td><select name="listnum[]" id="list6" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="address" onClick="javascript:func_enable_select('list6');" ><font face="Verdana" size="1">Address</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list7" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="city" onClick="javascript:func_enable_select('list7');" ><font face="Verdana" size="1">City</font></td>			  
		<td><select name="listnum[]" id="list8" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="state" onClick="javascript:func_enable_select('list8');" ><font face="Verdana" size="1">State</font></td>			  
		<td><select name="listnum[]" id="list9" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="country" onClick="javascript:func_enable_select('list9');" ><font face="Verdana" size="1">Country</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list10" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="zipcode" onClick="javascript:func_enable_select('list10');" ><font face="Verdana" size="1">Zipcode</font></td>			  
		<td><select name="listnum[]" id="list11" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="phonenumber" onClick="javascript:func_enable_select('list11');" ><font face="Verdana" size="1">Telephone #</font></td>			  
		<td><select name="listnum[]" id="list12" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="checktype" onClick="javascript:func_enable_select('list12');" ><font face="Verdana" size="1">Check Type</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list13" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="CCnumber" onClick="javascript:func_enable_select('list13');" ><font face="Verdana" size="1">Check #</font></td>			  
		<td><select name="listnum[]" id="list14" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="amount" onClick="javascript:func_enable_select('list14');" ><font face="Verdana" size="1">Amount</font></td>			  
		<td><select name="listnum[]" id="list15" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="bankname" onClick="javascript:func_enable_select('list15');" ><font face="Verdana" size="1">Bank Name</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list16" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="bankroutingcode" onClick="javascript:func_enable_select('list16');" ><font face="Verdana" size="1">Bank Routing #</font></td>
		<td><select name="listnum[]" id="list17" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="bankaccountnumber" onClick="javascript:func_enable_select('list17');" ><font face="Verdana" size="1">Account #</font></td>			  
		<td><select name="listnum[]" id="list18" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="accounttype" onClick="javascript:func_enable_select('list18');" ><font face="Verdana" size="1">Account Type</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list19" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="billingDate" onClick="javascript:func_enable_select('list19');" ><font face="Verdana" size="1">Billing Date</font></td>			  
		<td><select name="listnum[]" id="list20" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="chequedate" onClick="javascript:func_enable_select('list20');" ><font face="Verdana" size="1">Check Date</font></td>			  
		<td><select name="listnum[]" id="list21" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="approvaldate" onClick="javascript:func_enable_select('list21');" ><font face="Verdana" size="1">Approval Date</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list22" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="cancellationDate" onClick="javascript:func_enable_select('list22');" ><font face="Verdana" size="1">Cancellation Date</font></td>			  
		<td><select name="listnum[]" id="list23" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="shippingTrackingno" onClick="javascript:func_enable_select('list23');" ><font face="Verdana" size="1">Shipping #</font></td>			  
		<td><select name="listnum[]" id="list24" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="socialSecurity" onClick="javascript:func_enable_select('list24');" ><font face="Verdana" size="1">Social Security #</font></td></tr><tr>
		<td><select name="listnum[]" id="list25" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="driversLicense" onClick="javascript:func_enable_select('list25');" ><font face="Verdana" size="1">Drivers License #</font></td>			  
		<td><select name="listnum[]" id="list26" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="licensestate" onClick="javascript:func_enable_select('list26');" ><font face="Verdana" size="1">License State</font></td>			  
		<td><select name="listnum[]" id="list27" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox" name="exportlist[]" value="misc" onClick="javascript:func_enable_select('list27');" ><font face="Verdana" size="1">Miscellaneous</font></td></tr>			  
	 </table>
	</div>
	<div id="Credit" style="display:none">
	<table class="lgnbd" width="100%"><tr>
        <td><select name="listnum[]" id="list31" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="userId"  onClick="javascript:func_enable_select('list31');" ><font face="Verdana" size="1">Company Name</font></td>
		<td><select name="listnum[]" id="list32" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="voiceAuthorizationno"  onClick="javascript:func_enable_select('list32');" ><font face="Verdana" size="1">Voice Authorization #</font></td>
		<td><select name="listnum[]" id="list33" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="transactionDate" onClick="javascript:func_enable_select('list33');" ><font face="Verdana" size="1">Transaction Date</font></td></tr><tr>				  
		<td><select name="listnum[]" id="list34" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="name" onClick="javascript:func_enable_select('list34');" ><font face="Verdana" size="1">First Name</font></td>
		<td><select name="listnum[]" id="list35" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="surname" onClick="javascript:func_enable_select('list35');" ><font face="Verdana" size="1">Last Name</font></td>			  
		<td><select name="listnum[]" id="list36" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="address" onClick="javascript:func_enable_select('list36');" ><font face="Verdana" size="1">Address</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list37" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="city" onClick="javascript:func_enable_select('list37');" ><font face="Verdana" size="1">City</font></td>			  
		<td><select name="listnum[]" id="list38" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="state" onClick="javascript:func_enable_select('list38');" ><font face="Verdana" size="1">State</font></td>			  
		<td><select name="listnum[]" id="list39" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="country" onClick="javascript:func_enable_select('list39');" ><font face="Verdana" size="1">Country</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list40" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="zipcode" onClick="javascript:func_enable_select('list40');" ><font face="Verdana" size="1">Zipcode</font></td>			  
		<td><select name="listnum[]" id="list41" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="phonenumber" onClick="javascript:func_enable_select('list41');" ><font face="Verdana" size="1">Telephone #</font></td>			  
		<td><select name="listnum[]" id="list42" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="email" onClick="javascript:func_enable_select('list42');" ><font face="Verdana" size="1">Email Address</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list43" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="validupto" onClick="javascript:func_enable_select('list43');" ><font face="Verdana" size="1">Card Expiry Date</font></td>			  
		<td><select name="listnum[]" id="list44" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="CCnumber" onClick="javascript:func_enable_select('list44');" ><font face="Verdana" size="1">Card #</font></td>			  
		<td><select name="listnum[]" id="list45" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="amount" onClick="javascript:func_enable_select('list45');" ><font face="Verdana" size="1">Amount</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list46" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="cvv" onClick="javascript:func_enable_select('list46');" ><font face="Verdana" size="1">CVV #</font></td>			  
		<td><select name="listnum[]" id="list47" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="billingDate" onClick="javascript:func_enable_select('list47');" ><font face="Verdana" size="1">Billing Date</font></td>			  
		<td><select name="listnum[]" id="list48" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="approvaldate" onClick="javascript:func_enable_select('list48');" ><font face="Verdana" size="1">Approval Date</font></td></tr><tr>			  
		<td><select name="listnum[]" id="list49" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="cancellationDate" onClick="javascript:func_enable_select('list49');" ><font face="Verdana" size="1">Cancellation Date</font></td>			  
		<td><select name="listnum[]" id="list50" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="shippingTrackingno" onClick="javascript:func_enable_select('list50');" ><font face="Verdana" size="1">Shipping #</font></td>			  
		<td><select name="listnum[]" id="list51" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="socialSecurity" onClick="javascript:func_enable_select('list51');" ><font face="Verdana" size="1">Social Security #</font></td></tr><tr>
		<td><select name="listnum[]" id="list52" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="driversLicense" onClick="javascript:func_enable_select('list52');" ><font face="Verdana" size="1">Drivers License #</font></td>			  
		<td><select name="listnum[]" id="list53" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="licensestate" onClick="javascript:func_enable_select('list53');" ><font face="Verdana" size="1">License State</font></td>			  
		<td><select name="listnum[]" id="list54" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(24);?></select><input type="checkbox" name="exportlist[]" value="misc" onClick="javascript:func_enable_select('list54');" ><font face="Verdana" size="1">Miscellaneous</font></td></tr>			  
	 </table>
	</div>
	</td></tr></table>		  
		  </td>
	  </tr>
      <tr>
	              <td height="30" valign="middle" align="left" width="118"><font face="verdana" size="1"> 
                    <?=$endperiodstring?>
                    </font></td>
	      <td align="left" width="225"  height="30"  nowrap>&nbsp; 
                    <!--<input type="text" name="txtDate1" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init1()">-->
            <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
			  </select>
			<select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
			  </select>
			  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
			  </select>
			  <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
			  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(380,140,document.exporter.from_to)">
			  </td>   
	    	  </tr>
			<tr><td colspan="2" align="center" valign="middle">
			<table cellpadding="0" cellspacing="0" border="0"width="100%">
				<tr>
				<td height="30" valign="middle" align="left"><font face="verdana" size="1">Company 
                          Type&nbsp;</font></td>
				 <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px"  onChange="Displaycompanytype();">
					<?php print func_select_mailcompanytype($companytype); ?>
					</select></td>
				</tr>
				<tr>
				<td height="30" valign="middle" align="left"><font face="verdana" size="1">Merchant 
                          Type&nbsp;</font></td>
				 <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
				</select></td>
				</tr>
				<tr><td height="30" valign="middle" align="left"><font face="verdana" size="1">Select 
                                  Company&nbsp;</font></td>
				 <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="all" name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
				<?php func_multiselect_transaction($qrt_select_companies);
				?>
				</select>&nbsp;&nbsp;
				</td></tr>
			</table>
			</td></tr>				
			<tr>
	 				<td colspan="2"> <table width="307" border="0" align="left">
					<tr> 
                        <td  height="30"  valign="middle" align="left" width="115"><font face="verdana" size="1">Pending</font></td><td>
						<input type="checkbox" name="trans_ptype" value="P"></td>
                        <td  height="30"  valign="middle" align="left"> <font face="verdana" size="1">Pass</font></td><td>
                        <input type="checkbox" name="trans_pass" value="PA"></td>
                     </tr>
                      <tr> 
                        <td  height="30"  valign="middle" align="left"><font face="verdana" size="1">No Pass</font></td><td>
						<input type="checkbox" name="trans_nopass" value="NP"></td>
                        <td  height="30"  valign="middle" align="left"> <font face="verdana" size="1">Declined</font></td><td>
                        <input type="checkbox" name="trans_dtype" value="D"></td>
                      </tr>
                      <tr> 
                        <td  height="30"  valign="middle" align="left" ><font face="verdana" size="1">Cancelled</font></td><td>
						<input type="checkbox" name="trans_ctype" value="C"></td>
                        <td  height="30"  valign="middle" align="left" ><font face="verdana" size="1">Approved date</font></td><td>
                        <input type="radio" name="daterange" value="A"></td>
                      </tr>
                      <tr>
                        <td height="30"  valign="middle" align="left" ><font face="verdana" size="1">Order entry</font></td><td>
						<input type="radio" name="daterange" value="O" checked></td>
                        <td  height="30"  valign="middle" align="left"><font face="verdana" size="1">Set bill date</font></td><td>
                        <input type="radio" name="daterange" value="S"></td>
                      </tr>
                      <tr> 
                        <td  height="30"  valign="middle" align="left" ><font face="verdana" size="1">Check </font></td><td>
                        <input type="radio" name="checkorcard" value="C" checked onClick="javascript:display_list('Check','Credit');"></td>
                        <td  height="30"  valign="middle" align="left"><font face="verdana" size="1">Credit card</font></td><td>
                        <input type="radio" name="checkorcard" value="H" onClick="javascript:display_list('Credit','Check');"></td>
                      </tr>
                    </table></td>
	</tr>
	<tr>
	 <td  height="30"  valign="bottom" align="center"  colspan='4'>
		 <a href="javascript: validate();"><img SRC="<?=$tmpl_dir?>/images/exportfile.jpg" border="0"></a></input>
		</td>
	</tr>
	</table>
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

<?php
include("includes/footer.php");
	
?>