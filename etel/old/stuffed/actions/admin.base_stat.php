<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");
if ($nsProduct->LICENSE==3&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");
if ($nsProduct->LICENSE!=3&&!$nsUser->SUPER_USER) $nsProduct->Redir("default", "", "admin");

/////////////////////////////////////////////
///////// require libraries here
require_once SELF."/lib/delete.func.php";
require_once SYS."/system/lib/validate.func.php";
require_once SELF."/class/graph.class.php";

/////////////////////////////////////////////
///////// prepare any variables
$PageTitle=$Lang['Title'];

$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=$PageTitle;
$ProgPath[1]['Url']=getURL("base_stat", "", "admin");
$MenuSection="admin";


$DoOptimize=(ValidVar($_GP['DoOptimize']))?true:false;
$EndDate=(ValidDate($_GP['EndDate']))?$_GP['EndDate']:false;
$DoClear=(ValidVar($_GP['DoClear']))?true:false;
$ForClient=(ValidId($_GP['ForClient']))?$_GP['ForClient']:false;
$SiteId=(ValidId($_GP['SiteId']))?$_GP['SiteId']:false;

$EndTime=false;
if ($EndDate) $EndTime=$EndDate." 23:59:59";

if (!$ForClient&&ValidVar($_GP['ForClient'])!="all"&&ValidId($CurrentCompany->ID)) $ForClient=$CurrentCompany->ID;

$ClientsArr=array();
$StatLogArr=array();
$ActionLogArr=array();
$SaleLogArr=array();
$SplitLogArr=array();
$UndefLogArr=array();
$ClickLogArr=array();

$Query = "SELECT C.ID, C.NAME, S.HOST, S.ID AS SITE_ID FROM ".PFX."_tracker_client C INNER JOIN ".PFX."_tracker_site S ON S.COMPANY_ID=C.ID ORDER BY C.NAME";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	if (!$nsUser->ADMIN&&$Row->ID!=$nsUser->COMPANY_ID) continue;
	if ($nsProduct->LICENSE==2&&$Row->ID!=$CurrentCompany->ID) continue;
	$ClientsArr[$Row->ID]['Name']=$Row->NAME;
	$ClientsArr[$Row->ID]['Sites'][$Row->SITE_ID]=$Row->HOST;

	$StatLogArr[]=PFX."_tracker_".$Row->ID."_stat_log";
	$ActionLogArr[]=PFX."_tracker_".$Row->ID."_stat_action";
	$SaleLogArr[]=PFX."_tracker_".$Row->ID."_stat_sale";
	$SplitLogArr[]=PFX."_tracker_".$Row->ID."_stat_split";
	$UndefLogArr[]=PFX."_tracker_".$Row->ID."_stat_undef";
	$ClickLogArr[]=PFX."_tracker_".$Row->ID."_stat_click";
}



/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if ($DoOptimize) OptimizeTables();
	if ($DoClear&&$EndTime) ClearStat($EndTime, $ForClient, $SiteId);
}

/////////////////////////////////////////////
///////// display section here



$Query = "SHOW TABLE STATUS";
$Sql = new Query($Query);

$TotalSize=0;
$TotalRows=0;
$TotalOverhead=0;

$StatSize=0;
$ActionsSize=0;
$SalesSize=0;
$CampSize=0;
$PathSize=0;
$UndefSize=0;
$SplitSize=0;
$OtherSize=0;

$StatRows=0;
$ActionsRows=0;
$SalesRows=0;
$CampRows=0;
$PathRows=0;
$UndefRows=0;
$SplitRows=0;
$OtherRows=0;

while ($Row=$Sql->Row()) {
	$StatTable=false;
	if (strpos($Row->Name, PFX."_tracker")===false) continue;
	$TotalSize+=$Row->Data_length+$Row->Index_length;
	$TotalRows+=$Row->Rows;
	$TotalOverhead+=$Row->Data_free;

	if (in_array($Row->Name, $StatLogArr)) {
		$StatSize+=$Row->Data_length+$Row->Index_length;
		$StatRows+=$Row->Rows;
		$StatTable=true;
	}

	if (in_array($Row->Name, $ClickLogArr)) {
		$CampSize+=$Row->Data_length+$Row->Index_length;
		$CampRows+=$Row->Rows;
		$StatTable=true;
	}


	if (in_array($Row->Name, $SaleLogArr)) {
		$SalesSize+=$Row->Data_length+$Row->Index_length;
		$SalesRows+=$Row->Rows;
		$StatTable=true;
	}

	if (in_array($Row->Name, $ActionLogArr)) {
		$ActionsSize+=$Row->Data_length+$Row->Index_length;
		$ActionsRows+=$Row->Rows;
		$StatTable=true;
	}
	
	if (in_array($Row->Name, $SplitLogArr)) {
		$SplitSize+=$Row->Data_length+$Row->Index_length;
		$SplitRows+=$Row->Rows;
		$StatTable=true;
	}
	
	if (in_array($Row->Name, $UndefLogArr)) {
		$UndefSize+=$Row->Data_length+$Row->Index_length;
		$UndefRows+=$Row->Rows;
		$StatTable=true;
	}

	if (!$StatTable) {
		$OtherSize+=$Row->Data_length+$Row->Index_length;
		$OtherRows+=$Row->Rows;
	}
	
}

