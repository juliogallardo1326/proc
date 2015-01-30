<?


class Natural_v2 extends ReportParent
{
	var $CpId;
	var $SiteId;
	
	var $ShowVisitors; var $ShowActions; var $ShowSales; var $ShowActionConv; var $ShowSaleConv; var $ShowIncome; var $ShowPrevActionConv; var $SelectArr;
	var $JoinArr;
	var $GroupArr;
	var $WhereArr;
	var $OrderArr;
	var $PageStart;
	var $PageLimit;

	var $StartDate;
	var $EndDate;
	var $ViewDate;

	var $UseStraight; var $GrpFld; var $GrpName; var $NullGrpName; var $NoRef; var $ByPage; var $KeyId; var $HostId; var $HostGrp; var $RsNeeded; var $MakeSum;

	var $PrevLevelUni; var $ConversionUni; function Natural_v2()
	{
		$this->CpId=0;
		$this->SiteId=0;
		$this->ShowVisitors=false;
		$this->ShowActions=false;
		$this->ShowSales=false;
		$this->ShowActionConv=false;
		$this->ShowSaleConv=false;
		$this->ShowIncome=false;
		$this->StartDate=false;
		$this->EndDate=false;
		$this->ViewDate=false;
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

		$this->NoRef=false;
		$this->OnlyNoRef=false;
		$this->ByPage=false;

		$this->KeyId=false;
		$this->HostId=false;
		$this->HostGrp=false;

		$this->RsNeeded=false;
		$this->CookieJoin=true;
		$this->MakeSum=false;
		$this->CookieOnly=false;

		$this->LoggerFunc=false;

		$this->Db=&$GLOBALS['Db'];
		$this->SiteIds="";
		$this->ProcessTime=0;
		$this->StatArr=array();
		$this->CookieJoin=true;

		$this->CntClickSum=0;
		$this->UniClickSum=0;
		$this->CntActionSum=0;
		$this->UniActionSum=0;
		$this->CntSaleSum=0;
		$this->UniSaleSum=0;

		$this->PrevLevelUni=0;
		$this->ConversionUni="UniClick";
	}

	function Calculate()
	{
		global $nsUser, $CurrentCompany, $nsProduct;
		if (ValidId($CurrentCompany->ID)&&$CurrentCompany->SITE_CNT==0&&$this->CpId==$CurrentCompany->ID) return false;
		$this->StartTime=$this->GetMicrotime();

		if ($this->ShowActionConv) {
			$this->ShowVisitors=true;
			$this->ShowActions=true;
		}
		if ($this->ShowSaleConv) {
			$this->ShowVisitors=true;
			$this->ShowSales=true;
		}

		$this->JoinArr=array_unique($this->JoinArr);
		$this->SelectArr=array_unique($this->SelectArr);
		$this->WhereArr=array_unique($this->WhereArr);
		$this->GroupArr=array_unique($this->GroupArr);
		$this->OrderArr=array_unique($this->OrderArr);


		if ($this->NoRef||$this->ByPage||$this->OnlyNoRef) $this->CookieJoin=false;
		if ($this->CookieOnly) $this->CookieJoin=true;

		$this->Where();

		$this->StatArr=$this->GetStat();

		$this->EndTime=$this->GetMicrotime();
		$this->ProcessTime=$this->EndTime-$this->StartTime;
	}

