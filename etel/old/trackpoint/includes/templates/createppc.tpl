<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_CreatePPC%%</td>
	</tr>
	<tr>
		<td class=body>
			<br>%%LNG_Help_CreatePPC%%<br/><br/>
			<input type="button" onclick="javascript: document.location='index.php?Page=CreatePPCBulk';" value="%%LNG_CreatePPCBulk_Button%%" class=smallbutton>
			%%GLOBAL_WarningMessage%%
		</td>
	</tr>
	<tr>
		<td class="body"><br>
			<TABLE id="Table1" cellSpacing="0" cellPadding="0" width="100%" border="0">
				<TR>
					<TD vAlign="top" width="">
					<form name="campaignform" method="post" action="index.php?Page=CreatePPC&Action=PPCLink&Process=1" target="PPCLinkFrame" onsubmit="return generateCode();">
					<table width=100% class=panel>
						<tr><td class=heading2 colspan=2>%%LNG_PPCInformation%%</td></tr>
						<tr><td class="FieldLabel"><span class="Required">*</span> %%LNG_CreatePPCEngine%%:</td><td><input type=text class="field250" id="ppcEngine" name="ppcEngine"> %%LNG_HLP_CreatePPCEngine%%</td></tr>
						<tr><td class="FieldLabel"><span class="Required">*</span> %%LNG_CreatePPCName%%:</td><td><input type=text class="field250" id="ppcName" name="ppcName"> %%LNG_HLP_CreatePPCName%%</td></tr>
						<tr><td class="FieldLabel"><span class="Required">*</span> %%LNG_LandingPageURL%%:</td><td><input type=text class="field250" id="landingURL" value="http://" name="landingURL"> %%LNG_HLP_LandingPageURL%%</td></tr>
						<tr><td class="FieldLabel">&nbsp;&nbsp;&nbsp;%%LNG_CreatePPCCost%%:</td><td><input type=text class="field250" id="ppcCost" value="" name="ppcCost"> %%LNG_HLP_CreatePPCCost%%</td></tr>
						<tr><td class="FieldLabel">&nbsp;&nbsp;&nbsp;%%LNG_EncodeInfo%%:</td><td><input type="checkbox" value="1" id="EncodeInfo" name="EncodeInfo" CHECKED>%%LNG_EncodeInfoYes%% %%LNG_HLP_EncodeInfo%%</td></tr>
						<tr><td>&nbsp;</td><td><input type=submit class=smallbutton value="%%LNG_GenerateCode%%" style="margin-top:3px"></td></tr>
					</table>
					</form>
					<script>

						function IsNumeric(sText)
						{
							var ValidChars = "0123456789.";
							var IsNumber=true;
							var Char;

							for (i = 0; i < sText.length && IsNumber == true; i++)
							{
								Char = sText.charAt(i);
								if (ValidChars.indexOf(Char) == -1)
								{
									IsNumber = false;
									break;
								}
							}
							return IsNumber;
						}


						function generateCode() {

							site = document.getElementById("ppcEngine").value

							if (site == "") {
								alert("%%LNG_PPCEngineError%%")
								document.getElementById("ppcEngine").focus();
								document.getElementById("ppcEngine").select();
								return false;
							}

							name = document.getElementById("ppcName").value

							if (name == "") {
								alert("%%LNG_PPCNameError%%")
								document.getElementById("ppcName").focus();
								document.getElementById("ppcName").select();
								return false;
							}

							url = document.getElementById("landingURL").value

							if (url == "" || url =="http://") {
								alert("%%LNG_PPCURLError%%")
								document.getElementById("landingURL").focus();
								document.getElementById("landingURL").select();
								return false;
							}

							cost = document.getElementById("ppcCost").value

							if (!IsNumeric(cost)) {
								alert("%%LNG_PPCCostError%%")
								document.getElementById("ppcCost").focus();
								document.getElementById("ppcCost").select();
								return false;
							}
							return true;
						}

						function doAmount(myObj) {
							if (myObj.options[myObj.selectedIndex].value == "sale") {
								document.getElementById("amountRow").style.display = ""
							} else {
								document.getElementById("amountRow").style.display = "none"
							}
						}
					</script>

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
			<br><br>
		</td>
	</tr>
</table>
