<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//

include('includes/dbconnection.php');

	$iShopNumber 		= (isset($HTTP_GET_VARS["SHOP_NUMBER"])?trim($HTTP_GET_VARS["SHOP_NUMBER"]):"");
	$return_url 		= (isset($HTTP_GET_VARS['mt_return_url'])?trim($HTTP_GET_VARS['mt_return_url']):"");
	$order_id 			= (isset($HTTP_GET_VARS['mt_order_id'])?trim($HTTP_GET_VARS['mt_order_id']):"");
	$voiceauth 			= (isset($HTTP_GET_VARS['mt_voiceauth_id'])?trim($HTTP_GET_VARS['mt_voiceauth_id']):"");
	$amount				= (isset($HTTP_GET_VARS['mt_total_amount'])?trim($HTTP_GET_VARS['mt_total_amount']):"");	
	$transaction_type	= (isset($HTTP_GET_VARS["TRANS_TYPE"])?$HTTP_GET_VARS["TRANS_TYPE"]:"");
	

	$selectBankUpdates = "Select * from cs_bardo where shop_number = $iShopNumber";
	if(!($run_Select_Qry = mysql_query($selectBankUpdates))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$str_status = "";
	$str_decline_reason = "";
	if (mysql_num_rows($run_Select_Qry) == 0) {
		$strMessage = "<center><br><img src='images/progressbar.gif'><br><h3>Please wait. Transaction in Progress....</center>";
	} else {
?><body>
		<form name="Frmname" action="bardo_Integratedresult.php" method="post">
		<input type="hidden" name="mt_transaction_type" value="<?=$transaction_type?>">
		<input type="hidden" name="mt_transaction_id" value="<?=$iShopNumber?>">
		<input type="hidden" name="mt_voiceauth_id" value="<?=$voiceauth?>">
		<input type="hidden" name="mt_order_id" value="<?=$order_id?>">
		<input type="hidden" name="mt_total_amount" value="<?=number_format($amount,2)?>">
		<input type="hidden" name="mt_return_url" value="<?=$return_url?>">
		</form>
		<script language="JavaScript">
		document.Frmname.submit();
		</script>
		</body>
<?php
	}
?>

		<title>Display result</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV=Refresh CONTENT="10; URL=display_IntegratedResult.php?SHOP_NUMBER=<?=$iShopNumber?>&TRANS_TYPE=<?=$transaction_type?>&mt_return_url=<?=$return_url?>&mt_order_id=<?=$order_id?>&mt_voiceauth_id=<?=$voiceauth?>&mt_total_amount=<?=$amount?>"> 
</head>
<body topmargin="0" leftmargin="0" bgcolor="#ffffff"  marginheight="0" marginwidth="0">
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="90%" >
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
	<tr>
	<td valign="middle" align="center" bgcolor="#ffffff" width="35%"><img src="images/spacer.gif" width="180" height="46" border="0" alt=""></td>
	<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap><img alt="" border="0" src="images/top1.jpg" width="238" height="63" ><img alt="" border="0" src="images/top2.jpg" width="217" height="63"><img alt="" border="0" src="images/top3.jpg" width="138" height="63"><br>
	<img alt="" border="0" src="images/top4.jpg" width="238" height="63"><img  alt="" border="0" src="images/top5.jpg" width="217" height="63"><img alt="" border="0" src="images/top6.jpg" width="138" height="63"></td>
	</tr>
	</table>
	<!-- Sub header-->
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="34" bgcolor="#FFFFFF">
	<tr>
		<td height="9" align="center" background="images/menutopbg.gif"></td>
	</tr>
	<tr>
		<td background="images/midbg.gif" align="center">&nbsp;</td>
	</tr>
	</table>
	<!-- sub header -->
	<!--submenu starts-->
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td height="5" background="images/menubtmbg.gif"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	</tr>
	<tr>
	<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" height="10">
	<tr>
	  <td width="100%" height="20" align="left">&nbsp;</td>
	</tr>
	</table>
	</td>
	</tr>
	</table><br><br>
	<table border="0" cellpadding="0" cellspacing="0" width="600" height="70%" class="disbd" align="center">
		<tr>
		  <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
		  <img border="0" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		 <tr>
		  <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		 <tr>
		  <td width="100%" valign="middle" align="left" height="35" class="disctxhd">&nbsp; Message</td>
		</tr>
		<tr>
		 <td width="100%" valign="top" align="center">
		 <table width="500" border="0" cellpadding="0"  height="150" >
		  <tr><td width="500" height="60" align="center" valign="center" bgcolor="#F7F9FB" class="ratebd"><span class="intx"><?=$strMessage?></span></td></tr>
		  </table>
		  </td>
		</tr>
        </table>
		</td>
	</tr>
	</table>
</body>
<?php 
include 'includes/footer.php';
?>
