<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<?PostFORM();?>
<input type="hidden" name="EditId" value="<?=$EditId?>">
<input type="hidden" name="Mode" value="<?=$Mode?>">


<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$TableCaption?>
</td></tr>
</table>



<table  class=FormTable>

<tr><td class=FormLeftTd>
<?=$Lang['Domen']?>
<?FormError("Host")?>
</td><td class=FormRightTd>
<input type=text name="EditArr[Host]" value="<?=$EditArr['Host']?>" style="width:100%;">
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['KeyVarName']?>
<?FormError("KeyVar")?>
</td><td class=FormRightTd>
<input type=text name="EditArr[KeyVar]" value="<?=$EditArr['KeyVar']?>" style="width:100%;">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['StatHostIgnor']?>
<?FormError("Ban")?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[Ban]" value=1 <?=(($EditArr['Ban']==1)?"checked":"")?>>
</td></tr>

</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>
</table>

</form>



<?if (ValidArr($Refs)&&count($Refs)>0) {?>
<div class=ListDiv>
<table class=ListTable>
<?for ($i=0;$i<count($Refs);$i++) {
	$Row=$Refs[$i];?>
	<tr><td class=ListRowRight>
	<a href="<?=$Row->REFERER?>" target=_blank><?=urldecode(urldecode($Row->REFERER))?></a>
	</td></tr>
<?}?>
</table>
</div>
<?}?>

<?include $nsTemplate->Inc("inc/footer");?>