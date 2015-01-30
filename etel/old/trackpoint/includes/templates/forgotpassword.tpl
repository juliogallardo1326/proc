<!-- This top table is usually in the menu file, however login screen does not require menu. Table is closed in footer -->
<table><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">
<form action="index.php?Page=%%PAGE%%&Action=%%GLOBAL_SubmitAction%%" method="post" name="frmLogin" onSubmit="return CheckLogin()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td>&nbsp</td></tr>
			<tr><td>&nbsp</td></tr>
			  <tr>
				<td class="heading1">%%LNG_ForgotPasswordTitle%%</td>
			  </tr>
			<tr>
				<td class=body><br>%%LNG_Help_ForgotPassword%%</td>
			</tr>
			<tr>
				<td>
					%%GLOBAL_Message%%
				</td>
			</tr>
			  <tr>
				<td class=body><br>
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class=panel>
				<tr>
				  <td class="Heading2" colspan=2>%%LNG_ForgotPasswordDetails%%</td>
				</tr>
				<tr>
				  <td class="SmallFieldLabelLogin">%%LNG_UserName%%:</td>
				  <td align="left">
					<input type="text" id="username" name="username" class="Field150" value="">
				  </td>
				</tr>
				<tr>
				  <td class="SmallFieldLabelLogin">
				  	%%LNG_NewPassword%%:
				</td>
				  <td align="left">
					<input type="password" id="password" name="password" class="Field150" value="">
				  </td>
				</tr>
				<tr>
					<td class="SmallFieldLabelLogin">
						%%LNG_PasswordConfirm%%:
					</td>
					<td align="left">
						<input type="password" name="password_confirm" value="" class="field150">
					</td>
				</tr>
				  <tr>
					<td class="SmallFieldLabelLogin">&nbsp;</td>
					<td>
					  <input type="submit" name="SubmitButton" value="%%LNG_SendPassword%%" class="Field150">
					</td>
				  </tr>
				  <tr><td class="Gap" colspan="2"></td></tr>
			  </table>
			</td>
		  </tr>
		</table>
		</form>
</div>

		<script language=JavaScript>

			document.getElementById('username').focus();
			document.getElementById('username').select();

			function CheckLogin()
			{
				var f = document.frmLogin;
				
				if(f.username.value == '')
				{
					alert('%%LNG_NoUsername%%');
					f.username.focus();
					return false;
				}
				
				if (f.password.value == '') {
					alert('%%LNG_NoPassword%%');
					f.password.focus();
					return false;
				}
				
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
				
				// Everything is OK
				return true;
			}

			</script>
