<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//addcallcenteruserfb.php:		The page functions for callcenter users for this usertype = 1. 
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude="transactions";
include("includes/header.php");
require_once( 'includes/function.php');
$headerInclude="transactions";
include 'includes/topheader.php';

$siteid= $_SESSION['sessionlogin'];
$identity = " `cs_company_id` = '".$companyInfo['userId']."'";


if($_GET['delete'])
{
	$qry_delete = "UPDATE `cs_company_sites` set cs_hide = '1' WHERE $identity AND `cs_ID` = '".$_GET['delete']."' LIMIT 1";
	sql_query_read($qry_delete) or dieLog(mysql_error(),"Failed to remove URL from this company");
	$msg = "URL removed from site successfully.";
}

$cs_ID = (isset($_POST['cs_ID'])?quote_smart($_POST['cs_ID']):"");
$websiteurl= (isset($_POST['websiteurl'])?strtolower(quote_smart($_POST['websiteurl'])):"");
$cs_title= quote_smart($_POST['cs_title']);
$cs_member_url= (isset($_POST['cs_member_url'])?strtolower(quote_smart($_POST['cs_member_url'])):"");
$cs_member_username= (isset($_POST['cs_member_username'])?quote_smart($_POST['cs_member_username']):"");
$cs_member_password= (isset($_POST['cs_member_password'])?quote_smart($_POST['cs_member_password']):"");
$cs_enable_passmgmt= (isset($_POST['cs_enable_passmgmt'])?quote_smart($_POST['cs_enable_passmgmt']):"");
$cs_order_page= (isset($_POST['cs_order_page'])?strtolower(quote_smart($_POST['cs_order_page'])):"");
$cs_return_page= (isset($_POST['cs_return_page'])?strtolower(quote_smart($_POST['cs_return_page'])):"");
$cs_2257_page= (isset($_POST['cs_2257_page'])?quote_smart($_POST['cs_2257_page']):"");
$cs_support_email= (isset($_POST['cs_support_email'])?quote_smart($_POST['cs_support_email']):"");
$cs_support_phone= (isset($_POST['cs_support_phone'])?quote_smart($_POST['cs_support_phone']):"");
$cs_allow_testmode= intval($_POST['cs_allow_testmode']);

$cs_ftp= quote_smart($_POST['cs_ftp']);
$cs_ftp_user= quote_smart($_POST['cs_ftp_user']);
$cs_ftp_pass= quote_smart($_POST['cs_ftp_pass']);

$cs_notify_url= quote_smart($_POST['cs_notify_url']);
$cs_notify_type= quote_smart($_POST['cs_notify_type']);
$cs_notify_retry= quote_smart($_POST['cs_notify_retry']);
$cs_notify_user= quote_smart($_POST['cs_notify_user']);
$cs_notify_pass= quote_smart($_POST['cs_notify_pass']);
$cs_notify_key= quote_smart($_POST['cs_notify_key']);

$cs_notify_event1 = quote_smart($_POST['cs_notify_event1']);
$cs_notify_event4 = quote_smart($_POST['cs_notify_event4']);
$cs_notify_event8 = quote_smart($_POST['cs_notify_event8']);
$cs_notify_event16 = quote_smart($_POST['cs_notify_event16']);
$cs_notify_event32 = quote_smart($_POST['cs_notify_event32']);
$cs_notify_event128 = quote_smart($_POST['cs_notify_event128']);

$cs_member_secret= quote_smart($_POST['cs_member_secret']);
if($_POST['cs_member_passdir']) $cs_member_data['passdir']= quote_smart($_POST['cs_member_passdir']);
if($_POST['cs_member_groupdir']) $cs_member_data['groupdir']= quote_smart($_POST['cs_member_groupdir']);

if(is_array($_POST['cs_member_group_types']))
foreach($_POST['cs_member_group_types'] as $type)
	$cs_member_data['groups'][]= quote_smart($type);


$cs_member_updateurl= quote_smart($_POST['cs_member_updateurl']);

$cs_notify_event = 
	($cs_notify_event1 != "" ? 1 : 0) +
	($cs_notify_event4 != "" ? 4 : 0) +
	($cs_notify_event8 != "" ? 8 : 0) +
	($cs_notify_event16 != "" ? 16 : 0) +
	($cs_notify_event32 != "" ? 32 : 0) +
	($cs_notify_event128 != "" ? 128 : 0);


