<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<SCRIPT LANGUAGE="JavaScript">
<!--

function CheckVisitor()
{
	oHid=GetObj("AlreadySaved");
	if (oHid.value==1) return true;
	oNewIp=GetObj("NewIp");
	oNewIp2=GetObj("VisNewIp");
	oNewIp2.value=oNewIp.value;
	oForm=GetObj("EDIT_FORM");
	oForm.submit();
	return false;
}

//-->
</SCRIPT>



<?if ($Mode=="edit") {?>
<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=50% valign=top>
<?}?>




<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$TableCaption?>
</td></tr>
</table>

<table  class=FormTable>
<?PostFORM(false, false, false, "ID=\"EDIT_FORM\"");?>
<input type=hidden name="VisId" value="<?=$VisId?>">
<input type=hidden name="AlreadySaved" value="<?=((ValidId($Visitor->CLIENT_VIS_ID))?"1":"0")?>" Id="AlreadySaved">
<input type=hidden id="VisNewIp" name="NewIp" value="">

<tr><td class=FormLeftTd>
<?=$Lang['LastAgent']?>
</td><td class=FormRightTd>
<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Agent&WhereArr[0][Id]=".$Visitor->USER_AGENT_ID."&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Vis&WhereArr[1][OrderTo]=DESC&WhereArr[1][OrderBy]=CNT&ShowAll=1", "report")?>" title="<?=$Lang['OtherWithAgent']?>">
<?=$Visitor->USER_AGENT?>
</a>
<?if($Visitor->GRP_ID>0){?>
<br><B>
<a href="<?=getURL("natural_constructor", "CpId=$CpId&GroupBy=AgentGrp&ShowAll=1", "report")?>" title="<?=$Lang['AgentStat']?>">
<?=$Visitor->GRP_NAME?>
</a>
</B>
<?}?>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['LastIp']?>
</td><td class=FormRightTd>
<a href="<?=getURL("visitor_path", "CpId=$CpId&IP=".$Visitor->LAST_IP, "report")?>" title="<?=$Lang['OtherWithIP']?>">
<?=$Visitor->LAST_IP?>
</a>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['ScrRes']?>
</td><td class=FormRightTd>
<?=(($Visitor->LAST_RESOLUTION!="-1")?$Visitor->LAST_RESOLUTION:$Lang['Undefined'])?>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['Color']?>
</td><td class=FormRightTd>
<?=(($Visitor->PIXEL_DEPTH>0)?$Visitor->PIXEL_DEPTH."bit":$Lang['Undefined'])?>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['Flash']?>
</td><td class=FormRightTd>
<?=(($Visitor->FLASH_VERSION!="-1")?$Visitor->FLASH_VERSION:$Lang['Undefined'])?>
</td></tr>

<?if ($Mode=="edit") {?>

<tr><td class=FormLeftTd>
<?=$Lang['VisitorName']?>
</td><td class=FormRightTd>
<input type=text  name="EditArr[Name]" value="<?=$EditArr['Name']?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['Title']?>
</td><td class=FormRightTd>
<textarea rows=5 name="EditArr[Descr]"><?=$EditArr['Descr']?></textarea>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['AddToMy']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[Watch]" value=1 <?=(($EditArr['Watch']>0)?"checked":"")?>>
</td></tr>


</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>

<?}
elseif (ValidVar($EditArr['Descr'])) {?>
<tr><td class=FormLeftTd>
<?=$Lang['Title']?>
</td><td class=FormRightTd>
<?=stripslashes($EditArr['Descr'])?>
</td></tr>
<?}?>

</form>
</table>

<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">

<?if ($Mode=="edit") {?>
</td><td width=50% valign=top>

<div class=ListDiv>

<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['IpList']?>
</td></tr>
</table>


<table class=ListTable2>
<?for ($i=0;$i<count($IpArr);$i++) {
	$Row=$IpArr[$i];?>
	<tr>
	<td class=<?=$Row->_STYLE?>>
	
	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>
	<B style="color:#000000"><?=$Row->IP?></B>
	</td>
	<td>
	<?
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("visitor", "VisId=$VisId&CpId=$CpId&DeleteIp=".$IpArr[$i]->ID));
	$nsButtons->Dump();
	?>
	</td>	
	</tr></table>
	
	</td></tr>
<?}?>
</table>


<?PostFORM();?>
<input type=hidden name="VisId" value="<?=$VisId?>">
<input type=hidden name="CpId" value="<?=$CpId?>">
<table  class=FormTable>
<tr><td class=FormLeftTd>
<?=$Lang['AddNewIp']?>
</td><td class=FormRightTd>
<input type=text size=15 id="NewIp" name="NewIp">
</td></tr>

</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Add']?>" onclick="return CheckVisitor();">
</td></tr>
</table>

