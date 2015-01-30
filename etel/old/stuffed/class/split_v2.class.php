<?

class SplitStat_v2 extends ReportParent
{
	var $SplitId;
	var $StartDate;
	var $EndDate;
	var $ViewDate;

	var $SelectArr;
	var $JoinArr;
	var $GroupArr;
	var $WhereArr;
	var $OrderArr;

	var $ShowVisitors; var $ShowActions; var $ShowSales; var $ShowActionConv; var $ShowSaleConv; var $ShowPages;

	function SplitStat_v2()
	{
		$this->SplitId=false;
		$this->StartDate=false;
		$this->EndDate=false;
		$this->ViewDate=false;

		$this->ShowVisitors=false;
		$this->ShowActions=false;
		$this->ShowSales=false;
		$this->ShowActionConv=false;
		$this->ShowSaleConv=false;

		$this->SelectArr=array();
		$this->JoinArr=array();
		$this->GroupArr=array();
		$this->WhereArr=array();
		$this->OrderArr=array();

		$this->ShowPages=false;

		$this->Db=&$GLOBALS['Db'];
		$this->ProcessTime=0;
		$this->SplitStat=array();
		$this->PageStat=array();
		$this->CookieJoin=true;
		$this->GrpFld=false;
		$this->GrpName=false;

		$this->UseStraight=false;
	}

	function Calculate()
	{
		global $nsUser, $CurrentCompany;
		if (ValidId($CurrentCompany->ID)&&$CurrentCompany->SITE_CNT==0&&$this->CpId==$CurrentCompany->ID) return false;
		$this->StartTime=$this->GetMicrotime();

		if ($this->ShowActionConv&&$nsUser->Columns->CONVERSIONS) {
			$this->ShowVisitors=true;
			$this->ShowActions=true;
		}
		if ($this->ShowSaleConv&&$nsUser->Columns->CONVERSIONS) {
			$this->ShowVisitors=true;
			$this->ShowSales=true;
		}

		$this->Where();
		$this->SplitStat=$this->GetStat();

		if ($this->ShowPages) {
			$this->SelectArr[]="SPP.ID AS SPLIT_PAGE";
			$this->SelectArr[]="IF(S_LOG.QUERY_ID>0, CONCAT(SP.PATH, '?', TQ.QUERY_STRING), SP.PATH) AS PATH";
			$this->SelectArr[]="TQ.QUERY_STRING";
			$this->SelectArr[]="SH.HOST";
			$this->GroupArr[]="SPP.ID";
			$this->GrpFld="SPLIT_PAGE";
			$this->GrpName="PATH";
			$this->JoinArr[]="INNER JOIN ".PFX."_tracker_split_page SPP ON SPP.PAGE_ID=S_LOG.PAGE_ID AND SPP.QUERY_ID=S_LOG.QUERY_ID";
			$this->JoinArr[]="INNER JOIN ".PFX."_tracker_site_page SP ON SP.ID=S_LOG.PAGE_ID";
			$this->JoinArr[]="INNER JOIN ".PFX."_tracker_site_host SH ON SH.ID=S_LOG.SITE_HOST_ID";
			$this->JoinArr[]="LEFT JOIN ".PFX."_tracker_query TQ ON TQ.ID = S_LOG.QUERY_ID";
			$this->PageStat=$this->GetStat();
		}

		$this->EndTime=$this->GetMicrotime();
		$this->ProcessTime=$this->EndTime-$this->StartTime;

	}

