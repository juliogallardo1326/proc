<?
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway

$allowBank=true;
include("includes/sessioncheck.php");
include("../includes/completion.php");

$loginas = (isset($_REQUEST["loginas"])?trim($_REQUEST["loginas"]):"");
if($loginas)
{
	$etel_debug_mode=0;
	require_once("../includes/dbconnection.php");
	$_SESSION["loginredirect"]="None";
	if($resellerInfo['isMasterMerchant'])	
		$_SESSION["gw_masterMerchant_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_password"]."|Reseller|".$_SESSION['gw_id']."|editCompanyProfile.php?company_id=".$_GET['company_id']);
	$_SESSION["gw_admin_info"] = etelEnc($_SESSION["gw_user_username"]."|".$_SESSION["gw_user_password"]."|Admin|".$_SESSION['gw_id']."|editCompanyProfileAddRates.php?company_id=".$_GET['company_id']);
	
	general_login($_GET['username'],$_GET['password'],"merchant",$_GET['gw_id'],false);
	die();
}

if(!$headerInclude) $headerInclude = "companies";
include("includes/header.php");


$sessionAdmin =isset($_SESSION["sessionAdmin"])?$_SESSION["sessionAdmin"]:"";
$str_update =isset($_REQUEST["update"])?$_REQUEST["update"]:"";
$iCheckBankId ="";
$bank_Creditcard="";$val="";
$numrows=0;$modified=0;

$gw_options=NULL;
if($adminInfo['li_level'] == 'full')
{
	foreach($etel_gw_list as $gw)
		if($gw['gw_database']==$etel_gw_list[$_SESSION['gw_id']]['gw_database']) 
			$gw_options[$gw['gw_id']]=$gw['gw_title'];
	if(is_array($gw_options)) if (sizeof($gw_options)<2) 
		$gw_options = NULL;
}

