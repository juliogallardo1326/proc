<?


class Paid_v2 extends ReportParent
{
	var $GrpId;
	var $CampId;
	var $CpId;

	var $KeyId; var $HostId; var $HostGrp; var $StartDate;
	var $EndDate;
	var $ViewDate;

	var $ShowROI;
	var $ShowActionConv;
	var $ShowSaleConv;
	var $ShowActions;
	var $ShowSales;
	var $ShowVisitors;

	var $ShowPrevActionConv; var $GrpFld; var $GrpName; var $NullGrpName; var $UseStraight; var $SelectArr;
	var $JoinArr;
	var $GroupArr;
	var $WhereArr;
	var $OrderArr;
	var $PageStart;
	var $PageLimit;
	
	var $ByPage;
	var $GroupMode; // CAMP, GRP
	var $MakeSum;

	var $PrevLevelUni; var $ConversionUni; function Paid_v2()
	{
		$this->GrpId=false;
		$this->CpId=false;
		$this->CampId=false;
		$this->StartDate=false;
		$this->ViewDate=false;
		$this->EndDate=false;
		$this->ShowROI=false;
		$this->ShowActionConv=false;
		$this->ShowSaleConv=false;
		$this->ShowActions=false;
		$this->ShowSales=false;
		$this->ShowVisitors=false;
		$this->ShowPrevActionConv=false;

		$this->SelectArr=array();
		$this->JoinArr=array();
		$this->GroupArr=array();
		$this->WhereArr=array();
		$this->OrderArr=array();

		$this->PageStart=0;
		$this->PageLimit=0;
		$this->UseStraight=false;
		$this->GrpFld=false;
		$this->GrpName=false;

		$this->KeyId=false;
		$this->HostId=false;
		$this->HostGrp=false;
		$this->ByPage=false;
		$this->GroupMode=false;
		$this->MakeSum=false;

		///
		$this->Db=&$GLOBALS['Db'];
		$this->SiteIds="";
		$this->ProcessTime=0;
		$this->CampStat=array();
		$this->CookieJoin=true;
		$this->NoStat=false;

		$this->CntClickSum=0;
		$this->UniClickSum=0;
		$this->CntActionSum=0;
		$this->UniActionSum=0;
		$this->CntSaleSum=0;
		$this->UniSaleSum=0;

		$this->NoROI=true;
		$this->NoROICalc=false;
		$this->NoCost=true;
		$this->NoIncome=true;

		$this->PrevLevelUni=0;
		$this->ConversionUni="UniClick";

		$this->CampIds=array();
		$this->ShowPerClick=false;
		$this->ShowTotalCost=false;

	}

	function Calculate()
	{
		global $nsUser, $CurrentCompany;
		if (ValidId($CurrentCompany->ID)&&$CurrentCompany->SITE_CNT==0&&$this->CpId==$CurrentCompany->ID) return false;

		if ($this->ShowActionConv) {
			//$this->ShowVisitors=true;
			//$this->ShowActions=true;
		}
		if ($this->ShowSaleConv) {
			//$this->ShowVisitors=true;
			//$this->ShowSales=true;
		}
		if ($this->ShowROI) {
			//$this->ShowVisitors=true;
			//$this->ShowSales=true;
		}


		$this->StartTime=$this->GetMicrotime();
		$this->JoinArr=array_unique($this->JoinArr);
		$this->SelectArr=array_unique($this->SelectArr);
		$this->WhereArr=array_unique($this->WhereArr);
		$this->GroupArr=array_unique($this->GroupArr);
		$this->OrderArr=array_unique($this->OrderArr);

		if ($this->ShowActionConv&&$nsUser->Columns->CONVERSIONS) {
			$this->ShowVisitors=true;
			$this->ShowActions=true;
		}
		if ($this->ShowSaleConv&&$nsUser->Columns->CONVERSIONS) {
			$this->ShowVisitors=true;
			$this->ShowSales=true;
		}
		if ($this->ShowROI&&$nsUser->Columns->ROI) $this->ShowSales=true;
		

		$this->Where();
		$this->CampStat=$this->GetStat();

		$this->EndTime=$this->GetMicrotime();
		$this->ProcessTime=$this->EndTime-$this->StartTime;

	}

