<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>



<?PostFORM();?>

<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['AddNewKey']?>
</td></tr>
</table>


<table  class=FormTable>

<tr><td class=FormLeftTd>
<?=$Lang['InsertKey']?>
</td><td class=FormRightTd>
<textarea rows=10 name=NewKey wrap=hard></textarea>
</td></tr>

</table>


<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>
</table>

</form>


<?include $nsTemplate->Inc("inc/footer");?>