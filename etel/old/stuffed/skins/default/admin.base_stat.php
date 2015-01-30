<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<?require_once SELF."/lib/calendar.func.php"?>

<SCRIPT LANGUAGE="JavaScript">
<!--

var ClientsArr = new Array();

<?
foreach ($ClientsArr as $JCpId=>$JClient) {
	echo "ClientsArr[$JCpId]= new Array();\n";
	$j=0;
	foreach ($JClient['Sites'] as $JSiteId=>$JSiteName) {
		echo "ClientsArr[$JCpId][$j]= new Array();\n";
		echo "ClientsArr[$JCpId][$j][0]= '$JSiteId';\n";
		echo "ClientsArr[$JCpId][$j][1]= '$JSiteName';\n";
		$j++;
	}
}

?>

function SwitchSites(CpId)
{
	var SelObj=GetObj('SiteSelect');
	var HTML="";
	if (CpId!="all") {
		HTML+="<select name=SiteId><option value=\"all\" style=\"background:#d0d0d0;\"><?=$Lang['DeleteAll']?></option>";
		for(var i=0;i<ClientsArr[CpId].length;i++) {
			HTML+="<option value="+ClientsArr[CpId][i][0]+">"+ClientsArr[CpId][i][1]+"</option>";
		}
		HTML+="</select>";
	}
	SelObj.innerHTML=HTML;
}


function DoRemove(BtnObj) {
	if(confirm('<?=$Lang['ClearWarning']?>')) {
		BtnObj.disabled=true;
		FormObj=GetObj('REMOVE_FORM');
		FormObj.submit();
		return false;
	}
	else return false;
}

//-->
</SCRIPT>


<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td width=50% valign=top>


<table  class=FormTable width=100%>

<tr>
<td height=20>&nbsp;</td>
<td  nowrap><p class=ReportHeaderName><?=$Lang['DbSize']?></td>
<td  nowrap><p class=ReportHeaderName><?=$Lang['RecordsNo']?></td>
</tr>


<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="2" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>

<tr>
<td  height=30 style="padding-left:10px"><B style="color:#000000"><?=$Lang['TblPathStat']?></td>
<td width=60 nowrap><?=FileSizeStr($StatSize);?></td>
<td width=60 nowrap><?=number_format($StatRows, ",");?></td>
</tr>

<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>

<tr>
<td  height=30 style="padding-left:10px"><B style="color:#000000"><?=$Lang['TblActionStat']?></td>
<td width=60 nowrap><?=FileSizeStr($ActionsSize);?></td>
<td width=60 nowrap><?=number_format($ActionsRows, ",");?></td>
</tr>

<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>

<tr>
<td  height=30 style="padding-left:10px"><B style="color:#000000"><?=$Lang['TblSaleStat']?></td>
<td width=60 nowrap><?=FileSizeStr($SalesSize);?></td>
<td width=60 nowrap><?=number_format($SalesRows, ",");?></td>
</tr>

<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>

<tr>
<td  height=30 style="padding-left:10px"><B style="color:#000000"><?=$Lang['TblClickStat']?></td>
<td width=60 nowrap><?=FileSizeStr($CampSize);?></td>
<td width=60 nowrap><?=number_format($CampRows, ",");?></td>
</tr>

<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>

<tr>
<td  height=30 style="padding-left:10px"><B style="color:#000000"><?=$Lang['TblSplitStat']?></td>
<td width=60 nowrap><?=FileSizeStr($SplitSize);?></td>
<td width=60 nowrap><?=number_format($SplitRows, ",");?></td>
</tr>

<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>

<tr>
<td  height=30 style="padding-left:10px"><B style="color:#000000"><?=$Lang['TblUndefStat']?></td>
<td width=60 nowrap><?=FileSizeStr($UndefSize);?></td>
<td width=60 nowrap><?=number_format($UndefRows, ",");?></td>
</tr>

<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>


<tr>
<td  height=30 style="padding-left:10px"><B style="color:#000000"><?=$Lang['TblOther']?></td>
<td width=60 nowrap><?=FileSizeStr($OtherSize);?></td>
<td width=60 nowrap><?=number_format($OtherRows, ",");?></td>
</tr>

<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>

