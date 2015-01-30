<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<SCRIPT LANGUAGE="JavaScript">
<!--

function EnableTimeFld(oSelect, CpId, SiteId, Key)
{
	var oText=GetObj(Key+"_"+CpId+"_"+SiteId);
	if (oSelect.value==1) {
		oText.disabled=false;
		ValidIgnoreTime(false, Key+"_"+CpId+"_"+SiteId);
	}
	else {
		oText.disabled=true;
		oText.value='';
	}
}

function ValidIgnoreTime(oText, ObjName)
{
	if (!oText&&!ObjName) return;
	if (ObjName) var oText=GetObj(ObjName);
	if (!oText.value) return;
	if (oText.value) oText.value=parseInt(oText.value);
	//if (!oText.value||oText.value<1) oText.value=1;
}

//-->
</SCRIPT>

<br>
<table  class=FormTable width=100%>
<?GetFORM();?>
<input type=hidden name=CpId value="<?=$CpId?>">

<tr height=30>
<td  width=20% style="padding-left:10px;padding-right:10px;">&nbsp;</td>
<td  width=20% style="padding-left:5px;" valign=top><p class=ReportHeaderName><?=$Lang['DblPageLoad']?></td>
<td  width=20% style="padding-left:5px;" valign=top><p class=ReportHeaderName><?=$Lang['DblRefCome']?></td>
<td  width=20% style="padding-left:5px;" valign=top><p class=ReportHeaderName><?=$Lang['DblRedirAction']?></td>
<td  width=20% style="padding-left:5px;" valign=top><p class=ReportHeaderName><?=$Lang['DblSale']?></td>
</tr>

<tr><td colspan=5  height=2><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="2" BORDER="0" ALT="" style="background:#E1E1E1"></p></td></tr>



