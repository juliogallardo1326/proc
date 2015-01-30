<?

if (!defined("NS_PHP_TRACKING")) {
	$nsSelf=SELF;
	$nsSys=SYS;
	define("NS_DB_PFX", PFX);
	define("NS_COOKIE_PFX", COOKIE_PFX);
	$_NS_TRACK_VARS['QueryClass']="Query";

	if (isset($_GP['RequestParam'])) {
		$ReqArr=explode("/",$_GP['RequestParam']);
		foreach($ReqArr as $i=>$Val) {
			if (substr($Val, 0, 1)=="e") $_GP['eid']=substr($Val, 1);
		}
	}
}

require_once $nsSelf."/lib/track/general.func.php";
require_once $nsSelf."/lib/track/visitor.func.php";
require_once $nsSelf."/lib/track/page.func.php";
require_once $nsSelf."/lib/track/referer.func.php";
require_once $nsSelf."/lib/track/action.func.php";
require_once $nsSelf."/lib/track/query.func.php";
require_once $nsSelf."/lib/track/misc.func.php";
require $nsSelf."/lib/track/define.vars.php";

$_NS_TRACK_VARS['Db']=&$Db;

$HostsArr=&$_NS_TRACK_VARS['HostsArr'];
$TimeDblEvent=&$_NS_TRACK_VARS['TimeDblEvent'];
$Actions=&$_NS_TRACK_VARS['Actions']; $eid=NS_TRACK_MISC::ValidVar($_GP['eid']);
$rurl=NS_TRACK_MISC::ValidVar($_GP['rurl']);
$Current=NS_TRACK_MISC::ValidVar($_GP['cur']);
$CodeAction=NS_TRACK_MISC::ValidVar($_GP['code']);
$ScrRes= NS_TRACK_MISC::ValidVar($_GP['wr']);
$FlVer= NS_TRACK_MISC::ValidVar($_GP['fl']);
$PxD= NS_TRACK_MISC::ValidVar($_GP['px']);
$Frame= NS_TRACK_MISC::ValidVar($_GP['frame']);

if (NS_TRACK_MISC::ValidVar($_GP['ref'])) $Ref=$_GP['ref'];
else $Ref=NS_TRACK_MISC::ValidVar($_SERVER['HTTP_REFERER']);
$Skip=false;


if (!NS_TRACK_MISC::ValidId($eid)) {
	if (!$CodeAction) Redir($_SERVER['HTTP_REFERER']);
	else return NS_TRACK_GENERAL::FinishTracking();
	exit;
}



$Qr=$_SERVER['QUERY_STRING'];
$CurGet=NS_TRACK_QUERY::ParseTemplate($Qr);
unset($CurGet['eid']);
unset($CurGet['sc']);
unset($CurGet['action']);
unset($CurGet['rurl']);
unset($CurGet['itm']);
unset($CurGet['RequestPath']);
unset($CurGet['RequestParam']);
unset($CurGet['ref']);
unset($CurGet['CheckCookieId']);
$Item=(NS_TRACK_MISC::ValidVar($_GP['itm']))?urldecode(trim($_GP['itm'])):false;
$Undef=&$_NS_TRACK_VARS['Undef'];



if (!$Db->ID) {
	if ($CodeAction) return NS_TRACK_GENERAL::FinishTracking();
	if ($rurl) NS_TRACK_MISC::Redir(urldecode($rurl));
	@clearstatcache();
	$f=@fopen(SELF."/store/redir_action.nodb", "r");
	if (!$f) NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	$Data=fread($f, filesize(SELF."/store/redir_action.nodb"));
	if (!$Data) NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	$DataArr=@unserialize($Data);
	if(!NS_TRACK_MISC::ValidArr($DataArr)) NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	$Action=false;
	for($i=0;$i<count($DataArr);$i++) {
		if ($DataArr[$i]['ID']==$eid) {
			$Action=$DataArr[$i];
			break;
		}
	}
	if (!$Action) NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	if (!NS_TRACK_MISC::ValidVar($Action['PATH'])&&!NS_TRACK_MISC::ValidVar($Action['REDIRECT_URL'])) Redir($_SERVER['HTTP_REFERER']);
	if (NS_TRACK_MISC::ValidVar($Action['REDIRECT_URL'])) Redir(urldecode($Action['REDIRECT_URL']));
	if (NS_TRACK_MISC::ValidVar($Action['PATH'])) Redir(urldecode($Action['PATH']));
	exit;
}


