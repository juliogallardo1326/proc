<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SELF."/lib/company.func.php";
require_once SYS."/system/lib/validate.func.php";
require_once SYS."/system/lib/sql.func.php";
require_once SELF."/class/report_parent.class.php";
require_once SELF."/class/paid_v2.class.php";
require_once SELF."/class/graph.class.php";

$nsLang->TplInc("constructor/report_constructor");
$nsLang->TplInc("inc/report_headers");
require_once SELF."/lib/const_groups.arr.php";


/////////////////////////////////////////////
///////// prepare any variables
$CpId=(ValidVar($_GP['CpId']))?$_GP['CpId']:false;
$GroupBy=(ValidVar($_GP['GroupBy']))?$_GP['GroupBy']:false;
$CurrentGroupBy=(ValidVar($_GP['CurrentGroupBy']))?$_GP['CurrentGroupBy']:false;
$WhereArr=(ValidArr($_GP['WhereArr']))?$_GP['WhereArr']:false;
if (!ValidArr($WhereArr)) $WhereArr=array();
$CurrentId=(ValidArr($_GP['CurrentId']))?$_GP['CurrentId']:false;
if (!ValidArr($CurrentId)) $CurrentId=array();
$StartDate=(ValidDate($_GP['StartDate']))?$_GP['StartDate']:false;
$EndDate=(ValidDate($_GP['EndDate']))?$_GP['EndDate']:false;
$ViewDate=(ValidDate($_GP['ViewDate']))?$_GP['ViewDate']:false;
$Month=(ValidVar($_GP['Month']))?$_GP['Month']:false;
$CampId=(ValidVar($_GP['CampId']))?$_GP['CampId']:false;
$GrpId=(ValidVar($_GP['GrpId']))?$_GP['GrpId']:false;
$Filter=(ValidVar($_GP['Filter']))?trim($_GP['Filter']):false;
$Limit=(ValidVar($_GP['Limit']))?intval($_GP['Limit']):100;
$OrderTo=(ValidVar($_GP['OrderTo']))?$_GP['OrderTo']:false;
$OrderBy=(ValidVar($_GP['OrderBy']))?$_GP['OrderBy']:false;
$ConstId=(ValidId($_GP['ConstId']))?$_GP['ConstId']:false;
$General=(ValidVar($_GP['General']))?$_GP['General']:false;
$ExportReport=(ValidArr($_GP['ExportReport']))?$_GP['ExportReport']:false;
$FormUsed=(ValidVar($_GP['FormUsed'])==1)?true:false;
$Redir=(ValidVar($_GP['Redir'])==1)?true:false;
$Print=(ValidVar($_GP['Print']))?$_GP['Print']:false;

$ShowFilter=($nsUser->ADVANCED_MODE)?1:0;
if (isset($_GP['ShowFilter'])) $ShowFilter=$_GP['ShowFilter'];

$SavePrevLevel=false; $PrevReport=false;


$ShowAll=false;

$NoGroupBy=false;
if ($GroupBy=="General") {
	$NoGroupBy=true;
	$GroupBy=false;
}


$ExportCsv=false;
$ExportSep=",";
$ExportNoLimit=false;
$ExportExpanded=false;
if (ValidArr($ExportReport)) {
	$ExportCsv=true;
	$ExportSep=(ValidVar($ExportReport['Separator']))?$ExportReport['Separator']:",";
	$ExportNoLimit=(ValidVar($ExportReport['NoLimit'])==1)?true:false;
	$ExportExpanded=(ValidVar($ExportReport['Expanded'])==1)?true:false;
}

if (ValidId($ConstId)) ImportReport($ConstId);

