<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<SCRIPT LANGUAGE="JavaScript">
<!--

oBtnUp=false;
oBtnDown=false;
oBtnDisable=false;
SelObj=false;
oOrderBySel=false;
oOrderToSel=false;

arrOptions=new Array();
arrTmp=false;
optTemp=false;
arrDisabled=new Array();

SelectSize=<?=count($SelectOrder)?>;

<?	
foreach ($OrderArr as $Pos=>$Row) {
	$Key=$Row['Key'];
	echo "arrOptions[$Pos]=new Array();\n";
	echo "arrOptions[$Pos][0]='".$Lang[$OrderConstPath[$Key]]."';\n";
	echo "arrOptions[$Pos][1]='$Key';\n";
	echo "arrOptions[$Pos][2]='".$Row['OrderBy']."';\n";
	echo "arrOptions[$Pos][3]='".$Row['OrderTo']."';\n";
	if (!isset($OrderKeys[$Key])) echo "arrDisabled.push('$Key');\n";
}
?>


function InitButtons()
{
	oBtnUp=GetObj("BtnUp");
	oBtnDown=GetObj("BtnDown");
	oBtnDisable=GetObj("BtnDisable");
	oOrderBySel=GetObj("SelectOrderBy");
	oOrderToSel=GetObj("SelectOrderTo");
}

function InitSelect()
{
	SelObj=GetObj("GroupSelect");
}

function PrepareButtons(SelObj)
{
	if (!oBtnUp) InitButtons();
	if (SelObj.selectedIndex==0) oBtnUp.disabled=true;
	else oBtnUp.disabled=false;
	if (SelObj.selectedIndex==SelObj.options.length-1) oBtnDown.disabled=true;
	else oBtnDown.disabled=false;

	oBtnDisable.disabled=false;
	if (IsOptionDisabled(SelObj.options[SelObj.selectedIndex].value)) oBtnDisable.value="<?=$Lang['Enable']?>";
	else oBtnDisable.value="<?=$Lang['Disable']?>";

	oOrderBySel.options[0].innerHTML=arrOptions[SelObj.selectedIndex][0];
	var OrderBy=0;
	var OrderTo=0;
	if (arrOptions[SelObj.selectedIndex][2]=="NAME") OrderBy=0;
	if (arrOptions[SelObj.selectedIndex][2]=="CNT") OrderBy=1;
	if (arrOptions[SelObj.selectedIndex][2]=="UNI") OrderBy=2;
	if (arrOptions[SelObj.selectedIndex][2]=="SALECNT") OrderBy=3;
	if (arrOptions[SelObj.selectedIndex][2]=="SALEUNI") OrderBy=4;
	if (arrOptions[SelObj.selectedIndex][2]=="ACTIONCNT") OrderBy=5;
	if (arrOptions[SelObj.selectedIndex][2]=="ACTIONUNI") OrderBy=6;
	if (arrOptions[SelObj.selectedIndex][2]=="ACTCONV") OrderBy=7;
	if (arrOptions[SelObj.selectedIndex][2]=="SALECONV") OrderBy=8;
	if (arrOptions[SelObj.selectedIndex][2]=="INCOME") OrderBy=9;
	if (arrOptions[SelObj.selectedIndex][2]=="ROI") OrderBy=10;
	if (arrOptions[SelObj.selectedIndex][2]=="COST") OrderBy=11;
	if (arrOptions[SelObj.selectedIndex][3]=="ASC") OrderTo=0;
	if (arrOptions[SelObj.selectedIndex][3]=="DESC") OrderTo=1;
	oOrderBySel.selectedIndex=OrderBy;
	oOrderToSel.selectedIndex=OrderTo;
}

function ChangeOrderBy(oOrderBySel)
{
	if (!SelObj) InitSelect();
	arrOptions[SelObj.selectedIndex][2]=oOrderBySel.value;
}

function ChangeOrderTo(oOrderToSel)
{
	if (!SelObj) InitSelect();
	arrOptions[SelObj.selectedIndex][3]=oOrderToSel.value;
}

