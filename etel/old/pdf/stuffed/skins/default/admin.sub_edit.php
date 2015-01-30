<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<?require_once SELF."/lib/calendar.func.php"?>


<SCRIPT LANGUAGE="JavaScript">
<!--

var CurrentType=<?=$EditArr['Type']?>;

function CheckButtons(oSelect)
{
	var AddBtn=GetObj("AddBtn");	
	var SaveBtn=GetObj("SaveBtn");	
	if (oSelect.value==CurrentType) {
		if (AddBtn) AddBtn.disabled=false;
		if (SaveBtn) SaveBtn.disabled=false;
	}
	if (oSelect.value!=CurrentType) {
		if (AddBtn) AddBtn.disabled=true;
		if (SaveBtn) SaveBtn.disabled=true;
	}
}


//-->
</SCRIPT>


<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=50% valign=top>


<?PostFORM();?>
<input type="hidden" name="EditId" value="<?=$EditId?>">
<input type="hidden" name="GrpId" value="<?=$GrpId?>">


<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$TableCaption?>
</td></tr>
</table>



<table  class=FormTable>

<tr><td class=FormLeftTd>
<?=$Lang['Name']?>
<?FormError("Name")?>
</td><td class=FormRightTd>
<input type=text  name="EditArr[Name]" value="<?=$EditArr['Name']?>">
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['Descr']?>
<?FormError("Descr")?>
</td><td class=FormRightTd>
<textarea rows=6 name="EditArr[Descr]"><?=$EditArr['Descr']?></textarea>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['SrcId']?><br>
<span style="font-weight:normal;font-size:10px;color:#666666"><?=$Lang['SrcIdDesc']?></span>
</td><td class=FormRightTd>
<input type=text  name="EditArr[SrcId]" value="<?=$EditArr['SrcId']?>">
</td></tr>




<tr><td class=FormLeftTd>
<?=$Lang['Type']?>
</td><td class=FormRightTd>
<select name="EditArr[Type]" <?=((ValidId($EditId))?"onchange=\"CheckButtons(this);\"":"")?>>
<option value=0 <?=(($EditArr['Type']==0)?"selected":"")?>><?=$Lang['Type1']?></option>
<option value=1 <?=(($EditArr['Type']==1)?"selected":"")?>><?=$Lang['Type2']?></option>
</select>
</td></tr>


<?if(ValidId($EditId)&&$nsUser->ADMIN){?>
<tr><td class=FormLeftTd>
<?=$Lang['ShowOn1stPage']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[Watch]" value=1 <?=(($EditArr['Watch']==1)?"checked":"")?>>
</td></tr>
<?}?>

</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>
</table>

</form>




<?if (ValidId($EditId)&&count($MoveArr)>0) {
	include $nsTemplate->Inc("inc/move_camp_piece");
}?>


</td>
<td><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="10" HEIGHT="1" BORDER="0" ALT=""></p></td>
<td width=50% valign=top>

