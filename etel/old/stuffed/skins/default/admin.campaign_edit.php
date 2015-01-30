<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>





<?PostFORM();?>
<input type="hidden" name="EditId" value="<?=$EditId?>">
<input type="hidden" name="ParentId" value="<?=$ParentId?>">


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

<?if ($SelectComp==true) {?>
<tr><td class=FormLeftTd>
<?=$Lang['Company']?>
<?FormError("Company")?>
</td><td class=FormRightTd>
<?GenSelect($CompArr, "EditArr[Company]", $EditArr['Company']);?>
</td></tr>
<?}?>

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




<?include $nsTemplate->Inc("inc/footer");?>