<?
require_once SYS."/system/lib/validate.func.php";

$xref=(ValidVar($_GP['xref']))?$_GP['xref']:false;
$redirect=(ValidVar($_GP['redirect']))?$_GP['redirect']:false;
if (!$xref) {
	if (ValidVar($_SERVER['HTTP_REFERER']) and !preg_match("/login/i", $_SERVER['HTTP_REFERER']))  $xref=$_SERVER['HTTP_REFERER'];
	$xref=$redirect;
}

if(isset($_REQUEST['reauth'])) {
   $nsUser->Reauthorize();	
}
if(isset($_REQUEST['logout'])) {
   $nsUser->Logout();	
   Redir($nsProduct->SelfAction());
}
if(isset($_REQUEST['go_auth'])) {
	if (!ValidVar($_REQUEST['recall'])) $_REQUEST['recall']=false;
	$nsUser->Login($_REQUEST['xlogin'], $_REQUEST['xpwd'], $_REQUEST['recall']);
	if (!$nsUser->Logged()) $Logs->Err($Lang['LoginErr1']);
}

if ($nsUser->Logged()) {
	if (ValidVar($xref)) Redir($xref);
	else $nsProduct->Redir("default");
}

$xlogin=ValidVar($_GP['xlogin']);

$PageTitle=$Lang['Title'];
$TableCaption=$Lang['LoginPass'];

include $nsTemplate->Inc();
?>