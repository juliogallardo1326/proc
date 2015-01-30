<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SELF."/lib/company.func.php";
$nsLang->TplInc("inc/menu");


/////////////////////////////////////////////
///////// prepare any variables
$SiteId=(ValidVar($_GP['SiteId']))?$_GP['SiteId']:false;
if (!ValidId($CpId)) $CpId=(ValidVar($_GP['CpId']))? $_GP['CpId']:false;
$IP=(ValidVar($_GP['IP']))?trim($_GP['IP']):false;


$Settings=GetSettings();

$ProgPath=array();
$ProgPath[0]['Name']=$Lang['MLogs'];
$ProgPath[0]['Url']=$nsProduct->SelfAction();
$MenuSection="logs";
$IpCnt=0;

if (ValidIp($IP)) {
	$IpCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_ip WHERE IP = '$IP'");
	if($IpCnt==1) {
		$Query = "
			SELECT V.ID 
				FROM ".PFX."_tracker_ip I
				INNER JOIN ".PFX."_tracker_visitor V
					ON V.LAST_IP_ID=I.ID
				WHERE IP = '$IP' 
				ORDER BY V.ID DESC";
		$VisId=$Db->ReturnValue($Query);
		$nsProduct->Redir("visitor_path", "VisId=$VisId&SiteId=$SiteId&CpId=$CpId", "report");
	}
	if ($IpCnt>1) {
		$nsProduct->Redir("visitor_path", "IP=$IP&SiteId=$SiteId&CpId=$CpId", "report");
	}
	if ($IpCnt==0) {
		$Logs->Msg(str_replace("{IP}", $IP, $Lang['NoSuchIp']));
	}
}

if (ValidVar($IP)&&!ValidIp($IP)) $Logs->Err($Lang['IpErr']);

$SitesArr=array();

if (!ValidId($SiteId)&&!ValidId($CpId)) $nsProduct->Redir("default");
if (ValidId($CpId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_client WHERE ID = $CpId";
	$Comp=$Db->Select($Query);
	$PageTitle=$Comp->NAME;
	$Query = "SELECT ID, HOST FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $SitesArr[]=$Row;
	if (Count($SitesArr)==1) $SiteId=$SitesArr[0]->ID;
}
if (ValidId($SiteId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_site WHERE ID = $SiteId";
	$Site=$Db->Select($Query);
	$PageTitle=$Site->HOST;
	$SiteList=$SiteId;
	if (!ValidId($CpId)) {
		$CompId=$CpId=$Site->COMPANY_ID;
	}
}



$PageTitle.=" : ".$Lang['Reports'];
$CompId=(ValidId($Comp->ID))?$Comp->ID:$Site->COMPANY_ID;

/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here
include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


/////////////////////////////////////////////
///////// library section


?>