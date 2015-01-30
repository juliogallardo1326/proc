<?
$NoForm=true;
$Messages=array();
$DbHost=(ValidVar($_REQUEST['DbHost']))?$_REQUEST['DbHost']:"localhost";
$DbPort=(ValidVar($_REQUEST['DbPort']))?$_REQUEST['DbPort']:"3306";
$DbName=(ValidVar($_REQUEST['DbName']))?$_REQUEST['DbName']:"";
$DbUser=(ValidVar($_REQUEST['DbUser']))?$_REQUEST['DbUser']:"";
$DbPass=(ValidVar($_REQUEST['DbPass']))?$_REQUEST['DbPass']:"";
$DbPref=(ValidVar($_REQUEST['DbPref']))?$_REQUEST['DbPref']:"ns";
$NoPrevStep=true;

$AlreadyEx=false;

$DbHost1 = ($DbPort) ? $DbHost.":".$DbPort : $DbHost;
$ID = @mysql_connect($DbHost1, $DbUser, $DbPass);
$SelectRes=@mysql_select_db($DbName, $ID);
Set40Mode();

$Query = "SELECT ID FROM ".$DbPref."_tracker_visitor_agent";
$Res=mysql_query($Query);
if ($Res) {
	$Check=mysql_fetch_row($Res);
	if (ValidVar($Check[0])) $AlreadyEx=true;
}


if (!$AlreadyEx) {

	$f=fopen("robots.sql", "r");
	$SQL = fread($f, filesize("robots.sql"));
	fclose($f);
	$SQL = str_replace("{PREF}", $DbPref, $SQL);
	$SQL = str_replace("{GRP_ID}", 15, $SQL);
	$SqlArr=split(";[\n\r]", $SQL);
	for($i=0;$i<count($SqlArr);$i++) {
		$SqlArr[$i]=trim($SqlArr[$i]);
		if ($SqlArr[$i]=="") unset($SqlArr[$i]);
	}
	for ($i=0;$i<count($SqlArr);$i++) {
		mysql_query($SqlArr[$i]);
	}
	$Messages[]=$Lang['WellImported'];
}
else $Messages[]=$Lang['AlreadyImported'];

?>