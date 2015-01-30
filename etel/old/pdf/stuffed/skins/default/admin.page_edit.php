<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>



<?PostFORM();?>
<input type="hidden" name="HostId" value="<?=$HostId?>">
<input type="hidden" name="EditPage" value="<?=$EditPage?>">


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
<input type=text name="EditArr[Name]" value="<?=$EditArr['Name']?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['Path']?>
<?FormError("Path")?>
</td><td class=FormRightTd>
<input type=text name="EditArr[Path]" value="<?=$EditArr['Path']?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['PageIgnore']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[PageIgnore]" <?=(($EditArr['PageIgnore']==1)?"checked":"")?> value="1">
</td></tr>



</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>
</table>

</form>



<?include $nsTemplate->Inc("inc/footer");?>