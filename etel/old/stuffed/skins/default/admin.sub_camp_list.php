<?if (isset($InCampArr)&&is_array($InCampArr)) {?>



<table class=ListTable>

<?for ($i=0;$i<count($InCampArr);$i++) {
	$Row=$InCampArr[$i];?>



	<tr>
	<td class=<?=$Row->_STYLE?>>
	<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
	
	
	<td width=100%>
	<a href="<?=$Row->_STAT_LINK?>">
	<B><span style="font-size:10px;color:999999">(<?=$Row->_TYPE?>)</span> <?=$Row->NAME?></B>
	<?if ($Row->DESCRIPTION) {?>
	<br>
	<span class="ListDescr"><?=$Row->DESCRIPTION?></span>
	<?}?>

	<?if (ValidId($Row->SUB_CAMP)) {?>
		<br>
		
		<?if ($nsUser->Columns->ROI) {?>
		<?=$Lang['ROI']?>: <span class="<?=($Row->Report->GoodROI($Row->CampStat['ROI']))?"GoodROI":"BadROI"?>">
		<?=$Row->CampStat['ROI']?>%</span>, 
		<?}?>

		<?if ($nsUser->Columns->CONVERSIONS) {?>
		<?=$Lang['ActionsConv']?>: <?=$Row->CampStat['ActionConv']?>%, 
		<?=$Lang['SalesConv']?>: <?=$Row->CampStat['SaleConv']?>%
		<?}?>
	<?}?>

	<?if (ValidId($Row->SPLIT_TEST)) {?>
		<br>
		<?if ($nsUser->Columns->CLICKS) {?>
		<?=$Lang['Clicks']?>: <?=$Row->SplitStat['UniClick']?>, 
		<?}?>

		<?if ($nsUser->Columns->CONVERSIONS) {?>
		<?=$Lang['ActionsConv']?>: <?=$Row->SplitStat['ActionConv']?>%, 
		<?=$Lang['SalesConv']?>: <?=$Row->SplitStat['SaleConv']?>%
		<?}?>
	<?}?>
	</a>
	</td>
	
		
	<td class=ListRowLeft>
	<?
	$nsButtons->PostName=true;
	$nsButtons->Add("icon_link.gif", $Lang['CodeGen'], $Row->_CODELINK);
	$nsButtons->Add("edit.gif", $Lang['Edit'], $Row->_EDITLINK);
	$nsButtons->Add("delete.gif", $Lang['Delete'], $Row->_DELETELINK, $Lang['YouSure']);
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>
	
	</td></tr>


<?}?>
</table>

<?}?>

<?if (ValidArr($PathArr)&&count($PathArr)>1) {
	include $nsTemplate->Inc("inc/grp_path");
}?>