<?
/////////////////////////////////////////////
///////// permission check here


/////////////////////////////////////////////
///////// require libraries here

require SYS."/system/lib/validate.func.php";
require SELF."/lib/export_csv.inc.php";

/////////////////////////////////////////////
///////// prepare any variables

$SplitId=(ValidVar($_REQUEST['SplitId']))?$_REQUEST['SplitId']:false;
$CampId=(ValidVar($_REQUEST['CampId']))?$_REQUEST['CampId']:false;
$GLink=(ValidVar($_REQUEST['GLink']))?$_REQUEST['GLink']:false;
$GKey=(ValidVar($_REQUEST['GKey']))?$_REQUEST['GKey']:false;
$GenCode=(ValidVar($_REQUEST['GenCode']))?$_REQUEST['GenCode']:false;
$csv=(ValidVar($_REQUEST['csv']))?$_REQUEST['csv']:false;
$SiteId=ValidVar($_GP['SiteId']);
$UseRedirect=ValidVar($_GP['UseRedirect']);
$SiteId=ValidVar($_GP['SiteId']);

$CpId=$CurrentCompany->ID;
$MenuSection="campaign";
//$SiteNeeded=false;
$KeyArr=array();
$PageTitle=$Lang['Title'];
$nsLang->TplInc("inc/menu");
$ProgPath[0]['Name']=$Lang['MCampaign'];
$ProgPath[0]['Url']=getURL("campaign", "CpId=$CpId", "admin");
$ProgPath[1]['Name']=$PageTitle;
$ProgPath[1]['Url']=getURL("campaign_link", "CpId=$CpId", "admin");

$SitesArr=array();
$Query = "SELECT * FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) $SitesArr[]=$Row;

$nsProduct->SSL_LINK=$Db->ReturnValue("SELECT SSL_LINK FROM ".PFX."_tracker_config WHERE COMPANY_ID=0");
if (!$nsProduct->SSL_LINK) $UseSSL=false;
else $UseSSL=true;

$Settings=GetSettings();
$VarCamp=ValidVar($Settings['All']->VAR_CAMPAIGN, "c");
$VarKw=ValidVar($Settings['All']->VAR_KW, "kw");
$VarKeyword=ValidVar($Settings['All']->VAR_KEYWORD, "k");

$LinkArr=array();
$AllowCSV=false;

/////////////////////////////////////////////
///////// call any process functions


if ($GenCode) GenLink($GLink, $GKey);

/////////////////////////////////////////////
///////// display section here

$CampArr=array();
$Query = "
	SELECT CP.*, C.NAME AS GRP_NAME
	FROM ".PFX."_tracker_camp_piece CP
		INNER JOIN ".PFX."_tracker_sub_campaign SC
			ON SC.SUB_ID=CP.ID
		LEFT JOIN ".PFX."_tracker_campaign C
			ON C.ID=CP.CAMPAIGN_ID
	WHERE CP.COMPANY_ID=$CpId
	ORDER BY C.NAME, CP.NAME
";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	if ($Row->GRP_NAME) $Row->NAME=$Row->GRP_NAME." &raquo; ".$Row->NAME;
	$CampArr[]=$Row;
}

$SplitArr=array();
$Query = "
	SELECT CP.*
	FROM ".PFX."_tracker_camp_piece CP
		INNER JOIN ".PFX."_tracker_split_test ST
			ON ST.SUB_ID=CP.ID
	WHERE CP.COMPANY_ID=$CpId
	ORDER BY CP.NAME
";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$SplitArr[]=$Row;
}

include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


