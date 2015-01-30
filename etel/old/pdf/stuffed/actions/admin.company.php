<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";
require_once SELF."/class/pagenums2.class.php";
require_once SELF."/lib/company.func.php";
require_once SELF."/lib/delete.func.php";


/////////////////////////////////////////////
///////// prepare any variables
$PageTitle=$Lang['Title'];

$nsLang->TplInc("inc/user_welcome");
$MenuSection="admin";
$PlaceForPlus=false;


$EditId=(ValidVar($_GP['EditId']))?$_GP['EditId']:false;
$EditArr=(ValidArr($_GP['EditArr']))?$_GP['EditArr']:false;
$EditArr2=(ValidArr($_GP['EditArr2']))?$_GP['EditArr2']:false;
$DeleteId=(ValidId($_GP['DeleteId']))?$_GP['DeleteId']:false;
$SortId=(ValidId($_GP['SortId']))?$_GP['SortId']:false;
$SortTo=(ValidVar($_GP['SortTo']))?$_GP['SortTo']:false;
$DeleteHost=(ValidId($_GP['DeleteHost']))?$_GP['DeleteHost']:false;
$HostId=(ValidVar($_GP['HostId']))?$_GP['HostId']:false;
$EditPage=(ValidId($_GP['EditPage']))?$_GP['EditPage']:false;
$ShowHidden=(ValidId($_GP['ShowHidden']))?$_GP['ShowHidden']:false;
$DeletePage=(ValidId($_GP['DeletePage']))?$_GP['DeletePage']:false;
$Templ=(ValidVar($_GP['Templ']))?$_GP['Templ']:false;
$Enable=(ValidVar($_GP['Enable']))?$_GP['Enable']:false;
$SiteId=(ValidId($_GP['SiteId']))?$_GP['SiteId']:false;
$DelSiteHost=(ValidId($_GP['DelSiteHost']))?$_GP['DelSiteHost']:false;
$NewHost=(ValidVar($_GP['NewHost']))?$_GP['NewHost']:false;


$ClientsCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_client");
$SitesCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site");
$ShowExpand=-1;

$ShowEventForm=false;
if (ValidId($EditEvent)) {
	$Event=GetEvent($EditEvent);
}
if (ValidId($EditPage)) {
	$EditSPage=GetPage($EditPage);
	$HostId=$EditSPage->SITE_ID;
}
if (ValidId($HostId)) {
	$EditHost=GetHost($HostId);
	$EditId=$EditHost->COMPANY_ID;
}
if (ValidId($EditId)) $EditCompany=GetCompany($EditId);


$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=($nsProduct->LICENSE==3&&$nsUser->ADMIN)?$Lang['Title']:$Lang['ClientTitle'];
$ProgPath[1]['Url']=getURL("company", ($nsProduct->LICENSE==3&&$nsUser->ADMIN)?"":"EditId=".$EditId, "admin");
if (ValidId($EditId)) {
	$ProgPath[2]['Name']=$EditCompany->NAME;
	$ProgPath[2]['Url']=getURL("company", "EditId=".$EditCompany->ID, "admin");
}


if (!$nsUser->ADMIN&&!$EditId) $nsProduct->Redir("no_permission", "", "error");
if ($nsUser->MERCHANT&&$EditId!=$nsUser->COMPANY_ID) $nsProduct->Redir("no_permission", "", "error");
if ($nsProduct->LICENSE!=3&&$EditId!=$CurrentCompany->ID) $nsProduct->Redir("no_permission", "", "error");