$trans_activity = (isset($_REQUEST['rad_trans_activity'])?quote_smart($_REQUEST['rad_trans_activity']):"0");
$userid = (isset($_REQUEST['userid'])?quote_smart($_REQUEST['userid']):"");
if($str_update == "yes" && $adminInfo['li_level'] == 'bank') 
{
	$qry_update_user = "update cs_companydetails set activeuser='$trans_activity' where userId='$userid' $bank_sql_limit";
	mysql_query($qry_update_user) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
}


	if ($str_update == "yes") 
	{
		$suspend = (isset($_REQUEST['suspend'])?quote_smart($_REQUEST['suspend']):"");
		$trans_activity = (isset($_REQUEST['rad_trans_activity'])?quote_smart($_REQUEST['rad_trans_activity']):"0");

		$cd_fraudscore_limit = (isset($_REQUEST['cd_fraudscore_limit'])?quote_smart($_REQUEST['cd_fraudscore_limit']):"0");
		$cd_approve_timelimit = (isset($_REQUEST['cd_approve_timelimit'])?quote_smart($_REQUEST['cd_approve_timelimit']):"0");
		$cd_merchant_show_contract = (isset($_REQUEST['cd_merchant_show_contract'])?quote_smart($_REQUEST['cd_merchant_show_contract']):"0");
		$cd_pay_status = (isset($_REQUEST['cd_pay_status'])?quote_smart($_REQUEST['cd_pay_status']):"0");
		$cd_allow_rand_pricing  = (isset($_REQUEST['cd_allow_rand_pricing'])?quote_smart($_REQUEST['cd_allow_rand_pricing']):"0");
		$cd_custom_recur  = (isset($_REQUEST['cd_custom_recur'])?quote_smart($_REQUEST['cd_custom_recur']):"0");
		$cd_enable_price_points = (isset($_REQUEST['cd_enable_price_points'])?quote_smart($_REQUEST['cd_enable_price_points']):"0");
		$cs_monthly_charge = (isset($_REQUEST['cs_monthly_charge'])?quote_smart($_REQUEST['cs_monthly_charge']):"0");
		$cd_max_transaction = (isset($_REQUEST['cd_max_transaction'])?quote_smart($_REQUEST['cd_max_transaction']):"0");
		$cd_max_volume = (isset($_REQUEST['cd_max_volume'])?quote_smart($_REQUEST['cd_max_volume']):"0");
		$virtualterminal_off = (isset($_REQUEST['virtualterminalOff'])?quote_smart($_REQUEST['virtualterminalOff']):"");
		$contact_email=(isset($_REQUEST['contact_email'])?quote_smart($_REQUEST['contact_email']):"");
		$cd_payperiod=(isset($_REQUEST['cd_payperiod'])?quote_smart($_REQUEST['cd_payperiod']):"");
		$cd_paystartday=(isset($_REQUEST['cd_paystartday'])?quote_smart($_REQUEST['cd_paystartday']):"");
		$cd_paydelay=(isset($_REQUEST['cd_paydelay'])?quote_smart($_REQUEST['cd_paydelay']):"");
		$cd_next_pay_day=(isset($_REQUEST['cd_next_pay_day'])?quote_smart($_REQUEST['cd_next_pay_day']):"");
		$cd_paydaystartday=(isset($_REQUEST['cd_paydaystartday'])?quote_smart($_REQUEST['cd_paydaystartday']):"");
		$cd_rollover=(isset($_REQUEST['cd_rollover'])?quote_smart($_REQUEST['cd_rollover']):"");
		$cd_wirefee=(isset($_REQUEST['cd_wirefee'])?quote_smart($_REQUEST['cd_wirefee']):"");
		$cd_appfee=(isset($_REQUEST['cd_appfee'])?quote_smart($_REQUEST['cd_appfee']):"");
		$cd_appfee_upfront=(isset($_REQUEST['cd_appfee_upfront'])?quote_smart($_REQUEST['cd_appfee_upfront']):"");
		$customer_service_phone=(isset($_REQUEST['customer_service_phone'])?quote_smart($_REQUEST['customer_service_phone']):"");
		$send_notification_email=(isset($_REQUEST['send_notification_email'])?quote_smart($_REQUEST['send_notification_email']):"");
		$send_activity_email=(isset($_REQUEST['send_activity_email'])?quote_smart($_REQUEST['send_activity_email']):"");
		$cd_pay_bimonthly=(isset($_REQUEST['cd_pay_bimonthly'])?quote_smart($_REQUEST['cd_pay_bimonthly']):"");
		$cd_ignore=(isset($_REQUEST['cd_ignore'])?quote_smart($_REQUEST['cd_ignore']):"");
		$cd_custom_contract=(isset($_REQUEST['cd_custom_contract'])?quote_smart($_REQUEST['cd_custom_contract']):"");
		$cd_custom_orderpage=(isset($_REQUEST['cd_custom_orderpage'])? html_entity_decode(quote_smart($_REQUEST['cd_custom_orderpage'])):"");
		$gateway_id=(isset($_REQUEST['gateway_id'])?quote_smart($_REQUEST['gateway_id']):"");
		$cd_completion=(isset($_REQUEST['cd_completion'])?quote_smart($_REQUEST['cd_completion']):"");
		$cd_allow_gatewayVT=(isset($_REQUEST['cd_allow_gatewayVT'])?quote_smart($_REQUEST['cd_allow_gatewayVT']):"");
		
		if(!$gateway_id) $gateway_id = $_SESSION['gw_id'];
		
		if($cd_pay_bimonthly=='bimonthly') $cd_payperiod = 14;
		if($cd_pay_bimonthly=='trimonthly') $cd_payperiod = 10;

		$send_reseller_rates_email=(isset($_REQUEST['send_reseller_rates_email'])?quote_smart($_REQUEST['send_reseller_rates_email']):"");
		$send_merchant_rates_email=(isset($_REQUEST['send_merchant_rates_email'])?quote_smart($_REQUEST['send_merchant_rates_email']):"");



		if($strVoiceauthFee =="")$strVoiceauthFee =0;
		if($strChargeBack =="")$strChargeBack =0;
		if($strCredit =="")$strCredit =0;
		if($strDiscountRate =="")$strDiscountRate =0;
		if($strTransactionFee =="")$strTransactionFee =0;
		if($strReserve =="")$strReserve =0;
		if($merchant_discount =="")$merchant_discount =0;
		if($reseller_discount =="")$reseller_discount =0;
		if($total_discount =="")$total_discount =0;
		if($reseller_transfee =="")$reseller_transfee =0;
		if($total_transfee =="")$total_transfee =0;
		if($send_ecommercemail =="")$send_ecommercemail =0;
		if($cancelecommerce_checked =="")$cancelecommerce_checked =0;
		if($iCheckBankId =="")$iCheckBankId =0;
		if($bank_Creditcard =="")$bank_Creditcard =0;
		if($check_integrate=="")$check_integrate=0;
		if($virtualterminal_off==0 || $virtualterminal_off=="")$virtualterminal_off=0;
		if($recurtransactionoff==0 || $recurtransactionoff=="")$recurtransactionoff=0;
		if($rebilltransactionoff==0 || $rebilltransactionoff=="")$rebilltransactionoff=0;
		$date=date("Y-m-d H:i:s");

		$qry_check_userexist = "select cd.*,rd.* from cs_companydetails as cd 
		left join cs_resellerdetails as rd on cd.reseller_id = rd.reseller_id
		 where cd.userId='$userid' $bank_sql_limit";
		$result=mysql_query($qry_check_userexist) or dieLog(mysql_error()." ~ $qry_check_userexist");
		$companyInfo = mysql_fetch_assoc($result);

		$completion="";
		$bank_update_sql = "";
		if($strUnsubscribe!=$companyInfo['send_mail'])
		{
			if($strUnsubscribe)
			{
				removeListEmail($companyInfo['email']);
				removeListEmail($companyInfo['contact_email']);
			}
			else
			{
				addListEmail($companyInfo['email'],"Admin Unsubscribed Email",$companyInfo['userId'],'merchant','unsubscribe');
				addListEmail($companyInfo['contact_email'],"Admin Unsubscribed Email",$companyInfo['userId'],'merchant','unsubscribe');
			}
		}

		if($cd_custom_contract && !$companyInfo['cd_custom_contract'])
		{
			$contract = genMerchantContract(&$companyInfo);
			$sql = "insert into cs_email_templates set et_name='merchant_contract', et_custom_id='".$companyInfo['userId']."', et_title='".quote_smart($companyInfo['companyname'])." Contract', et_access='admin', et_to_title='".quote_smart($companyInfo['companyname'])."', et_subject='Custom Merchant Contract for ".quote_smart($companyInfo['companyname'])."', et_htmlformat='".quote_smart($contract['et_htmlformat'])."', et_catagory='Merchant'";
			$result=mysql_query($sql) or etelPrint(mysql_error());
			$cd_custom_contract = mysql_insert_id();
		}
		else 
		if(!$cd_custom_contract && $companyInfo['cd_custom_contract'])
		{
			$sql = "delete from cs_email_templates where et_name='merchant_contract' and et_custom_id='".$companyInfo['userId']."'";
			$result=mysql_query($sql) or dieLog(mysql_error());
			$cd_custom_contract = 'null';
		} 
		else $cd_custom_contract = intval($companyInfo['cd_custom_contract']);

		if($cd_merchant_show_contract==1 && $companyInfo['cd_completion']<=3)
			$completion = ' cd_completion=4, ';

		if($trans_activity==1 && !$companyInfo['activeuser'])
		{
			toLog('turnedlive','merchant', '', $companyInfo['userId']);
			$completion = ' cd_completion=10, ';
		}

		if($trans_activity==0 && $companyInfo['cd_completion']==10)
		{
			toLog('requestlive','merchant', '', $companyInfo['userId']);
			$completion = ' cd_completion=9, ';
		}


		// Email Merchant
		unset($data);
		$data['email'] = $companyInfo['email'];
		$data['companyname'] = $companyInfo['companyname'];
		$data['full_name'] = $companyInfo['companyname'];
		$data['username'] = $companyInfo['username'];
		$data['password'] = $companyInfo['password'];
		$data['Reference_ID'] = $companyInfo['ReferenceNumber'];
		$data["gateway_select"] = $companyInfo['gateway_id'];
		$updateRateRequest = "";

		if($RequestRatesMode=='Commit')
		{
			$updateRateRequest = "cd_reseller_rates_request = '0', ";
			send_email_template('contract_notification_email',$data);
		}

		if($send_notification_email==1 && $companyInfo['send_mail']==1)
		{
			send_email_template('contract_notification_email',$data);
		}

		if($send_activity_email==1 && $companyInfo['send_mail']==1)
		{
			send_email_template('active_notification_email',$data);
		}

		if($send_merchant_rates_email==1 && $companyInfo['send_mail']==1)
		{
			$data['resellername'] = $companyInfo['reseller_companyname'];
			send_email_template('merchant_rates_notification_email',$data);
			$updateRateRequest = "cd_reseller_rates_request = '1', ";
		}

		if($send_reseller_rates_email==1 && $companyInfo['reseller_sendmail']==1)
		{
			$data['email'] = $companyInfo['reseller_email'];
			$data['resellername'] = $companyInfo['reseller_companyname'];
			$data['companyname'] = $companyInfo['companyname'];
			$data['full_name'] = $companyInfo['reseller_contactname'];
			$data['username'] = $companyInfo['reseller_username'];
			$data['password'] = $companyInfo['reseller_password'];
			$data['Reference_ID'] = $companyInfo['ReferenceNumber'];
			send_email_template('reseller_rates_notification_email',$data);
			$updateRateRequest = "cd_reseller_rates_request = '1', ";
		}

// End Email

		if ($cd_custom_orderpage) 
			$cd_custom_orderpage = "'".$cd_custom_orderpage."'";
		else 
			$cd_custom_orderpage = "NULL";

		$cd_has_been_active = "";
		
		if ($trans_activity) 
			$cd_has_been_active = "cd_has_been_active=1, ";

		$qry_update_user  =  "
			update 
				cs_companydetails 
				left join cs_company_sites on cs_company_id = userId 
			set 
				$completion 
				$updateRateRequest
				$cd_has_been_active

				suspenduser='$suspend',
				send_mail='$strUnsubscribe',
				block_virtualterminal='$virtualterminal_off',
				block_recurtransaction='$recurtransactionoff',
				block_rebilltransaction='$rebilltransactionoff',

				cd_pay_bimonthly='$cd_pay_bimonthly',
				cc_customer_fee='$cc_customer_fee',
				cd_appfee_upfront='$cd_appfee_upfront',
				cd_cc_bank_extra='$cd_cc_bank_extra',
				cd_merchant_show_contract='$cd_merchant_show_contract',
				cd_allow_gatewayVT='$cd_allow_gatewayVT',
				gateway_id='$gateway_id', 
				cs_monthly_charge = '$cs_monthly_charge',
				send_ecommercemail = '$send_ecommercemail',

				cancel_ecommerce_letter = '$cancelecommerce_checked', 
				cd_enable_price_points ='$cd_enable_price_points', 
				cd_allow_rand_pricing='$cd_allow_rand_pricing', 
				cd_custom_recur = '$cd_custom_recur', 
				
				cd_next_pay_day='$cd_next_pay_day', 
				cd_fraudscore_limit='$cd_fraudscore_limit', 
				cd_orderpage_settings='$cd_orderpage_settings', 
				cd_orderpage_useraccount='$cd_orderpage_useraccount', 
				cd_approve_timelimit='$cd_approve_timelimit',
				cd_custom_orderpage=$cd_custom_orderpage, 
				cd_custom_contract='$cd_custom_contract', 
				cd_pay_status='$cd_pay_status',
				cd_ignore='$cd_ignore',
				cd_max_transaction='$cd_max_transaction',
				cd_max_volume='$cd_max_volume',
				contact_email='$contact_email',
				customer_service_phone='$customer_service_phone',
				cd_payperiod='$cd_payperiod',
				cd_paystartday='$cd_paystartday',
				cd_paydelay='$cd_paydelay',
				cd_paydaystartday='$cd_paydaystartday',
				cd_rollover='$cd_rollover',
				cd_wirefee='$cd_wirefee',
				cd_appfee='$cd_appfee',
				cd_completion='$cd_completion'
		";

		if($adminInfo['li_level'] == 'full') 
			$qry_update_user .= ",activeuser='$trans_activity'";

		$qry_update_user .=  " where userId='$userid'";

		mysql_query($qry_update_user) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	}

