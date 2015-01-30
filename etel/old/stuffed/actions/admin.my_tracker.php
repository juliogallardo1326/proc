<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");

/////////////////////////////////////////////
///////// require libraries here

$nsLang->TplInc("my_tracker");

/////////////////////////////////////////////
///////// prepare any variables

$EditId=(ValidId($_GP['EditId']))?$_GP['EditId']:false;
$DeleteId=(ValidId($_GP['DeleteId']))?$_GP['DeleteId']:false;
$MyDeleteId=(ValidId($_GP['MyDeleteId']))?$_GP['MyDeleteId']:false;
$EditArr=(ValidArr($_GP['EditArr']))?$_GP['EditArr']:false;
$Mode = (ValidVar($_GP['Mode'])) ?$_GP['Mode']:false;
$AddToMy=(ValidArr($_GP['AddToMy']))?$_GP['AddToMy']:false;
$Filter = (ValidVar($_GP['Filter'])) ?$_GP['Filter']:false;
$SelectCpId=(ValidId($_GP['SelectCpId']))?$_GP['SelectCpId']:false;

$PageTitle=$Lang['Title'];
$AllClients=($nsUser->ADMIN)?1:false;
if (!$Mode) $Mode="list";
$DATE_DIFF=false;

$ProgPath[0]['Name']=$Lang['Title'];
$ProgPath[0]['Url']=$nsProduct->SelfAction();

$MenuSection="my_tracker";

//if ($Mode=="list") {
	$SubMenu[0]['Name']=$Lang['SavedReports'];
	$SubMenu[0]['Link']=getURL("my_tracker", "Mode=reports", "admin");
	$SubMenu[1]['Name']=$Lang['Visitors'];
	$SubMenu[1]['Link']=getURL("my_tracker", "Mode=visitors", "admin");
	$SubMenu[2]['Name']=$Lang['VisitorGrps'];
	$SubMenu[2]['Link']=getURL("my_tracker", "Mode=visitor_grps", "admin");
	$SubMenu[3]['Name']=$Lang['Actions'];
	$SubMenu[3]['Link']=getURL("my_tracker", "Mode=actions", "admin");
	$SubMenu[4]['Name']=$Lang['ActionItems'];
	$SubMenu[4]['Link']=getURL("my_tracker", "Mode=action_items", "admin");
	$SubMenu[5]['Name']=$Lang['SaleItems'];
	$SubMenu[5]['Link']=getURL("my_tracker", "Mode=sale_items", "admin");
//}

//if ($Mode!="list") {
//	$SubMenu[0]['Name']=$Lang['Title'];
//	$SubMenu[0]['Link']=getURL("my_tracker", "Mode=list", "admin");
//}

/////////////////////////////////////////////
///////// call any process functions
if (!$nsUser->DEMO) {
	if ($Mode=="reports"&&ValidId($EditId)&&ValidArr($EditArr)) SaveUserReport($EditId, $EditArr);
	if ($Mode=="reports"&&ValidId($DeleteId)) DeleteUserReport($DeleteId);
	if ($Mode=="visitors"&&ValidId($DeleteId)) DeleteClientVisitor($DeleteId);
	if ($Mode=="visitor_grps"&&ValidId($DeleteId)) DeleteClientVisitorGrp($DeleteId);

	if (ValidArr($AddToMy)&&$Mode=="reports") AddToMy($AddToMy, "REPORT_ID");
	if (ValidId($MyDeleteId)&&$Mode=="reports") DeleteFromMy($MyDeleteId, "REPORT_ID");
	if (ValidArr($AddToMy)&&$Mode=="visitors") AddToMy($AddToMy, "VISITOR_ID");
	if (ValidId($MyDeleteId)&&$Mode=="visitors") DeleteFromMy($MyDeleteId, "VISITOR_ID");
	if (ValidArr($AddToMy)&&$Mode=="visitor_grps") AddToMy($AddToMy, "VISITOR_GRP_ID");
	if (ValidId($MyDeleteId)&&$Mode=="visitor_grps") DeleteFromMy($MyDeleteId, "VISITOR_GRP_ID");
	if (ValidArr($AddToMy)&&$Mode=="actions") AddToMy($AddToMy, "ACTION_ID");
	if (ValidId($MyDeleteId)&&$Mode=="actions") DeleteFromMy($MyDeleteId, "ACTION_ID");
	if (ValidArr($AddToMy)&&$Mode=="action_items") AddToMy($AddToMy, "ACTION_ITEM_ID");
	if (ValidId($MyDeleteId)&&$Mode=="action_items") DeleteFromMy($MyDeleteId, "ACTION_ITEM_ID");
	if (ValidArr($AddToMy)&&$Mode=="sale_items") AddToMy($AddToMy, "SALE_ITEM_ID");
	if (ValidId($MyDeleteId)&&$Mode=="sale_items") DeleteFromMy($MyDeleteId, "SALE_ITEM_ID");

}

