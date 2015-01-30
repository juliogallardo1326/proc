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
$headerInclude="profile";	
include 'includes/header.php';


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
           <form action="updateCompanyEdit.php?type=<?=$type?>" method="post" onsubmit="return validation()" name="Frmcompany">
		      <table height="100%" width="95%" cellspacing="0" cellpadding="0" >
                <tr>
                  <td align="center"> 
                    <?=$invalidlogin?>
                    <?
			  if($showval = mysql_fetch_array($show_sql)){ 
			 if($rst_currency=mysql_fetch_array($qry_currency))
			  ?>
           <input type="hidden" name="username" value="<?=$showval[1]?>"></input>			      <br>
					 <table width="500" cellpadding='5' cellspacing='0' class='lefttopright' height="100">
					  <tr> 
			
                        <td height="25" colspan="2" align="center" valign="center" bgcolor="#78B6C2" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Wired 
                          Instructions</strong></font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%"  class='cl1'><font face="verdana" size="1">Bank 
                          Name&nbsp;</font></td>
                        <td align="left" height="30" width="50%"  class='cl1'><font style="font-family:arial;font-size:10px;width:240px" valign="middle" height="30" width="50%" align="left"> 
                          <select name="currentBank"  style="font-family:arial;font-size:10px;width:270px" >
                            <script language="javascript">
							showBankNames();	
						</script>
                          </select>
                          <script language="javascript">
							document.Frmcompany.currentBank.value='<?=$showval[55]?>';	
						</script>
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%"  class='cl1'><font face="verdana" size="1">If 
                          'Other', please specify &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font face="verdana" size="1"> 
                          <input type="text" name="bank_other" style="font-family:arial;font-size:10px;width:270px" value="<?=htmlentities($showval[56])?>">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Beneficiary 
                          Name &nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text" name="beneficiary_name" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($showval[79])?>">
						  
                          </font> </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Name 
                          On Bank Account&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text"  name="bank_account_name" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($showval[80])?>">
                          </font> </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Bank 
                          Address&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text" name="bank_address" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($showval[57])?>">
                          </font> </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Bank 
                          Country&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <select name="bank_country"  style="font-family:arial;font-size:10px;width:170px">
                            <option value="">---------- Please select -----------</option>
                            <script language="javascript">
								showCountries();	
							</script>
                          </select>
                          <script language="javascript">
								document.Frmcompany.bank_country.value='<?=$showval[58]?>';	
							</script>
                          </font> </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Bank 
                          Telephone Number&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text" name="bank_phone" style="font-family:arial;font-size:10px;width:150px" value="<?=$showval[59]?>">
                          </font> </td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1"> 
                          Iban No(EUROPEAN only)&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text" name="bank_sort_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$showval[60]?>">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Bank 
                          Account Number&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text" name="bank_account_number" style="font-family:arial;font-size:10px;width:150px" value="<?=$showval[61]?>">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Bank 
                          Swift Code&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text" name="bank_swift_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$showval[62]?>">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">BIC 
                          code&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text"  name="biccode" style="font-family:arial;font-size:10px;width:150px" value="<?=$showval[123]?>">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">VAT 
                          Number&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text" name="vatnum" style="font-family:arial;font-size:10px;width:150px" value="<?=$showval[124]?>">
                          </font></td>
                      </tr>
                      <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1">Registration 
                          Number&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <input type="text" name="regnum" style="font-family:arial;font-size:10px;width:150px" value="<?=$showval[125]?>">
                          </font></td>
                      </tr>
					  <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1"><font face="verdana" size="1">Billing Descriptor Name&nbsp;</font>&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <?=$showval[48]?>
                          </font></td>
                      </tr>
					  <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1"><font face="verdana" size="1">Processing currency(MasterCard)&nbsp;</font>&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <?=$rst_currency[0]?>
                          </font></td>
                      </tr>
					  <tr> 
                        <td align="left" valign="center" height="30" width="50%" class='cl1'><font face="verdana" size="1"><font face="verdana" size="1">Processing currency(Visa)&nbsp;</font>&nbsp;</font></td>
                        <td align="left" height="30" width="50%" class='cl1'><font style="font-family:arial;font-size:10px;width:240px"> 
                          <?=$rst_currency[1]?>
                          </font></td>
                      </tr>
                     </table>				    <?			}	  ?>
                  </td>
                </tr>
			<tr><td align="center" valign="center" height="30"> 
			<a href="profile_blank.php"><img src="images/back.jpg" border="0"></a>&nbsp;<input type="image" src="images/submit.jpg" border="0">
			</td></tr>		  
		</table>
           </form>
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