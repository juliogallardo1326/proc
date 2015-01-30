<?if ($GLOBALS['FirstIter']==true) {?>
<?GetFORM(false, false, false, "ID=\"MOVE_FORM\" NAME=\"MOVE_FORM\"");?>
<input ID="MoveId" type=hidden name=MoveId value=0>
<input ID="MoveTo" type=hidden name=MoveTo value=0>
</form>

<script language="JavaScript" src="<?=FileLink("campaign.js");?>"></script>

<table width=100% cellpadding=0 cellspacing=0 border=0 style="border-bottom: 1px solid #cccccc;margin-bottom:5px;"><tr><td style="padding:5px;">
<span class=CaptionText><?=$Lang['CampaignGroups']?></span>
</td></tr></table>
<div ID="MoveRadio[ROOT]" style="display:none"><input type=radio onclick="SubmitMoveForm('ROOT')"> <?=$Lang['ToRoot']?></div>

<?}?>
<?
$GLOBALS['FirstIter']=false;
echo "<SCRIPT LANGUAGE=JavaScript>\n<!--\n";
for ($i=0;$i<count($CampArr);$i++) {
	echo "AllowIds[".$CampArr[$i]->ID."]=".$CampArr[$i]->PARENT_ID.";\n";
	echo "CampIds[".$GLOBALS['JavaArrCounter']."]=".$CampArr[$i]->ID.";\n";
	$GLOBALS['JavaArrCounter']++;
}
echo "//--></SCRIPT>\n";

?>



<table class=ListTable2>


<? for($i=0;$i<count($CampArr);$i++) {
	$Row=$CampArr[$i];?>

<tr>
<td class=ListRowLeft>
<?
$nsButtons->PostName=false;
$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("campaign", "EditId=".$Row->ID));	
$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("campaign", "DeleteId=".$Row->ID), $Lang['YouSure']);	
$nsButtons->Dump();
?>
</td>
<td class=<?=$Row->_STYLE?>>
<input type=radio ID="MoveRadio[<?=$Row->ID?>]"  value="<?=$Row->ID?>" style="display:none" onclick="SubmitMoveForm(this.value)">
<a href="<?=getURL("incampaign", "CampId=".$Row->ID)?>">
<B><?=$Row->NAME?></B></a>
&nbsp;<span style="font-size:10px;color:#86C71D">(<a href="<?=getURL("paid_constructor", "GrpId=".$Row->ID, "report")?>" style="font-size:10px;color:#86C71D"><?=$Lang['Stat']?>&nbsp;<IMG SRC="<?=FileLink("images/icon_ref_stat.gif");?>" WIDTH="8" HEIGHT="8" BORDER="0" ALT=""></a>&nbsp;)</span>

<a href="<?=getURL("incampaign", "CampId=".$Row->ID)?>">
<?if ($Row->DESCRIPTION) {?>
<br>
<span class=ListDescr><?=$Row->DESCRIPTION?></span>
<?}?>

<?if ($nsUser->Columns->ROI||$nsUser->Columns->CONVERSIONS) {?>
<br>

<?if ($nsUser->Columns->ROI) {?>
ROI: <span class="<?=($Row->Report->GoodROI($Row->CampStat['ROI']))?"GoodROI":"BadROI"?>">
<?=$Row->CampStat['ROI']?>%</span>, 
<?}?>

<?if ($nsUser->Columns->CONVERSIONS) {?>
<?=$Lang['ActionsConv']?>: <?=$Row->CampStat['ActionConv']?>%, 
<?=$Lang['SalesConv']?>: <?=$Row->CampStat['SaleConv']?>%
<?}?>
<?}?>

</a>
</td>

</tr>


<?if ($Row->CHILD_COUNT>0) {?>
<tr><td class=ListRowLeft>
</td><td>
<?ListCampTree(GetCampTree($Row->ID, $CompId));?>
</td></tr>
<?}?>


<?}?>

</table>
