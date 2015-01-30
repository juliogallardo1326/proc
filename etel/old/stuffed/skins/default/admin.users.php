<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<?if (ValidArr($UsersArr)||ValidArr($AgentsArr)) {?>



<table class=ListTable>

<? if ($nsProduct->LICENSE!=3) {?>

	<?for ($i=0;$i<count($AgentsArr);$i++) {
		$Row=$AgentsArr[$i];	?>

		<tr>
		<td class=<?=$Row->_STYLE?>>
		<table width=100% cellpadding=0 cellspacing=0 border=0>
		<tr><td width=100%><p>


		<B><a href="<?=getURL("agents", "EditUid=".$Row->ID)?>"><?=$Row->NAME?></a></B> (<?=$Row->EMAIL?>)<br>
		<I><?=$Row->LOGIN?></I>
		<?if ($Row->SUPER_ADMIN) {?>
		<br><B><I><?=$Lang['SuperUser']?></I></B>
		<?}?>		
		</p></td><td>
		<?
		$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("agents", "EditUid=".$Row->ID));
		if ($Row->ID!=$nsUser->UserId() && $nsUser->ADMIN) $nsButtons->Add("undo.gif", $Lang['DeleteFromUser'], getURL("users", "UnregisterUser=".$Row->ID), $Lang['YouSure']);
		if ($Row->ID!=$nsUser->UserId()) $nsButtons->Add("delete.gif", $Lang['Delete'], getURL("users", "DeleteUid=".$Row->ID), $Lang['YouSure']);
		$nsButtons->Dump();
		?>
		</td></tr></table>

		</td>
		</tr>
	<?}?>

<?}?>

<?for ($i=0;$i<count($UsersArr);$i++) {
	$Row=$UsersArr[$i];	?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr><td width=100%><p>


	<B><a href="<?=getURL("users", "EditUid=".$Row->ID)?>"><?=$Row->NAME?></a></B> (<?=$Row->EMAIL?>)<br>
	<I><?=$Row->LOGIN?></I>
	<?if ($Row->SUPER_USER) {?>
	<br><B><I><?=$Lang['SuperUser']?></I></B>
	<?}?>
	<?if ($Row->COMP_NAME&&($nsProduct->LICENSE==3&&$nsUser->ADMIN)) echo "<br>".$Row->COMP_NAME;?>
	
	</p></td><td>
	<?
	$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("users", "EditUid=".$Row->ID));
	if ($nsUser->ADMIN) $nsButtons->Add("undo.gif", $Lang['DeleteFromUser'], getURL("users", "UnregisterUser=".$Row->ID), $Lang['YouSure']);
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("users", "DeleteUid=".$Row->ID), $Lang['YouSure']);
	$nsButtons->Dump();
	?>
	</td></tr></table>

	</td>
	</tr>
<?}?>


</table>

<?}
else include $nsTemplate->Inc("inc/no_records");?>

<?include $nsTemplate->Inc("inc/footer");?>