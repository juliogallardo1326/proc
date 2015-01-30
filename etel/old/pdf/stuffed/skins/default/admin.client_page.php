<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<table  cellpadding=6 cellspacing=0 border=0 width=100%>


<tr>
<td valign=top><p><a href="<?=getURL("paid_constructor", "CpId=$CompId", "report")?>"><IMG SRC="<?=FileLink("images/big_icon_paid.gif");?>" WIDTH="32" align=middle hspace=5 HEIGHT="32" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("paid_constructor", "CpId=$CompId", "report")?>"><?=$Lang['MPaidAdv']?></a></span><br>
<?=$Lang['MPaidAdvDescr']?>
</td>
<td valign=top><p><a href="<?=getURL("natural_constructor", "CpId=$CompId", "report")?>"><IMG SRC="<?=FileLink("images/big_icon_natural.gif");?>" WIDTH="32" align=middle hspace=5 HEIGHT="32" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("natural_constructor", "CpId=$CompId", "report")?>"><?=$Lang['MNatural']?></a></span><br>
<?=$Lang['MNaturalDescr']?>
</td></tr>


<tr>
<td valign=top><p><a href="<?=getURL("reports", "CpId=$CompId", "admin")?>"><IMG SRC="<?=FileLink("images/big_icon_logs.gif");?>" WIDTH="32" align=middle hspace=5 HEIGHT="32" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("reports", "CpId=$CompId", "admin")?>"><?=$Lang['MLogs']?></a></span><br>
<?=$Lang['MLogsDescr']?>
</td>
<td valign=top><p><a href="<?=getURL("split_list", "CpId=$CompId", "admin")?>"><IMG SRC="<?=FileLink("images/big_icon_split.gif");?>" WIDTH="32" align=middle hspace=5 HEIGHT="32" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("split_list", "CpId=$CompId", "admin")?>"><?=$Lang['MSplits']?></a></span><br>
<?=$Lang['MSplitsDescr']?>
</td></tr>



<tr>
<td valign=top><p><a href="<?=getURL("actions", "CpId=$CompId", "admin")?>"><IMG SRC="<?=FileLink("images/big_icon_actions.gif");?>" WIDTH="32" align=middle hspace=5 HEIGHT="32" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("actions", "CpId=$CompId", "admin")?>"><?=$Lang['MActions']?></a></span><br>
<?=$Lang['MActionsDescr']?>
</td>
<td valign=top><p><a href="<?=getURL("campaign", "CpId=$CompId", "admin")?>"><IMG SRC="<?=FileLink("images/big_icon_campaign.gif");?>" WIDTH="32" align=middle hspace=5 HEIGHT="32" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("campaign", "CpId=$CompId", "admin")?>"><?=$Lang['MCampaign']?></a></span><br>
<?=$Lang['MCampaignDescr']?>
</td></tr>


<tr>
<td valign=top><p><a href="<?=getURL("settings", "CpId=$CompId", "admin")?>"><IMG SRC="<?=FileLink("images/big_icon_settings.gif");?>" WIDTH="32" align=middle hspace=5 HEIGHT="32" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("settings", "CpId=$CompId", "admin")?>"><?=$Lang['MSettings']?></a></span><br>
<?=$Lang['MSettingsDescr']?>
</td>
<td valign=top><p></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>

</td></tr>


</table>



<IMG SRC="images/0.gif" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">



