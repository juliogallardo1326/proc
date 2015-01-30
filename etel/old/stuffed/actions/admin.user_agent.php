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

/////////////////////////////////////////////
///////// prepare any variables
$RegSearchGrp=false;
if (isset($_GP['EditId'])) $EditId=$_GP['EditId'];
if (isset($_GP['DeleteId'])) $DeleteId=$_GP['DeleteId'];
if (isset($_GP['EditArr'])) $EditArr=$_GP['EditArr'];
if (isset($_GP['Srch'])) $Srch=$_GP['Srch'];
if (isset($_GP['SelAgent'])) $SelAgent=$_GP['SelAgent'];
if (isset($_GP['Mode'])) $Mode=$_GP['Mode'];
if (isset($_GP['GrpId'])) $GrpId=$_GP['GrpId'];
if (isset($_GP['GrpMove'])) $GrpMove=$_GP['GrpMove'];
if (isset($_GP['Update'])) $Update=$_GP['Update'];
$SearchRegId=(ValidId($_GP['SearchRegId']))?$_GP['SearchRegId']:false;
$R1=(ValidVar($_GP['R1']))?trim($_GP['R1']):false;
$R2=(ValidVar($_GP['R2']))?trim($_GP['R2']):false;
$RegCheck=(ValidVar($_GP['RegCheck']))?true:false;

if (!isset($Srch)) $Srch="";
else $Srch=str_replace(" ", "%", $Srch);


$PageTitle=$Lang['Title'];
$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");
$ProgPath[1]['Name']=$Lang['UserAgents'];
$ProgPath[1]['Url']=getURL("user_agent", "", "admin");
$MenuSection="admin";

if (!ValidVar($Mode)) $Mode="List";
$Grps=GetGrps();
if (!ValidId($GrpId)) $GrpId=0;

/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {

	if ($Mode=="Update") UpdateByRegs();

	if ($Mode=="Delete"&&ValidArr($SelAgent)) DeleteAgents($SelAgent);
	if ($Mode=="Ignore"&&ValidArr($SelAgent)) IgnoreAgents($SelAgent);

	if ($Mode=="GrpMove"&&ValidId($GrpMove)&&ValidArr($SelAgent)) MoveAgentsToGrp($GrpMove, $SelAgent);
	if ($Mode=="GrpFree"&&ValidArr($SelAgent)) MoveAgentsFromGrp($SelAgent);

	if ($Mode=="Agent"&&ValidId($DeleteId)) DeleteAgent($DeleteId);

	if ($Mode=="Grp"&&ValidVar($EditId)=="new"&&ValidArr($EditArr)) CreateGrp($EditArr);
	if ($Mode=="Grp"&&ValidId($EditId)&&ValidArr($EditArr)) UpdateGrp($EditId, $EditArr);
	if ($Mode=="Grp"&&ValidId($DeleteId)) DeleteGrp($DeleteId);
}

if ($Mode!="Host"&&$Mode!="Grp") $Mode="List";

/////////////////////////////////////////////
///////// display section here

if ($SearchRegId) {
	$RegExp=$Db->Select("SELECT * FROM ".PFX."_tracker_visitor_agent_grp WHERE ID = $SearchRegId");
	$R1=$RegExp->REGULAR_EXPRESSION;
	$R2=$RegExp->REGULAR_EXPRESSION2;
}

