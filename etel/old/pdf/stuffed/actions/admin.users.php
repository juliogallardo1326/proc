<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged())$nsProduct->Redir("login", "", "admin");
if (!$nsUser->ADMIN&&!$nsUser->SUPER_USER&&!ValidId($_GP['EditUid'])) $nsProduct->Redir("default", "", "admin"); 
if (!$nsUser->ADMIN&&!$nsUser->SUPER_USER&&ValidId($_GP['EditUid'])&&$_GP['EditUid']!=$nsUser->UserId()) $nsProduct->Redir("default", "", "admin"); 

/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";
require_once SELF."/lib/form.func.php";

/////////////////////////////////////////////
///////// prepare any variables
$PageTitle=$Lang['Title'];
if (isset($_GET['EditUid'])) $EditUid=$_GET['EditUid'];
if (isset($_GET['MakeUser'])) $MakeUser=$_GET['MakeUser'];
if (isset($_GET['UnregisterUser'])) $UnregisterUser=$_GET['UnregisterUser'];
if (isset($_POST['EditUid'])) $EditUid=$_POST['EditUid'];
if (isset($_GET['DeleteUid'])) $DeleteUid=$_GET['DeleteUid'];
if (isset($_POST['DeleteUid'])) $DeleteUid=$_POST['DeleteUid'];
if (isset($_POST['EditArr'])) $EditArr=$_POST['EditArr'];
$SkinsArr=array();
$LangsArr=array();
$MenuSection="admin";

$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=(ValidVar($EditUid)==$nsUser->UserId())?$Lang['Profile']:$Lang['Title'];
$ProgPath[1]['Url']=getURL("users", "", "admin");


/////////////////////////////////////////////
///////// call any process functions
if (!$nsUser->DEMO) {
	if (isset($EditArr)&&is_array($EditArr)&&isset($EditUid)&&$EditUid=="new") CreateUser($EditArr);
	if (isset($EditArr)&&is_array($EditArr)&&isset($EditUid)&&ValidId($EditUid)) UpdateUser($EditUid, $EditArr);
	if ($nsUser->ADMIN && isset($MakeUser)&&ValidId($MakeUser)) ConvertToUser($MakeUser);
	if ($nsUser->ADMIN && isset($UnregisterUser)&&ValidId($UnregisterUser)) ConvertFromUser($UnregisterUser);
	if (isset($DeleteUid)&&ValidId($DeleteUid)) DeleteUser($DeleteUid);
}


/////////////////////////////////////////////
///////// display section here

	$Query = "SELECT * FROM ".PFX."_system_user SU";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$NonTrackerUsersList=array();
	while ($Row = $Sql->Row()) {
		if ($Db->IsExists(PFX."_tracker_admin", "USER_ID", $Row->ID)) continue;
		if ($Db->IsExists(PFX."_tracker_user", "USER_ID", $Row->ID)) continue;
		$Row->_STYLE=$Sql->_STYLE;
		$Row->NAME=stripslashes($Row->NAME);
		$NonTrackerUsersList[]=$Row;
	}




//// users list
if ((!isset($EditUid)||(!ValidId($EditUid)&&$EditUid!="new"&&$EditUid!="perms"))&&
	(!isset($DeleteUid)||!ValidId($DeleteUid))) {
	$Sql = new Query(GetUserQuery());
	$Sql->ReadSkinConfig();
	while ($Row = $Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Row->COMP_NAME=stripslashes($Row->COMP_NAME);
		$Row->NAME=stripslashes($Row->NAME);
		$UsersArr[]=$Row;
	}
	if ($nsProduct->LICENSE!=3) {
		$AgentsArr=array();
		$Query ="
			SELECT 
			SU.*,
			TA.SUPER_ADMIN, TA.ID AS AGENT_ID
			FROM ".PFX."_tracker_admin TA
				INNER JOIN ".PFX."_system_user SU
					ON SU.ID = TA.USER_ID
		";
		$Sql = new Query($Query);
		$Sql->ReadSkinConfig();
		while ($Row = $Sql->Row()) {
			$Row->_STYLE=$Sql->_STYLE;
			$Row->NAME=stripslashes($Row->NAME);
			$AgentsArr[]=$Row;
		}
	}
	$SubMenu[0]['Name']=$Lang['AddNew'];
	$SubMenu[0]['Link']=getURL("users", "EditUid=new");
	if (count($NonTrackerUsersList)>0 && $nsUser->ADMIN) {
		$SubMenu[1]['Name']=$Lang['NoPermsList'];
		$SubMenu[1]['Link']=getURL("users", "EditUid=perms");
	}
	include $nsTemplate->Inc("admin.users");
}

