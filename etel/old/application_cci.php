<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// companyEdit.php:	The  page used to modify the company profile.
include 'includes/sessioncheck.php';
$headerInclude="startHere";
require_once("includes/header.php");

include 'includes/topheader.php';
require_once( 'includes/function.php');
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$str_where_qry = "";
$str_default_reseller = $_SESSION['gw_title'];

	if($sessionlogin!=""){
	$sql_select_qry ="select * from cs_companydetails where userid=$sessionlogin";
	if(!($run_select_qry =mysql_query($sql_select_qry,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}
	if($show_select_value = mysql_fetch_array($run_select_qry)){
?>
<script language="JavaScript" src="scripts/general.js"></script>
<script language="javascript">

function HelpWindow() {
   advtWnd=window.open("aboutyou.htm","Help","'status=1,scrollbars=1,width=500,height=550,left=0,top=0'");
   advtWnd.focus();
}

function SelectMerchanttype() {
	if(document.getElementById('how_about_us').value=='other') {
		document.getElementById('how_about_us_other').disabled=false;
	}else {
		document.getElementById('how_about_us_other').value="";
		document.getElementById('how_about_us_other').disabled=true;
	}
	if(document.getElementById('how_about_us').value =="rsel" ){
		document.getElementById('reseller_other').disabled=false;
	} else {
		document.getElementById('reseller_other').value="";
		document.getElementById('reseller_other').disabled=true;
	}
}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td valign="top" align="left"  height="333">
    &nbsp;
      <?php beginTable() ?>
      <table border="0" cellpadding="0" cellspacing="0" width="100%" class="disbd">

			   <tr>
              <td width="100%" height="333" valign="middle" align="center">

			  <table  width="100%" height="40"  valign="bottom" >
			  <tr>
                <td width="100%" valign="middle" align="left" height="40" bgcolor="#DDDDDD"><img border="0" src="<?=$tmpl_dir?>images/application.gif"><img border="0" src="<?=$tmpl_dir?>images/aboutyou1.gif"><img border="0" src="<?=$tmpl_dir?>images/yourcompany.gif"><img border="0" src="<?=$tmpl_dir?>images/yourprocess.gif"><img border="0" src="<?=$tmpl_dir?>images/yourbank.gif"><img border="0" src="<?=$tmpl_dir?>images/finishingline.gif"></td>
            </tr>
			</table>

			 <table border="0" cellpadding="0"  height="100" width="100%" >
			<tr>
				<td align="center" valign="center" height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Your Details</td>
			</tr>
			<tr>
				  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;
                    Title</font></td>
				<td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC">
					<select name="cboTitle" style="font-family:arial;font-size:10px;width:100px">
						<?php
						$sTitle = $show_select_value['stitle'];
						funcFillComboWithTitle ( $sTitle ); ?>
					</select>

				</td>
			</tr>
						<tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; Your First Name</font></td>

                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="100" name="first_name" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($show_select_value['first_name'])?>">
                  </td>
                      </tr>
						<tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; Your Last Name</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="100" name="family_name" style="font-family:arial;font-size:10px;width:150px" value="<?=htmlentities($show_select_value['family_name'])?>">
		                  </td>
                      </tr>

					  <tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;&nbsp;Date
                    of birth</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
							<?php
							if($show_select_value['sdateofbirth'] !=""){
								list($iYear,$iMonth,$iDay) = split("-",$show_select_value['sdateofbirth']);
							} else {
								$iYear = "";
								$iMonth = "";
								$iDay = "";
							}
							funcFillDate ( $iDay,$iMonth,$iYear );
							?>
						 </td>
                      </tr>
					   <tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;&nbsp;Sex</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
							<select name="cboSex" style="font-family:arial;font-size:10px">
							<?php
								if ( $show_select_value['ssex'] == "Male" ) {
									echo("<option value='Male' selected>Male</option>");
								}else {
									echo("<option value='Male'>Male</option>");
								}
								if ( $show_select_value['ssex'] == "Female" ) {
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
							<textarea title="req" rows="5" cols="35" name="txtAddress" style="font-family:arial;font-size:10px"><?= htmlentities($show_select_value['saddress']) ?></textarea>
						 </td>
                      </tr>
					  <tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;&nbsp;Zipcode</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
							<input type="text" src='req' size="10" maxlength="7" value="<?= $show_select_value['spostcode'] ?>" name="txtPostCode" style="font-family:arial;font-size:10px">
						 </td>
                      </tr>
						<tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; What is your job title or position?</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="100" name="job_title" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['job_title']?>">
                  </td>
                      </tr>
					<tr>

                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;
                    Instant Messanger </font></td>
                  <td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><select onChange="document.getElementById('cd_contact_im').value='';" name="cd_contact_im_type" id="cd_contact_im_type" style="font-family:arial;font-size:10px;">
                    <option value="" selected>Select</option>
                    <option value="AIM: ">AIM</option>
                    <option value="ICQ: ">ICQ</option>
                    <option value="Yahoo: ">Yahoo</option>
                    <option value="MSN: ">MSN</option>
                    <option value="Other: ">Other</option>
                  </select>
                  <input type="text" src='' maxlength="100" id="cd_contact_im" name="cd_contact_im" style="font-family:arial;font-size:10px;width:175px" value="<?=$show_select_value['cd_contact_im']?>"></td>
               </tr>
					<tr>

                  <td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp;
                    Contact email address</font></td>
                  <td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="100" name="contact_email" style="font-family:arial;font-size:10px;width:175px" value="<?=($show_select_value['contact_email']?$show_select_value['contact_email']:$show_select_value['email'])?>"></td>
                      </tr>
					  <tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; Please confirm email address</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="100" name="confirm_contact_email" style="font-family:arial;font-size:10px;width:175px" value="<?=$show_select_value['contact_email']?>">
                  </td> </tr>
					  <tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; Your telephone number</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' maxlength="15" name="contact_phone" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['contact_phone']?>"></td>
						</tr>
						<tr>

						<tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; Your residence number</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
						<input type="text" src='req' maxlength="15" name="residence_telephone" style="font-family:arial;font-size:10px;width:150px" value="<?= $show_select_value['sresidencetelephone'] ?>"></td>
						</tr>

						<tr>

                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; Your fax number</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC">
						<input type="text" src='req' maxlength="15" name="fax" style="font-family:arial;font-size:10px;width:150px" value="<?= $show_select_value['sfax'] ?>"></td>
						</tr>
				<tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; Where did you hear about <?= $str_default_reseller?>?</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><select name="how_about_us" id="how_about_us" style="font-family:arial;font-size:10px;width:100px" onChange="SelectMerchanttype()">
					<?= func_fill_info_source_combo($cnn_cs, $show_select_value['how_about_us']) ?>
						  </select></td>
					</tr>
					<tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; If other</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src='req' id="how_about_us_other" name="how_about_us_other" style="font-family:arial;font-size:10px;width:150px"  value="<?= htmlentities($show_select_value['reseller_other']) ?>"></td>
					</tr>
					<tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">
                    &nbsp; If reseller</font></td>
					<td align="left" height="30" width="50%" bgcolor="#F8FAFC">

					<input name="reseller_other" id="reseller_other" type="text" src='req' style="font-family:verdana;font-size:10px;width:150px" value='<?= $str_default_reseller ?>'>
					</td>
					</tr>
						<input type="hidden" name="company" value="company">
                      <tr>
                  <td align="center" valign="middle" height="30" colspan="2"><a href="javascript:HelpWindow();"><img border="0" src="<?=$tmpl_dir?>images/help_s.gif"></a>&nbsp;&nbsp;<a href="mailto:<?=$_SESSION['gw_emails_sales']?>"><img border="0" src="<?=$tmpl_dir?>images/mailme_s.gif"></a>&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="<?=$tmpl_dir?>images/back.jpg"></a>
                   &nbsp;<input name="image" type="image" id="modifycompany" src="<?=$tmpl_dir?>images/continue.gif">
                        </td>
                      </tr>
                    </table>
              </td>
            </tr>
          </table>
	<?php endTable("Merchant Application","application_aci.php") ?></td>
  </tr>
</table>
<br>
<script>
if(document.getElementById('how_about_us').value =="other" ){
	document.getElementById('how_about_us_other').disabled=false;
} else {
	document.getElementById('how_about_us_other').value="";
	document.getElementById('how_about_us_other').disabled=true;
}
if(document.getElementById('how_about_us').value =="rsel" ){
	document.getElementById('reseller_other').disabled=false;
} else {
	document.getElementById('reseller_other').value="";
	document.getElementById('reseller_other').disabled=true;
}
</script>

<?
}
include 'includes/footer.php';
}
?>