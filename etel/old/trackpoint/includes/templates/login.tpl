<!-- This top table is usually in the menu file, however login screen does not require menu. Table is closed in footer -->
<table><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">
<form action="index.php?Page=%%PAGE%%&Action=%%GLOBAL_SubmitAction%%" method="post" name="frmLogin" onSubmit="return CheckLogin()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr><td>&nbsp</td></tr>
			<tr><td>&nbsp</td></tr>
			  <tr>
				<td class="heading1">%%LNG_LoginTitle%%</td>
			  </tr>
			<tr>
				<td class=body><br>%%LNG_Help_Login%%&nbsp;%%LNG_ForgotPasswordReminder%%</td>
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
				  <td class="Heading2" colspan=2>%%LNG_LoginDetails%%</td>
				</tr>
				<tr>
				  <td nowrap class="SmallFieldLabel">%%LNG_UserName%%:</td>
				  <td>
					<input type="text" id="username" name="username" class="Field150" value="%%GLOBAL_username%%">
				  </td>
				</tr>
				<tr>
				  <td nowrap class="SmallFieldLabel">%%LNG_Password%%:</td>
				  <td>
					<input type="password" id="password" name="password" class="Field150" value="">
				  </td>
				</tr>
				<tr>
				  <td nowrap>&nbsp;</td>
				  <td align="left">
					<input type="checkbox" name="rememberme" value="1">&nbsp;%%LNG_RememberMe%%
				  </td>
				</tr>
				  <tr>
					<td>&nbsp;</td>
					<td>
					  <input type="submit" name="SubmitButton" value="%%LNG_Login%%" class="FormButton">
					</td>
				  </tr>
				  <tr><td class="Gap"></td></tr>
			  </table>
			</td>
		  </tr>
		</table>
		</form>
</div>

		<script language=JavaScript>

			document.getElementById('username').focus();
			if (document.getElementById('username').value != '') {
				document.getElementById('password').focus();
			}

			function CheckLogin()
			{
				var f = document.frmLogin;
				
				if(f.username.value == '')
				{
					alert('%%LNG_NoUsername%%');
					f.username.focus();
					f.username.select();
					return false;
				}
				
				if(f.password.value == '')
				{
					alert('%%LNG_NoPassword%%');
					f.password.focus();
					f.password.select();
					return false;
				}
				
				// Everything is OK
				return true;
			}

			</script>
