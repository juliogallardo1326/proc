<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//include ("includes/sessioncheck.php");

$headerInclude="blank";
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
function validation() {
  if(document.Frmcompany.email.value == "") {
    alert("Please enter the email address.")
    document.Frmcompany.email.focus();
	return false;
  }
   if(document.Frmcompany.confirm_email.value == "") {
    alert("Please enter the confirm email address.")
    document.Frmcompany.confirm_email.focus();
	return false;
  }
  if(document.Frmcompany.email.value != document.Frmcompany.confirm_email.value) {
    alert("Please enter the correct email address.")
    document.Frmcompany.confirm_email.focus();
	return false;
  }
 
}

function HelpWindow() {
   advtWnd=window.open("aboutyou.htm","Help","'status=1,scrollbars=1,width=500,height=550,left=0,top=0'");
   advtWnd.focus();
}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="70%" height="200" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
              <img border="0" src="../images/spacer.gif" width="1" height="1">
              </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
             <tr>
              <td width="100%" valign="middle" align="left" height="25" class="disctxhd">&nbsp;
                Reseller Application</td>
            </tr>
			   <tr>
              <td width="100%" height="200" valign="top" align="center">
           <form action="resellerBank.php" method="post" onsubmit="return validation()" name="Frmcompany">
			 <input type="hidden" name="username" value="<?=$show_select_value[3]?>">
			  <table border="0" cellpadding="0"  height="100" width="100%" >
                <tr> 
                  <td align="center" valign="center" height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Website 
                    informations</td>
                </tr>
				<tr>
					<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;
					  Company Name &nbsp;</font></td>
					<td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px" value="<?=$show_select_value[6]?>"> 
			  	</td>
				  </tr>
				  <tr>
					<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;
					  User 
					  Name &nbsp;</font></td>
				  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"><b> 
				<?=$show_select_value[3]?>
				</b></font></td>
				  </tr>
                <tr> 
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Email Address</font></td>
                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:200px" value="<?=$show_select_value[8]?>"> 
                  </td>
                </tr>
				<tr> 
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Confirm Email Address</font></td>
                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" maxlength="100" name="confirm_email" style="font-family:arial;font-size:10px;width:200px" value="<?=$show_select_value[8]?>"> 
                  </td>
                </tr>
				 <tr> 
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; URL 1</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" maxlength="100" name="url1" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[10]?>"> 
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
                <tr> 
                  <td align="center" valign="middle" height="30" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="../images/back.jpg"></a> 
                    &nbsp; <input name="image" type="image" id="modifycompany" src="../images/continue.gif"> 
                  </td>
                </tr>
              </table>
           		</form>
              </td>
            </tr>
          </table></td>
     </tr>
</table><br>
<?
}
include 'includes/footer.php';
}
?>