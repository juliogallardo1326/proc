<?
if (ValidVar($GroupBy)=="Site") {
	$Report->SelectArr[]="TS.ID";
	$Report->SelectArr[]="TS.HOST AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_site TS ON TS.ID=S_LOG.SITE_ID";
	$Report->GroupArr[]="TS.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="TS.HOST LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="Host") {
	$Report->SelectArr[]="TSH.ID";
	$Report->SelectArr[]="TSH.HOST AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_site_host TSH ON TSH.ID=S_LOG.SITE_HOST_ID";
	$Report->GroupArr[]="TSH.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="TSH.HOST LIKE ('%$Filter%')";
}


if (ValidVar($GroupBy)=="Grp") {
	$Report->SelectArr[]="C.ID";
	$Report->SelectArr[]="C.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_camp_piece CP ON CP.ID=S_CLICK.CAMP_ID";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_campaign C ON C.ID=CP.CAMPAIGN_ID";
	$Report->GroupArr[]="C.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->GroupMode="GRP";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="C.NAME LIKE ('%$Filter%')";
	$Report->ShowPerClick=true;
	if (!$Report->StartDate&&!$Report->EndDate&&!$Report->ViewDate
		&&(count($WhereArr)<2||$WhereArr[0]['Mode']=="Camp")) {
		$Report->ShowTotalCost=true;
	}
}

if (ValidVar($GroupBy)=="Camp") {
	$Report->SelectArr[]="CP.ID";
	$Report->SelectArr[]="CP.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_camp_piece CP ON CP.ID=S_CLICK.CAMP_ID";
	$Report->GroupArr[]="CP.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->GroupMode="CAMP";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="CP.NAME LIKE ('%$Filter%')";
	$Report->ShowPerClick=true;
	if (!$Report->StartDate&&!$Report->EndDate&&!$Report->ViewDate
		&&(count($WhereArr)<2||$WhereArr[0]['Mode']=="Grp")) {
		$Report->ShowTotalCost=true;
	}
}

if (ValidVar($GroupBy)=="Split") {
	$Report->SelectArr[]="CP.ID";
	$Report->SelectArr[]="CP.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_".$Report->CpId."_stat_split S_SPLIT ON S_SPLIT.LOG_ID=S_LOG.ID";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_camp_piece CP ON CP.ID=S_SPLIT.SPLIT_ID";
	$Report->GroupArr[]="CP.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	$Report->CookieOnly=true;
	if (ValidVar($Filter)) $Report->WhereArr[]="CP.NAME LIKE ('%$Filter%')";
}


if (ValidVar($GroupBy)=="CampSource") {
	$Report->SelectArr[]="TH.ID";
	$Report->SelectArr[]="TH.HOST AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_host TH ON TH.ID=S_CLICK.SOURCE_HOST_ID";
	$Report->GroupArr[]="TH.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="TH.HOST LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="CampRef") {
	$Report->SelectArr[]="RS.REFERER_ID";
	$Report->SelectArr[]="R.REFERER AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_referer_set RS ON RS.ID=S_LOG.REFERER_SET";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_referer R ON R.ID=RS.REFERER_ID";
	$Report->GroupArr[]="RS.REFERER_ID";
	$Report->GrpFld="REFERER_ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="R.REFERER  LIKE ('%$Filter%')";
}


if (ValidVar($GroupBy)=="CampKey") {
	$Report->SelectArr[]="K.ID";
	$Report->SelectArr[]="K.KEYWORD AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_keyword K ON K.ID=S_CLICK.KEYWORD_ID";
	$Report->GroupArr[]="K.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="K.KEYWORD LIKE ('%$Filter%')";

}



if (ValidVar($GroupBy)=="Ref") {
	$Report->RsNeeded=true;
	$Report->SelectArr[]="RS.REFERER_ID";
	$Report->SelectArr[]="R.REFERER AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_referer R ON R.ID=RS.REFERER_ID";
	$Report->GroupArr[]="RS.REFERER_ID";
	$Report->GrpFld="REFERER_ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="R.REFERER LIKE ('%$Filter%')";
}