	function Where()
	{
		global $nsUser;
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

		if (ValidId($this->SplitId)) $this->WhereArr[]="S_SPLIT.SPLIT_ID = ".$this->SplitId;
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
		$WhereStr="";
		$StraightStr="";
		$LimitStr="";
		if (count($this->SelectArr)>0) $SelectStr=implode(", ", $this->SelectArr).", ";
		if (count($this->JoinArr)>0) $JoinStr=implode(" \n", $this->JoinArr);
		if (count($this->OrderArr)>0) $OrderStr="ORDER BY ".implode(", ", $this->OrderArr);
		if (count($this->GroupArr)>0) $GroupStr="GROUP BY ".implode(", ", $this->GroupArr);
		if (count($this->WhereArr)>0) $WhereStr="WHERE ".implode(" AND ", $this->WhereArr);
		if ($this->UseStraight) $StraightStr="STRAIGHT_JOIN";

		///////////////////////////////////////////
		if ($this->ShowVisitors) {
			$Query = "
				SELECT  $StraightStr
					$SelectStr
					COUNT(S_LOG.ID) CNT,
					COUNT(DISTINCT S_LOG.VISITOR_ID) UNI
					# выбираем точки входа из лога
					FROM ".PFX."_tracker_".$this->CpId."_stat_log S_LOG
					# точки входа определяются путями по сайту
					# вероятно, с наличием referer set 
					#JOIN ".PFX."_tracker_stat_path S_PATH
					#	ON S_PATH.LOG_ID=S_LOG.ID
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_split S_SPLIT
						ON S_SPLIT.LOG_ID=S_LOG.ID
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
				else $Grp=$Sql->Position; if ($this->GrpName) {$Name=$this->GrpName; $StatArr[$Grp]['Name']=$Row->$Name;}
				$StatArr[$Grp]['CntClick']=$Row->CNT;
				$StatArr[$Grp]['UniClick']=$Row->UNI;
				$StatArr[$Grp]['CntAction']=0;
				$StatArr[$Grp]['UniAction']=0;
				$StatArr[$Grp]['CntSale']=0;
				$StatArr[$Grp]['UniSale']=0;
				$StatArr[$Grp]['Income']=0;
				$StatArr[$Grp]['Obj']=$Row;
				if (count($this->GroupArr)) $GrpItems[]=$Grp;
			}
			if (count($GrpItems)>0&&!ValidId($this->GroupArr[0])) $GrpList="AND ".$this->GroupArr[0]." IN (".implode(",",$GrpItems).")";
		}
		///////////////////////////////////////////
		if ($this->ShowActions) {
			$Query = "
				SELECT  $StraightStr
					$SelectStr
					COUNT(S_LOG.ID) CNT,
					COUNT(DISTINCT S_LOG.VISITOR_ID) UNI
					# выбираем точки входа из лога
					FROM ".PFX."_tracker_".$this->CpId."_stat_log S_LOG
					# присоединяем ту ветку, которая произошла от S_LOG.ID
					# по принципу, что в COOKIE_LOG хранится этот самый ID
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_log NODE
						ON NODE.COOKIE_LOG=S_LOG.ID
					# присоединяем таблицу действий
					# только к выбранным веткам
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_action S_ACTION
						ON S_ACTION.LOG_ID=NODE.ID
					# точки входа определяются путями по сайту
					# вероятно, с наличием referer set 
					#JOIN ".PFX."_tracker_stat_path S_PATH
					#	ON S_PATH.LOG_ID=S_LOG.ID
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_split S_SPLIT
						ON S_SPLIT.LOG_ID=S_LOG.ID
				$JoinStr
				$WhereStr
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
				if (!isset($StatArr[$Grp])&&$this->ShowVisitors) continue;
				if(!$this->ShowVisitors) {
					if ($this->GrpName) {$Name=$this->GrpName; $StatArr[$Grp]['Name']=$Row->$Name;}
				}
				$StatArr[$Grp]['CntAction']=$Row->CNT;
				$StatArr[$Grp]['UniAction']=$Row->UNI;
				$StatArr[$Grp]['Obj']=$Row;
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
					FROM ".PFX."_tracker_".$this->CpId."_stat_log S_LOG
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_log NODE
						ON NODE.COOKIE_LOG=S_LOG.ID
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_sale S_SALE
						ON S_SALE.LOG_ID=NODE.ID
					#JOIN ".PFX."_tracker_stat_path S_PATH
					#	ON S_PATH.LOG_ID=S_LOG.ID
					INNER JOIN ".PFX."_tracker_".$this->CpId."_stat_split S_SPLIT
						ON S_SPLIT.LOG_ID=S_LOG.ID
				$JoinStr
				$WhereStr
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
				if (!isset($StatArr[$Grp])&&$this->ShowVisitors) continue;
				if(!$this->ShowVisitors) {
					if ($this->GrpName) {$Name=$this->GrpName; $StatArr[$Grp]['Name']=$Row->$Name;}
				}
				$StatArr[$Grp]['CntSale']=$Row->CNT;
				$StatArr[$Grp]['UniSale']=$Row->UNI;
				$StatArr[$Grp]['Income']=$Row->SALE_SUM;
				$StatArr[$Grp]['Obj']=$Row;
			}
		}


		foreach ($StatArr as $i => $Row) {
			if ($this->ShowActionConv)
				$StatArr[$i]['ActionConv']=$this->GetRatio($StatArr[$i]['UniClick'], $StatArr[$i]['UniAction']);
			if ($this->ShowSaleConv) 
				$StatArr[$i]['SaleConv']=$this->GetRatio($StatArr[$i]['UniClick'], $StatArr[$i]['UniSale']);
			if (!$StatArr[$i]['Income']) $StatArr[$i]['Income']=0;
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