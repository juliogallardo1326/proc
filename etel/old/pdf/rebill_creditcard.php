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
//rebill_creditcard.php:	The page functions for entering the creditcard details as a rebilling one. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$headerInclude="home";	
include 'includes/topheader.php';
//*************************************************************************************************
$str_current_date = func_get_current_date();
$i_to_year = substr($str_current_date,0,4);
$i_to_month = substr($str_current_date,5,2);
$i_to_day = substr($str_current_date,8,2);

/*$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");*/
$cSelect ="";
$vSelect="";
$mSelect="";
$transid = "";
$fname = "";
$sname ="";
$amt  ="";
$billadd = "";
$country ="";
$state ="";
$city ="";
$zipcd ="";
$cardnum ="";
$cvvnum ="";
$cardtyp ="";
$expdd ="";
$expyy ="";
$misc ="";
$emailadd = "";
$modestatus = "";
$user =0;
$ddSelect ="";
$mmSelect="";
$mmv=0;
$ddv=0;
$type = "";
$tid = 0;
$voiceauth	= "";
$shippingno	= "";
$securityno	= "";
$licenseno 	= "";
$phonenumber = "";
$yearval=date("Y");
$monthval=date("m");
$dateval=date("d");
$dateToEnter= func_get_current_date_time(); //EST Time.
$domain 	= GetHostByName($_SERVER["REMOTE_ADDR"]); 
//**************************************************************************************************
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sender ="admin@companysetup.co.uk";
?>
<script language="javascript" src="scripts/general.js"></script>
<script language="javascript">
function validation(){
	
	reBdd=document.creditcardFrm.opt_rebill_day.options[document.creditcardFrm.opt_rebill_day.selectedIndex].value;
  	reBmm=document.creditcardFrm.opt_rebill_month.options[document.creditcardFrm.opt_rebill_month.selectedIndex].value;
	reByyyy=document.creditcardFrm.opt_rebill_year.options[document.creditcardFrm.opt_rebill_year.selectedIndex].value;
   if(!ValidateDateBox("rebilling date",document.creditcardFrm,reBdd,reBmm,reByyyy,1,1,0))
   {
   		return false;		
   }

/*if(document.creditcardFrm.ddv.selectedIndex ==0){
    alert("Please select the Date")
    document.creditcardFrm.ddv.focus();
	return false;
  }*/
  
  if(document.creditcardFrm.firstname.value==""){
    alert("Please enter the First name")
    document.creditcardFrm.firstname.focus();
	return false;
  }
  if(document.creditcardFrm.lastname.value==""){
    alert("Please enter the Last name")
    document.creditcardFrm.lastname.focus();
	return false;
  }
  if(document.creditcardFrm.address.value==""){
    alert("Please enter the address")
    document.creditcardFrm.address.focus();
	return false;
  }
  if(document.creditcardFrm.country.selectedIndex==0){
    alert("Please enter the country")
    document.creditcardFrm.country.focus();
	return false;
  }
 
  if(document.creditcardFrm.city.value==""){
    alert("Please enter the city")
    document.creditcardFrm.city.focus();
	return false;
  }
  if(document.creditcardFrm.zipcode.value==""){
    alert("Please enter the zip code")
    document.creditcardFrm.zipcode.focus();
	return false;
  }
  if(isNaN(document.creditcardFrm.zipcode.value)){
	alert("Please enter numric values");
	document.creditcardFrm.zipcode.focus();
	return false;
  }
  if(document.creditcardFrm.zipcode.value.length!=5 && document.creditcardFrm.zipcode.value.length!=9){
	alert("Please enter a valid zip code");
	document.creditcardFrm.zipcode.focus();
	return false;
  }
  if(document.creditcardFrm.telephone.value==""){
    alert("Please enter Phone #")
    document.creditcardFrm.telephone.focus();
	return false;
  }
  if(isNaN(document.creditcardFrm.telephone.value)){
    alert("Please enter numric values")
    document.creditcardFrm.telephone.focus();
	return false;
  }
  if(document.creditcardFrm.number.value==""){
    alert("Please enter the credit card number")
    document.creditcardFrm.number.focus();
	return false;
  }
  
 if((document.creditcardFrm.number.value.length!=16) && (document.creditcardFrm.number.value.length!=13)){
    alert("Please enter the correct credit card number")
    document.creditcardFrm.number.focus();
	return false;
  }

  if(document.creditcardFrm.cvv2.value==""){
    alert("Please enter the credit card CVV2 number")
    document.creditcardFrm.cvv2.focus();
	return false;
  } 
  if(document.creditcardFrm.cvv2.value.length!=3){
    alert("Credit card CVV2 number should a 3 digit number")
    document.creditcardFrm.cvv2.focus();
	return false;
  } 
 
  if((document.creditcardFrm.cardtype.options[document.creditcardFrm.cardtype.selectedIndex].value == "Master") && (parseInt(document.creditcardFrm.number.value.charAt(0))!= 5)) {
    alert("The card number should start with 5 for Master card")
    document.creditcardFrm.number.focus();
	return false;
  } else if((document.creditcardFrm.cardtype.options[document.creditcardFrm.cardtype.selectedIndex].value == "Visa") && (parseInt(document.creditcardFrm.number.value.charAt(0))!= 4)) {
	alert("The card number should start with 4 for Visa card")
    document.creditcardFrm.number.focus();
	return false;
  }

	mm=document.creditcardFrm.mm.options[document.creditcardFrm.mm.selectedIndex].value;
	yyyy=document.creditcardFrm.yyyy.options[document.creditcardFrm.yyyy.selectedIndex].value;
  if(mm==""){
    alert("Please enter month");
    document.creditcardFrm.mm.focus();
	return false;
  } else if(mm < <?=$monthval?>){
    alert("The month should not be less than current month.")
    document.creditcardFrm.mm.focus();
	return false;
  }
  if(yyyy==""){
    alert("Please enter year");
    document.creditcardFrm.yyyy.focus();
	return false;
  } else if(yyyy < <?=$yearval?>) {
    alert("The year should not be less than current year.");
    document.creditcardFrm.mm.focus();
	return false;
  }
  
  if(document.creditcardFrm.amount.value==""){
    alert("Please enter the amount");
    document.creditcardFrm.amount.focus();
	return false;
  }
  if(isNaN(document.creditcardFrm.amount.value)){
    alert("Please enter numeric values");
    document.creditcardFrm.amount.focus();
	return false;
  }
	
  /*if (document.creditcardFrm.setbilldate.value.length ==0)
  {
		alert("please enter the billing date");
		document.creditcardFrm.setbilldate.focus();
		return false;
  } */
  	Bdd=document.creditcardFrm.opt_bill_day.options[document.creditcardFrm.opt_bill_day.selectedIndex].value;
  	Bmm=document.creditcardFrm.opt_bill_month.options[document.creditcardFrm.opt_bill_month.selectedIndex].value;
	Byyyy=document.creditcardFrm.opt_bill_year.options[document.creditcardFrm.opt_bill_year.selectedIndex].value;
   if(!ValidateDateBox("billing date",document.creditcardFrm,Bdd,Bmm,Byyyy,1,1,0))
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
  
  if(document.creditcardFrm.authorizationno.value==""){
	alert("Please enter the voice authorization number.")
    document.creditcardFrm.authorizationno.focus();
	return false;
  } 
	if(isNaN(document.creditcardFrm.securityno.value)){
		alert("Please enter numeric values.")
		document.creditcardFrm.securityno.focus();
		return false;
  	}

  if (document.creditcardFrm.email.value  != "") 
  {
		if (document.creditcardFrm.email.value .indexOf('@')==-1) 
		{
		alert("Please enter valid email id");
		document.creditcardFrm.email.focus();
		return(false);
		}
  }
	
  if (document.creditcardFrm.email.value  != "") 
  {
		if (document.creditcardFrm.email.value .indexOf('.')==-1) 
		{
		alert("Please enter valid email id");
		document.creditcardFrm.email.focus();
		return(false);
		}
  }

  if (document.creditcardFrm.email.value.length > 100)
  {
		alert("Please enter email max upto 100 characters")
		document.creditcardFrm.email.focus();
		return(false);
  }

}