$cs_notify_eventurl = quote_smart($_POST['cs_notify_eventurl']);
$cs_notify_eventuser = quote_smart($_POST['cs_notify_eventuser']);
$cs_notify_eventpass = quote_smart($_POST['cs_notify_eventpass']);
$cs_notify_eventdomain = quote_smart($_POST['cs_notify_eventdomain']);
$cs_notify_eventlogintype = quote_smart($_POST['cs_notify_eventlogintype']);

$websiteurl_parts = parse_url($websiteurl);
$cs_URL = $websiteurl_parts['scheme']."://".$websiteurl_parts['host'];
$hashURL=strtolower(str_replace("www.","",$websiteurl_parts['host']));

$tableHeader = "Your Websites";

$sql = "SELECT cs_URL,cs_verified FROM `cs_company_sites` WHERE $identity AND cs_ID = '$cs_ID'";
$result=sql_query_read($sql) or dieLog(mysql_error());
if (mysql_num_rows($result)>0)
{
	$cs_company_sites = mysql_fetch_assoc($result);
	$cs_URL = $cs_company_sites['cs_URL'];
	$cs_verified = $cs_company_sites['cs_verified'];
	$websiteurl_parts = parse_url($cs_URL);
}

$msg = "Viewing Your Websites.";
if($_POST['mode'])
{
	//$urltemp = @parse_url($cs_member_url); 
	//if($cs_member_url != "" && strcasecmp($urltemp['host'],$websiteurl_parts['host'])!=0) 
		//$no_match = 3;
	$urltemp = @parse_url($cs_2257_page); 
	if($cs_2257_page && $cs_2257_page != 'http://' && $urltemp['host'] != $websiteurl_parts['host']) 
		$no_match = 4;
	if($no_match) dieLog("Different URLs: $cs_member_url, $cs_2257_page, $websiteurl","One or more of your entered URLS have different domains. This is not allowed. Please go back and make the changes.",false);

	$msg = "Updated Site Successfully.";
	if ($_POST['mode'] == 'new') 
	{
		$urltemp = @parse_url($cs_return_page); if($urltemp['host'] != $websiteurl_parts['host']) $no_match = 1;
		$urltemp = @parse_url($cs_order_page); if($urltemp['host'] != $websiteurl_parts['host']) $no_match = 2;
		if($no_match) dieLog("Different URLs: $cs_member_url, $cs_2257_page, $websiteurl","One or more of your entered URLS have different domains. This is not allowed. Please go back and make the changes.<BR>Member Section: $cs_member_url, 2257 Page: $cs_2257_page, Main Website: $websiteurl",false);

		$sql = "SELECT cs_URL,cs_ID FROM `cs_company_sites` WHERE $identity AND  cs_URL = '$cs_URL'";
		$result=sql_query_read($sql) or dieLog(mysql_error());
		if (mysql_num_rows($result)>0)
		{
			$cs_verified_sql="`cs_verified` = 'pending'";
		
			$msg = "Updated Entry '$cs_URL' Successfully.";
			$deleted_cs_info = mysql_fetch_assoc($result);

			$qry_update = "UPDATE `cs_company_sites` 
						SET 
							cs_ftp_user='$cs_ftp_user', 
							cs_ftp_pass='$cs_ftp_pass', 
							cs_ftp='$cs_ftp', 
							cs_title = '$cs_title',
							cs_support_email='$cs_support_email', 
							cs_support_phone='$cs_support_phone', 
							cs_reason = '',
							cs_2257_page = '$cs_2257_page',
							cs_member_url = '$cs_member_url',
							cs_member_username = '$cs_member_username',
							cs_member_password = '$cs_member_password',
							cs_enable_passmgmt = '$cs_enable_passmgmt',
							cs_hide = '0',
							cs_allow_testmode = '$cs_allow_testmode',

							cs_member_secret = '$cs_member_secret',
							cs_member_data = '".serialize($cs_member_data)."',
							cs_member_updateurl = '$cs_member_updateurl',

							cs_notify_url = '$cs_notify_url',
							cs_notify_retry = '$cs_notify_retry',
							cs_notify_user = '$cs_notify_user',
							cs_notify_pass = '$cs_notify_pass',
							cs_notify_type = '$cs_notify_type',
							cs_notify_key = '$cs_notify_key',

							cs_notify_event = '$cs_notify_event',
							cs_notify_eventurl = '$cs_notify_eventurl',
							cs_notify_eventuser = '$cs_notify_eventuser',
							cs_notify_eventpass = '$cs_notify_eventpass',
							cs_notify_eventdomain = '$cs_notify_eventdomain',
							cs_notify_eventlogintype = '$cs_notify_eventlogintype'
							
							WHERE $identity AND `cs_ID` = '".$deleted_cs_info['cs_ID']."'";

			sql_query_read($qry_update) or dieLog(mysql_error(). " ~ ".$qry_update,"Failed to reenable URL for this company. Please contact support.");
			toLog('pendingwebsite','merchant', "Merchant $siteid reenables site $cs_URL", $deleted_cs_info['cs_ID']);
		}
		else
		{
			$msg = "Added Site Successfully.";
			$qry_update = "INSERT INTO  `cs_company_sites` 
						SET 
							cs_created = NOW(),
							cs_en_ID = '".$companyInfo['en_ID']."', 
							cs_URL = '$cs_URL',
							cs_title = '$cs_title',
							cs_order_page = '$cs_order_page',
							cs_return_page = '$cs_return_page',
							cs_gatewayId = '".$_SESSION["gw_id"]."',
							cs_company_id = '$siteid',
							
							cs_reference_ID = '".strtoupper(md5($hashURL))."',
							cs_name = '$hashURL',
							
							cs_ftp_user='$cs_ftp_user', 
							cs_ftp_pass='$cs_ftp_pass', 
							cs_ftp='$cs_ftp', 
							cs_support_email='$cs_support_email', 
							cs_support_phone='$cs_support_phone', 
							cs_reason = '',
							cs_2257_page = '$cs_2257_page',
							cs_member_url = '$cs_member_url',
							cs_member_username = '$cs_member_username',
							cs_member_password = '$cs_member_password',
							cs_enable_passmgmt = '$cs_enable_passmgmt',
							cs_allow_testmode = '$cs_allow_testmode',

							cs_member_secret = '$cs_member_secret',
							cs_member_data = '".serialize($cs_member_data)."',
							cs_member_updateurl = '$cs_member_updateurl',

							cs_notify_url = '$cs_notify_url',
							cs_notify_retry = '$cs_notify_retry',
							cs_notify_user = '$cs_notify_user',
							cs_notify_pass = '$cs_notify_pass',
							cs_notify_type = '$cs_notify_type',
							cs_notify_key = '$cs_notify_key',

							cs_notify_event = '$cs_notify_event',
							cs_notify_eventurl = '$cs_notify_eventurl',
							cs_notify_eventuser = '$cs_notify_eventuser',
							cs_notify_eventpass = '$cs_notify_eventpass',
							cs_notify_eventdomain = '$cs_notify_eventdomain',
							cs_notify_eventlogintype = '$cs_notify_eventlogintype'
						";
			
			sql_query_read($qry_update) or dieLog(mysql_error(). " ~ ".$qry_update,"Failed to add URL to this company. This URL may already exist for another company.");
			toLog('pendingwebsite','merchant', "Merchant $siteid adds site $cs_URL",mysql_insert_id());
		}
	
	}
	else if ($_POST['mode'] == 'edit')
	{
		$qry_update = "UPDATE `cs_company_sites` 
						SET 
							cs_order_page = '$cs_order_page',
							cs_title = '$cs_title',
							cs_return_page = '$cs_return_page',
							cs_ftp_user='$cs_ftp_user', 
							cs_ftp_pass='$cs_ftp_pass', 
							cs_ftp='$cs_ftp', 
							cs_support_email='$cs_support_email', 
							cs_support_phone='$cs_support_phone', 
							cs_reason = '',
							cs_2257_page = '$cs_2257_page',
							cs_member_url = '$cs_member_url',
							cs_member_username = '$cs_member_username',
							cs_member_password = '$cs_member_password',
							cs_enable_passmgmt = '$cs_enable_passmgmt',

							cs_member_secret = '$cs_member_secret',
							cs_member_data = '".serialize($cs_member_data)."',
							cs_member_updateurl = '$cs_member_updateurl',

							cs_notify_url = '$cs_notify_url',
							cs_notify_retry = '$cs_notify_retry',
							cs_notify_user = '$cs_notify_user',
							cs_notify_pass = '$cs_notify_pass',
							cs_notify_type = '$cs_notify_type',
							cs_notify_key = '$cs_notify_key',
							cs_allow_testmode = '$cs_allow_testmode',
							
							cs_notify_event = '$cs_notify_event',
							cs_notify_eventurl = '$cs_notify_eventurl',
							cs_notify_eventuser = '$cs_notify_eventuser',
							cs_notify_eventpass = '$cs_notify_eventpass',
							cs_notify_eventdomain = '$cs_notify_eventdomain',
							cs_notify_eventlogintype = '$cs_notify_eventlogintype'
					";
		//if(strcasecmp("approved",$cs_verified) !=0 )
		//	$qry_update .=",cs_verified = 'pending'";
					
		$qry_update .= "							
						WHERE 
							$identity AND `cs_ID` = '$cs_ID'";
		toLog('pendingwebsite','merchant', "Merchant $siteid updates site $cs_URL", $cs_ID);
		sql_query_write($qry_update) or dieLog(mysql_error(). " ~ ".$qry_update,"Failed to edit URL for this company. Please contact support.");
	
	}
}
$sql = "SELECT * FROM `cs_company_sites` WHERE $identity AND  cs_hide = '0' order by cs_verified DESC";

