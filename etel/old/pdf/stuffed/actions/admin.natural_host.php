<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");
if (($nsProduct->LICENSE==3&&$nsUser->ADMIN)
	|| ($nsProduct->LICENSE!=3&&$nsUser->SUPER_USER)) {

	}
	else $nsProduct->Redir("default", "", "admin");

/////////////////////////////////////////////
///////// require libraries here
require_once SYS."/system/lib/validate.func.php";
require_once SELF."/lib/form.func.php";
require_once SELF."/class/pagenums3.class.php";
require_once SELF."/lib/track/general.func.php";
require_once SELF."/lib/track/referer.func.php";
require_once SELF."/lib/track/query.func.php";

/////////////////////////////////////////////
///////// prepare any variables
if (isset($_GP['EditId'])) $EditId=$_GP['EditId'];
if (isset($_GP['DeleteId'])) $DeleteId=$_GP['DeleteId'];
if (isset($_GP['EditArr'])) $EditArr=$_GP['EditArr'];
if (isset($_GP['Srch'])) $Srch=$_GP['Srch'];
if (isset($_GP['SelHost'])) $SelHost=$_GP['SelHost'];
if (isset($_GP['Mode'])) $Mode=$_GP['Mode'];
if (isset($_GP['GrpId'])) $GrpId=$_GP['GrpId'];
if (isset($_GP['GrpMove'])) $GrpMove=$_GP['GrpMove'];
if (isset($_GP['Update'])) $Update=$_GP['Update'];
if (isset($_GP['UpdateGrp'])) $UpdateGrp=$_GP['UpdateGrp'];
$RegSearchGrp=(ValidVar($_GP['RegSearchGrp']))?$_GP['RegSearchGrp']:false;
$R1=(ValidVar($_GP['R1']))?trim($_GP['R1']):false;
$R2=(ValidVar($_GP['R2']))?trim($_GP['R2']):false;
$RegCheck=(ValidVar($_GP['RegCheck']))?true:false;


if (!isset($Srch)) $Srch="";
else $Srch=str_replace(" ", "%", $Srch);

$PageTitle=$Lang['Title'];

$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=$Lang['NaturalHosts'];
$ProgPath[1]['Url']=getURL("natural_host", "", "admin");
$MenuSection="admin";


if (!ValidVar($Mode)) $Mode="List";
$Grps=GetGrps();
if (!ValidId($GrpId)) $GrpId=0;

/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {

	if (ValidVar($Update)==1) UpdateStats();
	if (ValidVar($UpdateGrp)==1) UpdateByRegs();
	if ($Mode=="Delete"&&ValidArr($SelHost)) DeleteHosts($SelHost);
	if ($Mode=="Ignore"&&ValidArr($SelHost)) IgnoreHosts($SelHost);

	if ($Mode=="GrpMove"&&ValidId($GrpMove)&&ValidArr($SelHost)) MoveHostsToGrp($GrpMove, $SelHost);
	if ($Mode=="GrpFree"&&ValidArr($SelHost)) MoveHostsFromGrp($SelHost);

	if ($Mode=="Host"&&ValidId($EditId)&&ValidArr($EditArr)) UpdateHost($EditId, $EditArr);
	if ($Mode=="Host"&&ValidVar($EditId)=="new"&&ValidArr($EditArr)) CreateHost($EditArr);
	if ($Mode=="Host"&&ValidId($DeleteId)) DeleteHost($DeleteId);

	if ($Mode=="Grp"&&ValidVar($EditId)=="new"&&ValidArr($EditArr)) CreateGrp($EditArr);
	if ($Mode=="Grp"&&ValidId($EditId)&&ValidArr($EditArr)) UpdateGrp($EditId, $EditArr);
	if ($Mode=="Grp"&&ValidId($DeleteId)) DeleteGrp($DeleteId);
}

if ($Mode!="Host"&&$Mode!="Grp") $Mode="List";

/////////////////////////////////////////////
///////// display section here


