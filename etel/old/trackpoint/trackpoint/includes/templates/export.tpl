<form method="post" name="exportform" action="index.php?Page=Export&Action=Step2" onsubmit="return CheckForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_Export_Heading%%</td>
	</tr>
	<tr>
		<td class="intro body">
			<div>%%LNG_Export_Help%%</div>
			<div><input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">&nbsp;<input class="formbutton" type="submit" value="%%LNG_Next%% &raquo"></div>
		</td>
	</tr>
	<tr>
		<td>
			%%GLOBAL_Message%%
		</td>
	</tr>
	<tr>
		<td>
			<table border="0" class="panel" cellpadding="4">
				<tr>
					<td colspan="2" class="heading2">
						%%LNG_Export_ChooseReports%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%LNG_Export_Include%%:
					</td>
					<td class=body>
						<input type="checkbox" value="1" name="campaign" %%GLOBAL_CampaignChecked%%>%%LNG_campaign%%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;%%LNG_HLP_Export_Include%%<br/>
						<input type="checkbox" value="1" name="ppc" %%GLOBAL_PpcChecked%%>%%LNG_ppc%%<br/>
						<input type="checkbox" value="1" name="search" %%GLOBAL_SearchChecked%%>%%LNG_search%%<br/>
						<input type="checkbox" value="1" name="referrer" %%GLOBAL_ReferrerChecked%%>%%LNG_referrer%%<br/>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%LNG_Export_DateRange%%: 
					</td>
					<td class="body">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<select name="Calendar[DateType]" class=Text onChange="doCustomDate(this)">
										%%GLOBAL_CalendarOptions%%
									</select>
									%%LNG_HLP_Export_DateRange%%
								</td>
								<td>
									<span id=customDate style="display:%%GLOBAL_CustomDateDisplay%%">&nbsp;
										<select name="Calendar[From][Day]" class=text style="margin-bottom:3px">%%GLOBAL_CustomDayFrom%%</select>
										<select name="Calendar[From][Mth]" class=text style="margin-bottom:3px">%%GLOBAL_CustomMthFrom%%</select>
										<select name="Calendar[From][Yr]" class=text style="margin-bottom:3px">%%GLOBAL_CustomYrFrom%%</select>
										<span class=body>%%LNG_To%%</span>
										<select name="Calendar[To][Day]" class=text style="margin-bottom:3px">%%GLOBAL_CustomDayTo%%</select>
										<select name="Calendar[To][Mth]" class=text style="margin-bottom:3px">%%GLOBAL_CustomMthTo%%</select>
										<select name="Calendar[To][Yr]" class=text style="margin-bottom:3px">%%GLOBAL_CustomYrTo%%</select>
									</span>&nbsp;
									<span id="showDate"></span><!-- here to stop a javascript error //-->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td>&nbsp;</td>
								<td class=body><input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
								<input class="formbutton" type="submit" value="%%LNG_Next%% &raquo"></td>
							</tr>
			</table>
		</td>
	</tr>
</table>
<script language="javascript">
	function CheckForm() {
		form = document.exportform;
		if (!form.ppc.checked && !form.campaign.checked && !form.search.checked && !form.referrer.checked) {
			alert('%%LNG_Export_ChooseType%%');
			return false;
		}
		return true;
	}
	
	function ConfirmCancel() {
		if (confirm('%%LNG_ConfirmCancel%%')) {
			document.location = 'index.php';
			return;
		}
	}
</script>
