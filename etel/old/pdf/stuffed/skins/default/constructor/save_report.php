<?PostFORM("save_report", "", "admin");?>
<?for($i=0;$i<count($WhereArr);$i++) {?>
<input type=hidden name="WhereArr[<?=$i?>][Mode]" value="<?=$WhereArr[$i]['Mode']?>">
<input type=hidden name="WhereArr[<?=$i?>][Id]" value="<?=$WhereArr[$i]['Id']?>">
<input type=hidden name="WhereArr[<?=$i?>][OrderBy]" value="<?=$WhereArr[$i]['OrderBy']?>">
<input type=hidden name="WhereArr[<?=$i?>][OrderTo]" value="<?=$WhereArr[$i]['OrderTo']?>">
<?}?>
<input type=hidden name="SaveReport[SaveMode]" value="<?=$SaveMode?>">
<input type=hidden name="SaveReport[CpId]" value="<?=$CpId?>">
<input type=hidden name="SaveReport[GroupBy]" value="<?=$GroupBy?>">
<input type=hidden name="SaveReport[DatesUsed]" value="<?=(($DatesUsed)?"1":"0")?>">
<input type=hidden name="SaveReport[ViewDate]" value="<?=$ViewDate?>">
<input type=hidden name="SaveReport[StartDate]" value="<?=$StartDate?>">
<input type=hidden name="SaveReport[EndDate]" value="<?=$EndDate?>">
<input type=hidden name="SaveReport[Filter]" value="<?=$Filter?>">
<input type=hidden name="SaveReport[Limit]" value="<?=$Limit?>">
<input type=hidden name="SaveReport[ShowAll]" value="<?=$ShowAll?>">
<input type=hidden name="SaveReport[OrderBy]" value="<?=$OrderBy?>">
<input type=hidden name="SaveReport[OrderTo]" value="<?=$OrderTo?>">

<table class=CaptionTable>
<tr><td  >
<?=$Lang['Title']?>
</td></tr>
</table>

<table  class=FormTable>

<?if (!$DatesUsed) {?>
<tr><td  class=ReportSimpleTd2>
<input type=checkbox value=1 ID="CurrentDate" name="SaveReport[CurrentDate]" <?=((ValidVar($SaveReport['CurrentDate'])==1)?"checked":"")?>><label for="CurrentDate">&nbsp;<?=$Lang['UseCurrentDate']?></label>&nbsp;&nbsp;
</td></tr>
<?}?>

<tr><td  class=ReportSimpleTd2>
<input type=checkbox value=1 ID="AddToMy" name="SaveReport[AddToMy]" <?=((ValidVar($SaveReport['AddToMy'])==1)?"checked":"")?>><label for="AddToMy">&nbsp;<?=$Lang['AddToMy']?></label>&nbsp;&nbsp;
</td></tr>

<tr><td  class=ReportSimpleTd2>
<input type=text size=30 name="SaveReport[Name]" value="<?=htmlspecialchars(stripslashes(ValidVar($SaveReport['Name'])))?>">&nbsp;<?=$Lang['ReportName']?>
</td></tr>

<tr><td  class=ReportSimpleTd2>
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>

</form>
</table>
