<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// massmail1.php:	This admin page functions for mailing the company. 
$disablePostChecks = true;
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
include("../includes/companySubView.php");
include("../includes/resellerSubView.php");
require_once("../includes/JSON_functions.php");
$headerInclude = "mail";

include("../includes/constants.php");

foreach($etel_completion_array as $key=>$data)
$cd_completion_options .="<option value='$key' style='".$data['style']."' >".$data['txt']."</option>\n";

$etel_completion_array[-1]['txt']="Old Company [No Status]";


include 'includes/header.php';
include("../includes/html2text.php");

set_time_limit(0);

$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$show_sql =mysql_query("select userid,companyname from cs_companydetails order by email",$cnn_cs) or dieLog(mysql_error());
$msgtodisplay = "";
$str_current_path = "";
$str_file_name = "";
$to_id="";
$mail_confirm = 0;
$qrt_select_companies="";
$qrt_select_nonreseller_qry = "";
$mails_sentid ="";
$mails_sent_num =0;

//$companytrans_type="tele";
//$companytype="AC";
$Transtype = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
$txtBody = isset($HTTP_POST_VARS['txtBody'])?trim($HTTP_POST_VARS['txtBody']):"";
if(!$txtBody) $txtBody = 
"Dear <span style=\"font-weight: bold;\">[companyname]</span>,<br/>
<br/>
<br/>
<br/>
<br/>
<span style='font-weight: bold;'> Your Login Information:</span><br/>
<br/>
<a href=\"".$_SESSION['gw_domain']."\">".$_SESSION['gw_domain']."<br/>
</a>Username: <span style=\"font-weight: bold;\">[username]</span>";


//$txtBody = nl2br($txtBody);
$selectAll =  isset($HTTP_POST_VARS['selectAll'])?quote_smart($HTTP_POST_VARS['selectAll']):"";
if ($selectAll==""){
	$selectAll =  isset($HTTP_GET_VARS['selectAll'])?quote_smart($HTTP_GET_VARS['selectAll']):"";
}
$fromaddress = $_REQUEST['fromaddress'];
if(!$fromaddress) $fromaddress = $_SESSION['gw_emails_sales'];
$allCompanies="";
$selectChecked="";
$selectnonReseller="";
$selectReseller="";
$search_reseller_active_status = "";
//$fromaddress = isset($HTTP_POST_VARS['fromaddress'])?quote_smart($HTTP_POST_VARS['fromaddress']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companymode_selection = $companytype;
if($companytype == 'active_reseller') {
	$companytype = 'reseller';
	$search_reseller_active_status = " AND completed_reseller_application=1 ";
	$companymode_selection = "active_reseller";
}
if($companytype == 'nonactive_reseller') {
	$companytype = 'reseller';
	$search_reseller_active_status = " AND completed_reseller_application=0 ";
	$companymode_selection = "nonactive_reseller";
}
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";
//$str_wire_fee_status = isset($HTTP_POST_VARS['wire_fee_status'])?quote_smart($HTTP_POST_VARS['wire_fee_status']):"A";
$str_uploaded_documents = isset($HTTP_POST_VARS['uploaded_documents'])?quote_smart($HTTP_POST_VARS['uploaded_documents']):"";
$str_merchant_application = isset($HTTP_POST_VARS['merchant_application'])?quote_smart($HTTP_POST_VARS['merchant_application']):"";
$str_wire_fee = isset($HTTP_POST_VARS['wire_fee'])?quote_smart($HTTP_POST_VARS['wire_fee']):"";
$nonReseller = isset($HTTP_POST_VARS['selectNonReseller'])?$HTTP_POST_VARS['selectNonReseller']:"";
$selectReseller = isset($HTTP_POST_VARS['selectReseller'])?$HTTP_POST_VARS['selectReseller']:"";
$cd_completion = isset($HTTP_POST_VARS['cd_completion'])?$HTTP_POST_VARS['cd_completion']:"";

$userIdList = $_REQUEST['companyname'];
$reselIdList = $_REQUEST['reselIdList'];

$ignore = " and cd_ignore=0 ";
if($_REQUEST['showall']) $ignore = " ";

$resellersql = "0";

