<?include $nsTemplate->Inc("inc/header");?>
<?require_once SELF."/lib/calendar.func.php"?>

<script language="JavaScript" src="<?=FileLink("logs.js");?>"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--

var VisLogArr= new Array;

<?if (count($VisLogArr)>0) {
	foreach ($VisLogArr as $VisId=>$LogIds) {
		echo "VisLogArr[$VisId]=new Array;\n";
		for ($i=0;$i<count($LogIds);$i++) {
			echo "VisLogArr[$VisId][$i]=".$LogIds[$i].";\n";
		}
	}
}
?>

//-->
</SCRIPT>







<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td height=24 width=100% >


<table width=100% height=26 cellpadding=0 cellspacing=0 border=0>
<tr height=2>
<td bgcolor="#<?=(($Mode=="Hits")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#<?=(($Mode=="Clicks")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<?if($Settings['All']->FRAUD_ENABLE) {?><td bgcolor="#<?=(($Mode=="Fraud")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td><?}?>
<td bgcolor="#<?=(($Mode=="Actions")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#<?=(($Mode=="Sales")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#<?=(($Mode=="Split")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#<?=(($Mode=="Ref")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#<?=(($Mode=="Key")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#<?=(($Mode=="Undef")?"85C71D":"FFFFFF")?>"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
</tr>

<tr height=24>
<td class=LogTab<?=(($Mode=="Hits")?"2":"3")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Hits", "report");?>"><?=$Lang['MenuHits']?></a></p></td>
<td class=LogTab<?=(($Mode=="Clicks")?"2":"")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Clicks", "report");?>"><?=$Lang['MenuClicks']?></a></p></td>
<?if($Settings['All']->FRAUD_ENABLE) {?><td class=LogTab<?=(($Mode=="Fraud")?"2":"")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Fraud", "report");?>"><?=$Lang['MenuClickFraud']?></a></p></td><?}?>
<td class=LogTab<?=(($Mode=="Actions")?"2":"")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Actions", "report");?>"><?=$Lang['MenuActions']?></a></p></td>
<td class=LogTab<?=(($Mode=="Sales")?"2":"")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Sales", "report");?>"><?=$Lang['MenuSales']?></a></p></td>
<td class=LogTab<?=(($Mode=="Split")?"2":"")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Split", "report");?>"><?=$Lang['MenuSplits']?></a></p></td>
<td class=LogTab<?=(($Mode=="Ref")?"2":"")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Ref", "report");?>"><?=$Lang['MenuRefs']?></a></p></td>
<td class=LogTab<?=(($Mode=="Key")?"2":"")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Key", "report");?>"><?=$Lang['MenuKeys']?></a></p></td>
<td class=LogTab<?=(($Mode=="Undef")?"2":"")?> nowrap><p class=TabsMenu><a href="<?=getURL("logs", $Get."Mode=Undef", "report");?>"><?=$Lang['MenuUndef']?></a></p></td>
<td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("visitor_path", $Get, "report");?>"><?=$Lang['Paths']?></a></p></td>
<td width=100%	style="border-left-style:solid;	border-left-width:1px;	border-left-color:e5e5e5;	border-bottom-style:solid;
	border-bottom-width:1px;	border-bottom-color:#C8C8C8;">&nbsp;</td>
</tr></table>

</td></tr><tr><td width=100% style="border-left-style:solid;border-left-width:1px;border-left-color:#C7C7C7; border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#C7C7C7; border-right-style:solid;border-right-width:1px;border-right-color:#C7C7C7;">

<?GetFORM();?>
<input type=hidden name="CpId" value="<?=$CpId?>">
<input type=hidden name="Limit" value="<?=$Limit?>">
<input type=hidden name="Start" value="<?=$Start?>">
<input type=hidden name="Mode" value="<?=$Mode?>">
<input type=hidden name="FormClicked" value="1">

