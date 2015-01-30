<table bgcolor="ffffff" width=100% cellpadding=0 cellspacing=0 border=0>
<tr height=30><td colspan=2>
<B><?=$Host?></B></td></tr>


<?for ($i=0;$i<count($Pages);$i++) {
	$Row=$Pages[$i];?>

	<tr><td><IMG SRC="<?=FileLink("images/icon_doc.gif");?>" WIDTH="12" HEIGHT="9" BORDER="0" ALT=""><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="5" HEIGHT="1" BORDER="0" ALT=""></td><td width=100%>
	
	<? if ($Row['no_link']!=1) echo "<a href=\"".getURL("split_test", "AddPage=".$Row['id']."&EditId=$EditId")."\">";?>
	<?=$Row['path']?> - <?=$Row['name']?>
	<? if ($Row['no_link']!=1) echo "</a>";?>


	</td></tr>


<?}?>


</table>


