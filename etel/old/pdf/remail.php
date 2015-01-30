<?php
die();
	include 'includes/sessioncheckuser.php';
	require_once("includes/dbconnection.php");
	$headerInclude = "transactions";
	include 'includes/header.php';
	require_once( 'includes/function.php');
	
	beginTable();
	
?>
	<table border="1" cellspacing="0" width="100%" class="report"  cellpadding="3">
<?	
	$sessionlogin =	isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

	if(($_POST['action'] && $_POST['refid']))
	{
			echo "<tr><td align='center' colspan='2'>";
			$res = sendTransactionEmail($_POST['refid'],$_POST['action'],$_POST['testmode'],'reference_number');

			echo "<h3>E-Mail Sent To:</h3><table>";
			foreach($res as $email)
				echo "<tr><td>" . $email['email'] . "</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>" . $email['copy'] . "</td></tr>";			
			echo "</table>";
	}
?>

    <tr>
	  <td width="50%"><b>Enter Transaction Reference ID: </b></input></td>
  	  <td width="50%"><input type=text name="refid"></td>
    </tr>
    <tr>
   		 <td width="50%">
			<b>Select Notification: </b></td>
  	     <td width="50%"><select name="action">
           <?
			$options = array(
				array("value" => "customer_rebill_decline_confirmation","display" => "Declined Rebill"),
				array("value" => "customer_cancel_confirmation","display" => "Cancel"),
				array("value" => "customer_refund_confirmation","display" => "Refund"),
				array("value" => "customer_expire_confirmation","display" => "Expire")
			);
				foreach($options as $option)
					echo "<option value=\"" . $option['value'] . "\"" . (strcasecmp($_POST['action'],$option)==0 ? "selected" : "")  . ">" . $option['display'] . "</option>";
			?>
         </select></td>
    </tr>
    <tr>
   		 <td width="50%">
			<b>Transaction Type: </b></td>
  	     <td width="50%"><select name="testmode">
           <option value="">Live Transaction</option>
           <option value="1">Test Transaction</option>
         </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input name="submit" type="submit" value="Resend E-Mail" /></td>
    </tr>
	</table>
<?php
	
	endTable("Resend Notification E-Mails",'remail.php');
	include("includes/footer.php");
?>