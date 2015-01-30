<?php
	include 'includes/sessioncheckuser.php';
	require_once("includes/dbconnection.php");
	$headerInclude = "transactions";
	include 'includes/header.php';
	require_once( 'includes/function.php');
	
	$sessionlogin =	isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
	
	if(isset($_POST['action']))
	{
		$site = merchant_getWebSite($sessionlogin,$_POST['cs_id']);
		if($site != 0 && $site['cs_member_updateurl'] != "")
		{
			$trans['td_username'] = $_POST['username'];
			$trans['td_password'] = $_POST['password'];
			$trans['cs_notify_retry'] = $site['cs_notify_retry'];
			$trans['cs_member_updateurl'] = $site['cs_member_updateurl'];
			$trans['cs_member_secret'] = $site['cs_member_secret'];
			$trans['amount'] = $_POST['amount'];
			
			echo "<b>Posting To: " . $trans['cs_member_updateurl'] . "</b><br>";
			$res = post_passwordmgmt($trans,$_POST['action'],true);
			echo "<textarea rows=10 cols=40>" . $res['response']['head'] . "</textarea><br>";
			echo "<textarea rows=10 cols=40>" . $res['response']['body'] . "</textarea><br>";
		}
		else
			echo "<p><b>Invalid Site Selected</b></p>";
	}
	
	$sites = merchant_getWebSites($sessionlogin);
	
beginTable();
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
	<tr><td>
	<select name="cs_ID" id="cs_ID">
	<option value="">Select Website</option>
	<?=get_fill_combo_conditionally("SELECT cs_ID,cs_name FROM `cs_company_sites` WHERE `cs_en_ID` = '".$curUserInfo['en_ID']."' AND cs_hide = '0' ORDER BY `cs_name` ASC",$cs_ID)?>
	</select>
	</td></tr>
    <tr>
   		 <td width="100%">


			<b>User Name: </b><input type="text" name="username"></input><br>
			<b>Password: </b><input type="text" name="password"></input><br>
			<b>$ Amount: </b><input type="text" name="amount"></input><br>
			
			<select name="action">
				<option value="add">add</option>
				<option value="delete">delete</option>
			</select><br>
			<input type="submit" value="Test POST">
		 </td>
  	</tr>
	</table>
<?php

	endTable("Password Manager",NULL,NULL,NULL,true);

	include("includes/footer.php");
?>