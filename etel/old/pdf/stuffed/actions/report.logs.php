<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here
require_once SELF."/lib/company.func.php";
require_once SELF."/class/pagenums3.class.php";
require_once SYS."/system/lib/validate.func.php";
require_once SELF."/lib/delete.func.php";

$nsLang->TplInc("inc/menu");



/////////////////////////////////////////////
///////// prepare any variables
$SiteId= (ValidVar($_GP['SiteId']))?$_GP['SiteId']:false;
$CpId= (ValidVar($_GP['CpId']))?$_GP['CpId']:false;
$Mode= (ValidVar($_GP['Mode']))?$_GP['Mode']:"Hits";
$Filter= (ValidVar($_GP['Filter']))?trim($_GP['Filter']):false;
$FilterFor= (ValidVar($_GP['FilterFor']))?$_GP['FilterFor']:false;
$Limit= (ValidVar($_GP['Limit']))?intval($_GP['Limit']):50;
$Start= (ValidVar($_GP['Start']))?$_GP['Start']:0;
$ViewDate= (ValidDate($_GP['ViewDate']))?$_GP['ViewDate']:false;
$StartDate= (ValidDate($_GP['StartDate']))?$_GP['StartDate']:false;
$EndDate= (ValidDate($_GP['EndDate']))?$_GP['EndDate']:false;
$FormClicked= (ValidVar($_GP['FormClicked']))?true:false;
$DeleteId= (ValidVar($_GP['DeleteId']))?$_GP['DeleteId']:false;
$UserOrder=ValidVar($_GET['UserOrder'], false);

$UserOrder=PrepareUserOrder($nsUser->DEF_LOGS_ORDER, $UserOrder, $Mode);


$ProgPath=array();
$ProgPath[0]['Name']=$Lang['MLogs'];
$ProgPath[0]['Url']=getUrl("reports", "CpId=$CpId&SiteId=$SiteId", "admin");

$Settings=GetSettings();

$AllSites=!ValidId($SiteId);
$MenuSection="logs";

if (!$ViewDate&&!$StartDate&&!$EndDate&&!$FormClicked) $ViewDate=UserDate();
$StartDay=false;
$EndDay=false;

if ($FilterFor=="IP"&&!ValidIp($Filter)) {
	$Filter=false;
	$Logs->Err($Lang['IpErr']);
}

if ($Mode=="Hits") $PageTitle=$Lang['LogHits'];
if ($Mode=="Clicks") $PageTitle=$Lang['LogClicks'];
if ($Mode=="Actions") $PageTitle=$Lang['LogActions'];
if ($Mode=="Sales") $PageTitle=$Lang['LogSales'];
if ($Mode=="Split") $PageTitle=$Lang['LogSplits'];
if ($Mode=="Undef") $PageTitle=$Lang['LogUndef'];
if ($Mode=="Ref") $PageTitle=$Lang['LogRef'];
if ($Mode=="Key") $PageTitle=$Lang['LogKeys'];
if ($Mode=="Fraud") $PageTitle=$Lang['ClickFraud'];
$ProgPath[1]['Name']=$PageTitle;
$ProgPath[1]['Url']=$nsProduct->SelfAction("Mode=$Mode&CpId=$CpId&SiteId=$SiteId");

$Get="&CpId=$CpId&SiteId=$SiteId&FormClicked=1&ViewDate=$ViewDate&StartDate=$StartDate&EndDate=$EndDate&";

$SitesArr=array();
$SitesIds=array();
$IdsStr="";
$Query = "SELECT * FROM ".PFX."_tracker_site WHERE COMPANY_ID=$CpId";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$SitesArr[]=$Row;
	$SitesIds[]=$Row->ID;
}
if ($CurrentCompany->SITE_CNT==0) $SitesIds[]=-1;
if (!ValidId($SiteId)) $IdsStr=implode(",",$SitesIds);
else $IdsStr=$SiteId;


if (!$CpId) $CpId=$nsSession->get('CpId');
if (!ValidId($CpId)) $nsProduct->Redir("default", "", "admin");
$Client=GetCompany($CpId);
if (!ValidId($Client->ID)) $nsProduct->Redir("default", "", "admin");

$FldNames=array();
$FilterNames=array();
$LogArr=array();

/////////////////////////////////////////////
///////// call any process functions

if (!$nsUser->DEMO) {
	if ($Mode=="Sales"&&$DeleteId&&($nsUser->ADMIN||$nsUser->SUPER_USER)) DeleteOneSale($CpId, $DeleteId);
	if ($Mode=="Actions"&&$DeleteId&&($nsUser->ADMIN||$nsUser->SUPER_USER)) DeleteOneAction($CpId, $DeleteId);
	if ($Mode=="Clicks"&&$DeleteId&&($nsUser->ADMIN||$nsUser->SUPER_USER)) DeleteOneClick($CpId, $DeleteId);
	if ($Mode=="Split"&&$DeleteId&&($nsUser->ADMIN||$nsUser->SUPER_USER)) DeleteOneSplit($CpId, $DeleteId);
}

