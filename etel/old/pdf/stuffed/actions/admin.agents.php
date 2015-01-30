<?
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");
if (!$nsUser->SUPER_ADMIN&&!ValidId($_GP['EditUid'])) {
	$nsProduct->Redir("default", "", "admin");
}
if ($nsProduct->LICENSE==3&&!$nsUser->SUPER_ADMIN&&ValidId($_GP['EditUid'])) {
	if ($_GP['EditUid']!=$nsUser->UserId()) $nsProduct->Redir("default", "", "admin");
}
if ($nsProduct->LICENSE!=3
	&&!$nsUser->SUPER_USER
	&&!ValidId($_GP['EditUid'])) 
		$nsProduct->Redir("default", "", "admin");



/// validate functions
require_once SYS."/system/lib/validate.func.php";



///////////////////////////
/// PREPARE SECTION
$PageTitle=$Lang['Title'];
if (isset($_GP['EditUid'])) $EditUid=$_GP['EditUid'];
if (isset($_GP['MakeAdmin'])) $MakeAdmin=$_GP['MakeAdmin'];
if (isset($_GP['UnregisterAdmin'])) $UnregisterAdmin=$_GP['UnregisterAdmin'];
if (isset($_GP['EditUid'])) $EditUid=$_GP['EditUid'];
if (isset($_GP['DeleteUid'])) $DeleteUid=$_GP['DeleteUid'];
if (isset($_GP['EditArr'])) $EditArr=$_GP['EditArr'];

if (!isset($ErrArr)) $ErrArr=array();
$SkinsArr=array();
$LangsArr=array();
$AutoTZ=false;
$AutoTZName=false;

$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=(ValidVar($EditUid)==$nsUser->UserId())?$Lang['Profile']:$Lang['Title'];
$ProgPath[1]['Url']=getURL("agents", "", "admin");
$MenuSection="admin";


////////////////////////////
// PROCESS CALL 

if (!$nsUser->DEMO) {
	if (isset($EditArr)&&is_array($EditArr)&&isset($EditUid)&&$EditUid=="new") CreateAgent($EditArr);
	if (isset($EditArr)&&is_array($EditArr)&&isset($EditUid)&&ValidId($EditUid)) UpdateAgent($EditUid, $EditArr);
	if (isset($MakeAdmin)&&ValidId($MakeAdmin)) ConvertToAgent($MakeAdmin);
	if (isset($UnregisterAdmin)&&ValidId($UnregisterAdmin)) ConvertFromAgent($UnregisterAdmin);
	if (isset($DeleteUid)&&ValidId($DeleteUid)) DeleteAdmin($DeleteUid);
}

//////////////////////////////////////////
//// DISPLAY SECTION

	$NonTrackerAgentsList=array();
	$Query = "SELECT * FROM ".PFX."_system_user SU";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row = $Sql->Row()) {
		if ($Db->IsExists(PFX."_tracker_admin", "USER_ID", $Row->ID)) continue;
		if ($Db->IsExists(PFX."_tracker_user", "USER_ID", $Row->ID)) continue;
		$Row->_STYLE=$Sql->_STYLE;
		$Row->NAME=stripslashes($Row->NAME);
		$NonTrackerAgentsList[]=$Row;
	}


/// Users listing
if ((!isset($EditUid)||(!ValidId($EditUid)&&$EditUid!="new"&&$EditUid!="perms"))&&
	(!isset($DeleteUid)||!ValidId($DeleteUid))) {
	$Sql = new Query(GetUserQuery());
	$Sql->ReadSkinConfig();
	while ($Row = $Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Row->NAME=stripslashes($Row->NAME);
		$AgentsList[]=$Row;
	}
	if ($nsUser->SUPER_ADMIN&&$nsProduct->LICENSE!=2) {
		$SubMenu[0]['Name']=$Lang['AddNew'];
		$SubMenu[0]['Link']=getURL("agents", "EditUid=new");
		if (count($NonTrackerAgentsList)>0) {
			$SubMenu[1]['Name']=$Lang['NoPermList'];
			$SubMenu[1]['Link']=getURL("agents", "EditUid=perms");
		}
	}
	include $nsTemplate->Inc("admin.agents");
}

