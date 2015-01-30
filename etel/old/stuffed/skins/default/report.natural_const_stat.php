

<SCRIPT LANGUAGE="JavaScript">
<!--
var Columns= new Array();
<?if ($nsUser->Columns->HITS) {?>
	Columns.push("CntClick");
	Columns.push("UniClick");
<?}?>
<?if ($nsUser->Columns->SALES) {?>
	Columns.push("CntSale");
	Columns.push("UniSale");
	<?if ($nsUser->Columns->ROI) {?>
		Columns.push("Income");
	<?}?>
<?}?>
<?if ($nsUser->Columns->ACTIONS) {?>
	Columns.push("CntAction");
	Columns.push("UniAction");
<?}?>
<?if ($nsUser->Columns->ACTIONS&&$nsUser->Columns->CONVERSIONS) {?>
	Columns.push("ActionConv");
<?}?>
<?if ($nsUser->Columns->SALES&&$nsUser->Columns->CONVERSIONS) {?>
	Columns.push("SaleConv");
<?}?>



function SubmitFloatForm(Action)
{
	if (Action=="") return false;
	location.href=location.href+ "&GroupItemAction="+Action+"&GroupItemId="+CurrentDivId+"&CurrentId[<?=$GroupBy?>]="+CurrentDivId;
	return;
}
//-->
</SCRIPT>

<script language="JavaScript" src="<?=FileLink("natural_stat.js");?>"></script>




<table border="0" cellpadding="0" cellspacing="0" width=100%>









<tr height=15>
<td width=20% class="ReportHeaderTd2" rowspan=2 valign=middle style="padding-left:4px;">
<p class="ReportHeaderName<?=(($DefaultOrderBy=="NAME")?"2":"")?>"><a href="<?=$CurrentPath."&OrderBy=NAME&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>"><?=$WhereArr[count($WhereArr)-1]['Name']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=NAME&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="NAME"&&$OrderTo=="DESC")?"2":"").".gif")?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=NAME&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="NAME"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></p>
</td>

<?if ($nsUser->Columns->HITS) {?>
<td class="ReportHeaderTd3" colspan="2"  width=10%>
	<p class="ReportHeaderName<?=(($DefaultOrderBy=="CNT"||$DefaultOrderBy=="UNI")?"2":"")?>">
	<?=$Lang['RHeaderHit']?></p>
</td>
<?}?>

<?if ($nsUser->Columns->SALES) {?>
<td class="ReportHeaderTd3" colspan="<?=(($nsUser->Columns->ROI)?"3":"2")?>"  width=10%>
	<p class="ReportHeaderName<?=(($DefaultOrderBy=="SALECNT" ||$DefaultOrderBy=="SALEUNI")?"2":"")?>">
	<?=$Lang['RHeaderSale']?></p>
</td>
<?}?>

<?if ($nsUser->Columns->ACTIONS) {?>
<td class="ReportHeaderTd3" colspan="2"  width=10%>
	<p class="ReportHeaderName<?=(($DefaultOrderBy=="ACTIONCNT" ||$DefaultOrderBy=="ACTIONUNI")?"2":"")?>">
	<?=$Lang['RHeaderAction']?></p>
</td>
<?}?>

<?if (($nsUser->Columns->SALES ||$nsUser->Columns->ACTIONS)&&$nsUser->Columns->CONVERSIONS) {?>
<td class="ReportHeaderTd<?=(($nsUser->Columns->SALES)?"3":"4")?>" colspan="<?=(($nsUser->Columns->SALES &&$nsUser->Columns->ACTIONS)?"2":"")?>"  width=10%>
	<p class="ReportHeaderName<?=(($DefaultOrderBy=="ACTCONV" ||$DefaultOrderBy=="SALECONV")?"2":"")?>">
	<?=$Lang['RHeaderConversion']?></p>
</td>
<?}?>

</tr>








<tr height=15>


