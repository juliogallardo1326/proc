<?/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";
require_once SYS."/system/lib/sort.func.php";
require_once SELF."/lib/store.func.php";
require_once SELF."/lib/campaign.func.php";
require_once SELF."/lib/delete.func.php";
$nsLang->TplInc("inc/menu");


/////////////////////////////////////////////
///////// prepare any variables
$EditId=(ValidVar($_GP['EditId']))? $_GP['EditId']:false;
$DeleteId=(ValidVar($_GP['DeleteId']))? $_GP['DeleteId']:false;
$EditArr=(ValidVar($_GP['EditArr']))? $_GP['EditArr']:false;
$GrpId=(ValidVar($_GP['GrpId']))? $_GP['GrpId']:false;
$EditPage=(ValidVar($_GP['EditPage']))? $_GP['EditPage']:false;
$AddPage=(ValidVar($_GP['AddPage']))? $_GP['AddPage']:false;
$DeletePage=(ValidVar($_GP['DeletePage']))? $_GP['DeletePage']:false;
$NewPage=(ValidVar($_GP['NewPage']))? $_GP['NewPage']:false;
$MoveCampTo=(ValidVar($_GP['MoveCampTo']))? $_GP['MoveCampTo']:false;
$AddToSite=ValidVar($_GP['AddToSite']);

if (!ValidId($CompId)) $nsProduct->Redir("default", "", "admin");
$MenuSection="split_test";

if (ValidId($EditId)) $SplitTest=GetSplit($EditId);
if (!ValidId($GrpId)&&ValidId($SplitTest->CAMPAIGN_ID)) $GrpId=$SplitTest->CAMPAIGN_ID;
$PageTitle=ValidId($SplitTest->ID)?stripslashes($SplitTest->NAME):$Lang['SplitTest'];

$SitesArr=array();
$Query = "SELECT ID, HOST, USE_HOSTS FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CompId";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) $SitesArr[]=$Row;
$SelectNeeded=false;

$ProgPath[0]['Name']=$Lang['MSplits'];
$ProgPath[0]['Url']=getURL("split_list", "CpId=$CpId", "admin");
if (ValidId($SplitTest->ID)) {
	$ProgPath[1]['Name']=stripslashes($SplitTest->NAME);
	$ProgPath[1]['Url']=$nsProduct->SelfAction("EditId=$EditId");
}

if (ValidId($SplitTest->CAMPAIGN_ID)) $MoveArr=GetGrpListForMove();

/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {

	if (ValidId($SplitTest->ID)&&ValidId($MoveCampTo)&&$MoveCampTo!=$SplitTest->CAMPAIGN_ID) MoveSubCamp($EditId, $MoveCampTo);
	if (ValidId($EditId)&&ValidArr($EditArr)) UpdateSplitTest($EditId, $EditArr);
	if (ValidVar($EditId)=="new"&&ValidArr($EditArr)) CreateNewSplit($EditArr);
	if (ValidId($DeleteId)) DeleteSplit($CpId, $DeleteId);
	if (ValidId($AddPage)&&ValidId($EditId)) AddPageToSplit($AddPage);
	if (ValidId($DeletePage)&&ValidId($EditId)) DelPageFromSplit($DeletePage);

}
/////////////////////////////////////////////
///////// display section here