<tr height=30>
<td height=30 width=20% style="padding-left:10px;padding-right:10px;"><B>
<?if (!$CpId) echo $Lang['Global']?>
<?if ($CpId) echo $Lang['ForClient']?>
</B></td>
<td height=30 width=20% style="padding-left:5px;">
<select name="<?=$GlobName?>[PageLoad]" onchange="EnableTimeFld(this, 0, 0, 'PageLoad');">
<option value=0 <?=(($Glob->STOP_DBL_PAGE_LOAD==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Glob->STOP_DBL_PAGE_LOAD==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<?if ($CpId&&$nsProduct->LICENSE==3) {?>
<option value=2 <?=(($Glob->STOP_DBL_PAGE_LOAD==2)?"selected":"")?>><?=$Lang['Inherits']?>
<?if ($ST[0][0]->STOP_DBL_PAGE_LOAD==0) echo $Lang['Store']?>
<?if ($ST[0][0]->STOP_DBL_PAGE_LOAD==1) echo $Lang['Ignore']?>
</option>
<?}?>
</select>
<input type=text size=4 <?=(($Glob->STOP_DBL_PAGE_LOAD!=1)?"disabled":"")?> name="<?=$GlobName?>[TimePageLoad]" ID="PageLoad_0_0" value="<?=$Glob->TIME_DBL_PAGE_LOAD?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="<?=$GlobName?>[AdvClick]" onchange="EnableTimeFld(this, 0, 0, 'AdvClick');">
<option value=0 <?=(($Glob->STOP_DBL_ADV_CLICK==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Glob->STOP_DBL_ADV_CLICK==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<?if ($CpId&&$nsProduct->LICENSE==3) {?>
<option value=2 <?=(($Glob->STOP_DBL_ADV_CLICK==2)?"selected":"")?>><?=$Lang['Inherits']?>
<?if ($ST[0][0]->STOP_DBL_ADV_CLICK==0) echo $Lang['Store']?>
<?if ($ST[0][0]->STOP_DBL_ADV_CLICK==1) echo $Lang['Ignore']?>
</option>
<?}?>
</select>
<input type=text size=4 <?=(($Glob->STOP_DBL_ADV_CLICK!=1)?"disabled":"")?> name="<?=$GlobName?>[TimeAdvClick]" ID="AdvClick_0_0" value="<?=$Glob->TIME_DBL_ADV_CLICK?>"  onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="<?=$GlobName?>[Event]" onchange="EnableTimeFld(this, 0, 0, 'Event');">
<option value=0 <?=(($Glob->STOP_DBL_EVENT==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Glob->STOP_DBL_EVENT==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<?if ($CpId&&$nsProduct->LICENSE==3) {?>
<option value=2 <?=(($Glob->STOP_DBL_EVENT==2)?"selected":"")?>><?=$Lang['Inherits']?>
<?if ($ST[0][0]->STOP_DBL_EVENT==0) echo $Lang['Store']?>
<?if ($ST[0][0]->STOP_DBL_EVENT==1) echo $Lang['Ignore']?>
</option>
<?}?>
</select>
<input type=text size=4 <?=(($Glob->STOP_DBL_EVENT!=1)?"disabled":"")?> name="<?=$GlobName?>[TimeEvent]" ID="Event_0_0" value="<?=$Glob->TIME_DBL_EVENT?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="<?=$GlobName?>[Sale]" onchange="EnableTimeFld(this, 0, 0, 'Sale');">
<option value=0 <?=(($Glob->STOP_DBL_SALE==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Glob->STOP_DBL_SALE==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<?if ($CpId&&$nsProduct->LICENSE==3) {?>
<option value=2 <?=(($Glob->STOP_DBL_SALE==2)?"selected":"")?>><?=$Lang['Inherits']?>
<?if ($ST[0][0]->STOP_DBL_SALE==0) echo $Lang['Store']?>
<?if ($ST[0][0]->STOP_DBL_SALE==1) echo $Lang['Ignore']?>
</option>
<?}?>
</select>
<input type=text size=4 <?=(($Glob->STOP_DBL_SALE!=1)?"disabled":"")?> name="<?=$GlobName?>[TimeSale]" ID="Sale_0_0" value="<?=$Glob->TIME_DBL_SALE?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
</tr>


<tr><td colspan=5  height=20><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0"></p></td></tr>


<?for ($i=0;$i<count($ClientsArr);$i++) {
	$Cp=$ClientsArr[$i];
	$Cp->Set=$ST[$Cp->ID][0];?>

<?if (!$CpId) {?>
<tr><td colspan=5  height=2><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="2" BORDER="0" ALT="" style="background:#E1E1E1"></p></td></tr>


<tr height=30>
<td height=30 width=20% style="padding-left:10px;padding-right:10px;">
<span class=CaptionText><?=$Cp->NAME?></span>
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="SaveSet[<?=$Cp->ID?>][0][PageLoad]" onchange="EnableTimeFld(this, <?=$Cp->ID?>, 0, 'PageLoad');">
<option value=0 <?=(($Cp->Set->STOP_DBL_PAGE_LOAD==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Cp->Set->STOP_DBL_PAGE_LOAD==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<option value=2 <?=(($Cp->Set->STOP_DBL_PAGE_LOAD==2)?"selected":"")?>><?=$Lang['Inherit']?></option>
</select>
<input type=text size=4 <?=(($Cp->Set->STOP_DBL_PAGE_LOAD!=1)?"disabled":"")?> name="SaveSet[<?=$Cp->ID?>][0][TimePageLoad]" ID="PageLoad_<?=$Cp->ID?>_0" value="<?=$Cp->Set->TIME_DBL_PAGE_LOAD?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="SaveSet[<?=$Cp->ID?>][0][AdvClick]" onchange="EnableTimeFld(this, <?=$Cp->ID?>, 0, 'AdvClick');">
<option value=0 <?=(($Cp->Set->STOP_DBL_ADV_CLICK==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Cp->Set->STOP_DBL_ADV_CLICK==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<option value=2 <?=(($Cp->Set->STOP_DBL_ADV_CLICK==2)?"selected":"")?>><?=$Lang['Inherit']?></option>
</select>
<input type=text size=4 <?=(($Cp->Set->STOP_DBL_ADV_CLICK!=1)?"disabled":"")?> name="SaveSet[<?=$Cp->ID?>][0][TimeAdvClick]" ID="AdvClick_<?=$Cp->ID?>_0" value="<?=$Cp->Set->TIME_DBL_ADV_CLICK?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="SaveSet[<?=$Cp->ID?>][0][Event]" onchange="EnableTimeFld(this, <?=$Cp->ID?>, 0, 'Event');">
<option value=0 <?=(($Cp->Set->STOP_DBL_EVENT==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Cp->Set->STOP_DBL_EVENT==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<option value=2 <?=(($Cp->Set->STOP_DBL_EVENT==2)?"selected":"")?>><?=$Lang['Inherit']?></option>
</select>
<input type=text size=4 <?=(($Cp->Set->STOP_DBL_EVENT!=1)?"disabled":"")?> name="SaveSet[<?=$Cp->ID?>][0][TimeEvent]" ID="Event_<?=$Cp->ID?>_0" value="<?=$Cp->Set->TIME_DBL_EVENT?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="SaveSet[<?=$Cp->ID?>][0][Sale]" onchange="EnableTimeFld(this, <?=$Cp->ID?>, 0, 'Sale');">
<option value=0 <?=(($Cp->Set->STOP_DBL_SALE==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Cp->Set->STOP_DBL_SALE==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<option value=2 <?=(($Cp->Set->STOP_DBL_SALE==2)?"selected":"")?>><?=$Lang['Inherit']?></option>
</select>
<input type=text size=4 <?=(($Cp->Set->STOP_DBL_SALE!=1)?"disabled":"")?> name="SaveSet[<?=$Cp->ID?>][0][TimeSale]" ID="Sale_<?=$Cp->ID?>_0" value="<?=$Cp->Set->TIME_DBL_SALE?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
</tr>
<?}?>



<?for ($j=0;$j<count($SitesArr[$Cp->ID]);$j++) {
	$Row=$SitesArr[$Cp->ID][$j];
	$Row->Set=$ST[$Cp->ID][$Row->ID];?>

<tr><td colspan=5  height=1><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#E1E1E1"></p></td></tr>


<tr height=30>
<td height=30 width=20% style="padding-left:10px;padding-right:10px;"><?=$Row->HOST?></td>
<td height=30 width=20% style="padding-left:5px;">
<select name="SaveSet[<?=$Cp->ID?>][<?=$Row->ID?>][PageLoad]" onchange="EnableTimeFld(this, <?=$Cp->ID?>, <?=$Row->ID?>, 'PageLoad');">
<option value=0 <?=(($Row->Set->STOP_DBL_PAGE_LOAD==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Row->Set->STOP_DBL_PAGE_LOAD==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<option value=2 <?=(($Row->Set->STOP_DBL_PAGE_LOAD==2)?"selected":"")?>><?=$Lang['Inherit']?></option>
</select>
<input type=text size=4 <?=(($Row->Set->STOP_DBL_PAGE_LOAD!=1)?"disabled":"")?> name="SaveSet[<?=$Cp->ID?>][<?=$Row->ID?>][TimePageLoad]" ID="PageLoad_<?=$Cp->ID?>_<?=$Row->ID?>" value="<?=$Row->Set->TIME_DBL_PAGE_LOAD?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="SaveSet[<?=$Cp->ID?>][<?=$Row->ID?>][AdvClick]" onchange="EnableTimeFld(this, <?=$Cp->ID?>, <?=$Row->ID?>, 'AdvClick');">
<option value=0 <?=(($Row->Set->STOP_DBL_ADV_CLICK==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Row->Set->STOP_DBL_ADV_CLICK==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<option value=2 <?=(($Row->Set->STOP_DBL_ADV_CLICK==2)?"selected":"")?>><?=$Lang['Inherit']?></option>
</select>
<input type=text size=4 <?=(($Row->Set->STOP_DBL_ADV_CLICK!=1)?"disabled":"")?> name="SaveSet[<?=$Cp->ID?>][<?=$Row->ID?>][TimeAdvClick]" ID="AdvClick_<?=$Cp->ID?>_<?=$Row->ID?>" value="<?=$Row->Set->TIME_DBL_ADV_CLICK?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="SaveSet[<?=$Cp->ID?>][<?=$Row->ID?>][Event]" onchange="EnableTimeFld(this, <?=$Cp->ID?>, <?=$Row->ID?>, 'Event');">
<option value=0 <?=(($Row->Set->STOP_DBL_EVENT==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Row->Set->STOP_DBL_EVENT==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<option value=2 <?=(($Row->Set->STOP_DBL_EVENT==2)?"selected":"")?>><?=$Lang['Inherit']?></option>
</select>
<input type=text size=4 <?=(($Row->Set->STOP_DBL_EVENT!=1)?"disabled":"")?> name="SaveSet[<?=$Cp->ID?>][<?=$Row->ID?>][TimeEvent]" ID="Event_<?=$Cp->ID?>_<?=$Row->ID?>" value="<?=$Row->Set->TIME_DBL_EVENT?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
<td height=30 width=20% style="padding-left:5px;">
<select name="SaveSet[<?=$Cp->ID?>][<?=$Row->ID?>][Sale]" onchange="EnableTimeFld(this, <?=$Cp->ID?>, <?=$Row->ID?>, 'Sale');">
<option value=0 <?=(($Row->Set->STOP_DBL_SALE==0)?"selected":"")?>><?=$Lang['Store']?></option>
<option value=1 <?=(($Row->Set->STOP_DBL_SALE==1)?"selected":"")?>><?=$Lang['Ignore']?></option>
<option value=2 <?=(($Row->Set->STOP_DBL_SALE==2)?"selected":"")?>><?=$Lang['Inherit']?></option>
</select>
<input type=text size=4 <?=(($Row->Set->STOP_DBL_SALE!=1)?"disabled":"")?> name="SaveSet[<?=$Cp->ID?>][<?=$Row->ID?>][TimeSale]" ID="Sale_<?=$Cp->ID?>_<?=$Row->ID?>" value="<?=$Row->Set->TIME_DBL_SALE?>" onchange="ValidIgnoreTime(this);" onkeyup="ValidIgnoreTime(this);">
</td>
</tr>



<?}?>
<tr><td colspan=3  height=20><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0"></p></td></tr>
<?}?>


<tr><td colspan=3  height=20 style="padding-left:10px;">
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>


</form>
</table>






<?include $nsTemplate->Inc("inc/footer");?>