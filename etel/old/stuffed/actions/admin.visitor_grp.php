<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");
if (!ValidId($CurrentCompany->ID)) $nsProduct->Redir("my_tracker", "Mode=visitor_grps", "admin");

/////////////////////////////////////////////
///////// require libraries here
$nsLang->TplInc("inc/user_welcome");


/////////////////////////////////////////////
///////// prepare any variables


$EditId=(ValidVar($_GP['EditId']))?$_GP['EditId']:false;
$ViewId=(ValidId($_GP['ViewId']))?$_GP['ViewId']:false;
$EditArr=(ValidArr($_GP['EditArr']))?$_GP['EditArr']:false;
$NewIp=(ValidVar($_GP['NewIp']))?trim($_GP['NewIp']):false;
$DeleteIp=(ValidVar($_GP['DeleteIp']))?$_GP['DeleteIp']:false;

$Mode=false;
if (ValidId($EditId)) $Mode="edit";
if (ValidId($ViewId)) {
	$Mode="view";
	$EditId=$ViewId;
}
if ($EditId=="new") $Mode="edit";

$PageTitle=$Lang['Title'];
$SubMenu[0]['Name']=$Lang['ShowGrpList'];
$SubMenu[0]['Link']=getURL("my_tracker", "Mode=visitor_grps");
$MenuSection="my_tracker";

$ProgPath[0]['Name']=$Lang['MyTracker'];
$ProgPath[0]['Url']=getUrl("my_tracker", "", "admin");


if (ValidVar($NewIp)&&!ValidIpTempl($NewIp)) {
	$Logs->Err($Lang['InvalidIp']);
	$NewIp=false;
}


/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if (ValidId($EditId)&&$NewIp) AddIp($NewIp, $EditId);
	if (ValidId($EditId)&&ValidId($DeleteIp)) RemoveIp($DeleteIp, $EditId);
	if (ValidArr($EditArr)&&$EditId=="new") CreateNewGrp($EditArr);
	if (ValidArr($EditArr)&&ValidId($EditId)) UpdateGrp($EditId, $EditArr);
}

/////////////////////////////////////////////
///////// display section here


if ($EditId=="new") {
	if (!$EditArr) {
		$EditArr['Name']="";
		$EditArr['Descr']="";
		$EditArr['Wacth']=0;
	}
	if (!ValidVar($EditArr['Watch'])) $EditArr['Watch']=0;
	$TableCaption=$Lang['CreateNewGrp'];
}

$IpArr=array();

if (ValidId($EditId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_client_visitor_grp WHERE ID=$EditId AND COMPANY_ID=".$CurrentCompany->ID;
	$VisGrp=$Db->Select($Query);
	if (!ValidId($VisGrp->ID)) $nsProduct->Redir("my_reports", "", "admin");
	$Query = "SELECT ID, IP FROM ".PFX."_tracker_client_visitor_grp_ip WHERE GRP_ID=$EditId";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$GrpIpArr=array();
	$IpTemplArr=array();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$IpArr[]=$Row;
		if (strpos($Row->IP, "*")===false) $GrpIpArr[]="'".$Row->IP."'";
		else $IpTemplArr[]=$Row->IP;
	}
	$Query = "SELECT ID FROM ".PFX."_tracker_watch WHERE VISITOR_GRP_ID=$EditId AND USER_ID=".$nsUser->UserId();
	$CheckWatch=$Db->ReturnValue($Query);
	$ProgPath[1]['Name']=$VisGrp->NAME;
	$ProgPath[1]['Url']=getUrl("visitor_grp", "CpId=$CpId&ViewId=".$VisGrp->ID, "admin");

	$TableCaption=$Lang['GrpEdit'];
	$EditArr['Name']=$VisGrp->NAME;
	$EditArr['Descr']=$VisGrp->DESCRIPTION;
	$EditArr['Watch']=($CheckWatch)?1:0;
}


$EditArr['Name']=htmlspecialchars(stripslashes($EditArr['Name']));
$EditArr['Descr']=htmlspecialchars(stripslashes($EditArr['Descr']));