<tr>
<td  height=30 style="padding-left:10px"><span style="color:#000000"><?=$Lang['TotalSize']?></td>
<td width=60 nowrap><B><?=FileSizeStr($TotalSize);?></B></td>
<td width=60 nowrap><B><?=number_format($TotalRows, ",");?></B></td>
</tr>

<tr><td colspan=3><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#c7c7c7;"></p></td></tr>
</table>




</td>
<td width=20><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="20" HEIGHT="1" BORDER="0" ALT=""></p></td>
<td width=50% valign=top>


<div class=FormDiv>
<table width=100%  cellpadding=6>
<?if ($MinStamp&&($nsUser->ADMIN||($nsProduct->LICENSE==2&&$nsUser->SUPER_USER))) {?>
<tr><td class=FormHeader>
<?=$Lang['StatStarted']?> <B><?=$MinStamp?></B>,<br>
<?=$Lang['AvgIncrease']?><B><?=FileSizeStr($AvgDaySize);?></B>
</td></tr>
<?}?>
<tr><td><?=$Lang['OverSize']?> <B><?=FileSizeStr($TotalOverhead);?></B>
<?if($TotalOverhead>0&&($nsUser->SUPER_USER||$nsUser->ADMIN)){?>
<br>
<?GetFORM();?>
<input type=hidden name="DoOptimize" value="1">
<input type=submit value="<?=$Lang['DoOptimize']?>" onclick="return confirm('<?=$Lang['OptWarning']?>');">
</form>
<?}?>
</td></tr>


<?if ($nsUser->ADMIN||$nsUser->SUPER_USER) {?>

	<?GetFORM(false, false, false, "ID=\"REMOVE_FORM\"");?>
	<?if (count($ClientsArr>0)) {?>
		<tr>
		<input type=hidden name=DoClear value=1>
		<td><?=$Lang['ClearStat']?></td>
		</tr>
		<?if (!ValidId($CurrentCompany->ID)||$CurrentCompany->SITE_CNT>0) {?>
			<tr><td>
			<?if ($nsUser->ADMIN&&$nsProduct->LICENSE!=2) {?>
				<select name=ForClient onchange="SwitchSites(this.value);">
				<option value="all" style="background:#d0d0d0;"><?=$Lang['DeleteAll']?></option>
				<?foreach($ClientsArr as $ClientId=>$ClArr) {?>
					<option value=<?=$ClientId?> <?=(($ClientId==$ForClient)?"selected":"")?>>
					<?=$ClArr['Name']?>
					</option>
				<?}?>
				</select>
				&nbsp;
			<?}
			else {?>
				<input type=hidden name=ForClient value=<?=$nsUser->COMPANY_ID?>>
			<?}?>

			<span ID="SiteSelect">
			<?if ($ForClient&&ValidArr($ClientsArr[$ForClient]['Sites'])&&count($ClientsArr[$ForClient]['Sites'])>0) {?>
				<select  name=SiteId>
				<option value="all" style="background:#d0d0d0;"><?=$Lang['DeleteAll']?></option>
				<?foreach ($ClientsArr[$ForClient]['Sites'] as $CSite=>$Name) {?>
					<option value=<?=$CSite?> <?=(($CSite==$SiteId)?"selected":"")?>>
					<?=$Name?>
					</option>
				<?}?>
				</select>
			<?}?>
			</span>

			</td></tr>
		<?}?>
	<?}?>


	<tr>
	<td>
	<input type=text class=DateFld  id="EndDate" name="EndDate" value="<?=$EndDate?>">
	<a href="javascript:;" onclick="ShowCalendar('EndDate');">
	<IMG SRC="<?=FileLink("images/icon_date.gif");?>"  WIDTH=18 HEIGHT=18 BORDER=0 ALT="" align=absmiddle>&nbsp;
	</a>
	&nbsp;<input type=submit value="<?=$Lang['Clear']?>" onclick="return DoRemove(this);">
	</td>
	</tr>
	</form>
<?}?>

</table>
</div>


</td></tr>
</table>


<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">

<div style="border-style:solid;border-width:1px;border-color:#c7c7c7">
<?=$SizePie->Dump();?>
</div>


<?include $nsTemplate->Inc("inc/footer");?>