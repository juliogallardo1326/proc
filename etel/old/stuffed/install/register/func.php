<?

require $SysPath."/system/lib/validate.func.php";

$RegLogin=(ValidVar($_REQUEST['RegLogin']))?$_REQUEST['RegLogin']:false;
$RegPass=(ValidVar($_REQUEST['RegPass']))?$_REQUEST['RegPass']:false;
$RegPass2=(ValidVar($_REQUEST['RegPass2']))?$_REQUEST['RegPass2']:false;
$RegName=(ValidVar($_REQUEST['RegName']))?$_REQUEST['RegName']:false;
$RegEmail=(ValidVar($_REQUEST['RegEmail']))?$_REQUEST['RegEmail']:false;

$SaveVars=array("RegLogin", "RegPass", "RegPass2", "RegName", "RegEmail");


function CheckReg()
{
	global $RegLogin, $RegPass, $RegPass2, $RegName, $RegEmail;
	global $Errors, $Lang, $CLang;

	$RegLogin=ToLower($RegLogin);
	if (CheckSymb_($RegLogin)) {$Errors[]=$Lang['SymbErr']; return; }
	if (CheckSymb_($RegPass)) {$Errors[]=$Lang['SymbErr']; return; }

	if (!$RegLogin) {$Errors[]=$Lang['MustFillLogin']; return; }
	if (!$RegPass) {$Errors[]=$Lang['MustFillPass']; return; }
	if (!$RegName) {$Errors[]=$Lang['MustFillName']; return; }
	if (!$RegEmail){ $Errors[]=$Lang['MustFillEmail']; return; }

	if ($RegPass!=$RegPass2) {$Errors[]=$Lang['PassNotPass2']; return; }
	if (strlen($RegLogin)<3) {$Errors[]=$Lang['LoginTooShort']; return; }
	if (strlen($RegLogin)>64) {$Errors[]=$Lang['LoginTooLong']; return; }
	if (strlen($RegPass)<3) {$Errors[]=$Lang['PassTooShort']; return; }
	if (strlen($RegPass)>64) {$Errors[]=$Lang['PassTooLong']; return; }
	if (!ValidMail($RegEmail)) {$Errors[]=$Lang['MustFillCorrEmail']; return; }

	NextStep();
}


?>