if (ValidVar($GroupBy)=="Source") {
	$Report->RsNeeded=true;
	$Report->SelectArr[]="TH.ID";
	$Report->SelectArr[]="TH.HOST AS NAME";
	if (!ValidId($Report->HostGrp)) $Report->JoinArr[]="INNER JOIN ".PFX."_tracker_host TH ON TH.ID=RS.HOST_ID";
	$Report->GroupArr[]="TH.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="TH.HOST LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="SourceGrp") {
	$Report->RsNeeded=true;
	$Report->SelectArr[]="TH.GRP_ID";
	$Report->SelectArr[]="HG.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_host TH ON TH.ID=RS.HOST_ID";
	$Report->JoinArr[]="LEFT JOIN ".PFX."_tracker_host_grp HG ON HG.ID=TH.GRP_ID";
	$Report->GroupArr[]="TH.GRP_ID";
	$Report->GrpFld="GRP_ID";
	$Report->GrpName="NAME";
	$Report->NullGrpName=$Lang['OtherSource'];
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="HG.NAME LIKE ('%$Filter%')";
}


if (ValidVar($GroupBy)=="Key") {
	$Report->RsNeeded=true;
	$Report->SelectArr[]="K.ID";
	$Report->SelectArr[]="K.KEYWORD AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_keyword K ON K.ID=RS.NATURAL_KEY";
	$Report->GroupArr[]="K.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="K.KEYWORD LIKE ('%$Filter%')";
}


if (ValidVar($GroupBy)=="Vis") {
	$Node=($ShowAll)?"S_LOG":"NODE";
	$Report->SelectArr[]="S_LOG.VISITOR_ID";
	$Report->SelectArr[]="I.IP AS NAME";
	$Report->SelectArr[]="CV.NAME AS VISITOR_NAME";	
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
	$Report->JoinArr[]="LEFT JOIN ".PFX."_tracker_client_visitor CV ON CV.VISITOR_ID=S_LOG.VISITOR_ID AND CV.COMPANY_ID=$CpId";	
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_ip I ON I.ID=S_LOG.IP_ID";
	$Report->GroupArr[]="S_LOG.VISITOR_ID";
	$Report->GrpFld="VISITOR_ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="I.IP LIKE ('%$Filter%') OR CV.NAME LIKE ('%$Filter%')";
}



if (ValidVar($GroupBy)=="Action") {
	$Report->ShowVisitors=false;
	$Report->ShowSales=false;
	$Report->ShowROI=false;
	$Report->ShowActionConv=false;
	$Report->ShowPrevActionConv=true;
	$Report->ShowSaleConv=false;
	$Report->NoROICalc=true;
	$Report->SelectArr[]="S_ACTION.ACTION_ID";
	$Report->SelectArr[]="VA.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_action VA  ON VA.ID=S_ACTION.ACTION_ID";
	$Report->GroupArr[]="S_ACTION.ACTION_ID";
	$Report->GrpFld="ACTION_ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="VA.NAME LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="ActionItem") {
	$Report->ShowVisitors=false;
	$Report->ShowSales=false;
	$Report->ShowROI=false;
	$Report->ShowActionConv=false;
	$Report->ShowPrevActionConv=true;
	$Report->ShowSaleConv=false;
	$Report->NoROICalc=true;
	$Report->SelectArr[]="ACS.ACTION_ITEM_ID";
	$Report->SelectArr[]="AI.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_action VA  ON VA.ID=S_ACTION.ACTION_ID";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_action_set ACS  ON ACS.STAT_ACTION_ID=S_ACTION.ID";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_action_item AI  ON AI.ID=ACS.ACTION_ITEM_ID";
	$Report->WhereArr[]="ACS.COMPANY_ID=$CpId";
	$Report->WhereArr[]="AI.COMPANY_ID=$CpId";
	$Report->GroupArr[]="ACS.ACTION_ITEM_ID";
	$Report->GrpFld="ACTION_ITEM_ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="AI.NAME LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="Order") {
	$Report->ShowVisitors=false;
	$Report->ShowActions=false;
	$Report->ShowROI=false;
	$Report->ShowActionConv=false;
	$Report->ShowSaleConv=false;
	$Report->NoROICalc=true;
	$Report->SelectArr[]="S_SALE.ID";
	$Report->SelectArr[]="S_SALE.CUSTOM_ORDER_ID";
	$Report->SelectArr[]="'".$Lang['OrderNo']."' AS NAME";
	$Report->GroupArr[]="S_SALE.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
}