<?if ($nsUser->Columns->HITS) {?>
<td class="ReportHeaderTd4" width=5% ID="CntClickHead">
	<p class="ReportSubHeaderName<?=(($DefaultOrderBy=="CNT")?"2":"")?>"><nobr>	<a href="<?=$CurrentPath."&OrderBy=CNT&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
	<?=$Lang['RHeaderTotal']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=CNT&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="CNT"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=CNT&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="CNT"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>
	</td><td width="5%" class="ReportHeaderTd3" ID="UniClickHead"><p class="ReportSubHeaderName<?=(($DefaultOrderBy=="UNI")?"2":"")?>"><nobr><a href="<?=$CurrentPath."&OrderBy=UNI&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
	<?=$Lang['RHeaderUni']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=UNI&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="UNI"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=UNI&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="UNI"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>
</td>
<?}?>


<?if ($nsUser->Columns->SALES) {?>
<td class="ReportHeaderTd4"  width=5% ID="CntSaleHead">
		<p class="ReportSubHeaderName<?=(($DefaultOrderBy=="SALECNT")?"2":"")?>"><nobr><a href="<?=$CurrentPath."&OrderBy=SALECNT&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
	  <?=$Lang['RHeaderTotal']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=SALECNT&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="SALECNT"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=SALECNT&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="SALECNT"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>
	</td><td width="5%" class="ReportHeaderTd<?=(($nsUser->Columns->ROI)?"4":"3")?>" ID="UniSaleHead">
		<p class="ReportSubHeaderName<?=(($DefaultOrderBy=="SALEUNI")?"2":"")?>"><nobr><a href="<?=$CurrentPath."&OrderBy=SALEUNI&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
	  <?=$Lang['RHeaderUni']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=SALEUNI&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="SALEUNI"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=SALEUNI&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="SALEUNI"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>
	</td>
	<?if ($nsUser->Columns->ROI) {?>
	<td width="5%" class="ReportHeaderTd3" ID="IncomeSaleHead">
		<p class="ReportSubHeaderName<?=(($DefaultOrderBy=="INCOME")?"2":"")?>"><nobr><a href="<?=$CurrentPath."&OrderBy=INCOME&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
	  <?=$Lang['RHeaderIncome']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=INCOME&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="INCOME"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=INCOME&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="INCOME"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>
	  <?}?>
</td>
<?}?>

<?if ($nsUser->Columns->ACTIONS) {?>
<td class="ReportHeaderTd4" width=5% ID="CntActionHead">
	<p class="ReportSubHeaderName<?=(($DefaultOrderBy=="ACTIONCNT")?"2":"")?>"><nobr><a href="<?=$CurrentPath."&OrderBy=ACTIONCNT&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
	  <?=$Lang['RHeaderTotal']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=ACTIONCNT&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="ACTIONCNT"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=ACTIONCNT&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="ACTIONCNT"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>

	</td><td width="5%" class="ReportHeaderTd3" ID="UniActionHead">
	<p class="ReportSubHeaderName<?=(($DefaultOrderBy=="ACTIONUNI")?"2":"")?>"><nobr><a href="<?=$CurrentPath."&OrderBy=ACTIONUNI&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
	  <?=$Lang['RHeaderUni']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=ACTIONUNI&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="ACTIONUNI"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=ACTIONUNI&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="ACTIONUNI"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>
</td>
<?}?>


