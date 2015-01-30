<?

$UseModR=(ValidVar($_REQUEST['UseModR']))?1:0;
$SiteDomain=$_REQUEST['SiteDomain'];
$NoPrevStep=true;
$Finished=true;
$NoForm=true;
$Messages=array();


$CallName=$ProdPath;
$CallName=str_replace("/install", "", $CallName);
if (substr($CallName, 0, 1)=="/") $CallName=substr($CallName, 1);

function GetUrl($Action=false, $Section=false)
{
	global $UseModR;
	if (!$Action) $Action="default";
	if (!$Section) $Section="track";
	if ($UseModR) if (strlen($Section)) $Section.="/";
	if (!$UseModR) return SelfUrl()."sc=$Section&action=$Action&";
	return SelfUrl()."/$Section$Action.html";
}

function SelfUrl()
{
	global $UseModR, $_SERVER, $CallName;
	if (!$UseModR) return "http://".$_SERVER['HTTP_HOST']."/$CallName/index.php?";
	return "http://".$_SERVER['HTTP_HOST']."/$CallName";
}

$PAmp=($UseModR)?"?":"";
$TrackPath=GetUrl();
$JsPath="http://".$_SERVER['HTTP_HOST']."/$CallName/track.js";


$ResultCode="<!-- Start of Stuffed Tracker $ProductVersion code for http://$SiteDomain -->\n";
$ResultCode.="<script type=\"text/javascript\">\n";
if (!$UseModR) $ResultCode.="var nsAmp=unescape('%26');\n";
if (!$UseModR) $TrackPath=str_replace("&", "'+nsAmp+'", $TrackPath);
$ResultCode.="var nsSiteId=1;\nvar nsTrackPath='$TrackPath$PAmp';\nvar nsTrackMode='default';\nvar nsCode=1;";
$ResultCode.="\n</script>\n<script type=\"text/javascript\" src=\"$JsPath\"></script>";
$ResultCode.="\n<!-- End of Stuffed Tracker code -->";

?>