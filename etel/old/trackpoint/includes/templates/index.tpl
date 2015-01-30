<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_ControlPanel%%</td>
	</tr>
	<tr>
		<td class="body">
			<TABLE id="Table1" cellSpacing="0" cellPadding="0" width="100%" border="0">
				<tr><td colspan="4">&nbsp;</td></tr>
				<TR>
					<TD vAlign="top" width="">
						<table border=0 width=100% cellspacing=0 cellpadding=0>
						<tr><td vAlign="top" width=100%>
						<DIV class="MidHeading"><img src="images/statsicon.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_TotalTrafficSummary%%</DIV>
						<UL class="Text"> 
							<LI>%%LNG_TotalVisits%%: %%GLOBAL_TotalVisits%%</li>
							<LI>%%LNG_TotalConversions%%: %%GLOBAL_TotalConversions%%</li>
							<LI>(%) %%LNG_Conversions%%: %%GLOBAL_TotalConversionsPercent%% %</li>
							<LI>%%LNG_Revenue%%: %%LNG_CurrencySymbol%% %%GLOBAL_TotalRevenue%%</li>
							<LI>(%) %%LNG_ROI%%: %%GLOBAL_TotalROI%% %</span></li>
						</UL>
						<DIV class="MidHeading"><img src="images/charticon.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_ChartType%%</DIV>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="ChangeGraph" name="ChangeGraph" class="Text" onchange="UpdateGraph();">
								%%GLOBAL_ChangeGraph%%
							</select>
						</TD>
						<td>%%GLOBAL_Chart%%</td>
						</tr>
						</table>

						<hr style="width:95%; height:1px; color:#D6D3CE;"><br>

						<table border=0 width=95% class=text>
						<tr class=heading3>
							<td>&nbsp;</td>
							<td>
							<span class=helpText onMouseOut="HideHelp('visits');" onMouseOver="ShowHelp('visits', '%%LNG_Visits%%', '%%LNG_Visits_helpText%%');">%%LNG_Visits%%</span><div style="display:none" id="visits"></div>
							</td>
							<td>
							<span class=helpText onMouseOut="HideHelp('visits_percent');" onMouseOver="ShowHelp('visits_percent', '(%) %%LNG_Visits%%', '%%LNG_Visits_percent_helpText%%');">(%) %%LNG_Visits%%</span><div style="display:none" id="visits_percent"></div>
							</td>
							
							<td>
							<span class=helpText onMouseOut="HideHelp('conv');" onMouseOver="ShowHelp('conv', '%%LNG_Conv%%', '%%LNG_Conv_helpText%%');">%%LNG_Conv%%</span><div style="display:none" id="conv"></div>
							</td>
							<td>
							<span class=helpText onMouseOut="HideHelp('conv_percent');" onMouseOver="ShowHelp('conv_percent', '(%) %%LNG_Conv%%', '%%LNG_Conv_percent_helpText%%');">(%) %%LNG_Conv%%</span><div style="display:none" id="conv_percent"></div>
							</td>

							<td>
							<span class=helpText onMouseOut="HideHelp('revenue');" onMouseOver="ShowHelp('revenue', '%%LNG_Revenue%%', '%%LNG_Revenue_helpText%%');">%%LNG_Revenue%%</span><div style="display:none" id="revenue"></div>
							</td>
							<td>
							<span class=helpText onMouseOut="HideHelp('roi');" onMouseOver="ShowHelp('roi', '							(%) %%LNG_ROI%%', '%%LNG_ROI_helpText%%');">(%) %%LNG_ROI%%</span><div style="display:none" id="roi"></div>
							</td>
							<td>%%LNG_Action%%</td></tr>
						<tr class="gridrow" onmouseover="this.className='gridrowover';" onmouseout="this.className='gridrow';">
						<TD>
							<img src="images/campaignicon.gif" width="20" height="20" align="absMiddle">&nbsp;<b>%%LNG_Campaigns%%</b>
						</TD>
						<td>%%GLOBAL_CampaignVisits%%</td>
						<td>%%GLOBAL_CampaignVisits_Percent%% %</td>
						<td>%%GLOBAL_CampaignConversions%%</td>
						<td>%%GLOBAL_CampaignConversions_Percent%% %</td>
						<td>%%LNG_CurrencySymbol%% %%GLOBAL_CampaignRevenue%%</td>
						<td>%%GLOBAL_CampaignROI%% %</td>
						<td><a href="index.php?Page=Campaigns">%%LNG_View%%</a></td>
						</tr>
						<tr class="gridrow" onmouseover="this.className='gridrowover';" onmouseout="this.className='gridrow';">
						<TD>
							<img src="images/ppcicon.gif" width="20" height="20" align="absMiddle">&nbsp;<b>%%LNG_PayPerClick%%</b>
						</TD>
						<td>%%GLOBAL_PPCVisits%%</td>
						<td>%%GLOBAL_PPCVisits_Percent%% %</td>
						<td>%%GLOBAL_PPCConversions%%</td>
						<td>%%GLOBAL_PPCConversions_Percent%% %</td>
						<td>%%LNG_CurrencySymbol%% %%GLOBAL_PPCRevenue%%</td>
						<td>%%GLOBAL_PPCROI%% %</td>
						<td><a href="index.php?Page=PPC">%%LNG_View%%</a></td>
						</tr>
						<tr class="gridrow" onmouseover="this.className='gridrowover';" onmouseout="this.className='gridrow';">
						<TD>
							<img src="images/searchengineicon.gif" width="20" height="20" align="absMiddle">&nbsp;<b>%%LNG_SearchResults%%</b>
						</TD>
						<td>%%GLOBAL_SearchVisits%%</td>
						<td>%%GLOBAL_SearchVisits_Percent%% %</td>
						<td>%%GLOBAL_SearchConversions%%</td>
						<td>%%GLOBAL_SearchConversions_Percent%% %</td>
						<td>%%LNG_CurrencySymbol%% %%GLOBAL_SearchRevenue%%</td>
						<td>n/a</td>
						<td><a href="index.php?Page=Search">%%LNG_View%%</a></td>
						</tr>
						<tr class="gridrow" onmouseover="this.className='gridrowover';" onmouseout="this.className='gridrow';">
						<TD>
							<img src="images/referrersicon.gif" width="20" height="20" align="absMiddle">&nbsp;<b>%%LNG_Referrers%%</b>
						</TD>
						<td>%%GLOBAL_ReferrerVisits%%</td>
						<td>%%GLOBAL_ReferrerVisits_Percent%% %</td>
						<td>%%GLOBAL_ReferrerConversions%%</td>
						<td>%%GLOBAL_ReferrerConversions_Percent%% %</td>
						<td>%%LNG_CurrencySymbol%% %%GLOBAL_ReferrerRevenue%%</td>
						<td>n/a</td>
						<td><a href="index.php?Page=Referrers">%%LNG_View%%</a></td>
						</tr>
						<tr><td colspan=8 align=right><br>%%TPL_PrintExportFooter%%</td></tr>
						</table>
					<td style="PADDING-RIGHT: 20px; BORDER-LEFT: 1px solid">&nbsp;</td>
					<TD vAlign="top" width="29%">
					<p class="Text">(%%GLOBAL_FirstVisit%%)</p>
					%%TPL_Calendar_Index%%
					<br>
					<div class="RightHeader">%%LNG_ToDo%%</div>
						<UL class="Text">
							<LI><a href="index.php?Page=Track">%%LNG_GetTrackingCode%%</a>
							<LI><a href="index.php?Page=Conversion">%%LNG_GetConversionCode%%</a>
							<LI><a href="index.php?Page=Createppc">%%LNG_CreatePPC%%</a>
							<LI><a href="index.php?Page=Createcampaign">%%LNG_CreateCampaign%%</a>
						</UL>
					</TD>
				</TR>
			</TABLE>
			<br><br>&nbsp;&nbsp;* %%LNG_HoldMouseOver%%
		</td>
	</tr>
</table>

<div align=right><a href="javascript:launchQS()">%%LNG_LaunchQuickStart%% &raquo;</a></div>

<script>

function launchQS()
{
	screenWidth = screen.availWidth / 2 - 250
	screenHeight = screen.availHeight / 2 - 125

	window.open("index.php?Action=QuickStart", "", "left=" + screenWidth + ", top="+ screenHeight +", width=450, height=260, toolbar=0, statusbar=0, scrollbars=0");
}

function UpdateGraph() {
	grph = document.getElementById('ChangeGraph');
	idx = grph.selectedIndex;
	opt = grph[idx].value;
	document.location = 'index.php?Graph=' + opt;

}

</script>