/////////////////////////////////////////////
///////// display section here


///////////////////////
if ($Mode=="list") {



	$Query = "
		SELECT UR.*, C.NAME AS COMP_NAME
		FROM ".PFX."_tracker_watch W
		INNER JOIN  ".PFX."_tracker_user_report UR
			ON UR.ID=W.REPORT_ID
		INNER JOIN ".PFX."_tracker_client C
			ON C.ID=UR.COMPANY_ID
		WHERE W.USER_ID=".$nsUser->UserId()." 
		ORDER BY C.NAME, UR.NAME
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$UserReports=array();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Row->NAME=stripslashes($Row->NAME);
		if ($Row->CONST_TYPE=="NATURAL") $Row->Addr="natural_constructor";
		if ($Row->CONST_TYPE=="PAID") $Row->Addr="paid_constructor";
		if ($Row->COMPANY_ID==0) $Row->CP_ID=$CurrentCompany->ID;
		else $Row->CP_ID=$Row->COMPANY_ID;
		$UserReports[]=$Row;
	}

	$Where="";
	$IdsStr="";
	if (!$nsUser->ADMIN) {
		$SiteIds=array();
		$Query = "SELECT ID FROM ".PFX."_tracker_site WHERE COMPANY_ID=".$nsUser->COMPANY_ID;
		$Sql= new Query($Query);
		while ($Row=$Sql->Row()) $SiteIds[]=$Row->ID;
		$IdsStr=implode(",",$SiteIds);
		$Where="AND S_LOG.SITE_ID IN ($IdsStr)";
	}

	$WatchVis=array();
	$UsersWhere="";
	if (!$nsUser->ADMIN) $UsersWhere="AND CV.COMPANY_ID=".$CurrentCompany->ID;
	$Query = "
		SELECT 
		V.*, CV.NAME, CV.DESCRIPTION, CV.COMPANY_ID,
			I.IP AS LAST_IP
		FROM ".PFX."_tracker_watch WV
		INNER JOIN ".PFX."_tracker_visitor V
			ON V.ID=WV.VISITOR_ID
		INNER JOIN ".PFX."_tracker_client_visitor CV	
			ON CV.VISITOR_ID=V.ID $UsersWhere
		LEFT JOIN ".PFX."_tracker_ip I
				ON I.ID=V.LAST_IP_ID
		WHERE WV.USER_ID=".$nsUser->UserId()."
		ORDER BY CV.NAME ASC, V.ID ASC
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Query = "SELECT UNIX_TIMESTAMP(DATE_ADD(STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) FROM ".PFX."_tracker_".$Row->COMPANY_ID."_stat_log S_LOG WHERE S_LOG.VISITOR_ID=".$Row->ID." AND S_LOG.PAGE_ID>0 $Where ORDER BY STAMP DESC LIMIT 1";
		$Row->LAST_STAMP=$Db->ReturnValue($Query);
		if ($Row->LAST_STAMP)  $Row->DATE_DIFF_NAME=LastStamp($Row->LAST_STAMP);
		$Row->DATE_DIFF=$DATE_DIFF;
		$WatchVis[]=$Row;
	}

	$WatchVisGrp=array();
	$UsersWhere="";
	if (!$nsUser->ADMIN) $UsersWhere="AND CV.COMPANY_ID=".$CurrentCompany->ID;
	$Query = "
		SELECT 
		CV.*
		FROM ".PFX."_tracker_watch WV
		INNER JOIN ".PFX."_tracker_client_visitor_grp CV
			ON CV.ID=WV.VISITOR_GRP_ID $UsersWhere
		WHERE WV.USER_ID=".$nsUser->UserId()."
		ORDER BY CV.NAME ASC, CV.ID ASC
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;

		$GrpIpArr=array();
		$IpTemplArr=array();
		$Query = "SELECT IP FROM ".PFX."_tracker_client_visitor_grp_ip WHERE GRP_ID=".$Row->ID;
		$SubSql = new Query($Query);
		while ($SubRow=$SubSql->Row()) {
			if (strpos($SubRow->IP, "*")===false) $GrpIpArr[]="'".$SubRow->IP."'";
			else $IpTemplArr[]=$SubRow->IP;
		}

		if (count($GrpIpArr)>0||count($IpTemplArr)>0) {

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
			if ($IdsStr) $WhereArr[]="S_LOG.SITE_ID IN ($IdsStr)";
			if ($WhereIpIn) $WhereArr[]=$WhereIpIn;
			if ($WhereIpTempl) $WhereArr[]=$WhereIpTempl;
			$WhereArr[]="S_LOG.PAGE_ID>0";
			$WhereStr="";
			if (count($WhereArr)>0) $WhereStr=" WHERE ".implode(" AND ", $WhereArr);


			$Query = "
				SELECT STRAIGHT_JOIN
					UNIX_TIMESTAMP(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) 
					FROM ".PFX."_tracker_ip I
					INNER JOIN  ".PFX."_tracker_".$Row->COMPANY_ID."_stat_log S_LOG FORCE INDEX (IP_ID)
					ON S_LOG.IP_ID=I.ID
					$WhereStr 
					ORDER BY S_LOG.STAMP DESC LIMIT 1";
			
			$Row->LAST_STAMP=$Db->ReturnValue($Query);
			if ($Row->LAST_STAMP)  $Row->DATE_DIFF_NAME=LastStamp($Row->LAST_STAMP);
			$Row->DATE_DIFF=$DATE_DIFF;
		}
		else $Row->LAST_STAMP=false;

		$WatchVisGrp[]=$Row;
	}

	$WatchActions=array();
	$ActionsWhere="";
	if (!$nsUser->ADMIN) $ActionsWhere=" AND C.ID=".$CurrentCompany->ID;
	$Query = "
		SELECT 
			VA.ID, VA.NAME, C.ID AS COMPANY_ID, C.NAME AS COMP_NAME, S.HOST, S.ID AS SITE_ID
			FROM ".PFX."_tracker_watch W
			INNER JOIN ".PFX."_tracker_visitor_action VA
				ON VA.ID=W.ACTION_ID
			INNER JOIN ".PFX."_tracker_site S
				ON S.ID=VA.SITE_ID
			INNER JOIN ".PFX."_tracker_client C
				ON C.ID=S.COMPANY_ID
		WHERE W.USER_ID=".$nsUser->UserId()." $ActionsWhere
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Query = "
			SELECT
			UNIX_TIMESTAMP(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR))
			FROM ".PFX."_tracker_".$Row->COMPANY_ID."_stat_log S_LOG
			INNER JOIN ".PFX."_tracker_".$Row->COMPANY_ID."_stat_action S_ACTION
				 ON S_ACTION.LOG_ID=S_LOG.ID
			WHERE S_ACTION.ACTION_ID=".$Row->ID." $Where
			ORDER BY S_LOG.STAMP DESC
			LIMIT 1
		";
		$Row->LAST_STAMP=$Db->ReturnValue($Query);
		if ($Row->LAST_STAMP)  $Row->DATE_DIFF_NAME=LastStamp($Row->LAST_STAMP);
		$Row->DATE_DIFF=$DATE_DIFF;
		$WatchActions[]=$Row;
	}

	$WatchActionItems=array();
	$ActionItemsWhere="";
	if (!$nsUser->ADMIN) $ActionItemsWhere=" AND AI.COMPANY_ID=".$CurrentCompany->ID;

	$Query = "
		SELECT
			AI.*
			FROM ".PFX."_tracker_watch W
			INNER JOIN ".PFX."_tracker_action_item AI
				ON AI.ID=W.ACTION_ITEM_ID
		WHERE W.USER_ID=".$nsUser->UserId()." $ActionItemsWhere
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Query = "
			SELECT
			UNIX_TIMESTAMP(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR))
			FROM ".PFX."_tracker_".$Row->COMPANY_ID."_stat_log S_LOG
			INNER JOIN ".PFX."_tracker_".$Row->COMPANY_ID."_stat_action S_ACTION
				 ON S_ACTION.LOG_ID=S_LOG.ID
			INNER JOIN ".PFX."_tracker_action_set SAS
				ON SAS.STAT_ACTION_ID=S_ACTION.ID
			INNER JOIN ".PFX."_tracker_action_item AI
				ON AI.ID=SAS.ACTION_ITEM_ID
			WHERE AI.ID=".$Row->ID." $ActionItemsWhere
			ORDER BY S_LOG.STAMP DESC
			LIMIT 1
		";
		$Row->LAST_STAMP=$Db->ReturnValue($Query);
		if ($Row->LAST_STAMP)  $Row->DATE_DIFF_NAME=LastStamp($Row->LAST_STAMP);
		$Row->DATE_DIFF=$DATE_DIFF;
		$WatchActionItems[]=$Row;
	}


	$WatchSaleItems=array();
	$SaleItemsWhere="";
	if (!$nsUser->ADMIN) $SaleItemsWhere=" AND SI.COMPANY_ID=".$CurrentCompany->ID;

	$Query = "
		SELECT
			SI.*
			FROM ".PFX."_tracker_watch W
			INNER JOIN ".PFX."_tracker_sale_item SI
				ON SI.ID=W.SALE_ITEM_ID
		WHERE W.USER_ID=".$nsUser->UserId()." $SaleItemsWhere
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Query = "
			SELECT
			UNIX_TIMESTAMP(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR))
			FROM ".PFX."_tracker_".$Row->COMPANY_ID."_stat_log S_LOG
			INNER JOIN ".PFX."_tracker_".$Row->COMPANY_ID."_stat_sale S_SALE
				 ON S_SALE.LOG_ID=S_LOG.ID
			INNER JOIN ".PFX."_tracker_sale_set SS
				ON SS.SALE_ID=S_SALE.ID
			INNER JOIN ".PFX."_tracker_sale_item SI
				ON SI.ID=SS.ITEM_ID
			WHERE SI.ID=".$Row->ID." $SaleItemsWhere
			ORDER BY S_LOG.STAMP DESC
			LIMIT 1
		";
		$Row->LAST_STAMP=$Db->ReturnValue($Query);
		if ($Row->LAST_STAMP)  $Row->DATE_DIFF_NAME=LastStamp($Row->LAST_STAMP);
		$Row->DATE_DIFF=$DATE_DIFF;
		$WatchSaleItems[]=$Row;
	}

	include $nsTemplate->Inc("admin.my_tracker");
}





