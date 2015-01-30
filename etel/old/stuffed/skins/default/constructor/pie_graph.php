<!-- Graphs -->	 
<div style="border-style:solid;border-width:1px;border-color:#c7c7c7">

<?
if ($SaveMode=="PAID"&&$CntClickGraph->CanDump&&$nsUser->Columns->CLICKS) $CntClickGraph->Dump();
if ($SaveMode=="PAID"&&$UniClickGraph->CanDump&&$nsUser->Columns->CLICKS) $UniClickGraph->Dump();
if ($SaveMode=="NATURAL"&&$CntClickGraph->CanDump&&$nsUser->Columns->HITS) $CntClickGraph->Dump();
if ($SaveMode=="NATURAL"&&$UniClickGraph->CanDump&&$nsUser->Columns->HITS) $UniClickGraph->Dump();

if ($CntActionGraph->CanDump&&$nsUser->Columns->ACTIONS) $CntActionGraph->Dump();
if ($UniActionGraph->CanDump&&$nsUser->Columns->ACTIONS) $UniActionGraph->Dump();
if ($CntSaleGraph->CanDump&&$nsUser->Columns->SALES) $CntSaleGraph->Dump();
if ($UniSaleGraph->CanDump&&$nsUser->Columns->SALES) $UniSaleGraph->Dump();
if ($SaleConvGraph->CanDump&&$nsUser->Columns->SALES&&$nsUser->Columns->CONVERSIONS) $SaleConvGraph->Dump();
if ($ActionConvGraph->CanDump&&$nsUser->Columns->ACTIONS&&$nsUser->Columns->CONVERSIONS) $ActionConvGraph->Dump();
if ($SaveMode=="PAID"&&$nsUser->Columns->ROI) {
	if ($ROIGraph->CanDump) $ROIGraph->Dump();
	if ($CostGraph->CanDump) $CostGraph->Dump();
	if ($IncomeGraph->CanDump) $IncomeGraph->Dump();
}
if ($SaveMode=="NATURAL"&&$nsUser->Columns->ROI) {
	if ($IncomeGraph->CanDump) $IncomeGraph->Dump();
}?>


</div>
<!--// Graphs -->	 	 