if (ValidId($VisGrp->ID)&&count($IpArr)>0) {
	$PageTitle=$VisGrp->NAME;
	$SubMenu[1]['Name']=$Lang['ShowPaths'];
	$SubMenu[1]['Link']=getURL("visitor_path", "GrpId=$EditId", "report");
	if ($Mode=="view") {
		$SubMenu[2]['Name']=$Lang['EditGrp'];
		$SubMenu[2]['Link']=getURL("visitor_grp", "EditId=$EditId&CpId=$CpId", "admin");
	}
	if ($Mode=="edit") {
		$SubMenu[2]['Name']=$Lang['GrpInfo'];
		$SubMenu[2]['Link']=getURL("visitor_grp", "ViewId=$EditId&CpId=$CpId", "admin");
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
	if ($IdsStr) $WhereArr[]="S_LOG.SITE_ID IN (".$IdsStr.")";
	if ($WhereIpIn) $WhereArr[]=$WhereIpIn;
	if ($WhereIpTempl) $WhereArr[]=$WhereIpTempl;
	$WhereArr[]="S_LOG.PAGE_ID>0";

	$WhereStr="";
	if (count($WhereArr)>0) $WhereStr=" WHERE ".implode(" AND ", $WhereArr);




	$Query = "
		SELECT 
		COUNT(*) 
		FROM ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_log S_LOG 
		INNER JOIN ".PFX."_tracker_ip I
		ON I.ID=S_LOG.IP_ID
		$WhereStr
	";
	$TotalCnt=$Db->ReturnValue($Query);

	$Query = "
		SELECT
		COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_action S_ACTION
		INNER JOIN ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_log S_LOG
			ON S_LOG.ID=S_ACTION.LOG_ID
		INNER JOIN ".PFX."_tracker_ip I
		ON I.ID=S_LOG.IP_ID
		$WhereStr
	";
	$ActionCnt=$Db->ReturnValue($Query);

	$Query = "
		SELECT
		COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_sale S_SALE
		INNER JOIN ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_log S_LOG
			ON S_LOG.ID=S_SALE.LOG_ID
		INNER JOIN ".PFX."_tracker_ip I
		ON I.ID=S_LOG.IP_ID
		$WhereStr
	";
	$SaleCnt=$Db->ReturnValue($Query);

	$Query = "
		SELECT
		COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_click S_CLICK
		INNER JOIN ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_log S_LOG
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
		K.KEYWORD,
		S.HOST

		FROM ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_site S
		ON S.ID=S_LOG.SITE_ID
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


	$AgentArr=array();
	$Query = "
		SELECT
		DISTINCT VA.ID,
		VA.USER_AGENT, VA.GRP_ID,
		VAG.NAME AS GRP_NAME

		FROM ".PFX."_tracker_".$VisGrp->COMPANY_ID."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_site S
		ON S.ID=S_LOG.SITE_ID
		INNER JOIN ".PFX."_tracker_ip I
		ON I.ID=S_LOG.IP_ID
		INNER JOIN ".PFX."_tracker_visitor_agent VA
		ON VA.ID=S_LOG.AGENT_ID
		LEFT JOIN ".PFX."_tracker_visitor_agent_grp VAG
		ON VAG.ID=VA.GRP_ID
		$WhereStr
		
		ORDER BY S_LOG.STAMP ASC
	";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$AgentArr[]=$Row;
	}
}



include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


function CreateNewGrp($Arr)
{
	global $Db, $Logs, $nsProduct, $CurrentCompany, $nsUser, $Lang;
	extract($Arr);
	if (!ValidVar($Name)) {$Logs->Err($Lang['MustFillName']); return;}
	if (!ValidVar($Watch)) $Watch=0;
	$Query = "INSERT INTO ".PFX."_tracker_client_visitor_grp (NAME, DESCRIPTION, COMPANY_ID) VALUES (?, ?, ".$CurrentCompany->ID.")";
	$Db->Query($Query, $Name, $Descr);
	$NewId=$Db->LastInsertId;
	if ($Watch) SetVisWatch($NewId, $nsUser->UserId());
	$nsProduct->Redir("visitor_grp", "RCrt=1&EditId=$NewId&RCtr=1", "admin");
}

function UpdateGrp($Id, $Arr)
{
	global $Db, $Logs, $nsProduct, $CurrentCompany, $nsUser, $Lang;
	extract($Arr);
	if (!ValidVar($Name)) {$Logs->Err($Lang['MustFillName']); return;}
	if (!ValidVar($Watch)) $Watch=0;
	$Query = "
		UPDATE ".PFX."_tracker_client_visitor_grp SET
			NAME=?,
			DESCRIPTION=?
		WHERE ID=$Id AND COMPANY_ID=".$CurrentCompany->ID."
	";
	$Db->Query($Query, $Name, $Descr);
	$CheckWatch=CheckVisWatch($Id, $nsUser->UserId());
	if (ValidVar($Watch)==1&&!$CheckWatch) SetVisWatch($Id, $nsUser->UserId());
	if (!ValidVar($Watch)&&$CheckWatch) RemoveVisWatch($Id, $nsUser->UserId());

	$nsProduct->Redir("visitor_grp", "RUpd=1&EditId=$Id", "admin");
}

function SetVisWatch($VisId, $UserId)
{
	global $Db;
	$Query = "INSERT INTO ".PFX."_tracker_watch (VISITOR_GRP_ID, USER_ID) VALUES ($VisId, $UserId)";
	$Db->Query($Query);
}

function RemoveVisWatch($VisId, $UserId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE VISITOR_GRP_ID=$VisId AND USER_ID=$UserId";
	$Db->Query($Query);
}


function CheckVisWatch($VisId, $UserId)
{
	global $Db;
	$Query = "SELECT ID FROM ".PFX."_tracker_watch WHERE VISITOR_GRP_ID=$VisId AND USER_ID=$UserId";
	return $Db->ReturnValue($Query);
}

function AddIp($Ip, $GrpId) 
{
	global $Db;
	$Query="SELECT ID FROM ".PFX."_tracker_client_visitor_grp_ip WHERE GRP_ID=$GrpId AND IP='$Ip'";
	$Check=$Db->ReturnValue($Query);
	if ($Check) return false;
	$Query = "INSERT INTO ".PFX."_tracker_client_visitor_grp_ip (GRP_ID, IP) VALUES ($GrpId, '$Ip')";
	$Db->Query($Query);
}

function RemoveIp($IpId, $GrpId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_client_visitor_grp_ip WHERE ID=$IpId AND GRP_ID=$GrpId";
	$Db->Query($Query);
}

/////////////////////////////////////////////
///////// library section


?>