<?

$SitesCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site");
$ClientCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_client");

if ($nsProduct->LICENSE==1) {
	echo date("Y-m-d", $nsProduct->STRT_INT);
}
else {
	for($i=0;$i<count($nsProduct->LC);$i++) {
		if ($i>0) echo ", ";
		echo $nsProduct->LC[$i]->License['ID'];
	}
}



echo "<br>";
echo $ClientCnt.", ".$SitesCnt;


?>