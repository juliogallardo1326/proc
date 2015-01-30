<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_SearchStatsKeyword%%</td>
	</tr>
	<tr>
		<td class=body><br>%%LNG_Help_Search%%</td>
	</tr>
	<tr>
		<td class=body>
			<br><input type=button value="%%LNG_ViewByEngines%%" class=smallbutton onClick="document.location = 'index.php?Page=Engines'">
		</td>
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
								<td style="width:20px;"></td>
								<td style="width:45%">&nbsp;&nbsp;%%LNG_SearchKeywords%%&nbsp;<a href='index.php?Page=Search&SortBy=Keywords&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Search&SortBy=Keywords&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Visits%%&nbsp;<a href='index.php?Page=Search&SortBy=Visits&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Search&SortBy=Visits&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Conv%%&nbsp;<a href='index.php?Page=Search&SortBy=Conv&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Search&SortBy=Conv&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">(%) %%LNG_Conv%%&nbsp;<a href='index.php?Page=Search&SortBy=Percent&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Search&SortBy=Percent&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Revenue%%&nbsp;<a href='index.php?Page=Search&SortBy=Revenue&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=Search&SortBy=Revenue&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td>%%LNG_Action%%&nbsp;</td>
							</tr>
						</table>
						%%TPL_SearchRows%%
					%%TPL_Paging_Bottom%%
					%%TPL_SearchResultsFooter%%
					</td>
				</TR>
			</TABLE>
			<br><br>&nbsp;&nbsp;* %%LNG_HoldMouseOver%%
		</td>
	</tr>
</table>
