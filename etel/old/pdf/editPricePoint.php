<?

$pageConfig['Title'] = 'PricePoint Information';
$headerInclude = "transactions";
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
require_once('includes/function.php');
require_once('includes/header.php');
require_once("includes/transaction.class.php");
require_once("includes/updateAccess.php");

$userId =$companyInfo['userId'];
$en_ID =$companyInfo['en_ID'];

$rd_subName=quote_smart($_REQUEST['rd_subName']);

$access = array();

$day_options = array();
//$day_options['']=array('txt'=>' - Select - ');
$day_options['0.00']=array('txt'=>'Select By Month','group'=>true);
for($i=1;$i<=6;$i++)
	$day_options[($i*30)]=array('txt'=>"$i Month".($i>1?'s':''));
$day_options[365]=array('txt'=>"1 Year");
$day_options['0.000']=array('txt'=>'Select By Days','group'=>true);
for($i=3;$i<=90;$i++)
	$day_options[$i.'.0'] = array('txt'=>"$i Days");
	
$new = false;
	
if(!$rd_subName)
{
	$new = true;
	$access['QueryType']='Insert';
	$access['InsertInto']='cs_rebillingdetails';
	$access['Data']['access_header1']=getAttrib('access_header');
	$access['Data']['access_header1']['Value'] = 'PricePoint Information';
	$access['Data']['rd_description']=getAttrib('rd_description');
	
	$access['Data']['access_header2']=getAttrib('access_header');
	$access['Data']['access_header2']['Value'] = 'Enable Trial';
	$access['Data']['rd_trial_enabled']=getAttrib('rd_trial_enabled');
	$access['Data']['rd_initial_amount']=getAttrib('rd_initial_amount');
	$access['Data']['rd_trial_days']=getAttrib('rd_trial_days');
	
	$access['Data']['access_header3']=getAttrib('access_header');
	$access['Data']['access_header3']['Value'] = 'Recurring Billing Information';
	$access['Data']['rd_recur_enabled']=getAttrib('rd_recur_enabled');
	$access['Data']['recur_charge']=getAttrib('recur_charge');
	$access['Data']['recur_day']=getAttrib('recur_day');
	$access['Submitable'] = 1;
	

	
}	
else
{
	$access = getAccessInfo("
	
	'PricePoint Information' as access_header,
		`rd_subName` as 'Price_Point_Name',  
		`rd_description`,
		`rd_enabled`,
	
	'Enable Trial' as access_header,
		`rd_trial_enabled`,
		`rd_initial_amount`,  `rd_trial_days`,
	
	'Recurring Billing Information' as access_header,
		`rd_recur_enabled`, `recur_charge`, `recur_day`,
		
	'Usage Information' as access_header,
		sum(ss_ID is not null) as 'sub_num',
		sum(ss_rebill_status = 'active') as 'sub_active',
		sum(ss_rebill_status = 'inactive') as 'sub_inactive'
	
		
	",
	
	"cs_rebillingdetails left join cs_subscription on rd_subaccount = ss_rebill_id",
	"rd_subName = '$rd_subName' && company_user_id = '$userId' && rd_hide=0",array('req'=>1),$access
	);
	
	
	if($access==-1) dieLog("Invalid PricePoint: ".print_r($_REQUEST,true),"Invalid PricePoint",false);

	$access['Data']['Price_Point_Name']['DisplayName'] = 'Price Point Name';
	$access['Data']['Price_Point_Name']['disable'] = 1;
}

$access['Data']['rd_enabled']['disable'] = 1;
$access['Data']['rd_enabled']['DisplayName'] = "Price Point Enabled";

$access['Data']['rd_recur_enabled']['DisplayName'] = 'Enable Recurring Billing';
$access['Data']['rd_recur_enabled']['Input'] = 'checkbox';
$access['Data']['rd_recur_enabled']['AddHtml']="<span class='small'><br>This will disable all active subscriptions using this price point.</span>";

$access['Data']['recur_charge']['DisplayName'] = 'Recuring $ Amount';
$access['Data']['recur_charge']['Valid'] = 'between|1.00|'.$companyInfo['cd_max_transaction'];
$access['Data']['recur_day']['DisplayName'] = 'Recuring Period';
$access['Data']['rd_recur_enabled']['InputAdditional']="onclick='".
'$(recur_charge).disabled=!this.checked; $(recur_day).disabled=!this.checked; '.
"'";
$access['Data']['recur_day']['Input']="selectcustomarray";	
$access['Data']['recur_day']['Input_Custom'] = $day_options;
$access['Data']['rd_recur_enabled']['AddHtml']="<span class='small'><br>Enter the Recurring Billing Schedule by which the customer will be billed. The customer will be charged the Recurring Amount by this schedule.</span>";
$access['Data']['recur_charge']['AddHtml']="<span class='small'><br>All Amounts must be greater than $1.00 and less than $".$companyInfo['cd_max_transaction'].".</span>";
   	
$day_options['0.0'] = array('txt'=>"No Subscription");