/////////////////////////////////////////////
///////// display section here

if (ValidVar($ViewDate)) {
	$StartDay=$ViewDate." 00:00:00";
	$EndDay=$Db->ReturnValue("SELECT DATE_ADD('$ViewDate', INTERVAL 1 DAY)")." 00:00:00";
}
if (ValidVar($StartDate)) $StartDay=$StartDate." 00:00:00";
if (ValidVar($EndDate)) $EndDay=$EndDate." 23:59:59";
$WhereStr="";
$WhereArr=array();
if ($StartDay&&!$EndDay) $WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) >= '$StartDay'";
if (!$StartDay&&$EndDay) $WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) <= '$EndDay'";
if ($StartDay&&$EndDay) $WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) BETWEEN '$StartDay' AND '$EndDay'";

$FilterArr=array();
$JoinArr=array();
if (ValidVar($Filter)) {
	if ($FilterFor=="IP") {
		$IpId=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_ip WHERE IP='$Filter'");
		$FilterArr[]="V.LAST_IP_ID='$IpId'";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
	}
	if ($FilterFor=="HIT_PATH") {
		$FilterArr[]="(SP.PATH LIKE '%$Filter%' OR Q.QUERY_STRING LIKE '%$Filter%' OR CONCAT(SP.PATH, '?', Q.QUERY_STRING) LIKE '%$Filter%')";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_site_page SP ON SP.ID=S_LOG.PAGE_ID";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_query Q ON Q.ID=S_LOG.QUERY_ID";
	}
	if ($FilterFor=="HIT_REF") {
		$FilterArr[]="R.REFERER LIKE '%$Filter%'";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_referer_set RS ON RS.ID=S_LOG.REFERER_SET";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_referer R ON R.ID=RS.REFERER_ID";
	}
	if ($FilterFor=="CLICK_CAMP_NAME") {
		$FilterArr[]="CP.NAME LIKE '%$Filter%'";
		$JoinArr[]="	INNER  JOIN ".PFX."_tracker_camp_piece CP	ON CP.ID=S_CLICK.CAMP_ID";
	}
	if ($FilterFor=="CLICK_HOST") {
		$FilterArr[]="H.HOST LIKE '%$Filter%'";
		$JoinArr[]="INNER 	JOIN ".PFX."_tracker_host H ON H.ID=S_CLICK.SOURCE_HOST_ID";
	}
	if ($FilterFor=="CLICK_KEYWORD") {
		$FilterArr[]="K.KEYWORD LIKE '%$Filter%'";
		$JoinArr[]="INNER 	JOIN ".PFX."_tracker_keyword K ON K.ID=S_CLICK.KEYWORD_ID";
	}
	if ($FilterFor=="ACTION_NAME") {
		$FilterArr[]="VA.NAME LIKE '%$Filter%'";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_action VA ON VA.ID=S_ACTION.ACTION_ID";
	}
	if ($FilterFor=="ACTION_ITEM") {
		$FilterArr[]="AI.NAME LIKE '%$Filter%'";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_action VA ON VA.ID=S_ACTION.ACTION_ID";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_action_set TAS ON TAS.STAT_ACTION_ID=S_ACTION.ACTION_ID AND TAS.COMPANY_ID=$CpId";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_action_item AI ON AI.ID=TAS.ACTION_ITEM_ID AND AI.COMPANY_ID=$CpId";
	}
	if ($FilterFor=="SALE_NAME") {
		$FilterArr[]="SI.NAME LIKE '%$Filter%'";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_sale_set SS ON SS.SALE_ID=S_SALE.ID";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_sale_item SI ON SI.ID=SS.ITEM_ID";
	}
	if ($FilterFor=="SALE_CUSTOM") {
		$FilterArr[]="S_SALE.CUSTOM_ORDER_ID LIKE '%$Filter%'";
	}
	if ($FilterFor=="SPLIT_PATH") {
		$FilterArr[]="SP.PATH LIKE '%$Filter%'";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_site_page SP ON SP.ID=S_LOG.PAGE_ID";
	}
	if ($FilterFor=="SPLIT_NAME") {
		$FilterArr[]="CP.NAME LIKE '%$Filter%'";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_camp_piece CP ON CP.ID=S_SPLIT.SPLIT_ID";
	}
	if ($FilterFor=="UNDEF_ADDRESS") {
		$FilterArr[]="S_UNDEF.ADDRESS LIKE '%$Filter%'";
	}
	if ($FilterFor=="REF_REFERER") {
		$JoinArr[]="INNER JOIN ".PFX."_tracker_referer_set RS ON RS.ID=S_LOG.REFERER_SET";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_referer R ON R.ID=RS.REFERER_ID";
		$FilterArr[]="R.REFERER LIKE '%$Filter%'";
	}
	if ($FilterFor=="KEY_WORD") {
		$FilterArr[]="K.KEYWORD LIKE '%$Filter%'";
		$JoinArr[]="INNER JOIN ".PFX."_tracker_keyword K ON K.ID=RS.NATURAL_KEY";
	}
}

