<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");

 
/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";
require_once SELF."/lib/delete.func.php";
require_once SELF."/lib/store.func.php";

$nsLang->TplInc("inc/menu");

/////////////////////////////////////////////
///////// prepare any variables

$SiteId=(ValidId($_GP['SiteId']))? $_GP['SiteId']:false;
$CpId=(ValidId($_GP['CpId']))? $_GP['CpId']:false;
$EditId=(ValidVar($_GP['EditId']))? $_GP['EditId']:false;
$EditArr=(ValidArr($_GP['EditArr']))? $_GP['EditArr']:false;
$DeleteId=(ValidId($_GP['DeleteId']))? $_GP['DeleteId']:false;
$Mode=(ValidVar($_GP['Mode']))? $_GP['Mode']:"list";
$WrErr=(ValidVar($_GP['WrErr']))? $_GP['WrErr']:false;
$PageId=(ValidId($_GP['PageId']))? $_GP['PageId']:false;
$fc=(ValidVar($_GP['fc']))?true:false;
$PagePath="/";

if ($Mode!="list"&&$Mode!="new"&&$Mode!="edit") $Mode="list";
if (ValidId($EditId)) $Mode="edit";

if ($fc&&$Mode=="list") $nsSession->set("aSiteId", $SiteId);
if (!$fc&&$Mode=="list") $SiteId=$nsSession->get("aSiteId");

if ($WrErr) $Logs->Err($Lang['WriteErr']);

if (!ValidId($CpId)) $nsProduct->Redir("default", "", "admin");

if (ValidId($CpId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_client WHERE ID = $CpId";
	$Comp=$Db->Select($Query);
	$PageTitle=$Comp->NAME;
	$Query = "SELECT ID, HOST FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $SitesArr[]=$Row;
	if (Count($SitesArr)==1) $SiteId=$SitesArr[0]->ID;
	if ($Mode=="new"&&!$SiteId) $SiteId=$SitesArr[0]->ID;
}

if (ValidId($SiteId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_site WHERE ID = $SiteId";
	$Site=$Db->Select($Query);
	$PageTitle=$Site->HOST;
	$SiteList=$SiteId;
	$HostsArr=array();
	$Site->Hosts=array();
	$Query = "SELECT * FROM ".PFX."_tracker_site_host WHERE SITE_ID=$SiteId ORDER BY LENGTH(HOST) DESC";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$HostsArr[]=$Row->HOST;
		$Site->Hosts[$Row->ID]=$Row->HOST;
	}
}

if (ValidId($PageId)&&$PageId>0) {
	$Query = "SELECT PATH FROM ".PFX."_tracker_site_page WHERE ID=$PageId";
	$PagePath=$Db->ReturnValue($Query);
}

$PageTitle.=" : ".$Lang['Actions'];
$ProgPath[0]['Name']=$Lang['MActions'];
$ProgPath[0]['Url']=$nsProduct->SelfAction("CpId=$CpId");


/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if (ValidVar($EditId)=="new"&&ValidArr($EditArr)) UpdateAction($EditArr);
	if (ValidId($EditId)&&ValidArr($EditArr)) UpdateAction($EditArr, $EditId);
	if (ValidId($DeleteId)) DropAction($DeleteId);
}

/////////////////////////////////////////////
///////// display section here

if ($Mode=="list") {
	$SubMenu[0]['Name']=$Lang['CreateNewAction'];
	$SubMenu[0]['Link']=getURL("actions", "CpId=$CpId&SiteId=$SiteId&Mode=new&EditId=new", "admin");
}
else {
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("actions", "CpId=$CpId&SiteId=$SiteId", "admin");
}


