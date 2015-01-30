<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
$nsLang->TplInc("constructor/report_constructor");
$nsLang->TplInc("constructor/save_report");

require_once SELF."/lib/const_groups.arr.php";
require_once SYS."/system/lib/validate.func.php";

/////////////////////////////////////////////
///////// prepare any variables
$WhereArr=(ValidArr($_GP['WhereArr']))?$_GP['WhereArr']:false;
$SaveReport=(ValidArr($_GP['SaveReport']))?$_GP['SaveReport']:false;
$NoSubmit=(ValidVar($_GP['NoSubmit']))?$_GP['NoSubmit']:false;

$CpId=false;
$GroupBy=false;
$DatesUsed=false;
$ViewDate=false;
$StartDate=false;
$EndDate=false;
$Filter=false;
$Limit=false;
$ShowAll=false;
$OrderBy=false;
$OrderTo=false;
$SaveMode=false;

if (ValidArr($SaveReport)) extract($SaveReport);


if ($WhereArr&&$SaveReport&&!$NoSubmit&&!$nsUser->DEMO) SaveReport($SaveReport, $WhereArr);
elseif (!$NoSubmit) $Logs->Err($Lang['SaveErr1']);

$PageTitle=$Lang['Title'];

/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here



include $nsTemplate->Inc("inc/header");
include $nsTemplate->Inc("inc/submenu");
include $nsTemplate->Inc("constructor/save_report");
include $nsTemplate->Inc("inc/footer");


/////////////////////////////////////////////
///////// process functions here


function SaveReport($SaveArr, $WhereArr)
{
	global $Db, $Logs, $nsUser, $nsProduct, $NaturalConstPath, $PaidConstPath, $Lang;

	if (ValidVar($SaveArr['SaveMode'])=="NATURAL") {
		$OrderConstPath=$NaturalConstPath;
		$ConstPath="natural_constructor";
	}
	if (ValidVar($SaveArr['SaveMode'])=="PAID") {
		$OrderConstPath=$PaidConstPath;
		$ConstPath="paid_constructor";
	}

	if (!ValidVar($SaveArr['Name'])) {
		$Logs->Err($Lang['NameMustFill']);
		return;
	}
	if (ValidVar($SaveArr['SaveMode'])!="NATURAL"&&ValidVar($SaveArr['SaveMode'])!="PAID") {
		$Logs->Err($Lang['SaveErr1']);
		return;
	}
	if (!ValidVar($SaveArr['GroupBy'])) {
		$Logs->Err($Lang['SaveErr1']);
		return;
	}
	foreach ($WhereArr as $i=>$Row) {
		if (!isset($OrderConstPath[$Row['Mode']])) {
			$Logs->Err($Lang['SaveErr1']);
			return;
		}
	}
	if ( (ValidVar($SaveArr['ViewDate'])&&!ValidDate($SaveArr['ViewDate'])) ||
		 (ValidVar($SaveArr['StartDate'])&&!ValidDate($SaveArr['StartDate'])) ||
		 (ValidVar($SaveArr['EndDate'])&&!ValidDate($SaveArr['EndDate']))
		) {
		$Logs->Err($Lang['ValidDates']);
		return;
	}
	$WhereArrStr=serialize($WhereArr);	

	$CurrentDate=(ValidVar($SaveArr['CurrentDate']))?$SaveArr['CurrentDate']:0;
	$SaveMode=(ValidVar($SaveArr['SaveMode']))?$SaveArr['SaveMode']:0;
	$CpId=(ValidVar($SaveArr['CpId']))?$SaveArr['CpId']:0;
	$GroupBy=(ValidVar($SaveArr['GroupBy']))?$SaveArr['GroupBy']:false;
	$DatesUsed=(ValidVar($SaveArr['DatesUsed']))?$SaveArr['DatesUsed']:false;
	$ViewDate=(ValidVar($SaveArr['ViewDate']))?$SaveArr['ViewDate']:false;
	$StartDate=(ValidVar($SaveArr['StartDate']))?$SaveArr['StartDate']:false;
	$EndDate=(ValidVar($SaveArr['EndDate']))?$SaveArr['EndDate']:false;
	$Filter=(ValidVar($SaveArr['Filter']))?$SaveArr['Filter']:false;
	$Limit=(ValidId($SaveArr['Limit']))?$SaveArr['Limit']:0;
	$ShowAll=(ValidVar($SaveArr['ShowAll']))?$SaveArr['ShowAll']:0;
	$OrderBy=(ValidVar($SaveArr['OrderBy']))?$SaveArr['OrderBy']:false;
	$OrderTo=(ValidVar($SaveArr['OrderTo']))?$SaveArr['OrderTo']:"ASC";
	$Name=(ValidVar($SaveArr['Name']))?addslashes($SaveArr['Name']):false;
	$UserId=$nsUser->UserId();
	$AddToMy=(ValidVar($SaveArr['AddToMy']))?$SaveArr['AddToMy']:false;

	$Query = "
		INSERT INTO ".PFX."_tracker_user_report 
			(CONST_TYPE, COMPANY_ID, NAME, VIEW_DATE, START_DATE, END_DATE, FILTER, PAGE_LIMIT, SHOW_NO_REF, SORT_BY, SORT_ORDER, USE_CURRENT_DATE, GROUP_BY, WHERE_ARR)
			VALUES 
			('$SaveMode', $CpId, '$Name', '$ViewDate', '$StartDate', '$EndDate', '$Filter', $Limit, '$ShowAll', '$OrderBy', '$OrderTo', '$CurrentDate', '$GroupBy', '$WhereArrStr')
		";
	$Db->Query($Query);
	$NewId=$Db->LastInsertId;

	if ($AddToMy) {
		$Query="INSERT INTO ".PFX."_tracker_watch (USER_ID, REPORT_ID) VALUES ($UserId, $NewId)";
		$Db->Query($Query);
	}

	$nsProduct->Redir($ConstPath, "RUpd=1&ConstId=$NewId&CpId=".ValidVar($SaveArr['CpId']), "report");
}

/////////////////////////////////////////////
///////// library section


?>