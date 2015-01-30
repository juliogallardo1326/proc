<?

function GetCamp($Id=false)
{
	if (!$Id||!ValidId($Id)) return false;
	global $Db;
	$Query = "SELECT * FROM ".PFX."_tracker_campaign WHERE ID = $Id";
	$Camp=$Db->Select($Query);
	return $Camp;
}


function GetCampTree($ParentId=0, $CompId=false)
{
	if (!ValidId($ParentId)) return false;
	global $Db, $Lang, $nsUser;
	if ($CompId) $Where=" AND COMPANY_ID=$CompId ";
	else $Where="";
	$Query = "SELECT * FROM ".PFX."_tracker_campaign WHERE PARENT_ID = $ParentId AND COMPANY_ID=$CompId ORDER BY NAME ASC";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$CampArr=array();
	while ($Row = $Sql->Row()) {
		if ($Sql->Position>0) $Row->_UP=true;
		else $Row->_UP=false;
		$Row->_DOWN=true;
		$Row->NAME=stripslashes($Row->NAME);
		$Row->DESCRIPTION=stripslashes($Row->DESCRIPTION);
		$Row->_STYLE=$Sql->_STYLE;
		$SubCnt = $Db->CNT(PFX."_tracker_campaign", "PARENT_ID=".$Row->ID);
		$Row->CHILD_COUNT=($SubCnt>0)?$SubCnt:0;

		$Row->_UPLINK=getURL("campaign", "SortId=".$Row->ID."&SortTo=Up&ParentId=".$Row->PARENT_ID);
		$Row->_DOWNLINK=getURL("campaign", "SortId=".$Row->ID."&SortTo=Down&ParentId=".$Row->PARENT_ID);

		if ($nsUser->Columns->ROI||$nsUser->Columns->CONVERSIONS) {
			$Row->Report = new Paid_v2();
			$Row->Report->GrpId=$Row->ID;
			$Row->Report->CpId=$Row->COMPANY_ID;
			$Row->Report->ShowPerClick=true;
			$Row->Report->ShowTotalCost=true;
			$Row->Report->DisableAll();
			if ($nsUser->Columns->ROI) $Row->Report->ShowROI=true;
			if ($nsUser->Columns->CONVERSIONS) $Row->Report->ShowActionConv=true;
			if ($nsUser->Columns->CONVERSIONS) $Row->Report->ShowSaleConv=true;
			$Row->Report->Calculate();
			//Dump($Row->Report);
			$Row->CampStat=&$Row->Report->CampStat;
		}

		$Row->_MOVE=true;
		$CampArr[$Sql->Position]=$Row;
		$PrevRow=&$CampArr[$Sql->Position];
	}
	$PrevRow->_DOWN=false;
	if (count($CampArr)<1) return false;
	//if (count($CampArr)==1) $PrevRow->_MOVE=false;
	return $CampArr;
}

function ListCampTree($CampArr)
{
	global $CompId, $nsTemplate, $Lang, $NoCamp;
	if ($CampArr) $NoCamp=false;
	if ($CampArr==false) {
		//include $nsTemplate->Inc("inc/no_records");
		return false;
	}
	global $nsButtons, $Lang, $nsTemplate, $nsProduct, $nsUser;
	include $nsTemplate->Inc("admin.campaign");

}

function GetGrpListForMove($ParentId=false, $Level=false)
{
	global $CompId, $Db;
	$GrpArr=array();
	$SubArr=array();
	$SubCnt=0;
	if (!$ParentId) $ParentId=0;
	if (!$Level) $Level=0;
	$Query = "SELECT ID, NAME, PARENT_ID FROM ".PFX."_tracker_campaign WHERE PARENT_ID=$ParentId AND COMPANY_ID=$CompId ORDER BY POSITION ASC";	
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$Row->LEVEL=$Level;
		$GrpArr[]=$Row;
		$SubCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_campaign WHERE PARENT_ID=".$Row->ID);
		if ($SubCnt>0) {
			$SubArr=GetGrpListForMove($Row->ID, $Level+1);
			if (count($SubArr)>0)  $GrpArr=array_merge($GrpArr, $SubArr);
		}
		$SubCnt=0;
	}
	return $GrpArr;
}

function GrpListPath($CurrentGrpId=false)
{
	if (!$CurrentGrpId) return false;
	global $CompId, $Db;
	$GrpArr=array();
	$OverArr=array();
	$Query = "SELECT ID, NAME, PARENT_ID FROM ".PFX."_tracker_campaign WHERE ID=$CurrentGrpId AND COMPANY_ID=$CompId ORDER BY POSITION ASC";	
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$GrpArr[]=$Row;
		if ($Row->PARENT_ID>0) $OverArr=GrpListPath($Row->PARENT_ID);
		if (ValidArr($OverArr)) $GrpArr=array_merge($GrpArr, $OverArr);
	}
	return $GrpArr;
}


?>