function GenLink($UrlTO=false, $Keyword=false)
{
	global $_GP, $CampId, $Logs, $Lang, $SplitId, $nsProduct, $UseSSL, $LinkArr;
	global $AllowCSV, $csv, $Db, $UseRedirect, $SiteId;
	global $VarCamp, $VarKw, $VarKeyword, $KeyArr;
	$ExportSep=$Lang['SeparatorValue'];
	if(!$CampId&&!$SplitId) {$Logs->Err($Lang['NeedToChoose']); return;}
	if ($CampId&&!$SplitId) {
		if(!$UrlTO) {$Logs->Err($Lang['UrlNeeded']); return;}
	}
	//if ($UrlTO&&!CheckURLTO($UrlTO)) return false;
	//if (!$SplitId) $UseSSL=false;
	if ($UseSSL) {
		$HL=$nsProduct->HL;
		$nsProduct->HL=$nsProduct->SSL_LINK;
	}
	$Keyword=trim($Keyword);
	$CSVArr=array();

	if ($CampId&&$csv) $CampName=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_camp_piece WHERE ID = $CampId");
	if ($SplitId&&$csv) $SplitName=$Db->ReturnValue("SELECT CP.NAME FROM ".PFX."_tracker_camp_piece CP WHERE CP.ID = $SplitId");

	if ($CampId&&!$SplitId) {

		if (!$UseRedirect) {
			$Link="";
			$Link.=$UrlTO;
			if (strpos($Link, "?")) $Link.="&";
			else $Link.="?";
			$Link.="$VarCamp=$CampId";
		}
		else {
			$Link=getURL("campaign", "cid=$CampId&st=$SiteId", "track");
			$Link.="&rurl=".urlencode($UrlTO);
		}

		$Keyword=trim(ToLower($Keyword));
		$KeyArr=array();
		if ($Keyword) {
			$KeyArr=explode("\n", $Keyword);
			$KeyArr=array_unique($KeyArr);
		}
		$Inx=0;
		if (count($KeyArr)>0) {
			for($i=0;$i<count($KeyArr);$i++) {
				if (!isset($KeyArr[$i])||!$KeyArr[$i]) continue;
				$KeyArr[$i]=trim($KeyArr[$i]);
				$LinkVar="";	
				$KeyId=CheckKeyword($KeyArr[$i]);
				if ($KeyId) $LinkVar=$Link."&$VarKeyword=$KeyId";
				$LinkArr[$i]=$LinkVar;
				if ($csv) { $CSVArr[$Inx]['Link']=$LinkVar;
					$CSVArr[$Inx]['Keyword']=$KeyArr[$i];
					$CSVArr[$Inx]['Camp']=$CampName;
				}
				$Inx++;
			}
		}
		else $Logs->Msg($Link);
	}
	if ($CampId&&$SplitId) {
		$GLOBALS['GLink']=false;
		$Id=GetSplitId($SplitId);
		if (!MOD_R) $Link=getURL("split", "s=$Id&$VarCamp=$CampId", "track");
		else {
			$Link=str_replace(".html", "", getURL("split", "", "track"));
			$Link.="/s$Id/$VarCamp$CampId/";
		}

		$Keyword=trim(ToLower($Keyword));
		$KeyArr=array();
		if ($Keyword) {
			$KeyArr=explode("\n", $Keyword);
			$KeyArr=array_unique($KeyArr);
		}
		$Inx=0;
		if (count($KeyArr)>0) {
			for($i=0;$i<count($KeyArr);$i++) {
				if (!isset($KeyArr[$i])||!$KeyArr[$i]) continue;
				$KeyArr[$i]=trim($KeyArr[$i]);
				$Keyword=$KeyArr[$i];
				$LinkVar="";		
				$KeyId=CheckKeyword($Keyword);
				if ($KeyId && !MOD_R) $LinkVar=$Link."&$VarKeyword=$KeyId";
				if ($KeyId && MOD_R) $LinkVar=$Link."$VarKeyword$KeyId/";
				$LinkArr[$i]=$LinkVar;
				if ($csv) { $CSVArr[$Inx]['Link']=$LinkVar;
					$CSVArr[$Inx]['Keyword']=$KeyArr[$i];
					$CSVArr[$Inx]['Split']=$SplitName;
					$CSVArr[$Inx]['Camp']=$CampName;
				}
				$Inx++;
			}
		}
		else $Logs->Msg($Link);
	}
	if ($SplitId&&!$CampId) {
		$GLOBALS['GLink']=false;
		$GLOBALS['GKey']=false;
		$Id=GetSplitId($SplitId);
		if (!MOD_R) $SLink=getURL("split", "s=$Id", "track");
		else {
			$SLink=str_replace(".html", "", getURL("split", "", "track"));
			$SLink.="/s$Id/";
		}
		$Logs->Msg($SLink);
	}

	if (ValidArr($KeyArr)&&count($KeyArr)>1) $AllowCSV=true;

	if ($UseSSL) {
		$nsProduct->HL=$HL;
	}
	
	if ($csv) {
		$NamesArr['Keyword']=$Lang['ColumnKey'];
		$NamesArr['Link']=$Lang['ColumnLink'];
		if ($SplitId) $NamesArr['Split']=$Lang['ColumnSplit'];
		if ($CampId) $NamesArr['Camp']=$Lang['ColumnCamp'];
		send_file_to_client("links.csv", ExportCsv($CSVArr, $ExportSep, $NamesArr));
	}
}

function GetSplitId($SubId)
{
	global $Db;
	$Query = "SELECT ID FROM ".PFX."_tracker_split_test WHERE SUB_ID=$SubId";
	return $Db->ReturnValue($Query);
}

function CheckURLTO($Url)
{
	global $Logs, $Lang, $Db, $CpId, $SiteNeeded, $SiteId, $SitesArr;
	$Arr=@parse_url($Url);
	if (!$Arr||!ValidVar($Arr['host'])) {$Logs->Err($Lang['UnableParseUrl']); return false;}
	$Query = "
		SELECT COUNT(S.ID)
			FROM ".PFX."_tracker_site_host SH
				INNER JOIN ".PFX."_tracker_site S
					ON S.ID=SH.SITE_ID
			WHERE S.COMPANY_ID='$CpId' AND SH.HOST = '".escape_string(ToLower($Arr['host']))."'
	";
	$CheckId=$Db->ReturnValue($Query);
	if ($CheckId==1) return true;
	if ($CheckId!=1&&!$SiteId&&count($SitesArr)>1) {
		$SiteNeeded=true;
		$Logs->Alert($Lang['SiteNeeded']); 
		return false;
	}
	if (count($SitesArr) == 1) $SiteId=$SitesArr[0]->ID;
	
	if ($SiteId) {
		$Query = "INSERT INTO ".PFX."_tracker_site_host (HOST, SITE_ID) VALUES (?, $SiteId)";
		$Db->Query($Query, ToLower($Arr['host']));
	}
	
	return true;
}

function CheckKeyword($Keyword=false)
{
	if (!$Keyword) return false;
	global $Db;
	$Keyword=addslashes($Keyword);
	$Query = "SELECT ID FROM ".PFX."_tracker_keyword WHERE KEYWORD = '$Keyword'";
	$CheckId=$Db->ReturnValue($Query);
	if (ValidId($CheckId)) return $CheckId;

	$Query = "INSERT INTO ".PFX."_tracker_keyword (KEYWORD) VALUES ('$Keyword')";
	$Db->Query($Query);
	return $Db->LastInsertId;
}



/////////////////////////////////////////////
///////// library section


?>