// list
if ($Mode=="List"&&!ValidId($EditId)&&$EditId!="new") {

	if ($GrpId>0) {
		$GrpName=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_visitor_agent_grp WHERE ID=$GrpId");
		$PageTitle=str_replace("{GRP}", "<B>$GrpName</B>", $Lang['Title2']);
	}
	$ProgPath[2]['Name']=$PageTitle;
	$ProgPath[2]['Url']=getURL("user_agent", "GrpId=$GrpId", "admin");

	$Where="WHERE GRP_ID=$GrpId ";
	$CntWhere="GRP_ID=$GrpId ";
	if (ValidVar($Srch)) {
		$Where.=" AND USER_AGENT LIKE '%$Srch%'";
		$CntWhere.=" AND USER_AGENT LIKE '%$Srch%'";
	}
	$Cnt=$Db->CNT(PFX."_tracker_visitor_agent", $CntWhere);
	$Limit=($SearchRegId)?$Cnt:50;
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
	//if ($SearchRegId) $Pages->Pages=0;
	$Query = "
		SELECT VA.*, AG.BAN AS GBAN
			FROM ".PFX."_tracker_visitor_agent VA
				LEFT JOIN ".PFX."_tracker_visitor_agent_grp AG
					ON AG.ID=VA.GRP_ID
			$Where 
			ORDER BY VA.USER_AGENT ASC  
			LIMIT ".$Pages->PageStart.", ".$Pages->Limit." 
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$AgentArr=array();
	$IdsArr=array();

	while ($Row=$Sql->Row()) {
		if ($SearchRegId||$RegCheck) {
			if ($R1&&!@preg_match("/$R1/i", $Row->USER_AGENT)) continue;
			if ($R2&&@preg_match("/$R2/", $Row->USER_AGENT)) continue;
		}
		if (!$Row->BAN&&$Row->GBAN) $Row->BAN=$Row->GBAN;
		$IdsArr[]=$Row->ID;
		$Row->USER_AGENT=htmlspecialchars($Row->USER_AGENT);
		$Row->_STYLE=$Sql->_STYLE;
		$AgentArr[]=$Row;
	}
	$SubMenu[0]['Name']=$Lang['AddNewGrp'];
	$SubMenu[0]['Link']=getURL("user_agent", "Mode=Grp&EditId=new");
	if ($GrpId>0||$Srch) {
		//$SubMenu[1]['Name']=$Lang['ShowNoGrp'];
		//$SubMenu[1]['Link']=getURL("user_agent");
	}
	if ($GrpId>0) {
		$SubMenu[2]['Name']=$Lang['EditGrp'];
		$SubMenu[2]['Link']=getURL("user_agent", "Mode=Grp&EditId=$GrpId");
		$SubMenu[3]['Name']=$Lang['DeleteGrp'];
		$SubMenu[3]['Link']=getURL("user_agent", "Mode=Grp&DeleteId=$GrpId");
		$SubMenu[3]['Onclick']="return confirm('".$Lang['YouSure']."')";
	}
	include $nsTemplate->Inc("admin.user_agent");
}

//// new grp
if ($Mode=="Grp"&&ValidVar($EditId)=="new") {
	if (!ValidArr($EditArr)) {
		$EditArr['Name']="";
		$EditArr['Ban']=0;
		$EditArr['Regular']="";
		$EditArr['Regular2']="";
	}
	if (!ValidVar($EditArr['Ban'])) $EditArr['Ban']=0;
	$TableCaption=$Lang['CaptionNew'];
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("user_agent");
	$PageTitle=$Lang['AddNewGrp'];
	include $nsTemplate->Inc("admin.agent_grp");
}

//// edit grp
if ($Mode=="Grp"&&ValidId($EditId)) {
	$Query="SELECT * FROM ".PFX."_tracker_visitor_agent_grp WHERE ID = $EditId";
	$EditGrp=$Db->Select($Query);
	$ProgPath[2]['Name']=$EditGrp->NAME;
	$ProgPath[2]['Url']=getURL("user_agent", "Mode=Grp&EditId=$EditId", "admin");


	if (!ValidArr($EditArr)) {
		$EditArr['Name']=$EditGrp->NAME;
		$EditArr['Ban']=$EditGrp->BAN;
		$EditArr['Regular']=$EditGrp->REGULAR_EXPRESSION;
		$EditArr['Regular2']=$EditGrp->REGULAR_EXPRESSION2;
	}
	if (!ValidVar($EditArr['Ban'])) $EditArr['Ban']=0;
	$TableCaption=$Lang['CaptionEdit'].$EditGrp->NAME;
	$PageTitle=$EditGrp->NAME;
	$SubMenu[0]['Name']=$Lang['BackToList'];
	$SubMenu[0]['Link']=getURL("user_agent", "GrpId=$EditId");
	if ($EditGrp->REGULAR_EXPRESSION||$EditGrp->REGULAR_EXPRESSION2) {
		//$SubMenu[1]['Name']=$Lang['FindByReg'];
		//$SubMenu[1]['Link']=getURL("user_agent", "SearchRegId=$EditId");
	}
	include $nsTemplate->Inc("admin.agent_grp");
}


/////////////////////////////////////////////
///////// process functions here



function DeleteAgent($Id)
{
	global $Db, $Mode;
	$Query = "UPDATE ".PFX."_tracker_visitor SET USER_AGENT_ID=0 WHERE USER_AGENT_ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_visitor_agent WHERE ID = $Id";
	$Db->Query($Query);
	$Mode="List";
}

function DeleteAgents($Arr)
{
	foreach($Arr as $Key=>$Value) {
		if ($Value!=1) continue;
		DeleteAgent($Key);
	}
}

