<?php
	include 'includes/sessioncheckuser.php';
	require_once("includes/dbconnection.php");
	$headerInclude = "transactions";
	include 'includes/header.php';
	require_once( 'includes/function.php');
	$connected = 0;
	$result = NULL;
	$msg = "";
	
	$cs_ID = intval($_REQUEST['cs_ID']);
	
	$identity = " `cs_company_id` = '".$companyInfo['userId']."'";
			
	if($cs_ID)
	{
		$sql = "SELECT * FROM `cs_company_sites` WHERE $identity AND `cs_ID` = '$cs_ID'";
		$sqlresult = sql_query_read($sql) or dieLog(mysql_error()."<BR>$sql");
		
		if(mysql_num_rows($sqlresult)!=1) dieLog("Site Not Found ~ $sql","Site was not found. Please contact administrator.");
		
		$site = mysql_fetch_assoc($sqlresult);
	
		if($site != 0 && $site['cs_member_updateurl'] != "")
		{
			$msg = "Connecting to '".$site['cs_member_updateurl']."'...\n";
			
			// Add New User
			if($_POST['new_user'] && $_POST['new_pass'])
			{
				$new_user = preg_replace('/[^a-zA-Z0-9_]/','',$_POST['new_user']);
				$new_pass = preg_replace('/[^a-zA-Z0-9_]/','',$_POST['new_pass']);
				$new_group = preg_replace('/[^a-zA-Z0-9_]/','',$_POST['new_group']);
				$new_prod_id = preg_replace('/[^a-zA-Z0-9_]/','',$_POST['new_prod_id']);
				$msg .= "-----------------\n Adding User '$new_user' to htpasswd file...\n";
				$result = post_passwordmgmt_query($site['cs_member_updateurl'],array('authpwd'=>$site['cs_member_secret'],'reqtype'=>'add','username'=>$new_user,'password'=>$new_pass,'groupaccess'=>$new_group,'product_id'=>$new_prod_id),-1);
				$result_val = intval($result['response']['body']);
				if($result && $etel_PW_response[$result_val])  $msg .= "  ".$result_val.": ".$etel_PW_response[$result_val].".\n";
			}		
				
			// Delete User(s)
			if($_POST['btn_delete'])
			{
				if($_POST['user_select_custom']) $_POST['user_select'][] = $_POST['user_select_custom'];
				foreach($_POST['user_select'] as $del_user)
				{
					$msg .= "-----------------\n Deleting User '$del_user' from htpasswd file...\n";
					$result = post_passwordmgmt_query($site['cs_member_updateurl'],array('authpwd'=>$site['cs_member_secret'],'reqtype'=>'delete','username'=>$del_user),-1);
					$result_val = intval($result['response']['body']);
					if($result && $etel_PW_response[$result_val])  $msg .= "  ".$result_val.": ".$etel_PW_response[$result_val].".\n";

				}
			}
				
			// Edit User
			if($_POST['edit_user'] && $_POST['edit_pass'])
			{
				$edit_user = preg_replace('/[^a-zA-Z0-9_]/','',$_POST['edit_user']);
				$edit_pass = preg_replace('/[^a-zA-Z0-9_]/','',$_POST['edit_pass']);
				$msg .= "-----------------\n Editing User '$edit_user' in htpasswd file...\n";
				$result = post_passwordmgmt_query($site['cs_member_updateurl'],array('authpwd'=>$site['cs_member_secret'],'reqtype'=>'chgpwd','username'=>$edit_user,'password'=>$edit_pass),-1);
				$result_val = intval($result['response']['body']);
				if($result && $etel_PW_response[$result_val])  $msg .= "  ".$result_val.": ".$etel_PW_response[$result_val].".\n";
			}	
				
			// Synchronize Users
			if($_POST['btn_sync'])
			{
				if($_POST['sync_remove'])
				{
					$sql = "SELECT 
						ss_cust_username
					FROM
						cs_subscription as ss
						LEFT JOIN cs_rebillingdetails AS rd ON ss_rebill_id = rd_subaccount
					WHERE
						ss_account_status = 'inactive'
						AND ss_cust_username != ''
						AND `ss_user_id` = '".$companyInfo['userId']."'
						AND `ss_site_ID` = '$cs_ID'";
					
					$msg .= "-----------------\n Removing all Inactive Users...\n";
					$sqlresult = sql_query_read($sql) or dieLog(mysql_error()."<BR>$sql");
					while($cs_subscription = mysql_fetch_assoc($sqlresult))
					{
						$del_user = preg_replace('/[^a-zA-Z0-9_]/','',$cs_subscription['ss_cust_username']);
						$msg .= "  Deleting User '$del_user' from htpasswd file...\n";
						$result = post_passwordmgmt_query($site['cs_member_updateurl'],array('authpwd'=>$site['cs_member_secret'],'reqtype'=>'delete','username'=>$del_user),-1);
						$result_val = intval($result['response']['body']);
						if($result && $etel_PW_response[$result_val])  $msg .= "   ".$result_val.": ".$etel_PW_response[$result_val].".\n";
						if($etel_PW_response[$result_val]=='202')  $summary['deleted']=intval($summary['deleted']) + 1;
					}
					$msg .= " Deleted (".intval($summary['deleted']).") Users.\n";
				}
				
				if($_POST['sync_add'])
				{
					$sql = "SELECT 
						ss_cust_username,
						ss_cust_password,
						ss_user_id,
						rd_description,
						rd_subName,
						td_product_ID
					FROM
						cs_subscription as ss
						LEFT JOIN cs_rebillingdetails AS rd ON ss_rebill_id = rd_subaccount
						LEFT JOIN cs_transactiondetails AS td ON td_ss_ID = ss_ID
					WHERE
						ss_account_status = 'active'
						AND ss_cust_username != ''
						AND  `ss_user_id` = '".$companyInfo['userId']."'
						AND  `ss_user_id` = '".$companyInfo['userId']."'
						AND `ss_site_ID` = '$cs_ID'";
						
					$msg .= "-----------------\n Sychronizing all Active Users...\n";
					$sqlresult = sql_query_read($sql) or dieLog(mysql_error()."<BR>$sql");
					while($cs_subscription = mysql_fetch_assoc($sqlresult))
					{
					
						$new_group = preg_replace('/[^a-zA-Z0-9_]/','',$cs_subscription['rd_description']);
						if(!$new_group) $new_group = preg_replace('/[^a-zA-Z0-9_]/','',$cs_subscription['rd_subName']);
						
						$new_user = preg_replace('/[^a-zA-Z0-9_]/','',$cs_subscription['ss_cust_username']);
						$new_pass = preg_replace('/[^a-zA-Z0-9_]/','',$cs_subscription['ss_cust_password']);
						$new_prod_id = preg_replace('/[^a-zA-Z0-9_]/','',$cs_subscription['td_product_ID']);
						
						$msg .= "  Adding User '$new_user' to htpasswd file...\n";
					
						$result = post_passwordmgmt_query($site['cs_member_updateurl'],array('authpwd'=>$site['cs_member_secret'],'reqtype'=>'add','username'=>$new_user,'password'=>$new_pass,'groupaccess'=>$new_group,'product_id'=>$new_prod_id),-1);
						$result_val = intval($result['response']['body']);
						if($result && $etel_PW_response[$result_val])  $msg .= "   ".$result_val.": ".$etel_PW_response[$result_val].".\n";
						if($etel_PW_response[$result_val]=='201')  $summary['added']=intval($summary['added']) + 1;
					}
					$msg .= " Added (".intval($summary['added']).") Users.\n";
				}
			}		
			
			// Get Current List
			$result_list = post_passwordmgmt_query($site['cs_member_updateurl'],array('authpwd'=>$site['cs_member_secret'],'reqtype'=>'list'),-1);
			if(!$result) $result = $result_list;	
			
			$result_val = intval($result_list['response']['body']);
			if($result && $etel_PW_response[$result_val])  $msg .= "   ".$result_val.": ".$etel_PW_response[$result_val].".\n";
						
			if($result_list['succeeded']) 
			{	
				$str = $result_list['response']['body'];
				parse_str($str,$listarray);
				if($listarray['version'])
				{
					$msg .= "-----------------\nConnected Successfully. Script Version:".$listarray['version'].". \n";
					$connected = 1;
					$user_array = $listarray['user'];
					$user_array_size = sizeof($user_array);
					if($listarray['usinggroups'])
					{
						$group_array = $listarray['group'];
						foreach($group_array as $key=>$data)
						{
							$group_array[$key] = explode(',',$data);
							if(!sizeof($user_array) || !$key)  unset($group_array[$key]);
						}
						$group_array_size = sizeof($group_array);
						if($user_array_size>0) $msg .= " ($group_array_size) Groups Found.\n";
						else $msg .= " No Groups Found.\n";
					}
					if($user_array_size>0) $msg .= " ($user_array_size) Password Entrys Found.\n";
					else $msg .= " No Password Entrys Found.\n";
				}
				else	// Support old script list
				{
					$msg .= "***Your Script may be Outdated. Please upgrade to latest version.***\n";
					$connected = 1;
					$user_array = preg_split('/[^a-zA-Z0-9_:, ]/',trim($result_list['response']['body']));
					$group_array = array();
					unset($user_array[0]);
					unset($user_array[sizeof($user_array)]);
					foreach ($user_array as $key=>$data)
					{
						if(!$data) unset($user_array[$key]);
						if($data=='502') unset($user_array[$key]);
						if(strpos($data,":"))
						{
							list($groupname, $users) = split(":", $data,2);
							$group_array[$groupname] = preg_split('/[^a-zA-Z0-9_]/',$users);
							unset($user_array[$key]);
						}
					}
					//etelPrint($group_array);
					$user_array_size = sizeof($user_array);
					if($user_array_size>0) $msg .= " ($user_array_size) Password Entrys Found.\n";
					else $msg .= " No Password Entrys Found.\n";
				}
				if($user_array) sort($user_array,SORT_STRING);
				if($group_array) ksort($group_array,SORT_STRING);
			}
		}
		else
			$msg .= "Invalid Site Selected. Please make sure that htaccess Password Management is enabled for this site.\n";
	}
	else
	{
		$msg = "Use this form to view, add, edit, and delete user accounts from your website in real time.\n";
	}
	
	beginTable();
	$msg_rows = substr_count($msg,"\n");
