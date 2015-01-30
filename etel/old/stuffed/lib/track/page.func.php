<?

class NS_TRACK_PAGE {

function GetPageId($PathArr=false, $StId=false)
{
	if (!$PathArr||!$StId) return 0;
	
	global $_NS_TRACK_VARS;
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$Db=&$_NS_TRACK_VARS['Db'];
	$Undef=&$_NS_TRACK_VARS['Undef'];
	$Site=&$_NS_TRACK_VARS['Site'];
	$HostsArr=&$_NS_TRACK_VARS['HostsArr'];
	$SSL=&$_NS_TRACK_VARS['SSL'];
	
	if (!isset($HostsArr[NS_TRACK_MISC::ToLower($PathArr['host'])])) {
		$Undef=true;
		$Skip=true;
		return 0;
	}
	
	
	if (NS_TRACK_MISC::ValidVar($PathArr['scheme'])=="https") $SSL=true;
	$Path=$PathArr['path'];
	$Query = "SELECT ID, IGNORE_PAGE FROM ".NS_DB_PFX."_tracker_site_page WHERE PATH = '$Path' AND SITE_ID=$StId";
	$Check=$Db->Select($Query);
	if (isset($Check->IGNORE_PAGE) && $Check->IGNORE_PAGE) {
		$Skip=true;
		$Undef=true;
		return 0;
	}
	if (NS_TRACK_MISC::ValidId($Check->ID)) return $Check->ID;


	$Query = "INSERT INTO ".NS_DB_PFX."_tracker_site_page (SITE_ID, PATH) VALUES ($StId, '$Path')";
	$Db->Query($Query);
	return $Db->LastInsertId;
}


}


?>