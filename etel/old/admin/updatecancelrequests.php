<?php
include("includes/sessioncheck.php");

include("../includes/function2.php");

$headerInclude = "customerservice";
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
		$iNoteId = (isset($HTTP_POST_VARS["hid_note_id$iLoop"])?quote_smart($HTTP_POST_VARS["hid_note_id$iLoop"]):"");
		$strCancel = (isset($HTTP_POST_VARS["cancel_status$iLoop"])?quote_smart($HTTP_POST_VARS["cancel_status$iLoop"]):"");
		$strPaymentType = (isset($HTTP_POST_VARS["payment_type$iLoop"])?quote_smart($HTTP_POST_VARS["payment_type$iLoop"]):"");
		$strBankRoutingCode = (isset($HTTP_POST_VARS["bank_routing_code$iLoop"])?quote_smart($HTTP_POST_VARS["bank_routing_code$iLoop"]):"");
		$iCompanyId = (isset($HTTP_POST_VARS["company_id$iLoop"])?quote_smart($HTTP_POST_VARS["company_id$iLoop"]):"");
		$qryUpdate = "";
		$qryUpdateCallNotes = "";
		//print("transID= $iTransactionId C/H= $strPaymentType Code= $strBankRoutingCode");
		if($iTransactionId != "")
		{

			if($strCancel != "")
			{
				if($strCancel == "accept")
				{
					$return_insertId = func_transaction_updatenew($iTransactionId,$cnn_cs);
					$qryUpdate = "update cs_transactiondetails set passStatus='ND',cancelstatus='Y',reason='Customer Service',cancellationDate='$strCurrentDateTime',admin_approval_for_cancellation='A' where transactionId=$return_insertId";
					if(!mysql_query($qryUpdate,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel update query");
						exit();
					}
					$qryUpdate = "update cs_transactiondetails set reason=null, other=null,cancellationDate=null,admin_approval_for_cancellation='N' where transactionId=$iTransactionId";
					if(!mysql_query($qryUpdate,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel return query");
						exit();
					}
					if($strPaymentType == "C")
					{
						//if($strBankRoutingCode != "")
						//{
							func_send_cancel_mail($iCompanyId,$strPaymentType);
						//}
					}
					func_canceledTransaction_receipt($iCompanyId, $return_insertId,$cnn_cs);
				}
				else if($strCancel == "reject")
				{
					$qryUpdateCallNotes = "update cs_callnotes set cancel_status='0' where note_id=$iNoteId";
					$qryUpdate = "update cs_transactiondetails set admin_approval_for_cancellation='D' where transactionId=$iTransactionId";
					if(!mysql_query($qryUpdate,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel update query");
						exit();
					}
					$qryUpdate = "update cs_transactiondetails set reason=null, other=null,cancellationDate=null,admin_approval_for_cancellation='N' where transactionId=$iTransactionId";
					if(!mysql_query($qryUpdate,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel update query");
						exit();
					}
				}
				if($qryUpdateCallNotes != "")
				{
					//print($qryUpdateCallNotes);
					if(!mysql_query($qryUpdateCallNotes,$cnn_cs))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Can not execute query cancel update query for call notes");
						exit();
					}
				}
			}
		}	
	}
	$headerInclude = "customerservice";
	$msgtodisplay="Cancel Status Updated Successfully.";
	$outhtml="y";				
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();	   


?>
