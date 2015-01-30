<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// recurringTransacxtion.php:	This page functions for uploading the company transactions. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude = "transactions";
include 'includes/header.php';


require_once( 'includes/function.php');
include 'includes/function1.php';
$str_currency="";

$sessionlogin = $companyInfo['userId'];
$str_company_id = $companyInfo['userId'];

if($_POST['mode']=='RequestIndp')
{
	$cd_recieve_order_confirmations = quote_smart($_POST['cd_recieve_order_confirmations']);
	$cd_enable_rand_pricing = quote_smart($_POST['cd_enable_rand_pricing']);
	$cd_verify_rand_price = quote_smart($_POST['cd_verify_rand_price']);
	$cd_secret_key = quote_smart($_POST['cd_secret_key']);
	

	
	$sql = "UPDATE `cs_companydetails` set 
	`cd_recieve_order_confirmations` = '$cd_recieve_order_confirmations',
	`cd_enable_rand_pricing` = '$cd_enable_rand_pricing',
	`cd_verify_rand_price` = '$cd_verify_rand_price',
	`cd_secret_key` = '$cd_secret_key'
	 WHERE `userId` = " .$str_company_id ;
	$result = mysql_query($sql,$cnn_cs) or dieLog(mysql_error());

	$data['email'] = $companyInfo['contact_email'];
	if(!$data['email']) $data['email'] = $companyInfo['email'];
	$data['companyname'] = $companyInfo['companyname'];
	$data['full_name'] = $companyInfo['companyname'];
	$data['phone'] = $companyInfo['phonenumber'];
	$data['fax'] = $companyInfo['fax_number'];
	$data['comments'] = $questions_charge;
	$data['contact_type'] = $companyInfo['transaction_type'];
	$data['edit_link'] = $_SESSION['gw_domain']."/admin/editCompanyProfileAccess.php?company_id=".$companyInfo['userId'];
	$data["gateway_select"] = $companyInfo['gateway_id'];
	//if($cd_enable_rand_pricing && !$companyInfo['cd_enable_rand_pricing']) send_email_template('merchant_enable_rand_pricing',$data);

	$companyInfo['cd_recieve_order_confirmations'] = $cd_recieve_order_confirmations;
	$companyInfo['cd_enable_rand_pricing'] = $cd_enable_rand_pricing;
	$companyInfo['cd_verify_rand_price'] = $cd_verify_rand_price;
	$companyInfo['cd_secret_key'] = $cd_secret_key;

}
?>
<script language="javascript" src="scripts/general.js"></script>
<script language="javascript" src="scripts/formvalid.js"></script>
<script language="javascript">
function validation(){

if(document.FrmRecur.txt_recurAmount.value==""){
	alert("Enter the Initial recur Amount");
	document.FrmRecur.txt_recurAmount.focus();
	return false;
	}
	if(isNaN(document.FrmRecur.txt_recurAmount.value)){
	alert("Enter numeric values only")
	document.FrmRecur.txt_recurAmount.focus()
	return false;
	}
		
	
	
	if(document.FrmRecur.rebill_amt.value==""){
	alert("Enter Recur Amount");
	document.FrmRecur.rebill_amt.focus();
	return false;
	}
	if(isNaN(document.FrmRecur.rebill_amt.value)){
	alert("Enter numeric values only")
	document.FrmRecur.rebill_amt.focus()
	return false;
	}
	
	var recur_mode = "";
for(i=0;i<document.FrmRecur.recurdatemode.length;i++)
{
	if(document.FrmRecur.recurdatemode[i].checked)
	{
		recur_mode = document.FrmRecur.recurdatemode[i].value;
		break;
	}
}
if(recur_mode == "")
	{
		alert("Please select a recurring mode.")
		document.FrmRecur.recurdatemode[0].focus();
		return false;

	}
	else if(recur_mode == "D")
	{
		if(document.FrmRecur.recur_day.value == "")
		{
			alert("Please enter the recurring days.")
			document.FrmRecur.recur_day.focus();
			return false;
		}
		else if(isNaN(document.FrmRecur.recur_day.value))
		{
			alert("Please enter numeric values.")
			document.FrmRecur.recur_day.focus();
			return false;
		}
	}
	
	/*
	if(document.FrmRecur.txt_transAmount.value!=""){
		if(isNaN(document.FrmRecur.txt_transAmount.value))
			{
				alert("Please enter numeric values for Trial Amount.")
				document.FrmRecur.txt_transAmount.focus();
				document.FrmRecur.txt_transAmount.select(); 
				return false;
			}
		}
		*/
	
	return true;
}

