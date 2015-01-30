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
include '../includes/dbconnection.php';
$headerInclude="negativedatabase";
include 'includes/header.php';


require_once( '../includes/function.php');

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$id = (isset($HTTP_GET_VARS['id'])?quote_smart($HTTP_GET_VARS['id']):"");

if($sessionAdmin!="")
{  
	$qrt_select_details ="select b.companyname,a.name,a.surname,a.transactionDate,a.checkorcard,a.CCnumber,a.checkto,a.amount,a.status,a.bankaccountnumber,a.bankroutingcode,a.phonenumber,a.address,a.country,a.city,a.state,a.zipcode,a.memodet,a.signature,a.email,a.transactionId,a.cardtype,a.validupto,a.reason,a.other,a.cvv,a.misc,a.Invoiceid,a.checktype,a.bankname,a.accounttype,a.ipaddress,a.cancelstatus,a.voiceAuthorizationno,a.shippingTrackingno,a.socialSecurity,a.driversLicense,a.chequedate,a.billingDate,a.licensestate from cs_transactiondetails as a,cs_companydetails as b where a.transactionId=$id and a.userid=b.userid";
	if(!($show_select_sql =mysql_query($qrt_select_details,$cnn_cs)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
?>
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

function func_submit()
{
	obj_form = document.view;
	obj_form.method="post";
	obj_form.action="negativedatabase.php";
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
    <td width="100%" valign="top" align="center">
<form name="view" action="viewreportpage.php" method="get" onsubmit="cancelvalidation()">
<input type="hidden" name="id" value="<?=$id?>"></input>
<input type="hidden" name="statusdiv1" value="">
<input type="hidden" name="statusdiv2" value="">
<input type="hidden" name="statusdiv3" value="">
<?php
$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

?>
<input type="hidden" name="hid_enquiry" value="negative">
<input type="hidden" name="opt_from_year" value="<?= $i_from_year?>">
<input type="hidden" name="opt_from_month" value="<?= $i_from_month?>">
<input type="hidden" name="opt_from_day" value="<?= $i_from_day?>">
<input type="hidden" name="opt_to_year" value="<?= $i_to_year?>">
<input type="hidden" name="opt_to_month" value="<?= $i_to_month?>">
<input type="hidden" name="opt_to_day" value="<?= $i_to_day?>">
<? 

while($show_select_val = mysql_fetch_array($show_select_sql)) 
{
 if($show_select_val[4]=="H")
 {
 ?>

<table border="0" cellpadding="0" width="100%" cellspacing="0" align="center">
  <tr>
       <td width="90%" valign="top" align="center"  >
    &nbsp;
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Credit&nbsp; 
                    Card&nbsp;Transaction</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
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
						
                      <tr align="center" valign="middle" bgcolor="#CCCCCC"> 
                        <td colspan="2" class="tdbdr"><span class="subhd"><strong>Customer 
                          Information</strong></span></td>
                      </tr>
                      <tr> 
                        <td width="47%" align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First 
                          Name : </font></td>
					  <td width="53%" valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="firstname2" size="19" value="<?=$show_select_val[1]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last 
                          Name :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="lastname2" size="19" value="<?=$show_select_val[2]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount 
                          of Money :</font><br></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="amount2" size="21" value="<?=$show_select_val[7]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Address: 
                          </font><br></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="address2" size="30" value="<?=$show_select_val[12]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">City 
                          :</font></td>
				  <td valign="middle" class="tdbdr"><font color="#001188"> 
					&nbsp; 
					<input type="text" name="city2" size="20" value="<?=$show_select_val[14]?>" disabled>
					</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Country 
                          :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="country2" size="20" value="<?=$show_select_val[13]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">State 
                          :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="state2" size="20" value="<?=$show_select_val[15]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip 
                          code :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="zipcode" size="13" value="<?=$show_select_val[16]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Phone 
                          : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="telephone" size="17" value="<?=$show_select_val[11]?>" disabled>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to :</font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
						<input type="text" name="email2" size="40" value="<?=$show_select_val[19]?>" disabled>
						</font></td>
                      </tr>
                      <tr bgcolor="#CCCCCC"> 
                        <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Payment 
                          Information</strong></span></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card 
                          Number :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="number" size="17" maxlength="16" value="<?=$show_select_val[5]?>" disabled>
						</font><font size="1" face="Verdana"><a href="#" onClick='javascript:window.open("../images/creditcard.gif","","width=500,height=350")' class="link">CVV2</a></font><font color="#001188"> 
						<input type="text" name="cvv2" size="3" maxlength="3"  value="<?=$show_select_val[25]?>" disabled>
						</font> </td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Type 
                          : </font></td>
						  <td valign="middle" class="tdbdr">&nbsp; <input type="text" name="ctype" size="20" value="<?=$show_select_val[21]?>" disabled ></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Expiration 
                          Date :</font></td>
					  <td valign="middle" class="tdbdr">&nbsp; <input type="text" name="expdate" size="20" value="<?=$show_select_val[22]?>" disabled></td>
                      </tr>
					<tr>
					  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Billing 
						Date(mm-dd-yyyy) : </font></td>
					  <td class="tdbdr">&nbsp;<font color="#001188"> 
						<input type="text" name="setbilldate" size="20" value="<?=func_get_date_inmmddyy($show_select_val[38])?>" disabled>
						</font></td>
					</tr>
                      <tr bgcolor="#CCCCCC"> 
                        <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Shipping 
                          Information</strong></span></td>
                      </tr>
                      <tr>
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
                          Authorization # : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="authorizationno" size="20" value="<?=$show_select_val[33]?>" disabled>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
                          Tracking # : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="shippingno" size="20" value="<?=$show_select_val[34]?>" disabled>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
                          Security # : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="securityno" size="20" value="<?=$show_select_val[35]?>" disabled>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
                          License : </font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="driverlicense" size="25" value="<?=$show_select_val[36]?>" disabled>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Misc 
                          : </font></td>
					  <td valign="middle" class="tdbdr"><font color="#001188"> 
						&nbsp; 
						<input type="text" name="misc" size="25" value="<?=$show_select_val[26]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time 
                          :</font></td>
							<td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp; 
							  <?=func_get_date_time_12hr($show_select_val[3])?>
							  </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                          Address :</font> </td>
					  <td valign="middle" ><font size="1" face="Verdana" color="#000000">&nbsp;
						<?=$show_select_val[31]?>
						</font>
						<img border="0" SRC="<?=$tmpl_dir?>/images/mastercard.jpg"> 
						</td>
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
		<table align="center"><tr><td><a href="#" onclick="func_submit()"><img   SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a></td></tr></table>
	</td>
      </tr>
    </table>
    </td>
     </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
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
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		                <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Check&nbsp;Transaction</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">

			
        <table border="0" cellpadding="0" cellspacing="0" width="700" align="center">
          <tr>
			<td width="100%" valign="top" align="left">&nbsp; 
			<table width="100%" cellspacing="0" cellpadding="2" style="border:1px solid black">
               <tr bgcolor="#CCCCCC"> 
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Customer 
                    Information</strong>&nbsp;
                    <!--<a href="javascript:void(0)" onclick="showDetails('div2')">show/hide</a>-->
                    </span></td>
                </tr>
                <!--Div starting for Customer Info.-->
                <tr> 
                  <td colspan="2"> 
                    <!--<div id="div2" style="display:none"> -->
                    <table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%"><font size="2" face="Verdana" color="#000000"> 
                          First Name : </font></td>
                                          <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="firstname" size="19" value="<?=$show_select_val[1]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Last 
                          Name : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="lastname" size="19" value="<?=$show_select_val[2]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Address 
                          : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="address" size="45" value="<?=$show_select_val[12]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">City 
                          : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
                                            <input type="text" name="city" size="15" value="<?=$show_select_val[14]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Country 
                          : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
						<input type="text" name="country" size="15" value="<?=$show_select_val[13]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">State 
                          : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
						<input type="text" name="state" size="15" value="<?=$show_select_val[15]?>" disabled>
						</font></td>
                      </tr>
                      <tr> 
                        <td a align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Zip 
                          code : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="zip" size="10" value="<?=$show_select_val[16]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Telephone 
                          # : </font></td>
                                          <td class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="phonenumber" size="20" value="<?=$show_select_val[11]?>" disabled>
                                            </font></td>
                      </tr>
                     <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to </font> : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="email22" size="40" value="<?=$show_select_val[19]?>" disabled>
                                            </font></td>
                      </tr>
					  
				<tr bgcolor="#CCCCCC"> 
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
                                            <input type="text" name="chequenumber" size="20" value="<?=$show_select_val[5]?>" disabled>
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
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Check 
                    Type : </font></td>
                                          <td class="tdbdr" valign="middle">&nbsp;&nbsp;
                                            <input type="text" name="typec" size="10"  value="<?=$show_select_val[28]?>" disabled> 
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
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Amount(US 
                    Dollars) : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                            <input type="text" name="amount" size="9" value="<?=$show_select_val[7]?>" disabled>
                                            </font></td>
                </tr>
                <tr> 
                  <td class="tdbdr1" align="right"><font size="2" face="Verdana" color="#000000">Account 
                    Type : </font></td>
					  <td class="tdbdr">&nbsp;&nbsp;<font color="#001188">
						<input type="text" name="account" size="10" value="<?=$show_select_val[30]?>" disabled>
						</font>
						<!-- <select size="1" name="accounttype" style="font-size: 8pt; font-family: Verdana">
                      <option value="Xchoose">Choose</option>
                      <option value="checking">Checking</option>
                      <option value="savings">Savings</option>
                    </select>  
				-->
                                          </td>
                </tr>
                <tr> 
                  <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Billing 
                    Date : </font></td>
				  <td class="tdbdr">&nbsp;&nbsp; <font color="#001188"> 
					<input type="text" name="setbilldate2" size="20" value="<?=func_get_date_inmmddyy($show_select_val[38])?>" disabled>
					<!--<font color="#001188"><input type="text" name="setbilldate" size="20"></font>-->
					</font> </td>
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
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Bank 
                    Information</strong>&nbsp;
                    <!-- <a href="javascript:void(0)" onclick="showDetails('div1')">show/hide</a> -->
                    </span></td>
                </tr>
                <!--Div starting for Bank Info -->
                <tr> 
                  <td colspan="2"> 
                    <!--<div id="div1" style="display:none"> -->
                    <table width="100%" cellpadding="2" cellspacing="0" align="center">
                      <tr> 
                        <td align="right" class="tdbdr1" width="50%"><font size="2" face="Verdana" color="#000000">Bank 
                          Name : </font></td>
                                                <td class="tdbdr" width="50%">&nbsp;&nbsp;<font color="#001188"> 
                                                  <input type="text" name="bankname" size="50" value="<?=$show_select_val[29]?>" disabled>
                                                  </font></td>
                      </tr>
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">Bank 
                          Routing Code : </font></td>
					<td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
					  <input type="text" name="bankroutingcode" size="25" value="<?=$show_select_val[10]?>" disabled>
					  </font></td>
                      </tr>
                      <tr> 
                              <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font size="2" face="Verdana" color="#000000">Bank 
                                Account # : </font></font></td>
                                                <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                                                  <input type="text" name="bankaccountno" size="25" value="<?=$show_select_val[9]?>" disabled>
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
                  <td colspan="2" align="center" valign="middle" class="tdbdr"><span class="subhd"><strong>Shipping 
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
                        <td align="right" valign="middle" class="tdbdr1" width="50%"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Voice 
                          Authorization # : </font></td>
                                          <td valign="middle" class="tdbdr" width="50%"> <font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="authorizationno2" size="20" value="<?=$show_select_val[33]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Shipping 
                          Tracking # : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="shippingno2" size="20" value="<?=$show_select_val[34]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Social 
                          Security # : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="securityno2" size="20" value="<?=$show_select_val[35]?>" disabled>
                                            </font></td>
                      </tr>
<tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000">License 
                          State : </font></td>
                                          <td class="tdbdr">&nbsp;&nbsp; <font color="#001188">
                                            <input type="text" name="securityno22" size="20" value="<?=$show_select_val[39]?>" disabled>
                                            </font></td>
                      </tr>                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Drivers 
                          License : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="driverlicense2" size="25" value="<?=$show_select_val[36]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Misc 
                          : </font></td>
                                          <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;&nbsp; 
                                            <input type="text" name="misc2" size="35" value="<?=$show_select_val[17]?>" disabled>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time 
                          :</font></td>
                                          <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp;&nbsp; 
                                            <?=func_get_date_time_12hr($show_select_val[3])?>
                                            </font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP 
                          Address :</font> </td>
                                          <td valign="middle"><font size="1" face="Verdana" color="#000000"> 
                                            &nbsp;&nbsp; 
                                            <?=$show_select_val[31]?>
                                            </font> </td>
                      </tr>
                      <input type="hidden" name="domain1" value="<?=$domain?>" >
                    </table>
                    <!--   </div>-->
                  </td>
                </tr>
                <!--Div -->
              </table>	
            </td>
          </tr>
        </table>
		<table align="center"><tr><td><a href="#" onClick="func_submit();"><img   SRC="<?=$tmpl_dir?>/images/back.jpg" border="0"></a></td></tr></table>
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
			}	
		}
	
?>
	    </td>
     </tr>
</table>
</form>	</td></tr></table>	
<?php
include 'includes/footer.php';
}
?>
