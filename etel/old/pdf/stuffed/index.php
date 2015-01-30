<?
# ============================================================================
#
#                        ___
#                    ,yQ$SSS$Q·,      ,yQQQL
#                  j$$"`     `?$'  ,d$P"```c$$,
#           i_L   I$;            `$½`       `$$,
#                                 `          I$$
#           .cQ$$$$,            ;        _,d$$'
#        ,d$$P"^```?$b,       _,'  ;  ,d$$P"`
#     ,d$P"`        `"?$$Q#QP½`    $d$$P"`
#   ,$$"         ;       ``       ;$?'
#   $$;        ,dI                I$;
#   `$$,    ,d$$$`               j$I
#     ?$S#S$P'j$'                $$;         Copyright (c) Stuffed Guys
#       `"`  j$'  __....,,,.__  j$I              www.stuffedguys.com
#           j$$½"``           ',$$
#           I$;               ,$$'
#           `$$,         _.u$$½`
#             "?$$Q##Q$$SP½"^`
#                `````
#
# ============================================================================
# $Id: index.php,v 1.74 2006/01/16 12:59:14 kuindji Exp $
# ============================================================================

////////////////
///
/////////////////


///////

$TrackerVersion="2.2.1";
$PluginAction=false;

ini_set("error_reporting", E_ERROR);
DEFINE("USER_PAGE_ENCODING", true);

require_once "conf.path.php";
if (@file_exists("conf.vars.php")) include_once "conf.vars.php";
$ProductCall="tracker";
if (!defined("NO_EVENT")) define("NO_EVENT", true);

if (!defined("INSTALLED")) {
	if (!@is_dir("install")) die("Unable to find directory \"install\".");
	Header("Location: http://".$_SERVER['HTTP_HOST']."$ProdPath/install/index.php");
	exit();
}

if(defined("DEBUG")&&DEBUG){
	list($usec, $sec) = explode(" ", microtime()); 
    $StartTime= ((float)$usec + (float)$sec); 
}



///////////////////////////////////////
if ((isset($_REQUEST['sc'])&&$_REQUEST['sc']=="plugin") ||
	(isset($_REQUEST['RequestPath'])&&preg_match("|plugin/[^\.]+\.html$|", $_REQUEST['RequestPath']))) {
	if (!defined("NO_ACTION")) define("NO_ACTION", true);
	$PluginAction=true;
}




require_once SYS."/system/index.php";
if(defined("DEBUG")&&DEBUG) $Db->Debug=true;
$Db->SetMysql40Mode();

if ($nsProduct->Section=="track") {
	define("NO_BUTTONS", true);
	define("NO_CONSULT", true);
	if (!defined("NO_WARNING")) define("NO_WARNING", true);
	define("NO_CLIENT", true);
}


if ($nsProduct->Section!="track") {
	include_once SYS."/system/class/bf/blowfish.class.php";
	include_once SELF."/lib/license.func.php";
	$BF = new Crypt_Blowfish('ns tracker license ');
	if ($Db->ID) GetCurrentLicense();
	$nsProduct->SEND_USAGE=$Db->ReturnValue("SELECT ALLOW_SEND_INFO FROM ".PFX."_tracker_config WHERE COMPANY_ID=0");
}

$_GP=$_REQUEST;
$CompArr=array();

require_once SELF."/lib/misc.func.php";

DEFINE("IMG", $nsProduct->HL."/skins/".$nsProduct->SKIN);

if ($nsProduct->InitExists) include $nsProduct->CurrentInit();

if (isset($nsUser->ENC)) header('Content-Type:text/html; charset='.$nsUser->ENC);
else 	header('Content-Type:text/html; charset='.$LangConfig['charset']);

if (!defined("NO_BUTTONS")||!NO_BUTTONS) {
	require_once SELF."/class/buttons.class.php";
	$nsButtons = new nsButtons();
}


if (!defined("NO_CLIENT")||!NO_CLIENT) {
if ($Db->ID) {
	$CompId=ValidVar($_COOKIE['CompId']);
	if (ValidId($_GP['CpId'])&&$_GP['CpId']>0) $CompId=$_GP['CpId'];
	if (ValidVar($_GP['CpId'])=="nocomp") {
		$CompId=false;
		$nsUser->SetCookie("CompId", 0, time()+60*60*24*10*365, "/");
		$_COOKIE['CompId']=false;
	}
	if (ValidId($SiteId)&&(!ValidId($CompId)||$CompId<1)) {
		$CompId=$Db->ReturnValue("SELECT COMPANY_ID FROM ".PFX."_tracker_site WHERE ID = $SiteId");
	}
	if (!ValidId($CpId)) $CpId=$CompId;

	if (ValidId($nsUser->UserInfo['ID'])&&ValidVar($nsUser->ADMIN)&&$nsProduct->LICENSE==3) {
		$Query = "SELECT * FROM ".PFX."_tracker_client WHERE HIDDEN != '1' ORDER BY NAME ASC";
		$Sql = new Query($Query);
		$CompArr=array();
		while ($Row = $Sql->Row()) {
			$CompArr[]=$Row;
		}
		if (count($CompArr)<1) $CompArr=false;
	}
	else $CompArr=false;
	if (ValidVar($nsUser->MERCHANT)) {
		$_GP['CpId']=$CompId=$nsUser->COMPANY_ID;
		$CurrentCompany=GetCurrentCompany($nsUser->COMPANY_ID);
	}
	if (ValidId($CompId)&&!ValidVar($CurrentCompany)) $CurrentCompany=GetCurrentCompany($CompId);
	if (ValidVar($CurrentCompany)&&ValidId($CurrentCompany->ID)) $CompId=$CurrentCompany->ID;
	if (ValidId($CompId)) {
		$nsUser->SetCookie("CompId", $CompId, time()+60*60*24*10*365, "/");
		$_COOKIE['CompId']=$CompId;
	}
}
}

if (!defined("NO_CONSULT")||!NO_CONSULT) {
	require_once SELF."/class/consult.class.php";
	$Consult=new TrackerConsult();
}

if ((!defined("NO_WARNING")||!NO_WARNING)&&is_dir("install")) {
	$Logs->Err($Lang['InstallNotDeleted']);
}

if (!NO_EVENT) {
	$nsEvent->On("OnBeforeAction");
}

if (!defined("NO_ACTION")||!NO_ACTION) {
	include $nsProduct->CurrentInclude();
}

if ($PluginAction&&ValidVar($_REQUEST['action'])) {
	require SELF."/plugins/".$_REQUEST['action'].".plugin/default.php";
}


if(defined("DEBUG")&&DEBUG){
	echo "<script type=\"text/javascript\"><!--\n";
	echo "defaultStatus='".GetResTime()." sec.';\n";
	echo "//--></script>";
}
?>