if ($Mode=="new") {
	$EditArr['Name']=(ValidVar($EditArr['Name']))?$EditArr['Name']:"";
	$EditArr['Templ']=(ValidVar($EditArr['Templ']))?$EditArr['Templ']:"http://".ValidVar($Site->HOST).$PagePath;
	$EditArr['RedirUrl']=(ValidVar($EditArr['RedirUrl']))?$EditArr['RedirUrl']:"";
	$EditArr['RedirOnly']=ValidVar($EditArr['RedirOnly'], 0);
	$EditArr['ItemVar']=(ValidVar($EditArr['ItemVar']))?$EditArr['ItemVar']:"";
	$EditArr['Active']=(ValidVar($EditArr['Active']))?$EditArr['Active']:1;
	$EditArr['Dynamic']=(ValidVar($EditArr['Dynamic'])==1)?1:0;
	$EditArr['CodeAction']=(ValidVar($EditArr['RedirOnly'])==2)?1:0;
	$TableCaption=$Lang['CreateAction'];
}
if (ValidId($EditId)) {
	$Mode="edit";
	$Query = "
		SELECT VA.*, SP.PATH AS PAGE_PATH
			FROM ".PFX."_tracker_visitor_action VA
			LEFT JOIN ".PFX."_tracker_site_page SP
				ON SP.ID=VA.PAGE_ID
			WHERE VA.ID = $EditId AND VA.SITE_ID=$SiteId
	";
	$Action=$Db->Select($Query);
	$EditArr['Name']=$Action->NAME;
	$EditArr['RedirUrl']=ValidVar($Action->REDIRECT_URL);
	$EditArr['RedirOnly']=ValidVar($Action->REDIRECT_CATCH);
	$EditArr['ItemVar']=ValidVar($Action->ITEM_VAR);
	$EditArr['Active']=ValidVar($Action->ACTIVE);
	$EditArr['Dynamic']=(ValidVar($Action->REDIRECT_URL))?0:1;
	$EditArr['CodeAction']=(ValidVar($Action->CODE_ACTION))?1:0;
	$EditArr['Templ']="";
	$TableCaption=stripslashes($Action->NAME);


	
	if ($Action->REDIRECT_CATCH) {
		$RedirCode="";
		$Query = "SELECT SSL_LINK FROM ".PFX."_tracker_config WHERE COMPANY_ID=0";
		$SSLink=$Db->ReturnValue($Query);
		if ($SSLink) {
			$HL=$nsProduct->HL;
			$nsProduct->HL=$SSLink;
		}

		if ($Action->QUERY) $RedirCode=getURL("event", "eid=".$Action->ID."&".str_replace("?", "", $Action->QUERY), "track");
		else {
			if (!MOD_R) $RedirCode=getURL("event", "eid=".$Action->ID, "track");
			else {
				$RedirCode=str_replace(".html", "", getURL("event", "", "track"));
				$RedirCode.="/e".$Action->ID."/";
			}
		}
		if ($SSLink) $nsProduct->HL=$HL;
		if ($RedirCode&&$Action->REDIRECT_CATCH) $Logs->Msg($Lang['UrlName']."<U>".$RedirCode."</U>");
	}

	$Action->IsPathTempl=(ValidVar($Action->PATH))?true:false;
	if (!ValidVar($Site->Hosts[$Action->SITE_HOST_ID])) $Site->Hosts[$Action->SITE_HOST_ID]=$Site->HOST;
	
	if (!ValidVar($Action->PATH)) $Action->PATH="/";
	if (ValidVar($Action->QUERY)&&(strpos($Action->QUERY, "?")===false||strpos($Action->QUERY, "?")!=0))
		$Action->QUERY="?".$Action->QUERY;
	if (ValidVar($Action->PATH)) $EditArr['Templ']="http://".$Site->Hosts[$Action->SITE_HOST_ID].$Action->PATH;
	if ($Action->PAGE_ID>0) $EditArr['Templ']="http://".$Site->Hosts[$Action->SITE_HOST_ID].$Action->PAGE_PATH;
	if (ValidVar($Action->QUERY)) $EditArr['Templ'].=$Action->QUERY;
	if ($Action->PAGE_PATH) $Action->PATH=$Action->PAGE_PATH;
	
	if ($Action->CODE_ACTION) {
		$SubMenu[1]['Name']=$Lang['GenerateCode'];
		$SubMenu[1]['Link']=getURL("get_code", "CpId=$CpId&SiteId=".$Action->SITE_ID."&CodeType=1&CodePlace=3");
	}
}




$ActionsArr=array();

