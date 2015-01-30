<?php
		$rootdir="../";
		$headerInclude = "service";
		include($rootdir."includes/sessioncheckserviceuser.php");
		include($rootdir."includes/dbconnection.php");
		require_once($rootdir."includes/function.php");
		include($rootdir."includes/header.php");
		$str_duration = $_SESSION['duration_start'];
		if(!$str_duration)
		{
			$str_hour = "0";
			$str_min = "00";
			$str_sec = "0";
		}
		else
		{
			$str_hour = floor((time()-$str_duration)/(60*60))%24;
			if (strlen($str_hour) == 1) $str_hour = "0".$str_hour;
			$str_min = floor((time()-$str_duration)/(60))%60;
			if (strlen($str_min) == 1) $str_min = "0".$str_min;
			$str_sec = floor((time()-$str_duration))%60;
			if (strlen($str_sec) == 1) $str_sec = "0".$str_sec;
			
			$strCallDuration = $str_hour.":".$str_min.":".$str_sec;
		
			$str_hour = floor((time()-$str_duration)/(60*60))%24;
			//if ($str_hour<10) $str_hour = "0".$str_hour;
			$str_min = floor((time()-$str_duration)/(60))%60;
			//if ($str_min<10) $str_min = "0".$str_min;
			$str_sec = floor((time()-$str_duration))%60;
			//if ($str_sec<10) $str_sec = "0".$str_sec;
		}
		$strBillDate = "";
		$i_gateway_id = -1;
		
		$cancelBtnTxt = "Refund Transaction";