/// Non tracker users listing
if (isset($EditUid)&&!ValidId($EditUid)&&$EditUid=="perms") {
	$UsersList=$NonTrackerUsersList;
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("users");
	include $nsTemplate->Inc("admin.users_noperms");
}


// Create new
if (isset($EditUid)&&$EditUid=="new") {
	if ($nsUser->ADMIN) {
		$CompList=array();
		$Query = "SELECT * FROM ".PFX."_tracker_client ORDER BY NAME ASC";
		$Sql = new Query($Query);
		while ($Row = $Sql->Row()) {
			$CompList[$Sql->Position]['Name']=stripslashes(htmlspecialchars($Row->NAME));
			$CompList[$Sql->Position]['Value']=$Row->ID;
		}
	}
	if (!isset($EditArr)||!is_array($EditArr)) {
		$EditArr['Login']="";
		$EditArr['Email']="";
		$EditArr['Name']="";
		$EditArr['Super']=0;
		$EditArr['AdvMode']=0;
		$EditArr['HelpMode']=2;
		$EditArr['Pass']="";
		$EditArr['Pass2']="";
		$EditArr['TZ']="";	
		$EditArr['Enc']="";
		$EditArr['Demo']="";
	}
	if (!isset($EditArr['Super'])) $EditArr['Super']=0;
	if (!isset($EditArr['AdvMode'])) $EditArr['AdvMode']=0;
	if (!isset($EditArr['Company'])) $EditArr['Company']=0;
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
	if ($nsUser->ADMIN||$nsUser->SUPER_USER) {
		$SubMenu[0]['Name']=$Lang['BackToList'];
		$SubMenu[0]['Link']=getURL("users");
	}
	include $nsTemplate->Inc("admin.user_edit");
}

// Edit user
if (isset($EditUid)&&ValidId($EditUid)) {
	if ($nsUser->ADMIN) {
		$CompList=array();
		$Query = "SELECT * FROM ".PFX."_tracker_client ORDER BY NAME ASC";
		$Sql = new Query($Query);
		while ($Row = $Sql->Row()) {
			$CompList[$Sql->Position]['Name']=stripslashes(htmlspecialchars($Row->NAME));
			$CompList[$Sql->Position]['Value']=$Row->ID;
		}
	}
	$EditUser=$Db->Select(GetUserQuery($EditUid));
	if (!isset($EditUser->ID)) $nsProduct->Redir("users");
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
		$EditArr['Super']=$EditUser->SUPER_USER;
		$EditArr['AdvMode']=$EditUser->ADVANCED_MODE;
		$EditArr['Pass']="";
		$EditArr['Pass2']="";
		$EditArr['Company']=$EditUser->COMPANY_ID;
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
	if (!isset($EditArr['Company'])) $EditArr['Company']=0;
	if (!isset($EditArr['ColHits'])) $EditArr['ColHits']=0;
	if (!isset($EditArr['ColSales'])) $EditArr['ColSales']=0;
	if (!isset($EditArr['ColActions'])) $EditArr['ColActions']=0;
	if (!isset($EditArr['ColClicks'])) $EditArr['ColClicks']=0;
	if (!isset($EditArr['ColROI'])) $EditArr['ColROI']=0;
	if (!isset($EditArr['ColConv'])) $EditArr['ColConv']=0;
	if (!isset($EditArr['Graphs'])) $EditArr['Graphs']=0;
	if (!isset($EditArr['HelpMode'])) $EditArr['HelpMode']=0;

	$EditArr['Ignore']=ValidVar($_COOKIE['ns_skip']);
	$EditArr['Name']=stripslashes(htmlspecialchars($EditArr['Name']));
	$TableCaption=$Lang['CaptionEdit'].stripslashes($EditUser->NAME);
	if ($nsUser->ADMIN||$nsUser->SUPER_USER) {
		$SubMenu[0]['Name']=$Lang['AddNew'];
		$SubMenu[0]['Link']=getURL("users", "EditUid=new");
		$SubMenu[1]['Name']=$Lang['BackToList'];
		$SubMenu[1]['Link']=getURL("users");
	}
	
	include $nsTemplate->Inc("admin.user_edit");
}



/////////////////////////////////////////////
///////// process functions here


