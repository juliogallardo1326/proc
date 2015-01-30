				  {include file='main_header.tpl'}
<table cellpadding="0" cellspacing="0" width="100%">
                    <tbody><tr> 
                      <td width="100%" class="heading1"><br>                        </td>
                    </tr>
                    <tr> 
                      <td valign="top"><p class="bodytext">&nbsp;</p>
                        <table style="border: 1px solid white;" align="center" border="0" cellpadding="0" cellspacing="0" width="87%">
                          <tbody>
                            <tr> 
                              <td class="subheading" align="center" valign="center"> 
                                <div align="left"> 
                                  <p><font color="#000000"><strong>TO GET STARTED, 
                                    PLEASE COMPLETE THE FOLLOWING PRE APPLICATION 
                                    FORM.</strong></font> <br>
                                    <br>
                                  </p>
                                </div></td>
                            </tr>
                            <tr> 
                              <td class="lgnbd"> <form name="Frmcompany" onsubmit="return submitform(this)" action="add_merchant.php" method="post">
{foreach from=$_POST key=key item=getval}
<input type="hidden" name="{$key}" value="{$getval}" >
{/foreach}
{foreach from=$_GET key=key item=getval}
<input type="hidden" name="{$key}" value="{$getval}" >
{/foreach}

                                  <table border="0" cellpadding="0" cellspacing="0" width="92%">
                                    <tbody><tr> 
                                      <td class="subheading" bgcolor="#a9a792">&nbsp;</td>
                                      <td class="subheading" bgcolor="#a9a792"><font color="#ffffff">Application 
                                        Form</font></td>
                                    </tr>
                                    <tr> 
                                      <td colspan="2" valign="top"><div align="center">&nbsp; 
                                          <span class="subheading"><font style="margin-left: 30px;"><b>Please 
                                          avoid free, limited, or unreliable email 
                                          services.<br>
                                          <br>
                                          </b></font></span></div></td>
                                    </tr>
                                    <tr> 
                                      <td valign="top" width="1%">&nbsp;</td>
                                      <td valign="top" width="99%"><table style="border: 1px solid rgb(80, 134, 15); background-color: white;" align="center" border="0" cellpadding="0" width="410">
                                          <tbody>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="147">Company Name &nbsp;</td>
                                              <td align="left" height="30" width="249"><input name="newcompany" style="font-size: 10px; width: 240px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.newcompany}" maxlength="100" src="req"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="147">Company Website &nbsp;</td>
                                              <td align="left" height="30" width="249"><input name="url1" style="font-size: 10px; width: 240px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.newcompany}" maxlength="100" src="url"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="147">User Name &nbsp;</td>
                                              <td align="left" height="30" width="249"><input name="username" style="font-size: 10px; width: 240px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.username}" maxlength="30" src="req"></td>
                                            </tr>
                                            <!--<tr>
                              <td align="right" valign="center" height="30" width="147"><font face="verdana" size="1">Password 
                                &nbsp;</font></td>
                              <td align="left" height="30" width="249">
                                <input type="text" maxlength="30" name="password" style="font-family:arial;font-size:10px;width:240px"></td>
                      </tr>
					 <tr>
                              <td align="right" valign="center" height="30" width="147"><font face="verdana" size="1">Confirm 
                                Password &nbsp;</font></td>
                              <td align="left" height="30" width="249">
                                <input type="text" maxlength="30" name="password1" style="font-family:arial;font-size:10px;width:240px"></td>
                      </tr>  
					   <tr> -->
                                            <tr>
                                              <td class="bodytext" align="right" height="19" valign="center" width="147">Email &nbsp;</td>
                                              <td align="left" height="19" width="249"><input name="email" id="email" style="font-size: 10px; width: 240px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.email}" maxlength="100" src="email"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="147">Confirm Email &nbsp;</td>
                                              <td align="left" height="30" width="249"><input style="font-size: 10px; width: 240px; font-family: arial;" maxlength="100" name="confirmation_email" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="confirm|email"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="147">Phone Number &nbsp;</td>
                                              <td align="left" height="30" width="249"><input name="phonenumber" id="phonenumber" style="font-size: 10px; width: 240px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.phonenumber}" maxlength="100" src="phone"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="147">Instant Messenger &nbsp;</td>
                                              <td align="left" height="30" width="249"><select onChange="document.getElementById('cd_contact_im').value='';" name="cd_contact_im_type" id="cd_contact_im_type" style="font-family:arial;font-size:10px;">
                    <option value="" selected>Select</option>
                    <option value="AIM: ">AIM</option>
                    <option value="ICQ: ">ICQ</option>
                    <option value="Yahoo: ">Yahoo</option>
                    <option value="MSN: ">MSN</option>
                    <option value="Other: ">Other</option>
                  </select>
                  <input type="text" src='req' maxlength="100" id="cd_contact_im" name="cd_contact_im" style="font-family:arial;font-size:10px;width:175px" value="{$_POST.cd_contact_im}"></td>
                                            </tr>
                                          </tbody>
                                      </table></td>
                                    </tr>
                                    <tr> 
                                      <td valign="top">&nbsp;</td>
                                      <td valign="top">&nbsp;</td>
                                    </tr>
                                    <tr> 
                                      <td valign="top">&nbsp;</td>
                                      <td valign="top"><table style="border: 1px solid rgb(80, 134, 15); background-color: white;" align="center" border="0" cellpadding="0" height="100" width="410">
                                          <tbody>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="153">Merchant Type &nbsp;</td>
                                              <td align="left" height="30" valign="center" width="252"><select name="rad_order_type" id="rad_order_type" style="font-size: 10px; width: 100px; font-family: arial;" title="reqmenu"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)">
                                                  <option value="select">Select</option>
                                                  <option value="ecom">Ecommerce</option>
                                                  <option value="trvl">Travel</option>
                                                  <option value="phrm">Pharmacy</option>
                                                  <option value="game">Gaming</option>
                                                  <option value="adlt">Adult</option>
                                                  <option value="tele">Telemarketing</option>
                                                </select>
                                                  <script language="javascript">
											  document.getElementById('rad_order_type').value = '{$_POST.rad_order_type}';
											  </script>                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="153">Expected Monthly Volume &nbsp;</td>
                                              <td align="left" height="30" width="252"><select name="merchant_volume" id="merchant_volume" style="font-size: 10px; width: 100px; font-family: arial;"title="reqmenu"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)">
                                                  <option value="select" selected>Select</option>
                                                  <option value="0-$5,000">0-$5,000</option>
                                                  <option value="$5,000-$10,000">$5,000-$10,000</option>
                                                  <option value="$10,000-$25,000">$10,000-$25,000</option>
                                                  <option value="$25,000-$50,000">$25,000-$50,000</option>
                                                  <option value="$50,000-$100,000">$50,000-$100,000</option>
                                                  <option value="$100,000-$250,000">$100,000-$250,000</option>
                                                  <option value="$250,000-$500,000">$250,000-$500,000</option>
                                                  <option value="$500,000-1MIL">$500,000-1MIL</option>
                                                  <option value="1Mil-2Mil">1Mil-2Mil</option>
                                                  <option value="2Mil-5Mil">2Mil-5Mil</option>
                                                  <option value="5 Mil+">5 Mil+</option>
                                                </select>
                                                  <script language="javascript">
											  document.getElementById('merchant_volume').value = '{$_POST.merchant_volume}';
											  </script>                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="153">How you hear about us? &nbsp;</td>
                                              <td align="left" height="30" valign="center" width="252"><select name="how_about_us" id="how_about_us" style="font-size: 10px; width: 100px; font-family: arial;" title="reqmenu"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)">
                                                  <option value="select" selected>Select</option>
                                                  <option value="http://www.about.com">About.com</option>
                                                  <option value="http://www.altavista.com">AltaVista</option>
                                                  <option value="http://www.alltheweb.com">AllTheWeb.com</option>
                                                  <option value="http://www.aolsearch.aol.com">AOL Search</option>
                                                  <option value="http://www.askjeeves.com">Ask Jeeves</option>
                                                  <option value="http://www.britannica.com">Britannica.com</option>
                                                  <option value="http://www.excite.com">Excite</option>
                                                  <option value="http://www.google.com">Google</option>
                                                  <option value="http://www.hotbot.com">HotBot</option>
                                                  <option value="http://www.inktomi.com">Inktomi</option>
                                                  <option value="http://www.iwon.com">iWon</option>
                                                  <option value="http://www.looksmart.com">LookSmart</option>
                                                  <option value="http://www.lycos.com">Lycos</option>
                                                  <option value="http://www.search.msn.com">MSN Search</option>
                                                  <option value="http://www.search.netscape.com">Netscape Search</option>
                                                  <option value="http://www.overture.com">Overture</option>
                                                  <option value="http://www.searchking.com">SearchKing</option>
                                                  <option value="http://www.teoma.com">Teoma</option>
                                                  <option value="http://www.webcrawler.com">WebCrawler</option>
                                                  <option value="http://www.wisenut.com">WiseNut</option>
                                                  <option value="http://www.yahoo.com">Yahoo</option>
                                                  <option value="rsel">Reseller</option>
                                                  <option value="other">Others</option>
                                                </select>
                                                  <script language="javascript">
											  document.getElementById('how_about_us').value = '{$_POST.how_about_us}';
											  </script>                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="153">If reseller/others specify &nbsp; details&nbsp;&nbsp;</td>
                                              <td align="left" height="30" width="252"><input name="reseller" id="reseller" style="font-size: 10px; width: 100px; font-family: arial;" value="{$_POST.reseller}" maxlength="75"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="153">Time Zone&nbsp;</td>
                                              <td align="left" height="30" width="252">              
				<select name="timezone" style="width:252;">
                <option value="-7.0" selected>Select Time Zone Below</option>
                <option value="-5.0">U.S. Eastern</option>
                <option value="-6.0">U.S. Central</option>

                <option value="-7.0">U.S. Mountain</option>
                <option value="-8.0">U.S. Pacific</option>
                <option value="-9.0">U.S. Alaska</option>
                <option value="-10.0">U.S. Hawaii</option>
                <option value="">----------------</option>
                <option value="0.0">GMT +00:00 Britain, Ireland, Portugal, Western 
                Africa </option>

                <option value="0.5">GMT +00:30 </option>
                <option value="1.0">GMT +01:00 Western Europe, Central Africa</option>
                <option value="1.5">GMT +01:30 </option>
                <option value="2.0">GMT +02:00 Eastern Europe, Eastern Africa</option>
                <option value="2.5">GMT +02:30 </option>
                <option value="3.0">GMT +03:00 Russia, Saudi Arabia</option>

                <option value="3.5">GMT +03:30 </option>
                <option value="4.0">GMT +04:00 Arabian</option>
                <option value="4.5">GMT +04:30 </option>
                <option value="5.0">GMT +05:00 West Asia, Pakistan</option>
                <option value="5.5">GMT +05:30 India</option>
                <option value="6.0">GMT +06:00 Central Asia</option>

                <option value="6.5">GMT +06:30 </option>
                <option value="7.0">GMT +07:00 Bangkok, Hanoi, Jakarta</option>
                <option value="7.5">GMT +07:30 </option>
                <option value="8.0">GMT +08:00 China, Singapore, Taiwan</option>
                <option value="8.5">GMT +08:30 </option>
                <option value="9.0">GMT +09:00 Korea, Japan</option>

                <option value="9.5">GMT +09:30 Central Australia</option>
                <option value="10.0">GMT +10:00 Eastern Australia</option>
                <option value="10.5">GMT +10:30 </option>
                <option value="11.0">GMT +11:00 Central Pacific</option>
                <option value="11.5">GMT +11:30 </option>
                <option value="12.0">GMT +12:00 Fiji, New Zealand</option>

                <option value="-12.0">GMT -12:00 Dateline </option>
                <option value="-11.5">GMT -11:30 </option>
                <option value="-11.0">GMT -11:00 Samoa</option>
                <option value="-10.5">GMT -10:30 </option>
                <option value="-10.0">GMT -10:00 Hawaiian</option>
                <option value="-9.5">GMT -09:30 </option>

                <option value="-9.0">GMT -09:00 Alaska/Pitcairn Islands</option>
                <option value="-8.5">GMT -08:30 </option>
                <option value="-8.0">GMT -08:00 US/Canada/Pacific</option>
                <option value="-7.5">GMT -07:30 </option>
                <option value="-7.0">GMT -07:00 US/Canada/Mountain</option>
                <option value="-6.5">GMT -06:30 </option>

                <option value="-6.0">GMT -06:00 US/Canada/Central</option>
                <option value="-5.5">GMT -05:30 </option>
                <option value="-5.0">GMT -05:00 US/Canada/Eastern, Colombia, Peru</option>
                <option value="-4.5">GMT -04:30 </option>
                <option value="-4.0">GMT -04:00 Bolivia, Western Brazil, Chile, 
                Atlantic</option>
                <option value="-3.5">GMT -03:30 Newfoundland</option>

                <option value="-3.0">GMT -03:00 Argentina, Eastern Brazil, Greenland</option>
                <option value="-2.5">GMT -02:30 </option>
                <option value="-2.0">GMT -02:00 Mid-Atlantic</option>
                <option value="-1.5">GMT -01:30 </option>
                <option value="-1.0">GMT -01:00 Azores/Eastern Atlantic</option>
                <option value="-0.5">GMT -00:30 </option>

              </select></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2" align="center" height="30" valign="center" width="404"><div align="center">
                                                  <input class="Button" value="Submit" name="addcompany" type="submit">
                                              </div></td>
                                            </tr>
                                            <!--    <tr>
                        <td colspan="2" height="50" align="justify" valign="bottom"><font face="Verdana" size="1" color="#FF6600">"ALL 
                          YOUR DATA ENTRIES ARE ENCRYPTED AND TRANFERRED WITH 
                          SSL THROUGH OUR SECURED SERVER! SO YOU WILL BE SURE, 
                          THAT ONLY etelegate.com CAN READ YOUR CONFIDENTIAL DATA"</font></td>
                      </tr> -->
                                          </tbody>
                                      </table>
                                        <table style="border: 1px solid rgb(80, 134, 15); background-color: white;" align="center" border="0" cellpadding="0" height="100" width="410">
                                          <tbody>
                                          </tbody>
                                          </table>
                                      </td>
                                    </tr>
                                    <tr> 
                                      <td valign="top">&nbsp;</td>
                                      <td valign="top">&nbsp;</td>
                                    </tr>
                                  </tbody></table>

                                </form></td>
                                      </tr>
                                    </tbody>
                                  </table></td>
                                      </tr>
                                    </tbody>
                                  </table>
  				  {include file='main_footer.tpl'}