/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {

	if (!$HostId&&ValidArr($EditArr)&&ValidVar($EditId)=="new") CreateCompany($EditArr);
	if (!$HostId&&ValidArr($EditArr)&&ValidId($EditId)) UpdateCompany($EditId, $EditArr);
	if (ValidId($DeleteId)) DeleteCompany($DeleteId);
	if (ValidId($DeleteHost)) DeleteHost($EditId, $DeleteHost);
	if ($HostId=="new"&&ValidArr($EditArr)) CreateNewHost($EditId, $EditArr);
	if (ValidId($HostId)&&!ValidVar($EditPage)&&!ValidVar($EditEvent)&&ValidArr($EditArr)) UpdateHost($HostId, $EditArr);
	if ($EditPage=="new"&&ValidArr($EditArr)) AddSitePage($HostId, $EditArr);
	if (ValidId($EditPage)&&ValidArr($EditArr)) UpdatePage($EditPage, $EditArr);
	if (ValidId($DeletePage)) DeletePage($DeletePage);
	if (ValidId($EditEvent)&&ValidArr($EditArr2)) UpdateQuery($EditEvent, $EditArr2);
	if (ValidVar($EditEvent)=="new"&&ValidId($EventPage)&&ValidArr($EditArr2)) CreateQuery($EventPage, $EditArr2);
	if (ValidId($DeleteEvent)) DeleteQuery($DeleteEvent);
	if (ValidId($HostId)&&ValidVar($NewHost)) CreateNewSiteHost($HostId, $NewHost);
	if (ValidArr($Enable)) SetEnable($Enable);
	if (ValidId($DelSiteHost)) DeleteSiteHost($DelSiteHost);

}
/////////////////////////////////////////////
///////// display section here

//// companies list
if (!$EditPage&&!$HostId&&!$EditId) {
	if (isset($ShowHidden)&&$ShowHidden==1) $Hidden="";
	else $Hidden="HIDDEN!='1'";
	$RecCount=$Db->CNT(PFX."_tracker_client", $Hidden);
	$Pages=new PageNums($RecCount, 50);
	$Pages->Calculate();
	if ($Hidden) $Hidden="WHERE ".$Hidden;
	$Query = "SELECT *, UNIX_TIMESTAMP(MODIFIED) AS MODIFIED FROM ".PFX."_tracker_client $Hidden ORDER BY NAME ASC LIMIT ".$Pages->PageStart.", ".$Pages->Limit." ";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row = $Sql->Row()) {
		$Row->NAME=stripslashes($Row->NAME);
		$Row->DESCRIPTION=stripslashes($Row->DESCRIPTION);
		$Row->_STYLE=$Sql->_STYLE;
		$ClientsArr[$Sql->Position]=$Row;
		$PrevRow=&$ClientsArr[$Sql->Position];
	}
	$SubMenu[0]['Name']=$Lang['AddNew'];
	$SubMenu[0]['Link']=getURL("company", "EditId=new");
	$SubMenu[1]['Name']=$Lang['ShowHidden'];
	$SubMenu[1]['Link']=getURL("company", "ShowHidden=1");
	include $nsTemplate->Inc("admin.company");
}

//// new company 
if ($EditId&&!$HostId&&$EditId=="new") {
	$TableCaption=$Lang['CaptionNew'];
	if (!isset($EditArr)||!is_array($EditArr)) {
		$EditArr['Name']="";
		$EditArr['Descr']="";
		$EditArr['Hidden']="";
		$EditArr['Currency']="";
		$EditArr['CurrencyPos']=0;
		$EditArr['Watch']=0;
		$EditArr['MaxSites']=0;
		$EditArr['AllowPhp']=0;
	}
	else {
		$EditArr['Currency']=htmlspecialchars($EditArr['Currency']);
	}
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("company");
	include $nsTemplate->Inc("admin.company_edit");
}

