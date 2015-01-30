<?php
include("includes/sessioncheck.php");

require_once("../includes/function.php");
$headerInclude = "customerservice";
include("includes/header.php");
include("includes/message.php");
	$iCount = (isset($HTTP_POST_VARS["hid_count"])?Trim($HTTP_POST_VARS["hid_count"]):"");
	$strCurrentDateTime = func_get_current_date_time();
	//$str_from_id = "unknown@unknown.com";
	//$str_to_id = "";
	//$str_mail_subject = "Cancellation Of Transaction";
	//$str_mail_body = "Please Cancel the Following Transaction";
	for($iLoop = 0;$iLoop<$iCount;$iLoop++)
	{
		$iTransactionId = (isset($HTTP_POST_VARS["hid_trans_id$iLoop"])?Trim($HTTP_POST_VARS["hid_trans_id$iLoop"]):"");
		$iNoteId = (isset($HTTP_POST_VARS["hid_note_id$iLoop"])?Trim($HTTP_POST_VARS["hid_note_id$iLoop"]):"");
		$strCancel = (isset($HTTP_POST_VARS["cancel_status$iLoop"])?Trim($HTTP_POST_VARS["cancel_status$iLoop"]):"");
		$strPaymentType = (isset($HTTP_POST_VARS["payment_type$iLoop"])?Trim($HTTP_POST_VARS["payment_type$iLoop"]):"");
		$strBankRoutingCode = (isset($HTTP_POST_VARS["bank_routing_code$iLoop"])?Trim($HTTP_POST_VARS["bank_routing_code$iLoop"]):"");
		$strCancel = (isset($HTTP_POST_VARS["cancel_status$iLoop"])?Trim($HTTP_POST_VARS["cancel_status$iLoop"]):"");
		$iCompanyId = (isset($HTTP_POST_VARS["company_id$iLoop"])?Trim($HTTP_POST_VARS["company_id$iLoop"]):"");
		$qryUpdate = "";
		$qryUpdateCallNotes = "";
		//print("transID= $iTransactionId C/H= $strPaymentType Code= $strBankRoutingCode");
		if($iTransactionId != "")
		{

			if($strCancel != "")
			{
				if($strCancel == "accept")
				{
					$qryUpdate = "update cs_transactiondetails set passStatus='ND',cancelstatus='Y',reason='Customer Service',cancellationDate='$strCurrentDateTime',admin_approval_for_cancellation='A' where transactionId=$iTransactionId";
					if($strPaymentType == "C")
					{
						//if($strBankRoutingCode != "")
						//{
							func_send_cancel_mail($iCompanyId,$strPaymentType);
						//}
					}
				}
				else if($strCancel == "reject")
				{
					$qryUpdateCallNotes = "update cs_callnotes set cancel_status='0' where note_id=$iNoteId";
					$qryUpdate = "update cs_transactiondetails set admin_approval_for_cancellation='D' where transactionId=$iTransactionId";
				}
				if($qryUpdate != "")
				{
					//print($qryUpdate);
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
