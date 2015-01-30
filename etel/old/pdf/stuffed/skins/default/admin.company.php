<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<?if (isset($ClientsArr)&&is_array($ClientsArr)) {?>


<?$Pages->Dump();?>
<table class=ListTable>

<?for ($i=0;$i<count($ClientsArr);$i++) {
	$Row=$ClientsArr[$i];?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	
	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr><td width=100%>
	<a href="<?=getURL("company", "EditId=".$Row->ID)?>">
	<B><?=$Row->NAME?></B>
	<?if (ValidVar($Row->DESCRIPTION)) {?><br><span class=ListDescr><?=nl2br(stripslashes($Row->DESCRIPTION))?></span><?}?>
	</a>
	
	</td><td>
	
	<?
	$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("company", "EditId=".$Row->ID));
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("company", "DeleteId=".$Row->ID."&".$Pages->Get), $Lang['ClientDelWarning']);
	$nsButtons->Dump();
	?>

	</td></tr></table>

	</td></tr>


<?}?>
</table>
<?$Pages->Dump();?>

<?}?>
<?include $nsTemplate->Inc("inc/footer");?>