<?if ($nsUser->Columns->ACTIONS&&$nsUser->Columns->CONVERSIONS) {?>
<td class="ReportHeaderTd4"  width=5% ID="ActionConvHead">
	<p class="ReportSubHeaderName<?=(($DefaultOrderBy=="ACTCONV")?"2":"")?>"><nobr><a href="<?=$CurrentPath."&OrderBy=ACTCONV&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
  <?=$Lang['RHeaderAction']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=ACTCONV&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="ACTCONV"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=ACTCONV&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="ACTCONV"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>
	</td>
<?}?>
<?if ($nsUser->Columns->SALES&&$nsUser->Columns->CONVERSIONS) {?>
	<td width="5%" class="ReportHeaderTd3" ID="SaleConvHead">
	<p class="ReportSubHeaderName<?=(($DefaultOrderBy=="SALECONV")?"2":"")?>"><nobr><a href="<?=$CurrentPath."&OrderBy=SALECONV&OrderTo=".(($OrderTo=="DESC")?"ASC":"DESC")?>">
  <?=$Lang['RHeaderSale']?></a>&nbsp;<a href="<?=$CurrentPath."&OrderBy=SALECONV&OrderTo=DESC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_down".(($DefaultOrderBy=="SALECONV"&&$OrderTo=="DESC")?"2":"").".gif");?>" width="9" height="5" border="0"></a><a href="<?=$CurrentPath."&OrderBy=SALECONV&OrderTo=ASC"?>" class=NoPrint><img src="<?=FileLink("images/arrow_up".(($DefaultOrderBy=="SALECONV"&&$OrderTo=="ASC")?"2":"").".gif");?>" width="9" height="5" border="0"></a></nobr></p>
</td>
<?}?>

</tr>






<tr>
<td width="100%" height="2" colspan="12" class=ReportRowSep>
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="2" border="0"></p>
</td>
</tr>


<?foreach ($Report->StatArr as $i=>$Row)  {?>

		
<tr ID="TR_<?=$Row['Position']?>">
<td  width=20% class="ReportNameTd" height="23"  ID="Row_<?=$Row['Position']?>"  onmouseover="HighLightRow('CntClick', <?=$Row['Position']?>);" onmouseout="RemoveLight('CntClick',<?=$Row['Position']?>);" >
<p class=ReportColumn><B>
<a href="<?=getURL("natural_constructor","CurrentId[$GroupBy]=".$Row['Obj']->ID.$Get, "report")?>" onclick="CurrentDiv=-1;">
<span title="<?=htmlspecialchars(urldecode(stripslashes($Row['Name'])))?>">
<?
if ($GroupBy=="Ref") echo RefCut(htmlspecialchars(urldecode(stripslashes($Row['Name'])), 40));
else echo htmlspecialchars(urldecode(stripslashes($Row['Name'])));
?>
</span></a></B>

	<?if ($GroupBy=="Ref") {?>
	&nbsp;[<a href="<?=htmlspecialchars($Row['Name'])?>" target=_blank>^</a>]
	<?}?>

	<?if ($GroupBy=="Vis") {?>
	&nbsp;[<a href="<?=getURL("visitor_path", "CpId=$CpId&ViewDate=$ViewDate&VisId=".$Row['Obj']->VISITOR_ID, "report")?>" title="<?=$Lang['VisPath']?>">^</a>]
	<?}?>

	<? if ($GroupBy=="Page") {?>
	&nbsp;[<a href="http://<?=$Row['Obj']->SITE_HOST.$Row['Obj']->NAME?>" target=_blank>^</a>]
	<?}?>
<span ID="RowDiv_<?=$Row['Position']?>" style="display:none;position:absolute;"></span>
</p>

</td>

<?if ($nsUser->Columns->HITS) {?>
<td class="ReportSimpleTd2" height="23"  ID="CntClick_<?=$Row['Position']?>" onmouseover="HighLightRow('CntClick', <?=$Row['Position']?>);" onmouseout="RemoveLight('CntClick',<?=$Row['Position']?>);" onclick="CheckRowCol('CntClick',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowVisitors) {?>
<?=($Row['CntClick']>0)?"<b>":""?><?=number_format($Row['CntClick'], ",")?>
<?}?>
<?if ($Row['CntClickPerc']>0) {?></b><br><span class=ReportSubColumn> (<?=$Row['CntClickPerc']?>%)</span><?}?>
</p>
</td>
<td class="ReportSimpleTd" height="23"  ID="UniClick_<?=$Row['Position']?>" onmouseover="HighLightRow('UniClick', <?=$Row['Position']?>);" onmouseout="RemoveLight('UniClick',<?=$Row['Position']?>);" onclick="CheckRowCol('UniClick',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowVisitors) {?>
<?=($Row['UniClick']>0)?"<b>":""?><?=number_format($Row['UniClick'], ",")?>
<?}?>
<?if ($Row['UniClickPerc']>0) {?></b><br><span class=ReportSubColumn> (<?=$Row['UniClickPerc']?>%)</span><?}?>
</p>
</td>
<?}?>