function DisableOption()
{
	if (!SelObj) InitSelect();
	oBtnDisable.disabled=true;
	if (!IsOptionDisabled(SelObj.options[SelObj.selectedIndex].value)) {
		SelObj.options[SelObj.selectedIndex].style.color="#aaaaaa";
		SelObj.options[SelObj.selectedIndex].style.textDecoration="line-through";
		arrDisabled.push(SelObj.options[SelObj.selectedIndex].value);
	}
	else {
		RemoveFromDisable(SelObj.options[SelObj.selectedIndex].value);
		SelObj.options[SelObj.selectedIndex].style.color="";
		SelObj.options[SelObj.selectedIndex].style.textDecoration="";
	}
	//if (SelObj.selectedIndex<SelObj.options.length-1) SelObj.selectedIndex++;
	//else SelObj.selectedIndex--;

	PrepareButtons(SelObj);
}

function IsOptionDisabled(Key)
{
	for (var i=0;i<arrDisabled.length;i++) {
		if (arrDisabled[i]==Key) return true;
	}
	return false;
}

function RemoveFromDisable(Key)
{
	for (var i=0;i<arrDisabled.length;i++) {
		if (arrDisabled[i]==Key) arrDisabled[i]="";
	}
}


function MoveOptionUp()
{
	if (!SelObj) InitSelect();
	var Position=SelObj.selectedIndex;
	optTemp=arrOptions[Position-1];
	arrOptions[Position-1]=arrOptions[Position];
	arrOptions[Position]=optTemp;
	optTemp=false;
	NewPosition=SelObj.selectedIndex-1;
	ReDrawSelect();
	SelObj.selectedIndex=NewPosition;
	PrepareButtons(SelObj);
}

function MoveOptionDown()
{
	if (!SelObj) InitSelect();
	var Position=SelObj.selectedIndex;
	optTemp=arrOptions[Position+1];
	arrOptions[Position+1]=arrOptions[Position];
	arrOptions[Position]=optTemp;
	optTemp=false;
	NewPosition=SelObj.selectedIndex+1;
	ReDrawSelect();
	SelObj.selectedIndex=NewPosition;
	PrepareButtons(SelObj);
}

function ReDrawSelect()
{
	DivObj=GetObj("SelectDiv");
	HTML="<select ID=\"GroupSelect\" name=\"SaveOrderArr\" size="+SelectSize+" onchange=\"PrepareButtons(this);\"  style=\"width:100%\">";
	for(var i=0;i<arrOptions.length;i++) {
		HTML+="<option value=\""+arrOptions[i][1]+"\"";
		if (IsOptionDisabled(arrOptions[i][1])) HTML+=" style=\"color:#aaaaaa;text-decoration:line-through;\" ";
		HTML+=">"+arrOptions[i][0]+"</option>";
	}
	HTML+="</select>";
	DivObj.innerHTML=HTML;
	InitSelect();
}


function SaveSelect()
{
	oForm=GetObj("SelectForm");
	if (!SelObj) InitSelect();
	var Key="";
	var HidObj=false;
	var Position=0;
	for(var i=0;i<SelObj.options.length;i++) {
		Key=SelObj.options[i].value;
		HidObj=GetObj("SaveEnableArr["+Key+"]");
		if (IsOptionDisabled(Key)) {
			HidObj.value=0;
			UsePosition=100;
		}
		else {
			HidObj.value=1;
			UsePosition=Position;
		}

		HidObj=GetObj("SavePosArr["+Key+"]");
		HidObj.value=UsePosition;
		HidObj=GetObj("SaveOrderByArr["+Key+"]");
		HidObj.value=arrOptions[i][2];
		HidObj=GetObj("SaveOrderToArr["+Key+"]");
		HidObj.value=arrOptions[i][3];
		if (UsePosition==Position)Position++;
	}

	oForm.submit();
}

function SaveByDefault()
{
	oForm=GetObj("ByDefaultForm");
	oForm.submit();
}


//-->
</SCRIPT>





<?PostFORM(false, false, false, "ID=\"SelectForm\"");?>
<input type=hidden name="CompanyId" value="<?=$CompanyId?>">
<input type=hidden name="Mode" value="<?=$Mode?>">