$company_id = isset($_REQUEST['company_id'])?$_REQUEST['company_id']:"";

if ($company_id == "") 
	$company_id = (isset($_REQUEST['userid'])?quote_smart($_REQUEST['userid']):"");

if ($company_id == "") 
	$company_id = (isset($_REQUEST['userIdList'])?quote_smart($_REQUEST['userIdList']):"");

$companyname = isset($_REQUEST['companyname'])?$_REQUEST['companyname']:"";
$companytype = isset($_REQUEST['companymode'])?$_REQUEST['companymode']:"";
$companytrans_type = isset($_REQUEST['companytrans_type'])?quote_smart($_REQUEST['companytrans_type']):"";

$script_display ="";
$qry_select_companies = "select * from cs_companydetails where userid='$company_id' $bank_sql_limit";
$qry_select_company_ext="select * from cs_companydetails_ext where userId='$company_id' ";

if($qry_select_companies != "")
	$show_sql = mysql_query($qry_select_companies) or dieLog(mysql_errno().": ".mysql_error()."<BR>");

if($qry_select_company_ext != "")
	$show_sql_ext = mysql_query($qry_select_company_ext) or dieLog(mysql_errno().": ".mysql_error()."<BR>");

if($cs_companydetails_ext=mysql_fetch_array($show_sql_ext))
{
	$mastercurrency=$cs_companydetails_ext[1];
	$visacurrency=$cs_companydetails_ext[2];
	$customerservice_email=$cs_companydetails_ext[5];
}
else
{
	$mastercurrency="";
	$visacurrency="";
	$customerservice_email="";
}

	$qry_select_companies = "
	select 
		*
	from 
		cs_companydetails 
	where 
		userid='$company_id' 
		$bank_sql_limit
	";
	if($qry_select_companies != "")
		$show_sql =sql_query_read($qry_select_companies) or dieLog(mysql_error()." ~ $qry_select_companies");

	$companyInfo = mysql_fetch_assoc($show_sql) or exit($qry_select_companies);

