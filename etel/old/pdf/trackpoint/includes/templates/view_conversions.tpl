<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_ConversionStats%%</td>
	</tr>
	<tr>
		<td class=body><br>%%LNG_Help_ConversionStats%%</td>
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
								<td style="width:15%">%%LNG_ConversionTime%%&nbsp;<a href='index.php?Page=View_Conversions&SortBy=OrderTime&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=View_Conversions&SortBy=OrderTime&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_ConversionName%%</td>
								<td style="width:10%">%%LNG_Revenue%%&nbsp;<a href='index.php?Page=View_Conversions&SortBy=Revenue&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=View_Conversions&SortBy=Revenue&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_ConversionType%%&nbsp;<a href='index.php?Page=View_Conversions&SortBy=Type&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=View_Conversions&SortBy=Type&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:15%">%%LNG_ConversionOrigin%%&nbsp;<a href='index.php?Page=View_Conversions&SortBy=Origin&Sort=Up'><img src="images/sortup.gif" border=0></a>&nbsp;<a href='index.php?Page=View_Conversions&SortBy=Origin&Sort=Down'><img src="images/sortdown.gif" border=0></a></td>
								<td style="width:10%">%%LNG_IPAddress%%&nbsp;</td>
								<td>%%LNG_ConversionDetails%%&nbsp;</td>
							</tr>
						</table>
						%%TPL_ViewConversions_Rows%%
						%%TPL_Paging_Bottom%%
						%%TPL_ConversionFooter%%
					</td>
				</TR>
			</TABLE>
		</td>
	</tr>
</table>
