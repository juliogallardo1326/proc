<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//include ("includes/sessioncheck.php");

$headerInclude="startHere";
include("includes/header.php");
include("includes/message.php"); 
if($resellerInfo['rd_completion']==1) func_update_single_field('cs_resellerdetails','rd_completion',2,NULL,'reseller_id',$resellerInfo['reseller_id'],$cnn_cs);
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";

if($resellerLogin!=""){
	$username = (isset($HTTP_POST_VARS['username'])?trim($HTTP_POST_VARS['username']):"");
	$currentBank = (isset($HTTP_POST_VARS['currentBank'])?trim($HTTP_POST_VARS['currentBank']):"");
	$bank_other = (isset($HTTP_POST_VARS['bank_other'])?trim($HTTP_POST_VARS['bank_other']):"");
	$beneficiary_name = (isset($HTTP_POST_VARS['beneficiary_name'])?trim($HTTP_POST_VARS['beneficiary_name']):"");
	$bank_account_name = (isset($HTTP_POST_VARS['bank_account_name'])?trim($HTTP_POST_VARS['bank_account_name']):"");
	$bank_address = (isset($HTTP_POST_VARS['bank_address'])?trim($HTTP_POST_VARS['bank_address']):"");
	$bank_country = (isset($HTTP_POST_VARS['bank_country'])?trim($HTTP_POST_VARS['bank_country']):"");
	$bank_phone = (isset($HTTP_POST_VARS['bank_phone'])?trim($HTTP_POST_VARS['bank_phone']):"");
	$bank_sort_code = (isset($HTTP_POST_VARS['bank_sort_code'])?trim($HTTP_POST_VARS['bank_sort_code']):"");
	$bank_account_number = (isset($HTTP_POST_VARS['bank_account_number'])?trim($HTTP_POST_VARS['bank_account_number']):"");
	$bank_swift_code = (isset($HTTP_POST_VARS['bank_swift_code'])?trim($HTTP_POST_VARS['bank_swift_code']):"");
	$bci_code = (isset($HTTP_POST_VARS['bci_code'])?trim($HTTP_POST_VARS['bci_code']):"");
	$vat_number = (isset($HTTP_POST_VARS['vat_number'])?trim($HTTP_POST_VARS['vat_number']):"");
	$company_number = (isset($HTTP_POST_VARS['company_number'])?trim($HTTP_POST_VARS['company_number']):"");
	$rd_bank_instructions = (isset($HTTP_POST_VARS['rd_bank_instructions'])?trim($HTTP_POST_VARS['rd_bank_instructions']):"");
	$rd_bank_routingcode = (isset($HTTP_POST_VARS['rd_bank_routingcode'])?trim($HTTP_POST_VARS['rd_bank_routingcode']):"");
	$rd_bank_routingnumber = (isset($HTTP_POST_VARS['rd_bank_routingnumber'])?trim($HTTP_POST_VARS['rd_bank_routingnumber']):"");
	
	$sql_update_qry = "update cs_resellerdetails set rd_bank_routingcode='$rd_bank_routingcode', rd_bank_instructions='$rd_bank_instructions', rd_bank_routingnumber='$rd_bank_routingnumber', reseller_bankname = '$currentBank', reseller_otherbank = '$bank_other', bank_address = '$bank_address', bank_country = '$bank_country', bank_telephone = '$bank_phone', bank_sortcode = '$bank_sort_code', bank_accountno = '$bank_account_number', bank_swiftcode = '$bank_swift_code',bank_benificiaryname='$beneficiary_name',bank_accountname='$bank_account_name', BICcode='$bci_code', VATnumber='$vat_number',registrationNo='$company_number', completed_reseller_application = 1 where reseller_id=$resellerLogin";
	if(!($run_update_qry =mysql_query($sql_update_qry,$cnn_cs))) {
		echo mysql_errno().": ".mysql_error()."<BR>";
		echo "Cannot execute query.";
		exit();
	}
	$msgtodisplay="Reseller application complete. Please click continue to the reseller contract";
?>

      <?php beginTable() ?>
		  <table width="500" border="0" cellpadding="0"  >
			<tr>
			<td align="center" valign="center" height="30" width="50%"  bgcolor="#F8FAFC"><font face="verdana" size="1">
			<?= $msgtodisplay ?>
			</td>
			</tr>
		  <tr>
	  	<td align="center" valign="center" height="30" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="../images/back.jpg"></a>&nbsp;&nbsp;&nbsp;<a href="resellerContract.php"><img border="0" src="../images/continue.gif"></a> 
		&nbsp;&nbsp;
		<br>
		</td>
	  	</tr>
		</table>
	<?php endTable("Reseller Bank","resellerContract.php") ?>

<?
include 'includes/footer.php';
}
?>