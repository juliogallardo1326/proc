<?


if (!defined("NS_PHP_TRACKING")) {
	define("NS_DB_PFX", PFX);
	define("NS_COOKIE_PFX", COOKIE_PFX);
	$_NS_TRACK_VARS['QueryClass']="Query";
}


require_once SELF."/lib/track/general.func.php";
require_once SELF."/lib/track/visitor.func.php";
require_once SELF."/lib/track/page.func.php";
require_once SELF."/lib/track/referer.func.php";
require_once SELF."/lib/track/action.func.php";
require_once SELF."/lib/track/query.func.php";
require_once SELF."/lib/track/campaign.func.php";
require_once SELF."/lib/track/misc.func.php";
require SELF."/lib/track/define.vars.php";



$_NS_TRACK_VARS['Db']=&$Db;

if (isset($_GP['RequestParam'])) {
	$ReqArr=explode("/",$_GP['RequestParam']);
	foreach($ReqArr as $i=>$Val) {
		if (substr($Val, 0, 1)=="s") $_GP['s']=substr($Val, 1);
	}
}

$SplitId=(NS_TRACK_MISC::ValidId($_GP['s']))?$_GP['s']:0;

$RememberPage=false;

$Ref=(NS_TRACK_MISC::ValidVar($_SERVER['HTTP_REFERER']))?$_SERVER['HTTP_REFERER']:false;


if (!$Db->ID) {
	@clearstatcache();
	$f=@fopen(SELF."/store/split_test.nodb", "r");
	if (!$f) NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	$Data=fread($f, filesize(SELF."/store/split_test.nodb"));
	if (!$Data) NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	$DataArr=@unserialize($Data);
	if(!ValidArr($DataArr)) NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	if(!ValidArr($DataArr[$SplitId])) NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	NS_TRACK_MISC::Redir($DataArr[$SplitId][0]);
	exit;
}

$RememberPage=$Db->ReturnValue("SELECT REMEMBER_PAGE FROM ".PFX."_tracker_split_test WHERE ID = $SplitId");
$PrevPageId=NS_TRACK_MISC::CookieStorageGet("split$SplitId"."_page");
$PrevQueryId=NS_TRACK_MISC::CookieStorageGet("split$SplitId"."_query");
if ($PrevPageId&&$RememberPage) $Where=" AND TSP.PAGE_ID='$PrevPageId'";
else $Where="";
if ($PrevPageId&&$PrevQueryId&&$RememberPage) $Where.=" AND TSP.QUERY_ID = '$PrevQueryId'";

function nsSplitQuery($SplitId, $Where) 
{
	$Query = "
		SELECT 
			TSP.*, TS.COMPANY_ID, TS.HOST, 
			SP.PATH, TQ.QUERY_STRING, TS.ID AS SITE_ID, ST.SUB_ID, 
			TSP.FULL_PATH AS PAGE_PATH
		FROM ".PFX."_tracker_split_page TSP
		INNER JOIN ".PFX."_tracker_split_test ST
			ON ST.ID=TSP.SPLIT_ID
		INNER JOIN ".PFX."_tracker_site_page SP
			ON SP.ID = TSP.PAGE_ID
		INNER JOIN ".PFX."_tracker_site TS
			ON TS.ID = SP.SITE_ID
		LEFT JOIN ".PFX."_tracker_query TQ
			ON TQ.ID=TSP.QUERY_ID
		WHERE TSP.SPLIT_ID=$SplitId
			$Where
		ORDER BY RAND()
		LIMIT 1
	";
	return $Query;
}

$Page=$Db->Select(nsSplitQuery($SplitId, $Where));
if (!NS_TRACK_MISC::ValidId($Page->ID)) $Page=$Db->Select(nsSplitQuery($SplitId, ""));
if (!NS_TRACK_MISC::ValidId($Page->ID)) {Redir($Ref);exit();}

if ($Page->PAGE_PATH) $Current=$Page->PAGE_PATH;
else {
	$Current="http://".$Page->HOST.$Page->PATH;
	if ($Page->QUERY_STRING) $Current.="?".$Page->QUERY_STRING;
}
$Redir=$Current;

$StId=$Page->SITE_ID;
$Undef=&$_NS_TRACK_VARS['Undef']; $KeepVisPath=true;
$CompanyId=$Page->COMPANY_ID; $UpdateVisPath=$KeepVisPath; $Item=&$_NS_TRACK_VARS['Item']; $Actions=&$_NS_TRACK_VARS['Actions'];
$Fraud=&$_NS_TRACK_VARS['Fraud'];
$TimeDblPageLoad=&$_NS_TRACK_VARS['TimeDblPageLoad'];
$TimeDblAdvClick=&$_NS_TRACK_VARS['TimeDblAdvClick'];

$Site=NS_TRACK_GENERAL::GetCurrentSite($StId);


if (NS_TRACK_MISC::ValidVar($_COOKIE['ns_skip'])) $Skip=true;
if ($Skip) NS_TRACK_MISC::Redir($Redir);

$Settings=NS_TRACK_MISC::GetSettings($CompanyId, $StId);

