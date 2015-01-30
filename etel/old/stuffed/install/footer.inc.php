
<?if (!$NoButtons) {?>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td height=50 align=right>
<?
if ($Step>0&&!$NoPrevStep) echo "<input type=button ID=\"StepBack\" value=\"&laquo; ".(($PrevTitle)?$PrevTitle:"Назад")."\" onclick=\"StepTo(".($Step-1).", 1);\">";
echo "&nbsp;";
if (!$Finished)echo "<input type=submit ID=\"StepForw\" value=\"".(($NextTitle)?$NextTitle:"Далее")." &raquo;\" onclick=\"return StepTo($Step);\"  ".(($DisableNext)?"disabled":"").">";
?>
</td></tr>
</table>
<?}?>


</td></tr>





<tr>
<td width="100%" valign="bottom" height="40">
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="40">
<tr>
<td width="250" height=1>
<p><img src="../skins/default/images/0.gif" width="1" height="1" border="0"></p>
</td>
<td bgcolor="#707070" height=1>
<p><img src="../skins/default/images/0.gif" width="1" height="1" border="0"></p>
</td>
</tr>
<tr>
<td width="250">
</td>
<td>
<p class=Copyright><?=$Lang['Copyright']?></p>

</td>
</tr>
</table>
</td>
</tr>
</table>
</form>

</body>

</html>