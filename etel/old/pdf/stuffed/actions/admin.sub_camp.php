<?/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";
require_once SYS."/system/lib/sort.func.php";
require_once SELF."/lib/campaign.func.php";
require_once SELF."/lib/delete.func.php";

/////////////////////////////////////////////
///////// prepare any variables
if (isset($_GP['EditId'])) $EditId=$_GP['EditId'];
if (isset($_GP['DeleteId'])) $DeleteId=$_GP['DeleteId'];
if (isset($_GP['EditArr'])) $EditArr=$_GP['EditArr'];
if (isset($_GP['GrpId'])) $GrpId=$_GP['GrpId'];
if (isset($_GP['DeleteId'])) $DeleteId=$_GP['DeleteId'];
if (isset($_GP['GenCode'])) $GenCode=$_GP['GenCode'];
if (isset($_GP['EditCost'])) $EditCost=$_GP['EditCost'];
if (isset($_GP['EditCostId'])) $EditCostId=$_GP['EditCostId'];
if (isset($_GP['SumThis'])) $SumThis=$_GP['SumThis'];
if (isset($_GP['UpdateSum'])) $UpdateSum=$_GP['UpdateSum'];
if (isset($_GP['DelCostId'])) $DelCostId=$_GP['DelCostId'];
$MoveCampTo=(ValidVar($_GP['MoveCampTo']))? $_GP['MoveCampTo']:false;
$UpdateMode=(ValidVar($_GP['UpdateMode']))? $_GP['UpdateMode']:false;

if (ValidId($EditId)) $SubCamp=GetPiece($EditId);
$PageTitle=isset($SubCamp)?stripslashes($SubCamp->NAME):$Lang['Campaign'];
if (!ValidId($GrpId))$GrpId=$SubCamp->CAMPAIGN_ID;

$MoveArr=GetGrpListForMove();

$ShowCosts=false;

$nsLang->TplInc("inc/menu");

$ProgPath[0]['Name']=$Lang['MCampaign'];
$ProgPath[0]['Url']=getURL("campaign", "CpId=$CpId", "admin");
if (ValidId($EditId)) {
	$ProgPath[1]['Name']=$SubCamp->NAME;
	$ProgPath[1]['Url']=$nsProduct->SelfAction("EditId=$EditId");
}

$MaxYear=date("Y")+5;

/////////////////////////////////////////////
///////// call any process functions
if (!$nsUser->DEMO) {

	if (ValidId($SubCamp->ID)&&ValidVar($MoveCampTo)&&$MoveCampTo!=$SubCamp->CAMPAIGN_ID) MoveSubCamp($EditId, $MoveCampTo);
	if (ValidId($EditId)&&isset($EditArr)&&is_array($EditArr)) UpdateSubCampaign($EditId, $EditArr);
	if (isset($EditId)&&$EditId=="new"&&isset($EditArr)&&ValidId($GrpId)) CreateNewSubCampaign($GrpId, $EditArr);
	if (ValidId($DeleteId)) DeleteSubCampaign($CpId, $DeleteId);
	if (isset($GenCode)&&$GenCode==1) GenLink();
	if (ValidId($EditCostId)&&ValidArr($EditCost)) UpdateCost($EditCostId, $EditCost);
	if (ValidVar($EditCostId)=="new"&&ValidArr($EditCost)) CreateCost($EditCost);
	if (ValidVar($UpdateSum)==1) UpdateSumThis($SumThis);
	if (ValidId($DelCostId)) DeleteCost($DelCostId);

}
/////////////////////////////////////////////
///////// display section here

if (!ValidArr($EditCost)) $EditCost=array();

//// new sub campaign
if (isset($EditId)&&$EditId=="new"&&ValidId($GrpId)) {
	if (!isset($EditArr)) {
		$EditArr['Name']="";
		$EditArr['Descr']="";
		$EditArr['Type']=0;
		$EditArr['SrcId']="";
	}
	$EditArr['Name']=htmlspecialchars(stripslashes($EditArr['Name']));
	$EditArr['Descr']=htmlspecialchars(stripslashes($EditArr['Descr']));	
	$TableCaption=$Lang['CaptionNew'];
	$SubMenu[0]['Name']=$Lang['BackToList'];
	if ($GrpId>0) $SubMenu[0]['Link']=getURL("incampaign", "CampId=$GrpId");
	else $SubMenu[0]['Link']=getURL("campaign");
	$ShowCosts=false;
	include $nsTemplate->Inc("admin.sub_edit");
}