$WhereArr[]="S_LOG.SITE_ID IN ($IdsStr)";

$FilterStr="";
$JoinStr="";
if(count($WhereArr)>0) $WhereStr="WHERE ".implode(" AND ", $WhereArr);
if(count($JoinArr)>0) $JoinStr=implode(" ", $JoinArr);
if(count($FilterArr)>0) {
	$FilterStr=implode(" AND ", $FilterArr);
	if ($WhereStr) $FilterStr = " AND ".$FilterStr;
}
$RecCount=0;



/////////////////////////////////////////////////////////////////////////
if ($Mode=="Hits") {
	$CntQuery = "
		SELECT COUNT(S_LOG.ID) 
			FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		$JoinStr
		$WhereStr
		$FilterStr
	";
	$RecCount=$Db->ReturnValue($CntQuery);
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT 
			S_LOG.ID, 
			DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) AS STAMP,
			DATE_FORMAT(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '%Y-%m-%d') AS DATE,
			S_LOG.VISITOR_ID,
			S_LOG.SITE_ID,
			I.IP AS LAST_IP,
			S.HOST AS SITE,
			SP.PATH,
			Q.QUERY_STRING,
			R.REFERER,
			SH.HOST AS SITE_HOST,
			CV.NAME AS VISITOR_NAME

			FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG

			INNER JOIN ".PFX."_tracker_site S
					ON S.ID=S_LOG.SITE_ID
			INNER JOIN ".PFX."_tracker_visitor V
					ON V.ID=S_LOG.VISITOR_ID
			INNER JOIN ".PFX."_tracker_site_page SP
					ON SP.ID=S_LOG.PAGE_ID
			LEFT JOIN ".PFX."_tracker_client_visitor CV 
					ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
				LEFT JOIN ".PFX."_tracker_ip I
					ON I.ID=S_LOG.IP_ID
				LEFT JOIN ".PFX."_tracker_site_host SH
					ON SH.ID=S_LOG.SITE_HOST_ID
				LEFT JOIN ".PFX."_tracker_referer_set RS
					ON RS.ID=S_LOG.REFERER_SET
				LEFT JOIN ".PFX."_tracker_query Q
					ON Q.ID=S_LOG.QUERY_ID
				LEFT JOIN ".PFX."_tracker_referer R
					ON R.ID=RS.REFERER_ID
		$WhereStr
		$FilterStr
		ORDER BY S_LOG.STAMP $UserOrder
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		if ($Row['SITE_HOST']) $Row['SITE']=$Row['SITE_HOST'];
		$Row['PATH']=$Row['SITE'].$Row['PATH'];
		if ($Row['QUERY_STRING']) $Row['PATH']=$Row['PATH']."?".$Row['QUERY_STRING'];
		$Row['PATH1']=RefCut(htmlspecialchars($Row['PATH']), 50);
		$Row['PATH1_title']=$Row['PATH'];
		$Row['PATH1_link']="http://".$Row['PATH'];
		$Row['REF']=RefCut(htmlspecialchars(urldecode($Row['REFERER'])));
		if (!$Row['REF']) $Row['REF']="&nbsp;";
		else $Row['REF_title']=htmlspecialchars(urldecode($Row['REFERER']));
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";
		
		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");
		$Row['STAMP_link']=getURL("visitor_path", "&SiteId=".$Row['SITE_ID']."&ViewDate=".$Row['DATE'], "report");
		$Row['PATH']=urldecode($Row['PATH']);
		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Time'];
	$FldNames['LAST_IP']=$Lang['IP'];
	$FldNames['PATH1']=$Lang['Page'];
	$FldNames['REF']=$Lang['Ref'];

	$FilterNames['HIT_PATH']=$Lang['ByPage'];
	$FilterNames['HIT_REF']=$Lang['ByRef'];
}