if (ValidVar($NewPage)&&ValidId($EditId)) {
	$PageSiteId=0;
	$CompanySiteCnt=0;
	$SiteIdCnt=0;
	$CheckArr=parse_url($NewPage);
	if (!ValidVar($CheckArr['scheme'])) $NewPage="http://".$NewPage;
	$NewPageArr=PreparePathAddr($NewPage);
	if ($NewPageArr===false) $Logs->Err(str_replace("{ADDR}", $NewPage, $Lang['UnableToParse']));
	else {
		if (strpos($NewPageArr['query'], "ns_skip")!==false) {
			$NewPageArr['query']=preg_replace("/ns_skip=[^&]*/", "", $NewPageArr['query']);
		}
		
		if (!$AddToSite) {
			$Query = "
					SELECT 
						COUNT( DISTINCT SH.SITE_ID) 
					FROM ".PFX."_tracker_site_host SH
						INNER JOIN ".PFX."_tracker_site S 
							ON S.ID=SH.SITE_ID
					WHERE SH.HOST = '".escape_string($NewPageArr['host'])."' AND S.COMPANY_ID=$CompId
				";
			$SiteIdCnt=$Db->ReturnValue($Query);
			$CompanySiteCnt=$Db->ReturnValue("SELECT COUNT(ID) FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CompId");
			$PageSiteId=$Db->ReturnValue("SELECT SITE_ID FROM ".PFX."_tracker_site_host WHERE HOST = '".escape_string($NewPageArr['host'])."'");
		}
		else {
			$Query = "SELECT ID FROM ".PFX."_tracker_site_host WHERE HOST = '".escape_string($NewPageArr['host'])."' AND SITE_ID=$AddToSite";
			$CheckId=$Db->ReturnValue($Query);
			if (!$CheckId) {
				$Query = "INSERT INTO ".PFX."_tracker_site_host (HOST, ENABLED, SITE_ID) VALUES (?, '1', $AddToSite)";
				$Db->Query($Query, $NewPageArr['host']);
				$PageSiteId=$AddToSite;
			}
			else $PageSiteId=$AddToSite;
		}
		
		if ($SiteIdCnt>1 || ($CompanySiteCnt && !$PageSiteId)) $SelectNeeded=true;
		if ($CompanySiteCnt==1 && !$PageSiteId) {
			$SelectNeeded=false;
			$Query = "SELECT ID FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CompId";
			$SiteId = $Db->ReturnValue($Query);
			$Query = "INSERT INTO ".PFX."_tracker_site_host (HOST, ENABLED, SITE_ID) VALUES (?, '1', $SiteId)";
			$Db->Query($Query, $NewPageArr['host']);
			$PageSiteId=$SiteId;
		}
		
		if ($PageSiteId&&!$SelectNeeded) {
			$PageId=GetPageId($NewPageArr, $PageSiteId);
			$QueryId=(ValidVar($NewPageArr['query']))?GetQueryId($NewPageArr['query']):0;
			if (ValidId($PageId)&&$PageId>0) AddPageToSplit($PageId, $QueryId, $NewPage);
			else $Logs->Err(str_replace("{ADDR}", $NewPage, $Lang['UnableAddPage']));
			$NewPage="";
		}
		elseif (!$PageSiteId&&!$SelectNeeded) $Logs->Err(str_replace("{HOST}",$NewPageArr['host'], $Lang['InvalidHost']));
	}
}

//// new split
if (ValidVar($EditId)=="new") {
	if (!ValidArr($EditArr)) {
		$EditArr['Name']="";
		$EditArr['Descr']="";
		$EditArr['Rem']=0;
	}
	$EditArr['Name']=htmlspecialchars(stripslashes($EditArr['Name']));
	$EditArr['Descr']=htmlspecialchars(stripslashes($EditArr['Descr']));	
	$TableCaption=$Lang['CaptionNew'];
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("split_list");
	include $nsTemplate->Inc("admin.split_test");
}


//// edit split