$company_table_sql = "select distinct (select en_email from cs_entities where en_type='merchant' and en_type_ID=userid),companyname,username,password,send_mail,ReferenceNumber, userId from cs_companydetails where 0";
if($_REQUEST['cd_view']=='A')
{
	$company_table_sql = "select distinct (select en_email from cs_entities where en_type='merchant' and en_type_ID=userid),companyname,username,password,send_mail,ReferenceNumber, userId from cs_companydetails where cd_ignore = 0";
	if($_REQUEST['showall']) $ignore = "1";
	$companysql = $ignore;
}
else
if($_REQUEST['companyname'][0]=='AL')
{
	$sql_info = JSON_getCompanyInfo_build($_REQUEST);
	$company_table_sql = "select distinct (select en_email from cs_entities where en_type='merchant' and en_type_ID=userid) ,companyname,username,password,send_mail,ReferenceNumber, userId from ". $sql_info['sql_from']." Where ".$sql_info['sql_where'];
	$my_sql['joins']['cd']['table'] = "($company_table_sql) as cd ";
	$_REQUEST['companyname'] = NULL;
}	
else if($userIdList)
{
	$userIdList = implode(", ",$userIdList);
	$company_table_sql = "select distinct (select en_email from cs_entities where en_type='merchant' and en_type_ID=userid) ,companyname,username,password,send_mail,ReferenceNumber, userId from cs_companydetails where userid in ($userIdList)";
}

if($_REQUEST['rd_view']=='A')
{
	$resellersql = "1";
}
else if($reselIdList)
{
	$reselIdList = str_replace("|",",",$reselIdList);
	$resellersql = " reseller_id in ($reselIdList)";
}

