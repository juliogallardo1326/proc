<?php

$disableInjectionChecks=1;

include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
$headerInclude = "transactions";
include 'includes/header.php';

//include 'includes/function2.php'; 
$str_values="";
$str_fields="";
$startday="";
$times="";
$start=0;


$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$i_recurAmount =isset($HTTP_POST_VARS["txt_recurAmount"])?$HTTP_POST_VARS["txt_recurAmount"]:"";
$rd_recur_enabled =isset($HTTP_POST_VARS["rd_recur_enabled"])?$HTTP_POST_VARS["rd_recur_enabled"]:"";
$company_user_id=$_SESSION['sessionlogin'];
$str_company_id = $companyInfo['userId'];
$en_ID = $companyInfo['en_ID'];

if($_GET['delete'])
{
	beginTable();
	$qry_delete = "UPDATE `cs_rebillingdetails` set rd_hide=1 WHERE `rd_en_ID` = '$en_ID' AND `rd_subName` = '".quote_smart($_GET['delete'])."' LIMIT 1";
	$result = sql_query_write($qry_delete,$cnn_cs) or dieLog(mysql_error());

	print("Account data Updated.");
	
	endTable('Remove SubAccount');	

}

/*
 $qrt_rebildetails="select checkorcard from cs_rebillingdetails where userId ='$sessionlogin' and recur_charge=$i_recurAmount and company_user_id=0";
if(!($show_total_details =mysql_query($qrt_rebildetails)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
else
{
if(($i_numTrans=mysql_num_rows($show_total_details))==0)
{
				
				$msgtodisplay="<br>No transactions for this recurring Amount <br><br>";
			print "<table align='center' width='100%'><tr height='20'><td  align='center'><font face='verdana' size='1'>&nbsp;$msgtodisplay</td></tr><tr>
		<td align='center' valign='middle' ><a href='editPricePoint.php'><img border='0' src='images/back.jpg'></td>
		</tr></table>";										
				
}
else
{

*/

