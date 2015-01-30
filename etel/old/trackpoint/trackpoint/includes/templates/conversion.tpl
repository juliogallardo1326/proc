<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="heading1">%%LNG_ConversionCode%%</td>
	</tr>
	<tr>
		<td class=body>
			<br>%%LNG_Help_ConversionCode%%
			%%GLOBAL_WarningMessage%%
		</td>
	</tr>
	<tr>
		<td class=body><br>
			<TABLE id="Table1" cellSpacing="0" cellPadding="0" width="100%" border="0">
				<TR>
					<TD vAlign="top" width="">
						<table width=100% class=panel>
							<tr><td class=heading2 colspan=2>%%LNG_ConversionInformation%%</td></tr>
							<tr><td class="FieldLabel"><span class="Required">*</span> %%LNG_ConversionName%%:</td><td><input type=text class="field250" id="conversionName"> %%LNG_HLP_ConversionName%%</td></tr>
							<tr id="amountRow"><td class="FieldLabel"><span class="Required">*</span> %%LNG_ConversionAmount%%:</td><td><input type=text class="field250" id="conversionAmount"> %%LNG_HLP_ConversionAmount%%</td></tr>
							<tr><td>&nbsp;</td><td><input type=button class=smallbutton value="%%LNG_GenerateCode%%" onClick="generateCode()"></td></tr>
						</table>
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
								name = document.getElementById("conversionName").value
								if (name == "") {
									alert("%%LNG_ConversionNameError%%")
									document.getElementById("conversionName").focus();
									document.getElementById("conversionName").select();
									return false;
								}

								amount = document.getElementById("conversionAmount").value

								if (amount == "") {
									alert("%%LNG_ConversionAmountError%%")
									document.getElementById("conversionAmount").focus();
									document.getElementById("conversionAmount").select();
									return false;
								}

								if (!IsNumeric(amount)) {
									alert("%%LNG_ConversionAmountError2%%")
									document.getElementById("conversionAmount").focus();
									document.getElementById("conversionAmount").select();
									return false;
								}

								var convvalue = "<!-- Start Conversion Code -->\n<img src=\"%%GLOBAL_TrackPointURL%%/tp.php?name=" + escape(name) + "&u=%%GLOBAL_TrackPointUserID%%";

								amount = document.getElementById("conversionAmount").value
								convvalue = convvalue + "&amount=" + amount;

								convvalue = convvalue + "\" width=\"1\" height=\"1\">\n<!-- End Conversion Code -->";
								document.getElementById("textArea").value = convvalue;

							}
						</script>

						<br>

						<table width=100% border=0 cellpadding=0 cellspacing=0>
							<tr class="heading3">
								<td>%%LNG_ConversionCode%%</td>
							</tr>
							<tr>
								<td>
									<textarea style="width:100%; height: 100px" id="textArea"></textarea>
								</td>
							</tr>
						</table>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
