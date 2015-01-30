<?

class NS_TRACK_SALE {

function GetSaleItem($ItemName=false, $CompanyId=false)
{
	if (!$ItemName||!$CompanyId) return 0;
	global $Db;
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];

	$ItemName=addslashes($ItemName);
	$Query = "SELECT ID FROM ".NS_DB_PFX."_tracker_sale_item WHERE NAME='$ItemName' AND COMPANY_ID=$CompanyId";
	$CheckId=$Db->ReturnValue($Query);
	if (NS_TRACK_MISC::ValidId($CheckId)) return $CheckId;
	$Query ="INSERT INTO ".NS_DB_PFX."_tracker_sale_item (COMPANY_ID, NAME) VALUES ($CompanyId, '$ItemName')";
	$Db->Query($Query);
	return $Db->LastInsertId;
}


 
function PrepareSaleItems($Arr)
{
	$Items=array();
	$TmpArr=array();
	for($i=0;$i<count($Arr);$i++) {
		$Str=$Arr[$i];
		preg_match_all("/{{([^}}]*)}}/", $Str, $TmpArr);
		$TmpArr=$TmpArr[1];
		$Items[]=$TmpArr;
	}
	return $Items;
}


}


?>