//// edit sub campaign
if (ValidId($EditId)) {
	if (!isset($EditArr)) {
		$EditArr['Name']=$SubCamp->NAME;
		$EditArr['Descr']=$SubCamp->DESCRIPTION;
		$EditArr['Type']=$SubCamp->TYPE;
		$EditArr['SrcId']=$SubCamp->SRC_ID;
	}
	$EditArr['Name']=htmlspecialchars(stripslashes($EditArr['Name']));
	$EditArr['Descr']=htmlspecialchars(stripslashes($EditArr['Descr']));
	$EditArr['SrcId']=htmlspecialchars(stripslashes($EditArr['SrcId']));
	$TableCaption=$Lang['CaptionEdit'].stripslashes($SubCamp->NAME);
	$EditArr['Watch']=(CheckSubWatch($EditId, $nsUser->UserId()))?1:0;

	$SubMenu[0]['Name']=$Lang['BackToList'];
	if ($GrpId>0) $SubMenu[0]['Link']=getURL("incampaign", "CampId=".$SubCamp->CAMPAIGN_ID);
	else $SubMenu[0]['Link']=getURL("campaign");
	$SubMenu[1]['Name']=$Lang['CodeGen'];
	$SubMenu[1]['Link']=getURL("campaign_link", "CampId=$EditId", "admin");
	$SubMenu[2]['Name']=$Lang['Stat'];
	$SubMenu[2]['Link']=getURL("paid_constructor", "CampId=$EditId", "report");
	$ShowCosts=true;


	if (ValidId($EditCostId)) {
		$Query = "SELECT *, UNIX_TIMESTAMP(START_DATE) AS STAMP1, UNIX_TIMESTAMP(END_DATE) AS STAMP2 FROM ".PFX."_tracker_camp_cost WHERE ID=$EditCostId AND SUB_CAMPAIGN=$EditId";
		$EditCostObj=$Db->Select($Query);
		$EditCost['StartDate']=($EditCostObj->STAMP1)?$EditCostObj->START_DATE:false;
		$EditCost['EndDate']=($EditCostObj->STAMP2)?$EditCostObj->END_DATE:false;
		$EditCost['Name']=$EditCostObj->NAME;	 
		$EditCost['Cost']=$EditCostObj->COST;
	}

	if (!ValidVar($EditCost['StartDate'])) $EditCost['StartDate']=false;
	if (!ValidVar($EditCost['EndDate'])) $EditCost['EndDate']=false;
	if (!ValidVar($EditCost['Name'])) $EditCost['Name']=false;	 
	if (!ValidVar($EditCost['Cost'])) $EditCost['Cost']=false;
	if (!ValidVar($EditCostId)) $EditCostId="new";

	$Query = "
		SELECT *, UNIX_TIMESTAMP(START_DATE) AS STAMP1, UNIX_TIMESTAMP(END_DATE) AS STAMP2
			FROM ".PFX."_tracker_camp_cost CC
			WHERE CC.SUB_CAMPAIGN = $EditId
			ORDER BY START_DATE ASC, ID ASC
	";
	$Sql = new Query($Query);
	$CostArr=array();
	$CostArr2=array();
	$TotalCost=0;
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Row->TITLE="";
	
		if ($Row->MODE==0) {
			if ($Row->STAMP1) $Row->TITLE.=$Row->START_DATE;
			if ($Row->STAMP1&&$Row->STAMP2) $Row->TITLE.=" - ";
			if ($Row->STAMP2) $Row->TITLE.=$Row->END_DATE;
			$CostArr[]=$Row;
			if ($Row->SUM_THIS==1) $TotalCost+=$Row->COST;
		}
		if ($Row->MODE==1) {
			$Row->TITLE=$Row->START_DATE;
			$CostArr2[]=$Row;
		}
	}

	$ReturnDateFormat = "Y-m-d";
	$DateFormatDescriptor = "-";
	include $nsTemplate->Inc("admin.sub_edit");
}



/////////////////////////////////////////////
///////// process functions here