if($toaddress=="") {
	$toaddress = isset($HTTP_GET_VARS['mailto_id'])?trim($HTTP_GET_VARS['mailto_id']):"";
	if($toaddress !="") {
		$allCompanies ="Yes";
	}
}
if($selectAll ==1) {
	$selectChecked ="checked";
}
if($nonReseller==1) {
	$selectnonReseller = "checked";
}
if($selectReseller==1) {
	$selectReseller = "checked";
}
$qrt_select_status_qry = "";
$subject = isset($HTTP_POST_VARS['subject'])?trim($HTTP_POST_VARS['subject']):"";

	if($subject){
	 
		$strFiles = isset($HTTP_POST_VARS['attachments'])?$HTTP_POST_VARS['attachments']:"";
		$strMailConfir = isset($HTTP_POST_VARS['confirmer'])?$HTTP_POST_VARS['confirmer']:"";
		$strMailReseller = isset($HTTP_POST_VARS['confirmer_resel'])?$HTTP_POST_VARS['confirmer_resel']:"";
		$arrFiles = split(",",$strFiles);
		$arrFileNames = split(",",$strFiles);
		$svr = $_SERVER["PATH_TRANSLATED"];
		$path_parts = pathinfo($svr); 
		//$str_current_path = $path_parts["dirname"];
		$str_current_path .= "/home/etel/public_html/csv/";
		$arrFileNames = "";
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
		$str_where_condition = "";
			$msgtodisplay = "Email has been sent successfully to the selected companies: <br>";

				$sql="(select distinct reseller_email as email,reseller_companyname as companyname,reseller_username as username,reseller_password as password,reseller_sendmail as send_mail,rd_referenceNumber as ReferenceNumber,0 as userId from cs_resellerdetails where $resellersql) 
				UNION ($company_table_sql)";

				$show_sql =mysql_query($sql,$cnn_cs) or dieLog(mysql_error()." ~ $sql");

				$sql="";

//					print "select distinct email,companyname,username,password,send_mail from cs_companydetails where userid=$mailid";
				while($show_val = mysql_fetch_assoc($show_sql))
				{
					$to_id=$show_val['email'];
					$company_name=$show_val['companyname'];
					$user_name =$show_val['username'];
					$pass_word=$show_val['password'];
					$mail_confirm = $show_val['send_mail'];
					$referenceNumber = $show_val['ReferenceNumber'];
					$userId = $show_val['userId'];
					
					
					
					if($userId && strpos($txtBody,'[list_pricepoints]'))
					{
						$list_pricepoints="<table cellspacing='3' cellpadding='2' border='2'>";
						$list_pricepoints.="<tr style='font-weight:bold;font-size:12px'><td width='100'> Description";
						$list_pricepoints.="</td><td> Please use the following code to Integrate your Price Points:";
						$list_pricepoints.="</td></tr>";
							
							
						$sql = "SELECT * FROM `cs_rebillingdetails` WHERE `company_user_id` ='$userId' limit 100";
						$result = mysql_query($sql);
						while($subAccount = mysql_fetch_assoc($result))
						{
						
							$schedule = "Once every ".$subAccount['recur_day']." day(s). <br>";
							if($subAccount['rd_initial_amount'] > 0) $schedule .="Trial Period is ".$subAccount['rd_trial_days']." day(s)";
							if($subAccount['recur_charge'] <= 0) 
							{
								$schedule = "One Time Payment.";
								if($subAccount['rd_trial_days']) $schedule .= "<br>Subscription time is ".$subAccount['rd_trial_days']." days";
							}
						
							$list_pricepoints.="<tr><td style='font-size:12px' width='100'>";
							$list_pricepoints.=$schedule;
							$list_pricepoints.="</td><td>";
							$list_pricepoints.="<textarea rows=5 cols=50>";
							
							$list_pricepoints .= "<form name='FrmPayment' action='".$_SESSION['gw_integration_site']."PaymentEntry.php' method='POST'>\n";
							//$list_pricepoints .= "<input type='hidden' name='mt_reference_id' value='".$subAccount['']."'>\n";
							$list_pricepoints .= "<input type='hidden' name='mt_subAccount' value='".$subAccount['rd_subName']."'>\n";
							$list_pricepoints .= "<input type='hidden' name='mt_prod_desc' value='".$subAccount['rd_description']."'>\n";
							$list_pricepoints .= "<input type='submit' name='Button' value='Purchase ".$subAccount['rd_description']."'>\n";
							$list_pricepoints .= "</form>\n";
							
							$list_pricepoints.="</textarea>";
							$list_pricepoints.="</td></tr>";
						
						}
						$list_pricepoints.="</table>";
						$show_val['list_pricepoints']=$list_pricepoints;
						
					}
					
					if($strMailConfir !="" && $companytype!="reseller") {
						$strMaildata =  get_email_template('merchant_welcome_letter',array());
						$str_current_path = "csv/merchant_registrationmail.htm";
						$create_file = fopen($str_current_path,'w');
						$file_content = $strMaildata;
						fwrite($create_file,$file_content);
						fclose($create_file);
					}else if($strMailReseller !="") {
							$strResellerMaildata =  get_email_template('reseller_welcome_letter',array());
							$str_current_path = "csv/reseller_registrationmail.htm";
							$create_file = fopen($str_current_path,'w');
							$file_content = $strResellerMaildata;
							fwrite($create_file,$file_content);
							fclose($create_file);
					}
					if($mail_confirm ==1) {
					
						unset($emailInfo); 
						
						$emailInfo['et_subject'] = $subject; // => Welcome to Etelegate, tech 
						$emailInfo['et_htmlformat'] = $txtBody; // => html
						foreach($show_val as $key => $data)
						{
							if (!$data) $data = "N/A";
							$emailInfo['et_subject']=str_replace("[".$key."]",$data,$emailInfo['et_subject']);
							$emailInfo['et_htmlformat']=str_replace("[".$key."]",$data,$emailInfo['et_htmlformat']);
						}
					
						$emailInfo['et_from'] = $fromaddress; // => sales@etelegate.com 
						$emailInfo['et_from_title'] = $fromaddress; // => Etelegate Sales 
						$emailInfo['et_htmlformat'] = stripslashes($emailInfo['et_htmlformat']);
								
						$Html2Text = new Html2Text ($emailInfo['et_htmlformat'], 900000); // 900 columns maximum
						$emailInfo['et_textformat']= $Html2Text->convert();
						$emailInfo['et_textformat'] = str_replace("&nbsp;"," ",$emailInfo['et_textformat']);
						
						$emailInfo['et_to'] = $to_id; // => techsupport@ecommerceglobal.com 
						$emailInfo['full_name'] = $to_id; // => Etelegate Merchant )
						 if(!send_email_data($emailInfo,$attachments))
						{
							$mails_sentid .= "'".$to_id."' could not be sent. No mail sent to $company_name.<br>";
						}
						else { 
							$mails_sentid .= $to_id."<br>";
							$mails_sent_num++;
						 }
					}
					else
					{
						$mails_sentid .= "'".$to_id."' is unsubscribed. No mail sent to $company_name.<br>";
					}
				} 
				
				if($mails_sent_num <= 0) {
					$msgtodisplay="Mails could not be sent.<br>".$mails_sentid;
				} else {
					$msgtodisplay .= $mails_sentid;
				}
				$outhtml="y";
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();
	$qrt_select_companies ="select distinct userid,companyname from cs_companydetails where 1 $ignore order by companyname ";
} else {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companytype =="RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companytype =="ET") {
		$qrt_select_subqry = " reseller_id is null";	
	} else if($companytype =="NE") {
		$qrt_select_subqry = " reseller_id is null and activeuser = 0";	
	} else {
		$qrt_select_subqry = "";	
	}
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
	} else {
		if($qrt_select_subqry =="") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}
	if ($cd_completion != -1) {
		if ($qrt_select_status_qry == "") {
			$qrt_select_status_qry = " ( cd_completion = '$cd_completion'";
		} else {
			$qrt_select_status_qry .= " or cd_completion = '$cd_completion'";
		}
	}


	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_merchant_qry ";
	} else {
		$str_total_query = "where 1 ";
	}
	if ($qrt_select_status_qry != "") {
		if ($str_total_query == "") {
			$str_total_query = "where $qrt_select_status_qry)";
		} else {
			$str_total_query .= " and $qrt_select_status_qry)";
		}
	}
	if($nonReseller==1) {
		if($str_total_query=="") {
			$qrt_select_nonreseller_qry = "where isnull(reseller_id)";
		}else {
			$qrt_select_nonreseller_qry = "and isnull(reseller_id)";
		}
	}
	if($selectReseller==1) {
		if($str_total_query=="") {
			$qrt_select_nonreseller_qry = "where isnull(reseller_id)";
		}else {
			$qrt_select_nonreseller_qry = "and reseller_id <>''";
		}
	}
	$qrt_select_companies="select distinct userid,companyname from cs_companydetails $str_total_query $qrt_select_nonreseller_qry $ignore order by companyname";
}
if($allCompanies!="") {
	$qrt_select_companies="select distinct userid,companyname from cs_companydetails where 1 $ignore order by companyname";
}
if($companytype=="reseller") {
	$fromaddress = $_SESSION['gw_emails_sales'];
	$qrt_select_companies="select distinct reseller_id,reseller_companyname from cs_resellerdetails where 1 $search_reseller_active_status order by reseller_companyname";
}
	//print($qrt_select_companies);