/////////////////////////////////////////////////////////////////////////
if ($Mode=="Clicks") {
	$CntQuery = "
		SELECT COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG 
		#FORCE INDEX (DBL_1)
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
		ON S_CLICK.LOG_ID=S_LOG.ID
		$JoinStr
		$WhereStr
		$FilterStr
	";
	$RecCount=$Db->ReturnValue($CntQuery);
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT 
		S_LOG.ID,
		DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) AS STAMP,
		S_LOG.VISITOR_ID,
		S_LOG.SITE_ID,
		I.IP AS LAST_IP,
		S.HOST AS SITE,
		CP.NAME AS CAMP_NAME,
		H.HOST,
		K.KEYWORD,
		SH.HOST AS SITE_HOST,
		SP.PATH AS LPAGE,
		S_CLICK.ID AS DELETE_ID,
		CV.NAME AS VISITOR_NAME

		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG 

		INNER JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
			ON S_CLICK.LOG_ID=S_LOG.ID
		INNER JOIN ".PFX."_tracker_site S
			ON S.ID=S_LOG.SITE_ID
		INNER JOIN ".PFX."_tracker_site_page SP
			ON SP.ID=S_LOG.PAGE_ID
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		INNER JOIN ".PFX."_tracker_camp_piece CP
			ON CP.ID=S_CLICK.CAMP_ID
		LEFT JOIN ".PFX."_tracker_client_visitor CV 
			ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
		LEFT JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
		LEFT JOIN ".PFX."_tracker_site_host SH
			ON SH.ID=S_LOG.SITE_HOST_ID
		LEFT JOIN ".PFX."_tracker_host H
			ON H.ID=S_CLICK.SOURCE_HOST_ID
		LEFT JOIN ".PFX."_tracker_keyword K
			ON K.ID=S_CLICK.KEYWORD_ID

		$WhereStr
		$FilterStr
		ORDER BY S_LOG.STAMP $UserOrder		
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";
		$Row['LPAGE']=htmlspecialchars($Row['LPAGE']);
		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		if (!$Row['HOST']) $Row['HOST']="&nbsp;";
		$Row['KEYWORD']=htmlspecialchars($Row['KEYWORD']);
		if (!$Row['KEYWORD']) $Row['KEYWORD']="&nbsp;";
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");
		if (!$SiteId) $Row['LPAGE']=$Row['SITE_HOST'].$Row['LPAGE'];
		$Row['DELETE']="";

		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Time'];
	$FldNames['LAST_IP']=$Lang['IP'];
	$FldNames['LPAGE']=$Lang['LandingPage'];
	$FldNames['CAMP_NAME']=$Lang['Camp'];
	$FldNames['HOST']=$Lang['Source'];
	$FldNames['KEYWORD']=$Lang['Key'];
	if($nsUser->ADMIN||$nsUser->SUPER_USER)$FldNames['DELETE']=$Lang['Delete'];

	$FilterNames['CLICK_CAMP_NAME']=$Lang['ByCampName'];
	$FilterNames['CLICK_HOST']=$Lang['BySource'];
	$FilterNames['CLICK_KEYWORD']=$Lang['ByKey'];
}


/////////////////////////////////////////////////////////////////////////
///// CLICK FRAUD

if ($Mode=="Fraud") {
	$CntQuery = "
		SELECT
		COUNT(DISTINCT S_LOG.VISITOR_ID) AS CNT
		
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
			ON S_CLICK.LOG_ID = S_LOG.ID
		$JoinStr		
		$WhereStr
			AND S_CLICK.FRAUD='1'
		$FilterStr
		GROUP BY S_LOG.VISITOR_ID
		
	";
	$RecCount=$Db->Select($CntQuery);
	$RecCount=$RecCount->CNT;
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT
		
		S_LOG.ID,
		S_LOG.VISITOR_ID,
		I.IP AS LAST_IP,
		DATE_FORMAT(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '%Y-%m-%d') AS STAMP,
		S_LOG.SITE_ID,
		COUNT(S_LOG.ID) AS FRAUD,
		CV.NAME AS VISITOR_NAME
		
		
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
		      ON S_CLICK.LOG_ID = S_LOG.ID
		INNER JOIN ".PFX."_tracker_camp_piece CP
		      ON CP.ID=S_CLICK.CAMP_ID
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		INNER JOIN ".PFX."_tracker_ip I 
			ON I.ID=V.LAST_IP_ID
		LEFT JOIN ".PFX."_tracker_client_visitor CV 
			ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
		$WhereStr
			AND S_CLICK.FRAUD='1'
		$FilterStr
		
		GROUP BY S_LOG.VISITOR_ID
		
		ORDER BY S_LOG.STAMP $UserOrder		
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		$Row['CAMP_NAME']="";
		$Row['TOTAL_CLICK']=0;
		$Query = "
			SELECT
			DISTINCT CP2.ID, CP2.NAME
			FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
			INNER JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
				ON S_CLICK.LOG_ID = S_LOG.ID
			INNER JOIN ".PFX."_tracker_camp_piece CP2
				ON CP2.ID=S_CLICK.CAMP_ID
			$JoinStr		
			$WhereStr
				AND S_CLICK.FRAUD='1'
				AND S_LOG.VISITOR_ID=".$Row['VISITOR_ID']."
			$FilterStr
		";
		$SubSql=new Query($Query);
		while ($SubRow=$SubSql->Row()) {
			if ($SubSql->Position>0) $Row['CAMP_NAME'].=", ";
			$Row['CAMP_NAME'].=$SubRow->NAME;
		}
		$Query = "
			SELECT
			COUNT(S_LOG.ID) AS CNT
			
			FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
			INNER JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
				ON S_CLICK.LOG_ID = S_LOG.ID
			$JoinStr		
			$WhereStr
				AND S_LOG.VISITOR_ID=".$Row['VISITOR_ID']."
			$FilterStr
		";
		$Row['TOTAL_CLICK']=$Db->ReturnValue($Query);
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";
		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");
		$Row['STAMP_link']=getURL("visitor_path", "&SiteId=".$Row['SITE_ID']."&ViewDate=".$Row['STAMP'], "report");
		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Date'];
	$FldNames['LAST_IP']=$Lang['IP'];
	$FldNames['CAMP_NAME']=$Lang['Camp'];
	$FldNames['FRAUD']=$Lang['FraudCount'];
	$FldNames['TOTAL_CLICK']=$Lang['TotalClicks'];
	$FilterNames['CLICK_CAMP_NAME']=$Lang['ByCampName'];
}