// list
if ($Mode=="List"&&!ValidId($EditId)&&$EditId!="new") {

	if ($GrpId>0) {
		$GrpName=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_host_grp WHERE ID=$GrpId");
		$PageTitle=str_replace("{GRP}", "<B>$GrpName</B>", $Lang['InGrpList']);
	}
	$ProgPath[2]['Name']=$PageTitle;
	$ProgPath[2]['Url']=getURL("natural_host", "GrpId=$GrpId", "admin");

	if ($RegSearchGrp) {
		$Query = "SELECT * FROM ".PFX."_tracker_host_grp WHERE ID = '$RegSearchGrp'";
		$RegGrp=$Db->Select($Query);
		if (!$RegGrp->REGULAR_EXPRESSION&&!$RegGrp->REGULAR_EXPRESSION2) $RegSearchGrp=false;
		$R1=$RegGrp->REGULAR_EXPRESSION;
		$R2=$RegGrp->REGULAR_EXPRESSION2;
	}

	$Where="WHERE GRP_ID=$GrpId ";
	$CntWhere="GRP_ID=$GrpId ";
	if (ValidVar($Srch)) {
		$Where.=" AND HOST LIKE '%$Srch%'";
		$CntWhere.=" AND HOST LIKE '%$Srch%'";
	}
	$Cnt=$Db->CNT(PFX."_tracker_host", $CntWhere);
	$Pages=new PageNums($Cnt, 50);
	$Pages->Args="&GrpId=$GrpId";
	if ($RegSearchGrp) $Pages->Args.="&RegSearchGrp=$RegSearchGrp";
	if ($RegCheck) {
		$Pages->Args.="&RegCheck=1";
		if ($R1) $Pages->Args.="&R1=$R1";
		if ($R2) $Pages->Args.="&R2=$R2";
	}
	if ($RegSearchGrp||$RegCheck) $Pages->Limit=($Cnt>0)?$Cnt:1;
	$Pages->Calculate();

	$Query = "
		SELECT TH.*, THG.KEY_VAR AS GKEY_VAR, THG.BAN AS GBAN
			FROM ".PFX."_tracker_host TH
				LEFT JOIN ".PFX."_tracker_host_grp THG
					ON THG.ID=TH.GRP_ID
			$Where 
			ORDER BY HOST ASC  
			LIMIT ".$Pages->PageStart.", ".$Pages->Limit." 
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$HostArr=array();
	$IdsArr=array();
	while ($Row=$Sql->Row()) {
		if ($RegSearchGrp||$RegCheck) {
			//echo $R1." ".$Row->HOST."<br>";
			if ($R1&&!preg_match("/".$R1."/i", $Row->HOST)) continue;
			if ($R2&&preg_match("/".$R2."/i", $Row->HOST)) continue;
		}
		if (!$Row->KEY_VAR&&$Row->GKEY_VAR) $Row->KEY_VAR=$Row->GKEY_VAR;
		if (!$Row->BAN&&$Row->GBAN) $Row->BAN=$Row->GBAN;
		$Row->HOST=htmlspecialchars($Row->HOST);
		$IdsArr[]=$Row->ID;
		$Row->_STYLE=$Sql->_STYLE;
		$HostArr[]=$Row;
	}
	$SubMenu[0]['Name']=$Lang['AddNewGrp'];
	$SubMenu[0]['Link']=getURL("natural_host", "Mode=Grp&EditId=new");
	if ($GrpId>0) {
		$SubMenu[2]['Name']=$Lang['EditGrp'];
		$SubMenu[2]['Link']=getURL("natural_host", "Mode=Grp&EditId=$GrpId");
		$SubMenu[3]['Name']=$Lang['DeleteGrp'];
		$SubMenu[3]['Link']=getURL("natural_host", "Mode=Grp&DeleteId=$GrpId");
		$SubMenu[3]['Onclick']="return confirm('".$Lang['YouSure']."')";
	}
	include $nsTemplate->Inc("admin.natural_host");
}

