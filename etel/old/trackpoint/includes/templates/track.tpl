<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_TrackingCode%%</td>
	</tr>
	<tr>
		<td class=body><br>
			%%LNG_Help_TrackingCode%%
			%%GLOBAL_WarningMessage%%
		</td>
	</tr>
	<tr>
		<td class=body><br>
			<TABLE id="Table1" cellSpacing="0" cellPadding="0" width="100%" border="0">
				<TR>
					<TD vAlign="top" width="">
						<table width=100% border=0 cellpadding=0 cellspacing=0>
							<tr class="heading3">
								<td>%%LNG_TrackingCode%%</td>
							</tr>
							<tr>
								<td>
<textarea style="width:100%; height: 200px">&lt!-- Start Tracking Code -->
&lt;script language="javascript">
var ref   = escape(document.referrer);
var land  = escape( location.href );

document.write( '<' + 'script language="javascript" src="%%GLOBAL_TrackPointURL%%/t.php?u=%%GLOBAL_TrackPointUserID%%&r=' + ref + '&l=' + land + '"><' + '/script>' );
&lt;/script>
&lt!-- End Tracking Code --></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
