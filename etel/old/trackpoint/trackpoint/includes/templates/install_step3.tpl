<table><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">
	<form method="post" action="index.php?Page=Install&Action=Step4" onSubmit="return CheckStep3()">
	<TABLE id="Table5" cellSpacing="0" cellPadding="0" width="100%" align="center">
		<TR>
			<TD class="Heading1"><br>Step 3 of 4: Database Settings</TD>
		</TR>
		<TR>
			<TD class="body">
				<DIV><br>
					Please enter the details for your database in the form shown below.<br>The database tables and fields will be created for you automatically.</br><br>
				</DIV>
				<div style="display: %%GLOBAL_HideErrorPanel%%">
				  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="Message">
					<tr>
					  <td width="20px"><img src="images/error.gif" width="18" height="18" hspace="10" vspace="5" align="middle"></td>
					<td>An error occured while trying to build your database: %%GLOBAL_Error%%<br></td>
					</tr>
				  </table><br>
				</div>
			</TD>
		</TR>
		<TR>
			<TD>
				<TABLE class="Panel" id="Table7" width="98%">
					<TR>
						<TD class="Heading2" colSpan="2">Database Settings</TD>
					</TR>
					<tr>
						<td class="FieldLabel">
							<span class="Required">*</span>&nbsp;Database Type:
						</td>
						<td>
							<select name="dbtype" class="field250">
								<option value="mysql"%%GLOBAL_mysql%%>MySQL</option>
								<option value="pgsql"%%GLOBAL_pgsql%%>PostgreSQL</option>
							</select>
							<img onMouseOut="HideHelp('d1')" onMouseOver="ShowHelp('d1', 'Database Type', 'Which type of database do you have access to?')" src="images/help.gif" width="24" height="16" border="0">
							<div style="display:none" id="d1"></div>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<span class="Required">*</span>&nbsp;Database Server:
						</td>
						<td>
							<input type="text" name="databaseserver" id="databaseserver" value="%%GLOBAL_databaseserver%%" class="field250">&nbsp;%%LNG_HLP_DatabaseHost%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<span class="Required">*</span>&nbsp;Database Username:
						</td>
						<td>
							<input type="text" name="databaseuser" id="databaseuser" value="%%GLOBAL_databaseuser%%" class="field250">&nbsp;%%LNG_HLP_DatabaseUser%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							&nbsp;&nbsp;&nbsp;Database Password:
						</td>
						<td>
							<input type="password" name="databasepass" id="databasepass" value="%%GLOBAL_databasepass%%" class="field250">&nbsp;%%LNG_HLP_DatabasePassword%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							&nbsp;&nbsp;&nbsp;Database Password (confirm):
						</td>
						<td>
							<input type="password" name="databasepass_confirm" id="databasepass_confirm" value="" class="field250">&nbsp;%%LNG_HLP_DatabasePasswordConfirm%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<span class="Required">*</span>&nbsp;Database Name:
						</td>
						<td>
							<input type="text" name="databasename" id="databasename" value="%%GLOBAL_databasename%%" class="field250">&nbsp;%%LNG_HLP_DatabaseName%%
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							&nbsp;&nbsp;&nbsp;Table Prefix:
						</td>
						<td>
							<input type="text" name="tableprefix" id="tableprefix" value="%%GLOBAL_tableprefix%%" class="field250">&nbsp;%%LNG_HLP_DatabaseTablePrefix%%
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

		function CheckStep3()
		{
			if(document.getElementById("databaseserver").value == "")
			{
				alert("Please enter your database server name.");
				document.getElementById("databaseserver").focus();
				return false;
			}

			if(document.getElementById("databaseuser").value == "")
			{
				alert("Please enter your database username.");
				document.getElementById("databaseuser").focus();
				return false;
			}

			if(document.getElementById("databasepass").value != "")
			{
				if(document.getElementById("databasepass").value != document.getElementById("databasepass_confirm").value)
				{
					alert("Your database passwords don\'t match.");
					document.getElementById("databasepass_confirm").focus();
					document.getElementById("databasepass_confirm").select();
					return false;
				}
				
			}
			
			if(document.getElementById("databasename").value == "")
			{
				alert("Please enter your database name.");
				document.getElementById("databasename").focus();
				return false;
			}

			return true;
		}

	</script>