<?
foreach ($OrderArr as $Pos=>$Row) {
	echo "<input type=hidden ID=\"SavePosArr[".$Row['Key']."]\" name=\"SavePosArr[".$Row['Key']."]\" value=\"$Pos\">\n";
	echo "<input type=hidden ID=\"SaveEnableArr[".$Row['Key']."]\" name=\"SaveEnableArr[".$Row['Key']."]\" value=\"".(($Row['Checked'])?1:0)."\">\n";
	echo "<input type=hidden ID=\"SaveOrderByArr[".$Row['Key']."]\" name=\"SaveOrderByArr[".$Row['Key']."]\" value=\"".$Row['OrderBy']."\">\n";
	echo "<input type=hidden ID=\"SaveOrderToArr[".$Row['Key']."]\" name=\"SaveOrderToArr[".$Row['Key']."]\" value=\"".$Row['OrderTo']."\">\n";
}
?>

<div style="border-width:1px;border-style:solid;border-color:#C7C7C7;padding:4px;">
<table width=100% cellpadding=4 cellspacing=0 border=0>

<tr>
<td valign=top width=33% rowspan=3>
	<?=$Lang['ListItem']?><br><br>
	<div ID=SelectDiv width=100%>
	<select ID="GroupSelect" name="SaveOrderArr" style="width:100%">
	</select>
	</div>

</td>

<td valign=top width=33% height=10>
&nbsp;<br><br>
<input type=button ID="BtnUp" value="<?=$Lang['Up']?>" disabled onclick="MoveOptionUp();">&nbsp;<input type=button ID="BtnDown" value="<?=$Lang['Down']?>" disabled onclick="MoveOptionDown();"><br><br>
<input type=button ID="BtnDisable" value="<?=$Lang['Disable']?>" disabled onclick="DisableOption();">

</td><td valign=top width=33% height=10>

<?=$Lang['DefaultSort']?><br><br>
<select ID="SelectOrderBy" onchange="ChangeOrderBy(this);">
<option value="NAME">---------------------------------------------------</option>
<option value="CNT"><?=$Lang['ByHit']?></option>
<option value="UNI"><?=$Lang['ByUni']?></option>
<option value="SALECNT"><?=$Lang['BySaleCnt']?></option>
<option value="SALEUNI"><?=$Lang['BySaleUni']?></option>
<option value="ACTIONCNT"><?=$Lang['ByActionCnt']?></option>
<option value="ACTIONUNI"><?=$Lang['ByActionUni']?></option>
<option value="ACTCONV"><?=$Lang['ByActionConv']?></option>
<option value="SALECONV"><?=$Lang['BySaleConv']?></option>
<option value="INCOME"><?=$Lang['ByIncome']?></option>
<?if ($Mode=="PAID") {?>
<option value="ROI"><?=$Lang['ByROI']?></option>
<option value="COST"><?=$Lang['ByCost']?></option>
<?}?>
</select>&nbsp;
<select ID="SelectOrderTo" onchange="ChangeOrderTo(this);">
<option value="ASC"><?=$Lang['Asc']?></option>
<option value="DESC"><?=$Lang['Desc']?></option>
</select>

</td>
</tr>

<tr>
<td width=33% colspan=2 valign=top height=1><p>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#C6C6C6">
</p>
</td></tr>


<tr>
<td width=33% colspan=2 valign=top>
<input type=button value="<?=$Lang['Save']?>" onclick="SaveSelect();">

<?if (ValidId($CompanyId)&&$CompanyId>0) {?>
&nbsp;<input type=button value="<?=$Lang['Reset']?>" onclick="SaveByDefault();">
<?}?>

</td>


</tr>


</form>
</table>
</div>




<SCRIPT LANGUAGE="JavaScript">
<!--
ReDrawSelect();
SelObj.selectedIndex=0;
PrepareButtons(SelObj);
//-->
</SCRIPT>

<?GetFORM(false, false, false, "ID=\"ByDefaultForm\"");?>
<input type=hidden name="ClearOrder" value="1">
<input type=hidden name="CompanyId" value="<?=$CompanyId?>">
<input type=hidden name="Mode" value="<?=$Mode?>">
</form>

<?include $nsTemplate->Inc("inc/footer");?>