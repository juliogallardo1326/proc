<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
//require_once SELF."/lib/report.func.php";
//require_once SELF."/class/report.class.php";
require_once SYS."/system/lib/validate.func.php";
require_once SYS."/system/lib/sql.func.php";
require_once SELF."/class/report_parent.class.php";
require_once SELF."/class/split_v2.class.php";
$nsLang->TplInc("inc/report_headers");
$nsLang->TplInc("inc/menu");


/////////////////////////////////////////////
///////// prepare any variables
$SplitId=(ValidId($_GP['SplitId'])) ?$_GP['SplitId']:false;
$ViewDate= (ValidDate($_GP['ViewDate'])) ?($_GP['ViewDate']):false;
$StartDate= (ValidDate($_GP['StartDate'])) ?($_GP['StartDate']):false;
$EndDate= (ValidDate($_GP['EndDate'])) ?($_GP['EndDate']):false;
UserColumns(); if (!ValidId($SplitId)) $nsProduct->Redir("default", "", "admin");
$SplitTest=$Db->Select("SELECT * FROM ".PFX."_tracker_camp_piece WHERE ID=$SplitId");
if (!ValidId($SplitTest->ID)) $nsProduct->Redir("default", "", "admin");

$PageTitle=$SplitTest->NAME;
$MenuSection="split_test";

$ProgPath[0]['Name']=$Lang['MSplits'];
$ProgPath[0]['Url']=getURL("incampaign", "CampId=".$SplitTest->CAMPAIGN_ID, "admin");
$ProgPath[1]['Name']=$SplitTest->NAME;
$ProgPath[1]['Url']=getURL("split_test", "EditId=$SplitId", "admin");
$ProgPath[2]['Name']=$Lang['SplitStat'];
$ProgPath[2]['Url']=getURL("split_test", "SplitId=$SplitId", "report");


$SubMenu[0]['Name']=$Lang['BackToList'];
$SubMenu[0]['Link']=getURL("incampaign", "CampId=".$SplitTest->CAMPAIGN_ID, "admin");
$SubMenu[1]['Name']=$Lang['SplitEdit'];
$SubMenu[1]['Link']=getURL("split_test", "EditId=$SplitId", "admin");

//$SubMenu[2]['Name']=$Lang['Dates'];
//$SubMenu[2]['Link']="javascript:;";
//$SubMenu[2]['Onclick']="ShowHide('DatePeriod');";

/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here




$Report=new SplitStat_v2();
$Report->SplitId=$SplitId;
$Report->CpId=$SplitTest->COMPANY_ID;

if ($nsUser->Columns->CLICKS) $Report->ShowVisitors=true;
if ($nsUser->Columns->ACTIONS) $Report->ShowActions=true;
if ($nsUser->Columns->SALES) $Report->ShowSales=true;
if ($nsUser->Columns->CONVERSIONS) $Report->ShowActionConv=true;
if ($nsUser->Columns->CONVERSIONS) $Report->ShowSaleConv=true;

$Report->ShowPages=true;
if ($StartDate) $Report->StartDate=$StartDate;
if ($EndDate) $Report->EndDate=$EndDate;
if ($ViewDate) $Report->ViewDate=$ViewDate;
$Report->Calculate();
$SplitStat=&$Report->SplitStat;


include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


/////////////////////////////////////////////
///////// library section


?>