$NoAdd=true;



/////////////////////////////////////
if ($Mode=="visitors") {

	$ProgPath[1]['Name']=$Lang['Visitors'];
	$ProgPath[1]['Url']=$nsProduct->SelfAction("Mode=$Mode");


	$VisitorsWhere="";
	if (!$nsUser->ADMIN) $VisitorsWhere="WHERE CV.COMPANY_ID=".$CurrentCompany->ID;
	$Query = "
		SELECT 
		V.*, CV.NAME, CV.DESCRIPTION, CV.COMPANY_ID, C.NAME AS COMP_NAME, W.ID AS WATCH_ID
		FROM ".PFX."_tracker_visitor V
		INNER JOIN ".PFX."_tracker_client_visitor CV	
			ON CV.VISITOR_ID=V.ID 
		INNER JOIN ".PFX."_tracker_client C
			ON C.ID=CV.COMPANY_ID
		LEFT JOIN ".PFX."_tracker_watch W
			ON W.VISITOR_ID=V.ID AND W.USER_ID=".$nsUser->UserId()."
		$VisitorsWhere
		ORDER BY C.NAME ASC, V.ID ASC
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$VisitorsList=array();
	$PrevCp=0;
	while ($Row=$Sql->Row()) {
		if (!$Row->WATCH_ID) $NoAdd=false;
		$Row->_STYLE=$Sql->_STYLE;
		$Row->NewComp=false;
		$Row->NAME=stripslashes($Row->NAME);
		$Row->DESCRIPTION=stripslashes($Row->DESCRIPTION);
		$Row->CP_ID=$Row->COMPANY_ID;
		if ($PrevCp!=$Row->COMPANY_ID) $Row->NewComp=true;
		$VisitorsList[]=$Row;
		$PrevCp=$Row->COMPANY_ID;
	}
	include $nsTemplate->Inc("admin.for_my_visitors");
}

