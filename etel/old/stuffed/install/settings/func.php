<?


$DbHost=(ValidVar($_REQUEST['DbHost']))?$_REQUEST['DbHost']:"localhost";
$DbPort=(ValidVar($_REQUEST['DbPort']))?$_REQUEST['DbPort']:"3306";
$DbName=(ValidVar($_REQUEST['DbName']))?$_REQUEST['DbName']:"";
$DbUser=(ValidVar($_REQUEST['DbUser']))?$_REQUEST['DbUser']:"";
$DbPass=(ValidVar($_REQUEST['DbPass']))?$_REQUEST['DbPass']:"";
$DbPref=(ValidVar($_REQUEST['DbPref']))?$_REQUEST['DbPref']:"ns";
$UseModR=(ValidVar($_REQUEST['UseModR']))?$_REQUEST['UseModR']:0;
$SendUsage=(ValidVar($_REQUEST['SendUsage']))?$_REQUEST['SendUsage']:0;

$UpdateNeeded=false;
$AdditionalOnload="";
$DisableModR=false;

$SaveVars=array("DbHost", "DbPort", "DbName", "DbUser", "DbPass", "DbPref", "UseModR", "SendUsage");

require_once $SysPath."system/lib/server.func.php";
if (!server_soft(true)) {
	$UseModR=false;
	$DisableModR=true;
}

function CheckSettings()
{
	global $DbHost, $DbPort, $DbName, $DbUser, $DbPass, $DbPref, $UpdateNeeded;
	global $Errors, $Lang, $CLang;
	$DbHost1 = ($DbPort) ? $DbHost.":".$DbPort : $DbHost;
	$ID = @mysql_connect($DbHost1, $DbUser, $DbPass);
	if (!$ID) {
		$Errors[]=$Lang['ConnectFailed'];
		return false;
	}
	$SelectRes=@mysql_select_db($DbName, $ID);
	if (!$SelectRes) {
		$Errors[]=$Lang['SelectFailed'];
		return false;
	}
	$Query = "SELECT * FROM ".$DbPref."_system_product";
	$SelectRes=mysql_query($Query);
	if ($SelectRes) {
		$Check=mysql_fetch_row($SelectRes);
		if (ValidVar($Check[0])||!mysql_error()) {
			$Errors[]=$Lang['PfxAlreadyExists'];
			$UpdateNeeded=true;
			return false;
		}
	}
	NextStep();
}


?>