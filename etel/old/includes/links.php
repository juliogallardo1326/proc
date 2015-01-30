<?php	
unset($link);
unset($sub_header);
unset($main_header);
$sub_header= array();
$main_header= array();
$link= array();
$udir = $etel_domain_path."/";
$display_todo_list=NULL;

if($headerInclude) 
	$pageConfig['SubHeader'] = $headerInclude;

foreach($_REQUEST as $k => $c)
	$etel_postback.= "<input type='hidden' name='$k' value='$c' >";

if($_SESSION["userType"]=="CustomerService")
{ 
	$udir = $etel_domain_path."/service/";
	$link['href'] = $udir."livetree.php?";
	$link['text'] = "New Call";
	$main_header['links'][] = $link;
}
else
if($_SESSION["userType"]=="Merchant")
{ 
	$udir = $etel_domain_path."/";
	$link['href'] = $udir."Listdetails.php";
	$link['text'] = "Start Here";
	if($companyInfo['cd_completion']<10 && is_array($companyInfo)) $main_header['links'][] = $link;

		
	$link = array();
	$link['href'] = $udir."profile_blank.php";
	$link['text'] = "Profile";
	$main_header['links'][] = $link;
	$link['href'] = $udir."websetup_blank.php";
	$link['text'] = "Tools";
	$main_header['links'][] = $link;
	$link['href'] = $udir."tracking_Smart.php";
	$link['text'] = "Affiliates and Tracking<img border=0 src='$udir"."images/new.gif'>";
	//$main_header['links'][] = $link;
	$link['href'] = $udir."ledger.php";
	$link['text'] = "Ledgers";
	$main_header['links'][] = $link;
	$link['href'] = $udir."report_Smart.php";
	$link['text'] = "Transactions";
	$main_header['links'][] = $link;
	$link['href'] = $udir."Support.php";
	$link['text'] = "Support";
	$main_header['links'][] = $link;
	
	if($companyInfo['cd_completion']<=0) $display_todo_list.="
- To Begin, Please Complete Your Merchant Application.
  Please Complete the entire application and omit no sections to ensure there are no delays in requesting rates.  
** If at any time you have a question, please select the Support Option on the left and create a support ticket.
** This Information Box will give you detailed directions on how to set up your Merchant Account every step of the way. ";
	
	if($companyInfo['cd_completion']==1) $display_todo_list.="Please Request Your Rates and Fees.\n  In your StartHere section, you may select 'Request Rates and Fees' shown at the top of your page.\n  After you have put in the request, you will be notified by email when your Merchant Contract is ready for viewing.";
	//if($companyInfo['cd_completion']==2)$display_todo_list.="lease Upload your Merchant Documents at this time.";
	if($companyInfo['cd_completion']>=3 && $companyInfo['cd_completion']<4) $display_todo_list.="Your Rates and Fees have been requested successfully. \n  Please check your email frequently. \n  We will soon send you a confirmation that your contract is available.";
	if($companyInfo['cd_completion']==4) $display_todo_list.="Your Merchant Contract is now available. Please view and Sign your Merchant Contract.";
	if($companyInfo['cd_completion']==5) $display_todo_list.="You have signed your Merchant Contract Online.\n  Now Please Print out, Sign, Scan, and then Upload your Merchant Contract to our system.\n  Please Scan and Upload Your Drivers License/Passport, Articles of Incorporation, and Previous Processing History.  \nOnce you have uploaded all your required files, you may begin integration of your site.";
	
	if($companyInfo['cd_completion']==6) $display_todo_list.="At this time please begin to integrate your site.\nNote: All the tools you will need can be found in the Tools Section.\n  1. Create/Update your Websites.\n  2. Create/Update your Price Points.\n  3. Download the Payment Integration Guide and integrate your site.\n  4. Optionally set up password management integration for your subscription-based websites.\n  5. Test at <strong>least one TEST Transaction</strong> in <strong>Test Mode</strong> to ensure that your website is integrated correctly.";
	
	if($companyInfo['cd_completion']==7) $display_todo_list.="You have successfully tested transactions.\n  This means that your website is correctly integrated with ours.\n  Please set your integration to <strong>Live Mode</strong> and put in the request to go live.\n    The 'Go Live' option is in the top right of your taskbar.\n  If you don't know how to set your integration to live mode, please refer to the integration guide, or start a support ticket.";
	
	
	
	if($companyInfo['cd_completion']==9) $display_todo_list.="Congradulations! You have successfully integrated with ".$_SESSION['gw_title']."!\n  You should recieve a notification soon when you are turned live.";
	
	if($companyInfo['cd_completion']==10 && $pageConfig['SubHeader']=="startHere") $pageConfig['SubHeader']="profile";

	if($companyInfo['cd_enable_tracking']=='on')
	{
		$sql = "select count(transactionId) from cs_transactiondetails as td where td_tracking_id is null and status='A' and cancelstatus='N' and cd_enable_tracking = 'on' and userId = '".$companyInfo['userId']."'";
		$result = mysql_query($sql);
		if($result)
		{
			$trackingOrders = mysql_result($result,0,0);
			if( $display_todo_list)  $display_todo_list.="\n";
			 $display_todo_list.="<a href='reportBottom.php?period=p&opt_from_month=11&opt_from_day=28&opt_from_year=2003&untracked_orders=1&opt_to_month=11&opt_to_day=28&opt_to_year=2009&crorcq=A' style='color:#FF0000;'>You have $trackingOrders Orders that need tracking numbers</a>.<BR>&nbsp;&nbsp;To prevent refunds, please update these orders as soon as possible.<BR>&nbsp;&nbsp;There will be a grace period for all existing orders at this time to help you catch up on your order history.<BR>&nbsp;&nbsp;Please create a support ticket if you have any questions.";
		}
	}
	if($pageConfig['SubHeader']=="startHere"){	
	//etelPrint($companyInfo['cd_completion']);
	//$companyInfo['cd_completion']
		$link=NULL;
		$link['href'] = $udir."application.php";
		$link['text'] = "1. Application";
		$link['selected']=($companyInfo['cd_completion']<=1);
		//$link['disabled']=($companyInfo['cd_completion']<0);
		$sub_header['links'][] = $link;
		$link['href'] = $udir."merchantContract.php";
		$link['text'] = "2. Request Rates";
		$link['selected']=($companyInfo['cd_completion']==2);
		$link['disabled']=($companyInfo['cd_completion']!=2 );
		$sub_header['links'][] = $link;
		$link['href'] = $udir."merchantContract.php";
		if($companyInfo['cd_completion']>=5) $link['text'] = "3. View/Print Contract";
		else $link['text'] = "3. Sign Contract";
		$link['selected']=($companyInfo['cd_completion']==4);
		$link['disabled']=($companyInfo['cd_completion']<4);
		$sub_header['links'][] = $link;
		$link['href'] = $udir."uploadDocuments.php";
		$link['text'] = "4. Upload Documents";
		$link['selected']=($companyInfo['cd_completion']==5);
		$link['disabled']=($companyInfo['cd_completion']<3);
		$sub_header['links'][] = $link;
		$link['href'] = $udir."integrate_active.php";
		$link['text'] = "5. Integrate Your Site";
		$link['selected']=($companyInfo['cd_completion']==6);
		$link['disabled']=($companyInfo['cd_completion']<6);
		$sub_header['links'][] = $link;
		$link['href'] = $udir."Listdetails.php?goLive=1";
		$link['text'] = "6. Go Live!";
		$link['selected']=($companyInfo['cd_completion']==7);
		$link['disabled']=($companyInfo['cd_completion']!=7);
		$sub_header['links'][] = $link;
	}
	if($pageConfig['SubHeader']=="profile"){	
	
		$link['href'] = $udir."useraccount.php";
		$link['text'] = "Change Password";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."application.php";
		$link['text'] = "Update Account Details";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."editCompanyProfileWire.php";
		$link['text'] = "Update Payment Details";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."merchantContract.php?showheader=profile";
		$link['text'] = "Merchant Contract";
		if($companyInfo['activeuser']!=0 && is_array($companyInfo)) $sub_header['links'][] = $link;
	}
	
	if($pageConfig['SubHeader']=="reports"){	
		$link['href'] = $udir."quickstats.php";
		$link['text'] = "Quick Stats";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."SmartProjection.php";
		$link['text'] = "Projected Settlement";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."ProfitSmart.php";
		$link['text'] = "Profit Summary";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."rebillSummary_smart.php";
		$link['text'] = "Rebilling Summary";
		if($companyInfo['cd_enable_price_points']==1) $sub_header['links'][] = $link;
	}
	
	if($pageConfig['SubHeader']=="affiliates"){	
		$link['href'] = $udir."add_affiliate.php";
		$link['text'] = "Create Affiliate";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."manage_affiliate.php";
		$link['text'] = "Manage Affiliate";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."tracking_setup.php";
		$link['text'] = "Set up Sales Tracking";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."tracking_Smart.php";
		$link['text'] = "View Clicks";
		$sub_header['links'][] = $link;
	}
	
	if($pageConfig['SubHeader']=="transactions"){	
		$link['href'] = $udir."addwebsiteuserfb.php";
		$link['text'] = "Websites";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."htpasswd_mgr.php";
		$link['text'] = "Password Manager";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."remail.php";
		$link['text'] = "Resend<br>E-Mails";
		//$sub_header['links'][] = $link;
		$link['href'] = $udir."posttesting.php";
		$link['text'] = "Test Post Notification";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."recurTransProcessing.php";
		$link['text'] = "Pricing Setup";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."priceOptions.php";
		$link['text'] = "Pricing Options";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."integrate_active.php";
		$link['text'] = "Integration Guide";
		$sub_header['links'][] = $link;
	
	}
	if($pageConfig['SubHeader']=="merchant"){	
		$link['href'] = $udir."addMerchant.php";
		$link['text'] = "Add New Merchant";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."selectMerchant.php";
		$link['text'] = "View Pending Application Status";
		$sub_header['links'][] = $link;
	
	}
	
	if($_SESSION["gw_masterMerchant_info"])
	{
		$login_info = explode("|",etelDec($_SESSION["gw_masterMerchant_info"]));
		$link['selected']=true;
		$link['disabled']=false;
		$link['href'] = $etel_domain_path."/reseller/".$login_info[4];
		$link['text'] = "Return to Master Merchant Login";
		$sub_header['links'][] = $link;		
	}
		
	if($_SESSION["gw_admin_info"])
	{
		$login_info = explode("|",etelDec($_SESSION["gw_admin_info"]));
		$link['selected']=true;
		$link['disabled']=false;
		$link['href'] = $etel_domain_path."/admin/".$login_info[4];
		$link['text'] = "Return to Admin Login";
		$sub_header['links'][] = $link;		
	}
		
	if($_SESSION["gw_customerservice_info"])
	{
		$login_info = explode("|",etelDec($_SESSION["gw_customerservice_info"]));
		$link['selected']=true;
		$link['disabled']=false;
		$link['href'] = $etel_domain_path."/service/".$login_info[4];
		$link['text'] = "Return to Customer Service";
		//$sub_header['links'][] = $link;		
	}
	
}
else
if($_SESSION["userType"]=="Reseller")
{ 
	$udir = $etel_domain_path."/reseller/";
	$link['href'] = $udir."startHere.php";
	$link['text'] = "Start Here";
	if($companyInfo['en_info']['Reseller']['Completion']<=3 && is_array($companyInfo)) $main_header['links'][] = $link;
	$link['href'] = $udir."profitBlank.php";
	$link['text'] = "SubGateway Report";	
	if($companyInfo['reseller_id']==$companyInfo['rd_subgateway_id'] && $companyInfo['rd_subgateway_id']>0) $main_header['links'][] = $link;
	$link['href'] = $udir."sgBlank.php";
	$link['text'] = "SubGateway Users";	
	if($companyInfo['reseller_id']==$companyInfo['rd_subgateway_id'] && $companyInfo['rd_subgateway_id']>0) $main_header['links'][] = $link;
	$link['href'] = $udir."requestMarkup.php";
	$link['text'] = "($pendingRequestRates) Merchants Pending";
	if($pendingRequestRates) $main_header['links'][] = $link;
	$link['href'] = $udir."profileBlank.php";
	$link['text'] = "Profile";
	$main_header['links'][] = $link;
	$link['href'] = $udir."MerchantUrl.php?nosub=1";
	$link['text'] = "Promotional Tools";
	if($_SESSION["gw_links"] != 'ecom') $main_header['links'][] = $link;
	$link['href'] = $udir."merchantBlank.php";
	$link['text'] = "Portfolio";
	$main_header['links'][] = $link;
	$link['href'] = $udir."blankLedger.php";
	$link['text'] = "Ledgers";
	$main_header['links'][] = $link;	
	$link['href'] = "/support/knowledgebase.php";
	$link['text'] = "Frequently Asked Questions";
	//if($_SESSION["gw_links"] == 'all')	$main_header['links'][] = $link;
	
	if($companyInfo['en_info']['Reseller']['Completion']==0) $display_todo_list.="Please Complete the Start Here Section.";
	if($companyInfo['en_info']['Reseller']['Completion']==1) $display_todo_list.="Please Fill out your Reseller Application.";
	if($companyInfo['en_info']['Reseller']['Completion']==2) $display_todo_list.="Please Sign your Reseller Contract.";
	if($companyInfo['en_info']['Reseller']['Completion']==3) $display_todo_list.="You are now ready to Promote your site. Browse to the Promotional Tools section to begin your promotions.";
	
	if($pendingRequestRates) $display_todo_list.="You have ($pendingRequestRates) Companys that need Reseller Markup Rates Pending.";
		
	if($pageConfig['SubHeader']=="startHere"){	
	//$companyInfo['en_info']['Reseller']['Completion']
		$link=NULL;
		$link['href'] = $udir."gettingStarted.php";
		$link['text'] = "1. Getting Started";
		$link['selected']=($companyInfo['en_info']['Reseller']['Completion']==0);
		$link['disabled']=($companyInfo['en_info']['Reseller']['Completion']<0);
		$sub_header['links'][] = $link;
		$link['href'] = $udir."resellerApplication.php";
		$link['text'] = "2. Reseller Application";
		$link['selected']=($companyInfo['en_info']['Reseller']['Completion']==1);
		$link['disabled']=($companyInfo['en_info']['Reseller']['Completion']<1);
		$sub_header['links'][] = $link;
		$link['href'] = $udir."resellerContract.php";
		$link['text'] = "3. Sign Reseller Contract";
		$link['selected']=($companyInfo['en_info']['Reseller']['Completion']==2);
		$link['disabled']=($companyInfo['en_info']['Reseller']['Completion']<2);
		$sub_header['links'][] = $link;
		$link['href'] = $udir."MerchantUrl.php";
		$link['text'] = "4. Banners and Promotion";
		$link['selected']=($companyInfo['en_info']['Reseller']['Completion']==3);
		$link['disabled']=($companyInfo['en_info']['Reseller']['Completion']<3);
		$sub_header['links'][] = $link;
	}
	if($pageConfig['SubHeader']=="profile"){	
		$link['href'] = $udir."changePassword.php";
		$link['text'] = "Change Password";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."editProfile.php";
		$link['text'] = "Edit Profile";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."bankProfile.php";
		$link['text'] = "Payment Details";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."MerchantUrl.php";
		$link['text'] = "Promotional Tools";
		if($_SESSION["gw_links"] == 'ecom')$sub_header['links'][] = $link;
	
	}
	if($pageConfig['SubHeader']=="subgatewayusers" && $companyInfo['isMasterMerchant']){	
		$link['href'] = $udir."addMerchant.php";
		$link['text'] = "Register New Merchant";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."addReseller.php";
		$link['text'] = "Register New Reseller";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."viewGatewayMerchants.php";
		$link['text'] = "View/Edit Gateway Merchants";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."viewGatewayResellers.php";
		$link['text'] = "View/Edit Gateway Resellers";
		$link['disabled']=0;
		$sub_header['links'][] = $link;
	}
	if($pageConfig['SubHeader']=="subgatewayreporting" && $companyInfo['isMasterMerchant'] ){	
		$link['href'] = $udir."viewProfitReport.php";
		$link['text'] = "View Profit Report";
		$link['disabled']=0;
		$sub_header['links'][] = $link;
	}
	
	if($pageConfig['SubHeader']=="reports"){	
		$link['href'] = $udir."quickstats.php";
		$link['text'] = "Quick Stats";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."selectMerchantLedger.php";
		$link['text'] = "Ledger";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."projectedsettlement.php";
		$link['text'] = "Projected Settlement";
		$sub_header['links'][] = $link;	
	}
	
	if($pageConfig['SubHeader']=="merchant"){	
		$link['href'] = $udir."addMerchant.php";
		$link['text'] = "Register New Merchant";
		if($_SESSION["gw_links"] != 'demo') $sub_header['links'][] = $link;
		$link['href'] = $udir."Portfolio.php";
		$link['text'] = "View Your Merchants";
		$sub_header['links'][] = $link;
	}
	
	if($_SESSION["gw_admin_info"])
	{
		$login_info = explode("|",etelDec($_SESSION["gw_admin_info"]));
		$link['selected']=true;
		$link['disabled']=false;
		$link['href'] = $etel_domain_path."/admin/".$login_info[4];
		$link['text'] = "Return to Admin Login";
		$sub_header['links'][] = $link;		
	}
}
else 
if($_SESSION["userType"]=="Admin")
{
	$support_Admin_folder = '';
	$udir = $etel_domain_path."/admin/";
	
	
	$link['href'] = $udir."administration_blank.php";
	$link['text'] = "Administration";
	$main_header['links'][] = $link;
	$link['href'] = $udir."risk_smart.php";
	$link['text'] = "Risk Management";
	if($_SESSION["gw_links"] == 'all') $main_header['links'][] = $link;
	$link['href'] = $udir."EntityManager.php";
	$link['text'] = "Companies";
	$main_header['links'][] = $link;
		
	$link['href'] = $udir."customerservice_blank.php";
	$link['text'] = "Customer Service";
	$main_header['links'][] = $link;
	$link['href'] = $udir."report_Smart.php";//"transactions_blank.php";
	$link['text'] = "Transactions";
	$main_header['links'][] = $link;

	if($adminInfo['li_level'] == 'full')	$support_Admin_folder = 'admin/';
	if($adminInfo['li_level'] == 'gateway')	$support_Admin_folder = 'admin/';
	if($adminInfo['li_level'] == 'customerservice')	$support_Admin_folder = 'admin/';
	if($adminInfo['li_level'] == 'full'){
		
		
		$link['href'] = $udir."ledger.php";
		
		$link['text'] = "Ledgers";
		$main_header['links'][] = $link;

		
		$link['href'] = $udir."company_banklist.php";
		
		$link['text'] = "Bank";
		$main_header['links'][] = $link;
		$link['href'] = $udir."mail_blank.php";
		
		$link['text'] = "Mail";
		$main_header['links'][] = $link;		
		
		$link['href'] = $udir."Support.php";
		
		$link['text'] = "Support";
		$main_header['links'][] = $link;

//		$link['href'] = $udir."voicesystem_blank.php";
//		
//		$link['text'] = "TeleSales";
//		if($_SESSION["gw_links"] == 'all') $main_header['links'][] = $link;
	}


		
		if($pageConfig['SubHeader']=="administration")
		{	
		$link['href'] = $udir."useraccount.php";
		$link['text'] = "Change Password";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."userManage.php";
		$link['text'] = "Manage Admin Users";
		if ($adminInfo['li_level'] == 'full') $sub_header['links'][] = $link;
		}
		
		if($pageConfig['SubHeader']=="riskassesment")
		{	
			$link['href'] = $udir."riskAssessment.php";
			$link['text'] = "Risk Management";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."addRiskReport.php";
			$link['text'] = "Create a Report/Alert";
			$sub_header['links'][] = $link;
		}

		if($pageConfig['SubHeader']=="risk_smart")
		{	
			$link['href'] = $udir."risk_smart.php";
			$link['text'] = "Risk Management";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."risk_custom.php";
			$link['text'] = "Create a Report";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."watchList.php";
			$link['text'] = "Manage Watch List";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."banList.php";
			$link['text'] = "Manage Ban List";
			$sub_header['links'][] = $link;
		}
					
		if($pageConfig['SubHeader']=="companies")
		{	
			$link['href'] = $udir."addMerchant.php";
			$link['text'] = "Add New Merchant ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."viewCompany.php";
			$link['text'] = "Merchant List ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."EntityManager.php";
			$link['text'] = "Entity Manager";
			if($adminInfo['li_level'] == 'full') $sub_header['links'][] = $link;
			$link['href'] = $udir."confirmWebsite.php?clear=1";
			$link['text'] = "Confirm Websites";
			//$sub_header['links'][] = $link;
			$link['href'] = $udir."confirmUploads.php?clear=1";
			$link['text'] = "Confirm Uploaded Documents";
			//$sub_header['links'][] = $link;
			$link['href'] = $udir."reseller_blank.php";
			$link['text'] = "Reseller ";
			if ($adminInfo['li_level'] == 'full') $sub_header['links'][] = $link;
			$link['href'] = $udir."setupfee.php";
			$link['text'] = "Setup Fee ";
			if ($adminInfo['li_level'] == 'full') $sub_header['links'][] = $link;
			$link['href'] = $etel_domain_path."/ispy/";
			$link['text'] = "Spider ";
			if ($adminInfo['li_level'] == 'full') $sub_header['links'][] = $link;
		}
				
		
		if($pageConfig['SubHeader']=="customerservice")
		{	
			$link['href'] = $udir."enquires.php";
			$link['text'] = "Unfound Calls ";
			if($adminInfo['li_level'] == 'full')$sub_header['links'][] = $link;
			$link['href'] = $udir."report_custom.php";
			$link['text'] = "Found Calls ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."refundrequests_Smart.php";
			$link['text'] = "Refund Requests ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."service_users.php";
			$link['text'] = "Users ";
			if($adminInfo['li_level'] == 'full')$sub_header['links'][] = $link;
			$link['href'] = $udir."logView.php";
			$link['text'] = "Log Viewer ";
			if($adminInfo['li_level'] == 'full')$sub_header['links'][] = $link;
		}	
		
		
	
	if($adminInfo['li_level'] == 'full'){
				
		if($pageConfig['SubHeader']=="ledgers")
		{	
			$link['href'] = $udir."genStats.php";
			$link['text'] = "General Statistics ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."trackingReport.php";
			$link['text'] = "URL Tracking Report";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."ledger.php";
			$link['text'] = "Ledger ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."projectedsettlement.php";
			$link['text'] = "Projected Settlement ";
			//$sub_header['links'][] = $link;
			$link['href'] = $udir."EntityManager.php";
			$link['text'] = "Entity Manager";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."paymentReport.php";
			$link['text'] = "Merchant Invoice/Payments ";
			//$sub_header['links'][] = $link;
			$link['href'] = $udir."resellerPaymentReport.php";
			$link['text'] = "Reseller Invoice/Payments ";
			//$sub_header['links'][] = $link;
			$link['href'] = $udir."ProfitSmart.php";
			$link['text'] = "Profit Summary";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."rebillSummary.php";
			$link['text'] = "Rebill Summary";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."quickstats.php";
			$link['text'] = "Quick Stats ";
			$sub_header['links'][] = $link;
		}
		
	
		if($pageConfig['SubHeader']=="mail")
		{	
			$link['href'] = $udir."massmail1.php";
			$link['text'] = "Mass Mail";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."massmail2.php";
			$link['text'] = "Blank Mailer ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."email_edit.php";
			$link['text'] = "Customize Emails";
			$sub_header['links'][] = $link;
/*
			$link['href'] = $udir."downloadDocuments.php";
			$link['text'] = "Document / Application";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."labels.php";
			$link['text'] = "Labels";
			if($_SESSION["gw_links"] == 'all') $sub_header['links'][] = $link;
*/
			$link['href'] = $udir."emailManage.php";
			$link['text'] = "Manage Emails";
			$sub_header['links'][] = $link;
		}
		

		
		
		if($pageConfig['SubHeader']=="emailReceipts")
		{	
			$link['href'] = $udir."dnc_emails.php";
			$link['text'] = "DNC Emails ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."ordermail.php";
			$link['text'] = "Email Receipt for Orders ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."bankcompany.php";
			$link['text'] = "Email Receipt for Cancels ";
			$sub_header['links'][] = $link;
		}


		
		
		if($pageConfig['SubHeader']=="transactions")
		{	
			$link['href'] = $udir."report_Smart.php";
			$link['text'] = "Transactions ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."export.php";
			$link['text'] = "Export Transactions ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."virtualterminal.php";
			$link['text'] = "Virtual Terminal ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."batchuploads.php";
			$link['text'] = "Batch Processing ";
			$sub_header['links'][] = $link;
			$link['href'] = $udir."negativedatabase.php";
			$link['text'] = "Negative Database";
			if($_SESSION["gw_links"] == 'all') $sub_header['links'][] = $link;
		}
		
		
		if($pageConfig['SubHeader']=="voicesystem")
		{	
		$link['href'] = $udir."voicesystem.php";
		$link['text'] = "Upload Reports ";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."voicesystemreport.php";
		$link['text'] = "View Reports ";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."shipping.php";
		$link['text'] = "Shipping";
		if($_SESSION["gw_links"] == 'all') $sub_header['links'][] = $link;
		}
		
		if($pageConfig['SubHeader']=="bank1")
		{	
		$link['href'] = $udir."company_banklist.php";
		$link['text'] = "Add Bank Details ";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."modifycompany_bankdetails.php";
		$link['text'] = "Modify Bank Details ";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."viewcompany_banklist.php";
		$link['text'] = "View Bank Details ";
		$sub_header['links'][] = $link;
		}
		
		
		if($pageConfig['SubHeader']=="autoLetters")
		{	
		$link['href'] = $udir."reply_registrationmail.php";
		$link['text'] = "Login Letter ";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."ecommerce_letter.php";
		$link['text'] = "Ecommerce Letter ";
		$sub_header['links'][] = $link;
		}
		
		
		if($pageConfig['SubHeader']=="reseller")
		{	
		$link['href'] = $udir."addReseller.php";
		$link['text'] = "Add Reseller ";
		$sub_header['links'][] = $link;
		$link['href'] = $udir."viewSelectReseller.php";
		$link['text'] = "View / Modify Reseller ";
		$sub_header['links'][] = $link;
		}
	}
	$smarty->assign("allowautorefresh", 1);
	if($_REQUEST['autorefresh']) $_SESSION['autorefresh'] = $_REQUEST['autorefresh'];
	
	if($_SESSION['autorefresh']==1) $smarty->assign("autorefresh", 300);
}
if ($adminInfo['li_singleview_allow']) 
{
	$allowed_pages = explode("|",$adminInfo['li_singleview']);
	unset($sub_header['links']);
	unset($main_header['links']);
	foreach($allowed_pages as $page)
	{
		$reparray = array('.php','_smart','_Smart');
		$pageTitle = str_replace($reparray,"",$page);
		$pageTitle = substr($pageTitle,strrpos($pageTitle,'/'));
		$link['href'] = '/admin/'.$page;
		
		$link['text'] = ucfirst(preg_replace('/[^a-zA-Z0-9]+/',' ',$pageTitle));
		$sub_header['links'][] = $link;
		$main_header['links'][] = $link;
	}

}

	$link['href'] = $etel_domain_path."/support/knowledgebase.php";
	$link['text'] = "Frequently Asked Questions";
	//if($_SESSION["gw_links"] == 'all') $main_header['links'][] = $link;

	$link['href'] = $udir."help.php";
	$link['text'] = "Legend";
	//if($_SESSION["userType"]!="Admin" && $_SESSION["userType"]!="CustomerService") $main_header['links'][] = $link;



	$link['href'] = $etel_domain_path."/logout.php";
	$link['text'] = "Log Out";
	$main_header['links'][] = $link;


if(!$pageConfig['HideToDo'] == true)
	$smarty->assign("display_todo_list", $display_todo_list);
$smarty->assign("etel_postback", $etel_postback);
$smarty->assign("main_header", $main_header);
$smarty->assign("sub_header", $sub_header);
$smarty->assign("etel_debug_mode", $etel_debug_mode);
$smarty->assign("etel_full_name", trim(ucwords($companyInfo['en_firstname']." ".$companyInfo['en_lastname'])));


if(!$printable_version){

	$smarty->assign("hide_header", $_REQUEST['hide_header']);
	etel_smarty_display('cp_header.tpl');
	//$smarty->display();
}	


?>
