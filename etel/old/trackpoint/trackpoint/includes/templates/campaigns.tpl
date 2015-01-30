<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_CampaignStats%%</td>
	</tr>
	<tr>
		<td class=body><br>%%LNG_Help_Campaigns%%</td>
	</tr>
	<tr>
		<td class=body><br><input type=button value="%%LNG_CreateCampaign%%" class=smallbutton onClick="document.location = 'index.php?Page=CreateCampaign'"></td>
	</tr>
	<tr>
		<td class=body><br>
			<TABLE id="Table1" cellSpacing="0" cellPadding="0" width="100%" border="0">
				<TR>
					<TD vAlign="top" width="">
						<table border=0 cellspacing=0 cellpadding=0 style="width:100%">
							<tr>
								<td valign=bottom>
									%%TPL_Calendar%%
								</td>
								<td width=100% valign=bottom>
									%%TPL_Paging%%
								</td>
							</tr>
						</table>
						<table border=0 cellspacing="1" cellpadding="2" width=100% class=text>
							<tr class=heading3>
								<td style="width:20px;">&nbsp;</td>
								<td style="width:40%">%%LNG_WebSite%%&nbsp;<a href='index.php?Page=Campaigns&SortBy=Site&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Campaigns&SortBy=Site&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Visits%%&nbsp;<a href='index.php?Page=Campaigns&SortBy=Visits&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Campaigns&SortBy=Visits&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Conv%%&nbsp;<a href='index.php?Page=Campaigns&SortBy=Conv&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Campaigns&SortBy=Conv&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">(%) %%LNG_Conv%%&nbsp;<a href='index.php?Page=Campaigns&SortBy=Percent&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Campaigns&SortBy=Percent&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Cost%%</td>
								<td style="width:10%">%%LNG_Revenue%%&nbsp;<a href='index.php?Page=Campaigns&SortBy=Revenue&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Campaigns&SortBy=Revenue&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td>%%LNG_ROI%%</td>
							</tr>
						</table>
						%%TPL_CampaignsRows%%
						%%TPL_Paging_Bottom%%
						%%TPL_CampaignsFooter%%
					</td>
				</TR>
			</TABLE>
			<br><br>&nbsp;&nbsp;* %%LNG_HoldMouseOver%%
		</td>
	</tr>
</table>
