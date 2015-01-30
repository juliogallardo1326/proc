<!-- footer -->
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">
</td>
</tr>
</table>



<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="100%" valign="bottom" height="40">
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="40">
<tr>
<td width="25%" height=1>
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
</td>
<td bgcolor="<?=((!$nsProduct->WHITE || !$nsProduct->WHITE_NO_COPY)?"#707070":"")?>" height=1>
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
</td>
</tr>
<tr>
<td width="25%">
</td>
<td>
<p class=Copyright><img src="<?=FileLink("images/0.gif");?>" width="20" height="1" border="0">
<?if (ValidVar($nsProduct->LICENSE)==1&&!$nsProduct->TRIAL_EXCEED) {
echo sprintf($Lang['TrialText'], $nsProduct->DAYS_LEFT, ParseNumberNow($nsProduct->DAYS_LEFT, $Lang['DayN'], $Lang['Day1'], $Lang['Day2']));
}?>
<?if (ValidVar($nsProduct->TRIAL_EXCEED)) echo $Lang['TrialExpired'];?>

<?if (!$nsProduct->WHITE || !$nsProduct->WHITE_NO_COPY) {?>
<?=str_replace("{VERSION}", ValidVar($nsProduct->VERSION), ValidVar($Lang['Powered']))?> 
<?}?>
</p>
</td>
</tr>
</table>
</td>
</tr>
</table>
</body>

</html>