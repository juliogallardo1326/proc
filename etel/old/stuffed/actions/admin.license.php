<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");
if ($nsProduct->LICENSE==3&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");
if ($nsProduct->LICENSE!=3&&!$nsUser->SUPER_USER) $nsProduct->Redir("default", "", "admin");

/////////////////////////////////////////////
///////// require libraries here

/////////////////////////////////////////////
///////// prepare any variables

$ViewId=(ValidId($_GP['ViewId']))?$_GP['ViewId']:false;
$EditId=(ValidVar($_GP['EditId']))?$_GP['EditId']:false;
$NewKey=(ValidVar($_GP['NewKey']))?trim($_GP['NewKey']):false;
$DeleteId=(ValidId($_GP['DeleteId']))?$_GP['DeleteId']:false;

$PageTitle=$Lang['Title'];
$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=$Lang['Title'];
$ProgPath[1]['Url']=getURL("license", "", "admin");
$MenuSection="admin";

$ClientsCntNow=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_client");
$SitesCntNow=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site");

if ($nsProduct->TRIAL_EXCEED&&!$NewKey) {
	if ($nsProduct->MAX_KEY_VERSION>0&&$nsProduct->MAX_KEY_VERSION<$nsProduct->VERSION) 
		$Logs->Err($Lang['WrongVersion']);
	else $Logs->Err($Lang['KeyNeeded']);
}

$KeyVars['ID']=$Lang['LicenseID'];
$KeyVars['CL']=$Lang['LicenseClient'];
$KeyVars['D']=$Lang['LicenseDate'];
$KeyVars['L']=$Lang['LicenseL2'];



/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if ($NewKey) AddNewKey($NewKey);
	if (ValidId($DeleteId)) DeleteKey($DeleteId);
}

/////////////////////////////////////////////
///////// display section here



$ClientsCnt=0;
$SitesCnt=0;


if (!$EditId&&!$ViewId) {
	$LicenseArr=array();
	$Query = "SELECT *, UNIX_TIMESTAMP(STAMP) AS STAMP FROM ".PFX."_tracker_license ORDER BY STAMP DESC";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Row->AGENT=false;
		$Row->CLIENT=false;
		$Row->DISABLED=false;
		$Row->ADD=false;
		$Row->WL=false;
		$Row->KEY_DECODE=$BF->decrypt($Row->LICENSE_KEY);
		$Row->License=GetLicenseText($Row->KEY_DECODE);
		if (!$Row->License) continue;
		$Row->License['D']=date("Y-m-d", $Row->STAMP);
		$SitesCnt+=$Row->License['L'];
		if ($Row->License['L']==0) unset($Row->License['L']);

		if (isset($Row->License['P'])&&$Row->License['P']==2) $Row->CLIENT=true;
		if (isset($Row->License['P'])&&$Row->License['P']==3) $Row->AGENT=true;
		if (!isset($Row->License['P'])) $Row->ADD=true;

		$LicenseArr[]=$Row;
	}

	for ($i=0;$i<Count($LicenseArr);$i++) {
		if (ValidVar($LicenseArr[$i]->License['WL'])==1) {
			//$LicenseArr[$i]->AGENT=false;
			//$LicenseArr[$i]->CLIENT=false;
			$LicenseArr[$i]->ADD=false;
			$LicenseArr[$i]->DISABLED=false;
			$LicenseArr[$i]->WL=true;
			unset($LicenseArr[$i]->License['L']);
		}
	}

	$SubMenu[0]['Name']=$Lang['AddNewKey'];
	$SubMenu[0]['Link']=getURL("license", "EditId=new", "admin");

	include $nsTemplate->Inc();
}

if ($EditId=="new") {
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("license", "", "admin");
	include $nsTemplate->Inc("admin.new_license");
}



/////////////////////////////////////////////
///////// process functions here

function AddNewKey($Key)
{
	global $Db, $nsProduct, $BF, $Logs, $Lang;
	$Key=str_replace("\n", "", $Key);

	$KEY_DECODE=$BF->decrypt($Key);
	$License=GetLicenseText($KEY_DECODE);
	if (!$License) {
		$Logs->Err($Lang['KeyHasNoInfo']);
		return;
	}
	if ($nsProduct->LICENSE==1&&!isset($License['P'])) {
		$Logs->Err($Lang['SecondaryKeyErr']);
		return;
	}
	$KeyId=intval(ValidVar($License['ID']));
	if (!ValidId($KeyId)||$KeyId==0) {
		$Logs->Err($Lang['KeyIsInvalid']);
		return;
	}
	if (ValidVar($License['L'])!=intval(ValidVar($License['L']))) {
		$Logs->Err($Lang['KeyIsInvalid']);
		return;
	}

	$Query = "SELECT ID FROM ".PFX."_tracker_license WHERE KEY_ID=$KeyId OR LICENSE_KEY='$Key'";
	$CheckId=$Db->ReturnValue($Query);
	if (ValidId($CheckId)) {
		$Logs->Err($Lang['KeyExists']);
		return;
	}

	if (CompareVersions($License['V'], $nsProduct->VERSION, 1)!=0) {
		$Logs->Err($Lang['KeyIsInvalid']);
		return;
	}
	
	if (isset($License['REQ']) && !CheckRequirements($License['REQ'])) {
		$Logs->Err($Lang['RequiredNotFound']);
		return false;
	}
	

	$Query = "INSERT INTO ".PFX."_tracker_license (LICENSE_KEY, STAMP, KEY_ID) VALUES ('$Key', NOW(), $KeyId)";
	$Db->Query($Query);
	$nsProduct->Redir("license", "RCrt=1", "admin");
}