$SizePie=new StatGraph("SizePie");
$SizePie->Name=$Lang['SizeDiff'];
$SizePie->Display=true;
$SizePie->Vars['disp_type']="pie";
$SizePie->CanDump=true;

DrawPie($SizePie, $Lang['SitePath'], $StatSize, "330099", 0);
DrawPie($SizePie, $Lang['ActionStat'], $ActionsSize, "339933", 1);
DrawPie($SizePie, $Lang['SaleStat'], $SalesSize, "CC6600", 2);
DrawPie($SizePie, $Lang['SplitStat'], $SplitSize, "FF0099", 3);
DrawPie($SizePie, $Lang['CampStat'], $CampSize, "9999FF", 4);
DrawPie($SizePie, $Lang['UndefStat'], $UndefSize, "FFFF00", 5);
DrawPie($SizePie, $Lang['OtherTables'], $OtherSize, "FF0000", 6);


$MinTable=false;
$MaxTable=false;
$MinStamp=0;
$MaxStamp=0;
$MinId=0;
$MaxId=0;
for($i=0;$i<count($StatLogArr);$i++) {
	$Query = "SELECT MIN(ID) AS MIN_ID, MAX(ID) AS MAX_ID FROM ".$StatLogArr[$i];
	$MM=$Db->Select($Query);
	if (ValidId($MM->MIN_ID)&&$MM->MIN_ID>0) {
		$MM->MIN_STAMP=$Db->ReturnValue("SELECT UNIX_TIMESTAMP(STAMP) FROM ".$StatLogArr[$i]." WHERE ID=".$MM->MIN_ID);
		$MM->MAX_STAMP=$Db->ReturnValue("SELECT UNIX_TIMESTAMP(STAMP) FROM ".$StatLogArr[$i]." WHERE ID=".$MM->MAX_ID);
		if ($MinStamp>$MM->MIN_STAMP||$MinStamp==0) {
			$MinStamp=$MM->MIN_STAMP;
			$MinTable=$StatLogArr[$i];
			$MinId=$MM->MIN_ID;
		}
		if ($MaxStamp<$MM->MAX_STAMP||$MaxStamp==0) {
			$MaxStamp=$MM->MAX_STAMP;
			$MaxTable=$StatLogArr[$i];
			$MaxId=$MM->MAX_ID;
		}
	}
}

$MinDays=$MaxDays=false;
if ($MinId&&$MaxId) {
	$MinStamp=date("Y-m-d", $MinStamp);
	$MinDays=$Db->ReturnValue("SELECT TO_DAYS(STAMP) FROM $MinTable WHERE ID=$MinId");
	$MaxDays=$Db->ReturnValue("SELECT TO_DAYS(STAMP) FROM $MaxTable WHERE ID=$MaxId");
}
$DayCnt=$MaxDays-$MinDays;
if ($DayCnt>0) $AvgDaySize=round($TotalSize/$DayCnt, 2);
else $AvgDaySize=0;


include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here

function OptimizeTables()
{
	global $Logs, $Db, $Lang;
	$Query="SHOW TABLE STATUS";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		if (strpos($Row->Name, PFX."_tracker")===false) continue;
		if ($Row->Data_free>0) $Db->Query("OPTIMIZE TABLE ".$Row->Name);
	}
	$Logs->Msg($Lang['SuccOptimize']);
}

function ClearStat($EndTime=false, $CpId=false, $SiteId=false)
{
	global $Logs, $Db, $Lang;
	if ($SiteId) {
		$Query = "SELECT MIN(S_LOG.STAMP) FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG WHERE S_LOG.SITE_ID=$SiteId";
		$StartTime=$Db->ReturnValue($Query);
		DeleteSiteStat($CpId, $SiteId, $StartTime, $EndTime); 
	}
	if ($CpId&&!$SiteId) {
		$Query = "SELECT MIN(S_LOG.STAMP) FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG INNER JOIN ".PFX."_tracker_site S ON S.ID=S_LOG.SITE_ID WHERE S.COMPANY_ID=$CpId";
		$StartTime=$Db->ReturnValue($Query);
		global $ClientsArr;
		if (ValidArr($ClientsArr[$CpId]['Sites'])&&count($ClientsArr[$CpId]['Sites'])>0) {
			foreach ($ClientsArr[$CpId]['Sites'] as $SiteId=>$Name) DeleteSiteStat($CpId, $SiteId, $StartTime, $EndTime); 
		}
	}
	if (!$CpId&&!$SiteId) {
		$Query = "SELECT ID, COMPANY_ID FROM ".PFX."_tracker_site";
		$Sql = new Query($Query);
		while ($Row=$Sql->Row()) {
			$Query = "SELECT MIN(S_LOG.STAMP) FROM ".PFX."_tracker_".$Row->COMPANY_ID."_stat_log S_LOG";
			$StartTime=$Db->ReturnValue($Query);
			DeleteSiteStat($Row->COMPANY_ID, $Row->ID, $StartTime, $EndTime); 
		}
	}
	$Logs->Msg($Lang['StatDeleted']);
}


/////////////////////////////////////////////
///////// library section

function DrawPie(&$Graph, $Name, $Size, $Color, $Inx)
{
	$Graph->Vars['piece'.$Inx.'_caption']=$Name;
	$Graph->Vars['piece'.$Inx.'_value']=$Size;
	$Graph->Vars['piece'.$Inx.'_color']=$Color;
	$Graph->Vars['piece'.$Inx.'_description']="";
}


?>