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
// modifyReseller.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");


$loginas = (isset($HTTP_GET_VARS["loginas"])?trim($HTTP_GET_VARS["loginas"]):"");
if($loginas){

	$etel_debug_mode=0;
	require_once("../includes/dbconnection.php");

	$i_reseller_id = isset($HTTP_GET_VARS["reseller_id"])?$HTTP_GET_VARS["reseller_id"]:"";
	$_SESSION["loginredirect"]="None";
	$_SESSION["gw_admin_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_password"]."|Admin|".$_SESSION['gw_id']."|modifyReseller.php?reseller_id=".$loginas."&returnid=".$loginas);
	$result = general_login($_GET['username'],$_GET['password'],"reseller",$_GET['gw_id'],false);
		
	$sql = "Insert ignore into cs_entities
		set 
			en_username = '".($_GET['username'])."',
			en_password = MD5('".($_GET['username'].$_GET['password'])."'),
			en_gateway_ID = '".quote_smart($_SESSION['gw_ID'])."',
			en_type = 'reseller',
			en_type_id = '".quote_smart($i_reseller_id)."'
		";
	sql_query_write($sql) or dieLog(mysql_error()." ~ $str_qry");
	$result = general_login($_GET['username'],$_GET['password'],"reseller",$_GET['gw_id'],false);
	
	die('Failed '.$result );

	
}

$headerInclude="reseller";
include("includes/header.php");

