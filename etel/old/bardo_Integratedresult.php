<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//

include('includes/dbconnection.php');
require_once('includes/function.php');
include('includes/function1.php');
	$iShopNumber 		= (isset($HTTP_POST_VARS["mt_transaction_id"])?trim($HTTP_POST_VARS["mt_transaction_id"]):"");
	$return_url 		= (isset($HTTP_POST_VARS['mt_return_url'])?trim($HTTP_POST_VARS['mt_return_url']):"");
	$order_id 			= (isset($HTTP_POST_VARS['mt_order_id'])?trim($HTTP_POST_VARS['mt_order_id']):"");
	$voiceauth 			= (isset($HTTP_POST_VARS['mt_voiceauth_id'])?trim($HTTP_POST_VARS['mt_voiceauth_id']):"");
	$amount				= (isset($HTTP_POST_VARS['mt_total_amount'])?trim($HTTP_POST_VARS['mt_total_amount']):"");	
	$transaction_type	= (isset($HTTP_POST_VARS["mt_transaction_type"])?$HTTP_POST_VARS["mt_transaction_type"]:"");
	
	$selectBankUpdates = "Select * from cs_bardo where shop_number = $iShopNumber";
	if(!($run_Select_Qry = mysql_query($selectBankUpdates))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$str_status = "";
	$str_decline_reason = "";
	if (mysql_num_rows($run_Select_Qry) != 0) {
		$str_status = mysql_result($run_Select_Qry, 0, 7);
		$str_decline_reason = $str_status == "S" ? "" : mysql_result($run_Select_Qry, 0, 4);
	}
	$approval_status = $str_status == "S" ? "A" : "D";
	$pass_status = "";
	if ($transaction_type != "tele") {
		$pass_status = "PA";
	}
	func_update_approval_status($cnn_cs, $iShopNumber, $approval_status, $pass_status, $str_decline_reason);

	if($str_status == "S" ) {
	$strMessage = "SUC";
	//	$strMessage = "<center><br><br><h3>Thank-you for your order</h3>Your order number is $iShopNumber. Please refer to this in any correspondence.</center>";
	}else{
	$strMessage = "DEC";
	//	$strMessage = "<p style='margin-left:40;margin-right:40'><b>Sorry</b>, there was a mistake with your credit card details. Your Order Number $iShopNumber has been declined - ".$str_decline_reason.".</p>";
	}
?>
		<body>
		<form name="Frmname" action="<?=$return_url?>" method="post">
		<input type="hidden" name="mt_transaction_result" value="<?=$strMessage?>">
		<input type="hidden" name="mt_transaction_desc" value="<?=$str_decline_reason?>">
		<input type="hidden" name="mt_transaction_id" value="<?=$iShopNumber?>">
		<input type="hidden" name="mt_voiceauth_id" value="<?=$voiceauth?>">
		<input type="hidden" name="mt_order_id" value="<?=$order_id?>">
		<input type="hidden" name="mt_total_amount" value="<?=number_format($amount,2)?>">
		</form>
		<script language="JavaScript">
		document.Frmname.submit();
		</script>
		</body>

