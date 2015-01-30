<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyleft (C) Etelegate.com 2003-2004, All lefts Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// resellerCompany.php:	The  page used to modify the company profile. 
include ("includes/sessioncheck.php");

$headerInclude="startHere";
include("includes/header.php");
require_once("../includes/function.php");
include("includes/message.php"); 
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
if($resellerLogin!=""){
	
	$cboTitle = (isset($HTTP_POST_VARS['cboTitle'])?Trim($HTTP_POST_VARS['cboTitle']):"");
	$first_name = (isset($HTTP_POST_VARS['first_name'])?Trim($HTTP_POST_VARS['first_name']):"");
	$family_name = (isset($HTTP_POST_VARS['family_name'])?Trim($HTTP_POST_VARS['family_name']):"");
	$cboSex = (isset($HTTP_POST_VARS['cboSex'])?Trim($HTTP_POST_VARS['cboSex']):"");
	$txtAddress = (isset($HTTP_POST_VARS['txtAddress'])?Trim($HTTP_POST_VARS['txtAddress']):"");
	$txtPostCode = (isset($HTTP_POST_VARS['txtPostCode'])?Trim($HTTP_POST_VARS['txtPostCode']):"");
	$job_title = (isset($HTTP_POST_VARS['job_title'])?Trim($HTTP_POST_VARS['job_title']):"");
	$contact_email = (isset($HTTP_POST_VARS['contact_email'])?Trim($HTTP_POST_VARS['contact_email']):"");
	$contact_phone = (isset($HTTP_POST_VARS['contact_phone'])?Trim($HTTP_POST_VARS['contact_phone']):"");
	$residence_telephone = (isset($HTTP_POST_VARS['residence_telephone'])?Trim($HTTP_POST_VARS['residence_telephone']):"");
	$fax = (isset($HTTP_POST_VARS['fax'])?Trim($HTTP_POST_VARS['fax']):"");
	$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
	$sql_update_qry = "update cs_resellerdetails set reseller_title = '$cboTitle', reseller_firstname = '$first_name', reseller_lastname = '$family_name', reseller_sex = '$cboSex', reseller_address = '$txtAddress', reseller_zipcode = '$txtPostCode', reseller_jobtitle = '$job_title', reseller_email = '$contact_email',reseller_phone='$contact_phone',reseller_res_phone='$residence_telephone', reseller_faxnumber ='$fax' where reseller_id=$resellerLogin";
	if(!($run_update_qry =mysql_query($sql_update_qry,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}

	$sql_select_qry ="select *  from cs_resellerdetails where reseller_id=$resellerLogin";
	if(!($run_select_qry =mysql_query($sql_select_qry,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}	
	if($show_select_value = mysql_fetch_array($run_select_qry)){ 
?>
<script language="javascript">

function validation(){
  if(document.Frmcompany.companyname.value==""){
    alert("Please enter company name")
    document.Frmcompany.companyname.focus();
	return false;
  }

  if(document.Frmcompany.email.value == "") {
    alert("Please enter the email address.")
    document.Frmcompany.email.focus();
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
function HelpWindow() {
   advtWnd=window.open("aboutcompany.htm","Help","'status=1,scrollbars=1,width=500,height=550,left=0,top=0'");
   advtWnd.focus();
}

</script>

      <?php beginTable() ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%"  height="333" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center">
			<table  width="100%" height="40"  valign="bottom" >			
			  <tr>
                <td width="100%" valign="middle" align="left" height="40" bgcolor="#DDDDDD"><img border="0" src="<?=$tmpl_dir?>images/application.gif"><img border="0" src="<?=$tmpl_dir?>images/aboutyou.gif"><img border="0" src="<?=$tmpl_dir?>images/yourcompany1.gif"><img border="0" src="<?=$tmpl_dir?>images/yourbank.gif"><img border="0" src="<?=$tmpl_dir?>images/finishingline.gif"></td>
            </tr> 
			</table>
            &nbsp;
              <table border="0" cellpadding="0"  height="100" width="100%">
                <tr> 
                  <td align="center"  height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Company 
                    Information </td>
                </tr>
                <tr> 
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; 
                    Company Name &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="req" type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px" value="<?=$show_select_value[6]?>"> 
                  </td>
                </tr>
                <tr> 
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; 
                    User Name &nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"><b> 
                    <?=$show_select_value[3]?>
                    </b></font></td>
                </tr>
                <tr> 
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Email Address</font></td>
                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><input src="email" type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:200px" value="<?=$show_select_value[8]?>"> 
                  </td>
                </tr>
                <tr> 
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; URL 1</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="url" type="text" maxlength="100" name="url1" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[10]?>"> 
                  </td>
                </tr>
                <tr> 
                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; 
                    URL 2</font></td>
                  <td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><input type="text" maxlength="100" name="url2" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[42]?>"></td>
                </tr>
                <tr> 
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; URL 3</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" maxlength="100" name="url3" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[43]?>"> 
                  </td>
                </tr>
                <input type="hidden" name="company" value="company">
                <tr align="center" > 
                  <td height="30" colspan="2" valign="middle"><a href="javascript:window.history.back();"><img border="0" src="../images/back.jpg"></a> 
                    &nbsp; <input type="image" id="modifycompany" src="../images/continue.gif"> 
                  </td>
                </tr>
              </table>
              </td>
            </tr>
          </table>
	<?php endTable("Reseller Application","resellerBank.php") ?>
<?
}
include 'includes/footer.php';
}
?>