function CreateUser(&$Arr) 
{
	global $Db, $nsProduct, $Logs, $nsUser, $CurrentCompany, $Lang;
	extract($Arr);
	if (!isset($Super)) $Super=0;
	if (!isset($Demo)) $Demo=0;

	$Login=ToLower($Login);
	if (CheckSymb_($Login)) $ErrArr['Login']=$Lang['SymbErr'];
	if (CheckSymb_($Pass)) $ErrArr['Pass']=$Lang['SymbErr'];
	if (!ValidMail($Email)) $ErrArr['Email']=$Lang['MustFillCorr'];
	if (!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!$Company) $ErrArr['Company']=$Lang['CompanyRequired'];
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


	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']);$GLOBALS['ErrArr']=$ErrArr; return;}

	if (!$nsUser->ADMIN) $Company=$nsUser->COMPANY_ID;
	if (!$nsUser->ADMIN&&!$nsUser->SUPER_USER) $Super=0;
	if ($nsProduct->LICENSE!=3 || (!$nsUser->ADMIN&&!$nsUser->SUPER_USER)) $Demo=0;
	if ($nsProduct->LICENSE==2) $Company=$CurrentCompany->ID;

	$Query="INSERT INTO ".PFX."_system_user (LOGIN, EMAIL, NAME, PWD) VALUES (?, ?, ?, ?)";
	$Db->Query($Query, $Login, $Email, $Name, md5($Pass));
	$Max=$Db->LastInsertId;

	$Query = "INSERT INTO ".PFX."_tracker_user (USER_ID, SUPER_USER, COMPANY_ID, DEMO) VALUES ($Max, '$Super', $Company, '$Demo')";
	$Db->Query($Query);

	$Query = "INSERT INTO ".PFX."_tracker_user_column (USER_ID) VALUES ($Max)";
	$Db->Query($Query);
	$Query = "INSERT INTO ".PFX."_tracker_user_settings (USER_ID) VALUES ($Max)";
	$Db->Query($Query);


	$nsProduct->Redir("users", "RCrt=1&EditUid=$Max");
}	



function UpdateUser($Id, &$Arr) 
{
	global $Db, $nsProduct, $Logs, $nsUser, $CurrentCompany, $Lang, $_COOKIE;
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
	if (!$Company) $ErrArr['Company']=$Lang['CompanyRequired'];

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

	if (isset($ErrArr)) {$Logs->Err($Lang['FormErr']);$GLOBALS['ErrArr']=$ErrArr; return;}


	if (!$nsUser->ADMIN) $Company=$nsUser->COMPANY_ID;
	if (!$nsUser->ADMIN&&!$nsUser->SUPER_USER) $Super=0;
	if ($nsProduct->LICENSE==2) $Company=$CurrentCompany->ID;
	if ($nsProduct->LICENSE!=3 || (!$nsUser->ADMIN&&!$nsUser->SUPER_USER)) $Demo=$EditUser->DEMO;


	$Query = "UPDATE ".PFX."_system_user SET LOGIN = ? , NAME = ?, EMAIL = ? WHERE ID = $Id";
	$Db->Query($Query, $Login, $Name, $Email);
	$Query = "UPDATE ".PFX."_tracker_user SET COMPANY_ID = $Company WHERE ID = ".$EditUser->MERCH_ID;
	$Db->Query($Query);

	if ($nsUser->UserId()!=$EditUser->ID) {
		$Query = "UPDATE ".PFX."_tracker_user SET SUPER_USER = '$Super', DEMO='$Demo' WHERE ID = ".$EditUser->MERCH_ID;
		$Db->Query($Query);
	}

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
		$Query = "UPDATE ".PFX."_tracker_user_column SET HITS='$ColHits', SALES='$ColSales', ACTIONS='$ColActions', CLICKS='$ColClicks', ROI='$ColROI', CONVERSIONS='$ColConv', GRAPHS='$Graphs' WHERE USER_ID=$Id";
		$Db->Query($Query);
		$Query = "UPDATE ".PFX."_tracker_user_settings SET ADVANCED_MODE='$AdvMode', HELP_MODE='$HelpMode', TIMEZONE='$TZ', PAGE_ENCODING = '$Enc' WHERE USER_ID=$Id";
		$Db->Query($Query);
	}

	if (ValidVar($Ignore)) $nsUser->SetCookie(COOKIE_PFX.'skip', "1", time()+60*60*24*10*365, "/");
	else $nsUser->SetCookie(COOKIE_PFX.'skip', "", time()-100, "/");
	if (!$nsUser->ADMIN&&!$nsUser->SUPER_USER) $nsProduct->Redir("users", "RUpd=1&EditUid=$Id");
	$nsProduct->Redir("users", "RUpd=1");
}	