$access['Data']['rd_initial_amount']['DisplayName'] = 'Initial $ Amount';
$access['Data']['rd_initial_amount']['Valid'] = 'between|1.00|'.$companyInfo['cd_max_transaction'];
$access['Data']['rd_trial_days']['DisplayName'] = 'Trial Period';
$access['Data']['rd_trial_enabled']['DisplayName'] = 'Enable Trial Period';
$access['Data']['rd_trial_enabled']['Input'] = 'checkbox';
$access['Data']['rd_trial_enabled']['InputAdditional']="onclick='".
'$(rd_trial_days).disabled=!this.checked; $(rd_initial_amount).disabled=!this.checked; '."'";
$access['Data']['rd_trial_enabled']['AddHtml']="<span class='small'><br>Enter the Trial Amount to be billed and the Trial Period duration for which the customer will have access to the site or product until they are rebilled again.</span>";
$access['Data']['rd_trial_days']['Input']="selectcustomarray";	
$access['Data']['rd_trial_days']['Input_Custom'] =  $day_options;
$access['Data']['rd_initial_amount']['AddHtml']="<span class='small'><br>All Amounts must be greater than $1.00 and less than $".$companyInfo['cd_max_transaction'].".</span>";


$access['Data']['rd_description']['DisplayName'] = 'Describe This Price Point';
$access['Data']['rd_description']['Input'] = 'textarea';
$access['Data']['rd_description']['Size'] = 30;


$access['Data']['sub_num']['DisplayName'] = 'Subscriptions Used';
$access['Data']['sub_active']['DisplayName'] = 'Subscriptions Active';
$access['Data']['sub_inactive']['DisplayName'] = 'Subscriptions Inactive';

$access['Data']['sub_num']['disable'] = 1;
$access['Data']['sub_active']['disable'] = 1;
$access['Data']['sub_inactive']['disable'] = 1;
	
if($access['Data']['sub_active']['Value'])
{
	unset($access['Data']['rd_trial_enabled']);
	$access['Data']['rd_initial_amount']['disable'] = 1;
	$access['Data']['rd_trial_days']['disable'] = 1;

	unset($access['Data']['rd_recur_enabled']);
	$access['Data']['recur_day']['disable'] = 1;
	$access['Data']['recur_charge']['disable'] = 1;
}
if(!$access['Data']['sub_active']['Value']) $access['Data']['sub_active']['Value'] = 'No Activity';
if(!$access['Data']['sub_inactive']['Value']) $access['Data']['sub_inactive']['Value'] = 'No Activity';
if(!$access['Data']['sub_num']['Value'])
{
	$access['Data']['sub_num']['Value'] = 'No Activity';
	unset($access['Data']['sub_inactive']);
	unset($access['Data']['sub_active']);
}
$_POST['rd_trial_enabled'] = ($_POST['rd_initial_amount']>=1);
if(!$_POST['rd_trial_enabled']) $_POST['rd_initial_amount'] = $_POST['rd_trial_days'] = 0;
 
$_POST['rd_recur_enabled'] = ($_POST['recur_charge']>=1 && $_POST['recur_day']>=2);
if(!$_POST['rd_recur_enabled']) $_POST['recur_day'] = $_POST['recur_charge'] = 0;

if($_POST['rd_recur_enabled'] && $_POST['rd_trial_days']<2)
{
	$_POST['rd_trial_enabled'] = false;
	$_POST['rd_initial_amount'] = 0;
}

if($_POST['rd_initial_amount']>$companyInfo['cd_max_transaction']) $_POST['rd_initial_amount'] = $companyInfo['cd_max_transaction'];
if($_POST['recur_charge']>$companyInfo['cd_max_transaction']) $_POST['recur_charge'] = $companyInfo['cd_max_transaction'];

if(!$_POST['rd_trial_enabled'] && !$_POST['rd_recur_enabled']) $access['Sql_Sets'][]= "rd_enabled = 'No'";
else $access['Sql_Sets'][]= "rd_enabled = 'Yes'";

if($_POST['submit_access'] == 'Submit')
{
	if($new)
	{	
		$sql = "SELECT MAX(RIGHT(rd_subName,5)) as max FROM `cs_rebillingdetails` WHERE `company_user_id` = '$userId'";
		$result = sql_query_read($sql) or dieLog(mysql_error());
	
		$newInc = mysql_result($result,0,0);
		if($newInc <= 100) $newInc = 100;
		$newInc++;
		$rd_subName=$userId."-".sprintf('%05d', $newInc);
		$access['Sql_Sets'][] = "company_user_id = '$userId'";
		$access['Sql_Sets'][] = "rd_en_ID = '$en_ID'";
		$access['Sql_Sets'][] = "rd_subName = '$rd_subName' ";
	}
	$msg='';
	$result = processAccessForm(&$access);
	if($result) $msg .= "PricePoint ".$result['msg'];
	else 
	{
		$msg .= "No Updates Detected";
		if($new) $rd_subName = '';
	}
}
$access['Columns'] = 1;
$access['HeaderMessage']=$msg;


if(!$access['Data']['rd_trial_enabled']['Value'])
{
	$access['Data']['rd_initial_amount']['InputAdditional'] = 'disabled';
	$access['Data']['rd_trial_days']['InputAdditional'] = 'disabled';
}

if(!$access['Data']['rd_recur_enabled']['Value'])
{
	$access['Data']['recur_charge']['InputAdditional'] = 'disabled';
	$access['Data']['recur_day']['InputAdditional'] = 'disabled';
}

beginTable();
writeAccessForm(&$access);
endTable("PricePoint Info - ".(!$new?$access['Data']['rd_subName']['Value']:"New Price Point"),'?rd_subName='.$rd_subName);


include("includes/footer.php");
?>
