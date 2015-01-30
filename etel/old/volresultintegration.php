<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//

include('includes/dbconnection.php');
require_once('includes/function.php');
include 'includes/function1.php';
include 'admin/includes/mailbody_replytemplate.php';
$data="";
	$strMessage ="";
		$i_company_id 		= (isset($HTTP_POST_VARS['COMPANY_ID'])?trim($HTTP_POST_VARS['COMPANY_ID']):"");
		$UserId 			= (isset($HTTP_POST_VARS['USER_ID'])?trim($HTTP_POST_VARS['USER_ID']):"");
		$UserPassword		= (isset($HTTP_POST_VARS['USER_PASSWORD'])?trim($HTTP_POST_VARS['USER_PASSWORD']):"");
		$TransNumber 		= (isset($HTTP_POST_VARS['TRAN_NUMBER'])?trim($HTTP_POST_VARS['TRAN_NUMBER']):"");
		$customerLastName 	= (isset($HTTP_POST_VARS['CUSTOMER_LAST_NAME'])?trim($HTTP_POST_VARS['CUSTOMER_LAST_NAME']):"");
		$customerFirstName 	= (isset($HTTP_POST_VARS['CUSTOMER_FIRST_NAME'])?trim($HTTP_POST_VARS['CUSTOMER_FIRST_NAME']):"");
		$customerEmail 		= (isset($HTTP_POST_VARS['CUSTOMER_EMAIL'])?trim($HTTP_POST_VARS['CUSTOMER_EMAIL']):"");
		$customerAddress 	= (isset($HTTP_POST_VARS['CUSTOMER_ADRESS'])?trim($HTTP_POST_VARS['CUSTOMER_ADRESS']):"");
		$customerCity 		= (isset($HTTP_POST_VARS['CUSTOMER_CITY'])?trim($HTTP_POST_VARS['CUSTOMER_CITY']):"");
		$customerZipCode 	= (isset($HTTP_POST_VARS['CUSTOMER_ZIP_CODE'])?trim($HTTP_POST_VARS['CUSTOMER_ZIP_CODE']):"");
		$customerState 		= (isset($HTTP_POST_VARS['CUSTOMER_STATE'])?trim($HTTP_POST_VARS['CUSTOMER_STATE']):"");
		$customerCountry 	= (isset($HTTP_POST_VARS['CUSTOMER_COUNTRY'])?trim($HTTP_POST_VARS['CUSTOMER_COUNTRY']):"");
		$customerPhone 		= (isset($HTTP_POST_VARS['CUSTOMER_PHONE'])?trim($HTTP_POST_VARS['CUSTOMER_PHONE']):"");
		$customerIp 		= (isset($HTTP_POST_VARS['CUSTOMER_IP'])?trim($HTTP_POST_VARS['CUSTOMER_IP']):"");
		$productName 		= (isset($HTTP_POST_VARS['PRODUCT_NAME'])?trim($HTTP_POST_VARS['PRODUCT_NAME']):"");
		$transactAmount 	= (isset($HTTP_POST_VARS['TRANSAC_AMOUNT'])?trim($HTTP_POST_VARS['TRANSAC_AMOUNT']):"");
		$currencyCode 		= (isset($HTTP_POST_VARS['CURRENCY_CODE'])?trim($HTTP_POST_VARS['CURRENCY_CODE']):"");
		$cardType 			= (isset($HTTP_POST_VARS['CB_TYPE'])?trim($HTTP_POST_VARS['CB_TYPE']):"");
		$cardNumber 		= (isset($HTTP_POST_VARS['CB_NUMBER'])?trim($HTTP_POST_VARS['CB_NUMBER']):"");
		$cardExpire 		= (isset($HTTP_POST_VARS['CB_EXPIRE'])?trim($HTTP_POST_VARS['CB_EXPIRE']):"");
		$cardCvcNumber 		= (isset($HTTP_POST_VARS['CB_CVC'])?trim($HTTP_POST_VARS['CB_CVC']):"");
		$transactionType 	= (isset($HTTP_POST_VARS['TRANS_TYPE'])?trim($HTTP_POST_VARS['TRANS_TYPE']):"");
		$order_id 			= (isset($HTTP_POST_VARS['mt_order_id'])?trim($HTTP_POST_VARS['mt_order_id']):"");
		$voiceauth 			= (isset($HTTP_POST_VARS['mt_voiceauth_id'])?trim($HTTP_POST_VARS['mt_voiceauth_id']):"");			
		$return_url 		= (isset($HTTP_POST_VARS['mt_return_url'])?trim($HTTP_POST_VARS['mt_return_url']):"");
		$ReferenceNo 		= (isset($HTTP_POST_VARS['REFERENCE_NUMBER'])?trim($HTTP_POST_VARS['REFERENCE_NUMBER']):"");
				
			$xml_string = "<epxml><header>
			<responsetype>direct</responsetype>
				<mid>$UserId</mid>
				<password>$UserPassword</password>
			<type>charge</type>
			</header>
			<request><charge>
				<etan>$ReferenceNo</etan>
			<card>
				<cctype>$cardType</cctype>
				<cc>$cardNumber</cc>
				<expire>$cardExpire</expire>
				<cvv>$cardCvcNumber</cvv>
			</card>
			<cardholder>
				<name>$customerFirstName</name>
				<surname>$customerLastName</surname>
				<street>$customerAddress</street>
				<housenumber>123</housenumber>
				<zip>$customerZipCode</zip>
				<city>$customerCity</city>
				<country>$customerCountry</country>
				<telephone>$customerPhone</telephone>
				<state>$customerState</state>
				<email>$customerEmail</email>
			</cardholder>
			<amount>
				<currency>$currencyCode</currency>
				<value>$transactAmount</value>
			</amount>
			</charge>
			</request></epxml>";
			 
			$is_existTransId = func_get_value_of_field($cnn_cs,"cs_volpay","trans_id","trans_id",$TransNumber);
			if($is_existTransId==""){ 
				$data= func_volpaybank_integration_result($xml_string);
			}else {
				$msgtodisplay= "<h3>The Order number already exist.</h3>Please process with a new transaction.";
			}
			
			
			$Nodes = array();
			$count = 0;
			$pos = 0;
			
			// Goes throw XML file and creates an array of all <XML_TAG> tags.
			while ($node = GetElementByName($data, "<epxml>", "</epxml>")) {
			   $Nodes[$count] = $node;
			   $count++;
			   $data = substr($data, $pos);
			}
			
			// Gets infomation from tag siblings.
			
			for ($i=0; $i<$count; $i++) {
					$headerCode = GetElementByName($Nodes[$i], "<header>", "</header>");
					$responseDesc = GetElementByName($Nodes[$i], "<response>", "</response>");
			
					$responseType = GetElementByName($Nodes[$i], "<responsetype>", "</responsetype>");
					$merchantId = GetElementByName($Nodes[$i], "<mid>", "</mid>");
					$merchantType = GetElementByName($Nodes[$i], "<type>", "</type>");
			
					if(strpos($responseDesc,"error")) {
						$returnCode = GetElementByName($Nodes[$i], "<errorcode>", "</errorcode>");
						$returnMessage = GetElementByName($Nodes[$i], "<errormessage>", "</errormessage>");
						$transNo = GetElementByName($Nodes[$i], "<etan>", "</etan>");
						$msgtodisplay ="<b>Sorry</b>, there was a mistake with your credit card details. Your Order Number $ReferenceNo has been declined. ";
						$msgtodisplay .= $returnMessage."-".$returnCode ;
						$transStatus ="Failure";
					}
					
					if(strpos($responseDesc,"success")) {
						$returnMessage = GetElementByName($Nodes[$i], "<message>", "</message>");
						$returnCode = GetElementByName($Nodes[$i], "<tan>", "</tan>");
						$transNo = GetElementByName($Nodes[$i], "<etan>", "</etan>");
						$msgtodisplay ="<h3>Thank-you for your order</h3>Your order number is $ReferenceNo.Your Order has been Approved and Please refer to this in any correspondence.";
						$msgtodisplay .= $returnMessage."-".$returnCode ;
						$transStatus ="Success";		
					}
						
						$qrt_insert_bankdetails = "insert into cs_volpay (trans_id,user_id,currency,amount,trans_status,return_code,return_message,reference_number) values($TransNumber,$i_company_id,'$currencyCode',$transactAmount,'$transStatus','$returnCode','$returnMessage','$ReferenceNo') "; 
						//print($qrt_insert_bankdetails); 
						if(!($show_sql =mysql_query($qrt_insert_bankdetails,$cnn_cs)))
						{
							print(mysql_errno().": ".mysql_error()."<BR>");
							print("Cannot execute queryin");
							exit();
						}
						$approval_status = $transStatus == "Success" ? "A" : "D";
						$decline_reason = $transStatus == "Success" ? "" : $returnCode." : ".$returnMessage;
						$pass_status = "";
						if ($transactionType != "tele") {
							$pass_status = "PA";
						}
						func_update_approval_status($cnn_cs, $TransNumber, $approval_status, $pass_status, $decline_reason);
						if ($transStatus == "Success") {
							func_send_transaction_success_mail($TransNumber);
							$strMessage = "SUC";
						} 
						else {
							$strMessage = "DEC";
					//		func_send_transaction_failure_mail($TransNumber, $returnMessage."-".$returnCode);
						}
			}
