<?
/////////////////////////////////////////////
///////// permission check here


/////////////////////////////////////////////
///////// require libraries here


/////////////////////////////////////////////
///////// prepare any variables

$PageTitle=$Lang['NewPass'];
$TableCaption=$Lang['EnterEmail'];
$Email=(ValidVar($_REQUEST['Email']))?$_REQUEST['Email']:false;

/////////////////////////////////////////////
///////// call any process functions

if ($Email) RemindPassword($Email);


/////////////////////////////////////////////
///////// display section here
include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here

function RemindPassword($Email)
{
	global $Db, $Logs, $nsProduct, $nsLang, $LangConfig, $Lang;
	$Query = "SELECT * FROM ".PFX."_system_user WHERE EMAIL = ?";
	$User=$Db->Select($Query, false, $Email);
	if (!ValidId($User->ID)) {
		$Logs->Err($Lang['NoEmail']);
		return false;
	}
	$NewPass=substr(md5(uniqid(rand())), 0, 10);
	$Query = "UPDATE ".PFX."_system_user SET PWD= '".md5($NewPass)."' WHERE ID = ".$User->ID;
	$Db->Query($Query);

	$Query = "SELECT LANG FROM ".PFX."_system_user2lang WHERE PROD_ID=".$nsProduct->ID." AND UID = ".$User->ID;
	$ULang=$Db->ReturnValue($Query);
	if ($ULang&&$ULang!=$nsLang->CurrentLang) {
		$LConfig=$nsLang->ReturnConfig($ULang);
		$nsLang->TplInc("admin.remind", $ULang);
	}
	else $LConfig=$LangConfig;

	$Query = "SELECT FROM_EMAIL FROM ".PFX."_tracker_config WHERE COMPANY_ID=0";
	$FromEmail=$Db->ReturnValue($Query);
	$FromEmail=($FromEmail)?$FromEmail:$Email;

	$Message=$Lang['MsgBody'];
	$Message=str_replace("{LOGIN}", $User->LOGIN,$Message);
	$Message=str_replace("{PASS}", $NewPass,$Message);
	$Message=str_replace("{LINK}", getURL("login", "", "admin"),$Message);

	$Subject =$Lang['MsgSubject'];
	$Headers="From: $FromEmail\n";
	$Headers.="Content-Type: text/plain; charset=".$LConfig['charset']."\n";
	mail($Email, $Subject, $Message, $Headers);
	$Logs->Msg(str_replace("{EMAIL}", $Email, $Lang['PasswordSent']));
}


/////////////////////////////////////////////
///////// library section


?>