<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// massmail2fb.php:	This admin page functions for mailing the company. 
$disablePostChecks = true;
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude = "adminemail";
$etel_debug_mode=0;
include 'includes/header.php';
include("../includes/html2text.php");

foreach($HTTP_POST_VARS as $k => $c)
	$postback.= "<input type='hidden' name='$k' value='$c' >";
	
require_once( '../includes/function.php');

$show_sql =mysql_query("select distinct email,companyname from customerdetails order by email",$cnn_cs);
$backhref ="massmail2.php";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
?>

<?php
		$str_error = "";
		extract($_FILES['fle_address'], EXTR_PREFIX_ALL, 'uf');
		$str_from_address = (isset($HTTP_POST_VARS["fromaddress"])?trim($HTTP_POST_VARS["fromaddress"]):"");
		$str_subject = (isset($HTTP_POST_VARS["subject"])?trim($HTTP_POST_VARS["subject"]):"");
		$str_body = (isset($HTTP_POST_VARS["txtBody"])?trim($HTTP_POST_VARS["txtBody"]):"");
		$strAttachments = (isset($HTTP_POST_VARS["attachments"])?trim($HTTP_POST_VARS["attachments"]):"");
		
		$arrFiles = split(",",$strAttachments);
		if(trim($uf_size) == "0"){
			$str_error .= "<li>Uploaded TXT file is not a valid one</li>";
		}
		if($str_from_address == ""){
			$str_error .= "<li>Please enter the from address </li>";
		}
		if($str_subject == ""){
			$str_error .= "<li>Please enter the subject </li>";
		}
		if($str_body == ""){
			$str_error .= "<li>Please enter the body </li>";
		}
		if($str_error == ""){
			$str_file_name = $uf_name;
			$i_len = strlen($str_file_name);
			$i_pos_dot = strpos($str_file_name,"."); 
			$str_type = substr($str_file_name,$i_pos_dot+1,3);
			if(strtoupper($str_type)!="TXT"){
				$str_error .= "<li>Please select TXT file</li>";	
			}
		} 
		$str_current_path = "csv/";
		unset($attachments);
		for($iLoop = 0;$iLoop<count($arrFiles);$iLoop++){
			if($arrFiles[$iLoop]!=""){
				$arrFileNames[$iLoop] = $arrFiles[$iLoop];
				$arrFiles[$iLoop] = $str_current_path.$arrFiles[$iLoop];
				$attch['path']=$arrFiles[$iLoop];
				$attch['name']=$arrFileNames[$iLoop];
				$attch['encoding'] = 'base64';
				$attch['type'] = 'application/octet-stream';
				$attachments[] = $attch;
			}
		}
		if($str_error != "")
		{
			//******** Message showing while error occures starts *******************	
			 message($str_error.$postback,true,"Error",'massmail2.php',false);
			//******** Message showing while error occures ends **********************
		}
		else
		{
			$str_message = "";
			$str_file_name = $uf_name;
			$str_current_path = "csv/".$str_file_name;
			$str_file_path = "";
			
			
			
			if(copy($uf_tmp_name,$str_current_path))
			{
				$fd = fopen ($str_current_path,"r"); 
				$contents = fread ($fd,filesize($str_current_path)); 
				fclose ($fd); 
				$delimiter = "/[\s,]+/"; 
				$splitcontents = preg_split($delimiter, $contents); 
				$counter =0;
				$str_message = "";
				foreach ( $splitcontents as $str_mail_address )
				{
					if(Trim($str_mail_address) != ""){
						unset($emailInfo); 
						
						$emailInfo['et_subject'] = $str_subject; // => Welcome to Etelegate, tech 
						$emailInfo['et_htmlformat'] = stripslashes($str_body); // => html
					
						$emailInfo['et_from'] = $str_from_address; // => sales@etelegate.com 
						$emailInfo['et_from_title'] = $str_from_address; // => Etelegate Sales 
								
						$Html2Text = new Html2Text ($emailInfo['et_htmlformat'], 900000); // 900 columns maximum
						$emailInfo['et_textformat']= $Html2Text->convert();
						$emailInfo['et_textformat'] = str_replace("&nbsp;"," ",$emailInfo['et_textformat']);
						
						$emailInfo['et_to'] = $str_mail_address; // => techsupport@ecommerceglobal.com 
						$emailInfo['full_name'] = $str_mail_address; // => Etelegate Merchant )
						
						if(!send_email_data($emailInfo,$attachments))
						{
							func_store_bad_email($cnn_cs, 0, $emailInfo['et_to'], $emailInfo['et_to']);
							$mails_sentid .= "'".$emailInfo['et_to']."' could not be sent.<br>";
						}
						else { 
							$mails_sentid .= "Email sent to ".$emailInfo['et_to']."<br>";
							$mails_sent_num++;
						 }


					}	
				}
				if($str_message == "" )
					 $str_message = "Mail send to $mails_sent_num addresses <br>".$mails_sentid;
					message($str_message,true,"Result");
				?>
				<?php // message($str_message,"Y",$headerInclude); ?>		
<?php			}
			else
			{
			
			
			}
		}	

include 'includes/footer.php';
?>
