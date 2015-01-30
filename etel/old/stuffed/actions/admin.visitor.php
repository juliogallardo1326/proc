<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");

/////////////////////////////////////////////
///////// require libraries here
$nsLang->TplInc("inc/user_welcome");
$nsLang->TplInc("constructor/report_constructor");

/////////////////////////////////////////////
///////// prepare any variables
$ViewId=(ValidId($_GP['ViewId']))?$_GP['ViewId']:false;
$VisId=(ValidId($_GP['VisId']))?$_GP['VisId']:false;
$EditArr=(ValidArr($_GP['EditArr']))?$_GP['EditArr']:false;
$NewIp=(ValidVar($_GP['NewIp']))?trim($_GP['NewIp']):false;
$CpId=(ValidId($_GP['CpId']))?$_GP['CpId']:false;
$DeleteIp=(ValidVar($_GP['DeleteIp']))?$_GP['DeleteIp']:false;

$ProgPath[0]['Name']=$Lang['MyTracker'];
$ProgPath[0]['Url']=getUrl("my_tracker", "", "admin");

if (!$VisId&&!$ViewId) $nsProduct->Redir("default", "", "admin");

$Mode=false;
if (ValidId($VisId)) $Mode="edit";
if (ValidId($ViewId)) {
	$Mode="view";
	$VisId=$ViewId;
}

if (ValidVar($NewIp)&&!ValidIpTempl($NewIp)) {
	$Logs->Err($Lang['InvalidIp']);
	$NewIp=false;
}

$PageTitle=$Lang['Title'];
$TableCaption=$Lang['Visitor']." $VisId";
$MenuSection="my_tracker";


//$CpId=false;
//$AllClients=false;
$IpArr=array();

if ($nsUser->ADMIN) {
	//$AllClients=1;
	//$CpId=false;
}
else {
	$CpId=$nsUser->COMPANY_ID;
}

$SubMenu[0]['Name']=$Lang['ShowPaths'];
$SubMenu[0]['Link']=getURL("visitor_path", "VisId=$VisId", "report");

if ($Mode=="edit") {
	$SubMenu[1]['Name']=$Lang['VisitorInfo'];
	$SubMenu[1]['Link']=getURL("visitor", "ViewId=$VisId&CpId=$CpId", "admin");
}
if ($Mode=="view") {
	$SubMenu[1]['Name']=$Lang['EditVisitor'];
	$SubMenu[1]['Link']=getURL("visitor", "VisId=$VisId&CpId=$CpId", "admin");
}



$UsersWhere="";
if (!$nsUser->ADMIN) $UsersWhere=" AND CV.COMPANY_ID=".$CurrentCompany->ID;
$Query = "
	SELECT 
	V.*, I.IP AS LAST_IP,
	VA.GRP_ID, VA.USER_AGENT, 
	VAG.NAME AS GRP_NAME,
	CV.NAME, CV.DESCRIPTION, CV.COMPANY_ID, CV.ID AS CLIENT_VIS_ID,
	W.ID AS WATCH
	FROM ".PFX."_tracker_visitor V
	LEFT JOIN ".PFX."_tracker_ip I
		ON I.ID=V.LAST_IP_ID
	LEFT JOIN ".PFX."_tracker_visitor_agent VA
		ON VA.ID=V.LAST_AGENT_ID
	LEFT JOIN ".PFX."_tracker_visitor_agent_grp VAG
		ON VAG.ID=VA.GRP_ID
	LEFT JOIN ".PFX."_tracker_client_visitor CV
		ON CV.VISITOR_ID=V.ID $UsersWhere
	LEFT JOIN ".PFX."_tracker_watch W
		ON W.USER_ID=".$nsUser->UserId()." AND W.VISITOR_ID=V.ID
	WHERE V.ID=$VisId
";
$Visitor=$Db->Select($Query);
if (!$Visitor->COMPANY_ID) $Visitor->COMPANY_ID=$CpId;
if (ValidVar($Visitor->NAME)) $TableCaption=$Visitor->NAME;
$Visitor->WATCH=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_watch WHERE VISITOR_ID=$VisId AND USER_ID=".$nsUser->UserId());
$CpId=$Visitor->COMPANY_ID;

if (ValidVar($Visitor->NAME)) {
	$ProgPath[1]['Name']=$Visitor->NAME;
	$ProgPath[1]['Url']=getUrl("visitor", "CpId=$CpId&ViewId=".$Visitor->ID, "admin");
}
else {
	$ProgPath[1]['Name']=$Lang['Visitor']." ".$Visitor->ID;
	$ProgPath[1]['Url']=getUrl("visitor", "CpId=$CpId&ViewId=".$Visitor->ID, "admin");
}


