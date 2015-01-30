<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";
$nsLang->TplInc("inc/menu");
$nsLang->TplInc("admin.reports");

/////////////////////////////////////////////
///////// prepare any variables
$SiteId=(ValidVar($_GP['SiteId']))? $_GP['SiteId']:false;
$CpId=(ValidVar($_GP['CpId']))? $_GP['CpId']:false;
$StartDate=(ValidDate($_GP['StartDate']))? $_GP['StartDate']:false;
$EndDate=(ValidDate($_GP['EndDate']))? $_GP['EndDate']:false;
$ViewDate=(ValidDate($_GP['ViewDate']))? $_GP['ViewDate']:false;
$FormClicked=ValidVar($_GP['FormClicked']);
 
$StartDay=false;
$EndDay=false;
$SitesArr=array();

if (!ValidId($SiteId)&&!ValidId($CpId)) $nsProduct->Redir("default");
if (ValidId($CpId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_client WHERE ID = $CpId";
	$Comp=$Db->Select($Query);
	$PageTitle=$Comp->NAME;
	$Query = "SELECT * FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$SitesArr[]=$Row;
		$IdArr[]=$Row->ID;
	}
	$SiteList=implode(",", $IdArr);
}
if (ValidId($SiteId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_site WHERE ID = $SiteId";
	$Site=$Db->Select($Query);
	$PageTitle=$Site->HOST;
	$SiteList=$SiteId;
}
$PageTitle.=" : ".$Lang['Title'];

$ProgPath[0]['Name']=$Lang['MLogs'];
$ProgPath[0]['Url']=getUrl("reports", "CpId=$CpId&SiteId=$SiteId", "admin");
$ProgPath[1]['Name']=$Lang['Compare'];
$ProgPath[1]['Url']=getUrl("compare", "CpId=$CpId&SiteId=$SiteId", "report");



if (!$StartDate&&!$EndDate&&!$ViewDate&&!$FormClicked) $ViewDate=UserDate();
$MenuSection="logs";

//$SubMenu[0]['Name']=$Lang['OtherReports'];
//$SubMenu[0]['Link']=getURL("reports", "CpId=$CpId&SiteId=$SiteId", "admin");


/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here

$WhereArr=array();
$WhereStr="";
if ($StartDate) $StartDay=$StartDate." 00:00:00";
if ($EndDate) $EndDay=$EndDate." 00:00:00";
if ($ViewDate) {
	$EndDay=$Db->ReturnValue("SELECT DATE_ADD('$ViewDate', INTERVAL 1 DAY)")." 00:00:00";
	$StartDay=$ViewDate." 00:00:00";
}
if ($StartDay&&!$EndDay) $WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) >= '$StartDay'";
if (!$StartDay&&$EndDay) $WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) <= '$EndDay'";
if ($StartDay&&$EndDay) $WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) BETWEEN '$StartDay' AND '$EndDay'";
$WhereArr[]="S_LOG.SITE_ID IN ($SiteList)";
if (Count($WhereArr)>0) 	$WhereStr=implode(" AND ", $WhereArr);


$Query = "
	SELECT 
	COUNT(DISTINCT S_LOG.VISITOR_ID) 
	FROM  ".PFX."_tracker_".$CpId."_stat_log S_LOG 
	WHERE $WhereStr
";
$TotalCnt=$Db->ReturnValue($Query);
$TotalCntPerc=100;

$Query = "
	SELECT 
	COUNT(DISTINCT S_LOG.VISITOR_ID) 
	FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG 
	INNER JOIN ".PFX."_tracker_referer_set RS
	ON RS.ID=S_LOG.REFERER_SET
	WHERE RS.HOST_ID>0
	AND $WhereStr
";
$RefCnt=$Db->ReturnValue($Query);
$RefCntPerc=($RefCnt>0)?round((100/$TotalCnt)*$RefCnt, 2):0;

$NoRefCnt=$TotalCnt-$RefCnt;
$NoRefCntPerc=($NoRefCnt>0)?round((100/$TotalCnt)*$NoRefCnt, 2):0;

$Query = "
	SELECT 
	COUNT(DISTINCT S_LOG.VISITOR_ID) 
	FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG 
	INNER JOIN ".PFX."_tracker_referer_set RS
	ON RS.ID=S_LOG.REFERER_SET
	WHERE RS.NATURAL_KEY>0
	AND $WhereStr
";
$KeyCnt=$Db->ReturnValue($Query);
$KeyCntPerc=($KeyCnt>0)?round((100/$TotalCnt)*$KeyCnt, 2):0;

$Query = "
	SELECT 
	COUNT(DISTINCT S_LOG.VISITOR_ID) 
	FROM ".PFX."_tracker_".$CpId."_stat_action S_ACTION
	INNER JOIN ".PFX."_tracker_".$CpId."_stat_log S_LOG 
	ON S_LOG.ID=S_ACTION.LOG_ID
	WHERE $WhereStr
";
$ActionCnt=$Db->ReturnValue($Query);
$ActionCntPerc=($ActionCnt>0)?round((100/$TotalCnt)*$ActionCnt, 2):0;


$Query = "
	SELECT 
	COUNT(DISTINCT S_LOG.VISITOR_ID) 
	FROM ".PFX."_tracker_".$CpId."_stat_sale S_SALE
	INNER JOIN ".PFX."_tracker_".$CpId."_stat_log S_LOG 
	ON S_LOG.ID=S_SALE.LOG_ID
	WHERE $WhereStr
";
$SaleCnt=$Db->ReturnValue($Query);
$SaleCntPerc=($SaleCnt>0)?round((100/$TotalCnt)*$SaleCnt, 2):0;

$Query = "
	SELECT 
	COUNT(DISTINCT S_LOG.VISITOR_ID) 
	FROM ".PFX."_tracker_".$CpId."_stat_click S_CLICK
	INNER JOIN ".PFX."_tracker_".$CpId."_stat_log S_LOG 
	ON S_LOG.ID=S_CLICK.LOG_ID
	WHERE $WhereStr
";
$ClickCnt=$Db->ReturnValue($Query);
$ClickCntPerc=($ClickCnt>0)?round((100/$TotalCnt)*$ClickCnt, 2):0;

include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here





/////////////////////////////////////////////
///////// library section






?>