$ListWhere="";
if (ValidId($SiteId)) $ListWhere="WHERE VA.SITE_ID = $SiteId ";
else {
	$In="";
	for($i=0;$i<count($SitesArr);$i++) {
		if ($i>0) $In.=",";
		$In.=$SitesArr[$i]->ID;
	}
	$ListWhere="WHERE VA.SITE_ID IN ($In)";
}

	$Query="
		SELECT VA.*, SP.PATH AS PAGE_PATH, S.HOST
			FROM ".PFX."_tracker_visitor_action VA
				INNER JOIN ".PFX."_tracker_site S
					ON S.ID=VA.SITE_ID
				LEFT JOIN ".PFX."_tracker_site_page SP
					ON SP.ID=VA.PAGE_ID
			$ListWhere
			ORDER BY VA.SITE_ID, VA.NAME
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		if ($Row->PAGE_PATH) $Row->PATH=$Row->PAGE_PATH;
		$Row->REDIRECT_URL=ValidVar($Row->REDIRECT_URL);
		$Row->REDIRECT_CATCH=ValidVar($Row->REDIRECT_CATCH);
		if (ValidVar($Row->QUERY)!=""&&strpos($Row->QUERY, "?")===false) $Row->QUERY="?".$Row->QUERY;
		$ActionsArr[]=$Row;
	}


include $nsTemplate->Inc();


/////////////////////////////////////////////
///////// process functions here