if (ValidVar($GroupBy)=="Sale") {
	$Report->ShowVisitors=false;
	$Report->ShowActions=false;
	$Report->ShowROI=false;
	$Report->ShowActionConv=false;
	$Report->ShowSaleConv=false;
	$Report->NoROICalc=true;
	$Report->SelectArr[]="SS.ITEM_ID";
	$Report->SelectArr[]="SI.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_sale_set SS  ON SS.SALE_ID=S_SALE.ID";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_sale_item SI  ON SI.ID=SS.ITEM_ID";
	$Report->GroupArr[]="SS.ITEM_ID";
	$Report->GrpFld="ITEM_ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	$Report->WhereArr[]="SS.COMPANY_ID=".$Report->CpId;
	$Report->WhereArr[]="SI.COMPANY_ID=".$Report->CpId;
	if (ValidVar($Filter)) $Report->WhereArr[]="SI.NAME LIKE ('%$Filter%')";
}


if (ValidVar($GroupBy)=="Date") {
	$Node=($ShowAll)?"S_LOG":"NODE";
	$DateTempl="%Y-%m-%d";
	$Report->DateTempl=$DateTempl;
	$Report->SelectArr[]="DATE_FORMAT(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '$DateTempl') AS NAME";
	$Report->SelectArr[]="UNIX_TIMESTAMP(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) AS USTAMP";
	$Report->GroupArr[]="1";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	$Report->GrpFld="NAME";
	$Report->GrpName="NAME";
}

if (ValidVar($GroupBy)=="Month") {
	$Node=($ShowAll)?"S_LOG":"NODE";
	$DateTempl="%Y-%m";
	$Report->DateTempl=$DateTempl;	
	$Report->SelectArr[]="DATE_FORMAT(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '$DateTempl') AS NAME";
	$Report->SelectArr[]="UNIX_TIMESTAMP(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) AS USTAMP";
	$Report->GroupArr[]="1";
	$Report->GrpFld="NAME";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
}

if (ValidVar($GroupBy)=="Time") {
	$Node=($ShowAll)?"S_LOG":"NODE";
	$DateTempl="%H";
	$Report->DateTempl=$DateTempl;	
	$Report->SelectArr[]="DATE_FORMAT(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '$DateTempl') AS NAME";
	$Report->SelectArr[]="UNIX_TIMESTAMP(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) AS USTAMP";
	$Report->GroupArr[]="1";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	$Report->GrpFld="NAME";
	$Report->GrpName="NAME";
}

if (ValidVar($GroupBy)=="Year") {
	$Node=($ShowAll)?"S_LOG":"NODE";
	$DateTempl="%Y";
	$Report->DateTempl=$DateTempl;	
	$Report->SelectArr[]="DATE_FORMAT(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '$DateTempl') AS NAME";
	$Report->SelectArr[]="UNIX_TIMESTAMP(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) AS USTAMP";
	$Report->GroupArr[]="1";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	$Report->GrpFld="NAME";
	$Report->GrpName="NAME";
}

if (ValidVar($GroupBy)=="WeekDay") {
	$Node=($ShowAll)?"S_LOG":"NODE";
	$DateTempl="%w";
	$Report->DateTempl=$DateTempl;	
	$Report->SelectArr[]="DATE_FORMAT(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '$DateTempl') AS NAME";
	$Report->SelectArr[]="UNIX_TIMESTAMP(DATE_ADD($Node.STAMP, INTERVAL '".$nsUser->TZ."' HOUR)) AS USTAMP";
	$Report->GroupArr[]="1";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	$Report->GrpFld="NAME";
	$Report->GrpName="NAME";
}



