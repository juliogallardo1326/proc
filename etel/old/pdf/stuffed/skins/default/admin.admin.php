<?$PageTitle=$Lang['Title']?>
<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>


<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td width=18%>

</td><td width=82%>


	<table  cellpadding=6 cellspacing=0 border=0 width=100%>
	<?if ($nsUser->ADMIN||($nsUser->MERCHANT&&$nsUser->SUPER_USER)) {?>
	<tr>
	<td valign=top width=32><p><IMG SRC="<?=FileLink("images/admin_account.gif");?>" WIDTH="32" align=middle vspace=2 hspace=5 HEIGHT="24" BORDER="0" ALT=""></p></td>
	<td  width="100%" valign="top"><span class=SectionName><?=$Lang['AdmAcc']?></span>
	
	<table width=100% cellpadding=0 cellspacing=0 border=0 class=SectionTable>

	<?if ($nsUser->ADMIN&&$nsProduct->LICENSE==3) {?>
	<tr><td class=SectionTd>
	<a href="<?=getURL("company", "", "admin")?>"><?=$Lang['AdmClnt']?></a>
	</td></tr>
	<?}?>
	
	<?if ($nsUser->MERCHANT||$nsProduct->LICENSE!=3) {?>
	<tr><td class=SectionTd>
	<a href="<?=getURL("company", "EditId=".$nsUser->COMPANY_ID, "admin")?>"><?=$Lang['ClientEdit']?></a>
	</td></tr>
	<?}?>

	<?if ($nsUser->ADMIN||($nsUser->MERCHANT&&$nsUser->SUPER_USER)) {?>
	<tr><td class=SectionTd>
	<a href="<?=getURL("users", "", "admin")?>"><?=$Lang['AdmUsers']?></a>
	</td></tr>
	<?}?>

	<?if ($nsUser->ADMIN&&$nsUser->SUPER_ADMIN&&$nsProduct->LICENSE==3) {?>
	<tr><td class=SectionTd>
	<a href="<?=getURL("agents", "", "admin")?>"><?=$Lang['AdmAgents']?></a>
	</td></tr>
	<?}?>

	</table>

	</td>
	</tr>
	<?}?>


	<?if (($nsProduct->LICENSE!=3&&$nsUser->SUPER_USER)||$nsUser->ADMIN) {?>
	<tr>
	<td valign=top width=32><p><IMG SRC="<?=FileLink("images/admin_db.gif");?>" WIDTH="32" align=middle vspace=2 hspace=5 HEIGHT="24" BORDER="0" ALT=""></p></td>
	<td  width="100%" valign="top"><span class=SectionName><?=$Lang['BaseStruct']?></span>

	<table width=100% cellpadding=0 cellspacing=0 border=0 class=SectionTable>

	<tr><td class=SectionTd>
	<a href="<?=getURL("natural_host", "", "admin")?>"><?=$Lang['NaturalHosts']?></a>
	</td></tr>

	<tr><td class=SectionTd>
	<a href="<?=getURL("user_agent", "", "admin")?>"><?=$Lang['UserAgents']?></a>
	</td></tr>

	<tr><td class=SectionTd>
	<a href="<?=getURL("base_stat", "", "admin")?>"><?=$Lang['Database']?></a>
	</td></tr>
	
	<tr><td class=SectionTd>
	<a href="<?=getURL("ip_ignore", "", "admin")?>"><?=$Lang['IpIgnore']?></a>
	</td></tr>	
	
	<tr><td class=SectionTd>
	<a href="<?=getURL("countries", "", "admin")?>"><?=$Lang['ImportCountries']?></a>
	</td></tr>	

	</table>
	</td></tr>
	<?}?>

	<?if ($nsUser->ADMIN&&$nsProduct->LICENSE==3) {?>
	<tr>
	<td valign=top width=32><p><IMG SRC="<?=FileLink("images/admin_track.gif");?>" WIDTH="32" align=middle vspace=2 hspace=5 HEIGHT="24" BORDER="0" ALT=""></p></td>
	<td  width="100%" valign="top"><span class=SectionName><?=$Lang['TrackSets']?></span>

	<table width=100% cellpadding=0 cellspacing=0 border=0 class=SectionTable>

	<tr><td class=SectionTd>
	<a href="<?=getURL("group_order", "Mode=NATURAL", "admin")?>"><?=$Lang['NaturalGroupOrder']?></a>
	</td></tr>

	<tr><td class=SectionTd>
	<a href="<?=getURL("group_order", "Mode=PAID", "admin")?>"><?=$Lang['PaidGroupOrder']?></a>
	</td></tr>

	<tr><td class=SectionTd>
	<a href="<?=getURL("stat_config","","admin")?>"><?=$Lang['CatchSets']?></a>
	</td></tr>

	<tr><td class=SectionTd>
	<a href="<?=getURL("stat_dbl_click","","admin")?>"><?=$Lang['DblClickSets']?></a>
	</td></tr>

	</table>

	</td></tr>
	<?}?>

	<?if ($nsUser->ADMIN||($nsUser->SUPER_USER&&$nsProduct->LICENSE!=3)) {?>
	<tr>
	<td valign=top width=32><p><IMG SRC="<?=FileLink("images/admin_general.gif");?>" WIDTH="32" align=middle hspace=5 HEIGHT="28" BORDER="0" ALT=""></p></td>
	<td  width="100%" valign="top"><span class=SectionName><?=$Lang['ProgramSets']?></span>
	
	<table width=100% cellpadding=0 cellspacing=0 border=0 class=SectionTable>

	<tr><td class=SectionTd>
	<a href="<?=getURL("misc_config","","admin")?>"><?=$Lang['GeneralSets']?></a>
	</td></tr>

	<tr><td class=SectionTd>
	<a href="<?=getURL("license","","admin")?>"><?=$Lang['LicenseKeys']?></a>
	</td></tr>

	<?if ($UpdatesAvailable>0) {?>
		<tr><td class=SectionTd>
		<a href="<?=getURL("update","","admin")?>"><?=$Lang['Updates']?></a>
		</td></tr>
	<?}?>

		<tr><td class=SectionTd>
		<a href="<?=getURL("versioncheck","","admin")?>"><?=$Lang['CheckUpdates']?></a>
		</td></tr>

	</table>

	</td></tr>
	<?}?>

	</table>


	</td>
	</tr>
</table>



<?include $nsTemplate->Inc("inc/footer");?>