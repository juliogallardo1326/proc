<?

if (!defined("NS_PHP_TRACKING")) {
	$nsSelf=SELF;
	$nsSys=SYS;
	define("NS_DB_PFX", PFX);
	define("NS_COOKIE_PFX", COOKIE_PFX);
	$_NS_TRACK_VARS['QueryClass']="Query";
}


require_once $nsSelf."/lib/track/general.func.php";
require_once $nsSelf."/lib/track/visitor.func.php";
require_once $nsSelf."/lib/track/page.func.php";
require_once $nsSelf."/lib/track/referer.func.php";
require_once $nsSelf."/lib/track/action.func.php";
require_once $nsSelf."/lib/track/query.func.php";
require_once $nsSelf."/lib/track/campaign.func.php";
require_once $nsSelf."/lib/track/misc.func.php";
require $nsSelf."/lib/track/define.vars.php";


$_NS_TRACK_VARS['Db']=&$Db;


$GetVars=&$_NS_TRACK_VARS['GetVars'];
$TimeDblPageLoad=&$_NS_TRACK_VARS['TimeDblPageLoad'];
$TimeDblAdvClick=&$_NS_TRACK_VARS['TimeDblAdvClick'];
$TimeDblSale=&$_NS_TRACK_VARS['TimeDblSale'];
$TimeDblEvent=&$_NS_TRACK_VARS['TimeDblEvent'];


$Ref= (NS_TRACK_MISC::ValidVar($_GP['ref']))?$_GP['ref']:false;
$ScrRes= NS_TRACK_MISC::ValidVar($_GP['wr']);
$FlVer= NS_TRACK_MISC::ValidVar($_GP['fl']);
$PxD= NS_TRACK_MISC::ValidVar($_GP['px']);
$Frame= NS_TRACK_MISC::ValidVar($_GP['frame']);

$StId=(NS_TRACK_MISC::ValidId($_GP['st']))?$_GP['st']:false; $Cur=(NS_TRACK_MISC::ValidVar($_GP['cur']))?$_GP['cur']:false; $DTitle=(NS_TRACK_MISC::ValidVar($_GP['dtitle']))?$_GP['dtitle']:false; $CheckCookieId=NS_TRACK_MISC::ValidVar($_GP['CheckCookieId']); 
if (NS_TRACK_MISC::ValidVar($_COOKIE['ns_skip'])) $Skip=true;

if (!$StId||$Skip) return NS_TRACK_GENERAL::FinishTracking();


$Site=NS_TRACK_GENERAL::GetCurrentSite($StId);

if (!NS_TRACK_MISC::ValidVar($Site)) $Skip=true;
if ($Skip) return NS_TRACK_GENERAL::FinishTracking();
$CompanyId=$Site->COMPANY_ID; $Settings=NS_TRACK_MISC::GetSettings($CompanyId, $StId);



$Item=&$_NS_TRACK_VARS['Item']; $Undef=&$_NS_TRACK_VARS['Undef']; $KeepVisPath=NS_TRACK_MISC::SetsByPrior($Settings, "KEEP_VISITOR_PATH"); 
$KeepNoRef=NS_TRACK_MISC::SetsByPrior($Settings, "KEEP_NO_REF"); 
$NoDblPageLoad=NS_TRACK_MISC::SetsByPrior($Settings, "STOP_DBL_PAGE_LOAD"); $TimeDblPageLoad=NS_TRACK_MISC::TimeDblSettings($Settings, "STOP_DBL_PAGE_LOAD", "TIME_DBL_PAGE_LOAD"); 
$NoDblAdvClick=NS_TRACK_MISC::SetsByPrior($Settings, "STOP_DBL_ADV_CLICK"); $TimeDblAdvClick=NS_TRACK_MISC::TimeDblSettings($Settings, "STOP_DBL_ADV_CLICK", "TIME_DBL_ADV_CLICK"); 
$Actions=&$_NS_TRACK_VARS['Actions']; $UpdateVisPath=&$_NS_TRACK_VARS['UpdateVisPath']; $Fraud=&$_NS_TRACK_VARS['Fraud'];

$VarCamp=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_CAMPAIGN, "c");
$VarCampSource=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_CAMPAIGN_SOURCE, false);
$VarKw=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_KW, "kw");
$VarKeyword=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_KEYWORD, "k");


$Current=false;
if (isset($_SERVER['HTTP_REFERER'])) $Current=$_SERVER['HTTP_REFERER'];
if ($Cur) $Current=$Cur;
if (!$Current) return NS_TRACK_GENERAL::FinishTracking();













