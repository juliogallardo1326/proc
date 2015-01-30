<?

if (!isset($_REQUEST['NoProcess'])) {

$_REQUEST['NoProcess']=1;

$NoForm=true;
$CallName="tracker";
$AltDomains="";
$License="";
$StartTime=0;
$DbHost=(ValidVar($_REQUEST['DbHost']))?$_REQUEST['DbHost']:"localhost";
$DbPort=(ValidVar($_REQUEST['DbPort']))?$_REQUEST['DbPort']:"3306";
$DbName=(ValidVar($_REQUEST['DbName']))?$_REQUEST['DbName']:"";
$DbUser=(ValidVar($_REQUEST['DbUser']))?$_REQUEST['DbUser']:"";
$DbPass=(ValidVar($_REQUEST['DbPass']))?$_REQUEST['DbPass']:"";
$DbPref=(ValidVar($_REQUEST['DbPref']))?$_REQUEST['DbPref']:"ns";
$SendUsage=(ValidVar($_REQUEST['SendUsage']))?$_REQUEST['SendUsage']:'0';


$DbHost1 = ($DbPort) ? $DbHost.":".$DbPort : $DbHost;
$ID = @mysql_connect($DbHost1, $DbUser, $DbPass);
$SelectRes=@mysql_select_db($DbName, $ID);
Set40Mode();


if (!ValidVar($_REQUEST['Trial'])) {
	$License = "INSERT INTO `".$DbPref."_tracker_license` (`ID`, `KEY_ID`, `LICENSE_KEY`, `STAMP`) VALUES ";
	$License .= "(1, ".$_REQUEST['LcID'].", '".$_REQUEST['LKey']."', NOW());";
}


/////////////////////////////////////////////////
$f=fopen("conf.sample", "r");
$Conf=fread($f, filesize("conf.sample"));
fclose($f);
$ModR=(ValidVar($_REQUEST['UseModR']))?"true":"false";
$Conf=str_replace("{C_LANG}",$CLang, $Conf);
$Conf=str_replace("{PFX}",$_REQUEST['DbPref'], $Conf);
$Conf=str_replace("{MOD_R}",$ModR, $Conf);
$Conf=str_replace("{DB_NAME}",$_REQUEST['DbName'], $Conf);
$Conf=str_replace("{DB_HOST}",$_REQUEST['DbHost'], $Conf);
$Conf=str_replace("{DB_PASS}",$_REQUEST['DbPass'], $Conf);
$Conf=str_replace("{DB_USER}",$_REQUEST['DbUser'], $Conf);
$Conf=str_replace("{DB_PORT}",$_REQUEST['DbPort'], $Conf);

$f=fopen("../conf.vars.php", "a+");
@flock($f, LOCK_EX); 
@ftruncate($f, 0);
@fwrite($f, $Conf);
@flock($f, LOCK_UN);
@fclose($f);




/////////////////////////////////////////////////
$f=fopen("ns.sql", "r");
$SQL = fread($f, filesize("ns.sql"));
fclose($f);
$SQL = str_replace("{PREF}", $DbPref, $SQL);
$SqlArr=explode(";", $SQL);
for($i=0;$i<count($SqlArr);$i++) {
	$SqlArr[$i]=trim($SqlArr[$i]);
	if ($SqlArr[$i]=="") unset($SqlArr[$i]);
}

/////////////////////////////////////////////////
$f=fopen("insert.sql", "r");
$SQL = fread($f, filesize("insert.sql"));
fclose($f);
$SQL = str_replace("{PREF}", $DbPref, $SQL);
$SQL = str_replace("{P_VERSION}", $ProductVersion, $SQL);
$SQL = str_replace("{SEND_USAGE}", $SendUsage, $SQL);
$SQL = str_replace("{REG_LOGIN}", $_REQUEST['RegLogin'], $SQL);
$SQL = str_replace("{REG_PASS}", md5($_REQUEST['RegPass']), $SQL);
$SQL = str_replace("{REG_NAME}", addslashes($_REQUEST['RegName']), $SQL);
$SQL = str_replace("{REG_EMAIL}", $_REQUEST['RegEmail'], $SQL);
$SQL = str_replace("{C_LANG}", $CLang, $SQL);
$SQL = str_replace("{PRODUCT_NAME}", "Stuffed Tracker", $SQL);
$SQL = str_replace("{FOLDER}", "tracker", $SQL);
$SQL = str_replace("{CLIENT_NAME}", addslashes($_REQUEST['CompName']), $SQL);
$SQL = str_replace("{CLIENT_DESCR}", addslashes($_REQUEST['CompDescr']), $SQL);
$SQL = str_replace("{SITE_DOMAIN}", $_REQUEST['SiteDomain'], $SQL);
$SQL = str_replace("#{LICENSE}", $License, $SQL);

$SqlArr2=explode(";", $SQL);
for($i=0;$i<count($SqlArr2);$i++) {
	$SqlArr2[$i]=trim($SqlArr2[$i]);
	if ($SqlArr2[$i]=="") unset($SqlArr2[$i]);
}



for ($i=0;$i<count($SqlArr);$i++) {
	mysql_query($SqlArr[$i]);
}
for ($i=0;$i<count($SqlArr2);$i++) {
	mysql_query($SqlArr2[$i]);
}

}

$PageTitle=$Lang['InstallPage'];
//$NoButtons=true;
$NoPrevStep=true;

function ReplaceProtocol($Domain=false)
{
	return preg_replace("/^.+:\/\//", "", $Domain);
}
?>