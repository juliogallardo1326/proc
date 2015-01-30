<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";

require_once SELF."/class/report_parent.class.php";
require_once SELF."/class/split_v2.class.php";

$nsLang->TplInc("inc/report_headers");



/////////////////////////////////////////////
///////// prepare any variables
$DeleteId=(ValidId($_GET['DeleteId']))?$_GET['DeleteId']:false;

$ProgPath[0]['Name']=$Lang['Title'];
$ProgPath[0]['Url']=$nsProduct->SelfAction("CpId=$CpId");

$PageTitle=stripslashes($CurrentCompany->NAME)." : ".$Lang['Title'];
if (!ValidId($CompId)) $nsProduct->Redir("default", "", "admin");
$MenuSection="split_test";
UserColumns(); $CpId=$CurrentCompany->ID;

/////////////////////////////////////////////
///////// call any process functions
if (ValidId($DeleteId)) DeleteCampPiece($DeleteId);

/////////////////////////////////////////////
///////// display section here

$SubMenu[0]['Name']=$Lang['AddNewSplit'];
$SubMenu[0]['Link']=getURL("split_test", "EditId=new");
$InCampArr=GetPiecesList();

include $nsTemplate->Inc("admin.split_list");

/////////////////////////////////////////////
///////// process functions here



/////////////////////////////////////////////
///////// free section

function GetPiecesList()
{
	global $Get, $CurrentCompany, $Lang, $nsUser;
	$InCampArr=array();
	$Query = "
		SELECT 
			TCP.*,
			TST.ID AS SPLIT_TEST
			FROM ".PFX."_tracker_camp_piece TCP
			INNER JOIN ".PFX."_tracker_split_test TST
				ON TST.SUB_ID=TCP.ID
			WHERE TST.COMPANY_ID=".$CurrentCompany->ID."
			ORDER BY TCP.NAME
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		if (!$Row->COMPANY_ID) continue;
		$Row->NAME=stripslashes($Row->NAME);
		$Row->DESCRIPTION=stripslashes($Row->DESCRIPTION);
		$Row->_EDITLINK=getURL("split_test", "EditId=".$Row->ID);
		$Row->_CODELINK=getURL("campaign_link", "SplitId=".$Row->ID);
		$Row->_DELETELINK=getURL("split_test", "DeleteId=".$Row->ID);
		//$Row->_TYPE=$Lang['SplitTest'];
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
		$Row->_STYLE=$Sql->_STYLE;
		$InCampArr[$Sql->Position]=$Row;
		$PrevRow=&$InCampArr[$Sql->Position];
	}
	if (count($InCampArr)>0) return $InCampArr;
	else return false;
}



?>