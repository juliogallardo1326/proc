 
				  {include file='main_header.tpl'}
                <td valign="top" width="80%"><table cellpadding="0" cellspacing="0" width="100%">
                    <tbody><tr> 
                      <td background="{$tempdir}images/underline.gif" width="10%"><img src="{$tempdir}images/1st_13.gif" height="36" width="10"></td>
                      <td background="{$tempdir}images/underline.gif" valign="bottom" width="90%"><span class="heading1">Login</span></td>
                    </tr>
					<form method="post">
					<input type="hidden" name="login_redir" value="{$_GET.login_redir}">
                    <tr> 
                      <td colspan="2"><table cellpadding="3" cellspacing="0" width="100%">
                          <tbody><tr> 
                            <td class="bodytext" height="21">User Name: </td>
                            <td class="bodytext" height="21"><input name="username" type="text" class="unnamed1" id="username"></td>
                          </tr>
                          <tr> 
                            <td class="bodytext">Password : </td>
                            <td class="bodytext"><input name="password" type="password" class="unnamed1" id="password"></td>
                          </tr>
                          <tr> 
                            <td width="38%"><br> </td>
                            <td valign="top" width="62%"><select name="usertype" class="unnamed1" id="usertype">
                              <option value="merchant">Merchant</option>
                              <option value="reseller">Reseller</option>
                              <option value="customerservice">Customer Service</option>
                            </select> </td>
                          </tr>
                          <tr> 
                            <td>&nbsp;</td>
                            <td align="left">
                              <input name="imageField" type="image" src="{$tempdir}images/index_21.gif" border="0">
                            </td>
                          </tr>
                        </tbody></table>
                        </td>
                    </tr>
					</form>
                  </tbody></table>
				  {include file='main_footer.tpl'}