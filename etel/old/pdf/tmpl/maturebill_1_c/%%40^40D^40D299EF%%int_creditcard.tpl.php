<?php /* Smarty version 2.6.9, created on 2006-04-02 00:53:46
         compiled from int_creditcard.tpl */ ?>
<!--Main-->

<form id="processingFrm" action="<?php echo $this->_tpl_vars['rootdir']; ?>
/secure/FinalProcessing.php" method="post" onsubmit="return submitOrder(this)">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="20" class="fieldname" align="left" style="font-size:24px; font-weight:bold"><?php echo $this->_config[0]['vars']['GL_Step']; ?>
 #2</td>
  </tr>
      <tr>
        <td height="10" colspan="2" valign="middle" class="normaltext"><span class="large"><?php echo $this->_config[0]['vars']['OP_BilInfoAsAppears']; ?>
</span></td>
      </tr>
      <tr>
        <td bgcolor="#009999"><img src="https://www.MatureBill.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
        <td align="right" bgcolor="#009999"><img src="https://www.MatureBill.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
      </tr>
    <tr>
      <td align="left" valign="top">&nbsp;</td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td width="60%" align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['OP_FirstLastName']; ?>
</td>
            <td><input type="text" name="firstname" size="19" maxlength="75" value="<?php echo $this->_tpl_vars['str_firstname']; ?>
"     src="req">
              <input type="text" name="lastname" size="19" maxlength="75" value="<?php echo $this->_tpl_vars['str_lastname']; ?>
"     src="req">
            </td>
          </tr>
          <?php if ($this->_tpl_vars['isPasswordManagement']): ?>
          <tr>
            <td width="25%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_ChooseUser']; ?>
 </td>
            <td><input name="td_username" type="text" id="td_username" value="<?php echo $this->_tpl_vars['str_username']; ?>
" size="30" maxlength="30"     src="req">
              <strong><span class="terms"> </span></strong></td>
          </tr>
          <tr>
            <td width="25%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_ChoosePass']; ?>
 </td>
            <td><input name="td_password" type="password" id="td_password" value="<?php echo $this->_tpl_vars['str_password']; ?>
" size="30" maxlength="30"    src="req">
              <strong><span class="terms"> </span></strong></td>
          </tr>
          <?php endif; ?>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_Address']; ?>
</td>
            <td><input type="text" name="address" size="32" maxlength="100" value="<?php echo $this->_tpl_vars['str_address']; ?>
"     src="req">
            </td>
          </tr>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_Country']; ?>
</td>
            <td><select name="country" id="country" style="font-size:11px;width:180px;font-height:10px;font-face:verdana;" onChange="updatevalid(this);updateCountry(this);" title="reqmenu" >
                
              <?php echo $this->_tpl_vars['opt_Countrys']; ?>

            
              </select>
            </td>
          </tr>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_City']; ?>
</td>
            <td><input type="text" name="city" size="32" maxlength="50" value="<?php echo $this->_tpl_vars['str_city']; ?>
"    src="req">
            </td>
          </tr>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_State']; ?>
</td>
            <td><select name="state" id="state" style="width:140px;font-height:10px;font-face:verdana;" title=""    >
                
              <?php echo $this->_tpl_vars['opt_States']; ?>

            
              </select>
            </td>
          </tr>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_OtherState']; ?>
</td>
            <td><input name="otherstate" type="text" value="<?php echo $this->_tpl_vars['str_otherstate']; ?>
" size="25">
            </td>
          </tr>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_Zipcode']; ?>
</td>
            <td><input name="zipcode" type="text" id="zipcode"     value="<?php echo $this->_tpl_vars['str_zipcode']; ?>
" size="15" maxlength="15" src="req">
            </td>
          </tr>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_PhoneNumber']; ?>
</td>
            <td><input type="text" name="telephone" size="15" maxlength="15" value="<?php echo $this->_tpl_vars['str_phonenumber']; ?>
"     src="num"></td>
          </tr>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_YourEmail']; ?>
</td>
            <td><input type="text" id="email" name="email" size="32" maxlength="100" value="<?php echo $this->_tpl_vars['str_emailaddress']; ?>
"    src="email">
            </td>
          </tr>
          <tr>
            <td width="40%" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_ConfirmEmail']; ?>