/// Non tracker users listing
if (isset($EditUid)&&!ValidId($EditUid)&&$EditUid=="perms") {
	$AgentsList=$NonTrackerAgentsList;
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("agents");
	include $nsTemplate->Inc("admin.agents_noperms");
}


// Edit user
if (isset($EditUid)&&ValidId($EditUid)) {
	$EditUser=$Db->Select(GetUserQuery($EditUid));
	if (!isset($EditUser->ID)) $nsProduct->Redir("agents");

	if ($EditUser->ID==$nsUser->UserId()) {
		$Path=SELF."/skins";
		clearstatcache();
		$Dir=@opendir($Path);
		while ($Row = readdir($Dir)) {
			if ($Row == "." || $Row == ".." || $Row=="CVS") continue;
			if (is_file($Row)) continue;
			$SkinsArr[]=$Row;
		}
		$LangsArr=$nsLang->GetList();
		if ($nsUser->TZ&&ValidVar($_COOKIE[COOKIE_PFX.'auto_tz'])) {
			$Lang['AutoTZ'].=" (".(($nsUser->TZ>=0)?"+":"").$nsUser->TZ.")";
		}
	}

	if (!isset($EditArr)||!is_array($EditArr)) {
		$EditArr['Login']=$EditUser->LOGIN;
		$EditArr['Email']=$EditUser->EMAIL;
		$EditArr['Name']=$EditUser->NAME;
		$EditArr['Super']=$EditUser->SUPER_ADMIN;
		$EditArr['AdvMode']=$EditUser->ADVANCED_MODE;
		$EditArr['Pass']="";
		$EditArr['Pass2']="";
		$EditArr['ColHits']=$EditUser->HITS;
		$EditArr['ColSales']=$EditUser->SALES;
		$EditArr['ColActions']=$EditUser->ACTIONS;
		$EditArr['ColClicks']=$EditUser->CLICKS;
		$EditArr['ColROI']=$EditUser->ROI;
		$EditArr['ColConv']=$EditUser->CONVERSIONS;
		$EditArr['Graphs']=$EditUser->GRAPHS;
		$EditArr['HelpMode']=$EditUser->HELP_MODE;
		$EditArr['TZ']=$EditUser->TIMEZONE;	
		$EditArr['Enc']=$EditUser->PAGE_ENCODING;	
		$EditArr['Demo']=$EditUser->DEMO;	
	}
	
	if (!isset($EditArr['Super'])) $EditArr['Super']=0;
	if (!isset($EditArr['ColHits'])) $EditArr['ColHits']=0;
	if (!isset($EditArr['ColSales'])) $EditArr['ColSales']=0;
	if (!isset($EditArr['ColActions'])) $EditArr['ColActions']=0;
	if (!isset($EditArr['ColClicks'])) $EditArr['ColClicks']=0;
	if (!isset($EditArr['ColROI'])) $EditArr['ColROI']=0;
	if (!isset($EditArr['ColConv'])) $EditArr['ColConv']=0;
	if (!isset($EditArr['Graphs'])) $EditArr['Graphs']=0;
	if (!isset($EditArr['HelpMode'])) $EditArr['HelpMode']=0;
	

	$EditArr['Name']=stripslashes(htmlspecialchars($EditArr['Name']));
	$EditArr['Ignore']=ValidVar($_COOKIE['ns_skip']);
	$TableCaption=$Lang['CaptionEdit'].stripslashes($EditUser->NAME);

	if ($nsUser->SUPER_ADMIN&&$nsProduct->LICENSE!=2) {
		$SubMenu[0]['Name']=$Lang['AddNew'];
		$SubMenu[0]['Link']=getURL("agents", "EditUid=new");
		$SubMenu[1]['Name']=$Lang['BackToList'];
		$SubMenu[1]['Link']=getURL("agents");
	}
	include $nsTemplate->Inc("admin.agent_edit");
}