//// edit company
if (!$HostId&&ValidId($EditId)) {

	$CSitesCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site WHERE COMPANY_ID=$EditId");
	if ($CSitesCnt==0) $Consult->CurrentHelp="no_site";

	$PageTitle=stripslashes($EditCompany->NAME);
	$EditCompany->NAME=stripslashes(htmlspecialchars($EditCompany->NAME));
	$EditCompany->DESCRIPTION=stripslashes(htmlspecialchars($EditCompany->DESCRIPTION));
	$TableCaption=$Lang['CaptionEdit'].$EditCompany->NAME;
	if (!ValidArr($EditArr)) {
		$EditArr['Name']=$EditCompany->NAME;
		$EditArr['Hidden']=$EditCompany->HIDDEN;
		$EditArr['Descr']=$EditCompany->DESCRIPTION;
		$EditArr['Currency']=$EditCompany->CURRENCY;
		$EditArr['CurrencyPos']=$EditCompany->CURRENCY_POSITION;
		$EditArr['MaxSites']=$EditCompany->MAX_SITES;
		$EditArr['AllowPhp']=$EditCompany->ALLOW_PHP_TRACKING;
	}
	$EditArr['Currency']=htmlspecialchars($EditArr['Currency']);
	$EditArr['Watch']=(CheckCompWatch($EditCompany->ID, $nsUser->UserId()))?1:0;
	$HostsArr=GetHostsList($EditCompany->ID);
	for($i=0;$i<count($HostsArr);$i++) {
		if ($HostsArr[$i]->USE_HOSTS) {
			$PlaceForPlus=true;
			break;
		}
	}
	if ($nsUser->ADMIN&&$nsProduct->LICENSE==3) {
		$SubMenu[0]['Name']=$Lang['BackToList'];
		$SubMenu[0]['Link']=getURL("company");
	}
	if ($SitesCnt<$nsProduct->MAX_SITES && (($EditCompany->MAX_SITES>0 && $CSitesCnt<$EditCompany->MAX_SITES)||$EditCompany->MAX_SITES==0) ) {
		$SubMenu[1]['Name']=$Lang['AddNewHost'];
		$SubMenu[1]['Link']=getURL("company", "HostId=new&EditId=$EditId");
	}
	$SubMenu[2]['Name']=$Lang['Stat'];
	$SubMenu[2]['Link']=getURL("natural_constructor", "CpId=$EditId", "report");
	include $nsTemplate->Inc("admin.company_edit");
}

//// new host
if ($HostId=="new") {
	$PageTitle=stripslashes($EditCompany->NAME);
	$TableCaption=$Lang['CaptionNew'];
	if (!ValidArr($EditArr)) {
		$EditArr['Host']="";
		$EditArr['UseHosts']=0;
		$EditArr['Watch']=0;
		$EditArr['ShowTitles']=0;
		$EditArr['CookieDomain']='';

	}
	else {
		if (!ValidVar($EditArr['UseHosts'])) $EditArr['UseHosts']=0;
		if (!ValidVar($EditArr['Watch'])) $EditArr['Watch']=0;
		if (!ValidVar($EditArr['ShowTitles'])) $EditArr['ShowTitles']=0;
	}
	$SubMenu[0]['Name']=$Lang['BackToEdit'];
	$SubMenu[0]['Link']=getURL("company", "EditId=$EditId");
	if ($nsUser->ADMIN&&$nsProduct->LICENSE==3) {
		$SubMenu[1]['Name']=$Lang['BackToList'];
		$SubMenu[1]['Link']=getURL("company");
	}

	include $nsTemplate->Inc("admin.host_edit");
}

