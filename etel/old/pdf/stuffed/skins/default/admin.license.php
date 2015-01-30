<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<?if (count($LicenseArr)>0) {?>


<table class=ListTable>
<?for ($i=0;$i<count($LicenseArr);$i++) {
	$Row=$LicenseArr[$i];?>
	<tr>
	<td style='<?=(($Row->DISABLED)?"background:#EEEEEE":"")?>' class=<?=$Row->_STYLE?>>


	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<td nowrap style="padding-right:10px;" width=100>
	<?if ($Row->DISABLED) {?><B style="color:#FF0000"><?=$Lang['Useless']?></B><br><?}?>
	<?if ($Row->AGENT) {?><B style="color:#669900"><?=$Lang['Agency']?></B><br><?}?>
	<?if ($Row->WL) {?><B style="color:#ffffff;background:#666666;background-color:#666666; border-width:1px;border-color:#B7B7B7;border-style:solid;">&nbsp;<?=$Lang['WL']?>&nbsp;</B><br><?}?>
	<?if ($Row->CLIENT) {?><B style="color:#669900"><?=$Lang['Merchant']?></B><br><?}?>
	<?if ($Row->ADD) {?><span style="color:#669900"><?=$Lang['Additional']?></span><br><?}?>
	</td>

	<td width=100%>
	<p <?=(($Row->DISABLED)?"disabled readonly":"")?>>
	<?
	foreach ($Row->License as $Key=>$Value) {?>
		<?if (isset($KeyVars[$Key])) {?>
		<?=$KeyVars[$Key]?>: 
		<B><?=$Value?></B><br>
		<?}?>
	<?}?>
	</p>
	</td>
	<td>
	<?
	$nsButtons->Add("delete.gif", $Lang['Delete'], getURL("license", "DeleteId=".$Row->ID), $Lang['YouSure']);
	$nsButtons->Dump();
	?>
	</td>
	</tr></table>
	
	</td></tr>
<?}?>


<tr><td class=ReportSimpleTd2>
<?if ($ClientsCnt>0) {?>
<?=$Lang['MaxClientsCnt']?> <B><?=$ClientsCnt?></B> (<?=$Lang['Now']?> <B><?=$ClientsCntNow?></B>)<br>
<?=$Lang['SitesUnlimit']?>
<?}
else {?>
<?=$Lang['MaxSitesCnt']?> <B><?=$SitesCnt?></B> (<?=$Lang['Now']?> <B><?=$SitesCntNow?></B>)<br>
<?}?>
</td></tr>

</table>


<br>
<?}
else include $nsTemplate->Inc("inc/no_records");?>




<?include $nsTemplate->Inc("inc/footer");?>