/////////////////////////////////////////////////////////////////////////
if ($Mode=="Actions") {
	$CntQuery = "
		SELECT COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG 
		#FORCE INDEX (DBL_1)
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION
		ON S_ACTION.LOG_ID=S_LOG.ID
		$JoinStr
		$WhereStr
		$FilterStr
	";
	$RecCount=$Db->ReturnValue($CntQuery);
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT 
		S_LOG.ID,
		DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) AS STAMP,
		S_LOG.VISITOR_ID,
		S_LOG.SITE_ID,
		I.IP AS LAST_IP,
		S.HOST AS SITE,
		VA.NAME,
		AI.NAME AS ACTION_ITEM,
		SH.HOST AS SITE_HOST,
		S_ACTION.ID AS DELETE_ID,
		SP.PATH AS LPAGE,
		CV.NAME AS VISITOR_NAME

		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG 
		
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION
			ON S_ACTION.LOG_ID=S_LOG.ID
		INNER JOIN ".PFX."_tracker_site S
			ON S.ID=S_LOG.SITE_ID
		INNER JOIN ".PFX."_tracker_site_page SP
			ON SP.ID=S_LOG.PAGE_ID
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		INNER JOIN ".PFX."_tracker_visitor_action VA
			ON VA.ID=S_ACTION.ACTION_ID
		LEFT JOIN ".PFX."_tracker_client_visitor CV 
			ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
		LEFT JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
		LEFT JOIN ".PFX."_tracker_action_set ACS
			ON ACS.STAT_ACTION_ID=S_ACTION.ID AND ACS.COMPANY_ID=$CpId
		LEFT JOIN ".PFX."_tracker_action_item AI
			ON AI.ID=ACS.ACTION_ITEM_ID AND AI.COMPANY_ID=$CpId
		LEFT JOIN ".PFX."_tracker_site_host SH
			ON SH.ID=S_LOG.SITE_HOST_ID

		$WhereStr
		$FilterStr
		ORDER BY S_LOG.STAMP $UserOrder		
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";
		$Row['LPAGE']=htmlspecialchars($Row['LPAGE']);
		$Row['ACTION_ITEM']=htmlspecialchars($Row['ACTION_ITEM']);
		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");
		if (ValidVar($Row['ACTION_ITEM'])) {
			$Row['NAME']="<nobr>".$Row['NAME']." : </nobr><nobr>".stripslashes($Row['ACTION_ITEM'])."</nobr>";
		}
		if (!$SiteId) $Row['LPAGE']=$Row['SITE_HOST'].$Row['LPAGE'];
		$Row['DELETE']="";
		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Time'];
	$FldNames['LAST_IP']=$Lang['IP'];
	$FldNames['LPAGE']=$Lang['JustPage'];
	$FldNames['NAME']=$Lang['Action'];
	if($nsUser->ADMIN||$nsUser->SUPER_USER)$FldNames['DELETE']=$Lang['Delete'];
	$FilterNames['ACTION_NAME']=$Lang['ByActionName'];
	$FilterNames['ACTION_ITEM']=$Lang['ByActionTarget'];

}


