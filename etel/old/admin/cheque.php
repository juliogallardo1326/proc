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
//check.php:		The page functions for entering the check details. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude="transactions";
include 'includes/header.php';

require_once( '../includes/function.php');
include '../includes/function1.php';
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="")
{
$i_company_id = (isset($HTTP_GET_VARS['companyname'])?quote_smart($HTTP_GET_VARS['companyname']):"");
$str_atm_verify = func_get_value_of_field($cnn_cs,"cs_companydetails","atm_verify","userid",$i_company_id);
$str_currency = func_get_processing_currency($i_company_id);

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
$str_current_date = func_get_current_date();
$i_to_year = substr($str_current_date,0,4);
$i_to_month = substr($str_current_date,5,2);
$i_to_day = substr($str_current_date,8,2);

/*$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2); */

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;
$tran_login_type="";		
$dateToEnter = func_get_current_date_time(); //EST Time.
//$dateToEnter="$yyyy-$mm-$dd $hr:$mn:$tt";
// $dateToEnter1="$yyyy-$mm-$dd";

 if($i_company_id !=""){
	$sql_trans_type = "Select transaction_type from cs_companydetails where userid=$i_company_id";
	if($show_trans_show = mysql_query($sql_trans_type)) {
		if($show_val = mysql_fetch_array($show_trans_show)) {
			$tran_login_type = $show_val[0];
		}
			
	}
 }
if ($tran_login_type == "tele") {
	$i_to_day = date("d",mktime(0,0,0,$i_to_month,$i_to_day + 1,$i_to_year));
	$i_to_month = date("m",mktime(0,0,0,$i_to_month,$i_to_day + 1,$i_to_year));
	$i_to_year = date("Y",mktime(0,0,0,$i_to_month,$i_to_day + 1,$i_to_year));

}

$domain = GetHostByName($_SERVER["REMOTE_ADDR"]); 
	
	
?>
<script language="javascript" src="../scripts/general.js"></script>
<script language="javascript">
function showDetails(the_sub){
	/*if(the_sub =="div1" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.chequeFrm.statusdiv1.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.chequeFrm.statusdiv1.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
	} else if(the_sub =="div2" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.chequeFrm.statusdiv2.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.chequeFrm.statusdiv2.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
    } else */
	if(the_sub =="div3" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	//document.chequeFrm.statusdiv3.value="";
		
		//document.chequeFrm.authorizationno.value="";
		//document.chequeFrm.shippingno.value="";
		document.chequeFrm.securityno.value="";
		document.chequeFrm.licensestate.selectedIndex=0;
		document.chequeFrm.driverlicense.value="";
		document.chequeFrm.misc.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		//document.chequeFrm.statusdiv3.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
	}
	if(the_sub =="div4" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	//document.chequeFrm.statusdiv4.value="";
		document.chequeFrm.chk_recur_date.checked=false;
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		//document.chequeFrm.statusdiv4.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
	}
}
function numbercheck(stringvar){
var strchk;
var flag = 0;
	if(stringvar.length>0) {
		for(var i=0;i<stringvar.length;i++) {
			strchk = stringvar.charAt(i);
			if(!(isNaN(strchk))) {
				flag = 1;
				return true;				
				break;
			}
		}
	}
}

function validation(srt_trans_type){

	var recur_mode = "";
	var flag = 0;
	for(i=0;i<document.chequeFrm.recurdatemode.length;i++)
	{
		if(document.chequeFrm.recurdatemode[i].checked)
		{
			recur_mode = document.chequeFrm.recurdatemode[i].value;
			break;
		}
	}
trimSpace(document.chequeFrm.txtproductDescription)
	if(document.chequeFrm.firstname.value==""){
		alert("Please enter the First name")
		document.chequeFrm.firstname.focus();
		return false;
   } 
	 
	 if(document.chequeFrm.lastname.value==""){
		alert("Please enter the Last name")
		document.chequeFrm.lastname.focus();
		return false;
	  } 
	  
	  if(document.chequeFrm.address.value==""){
		alert("Please enter address")
		document.chequeFrm.address.focus();
		return false;
	  }
	  
	  if(document.chequeFrm.city.value==""){
		alert("Please enter city")
		document.chequeFrm.city.focus();
		return false;
	  } 
/*	 else {
   		if(numbercheck(document.chequeFrm.city.value)){
			alert("Please enter non-numeric values only.")
			document.chequeFrm.city.focus();
			return false;
		} 
   	}
*/	
	  if(document.chequeFrm.country.selectedIndex==0){
		alert("Please enter country")
		document.chequeFrm.country.focus();
		return false;
	  }
	   if (document.chequeFrm.country.value== "United States"){
	     if( document.chequeFrm.state.selectedIndex==0){
    alert("Please enter the state")
	document.chequeFrm.state.focus();
	return false;
  }
  }
	  if(document.chequeFrm.zip.value==""){
		alert("Please enter zip code")
		document.chequeFrm.zip.focus();
		return false;
	  }
	  if(document.chequeFrm.country.value=="United States")
	  {
		if(document.chequeFrm.zip.value.length!=5 && document.chequeFrm.zip.value.length!=9){
			alert("Please enter the correct zip code")
			document.chequeFrm.zip.focus();
			return false;
		}
		if(isNaN(document.chequeFrm.zip.value)){
			alert("Please enter numeric values");
			document.chequeFrm.zip.focus();
			return false;
		}
	  }
	  if(document.chequeFrm.phonenumber.value==""){
		alert("Please enter phone #")
		document.chequeFrm.phonenumber.focus();
		return false;
	  }
	 
	if(srt_trans_type !="tele"){
 	  if(document.chequeFrm.email.value==""){
		alert("Please enter email address.")
		document.chequeFrm.email.focus();
		return false;
	  }
		  if (document.chequeFrm.email.value  != "") 
		  {
				if (document.chequeFrm.email.value .indexOf('@')==-1) 
				{
				alert("Please enter valid email id");
				document.chequeFrm.email.focus();
				return(false);
				}
		  }
			
		  if (document.chequeFrm.email.value  != "") 
		  {
				if (document.chequeFrm.email.value .indexOf('.')==-1) 
				{
				alert("Please enter valid email id");
				document.chequeFrm.email.focus();
				return(false);
				}
		  }
		
		  if (document.chequeFrm.email.value.length > 100)
		  {
				alert("Please enter email max upto 100 characters")
				document.chequeFrm.email.focus();
				return(false);
		  }
	  	  // added by midhun on 3/6/2004 starts here
		  if(document.chequeFrm.cfrm_email.value==""){
			alert("Please Confirm email address.")
			document.chequeFrm.cfrm_email.focus();
			return false;
		  }
		  if(document.chequeFrm.email.value != document.chequeFrm.cfrm_email.value){
			alert("Please re-enter Confirm email address.")
			document.chequeFrm.cfrm_email.focus();
			return false;
		  }
	  	  // added by midhun on 3/6/2004 ends here
	}

	  
	 if(!(document.chequeFrm.chequetype[0].checked) && !(document.chequeFrm.chequetype[1].checked)){
		alert("Please enter the type of Check")
		return false;
	}

	if(document.chequeFrm.amount.value==""){
		alert("Please enter amount")
		document.chequeFrm.amount.focus();
		return false;
	} 
	if(!(checkAllowedChars(document.chequeFrm.amount.value,'D'))) {
		alert("Please enter numeric values");
		document.chequeFrm.amount.focus();
		return false;
	}
	if(document.chequeFrm.amount.value.indexOf(".")<=0){
	
	    document.chequeFrm.amount.value = document.chequeFrm.amount.value + ".00";
		//alert("Please enter the decimal value")
		//document.chequeFrm.amount.focus();
		//flag = 1;
		
	} 
	 
	if(!(document.chequeFrm.accounttype[0].checked) && !(document.chequeFrm.accounttype[1].checked)){
		alert("Please enter Type of Account")
		return false;
	}
  
  	Bdd=document.chequeFrm.opt_bill_day.options[document.chequeFrm.opt_bill_day.selectedIndex].value;
  	Bmm=document.chequeFrm.opt_bill_month.options[document.chequeFrm.opt_bill_month.selectedIndex].value;
	Byyyy=document.chequeFrm.opt_bill_year.options[document.chequeFrm.opt_bill_year.selectedIndex].value;

	if(!ValidateDateBox("billing date",document.chequeFrm,Bdd,Bmm,Byyyy,1,1,0))
	{
		document.chequeFrm.opt_bill_month.focus();
		return false;		
	}
	
	if(document.chequeFrm.bankroutingcode.value.length < 9){
	alert("Please enter the correct bank routing code")
	document.chequeFrm.bankroutingcode.focus();
	return false;
	}
	if(document.chequeFrm.bankaccountno.value.length < 4 ){
	alert("Please enter bankaccount number")
	document.chequeFrm.bankaccountno.focus();
	return false;
	}
	if(isNaN(document.chequeFrm.bankaccountno.value)){
		alert("Please enter numeric values.")
		document.chequeFrm.bankaccountno.focus();
		return false;
  	}

	if(document.chequeFrm.securityno.value!=""){
		if(isNaN(document.chequeFrm.securityno.value)){
			alert("Please enter numeric values.")
			document.chequeFrm.securityno.focus();
			return false;
  		}
	}
	if(document.chequeFrm.chk_recur_date.checked)
	{
		if(recur_mode == "")
		{
			alert("Please select a recurring mode.")
			document.chequeFrm.recurdatemode[0].focus();
			return false;

		}
		else if(recur_mode == "D"){
			if(document.chequeFrm.recur_day.value == "")
			{
				alert("Please enter the recurring days.")
				document.chequeFrm.recur_day.focus();
				return false;
			}
			else if(isNaN(document.chequeFrm.recur_day.value))
			{
				alert("Please enter numeric values.")
				document.chequeFrm.recur_day.focus();
				return false;
			}
		}
		if(document.chequeFrm.recur_charge.value != "")
		{
			if(isNaN(document.chequeFrm.recur_charge.value)){
				alert("Please enter numeric values");
				document.chequeFrm.recur_charge.focus();
				return false;
			}
		}
		if(document.chequeFrm.recur_times.value==""){
			alert("Please enter no: of rebillings")
			document.chequeFrm.recur_times.focus();
			return false;
		}
		if(isNaN(document.chequeFrm.recur_times.value)){
			alert("Please enter numeric values");
			document.chequeFrm.recur_times.focus();
			return false;
		}
		Rdd=document.chequeFrm.opt_recur_day.options[document.chequeFrm.opt_recur_day.selectedIndex].value;
		Rmm=document.chequeFrm.opt_recur_month.options[document.chequeFrm.opt_recur_month.selectedIndex].value;
		Ryyyy=document.chequeFrm.opt_recur_year.options[document.chequeFrm.opt_recur_year.selectedIndex].value;

		if(!func_is_date1_after_date2(Ryyyy,Rmm,Rdd,Byyyy,Bmm,Bdd))
		{
			alert("Rebill Start Date should be later than Bill Date");
			document.chequeFrm.opt_recur_month.focus();
			return false;
		}
	}
	
	var day=document.chequeFrm.recur_day.value;
var rec_times=document.chequeFrm.recur_times.value;
var recur_charge=document.chequeFrm.recur_charge.value;
if(document.chequeFrm.recur_charge.value==""){recur_charge=document.chequeFrm.amount.value;}

var fname=document.chequeFrm.firstname.value;
var week=document.chequeFrm.recur_week.value;
var rec_day=document.chequeFrm.opt_recur_day.value;
var rec_year=document.chequeFrm.opt_recur_year.value;
var rec_mon=document.chequeFrm.opt_recur_month.value;
var mon=document.chequeFrm.recur_month.value;
var mode="";
for(i=0;i<document.chequeFrm.recurdatemode.length;i++)
{
if(document.chequeFrm.recurdatemode[i].checked)
{
	mode = document.chequeFrm.recurdatemode[i].value;
	break;
}
}


if(document.chequeFrm.chk_recur_date.checked){
var cu ="<?=$str_currency?>";
window.open("details.php?day="+day+"&curr="+cu+"&recur_charge="+recur_charge+"&mon="+mon+"&week="+week+"&recur_times="+rec_times+"&mode="+mode+"&opt_recur_day="+rec_day+"&opt_recur_year="+rec_year+"&opt_recur_month="+rec_mon,"Paymentdetails","status=1,scrollbars=1,width=450,height=250,left=150,top=150");
}

	
	document.chequeFrm.action="addcheque.php";	
	document.chequeFrm.method="post";
	document.chequeFrm.target="_self";
	document.chequeFrm.submit();
}

function validator(){
	if(document.chequeFrm.country.options[document.chequeFrm.country.selectedIndex].text=="United States") {
		document.chequeFrm.state.disabled = false;
	} else {
		document.chequeFrm.state.disabled = true;
	}
	return false;
}

function clearRecurDate()
{
	var obj_form = document.chequeFrm;
	for(i=0;i<obj_form.recurdatemode.length;i++)
	{
		if(obj_form.recurdatemode[i].checked)
		{
			obj_form.recurdatemode[i].checked = false;
			break;
		}
	}
}
function amt_submit(){

	if(document.chequeFrm.firstname.value==""){
		alert("Please enter the First name")
		document.chequeFrm.firstname.focus();
		return false;
	} 
	 if(document.chequeFrm.lastname.value==""){
		alert("Please enter the Last name")
		document.chequeFrm.lastname.focus();
		return false;
	  } 
	if(document.chequeFrm.amount.value==""){
		alert("Please enter amount")
		document.chequeFrm.amount.focus();
		return false;
	} 
	if(!(checkAllowedChars(document.chequeFrm.amount.value,'D'))) {
		alert("Please enter numeric values");
		document.chequeFrm.amount.focus();
		return false;
	}
	if(document.chequeFrm.amount.value.indexOf(".")<=0){
	    document.chequeFrm.amount.value= document.chequeFrm.amount.value + ".00";	     
		
	} 
	 
	if(!(document.chequeFrm.accounttype[0].checked) && !(document.chequeFrm.accounttype[1].checked)){
		alert("Please enter Type of Account")
		return false;
	}
	if(document.chequeFrm.bankroutingcode.value.length < 9){
	alert("Please enter the correct bank routing code")
	document.chequeFrm.bankroutingcode.focus();
	return false;
	}
	if(document.chequeFrm.bankaccountno.value.length < 4 ){
	alert("Please enter bankaccount number")
	document.chequeFrm.bankaccountno.focus();
	return false;
	}
	if(isNaN(document.chequeFrm.bankaccountno.value)){
		alert("Please enter numeric values.")
		document.chequeFrm.bankaccountno.focus();
		return false;
  	}
	open_window();
	document.chequeFrm.action="atmverify.php";	
	document.chequeFrm.method="post";
	document.chequeFrm.target="atmverify";
	document.chequeFrm.submit();
}
function open_window() {
	window_width = 330;
	window_height = 130;
	urlstr="";
	window.title="ATM";
	window_top = (screen.availHeight-window_height)/2
	window_left = (screen.availWidth-window_width)/2
	windowFeatures = "width=" + window_width + ",height=" + window_height 
	windowFeatures += ",top=" + window_top + ",left=" + window_left + ",scrollbars=0";
	windowname = "atmverify";
	window.open(urlstr,windowname,windowFeatures)
}

</script>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>
  
<form action="addcheque.php" method="post" name="chequeFrm" onsubmit="return validation('<?=$tran_login_type?>')">
<input type="hidden" name="statusdiv1" value="">
<input type="hidden" name="statusdiv2" value="">
<input type="hidden" name="statusdiv3" value="">
<input type="hidden" name="atm_verify" value="<?php print($str_atm_verify); ?>">
<input type="hidden" name="currency_code" value="<?=$str_currency?>" >

<table border="0" cellpadding="0" width="100%" cellspacing="0">
   <tr>
    <td width="100%" valign="top" align="left">
	    <table border="0" cellpadding="0" cellspacing="0" width="750" height="407" align="center">
      		<tr>
            <td width="100%" height="83" valign="top" align="left"> 
			<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid black">
               <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Customer 
                    Information</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div2')">show/hide</a>-->
                    </span></td>
                </tr>
                <!--Div starting for Customer Info.-->
                <tr> 
                  <td colspan="2"> 
                    <!--<div id="div2" style="display:none"> -->
                    <table width="100%" cellpadding="0" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%" height="30"><font size="2" face="Verdana" color="#3A7474"> 
                          <strong>First Name :</strong> </font></td>
                        <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="firstname" size="20" maxlength="75">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#3A7474"><strong>Last 
                          Name :</strong> </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="lastname" size="20" maxlength="75">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Address 
                          : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="address" size="45" maxlength="100">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">City 
                          : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp; <input type="text" name="city" size="35" maxlength="50"></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Country 
                          : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp; <select name="country"  style="font-size:11px;width:140px;font-height:10px;font-face:verdana;" onChange="return validator()">
                            <script language="JavaScript">showCountries();
							document.chequeFrm.country.value="United States";
							</script>
                          </select></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">State 
                          : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp; <select name="state" style="font-size:11px;width:120px;font-height:10px;font-face:verdana;">
							<?=func_get_state_select($showval[7]) ?>
						  </select> </td>
                      </tr>
                      <tr> 
                        <td a align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Zip 
                          code : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input name="zip" type="text" size="15" maxlength="15">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Telephone 
                          # : </font></td>
                        <td class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                          <input type="text" name="phonenumber" size="25" maxlength="30">
                          </font></td>
                      </tr>
                     <!-- Changed By midhun on 3/6/2004 starts here
					 <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to </font> : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="email" size="40" maxlength="100">
                          </font></td>
                      </tr>
					  -->
					<?php if($tran_login_type=="tele"){ ?>
							<tr> 
								<td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
								  email confirmation of&nbsp;&nbsp;<br>
								  this order will be sent to </font> : </font></td>
								<td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
								  <input type="text" name="email" size="40" maxlength="100">
								  </font></td>
							  </tr>
					<?php }else{?>
							 <tr> 
								<td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Customer's email address</font> : </font></td>
								<td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
								  <input type="text" name="email" size="40" maxlength="100">
								  </font></td>
							  </tr>
							  <tr> 
								<td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Confirm email address</font> : </font></td>
								<td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
								  <input type="text" name="cfrm_email" size="40" maxlength="100">
								  </font></td>
							  </tr>
					<?php } ?>  
					<!-- Changed By midhun on 3/6/2004 ends here-->
				<tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><strong><span class="subhd">Transaction 
                    Information</span></strong></td>
                </tr>
<!--                <tr> 
                  <td align="right" width=50% class="tdbdr1"><font size="2" face="Verdana" color="#000000">Invoice/Reference 
                    ID : </font></td>
                  <td width=50% class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="invoiceid" size="20">
                    </font></td>
                </tr>
-->				
                <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Check 
                          # : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="chequenumber" size="20" maxlength="50">
                    </font></td>
                </tr>
<!--                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Check 
                    Date : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp; 
                    <font color="#001188"><input type="text" name="checkdate" size="20"></font>
                    <select name="opt_chk_month" class="lineborderselect" style="font-size:10px">
                      <?php// func_fill_month($i_to_month); ?>
                    </select> <select name="opt_chk_day" class="lineborderselect" style="font-size:10px">
                      <?php// func_fill_day($i_to_day); ?>
                    </select> <select name="opt_chk_year" class="lineborderselect" style="font-size:10px">
                      <?php// func_fill_year($i_to_year); ?>
                    </select> </td>
                </tr>
-->				
                <tr> 
                  <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Check 
                    Type : </font></td>
                  <td class="tdbdr" valign="middle">&nbsp;&nbsp;<input type="radio" name="chequetype" value="personal"><font size="1" face="Verdana" color="#000000">Personal</font>&nbsp;&nbsp;<input type="radio" name="chequetype" value="business"><font size="1" face="Verdana" color="#000000">Business</font>
				  <!-- <select size="1" name="chequetype" style="font-size: 8pt; font-family: Verdana">
                      <option value="Xchoose">Choose</option>
                      <option value="personal">Personal</option>
                      <option value="business">Business</option>
                    </select> -->
				</td>
                </tr>
<!--                <tr> 
                  <td  align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Pay 
                    To The Order Of : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="payto" size="44">
                    </font></td>
                </tr>-->	
                <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#3A7474"><strong>Amount  :</strong> </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="amount" size="9" maxlength="50">
                   (<?= $str_currency?>) </font></td>
                </tr>
                <tr> 
                        <td class="tdbdr1" align="right" height="30"><font size="2" face="Verdana" color="#3A7474"><strong>Account 
                          Type :</strong> </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<input type="radio" name="accounttype" value="checking"><font size="1" face="Verdana" color="#000000">Checking</font>&nbsp;&nbsp;<input type="radio" name="accounttype" value="savings"><font size="1" face="Verdana" color="#000000">Savings</font>
				  <!-- <select size="1" name="accounttype" style="font-size: 8pt; font-family: Verdana">
                      <option value="Xchoose">Choose</option>
                      <option value="checkings">Checking</option>
                      <option value="savings">Savings</option>
                    </select>  
				-->
					</td>
                </tr>
                <tr> 
                  <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Billing 
                    Date : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;
                    <!--<font color="#001188"><input type="text" name="setbilldate" size="20"></font>-->
                    <select name="opt_bill_month" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_month($i_to_month); ?>
                    </select> <select name="opt_bill_day" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_day($i_to_day); ?>
                    </select> <select name="opt_bill_year" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_year($i_to_year); ?>
                    </select> </td>
                </tr>
				             <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Product Description : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="txtproductDescription" size="30" maxlength="200">
                    </font></td>
                </tr>
				
<!--                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Memo 
                    : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="memo" size="44">
                    </font></td>
                </tr>
-->				
                <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Bank 
                    Information</strong>&nbsp;
                    <!-- <a href="javascript:void(0)" onclick="showDetails('div1')">show/hide</a> -->
                    </span></td>
                </tr>
                <!--Div starting for Bank Info -->
                <tr> 
                  <td colspan="2"> 
                    <!--<div id="div1" style="display:none"> -->
                    <table width="100%" cellpadding="0" cellspacing="0" align="center" border="0">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%" height="30"><font size="2" face="Verdana" color="#000000">Bank 
                          Name : </font></td>
                        <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="bankname" size="45" maxlength="75">
                          </font></td>
                      </tr>
                      <tr> 
                              <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#3A7474"><strong>Bank 
                                Routing Code :</strong> </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="bankroutingcode" size="9" maxlength="9">
                          </font></td>
                      </tr>
                      <tr> 
                              <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#3A7474"><strong>Bank 
                                Account #</strong> : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="bankaccountno" size="25" maxlength="25">
                          </font></td>
                      </tr>
                    </table></td>
                </tr>
                <!--Div -->
                    </table>
                    <!--  </div> -->
                  </td>
                </tr>
                <!--Div -->
                <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Shipping 
                    Information</strong>&nbsp;
                    <a href="javascript:void(0)" onclick="showDetails('div3')" class="subhd" style="text-decoration:underline">show/hide</a>
                    </span></td>
                </tr>
                <!--Div starting for Shipping Info.-->
                <tr> 
                  <td colspan="2"> 
                    <div id="div3" style="display:none">
                      <table width="100%" cellpadding="0" cellspacing="0" align="center">
                        <?php if($tran_login_type =="tele") { ?>
                        <tr> 
                          <td align="right" valign="middle" class="tdbdr1" width="50%" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
                            Authorization # : </font></td>
                          <td valign="middle" class="tdbdr" width="50%"> <font color="#001188">&nbsp;&nbsp; 
                            <input type="text" name="authorizationno" size="25" maxlength="25">
                            </font></td>
                        </tr>
                        <tr> 
                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
                            Tracking # : </font></td>
                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                            <input type="text" name="shippingno" size="20" maxlength="50">
                            </font></td>
                        </tr>
                        <?php 	} ?>
                        <tr> 
                          <td align="right" valign="middle" class="tdbdr1" height="30" width="50%" ><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
                            Security # : </font></td>
                          <td valign="middle" class="tdbdr" width="50%" ><font color="#001188">&nbsp;&nbsp; 
                            <input type="text" name="securityno" size="9" maxlength="9">
                            </font></td>
                        </tr>
                        <tr> 
                          <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">License 
                            State : </font></td>
                          <td class="tdbdr">&nbsp;&nbsp; <select name="licensestate" style="font-size:11px;width:120px;font-height:10px;font-face:verdana;">
                              <option>- - -Select- - -</option>
                              <option>Alabama</option>
                              <option> Alaska</option>
                              <option> Arizona</option>
                              <option> Arkansas</option>
                              <option> California</option>
                              <option> Colorado</option>
                              <option> Connecticut</option>
                              <option> DC</option>
                              <option> Delaware</option>
                              <option> Florida</option>
                              <option> Georgia</option>
                              <option> Hawaii</option>
                              <option> Idaho </option>
                              <option> Illinois</option>
                              <option> Indiana</option>
                              <option> Iowa</option>
                              <option> Kansas</option>
                              <option> Kentucky </option>
                              <option> Louisiana </option>
                              <option> Maine</option>
                              <option> Maryland</option>
                              <option> Massachusetts</option>
                              <option> Michigan</option>
                              <option> Minnesota</option>
                              <option> Mississippi</option>
                              <option> Missouri</option>
                              <option> Montana</option>
                              <option> Nebraska</option>
                              <option> Nevada</option>
                              <option> New Hampshire</option>
                              <option> New Jersey</option>
                              <option> New Mexico</option>
                              <option> New York</option>
                              <option> North Carolina</option>
                              <option> North Dakota</option>
                              <option> Ohio</option>
                              <option> Oklahoma </option>
                              <option> Oregon</option>
                              <option> Pennsylvania</option>
                              <option> Rhode Island</option>
                              <option> South Carolina</option>
                              <option> South Dakota</option>
                              <option> Tennessee</option>
                              <option> Texas</option>
                              <option> Utah</option>
                              <option> Vermont</option>
                              <option> Virginia</option>
                              <option> Washington</option>
                              <option>Washington DC</option>
                              <option> West Virginia</option>
                              <option> Wisconsin</option>
                              <option> Wyoming </option>
                            </select></td>
                        </tr>
                        <tr> 
                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
                            License # : </font></td>
                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                            <input type="text" name="driverlicense" size="25" maxlength="100">
                            </font></td>
                        </tr>
                        <tr> 
                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Misc 
                            : </font></td>
                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                            <input type="text" name="misc" size="35" maxlength="100">
                            </font></td>
                        </tr>
                        <tr> 
                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time 
                            :</font></td>
                          <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp; 
                            <?php print func_get_date_time_12hr($dateToEnter);?> 
                            </font></td>
                        </tr>
                        <tr> 
                          <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                            Address :</font> </td>
                          <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000"> 
                            &nbsp;&nbsp; 
                            <?=$domain?>
                            </font> </td>
                        </tr>
                        <input type="hidden" name="domain1" value="<?=$domain?>" >
                      </table>
                    </div>
                  </td>
                </tr>
                <!--Div -->

                <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Set Recurring Date</strong>&nbsp;
                    <a href="javascript:void(0)" onclick="showDetails('div4')" class="subhd" style="text-decoration:underline">show/hide</a>
                    </span></td>
                </tr>
                <!--Div starting for Recurring Date.-->
                <tr> 
                  <td colspan="2"> 
                    <div id="div4" style="display:none">
                    <table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" width="50%" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Set Recurring Date : </font></td>
                        <td valign="middle" class="tdbdr" width="50%">&nbsp;<input type="checkbox" name="chk_recur_date" value="Y">
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" width="50%" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Day <input type="radio" name="recurdatemode" value="D"> </font></td>
                        <td valign="middle" class="tdbdr" width="50%"> <font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                          <input type="text" name="recur_day" size="3" maxlength="3"> Days</font>
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Week <input type="radio" name="recurdatemode" value="W"></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font> 
                          <select name="recur_week" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
                          <option value="1">Sunday</option>
                          <option value="2">Monday</option>
                          <option value="3">Tuesday</option>
                          <option value="4">Wednesday</option>
                          <option value="5">Thursday</option>
                          <option value="6">Friday</option>
                          <option value="7">Saturday</option>
						  </select>
						  </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Month <input type="radio" name="recurdatemode" value="M"></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font>
                          <select name="recur_month" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
							<?php func_fill_day("");?>
						  </select>
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Year <input type="radio" name="recurdatemode" value="Y"></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                          <select name="recur_year_month" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
							<?php func_fill_month("");?>
						  </select>
                          <select name="recur_year_day" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
							<?php func_fill_day("");?>
						  </select>
                          </font></td>
                      </tr>
					<tr> 
					  <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Rebilling Start 
						Date : </font></td>
					  <td class="tdbdr">&nbsp;
						<!--<font color="#001188"><input type="text" name="setbilldate" size="20"></font>-->
						<select name="opt_recur_month" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_month($i_to_month); ?>
						</select> <select name="opt_recur_day" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_day($i_to_day); ?>
						</select> <select name="opt_recur_year" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_year($i_to_year); ?>
						</select> </td>
					</tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Rebilling Charge (<?=$str_currency?>)
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                          <input type="text" name="recur_charge" size="10" maxlength="30"><font size="1" face="Verdana" color="#000000"> (Leave blank if same as original amount)</font>
                          </font></td>
                      </tr>
                      <tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">No: of Rebillings  
                          : </font></td>
                        <td valign="middle"><font color="#001188">&nbsp; 
                          <input type="text" name="recur_times" size="5" maxlength="20">&nbsp;&nbsp;<font size="1" face="Verdana" color="#000000">Type 0 for infinite re-bill until Cancelled </font>
                          </font></td>
                      </tr>
					</table>
					</div>
				</td>
			 </tr>

              </table></td>
    </tr>
    <tr>
      <td width="100%" valign="top" align="left">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="100%" align="center" height="40" valign="center">
		  <?
			if ($str_atm_verify == "Y") {
		  ?> 		
			<a href="#" onClick="return amt_submit();"><img border="0" SRC="<?=$tmpl_dir?>/images/atm_verify_bl.gif"></a>&nbsp;&nbsp;
		  <?
			  }
		  ?>
			<input type="image" name="add" SRC="<?=$tmpl_dir?>/images/submit.jpg"></input></td>
        </tr>
      </table>
      </td>
    </tr>
	  <tr><td>&nbsp;</td></tr>
  </table>
	</td>
  </tr>
</table>
<input type="hidden" name="hid_company_id" value="<?php print($i_company_id); ?>">
</form>
<?php 
include("includes/footer.php");
}
?>
