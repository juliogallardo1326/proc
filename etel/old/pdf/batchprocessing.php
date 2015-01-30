<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// batchuploads.php:	This page functions for updating the company transaction details. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

$headerInclude = "home";
include 'includes/topheader.php';
require_once('includes/function.php');
include 'includes/function1.php';
include 'includes/function2.php'; 
$trans_date_enter=date("Y-m-d H:i:s");				
$trans_id="";
$cardType="";
$bank_check="";
$cardExpire="";
$insert_data_status = array();
$company_id = isset($HTTP_POST_VARS['company'])?Trim($HTTP_POST_VARS['company']):"";
$trans_type = isset($HTTP_POST_VARS['trans_type'])?Trim($HTTP_POST_VARS['trans_type']):"";
if(isset($_FILES['fle_attachment'])) {
	extract($_FILES['fle_attachment'], EXTR_PREFIX_ALL, 'uf2');
	if ($uf2_name != "" ) {
		$svr = $_SERVER["PATH_TRANSLATED"];
		$path_parts = pathinfo($svr); 
		$str_current_path = $path_parts["dirname"];
		$str_file_name = $uf2_name;
		$str_current_path .= "\\admin\\csv\\".$str_file_name;
	if(filesize($uf2_tmp_name) != 0)
		{
			if(copy($uf2_tmp_name,$str_current_path))
			{
				$fd = fopen ($str_current_path,"r"); 
				$contents = fread ($fd,filesize($str_current_path)); 
				fclose ($fd); 
				$delimiter = "\n"; 
				$splitcontents = explode($delimiter, $contents);
				//print count($splitcontents);
				$counter =0;
				$str_message = "";
				if($splitcontents) {
?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table width="95%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Batch 
            Processing Report</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5">

<?php				
			print("<br><table align=center width='98%'>");
			print"<tr bgcolor='#78B6C2'><td class='cl1'><span class='subhd'>Reference Number</span></td><td class='cl1'><span class='subhd'>First Name</span></td><td class='cl1'><span class='subhd'>Last Name</span></td><td class='cl1'><span class='subhd'>Address</span></td><td class='cl1'><span class='subhd'>Email Address</span></td><td class='cl1'><span class='subhd'>Telephone Number</span></td><td class='cl1'><span class='subhd'>Status</span></td></tr>";
				}
				foreach ( $splitcontents as $str_line_data )
				{
					if(Trim($str_line_data) != "")
					{
						if($counter > 0)
						{
							// print($counter.". ".$str_line_data."<br>");
							$delimiter = "\t"; 
							$str_data = explode($delimiter, $str_line_data);
							
							$i_array_count = 0;
							for($iLoop = 0;$iLoop<count($str_data);$iLoop++)
							{
								if(Trim($str_data[$iLoop])!="")
								{
									$arr_real[$i_array_count] = Trim($str_data[$iLoop]);
									$i_array_count++;
								}
								$array_transdetails = explode(",",$str_data[$iLoop]);
								if($trans_type == "Check") {
									func_insert_transactionCheckdata($array_transdetails,$company_id,$cnn_cs);
								} else {
								
									func_insert_transactionCreditdata($array_transdetails,$company_id,$cnn_cs);
								}
							} 
						}
						$counter++;
					}
				}
				if($counter+1 == count($splitcontents)) {
					print("<tr><td colspan='7' align='center' height='30' valign='middle'><a href='javascript:window.history.back();'><img border=0 src='images/back.jpg'></a></td></tr></table>");
				} 
			}
			else
			{
				$msgtodisplay = "Error in copying file";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();	
			}
		}
		else
		{
			$msgtodisplay = "Invalid File";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();	
		}
	}
?>
</td>
      </tr>
	<tr>
	<td width="1%"><img src="images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="images/menubtmright.gif"></td>
	</tr>
    </table>
    </td>
     </tr>
</table><br>

<?php
include("includes/footer.php");
	
 }
 
 // Function for check process
 function func_insert_transactionCheckdata($array_transdetails,$company_id_val,$cnn_connection) {
	$trans_id="";
	$trans_insert_status="";
	$reference_number ="NIL";
	$str_invalid_date = "";
	if(count($array_transdetails)>0) {
			$authorisationno =$array_transdetails[16]; 
			$telephoneno = $array_transdetails[7];
			/*$format_split_date = split("/",$array_transdetails[21]);
			if(count($format_split_date)== 3 ) {			
				$format_split_date[0] = strlen(trim($format_split_date[0])) == 1 ? "0".$format_split_date[0] : $format_split_date[0];
				$format_split_date[1] = strlen(trim($format_split_date[1])) == 1 ? "0".$format_split_date[1] : $format_split_date[1];
				$trans_date_enter = "$format_split_date[0]-$format_split_date[1]-$format_split_date[2]:00";
				$trans_date_enter = func_get_date_inyyyymmdd_time($trans_date_enter);
			} else {
				$trans_date_enter = "";
			}*/$trans_date_enter=date("Y-m-d H:i:s");
			if($trans_date_enter == "") {
				$str_invalid_date = "Invalid Date";
			} else {
			
				$name			=	trim($array_transdetails[0]);
				$surname		=	trim($array_transdetails[1]);
				$address		=	trim($array_transdetails[2]);
				$city			=	trim($array_transdetails[3]);
				$state			=	trim($array_transdetails[4]);
				$zipcode		=	trim($array_transdetails[5]);
				$country		=	trim($array_transdetails[6]);
				$phonenumber	=	trim($array_transdetails[7]);
				$email			=	trim($array_transdetails[8]);																															
				$amount			=	trim($array_transdetails[9]);
				$checkorcard 	=	trim($array_transdetails[10]);
				$CCnumber		=	trim(etelDec($array_transdetails[11]));
				$checkType		=	trim($array_transdetails[12]);
				$accounttype	=	trim($array_transdetails[13]);
				$bankname		=	trim($array_transdetails[14]);
				$bankroutingcode =	trim($array_transdetails[15]);
				$bankaccountnumber =	trim($array_transdetails[16]);
				$voiceAuthorizationno =	trim($array_transdetails[17]);
				$shippingTrackingno =	trim($array_transdetails[18]);
				$socialSecurity =	trim($array_transdetails[19]);
				$licensestate 	=	trim($array_transdetails[20]);
				$driversLicense =	trim($array_transdetails[21]);
				$transactionDate =	$trans_date_enter;
				$billingDate 	=	trim($array_transdetails[22]);
				$misc			=	trim($array_transdetails[23]);
				$userid			=	trim($company_id_val);
																
				$qrt_insert_details = "insert into cs_transactiondetails (name,surname,address,city,state,zipcode,country,phonenumber,email,amount,checkorcard,CCnumber,checktype,accounttype,bankname,bankroutingcode,bankaccountnumber,voiceAuthorizationno,shippingTrackingno,socialSecurity,licensestate,driversLicense,transactionDate,billingDate,misc,userid,passStatus,pass_count,cancelstatus,status,declinedReason,productdescription,company_usertype,company_user_id) 
				values('$array_transdetails[0]','$array_transdetails[1]','$array_transdetails[2]','$array_transdetails[3]','$array_transdetails[4]','$array_transdetails[5]','$array_transdetails[6]','$array_transdetails[7]','$array_transdetails[8]',$array_transdetails[9],'C','$array_transdetails[10]','$array_transdetails[11]','$array_transdetails[12]','$array_transdetails[13]','$array_transdetails[14]','$array_transdetails[15]','$array_transdetails[16]','$array_transdetails[17]','$array_transdetails[18]','$array_transdetails[19]','$array_transdetails[20]','$trans_date_enter','$array_transdetails[22]','$array_transdetails[23]',$company_id_val,'PA',0,'N','P','Error in Data','Service',5,$userid)"; 
			}
		if($str_invalid_date == "") {
			$auth_status = func_isauthorisationno_check($authorisationno,$telephoneno,$company_id_val,$cnn_connection);
			if ($auth_status == "")
			{	
				if($authorisationno !="" || $telephoneno !="") {
					if(!$sql_insert_data = mysql_query($qrt_insert_details)) {
						$trans_insert_status = "failure";
					} else {
						$trans_id=mysql_insert_id();
						$ref_number= func_Trans_Ref_No($trans_id );
						$updateSuccess="";
						$updateSuccess=func_update_single_field('cs_transactiondetails','reference_number',$ref_number,'transactionId',$trans_id,$cnn_connection);
						func_update_rate($userid,$trans_id,$cnn_connection);
						if($updateSuccess=1){
							$reference_number=$ref_number;
						}
						$trans_insert_status = "success";
						$qrt_select_company = "Select processing_currency,bank_check from cs_companydetails where userid='$company_id_val'";
					if(!($show_sql_run = mysql_query($qrt_select_company)))
					{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

					}
					else
					{
						if(mysql_num_rows($show_sql_run)== 0) 

						{
							$msgtodisplay="You are not a valid user";
							$outhtml="y";				
							message($msgtodisplay,$outhtml,"transactions");									
							exit();	
						} 
						else 
						{							
							$processing_currency= mysql_result($show_sql_run,0,0);
							$bank_check= mysql_result($show_sql_run,0,1);
							func_ins_bankrates($trans_id,$bank_check,$cnn_connection);
							if($processing_currency=="") { $processing_currency ="USD";}
							$updateSuccess=func_update_single_field('cs_transactiondetails','currencytype ',$processing_currency,'transactionId',$trans_id,$cnn_connection);
						}
			
					}
						if($updateSuccess=1)
						$trans_insert_status = "success";
					}
					
				} else {
					$trans_insert_status = "success";
				}
			} else {
					$trans_insert_status = "failure";
			}
		}
	} else {
		$trans_insert_status = $str_invalid_date;
	}
	print "<tr height='20'><td class='leftbottomright'><font face='verdana' size='1'>&nbsp;$reference_number</font></td><td class='leftbottomright'><font face='verdana' size='1'>&nbsp;$array_transdetails[0]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$array_transdetails[1]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$array_transdetails[2]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$array_transdetails[8]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$array_transdetails[7]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$trans_insert_status</font></td></tr>";
}