/////////////////////////////////////
if ($Mode=="visitor_grps") {

	$ProgPath[1]['Name']=$Lang['VisitorGrps'];
	$ProgPath[1]['Url']=$nsProduct->SelfAction("Mode=$Mode");

	$VisitorsWhere="";
	if (!$nsUser->ADMIN) $VisitorsWhere="WHERE CV.COMPANY_ID=".$CurrentCompany->ID;
	$Query = "
		SELECT 
		CV.*, C.NAME AS COMP_NAME, W.ID AS WATCH_ID
		FROM ".PFX."_tracker_client_visitor_grp CV	
		INNER JOIN ".PFX."_tracker_client C
			ON C.ID=CV.COMPANY_ID
		LEFT JOIN ".PFX."_tracker_watch W
			ON W.VISITOR_GRP_ID=CV.ID AND W.USER_ID=".$nsUser->UserId()."
		$VisitorsWhere
		ORDER BY C.NAME ASC, CV.ID ASC
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$VisitorsList=array();
	$PrevCp=0;
	while ($Row=$Sql->Row()) {
		if (!$Row->WATCH_ID) $NoAdd=false;
		$Row->_STYLE=$Sql->_STYLE;
		$Row->NewComp=false;
		$Row->NAME=stripslashes($Row->NAME);
		$Row->DESCRIPTION=stripslashes($Row->DESCRIPTION);
		$Row->CP_ID=$Row->COMPANY_ID;
		if ($PrevCp!=$Row->COMPANY_ID) $Row->NewComp=true;
		$VisitorsList[]=$Row;
		$PrevCp=$Row->COMPANY_ID;
	}
	//if (isset($CurrentCompany->ID)) {
	//	$SubMenu[1]['Name']=$Lang['AddNewGrp'];
	//	$SubMenu[1]['Link']=getURL("visitor_grp", "EditId=new");
	//}
	include $nsTemplate->Inc("admin.for_my_visitor_grps");
}