include("includes/message.php");
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";

	$returnid = isset($HTTP_GET_VARS["returnid"])?$HTTP_GET_VARS["returnid"]:"";
	$i_reseller_id = isset($HTTP_GET_VARS["reseller_id"])?$HTTP_GET_VARS["reseller_id"]:"";
	$qry_selectdetails = "select * from cs_resellerdetails where reseller_id = '$i_reseller_id'";	
	if (!($rst_selectdetails = mysql_query($qry_selectdetails)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>$qry_selectdetails");

	}
if($resellerInfo = mysql_fetch_array($rst_selectdetails)){ 

?>
<script language="JavaScript" >
function validation() {
	trimSpace(document.modifyResellerForm.companyname)
	if (document.modifyResellerForm.companyname.value =="") {
		alert("Please enter the Reseller company name.");
		document.modifyResellerForm.companyname.focus();
		return false;
	}
	trimSpace(document.modifyResellerForm.contactname)
	if (document.modifyResellerForm.contactname.value =="") {
		alert("Please enter Reseller contact name.");
		document.modifyResellerForm.contactname.focus();
		return false;
	}
	trimSpace(document.modifyResellerForm.password)
	if (document.modifyResellerForm.password.value =="") {
		alert("Please enter the Password.");
		document.modifyResellerForm.password.focus();
		return false;
	}
	trimSpace(document.modifyResellerForm.repassword)
	if (document.modifyResellerForm.repassword.value =="") {
		alert("Please re-enter the Password.");
		document.modifyResellerForm.repassword.focus();
		return false;
	}
	trimSpace(document.modifyResellerForm.password)
	if(document.modifyResellerForm.password.value !="") {
		if(document.modifyResellerForm.password.value != document.modifyResellerForm.repassword.value ) {
			alert("Please retype the correct Password.");
			document.modifyResellerForm.repassword.focus();
			return false;
		} 
	}
	trimSpace(document.modifyResellerForm.email)
	if (document.modifyResellerForm.email.value =="") {
		alert("Please enter the email address.");
		document.modifyResellerForm.email.focus();
		return false;
	}
	
	 if (document.modifyResellerForm.email.value  != "") 
	{
		if (document.modifyResellerForm.email.value .indexOf('@')==-1) 
		{
			alert("Please enter valid email id");
			document.modifyResellerForm.email.focus();
			return(false);
		}
	}
	
	if (document.modifyResellerForm.email.value  != "") 
	{
		if (document.modifyResellerForm.email.value .indexOf('.')==-1) 
		{
			alert("Please enter valid email id");
			document.modifyResellerForm.email.focus();
			return(false);
		}
	}
	
	if (document.modifyResellerForm.email.value.length > 100)
	{
		alert("Please enter email max upto 100 characters")
		document.modifyResellerForm.email.focus();
		return(false);
	}
	
	trimSpace(document.modifyResellerForm.confirmemail)
	if (document.modifyResellerForm.confirmemail.value =="") {
		alert("Please enter the confirm email address.");
		document.modifyResellerForm.confirmemail.focus();
		return false;
	}
	
	if(document.modifyResellerForm.email.value != document.modifyResellerForm.confirmemail.value ) {
		alert("Please enter the correct email address.");
		document.modifyResellerForm.confirmemail.focus();
		return false;
	} 
	
}
function validate()
{
	
if (document.modifyResellerForm.password.value!=""&&(!func_vali_pass(document.modifyResellerForm.password)))
		{
		
		alert("Special characters not allowed for password");
		document.modifyResellerForm.password.focus();
		document.modifyResellerForm.password.select();
		return false;
		}
}

function trimSpace(frmElement)
{
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);

     frmElement.value = stringToTrim.substring(front, back);
}
function func_vali_pass(frmelement)
{ ///return true if no special chars
 var invalid="!`~@#$%^&*()_-+={}[]|\"':;?/>.<,";
 var inp=frmelement.value;
 var b_flag=true;
for(var i=0;((i<inp.length)&&b_flag);i++)
{
var temp= inp.charAt(i);
var j=invalid.indexOf(temp);
if(j!=-1)
{
b_flag =false;
return false;
}
}
if (b_flag==true)return true;
}
</script>
<script language="javascript" src="../scripts/general.js"></script>

<table width="100%" border="0" cellspacing="0" cellpadding="0"  >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="80%">
      <tr>
        <td width="100%" height="22">&nbsp;
         
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Modify 
                  Reseller</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
	<form name="modifyResellerForm" action="modifyResellerFb.php"  method="post"  onsubmit="javascript:return validate();">
	 <input type="hidden" name="hid_reseller_id" value="<?=$resellerInfo[0]?>">
	 <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100"><br>  
		 <tr>
		  <td height="70"  valign="top" align="left"  width="50%">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr> 
			  <td align="center" valign="middle" height="30" class="rightbottomtop" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Company 
				Informations</strong></font></td>
			  <td align="left" valign="center" height="30" class="rightbottomtop"><span class="cl1"><a href="<?="modifyReseller.php?username=".$resellerInfo['reseller_username']."&password=".$resellerInfo['reseller_password']."&gw_id=".$_SESSION['gw_id']?>&loginas=1">Login as
                    <?= $resellerInfo['reseller_companyname'] ?>
              </a></span></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Company 
				Name&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input name="companyname" type="text" style="font-family:arial;font-size:10px;width:150px" maxlength="250" value="<?=$resellerInfo[6]?>"></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Contact 
				Name&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input name="contactname" type="text" style="font-family:arial;font-size:10px;width:150px" maxlength="250" value="<?=$resellerInfo[7]?>"></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;User 
				Name&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<?=$resellerInfo[3]?>
				</font></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Password&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input name="password" type="text" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[4]?>" maxlength="50"></td>
			</tr>
<!--
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Retype 
				Password&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp; 
				<input name="repassword" type="text" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[4]?>" ></td>
			</tr>
            <tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Confirm 
					email address&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp; 
					<input name="confirmemail" type="text" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[8]?>"></td>
				</tr>
-->							
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Phone 
				Number&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input name="phone" type="text" style="font-family:arial;font-size:10px;width:100px" value="<?=$resellerInfo[9]?>"></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;New 
				merchant applications &nbsp;monthly&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<select name="merchantmonthly" style="font-family:arial;font-size:10px;width:70px" >
				  <option value="">Select</option>
				  <option value="1-5">1-5</option>
				  <option value="5-10">5-10</option>
				  <option value="10-25">10-25</option>
				  <option value="25-50">25-50</option>
				  <option value="50-100">50-100</option>
				  <option value="100+">100+</option>
				</select> <script>
			  document.modifyResellerForm.merchantmonthly.value= "<?=$resellerInfo[11]?>";
			  </script> </td>
			</tr>
			<tr> 
			  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Website 
				Informations</strong></font></td>
			  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
			</tr>
				<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;URL 1&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input name="url1" type="text" style="font-family:arial;font-size:10px;width:215px" value="<?=$resellerInfo[10]?>"></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;URL 2&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input name="url2" type="text" style="font-family:arial;font-size:10px;width:215px" value="<?=$resellerInfo[42]?>"></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;URL 3&nbsp;</font></td>
			  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input name="url3" type="text" style="font-family:arial;font-size:10px;width:215px" value="<?=$resellerInfo[43]?>"></td>
			</tr>
			<tr> 
			  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Bank 
				Informations</strong></font></td>
			  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">&nbsp;With 
				which bank do you hold a &nbsp;company account?</font></td>
			  <td align="left" valign="center" height="30" class="cl1">&nbsp;<select name="currentBank" style="font-family:arial;font-size:10px;width:200px" >
								<?=func_get_bank_select($resellerInfo[26])?>
                          </select> </td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">	
				&nbsp;If 'Other', please specify:&nbsp;&nbsp;</font></td>
			  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text"  name="bank_other" style="font-family:arial;font-size:10px;width:200px" value="<?=$resellerInfo[27]?>"></td>
			</tr>
			<tr> 
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">	
				&nbsp;Beneficiary Name:&nbsp;&nbsp;</font></td>
			  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text"  name="beneficiary_name" style="font-family:arial;font-size:10px;width:200px" value="<?=$resellerInfo[28]?>"></td>
			</tr>
			<tr>
			  <td align="left" valign="center" height="30" class="cl1"><font face="verdana" size="1">	
				&nbsp;Name On Bank Account:&nbsp;&nbsp;</font></td>
			  <td align="left" valign="center" height="30" class="cl1">&nbsp;<input type="text"  name="bank_account_name" style="font-family:arial;font-size:10px;width:200px" value="<?=$resellerInfo[29]?>"></td>
			</tr>
			<tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp;Bank Address:&nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text"  name="bank_address" style="font-family:arial;font-size:10px;width:200px" value="<?=$resellerInfo[30]?>"></td>
		  </tr>
		  <tr>
	  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
		&nbsp;Bank Country:&nbsp;&nbsp;</font></td>
	  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<select name="bank_country"  style="font-family:arial;font-size:10px;width:150px">
		  <option value="">---------- Please select -----------</option>
							<?=func_get_country_select($resellerInfo[31]) ?>
		</select>
				</td>
		  </tr>
		  <tr>
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp;Bank Telephone Number:&nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" name="bank_phone" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[32]?>"></td>
		  </tr>
		  <tr> 
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp;Sort Code/Branch Number:&nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" name="bank_sort_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[33]?>"></td>
		</tr>
		<tr> 
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp;Bank Account Number: &nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" name="bank_account_number" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[34]?>"></td>
		</tr>
		<tr> 
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp;Bank Account Number: &nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" name="bank_routing_no" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo['bank_routing_no']?>"></td>
		</tr>
		<tr> 
		  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">	
			&nbsp;Bank Swift Code:&nbsp;&nbsp;</font></td>
		  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" name="bank_swift_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo['bank_swiftcode']?>"></td>
		</tr>
           </table>
		  </td>
		  <td height="70"  valign="top" align="left"  width="50%">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr> 
				  <td align="center" valign="middle" height="30" class="rightbottomtop" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Subscription 
					Informations</strong></font></td>
				  <td align="left" valign="center" height="30" class="rightbottomtop">&nbsp;</td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1" ><font face="verdana" size="1">&nbsp;Unsubscribe 
					Mails&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="checkbox" name="mail_send" value="0" <?=$resellerInfo[25]==1?"":"checked"?>></td>
				</tr> 
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1" ><font face="verdana" size="1">&nbsp;Suspend 
					Reseller&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="checkbox" name="suspend_user" value="1" <?=$resellerInfo['suspend_reseller']==1?"checked":""?>></td>
				</tr>    
				<tr> 
				  <td align="center" valign="middle" height="30" class="cl1" bgcolor="#CCCCCC"><font face="verdana" size="1" color="#FFFFFF"><strong>Customer 
					Informations</strong></font></td>
				  <td align="left" valign="center" height="30" class="cl1">&nbsp;</td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Title&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<select name="cboTitle" style="font-family:arial;font-size:10px;width:100px">
<?php 			$sTitle = $resellerInfo[12]; 
				funcFillComboWithTitle ( $sTitle ); 
?>
					</select> </td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;First 
					Name&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" maxlength="100" name="first_name" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[13]?>"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Last 
					Name&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;<input type="text" maxlength="100" name="family_name" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[14]?>">
					</font></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Sex&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<select name="cboSex" style="font-family:arial;font-size:10px">
					<?php
						if ( $resellerInfo[15] == "Male" ) {
							echo("<option value='Male' selected>Male</option>");
						}else {
							echo("<option value='Male'>Male</option>");
						}
						if ( $resellerInfo[15] == "Female" ) {
							echo("<option value='Female' selected>Female</option>");
						}else {
							echo("<option value='Female'>Female</option>");
						}
					?>
					</select></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="90" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Address&nbsp;</font></td>
				  <td align="left" valign="center" height="90" width="50%" class="cl1">&nbsp;<textarea rows="5" cols="35" name="txtAddress" style="font-family:arial;font-size:10px"><?= $resellerInfo[2] ?></textarea></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Zipcode 
					&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" size="10" maxlength="10" value="<?= $resellerInfo[16] ?>" name="txtPostCode" style="font-family:arial;font-size:10px"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Job 
					Title &nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" maxlength="100" name="job_title" style="font-family:arial;font-size:10px;width:150px" value="<?=$resellerInfo[17]?>"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Contact 
					Email &nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" maxlength="100" name="contact_email" style="font-family:arial;font-size:10px;width:155px" value="<?=$resellerInfo[8]?>"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Residence 
					Phone &nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" maxlength="15" name="residence_telephone" style="font-family:arial;font-size:10px;width:100px" value="<?= $resellerInfo[18] ?>"></td>
				</tr>
				<tr> 
				  <td align="left" valign="center" height="30" width="50%" class="cl1"><font face="verdana" size="1">&nbsp;Fax 
					Number&nbsp;</font></td>
				  <td align="left" valign="center" height="30" width="50%" class="cl1">&nbsp;<input type="text" maxlength="15" name="fax" style="font-family:arial;font-size:10px;width:100px" value="<?= $resellerInfo[19] ?>"> 
				  </td>
				</tr>
			  </table>
		  <table cellpadding='0' cellspacing='0' class='lefttopright' style='margin-top: 15; margin-bottom: 5' valign="top">
            <?php
				if ($showval[27] == "tele") {
			?>
            <?php
				}
				if ($showval[27] != "tele") {
			?>
            <? } ?>
            <tr>
              <td width="233" height="30" align="right" valign="center" bgcolor="#CCCCCC" class='cl1'><font face="verdana" size="1" color="#FFFFFF"><strong>Pay Period Information </strong>&nbsp;</font></td>
              <td width="50%" height="30" align="left" class='cl1'>&nbsp;</td>
            </tr>
            <tr>
              <td height="31" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;</strong>Pay D<font face="verdana" size="1">ay</font></font></td>
              <td height="31" align="left"  class='cl1' >&nbsp;<font face="verdana" size="1">on the </font>
                  <input name="rd_paydelay" type="text" id="rd_paydelay" style="font-family:arial;font-size:10px" onChange="addRatesFees('disc');" value="<?= $resellerInfo['rd_paydelay'] ?>" size="5" maxlength="15"><font face="verdana" size="1">th</font>            </tr>
            <tr>
              <td height="31" align="left"  class='cl1' ><font face="verdana" size="1"> <strong>&nbsp;</strong>Roll Over </font></td>
              <td height="31" align="left"  class='cl1' >&nbsp;
                  <input name="rd_rollover" type="text" id="rd_rollover" style="font-family:arial;font-size:10px" onChange="addRatesFees('disc');" value="<?= $resellerInfo['rd_rollover'] ?>" maxlength="15">
              </td>
            </tr>
            <tr>
              <td height="31" align="left"  class='cl1' ><font face="verdana" size="1"> &nbsp;Wire Fee </font></td>
              <td height="31" align="left"  class='cl1' >&nbsp;
                  <input name="rd_wirefee" type="text" id="rd_wirefee" style="font-family:arial;font-size:10px" onChange="addRatesFees('disc');" value="<?= $resellerInfo['rd_wirefee'] ?>" maxlength="15">
              </td>
            </tr>
            <tr>
              <td colspan="2"><div id="auto_sendecommerce" style="display:<?=$sendecommerce_diplay?>"> </div></td>
            </tr>

          </table>		  
		  <p>&nbsp;</p></td>
		  </tr>
		  <tr><td height="40" valign="bottom" align="center" colspan="2"><a href="viewReseller.php?return_reseller_id=<?=$returnid?>" ><img border="0" SRC="<?=$tmpl_dir?>/images/back.gif"></a>&nbsp;&nbsp;&nbsp;<input type="image" id="modifyuser" SRC="<?=$tmpl_dir?>/images/submit.jpg"></input>
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
	 </table><br>
	 </td>
	</tr>
</table>

<?php
	}
include("includes/footer.php");
?>