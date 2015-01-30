<?php
require_once("includes/sessioncheck.php");
include("../includes/completion.php");
include("../includes/companySubView.php");

$etel_completion_array[-1]['txt']="Old Company [No Status]";
$headerInclude = "companies";
require_once("includes/header.php");
require_once($rootdir."includes/JSON_functions.php");

$displayAllStatus = false;
$recentIDs = $_SESSION['recentWebsiteIds'];
if(!is_array($recentIDs)) $recentIDs = array();

if($_GET['clear']) $recentIDs = array();

if($_POST['Submit']=='Submit Changes')
{
	if(is_array($_POST['Approve_Website']))
	{
		foreach($_POST['Approve_Website'] as $cs_ID)
		{
			$cs_ID = intval($cs_ID);
			$cs_reason = $_POST['Reject_Reason_'.$cs_ID];
			$sql = "select * from `cs_company_sites` as cs left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id where cs.cs_ID='$cs_ID' $bank_sql_limit";
			
			$result = sql_query_read($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			
			$letterTempate = 'merchant_website_approved';
			
			$cs_URL = $companyInfo['cs_URL'];
						
			$emailData["email"] = $companyInfo['email'];
			$emailData["companyname"] = $companyInfo['companyname'];
			$emailData["username"] = $companyInfo['username'];
			$emailData["password"] = $companyInfo['password'];
			$emailData["Reference_ID"] = $companyInfo['ReferenceNumber'];
			$emailData["Message"] = $cs_reason;
			$emailData["site_URL"] = $companyInfo['cs_URL'];
			$emailData["gateway_select"] = $companyInfo['gateway_id'];
		
			$sql = "Update `cs_company_sites` as cs left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id  set `cs_verified` = 'approved',`cs_reason` = '$cs_reason' where `cs_ID` = '$cs_ID' $bank_sql_limit";
			$result = sql_query_write($sql) or dieLog(mysql_error());
			if(sizeof($recentIDs)>9) array_pop($recentIDs);

			$recentIDs[] = $cs_ID;
			$msg .= "<span class='approved'>$cs_URL has been Approved.</span><BR>\n";
			toLog('misc','merchant', "Merchant Site $cs_URL has been Approved by IP:".getRealIp(), $_SESSION["sessionlogin"]);
			send_email_template($letterTempate,$emailData);
		}
	}
	
	if(is_array($_POST['Reject_Website']))
	{
		foreach($_POST['Reject_Website'] as $cs_ID)
		{
			$cs_ID = intval($cs_ID);
			$cs_reason = $_POST['Reject_Reason_'.$cs_ID];
			$sql = "select * from `cs_company_sites` as cs left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id where cs.cs_ID='$cs_ID' $bank_sql_limit";
			
			$result = sql_query_read($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			$cs_URL = $companyInfo['cs_URL'];
			
			$letterTempate = 'merchant_website_declined';
						
			$emailData["email"] = $companyInfo['email'];
			$emailData["companyname"] = $companyInfo['companyname'];
			$emailData["username"] = $companyInfo['username'];
			$emailData["password"] = $companyInfo['password'];
			$emailData["Reference_ID"] = $companyInfo['ReferenceNumber'];
			$emailData["Message"] = $cs_reason;
			$emailData["site_URL"] = $companyInfo['cs_URL'];	
			$emailData["gateway_select"] = $companyInfo['gateway_id'];	
		
			$sql = "Update `cs_company_sites`  as cs left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id  set `cs_verified` = 'declined',`cs_reason` = '$cs_reason' where `cs_ID` = '$cs_ID' $bank_sql_limit";
			$result = sql_query_write($sql) or dieLog(mysql_error());
			if(sizeof($recentIDs)>9) array_pop($recentIDs);
			$recentIDs[] = $cs_ID;	
			$msg .= "<span class='declined'>$cs_URL has been Declined.</span><BR>\n";
			toLog('misc','merchant', "Merchant Site $cs_URL has been Declined by IP:".getRealIp(), $_SESSION["sessionlogin"]);
			send_email_template($letterTempate,$emailData);
		}
		
	}	
	
	if(is_array($_POST['NonCompliant_Website']))
	{
		foreach($_POST['NonCompliant_Website'] as $cs_ID)
		{
			$cs_ID = intval($cs_ID);
			$cs_reason = $_POST['Reject_Reason_'.$cs_ID];
			$sql = "select * from `cs_company_sites` as cs left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id where cs.cs_ID='$cs_ID' $bank_sql_limit";
			
			$result = sql_query_read($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			$cs_URL = $companyInfo['cs_URL'];
			
			$letterTempate = 'merchant_website_approved';
						
			$emailData["email"] = $companyInfo['email'];
			$emailData["companyname"] = $companyInfo['companyname'];
			$emailData["username"] = $companyInfo['username'];
			$emailData["password"] = $companyInfo['password'];
			$emailData["Reference_ID"] = $companyInfo['ReferenceNumber'];
			$emailData["Message"] = $cs_reason;
			$emailData["site_URL"] = $companyInfo['cs_URL'];	
			$emailData["gateway_select"] = $companyInfo['gateway_id'];	
		
			$sql = "Update `cs_company_sites`  as cs left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id  set `cs_verified` = 'non-compliant',`cs_reason` = '$cs_reason' where `cs_ID` = '$cs_ID' $bank_sql_limit";
			$result = sql_query_write($sql) or dieLog(mysql_error());
			if(sizeof($recentIDs)>9) array_pop($recentIDs);
			$recentIDs[] = $cs_ID;	
			$msg .= "<span class='declined'>$cs_URL has been set Non-Compliant.</span><BR>\n";
			toLog('misc','merchant', "Merchant Site $cs_URL has been Non-Compliant by IP:".getRealIp(), $_SESSION["sessionlogin"]);
			send_email_template($letterTempate,$emailData);
		}
		
	}
	
	if(is_array($_POST['Ignore_Website']))
	{
		foreach($_POST['Ignore_Website'] as $cs_ID)
		{
			$cs_ID = intval($cs_ID);
			$cs_reason = $_POST['Reject_Reason_'.$cs_ID];
			$sql = "select * from `cs_company_sites` as cs left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id where cs.cs_ID='$cs_ID' $bank_sql_limit";
			
			$result = sql_query_read($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			
			$cs_URL = $companyInfo['cs_URL'];
		
			$sql = "Update `cs_company_sites` as cs set `cs_verified` = 'ignored',`cs_reason` = '$cs_reason' where `cs_ID` = '$cs_ID' $bank_sql_limit";
			$result = sql_query_write($sql) or dieLog(mysql_error());
			if(sizeof($recentIDs)>9) array_pop($recentIDs);

			$recentIDs[] = $cs_ID;
			$msg .= "<span class='approved'>$cs_URL has been Ignored.</span><BR>\n";
			toLog('misc','merchant', "Merchant Site $cs_URL has been Ignored by IP:".getRealIp(), $_SESSION["sessionlogin"]);
		}
	}
}

$_SESSION['recentWebsiteIds'] = $recentIDs;

$recentIDsql = "";

if(is_array($recentIDs)) 
	foreach($recentIDs as $id) 
		$recentIDsql .= " or (`cs_ID` = '$id') ";
		
if($_REQUEST['webList']) $webListsql = " or (`cs_ID` in (".quote_smart($_REQUEST['webList']).")) ";

// Select Company

beginTable();
echo genCompanyViewTable('confirmWebsite.php','confirmWebsite.php','full');
endTable("Select Company Websites");

$company_table_sql = "`cs_companydetails`";

$pendingSql = "`cs_verified` = 'pending'";

if($_REQUEST['cd_view'] == 'AL')
{
	$sql_info = JSON_getCompanyInfo_build($_REQUEST);
	$company_table_sql = " (Select cd.userId,cd.companyname,cd.cd_completion from ". $sql_info['sql_from']." Where ".$sql_info['sql_where'].")";
	$recentIDsql = NULL;
	$displayAllStatus = true;
	$pendingSql = "0";
}
else if($_REQUEST['userIdList'])
{
	$userIdList = explode("|",$_REQUEST['userIdList']);
	foreach($userIdList as $id) 
		$recentIDsql .= " or (cd.userId = '$id') ";
	$pendingSql = "0";
}




$sql = "SELECT cs.*,cd.companyname,cd.cd_completion
FROM `cs_company_sites` as cs left join $company_table_sql as cd on cs_company_id=userId
WHERE cd.userId is not null && ((($pendingSql  && `cs_hide`=0) $company_SQL ) $recentIDsql $webListsql)$bank_sql_limit
order by `cs_verified` DESC,
`cd_completion` DESC,
`cs_company_id` DESC limit 500";
$result = sql_query_read($sql) or dieLog(mysql_error());
$numSites = mysql_num_rows($result);
if(mysql_num_rows($result)>0)
{

	beginTable(); 
?>
<script language="javascript">
function ignore(id)
{
	document.getElementById('rw_'+id).checked = false;
	document.getElementById('aw_'+id).checked = false;
	document.getElementById('rr_'+id).value = "";
	document.getElementById('rr_label_'+id).innerHTML = "";
	
	return true;
}
function approve(id)
{
	document.getElementById('rw_'+id).checked = false;
	document.getElementById('iw_'+id).checked = false;
	document.getElementById('rr_'+id).value = "";
	document.getElementById('rr_label_'+id).innerHTML = "";
	
	return true;
}
function decline(id)
{
	reject_reason = prompt("Please give a reason for modifying this website:","");
  	
	if(reject_reason == null || reject_reason == '') return false;
	document.getElementById('aw_'+id).checked = false;
	document.getElementById('iw_'+id).checked = false;
	document.getElementById('rr_'+id).value = reject_reason;
	document.getElementById('rr_label_'+id).innerHTML = 'Reject Reason: '+reject_reason;

	return true;
}
</script>


<table width="100%" cellpadding="0" class="websites">
            <tr align="center" valign="middle">
              <td height="30" class="header" colspan="7"><?=$numSites?> Websites found. 
              <BR><?=$msg?></td>
            </tr>
            <?php

	while ($url = mysql_fetch_assoc($result))
	{	
	$url_format = str_replace("http://","",$url['cs_URL']);
	$url_format = str_replace("https://","",$url_format);
	$url_format = str_replace("www.","",$url_format);
	

	if ($cs_verified != $url['cs_verified']) {
	$cs_verified = $url['cs_verified'];
	$recently = '';//"Recently ";
	if($cs_verified=='pending') $recently = "";
		?>
            <tr align="center" valign="middle">
              <td height="30" class="subheader" colspan="7"><?=$recently.ucfirst($cs_verified)?> Websites <hr>              </td>
            </tr>
            <tr align="center" valign="middle">
              <td height="30">URL</td>
              <td height="30">Order Page</td>
              <td height="30">Return Page</td>
              <td height="30">2257 Compliance Page</td>
              <td height="30">Password Management </td>
              <td height="30">&nbsp;</td>
            </tr>
	<?php }?>
			<?php	
	if ($companyname != $url['companyname']) {
	$companyname = $url['companyname'];
	$status = $etel_completion_array[intval($url['cd_completion'])]['txt'];
	$style = $etel_completion_array[intval($url['cd_completion'])]['style'];
	$displayCompanyname = $companyname;
	if(!$displayCompanyname) $displayCompanyname = "Deleted Companys / Invalid Websites";
	?>
            <tr align="center" valign="middle">
              <td height="30" colspan="4" align="right">
              <a href="editCompanyProfileAccess.php?company_id=<?=$url['cs_company_id']?>" class="subheader" style="text-decoration:none;"><?=ucfirst($displayCompanyname)?></a></td>
              <td height="30" colspan="2" align="left"> - <span style="font-size:10px; <?=$style?> ">
                <?=$status?>
              </span></td>
            </tr>
	<?php }?>
            <tr align="center" valign="middle">
              <td height="30"><a target='_blank' style="font-size:12px " href="<?=$url['cs_URL']?>"><?=$url_format?></a><BR><span class="<?=$url['cs_verified']?>"><?=ucfirst($url['cs_verified'])?></span></td>
              <td height="30"><?php if($url['cs_order_page']){?><a target='_blank' href='<?=$url['cs_order_page']?>'>Order</a><?php } ?></td>
              <td height="30"><?php if($url['cs_return_page']){?><a target='_blank' href='<?=$url['cs_return_page']?>'>Return</a><?php } ?></td>
              <td height="30"><?php if($url['cs_2257_page']){?><a target='_blank' href='<?=$url['cs_2257_page']?>'>2257</a><?php } ?></td>
              <td height="30"><pre><?php 
						if ($url['cs_enable_passmgmt'] || $url['cs_member_url'])
							echo "U: ".$url['cs_member_username']."\nP: ".$url['cs_member_password']."\n<a href='".$url['cs_member_url']."'  target='_blank' >Link</a>";
						else
							echo "Disabled";
						?>
                </pre></td>
              <td width="120" height="30" nowrap>			  
			  <label for="aw_<?=$url['cs_ID']?>">Approve</label>
			  <input type="checkbox" name="Approve_Website[]"  id="aw_<?=$url['cs_ID']?>" value="<?=$url['cs_ID']?>"  onClick="return approve('<?=$url['cs_ID']?>')">
			  <BR><label for="rw_<?=$url['cs_ID']?>">Reject</label>
			  <input type="checkbox" name="Reject_Website[]" id="rw_<?=$url['cs_ID']?>" value="<?=$url['cs_ID']?>" onClick="return decline('<?=$url['cs_ID']?>')">
			  <BR><label for="rw_<?=$url['cs_ID']?>">Non-Compliant</label>
			  <input type="checkbox" name="NonCompliant_Website[]" id="rw_<?=$url['cs_ID']?>" value="<?=$url['cs_ID']?>" onClick="return decline('<?=$url['cs_ID']?>')">
			  <BR><label for="iw_<?=$url['cs_ID']?>">Ignore</label>
			  <input type="checkbox" name="Ignore_Website[]" id="iw_<?=$url['cs_ID']?>" value="<?=$url['cs_ID']?>" onClick="return ignore('<?=$url['cs_ID']?>')">
			  <input type="hidden" name='Reject_Reason_<?=$url['cs_ID']?>' id='rr_<?=$url['cs_ID']?>' value="">
              <BR><label id='rr_label_<?=$url['cs_ID']?>'></label>            </td>
            </tr>
            <?php
	
	}

?>
            <tr align="center" valign="middle">
              <td height="30" colspan="6">
			  
			  <input type="hidden" name="userIdList" value="<?=$_REQUEST['userIdList']?>">
			  <input type="hidden" name="cd_view" value="<?=$_REQUEST['cd_view']?>">
              <input type="submit" name="Submit" value="Submit Changes">
</span></td>
            </tr>
</table>
<?php 
	endTable("Approve or Decline Websites","confirmWebsite.php");
}
include("includes/footer.php");
?>
