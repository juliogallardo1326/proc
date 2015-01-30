<form name="settings" method="post" action="index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%" onsubmit="return CheckForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="heading1">%%LNG_Settings%%</td>
			  </tr>
			<tr>
				<td class=body><br>%%LNG_Help_Settings%%</td>
			</tr>
			<tr>
				<td>
					%%GLOBAL_Message%%
				</td>
			</tr>
			<tr><td class=body><br><input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
				<input class="formbutton" type="submit" value="%%LNG_Save%%"></td></tr>
			  <tr>
				<td><br>

			<table border="0" cellspacing="0" cellpadding="2" width="100%" class=panel>
				<tr>
					<td colspan="2" class="heading2">
						%%LNG_DatabaseIntro%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_DatabaseType%%:
					</td>
					<td class=body>
						[%%GLOBAL_DatabaseType%%]
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_DatabaseUser%%:
					</td>
					<td>
						<input type="text" name="database_user" value="%%GLOBAL_DatabaseUser%%" class="field250"> %%LNG_HLP_DatabaseUser%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_DatabasePassword%%:
					</td>
					<td>
						<input type="password" name="database_pass" value="%%GLOBAL_DatabasePass%%" class="field250"> %%LNG_HLP_DatabasePassword%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_DatabasePasswordConfirm%%:
					</td>
					<td>
						<input type="password" name="database_pass_confirm" value="" class="field250"> %%LNG_HLP_DatabasePasswordConfirm%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_DatabaseHost%%:
					</td>
					<td>
						<input type="text" name="database_host" value="%%GLOBAL_DatabaseHost%%" class="field250"> %%LNG_HLP_DatabaseHost%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_DatabaseName%%:
					</td>
					<td>
						<input type="text" name="database_name" value="%%GLOBAL_DatabaseName%%" class="field250"> %%LNG_HLP_DatabaseName%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_DatabaseTablePrefix%%:
					</td>
					<td>
						<input type="text" name="tableprefix" value="%%GLOBAL_DatabaseTablePrefix%%" class="field250"> %%LNG_HLP_DatabaseTablePrefix%%
					</td>
				</tr>
				</table>
				<br>

				<table border="0" cellspacing="0" cellpadding="2" width="100%" class=panel>
				<tr>
					<td colspan="2" class="heading2">
						%%LNG_Miscellaneous%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_ApplicationURL%%:
					</td>
					<td>
						<input type="text" name="application_url" value="%%GLOBAL_ApplicationURL%%" class="field250"> %%LNG_HLP_ApplicationURL%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_ApplicationEmail%%:
					</td>
					<td>
						<input type="text" name="email_address" value="%%GLOBAL_EmailAddress%%" class="field250"> %%LNG_HLP_ApplicationEmail%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_ServerTimeZone%%:
					</td>
					<td>
						<select name="servertimezone" class="field250">
							%%GLOBAL_ServerTimeZoneList%%
						</select>&nbsp;%%LNG_HLP_ServerTimeZone%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_CookieTime%%:
					</td>
					<td>
						<input type="text" name="cookie_time" value="%%GLOBAL_CookieTime%%" class="field250"> %%LNG_HLP_CookieTime%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_DelCookieOnPurchase%%:
					</td>
					<td>
						%%LNG_DelCookieIntro%%&nbsp;<input type="checkbox" name="deletecookie" value="1" %%GLOBAL_DeleteCookie%%> %%LNG_HLP_DelCookieOnPurchase%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_TrackingLogs%%:
					</td>
					<td>
						%%LNG_TrackingLogsIntro%%&nbsp;<input type="checkbox" name="trackinglogs" value="1" %%GLOBAL_TrackingLogs%%> %%LNG_HLP_TrackingLogs%%
					</td>
				</tr>
			</table>
				<br>

				<table border="0" cellspacing="0" cellpadding="2" width="100%" class=panel>
				<tr>
					<td colspan="2" class="heading2">
						%%LNG_LicenseKeyIntro%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_LicenseKey%%:
					</td>
					<td>
						<input type="text" name="licensekey" value="%%GLOBAL_LicenseKey%%" class="field250"> %%LNG_HLP_LicenseKey%%
					</td>
				</tr>
				<tr>
					<td>&nbsp</td>
					<td>
						<input type="hidden" name="licensekey_old" value="%%GLOBAL_LicenseKey%%">
						<input type="hidden" name="database_type" value="%%GLOBAL_DatabaseType%%">
						<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script language="javascript">
	function ConfirmCancel()
	{
		if(confirm('Are you sure you want to cancel?'))
		{
			document.location.href='index.php';
		}
		else
		{
			return false;
		}
	}

	function CheckForm() {
		f = document.forms[0];
		if (f.licensekey.value != f.licensekey_old.value) {
			alert("%%LNG_LicenseKeyUpdated%%");
		}
		return true;
	}
</script>
</form>
