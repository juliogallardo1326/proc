<?

class NS_TRACK_QUERY {


function GetQueryId($Qr=false)
{
	if (!$Qr) return 0;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	
	$Qr=NS_TRACK_MISC::escape_string($Qr);
	$Query = "SELECT ID FROM ".NS_DB_PFX."_tracker_query WHERE MD5_SEARCH=MD5('$Qr')";
	$CheckId=$Db->ReturnValue($Query);
	if (NS_TRACK_MISC::ValidId($CheckId)) return $CheckId;
	$Query ="INSERT INTO ".NS_DB_PFX."_tracker_query (QUERY_STRING, MD5_SEARCH) VALUES ('$Qr', MD5('$Qr'))";
	$Db->Query($Query);
	return (NS_TRACK_MISC::ValidId($Db->LastInsertId))?$Db->LastInsertId:0;
}



function ParseTemplate($String=false)
{
	$TplGet=array();
	if (!$String) return $TplGet;
	$String=str_replace("?", "", $String);
	parse_str($String, $TplGet);
	return $TplGet;
}

}


?>