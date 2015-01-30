<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");



/////////////////////////////////////////////
///////// require libraries here
require_once SELF."/lib/company.func.php";
require_once SYS."/system/lib/validate.func.php";
require_once SELF."/class/pagenums2.class.php";

$nsLang->TplInc("inc/menu");


/////////////////////////////////////////////
///////// prepare any variables
$SiteId=ValidVar($_GP['SiteId']);
$CpId=(ValidVar($_GP['CpId']))? $_GP['CpId']:false;
$VisId=(ValidVar($_GP['VisId']))? $_GP['VisId']:false;
$GrpId=(ValidVar($_GP['GrpId']))? $_GP['GrpId']:false;
$Filter=(ValidVar($_GP['Filter']))? trim($_GP['Filter']):false;
$IP=(ValidVar($_GP['IP']))? trim($_GP['IP']):false;
$FilterFor=(ValidVar($_GP['FilterFor']))? $_GP['FilterFor']:false;
$ViewDate=(ValidDate($_GP['ViewDate']))? $_GP['ViewDate']:false;
$AllSites=(ValidVar($_GP['AllSites']))?trim($_GP['AllSites']):false;
$OnlineOnly=(ValidVar($_GP['OnlineOnly']))?true:false;
$OnlinePeriod=(ValidVar($_GP['OnlinePeriod']))?$_GP['OnlinePeriod']:false;
$UserOrder=ValidVar($_GET['UserOrder'], false);
$OnPage=(ValidId($_GP['OnPage']))?$_GP['OnPage']:30;
$PC=(ValidId($_GP['PC']))?$_GP['PC']:-1;

$ViewNode=ValidVar($_GET['ViewNode']);


$MoveAgent=ValidVar($_GP['MoveAgent']);
$MoveReferer=ValidVar($_GP['MoveReferer']);
$MoveToGrp=ValidVar($_GP['MoveToGrp']);
$AName=ValidVar($_GP['AName']);

if (!$nsUser->DEMO && $MoveReferer && $MoveToGrp) MoveRefererToGrp($MoveReferer, $MoveToGrp);
if (!$nsUser->DEMO && $MoveAgent && $MoveToGrp) MoveAgentToGrp($MoveAgent, $MoveToGrp);


$UserOrder=PrepareUserOrder($nsUser->DEF_LOGS_ORDER, $UserOrder, "PATH");

$Settings=GetSettings();

if ($OnPage==0) $OnPage=30;
if (!$CpId) $CpId=$CurrentCompany->ID;
if (!ValidId($SiteId)&&!ValidId($CpId)&&!$AllSites) $CpId=$CompId;
$IpId=false;


$OnlineTime=($OnlinePeriod)?$OnlinePeriod:$Db->ReturnValue("SELECT ONLINE_PERIOD FROM ".PFX."_tracker_config WHERE COMPANY_ID=0");
if (!$OnlineTime) $OnlineTime=600;

if ($VisId) $GrpId=false;

$ProgPath=array();
$ProgPath[0]['Name']=$Lang['MLogs'];
$ProgPath[0]['Url']=getUrl("reports", "CpId=$CpId&SiteId=$SiteId", "admin");
$ProgPath[1]['Name']=$Lang['Paths'];
$ProgPath[1]['Url']=$nsProduct->SelfAction("CpId=$CpId&SiteId=$SiteId");


if ($AllSites&&ValidId($CpId)) $SiteId=false;
if ($AllSites&&!ValidId($CpId)) {
	$CpId=$Db->ReturnValue("SELECT COMPANY_ID FROM ".PFX."_tracker_site WHERE ID = $SiteId");
	$SiteId=false;
}


$HostsArr=array();
$MenuSection="logs";
$IpCnt=0; if ($IP) {
	$Filter=$IP;
	$FilterFor="IP";
}

if ($Filter&&$FilterFor=="IP") {
	if (!ValidIp($Filter)) {
		$Logs->Err($Lang['InvalidIP']);
		$IP=false;
		$Filter=false;
	}
	else {
		$IP=$Filter;
		$Query = "
			SELECT COUNT(V.ID) FROM ".PFX."_tracker_visitor V
				INNER JOIN ".PFX."_tracker_ip I
					ON I.ID=V.LAST_IP_ID
				WHERE I.IP = '$IP'
		";
		$IpCnt=$Db->ReturnValue($Query);
		if ($IpCnt==1) {
			$Query = "
				SELECT V.ID FROM ".PFX."_tracker_visitor V 
				INNER JOIN ".PFX."_tracker_ip I
					ON I.ID=V.LAST_IP_ID
				WHERE I.IP = '$IP' ORDER BY ID DESC
			";
			$VisId=$Db->ReturnValue($Query);
		}
		else {
			$IpId=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_ip WHERE IP = '$IP'");
		}
	}
}

