<?

class NS_TRACK_REFERER {


function GetRefererSet($RefArr=false, $Referer=false)
{
	if (!$RefArr||!$Referer) return 0;
	//global $Db, $StId, $Skip, $NaturalKey, $HostsArr, $CookieLogSet, $UpdateVisPath;

	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$NaturalKey=&$_NS_TRACK_VARS['NaturalKey'];
	$HostsArr=&$_NS_TRACK_VARS['HostsArr'];
	$CookieLogSet=&$_NS_TRACK_VARS['CookieLogSet'];
	$UpdateVisPath=&$_NS_TRACK_VARS['UpdateVisPath'];
	$Frame=&$_NS_TRACK_VARS['Frame'];

	if ($Frame) return 0;

	//if ($RefArr['host']!=ValidHost($RefArr['host'])) return 0;
	if (!NS_TRACK_MISC::ValidVar($RefArr['host'])) return 0;

	$HostObj=NS_TRACK_REFERER::GetRefHost($RefArr['host']);

	if ($HostObj==0) {$Skip=true; return 0;}

	$RefHostId=&$_NS_TRACK_VARS['RefHostId'];
	$SourceId=&$_NS_TRACK_VARS['SourceId'];

	$SourceId=$HostObj->ID;

	if (!isset($HostsArr[NS_TRACK_MISC::ToLower($RefArr['host'])])) {
		$RefHostId=$HostObj->ID; $CookieLogSet=true;
		$UpdateVisPath=true;
	}
	else 	return 0;

	$RefId=NS_TRACK_REFERER::GetRefererId($Referer);

	$Query = "SELECT ID, NATURAL_KEY FROM ".NS_DB_PFX."_tracker_referer_set WHERE REFERER_ID=$RefId";
	$Check=$Db->Select($Query);
	if (NS_TRACK_MISC::ValidId($Check->ID)) {
		$NaturalKey=($Check->NATURAL_KEY>0)?$Check->NATURAL_KEY:false;
		return $Check->ID;
	}
	return NS_TRACK_REFERER::RefSetProcess($RefArr,$RefId,$HostObj);
}

function GetRefererId($Ref=false)
{
	if (!$Ref) return 0;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	
	$Ref=NS_TRACK_MISC::escape_string($Ref);
	$Query = "SELECT ID FROM ".NS_DB_PFX."_tracker_referer WHERE MD5_SEARCH=MD5('$Ref')";
	$CheckId=$Db->ReturnValue($Query);
	if (NS_TRACK_MISC::ValidId($CheckId)) return $CheckId;
	$Query ="INSERT INTO ".NS_DB_PFX."_tracker_referer (REFERER, MD5_SEARCH) VALUES ('$Ref', MD5('$Ref'))";
	$Db->Query($Query);
	return $Db->LastInsertId;
}



function RefSetProcess($RefArr, $RefId=false, $HostObj=false)
{
	//global $Db, $StId, $Skip, $NaturalKey, $HostsArr, $CookieLogSet, $UpdateVisPath;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$NaturalKey=&$_NS_TRACK_VARS['NaturalKey'];
	$HostsArr=&$_NS_TRACK_VARS['HostsArr'];
	$CookieLogSet=&$_NS_TRACK_VARS['CookieLogSet'];
	$UpdateVisPath=&$_NS_TRACK_VARS['UpdateVisPath'];

	$QrArr=NS_TRACK_QUERY::ParseTemplate(NS_TRACK_MISC::ValidVar($RefArr['query']));
	if (NS_TRACK_MISC::ValidVar($HostObj->KEY_VAR)&&isset($QrArr[$HostObj->KEY_VAR])) {
		$Key=NS_TRACK_MISC::ToLower(urldecode(urldecode($QrArr[$HostObj->KEY_VAR])));
		$Key=NS_TRACK_REFERER::ReplacePunkt($Key);
		$Key=preg_replace("/\s+/", " ", $Key);
		$Key=trim($Key);
		$KeyId=NS_TRACK_REFERER::GetKeywordId($Key);
	}
	else $KeyId=0;
	$NaturalKey=$KeyId;

	$Query = "INSERT INTO ".NS_DB_PFX."_tracker_referer_set (HOST_ID, NATURAL_KEY, PROCESSED, REFERER_ID) VALUES (".$HostObj->ID.", $KeyId, '1', $RefId)";
	$Db->Query($Query);
	return $Db->LastInsertId;
}



function GetRefHost($Host=false)
{
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	
	$Query = "
		SELECT 
			TH.ID, TH.KEY_VAR, TH.BAN,
			THG.KEY_VAR AS GKEY, THG.BAN AS GBAN
			FROM ".NS_DB_PFX."_tracker_host TH
				LEFT JOIN ".NS_DB_PFX."_tracker_host_grp THG
					ON THG.ID=TH.GRP_ID
			WHERE TH.HOST = '$Host'
	";
	$RefObj=$Db->Select($Query);
	if (!$RefObj->ID) {
		NS_TRACK_REFERER::NewHost($Host);
		return NS_TRACK_REFERER::GetRefHost($Host);
	}
	if ($RefObj->BAN||$RefObj->GBAN) return 0;
	if ($RefObj->GKEY) $RefObj->KEY_VAR=$RefObj->GKEY;
	return $RefObj;
}


function GetKeywordId($Key=false)
{
	if (!$Key) return 0;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
		
	$Key=NS_TRACK_MISC::escape_string($Key);
	$Query = "SELECT ID FROM ".NS_DB_PFX."_tracker_keyword WHERE MD5_SEARCH=MD5('$Key')";
	$CheckId=$Db->ReturnValue($Query);
	if (NS_TRACK_MISC::ValidId($CheckId)) return $CheckId;
	$Query ="INSERT INTO ".NS_DB_PFX."_tracker_keyword (KEYWORD, MD5_SEARCH) VALUES ('$Key', MD5('$Key'))";
	$Db->Query($Query);
	return $Db->LastInsertId;
}



function NewHost($Host)
{
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$QueryClass=&$_NS_TRACK_VARS['QueryClass'];

	$GrpId=0;
	$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_host_grp";
	$Sql = new $QueryClass($Query);
	while ($Row=$Sql->Row()) {
		if (!$Row->REGULAR_EXPRESSION&&!$Row->REGULAR_EXPRESSION2) continue;
		if ($Row->REGULAR_EXPRESSION
			&&!@preg_match("/".$Row->REGULAR_EXPRESSION."/i", $Host)) continue;
		if ($Row->REGULAR_EXPRESSION2
			&&@preg_match("/".$Row->REGULAR_EXPRESSION2."/i", $Host)) continue;
		$GrpId=$Row->ID;
		break;
	}
	$Query = "INSERT INTO ".NS_DB_PFX."_tracker_host (GRP_ID, HOST) VALUES ($GrpId, '$Host')";
	$Db->Query($Query);
	return $Db->LastInsertId;
}


function ReplacePunkt($Str)
{
	$From="~!@#$%^&*()_+|`-=\\{}[]:\";',./<>?¹«»";
	$To="                                    ";
	return strtr($Str, $From, $To);
}


}

?>