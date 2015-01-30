<?

class NS_TRACK_CAMPAIGN {

function GetCampaignBySrc($Src=false)
{
	if (!$Src) return 0;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	
	if (!$CompanyId) return 0;
	$Src=NS_TRACK_MISC::escape_string($Src);
	$Query = "SELECT SUB_ID FROM ".NS_DB_PFX."_tracker_sub_campaign WHERE SRC_ID='$Src'";
	$CheckId=$Db->ReturnValue($Query);
	if ($CheckId) return $CheckId;
	$Query = "INSERT INTO ".NS_DB_PFX."_tracker_camp_piece (CAMPAIGN_ID, COMPANY_ID, NAME) VALUES (0, $CompanyId, '$Src')";
	$Db->Query($Query);
	$SubId=$Db->LastInsertId;
	$Query = "INSERT INTO ".NS_DB_PFX."_tracker_sub_campaign (SUB_ID, SRC_ID) VALUES ($SubId, '$Src')";
	$Db->Query($Query);
	return $SubId;
}

function GetCampaignById($Id=false)
{
	if (!$Id) return false;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];	
	
	$Query = "
		SELECT *
			FROM ".NS_DB_PFX."_tracker_camp_piece 
			WHERE ID=$Id
	";
	return $Db->Select($Query);
}

}

?>