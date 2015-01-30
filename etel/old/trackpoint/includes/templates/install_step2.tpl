<form method="post" action="index.php?Page=Install&Action=Step3" onSubmit="return CheckStep2()">
<table><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">
	<TABLE id="Table5" cellSpacing="0" cellPadding="0" width="100%" align="center">
		<TR>
			<TD class="Heading1"><br>Step 2 of 4: Site Settings</TD>
		</TR>
		<TR>
			<TD><DIV class="body"><br>Please enter your site specific settings below.</div><br></TD>
		</TR>
		<TR>
			<TD>
				<TABLE class="Panel" id="Table7" width="98%">
					<TR>
						<TD class="Heading2" colSpan="2">Site Settings</TD>
					</TR>
					<tr>
						<td class="FieldLabel">
							<span class="Required">*</span>&nbsp;TrackPoint URL:
						</td>
						<td>
							<input type="text" name="application_url" id="application_url" class="field250" value="%%GLOBAL_application_url%%">&nbsp;%%LNG_HLP_ApplicationURL%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<span class="Required">*</span>&nbsp;%%LNG_ApplicationEmail%%:
						</td>
						<td>
							<input type="text" id="email_address" name="email_address" class="field250" value="">&nbsp;%%LNG_HLP_ApplicationEmail%%
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
					<TR>
						<TD class="Gap" colSpan="2"></TD>
					</TR>
					<TR>
						<TD class="FieldLabel"></TD>
						<TD>
							<input type="submit" name="Step1NextButton" value="Next >>" class="FormButton">
						</TD>
					</TR>
					<TR>
						<TD class="Gap" colSpan="2"></TD>
					</TR>
				</TABLE>
			</TD>
		</TR>
	</TABLE>
	</form>

	<script>

		function CheckStep2()
		{
			if(document.getElementById("application_url").value == "")
			{
				alert("Please enter your TrackPoint URL.");
				document.getElementById("application_url").focus();
				return false;
			}
			if (document.getElementById("email_address").value == "") {
				alert("Please type in an email address where users can contact an administrator.");
				document.getElementById("email_address").focus();
				return false;
			}
			return true;
		}
	</script>