//// new grp
if ($Mode=="Grp"&&ValidVar($EditId)=="new") {
	if (!ValidArr($EditArr)) {
		$EditArr['Name']="";
		$EditArr['KeyVar']="";
		$EditArr['Ban']=0;
		$EditArr['Regular']="";
		$EditArr['Regular2']="";
	}
	if (!ValidVar($EditArr['Ban'])) $EditArr['Ban']=0;
	$TableCaption=$Lang['CaptionNew'];
	$PageTitle=$Lang['AddNewGrp'];
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("natural_host");
	include $nsTemplate->Inc("admin.natural_grp");
}

//// edit grp
if ($Mode=="Grp"&&ValidId($EditId)) {
	$Query="SELECT * FROM ".PFX."_tracker_host_grp WHERE ID = $EditId";
	$EditGrp=$Db->Select($Query);
	$ProgPath[2]['Name']=$EditGrp->NAME;
	$ProgPath[2]['Url']=getURL("natural_host", "Mode=Grp&EditId=$EditId", "admin");

	if (!ValidArr($EditArr)) {
		$EditArr['Name']=$EditGrp->NAME;
		$EditArr['KeyVar']=$EditGrp->KEY_VAR;
		$EditArr['Ban']=$EditGrp->BAN;
		$EditArr['Regular']=$EditGrp->REGULAR_EXPRESSION;
		$EditArr['Regular2']=$EditGrp->REGULAR_EXPRESSION2;
	}
	if (!ValidVar($EditArr['Ban'])) $EditArr['Ban']=0;
	$TableCaption=$Lang['CaptionEdit'].$EditGrp->NAME;
	$PageTitle=$EditGrp->NAME;
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("natural_host", "GrpId=$EditId");
	include $nsTemplate->Inc("admin.natural_grp");
}

//// new host
if ($Mode=="Host"&&ValidVar($EditId)=="new") {
	if (!ValidArr($EditArr)) {
		$EditArr['Host']="";
		$EditArr['KeyVar']="";
		$EditArr['Ban']=0;
	}
	if (!ValidVar($EditArr['Ban'])) $EditArr['Ban']=0;
	$TableCaption=$Lang['CaptionNew'];
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("natural_host");
	include $nsTemplate->Inc("admin.natural_edit");
}

//// edit host
if ($Mode=="Host"&&ValidId($EditId)) {
	$Query="SELECT * FROM ".PFX."_tracker_host WHERE ID = $EditId";
	$EditHost=$Db->Select($Query);
	$Query = "SELECT * FROM ".PFX."_tracker_referer WHERE REFERER LIKE '%".$EditHost->HOST."%' LIMIT 50";
	$Sql = new Query($Query);
	$Refs=array();
	while ($Row=$Sql->Row()) $Refs[]=$Row;
	if (!ValidArr($EditArr)) {
		$EditArr['Host']=$EditHost->HOST;
		$EditArr['KeyVar']=$EditHost->KEY_VAR;
		$EditArr['Ban']=$EditHost->BAN;
	}
	if (!ValidVar($EditArr['Ban'])) $EditArr['Ban']=0;
	$TableCaption=$Lang['CaptionEdit'].$EditHost->HOST;
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("natural_host");
	include $nsTemplate->Inc("admin.natural_edit");
}

/////////////////////////////////////////////
///////// process functions here


function UpdateHost($Id, $Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $HostId;
	extract($Arr);
	if(!$Host) $ErrArr['Host']=$Lang['MustFill'];
	if (!ValidVar($Ban)) $Ban=0;
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_host SET HOST='$Host', KEY_VAR='$KeyVar', BAN='$Ban' WHERE ID = $Id";
	$Db->Query($Query);
	$Logs->Msg($Lang['RecordUpdated']);
}

