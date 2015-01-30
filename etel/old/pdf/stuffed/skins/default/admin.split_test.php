<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=50% valign=top>


<?PostFORM();?>
<input type="hidden" name="EditId" value="<?=$EditId?>">
<input type="hidden" name="GrpId" value="<?=$GrpId?>">


<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="1" BORDER="0" ALT="">
<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$TableCaption?>
</td></tr>
</table>



<table  class=FormTable>

<tr><td class=FormLeftTd>
<?=$Lang['Name']?>
<?FormError("Name")?>
</td><td class=FormRightTd>
<input type=text  name="EditArr[Name]" value="<?=$EditArr['Name']?>">
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['Descr']?>
<?FormError("Descr")?>
</td><td class=FormRightTd>
<textarea rows=6 name="EditArr[Descr]"><?=$EditArr['Descr']?></textarea>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['RememberPage']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[Rem]" value=1 <?=(($EditArr['Rem']==1)?"checked":"")?>>
</td></tr>

<?if(ValidId($EditId)&&$nsUser->ADMIN){?>
<tr><td class=FormLeftTd>
<?=$Lang['ShowOn1stPage']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[Watch]" value=1 <?=(($EditArr['Watch']==1)?"checked":"")?>>
</td></tr>
<?}?>

</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>
</table>

</form>


<?if (ValidId($EditId)&&ValidId($SplitTest->CAMPAIGN_ID)&&count($MoveArr)>1) {
	include $nsTemplate->Inc("inc/move_camp_piece");
}?>


</td>
<td width=10><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="10" HEIGHT="1" BORDER="0" ALT=""></p></td>

<td width=50% valign=top>


<?if (ValidId($SplitTest->ID)) {?>


<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['PagesList']?>
</td></tr>
</table>
<div class=ListDiv2>

<?if (ValidArr($PagesArr)) {?>
<table class=ListTable2>

<?for ($i=0;$i<count($PagesArr);$i++) {
	$Row=$PagesArr[$i];?>

	<tr>
	<td class=<?=$Row->_STYLE?>>

	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>
	<a href="<?=getURL("company", "EditPage=".$Row->ID, "admin")?>">
	<?=$Row->NAME?><br>
	<B><?=$Row->PATH?></B>
	</a>

	
	<td class=ListRowLeft>
	<?
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("split_test", "EditId=$EditId&DeletePage=".$Row->TSP_ID), $Lang['YouSure']);
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>
	

	</td></tr>
<?}?>
</table>
<?}?>



<?PostFORM();?>
<input type="hidden" name="EditId" value="<?=$EditId?>">
<input type="hidden" name="GrpId" value="<?=$GrpId?>">


<table  class=FormTable>


<tr><td class=FormLeftTd>
<?=$Lang['AddNewPage']?>
</td><td class=FormRightTd>
<input type=text  name="NewPage" value="<?=$NewPage?>">
</td></tr>

<?if ($SelectNeeded) {?>
<tr><td class=FormLeftTd>
<p style="font-weight:normal"><?=$Lang['ChooseSite']?></p>
</td><td class=FormRightTd>
<select name="AddToSite">
<?for ($i=0;$i<count($SitesArr);$i++) {?>
	<option value=<?=$SitesArr[$i]->ID?>><?=$SitesArr[$i]->HOST?></option>
<?}?>
</select>
</td></tr>
<?}?>

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

</td></tr></table>




<?include $nsTemplate->Inc("inc/footer");?>