// Function for volpay process.

function func_volpay_bankprocess($bank_Username,$bank_Password,$ReferenceNo,$cardType,$CCnumber,$cardExpire,$cvv,$name,$surname,$address,$zipcode,$city,$country,$phonenumber,$state,$email,$currencyCode,$transactAmount,$cnn_connection, $transactionId,$i_company_id){
$data ="";
$xml_string = "<epxml><header>
			<responsetype>direct</responsetype>
				<mid>$bank_Username</mid>
				<password>$bank_Password</password>
			<type>charge</type>
			</header>
			<request><charge>
				<etan>$ReferenceNo</etan>
			<card>
				<cctype>$cardType</cctype>
				<cc>$CCnumber</cc>
				<expire>$cardExpire</expire>
				<cvv>$cvv</cvv>
			</card>
			<cardholder>
				<name>$name</name>
				<surname>$surname</surname>
				<street>$address</street>
				<housenumber>123</housenumber>
				<zip>$zipcode</zip>
				<city>$city</city>
				<country>$country</country>
				<telephone>$phonenumber</telephone>
				<state>$state</state>
				<email>$email</email>
			</cardholder>
			<amount>
				<currency>$currencyCode</currency>
				<value>$transactAmount</value>
			</amount>
			</charge>
			</request></epxml>";
			$data	= func_volpaybank_integration_result($xml_string);
			$Nodes = array();
			$count = 0;
			$pos = 0;
			
			// Goes throw XML file and creates an array of all <XML_TAG> tags.
			while ($node = GetElementByName($data, "<epxml>", "</epxml>")) {
			   $Nodes[$count] = $node;
			   $count++;
			   $data = substr($data, $pos);
			   $pos ++;
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
						$transStatus = $returnMessage."-".$returnCode;
					}
					
					if(strpos($responseDesc,"success")) {
						$returnMessage = GetElementByName($Nodes[$i], "<message>", "</message>");
						$returnCode = GetElementByName($Nodes[$i], "<tan>", "</tan>");
						$transNo = GetElementByName($Nodes[$i], "<etan>", "</etan>");
						$strMessage="<h3>Thank-you for your order</h3>Your order number is $ReferenceNo.Your Order has been Approved and Please refer to this in any correspondence.";
						//$msgtodisplay .= $returnMessage."-".$returnCode ;
						$transStatus ="Success";		
					}
					
			}
					$qrt_insert_bankdetails = "insert into cs_volpay (trans_id,user_id,currency,amount,trans_status,return_code,return_message,reference_number) values($transactionId,$i_company_id,'$currencyCode',$transactAmount,'$transStatus','$returnCode','$returnMessage','$ReferenceNo') "; 
				//	print($qrt_insert_bankdetails); 
					if(!($show_sql =mysql_query($qrt_insert_bankdetails,$cnn_connection)))
					{
						print(mysql_errno().": ".mysql_error()."<BR>");
						print("Cannot execute queryin");
						exit();
					}
					$approval_status = $transStatus == "Success" ? "A" : "D";
					$decline_reason = $transStatus == "Success" ? "" : $returnCode." : ".$returnMessage;
					$pass_status = "PA";
					func_update_approval_status($cnn_connection, $transactionId, $approval_status, $pass_status, $decline_reason);
					if ($transStatus == "Success") {
					//	func_send_transaction_success_mail($transactionId);
					} 
			return $transStatus;
}


