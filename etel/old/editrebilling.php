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
//viewreportpage.php:	The admin page functions for displaying the company transactions. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$headerInclude="transactions";
include 'includes/topheader.php';
include 'includes/function2.php';
require_once('includes/function.php');

$sessionGatewaylogin =isset($HTTP_SESSION_VARS["sessionGatewaylogin"])?$HTTP_SESSION_VARS["sessionGatewaylogin"]:"";
$trans_recur_start_date ="";
$set_recurring ="";
$dayval ="";
$weekval ="";
$monthval ="";
$yearval ="";
$datevalue ="";
$weekvalue="";
$monthvalue="";
$yearmonthvalue ="";
$yeardayvalue="";
$checkorcard="";
$id = (isset($HTTP_POST_VARS['id'])?Trim($HTTP_POST_VARS['id']):"");

	  if($id=="")
	  {
	  $id = $HTTP_GET_VARS['id'];
	  }
	 				  $qrt_select_details ="select rebill_transactionid,name,surname,address,country,state,city,zipcode,checkorcard,CCnumber,cvv,amount,transactionDate,cancelstatus,status,userid,cardtype,validupto,misc,email,ipaddress,phonenumber,voiceAuthorizationno,shippingTrackingno,socialSecurity,driversLicense,billingDate,recur_mode,recur_day,recur_week,recur_month,recur_start_date,recur_charge,recur_times,checktype,bankname,bankroutingcode,bankaccountnumber,licensestate,misc,accounttype,productdescription from cs_rebillingdetails  where rebill_transactionid ='$id'";
		   /*                            0                   1      2      3      4        5    6    7      8            9      10    11     12                13          14     15     16        17       18  19    20          21         22                       23               24           25                 26          27        28       29         30          31                  32      33                                                                                                           41       */
		//   print $qrt_select_details;b.companyname,a.name,a.surname,a.transactionDate,a.checkorcard,a.CCnumber,a.checkto,a.amount,a.status,a.bankaccountnumber,a.bankroutingcode,a.phonenumber,a.address,a.country,a.city,a.state,a.zipcode,a.memodet,a.signature,a.email,a.transactionId,a.cardtype,a.validupto,a.reason,a.other,a.cvv,a.misc,a.Invoiceid,a.checktype,a.bankname,a.accounttype,a.ipaddress,a.cancelstatus,a.voiceAuthorizationno,a.shippingTrackingno,a.socialSecurity,a.driversLicense,a.chequedate,a.billingDate,a.licensestate,c.recur_mode,c.recur_day,c.recur_week,c.recur_month,c.recur_start_date,c.recur_charge,c.recur_times,a.userid,a.productdescription FROM cs_transactiondetails as a INNER JOIN cs_companydetails as b ON a.userid=b.userid LEFT OUTER JOIN cs_rebillingdetails as c ON a.transactionId = c.rebill_transactionid where a.transactionId=$id";
		   if(!($show_select_sql =mysql_query($qrt_select_details,$cnn_cs)))
		   {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		   }
		   



?>
<script language="javascript" src="scripts/general.js"></script>
<script language="javascript">
function showDetails(the_sub){
	if(the_sub =="div1" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.view.statusdiv1.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.view.statusdiv1.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
	} else if(the_sub =="div2" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.view.statusdiv2.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.view.statusdiv2.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
    } else if(the_sub =="div3" ){
	  if(document.getElementById(the_sub).style.display=="") {
	  	document.view.statusdiv3.value="";
	 	document.getElementById(the_sub).style.display = "none";
		return false;
  	  } else {
		document.view.statusdiv3.value=true;
		document.getElementById(the_sub).style.display = "";
	  }
	}
}

