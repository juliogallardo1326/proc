<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// reportBottom.php:	The admin page functions for report view of the company. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
include 'includes/header.php';
require_once( '../includes/function.php');

$headerInclude="transactions";
include 'includes/topheader.php';
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
function listvalidation() {
var icount = document.getElementsByName("exportlist[]").length;
var flag=0;
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
			  alert("Please select another order");
			  document.getElementsByName("listnum[]")[k].focus();
			  return false;
				
			 }	
	 
			} 
		}
	}	
 
	if(flag == 0 ) {
		alert("Please select the export list");
		return false;
	} else {
		return true;
	}

//alert(document.getElementsByName("exportlist[]")[0].checked);
//alert(document.getElementsByName("exportlist[]").length);
//return false;

}
function func_enable_select(list_numb) {
	if(document.getElementById(list_numb).disabled) {
		document.getElementById(list_numb).disabled=false;
	} else {
		document.getElementById(list_numb).disabled=true;
	}
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center">
    &nbsp;
    <table width="50%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Export</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5"><br>
		<form name="export"  method="POST" action="excelout.php" onsubmit="javascript: return listvalidation();">
	  <table align="center" cellpadding="0" cellspacing="0" width="100%">  
	  <tr>
		  <td height="30" valign="middle"   align="right" width="40%"><font face="verdana" size="1"><?=$periodstring?></font></td>
		  <td align="left" width="60%"  height="30" >&nbsp;
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
		  </td>
	  </tr>
      <tr>
		  <td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1"><?=$endperiodstring?></font></td>
		  <td align="left" width="60%"  height="30"  >&nbsp;
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
		  </td>   
      </tr>
	 <tr>
	  	<td height="30"  valign="middle" align="right"><font face="verdana" size="1">Company</font></td>
		<td align="left" >&nbsp;&nbsp;<select name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 140px" multiple>
			<option value='A' selected>All Companies</option>
<?php	
		$qrt_select_company = "select userid,companyname from cs_companydetails order by companyname";
		
    	if(!($rstSelectCompany = mysql_query($qrt_select_company,$cnn_cs)))
		{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}
		for($iLoop = 0;$iLoop<mysql_num_rows($rstSelectCompany);$iLoop++)
		{?>	
			<option value='<?=mysql_result($rstSelectCompany,$iLoop,0)?>'><?=mysql_result($rstSelectCompany,$iLoop,1)?></option>	  
<?		} ?>
  		</select>
		</font>
		</td>
	</tr>
	<tr>
	              <td colspan="2"> <table width="100%" align="center" border="0">
                      <tr> 
                        <td  height="30"  valign="middle" align="right" width="47%"> 
                          <font face="verdana" size="1">Pending&nbsp;</font> <input type="checkbox" name="trans_ptype" value="P"> 
                          &nbsp;</td>
                        <td  height="30"  valign="middle" align="right" width="25%"> 
                          <font face="verdana" size="1">Pass&nbsp;</font> <input type="checkbox" name="trans_pass" value="PA"> 
                        </td>
                        <td  height="30"  valign="middle" align="center" width="25%"> 
                        </td>
                      </tr>
                      <tr> 
                        <td  height="30"  valign="middle" align="right"><font face="verdana" size="1">No 
                          Pass</font>
                          <input type="checkbox" name="trans_nopass" value="NP">
                          &nbsp;</td>
                        <td  height="30"  valign="middle" align="right"> <font face="verdana" size="1">Declined&nbsp;</font> 
                          <input type="checkbox" name="trans_dtype" value="D"> 
                        </td>
                        <td  height="30"  valign="middle" align="center"></td>
                      </tr>
                      <tr> 
                        <td  height="30"  valign="middle" align="right" ><font face="verdana" size="1">Cancelled</font> 
                          <input type="checkbox" name="trans_ctype" value="C"> 
                          &nbsp;</td>
                        <td  height="30"  valign="middle" align="right" ><font face="verdana" size="1"> 
                          Approved date <input type="radio" name="daterange" value="A"></font></td>
                        <td  height="30"  valign="middle" align="center">&nbsp;</td>
                      </tr>
                      <tr>
                        <td  height="30"  valign="middle" align="right" ><font face="verdana" size="1">Order entry
						  <input type="radio" name="daterange" value="O" checked></font>&nbsp;&nbsp;</td>
                        <td  height="30"  valign="middle" align="right"><font face="verdana" size="1">Set 
                          bill date</font> <input type="radio" name="daterange" value="S"></td>
                      </tr>
                      <tr> 
                        <!--  <td  height="30"  valign="middle" align="right" ><font face="verdana" size="1">All 
						<input type="radio" name="checkorcard" value="A" checked>&nbsp;</font>&nbsp;</td> -->
                        <td  height="30"  valign="middle" align="right" ><font face="verdana" size="1">Check 
                          <input type="radio" name="checkorcard" value="C" checked onClick="javascript:display_list('Check','Credit');"></font>&nbsp;&nbsp;</td>
                        <td  height="30"  valign="middle" align="right"><font face="verdana" size="1">Credit 
                          card</font>&nbsp;&nbsp;<input type="radio" name="checkorcard" value="H" onClick="javascript:display_list('Credit','Check');"></td>
                      </tr>
					  <tr>
					  <td height="30"  valign="middle" align="right" width="230"><font face="verdana" size="1">Alphabetize </font> &nbsp;<input type="checkbox" name="order" value="Y"></td>
					  <td align="left"></td>
					  </tr>
                    </table></td>
	</tr>
	<tr><td height="30"  align="center" width="116"><font face="verdana" size="1">Export List</font></td></tr>
	<tr><td colspan="4" align="center" valign="middle">
	<table><tr>
	<td align="right" colspan="4">
	<div id="Check" style="display:yes">
	<table class="lgnbd" width="100%"><tr>
       <td valign="middle"><select name="listnum[]" id="list1" style="font-family:verdana;font-size:8px;WIDTH:30px" disabled><option value="" selected>&nbsp;</option><?php print func_numbering_list(27);?></select><input type="checkbox"name="exportlist[]" value="userId" onClick="javascript:func_enable_select('list1');" ><font face="Verdana" size="1">Company Name</font></td>
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
	<table class="lgnbd"><tr>
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
	</td>
	</tr></table></td>
	</tr>
	<tr>
	 <td  height="50"  valign="middle" align="center"  colspan='2'>
		 <input type="image" id="reportview" src="../images/exportfile.jpg"></input>
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