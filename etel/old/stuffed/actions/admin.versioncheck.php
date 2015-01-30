<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");

if ($nsProduct->LICENSE==3&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");
if ($nsProduct->LICENSE!=3&&!$nsUser->SUPER_USER) $nsProduct->Redir("default", "", "admin");


/////////////////////////////////////////////
///////// require libraries here


/////////////////////////////////////////////
///////// prepare any variables

$PageTitle=$Lang['Title'];
$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=$Lang['Title'];
$ProgPath[1]['Url']=getURL("versioncheck", "", "admin");
$MenuSection="admin";
$ConnectionFailed=false;

$NewVersion=CheckForNewVersion();


if (!$ConnectionFailed) {
	if (CompareVersions($NewVersion, $nsProduct->VERSION)==1) {
		$Logs->Msg("<span style=\"font-size:14px;color:#000000;font-weight:bold;font-family:Arial;\">".$Lang['LatestVersion'].$NewVersion."</span><br>".$Lang['NewVersionAvail']);
	}
	if (CompareVersions($NewVersion, $nsProduct->VERSION)==-1) {
		$Logs->Err($Lang['Fun']);
	}
	if (CompareVersions($NewVersion, $nsProduct->VERSION)==0) {
		$Logs->Msg($Lang['NoNew']);
	}
}


/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here
include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


/////////////////////////////////////////////
///////// library section


function CheckForNewVersion()
{
	global $nsProduct, $Logs, $Lang, $_SERVER, $ConnectionFailed;
	$f=fopen("http://my.stuffedguys.com/sales/pub/versioncheck.html?k=tracker&v=".$nsProduct->VERSION."&UIP=".$_SERVER['REMOTE_ADDR']."&H=".$nsProduct->SelfUrl(), "r");
	if (!$f) {
		$Logs->Err($Lang['ConnectFailed']);
		$ConnectionFailed=true;
		return false;
	}
	$Data=@fread($f, 100);
	if (!$Data) {
		$Logs->Err($Lang['ConnectFailed']);
		$ConnectionFailed=true;
		return false;
	}
	@fclose($f);
	return $Data;
}


?>