function cancelvalidation() {
	var recur_mode = "";
	for(i=0;i<document.view.recurdatemode.length;i++)
	{
		if(document.view.recurdatemode[i].checked)
		{
			recur_mode = document.view.recurdatemode[i].value;
			break;
		}
	}
	if(document.view.firstname1.value==""){
		alert("Please enter the First name")
		document.view.firstname1.focus();
		return false;
   } 
	 
	 if(document.view.lastname1.value==""){
		alert("Please enter the Last name")
		document.view.lastname1.focus();
		return false;
	  } 
	  
	  if(document.view.address.value==""){
		alert("Please enter address")
		document.view.address.focus();
		return false;
	  }
	  
	  if(document.view.city.value==""){
		alert("Please enter city")
		document.view.city.focus();
		return false;
	  } 
	  if(document.view.country.selectedIndex==0){
		alert("Please enter country")
		document.view.country.focus();
		return false;
	  }
	  if(document.view.zip.value==""){
		alert("Please enter zip code")
		document.view.zip.focus();
		return false;
	  }
	   if(document.view.country.value == "United States") {
 	 if(document.view.zip.value.length!=5 && document.view.zip.value.length!=9){
		alert("Please enter the correct zip code")
		document.view.zip.focus();
		return false;
	  }
	}
	  if(document.view.country.value == "United States") {
		  if(isNaN(document.view.zip.value)){
			alert("Please enter numeric values");
			document.view.zip.focus();
			return false;
		  }
	 }
	  if(document.view.phonenumber.value==""){
		alert("Please enter phone #")
		document.view.phonenumber.focus();
		return false;
	  }
	 
	if(document.view.amount.value==""){
		alert("Please enter amount")
		document.view.amount.focus();
		return false;
	} 
	if(!(checkAllowedChars(document.view.amount.value,'D'))) {
		alert("Please enter numeric values");
		document.view.amount.focus();
		return false;
	}
	if(document.view.amount.value.indexOf(".")<=0){
	     document.view.amount.value = document.view.amount.value + ".00";
		 		
				
	} 
	 if(document.view.crorcq1.value!="H")
	{
	
	if(document.view.authorizationno.value==""){
		alert("Please enter the voice authorization number.")
		document.view.authorizationno.focus();
		return false;
  	}
	if(isNaN(document.view.authorizationno.value)){
		alert("Please enter numeric values.")
		document.view.authorizationno.focus();
		return false;
  	}

	if(document.view.securityno.value==""){
		alert("Please enter the social security number.")
		document.view.securityno.focus();
		return false;
  	}
	if(isNaN(document.view.securityno.value)){
		alert("Please enter numeric values.")
		document.view.securityno.focus();
		return false;
  	}

 }
	if(document.view.chk_recur_date.checked)
	{
		if(recur_mode == "")
		{
			alert("Please select a recurring mode.")
			document.view.recurdatemode[0].focus();
			return false;

		}
		else if(recur_mode == "D"){
			if(document.view.recur_day.value == "")
			{
				alert("Please enter the recurring days.")
				document.view.recur_day.focus();
				return false;
			}
			else if(isNaN(document.view.recur_day.value))
			{
				alert("Please enter numeric values.")
				document.view.recur_day.focus();
				return false;
			}
		}
		if(document.view.recur_charge.value != "")
		{
			if(isNaN(document.view.recur_charge.value)){
				alert("Please enter numeric values");
				document.view.recur_charge.focus();
				return false;
			}
		}
		if(document.view.recur_times.value==""){
			alert("Please enter no: of rebillings")
			document.view.recur_times.focus();
			return false;
		}
		if(isNaN(document.view.recur_times.value)){
			alert("Please enter numeric values");
			document.view.recur_times.focus();
			return false;
		}
	}
	else{
		if(document.view.cancelreason.selectedIndex==0){
			document.view.cancelreason.value="";
		}
		return true;
	}

}

function func_submit()
{
	obj_form = document.view;
	//obj_form.method="post";
	obj_form.action="rebillinglist.php";
	obj_form.submit();
}
</script>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>
 <table border="0" cellpadding="0" width="800" cellspacing="0" align="center">
  <tr>
    <td width="100%" valign="top" align="center"><!--onsubmit="return cancelvalidation()"-->
	<form name="view" action="modifyrebilling.php" method="post" >
	<input type="hidden" name="id" value="<?=$id?>"></input>
<input type="hidden" name="transid" value="<?= $id?>">
	
	<!--<input type="hidden" name="tele_nontele_type" value="<?=$tele_nontele_type?>">-->
	
<? 

