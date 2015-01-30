<?php

		$rootdir="../";
		$headerInclude = "service";
		include($rootdir."includes/sessioncheckserviceuser.php");
		include($rootdir."includes/dbconnection.php");
		require_once($rootdir."includes/function.php");
		include($rootdir."includes/header.php");

		$_SESSION['duration_start'] = time();

		$str_duration = $_SESSION['duration_start'];
		if($str_duration == "")
		{
			$str_hour = "0";
			$str_min = "00";
			$str_sec = "0";
		}
		else
		{
			$str_hour = floor((time()-$str_duration)/(60*60))%24;
			//if (strlen($str_hour) == 1) $str_hour = "0".$str_hour;
			$str_min = floor((time()-$str_duration)/(60))%60;
			//if (strlen($str_min) == 1) $str_min = "0".$str_min;
			$str_sec = floor((time()-$str_duration))%60;
			//if (strlen($str_sec) == 1) $str_sec = "0".$str_sec;
		}
		$strBillDate = "";
		$i_gateway_id = -1;
?>
<script>
	function validate()
	{
		if(document.search.txt_phone.value == "")
		{
			alert("Please enter either a telephone number or Voice Authorization Id");
			return false;
		}
	}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%">
  <tr>
       <td width="83%" valign="top" align="center"  >
         <p>&nbsp;</p>
         <div id="time" align="left">
<table><tr><td>
<form name="form_counter"><font face="verdana" color="#448A99" size="2"><b>Time Elapsed:&nbsp;</b></font><input name="counter" type="text" style="border:0px;font-face:verdana;font-weight:bold;Color:#448A99" value="" size="13">
</form>
</td></tr></table>
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
		alert("Please check either the 'Company Call Back', 'Solved', or 'Cancel' Button");
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
	if(confirm("Are you sure you would like to cancel this order?")){
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
		if( confirm("Are you sure you would like to cancel this order?") )
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
    <table border="0" cellpadding="0" cellspacing="0" width="50%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Transaction&nbsp; 
            Search</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>
		<form name="search"  method="GET"  action="searchresult.php" onSubmit="return validate();">
	          <table align="left" cellpadding="0" cellspacing="0" width="500">
				<tr>
                  <td height="20" colspan="2" ><div align="center"><font face="verdana" size="1">Enter one of the below fields to search for a customer's data: </font></div></td>
				</tr>
			<tr>
                  <td height="32" ><div align="right"><font face="verdana" size="1">Credit Card Number: </font> </div></td>
                  <td ><input name="txt_cc" type="password" id="txt_cc" style="font-family:verdana;font-size:10px;WIDTH:120px" value="" maxlength="16"></td>
				</tr>
			<tr>
                  <td height="32" ><div align="right"><font face="verdana" size="1">Telephone Number: </font> </div></td>
                  <td ><input name="txt_telephone" type="text" id="txt_telephone" style="font-family:verdana;font-size:10px;WIDTH:120px" value=""></td>
				</tr>
			<tr>
                  <td height="32" ><div align="right"><font face="verdana" size="1">Email Address: </font> </div></td>
                  <td ><input name="txt_email" type="text" id="txt_email" style="font-family:verdana;font-size:10px;WIDTH:120px" value=""></td>
				</tr>
			<tr>
                  <td height="32" ><div align="right"><font face="verdana" size="1">Reference Number: </font> </div></td>
                  <td ><input name="txt_reference" type="text" id="txt_reference" style="font-family:verdana;font-size:10px;WIDTH:120px" value=""></td>
				</tr>
			<tr>
                  <td height="32" ><div align="right"><font face="verdana" size="1">Cancelation Number: </font> </div></td>
                  <td ><input name="txt_cancel" type="text" id="txt_cancel" style="font-family:verdana;font-size:10px;WIDTH:120px" value=""></td>
				</tr>
			<tr valign="bottom">
                  <td height="32" colspan="2" > <div align="center" style="font-family:verdana;font-size:10px" >For checking transactions, both fields must be entered to search:</div></td>
                </tr>
			<tr>
                  <td height="32" ><div align="right"><font face="verdana" size="1">Checking Account Number: </font> </div></td>
                  <td ><input name="txt_checkingaccount" type="text" id="txt_checkingaccount" style="font-family:verdana;font-size:10px;WIDTH:120px" value=""></td>
				</tr>
			<tr>
                  <td height="32" ><div align="right"><font face="verdana" size="1">Bank Routing Number: </font> </div></td>
                  <td ><input name="txt_bankrouting" type="text" id="txt_bankrouting" style="font-family:verdana;font-size:10px;WIDTH:120px" value=""></td>
				</tr>
                <tr>
                  <td   height="30" colspan="2" align="center"  valign="middle" bgcolor="#ffffff"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
                    <input name="imageField" type="submit" value="Search" border="0" style="font-weight:bold ">
                    <input name="search" type="button" onClick="javascript:document.location.href='startpage.php'" value="End Call">
</font>
                </tr>
                <tr> 
                  <td  height="50"  valign="middle" align="center" bgcolor="#ffffff" colspan='3'>&nbsp;</td>
                </tr>
              </table>
		</form>
		</td>
      </tr>
		<tr>
		<td width="1%"><img src="../images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="../images/menubtmright.gif"></td>
		</tr>
    </table>
    </td>
  </tr>
</table>	
<?php
	include("../includes/footer.php");
?>	
