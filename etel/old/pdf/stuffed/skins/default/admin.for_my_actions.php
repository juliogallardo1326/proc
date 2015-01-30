<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<?if (count($ActionsList)>0) {?>

<table class=ListTable>

<?PostFORM();?>
	<input type=hidden name="Mode" value="actions">


<?for ($i=0;$i<count($ActionsList);$i++) {
	$Row=$ActionsList[$i];?>

	<?if ($Row->NewComp&&$nsUser->ADMIN) {?>
		<tr><td colspan=3 class=ListRowRight style="border-bottom-width:2px;">
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
	<B style="color:#000000"><span style="font-size:10px;color:999999"><?=$Row->HOST?></span>&nbsp;
	<?=$Row->NAME?></B>
	</td>
	<td>
	<?
	$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("actions", "EditId=".$Row->ID."&CpId=".$Row->COMPANY_ID."&SiteId=".$Row->SITE_ID));
	$nsButtons->Dump();
	?>
	</td>

	</tr></table>
	
	</td></tr>

<?}?>


<?if (!$NoAdd) {?>
<tr><td class=ReportSimpleTd2 colspan=3>
<input type=submit value="<?=$Lang['AddChoosedToMy2']?>">
</td></tr>
<?}?>
</form>


</table>

<?}
else include $nsTemplate->Inc("inc/no_records");?>


<?include $nsTemplate->Inc("inc/footer");?>