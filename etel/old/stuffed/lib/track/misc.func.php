<?
class NS_TRACK_MISC {

function ValidId(&$id)
{
    if ( !isset($id) )
        return false;

    if ( !is_integer($id) && !is_string($id) )
        return false;

    if ( is_string($id) && strval(intval($id)) != $id )
        return false;

    if ( !(intval($id) >= 0) )
        return false;

    return true;
}

function ValidVar(&$Var, $defval=false)
{
	if (empty($Var)) return $defval;
	return $Var;
}

function ValidArr(&$Arr)
{
	if (!isset($Arr)) return false;
	if (!is_array($Arr)) return false;
	return true;
}


function Redir($Url) 
{
  	Header("Location: $Url"); 
  	exit; 
}


function GetSettings($CompId=false, $SiteId=false)
{
	$Where=array();
	$Where[]="(COMPANY_ID=0 AND SITE_ID=0)";
	if (NS_TRACK_MISC::ValidId($CompId)) $Where[]="(COMPANY_ID=$CompId AND SITE_ID=0)";
	if (NS_TRACK_MISC::ValidId($SiteId)) $Where[]="SITE_ID=$SiteId";
	$WhereStr="WHERE ".implode(" OR ", $Where);
	$Pfx=(defined("NS_DB_PFX") )?NS_DB_PFX:PFX;
	global $_NS_TRACK_VARS;
	$QueryClass=(isset($_NS_TRACK_VARS['QueryClass']))?$_NS_TRACK_VARS['QueryClass']:"Query";
	$Query = "SELECT * FROM ".$Pfx."_tracker_config $WhereStr";
	$Sql = new $QueryClass($Query);
	$Sets=array();
	while ($Row=$Sql->Row()) {
		if ($Row->COMPANY_ID==0) $Sets['All']=$Row;
		if ($Row->COMPANY_ID>0&&$Row->SITE_ID==0) $Sets['Client']=$Row;
		if ($Row->COMPANY_ID>0&&$Row->SITE_ID>0) $Sets['Site']=$Row;
	}
	return $Sets;
}


function SetsByPrior($Arr, $Var)
{
	if (isset($Arr['Site']->$Var)&&$Arr['Site']->$Var!=2) return $Arr['Site']->$Var;
	if (isset($Arr['Client']->$Var)&&$Arr['Client']->$Var!=2) return $Arr['Client']->$Var;
	if (isset($Arr['All']->$Var)) return $Arr['All']->$Var;
	return false;
}

function TimeDblSettings($Arr, $Var, $Time)
{
	if (isset($Arr['Site']->$Var)&&$Arr['Site']->$Var!=2) return $Arr['Site']->$Time;
	if (isset($Arr['Client']->$Var)&&$Arr['Client']->$Var!=2) return $Arr['Client']->$Time;
	if (isset($Arr['All']->$Var)) return $Arr['All']->$Time;
	return false;	
}


function ValidIp($IP) {
	$Num="([0-9]|[0-9]{2}|1\d\d|2[0-4]\d|25[0-5])";
	return preg_match("/^$Num\.$Num\.$Num\.$Num$/", $IP);
}



function GetMicrotime() 
{ 
    list($usec, $sec) = explode(" ", microtime()); 
    return ((float)$usec + (float)$sec); 
}



function ToLower($str)
{
	if (!$str) return false;
	$U_alph = "יצףךוםדרשחץתפûגאןנמכהז‎ÿקסלטעüב‏¸qwertyuiopasdfghjklzxcvbnm";
	$L_alph = "ֹײ׃Êֳֵֽ״ÙַױÚװÛְֲֿ׀־ִֶֻÝß׳ָּׁׂÜֱÞ¨QWERTYUIOPASDFGHJKLZXCVBNM";
	return strtr($str, $L_alph, $U_alph);
}


function CookieStorageSet($Name=false, $Value=false, $Expire=false, $Path=false, $Domain=false, $Secure=false) {
	if (!$Name) return false;
	global $nsUser, $_COOKIE, $_NS_TRACK_VARS;
	if (isset($_NS_TRACK_VARS['COOKIE_DOMAIN'])) $Domain=$_NS_TRACK_VARS['COOKIE_DOMAIN'];
	$CookieArr=NS_TRACK_MISC::ValidVar($_COOKIE[COOKIE_PFX.'storage']);
	if (!NS_TRACK_MISC::ValidVar($CookieArr)) $CookieArr=array();
	else $CookieArr=@unserialize($CookieArr);
	if (!is_array($Name)) $Arr[$Name]=$Value;
	else $Arr=$Name;
	foreach($Arr as $Name=>$Value) $CookieArr[$Name]=$Value;
	$CookieArr=serialize($CookieArr);
	$_COOKIE[COOKIE_PFX.'storage']=$CookieArr;
	$nsUser->SetCookie(COOKIE_PFX.'storage', $CookieArr, $Expire, $Path, $Domain, $Secure);
}

function CookieStorageGet($Name=false) {
	if (!$Name) return false;
	global $_COOKIE;
	$CookieArr=NS_TRACK_MISC::ValidVar($_COOKIE[COOKIE_PFX.'storage']);
	if (!NS_TRACK_MISC::ValidVar($CookieArr)) return false;
	else $CookieArr=@unserialize($CookieArr);
	return NS_TRACK_MISC::ValidVar($CookieArr[$Name]);
}



function escape_string($string){
	if (is_null($string) || $string===false){
			 return null;
	}		 
   if(version_compare(phpversion(),"4.3.0")=="-1") {
     return mysql_escape_string($string);
   } else {
     return mysql_real_escape_string($string);
   }
}

function ns_my_url(){
	return 'http'.((strtolower(ValidVar($_SERVER["HTTPS"]))=="on") ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

}
?>