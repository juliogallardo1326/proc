<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td class="heading1">%%LNG_Users%%</td>
			  </tr>
			<tr>
				<td class=body><br>%%LNG_Help_Users%%</td>
			</tr>
			<tr>
				<td>
					%%GLOBAL_Message%%
				</td>
			</tr>
			<tr><td class=body><br>%%GLOBAL_Users_AddButton%%</td></tr>
			  <tr>
				<td class=body><br>

				<span class=body>%%GLOBAL_UserReport%%</span>

		<table border=0 cellspacing="1" cellpadding="2" width=100% class=text>
		<tr class="Heading3">
			<td width="4%">&nbsp;</td>
			<td width="20%">
				%%LNG_UserName%%
			</td>
			<td width="36%">
				%%LNG_FullName%%
			</td>
			<td width="20%">
				%%LNG_Status%%
			</td>
			<td width="20%">
				%%LNG_Action%%
			</td>
		</tr>
			%%TPL_Users_List_Row%%

		</table>


		</td>
	</tr>
</table>

<script language="javascript">
	function ConfirmDelete(UserID) {
		if (!UserID) {
			return false;
		}
		if (confirm("%%LNG_DeleteUserPrompt%%")) {
			document.location='index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%&Action=Delete&UserID=' + UserID;
		}
	}
</script>
