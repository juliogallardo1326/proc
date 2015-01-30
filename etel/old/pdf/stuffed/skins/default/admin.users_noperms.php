<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>



<?if (isset($UsersList)&&is_array($UsersList)) {?>


<table class=ListTable>

<?for ($i=0;$i<count($UsersList);$i++) {
	$Row=$UsersList[$i];?>

	<tr>
	<td  class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr><td width=100%><p>

	
	<B><?=$Row->NAME?></B> (<?=$Row->EMAIL?>)<br>
	<I><?=$Row->LOGIN?></I>
	
	</td><td>
	
	
	<?
	$nsButtons->Add("redo.gif", $Lang['MakeUser'], getURL("users", "MakeUser=".$Row->ID));
	$nsButtons->Dump();
	?>
	</td></tr></table>

	</td></tr>
<?}?>


</table>

<?}?>


<?include $nsTemplate->Inc("inc/footer");?>