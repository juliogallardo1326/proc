<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<?if (count($ReportsList)>0) {?>

<table class=ListTable>

<?if (!ValidId($EditId)) {?>
	<?PostFORM();?>
	<input type=hidden name="Mode" value="reports">

<?}?>

<?for ($i=0;$i<count($ReportsList);$i++) {
	$Row=$ReportsList[$i];?>

	<?if ($Row->NewComp&&$nsUser->ADMIN) {?>
		<tr><td colspan=3 class=ListRowRight style="border-bottom-width:2px;">
		<span class=MyTrackerHeader><?=$Row->COMP_NAME?></span>
		</td></tr>
	<?}?>

	<tr>
	<?if ($EditId!=$Row->ID) {?>
	<td class=<?=$Row->_STYLE?>>

	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>

	<td width=25 nowrap>
	<?if (!ValidId($EditId)&&!ValidId($Row->WATCH_ID)) {?>
		<input type=checkbox value=1 name="AddToMy[<?=$Row->ID?>]">
	<?}?>
	</td>
	<td width=100%>
	<a href="<?=getURL($Row->Addr, "CpId=".$Row->CP_ID."&ConstId=".$Row->ID, "report")?>">
	<B><span style="font-size:10px;color:999999">(<?=$Row->CONST_TYPE?>)</span> 
	<?=$Row->NAME?></B>
	</a>
	</td>
	<td>
	<?
	$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("my_tracker", "Mode=reports&EditId=".$Row->ID));
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("my_tracker", "Mode=reports&DeleteId=".$Row->ID), $Lang['YouSure']);
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>

	</td>
	<?}
	else {?>



	<td class="<?=$Row->_STYLE?>" style="padding-left:35px;" >
	<?getFORM();?>
	<input type=hidden name="EditId" value="<?=$EditId?>">
	<input type=hidden name="Mode" value="reports">
	<input type=text size=50 name="EditArr[Name]" value="<?=htmlspecialchars(stripslashes($Row->NAME))?>"><br>
	<input type=submit value="<?=$Lang['Save']?>">
	</form>
	</td>



	<?}?>



	</tr>

<?}?>

<?if (!ValidId($EditId)&&!$NoAdd) {?>

<tr><td class=ReportSimpleTd2 colspan=3>
<input type=submit value="<?=$Lang['AddChoosedToMy2']?>">
</td></tr>
</form>

<?}?>


</table>
<?}
else include $nsTemplate->Inc("inc/no_records");?>


<?include $nsTemplate->Inc("inc/footer");?>