if(($HTTP_POST_VARS['recur_charge'] || $HTTP_POST_VARS['trial_amount']) && !$etel_repost_warning){

		  beginTable();
//Modification to change recurtransprocessing from transactions to rebilling

$str_recurdate_mode = (isset($HTTP_POST_VARS['recurdatemode'])?quote_smart($HTTP_POST_VARS['recurdatemode']):"");
$i_recur_day = (isset($HTTP_POST_VARS['recur_day'])?quote_smart($HTTP_POST_VARS['recur_day']):"");
$rd_recur_enabled = intval($HTTP_POST_VARS['rd_recur_enabled']);
$i_recur_month = (isset($HTTP_POST_VARS['recur_month'])?quote_smart($HTTP_POST_VARS['recur_month']):"");
$i_trial_amount = (isset($HTTP_POST_VARS['trial_amount'])?quote_smart($HTTP_POST_VARS['trial_amount']):"");
$i_recur_charge = (isset($HTTP_POST_VARS['recur_charge'])?quote_smart($HTTP_POST_VARS['recur_charge']):"");
$i_trial_days = (isset($HTTP_POST_VARS['trial_days'])?quote_smart($HTTP_POST_VARS['trial_days']):"");
$i_txt_description = (isset($HTTP_POST_VARS['txt_description'])?quote_smart($HTTP_POST_VARS['txt_description']):"");
$rd_ibill_landing_html = (isset($HTTP_POST_VARS['rd_ibill_landing_html'])?quote_smart(html_entity_decode(stripslashes($HTTP_POST_VARS['rd_ibill_landing_html']))):"");
$chk_enable_landing = intval($HTTP_POST_VARS['chk_enable_landing']);

if(!$i_recur_charge) $rd_recur_enabled = 0;
if(!$i_trial_amount) $i_trial_days = 0;
if($i_trial_amount !=0 && $i_trial_amount<1.00) {print("Trial Must be greater than 1.00");$fail = true;}
if($i_recur_charge !=0 && $i_recur_charge<1.00) {print("Recur Charge Must be greater than 1.00");$fail = true;}
if($i_trial_amount !=0 && $i_trial_amount>$companyInfo['cd_max_transaction'] && $companyInfo['cd_max_transaction']>0) {print("Trial Must be less than ".$companyInfo['cd_max_transaction']);$fail = true;}
if($i_recur_charge !=0 && $i_recur_charge>$companyInfo['cd_max_transaction'] && $companyInfo['cd_max_transaction']>0) {print("Recur Charge Must be less than ".$companyInfo['cd_max_transaction']);$fail = true;}
if($i_recur_charge==0 && $i_trial_amount==0) {print("Invalid Price Point. Please specify a Price.");$fail = true;}

if(!$fail)
{
	$rd_subName = quote_smart($_POST['rd_subName']);
	if(!$rd_subName)
	{
		$sql = "SELECT MAX(RIGHT(rd_subName,5)) as max FROM `cs_rebillingdetails` WHERE `rd_en_ID` = '$en_ID'";
		$result = sql_query_read($sql,$cnn_cs) or dieLog(mysql_error());
	
		$Max = mysql_fetch_assoc($result);
		$newInc = intval($Max['max']);
		if($newInc <= 100) $newInc = 100;
		$newInc++;
		$rd_subName = $company_user_id."-".sprintf('%05d', $newInc);
	}
	
	$update = "registered ";
	
	if(!$rd_recur_enabled)$i_recur_day=0;
	if($i_recur_day>0 && $i_trial_days==0) $i_trial_days = 3;
	
	if($rd_ibill_landing_html && $chk_enable_landing) $rd_ibill_landing_html = "'".$rd_ibill_landing_html."'";
	else $rd_ibill_landing_html = 'NULL';
	
	$sql_fields = "
			`recur_day` = '$i_recur_day', 
			`recur_charge` = '$i_recur_charge', 
			`company_user_id` = '$company_user_id', 
			`rd_en_ID` = '".$companyInfo['en_ID']."', 
			`rd_initial_amount` = '$i_trial_amount', 
			`rd_trial_days` = '$i_trial_days', 
			`rd_description`  = '$i_txt_description',
			`rd_hide`  = 0,
			`rd_recur_enabled` = '$rd_recur_enabled',
			`rd_ibill_landing_html`  = $rd_ibill_landing_html,
			`rd_subName` = '$rd_subName'";
	
	
	$qry_insert_details="
		INSERT INTO `cs_rebillingdetails` 
		set $sql_fields on duplicate key update $sql_fields
		";

	
	 $rst_insert=sql_query_write($qry_insert_details,$cnn_cs) or die(mysql_error());

	$subAccount = mysql_insert_id($cnn_cs);
	if ($rd_subaccount) $subAccount = $rd_subaccount;
	$subAccountName = $subAcc['rd_subName'];
	$msgtodisplay="<br>Sub Account '$rd_subName' Updated successfully.<br>To use this subaccount, make sure to include the entire subaccount name '$rd_subName' in your integration form.<br> ";
	print "<table align='center' width='100%'><tr height='20'><td  align='center'><font face='verdana' size='1'>&nbsp;$msgtodisplay</td></tr><tr>
	</tr></table>";
				
}	

	endTable('Recurring Transaction',NULL,true,true);	
	die();
}
 $sql = "SELECT * FROM `cs_rebillingdetails` WHERE `rd_hide` = 0 AND `rd_en_ID` = '$en_ID' ORDER BY `rd_subaccount` DESC ";
