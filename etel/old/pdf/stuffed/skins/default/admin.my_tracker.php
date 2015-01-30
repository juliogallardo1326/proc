<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<br>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">

<?if (count($UserReports)>0) {?>
<span style="color:#77B60B; font-size:12px;font-weight:bold;"><?=$Lang['MyReports']?></span>

<table class=ListTable>

<?for ($i=0;$i<count($UserReports);$i++) {
	$Row=$UserReports[$i];?>
	<tr>
	<td class=<?=$Row->_STYLE?>>
	
	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>
	<a href="<?=getURL($Row->Addr, "CpId=".$Row->CP_ID."&ConstId=".$Row->ID, "report")?>">
	<B><span style="font-size:10px;color:999999">(<?=$Row->CONST_TYPE?>)</span> 
	<?=$Row->NAME?></B>
	<?if ($nsUser->ADMIN) {?>
	 (<?=$Row->COMP_NAME?>)
	<?}?>
	</a>

	</td>
	<td width=200 nowrap>
	<?
	$nsButtons->Add("delete.gif", $Lang['DeleteFromMy'], getURL("my_tracker", "Mode=reports&MyDeleteId=".$Row->ID));
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>

	</td>
	</tr>


<?}?>
</table>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="40" BORDER="0" ALT="">

<?}?>

<?if (count($WatchVis)>0) {?>
<span style="color:#77B60B; font-size:12px;font-weight:bold;"><?=$Lang['MyVisitors']?></span>

<table class=ListTable>

<?for ($i=0;$i<count($WatchVis);$i++) {
	$Row=$WatchVis[$i];?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>


	<B>
	<a href="<?=getURL("visitor", "ViewId=".$Row->ID."&CpId=".$Row->COMPANY_ID, "admin")?>" title="<?=$Lang['VisitorInfo']?>">
	<?if ($Row->NAME) {?>
	<?=stripslashes($Row->NAME);?>
	<?}
	else {?>
	<?=$Lang['Visitor']?> <?=$Row->ID?> / <?=$Row->LAST_IP?>
	<?}?>
	</a>
	</B>
	<?if ($Row->DESCRIPTION) {?>
	<br><span class=ListDescr><?=stripslashes($Row->DESCRIPTION)?></span>
	<?}?>
	<?if ($Row->LAST_STAMP) {?>
	<br>
	<span style="font-size:9px;"><?=$Lang['LastVisit']?>: <?=date("Y-m-d H:i", $Row->LAST_STAMP)?>
	&nbsp;
	(<?=(($Row->DATE_DIFF==1||$Row->DATE_DIFF==="0")?"<B>":"")?><a href="<?=getURL("visitor_path", "VisId=".$Row->ID."&CpId=".$Row->COMPANY_ID."&AllClients=$AllClients&ViewDate=".date("Y-m-d", $Row->LAST_STAMP), "report")?>" title="<?=$Lang['ShowPaths']?>"><?=$Row->DATE_DIFF_NAME?></a></B>)</span>
	<?}?>

	<td  width=200 nowrap>
	<?
	$nsButtons->Add("delete.gif", $Lang['DeleteFromMy'], getURL("my_tracker", "Mode=visitors&MyDeleteId=".$Row->ID));
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>

	</td></tr>
<?}?>

</table>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="40" BORDER="0" ALT="">



<?}?>











<?if (count($WatchVisGrp)>0) {?>
<span style="color:#77B60B; font-size:12px;font-weight:bold;"><?=$Lang['MyVisitorGrps']?></span>

<table class=ListTable>

<?for ($i=0;$i<count($WatchVisGrp);$i++) {
	$Row=$WatchVisGrp[$i];?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>


	<a href="<?=getURL("visitor_grp", "ViewId=".$Row->ID."&CpId=".$Row->COMPANY_ID, "admin")?>" title="<?=$Lang['GrpInfo']?>">
	<B>
	<?=stripslashes($Row->NAME);?>
	</B></a>
	<?if ($Row->DESCRIPTION) {?>
	<br><span class=ListDescr><?=stripslashes($Row->DESCRIPTION)?></span>
	<?}?>
	<?if ($Row->LAST_STAMP) {?>
	<br>
	<span style="font-size:9px;"><?=$Lang['LastVisit']?>: <?=date("Y-m-d H:i", $Row->LAST_STAMP)?>
	&nbsp;
	(<?=(($Row->DATE_DIFF==1||$Row->DATE_DIFF==="0")?"<B>":"")?><a href="<?=getURL("visitor_path", "GrpId=".$Row->ID."&CpId=".$Row->COMPANY_ID."&AllClients=$AllClients&ViewDate=".date("Y-m-d", $Row->LAST_STAMP), "report")?>" title="<?=$Lang['ShowPaths']?>"><?=$Row->DATE_DIFF_NAME?></a></B>)</span>
	<?}?>

	<td   width=200 nowrap>
	<?
	$nsButtons->Add("delete.gif", $Lang['DeleteFromMy'], getURL("my_tracker", "Mode=visitor_grps&MyDeleteId=".$Row->ID));
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>

	</td></tr>
<?}?>

</table>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="40" BORDER="0" ALT="">


<?}?>






