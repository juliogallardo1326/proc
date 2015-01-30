<table border="0" cellpadding="0" cellspacing="0" width="50%" height="303">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Merchant Application</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>

      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
           <form action="merchantApplication.php?" method="post" onsubmit="return validation()" name="Frmcompany">
		  <table height="100%" width="70%" cellspacing="0" cellpadding="0" ><tr><td align="left">
		<?
			  if($showval = mysql_fetch_array($show_sql)){ 
			  ?>
				 <input type="hidden" name="username" value="<?=$showval[1]?>"></input>
                      <input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
			  <table width="400" border="0" cellpadding="0"  height="100">
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><hr><font face="verdana" size="1"><b>Company Information</b></font><hr></td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Company 
                          Name &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[3]?>"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">User 
                          Name &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><font face="verdana" size="1"><b>
                          <?=$showval[1]?>
                          </b></font></td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Address 
                          &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" name="address" value="<?=$showval[5]?>" style="font-family:arial;font-size:10px;width:240px"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">City 
                          &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" name="city" value="<?=$showval[6]?>" style="font-family:arial;font-size:10px;width:240px"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Country&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><select name="country"  style="font-family:arial;font-size:10px;width:240px" onchange="return validator()">
                            <option value="Afghanistan">Afghanistan </option>
                            <option value="Albania">Albania </option>
                            <option value="Algeria">Algeria </option>
                            <option value="Andorra">Andorra </option>
                            <option value="Angola">Angola</option>
                            <option value="Antigua and Barbuda">Antigua and Barbuda 
                            </option>
                            <option value="Argentina">Argentina </option>
                            <option value="Armenia">Armenia </option>
                            <option value="Australia">Australia </option>
                            <option value="Austria">Austria</option>
                            <option value="Azerbaijan">Azerbaijan </option>
                            <option value="Bahamas">Bahamas</option>
                            <option value="Bahrain">Bahrain </option>
                            <option value="Bangladesh">Bangladesh </option>
                            <option value="Barbados">Barbados </option>
                            <option value="Belarus">Belarus </option>
                            <option value="Belgium">Belgium </option>
                            <option value="Belize">Belize </option>
                            <option value="Benin">Benin </option>
                            <option value="Bhutan">Bhutan </option>
                            <option value="Bolivia">Bolivia </option>
                            <option value="Bosnia">Bosnia</option>
                            <option value="Botswana">Botswana </option>
                            <option value="Brazil">Brazil </option>
                            <option value="Brunei">Brunei </option>
                            <option value="Bulgaria">Bulgaria </option>
                            <option value="Burkina Faso">Burkina Faso </option>
                            <option value="Burundi">Burundi 
                            <option value="Cameroon">Cameroon </option>
                            <option value="Canada">Canada </option>
                            <option value="Cape Verde">Cape Verde </option>
                            <option value="Central African">Central African </option>
                            <option value="Chad">Chad </option>
                            <option value="Chile">Chile </option>
                            <option value="China">China </option>
                            <option value="Colombia">Colombia </option>
                            <option value="Comoros">Comoros</option>
                            <option value="Congo">Congo </option>
                            <option value="Costa Rica">Costa Rica </option>
                            <option value="Croatia">Croatia </option>
                            <option value="Cuba">Cuba </option>
                            <option value="Cyprus">Cyprus </option>
                            <option value="Czech Republic">Czech Republic </option>
                            <option value="Côte d'Ivoire">Côte d'Ivoire </option>
                            <option value="Denmark">Denmark</option>
                            <option value="Djibouti">Djibouti</option>
                            <option value="Dominica">Dominica</option>
                            <option value="Dominican Republic">Dominican Republic 
                            </option>
                            <option value="East Timor">East Timor</option>
                            <option value="Ecuador">Ecuador</option>
                            <option value="Egypt">Egypt </option>
                            <option value="El Salvador">El Salvador</option>
                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                            <option value="Eritrea">Eritrea</option>
                            <option value="Estonia">Estonia </option>
                            <option value="Ethiopia">Ethiopia </option>
                            <option value="Fiji">Fiji </option>
                            <option value="Finland">Finland </option>
                            <option value="France">France </option>
                            <option value="Gabon">Gabon </option>
                            <option value="Gambia">Gambia</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Germany">Germany </option>
                            <option value="Ghana">Ghana </option>
                            <option value="Greece">Greece </option>
                            <option value="Grenada">Grenada </option>
                            <option value="Guatemala">Guatemala </option>
                            <option value="Guinea">Guinea </option>
                            <option value="Guyana">Guyana </option>
                            <option value="Haiti">Haiti</option>
                            <option value="Honduras">Honduras </option>
                            <option value="Hungary">Hungary</option>
                            <option value="Iceland">Iceland</option>
                            <option value="India">India </option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Iran">Iran </option>
                            <option value="Iraq">Iraq </option>
                            <option value="Ireland">Ireland </option>
                            <option value="Israel">Israel </option>
                            <option value="Italy">Italy </option>
                            <option value="Jamaica">Jamaica </option>
                            <option value="Japan">Japan </option>
                            <option value="Jordan">Jordan </option>
                            <option value="Kazakhstan">Kazakhstan</option>
                            <option value="Kenya">Kenya </option>
                            <option value="Kiribati">Kiribati </option>
                            <option value="Korea">Korea</option>
                            <option value="Kuwait">Kuwait </option>
                            <option value="Kyrgyzstan">Kyrgyzstan </option>
                            <option value="Laos">Laos </option>
                            <option value="Latvia">Latvia </option>
                            <option value="Lebanon">Lebanon </option>
                            <option value="Lesotho">Lesotho</option>
                            <option value="Liberia">Liberia </option>
                            <option value="Libya">Libya </option>
                            <option value="Liechtenstein">Liechtenstein </option>
                            <option value="Lithuania">Lithuania </option>
                            <option value="Luxembourg">Luxembourg </option>
                            <option value="Macedonia">Macedonia</option>
                            <option value="Madagascar">Madagascar </option>
                            <option value="Malawi">Malawi </option>
                            <option value="Malaysia">Malaysia </option>
                            <option value="Maldives">Maldives </option>
                            <option value="Mali">Mali </option>
                            <option value="Malta">Malta </option>
                            <option value="Marshall Islands">Marshall Islands 
                            </option>
                            <option value="Mauritania">Mauritania </option>
                            <option value="Mauritius">Mauritius </option>
                            <option value="Mexico">Mexico </option>
                            <option value="Micronesia">Micronesia</option>
                            <option value="Moldova">Moldova </option>
                            <option value="Monaco">Monaco </option>
                            <option value="Mongolia">Mongolia </option>
                            <option value="Morocco">Morocco </option>
                            <option value="Mozambique">Mozambique </option>
                            <option value="Myanmar">Myanmar </option>
                            <option value="Namibia">Namibia </option>
                            <option value="Nauru">Nauru </option>
                            <option value="Nepal">Nepal </option>
                            <option value="Netherlands">Netherlands </option>
                            <option value="New Zealand">New Zealand </option>
                            <option value="Nicaragua">Nicaragua </option>
                            <option value="Niger">Niger </option>
                            <option value="Nigeria">Nigeria </option>
                            <option value="Norway ">Norway </option>
                            <option value="Oman">Oman </option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Palau">Palau </option>
                            <option value="Panama">Panama </option>
                            <option value="Papua New Guinea">Papua New Guinea 
                            </option>
                            <option value="Paraguay">Paraguay </option>
                            <option value="Peru">Peru </option>
                            <option value="Philippines">Philippines </option>
                            <option value="Poland">Poland </option>
                            <option value="Portugal">Portugal </option>
                            <option value="Qatar">Qatar </option>
                            <option value="Romania">Romania </option>
                            <option value="Russia">Russia </option>
                            <option value="Rwanda">Rwanda </option>
                            <option value="Saint Kitts">Saint Kitts </option>
                            <option value="Saint Lucia">Saint Lucia</option>
                            <option value="Saint Vincent">Saint Vincent </option>
                            <option value="Samoa">Samoa </option>
                            <option value="San Marino">San Marino</option>
                            <option value="Sao Tome and Principe">Sao Tome and 
                            Principe </option>
                            <option value="Saudi Arabia ">Saudi Arabia </option>
                            <option value="Senegal">Senegal </option>
                            <option value="Serbia and Montenegro">Serbia and Montenegro 
                            </option>
                            <option value="Seychelles ">Seychelles </option>
                            <option value="Sierra Leone">Sierra Leone </option>
                            <option value="Singapore">Singapore </option>
                            <option value="Slovakia">Slovakia </option>
                            <option value="Slovenia">Slovenia</option>
                            <option value="Solomon Islands">Solomon Islands </option>
                            <option value="Somalia">Somalia </option>
                            <option value="South Africa">South Africa </option>
                            <option value="Spain">Spain </option>
                            <option value="Sri Lanka">Sri Lanka </option>
                            <option value="Sudan">Sudan </option>
                            <option value="Suriname">Suriname </option>
                            <option value="Swaziland">Swaziland </option>
                            <option value="Sweden">Sweden </option>
                            <option value="Switzerland">Switzerland </option>
                            <option value="Syria">Syria </option>
                            <option value="Taiwan">Taiwan </option>
                            <option value="Tajikistan">Tajikistan </option>
                            <option value="Tanzania">Tanzania </option>
                            <option value="Thailand">Thailand </option>
                            <option value="Togo">Togo </option>
                            <option value="Tonga">Tonga</option>
                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                            <option value="Tunisia">Tunisia </option>
                            <option value="Turkey">Turkey </option>
                            <option value="Turkmenistan">Turkmenistan </option>
                            <option value="Tuvalu">Tuvalu </option>
                            <option value="Uganda">Uganda </option>
                            <option value="Ukraine">Ukraine </option>
                            <option value="United Arab Emirates">United Arab Emirates </option> 
                            <option value="United Kingdom">United Kingdom </option>
                            <option value="United States">United States </option>
                            <option value="Uruguay">Uruguay </option>
                            <option value="Uzbekistan">Uzbekistan </option>
                            <option value="Vanuatu">Vanuatu </option>
                            <option value="Vatican City">Vatican City </option>
                            <option value="Venezuela">Venezuela </option>
                            <option value="Vietnam">Vietnam</option>
                            <option value="Western Sahara">Western Sahara </option>
                            <option value="Yemen">Yemen </option>
                            <option value="Zambia">Zambia </option>
                            <option value="Zimbabwe">Zimbabwe </option>
                          </select> 
						  <script language="javascript">
					     	document.Frmcompany.country.value='<?=$showval[8]?>';	
						  </script>
						</td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">State&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"> <select name="state"  style="font-family:arial;font-size:10px;width:240px">
                            <option value="Alabama">Alabama</option>
                            <option value="Alaska"> Alaska</option>
                            <option value="Arizona"> Arizona</option>
                            <option value="Arkansas"> Arkansas</option>
                            <option value="California"> California</option>
                            <option value="Colorado"> Colorado</option>
                            <option value="Connecticut"> Connecticut</option>
                            <option value="Delaware"> Delaware</option>
                            <option value="Florida"> Florida</option>
                            <option value="Georgia"> Georgia</option>
                            <option value="Hawaii"> Hawaii</option>
                            <option value="Idaho"> Idaho </option>
                            <option value="Illinois"> Illinois</option>
                            <option value="Indiana"> Indiana</option>
                            <option value="Iowa"> Iowa</option>
                            <option value="Kansas"> Kansas</option>
                            <option value="Kentucky"> Kentucky </option>
                            <option value="Louisiana"> Louisiana </option>
                            <option value="Maine"> Maine</option>
                            <option value="Maryland"> Maryland</option>
                            <option value="Massachusetts"> Massachusetts</option>
                            <option value="Michigan"> Michigan</option>
                            <option value="Minnesota"> Minnesota</option>
                            <option value="Mississippi"> Mississippi</option>
                            <option value="Missouri"> Missouri</option>
                            <option value="Montana"> Montana</option>
                            <option value="Nebraska"> Nebraska</option>
                            <option value="Nevada"> Nevada</option>
                            <option value="New Hampshire"> New Hampshire</option>
                            <option value="New Jersey"> New Jersey</option>
                            <option value="New Mexico"> New Mexico</option>
                            <option value="New York"> New York</option>
                            <option value="North Carolina"> North Carolina</option>
                            <option value="North Dakota"> North Dakota</option>
                            <option value="Ohio"> Ohio</option>
                            <option value="Oklahoma"> Oklahoma </option>
                            <option value="Oregon"> Oregon</option>
                            <option value="Pennsylvania"> Pennsylvania</option>
                            <option value="Rhode Island"> Rhode Island</option>
                            <option value="South Carolina"> South Carolina</option>
                            <option value="South Dakota"> South Dakota</option>
                            <option value="Tennessee"> Tennessee</option>
                            <option value="Texas"> Texas</option>
                            <option value="Utah"> Utah</option>
                            <option value="Vermont"> Vermont</option>
                            <option value="Virginia"> Virginia</option>
                            <option value="Washington"> Washington</option>
                            <option value="West Virginia"> West Virginia</option>
                            <option value="Wisconsin"> Wisconsin</option>
                            <option value="Wyoming"> Wyoming </option>
                          </select> 
						 <script language="javascript">
							 document.Frmcompany.state.value='<?=$showval[7]?>';
						</script> 
						</td>
                      </tr>
                      <input type="hidden" name="company" value="company"></input>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Other 
                          State&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" name="ostate" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[12]?>"></input>
                        </td>
                      </tr>
                      <script language="javascript">
				if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
					document.Frmcompany.ostate.disabled= true;
					document.Frmcompany.ostate.value= "";
					document.Frmcompany.state.disabled = false;
				} else {
					document.Frmcompany.state.disabled = true;
					document.Frmcompany.state.value= "";
					document.Frmcompany.ostate.disabled= false;
				}
			</script>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Zipcode&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" name="zipcode" value="<?=$showval[9]?>" style="font-family:arial;font-size:10px;width:140px"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Phone 
                          Number &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="25" name="phonenumber" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[4]?>"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><hr><font face="verdana" size="1"><b>Web Site Information</b></font><hr></td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Email 
                          &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[10]?>"></input>
                        </td>
                      </tr>                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">URL 
                          &nbsp;&nbsp;</font></td>
                        <td align="left" height="50" width="50%"><input type="text" name="url1" value="<?=$showval[43]?>" style="font-family:arial;font-size:10px;width:230px">
                          <br>
                          <input type="text" name="url2" value="<?=$showval[44]?>" style="font-family:arial;font-size:10px;width:230px">
                          <br>
                          <input type="text" name="url3" value="<?=$showval[45]?>" style="font-family:arial;font-size:10px;width:230px"></td>
                      </tr>
                      
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><hr><font face="verdana" size="1"><b>Processing Information</b></font><hr></td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Projected monthly sales&nbsp;&nbsp; volume $&nbsp;&nbsp;</font></td>
                        <td align="left" valign="middle" height="30" width="50%"><input type="text" maxlength="100" name="volume" style="font-family:arial;font-size:10px;width:80px" value="<?=$showval[30]?>"></input>
                        </td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Average ticket&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="10" name="avgticket" style="font-family:arial;font-size:10px;width:80px" value="<?=$showval[38]?>"></input>
                        </td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Charge back %&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="10" name="chargeper" style="font-family:arial;font-size:10px;width:80px" value="<?=$showval[39]?>"></input>
                        </td>
                      </tr>
					<tr>
                        <td align="left" valign="middle" height="30" width="50%"><font face="verdana" size="1">Merchant 
                          Type &nbsp;&nbsp;</font></td>
						  <td align="left" height="30"  width="50%" valign="middle"><select name="rad_order_type" style="font-family:arial;font-size:10px;width:100px">
							<option value="">Select</option>
							<option value="ecom">General Ecommerce</option>
							<option value="trvl">Travel</option>
							<option value="phrm">Pharmacy</option>
							<option value="game">Gaming</option>
							<option value="adlt">Adult</option>
							<option value="tele">Telemarketing</option>
							<option value="pmtg">Gateway</option>
							<!--option value="crds">Card swipe</option-->
						  </select></td>
						<script language="javascript">
							 document.Frmcompany.rad_order_type.value='<?=$showval[27]?>';	
						</script>
                      </tr>	
					  <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Previous processing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><select name="prepro" style="font-family:verdana;font-size:10px;width:50px">
						<option value="">&nbsp;</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
						</select>
                        </td>
						 <script language="javascript">
							 document.Frmcompany.prepro.value='<?=$showval[40]?>';
						</script> 
                      </tr>	
					  <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Recurring billing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><select name="rebill" style="font-family:verdana;font-size:10px;width:50px">
						<option value="">&nbsp;</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
						</select></input>
                        </td>
 						<script language="javascript">
							 document.Frmcompany.rebill.value='<?=$showval[41]?>';
						</script>                       
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Currently Processing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><select name="currpro" style="font-family:verdana;font-size:10px;width:50px">
                            <option value="">&nbsp;</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                          </select>
						</td>
 						<script language="javascript">
							 document.Frmcompany.currpro.value='<?=$showval[42]?>';
						</script>                       
						</tr>

                        <input type="hidden" name="company" value="company"></input>
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>
						&nbsp;&nbsp;<input type="image" id="modifycompany" src="images/submit.jpg"></input>
                        </td>
                      </tr>
                    </table>
		<?
		  }
					  ?>
		  </td></tr></table></form>
	</td>
      </tr>
		<tr>
		<td width="1%"><img src="images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="images/menubtmright.gif"></td>
		</tr>
    </table>