if(!($result = sql_query_read($sql,1)))
{
	dieLog(mysql_errno().": ".mysql_error()."<BR>");
}
else
{
?>


&nbsp;
<script language="javascript">
function removeQuery(name)
{
	return confirm("Are you sure you want to delete Site '"+name+"'?");
	
}

</script>
<?php beginTable() ?>

<a href="addwebsiteuser.php">Add a Website</a>
	  <table height="100%" width="100%" cellspacing="0" cellpadding="0"><tr><td  width="100%" valign="center" align="center">

		  <table width="100%" border="0" cellpadding="0"  class="websites">
            <tr align="center" valign="middle">
              <td height="30" colspan="8" class="header">                <?=$msg?></td>
            </tr>

            <?php

	while ($url = mysql_fetch_assoc($result))
	{	
	$url_format = str_replace("http://","",$url['cs_URL']);
	$url_format = str_replace("https://","",$url_format);
	$url_format = str_replace("www.","",$url_format);
	if($url['cs_verified']=='ignored') $url['cs_verified'] = 'pending';
	if ($cs_verified != $url['cs_verified']) {
		$cs_verified = $url['cs_verified'];
	?>
            <tr align="center" valign="middle">
              <td height="30" class="subheader" colspan="10"><BR><BR><?=ucfirst($cs_verified)?> Websites <hr>

              </td>
            </tr>
			            <tr align="center" valign="middle">
              <td height="30" width="250">URL</td>
              <td height="30" width="250">Verified By <?=$_SESSION['gw_title']?></td>
              <td height="30" width="250">Password Management </td>
              <td height="30" width="250">Reference ID </td>
              <td height="30" width="250">&nbsp;</td>
            </tr>
	<?php }	?>
            <tr align="center" valign="middle">
              <td width="250" height="30"><?=$url_format?></td>
              <td width="250" height="30"><span class="<?=$url['cs_verified']?>"><?=ucfirst($url['cs_verified'])?></span></td>
              <td width="250" height="30"><?=($url['cs_enable_passmgmt']?"<a href='htpasswd_mgr.php?cs_ID=".$url['cs_ID']."'>Enabled</a>":"Disabled")?></td>
   			  <td width="250" height="30" class="denied"><?=($url['cs_reference_ID'])?></td>
              <td width="250" height="30"><a href="addwebsiteuser.php?mode=edit&cs_ID=<?=$url['cs_ID']?>">Edit</a><br><a href="addwebsiteuserfb.php?delete=<?=$url['cs_ID']?>"  onclick="return confirm('Are you sure you want to delete Website <?=$url['cs_name']?>?');">Delete</a><br></td>
            </tr>
            <?php
			if($url['cs_verified']=='declined'){
			?>
            <tr align="center" valign="middle">
              <td height="30" colspan="8">
               Decline Reason:<strong><em>
               <?=ucfirst($url['cs_reason'])?>
               </em></strong><BR>
                If you would like to resubmit this site for approval, please select the Edit option to update your details. By updating your details, your website will be set to <span class="pending">Pending</span>, and will be reconsidered by 
              <?=$_SESSION['gw_title']?><hr></td>
            </tr>
			<?php
			 }
	}


}
?>
          </table></td>
	  </tr></table>
<?php endTable($tableHeader,"addwebsiteuser.php") ?>
<?php
include("includes/footer.php");
?>