function CreateHost($Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $HostId;
	extract($Arr);
	if(!$Host) $ErrArr['Host']=$Lang['MustFill'];
	if (!ValidVar($Ban)) $Ban=0;
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "INSERT INTO ".PFX."_tracker_host (HOST, KEY_VAR, BAN) VALUES ('$Host', '$KeyVar', '$Ban')";
	$Db->Query($Query);
	$nsProduct->Redir("natural_host", "RCrt=1&Mode=Host&EditId=".$Db->LastInsertId);
}

function DeleteHost($Id)
{
	global $Db, $Mode, $Lang, $Logs;
	$Query = "DELETE FROM ".PFX."_tracker_host WHERE ID = $Id";
	$Db->Query($Query);
	$Mode="List";
}

function DeleteHosts($Arr)
{
	foreach($Arr as $Key=>$Value) {
		if ($Value!=1) continue;
		DeleteHost($Key);
	}
}

function IgnoreHosts($Arr)
{
	global $Db, $Mode, $Lang, $Logs;
	foreach($Arr as $Key=>$Value) {
		if ($Value!=1) continue;
		$Query = "SELECT BAN FROM ".PFX."_tracker_host  WHERE ID = $Key";
		$Ban=$Db->ReturnValue($Query);
		$Ban=!$Ban;
		$Query = "UPDATE ".PFX."_tracker_host SET BAN = '$Ban' WHERE ID = $Key";
		$Db->Query($Query);
	}
	$Mode="List";
	$Logs->Msg($Lang['RecordUpdated']);
}



function CreateGrp($Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $HostId;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!ValidVar($Ban)) $Ban=0;
	$Regular=htmlspecialchars(ValidVar($Regular));
	$Regular2=htmlspecialchars(ValidVar($Regular2));

	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "INSERT INTO ".PFX."_tracker_host_grp (NAME, KEY_VAR, BAN, REGULAR_EXPRESSION, REGULAR_EXPRESSION2) VALUES ('$Name', '$KeyVar', '$Ban', ?, ?)";
	$Db->Query($Query, $Regular, $Regular2);
	$nsProduct->Redir("natural_host", "RCrt=1&Mode=Grp&EditId=".$Db->LastInsertId);
}

function UpdateGrp($Id, $Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $HostId;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!ValidVar($Ban)) $Ban=0;
	$Regular=htmlspecialchars(ValidVar($Regular));
	$Regular2=htmlspecialchars(ValidVar($Regular2));

	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_host_grp SET NAME = '$Name', KEY_VAR = '$KeyVar', BAN = '$Ban', REGULAR_EXPRESSION=?, REGULAR_EXPRESSION2=? WHERE ID = $Id";
	$Db->Query($Query, $Regular, $Regular2);
	$nsProduct->Redir("natural_host", "RUpd=1&Mode=Grp&EditId=$Id");
}



function MoveHostsToGrp($GrpId, $Arr)
{
	global $Db, $nsProduct;
	foreach($Arr as $Key=>$Value) {
		if ($Value!=1) continue;
		$Query = "UPDATE ".PFX."_tracker_host SET GRP_ID = $GrpId WHERE ID = $Key";
		$Db->Query($Query);
	}
	$nsProduct->Redir("natural_host", "RUpd=1&GrpId=$GrpId");
}

function MoveHostsFromGrp($Arr)
{
	global $Db, $nsProduct, $GrpId;
	foreach($Arr as $Key=>$Value) {
		if ($Value!=1) continue;
		$Query = "UPDATE ".PFX."_tracker_host SET GRP_ID = 0 WHERE ID = $Key";
		$Db->Query($Query);
	}
	$nsProduct->Redir("natural_host", "RUpd=1&GrpId=$GrpId");
}

function DeleteGrp($Id)
{
	global $Db, $nsProduct;
	$Query = "UPDATE ".PFX."_tracker_host SET GRP_ID=0 WHERE GRP_ID=$Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_host_grp WHERE ID = $Id";
	$Db->Query($Query);
	$nsProduct->Redir("natural_host", "RDlt=1");
}