function IgnoreAgents($Arr)
{
	global $Db, $Mode;
	foreach($Arr as $Key=>$Value) {
		if ($Value!=1) continue;
		$Query = "SELECT BAN FROM ".PFX."_tracker_visitor_agent  WHERE ID = $Key";
		$Ban=$Db->ReturnValue($Query);
		$Ban=!$Ban;
		$Query = "UPDATE ".PFX."_tracker_visitor_agent SET BAN = '$Ban' WHERE ID = $Key";
		$Db->Query($Query);
	}
	$Mode="List";
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
	$Query = "INSERT INTO ".PFX."_tracker_visitor_agent_grp (NAME, BAN, REGULAR_EXPRESSION, REGULAR_EXPRESSION2) VALUES ('$Name', '$Ban', ?, ?)";
	$Db->Query($Query, $Regular, $Regular2);
	$nsProduct->Redir("user_agent", "RCrt=1&Mode=Grp&EditId=".$Db->LastInsertId);
}

function UpdateGrp($Id, $Arr)
{
	global $Db, $Logs, $Lang, $nsProduct, $HostId;
	extract($Arr);
	if(!$Name) $ErrArr['Name']=$Lang['MustFill'];
	if (!ValidVar($Ban)) $Ban=0;
	$Regular2=htmlspecialchars(ValidVar($Regular2));
	$Regular=htmlspecialchars(ValidVar($Regular));
	if (isset($ErrArr)) {$Logs->Msg($Lang['FormErr']); $GLOBALS['ErrArr']=$ErrArr; return;}
	$Query = "UPDATE ".PFX."_tracker_visitor_agent_grp SET NAME = '$Name', BAN = '$Ban', REGULAR_EXPRESSION = ?, REGULAR_EXPRESSION2 = ? WHERE ID = $Id";
	$Db->Query($Query, $Regular, $Regular2);
	$nsProduct->Redir("user_agent", "RUpd=1&Mode=Grp&EditId=$Id");
}



function MoveAgentsToGrp($GrpId, $Arr)
{
	global $Db, $nsProduct;
	foreach($Arr as $Key=>$Value) {
		if ($Value!=1) continue;
		$Query = "UPDATE ".PFX."_tracker_visitor_agent SET GRP_ID = $GrpId WHERE ID = $Key";
		$Db->Query($Query);
	}
	$nsProduct->Redir("user_agent", "RUpd=1&GrpId=$GrpId");
}

function MoveAgentsFromGrp($Arr)
{
	global $Db, $nsProduct, $GrpId;
	foreach($Arr as $Key=>$Value) {
		if ($Value!=1) continue;
		$Query = "UPDATE ".PFX."_tracker_visitor_agent SET GRP_ID = 0 WHERE ID = $Key";
		$Db->Query($Query);
	}
	$nsProduct->Redir("user_agent", "RUpd=1&GrpId=$GrpId");
}

function DeleteGrp($Id)
{
	global $Db, $nsProduct;
	$Query = "UPDATE ".PFX."_tracker_visitor_agent SET GRP_ID=0 WHERE GRP_ID=$Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_visitor_agent_grp WHERE ID = $Id";
	$Db->Query($Query);
	$nsProduct->Redir("user_agent", "RDlt=1");
}




function UpdateByRegs()
{
	global $Db, $Logs, $Lang;
	$AgentList=array();
	$Updated=0;
	$Query = "SELECT * FROM ".PFX."_tracker_visitor_agent WHERE GRP_ID=0";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $AgentList[$Row->ID]=$Row;
	if (count($AgentList)==0) return;
	$Query = "SELECT * FROM ".PFX."_tracker_visitor_agent_grp";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		if (!$Row->REGULAR_EXPRESSION&&!$Row->REGULAR_EXPRESSION2) continue;
		foreach ($AgentList as $AgentId=>$SubRow) {
			if ($Row->REGULAR_EXPRESSION
				&&!@preg_match("/".$Row->REGULAR_EXPRESSION."/i", $SubRow->USER_AGENT)) continue;
			if ($Row->REGULAR_EXPRESSION2
				&&@preg_match("/".$Row->REGULAR_EXPRESSION2."/i", $SubRow->USER_AGENT)) continue;
			$Query="UPDATE ".PFX."_tracker_visitor_agent SET GRP_ID=".$Row->ID." WHERE ID = $AgentId";
			$Db->Query($Query);
			$Updated++;
			unset($AgentList[$AgentId]);
		}
	}
	$Logs->Msg(str_replace("{CNT}",$Updated,$Lang['RecUpdated']));
}


/////////////////////////////////////////////
///////// library section

function GetGrps()
{
	global $Lang;
	$Grps=array();
	$Grps[0]['Name']=$Lang['NotSorter'];
	$Grps[0]['Value']=0;
	$Query = "SELECT * FROM ".PFX."_tracker_visitor_agent_grp ORDER BY NAME";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$Grps[$Sql->Position+1]['Name']=$Row->NAME;
		$Grps[$Sql->Position+1]['Value']=$Row->ID;
	}
	if (count($Grps)>0) return $Grps;
	else return false;
}

?>