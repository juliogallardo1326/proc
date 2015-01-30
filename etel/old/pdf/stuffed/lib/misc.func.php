<?

function ExtendUser()
{
	global $nsUser, $Db, $nsProduct, $CpId; 
	global $_COOKIE;
	$nsUser->ADMIN=false;
	$nsUser->MERCHANT=false;
	$nsUser->SUPER_USER=false;
	$nsUser->SUPER_ADMIN=false;
	$nsUser->DEMO=false;
	$nsUser->EditFile=false;
	$nsUser->UserInfo['NAME']=stripslashes($nsUser->UserInfo['NAME']);

	$Query = "SELECT * FROM ".PFX."_tracker_user_settings WHERE USER_ID=".$nsUser->UserId();
	$Check=$Db->Select($Query);
	$nsUser->ADVANCED_MODE=$Check->ADVANCED_MODE;
	$nsUser->HELP_MODE=$Check->HELP_MODE;
	$nsUser->TZ=$Check->TIMEZONE;
	$nsUser->DEF_LOGS_ORDER=$Check->DEF_PATH_ORDER;
	$nsUser->AUTO_TZ=false;
	$nsUser->ENC=$Check->PAGE_ENCODING;
	if ($nsUser->TZ!="a"&&ValidVar($_COOKIE[COOKIE_PFX.'auto_tz'])) {
		$nsUser->SetCookie(COOKIE_PFX.'auto_tz', '0', time()-1000, '/');
		unset($_COOKIE[COOKIE_PFX.'auto_tz']);
	}	

	if ($nsUser->TZ=="a")  {
		$nsUser->TZ=ValidVar($_COOKIE[COOKIE_PFX.'auto_tz']);
		$nsUser->AUTO_TZ=true;
	}

	if (!$nsUser->TZ) $nsUser->TZ="0";

	$Query = "SELECT * FROM ".PFX."_tracker_user WHERE USER_ID = ".$nsUser->UserId();
	$Check=$Db->Select($Query);
	if (isset($Check->ID)&&ValidId($Check->ID)) {
		$nsUser->SUPER_USER=$Check->SUPER_USER;
		$nsUser->COMPANY_ID=$Check->COMPANY_ID;
		$nsUser->MERCHANT=true;
		$nsUser->DEMO=ValidVar($Check->DEMO);
		$nsUser->EditFile="users";
		$CpId=$nsUser->COMPANY_ID;
		return;
	}

	$Query = "SELECT * FROM ".PFX."_tracker_admin WHERE USER_ID = ".$nsUser->UserId();
	$Check=$Db->Select($Query);
	if (isset($Check->ID)&&ValidId($Check->ID)) {
		$nsUser->SUPER_ADMIN=$Check->SUPER_ADMIN;
		$nsUser->DEMO=$Check->DEMO;
		$nsUser->ADMIN=true;
		$nsUser->EditFile="agents";

		if ($nsProduct->LICENSE!=3) {
			$nsUser->ADMIN=false;
			$nsUser->SUPER_ADMIN=false;
			$nsUser->MERCHANT=true;
			$nsUser->SUPER_USER=true;
			$nsUser->COMPANY_ID=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_client");
		}
		return;
	}
}


function PrepareUserOrder($Str='', $UserValue=false, $Mode=false) 
{
	global $Db, $nsUser;
	$Mode=strtoupper($Mode);
	if (!$Str && !$UserValue) $UserValue="DESC";
	$Arr=explode(";", $Str);
	$ReturnArr=array();
	foreach ($Arr as $i=>$Val) {
		$Arr2=explode(":",$Val);
		if (isset($Arr2[0]) && ($Arr2[0]=="ASC" || $Arr2[0]=="DESC")) continue;
		if (isset($Arr2[0]) && $Arr2[0]==$Mode) {
			if ($UserValue) $Arr2[1]=$UserValue;
			if (!$UserValue && isset($Arr2[1])) $UserValue=$Arr2[1];
		}
		if (isset($Arr2[0]) && isset($Arr2[1])) $ReturnArr[$Arr2[0]]=$Arr2[1];
	}
	if (!isset($ReturnArr[$Mode]) || !$ReturnArr[$Mode]) $ReturnArr[$Mode]="DESC";
	
	$Str="";
	$i=0;
	foreach ($ReturnArr as $K=>$V) {
		if ($i>0) $Str.=";";
		$Str.="$K:$V";
		$i++;
	}

	$Query = "UPDATE ".PFX."_tracker_user_settings SET DEF_PATH_ORDER = '$Str' WHERE USER_ID = ".$nsUser->UserId();
	$Db->Query($Query);

	return $ReturnArr[$Mode];
}


