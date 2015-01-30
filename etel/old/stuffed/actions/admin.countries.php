<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");
if ($nsProduct->LICENSE==3&&!$nsUser->ADMIN) $nsProduct->Redir("default", "", "admin");
if ($nsProduct->LICENSE!=3&&!$nsUser->SUPER_USER) $nsProduct->Redir("default", "", "admin");


require SELF."/class/pagenums2.class.php";

/////////////////////////////////////////////
///////// prepare any variables

$PageTitle=$Lang['Title'];

$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=$PageTitle;
$ProgPath[1]['Url']=getURL("countries", "", "admin");
$MenuSection="admin";

$Pages=false;
$CurrentProgress=0;
$AdditionalHead=false;
$FileExists=false;
$FileSize=0;
$FileModified=false;
$FileName="country.csv";
$ImportFile=SELF."/store/$FileName";
$UnCountried=0;
//$UnCountried=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_visitor WHERE FIRST_COUNTRY_ID = 0 AND LAST_IP_ID>0");
$CountryCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_country");

$DoImport=ValidVar($_GP['DoImport']);
$DoConvert=ValidVar($_GP['DoConvert']);
$UnCCnt=ValidVar($_GP['UnCCnt']);
$PerPage=ValidVar($_GP['pp']);
if (!$PerPage) $PerPage=5000;

/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if ($DoImport) ImportDatabase();
	if ($DoConvert) UpdateStats();
}

/////////////////////////////////////////////
///////// display section here

if (!$DoImport) {
	$FileExists=@file_exists($ImportFile);
	if ($FileExists) {
		$FileSize=@filesize($ImportFile);
		$FileModified=@filemtime($ImportFile);
	}
}

include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


function ImportDatabase()
{
	global $Lang, $Logs, $Db, $Pages, $CurrentProgress, $AdditionalHead, $ImportFile, $PerPage;
	//$PerPage=500;
	ini_set("memory_limit","40M");
	
	$DataArr=array();
	
	if (!file_exists($ImportFile)) {$Logs->Err($Lang['FindFail']);return;}
	$f=fopen($ImportFile, "r");
	
	if (!$f) {$Logs->Err($Lang['OpenFail']);return;}
	while ($Row=fgets($f)) $DataArr[]=$Row;
	fclose($f);

	if (!$DataArr) {$Logs->Err($Lang['NoData']); return;}

	$Cnt=count($DataArr);
	$Pages = new PageNums($Cnt, $PerPage);
	$Pages->NoPrev=true;
	$Pages->NoPrevPrev=true;
	$Pages->NoNextNext=true;
	$Pages->NoPageLink=true;
	$Pages->Calculate();
	$Pages->Args="&DoImport=1&pp=$PerPage";
	$CurrentProgress=round(($Pages->PageCurrent+1)*(100/$Pages->Pages));
	if ($CurrentProgress>100) $CurrentProgress=100;
	
	$DataArr=array_chunk($DataArr, $PerPage, true);
	
	$DataArr=$DataArr[$Pages->PageCurrent];
	$AdditionalHead="<meta http-equiv=\"refresh\" content=\"1;url=".getURL("countries", "DoImport=1&pp=$PerPage&PS=".($Pages->PageStart+$Pages->Limit)."&PC=".($Pages->PageCurrent+1), "admin")."\">";
	
	if ($Pages->PageCurrent==0) {
		$Query = "DELETE FROM ".PFX."_tracker_country_ip";
		$Db->Query($Query);
	}
	
	$Countries=array();
	$Query = "SELECT * FROM ".PFX."_tracker_country";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $Countries[$Row->CODE]=1; 
	
	foreach ($DataArr as $Key=>$Str) {
		if (!$Str) continue;
		$StrArr=explode('","', $Str);
		for($j=0;$j<count($StrArr);$j++) $StrArr[$j]=str_replace('"', "", $StrArr[$j]); 
		$Query = "INSERT INTO ".PFX."_tracker_country_ip (COUNTRY_CODE, IP_START, IP_END) VALUES (?, ?, ?)";
		$Db->Query($Query, $StrArr[4], $StrArr[2], $StrArr[3]);
		if (!isset($Countries[$StrArr[4]])) {
			$Query = "INSERT INTO ".PFX."_tracker_country (CODE, NAME) VALUES (?, ?)";
			$Db->Query($Query, $StrArr[4], $StrArr[5]);
			$Countries[$StrArr[4]]=1;
		}
	}
	
	if ($CurrentProgress==100) { 
		$Logs->Msg($Lang['ImportDone']);
		$Pages = new PageNums(0, 0);
		$AdditionalHead=false;
	}

	
}

function UpdateStats()
{
	global $Lang, $Logs, $Db, $Pages, $CurrentProgress, $AdditionalHead, $UnCountried, $UnCCnt;
	$PerPage=100;
	$Pages = new PageNums($UnCCnt, $PerPage);
	$Pages->NoPrev=true;
	$Pages->NoPrevPrev=true;
	$Pages->NoNextNext=true;
	$Pages->NoPageLink=true;
	$Pages->Calculate();
	$Pages->Args="&DoConvert=1&UnCCnt=$UnCCnt";
	$CurrentProgress=($Pages->PageCurrent+1)*ceil(100/$Pages->Pages);
	if ($CurrentProgress>100) $CurrentProgress=100;
	$AdditionalHead="<meta http-equiv=\"refresh\" content=\"1;url=".getURL("countries", "DoConvert=1&UnCCnt=$UnCCnt&PS=".($Pages->PageStart+$Pages->Limit)."&PC=".($Pages->PageCurrent+1), "admin")."\">";
	
	$Query = "
			SELECT V.ID,  I.IP
				FROM ".PFX."_tracker_visitor V 
				INNER JOIN ".PFX."_tracker_ip I
					ON I.ID=V.LAST_IP_ID 
				WHERE V.FIRST_COUNTRY_ID = 0  AND V.LAST_IP_ID>0
				LIMIT 0, ".$Pages->Limit;
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$IpLong=sprintf("%u\n", ip2long($Row->IP));
		if (!$IpLong) return 0;
		global $Db;
		$Query = "
			SELECT C.ID
				FROM ".PFX."_tracker_country_ip CI
				INNER JOIN ".PFX."_tracker_country C
					ON C.CODE=CI.COUNTRY_CODE
				WHERE $IpLong BETWEEN CI.IP_START AND CI.IP_END
		";
		$CountryId=$Db->ReturnValue($Query);
		if (!$CountryId) continue;
		$Query = "UPDATE ".PFX."_tracker_visitor SET FIRST_COUNTRY_ID = '$CountryId' WHERE ID = ".$Row->ID;
		$Db->Query($Query);
	}
	

	if ($CurrentProgress==100) { 
		$Logs->Msg($Lang['ConvertDone']);
		$Pages = new PageNums(0, 0);
		$AdditionalHead=false;
	}

}


/////////////////////////////////////////////
///////// library section
?>