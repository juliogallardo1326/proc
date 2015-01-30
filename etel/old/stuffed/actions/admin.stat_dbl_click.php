<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
$SiteId=(ValidId($_GP['SiteId']))?$_GP['SiteId']:false;
$CpId=(ValidId($_GP['CpId']))?$_GP['CpId']:false;
$SaveSet=(ValidArr($_GP['SaveSet']))?$_GP['SaveSet']:false;

if ($CpId&&!($nsUser->SUPER_USER||$nsUser->ADMIN)) $nsProduct->Redir("default", "", "admin");
if (!$CpId&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");


/////////////////////////////////////////////
///////// prepare any variables

$SitesArr=array();
$ClientsArr=array();
$ST=array();
$PageTitle="";
$nsLang->TplInc("inc/user_welcome");
$nsLang->TplInc("inc/menu");

if ($nsUser->ADMIN&&$nsProduct->LICENSE==3&&!$CpId) $Query = "SELECT * FROM ".PFX."_tracker_client";
else $Query = "SELECT * FROM ".PFX."_tracker_client WHERE ID = $CpId";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) $ClientsArr[]=$Row;

if (ValidId($CpId)) $Query = "SELECT ID, HOST, COMPANY_ID FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
else $Query = "SELECT ID, HOST, COMPANY_ID FROM ".PFX."_tracker_site";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) $SitesArr[$Row->COMPANY_ID][]=$Row;

$ConfigWhere="";
if ($CpId) $ConfigWhere="WHERE COMPANY_ID IN (0, $CpId)";
$Query = "SELECT * FROM ".PFX."_tracker_config $ConfigWhere";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	if ($Row->TIME_DBL_PAGE_LOAD==0) $Row->TIME_DBL_PAGE_LOAD="";
	if ($Row->TIME_DBL_ADV_CLICK==0) $Row->TIME_DBL_ADV_CLICK="";
	if ($Row->TIME_DBL_EVENT==0) $Row->TIME_DBL_EVENT="";
	if ($Row->TIME_DBL_SALE==0) $Row->TIME_DBL_SALE="";
	$ST[$Row->COMPANY_ID][$Row->SITE_ID]=$Row;
}

for ($i=0;$i<count($ClientsArr);$i++) {
	if (!ValidVar($ST[$ClientsArr[$i]->ID][0])) {
		$Query = "INSERT INTO ".PFX."_tracker_config (COMPANY_ID, SITE_ID) VALUES (".$ClientsArr[$i]->ID.", 0)";
		$Db->Query($Query);
	}
	for($j=0;$j<count($SitesArr[$ClientsArr[$i]->ID]);$j++) {
		$Row=$SitesArr[$ClientsArr[$i]->ID][$j];
		if (!ValidVar($ST[$ClientsArr[$i]->ID][$Row->ID])) {
			$Query = "INSERT INTO ".PFX."_tracker_config (COMPANY_ID, SITE_ID) VALUES (".$ClientsArr[$i]->ID.", ".$Row->ID.")";
			$Db->Query($Query);
		}
	}
}

$Consult->CurrentHelp="hint";

