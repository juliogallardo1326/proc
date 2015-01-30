<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<SCRIPT LANGUAGE="JavaScript">
<!--

function ShowHosts(ID)
{
	oDiv=GetObj('Site_'+ID);
	oImg=GetObj('SiteImg_'+ID);
	if (oDiv.style.display=="") {
		oDiv.style.display="none";
		oImg.src="<?=FileLink("images/icon_plus.gif");?>";
	}
	else {
		oDiv.style.display="";
		oImg.src="<?=FileLink("images/icon_minus.gif");?>";
	}
}

function ExpandAll()
{
	for(var i=0;i<Sites.length;i++) {
		ID=Sites[i];
		oDiv=GetObj('Site_'+ID);
		oImg=GetObj('SiteImg_'+ID);
		oDiv.style.display="";
		oImg.src="<?=FileLink("images/icon_minus.gif");?>";
	}
}

function CollapseAll()
{
	for(var i=0;i<Sites.length;i++) {
		ID=Sites[i];
		oDiv=GetObj('Site_'+ID);
		oImg=GetObj('SiteImg_'+ID);
		oDiv.style.display="none";
		oImg.src="<?=FileLink("images/icon_plus.gif");?>";
	}
}


var Sites = new Array();
<?if (isset($HostsArr)&&is_array($HostsArr)) {
	for ($i=0;$i<count($HostsArr);$i++) {?>
	Sites[<?=$i?>]=<?=$HostsArr[$i]->ID?>;
<?}}?>

var PreloadImg=new Image();
PreloadImg.src="<?=FileLink("images/icon_minus.gif");?>";

//-->
</SCRIPT>

<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=50% valign=top>


<?PostFORM();?>
<input type="hidden" name="EditId" value="<?=$EditId?>">


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
<input type=text  name="EditArr[Name]" value="<?=$EditArr['Name']?>" style="width:expression(this.type=='checkbox'?'':'100%');">
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['Descr']?>
<?FormError("Descr")?>
</td><td class=FormRightTd>
<textarea rows=6 name="EditArr[Descr]"><?=$EditArr['Descr']?></textarea>
</td></tr>

<?if ($nsUser->ADMIN) {?>

<tr><td class=FormLeftTd>
<?=$Lang['Hidden']?>
<?FormError("Hidden")?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[Hidden]" value=1 name="" <?=(($EditArr['Hidden'])?"checked":"")?>>
</td></tr>


<?if(ValidId($EditId)&&$nsUser->ADMIN){?>
<tr><td class=FormLeftTd>
<?=$Lang['Show1stPage']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[Watch]" value=1 <?=(($EditArr['Watch']==1)?"checked":"")?>>
</td></tr>
<?}?>

<?}?>


<tr><td class=FormLeftTd>
<?=$Lang['Currency']?>
</td><td class=FormRightTd>
<input type=text maxlength=10 name="EditArr[Currency]" value="<?=$EditArr['Currency']?>" style="width:expression(this.type=='checkbox'?'':'100%');"><br><br>
<input type=radio id="PosBefore" name="EditArr[CurrencyPos]" value=0 <?=(($EditArr['CurrencyPos']==0)?"checked":"")?>>&nbsp;<label for="PosBefore"><?=$Lang['PositionBefore']?></label><br>
<input type=radio id="PosAfter" name="EditArr[CurrencyPos]" value=1 <?=(($EditArr['CurrencyPos']==1)?"checked":"")?>>&nbsp;<label for="PosAfter"><?=$Lang['PositionAfter']?></label>
</td></tr>

<? if ($nsProduct->LICENSE==3 && $nsUser->ADMIN) {?>
<tr><td class=FormLeftTd>
<?=$Lang['CMaxSites']?>
</td><td class=FormRightTd>
<input type=text  name="EditArr[MaxSites]" value="<?=$EditArr['MaxSites']?>">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['AllowPhp']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[AllowPhp]" value=1 <?=(($EditArr['AllowPhp']==1)?"checked":"")?>>
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


</td>
<td><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="10" HEIGHT="1" BORDER="0" ALT=""></p></td>

<td width=50% valign=top>



<?if (isset($HostsArr)&&is_array($HostsArr)) {?>
<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$Lang['HostList']?>
</td></tr>
</table>

<table class=ListDiv2 width=100% cellspacing=0><tr><td>
<table class=ListTable2>
<?for ($i=0;$i<count($HostsArr);$i++) {
	$Row=$HostsArr[$i];
	//if (Count($Row->Hosts)<1) continue;
	?>

	<?if ($i>0) {?>
	<tr><td style="padding-top:4px;padding-bottom:4px;"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#E1E1E1"></p></td></tr>
	<?}?>

	<tr><td>

	<table width=100% cellpadding=0 border=0 cellspacing=0><tr>
	<td >
	<B style="color:#000000">
	<?if ($PlaceForPlus) {?>
	<?if ($Row->USE_HOSTS) {?><a href="javascript:;" onclick="ShowHosts(<?=$Row->ID?>);"><?}?>
	<IMG ID="SiteImg_<?=$Row->ID?>" SRC="<?=FileLink("images/".(($Row->USE_HOSTS)?"icon_plus":"0").".gif");?>" WIDTH="9" HEIGHT="9" BORDER="0" ALT=""></a>&nbsp;&nbsp;
	<?}?>
	<a href="<?=getURL("company", "EditId=$EditId&HostId=".$Row->ID)?>">
	<?=$Row->HOST?>
	</a>
	</B>&nbsp;[&nbsp;<a href="<?=getURL("get_code", "CpId=$EditId&SiteId=".$Row->ID, "admin")?>"><IMG SRC="<?=FileLink("images/icon_code.gif");?>" WIDTH="13" HEIGHT="10" BORDER="0" ALT="">&nbsp;&nbsp;<?=$Lang['SiteCode']?></a>]
	</td>

	<td align=right>
	<?
	$nsButtons->PostName=false;
	$nsButtons->Add("edit.gif", $Lang['Edit'], getURL("company", "EditId=$EditId&HostId=".$Row->ID));
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("company", "EditId=$EditId&DeleteHost=".$Row->ID), $Lang['SiteDelWarning']);
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>

	</td></tr>

	<?if ($Row->USE_HOSTS!=0) {?>
	<tr><td>
	<div ID="Site_<?=$Row->ID?>" style="display:none">
	<table cellpadding=2 cellspacing=0 border=0>
	<?for($j=0;$j<count($Row->Hosts);$j++) {?>
	<tr><td style="padding-left:20px;">
	<span style="font-size:10px;">
	<B><?=$Row->Hosts[$j]->HOST?></B>
	</td></tr>
	<?}?>
	</table>
	</div>

	</td></tr>
	<?}?>
<?}?>


</table>
</td></tr>

	<?if ($ShowExpand>0) {?>
	<table width=100% cellpadding=0 border=0 cellspacing=0 height=30>
	<tr><td style="padding-left:10px;"><span style="font-size:10px;">
	<a href="javascript:;" onclick="ExpandAll();"><?=$Lang['ExpandAll']?></a> / <a href="javascript:;" onclick="CollapseAll();"><?=$Lang['CollapseAll']?></a>
	</span></td></tr>
	</table>
	<?}?>


<?}?>

<?if (ValidVar($SiteId)) {?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	ShowHosts(<?=$SiteId?>);
	//-->
	</SCRIPT>
<?}?>

</td></tr></table>

<?include $nsTemplate->Inc("inc/footer");?>