<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_CreatePPCBulk%%</td>
	</tr>
	<tr>
		<td class=body>
			<br>%%LNG_Help_CreatePPCBulk%%<br/><br/>
			<input type="button" onclick="javascript: document.location='index.php?Page=CreatePPC';" value="%%LNG_CreatePPC_Button%%" class=smallbutton>
		</td>
	</tr>
	<tr>
		<td class="body"><br>
			<form name="campaignform" method="post" action="index.php?Page=CreatePPCBulk&Action=Upload" target="PPCLinkFrame" enctype="multipart/form-data">
			<TABLE id="Table1" cellSpacing="0" cellPadding="0" width="100%" border="0" class=panel>
				<tr><td class=heading2 colspan=2>%%LNG_PPCInformation%%</td></tr>
				%%GLOBAL_SelectUser%%
				<tr>
					<td class="FieldLabel">
						<span class="Required">*</span> %%LNG_FieldSeparator%%:
					</td>
					<td>
						<input type="text" name="fieldseparator" value="," class="field250"> %%LNG_HLP_FieldSeparator%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<span class="Required">*</span> %%LNG_ChooseFile_BulkPPC%%
					</td>
					<td>
						<input type="file" name="ppcfile" value="" class="field250"> %%LNG_HLP_ChooseFile_BulkPPC%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						&nbsp;&nbsp;&nbsp;%%LNG_EncodeInfo%%:
					</td>
					<td>
						<input type="checkbox" value="1" id="EncodeInfo" name="EncodeInfo" CHECKED>%%LNG_EncodeInfoYes%% %%LNG_HLP_EncodeInfo%%
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td><input type=submit class=smallbutton value="%%LNG_Generate%%" style="margin-top:3px"></td>
				</tr>
				<TR>
					<TD vAlign="top" width="" colspan="2">
						<br>
						<table width=100% border=0 cellpadding=0 cellspacing=0>
							<tr class="heading3">
								<td>%%LNG_PPCLink%%</td>
							</tr>
							<tr>
								<td>
									<iframe style="width:100%; height: 100px" src="index.php?Page=CreatePPC&Action=PPCLink" width="100%" height="100" name="PPCLinkFrame" class="body"></iframe>
								</td>
							</tr>
						</table>
					</TD>
				</TR>
			</TABLE>
			</form>
			<br><br>
		</td>
	</tr>
</table>
