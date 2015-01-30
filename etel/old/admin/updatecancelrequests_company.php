<?php
include("includes/sessioncheck.php");

include("../includes/function2.php");

$headerInclude = "transactions";
include("includes/header.php");

include("includes/message.php");
	$iCount = (isset($HTTP_POST_VARS["hid_count"])?quote_smart($HTTP_POST_VARS["hid_count"]):"");
		$strCurrentDateTime = func_get_current_date_time();
	//$str_from_id = "unknown@unknown.com";
	//$str_to_id = "";
	//$str_mail_subject = "Cancellation Of Transaction";
	//$str_mail_body = "Please Cancel the Following Transaction";
	for($iLoop = 0;$iLoop<$iCount;$iLoop++)
	{
		$iTransactionId = (isset($HTTP_POST_VARS["hid_trans_id$iLoop"])?quote_smart($HTTP_POST_VARS["hid_trans_id$iLoop"]):"");
		$strCancel = (isset($HTTP_POST_VARS["cancel_status$iLoop"])?quote_smart($HTTP_POST_VARS["cancel_status$iLoop"]):"");
		$strPaymentType = (isset($HTTP_POST_VARS["payment_type$iLoop"])?quote_smart($HTTP_POST_VARS["payment_type$iLoop"]):"");
		$iCompanyId = (isset($HTTP_POST_VARS["company_id$iLoop"])?quote_smart($HTTP_POST_VARS["company_id$iLoop"]):"");
		$i_bnk_creditcard = (isset($HTTP_POST_VARS["i_bnk_creditcard$iLoop"])?quote_smart($HTTP_POST_VARS["i_bnk_creditcard$iLoop"]):"");
		$i_bnk_chek = (isset($HTTP_POST_VARS["i_bnk_chek$iLoop"])?quote_smart($HTTP_POST_VARS["i_bnk_chek$iLoop"]):"");
		$qryUpdate = "";
		//print("transID= $iTransactionId C/H= $strPaymentType Code= $strBankRoutingCode");
		if($iTransactionId != "")
		{
			if($strCancel != "")
			{
				if($strCancel == "accept")
				{
					//$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
					//$qryUpdateold = "update cs_transactiondetails set cancelstatus='N' where transactionId=$iTransactionId";
					//mysql_query($qryUpdateold,$cnn_cs);
					$qryUpdate = "update cs_transactiondetails set passStatus='ND',cancelstatus='Y',transactionDate='$strCurrentDateTime',admin_approval_for_cancellation='A' where transactionId=$iTransactionId";
					
					$toemail=func_get_value_of_field($cnn_cs,"cs_transactiondetails","email","transactionId",$iTransactionId);
					$refer_num=func_get_value_of_field($cnn_cs,"cs_transactiondetails","reference_number","transactionId",$iTransactionId);
					
					$Custname=func_get_value_of_field($cnn_cs,"cs_transactiondetails","name","transactionId",$iTransactionId);
					$str_from=$_SESSION[gw_emails_sales];
					$str_subject="Transaction cancelled";
					
					$strmessage ="Dear $Custname\r\n";
					$strmessage.="Your transaction having reference Number $refer_num has been cancelled.\r\n";
					$strmessage.="Sincerely,\r\n\r\n";
					$strmessage .= $_SESSION['gw_title'];
					func_send_mail($str_from,$toemail,$str_subject,$strmessage);
					
					
					$oldtrans_id=func_get_value_of_field($cnn_cs,"cs_transactiondetails","cancel_refer_num","transactionId",$iTransactionId);
					$refer_num=$oldtrans_id;
					$len=strlen($oldtrans_id);
					$oldtrans_id= substr($oldtrans_id,4,$len-6);
					
					if( $i_bnk_creditcard==6||$i_bnk_creditcard==7 ||$i_bnk_creditcard==8) {
					$toemail=func_get_value_of_field($cnn_cs,"cs_bank","bank_email","bank_id",$i_bnk_creditcard);
					$str_subject="Cancellation of Transaction";
					func_get_value_of_field($cnn_cs,"cs_volpay","return_code","trans_id",$oldtrans_id);
					$strmessage="Please cancel the transaction having Identification Number: $refer_num .\r\n";
					$strmessage.="Sincerely \r\n";
					$strmessage.="$_SESSION[gw_emails_sales] \r\n";
					$strmessage.="$_SESSION[gw_title] \r\n";
					$str_from="$_SESSION[gw_emails_sales]";
						func_send_mail($str_from,$toemail,$str_subject,$strmessage);
					}
					else if( $i_bnk_creditcard==3) {  
					$toemail=func_get_value_of_field($cnn_cs,"cs_bank","bank_email","bank_id",$i_bnk_creditcard);
					$refer_num = func_get_value_of_field($cnn_cs,"cs_bardo","bardo_number","shop_number",$oldtrans_id); 
					$str_subject="Cancellation of Transaction";
				 	$strmessage="Please cancel the transaction having Identification Number: $refer_num .\r\n";
					$strmessage.="Sincerely \r\n";
					$strmessage.="$_SESSION[gw_emails_sales] \r\n";
					$strmessage.="$_SESSION[gw_title] \r\n";
					$str_from="$_SESSION[gw_emails_sales]";
						func_send_mail($str_from,$toemail,$str_subject,$strmessage);
						}
						else if( $i_bnk_creditcard==9||$i_bnk_creditcard==10) {  
					$toemail=func_get_value_of_field($cnn_cs,"cs_bank","bank_email","bank_id",$i_bnk_creditcard);
					$refer_num=func_get_value_of_field($cnn_cs,"cs_scanorder","ScanOrderId","transactionId",$oldtrans_id);
					$str_subject="Cancellation of Transaction";
					$strmessage="Please cancel the transaction having Identification Number: $refer_num .\r\n";
					$strmessage.="Sincerely \r\n";
					$strmessage.="$_SESSION[gw_emails_sales] \r\n";
					$strmessage.="$_SESSION[gw_title] \r\n";
					$str_from="$_SESSION[gw_emails_sales]";
						func_send_mail($str_from,$toemail,$str_subject,$strmessage);
						}		
					
					    
					//exit();
				//	print($qryUpdate."<br>");
					if(!mysql_query($qryUpdate,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel update query");
						exit();
					}
				 	$qryUpdate = "update cs_transactiondetails set reason='Customer Cancel', other=null,admin_approval_for_cancellation='A' where transactionId=$iTransactionId";
					if(!mysql_query($qryUpdate,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel update query");
						exit();
					}
					if($strPaymentType == "C")
					{
						//	func_send_cancel_mail($iCompanyId,$strPaymentType);
					}
					//func_canceledTransaction_receipt($iCompanyId, $return_insertId,$cnn_cs);
				} else if($strCancel == "reject"){
					
					$ref_oldnum=func_get_value_of_field($cnn_cs,"cs_transactiondetails","cancel_refer_num ","transactionId",$iTransactionId);
					$qry_update=	"update cs_transactiondetails set cancel_count=0 where reference_number='$ref_oldnum'";
					mysql_query($qry_update,$cnn_cs);
					$qry_reject = "update cs_transactiondetails  set transactionDate='$strCurrentDateTime', admin_approval_for_cancellation ='R' where transactionId=$iTransactionId";
					if(!mysql_query($qry_reject,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel update query");
						exit();
					}
				}
			}
		}	
	}
	$headerInclude = "transactions";
	$msgtodisplay="Cancel Status Updated Successfully.";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	   
?>
