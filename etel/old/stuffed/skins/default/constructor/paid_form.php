<?require_once SELF."/lib/calendar.func.php"?>


<SCRIPT LANGUAGE="JavaScript">
<!--

var FilterShowed=<?=(($ShowFilter==1)?"true":"false")?>;
var FilterMustShowed=<?=(($ShowFilter==1)?"true":"false")?>;
var DataOpShowed=false;

function ShowFilters()
{
	if (DataOpShowed) ShowDataOp();
	var Obj1=GetObj("Filter1");
	var FLink=GetObj("FilterLink");
	var FImg=GetObj("FilterImg");
	var FHidden=GetObj("ShowFilter");
	Obj1.style.display="";
	FLink.innerHTML="<a href=\"javascript:;\" onclick=\"HideFilters();\"><?=$Lang['HideFilter']?></a>";
	FHidden.value=1;
	FImg.src="<?=FileLink("images/close_03.gif");?>";
	FilterShowed=true;
}


function HideFilters()
{
	var Obj1=GetObj("Filter1");
	var FLink=GetObj("FilterLink");
	var FImg=GetObj("FilterImg");
	var FHidden=GetObj("ShowFilter");
	Obj1.style.display="none";
	FLink.innerHTML="<a href=\"javascript:;\" onclick=\"ShowFilters();\"><?=$Lang['ShowFilter']?></a>";
	FHidden.value=0;
	FImg.src="<?=FileLink("images/close_03-1.gif");?>";
	FilterShowed=false;
}

function ShowDataOp()
{
	var Obj1=GetObj("DataOperate");
	if (Obj1.style.display=="none") {
		if (FilterShowed) HideFilters();
		var FImg=GetObj("DataImg");
		Obj1.style.display="";
		FImg.src="<?=FileLink("images/close_04-1.gif");?>";
		DataOpShowed=true;
	}
	else {
		DataOpShowed=false;
		if (FilterMustShowed) ShowFilters();
		Obj1.style.display="none";
		var FImg=GetObj("DataImg");
		FImg.src="<?=FileLink("images/close_04.gif");?>";
	}
}

var Preview1=new Image();
Preview1.src="<?=FileLink("images/close_03-1.gif");?>";
var Preview2=new Image();
Preview2.src="<?=FileLink("images/close_03.gif");?>";
var Preview3=new Image();
Preview3.src="<?=FileLink("images/close_04-1.gif");?>";
var Preview4=new Image();
Preview4.src="<?=FileLink("images/close_04.gif");?>";

function GroupSelection(SelObj)
{
	if (!SelObj.value) SelObj.selectedIndex++;	
}



//-->
</SCRIPT>



<div class=FormDiv>

<?GetFORM();?>
<input type=hidden name=CpId value=<?=$CpId?>>
<input type=hidden name=CurrentGroupBy value="<?=$GroupBy?>">
<input type=hidden id=ShowFilter name=ShowFilter value="<?=$ShowFilter?>">
<input type=hidden name=FormUsed value="1">

<?for($i=0;$i<count($WhereArr);$i++) {?>
<input type=hidden name="WhereArr[<?=$i?>][Mode]" value="<?=$WhereArr[$i]['Mode']?>">
<input type=hidden name="WhereArr[<?=$i?>][Id]" value="<?=$WhereArr[$i]['Id']?>">
<input type=hidden name="WhereArr[<?=$i?>][OrderBy]" value="<?=$WhereArr[$i]['OrderBy']?>">
<input type=hidden name="WhereArr[<?=$i?>][OrderTo]" value="<?=$WhereArr[$i]['OrderTo']?>">
<?}?>

<? if(count($WhereArr)==0&&!$GroupBy) {?>
<input type=hidden name="General" value=1>
<?}?>