/////////////////////////////////////
if ($Mode=="reports") {

	$ProgPath[1]['Name']=$Lang['SavedReports'];
	$ProgPath[1]['Url']=$nsProduct->SelfAction("Mode=$Mode");

	$ReportsWhere="";
	if (!$nsUser->ADMIN) $ReportsWhere="WHERE UR.COMPANY_ID=".$nsUser->COMPANY_ID;
	$Query = "
		SELECT UR.*, C.NAME AS COMP_NAME, W.ID AS WATCH_ID
		FROM ".PFX."_tracker_user_report UR
		INNER JOIN ".PFX."_tracker_client C
			ON C.ID=UR.COMPANY_ID
		LEFT JOIN ".PFX."_tracker_watch W
			ON W.USER_ID=".$nsUser->UserId()." AND W.REPORT_ID=UR.ID
		$ReportsWhere
		ORDER BY C.NAME, UR.NAME
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$ReportsList=array();
	$PrevCp=0;
	while ($Row=$Sql->Row()) {
		if (!$Row->WATCH_ID) $NoAdd=false;
		$Row->_STYLE=$Sql->_STYLE;
		$Row->NewComp=false;
		$Row->NAME=stripslashes($Row->NAME);
		if ($Row->CONST_TYPE=="NATURAL") $Row->Addr="natural_constructor";
		if ($Row->CONST_TYPE=="PAID") $Row->Addr="paid_constructor";
		$Row->CP_ID=$Row->COMPANY_ID;
		if ($PrevCp!=$Row->COMPANY_ID) $Row->NewComp=true;
		$ReportsList[]=$Row;
		$PrevCp=$Row->COMPANY_ID;
	}
	include $nsTemplate->Inc("admin.for_my_reports");
}



