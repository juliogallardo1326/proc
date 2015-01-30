<?php
require_once("includes/sessioncheck.php");
include("../includes/completion.php");
include("../includes/companySubView.php");

$cd_completion_array[-1]['txt']="Old Company [No Status]";
$headerInclude = "mail";
require_once("includes/header.php");


$recentIDs = $_SESSION['recentEmailIds'];
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
			$sql = "select * from `cs_email_lists` as ec left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id where cs.cs_ID='$cs_ID'";
			
			$result = mysql_query($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			
			$letterTempate = 'merchant_website_approved';
			
			$cs_URL = $companyInfo['cs_URL'];
						
			$emailData["email"] = $companyInfo['email'];
			$emailData["ec_action"] = $companyInfo['ec_action'];
			$emailData["username"] = $companyInfo['username'];
			$emailData["password"] = $companyInfo['password'];
			$emailData["Reference_ID"] = $companyInfo['ReferenceNumber'];
			$emailData["Message"] = $cs_reason;
			$emailData["site_URL"] = $companyInfo['cs_URL'];
			$emailData["gateway_select"] = $companyInfo['gateway_id'];
		
			$sql = "Update `cs_email_lists` set `ec_type` = 'approved',`cs_reason` = '$cs_reason' where `cs_ID` = '$cs_ID'";
			$result = mysql_query($sql) or dieLog(mysql_error());
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
			$sql = "select * from `cs_email_lists` as ec left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id where cs.cs_ID='$cs_ID'";
			
			$result = mysql_query($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			$cs_URL = $companyInfo['cs_URL'];
			
			$letterTempate = 'merchant_website_declined';
						
			$emailData["email"] = $companyInfo['email'];
			$emailData["ec_action"] = $companyInfo['ec_action'];
			$emailData["username"] = $companyInfo['username'];
			$emailData["password"] = $companyInfo['password'];
			$emailData["Reference_ID"] = $companyInfo['ReferenceNumber'];
			$emailData["Message"] = $cs_reason;
			$emailData["site_URL"] = $companyInfo['cs_URL'];			
			$emailData["gateway_select"] = $companyInfo['gateway_id'];
		
			$sql = "Update `cs_email_lists` set `ec_type` = 'declined',`cs_reason` = '$cs_reason' where `cs_ID` = '$cs_ID'";
			$result = mysql_query($sql) or dieLog(mysql_error());
			if(sizeof($recentIDs)>9) array_pop($recentIDs);
			$recentIDs[] = $cs_ID;	
			$msg .= "<span class='declined'>$cs_URL has been Declined.</span><BR>\n";
			toLog('misc','merchant', "Merchant Site $cs_URL has been Declined by IP:".getRealIp(), $_SESSION["sessionlogin"]);
			send_email_template($letterTempate,$emailData);
		}
		
	}
	
	if(is_array($_POST['Ignore_Website']))
	{
		foreach($_POST['Ignore_Website'] as $cs_ID)
		{
			$cs_ID = intval($cs_ID);
			$cs_reason = $_POST['Reject_Reason_'.$cs_ID];
			$sql = "select * from `cs_email_lists` as ec left join `cs_companydetails` as cd on cd.userId=cs.cs_company_id where cs.cs_ID='$cs_ID'";
			
			$result = mysql_query($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			
			$cs_URL = $companyInfo['cs_URL'];
		
			$sql = "Update `cs_email_lists` set `ec_type` = 'ignored',`cs_reason` = '$cs_reason' where `cs_ID` = '$cs_ID'";
			$result = mysql_query($sql) or dieLog(mysql_error());
			if(sizeof($recentIDs)>9) array_pop($recentIDs);

			$recentIDs[] = $cs_ID;
			$msg .= "<span class='approved'>$cs_URL has been Ignored.</span><BR>\n";
			toLog('misc','merchant', "Merchant Site $cs_URL has been Ignored by IP:".getRealIp(), $_SESSION["sessionlogin"]);
		}
	}
}

$_SESSION['recentEmailIds'] = $recentIDs;

$recentIDsql = "";

if(is_array($recentIDs)) 
	foreach($recentIDs as $id) 
		$recentIDsql .= " or (`el_ID` = '$id') ";


$sql = "SELECT count(ec_email) as cnt
FROM `cs_email_lists` as ec 
WHERE 1";
$result = mysql_query($sql) or dieLog(mysql_error());
$numEmails = mysql_fetch_assoc($result);
$numEmails = $numEmails['cnt'];

$sql = "SELECT ec.*,companyname,reseller_companyname,reference_number,cd.userId,rd.reseller_id,td.transactionId
FROM `cs_email_lists` as ec 
left join `cs_companydetails` as cd on cd.userId=ec_item_ID AND ec_type='merchant'
left join `cs_resellerdetails` as rd on rd.reseller_id=ec_item_ID AND ec_type='reseller'
left join `cs_transactiondetails` as td on td.transactionId=ec_item_ID AND ec_type='customer'
WHERE 1
group by ec_ID
order by
ec_type DESC,
ec_action DESC
";
$result = mysql_query($sql) or dieLog(mysql_error());

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
	reject_reason = prompt("Please give a reason for declining this document:","");
  	
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
              <td height="30" class="header" colspan="3"><?=$numEmails?> Emails
              <BR><?=$msg?></td>
            </tr>
            <?php
$last_ec_type = "";
	while ($email = mysql_fetch_assoc($result))
	{	
		$objectname = "Nothing";
		$link = "";
		
		if($email['userId'] && $email['ec_type']=='merchant')
		{
			$objectname = $email['companyname'];
			$link = "<a href='editCompanyProfile3.php?company_id=".$email['userId']."'>$objectname</a>";
		} else if($email['reseller_id'] && $email['ec_type']=='reseller')
		{
			$objectname = $email['reseller_companyname'];
			$link = "<a href='modifyReseller.php?reseller_id=".$email['reseller_id']."'>$objectname</a>";
		} else if($email['transactionId'] && $email['ec_type']=='customer')
		{
			$objectname = $email['reference_number'];
			$link = "<a href='viewreportpage.php?id=".$email['transactionId']."'>$objectname</a>";
		} 
		if ($last_ec_type != $email['ec_type']) {
			$last_ec_type = $email['ec_type'];
			$ec_action = -1;
		?>
            <tr align="center" valign="middle">
              <td height="30" class="subheader" colspan="3"><?=ucfirst($last_ec_type)?> Emails <hr>

              </td>
            </tr>
	<?php }?>
			<?php	
	if ($ec_action != $email['ec_action']) {
	$ec_action = $email['ec_action'];
	if(!$ec_action) $email['ec_action'] = "Nothing";
	?>
            <tr align="center" valign="middle">
              <td height="30" colspan="3" align="center">                <strong>
              <?=ucfirst($email['ec_action'])?></strong></td>
            </tr>
            <tr align="center" valign="middle">
              <td height="30">Email</td>
              <td height="30">Type</td>
              <td height="30">Reason</td>
            </tr>
	<?php }?>
            <tr align="center" valign="middle">
              <td height="30"><a href="mailto:<?=$email['ec_email']?>"><?=$email['ec_email']?></a></td>
              <td height="30"><?=$link?></td>
              <td height="30"><?=$email['ec_reason']?></td>
            </tr>
            <?php
	
	}

?>
            <tr align="center" valign="middle">
              <td height="30" colspan="9">
			  
</span></td>
            </tr>
</table>
<?php 
	endTable("Mange Emails","emailManage.php");
}
include("includes/footer.php");
?>
