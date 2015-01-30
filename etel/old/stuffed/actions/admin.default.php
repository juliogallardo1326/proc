<?

if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");
if ($nsUser->MERCHANT||$nsProduct->LICENSE!=3) $nsProduct->Redir("client_page", "", "admin");

$PageTitle=($nsProduct->WHITE)?$Lang['Title2']:$Lang['Title'];

require_once SYS."/system/lib/validate.func.php";
require_once SYS."/system/lib/sql.func.php";

require_once SELF."/class/report_parent.class.php";
require_once SELF."/class/paid_v2.class.php";
require_once SELF."/class/split_v2.class.php";
require_once SELF."/class/natural_v2.class.php";

$nsLang->TplInc("inc/report_headers");

$Today=UserDate();


$Query = "SELECT * FROM ".PFX."_tracker_client ORDER BY NAME";
$Sql = new Query($Query);
$Clients=array();
while ($Row=$Sql->Row()) {
	$Clients[]=$Row;
}



$ShowStats=false;
UserColumns(); $CampList=array();
$SplitList=array();
$GrpList=array();

$Query = "
	SELECT 
		WS.SUB_ID, CP.NAME,
		SC.ID AS CAMP_ID,
		SS.ID AS SPLIT_ID,
		CL.NAME AS CLIENT_NAME,
		CP.COMPANY_ID
		FROM ".PFX."_tracker_watch WS
			INNER JOIN ".PFX."_tracker_camp_piece CP
				ON CP.ID=WS.SUB_ID
			INNER JOIN ".PFX."_tracker_client CL
				ON CL.ID=CP.COMPANY_ID
			LEFT JOIN ".PFX."_tracker_sub_campaign SC
				ON SC.SUB_ID=WS.SUB_ID
			LEFT JOIN ".PFX."_tracker_split_test SS
				ON SS.SUB_ID=WS.SUB_ID
		WHERE WS.USER_ID=".$nsUser->UserId();
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$ShowStats=true;
	if ($Row->CAMP_ID) {
		if (!$nsUser->Columns->CLICKS&&!$nsUser->Columns->ROI&&
			!$nsUser->Columns->CONVERSIONS) continue;
		$CampList[$Row->SUB_ID]=new Paid_v2();
		//$CampList[$Row->SUB_ID]->EnableAll();
		if ($nsUser->Columns->CLICKS) $CampList[$Row->SUB_ID]->ShowVisitors=true;
		if ($nsUser->Columns->ROI) $CampList[$Row->SUB_ID]->ShowROI=true;
		if ($nsUser->Columns->CONVERSIONS) $CampList[$Row->SUB_ID]->ShowActionConv=true;
		if ($nsUser->Columns->CONVERSIONS) $CampList[$Row->SUB_ID]->ShowSaleConv=true;
		$CampList[$Row->SUB_ID]->CampId=$Row->SUB_ID;
		$CampList[$Row->SUB_ID]->CpId=$Row->COMPANY_ID;
		$CampList[$Row->SUB_ID]->Name=$Row->NAME;
		$CampList[$Row->SUB_ID]->ShowPerClick=true;
		$CampList[$Row->SUB_ID]->ShowTotalCost=true;
		$CampList[$Row->SUB_ID]->Calculate();
		$CampList[$Row->SUB_ID]->ClientName=$Row->CLIENT_NAME;
	}
	if ($Row->SPLIT_ID) {
		if (!$nsUser->Columns->CLICKS&&!$nsUser->Columns->ACTIONS&&
			!$nsUser->Columns->SALES&&!$nsUser->Columns->CONVERSIONS) continue;
		$SplitList[$Row->SUB_ID]=new SplitStat_v2();
		//$SplitList[$Row->SUB_ID]->EnableAll();
		if ($nsUser->Columns->CLICKS) $SplitList[$Row->SUB_ID]->ShowVisitors=true;
		if ($nsUser->Columns->ACTIONS) $SplitList[$Row->SUB_ID]->ShowActions=true;
		if ($nsUser->Columns->SALES) $SplitList[$Row->SUB_ID]->ShowSales=true;
		if ($nsUser->Columns->CONVERSIONS) $SplitList[$Row->SUB_ID]->ShowActionConv=true;
		if ($nsUser->Columns->CONVERSIONS) $SplitList[$Row->SUB_ID]->ShowSaleConv=true;
		$SplitList[$Row->SUB_ID]->SplitId=$Row->SUB_ID;
		$SplitList[$Row->SUB_ID]->CpId=$Row->COMPANY_ID;
		$SplitList[$Row->SUB_ID]->Name=$Row->NAME;
		$SplitList[$Row->SUB_ID]->Calculate();
		$SplitList[$Row->SUB_ID]->ClientName=$Row->CLIENT_NAME;
	}
}