// Create new
if (isset($EditUid)&&$EditUid=="new") {
	if (!isset($EditArr)||!is_array($EditArr)) {
		$EditArr['Login']="";
		$EditArr['Email']="";
		$EditArr['Name']="";
		$EditArr['Super']=0;
		$EditArr['AdvMode']=0;
		$EditArr['HelpMode']=0;
		$EditArr['Pass']="";
		$EditArr['Pass2']="";
		$EditArr['TZ']="";	
		$EditArr['Enc']="";
		$EditArr['Demo']=0;
	}
	if (!isset($EditArr['Super'])) $EditArr['Super']=0;
	if (!isset($EditArr['AdvMode'])) $EditArr['AdvMode']=0;
	if (!isset($EditArr['ColHits'])) $EditArr['ColHits']=0;
	if (!isset($EditArr['ColSales'])) $EditArr['ColSales']=0;
	if (!isset($EditArr['ColActions'])) $EditArr['ColActions']=0;
	if (!isset($EditArr['ColClicks'])) $EditArr['ColClicks']=0;
	if (!isset($EditArr['ColROI'])) $EditArr['ColROI']=0;
	if (!isset($EditArr['ColConv'])) $EditArr['ColConv']=0;
	if (!isset($EditArr['Graphs'])) $EditArr['Graphs']=0;
	if (!isset($EditArr['HelpMode'])) $EditArr['HelpMode']=0;

	$EditArr['Login']=ToLower($EditArr['Login']);
	$EditArr['Name']=stripslashes(htmlspecialchars($EditArr['Name']));
	$TableCaption=$Lang['CaptionNew'];
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("agents");
	include $nsTemplate->Inc("admin.agent_edit");
}

/////////////////////////////////////////
/// PROCESS FUNCTIONS