function func_insert_transactionCreditdata($array_transdetails,$company_id_val,$cnn_connection) {
	$trans_insert_status="";
	$reference_number ="NIL";
	$str_invalid_date = "";
		if(count($array_transdetails)>0) {
			$telephoneno = $array_transdetails[7];
			/*$format_split_date = split("/",$array_transdetails[15]);
			if(count($format_split_date)== 3 ) {			
				$format_split_date[0] = strlen(trim($format_split_date[0])) == 1 ? "0".$format_split_date[0] : $format_split_date[0];
				$format_split_date[1] = strlen(trim($format_split_date[1])) == 1 ? "0".$format_split_date[1] : $format_split_date[1];
				$trans_date_enter = "$format_split_date[0]-$format_split_date[1]-$format_split_date[2]:00";
				$trans_date_enter = func_get_date_inyyyymmdd_time($trans_date_enter);
			} else {
				$trans_date_enter = "";
			}*/$trans_date_enter=date("Y-m-d H:i:s");
			if($trans_date_enter == "") {
				$str_invalid_date = "Invalid Date";
			} else {
			
				$name			=	trim($array_transdetails[0]);
				$surname		=	trim($array_transdetails[1]);
				$address		=	trim($array_transdetails[2]);
				$city			=	trim($array_transdetails[3]);
				$state			=	trim($array_transdetails[4]);
				$zipcode		=	trim($array_transdetails[5]);
				$country		=	trim($array_transdetails[6]);
				$phonenumber	=	trim($array_transdetails[7]);
				$email			=	trim($array_transdetails[8]);
				$amount			=	trim($array_transdetails[9]);
				$checkorcard 	=	"H";
				$cardType		=	trim($array_transdetails[10]);	
				$CCnumber		=	trim($array_transdetails[11]);
				$cvv			=	trim($array_transdetails[12]);
				$validupto		=	trim($array_transdetails[13]);
				$transactionDate=	$trans_date_enter;
				$productdescription= trim($array_transdetails[14]);
				$billingDate	=	trim($array_transdetails[15]);
				$firstnum	=	substr($CCnumber,0,1);
				if($firstnum=="5"){ $cardType="Master"; }
				 else { $cardType="Visa"; }
				$userid=$company_id_val;
				$domain= getRealIp()); 
				
				$temp=$billingDate; 
				$tem=explode ("/",$temp);
				$mm=$tem[0];
				$dd=$tem[1];
				$yyyy=$tem[2];
				$d=$yyyy."-".$mm."-".$dd;
				
				
				$qrt_insert_details = "insert into cs_transactiondetails (name,surname,address,city,state,zipcode,country,phonenumber,email,amount,checkorcard,cardtype,CCnumber,cvv,validupto,transactionDate,billingDate,userid,passStatus,pass_count,cancelstatus,status,ipaddress,productdescription,declinedReason,company_usertype,company_user_id )
				  values('$array_transdetails[0]','$array_transdetails[1]','$array_transdetails[2]','$array_transdetails[3]','$array_transdetails[4]','$array_transdetails[5]','$array_transdetails[6]','$array_transdetails[7]','$array_transdetails[8]',$array_transdetails[9],'H','$cardType','$array_transdetails[11]','$array_transdetails[12]','$array_transdetails[13]','$trans_date_enter','$trans_date_enter;',$company_id_val,'PA',0,'N','D','$domain','$array_transdetails[14]','Error in data',5,$userid)"; 
				// print $qrt_insert_details ."<br>";
			}
			if(func_isvalidCardnumber($CCnumber)){
			if(func_isvalidCardnumber($cvv)){
			if($str_invalid_date == "") {
			// Credit card details.
				if(!$sql_insert_data = mysql_query($qrt_insert_details)) {
					$trans_insert_status = "failure";
				} else {
					$transactionId = mysql_insert_id();
					$ref_number= func_Trans_Ref_No($transactionId );
		

					$updateSuccess="";
					$updateSuccess=func_update_single_field('cs_transactiondetails','reference_number',$ref_number,'transactionId',$transactionId,$cnn_connection);
					func_update_rate($userid,$transactionId,$cnn_connection);
					if($updateSuccess=1){
						$reference_number=$ref_number;
					}
					$qrt_select_company = "Select companyname,transaction_type,billingdescriptor,email,send_mail,send_ecommercemail,bank_Creditcard,bank_shopId,bank_Username,bank_Password,processing_currency from cs_companydetails where userid='$company_id_val'";
					if(!($show_sql_run = mysql_query($qrt_select_company)))
					{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

					}
					else
					{
						if(mysql_num_rows($show_sql_run)== 0) 
						{
							$msgtodisplay="You are not a valid user";
							$outhtml="y";				
							message($msgtodisplay,$outhtml,$headerInclude);									
							exit();	
						} 
						else 
						{
							$company_name 		= mysql_fetch_array($show_sql_run);
							$transaction_type 	= mysql_result($show_sql_run,0,1);
							$billingdescriptor 	= mysql_result($show_sql_run,0,2);
							$fromaddress 		= mysql_result($show_sql_run,0,3);
							$send_mails 		= mysql_result($show_sql_run,0,4);
							$send_ecommercemail = mysql_result($show_sql_run,0,5);
							$bank_CreditcardId 	= mysql_result($show_sql_run,0,6);
							$bank_shopId 		= mysql_result($show_sql_run,0,7);
							$bank_Username 		= mysql_result($show_sql_run,0,8);
							$bank_Password 		= mysql_result($show_sql_run,0,9);
							$processing_currency= func_get_cardcurrency($cardType,$company_id_val,$cnn_connection);
						func_ins_bankrates($transactionId,$bank_CreditcardId,$cnn_connection);		
							$firstnum="";
							if($processing_currency=="") { $processing_currency ="USD";}
							$updateSuccess=func_update_single_field('cs_transactiondetails','currencytype ',$processing_currency,'transactionId',$transactionId,$cnn_connection);
							$updateSuccess=func_update_single_field('cs_transactiondetails','bank_id',$bank_CreditcardId,'transactionId',$transactionId,$cnn_connection);
							$firstnum	=	substr($CCnumber,0,1);
							if($firstnum=="5"){ $cardType="Master";} else { $cardType="Visa"; }
							$cardExpir 	=	split("/",$validupto);
							$year		=	$cardExpir[0];
							$mon	=		$cardExpir[1];
							$cardExpire	= 	$cardExpir[1]."/".substr($year,2,3);
							$abbrCountry = func_country_abbreviation($country);
							$abbrState	 = func_state_abbreviation($state);
						// function for submitting to bank
						// print "func_volpay_bankprocess($bank_Username,$bank_Password,$reference_number,$cardType,$CCnumber,$cardExpire,$cvv,$name,$surname,$address,$zipcode,$city,$abbrCountry,$phonenumber,$abbrState,$email,$processing_currency,$amount,$cnn_connection,$transactionId)";
							$amount =($amount*100);
							if($bank_CreditcardId == 6 || $bank_CreditcardId == 7 || $bank_CreditcardId == 8) {
								$trans_insert_status = func_volpay_bankprocess($bank_Username,$bank_Password,$reference_number,$cardType,$CCnumber,$cardExpire,$cvv,$name,$surname,$address,$zipcode,$city,$abbrCountry,$phonenumber,$abbrState,$email,$processing_currency,$amount,$cnn_connection, $transactionId,$company_id_val);
							} else if($bank_CreditcardId == 3){ 
								$cardTypeBr = $cardType == "Visa" ? "V" : "M";
								$trans_insert_status= func_bardo_bankprocess($bank_shopId,$cardTypeBr,$CCnumber,$validupto,$cvv,$name,$surname,$address,$zipcode,$city,$abbrCountry,$phonenumber,$abbrState,$email,$processing_currency,$amount,$transactionId);
							} //else if bid==bardo
						}
					}
				}
			} else {
				$trans_insert_status = $str_invalid_date;
			}
			}
			else
			$trans_insert_status = "Error in Data";
			}
			else
			$trans_insert_status = "Error in Data";
	print "<tr height='20'><td class='leftbottomright'><font face='verdana' size='1'>&nbsp;$reference_number</font></td><td class='leftbottomright'><font face='verdana' size='1'>&nbsp;$array_transdetails[0]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$array_transdetails[1]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$array_transdetails[2]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$array_transdetails[8]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$array_transdetails[7]</font></td><td class='cl1'><font face='verdana' size='1'>&nbsp;$trans_insert_status</font></td></tr>";
	}
}

