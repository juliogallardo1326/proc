<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");

if ($nsProduct->LICENSE==3&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");
if ($nsProduct->LICENSE!=3&&!$nsUser->SUPER_USER) $nsProduct->Redir("default", "", "admin");

/////////////////////////////////////////////
///////// require libraries here

require_once SELF."/lib/store.func.php";
require_once SYS."/system/lib/validate.func.php";

/////////////////////////////////////////////
///////// prepare any variables

$EditArr=(ValidArr($_GP['EditArr']))? $_GP['EditArr']:false;

$PageTitle=$Lang['Title'];
$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=$Lang['Title'];
$ProgPath[1]['Url']=getURL("misc_config", "", "admin");
$MenuSection="admin";

$Query = "SELECT * FROM ".PFX."_tracker_config WHERE COMPANY_ID=0 AND SITE_ID=0";
$Settings=$Db->Select($Query);
$P3P=GetParam("P3P", "STRVAL");
$P3P_REF=GetParam("P3P_REF", "STRVAL");

/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if (ValidArr($EditArr)) SaveSettings($EditArr);
}

/////////////////////////////////////////////
///////// display section here


$SkinsArr=array();
$Path=SELF."/skins";
clearstatcache();
$Dir=@opendir($Path);
while ($Row = @readdir($Dir))
{
	if ($Row == "." || $Row == ".." || $Row=="CVS") continue;
	if (is_file($Row)) continue;
	$SkinsArr[]=$Row;
}

$LangsArr=array();
$LangsArr=$nsLang->GetList();

if (ValidArr($EditArr)) {
	if (!ValidVar($EditArr['P3P'])) $EditArr['P3P']="";
	if (!ValidVar($EditArr['SSLink'])) $EditArr['SSLink']="";
	if (!ValidVar($EditArr['P3P_REF'])) $EditArr['P3P_REF']="";
	if (!ValidVar($EditArr['DefSkin'])) $EditArr['DefSkin']="";
	if (!ValidVar($EditArr['DefLang'])) $EditArr['DefLang']="";
	if (!ValidVar($EditArr['WhiteLogo'])) $EditArr['WhiteLogo']="";
	if (!ValidVar($EditArr['SendUsage'])) $EditArr['SendUsage']=0;
	if (!ValidVar($EditArr['UseStore'])) $EditArr['UseStore']=0;
	if (!ValidVar($EditArr['FromEmail'])) $EditArr['FromEmail']="";
	if (!ValidVar($EditArr['OnlinePeriod'])) $EditArr['OnlinePeriod']=0;
	if (!ValidVar($EditArr['UseIp'])) $EditArr['UseIp']=0;
	if (!ValidVar($EditArr['IpPeriod'])) $EditArr['IpPeriod']=0;
	if (!ValidVar($EditArr['IpNoCookie'])) $EditArr['IpNoCookie']=0;
	if (!ValidVar($EditArr['EnableFraud'])) $EditArr['IpPeriod']=0;
	if (!ValidVar($EditArr['FraudPeriod'])) $EditArr['IpPeriod']=1;
	if (!ValidVar($EditArr['FraudCount'])) $EditArr['IpPeriod']=5;
	if (!ValidVar($EditArr['UseWhiteLogo'])) $EditArr['UseWhiteLogo']=0;
	if (!ValidVar($EditArr['UseWhiteCopy'])) $EditArr['UseWhiteCopy']=0;
	if (!ValidVar($EditArr['TrafficPrior'])) $EditArr['TrafficPrior']="NONE";
	if (!ValidVar($EditArr['PaidEntryPrior'])) $EditArr['PaidEntryPrior']="LAST";
	if (!ValidVar($EditArr['NaturalEntryPrior'])) $EditArr['NaturalEntryPrior']="LAST";
	if (!ValidVar($EditArr['NoneEntryPrior'])) $EditArr['NoneEntryPrior']="LAST";

	if (!ValidVar($EditArr['VarCamp'])) $EditArr['VarCamp']="";
	if (!ValidVar($EditArr['VarCampSource'])) $EditArr['VarCampSource']="";
	if (!ValidVar($EditArr['VarKw'])) $EditArr['VarKw']="";
	if (!ValidVar($EditArr['VarKeyword'])) $EditArr['VarKeyword']="";
}