function UpdateAgent($Id, &$Arr) 
{
	global $Db, $nsProduct, $Logs, $Lang, $nsUser, $_COOKIE;
	$EditUser=$Db->Select(GetUserQuery($Id));
	extract($Arr);
	if (!isset($Super)) $Super=0;
	if (!isset($Demo)) $Demo=0;
	if (!isset($AdvMode)) $AdvMode=0;
	if (!isset($ColHits)) $ColHits=0;
	if (!isset($ColSales)) $ColSales=0;
	if (!isset($ColActions)) $ColActions=0;
	if (!isset($ColClicks)) $ColClicks=0;
	if (!isset($ColROI)) $ColROI=0;
	if (!isset($ColConv)) $ColConv=0;
	if (!isset($Graphs)) $Graphs=0;
	if (!isset($HelpMode)) $HelpMode=0;
	if (!isset($Enc)) $Enc="";
	$Enc=addslashes($Enc);

	$Login=ToLower($Login);
	if (CheckSymb_($Login)) $ErrArr['Login']=$Lang['SymbErr'];
	if (!ValidMail($Email)) $ErrArr['Email']=$Lang['MustFillCorr'];
	if (!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!$Email) $ErrArr['Email']=$Lang['MustFill'];
	if (!$Login) $ErrArr['Login']=$Lang['MustFill'];
	if (strlen($Login)<3) $ErrArr['Login']=$Lang['LoginTooShort'];
	if (strlen($Login)>64) $ErrArr['Login']=$Lang['LoginTooLong'];

	if ($Pass) {
		if (strlen($Pass)<3) $ErrArr['Pass']=$Lang['PassTooShort'];
		if (strlen($Pass)>64) $ErrArr['Pass']=$Lang['PassTooLong'];
		if ($Pass!=$Pass2) $ErrArr['Pass2']=$Lang['PassNotPass2'];
		if (CheckSymb_($Pass)) $ErrArr['Pass']=$Lang['SymbErr'];
	}

	if ($Login!=$EditUser->LOGIN) {
		$Query = "SELECT ID FROM ".PFX."_system_user WHERE LOGIN = '$Login'";
		$Check=$Db->Select($Query);
		if (isset($Check->ID)&&ValidId($Check->ID)) $ErrArr['Login']=$Lang['LoginExists'];
	}
	if ($Email!=$EditUser->EMAIL) {
		$Query = "SELECT ID FROM ".PFX."_system_user WHERE EMAIL = '$Email'";
		$Check=$Db->Select($Query);
		if (isset($Check->ID)&&ValidId($Check->ID)) $ErrArr['Email']=$Lang['EmailExists'];
	}

	if ($nsProduct->LICENSE==2&&$EditUser->SUPER_ADMIN) $Super=1;
	if ($Super==0&&$Super!=$EditUser->SUPER_ADMIN
		&&$nsProduct->LICENSE!=3&&$nsUser->SUPER_ADMIN) {
		$Query="SELECT ID FROM ".PFX."_tracker_admin WHERE SUPER_ADMIN = '1' AND ID != ".$EditUser->AGENT_ID;
		$Check=$Db->Select($Query);
		if (!isset($Check->ID)||!ValidId($Check->ID)) $ErrArr['Super']=$Lang['SuperLocked'];
	}

	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']);$GLOBALS['ErrArr']=$ErrArr; return;}

	if (!$nsUser->SUPER_ADMIN) $Super=0;
	if ($nsProduct->LICENSE!=3) $Super=$EditUser->SUPER_ADMIN;
	if ($nsProduct->LICENSE!=3 || !$nsUser->SUPER_ADMIN) $Demo=$EditUser->DEMO;

	$Query = "UPDATE ".PFX."_system_user SET LOGIN = ? , NAME = ?, EMAIL = ? WHERE ID = $Id";
	$Db->Query($Query, $Login, $Name, $Email);
	$Query = "UPDATE ".PFX."_tracker_admin SET SUPER_ADMIN = '$Super', DEMO='$Demo' WHERE ID = ".$EditUser->AGENT_ID;
	$Db->Query($Query);

	if ($Pass) {
		$Query = "UPDATE ".PFX."_system_user SET PWD = '".md5($Pass)."' WHERE ID = $Id";
		$Db->Query($Query);
		if ($Id==$nsUser->UserId()) {
			$nsUser->Login($nsUser->UserInfo['LOGIN'], $Pass, ValidVar($_COOKIE[COOKIE_PFX.'pwd']));
		}
	}

	if ($Id==$nsUser->UserId()) {
		if (ValidVar($DefSkin)) {
			if ($nsUser->USKIN) $Query = "UPDATE ".PFX."_system_user2skin SET SKIN='$DefSkin' WHERE UID=$Id AND PROD_ID=".$nsProduct->ID."";
			else $Query = "INSERT INTO ".PFX."_system_user2skin (UID, PROD_ID, SKIN) VALUES ($Id, ".$nsProduct->ID.", '$DefSkin')";
			$Db->Query($Query);
		}
		if (ValidVar($DefLang)) {
			if ($nsUser->ULANG) $Query = "UPDATE ".PFX."_system_user2lang SET LANG= '$DefLang' WHERE UID=$Id AND PROD_ID=".$nsProduct->ID."";
			else $Query = "INSERT INTO ".PFX."_system_user2lang (UID, PROD_ID, LANG) VALUES ($Id, ".$nsProduct->ID.", '$DefLang')";
			$Db->Query($Query);
		}
		$Check=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_user_column WHERE USER_ID=$Id");
		if (!ValidId($Check)) $Db->Query("INSERT INTO ".PFX."_tracker_user_column (USER_ID) VALUES ($Id)");
		$Check=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_user_settings WHERE USER_ID=$Id");
		if (!ValidId($Check)) $Db->Query("INSERT INTO ".PFX."_tracker_user_settings (USER_ID) VALUES ($Id)");

		$Query = "UPDATE ".PFX."_tracker_user_column SET HITS='$ColHits', SALES='$ColSales', ACTIONS='$ColActions', CLICKS='$ColClicks', ROI='$ColROI', CONVERSIONS='$ColConv', GRAPHS='$Graphs' WHERE USER_ID=$Id";
		$Db->Query($Query);
		$Query = "UPDATE ".PFX."_tracker_user_settings SET ADVANCED_MODE='$AdvMode', HELP_MODE='$HelpMode', TIMEZONE='$TZ', PAGE_ENCODING = '$Enc' WHERE USER_ID=$Id";
		$Db->Query($Query);
	}

	if (ValidVar($Ignore)) $nsUser->SetCookie(COOKIE_PFX.'skip', "1", time()+60*60*24*10*365, "/");
	else $nsUser->SetCookie(COOKIE_PFX.'skip', "", time()-100, "/");
	if (!$nsUser->SUPER_ADMIN) $nsProduct->Redir("agents", "EditUid=$Id&RUpd=1");
	$nsProduct->Redir("agents", "RUpd=1");
}	



