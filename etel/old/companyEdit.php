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
// companyEdit.php:	The  page used to modify the company profile. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

die();
$type = (isset($HTTP_GET_VARS['type'])?Trim($HTTP_GET_VARS['type']):"edit");
$headerInclude= $type == "testMode" ? "testMode" : "profile";	
include 'includes/topheader.php';
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

	if($sessionlogin!=""){
	
$show_sql =mysql_query("select *  from cs_companydetails where userid=$sessionlogin",$cnn_cs);
$qry_currency=mysql_query("select processingcurrency_master,processingcurrency_visa, customerservice_email from cs_companydetails_ext where  userid=$sessionlogin",$cnn_cs);
	
?>
<script language="javascript" src="scripts/general.js"></script>
<script language="javascript">

function emailsubmit() {
document.Frmcompany.action="userBottom.php?sub=email";
document.Frmcompany.method="POST";
document.Frmcompany.submit();
}

function validation(){
  if(document.Frmcompany.companyname.value==""){
    alert("Please enter company name")
    document.Frmcompany.companyname.focus();
	return false;
  }
  if(document.Frmcompany.phonenumber.value==""){
    alert("Please enter phone number")
    document.Frmcompany.phonenumber.focus();
	return false;
  }
   if(document.Frmcompany.email.value==""){
    alert("Please enter email")
    document.Frmcompany.email.focus();
	return false;
  }
  if(document.Frmcompany.address.value==""){
    alert("Please enter address")
    document.Frmcompany.address.focus();
	return false;
  }
 
}
function validator(){
	if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
		document.Frmcompany.ostate.disabled= true;
		document.Frmcompany.ostate.value= "";
		document.Frmcompany.state.disabled = false;
	} else {
		document.Frmcompany.state.disabled = true;
		document.Frmcompany.state.value= "";
		document.Frmcompany.ostate.disabled= false;
	}
	return false;
}

</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="96%" height="303">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">View&nbsp; 
            Profile</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>

      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
        	      <table height="100%" width="95%" cellspacing="0" cellpadding="0" >
                <tr>
                  <td width="45%" align="left"> 
                    <?=$invalidlogin?>
                    <?
			  $showval = mysql_fetch_array($show_sql);
			 $rst_currency=mysql_fetch_array($qry_currency);
			  ?>
			  <table width="400" cellpadding='5' cellspacing='0' class='lefttopright' height="100">
                      <tr>
						<td height="25" colspan="2" align="center" valign="center" bgcolor="#78B6C2" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Company Information</strong>&nbsp;</font></td>                      
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%"  class='cl1'><font face="verdana" size="1">Company 
                          Name &nbsp;</font></td>
                        <td align="left" height="30" width="50%"  class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[3]?></font></td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"  class='cl1'><font face="verdana" size="1">User 
                          Name &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font face="verdana" size="1"><b>
                          <?=$showval[1]?>
                          </b></font></td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Address 
                          &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[5]?></font>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">City 
                          &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[6]?></font>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Country&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[8]?></font>
					     	
						</td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">State&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[7]==""?$showval[12]:$showval[7]?></font>
						</td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Zipcode&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[9]?></font>
                          </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Phone 
                          Number &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[4]?></font></td>
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Fax 
                          Number &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[51]?></font></td>
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Type of company&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[52]?></font></td>
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Customer service phone number &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[54]?></font></td>
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Customer service email &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$rst_currency[2]?>&nbsp;</font></td>
                      </tr>
                    </table></td>
                  <td width="55%" valign="top"><br>
					 <table width="500" cellpadding='5' cellspacing='0' class='lefttopright' height="100">
                      <tr>
						<td height="25" colspan="2" align="center" valign="center" bgcolor="#78B6C2" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Website 
                          Information</strong>&nbsp;</font></td>                      
					  </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Email 
                          &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[10]?></font></td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">URL 
                          &nbsp;&nbsp;</font></td>
                        <td align="left" height="50" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[43]?>
                          <br><?=$showval[44]?><br><?=$showval[45]?><br><?=$showval[126]?><br><?=$showval[127]?></font></td>
                      </tr>
					                        <tr>
						<td height="25" colspan="2" align="center" valign="center" bgcolor="#78B6C2" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Rates 
                          & Fees Information</strong>&nbsp;</font></td>                      
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">	
                          Projected monthly sales volume $&nbsp;&nbsp;</font></td>
                        <td align="left" valign="middle" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[30]?></font></td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">	
                          Average ticket&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px">$ <?=$showval[38]?></font></td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">	
                          Charge back %&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[39]?> %</font></td>
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">	
                          Charge back $&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px">$ <?=$showval[18]?></font></td>
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">	
                          Credit $&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px">$ <?=$showval[19]?></font></td>
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">	
                          Transaction Fee $&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px">$ <?=$showval[21]?></font></td>
                      </tr>
					  <tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">	
                          Discount Rate %&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[20]?> %</font></td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">	
                          Reserve %&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"><?=$showval[22]?> %</font></td>
                      </tr>
					    <tr>
                        <td height="31" align="left"  class='cl1' ><font face="verdana" size="1"> Wire Fee </font></td>
                        <td height="31" align="left"  class='cl1' ><font style="font-family:arial;font-size:10px;width:240px">
                          <?=($showval['cd_wirefee']==0?"-":"$ ".$showval['cd_wirefee'])?></font>
                        </td>
                      </tr>
                      <tr>
                        <td height="31" align="left"  class='cl1' ><font face="verdana" size="1"> Application Fee </font></td>
                        <td height="31" align="left"  class='cl1' ><font style="font-family:arial;font-size:10px;width:240px">
                        <?=($showval['cd_appfee']==0?"-":"$ ".$showval['cd_appfee'])?></font>
						</td>
                      </tr>
                      <tr>
                        <td height="31" align="left"  class='cl1' ><font face="verdana" size="1"> Monthly Fee </font></td>
                        <td height="31" align="left"  class='cl1' ><font style="font-family:arial;font-size:10px;width:240px">
                           <?=($showval['cs_monthly_charge']==0?"-":"$ ".$showval['cs_monthly_charge'])?></font>
                        </td>
                      </tr>
                    </table>
                    </td>
                </tr>
			<tr><td align="center" valign="center" height="30" colspan="2"> 
			<a href="profile_blank.php"><img src="images/back.jpg" border="0"></a>
			</td></tr>		  
		</table>
	</td>
      </tr>
		<tr>
		<td width="1%"><img src="images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="images/menubtmright.gif"></td>
		</tr>
    </table>
    </td>
     </tr>
</table><br>
<?
include 'includes/footer.php';
}
?>