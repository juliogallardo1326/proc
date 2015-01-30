<table border="0" height="100%" class='MessageWhite' cellspacing="5" cellpadding="0" align="center">
	<tr>
		<td>&nbsp;</td>
	</tr>
	%%GLOBAL_Report%%
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align='center' valign="bottom">
			[&nbsp;<a href="#" onclick="javascript: StopExport(); return false;">%%LNG_Cancel%%</a>&nbsp;]
		</td>
	</tr>
</table>

<script language="javascript">
	function StopExport() {
		window.opener.document.location='index.php?Page=Export&Action=Finished';
		window.close();
	}
</script>

