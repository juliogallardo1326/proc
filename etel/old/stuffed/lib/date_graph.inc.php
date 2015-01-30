<?

		$NotShowed=true;

		if ($DateFormat=="Year") $TimeName=$Lang['GraphByYear'];
		if ($DateFormat=="Month") $TimeName=$Lang['GraphByMonths'];
		if ($DateFormat=="Date") $TimeName=$Lang['GraphByDays'];
		if ($DateFormat=="WeekDay") $TimeName=$Lang['GraphByWeek'];
		if ($DateFormat=="Time") $TimeName=$Lang['GraphByTime'];


		$ClickGraph=new StatGraph("ClickGraph");
		if ($Report->ShowVisitors) {$ClickGraph->Display=$NotShowed; $NotShowed=false;}
		$ClickGraph->Name=$Lang['ClickGraphName'];
		$ClickGraph->Vars['disp_type']="diagram";
		$ClickGraph->Vars['disp_x_caption']=$TimeName;
		$ClickGraph->Vars['disp_y_caption']=$Lang['Visitors'];
		$ClickGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrHits']);
		$ClickGraph->Vars['line0_description']="";
		$ClickGraph->Vars['line0_color']="FF3300";
		$ClickGraph->Vars['line0_points']="";
		$ClickGraph->Vars['line1_caption']=htmlspecialchars($Lang['GraphUniLine']);
		$ClickGraph->Vars['line1_description']="";
		$ClickGraph->Vars['line1_color']="669966";
		$ClickGraph->Vars['line1_points']="";
		$Params[0]="CntClick";
		$Params[1]="UniClick";
		if ($Report->ShowVisitors) DrawDateStat($Report->$StatArrName, $ClickGraph, $Params);

		$ActionGraph=new StatGraph("ActionGraph");
		if ($Report->ShowActions) {$ActionGraph->Display=$NotShowed; $NotShowed=false;}
		$ActionGraph->Name=$Lang['ActionGraphName'];
		$ActionGraph->Vars['disp_type']="diagram";
		$ActionGraph->Vars['disp_x_caption']=$TimeName;
		$ActionGraph->Vars['disp_y_caption']=$Lang['Visitors'];
		$ActionGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrActions']);
		$ActionGraph->Vars['line0_description']="";
		$ActionGraph->Vars['line0_color']="FF3300";
		$ActionGraph->Vars['line0_points']="";
		$ActionGraph->Vars['line1_caption']=htmlspecialchars($Lang['Visitors']);
		$ActionGraph->Vars['line1_description']="";
		$ActionGraph->Vars['line1_color']="669966";
		$ActionGraph->Vars['line1_points']="";
		$Params[0]="CntAction";
		$Params[1]="UniAction";
		if ($Report->ShowActions) DrawDateStat($Report->$StatArrName, $ActionGraph, $Params);

		$SaleGraph=new StatGraph("SaleGraph");
		if ($Report->ShowSales) {$SaleGraph->Display=$NotShowed; $NotShowed=false;}
		$SaleGraph->Name=$Lang['SaleGraphName'];
		$SaleGraph->Vars['disp_type']="diagram";
		$SaleGraph->Vars['disp_x_caption']=$TimeName;
		$SaleGraph->Vars['disp_y_caption']=$Lang['Visitors'];
		$SaleGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrSales']);
		$SaleGraph->Vars['line0_description']="";
		$SaleGraph->Vars['line0_color']="FF3300";
		$SaleGraph->Vars['line0_points']="";
		$SaleGraph->Vars['line1_caption']=htmlspecialchars($Lang['Visitors']);
		$SaleGraph->Vars['line1_description']="";
		$SaleGraph->Vars['line1_color']="669966";
		$SaleGraph->Vars['line1_points']="";
		$Params[0]="CntSale";
		$Params[1]="UniSale";
		if ($Report->ShowSales) DrawDateStat($Report->$StatArrName, $SaleGraph, $Params);

		$ActionConvGraph=new StatGraph("ActionConvGraph");
		if ($Report->ShowActionConv||$Report->ShowPrevActionConv) {$ActionConvGraph->Display=$NotShowed; $NotShowed=false;}
		$ActionConvGraph->Name=$Lang['GraphActionConvName'];
		$ActionConvGraph->Vars['disp_type']="diagram";
		$ActionConvGraph->Vars['disp_x_caption']=$TimeName;
		$ActionConvGraph->Vars['disp_y_caption']=$Lang['Conversion'];
		$ActionConvGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrActionConv']);
		$ActionConvGraph->Vars['line0_description']="";
		$ActionConvGraph->Vars['line0_color']="669966";
		$ActionConvGraph->Vars['line0_points']="";
		$Params[0]="ActionConv";
		if ($Report->ShowActionConv||$Report->ShowPrevActionConv) DrawDateStat($Report->$StatArrName, $ActionConvGraph, $Params);

		$SaleConvGraph=new StatGraph("SaleConvGraph");
		if ($Report->ShowSaleConv) {$SaleConvGraph->Display=$NotShowed; $NotShowed=false;}
		$SaleConvGraph->Name=$Lang['GraphSaleConvName'];
		$SaleConvGraph->Vars['disp_type']="diagram";
		$SaleConvGraph->Vars['disp_x_caption']=$TimeName;
		$SaleConvGraph->Vars['disp_y_caption']=$Lang['Conversion'];
		$SaleConvGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrSaleConv']);
		$SaleConvGraph->Vars['line0_description']="";
		$SaleConvGraph->Vars['line0_color']="669966";
		$SaleConvGraph->Vars['line0_points']="";
		$Params[0]="SaleConv";
		if ($Report->ShowSaleConv) DrawDateStat($Report->$StatArrName, $SaleConvGraph, $Params);


		if ($SaveMode=="PAID") {
			$ROIGraph=new StatGraph("ROIGraph");
			if ($Report->ShowROI) {$ROIGraph->Display=$NotShowed; $NotShowed=false;}
			$ROIGraph->Vars['disp_type']="diagram";
			$ROIGraph->Vars['disp_x_caption']=$TimeName;
			$ROIGraph->Vars['disp_y_caption']=$Lang['ROI'];
			$ROIGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrROI']);
			$ROIGraph->Vars['line0_description']="";
			$ROIGraph->Vars['line0_color']="669966";
			$ROIGraph->Vars['line0_points']="";
			$ROIGraph->Name=$Lang['GraphROIName'];
			$Params[0]="ROI";
			if (!$Report->NoROI) DrawDateStat($Report->$StatArrName, $ROIGraph, $Params);

			$CostGraph=new StatGraph("CostGraph");
			if ($Report->ShowROI) {$CostGraph->Display=$NotShowed; $NotShowed=false;}
			$CostGraph->Vars['disp_type']="diagram";
			$CostGraph->Vars['disp_x_caption']=$TimeName;
			$CostGraph->Vars['disp_y_caption']=$Lang['Cost'];
			$CostGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrCost']);
			$CostGraph->Vars['line0_description']="";
			$CostGraph->Vars['line0_color']="669966";
			$CostGraph->Vars['line0_points']="";
			$CostGraph->Name=$Lang['GraphCostName'];
			$Params[0]="CampCost";
			if (!$Report->NoCost) DrawDateStat($Report->$StatArrName, $CostGraph, $Params);

			$IncomeGraph=new StatGraph("IncomeGraph");
			if ($Report->ShowROI) {$IncomeGraph->Display=$NotShowed; $NotShowed=false;}
			$IncomeGraph->Vars['disp_type']="diagram";
			$IncomeGraph->Vars['disp_x_caption']=$TimeName;
			$IncomeGraph->Vars['disp_y_caption']=$Lang['Income'];
			$IncomeGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrIncome']);
			$IncomeGraph->Vars['line0_description']="";
			$IncomeGraph->Vars['line0_color']="669966";
			$IncomeGraph->Vars['line0_points']="";
			$IncomeGraph->Name=$Lang['GraphIncomeName'];
			$Params[0]="TotalIncome";
			if (!$Report->NoIncome) DrawDateStat($Report->$StatArrName, $IncomeGraph, $Params);
		}

		if ($SaveMode=="NATURAL") {
			$IncomeGraph=new StatGraph("IncomeGraph");
			if ($Report->ShowSales) {$IncomeGraph->Display=$NotShowed; $NotShowed=false;}
			$IncomeGraph->Vars['disp_type']="diagram";
			$IncomeGraph->Vars['disp_x_caption']=$TimeName;
			$IncomeGraph->Vars['disp_y_caption']=$Lang['Income'];
			$IncomeGraph->Vars['line0_caption']=htmlspecialchars($Lang['GrIncome']);
			$IncomeGraph->Vars['line0_description']="";
			$IncomeGraph->Vars['line0_color']="669966";
			$IncomeGraph->Vars['line0_points']="";
			$IncomeGraph->Name=$Lang['GraphIncomeName'];
			$Params[0]="Income";
			if ($Report->ShowSales) DrawDateStat($Report->$StatArrName, $IncomeGraph, $Params);
		}

		$DateCanDump=$ClickGraph->CanDump+ $ActionGraph->CanDump+ $SaleGraph->CanDump+ $ActionConvGraph->CanDump+ $SaleConvGraph->CanDump+ ValidVar($ROIGraph->CanDump)+ ValidVar($CostGraph->CanDump)+ ValidVar($IncomeGraph->CanDump);




