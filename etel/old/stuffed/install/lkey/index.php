
<SCRIPT LANGUAGE="JavaScript">
<!--

function OnChangeKey()
{
	oCheck=GetObj("TrialCheck");
	oText=GetObj("KeyText");
	if (oCheck.checked==true||oText.value.length>0) SetDisableOff();
	else SetDisableOn();
}


function SetDisableOn()
{
	oBtn=GetObj("StepForw");
	oBtn.disabled=true;
}

function SetDisableOff()
{
	oBtn=GetObj("StepForw");
	oBtn.disabled=false;
}

function SetKeyField(oCheck)
{
	oText=GetObj("KeyText");
	if (oCheck.checked==true) {
		oText.disabled=true;
		oText.style.textDecoration="line-through";
	}
	else  {
		oText.disabled=false;
		oText.style.textDecoration="";
	}
}

//-->
</SCRIPT>


<table  class=FormTable>

<tr><td class=FormLeftTd>
<?=$Lang['InsertLicenseKey']?>
</td><td class=FormRightTd>

<textarea name="LKey" ID="KeyText" style="width:100%;<?=(($Trial)?"text-decoration:line-through":"")?>" rows=5 onchange="OnChangeKey();" onkeyup="OnChangeKey();" <?=(($Trial)?"disabled":"")?>>
<?=$LKey?>
</textarea>

</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['IWantTrial']?>
</td><td class=FormRightTd>
<input type=checkbox ID="TrialCheck" name=Trial value=1 <?=(($Trial)?"checked":"")?> onchange="OnChangeKey();SetKeyField(this);" onclick="OnChangeKey();SetKeyField(this);">
</td></tr>

</table>
