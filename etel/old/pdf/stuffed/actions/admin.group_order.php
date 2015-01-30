<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");

/////////////////////////////////////////////
///////// require libraries here
$nsLang->TplInc("constructor/report_constructor");

require_once SELF."/lib/const_groups.arr.php";

/////////////////////////////////////////////
///////// prepare any variables
$EnableElement=(ValidArr($_GP['EnableElement']))?$_GP['EnableElement']:false;
$SaveOrderArr=(ValidVar($_GP['SaveOrderArr']))?$_GP['SaveOrderArr']:false;
$CompanyId=(ValidId($_GP['CompanyId']))?$_GP['CompanyId']:0;
$CpId=(ValidId($_GP['CpId']))?$_GP['CpId']:false;
$ClearOrder=(ValidVar($_GP['ClearOrder']))?$_GP['ClearOrder']:false;
$Mode=(ValidVar($_GP['Mode']))?$_GP['Mode']:"NATURAL";
if ($Mode!="NATURAL"&&$Mode!="PAID") $nsProduct->Redir("config", "", "admin");

$SavePosArr=(ValidArr($_GP['SavePosArr']))?$_GP['SavePosArr']:false;
$SaveEnableArr=(ValidArr($_GP['SaveEnableArr']))?$_GP['SaveEnableArr']:false;
$SaveOrderToArr=(ValidArr($_GP['SaveOrderToArr']))?$_GP['SaveOrderToArr']:false;
$SaveOrderByArr=(ValidArr($_GP['SaveOrderByArr']))?$_GP['SaveOrderByArr']:false;


if ($CompanyId>0&&!($nsUser->SUPER_USER||$nsUser->ADMIN)) $nsProduct->Redir("default", "", "admin");
if ($CompanyId<1&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");

$nsLang->TplInc("inc/user_welcome");
$nsLang->TplInc("inc/menu");


//if (ValidId($CpId)&&$CpId>0) $CompanyId=$CpId;

if ($Mode=="NATURAL") {
	$PageTitle=$Lang['Title1'];
	$OrderConstPath=$NaturalConstPath;
}
if ($Mode=="PAID") {
	$PageTitle=$Lang['Title2'];
	$OrderConstPath=$PaidConstPath;
}


/////////////////////////////////////////////
///////// call any process functions
if (!$nsUser->DEMO) {
	if (ValidVar($ClearOrder)==1&&ValidId($CompanyId)) ClearOrder($CompanyId, $Mode);
	if (ValidArr($SavePosArr)) 
		SaveNaturalOrder($SavePosArr, $SaveEnableArr, $SaveOrderByArr, $SaveOrderToArr);
}

/////////////////////////////////////////////
///////// display section here
$GrpCompany=0;

if($CompanyId>0) 	{
	$MenuSection="settings";
	$ProgPath[0]['Name']=$Lang['MSettings'];
	$ProgPath[0]['Url']=getURL("settings", "CpId=$CompanyId", "admin");
}
else {
	$MenuSection="admin";
	$ProgPath[0]['Name']=$Lang['Administr'];
	$ProgPath[0]['Url']=getURL("admin", "", "admin");
}
if(ValidId($CompanyId)) {
	$Query = "SELECT COUNT(*) FROM ".PFX."_tracker_const_group WHERE CONST_TYPE='$Mode' AND COMPANY_ID=$CompanyId";
	$GrpOrderCnt=$Db->ReturnValue($Query);
	$GrpCompany=($GrpOrderCnt>0)?$CompanyId:0;
}

	$ProgPath[1]['Name']=$PageTitle;
	$ProgPath[1]['Url']=getURL("group_order", "CompanyId=$CompanyId&Mode=$Mode", "admin");

$Query = "SELECT * FROM ".PFX."_tracker_const_group WHERE CONST_TYPE = '$Mode' AND COMPANY_ID=$GrpCompany ORDER BY POSITION ASC";
$Sql = new Query($Query);
$OrderArr=array();
$OrderKeys=array();
$Position=-1;
while ($Row=$Sql->Row()) {
	$OrderArr[$Row->POSITION]['Key']=$Row->KEY_NAME;
	$OrderArr[$Row->POSITION]['Checked']=true;
	$OrderArr[$Row->POSITION]['Name']=$Lang[$OrderConstPath[$Row->KEY_NAME]];
	$OrderArr[$Row->POSITION]['OrderBy']=($Row->ORDER_BY)?$Row->ORDER_BY:"CNT";
	$OrderArr[$Row->POSITION]['OrderTo']=($Row->ORDER_TO)?$Row->ORDER_TO:"DESC";

	$Position=$Row->POSITION;
	$OrderKeys[$Row->KEY_NAME]=$Row->POSITION;
}
$Position++;
foreach($OrderConstPath as $Key=>$LangKey) {
	if (!isset($OrderKeys[$Key])) {
		$OrderArr[$Position]['Key']=$Key;
		$OrderArr[$Position]['Checked']=false;
		$OrderArr[$Position]['Name']=$Lang[$LangKey];
		$OrderArr[$Position]['OrderBy']="CNT";
		$OrderArr[$Position]['OrderTo']="DESC";
		$Position++;
	}
}


include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here

function SaveNaturalOrder($PosArr=false, $SaveArr=false, $OrderByArr=false, $OrderToArr=false)
{
	global $Db, $CompanyId, $Mode, $Logs, $ClearOrder, $Lang, $nsProduct;
	$Query = "DELETE FROM ".PFX."_tracker_const_group WHERE CONST_TYPE = '$Mode' AND COMPANY_ID=$CompanyId";
	$Db->Query($Query);
	if ($ClearOrder) return false;
	foreach ($SaveArr as $Key=>$Enable) {
		if ($Enable&&isset($PosArr[$Key])) {
			$Position=$PosArr[$Key];
			$OrderBy=$OrderByArr[$Key];
			$OrderTo=$OrderToArr[$Key];
			$Query="INSERT INTO ".PFX."_tracker_const_group (CONST_TYPE, COMPANY_ID, KEY_NAME, POSITION, ORDER_BY, ORDER_TO) VALUES ('$Mode', $CompanyId, '$Key', $Position, '$OrderBy', '$OrderTo')";
			$Db->Query($Query);
		}
	}
	$nsProduct->Redir("group_order", "RUpd=1&Mode=$Mode&CompanyId=$CompanyId", "admin");
}

function ClearOrder($CpId, $Mode)
{
	global $Db, $Logs, $Lang;
	$Query="DELETE FROM ".PFX."_tracker_const_group WHERE COMPANY_ID=$CpId AND CONST_TYPE='$Mode'";
	$Db->Query($Query);
	$Logs->Msg($Lang['RecordUpdated']);
}

/////////////////////////////////////////////
///////// library section


?>