	function Where()
	{
		global $nsUser;
		if (!$this->SiteId&&$this->CpId) $this->SiteIds=$this->GetSiteIds($this->CpId);
		if ($this->SiteId) $this->SiteIds=$this->SiteId;
		if ($this->SiteIds) $this->WhereArr[]="S_LOG.SITE_ID IN (".$this->SiteIds.")";

		$StartStamp=false;
		$EndStamp=false;
		if (ValidDate($this->ViewDate)) {
			$StartStamp=$this->ViewDate." 00:00:00";
			$EndStamp=$this->ViewDate." 23:59:59";
		}
		if (ValidDate($this->StartDate)) $StartStamp=$this->StartDate." 00:00:00";
		if (ValidDate($this->EndDate)) $EndStamp=$this->EndDate." 23:59:59";
		if ($StartStamp&&$EndStamp) $this->WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) BETWEEN '$StartStamp' AND '$EndStamp'";
		if ($StartStamp&&!$EndStamp) $this->WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) >= '$StartStamp'";
		if (!$StartStamp&&$EndStamp) $this->WhereArr[]="DATE_ADD(S_LOG.STAMP, INTERVAL '".$nsUser->TZ."' HOUR) <= '$EndStamp'";

		if (!$this->NoRef&&!$this->KeyId&&!$this->HostId&&!$this->HostGrp&&!$this->RsNeeded&&!$this->OnlyNoRef) {
			$this->WhereArr[]="S_LOG.REFERER_SET>0";
		}
		if ($this->OnlyNoRef) $this->WhereArr[]="S_LOG.REFERER_SET=0";

		if (ValidDate($this->ViewDate)) $this->UseStraight=true;

		if (ValidId($this->KeyId)) $this->WhereArr[]="RS.NATURAL_KEY=".$this->KeyId;
		if (ValidId($this->HostId)) $this->WhereArr[]="RS.HOST_ID=".$this->HostId;
		if (ValidId($this->HostGrp)&&!ValidId($this->HostId)) {
			$this->JoinArr[]="INNER JOIN ".PFX."_tracker_host TH ON TH.ID=RS.HOST_ID";
			$this->WhereArr[]="TH.GRP_ID=".$this->HostGrp;
		}

		if ($this->RsNeeded!==false||$this->KeyId!==false||$this->HostId!==false||$this->HostGrp!==false) {
			array_unshift($this->JoinArr, "INNER JOIN ".PFX."_tracker_referer_set RS ON RS.ID=S_LOG.REFERER_SET");
		}

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
		if (count($this->SelectArr)>0) $SelectStr=implode(", \n", $this->SelectArr).", \n";
		if (count($this->JoinArr)>0) $JoinStr=implode(" \n", $this->JoinArr);
		if (count($this->OrderArr)>0) $OrderStr="ORDER BY ".implode(", ", $this->OrderArr);
		if (count($this->GroupArr)>0) $GroupStr="GROUP BY ".implode(", ", $this->GroupArr);
		if (count($this->WhereArr)>0) $WhereStr="WHERE ".implode(" AND ", $this->WhereArr);
		//if ($this->UseStraight) $StraightStr="STRAIGHT_JOIN";
		if ($this->PageLimit>0) $LimitStr="LIMIT ".$this->PageLimit;

		//echo $this->CookieJoin;

		if ($this->CookieJoin) {
			$ActionJoin="
					# присоединяем ту ветку, которая произошла от S_LOG.ID
					# по принципу, что в COOKIE_LOG хранится этот самый ID
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_log NODE
						ON NODE.COOKIE_LOG=S_LOG.ID
					# присоединяем таблицу действий
					# только к выбранным веткам
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_action S_ACTION
						ON S_ACTION.LOG_ID=NODE.ID
			";
			$SaleJoin="
					# присоединяем ту ветку, которая произошла от S_LOG.ID
					# по принципу, что в COOKIE_LOG хранится этот самый ID
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

		///////////////////////////////////////////
		$HitSelectStr=$SelectStr;
		$HitSelectStr=preg_replace("/NODE\.STAMP/", "S_LOG.STAMP", $HitSelectStr);

		if ($this->ShowVisitors) {
			$Query = "
				SELECT  $StraightStr
					$HitSelectStr
					COUNT(S_LOG.ID) CNT,
					COUNT(DISTINCT S_LOG.VISITOR_ID) UNI
					# выбираем точки входа из лога
					FROM ".PFX."_tracker_".$this->CpId."_stat_log S_LOG
				$JoinStr
				$WhereStr
				$GroupStr
				$OrderStr
				$LimitStr
			";
			//echo HLSQL($Query);
			$Sql = new Query($Query);
			if ($this->LoggerFunc) {
				$Sql->LoggerFunc=$this->LoggerFunc;
				$Sql->GetTime=true;
			}
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
				$StatArr[$Grp]['CntClick']=$Row->CNT;
				$StatArr[$Grp]['UniClick']=$Row->UNI;
				$StatArr[$Grp]['CntAction']=0;
				$StatArr[$Grp]['UniAction']=0;
				$StatArr[$Grp]['CntSale']=0;
				$StatArr[$Grp]['UniSale']=0;
				$StatArr[$Grp]['Income']=0;
				$StatArr[$Grp]['Obj']=$Row;
				if ($this->MakeSum) {
					$this->CntClickSum+=$Row->CNT;
					$this->UniClickSum+=$Row->UNI;
				}
				if (count($this->GroupArr)) $GrpItems[]=" '$Grp' ";
			}
			if (count($GrpItems)>0&&!ValidId($this->GroupArr[0])) $GrpList="AND ".$this->GroupArr[0]." IN (".implode(",",$GrpItems).")";
		}
		
		if ($this->CookieJoin) $WhereStr=preg_replace("/S_LOG\.STAMP/", "NODE.STAMP", $WhereStr);

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
			if ($this->LoggerFunc) {
				$Sql->LoggerFunc=$this->LoggerFunc;
				$Sql->GetTime=true;
			}
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
					$StatArr[$Grp]['CntSale']=0;
					$StatArr[$Grp]['UniSale']=0;
					$StatArr[$Grp]['Income']=0;
				}
				$StatArr[$Grp]['CntAction']=$Row->CNT;
				$StatArr[$Grp]['UniAction']=$Row->UNI;
				$StatArr[$Grp]['Actions']=$Row->ACTION_UNI;
				if (!ValidVar($StatArr[$Grp]['Obj'])) $StatArr[$Grp]['Obj']=$Row;
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
			if ($this->LoggerFunc) {
				$Sql->LoggerFunc=$this->LoggerFunc;
				$Sql->GetTime=true;
			}
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
				}
				if (!$this->ShowVisitors&&!$this->ShowActions) {
					$StatArr[$Grp]['CntClick']=0;
					$StatArr[$Grp]['UniClick']=0;
					$StatArr[$Grp]['CntAction']=0;
					$StatArr[$Grp]['UniAction']=0;
				}
				$StatArr[$Grp]['CntSale']=$Row->CNT;
				$StatArr[$Grp]['UniSale']=$Row->UNI;
				$StatArr[$Grp]['Income']=$Row->SALE_SUM;
				if (!ValidVar($StatArr[$Grp]['Obj'])) $StatArr[$Grp]['Obj']=$Row;
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
		}

		if (!ValidArr($StatArr)||count($StatArr)==0) return false;
		if (count($this->GroupArr)<1) {
			if (!isset($Grp)) $Grp=0;
			return $StatArr[$Grp];
		}
		return $StatArr;
	}





}

?>