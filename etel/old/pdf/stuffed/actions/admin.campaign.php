<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
//require_once "tracker/class/pagenums.class.php";
require_once SYS."/system/lib/validate.func.php";
require_once SYS."/system/lib/sql.func.php";
require_once SYS."/system/lib/sort.func.php";
require_once SELF."/lib/campaign.func.php";
require_once SELF."/lib/form.func.php";
require_once SELF."/class/report_parent.class.php";
require_once SELF."/class/paid_v2.class.php";
require_once SELF."/lib/delete.func.php";

$nsLang->TplInc("inc/report_headers");
$nsLang->TplInc("inc/menu");

$ProgPath[0]['Name']=$Lang['MCampaign'];
$ProgPath[0]['Url']=getURL("campaign", "CpId=$CpId", "admin");

/////////////////////////////////////////////
///////// prepare any variables
$PageTitle=$Lang['Title'];
if (ValidVar($_GET['EditId'])) $EditId=$_GP['EditId'];
if (ValidVar($_POST['EditId'])) $EditId=$_GP['EditId'];
if (ValidVar($_POST['EditArr'])) $EditArr=$_GP['EditArr'];
if (ValidVar($_GET['DeleteId'])) $DeleteId=$_GP['DeleteId'];
$ParentId=(ValidId($_GP['ParentId']))?$_GP['ParentId']:0;
if (ValidId($_GP['MoveId'])) $MoveId=$_GP['MoveId'];
if (ValidId($_GP['MoveTo'])) $MoveTo=$_GP['MoveTo'];
if (ValidVar($_GET['SortId'])) $SortId=$_GP['SortId'];
if (ValidVar($_GET['SortTo'])) $SortTo=$_GP['SortTo'];
if (ValidVar($_GET['NoComp'])) $NoComp=$_GP['NoComp'];
if (ValidVar($_GET['CpId'])) $CpId=$_GP['CpId'];

$CpId=$CompId;

if (!ValidId($CpId)) $nsProduct->Redir("default");


/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {

	if (ValidId($EditId)&&isset($EditArr)&&is_array($EditArr)) UpdateCampaign($EditId, $EditArr);
	if (isset($EditId)&&$EditId=="new"&&isset($EditArr)&&is_array($EditArr)) CreateCampaign($EditArr, $ParentId);
	if (ValidId($DeleteId)) DeleteCampaign($CpId, $DeleteId);
	if (ValidId($MoveId)&&ValidId($MoveTo)) MoveCampaign($MoveId, $MoveTo);
	if (ValidId($SortId)&&isset($SortTo)&&ValidId($ParentId)) SortTable(PFX."_tracker_campaign", false, $SortId, $SortTo, "PARENT_ID=$ParentId");

}
/////////////////////////////////////////////
///////// display section here


//// campaigns tree
if (!isset($EditId)||(!ValidId($EditId)&&$EditId!="new")) {
	UserColumns(); $SubMenu[0]['Name']=$Lang['AddGroup'];
	$SubMenu[0]['Link']=getURL("campaign", "EditId=new");
	$SubMenu[1]['Name']=$Lang['AddCampNoGrp'];
	$SubMenu[1]['Link']=getURL("sub_camp", "EditId=new&GrpId=0");
	include $nsTemplate->Inc("inc/header");
	include $nsTemplate->Inc("inc/submenu");
	$FirstIter=true;
	$JavaArrCounter=1;
	$NoCamp=true;
	ListCampTree(GetCampTree(0, $CpId));

	$InCampArr=array();
	$Query = "
		SELECT CP.* 
		FROM ".PFX."_tracker_camp_piece CP
			INNER JOIN ".PFX."_tracker_sub_campaign SC
			ON SC.SUB_ID=CP.ID
		WHERE CP.CAMPAIGN_ID=0 AND CP.COMPANY_ID=$CompId
	";
	$Sql= new Query($Query);
	$Sql->ReadSkinConfig();
	while($Row=$Sql->Row()) {
		$Row->_EDITLINK=getURL("sub_camp", "EditId=".$Row->ID);
		$Row->_CODELINK=getURL("campaign_link", "CampId=".$Row->ID);
		$Row->_DELETELINK=getURL("sub_camp", "GrpId=0&DeleteId=".$Row->ID);
		$Row->_TYPE="CAMPAIGN";
		$Row->_STAT_LINK=getURL("paid_constructor", "CampId=".$Row->ID, "report");
		$Row->_STYLE=$Sql->_STYLE;
		$Row->SUB_CAMP=$Row->ID;

		if ($nsUser->Columns->ROI||$nsUser->Columns->CONVERSIONS) {
			$Row->Report = new Paid_v2();
			$Row->Report->CampId=$Row->ID;
			$Row->Report->CpId=$Row->COMPANY_ID;
			$Row->Report->ShowPerClick=true;
			$Row->Report->ShowTotalCost=true;
			$Row->Report->DisableAll();
			if ($nsUser->Columns->ROI) $Row->Report->ShowROI=true;
			if ($nsUser->Columns->CONVERSIONS) $Row->Report->ShowActionConv=true;
			if ($nsUser->Columns->CONVERSIONS) $Row->Report->ShowSaleConv=true;
			$Row->Report->Calculate();
			$Row->CampStat=&$Row->Report->CampStat;
		}

		$InCampArr[]=$Row;
	}

	if (count($InCampArr)>0) {
		$PageTitle2=$Lang['CampNoGrp'];
		include $nsTemplate->Inc("inc/grp_camp");
		include $nsTemplate->Inc("admin.sub_camp_list");
	}
	if ($NoCamp&&count($InCampArr)==0) include $nsTemplate->Inc("inc/no_records");

	include $nsTemplate->Inc("inc/footer");
}