	function Where()
	{
		global $nsUser;
		if (!ValidId($this->SiteId)&&$this->CpId) $this->SiteIds=$this->GetSiteIds($this->CpId);
		if (ValidId($this->SiteId)) $this->SiteIds=$this->SiteId;
		if ($this->SiteIds) $this->WhereArr[]="S_LOG.SITE_ID IN (".$this->SiteIds.")";

		$StartStamp=false;
		$EndStamp=false;
		if (ValidDate($this->ViewDate)) {
			$StartStamp=$this->ViewDate." 00:00:00";
			$EndStamp=$this->ViewDate." 23:59:59";
			//$EndStamp=$this->Db->ReturnValue("SELECT DATE_ADD('".$this->ViewDate."', INTERVAL 1 DAY)")." 00:00:00";
		}
		if (ValidDate($this->StartDate)) $StartStamp=$this->StartDate." 00:00:00";
		if (ValidDate($this->EndDate)) $EndStamp=$this->EndDate." 23:59:59";
		if ($StartStamp&&$EndStamp) $this->WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) BETWEEN '$StartStamp' AND '$EndStamp'";
		if ($StartStamp&&!$EndStamp) $this->WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) >= '$StartStamp'";
		if (!$StartStamp&&$EndStamp) $this->WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) <= '$EndStamp'";

		if ((ValidId($this->GrpId)||ValidId($this->CpId))&&!ValidId($this->CampId)) {
			$this->CampIds=$this->GetCampIds();
			$this->WhereArr[]="S_CLICK.CAMP_ID IN (".implode(",",$this->CampIds).")";
		}
		if (ValidId($this->CampId)) {
			$this->WhereArr[]="S_CLICK.CAMP_ID = ".$this->CampId;
			$this->CampIds[]=$this->CampId;
		}
		if (ValidId($this->KeyId)) $this->WhereArr[]="S_CLICK.KEYWORD_ID = ".$this->KeyId;
		if (ValidId($this->HostId)) $this->WhereArr[]="S_CLICK.SOURCE_HOST_ID = ".$this->HostId;

	}

	function GetStat()
	{
		global $Logs;
		$StatArr=array();
		$GrpItems=array();
		$GrpList="";
		$SelectStr="";
		$JoinStr="";
		$OrderStr="";
		$GroupStr="";
		$LimitStr="";
		$WhereStr="";
		$StraightStr="";
		if (count($this->SelectArr)>0) $SelectStr=implode(", ", $this->SelectArr).", ";
		if (count($this->JoinArr)>0) $JoinStr=implode(" \n", $this->JoinArr);
		if (count($this->OrderArr)>0) $OrderStr="ORDER BY ".implode(", ", $this->OrderArr);
		if (count($this->GroupArr)>0) $GroupStr="GROUP BY ".implode(", ", $this->GroupArr);
		if (count($this->WhereArr)>0) $WhereStr="WHERE ".implode(" AND ", $this->WhereArr);
		if ($this->UseStraight) $StraightStr="STRAIGHT_JOIN";
		if ($this->PageLimit>0) $LimitStr="LIMIT ".$this->PageLimit;


		if (!$this->ByPage) {
			$ActionJoin="
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_log NODE
						ON NODE.COOKIE_LOG=S_LOG.ID
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_action S_ACTION
						ON S_ACTION.LOG_ID=NODE.ID
			";
			$SaleJoin="
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_log NODE
						ON NODE.COOKIE_LOG=S_LOG.ID
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_sale S_SALE
						ON S_SALE.LOG_ID=NODE.ID
			";
		}
		else {
			$ActionJoin="
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_action S_ACTION
						ON S_ACTION.LOG_ID=S_LOG.ID
			";
			$SaleJoin="
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_sale S_SALE
						ON S_SALE.LOG_ID=S_LOG.ID
			";
		}
		$ActionWhereAdd="";
		$SaleWhereAdd="";
		if ($this->SiteIds) {
			$ActionWhereAdd="AND S_ACTION.SITE_ID IN (".$this->SiteIds.")";
			$SaleWhereAdd="AND S_SALE.SITE_ID IN (".$this->SiteIds.")";
		}

		$HitSelectStr=$SelectStr;
		$HitSelectStr=preg_replace("/NODE\.STAMP/", "S_LOG.STAMP", $HitSelectStr);


		///////////////////////////////////////////
		if ($this->ShowVisitors) {
			$Query = "
				SELECT  $StraightStr
					$HitSelectStr
					COUNT(S_LOG.ID) CNT,
					COUNT(DISTINCT S_LOG.VISITOR_ID) UNI,
					COUNT(DISTINCT S_CLICK.CAMP_ID) AS CAMP_UNI
					# выбираем точки входа из лога
					FROM ".PFX."_tracker_".$this->CpId."_stat_log S_LOG
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_click S_CLICK
						ON S_CLICK.LOG_ID=S_LOG.ID
				$JoinStr
				$WhereStr
				$GroupStr
				$OrderStr
				$LimitStr
			";
			//echo HLSQL($Query);
			$Sql = new Query($Query);
			while ($Row=$Sql->Row()) {
				if ($this->GrpFld) {$Tmp=$this->GrpFld;$Grp=$Row->$Tmp;}
				else $Grp=$Sql->Position; $Row->ID=$Grp;
				if ($this->GrpName) {
					$Name=$this->GrpName; 
					$StatArr[$Grp]['Name']=$Row->$Name;
					if ($this->NullGrpName&&!$Grp) {
						$Row->$Name=$this->NullGrpName;
						$StatArr[$Grp]['Name']=$this->NullGrpName;
					}
				}
				$StatArr[$Grp]['CampCost']=0;
				$StatArr[$Grp]['CntClick']=($Row->CNT)?$Row->CNT:0;
				$StatArr[$Grp]['UniClick']=($Row->UNI)?$Row->UNI:0;
				$StatArr[$Grp]['Camp']=$Row->CAMP_UNI;
				$StatArr[$Grp]['CntAction']=0;
				$StatArr[$Grp]['UniAction']=0;
				$StatArr[$Grp]['CntSale']=0;
				$StatArr[$Grp]['UniSale']=0;
				$StatArr[$Grp]['Obj']=$Row;
				if ($this->MakeSum) {
					$this->CntClickSum+=$Row->CNT;
					$this->UniClickSum+=$Row->UNI;
				}
				if (count($this->GroupArr)) $GrpItems[]= " '$Grp' ";
			}
			if (count($GrpItems)>0&&!ValidId($this->GroupArr[0])) $GrpList="AND ".$this->GroupArr[0]." IN (".implode(",",$GrpItems).")";

		}
		///////////////////////////////////////////
		if (($this->ShowPerClick||$this->ShowTotalCost)&&!$this->NoROICalc) { 
		$CostSelectStr="";
		$CostGroupStr="";
		if (count($this->SelectArr)>0) {
			$CostSelectStr=preg_replace("/NODE\.STAMP/", "S_LOG.STAMP", $this->SelectArr[0]).", ";
			$CostGroupStr="1, ";
		}
				$Query = "
					SELECT
					$CostSelectStr
					CC.ID AS COST_ID,
					SC.TYPE,
					CC.MODE,
					COUNT(S_CLICK.ID)*CC.COST AS COST,
					CC.COST AS CAMP_COST
					FROM ".PFX."_tracker_".$this->CpId."_stat_log S_LOG
						 INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_click S_CLICK
							  ON S_CLICK.LOG_ID=S_LOG.ID
						 INNER JOIN ".PFX."_tracker_sub_campaign SC
							  ON SC.SUB_ID=S_CLICK.CAMP_ID
						 INNER JOIN ".PFX."_tracker_camp_cost CC
							  ON CC.SUB_CAMPAIGN=S_CLICK.CAMP_ID
					$JoinStr
					$WhereStr
					AND CC.SUM_THIS='1'
	
					AND (
						(SC.TYPE='0' AND CC.MODE='1' AND S_LOG.STAMP>=CC.START_DATE)
						OR
						(SC.TYPE='1' AND CC.MODE='0')
					)
					GROUP BY $CostGroupStr CC.ID
				";
				//echo HLSQL($Query);
				$SubSql=new Query($Query, "ROW");
				while ($SubRow=$SubSql->Row()) {
					$CostGrp=0;
					$CostIndex=3;
					if (count($this->SelectArr)>0) {$CostGrp=$SubRow[0]; $CostIndex=4;}
					if (!isset($StatArr[$CostGrp])) continue;
					if ($this->ShowPerClick&&$SubRow[$CostIndex-1]==1&&$SubRow[$CostIndex-2]==0) {
						$StatArr[$CostGrp]['CampCost']+=$SubRow[$CostIndex];
					}
					if ($this->ShowTotalCost&&$SubRow[$CostIndex-1]==0&&$SubRow[$CostIndex-2]==1) {
						$StatArr[$CostGrp]['CampCost']+=$SubRow[$CostIndex+1];
					}
				}
		}

		///////////////////////////////////////////
		if ($this->ShowActions) {
			$Query = "
				SELECT  $StraightStr
					$SelectStr
					COUNT(S_LOG.ID) CNT,
					COUNT(DISTINCT S_LOG.VISITOR_ID) UNI,
					COUNT(DISTINCT S_ACTION.ACTION_ID) ACTION_UNI
					# выбираем точки входа из лога
					FROM ".PFX."_tracker_".$this->CpId."_stat_log S_LOG
					$ActionJoin
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_click S_CLICK
						ON S_CLICK.LOG_ID=S_LOG.ID
				$JoinStr
				$WhereStr
				$ActionWhereAdd		
				$GrpList
				$GroupStr
				$OrderStr
				$LimitStr
			";
			//echo HLSQL($Query);
			$Sql = new Query($Query);
			while ($Row=$Sql->Row()) {
				if ($this->GrpFld) {$Tmp=$this->GrpFld;$Grp=$Row->$Tmp;}
				else $Grp=$Sql->Position;
				$Row->ID=$Grp;
				if (!isset($StatArr[$Grp])&&$this->ShowVisitors) continue;
				if(!$this->ShowVisitors) {
					if ($this->GrpName) {
						$Name=$this->GrpName; 
						$StatArr[$Grp]['Name']=$Row->$Name;
						if ($this->NullGrpName&&!$Grp) {
							$Row->$Name=$this->NullGrpName;
							$StatArr[$Grp]['Name']=$this->NullGrpName;
						}
					}
					$StatArr[$Grp]['CntClick']=0;
					$StatArr[$Grp]['UniClick']=0;
				}
				$StatArr[$Grp]['CntSale']=0;
				$StatArr[$Grp]['UniSale']=0;
				$StatArr[$Grp]['CntAction']=$Row->CNT;
				$StatArr[$Grp]['UniAction']=$Row->UNI;
				$StatArr[$Grp]['Actions']=$Row->ACTION_UNI;
				$StatArr[$Grp]['Obj']=$Row;
				if ($this->MakeSum) {	
					$this->CntActionSum+=$Row->CNT;
					$this->UniActionSum+=$Row->UNI;
				}
			}

		}

		///////////////////////////////////////////
		if ($this->ShowSales) {
			$Query = "
				SELECT  $StraightStr
					$SelectStr
					COUNT(S_LOG.ID) CNT,
					COUNT(DISTINCT S_LOG.VISITOR_ID) UNI,
					SUM(S_SALE.COST) AS SALE_SUM
					# выбираем точки входа из лога
					FROM ".PFX."_tracker_".$this->CpId."_stat_log S_LOG
					$SaleJoin
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_click S_CLICK
						ON S_CLICK.LOG_ID=S_LOG.ID
				$JoinStr
				$WhereStr
				$SaleWhereAdd		
				$GrpList
				$GroupStr
				$OrderStr
				$LimitStr
			";
			//echo HLSQL($Query);
			$Sql = new Query($Query);
			while ($Row=$Sql->Row()) {
				if ($this->GrpFld) {$Tmp=$this->GrpFld;$Grp=$Row->$Tmp;}
				else $Grp=$Sql->Position;
				$Row->ID=$Grp;
				if (!isset($StatArr[$Grp])&&$this->ShowVisitors) continue;
				if (!$this->ShowVisitors) {
					if ($this->GrpName) {
						$Name=$this->GrpName; 
						$StatArr[$Grp]['Name']=$Row->$Name;
						if ($this->NullGrpName&&!$Grp) {
							$Row->$Name=$this->NullGrpName;
							$StatArr[$Grp]['Name']=$this->NullGrpName;
						}
					}
				}
				if (!$this->ShowVisitors) {
					$StatArr[$Grp]['CntClick']=0;
					$StatArr[$Grp]['UniClick']=0;
				}
				if (!$this->ShowActions) {
					$StatArr[$Grp]['CntAction']=0;
					$StatArr[$Grp]['UniAction']=0;
				}
				$StatArr[$Grp]['CntSale']=$Row->CNT;
				$StatArr[$Grp]['UniSale']=$Row->UNI;
				$StatArr[$Grp]['TotalIncome']=$Row->SALE_SUM;
				$StatArr[$Grp]['Obj']=$Row;
				if ($this->MakeSum) {
					$this->CntSaleSum+=$Row->CNT;
					$this->UniSaleSum+=$Row->UNI;
				}
			}
		}

		foreach ($StatArr as $i => $Row) {
	
			$Uni=$StatArr[$i]['UniClick']=($StatArr[$i]['UniClick'])?	$StatArr[$i]['UniClick']:$this->PrevLevelUni;

			if ($this->ShowActionConv||$this->ShowPrevActionConv)
				$StatArr[$i]['ActionConv']=$this->GetRatio($Uni, $StatArr[$i]['UniAction']);
			if ($this->ShowSaleConv) 
				$StatArr[$i]['SaleConv']=$this->GetRatio($Uni, $StatArr[$i]['UniSale']);

			if ($this->ShowROI&&ValidVar($StatArr[$i]['CampCost'])&&ValidVar($StatArr[$i]['TotalIncome'])) {
				$StatArr[$i]['ROI']=$this->GetROI($StatArr[$i]['CampCost'], $StatArr[$i]['TotalIncome']);
				if (!$StatArr[$i]['ROI']) $StatArr[$i]['ROI']=0;
				else $this->NoROI=false;
			}
			else 	$StatArr[$i]['ROI']="0";
		
			if (ValidVar($StatArr[$i]['CampCost'])) $this->NoCost=false;
			if (ValidVar($StatArr[$i]['TotalIncome'])) $this->NoIncome=false;
		
		}

		if (!ValidArr($StatArr)||count($StatArr)==0) {
			$this->NoStat=true;
			return false;
		}
		if (count($this->GroupArr)<1) {
			if (!isset($Grp)) $Grp=0;
			return $StatArr[$Grp];
		}
		return $StatArr;
	}





	///////////////////////////////////////
	function GetCampIds($GrpId=false, $CpId=false)
	{
		$Grp=($GrpId)?$GrpId:$this->GrpId;
		$Cp=($CpId)?$CpId:$this->CpId;
		if (!ValidId($Grp)&&!ValidId($Cp)) return false;
		if ($Cp) {$Fld="COMPANY_ID";$Value=$Cp;}
		if ($Grp) {$Fld="CAMPAIGN_ID";$Value=$Grp;}
		$CampArr=array();
		$SubArr=array();
		$Query = "
			SELECT CP.ID
			FROM ".PFX."_tracker_camp_piece CP
			WHERE CP.$Fld=$Value
		";
		$Sql = new Query($Query);
		while ($Row=$Sql->Row()) $CampArr[]=$Row->ID;
		if ($Grp) {
			$Query = "SELECT ID FROM ".PFX."_tracker_campaign WHERE PARENT_ID=$Grp";
			$Sql = new Query($Query);
			while ($Row=$Sql->Row()) {
				$SubArr=$this->GetCampIds($Row->ID);
				$CampArr=array_merge($CampArr,$SubArr);
			}
		}
		$CampArr=array_unique($CampArr);
		if (count($CampArr)==0) $CampArr[]=0;
		return $CampArr;
	}

	function GetROI($Cost=false, $Income=false)
	{
		if (!$Cost||!$Income) return 0;
		return round(($Income/$Cost)*100, 2);
	}

	function GoodROI($Roi)
	{
		if ($Roi>100) return true;
		else return false;
	}




}

?>