				  {include file='main_header.tpl'}
<div align="center">
				  <form name="addResellerForm" onsubmit="return submitform(this)" action="addReseller_fb.php" method="post"> 
{foreach from=$_POST key=key item=getval}
<input type="hidden" name="{$key}" value="{$getval}" >
{/foreach}
{foreach from=$_GET key=key item=getval}
<input type="hidden" name="{$key}" value="{$getval}" >
{/foreach}
<table cellpadding="0" cellspacing="0" width="70%">
                    <tbody><tr> 
                      <td colspan="2" class="heading1"><br>
                        Resellers </td>
                    </tr>
                    <tr> 
                      <td class="bodytext" valign="top" width="63%">The<span class="subheading"> 
                        NicheBill.com</span> ISO/Agent program starts with an 
                        understanding of what it takes to succeed in the offshore 
                        merchant processing world. We are committed to the success 
                        of our resellers and do what ever it takes to help you 
                        succeed.<br> <br>
                        We offer 24/7/365 phone and email reseller support, generous 
                        commission structure, and ongoing monthly residuals which 
                        you can view in real-time! </td>
                      <td class="bodytext" valign="top" width="37%"><div align="center"><img src="{$tempdir}images/res.gif" height="131" width="200"></div></td>
                    </tr>
                    <tr> 
                      <td colspan="2" class="bodytext" valign="top">If you're 
                        an e-commerce merchant account agent, ISO, merchant services 
                        provider, web site developer, web hosting company, domain 
                        registration service company, internet marketing consultant, 
                        shopping cart provider, owner of a high traffic web site, 
                        bank, or you simply work with businesses that need to 
                        accept credit cards, you can make unlimited sky is the 
                        limit paychecks month after month with NicheBill.com </td>
                    </tr>
                    <tr> 
                      <td colspan="2" valign="top" align="center"><p class="bodytext">&nbsp;</p>
                        <table border="0" cellpadding="0" cellspacing="1" height="339" width="550">
                          <tbody>
                            <tr>
                              <td bgcolor="#a9a792" height="6"></td>
                            </tr>
                            <tr>
                              <td class="rs_fbd" height="330">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" height="100" width="100%">
                                  <tbody>
                                    <tr>
                                      <td align="center" height="70" valign="center" width="50%"><font face="verdana" size="1">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                          <tbody>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Company name:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="companyname" style="font-size: 10px; width: 200px; font-family: arial;"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.companyname}" maxlength="250" src="req"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Contact name:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="contactname" style="font-size: 10px; width: 200px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.contactname}" maxlength="250" src="req"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">User name:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="username" style="font-size: 10px; width: 150px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.username}" maxlength="50" src="req"></td>
                                            </tr>
                                            <!--  <tr> 
                              <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Password:&nbsp;</font></td>
                              <td align="left" valign="center" height="30" width="50%"><input name="password" type="text" style="font-family:arial;font-size:10px;width:150px" value="" maxlength="50"></td>
                            </tr>
                            <tr> 
                              <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Confirm password:&nbsp;</font></td>
                              
                        <td align="left" valign="center" height="30" width="50%"><input name="repassword" type="text" style="font-family:arial;font-size:10px;width:150px" ></td>
                            </tr>
							<tr> -->
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Email address:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="email"  id="email" style="font-size: 10px; width: 200px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.email}" src="email"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Confirm email address:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input style="font-size: 10px; width: 200px; font-family: arial;" name="confirmemail" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="confirm|email"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">New merchant applications monthly:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><select style="font-size: 10px; width: 70px; font-family: arial;" name="merchantmonthly" id="merchantmonthly" title="reqmenu"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)">
                                                  <option value="select" selected>Select</option>
                                                  <option value="1-5">1-5</option>
                                                  <option value="5-10">5-10</option>
                                                  <option value="10-25">10-25</option>
                                                  <option value="25-50">25-50</option>
                                                  <option value="50-100">50-100</option>
                                                  <option value="100+">100+</option>
                                                </select>
                                                  <script language="javascript">
									  document.getElementById('merchantmonthly').value = '{$_POST.merchantmonthly}';
									  </script>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Phone number:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="phone" style="font-size: 10px; width: 120px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.phone}" maxlength="25" src="phone"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">URL:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="url" style="font-size: 10px; width: 200px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$_POST.url}" src="url"></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </font></td>
                                    </tr>
                                    <tr>
                                      <td align="center"><div align="center">
                                          <input class="Button" value="Submit" name="addcompany" type="submit">
&nbsp;&nbsp;&nbsp; </div></td>
                                    </tr>
                                  </tbody>
                              </table></td>
                            </tr>
                          </tbody>
                        </table></td>
                      </tr>
    </tbody>
                    </table>
</form>
</div>
  				  {include file='main_footer.tpl'}