/////////////////////////////////////////////////////////////////////////
if ($Mode=="Sales") {
	$CntQuery = "
		SELECT COUNT(S_LOG.ID)
			FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
			INNER JOIN ".PFX."_tracker_".$CpId."_stat_sale S_SALE
			ON S_SALE.LOG_ID=S_LOG.ID
			$JoinStr
			$WhereStr
			$FilterStr
	";
	$RecCount=$Db->ReturnValue($CntQuery);
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT 
			S_LOG.ID,
			DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) AS STAMP,
			S_LOG.VISITOR_ID,
			S_LOG.SITE_ID,
			I.IP AS LAST_IP,
			S.HOST AS SITE,
			S_SALE.COST AS SALE_COST,
			SS.QUANT,
			SS.COST,
			SI.NAME,
			SH.HOST AS SITE_HOST,
			S_SALE.ID AS DELETE_ID,
			S_SALE.CUSTOM_ORDER_ID,
			S_SALE.ADDITIONAL,
			CV.NAME AS VISITOR_NAME

			FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG

			INNER JOIN ".PFX."_tracker_".$CpId."_stat_sale S_SALE
			ON S_SALE.LOG_ID=S_LOG.ID
			INNER JOIN ".PFX."_tracker_site S
			ON S.ID=S_LOG.SITE_ID
			INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
			LEFT JOIN ".PFX."_tracker_client_visitor CV 
				ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
			LEFT JOIN ".PFX."_tracker_ip I
				ON I.ID=S_LOG.IP_ID
			LEFT JOIN ".PFX."_tracker_sale_set SS
				ON SS.SALE_ID=S_SALE.ID AND SS.COMPANY_ID=$CpId
			LEFT JOIN ".PFX."_tracker_sale_item SI
				ON SI.ID=SS.ITEM_ID AND SI.COMPANY_ID=$CpId
			LEFT JOIN ".PFX."_tracker_site_host SH
				ON SH.ID=S_LOG.SITE_HOST_ID
			$WhereStr
			$FilterStr
		ORDER BY S_LOG.STAMP $UserOrder		
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";
		$Row['CUSTOM_ORDER_ID']=htmlspecialchars($Row['CUSTOM_ORDER_ID']);
		$Row['NAME']=htmlspecialchars($Row['NAME']);
		$Row['ADDITIONAL']=htmlspecialchars($Row['ADDITIONAL']);

		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		if (!$Row['NAME']) $Row['NAME']="&nbsp;";
		if (!$Row['QUANT']) $Row['QUANT']="&nbsp;";
		if (!$Row['COST']) $Row['COST']=$Row['SALE_COST'];
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");
		//if ($Row['SITE_HOST']) $Row['SITE']=$Row['SITE_HOST'];
		$Row['COST']=ShowCost($Row['COST']);
		$Row['DELETE']="";
		$Row['CUSTOM']=$Row['CUSTOM_ORDER_ID'];

		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Time'];
	$FldNames['LAST_IP']=$Lang['IP'];
	$FldNames['SITE']=$Lang['Site'];
	$FldNames['CUSTOM']=$Lang['CustomId'];
	$FldNames['NAME']=$Lang['Item'];
	$FldNames['QUANT']=$Lang['Quant'];
	$FldNames['COST']=$Lang['Cost'];
	$FldNames['ADDITIONAL']=$Lang['AddInfo'];	
	if($nsUser->ADMIN||$nsUser->SUPER_USER)$FldNames['DELETE']=$Lang['Delete'];
	$FilterNames['SALE_NAME']=$Lang['ByItem'];
	$FilterNames['SALE_CUSTOM']=$Lang['ByCustomId'];

}

/////////////////////////////////////////////////////////////////////////
if ($Mode=="Split") {
	$CntQuery = "
		SELECT COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_split S_SPLIT
		ON S_SPLIT.LOG_ID=S_LOG.ID
		$JoinStr
		$WhereStr
		$FilterStr
	";
	$RecCount=$Db->ReturnValue($CntQuery);
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT
		S_LOG.ID,
		DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) AS STAMP,
		S_LOG.VISITOR_ID,
		S_LOG.SITE_ID,
		I.IP AS LAST_IP,
		S.HOST AS SITE,
		S_SPLIT.SPLIT_ID,
		S_LOG.PAGE_ID,
		CP.NAME,
		SP.PATH,
		Q.QUERY_STRING,
		SH.HOST AS SITE_HOST,
		S_SPLIT.ID AS DELETE_ID,
		CV.NAME AS VISITOR_NAME

		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG

		INNER JOIN ".PFX."_tracker_".$CpId."_stat_split S_SPLIT
			ON S_SPLIT.LOG_ID=S_LOG.ID

		INNER JOIN ".PFX."_tracker_camp_piece CP
			ON CP.ID=S_SPLIT.SPLIT_ID
		INNER JOIN ".PFX."_tracker_site_page SP
			ON SP.ID=S_LOG.PAGE_ID
		INNER JOIN ".PFX."_tracker_split_test ST
			ON ST.SUB_ID=S_SPLIT.SPLIT_ID
		INNER JOIN ".PFX."_tracker_split_page SPP
			ON SPP.SPLIT_ID=ST.ID AND SPP.PAGE_ID=S_LOG.PAGE_ID

		INNER JOIN ".PFX."_tracker_site S
			ON S.ID=S_LOG.SITE_ID
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		LEFT JOIN ".PFX."_tracker_client_visitor CV 
			ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
		LEFT JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
		LEFT JOIN ".PFX."_tracker_query Q
			ON Q.ID=SPP.QUERY_ID
		LEFT JOIN ".PFX."_tracker_site_host SH
			ON SH.ID=S_LOG.SITE_HOST_ID
		$WhereStr
		$FilterStr
		ORDER BY S_LOG.STAMP $UserOrder		
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";
		if ($Row['SITE_HOST']) $Row['SITE']=$Row['SITE_HOST'];
		if (!$SiteId) $Row['PATH']=$Row['SITE'].$Row['PATH'];
		if ($Row['QUERY_STRING']) $Row['PATH']=$Row['PATH']."?".$Row['QUERY_STRING'];
		$Row['PATH1']=RefCut(htmlspecialchars($Row['PATH']), 50);
		$Row['PATH1_title']=$Row['PATH'];
		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");
		$Row['DELETE']="";

		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Time'];
	$FldNames['LAST_IP']=$Lang['IP'];
	$FldNames['PATH1']=$Lang['LandingPage'];
	$FldNames['NAME']=$Lang['SplitName'];
	if($nsUser->ADMIN||$nsUser->SUPER_USER)$FldNames['DELETE']=$Lang['Delete'];

	$FilterNames['SPLIT_PATH']=$Lang['ByPage'];
	$FilterNames['SPLIT_NAME']=$Lang['BySplitName'];
}