<?if ($nsUser->Columns->SALES) {?>
<td class="ReportSimpleTd2" height="23"   ID="CntSale_<?=$Row['Position']?>" onmouseover="HighLightRow('CntSale', <?=$Row['Position']?>);" onmouseout="RemoveLight('CntSale',<?=$Row['Position']?>);" onclick="CheckRowCol('CntSale',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowSales) {?>
<?=($Row['CntSale']>0)?"<b>":""?><?=number_format($Row['CntSale'], ",")?>
<?if ($Row['CntSalePerc']>0) {?></b><br><span class=ReportSubColumn> (<?=$Row['CntSalePerc']?>%)</span><?}?>
<?}?>
</p>
</td>
<td class="ReportSimpleTd2" height="23"    ID="UniSale_<?=$Row['Position']?>" onmouseover="HighLightRow('UniSale', <?=$Row['Position']?>);" onmouseout="RemoveLight('UniSale',<?=$Row['Position']?>);" onclick="CheckRowCol('UniSale',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowSales) {?>
<?=($Row['UniSale']>0)?"<b>":""?><?=number_format($Row['UniSale'], ",")?>
<?if ($Row['UniSalePerc']>0) {?></b><br><span class=ReportSubColumn> (<?=$Row['UniSalePerc']?>%)</span><?}?>
<?}?>
</p>
</td>
<?if ($nsUser->Columns->ROI) {?>
<td class="ReportSimpleTd" height="23"    ID="Income_<?=$Row['Position']?>" onmouseover="HighLightRow('Income', <?=$Row['Position']?>);" onmouseout="RemoveLight('Income',<?=$Row['Position']?>);" onclick="CheckRowCol('Income',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowSales) {?>
<?=($Row['Income']>0)?"<b>":""?><?=ShowCost($Row['Income'], $CurrentCompany->CUR)?>
<?} else echo "&nbsp;";?>
</b>
<?if ($Row['UniSalePerc']>0||$Row['CntSalePerc']>0) {?><br><span class=ReportSubColumn>&nbsp;</span><?}?>
</p>
</td>
<?}?>
<?}?>

<?if ($nsUser->Columns->ACTIONS) {?>
<td class="ReportSimpleTd2" height="23"    ID="CntAction_<?=$Row['Position']?>" onmouseover="HighLightRow('CntAction', <?=$Row['Position']?>);" onmouseout="RemoveLight('CntAction',<?=$Row['Position']?>);" onclick="CheckRowCol('CntAction',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowActions) {?>
<?=($Row['CntAction']>0)?"<b>":""?><?=number_format($Row['CntAction'], ",")?>
<?}?>
<?if ($Row['CntActionPerc']>0) {?></b><br><span class=ReportSubColumn> (<?=$Row['CntActionPerc']?>%)</span><?}?>
</p>
</td>
<td class="ReportSimpleTd" height="23"    ID="UniAction_<?=$Row['Position']?>" onmouseover="HighLightRow('UniAction', <?=$Row['Position']?>);" onmouseout="RemoveLight('UniAction',<?=$Row['Position']?>);" onclick="CheckRowCol('UniAction',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowActions) {?>
<?=($Row['UniAction']>0)?"<b>":""?><?=number_format($Row['UniAction'], ",")?>
<?}?>
<?if ($Row['UniActionPerc']>0) {?></b><br><span class=ReportSubColumn> (<?=$Row['UniActionPerc']?>%)</span><?}?>
</p>
</td>
<?}?>