?>

<script language="javascript">
function validation(){
trimSpace(document.frmSelComp.fromaddress)
trimSpace(document.frmSelComp.trans_type)
trimSpace(document.frmSelComp.subject)



	  if(document.frmSelComp.fromaddress.value==""){
		alert("Please enter From address")
		document.frmSelComp.fromaddress.focus();
		return false;
	  }
	  if(document.frmSelComp.trans_type.value!="Submit") {

	  if(document.frmSelComp.subject.value==""){
		alert("Please enter the subject")
		document.frmSelComp.subject.focus();
		return false;
	  }
	  if(document.frmSelComp.txtBody.value==""){
		//alert("Please enter details")
		//document.frmSelComp.txtBody.focus();
		//return false;
	  }
	  if(document.frmSelComp.optAttachments.length>=0){
		strFiles = ",";	
		for(i=0;i<document.frmSelComp.optAttachments.length;i++){
			if(document.frmSelComp.optAttachments[i].value != ""){
				strFiles = strFiles + document.frmSelComp.optAttachments[i].value+",";
			}
	
		}
		
		document.frmSelComp.attachments.value = strFiles;
	 }else{
		document.frmSelComp.attachments.value = ",";
	 }
 }
 viewCompany();
 viewReseller();
 
}
function funcUpload()
{
	objForm = document.frmSelComp;
	window.open("uploadmailattach.php",null,"height=125,width=400,status=yes,toolbar=no,menubar=no,location=no,scrollbars=0");
}
function funcUploadConfirmation(attach) {
	objForm = document.frmSelComp;
	if(attach=="reseller") {
		if(objForm.confirmer_resel.checked) {
			strFileName ="reseller_registrationmail.htm";
			funcAddValue(strFileName);
		} else {
			objElement = document.frmSelComp.optAttachments;
			if(objElement.length > 0) {
				for (iLength=0;iLength<objElement.length;iLength++) {
					if(objElement.options[iLength].text =="reseller_registrationmail.htm"){
						objElement.options[iLength].selected = true;
						objElement.remove(objElement.selectedIndex);
					}
				}
			}
		}
	} else {
		if(objForm.confirmer.checked) {
			strFileName ="merchant_registrationmail.htm";
			funcAddValue(strFileName);
		} else {
			objElement = document.frmSelComp.optAttachments;
			if(objElement.length > 0) {
				for (iLength=0;iLength<objElement.length;iLength++) {
					if(objElement.options[iLength].text =="merchant_registrationmail.htm"){
						objElement.options[iLength].selected = true;
						objElement.remove(objElement.selectedIndex);
					}
				}
			}
		}
	}
}
function funcAddValue(strFileName)
{
	objElement = document.frmSelComp.optAttachments
	iLength = objElement.length;
	objElement.length = iLength+1;
	objElement.options[iLength].value = strFileName;
	objElement.options[iLength].text = strFileName;
}
function funcDelete(){
	objElement = document.frmSelComp.optAttachments;
	if(objElement.selectedIndex >=0){
		objElement.remove(objElement.selectedIndex);
	}
//	objElement.options[objElement.selectedIndex].remove;

}
function Displaycompany(){
	if(document.frmSelComp.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.frmSelComp.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.frmSelComp.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
}
function Displaycompanytype() {
	document.frmSelComp.trans_type.value="Submit";
	document.frmSelComp.submit();
}

function viewtemplate(type)
{	
	var isValid = false;
	var companyid;
	var obj_element = document.frmSelComp.elements[9];
	for (i = 0; i < obj_element.length; i++) {
		if(obj_element[i].selected) {
			isValid = true;
		}
	} 
	if(type =="login") {
		if (isValid) {
		   companyid = document.frmSelComp.elements[9].value;
		   advtWnd=window.open("reply_registrationmailview.php?company="+companyid,"advtWndName","'status=1,scrollbars=1,width=800,height=640,left=0,top=0'");
		   advtWnd.focus();
		} else {
		   companyid = "";
		   advtWnd=window.open("reply_registrationmailview.php?company="+companyid,"advtWndName","'status=1,scrollbars=1,width=800,height=640,left=0,top=0'");
		   advtWnd.focus();
		}
	} else if(type=="reseller") {
		if (isValid) {
		   companyid = document.frmSelComp.elements[9].value;
		   advtWnd=window.open("reply_registrationmailview.php?type=reseller","advtWndName","'status=1,scrollbars=1,width=800,height=640,left=0,top=0'");
		   advtWnd.focus();
		} else {
		   companyid = "";
		   advtWnd=window.open("reply_registrationmailview.php?type=reseller","advtWndName","'status=1,scrollbars=1,width=800,height=640,left=0,top=0'");
		   advtWnd.focus();
		}

	} else {
		if (isValid) {
		   companyid = document.frmSelComp.elements[9].value;
		   advtWnd=window.open("reply_registrationmailview.php?type=ecom","advtWndName","'status=1,scrollbars=1,width=800,height=640,left=0,top=0'");
		   advtWnd.focus();
		} else {
		   companyid = "";
		   advtWnd=window.open("reply_registrationmailview.php?type=ecom","advtWndName","'status=1,scrollbars=1,width=800,height=640,left=0,top=0'");
		   advtWnd.focus();
		}
	}
}
function trimSpace(frmElement)
{
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);

     frmElement.value = stringToTrim.substring(front, back);
}
function updateBatchlist(obj)
{
	total = 0;
	re = /[^\d]+/;
	id_array = obj.value.split(re);
	clist = document.getElementById('all');
	for(i=0;i<clist.length;i++)
	{
		clist[i].selected = false;
		for(j=0;j<id_array.length;j++)
		{
			if(clist[i].value==id_array[j]) 
			{	
				total++;
				clist[i].selected = true;
			}
		}
	}
	alert(total+" Companys Selected.");
	obj.value = "";
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="75%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
	<table width="80%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="<?=$tmpl_dir?>/images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="<?=$tmpl_dir?>/images/menucenterbg.gif" ><span class="whitehd">Send 
            Email </span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="<?=$tmpl_dir?>/images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="<?=$tmpl_dir?>/images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">


  <form action="" method="post" onsubmit="return validation()" name="frmSelComp" id="frmSelComp" enctype="multipart/form-data" >
	<input type="hidden" name="attachments" value="">
	<input type="hidden" name="trans_type" value="">
  <table height="100%" width="100%" cellspacing="0" cellpadding="0" align="center">
<br>   
<tr><td colspan="2" align="center" valign="middle" width="650">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			  <tr><td align="right" valign="center" height="30" width="200"><font face="verdana" size="1">From :</font></td><td align="left" height="30">&nbsp;<input type="text" maxlength="230" name="fromaddress" style="font-family:arial;font-size:10px;width:280px" value="<?= $fromaddress ?>"></input></td></tr>
			<!--	 <tr>
				<td height="30" valign="middle" align="right" width="150"><font face="verdana" size="1">Wire Setup Fee Status :</font></td>
				<td align="left"  width="350">&nbsp;<select name="wire_fee_status" style="font-family:verdana;font-size:10px;WIDTH: 150px" onChange="Displaycompanytype();">
				<option value="A" <?= $str_wire_fee_status == "A" ? "selected" : ""?>>All</option>
				<option value="R" <?= $str_wire_fee_status == "R" ? "selected" : ""?>>Ready</option>
				<option value="N" <?= $str_wire_fee_status == "N" ? "selected" : ""?>>Not Ready</option>
					</select></td>
				</tr> -->
				<tr>
				<td height="60" colspan="2"><?php
				  

echo genCompanyViewTable('massmail1.php','massmail1.php','full');
genResellerViewTable('','massmail1.php','massmail1.php',$adminInfo['li_level'],NULL,false);
				  ?></td>
				 </tr>
			  <tr><td align="right" valign="center" height="30"><font face="verdana" size="1">Subject :</font></td><td align="left" height="30" width="350">&nbsp;<input type="text" maxlength="230" name="subject" style="font-family:arial;font-size:10px;width:280px" value="<?= $subject ?>"></input></td></tr>
		   <tr><td align="center" valign="center" height="30" colspan="2">
		   

		   <textarea  wrap="virtual" name="txtBody" id="txtBody" cols="3" rows="10"><?= $txtBody ?></textarea>
		   
<script type="text/javascript" src="<?=$etel_domain_path?>/fckedit/fckeditor.js"></script>
<script type="text/javascript">
<!--
// Automatically calculates the editor base path based on the _samples directory.
// This is usefull only for these samples. A real application should use something like this:
// oFCKeditor.BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
var sBasePath = '<?=$etel_domain_path?>/fckedit/';
var oFCKeditor = new FCKeditor( 'txtBody','100%','400' ) ;//( instanceName, width, height, toolbarSet, value )
oFCKeditor.BasePath	= sBasePath ;
oFCKeditor.ReplaceTextarea() ;
//-->

document.getElementById('viewcompany').style.visibility='hidden';
document.getElementById('viewreseller').style.visibility='hidden';
document.getElementById('resellername').options[0].selected=false;
document.getElementById('companyname').options[0].selected=false;
</script>
		   
		   </td></tr>
		  <tr align="center">
		  	<td valign="middle" colspan="2"><font face="verdana" size="1">Variables: [username], [password], [companyname], [list_pricepoints] </font></td>

		  </tr>
		  <tr>
		  	<td align="right" valign="middle" height="100"><font face="verdana" size="1">Attachments :</font></td>
		  	<td>
				&nbsp;<select name="optAttachments" size="5" style="width:200"></select>
			</td>
		  </tr>
<?php  if($companytype!="reseller") { ?>
<?php } else { ?>
<?php } ?>		 
		  <tr>
		  	<td align="center" valign="middle" colspan="2" height="40">
				<a href="javascript:funcUpload()"><img SRC="<?=$tmpl_dir?>/images/uploadattachment.jpg" border="0" alt="Upload Attachments"></a>&nbsp;
				<a href="javascript:funcDelete()"><img SRC="<?=$tmpl_dir?>/images/delete.jpg" border="0" alt="Delete"></a>
		  </td>
		  </tr>			  
		  <tr><td align="center" valign="top" height="30" colspan="2"><input type="image" id="sendmail" SRC="<?=$tmpl_dir?>/images/send.jpg"></input>
		  </td></tr>
	  </table>
  </td></tr></table></form>
			   
	</td>
      </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="<?=$tmpl_dir?>/images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
    </table><br>
    </td>
     </tr>
</table>

<?
include 'includes/footer.php';
?>