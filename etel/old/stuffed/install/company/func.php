<?

$CompName=(ValidVar($_REQUEST['CompName']))?$_REQUEST['CompName']:false;
$CompDescr=(ValidVar($_REQUEST['CompDescr']))?$_REQUEST['CompDescr']:false;
$SiteDomain=(ValidVar($_REQUEST['SiteDomain']))?trim($_REQUEST['SiteDomain']):false;

$SaveArr=array("CompName", "CompDescr", "SiteDomain");

function ValidHost($Host)
{
	$Arr1=array();
	$Arr2=array();
	$From="~!@#$%^&*()_+|`=\\{}[]:\";',/<>?¹";
	$To="                                 ";
	$Arr1=explode(".", $Host);
	for($i=0;$i<count($Arr1);$i++) {
		$Arr1[$i]=strtr($Arr1[$i], $From, $To);
		$Arr1[$i]=preg_replace("/\s+/", " ", $Arr1[$i]);
		if ($Arr1[$i]=="") continue;
		$Arr2[]=$Arr1[$i];
	}
	if (count($Arr2)<2) return false;
	$Host=implode(".",$Arr2);
	return $Host;
}


function CheckCompany()
{	
	global $CompName, $CompDescr, $SiteDomain;
	global $Errors, $Lang;
	if (!$CompName) {$Errors[]=$Lang['MustFillCompanyName']; return ;}
	if (!$SiteDomain) {$Errors[]=$Lang['MustFillDomain']; return ;}
	if (!ValidHost($SiteDomain)) {$Errors[]=$Lang['DomainIncorrect']; return ;}
	$Check=@parse_url($SiteDomain);
	if (is_array($Check)&&ValidVar($Check['scheme'])) {
		$Host=str_replace($Check['scheme']."://", "", $SiteDomain);
	}

	NextStep();
}

?>