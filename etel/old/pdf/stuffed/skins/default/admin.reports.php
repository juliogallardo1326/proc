<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<div class=FormDiv>
<table width=100%>
<tr><td class=FormHeader>

<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td width=50%>
<?if (count($SitesArr)>1) {?>
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
<?}?>
</td><td width=50% align=right>

<?GetFORM();?>
<input type=hidden name="CpId" value="<?=$CpId?>">
<input type=hidden name="SiteId" value="<?=$SiteId?>">
<span style="color:#000000">
<B><?=$Lang['OneVisPath']?>:</B>&nbsp;&nbsp;
<?=$Lang['TypeIP']?>&nbsp;<input type=text size=20 name=IP>
&nbsp;</span>
<input type=submit value="<?=$Lang['Show']?>">
</form>

</td></tr>
</table>

</td></tr>
</table>
</div>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="20" BORDER="0" ALT="">

<table  cellpadding=6 cellspacing=0 border=0 width=100%>


<tr>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Hits&CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_hits.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Hits&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['LogHits']?></a></span><br>
<?=$Lang['LogHitsDescr']?>
</td>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Clicks&CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_clicks.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Clicks&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['LogClicks']?></a></span><br>
<?=$Lang['LogClicksDescr']?>
</td></tr>


<tr>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Actions&CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_actions.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Actions&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['LogActions']?></a></span><br>
<?=$Lang['LogActionsDescr']?>
</td>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Sales&CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_sales.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Sales&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['LogSales']?></a></span><br>
<?=$Lang['LogSalesDescr']?>
</td></tr>



<tr>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Split&CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_splits.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Split&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['LogSplits']?></a></span><br>
<?=$Lang['LogSplitsDescr']?>
</td>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Ref&CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_refs.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Ref&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['LogRefs']?></a></span><br>
<?=$Lang['LogRefsDescr']?>
</td></tr>


<tr>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Key&CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_keys.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Key&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['LogKeys']?></a></span><br>
<?=$Lang['LogKeysDescr']?>)
</td>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Undef&CpId=$CpId&SiteId=$SiteId", "report")?>"<IMG SRC="<?=FileLink("images/logs_undef.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Undef&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['LogUndef']?></a></span><br>
<?=$Lang['LogUndefDescr']?>
</td></tr>


<tr>
<?if (!$Settings['All']->FRAUD_ENABLE) {?>
<td valign=top><p><a href="<?=getURL("compare", "CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_compare.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("compare", "CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['Compare']?></a></span><br>
<?=$Lang['CompareDescr']?>
</td>	

<?} else {?>
<td valign=top><p><a href="<?=getURL("logs", "Mode=Fraud&CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_fraud.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("logs", "Mode=Fraud&CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['ClickFraud']?></a></span><br>
<?=$Lang['ClickFraudDescr']?>
</td>
<?}?>
<td valign=top><p><a href="<?=getURL("visitor_path", "CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_paths.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("visitor_path", "CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['VisPaths']?></a></span><br>
<?=$Lang['VisPathsDescr']?>
</td></tr>


<?if ($Settings['All']->FRAUD_ENABLE) {?>
<tr>
<td valign=top><p><a href="<?=getURL("compare", "CpId=$CpId&SiteId=$SiteId", "report")?>"><IMG SRC="<?=FileLink("images/logs_compare.gif");?>" WIDTH="32" align=middle hspace=5 vspace=3 HEIGHT="28" BORDER="0" ALT=""></a></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
<a href="<?=getURL("compare", "CpId=$CpId&SiteId=$SiteId", "report")?>"><?=$Lang['Compare']?></a></span><br>
<?=$Lang['CompareDescr']?>
</td>
<td valign=top><p></p></td>
<td  width="50%" valign="top" style="border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#E6E6E6;"><span class=SectionName>
</td></tr>
<?}?>

</table>




<?include $nsTemplate->Inc("inc/footer");?>