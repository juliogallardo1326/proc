<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<?if ($Mode=="edit") include $nsTemplate->Inc("admin.visitor_grp_edit");?>


<?if (ValidId($VisGrp->ID)&&count($IpArr)>0&&$Mode=="view") {?>



<?if ($Mode!="edit"&&ValidVar($EditArr['Descr'])) {?>
<table  class=FormTable>
<tr><td class=FormLeftTd>
<?=$Lang['GrpInfo']?>
</td><td class=FormRightTd>
<?=stripslashes($EditArr['Descr'])?>
</td></tr>
</table><?}?>

<table  class=FormTable>

<tr>
<td   align=center><?=$Lang['TotalPageLoad']?></td>
<td   align=center><?=$Lang['TotalActions']?></td>
<td   align=center><?=$Lang['TotalSales']?></td>
<td   align=center><?=$Lang['TotalClicks']?></td>
<td   align=center><?=$Lang['TotalRefCome']?></td>
<td   align=center><?=$Lang['TotalKeywords']?></td>
</tr>

<tr>
<td class=ReportSimpleTd align=center><B>
<?=$TotalCnt?>
</B></td>
<td class=ReportSimpleTd align=center><B>
<?=$ActionCnt?>
</B></td>
<td class=ReportSimpleTd align=center><B>
<?=$SaleCnt?>
</B></td>
<td class=ReportSimpleTd align=center><B>
<?=$ClickCnt?>
</B></td>
<td class=ReportSimpleTd align=center><B>
<?=$RefCnt?>
</B></td>
<td class=ReportSimpleTd2 align=center><B>
<?=$KeyCnt?>
</B></td>

</tr>

</table>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">


<?if (count($RefArr)>0) {?>
<div class=ListDiv>
<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['RefList']?>
</td></tr>
</table>
<table class=ListTable2>
	<?for($i=0;$i<count($RefArr);$i++) {?>
	<tr><td class=ListRowRight2>
	<span style="font-size:9px;"><b><?=date("Y-m-d H:i", $RefArr[$i]->STAMP)?></b>&nbsp;</span>
	<span style="font-size:10px;color:999999"><?=$RefArr[$i]->HOST?></span>&nbsp;
	<a href="<?=htmlspecialchars($RefArr[$i]->REFERER)?>" target=_blank>
	<?=urldecode(urldecode($RefArr[$i]->REFERER))?>
	</a>
	</td></tr>
	<?}?>
</table>
</div>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">
<?}?>


<?if (count($KeyArr)>0) {?>
<div class=ListDiv>
<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['KeyList']?>
</td></tr>
</table>
<table class=ListTable2>
	<?for($i=0;$i<count($KeyArr);$i++) {?>
	<tr><td class=ListRowRight2>
	<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Key&WhereArr[0][Id]=".$KeyArr[$i]->NATURAL_KEY."&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Vis", "report")?>" title="<?=$Lang['ShowKeyStat']?>">
	<?=urldecode(urldecode($KeyArr[$i]->KEYWORD))?>
	</a>
	</td></tr>
	<?}?>
</table>
</div>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">
<?}?>



<?if (count($AgentArr)>0) {?>
<div class=ListDiv>
<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['AgentsList']?>
</td></tr>
</table>
<table class=ListTable2>
	<?for($i=0;$i<count($AgentArr);$i++) {?>
	<tr><td class=ListRowRight2>
	<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Agent&WhereArr[0][Id]=".$AgentArr[$i]->ID."&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Vis", "report")?>" title="<?=$Lang['AgentStat']?>">
	<?=$AgentArr[$i]->USER_AGENT?>
	</a>
	<?if($AgentArr[$i]->GRP_ID>0) {?>
		<B><a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=AgentGrp&WhereArr[0][Id]=".$AgentArr[$i]->ID."&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Vis", "report")?>"  title="<?=$Lang['AgentGrpStat']?>"><?=$AgentArr[$i]->GRP_NAME?></a></B>
	<?}?>
	</td></tr>
	<?}?>
</table>
</div>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">
<?}?>

<?}?>

<?include $nsTemplate->Inc("inc/footer");?>