if (ValidId($EditId)&&!$EditPage) {

	$Query = "SELECT SSL_LINK FROM ".PFX."_tracker_config WHERE COMPANY_ID=0";
	$SSLink=$Db->ReturnValue($Query);
	if ($SSLink) {
		$HL=$nsProduct->HL;
		$nsProduct->HL=$SSLink;
	}
	
	if (!MOD_R) $SLink = getURL("split", "s=".$SplitTest->SPLIT_ID, "track");
	else {
		$SLink = str_replace(".html", "", getURL("split", "", "track"));
		$SLink .= "/s".$SplitTest->SPLIT_ID."/";
	}
	$Logs->Msg($SLink);
	
	if ($SSLink) {
		$nsProduct->HL=$HL;
	}

	if (!ValidArr($EditArr)) {
		$EditArr['Name']=$SplitTest->NAME;
		$EditArr['Descr']=$SplitTest->DESCRIPTION;
		$EditArr['Rem']=$SplitTest->REMEMBER_PAGE;
	}
	$EditArr['Name']=htmlspecialchars(stripslashes($EditArr['Name']));
	$EditArr['Descr']=htmlspecialchars(stripslashes($EditArr['Descr']));
	$EditArr['Watch']=(CheckSubWatch($EditId, $nsUser->UserId()))?1:0;

	$TableCaption=$Lang['CaptionEdit'].stripslashes($SplitTest->NAME);
	$PagesArr=GetSplitPages($SplitTest->SPLIT_ID);

	if (ValidId($GrpId)&&$GrpId>0) {
		$SubMenu[0]['Name']=$Lang['BackToGrp'];
		$SubMenu[0]['Link']=getURL("incampaign", "CampId=$GrpId");
		$SubMenu[1]['Name']=$Lang['MoveToSplitList'];
		$SubMenu[1]['Link']=getURL("split_list");
	}
	else {
		//$SubMenu[1]['Name']=$Lang['BackToGrp'];
		//$SubMenu[1]['Link']=getURL("incampaign", "CampId=$GrpId");
		$SubMenu[0]['Name']=$Lang['MoveToSplitList'];
		$SubMenu[0]['Link']=getURL("split_list");
	}

	$SubMenu[2]['Name']=$Lang['ChoosePage'];
	$SubMenu[2]['Link']=getURL("split_test", "EditPage=new&EditId=$EditId");
	$SubMenu[3]['Name']=$Lang['SplitStat'];
	$SubMenu[3]['Link']=getURL("split_test", "SplitId=$EditId", "report");

	include $nsTemplate->Inc("admin.split_test");
}

//// new page
if (ValidVar($EditPage)=="new") {
	$TableCaption=$Lang['CaptionNew'];
	$SubMenu[0]['Name']=$Lang['BackToEdit'];
	$SubMenu[0]['Link']=getURL("split_test", "EditId=$EditId&GrpId=$GrpId");

	$InSplit=array();
	$Query = "SELECT PAGE_ID FROM ".PFX."_tracker_split_page WHERE SPLIT_ID=".$SplitTest->SPLIT_ID;
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $InSplit[]=$Row->PAGE_ID;

	$Query = "
		SELECT TC.COMPANY_ID
			FROM ".PFX."_tracker_split_test TST
			INNER JOIN ".PFX."_tracker_camp_piece TCP
				ON TCP.ID=TST.SUB_ID
			INNER JOIN ".PFX."_tracker_campaign TC 
				ON TC.ID=TCP.CAMPAIGN_ID
			WHERE TST.ID=".$SplitTest->SPLIT_ID."
	";
	$CompanyId=$Db->ReturnValue($Query);

	$Query = "
		SELECT TS.HOST, TS.ID AS HOST_ID, SP.*
			FROM  ".PFX."_tracker_site TS
			INNER JOIN ".PFX."_tracker_site_page SP
				ON SP.SITE_ID = TS.ID
			WHERE TS.COMPANY_ID=$CpId
		ORDER BY SP.SITE_ID, SP.PATH
	";
	$Sql = new Query($Query);
	$PagesTree=array();
	while ($Row=$Sql->Row()) {
		if (in_array($Row->ID, $InSplit)) $Arr['no_link']=1;
		else $Arr['no_link']=0;
		$Arr['path']=$Row->PATH;
		$Arr['name']=$Row->NAME;
		$Arr['id']=$Row->ID;
		$Arr['host_id']=$Row->HOST_ID;
		$PagesTree[$Row->HOST][]=$Arr;
	}

	include $nsTemplate->Inc("inc/header");
	include $nsTemplate->Inc("inc/submenu");
	foreach($PagesTree as $Host=>$Pages) {
		include $nsTemplate->Inc("admin.split_page");
	}
	include $nsTemplate->Inc("inc/footer");
}