/////////////////////////////////////////////////////////////////////////
if ($Mode=="Ref") {
	$CntQuery = "
		SELECT COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		$JoinStr
		$WhereStr
		AND S_LOG.REFERER_SET>0
		$FilterStr
	";
	$RecCount=$Db->ReturnValue($CntQuery);
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT 
		S_LOG.ID,
		DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) AS STAMP,
		S_LOG.VISITOR_ID,
		S_LOG.SITE_ID,
		I.IP AS LAST_IP,
		S.HOST AS SITE,
		R.REFERER,
		SH.HOST AS SITE_HOST,
		SP.PATH AS LPAGE,
		CV.NAME AS VISITOR_NAME

		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG

		INNER JOIN ".PFX."_tracker_referer_set RS
			ON RS.ID=S_LOG.REFERER_SET
		INNER JOIN ".PFX."_tracker_referer R
			ON R.ID=RS.REFERER_ID
		INNER JOIN ".PFX."_tracker_site_page SP
			ON SP.ID=S_LOG.PAGE_ID
		INNER JOIN ".PFX."_tracker_site S
			ON S.ID=S_LOG.SITE_ID
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		LEFT JOIN ".PFX."_tracker_client_visitor CV 
			ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
		LEFT JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
		LEFT JOIN ".PFX."_tracker_site_host SH
			ON SH.ID=S_LOG.SITE_HOST_ID
		$WhereStr
		$FilterStr
		ORDER BY S_LOG.STAMP $UserOrder		
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		$Row['REF']=htmlspecialchars(urldecode($Row['REFERER']));
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";
		$Row['LPAGE']=htmlspecialchars($Row['LPAGE']);

		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");
		if (!$SiteId) $Row['LPAGE']=$Row['SITE_HOST'].$Row['LPAGE'];

		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Time'];
	$FldNames['LAST_IP']=$Lang['IP'];
	$FldNames['LPAGE']=$Lang['Page'];
	$FldNames['REF']=$Lang['Ref'];

	$FilterNames['REF_REFERER']=$Lang['ByRef'];

}

/////////////////////////////////////////////////////////////////////////
if ($Mode=="Key") {
	$CntQuery = "
		SELECT COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		$JoinStr
		INNER JOIN ".PFX."_tracker_referer_set RS
			ON RS.ID=S_LOG.REFERER_SET
		$WhereStr
		AND S_LOG.REFERER_SET>0
		AND RS.NATURAL_KEY>0
		$FilterStr
	";

	$RecCount=$Db->ReturnValue($CntQuery);
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT 
		S_LOG.ID,
		DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) AS STAMP,
		S_LOG.VISITOR_ID,
		S_LOG.SITE_ID,
		I.IP AS LAST_IP,
		S.HOST AS SITE,
		K.KEYWORD,
		H.HOST AS REF_DOMAIN,
		SP.PATH AS LPAGE,
		Q.QUERY_STRING,
		SH.HOST AS SITE_HOST,
		CV.NAME AS VISITOR_NAME

		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG

		INNER JOIN ".PFX."_tracker_referer_set RS
			ON RS.ID=S_LOG.REFERER_SET
		INNER JOIN ".PFX."_tracker_host H
			ON H.ID=RS.HOST_ID
		INNER JOIN ".PFX."_tracker_site S
			ON S.ID=S_LOG.SITE_ID
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=S_LOG.VISITOR_ID
		INNER JOIN ".PFX."_tracker_keyword K
			ON K.ID=RS.NATURAL_KEY
		INNER JOIN ".PFX."_tracker_site_page SP
			ON SP.ID=S_LOG.PAGE_ID
		LEFT JOIN ".PFX."_tracker_client_visitor CV 
			ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
		LEFT JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
		LEFT JOIN ".PFX."_tracker_query Q
			ON Q.ID=S_LOG.QUERY_ID
		LEFT JOIN ".PFX."_tracker_site_host SH
			ON SH.ID=S_LOG.SITE_HOST_ID
		$WhereStr
		$FilterStr
		ORDER BY S_LOG.STAMP $UserOrder		
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		if (!$SiteId) $Row['LPAGE']=$Row['SITE_HOST'].$Row['LPAGE'];
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";
		$Row['KEYWORD']=htmlspecialchars($Row['KEYWORD']);

		if ($Row['QUERY_STRING']) $Row['LPAGE']=$Row['LPAGE']."?".$Row['QUERY_STRING'];
		$Row['PATH1']=RefCut(htmlspecialchars($Row['LPAGE']), 50);
		$Row['PATH1_title']=$Row['LPAGE'];
		$Row['LPAGE']=urldecode($Row['LPAGE']);

		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");

		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Time'];
	$FldNames['LAST_IP']=$Lang['IP'];
	$FldNames['PATH1']=$Lang['Page'];
	$FldNames['KEYWORD']=$Lang['Key'];
	$FldNames['REF_DOMAIN']=$Lang['RefDomain'];
	$FilterNames['KEY_WORD']=$Lang['ByKey'];

}


