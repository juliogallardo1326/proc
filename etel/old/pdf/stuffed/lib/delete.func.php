<?



$MyVersion=$Db->Version;
$MyVersion=ToFloat($MyVersion)*10;


function DeleteSiteStat($CpId, $SiteId, $StartTime=false, $EndTime=false)
{
	global $Db, $MyVersion, $Logs;
	$Db->LastError=false;
	$Where="";
		if ($StartTime&&$EndTime) $Where=" AND S_LOG.STAMP BETWEEN '$StartTime' AND '$EndTime'";
	$V40Syn=PFX."_tracker_".$CpId."_stat_log, ".PFX."_tracker_".$CpId."_stat_click, ".PFX."_tracker_".$CpId."_stat_action, ".PFX."_tracker_".$CpId."_stat_sale, ".PFX."_tracker_".$CpId."_stat_split, ".PFX."_tracker_".$CpId."_stat_undef";
	$V41Syn="S_LOG, S_ACTION, S_CLICK, S_SPLIT, S_UNDEF, S_SALE";
	$Str=($MyVersion>40)?$V41Syn:$V40Syn;

	$Logs->USE_LOG=false;
	$Query = "
		DELETE $Str
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION
			ON S_ACTION.LOG_ID=S_LOG.ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
			ON S_CLICK.LOG_ID=S_LOG.ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_split S_SPLIT
			ON S_SPLIT.LOG_ID=S_LOG.ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_undef S_UNDEF
			ON S_UNDEF.LOG_ID=S_LOG.ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_sale S_SALE
			ON S_SALE.LOG_ID=S_LOG.ID

		WHERE S_LOG.SITE_ID=$SiteId
			$Where
	";
	$Db->Query($Query);
	$Logs->USE_LOG=true;
	if ($Db->LastError) {
		$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_log WHERE SITE_ID=$SiteId ".str_replace("S_LOG.", "", $Where);
		$Db->Query($Query);
	}
	return $Db->LastAffected;
}

function DeleteSiteHostStat($CpId, $SiteId, $HostId, $StartTime=false, $EndTime=false)
{
	global $Db, $MyVersion, $Logs;
	$Db->LastError=false;

	$Where="";
		if ($StartTime&&$EndTime) $Where=" AND S_LOG.STAMP BETWEEN '$StartTime' AND '$EndTime'";
	$V40Syn=PFX."_tracker_".$CpId."_stat_log, ".PFX."_tracker_".$CpId."_stat_click, ".PFX."_tracker_".$CpId."_stat_action, ".PFX."_tracker_".$CpId."_stat_sale, ".PFX."_tracker_".$CpId."_stat_split, ".PFX."_tracker_".$CpId."_stat_undef";
	$V41Syn="S_LOG, S_ACTION, S_CLICK, S_SPLIT, S_UNDEF, S_SALE";
	$Str=($MyVersion>40)?$V41Syn:$V40Syn;

	$Logs->USE_LOG=false;
	$Query = "
		DELETE $Str
		FROM ".PFX."_tracker_".$CpId."_stat_log S_LOG


		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION
			ON S_ACTION.LOG_ID=S_LOG.ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_click S_CLICK
			ON S_CLICK.LOG_ID=S_LOG.ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_split S_SPLIT
			ON S_SPLIT.LOG_ID=S_LOG.ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_undef S_UNDEF
			ON S_UNDEF.LOG_ID=S_LOG.ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_sale S_SALE
			ON S_SALE.LOG_ID=S_LOG.ID

		WHERE S_LOG.SITE_ID=$SiteId AND S_LOG.SITE_HOST_ID=$HostId
			$Where
	";
	$Db->Query($Query);
	$Logs->USE_LOG=true;
	if ($Db->LastError) {
		$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_log WHERE SITE_ID=$SiteId AND SITE_HOST_ID=$HostId ".str_replace("S_LOG.", "", $Where);
		$Db->Query($Query);
	}
	return $Db->LastAffected;
}


