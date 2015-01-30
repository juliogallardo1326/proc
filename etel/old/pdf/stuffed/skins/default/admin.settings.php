<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td width=18%>

</td><td width=82%>

	<table  cellpadding=6 cellspacing=0 border=0 width=100%>
	<tr>
	<td valign=top width=32><p><IMG SRC="<?=FileLink("images/admin_track.gif");?>" WIDTH="32" align=middle vspace=2 hspace=5 HEIGHT="24" BORDER="0" ALT=""></p></td>
	<td  width="100%" valign="top"><span class=SectionName><?=$Lang['Title2']?></span>
	
<table width=100% cellpadding=0 cellspacing=0 border=0 class=SectionTable>

<tr><td class=SectionTd>
<a href="<?=getURL("group_order", "Mode=NATURAL&CompanyId=$CpId", "admin")?>"><?=$Lang['NaturalOrder']?></a>
</td></tr>

<tr><td class=SectionTd>
<a href="<?=getURL("group_order", "Mode=PAID&CompanyId=$CpId", "admin")?>"><?=$Lang['PaidOrder']?></a>
</td></tr>

<tr><td class=SectionTd>
<a href="<?=getURL("stat_config","CpId=$CpId","admin")?>"><?=$Lang['CatchSettings']?></a>
</td></tr>

<tr><td class=SectionTd>
<a href="<?=getURL("stat_dbl_click","CpId=$CpId","admin")?>"><?=$Lang['DblClickSets']?></a>
</td></tr>


<tr><td class=SectionTd>
<a href="<?=getURL("campaign_link","","admin")?>"><?=$Lang['GenLinks']?></a>
</td></tr>


<tr><td class=SectionTd>
<a href="<?=getURL("get_code","CpId=$CpId","admin")?>"><?=$Lang['GetCode']?></a>
</td></tr>




</table>

	</td>
	</tr>
</table>


	</td>
	</tr>
</table>


<?include $nsTemplate->Inc("inc/footer");?>