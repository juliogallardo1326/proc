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
// AddCompanyUser.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

$headerInclude="transactions";
include("includes/header.php");

$sessionlogintype =isset($HTTP_SESSION_VARS["sessionlogin_type"])?$HTTP_SESSION_VARS["sessionlogin_type"]:"";
$sessioncompanyid =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";


$identity = " `cs_company_id` = ".$companyInfo['userId'];

$cs_ID = intval($_GET['cs_ID']);

if($_GET['mode'] != 'edit') $_GET['mode'] = "new";
$tableHeader = "Please add a Website";

if($_GET['mode'] == 'edit') 
{
	$sql = "SELECT * FROM `cs_company_sites` WHERE $identity AND `cs_ID` = '$cs_ID' ";
	$result = sql_query_read($sql) or dieLog(mysql_errno().": ".mysql_error()."<BR>$sql");
	
	if(mysql_num_rows($result)!=1) dieLog("Site Not Found ~ $sql","Site was not found. Please contact administrator.");
	
	$url = mysql_fetch_assoc($result);

	$str_websiteurl = $url['cs_URL'];
	$cs_title = $url['cs_title'];
	$cs_order_page = $url['cs_order_page'];
	$cs_return_page = $url['cs_return_page'];
	$cs_2257_page = $url['cs_2257_page'];
	$str_creditcards = $url['cs_creditcards'];
	$str_echeck = $url['cs_echeck'];
	$str_web900 = $url['cs_web900'];
	$cs_member_url = $url['cs_member_url'];
	$cs_order_page = $url['cs_order_page'];
	$cs_return_page = $url['cs_return_page'];
	$cs_enable_passmgmt = $url['cs_enable_passmgmt'];
	$cs_member_password = $url['cs_member_password'];
	$cs_member_username = $url['cs_member_username'];
	$cs_support_email = $url['cs_support_email'];
	$cs_support_phone = $url['cs_support_phone'];
	$cs_verified = $url['cs_verified'];

	//post notification
	$cs_notify_url = $url['cs_notify_url'];
	$cs_notify_type = $url['cs_notify_type'];
	$cs_notify_retry = $url['cs_notify_retry'];
	$cs_notify_user = $url['cs_notify_user'];
	$cs_notify_pass = $url['cs_notify_pass'];
	$cs_notify_key = $url['cs_notify_key'];

	//event notification
	$cs_notify_event = $url['cs_notify_event'];
	$cs_notify_eventurl = $url['cs_notify_eventurl'];
	$cs_notify_eventuser = $url['cs_notify_eventuser'];
	$cs_notify_eventpass = $url['cs_notify_eventpass'];
	$cs_notify_eventdomain = $url['cs_notify_eventdomain'];
	$cs_notify_eventlogintype = $url['cs_notify_eventlogintype'];

	$cs_member_usedbmm = $url['cs_member_usedbmm'];
	$cs_member_data = unserialize($url['cs_member_data']);
	$cs_member_secret = $url['cs_member_secret'];
	$cs_member_updateurl = $url['cs_member_updateurl'];
	$cs_allow_testmode = $url['cs_allow_testmode'];

	$tableHeader = "Please Edit This Website";
}

?>
<script language="javascript" src="scripts/formvalid.js"></script>
<script language="javascript">

function randomString() {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 64;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}

function genkey(obj)
{
	if(!$(obj).disabled) $(obj).value = randomString();
}

function updateMember(checked)
{
	$('cs_member_url').disabled = (checked);
	$('cs_member_username').disabled = (checked);
	$('cs_member_password').disabled = (checked);
}

function updatePM(checked)
{
	$('cs_member_secret').disabled = (checked);
	$('cs_member_passdir').disabled = (checked);
	$('cs_member_groupdir').disabled = (checked);
	$('cs_member_group_types').disabled = (checked);
	$('cs_member_updateurl').disabled = (checked);
}