$VisId=NS_TRACK_VISITOR::GetVisitorId();


if ($Skip)  return NS_TRACK_GENERAL::FinishTracking();

$CurrentPageArr=NS_TRACK_GENERAL::PreparePathAddr($Current);
$RefPageArr=NS_TRACK_GENERAL::PreparePathAddr($Ref);
if (!$CurrentPageArr)  return NS_TRACK_GENERAL::FinishTracking();




$HostsArr=NS_TRACK_GENERAL::GetSiteHosts($StId, $Site);
$SiteHostId=NS_TRACK_GENERAL::CurrentSiteHost($HostsArr, $CurrentPageArr, $Site);
if (!$SiteHostId) $Skip=true;


if ($Skip) return NS_TRACK_GENERAL::FinishTracking();

$PageId=NS_TRACK_PAGE::GetPageId($CurrentPageArr, $StId);
if ($Undef&&$KeepVisPath) {
	NS_TRACK_GENERAL::UpdateStatUndef();
	return NS_TRACK_GENERAL::FinishTracking();
}
if ($Skip) return NS_TRACK_GENERAL::FinishTracking();


$GetVars=NS_TRACK_QUERY::ParseTemplate(NS_TRACK_MISC::ValidVar($CurrentPageArr['query']));
$Skip=(NS_TRACK_MISC::ValidId($GetVars['ns_skip']))?true:false; if ($Skip) return NS_TRACK_GENERAL::FinishTracking();

$ClickSubId=(NS_TRACK_MISC::ValidId($GetVars[$VarCamp]))?$GetVars[$VarCamp]:0;
if (!$ClickSubId) $ClickSubId=NS_TRACK_CAMPAIGN::GetCampaignBySrc(NS_TRACK_MISC::ValidVar($GetVars[$VarCampSource]));
$KeyId=(NS_TRACK_MISC::ValidId($GetVars[$VarKeyword]))?$GetVars[$VarKeyword]:0;
$Keyword=(NS_TRACK_MISC::ValidVar($GetVars[$VarKw]))?$GetVars[$VarKw]:false;
if ($Keyword) $KeyId=NS_TRACK_REFERER::GetKeywordId($Keyword);


$RefSet=NS_TRACK_REFERER::GetRefererSet($RefPageArr, $Ref);
$QueryId=NS_TRACK_QUERY::GetQueryId($CurrentPageArr['query']);
$Actions=NS_TRACK_ACTION::GetActionIds($PageId, $CurrentPageArr);



if (!$KeepNoRef&&$RefSet==0&&!NS_TRACK_MISC::ValidId($_COOKIE['ns_log'])&&$ClickSubId!=0) $Skip=true;
if ($Skip) return NS_TRACK_GENERAL::FinishTracking();
if ($NoDblPageLoad) NS_TRACK_GENERAL::CheckPathDblClick();
if ($NoDblAdvClick) NS_TRACK_GENERAL::CheckAdvDblClick();
if ($Skip) return NS_TRACK_GENERAL::FinishTracking();
$Fraud=NS_TRACK_GENERAL::CheckClickFraud($Settings, $VisId, $ClickSubId);

NS_TRACK_GENERAL::UpdateVisitorAction();
if (NS_TRACK_MISC::ValidId($ClickSubId)&&$ClickSubId>0) NS_TRACK_GENERAL::UpdateVisitorClick();
if ($UpdateVisPath||$KeepVisPath) NS_TRACK_GENERAL::UpdateVisitorPath();



if ($CookieLogSet && NS_TRACK_GENERAL::CheckTrackingMode()) $CookieLogSet=false;
if ($CookieLogSet) NS_TRACK_GENERAL::SetCookieLog();
if ($FindLastNode) NS_TRACK_VISITOR::FindLastNode($VisId);

NS_TRACK_VISITOR::UpdateByRegs();


if (defined("TRACK_ERRORS")&&TRACK_ERRORS &&  !defined("NS_PHP_TRACKING")) {
	$ResTime=NS_TRACK_MISC::GetMicrotime()-$StartTime;
	$Query = "INSERT INTO track_time (TRACK_TIME) VALUES ('$ResTime')";
	$Db->Query($Query);
	for ($i=0;$i<count($Logs->Errors);$i++) {
		$Db->Query("INSERT INTO track_error (ERROR) VALUES ('".addslashes($Logs->Errors[$i])."')");
	}
}



return NS_TRACK_GENERAL::FinishTracking();
?>