function CreateNewSubCampaign($GrpId, &$Arr)
{
	global $Db, $Logs, $nsProduct, $Lang, $CurrentCompany;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!ValidVar($Type)) $Type=0;
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$CompId=$CurrentCompany->ID;

	$Query = "SELECT ID FROM ".PFX."_tracker_sub_campaign WHERE SRC_ID != '' AND SRC_ID='".escape_string($SrcId)."'";
	$CheckId = $Db->ReturnValue($Query);
	if ($CheckId) {
		$Logs->Err($Lang['UniSrc']);
		return;
	}

	$Query = "INSERT INTO ".PFX."_tracker_camp_piece (CAMPAIGN_ID, NAME, DESCRIPTION, COMPANY_ID) VALUES ($GrpId, ?, ?, $CompId)";
	$Db->Query($Query, $Name, $Descr);
	$NewId=$Db->LastInsertId;
	$Query = "INSERT INTO ".PFX."_tracker_sub_campaign (SUB_ID, TYPE, SRC_ID) VALUES ($NewId, '$Type', ?)";
	$Db->Query($Query, $SrcId);
	ResortTable(PFX."_tracker_camp_piece", "POSITION", "CAMPAIGN_ID=$GrpId");
	$nsProduct->Redir("sub_camp", "RCrt=1&EditId=$NewId");
}


function UpdateSubCampaign($Id, &$Arr)
{
	global $Db, $Logs, $nsProduct, $Lang, $nsUser;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!ValidVar($Type)) $Type=0;
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_camp_piece SET NAME = ?, DESCRIPTION = ? WHERE ID = $Id";
	$Db->Query($Query, $Name, $Descr);
	$Query = "SELECT ID FROM ".PFX."_tracker_sub_campaign WHERE SRC_ID != '' AND SRC_ID='".escape_string($SrcId)."' AND SUB_ID!=$Id AND SRC!='' ";
	$CheckId = $Db->ReturnValue($Query);
	if ($CheckId) {
		$Logs->Err($Lang['UniSrc']);
		return;
	}
	$Query = "UPDATE ".PFX."_tracker_sub_campaign SET TYPE='$Type', SRC_ID=? WHERE SUB_ID=$Id";
	$Db->Query($Query, $SrcId);
	if (ValidVar($Watch)==1&&!CheckSubWatch($Id, $nsUser->UserId())) SetSubWatch($Id, $nsUser->UserId());
	else RemoveSubWatch($Id, $nsUser->UserId());

	$nsProduct->Redir("sub_camp", "RUpd=1&EditId=$Id");
}


function GenLink()
{
	global $_GP, $SubCamp, $Logs, $Lang;
	if (isset($_GP['Keyword'])) $Keyword=$_GP['Keyword'];
	else $Keyword=false;
	if (isset($_GP['UrlTO'])) $UrlTO=$_GP['UrlTO'];
	else $UrlTO=false;
	$Keyword=ToLower($Keyword);

	if(!$UrlTO) $ErrArr['Link']=$Lang['MustFill'];
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}


	$Link="";
	$Link.=$UrlTO;
	if (strpos($Link, "?")) $Link.="&";
	else $Link.="?";
	$KeyId=CheckKeyword($Keyword);
	if ($KeyId) $Link.="k=$KeyId&";
	$Link.="c=".$SubCamp->ID;
	$Logs->Msg($Link);
}

function CheckKeyword($Keyword=false)
{
	if (!$Keyword) return false;
	global $Db;
	$Query = "SELECT ID FROM ".PFX."_tracker_keyword WHERE KEYWORD = '$Keyword'";
	$CheckId=$Db->ReturnValue($Query);
	if (ValidId($CheckId)) return $CheckId;

	$Query = "INSERT INTO ".PFX."_tracker_keyword (KEYWORD) VALUES ('$Keyword')";
	$Db->Query($Query);
	return $Db->LastInsertId;
}

function UpdateCost($Id, $Arr)
{
	global $Db, $Logs, $nsProduct, $Lang, $EditId;
	extract($Arr);
	if(!$Cost) $ErrArr['Cost']=$Lang['MustFill'];
	if (!ValidVar($Name)) $Name="";
	if (!ValidVar($EndDate)) $EndDate="";
	if (!ValidVar($Mode)) $Mode=0;
	if ($Mode==1&&!ValidVar($StartDate)) $ErrArr['StartDate']=$Lang['MustFill'];
	if ($Mode==1) {
		$Query = "SELECT ID FROM ".PFX."_tracker_camp_cost WHERE SUB_CAMPAIGN=$EditId AND START_DATE = '$StartDate' AND ID!=$Id";
		$CheckId=$Db->ReturnValue($Query);
		if ($CheckId>0) $ErrArr['StartDate']=$Lang['DateDuplicate'];
	}
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Cost=str_replace(",", ".", $Cost);
	$Query = "UPDATE ".PFX."_tracker_camp_cost SET NAME = ?, START_DATE = ?, END_DATE = ?, COST = ? WHERE ID = $Id";
	$Db->Query($Query, $Name, $StartDate, $EndDate, $Cost);
	UpdatePerClickEndDate($EditId);
	$nsProduct->Redir("sub_camp", "RUpd=1&EditId=$EditId");
}