/////////////////////////////////////////////////////////////////////////
if ($Mode=="Undef") {
	$CntQuery = "
		SELECT COUNT(S_LOG.ID)
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_undef S_UNDEF
		ON S_UNDEF.LOG_ID=S_LOG.ID
		$JoinStr
		$WhereStr
		$FilterStr
	";
	$RecCount=$Db->ReturnValue($CntQuery);
	$Pages=new PageNums($RecCount,$Limit);
	$Pages->Calculate();

	$Query = "
		SELECT 
		S_LOG.ID,
		DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) AS STAMP,
		S_LOG.VISITOR_ID,
		S_LOG.SITE_ID,
		I.IP AS LAST_IP,
		S.HOST AS SITE,
		S_UNDEF.ADDRESS,
		CV.NAME AS VISITOR_NAME

		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG

		INNER JOIN ".PFX."_tracker_".$CpId."_stat_undef S_UNDEF
		ON S_UNDEF.LOG_ID=S_LOG.ID

		INNER JOIN ".PFX."_tracker_site S
		ON S.ID=S_LOG.SITE_ID
		INNER JOIN ".PFX."_tracker_visitor V
		ON V.ID=S_LOG.VISITOR_ID
		LEFT JOIN ".PFX."_tracker_client_visitor CV 
			ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId
		LEFT JOIN ".PFX."_tracker_ip I
			ON I.ID=S_LOG.IP_ID
		$WhereStr
		$FilterStr
		ORDER BY S_LOG.STAMP $UserOrder		
		LIMIT ".$Pages->PageStart.", ".$Pages->Limit."
	";
	$Sql = new Query($Query, "ARR");
	while ($Row=$Sql->Row()) {
		$Row['ADDRESS']=htmlspecialchars(urldecode($Row['ADDRESS']));
		$Row['LAST_IP']=htmlspecialchars($Row['LAST_IP']);
		$Row['VISITOR_NAME']=htmlspecialchars(stripslashes($Row['VISITOR_NAME']));
		if ($Row['VISITOR_NAME']) $Row['LAST_IP'] =$Row['VISITOR_NAME']." (".$Row['LAST_IP'].")";

		if (!$Row['LAST_IP']) $Row['LAST_IP']="^";
		$Row['LAST_IP_title']=$Lang['VisPaths'];
		$Row['STAMP_title']=$Lang['PathsForDay'];
		$Row['LAST_IP_link']=getURL("visitor_path", "AllSites=$AllSites&VisId=".$Row['VISITOR_ID']."&SiteId=".$Row['SITE_ID']."&ViewDate=$ViewDate", "report");
		$LogArr[]=$Row;
	}
	$FldNames['STAMP']=$Lang['Time'];
	$FldNames['LAST_IP']="IP";
	if (!ValidId($SiteId)) $FldNames['SITE']=$Lang['Site'];
	$FldNames['ADDRESS']=$Lang['Page'];

	$FilterNames['UNDEF_ADDRESS']=$Lang['ByPage'];

}

$FilterNames['IP']=$Lang['ByIp'];

$VisLogArr=array();
if (count($LogArr)>0) {
	for($i=0;$i<count($LogArr);$i++) {
		$VisLogArr[$LogArr[$i]['VISITOR_ID']][]=$LogArr[$i]['ID'];
	}
}

$Pages->Args=$Get."ViewDate=$ViewDate&StartDate=$StartDate&EndDate=$EndDate&Mode=$Mode&Filter=$Filter&FilterFor=$FilterFor&Limit=$Limit&UserOrder=$UserOrder";


include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here



/////////////////////////////////////////////
///////// library section

function RefCut($Ref, $MaxLen=45) {

	$Len=strlen($Ref);
	if ($Len>$MaxLen) {
		$Ref=substr_replace($Ref, "...", 30, $Len-30-(($MaxLen-30)+3));
	}
	return $Ref;
}


?>