<?if ($ShowCosts) {?>


<? if ($EditArr['Type']==0) {?>





	<table class=CaptionTable>
	<tr><td class=CaptionTd>
	<?=$Lang['Type1']?>
	</td></tr>
	</table>
	<div class=ListDiv2>
	<?PostFORM();?>
	<input type=hidden name=EditCostId value=<?=$EditCostId?>>
	<input type="hidden" name="EditId" value="<?=$EditId?>">
	<input type="hidden" name="EditCost[Mode]" value=1>

	<table  class=FormTable>

	<tr><td class=FormLeftTd>
	<?=$Lang['PayCost']?>, <?=$SubCamp->Currency[0]?>
	<?FormError("Cost")?>
	</td><td class=FormRightTd>
	<input type=text name="EditCost[Cost]" value="<?=$EditCost['Cost']?>">
	</td></tr>

	<tr><td class=FormLeftTd>
	<?=$Lang['PayStartDate2']?>
	<?FormError("StartDate")?>
	</td><td class=FormRightTd2>
	<input type=text class=DateFld size=10 id="StartDate2" name="EditCost[StartDate]" value="<?=$EditCost['StartDate']?>">&nbsp;<a href="javascript:;"  onClick="ShowCalendar('StartDate2');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>"  WIDTH=18 HEIGHT=18 BORDER=0 ALT="" align=absmiddle></a>
	</td></tr>
	</table>

	<table class=SubmitTable>
	<tr><td class=SubmitLeftTd>
	</td><td class=SubmitRightTd>
	<input type=submit id="AddBtn" value="<?=$Lang['Add']?>">
	</td></tr>
	</table>


	</form>


	<table width=100% style="border:1px solid #ccc;">
	<?PostFORM();?>
	<input type="hidden" name="EditId" value="<?=$EditId?>">
	<input type="hidden" name="UpdateSum" value=1>
	<input type="hidden" name="UpdateMode" value=1>


	<?if (count($CostArr2)>0) {?>
	<?for ($i=0;$i<count($CostArr2);$i++) {
		$Row=$CostArr2[$i];?>
		<tr><td class="<?=$Row->_STYLE?>" style="padding-right:10px;padding-left:4px;">

		<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
		<td width=1><input type=checkbox name="SumThis[<?=$Row->ID?>]" <?=(($Row->SUM_THIS==1)?"checked":"")?> value=1 title="<?=$Lang['PayEnable']?>" style="margin-right:10px;"></td>
		<td width=100%>
		<B><?=$Row->NAME?></B>
		<?if ($Row->NAME&&$Row->TITLE) echo "<br>";?>
		<?if ($Row->TITLE) echo $Row->TITLE;?>
		</td>
		<td nowrap>
		<a href="<?=getURL("sub_camp", "EditId=$EditId&EditCostId=".$Row->ID)?>">
		<?=ShowCost($Row->COST, $SubCamp->Currency)?>
		</a>&nbsp;
		</td>
		<td nowrap>
		<a href="<?=getURL("sub_camp", "EditId=$EditId&DelCostId=".$Row->ID, "admin")?>" onclick="return confirm('<?=$Lang['YouSure']?>')">
		<IMG SRC="<?=FileLink("images/icon_delete.gif");?>" WIDTH="11" HEIGHT="11" BORDER="0" ALT="">
		</a>
		</td>

		</tr></table>

		</td></tr>
	<?}?>
		<tr><td height=25  style="padding-right:10px;padding-left:6px;">
		<input type=submit id="SaveBtn" value="<?=$Lang['Save']?>">
		</td></tr>
	<?}?>

	</form>
	</table>

	</div>


<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">

<?}?>

<? if ($EditArr['Type']==1) {?>


<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['CampCost']?>
</td></tr>
</table>
<div class=ListDiv2>
<?PostFORM();?>
<input type=hidden name=EditCostId value=<?=$EditCostId?>>
<input type="hidden" name="EditId" value="<?=$EditId?>">
<input type="hidden" name="EditCost[Mode]" value=0>

	<table  class=FormTable>

	<tr><td class=FormLeftTd>
	<?=$Lang['PayName']?>
	</td><td class=FormRightTd>
	<input type=text name="EditCost[Name]" value="<?=$EditCost['Name']?>">
	</td></tr>

	<tr><td class=FormLeftTd>
	<?=$Lang['PayCost']?>, <?=$SubCamp->Currency[0]?>
	<?FormError("Cost")?>
	</td><td class=FormRightTd>
	<input type=text name="EditCost[Cost]" value="<?=$EditCost['Cost']?>">
	</td></tr>

	 <tr><td class=FormLeftTd>
	<?=$Lang['PayStartDate']?>
	</td><td class=FormRightTd2>
	<input type=text  class=DateFld id="StartDate" name="EditCost[StartDate]" value="<?=$EditCost['StartDate']?>">&nbsp;<a href="javascript:;"  onClick="ShowCalendar('StartDate');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>"  WIDTH=18 HEIGHT=18 BORDER=0 ALT="" align=absmiddle></a>
	</td></tr>
	

	</table>

	<table class=SubmitTable>
	<tr><td class=SubmitLeftTd>
	</td><td class=SubmitRightTd>
	<input type=submit id="AddBtn" value="<?=$Lang['Add']?>">
	</td></tr>
	</table>




</form>


<table width=100% style="border:1px solid #ccc;">
<?PostFORM();?>
<input type="hidden" name="EditId" value="<?=$EditId?>">
<input type="hidden" name="UpdateSum" value=1>
<input type="hidden" name="UpdateMode" value=0>


<?if (count($CostArr)>0) {?>
<?for ($i=0;$i<count($CostArr);$i++) {
	$Row=$CostArr[$i];?>
	<tr><td class="<?=$Row->_STYLE?>" style="padding-right:10px;padding-left:4px;">

	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=1><input type=checkbox name="SumThis[<?=$Row->ID?>]" <?=(($Row->SUM_THIS==1)?"checked":"")?> value=1 title="<?=$Lang['PayEnable']?>" style="margin-right:10px;"></td>
	<td width=100%>
	<B><?=$Row->NAME?></B>
	<?if ($Row->NAME&&$Row->TITLE) echo "<br>";?>
	<?if ($Row->TITLE) echo $Row->TITLE;?>
	</td>
	<td nowrap>
	<a href="<?=getURL("sub_camp", "EditId=$EditId&EditCostId=".$Row->ID)?>">
	<?=ShowCost($Row->COST, $SubCamp->Currency)?>
	</a>&nbsp;
	</td>
	<td nowrap>
	<a href="<?=getURL("sub_camp", "EditId=$EditId&DelCostId=".$Row->ID, "admin")?>" onclick="return confirm('<?=$Lang['YouSure']?>')">
	<IMG SRC="<?=FileLink("images/icon_delete.gif");?>" WIDTH="11" HEIGHT="11" BORDER="0" ALT="">
	</a>
	</td>

	</tr></table>

	</td></tr>
<?}?>
	<tr><td height=25  style="padding-right:10px;padding-left:6px;">

	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>
	<input type=submit id="SaveBtn" value="<?=$Lang['Save']?>">
	</td><td nowrap>
	<B><?=$Lang['PayTotal']?> 
	<?=ShowCost($TotalCost, $SubCamp->Currency)?>
	</B>
	</td><td>
	<p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="20" HEIGHT="1" BORDER="0" ALT=""></p>
	</td></tr></table>


	</td></tr>
<?}?>

</form>
</table>


</div>

<?}?>





<?}?>
</td></tr></table>







<?include $nsTemplate->Inc("inc/footer");?>