$HostGrpArr=array();
$Query = "SELECT * FROM ".PFX."_tracker_host_grp ORDER BY NAME";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) $HostGrpArr[]=$Row;

$AgentGrpArr=array();
$Query = "SELECT * FROM ".PFX."_tracker_visitor_agent_grp ORDER BY NAME";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) $AgentGrpArr[]=$Row;

if (ValidVar($IP)&&$IpCnt==1&&!ValidId($VisId)) {
	$Logs->Msg(str_replace("{IP}",$IP,$Lang['IPNotFound']));
	$IP=false;
}

if (!ValidId($VisId)&&!ValidId($GrpId)&&!ValidVar($ViewDate)&&$IpCnt<2) $ViewDate=UserDate();
$StartDay=false;
$EndDay=false;
$SitesCnt=0;

$VisitorAgent="";
if (ValidId($VisId)&&$VisId>0) {
	$VisWhere="";
	if (!$nsUser->ADMIN) $VisWhere=" AND CV.COMPANY_ID=".$nsUser->COMPANY_ID;
	$Query="
		SELECT 
			V.ID,
			VA.USER_AGENT, CV.NAME,
			CV.ID AS CLIENT_VIS_ID,
			VA.ID AS AGENT_ID,
			VG.ID AS GRP_ID,
			VG.NAME AS GRP_NAME
			FROM ".PFX."_tracker_visitor V 
			INNER JOIN ".PFX."_tracker_visitor_agent VA 
			ON VA.ID=V.LAST_AGENT_ID 
			LEFT JOIN ".PFX."_tracker_visitor_agent_grp VG
				ON VG.ID=VA.GRP_ID
			LEFT JOIN ".PFX."_tracker_client_visitor CV
				ON CV.VISITOR_ID=V.ID $VisWhere
			WHERE V.ID = $VisId
	";
	$Visitor=$Db->Select($Query);
	$VisitorAgent=$Visitor->USER_AGENT;
	if (!$Visitor->GRP_ID) $Visitor->GRP_ID=0;
}

	$GrpIpArr=array();
	$IpTemplArr=array();

if ((ValidId($GrpId)&&$GrpId>0)||ValidId($Visitor->CLIENT_VIS_ID)) {

	if (ValidId($GrpId)&&$GrpId>0) {
		$GrpWhere="";
		if (!$nsUser->ADMIN) $GrpWhere="AND VG.COMPANY_ID=".$CurrentCompany->ID;
		$Query = "
			SELECT
				VG.*
				FROM ".PFX."_tracker_client_visitor_grp VG
				WHERE VG.ID=$GrpId $GrpWhere
		";
		$VisitorGrp=$Db->Select($Query);
		$Query = "SELECT IP FROM ".PFX."_tracker_client_visitor_grp_ip WHERE GRP_ID=$GrpId";
	}

	if (ValidId($Visitor->CLIENT_VIS_ID)) {
		$Query = "SELECT IP FROM ".PFX."_tracker_client_visitor_ip WHERE CLIENT_VISITOR_ID=".$Visitor->CLIENT_VIS_ID;
	}

	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		if (strpos($Row->IP, "*")===false) $GrpIpArr[]="'".$Row->IP."'";
		$IpTemplArr[]=$Row->IP;
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
}

if (ValidVar($VisitorGrp->ID)) {
	$ProgPath[2]['Name']=$VisitorGrp->NAME;
	$ProgPath[2]['Url']=$nsProduct->SelfAction("CpId=$CpId&SiteId=$SiteId&GrpId=$GrpId");
}

$SitesArr=array();

if (!ValidId($SiteId)&&!ValidId($CpId)) $nsProduct->Redir("default", "", "admin");


if (ValidId($CpId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_client WHERE ID = $CpId";
	$Comp=$Db->Select($Query);
	$PageTitle=$Comp->NAME;
	$Query = "SELECT ID, HOST FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$SitesArr[]=$Row;
		$IdArr[]=$Row->ID;
	}
	$SiteList=implode(",", $IdArr);
	$SitesCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId");
}