/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if (ValidArr($EditArr)) UpdateVisitor($VisId, $EditArr);
	if (ValidId($Visitor->CLIENT_VIS_ID)&&$NewIp) AddIp($NewIp, $Visitor->CLIENT_VIS_ID);
	if (ValidId($Visitor->CLIENT_VIS_ID)&&ValidId($DeleteIp)) RemoveIp($DeleteIp, $Visitor->CLIENT_VIS_ID);
}

/////////////////////////////////////////////
///////// display section here
$GrpIpArr=array();
$IpTemplArr=array();

if (ValidId($Visitor->CLIENT_VIS_ID)) {
	$Query = "SELECT ID, IP FROM ".PFX."_tracker_client_visitor_ip WHERE CLIENT_VISITOR_ID=".$Visitor->CLIENT_VIS_ID;
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$IpArr[]=$Row;
		if (strpos($Row->IP, "*")===false) $GrpIpArr[]="'".$Row->IP."'";
		else $IpTemplArr[]=$Row->IP;
	}
}


if (!ValidArr($EditArr)) {
	$EditArr['Name']=htmlspecialchars(stripslashes($Visitor->NAME));
	$EditArr['Descr']=htmlspecialchars(stripslashes($Visitor->DESCRIPTION));
	$EditArr['Watch']=$Visitor->WATCH;
}
if (ValidArr($EditArr)) {
	$EditArr['Name']=htmlspecialchars(stripslashes($EditArr['Name']));
	$EditArr['Descr']=htmlspecialchars(stripslashes($EditArr['Descr']));
	$EditArr['Watch']=$Visitor->WATCH;
}

$IdsStr="";
if (!$nsUser->ADMIN) {
	$SiteIds=array();
	$Query = "SELECT ID FROM ".PFX."_tracker_site WHERE COMPANY_ID=".$nsUser->COMPANY_ID;
	$Sql= new Query($Query);
	while ($Row=$Sql->Row()) $SiteIds[]=$Row->ID;
	$IdsStr=implode(",",$SiteIds);
}

	$WhereIpIn="";
	if (count($GrpIpArr)>0) $WhereIpIn="I.IP IN (".implode(",", $GrpIpArr).")";
	$WhereIpTempl="";
	if (count($IpTemplArr)>0) {
		$LikeArr=array();
		for($i=0;$i<Count($IpTemplArr);$i++) {
			$IpTemplArr[$i]=str_replace("*", "%", $IpTemplArr[$i]);
			$LikeArr[]="I.IP LIKE '".$IpTemplArr[$i]."'";
		}
		$WhereIpTempl="(".implode(" OR ", $LikeArr).")";
	}

	$WhereArr=array();
	if (!ValidVar($WhereIpIn)&&!ValidVar($WhereIpTempl)) $WhereArr[]="S_LOG.VISITOR_ID=$VisId";
	if ($IdsStr) $WhereArr[]="S_LOG.SITE_ID IN (".$IdsStr.")";
	if ($WhereIpIn) $WhereArr[]=$WhereIpIn;
	if ($WhereIpTempl) $WhereArr[]=$WhereIpTempl;
	$WhereArr[]="S_LOG.PAGE_ID>0";

	$WhereStr="";
	if (count($WhereArr)>0) $WhereStr=" WHERE ".implode(" AND ", $WhereArr);