function DeleteCampStat($CpId, $CampId, $StartTime=false, $EndTime=false)
{
	global $Db, $MyVersion, $Logs;
	$Db->LastError=false;

	$Where="";
	if ($StartTime&&$EndTime) $Where=" AND S_LOG.STAMP BETWEEN '$StartTime' AND '$EndTime'";

	$V40Syn= PFX."_tracker_".$CpId."_stat_click, ".PFX."_tracker_".$CpId."_stat_log, ".PFX."_tracker_".$CpId."_stat_action";
	$V41Syn="S_LOG, S_ACTION, S_CLICK";
	$Str=($MyVersion>40)?$V41Syn:$V40Syn;

	$Logs->USE_LOG=false;
	$Query = "
		DELETE $Str
		FROM ".PFX."_tracker_".$CpId."_stat_click S_CLICK
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_log S_LOG
		ON S_LOG.ID=S_CLICK.LOG_ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION
		ON S_ACTION.LOG_ID=S_LOG.ID
		WHERE S_CLICK.CAMP_ID=$CampId
				$Where
	";
	$Db->Query($Query);
	$Logs->USE_LOG=true;
	if ($Db->LastError) {

		if ($StartTime&&$EndTime) {
			$Query = "
				SELECT 
					S_CLICK.ID AS CLICK_ID,
					S_LOG.ID AS LOG_ID
					FROM ".PFX."_tracker_".$CpId."_stat_click S_CLICK 
						INNER JOIN ".PFX."_tracker_".$CpId."_stat_log S_LOG
							ON S_LOG.ID=S_CLICK.LOG_ID
					WHERE S_CLICK.CAMP_ID=$CampId $Where
			";
			$Sql = new Query($Query);
			while ($Row=$Sql->Row()) {
				$Query="DELETE FROM ".PFX."_tracker_".$CpId."_stat_log WHERE ID = ".$Row->LOG_ID;
				$Db->Query($Query);
				$Query="DELETE FROM ".PFX."_tracker_".$CpId."_stat_click WHERE ID = ".$Row->CLICK_ID;
				$Db->Query($Query);
			}
		}
		else {
			$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_click WHERE CAMP_ID=$CampId";
			$Db->Query($Query);
		}
	}
	return $Db->LastAffected;
}

function DeleteSplitStat($CpId, $SubId, $StartTime=false, $EndTime=false)
{
	global $Db, $MyVersion, $Logs;
	$Db->LastError=false;

	$Where="";
	if ($StartTime&&$EndTime) $Where=" AND S_LOG.STAMP BETWEEN '$StartTime' AND '$EndTime'";

	$V40Syn= PFX."_tracker_".$CpId."_stat_split, ".PFX."_tracker_".$CpId."_stat_log, ".PFX."_tracker_".$CpId."_stat_action";
	$V41Syn="S_LOG, S_ACTION, S_SPLIT";
	$Str=($MyVersion>40)?$V41Syn:$V40Syn;

	$Logs->USE_LOG=false;
	$Query = "
		DELETE $Str
		FROM ".PFX."_tracker_".$CpId."_stat_split S_SPLIT
		INNER JOIN ".PFX."_tracker_".$CpId."_stat_log S_LOG
		ON S_LOG.ID=S_SPLIT.LOG_ID
		LEFT JOIN ".PFX."_tracker_".$CpId."_stat_action S_ACTION
		ON S_ACTION.LOG_ID=S_LOG.ID
		WHERE S_SPLIT.SPLIT_ID=$SubId
				$Where
	";
	$Db->Query($Query);
	$Logs->USE_LOG=true;
	if ($Db->LastError) {

		if ($StartTime&&$EndTime) {
			$Query = "
				SELECT 
					S_SPLIT.ID AS SPLIT_ID,
					S_LOG.ID AS LOG_ID
					FROM ".PFX."_tracker_".$CpId."_stat_split S_SPLIT
						INNER JOIN ".PFX."_tracker_".$CpId."_stat_log S_LOG
							ON S_LOG.ID=S_SPLIT.LOG_ID
					WHERE S_SPLIT.SPLIT_ID=$SubId $Where
			";
			$Sql = new Query($Query);
			while ($Row=$Sql->Row()) {
				$Query="DELETE FROM ".PFX."_tracker_".$CpId."_stat_log WHERE ID = ".$Row->LOG_ID;
				$Db->Query($Query);
				$Query="DELETE FROM ".PFX."_tracker_".$CpId."_stat_split WHERE ID = ".$Row->SPLIT_ID;
				$Db->Query($Query);
			}
		}
		else {
			$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_split WHERE SPLIT_ID=$SubId";
			$Db->Query($Query);
		}
	}
	return $Db->LastAffected;
}

