<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");

/////////////////////////////////////////////
///////// require libraries here

/////////////////////////////////////////////
///////// prepare any variables




$SiteId=(ValidId($_GP['SiteId']))?$_GP['SiteId']:false;
$CodeType=(ValidVar($_GP['CodeType']))?$_GP['CodeType']:false;
$CodePlace=(ValidVar($_GP['CodePlace']))?$_GP['CodePlace']:false;
$FormClicked=(ValidVar($_GP['FormClicked']))?$_GP['FormClicked']:false;
$ActionId=(ValidVar($_GP['ActionId']))?$_GP['ActionId']:false;
$SSL=(ValidVar($_GP['SSL']))?$_GP['SSL']:false;

$AllowPhp=false;

if ($CodeType!=1&&$CodeType!=2&&$CodeType!=3&&$CodeType!=4) $CodeType=1;
if ($CodePlace!=1&&$CodePlace!=2&&$CodePlace!=3) $CodePlace=1;



$Query = "SELECT SSL_LINK FROM ".PFX."_tracker_config WHERE COMPANY_ID=0";
$SSLink=$Db->ReturnValue($Query);
if ($SSLink) {
	$HL=$nsProduct->HL;
	$nsProduct->HL=$SSLink;
}

$PageTitle="";
$SitesArr=array();
$SitesCnt=0;
$TopCodeHelp="";
$CodeComment="";
$TrackPath=getURL("default", "", "track");
$SalePath=getURL("sale", "", "track");
$ActionPath=getURL("event", "", "track");

$JsPath=$nsProduct->HL."/track.js";
$PAmp=(MOD_R)?"?":"";
$ActionsArr=array();

if ($SSLink) {
	$nsProduct->HL=$HL;
}


$nsLang->TplInc("inc/menu");
$ProgPath[0]['Name']=$Lang['MSettings'];
$ProgPath[0]['Url']=getURL("settings", "CpId=$CpId", "admin");
$ProgPath[1]['Name']=$Lang['CodeGen'];
$ProgPath[1]['Url']=getURL("get_code", "CpId=$CpId&SiteId=$SiteId", "admin");