if ($Visitor->COMPANY_ID) {

	$Query = "
		SELECT 
		COUNT(*) 
		FROM ".PFX."_tracker_".$Visitor->COMPANY_ID."_stat_log S_LOG 
		INNER JOIN ".PFX."_tracker_ip I
		ON I.ID=S_LOG.IP_ID
		$WhereStr
	";
	$TotalCnt=$Db->ReturnValue($Query);

	$Query = "
		SELECT
		COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$Visitor->COMPANY_ID."_stat_action S_ACTION
		INNER JOIN ".PFX."_tracker_".$Visitor->COMPANY_ID."_stat_log S_LOG
			ON S_LOG.ID=S_ACTION.LOG_ID
		INNER JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
			$WhereStr
	";
	$ActionCnt=$Db->ReturnValue($Query);

	$Query = "
		SELECT
		COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$Visitor->COMPANY_ID."_stat_sale S_SALE
		INNER JOIN ".PFX."_tracker_".$Visitor->COMPANY_ID."_stat_log S_LOG
			ON S_LOG.ID=S_SALE.LOG_ID
		INNER JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
			$WhereStr
	";
	$SaleCnt=$Db->ReturnValue($Query);

	$Query = "
		SELECT
		COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$Visitor->COMPANY_ID."_stat_click S_CLICK
		INNER JOIN ".PFX."_tracker_".$Visitor->COMPANY_ID."_stat_log S_LOG
			ON S_LOG.ID=S_CLICK.LOG_ID
		INNER JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
			$WhereStr
	";
	$ClickCnt=$Db->ReturnValue($Query);

	$RefArr=array();
	$KeyArr=array();
	$DistKey=array();
	$Query = "
		SELECT
		R.ID, R.REFERER, 
		UNIX_TIMESTAMP(S_LOG.STAMP) AS STAMP,
		RS.NATURAL_KEY,
		K.KEYWORD

		FROM ".PFX."_tracker_".$Visitor->COMPANY_ID."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_referer_set RS
		ON RS.ID=S_LOG.REFERER_SET
		INNER JOIN ".PFX."_tracker_referer R
		ON R.ID=RS.REFERER_ID
		LEFT JOIN ".PFX."_tracker_keyword K
		ON K.ID=RS.NATURAL_KEY
		INNER JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID

			$WhereStr
		
		ORDER BY S_LOG.STAMP ASC
	";
	//echo $Query;
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$RefArr[]=$Row;
		if ($Row->NATURAL_KEY>0&&!in_array($Row->KEYWORD, $DistKey)) {
			$KeyArr[]=$Row;
			$DistKey[]=$Row->KEYWORD;
		}
	}
	$RefCnt=count($RefArr);
	$KeyCnt=count($DistKey);

}

include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


function UpdateVisitor($Id, $EditArr)
{
	global $Db, $nsProduct, $nsUser, $CurrentCompany, $Lang;
	global $Logs, $NewIp;
	if (!ValidId($CurrentCompany->ID)) {
		$Logs->Err($Lang['ChooseClientForSave']);
		return;
	}
	extract($EditArr);
	if (!ValidVar($Watch)) $Watch=0;
	$Query = "SELECT ID FROM ".PFX."_tracker_client_visitor WHERE VISITOR_ID = $Id AND COMPANY_ID=".$CurrentCompany->ID;
	$Check=$Db->ReturnValue($Query);
	if ($Check) $Query = "UPDATE ".PFX."_tracker_client_visitor SET NAME = ?, DESCRIPTION = ? WHERE VISITOR_ID = $Id AND COMPANY_ID=".$CurrentCompany->ID;
	else $Query = "INSERT INTO ".PFX."_tracker_client_visitor (NAME, DESCRIPTION, VISITOR_ID, COMPANY_ID) VALUES (?, ?, $Id, ".$CurrentCompany->ID.")";
	$Db->Query($Query, $Name, $Descr);
	$CheckWatch=CheckVisWatch($Id, $nsUser->UserId());
	if (ValidVar($Watch)==1&&!$CheckWatch) SetVisWatch($Id, $nsUser->UserId());
	if (!ValidVar($Watch)&&$CheckWatch) RemoveVisWatch($Id, $nsUser->UserId());
	$nsProduct->Redir("visitor", "RUpd=1&VisId=$Id&NewIp=$NewIp", "admin");
}



function SetVisWatch($VisId, $UserId)
{
	global $Db;
	$Query = "INSERT INTO ".PFX."_tracker_watch (VISITOR_ID, USER_ID) VALUES ($VisId, $UserId)";
	$Db->Query($Query);
}

function RemoveVisWatch($VisId, $UserId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE VISITOR_ID=$VisId AND USER_ID=$UserId";
	$Db->Query($Query);
}


function CheckVisWatch($VisId, $UserId)
{
	global $Db;
	$Query = "SELECT ID FROM ".PFX."_tracker_watch WHERE VISITOR_ID=$VisId AND USER_ID=$UserId";
	return $Db->ReturnValue($Query);
}

function AddIp($Ip, $VisId) 
{
	global $Db;
	$Query="SELECT ID FROM ".PFX."_tracker_client_visitor_ip WHERE CLIENT_VISITOR_ID=$VisId AND IP='$Ip'";
	$Check=$Db->ReturnValue($Query);
	if ($Check) return false;
	$Query = "INSERT INTO ".PFX."_tracker_client_visitor_ip (CLIENT_VISITOR_ID, IP) VALUES ($VisId, '$Ip')";
	$Db->Query($Query);
}

function RemoveIp($IpId, $VisId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_client_visitor_ip WHERE ID=$IpId AND CLIENT_VISITOR_ID=$VisId";
	$Db->Query($Query);
}

/////////////////////////////////////////////
///////// library section


?>