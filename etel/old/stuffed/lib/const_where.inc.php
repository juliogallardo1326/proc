<?
/////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
for($i=0;$i<count($WhereArr);$i++) {
	$ShowListName=false;
	if (!isset($WhereArr[$i]['Id'])||$WhereArr[$i]['Id']=="") $WhereArr[$i]['Id']=false;
	if (isset($CurrentId[$WhereArr[$i]['Mode']])&&$CurrentId[$WhereArr[$i]['Mode']]!="") {
		$WhereArr[$i]['Id']=$CurrentId[$WhereArr[$i]['Mode']];
	}
	if (isset($WhereArr[$i]['Id'])&&$WhereArr[$i]['Id']!="") {
		$WhereForm[$WhereArr[$i]['Mode']]=1;
		$LastMode=$WhereArr[$i]['Mode'];
	}

	$WhereArr[$i]['Url']=GetConstPath($WhereArr, $i);
	$WhereArr[$i]['Url2']=GetConstPath($WhereArr, $i, false);

	if ($WhereArr[$i]['Mode']=="Month") {
		$WhereArr[$i]['Name']=$Lang['ByMonths'];
		$MetaTitle.=": ".$Lang['ByMonths'];
		if (ValidVar($WhereArr[$i]['Id'])!==false) {
			$RowMonth=explode("-", $WhereArr[$i]['Id']);
			$WhereArr[$i]['Name2']=ValidVar($Lang['MonthName'][ ValidVar($RowMonth[1]) ] );
			$Report->WhereArr[]="DATE_FORMAT(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '%Y-%m')='".$WhereArr[$i]['Id']."'";
			unset($RowMonth);
		}
	}

	if ($WhereArr[$i]['Mode']=="Date") {
		$WhereArr[$i]['Name']=$Lang['ByDays'];
		$MetaTitle.=": ".$Lang['ByDays'];
		if (ValidVar($WhereArr[$i]['Id'])!==false) {
			$WhereArr[$i]['Name2']=$WhereArr[$i]['Id'];
			$Report->WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) BETWEEN '".$WhereArr[$i]['Id']." 00:00:00' AND '".$WhereArr[$i]['Id']." 23:59:59'";
		}
	}

	if ($WhereArr[$i]['Mode']=="Time") {
		$WhereArr[$i]['Name']=$Lang['ByTime'];
		$MetaTitle.=": ".$Lang['ByTime'];
		if (ValidVar($WhereArr[$i]['Id'])!==false) {
			$WhereArr[$i]['Name2']=$WhereArr[$i]['Id'];
			$Report->WhereArr[]="DATE_FORMAT(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '%H')=".$WhereArr[$i]['Id'];
		}
	}


	if ($WhereArr[$i]['Mode']=="Year") {
		$WhereArr[$i]['Name']=$Lang['ByYear'];
		$MetaTitle.=": ".$Lang['ByYear'];
		if (ValidVar($WhereArr[$i]['Id'])!==false) {
			$WhereArr[$i]['Name2']=$WhereArr[$i]['Id'];
			$Report->WhereArr[]="DATE_FORMAT(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '%Y')=".$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="WeekDay") {
		$WhereArr[$i]['Name']=$Lang['ByWeek'];
		$MetaTitle.=": ".$Lang['ByWeek'];
		if (ValidVar($WhereArr[$i]['Id'])!==false) {
			$WhereArr[$i]['Name2']=$Lang['DayOfWeek'][$WhereArr[$i]['Id']];
			$Report->WhereArr[]="DATE_FORMAT(DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR), '%w')=".$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="Site") {
		$WhereArr[$i]['Name']=$Lang['BySites'];
		$MetaTitle.=": ".$Lang['BySites'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT HOST FROM ".PFX."_tracker_site WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->SiteId=$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="Host") {
		$WhereArr[$i]['Name']=$Lang['ByHost'];
		$MetaTitle.=": ".$Lang['ByHost'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT HOST FROM ".PFX."_tracker_site_host WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="S_LOG.SITE_HOST_ID=".$WhereArr[$i]['Id'];
		}
	}


	if ($WhereArr[$i]['Mode']=="Grp") {
		$WhereArr[$i]['Name']=$Lang['ByCampGrp'];
		$MetaTitle.=": ".$Lang['ByCampGrp'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_campaign WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->GrpId=$WhereArr[$i]['Id'];
		}
	}
	if ($WhereArr[$i]['Mode']=="Camp") {
		$WhereArr[$i]['Name']=$Lang['ByCamp'];
		$MetaTitle.=": ".$Lang['ByCamp'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_camp_piece WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->CampId=$WhereArr[$i]['Id'];
		}
		$Report->ShowPerClick=true;
	}

	if ($WhereArr[$i]['Mode']=="Split") {
		$WhereArr[$i]['Name']=$Lang['BySplit'];
		$MetaTitle.=": ".$Lang['BySplit'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_camp_piece WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_".$Report->CpId."_stat_split S_SPLIT ON S_SPLIT.LOG_ID=S_LOG.ID";
			$Report->WhereArr[]="S_SPLIT.SPLIT_ID=".$WhereArr[$i]['Id'];
			$Report->CookieOnly=true;

		}
	}

	if ($WhereArr[$i]['Mode']=="CampSource") {
		$WhereArr[$i]['Name']=$Lang['ByCampSource'];
		$MetaTitle.=": ".$Lang['ByCampSource'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT HOST FROM ".PFX."_tracker_host WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->HostId=$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="CampRef") {
		$WhereArr[$i]['Name']=$Lang['ByCampRef'];
		$MetaTitle.=": ".$Lang['ByCampRef'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=htmlspecialchars(urldecode($Db->ReturnValue("SELECT REFERER FROM ".PFX."_tracker_referer WHERE ID = ".$WhereArr[$i]['Id'])));
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_referer_set RS ON RS.ID=S_LOG.REFERER_SET";
			$Report->WhereArr[]="RS.REFERER_ID=".$WhereArr[$i]['Id'];
		}
	}


	if ($WhereArr[$i]['Mode']=="CampKey") {
		$WhereArr[$i]['Name']=$Lang['ByKey'];
		$MetaTitle.=": ".$Lang['ByKey'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT KEYWORD FROM ".PFX."_tracker_keyword WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->KeyId=$WhereArr[$i]['Id'];
		}
	}



	if ($WhereArr[$i]['Mode']=="Ref") {
		$ShowAll=0;
		$DisableRefFilter=true;
		$WhereArr[$i]['Name']=$Lang['ByRefs'];
		$MetaTitle.=": ".$Lang['ByRefs'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=htmlspecialchars(urldecode($Db->ReturnValue("SELECT REFERER FROM ".PFX."_tracker_referer WHERE ID = ".$WhereArr[$i]['Id'])));
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->RsNeeded=true;
			$Report->WhereArr[]="RS.REFERER_ID=".$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="Source") {
		$ShowAll=0;
		$DisableRefFilter=true;
		$WhereArr[$i]['Name']=$Lang['BySource'];
		$MetaTitle.=": ".$Lang['BySource'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT HOST FROM ".PFX."_tracker_host WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->HostId=$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="SourceGrp") {
		$ShowAll=0;
		$DisableRefFilter=true;
		$WhereArr[$i]['Name']=$Lang['BySourceGrp'];
		$MetaTitle.=": ".$Lang['BySourceGrp'];
		if (ValidId($WhereArr[$i]['Id'])) {
			if ($WhereArr[$i]['Id']>0) {
				$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_host_grp WHERE ID = ".$WhereArr[$i]['Id']);
			}
			else $WhereArr[$i]['Name2']=$Lang['OtherSource'];
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->HostGrp=$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="Key") {
		$ShowAll=0;
		$DisableRefFilter=true;
		$WhereArr[$i]['Name']=$Lang['ByKey'];
		$MetaTitle.=": ".$Lang['ByKey'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT KEYWORD FROM ".PFX."_tracker_keyword WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->KeyId=$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="Vis") {
		$WhereArr[$i]['Name']=$Lang['ByVis'];
		$MetaTitle.=": ".$Lang['ByVis'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Lang['Vis']." ".$WhereArr[$i]['Id'];
			$Query = "SELECT NAME FROM ".PFX."_tracker_client_visitor WHERE VISITOR_ID=".$WhereArr[$i]['Id']." AND COMPANY_ID=$CpId";
			$VisitorName=$Db->ReturnValue($Query);
			if ($VisitorName) $WhereArr[$i]['Name2']=htmlspecialchars(stripslashes($VisitorName));
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="S_LOG.VISITOR_ID=".$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="AgentGrp") {
		$WhereArr[$i]['Name']=$Lang['ByAgentGrp'];
		$MetaTitle.=": ".$Lang['ByAgentGrp'];
		if (ValidId($WhereArr[$i]['Id'])) {
			if ($WhereArr[$i]['Id']>0) {
				$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_visitor_agent_grp WHERE ID = ".$WhereArr[$i]['Id']);
			}
			else $WhereArr[$i]['Name2']=$Lang['OtherAgentGrp'];
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor_agent VAG ON VAG.ID=S_LOG.AGENT_ID";
			$Report->WhereArr[]="VAG.GRP_ID=".$WhereArr[$i]['Id'];
		}
	}
	
	if ($WhereArr[$i]['Mode']=="Agent") {
		$WhereArr[$i]['Name']=$Lang['ByAgent'];
		$MetaTitle.=": ".$Lang['ByAgent'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT USER_AGENT FROM ".PFX."_tracker_visitor_agent WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="S_LOG.AGENT_ID=".$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="Action") {
		$WhereArr[$i]['Name']=$Lang['ByActions'];
		$MetaTitle.=": ".$Lang['ByActions'];
		$PrevReport=clone($Report);
		$Report->ConversionUni="UniClick";
		if (ValidId($WhereArr[$i]['Id'])) {
			$Report->ShowVisitors=false;
			$Report->ShowSales=false;
			$Report->ShowROI=false;
			$Report->ShowActionConv=false;
			$Report->ShowPrevActionConv=true;
			$Report->ShowSaleConv=false;
			$Report->NoROICalc=true;
				$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_visitor_action WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="S_ACTION.ACTION_ID=".$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="ActionItem") {
		$WhereArr[$i]['Name']=$Lang['ByActionItem'];
		$MetaTitle.=": ".$Lang['ByActionItem'];
		$PrevReport=clone($Report);
		$Report->ConversionUni="UniAction";
		if (ValidId($WhereArr[$i]['Id'])) {
			$Report->ShowVisitors=false;
			$Report->ShowSales=false;
			$Report->ShowROI=false;
			$Report->ShowActionConv=false;
			$Report->ShowPrevActionConv=true;
			$Report->ShowSaleConv=false;
			$Report->NoROICalc=true;
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_action_set ACS  ON ACS.STAT_ACTION_ID=S_ACTION.ID";
			$WhereArr[$i]['Name2']=stripslashes($Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_action_item WHERE ID = ".$WhereArr[$i]['Id']));
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="ACS.COMPANY_ID=$CpId";
			$Report->WhereArr[]="ACS.ACTION_ITEM_ID=".$WhereArr[$i]['Id'];
		}
	}
	
	if ($WhereArr[$i]['Mode']=="Sale") {
		$WhereArr[$i]['Name']=$Lang['BySaleItem'];
		$MetaTitle.=": ".$Lang['BySaleItem'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$Report->ShowVisitors=false;
			$Report->ShowActions=false;
			$Report->ShowROI=false;
			$Report->ShowActionConv=false;
			$Report->ShowSaleConv=false;
			$Report->ShowPrevSaleConv=false;
			$Report->NoROICalc=true;
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_sale_set SS  ON SS.SALE_ID=S_SALE.ID";
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_sale_item WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="SS.ITEM_ID=".$WhereArr[$i]['Id'];
		}
	}

	if ($WhereArr[$i]['Mode']=="Order") {
		$WhereArr[$i]['Name']=$Lang['BySale'];
		$MetaTitle.=": ".$Lang['BySale'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$Report->ShowVisitors=false;
			$Report->ShowActions=false;
			$Report->ShowROI=false;
			$Report->ShowActionConv=false;
			$Report->ShowSaleConv=false;
			$Report->ShowPrevSaleConv=false;
			$Report->NoROICalc=true;
			$CustomOID=$Db->ReturnValue("SELECT CUSTOM_ORDER_ID FROM ".PFX."_tracker_".$CpId."_stat_sale WHERE ID = ".$WhereArr[$i]['Id']);
			$WhereArr[$i]['Name2']=$Lang['OrderNo']." ";
			$WhereArr[$i]['Name2'].=($CustomOID)?$CustomOID:$WhereArr[$i]['Id'];
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="S_SALE.ID=".$WhereArr[$i]['Id'];
		}
	}


	if ($WhereArr[$i]['Mode']=="Page") {
		$WhereArr[$i]['Name']=$Lang['ByPage'];
		$MetaTitle.=": ".$Lang['ByPage'];
		if (ValidId($WhereArr[$i]['Id'])) {
			//$Report->ByPage=true;
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT PATH FROM ".PFX."_tracker_site_page WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="S_LOG.PAGE_ID=".$WhereArr[$i]['Id'];
		}
	}
	
	if ($WhereArr[$i]['Mode']=="Country") {
		$WhereArr[$i]['Name']=$Lang['ByCountry'];
		$MetaTitle.=": ".$Lang['ByCountry'];
		if (ValidId($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=$Db->ReturnValue("SELECT NAME FROM ".PFX."_tracker_country WHERE ID = ".$WhereArr[$i]['Id']);
			$MetaTitle.=": ".$WhereArr[$i]['Name2'];
			$Report->WhereArr[]="V.FIRST_COUNTRY_ID=".$WhereArr[$i]['Id'];
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
		}
	}	

	
	if ($WhereArr[$i]['Mode']=="Resolution") {
		$WhereArr[$i]['Name']=$Lang['ByResolution'];
		$MetaTitle.=": ".$Lang['ByResolution'];
		if (ValidVar($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=($WhereArr[$i]['Id']!="-1")?$WhereArr[$i]['Id']:$Lang['Undefined'];
			$MetaTitle.=": ".(($WhereArr[$i]['Id']!="-1")?$WhereArr[$i]['Id']:$Lang['Undefined']);
			$Report->WhereArr[]="V.LAST_RESOLUTION='".$WhereArr[$i]['Id']."'";
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
		}
	}	

	if ($WhereArr[$i]['Mode']=="Pixel") {
		$WhereArr[$i]['Name']=$Lang['ByPixel'];
		$MetaTitle.=": ".$Lang['ByPixel'];
		if (ValidVar($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=($WhereArr[$i]['Id']!="-1")?$WhereArr[$i]['Id']:$Lang['Undefined'];
			$MetaTitle.=": ".(($WhereArr[$i]['Id']!="-1")?$WhereArr[$i]['Id']:$Lang['Undefined']);
			$Report->WhereArr[]="V.PIXEL_DEPTH='".$WhereArr[$i]['Id']."'";
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
		}
	}	

	if ($WhereArr[$i]['Mode']=="Flash") {
		$WhereArr[$i]['Name']=$Lang['ByFlash'];
		$MetaTitle.=": ".$Lang['ByFlash'];
		if (ValidVar($WhereArr[$i]['Id'])) {
			$WhereArr[$i]['Name2']=($WhereArr[$i]['Id']!="-1")?$WhereArr[$i]['Id']:$Lang['None'];
			$MetaTitle.=": ".(($WhereArr[$i]['Id']!="-1")?$WhereArr[$i]['Id']:$Lang['None']);
			$Report->WhereArr[]="V.FLASH_VERSION='".$WhereArr[$i]['Id']."'";
			$Report->JoinArr[]="INNER JOIN ".PFX."_tracker_visitor V ON V.ID=S_LOG.VISITOR_ID";
		}
	}	

	$Get.="&WhereArr[$i][Mode]=".$WhereArr[$i]['Mode'];
	$Get.="&WhereArr[$i][Id]=".$WhereArr[$i]['Id'];
	if (ValidVar($WhereArr[$i]['OrderTo'])) $Get.="&WhereArr[$i][OrderTo]=".$WhereArr[$i]['OrderTo'];
	if (ValidVar($WhereArr[$i]['OrderBy'])) $Get.="&WhereArr[$i][OrderBy]=".$WhereArr[$i]['OrderBy'];

}

?>