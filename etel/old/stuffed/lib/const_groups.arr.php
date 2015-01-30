<?

$NaturalConstPath['Site']="NAT_GRP_SITE";
$NaturalConstPath['Host']="NAT_GRP_HOST";
$NaturalConstPath['SourceGrp']="NAT_GRP_SGRP";
$NaturalConstPath['Source']="NAT_GRP_SOURCE";
$NaturalConstPath['Ref']="NAT_GRP_REF";
$NaturalConstPath['Key']="NAT_GRP_KEY";
$NaturalConstPath['Action']="NAT_GRP_ACTION";
$NaturalConstPath['ActionItem']="NAT_GRP_AITEM";
$NaturalConstPath['Order']="NAT_GRP_ORDER";
$NaturalConstPath['Sale']="NAT_GRP_SALE";
$NaturalConstPath['Year']="NAT_GRP_YEAR";
$NaturalConstPath['Month']="NAT_GRP_MONTH";
$NaturalConstPath['Date']="NAT_GRP_DATE";
$NaturalConstPath['WeekDay']="NAT_GRP_WEEK";
$NaturalConstPath['Time']="NAT_GRP_TIME";
$NaturalConstPath['Vis']="NAT_GRP_VIS";
$NaturalConstPath['AgentGrp']="NAT_GRP_UA_GRP";
$NaturalConstPath['Agent']="NAT_GRP_UA";
$NaturalConstPath['Page']="NAT_GRP_PAGE";
$NaturalConstPath['Split']="NAT_GRP_SPLIT";
$NaturalConstPath['Country']="NAT_COUNTRY";
$NaturalConstPath['Resolution']="NAT_RESOLUTION";
$NaturalConstPath['Pixel']="NAT_PIXEL";
$NaturalConstPath['Flash']="NAT_FLASH";

$PaidConstPath['Site']="PAID_GRP_SITE";
$PaidConstPath['Host']="PAID_GRP_HOST";
$PaidConstPath['Grp']="PAID_GRP_GRP";
$PaidConstPath['Camp']="PAID_GRP_CAMP";
$PaidConstPath['CampSource']="PAID_GRP_SRC";
$PaidConstPath['CampRef']="PAID_GRP_REF";
$PaidConstPath['CampKey']="PAID_GRP_KEY";
$PaidConstPath['Action']="PAID_GRP_ACTION";
$PaidConstPath['ActionItem']="PAID_GRP_AITEM";
$PaidConstPath['Order']="PAID_GRP_ORDER";
$PaidConstPath['Sale']="PAID_GRP_SALE";
$PaidConstPath['Year']="PAID_GRP_YEAR";
$PaidConstPath['Month']="PAID_GRP_MONTH";
$PaidConstPath['Date']="PAID_GRP_DATE";
$PaidConstPath['WeekDay']="PAID_GRP_WEEK";
$PaidConstPath['Time']="PAID_GRP_TIME";
$PaidConstPath['Vis']="PAID_GRP_VIS";
$PaidConstPath['AgentGrp']="PAID_GRP_UA_GRP";
$PaidConstPath['Agent']="PAID_GRP_UA";
$PaidConstPath['Page']="PAID_GRP_PAGE";
$PaidConstPath['Split']="PAID_GRP_SPLIT";
$PaidConstPath['Country']="PAID_COUNTRY";
$PaidConstPath['Resolution']="PAID_RESOLUTION";
$PaidConstPath['Pixel']="PAID_PIXEL";
$PaidConstPath['Flash']="PAID_FLASH";

////////////////////////////////////////////////
$SelectOrder=array();
$Inx=0;
$SelectGrp[0]['Name']=$Lang['SelGrpSite'];
$Inx=array_push($SelectOrder, "Site", "Host", "Page");
$SelectGrp[0]['Punkt']=range(0, $Inx-1);
$PrevInx=$Inx;

$SelectGrp[1]['Name']=$Lang['SelGrpCamp'];
$Inx=array_push($SelectOrder, "Grp", "Camp", "Split", "CampSource", "CampRef", "CampKey");
$SelectGrp[1]['Punkt']=range($PrevInx, $Inx-1);
$PrevInx=$Inx;

$SelectGrp[2]['Name']=$Lang['SelGrpRef'];
$Inx=array_push($SelectOrder, "SourceGrp", "Source", "Key", "Ref");
$SelectGrp[2]['Punkt']=range($PrevInx, $Inx-1);
$PrevInx=$Inx;

