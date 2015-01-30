<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//include ("includes/sessioncheck.php");

$headerInclude="startHere";
include('application.php');
die();

include("includes/header.php");
require_once("../includes/function.php");
include("includes/message.php"); 
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";


function funcFillComboWithTitle ( $sTitle ) {
	$arrTitles[0] = "Prof";
	$arrTitles[1] = "Dr";
	$arrTitles[2] = "Mr";
	$arrTitles[3] = "Miss";
	$arrTitles[4] = "Mrs";
	$arrTitles[5] = "Others";
	for ( $iLoop = 0;$iLoop<6;$iLoop++ ) {
		if ( $arrTitles[$iLoop] == $sTitle ) {
			echo ("<option value='$arrTitles[$iLoop]' selected>$arrTitles[$iLoop]</option>");
		}
		else {
			echo ("<option value='$arrTitles[$iLoop]'>$arrTitles[$iLoop]</option>");
		}
	}
}

	if($resellerLogin!=""){
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
   if(document.Frmcompany.first_name.value == "") {
    alert("Please enter the first name.")
    document.Frmcompany.first_name.focus();
	return false;
  }
   if(document.Frmcompany.family_name.value == "") {
    alert("Please enter the family name.")
    document.Frmcompany.family_name.focus();
	return false;
  }
   if(document.Frmcompany.job_title.value == "") {
    alert("Please enter the job_title.")
    document.Frmcompany.job_title.focus();
	return false;
  }
   if(document.Frmcompany.contact_email.value == "") {
    alert("Please enter the contact email address.")
    document.Frmcompany.contact_email.focus();
	return false;
  }
   if(document.Frmcompany.confirm_contact_email.value == "") {
    alert("Please confirm the contact email address.")
    document.Frmcompany.confirm_contact_email.focus();
	return false;
  }
   if(document.Frmcompany.confirm_contact_email.value != document.Frmcompany.contact_email.value) {
    alert("Contact email ids do not match.")
    document.Frmcompany.confirm_contact_email.focus();
	return false;
  }
   if(document.Frmcompany.contact_phone.value == "") {
    alert("Please enter the telephone number.")
    document.Frmcompany.contact_phone.focus();
	return false;
  }
 
}


function HelpWindow() {
   advtWnd=window.open("aboutyou.htm","Help","'status=1,scrollbars=1,width=500,height=550,left=0,top=0'");
   advtWnd.focus();
}
</script>


      <?php beginTable() ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="333" class="disbd">

			   <tr>
              <td width="100%" height="333" valign="top" align="center">
			  <table  width="100%" height="40"  valign="bottom" >			
			  <tr>
                <td width="100%" valign="middle" align="left" height="40" bgcolor="#DDDDDD"><img border="0" src="<?=$tmpl_dir?>images/application.gif"><img border="0" src="<?=$tmpl_dir?>images/aboutyou1.gif"><img border="0" src="<?=$tmpl_dir?>images/yourcompany.gif"><img border="0" src="<?=$tmpl_dir?>images/yourbank.gif"><img border="0" src="<?=$tmpl_dir?>images/finishingline.gif"></td>
            </tr> 
			</table>
			<input type="hidden" name="username" value="<?=$show_select_value[3]?>">
			 <table border="0" cellpadding="0"  height="100" width="100%" >
			<tr>
				<td align="center" valign="center" height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Reseller Informations</td>
			</tr>
			<tr>
				  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; 
                    Title</font></td>
				<td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC">
					<select name="cboTitle" style="font-family:arial;font-size:10px;width:100px">
						<?php 
						$sTitle = $show_select_value[12];
						funcFillComboWithTitle ( $sTitle ); ?>
					</select>
				</td>
				</tr>
				<tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Your First Name</font></td>
                        
                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><input src="req" type="text" maxlength="100" name="first_name" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[13]?>">
                  </td>
                      </tr>
						<tr>
                        
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Your Last Name</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="req" type="text" maxlength="100" name="family_name" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[14]?>"> 
		                  </td>
                      </tr>
					   <tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;&nbsp;Sex</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
							<select name="cboSex" style="font-family:arial;font-size:10px">
							<?php
								if ( $show_select_value[15] == "Male" ) {
									echo("<option value='Male' selected>Male</option>");
								}else {
									echo("<option value='Male'>Male</option>");
								}
								if ( $show_select_value[15] == "Female" ) {
									echo("<option value='Female' selected>Female</option>");
								}else {
									echo("<option value='Female'>Female</option>");
								}
							?>	
							</select>
						 </td>
                      </tr>
					  <tr>
                        
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;&nbsp;Address</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
							<textarea title="noeffort" rows="5" cols="35" name="txtAddress" style="font-family:arial;font-size:10px"><?= $show_select_value[2] ?></textarea>
						 </td>
                      </tr>
					  <tr>
                        
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;&nbsp;Zipcode</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
							<input src="req" type="text" size="10" maxlength="10" value="<?= $show_select_value[16] ?>" name="txtPostCode" style="font-family:arial;font-size:10px">
						 </td>
                      </tr>
						<tr>
                        
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; What is your job title or position?</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" maxlength="100" name="job_title" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[17]?>"> 
                  </td>
                      </tr>
					<tr>
                        
                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; 
                    Contact email address</font></td>
                  <td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><input src="email" type="text" maxlength="100" name="contact_email" id="contact_email" style="font-family:arial;font-size:10px;width:175px" value="<?=$show_select_value[8]?>"></td>
                      </tr>	
					  <tr>
                        
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Please confirm email address</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="confirm|contact_email" type="text" maxlength="100" name="confirm_contact_email" style="font-family:arial;font-size:10px;width:175px" value="<?=$show_select_value[8]?>">
                  </td> </tr>	
					  <tr>
                        
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Your telephone number</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="phone" type="text" maxlength="15" name="contact_phone" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[9]?>"></td>
						</tr>
						<tr>
						
						<tr>
                        
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Your residence number</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
						<input type="text" maxlength="15" name="residence_telephone" style="font-family:arial;font-size:10px;width:150px" value="<?= $show_select_value[18] ?>"></td>
						</tr>
						
						<tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Your fax number</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
						<input type="text" maxlength="15" name="fax" style="font-family:arial;font-size:10px;width:150px" value="<?= $show_select_value[19] ?>"></td>
						</tr>
                      <tr>
                  <td align="center" valign="middle" height="30" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="../images/back.jpg"></a> 
                    &nbsp; 
						<input name="image" type="image" id="modifycompany" src="../images/continue.gif">
                        </td>
                      </tr>
                    </table>
              </td>
            </tr>
          </table>
	<?php endTable("Reseller Application","resellerCompany.php") ?>
<?
}
include 'includes/footer.php';

}
?>