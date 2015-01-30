<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<SCRIPT LANGUAGE="JavaScript">
<!--

var UseSSL=<?=(($nsProduct->SSL_LINK)?1:0)?>;

function ShowTR()
{
	var CampSelect=GetObj("CampSelect");
	var SplitSelect=GetObj("SplitSelect");
	var GLink_TR=GetObj("GLink_TR");
	var GKey_TR=GetObj("GKey_TR");
	var GRedir=GetObj("GRedir");
	var GSiteSelect=GetObj("GSiteSelect");
	
	var UseRedirect = GetObj("UseRedirect");
	
	if (CampSelect.value>0) GKey_TR.style.display="";
	else GKey_TR.style.display="none";

	if (CampSelect.value>0&&SplitSelect.value<1) {
		GLink_TR.style.display="";
		GRedir.style.display="";
	}
	else {
		GLink_TR.style.display="none";
		GRedir.style.display="none";
	}
	
	if (UseRedirect.checked && SplitSelect.value<1) GSiteSelect.style.display="";
	else GSiteSelect.style.display="none";
}

//-->
</SCRIPT>

<?if (count($CampArr)>0||count($SplitArr)) {?>

<?GetFORM();?>
<input type=hidden name=GenCode value=1>

<table  class=FormTable>



<?if (count($LinkArr)>0) {?>
<tr><td class=FormRightTd colspan=2>
<?=$Lang['CampaignLinks']?><br>
<?if ($AllowCSV) {?>
<br><span style="font-weight:normal;font-size:10px;color:#666666"><a href="" onmouseover="this.href=window.location.href+'&csv=1'"><?=$Lang['GetCSV']?></a></span><br><br>
<?}?>

<?foreach ($LinkArr as $i=>$Url)  {
	echo "<span class=GlobalMsg><a href=\"".$LinkArr[$i]."\" target=_blank>".$LinkArr[$i]."</a> - ".$KeyArr[$i]."<br></span>";
}?>
</td></tr>
<?}?>


<tr><td class=FormLeftTd>
<?=$Lang['Campaign']?>
</td><td class=FormRightTd>
<select name=CampId ID="CampSelect" onchange="ShowTR()">
<option></option>
<?for ($i=0;$i<count($CampArr);$i++) {?>
	<option value=<?=$CampArr[$i]->ID?> <?=(($CampArr[$i]->ID==$CampId)?"selected":"")?>><?=$CampArr[$i]->NAME?></option>
<?}?>
</select>

</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['SplitTest']?>
</td><td class=FormRightTd>
<select name=SplitId ID="SplitSelect" onchange="ShowTR()">
<option></option>
<?for ($i=0;$i<count($SplitArr);$i++) {?>
	<option value=<?=$SplitArr[$i]->ID?> <?=(($SplitArr[$i]->ID==$SplitId)?"selected":"")?>><?=$SplitArr[$i]->NAME?></option>
<?}?>
</select>

</td></tr>


<tr ID="GLink_TR" style=""><td class=FormLeftTd>
<?=$Lang['UrlTo']?>
</td><td class=FormRightTd>
<input type=text  name="GLink" value="<?=$GLink?>">
</td></tr>


<tr ID="GRedir" style=""><td class=FormLeftTd>
<?=$Lang['UseRedirect']?>
</td><td class=FormRightTd>
<input type=checkbox Id="UseRedirect" name="UseRedirect" value="1" <?=(($UseRedirect)?"checked":"")?>  onclick="ShowTR()">
</td></tr>

<tr ID="GSiteSelect" style=""><td class=FormLeftTd>
<?=$Lang['ChooseSiteForRedirect']?>
</td><td class=FormRightTd>
<select name=SiteId>
<?for ($i=0;$i<count($SitesArr);$i++) {?>
	<option value=<?=$SitesArr[$i]->ID?> <?=(($SitesArr[$i]->ID==$SiteId)?"selected":"")?>><?=$SitesArr[$i]->HOST?></option>
<?}?>
</select>
</td></tr>






<tr ID="GKey_TR" style=""><td class=FormLeftTd>
<?=$Lang['StatKey']?>
<br><span style="font-weight:normal;font-size:10px;color:#666666"><?=$Lang['UrlToComment']?></span>
</td><td class=FormRightTd>
<textarea name="GKey" rows=5 style="width:100%"><?=$GKey?></textarea>
</td></tr>
</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['GenLink']?>">
</td></tr>
</table>

</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
ShowTR();
//-->
</SCRIPT>

<?} else {?>

<p align=center><br><B><?=$Lang['NoRecords']?></B></p>

<?}?>

<?include $nsTemplate->Inc("inc/footer");?>