
<SCRIPT LANGUAGE="JavaScript">
<!--

function GoToUpdate()
{
	oForm=GetObj("InstallForm");
	oForm.action="update.php";
	oForm.submit();
}

//-->
</SCRIPT>

<table  class=FormTable>

<tr><td class=FormLeftTd>
<?=$Lang['Host']?> *
</td><td class=FormRightTd>
<input type=text  name="DbHost" value="<?=$DbHost?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['Port']?> 
</td><td class=FormRightTd>
<input type=text  name="DbPort" value="<?=$DbPort?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['Db']?> *
</td><td class=FormRightTd>
<input type=text  name="DbName" value="<?=$DbName?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['DbUsername']?> 
</td><td class=FormRightTd>
<input type=text  name="DbUser" value="<?=$DbUser?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['DbPass']?> 
</td><td class=FormRightTd>
<input type=text  name="DbPass" value="<?=$DbPass?>">
</td></tr>

<tr><td colspan=2></td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['TablePfx']?> *
</td><td class=FormRightTd>
<input type=text  name="DbPref" value="<?=$DbPref?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['UseModR-2']?>
</td><td class=FormRightTd>
<input type=checkbox  name="UseModR" <?=(($DisableModR)?"disabled":"")?> value="1" <?=(($UseModR)?"checked":"")?>>
</td></tr>

<tr><td colspan=2></td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['SendUseInfo-2']?>
</td><td class=FormRightTd>
<input type=checkbox  name="SendUsage" value="1" <?=(($SendUsage)?"checked":"")?>>
</td></tr>


<?if ($UpdateNeeded) {?>
<tr><td class=FormLeftTd>

</td><td class=FormRightTd>
<input type=button value="<?=$Lang['RepairConf']?>" onclick="GoToUpdate();">
</td></tr>

<?}?>
</table>