//// edit host
if (ValidId($HostId)&&ValidId($EditId)&&!$EditPage) {
	$PageTitle=stripslashes($EditCompany->NAME);
	$EditSet=$Db->Select("SELECT * FROM ".PFX."_tracker_config WHERE SITE_ID=".$EditHost->ID);

	if (ValidVar($Templ)) $Like=" AND PATH LIKE '%$Templ%'";
	else $Like="";
	$Cnt=$Db->CNT(PFX."_tracker_site_page", "SITE_ID=$HostId $Like");
	$Pages=new PageNums($Cnt, 100);
	$Pages->Args="&HostId=$HostId&EditId=$EditId";
	if (ValidVar($Templ)) $Pages->Args.="&Templ=$Templ";
	else $Templ=false;
	$PagesArr=GetPagesList($HostId, $Templ, $Pages->PageStart, $Pages->Limit);
	$Pages->Calculate();


	$TableCaption=$Lang['CaptionEdit'].$EditHost->HOST;
	if (!ValidArr($EditArr)) {
		$EditArr['Host']=$EditHost->HOST;
		$EditArr['UseHosts']=$EditHost->USE_HOSTS;
		$EditArr['CookieDomain']=$EditHost->COOKIE_DOMAIN;
	}
	$EditArr['Watch']=(CheckSiteWatch($HostId, $nsUser->UserId()))?1:0;
	$EventCaption=$Lang['CaptionEdit'];
	$EditArr['Host']=htmlspecialchars(stripslashes($EditArr['Host']));
	$SubMenu[0]['Name']=$Lang['BackToEdit'];
	$SubMenu[0]['Link']=getURL("company", "EditId=$EditId");
	if ($nsUser->ADMIN&&$nsProduct->LICENSE==3) {
		$SubMenu[1]['Name']=$Lang['BackToList'];
		$SubMenu[1]['Link']=getURL("company");
	}

	//// edit page event
	$NoPageActions=GetActions(0, $HostId);
	if (ValidVar($EditEvent)) {
		if	($EditEvent=="new"&&!ValidArr($EditArr2)) {
			$EventCaption=$Lang['CaptionNew'];
			$EditArr2['EvName']="";
			$EditArr2['EvQuery']="";
		}
		if (ValidId($EditEvent)&&!ValidId($EditArr2)) {
			$EventCaption=$Lang['CaptionEdit'].stripslashes($Event->NAME);
			$EditArr2['EvName']=$Event->NAME;
			$EditArr2['EvQuery']=$Event->QUERY;
		}
		$EditArr2['EvName']=htmlspecialchars(stripslashes($EditArr2['EvName']));
		$EditArr2['EvQuery']=htmlspecialchars(stripslashes($EditArr2['EvQuery']));
		$ShowEventForm=true;
	}
	else $ShowEventForm=false;

	include $nsTemplate->Inc("admin.host_edit");
}


//// new page 
if ($EditPage=="new") {
	$TableCaption=$Lang['CaptionNew'];
	if (!ValidArr($EditArr)) {
		$EditArr['Name']="";
		$EditArr['Path']="";
		$EditArr['PageIgnore']=0;
	}
	$SubMenu[0]['Name']=$Lang['BackToHost'];
	$SubMenu[0]['Link']=getURL("company", "HostId=$HostId");
	$PageTitle=stripslashes($EditCompany->NAME);

	include $nsTemplate->Inc("admin.page_edit");
}

//// edit page 

if (ValidId($EditPage)) {
	if (!ValidArr($EditArr)) {
		$EditArr['Name']=$EditSPage->NAME;
		$EditArr['Path']=$EditSPage->PATH;
		$EditArr['PageIgnore']=$EditSPage->IGNORE_PAGE;
	}
	$SubMenu[0]['Name']=$Lang['BackToHost'];
	$SubMenu[0]['Link']=getURL("company", "HostId=$HostId");
	$PageTitle=stripslashes($EditCompany->NAME);

	$EditArr['Name']=htmlspecialchars(stripslashes($EditArr['Name']));
	$TableCaption=$Lang['CaptionEdit'].stripslashes($EditSPage->NAME);
	include $nsTemplate->Inc("admin.page_edit");
}


/////////////////////////////////////////////
///////// process functions here