if ($GroupBy&&!$OrderBy&&!$OrderTo&&!$CurrentId&&!$Redir) {
	if (isset($WhereArr[count($WhereArr)-1]['OrderBy'])) $OrderBy=$WhereArr[count($WhereArr)-1]['OrderBy'];
	if (isset($WhereArr[count($WhereArr)-1]['OrderTo'])) $OrderTo=$WhereArr[count($WhereArr)-1]['OrderTo'];
}
if ($OrderTo!="DESC"&&$OrderTo!="ASC") $OrderTo="DESC";
$OrderByArr[0]['Key']="CNT";
$OrderByArr[0]['Name']=$Lang['ByHits'];
$OrderByArr[1]['Key']="UNI";
$OrderByArr[1]['Name']=$Lang['ByUni'];
$OrderByArr[2]['Key']="NAME";
$OrderByArr[2]['Name']=(ValidVar($GroupBy))?$Lang[$PaidConstPath[$GroupBy]]:$Lang['ByName'];
$OrderByArr[3]['Key']="DEFAULT"; $OrderByArr[3]['Name']=$Lang['DefaultSort'];
$OrderByArr[4]['Key']="ACTCONV"; $OrderByArr[4]['Name']="";
$OrderByArr[5]['Key']="SALECONV"; $OrderByArr[5]['Name']="";
$OrderByArr[6]['Key']="ACTIONCNT"; $OrderByArr[6]['Name']="";
$OrderByArr[7]['Key']="ACTIONUNI"; $OrderByArr[7]['Name']="";
$OrderByArr[8]['Key']="SALECNT"; $OrderByArr[8]['Name']="";
$OrderByArr[9]['Key']="SALEUNI"; $OrderByArr[9]['Name']="";
$OrderByArr[10]['Key']="ROI"; $OrderByArr[10]['Name']="";
$OrderByArr[11]['Key']="COST"; $OrderByArr[11]['Name']="";
$OrderByArr[12]['Key']="INCOME"; $OrderByArr[12]['Name']="";

$ValidOrder=false;
for($i=0;$i<count($OrderByArr);$i++) if ($OrderByArr[$i]['Key']==$OrderBy) $ValidOrder=true;
if (!$ValidOrder) $OrderBy="DEFAULT";
$DefaultOrderBy=($OrderBy!="DEFAULT"&&	
								$OrderBy!="ACTCONV"&&$OrderBy!="SALECONV"&&
								$OrderBy!="ACTIONCNT"&&$OrderBy!="ACTIONUNI"&&
								$OrderBy!="SALECNT"&&$OrderBy!="SALEUNI"&&
								$OrderBy!="ROI"&&$OrderBy!="COST"&&$OrderBy!="INCOME")?$OrderBy:"CNT";




$PageTitle=$Lang['Title'];
$MetaTitle="PAID";
$SaveMode="PAID";

if (!$GroupBy&&$CurrentGroupBy) {
	if (count($WhereArr)>1) $GroupBy=$CurrentGroupBy;
	if (count($WhereArr)>=1&&ValidId($WhereArr[0]['Id'])) $GroupBy=$CurrentGroupBy;
	if (count($WhereArr)==1&&!ValidId($WhereArr[0]['Id'])) {
		$CurrentGroupBy=false;
		$WhereArr=array();
	}
}

/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here

if (ValidId($CampId)) {
	$WhereArr[0]['Mode']="Camp";
	$WhereArr[0]['Id']=$CampId;
	$WhereArr[0]['OrderBy']="CNT";
	$WhereArr[0]['OrderTo']="DESC";
	$CpId=$Db->ReturnValue("SELECT COMPANY_ID FROM ".PFX."_tracker_camp_piece WHERE ID=$CampId");
}
if (ValidId($GrpId)) {
	$WhereArr[0]['Mode']="Grp";
	$WhereArr[0]['Id']=$GrpId;
	$WhereArr[0]['OrderBy']="CNT";
	$WhereArr[0]['OrderTo']="DESC";
	$Query = "
		SELECT C.COMPANY_ID
			FROM ".PFX."_tracker_campaign C
			WHERE C.ID=$GrpId
	";
	$CpId=$Db->ReturnValue($Query);
}