$Settings=NS_TRACK_MISC::GetSettings($CompanyId, $StId);
$VisId=NS_TRACK_VISITOR::GetVisitorId();

$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_visitor_action WHERE ID = $eid";
$Event=$Db->Select($Query);
if ($Event->REDIRECT_URL) $rurl=$Event->REDIRECT_URL;
$StId=$Event->SITE_ID;
$Site=NS_TRACK_GENERAL::GetCurrentSite($Event->SITE_ID);
$CompanyId=$Site->COMPANY_ID; if ($Event->ACTIVE!=1) $Skip=true;
if (NS_TRACK_MISC::ValidVar($_COOKIE['ns_skip'])) $Skip=true;



$rurl=urldecode($rurl);
if ($Skip&&isset($rurl)&&!$CodeAction) NS_TRACK_MISC::Redir($rurl);
if ($Skip&&$CodeAction) return NS_TRACK_GENERAL::FinishTracking();


$KeepNoRef=NS_TRACK_MISC::SetsByPrior($Settings, "KEEP_NO_REF"); 
$NoDblEvent=NS_TRACK_MISC::SetsByPrior($Settings, "STOP_DBL_EVENT"); 
$TimeDblEvent=NS_TRACK_MISC::TimeDblSettings($Settings, "STOP_DBL_EVENT", "TIME_DBL_EVENT"); 
$UpdateVisPath=true;

if($NoDblEvent) NS_TRACK_GENERAL::CheckActionDblClick($eid);

if ($Skip&&isset($rurl)&&!$CodeAction) NS_TRACK_MISC::Redir($rurl);
if ($Skip&&isset($rurl)&&$CodeAction) return NS_TRACK_GENERAL::FinishTracking();

if ($Skip&&!isset($rurl)&&!$CodeAction) {
	$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_site_page WHERE ID = ".$Event->PAGE_ID;
	$Page=$Db->Select($Query);
	$Site=$Db->Select("SELECT * FROM ".NS_DB_PFX."_tracker_site WHERE ID = ".$Event->SITE_ID);
	$Current="http://".$Site->HOST;
	if (NS_TRACK_MISC::ValidVar($Page->PATH)) $Current.=$Page->PATH;
	else $Current.="/";
	if (NS_TRACK_MISC::ValidVar($NewQr)) $Current.="?$NewQr";
	NS_TRACK_MISC::Redir($Current);
}