if($return_url=="") {
?>
<title>Payment</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="styles/style.css" type="text/css" rel="stylesheet">
<link href="styles/text.css" type="text/css" rel="stylesheet">
</head>
<body topmargin="0" leftmargin="0" bgcolor="#ffffff"  marginheight="0" marginwidth="0">
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
	</table>
	<!--submenu ends-->
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  
  <tr>
    <td width="100%" valign="top" align="center">&nbsp;
	<table border="0" cellpadding="0" cellspacing="0" width="600" height="70%" class="disbd">
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
} else {
?>
<body>
		<form name="Frmname" action="<?=$return_url?>" method="post">
		<input type="hidden" name="mt_transaction_type" value="<?=$transactionType?>">
		<input type="hidden" name="mt_transaction_id" value="<?=$TransNumber?>">
		<input type="hidden" name="mt_voiceauth_id" value="<?=$voiceauth?>">
		<input type="hidden" name="mt_order_id" value="<?=$order_id?>">
		<input type="hidden" name="mt_total_amount" value="<?=number_format($transactAmount,2)?>">
		<input type="hidden" name="mt_transaction_result" value="<?=$strMessage?>">
		<input type="hidden" name="mt_reference_number" value="<?=$reference_number?>">
		</form>
		<script language="JavaScript">
		document.Frmname.submit();
		</script>
		</body>
<?php 
}
?>
