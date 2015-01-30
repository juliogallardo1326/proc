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
//check.php:		The page functions for entering the check details. 
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$headerInclude="home";	
include 'includes/topheader.php';
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
if($sessionCompanyUser !="") 
{
	$headerInclude="sessionCompanyUser";
} 
else 
{
	$headerInclude="home";
}
		
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
		
$dateToEnter = func_get_current_date_time(); //EST Time.
//$dateToEnter="$yyyy-$mm-$dd $hr:$mn:$tt";
// $dateToEnter1="$yyyy-$mm-$dd";
$domain = GetHostByName($_SERVER["REMOTE_ADDR"]); 
	
	
?>
<script language="javascript" src="scripts/general.js"></script>
<script language="javascript">
function showDetails(the_sub){
	if(the_sub =="div1" ){
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
    } else if(the_sub =="div3" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.chequeFrm.statusdiv3.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.chequeFrm.statusdiv3.value=true;
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

function validation(){

	reBdd=document.chequeFrm.opt_rebill_day.options[document.chequeFrm.opt_rebill_day.selectedIndex].value;
  	reBmm=document.chequeFrm.opt_rebill_month.options[document.chequeFrm.opt_rebill_month.selectedIndex].value;
	reByyyy=document.chequeFrm.opt_rebill_year.options[document.chequeFrm.opt_rebill_year.selectedIndex].value;
   if(!ValidateDateBox("rebilling date",document.chequeFrm,reBdd,reBmm,reByyyy,1,1,0))
   {
   		return false;		
   }
	if(document.chequeFrm.firstname.value==""){
		alert("Please enter the First name")
		document.chequeFrm.firstname.focus();
		return false;
   } else { 
   		if(numbercheck(document.chequeFrm.firstname.value)){
			alert("Please enter non-numeric values only.")
			document.chequeFrm.firstname.focus();
			return false;
		} 
   }
	 
	 if(document.chequeFrm.lastname.value==""){
		alert("Please enter the Last name")
		document.chequeFrm.lastname.focus();
		return false;
	  } else {
   		if(numbercheck(document.chequeFrm.lastname.value)){
			alert("Please enter non-numeric values only.")
			document.chequeFrm.lastname.focus();
			return false;
		} 
   	}
	  if(document.chequeFrm.address.value==""){
		alert("Please enter address")
		document.chequeFrm.address.focus();
		return false;
	  }
/*	   else {
   		if(numbercheck(document.chequeFrm.address.value)){
			alert("Please enter non-numeric values only.")
			document.chequeFrm.address.focus();
			return false;
		} 
   	}
*/	
	  if(document.chequeFrm.city.value==""){
		alert("Please enter city")
		document.chequeFrm.city.focus();
		return false;
	  } 
/*	  else {
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
	  if(document.chequeFrm.zip.value==""){
		alert("Please enter zip code")
		document.chequeFrm.zip.focus();
		return false;
	  }
 	 if(document.chequeFrm.zip.value.length!=5 && document.chequeFrm.zip.value.length!=9){
		alert("Please enter the correct zip code")
		document.chequeFrm.zip.focus();
		return false;
	  }

	  if(document.chequeFrm.country.value == "United States") {
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
	  if(isNaN(document.chequeFrm.phonenumber.value)){
		alert("Please enter numeric values")
		document.chequeFrm.phonenumber.focus();
		return false;
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
	if(isNaN(document.chequeFrm.amount.value)){
		alert("Please numeric value");
		document.chequeFrm.amount.focus();
		return false;
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
			return false;		
	   }
   
   var rebilldate = new Date(reByyyy,reBmm-1,reBdd);
   var billingdate = new Date(Byyyy,Bmm-1,Bdd);
   if (billingdate < rebilldate)
   {
   		alert("Billing date should be less than rebilling date");
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

	if(document.chequeFrm.authorizationno.value==""){
		alert("Please enter the voice authorization number.")
		document.chequeFrm.authorizationno.focus();
		return false;
  	}
	if(isNaN(document.chequeFrm.authorizationno.value)){
		alert("Please enter numeric values.")
		document.chequeFrm.authorizationno.focus();
		return false;
  	}

	if(document.chequeFrm.securityno.value==""){
		alert("Please enter the social security number.")
		document.chequeFrm.securityno.focus();
		return false;
  	}
	if(isNaN(document.chequeFrm.securityno.value)){
		alert("Please enter numeric values.")
		document.chequeFrm.securityno.focus();
		return false;
  	}
  
}

function validator(){
	if(document.chequeFrm.country.options[document.chequeFrm.country.selectedIndex].text=="United States") {
		document.chequeFrm.state.disabled = false;
	} else {
		document.chequeFrm.state.disabled = true;
	}
	return false;
}

</script>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>
  
<form action="rebill_chequefb.php" method="post" name="chequeFrm" onsubmit="return validation()">
<input type="hidden" name="statusdiv1" value="">
<input type="hidden" name="statusdiv2" value="">
<input type="hidden" name="statusdiv3" value="">
<table border="0" cellpadding="0" width="100%" cellspacing="0">
   <tr>
    <td width="100%" valign="top" align="left">
	    <table border="0" cellpadding="0" cellspacing="0" width="750" height="80%" align="center">
      	<tr>
			  <td align="center" valign="middle"><font size="2" face="Verdana" >Rebilling 
				Date :</font>&nbsp; <select name="opt_rebill_month" class="lineborderselect" style="font-size:10px">
				  <?php func_fill_month($i_to_month); ?>
				</select> <select name="opt_rebill_day" class="lineborderselect" style="font-size:10px">
				  <?php func_fill_day($i_to_day); ?>
				</select> <select name="opt_rebill_year" class="lineborderselect" style="font-size:10px">
				  <?php func_fill_year($i_to_year); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>		
			<tr>
            <td width="100%" height="83" valign="top" align="left"> 
			<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid black">
               <tr bgcolor="#78B6C2"> 
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
                        <td align="right" class="tdbdr1" width="50%" height="30"><font size="2" face="Verdana" color="#000000"> 
                          First Name : </font></td>
                        <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="firstname" size="20">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Last 
                          Name : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="lastname" size="20">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Address 
                          : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="address" size="45">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">City 
                          : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp; <input type="text" name="city" size="15"></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Country 
                          : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp; <select name="country"  style="font-size:11px;width:140px;font-height:10px;font-face:verdana;" onChange="return validator()">
                            <option>&nbsp;</option>
                            <option>Afghanistan </option>
                            <option>Albania </option>
                            <option>Algeria </option>
                            <option>Andorra </option>
                            <option>Angola</option>
                            <option>Antigua and Barbuda </option>
                            <option>Argentina </option>
                            <option>Armenia </option>
                            <option>Australia </option>
                            <option>Austria</option>
                            <option>Azerbaijan </option>
                            <option>Bahamas</option>
                            <option>Bahrain </option>
                            <option>Bangladesh </option>
                            <option>Barbados </option>
                            <option>Belarus </option>
                            <option>Belgium </option>
                            <option>Belize </option>
                            <option>Benin </option>
                            <option>Bhutan </option>
                            <option>Bolivia </option>
                            <option>Bosnia</option>
                            <option>Botswana </option>
                            <option>Brazil </option>
                            <option>Brunei </option>
                            <option>Bulgaria </option>
                            <option>Burkina Faso </option>
                            <option>Burundi </option>
                            <option>Cambodia </option>
                            <option>Cameroon </option>
                            <option>Canada </option>
                            <option>Cape Verde </option>
                            <option>Central African </option>
                            <option>Chad </option>
                            <option>Chile </option>
                            <option>China </option>
                            <option>Colombia </option>
                            <option>Comoros</option>
                            <option>Congo </option>
                            <option>Costa Rica </option>
                            <option>Croatia </option>
                            <option>Cuba </option>
                            <option>Cyprus </option>
                            <option>Czech Republic </option>
                            <option>Côte d'Ivoire </option>
                            <option>Denmark</option>
                            <option>Djibouti</option>
                            <option>Dominica</option>
                            <option>Dominican Republic </option>
                            <option>East Timor</option>
                            <option>Ecuador</option>
                            <option>Egypt </option>
                            <option>El Salvador</option>
                            <option>Equatorial Guinea</option>
                            <option>Eritrea</option>
                            <option>Estonia </option>
                            <option>Ethiopia </option>
                            <option>Fiji </option>
                            <option>Finland </option>
                            <option>France </option>
                            <option>Gabon </option>
                            <option>Gambia</option>
                            <option>Georgia</option>
                            <option>Germany </option>
                            <option>Ghana </option>
                            <option>Greece </option>
                            <option>Grenada </option>
                            <option>Guatemala </option>
                            <option>Guinea </option>
                            <option>Guyana </option>
                            <option>Haiti</option>
                            <option>Honduras </option>
                            <option>Hungary</option>
                            <option>Iceland</option>
                            <option>India </option>
                            <option>Indonesia</option>
                            <option>Iran </option>
                            <option>Iraq </option>
                            <option>Ireland </option>
                            <option>Israel </option>
                            <option>Italy </option>
                            <option>Jamaica </option>
                            <option>Japan </option>
                            <option>Jordan </option>
                            <option>Kazakhstan</option>
                            <option>Kenya </option>
                            <option>Kiribati </option>
                            <option>Korea</option>
                            <option>Kuwait </option>
                            <option>Kyrgyzstan </option>
                            <option>Laos </option>
                            <option>Latvia </option>
                            <option>Lebanon </option>
                            <option>Lesotho</option>
                            <option>Liberia </option>
                            <option>Libya </option>
                            <option>Liechtenstein </option>
                            <option>Lithuania </option>
                            <option>Luxembourg </option>
                            <option>Macedonia</option>
                            <option>Madagascar </option>
                            <option>Malawi </option>
                            <option>Malaysia </option>
                            <option>Maldives </option>
                            <option>Mali </option>
                            <option>Malta </option>
                            <option>Marshall Islands </option>
                            <option>Mauritania </option>
                            <option>Mauritius </option>
                            <option>Mexico </option>
                            <option>Micronesia</option>
                            <option>Moldova </option>
                            <option>Monaco </option>
                            <option>Mongolia </option>
                            <option>Morocco </option>
                            <option>Mozambique </option>
                            <option>Myanmar </option>
                            <option>Namibia </option>
                            <option>Nauru </option>
                            <option>Nepal </option>
                            <option>Netherlands </option>
                            <option>New Zealand </option>
                            <option>Nicaragua </option>
                            <option>Niger </option>
                            <option>Nigeria </option>
                            <option>Norway </option>
                            <option>Oman </option>
                            <option>Pakistan</option>
                            <option>Palau </option>
                            <option>Panama </option>
                            <option>Papua New Guinea </option>
                            <option>Paraguay </option>
                            <option>Peru </option>
                            <option>Philippines </option>
                            <option>Poland </option>
                            <option>Portugal </option>
                            <option>Qatar </option>
                            <option>Romania </option>
                            <option>Russia </option>
                            <option>Rwanda </option>
                            <option>Saint Kitts </option>
                            <option>Saint Lucia</option>
                            <option>Saint Vincent </option>
                            <option>Samoa </option>
                            <option>San Marino</option>
                            <option>Sao Tome and Principe </option>
                            <option>Saudi Arabia </option>
                            <option>Senegal </option>
                            <option>Serbia and Montenegro </option>
                            <option>Seychelles </option>
                            <option>Sierra Leone </option>
                            <option>Singapore </option>
                            <option>Slovakia </option>
                            <option>Slovenia</option>
                            <option>Solomon Islands </option>
                            <option>Somalia </option>
                            <option>South Africa </option>
                            <option>Spain </option>
                            <option>Sri Lanka </option>
                            <option>Sudan </option>
                            <option>Suriname </option>
                            <option>Swaziland </option>
                            <option>Sweden </option>
                            <option>Switzerland </option>
                            <option>Syria </option>
                            <option>Taiwan </option>
                            <option>Tajikistan </option>
                            <option>Tanzania </option>
                            <option>Thailand </option>
                            <option>Togo </option>
                            <option>Tonga</option>
                            <option>Trinidad and Tobago</option>
                            <option>Tunisia </option>
                            <option>Turkey </option>
                            <option>Turkmenistan </option>
                            <option>Tuvalu </option>
                            <option>Uganda </option>
                            <option>Ukraine </option>
                            <option>United Arab Emirates </option>
                            <option>United Kingdom </option>
                            <option selected value="United States">United States</option>
                            <option>Uruguay </option>
                            <option>Uzbekistan </option>
                            <option>Vanuatu </option>
                            <option>Vatican City </option>
                            <option>Venezuela </option>
                            <option>Vietnam</option>
                            <option>Western Sahara </option>
                            <option>Yemen </option>
                            <option>Zambia </option>
                            <option>Zimbabwe </option>
                          </select></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">State 
                          : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp; <select name="state" style="font-size:11px;width:120px;font-height:10px;font-face:verdana;">
                            <option>- - -Select- - -</option>
                            <option>Alabama</option>
                            <option> Alaska</option>
                            <option> Arizona</option>
                            <option> Arkansas</option>
                            <option> California</option>
                            <option> Colorado</option>
                            <option> Connecticut</option>
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
                            <option> West Virginia</option>
                            <option> Wisconsin</option>
                            <option> Wyoming </option>
                          </select></td>
                      </tr>
                      <tr> 
                        <td a align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Zip 
                          code : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input name="zip" type="text" size="10" maxlength="9">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Telephone 
                          # : </font></td>
                        <td class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                          <input type="text" name="phonenumber" size="10" maxlength="10">
                          </font></td>
                      </tr>
<!--                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to </font> : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="email" size="40">
                          </font></td>
                      </tr>
-->					  
				<tr bgcolor="#78B6C2"> 
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
                    <input type="text" name="chequenumber" size="20" maxlength="35">
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
                  <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Amount(US 
                    Dollars) : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="amount" size="9">
                    </font></td>
                </tr>
                <tr> 
                  <td class="tdbdr1" align="right" height="30"><font size="2" face="Verdana" color="#000000">Account 
                    Type : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<input type="radio" name="accounttype" value="checking"><font size="1" face="Verdana" color="#000000">Checking</font>&nbsp;&nbsp;<input type="radio" name="accounttype" value="savings"><font size="1" face="Verdana" color="#000000">Savings</font>
				  <!-- <select size="1" name="accounttype" style="font-size: 8pt; font-family: Verdana">
                      <option value="Xchoose">Choose</option>
                      <option value="checking">Checking</option>
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
<!--                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Memo 
                    : </font></td>
                  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                    <input type="text" name="memo" size="44">
                    </font></td>
                </tr>
-->				
                <tr bgcolor="#78B6C2"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Bank 
                    Information</strong>&nbsp;
                    <!-- <a href="javascript:void(0)" onclick="showDetails('div1')">show/hide</a> -->
                    </span></td>
                </tr>
                <!--Div starting for Bank Info -->
                <tr> 
                  <td colspan="2"> 
                    <!--<div id="div1" style="display:none"> -->
                    <table width="100%" cellpadding="0" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%" height="30"><font size="2" face="Verdana" color="#000000">Bank 
                          Name : </font></td>
                        <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="bankname" size="25">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Bank 
                          Routing Code : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="bankroutingcode" size="18" maxlength="9">
                          </font></td>
                      </tr>
                      <tr> 
                              <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000"><font size="2" face="Verdana" color="#000000">Bank 
                                Account # : </font></font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="bankaccountno" size="18" maxlength="15">
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
                <tr bgcolor="#78B6C2"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Shipping 
                    Information</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                    </span></td>
                </tr>
                <!--Div starting for Shipping Info.-->
                <tr> 
                  <td colspan="2"> 
                    <!--<div id="div3" style="display:none"> -->
                    <table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" width="50%" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
                          Authorization # : </font></td>
                        <td valign="middle" class="tdbdr" width="50%"> <font color="#001188">&nbsp;&nbsp; 
                          <input type="text" name="authorizationno" size="20">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
                          Tracking # : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                          <input type="text" name="shippingno" size="20">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
                          Security # : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                          <input type="text" name="securityno" size="18" maxlength="9">
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
                            <option> West Virginia</option>
                            <option> Wisconsin</option>
                            <option> Wyoming </option>
                          </select></td>
                      </tr>                      
					  <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
                          License : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                          <input type="text" name="driverlicense" size="25">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Misc 
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                          <input type="text" name="misc" size="35">
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
                        <td align="right" valign="middle" class="tdbdr2" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                          Address :</font> </td>
                        <td valign="middle"><font size="1" face="Verdana" color="#000000"> 
                          &nbsp;&nbsp;
                          <?=$domain?>
                          </font> </td>
                      </tr>
                      <input type="hidden" name="domain1" value="<?=$domain?>" >
                    </table>
                    <!--   </div>-->
                  </td>
                </tr>
                <!--Div -->
              </table></td>
    </tr>
    <tr>
      <td width="100%" valign="top" align="left">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="100%" align="center" height="40" valign="center">
			<input type="image" name="add" src="images/submit.jpg"></input></td>
        </tr>
      </table>
      </td>
    </tr>
	  <tr><td>&nbsp;</td></tr>
  </table>
	</td>
  </tr>
</table>
</form>
<?php 
include("includes/footer.php");
?>
