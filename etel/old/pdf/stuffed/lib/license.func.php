<?


function GetLicenseText($DecodedString=false)
{
	$Arr1=array();
	$License=array();
	$DecodedString=trim($DecodedString);
	if (!$DecodedString) return false;
	$Arr1=explode("\n", $DecodedString);
	for($i=0;$i<count($Arr1);$i++) {
		$Arr2=explode("=", $Arr1[$i]);
		if (!is_array($Arr2)||Count($Arr2)<2) continue;
		$License[$Arr2[0]]=str_replace("&equal;", "=", $Arr2[1]);
	}
	if (count($License)==0) return false;

	if (!isset($License['ID'])) return false;
	if (!isset($License['V'])) return false;
	if (!isset($License['CL'])) return false;
	if (!isset($License['L'])) return false;
	//if (!isset($License['L2'])) return false;

	return $License;
}


function GetCurrentLicense()
{
	// 1- trial, 2- client, 3- agent
	global $Db, $nsProduct, $BF;
	$MaxVersion=0;
	$nsProduct->LC=array();
	$nsProduct->LICENSE=1;
	$nsProduct->WHITE=false;
	//$nsProduct->MAX_CLIENTS=0;
	$nsProduct->MAX_SITES=0;
	$nsProduct->TRIAL_EXCEED=false;
	$nsProduct->DAYS_LEFT=0;
	$nsProduct->MAX_KEY_VERSION=0;
	$Query = "SELECT * FROM ".PFX."_tracker_license";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {

		$Row->KEY_DECODE=$BF->decrypt($Row->LICENSE_KEY);
		$Row->License=GetLicenseText($Row->KEY_DECODE);
		if (!$Row->License) continue;
		if ($nsProduct->MAX_KEY_VERSION<intval($Row->License['V'])) $nsProduct->MAX_KEY_VERSION=intval($Row->License['V']);
		if (CompareVersions($Row->License['V'], $nsProduct->VERSION, 1)!=0) continue;

		$Row->License['ID']=intval(ValidVar($Row->License['ID']));
		$Row->License['L']=intval(ValidVar($Row->License['L']));
		$Row->License['WL']=intval(ValidVar($Row->License['WL']));

		if (ValidId($Row->License['L'])&&$Row->License['L']>0) {
			$nsProduct->MAX_SITES+=$Row->License['L'];
		}
		if (ValidVar($Row->License['WL'])==1) $nsProduct->WHITE=true;
		if (isset($Row->License['P'])&&$Row->License['P']==3) $nsProduct->LICENSE=3;
		if (isset($Row->License['P'])&&$Row->License['P']==2&&$nsProduct->LICENSE!=3) $nsProduct->LICENSE=2;
		$nsProduct->LC[]=$Row;
	}

	if ($nsProduct->LICENSE==1) {
		$nsProduct->MAX_SITES=1;
		$nsProduct->WHITE=false;
		TrialExceed();
	}

	for ($i=0;$i<count($nsProduct->LC);$i++) {
		$nsProduct->LC[$i]->KEY_DECODE=false;
		$nsProduct->LC[$i]->LICENSE_KEY=false;
	}

	$nsProduct->WHITE_ENABLE=false;
	$nsProduct->WHITE_POSSIBLE=false;
	$nsProduct->WHITE_NO_LOGO=false;
	$nsProduct->WHITE_NO_COPY=false;
	if ($nsProduct->WHITE) {
		$nsProduct->WHITE_POSSIBLE=true;
		$Check=$Db->Select("SELECT WHITE_NO_LOGO, WHITE_NO_COPY FROM ".PFX."_tracker_config WHERE COMPANY_ID=0 AND SITE_ID=0");
		$nsProduct->WHITE_NO_LOGO=$Check->WHITE_NO_LOGO;
		$nsProduct->WHITE_NO_COPY=$Check->WHITE_NO_COPY;	
	}
}


function GetFullLicenseArr()
{
	$FullArr=array();
	global $Db, $BF;
	$Query = "SELECT * FROM ".PFX."_tracker_license";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$Row->KEY_DECODE=$BF->decrypt($Row->LICENSE_KEY);
		$Row->License=GetLicenseText($Row->KEY_DECODE);
		if (!$Row->License) continue;
		foreach ($Row->License as $Key=>$Value) $FullArr[$Key][]=$Value;
	}
	return $FullArr;
}

function TrialExceed()
{
	global $nsProduct, $Db;
	if ($nsProduct->STRT_INT>0) {
		$Started=date("Y-m-d", $nsProduct->STRT_INT);
		$DaysCount=$Db->ReturnValue("SELECT TO_DAYS(NOW()) - TO_DAYS('$Started')");
		if ($DaysCount>30) $nsProduct->TRIAL_EXCEED=true;
		$nsProduct->DAYS_LEFT=30-$DaysCount;
	}
	else $nsProduct->DAYS_LEFT=30;
}


?>