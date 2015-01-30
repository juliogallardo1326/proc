<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<SCRIPT LANGUAGE="JavaScript">
<!--

var IdsArr = new Array;

<?
for ($i=0;$i<count($IdsArr);$i++) echo "IdsArr[$i]=".$IdsArr[$i].";\n";
?>

function SelectAll()
{
	for (i=0;i<IdsArr.length;i++) {
		Obj=GetObj("SelAgent["+IdsArr[i]+"]");
		Obj.checked=true;
	}
}

function UnSelectAll()
{
	for (i=0;i<IdsArr.length;i++) {
		Obj=GetObj("SelAgent["+IdsArr[i]+"]");
		Obj.checked=false;
	}
}

function SelectedAction(sBox)
{
	var GrpBox=GetObj("GrpMove");
	if (sBox.value=="GrpMove") {
		GrpBox.style.display="";
		return false;
	}	
	GrpBox.style.display="none";
	if (sBox.value=='') return false;
	document.SelectForm.Mode.value=sBox.value;
	document.SelectForm.submit();
}

function MoveToGrp(sBox)
{
	document.SelectForm.Mode.value="GrpMove";
	document.SelectForm.submit();
}

//-->
</SCRIPT>


<div class=FormDiv>
<table width=100% cellpadding=4>

<tr><td class=FormHeader colspan=2 width=100%>

<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=50%>
<?GetFORM();?>
<B style="color:#000000"><?=$Lang['Grp']?></B> <?=GenSelect($Grps, "GrpId", $GrpId);?> <input type=submit value="<?=$Lang['Choose']?>"></form>
</td><td width=50% align=right>
<?if (!$GrpId) {?>
<?GetFORM();?>
<input type=hidden name=Mode value="Update">
<input type=submit value="<?=$Lang['UpdateGrp']?>">
</form>
<?}?>
</td></tr></table>

</td></tr>


<?GetFORM(false, false, false, "name=\"SelectForm\"");?>
<input type=hidden name=Srch value="<?=htmlspecialchars($Srch)?>">
<input type=hidden name=GrpId value="<?=htmlspecialchars($GrpId)?>">
<input type=hidden name=Mode value="<?=$Mode?>">

<tr><td width=50%>
<?if (count($AgentArr)>0){?>
<p>
<a href="javascript:;" onclick="SelectAll();">
<IMG SRC="<?=FileLink("images/icon_select_all.gif");?>" WIDTH="11" HEIGHT="11" BORDER="0" ALT="" title="<?=$Lang['ChooseAll']?>">
&nbsp;<?=$Lang['SelectAll']?></a>&nbsp;
<a href="javascript:;" onclick="UnSelectAll();">
<IMG SRC="<?=FileLink("images/icon_unselect_all.gif");?>" WIDTH="11" HEIGHT="11" BORDER="0" ALT="" title="<?=$Lang['UnselectAll']?>">
&nbsp;<?=$Lang['UnselectAll']?></a>
</p>
<?}?>
</td><td width=50% align=right>
<input type=text size=30 name=Srch value="<?=htmlspecialchars($Srch)?>">&nbsp;<input type=submit value="<?=$Lang['Filter']?>">
</td></tr>

<tr><td width=100% colspan=2>
<?if (count($AgentArr)>0){?><?=$Lang['Operations']?>: <?}?>

	<?if (count($AgentArr)>0){?>
	<select name=Mode onchange="SelectedAction(this);" style="font-size:9px;">
	<option></option>
	<option value="Delete"><?=$Lang['Delete']?></option>
	<option value="GrpMove"><?=$Lang['PutIntoGrp']?></option>
	<?if ($GrpId>0) {?><option value="GrpFree"><?=$Lang['GetOutFromGrp']?></option><?}?>
	<option value="Ignore"><?=$Lang['SetIgnore']?></option>
	</select>

	<select id="GrpMove" name="GrpMove" style="display:none;font-size:9px;" onchange="MoveToGrp(this)">
	<?for ($i=0;$i<count($Grps);$i++) {?>
		<option value="<?=$Grps[$i]['Value']?>"><?=$Grps[$i]['Name']?></option>
	<?}?>
	</select>
	<?}?>
</td></tr>

</table>
</div>

<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">




<?=$Pages->Dump();?>
<table class=ListTable width=100%>



<?for ($i=0;$i<count($AgentArr);$i++) {
	$Row=$AgentArr[$i];?>

	<tr>
	<td class=ListRowLeft><input type=checkbox ID="SelAgent[<?=$Row->ID?>]" name="SelAgent[<?=$Row->ID?>]" value=1></td>
	<td class="<?=$Row->_STYLE?>" colspan=2>
	<B><?=$Row->USER_AGENT?></B>
	<?if ($Row->BAN==1) echo "<br>".$Lang['Ignore'];?>
	</td></tr>


<?}?>
</table>
<?=$Pages->Dump();?>
</form>

<?if (count($AgentArr)<1) include $nsTemplate->Inc("inc/no_records");?>


<?include $nsTemplate->Inc("inc/footer");?>