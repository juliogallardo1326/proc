<?

$NotShowed=true;

$CurPosition=($CurrentCompany->CUR[1]==0)?"type_before":"type_after";
$NoPerc=($CurPosition=="type_before")?"type_after":"type_before";

$CntClickGraph=new StatGraph("CntClickGraph");
if ($Report->ShowVisitors) {$CntClickGraph->Display=$NotShowed; $NotShowed=false;}
$CntClickGraph->Name=$Lang['ClickPieName'];
$CntClickGraph->Vars['disp_type']="pie";
$Param="CntClick";
if ($Report->ShowVisitors) DrawPie($Report->$StatArrName, $CntClickGraph, $Param);

$UniClickGraph=new StatGraph("UniClickGraph");
if ($Report->ShowVisitors) {$UniClickGraph->Display=$NotShowed; $NotShowed=false;}
$UniClickGraph->Vars['disp_type']="pie";
$UniClickGraph->Name=$Lang['UniPieName'];
$Param="UniClick";
if ($Report->ShowVisitors) DrawPie($Report->$StatArrName, $UniClickGraph, $Param);

$CntActionGraph=new StatGraph("CntActionGraph");
if ($Report->ShowActions) {$CntActionGraph->Display=$NotShowed; $NotShowed=false;}
$CntActionGraph->Vars['disp_type']="pie";
$CntActionGraph->Name=$Lang['ActionPieName'];
$Param="CntAction";
if ($Report->ShowActions) DrawPie($Report->$StatArrName, $CntActionGraph, $Param);

$UniActionGraph=new StatGraph("UniActionGraph");
if ($Report->ShowActions) {$UniActionGraph->Display=$NotShowed; $NotShowed=false;}
$UniActionGraph->Vars['disp_type']="pie";
$UniActionGraph->Name=$Lang['UniActionPieName'];
$Param="UniAction";
if ($Report->ShowActions) DrawPie($Report->$StatArrName, $UniActionGraph, $Param);

$CntSaleGraph=new StatGraph("CntSaleGraph");
if ($Report->ShowSales) {$CntSaleGraph->Display=$NotShowed; $NotShowed=false;}
$CntSaleGraph->Vars['disp_type']="pie";
$CntSaleGraph->Name=$Lang['SalePieName'];
$Param="CntSale";
if ($Report->ShowSales) DrawPie($Report->$StatArrName, $CntSaleGraph, $Param);

$UniSaleGraph=new StatGraph("UniSaleGraph");
if ($Report->ShowSales) {$UniSaleGraph->Display=$NotShowed; $NotShowed=false;}
$UniSaleGraph->Vars['disp_type']="pie";
$UniSaleGraph->Name=$Lang['UniSalePieName'];
$Param="UniSale";
if ($Report->ShowSales) DrawPie($Report->$StatArrName, $UniSaleGraph, $Param);

$SaleConvGraph=new StatGraph("SaleConvGraph");
if ($Report->ShowSaleConv) {$SaleConvGraph->Display=$NotShowed; $NotShowed=false;}
$SaleConvGraph->Vars['disp_type']="pie";
$SaleConvGraph->Name=$Lang['ActionConvPieName'];
$Param="SaleConv";
if ($Report->ShowSaleConv) DrawPie($Report->$StatArrName, $SaleConvGraph, $Param);

$ActionConvGraph=new StatGraph("ActionConvGraph");
if ($Report->ShowActionConv||$Report->ShowPrevActionConv) {$ActionConvGraph->Display=$NotShowed; $NotShowed=false;}
$ActionConvGraph->Vars['disp_type']="pie";
$ActionConvGraph->Vars['real_percent']="no";
$ActionConvGraph->Name=$Lang['SaleConvPieName'];
$Param="ActionConv";
if ($Report->ShowActionConv||$Report->ShowPrevActionConv) DrawPie($Report->$StatArrName, $ActionConvGraph, $Param);

if ($SaveMode=="PAID") {
	$ROIGraph=new StatGraph("ROIGraph");
	if ($Report->ShowROI) {$ROIGraph->Display=$NotShowed; $NotShowed=false;}
	$ROIGraph->Vars['disp_type']="pie";
	$ROIGraph->Vars['real_percent']="no";
	$ROIGraph->Name=$Lang['RoiPieName'];
	$Param="ROI";
	if (!$Report->NoROI) DrawPie($Report->$StatArrName, $ROIGraph, $Param);

	$CostGraph=new StatGraph("CostGraph");
	if ($Report->ShowROI) {$CostGraph->Display=$NotShowed; $NotShowed=false;}
	$CostGraph->Vars['disp_type']="pie";
	$CostGraph->Vars['real_percent']="no";
	$CostGraph->Vars[$CurPosition]=($CurrentCompany->CUR[0])?$CurrentCompany->CUR[0]:" ";
	$CostGraph->Vars[$NoPerc]=" ";
	$CostGraph->Name=$Lang['CostPieName'];
	$Param="CampCost";
	if (!$Report->NoCost) DrawPie($Report->$StatArrName, $CostGraph, $Param);

	$IncomeGraph=new StatGraph("IncomeGraph");
	if ($Report->ShowROI) {$IncomeGraph->Display=$NotShowed; $NotShowed=false;}
	$IncomeGraph->Vars['disp_type']="pie";
	$IncomeGraph->Vars['real_percent']="no";
	$IncomeGraph->Vars[$CurPosition]=($CurrentCompany->CUR[0])?$CurrentCompany->CUR[0]:" ";
	$IncomeGraph->Vars[$NoPerc]=" ";
	$IncomeGraph->Name=$Lang['IncomePieName'];
	$Param="TotalIncome";
	if (!$Report->NoIncome) DrawPie($Report->$StatArrName, $IncomeGraph, $Param);
}

