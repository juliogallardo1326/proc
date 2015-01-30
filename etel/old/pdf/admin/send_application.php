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

$headerInclude = "mail";
include("includes/header.php");




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

$Transtype = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";

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
	
	var obj_email = obj_form.txt_to
	if( obj_email.value == "") 
	{
		alert("Please enter the bank email address");
		obj_email.focus();
		return false
	}

	if( !func_isEmail(obj_email.value)) 
	{
		alert("Please enter the valid email address");
		obj_email.select();
		return false
	}

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
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.application;
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

function Displaycompanytype() {
	document.application.trans_type.value="Submit";
	document.application.action = "send_application.php";
	document.application.submit();
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Send Document / Application</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">

		<form name="application" action="send_applicationfb.php" method="POST" onsubmit="return validation();">
		<input type="hidden" name="period" value="<?=$period?>"></input>
		<input type="hidden" name="trans_type" value="">
	  <table align="center" cellpadding="0" cellspacing="0" width="100%">  
	<br>
	  
	
		<tr><td colspan="2" align="center" valign="middle" width="100%">
			<table  cellpadding="0" cellspacing="2" width="100%">
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
				
				<tr><td colspan="2" height="30" align="center">
					<div id="allC" style="display:yes">
					<table width="100%"  cellpadding="0" cellspacing="0"><tr>
					<td valign="middle" align="right" width="40%"><font face="verdana" size="1">Company Name :</font></td>
					 <td align="left" width="60%">&nbsp;<select id="all" name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 150px" multiple>
					<?php func_multiselect_transaction($qrt_select_companies);
					?>
					</select>
					</td></tr></table>
					</div>				
					</td>
				</tr>

				<tr>
				<td height="30" align="right" width="40%"><font face="verdana" size="1">Bank Email Address :</font></td>
				<td align="left"  width="60%">&nbsp;<input type="text" name="txt_to" size="40" style="font-family:arial;font-size:10px;width:200px"> <br>
				</td>
				</tr>

				<tr>
				<td height="30" valign="top" align="right" width="40%"><font face="verdana" size="1">Attach  :</font></td>
				<td align="left"  width="60%">
				<input type="checkbox" name="chk_app" value="0">&nbsp; <font face="verdana" size="1">Merchant Application </font>&nbsp; <br>
				<input type="checkbox" name="chk_doc" value="1">&nbsp;
				<font face="verdana" size="1">Company Documents </font><br>
				</td>
				</tr>



				</table>
			</td></tr>
				
	<input type="hidden" value="" name="id" ></input>
	<input type="hidden" value="" name="cnumber"></input>
	<tr>
	 <td  height="50"  valign="middle" align="center"colspan='2'>
		 <input type="image" id="reportview" SRC="<?=$tmpl_dir?>/images/send.jpg"></input>
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
</table>
</td>
</tr>
</table>
<?php
include("includes/footer.php");
?>