if ($nsUser->Columns->CLICKS || 
	$nsUser->Columns->ROI || 
	$nsUser->Columns->CONVERSIONS) {
	$Query = "
	SELECT 
	WG.GRP_ID, C.NAME, CL.NAME AS CLIENT_NAME, C.COMPANY_ID
	FROM ".PFX."_tracker_watch WG
		INNER JOIN ".PFX."_tracker_campaign C
			ON C.ID=WG.GRP_ID
		INNER JOIN ".PFX."_tracker_client CL
			ON CL.ID=C.COMPANY_ID
	WHERE WG.USER_ID=".$nsUser->UserId();
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$ShowStats=true;
	$GrpList[$Row->GRP_ID]=new Paid_v2();
	$GrpList[$Row->GRP_ID]->GrpId=$Row->GRP_ID;
	$GrpList[$Row->GRP_ID]->CpId=$Row->COMPANY_ID;
	$GrpList[$Row->GRP_ID]->ShowPerClick=true;
	$GrpList[$Row->GRP_ID]->ShowTotalCost=true;

	//$GrpList[$Row->GRP_ID]->EnableAll();
	if ($nsUser->Columns->CLICKS) $GrpList[$Row->GRP_ID]->ShowVisitors=true;
	if ($nsUser->Columns->ROI) $GrpList[$Row->GRP_ID]->ShowROI=true;
	if ($nsUser->Columns->CONVERSIONS) $GrpList[$Row->GRP_ID]->ShowActionConv=true;
	if ($nsUser->Columns->CONVERSIONS) $GrpList[$Row->GRP_ID]->ShowSaleConv=true;
	$GrpList[$Row->GRP_ID]->Name=$Row->NAME;
	$GrpList[$Row->GRP_ID]->ClientName=$Row->CLIENT_NAME;
	$GrpList[$Row->GRP_ID]->Calculate();
}

}



$CompList=array();
$SiteList=array();

if ($nsUser->Columns->CLICKS || 
	$nsUser->Columns->HITS || 
	$nsUser->Columns->ACTIONS) {


$Query = "
	SELECT WC.COMPANY_ID, TC.NAME
	FROM ".PFX."_tracker_watch WC
		INNER JOIN ".PFX."_tracker_client TC ON TC.ID=WC.COMPANY_ID
	WHERE WC.USER_ID=".$nsUser->UserId();
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$ShowStats=true;
	$CompList[$Row->COMPANY_ID] = new Natural_v2();
	$CompList[$Row->COMPANY_ID]->CpId=$Row->COMPANY_ID;
	//$CompList[$Row->COMPANY_ID]->NoRef=true;
	$CompList[$Row->COMPANY_ID]->Name=$Row->NAME;
	$CompList[$Row->COMPANY_ID]->ViewDate=$Today;
	if ($nsUser->Columns->ACTIONS) $CompList[$Row->COMPANY_ID]->ShowActions=true;
	if ($nsUser->Columns->HITS) $CompList[$Row->COMPANY_ID]->ShowVisitors=true;
	$CompList[$Row->COMPANY_ID]->Calculate();

	if ($nsUser->Columns->CLICKS) {
		$Tmp=new Paid_v2();
		$Tmp->ViewDate=$Today;
		$Tmp->ShowVisitors=true;
		$Tmp->CpId=$Row->COMPANY_ID;
		$Tmp->Calculate();
		$CompList[$Row->COMPANY_ID]->StatArr['CntCamp']=$Tmp->CampStat['CntClick'];
		$CompList[$Row->COMPANY_ID]->StatArr['UniCamp']=$Tmp->CampStat['UniClick'];
		$CompList[$Row->COMPANY_ID]->StatArr['Camp']=$Tmp->CampStat['Camp'];
	}
}

$Query = "
	SELECT WS.SITE_ID, TS.HOST, C.NAME AS CLIENT_NAME, C.ID AS CLIENT_ID
	FROM ".PFX."_tracker_watch WS
		INNER JOIN ".PFX."_tracker_site TS ON TS.ID=WS.SITE_ID
		INNER JOIN ".PFX."_tracker_client C ON C.ID=TS.COMPANY_ID
	WHERE WS.USER_ID=".$nsUser->UserId()."
	ORDER BY C.NAME ASC
	";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$ShowStats=true;
	$SiteList[$Row->SITE_ID] = new Natural_v2();
	//$SiteList[$Row->SITE_ID]->NoRef=true;
	if ($nsUser->Columns->HITS) $SiteList[$Row->SITE_ID]->ShowVisitors=true;
	if ($nsUser->Columns->ACTIONS) $SiteList[$Row->SITE_ID]->ShowActions=true;
	$SiteList[$Row->SITE_ID]->SiteId=$Row->SITE_ID;
	$SiteList[$Row->SITE_ID]->CpId=$Row->CLIENT_ID;
	$SiteList[$Row->SITE_ID]->Name=$Row->HOST;
	$SiteList[$Row->SITE_ID]->ClientName=$Row->CLIENT_NAME;
	$SiteList[$Row->SITE_ID]->ViewDate=$Today;
	$SiteList[$Row->SITE_ID]->NoRef=true;
	$SiteList[$Row->SITE_ID]->Calculate();

	if ($nsUser->Columns->CLICKS) {
		$Tmp=new Paid_v2();
		$Tmp->ViewDate=$Today;
		$Tmp->CpId=$Row->CLIENT_ID;
		$Tmp->ShowVisitors=true;
		$Tmp->SiteId=$Row->SITE_ID;
		$Tmp->Calculate();
		$SiteList[$Row->SITE_ID]->StatArr['CntCamp']=$Tmp->CampStat['CntClick'];
		$SiteList[$Row->SITE_ID]->StatArr['UniCamp']=$Tmp->CampStat['UniClick'];
		$SiteList[$Row->SITE_ID]->StatArr['Camp']=$Tmp->CampStat['Camp'];
	}
}


}


include $nsTemplate->Inc();
?>