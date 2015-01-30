<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<?if (ValidArr($CompArr)&&count($CompArr)>1) {?>
	<div class=FormDiv>
	<?GetFORM();?>
	<input type=hidden name=Mode value=<?=$Mode?>>
	<table width=100%>
	<tr><td class=FormHeader>
	<B style="color:#000000"><?=$Lang['SIChooseClient']?></B>&nbsp;<select name=SelectCpId>
	<option></option>
	<?for ($i=0;$i<Count($CompArr);$i++) {?>
	<option value=<?=$CompArr[$i]->ID?> <?=(($CompArr[$i]->ID==$SelectCpId)?"selected":"")?>><?=$CompArr[$i]->NAME?></option>
	<?}?>
	</select>&nbsp;<input type=submit value="<?=$Lang['Choose']?>">
	</td></tr></table>
	</form>
	</div><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">
<?}?>


<?if (count($SaleItemsList)>0) {?>


<table class=ListTable>

<tr><td class=ReportSimpleTd colspan=2>
<?PostFORM();?>
<input type=hidden name="Mode" value="sale_items">
<input type=hidden name="SelectCpId" value="<?=$SelectCpId?>">

<input type=text size=40 name="Filter" value="<?=$Filter?>">&nbsp;<input type=submit value="<?=$Lang['Filter']?>">
</form>
</td></tr>

<form action="<?=$nsProduct->SelfAction()?>" method=post>
	<input type=hidden name="Mode" value="sale_items">


<?for ($i=0;$i<count($SaleItemsList);$i++) {
	$Row=$SaleItemsList[$i];?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>

	<td width=25 nowrap>
	<?if (!ValidId($Row->WATCH_ID)) {?>
		<input type=checkbox value=1 name="AddToMy[<?=$Row->ID?>]">
	<?}?>
	</td>

	<td >
	<B style="color:#000000"><?=$Row->NAME?></B>
	</td>
	</tr></table>

	</td></tr>

<?}?>


<?if (!$NoAdd) {?>
<tr><td class=ReportSimpleTd2 colspan=2>
<input type=submit value="<?=$Lang['AddChoosedToMy2']?>">
</td></tr>
<?}?>
</form>


</table>

<?}
else include $nsTemplate->Inc("inc/no_records");?>


<?include $nsTemplate->Inc("inc/footer");?>