function toggleVerif(status)
{
	if(status) document.getElementById('secretKey').style.display = 'block';
	else document.getElementById('secretKey').style.display = 'none';
	if(status) document.getElementById('cd_secret_key').src = 'minlen|5';
	else document.getElementById('cd_secret_key').src = '';	
}
function togglePVerif(status)
{
	document.getElementById('cd_verify_rand_price').disabled = !status;
}
function toggleShowEmail(status)
{
	document.getElementById('cd_recieve_order_confirmations').style.visibility = (status?"visible":"hidden");
	document.getElementById('cd_recieve_order_confirmations').disabled = !status;
}
function randomString() {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 16;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}
function genkey()
{
	document.getElementById('cd_secret_key').value=randomString();
}
function showDemo()
{
	URL = 'checksumDemo.php';
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=1,menubar=0,resizable=0,width=420,height=350,left = 336,top = 332');");
}
</script>
 <table border="0" cellpadding="0" width="100%" cellspacing="0" height="75%" > 
  <tr> 
     <td width="83%" valign="top" align="center"  height="333"><p><font face="verdana" size="1"><a href="recurTransProcessing.php">View/Edit Sub Accounts </a></font>&nbsp; &nbsp;<font size="1" face="verdana"> </font></p> 
      <p><font size="5">Set up Pricing Options </font></p> 
      <table width="500" border="0" cellpadding="0" cellspacing="0"> 
         <tr> 
          <td><p><font size="2">This is where you set up your Price Options. </font></p> 
             <ul><li><font size="2"> If you have password management enabled, this is where the length of your subscription is determined. </font></li> 
              <li><font size="2"><strong>If you do not enable Password management for a site that provides subscriptions, those subscriptions will not be given usernames and passwords and will not be able to use our password management system.</strong> <br> 
                </font> </li> 
            </ul></td> 
        </tr> 
       </table> 
      <p>&nbsp;</p> 
            <p><a name="indp_pricing"></a>Pricing Options </p> 
      <table width="30%" border="0" cellspacing="0" cellpadding="0"> 
         <tr> 
          <td height="22" align="left" valign="top" width="1%" background="<?=$tmpl_dir?>images/menucenterbg.gif" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopleft.gif" width="8" height="22"></td> 
          <td height="22" align="center" valign="middle" width="80%" background="<?=$tmpl_dir?>images/menucenterbg.gif" ><span class="whitehd">Pricing Options </span></td> 
          <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopcurve.gif" height="22"></td> 
          <td height="22" align="left" valign="top" background="<?=$tmpl_dir?>images/menutoprightbg.gif" ><img alt="" src="<?=$tmpl_dir?>images/spacer.gif" width="1" height="1"></td> 
          <td height="22" align="right" valign="top" background="<?=$tmpl_dir?>images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menuright.gif" width="10" height="22"></td> 
        </tr> 
         <tr> 
          <td class="lgnbd" colspan="5"><!--onsubmit="return validation()"--> 
             <form action="" method="post" name="FrmRequestIndp" id="FrmRequestIndp" > 
              <input name="mode" type="hidden" value="RequestIndp"> 
              <br> 
              <table  width="100%" cellspacing="0" cellpadding="0"> 
                 <tr> 
                  <td  width="100%" valign="center" align="center"><table width="550" cellpadding="0"  > 
                      <tr> 
                        <td colspan="2" align="center"><font face="verdana" size="2">Independent Pricing </font></td> 
                      </tr> 
                      <tr> 
                        <td height="30" colspan="2" align="right" valign="center"><div align="justify"><font color="#000000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Indepenant pricing is described in your integration guide as the merchant's ability to charge any price on the fly without setting up a price point here. To enable Independent Pricing, please use this form to request for the option to be turned on. After you submit this form, we may again disable Independant pricing.. </font></div></td> 
                      </tr> 
                      <tr> 
                        <td width="250" height="30" align="right" valign="center"><font face="verdana" size="2">Recieve Customer Order Confirmations:<font size="1"><br> 
                          (All Customer Order Emails will be sent to this email)</font></font></td> 
                        <td height="30" align="left" valign="middle"><input name="cd_recieve_order_confirmations_check" type="checkbox" id="cd_recieve_order_confirmations_check" onClick="toggleShowEmail(this.checked)"value="1" <?=($companyInfo['cd_recieve_order_confirmations']?"checked":"")?>>
                        <input name="cd_recieve_order_confirmations" type="text" id="cd_recieve_order_confirmations" value="<?=$companyInfo['cd_recieve_order_confirmations']?$companyInfo['cd_recieve_order_confirmations']:$companyInfo['email']?>" size="40" src="email"></td> 
                      </tr> 
                      <tr> 
                        <td width="250" height="30" align="right" valign="center"><font face="verdana" size="2">Enable Independent Pricing:<font size="1"><br> 
                          (Charge any value without creating a price point)</font></font></td> 
                        <td height="30" align="left" valign="middle"><input name="cd_enable_rand_pricing" type="checkbox" id="cd_enable_rand_pricing" onClick="togglePVerif(this.checked)" value="1" <?=($companyInfo['cd_enable_rand_pricing']?"checked":"")?>></td> 
                      </tr> 
                      <tr> 
                        <td width="250" height="30" align="right" valign="center"><font face="verdana" size="2">Enable Price Verification:<font size="1"><br> 
                          (Not Required - <strong>HIGHLY RECOMMENDED </strong>- This verification process helps protect you from hackers by verifying your Independent prices.)</font></font></td> 
                        <td height="30" align="left" valign="middle"><input name="cd_verify_rand_price" type="checkbox" id="cd_verify_rand_price" onClick="toggleVerif(this.checked)" value="1" <?=($companyInfo['cd_verify_rand_price']?"checked":"")?>></td> 
                      </tr> 
                      <tr> 
                        <td colspan="2"><table id="secretKey"> 
                            <tr > 
                              <td width="250" height="30" align="right" valign="center"><font face="verdana" size="2">Secret Key:<font size="1"><br> 
                                (This is required for using Price Verification. Please refer to your Integration Guide)</font></font></td> 
                              <td width="230" height="30" align="left" valign="middle"><input name="cd_secret_key" type="text" id="cd_secret_key" value="<?=$companyInfo['cd_secret_key']?>" maxlength="32"></td> 
                            </tr> 
                            <tr > 
                              <td width="250" height="30" align="right" valign="center"><font face="verdana" size="2">Generate Key:<font size="1"><br> 
                              (Quick Generation of a secure key)</font></font></td> 
                              <td width="230" height="30" align="left" valign="middle"><a href="javascript:genkey()">Generate</a></td>
                            </tr> 
                            <tr > 
                              <td width="250" height="30" align="right" valign="center"><font face="verdana" size="2">Checksum Demonstration:<font size="1"><br> 
                              (Show me how to build a checksum)</font></font></td> 
                              <td width="230" height="30" align="left" valign="middle"><a href="javascript:showDemo()">Demonstrate</a></td>
                            </tr> 
                          </table></td> 
                      </tr> 
                      <!--modification to include recurring details --> 
                      <!-- --> 
                      <tr> 
                        <td align="center" valign="center" height="30" colspan="2"><input type="image" src="<?=$tmpl_dir?>images/submit.jpg"> </td> 
                      </tr> 
                    </table></td> 
                </tr> 
               </table> 
            </form></td> 
        </tr> 
         <tr> 
          <td width="1%"><img src="<?=$tmpl_dir?>images/menubtmleft.gif"></td> 
          <td colspan="3" width="98%" background="<?=$tmpl_dir?>images/menubtmcenter.gif"><img border="0" src="<?=$tmpl_dir?>images/menubtmcenter.gif" height="11"></td> 
          <td width="1%" ><img src="<?=$tmpl_dir?>images/menubtmright.gif"></td> 
        </tr> 
       </table> 
      <p><font size="6"></font></p> 
      <p>&nbsp;</p></td> 
   </tr> 
</table> 
<script language="javascript">
	toggleVerif(<?=($companyInfo['cd_verify_rand_price']?"true":"false")?>);
	togglePVerif(<?=($companyInfo['cd_enable_rand_pricing']?"true":"false")?>);
	toggleShowEmail(<?=($companyInfo['cd_recieve_order_confirmations']?"true":"false")?>);
	setupForm(document.getElementById('FrmRequestIndp'));
</script> 
<?php 
	
include("includes/footer.php");
?> 