if(!($result = sql_query_read($sql,$cnn_cs)))
{
	print(mysql_errno().": ".mysql_error()."<BR>");
	print ($qry_update."<br>");
	print("Failed to access company Sub Accounts");
	exit();
}
else
{
?>
    <p align="center"><font face="verdana" size="1"><a href="editPricePoint.php">Add a Sub Account </a></font>&nbsp;
      <script language="javascript">
function removeQuery(name)
{
	return confirm("Are you sure you want to delete Account '"+name+"'?");
	
}

function generateCode()
{

	var Mode = document.getElementById('mode').value;
	var ReferenceID =document.getElementById('cs_Reference_ID').value;
	var SubAccount = document.getElementById('rd_subName').value;
	var Description = document.getElementById('rd_subName').options[document.getElementById('rd_subName').selectedIndex].text;
	if(!Description) Description = SubAccount;
	var HrefFile = "<?=$_SESSION['gw_integration_site']?>/"+Mode;
	var Href = HrefFile+"?mt_reference_id="+ReferenceID+"&mt_subAccount="+SubAccount+"&mt_prod_desc="+Description;


	var Content = "";
	Content += "<a href='"+Href+"'>Purchase \'"+Description+"\'</a><br>\n";
	document.getElementById('link1').value = Content;
	
	var Content = "";	
	Content += "<form name='FrmPayment' action='"+HrefFile+"' method='POST'>\n";
	Content += "<input type='hidden' name='mt_reference_id' value='"+ReferenceID+"'>\n";
	Content += "<input type='hidden' name='mt_language' value='eng'>\n";
	Content += "<input type='hidden' name='mt_subAccount' value='"+SubAccount+"'>\n";
	Content += "<input type='hidden' name='mt_prod_desc' value='"+Description+"'>\n";
	Content += "<input type='submit' name='Button' value='Purchase "+Description+"'>\n";
	Content += "</form>\n";
	document.getElementById('form1').value = Content;
}

function getPinCodes_Complete(response)
{
	//alert(response.responseText);
	var data = JSON.parse(response.responseText);
	obj_element = $('pincodes');
	pin_mode = $F('pin_mode');
	mode_str = 'PinCodes';
	if(pin_mode=='userpass') mode_str = 'Username / Password';
	last_used = -1;
	
	obj_element.options.length=0;

	var len =data['pinInfo_list'].length;
	for (var i = 0;i<len;i++)
	{
		if(data['pinInfo_list'][i]['pc'])
		{
			if(last_used != data['pinInfo_list'][i]['used'])
			{
				last_used = data['pinInfo_list'][i]['used'];
				obj_element.options.length=obj_element.options.length+1;
				obj_element.options[obj_element.options.length-1].text=mode_str + ' ' + (last_used==0?'Unused':'That Have Been Used') + ' (' + data['found_num'][last_used] + ')';
				obj_element.options[obj_element.options.length-1].disabled=true;
				obj_element.options[obj_element.options.length-1].style.color = "#000000";
				obj_element.options[obj_element.options.length-1].style.fontWeight = "bold";
				obj_element.options[obj_element.options.length-1].style.textAlign = "center";
			}	
		
			obj_element.options.length=obj_element.options.length+1;
			obj_element.options[obj_element.options.length-1].value=data['pinInfo_list'][i]['pi'];
			obj_element.options[obj_element.options.length-1].text=data['pinInfo_list'][i]['pc'];
			transId = parseInt(data['pinInfo_list'][i]['td']);
			if(transId>0) 
			{
				obj_element.options[obj_element.options.length-1].td=transId;
				obj_element.options[obj_element.options.length-1].onclick=function () {document.location.href = 'viewreportpage.php?id='+this.td;}
				obj_element.options[obj_element.options.length-1].style.textDecoration = "underline";
			}
			if(data['pinInfo_list'][i]['si']) 
				obj_element.options[obj_element.options.length-1].text+=' - (Subscription: '+data['pinInfo_list'][i]['si']+')';
			
			if(pin_mode=='userpass')
				obj_element.options[obj_element.options.length-1].text+= " / " + data['pinInfo_list'][i]['pass'];
			
			if(data['pinInfo_list'][i]['used'] == 1)
			{
				obj_element.options[obj_element.options.length-1].style.color = "#FF0000";
			}
		}
	}	
	if(data['deleted_num']) alert(data['deleted_num']+' Item(s) Purged!');
	if(data['created_num']) alert(data['created_num']+' Item(s) Created!');
}

function getPinCodes(func,num)
{
	subAccount = $F('pc_subAccount');
	mode = $F('pin_mode');
	
	var url = '<?=$etel_domain_path?>/query_JOSN.php?';
	var pars = 'func='+func+'&gn='+num+'&sa=' + subAccount + '&mode=' + mode;
	//document.location.href=url+'?'+pars;
	var myAjax = new Ajax.Request( url, { method: 'post', parameters: pars, onComplete: getPinCodes_Complete }); 
//	$('testarea').value = 
//	alert(url + pars + "\n" + myAjax.request.status);
}

function downloadPinCodes()
{
	subAccount = $F('pc_subAccount');
	mode = $F('pin_mode');
	
	liststr = "";
	var length = $('pincodes').length;
	for(i=0;i<length;i++)
	{
		if($('pincodes').options[i].selected) liststr += '&pi[]='+$('pincodes').options[i].value;
	}
	
	var url = '<?=$etel_domain_path?>/query_JOSN.php?';
	var pars = 'func=downloadPincode&mode='+mode+'&sa=' + subAccount + liststr;
	//alert(url+pars);
	document.location.href = url+pars;
	
}

function purgePinCodes()
{
	subAccount = $F('pc_subAccount');
	mode = $F('pin_mode');
	var liststr = "";
	
	
	var length = $('pincodes').length;
	for(i=0;i<length;i++)
	{
		if($('pincodes').options[i].selected) liststr += '&pi[]='+$('pincodes').options[i].value;
	}
	
	var url = '<?=$etel_domain_path?>/query_JOSN.php?';
	var pars = 'func=purgePincode&sa=' + subAccount + liststr;
	//document.location.href=url+'?'+pars;
	
	var myAjax = new Ajax.Request( url, { method: 'post', parameters: pars, onComplete: getPinCodes_Complete }); 
}


var refresh_timeout = null;
      </script>
    </p>
    <?php beginTable() ?>
    <table border="0" cellpadding="0"  height="100">
      <tr align="center" valign="middle">
        <td width="200" height="30"><font face="verdana" size="1">SubAccount</font></td>
        <td height="30"><font face="verdana" size="1">Initial Payment Amount </font></td>
        <td height="30"><font face="verdana" size="1">Recurring Amount</font></td>
        <td width="150" height="30"><font face="verdana" size="1">Schedule</font></td>
        <td width="150" height="30"><font face="verdana" size="1">Action</font></td>
        <td width="150" height="30"><font face="verdana" size="1">Enabled</font></td>
      </tr>
      <?php

	while ($subAcc = mysql_fetch_assoc($result))
	{	
	$recur_mode = "";
	
	$schedule = "Once every ".$subAcc['recur_day']." day(s). <br>";
	if($subAcc['rd_initial_amount'] > 0) $schedule .="Trial Period is ".$subAcc['rd_trial_days']." day(s)";
	if($subAcc['recur_charge'] <= 0) 
	{
		$schedule = "One Time Payment.";
		if($subAcc['rd_trial_days']) $schedule .= "<br>Subscription time is ".$subAcc['rd_trial_days']." days";
	}
	
	
	$subAccountName = $subAcc['rd_subName'];
	?>
      <tr align="center" valign="middle">
        <td height="30"><font face="verdana" size="1">
          <?=$subAccountName?>
          <br>
          <?=substr($subAcc['rd_description'],0,55)?>
          </font></td>
        <td height="30"><font face="verdana" size="1">
          <?=($subAcc['rd_initial_amount']==0?"None":number_format($subAcc['rd_initial_amount'], 2, '.', ''))?>
          </font></td>
        <td height="30"><font face="verdana" size="1">
          <?=($subAcc['recur_charge']==0?"None":number_format($subAcc['recur_charge'], 2, '.', ''))?>
          </font></td>
        <td height="30"><font face="verdana" size="1">
          <?=$schedule?>
          </font></td>
        <td height="30"><font face="verdana" size="1"> <a href='recurTransProcessing.php?delete=<?=$subAcc['rd_subName']?>' onclick="return confirm('Are you sure you want to delete SubAccount <?=$subAcc['rd_subName']?>?');">Delete</a><br><a href='editPricePoint.php?rd_subName=<?=$subAcc['rd_subName']?>'>Edit</a> </font></td>
        <td height="30"><font face="verdana" size="1"> <?=($subAcc['rd_enabled'])?></font></td>
      </tr>
      <?php
	
	}


}

