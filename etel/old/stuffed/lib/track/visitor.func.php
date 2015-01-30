<?

class NS_TRACK_VISITOR {


function GetVisitorId()
{
	global $nsUser, $nsProduct;

	global $_NS_TRACK_VARS, $_COOKIE;
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$IpId=&$_NS_TRACK_VARS['IpId'];
	$AgentId=&$_NS_TRACK_VARS['AgentId'];
	$PresetID=&$_NS_TRACK_VARS['PresetID'];
	$PresetIP=&$_NS_TRACK_VARS['PresertIP'];
	$Settings=&$_NS_TRACK_VARS['Settings'];
	$ScrRes=&$_NS_TRACK_VARS['ScrRes'];
	$PxD=&$_NS_TRACK_VARS['PxD'];
	$FlVer=&$_NS_TRACK_VARS['FlVer'];
	$CheckCookieId=&$_NS_TRACK_VARS['CheckCookieId'];
	$Db=&$_NS_TRACK_VARS['Db'];
	$FindLastNode=&$_NS_TRACK_VARS['FindLastNode'];


	$UserAgent=NS_TRACK_MISC::ValidVar($_SERVER['HTTP_USER_AGENT']);
	if (!trim($UserAgent)) {
		$Skip=true;
		return 0;
	}
	$AgentId=NS_TRACK_VISITOR::GetUserAgentId($UserAgent);
	$Visitor=false;
	$CookieId=false;
	$CountryId=0;
	$ScrRes=addslashes($ScrRes);
	$PxD=addslashes($PxD);
	$FlVer=addslashes($FlVer);
	if (strpos($FlVer, "<")!==false) $FlVer=substr($FlVer, 0, strpos($FlVer, "<"));
	if (!$FlVer) $FlVer="-1";
	if ($FlVer!="-1") $FlVer=substr($FlVer, 0, 1);
	if (!$ScrRes || $ScrRes == "x" || $ScrRes==0) $ScrRes="-1";
	if (!$PxD) $PxD="-1";



	if (isset($_COOKIE[NS_COOKIE_PFX.'tmp_skip'])) {
		NS_TRACK_GENERAL::TrackingCookie(NS_COOKIE_PFX.'tmp_skip', '0',time()-60, '/');
		$Skip=true;
	}

	if ($Skip) return 0;

	$Stamp=gmdate("Y-m-d H:i:s", time());
	$UserIp=NS_TRACK_VISITOR::GetUserIp();
	$IpId=NS_TRACK_VISITOR::GetIpId($UserIp);
	if ($Skip) return 0;

	if (NS_TRACK_MISC::ValidVar($PresetID)&&NS_TRACK_MISC::ValidId($PresetID)) {
		$Visitor=$Db->Select("SELECT * FROM ".NS_DB_PFX."_tracker_visitor WHERE ID = $PresetID");
		if (NS_TRACK_MISC::ValidId($Visitor->ID)) {
			$AgentId=($Visitor->LAST_AGENT_ID)?$Visitor->LAST_AGENT_ID:$AgentId;
			return $PresetID;
		}
	}

	if (!NS_TRACK_MISC::ValidVar($PresetID)||!NS_TRACK_MISC::ValidVar($Visitor->ID)) {


		if (defined("NS_PHP_TRACKING") 
			&& $Settings['All']->IP_TRACKING 
			&& $Settings['All']->IP_NO_COOKIE
			&& count($_COOKIE) > 0 ) {

			$Settings['All']->IP_TRACKING=false;
		}

		if (!defined("NS_PHP_TRACKING")) {
			if (!$CheckCookieId && !isset($_COOKIE[NS_COOKIE_PFX.'visitor']) && 
				$Settings['All']->IP_TRACKING && $Settings['All']->IP_NO_COOKIE) {
				$CookieId=NS_TRACK_VISITOR::TrackingVisitorCookie();
				NS_TRACK_MISC::Redir(NS_TRACK_MISC::ns_my_url()."&CheckCookieId=$CookieId");
			}

			if ($CheckCookieId && isset($_COOKIE[NS_COOKIE_PFX.'visitor']) && 
				$_COOKIE[NS_COOKIE_PFX.'visitor']==$CheckCookieId) {
				$Settings['All']->IP_TRACKING=false;
			}
		}

		if (!isset($_COOKIE[NS_COOKIE_PFX.'visitor']) && $Settings['All']->IP_TRACKING) {
			$Visitor=NS_TRACK_VISITOR::FindByIp($IpId, $AgentId, $Settings['All']->IP_PERIOD);
			if ($Visitor) {
				$CookieId=NS_TRACK_VISITOR::TrackingVisitorCookie($Visitor->COOKIE_ID);
				NS_TRACK_VISITOR::FindLastNode($Visitor->ID);
				$FindLastNode=false;
			}
		}
		if (!$CookieId) $CookieId=NS_TRACK_VISITOR::TrackingVisitorCookie();
		if (!$Visitor) {
			$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_visitor WHERE COOKIE_ID='$CookieId'";
			$Visitor=$Db->Select($Query);
		}
	}

	if ($Visitor && NS_TRACK_MISC::ValidId($Visitor->ID) ) {
		if (!$Visitor->FIRST_COUNTRY_ID) $CountryId=NS_TRACK_VISITOR::GetCountryId($UserIp);
		else $CountryId=$Visitor->FIRST_COUNTRY_ID;
		if ((!$ScrRes || $ScrRes=="-1") && $Visitor->LAST_RESOLUTION) $ScrRes=$Visitor->LAST_RESOLUTION;
		if ((!$PxD || $PxD=="-1") && $Visitor->PIXEL_DEPTH) $PxD=$Visitor->PIXEL_DEPTH;
		if ((!$FlVer || $FlVer=="-1") && $Visitor->FLASH_VERSION) $FlVer=$Visitor->FLASH_VERSION;
		$Query = "UPDATE ".NS_DB_PFX."_tracker_visitor SET LAST_IP_ID = $IpId, LAST_AGENT_ID=$AgentId, LAST_STAMP='$Stamp', FIRST_COUNTRY_ID='$CountryId', LAST_RESOLUTION = '$ScrRes', PIXEL_DEPTH='$PxD', FLASH_VERSION='$FlVer' WHERE ID = ".$Visitor->ID;
		$Db->Query($Query);
		NS_TRACK_VISITOR::TrackingVisitorCookie($Visitor->COOKIE_ID);
		return $Visitor->ID;
	}

	$CountryId=NS_TRACK_VISITOR::GetCountryId($UserIp);
	$Query ="INSERT INTO ".NS_DB_PFX."_tracker_visitor (COOKIE_ID, LAST_IP_ID, LAST_AGENT_ID, FIRST_STAMP, LAST_STAMP, FIRST_COUNTRY_ID, LAST_RESOLUTION, PIXEL_DEPTH, FLASH_VERSION) VALUES ('$CookieId', $IpId, $AgentId, '$Stamp', '$Stamp', '$CountryId', '$ScrRes', '$PxD', '$FlVer')";
	$Db->Query($Query);
	$FindLastNode=false;
	return $Db->LastInsertId;
}

function TrackingVisitorCookie($Cookie=false)
{
	global $_NS_TRACK_VARS;
	$Domain=false;
	if (defined("COOKIE_DOMAIN")) $Domain=COOKIE_DOMAIN;
	if (isset($_NS_TRACK_VARS['COOKIE_DOMAIN'])) $Domain=$_NS_TRACK_VARS['COOKIE_DOMAIN'];
	if ($Cookie) {
		NS_TRACK_GENERAL::TrackingCookie(NS_COOKIE_PFX.'visitor', $Cookie, time()+60*60*24*10*365, "/", $Domain);
		return $Cookie;
	}
	global $_COOKIE;
	if (isset($_COOKIE[NS_COOKIE_PFX.'visitor'])) return $_COOKIE[NS_COOKIE_PFX.'visitor'];
	else {
		$Cookie=substr(md5(uniqid(rand())), 0, 32);
		NS_TRACK_GENERAL::TrackingCookie(NS_COOKIE_PFX.'visitor', $Cookie, time()+60*60*24*10*365, "/", $Domain);
		return $Cookie;
	}
}


function GetUserAgentId($Agent=false)
{
	if (!$Agent) return 0;
	global $_NS_TRACK_VARS;
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$Db=&$_NS_TRACK_VARS['Db'];

	$Agent=addslashes($Agent);
	$Query = "
		SELECT 
			VA.ID, VA.BAN, AG.BAN AS GBAN
			FROM ".NS_DB_PFX."_tracker_visitor_agent VA
			LEFT JOIN ".NS_DB_PFX."_tracker_visitor_agent_grp AG
				ON AG.ID=VA.GRP_ID
			WHERE VA.MD5_SEARCH=MD5('$Agent')
	";
	$Check=$Db->Select($Query);
	if (NS_TRACK_MISC::ValidVar($Check->BAN)==1||NS_TRACK_MISC::ValidVar($Check->GBAN)==1) {
		$Skip=true;
		return 0;
	}
	if (NS_TRACK_MISC::ValidId($Check->ID)) return $Check->ID;
	$GrpId=0;
	$Query ="INSERT INTO ".NS_DB_PFX."_tracker_visitor_agent (GRP_ID, USER_AGENT, MD5_SEARCH) VALUES ($GrpId, '$Agent', MD5('$Agent'))";
	$Db->Query($Query);
	return $Db->LastInsertId;	
}

function GetUserIp()
{
	global $_NS_TRACK_VARS;
	$PresetIP=&$_NS_TRACK_VARS['PresetIP'];	
	
	if (NS_TRACK_MISC::ValidVar($PresetIP)&&NS_TRACK_MISC::ValidIp($PresetIP)) return $PresetIP;
	$UserIp=false;
	if (NS_TRACK_MISC::ValidVar($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$UserIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
		if (strpos($UserIp, ",")!==false) {
			$Arr=explode(",",$UserIp);
			$UserIp=$Arr[0];
		}
	}
	if (NS_TRACK_MISC::ValidIp($UserIp)) return $UserIp;
	else  return $_SERVER['REMOTE_ADDR'];
}

function GetIpId($IP)
{
	global $_NS_TRACK_VARS;
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$Db=&$_NS_TRACK_VARS['Db'];
			
	$Query = "SELECT ID, IGNORED FROM ".NS_DB_PFX."_tracker_ip WHERE IP='$IP'";
	$Check=$Db->Select($Query);
	if ($Check->IGNORED) {
		$Skip=true;
		return false;
	}

	$IpLong=sprintf("%u\n", ip2long($IP));
	$Query = "SELECT ID FROM ".NS_DB_PFX."_tracker_ip_ignore WHERE '$IpLong' BETWEEN START_INT AND END_INT";
	$CheckId=$Db->ReturnValue($Query);
	if ($CheckId) {
		$Skip=true;
		return false;
	}

	$IpId=$Check->ID;
	if ($IpId>0) return $IpId;
	$Query = "INSERT INTO ".NS_DB_PFX."_tracker_ip (IP) VALUES ('$IP')";
	$Db->Query($Query);
	return $Db->LastInsertId;
}

function UpdateByRegs()
{
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$QueryClass=&$_NS_TRACK_VARS['QueryClass'];
		
	$LastUpdated=0;
	$Query = "SELECT (TO_DAYS(NOW()) - TO_DAYS(AGENTS_LAST_UPDATED)) FROM ".NS_DB_PFX."_tracker_config WHERE COMPANY_ID=0";
	$LastUpdated=$Db->ReturnValue($Query);
	if ($LastUpdated<1) return false;
	$AgentList=array();

	$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_visitor_agent WHERE GRP_ID=0";
	$Sql = new $QueryClass($Query);
	while ($Row=$Sql->Row()) $AgentList[$Row->ID]=$Row;

	if (count($AgentList)>0) {
		$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_visitor_agent_grp";
		$Sql = new $QueryClass($Query);
		while ($Row=$Sql->Row()) {
			if (!$Row->REGULAR_EXPRESSION&&!$Row->REGULAR_EXPRESSION2) continue;
			foreach ($AgentList as $AgentId=>$SubRow) {
				if ($Row->REGULAR_EXPRESSION
					&&!@preg_match("/".$Row->REGULAR_EXPRESSION."/i", $SubRow->USER_AGENT)) continue;
				if ($Row->REGULAR_EXPRESSION2
					&&@preg_match("/".$Row->REGULAR_EXPRESSION2."/i", $SubRow->USER_AGENT)) continue;
				$Query="UPDATE ".NS_DB_PFX."_tracker_visitor_agent SET GRP_ID=".$Row->ID." WHERE ID = $AgentId";
				$Db->Query($Query);
				unset($AgentList[$AgentId]);
			}
		}
	}

	$Query = "UPDATE ".NS_DB_PFX."_tracker_config SET AGENTS_LAST_UPDATED = NOW() WHERE COMPANY_ID=0";
	$Db->Query($Query);
	
}


function FindByIp($IpId, $AgentId, $Days=0)
{
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];

	$WhereArr[]="LAST_IP_ID=$IpId";
	if (!$_NS_TRACK_VARS['PresetIP']) $WhereArr[]="LAST_AGENT_ID=$AgentId";
	$WhereStr=implode(" AND ", $WhereArr);
	
	$Query = "
		SELECT * 
		FROM ".NS_DB_PFX."_tracker_visitor 
		WHERE 
			$WhereStr
			AND LAST_STAMP >= DATE_ADD(NOW(), INTERVAL -$Days DAY)
		ORDER BY LAST_STAMP DESC
	";
	$Visitor=$Db->Select($Query);
	return $Visitor;
}

function GetCountryId($IP=false)
{
	if (!$IP) return 0;
	$IpLong=sprintf("%u\n", ip2long($IP));
	if (!$IpLong) return 0;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	
	$Query = "
		SELECT C.ID
			FROM ".NS_DB_PFX."_tracker_country_ip CI
			INNER JOIN ".NS_DB_PFX."_tracker_country C
				ON C.CODE=CI.COUNTRY_CODE
			WHERE $IpLong BETWEEN CI.IP_START AND CI.IP_END
	";
	$CId=$Db->ReturnValue($Query);
	return ($CId)?$CId:0;
}


function FindLastNode($VisId=0, $AndSet=true) 
{
	global $_NS_TRACK_VARS, $_COOKIE;
	$Db=&$_NS_TRACK_VARS['Db'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$LogId=&$_NS_TRACK_VARS['LogId'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];

	$Query = "SELECT ID FROM ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log WHERE ID=COOKIE_LOG AND VISITOR_ID=$VisId AND SITE_ID=$StId ORDER BY STAMP DESC";
	$CheckId=$Db->ReturnValue($Query);
	if (!$CheckId) return;
	if (!$AndSet) return $CheckId;

	if ($LogId) NS_TRACK_GENERAL::SetCookieLog(false, $CheckId);

}

}


?>