$VarCamp=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_CAMPAIGN, "c");
$VarCampSource=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_CAMPAIGN_SOURCE, false);
$VarKw=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_KW, "kw");
$VarKeyword=NS_TRACK_MISC::ValidVar($Settings['All']->VAR_KEYWORD, "k");

if (isset($_GP['RequestParam'])) {
	$ReqArr=explode("/",$_GP['RequestParam']);
	foreach($ReqArr as $i=>$Val) {
		if (substr($Val, 0, strlen($VarCamp))==$VarCamp) $_GP[$VarCamp]=substr($Val,strlen($VarCamp));
		if (substr($Val, 0, strlen($VarKeyword))==$VarKeyword) $_GP[$VarKeyword]=substr($Val, strlen($VarKeyword));
	}
}

$ClickSubId=(NS_TRACK_MISC::ValidId($_GP[$VarCamp]))?$_GP[$VarCamp]:0;
if (!$ClickSubId) $ClickSubId=NS_TRACK_CAMPAIGN::GetCampaignBySrc(NS_TRACK_MISC::ValidVar($GetVars[$VarCampSource]));
$KeyId=(NS_TRACK_MISC::ValidId($_GP[$VarKeyword]))?$_GP[$VarKeyword]:0;
$Keyword=(NS_TRACK_MISC::ValidVar($_GP[$VarKw]))?$_GP[$VarKw]:0;
if ($Keyword) $KeyId=NS_TRACK_REFERER::GetKeywordId($Keyword);

$VisId=NS_TRACK_VISITOR::GetVisitorId();
if ($Skip) NS_TRACK_MISC::Redir($Redir);
$PageId=$Page->PAGE_ID;
$StId=$SiteId=$Page->SITE_ID;
$SplitCamp=$Page->SUB_ID;

$KeepNoRef=NS_TRACK_MISC::SetsByPrior($Settings, "KEEP_NO_REF"); 
$NoDblPageLoad=NS_TRACK_MISC::SetsByPrior($Settings, "STOP_DBL_PAGE_LOAD"); 
$TimeDblPageLoad=NS_TRACK_MISC::TimeDblSettings($Settings, "STOP_DBL_PAGE_LOAD", "TIME_DBL_PAGE_LOAD"); 
$NoDblAdvClick=NS_TRACK_MISC::SetsByPrior($Settings, "STOP_DBL_ADV_CLICK");
$TimeDblAdvClick=NS_TRACK_MISC::TimeDblSettings($Settings, "STOP_DBL_ADV_CLICK", "TIME_DBL_ADV_CLICK"); 


$CurrentPageArr=NS_TRACK_GENERAL::PreparePathAddr($Current);
$RefPageArr=NS_TRACK_GENERAL::PreparePathAddr($Ref);
if (!$CurrentPageArr)  return NS_TRACK_GENERAL::FinishTracking();
$HostsArr=NS_TRACK_GENERAL::GetSiteHosts($StId);
$SiteHostId=NS_TRACK_GENERAL::CurrentSiteHost($HostsArr, $CurrentPageArr);
if (!$SiteHostId) $Skip=true;
if ($Skip) NS_TRACK_MISC::Redir($Redir);

$RefSet=NS_TRACK_REFERER::GetRefererSet($RefPageArr, $Ref);

if(!$KeepNoRef&&$RefSet==0&&!NS_TRACK_MISC::ValidId($_COOKIE[NS_COOKIE_PFX.'log'])&&$ClickSubId!=0) $Skip=true;
if ($Skip) NS_TRACK_MISC::Redir($Redir);


$Actions=NS_TRACK_ACTION::GetActionIds($PageId, $CurrentPageArr);
$QueryId=NS_TRACK_QUERY::GetQueryId(NS_TRACK_MISC::ValidVar($CurrentPageArr['query']));

if ($NoDblPageLoad) NS_TRACK_GENERAL::CheckPathDblClick();
if ($NoDblAdvClick) NS_TRACK_GENERAL::CheckAdvDblClick();
if ($Skip) NS_TRACK_MISC::Redir($Redir);
$Fraud=NS_TRACK_GENERAL::CheckClickFraud($Settings, $VisId, $ClickSubId);

NS_TRACK_GENERAL::UpdateVisitorPath();
$nsUser->SetCookie(NS_COOKIE_PFX.'tmp_skip', '1', time()+120, '/', $_NS_TRACK_VARS['COOKIE_DOMAIN']);
NS_TRACK_GENERAL::UpdateVisitorAction();

if (NS_TRACK_MISC::ValidId($ClickSubId)&&$ClickSubId>0) NS_TRACK_GENERAL::UpdateVisitorClick();

NS_TRACK_GENERAL::UpdateVisitorSplit($SplitCamp);
if ($RememberPage) {
	$Arr["split$SplitId"."_page"]=$Page->PAGE_ID;
	$Arr["split$SplitId"."_query"]=$Page->QUERY_ID;	
	NS_TRACK_MISC::CookieStorageSet($Arr, false, time()+60*60*24*365*10, '/');
}

NS_TRACK_GENERAL::SetCookieLog();
NS_TRACK_MISC::Redir($Redir);
?>