if (ValidId($SiteId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_site WHERE ID = $SiteId";
	$Site=$Db->Select($Query);
	if (ValidId($CpId)&&$CpId!=$Site->COMPANY_ID) $CpId=$Site->COMPANY_ID;
	$PageTitle=$Site->HOST;
	$SiteList=$SiteId;
	$SitesCnt=$Db->ReturnValue("SELECT COUNT(*) FROM ".PFX."_tracker_site WHERE COMPANY_ID=".$Site->COMPANY_ID);
}

if (ValidId($SiteId)) $Query = "SELECT SH.SITE_ID, SH.ID FROM ".PFX."_tracker_site_host SH WHERE SH.SITE_ID=$SiteId";
if (ValidId($CpId)&&!ValidId($SiteId)) $Query = "SELECT SH.SITE_ID, SH.ID FROM ".PFX."_tracker_site_host SH INNER JOIN ".PFX."_tracker_site TS ON TS.ID=SH.SITE_ID WHERE TS.COMPANY_ID=$CpId";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) $HostsArr[$Row->SITE_ID][]=$Row->ID;


if ($ViewDate) {
	$EndDay=$Db->ReturnValue("SELECT DATE_ADD('$ViewDate', INTERVAL 1 DAY)")." 00:00:00";
	$StartDay=$ViewDate." 00:00:00";
}

if (ValidId($SiteId))$PageTitle.=" : ".$Lang['Paths'];
if (!ValidId($SiteId))$PageTitle.=" : ".$Lang['Paths3'];


if	(ValidId($VisId)&&$VisId>0) {
	if (ValidVar($Visitor->NAME)) $PageTitle=$Visitor->NAME.": ".$Lang['Paths2'];
	else $PageTitle=str_replace("{VIS_ID}", $VisId, $Lang['Title2']);
}

if (ValidId($GrpId)&&$GrpId>0) {
	$PageTitle=$VisitorGrp->NAME.": ".$Lang['Paths2'];
}

/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here


if ($ViewDate) {
	$DaysWhereArr=array();
	$DaysWhere="";
	if ($IpCnt>1||ValidId($GrpId)||ValidId($Visitor->CLIENT_VIS_ID)) {
		$DaysWhere.=" INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
		$DaysWhere.=" INNER JOIN ".PFX."_tracker_ip I ON I.ID=S_LOG.IP_ID";
	}
	if ($SiteList) $DaysWhereArr[]="S_LOG.SITE_ID IN ($SiteList) ";
	if (ValidId($VisId)&&!ValidVar($WhereIpIn)&&!ValidVar($WhereIpTempl)) $DaysWhereArr[]="S_LOG.VISITOR_ID=$VisId";
	if ($IpCnt>1) $DaysWhereArr[]="V.LAST_IP_ID='$IpId'";
	if (ValidVar($WhereIpIn))  $DaysWhereArr[]=$WhereIpIn;
	if (ValidVar($WhereIpTempl))  $DaysWhereArr[]=$WhereIpTempl;
	if (count($DaysWhereArr)>0) {
		$DaysWhere.=" WHERE ".implode(" AND ", $DaysWhereArr);
	}
	if ($DaysWhere) $DaysWhere.=" AND ";
	else $DaysWhere=" WHERE ";

	$Query = "
		SELECT 
		DATE_FORMAT(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '%Y-%m-%d')
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		$DaysWhere
			DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) < '$ViewDate 00:00:00' 
		ORDER BY S_LOG.STAMP DESC
		LIMIT 1
	";
	$PrevDate=$Db->ReturnValue($Query);

	$Query = "
		SELECT 
		DATE_FORMAT(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '%Y-%m-%d')
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		$DaysWhere
			DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) > '$ViewDate 23:59:59' 
		ORDER BY S_LOG.STAMP ASC
		LIMIT 1
	";
	$NextDate=$Db->ReturnValue($Query);
}


$WhereArr=array();
$WhereStr="";
$JoinArr=array();
$CountJoin=array();
$CountJoinStr="";
$JoinStr="";
$SelectArr=array();
$SelectStr="";
if ($SiteList) $WhereArr[]="S_LOG.SITE_ID IN ($SiteList)";
if ($ViewDate) $WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) BETWEEN '$StartDay' AND '$EndDay'";
if (ValidId($VisId)&&!ValidVar($WhereIpIn)&&!ValidVar($WhereIpTempl)) $WhereArr[]="S_LOG.VISITOR_ID=$VisId";
if ($IpCnt>1) $WhereArr[]="V.LAST_IP_ID=$IpId";
if (ValidVar($IP)&&$IpCnt==0) $WhereArr[]="1=2";

if (ValidId($GrpId)&&$GrpId>0) $CountJoin[]="INNER JOIN ".PFX."_tracker_ip I ON S_LOG.IP_ID=I.ID";