if($companyInfo['state']=="")
	$state=str_replace("\n",",\t",$companyInfo['ostate']);
else
	$state=str_replace("\n",",\t",$companyInfo['state']);

if($companyInfo['transaction_type'] == "tele") 
{
	$script_display ="yes";
	$sendecommerce_diplay = "none";
}
else 
{
	$script_display ="none";
	$sendecommerce_diplay = "yes";
}

if($companyInfo['send_ecommercemail'] == 1) 
	$sendecommerce_checked = "checked";
else 
	$sendecommerce_checked = "";

if($companyInfo['cancel_ecommerce_letter'] == 1) 
	$cancelecommerce_checked = "checked";
else 
	$cancelecommerce_checked = "";

$cd_enable_price_points = $companyInfo['cd_enable_price_points'];
$cd_allow_rand_pricing = $companyInfo['cd_allow_rand_pricing'];
$cd_custom_recur = $companyInfo['cd_custom_recur'];
$str_currency ="USD";

if($companyInfo['bank_Creditcard']==19)
{
	$custom_text = "Forcetronix Inc.<BR>
	U12 Gamma Commercial Complex, #47<BR>
	Rizal Highway cor. Manila Avenue,<BR>
	Subic Bay Freeport, Olongapo City<BR>
	Philippines<BR>
	Is an authorized payment service provider for ";
}

