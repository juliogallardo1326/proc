<?php
	include("includes/sessioncheck.php");
	require_once("includes/dbconnection.php");
	include("includes/header.php");
require_once( 'includes/function.php');
$agree_contract = (isset($HTTP_GET_VARS['agree_contract'])?Trim($HTTP_GET_VARS['agree_contract']):"0");
if($agree_contract)
{
	$cd_contract_ip = getRealIp();
	$cd_contract_date = time();
}
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$completedUploadingDoc = (isset($HTTP_GET_VARS['completed'])?Trim($HTTP_GET_VARS['completed']):"");
$str_qry = "update cs_companydetails set merchant_contract_agree = '$agree_contract',cd_contract_ip='$cd_contract_ip',cd_contract_date='$cd_contract_date' where userId = '$sessionlogin'";
if (!mysql_query($str_qry,$cnn_cs)) {			
dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

$str_completed_uploading = (isset($HTTP_GET_VARS['completedUploading'])?Trim($HTTP_GET_VARS['completedUploading']):"");
if($completedUploadingDoc !="") {
	$str_qry = "update cs_companydetails set completed_uploading = '$str_completed_uploading' where userId = $sessionlogin";
	if (!mysql_query($str_qry,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
}
$str_qry = "select completed_uploading from cs_companydetails where userId = $sessionlogin";
if (!$sql_select_val = mysql_query($str_qry,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}else {
	$confirm ="";
	if(mysql_result($sql_select_val,0,0)=="Y") {
		$confirm ="checked";
	} 
}
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$setup_amount=0;
$strCompanyType = funcGetValueByQuery("select transaction_type from cs_companydetails where userId=$sessionlogin",$cnn_cs);
$setup_amount = funcGetValueByQuery("select setupfee from cs_setupfee where company_type_short='$strCompanyType'",$cnn_cs);
$setup_amount = number_format ($setup_amount,2,".",",");

$msg = $_GET["msg"]."<br>";

?>
<script language="JavaScript">
function submitPage() {

	if(document.confirm_form.completedUploading.checked) {
	   advtWnd=window.open("Confirmation_Message.php","Message","'status=1,scrollbars=1,width=500,height=150,left=0,top=0'");
	   advtWnd.focus();
		document.confirm_form.completed.value ="submit";
		document.confirm_form.action="confirmation.php";
		document.confirm_form.submit();
		return true;
	} else {
		document.confirm_form.completed.value ="submit";
		document.confirm_form.action="confirmation.php";
		document.confirm_form.submit();
		return true;
	}
}
</script>
<link href="styles/text.css" rel="stylesheet" type="text/css">

<form name="confirm_form" method="get" action="integrate.php?type=testMode">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
    <tr>
   		 <td width="100%" valign="top" align="center"><?=$msg?><br>
		<table border="0" cellpadding="0" cellspacing="0" width="85%" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
              <img border="0" src="images/spacer.gif" width="1" height="1">
              </td>
            </tr>
             <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
            </tr>
             <tr>
              <td width="100%" valign="middle" align="left" height="35" class="disctxhd">
                           &nbsp;&nbsp;&nbsp; Wire Transfer
                           </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center">
				<table border="0" cellspacing="0" cellpadding="0" width="95%">
				   <tr>
				<td><p align="justify"><span class="normaltext1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				  <?=$_SESSION['gw_title']?> Guarantees unconditional merchant approval pending the following:<br>
				  <br>
				</span>
				  <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center" class="ratebd" >
					<tr>
					  <td width="100%"  class="bentx" bgcolor="#F8F9FC"><br><strong>
					  <ul>
					  <li> Merchant Application is filled out completely.</li><br><br>
				 <li> ALL Required Documents are uploaded.</li><br><br>
				 <li> 6 months previous processing with acceptable chargeback %/(Business plan for new companies).</li> <br><br>
				 <li> SET-UP Fee is paid in full.</li><br><br>
				 </ul> </strong></td>
					</tr>
				  </table>
				  <p align="justify" style="margin: 10">
				  <span class="bentx">
				<br><img border="0" src="images/wiretransfer.jpg" align="left" width="150" height="119"><br>
				<br>
				The <?=$_SESSION['gw_title']?> GUARANTEE:
				<!--Set up fee is 100% REFUNDABLE if <?=$_SESSION['gw_title']?> does not provide COMPLETE credit card/ACH processing to merchant within 14 days from receiving funds and complete documents.-->Set up fee is 100% REFUNDABLE if <?=$_SESSION['gw_title']?> does not provide COMPLETE processing to merchant upon bank approval, set up fee paid, and complete documents uploaded. The set-up fee is NON-REFUNDABLE if merchant is given an account and does not choose to process using the account.<br>
				<br>
                      SET UP FEE-Each new merchant will be required to pay a mandatory 
                      <?=$setup_amount?> USD setup fee to establish new offshore merchant 
                      account.</span> </p>
				</td>
			  </tr>
			  <tr><td><p align="justify" style="margin: 10">
				  <span class="bentx">	<input type="checkbox" name="completedUploading" value="Y" onClick="submitPage();" <?=$confirm?>>&nbsp;I have completed the merchant application and have uploaded all required documents and am now ready to wire the setup fee.</span> </p>
			</td></tr>
				  <tr><td align="center" valign="middle" width="100%" height="40"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>&nbsp;&nbsp;<input type="image" border="0" src='images/continue.gif'></td></tr>
			</table>
              </td>
            </tr>
          </table><br>
	 </td>
  	</tr>
	</table>
	<input type="hidden" name="completed" value="">
	</form>
<?php
	include("includes/footer.php");
/*
if($completedUploadingDoc =="submit") {
	$qrt_select_uploading = "select completed_merchant_application, num_documents_uploaded, completed_uploading from cs_companydetails where userId = $sessionlogin";
		if (!$run_select_sql = mysql_query($qrt_select_uploading,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		}else {
			if((mysql_result($run_select_sql,0,0)== 1) && (mysql_result($run_select_sql,0,1)==4) && (mysql_result($run_select_sql,0,2)=='Y')) {
?>			
<script> 
		   advtWnd=window.open("Confirmation_Message.php","eTelegateMessage","'status=1,scrollbars=1,width=500,height=150,left=0,top=0'");
		   advtWnd.focus();

</script>		
<?php					
			}
		}
}
*/
?>
