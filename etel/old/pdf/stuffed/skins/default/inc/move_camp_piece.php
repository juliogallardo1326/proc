<?$OptionColor="ffffff"?>

<div style="width:100%" class=ListDiv>
<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['MoveToGrp']?>
</td></tr>
</table>
<table  class=FormTable>
<?GetFORM();?>
<input type=hidden name="EditId" value="<?=$EditId?>">

<tr><td class=ReportSimpleTd2 style="padding-left:10px;padding-top:10px;">

<select name="MoveCampTo">
<option value=-1></option>
<?for($i=0;$i<count($MoveArr);$i++) {?>
<?if ($MoveArr[$i]->PARENT_ID==0) {
	if ($OptionColor=="ffffff") $OptionColor="f0f0f0";
	else $OptionColor="ffffff";
}?>
<option value="<?=$MoveArr[$i]->ID?>" style="background:<?=$OptionColor?>;background-color:<?=$OptionColor?>;" <?=((ValidId($GrpId)&&$GrpId==$MoveArr[$i]->ID)?"selected":"")?>>
<?for($j=0;$j<$MoveArr[$i]->LEVEL;$j++) echo "&nbsp;&nbsp;"?>
&gt; <?=$MoveArr[$i]->NAME?>
</option>
<?}?>
</select>

</td></tr>
<tr><td class=ReportSimpleTd2 style="padding-left:9px;">
<input type=submit value="<?=$Lang['Move']?>">
</td></tr>
</form>
</table>
</div>