<?if (count($WatchActions)>0) {?>

<span style="color:#77B60B; font-size:12px;font-weight:bold;"><?=$Lang['MyActions']?></span>

<table class=ListTable>

<?for ($i=0;$i<count($WatchActions);$i++) {
	$Row=$WatchActions[$i];?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>


	<?if ($Row->LAST_STAMP) {?>
	<a href="<?=getURL("natural_constructor", "CpId=".$Row->COMPANY_ID."&ViewDate=".date("Y-m-d", $Row->LAST_STAMP)."&WhereArr[0][Mode]=Action&WhereArr[0][Id]=".$Row->ID."&WhereArr[0][OrderBy]=CNT&WhereArr[0][OrderTo]=DESC&GroupBy=Vis&ShowAll=1", "report")?>" title="<?=$Lang['ShowConst']?>">
	<?}?>
	<B><span style="font-size:10px;color:999999"><?=$Row->HOST?></span>&nbsp;
	<?=$Row->NAME?></B>
	<?if ($Row->LAST_STAMP) {?>
	<br>
	<span style="font-size:9px;"><?=$Lang['LastTimeUsed']?>: <?=date("Y-m-d H:i", $Row->LAST_STAMP)?>
	&nbsp;
	(<?=(($Row->DATE_DIFF==1||$Row->DATE_DIFF==="0")?"<B>":"")?><?=$Row->DATE_DIFF_NAME?></B>)</span></a>
	<?}?>

	<td  width=200 nowrap>
	<?
	$nsButtons->Add("delete.gif", $Lang['DeleteFromMy'], getURL("my_tracker", "Mode=actions&MyDeleteId=".$Row->ID));
	$nsButtons->Dump();
	?>
	</td></tr></table>

	</td></tr>
<?}?>

</table>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="40" BORDER="0" ALT="">


<?}?>




<?if (count($WatchActionItems)>0) {?>
<span style="color:#77B60B; font-size:12px;font-weight:bold;"><?=$Lang['MyActionItems']?></span>

<table class=ListTable>

<?for ($i=0;$i<count($WatchActionItems);$i++) {
	$Row=$WatchActionItems[$i];?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>



	<?if ($Row->LAST_STAMP) {?>
	<a href="<?=getURL("natural_constructor", "CpId=".$Row->COMPANY_ID."&ViewDate=".date("Y-m-d", $Row->LAST_STAMP)."&WhereArr[0][Mode]=ActionItem&WhereArr[0][Id]=".$Row->ID."&WhereArr[0][OrderBy]=CNT&WhereArr[0][OrderTo]=DESC&GroupBy=Vis&ShowAll=1", "report")?>" title="<?=$Lang['ShowConst']?>">
	<?}?>
	<B><?=$Row->NAME?></B>
	<?if ($Row->LAST_STAMP) {?>
	<br>
	<span style="font-size:9px;"><?=$Lang['LastTimeUsed']?>: <?=date("Y-m-d H:i", $Row->LAST_STAMP)?>
	&nbsp;
	(<?=(($Row->DATE_DIFF==1||$Row->DATE_DIFF==="0")?"<B>":"")?><?=$Row->DATE_DIFF_NAME?></B>)</span></a>
	<?}?>

	<td width=200 nowrap>
	<?
	$nsButtons->Add("delete.gif", $Lang['DeleteFromMy'], getURL("my_tracker", "Mode=action_items&MyDeleteId=".$Row->ID));
	$nsButtons->Dump();
	?>
	</td></tr></table>

	</td></tr>
<?}?>

</table>
<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="40" BORDER="0" ALT="">

<?}?>




<?if (count($WatchSaleItems)>0) {?>
<span style="color:#77B60B; font-size:12px;font-weight:bold;"><?=$Lang['MySaleItems']?></span>

<table class=ListTable>

<?for ($i=0;$i<count($WatchSaleItems);$i++) {
	$Row=$WatchSaleItems[$i];?>

	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	<td width=100%>


	<?if ($Row->LAST_STAMP) {?>
	<a href="<?=getURL("natural_constructor", "CpId=".$Row->COMPANY_ID."&ViewDate=".date("Y-m-d", $Row->LAST_STAMP)."&WhereArr[0][Mode]=Sale&WhereArr[0][Id]=".$Row->ID."&WhereArr[0][OrderBy]=CNT&WhereArr[0][OrderTo]=DESC&GroupBy=Vis&ShowAll=1", "report")?>" title="<?=$Lang['ShowConst']?>">
	<?}?>
	<B><?=$Row->NAME?></B>
	<?if ($Row->LAST_STAMP) {?>
	<br>
	<span style="font-size:9px;"><?=$Lang['LastTimeUsed']?>: <?=date("Y-m-d H:i", $Row->LAST_STAMP)?>
	&nbsp;
	(<?=(($Row->DATE_DIFF==1||$Row->DATE_DIFF==="0")?"<B>":"")?><?=$Row->DATE_DIFF_NAME?></B>)</span></a>
	<?}?>
 
	<td width=200 nowrap>
	<?
	$nsButtons->Add("delete.gif", $Lang['DeleteFromMy'], getURL("my_tracker", "Mode=sale_items&MyDeleteId=".$Row->ID));
	$nsButtons->Dump();
	?>
	</td></tr></table>
	

	</td></tr>
<?}?>

</table>

<?}?>

<?include $nsTemplate->Inc("inc/footer");?>