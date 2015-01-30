<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<div class=FormDiv>
<table width=100%>
<tr><td class=FormHeader <?=(($Pages)?"align=center":"")?>>


<?if (!$Pages) {?>
<?GetFORM();?>

<?if ($FileExists) {?>
<input type=hidden name=DoImport value="1">
<input type=submit value="<?=$Lang['Import']?>">
&nbsp;<b style="color:#000000">(<?=$FileName?>, <?=FileSizeStr($FileSize)?><?if ($FileModified) {?>, <?=$Lang['LastModify']?>: <?=date("Y-m-d H:i:s", $FileModified)?><?}?>)</b>
&nbsp;<input type=text size=5 name=pp value=<?=$PerPage?>>&nbsp;<?=$Lang['RowsPerPage']?>
<?} else {?>
	<b style="color:#000000"><?=$FileName?>: <?=$Lang['FindFail']?></b>
<?}?>
<?} else {?>
	<span style="font-size:14px;color:#000000"><?=$Lang['Progress']?>: <b><?=$CurrentProgress?>%</b></span>
<?}?>

</form>


</td></tr></table>
</div>

<?if ($Pages) $Pages->Dump();?>

<?if (!$Pages) {?>
<div style="background:#ffffff;padding:14px;color:#000000"><?=$Lang['ImportComment']?></div>
<?}?>

<?include $nsTemplate->Inc("inc/footer");?>