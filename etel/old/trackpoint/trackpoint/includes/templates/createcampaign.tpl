<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td class="heading1">%%LNG_CreateCampaign%%</td>
  </tr>
<tr>
	<td class=body>
		<br>%%LNG_Help_CreateCampaign%%
		%%GLOBAL_WarningMessage%%
	</td>
</tr>
  <tr>
	<td class="body"><br>
		<TABLE id="Table1" cellSpacing="0" cellPadding="0" width="100%" border="0">
			<TR>
				<TD vAlign="top" width="">
				<form name="campaignform" method="post" action="index.php?Page=CreateCampaign&Action=CampaignLink&Process=1" target="CampaignLinkFrame" onsubmit="return generateCode();">
				<table width=100% class=panel>
					<tr><td class=heading2 colspan=2>%%LNG_CampaignInformation%%</td></tr>
					<tr><td class="FieldLabel"><span class="Required">*</span> %%LNG_CreateCampaignSite%%:</td><td><input type=text class="field250" id="CampaignSite" name="CampaignSite" value=""> %%LNG_HLP_CreateCampaignSite%%</td></tr>
					<tr><td class="FieldLabel"><span class="Required">*</span> %%LNG_CreateCampaignName%%:</td><td><input type=text class="field250" id="CampaignName" name="CampaignName" value=""> %%LNG_HLP_CreateCampaignName%%</td></tr>
					<tr><td class="FieldLabel"><span class="Required">*</span> %%LNG_LandingPageURL%%:</td><td><input type=text class="field250" id="CampaignURL" value="http://" name="CampaignURL"> %%LNG_HLP_LandingPageURL%%</td></tr>
					<tr><td class="FieldLabel">&nbsp;&nbsp;&nbsp;%%LNG_CampaignCost%%:</td><td><input type=text class="field250" id="CampaignCost" value="%%GLOBAL_CampaignCost%%" name="CampaignCost"> %%LNG_HLP_CampaignCost%%</td></tr>
					<tr><td class="FieldLabel">&nbsp;&nbsp;&nbsp;%%LNG_CostType%%:</td><td><input type=checkbox value="1" id="CampaignCostType" name="CampaignCostType" onClick="showRecurring(this)">%%LNG_Recurring%% %%LNG_HLP_CostType%%</td></tr>
					<tr id=period style="display:none">
						<td class="FieldLabel"><span class="Required">*</span> %%LNG_Period%%:</td>
						<td>
							<select onChange="doCustomDate(this)" class=field250 id="PeriodDate" name="PeriodDate">
								<option value=1%%GLOBAL_Period1_Selected%%>%%LNG_Day%%</option>
								<option value=7%%GLOBAL_Period7_Selected%%>%%LNG_Week%%</option>
								<option value=30%%GLOBAL_Period30_Selected%%>%%LNG_Month%%</option>
								<option value=90%%GLOBAL_Period90_Selected%%>%%LNG_3Months%%</option>
								<option value=180%%GLOBAL_Period180_Selected%%>%%LNG_6Months%%</option>
								<option value=365%%GLOBAL_Period365_Selected%%>%%LNG_12Months%%</option>
								<option value=custom%%GLOBAL_PeriodCustom_Selected%%>%%LNG_Custom%%</option>
							</select>&nbsp;%%LNG_HLP_Period%%
						</td>
					</tr>
					<tr id="CustomDate" style="display:none">
						<td class="FieldLabel"><span class="Required">*</span> %%LNG_Days%%:</td>
						<td><input type=text class=field250 id="Days" name="Days"></td>
					</tr>
					<tr id=period2 style="display:none">
						<td class="FieldLabel"><span class="Required">*</span> %%LNG_StartDate%%:</td>
						<td>
						
						<select class=text style="margin-bottom:3px" id=StartDay name="StartDay">
								%%GLOBAL_StartDay%%
						</select>
						<select class=text style="margin-bottom:3px" id=StartMonth name="StartMonth">
								%%GLOBAL_StartMonth%%
						</select>
						<select class=text style="margin-bottom:3px" id=StartYear name="StartYear"> 
								%%GLOBAL_StartYear%%
						</select>
						%%LNG_HLP_CampaignStartDate%%
						</td>
					</tr>
					<tr><td class="FieldLabel">&nbsp;&nbsp;&nbsp;%%LNG_EncodeInfo%%:</td><td><input type="checkbox" value="1" id="EncodeInfo" name="EncodeInfo" CHECKED>%%LNG_EncodeInfoYes%% %%LNG_HLP_EncodeInfo%%</td></tr>
					<tr><td>&nbsp;</td><td><input type=submit class=smallbutton value="%%LNG_GenerateCode%%" style="margin-top:3px"></td></tr>
				</table>
				</form>
				<script>

					function showRecurring(myObj) {
							if (myObj.checked) {
								document.getElementById("period").style.display = ""
								document.getElementById("period2").style.display = ""
								if (document.getElementById("PeriodDate").value == "custom") {
									document.getElementById("CustomDate").style.display = ""
								}
							} else {
								document.getElementById("period").style.display = "none"
								document.getElementById("period2").style.display = "none"
								document.getElementById("CustomDate").style.display = "none"
							}
						}
						
					function doCustomDate(myObj) {
							if (myObj.options[myObj.selectedIndex].value == "custom") {
								document.getElementById("CustomDate").style.display = ""
							} else {
								document.getElementById("CustomDate").style.display = "none"
							}
						}

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
							 }
						  }
					   return IsNumber;
					   
					}

					function generateCode() {

						site = document.getElementById("CampaignSite").value

						if (site == "") {
							alert("%%LNG_CampaignSiteError%%")
							document.getElementById("CampaignSite").focus();
							document.getElementById("CampaignSite").select();
							return false;
						}

						name = document.getElementById("CampaignName").value

						if (name == "") {
							alert("%%LNG_CampaignNameError%%")
							document.getElementById("CampaignName").focus();
							document.getElementById("CampaignName").select();
							return false;
						}

						url = document.getElementById("CampaignURL").value

						if (url == "" || url =="http://") {
							alert("%%LNG_CampaignURLError%%")
							document.getElementById("CampaignURL").focus();
							document.getElementById("CampaignURL").select();
							return false;
						}

						cost = document.getElementById("CampaignCost").value

						if (!IsNumeric(cost)) {
							alert("%%LNG_CampaignCostError2%%")
							document.getElementById("CampaignCost").focus();
							document.getElementById("CampaignCost").select();
							return false;
						}

						var periodDays = ""
						var startDate = ""
						if (document.getElementById("CampaignCostType").checked) {
							periodDays = document.getElementById("PeriodDate").value

							if (periodDays == "custom") {
								if (document.getElementById("Days").value == "") {
									alert("%%LNG_CampaignPeriodError%%")
									document.getElementById("Days").focus()
									document.getElementById("Days").select()
									return false;
								}
								if (!IsNumeric(document.getElementById("Days").value)) {
									alert("%%LNG_CampaignPeriodError2%%")
									document.getElementById("Days").focus()
									document.getElementById("Days").select()
									return false;
								}
							}
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
						<td>%%LNG_CampaignLink%%</td>
					</tr>
					<tr>
						<td>
							<iframe style="width:100%; height: 100px" src="index.php?Page=CreateCampaign&Action=CampaignLink" width="100%" height="100" name="CampaignLinkFrame" class="body"></iframe>
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