<?if ($nsUser->Columns->ACTIONS&&$nsUser->Columns->CONVERSIONS) {?>
<td class="ReportSimpleTd2" height="23"    ID="ActionConv_<?=$Row['Position']?>" onmouseover="HighLightRow('ActionConv', <?=$Row['Position']?>);" onmouseout="RemoveLight('ActionConv',<?=$Row['Position']?>);" onclick="CheckRowCol('ActionConv',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowActionConv||$Report->ShowPrevActionConv) {?>
<?=($Row['ActionConv']>0)?"<b>":""?><?=$Row['ActionConv']?>%</b><br><span class=ReportSubColumn>(<?=$Report->OneOf($Row['UniAction'], $Row['UniClick'])?>)</span>
<?}?>
</p>
</td>
<?}?>
<?if ($nsUser->Columns->SALES&&$nsUser->Columns->CONVERSIONS) {?>
<td class="ReportSimpleTd" height="23"  ID="SaleConv_<?=$Row['Position']?>" onmouseover="HighLightRow('SaleConv', <?=$Row['Position']?>);" onmouseout="RemoveLight('SaleConv',<?=$Row['Position']?>);" onclick="CheckRowCol('SaleConv',<?=$Row['Position']?>);" >
<p class=ReportColumn>
<?if ($Report->ShowSaleConv) {?>
<?=($Row['SaleConv']>0)?"<b>":""?><?=$Row['SaleConv']?>%</b><br><span class=ReportSubColumn>(<?=$Report->OneOf($Row['UniSale'], $Row['UniClick'])?>)</span>
<?}?>
</p>
</td>
<?}?>

</tr>

<tr>
<td width="100%" height="1" colspan="12" class=ReportRowSep>
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
</td>
</tr>

<?}?>


