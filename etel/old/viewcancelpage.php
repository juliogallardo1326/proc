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
//viewcancelpage.php:	The cancel page functions for viewing the company transaction details. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$headerInclude="transactions";
include("includes/topheader.php");
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
if($sessionlogin!=""){  
	$cancel = (isset($HTTP_GET_VARS['cancel'])?Trim($HTTP_GET_VARS['cancel']):"");
	$cancelreason = (isset($HTTP_GET_VARS['cancelreason'])?Trim($HTTP_GET_VARS['cancelreason']):"");
	$id = (isset($HTTP_GET_VARS['id'])?Trim($HTTP_GET_VARS['id']):"");
	$cnumber = (isset($HTTP_GET_VARS['cnumber'])?Trim($HTTP_GET_VARS['cnumber']):"");
	$tele_number =(isset($HTTP_GET_VARS['telnumber'])?Trim($HTTP_GET_VARS['telnumber']):"");
	$canceldate = func_get_current_date_time(); 
    if($id==""){
    	$id = (isset($HTTP_POST_VARS['id'])?Trim($HTTP_POST_VARS['id']):"");
		if($cnumber =="")
		{
    		$cnumber = (isset($HTTP_POST_VARS['cnumber'])?Trim($HTTP_POST_VARS['cnumber']):"");
		}	
    }
	$other = (isset($HTTP_GET_VARS['other'])?Trim($HTTP_GET_VARS['other']):"");
		if($cancel) {
			$iTransactionId = $id;
			$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
			$query ="Update transactiondetails set cancelstatus='Y' where transactionId=$return_insertId and userid=$sessionlogin";
	//		echo $query;
			mysql_query($query);
			 if(mysql_affected_rows()==0) {
                $outhtml="y";
				$msgtodisplay="This transaction has been already canceled";
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();
			 } else {
             $show_sql =mysql_query("update transactiondetails set reason='$cancelreason',cancellationDate='$canceldate' where transactionId=$return_insertId and userid=$sessionlogin");
			 $show_sql =mysql_query("update transactiondetails set other='$other' where transactionId=$return_insertId and userid=$sessionlogin");
			 
			func_canceledTransaction_receipt($sessionlogin, $id,$cnn_cs);
			 
			    $outhtml="y";
				$msgtodisplay="Selected transactions has been canceled";
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();
				
			 }
		}else if($cnumber!="" && $id!="" || $tele_number !="") {
				
				if($cnumber) {
					$cnumber_qry ="a.CCnumber ='".etelEnc($cnumber)."' and";
				}
				
				if($id) {
					$tid_qry ="a.transactionId=$id and ";
				}
			  $querystr="select b.companyname,a.name,a.surname,a.transactionDate,a.checkorcard,a.CCnumber,a.checkto,a.amount,a.status,a.bankaccountnumber,a.bankroutingcode,a.phonenumber,a.address,a.country,a.city,a.state,a.zipcode,a.memodet,a.signature,a.email,a.transactionId,a.cardtype,a.validupto,a.reason,a.other,a.cvv,a.misc,a.Invoiceid,a.checktype,a.bankname,a.accounttype,a.ipaddress,a.cancelstatus,a.voiceAuthorizationno,a.shippingTrackingno,a.socialSecurity,a.driversLicense,a.chequedate,a.billingDate,a.licensestate from transactiondetails as a,customerdetails as b where ". $tid_qry ."a.userid=$sessionlogin and ".$cnumber_qry." ".$telephone_qry." a.userid=b.userid";
				
			//	 echo $querystr;
				//		exit();  
				$show_sql =mysql_query($querystr); 
				if(mysql_num_rows($show_sql)==0){
					$outhtml="y";
					$msgtodisplay="Enter the correct transaction Id, Check/Credit Card Number or Telephone Number";
					message($msgtodisplay,$outhtml,$headerInclude);									
					exit();
				}


?>

<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
</style>

<table border="0" cellpadding="0" width="800" cellspacing="0" align="center">
  <tr>
    <td width="100%" valign="top" align="center">
<form action="viewcancelpage.php" name="view" method="get">
<input type="hidden" name="id" value="<?=$id?>"></input>
<input type="hidden" name="statusdiv1" value="">
<input type="hidden" name="statusdiv2" value="">
<input type="hidden" name="statusdiv3" value="">

<?
while($showval = mysql_fetch_array($show_sql)) {
	
	  if($showval[4]=="H"){
	  //echo($showval[0]);
?>
<br>
    	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Credit&nbsp; 
              Card&nbsp;Transaction</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
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
                  <td height="167" valign="top" align="left" width="652" > 
                    <table width="100%" cellpadding="2" cellspacing="0" style="border:1px solid black">
						
                      <tr align="center" valign="middle" bgcolor="#78B6C2"> 
                        <td colspan="2" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Customer 
                          Information</strong></font></td>
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
                      <tr bgcolor="#78B6C2"> 
                        <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Payment 
                          Information</strong></font></td>
                      </tr>
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card 
                          Number :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="number" size="17" maxlength="16" value="<?=$show_select_val[5]?>" disabled>
						</font><font size="1" face="Verdana"><a href="#" onClick='javascript:window.open("images/creditcard.gif","","width=500,height=350")' class="link">CVV2</a></font><font color="#001188"> 
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
                        <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing 
                          Date(yyyy-mm-dd) :</font></td>
                        <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; 
                          <input type="text" name="setbilldate" size="20" value="<?=$show_select_val[38]?>" disabled>
                          </font></td>
                      </tr>
                      <tr bgcolor="#78B6C2"> 
                        <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Shipping 
                          Information</strong></font></td>
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
						<img border="0" src="images/mastercard.jpg"> 
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
	<table align="center" height="50" ><tr><td><a href="#" onclick="window.history.back()"><img   src="images/back.jpg" border="0"></a>&nbsp;<input type="image" id="reportview" src="images/canceltransaction.jpg"></input></td></tr></table>
	<input type="hidden" name="cancel" value="cancel"></input>
	</td>
      </tr>
    </table>
    </td>
     </tr>
	 	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>

</table>
<?	
	} 
	else 
	{
	if(!isset($dateToEnter)) {
		$dateToEnter="";
	}

?>
	<table border="0" cellpadding="0" width="100%" cellspacing="0"  align="center">
  <tr>
       <td width="90%" valign="top" align="center" >
    &nbsp;
    	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		    <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Check 
            &nbsp;Transaction</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
		<table width="100%" cellspacing="0" cellpadding="2" style="border:1px solid black">
               <tr bgcolor="#78B6C2"> 
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
                                <input type="text" name="setbilldate2" size="20" value="<?=$show_select_val[38]?>" disabled>
                                </font>
                                <!--<font color="#001188"><input type="text" name="setbilldate" size="20"></font>-->
                                <font color="#001188">&nbsp; </font> </td>
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
                                      <input type="text" name="bankname" size="25" value="<?=$show_select_val[29]?>" disabled>
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
                <tr bgcolor="#78B6C2"> 
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
	 	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
</table>
<?php
			}	
		}
?>
		    </td>
     </tr>
</table>
</form>	
<?php
	} 
	else if($cnumber!="" && $id =="") 
	{
		$querystr="select a.status,a.transactionId,b.companyname,a.name,a.surname,a.checkorcard,a.amount,a.transactionDate from cs_transactiondetails as a,cs_companydetails as b where a.CCnumber = '".etelEnc($cnumber)."' and a.userid=b.userid";
        $querystr=$querystr." and a.userid=" . $sessionlogin." order by a.transactionId desc";

	//	echo $querystr;
		$show_sql =mysql_query($querystr); 
		if(mysql_num_rows($show_sql)==0)
		{
			$outhtml="y";
			$msgtodisplay="The Check or Credit Card Number is not valid";
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();			
		} 

?>
<table border="0" cellpadding="0" width="95%" cellspacing="0" height="490" align="center">
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="70%" >
      <tr>
        <td width="100%" height="22">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="22">
            <tr>
              <td width="17" height="22"><img border="0" src="images/leftcurve.gif" width="17" height="22"></td>
              <td bgcolor="#1c5abc" >
                <p style="margin-left: 25"><font size="1" face="Verdana" color="#FFFFFF"><B>Transactions</B></font></p>
              </td>
              <td width="17" height="22"><img border="0" src="images/rightcurve.gif" width="17" height="22"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td width="100%"  valign="top" align="center" style="border:1px solid #1c5abc">
	<table class='lefttopright' cellpadding='0' cellspacing='0' width='90%'  valign=left  bgColor='#ffffff'  ID='Table1' style=' margin-left: 10; margin-top: 15; margin-bottom: 5'>
	 <form name="dates" action="cancelTop.php"  method="GET">

		<?
				$totamount=0;
				$i = 0;
				while($showval = mysql_fetch_array($show_sql)) {
			  $amount=$showval[6]; 
			  
              $totamount=$totamount+$showval[6];
              
			if($i==0){
			 ?>
			<tr height='30' bgcolor='#aebbd2'>
			<td align='left' width='100' class='cl1'><font face='verdana' size='1'><b>Transaction Id</b></font></td>
			<td align='left'  width='125' class='cl1' ><font face='verdana' size='1'><b>First name</b></font></td>
			<td align='left'  width='125' class='cl1' ><font face='verdana' size='1'><b>Last name</b></font></td>
			<td align='left'  width='100' class='cl1' ><font face='verdana' size='1'><b>Type</b></font></td>
			<td  align='left' width="100" class='cl1'><font face='verdana' size='1'><b>Amount</b></font></td>
			<td  align='left' width='200' class='bottom'><font face='verdana' size='1'><b>Transaction Date</b></font></td>
			</tr>


			  <?  } 
					   $i=$i+1;
						  if($showval[5]=="C"){
							 $ctype="Check";
						  }
						  else{
							$ctype="Creditcard";
						  }
						  //echo($showval[0]);
						 						  
						  
						  ?>
				   


	<tr height='30' bgcolor='#ffffff'>
		<td align='left' class='cl1'><font face='verdana' size='1'><a href="viewreportpage.php?id=<?=$showval[1]?>"  class="link">&nbsp;<?=$showval[1]?></a> </font></td>
		<td align='left'   class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$showval[3]?></font></td>
		<td align='left'   class='cl1' ><font face='verdana' size='1'>&nbsp;<?=$showval[4]?></font></td>
		<td  align='left'  class='cl1'><font face='verdana' size='1'>&nbsp;<?=$ctype?></font></td>
		<td  align='right' class='cl1'><font face='verdana' size='1'><?=number_format($amount,2)?></font>&nbsp;</td>
		<td  align='left'  class='bottom'><font face='verdana' size='1'>&nbsp;<?=$showval[7]?></font></td>
	</tr>
				  
			<? 
				}
			if(!isset($nextstr))
			{
				$nextstr = "";
			}

			?>
</form>				
</table>
</td></tr>	
<tr><td>
<table width="98%" cellspacing="0" cellpadding="0">
<tr>
<td height="50" align="center" valign="middle"  colspan="2">
<input type="image" id="reportview" src="images/back.jpg" border="0"></input>&nbsp;&nbsp;<?=$nextstr?></td>
</tr>
</table>
</td></tr>
</table>
</td></tr></table>
<?
}
?>

</td></tr></table>
	
<?php
include 'includes/footer.php';
}
?>
