
<SCRIPT LANGUAGE="JavaScript">
<!--

function PrintVersion()
{
	window.open(location.href+"&Print=1");
}

//-->
</SCRIPT>

<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td width=33% valign=top>

<?PostFORM();?>
<input type=hidden name=CpId value=<?=$CpId?>>
<input type=hidden name=CurrentGroupBy value="<?=$GroupBy?>">
<input type=hidden name=GroupBy value="<?=$GroupBy?>">

<? if(count($WhereArr)==0&&!$GroupBy) {?>
<input type=hidden name="General" value=1>
<?}?>

<?for($i=0;$i<count($WhereArr);$i++) {?>
<input type=hidden name="WhereArr[<?=$i?>][Mode]" value="<?=$WhereArr[$i]['Mode']?>">
<input type=hidden name="WhereArr[<?=$i?>][Id]" value="<?=$WhereArr[$i]['Id']?>">
<input type=hidden name="WhereArr[<?=$i?>][OrderBy]" value="<?=$WhereArr[$i]['OrderBy']?>">
<input type=hidden name="WhereArr[<?=$i?>][OrderTo]" value="<?=$WhereArr[$i]['OrderTo']?>">
<?}?>

<input type=hidden name="ViewDate" value="<?=$ViewDate?>">
<input type=hidden name="StartDate" value="<?=$StartDate?>">
<input type=hidden name="EndDate" value="<?=$EndDate?>">
<input type=hidden name="Filter" value="<?=$Filter?>">
<input type=hidden name="Limit" value="<?=$Limit?>">
<input type=hidden name="ShowAll" value="<?=$ShowAll?>">
<input type=hidden name="OrderBy" value="<?=$OrderBy?>">
<input type=hidden name="OrderTo" value="<?=$OrderTo?>">

<table width=100% cellpadding=4 cellspacing=0 border=0>
<tr><td><p><B>
<IMG SRC="<?=FileLink("images/icon_export.gif");?>" WIDTH="19" HEIGHT="16" BORDER="0" ALT="">&nbsp;<?=$Lang['Export']?>
</B></p></td></tr>

<tr><td><p>
<input type=checkbox ID="ExportNoLimit" name="ExportReport[NoLimit]" value=1><label for="ExportNoLimit">&nbsp;<?=$Lang['NoRowLimit']?></label>
</p></td></tr>

<tr><td><p>
<input type=checkbox ID="ExportExpanded" name="ExportReport[Expanded]" value=1><label for="ExportExpanded">&nbsp;<?=$Lang['IncludePerc']?></label>
</p></td></tr>

<tr><td><p>
<input type=text size=5 name="ExportReport[Separator]" value="<?=$Lang['SeparatorValue']?>">&nbsp;<?=$Lang['Separator']?>&nbsp;
<input type=submit value="<?=$Lang['DoExport']?>">
</p></td></tr>

</table>
</form>
</td><td width=34% valign=top>


<?if ($GroupBy!="General"&&!ValidId($ConstId)&&$CpId>0) {?>

<?GetFORM("save_report", "", "admin");?>
<?for($i=0;$i<count($WhereArr);$i++) {?>
<input type=hidden name="WhereArr[<?=$i?>][Mode]" value="<?=$WhereArr[$i]['Mode']?>">
<input type=hidden name="WhereArr[<?=$i?>][Id]" value="<?=$WhereArr[$i]['Id']?>">
<input type=hidden name="WhereArr[<?=$i?>][OrderBy]" value="<?=$WhereArr[$i]['OrderBy']?>">
<input type=hidden name="WhereArr[<?=$i?>][OrderTo]" value="<?=$WhereArr[$i]['OrderTo']?>">
<?}?>
<input type=hidden name="SaveReport[SaveMode]" value="<?=$SaveMode?>">
<input type=hidden name="SaveReport[CpId]" value="<?=$CpId?>">
<input type=hidden name="SaveReport[GroupBy]" value="<?=$GroupBy?>">
<input type=hidden name="SaveReport[ViewDate]" value="<?=$ViewDate?>">
<input type=hidden name="SaveReport[StartDate]" value="<?=$StartDate?>">
<input type=hidden name="SaveReport[EndDate]" value="<?=$EndDate?>">
<input type=hidden name="SaveReport[Filter]" value="<?=$Filter?>">
<input type=hidden name="SaveReport[Limit]" value="<?=$Limit?>">
<input type=hidden name="SaveReport[ShowAll]" value="<?=$ShowAll?>">
<input type=hidden name="SaveReport[OrderBy]" value="<?=$OrderBy?>">
<input type=hidden name="SaveReport[OrderTo]" value="<?=$OrderTo?>">

<table width=100% cellpadding=4 cellspacing=0 border=0>
<tr><td>
<p><B>
<IMG SRC="<?=FileLink("images/icon_save.gif");?>" WIDTH="16" HEIGHT="16" BORDER="0" ALT="">&nbsp;<?=$Lang['SaveReport']?>
</B></p>
</td></tr>
<tr><td>
<p><input type=checkbox value=1 ID="CurrentDate" name="SaveReport[CurrentDate]"><label for="CurrentDate">&nbsp;<?=$Lang['UseCurrentDate']?></label>&nbsp;</p>
</td></tr>
<tr><td>
<p><input type=checkbox value=1 ID="AddToMy" name="SaveReport[AddToMy]" <?=((ValidVar($SaveReport['AddToMy'])==1)?"checked":"")?>><label for="AddToMy">&nbsp;<?=$Lang['AddToMy']?></label>&nbsp;</p>
</td></tr>
<tr><td>
<p><?=$Lang['ReportName']?>: <input type=text size=30 name="SaveReport[Name]" value="">
&nbsp;<input type=submit value="<?=$Lang['Save']?>"></p>
</td></tr>
</table>
</form>
<?}?>

</td><td width=33% valign=top>


<table width=100% cellpadding=4 cellspacing=0 border=0>
<tr><td>
<p><B>
<IMG SRC="<?=FileLink("images/icon_print.gif");?>" WIDTH="19" HEIGHT="16" BORDER="0" ALT="">&nbsp;<?=$Lang['PrintReport']?>
</B></p>
</td></tr>
<tr><td>
<p><input type=button value="<?=$Lang['Print']?>" onclick="PrintVersion();" <?=(ValidVar($Print))?"disabled":""?>></p>
</td></tr>
</table>


</td></tr>
</table>