if (ValidVar($GroupBy)=="AgentGrp") {
	$Report->SelectArr[]="VAG.GRP_ID";
	$Report->SelectArr[]="AG.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_agent VAG ON VAG.ID=S_LOG.AGENT_ID";
	$Report->JoinArr[]="LEFT JOIN ".PFX."_tracker_visitor_agent_grp AG ON AG.ID=VAG.GRP_ID";
	$Report->GroupArr[]="VAG.GRP_ID";
	$Report->GrpFld="GRP_ID";
	$Report->GrpName="NAME";
	$Report->NullGrpName=$Lang['OtherAgentGrp'];
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="AG.NAME LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="Agent") {
	$Report->SelectArr[]="S_LOG.AGENT_ID";
	$Report->SelectArr[]="VAG.USER_AGENT AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_agent VAG ON VAG.ID=S_LOG.AGENT_ID";
	$Report->GroupArr[]="S_LOG.AGENT_ID";
	$Report->GrpFld="AGENT_ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="VAG.USER_AGENT LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="Page") {
	$Report->ByPage=true;
	$Report->SelectArr[]="S_LOG.PAGE_ID";
	$Report->SelectArr[]="SP.PATH AS NAME";
	$Report->SelectArr[]="S2.HOST AS SITE_HOST";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_site_page SP ON SP.ID=S_LOG.PAGE_ID";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_site_host S2 ON S2.ID=S_LOG.SITE_HOST_ID";
	$Report->GroupArr[]="S_LOG.PAGE_ID";
	$Report->GrpFld="PAGE_ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) {
		$FilterStr=$Filter;
		if (substr($FilterStr, 0,1)!="^") $FilterStr="%".$FilterStr;
		else $FilterStr=substr($FilterStr, 1);
		if (substr($FilterStr, -1)!="^") $FilterStr.="%";
		else $FilterStr=substr($FilterStr, 0,-1);
		$Report->WhereArr[]="SP.PATH LIKE ('$FilterStr')";
	}
}


if (ValidVar($GroupBy)=="Country") {
	$Report->SelectArr[]="CO.ID";
	$Report->SelectArr[]="CO.NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_country CO ON CO.ID=V.FIRST_COUNTRY_ID";
	$Report->GroupArr[]="CO.ID";
	$Report->GrpFld="ID";
	$Report->GrpName="NAME";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	if (ValidVar($Filter)) $Report->WhereArr[]="VAG.USER_AGENT LIKE ('%$Filter%')";
}


if (ValidVar($GroupBy)=="Resolution") {
	$Report->SelectArr[]="V.LAST_RESOLUTION";
	$Report->SelectArr[]="V.LAST_RESOLUTION AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
	$Report->GroupArr[]="V.LAST_RESOLUTION";
	$Report->GrpFld="LAST_RESOLUTION";
	$Report->GrpName="LAST_RESOLUTION";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	 $Report->WhereArr[]="V.LAST_RESOLUTION != '' ";
	if (ValidVar($Filter)) $Report->WhereArr[]="V.LAST_RESOLUTION LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="Pixel") {
	$Report->SelectArr[]="V.PIXEL_DEPTH";
	$Report->SelectArr[]="V.PIXEL_DEPTH AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
	$Report->GroupArr[]="V.PIXEL_DEPTH";
	$Report->GrpFld="PIXEL_DEPTH";
	$Report->GrpName="PIXEL_DEPTH";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	 $Report->WhereArr[]="V.PIXEL_DEPTH != 0 ";
	if (ValidVar($Filter)) $Report->WhereArr[]="V.PIXEL_DEPTH LIKE ('%$Filter%')";
}

if (ValidVar($GroupBy)=="Flash") {
	$Report->SelectArr[]="V.FLASH_VERSION";
	$Report->SelectArr[]="V.FLASH_VERSION AS NAME";
	$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
	$Report->GroupArr[]="V.FLASH_VERSION";
	$Report->GrpFld="FLASH_VERSION";
	$Report->GrpName="FLASH_VERSION";
	$Report->OrderArr[]="$DefaultOrderBy $OrderTo";
	 $Report->WhereArr[]="V.FLASH_VERSION != '' ";
	if (ValidVar($Filter)) $Report->WhereArr[]="V.FLASH_VERSION LIKE ('%$Filter%')";
}

?>