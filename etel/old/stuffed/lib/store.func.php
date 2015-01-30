<?

function SaveActionToFile($ActionArr=false, $Filename=false)
{
	if (!$ActionArr||!$Filename) return false;
	@clearstatcache();
	$f=@fopen("store/$Filename", "a+");
	if (!@is_writable("store/$Filename")) return false;
	if (!$f) return false;
	$Data=@fread($f, @filesize("store/$Filename"));
	if (!$Data) $DataArr[0]=$ActionArr;
	else {
		$DataArr=@unserialize($Data);
		if (!ValidArr($DataArr)) $DataArr=array();
		$Position=-1;
		for($i=0;$i<count($DataArr);$i++) {
			if ($DataArr[$i]['ID']==$ActionArr['ID']) {$Position=$i; break;}
		}
		if ($Position<0&&count($DataArr)>0) $Position=count($DataArr);
		$DataArr[$Position]=$ActionArr;
	}

	$Data=@serialize($DataArr);
	@flock($f, LOCK_EX); 
	@ftruncate($f, 0);
	if (!@fwrite($f, $Data)) return false;
	@flock($f, LOCK_UN);
	@fclose($f);
	return true;
}



function SaveSplitToFile($Id=false, $Filename, $SplitId=false)
{
	global $Db;
	if (!$Id&&!$SplitId) return false;
	$UseStore=$Db->ReturnValue("SELECT USE_STORE FROM ".PFX."_tracker_config WHERE COMPANY_ID=0");
	if (!$UseStore) return true;
	if (!$SplitId)$SplitId=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_split_test WHERE SUB_ID=$Id");
	$Query = "
		SELECT SP.FULL_PATH AS PAGE_PATH
			FROM ".PFX."_tracker_split_page SP
			INNER JOIN ".PFX."_tracker_site_page SPA
				ON SPA.ID=SP.PAGE_ID
			INNER JOIN ".PFX."_tracker_site S
				ON S.ID=SPA.SITE_ID
			LEFT JOIN ".PFX."_tracker_query Q
				ON Q.ID=SP.QUERY_ID
			WHERE SP.SPLIT_ID=$SplitId
		";
	$Sql=new Query($Query, "ARR");
	$PageArr=array();
	while ($Row=$Sql->Row()) {
		if (!ValidVar($Row['HOST'])) continue;
		if (!$Row['PAGE_PATH']) {
			$Row['PAGE_PATH']="http://".$Row['HOST'].$Row['PATH'];
			if ($Row['QUERY_STRING']) $Row['PAGE_PATH'].="?".$Row['QUERY_STRING'];
		}
		$PageArr[]=$Row['PAGE_PATH'];
	}
	if (!ValidArr($PageArr)||count($PageArr)<1) return false;

	@clearstatcache();
	$f=@fopen("store/$Filename", "a+");
	if (!@is_writable("store/$Filename")) return false;
	if (!$f) return false;
	$Data=@fread($f, @filesize("store/$Filename"));
	if (!$Data) $DataArr[$SplitId]=$PageArr;
	else {
		$DataArr=@unserialize($Data);
		if (!ValidArr($DataArr)) $DataArr=array();
		$DataArr[$SplitId]=$PageArr;
	}

	$Data=@serialize($DataArr);
	@flock($f, LOCK_EX); 
	@ftruncate($f, 0);
	if (!@fwrite($f, $Data)) return false;
	@flock($f, LOCK_UN);
	@fclose($f);
	return true;
}


?>