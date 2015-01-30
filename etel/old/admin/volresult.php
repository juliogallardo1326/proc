<?php 
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
//volresults.php:	The page functions for entering the creditcard details.
include 'includes/sessioncheck.php';
include('../includes/dbconnection.php');
$headerInclude="transactions";	
include 'includes/header.php';
require_once( '../includes/function.php');
include '../includes/function1.php';
include 'includes/mailbody_replytemplate.php';

$data ="";
$strMessage ="";
		$i_company_id 		= (isset($HTTP_POST_VARS['COMPANY_ID'])?quote_smart($HTTP_POST_VARS['COMPANY_ID']):"");
		$UserId 			= (isset($HTTP_POST_VARS['USER_ID'])?quote_smart($HTTP_POST_VARS['USER_ID']):"");
		$UserPassword		= (isset($HTTP_POST_VARS['USER_PASSWORD'])?quote_smart($HTTP_POST_VARS['USER_PASSWORD']):"");
		$TransNumber 		= (isset($HTTP_POST_VARS['TRAN_NUMBER'])?quote_smart($HTTP_POST_VARS['TRAN_NUMBER']):"");
		$customerLastName 	= (isset($HTTP_POST_VARS['CUSTOMER_LAST_NAME'])?quote_smart($HTTP_POST_VARS['CUSTOMER_LAST_NAME']):"");
		$customerFirstName 	= (isset($HTTP_POST_VARS['CUSTOMER_FIRST_NAME'])?quote_smart($HTTP_POST_VARS['CUSTOMER_FIRST_NAME']):"");
		$customerEmail 		= (isset($HTTP_POST_VARS['CUSTOMER_EMAIL'])?quote_smart($HTTP_POST_VARS['CUSTOMER_EMAIL']):"");
		$customerAddress 	= (isset($HTTP_POST_VARS['CUSTOMER_ADRESS'])?quote_smart($HTTP_POST_VARS['CUSTOMER_ADRESS']):"");
		$customerCity 		= (isset($HTTP_POST_VARS['CUSTOMER_CITY'])?quote_smart($HTTP_POST_VARS['CUSTOMER_CITY']):"");
		$customerZipCode 	= (isset($HTTP_POST_VARS['CUSTOMER_ZIP_CODE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_ZIP_CODE']):"");
		$customerState 		= (isset($HTTP_POST_VARS['CUSTOMER_STATE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_STATE']):"");
		$customerCountry 	= (isset($HTTP_POST_VARS['CUSTOMER_COUNTRY'])?quote_smart($HTTP_POST_VARS['CUSTOMER_COUNTRY']):"");
		$customerPhone 		= (isset($HTTP_POST_VARS['CUSTOMER_PHONE'])?quote_smart($HTTP_POST_VARS['CUSTOMER_PHONE']):"");
		$customerIp 		= (isset($HTTP_POST_VARS['CUSTOMER_IP'])?quote_smart($HTTP_POST_VARS['CUSTOMER_IP']):"");
		$productName 		= (isset($HTTP_POST_VARS['PRODUCT_NAME'])?quote_smart($HTTP_POST_VARS['PRODUCT_NAME']):"");
		$transactAmount 	= (isset($HTTP_POST_VARS['TRANSAC_AMOUNT'])?quote_smart($HTTP_POST_VARS['TRANSAC_AMOUNT']):"");
		$currencyCode 		= (isset($HTTP_POST_VARS['CURRENCY_CODE'])?quote_smart($HTTP_POST_VARS['CURRENCY_CODE']):"");
		$cardType 			= (isset($HTTP_POST_VARS['CB_TYPE'])?quote_smart($HTTP_POST_VARS['CB_TYPE']):"");
		$cardNumber 		= (isset($HTTP_POST_VARS['CB_NUMBER'])?quote_smart($HTTP_POST_VARS['CB_NUMBER']):"");
		$cardExpire 		= (isset($HTTP_POST_VARS['CB_EXPIRE'])?quote_smart($HTTP_POST_VARS['CB_EXPIRE']):"");
		$cardCvcNumber 		= (isset($HTTP_POST_VARS['CB_CVC'])?quote_smart($HTTP_POST_VARS['CB_CVC']):"");
		$transactionType 	= (isset($HTTP_POST_VARS['TRANS_TYPE'])?quote_smart($HTTP_POST_VARS['TRANS_TYPE']):"");
		$ReferenceNo 		= (isset($HTTP_POST_VARS['REFERENCE_NUMBER'])?quote_smart($HTTP_POST_VARS['REFERENCE_NUMBER']):"");
			
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
			}else
				{
					$strMessage= "<h3>The Order number already exist.</h3>Please process with a new transaction.";
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
						$strMessage="<b>Sorry</b>, there was a mistake with your credit card details. Your Order Number $ReferenceNo has been declined. ";
						$strMessage .= $returnMessage."-".$returnCode ;
						$transStatus ="Failure";
					}
					
					if(strpos($responseDesc,"success")) {
						$returnMessage = GetElementByName($Nodes[$i], "<message>", "</message>");
						$returnCode = GetElementByName($Nodes[$i], "<tan>", "</tan>");
						$transNo = GetElementByName($Nodes[$i], "<etan>", "</etan>");
						$strMessage="<h3>Thank-you for your order</h3>Your order number is $ReferenceNo.Your Order has been Approved and Please refer to this in any correspondence.";
						//$msgtodisplay .= $returnMessage."-".$returnCode ;
						$transStatus ="Success";		
					}
						
						$qrt_insert_bankdetails = "insert into cs_volpay (trans_id,user_id,currency,amount,trans_status,return_code,return_message,reference_number) values($TransNumber,$i_company_id,'$currencyCode',$transactAmount,'$transStatus','$returnCode','$returnMessage','$ReferenceNo') "; 
					//	print($qrt_insert_bankdetails); 
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
						} else {
					//		func_send_transaction_failure_mail($TransNumber, $returnMessage."-".$returnCode);
						}
			}

?>
<title>Volpay display result</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
	<table border="0" cellpadding="0" cellspacing="0" width="600" height="70%" class="disbd">
		<tr>
		  <td width="100%" valign="top" align="center" bgcolor="#999999" height="20">
		  <img border="0" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
		</tr>
		 <tr>
		  <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
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