if ($SaveMode=="NATURAL") {
	$IncomeGraph=new StatGraph("IncomeGraph");
	if ($Report->ShowSales) {$IncomeGraph->Display=$NotShowed; $NotShowed=false;}
	$IncomeGraph->Vars['disp_type']="pie";
	$IncomeGraph->Vars['real_percent']="no";
	$IncomeGraph->Vars[$CurPosition]=($CurrentCompany->CUR[0])?$CurrentCompany->CUR[0]:" ";
	$IncomeGraph->Vars[$NoPerc]=" ";
	$IncomeGraph->Name=$Lang['IncomePieName'];
	$Param="Income";
	if ($Report->ShowSales) DrawPie($Report->$StatArrName, $IncomeGraph, $Param);
}

$PieCanDump=$CntClickGraph->CanDump+ $UniClickGraph->CanDump+ $CntActionGraph->CanDump+ $UniActionGraph->CanDump+ $CntSaleGraph->CanDump+ $UniSaleGraph->CanDump+ $SaleConvGraph->CanDump+ $ActionConvGraph->CanDump+ ValidVar($ROIGraph->CanDump)+ ValidVar($CostGraph->CanDump)+ ValidVar($IncomeGraph->CanDump);

function DrawPie(&$StatArr, &$Graph, $Param)
{
	global $Lang;
	$Colors[]="330099";
	$Colors[]="FFCC00";
	$Colors[]="339933";
	$Colors[]="CC6600";
	$Colors[]="FF0099";
	$Colors[]="9999FF";
	$Colors[]="FFFF00";
	$Colors[]="FF0000";
	$Colors[]="336699";

	$i=0;
	$Color=0;
	$Others=0;
	$Sum=0;
	$OtherArr=array();
	if (!isset($Graph->Vars['real_percent'])) $Graph->Vars['real_percent']="yes";

	foreach ($StatArr as $Key=>$Row) {
		$Name=stripslashes($Row['Obj']->NAME);
		$Name=str_replace("&", urlencode("&"), $Name);
		$Name=str_replace("=", urlencode("="), $Name);
		$Name=htmlspecialchars($Name);

		if ($i>9||(ValidVar($Row[$Param."Perc"])&&$Row[$Param."Perc"]<2)) {
			$Others+=$Row[$Param];
			$OtherArr[]=$Row;
			continue;
		}
		if ($Row[$Param]<=0) continue;
		if ($Color==count($Colors)-1) $Color=0;
		$Graph->Vars['piece'.$i.'_caption']=$Name;
		$Graph->Vars['piece'.$i.'_value']=$Row[$Param];
		$Graph->Vars['piece'.$i.'_color']=$Colors[$Color];
		$Graph->Vars['piece'.$i.'_description']="";
		$i++;
		$Color++;
		$Sum+=$Row[$Param];
	}
	if (count($OtherArr)==1) {
		$Row=$OtherArr[0];
		$Name=stripslashes($Row['Obj']->NAME);
		$Name=str_replace("&", urlencode("&"), $Name);
		$Name=str_replace("=", urlencode("="), $Name);
		$Name=htmlspecialchars($Name);
		$Graph->Vars['piece'.$i.'_caption']=$Name;
		$Graph->Vars['piece'.$i.'_value']=$Row[$Param];
		$Graph->Vars['piece'.$i.'_color']="cccccc";
		$Graph->Vars['piece'.$i.'_description']="";
		$Others=0;
		$Sum+=$Row[$Param];
	}
	if ($Others>0) {
		if ($Graph->Vars['real_percent']=="no") {
			$Others=100-$Sum;
		}
		$Graph->Vars['piece'.$i.'_caption']=$Lang['Other'];
		$Graph->Vars['piece'.$i.'_value']=$Others;
		$Graph->Vars['piece'.$i.'_color']="cccccc";
		$Graph->Vars['piece'.$i.'_description']="";
	}
	if ($i>1) $Graph->CanDump=true;
}



?>