function DrawDateStat($DayStatArr, &$Graph, $Params)
{
	global $Lang, $DateFormat, $OrderBy;
	usort($DayStatArr, "SortArrByDate");

	$DateRatio=12-(MaxNameLength("DayOfWeekShort")-2);

	if ($DateFormat=="Date") $Graph->Vars['ixes_ratio']=$DateRatio;
	if ($DateFormat=="Month") $Graph->Vars['ixes_ratio']=12;
	if ($DateFormat=="Time") $Graph->Vars['ixes_ratio']=24;
	if ($DateFormat=="Year") $Graph->Vars['ixes_ratio']=20;
	if ($DateFormat=="WeekDay") $Graph->Vars['ixes_ratio']=7;

	$Max=0;
	if (!ValidArr($DayStatArr)) return false;
	foreach ($DayStatArr as $Day=>$Row) if ($Max<$Row[$Params[0]]) $Max=$Row[$Params[0]];
	if ($Max<=0) return false;
	$VisZones=$Graph->GraphZone(0, $Max);
	for($i=0;$i<count($VisZones);$i++) $Graph->Vars["disp_y$i"]=$VisZones[$i];
	$i=count($DayStatArr)-1;
	$j=0;
	for($z=0;$z<count($Params);$z++) $Graph->Vars['line'.$z.'_points']="";
	foreach ($DayStatArr as $Day=>$Row) {
		if ($DateFormat=="Date") {
			$Graph->Vars["disp_x$i"]=date("d.m",$Row['Obj']->USTAMP).",".$Lang['DayOfWeekShort'][date("w", $Row['Obj']->USTAMP)];
		}
		if ($DateFormat=="Month") {
			$Graph->Vars["disp_x$i"]=$Lang['MonthName'][intval(date("m",$Row['Obj']->USTAMP))];
		}
		if ($DateFormat=="Time") {
			$Graph->Vars["disp_x$i"]=date("H",$Row['Obj']->USTAMP);
		}
		if ($DateFormat=="Year") {
			$Graph->Vars["disp_x$i"]=date("Y",$Row['Obj']->USTAMP);
		}
		if ($DateFormat=="WeekDay") {
			$Graph->Vars["disp_x$i"]=$Lang['DayOfWeek'][date("w",$Row['Obj']->USTAMP)];
		}
		for($z=0;$z<count($Params);$z++) {
			$ZoneValue=$Graph->ZoneValue($VisZones, $Row[$Params[$z]]);
			$Graph->Vars['line'.$z.'_points'].="$i:$ZoneValue,";
		}
		$i--; $j++;
		if ($j>1) $Graph->CanDump=true;
	}
}


function SortArrByDate($a, $b)
{
	global $OrderTo, $OrderBy, $GroupBy;
	if ($GroupBy=="Time") $Key="NAME";
	else $Key="USTAMP";
	$To="DESC";
	
	$KeyA=intval($a['Obj']->$Key);
	$KeyB=intval($b['Obj']->$Key);

  	if ($KeyA < $KeyB) {
  		return ($To == 'ASC' ? -1 : 1);
  	} else if ($KeyA > $KeyB) {
  		return ($To == 'ASC' ? 1 : -1);
  	} else {
  		return 0;
  	}
}

function MaxNameLength($Key="DayOfWeekShort")
{
	global $Lang;
	$Max=0;
	for ($i=0;$i<count($Lang[$Key]);$i++) {
		if ($Max<strlen($Lang[$Key][$i])) $Max=strlen($Lang[$Key][$i]);
	}
	return $Max;
}

?>