$SelectGrp[3]['Name']=$Lang['SelGrpDate'];
$Inx=array_push($SelectOrder, "Year", "Month", "Date", "WeekDay", "Time");
$SelectGrp[3]['Punkt']=range($PrevInx, $Inx-1);
$PrevInx=$Inx;

$SelectGrp[4]['Name']=$Lang['SelGrpAction'];
$Inx=array_push($SelectOrder, "Action", "ActionItem");
$SelectGrp[4]['Punkt']=range($PrevInx, $Inx-1);
$PrevInx=$Inx;

$SelectGrp[5]['Name']=$Lang['SelGrpSale'];
$Inx=array_push($SelectOrder, "Order", "Sale");
$SelectGrp[5]['Punkt']=range($PrevInx, $Inx-1);
$PrevInx=$Inx;

$SelectGrp[6]['Name']=$Lang['SelGrpVis'];
$Inx=array_push($SelectOrder, "AgentGrp", "Agent", "Vis", "Country", "Resolution", "Pixel", "Flash");
$SelectGrp[6]['Punkt']=range($PrevInx, $Inx-1);
$PrevInx=$Inx;


///////////////////////////////////////////////

function ImportReport($Id=false)
{
	if (!$Id) return false;
	global $Db, $nsUser, $CpId, $nsProduct;
	$Where="";
	if (!$nsUser->ADMIN) $Where="AND COMPANY_ID=".$nsUser->COMPANY_ID;
	$Query = "SELECT * FROM ".PFX."_tracker_user_report WHERE ID = $Id $Where";
	$Import=$Db->Select($Query);
	if (!ValidId($Import->ID)) return false;
	if ($Import->COMPANY_ID>0&&$Import->COMPANY_ID!=$CpId) {
		Redir($nsProduct->SelfAction("CpId=".$Import->COMPANY_ID."&ConstId=".$Import->ID));
	}
	
	$WhereArr=unserialize($Import->WHERE_ARR);
	if (!ValidArr($WhereArr)) return false;

	for($i=0;$i<count($WhereArr);$i++) {
		if (!ValidVar($WhereArr[$i]['OrderBy'])) $WhereArr[$i]['OrderBy']="CNT";
		if (!ValidVar($WhereArr[$i]['OrderTo'])) $WhereArr[$i]['OrderTo']="DESC";
	}

	$GLOBALS['WhereArr']=$WhereArr;
	
	$DatesUsed=false;
	for($i=0;$i<count($WhereArr);$i++) {
		if ($WhereArr[$i]['Mode']=="Month"||$WhereArr[$i]['Mode']=="Date") {$DatesUsed=true;break;}
	}

	if (!$DatesUsed) {
		$ViewDate=($Import->VIEW_DATE!="0000-00-00"&&ValidDate($Import->VIEW_DATE))? $Import->VIEW_DATE:false;
		$StartDate=($Import->START_DATE!="0000-00-00"&&ValidDate($Import->START_DATE))? $Import->START_DATE:false;
		$EndDate=($Import->END_DATE!="0000-00-00"&&ValidDate($Import->END_DATE))? $Import->END_DATE:false;
		if ($ViewDate&&$Import->USE_CURRENT_DATE) $ViewDate=date("Y-m-d");
		if ($EndDate&&$Import->USE_CURRENT_DATE) $EndDate=date("Y-m-d");
		if ($Import->USE_CURRENT_DATE&&!$ViewDate&&!$EndDate) $ViewDate=date("Y-m-d");
		$GLOBALS['ViewDate']=$ViewDate;
		$GLOBALS['StartDate']=$StartDate;
		$GLOBALS['EndDate']=$EndDate;
	}

	$GLOBALS['Limit']=(ValidVar($Import->PAGE_LIMIT)>0)?$Import->PAGE_LIMIT:false;
	$GLOBALS['Filter']=ValidVar($Import->FILTER);
	$GLOBALS['ShowAll']=($Import->SHOW_NO_REF==1)?1:false;
	$GLOBALS['OrderBy']=ValidVar($Import->SORT_BY);
	$GLOBALS['OrderTo']=$Import->SORT_ORDER;
	$GLOBALS['GroupBy']=ValidVar($Import->GROUP_BY);

	return true;
}


?>