</form>

</div><br>

</td></tr></table>
<?}?>



<?if ($Mode=="view") {?>
<?//if ($Visitor->COMPANY_ID) {?>

<div style="width:100%" class=FormDiv2>
<table  class=FormTable>

<tr>
<td  class=ReportTabTd align=center><?=$Lang['TotalPageLoad']?></td>
<td  class=ReportTabTd align=center><?=$Lang['TotalActions']?></td>
<td  class=ReportTabTd align=center><?=$Lang['TotalSales']?></td>
<td  class=ReportTabTd align=center><?=$Lang['TotalClicks']?></td>
<td  class=ReportTabTd align=center><?=$Lang['TotalRefCome']?></td>
<td  class=ReportTabTd align=center><?=$Lang['TotalKeywords']?></td>

</tr>

<tr>

<td class=ReportSimpleTd align=center><B>
<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Vis&WhereArr[0][Id]=$VisId&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Page&WhereArr[1][OrderTo]=DESC&WhereArr[1][OrderBy]=CNT&ShowAll=1", "report")?>" title="<?=$Lang['ShowPagesList']?>">
<?=$TotalCnt?>
</a>
</B></td>

<td class=ReportSimpleTd align=center><B>
<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Vis&WhereArr[0][Id]=$VisId&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Action&WhereArr[1][OrderTo]=DESC&WhereArr[1][OrderBy]=CNT&ShowAll=1", "report")?>" title="<?=$Lang['ShowActionsList']?>">
<?=$ActionCnt?>
</a>
</B></td>

<td class=ReportSimpleTd align=center><B>
<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Vis&WhereArr[0][Id]=$VisId&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Sale&WhereArr[1][OrderTo]=DESC&WhereArr[1][OrderBy]=CNT&ShowAll=1", "report")?>" title="<?=$Lang['ShowSalesList']?>">
<?=$SaleCnt?>
</a>
</B></td>

<td class=ReportSimpleTd align=center><B>
<a href="<?=getURL("paid_constructor", "CpId=$CpId&WhereArr[0][Mode]=Vis&WhereArr[0][Id]=$VisId&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Camp&WhereArr[1][OrderTo]=DESC&WhereArr[1][OrderBy]=CNT&ShowAll=1", "report")?>" title="<?=$Lang['ShowCampList']?>">
<?=$ClickCnt?>
</B></td>

<td class=ReportSimpleTd align=center><B>
<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Vis&WhereArr[0][Id]=$VisId&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Ref&WhereArr[1][OrderTo]=DESC&WhereArr[1][OrderBy]=CNT", "report")?>" title="<?=$Lang['ShowRefList']?>">
<?=$RefCnt?>
</a>
</B></td>

<td class=ReportSimpleTd2 align=center><B>
<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Vis&WhereArr[0][Id]=$VisId&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Key&WhereArr[1][OrderTo]=DESC&WhereArr[1][OrderBy]=CNT", "report")?>" title="<?=$Lang['ShowLeyList']?>">
<?=$KeyCnt?>
</a>
</B></td>

</tr>

</table>
</div>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">

<?if (count($RefArr)>0) {?>
<div class=ListDiv>
<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['RefList']?>
</td></tr>
</table>
<table class=ListTable2 width=100%>
	<?for($i=0;$i<count($RefArr);$i++) {?>
	<tr><td class=ListRowRight2>
	<span style="font-size:9px;"><b><?=date("Y-m-d H:i", $RefArr[$i]->STAMP)?></b>&nbsp;</span>
	<a href="<?=htmlspecialchars($RefArr[$i]->REFERER)?>" target=_blank>
	<?=urldecode(urldecode($RefArr[$i]->REFERER))?>
	</a>
	</td></tr>
	<?}?>
</table>
</div>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">
<?}?>


<?if (count($KeyArr)>0) {?>
<div class=ListDiv>
<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['KeyList']?>
</td></tr>
</table>
<table class=ListTable2 width=100%>
	<?for($i=0;$i<count($KeyArr);$i++) {?>
	<tr><td class=ListRowRight2>
	<a href="<?=getURL("natural_constructor", "CpId=$CpId&WhereArr[0][Mode]=Key&WhereArr[0][Id]=".$KeyArr[$i]->NATURAL_KEY."&WhereArr[0][OrderTo]=DESC&WhereArr[0][OrderBy]=CNT&GroupBy=Vis", "report")?>" title="<?=$Lang['ShowKeyStat']?>">
	<?=urldecode(urldecode($KeyArr[$i]->KEYWORD))?>
	</a>
	</td></tr>
	<?}?>
</table>
</div>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">

<?}?>


<?}?>
<?//}?>




<?include $nsTemplate->Inc("inc/footer");?>