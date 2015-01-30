<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// labels.php:	The admin page functions for creating labels
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
require_once( '../includes/function.php');
$headerInclude="mail";	
include 'includes/header.php';


$backhref="useraccount.php";
//include '../message.php';
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
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
<script language="javascript">
   	function validation(){   
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
	function func_emailprint()
	{
		objForm = document.ledger;
		objForm.method = "POST";
		objForm.action = "printemails.php";
		objForm.target = "_blank";
		objForm = document.ledger;
		var strCompany;
		strCompany = "";
		for($i=0;$i<objForm.companyname.length;$i++)
		{
			if(objForm.companyname.options[$i].selected == true)
			{
				strCompany = strCompany +","+objForm.companyname.options[$i].value;
			}	
		}
		objForm.hid_companies.value = strCompany;
		objForm.submit();
	}
	function show(str_type)
	{
		var isValid = false;
		var obj_element = document.ledger.elements[38];
		for (i = 0; i < obj_element.length; i++) {
			if(obj_element[i].selected) {
				isValid = true;
			}
		}
		if (!isValid) {
			alert("Please select a company");
			return false;
		}

		var obj_form = document.ledger;
		if(obj_form.opt_order_sname.selectedIndex == obj_form.opt_order_fname.selectedIndex)
		{
			alert ("Please change order.");
			obj_form.opt_order_fname.focus();
			return false;
		}
//		if(validation())
//		{
			objForm = document.ledger;
			var strCompany;
			strCompany = "";
			/*
			for($i=0;$i<objForm.companyname.length;$i++)
			{
				if(objForm.companyname.options[$i].selected == true)
				{
					strCompany = strCompany +","+objForm.companyname.options[$i].value;
				}	
			}
			*/
			objForm.hid_companies.value = strCompany;
			document.ledger.method = "post";
			document.ledger.action = "labelsshow.php?type="+str_type;
			document.ledger.target = "_blank";
			document.ledger.submit();
//		}
	}
	
function showType(){
	if(document.ledger.crorcq.options[document.ledger.crorcq.selectedIndex].value=="C") {
		document.ledger.type[0] = new Option("All","");
		document.ledger.type[1] = new Option("Savings Account","savings");
		document.ledger.type[2] = new Option("Checking Account","checking");
		document.ledger.type.disabled = false;
	} else if(document.ledger.crorcq.options[document.ledger.crorcq.selectedIndex].value=="H") {
		document.ledger.type[0] = new Option("All","");
		document.ledger.type[1] = new Option("Master Card","Master");
		document.ledger.type[2] = new Option("Visa","Visa");
		document.ledger.type.disabled = false;
	}
	else{
		document.ledger.type.value= "";
		document.ledger.type.disabled = true;
	}
	return false;
}
function Displaycompany(){
	if(document.ledger.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.ledger.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.ledger.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = -1;
	document.getElementById('activename').selectedIndex = -1;
	document.getElementById('nonactivename').selectedIndex = -1;
}
function validation() {
	if(document.getElementById('all').value=="" && document.getElementById('activename').value=="" && document.getElementById('nonactivename').value==""){
		alert("Please select the company.");
		return false;
	}
	var obj_form = document.ledger;
	if(obj_form.opt_order_sname.selectedIndex == obj_form.opt_order_fname.selectedIndex)
	{
		alert ("Please change order.");
		obj_form.opt_order_fname.focus();
		return false;
	}
}
</script>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.ledger;
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
	document.ledger.trans_type.value="Submit";
	document.ledger.target = "_self";
	document.ledger.action = "labels.php";
	document.ledger.submit();
}

</script>
<?
//	$ddCur=date("d");
//	$mmCur=date("n");
//	$yyyyCur=date("Y");
//	$dateval2=$mmCur."/".$ddCur."/".$yyyyCur;
	
	$show_sql =mysql_query("select distinct userid,companyname from cs_companydetails order by companyname",$cnn_cs);
?>


 <table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
		<table border="0" cellpadding="0" cellspacing="0" width="80%">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Labels</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" colspan="5"><br>
			<form name="ledger"  method="POST" action="labelsshow.php" target="_blank">
			<input type="hidden" name="hid_companies" value="">
			<input type="hidden" name="trans_type" value="">
              <table align="center" cellpadding="0" cellspacing="0" width="92%">
<tr> 
                  <td height="30" valign="middle"   align="left" width="120"><font face="verdana" size="1">Date 
                    From</font></td>
                  <td align="left" width="228"  height="30" >&nbsp;&nbsp; 
                    <!--  <input type="text" name="txtDate" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input type="button" value="..." onclick="init()" style="font-family:verdana;font-size:10px;">-->
                    <select name="opt_from_month" style="font-size:10px">
                      <?php func_fill_month($i_from_month); ?>
                    </select> <select name="opt_from_day" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_day($i_from_day); ?>
                    </select></font>
                    <select name="opt_from_year" style="font-size:10px">
                      <?php func_fill_year($i_from_year); ?>
                    </select> 
					 <input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
					 <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(475,73,document.ledger.from_date)">
					</td>
                  <td width="329" colspan="2" rowspan="7" valign="top"> 
                    <table width="329">
                      <tr> 
                        <td width="119"><font face="verdana" size="1">Pending</font></td>
                        <td width="198"> 
                          <input type="checkbox" name="trans_ptype" value="p">
                          &nbsp;&nbsp;&nbsp;<font face="verdana" size="1">Pass</font> 
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="chk_pass" value="D">
                          &nbsp; &nbsp; </td>
                      </tr>
                        <td><font face="verdana" size="1">No Pass</font></td>
                      <td><input type="checkbox" name="chk_nopass" value="D">
                          &nbsp;&nbsp;&nbsp;<font face="verdana" size="1">Declined</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="trans_dtype" value="D"> 
                        </td>
                      </tr>
                      <tr> 
                        <td><font face="verdana" size="1">Cancelled</font></td>
                        <td> 
                          <input type="checkbox" name="trans_ctype" value="C">
                          &nbsp;&nbsp;&nbsp;<font face="verdana" size="1">Approved</font> 
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type="checkbox" name="trans_atype" value="A"></td>
                      </tr>
                      <tr> 
                        <td><font face="verdana" size="1">Set to bill Date</font></td>
                        <td> <input type="checkbox" name="radRange" value="S" ></td>
                      </tr>
					  <tr>
					  	<td height="2" colspan="2">&nbsp;</td>
					  </tr>			
					  <tr>
					  	<td colspan="2">
							<table class="lgnbd" width="100%">
							<tr>
								<td>&nbsp;</td>
								<td><font face="Verdana" size="1">Sort by</font></td>
								<td><select name="opt_sortorder" class="lineborderselect" style="font-size:9px">
										<option value="surname">Surname</option>
										<option value="name">Firstname</option>
										<option value="address">Address</option>
										<option value="city">City</option>
										<option value="state">State</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><font face="Verdana" size="1">No: of columns</font></td>
								<td><input type="text" name="txt_columns" value="3" size="1" maxlength="2" class="lineborderselect" style="font-size:9px"></td>
							</tr>
							<tr>
								<td>
									<select name="opt_order_sname" class="lineborderselect" style="font-size:8px">
										<option value="1" selected>1</option>
										<option value="2">2</option>
									</select>
								</td>
								<td><font face="Verdana" size="1">Surname</font></td>
								<td><select name="opt_font_sname" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontcombo();?>
									</select>&nbsp;
									<select name="opt_fontsize_sname" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontsizecombo();?>
									</select>&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<select name="opt_order_fname" class="lineborderselect" style="font-size:8px">
										<option value="1">1</option>
										<option value="2" selected>2</option>
									</select>
								</td>
								<td><font face="Verdana" size="1">Firstname</font></td>
								<td><select name="opt_font_fname" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontcombo();?>
									</select>&nbsp;
									<select name="opt_fontsize_fname" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontsizecombo();?>
									</select>&nbsp;
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><font face="Verdana" size="1">Address</font></td>
								<td><select name="opt_font_address" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontcombo();?>
									</select>&nbsp;
									<select name="opt_fontsize_address" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontsizecombo();?>
									</select>&nbsp;
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><font face="Verdana" size="1">City</font></td>
								<td><select name="opt_font_city" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontcombo();?>
									</select>&nbsp;
									<select name="opt_fontsize_city" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontsizecombo();?>
									</select>&nbsp;
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><font face="Verdana" size="1">State</font></td>
								<td><select name="opt_font_state" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontcombo();?>
									</select>&nbsp;
									<select name="opt_fontsize_state" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontsizecombo();?>
									</select>&nbsp;
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><font face="Verdana" size="1">Postcode</font></td>
								<td><select name="opt_font_pc" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontcombo();?>
									</select>&nbsp;
									<select name="opt_fontsize_pc" class="lineborderselect" style="font-size:9px">
										<?php func_fillfontsizecombo();?>
									</select>&nbsp;
								</td>
							</tr>
							</table>
						</td>
					  </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="30" valign="middle" align="left"  width="120"><font face="verdana" size="1">Date 
                    to</font></td>
                  <td align="left" width="228"  height="30"  >&nbsp;&nbsp; 
                    <!-- <input type="text" name="txtDate1" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init1()"> -->
                    <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_month($i_to_month); ?>
                    </select> <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_day($i_to_day); ?>
                    </select> <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_year($i_to_year); ?>
                    </select> 
					 <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
					  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(475,73,document.ledger.from_to)">
					</td>
                </tr>

			<tr><td colspan="2" align="center" valign="middle" width="100%">
			<table  cellpadding="0" cellspacing="0" width="100%">
				<tr>
				 <td height="30" valign="middle" align="left"  width="35%"><font face="verdana" size="1">Company Type</font></td>
				 <td align="left"  width="400">&nbsp;&nbsp;<select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_mailcompanytype($companytype); ?>
				</select></td>
				</tr>
				<tr>
				 <td height="30" valign="middle" align="left"  width="35%"><font face="verdana" size="1">Merchant Type</font></td>
				 <td align="left"  width="400">&nbsp;&nbsp;<select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
				</select></td>
				</tr>
				<tr><td colspan="2" align="center" height="60">
				<table width="100%"  cellpadding="0" cellspacing="0"><tr>
				<td valign="middle" align="left" width="35%"><font face="verdana" size="1">Select Company</font></td>
				 <td align="left" >&nbsp;&nbsp;<select id="all" name="companyname[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
				<?php func_multiselect_transaction($qrt_select_companies);
				?>
				</select>
				</td></tr></table>
				</td></tr>
                <tr> 
                  <td  height="30"  valign="middle" align="left"  width="120"> 
                    <font face="verdana" size="1" >Payment Type</font></td>
                  <td align="left" width="228">&nbsp;
                    <select name="crorcq" onChange="javascript:showType()" style="font-family:verdana;font-size:10px;WIDTH: 210px">
                      <option value="" selected>All</option>
                      <option value="C">Check</option>
                      <option value="H">Credit Card</option>
                    </select> </td>
                </tr>
                <tr> 
                  <td  height="30"  valign="middle" align="left"> <font face="verdana" size="1">Card/Check 
                    Type</font></td>
                  <td align="left" width="228">&nbsp; 
                    <select name="type" style="font-family:verdana;font-size:10px;WIDTH: 210px" disabled>
                    </select></font>
                  </td>
                </tr>
				<tr> 
					<td height="60" valign="middle" align="left"  width="35%"><p><font face="verdana" size="1">Cancellation Reason</font></p></td>
					<td align="left"  width="400">&nbsp;&nbsp;<select name="cancel_reasons[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
						<?php print(funcFillCancellationReason('',''));?> 
					  </select></td>
				  </tr>
				  <tr> 
					<td height="60" valign="middle" align="left"  width="35%"><font face="verdana" size="1">Decline Reason</font></td>
					<td align="left"  width="400">&nbsp;&nbsp;<select name="decline_reasons[]" style="font-family:verdana;font-size:10px;WIDTH: 210px" multiple>
						<?php print(funcFillDeclineReason('','Check'));?> 
					  </select></td>
				  </tr>
			</table>
			</td></tr>
                <tr> 
                  <td  height="91"  valign="middle" align="center"  colspan='5'> 
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p><a href="#" onclick="return show('v');"><img SRC="<?=$tmpl_dir?>/images/view.jpg" border="0"></a> 
                      <a href="#" onclick="return show('p');"><img SRC="<?=$tmpl_dir?>/images/print.jpg" border="0"></a> 
                      <!--<a href="javascript:func_emailprint()"><img SRC="<?=$tmpl_dir?>/images/printemails.gif" border="0"></a>-->
                    </p></td>
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
include 'includes/footer.php';
?>