if (!$CpId) {
	$Glob=$ST[0][0];
	$GlobName="SaveSet[0][0]";
}
else {
	$Glob=$ST[$CpId][0];
	if ($Glob->STOP_DBL_PAGE_LOAD==2) $Glob->TIME_DBL_PAGE_LOAD=$ST[0][0]->TIME_DBL_PAGE_LOAD;
	if ($Glob->STOP_DBL_ADV_CLICK==2) $Glob->TIME_DBL_ADV_CLICK=$ST[0][0]->TIME_DBL_ADV_CLICK;
	if ($Glob->STOP_DBL_EVENT==2) $Glob->TIME_DBL_EVENT=$ST[0][0]->TIME_DBL_EVENT;
	if ($Glob->STOP_DBL_SALE==2) $Glob->TIME_DBL_SALE=$ST[0][0]->TIME_DBL_SALE;

	if($nsProduct->LICENSE!=3) {
		if ($Glob->STOP_DBL_PAGE_LOAD==2) $Glob->STOP_DBL_PAGE_LOAD=$ST[0][0]->STOP_DBL_PAGE_LOAD;
		if ($Glob->STOP_DBL_ADV_CLICK==2) $Glob->STOP_DBL_ADV_CLICK=$ST[0][0]->STOP_DBL_ADV_CLICK;
		if ($Glob->STOP_DBL_EVENT==2) $Glob->STOP_DBL_EVENT=$ST[0][0]->STOP_DBL_EVENT;
		if ($Glob->STOP_DBL_SALE==2) $Glob->STOP_DBL_SALE=$ST[0][0]->STOP_DBL_SALE;
	}
	/*}
	if($nsProduct->LICENSE==3) {
		$Glob->TIME_DBL_PAGE_LOAD=$ST[0][0]->TIME_DBL_PAGE_LOAD;
		$Glob->TIME_DBL_ADV_CLICK=$ST[0][0]->TIME_DBL_ADV_CLICK;
		$Glob->TIME_DBL_EVENT=$ST[0][0]->TIME_DBL_EVENT;
		$Glob->TIME_DBL_SALE=$ST[0][0]->TIME_DBL_SALE;
	}*/
	$GlobName="SaveSet[$CpId][0]";
}


if (ValidId($CpId)) {
	$MenuSection="settings";
	$ProgPath[0]['Name']=$Lang['MSettings'];
	$ProgPath[0]['Url']=getURL("settings", "CpId=$CpId", "admin");
}
else {
	$MenuSection="admin";
	$ProgPath[0]['Name']=$Lang['Administr'];
	$ProgPath[0]['Url']=getURL("admin", "", "admin");

}

$PageTitle.=$Lang['Title'];
$ProgPath[1]['Name']=$Lang['Title'];
$ProgPath[1]['Url']=getURL("stat_dbl_click", "CpId=$CpId&SiteId=$SiteId", "admin");

/////////////////////////////////////////////
///////// call any process functions
if (!$nsUser->DEMO) {
	if (ValidArr($SaveSet)) SaveSettings($SaveSet);
}
/////////////////////////////////////////////
///////// display section here

$Sets=GetSettings($CpId, $SiteId);
include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here

function SaveSettings($SaveSet)
{
	//Dump($SaveSet);
	//return;
	global $Db, $Logs, $Lang, $CpId, $nsProduct;
	foreach($SaveSet as $CP=>$Arr) {
		foreach ($Arr as $SiteId=>$SubArr) {
			extract($SubArr);
			$TimePageLoad=isset($TimePageLoad)?$TimePageLoad:"";
			$TimeAdvClick=isset($TimeAdvClick)?$TimeAdvClick:"";
			$TimeEvent=isset($TimeEvent)?$TimeEvent:"";
			$TimeSale=isset($TimeSale)?$TimeSale:"";
			if ($PageLoad!=1) $TimePageLoad="";
			if ($AdvClick!=1) $TimeAdvClick="";
			if ($Event!=1) $TimeEvent="";
			if ($Sale!=1) $TimeSale="";
			$Query = "
				UPDATE ".PFX."_tracker_config SET 
				`TIME_DBL_PAGE_LOAD`='$TimePageLoad',
				`TIME_DBL_ADV_CLICK`='$TimeAdvClick',
				`TIME_DBL_EVENT`='$TimeEvent',
				`TIME_DBL_SALE`='$TimeSale',
				`STOP_DBL_PAGE_LOAD`='$PageLoad',
				`STOP_DBL_ADV_CLICK`='$AdvClick',
				`STOP_DBL_EVENT`='$Event',
				`STOP_DBL_SALE`='$Sale'
				WHERE COMPANY_ID=$CP AND SITE_ID=$SiteId
			";
			$Db->Query($Query);
			unset($TimePageLoad, $TimeAdvClick, $TimeEvent, $TimeSale);
		}
	}
	$nsProduct->Redir(false, "RUpd=1&CpId=$CpId");
}


/////////////////////////////////////////////
///////// library section


?>