$cust_cntry = urlencode(func_get_country($companyInfo['country'],'co_full'));
$custom_text .="";

if(!$companyInfo['cd_custom_orderpage']) 
	$companyInfo['cd_custom_orderpage'] = $custom_text;

$str_bank_table = "cs_bank where 1 ";

?>
<script language="javascript" src="../scripts/general.js"></script>
<script language="javascript">

	function updatePayDelay()
	{
		document.getElementById('cd_paydelay').value=
			parseInt(document.getElementById('cd_paydaystartday').value)+
			parseInt(document.getElementById('cd_paydelayweeks').value)-
			parseInt(document.getElementById('cd_paystartday').value);
		if(document.getElementById('cd_pay_bimonthly'))document.getElementById('cd_paydelay').value=document.getElementById('cd_paydelayweeks').value;
	}
	
	function emailsubmit() 
	{
		document.Frmcompany.method="POST";
		document.Frmcompany.submit();
	}
	
	function func_ischanged()
	{ 
		updatePayDelay();
		addRatesFees();
		return true;
	}
	
	function addRatesFees() {
	
		document.getElementById('cc_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('cc_reseller_trans_fees').value)+parseFloat(document.getElementById('cc_total_trans_fees').value)))*.01;
		document.getElementById('ch_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('ch_reseller_trans_fees').value)+parseFloat(document.getElementById('ch_total_trans_fees').value)))*.01;
		document.getElementById('web_merchant_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('web_reseller_trans_fees').value)+parseFloat(document.getElementById('web_total_trans_fees').value)))*.01;
	
		document.getElementById('cc_merchant_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('cc_reseller_discount_rate').value)+parseFloat(document.getElementById('cc_total_discount_rate').value)))*.01;
		document.getElementById('ch_merchant_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('ch_reseller_discount_rate').value)+parseFloat(document.getElementById('ch_total_discount_rate').value)))*.01;
	}
	
	function funcOpen3VT(iCompanyId) 
	{
		window.open("vtusers.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
	}
	function funcOpenTSR(iCompanyId) 
	{
		window.open("tsruserlist.php?id="+iCompanyId,null,"height=600,width=500,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
	}
	function funcOpenEcom(iCompanyId) 
	{
		window.open("ecomlist.php?id="+iCompanyId,null,"height=600,width=800,status=yes,toolbar=no,menubar=no,location=no,scrollbar=yes");
	}
	function updateEmailNotification(obj)
	{
		if(document.getElementById('send_notification_email')) document.getElementById('send_notification_email').disabled = !obj.checked;
		if(document.getElementById('send_notification_email')) document.getElementById('send_notification_email').checked = obj.checked;
	}
	function updateScheduleMethod(obj)
	{
		disable = false;
		if(obj) disable = true;
		if(document.getElementById('cd_paystartday')) document.getElementById('cd_paystartday').disabled = disable;
		if(document.getElementById('cd_paydaystartday')) document.getElementById('cd_paydaystartday').disabled = disable;
		if(document.getElementById('cd_payperiod')) document.getElementById('cd_payperiod').disabled = disable;
		if(disable) if(document.getElementById('cd_payperiod')) document.getElementById('cd_payperiod').value = 14;
	}
	function commitRequest()
	{
		document.getElementById('cc_reseller_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_trans_fees').value)))/100;
		document.getElementById('ch_reseller_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_trans_fees').value)))/100;
		document.getElementById('web_reseller_trans_fees').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_trans_fees').value)))/100;
	
		document.getElementById('cc_reseller_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_discount_rate').value)))/100;
		document.getElementById('ch_reseller_discount_rate').value = Math.round(100*(parseFloat(document.getElementById('request_cc_reseller_discount_rate').value)))/100;
		addRatesFees();
		document.getElementById('RequestRatesMode').value='Commit';
	}