/////////////////////////////////////
if ($Mode=="actions") {

	$ProgPath[1]['Name']=$Lang['Actions'];
	$ProgPath[1]['Url']=$nsProduct->SelfAction("Mode=$Mode");

	$ActionsWhere="";
	if (!$nsUser->ADMIN) $ActionsWhere="WHERE S.COMPANY_ID=".$CurrentCompany->ID;
	$Query = "
		SELECT 
			C.ID AS COMPANY_ID, C.NAME AS COMP_NAME,
			S.HOST, S.ID AS SITE_ID,
			VA.ID, VA.NAME, 
			W.ID AS WATCH_ID
			FROM ".PFX."_tracker_visitor_action VA
			INNER JOIN ".PFX."_tracker_site S
				ON S.ID=VA.SITE_ID
			INNER JOIN ".PFX."_tracker_client C
				ON C.ID=S.COMPANY_ID
			LEFT JOIN ".PFX."_tracker_watch W
				ON W.ACTION_ID=VA.ID AND W.USER_ID=".$nsUser->UserId()."
		$ActionsWhere
		ORDER BY S.COMPANY_ID, S.HOST, VA.NAME
	";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	$ActionsList=array();
	$PrevCp=0;
	while ($Row=$Sql->Row()) {
		if (!$Row->WATCH_ID) $NoAdd=false;
		$Row->_STYLE=$Sql->_STYLE;
		$Row->NewComp=false;
		$Row->NAME=stripslashes($Row->NAME);
		$Row->CP_ID=$Row->COMPANY_ID;
		if ($PrevCp!=$Row->COMPANY_ID) $Row->NewComp=true;
		$ActionsList[]=$Row;
		$PrevCp=$Row->COMPANY_ID;
	}
	include $nsTemplate->Inc("admin.for_my_actions");

}

