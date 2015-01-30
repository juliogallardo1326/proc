<?php
include("includes/sessioncheck.php");


$str_type = (isset($HTTP_GET_VARS["type"])?quote_smart($HTTP_GET_VARS["type"]):"v");
?>
		<html>
		<head>
		<title>
		Printable Letters
		</title>
		</head>
		<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="javascript:func_print('<?= $str_type?>');">
<?php
set_time_limit(300);
ignore_user_abort(true);
ini_set("max_execution_time",0);

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$iTemplatId = (isset($HTTP_POST_VARS["mailtemplate"])?quote_smart($HTTP_POST_VARS["mailtemplate"]):$i_to_day);
		
//		$txtDate = (isset($HTTP_POST_VARS["txtDate"])?quote_smart($HTTP_POST_VARS["txtDate"]):"");
//		$txtDate1 = (isset($HTTP_POST_VARS["txtDate1"])?quote_smart($HTTP_POST_VARS["txtDate1"]):"");
$crorcq = (isset($HTTP_POST_VARS["crorcq"])?quote_smart($HTTP_POST_VARS["crorcq"]):"");
$hid_companies = (isset($HTTP_POST_VARS["hid_companies"])?quote_smart($HTTP_POST_VARS["hid_companies"]):"");
$trans_ptype = (isset($HTTP_POST_VARS["trans_ptype"])?quote_smart($HTTP_POST_VARS["trans_ptype"]):"");
$trans_ctype = (isset($HTTP_POST_VARS["trans_ctype"])?quote_smart($HTTP_POST_VARS["trans_ctype"]):"");
$trans_dtype = (isset($HTTP_POST_VARS["trans_dtype"])?quote_smart($HTTP_POST_VARS["trans_dtype"]):"");
$trans_atype = (isset($HTTP_POST_VARS["trans_atype"])?quote_smart($HTTP_POST_VARS["trans_atype"]):"");
$str_pass = (isset($HTTP_POST_VARS["chk_pass"])?quote_smart($HTTP_POST_VARS["chk_pass"]):"");
$str_nopass = (isset($HTTP_POST_VARS["chk_nopass"])?quote_smart($HTTP_POST_VARS["chk_nopass"]):"");
$voiceid = (isset($HTTP_POST_VARS["voiceid"])?quote_smart($HTTP_POST_VARS["voiceid"]):"");
$transactionId = (isset($HTTP_POST_VARS["transactionId"])?quote_smart($HTTP_POST_VARS["transactionId"]):"");
$cnumber = (isset($HTTP_POST_VARS["cnumber"])?quote_smart($HTTP_POST_VARS["cnumber"]):"");
$radRange = (isset($HTTP_POST_VARS["radRange"])?quote_smart($HTTP_POST_VARS["radRange"]):"");
$strType = (isset($HTTP_POST_VARS["type"])?quote_smart($HTTP_POST_VARS["type"]):"");
$decline_reason=(isset($HTTP_POST_VARS['decline_reasons'])?($HTTP_POST_VARS['decline_reasons']):"");
$cancel_reason=(isset($HTTP_POST_VARS['cancel_reasons'])?($HTTP_POST_VARS['cancel_reasons']):"");
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"";
$companyname = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"";

		$dateToEnter="$i_from_year-$i_from_month-$i_from_day 00:00:00";
        $dateToEnter1="$i_to_year-$i_to_month-$i_to_day 23:59:59";
		  $arrCompanies = $companyname;