function CookieStorageSet($Name=false, $Value=false, $Expire=false, $Path=false, $Domain=false, $Secure=false) {
	if (!$Name) return false;
	global $nsUser, $_COOKIE;
	$CookieArr=ValidVar($_COOKIE[COOKIE_PFX.'storage']);
	if (!ValidVar($CookieArr)) $CookieArr=array();
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
	$CookieArr=ValidVar($_COOKIE[COOKIE_PFX.'storage']);
	if (!ValidVar($CookieArr)) return false;
	else $CookieArr=@unserialize($CookieArr);
	return ValidVar($CookieArr[$Name]);
}

function CookieStorageRemove($Name=false, $Expire=false, $Path=false, $Domain=false, $Secure=false) {
	if (!$Name) return false;	
	global $nsUser, $_COOKIE;
	$CookieArr=ValidVar($_COOKIE[COOKIE_PFX.'storage']);
	if (!ValidVar($CookieArr)) $CookieArr=array();
	else $CookieArr=@unserialize($CookieArr);
	unset($CookieArr[$Name]);
	$CookieArr=serialize($CookieArr);
	$_COOKIE[COOKIE_PFX.'storage']=$CookieArr;
	$nsUser->SetCookie(COOKIE_PFX.'storage', $CookieArr, $Expire, $Path, $Domain, $Secure);	
}

function UserColumns()
{
	global $Db, $nsUser;
	$Query = "SELECT * FROM ".PFX."_tracker_user_column WHERE USER_ID=".$nsUser->UserId();
	$nsUser->Columns=$Db->Select($Query);
}


function ShowCost($Value=0, $Arr=false)
{
	global $CurrentCompany;
	if (!$Arr) $Arr=ValidVar($CurrentCompany->CUR);
	if (!$Arr||!$Arr[0]||!$Value) return $Value;
	if ($Arr[1]==0) return $Arr[0].$Value;
	if ($Arr[1]==1) return $Value.$Arr[0];
}


function GetSettings($CompId=false, $SiteId=false)
{
	$Where=array();
	$Where[]="(COMPANY_ID=0 AND SITE_ID=0)";
	if (ValidId($CompId)) $Where[]="(COMPANY_ID=$CompId AND SITE_ID=0)";
	if (ValidId($SiteId)) $Where[]="SITE_ID=$SiteId";
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


function GetCurrentCompany($Id=false)
{
	if (!ValidId($Id)) return false;
	global $Db, $nsUser, $nsProduct;
	$Company = $Db->Select("SELECT * FROM ".PFX."_tracker_client WHERE ID = $Id");
	if (!$Company) {
		$nsUser->SetCookie("CompId", '', time()-1000, "/");
		$nsProduct->Redir("default", "", "admin");
	}
	$Company->SITE_CNT=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site WHERE COMPANY_ID=$Id");
	$Company->CUR[0]=$Company->CURRENCY;
	$Company->CUR[1]=$Company->CURRENCY_POSITION;
	return $Company;
}



function ValidIp($IP) {
	$Num="([0-9]|[0-9]{2}|1\d\d|2[0-4]\d|25[0-5])";
	return preg_match("/^$Num\.$Num\.$Num\.$Num$/", $IP);
}

function ValidIpTempl($IP) {
	$Num="([0-9]|[0-9]{2}|1\d\d|2[0-4]\d|25[0-5]|\*)";
	return preg_match("/^$Num\.$Num\.$Num\.$Num$/", $IP);
}


function UpdatesAvailable()
{
	global $nsProduct;
	$Avail=0;
	$Necessary=false;
	$D=@opendir(SELF."/update");
	while (($Row=@readdir($D))!==false) {
		if ($Row==".."||$Row=="."||$Row=="CVS") continue;
		if (CompareVersions($Row, $nsProduct->VERSION)<1) continue;
		$Necessary=true;
		$Avail++;
	}
	@closedir($D);
	if ($Necessary) $nsProduct->Redir("update", "", "admin");
	return $Avail;
}

function UserDate() 
{
	global $Db, $nsUser;
	$GmTime = gmdate("Y-m-d H:i:s");
	return $Db->ReturnValue("SELECT DATE_FORMAT(DATE_ADD('$GmTime', INTERVAL '".$nsUser->TZ."' HOUR), '%Y-%m-%d')");
}


function GetMicrotime() 
{ 
    list($usec, $sec) = explode(" ", microtime()); 
    return ((float)$usec + (float)$sec); 
}

function GetResTime(){ global $StartTime; return GetMicrotime()-$StartTime; }
function PrintResTime($line=0){ echo ($line!=0?'line '.$line:'').": ".GetResTime().'<br>'; }


?>