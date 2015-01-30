<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%GLOBAL_HeadingDescription%%&nbsp;(<span title="%%GLOBAL_TopHeading%%">%%GLOBAL_TopHeading_Truncate%%</span>)&nbsp;&nbsp;<span title="%%GLOBAL_TopHeading_Detail%%">%%GLOBAL_TopHeading_Detail_Truncate%%</span></td>
	</tr>
	<tr>
		<td class=body><br>%%GLOBAL_Help%%</td>
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
								<td style="width:55%">&nbsp;&nbsp;%%GLOBAL_Title%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=%%GLOBAL_Sort%%&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=%%GLOBAL_Sort%%&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Visits%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Visits&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Visits&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Conv%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Conv&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Conv&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">(%) %%LNG_Conv%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Percent&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Percent&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td style="">%%LNG_Revenue%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Revenue&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Revenue&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
							</tr>
							%%TPL_LandingPagesRows_Header%%
							%%TPL_LandingPagesRows%%
						</table>
						%%TPL_Paging_Bottom%%
						%%TPL_LandingPageResultsFooter%%
					</td>
				</TR>
			</TABLE>
			<br><br>&nbsp;&nbsp;* %%LNG_HoldMouseOver%%
		</td>
	</tr>
</table>
