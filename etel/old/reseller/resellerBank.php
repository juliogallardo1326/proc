<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyleft (C) Etelegate.com 2003-2004, All lefts Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Zerone-Consulting
// Description:     Online PaymentGateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// resellerBank.php:	The  page used to modify the company profile. 
include ("includes/sessioncheck.php");

$headerInclude="startHere";
include("includes/header.php");
require_once("../includes/function.php");
include("includes/message.php"); 
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";

if($resellerLogin!=""){
	
	$companyname = (isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"");
	$email = (isset($HTTP_POST_VARS['email'])?quote_smart($HTTP_POST_VARS['email']):"");
	$reseller_url1 = (isset($HTTP_POST_VARS['url1'])?quote_smart($HTTP_POST_VARS['url1']):"");
	$reseller_url2 = (isset($HTTP_POST_VARS['url2'])?quote_smart($HTTP_POST_VARS['url2']):"");
	$reseller_url3 = (isset($HTTP_POST_VARS['url3'])?quote_smart($HTTP_POST_VARS['url3']):"");
	$username = (isset($HTTP_POST_VARS['username'])?quote_smart($HTTP_POST_VARS['username']):"");

	$sql_update_qry = "update cs_resellerdetails set reseller_companyname='$companyname',reseller_email='$email',reseller_url='$reseller_url1',reseller_url1='$reseller_url2',reseller_url2='$reseller_url3' where reseller_id=$resellerLogin";
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
<script language="javascript" src="../scripts/general.js"></script>
<script language="javascript">
function validation(){
trimSpace(document.Frmcompany.currentBank)
trimSpace(document.Frmcompany.bank_other)
trimSpace(document.Frmcompany.bank_address)
trimSpace(document.Frmcompany.bank_country)
trimSpace(document.Frmcompany.bank_phone)
trimSpace(document.Frmcompany.bank_sort_code)
trimSpace(document.Frmcompany.bank_account_number)
trimSpace(document.Frmcompany.bank_swift_code)
trimSpace(document.Frmcompany.vat_number)
trimSpace(document.Frmcompany.company_number)
  /* if(document.Frmcompany.currentBank.value==""){
    alert("Please select a bank.")
    document.Frmcompany.currentBank.focus();
	return false;
  }
   if(document.Frmcompany.currentBank.value=="other" && document.Frmcompany.bank_other.value==""){
    alert("Please enter a bank name.")
    document.Frmcompany.bank_other.focus();
	return false;
  }

  if(document.Frmcompany.bank_country.value==""){
    alert("Please select the country.")
    document.Frmcompany.bank_country.focus();
	return false;
  }
 

  if(document.Frmcompany.bank_account_number.value==""){
    alert("Please enter the account number.")
    document.Frmcompany.bank_account_number.focus();
	return false;
  }
  if(document.Frmcompany.bank_swift_code.value==""){
    alert("Please enter the swift code.")
    document.Frmcompany.bank_swift_code.focus();
	return false;
  }*/
  return true;
}
function HelpWindow() {
   advtWnd=window.open("aboutbank.htm","Help","'status=1,scrollbars=1,width=500,height=450,left=0,top=0'");
   advtWnd.focus();
}
</script>


      <?php beginTable() ?>
	  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="disbd">
          <tr>
          <td width="100%" valign="top" align="center">
		  <table  width="100%" height="40"  valign="bottom" >			
			  <tr>
                <td width="100%" valign="middle" align="left" height="40" bgcolor="#DDDDDD"><img border="0" src="<?=$tmpl_dir?>images/application.gif"><img border="0" src="<?=$tmpl_dir?>images/aboutyou.gif"><img border="0" src="<?=$tmpl_dir?>images/yourcompany.gif"><img border="0" src="<?=$tmpl_dir?>images/yourbank1.gif"><img border="0" src="<?=$tmpl_dir?>images/finishingline.gif"></td>
            </tr> 
			</table>
			<input type="hidden" name="username" value="<?=$username?>">
            <table border="0" cellpadding="0"  height="100" width="100%">
			  <tr><td align="center" valign="center" height="30" colspan="2" bgcolor="#CCCCCC" class="whitehd">Bank Processing Information</td>
			  </tr>
				<tr><td align="center" valign="center" height="30" colspan="2"><font face="verdana" size="1" color="#FF0000"><strong>"Banking information used to wire reseller commissions."</strong></font></td>
			  </tr>
			  <tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
				 &nbsp; With which bank do you hold a company account?</font></td>
				<td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC">
				<select name="currentBank" style="font-family:arial;font-size:10px;width:270px" >
					<?=func_get_bank_select($show_select_value[26])?>
					<option value="other">other</option>
				</select> 
				</td></tr>
				<tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                 &nbsp;If 'Other', please specify:&nbsp;&nbsp;</font></td>
                 <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_other" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[27]?>"> 
                  </td></tr>
				<tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                &nbsp; Beneficiary Name:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="req" type="text" name="beneficiary_name" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[28]?>"> 
                </td></tr>
				<tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                &nbsp; Name On Bank Account:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="req" type="text" name="bank_account_name" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[29]?>"> 
                </td></tr>
				<tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                &nbsp; Bank Address:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input src="req" type="text"  name="bank_address" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[30]?>"> 
                </td></tr>
				<tr><td align="left" valign="middle" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">&nbsp; 
                Bank Country: &nbsp;&nbsp;</font></td>
				<td align="left" height="30"  width="50%" valign="middle" bgcolor="#F8FAFC"><select name="bank_country" title="reqmenu" style="font-family:arial;font-size:10px;width:170px">
                    <?=func_get_country_select($show_select_value[31],1)?>
				</select>
				</td></tr>	
				<tr>   
                <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                &nbsp; Bank Telephone Number:&nbsp;&nbsp;</font></td>
                <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" src="req" name="bank_phone" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[32]?>">
                </td> </tr>	
			  <tr>
				<td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
				  &nbsp;
				  Sort Code/Branch Number:&nbsp;&nbsp;</font></td>
				<td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_sort_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[33]?>"></td>
				</tr>
				<tr>
                  <td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Bank Account Number: &nbsp;&nbsp;</font></td>
                  <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_account_number" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[34]?>"></td>
					</tr>
					<tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Bank Swift Code: &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="bank_swift_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[35]?>"></td>
						</tr> 
						<tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; BCI Code: &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="bci_code" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[46]?>"></td>
						</tr> 
						<tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; VAT Number: &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="vat_number" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[47]?>"></td>
						</tr> 
						<tr><td align="left" valign="center" height="30" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1">	
                    &nbsp; Company No: &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%" bgcolor="#F8FAFC"><input type="text" name="company_number" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value[48]?>"></td>
						</tr> 
						<input type="hidden" name="company" value="company">
                      <tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Bank Routing Number: </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><input type="text" name="rd_bank_routingnumber" style="font-family:arial;font-size:10px;width:150px" value="<?=$show_select_value['rd_bank_routingnumber']?>">
                </td>
              </tr>
	          <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Bank Routing Type: </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC">
                  <select name="rd_bank_routingcode" id="rd_bank_routingcode" style="font-family:arial;font-size:10px;width:170px">
                    <option value="select">- Select -</option>
                    <option value="1">ABA</option>
                    <option value="2">SWIFT</option>
                    <option value="3">Chips ID</option>
                    <option value="4">Sort Code</option>
                    <option value="5">Transit Number</option>
                    <option value="6">BLZ Code</option>
                    <option value="7">BIC Code</option>
                    <option value="8">Other</option>
				  </select>
				  <script language="javascript">document.getElementById('rd_bank_routingcode').value='<?=$show_select_value['rd_bank_routingcode']?>'</script>
                </td>
              </tr>
              <tr>
                <td align="left" valign="center" height="29" width="50%" bgcolor="#F8FAFC"><font face="verdana" size="1"> &nbsp;&nbsp;Bank Instructions (Optional): </font></td>
                <td align="left" height="29" width="50%" bgcolor="#F8FAFC"><textarea name="rd_bank_instructions" cols="50" rows="3"><?=$show_select_value['rd_bank_instructions']?></textarea>
                </td>
              </tr>
                  <td align="center" valign="center" height="30" colspan="2">&nbsp;&nbsp;<a href="javascript:window.history.back();"><img border="0" src="../images/back.jpg"></a> 
                    &nbsp;<input name="image" type="image" id="modifycompany" src="../images/continue.gif">
					<br>
                        </td>
                      </tr>
                    </table>
           		</form>
              </td>
            </tr>
          </table>
	<?php endTable("Reseller Bank","submitApplication.php") ?>
<?
}
include 'includes/footer.php';
}
?>