if (!ValidId($CpId)&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");
if ($CpId) $Client=GetCompany($CpId);
if (!ValidId($Client->ID)&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");
if (!$CpId) $CpId=0;

$GroupByForm=array();
$WhereForm=array();
UserColumns(); $Report= new Paid_v2();
if ($nsUser->Columns->CLICKS) $Report->ShowVisitors=true;
if ($nsUser->Columns->ACTIONS) $Report->ShowActions=true;
if ($nsUser->Columns->SALES) $Report->ShowSales=true;
if ($nsUser->Columns->ROI) $Report->ShowROI=true;
if ($nsUser->Columns->SALES) $Report->ShowSaleConv=true;
if ($nsUser->Columns->ACTIONS) $Report->ShowActionConv=true;
$Report->MakeSum=true;
$Report->CpId=$CpId;
if ($StartDate) $Report->StartDate=$StartDate;
if ($ViewDate) $Report->ViewDate=$ViewDate;
if ($EndDate) $Report->EndDate=$EndDate;

$Get="";
$ShowListName=false;
$ListName="";

$EnableDeepLink=true;


if ($GroupBy&&$GroupBy!=$CurrentGroupBy) {
	$Inx=count($WhereArr);
	if ($Inx>0&&(!isset($WhereArr[$Inx-1]['Id'])||$WhereArr[$Inx-1]['Id']=="")) $Inx--;
	$WhereArr[$Inx]['Mode']=$GroupBy;
	$WhereArr[$Inx]['Id']=false;
	$CurrentGroupBy=false;
}



if(!ValidVar($_GP['OrderBy'])&&count($WhereArr)>0) {
	$Inx=count($WhereArr)-1;
	if ($Inx<0) $Inx=0;
	if (ValidVar($WhereArr[$Inx]['OrderBy'])) {
		$OrderBy=$WhereArr[$Inx]['OrderBy'];
		$OrderTo=$WhereArr[$Inx]['OrderTo'];
	}
	if (ValidVar($_GP['OrderTo'])) $OrderTo=$_GP['OrderTo'];
}

if ($GroupBy) {
	$Inx=count($WhereArr)-1;
	if ($Inx<0) $Inx=0;
	$WhereArr[$Inx]['OrderTo']=$OrderTo;
	$WhereArr[$Inx]['OrderBy']=$OrderBy;
}


$GroupOrder=array();
$Query = "SELECT COUNT(*) FROM ".PFX."_tracker_const_group WHERE CONST_TYPE='PAID' AND COMPANY_ID=$CpId";
$GrpOrderCnt=$Db->ReturnValue($Query);
$GrpCompany=($GrpOrderCnt>0)?$CpId:0;
$Query = "SELECT * FROM ".PFX."_tracker_const_group WHERE CONST_TYPE = 'PAID' AND COMPANY_ID=$GrpCompany ORDER BY POSITION ASC";
$Position=0;
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$GroupOrder[$Position]['Key']=$Row->KEY_NAME;
	$GroupOrder[$Position]['Name']=$Lang[$PaidConstPath[$Row->KEY_NAME]];
	if ($GroupBy==$Row->KEY_NAME&&$OrderBy=="DEFAULT") {
		$DefaultOrderBy=($Row->ORDER_BY!="SALECNT"&&$Row->ORDER_BY!="SALEUNI"
										&&$Row->ORDER_BY!="ACTIONCNT"&&$Row->ORDER_BY!="ACTIONUNI"
										&&$Row->ORDER_BY!="ACTCONV"&&$Row->ORDER_BY!="SALECONV"
										&&$Row->ORDER_BY!="ROI"&&$Row->ORDER_BY!="COST"
										&&$Row->ORDER_BY!="INCOME")?$Row->ORDER_BY:"CNT";
		$OrderBy=$Row->ORDER_BY;
		$OrderTo=($Row->ORDER_TO)?$Row->ORDER_TO:"DESC";
	}

	$Position++;
}


$Get.="&CpId=$CpId&ShowFilter=$ShowFilter&";
$Get.="StartDate=$StartDate&EndDate=$EndDate&ViewDate=$ViewDate&";
$LastMode=false;


if (!$FormUsed&&($ViewDate||$StartDate||$EndDate||$Filter)) $ShowFilter=1;


///////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
require "lib/const_where.inc.php";


//////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////




$CurrentOrder=false;
for($i=0;$i<count($GroupOrder);$i++) if ($GroupOrder[$i]['Key']==$LastMode) $CurrentOrder=$i;


for($i=0;$i<count($GroupOrder);$i++) {
	$GrpKey=$GroupOrder[$i]['Key'];
	if (!ValidVar($WhereForm[$GrpKey])) $GroupByForm[$GrpKey]=$GroupOrder[$i]['Name'];
}

if (!$GroupBy&&!$NoGroupBy) {
	if (!$CurrentOrder||$CurrentOrder==(count($GroupOrder)-1)) $CurrentOrder=0;
	else $CurrentOrder++;
	for($i=$CurrentOrder;$i<count($GroupOrder);$i++) {
		if (ValidVar($GroupByForm[$GroupOrder[$i]['Key']])) {
			$GroupBy=$GroupOrder[$i]['Key'];
			break;
		}
	}
	if ($GroupBy) $nsProduct->Redir("paid_constructor", $Get."&GroupBy=$GroupBy", "report");
}


$GroupByCnt=count($GroupByForm);

if ($StartDate) $Report->StartDate=$StartDate;
if ($ViewDate) $Report->ViewDate=$ViewDate;
if ($EndDate) $Report->EndDate=$EndDate;



///////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
require "lib/const_groupby.inc.php";


require "lib/const_path.inc.php";

$CurrentPath=GetConstPath($WhereArr, count($WhereArr)-2, false);
$CurrentPath.="GroupBy=$GroupBy&Limit=$Limit&Filter=$Filter";
$CurrentPath=$nsProduct->SelfAction($CurrentPath);

//////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////



if ($PrevReport&&$nsUser->Columns->CONVERSIONS) {
	$PrevReport->Calculate();
	$Report->PrevLevelUni=$PrevReport->CampStat[$Report->ConversionUni];
}

$Report->PageLimit=$Limit;
if ($OrderBy == "ACTCONV"||$OrderBy == "SALECONV"
	||$OrderBy == "ACTIONCNT"||$OrderBy == "ACTIONUNI"
	||$OrderBy == "SALECNT"||$OrderBy == "SALEUNI"
	||$OrderBy == "COST"||$OrderBy == "ROI"||$OrderBy == "INCOME") {
		$Report->PageLimit=false;
		@ini_set("memory_limit","20M");
}

$Report->Calculate();

if (ValidVar($Report->CampStat)) {
	if (!$GroupBy) {
		$Report->Tmp[0]=$Report->CampStat;
		$Report->CampStat=$Report->Tmp;
	}
	if ($ShowListName) $Report->CampStat[0]['Name']=$ListName;
	if (!$GroupBy&&!$WhereArr) {
		$Report->CampStat[0]['Name']=$Lang['Total'];
		$GroupBy="General";
	}


  // Experimenting with sorting by actions conversion
	if ($OrderBy == "ACTCONV"||$OrderBy == "SALECONV"
		||$OrderBy == "ACTIONCNT"||$OrderBy == "ACTIONUNI"
		||$OrderBy == "SALECNT"||$OrderBy == "SALEUNI"
		||$OrderBy == "ROI"||$OrderBy == "COST"||$OrderBy == "INCOME") {
	  	usort($Report->CampStat, "SortRows"); 
	    if (count($Report->CampStat) > $Limit) array_splice($Report->CampStat, $Limit);
		$DefaultOrderBy = $OrderBy;
  }

	$z=0;
	foreach ($Report->CampStat as $i=>$Row) {
		if (ValidVar($Row->USTAMP)&&$nsUser->TZ) {
			$Row->USTAMP=ConvertFromGM(date("Y-m-d H:i:s", $Row->USTAMP), true);
			$Row->NAME=$Db->ReturnValue("SELECT DATE_FORMAT(FROM_UNIXTIME(".$Row->USTAMP."), '".$Report->DateTempl."')");
		}
		 	
		if ($GroupBy=="Date") {
			$Row['Name'].=", ".$Lang['DayOfWeekShort'][date("w", $Row['Obj']->USTAMP)];
		}
		if ($GroupBy=="Month") {
			$Row['Name']=explode("-", $Row['Name']);
			$Row['Name']=$Row['Name'][1];
			$Row['Name']=$Lang['MonthName'][intval($Row['Name'])].", ".date("Y", $Row['Obj']->USTAMP);
		}
		if ($GroupBy=="WeekDay") {
			$Row['Name']=$Lang['DayOfWeek'][$Row['Name']];
		}
		if ($GroupBy=="Page"&&!$Report->SiteId) {
			$Row['Name']=$Row['Obj']->SITE_HOST.$Row['Name'];
		}
		if ($GroupBy=="Order") {
			if ($Row['Obj']->CUSTOM_ORDER_ID) $Row['Name'].= " ".$Row['Obj']->CUSTOM_ORDER_ID;
			else $Row['Name'].= " ".$Row['Obj']->ID;
			$Row['Obj']->NAME=$Row['Name'];
		}
		if ($GroupBy=="Vis" && $Row['Obj']->VISITOR_NAME) {
			$Row['Obj']->NAME=$Row['Name']=htmlspecialchars(stripslashes($Row['Obj']->VISITOR_NAME))." (".$Row['Name'].")";
		}
		if ($GroupBy=="Flash") {
			if ($Row['Name']==-1) $Row['Obj']->NAME=$Row['Name']=$Lang['None'];
		}
		if ($GroupBy=="Resolution") {
			if ($Row['Name']==-1 || $Row['Name']=="x") $Row['Obj']->NAME=$Row['Name']=$Lang['Undefined'];
		}
		if ($GroupBy=="Pixel") {
			if ($Row['Name']==-1) $Row['Obj']->NAME=$Row['Name']=$Lang['Undefined'];
		}


		$Row['CntClickPerc']=($Report->CntClickSum>0&&$GroupBy!="General")?round((100/$Report->CntClickSum)*$Row['CntClick'], 2):0;
		$Row['UniClickPerc']=($Report->UniClickSum>0&&$GroupBy!="General")?round((100/$Report->UniClickSum)*$Row['UniClick'], 2):0;
		$Row['CntActionPerc']=($Report->CntActionSum>0&&$GroupBy!="General")?round((100/$Report->CntActionSum)*$Row['CntAction'], 2):0;
		$Row['UniActionPerc']=($Report->UniActionSum>0&&$GroupBy!="General")?round((100/$Report->UniActionSum)*$Row['UniAction'], 2):0;
		$Row['CntSalePerc']=($Report->CntSaleSum>0&&$GroupBy!="General")?round((100/$Report->CntSaleSum)*$Row['CntSale'], 2):0;
		$Row['UniSalePerc']=($Report->UniSaleSum>0&&$GroupBy!="General")?round((100/$Report->UniSaleSum)*$Row['UniSale'], 2):0;

		if ($ExportCsv&&$ExportExpanded) {
			$Row['ActionConvOne']=$Report->OneOf($Row['UniAction'], $Row['UniClick']);
			$Row['SaleConvOne']=$Report->OneOf($Row['UniSale'], $Row['UniClick']);
		}

		$Row['Position']=$z;
		$Report->CampStat[$i]=$Row;
		$z++;
	}


	if ($ExportCsv) {	
		include_once SELF."/lib/export_csv.inc.php";
		$CsvNames['Name']="--";
		$CsvNames['CntClick|CntClickPerc']=$Lang['TotalHits'];
		$CsvNames['UniClick|UniClickPerc']=$Lang['UniVisitors'];
		$CsvNames['CntAction|CntActionPerc']=$Lang['TotalActions'];
		$CsvNames['UniAction|UniActionPerc']=$Lang['ByVisitors'];
		$CsvNames['CntSale|CntSalePerc']=$Lang['TotalSales'];
		$CsvNames['UniSale|UniSalePerc']=$Lang['ByVisitors'];
		$CsvNames['ROI']=$Lang['ROI'];
		$CsvNames['CampCost']=$Lang['CampCost'];
		$CsvNames['TotalIncome']=$Lang['CampIncome'];
		$CsvNames['ActionConv|ActionConvOne']=$Lang['ActionsConv'];
		$CsvNames['SaleConv|SaleConvOne']=$Lang['SalesConv'];
		send_file_to_client("export.csv", ExportCsv($Report->CampStat, $ExportSep, $CsvNames, $ExportExpanded));
	}

	if ($nsUser->Columns->GRAPHS) {
		$StatArrName="CampStat";
		$DateFormat=$GroupBy;
		if($GroupBy=="Date"||$GroupBy=="Month"||$GroupBy=="Time"||
			$GroupBy=="Year"||$GroupBy=="WeekDay") include_once SELF."/lib/date_graph.inc.php";
		if ($GroupBy!="General"&&$GroupBy!="Date"&&$GroupBy!="Month"&&$GroupBy!="Time"&&
			$GroupBy!="Year"&&$GroupBy!="WeekDay") include_once SELF."/lib/pie_graph.inc.php";
	}

	include $nsTemplate->Inc("inc/".(($Print)?"print_":"")."header");
	if (!$Print) include $nsTemplate->Inc("inc/submenu");
	if (!$Print) include $nsTemplate->Inc("constructor/paid_form");
	include $nsTemplate->Inc("report.paid_stat");
	

	if ($nsUser->Columns->GRAPHS) {
		if (($GroupBy=="Date"||$GroupBy=="Month"||$GroupBy=="Time"||
			$GroupBy=="Year"||$GroupBy=="WeekDay")&&$DateCanDump) include $nsTemplate->Inc("constructor/date_graph");
		if ($GroupBy!="General"&&$GroupBy!="Date"&&$GroupBy!="Month"&&$GroupBy!="Time"&&
			$GroupBy!="Year"&&$GroupBy!="WeekDay"&&$PieCanDump) include $nsTemplate->Inc("constructor/pie_graph");
	}


}
else {
	include $nsTemplate->Inc("inc/".(($Print)?"print_":"")."header");
	if (!$Print) include $nsTemplate->Inc("inc/submenu");
	if (!$Print) include $nsTemplate->Inc("constructor/paid_form");
	include $nsTemplate->Inc("inc/no_records");
}

//if (count($TmpStat)>0) $Report->CampStat[]=$TmpStat;



include $nsTemplate->Inc("inc/".(($Print)?"print_":"")."footer");
/////////////////////////////////////////////
///////// process functions here


/////////////////////////////////////////////
///////// library section

function GetConstPath(&$Arr, $Inx, $Group=true) {
	global $CpId, $ViewDate, $StartDate, $EndDate, $DatesUsed, $ShowFilter;
	$URL="CpId=$CpId&";
	if (!$DatesUsed) $URL.="StartDate=$StartDate&EndDate=$EndDate&ViewDate=$ViewDate&ShowFilter=$ShowFilter&";
	for($i=0;$i<=$Inx;$i++) {
		if ($Inx!=$i||!$Group) {
			$URL.="WhereArr[$i][Mode]=".$Arr[$i]['Mode']."&";
			if (isset($Arr[$i]['Id'])&&$Arr[$i]['Id']!="") $URL.="WhereArr[$i][Id]=".$Arr[$i]['Id']."&";
		}
		if ($Group) $URL.="GroupBy=".$Arr[$i]['Mode']."&";
		if (ValidVar($Arr[$i]['OrderTo'])) $URL.="WhereArr[$i][OrderTo]=".$Arr[$i]['OrderTo']."&";
		if (ValidVar($Arr[$i]['OrderBy'])) $URL.="WhereArr[$i][OrderBy]=".$Arr[$i]['OrderBy']."&";
	}
	return $URL;
}

function SortRows($a, $b) {
	global $OrderTo, $OrderBy;
	if ($OrderBy=="ACTCONV") $Key="ActionConv";
	if ($OrderBy=="SALECONV") $Key="SaleConv";
	if ($OrderBy=="ACTIONCNT") $Key="CntAction";
	if ($OrderBy=="ACTIONUNI") $Key="UniAction";
	if ($OrderBy=="SALECNT") $Key="CntSale";
	if ($OrderBy=="SALEUNI") $Key="UniSale";
	if ($OrderBy=="ROI") $Key="ROI";
	if ($OrderBy=="COST") $Key="CampCost";
	if ($OrderBy=="INCOME") $Key="TotalIncome";
  	if ($a[$Key] < $b[$Key]) {
  		return ($OrderTo == 'ASC' ? -1 : 1);
  	} else if ($a[$Key] > $b[$Key]) {
  		return ($OrderTo == 'ASC' ? 1 : -1);;
  	} else {
  		return 0;
  	}
}


function RefCut($Ref, $MaxLen=45) {

	$Len=strlen($Ref);
	if ($Len>$MaxLen+1) {
		$Ref=substr_replace($Ref, "...", 30, $Len-30-(($MaxLen-30)+3));
	}
	return $Ref;
}

?>