?>

<table border="1" cellspacing="0" width="100%" class="report"  cellpadding="3">
  <tr>
    <td colspan="2"><textarea cols="70" rows="<?=($msg_rows>6?6:$msg_rows)?>" readonly="readonly" class="report" style="border:none;" ><?=$msg?></textarea></td>
  </tr>
  <tr>
    <td colspan="2"><select name="cs_ID" id="cs_ID">
        <option value="">Select Website</option>
        <?=get_fill_combo_conditionally("SELECT cs_ID,cs_name FROM `cs_company_sites` WHERE $identity AND cs_hide = '0' ORDER BY `cs_name` ASC",$cs_ID)?>
      </select>    </td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" name="connect" value="Refresh" /><label title="Show Server Output"> Show Server Output:<input type="checkbox" name="show_output" value="1" <?=$_REQUEST['show_output']?"checked":""?> /></label>    </td>
  </tr>
  <? if($connected) { ?>
  <tr>
    <td>Add New User <br />
      New Username:
        <input type="text" name="new_user" />
      <br />
      New Password:
      <input type="text" name="new_pass" />
      <br />
      Group (Optional):
      <input type="text" name="new_group" />
      <br />
      Product ID (Optional):
      <input type="text" name="new_prod_id" />
      <br />
      <input type="submit" value="Add User" name="btn_add" tabindex="0" />	  </td>
    <td height="300" rowspan="3">Delete User(s)<br />
	<?php
if(sizeof($user_array)>0)
{
?>
<select name="user_select[]" size="20" multiple="multiple" id="username">
<?php
	if($listarray['usinggroups'])
	{
		foreach($group_array as $key=>$users)
		{
			?>
			<optgroup label="<?=$key?>">
			<?
			foreach($user_array as $id=>$username)
			{
				if(in_array($username ,$users))
				{
				unset($user_array[$id]);
				?>
				<option>
				<?=$username?>
				</option>
				<?php
				}
			}
			?>
			</optgroup>
			<?
		}
	}
	
	foreach($user_array as $username)
	{
	?>
	<option>
	<?=$username?>
	</option>
	<?php
	}
?>
</select>
<?php
}

?>
	  <br />
      <input type="text" name="user_select_custom" />
      <br />
      <input type="submit" value="Delete User(s)" name="btn_delete" />    </td>
  </tr>
  <tr>
    <td>Edit Password for User<br />
      Find Username:
        <input type="text" name="edit_user" />
      <br />
      New Password:
      <input type="text" name="edit_pass" />
      <br />
      <input type="submit" value="Edit Password" name="btn_edit" />
	  </td>
  </tr>
  <tr>
    <td>Synchronize .htpasswd File <br />
      <label>
      <input name="sync_remove" type="checkbox" value="1" checked="checked" />
      Remove Expired Accounts</label><br />
      <label>
      <input name="sync_add" type="checkbox" value="1" checked="checked" />
      Add Active Accounts</label>
      <br />
      <input name="btn_sync" type="submit" id="btn_sync" value="Synchronize" />
    </td>
  </tr>
  <? }  ?>
  <? if ($_REQUEST['show_output']) { ?>
  <tr>
  <td colspan="2">
  Connection Result: <br /><textarea cols="80" rows="2" readonly="readonly" class="report" style="border:none;"><?=$result_list['response']['body']?></textarea>
  <br />
  Command: <br />
  <textarea cols="80" rows="2" readonly="readonly" class="report" style="border:none;"><?=$result['response']['query']?></textarea><br />
  Command Result: <br />
  <textarea cols="80" rows="2" readonly="readonly" class="report" style="border:none;"><?=$result['response']['body']?></textarea>
  </td>
  </tr>
  <? }  ?>
</table>
<?php

	endTable("Password Manager",'htpasswd_mgr.php',NULL,NULL,NULL);

	include("includes/footer.php");
?>