//// edit campaign
if (isset($EditId)&&ValidId($EditId)) {
	$EditCamp=GetCamp($EditId);
	
	if (!isset($EditArr)||!is_array($EditArr)) {
		$EditArr['Name']=stripslashes($EditCamp->NAME);
		$EditArr['Descr']=stripslashes($EditCamp->DESCRIPTION);
		$EditArr['Company']=$EditCamp->COMPANY_ID;
	}
	$EditArr['Name']=htmlspecialchars($EditArr['Name']);
	$EditArr['Descr']=htmlspecialchars($EditArr['Descr']);
	$EditArr['Watch']=(CheckGrpWatch($EditId, $nsUser->UserId()))?1:0;
	$TableCaption=$Lang['CaptionEdit'].stripslashes($EditCamp->NAME);
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("campaign");
	$SubMenu[1]['Name']=$Lang['Stat'];
	$SubMenu[1]['Link']=getURL("paid_constructor", "GrpId=".$EditCamp->ID, "report");
	$SelectComp=false;
	include $nsTemplate->Inc("admin.campaign_edit");
}


//// new campaign
if (isset($EditId)&&$EditId=="new") {
	if (!isset($EditArr)) {
		$EditArr['Name']="";
		$EditArr['Descr']="";
		$EditArr['Company']=0;
	}
	$EditArr['Name']=htmlspecialchars($EditArr['Name']);
	$EditArr['Descr']=htmlspecialchars($EditArr['Descr']);
	if (!isset($ParentId)||!ValidId($ParentId)) $ParentId=0;
	$TableCaption=$Lang['CaptionNew'];
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("campaign");
	if (!ValidId($CompId)) 	{
		$CompArr=GetCompanies();
		$SelectComp=true;
	}
	else $SelectComp=false;
	include $nsTemplate->Inc("admin.campaign_edit");
}








/////////////////////////////////////////////
///////// process functions here

function CreateCampaign(&$Arr, $ParentId=false)
{
	global $Db, $Lang, $nsProduct, $Logs, $CompId;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!$ParentId) $ParentId=0;
	if (!isset($Company)) $Company=$CompId;
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "INSERT INTO ".PFX."_tracker_campaign (PARENT_ID, NAME, DESCRIPTION, COMPANY_ID) VALUES ($ParentId, ?, ?, $Company)";
	$Db->Query($Query, $Name, $Descr);
	ResortTable(PFX."_tracker_campaign", "POSITION", "PARENT_ID=$ParentId");
	$nsProduct->Redir("campaign", "RCrt=1");
}

function UpdateCampaign($Id, &$Arr) 
{
	global $Db, $nsProduct, $Logs, $Lang, $nsUser;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_campaign SET NAME = ?, DESCRIPTION = ? WHERE ID = $Id";
	$Db->Query($Query, $Name, $Descr);
	if (ValidVar($Watch)==1&&!CheckGrpWatch($Id, $nsUser->UserId())) SetGrpWatch($Id, $nsUser->UserId());
	else RemoveGrpWatch($Id, $nsUser->UserId());
	$nsProduct->Redir("campaign", "RUpd=1");
}




function MoveCampaign($MoveId, $MoveTo)
{
	global $Db, $nsProduct;
	$Parent1=$Db->ReturnValue("SELECT PARENT_ID FROM ".PFX."_tracker_campaign WHERE ID = $MoveId");
	if ($MoveTo>0) $Parent2=$Db->ReturnValue("SELECT PARENT_ID FROM ".PFX."_tracker_campaign WHERE ID = $MoveTo");
	else $Parent2=0;
	$Query = "UPDATE ".PFX."_tracker_campaign SET PARENT_ID = $MoveTo WHERE ID = $MoveId";
	$Db->Query($Query);
	ResortTable(PFX."_tracker_campaign", "POSITION", "PARENT_ID=$Parent1");
	ResortTable(PFX."_tracker_campaign", "POSITION", "PARENT_ID=$Parent2");
	$nsProduct->Redir("campaign");
}


function SetGrpWatch($GrpId, $UserId)
{
	global $Db;
	$Query = "INSERT INTO ".PFX."_tracker_watch (GRP_ID, USER_ID) VALUES ($GrpId, $UserId)";
	$Db->Query($Query);
}

function RemoveGrpWatch($GrpId, $UserId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE GRP_ID=$GrpId AND USER_ID=$UserId";
	$Db->Query($Query);
}


function CheckGrpWatch($GrpId, $UserId)
{
	global $Db;
	$Query = "SELECT ID FROM ".PFX."_tracker_watch WHERE GRP_ID=$GrpId AND USER_ID=$UserId";
	return $Db->ReturnValue($Query);
}




/////////////////////////////////////////////
///////// free section

function GetCompanies()
{
	$CompArr=array();
	$Query = "SELECT * FROM ".PFX."_tracker_client ORDER BY NAME ASC";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$CompArr[$Sql->Position]['Name']=htmlspecialchars(stripslashes($Row->NAME));
		$CompArr[$Sql->Position]['Value']=$Row->ID;
	}
	return $CompArr;
}



?>