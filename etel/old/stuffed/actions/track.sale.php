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
require_once $nsSelf."/lib/track/query.func.php";
require_once $nsSelf."/lib/track/sale.func.php";
require_once $nsSelf."/lib/track/misc.func.php";
require $nsSelf."/lib/track/define.vars.php";


$_NS_TRACK_VARS['Db']=&$Db;

$TimeDblSale=&$_NS_TRACK_VARS['TimeDblSale'];
$PresetIP=&$_NS_TRACK_VARS['PresetIP'];
$PresetID=&$_NS_TRACK_VARS['PresetID'];
$Cost=&$_NS_TRACK_VARS['Cost'];
$CustomId=&$_NS_TRACK_VARS['CustomId'];
$AddInfo=&$_NS_TRACK_VARS['AddInfo'];
$Items=&$_NS_TRACK_VARS['Items'];


$Item=&$_NS_TRACK_VARS['Item']; $Undef=&$_NS_TRACK_VARS['Undef']; $KeepVisPath=true; $UpdateVisPath=$KeepVisPath; $Cur= (NS_TRACK_MISC::ValidVar($_GP['cur']))?$_GP['cur']:false;
$ScrRes= NS_TRACK_MISC::ValidVar($_GP['wr']);
$FlVer= NS_TRACK_MISC::ValidVar($_GP['fl']);
$PxD= NS_TRACK_MISC::ValidVar($_GP['px']);
$Frame= NS_TRACK_MISC::ValidVar($_GP['frame']);

$Current=false;
if (NS_TRACK_MISC::ValidVar($_SERVER['HTTP_REFERER'])) $Current=$_SERVER['HTTP_REFERER'];
if (NS_TRACK_MISC::ValidVar($Cur)) $Current=$Cur;
$StId= (NS_TRACK_MISC::ValidId($_GP['st']))?$_GP['st']:false;


$Cost=NS_TRACK_MISC::ValidVar($_GP['cs'], 0);
$CustomId= NS_TRACK_MISC::ValidVar($_GP['oid']);
$AddInfo= NS_TRACK_MISC::ValidVar($_GP['oinfo']);

$Site=$Db->Select("SELECT * FROM ".NS_DB_PFX."_tracker_site WHERE ID = $StId");
$CompanyId=$Site->COMPANY_ID;

$Settings=NS_TRACK_MISC::GetSettings($CompanyId, $StId);
$VisId=NS_TRACK_VISITOR::GetVisitorId();
if (NS_TRACK_MISC::ValidVar($_COOKIE['ns_skip'])) $Skip=true;
if ($Skip||!NS_TRACK_MISC::ValidId($StId)) return NS_TRACK_GENERAL::FinishTracking();

$KeepNoRef=NS_TRACK_MISC::SetsByPrior($Settings, "KEEP_NO_REF");
$NoDblSale=NS_TRACK_MISC::SetsByPrior($Settings, "STOP_DBL_SALE"); 
$TimeDblSale=NS_TRACK_MISC::TimeDblSettings($Settings, "STOP_DBL_SALE", "TIME_DBL_SALE"); 

if(!$KeepNoRef&&!NS_TRACK_MISC::ValidId($_COOKIE['ns_log'])) $Skip=true;
if ($Skip) return NS_TRACK_GENERAL::FinishTracking();

$HostsArr=NS_TRACK_GENERAL::GetSiteHosts($StId, $Site);
$CurrentPageArr=NS_TRACK_GENERAL::PreparePathAddr($Current);
$SiteHostId=NS_TRACK_GENERAL::CurrentSiteHost($HostsArr, $CurrentPageArr, $Site);
if (!$SiteHostId) $Skip=true;
if ($Skip) return NS_TRACK_GENERAL::FinishTracking();

$RefSet=0;
$QueryId=NS_TRACK_QUERY::GetQueryId($CurrentPageArr['query']);
$PageId=NS_TRACK_PAGE::GetPageId($CurrentPageArr, $StId);
if ($Undef) {
	NS_TRACK_GENERAL::UpdateStatUndef();
	return NS_TRACK_GENERAL::FinishTracking();
}

$Items=(NS_TRACK_MISC::ValidArr($_GP['itm']))?NS_TRACK_SALE::PrepareSaleItems($_GP['itm']):false;


if ($NoDblSale) NS_TRACK_GENERAL::CheckSaleDblClick();
if ($Skip) return NS_TRACK_GENERAL::FinishTracking();

NS_TRACK_GENERAL::UpdateVisitorPath();
NS_TRACK_GENERAL::UpdateVisitorSale();



if (defined("TRACK_ERRORS")&&TRACK_ERRORS &&  !defined("NS_PHP_TRACKING")) {
	for ($i=0;$i<count($Logs->Errors);$i++) {
		$Db->Query("INSERT INTO track_error (ERROR) VALUES ('".addslashes($Logs->Errors[$i])."')");
	}
}


return NS_TRACK_GENERAL::FinishTracking();

?>