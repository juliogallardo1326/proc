<?php
require_once("includes/sessioncheck.php");
include("../includes/completion.php");
include("../includes/companySubView.php");
$etel_completion_array[-1]['txt']="Old Company [No Status]";
$headerInclude = "companies";
require_once("includes/header.php");
require_once($rootdir."includes/JSON_functions.php");

$image_list = array('.jpg','.gif','.bmp');

$recentIDs = $_SESSION['recentDocumentIds'];
if(!is_array($recentIDs)) $recentIDs = array();

if($_GET['clear']) $recentIDs = array();

$file_id = intval($_POST['file_id']);
$reject_reason = $_POST['reject_reason'];



if($_POST['Submit']=='Submit Changes')
{
	if(is_array($_POST['Approve_Document']))
	{
		foreach($_POST['Approve_Document'] as $file_id)
		{
			$file_id = intval($file_id);
			$sql = "select * from `cs_uploaded_documents` as ud left join `cs_companydetails` as cd on ud.user_id=cd.userId where ud.file_id='$file_id' $bank_sql_limit";
	
			$result = mysql_query($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			
			$letterTempate = 'merchant_document_approved';
						
			$emailData["email"] = $companyInfo['email'];
			$emailData["companyname"] = $companyInfo['companyname'];
			$emailData["username"] = $companyInfo['username'];
			$emailData["password"] = $companyInfo['password'];
			$emailData["Reference_ID"] = $companyInfo['ReferenceNumber'];
			$emailData["Message"] = $reject_reason;
			$emailData["gateway_select"] = $companyInfo['gateway_id'];
			
			$documentFormat = $companyInfo['file_type'].": ".$companyInfo['file_name'];
			$emailData["Document"] = $documentFormat;
		
			$sql = "Update `cs_uploaded_documents` as ud left join `cs_companydetails` as cd on ud.user_id=cd.userId set `status` = 'Approved',`reject_reason` = '$reject_reason' where `file_id` = '$file_id' $bank_sql_limit";
			$result = mysql_query($sql) or dieLog(mysql_error());
			if(sizeof($recentIDs)>9) array_pop($recentIDs);
			$recentIDs[] = $file_id;
			$msg .= "<span class='approved'>$documentFormat has been Approved.</span><BR>\n";
			send_email_template($letterTempate,$emailData);
		}
	}
	
	if(is_array($_POST['Reject_Document']))
	{
		foreach($_POST['Reject_Document'] as $file_id)
		{
			$file_id = intval($file_id);
			$reject_reason = $_POST['Reject_Reason_'.$file_id];
			$sql = "select * from `cs_uploaded_documents` as ud left join `cs_companydetails` as cd on ud.user_id=cd.userId where ud.file_id='$file_id' $bank_sql_limit";
	
			$result = mysql_query($sql) or dieLog(mysql_error());
			$companyInfo = mysql_fetch_assoc($result);
			
			$letterTempate = 'merchant_document_declined';
						
			$emailData["email"] = $companyInfo['email'];
			$emailData["companyname"] = $companyInfo['companyname'];
			$emailData["username"] = $companyInfo['username'];
			$emailData["password"] = $companyInfo['password'];
			$emailData["Reference_ID"] = $companyInfo['ReferenceNumber'];
			$emailData["Message"] = $reject_reason;
			$emailData["gateway_select"] = $companyInfo['gateway_id'];
			
			$documentFormat = $companyInfo['file_type'].": ".$companyInfo['file_name'];
			$emailData["Document"] = $documentFormat;
			
		
			$sql = "Update `cs_uploaded_documents`  as ud left join `cs_companydetails` as cd on ud.user_id=cd.userId set `status` = 'Declined',`reject_reason` = '$reject_reason' where `file_id` = '$file_id' ";
			$result = mysql_query($sql) or dieLog(mysql_error());
			if(sizeof($recentIDs)>9) array_pop($recentIDs);
			$recentIDs[] = $file_id;
			$msg .= "<span class='rejected'>$documentFormat has been Rejected.</span><BR>\n";
			send_email_template($letterTempate,$emailData);
		}
	}
}

$_SESSION['recentDocumentIds'] = $recentIDs;

$recentIDsql = "";

// Select Company

beginTable();
echo genCompanyViewTable('confirmUploads.php','confirmUploads.php','full');
endTable("Select Company Document");

$company_table_sql = "`cs_companydetails`";

$pendingSql = "`status` = 'Pending'";

foreach($recentIDs as $id) 
	$recentIDsql .= " or (`file_id` = '$id') ";

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


// End Select Company


if($_REQUEST['fileList']) $fileListsql = " or (`file_id` in (".quote_smart($_REQUEST['fileList']).")) ";

$sql = "SELECT ud.*,cd.companyname,cd.cd_completion,cd.userId
FROM `cs_uploaded_documents` as ud left join $company_table_sql as cd on ud.user_id=cd.userId
WHERE cd.userId is not null && ((($pendingSql) $company_SQL) $recentIDsql $fileListsql) $bank_sql_limit
order by `status`='Pending' ASC,
`status` ASC,
`cd_completion` DESC,
`userId` DESC LIMIT 500";
$result = sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
$numFiles = mysql_num_rows($result);

if(mysql_num_rows($result)>0)
{

beginTable(); ?>
<script language="javascript">
function approve(id)
{
	document.getElementById('rd_'+id).checked = false;
	document.getElementById('rr_'+id).value = "";
	document.getElementById('rr_label_'+id).innerHTML = "";
	
	return true;
}
function decline(id)
{
	reject_reason = prompt("Please give a reason for declining this document:","");
  	
	if(reject_reason == null || reject_reason == '') return false;
	document.getElementById('ad_'+id).checked = false;
	document.getElementById('rr_'+id).value = reject_reason;
	document.getElementById('rr_label_'+id).innerHTML = 'Reject Reason: '+reject_reason;

	return true;
}
function showUploadWindow(userId) {
	window.open ("uploadDocuments.php?company="+userId,'',"'scrollbars=no,title=no,resizable=no,width=600, height=300'");
}
</script>
<table width="100%" border="0" cellpadding="0" class="websites">
            <tr align="center" valign="middle">
              <td height="30" class="headerLight" colspan="4"><?=$numFiles?>                Documents Found. 
              <BR>
              <?=$msg?></td>
            </tr>
            <?php

	while ($document = mysql_fetch_assoc($result))
	{	


	if ($ud_status != $document['status']) {
	$ud_status = $document['status'];
	$recently = "Recently ";
	if($ud_status=='Pending') $recently = "";
		?>
            <tr align="center" valign="middle">
              <td height="30" class="subheader" colspan="4"><?=$recently.ucfirst($ud_status)?> Documents 
                <hr>

              </td>
            </tr>
            <tr align="center" valign="middle">
              <td height="30">Document Type </td>
              <td height="30">Download File </td>
              <?php if($numFiles<50) { ?><td height="30">Preview</td><?php } ?>
              <td width="200" height="30">&nbsp;</td>
            </tr>
	<?php }?>
			<?php	
	if ($companyname != $document['companyname']) {
	$companyname = $document['companyname'];
	$status = $etel_completion_array[intval($document['cd_completion'])]['txt'];
	$style = $etel_completion_array[intval($document['cd_completion'])]['style'];
	?>
            <tr align="center" valign="middle">
              <td height="30" colspan="2" align="right">              <span style="font-size:10px; <?=$style?> ">
              </span><a href="editCompanyProfileAccess.php?company_id=<?=$document['userId']?>" class="subheader" style="text-decoration:none;">
                <?=ucfirst(($companyname?$companyname:"Company Deleted / Invalid Documents"))?>
              </a> - </td>
              <td height="30" colspan="2" align="left"><span style="font-size:10px; font-weight:<?=$bold?> ">
                <?=$status?>
              </span></td>
            </tr>
			<tr><td colspan="4" align="center"><a href="javascript:showUploadWindow('<?=$document['userId']?>')">Upload a File</a></td></tr>
	<?php }?>
            <tr align="center" valign="middle">
              <td height="30"><?=$document['file_type']?></td>
              <td height="30"><a target="_blank" href='<?="../gateway/".$_SESSION['gw_folder']."UserDocuments/".$document['file_type']."/".$document['file_name']?>'><?=substr($document['file_name'],0,20)?></a><BR><span class="<?=$ud_status?>"><?=ucfirst($ud_status)?></span></td>
              <?php if($numFiles<50) { ?><td height="30"><?php if(isImage($document['file_name'])){?><a target="_blank" href='<?="../gateway/".$_SESSION['gw_folder']."UserDocuments/".$document['file_type']."/".$document['file_name']?>'><img width="150" height="100" src='<?="../gateway/".$_SESSION['gw_folder']."UserDocuments/".$document['file_type']."/".$document['file_name']?>'></a><?php } ?></td><?php } ?>
              
   			  <td height="30">
			  <label for="ad_<?=$document['file_id']?>">Approve</label>
			  <input type="checkbox" name="Approve_Document[]"  id="ad_<?=$document['file_id']?>" value="<?=$document['file_id']?>"  onClick="return approve('<?=$document['file_id']?>')">
			  <label for="rd_<?=$document['file_id']?>">Reject</label>
			  <input type="checkbox" name="Reject_Document[]" id="rd_<?=$document['file_id']?>" value="<?=$document['file_id']?>" onClick="return decline('<?=$document['file_id']?>')">
			  <input type="hidden" name='Reject_Reason_<?=$document['file_id']?>' id='rr_<?=$document['file_id']?>' value="">
              <BR><label id='rr_label_<?=$document['file_id']?>'></label>
			 </td>
            </tr>
            <?php
	
	}

?>
            <tr align="center" valign="middle">
              <td height="30" colspan="4">              <span style="font-size:10px; font-weight:<?=$bold?> ">
              </span><a href="editCompanyProfileAccess.php?company_id=<?=$document['userId']?>" class="subheader" style="text-decoration:none;">
              </a><span style="font-size:10px; font-weight:<?=$bold?> ">
			  <input type="hidden" name="userIdList" value="<?=$_REQUEST['userIdList']?>">
			  <input type="hidden" name="cd_view" value="<?=$_REQUEST['cd_view']?>">
              <input type="submit" name="Submit" value="Submit Changes">
</span></td>
            </tr>
</table>
<?php 
endTable("Approve or Decline Documents","confirmUploads.php"); 
}

include("includes/footer.php");

function isImage($imgFile)
{
	global $image_list;
	
	foreach($image_list as $image_type)
		if (strpos($imgFile,$image_type)!== false) return 1;
	return 0;
}
?>