if (!$CodeAction) { if (NS_TRACK_ACTION::CompareTemplate($Qr, $Event->QUERY)) {

	$TplGet=NS_TRACK_QUERY::ParseTemplate($Event->QUERY);
	$NewQr="";
	if (NS_TRACK_MISC::ValidArr($TplGet)) {
		foreach($TplGet as $Key=>$Value) {
			$NewQr.=$Key."=";
			if ($Value!="*") $NewQr.=$Value."&";
			else $NewQr.=$_GP[$Key]."&";
			}
	}
	if (NS_TRACK_MISC::ValidArr($CurGet)) {
		foreach ($CurGet as $Key=>$Value) {
			if (!isset($TplGet[$Key])) {
				$NewQr.="$Key=$Value&";
				}
		}
	}
	if ($NewQr) $NewQr=substr($NewQr, 0,-1);

	$PageId=$Event->PAGE_ID;
	$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_site_page WHERE ID = ".$Event->PAGE_ID;
	$Page=$Db->Select($Query);

	$CompanyId=$Site->COMPANY_ID;
	$HostsArr=NS_TRACK_GENERAL::GetSiteHosts($StId, $Site);

	if ($Event->SITE_HOST_ID) {
		$Query = "SELECT HOST FROM ".NS_DB_PFX."_tracker_site_host WHERE ID=".$Event->SITE_HOST_ID;
		$SiteHost=$Db->ReturnValue($Query);
		$Current="http://".$SiteHost;
	}
	else $Current="http://".$Site->HOST;
	if (NS_TRACK_MISC::ValidVar($Page->PATH)) $Current.=$Page->PATH;
	else $Current.="/";
	if (NS_TRACK_MISC::ValidVar($NewQr)) $Current.="?$NewQr";
	$CurrentPageArr=NS_TRACK_GENERAL::PreparePathAddr($Current);
	if ($PageId==0) $PageId=NS_TRACK_PAGE::GetPageId($CurrentPageArr, $StId);
	$SiteHostId=NS_TRACK_GENERAL::CurrentSiteHost($HostsArr, $CurrentPageArr, $Site);
	if (!$SiteHostId) $Skip=true;
	if ($Skip&&$rurl) NS_TRACK_MISC::Redir($rurl);
	if ($Skip&&!$rurl) NS_TRACK_MISC::Redir($Current); 

	$QueryId=NS_TRACK_QUERY::GetQueryId($NewQr);

	if ($PageId>0) {
		$RefPageArr=NS_TRACK_GENERAL::PreparePathAddr($Ref);
		$RefSet=NS_TRACK_REFERER::GetRefererSet($RefPageArr, $Ref);

		if(!$KeepNoRef&&$RefSet==0&&!NS_TRACK_MISC::ValidId($_COOKIE['ns_log'])) $Skip=true;
		if ($Skip&&isset($rurl)) {
			NS_TRACK_MISC::Redir($rurl);
		}

		NS_TRACK_GENERAL::UpdateVisitorPath();

		if ($CookieLogSet && NS_TRACK_GENERAL::CheckTrackingMode()) $CookieLogSet=false;
		if ($CookieLogSet) NS_TRACK_GENERAL::SetCookieLog();
		if ($FindLastNode) NS_TRACK_VISITOR::FindLastNode($VisId);

	}

	if(!$KeepNoRef&&!NS_TRACK_MISC::ValidId($_COOKIE['ns_log'])) $Skip=true;
	if ($Skip&&$rurl) NS_TRACK_MISC::Redir($rurl);

	$Actions[$eid]['Id']=$eid;
	$Actions[$eid]['Item']=$Item;
	NS_TRACK_GENERAL::UpdateVisitorAction();

	//if (NS_TRACK_MISC::ValidVar($NewQr))$Current.="&ns_skip=1";
	//else $Current.="?ns_skip=1";
	NS_TRACK_GENERAL::TrackingCookie(NS_COOKIE_PFX.'tmp_skip', '1', time()+120, '/');
}
else {
	NS_TRACK_MISC::Redir($_SERVER['HTTP_REFERER']);
	return;
}
}

if ($CodeAction) { if (!$Current) $Current=$_SERVER['HTTP_REFERER'];
	$CurrentPageArr=NS_TRACK_GENERAL::PreparePathAddr($Current);
	if (!$CurrentPageArr)  return NS_TRACK_GENERAL::FinishTracking();
	$RefPageArr=array();
	$HostsArr=NS_TRACK_GENERAL::GetSiteHosts($StId, $Site);
	$SiteHostId=NS_TRACK_GENERAL::CurrentSiteHost($HostsArr, $CurrentPageArr, $Site);
	if (!$SiteHostId) $Skip=true;
	if ($Skip) return NS_TRACK_GENERAL::FinishTracking();
	$PageId=NS_TRACK_PAGE::GetPageId($CurrentPageArr, $StId);
	$RefSet=0;
	$QueryId=NS_TRACK_QUERY::GetQueryId($CurrentPageArr['query']);
	$Actions[$eid]['Id']=$eid;
	$Actions[$eid]['Item']=$Item;
	//Dump($GLOBALS);
	NS_TRACK_GENERAL::UpdateVisitorPath();
	NS_TRACK_GENERAL::UpdateVisitorAction();
	return NS_TRACK_GENERAL::FinishTracking();
}

if (!$CodeAction) {
	if (NS_TRACK_MISC::ValidVar($rurl)) NS_TRACK_MISC::Redir($rurl);
	else NS_TRACK_MISC::Redir($Current);
	}


?>