function CreateNewHost($CompId, &$Arr)
{
	global $Db, $nsProduct, $Logs, $Lang, $SitesCnt;
	if ($SitesCnt>=$nsProduct->MAX_SITES) {
		$Logs->Err($Lang['StCntExceed']);
		return false;
	}
	$CSiteCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CompId");
	$MaxCSiteCnt=$Db->ReturnValue("SELECT MAX_SITES FROM ".PFX."_tracker_client WHERE ID=$CompId");
	if ($MaxCSiteCnt > 0 && $nsProduct->LICENSE==3 && $CSiteCnt >= $MaxCSiteCnt) {
		$Logs->Err($Lang['StCntExceed']);
		return false;
	}
	extract($Arr);
	if (!ValidVar($UseHosts)) $UseHosts=0;
	if (ValidVar($ShowTitles)!=1) $ShowTitles=0;
	if(!$Host) $ErrArr['Host']=$Lang['MustFill'];
	$Host=ToLower($Host);
	$Check=@parse_url($Host);
	if (ValidArr($Check)&&ValidVar($Check['scheme'])) {
		$Host=str_replace($Check['scheme']."://", "", $Host);
	}
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}

	$Query = "INSERT INTO ".PFX."_tracker_site (COMPANY_ID, USE_HOSTS, HOST, COOKIE_DOMAIN) VALUES ($CompId, '$UseHosts', ?, ?)";
	$Db->Query($Query, $Host, $CookieDomain);
	$SiteId=$Db->LastInsertId;
	$Query = "INSERT INTO ".PFX."_tracker_config (COMPANY_ID, SITE_ID, KEEP_VISITOR_PATH, KEEP_NO_REF) VALUES ($CompId, $SiteId, '2', '2')";
	$Db->Query($Query);

	CreateNewSiteHost($SiteId, $Host, true);
	$Query = "INSERT INTO ".PFX."_tracker_site_page (SITE_ID, PATH) VALUES ($SiteId, '/')";
	$Db->Query($Query);
	if ($UseHosts==1) $nsProduct->Redir("company", "EditId=$CompId&HostId=$SiteId&RCrt=1"); 
	else $nsProduct->Redir("company", "EditId=$CompId&RCrt=1");
}

function UpdateHost($Id, &$Arr)
{
	global $Db, $nsProduct, $Logs, $Lang, $EditId, $nsUser;
	extract($Arr);
	if (ValidVar($UseHosts)!=1) $UseHosts=0;
	if (ValidVar($ShowTitles)!=1) $ShowTitles=0;
	if(!$Host) $ErrArr['Host']=$Lang['MustFill'];
	$Host=ToLower($Host);
	$Check=@parse_url($Host);
	if (ValidArr($Check)&&ValidVar($Check['scheme'])) {
		$Host=str_replace($Check['scheme']."://", "", $Host);
	}
	//$Host=str_replace("http://", "", $Host);
	//if (CheckMiscSymb($Host, "\-\.")) $ErrArr['Host']=$Lang['SymbErr'];
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_site SET HOST = ?, USE_HOSTS='$UseHosts', COOKIE_DOMAIN=? WHERE ID = $Id";
	$Db->Query($Query, $Host, $CookieDomain);
	$CheckId=CheckSiteWatch($Id, $nsUser->UserId());
	$SiteWatch=ValidId($CheckId);
	if (ValidVar($Watch)==1&&!$SiteWatch) SetSiteWatch($Id, $nsUser->UserId());
	if (ValidVar($Watch)!=1&&$SiteWatch)  RemoveSiteWatch($Id, $nsUser->UserId());
	$nsProduct->Redir("company", "EditId=$EditId&HostId=$Id&RUpd=1");	
}


function CreateCompany(&$Arr)
{
	global $Db, $nsProduct, $Logs, $Lang, $ClientsCnt, $nsUser;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!isset($Hidden)) $Hidden=0;
	if (!isset($AllowPhp)) $AllowPhp=0;
	if (!ValidVar($MaxSites)) $MaxSites="0";
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "INSERT INTO ".PFX."_tracker_client (HIDDEN, NAME, DESCRIPTION, CURRENCY, CURRENCY_POSITION) VALUES (?, ?, ?, ?, ?)";
	$Db->Query($Query, $Hidden, $Name, $Descr, $Currency, $CurrencyPos);
	$NewId=$Db->LastInsertId;
	if ($nsProduct->LICENSE==3 && $nsUser->ADMIN) {	
		$Query = "UPDATE ".PFX."_tracker_client SET MAX_SITES = $MaxSites, ALLOW_PHP_TRACKING = '$AllowPhp' WHERE ID = $NewId";
		$Db->Query($Query);
	}	
	$Query = "INSERT INTO ".PFX."_tracker_config (COMPANY_ID, SITE_ID, KEEP_VISITOR_PATH, KEEP_NO_REF) VALUES ($NewId, 0, '2', '2')";
	$Db->Query($Query);
	CreateStatTables($NewId);
	$nsProduct->Redir("company", "RCrt=1&EditId=$NewId");
}