function UpdateAction($Arr, $Id=false)
{
	global $Db, $Logs, $nsProduct, $HostsArr, $SiteId, $CpId, $Site, $Lang;
	$TPath="";
	$ActionPath="";
	$CodeAction=0;
	$SiteHostId=0;
	
	extract($Arr);
	if (!ValidVar($Name)) {$Logs->Err($Lang['MustFillName']);return;}
	if (!ValidVar($Templ)&&ValidVar($RedirOnly)!=2) {$Logs->Err($Lang['WhatIsAction']);return;}
	if (ValidVar($RedirOnly)==1&&!ValidVar($RedirUrl)&&!ValidVar($Dynamic)) {$Logs->Err($Lang['RedirUrlRequired']);return;}
	if (!ValidVar($Active)) $Active=0;
	if (ValidVar($RedirOnly)==2) $CodeAction=1;

	if (!$CodeAction) { $TmpArr=explode("{a}", $Templ);
	if (ValidArr($TmpArr)&&count($TmpArr)>2) {$Logs->Err($Lang['OnlyOneActionTarget']);return;}

	$NoPage=false;
	$TArr=@parse_url($Templ);
	if (!$TArr) {$Logs->Err($Lang['UnableParseTemplate']);return;}
	if (isset($TArr['path'])&&!isset($TArr['host'])) {
		//$Path=ToLower($TArr['path']);
		for($i=0;$i<count($HostsArr);$i++) {
			if (strpos($Path, $HostsArr[$i])!==false) {
				$Path=str_replace($HostsArr[$i], "", $Path);
				$TArr['host']=$HostsArr[$i];
				$TArr['path']=$Path;
				break;
			}
		}
	}	
	if (count($TArr)==1&&substr(ValidVar($TArr['path']), 0, 1)!="/") {
		if (isset($TArr['path'])&&!isset($TArr['query'])) $TArr['query']=$TArr['path'];
		unset($TArr['path']);			
		$NoPage=1;
	}
	if (isset($TArr['fragment'])) unset($TArr['fragment']);

	if (!$NoPage) {
		if (!isset($TArr['host'])) $TArr['host']=$Site->HOST;
		$TArr['host']=ToLower($TArr['host']);
		//if (isset($TArr['path'])&&isset($TArr['query'])&&!isset($TArr['host'])) $TArr['host']=$Site->HOST;
		if (isset($TArr['path'])&&substr($TArr['path'], 0, 1)!="/") $TArr['path']="/".$TArr['path'];
		if (!ValidVar($TArr['host'])||!in_array($TArr['host'], $HostsArr)) {
			//$Logs->Err($Lang['InvalidDomain']);return;
			$Query = "INSERT INTO ".PFX."_tracker_site_host (HOST, SITE_ID) VALUES (?, ".$Site->ID.")";
			$Db->Query($Query, $TArr['host']);
			$NewHostId=$Db->LastInsertId;
			$HostsArr[]=$TArr['host'];
			$Site->Hosts[$NewHostId]=$TArr['host'];
		}
		if (!ValidVar($TArr['path'])) $TArr['path']="/";

		if (strpos($TArr['path'], "*")===false
			&&strpos($TArr['path'], "{a}")===false
			&&strpos($TArr['path'], ".")===false
			&&$TArr['path']!="/"
			&&substr($TArr['path'],-1)!="/"
		) $TArr['path'].="/";

		extract($TArr);

		foreach ($Site->Hosts as $SHId=>$SHost) {
			if ($host==$SHost) $SiteHostId=$SHId;
		}

		if (strpos($path, "*")===false&&strpos($path, "{a}")===false) {
			$Query = "SELECT ID FROM ".PFX."_tracker_site_page WHERE SITE_ID=$SiteId AND PATH='$path'";
			$PageId=$Db->ReturnValue($Query);
			if (!ValidId($PageId)) {
				$Query = "INSERT INTO ".PFX."_tracker_site_page (SITE_ID, PATH) VALUES ($SiteId, '$path')";
				$Db->Query($Query);
				$PageId=$Db->LastInsertId;
			}
			$ActionPath=$path;
			if (!ValidId($PageId)) {$Logs->Err($Lang['UnableCreatePage']);return;}
		}
		else {
			if (ValidVar($RedirOnly)) {$Logs->Err($Lang['NoRedirWithPageTemp']);return;}
			$PageId=0;
			$TPath=$path;
		}


		if (ValidVar($query)) $query="?".$query;
	}
	else {
		$PageId=0;
		$query=$Templ;
		if (strpos($query, "?")===false||strpos($query, "?")!=0) $query="?".$query;
	}

	if (ValidVar($RedirOnly)!=1&&ValidVar($RedirOnly)!=2) $RedirOnly=0;
	if (!ValidVar($RedirUrl)) $RedirUrl="";
	if (!ValidVar($ItemVar)) $ItemVar="";
	
	$RedirUrl=urlencode($RedirUrl);
	$LogInfo="";
	} else {
		$PageId=0;
		$query = "";
		$RedirUrl="";
		$TPath="";
		$ItemVar="";
		$RedirOnly=0;
	}
	

	if (!$Id) {
		$Query = "INSERT INTO ".PFX."_tracker_visitor_action (SITE_ID, PAGE_ID, NAME, QUERY, PATH, REDIRECT_URL, REDIRECT_CATCH, ITEM_VAR, ACTIVE, CODE_ACTION, SITE_HOST_ID) VALUES ($SiteId, $PageId, '$Name', '$query', '$TPath', '$RedirUrl', '$RedirOnly', '$ItemVar', '$Active', '$CodeAction', '$SiteHostId')";
		$Db->Query($Query);
		$EditId=$Db->LastInsertId;
		$LogInfo="&RCrt=1";
	}
	else {
		$Query="UPDATE ".PFX."_tracker_visitor_action SET PAGE_ID=$PageId, NAME='$Name', QUERY='$query', PATH='$TPath', REDIRECT_URL='$RedirUrl', REDIRECT_CATCH='$RedirOnly', ITEM_VAR='$ItemVar', ACTIVE='$Active', CODE_ACTION='$CodeAction', SITE_HOST_ID='$SiteHostId' WHERE ID = $Id AND SITE_ID=$SiteId";
		$Db->Query($Query);
		$EditId=$Id;
		$LogInfo="&RUpd=1";
	}

	$WrRes=true;
	$WrErr=false;
	if (!$CodeAction) {
	$UseStore=$Db->ReturnValue("SELECT USE_STORE FROM ".PFX."_tracker_config WHERE COMPANY_ID=0");
	if ($RedirOnly&&$UseStore) {
		$ActionArr['ID']=$EditId;
		$ActionArr['PAGE_ID']=$PageId;
		$ActionArr['SITE_ID']=$SiteId;
		$ActionArr['REDIRECT_URL']=$RedirUrl;
		$ActionArr['PATH']=$ActionPath;
		$WrRes=false;
		$WrRes=SaveActionToFile($ActionArr, "redir_action.nodb");
		//if (!$WrRes) $WrErr="&WrErr=1";
	}
	}

	$nsProduct->Redir("actions", "CpId=$CpId&SiteId=$SiteId&EditId=$EditId".$WrErr.$LogInfo);
}



function DropAction($Id)
{
	global $Db, $SiteId, $CpId, $nsProduct;
	$Query = "DELETE FROM ".PFX."_tracker_visitor_action WHERE ID = $Id";
	$Db->Query($Query);
	DeleteActionStat($CpId, $Id);
	$nsProduct->Redir("actions", "CpId=$CpId&SiteId=$SiteId&RDlt=1", "admin");
}


/////////////////////////////////////////////
///////// library section



?>