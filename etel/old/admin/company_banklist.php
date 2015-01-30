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
// company_banklist.php:	The admin page functions for selecting the company for adding company user. 
include("includes/sessioncheck.php");


$headerInclude="bank1";
include("includes/header.php");
include("includes/message.php");
$sBankName	=	"";
$iBankId	=	"";
$sBankEmail	=	"";
$i_paybackday=	"";
$i_payweekfrom=	"";
$i_payweekto=	"";
$i_payday	=	"";


$i_miscadd	=	"";

$i_transactionfee="";
$i_discountrate="";
$i_rollingreserve="";
$i_chargebackfee ="";
$i_reserve  ="";
$iId		=	(isset($HTTP_GET_VARS["id"])?quote_smart($HTTP_GET_VARS["id"]):"");
$act		=	(isset($HTTP_GET_VARS["act"])?quote_smart($HTTP_GET_VARS["act"]):"");
if ( $iId != "" ) {
	$qrySelect	=	"select * from cs_bank where bank_id = ".$iId;
	
	$rstSelect	=	mysql_query($qrySelect,$cnn_cs);
	if ( mysql_num_rows($rstSelect) > 0 ) {
	$bankInfo = mysql_fetch_assoc($rstSelect);
 		$iBankId	=	$bankInfo['bank_id'];
		$sBankName	=	$bankInfo['bank_name'];
		$sBankEmail	=	$bankInfo['bank_email'];
		$i_paybackday=	$bankInfo['bank_paybackday'];
		$i_payweekfrom=	$bankInfo['bank_payweekfrom'];
		$i_payweekto=	$bankInfo['bank_payweekto'];
		$i_payday=		$bankInfo['bank_payday'];
		$i_discountrate=	$bankInfo['discountrate'];
		$i_transactionfee =		$bankInfo['transactionfee'];
		$i_rollingreserve=		$bankInfo['rollingreserve'];
		$i_chargebackfee =		$bankInfo['chargebackfee'];
		$bk_cc_bank_enabled = $bankInfo['bk_cc_bank_enabled'];
		
	}
}	




