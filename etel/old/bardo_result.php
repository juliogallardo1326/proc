<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//

include 'includes/sessioncheckuser.php';
include 'includes/header.php';
include('includes/dbconnection.php');
require_once('includes/function.php');
include('includes/function1.php');
$headerInclude="home";	
include 'includes/topheader.php';

	$iShopNumber		= $HTTP_GET_VARS["SHOP_NUMBER"];
	$transaction_type	= (isset($HTTP_GET_VARS["TRANS_TYPE"])?$HTTP_GET_VARS["TRANS_TYPE"]:"");

	$selectBankUpdates = "Select * from cs_bardo where shop_number = $iShopNumber";
	if(!($run_Select_Qry = mysql_query($selectBankUpdates))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$str_status = "";
	$str_decline_reason = "";
	//print($selectBankUpdates);
	if (mysql_num_rows($run_Select_Qry) != 0) {
		$str_status = mysql_result($run_Select_Qry, 0, 7);
		$str_decline_reason = $str_status == "S" ? "" : mysql_result($run_Select_Qry, 0, 4);
	}
	$approval_status = $str_status == "S" ? "A" : "D";
	$pass_status = "";
	if ($transaction_type != "tele") {
		$pass_status = "PA";
	}
	
	$referenceNumber = func_get_value_of_field($cnn_cs,"cs_transactiondetails","reference_number","transactionId",$iShopNumber );

	
	if($str_status == "S" ) {
		$strMessage = "<center><br><br><h3>Thank-you for your order</h3>Your order number is $referenceNumber. Please refer to this in any correspondence.</center>";
	}else{
		$strMessage = "<p style='margin-left:40;margin-right:40'><b>Sorry</b>, there was a mistake with your credit card details. Your Order Number $referenceNumber has been declined - ".$str_decline_reason.".</p>";
	}
?>
<title>Bardo display result</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
	<table border="0" cellpadding="0" cellspacing="0" width="600" class="disbd">
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
		 <table width="500" border="0" cellpadding="0"  >
		  <tr><td width="500" height="60" align="center" valign="center" bgcolor="#F7F9FB" class="ratebd"><span class="intx"><?=$strMessage?></span></td></tr>
		  <tr><td align="center" valign="middle" height="30">&nbsp;<!--<a href="creditcard.php"><img border="0" src="images/back.gif"></a>--></td></tr>
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
