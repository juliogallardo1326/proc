<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<?require_once SELF."/lib/calendar.func.php"?>


<div class=FormDiv>
<table width=100% cellpadding=6>

<?if (count($SitesArr)>1) {?>
<tr><td class=FormHeader>
<?GetFORM();?>
<input type=hidden name="CpId" value="<?=$CpId?>">
<span style="color:#000000;font-weight:bold;"><?=$Lang['ChooseSite']?>:&nbsp;</span>
<select name=SiteId>
<option style="color:#999999" value=0><?=$Lang['AllSites']?></option>
<?for ($i=0;$i<count($SitesArr);$i++) {?>
	<option value=<?=$SitesArr[$i]->ID?> <?=(($SitesArr[$i]->ID==$SiteId)?"selected":"")?>><?=$SitesArr[$i]->HOST?></option>
<?}?>
</select>&nbsp;
<input type=submit value="<?=$Lang['Choose']?>">
</form>
</td></tr>
<?}?>


<tr><td>
<?GetFORM();?>
<input type=hidden name="FormClicked" value="1">
<input type=hidden name=CpId value="<?=$CpId?>">
<input type=hidden name=SiteId value="<?=$SiteId?>">

<?=$Lang['Date']?>:&nbsp;<input type=text size=10 id="ViewDate" name="ViewDate" value="<?=$ViewDate?>" class=DateFld ondblclick="this.value=''">
<a href="javascript:;" onclick="ShowCalendar('ViewDate');">
<IMG SRC="<?=FileLink("images/icon_date.gif");?>"  WIDTH=18 HEIGHT=18 BORDER=0 ALT="" align=absmiddle>&nbsp;
</a>

&nbsp;&nbsp;|&nbsp;&nbsp;
<?=$Lang['Period']?>:&nbsp;
<input type=text size=10 id="StartDate" name="StartDate" value="<?=$StartDate?>" class=DateFld ondblclick="this.value=''">
<a href="javascript:;" onclick="ShowCalendar('StartDate');">
<IMG SRC="<?=FileLink("images/icon_date.gif");?>"  WIDTH=18 HEIGHT=18 BORDER=0 ALT="" align=absmiddle>&nbsp;
</a>&nbsp;—&nbsp;
<input type=text size=10 id="EndDate" name="EndDate" value="<?=$EndDate?>" class=DateFld ondblclick="this.value=''">
<a href="javascript:;" onclick="ShowCalendar('EndDate');">
<IMG SRC="<?=FileLink("images/icon_date.gif");?>"  WIDTH=18 HEIGHT=18 BORDER=0 ALT="" align=absmiddle>&nbsp;
</a>
&nbsp;
<input type=submit value="<?=$Lang['Refresh']?>">

</form>
</td></tr>

</table>
</div>


<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">



<table border="0" cellpadding="0" cellspacing="0" width=100%>


<tr height=30>
<td  class=ReportHeaderTd2 style="padding:5px;"><p class=ReportHeaderName><?=$Lang['VisTotal']?></td>
<td  class=ReportHeaderTd2 style="padding:5px;"><p class=ReportHeaderName><?=$Lang['VisWRef']?></td>
<td  class=ReportHeaderTd2 style="padding:5px;"><p class=ReportHeaderName><?=$Lang['VisNoRef']?></td>
<td  class=ReportHeaderTd2 style="padding:5px;"><p class=ReportHeaderName><?=$Lang['VisKey']?></td>
<td  class=ReportHeaderTd2 style="padding:5px;"><p class=ReportHeaderName><?=$Lang['VisPaid']?></td>
<td  class=ReportHeaderTd2 style="padding:5px;"><p class=ReportHeaderName><?=$Lang['VisAction']?></td>
<td  class=ReportHeaderTd2 style="padding:5px;"><p class=ReportHeaderName><?=$Lang['VisSale']?></td>
</tr>

<tr><td colspan=7><p>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="2" BORDER="0" ALT="" style="background:#E1E1E1">
</p></td></tr>

<tr>
<td class=ReportSimpleTd><B style="color:#000000"><?=$TotalCnt?></B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$RefCnt?></B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$NoRefCnt?></B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$KeyCnt?></B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$ClickCnt?></B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$ActionCnt?></B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$SaleCnt?></B></td>
</tr>

<tr><td colspan=7><p>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#E1E1E1">
</p></td></tr>


<tr>
<td class=ReportSimpleTd><B style="color:#000000"><?=$TotalCntPerc?>%</B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$RefCntPerc?>%</B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$NoRefCntPerc?>%</B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$KeyCntPerc?>%</B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$ClickCntPerc?>%</B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$ActionCntPerc?>%</B></td>
<td class=ReportSimpleTd><B style="color:#000000"><?=$SaleCntPerc?>%</B></td>
</tr>

<tr><td colspan=7><p>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="100%" HEIGHT="1" BORDER="0" ALT="" style="background:#E1E1E1">
</p></td></tr>



</table>



<?include $nsTemplate->Inc("inc/footer");?>