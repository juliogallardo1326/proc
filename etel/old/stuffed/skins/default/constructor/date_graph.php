<!-- Graphs -->	 
<div style="border-style:solid;border-width:1px;border-color:#c7c7c7">

<?if ($SaveMode=="PAID"&&$ClickGraph->CanDump&&$nsUser->Columns->CLICKS) $ClickGraph->Dump();?>
<?if ($SaveMode=="NATURAL"&&$ClickGraph->CanDump&&$nsUser->Columns->HITS) $ClickGraph->Dump();?>
<?if ($ActionGraph->CanDump&&$nsUser->Columns->ACTIONS) $ActionGraph->Dump();?>
<?if ($SaleGraph->CanDump&&$nsUser->Columns->SALES) $SaleGraph->Dump();?>

<?if ($SaveMode=="PAID"&&$nsUser->Columns->ROI) {
	if ($ROIGraph->CanDump) $ROIGraph->Dump();
	if ($CostGraph->CanDump) $CostGraph->Dump();
	if ($IncomeGraph->CanDump) $IncomeGraph->Dump();
}?>
<?if ($SaveMode=="NATURAL"&&$nsUser->Columns->SALES) {
	if ($IncomeGraph->CanDump) $IncomeGraph->Dump();
}?>

<?if ($ActionConvGraph->CanDump&&$nsUser->Columns->ACTIONS&&$nsUser->Columns->CONVERSIONS) $ActionConvGraph->Dump();?>
<?if ($SaleConvGraph->CanDump&&$nsUser->Columns->SALES&&$nsUser->Columns->CONVERSIONS) $SaleConvGraph->Dump();?>

</div>
<!--// Graphs -->	 	 