/////////////////////////////////////////////
///////// process functions here

function CreateNewSplit(&$Arr)
{
	global $Db, $Logs, $nsProduct, $Lang, $CurrentCompany, $GrpId;
	if (!$GrpId) $GrpId=0;
	extract($Arr);
	if (!ValidVar($Rem)) $Rem=0;
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$CompId=$CurrentCompany->ID;
	$Query = "INSERT INTO ".PFX."_tracker_camp_piece (CAMPAIGN_ID, NAME, DESCRIPTION, COMPANY_ID) VALUES ($GrpId, ?, ?, $CompId)";
	$Db->Query($Query, $Name, $Descr);
	$NewId=$Db->LastInsertId;
	$Query = "INSERT INTO ".PFX."_tracker_split_test (SUB_ID, COMPANY_ID, REMEMBER_PAGE) VALUES ($NewId, $CompId, '$Rem')";
	$Db->Query($Query);

	SaveSplitToFile($NewId, "split_test.nodb");
	$nsProduct->Redir("split_test", "RCrt=1&EditId=$NewId&GrpId=$GrpId");
}


function UpdateSplitTest($Id, &$Arr)
{
	global $Db, $Logs, $nsProduct, $Lang, $nsUser;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!ValidVar($Rem)) $Rem=0;
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_camp_piece SET NAME = ?, DESCRIPTION = ? WHERE ID = $Id";
	$Db->Query($Query, $Name, $Descr);
	$Query = "UPDATE ".PFX."_tracker_split_test SET REMEMBER_PAGE = '$Rem' WHERE SUB_ID=$Id";
	$Db->Query($Query);
	if (ValidVar($Watch)==1&&!CheckSubWatch($Id, $nsUser->UserId())) SetSubWatch($Id, $nsUser->UserId());
	else RemoveSubWatch($Id, $nsUser->UserId());
	
	$WrRes=true;
	$WrRes=SaveSplitToFile($Id, "split_test.nodb");
	//if (!$WrRes) $Logs->Err($Lang['WriteErr']);
	//if ($WrRes) 
	$nsProduct->Redir("split_test", "RUpd=1&EditId=$Id");
}


function MoveSubCamp($CampId, $MoveTo) {
	global $Db, $nsProduct;
	$Query = "UPDATE ".PFX."_tracker_camp_piece SET CAMPAIGN_ID=$MoveTo WHERE ID=$CampId";
	$Db->Query($Query);
	$nsProduct->Redir("split_test", "RUpd=1&EditId=$CampId");
}

function AddPageToSplit($Id, $QueryId=0, $FullPath=false)
{
	global $Db, $SplitTest, $Lang, $Logs;
	$Query = "INSERT INTO ".PFX."_tracker_split_page (SPLIT_ID, PAGE_ID, QUERY_ID, FULL_PATH) VALUES (".$SplitTest->SPLIT_ID.", $Id, $QueryId, ?)";
	$Db->Query($Query, $FullPath);
	$WrRes=true;
	$WrRes=SaveSplitToFile(false, "split_test.nodb", $SplitTest->SPLIT_ID);
	$Logs->Msg($Lang['RecordUpdated']);
	//if (!$WrRes) $Logs->Err($Lang['WriteErr']);
}

function DelPageFromSplit($Id)
{
	global $Db, $SplitTest, $Lang, $Logs;
	$Query = "DELETE FROM ".PFX."_tracker_split_page WHERE ID=$Id";
	$Db->Query($Query);
	$WrRes=true;
	$WrRes=SaveSplitToFile(false, "split_test.nodb", $SplitTest->SPLIT_ID);
	$Logs->Msg($Lang['RecordDeleted']);
	//if (!$WrRes) $Logs->Err($Lang['WriteErr']);
}




function SetSubWatch($GrpId, $UserId)
{
	global $Db;
	$Query = "INSERT INTO ".PFX."_tracker_watch (SUB_ID, USER_ID) VALUES ($GrpId, $UserId)";
	$Db->Query($Query);
}