<table width=100%>
<tr><td class=FormHeader>

	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr><td width=50 nowrap>
	<b style="color:#000000"><?=$Lang['GroupType']?>: </b>
	</td><td nowrap>
	<?if($GroupByCnt>0){?>
		<select name="GroupBy" onchange="GroupSelection(this);" style="width:220px;">

		<?for ($i=0;$i<count($SelectGrp);$i++) {
			$UseGroup=false;
			for ($j=0;$j<count($SelectGrp[$i]['Punkt']);$j++) {
				if (!isset($GroupByForm[$SelectOrder[$SelectGrp[$i]['Punkt'][$j]]])) continue;
				$UseGroup=true;
			}
			if (!$UseGroup) continue;
			echo "<optgroup style=\"font-size:10px;font-style:normal;\" label=\"";
			echo $SelectGrp[$i]['Name']."\">\n";
			for ($j=0;$j<count($SelectGrp[$i]['Punkt']);$j++) {
				$Key=$SelectOrder[$SelectGrp[$i]['Punkt'][$j]];
				if (isset($GroupByForm[$Key])) {
					echo "<option  value=\"$Key\" ";
					if ($GroupBy==$Key||$CurrentGroupBy==$Key) echo "selected";
					echo ">".$GroupByForm[$Key]."</option>\n";
				}
			}
			echo "</optgroup>";
		}?>
		<?if (count($WhereArr)<2) {?>
		<optgroup style="font-size:10px;font-style:normal;"  label="<?=$Lang['SelGrpOther']?>">
		<option value="General" <?=(($GroupBy=="General")?"selected":"")?>><?=$Lang['GrByGeneral']?></option>
		</optgroup>
		<?}?>
		</select>
	<?}?>


	&nbsp;<input type=submit value="<?=$Lang['Show']?>">
	</td><td align=right>
		<table cellpadding=0 cellspacing=0 border=0><tr>
		<td><p class=ShowHide>
		<span ID="FilterLink">
		<?if ($ShowFilter) {?><a href="javascript:;" onclick="HideFilters();"><?=$Lang['HideFilter']?></a><?} else {?>
		<a href="javascript:;" onclick="ShowFilters();"><?=$Lang['ShowFilter']?></a>
		<?}?>
		</span>
		</p></td>
		<td valign=bottom><img src="<?=FileLink("images/0.gif");?>" width="3" height="1" border="0"><IMG SRC="<?=FileLink("images/close_03".(($ShowFilter)?"":"-1").".gif");?>" ID="FilterImg" WIDTH="11" HEIGHT="11" BORDER="0" ALT=""><img src="<?=FileLink("images/0.gif");?>" width="10" height="1" border="0"></td>
		<td><p class=ShowHide><a href="javascript:;" onclick="ShowDataOp();"><?=$Lang['DataOperate']?></a></p></td>
		<td valign=bottom><img src="<?=FileLink("images/0.gif");?>" width="3" height="1" border="0"><IMG SRC="<?=FileLink("images/close_04.gif");?>" ID="DataImg" WIDTH="11" HEIGHT="11" BORDER="0" ALT=""></td>
		</td></tr></table>
	</td></tr></table>

</td></tr>
<tr ID="Filter1"  style="<?=(($ShowFilter)?"":"display:none")?>"><td class=FormSimpleTd>
	
	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr><td width=50>
	</td><td>

	</td><td width=10% nowrap align=right>
	<?=$Lang['Date']?>:&nbsp;
	</td><td width=23% nowrap>
	<input type=text size=5 class=DateFld  id="ViewDate" name="ViewDate" ondblclick="this.value=''" value="<?=$ViewDate?>">&nbsp;
	<a href="javascript:;" onclick="ShowCalendar('ViewDate');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>" WIDTH="18" HEIGHT="18" BORDER="0" ALT="" align=middle align=absmiddle></a>
	</td>

	<td width=33% rowspan=3 valign=bottom align=right>
	<?=$Lang['Limit']?>:&nbsp;<input type=text size=5 class=DateFld name=Limit value="<?=$Limit?>">
	</td>

	</tr>
	<tr><td colspan=4 height=5><img src="<?=FileLink("images/0.gif");?>" width="1" height="5" border="0"></td></tr>

	<tr><td width=50 nowrap>
	<?=$Lang['Filter']?>:
	</td><td>
	<input type=text size=38 style="width:220px;padding-left:3px;" class=InputFld name="Filter" value="<?=$Filter?>">
	</td><td width=10% nowrap align=right>
	<?=$Lang['Period']?>:&nbsp;
	</td><td width=23% nowrap>
	<input type=text size=5 class=DateFld id="StartDate" name="StartDate" ondblclick="this.value=''" value="<?=$StartDate?>">&nbsp;
	<a href="javascript:;" onclick="ShowCalendar('StartDate');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>" WIDTH="18" HEIGHT="18" BORDER="0" ALT="" align=middle align=absmiddle></a>
	&nbsp;&mdash;&nbsp;
	<input type=text size=5 class=DateFld id="EndDate" name="EndDate" ondblclick="this.value=''" value="<?=$EndDate?>">&nbsp;
	<a href="javascript:;" onclick="ShowCalendar('EndDate');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>" WIDTH="18" HEIGHT="18" BORDER="0" ALT="" align=middle align=absmiddle></a>
	</td>
	</tr>
	
	</table>

</form>
</td></tr>

<tr ID="DataOperate" style="display:none"><td>







<?include $nsTemplate->Inc("constructor/export")?>


<!---->




</td></tr>

</table>
</form>
</div>
<!---->

<IMG SRC="<?=FileLink("images/0.gif")?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">