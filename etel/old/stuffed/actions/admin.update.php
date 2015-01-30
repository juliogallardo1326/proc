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
$PageTitle=$Lang['Title'];
$AdditionalOnload="";
$UpdatesArr=array();
$UpdateDescr=array();
$FirstUpdate="";
$UpdatesAvail=0;
$RunSql=false;
$UpdateVersion=true;
$AdditionalInclude=false;
$UseRedir=true;


$Done=(ValidVar($_REQUEST['Done']))?$_REQUEST['Done']:false;
$Run=(ValidVar($_REQUEST['Run']))?$_REQUEST['Run']:false;
$NoHands=(ValidVar($_REQUEST['NoHands']))?$_REQUEST['NoHands']:false;
$Silent=(ValidVar($_REQUEST['Silent']))?true:false;

/////////////////////////////////////////////
///////// call any process functions


$D=@opendir(SELF."/update");
while (($Row=@readdir($D))!==false) {
	if ($Row==".."||$Row=="."||$Row=="CVS") continue;
	if (CompareVersions($Row, $nsProduct->VERSION)<1) continue;
	if (CompareVersions($Row, $nsProduct->VERSION, 1)==1&&$nsProduct->LICENSE!=1) {
		$Logs->Alert($Lang['NextVersionKey']);
	}
	$UpdateDescr[$Row]="";
	$DescrFile="$Row.".$nsLang->CurrentLang.".html";
	if (@file_exists(SELF."/update/$Row/$DescrFile")) $UpdateDescr[$Row]=ReadFileData(SELF."/update/$Row/$DescrFile");
	elseif (@file_exists(SELF."/update/$Row/$Row.html")) $UpdateDescr[$Row]=ReadFileData(SELF."/update/$Row/$Row.html");
	
	$UpdatesArr[]=$Row;
}
@closedir($D);

if (count($UpdatesArr)>0) {
	sort($UpdatesArr);
	$UpdatesAvail=count($UpdatesArr);
	$FirstUpdate=$UpdatesArr[0];
}

$SubTitle=$Lang['UpdatesAvail'].$UpdatesAvail;


if ($Run&&$FirstUpdate&&!$nsUser->DEMO) {
	if (@file_exists(SELF."/update/$FirstUpdate/$FirstUpdate.php")) {
		include_once SELF."/update/$FirstUpdate/$FirstUpdate.php";
	}
	else $RunSql=true;

	if ($RunSql&&@file_exists(SELF."/update/$FirstUpdate/$FirstUpdate.sql")) {
		$Logs->ClearError();
		RunSqlArr(PrepareQueries(ReadFileData(SELF."/update/$FirstUpdate/$FirstUpdate.sql")));
		if($Logs->HaveErr()&&!$Silent) {
			$UseRedir=false;
			$UpdateVersion=false;
		}
	}

	if ($UpdateVersion) {
		$CurVersion=$FirstUpdate;
		$CurVersionArr=explode(".", $CurVersion);
		if (count($CurVersionArr)==2) $CurVersion=$FirstUpdate.".0";
		else $CurVersion=$FirstUpdate;
		$Query = "UPDATE ".PFX."_system_product SET VERSION='$CurVersion' WHERE ID=".$nsProduct->ID;
		$Db->Query($Query);
	}
	if ($UseRedir) {
		if ($NoHands&&$UpdatesAvail>1) $nsProduct->Redir("update", "Run=1&NoHands=$NoHands", "admin");
		$nsProduct->Redir("update", "Done=1", "admin");
	}
}


if($UpdatesAvail==0&&!$Done) $Logs->Msg($Lang['NoUpdateNeeded']);
if($Done) $Logs->Msg($Lang['UpdateComplete']);



/////////////////////////////////////////////
///////// display section here
include $nsTemplate->Inc("update/header.inc");
if (!$AdditionalInclude) include $nsTemplate->Inc();
else include $AdditionalInclude;
include $nsTemplate->Inc("update/footer.inc");

/////////////////////////////////////////////
///////// process functions here


/////////////////////////////////////////////
///////// library section


function PrepareQueries($SQL=false)
{
	if (!$SQL) return false;
	$SqlArr=array();
	$SQL = str_replace("{PREF}", PFX, $SQL);
	$SqlArr=explode(";", $SQL);
	for($i=0;$i<count($SqlArr);$i++) {
		$SqlArr[$i]=trim($SqlArr[$i]);
		if ($SqlArr[$i]=="") unset($SqlArr[$i]);
	}
	return $SqlArr;
}

function ReadFileData($File=false)
{
	if (!$File) return false;
	$f=@fopen($File, "r");
	$SQL = @fread($f, @filesize($File));
	@fclose($f);
	return $SQL;
}

function RunSqlArr($Arr=false)
{
	if (!$Arr) return false;
	global $Db;
	for($i=0;$i<count($Arr);$i++) {
		$Query=$Arr[$i];
		$Db->Query($Query);
	}
}

?>