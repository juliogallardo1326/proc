<?php
die();
include('includes/dbconnection.php');
require_once('includes/function.php');
include 'includes/function1.php';
include 'admin/includes/mailbody_replytemplate.php';

		$transactionType ="";
		$data ="";
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
		if($currencyCode=="") {$currencyCode= "USD"; }
				
			$xml_string = "<epxml><header>
			<responsetype>direct</responsetype>
				<mid>$UserId</mid>
				<password>$UserPassword</password>
			<type>charge</type>
			</header>
			<request><charge>
				<etan>$TransNumber</etan>
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

			if($is_existTransId =="") {
				$data	= func_volpaybank_integration_result($xml_string);
			}else {
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
						$strMessage="<b>Sorry</b>, there was a mistake with your credit card details. Your Order Number $TransNumber has been declined. ";
						$strMessage .= $returnMessage."-".$returnCode ;
						$transStatus ="Failure";
					}
					if(strpos($responseDesc,"success")) {
						$returnMessage = GetElementByName($Nodes[$i], "<message>", "</message>");
						$returnCode = GetElementByName($Nodes[$i], "<tan>", "</tan>");
						$transNo = GetElementByName($Nodes[$i], "<etan>", "</etan>");
						$strMessage="<h3>Thank-you for your order</h3>Your order number is $TransNumber.Your Order has been Approved and Please refer to this in any correspondence.";
						//$msgtodisplay .= $returnMessage."-".$returnCode ;
						$transStatus ="Success";		
					}
						$qry_select ="select reference_number from cs_transactiondetails where transactionId=$TransNumber";
						$select_sql =mysql_query($qry_select,$cnn_cs);
						$value=mysql_fetch_array($select_sql);
						$ref_num=$value[0];
						$qrt_insert_bankdetails = "insert into cs_volpay (trans_id,user_id,currency,amount,trans_status,return_code,return_message,reference_number) values('$TransNumber','$i_company_id','$currencyCode',$transactAmount,'$transStatus','$returnCode','$returnMessage','$ref_num') "; 
						if(!($show_sql =mysql_query($qrt_insert_bankdetails,$cnn_cs)))
						{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

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
						} 

			}
			exit();		
?>