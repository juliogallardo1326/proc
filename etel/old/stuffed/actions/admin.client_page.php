<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";
require_once SYS."/system/lib/sql.func.php";

require_once SELF."/class/report_parent.class.php";
require_once SELF."/class/paid_v2.class.php";
require_once SELF."/class/natural_v2.class.php";

$nsLang->TplInc("inc/report_headers");

/////////////////////////////////////////////
///////// prepare any variables

if (!ValidId($CurrentCompany->ID)) $nsProduct->Redir("default", "", "admin");
$PageTitle=$Lang['Title'];
$ProgPath[0]['Name']=$Lang['Home'];
$ProgPath[0]['Url']=$nsProduct->SelfAction("CpId=$CpId");
$Today=UserDate();
UserColumns(); $Settings=GetSettings();
$OnlinePeriod=$Settings['All']->ONLINE_PERIOD;
if (!$OnlinePeriod || $OnlinePeriod < 0) $OnlinePeriod=600;
/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here

if ($nsUser->Columns->HITS ||
	$nsUser->Columns->ACTIONS ||
	$nsUser->Columns->CLICKS ||
	$nsUser->Columns->SALES) {

$Stamp=gmdate("Y-m-d H:i:s", time());
$Query = "
	SELECT 
		S_LOG.SITE_ID,
		COUNT(DISTINCT S_LOG.VISITOR_ID) AS CNT
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		WHERE S_LOG.STAMP >= DATE_ADD('$Stamp', INTERVAL -$OnlinePeriod SECOND)
		GROUP BY S_LOG.SITE_ID
";
$Sql = new Query($Query);
$OnlineArr=array();
while ($Row=$Sql->Row()) {
	$OnlineArr[$Row->SITE_ID]=$Row->CNT;
}

$Query = "SELECT COUNT(*) FROM ".PFX."_tracker_site WHERE COMPANY_ID = ".$CurrentCompany->ID;
$SitesCnt=$Db->ReturnValue($Query);

$NaturalReport = new Natural_v2();
$NaturalReport->NoRef=true;
$NaturalReport->RsNeeded=false;
if ($nsUser->Columns->HITS) $NaturalReport->ShowVisitors=true;
if ($nsUser->Columns->ACTIONS) $NaturalReport->ShowActions=true;
if ($nsUser->Columns->SALES) $NaturalReport->ShowSales=true;
$NaturalReport->ViewDate=$Today;
$NaturalReport->CpId=$CurrentCompany->ID;
if ($SitesCnt==0) $NaturalReport->SiteId=-1;
$NaturalReport->SelectArr[]="TS.ID";
$NaturalReport->SelectArr[]="TS.HOST AS NAME";
$NaturalReport->JoinArr[]="INNER JOIN ".PFX."_tracker_site TS ON TS.ID=S_LOG.SITE_ID";
$NaturalReport->GroupArr[]="TS.ID";
$NaturalReport->GrpFld="ID";
$NaturalReport->GrpName="NAME";
$NaturalReport->OrderArr[]="TS.HOST ASC";
$NaturalReport->Calculate();


if ($nsUser->Columns->CLICKS) {
	$PaidReport=new Paid_v2();
	$PaidReport->ShowVisitors=true;
	$PaidReport->CpId=$CurrentCompany->ID;
	if ($SitesCnt==0) $PaidReport->SiteId=-1;
	$PaidReport->ViewDate=$Today;
	$PaidReport->SelectArr[]="TS.ID";
	$PaidReport->SelectArr[]="TS.HOST AS NAME";
	$PaidReport->JoinArr[]="INNER JOIN ".PFX."_tracker_site TS ON TS.ID=S_LOG.SITE_ID";
	$PaidReport->GroupArr[]="TS.ID";
	$PaidReport->GrpFld="ID";
	$PaidReport->GrpName="NAME";
	$PaidReport->OrderArr[]="TS.HOST ASC";
	$PaidReport->Calculate();
}

if (ValidArr($NaturalReport->StatArr)&&count($NaturalReport->StatArr)>0&&!isset($NaturalReport->StatArr[0])) {
	if ($nsUser->Columns->HITS) {
		foreach($NaturalReport->StatArr as $SiteId => $Row) {
			if (ValidVar($PaidReport->CampStat[$SiteId])) {
				$Row['CntCamp']=ValidVar($PaidReport->CampStat[$SiteId]['CntClick']);
				$Row['UniCamp']=ValidVar($PaidReport->CampStat[$SiteId]['UniClick']);
				$Row['Camp']=ValidVar($PaidReport->CampStat[$SiteId]['Camp']);
			}
			else {
				$Row['CntCamp']=0;
				$Row['UniCamp']=0;
				$Row['Camp']=0;
			}
			if (ValidVar($OnlineArr[$SiteId])) $Row['Online']=$OnlineArr[$SiteId];
			else $Row['Online']="0";
			$NaturalReport->StatArr[$SiteId]=$Row;
		}
	}
}
else unset($NaturalReport->StatArr);

}

include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


/////////////////////////////////////////////
///////// library section


?>