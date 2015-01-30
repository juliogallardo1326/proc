<?

if (!defined("NS_PHP_TRACKING")) {
	DEFINE("HOME", $CurPath);
	DEFINE("HOST", $_SERVER['HTTP_HOST']);
	DEFINE("SELF", $CurPath);
	DEFINE("PATH", $ProdPath);
	$DefLangFile = "en";

	///////////////////////////
	DEFINE("SYS", HOME.$SPath);
	DEFINE("COOKIE_PFX", "ns_");
	// cookie must expire after (12 hours)
	DEFINE("COOKIE_EXP", "43200");

	DEFINE("PFX", "ns");
	DEFINE("MOD_R", false);

	DEFINE("INSTALLED", true);
}
else {
	$CookiePfx="ns_";
	$CookieExp="43200";
	$DbPfx="ns";
}

$DbName="etel_dbscompanysetup";
$DbHost="localhost";
$DbPass="WSD%780=";
$DbUser="etel_root";
$DbPort="3306";



?>