// Function to call bardo.
function func_bardo_bankprocess($bank_shopId,$cardType,$CCnumber,$validupto,$cvv,$name,$surname,$address,$zipcode,$city,$abbrCountry,$phonenumber,$abbrState,$email,$processing_currency,$amount,$transactionId) 
{	/*if($cardType=='M'){
	$processing_currency="EUR";
	}
	else{
	$processing_currency="USD";
	}*/
	$cardExp 	=	split("/",$validupto);
	$yyyy		=	$cardExp[0];
	$mon	=		$cardExp[1];
	$domain = GetHostByName($_SERVER["REMOTE_ADDR"]); 
	if($bank_shopId =="") { $bank_shopId = "TELEGATE"; }
	$pg_response_description="";
	$pg_response_type="";
	$pg_response_code="";
	$output_transaction =  "SHOP_ID=$bank_shopId&";
	$output_transaction .= "CUSTOMER_IP=$domain&";
	$output_transaction .= "PRODUCT_NAME=Service&";
	$output_transaction .= "LANGUAGE_CODE=ENG&";
	$output_transaction .= "CURRENCY_CODE=$processing_currency&";
	$output_transaction .= "SHOP_NUMBER=$transactionId&";
	$output_transaction .= "CUSTOMER_LAST_NAME=$surname&";
	$output_transaction .= "CUSTOMER_FIRST_NAME=$name&";
	$output_transaction .= "CUSTOMER_EMAIL=$email&";
	$output_transaction .= "CUSTOMER_ADDRESS=$address&";
	$output_transaction .= "CUSTOMER_CITY=$city&";
	$output_transaction .= "CUSTOMER_ZIP_CODE=$zipcode&";
	$output_transaction .= "CUSTOMER_STATE=$abbrState&";
	$output_transaction .= "CUSTOMER_COUNTRY=$abbrCountry&";
	$output_transaction .= "CUSTOMER_PHONE=$phonenumber&";
	$output_transaction .= "TRANSAC_AMOUNT=$amount&";
	$output_transaction .= "CB_TYPE=$cardType&";
	$output_transaction .= "CB_NUMBER=$CCnumber&";
	$output_transaction .= "CB_MONTH=$mon&";
	$output_transaction .= "CB_YEAR=$yyyy&";
	$output_transaction .= "CB_CVC=$cvv&";
	$output_transaction .= "3DS=YES&";
	$output_transaction .= "endofdata&";
	
	// output url - i.e. the absolute url to the paymentsgateway.net script
	//$output_url = "https://www.paymentsgateway.net/cgi-bin/posttest.pl";
	
	// Uncomment below for live
	$output_url = "https://www.bardo-secured-transactions.com/cpe/receive.asp";
	
	// start output buffer to catch curl return data
	ob_start();

	// setup curl
		$ch = curl_init ($output_url);
	// set curl to use verbose output
		curl_setopt ($ch, CURLOPT_VERBOSE, 1);
	// set curl to use HTTP POST
		curl_setopt ($ch, CURLOPT_POST, 1);
	// set POST output
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $output_transaction);
	//execute curl and return result to STDOUT
		curl_exec ($ch);
	//close curl connection
		curl_close ($ch);

	// set variable eq to output buffer
	$process_result = ob_get_contents();
	
	// close and clean output buffer
	ob_end_clean();
	
	// clean response data of whitespace, convert newline to ampersand for parse_str function and trim off endofdata
	$clean_data = str_replace("\n","&",trim(str_replace("endofdata", "", trim($process_result))));
	
	// parse the string into variablename=variabledata
	parse_str($clean_data);
	
	//echo "Response Data ".$clean_data;
	sleep(10);
	// output some of the variables
	$selectBankUpdates = "Select * from cs_bardo where shop_number = '$transactionId'";
	if(!($run_Select_Qry = mysql_query($selectBankUpdates))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$str_status = "";
	$str_decline_reason = "";
	if (mysql_num_rows($run_Select_Qry) != 0) {
		$pg_response_type = mysql_result($run_Select_Qry, 0, 7);
		$pg_response_description = $str_status == "S" ? "Success" : mysql_result($run_Select_Qry, 0, 4);
	}

//	echo "Response Code = ".$pg_response_code."<br />"; 
//	echo "Response Description = ".$pg_response_description."<br />"; 
//	print $output_transaction;
	$trans_response = $pg_response_type."-".$pg_response_description;
	return($trans_response);
}
function func_isvalidCardnumber($cardNo)
{
$len= strlen($cardNo);
$str_Except="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSDTUVWXYZ.~!@#$%^&*()_+|\=-{}][;'";
$i_count=0;
$cardNo=(string)$cardNo;

for($i=0;$i<$len;$i++)
{
$item=substr($cardNo,$i,1);
$i_count+=substr_count($str_Except,$item);
}

if ($i_count==0) {
return true;
} else {
return false;
}
}
?>