function UpdateCompany($Id, &$Arr) 
{
	global $Db, $nsProduct, $Logs, $Lang, $nsUser;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!isset($Hidden)) $Hidden=0;
	if (!isset($AllowPhp)) $AllowPhp=0;
	if (!ValidVar($MaxSites)) $MaxSites="0";
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_client SET NAME = ?, DESCRIPTION = ?, HIDDEN = ?, CURRENCY = ?, CURRENCY_POSITION = ? WHERE ID = $Id";
	$Db->Query($Query, $Name, $Descr, $Hidden, $Currency, $CurrencyPos);
	if ($nsProduct->LICENSE==3 && $nsUser->ADMIN) {	
		$Query = "UPDATE ".PFX."_tracker_client SET MAX_SITES = $MaxSites, ALLOW_PHP_TRACKING = '$AllowPhp' WHERE ID = $Id";
		$Db->Query($Query);
	}

	if (ValidVar($Watch)==1&&!CheckCompWatch($Id, $nsUser->UserId())) SetCompWatch($Id, $nsUser->UserId());
	else RemoveCompWatch($Id, $nsUser->UserId());

	if ($nsUser->ADMIN) $nsProduct->Redir("company", "RUpd=1");
	if ($nsUser->MERCHANT) $nsProduct->Redir("company", "RUpd=1&EditId=$Id");
}



function AddSitePage($SiteId, &$Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $EditCompany;
	extract($Arr);
	//if(!$Name) $Name=""; 
	//$ErrArr['Name']=$Lang['MustFill'];
	if(!$Path) $ErrArr['Path']=$Lang['MustFill'];
	if (!isset($PageIgnore)) $PageIgnore=0;
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "INSERT INTO ".PFX."_tracker_site_page (SITE_ID, NAME, PATH, IGNORE_PAGE) VALUES ($SiteId, ?, ?, ?)";
	$Db->Query($Query, $Name, $Path, $PageIgnore);
	$nsProduct->Redir("company", "RCrt=1&EditPage=".$Db->LastInsertId);
}

function UpdatePage($Id, &$Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $EditCompany;
	extract($Arr);
	//if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if(!$Path) $ErrArr['Path']=$Lang['MustFill'];
	if (!isset($PageIgnore)) $PageIgnore=0;
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_site_page SET NAME = ?, PATH = ?, IGNORE_PAGE=? WHERE ID = $Id";
	$Db->Query($Query, $Name, $Path, $PageIgnore);
	$nsProduct->Redir("company", "RUpd=1&EditPage=$Id");
}


function DeletePage($Id)
{
	$EditPage=GetPage($Id);
	global $Db, $nsProduct;
	$Query = "DELETE FROM ".PFX."_tracker_site_page WHERE ID = $Id";
	$Db->Query($Query);
	$nsProduct->Redir("company", "RDlt=1&HostId=".$EditPage->SITE_ID);
}


function UpdateQuery($Id, &$Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $HostId;
	extract($Arr);
	if(!$EvQuery&&!$EvName) $ErrArr['EvName']=$Lang['MustFill'];
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_visitor_action SET NAME = ?, QUERY = ? WHERE ID = $Id";
	$Db->Query($Query, $EvName, $EvQuery);
	$nsProduct->Redir("company", "RUpd=1&HostId=$HostId&EditEvent=$Id");
}

function CreateQuery($PageId, &$Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $HostId;
	extract($Arr);
	if(!$EvQuery&&!$EvName) $ErrArr['EvName']=$Lang['MustFill'];
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "INSERT INTO ".PFX."_tracker_visitor_action (PAGE_ID, SITE_ID, NAME, QUERY) VALUES ($PageId, $HostId, ?, ?)";
	$Db->Query($Query, $EvName, $EvQuery);
	$nsProduct->Redir("company", "RCrt=1&HostId=$HostId");
}

