<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<script type=javascript>

var ActionsArr = new Array;
var ActionIds = new Array();
<?for ($i=0;$i<count($ActionsArr);$i++) {?>
	echo "ActionsArr[$i]='".$ActionsArr[$i]->NAME."';\n";
	echo "ActionsIds[$i]='".$ActionsArr[$i]->ID."';\n";
<?}?>

</script>

<div class=FormDiv>
<table width=100% >
<tr><td class=FormHeader>
<?GetFORM();?>
<input type=hidden name="CpId" value="<?=$CpId?>">
<input type=hidden name="FormClicked" value="1">

<table cellpadding=0 cellspacing=0 border=0><tr>


<?if (count($SitesArr)>1) {?>
<td>
<B style="color:#000000"><?=$Lang['ChooseSite']?></B>:&nbsp;
<select name=SiteId>
<option></option>
<?for ($i=0;$i<count($SitesArr);$i++) {?>
	<option value=<?=$SitesArr[$i]->ID?> <?=(($SitesArr[$i]->ID==$SiteId)?"selected":"")?>><?=$SitesArr[$i]->HOST?></option>
<?}?>
</select>
&nbsp;
</td>
<?}?>

<td>
<select name="CodeType">
<option value=1 <?=(($CodeType==1)?"selected":"")?>><?=$Lang['CodeType1']?></option>
<option value=3 <?=(($CodeType==3)?"selected":"")?>><?=$Lang['CodeType3']?></option>
<?if ($nsProduct->LICENSE!=3 || $AllowPhp) {?>
<option value=4 <?=(($CodeType==4)?"selected":"")?>><?=$Lang['CodeType4']?></option>
<?}?>
</select>
&nbsp;
</td><td>
<select name="CodePlace">
<option value=1 <?=(($CodePlace==1)?"selected":"")?>><?=$Lang['ForSite']?></option>
<option value=2 <?=(($CodePlace==2)?"selected":"")?>><?=$Lang['ForShop']?></option>
<option value=3 <?=(($CodePlace==3)?"selected":"")?>><?=$Lang['ForAction']?></option>
</select>
</td>
<?if (count($ActionsArr)>0&&$CodePlace==3) {?>
<td>&nbsp;
<select name="ActionId">
<?for ($i=0;$i<count($ActionsArr);$i++) {?>
	<option value=<?=$ActionsArr[$i]->ID?> <?=(($ActionsArr[$i]->ID==$ActionId)?"selected":"")?>><?=$ActionsArr[$i]->NAME?></option>
<?}?>
</select>
<?}?>
<td>&nbsp;<input type=submit value="<?=$Lang['Refresh']?>"></td>
</tr></table>
</form>
</td></tr></table>
</div>

<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">


<div style="border-width:1px;border-style:solid;border-color:#C7C7C7;padding:10px;">

<span style="color:#000000">
<?=$TopCodeHelp?>
</span>
<br><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">

<textarea rows=9 style="width:100%;font-family:Courier;font-size:14px;border-width:1px; border-style:solid;border-color:#C7C7C7;padding:4px;background:#E5E5E5;color:#000000;" readonly onclick="this.select();">
<?=$ResultCode?>
</textarea>

<?if (ValidVar($CodeComment)) {?>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT=""><br><span style="color:#000000">
<?=$CodeComment?>
<?}?>
</span><br><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">
</div>


<?include $nsTemplate->Inc("inc/footer");?>