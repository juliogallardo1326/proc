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
// AddCompanyUser.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

require_once("includes/function.php");

$sessionlogintype =isset($HTTP_SESSION_VARS["sessionlogin_type"])?$HTTP_SESSION_VARS["sessionlogin_type"]:"";
$sessioncompanyid =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$iEditTsrId = (isset($HTTP_POST_VARS["hdTsr"])?$HTTP_POST_VARS["hdTsr"]:"");

$iTsrId	=	(isset($HTTP_GET_VARS["id"])?trim($HTTP_GET_VARS["id"]):"");
$sError	=	"";
if($_SESSION["sessionlogin_type"] == "tele")
{
	$i_userid="";
	$str_username="";
	$str_password="";
	
	$i_userid=isset($HTTP_GET_VARS["uid"])?$HTTP_GET_VARS["uid"]:"";
	if ($i_userid!="" && is_numeric($i_userid))
	{
		$qry_getdetails = "Select username,password from cs_companyusers where id=$i_userid";
		$rst_getdetails = mysql_query($qry_getdetails,$cnn_cs);
		if(mysql_num_rows($rst_getdetails)>0)
		{
			$str_username=mysql_result($rst_getdetails,0,0);
			$str_password=mysql_result($rst_getdetails,0,1);
		}
	
	}
	$sFirstName		=	(isset($HTTP_POST_VARS["txtFirstName"])?trim($HTTP_POST_VARS["txtFirstName"]):"");
	$sLastName		=	(isset($HTTP_POST_VARS["txtLastName"])?trim($HTTP_POST_VARS["txtLastName"]):"");
	$sUserName		=	(isset($HTTP_POST_VARS["txtUserName"])?trim($HTTP_POST_VARS["txtUserName"]):"");
	$sPassword		=	(isset($HTTP_POST_VARS["txtPassword"])?trim($HTTP_POST_VARS["txtPassword"]):"");
	$dAmount 		=	(isset($HTTP_POST_VARS["txtAmount"])?trim($HTTP_POST_VARS["txtAmount"]):"");
	$dVoiceAuthFee 	=	(isset($HTTP_POST_VARS["txtVoiceAuthFee"])?trim($HTTP_POST_VARS["txtVoiceAuthFee"]):"");

	if ( $iTsrId != "" ) {
		$qrySelect = "select * from cs_tsrusers where tsr_user_id = $iTsrId ";
		if ( !($rstSelect = mysql_query($qrySelect,$cnn_cs))) {
			print("can not execute query");
			exit();	
		} else {
			$sFirstName		=	mysql_result($rstSelect,0,3);	
			$sLastName		=	mysql_result($rstSelect,0,4);	
			$sUserName		=	mysql_result($rstSelect,0,5);	
			$sPassword		=	mysql_result($rstSelect,0,6);	
			$dAmount 		=	mysql_result($rstSelect,0,7);
			$dVoiceAuthFee 	=	mysql_result($rstSelect,0,8);
		}
	}
	// *************** TSR user adding part ******************************
	if ( isset($HTTP_POST_VARS["txtFirstName"]) && isset($HTTP_POST_VARS["txtLastName"]) && isset($HTTP_POST_VARS["txtUserName"]) && isset($HTTP_POST_VARS["txtPassword"]) ) {
		
		
		if(	$_SESSION["sessionlogin_type"] == "tele" ) {
			$sAddedUser = "T";
		} else {
			$sAddedUser = "C";
		}
		
		$qryInsert = "insert into cs_tsrusers (tsr_added_by,tsr_added_user_id,tsr_first_name,tsr_last_name,tsr_user_name,tsr_password,tsr_amount_per_sale,tsr_voice_auth_fee)";
		$qryInsert .= " values ('$sAddedUser','$sessioncompanyid','$sFirstName','$sLastName','$sUserName','$sPassword',$dAmount,$dVoiceAuthFee)";
		$bIsUserExist = func_checkUsernameExistInAnyTable($sUserName,$cnn_cs);
		$qryUpdate	=	"update cs_tsrusers set tsr_first_name = '$sFirstName',tsr_last_name = '$sLastName',tsr_password = '$sPassword',tsr_amount_per_sale =$dAmount,tsr_voice_auth_fee = $dVoiceAuthFee where tsr_user_id = $iEditTsrId  ";
	
		$qrySelect = "select * from cs_tsrusers where tsr_user_name ='$sUserName'";
		
		if ( $iEditTsrId == "" ) {
			if ( $bIsUserExist ) {
				$sError = "This user name already exist";
			} else {
				if ( !(mysql_query($qryInsert,$cnn_cs))) {
					print("Can not execute query");
					exit();
				}else{
					header('location:addtsrusermessage.php?msg=add');
				}
			}
		} else {
			if(!(mysql_query($qryUpdate))) {
				print("Can not execute query");
				print($qryUpdate);
				exit();
			} else {
				header('location:addtsrusermessage.php?msg=edit');
			}
		}
				
	}
	// *******************************************************************
include("includes/header.php");
$headerInclude="tsr";
include("includes/topheader.php");
include("includes/message.php");	
	
?>
<script language="JavaScript" >
function validation(objForm) {
	var bCorrect = true;
	objElement = objForm.txtFirstName;
	if ( bCorrect && objElement.value == "" ) {
		alert("Please enter the first name.");
		bCorrect = false;
		objElement.focus();
	}
	objElement = objForm.txtLastName;
	if ( bCorrect && objElement.value == "" ) {
		alert("Please enter the last name.");
		bCorrect = false;
		objElement.focus();
	}
	objElement = objForm.txtUserName;
	if ( bCorrect && objElement.value == "" ) {
		alert("Please enter the user name.");
		bCorrect = false;
		objElement.focus();
	}
	objElement = objForm.txtPassword;
	if ( bCorrect && objElement.value == "" ) {
		alert("Please enter the password.");
		bCorrect = false;
		objElement.focus();
	}
	objElement = objForm.txtAmount;
	if ( bCorrect && objElement.value == "" ) {
		alert("Please enter per sale amount.");
		bCorrect = false;
		objElement.focus();
	}
	objElement = objForm.txtAmount;
	if ( bCorrect && isNaN(objElement.value)){
		alert("Please enter valid per sale amount.");
		bCorrect = false;
		objElement.focus();
	}
	objElement = objForm.txtVoiceAuthFee;
	if ( bCorrect && objElement.value == "" ) {
		alert("Please enter the Voice Authorization Fee.");
		bCorrect = false;
		objElement.focus();
	}
	objElement = objForm.txtVoiceAuthFee;
	if ( bCorrect && isNaN(objElement.value)){
		alert("Please enter valid Voice Authorization Fee.");
		bCorrect = false;
		objElement.focus();
	}
	return bCorrect;
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

</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="80%" >
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%">
      <tr>
        <td width="100%" height="22">&nbsp;
         
        </td>
      </tr>
      <tr>
        <td width="100%" valign="top" align="left">
		<table border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
		<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Add TSR User</span></td>
		<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
		<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		<td class="lgnbd" width="987" colspan="5">
	<form name="frmTsrUser" action="addtsruser.php"  method="post" onsubmit="javascript:return validation(document.frmTsrUser);">
	 <input type="hidden" name="companyid" value="<?=$sessioncompanyid?>">
	 <input type="hidden" name="userid" value="<?=$i_userid?>">
	 <input type="hidden" name="hid_username" value="<?=$str_username?>">
	 <br>
	 <div align="center"><font color="#FF0000" face="Verdana, Arial, Helvetica, sans-serif"><?= $sError ?></font></div>
	  <table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">  
		 <tr>
		  <td height="70"  valign="center" align="center"  width="50%">
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">First Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="txtFirstName" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$sFirstName?>"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Last Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="txtLastName" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$sLastName?>"></td>
		  </tr>
		  <?php
		  	if (  $iTsrId == "" ) {
		  ?>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">User Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="txtUserName" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$sUserName?>"></td>
		  </tr>
		  <?php
		  } else {
?>
		   <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">User Name:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="txtUserName" type="hidden" value="<?=$sUserName?>"><font face="verdana" size="1"><?=$sUserName?></font></td>
		  </tr>
<?php		  
		  }
		  ?>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Password:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="txtPassword" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$sPassword?>"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Amount per sale:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="txtAmount" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$dAmount?>"></td>
		  </tr>
		  <tr>
			<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Voice Authorization Fee:&nbsp;</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="txtVoiceAuthFee" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$dVoiceAuthFee?>"></td>
		  </tr>
		  
		  
		</table>
		  </td>
		  </tr>
		  <tr><td align="center">&nbsp;&nbsp;&nbsp;<input type="image" id="adduser" src="images/submit.jpg"></input>
		   </td>
		</tr>
		</table>
		<input type="hidden" name="hdTsr" value="<?=$iTsrId?>">
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
	 <tr><td>
	<br>
	
		</table>
		</td></tr>
    </table>
	   </td>
	</tr>
</table>

<?php
}
include("includes/footer.php");
?>
<?php
function func_user_exists($username,$cnn_connection)
{
	$i_returnstring = 0;
	$qry_select_user = "Select userid from cs_companyusers where username = '".$username."'";
	$rst_select_user = mysql_query($qry_select_user,$cnn_connection);
	if (mysql_num_rows($rst_select_user)>0)
	{
		$i_returnstring = 1;
	}
	return $i_returnstring;
}

?>