<form name="settings" method="post" action="index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%" onsubmit="return CheckForm();">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="heading1">%%LNG_EditUser%%</td>
			  </tr>
			<tr>
				<td class=body><br>%%LNG_Help_EditUser%%</td>
			</tr>
			<tr>
				<td>
					%%GLOBAL_Message%%
				</td>
			</tr>
			<tr><td class=body><br>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
				&nbsp;&nbsp;<input class="field150" type="button" value="%%LNG_ClearStatistics%%" onclick="ConfirmDeleteStats()">
			</td></tr>
			  <tr>
				<td><br>

			<table border="0" cellspacing="0" cellpadding="2" width="100%" class=panel>
				<tr><td class=heading2 colspan=2>%%LNG_UserDetails%%</td></tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_UserName%%:
					</td>
					<td>
						%%GLOBAL_UserName%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_Password%%:
					</td>
					<td>
						<input type="password" name="tp_password" value="" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_PasswordConfirm%%:
					</td>
					<td>
						<input type="password" name="tp_password_confirm" value="" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_EmailAddress%%:
					</td>
					<td>
						<input type="text" name="emailaddress" value="%%GLOBAL_EmailAddress%%" class="field250"> %%LNG_HLP_EmailAddress%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_FullName%%:
					</td>
					<td>
						<input type="text" name="fullname" value="%%GLOBAL_FullName%%" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_IgnoreSites%%:
					</td>
					<td>
						<input type="text" name="ignoresites" value="%%GLOBAL_IgnoreSites%%" class="field250"> %%LNG_HLP_IgnoreSites%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_IgnoreIPs%%:
					</td>
					<td>
						<input type="text" name="ignoreips" value="%%GLOBAL_IgnoreIPs%%" class="field250"> %%LNG_HLP_IgnoreIPs%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_IgnoreKeywords%%:
					</td>
					<td>
						<input type="text" name="ignorekeywords" value="%%GLOBAL_IgnoreKeywords%%" class="field250"> %%LNG_HLP_IgnoreKeywords%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class=required>*</span> %%LNG_UserTimeZone%%:
					</td>
					<td>
						<select name="usertimezone" class="field250">
							%%GLOBAL_TimeZoneList%%
						</select>&nbsp;%%LNG_HLP_UserTimeZone%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_Active%%:
					</td>
					<td>
						<input type="checkbox" name="status" value="1"%%GLOBAL_StatusChecked%%> %%LNG_YesIsActive%% %%LNG_HLP_Active%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_Admin%%:
					</td>
					<td>
						<input type="checkbox" name="admin" value="1"%%GLOBAL_AdminChecked%%> %%LNG_YesIsAdmin%% %%LNG_HLP_Admin%%
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
						<input class="formbutton" type="submit" value="%%LNG_Save%%">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script language="javascript">

	function ConfirmDeleteStats() {
		if(confirm('%%LNG_ConfirmDeleteStatistics%%'))
		{
			if (confirm('%%LNG_ReallyConfirmDeleteStatistics%%')) {
				window.open('index.php?Page=Users&Action=DelStats&UserID=%%GLOBAL_UserID%%', 'delStats%%GLOBAL_UserID%%', 'width=300,height=270,left=400,top=270');
			} else {
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	function ConfirmCancel()
	{
		if(confirm('%%LNG_ConfirmCancel%%'))
		{
			document.location.href='index.php?Page=Users';
		}
		else
		{
			return false;
		}
	}

	function CheckForm() {
		f = document.forms[0];
		if (f.password.value != "") {
			if (f.password_confirm.value == "") {
				alert("%%LNG_PasswordConfirmAlert%%");
				f.password_confirm.focus();
				return false;
			}
			if (f.password.value != f.password_confirm.value) {
				alert("%%LNG_PasswordsDontMatch%%");
				f.password_confirm.select();
				f.password_confirm.focus();
				return false;
			}
		}
		if (f.emailaddress.value == "") {
			alert("%%LNG_SupplyUserEmailAddress%%");
			f.emailaddress.focus();
			return false;
		}
		return true;
	}
</script>
</form>