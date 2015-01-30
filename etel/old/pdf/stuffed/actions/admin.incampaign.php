<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SELF."/lib/campaign.func.php";
require_once SYS."/system/lib/sort.func.php";
require_once SYS."/system/lib/validate.func.php";
require_once SYS."/system/lib/sql.func.php";
require_once SELF."/class/report_parent.class.php";
require_once SELF."/class/paid_v2.class.php";
require_once SELF."/class/split_v2.class.php";

$nsLang->TplInc("inc/report_headers");
$nsLang->TplInc("inc/menu");

$ProgPath[0]['Name']=$Lang['MCampaign'];
$ProgPath[0]['Url']=getURL("campaign", "CpId=$CpId", "admin");


/////////////////////////////////////////////
///////// prepare any variables
if (isset($_GET['CampId'])) $CampId=$_GET['CampId'];
if (!ValidId($CampId)) $nsProduct->Redir("campaign");
$Camp=GetCamp($CampId);
$PageTitle=stripslashes($Camp->NAME);

$ProgPath[1]['Name']=$Camp->NAME;
$ProgPath[1]['Url']=getURL("incampaign", "CampId=$CampId", "admin");


$Get="CampId=$CampId&";
if (isset($_GET['SortId'])) $SortId=$_GET['SortId'];
if (isset($_GET['SortTo'])) $SortTo=$_GET['SortTo'];
if (isset($_GET['DeleteId'])) $DeleteId=$_GET['DeleteId'];
UserColumns(); /////////////////////////////////////////////
///////// call any process functions
if (!$nsUser->DEMO) {
	if (ValidId($SortId)&&isset($SortTo)) SortTable(PFX."_tracker_camp_piece", false, $SortId, $SortTo, "CAMPAIGN_ID=$CampId");
}

$PathArr=false;
$PathArr=GrpListPath($CampId);
if (count($PathArr)>1)  $PathArr=array_reverse($PathArr);
else $PathArr=false;

/////////////////////////////////////////////
///////// display section here

$SubMenu[0]['Name']=$Lang['BackToCamp'];
$SubMenu[0]['Link']=getURL("campaign");
$SubMenu[1]['Name']=$Lang['AddNewCamp'];
$SubMenu[1]['Link']=getURL("sub_camp", "EditId=new&GrpId=$CampId");
$SubMenu[2]['Name']=$Lang['AddNewSplit'];
$SubMenu[2]['Link']=getURL("split_test", "EditId=new&GrpId=$CampId");
$InCampArr=GetPiecesList();


include $nsTemplate->Inc("admin.incampaign");

/////////////////////////////////////////////
///////// process functions here



/////////////////////////////////////////////
///////// free section

function GetPiecesList()
{
	global $Get, $CampId, $Lang, $nsUser;
	$InCampArr=array();
	$Query = "
		SELECT 
			TCP.*,
			TSC.ID AS SUB_CAMP,
			TST.ID AS SPLIT_TEST
			FROM ".PFX."_tracker_camp_piece TCP
				LEFT JOIN ".PFX."_tracker_sub_campaign TSC
					ON TSC.SUB_ID=TCP.ID
				LEFT JOIN ".PFX."_tracker_split_test TST
					ON TST.SUB_ID=TCP.ID
			WHERE CAMPAIGN_ID=$CampId 
			ORDER BY TCP.NAME
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$i=0;
	while ($Row=$Sql->Row()) {
		$Row->NAME=stripslashes($Row->NAME);
		$Row->DESCRIPTION=stripslashes($Row->DESCRIPTION);
		if ($Row->SUB_CAMP) {
			$Row->_EDITLINK=getURL("sub_camp", "EditId=".$Row->ID);
			$Row->_CODELINK=getURL("campaign_link", "CampId=".$Row->ID);
			$Row->_DELETELINK=getURL("sub_camp", "GrpId=$CampId&DeleteId=".$Row->ID);
			$Row->_TYPE=$Lang['Campaign'];
			$Row->_STAT_LINK=getURL("paid_constructor", "CampId=".$Row->ID, "report");

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
		if ($Row->SPLIT_TEST) {
			$Row->_EDITLINK=getURL("split_test", "EditId=".$Row->ID);
			$Row->_CODELINK=getURL("campaign_link", "SplitId=".$Row->ID);
			$Row->_DELETELINK=getURL("split_test", "GrpId=$CampId&DeleteId=".$Row->ID);
			$Row->_TYPE=$Lang['SplitTest'];
			$Row->_STAT_LINK=getURL("split_test", "SplitId=".$Row->ID, "report");

			$Row->Report = new SplitStat_v2();
			$Row->Report->SplitId=$Row->ID;
			$Row->Report->CpId=$Row->COMPANY_ID;
			$Row->Report->DisableAll();
			if ($nsUser->Columns->CLICKS) $Row->Report->ShowVisitors=true;
			if ($nsUser->Columns->CONVERSIONS) $Row->Report->ShowActionConv=true;
			if ($nsUser->Columns->CONVERSIONS) $Row->Report->ShowSaleConv=true;

			$Row->Report->Calculate();
			$Row->SplitStat=&$Row->Report->SplitStat;
		}
		if (!$Row->SUB_CAMP&&!$Row->SPLIT_TEST) continue;
		$Row->_STYLE=$Sql->_STYLE;
		$InCampArr[$i]=$Row;
		$PrevRow=&$InCampArr[$i];
		$i++;
	}
	$PrevRow->_DOWN=false;
	if (count($InCampArr)>0) return $InCampArr;
	else return false;
}



?>