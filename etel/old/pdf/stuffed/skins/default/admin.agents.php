<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<?if (isset($AgentsList)&&is_array($AgentsList)) {?>


<table class=ListTable>

<?for ($i=0;$i<count($AgentsList);$i++) {
	$Row=$AgentsList[$i];	?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr><td width=100%><p>


	<B><a href="<?=getURL("agents", "EditUid=".$Row->ID)?>"><?=$Row->NAME?></a></B> (<?=$Row->EMAIL?>)<br>
	<I><?=$Row->LOGIN?></I>
	<?if ($Row->SUPER_ADMIN) {?>
	<br><B><I><?=$Lang['SuperAdmin']?></I></B>
	<?}?>
	</td><td>
	<?
	$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("agents", "EditUid=".$Row->ID));
	$nsButtons->Add("undo.gif", $Lang['DeleteFromAdmin'], getURL("agents", "UnregisterAdmin=".$Row->ID), $Lang['YouSure']);
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("agents", "DeleteUid=".$Row->ID), $Lang['YouSure']);
	$nsButtons->Dump();
	?>
	</td></tr></table>

	</td></tr>
<?}?>


</table>


<?}?>
<?include $nsTemplate->Inc("inc/footer");?>