function ConvertToUser($Id) 
{
	global $Db, $nsProduct, $nsUser, $CurrentCompany;
	$Query = "SELECT * FROM ".PFX."_system_user WHERE ID = $Id";
	$EditUser=$Db->Select($Query);
	if ($Db->IsExists(PFX."_tracker_admin", "USER_ID", $EditUser->ID)) return;
	if ($Db->IsExists(PFX."_tracker_user", "USER_ID", $EditUser->ID)) return;
	if (!$nsUser->ADMIN) $CpId=$nsUser->COMPANY_ID;
	if ($nsUser->ADMIN) $CpId=0;
	if (!$CpId) $CpId=0;

	$Query = "INSERT INTO ".PFX."_tracker_user (USER_ID, COMPANY_ID) VALUES (".$EditUser->ID.", $CpId)";
	$Db->Query($Query);
	$Query = "INSERT INTO ".PFX."_tracker_user_column (USER_ID) VALUES ($Id)";
	$Db->Query($Query);
	$Query = "INSERT INTO ".PFX."_tracker_user_settings (USER_ID) VALUES ($Id)";
	$Db->Query($Query);

	$nsProduct->Redir("users", "RUpd=1&EditUid=".$EditUser->ID);
}

function ConvertFromUser($Id) 
{
	global $Db, $nsProduct;
	$Query = "DELETE FROM ".PFX."_tracker_user WHERE USER_ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_column WHERE USER_ID=$Id ";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_settings WHERE USER_ID=$Id ";
	$Db->Query($Query);
	if (isset($GLOBALS['UnregisterUser'])) $nsProduct->Redir("users", "RUpd=1");
	return true;
}

function DeleteUser($Id)
{
	global $Db, $nsProduct, $nsUser;
	$CheckId=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_admin WHERE USER_ID=$Id");
	if ($CheckId>0&&$nsProduct->LICENSE!=3&&$nsUser->SUPER_USER) {
		if ($Id==$nsUser->UserId()) $nsProduct->Redir("users");
		$Cnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_admin");
		if ($Cnt==1) ConvertUserToAdmin();
		$Query = "DELETE FROM ".PFX."_tracker_admin WHERE USER_ID=$Id";
		$Db->Query($Query);
	}
	if ($CheckId&&($nsProduct->LICENSE==3||!$nsUser->SUPER_USER)) $nsProduct->Redir("users");
	if (!$CheckId) ConvertFromUser($Id);
	$Query = "DELETE FROM ".PFX."_system_user WHERE ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_column WHERE USER_ID=$Id ";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_settings WHERE USER_ID=$Id ";
	$Db->Query($Query);
	$nsProduct->Redir("users", "RDlt=1");
}


function ConvertUserToAdmin()
{
	global $Db, $nsUser, $nsProduct;
	if ($nsProduct->LICENSE==3) return false;
	$Query = "INSERT INTO ".PFX."_tracker_admin (USER_ID, SUPER_ADMIN, ADVANCED_MODE) VALUES (?, '1', ?)";
	$Db->Query($Query, $nsUser->UserId(), $nsUser->ADVANCED_MODE);
	$Query = "DELETE FROM ".PFX."_tracker_user WHERE USER_ID=".$nsUser->UserId();
	$Db->Query($Query);
}

/////////////////////////////////////////////
///////// free section

function GetUserQuery($Id=false) {
	global $nsUser;
	$Query = "
		SELECT 
			SU.*,
			TA.SUPER_USER, TA.ID AS MERCH_ID, TA.COMPANY_ID, TA.DEMO, 
			UNIX_TIMESTAMP(TA.MODIFIED) AS MODIFIED,
			TC.NAME AS COMP_NAME,
			UC.HITS, UC.SALES, UC.ACTIONS, UC.CLICKS, UC.ROI, UC.CONVERSIONS, UC.GRAPHS,
			US.ADVANCED_MODE, US.HELP_MODE, US.TIMEZONE, US.DEF_PATH_ORDER,
			US.PAGE_ENCODING
			FROM ".PFX."_system_user SU
				INNER JOIN ".PFX."_tracker_user TA
					ON TA.USER_ID= SU.ID
				LEFT JOIN ".PFX."_tracker_client TC
					ON TC.ID = TA.COMPANY_ID
				LEFT JOIN ".PFX."_tracker_user_column UC
					ON UC.USER_ID=SU.ID
				LEFT JOIN ".PFX."_tracker_user_settings US
					ON US.USER_ID=SU.ID
	";
	if (ValidId($Id)) $Query .= "WHERE SU.ID = $Id";
	if (!ValidId($Id)&&!$nsUser->ADMIN) $Query.="WHERE TA.COMPANY_ID=".$nsUser->COMPANY_ID;
	return $Query;
}


?>