<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=50% valign=top>


<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$TableCaption?>
</td></tr>
</table>


<?PostFORM();?>
<input type=hidden name="EditId" value="<?=$EditId?>">
<table  class=FormTable>


<tr><td class=FormLeftTd>
<?=$Lang['GrpName']?>
</td><td class=FormRightTd>
<input type=text  name="EditArr[Name]" value="<?=$EditArr['Name']?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['GrpInfo']?>
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

</table>
</form>


</td><td width=50% valign=top>

<?if (ValidId($VisGrp->ID)) {?>


<div class=ListDiv>

<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['IpList']?>
</td></tr>
</table>


<table class=ListTable2>
<?for ($i=0;$i<count($IpArr);$i++) {
	$Row=$IpArr[$i];?>
	<tr><td class=<?=$Row->_STYLE?>>

	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>
	<?=$Row->IP?>
	</td><td>
	<?
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("visitor_grp", "EditId=$EditId&DeleteIp=".$IpArr[$i]->ID));
	$nsButtons->Dump();
	?>
	</td></tr></table>

	</td></tr>
<?}?>
</table>


<?PostFORM();?>
<input type=hidden name="EditId" value="<?=$EditId?>">
<table  class=FormTable>
<tr><td class=FormLeftTd>
<?=$Lang['AddNewIp']?>
</td><td class=FormRightTd>
<input type=text size=15 name="NewIp">
</td></tr>

</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Add']?>">
</td></tr>
</table>

</form>

</div>

<?}?>

</td></tr></table><br>