</td>
            <td><input type="text" name="emailconfirm" id="emailconfirm" size="32" value="<?php echo $this->_tpl_vars['str_emailaddress']; ?>
" maxlength="100"    src="confirm|email">
            </td>
          </tr>
        <tr>
          <td colspan="2" valign="top" class="fieldname"><?php echo $this->_config[0]['vars']['GL_EmailWarning']; ?>
</td>
          </tr>
        </table></td>
      <td width="40%" align="right" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
          <tr>
            <td width="36%" valign="top" class="fieldname1"><span class="fieldname"><?php echo $this->_config[0]['vars']['OP_CardNumber']; ?>
</span></td>
            <td class="style1"><input type="text" name="number" size="17" maxlength="16"   onChange="setCCType(this);"  src="creditcard">
&nbsp;<br>
              <?php echo $this->_config[0]['vars']['OP_CCExample']; ?>
<a href="#" class="style1" onClick='javascript:window.open("https://www.MatureBill.com/images/creditcard.gif","","width=500,height=350")' ></a></span><span class="fieldname1"><a href="#" class="style1" onClick='javascript:window.open("https://www.MatureBill.com/images/creditcard.gif","","width=500,height=350")' ><br>
              CVV2</a>&nbsp;
              <input type="text" name="cvv2" size="3" maxlength="3"     src="minlen|2">
            </td>
          </tr>
          <tr>
            <td class="style1"><input type="text" name="number" size="17" maxlength="16"   onChange="setCCType(this);"  src="creditcard">
&nbsp;<br>
              <?php echo $this->_config[0]['vars']['OP_CCExample']; ?>
<a href="#" class="style1" onClick='javascript:window.open("https://www.etelegate.com/images/creditcard.gif","","width=500,height=350")' ></a></span><span class="fieldname1"><a href="#" class="style1" onClick='javascript:window.open("https://www.etelegate.com/images/creditcard.gif","","width=500,height=350")' ><br>
              CVV2</a>&nbsp;
              <input type="text" name="cvv2" size="3" maxlength="3"     src="minlen|2">
            </td>
          </tr>
          <tr>
            <td width="36%" valign="top" class="fieldname1"><span class="fieldname"><?php echo $this->_config[0]['vars']['OP_ExpDate']; ?>
</span></td>
            <td><script> mmsel(); </script></td>
          </tr>
          <tr>
            <td width="36%" valign="top" class="fieldname1"><span class="fieldname"><?php echo $this->_config[0]['vars']['OP_BankPhone']; ?>
</span></td>
            <td class="style1"><input name="td_bank_number" type="text" id="td_bank_number"     size="17" src="phone">
              <br>
              <?php echo $this->_config[0]['vars']['OP_BankPhoneFound']; ?>
</td>
          </tr>
          <tr>
            <td width="36%" valign="top" class="fieldname1"><span class="fieldname"><?php echo $this->_config[0]['vars']['GL_Charge']; ?>
</span></td>
            <td valign="middle" class="normaltext"><font size="2" face="Verdana" color="#000000"> $<?php echo $this->_tpl_vars['TotalCharge']; ?>

              <label name="txt_amount" id="txt_amount">(USD)</label>
              </font></td>
          </tr>
          <tr>
            <td valign="top" class="fieldname"><span class="fieldname1"><?php echo $this->_config[0]['vars']['OP_BillDesc']; ?>
</span></td>
            <td><font size="2" face="Verdana">
			<pre style="font-size:10px; font-family:Verdana, Arial, Helvetica, sans-serif; "><label id="bill_desc"><?php echo $this->_tpl_vars['bill_des_visa']; ?>
</label></pre>
              </font></td>
          </tr>
          <tr>
            <td valign="top" class="fieldname"><span class="fieldname1"><?php echo $this->_config[0]['vars']['GL_YourIp']; ?>
</span></td>
            <td><font size="1" face="Verdana"> <?php echo $this->_tpl_vars['str_ipaddress']; ?>
 </font></td>
          </tr>
        </table></td>
    </tr>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "int_subinfo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </table>
  <script language="javascript">
	setupForm(document.getElementById('processingFrm'));
	updateCountry(document.getElementById('country'));
</script>
  <!--End Main-->
</form>