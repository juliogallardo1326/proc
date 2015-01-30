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
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ecommerce.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//transactionverification.php:	The admin page functions for selecting the type of report view  for the company. 

include("includes/sessioncheck.php");

$headerInclude = "autoLetters";
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
<!--<script language="javascript" src="../scripts/calendar1.js"></script> 
<script language="javascript" src="../scripts/general.js"></script> -->
<script language="javascript">
function viewtemplate(type)
{	
	var isValid = false;
	var companyid;
	var obj_element = document.ecommerce.elements[3];
	for (i = 0; i < obj_element.length; i++) {
		if(obj_element[i].selected) {
			isValid = true;
		}
	} 
	if(type =="login") {
		if (isValid) {
		   companyid = document.ecommerce.elements[3].value;
		   advtWnd=window.open("reply_registrationmailview.php?company="+companyid,"advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
		   advtWnd.focus();
		} else {
		   companyid = "";
		   advtWnd=window.open("reply_registrationmailview.php?company="+companyid,"advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
		   advtWnd.focus();
		}
	} else {
		if (isValid) {
		   companyid = document.ecommerce.elements[3].value;
		   advtWnd=window.open("reply_registrationmailview.php?type=ecom","advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
		   advtWnd.focus();
		} else {
		   companyid = "";
		   advtWnd=window.open("reply_registrationmailview.php?type=ecom","advtWndName","'status=1,scrollbars=1,width=800,height=600,left=0,top=0'");
		   advtWnd.focus();
		}
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

function showType(){
	if(document.ecommerce.crorcq.options[document.ecommerce.crorcq.selectedIndex].value=="C") {
		document.ecommerce.type[0] = new Option("All","A");
		document.ecommerce.type[1] = new Option("Savings Account","S");
		document.ecommerce.type[2] = new Option("Checking Account","C");
		document.ecommerce.type.disabled = false;
	} else if(document.ecommerce.crorcq.options[document.ecommerce.crorcq.selectedIndex].value=="H") {
		document.ecommerce.type[0] = new Option("All","A");
		document.ecommerce.type[1] = new Option("Master Card","M");
		document.ecommerce.type[2] = new Option("Visa","V");
		document.ecommerce.type.disabled = false;
	}
	else{
		document.ecommerce.type.value= "";
		document.ecommerce.type.disabled = true;
	}
	return false;
}
function Displaycompany(){
	if(document.ecommerce.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.ecommerce.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.ecommerce.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = -1;
	document.getElementById('activename').selectedIndex = -1;
	document.getElementById('nonactivename').selectedIndex = -1;
}

function validation() {

	return true;
}
</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.ecommerce;
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
	document.ecommerce.trans_type.value="Submit";
	document.ecommerce.action = "ecommerce_letter.php";
	document.ecommerce.submit();
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
		<table width="50%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Ecommerce letter</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5">

		<form name="ecommerce" action="ecommerce_letterfb.php" method="POST" onsubmit="return validation();">
		<input type="hidden" name="period" value="<?=$period?>"></input>
		<input type="hidden" name="trans_type" value="">
	  <table align="center" cellpadding="0" cellspacing="0" width="100%">  
	<br>
	   <tr>
		  <td   height="30" valign="middle"   align="right" width="40%"><font face="verdana" size="1"><?=$periodstring?></font></td><td align="left" width="60%"  height="30" >&nbsp;
		<!--	 <input type="text" name="txtDate" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input type="button" value="..." onclick="init()" style="font-family:verdana;font-size:10px;"> -->
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
		   <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(640,280,document.ecommerce.from_date);">
		   </td>
		</tr>
        <tr>
		  <td   height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1"><?=$endperiodstring?></font></td><td align="left" width="60%"  height="30"  >&nbsp;
		<!--	<input type="text" name="txtDate1" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init1()"> -->
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
			  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(640,310,document.ecommerce.from_to)">
		 </td>   
        </tr>

	
		<tr><td colspan="2" align="center" valign="middle" width="100%">
			<table  cellpadding="0" cellspacing="0" width="100%">
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
				
				</td></tr>
				<tr>
				        <td height="30"  valign="middle" align="center" colspan="2"> 
                          <font face="verdana" size="1"><a href="javascript:viewtemplate('ecom');">View 
                          Ecommerce Purchase Letter</a></font> </td>
				</tr>				
				</table>
			</td></tr>
				
	<input type="hidden" value="" name="id" ></input>
	<input type="hidden" value="" name="cnumber"></input>
	<tr>
	 <td  height="50"  valign="middle" align="center"colspan='2'>
		 <input type="image" id="reportview" SRC="<?=$tmpl_dir?>/images/view.jpg"></input>
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