function updatePostNotify(value)
{
	$('cs_notify_url').disabled = (value=='');
	$('cs_notify_retry').disabled = (value=='');
	$('cs_notify_user').disabled = (value=='');
	$('cs_notify_pass').disabled = (value=='');
	$('cs_notify_key').disabled = (value=='');
}
function updateEventLoginType(value)
{
	$('cs_notify_eventuser').disabled = (value=='');
	$('cs_notify_eventpass').disabled = (value=='');
}
function getScript(file)
{
	url = $('websiteurl').value;
    domain = url.match( /:\/\/(www\.)?([^\/:]+)/ );
    domain = domain[2]?domain[2]:'';

	sitename = domain;
	
	groups = $A($('cs_member_group_types').getElementsByTagName('option'));
	group_txt = "";
	i=0;
	requireinfo = "valid-user ";
	groups.each(function(group){
		if(group.selected) 
		{
			if(i>0) group_txt += ",";
			group_txt += group.value;
			i++;
		}
	});
	if(i>0) requireinfo = "group "+group_txt;
	
	passdir = $('cs_member_passdir').value;
	groupdir = $('cs_member_groupdir').value;
	htpassdir = $('cs_member_passdir').value;
	htgroupdir = $('cs_member_groupdir').value;
	if(!htpassdir) htpassdir = '/dev/null';
	if(!htgroupdir) htgroupdir = '/dev/null';
	
	document.location.href = 'get_script.php?script_file=' + file +
	'&passdir=' + passdir + 
	'&groupdir=' + groupdir + 
	'&htpassdir=' + htpassdir + 
	'&htgroupdir=' + htgroupdir + 
	'&secret=' + $('cs_member_secret').value + 
	'&sitename=' + sitename + 
	'&requireinfo=' + requireinfo;
}



<!-- Idea by:  Nic Wolfe (Nic@TimelapseProductions.com) -->
<!-- Web URL:  http://fineline.xs.mw -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin

function popUp(URL) {
location.href=URL;
day = new Date();
id = day.getTime();
//eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=900,height=600,left = 340,top = 312');");
}
// End -->

</script>
 
<?php beginTable() ?>
<font face="verdana" size="1">
<?php
if ($_GET['mode'] != 'edit'){
	print "Please register your website(s) URL and select the payment option(s) you wish to accept. Please note integration will not work until you register the below information.<BR>";
}
else 
{
	if($cs_verified=='approved') print "<strong>This website has been approved already.<BR> You can edit your website but not the order, return, and main website pages. Please create a support ticket if you need any additional changes.</strong>";
}

if(!$str_websiteurl) $str_websiteurl='http://';
if(!$cs_member_url) $cs_member_url='http://';
if(!$cs_order_page) $cs_order_page='http://';
if(!$cs_return_page) $cs_return_page='http://';
if(!$cs_2257_page) $cs_2257_page='http://';
if(!$url['cs_ftp']) $url['cs_ftp']='ftp://';
$ftp_required = ($companyInfo['transaction_type']=='Adult' || $companyInfo['transaction_type']=='Extreme');

