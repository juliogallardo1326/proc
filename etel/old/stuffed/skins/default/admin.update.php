<?if (count($UpdatesArr)>0) {?>
<table class=ListTable>

<?for ($i=0;$i<count($UpdatesArr);$i++) {?>

<tr><td class=ListRowRight>
<p><span  class=SectionName><?=$Lang['UpdateTo'].$UpdatesArr[$i]?></span>
<?if ($UpdateDescr[$UpdatesArr[$i]]) echo "<br>".$UpdateDescr[$UpdatesArr[$i]]?>
</td></tr>

<?}?>
<tr><td style="padding:10px;">
<?GetForm();?>
<input type=hidden name="Run" value=1>
<input type=submit value="<?=$Lang['RunUpdate'].$FirstUpdate?>">
<?if (count($UpdatesArr)>1) {?>
<br><br>
<input type=checkbox name="NoHands" value=1 id="RunAll"><label for="RunAll">&nbsp;<?=$Lang['FreeHand']?></label>
<?}?>
</form>
</td></tr>

</table>



<?}?>