?>
    </table>
    <?php endTable("Your SubAccounts") ?>
    <?php beginTable() ?>
    <table width="100%"  border="1" cellspacing="2" cellpadding="2" class="websites">
      <tr>
        <td>Choose Website: </td>
        <td><select name="cs_Reference_ID" id="cs_Reference_ID" style="font-family:arial;font-size:10px;width:300px">
            <?=func_fill_combo_conditionally("select cs_Reference_ID, cs_URL from `cs_company_sites` where cs_hide=0 AND `cs_en_ID` = '$en_ID' ORDER BY `cs_URL` ASC ",-1,$cnn_cs);?>
          </select></td>
      </tr>
      <tr>
        <td>Choose Price Point:</td>
        <td><select name="rd_subName" id="rd_subName" style="font-family:arial;font-size:10px;width:300px">
            <?=func_fill_combo_conditionally("select rd_subName, concat(rd_subName,' - ',rd_description) from `cs_rebillingdetails` where `rd_hide` = 0 AND `rd_en_ID` = '$en_ID' ORDER BY `rd_subName` ASC ",-1,$cnn_cs);?>
          </select></td>
      </tr>
      <tr>
        <td>Integration Mode:</td>
        <td><select id="mode" style="font-family:arial;font-size:10px;width:300px">
            <option value='PaymentEntry.php'>Live Mode</option>
            <option value='testintegration.php'>Test Mode</option>
          </select></td>
      </tr>
      <tr align="center" height="300">
        <td colspan="2">Form HTML CODE:<br>
          <textarea style="text-align: left;" cols="60" rows="5" id="form1" name="form1"></textarea>
          <br>
          Hyperlink HTML CODE:<br>
          <textarea style="text-align: left;" cols="60" rows="5" id="link1" name="link1"></textarea>
         
        </td>
      </tr>
      <tr align="center">
        <td colspan="2"><input type="button" name="Submit" value="Generate" onClick="generateCode()"></td>
      </tr>
    </table>
    <?php endTable("Generate HTML/Purchase Buttons") ?>
	
    <?php beginTable() ?>
    <table width="100%"  border="1" cellspacing="2" cellpadding="2" class="websites">
      <tr>
        <td>Choose Price Point:</td>
        <td><select name="pc_subAccount" id="pc_subAccount" style="font-family:arial;font-size:10px;width:300px">
            <?=func_fill_combo_conditionally("select rd_subaccount, concat(rd_subName,' - ',rd_description) from `cs_rebillingdetails` where `rd_hide` = 0 AND `rd_en_ID` = '$en_ID' ORDER BY `rd_subName` ASC ",-1,$cnn_cs);?>
          </select></td>
      </tr>
      <tr>
        <td>Password Mode :</td>
        <td><select id="pin_mode" style="font-family:arial;font-size:10px;width:300px">
          <option value="pincode">Pin Codes</option>
          <option value="userpass">Username/Pass</option>
          </select></td>
      </tr>
      <tr align="center" height="300">
        <td colspan="2"><select name="pin_codes" id="pincodes" size="16" style="width:400px;" multiple>
        </select>&nbsp;<br />
        <input type="button" name="pin_purgePincodeInfo" value="Purge Selected PinCodes" onclick="purgePinCodes()" />
        <input type="button" name="pin_purgePincodeInfo" value="Download Selected Pins" onclick="downloadPinCodes()" /></td>
      </tr>
      <tr align="center">
        <td colspan="2"><input type="button" name="pin_getPinCodes" value="Show All PinCodes" onclick="getPinCodes('getPincodeInfo',0)" />
            <input type="button" name="pin_getPinCodes" value="Show Unused PinCodes" onclick="getPinCodes('getPincodeInfo',0)" />
            <br />
            <input type="button" name="pin_getPinCodes" style="font-weight:bold" value="Generate More PinCodes" onclick="getPinCodes('genPincodes',prompt('How many new pins would you like to generate?',50))" />
          <br>
		<!--<textarea id="testarea" name="testarea" cols=40 rows=10></textarea> -->
		</td>
      </tr>
    </table>
    <?php endTable("Manage PinCodes/PasswordAccounts") ?>
    <br>
    <?php

include("includes/footer.php");
	
 




?>