function DeleteQuery($Id)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_visitor_action WHERE ID = $Id";
	$Db->Query($Query);
}



function SetSiteWatch($SiteId, $UserId)
{
	global $Db;
	$Query = "INSERT INTO ".PFX."_tracker_watch (SITE_ID, USER_ID) VALUES ($SiteId, $UserId)";
	$Db->Query($Query);
}

function RemoveSiteWatch($SiteId, $UserId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE SITE_ID=$SiteId AND USER_ID=$UserId";
	$Db->Query($Query);
}


function CheckSiteWatch($SiteId, $UserId)
{
	global $Db;
	$Query = "SELECT ID FROM ".PFX."_tracker_watch WHERE SITE_ID=$SiteId AND USER_ID=$UserId";
	return $Db->ReturnValue($Query);
}




function SetCompWatch($CompId, $UserId)
{
	global $Db;
	$Query = "INSERT INTO ".PFX."_tracker_watch (COMPANY_ID, USER_ID) VALUES ($CompId, $UserId)";
	$Db->Query($Query);
}

function RemoveCompWatch($CompId, $UserId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE COMPANY_ID=$CompId AND USER_ID=$UserId";
	$Db->Query($Query);
}


function CheckCompWatch($CompId, $UserId)
{
	global $Db;
	$Query = "SELECT ID FROM ".PFX."_tracker_watch WHERE COMPANY_ID=$CompId AND USER_ID=$UserId";
	return $Db->ReturnValue($Query);
}


function CreateNewSiteHost($SiteId, $Host, $NoRedir=false)
{
	global $Db, $EditId, $nsProduct;
	$Host=ToLower(trim($Host));
	$Check=array();
	$Check=@parse_url($Host);
	if (ValidArr($Check)&&ValidVar($Check['scheme'])) {
		$Host=str_replace($Check['scheme']."://", "", $Host);
	}
	$CheckId=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_site_host WHERE SITE_ID=$SiteId AND HOST = '$Host'");
	if (!$CheckId) {
		$Query = "INSERT INTO ".PFX."_tracker_site_host (SITE_ID, HOST, ENABLED) VALUES ($SiteId, ?, '1')";
		$Db->Query($Query, $Host);
	}
	if (!$NoRedir) $nsProduct->Redir("company", "RCrt=1&EditId=$EditId&HostId=$SiteId");
}

function SetEnable($Arr)
{
	global $Db, $EditId, $HostId, $nsProduct;
	foreach ($Arr as $SHost => $Enable) {
		if ($Enable!=1&&$Enable!=0) continue;
		$Query = "UPDATE ".PFX."_tracker_site_host SET ENABLED = '$Enable' WHERE ID = $SHost";
		$Db->Query($Query);
	}
	$nsProduct->Redir("company", "EditId=$EditId&HostId=$HostId&RUpd=1");
}

function DeleteSiteHost($Id)
{
	global $Db, $EditId, $HostId, $nsProduct;
	$SiteId=$Db->ReturnValue("SELECT SITE_ID FROM ".PFX."_tracker_site_host WHERE ID = $Id");
	//$HCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site_host WHERE SITE_ID=$SiteId");
	$Query = "DELETE FROM ".PFX."_tracker_site_host WHERE ID = $Id";
	$Db->Query($Query);
	//if ($HCnt==1) {
	//	$SiteHost=$Db->ReturnValue("SELECT HOST FROM ".PFX."_tracker_site WHERE ID = $SiteId");
	//	CreateNewSiteHost($SiteId, $SiteHost, true);
	//}
	DeleteSiteHostStat($EditId, $SiteId, $Id);
	$nsProduct->Redir("company", "RDlt=1&EditId=$EditId&HostId=$HostId");
}

/////////////////////////////////////////////
///////// free section




?>