</script>
<?
$status = $etel_completion_array[intval($companyInfo['cd_completion'])]['txt'];
$bold = $etel_completion_array[intval($companyInfo['cd_completion'])]['style'];
$request=@unserialize($companyInfo['cd_reseller_rates_request']);

?>
	<table>
	<tr>
	<td><a href="editCompanyProfileAccess.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/merchantdetails_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileWire.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/bankprocessing_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileRates.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/ratesandfees_tab1.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	<td><a href="editCompanyProfileDocs.php?company_id=<?= $company_id?>"><IMG SRC="<?=$tmpl_dir?>/images/uploadown_tab.gif" WIDTH="128" HEIGHT="32" BORDER="0" ALT=""></a></td>
	</tr>
	</table>

	<center>
	<a href="<?="?username=".$companyInfo['username']."&password=".$companyInfo['password']."&gw_id=".$_SESSION['gw_id']."&company_id=".$companyInfo['userid']?>&loginas=1">Login as <?= $companyInfo['companyname'] ?></a>
	</center>

<?
beginTable();
?>
<input type="hidden" name="userid" value="<?=$companyInfo['userId']?>"></input>
<input type="hidden" name="update" value="yes"></input>

<table width="98%" cellpadding="0" cellspacing="0" align="center" border="0">
	<tr>
		<td align="center" width="50%" valign="top">
			<table cellpadding='0' cellspacing='0'>
				<tr>
					<td height="30" colspan=2 align="center" valign="center" bgcolor="#CCCCCC" class="cl3">
						<font face="verdana" size="1" color="#FFFFFF"><b>Pay Period Information </b></font>
					</td>
				</tr>



			</table>
			<br>
		</td>
	</tr>