function UpdateStats()
{
	global $Logs, $Lang;
	$Query = "
		SELECT RS.*, R.REFERER
			FROM ".PFX."_tracker_referer_set RS
			INNER JOIN ".PFX."_tracker_referer R
				ON R.ID=RS.REFERER_ID
		WHERE (RS.HOST_ID=0 OR RS.NATURAL_KEY=0) AND PROCESSED='0'
	";
	$Set=new Query($Query);
	$Count=0;
	$Cnt=0;
	while ($RefSet=$Set->Row()) {
		$Cnt++;
		$RefArr=PreparePathAddr($RefSet->REFERER);
		if (!ValidArr($RefArr)) continue;
		$HostObj=GetRefHost($RefArr['host']);
		if (!ValidId($HostObj->ID)) continue;
		$Res=RefSetUpdate($RefSet->ID, $RefArr, $RefSet->REFERER_ID, $HostObj);
		if ($Res) $Count++;
	}
	$Logs->Msg(str_replace("{CNT}", "<B>$Count</B>", $Lang['Updated']));
}


function UpdateByRegs()
{
	global $Db, $Logs, $Lang;
	$HostList=array();
	$Updated=0;
	$Query = "SELECT * FROM ".PFX."_tracker_host WHERE GRP_ID=0";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $HostList[$Row->ID]=$Row;
	if (count($HostList)==0) return;
	$Query = "SELECT * FROM ".PFX."_tracker_host_grp";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		if (!$Row->REGULAR_EXPRESSION&&!$Row->REGULAR_EXPRESSION2) continue;
		foreach ($HostList as $HostId=>$SubRow) {
			if ($Row->REGULAR_EXPRESSION
				&&!@preg_match("/".$Row->REGULAR_EXPRESSION."/i", $SubRow->HOST)) continue;
			if ($Row->REGULAR_EXPRESSION2
				&&@preg_match("/".$Row->REGULAR_EXPRESSION2."/i", $SubRow->HOST)) continue;
			$Query="UPDATE ".PFX."_tracker_host SET GRP_ID=".$Row->ID." WHERE ID = $HostId";
			$Db->Query($Query);
			$Updated++;
			unset($HostList[$HostId]);
		}
	}
	$Logs->Msg(str_replace("{CNT}",$Updated,$Lang['Updated']));
}


/////////////////////////////////////////////
///////// library section

function GetGrps()
{
	global $Lang;
	$Grps=array();
	$Grps[0]['Name']=$Lang['NotSorter'];
	$Grps[0]['Value']=0;
	$Query = "SELECT * FROM ".PFX."_tracker_host_grp ORDER BY NAME";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$Grps[$Sql->Position+1]['Name']=$Row->NAME;
		$Grps[$Sql->Position+1]['Value']=$Row->ID;
	}
	if (count($Grps)>0) return $Grps;
	else return false;
}


function RefSetUpdate($RefSetId, $RefArr, $RefId=false, $HostObj=false)
{
	global $Db;
	$Prcsd=0;
	if (ValidVar($RefArr['query'])) $QrArr=ParseTemplate($RefArr['query']);
	if (ValidVar($HostObj->KEY_VAR)&&ValidArr($QrArr)&&isset($QrArr[$HostObj->KEY_VAR])) {
		$Key=ToLower(urldecode($QrArr[$HostObj->KEY_VAR]));
		$Key=ReplacePunkt($Key);
		$Key=preg_replace("/\s+/", " ", $Key);
		$Key=trim($Key);
		$KeyId=GetKeywordId($Key);
		$Prcsd=1;
	}
	else $KeyId=0;

	$Query = "UPDATE ".PFX."_tracker_referer_set SET HOST_ID=".$HostObj->ID.", NATURAL_KEY=$KeyId, PROCESSED='$Prcsd' WHERE ID=$RefSetId";
	$Db->Query($Query);
	return $KeyId;
}

?>