// функция занимается проверкой требований лицензии
// если в ключе указано, REQ=P/>&equal;/2
// то это значит, что тип лицензии должен быть больше или равен, чем 2
// то есть мерчант, или агентская
// и такие же операции можно проделывать с любыми другими переменными
// например с айдишником - REQ=ID/102 (требуется наличие лицензии с айдишником 102) 
function CheckRequirements($Req=false)
{
	if (!$Req) return false;
	
	$FullArr=GetFullLicenseArr();
	$ReqArr=explode(";", $Req); // собираем массив из строки типа P/2;WL;L/10
	$Return = false;
	for ($i=0;$i<count($ReqArr);$i++) {
		if ($Return) return false;
		if (!$ReqArr[$i]) continue;
		$Func=false;
		$ReqValue=explode("/",$ReqArr[$i]); // разбиваем P/<=/2 или P/2 в массив
		if (!isset($FullArr[$ReqValue[0]])) return false;  // если ключа P в общем массиве лицензии нет, то сразу false
		if (count($ReqValue)==2) { // если не конкретизирован способ сравнения, то подразумевается equal
			for ($j=0; $j<count($FullArr[$ReqValue[0]]); $j++) {
				if (trim($FullArr[$ReqValue[0]][$j])==$ReqValue[1]) {	$Return = false; break;}
				$Return = true;
			}
		}
		if (count($ReqValue)==3) { // если конкретизирован способ сравнения
			$ReqValue[1]=str_replace("&equal;", "=", $ReqValue[1]);
			if (substr($ReqValue[1], 0, 3)=="sum") $Func="sum";
			if (substr($ReqValue[1], 0, 3)=="max") $Func="max";
			if (substr($ReqValue[1], 0, 3)=="min") $Func="min";
			if ($Func=="sum") {
				$Value=0;
				for ($j=0; $j<count($FullArr[$ReqValue[0]]); $j++) $Value+=trim($FullArr[$ReqValue[0]][$j]);
				$ReqValue[1]=substr($ReqValue[1], 3);
			}
			if ($Func=="max") {
				$Value=0;
				for ($j=0; $j<count($FullArr[$ReqValue[0]]); $j++) if ($Value<trim($FullArr[$ReqValue[0]][$j])) $Value=trim($FullArr[$ReqValue[0]][$j]);
				$ReqValue[1]=substr($ReqValue[1], 3);
			}
			if ($Func=="min") {
				$Value=false;
				for ($j=0; $j<count($FullArr[$ReqValue[0]]); $j++) if ($Value!==false&&$Value>trim($FullArr[$ReqValue[0]][$j])) $Value=trim($FullArr[$ReqValue[0]][$j]);
				$ReqValue[1]=substr($ReqValue[1], 3);
			}	
			for ($j=0; $j<count($FullArr[$ReqValue[0]]); $j++) {
				if (!$Func) $Value=trim($FullArr[$ReqValue[0]][$j]);
				if ($ReqValue[1]=="<="&&$Value<=$ReqValue[2]) {$Return = false; break;}
				if ($ReqValue[1]==">="&&$Value>=$ReqValue[2]) {$Return = false; break;}
				if ($ReqValue[1]=="<"&&$Value<$ReqValue[2]) {$Return = false; break;}
				if ($ReqValue[1]==">"&&$Value>$ReqValue[2]) {$Return = false; break;}
				if ($ReqValue[1]=="="&&$Value==$ReqValue[2]) {$Return = false; break;}
				$Return = true;
			}
		}
	}
	if ($Return) return false;
	return true;
}

function DeleteKey($Id)
{
	global $Db, $nsProduct, $ClientsCntNow, $SitesCntNow, $BF, $Logs, $Lang;
	$Query = "SELECT * FROM ".PFX."_tracker_license WHERE ID = $Id";
	$Row=$Db->Select($Query);
	$Row->KEY_DECODE=$BF->decrypt($Row->LICENSE_KEY);
	$Row->License=GetLicenseText($Row->KEY_DECODE);
	if (!$Row->License) {
		$Logs->Err($Lang['CantDelete']);
		return;
	}

	if ($ClientsCntNow>1&&isset($Row->License['P'])&&$Row->License['P']==3) {
		$Logs->Err($Lang['DelClientsErr']);
		return;
	}

	if ( ($nsProduct->MAX_SITES-$Row->License['L'])<$SitesCntNow ) {
		$Logs->Err($Lang['DelSitesErr']);
		return;
	}

	$Query = "DELETE FROM ".PFX."_tracker_license WHERE ID = $Id";
	$Db->Query($Query);
	$nsProduct->Redir("license", "RDlt=1", "admin");
}




/////////////////////////////////////////////
///////// library section


?>