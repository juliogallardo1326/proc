<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%GLOBAL_ViewAllTitle%%</td>
	</tr>

	<tr>
		<td class=body><br>%%GLOBAL_Help_Intro%%</td>
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
								<td style="width:45%">&nbsp;&nbsp;%%GLOBAL_Title%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=%%GLOBAL_Sort%%&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=%%GLOBAL_Sort%%&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Visits%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Visits&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Visits&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Conv%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Conv&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Conv&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">(%) %%LNG_Conv%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Conv&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Conv&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_Revenue%%&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Revenue&Sort=Up&%%GLOBAL_SearchDetails%%'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=%%PAGE%%&SortBy=Revenue&Sort=Down&%%GLOBAL_SearchDetails%%'><img src="images/sortdown.gif" border=0></a></td>
								<td>%%LNG_Action%%&nbsp;</td>
							</tr>
							%%TPL_ViewAllRows_Header%%
							%%TPL_ViewAllRows%%
						</table>
						%%TPL_Paging_Bottom%%
						%%TPL_SearchResultsFooter%%
					</td>
				</TR>
			</TABLE>
			<br><br>&nbsp;&nbsp;* %%LNG_HoldMouseOver%%
		</td>
	</tr>
</table>