if (ValidVar($WhereIpIn)) $WhereArr[]=$WhereIpIn;
if (ValidVar($WhereIpTempl)) $WhereArr[]=$WhereIpTempl;

$Stamp=gmdate("Y-m-d H:i:s", time());
if ($OnlineOnly) $WhereArr[]="(UNIX_TIMESTAMP('$Stamp')-UNIX_TIMESTAMP(V.LAST_STAMP))<$OnlineTime";

if ($Filter) {
	$Filter=addslashes($Filter);

	if ($FilterFor=="Action") {
		if ($Filter!="*") {
			$WhereArr[]="VA.NAME LIKE '%$Filter%'";
			$JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_action VA ON VA.ID=S_ACTION.ACTION_ID";
		}
		else $WhereArr[]="S_ACTION.ID > 0";
		$CountJoin[]="INNER JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION ON S_ACTION.LOG_ID=S_LOG.ID";
	}

	if ($FilterFor=="ActionItem") {
		if ($Filter!="*") $WhereArr[]="AI.NAME LIKE '%$Filter%'";
		else $WhereArr[]="AI.NAME != ''";
		$CountJoin[]="INNER JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION ON S_ACTION.LOG_ID=S_LOG.ID";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_action_set ACS ON ACS.STAT_ACTION_ID=S_ACTION.ID AND ACS.COMPANY_ID=$CpId";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_action_item AI ON AI.ID=ACS.ACTION_ITEM_ID AND AI.COMPANY_ID=$CpId";
	}

	if ($FilterFor=="Path") {
		$CountJoin[]="INNER JOIN ".PFX."_tracker_site_page SP ON SP.ID = S_LOG.PAGE_ID";
		$CountJoin[]="	LEFT JOIN ".PFX."_tracker_query Q ON Q.ID=S_LOG.QUERY_ID";
		$WhereArr[]="(SP.PATH LIKE '%$Filter%' OR Q.QUERY_STRING LIKE '%$Filter%' OR CONCAT(SP.PATH, '?', Q.QUERY_STRING) LIKE '%$Filter%')";
	}
	
	if ($FilterFor=="Sale") {
		$CountJoin[]="INNER JOIN ".PFX."_tracker_".$CpId."_stat_sale S_SALE ON S_SALE.LOG_ID=S_LOG.ID";
		if ($Filter!="*") $WhereArr[]="(S_SALE.ID = '$Filter' OR S_SALE.CUSTOM_ORDER_ID LIKE '%$Filter%')";
		else $WhereArr[]="S_SALE.ID > 0";
	}

	if ($FilterFor=="SaleItem") {
		$CountJoin[]="INNER JOIN ".PFX."_tracker_".$CpId."_stat_sale S_SALE ON S_SALE.LOG_ID=S_LOG.ID";
		if ($Filter!="*") $WhereArr[]="SI.NAME LIKE '%$Filter%'";
		else $WhereArr[]="SI.NAME != '' ";
		$JoinArr[]="LEFT JOIN ".PFX."_tracker_sale_set SS ON SS.SALE_ID=S_SALE.ID AND SS.COMPANY_ID=$CpId";
		$JoinArr[]="LEFT JOIN ".PFX."_tracker_sale_item SI ON SI.ID=SS.ITEM_ID AND SI.COMPANY_ID=$CpId";
	}

	if ($FilterFor=="Agent") {
		$SelectArr[]="VAG.USER_AGENT";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_agent VAG ON VAG.ID=V.LAST_AGENT_ID";
		$WhereArr[]="VAG.USER_AGENT LIKE '%$Filter%'";
	}

	if ($FilterFor=="Ref") {
		$CountJoin[]="INNER JOIN ".PFX."_tracker_referer_set RS ON RS.ID=S_LOG.REFERER_SET";
		$CountJoin[]="INNER JOIN ".PFX."_tracker_referer R ON R.ID=RS.REFERER_ID";
		if ($Filter!="*") $WhereArr[]="R.REFERER LIKE '%$Filter%'";
		else $WhereArr[]="S_LOG.REFERER_SET>0";
	}

	if ($FilterFor=="Key") {
		$CountJoin[]="INNER JOIN ".PFX."_tracker_referer_set RS ON RS.ID=S_LOG.REFERER_SET";
		$SelectArr[]="K.KEYWORD";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_keyword K ON K.ID=RS.NATURAL_KEY";
		$WhereArr[]="S_LOG.REFERER_SET>0";
		if ($Filter!="*") $WhereArr[]="K.KEYWORD LIKE '%$Filter%'";
		else $WhereArr[]="K.KEYWORD != ''";
	}
}

