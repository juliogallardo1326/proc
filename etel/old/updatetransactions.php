<?php
	
	require_once("../includes/function.php");
	$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?Trim($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
	$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?Trim($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
	$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?Trim($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
	$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?Trim($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
	$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?Trim($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
	$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?Trim($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
	$crorcq = (isset($HTTP_POST_VARS["crorcq"])?Trim($HTTP_POST_VARS["crorcq"]):"");
	$hid_companies = (isset($HTTP_POST_VARS["hid_companies"])?Trim($HTTP_POST_VARS["hid_companies"]):"");
	$trans_ptype = (isset($HTTP_POST_VARS["trans_ptype"])?Trim($HTTP_POST_VARS["trans_ptype"]):"");
	$trans_ctype = (isset($HTTP_POST_VARS["trans_ctype"])?Trim($HTTP_POST_VARS["trans_ctype"]):"");
	$trans_dtype = (isset($HTTP_POST_VARS["trans_dtype"])?Trim($HTTP_POST_VARS["trans_dtype"]):"");
	$voiceid = (isset($HTTP_POST_VARS["voiceid"])?Trim($HTTP_POST_VARS["voiceid"]):"");
	$transactionId = (isset($HTTP_POST_VARS["transactionId"])?Trim($HTTP_POST_VARS["transactionId"]):"");
	$cnumber = (isset($HTTP_POST_VARS["cnumber"])?Trim($HTTP_POST_VARS["cnumber"]):"");
	$radRange = (isset($HTTP_POST_VARS["radRange"])?Trim($HTTP_POST_VARS["radRange"]):"");
	$iCount = (isset($HTTP_POST_VARS["hdCount"])?Trim($HTTP_POST_VARS["hdCount"]):"");
	$strCurrentDateTime = func_get_current_date();
	

	for($iLoop = 0;$iLoop<$iCount;$iLoop++){
		$iTransactionId = (isset($HTTP_POST_VARS["hdId$iLoop"])?Trim($HTTP_POST_VARS["hdId$iLoop"]):"");
		$strPassStatus = (isset($HTTP_POST_VARS["radpass$iLoop"])?Trim($HTTP_POST_VARS["radpass$iLoop"]):"");
		$strStatus = (isset($HTTP_POST_VARS["radpending$iLoop"])?Trim($HTTP_POST_VARS["radpending$iLoop"]):"");
		$strCancel = (isset($HTTP_POST_VARS["chk$iLoop"])?Trim($HTTP_POST_VARS["chk$iLoop"]):"");
		$strCancelReason = (isset($HTTP_POST_VARS["optReason$iLoop"])?Trim($HTTP_POST_VARS["optReason$iLoop"]):"");
		$strOtherReason = (isset($HTTP_POST_VARS["txt$iLoop"])?Trim($HTTP_POST_VARS["txt$iLoop"]):"");
		$strNoPassReason = (isset($HTTP_POST_VARS["txtarea$iLoop"])?Trim($HTTP_POST_VARS["txtarea$iLoop"]):"");
		$qryUpdate = "";
		if($iTransactionId != ""){
			$qry_select_status = "Select passStatus,status,cancelstatus,pass_count,approval_count from cs_transactiondetails where transactionId=$iTransactionId";
			
			if(!($rst_select_status = mysql_query($qry_select_status,$cnn_cs)){
				print("Can not execute query");
				exit();
			}	
			if (mysql_num_rows($rst_select_status)>0){
				$db_passstatus = mysql_result($rst_select_status,0,0);
				$db_status = mysql_result($rst_select_status,0,1);
				$db_cancelstatus = mysql_result($rst_select_status,0,2);
				$db_passcount = mysql_result($rst_select_status,0,3);
				$db_approvalcount = mysql_result($rst_select_status,0,4);
			}
			if($strPassStatus != "" && $strPassStatus != $db_passstatus)
			{
				if($strPassStatus == "NP")
				{
					$qryUpdate = "update cs_transactiondetails set nopasscomments = '$strNoPassReason' where transactionId=$iTransactionId";
				}
				else
				{
					if($db_passstatus == "NP")
					{
						$qryUpdate = "update cs_transactiondetails set nopasscomments = '' where transactionId=$iTransactionId";
					}
				}
			}
			else
			{
				if($strPassStatus != "")
				{
					if($strPassStatus == "NP")
					{
						$qryUpdate = "update cs_transactiondetails set nopasscomments = '$strNoPassReason' where transactionId=$iTransactionId";
					}
				}
			}
			if($qryUpdate != "")
			{
				//print($qryUpdate);
				if(!mysql_query($qryUpdate,$cnn_cs)){
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query pass update query".$qryUpdate);
					exit();
				}
			}

			if($strPassStatus != "" && $strPassStatus != $db_passstatus){
				if ($strPassStatus == "PA"){
					$qryUpdate = "update cs_transactiondetails set passStatus  = '$strPassStatus' where transactionId=$iTransactionId";
				}
				else{
					$passcount = $db_passcount + 1;
					if ($strPassStatus == "NP" && $db_passcount >= 1){
						$qryUpdate = "update cs_transactiondetails set passStatus  = 'ND',pass_count=".$passcount." where transactionId=$iTransactionId";
					}
					else{
						$qryUpdate = "update cs_transactiondetails set passStatus  = '$strPassStatus',pass_count=".$passcount." where transactionId=$iTransactionId";
					}
				}
				if(!mysql_query($qryUpdate,$cnn_cs)){
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query pass update query");
					exit();
				}		
				//print("query= ".$qryUpdate."<br>");
			}
			if($strStatus != "" && $strStatus != $db_status){
				if($strStatus == "A"){
					$qryUpdate = "update cs_transactiondetails set status  = '$strStatus',approvaldate='$strCurrentDateTime' where transactionId=$iTransactionId";
				}else{
					$approvalcount = $db_approvalcount + 1;
					if ($strStatus == "D" && $db_approvalcount >= 1){
						$qryUpdate = "update cs_transactiondetails set status  = '$strStatus', passStatus  = 'ND',approval_count=".$approvalcount." where transactionId=$iTransactionId";
					}
					else{
						$qryUpdate = "update cs_transactiondetails set status  = '$strStatus', approval_count=".$approvalcount." where transactionId=$iTransactionId";
					}
				}
				if(!mysql_query($qryUpdate,$cnn_cs)){
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query status update");
					exit();
				}				
			}
			if($strCancel != "" && $strCancel != $db_cancelstatus){
				$qryUpdate = "update cs_transactiondetails set passStatus='ND',cancelstatus='Y',reason='$strCancelReason',other='$strOtherReason',cancellationDate='$strCurrentDateTime'   where transactionId=$iTransactionId";
				if(!mysql_query($qryUpdate,$cnn_cs)){
					print(mysql_errno().": ".mysql_error()."<BR>");
					print("Can not execute query cancel update query");
					exit();
				}				
			}
		}	
	}

?>
<html>
<body onLoad="document.dates1.submit()">
		<form name="dates1" action="reportbottom1.php"  method="POST">
			<input type="hidden" name="opt_from_year" value="<?= $i_from_year?>">
			<input type="hidden" name="opt_from_month" value="<?= $i_from_month?>">
			<input type="hidden" name="opt_from_day" value="<?= $i_from_day?>">
			<input type="hidden" name="opt_to_year" value="<?= $i_to_year?>">
			<input type="hidden" name="opt_to_month" value="<?= $i_to_month?>">
			<input type="hidden" name="opt_to_day" value="<?= $i_to_day?>">
			<input type="hidden" name="crorcq" value="<?= $crorcq?>">
			<input type="hidden" name="hid_companies" value="<?= $hid_companies ?>">
			<input type="hidden" name="trans_ptype" value="<?=$trans_ptype ?>">
			<input type="hidden" name="trans_ctype" value="<?=$trans_ctype ?>">
			<input type="hidden" name="trans_dtype" value="<?= $trans_dtype?>">
			<input type="hidden" name="voiceid" value="<?= $voiceid?>">
			<input type="hidden" name="transactionId" value="<?=$transactionId ?>">
			<input type="hidden" name="radRange" value="<?=$radRange ?>">
			<input type="hidden" name="cnumber" value="<?=$cnumber ?>">
		</form>
<script language="JavaScript" type="text/JavaScript">
function func_submit()
{
	//document.dates1.submit();
}
</script>
		
</body>
</html> 