/////////////////////////////////////
if ($Mode=="action_items") {

	$ProgPath[1]['Name']=$Lang['ActionItems'];
	$ProgPath[1]['Url']=$nsProduct->SelfAction("Mode=$Mode");

	$ActionItemsList=array();
	if (!$SelectCpId) $SelectCpId=ValidVar($CurrentCompany->ID);
	if ($nsUser->ADMIN) {
		$Query = "SELECT * FROM ".PFX."_tracker_client WHERE HIDDEN != '1' ORDER BY NAME ASC";
		$Sql = new Query($Query);
		$CompArr=array();
		while ($Row = $Sql->Row()) $CompArr[]=$Row;
		if (count($CompArr)==1) $SelectCpId=$CompArr[0]->ID;
	}

	if ($SelectCpId) {

		$ItemsWhere="";
		if (ValidVar($Filter)) {
			$Filter=addslashes($Filter);
			$ItemsWhere="AND AI.NAME LIKE '%$Filter%'";
			$Filter=stripslashes($Filter);
		}

		$Query = "
			SELECT 
			AI.*, C.NAME AS COMP_NAME, W.ID AS WATCH_ID
			FROM ".PFX."_tracker_action_item AI
			INNER JOIN ".PFX."_tracker_client C
				ON C.ID=AI.COMPANY_ID
			LEFT JOIN ".PFX."_tracker_watch W
				ON W.ACTION_ITEM_ID=AI.ID AND W.USER_ID=".$nsUser->UserId()."
			WHERE AI.COMPANY_ID=$SelectCpId
				$ItemsWhere
			ORDER BY AI.COMPANY_ID, AI.NAME
		";
		$Sql = new Query($Query);
		$Sql->ReadSkinConfig();
		$ActionsList=array();
		$PrevCp=0;
		while ($Row=$Sql->Row()) {
			if (!$Row->WATCH_ID) $NoAdd=false;
			$Row->_STYLE=$Sql->_STYLE;
			$Row->NewComp=false;
			$Row->NAME=htmlspecialchars(stripslashes($Row->NAME));
			$Row->CP_ID=$Row->COMPANY_ID;
			if ($PrevCp!=$Row->COMPANY_ID) $Row->NewComp=true;
			$ActionItemsList[]=$Row;
			$PrevCp=$Row->COMPANY_ID;
		}

	}
	else {
		//$Logs->Msg($Lang['AIChooseClient']);
	}
	include $nsTemplate->Inc("admin.for_my_action_items");

}


/////////////////////////////////////
if ($Mode=="sale_items") {
	$SaleItemsList=array();
	$ProgPath[1]['Name']=$Lang['SaleItems'];
	$ProgPath[1]['Url']=$nsProduct->SelfAction("Mode=$Mode");
	if (!$SelectCpId) $SelectCpId=ValidVar($CurrentCompany->ID);
	if ($nsUser->ADMIN) {
		$Query = "SELECT * FROM ".PFX."_tracker_client WHERE HIDDEN != '1' ORDER BY NAME ASC";
		$Sql = new Query($Query);
		$CompArr=array();
		while ($Row = $Sql->Row()) $CompArr[]=$Row;
		if (count($CompArr)==1) $SelectCpId=$CompArr[0]->ID;
	}
	if ($SelectCpId) {

		$ItemsWhere="";
		if (ValidVar($Filter)) {
			$Filter=addslashes($Filter);
			$ItemsWhere="AND SI.NAME LIKE '%$Filter%'";
			$Filter=stripslashes($Filter);
		}

		$Query = "
			SELECT 
			SI.*, C.NAME AS COMP_NAME, W.ID AS WATCH_ID
			FROM ".PFX."_tracker_sale_item SI
			INNER JOIN ".PFX."_tracker_client C
				ON C.ID=SI.COMPANY_ID
			LEFT JOIN ".PFX."_tracker_watch W
				ON W.SALE_ITEM_ID=SI.ID AND W.USER_ID=".$nsUser->UserId()."
			WHERE SI.COMPANY_ID=$SelectCpId
				$ItemsWhere
			ORDER BY SI.COMPANY_ID, SI.NAME
		";
		$Sql = new Query($Query);
		$Sql->ReadSkinConfig();
		$PrevCp=0;
		while ($Row=$Sql->Row()) {
			if (!$Row->WATCH_ID) $NoAdd=false;
			$Row->_STYLE=$Sql->_STYLE;
			$Row->NewComp=false;
			$Row->NAME=htmlspecialchars(stripslashes($Row->NAME));
			$Row->CP_ID=$Row->COMPANY_ID;
			if ($PrevCp!=$Row->COMPANY_ID) $Row->NewComp=true;
			$SaleItemsList[]=$Row;
			$PrevCp=$Row->COMPANY_ID;
		}

	}
	else {
		//$Logs->Msg($Lang['SIChooseClient']);
	}
	include $nsTemplate->Inc("admin.for_my_sale_items");

}


/////////////////////////////////////////////
///////// process functions here