//		  $arrCompanies = split(",",$hid_companies);
		  $strCompanyCondition = "";
		  $strCheckCreditCondition = "";
		  $strPendingCondition = "";
		  $strApprovedCondition = "";
		  $strDeclineCondition = "";
		  $decline_condition="";
		  $cancel_condition ="";
		  $i_dec=0;
		  $i_cancel=0;

		if($cancel_reason !=""){
		for($i_cancel = 0;$i_cancel < count($cancel_reason);$i_cancel++) {
			if($cancel_reason[$i_cancel] !="") {
				if($cancel_condition =="") {
					$cancel_condition = "reason ='".$cancel_reason[$i_cancel]."'";
				} else {
					$cancel_condition = $cancel_condition ." or reason ='".$cancel_reason[$i_cancel]."'";
				}
			}
		}
		}
		
		if($decline_reason !=""){
		for($i_dec = 0;$i_dec < count($decline_reason);$i_dec++) {
			if($decline_reason[$i_dec] !="") {
				if($decline_condition =="") {
					$decline_condition = "declinedReason ='".$decline_reason[$i_dec]."'";
				} else {
					$decline_condition = $decline_condition ." or declinedReason ='".$decline_reason[$i_dec]."'";
				}
			}
		}
		}
		  if($crorcq != "")
		  {
			$strCheckCreditCondition = "checkorcard = '$crorcq'";
		  }	
		   
		  $strConditions = "";
		  if($strCheckCreditCondition != "")
		  {
			if($strConditions != ""){
				$strConditions .= " and $strCheckCreditCondition ";		
			}else{
				$strConditions .= " $strCheckCreditCondition ";		
			}
		  }	
		
		  
		  $strTypeCondition = "";
		  if($strType != ""){
			if($crorcq == "C"){
				$strTypeCondition = "accounttype ='$strType' ";
			}
			if($crorcq == "H"){
				$strTypeCondition = "cardtype ='$strType' ";
			}
		  }
		  if($strTypeCondition != ""){
		  	if($strConditions != ""){
				$strConditions .= " and $strTypeCondition ";
			}else{
				$strConditions .= " $strTypeCondition ";
			}
		  }
		  $strStatusCondition = "";
		  $str_or_query = "";
		  
			if($trans_ptype != ""){
				if($strStatusCondition != ""){
					$strStatusCondition .= " or passStatus = 'PE' ";
				}else{
					$strStatusCondition .= " passStatus = 'PE' ";
				}		
			}
			if($trans_dtype != ""){
				if($strStatusCondition != ""){
					$strStatusCondition .= " or status = 'D' ";	 	
				}else{
					$strStatusCondition .= " status = 'D' ";	 	
				}
			}	
			if($trans_atype != ""){
				if($strStatusCondition != ""){
					$strStatusCondition .= " or status = 'A' ";
				}else{
					$strStatusCondition .= " status = 'A' ";
				}		
			}
			if($strStatusCondition != ""){
				if($str_or_query != ""){
					$str_or_query .= " or ".$strStatusCondition." ";
				}else{
					$str_or_query .= " ( ".$strStatusCondition." ";
				}
			}
			
			$strPassStatusCondition = "";

			if($str_pass != ""){ 
				if($strPassStatusCondition != ""){
					$strPassStatusCondition .= " or passStatus = 'PA' ";	 	
				}else{
					$strPassStatusCondition .= " passStatus = 'PA' ";	 	
				}
			}

			if($str_nopass != ""){ 
				if($strPassStatusCondition != ""){
					$strPassStatusCondition .= " or passStatus = 'NP' ";	 	
				}else{
					$strPassStatusCondition .= " passStatus = 'NP' ";	 	
				}
			}
				
			if($strPassStatusCondition != ""){
				if($str_or_query != ""){
					$str_or_query .= " or ".$strPassStatusCondition." ";
				}else{
					$str_or_query .= " ( ".$strPassStatusCondition." ";
				}
			}

			if($voiceid != ""){
				if($strConditions != "") {
					$strConditions .= " and voiceAuthorizationno ='$voiceid' ";
				}else{
					$strConditions .= " voiceAuthorizationno ='$voiceid' ";	
				}
			}
			
			if($transactionId != ""){
				if($strConditions != "") {
					$strConditions .= " and transactionId = $transactionId ";
				}else{
					$strConditions .= " transactionId = $transactionId ";
				}		
			}	
			if($decline_condition != ""){
				if($str_or_query != ""){
					$str_or_query .= " or ".$decline_condition;
				}else{
					$str_or_query .= " ( ".$decline_condition;
				}
			}
			
			if($cancel_condition != ""){
				if($str_or_query != ""){
					$str_or_query .= " or ".$cancel_condition;
				}else{
					$str_or_query .= " ( ".$cancel_condition;
				}
			}
			if($cnumber != ""){
				if($strConditions != ""){
					$strConditions .= " and CCnumber = '".etelEnc($cnumber)."' ";
				}else{
					$strConditions .= " CCnumber = '".etelEnc($cnumber)."' ";
				}
			}
			$strRadConditions = "";
			if($radRange == "S"){
				$strRadConditions = " (billingDate >= '$dateToEnter' and billingDate <= '$dateToEnter1') ";	
			}else{
				$strRadConditions = " (transactionDate  >= '$dateToEnter' and  transactionDate <= '$dateToEnter1') ";
			}
			if($strRadConditions != ""){
				if($strConditions != ""){
					$strConditions .= " and $strRadConditions";
				}else{
					$strConditions .= $strRadConditions;
				}
				
			}
			
			if($trans_ctype != ""){
				if($str_or_query != ""){
					$str_or_query .= " or cancelstatus ='Y' ";
				}else{
					$str_or_query .= " ( cancelstatus ='Y' ";
				}
			}
		if($str_or_query != ""){
			if($strConditions != ""){
				$strConditions .= " and $str_or_query ) ";
			}else{
				$strConditions .= " $str_or_query ) ";
			}
		}

			//if($companytype != "A" || $companytrans_type != "A") {
				$qrySelect = "SELECT billingdate,name,surname,checkorcard,a.phonenumber,amount,voiceAuthorizationno FROM cs_transactiondetails as a,cs_companydetails as b";
			/*}else {
				$qrySelect = "SELECT billingdate,name,surname,checkorcard,phonenumber,amount,voiceAuthorizationno FROM cs_transactiondetails ";
			}*/

			
			if($strConditions != ""){
				if($companytype=="AC"){
					if ($companytrans_type == "A") {
						$qrySelect .=" where a.userid=b.userid and b.activeuser=1 and ". $strConditions;
					} else {
						$qrySelect .=" where a.userid=b.userid and b.activeuser=1 and ". $strConditions ." and  transaction_type = '$companytrans_type'";
					}
				} else if($companytype=="NC"){
					if ($companytrans_type == "A") {
						$qrySelect .=" where a.userid=b.userid and b.activeuser=0 and ". $strConditions;
					} else {
						$qrySelect .=" where a.userid=b.userid and b.activeuser=0 and ". $strConditions ." and  transaction_type = '$companytrans_type'";
					}
				} else if($companytype=="RE"){
					if ($companytrans_type == "A") {
						$qrySelect .=" where a.userid=b.userid and b.reseller_id <> '' and ". $strConditions;
					} else {
						$qrySelect .=" where a.userid=b.userid and b.reseller_id <> '' and ". $strConditions ." and  transaction_type = '$companytrans_type'";
					}
				} else if($companytype=="ET"){
					if ($companytrans_type == "A") {
						$qrySelect .=" where a.userid=b.userid and b.reseller_id is null and ". $strConditions;
					} else {
						$qrySelect .=" where a.userid=b.userid and b.reseller_id is null and ". $strConditions ." and  transaction_type = '$companytrans_type'";
					}
				} else {
					if ($companytrans_type == "A") {
						$qrySelect .=" where ". $strConditions;
					} else {
						$qrySelect .=" where ". $strConditions ." and  transaction_type = '$companytrans_type'";
					}
				}
	//			$qrySelect = $qrySelect." where ".$strConditions;
			}	
		$qrySelect .= " and gateway_id = -1"; 				
		//print($qrySelect."<br><br>");
							
		
		
		//******************  if all company is selected *************************
		//************************************************************************
		 for($iLoop = 0;$iLoop<count($arrCompanies);$iLoop++)
		 {
			if(Trim($arrCompanies[$iLoop]) !="")
			{
				if($arrCompanies[$iLoop] == "A")
				{	if($companytype=="AC"){
						if ($companytrans_type == "A") {
							$qrySelectCompanies = "select userId from cs_companydetails where activeuser=1 ";
						} else {
							$qrySelectCompanies = "select userId from cs_companydetails where activeuser=1 and  transaction_type = '$companytrans_type'";
						}
					} else if($companytype=="NC"){
						if ($companytrans_type == "A") {
							$qrySelectCompanies = "select userId from cs_companydetails where activeuser=0 ";
						} else {
							$qrySelectCompanies = "select userId from cs_companydetails where activeuser=0 and  transaction_type = '$companytrans_type'";
						}
					} else if($companytype=="RE"){
						if ($companytrans_type == "A") {
							$qrySelectCompanies = "select userId from cs_companydetails where reseller_id <> '' ";
						} else {
							$qrySelectCompanies = "select userId from cs_companydetails where reseller_id <> '' and  transaction_type = '$companytrans_type'";
						}
					} else if($companytype=="ET"){
						if ($companytrans_type == "A") {
							$qrySelectCompanies = "select userId from cs_companydetails where reseller_id is null ";
						} else {
							$qrySelectCompanies = "select userId from cs_companydetails where reseller_id is null and  transaction_type = '$companytrans_type'";
						}
					} else {
						if ($companytrans_type == "A") {
							$qrySelectCompanies = "";
						} else {
							$qrySelectCompanies = "select userId from cs_companydetails where transaction_type = '$companytrans_type'";
						}
					}
					if ($qrySelectCompanies == "") {
						$qrySelectCompanies = "select userId from cs_companydetails where 1 ";
					} else {
						$qrySelectCompanies .= " and gateway_id = -1";
					}
					//print($qrySelectCompanies);
					if(!($rstSelectCompanies = mysql_query($qrySelectCompanies,$cnn_cs)))
					{
						print("Can not execute query");
						exit();	
					}
					for($iLoop=0;$iLoop<mysql_num_rows($rstSelectCompanies);$iLoop++)
					{
						$iUserId = mysql_result($rstSelectCompanies,$iLoop,0);
						//if($companytype != "A" || $companytrans_type != "A"){
							$qrySelect1 = $qrySelect." and a.userid = ".$iUserId;
						/*}else {
							$qrySelect1 = $qrySelect." and userid = ".$iUserId;
						}*/
						funcSendMail($iUserId,$qrySelect1,$cnn_cs,$iTemplatId);
					}
					break;
				}
				else
				{		
					//if($companytype != "A" || $companytrans_type != "A"){
						$qrySelect1 = $qrySelect." and a.userid = ".$arrCompanies[$iLoop];
					/*}else {
						$qrySelect1 = $qrySelect." and userid = ".$arrCompanies[$iLoop];
					}*/
					funcSendMail($arrCompanies[$iLoop],$qrySelect1,$cnn_cs,$iTemplatId);
				}
			}	
		 }
		
	//*********** Function for sending mail to a company transactions if company id and condition quey passed *********
	//*****************************************************************************************************************
	function funcSendMail($iCompanyId,$qryCondition,$cnn_cs,$iTemplatId)
	{	
		//*********** Opening the mail template content ***********
		$show_sql = "select template_content from cs_mailtemplate where template_id  = ".$iTemplatId;
		$file_content = funcGetValueByQuery($show_sql,$cnn_cs);
		
		
/*		if(!($file = fopen("csv/mailtemplate.htm", "r")))
		{
			print("Can not open file");
			exit();
		}	
		$content = fread($file, filesize("csv/mailtemplate.htm"));
		$content = explode("\r\n", $content);
		fclose($file);
		$file_content = "";
		for($i=0;$i<count($content);$i++)
		{
			$file_content .= $content[$i];
		} */
		
		//************* ends here *********************************
		
		
		
		$qry_email_template = "SELECT merchantName,tollFreeNumber,retrievalNumber,securityNumber,processor  FROM cs_companydetails where userid =".$iCompanyId;
		if(!($rst_email_template = mysql_query($qry_email_template,$cnn_cs)))
		{
			print("Can not execute query");
			exit();
		}	
		$str_merchant_name = "";
		$str_toll_free_number = "";
		$str_retrieval_number = "";
		$str_security_number = "";
		$str_processor = "";
		if(mysql_num_rows($rst_email_template)>0)
		{
			$str_merchant_name = mysql_result($rst_email_template,0,0);
			$str_toll_free_number = mysql_result($rst_email_template,0,1);
			$str_retrieval_number = mysql_result($rst_email_template,0,2);
			$str_security_number = mysql_result($rst_email_template,0,3);
			$str_processor = mysql_result($rst_email_template,0,4);
		}
		$file_content = str_replace("[MerchantName]",$str_merchant_name.",",$file_content); 
		$file_content = str_replace("[MerchantTollFreeNumber]",$str_toll_free_number,$file_content); 
		$file_content = str_replace("[RetrievalNumber]",$str_retrieval_number,$file_content); 
		$file_content = str_replace("[SecurityCode]",$str_security_number,$file_content); 
		$file_content = str_replace("[Processor]",$str_processor,$file_content); 
		
		
		$qrySelect = $qryCondition;
	//		print($qrySelect);
		if(!($show_sql = mysql_query($qrySelect,$cnn_cs)))
		{
			print("<br>");
			print("Can not execute select query");
			print("<br>");
			exit();
		}	

		for($i_loop = 0;$i_loop<mysql_num_rows($show_sql);$i_loop++)
		{
			$str_date_of_sale = mysql_result($show_sql,$i_loop,0);
			$str_date_of_sale = funcFormatDate($str_date_of_sale);
			$str_customer_first_name =  mysql_result($show_sql,$i_loop,1);
			$str_customer_last_name =  mysql_result($show_sql,$i_loop,2);
			$str_curstomer_name = $str_customer_first_name." ".$str_customer_last_name;
			$charge_type =  mysql_result($show_sql,$i_loop,3);
			if($charge_type == "H")
			{
				$charge_type = "credit card";
			}
			if($charge_type == "C")
			{
				$charge_type = "check";
			}
				
			$str_telephone =  mysql_result($show_sql,$i_loop,4);
			$str_charge =  mysql_result($show_sql,$i_loop,5);
			$str_voice_code = mysql_result($show_sql,$i_loop,6);
			
			$str_email_content = $file_content;
			$str_email_content = str_replace("[Dateofsale]",$str_date_of_sale,$str_email_content); 
			$str_email_content = str_replace("[CustomerName]",$str_curstomer_name,$str_email_content); 
			$str_email_content = str_replace("[ChargeType]",$charge_type,$str_email_content); 
			$str_email_content = str_replace("[CustomerTelephoneNumber]",$str_telephone,$str_email_content); 
			$str_email_content = str_replace("[Charge]",formatMoney($str_charge),$str_email_content); 
			$str_email_content = str_replace("[VoiceCode]",$str_voice_code,$str_email_content); 
			
			print($str_email_content); ?>
			<span style='font-size:12.0pt;font-family:"Times New Roman";mso-fareast-font-family:"Times New Roman";mso-ansi-language:EN-US;mso-fareast-language:EN-US;mso-bidi-language:AR-SA'><br clear=all style='mso-special-character:line-break;page-break-before:always'></span>
<?php		}
		} 
		//*********************** End of function *******************************
		//***********************************************************************
?>			
<script language="JavaScript" type="text/JavaScript">
function func_print(str_type)
{
	if (str_type == "p")
	{
		window.print();
		window.close();
	}
}
</script>
	
		</body>
		</html>
<?php
		function funcFormatDate($strDate)
		{
			$str_year = substr($strDate,0,4);
			$str_month = substr($strDate,5,2);
			$str_day = substr($strDate,8,2);
			$str_hour = substr($strDate,11,2);
			$str_minute = substr($strDate,14,2);
			$str_second = substr($strDate,17,2);
			$str_return_date = $str_month."/".$str_day."/".$str_year;
			return ($str_return_date);
		}?>		