function validator(){
	if(document.creditcardFrm.country.options[document.creditcardFrm.country.selectedIndex].text=="United States") {
		document.creditcardFrm.state.disabled = false;
	} else {
		document.creditcardFrm.state.disabled = true;
	}
	return false;
}



function mmsel(){
	document.write('<select name="mm" style="font-family:verdana;font-size:10px;WIDTH: 50px" >')
	document.write('<OPTION  value="">mm</option>') 
	var str
		for (var i = 0; i <=11;  i++){
		
			str=str + '<option value=' + (i+1) + ' >' +    (i+1)  + '</option>'
			
		}
	document.write(str)
	document.write ('</select>&nbsp;')
	yyyysel()
}
function yyyysel(){
	document.write('<select name="yyyy" style="font-family:verdana;font-size:10px;WIDTH: 60px" >')
	document.write('<OPTION  value="">year</option>') 
	var str
		for (var i = 2004; i <=2025;  i++){
		
			str=str + '<option value=' + (i) + ' >' +   (i)  + '</option>'
			
		}
	document.write(str)
	document.write ('</select>&nbsp;')
}
</script>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>
<form action="rebill_creditcardfb.php" method="post" name="creditcardFrm" onsubmit="return validation()">
<input type="hidden" name="type" value="<?=$mode?>">
<input type="hidden" name="tid" value="<?=$id?>">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
      <td align="center" valign="middle"><font size="2" face="Verdana" >Rebilling 
        Date :</font>&nbsp; <select name="opt_rebill_month" class="lineborderselect" style="font-size:10px">
          <?php func_fill_month($i_to_month); ?>
        </select> <select name="opt_rebill_day" class="lineborderselect" style="font-size:10px">
          <?php func_fill_day($i_to_day); ?>
        </select> <select name="opt_rebill_year" class="lineborderselect" style="font-size:10px">
          <?php func_fill_year($i_to_year); ?>
        </select> </td>
