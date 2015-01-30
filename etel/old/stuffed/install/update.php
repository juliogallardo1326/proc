<?

function ValidVar(&$Var, $defval=false)
{
	if (empty($Var)) return $defval;
	return $Var;
}


$DbHost=(ValidVar($_REQUEST['DbHost']))?$_REQUEST['DbHost']:"localhost";
$DbPort=(ValidVar($_REQUEST['DbPort']))?$_REQUEST['DbPort']:"3306";
$DbName=(ValidVar($_REQUEST['DbName']))?$_REQUEST['DbName']:"";
$DbUser=(ValidVar($_REQUEST['DbUser']))?$_REQUEST['DbUser']:"";
$DbPass=(ValidVar($_REQUEST['DbPass']))?$_REQUEST['DbPass']:"";
$DbPref=(ValidVar($_REQUEST['DbPref']))?$_REQUEST['DbPref']:"ns";
$CLang=(ValidVar($_REQUEST['CLang']))?$_REQUEST['CLang']:"en";
$ModR=(ValidVar($_REQUEST['UseModR']))?"true":"false";


require "../conf.path.php";

$CallName=$ProdPath;
$CallName=str_replace("/install", "", $CallName);
if (substr($CallName, 0, 1)=="/") $CallName=substr($CallName, 1);
$UseModR=($ModR=="true")?true:false;

/////////////////////////////////////////////////
$f=fopen("conf.sample", "r");
$Conf=fread($f, filesize("conf.sample"));
fclose($f);
$ModR=(ValidVar($_REQUEST['UseModR']))?"true":"false";
$Conf=str_replace("{C_LANG}",$CLang, $Conf);
$Conf=str_replace("{PFX}",$_REQUEST['DbPref'], $Conf);
//$Conf=str_replace("{SYS_PATH}",$SYS_PATH, $Conf);
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


Header("Location: ".getURL("update", "admin"));
exit();



function GetUrl($Action=false, $Section=false)
{
	global $UseModR;
	if (!$Action) $Action="default";
	if (!$Section) $Section="track";
	if ($UseModR) if (strlen($Section)) $Section.="/";
	if (!$UseModR) return SelfUrl()."sc=$Section&action=$Action&";
	return SelfUrl()."/$Section$Action.html";
}

function SelfUrl()
{
	global $UseModR, $_SERVER, $CallName;
	if (!$UseModR) return "http://".$_SERVER['HTTP_HOST']."/$CallName/index.php?";
	return "http://".$_SERVER['HTTP_HOST']."/$CallName";
}

?>