<?if ($nsUser->Columns->GRAPHS) {?>



<?if (($GroupBy=="Date"||$GroupBy=="Month"||$GroupBy=="Time"||
		 $GroupBy=="Year"||$GroupBy=="WeekDay")&&$DateCanDump) {?>
<tr class=NoPrint>
<td class=ReportHeaderTd2>&nbsp;</td>

<?if ($nsUser->Columns->HITS) {?>
<td class=ReportSimpleTd2 colspan=2>
<?if ($Report->ShowVisitors&&$ClickGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('ClickGraph')"><img src="<?=FileLink("images/icon_graph.gif");?>" width="22" height="19" border="0" title="<?=$Lang['GrHits']?>"  class=NoPrint></a><?}?>
</td>
<?}?>

<?if ($nsUser->Columns->SALES) {?>
<td class=ReportSimpleTd2 colspan=2>
<?if ($Report->ShowSales&&$SaleGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('SaleGraph')"><img src="<?=FileLink("images/icon_graph.gif");?>" width="22" height="19" border="0" title="<?=$Lang['GrSales']?>"></a><?}?>
</td>
<?if ($nsUser->Columns->ROI) {?>
<td class=ReportSimpleTd2 ><?if ($Report->ShowSales&&$IncomeGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('IncomeGraph')"><img src="<?=FileLink("images/icon_graph.gif");?>" width="22" height="19" border="0" title="<?=$Lang['GrIncome']?>" class=NoPrint></a><?}?></td>
<?}?>
<?}?>

<?if ($nsUser->Columns->ACTIONS) {?>
<td class=ReportSimpleTd2 colspan=2>
<?if ($Report->ShowActions&&$ActionGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('ActionGraph')"><img src="<?=FileLink("images/icon_graph.gif");?>" width="22" height="19" border="0" title="<?=$Lang['GrActions']?>" class=NoPrint></a><?}?>
</td>
<?}?>

<?if ($nsUser->Columns->ACTIONS&&$nsUser->Columns->CONVERSIONS) {?>
<td class=ReportSimpleTd2>
<?if (($Report->ShowActionConv||$Report->ShowPrevActionConv)&&$ActionConvGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('ActionConvGraph')"><img src="<?=FileLink("images/icon_graph.gif");?>" width="22" height="19" border="0" title="<?=$Lang['GrActionConv']?>" class=NoPrint></a><?}?>
</td>
<?}?>
<?if ($nsUser->Columns->SALES&&$nsUser->Columns->CONVERSIONS) {?>
<td class=ReportSimpleTd2>
<?if (($Report->ShowSaleConv)&&$SaleConvGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('SaleConvGraph')"><img src="<?=FileLink("images/icon_graph.gif");?>" width="22" height="19" border="0" title="<?=$Lang['GrSaleConv']?>" class=NoPrint></a><?}?>
</td>
<?}?>

</tr>
<?}?>




<?if ($GroupBy!="General"&&$GroupBy!="Date"&&$GroupBy!="Month"&&
		$GroupBy!="Time"&&$GroupBy!="Year"&&$GroupBy!="WeekDay"&&$PieCanDump) {?>
<tr class=NoPrint>
<td class=ReportHeaderTd2 width=20%>&nbsp;</td>

<?if ($nsUser->Columns->HITS) {?>
<td class=ReportSimpleTd2>
<?if ($Report->ShowVisitors&&$CntClickGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('CntClickGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieHits']?>" class=NoPrint></a><?}?>
</td>
<td class=ReportSimpleTd2>
<?if ($Report->ShowVisitors&&$UniClickGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('UniClickGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieUni']?>" class=NoPrint></a><?}?>
</td>
<?}?>

<?if ($nsUser->Columns->SALES) {?>
<td class=ReportSimpleTd2>
<?if ($Report->ShowSales&&$CntSaleGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('CntSaleGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieSales']?>" class=NoPrint></a><?}?>
</td>
<td class=ReportSimpleTd2>
<?if ($Report->ShowSales&&$UniSaleGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('UniSaleGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieUniSale']?>" class=NoPrint></a><?}?>
</td>
<?if ($nsUser->Columns->ROI) {?>
<td class=ReportSimpleTd2><?if ($Report->ShowSales&&$IncomeGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('IncomeGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieIncome']?>"></a><?}?></td>
<?}?>
<?}?>

<?if ($nsUser->Columns->ACTIONS) {?>
<td class=ReportSimpleTd2>
<?if ($Report->ShowActions&&$CntActionGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('CntActionGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieActions']?>" class=NoPrint></a><?}?>
</td>
<td class=ReportSimpleTd2>
<?if ($Report->ShowActions&&$UniActionGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('UniActionGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieUniAction']?>" class=NoPrint></a><?}?>
</td>
<?}?>

<?if ($nsUser->Columns->ACTIONS&&$nsUser->Columns->CONVERSIONS) {?>
<td class=ReportSimpleTd2>
<?if (($Report->ShowActionConv||$Report->ShowPrevActionConv)&&$ActionConvGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('ActionConvGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieActionConv']?>" class=NoPrint></a><?}?>
</td>
<?}?>
<?if ($nsUser->Columns->SALES&&$nsUser->Columns->CONVERSIONS) {?>
<td class=ReportSimpleTd2>
<?if (($Report->ShowSaleConv)&&$SaleConvGraph->CanDump) {?><a href="javascript:;" onclick="SwitchGraph('SaleConvGraph')"><img src="<?=FileLink("images/icon_pie.gif");?>" width="22" height="19" border="0" title="<?=$Lang['PieSaleConv']?>" class=NoPrint></a><?}?>
</td>
<?}?>
</tr>
<?}?>
<?}?>

		
</table>
<!---->

<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">