function CreateAgent(&$Arr) 
{
	global $Db, $nsProduct, $Logs, $Lang, $nsUser, $nsLang;
	extract($Arr);
	if (!isset($Super)) $Super=0;
	if (!isset($Demo)) $Demo=0;

	$Login=ToLower($Login);
	if (CheckSymb_($Login)) $ErrArr['Login']=$Lang['SymbErr'];
	if (CheckSymb_($Pass)) $ErrArr['Pass']=$Lang['SymbErr'];
	if (!ValidMail($Email)) $ErrArr['Email']=$Lang['MustFillCorr'];
	if (!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!$Email) $ErrArr['Email']=$Lang['MustFill'];
	if (!$Pass) $ErrArr['Pass']=$Lang['MustFill'];
	if (!$Login) $ErrArr['Login']=$Lang['MustFill'];
	if (strlen($Login)<3) $ErrArr['Login']=$Lang['LoginTooShort'];
	if (strlen($Login)>64) $ErrArr['Login']=$Lang['LoginTooLong'];
	if (strlen($Pass)<3) $ErrArr['Pass']=$Lang['PassTooShort'];
	if (strlen($Pass)>64) $ErrArr['Pass']=$Lang['PassTooLong'];
	if ($Pass!=$Pass2) $ErrArr['Pass2']=$Lang['PassNotPass2'];


	if ($Login) {
		$Query = "SELECT ID FROM ".PFX."_system_user WHERE LOGIN = '$Login'";
		$Check=$Db->Select($Query);
		if (isset($Check->ID)&&ValidId($Check->ID)) $ErrArr['Login']=$Lang['LoginExists'];
	}
	if ($Email) {
		$Query = "SELECT ID FROM ".PFX."_system_user WHERE EMAIL = '$Email'";
		$Check=$Db->Select($Query);
		if (isset($Check->ID)&&ValidId($Check->ID)) $ErrArr['Email']=$Lang['EmailExists'];
	}


	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']);$GLOBALS['ErrArr']=$ErrArr; return;}
	if (!$nsUser->SUPER_ADMIN) $Super=0;
	if (!$nsProduct->LICENSE!=3 || !$nsUser->SUPER_ADMIN) $Demo=0;

	$Query="INSERT INTO ".PFX."_system_user (LOGIN, EMAIL, NAME, PWD) VALUES (?, ?, ?, ?)";
	$Db->Query($Query, $Login, $Email, $Name, md5($Pass));
	$Max=$Db->LastInsertId;

	$Query = "INSERT INTO ".PFX."_tracker_admin (USER_ID, SUPER_ADMIN, DEMO) VALUES ($Max, '$Super', '$Demo')";
	$Db->Query($Query);

	$Query = "INSERT INTO ".PFX."_system_user2skin (UID, PROD_ID, SKIN) VALUES ($Max, ".$nsProduct->ID.", '".$nsProduct->SKIN."')";
	$Db->Query($Query);
	$Query = "INSERT INTO ".PFX."_system_user2lang (UID, PROD_ID, LANG) VALUES ($Max, ".$nsProduct->ID.", '".$nsLang->CurrentLang."')";
	$Db->Query($Query);

	$Query = "INSERT INTO ".PFX."_tracker_user_column (USER_ID) VALUES ($Max)";
	$Db->Query($Query);
	$Query = "INSERT INTO ".PFX."_tracker_user_settings (USER_ID) VALUES ($Max)";
	$Db->Query($Query);

	$nsProduct->Redir("agents", "EditUid=$Max&RCrt=1");
}	