<?if (ValidArr($NaturalReport->StatArr)) {?>
<div class=FormDiv2>

<table border="0" cellpadding="0" cellspacing="0" width=100%>
<tr>
<td class="ReportHeaderTd2" style="padding-left:6px;"  width=20%><p class="ReportHeaderName"><?=$Lang['Site']?></td>

<?if ($nsUser->Columns->HITS) {?>
<td class="ReportHeaderTd2" style="padding-left:6px;"  width=10%><p class="ReportHeaderName"><?=$Lang['Online']?></td>

<td class="ReportHeaderTd2" colspan="2"  width=10%>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
	<p class="ReportHeaderName"><?=$Lang['RHeaderHit']?></p>
	</td></tr>
	<tr><td width="50%" class="ReportHeaderTableTd" ID="CntSaleHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderTotal']?></p>
	</td><td width="50%" class="ReportHeaderTableTd" ID="UniSaleHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderUni']?></p>
	</td></tr>
	</table>
</td>
<?}?>

<?if ($nsUser->Columns->ACTIONS) {?>
<td class="ReportHeaderTd2" colspan="2"  width=10%>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
	<p class="ReportHeaderName"><?=$Lang['RHeaderAction']?></p>
	</td></tr>
	<tr><td width="50%" class="ReportHeaderTableTd" ID="CntActionHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderTotal']?></p>
	</td><td width="50%" class="ReportHeaderTableTd" ID="UniActionHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderUni']?></p>
	</td></tr>
	</table>
</td>
<?}?>

<?if ($nsUser->Columns->CLICKS) {?>
<td class="ReportHeaderTd2" colspan="2"  width=10%>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
	<p class="ReportHeaderName"><?=$Lang['RHeaderClick']?></p>
	</td></tr>
	<tr><td width="50%" class="ReportHeaderTableTd" ID="CntActionHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderTotal']?></p>
	</td><td width="50%" class="ReportHeaderTableTd" ID="UniActionHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderUni']?></p>
	</td></tr>
	</table>
</td>
<?}?>

<?if ($nsUser->Columns->SALES) {?>
<td class="ReportHeaderTd2" colspan="2"  width=10%>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
	<p class="ReportHeaderName"><?=$Lang['RHeaderSale']?></p>
	</td></tr>
	<tr><td width="50%" class="ReportHeaderTableTd" ID="CntSaleHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderTotal']?></p>
	</td><td width="50%" class="ReportHeaderTableTd" ID="UniSaleHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderUni']?></p>
	</td></tr>
	</table>
</td>
<?}?>
<td></td>
</tr>

<tr>
<td width="100%" height="2" colspan="11" bgcolor="#E1E1E1">
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="2" border="0"></p>
</td>
</tr>

<?foreach ($NaturalReport->StatArr as $SiteId=>$Report) {?>
	<tr>
	<td class=ReportNameTd width=20%><p class=ReportColumn><a href="<?=getURL("visitor_path", "SiteId=$SiteId&CpId=$CpId", "report")?>"><?=$Report['Name']?></a></p></td>


	<?if ($nsUser->Columns->HITS) {?>
	<td class=ReportNameTd width=10%><p class=ReportColumn><a href="<?=getURL("visitor_path", "SiteId=$SiteId&CpId=$CpId&OnlineOnly=1", "report")?>"><?=StatBold($Report['Online'])?></a></p></td>

	<td class="ReportSimpleTd2" height="23" >
	<p class=ReportColumn><a href="<?=getURL("visitor_path", "CpId=".$NaturalReport->CpId."&SiteId=$SiteId&ViewDate=".$NaturalReport->ViewDate."", "report")?>" title="<?=$Lang['VisitorsPath']?>"><?=StatBold($Report['CntClick'])?></a>&nbsp;</p>
	</td>
	<td class="ReportSimpleTd" height="23">
	<p class=ReportColumn><?=StatBold($Report['UniClick'])?>&nbsp;</p>
	</td>
	<?}?>

	<?if ($nsUser->Columns->ACTIONS) {?>
	<td class="ReportSimpleTd2" height="23">
	<p class=ReportColumn><a href="<?=getURL("natural_constructor", "CpId=".$NaturalReport->CpId."&WhereArr[0][Mode]=Site&WhereArr[0][Id]=$SiteId&WhereArr[0][OrderBy]=CNT&WhereArr[0][OrderTo]=DESC&GroupBy=Action&ViewDate=".$NaturalReport->ViewDate."", "report")?>" title="<?=$Lang['GroupByActions']?>"><?=StatBold($Report['CntAction'])?></a>&nbsp;</p>
	</td>
	<td class="ReportSimpleTd" height="23">
	<p class=ReportColumn><?=StatBold($Report['UniAction'])?>&nbsp;</p>
	</td>
	<?}?>


	<? if ($nsUser->Columns->CLICKS) {?>
	<td class=ReportSimpleTd2><p class=ReportColumn><a href="<?=getURL("paid_constructor", "CpId=".$NaturalReport->CpId."&WhereArr[0][Mode]=Site&WhereArr[0][Id]=$SiteId&WhereArr[0][OrderBy]=CNT&WhereArr[0][OrderTo]=DESC&GroupBy=Camp&ViewDate=".$NaturalReport->ViewDate."", "report")?>" title="<?=$Lang['GroupByCamp']?>"><?=StatBold($Report['CntCamp'])?></a>&nbsp;</p></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=StatBold($Report['UniCamp'])?>&nbsp;</td>
	<?}?>



	<? if ($nsUser->Columns->SALES) {?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=StatBold($Report['CntSale'])?>&nbsp;</td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=StatBold($Report['UniSale'])?>&nbsp;</td>
	<?}?>

	<td></td>
	</tr>
	<tr>
	<td width="100%" height="1" colspan="11" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td>
	</tr>
<?}?>
<tr>
<td class=ReportSimpleTd2 colspan=11><B><?=str_replace("{DATE}", date("d.m.Y"), $Lang['StatPeriod']);?></B></td>
</tr>

</table></div>
<?}?>

<?

function StatBold($Value)
{
	if ($Value>0) return "<b>$Value</b>";
	else return $Value;
}

?>

<?include $nsTemplate->Inc("inc/footer");?>