if (count($WhereArr)>0) $WhereStr=" WHERE ".implode(" AND ", $WhereArr);
if (count($JoinArr)>0) $JoinStr=implode(" \n", $JoinArr);
if (count($CountJoin)>0) $CountJoinStr=implode(" \n", $CountJoin);
if (count($SelectArr)>0) $SelectStr=implode(",", $SelectArr).", ";

$Query = "
	SELECT 
		COUNT(DISTINCT S_LOG.VISITOR_ID)
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		INNER JOIN ".PFX."_tracker_site TS
			ON TS.ID=S_LOG.SITE_ID
		# additional join
		$CountJoinStr
		$JoinStr
		$WhereStr
";
$TotalVisitors=$Db->ReturnValue($Query);
$Pages = new PageNums($TotalVisitors, $OnPage);
$Pages->Calculate();
if ($PC==-1&&$Pages->Pages>1 && $UserOrder == "ASC") {
	$Pages->PageCurrent=$Pages->Pages-1;
	$Pages->PageStart=$Pages->PageCurrent*$Pages->Limit;
}

$Pages->Args="&CpId=$CpId&SiteId=$SiteId&ViewDate=$ViewDate&Filter=$Filter&FilterFor=$FilterFor&VisId=$VisId&GrpId=$GrpId";
$Pages->Args.="&OnlinePeriod=$OnlinePeriod&OnlineOnly=$OnlineOnly&OnPage=$OnPage&UserOrder=$UserOrder";
$Get="&CpId=$CpId&SiteId=$SiteId&ViewDate=$ViewDate&";

$VisArr=array();
$VisTime=array();
$Query = "
	SELECT 
		V.ID, MIN(S_LOG.STAMP) AS MIN_STAMP, 
		(UNIX_TIMESTAMP(MAX(S_LOG.STAMP))-UNIX_TIMESTAMP(MIN(S_LOG.STAMP))) AS TOTAL_TIME
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		INNER JOIN ".PFX."_tracker_site TS
			ON TS.ID=S_LOG.SITE_ID
		# additional join
		$CountJoinStr
		$JoinStr
		$WhereStr

		GROUP BY V.ID
		ORDER BY 
			2 $UserOrder
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
";

$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$VisArr[]=$Row->ID;
	$VisTime[$Row->ID]=$Row->TOTAL_TIME;
}
if (count($VisArr)==0) $VisArr[]=-1;



$WhereArr[]="S_LOG.VISITOR_ID IN (".implode(", ", $VisArr).")";
$WhereStr=" WHERE ".implode(" AND ", $WhereArr);