function SaveUserReport(&$Id, $Arr)
{
	global $Db, $Logs, $Lang;
	extract($Arr);
	$Name=addslashes(trim(ValidVar($Name)));
	if (!$Name) {
		$Logs->Err($Lang['MustFillName']);
		return;
	}
	$Query="UPDATE ".PFX."_tracker_user_report SET NAME='$Name' WHERE ID=$Id";
	$Db->Query($Query);
	$Id=false;
	$Logs->Msg($Lang['RecordUpdated']);
}

function DeleteUserReport($Id)
{
	global $Db, $nsProduct;
	$Query="DELETE FROM ".PFX."_tracker_user_report WHERE ID = $Id";
	$Db->Query($Query);
	$Query="DELETE FROM ".PFX."_tracker_watch WHERE REPORT_ID=$Id";
	$Db->Query($Query);
	$nsProduct->Redir("my_tracker", "Mode=reports&RDlt=1");
}

function DeleteClientVisitor($Id) 
{
	global $Db, $nsUser, $nsProduct;
	$Check=true;
	if (!$nsUser->ADMIN) {
		$Query = "SELECT ID FROM ".PFX."_tracker_client_visitor WHERE VISITOR_ID=$Id AND COMPANY_ID=".$nsUser->COMPANY_ID;
		$Check=$Db->ReturnValue($Query);
	}
	if (!$Check) return false;
	$Query = "DELETE FROM ".PFX."_tracker_client_visitor WHERE VISITOR_ID=$Id";
	$Db->Query($Query);
	$nsProduct->Redir("my_tracker", "Mode=visitors&RDlt=1");
}

function DeleteClientVisitorGrp($Id) 
{
	global $Db, $nsUser, $nsProduct;
	$Check=true;
	if (!$nsUser->ADMIN) {
		$Query = "SELECT ID FROM ".PFX."_tracker_client_visitor_grp WHERE ID=$Id AND COMPANY_ID=".$nsUser->COMPANY_ID;
		$Check=$Db->ReturnValue($Query);
	}
	if (!$Check) return false;
	$Query = "DELETE FROM ".PFX."_tracker_client_visitor_grp WHERE ID=$Id";
	$Db->Query($Query);
	$nsProduct->Redir("my_tracker", "Mode=visitor_grps&RDlt=1");
}

function AddToMy($Arr, $Field)
{
	global $Db, $nsUser, $nsProduct;
	$UserId=$nsUser->UserId();
	foreach ($Arr as $SomeId=>$Value) {
		if ($Value!=1) continue;
		$Query = "INSERT INTO ".PFX."_tracker_watch (USER_ID, $Field) VALUES ($UserId, $SomeId)";
		$Db->Query($Query);
	}
	$nsProduct->Redir("my_tracker", "RUpd=1", "admin");
}

function DeleteFromMy($Id, $Field)
{
	global $Db, $nsUser, $nsProduct;
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE USER_ID=".$nsUser->UserId()." AND $Field=$Id";
	$Db->Query($Query);
	$nsProduct->Redir("my_tracker", "RUpd=1", "admin");
}



/////////////////////////////////////////////
///////// library section

function LastStamp($Stamp) 
{
	global $Db, $Lang, $DATE_DIFF, $nsUser;
	$Query= "SELECT TO_DAYS(DATE_ADD(NOW(), INTERVAL '".$nsUser->TZ."' HOUR))-TO_DAYS(DATE_ADD('".date("Y-m-d", $Stamp)."', INTERVAL '".$nsUser->TZ."' HOUR))";
	$DATE_DIFF=$Db->ReturnValue($Query);
	if ($DATE_DIFF==0) $DATE_DIFF_NAME=$Lang['Today'];
	if ($DATE_DIFF==1) $DATE_DIFF_NAME=$Lang['Yesterday'];
	if ($DATE_DIFF>1) {
		$DATE_DIFF_NAME=$DATE_DIFF." ";
		$DATE_DIFF_NAME.=ParseNumberNow($DATE_DIFF, $Lang['DayN'], $Lang['Day1'], $Lang['Day2']);
		$DATE_DIFF_NAME.=' '.$Lang['DaysBefore'];
	}
	return $DATE_DIFF_NAME;
}

?>