?>
<style type="text/css">
<!--
.style1 {color: #FF0000}
.style2 {font-size:12px}
-->
</style>
<div id="time" align="left" >
  <table>
    <tr>
      <td><form name="form_counter">
          <font face="verdana" color="#448A99" size="2"><b>Time Elapsed:&nbsp;</b></font>
          <input name="counter" type="text" style="border:0px;font-face:verdana;font-weight:bold;Color:#448A99" value="" size="13">
        </form></td>
    </tr>
  </table>
</div>
<script language="javascript" src="../scripts/general.js"></script>
<script language="JavaScript">
function func_validate(obj_form)
{
	var b_correct = true;
	obj_element = obj_form.txtName;
	if(b_correct && (obj_element.value == ""))
	{
		alert("Please enter name");
		b_correct = false;
		obj_element.focus();
	}	
	obj_element = obj_form.txtPhone
	if(b_correct && (obj_element.value == ""))
	{
		alert("Please enter phone number");
		b_correct = false;
		obj_element.focus();
	}
	obj_element = obj_form.txtNotes
	if(b_correct && (obj_element.value == ""))
	{
		alert("Please enter notes");
		b_correct = false;
		obj_element.focus();
	}
	if(b_correct)
	{
		stopTimer();
		obj_form.hid_counter.value = document.form_counter.counter.value;
	}
	return b_correct;
}
function func_add_notes(obj_form,orig_bill_date)
{
	var isValid = true;
	//if((obj_form.hid_cancel.value = "Y") && (obj_form.txtNotes.value == ""))
	if(!obj_form.chkCallBack.checked && !obj_form.chkSolved.checked && obj_form.chkCancel != null && !obj_form.chkCancel.checked)
	{
		isValid = false;
		alert("Please check either the 'Company Call Back', 'Solved', or 'Refund' Button");
	}
	if(isValid && obj_form.txtNotes.value == "")
	{
		isValid = false;
		alert("Please enter the notes");
		obj_form.txtNotes.focus();
	}
	if(isValid && obj_form.chkChangedBillDate != null)
	{
		if(obj_form.chkChangedBillDate.checked)
		{
			var new_bill_day = obj_form.opt_bill_day[obj_form.opt_bill_day.selectedIndex].value;
			var new_bill_month = obj_form.opt_bill_month[obj_form.opt_bill_month.selectedIndex].value;
			var new_bill_year = obj_form.opt_bill_year[obj_form.opt_bill_year.selectedIndex].value;
			if(new_bill_day.length <= 1)
				new_bill_day = "0" + new_bill_day;
			if(new_bill_month.length <= 1)
				new_bill_month = "0" + new_bill_month;
			var new_bill_date = new_bill_year + "-" +new_bill_month + "-" +new_bill_day;
			if(orig_bill_date == new_bill_date)
			{
				isValid = false;
				alert("Please select a new Bill Date");
				obj_form.txtNotes.focus();
			}

			if(isValid && !ValidateDateBox("billing date",obj_form,new_bill_day,new_bill_month,new_bill_year,1,1,0))
			{
				obj_form.opt_bill_month.focus();
				isValid = false;		
			}
		}
	}
	if(isValid)
	{
		stopTimer();
		obj_form.hid_counter.value = document.form_counter.counter.value;
		obj_form.action = "addnotes.php";
		obj_form.method="post";
		obj_form.submit();
	}	
}
/*function funcShowPreviousCalls(iTransactionId)
{
	window.open("previouscalls.php?id="+iTransactionId,null,"height=300,width=600,status=yes,toolbar=no,menubar=no,location=no,scrollbars=1");
}*/
function funcShowPreviousCalls(iTransactionId,phoneNumber,searchMode,callNoteId)
{
	var duration = document.form_counter.counter.value;
	window.location="previouscalls.php?id="+iTransactionId+"&phoneNumber="+phoneNumber+"&searchMode="+searchMode+"&callNoteId="+callNoteId+"&duration="+duration;
}
function funcCancel(obj_form)
{
	if(confirm("Are you sure you would like to refund this order?")){
		obj_form.chkCancel.checked = true;
	}
	else{
		obj_form.chkCancel.checked = false;
	}
}
function funcCallBack(iTransactionId)
{
	window.open("callback.php?id="+iTransactionId,null,"height=100,width=400,status=yes,toolbar=no,menubar=no,location=no");
}

function cancel()
{
	if( document.frm_result.chkCancel.checked == true )
	{
		document.frm_result.chkCancel.checked = false;
		if( confirm("Are you sure you would like to refund this order?") )
			document.frm_result.chkCancel.checked = true;
	}
}

var hour = "<?=$str_hour?>";
var min = "<?=$str_min?>";
var sec = "<?=$str_sec?>";
var timerId = null;
function timer(){
if ((min < 10) && (min != "00")){
	dismin = "0" + min
}
else{
	dismin = min
}

	dissec = (sec < 10) ? sec = "0" + sec : sec
	dishour = (hour < 10) ? "0" + hour : hour
	document.form_counter.counter.value = dishour + ":" + dismin + ":" + dissec

	if (sec < 59){
		sec++
	}
	else{
		sec = "0"
		min++
		if (min > 59){
			min = "00"
			hour++
		}
	}
timerId = window.setTimeout("timer()",1000); 

}
window.setTimeout("timer()",0); 

function stopTimer()
{
	clearTimeout(timerId);
}

</script>
<?php
			$txt_cc = (isset($HTTP_GET_VARS["txt_cc"])?Trim($HTTP_GET_VARS["txt_cc"]):"");
			$txt_telephone = (isset($HTTP_GET_VARS["txt_telephone"])?Trim($HTTP_GET_VARS["txt_telephone"]):"");
			$txt_email = (isset($HTTP_GET_VARS["txt_email"])?Trim($HTTP_GET_VARS["txt_email"]):"");
			$txt_reference = (isset($HTTP_GET_VARS["txt_reference"])?Trim($HTTP_GET_VARS["txt_reference"]):"");
			$txt_cancel = (isset($HTTP_GET_VARS["txt_cancel"])?Trim($HTTP_GET_VARS["txt_cancel"]):"");
			$txt_checkingaccount = (isset($HTTP_GET_VARS["txt_checkingaccount"])?Trim($HTTP_GET_VARS["txt_checkingaccount"]):"");
			$txt_bankrouting = (isset($HTTP_GET_VARS["txt_bankrouting"])?Trim($HTTP_GET_VARS["txt_bankrouting"]):"");


		$cancelBtnTxt = "Cancel Transaction";
		$refundBtnTxt = "Request Refund";

			
			$str_qry = "";
			if($txt_cc) $str_qry .= " AND t.CCnumber = '".etelEnc(strip_chars($txt_cc))."' ";
			if($txt_telephone) $str_qry .= " AND t.phonenumber = '".strip_chars($txt_telephone)."'";
			if($txt_email) $str_qry .= " AND t.email = '$txt_email' ";
			if($txt_reference) $str_qry .= " AND t.reference_number = '".strtoupper($txt_reference)."' ";
			if($txt_cancel) $str_qry .= " AND t.cancel_refer_num = '$txt_cancel' ";
			if(($txt_checkingaccount) && ($txt_bankrouting)) $str_qry .= " AND t.bankaccountnumber = '".strip_chars($txt_checkingaccount)."'  AND bankroutingcode = '$txt_bankrouting' ";
			
				if (!$str_qry) $str_qry = " And 0 ";
			
				$i_customer_service_id = -1;
				if(isset($_SESSION["sessionService"]))
				{
					$i_customer_service_id = 0;
				}
				else if(isset($_SESSION["sessionServiceUserId"]))
				{
					$i_customer_service_id = $_SESSION["sessionServiceUserId"];
				}
				if($i_customer_service_id == 0)
				{
					$str_company_ids = "A";
				}
				if($str_company_ids != "A")
				{
					$qry_select = "select company_ids, gateway_id from cs_customerserviceusers where id = $i_customer_service_id";
					if(!($rst_select = mysql_query($qry_select,$cnn_cs)))
					{
						print("Can not execute query: ".mysql_error());
						exit();
					}
					if(mysql_num_rows($rst_select))
					{
						$str_company_ids = mysql_result($rst_select,0,0);
						$i_gateway_id = mysql_result($rst_select,0,1);
					}
				}
				$table = "`cs_transactiondetails` as t ";
				//$table .= "left join `cs_companydetails` as c on (`company_user_id` = c.userId OR t.`userId` = c.userId) left join `etel_dbsmain`.`cs_company_sites` as s on cs_ID = td_site_ID ";
				$qry_select = "SELECT transactionId,productdescription,transactionDate FROM $table WHERE t.td_recur_processed=0 AND t.status='A' $str_qry";
				//$qry_select .= " and admin_approval_for_cancellation <> 'P'";
				//print $qry_select;
				$strBillDate = "";
				if(!($qrt_select_run = mysql_query($qry_select,$cnn_cs)))
				{
					print("Can not execute query: ".mysql_error());
					exit();
				}
				//********** IF THE NUMBER FOUND IN DATABASE ***********************
				if(mysql_num_rows($qrt_select_run))
				{
					
		?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
<tr>
  <td width="83%" valign="top" align="center"  >&nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <tr>
      <td colspan="2" height="25"></td>
    </tr>
    <tr>
      <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
      <td height="22" align="center" valign="middle" width="20%" background="../images/menucenterbg.gif" ><span class="whitehd">Search&nbsp;Result</span></td>
      <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
      <td height="22" align="left" valign="top" width="75%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
      <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
    </tr>
    <tr>
      <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>
        <table border="0" cellpadding="0" cellspacing="0" width="842" align="center" bgcolor="#658343" class="blkbd1">
          <tr>
            <td height="15" class="blackbtbd" bgcolor="#4A9FA6"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
          </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="842" align="center" class="blkbd1" height="460">
          <tr>
          
          <td height="25" valign="top" align="center" width="192" bgcolor="#FFFFFF"><table border="0" cellpadding="0" width="100%" height="249">
              <tr>
                <td width="99%" bgcolor="#B7D0DD" height="14"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
                <td width="1%"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr>
                <td width="99%" bgcolor="#85AFBC" height="16"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
                <td width="1%"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr>
                <td width="99%" bgcolor="#85AFBC" height="18"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
                <td width="1%"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
              </tr>
              <tr>
                <td width="100%" height="178" colspan="2" valign="top"><img border="0" src="../images/service_pic.jpg" width="160" height="176"></td>
              </tr>
            </table>
            <p>&nbsp;</p></td>
          <td height="25" valign="top" align="center" width="580">
          <br>
          <?php
$ResultTotal = mysql_num_rows($qrt_select_run);
$ResultNum = 1;
if ($ResultTotal>1){
	?>
          <span class="tdbdr"><font color="#001188">Total Results:
          <?=$ResultTotal?>
          </font></span><br>
          <br>
          <?php
	for($i=1;$i<=$ResultTotal;$i++)
	{
		$prodesc = mysql_result($qrt_select_run,($i-1),'productdescription');
		if (!$prodesc) $prodesc = "No Description";
		$prodesc = substr($prodesc,0,50);
		print "<a href='#res_$i' >".date("F j, Y G:i:s",strtotime(mysql_result($qrt_select_run,$i-1,'transactionDate')))."</a> - $prodesc<br>";
	}
	mysql_data_seek($qrt_select_run,0);

}
if($msgtodisplay !="") {
	print $msgtodisplay;
} else {
	while($tinfo = mysql_fetch_assoc($qrt_select_run)) {
	$trans_id = $tinfo['transactionId'];
	$show_select_val = getTransactionInfo($trans_id);
	if (!is_array($show_select_val)) continue;
	if(!$logged) 
	{
		toLog('order','service', "Customer Service ".$show_select_val['fullname']." views Transaction ID $trans_id", $transInfo['userId']);

		$sql="INSERT INTO `cs_callnotes` (`transaction_id` , `call_date_time` , `service_notes` , `cancel_status` , `customer_notes` , `solved` , `is_bill_date_changed` , `call_duration` , `customer_service_id` , `prev_bill_date` , `dnc`, `cn_type` )
			VALUES ('$trans_id', NOW() , 'Customer Service Found Transaction', '', '', '' , '', '$strCallDuration', '', '', '', 'foundcall');";
		$qry_callnotes = mysql_query($sql) or die("Cannot execute query ");
		$note_id = mysql_insert_id();
	}
	$logged = 1;
	
	if($show_select_val['checkorcard']=="H") $charge_type = "Credit Card";
	if($show_select_val['checkorcard']=="C") $charge_type = "Check";
	if($show_select_val['checkorcard']=="W") $charge_type = "ETEL900";

	$ResultDate = @date("F j, Y",strtotime( $show_select_val['billingDate']));
	$is_canceled = $show_select_val['cancelstatus'];
	$td_rebill_enabled = $show_select_val['td_enable_rebill'];

?>
          <span class="tdbdr"><font color="#001188">Result '
          <?=$ResultDate?>
          '</font></span><br>
          <form name="FrmTransaction" action="merchantcancel.php" method="post" onsubmit="return submitOrder(this)">
            <input type="hidden" name="note_id" value="<?=$note_id?>">
            <input type="hidden" name="transactionId" value="<?=$show_select_val['transactionId']?>">
            <a name="res_<?=$ResultNum++?>"></a>
            <table width="580" cellpadding="2" cellspacing="0" style="border:1px solid black" dwcopytype="CopyTableCell" align="center">
              <tr align="center" valign="middle" bgcolor="#3D8287">
                <td colspan="2" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Customer Information</strong></font></td>
              </tr>
              <tr>
                <td width="50%" align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First Name : </font></td>
                <td width="50%" valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                  <?=$show_select_val['name'];?>
                  </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last Name :</font></td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                  <?=$show_select_val['surname'];?>
                  </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Address :</font></td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188"> <font color="#001188">&nbsp;
                  <?=$show_select_val['address'];?>
                  </font></font></font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP Address :</font></td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp;
                  <?=$show_select_val['ipaddress'];?>
                  </font></font></td>
              </tr>
              <tr bgcolor="#3D8287">
                <td colspan="2" align="center" valign="middle" class="tdbdr"><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Payment Information</strong></font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Charge Type :</font><br></td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print $charge_type;?> </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Charge Amount :</font><br></td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; $<?php print formatMoney( $show_select_val['amount']);?> </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Name that appeared on billing statement:</font></td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp;
                  <?=$show_select_val['billingdescriptor'];?>
                  </font> </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing Date :</font></td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp; <?php print date("F j, Y",strtotime( $show_select_val['billingDate']));?>
                  <input name="billDate" type="hidden" id="billDate" value="<?=$show_select_val['billingDate'];?>">
                  </font></td>
              </tr>
              <? if($show_select_val['cancellationDate']>"0000-00-00 00:00:00") { ?>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Cancelation Date :</font></td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp;
                  <?=$show_select_val['cancellationDate'];?>
                  </font> </font></td>
              </tr>
              <? } ?>
              <tr bgcolor="#3D8287">
                <td colspan="2" align="center" valign="middle" class="tdbdr"><p><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Purchase Information</strong></font></p></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Reference Number : </font></td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp;
                  <?=$show_select_val['reference_number'];?>
                  </font> </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Company Name : </font></td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp;
                  <?=$show_select_val['companyname'];?>
                  </font> </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Website URL : </font></td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp; <a href='<?=$show_select_val['cs_URL'];?>'>
                  <?=str_replace("http://","",$show_select_val['cs_URL']);?>
                  </a> </font> </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Website Customer Service Email Address : </font></td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp;
                  <?=$show_select_val['contact_email'];?>
                  </font> </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Website Customer Service Telephone Number : </font></td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                  <?=$show_select_val['customer_service_phone'];?>
                  </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Product Description : </font></td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                  <?=$show_select_val['productdescription'];?>
                  </font></td>
              </tr>
              <?php if ($show_select_val['td_username']) { ?>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">UserName : </font></td>
                <td valign="middle" class="tdbdr">&nbsp;&nbsp;
                  <?=$show_select_val['td_username']?>
                </td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Password : </font></td>
                <td valign="middle" class="tdbdr">&nbsp;&nbsp;
                  <?=$show_select_val['td_password']?>
                </td>
              </tr>
              <?php } ?>
              <?php if($td_rebill_enabled == "1"){ ?>
              <tr bgcolor="#3D8287">
                <td colspan="2" align="center" valign="middle" class="tdbdr"><p><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> Transaction Schedule </strong></font></p></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Payment Schedule : </td>
                <td valign="middle" class="tdbdr">&nbsp;&nbsp;
                  <?=$show_select_val['subAcc']['payment_schedule']?>
                </td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Next billing date: </td>
                <td valign="middle" class="tdbdr">&nbsp;&nbsp;<?print date("F j, Y",strtotime( $show_select_val['td_recur_next_date']));?> </td>
              </tr>
              <tr bgcolor="#3D8287">
                <td colspan="2" align="center" valign="middle" class="tdbdr"><p><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> Cancel Subscription </strong></font></p></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Please Select One : </td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp;</font>
                  <select name="selectRefundReason" id="selectRefundReason" onBlur="updatevalid(this)" onFocus="updatevalid(this)" onChange="updatevalid(this)" title="reqmenu" >
                    <option value="Cant get in touch with Merchant (.Net Refund)">Cant get in touch with Merchant</option>
                    <option value="Changed Mind (.Net Refund)" selected>Changed Mind</option>
                    <option value="Fraudulent (.Net Refund)">Fraudulent </option>
                    <option value="Spouse (.Net Refund)">Spouse</option>
                    <option value="Did not recieve Product/Package (.Net Refund)">Did not recieve Product/Package</option>
                    <option value="Other (.Net Refund)">Other</option>
                  </select>
                  </font></td>
              </tr>
              <tr>
                <td align="right" valign="top" class="tdbdr1">If Other, please describe : </td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                  <textarea name="RefundNotes" id="RefundNotes" disabled title="noeffort" ></textarea>
                  </font></td>
              </tr>
              <tr>
                <td align="right" valign="top" class="tdbdr1">&nbsp;</td>
                <td valign="middle" class="tdbdr">&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="cancel" type="submit" id="cancel" value="<?=$cancelBtnTxt?>">
                  </font> </td>
              </tr>
              <?php } else if ($show_select_val['subAcc']['recur_day']) {?>
              <tr bgcolor="#3D8287">
                <td colspan="2" align="center" valign="middle" class="tdbdr"><p><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> Subscription Information </strong></font></p></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Payment Schedule : </td>
                <td valign="middle" class="tdbdr"><font color="#001188"><font color="#001188">&nbsp;</font></font><span class="style1">Canceled</span> </td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Next billing date: </td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;You will not be rebilled for this subscription </font></td>
              </tr>
              <?php } ?>
              <?php if($is_canceled == "Y") { ?>
              <tr bgcolor="#3D8287">
                <td colspan="2" align="center" valign="middle" class="tdbdr"><p><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> Status </strong></font></p></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Status : </td>
                <td valign="middle" class="tdbdr">&nbsp;<span class="style1">Refunded</span> </td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Refunded On : </td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;<?php print date("F j, Y",strtotime( $show_select_val['cancellationDate']));?></font> </td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Refund Reference No. : </td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                  <?=$show_select_val['cancel_refer_num']?>
                  </font> </td>
              </tr>
              <?php } else { ?>
              <tr bgcolor="#3D8287">
                <td colspan="2" align="center" valign="middle" class="tdbdr"><p><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Request Refund</strong></font></p></td>
              </tr>
              <tr>
                <td colspan="2" align="right" valign="middle" class="tdbdr1">If you have any additional questions regarding your transaction, please use this form to contact customer service. <font color="#001188"><font color="#001188">&nbsp;</font> </font></td>
              </tr>
              <tr>
                <td align="right" valign="middle" class="tdbdr1">Contact me by: </td>
                <td valign="middle" class="tdbdr">&nbsp;
                  <select name="contactmethod" id="contactmethod" onBlur="updatevalid(this)" onFocus="updatevalid(this)" onChange="updatevalid(this)" title="reqmenu" >
                    <option value="Email" selected>Email</option>
                    <option value="Phone">Phone</option>
                    <option value="Other">Other - Please Describe Below</option>
                  </select>
                  </font></td>
              </tr>
              <tr>
                <td align="right" valign="top" class="tdbdr1">Please describe in detail your problem or request in regards to this account </td>
                <td valign="middle" class="tdbdr"><font color="#001188">&nbsp;
                  <textarea name="RefundReason" cols="38" rows="5" id="RefundReason" title="noeffort" onKeyDown="updatevalid(this)" ></textarea>
                  </font></td>
              </tr>
              <tr>
                <td align="right" valign="top" class="tdbdr1">&nbsp;</td>
                <td valign="middle" class="tdbdr">&nbsp;<font size="1" face="Verdana, Arial, Helvetica, sans-serif">
                  <input name="refund" type="submit" id="refund" value="<?=$refundBtnTxt?>">
                  </font> </td>
              </tr>
              <?php } ?>
            </table>
            <input type="hidden" name="tid" value="<?=$trans_id?>">
            <input type="hidden" name="reference_number" value="<?=$show_select_val['reference_number']?>">
            <input type="hidden" name="cancel" value="Yes">
            <input type="hidden" name="user_id" value="<?=$user_id?>">
          </form>
              <?php 
}
?>
            <p><strong>To Request that this transaction is refunded, please contact <a href="mailto:customerservice@etelegate.com">customer service</a>. </strong></p>
            <p>
            </td>
            
            </tr>
            
            <tr>
              <td>&nbsp;</td>
              <td height="30" align="center" valign="bottom">&nbsp;</td>
            </tr>
          <? 
}
?>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="842" align="center" class="blkbd1" height="20">
          <tr>
            <td height="25" bgcolor="#3D8287"></td>
          </tr>
        </table>
        <?php

}
			//************* IF THE NUMBER IS NOT FOUND IN DATABASE **********
else
{ 

		if($str_search_mode == "voice_id")
		{
			$msgtodisplay="Voice Authorization Id '$str_phone' not found";
			$outhtml="y";
			print("<br>");
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}
		else
		{
?>
        <table border="0" cellpadding="0" width="50%" cellspacing="0" height="80%" align="center">
          <tr>
            <td width="83%" valign="top" align="center"  >&nbsp;
              <table border="0" cellpadding="0" cellspacing="0" width="100%" >
                <tr>
                  <td colspan="2" height="25"></td>
                </tr>
                <tr>
                  <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
                  <td height="22" align="center" valign="middle" width="20%" background="../images/menucenterbg.gif" ><span class="whitehd">Search&nbsp;Result</span></td>
                  <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
                  <td height="22" align="left" valign="top" width="75%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
                  <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
                </tr>
                <tr>
                  <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>
                    <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">Not found in database. If you like to record the particular call details please fill the following form and click on submit or hit the &quot;Search Again&quot; Button to retry your search.</font></p>
                    <form name="frm_unfound_call" method="post" action="unfoundcall.php" onSubmit="return func_validate(document.frm_unfound_call)">
                      <table width="100%" border="0" cellspacing="0" cellpadding="4">
                        <tr>
                          <td width="35%"><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Name</font></font></div></td>
                          <td width="65%"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                            <input name="txtName" type="text" id="txtName" size="41" maxlength="250" style="font-family:Verdana;font-size:10px">
                            </font></td>
                        </tr>
                        <tr>
                          <td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Address</font></font></div></td>
                          <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                            <textarea name="txtAddress" cols="40" rows="4" id="txtAddress" style="font-family:Verdana;font-size:10px"></textarea>
                            </font></td>
                        </tr>
                        <tr>
                          <td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Phone Number </font></font></div></td>
                          <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                            <input name="txtPhone" type="text" id="txtPhone"  maxlength="10" style="font-family:Verdana;font-size:10px;width:80px" value="<?php print($str_phone);?>">
                            </font></td>
                        </tr>
                        <tr>
                          <td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1">Notes</font></font></div></td>
                          <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                            <textarea name="txtNotes" cols="40" rows="6" id="txtNotes" style="font-family:Verdana;font-size:10px"></textarea>
                            </font></td>
                        </tr>
                        <tr>
                          <td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1"></font></font></div></td>
                          <td><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;
                            <input name="imageField" type="submit" value="Submit" border="0">
                            <input name="search" type="button" onClick="javascript:document.location.href='customerservice.php'" value="Search Again">
                            </font></td>
                        </tr>
                      </table>
                      <input type="hidden" name="hid_counter" value="">
                      <input type="hidden" name="hid_gateway_id" value="<?= $i_gateway_id ?>">
                    </form>
                    <?php			}
}
		?>
                  </td>
                </tr>
                <tr>
                  <td width="1%"><img src="../images/menubtmleft.gif"></td>
                  <td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
                  <td width="1%" ><img src="../images/menubtmright.gif"></td>
                </tr>
              </table></td>
          </tr>
        </table>
        <br>
        <?php
	include("../includes/footer.php");
?>
        <?php
function get_previous_calls_count($cnn_cs,$iTransactionId)
{
	$qrySelect = "SELECT * FROM cs_callnotes WHERE transaction_id = ".$iTransactionId;
	if(!($rstSelect = mysql_query($qrySelect,$cnn_cs)))
	{
		print("Can not execute query");
		exit();
	}
	return mysql_num_rows($rstSelect);
}

function func_is_future_date($strBillDate)
{	
	$is_future_date = false;
	$str_current_date = func_get_current_date();
	if($strBillDate > $str_current_date)
		$is_future_date = true;
	//print("is future date= ".$is_future_date."*");
	return $is_future_date;
}

function strip_chars($str)
{
 	$str = preg_replace('@\D@','',$str);
	return $str;
}
?>