while($show_select_val = mysql_fetch_array($show_select_sql)) 
{
   $trans_recur_mode = $show_select_val[27];
   $trans_recur_day = $show_select_val[28];
   $trans_recur_week = $show_select_val[29];
   $trans_recur_month = $show_select_val[30];
   $trans_recur_start_date = $show_select_val[31];
   $i_to_month = substr($trans_recur_start_date,5,2);
   $i_to_day = substr($trans_recur_start_date,8,2);
   $i_to_year = substr($trans_recur_start_date,0,4);
   $trans_recur_charge = $show_select_val[32];
   $trans_recur_times = $show_select_val[33];
    
   $userid = $show_select_val[15];
	if($trans_recur_mode !="") {
		$set_recurring ="checked";
	}
	if($trans_recur_mode =="D"){
		$datevalue=  $trans_recur_day;
		$dayval = "Checked";
	}elseif($trans_recur_mode =="W") {
		$weekvalue= $trans_recur_week; 
		$weekval = "Checked";
	}elseif($trans_recur_mode =="M") {
		$monthvalue=  $trans_recur_day;
		$monthval = "Checked";
	}elseif($trans_recur_mode =="Y") {
		$yearmonthvalue=  $trans_recur_month;
		$yeardayvalue = $trans_recur_day;
		$yearval = "Checked";
	}
?>
<input type="hidden" name="userid" value="<?=$userid?>" >
<?php $checkorcard = $show_select_val[8]; ?>
<input type="hidden" name="checkorcard" value="<?=$checkorcard?>">
<?	
 if($show_select_val[8]=="H")
 {
 ?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" align="center">
  <tr>
       <td width="100%" valign="top" align="center"  >
    &nbsp;
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		          <td height="22" align="left" valign="top" width="10" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		          <td height="22" align="center" valign="middle" width="375" background="images/menucenterbg.gif" ><span class="whitehd">Recurring&nbsp; 
                    Transactions</span></td>
		          <td height="22" align="left" valign="top" width="49" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		          <td height="22" align="left" valign="top" width="356" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		          <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="10" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		          <td class="lgnbd" colspan="5"> 
<table border="0" cellpadding="0" cellspacing="0" width="750" height="544" align="center" >
         	 <tr>
                        <td width="100%" height="494" valign="top" align="left"> 
                          <table width="691" height="165"  align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                            <tr> 
                              <td height="11" valign="top" align="left" width="19">&nbsp;</td>
                              <td valign="top" align="left" width="652"  height="11"><img border="0" src="images/cbg.jpg" width="1" height="2"></td>
                              <td height="11" valign="top" align="left" width="28">&nbsp;</td>
                            </tr>
                            <tr> 
                              <td height="167" valign="top" align="left" width="19">&nbsp;</td>
                              <td height="167" valign="top" align="left" width="100%" > 
                                <table width="100%" cellpadding="2" cellspacing="0" style="border:1px solid black">
                                  <tr align="center" valign="middle" bgcolor="#78B6C2"> 
                                    <td colspan="2" class="tdbdr"><span class="subhd"><strong>Customer 
                                      Information</strong></span></td>
                                  </tr>
                                  <tr> 
                                    <td width="47%" align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First 
                                      Name : </font></td>
                                    <td width="53%" valign="middle" class="tdbdr"><font color="#001188"> 
                                      &nbsp; 
                                      <input type="text" name="firstname12" size="19" maxlength="75" value="<?=$show_select_val[1]?>" >
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
                                      Name :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> 
                                      &nbsp; 
                                      <input type="text" name="lastname12" size="19" maxlength="75" value="<?=$show_select_val[2]?>" >
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Address: 
                                      </font><br></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> 
                                      &nbsp; 
                                      <input type="text" name="address2"size="45" maxlength="100" value="<?=$show_select_val[3]?>" >
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">City 
                                      :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> 
                                      &nbsp; 
                                      <input type="text" name="city2"  size="35" maxlength="50" value="<?=$show_select_val[6]?>" >
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Country 
                                      :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> 
                                      &nbsp;  <!--	<input type="text" name="country2" size="20" value="<?=$show_select_val[13]?>" > //from below onchange="return validator()"-->
                                       &nbsp;<select name="country"  style="font-family:arial;font-size:10px;width:150px" > 
						
						<script language="javascript">
							 showCountries();
						</script>	
						</select>	
						<script language="javascript">
							document.view.country.value='<?=$show_select_val[4]?>';	
						</script>	
                                    
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">State 
                                      :</font></td>
                                    <td valign="middle" class="tdbdr">&nbsp; <select name="state" style="font-family:arial;font-size:10px;width:150px">
									<script language="javascript">
							 	showStates();
								</script>
									</select> 
									<script language="javascript">
							document.view.state.value='<?=$show_select_val[5]?>';	
						</script>
									</td>                              
                                  </tr>
								  <tr> 
                        <td height="30" align='right'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Other 
                          State</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<input type="text" name="ostate"  class="normaltext" style="width:200px" value="<?=$show_select_val[5]?>"></input></td>
                      </tr>
								  
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip 
                                      code :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> 
                                      &nbsp; 
                                      <input type="text" name="zip2"  size="15" maxlength="15" value="<?=$show_select_val[7]?>">
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Phone 
                                      : </font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> 
                                      &nbsp; 
                                      <input type="text" name="phonenumber2" size="20" maxlength="20" value="<?=$show_select_val[21]?>" >
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                                      email confirmation of&nbsp;&nbsp;<br>
                                      this order will be sent to :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                                      <input type="text" name="email22" size="40" maxlength="100" value="<?=$show_select_val[19]?>" >
                                      </font></td>
                                  </tr>
                                  <tr bgcolor="#78B6C2"> 
                                    <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Payment 
                                      Information</strong></span></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card 
                                      Number :</font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188"> 
                                      &nbsp; 
                                      <input type="text" name="number" size="17" maxlength="16" value="<?=$show_select_val[9]?>" >
                                      </font><font size="1" face="Verdana"><a href="#" onClick='javascript:window.open("images/creditcard.gif","","width=500,height=350")' class="link">CVV2</a></font><font color="#001188"> 
                                      <input type="text" name="cvv2" size="3" maxlength="3"  value="<?=$show_select_val[10]?>" >
                                      </font> </td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Type 
                                      : </font></td>
                                    <td valign="middle" class="tdbdr">&nbsp; 
                                      <!-- <input type="text" name="ctype" size="20" value="<?=$show_select_val[21]?>"  > -->
                                      <select size="1" name="cardtype" style="font-size: 8pt; font-family: Verdana">
                                        
										
                                        <option value="Master"<?php print( $show_select_val[16]=="Master" ? "selected" : ""); ?> >Master Card</option>
										<option value="Visa"<?php print( $show_select_val[16]=="Visa" ? "selected" : ""); ?> >Visa</option>
                                      </select> <script language="javascript">
							 	
						</script> </td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Expiration 
                                      Date :</font></td>
                                    <td valign="middle" class="tdbdr">&nbsp; 
                                      <!--<input type="text" name="expdate" size="20" value="<?=$show_select_val[22]?>" > -->
                                      <?php 
					  $exp_year="";
					  $exp_mm="";
					  $exp_year = substr($show_select_val[17],0,4);
					  $exp_mm =  substr($show_select_val[17],5,6);
					  ?>
                                      <select name="opt_exp_month" class="lineborderselect" style="font-size:10px">
                                        <?php func_fill_month($exp_mm); ?>
                                      </select> <select name="opt_exp_year" class="lineborderselect" style="font-size:10px">
                                        <?php func_fill_year($exp_year); ?>
                                      </select> </td>
                                  </tr>
                                  <tr> 
                                    <td  align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount 
                                      of Money :</font><br></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                                      <input type="text" name="amount2" size="15" maxlength="50" value="<?=$show_select_val[11]?>" >
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Billing 
                                      Date(mm-dd-yyyy) : </font></td>
                                    <td class="tdbdr">&nbsp;<font color="#001188"> 
                                      <!--<input type="text" name="setbilldate" size="20" value="<?=func_get_date_inmmddyy($show_select_val[38])?>" > -->
                                      <?php 
				   $trans_recur_start_date = $show_select_val[31];
				   $i_to_month1 = substr($trans_recur_start_date,5,2);
				   $i_to_day1 = substr($trans_recur_start_date,8,2);
				   $i_to_year1 = substr($trans_recur_start_date,0,4);

?>
                                      <select name="select1" class="lineborderselect" style="font-size:10px">
                                        <?php func_fill_month($i_to_month1); ?>
                                      </select>
                                      <select name="select2" class="lineborderselect" style="font-size:10px">
                                        <?php func_fill_day($i_to_day1); ?>
                                      </select>
                                      <select name="select3" class="lineborderselect" style="font-size:10px">
                                        <?php func_fill_year($i_to_year1); ?>
                                      </select>
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Product 
                                      Description : </font></td>
                                    <td class="tdbdr">&nbsp;<font color="#001188"> 
                                      <input type="text" name="txtproductDescription2" size="30" maxlength="200" value="<?=$show_select_val[41]?>" >
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time 
                                      :</font></td>
                                    <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp; 
                                      <?=date('Y-m-d h:i:s')?>
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                                      Address :</font> </td>
                                    <td valign="middle" ><font size="1" face="Verdana" color="#000000">&nbsp; 
                                      <?=$show_select_val[31]?>
                                      </font> <img border="0" src="images/mastercard.jpg"> 
                                      <img border="0" src="images/visa.jpg"> 
                                    </td>
                                  </tr>
                                  <tr bgcolor="#78B6C2"> 
                                    <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Set 
                                      Recurring Date</strong>&nbsp; 
                                      <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                                      </span></td>
                                  </tr>
                                  <tr> 
                                    <td colspan="2"> <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                      </table></tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Day 
                                      <input type="radio" name="recurdatemode" value="D" <?=$dayval?>>
                                      </font></td>
                                    <td valign="middle" class="tdbdr" width="53%"> 
                                      <font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                                      <input type="text" name="recur_day2" size="3" value="<?=$datevalue?>" >
                                      Days</font> </td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Week 
                                      <input type="radio" name="recurdatemode" value="W" <?=$weekval?>>
                                      </font></td>
                                    <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font> 
                                      <select name="selectWeekDay" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
                                       <?php $weekvalue ="$show_select_val[29]"; ?>
									    <option value="0">&nbsp;</option>
                                        <option value="1" <?php print($weekvalue == "1" ? "selected" : ""); ?> >Sunday</option>
                                        <option value="2" <?php print($weekvalue == "2" ? "selected" : ""); ?> >Monday</option>
                                        <option value="3" <?php print($weekvalue == "3" ? "selected" : ""); ?> >Tuesday</option>
                                        <option value="4" <?php print($weekvalue == "4" ? "selected" : ""); ?> >Wednesday</option>
                                        <option value="5" <?php print($weekvalue == "5" ? "selected" : ""); ?> >Thursday</option>
                                        <option value="6" <?php print($weekvalue == "6" ? "selected" : ""); ?> >Friday</option>
                                        <option value="7" <?php print($weekvalue == "7" ? "selected" : ""); ?> >Saturday</option>
                                      </select> </td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Month 
                                      <input type="radio" name="recurdatemode" value="M" <?=$monthval?>>
                                      </font></td>
                                    <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font> 
                                      <select name="selMonth" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
                                        <option value="0">&nbsp;</option>
										<?php  $monthvalue ="$show_select_val[28]"; ?>
                                        <?php  func_fill_day("$monthvalue");?>
                                      </select> </td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Year 
                                      <input type="radio" name="recurdatemode" value="Y" <?=$yearval?>>
                                      </font></td>
                                    <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                                      <select name="selectym" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
                                        <option value="0">&nbsp;</option>
                                        <?php    $yearmonthvalue=$show_select_val[30]=func_fill_month("$yearmonthvalue");?>
                                      </select>
                                       <?php  $yeardayvalue ="$show_select_val[28]"; ?>
									   <select name="selyear_day" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
                                        <option value="0">&nbsp;</option>
                                        <?php func_fill_day("$yeardayvalue");?>
                                      </select>
                                      </font></td>
                                  </tr>
                                  <tr> 
                                    <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Recurring 
                                      Start Date : </font></td>
                                    <td class="tdbdr">&nbsp; 
                                      <!--<font color="#001188"><input type="text" name="setbilldate" size="20"></font>-->
                                      <select name="select_re_mon" class="lineborderselect" style="font-size:10px">
                                        <option value="0">&nbsp;</option>
                                        <?php func_fill_month($i_to_month); ?>
                                      </select> <select name="select_re_day" class="lineborderselect" style="font-size:10px">
                                        <option value="0">&nbsp;</option>
                                        <?php func_fill_day($i_to_day); ?>
                                      </select> <select name="select_re_year" class="lineborderselect" style="font-size:10px">
                                     <!-- --> <option value="0">&nbsp;</option>
                                        <?php func_fill_year($i_to_year); ?>
                                      </select> </td>
                                  </tr>
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Recurring 
                                      Charge : </font></td>
                                    <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                                      <input type="text" name="recur_charge2"size="10" maxlength="30" value="<?=$show_select_val[32]?>" >
                                      <font size="1" face="Verdana" color="#000000">$ 
                                      (Leave blank if same as original amount)</font> 
                                      </font></td>
                                  </tr>
                                  <tr> 
                                  <tr> 
                                    <td align="right" valign="middle" class="tdbdr2" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">No: 
                                      of Recurring Transactions : </font></td>
                                    <td valign="middle"><font color="#001188">&nbsp; 
                                      <input type="text" name="recur_times2"  size="5" maxlength="15"value="<?=$trans_recur_times?>" >
                                      </font></td>
                                  </tr>
                                </table></td>
                            </tr>
                          </table>
                  
              <input type="hidden" name="cancel" value=""></input>
		<table align="center"><tr><td><a href="#" onclick="func_submit()"><img   src="images/back.jpg" border="0"></a>&nbsp;<input type="image" id="viewreport" src="images/submitcompanydetails.jpg"></input></td></tr></table>
	</td>
      </tr>
    </table>
    </td>
     </tr>
	<tr>
	              <td width="10"><img src="images/menubtmleft.gif"></td>
	              <td colspan="3" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	              <td width="10" ><img src="images/menubtmright.gif"></td>
	</tr>
	 
</table>
<?	
	} 
	else 
	{
?>
	<table border="0" cellpadding="0" width="100%" cellspacing="0"  align="center">
  <tr>
       <td width="90%" valign="top" align="center" >
    &nbsp;
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		                <td height="22" align="left" valign="top" width="10" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		                <td height="22" align="center" valign="middle" width="350" background="images/menucenterbg.gif" ><span class="whitehd">Check&nbsp;Transaction</span></td>
		                <td height="22" align="left" valign="top" width="51" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		                <td height="22" align="left" valign="top" width="379" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		                <td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="10" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		                <td class="lgnbd" colspan="5"> 
<table border="0" cellpadding="0" cellspacing="0" width="700" align="center">
          <tr>
			<td width="100%" valign="top" align="left">&nbsp; 
			<table width="100%" cellspacing="0" cellpadding="2" style="border:1px solid black">
               <tr bgcolor="#78B6C2"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Customer 
                    Information</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div2')">show/hide</a>-->
                    </span></td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%"><font size="2" face="Verdana" color="#000000"> 
                          First Name : </font></td>
                                          <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="firstname12" size="19" maxlength="75" value="<?=$show_select_val[1]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Last 
                          Name : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="lastname12" size="19" maxlength="75" value="<?=$show_select_val[2]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Address 
                          : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="address2"size="45" maxlength="100" value="<?=$show_select_val[3]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">City 
                          : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
                                            <input type="text" name="city2"  size="35" maxlength="50" value="<?=$show_select_val[6]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Country 
                          : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
						<!--<input type="text" name="country" size="15" value="<?=$show_select_val[13]?>" >-->
						<select name="country"  style="font-family:arial;font-size:10px;width:150px" > 
						
						<script language="javascript">
							 showCountries();
						</script>	
						</select>	
						<script language="javascript">
							document.view.country.value='<?=$show_select_val[4]?>';	
						</script>		
						
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">State 
                          : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
						<!-- <input type="text" name="state" size="15" value="<?=$show_select_val[15]?>" > -->
						<select name="state" style="font-family:arial;font-size:10px;width:150px">
									<script language="javascript">
							 	showStates();
								</script>
									</select> 
									<script language="javascript">
							document.view.state.value='<?=$show_select_val[5]?>';	
						</script>
						
						</font></td>
                      </tr>
					  		  <tr> 
                        <td height="30" align='right'  class='cl1'><font face='verdana' size='1'><b>&nbsp;Other 
                          State</b></font></td>
                        <td height="30" align='left' class='cl1'>
                          &nbsp;<input type="text" name="ostate"  class="normaltext" style="width:200px" value="<?=$show_select_val[5]?>"></input></td>
                      </tr>
					  
                      <tr> 
                        <td a align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Zip 
                          code : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="zip2"  size="15" maxlength="15" value="<?=$show_select_val[7]?>">
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Telephone 
                          # : </font></td>
                                          <td class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="phonenumber2" size="20" maxlength="20" value="<?=$show_select_val[21]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to </font> : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
						<input type="text" name="email22" size="40" maxlength="100" value="<?=$show_select_val[19]?>" >
						</font></td>
                      </tr>
					  
				<tr bgcolor="#78B6C2"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><strong><span class="subhd">Transaction 
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
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Check 
                          # : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="checknumber" size="17" maxlength="16" value="<?=$show_select_val[9]?>" >
                                            </font></td>
                </tr>
                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Check 
                    Type : </font></td>
			  <td class="tdbdr" valign="middle">&nbsp;&nbsp;
			<!--	<input type="text" name="typec" size="10"  value="<?=$show_select_val[28]?>" >  -->
			<?PHP $checkb="";
				  $checkp="";
				if($show_select_val[34] =="personal"){
					$checkp="checked";
				} else {
					$checkb="checked";
				}
			?>
				<input type="radio" name="chequetype" value="personal" <?=$checkp?>><font size="1" face="Verdana" color="#000000">Personal</font>&nbsp;&nbsp;<input type="radio" name="chequetype" value="business" <?=$checkb?>><font size="1" face="Verdana" color="#000000">Business</font>
			  </td>
                </tr>
                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Amount(US 
                    Dollars) : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
                                            <input type="text" name="amount"size="9" maxlength="50" value="<?=$show_select_val[11]?>" >
                                            </font></td>
                </tr>
                <tr> 
                  <td class="tdbdr1" align="right"><font size="2" face="Verdana" color="#000000">Account 
                    Type : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
						<!-- <input type="text" name="account" size="10" value="<?=$show_select_val[30]?>" > -->
					
						<?php 
						$checks="";
						$checkc="";
						if($show_select_val[40] =="checking") {
							$checkc="Checked";
						} else {
							$checks="Checked";
						}
						?>
						<input type="radio" name="accounttype" value="checking" <?=$checkc?>><font size="1" face="Verdana" color="#000000">Checking</font>&nbsp;&nbsp;<input type="radio" name="accounttype" value="savings" <?=$checks?>><font size="1" face="Verdana" color="#000000">Savings</font>
						</font>
				   </td>
                </tr>
                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Billing 
                    Date(mm-dd-yyyy) : </font></td>
				  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188"> 
				<!--	<input type="text" name="setbilldate2" size="20" value="<?=func_get_date_inmmddyy($show_select_val[38])?>" > -->
				<?php 
				   $trans_recur_start_date = $show_select_val[31];
				    $i_to_month1 = substr($trans_recur_start_date,5,2);
				   $i_to_day1 = substr($trans_recur_start_date,8,2);
				   $i_to_year1 = substr($trans_recur_start_date,0,4);

?>
				   <select name="opt_bill_month" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_month($i_to_month1); ?>
						  </select>
						  <select name="opt_bill_day" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_day($i_to_day1); ?>	
						  </select> <?=$i_to_year1?>
						  <select name="opt_bill_year" class="lineborderselect" style="font-size:10px">
						  <?php func_fill_year($i_to_year1); ?>
						  </select>
					</font> </td>
                </tr>
				<tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Product Description : </font></td>
                                          <td class="tdbdr">&nbsp;<font color="#001188"> 
                                            <input type="text" name="txtproductDescription" size="30" maxlength="200" value="<?=$show_select_val[41]?>" >
                                            </font></td>
                </tr>
				
				
                <tr bgcolor="#78B6C2"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Bank 
                    Information</strong>&nbsp;
                    <!-- <a href="javascript:void(0)" onclick="showDetails('div1')">show/hide</a> -->
                    </span></td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%"><font size="2" face="Verdana" color="#000000">Bank 
                          Name : </font></td>
                                                <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                                                  <input type="text" name="bankname" size="45" maxlength="75" value="<?=$show_select_val[35]?>" >
                                                  </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Bank 
                          Routing Code : </font></td>
                                                <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                                  <input type="text" name="bankroutingcode" size="9" maxlength="9" value="<?=$show_select_val[36]?>" >
                                                  </font></td>
                      </tr>
                      <tr> 
                              <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font size="2" face="Verdana" color="#000000">Bank 
                                Account # : </font></font></td>
                                                <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                                  <input type="text" name="bankaccountno"size="25" maxlength="15"value="<?=$show_select_val[37]?>" >
                                                  </font></td>
                      </tr>
					  
					  
                    </table></td>
                </tr>
                    </table>
                  </td>
                </tr>
                <tr bgcolor="#78B6C2"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Shipping 
                    Information</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                    </span></td>
                </tr>
                <tr> 
              	<td colspan="2"> 
				<table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" width="50%"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
                          Authorization # : </font></td>
                                          <td valign="middle" class="tdbdr" width="50%"> <font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="authorizationno"  size="25" maxlength="25" value="<?=$show_select_val[22]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
                          Tracking # : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="shippingno"size="20" maxlength="50" value="<?=$show_select_val[23]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
                          Security # : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="securityno"size="9" maxlength="9" value="<?=$show_select_val[24]?>" >
                                            </font></td>
                      </tr>
						<tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">License 
                          State : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
					<!--    <input type="text" name="licensestate" size="20" value="<?=$show_select_val[38]?>" > -->
					<select name="licensestate" style="font-family:arial;font-size:10px;width:150px">
									<script language="javascript">
							 	showStates();
								</script>
									</select> 
									<script language="javascript">
							document.view.state.value='<?=$show_select_val[38]?>';	
						</script>
		
						</font></td>
                      </tr>                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
                          License # : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="driverlicense"size="25" maxlength="100"value="<?=$show_select_val[25]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Misc 
                          : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="misc" size="35" maxlength="100" value="<?=$show_select_val[39]?>" >
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time 
                          :</font></td>
                                          <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp; 
                                            <?=date('Y-m-d h:i:s')?>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                          Address :</font> </td>
                                          <td valign="middle"><font size="1" face="Verdana" color="#000000"> 
                                            &nbsp;&nbsp; 
                                            <?=$show_select_val[20]?>
                                            </font> </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <!--Div -->
				<tr bgcolor="#78B6C2"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Set Recurring Date</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div3')">show/hide</a>-->
                    </span></td>
                </tr>
                <tr> 
                  <td colspan="2"> 
                    <table width="100%" cellpadding="0" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" width="50%" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Set Recurring Date : </font></td>
                                          <td valign="middle" class="tdbdr" width="50%">&nbsp; </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Day <input type="radio" name="recurdatemode" value="D" <?=$dayval?>> </font></td>
                        <td valign="middle" class="tdbdr" width="50%"> <font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                          <input type="text" name="recur_day1" size="3" value="<?=$show_select_val[28]?>" > Days</font>
                          </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Week <input type="radio" name="recurdatemode" value="W" <?=$weekval?>></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font> 
						  <select name="recur_week2" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
                          <option value="0">&nbsp;</option>
						  <?php $weekvalue ="$show_select_val[29]";?>
						  <option value="1" <?php print($weekvalue == "1" ? "selected" : ""); ?> >Sunday</option>
                          <option value="2" <?php print($weekvalue == "2" ? "selected" : ""); ?> >Monday</option>
                          <option value="3" <?php print($weekvalue == "3" ? "selected" : ""); ?> >Tuesday</option>
                          <option value="4" <?php print($weekvalue == "4" ? "selected" : ""); ?> >Wednesday</option>
                          <option value="5" <?php print($weekvalue == "5" ? "selected" : ""); ?> >Thursday</option>
                          <option value="6" <?php print($weekvalue == "6" ? "selected" : ""); ?> >Friday</option>
                          <option value="7" <?php print($weekvalue == "7" ? "selected" : ""); ?> >Saturday</option>
						  </select>
						  </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Month <input type="radio" name="recurdatemode" value="M" <?=$monthval?>></font></td>
                                          <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp;</font> 
                                            <select name="recur_mday" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
                                              <option value="0">&nbsp;</option>
                                              <?php $monthvalue=$show_select_val[28];  func_fill_day("$monthvalue");?>
                                            </select> </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Year <input type="radio" name="recurdatemode" value="Y" <?=$yearval?>></font></td>
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp;Every&nbsp; 
                          <select name="recur_year_month" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
							<option value="0">&nbsp;</option>
							$yearmonthvalue=$show_select_val[29]
							<?php $yearmonthvalue=$show_select_val[30]; func_fill_month("$yearmonthvalue");?>
						  </select>
                          <select name="recur_year_day" style="font-size:11px;width:40px;font-height:10px;font-face:verdana;">
							<option value="0">&nbsp;</option>
							<?php $yeardayvalue= $show_select_val[28];func_fill_day("$yeardayvalue");?>
						  </select>
                          </font></td>
                      </tr>
					<tr> 
					  <td align="right" class="tdbdr1" height="30"><font size="2" face="Verdana" color="#000000">Recurring Start 
						Date : </font></td>
					  <td class="tdbdr">&nbsp;
						<!--<font color="#001188"><input type="text" name="setbilldate" size="20"></font>-->
						<select name="opt_recur_month" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_month($i_to_month); ?>
						</select> <select name="opt_recur_day" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_day($i_to_day); ?>
						</select> <select name="opt_recur_year" class="lineborderselect" style="font-size:10px">
							<option value="0">&nbsp;</option>
						  <?php func_fill_year($i_to_year); ?>
						</select> </td>
					</tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Rebilling Charge 
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                          <input type="text" name="recur_charge2"  size="10" maxlength="30" value="<?=$show_select_val[32]?>" ><font size="1" face="Verdana" color="#000000">$ (Leave blank if same as original amount)</font>
                          </font></td>
                      </tr>
                      <tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">No: of Recurring Transactions 
                          : </font></td>
                        <td valign="middle"><font color="#001188">&nbsp; 
                          <input type="text" name="recur_times2"size="5" maxlength="20" value="<?=$trans_recur_times?>" >
                          </font></td>
                      </tr>
					</table>
				</td>
			 </tr>
              </table>	
							
				<table width="100%" align="center" height="50">
				<tr>
						  
				  </tr>
			</table>
            </td>
          </tr>
        </table>
	<input type="hidden" name="cancel" value="cancel"></input>
		<table align="center"><tr><td><a href="#" onclick="func_submit()"><img   src="images/back.jpg" border="0"></a>&nbsp;<input type="image" id="viewreport" src="images/submitcompanydetails.jpg"></input></td></tr></table>
	</td>
      </tr>
	<tr>
	                    <td width="10"><img src="images/menubtmleft.gif"></td>
	                    <td colspan="3" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	                    <td width="10" ><img src="images/menubtmright.gif"></td>
	</tr>
    </table>
    </td>
     </tr>
</table>

<?php
			}	
		}


	
?>
		    </td>
     </tr>
</table>
</form>	</td></tr></table>	
<?php
include 'includes/footer.php';

?>