if (!ValidArr($EditArr)) {
	$EditArr['P3P']=$P3P;
	$EditArr['P3P_REF']=$P3P_REF;
	$EditArr['SSLink']=$Settings->SSL_LINK;
	$EditArr['DefSkin']=$nsProduct->DEFAULT_SKIN;
	$EditArr['DefLang']=$nsProduct->DEFAULT_LANG;
	$EditArr['WhiteLogo']=$nsProduct->WHITE_LOGO;
	$EditArr['SendUsage']=$Settings->ALLOW_SEND_INFO;
	$EditArr['UseStore']=$Settings->USE_STORE;
	$EditArr['FromEmail']=$Settings->FROM_EMAIL;
	$EditArr['OnlinePeriod']=$Settings->ONLINE_PERIOD;
	$EditArr['UseIp']=$Settings->IP_TRACKING;
	$EditArr['IpPeriod']=$Settings->IP_PERIOD;
	$EditArr['IpNoCookie']=$Settings->IP_NO_COOKIE;
	$EditArr['EnableFraud']=$Settings->FRAUD_ENABLE;
	$EditArr['FraudPeriod']=$Settings->FRAUD_PERIOD;
	$EditArr['FraudCount']=$Settings->FRAUD_COUNT;
	$EditArr['UseWhiteLogo']=$Settings->WHITE_NO_LOGO;
	$EditArr['UseWhiteCopy']=$Settings->WHITE_NO_COPY;
	$EditArr['VarCamp']=$Settings->VAR_CAMPAIGN;
	$EditArr['VarCampSource']=$Settings->VAR_CAMPAIGN_SOURCE;
	$EditArr['VarKw']=$Settings->VAR_KW;
	$EditArr['VarKeyword']=$Settings->VAR_KEYWORD;

	$PriorArr=explode("|", $Settings->TRACKING_MODE);
	$EditArr['TrafficPrior']=ValidVar($PriorArr[0], "NONE");
	if (ValidVar($PriorArr[1])) {
		$EntryArr=explode(";", $PriorArr[1]);
		for($i=0;$i<count($EntryArr);$i++) {
			$TmpArr=explode(":", $EntryArr[$i]);
			if ($TmpArr[0]=="NONE") $EditArr['NoneEntryPrior']=$TmpArr[1];
			if ($TmpArr[0]=="PAID") $EditArr['PaidEntryPrior']=$TmpArr[1];
			if ($TmpArr[0]=="NATURAL") $EditArr['NaturalEntryPrior']=$TmpArr[1];
		}
	}
	$EditArr['NoneEntryPrior']=ValidVar($EditArr['NoneEntryPrior'], "LAST");
	$EditArr['PaidEntryPrior']=ValidVar($EditArr['PaidEntryPrior'], "LAST");
	$EditArr['NaturalEntryPrior']=ValidVar($EditArr['NaturalEntryPrior'], "LAST");
}

$EditArr['P3P']=htmlspecialchars(stripslashes($EditArr['P3P']));
$EditArr['P3P_REF']=htmlspecialchars(stripslashes($EditArr['P3P_REF']));
$EditArr['SSLink']=htmlspecialchars(stripslashes($EditArr['SSLink']));


include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here

