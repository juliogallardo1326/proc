<div class=ListDiv>
<table class=FormTable>
<tr><td class=ReportSimpleTd>
<?for ($i=0;$i<count($PathArr);$i++) {
	if ($i>0) echo "&nbsp;&raquo;&nbsp;";?>
	<a href="<?=getURL("incampaign", "CampId=".$PathArr[$i]->ID, "admin")?>"><?=ToUpper(stripslashes($PathArr[$i]->NAME))?></a>
<?}?>
</td></tr>
</table>
</div>