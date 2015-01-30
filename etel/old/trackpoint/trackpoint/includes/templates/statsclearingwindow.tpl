<table border="0" width="300" height="100%" class='MessageWhite'>
%%GLOBAL_Report%%
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align='center'>
			[&nbsp;<a href="#" onclick="javascript: StopDeletion(); return false;">%%LNG_Cancel%%</a>&nbsp;]
		</td>
	</tr>
</table>

<script language="javascript">
	function StopDeletion() {
		window.opener.document.location='index.php?Page=Users&Action=DelStats_Cancelled&StatsCleaned=1&UserID=%%GLOBAL_UserID%%';
		window.close();
	}
</script>

