<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_PPCStats%%</td>
	</tr>
	<tr>
		<td class=body><br>%%LNG_Help_PPC%%</td>
	</tr>
	<tr><td class=body><br><input type=button value="%%LNG_CreatePPC%%" class=smallbutton onClick="document.location = 'index.php?Page=CreatePPC'"></td></tr>
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
								<td style="width:40%">%%LNG_PPCName%%&nbsp;<a href='index.php?Page=PPC&SortBy=PPC&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=PPC&SortBy=PPC&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Visits%%&nbsp;<a href='index.php?Page=PPC&SortBy=Visits&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=PPC&SortBy=Visits&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Conv%%&nbsp;<a href='index.php?Page=PPC&SortBy=Conv&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=PPC&SortBy=Conv&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">(%) %%LNG_Conv%%&nbsp;<a href='index.php?Page=PPC&SortBy=Percent&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=PPC&SortBy=Percent&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Cost%%&nbsp;<a href='index.php?Page=PPC&SortBy=Cost&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=PPC&SortBy=Cost&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Revenue%%&nbsp;<a href='index.php?Page=PPC&SortBy=Revenue&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=PPC&SortBy=Revenue&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td>%%LNG_ROI%%&nbsp;<a href='index.php?Page=PPC&SortBy=ROI&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=PPC&SortBy=ROI&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
							</tr>
						</table>
						%%TPL_PPCRows%%
						%%TPL_Paging_Bottom%%
						%%TPL_PPCFooter%%
					</td>
				</TR>
			</TABLE>
			<br><br>&nbsp;&nbsp;* %%LNG_HoldMouseOver%%
		</td>
	</tr>
</table>