if (ValidId($CpId)) {
	$MenuSection="settings";
	$Query = "SELECT * FROM ".PFX."_tracker_client WHERE ID = $CpId";
	$Comp=$Db->Select($Query);
	$AllowPhp=$Comp->ALLOW_PHP_TRACKING;
	$PageTitle=$Comp->NAME;
	$Query = "SELECT ID FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
	$IdArr=array();
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $IdArr[]=$Row->ID;
	$SiteList=implode(",", $IdArr);
	$Query = "SELECT ID, HOST FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $SitesArr[]=$Row;
	if (Count($SitesArr)==1) $SiteId=$SitesArr[0]->ID;
	$SitesCnt=count($SitesArr);
}
if (ValidId($SiteId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_site WHERE ID = $SiteId";
	$Site=$Db->Select($Query);
	if (ValidId($CpId)&&$CpId!=$Site->COMPANY_ID) $CpId=$Site->COMPANY_ID;
	$PageTitle=$Site->HOST;
	$SiteList=$SiteId;
	$SitesCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site WHERE COMPANY_ID=".$Site->COMPANY_ID);
	$Query = "SELECT ID, NAME FROM ".PFX."_tracker_visitor_action WHERE CODE_ACTION='1' AND SITE_ID=$SiteId";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $ActionsArr[]=$Row;
	if (count($ActionsArr)==0) {
		if ($CodePlace==3) {
			$Logs->Alert($Lang['NoActionForSite']);
			$CodePlace=1;
		}
	}
	else {
		if (!$ActionId) $ActionId=$ActionsArr[0]->ID;
	}
}

if ($nsProduct->LICENSE==3 && !$AllowPhp && $CodeType==4) $CodeType==1;

$PageTitle.=" : ".$Lang['CodeGen'];
$ResultCode="";
$ShopCode="";

/////////////////////////////////////////////
///////// call any process functions

/////////////////////////////////////////////
///////// display section here


if (ValidId($SiteId)) {
	$ResultCode="";
	if (!$nsProduct->WHITE&&$CodeType!=4) $ResultCode.="<!-- Start of Stuffed Tracker ".$nsProduct->VERSION." code for http://".$Site->HOST." -->\n";
	if (!$nsProduct->WHITE&&$CodeType==4) $ResultCode.="<?\n// Start of Stuffed Tracker ".$nsProduct->VERSION." code for http://".$Site->HOST." //\n";	

	if ($CodeType==1||$CodeType==2) {
		$ResultCode.="<script type=\"text/javascript\">\n";
		if (!MOD_R) $ResultCode.="var nsAmp=unescape('%26');\n";
	}

	if ($CodePlace==1) {

		if ($CodeType==1) {
			if (!MOD_R) $TrackPath=str_replace("&", "'+nsAmp+'", $TrackPath);
			$ResultCode.="var nsSiteId=$SiteId;\nvar nsTrackPath='$TrackPath$PAmp';\nvar nsTrackMode='default';\nvar nsCode=1;";
		}
		if ($CodeType==3) {
			$TrackPath=str_replace("&", "&amp;", $TrackPath);
			$ResultCode.="<img src=\"".$TrackPath.$PAmp."st=$SiteId\" width=1 height=1 alt=\"\" style=\"display:none\">";
		}
		if ($CodeType==4) {
			$ResultCode.='' .
				'include_once "'.$CurPath.'/track.php";'."\n".
				'$nsTrack=new nsTrack("default", '.$SiteId.', "'.$CurPath.'");'."\n".
				'$nsTrack->DoTrack();';

		}
	}

	if ($CodePlace==2) {
		if ($CodeType==1) {
			if (!MOD_R) $SalePath=str_replace("&", "'+nsAmp+'", $SalePath);
			$ResultCode.="var nsSiteId=$SiteId;\nvar nsTrackPath='$SalePath$PAmp';\nvar nsTrackMode='sale';\nvar nsCode=1;\nvar nsCost='';\nvar nsOrderId='';\nvar nsOrderInfo='';\nvar nsOrderItems = new Array();\n//nsOrderItems.push('{{".$Lang['TestItemName']."}}{{".$Lang['TestItemValue']."}}{{".$Lang['TestItemCnt']."}}');";
		}

		if ($CodeType==3) {
			$SalePath=str_replace("&", "&amp;", $SalePath);
			$ResultCode.="<img src=\"".$SalePath.$PAmp."st=$SiteId\" width=1 height=1 alt=\"\" style=\"display:none\">";
		}
		if ($CodeType==4) {
			$ResultCode.='' .
					'//$nsSTcost="99.95";'."\n".
					'//$nsSToid="OID155933";'."\n".
					'//$nsSToinfo="";'."\n".
					'//$nsSTItems=array();'."\n".
					'//$nsSTItems[0][\'Name\']="'.$Lang['TestItemName'].'";'."\n".	
					'//$nsSTItems[0][\'Cnt\']="'.$Lang['TestItemCnt'].'";'."\n".
					'//$nsSTItems[0][\'Value\']="'.$Lang['TestItemValue'].'";'."\n".
					'include_once "'.$CurPath.'/track.php";'."\n".
					'$nsTrack=new nsTrack("sale", '.$SiteId.', "'.$CurPath.'");'."\n".
					'$nsTrack->Order($nsSTcost, $nsSToid, $nsSToinfo, $nsSTItems);'."\n".
					'$nsTrack->DoTrack();';

		}

	}
	
	

	if ($CodePlace==3) {
		if ($CodeType==1) {
			if (!MOD_R) $ActionPath=str_replace("&", "'+nsAmp+'", $ActionPath);
			$ResultCode.="var nsSiteId=$SiteId;\nvar nsTrackPath='$ActionPath$PAmp';\nvar nsTrackMode='event';\nvar nsCode=1;\nvar nsEvent=$ActionId;";
		}
		if ($CodeType==3) {
			$ActionPath=str_replace("&", "&amp;", $ActionPath);
			$ResultCode.="<img src=\"".$ActionPath.$PAmp."eid=$ActionId&amp;code=1\" width=1 height=1 alt=\"\" style=\"display:none\">";
		}
		if ($CodeType==4) {
			$ResultCode.='' .
					'$nsSTEvent='.$ActionId.';' ."\n".
					'$nsSTItem="";'."\n".	
					'include_once "'.$CurPath.'/track.php";'."\n".
					'$nsTrack=new nsTrack("event", '.$SiteId.', "'.$CurPath.'");'."\n".
					'$nsTrack->Event($nsSTEvent, $nsSTItem);'."\n".
					'$nsTrack->DoTrack();';
		}
	}
	

	if ($CodeType==1||$CodeType==2) {
		$ResultCode.="\n</script>\n<script type=\"text/javascript\" src=\"$JsPath\"></script>";
	}

	if (!$nsProduct->WHITE&&$CodeType!=4) $ResultCode.="\n<!-- End of Stuffed Tracker code -->";
	if (!$nsProduct->WHITE&&$CodeType==4) $ResultCode.="\n// End of Stuffed Tracker code //\n?>";	


	if ($CodePlace==1) $TopCodeHelp=$Lang['CodeHelpVis'];
	if ($CodePlace==2) $TopCodeHelp=$Lang['CodeHelpSale'];
	if ($CodePlace==3) $TopCodeHelp=$Lang['CodeHelpAction'];

	if ($CodeType==1) {
		$TopCodeHelp.=$Lang['CodeHelpJs'];
		if ($CodePlace==2) $CodeComment=$Lang['SaleCommentJs'];
	}
	if ($CodeType==3) {
		$TopCodeHelp.=$Lang['CodeHelpHtml'];
		if ($CodePlace==2) $CodeComment=$Lang['SaleCommentHtml'];
	}
	if ($CodeType==4) {
		$TopCodeHelp.=$Lang['CodeHelpPhp'];
		if ($CodePlace==2) $CodeComment=$Lang['SaleCommentPhp'];
	}
}

include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here

/////////////////////////////////////////////
///////// library section


?>