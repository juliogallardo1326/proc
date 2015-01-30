<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<?if (count($VisitorsList)>0) {?>

<table class=ListTable>

<?PostFORM();?>
	<input type=hidden name="Mode" value="visitors">


<?for ($i=0;$i<count($VisitorsList);$i++) {
	$Row=$VisitorsList[$i];?>

	<?if ($Row->NewComp&&$nsUser->ADMIN) {?>
		<tr><td   class=ListRowRight style="border-bottom-width:2px;">
		<span class=MyTrackerHeader><?=$Row->COMP_NAME?></span>
		</td></tr>
	<?}?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>

	<td width=25 nowrap>
	<?if (!ValidId($Row->WATCH_ID)) {?>
		<input type=checkbox value=1 name="AddToMy[<?=$Row->ID?>]">
	<?}?>
	</td>


	
	<td width=100%>
	<B style="color:#000000"><a href="<?=getURL("visitor", "CpId=".$Row->COMPANY_ID."&ViewId=".$Row->ID)?>"><?=$Row->NAME?></a></B>
	<?if ($Row->DESCRIPTION) {?>
	<br><span class=ListDescr><?=stripslashes($Row->DESCRIPTION)?></span>
	<?}?>
	</a>
	</td>

	<td>
	<?
	$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("visitor", "CpId=".$Row->COMPANY_ID."&VisId=".$Row->ID));
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("my_tracker", "Mode=visitors&DeleteId=".$Row->ID), $Lang['YouSure']);
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>

	</td></tr>

<?}?>


<?if (!$NoAdd) {?>
<tr><td class=ReportSimpleTd2 colspan=3>
<input type=submit value="<?=$Lang['AddChoosedToMy1']?>">
</td></tr>
<?}?>
</form>


</table>
<?}
else include $nsTemplate->Inc("inc/no_records");?>


<?include $nsTemplate->Inc("inc/footer");?>