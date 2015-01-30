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

$RUrl= NS_TRACK_MISC::ValidVar($_GP['rurl']);


$ClickSubId= NS_TRACK_MISC::ValidVar($_GP['cid']);
$StId= NS_TRACK_MISC::ValidVar($_GP['st']);
if (NS_TRACK_MISC::ValidVar($_COOKIE['ns_skip'])) $Skip=true;
if (!$RUrl) Redir($_SERVER['HTTP_REFERER']);
if (!NS_TRACK_MISC::ValidId($ClickSubId)) $Skip=true;

if ($Skip) NS_TRACK_MISC::Redir($RUrl);

$Campaign=NS_TRACK_CAMPAIGN::GetCampaignById($ClickSubId);
if (!$Campaign) Redir($RUrl);

$Current=$RUrl;
$Ref=NS_TRACK_MISC::ValidVar($_SERVER['HTTP_REFERER']);
$CurrentPageArr=NS_TRACK_GENERAL::PreparePathAddr($Current);
$RefPageArr=NS_TRACK_GENERAL::PreparePathAddr($Ref);
if (!$CurrentPageArr) NS_TRACK_MISC::Redir($RUrl);
$Site=NS_TRACK_GENERAL::GetCurrentSite($StId);
if (!$Site) Redir($RUrl);
$CompanyId=$Site->COMPANY_ID; $Settings=NS_TRACK_MISC::GetSettings($CompanyId, $StId);



$Item=&$_NS_TRACK_VARS['Item']; $Undef=&$_NS_TRACK_VARS['Undef']; $KeepVisPath=NS_TRACK_MISC::SetsByPrior($Settings, "KEEP_VISITOR_PATH"); 
$KeepNoRef=NS_TRACK_MISC::SetsByPrior($Settings, "KEEP_NO_REF"); 
$NoDblPageLoad=NS_TRACK_MISC::SetsByPrior($Settings, "STOP_DBL_PAGE_LOAD"); $TimeDblPageLoad=NS_TRACK_MISC::TimeDblSettings($Settings, "STOP_DBL_PAGE_LOAD", "TIME_DBL_PAGE_LOAD"); 
$NoDblAdvClick=NS_TRACK_MISC::SetsByPrior($Settings, "STOP_DBL_ADV_CLICK"); $TimeDblAdvClick=NS_TRACK_MISC::TimeDblSettings($Settings, "STOP_DBL_ADV_CLICK", "TIME_DBL_ADV_CLICK"); 
$Actions=&$_NS_TRACK_VARS['Actions']; $UpdateVisPath=false; $Fraud=&$_NS_TRACK_VARS['Fraud'];
$TimeDblPageLoad=&$_NS_TRACK_VARS['TimeDblPageLoad'];
$TimeDblAdvClick=&$_NS_TRACK_VARS['TimeDblAdvClick'];

$VarKw=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_KW, "kw");
$VarKeyword=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_KEYWORD, "k");


$VisId=NS_TRACK_VISITOR::GetVisitorId();
if ($Skip)  NS_TRACK_MISC::Redir($RUrl);

$HostsArr=NS_TRACK_GENERAL::GetSiteHosts($StId, $Site);
$SiteHostId=NS_TRACK_GENERAL::CurrentSiteHost($HostsArr, $CurrentPageArr, $Site);
if (!$SiteHostId || $Skip) Redir($RUrl);

$PageId=NS_TRACK_PAGE::GetPageId($CurrentPageArr, $StId);
if ($Undef&&$KeepVisPath) {
	NS_TRACK_GENERAL::UpdateStatUndef();
	NS_TRACK_MISC::Redir($RUrl);
}
if ($Skip) Redir($RUrl);


$GetVars=NS_TRACK_QUERY::ParseTemplate(NS_TRACK_MISC::ValidVar($CurrentPageArr['query']));
$Skip=(NS_TRACK_MISC::ValidId($GetVars['ns_skip']))?true:false; if ($Skip) NS_TRACK_MISC::Redir($RUrl);

$KeyId=(NS_TRACK_MISC::ValidId($_GP[$VarKeyword]))?$_GP[$VarKeyword]:0;
$Keyword=NS_TRACK_MISC::ValidVar($_GP[$VarKw], false);
if ($Keyword) $KeyId=NS_TRACK_REFERER::GetKeywordId($Keyword);


$RefSet=NS_TRACK_REFERER::GetRefererSet($RefPageArr, $Ref);
$QueryId=NS_TRACK_QUERY::GetQueryId($CurrentPageArr['query']);


if (!$KeepNoRef&&$RefSet==0&&!NS_TRACK_MISC::ValidId($_COOKIE['ns_log'])&&$ClickSubId!=0) $Skip=true;
if ($Skip) NS_TRACK_MISC::Redir($RUrl);
if ($NoDblPageLoad) NS_TRACK_GENERAL::CheckPathDblClick();
if ($NoDblAdvClick) NS_TRACK_GENERAL::CheckAdvDblClick();
if ($Skip) NS_TRACK_MISC::Redir($RUrl);
$Fraud=NS_TRACK_GENERAL::CheckClickFraud($Settings, $VisId, $ClickSubId);


if (NS_TRACK_MISC::ValidId($ClickSubId)&&$ClickSubId>0) NS_TRACK_GENERAL::UpdateVisitorClick();
if ($UpdateVisPath||$KeepVisPath) NS_TRACK_GENERAL::UpdateVisitorPath();
$nsUser->SetCookie(NS_COOKIE_PFX.'tmp_skip', '1', 120, '/');

if ($CookieLogSet && NS_TRACK_GENERAL::CheckTrackingMode()) $CookieLogSet=false;
if ($CookieLogSet) NS_TRACK_GENERAL::SetCookieLog();
if ($FindLastNode) NS_TRACK_VISITOR::FindLastNode($VisId);

if (defined("TRACK_ERRORS")&&TRACK_ERRORS &&  !defined("NS_PHP_TRACKING")) {
	$ResTime=NS_TRACK_MISC::GetMicrotime()-$StartTime;
	$Query = "INSERT INTO track_time (TRACK_TIME) VALUES ('$ResTime')";
	$Db->Query($Query);
	for ($i=0;$i<count($Logs->Errors);$i++) {
		$Db->Query("INSERT INTO track_error (ERROR) VALUES ('".addslashes($Logs->Errors[$i])."')");
	}
}

//if ($CurrentPageArr['query']) $RUrl.="&ns_skip=1";
//else $RUrl.="?ns_skip=1";



NS_TRACK_MISC::Redir($RUrl);
?>