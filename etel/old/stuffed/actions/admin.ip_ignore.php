<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");
if ($nsProduct->LICENSE==3&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");
if ($nsProduct->LICENSE!=3&&!$nsUser->SUPER_USER) $nsProduct->Redir("default", "", "admin");

/////////////////////////////////////////////
///////// prepare any variables

$PageTitle=$Lang['Title'];

$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=$PageTitle;
$ProgPath[1]['Url']=getURL("ip_ignore", "", "admin");
$MenuSection="admin";


$NewIp=ValidVar($_GP['NewIp']);
$NewIpDescr=ValidVar($_GP['NewIpDescr']);
if ($NewIp&&!ValidIP($NewIp) && !ValidIpTempl($NewIp)) {
	$Logs->Err($Lang['WrongIp']);
	$NewIp=false;
}

$RemoveIP=ValidVar($_GP['RemoveIP']);
if (!ValidArr($RemoveIp)) $RemoveIp=false;

$MyIP=$_SERVER['REMOTE_ADDR'];

/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if ($NewIp && ValidIp($NewIp)) AddNewIp($NewIp, $NewIpDescr);
	if ($NewIp && ValidIpTempl($NewIp)) AddNewIpTempl($NewIp, $NewIpDescr);
	if ($RemoveIP) RemoveIp($RemoveIP);
}

/////////////////////////////////////////////
///////// display section here

$IpList=array();
$Query = "SELECT * FROM ".PFX."_tracker_ip WHERE IGNORED = '1' ORDER BY IP ASC";
$Sql = new Query($Query);
$Sql->ReadSkinConfig();
while ($Row=$Sql->Row()) {
	$Row->_STYLE=$Sql->_STYLE;
	$Row->TEMPLATE=false;
	$IpList[]=$Row;
}
$Query = "SELECT * FROM ".PFX."_tracker_ip_ignore ORDER BY TEMPLATE ASC";
$Sql = new Query($Query);
$Sql->ReadSkinConfig();
while ($Row=$Sql->Row()) {
	$Row->IP=$Row->TEMPLATE;
	$Row->_STYLE=$Sql->_STYLE;
	$IpList[]=$Row;
}


include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here

function AddNewIp($IP, $Descr=false) {
	global $Db, $nsProduct;
	if (!$Descr) $Descr="";
	$Query = "SELECT ID FROM ".PFX."_tracker_ip WHERE IP='$IP'";
	$CheckId=$Db->ReturnValue($Query);
	if ($CheckId) $Query = "UPDATE ".PFX."_tracker_ip SET IGNORED='1', DESCRIPTION=? WHERE ID = $CheckId";
	else $Query = "INSERT INTO ".PFX."_tracker_ip (IP, IGNORED, DESCRIPTION) VALUES ('$IP', '1', ?)";
	$Db->Query($Query, $Descr);
	$nsProduct->Redir("ip_ignore", "RCrt=1", "admin");
}

function AddNewIpTempl($IP, $Descr=false) {
	global $Db, $nsProduct;
	global $Lang, $Logs;
	if (!$Descr) $Descr="";

	$Query = "SELECT ID FROM ".PFX."_tracker_ip_ignore WHERE TEMPLATE = '$IP' ";
	$CheckId=$Db->ReturnValue($Query);
	if ($CheckId) return false;

	$Arr=explode(".", $IP);
	$t=0;
	foreach ($Arr as $i=>$s) {
		if ($t!=2 && $s=="*") $t=1;
		if ($t==1 && $s!="*") $t=2;
		if ($t==2 && $s=="*") {
			$Logs->Err($Lang['WrongIp']);
			return;
		}
	}
	$StartIp=trim(sprintf("%u\n", ip2long(str_replace("*", "0", implode(".", $Arr)))));
	$EndIp=trim(sprintf("%u\n", ip2long(str_replace("*", "255", implode(".", $Arr)))));

	$Query = "INSERT INTO ".PFX."_tracker_ip_ignore (TEMPLATE, START_INT, END_INT, DESCRIPTION) VALUES ('$IP', '$StartIp', '$EndIp', ?)";
	$Db->Query($Query, $Descr);

	$nsProduct->Redir("ip_ignore", "RCrt=1", "admin");
}

function RemoveIp($Arr) {
	global $Db, $nsProduct;
	foreach ($Arr as $IP => $Value) {
		if ($Value!=1) continue;
		$Query = "UPDATE ".PFX."_tracker_ip SET IGNORED='0' WHERE IP = '$IP'";
		$Db->Query($Query);
		$Query = "DELETE FROM  ".PFX."_tracker_ip_ignore WHERE TEMPLATE = '$IP'";
		$Db->Query($Query);
	}
	$nsProduct->Redir("ip_ignore", "RUpd=1", "admin");
}

/////////////////////////////////////////////
///////// library section
?>