$Query = "

	SELECT 
	S_LOG.ID,
	S_LOG.COOKIE_LOG,
	S_LOG.VISITOR_ID,
	$SelectStr
	I.IP AS LAST_IP, CV.NAME AS VISITOR_NAME,
	S_LOG.SITE_ID,
	S_LOG.REFERER_SET,
	S_LOG.SCHEME, 
	SP.PATH, SP.NAME,
	Q.QUERY_STRING AS QUERY,
	UNIX_TIMESTAMP(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) AS STAMP,
	TO_DAYS(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) AS DAYS,
	TS.HOST, TS.COMPANY_ID,
	R.REFERER,
	R.ID AS REFERER_ID,
	RS.HOST_ID,
	RS.NATURAL_KEY,
	SH.HOST AS SITE_HOST,
	
	UNIX_TIMESTAMP(DATE_ADD(V.FIRST_STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) AS FIRST_STAMP,

	S_CLICK.ID AS CLICK_ID,
	S_SPLIT.ID AS SPLIT_ID,
	S_SALE.ID AS ORDER_ID,
	S_ACTION.ID AS ACTION_ID,

	CO.ID AS COUNTRY_ID,
	CO.NAME AS COUNTRY_NAME
	
	FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_site_page SP
			ON SP.ID = S_LOG.PAGE_ID
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		LEFT JOIN ".PFX."_tracker_country CO
			ON CO.ID=V.FIRST_COUNTRY_ID
		INNER JOIN ".PFX."_tracker_site TS
			ON TS.ID=S_LOG.SITE_ID

		LEFT JOIN ".PFX."_tracker_ip I
			ON I.ID=V.LAST_IP_ID
		LEFT JOIN ".PFX."_tracker_client_visitor CV
			ON CV.VISITOR_ID=V.ID AND CV.COMPANY_ID=TS.COMPANY_ID
		LEFT JOIN ".PFX."_tracker_site_host SH
			ON SH.ID=S_LOG.SITE_HOST_ID
		LEFT JOIN ".PFX."_tracker_query Q
			ON Q.ID=S_LOG.QUERY_ID

		# referers
		LEFT JOIN ".PFX."_tracker_referer_set RS
			ON RS.ID=S_LOG.REFERER_SET
		LEFT JOIN ".PFX."_tracker_referer R
			ON R.ID=RS.REFERER_ID

		# actions
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION
			ON S_ACTION.LOG_ID=S_LOG.ID

		# orders 
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_sale S_SALE
			ON S_SALE.LOG_ID=S_LOG.ID

		# clicks
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
			ON S_CLICK.LOG_ID=S_LOG.ID

		#splits 
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_split S_SPLIT
			ON S_SPLIT.LOG_ID=S_LOG.ID

		# additional join
		$JoinStr

	$WhereStr

	ORDER BY 
		S_LOG.STAMP $UserOrder

";



$Sql = new Query($Query);
$VPath=array();
$PrevDays=0;
$PrevVis=0;
$AllowVisInfo=false;
while ($Row=$Sql->Row()) {
	$AllowVisInfo=true;
	$Row->ONLINE=($OnlineOnly)?true:false;

	$Row->NODE_START=false;
	if ($Row->COOKIE_LOG==$Row->ID) $Row->NODE_START=true;

	if ($Row->CLICK_ID>0) {
		$Query = "
			SELECT 
				CP.ID, CP.NAME,
				K.KEYWORD,
				H.HOST
				FROM ".PFX."_tracker_".$CpId."_stat_click S_CLICK
					INNER JOIN ".PFX."_tracker_camp_piece CP
						ON CP.ID=S_CLICK.CAMP_ID
					LEFT JOIN ".PFX."_tracker_keyword K
						ON K.ID=S_CLICK.KEYWORD_ID
					LEFT JOIN ".PFX."_tracker_host H
						ON H.ID=S_CLICK.SOURCE_HOST_ID
				WHERE S_CLICK.ID=".$Row->CLICK_ID."
		";
		$RowClick=$Db->Select($Query);
		if ($RowClick) {
			$Row->CAMP_KEYWORD=$RowClick->KEYWORD;
			$Row->CAMP_NAME=$RowClick->NAME;
			$Row->CAMP_HOST=$RowClick->HOST;
			$Row->CAMP_ID=$RowClick->ID;
			unset($RowClick);
		}
		else $Row->CLICK_ID=false;
	}

	if ($Row->SPLIT_ID>0) {
		$Query = "
			SELECT 
				CP.ID, CP.NAME
				FROM ".PFX."_tracker_".$CpId."_stat_split S_SPLIT
				INNER JOIN ".PFX."_tracker_camp_piece CP
					ON CP.ID=S_SPLIT.SPLIT_ID
				WHERE S_SPLIT.ID=".$Row->SPLIT_ID."
		";
		$RowSplit=$Db->Select($Query);
		if ($RowSplit) {
			$Row->SPLIT_NAME=$RowSplit->NAME;
			$Row->SPLIT_CAMP_ID=$RowSplit->ID;
			unset($RowSplit);
		}
		else $Row->SPLIT_ID=false;
	}

	$Row->ACTION=false;
	if ($Row->ACTION_ID>0) {
		$Query = "
			SELECT 
				VA.ID, 
				AI.ID AS ACTION_ITEM_ID,
				AI.NAME AS ACTION_ITEM, 
				VA.NAME AS ACTION
				FROM ".PFX."_tracker_".$CpId."_stat_action S_ACTION
				LEFT JOIN ".PFX."_tracker_visitor_action VA
					ON VA.ID=S_ACTION.ACTION_ID
				LEFT JOIN ".PFX."_tracker_action_set ACS
					ON ACS.STAT_ACTION_ID=S_ACTION.ID AND ACS.COMPANY_ID=$CpId
				LEFT JOIN ".PFX."_tracker_action_item AI
					ON AI.ID=ACS.ACTION_ITEM_ID AND AI.COMPANY_ID=$CpId
			WHERE S_ACTION.ID=".$Row->ACTION_ID."
		";
		$RowAction=$Db->Select($Query);
		if ($RowAction) {
			$Row->ACTION_ITEM=$RowAction->ACTION_ITEM;
			$Row->ACTION_ITEM_ID=$RowAction->ACTION_ITEM_ID;
			$Row->ACTION=$RowAction->ACTION;
			$Row->ACTION_ID=$RowAction->ID;
			unset($RowAction);
		}
		else $Row->ACTION_ID=false;
	}

	$Row->CUSTOM_ORDER_ID=false;
	if ($Row->ORDER_ID>0) {
		$Query = "
			SELECT 
				S_SALE.COST AS ORDER_COST,
				SI.NAME AS ITEM_NAME,
				S_SALE.CUSTOM_ORDER_ID
				FROM ".PFX."_tracker_".$CpId."_stat_sale S_SALE
				LEFT JOIN ".PFX."_tracker_sale_set SS
					ON SS.SALE_ID=S_SALE.ID AND SS.COMPANY_ID=$CpId
				LEFT JOIN ".PFX."_tracker_sale_item SI
					ON SI.ID=SS.ITEM_ID AND SI.COMPANY_ID=$CpId
				WHERE S_SALE.ID=".$Row->ORDER_ID."
		";
		$RowSale=$Db->Select($Query);
		if ($RowSale) {
			$Row->ORDER_COST=$RowSale->ORDER_COST;
			$Row->ITEM_NAME=$RowSale->ITEM_NAME;
			$Row->CUSTOM_ORDER_ID=$RowSale->CUSTOM_ORDER_ID;
			unset($RowSale);
		}
		else $Row->ORDER_ID=false;
	}

	if ($Row->QUERY) {
		$Row->QUERY=urldecode(urldecode($Row->QUERY));
		$Row->QUERY="?".$Row->QUERY;
	}

	$Row->HOST_GRP_ID=0;
	if ($Row->REFERER_SET>0&&$Row->HOST_ID>0) {
		$Query="
			SELECT HG.ID, HG.NAME
				FROM ".PFX."_tracker_host H 
				INNER JOIN ".PFX."_tracker_host_grp HG
					ON HG.ID=H.GRP_ID
			WHERE H.ID=".$Row->HOST_ID."
		";
		$HostGrp=$Db->Select($Query);
		if ($HostGrp) {
			$Row->HOST_GRP=$HostGrp->NAME;
			$Row->HOST_GRP_ID=$HostGrp->ID;
		}
		if (ValidId($Row->NATURAL_KEY)&&!ValidVar($Row->KEYWORD))  {
			$Query = "SELECT KEYWORD FROM ".PFX."_tracker_keyword WHERE ID = ".$Row->NATURAL_KEY;
			$Row->KEYWORD=$Db->ReturnValue($Query);
		}
	}

	if (!$Row->SITE_HOST) $Row->SITE_HOST=$Row->HOST;
	$Row->SCHEME=($Row->SCHEME)?"https://":"http://";
	$Row->LINK=$Row->SCHEME.$Row->SITE_HOST.$Row->PATH.$Row->QUERY;
	$Row->PATH=$Row->SCHEME.$Row->SITE_HOST.$Row->PATH;

	$Row->PATH=htmlspecialchars($Row->PATH);
	$Row->QUERY=htmlspecialchars($Row->QUERY);
	$Row->LAST_IP=htmlspecialchars($Row->LAST_IP);
	$Row->REFERER=htmlspecialchars($Row->REFERER);
	$Row->CUSTOM_ORDER_ID=htmlspecialchars(ValidVar($Row->CUSTOM_ORDER_ID));
	$Row->ACTION_ITEM=htmlspecialchars(ValidVar($Row->ACTION_ITEM));
	$Row->CAMP_KEYWORD=htmlspecialchars(ValidVar($Row->CAMP_KEYWORD));
	$Row->ITEM_NAME=htmlspecialchars(ValidVar($Row->ITEM_NAME));
	$Row->KEYWORD=htmlspecialchars(ValidVar($Row->KEYWORD));
	$Row->USER_AGENT=htmlspecialchars(ValidVar($Row->USER_AGENT));

	$Row->NewDay=false;
	$Row->NewVis=false;
	$Row->NewRef=false;
	$Row->TotalTime=false;
	$Row->Hours=false;
	$Row->Minutes=false;
	if ($Row->REFERER) $Row->NewRef=true;
	if ($PrevDays!=$Row->DAYS) $Row->NewDay=true;
	if (!isset($VPath[$Row->VISITOR_ID])) $Row->NewVis=true;
	$Row->Time=date("H:i:s", $Row->STAMP);
	$Row->Date=date("d.m", $Row->STAMP);
	$Row->Year=date("Y", $Row->STAMP);
	if (date("Y-m-d", $Row->STAMP)==date("Y-m-d", $Row->FIRST_STAMP)) $Row->FIRST_STAMP=false;
	if ($Row->FIRST_STAMP) $Row->FIRST_STAMP_NAME=LastStamp($Row->FIRST_STAMP);
	if ($Row->CUSTOM_ORDER_ID) $Row->ORDER_ID=$Row->CUSTOM_ORDER_ID;
	
	if ($Row->NewVis&&!$Filter&&!$VisId) {
		$Row->TotalTime=ValidVar($VisTime[$Row->VISITOR_ID]);
		if ($Row->TotalTime>0) {
			$Row->TotalTime=round($Row->TotalTime/60);
			if ($Row->TotalTime>=60) {
				$Row->Hours=round($Row->TotalTime/60);
				$Row->Minutes=round($Row->TotalTime%60);
			}
			else $Row->Minutes=$Row->TotalTime;
		}
		if ($Row->Minutes&&strlen($Row->Minutes)==1)$Row->Minutes="0".$Row->Minutes;
		if (!$Row->Minutes)$Row->Minutes="00"; 
		if ($Row->Hours&&strlen($Row->Hours)==1)$Row->Hours="0".$Row->Hours;
		if (!$Row->Hours)$Row->Hours="00";
	}
	
	$VPath[$Row->VISITOR_ID][]=$Row;
	if (!$OnlineOnly&&ceil(time()-$Row->STAMP)<$OnlineTime) $VPath[$Row->VISITOR_ID][0]->ONLINE=true;

	$PrevDays=$Row->DAYS;
	$PrevVis=$Row->VISITOR_ID;
}

if ($VisId&&!$Filter && isset($VPath[$VisId])) {
	for ($i=0;$i<count($VPath[$VisId]);$i++) {
		$Row=$VPath[$VisId][$i];
		if ($Row->NewDay) {
			$LastStamp=false;
			for ($j=$i+1;$j<count($VPath[$VisId]);$j++) {
				$SubRow=$VPath[$VisId][$j];
				if ($SubRow->NewDay) break;
				$LastStamp=$SubRow->STAMP;
			}
			if ($LastStamp) {
				$Row->TotalTime=$LastStamp-$Row->STAMP;
				if ($Row->TotalTime>0) {
					$Row->TotalTime=round($Row->TotalTime/60);
					if ($Row->TotalTime>=60) {
						$Row->Hours=round($Row->TotalTime/60);
						$Row->Minutes=round($Row->TotalTime%60);
					}
					else $Row->Minutes=$Row->TotalTime;
				}
				if ($Row->Minutes&&strlen($Row->Minutes)==1)$Row->Minutes="0".$Row->Minutes;
				if (!$Row->Minutes)$Row->Minutes="00"; 
				if ($Row->Hours&&strlen($Row->Hours)==1)$Row->Hours="0".$Row->Hours;
				if (!$Row->Hours)$Row->Hours="00";
				$VPath[$VisId][$i]=$Row;
			}
		}
	}
}

if (!ValidId($CpId)) {
	if (ValidVar($Site)) $CpId=$Site->COMPANY_ID;
	else $CpId=$CompId;
}
include $nsTemplate->Inc();


/////////////////////////////////////////////
///////// process functions here


/////////////////////////////////////////////
///////// library section

function LastStamp($Stamp) 
{
	global $Lang, $DATE_DIFF;
	$DATE_DIFF=ceil((time()-$Stamp)/60/60/24);
	if ($DATE_DIFF==1) $DATE_DIFF_NAME=$Lang['Yesterday'].", ".date("H:i", $Stamp);
	if ($DATE_DIFF>1) {
		$DATE_DIFF_NAME=date("j", $Stamp)." ".$Lang['MonthName'][date("n", $Stamp)]." ".date("Y",$Stamp);
		$DATE_DIFF_NAME.=" ".date("H:i", $Stamp);
	}
	return $DATE_DIFF_NAME;
}


function MoveRefererToGrp($RefId=false, $GrpId=false) {
	global $Db, $AName;
	if ($GrpId==-1) $GrpId=0;
	$Query = "UPDATE ".PFX."_tracker_host SET GRP_ID=$GrpId WHERE ID=$RefId";
	$Db->Query($Query);
	Redir(strip_certain_req_vars(array("MoveReferer", "MoveToGrp", "AName"))."#Vis$AName");
}


function MoveAgentToGrp($AgId=false, $GrpId=false) {
	global $Db, $AName;
	if ($GrpId==-1) $GrpId=0;
	$Query = "UPDATE ".PFX."_tracker_visitor_agent SET GRP_ID=$GrpId WHERE ID=$AgId";
	$Db->Query($Query);
	Redir(strip_certain_req_vars(array("MoveAgent", "MoveToGrp", "AName"))."#Vis$AName");
}

?>