function RemoveSubWatch($GrpId, $UserId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE SUB_ID=$GrpId AND USER_ID=$UserId";
	$Db->Query($Query);
}


function CheckSubWatch($GrpId, $UserId)
{
	global $Db;
	$Query = "SELECT ID FROM ".PFX."_tracker_watch WHERE SUB_ID=$GrpId AND USER_ID=$UserId";
	return $Db->ReturnValue($Query);
}



/////////////////////////////////////////////
///////// free section





function GetPageId($PathArr=false, $StId=false)
{
	global $Db;
	$Path=$PathArr['path'];
	$Query = "SELECT ID FROM ".PFX."_tracker_site_page WHERE PATH = '$Path' AND SITE_ID=$StId";
	$CheckId=$Db->ReturnValue($Query);
	if (ValidId($CheckId)) return $CheckId;
	$Query = "INSERT INTO ".PFX."_tracker_site_page (SITE_ID, PATH) VALUES ($StId, '$Path')";
	$Db->Query($Query);
	return $Db->LastInsertId;
}

function GetSplit($Id)
{
	global $Db;
	$Query = "
		SELECT TCP.*, TST.ID AS SPLIT_ID, TST.REMEMBER_PAGE
			FROM ".PFX."_tracker_camp_piece TCP
				INNER JOIN ".PFX."_tracker_split_test TST
					ON TST.SUB_ID=TCP.ID
			WHERE TCP.ID = $Id
	";
	$SplitTest=$Db->Select($Query);
	return $SplitTest;
}




function GetSplitPages($Id)
{
	$PagesArr=array();
	$Query = "
		SELECT TS.*, SI.HOST, TQ.QUERY_STRING, TSP.ID AS TSP_ID, 
			TSP.FULL_PATH
			FROM ".PFX."_tracker_split_page TSP
			INNER JOIN ".PFX."_tracker_site_page TS
				ON TS.ID=TSP.PAGE_ID
			INNER JOIN ".PFX."_tracker_site SI 
				ON SI.ID = TS.SITE_ID
			LEFT JOIN ".PFX."_tracker_query TQ
				ON TQ.ID=TSP.QUERY_ID
			WHERE SPLIT_ID=$Id
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row())	{
		$Row->SCHEME="";
		if ($Row->FULL_PATH) {
			$Check=parse_url($Row->FULL_PATH);
			$Row->SCHEME=$Check['scheme']."://";
			$Row->HOST=$Check['host'];
		}
		$Row->PATH=$Row->SCHEME.$Row->HOST."<br>".$Row->PATH;
		if($Row->QUERY_STRING) $Row->PATH.="?".$Row->QUERY_STRING;
		$Row->_STYLE=$Sql->_STYLE;
		$PagesArr[]=$Row;
	}
	if (count($PagesArr)>0) return $PagesArr;
	else return false;
}



function PreparePathAddr($Addr)
{
	$Arr=@parse_url(urldecode(urldecode($Addr)));
	if (!isset($Arr['path'])) $Arr['path']="";
	$Arr['path']=ereg_replace("/+", "/", $Arr['path']);
	if ($Arr['path']=="") $Arr['path']="/";
	if (!ValidVar($Arr['host'])) return false;
	$Arr['host']=ToLower($Arr['host']);
	if (!isset($Arr['query'])) $Arr['query']="";
	return $Arr;
}


function GetQueryId($Qr=false)
{
	if (!$Qr) return 0;
	global $Db;	
	$Qr=escape_string($Qr);
	$Query = "SELECT ID FROM ".PFX."_tracker_query WHERE MD5_SEARCH=MD5('$Qr')";
	$CheckId=$Db->ReturnValue($Query);
	if (ValidId($CheckId)) return $CheckId;
	$Query ="INSERT INTO ".PFX."_tracker_query (QUERY_STRING, MD5_SEARCH) VALUES ('$Qr', MD5('$Qr'))";
	$Db->Query($Query);
	return (ValidId($Db->LastInsertId))?$Db->LastInsertId:0;
}


?>