function DeleteActionStat($CpId, $ActionId) {
	global $Db, $MyVersion, $Logs;
	$Db->LastError=false;

	$V40Syn= PFX."_tracker_action_item, ".PFX."_tracker_action_set, ".PFX."_tracker_".$CpId."_stat_action";
	$V41Syn="AI, ASET, S_ACTION";
	$Str=($MyVersion>40)?$V41Syn:$V40Syn;

	$Logs->USE_LOG=false;
	$Query = "
		DELETE $Str
			FROM ".PFX."_tracker_".$CpId."_stat_action S_ACTION
			LEFT JOIN ".PFX."_tracker_action_set ASE
				ON ASE.STAT_ACTION_ID=S_ACTION.ACTION_ID
			LEFT JOIN ".PFX."_tracker_action_item AI
				ON AI.ID=ASE.ACTION_ITEM_ID
		WHERE S_ACTION.ACTION_ID=$ActionId
	";
	$Db->Query($Query);
	$Logs->USE_LOG=true;
	if ($Db->LastError) {
		$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_action WHERE ACTION_ID=$ActionId";
		$Db->Query($Query);
	}
	return $Db->LastAffected;
}



function DeleteSubCampaign($CpId, $Id, $NoRedir=false)
{
	global $Db, $Logs, $nsProduct, $Lang, $GrpId;
	$Query = "DELETE FROM ".PFX."_tracker_camp_piece WHERE ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_sub_campaign WHERE SUB_ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_camp_cost WHERE SUB_CAMPAIGN = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE SUB_ID = $Id";
	$Db->Query($Query);
	DeleteCampStat($CpId, $Id);
	if (!$NoRedir&&$GrpId>0) $nsProduct->Redir("incampaign", "CampId=$GrpId&RDlt=1");
	if (!$NoRedir&&$GrpId==0) $nsProduct->Redir("campaign", "RDlt=1");
}

function DeleteSplit($CpId, $Id, $NoRedir=false)
{
	global $Db, $Logs, $nsProduct, $Lang, $CompId, $GrpId;
	$SplitId=$Db->ReturnValue("SELECT ID FROM ".PFX."_tracker_split_test WHERE SUB_ID=$Id");
	$Query = "DELETE FROM ".PFX."_tracker_camp_piece WHERE ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_split_test WHERE SUB_ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_split_page WHERE SPLIT_ID = $SplitId";
	$Db->Query($Query);

	DeleteSplitStat($CpId, $Id);
	if (!$NoRedir&&!$GrpId) $nsProduct->Redir("split_list", "CompId=$CompId&RDlt=1");
	if (!$NoRedir&&ValidId($GrpId)&&$GrpId>0) $nsProduct->Redir("incampaign", "CampId=$GrpId&RDlt=1");
}


function DeleteCampaign($CpId, $Id, $NoRedir=false) 
{
	global $Db, $Logs, $Lang, $nsProduct;
	$SubCnt = $Db->CNT(PFX."_tracker_campaign", "PARENT_ID=$Id");

	if ($SubCnt>0) {
		$Query = "SELECT ID FROM ".PFX."_tracker_campaign WHERE PARENT_ID=$Id";
		$Sql = new Query($Query);
		while ($Row=$Sql->Row()) DeleteCampaign($CpId, $Row->ID,true);
	}

	$Query = "
		SELECT 
			TCP.ID,
			TSC.ID AS SUB_CAMP,
			TST.ID AS SPLIT_TEST
			FROM ".PFX."_tracker_camp_piece TCP
				LEFT JOIN ".PFX."_tracker_sub_campaign TSC
					ON TSC.SUB_ID=TCP.ID
				LEFT JOIN ".PFX."_tracker_split_test TST
					ON TST.SUB_ID=TCP.ID
			WHERE CAMPAIGN_ID=$Id 
			ORDER BY TCP.NAME
	";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		if ($Row->SUB_CAMP) DeleteSubCampaign($CpId, $Row->ID, true);
		if ($Row->SPLIT_TEST) DeleteSplit($CpId, $Row->ID, true);
	}

	$Query = "DELETE FROM ".PFX."_tracker_campaign WHERE ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE GRP_ID = $Id";
	$Db->Query($Query);
	if (!$NoRedir) $nsProduct->Redir("campaign", "RDlt=1");
}



