<?
$PageTitle="";
$Finished=false;
$DisableNext=false;
$Errors=array();
$Messages=array();
$Lang=array();
$SaveVars=array();
$NoButtons=false;
$NoPrevStep=false;
$AdditionalOnload=false;

function ValidVar(&$Var, $defval=false)
{
	if (empty($Var)) return $defval;
	return $Var;
}

function CompareVersions($Str1="", $Str2="", $Position=false)
{
	if (!$Str1&&!$Str2) return 0;
	if (!$Position&&function_exists("version_compare")) {
		return version_compare($Str1, $Str2);
	}
	if (!$Str2) return 1;
	if (!$Str1) return -1;
	if ($Str1==$Str2) return 0;

	$Arr1=explode(".", $Str1);
	$Arr2=explode(".", $Str2);

	if (count($Arr1)!=count($Arr2)) {
		if (count($Arr1)>count($Arr2)) {
			for ($i=count($Arr2);$i<count($Arr1);$i++) $Arr2[$i]=0;
		}
		else {
			for ($i=count($Arr1);$i<count($Arr2);$i++) $Arr1[$i]=0;
		}
	}
	$iStop=($Position)?$Position:count($Arr1);
	for ($i=0;$i<$iStop;$i++) {
		$V1=intval($Arr1[$i]);
		$V2=intval($Arr2[$i]);
		if ($V1==$V2) continue;
		if ($V1<$V2) return -1;
		if ($V1>$V2) return 1;
	}

	return 0;
}





function Set40Mode()
{
	$Query = "SELECT VERSION() as VERSION";
	$res=@mysql_query($Query);
	$Version=@mysql_fetch_object($res);
	if (CompareVersions($Version->VERSION, "4.1") == 1) {
		@mysql_query("SET SESSION sql_mode='MYSQL40' ");
	}
}



function NextStep()
{
	global $StepArr, $CurrentStep, $Step, $SaveVars, $_REQUEST, $PageTitle;
	$Step++;
	$CurrentStep=$StepArr[$Step];
	$PageTitle=$CurrentStep['Name'];
	for($i=0;$i<count($SaveVars);$i++) {
		$Key=$SaveVars[$i];
		$_REQUEST[$Key]=$GLOBALS[$Key];
	}
	$SaveVars=array();
}


if (is_dir("../system")) $SysPath="../";
elseif (is_dir("../../system")) $SysPath="../../";


require "lang.arr.php";

require "../conf.path.php";
//if (@file_exists("../conf.vars.php")) include "../conf.vars.php";
//if (defined("INSTALLED")&&INSTALLED) exit();


$CLang=(ValidVar($_REQUEST['CLang']))? $_REQUEST['CLang']:$DefaultLang;
$Lang=$Lang[$CLang];


$Trial=(ValidVar($_REQUEST['Trial']))? $_REQUEST['Trial']:0;
$LcL=(ValidVar($_REQUEST['LcL']))? $_REQUEST['LcL']:0;
$LcP=(ValidVar($_REQUEST['LcP']))? $_REQUEST['LcP']:0;
//$LcL2=(ValidVar($_REQUEST['LcL2']))? $_REQUEST['LcL2']:0;



require "step_info.arr.php";

if ($CLang&&isset($Charset[$CLang])) header('Content-Type:text/html; charset='.$Charset[$CLang]);

$Step=(ValidVar($_REQUEST['Step']))? $_REQUEST['Step']:0;
$PrevStep=(ValidVar($_REQUEST['PrevStep']))? $_REQUEST['PrevStep']:0;
$FormClicked=(ValidVar($_REQUEST['FormClicked']))? $_REQUEST['FormClicked']:0;
$Dir=(ValidVar($_REQUEST['Dir']))? $_REQUEST['Dir']:0;


$TmpStep=$Step;
$CurrentStep=$StepArr[$Step];
$PageTitle=$CurrentStep['Name'];

unset($_REQUEST['Step']);
unset($_REQUEST['ns_uid']);
unset($_REQUEST['ns_pwd']);
unset($_REQUEST['PHPSESSID']);
unset($_REQUEST['FormClicked']);
unset($_REQUEST['Dir']);



if(@file_exists($CurrentStep['Folder']."/func.php")) {
	include_once $CurrentStep['Folder']."/func.php";

	for($i=0;$i<count($SaveVars);$i++) {
		$Key=$SaveVars[$i];
		unset($_REQUEST[$Key]);
	}

	if (ValidVar($CurrentStep['ValidateFunc'])&&$Dir!=1&&$FormClicked) {
		$CallFunc=$CurrentStep['ValidateFunc'];
		if (@function_exists($CallFunc)) $CallFunc();
	}
}
elseif ($PrevStep==$Step&&$FormClicked) NextStep();

if ($TmpStep!=$Step) {
	if(@file_exists($CurrentStep['Folder']."/func.php")) {
		include_once $CurrentStep['Folder']."/func.php";
		for($i=0;$i<count($SaveVars);$i++) {
			$Key=$SaveVars[$i];
			unset($_REQUEST[$Key]);
		}
	}
}



$PrevTitle=(isset($StepArr[$Step-1]['Name']))?($Lang['Step']." ".($Step).": ".$StepArr[$Step-1]['Name']):"";
$NextTitle=(isset($StepArr[$Step+1]['Name']))?($Lang['Step']." ".($Step+2).": ".$StepArr[$Step+1]['Name']):"";



if (@file_exists("header.inc.php")) include "header.inc.php";
if (@file_exists($CurrentStep['Folder']."/index.php")) include $CurrentStep['Folder']."/index.php";
if (@file_exists("footer.inc.php")) include "footer.inc.php";

?>