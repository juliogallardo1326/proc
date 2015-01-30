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
# $Id: index.php,v 1.10 2005/12/26 12:28:07 kuindji Exp $
# ============================================================================

if(get_magic_quotes_runtime()==1){
	set_magic_quotes_runtime(0);
}	
function strip_request_slashes(&$request){
	foreach($request as $key => $val){
		if(!is_array($request[$key]))$request[$key] = stripslashes($val);
		else 
			strip_request_slashes($request[$key]);
	}

}
if(get_magic_quotes_gpc()){
			strip_request_slashes($_REQUEST);
			strip_request_slashes($_COOKIE);
			strip_request_slashes($_GET);
			strip_request_slashes($_POST);
}			



require_once SYS."/system/lib/server.conf.php";
require_once SYS."/system/lib/misc.func.php";

require_once SYS."/system/class/lang.class.php";
$nsLang = new nsLang();
$nsLang->Inc();

if (!defined("NO_DB")||!NO_DB) {
	require_once SYS."/system/class/db.class.php";
}
require_once SYS."/system/class/logs.class.php";
$Logs= new nsLogs(false, "ARR");
if (!defined("NO_DB")||!NO_DB) {
	$Db=new nsDatabase();
	$Db->Debug=false;
}

require_once SYS."/system/class/nsbase.class.php";

if (!defined("NO_SESSION")||!NO_SESSION) {
	require_once SYS."/system/class/session.class.php";
	$nsSession = new nsSession();
}

//---------------------------------------------------------------------		
// gotta throw the session for language(here - because of the 
// order of declaration objects - it must happen AFTER nsLang and nsSession,
// but BEFORE nsUser):
if(isset($_GET['lang'])){
		$nsLang->CurrentLang($_GET['lang']);
		$nsSession->set('ns_lang_current', $_GET['lang']);
}else if(($cl = $nsSession->get('ns_lang_current'))!==false){
		$nsLang->CurrentLang($cl);
}else{
		$nsLang->CurrentLang($DefLangFile);
		$nsSession->set('ns_lang_current', $DefLangFile);
}		
//---------------------------------------------------------------------		


require_once SYS."/system/class/product.class.php";
$nsProduct = new nsProduct();

if (!defined("NO_EVENT")||!NO_EVENT) {
	require_once SYS."/system/class/event.class.php";
	$nsEvent = new nsEvent();
}

require_once SYS."/system/class/user.class.php";
$nsUser = new nsUser();

if (!defined("NO_TEMPLATE")||!NO_TEMPLATE) {
	require_once SYS."/system/class/template.class.php";
	$nsTemplate = new nsTemplate();
}

$nsLang->Inc();
$LangConfig = array();
$nsLang->IncConfig();

if ( (!defined("NO_LANG")||!NO_LANG) && !defined("USER_PAGE_ENCODING")) {
	header('Content-Type:text/html; charset='.$LangConfig['charset']);
}

if (!defined("NO_ACTION")||!NO_ACTION) {
	$nsProduct->PrepareAction();
}

if (!defined("NO_LANG")||!NO_LANG) $nsLang->Inc();
?>