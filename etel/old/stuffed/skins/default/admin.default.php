<?include $nsTemplate->Inc("inc/header");?>




<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">



<?if (count($GrpList)>0) {?>


<table border="0" cellpadding="0" cellspacing="0" width=100%>
	<tr>
	<td width="100%" height="1" colspan="6" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td>

	
	</tr>
<tr bgcolor="#f5f5f5">
<td width=20% class=ReportSimpleTd ><B><?=$Lang['StatByGrp']?></B></td>
<?if($nsUser->Columns->CLICKS) {?>
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
<?if($nsUser->Columns->ROI) {?>
	<td class="ReportHeaderTd2"   width=5% style="padding-left:6px;">
	<p class="ReportHeaderName"><?=$Lang['RHeaderROI']?></p>
	</td>
<?}?>
<?if($nsUser->Columns->CONVERSIONS) {?>
<td class="ReportHeaderTd2" colspan="2"  width=10%>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
	<p class="ReportHeaderName"><?=$Lang['RHeaderConversion']?></p>
	</td></tr>
	<tr><td width="50%" class="ReportHeaderTableTd" ID="ActionConvHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderAction']?></p>
	</td><td width="50%" class="ReportHeaderTableTd" ID="SaleConvHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderSale']?></p>
	</td></tr>
	</table>
</td>
<?}?>
</tr>

<tr>
<td width="100%" height="2" colspan="6" bgcolor="#E1E1E1">
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="2" border="0"></p>
</td>
</tr>

<?foreach ($GrpList as $GrpId=>$Report) {?>
	<tr>

	<td class=ReportNameTd width=20%><p class=ReportColumn><B><a href="<?=getURL("paid_constructor", "GrpId=$GrpId", "report")?>" title="<?=$Lang['Stat']?>"><?=$Report->Name?></a></B>
	<?if (ValidVar($Report->ClientName)) {?><br><span class=ListDescr><?=$Report->ClientName?></span><?}?>
	</td>

	<?if($nsUser->Columns->CLICKS) {?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->CampStat['CntClick']?></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->CampStat['UniClick']?></td>
	<?}?>

	<?if($nsUser->Columns->ROI) {?>
	<td class=ReportSimpleTd><p class=ReportColumn>
	<span class="<?=($Report->GoodROI($Report->CampStat['ROI']))?"GoodROI":"BadROI"?>">
	<?=$Report->CampStat['ROI']?>%</span></td>
	<?}?>

	<?if($nsUser->Columns->CONVERSIONS) {?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->CampStat['ActionConv']?>%</td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->CampStat['SaleConv']?>%</td>
	<?}?>

	</tr>
	<tr>
	<td width="100%" height="1" colspan="6" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td>

	
	</tr>
<?}?>
</table>
<?}?>


<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">






<?if (count($CampList)>0) {?>


<table border="0" cellpadding="0" cellspacing="0" width=100%>
	<tr>
	<td width="100%" height="1" colspan="6" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td>

	
	</tr>
<tr bgcolor="#f5f5f5">
<td width=20% class=ReportSimpleTd ><B><?=$Lang['StatByCamp']?></B></td>
<?if($nsUser->Columns->CLICKS) {?>
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
<?if($nsUser->Columns->ROI) {?>
	<td class="ReportHeaderTd2"   width=5% style="padding-left:6px;">
	<p class="ReportHeaderName"><?=$Lang['RHeaderROI']?></p>
	</td>
<?}?>
<?if($nsUser->Columns->CONVERSIONS) {?>
<td class="ReportHeaderTd2" colspan="2"  width=10%>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
	<p class="ReportHeaderName"><?=$Lang['RHeaderConversion']?></p>
	</td></tr>
	<tr><td width="50%" class="ReportHeaderTableTd" ID="ActionConvHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderAction']?></p>
	</td><td width="50%" class="ReportHeaderTableTd" ID="SaleConvHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderSale']?></p>
	</td></tr>
	</table>
</td>
<?}?>
</tr>
<tr>
<td width="100%" height="2" colspan="6" bgcolor="#E1E1E1">
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="2" border="0"></p>
</td>
</tr>

<?foreach ($CampList as $CampId=>$Report) {?>
	<tr>

	<td class=ReportNameTd width=20%><p class=ReportColumn><B><a href="<?=getURL("paid_constructor", "CampId=$CampId", "report")?>" title="<?=$Lang['Stat']?>"><?=$Report->Name?></a></B>
	<?if (ValidVar($Report->ClientName)) {?><br><span class=ListDescr><?=$Report->ClientName?></span><?}?>
	</td>

	<?if($nsUser->Columns->CLICKS) {?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->CampStat['CntClick']?></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->CampStat['UniClick']?></td>
	<?}?>

	<?if($nsUser->Columns->ROI) {?>
	<td class=ReportSimpleTd><p class=ReportColumn>
	<span class="<?=($Report->GoodROI($Report->CampStat['ROI']))?"GoodROI":"BadROI"?>">
	<?=$Report->CampStat['ROI']?>%</span></td>
	<?}?>

	<?if($nsUser->Columns->CONVERSIONS) {?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->CampStat['ActionConv']?>%</td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->CampStat['SaleConv']?>%</td>
	<?}?>

	</tr>
	<tr>
	<td width="100%" height="1" colspan="6" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td>

	
	</tr>


<?}?>
</table>
<?}?>





<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">





<?if (count($SplitList)>0) {?>
<table border="0" cellpadding="0" cellspacing="0" width=100%>
	<tr>
	<td width="100%" height="1" colspan="7" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td></tr>
<tr bgcolor="#f5f5f5">
<td width=20% class=ReportSimpleTd ><B><?=$Lang['StatBySplit']?></B></td>
<?if($nsUser->Columns->CLICKS) {?>
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
<?if($nsUser->Columns->SALES) {?>
	<td class="ReportHeaderTd2"   width=5% style="padding-left:6px;">
	<p class="ReportHeaderName"><?=$Lang['RHeaderSale']?></p>
	</td>
<?}?>
<?if($nsUser->Columns->ACTIONS) {?>
	<td class="ReportHeaderTd2"   width=5% style="padding-left:6px;">
	<p class="ReportHeaderName"><?=$Lang['RHeaderAction']?></p>
	</td>
<?}?>
<?if($nsUser->Columns->CONVERSIONS) {?>
<td class="ReportHeaderTd2" colspan="2"  width=10%>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
	<p class="ReportHeaderName"><?=$Lang['RHeaderConversion']?></p>
	</td></tr>
	<tr><td width="50%" class="ReportHeaderTableTd" ID="ActionConvHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderAction']?></p>
	</td><td width="50%" class="ReportHeaderTableTd" ID="SaleConvHead">
	<p class="ReportSubHeaderName"><?=$Lang['RHeaderSale']?></p>
	</td></tr>
	</table>
</td>
<?}?>
</tr>

<tr>
<td width="100%" height="2" colspan="7" bgcolor="#E1E1E1">
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="2" border="0"></p>
</td>
</tr>



<?foreach ($SplitList as $SplitId=>$Report) {?>
	<tr>
	<td class=ReportNameTd width=20%><p class=ReportColumn><B><a href="<?=getURL("split_test", "SplitId=$SplitId", "report")?>" title="<?=$Lang['Stat']?>"><?=$Report->Name?></a></B>
	<?if (ValidVar($Report->ClientName)) {?><br><span class=ListDescr><?=$Report->ClientName?></span><?}?>	
	</td>
	<?if($nsUser->Columns->CLICKS){?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->SplitStat['CntClick']?></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->SplitStat['UniClick']?></td>
	<?}?>
	<?if($nsUser->Columns->SALES){?>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->SplitStat['CntSale']?></td>
	<?}?>
	<?if($nsUser->Columns->ACTIONS){?>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->SplitStat['CntAction']?></td>
	<?}?>
	<?if($nsUser->Columns->CONVERSIONS){?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->SplitStat['ActionConv']?>%</td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->SplitStat['SaleConv']?>%</td>
	<?}?>

	</tr>

	<tr>
	<td width="100%" height="1" colspan="7" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td></tr>

<?}?>
</table>
<?}?>






<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">



<?if (count($CompList)>0) {?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td width="100%" height="1" colspan="7" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td></tr>
<tr bgcolor="#f5f5f5">
<td width=20% class=ReportSimpleTd ><B><?=str_replace("{DATE}", date("d.m.Y"), $Lang['StatByClient'])?></B></td>
<?if($nsUser->Columns->HITS) {?>
	<td class="ReportHeaderTd2" colspan="2"  width=10%>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
		<p class="ReportHeaderName"><?=$Lang['RHeaderHit']?></p>
		</td></tr>
		<tr><td width="50%" class="ReportHeaderTableTd" ID="CntActionHead">
		<p class="ReportSubHeaderName"><?=$Lang['RHeaderTotal']?></p>
		</td><td width="50%" class="ReportHeaderTableTd" ID="UniActionHead">
		<p class="ReportSubHeaderName"><?=$Lang['RHeaderUni']?></p>
		</td></tr>
		</table>
	</td>
<?}?>

<?if($nsUser->Columns->ACTIONS) {?>
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

<?if($nsUser->Columns->CLICKS) {?>
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

</tr>

<tr>
<td width="100%" height="2" colspan="7" bgcolor="#E1E1E1">
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="2" border="0"></p>
</td>
</tr>



<?foreach ($CompList as $CompId=>$Report) {?>
	<tr>
	<td class=ReportNameTd width=20%><p class=ReportColumn><B><?=$Report->Name?></B></td>
	<?if($nsUser->Columns->HITS){?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->StatArr['CntClick']?></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->StatArr['UniClick']?></td>
	<?}?>
	<?if($nsUser->Columns->ACTIONS){?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->StatArr['CntAction']?></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->StatArr['UniAction']?></td>
	<?}?>
	<?if($nsUser->Columns->CLICKS){?>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->StatArr['CntCamp']?></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->StatArr['UniCamp']?></td>
	<?}?>
	</tr>
	<tr>
	<td width="100%" height="1" colspan="7" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td></tr>
<?}?>
</table>
<?}?>




<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">





<?if (count($SiteList)>0) {?>
<table border="0" cellpadding="0" cellspacing="0" width=100%>
	<tr>
	<td width="100%" height="1" colspan="7" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td></tr>

<tr bgcolor="#f5f5f5">
<td width=20% class=ReportSimpleTd ><B><?=str_replace("{DATE}", date("d.m.Y"), $Lang['StatBySite'])?></B></td>
<?if($nsUser->Columns->HITS) {?>
	<td class="ReportHeaderTd2" colspan="2"  width=10%>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr><td colspan="2" width="100%" class="ReportHeaderTableTd">
		<p class="ReportHeaderName"><?=$Lang['RHeaderHit']?></p>
		</td></tr>
		<tr><td width="50%" class="ReportHeaderTableTd" ID="CntActionHead">
		<p class="ReportSubHeaderName"><?=$Lang['RHeaderTotal']?></p>
		</td><td width="50%" class="ReportHeaderTableTd" ID="UniActionHead">
		<p class="ReportSubHeaderName"><?=$Lang['RHeaderUni']?></p>
		</td></tr>
		</table>
	</td>
<?}?>

<?if($nsUser->Columns->ACTIONS) {?>
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

<?if($nsUser->Columns->CLICKS) {?>
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

</tr>

<tr>
<td width="100%" height="2" colspan="7" bgcolor="#E1E1E1">
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="2" border="0"></p>
</td>
</tr>


<?foreach ($SiteList as $SiteId=>$Report) {?>
	<tr>
	<td class=ReportNameTd width=20%><p class=ReportColumn><B><?=$Report->Name?></B>
	<?if (ValidVar($Report->ClientName)) {?><br><span class=ListDescr><?=$Report->ClientName?></span><?}?>
	</td>

	<?if($nsUser->Columns->HITS){?>
	<td class=ReportSimpleTd2><p class=ReportColumn><a href="<?=getURL("natural_constructor", "CpId=".$Report->CpId."&WhereArr[0][Mode]=Site&WhereArr[0][Id]=$SiteId&WhereArr[0][OrderBy]=CNT&WhereArr[0][OrderTo]=DESC&GroupBy=Date&", "report")?>"><?=$Report->StatArr['CntClick']?></a></td>
	<td class=ReportSimpleTd2><p class=ReportColumn><?=$Report->StatArr['UniClick']?></td>
	<?}?>

	<?if($nsUser->Columns->ACTIONS){?>
	<td class=ReportSimpleTd2><p class=ReportColumn><a href="<?=getURL("natural_constructor", "CpId=".$Report->CpId."&WhereArr[0][Mode]=Site&WhereArr[0][Id]=$SiteId&WhereArr[0][OrderBy]=CNT&WhereArr[0][OrderTo]=DESC&GroupBy=Action&ViewDate=".$Report->ViewDate."", "report")?>"><?=$Report->StatArr['CntAction']?></a></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->StatArr['UniAction']?></td>
	<?}?>

	<?if($nsUser->Columns->CLICKS){?>
	<td class=ReportSimpleTd2><p class=ReportColumn><a href="<?=getURL("paid_constructor", "CpId=".$Report->CpId."&WhereArr[0][Mode]=Site&WhereArr[0][Id]=$SiteId&WhereArr[0][OrderBy]=CNT&WhereArr[0][OrderTo]=DESC&GroupBy=Camp&ViewDate=".$Report->ViewDate."", "report")?>"><?=$Report->StatArr['CntCamp']?></a></td>
	<td class=ReportSimpleTd><p class=ReportColumn><?=$Report->StatArr['UniCamp']?></td>
	<?}?>

	</tr>

	<tr>
	<td width="100%" height="1" colspan="7" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td></tr>
<?}?>
</table>
<?}?>




<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">





<?if (count($Clients)>0) {?>

<table border="0" cellpadding="0" cellspacing="0" width=100%>
<?for($i=0;$i<count($Clients);$i++) {
	$Row=$Clients[$i];?>

	<tr><td class=ListRowRight>

	<table border="0" cellpadding="0" cellspacing="0" width=100%>
	<tr><td width=100%><p class=SectionName style="margin-bottom:0px;"><a href="<?=getURL("client_page", "CpId=".$Row->ID)?>"><B><?=$Row->NAME?></B></a></p>
	</td>
	
	<td><IMG SRC="<?=FileLink("images/small_icon_stat-a.gif");?>" WIDTH="8" HEIGHT="8" BORDER="0" ALT="" hspace=6></td>
	<td nowrap style="font-size:10px;">
	<a href="<?=getURL("paid_constructor", "CpId=".$Row->ID, "report")?>"><?=$Lang['MPaidAdv']?></a> 


	</td>
	
	<td><IMG SRC="<?=FileLink("images/small_icon_stat-a.gif");?>" WIDTH="8" HEIGHT="8" BORDER="0" ALT="" hspace=6></td><td nowrap style="font-size:10px;">
	<a href="<?=getURL("natural_constructor", "CpId=".$Row->ID, "report")?>"><?=$Lang['MNatural']?></a>


	</td>
	
	<td><IMG SRC="<?=FileLink("images/small_icon_logs-a.gif");?>" WIDTH="11" HEIGHT="9" BORDER="0" ALT="" hspace=6></td><td nowrap style="font-size:10px;">
	<a href="<?=getURL("reports", "CpId=".$Row->ID, "admin")?>"><?=$Lang['MLogs']?></a>


	</td>
	
	<td><IMG SRC="<?=FileLink("images/small_icon_campaign-a.gif");?>" WIDTH="8" HEIGHT="7" BORDER="0" ALT="" hspace=6></td><td nowrap style="font-size:10px;">
	<a href="<?=getURL("campaign", "CpId=".$Row->ID, "admin")?>"><?=$Lang['MCampaign']?></a>
	</td>

	<td><IMG SRC="<?=FileLink("images/small_icon_actions-a.gif");?>" WIDTH="4" HEIGHT="8" BORDER="0" ALT="" hspace=6></td><td nowrap style="font-size:10px;">
	<a href="<?=getURL("actions", "CpId=".$Row->ID, "admin")?>"><?=$Lang['MActions']?></a>
	</td>

	
	</tr>
	</table>

	</td></tr>

<?}?>
</table>

<?}?>




<?include $nsTemplate->Inc("inc/footer");?>