function CreateCost($Arr)
{
	global $Db, $Logs, $nsProduct, $Lang, $EditId;
	extract($Arr);
	if(!$Cost) $ErrArr['Cost']=$Lang['MustFill'];
	if (!ValidVar($Name)) $Name="";
	if (!ValidVar($EndDate)) $EndDate="";
	if (!ValidVar($Mode)) $Mode=0;
	if ($Mode==1&&!ValidVar($StartDate)) $ErrArr['StartDate']=$Lang['MustFill'];
	if ($Mode==1) {
		$Query = "SELECT ID FROM ".PFX."_tracker_camp_cost WHERE SUB_CAMPAIGN=$EditId AND START_DATE = '$StartDate'";
		$CheckId=$Db->ReturnValue($Query);
		if ($CheckId>0) $ErrArr['StartDate']=$Lang['DateDuplicate'];
	}
	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Cost=str_replace(",", ".", $Cost);
	$Query = "INSERT INTO ".PFX."_tracker_camp_cost (SUB_CAMPAIGN, NAME, START_DATE, END_DATE, COST, MODE) VALUES ($EditId, ?, ?, ?, ?, ?)";
	$Db->Query($Query, $Name, $StartDate, $EndDate, $Cost, $Mode);
	UpdatePerClickEndDate($EditId);
	$nsProduct->Redir("sub_camp", "RCrt=1&EditId=$EditId");
}

function UpdatePerClickEndDate($EditId)
{
	global $Db;
	$Query = "SELECT * FROM ".PFX."_tracker_camp_cost WHERE SUB_CAMPAIGN=$EditId ORDER BY START_DATE";
	$Sql = new Query($Query);
	$Cost=array();
	while ($Row=$Sql->Row()) $Cost[]=$Row;
	for ($i=0;$i<count($Cost);$i++) {
		if (isset($Cost[$i+1])) {
			$Query = "UPDATE ".PFX."_tracker_camp_cost SET END_DATE = '".$Cost[$i+1]->START_DATE."' WHERE ID = ".$Cost[$i]->ID;
			$Db->Query($Query);
		}
		else $Db->Query("UPDATE ".PFX."_tracker_camp_cost SET END_DATE ='' WHERE ID =".$Cost[$i]->ID);
	}
}

function UpdateSumThis(&$Arr)
{
	global $Db, $EditId, $UpdateMode;
	$Mode=(ValidVar($UpdateMode)==1)?1:0;
	$Query = "UPDATE ".PFX."_tracker_camp_cost SET SUM_THIS = '0' WHERE SUB_CAMPAIGN=$EditId AND MODE='$Mode'";
	$Db->Query($Query);
	if (!ValidArr($Arr)) return;
	foreach ($Arr as $Key => $Value) {
		if ($Value!=1) continue;
		$Query = "UPDATE ".PFX."_tracker_camp_cost SET SUM_THIS = '1' WHERE ID=$Key AND MODE='$Mode'";
		$Db->Query($Query);
	}
}

function MoveSubCamp($CampId, $MoveTo) {
	global $Db, $nsProduct;
	if ($MoveTo==-1) $MoveTo=0;
	$Query = "UPDATE ".PFX."_tracker_camp_piece SET CAMPAIGN_ID=$MoveTo WHERE ID=$CampId";
	$Db->Query($Query);
	$nsProduct->Redir("sub_camp", "RUpd=1&EditId=$CampId");
}

function DeleteCost($Id)
{
	global $Db, $nsProduct, $EditId;
	$Query = "DELETE FROM ".PFX."_tracker_camp_cost WHERE ID = $Id";
	$Db->Query($Query);
	$nsProduct->Redir("sub_camp", "RDlt=1&EditId=$EditId");
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

function GetPiece($Id)
{
	global $Db, $CurrentCompany;
	$Query = "
		SELECT 
			TCP.*,
			TSC.TYPE, TSC.SRC_ID
			FROM ".PFX."_tracker_camp_piece TCP
				INNER JOIN ".PFX."_tracker_sub_campaign TSC
					ON TSC.SUB_ID=TCP.ID
			WHERE TCP.ID = $Id
	";
	$SubCamp=$Db->Select($Query);

	$SubCamp->Currency[0]=$CurrentCompany->CURRENCY;
	$SubCamp->Currency[1]=$CurrentCompany->CURRENCY_POSITION;
	return $SubCamp;
}


?>