?>
</font> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" >
	<tr align="center" >
		<td height="30" colspan="2" valign="center" style="padding:8; "  ><a style="font-size:12px" href="addwebsiteuserfb.php">View Your Websites</a></td>
	</tr>			
	<tr align="left" >
		<td height="30" colspan="2" valign="center" style="padding:8; "  ><p>&nbsp;</p>		</td>
	</tr>
	<tr>
		<td colspan=2>
			<hr>
			<p><b>Website Settings </b></p>
			<blockquote>
			<p><font size="1" face="verdana">All URLs must be full and valid. Example: &nbsp;<br>
			'http://www.yoursite.com/catalog/' - correct<br>
			'http://www.yoursite.com/catalog/orderpage.php' - correct <br>
			'http://www.yoursite.com/catalog' - <font color="#FF0000">incorrect</font> <br>
			'www.yoursite.com/catalog' - <font color="#FF0000">incorrect</font> </font></p>
			</blockquote>
			<p><font color="#FF0000" size="1" face="verdana">Ftp access information is required for all Adult Merchants Only. </font></p>		</td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Website URL:&nbsp;<br />(Example: http://web2000.com</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="websiteurl" id="websiteurl" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$str_websiteurl?>" <?=($_GET['mode'] == 'edit'?'disabled':'')?> alt="url"></td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">(Phonetic) Website Title: <br />
	    (Example: Web Two Thousand Dot Com)&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_title" id="cs_title" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_title?>"  alt="req"></td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Your Order Page URL:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_order_page" type="text" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_order_page?>" alt="url"></td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Your Return Page URL:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_return_page" type="text" id="cs_return_page" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_return_page?>" alt="url"></td>
	</tr>
	<?php if ($ftp_required) { ?>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Your <a href="2257_Compliance.php">2257 Compliance</a> Page:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%">
			<input name="cs_2257_page" type="text" id="cs_2257_page" style="font-family:arial;font-size:10px;width:200px" onKeyDown="$('2257check').checked=(this.value=='')" value="<?=$cs_2257_page?>" alt="url">
			<br>
			<input type="checkbox" id="2257check" name="2257check" value="checkbox" onClick="$('cs_2257_page').alt=(this.checked?'':'url');$('cs_2257_page').value=(this.checked?'':'http://')">
			<font face="verdana" size="1">I do not need to comply with 2257. </font>		</td>
	</tr>
	<?php } ?>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Your Customer Service Email:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_support_email" type="text" id="cs_support_email" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_support_email?>" alt="email"></td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Your Customer Phone Number:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_support_phone" type="text" id="cs_support_phone" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_support_phone?>" alt="phone"></td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Your Website Ftp address <br>
			(Example: ftp://ftp.myftpserver.com)&nbsp;<br>
			</font>		</td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_ftp" type="text" id="cs_ftp" style="font-family:arial;font-size:10px;width:200px" value="<?=$url['cs_ftp']?>" alt="<?=($ftp_required?'url':'')?>"></td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Your Website Ftp Username:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_ftp_user" type="text" id="cs_ftp_user" style="font-family:arial;font-size:10px;width:200px" value="<?=$url['cs_ftp_user']?>" alt="<?=($ftp_required?'req':'')?>">		    </td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Your Website Ftp Password:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_ftp_pass" type="text" id="cs_ftp_pass" style="font-family:arial;font-size:10px;width:200px" value="<?=$url['cs_ftp_pass']?>" alt="<?=($ftp_required?'req':'')?>">		    </td>
	</tr>
	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Allow Test Modes:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_allow_testmode" type="checkbox" id="cs_allow_testmode" value="1" <?=($cs_allow_testmode?"checked":"")?>></td>
	</tr>
	<tr>
	<td colspan="2"><font face="verdana" color="#FF0000" size="1"><strong>Important</strong>: Please disable Test Mode once your site is live!.</font></td>
	</tr>
	<tr>
	<td colspan=2>
			<hr>
			<p><b>Members Section Information </b>			</p>
			<blockquote>
			<p><font face="verdana" size="1">In this section, you must include the direct link to the members section of your website. This will allow  
		    <?=$_SESSION['gw_title']?>
	        to include the members section link (along with additional login access if available) in the emails we send out to customers. <strong>If you have a members section and leave this blank, your website may be declined by </strong></font>			<font face="verdana" size="1">
	        <?=$_SESSION['gw_title']?>
	        . If you do not have a members section, please select the appropriate box.	        </font></p>
		</blockquote>		</td>
	</tr>

	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">I don't have a members section :&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_enable_passmgmt1" type="checkbox" id="cs_enable_passmgmt" value="1" onClick="updateMember(this.checked)"></td>
	</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Members Login Url &nbsp;</font></td>
					<td height="30" align="left" valign="center"   ><input name="cs_member_url" type="text" id="cs_member_url" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_member_url?>" alt="req">					</td>
				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Member Username:&nbsp;</font></td>
					<td height="30" align="left" valign="center" ><input name="cs_member_username" type="text" id="cs_member_username" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_member_username?>" alt="req"></td>
				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Member Password:&nbsp;</font></td>
					<td height="30" align="left" valign="center"><input name="cs_member_password" type="text" id="cs_member_password" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_member_password?>" alt="req"></td>
					</tr>
	<tr>
		<td colspan=2>
			<hr>
			<p><b>Enable Password Management</b>			</p>
			<blockquote>
			<p><font face="verdana" size="1">Password management allows <?=$_SESSION['gw_title']?> to manage your user accounts for you. By selecting this option, your customers will be asked to create a Username and Password during the Payment Process. This option affects password management functions and allows <?=$_SESSION['gw_title']?>
		    to automatically update your website's password software with the current status of each customer's subscription. <strong>To use this option, you must provide a link to the members section of your website. You must also provide a username and password that we can use to verify the contents of your members section. </strong></font>			</p>
			</blockquote>		</td>
	</tr>

	<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Enable Password Management:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_enable_passmgmt" type="checkbox" id="cs_enable_passmgmt" value="1" <?=($cs_enable_passmgmt?"checked":"")?> /></td>
	</tr>
	<tr>
		<td colspan=2>
			<hr>
			<p><b>Password Management Settings</b>			</p>
			<blockquote>
			<p><font face="verdana" size="1">If you are using Password Management, please provide the following information to integrate with your site.</font>			          </p>
			</blockquote>		</td>
	</tr>
		<tr>
		<td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Enable Password Management Settings:&nbsp;</font></td>
		<td align="left" valign="center" height="30" width="50%"><input name="cs_enable_passmgmt3" type="checkbox" id="cs_enable_settings" value="1" <?=($cs_member_updateurl&&$cs_member_secret?"checked":"")?> onClick="updatePM(!this.checked)"></td>
	</tr>

				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">PM Update Script URL:&nbsp;</font></td>
					<td height="30" align="left" valign="center"><input name="cs_member_updateurl" type="text" id="cs_member_updateurl" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_member_updateurl?>" alt="" onChange="updateScriptLink()" ></td>
				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Secret Key:&nbsp;(<a href="javascript:genkey('cs_member_secret')">Generate&nbsp;Key) </a></font></td>
					<td height="30" align="left" valign="center"><input name="cs_member_secret" type="text" id="cs_member_secret" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_member_secret?>" alt=""></td>
				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Full Path to your .htpasswd file:&nbsp;</font></td>
					<td height="30" align="left" valign="center"><input name="cs_member_passdir" type="text" id="cs_member_passdir" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_member_data['passdir']?>" alt=""></td>
				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Optional Full Path to your .htgroup file:&nbsp;</font></td>
					<td height="30" align="left" valign="center"><input name="cs_member_groupdir" type="text" id="cs_member_groupdir" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_member_data['groupdir']?>" alt=""></td>
				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Optional Group Access:<br />(Limit access to these groups)&nbsp;</font></td>
					<td height="30" align="left" valign="center"><select name="cs_member_group_types[]" size="4" multiple="multiple" id="cs_member_group_types" style="font-family:arial;font-size:10px;width:200px">
					  <?php
					  $sql = "select rd_description,rd_subName from cs_rebillingdetails where company_user_id = '".$companyInfo['userId']."' group by rd_description";
					  $result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
					  while ($rd = mysql_fetch_assoc($result))
					  {
						$group = preg_replace('/[^a-zA-Z0-9_]/','',$rd['rd_description']);
						if(!$group) $group = preg_replace('/[^a-zA-Z0-9_]/','',$rd['rd_subName']);
						$selected = '';
						if(in_array($group,$cs_member_data['groups'])) $selected = 'selected';
						echo "<option value='$group' $selected>$group</option>\n";
					  }
					  ?>
					</select></td>
				</tr>
	<tr >
		<td colspan="2" align="center"><font face="verdana" size="1">
		<a href="javascript:getScript('etelegate_pl')" id="script_link">[Get Perl Script]</a>
		<a href="javascript:getScript('etelegate_php')" id="script_link">[Get PHP Script]</a>
        <a href="javascript:getScript('_htaccess')" id="script_link">[Get .htaccess file]</a>
<?php if($cs_ID) echo "<BR><a href='htpasswd_mgr.php?cs_ID=$cs_ID'>[Test/Manage Passwords]</a>"; ?></font>
			
	  <hr>	  </td>
	</tr>
	<tr >
		<td colspan="2">
			<table id="pass_mgmt">
				<tr>
					<td colspan=2>
						<p>
						<b>Post Notification Settings</b></p>
						<blockquote>
						<p><font face="verdana" size="1">
						Post Notification provides instant notification of a transaction to a user-defined location.
						</font></p>
						<p><font face="verdana" size="1">
						<b>Sign-Up Notification (HTTPS ONLY)</b><br>
						We can integrate with your website by sending POSTS to a script located on your server.  Depending on the result of the transaction, you can have the transaction data sent to a dynamic page.
						</font></p>
						<p><font face="verdana" size="1">
						*HTTP Notification is not allowed due to security restrictions.<br>
						*HTTPS Notification is similar to HTTP but allows posting to a secure location
						</font></p>
						</blockquote>					</td>
				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Notify Me of the Following:&nbsp;</font></td>
					<td height="30" align="left" valign="center" >
						<select style="font-family:arial;font-size:10px;width:200px" name="cs_notify_type" onChange="updatePostNotify(this.value)" >
						<option value="" <?=(!$cs_notify_type ? "selected" : "")?>>Disabled</option>
						<option value="both" <?=(!strcasecmp($cs_notify_type,"both") ? "selected" : "")?>>Approve/Decline</option>
						<option value="approve only" <?=(!strcasecmp($cs_notify_type,"approve only") ? "selected" : "")?>>Approve Only</option>
						<option value="decline only" <?=(!strcasecmp($cs_notify_type,"decline only") ? "selected" : "")?>>Decline Only</option>
						</select>					</td>
				</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Notification URL:&nbsp;</font></td>
				<td height="30" align="left" valign="center"   ><input name="cs_notify_url" type="text" id="cs_notify_url" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_notify_url?>">				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Post Notification Retries:&nbsp;</font></td>
					<td height="30" align="left" valign="center" >
						<select style="font-family:arial;font-size:10px;width:200px" name="cs_notify_retry" id="cs_notify_retry">
						<option value="0" <?=(!strcasecmp($cs_notify_retry,"0") ? "selected" : "")?>>Don't Retry the Post</option>
						<option value="1" <?=(!strcasecmp($cs_notify_retry,"1") ? "selected" : "")?>>Retry the Post Once</option>
						<option value="2" <?=(!strcasecmp($cs_notify_retry,"2") ? "selected" : "")?>>Retry the Post Twice</option>
						<option value="3" <?=(!strcasecmp($cs_notify_retry,"3") ? "selected" : "")?>>Retry the Post Three Times</option>
						<option value="4" <?=(!strcasecmp($cs_notify_retry,"4") ? "selected" : "")?>>Retry the Post Four Times</option>
						<option value="5" <?=(!strcasecmp($cs_notify_retry,"5") ? "selected" : "")?>>Retry the Post Five Times</option>
						</select>					</td>
				</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Login Username:&nbsp;</font></td>
				<td height="30" align="left" valign="center"   ><input name="cs_notify_user" type="text" id="cs_notify_user" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_notify_user?>">				</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Login Password:&nbsp;</font></td>
				<td height="30" align="left" valign="center"   ><input name="cs_notify_pass" type="text" id="cs_notify_pass" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_notify_pass?>">				</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">RC4 Encryption Key:&nbsp;<a href="javascript:genkey('cs_notify_key')">Generate&nbsp;Key</a></font></td>
				<td height="30" align="left" valign="center"   ><input name="cs_notify_key" type="text" id="cs_notify_key" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_notify_key?>">				</tr>
			</table><center>
		<?php echo "<a href='posttesting.php'>Test Post Notification</a>"; ?>
			</center>		</td>
	</tr>
	<tr >
		<td colspan="2">
			<hr>
			<table id="pass_mgmt">
				<tr>
					<td colspan=2>
						<p>
						<b>Event Notification Settings(HTTP or HTTPS)</b>:</p>
						<blockquote>
						  <p><font face="verdana" size="1"> *HTTP Notification is a method of sending a post to a non-secure location.<br />
						  *HTTPS Notification is similar to HTTP but allows posting to a secure location </font></p>
						<p><font face="verdana" size="1">We can notify you for event types other than Sign-Up.  For example, cancellations, chargebacks, delayed 
						  site access, expirations, refunds, revokes and rebills.
						  </font></p>
					  </blockquote>					</td>
				</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Notification URL:&nbsp;</font></td>
				<td height="30" align="left" valign="center"   ><input name="cs_notify_eventurl" type="text" id="cs_notify_eventurl" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_notify_eventurl?>">				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Authentication Type:&nbsp;</font></td>
					<td height="30" align="left" valign="center" >
						<select style="font-family:arial;font-size:10px;width:200px" name="cs_notify_eventlogintype" onChange="updateEventLoginType(this.value)">
						<option value="" <?=(!$cs_notify_eventlogintype ? "selected" : "")?>>Anonymous</option>
						<option value="basic" <?=(!strcasecmp($cs_notify_eventlogintype,"basic") ? "selected" : "")?>>Basic</option>
						<option value="ntlm" <?=(!strcasecmp($cs_notify_eventlogintype,"ntlm") ? "selected" : "")?>>NTLM for Domain</option>
						</select>					</td>
				</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Login Username:&nbsp;</font></td>
				<td height="30" align="left" valign="center"   ><input name="cs_notify_eventuser" type="text" id="cs_notify_eventuser" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_notify_eventuser?>">				</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Login Password:&nbsp;</font></td>
				<td height="30" align="left" valign="center"   ><input name="cs_notify_eventpass" type="text" id="cs_notify_eventpass" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_notify_eventpass?>">				</tr>
				<tr >
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Domain:&nbsp;</font></td>
				<td height="30" align="left" valign="center"   ><input name="cs_notify_eventdomain" type="text" id="cs_notify_eventdomain" style="font-family:arial;font-size:10px;width:200px" value="<?=$cs_notify_eventdomain?>">				</tr>
				<tr>
					<td width="50%" align="right" valign="center" ><font face="verdana" size="1">Events:&nbsp;</font></td>
					<td></td>
				</tr>
				<tr>
					<td colspan=2>
						<center>
						<table>
						<tr>
						<td><input name="cs_notify_event1" type="checkbox" <?=(($cs_notify_event & 1) == 1 ? "checked" : "")?>><font face="verdana" size="1">Approved/Declined Rebills</font></input></td>
						<td><input name="cs_notify_event4" type="checkbox" <?=(($cs_notify_event & 4) == 4 ? "checked" : "")?>><font face="verdana" size="1">Cancellation</input></font></td>
						<td><input name="cs_notify_event16" type="checkbox" <?=(($cs_notify_event & 16) == 16 ? "checked" : "")?>><font face="verdana" size="1">Chargeback</input></font></td>
						</tr>
						<tr>
						<td><input name="cs_notify_event128" type="checkbox" <?=(($cs_notify_event & 128) == 128 ? "checked" : "")?>><font face="verdana" size="1">Expiration</input></font></td>
						<td><input name="cs_notify_event8" type="checkbox" <?=(($cs_notify_event & 8) == 8 ? "checked" : "")?>><font face="verdana" size="1">Refund</input></font></td>
						<td><input name="cs_notify_event32" type="checkbox" <?=(($cs_notify_event & 32) == 32 ? "checked" : "")?>><font face="verdana" size="1">Revoke</input></font></td>
						</tr>
						</table>
						</center>					</td>
				</tr>
			</table>
			<hr>		</td>
	</tr>		  
	<tr>
		<td align="center" colspan="2">&nbsp;&nbsp;&nbsp;<input type="image" id="addsite" name="addsite" src="<?=$tmpl_dir?>images/submit.jpg"></input>		</td>
	</tr>
</table>
<input name="mode" type="hidden" value="<?=$_GET['mode']?>">
<input name="cs_ID" type="hidden" value="<?=$cs_ID?>">
<script>
updateMember(<?=($cs_member_url?"0":"1")?>);
updatePM(<?=($cs_member_updateurl&&$cs_member_secret?"0":"1")?>);
updatePostNotify('<?=($cs_notify_type)?>');
updateEventLoginType('<?=($cs_notify_eventlogintype)?>');
</script>
<?php 
endTable($tableHeader,"addwebsiteuserfb.php");
include("includes/footer.php");
?>