</tr>
  <tr>
    <td width="100%" valign="top" align="left">
        <table border="0" cellpadding="0" cellspacing="0" width="750" height="544" align="center" >
          <tr>
            <td width="100%" height="494" valign="top" align="left"> <table width="691" height="165"  align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
				<tr> 
                  <td height="11" valign="top" align="left" width="19">&nbsp;</td>
                  <td valign="top" align="left" width="652"  height="11"><img border="0" src="images/cbg.jpg" width="1" height="2"></td>
                  <td height="11" valign="top" align="left" width="28">&nbsp;</td>
                </tr>
                <tr> 
                  <td height="167" valign="top" align="left" width="19">&nbsp;</td>
                  <td height="167" valign="top" align="left" width="652" > 
                    <table width="100%" cellpadding="2" cellspacing="0" style="border:1px solid black">
						
                      <tr align="center" valign="middle" bgcolor="#78B6C2"> 
                        <td colspan="2" class="tdbdr" height="20"><span class="subhd"><strong>Customer 
                          Information</strong></span></td>
                      </tr>
                      <tr> 
                        <td width="47%" align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First 
                          Name : </font></td>
                        <td width="53%" valign="middle" class="tdbdr"><font color="#001188"> 
                          &nbsp; 
                          <input type="text" name="firstname" size="19" value="<?=$fname?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
                          Name :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="lastname" size="19"  value="<?=$sname?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Address: 
                          </font><br></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="address" size="30" value="<?=$billadd?>" <?=$modestatus?> >
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">City 
                          :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="city" size="20" value="<?=$city?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Country 
                          :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> 
                          &nbsp; 
<?	
			 
 			if ($country !="") {
					echo "<input type='text' name='country' value='$country' $modestatus>";
			 } else {
?>
                          <select name="country"  style="width:135px;font-height:10px;font-face:verdana" onchange="return validator()">
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
                            <option>Denmark </option>
                            <option>Djibouti </option>
                            <option>Dominica </option>
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
                            <option selected>United States </option>
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
                          </select>
<?
}
?>						  
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">State 
                          :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> 
                          &nbsp; 