?>
<script language="JavaScript">
function funcValidate(objForm) {
	return true;
}
function func_settoweek(obj_element)
{
var fromweek=obj_element.cbo_from_week.value;
if (fromweek==1)
var toweek=7;
else
var toweek=fromweek-1;
obj_element.cbo_to_week.value=toweek;
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="63%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
	<table width="75%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td height="22" align="left" valign="top" width="10" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		  <td height="22" align="center" valign="middle" width="168" background="../images/menucenterbg.gif" ><span class="whitehd"> 
            <?php
				if ( $iBankId == "" ) {
		    		print("Add Bank Details");
				} else {
					print(" Edit Bank Details");
				}	
		  ?>
            </span></td>
		  <td height="22" align="left" valign="top" width="49" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
		  <td height="22" align="left" valign="top" width="335" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		  <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="10" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
		</tr>
		<tr>
		  <td class="lgnbd" colspan="5"> <br>
		<form name="frmBanks" action="assign_bank.php"  method="post" onSubmit="return funcValidate(document.frmBanks);" >
			  <table border="0" cellpadding="3" cellspacing="3" align="center" width="90%">
                <tr> 
                  <td colspan="2" align="center"> 
                    <?php
							$msg = (isset($HTTP_GET_VARS["msg"])?$HTTP_GET_VARS["msg"]:"");
							if( $msg == "add" ) {
								print("<font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">New Bank added successfully</font>");
							}
							if( $msg == "edit" ) {
								print("<font size=2 face=\"Verdana, Arial, Helvetica, sans-serif\">Bank edited successfully</font>");
							}
							
					?>
                  </td>
                </tr>
                <tr> 
                  <td width=162><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Bank 
                    Name</font></td>
                  <td><input type="text" name="txtBankName" value="<?= $sBankName ?>" size="30" maxlength="250" style="width:200px;font-family:verdana;font-size:10px"></td>
                </tr>
                <tr> 
                  <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" width=150px>Bank 
                    Email</font></td>
                  <td><input type="text" name="txtBankEmail" value="<?= $sBankEmail ?>" size="30" maxlength="250" style="width:200px;font-family:verdana;font-size:10px"></td>
                </tr>
                <tr> 
                  <td width=162><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Pay 
                    Week</font></td>
                  <td valign="middle" class="tdbdr"> <input type="text" name="txt_payback" size="5"value="<?= $i_paybackday ?>"> 
                    <select name="cbo_from_week" onChange="func_settoweek(document.frmBanks)" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;" >
                      <option value="1" <?= $i_payweekfrom == "1" ? "selected" : ""?>>Sunday</option>
                      <option value="2" <?= $i_payweekfrom == "2" ? "selected" : ""?>>Monday</option>
                      <option value="3" <?= $i_payweekfrom == "3" ? "selected" : ""?>>Tuesday</option>
                      <option value="4" <?= $i_payweekfrom == "4" ? "selected" : ""?>>Wednesday</option>
                      <option value="5" <?= $i_payweekfrom == "5" ? "selected" : ""?>>Thursday</option>
                      <option value="6" <?= $i_payweekfrom == "6" ? "selected" : ""?>>Friday</option>
                      <option value="7" <?= $i_payweekfrom == "7" ? "selected" : ""?>>Saturday</option>
                    </select> <select name="cbo_to_week" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
                      <option value="1" <?= $i_payweekto == "1" ? "selected" : ""?>>Sunday</option>
                      <option value="2"   <?= $i_payweekto == "2" ? "selected" : ""?>>Monday</option>
                      <option value="3"  <?= $i_payweekto == "3" ? "selected" : ""?>>Tuesday</option>
                      <option value="4"  <?= $i_payweekto == "4" ? "selected" : ""?>>Wednesday</option>
                      <option value="5"  <?= $i_payweekto == "5" ? "selected" : ""?>>Thursday</option>
                      <option value="6"  <?= $i_payweekto == "6" ? "selected" : ""?>>Friday</option>
                      <option value="7"  <?= $i_payweekto == "7" ? "selected" : ""?>>Saturday</option>
                    </select> </td>
                </tr>
                <tr> 
                  <td width=162><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Pay 
                    day</font></td>
                  <td><select name="cbo_payday" style="font-size:11px;width:100px;font-height:10px;font-face:verdana;">
                      <option value="1"  <?= $i_payday == "1" ? "selected" : ""?>>Sunday</option>
                      <option value="2" <?= $i_payday == "2" ? "selected" : ""?>>Monday</option>
                      <option value="3" <?= $i_payday == "3" ? "selected" : ""?>>Tuesday</option>
                      <option value="4" <?= $i_payday == "4" ? "selected" : ""?>>Wednesday</option>
                      <option value="5" <?= $i_payday == "5" ? "selected" : ""?>>Thursday</option>
                      <option value="6" <?= $i_payday == "6" ? "selected" : ""?>>Friday</option>
                      <option value="7" <?= $i_payday == "7" ? "selected" : ""?>>Saturday</option>
                    </select></td>
                </tr>
                <tr> 
                  <td valign="middle" width="162" height="30"><font face="verdana" size="2">Discount 
                    Rate </font>&nbsp;</td>
                  <td width="333" align="left" > <input type="text" name="disc_rate" style="width:60px;font-family:verdana;font-size:10px" value="<?= $i_discountrate?>"> 
                  </td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Transaction 
                    Fee</font>&nbsp;&nbsp;</td>
                  <td width="333" align="left" > <input type="text" name="trans_fee" style="width:60px;font-family:verdana;font-size:10px" value="<?= $i_transactionfee ?>"> 
                  </td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Rolling Reserve</font>&nbsp;&nbsp;</td>
                  <td width="333" align="left" > <input type="text" name="roll_res" style="width:60px;font-family:verdana;font-size:10px" value="<?=$i_rollingreserve  ?>"></td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Chargeback Fee 
                    </font>&nbsp;&nbsp;</td>
                  <td width="333" align="left" > <input type="text" name="chrgbk_fee" style="width:60px;font-family:verdana;font-size:10px" value="<?=$i_chargebackfee ?>"> 
                  </td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >UserName</font>&nbsp;&nbsp;</td>                                                                                           

                  <td width="333" align="left" > <input name="bk_username" type="text" id="bk_username" style="font-family:verdana;font-size:10px" value="<?=$bankInfo['bk_username']?>"> 
                  </td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Password</font>&nbsp;&nbsp;</td>
                  <td width="333" align="left" > <input name="bk_password" type="text" id="bk_password" style="font-family:verdana;font-size:10px" value="<?=$bankInfo['bk_password'] ?>"> 
                  </td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Additional Id </font>&nbsp;&nbsp;</td>
                  <td width="333" align="left" > <input name="bk_additional_id" type="text" id="bk_additional_id" style="font-family:verdana;font-size:10px" value="<?=$bankInfo['bk_additional_id'] ?>"> 
                  </td>
                </tr>
				
		  <tr>
		  <td width="162" ><font face="verdana" size="2" >CC Transactions to Bank 
			Email Address</font></td>
			<td align="left" valign="center" height="30" width="50%"><input name="bk_cc_bank_enabled" type="checkbox" id="bk_cc_bank_enabled" value="1" <?=($bk_cc_bank_enabled?"checked":"")?>></td>
		  </tr> 
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Supports Credit</font>&nbsp;&nbsp;</td>
                  <td width="333" align="left" > <input type="checkbox" name="checkbox" value="checkbox" <?=($bankInfo['bk_cc_support']==1?"checked":"")?> disabled></td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Supports Check </font></td>
                  <td width="333" align="left" ><input type="checkbox" name="checkbox" value="checkbox" <?=($bankInfo['bk_ch_support']==1?"checked":"")?> disabled> </td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Supports Web900 </font></td>
                  <td width="333" align="left" ><input type="checkbox" name="checkbox" value="checkbox" <?=($bankInfo['bk_w9_support']==1?"checked":"")?> disabled> </td>
                </tr>
                <tr> 
                  <td  width=162  ><font face="verdana" size="2" >Integration Function</font>&nbsp;&nbsp;</td>
                  <td width="333" align="left" > <input name="chrgbk_fee" type="text" disabled style="font-family:verdana;font-size:10px" value="<?=$bankInfo['bk_int_function'] ?>" size="50"> 
                  </td>
                </tr>
                <tr >
                  <td bgcolor="#00CC99"height="5" colspan="2"></td>
                </tr>
                <td colspan="2" align="center"><?php if($act=="view") {?> <a href="javascript:history.back()"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a> <?php } else {?><input type="image" SRC="<?=$tmpl_dir?>/images/submit.jpg" alt="submit"> <?php } ?> 
                </td>
                </tr>
              </table>
			<input type="hidden" name="hdId" value="<?= $iBankId ?>">
			<?php
				if ( $iBankId == "" ) {
			?>	
				<input type="hidden" name="hdAction" value="add">	
			<?php
				} else {
			?>	
			<input type="hidden" name="hdAction" value="edit">					
<?php			}
			?>	
		</form>
		<br><br>
		</td>
	</tr>
		<tr>
		  <td width="10"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		  <td colspan="3" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		  <td width="10" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
		</tr>
	</table>
    </td>
    </tr>
</table>
<?php 
include("includes/footer.php");

?>