function DeleteCompany($Id, $NoRedir=false) {
	global $Db, $nsProduct, $Logs, $Lang;

	$Users=array();
	$Query = "SELECT USER_ID FROM ".PFX."_tracker_user WHERE COMPANY_ID=$Id";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) $Users[]=$Row->USER_ID;
	for($i=0;$i<count($Users);$i++) {
		$Query="DELETE FROM ".PFX."_system_user WHERE ID = ".$Users[$i];
		$Db->Query($Query);
		$Query="DELETE FROM ".PFX."_tracker_user WHERE USER_ID = ".$Users[$i];
		$Db->Query($Query);
		$Query="DELETE FROM ".PFX."_tracker_user_column WHERE USER_ID = ".$Users[$i];
		$Db->Query($Query);
		$Query="DELETE FROM ".PFX."_tracker_user_settings WHERE USER_ID = ".$Users[$i];
		$Db->Query($Query);
	}


	$Query = "SELECT ID FROM ".PFX."_tracker_site WHERE COMPANY_ID=$Id";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) DeleteHost($Id, $Row->ID, false, true);


	DeleteCompanyActions($Id);
	DeleteCompanySaleItems($Id);

	$Query = "SELECT ID FROM ".PFX."_tracker_campaign WHERE PARENT_ID=0 AND COMPANY_ID=$Id";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) DeleteCampaign($Row->ID,true);

	$Query = "DELETE FROM ".PFX."_tracker_config WHERE COMPANY_ID=$Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_const_group WHERE COMPANY_ID=$Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_user_report WHERE COMPANY_ID=$Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE COMPANY_ID=$Id";
	$Db->Query($Query);

	DropStatTables($Id);
	$Query = "DELETE FROM ".PFX."_tracker_client WHERE ID = $Id";
	$Db->Query($Query);
	if (!$NoRedir)$nsProduct->Redir("company", "RDlt=1");
}

function DeleteCompanyActions($CpId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_action_item WHERE COMPANY_ID=$CpId";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_action_set WHERE COMPANY_ID=$CpId";
	$Db->Query($Query);
}


function DeleteCompanySaleItems($CpId)
{
	global $Db;
	$Query = "DELETE FROM ".PFX."_tracker_sale_item WHERE COMPANY_ID=$CpId";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_sale_set WHERE COMPANY_ID=$CpId";
	$Db->Query($Query);
}



function DeleteHost($CpId, $Id, $NoDelStat=false, $NoRedir=false)
{
	global $Db, $nsUser, $nsProduct;
	if (!$NoDelStat) DeleteSiteStat($CpId, $Id);
	$Query = "DELETE FROM ".PFX."_tracker_site WHERE ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_site_host WHERE SITE_ID=$Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_site_page WHERE SITE_ID=$Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_visitor_action WHERE SITE_ID=$Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_watch WHERE SITE_ID=$Id";
	$Db->Query($Query);
	if (!$NoRedir) $nsProduct->Redir("company", "EditId=$CpId&RDlt=1", "admin");
}


function DeleteOneSale($CpId, $Id)
{
	global $Db, $Logs, $Lang;
	$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_sale WHERE ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_sale_set WHERE SALE_ID = $Id";
	$Db->Query($Query);
	$Logs->Msg($Lang['RecordDeleted']);
}

function DeleteOneAction($CpId, $Id) {
	global $Db, $Logs, $Lang;
	$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_action WHERE ID = $Id";
	$Db->Query($Query);
	$Query = "DELETE FROM ".PFX."_tracker_action_set WHERE STAT_ACTION_ID = $Id";
	$Db->Query($Query);
	$Logs->Msg($Lang['RecordDeleted']);
}

function DeleteOneClick($CpId, $Id) 
{
	global $Db, $Logs, $Lang;
	$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_click WHERE ID = $Id";
	$Db->Query($Query);
	$Logs->Msg($Lang['RecordDeleted']);
}

function DeleteOneSplit($CpId, $Id) 
{
	global $Db, $Logs, $Lang;
	$Query = "DELETE FROM ".PFX."_tracker_".$CpId."_stat_split WHERE ID = $Id";
	$Db->Query($Query);
	$Logs->Msg($Lang['RecordDeleted']);
}

function ToFloat($Var, $Cnt=1)
{
	$Arr=explode(".", $Var);
	for ($i=0;$i<count($Arr);$i++) {
		$Arr[$i]=intval($Arr[$i]);
	}
	$Var="";
	for ($i=0;$i<=$Cnt;$i++) {
		if ($i>0) $Var.=".";
		$Var.=$Arr[$i];
	}
	return $Var;
}


?>