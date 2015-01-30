<?php 
include("includes/sessioncheck.php");


$sBankName	=	(isset($HTTP_POST_VARS["txtBankName"])?quote_smart($HTTP_POST_VARS["txtBankName"]):"");
$sBankMail	=	(isset($HTTP_POST_VARS["txtBankEmail"])?quote_smart($HTTP_POST_VARS["txtBankEmail"]):"");
$sAction	=	(isset($HTTP_POST_VARS["hdAction"])?quote_smart($HTTP_POST_VARS["hdAction"]):"");
$iBankId	=	(isset($HTTP_POST_VARS["hdId"])?quote_smart($HTTP_POST_VARS["hdId"]):"");
$i_paybackday=	(isset($HTTP_POST_VARS["txt_payback"])?quote_smart($HTTP_POST_VARS["txt_payback"]):"");
$i_payweekfrom=	(isset($HTTP_POST_VARS["cbo_from_week"])?quote_smart($HTTP_POST_VARS["cbo_from_week"]):"");
$i_payweekto=	(isset($HTTP_POST_VARS["cbo_to_week"])?quote_smart($HTTP_POST_VARS["cbo_to_week"]):"");
$i_payday=		(isset($HTTP_POST_VARS["cbo_payday"])?quote_smart($HTTP_POST_VARS["cbo_payday"]):"");
$disc_rate=	(isset($HTTP_POST_VARS["disc_rate"])?quote_smart($HTTP_POST_VARS["disc_rate"]):"");
$trans_fee=	(isset($HTTP_POST_VARS["trans_fee"])?quote_smart($HTTP_POST_VARS["trans_fee"]):"");
$roll_res=		(isset($HTTP_POST_VARS["roll_res"])?quote_smart($HTTP_POST_VARS["roll_res"]):"");
$chrgbk_fee=		(isset($HTTP_POST_VARS["chrgbk_fee"])?quote_smart($HTTP_POST_VARS["chrgbk_fee"]):"");
$reserve=		(isset($HTTP_POST_VARS["reserve"])?quote_smart($HTTP_POST_VARS["reserve"]):"");
$bk_username=		(isset($HTTP_POST_VARS["bk_username"])?quote_smart($HTTP_POST_VARS["bk_username"]):"");
$bk_password=		(isset($HTTP_POST_VARS["bk_password"])?quote_smart($HTTP_POST_VARS["bk_password"]):"");
$bk_additional_id=		(isset($HTTP_POST_VARS["bk_additional_id"])?quote_smart($HTTP_POST_VARS["bk_additional_id"]):"");
$bk_cc_bank_enabled =		(isset($HTTP_POST_VARS["bk_cc_bank_enabled"])?quote_smart($HTTP_POST_VARS["bk_cc_bank_enabled"]):"");


if ( $sAction == "add" ) { 
	$qryInsert = "insert into cs_bank (bk_cc_bank_enabled,bank_name,bank_email,bank_paybackday,bank_payweekfrom,bank_payweekto,bank_payday,discountrate,transactionfee,rollingreserve,chargebackfee,bk_username,bk_password,bk_additional_id) values ('$bk_cc_bank_enabled','$sBankName','$sBankMail','$i_paybackday','$i_payweekfrom','$i_payweekto','$i_payday','$disc_rate','$trans_fee','$roll_res','$chrgbk_fee','$bk_username','$bk_password','$bk_additional_id')";
	if ( !(mysql_query($qryInsert,$cnn_cs))) {
		print("Can not execute query");
	}
	else
	{
		header("location:company_banklist.php?msg=add&id=$iBankId");
	}
}
if ( $sAction == "edit" ) {
	$qryUpdate = "update cs_bank set bk_cc_bank_enabled='$bk_cc_bank_enabled', bank_name = '$sBankName',bank_email = '$sBankMail',bank_paybackday='$i_paybackday',bank_payweekfrom='$i_payweekfrom',bank_payweekto='$i_payweekto',bank_payday='$i_payday',discountrate ='$disc_rate',transactionfee ='$trans_fee' ,rollingreserve  ='$roll_res' ,chargebackfee  ='$chrgbk_fee', bk_username = '$bk_username', bk_password = '$bk_password', bk_additional_id = '$bk_additional_id'  where bank_id = $iBankId";
	if ( !(mysql_query($qryUpdate,$cnn_cs))) {
		print("Can not execute query");
	}
	else
	{
		header("location:company_banklist.php?msg=edit&id=$iBankId");
	}
}

?>
