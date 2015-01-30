<?

class NS_TRACK_ACTION {

function GetActionIds($PageId=false, $PathArr=false)
{
	global $_NS_TRACK_VARS;
	$StId=&$_NS_TRACK_VARS['StId'];
	$Item=&$_NS_TRACK_VARS['Item'];
	$QueryClass=&$_NS_TRACK_VARS['QueryClass'];

	
	$Actions=array();
	$Qr=NS_TRACK_MISC::ValidVar($PathArr['query']);
	$CurrentPath=$PathArr['path'];
	$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_visitor_action WHERE (PAGE_ID = $PageId OR (PAGE_ID = 0 AND SITE_ID=$StId)) AND REDIRECT_CATCH='0' AND ACTIVE='1' AND CODE_ACTION='0' ";
	$Sql = new $QueryClass($Query);
	while ($Row=$Sql->Row()) {
		$Item=false;
		if (!NS_TRACK_MISC::ValidVar($Row->QUERY)) $Row->QUERY=false;
		if (!NS_TRACK_MISC::ValidVar($Row->PATH)) $Row->PATH=false;
		if (!$Row->PATH&&NS_TRACK_ACTION::CompareTemplate($Qr, $Row->QUERY)) {
			$Actions[$Row->ID]['Id']=$Row->ID;
			if ($Item) $Actions[$Row->ID]['Item']=urldecode($Item);
		}
		if ($Row->PATH&&NS_TRACK_ACTION::ComparePathTemplate($CurrentPath, $Row->PATH, $Qr, $Row->QUERY)) {
			$Actions[$Row->ID]['Id']=$Row->ID;
			if ($Item) $Actions[$Row->ID]['Item']=urldecode($Item);
		}
	}
	return $Actions;
}

function GetActionItemId($ItemName=false, $CompanyId=false) 
{
	if (!$ItemName||!$CompanyId) return 0;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$ItemName=addslashes($ItemName);
	$Query = "SELECT ID FROM ".NS_DB_PFX."_tracker_action_item WHERE NAME='$ItemName' AND COMPANY_ID=$CompanyId";
	$CheckId=$Db->ReturnValue($Query);
	if (NS_TRACK_MISC::ValidId($CheckId)) return $CheckId;
	$Query ="INSERT INTO ".NS_DB_PFX."_tracker_action_item (COMPANY_ID, NAME) VALUES ($CompanyId, '$ItemName')";
	$Db->Query($Query);
	return $Db->LastInsertId;	
}


function ComparePathTemplate($CurrentPath=false, $Path=false, $Query=false, $Template=false)
{
	if (NS_TRACK_ACTION::CompareStrings($CurrentPath, $Path)&&NS_TRACK_ACTION::CompareTemplate($Query, $Template)) return true;
	return false;
}

function CompareStrings($Str=false, $Templ=false)
{
	global $_NS_TRACK_VARS;
	$Item=&$_NS_TRACK_VARS['Item'];
	
		
	if (!$Templ) return true;
	if ($Templ=="*"||$Templ=="/*") return true;
	if ($Templ=="{a}"||$Templ=="/{a}") {
		$Item=$Str;
		return true;
	}
	if (!$Str) return false;

	$ItemPosition=false;
	$Matches=array();
	if (strpos($Templ, "{a}")!==false) {
		$TmpArr=explode("*", substr($Templ, 0, strpos($Templ, "{a}")));
		$ItemPosition=count($TmpArr);
	}
	$Templ=str_replace("{a}", "*", $Templ);
	$Arr=explode("*", $Templ);
	for($i=0;$i<count($Arr);$i++) $Arr[$i]=preg_quote($Arr[$i], "/");
	$Templ2=implode("(.+)", $Arr);
	$Result=@preg_match("/$Templ2/i", $Str, $Matches);
	if ($ItemPosition) $Item=NS_TRACK_MISC::ValidVar($Matches[$ItemPosition]);
	return $Result;
}



function CompareTemplate($Query=false, $Template=false)
{
	$ItemValue=false;
	if (!$Template) return true;
	if (!$Query) return false;
	$TplGet=NS_TRACK_QUERY::ParseTemplate($Template);
	$QrGet=NS_TRACK_QUERY::ParseTemplate($Query);
	if (!$TplGet||!$QrGet) return false;
	foreach ($TplGet as $Key=>$Value) {
		if (!isset($QrGet[$Key])) return 0;
		if ($Value=="{a}") {
			$ItemValue=$QrGet[$Key];
			continue;
		}
		if ($Value=="*") continue;
		if ($Value!=$QrGet[$Key]) return 0;
	}
	if ($ItemValue) {
		global $Item;
		$Item=$ItemValue;
	}
	return 1;
}

}

?>