<table width=100% cellpadding=8 cellspacing=0 border=0>

	<tr><td width=80 style="padding-left:10px;padding-bottom:4px;">
	<?=$Lang['ChooseSite']?>:
	</td><td style="padding-bottom:4px;">
	<select name=SiteId style="width:220px">
	<option value=0><?=$Lang['AllSites']?></option>
	<?for($i=0;$i<Count($SitesArr);$i++) {?>
		<option value="<?=$SitesArr[$i]->ID?>" <?=(($SitesArr[$i]->ID==$SiteId)?"selected":"")?>>
		<?=$SitesArr[$i]->HOST?>
		</option>
	<?}?>
	</select>
	</td><td width=10% nowrap align=right style="padding-bottom:4px;">
	<?=$Lang['Date']?>:
	</td><td width=23% nowrap style="padding-bottom:4px;">
	<input type=text size=5 class=DateFld  id="ViewDate" name="ViewDate" ondblclick="this.value=''" value="<?=$ViewDate?>">&nbsp;
	<a href="javascript:;" onclick="ShowCalendar('ViewDate');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>" WIDTH="18" HEIGHT="18" BORDER="0" ALT="" align=middle align=absmiddle></a>
	</td>

	<td width=33% style="padding-bottom:4px;">
	<input type=text style="width:70px;" name=Limit class=DateFld value="<?=$Limit?>">&nbsp;<?=$Lang['PerPage']?>
	</td>

	</tr>

	<tr><td width=80 nowrap style="padding-left:10px;padding-top:4px;">
	<?=$Lang['Filter']?>:
	</td><td style="padding-top:4px;">
	<?if (count($FilterNames)>0) {?>
	<input type=text style="width:100px;padding-left:3px;" name=Filter value="<?=$Filter?>" ondblclick="this.value='';"><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="5" HEIGHT="1" BORDER="0" ALT=""><select name=FilterFor style="width:115px">
	<?foreach($FilterNames as $Key=>$Name) {?>
	<option value="<?=$Key?>" <?=(($Key==$FilterFor)?"selected":"")?>><?=$Name?></option>
	<?}?>
	</select>
	<?}?>


	</td><td width=10% nowrap align=right style="padding-top:4px;">
	<?=$Lang['Period']?>:
	</td><td width=23% nowrap style="padding-top:4px;">
	<input type=text size=5 class=DateFld id="StartDate" name="StartDate" ondblclick="this.value=''" value="<?=$StartDate?>">&nbsp;
	<a href="javascript:;" onclick="ShowCalendar('StartDate');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>" WIDTH="18" HEIGHT="18" BORDER="0" ALT="" align=middle align=absmiddle></a>
	&nbsp;&mdash;&nbsp;
	<input type=text size=5 class=DateFld id="EndDate" name="EndDate" ondblclick="this.value=''" value="<?=$EndDate?>">&nbsp;
	<a href="javascript:;" onclick="ShowCalendar('EndDate');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>" WIDTH="18" HEIGHT="18" BORDER="0" ALT="" align=middle align=absmiddle></a>
	</td>

	<td style="padding-top:4px;">
	<select name="UserOrder" style="width:70px;">
	<option value="ASC" <?=(($UserOrder=="ASC")?"selected":"")?>><?=$Lang['AscOrder']?></option>
	<option value="DESC" <?=(($UserOrder=="DESC")?"selected":"")?>><?=$Lang['DescOrder']?></option>
	</select>
	</td>
	</tr>
	
	<tr><td style="padding-top:0px;"></td>
	<td style="padding-top:0px;"><input type=submit value="<?=$Lang['Refresh']?>"></td>
	<td colspan=2 style="padding-top:0px;"></td>
	</tr>

</table>


</form>
</td></tr>
</table>



<?if ($Pages->Pages>1) {?>
<?=$Pages->Dump();?>
<?}?>


</td></tr></table>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<td style="padding-left:41px;padding-right:20px;">
<!--// -->

<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">



<?if (count($LogArr)>0) {?>
<table border="0" cellpadding="0" cellspacing="0" width=100%>
<tr>
<?foreach($FldNames as $Key=>$Name) {?>
<td class=ReportHeaderTd2 height=24 style="padding-left:6px;"><p class="ReportHeaderName"><?=$Name?></p></td>
<?}?>
</tr>

<tr>
<td width="100%" height="2" colspan="<?=count($FldNames)?>" bgcolor="#E1E1E1">
<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="2" border="0"></p>
</td>
</tr>

<?foreach ($LogArr as $i=>$Row) {?>
	<tr ID="Log_<?=$Row['ID']?>" onmouseover="HighlightNext(<?=$Row['ID']?>, <?=$Row['VISITOR_ID']?>);" onmouseout="RemoveLight(<?=$Row['ID']?>, <?=$Row['VISITOR_ID']?>);" onclick="CheckAllPath(<?=$Row['VISITOR_ID']?>);" class=LogRecordTR>
	<?foreach ($FldNames as $Key=>$Name) {?>
		<td class=ReportSimpleTd style="color:#000000;" <?=(($Key=="STAMP")?"nowrap":"")?>>
		<?if ($Key=="STAMP") echo "<B>";?>
		<?if ($Key=="LAST_IP") echo "<span style=\"text-decoration:underline\">";?>
		<?if (ValidVar($Row[$Key."_title"])) {?><span title="<?=$Row[$Key."_title"]?>"><?}?>
		<?if (ValidVar($Row[$Key."_link"])) {?><a href="<?=$Row[$Key."_link"]?>"><?}?>
		<?=$Row[$Key]?>
		<?if (ValidVar($Row[$Key."_link"])) {?></a><?}?>
		<?if (ValidVar($Row[$Key."_title"])) {?></span><?}?>
		<?if (($Mode=="Hits"||$Mode=="Ref")&&$Key=="REF"&&ValidVar($Row['REFERER'])) {?>
		&nbsp;[<a href="<?=htmlspecialchars($Row['REFERER'])?>" target=_blank>^</a>]
		<?}?>
		<?if ($Key=="DELETE") {?>
		<a href="<?=getURL("logs", $Get."&Filter=$Filter&FilterFor=$FilterFor&Mode=$Mode&DeleteId=".$Row['DELETE_ID'], "report")?>" onclick="return confirm('<?=$Lang['YouSure']?>');"><IMG SRC="<?=FileLink("images/icon_delete.gif");?>" WIDTH="11" HEIGHT="11" BORDER="0" ALT=""></a>
		<?}?>
		<?if (!ValidVar($Row[$Key])) echo "&nbsp;";?>
		</td>	
	<?}?>
	</tr>

	<tr>
	<td width="100%" height="1" colspan="<?=count($FldNames)?>" bgcolor="#E1E1E1">
	<p><img src="<?=FileLink("images/0.gif");?>" width="1" height="1" border="0"></p>
	</td>
	</tr>
<?}?>


</table>
<?} else {
include $nsTemplate->Inc("inc/no_records");
}?>


</td></tr></table>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<td style="padding-left:41px;padding-right:20px;">
<!--// -->

<?if ($Pages->Pages>1) {?>
<?=$Pages->Dump();?>
<?}?>


<?include $nsTemplate->Inc("inc/footer");?>

