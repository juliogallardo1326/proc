<div ID="ContextDiv" class="ContextDiv" style="width:294;">

<table width=294 height=44 bgcolor=#86C71D cellpadding=0 border=0 cellspacing=0>
<tr><td width=2><p><IMG SRC="<?=FileLink("images/corn_04.gif")?>" WIDTH="2" HEIGHT="44" BORDER="0" ALT=""></p></td>

<td width=16 nowrap valign=top align=center>
<p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="3" HEIGHT="15" BORDER="0" ALT=""><br><IMG SRC="<?=FileLink("images/arrow_03.gif");?>" WIDTH="3" HEIGHT="5" BORDER="0" ALT=""></p>
</td>

<td>
<p class=ContextLink><a href="javascript:;" onclick="ShowHelpBlock();">Hint is available for this page</a></p>
</td>

<td width=1><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="40" BORDER="0" ALT="" style="background:#ffffff"></p></td>


<td width=142 valign=middle>

<table width=142 height=30 cellpadding=0 cellspacing=0 border=0><tr>
<?=GetForm(false, "CpId=$CpId", false, "ID=\"DisableForm\"");?>
<input type=hidden name="DisableContext" value=1>
</form>

<td><p align=center><IMG SRC="<?=FileLink("images/close_07.gif");?>" WIDTH="7" HEIGHT="7" BORDER="0" ALT=""></p></td>
<td width=118>
<p class=ContextClose><a href="javascript:;" onclick="CloseHelpLink();">close</a></p>
</td></tr>

<tr><td><p align=center><IMG SRC="<?=FileLink("images/close_08.gif");?>" WIDTH="10" HEIGHT="8" BORDER="0" ALT=""></p></td>
<td width=118>
<p class=ContextClose><a href="javascript:;" onclick="var Obj=GetObj('DisableForm'); Obj.submit();">don't show again</a></p>
</td></tr>

</table>

</td>
</tr>
</table>
</div>
<SCRIPT LANGUAGE="JavaScript">
<!--

function ShowHelpBlock()
{
	CloseHelpLink();
	var Obj=GetObj('HelpBlock'); 
	Obj.style.display='';
}

function CloseHelpLink()
{
	var Obj=GetObj('ContextDiv'); 
	Obj.style.display='none';
}

//-->
</SCRIPT>