<?	
 			if ($state !="") {
					echo "<input type='text' name='state' value='$state' $modestatus>";
			 } else {
?>                          <select name="state" style="width:100px;font-height:10px;font-face:verdana;">
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
                          </select>
<?
}
?>					  
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip 
                          code :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="zipcode" size="10"  value="<?=$zipcd?>" maxlength="9" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Phone 
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="telephone" size="10"  value="<?=$phonenumber?>" maxlength="10" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                          <input type="text" name="email" size="40" value="<?=$emailadd?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr bgcolor="#78B6C2"> 
                        <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Payment 
                          Information</strong></span></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card 
                          Number :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="number" size="17" maxlength="16"  value="<?=$cardnum?>" <?=$modestatus?>>
                          </font><font size="1" face="Verdana"><a href="#" onClick='javascript:window.open("images/creditcard.gif","","width=500,height=350")' class="link">CVV2</a></font><font color="#001188"> 
                          <input type="text" name="cvv2" size="3" maxlength="3" value="<?=$cvvnum?>" <?=$modestatus?>>
                          </font> </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Type 
                          : </font></td>
<?		 
if ($cardtyp =="Master"){
	$mSelect = "selected";
} elseif ($cardtyp =="Visa") {
	$vSelect = "selected";
}
?>
                        <td valign="middle" class="tdbdr">&nbsp; <select size="1" name="cardtype" style="font-size: 8pt; font-family: Verdana">
                            <option value="Master">Master Card</option>
                            <option value="Visa">Visa</option>
                          </select></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Expiration 
                          Date :</font></td>
                        <td valign="middle" class="tdbdr">&nbsp; 
<?				      if ($expdd !="") {
							$dateval= explode("/",$expdd);
?>     		
					  <script>
					  mmsel(<?=$dateval[0]?>,<?=$dateval[1]?>);
					  </script>
<?						} else {
?>						<script>
						mmsel(0,0);
						</script>
<? 						}
?> 
					  </td>
                      </tr>
					  <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount 
                          of Money (US Dollars):</font><br></td>
                        <td valign="middle" class="tdbdr"><font color="#000000"> &nbsp;
                          <input type="text" name="amount" size="21"  value="<?=$amt?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
					  <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing 
                          Date :</font></td>
                        <td valign="middle" class="tdbdr">&nbsp;<!--<font color="#001188">&nbsp;<input type="text" name="setbilldate" size="20">-->
						  <select name="opt_bill_month" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_month($i_to_month); ?>
						  </select>
						  <select name="opt_bill_day" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_day($i_to_day); ?>	
						  </select>
						  <select name="opt_bill_year" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_year($i_to_year); ?>
						  </select>
                          </font></td>
                      </tr>
                      <tr bgcolor="#78B6C2"> 
                        <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Shipping 
                          Information</strong></span></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
                          Authorization # : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="authorizationno" size="20"  value="<?=$voiceauth?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
                          Tracking # : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="shippingno" size="20" value="<?=$shippingno?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
                          Security # : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="securityno" size="20" value="<?=$securityno?>" maxlength="9" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
                          License : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="driverlicense" size="25" value="<?=$licenseno?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Misc 
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="misc" size="35" value="<?=$misc?>" <?=$modestatus?>>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time 
                          :</font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;
						<?php print func_get_date_time_12hr($dateToEnter);?></font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                          Address :</font> </td>
                        <td valign="middle" ><font size="1" face="Verdana" color="#000000">&nbsp; 
                          <?=$domain?>
                          <img border="0" src="images/mastercard.jpg"> </font></td>
                      </tr>
					  <input type="hidden" name="domain1" value="<?=$domain?>" >
                    </table></td>
                  <td height="2" valign="top" align="left" width="1" style="border-right:1px solid white"></td>
                </tr>
                <tr> 
                  <td height="1" valign="top" align="left" width="19">&nbsp;</td>
                  <td height="1" valign="top" align="left" width="652">&nbsp;</td>
                  <td height="1" valign="top" align="left" width="28">&nbsp;</td>
                </tr>
              </table>
            <br>
			</td>
			    </tr>
				 <tr>
      <td width="100%" valign="top" align="left">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
                  <td width="100%" align="center" height="23" valign="center"> 
<input type="image" name="add" src="images/submit.jpg"></input>	
			</td>
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
