<?
$PageTitle=$Lang['ConfirmPage'];
$NoForm=true;

/*
$Filename="test.txt";
$f=@fopen("../store/$Filename", "a+");
if (!$f) {
	$Messages[]=$Lang['StoreFolderPerms'];
}
//if (!@is_writable("../store/$Filename")) {
//	$Messages[]=$Lang['StoreFolderPerms2'];
//}
@unlink("../store/$Filename");
*/

function CheckPermission()
{
	global $Lang, $Errors, $Messages;
	$Errors=array();
	$Messages=array();
	//if (!is_dir("../store")) {
	//	$Errors[]=$Lang['NoStoreFolder'];
	//	return;
	//}
	$filename="../conf.vars.php";
	$somecontent="test";

	if (@is_writable($filename)) {
		if (!$handle = @fopen($filename, 'a')) {
			$Errors[]=$Lang['ConfPerms'];
			return;
		}
		if (@fwrite($handle, $somecontent) === FALSE) {
			$Errors[]=$Lang['ConfPerms'];
			return;
		}
		@fclose($handle);
	} else {
		$Errors[]=$Lang['ConfPerms'];
		return;
	}

	global $_REQUEST;
	$DbName=ValidVar($_REQUEST['DbName']);
	$DbHost=ValidVar($_REQUEST['DbHost']);
	$DbPort=ValidVar($_REQUEST['DbPort']);
	$DbUser=ValidVar($_REQUEST['DbUser']);
	$DbPass=ValidVar($_REQUEST['DbPass']);
	$DbPref=ValidVar($_REQUEST['DbPref']);
	$DbHost1 = ($DbPort) ? $DbHost.":".$DbPort : $DbHost;
	$ID = @mysql_connect($DbHost1, $DbUser, $DbPass);
	$SelectRes=@mysql_select_db($DbName, $ID);

	$Query = "
		CREATE  TABLE `".$DbPref."_tracker_temp` (
			`ID` int(11) NOT NULL default '0',
			PRIMARY KEY  (`ID`)
		)
	";
	$res=mysql_query($Query);
	if (!$res) {
		$Errors[]=$Lang['DbNoPerms'];
		return;
	}
	$Query = "INSERT INTO ".$DbPref."_tracker_temp (ID) VALUES (1)";
	$res=mysql_query($Query);
	if (!$res) {
		$Errors[]=$Lang['InsertNoPerms'];
		return;
	}
	$Query = "UPDATE ".$DbPref."_tracker_temp  SET ID=2 WHERE ID=1";
	$res=mysql_query($Query);
	if (!$res) {
		$Errors[]=$Lang['UpdateNoPerms'];
		return;
	}
	$Query = "DELETE FROM ".$DbPref."_tracker_temp  WHERE ID=2";
	$res=mysql_query($Query);
	if (!$res) {
		$Errors[]=$Lang['DeleteNoPerms'];
		return;
	}
	$Query = "DROP TABLE ".$DbPref."_tracker_temp";
	mysql_query($Query);


	NextStep();
}



?>