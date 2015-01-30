<?php
		include("../includes/sessioncheckserviceuser.php");
		
		require_once("../includes/function.php");
		include("../admin/includes/serviceheader.php");
		$headerInclude = "service";
		include("../admin/includes/topheader.php");

		$str_duration = (isset($HTTP_GET_VARS["duration"])?Trim($HTTP_GET_VARS["duration"]):"");
		$str_hour = intval(substr($str_duration,0,2));
		$str_min = intval(substr($str_duration,3,2));
		$str_sec = intval(substr($str_duration,6,2));
?>
<div id="time" style="position:absolute; width:370px; height:40px; z-index:1; overflow: hidden;left:15;top:200">
<table><tr><td>
<form name="form_counter"><font face="verdana" color="#006633" size="2"><b>Time Elapsed:&nbsp;</b></font><input type="text" value="" name="counter" style="border:0px;font-face:verdana;font-weight:bold;Color:#006633"></form>
</td></tr></table>
</div>

<script>
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
function goBack(phoneNumber,searchMode,callNoteId)
{
	var duration = document.form_counter.counter.value;
	window.location="searchresult.php?txt_phone="+phoneNumber+"&rad_search_mode="+searchMode+"&duration="+duration+"&call_note_id="+callNoteId+"&back=yes";
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
	<tr>
	<td colspan="2" height="25">
	</td>
	</tr>
<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="20%" background="../images/menucenterbg.gif" ><span class="whitehd">Previous Call Details</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="75%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>
	<?php
			$iTransactionId = (isset($HTTP_GET_VARS["id"])?Trim($HTTP_GET_VARS["id"]):"");
			$str_phone_number = (isset($HTTP_GET_VARS["phoneNumber"])?Trim($HTTP_GET_VARS["phoneNumber"]):"");
			$str_search_mode = (isset($HTTP_GET_VARS["searchMode"])?Trim($HTTP_GET_VARS["searchMode"]):"");
			$i_call_note_id = (isset($HTTP_GET_VARS["callNoteId"])?Trim($HTTP_GET_VARS["callNoteId"]):"");
			if($iTransactionId != "")
			{
				$i_max_note_id = "";
				if(!($rst_select1 = mysql_query("select max(note_id) from cs_callnotes where transaction_id = $iTransactionId",$cnn_cs)))
				{
					print("Can not execute query");
					exit();
				}
				else
				{
					$i_max_note_id = mysql_result($rst_select1,0,0);
				}

				$qrySelect = "SELECT * FROM cs_callnotes WHERE transaction_id = ".$iTransactionId." and note_id <> ".$i_max_note_id;
				if(!($rstSelect = mysql_query($qrySelect)))
				{
					print("Can not execute query");
					exit();
				}
				//print($qrySelect);
				if(mysql_num_rows($rstSelect))
				{ ?>
				<form name="frmNotes" method="post" action="updatenotes.php">
				<table width="100%" border="1" cellspacing="0" cellpadding="5">
				<tr> 
				  <td width="2%"> 
					<div align="center"><strong><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">No</font></font></strong></div></td>
				  
          <td width="17%"> 
            <div align="center"><strong><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Call 
					  Date-Time </font></font></strong></div></td>
				  
          <td width="76%"> 
            <div align="center"><strong><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Notes</font></font></strong></div></td>
				  
          <td width="5%"> 
            <div align="center"><strong><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Solved</font></font></strong></div></td>
			</tr>
<?php				
					for($iLoop = 0;$iLoop<mysql_num_rows($rstSelect);$iLoop++)
					{ ?>
						
      
       				<tr> 
					<td> 
						<div align="left"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><?=$iLoop+1?></font></div></td>
			        <td> 
			            <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><?=mysql_result($rstSelect,$iLoop,2)?></font></div></td>
			        <td> 
			            <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><?=mysql_result($rstSelect,$iLoop,3)?>&nbsp;</font></div></td>
			        <td> 
			            <div align="left"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2"><?=mysql_result($rstSelect,$iLoop,6) == "1" ? "Solved" : ""?>&nbsp;</font></div></td>
			        </tr>
<?php				} ?>
			<tr>
				<td colspan="4" align="center">
					<a href="Javascript:goBack('<?=$str_phone_number?>','<?=$str_search_mode?>','<?=$i_call_note_id?>');"><img border="0" src="../images/back.jpg"></a>
				</td>
			</tr>
			</table>
			<input type="hidden" name="hdCount" value="<?=$iLoop?>">
			<input type="hidden" name="hdTransactionId" value="<?=$iTransactionId?>">
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
    </table>
    </td>
     </tr>
</table>
<br>
<?php
	include("../admin/includes/footer.php");
?>	
