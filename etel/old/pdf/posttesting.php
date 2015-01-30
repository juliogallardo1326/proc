<?php
	include 'includes/sessioncheckuser.php';
	require_once("includes/dbconnection.php");
	$headerInclude = "transactions";
	include 'includes/header.php';
	require_once( 'includes/function.php');
	
	beginTable();
	
?>
	<table border="1" cellspacing="0" width="100%" class="report"  cellpadding="3">
<?	

	$sessionlogin = $companyInfo['userId'];
	$str_company_id = $companyInfo['userId'];
	
	if(($_POST['action'] && $_POST['refid']))
	{
			$res = Process_Transaction(trim($_POST['refid']),$_POST['action'],intval($_POST['testmode']),'reference_number');
			echo "<tr><td align='center' colspan='2'>";

			echo "<p>POST Notification Results ( " . $res[0]['response']['url'] . " )<br>";
			//echo "<textarea rows=5 cols=60>" . $res[0]['response']['head'] . "</textarea><br>";
			echo "<textarea rows=10 cols=60>" . $res[0]['response']['body'] . "</textarea></p>";	
			echo "<p>Variables Sent<br>";
			echo "<textarea rows=10 cols=60>";
			print_r($res[0]['response']['data']);
			echo "</textarea></p>";			
			
			//echo "<p>Password Management Results ( " . $res[1]['response']['url'] . " )<br>";
			//echo "<textarea rows=5 cols=60>" . $res[1]['response']['head'] . "</textarea><br>";
			//echo "<textarea rows=10 cols=60>" . $res[1]['response']['body'] . "</textarea></p>";			
			echo "</td></tr>";
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
			$options = array("approve","cancellation","chargeback","decline","expiration","rebill","refund","revoke");
				foreach($options as $option)
					echo "<option value=\"" . $option . "\"" . (strcasecmp($_POST['action'],$option)==0 ? "selected" : "")  . ">" . $option . "</option>";
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
      <td><input name="submit" type="submit" value="Test POST" /></td>
    </tr>
	</table>
<?php
	
	endTable("Post Notification Test",'posttesting.php');
	include("includes/footer.php");
?>