</table>

<center>
		<input type="image" id="modifycompany" SRC="<?=$tmpl_dir?>/images/modifycompanydetails.jpg"></input>
</center>
			
<?
	endTable("Edit Company Details","editCompanyProfileAddRates.php");

include("includes/footer.php");
//-------------	Function for filling freequency --------------
//------------------------------------------------------------
function funcFillFreequency($iFreequency) 
{
	$arrFre[1] = "Daily";
	$arrVal[1] = "D";
	$arrFre[2] = "Weekly";
	$arrVal[2] = "W";
	$arrFre[3] = "Monthly";
	$arrVal[3] = "M";
	for ( $iLoop = 1 ;$iLoop < 4 ;$iLoop++ ) 
		if ( $iLoop == $iFreequency ) 
			echo("<option value=\"$arrVal[$iLoop]\" selected>$arrFre[$iLoop]</option>");
		else 
			echo("<option value=\"$arrVal[$iLoop]\">$arrFre[$iLoop]</option>");
}

//------------- Function for filling week days ----------------
//-------------------------------------------------------------

function funcFillWeekDays($iWeekDay) 
{
	$arrWeekDays[1] = "Monday";
	$arrWeekDays[2] = "Tuesday";
	$arrWeekDays[3] = "Wednesday";
	$arrWeekDays[4] = "Thursday";
	$arrWeekDays[5] = "Friday";
	$arrWeekDays[6] = "Saturday";
	$arrWeekDays[7] = "Sunday";

	for ($iLoop = 1;$iLoop < 8;$iLoop++ ) 
		if ( $iLoop == $iWeekDay ) 
			echo("<option value=\"$iLoop\" selected>$arrWeekDays[$iLoop]</option> ");
		else 
			echo("<option value=\"$iLoop\">$arrWeekDays[$iLoop]</option> ");
}
//------------- Function for entering card currencies in companydetails_ext ----------------
//-------------------------------------------------------------

function func_company_ext_entry($userid,$mastercurrency,$visacurrency,$scanorder_merchantid,$scanorder_password,$customerservice_email,$cnn_cs)
{
	$qry_exist="select * from cs_companydetails_ext where userid='$userid'";
	if(!$rst_exist=mysql_query($qry_exist,$cnn_cs))
		echo "Cannot execute Query";
	else
	{
		$num=mysql_num_rows($rst_exist);
		if($num==0)
			$qry_companyext="insert into cs_companydetails_ext (userId,processingcurrency_master,processingcurrency_visa,scanorder_merchantid,scanorder_password, customerservice_email ) values('$userid','$mastercurrency','$visacurrency','$scanorder_merchantid','$scanorder_password','$customerservice_email')";
		else
			$qry_companyext="update cs_companydetails_ext set processingcurrency_master='$mastercurrency',processingcurrency_visa='$visacurrency',scanorder_merchantid='$scanorder_merchantid',scanorder_password='$scanorder_password',customerservice_email='$customerservice_email' where userid='$userid'";

		if(!$rst_update=mysql_query($qry_companyext,$cnn_cs))
			echo "Cannot execute query";
	}
}
?>