function ConvertToAgent($Id) 
{
	global $Db, $nsProduct;
	$Query = "SELECT * FROM ".PFX."_system_user WHERE ID = $Id";
	$EditUser=$Db->Select($Query);
	if ($Db->IsExists(PFX."_tracker_admin", "USER_ID", $EditUser->ID)) return;
	if ($Db->IsExists(PFX."_tracker_user", "USER_ID", $EditUser->ID)) return;
	$Query = "INSERT INTO ".PFX."_tracker_admin (USER_ID) VALUES (".$EditUser->ID.")";
	$Db->Query($Query);
	$Query = "SELECT ID FROM ".PFX."_tracker_user_column WHERE USER_ID=$Id";
	$Check=$Db->ReturnValue($Query);
	if (!$Check) {
		$Query = "INSERT INTO ".PFX."_tracker_user_column (USER_ID) VALUES ($Id)";
		$Db->Query($Query);
		$Query = "INSERT INTO ".PFX."_tracker_user_settings (USER_ID) VALUES ($Id)";
		$Db->Query($Query);
	}
	$nsProduct->Redir("agents", "RUpd=1&EditUid=".$EditUser->ID);
}

function ConvertFromAgent($Id) 
{
	global $Db, $nsProduct;
	$Query = "SELECT * FROM ".PFX."_tracker_admin WHERE USER_ID = $Id";
	$Check=$Db->Select($Query);
	$Query = "SELECT COUNT(*) FROM ".PFX."_tracker_admin WHERE SUPER_ADMIN = '1'";
	$Cnt=$Db->ReturnValue($Query);
	if ($Check->SUPER_ADMIN==1&&$Cnt<2) return false;
	$Query = "DELETE FROM ".PFX."_tracker_admin WHERE USER_ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_column WHERE USER_ID=$Id ";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_settings WHERE USER_ID=$Id ";
	$Db->Query($Query);
	if (isset($GLOBALS['UnregisterAdmin'])) $nsProduct->Redir("agents", "RUpd=1");
	return true;
}

function DeleteAdmin($Id)
{
	global $Db, $nsProduct;
	if (!ConvertFromAgent($Id)) return false;
	$Query = "DELETE FROM ".PFX."_system_user WHERE ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_column WHERE USER_ID=$Id ";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_settings WHERE USER_ID=$Id ";
	$Db->Query($Query);
	$nsProduct->Redir("agents", "RDlt=1");
}


//////////////////////////////////////////
/// MISC

function GetUserQuery($Id=false) {
	$Query = "
		SELECT 
			SU.*,
			TA.SUPER_ADMIN, TA.ID AS AGENT_ID, TA.DEMO, 
			UNIX_TIMESTAMP(TA.MODIFIED) AS MODIFIED,
			UC.HITS, UC.SALES, UC.ACTIONS, UC.CLICKS, UC.ROI, UC.CONVERSIONS, UC.GRAPHS,
			US.ADVANCED_MODE, US.HELP_MODE, US.TIMEZONE, US.DEF_PATH_ORDER,
			US.PAGE_ENCODING
			FROM ".PFX."_tracker_admin TA
				INNER JOIN ".PFX."_system_user SU
					ON SU.ID = TA.USER_ID
				LEFT JOIN ".PFX."_tracker_user_column UC
					ON UC.USER_ID=SU.ID
				LEFT JOIN ".PFX."_tracker_user_settings US
					ON US.USER_ID=SU.ID
	";
	if (ValidId($Id)) $Query .= "WHERE SU.ID = $Id";
	return $Query;
}


?>