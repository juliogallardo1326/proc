<?

$SaveVars=array("Trial", "LKey");
$LArray=false;

DEFINE("SYS", substr($SysPath, 0, -1));
require $SysPath."system/class/bf/blowfish.class.php";
require "../lib/license.func.php";

$AdditionalOnload="OnChangeKey();SetKeyField(GetObj('TrialCheck'));";

$Trial=(ValidVar($_REQUEST['Trial']))?$_REQUEST['Trial']:false;
$LKey=(ValidVar($_REQUEST['LKey']))?trim($_REQUEST['LKey']):false;

if (!$Trial&&!$LKey) $DisableNext=true;

function CheckLicense()
{
	global $LKey, $Trial, $SaveVars, $Errors, $Lang, $CLang;
	if (!$LKey&&!$Trial) return false;
	if ($LKey) {
		$BF = new Crypt_Blowfish('ns tracker license ');
		$Decoded=$BF->decrypt($LKey);
		$LArray=GetLicenseText($Decoded);
		if ($LArray) {
			if (!isset($LArray['P'])) {
				$Errors[]=$Lang['SecondaryKey'];
				return;
			}
			$GLOBALS['LArray']=$LArray;
			foreach($LArray as $Key=>$Val) {
				$GLOBALS['Lc'.$Key]=$Val;
				$SaveVars[]="Lc".$Key;
			}
		}
		else $Errors[]=$Lang['BadLicense'];
	}
	if (count($Errors)) return;
	NextStep();
}


?>