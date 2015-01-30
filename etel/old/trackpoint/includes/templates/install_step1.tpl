<table border=0><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">
	<TABLE id="Table5" cellSpacing="0" cellPadding="0" width="100%" align="center" border="0">
		<TR>
			<TD class="Heading1"><br>Step&nbsp;1 of 4: Permissions and License Key</TD>
		</TR>
		<TR>
			<TD>
				<DIV class="body"><br>Please copy&nbsp;and paste&nbsp;the license key you received when you 
				purchased TrackPoint.
				</DIV>
				<br/>
				<div style="display: %%GLOBAL_HideErrorPanel%%">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="Message">
						<tr>
							<td width="20px"><img src="images/error.gif" width="18" height="18" hspace="10" vspace="5" align="middle"></td>
							<td>%%GLOBAL_Error%%<br></td>
						</tr>
					</table><br>
				</div>

				<div style="display: %%GLOBAL_PermissionErrorPanel%%">
					<form method="post" action="index.php?Page=Install&Action=Step1" style="margin: 0px;">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="Message">
							<tr>
								<td width="20px"><img src="images/error.gif" width="18" height="18" hspace="10" vspace="5" align="middle"></td>
								<td>%%GLOBAL_Error%%<br></td>
							</tr>
							<TR>
								<TD>&nbsp;</TD>
								<TD>
									<input type="submit" name="TryAgain" value="Try Again" class="FormButton" />
								</TD>
							</TR>
						</table><br>
					</form>
				</div>

				<form method="post" action="index.php?Page=Install&Action=Step2" onSubmit="return CheckStep1()">
					<TABLE class="Panel" id="Table7" width="98%" border=0 style="display: %%GLOBAL_ShowStep1%%">
					<TR>
						<TD class="Heading2" colSpan="2">License Key</TD>
					</TR>
					<TR>
						<TD class="FieldLabel"><SPAN class="Required">*</SPAN> License Key:</TD>
						<TD>
							<input type="text" name="LicenseKey" id="LicenseKey" class="field250" size="20" value="%%GLOBAL_LicenseKey%%" />&nbsp;
							<img onMouseOut="HideHelp('d1')" onMouseOver="ShowHelp('d1', 'License Key', 'You should have received a license key when you purchased TrackPoint, copy it to here.')" src="images/help.gif" width="24" height="16" border="0">
							<div style="display:none" id="d1"></div>
						</TD>
					</TR>
					<TR>
						<TD class="Gap" colSpan="2"></TD>
					</TR>
					<TR>
						<TD class="FieldLabel"></TD>
						<TD>
							<input type="submit" name="Step1NextButton" value="Next >>" class="FormButton" />
						</TD>
					</TR>
					<TR>
						<TD class="Gap" colSpan="2"></TD>
					</TR>
				</TABLE>
			</form>
		</TD>
	</TR>
</TABLE>

<script>
	function CheckStep1()
	{
		if(document.getElementById("LicenseKey").value == "")
		{
			alert("Please enter your license key.");
			document.getElementById("LicenseKey").focus();
			return false;
		}

		return true;
	}
</script>