function SaveSettings($EditArr)
{
	global $Db, $nsProduct, $Lang, $Logs, $Settings;
	$DoRedir=true;
	extract($EditArr);
	if (!isset($UseIp)) $UseIp=0;
	if (!isset($EnableFraud)) $EnableFraud=0;
	if (!isset($IpNoCookie)) $IpNoCookie=0;
	if (!isset($UseWhiteLogo)) $UseWhiteLogo=0;
	if (!isset($UseWhiteCopy)) $UseWhiteCopy=0;
	if (!isset($TrafficPrior)) $TrafficPrior="NONE";
	if (!isset($NoneEntryPrior)) $NoneEntryPrior="LAST";
	if (!isset($PaidEntryPrior)) $PaidEntryPrior="LAST";
	if (!isset($NaturalEntryPrior)) $NaturalEntryPrior="LAST";
	
	$TrackingMode=$TrafficPrior."|NONE:$NoneEntryPrior;PAID:$PaidEntryPrior;NATURAL:$NaturalEntryPrior";
	$VarCamp=RemoveMiscSymb($VarCamp, "_");
	$VarCampSource=RemoveMiscSymb($VarCampSource, "_");
	$VarKw=RemoveMiscSymb($VarKw, "_");
	$VarKeyword=RemoveMiscSymb($VarKeyword, "_");
	if (!ValidVar($FraudPeriod)) $FraudPeriod=1;
	if (!ValidVar($FraudCount)) $FraudCount=5; 
	SetParam("P3P", "STRVAL", $P3P);
	SetParam("P3P_REF", "STRVAL", $P3P_REF);
	if (!ValidVar($DefSkin)) $DefSkin=$nsProduct->DEFAULT_SKIN;
	if (!ValidVar($DefLang)) $DefLang=$nsProduct->DEFAULT_LANG;
	if (!$nsProduct->WHITE||!ValidVar($WhiteLogo)) $WhiteLogo="";
	if (!ValidVar($SendUsage)) $SendUsage=0;
	if (!ValidVar($UseStore)) $UseStore=0;
	if ($UseStore&&!CheckStore()) {
		$Logs->Err($Lang['WriteErr']);
		$UseStore=0;
		$GLOBALS['EditArr']['UseStore']=0;
		$DoRedir=false;
	}
	if ($UseStore&&$UseStore!=$Settings->USE_STORE) {
		RebuildStoreFiles();
	}

	$Query = "UPDATE ".PFX."_system_product SET DEFAULT_SKIN = '$DefSkin', DEFAULT_LANG='$DefLang', WHITE_LOGO='$WhiteLogo' WHERE ID = ".$nsProduct->ID;
	$Db->Query($Query);


	$Query = "
		UPDATE ".PFX."_tracker_config SET 
			ALLOW_SEND_INFO='$SendUsage', 
			SSL_LINK = '$SSLink', 
			USE_STORE='$UseStore', 
			FROM_EMAIL = ?, 
			ONLINE_PERIOD = ?, 
			IP_TRACKING='$UseIp',
			IP_NO_COOKIE='$IpNoCookie',
			IP_PERIOD=?,
			FRAUD_ENABLE='$EnableFraud',
			FRAUD_COUNT=?,
			FRAUD_PERIOD=?,
			VAR_CAMPAIGN=?,
			VAR_CAMPAIGN_SOURCE=?,
			VAR_KW=?,
			VAR_KEYWORD=?,
			WHITE_NO_LOGO='$UseWhiteLogo',
			WHITE_NO_COPY='$UseWhiteCopy',
			TRACKING_MODE='$TrackingMode'
		WHERE COMPANY_ID=0 AND SITE_ID=0";
	$Db->Query($Query, 
					$FromEmail, 
					abs(intval($OnlinePeriod)),
					abs(intval($IpPeriod)), 
					abs(intval($FraudCount)), 
					abs(intval($FraudPeriod)),
					$VarCamp,
					$VarCampSource,
					$VarKw,
					$VarKeyword);

	if ($DoRedir) $nsProduct->Redir("misc_config", "RUpd=1", "admin");

}

function CheckStore() {
	if (!is_dir(SELF."/store")) return false;
	$Filename="test.txt";
	$f=@fopen(SELF."/store/$Filename", "a+");
	if (!$f) return false;
	if (!@is_writable(SELF."/store/$Filename")) return false;
	@unlink(SELF."/store/$Filename");
	return true;
}

/////////////////////////////////////////////
///////// library section


function RebuildStoreFiles()
{
	$ActionFile="redir_action.nodb";
	$f=@fopen(SELF."/store/$ActionFile", "a+");
	@flock($f, LOCK_EX); 
	@ftruncate($f, 0);
	@flock($f, LOCK_UN);
	@fclose($f);

	$SplitFile="split_test.nodb";
	$f=@fopen(SELF."/store/$SplitFile", "a+");
	@flock($f, LOCK_EX); 
	@ftruncate($f, 0);
	@flock($f, LOCK_UN);
	@fclose($f);

	$Query = "SELECT * FROM ".PFX."_tracker_visitor_action WHERE REDIRECT_CATCH='1' ";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$ActionArr['ID']=$Row->ID;
		$ActionArr['PAGE_ID']=$Row->PAGE_ID;
		$ActionArr['SITE_ID']=$Row->SITE_ID;
		$ActionArr['REDIRECT_URL']=$Row->REDIRECT_URL;
		$ActionArr['PATH']=$Row->PATH;
		SaveActionToFile($ActionArr, $ActionFile);
	}

	$Query = "SELECT ID FROM ".PFX."_tracker_split_test";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) SaveSplitToFile(false, $SplitFile, $Row->ID);
}


?>