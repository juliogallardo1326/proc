<?php /* Smarty version 2.6.9, created on 2007-06-28 09:31:41
         compiled from main_applynow_form.tpl */ ?>

<form name="Frmcompany" id="Frmcompany" onsubmit="return submitform(this)" action="add_merchant.php?submit=1" method="post">
  <table style="border: 1px solid rgb(80, 134, 15); background-color: white;" align="center" border="0" cellpadding="0" width="410">
    <tbody>
      <tr>
        <td colspan="2" bgcolor="#a9a792" class="subheading"><div align="center"><font color="#ffffff">Application 
            Form</font></div></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><div align="center">&nbsp; <span class="subheading"><font style="margin-left: 30px;"><b>Please 
            avoid free, limited, or unreliable email 
            services.<br>
            <br>
            </b></font></span></div></td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">Company Name &nbsp;</td>
        <td align="left" height="30" width="252"><input name="newcompany" title="Company Name" style="font-size: 10px; width: 240px; font-family: arial;"    value="<?php echo $_POST['newcompany']; ?>
" maxlength="100" valid="req"></td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">Company Website &nbsp;</td>
        <td align="left" height="30" width="252"><input name="url1" title="Company Website" style="font-size: 10px; width: 240px; font-family: arial;"    value="<?php echo $_POST['url1']; ?>
" maxlength="100" valid="url"></td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">User Name &nbsp;</td>
        <td align="left" height="30" width="252"><input name="username" title="UserName" style="font-size: 10px; width: 240px; font-family: arial;"    value="<?php echo $_POST['username']; ?>
" maxlength="30" valid="req"></td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="19" valign="center" width="404">Email &nbsp;</td>
        <td align="left" height="19" width="252"><input name="email" title="Email Address" id="email" style="font-size: 10px; width: 240px; font-family: arial;"    value="<?php echo $_POST['email']; ?>
" maxlength="100" valid="email"></td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">Phone Number &nbsp;</td>
        <td align="left" height="30" width="252"><input title="Phone Number" name="phonenumber" id="phonenumber" style="font-size: 10px; width: 240px; font-family: arial;"    value="<?php echo $_POST['phonenumber']; ?>
" maxlength="100" valid="req"></td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">Instant Messenger &nbsp;</td>
        <td align="left" height="30" width="252"><select title="Contact Instant Messenger" onChange="document.getElementById('cd_contact_im').value='';" name="cd_contact_im_type" id="cd_contact_im_type" style="font-family:arial;font-size:10px;">
            <option value="" selected>Select</option>
            <option value="AIM: ">AIM</option>
            <option value="ICQ: ">ICQ</option>
            <option value="Yahoo: ">Yahoo</option>
            <option value="MSN: ">MSN</option>
            <option value="Other: ">Other</option>
          </select>
          <script>$(cd_contact_im_type).value = '<?php echo $_POST['cd_contact_im_type']; ?>
';</script>
          <input type="text" valid='' maxlength="100" id="cd_contact_im" name="cd_contact_im" title="Contact Instant Messenger ID" style="font-family:arial;font-size:10px;width:175px" value="<?php echo $_POST['cd_contact_im']; ?>
"></td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">Merchant Type &nbsp;</td>
        <td align="left" height="30" valign="center" width="252"><select name="rad_order_type" id="rad_order_type" style="font-size: 10px; width: 100px; font-family: arial;" valid="reqmenu" title="Merchant Type"    >
            <option value="">Select</option>
            <option value="Ecommerce">Ecommerce</option>
            <option value="Travel">Travel</option>
            <option value="Pharmacy">Pharmacy</option>
            <option value="Gaming">Gaming</option>
            <option value="Adult">Adult</option>
            <option value="Extreme">Extreme Adult</option>
            <option value="Telemarketing">Telemarketing</option>
          </select>
          <script>$(rad_order_type).value = '<?php echo $_POST['rad_order_type']; ?>
';</script>
        </td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">Expected Monthly Volume &nbsp;</td>
        <td align="left" height="30" width="252"><select name="merchant_volume" title="Merchant Volume" id="merchant_volume" style="font-size: 10px; width: 100px; font-family: arial;"valid="reqmenu"    >
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
          <script>$(merchant_volume).value = '<?php echo $_POST['merchant_volume']; ?>
';</script>
        </td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">How you hear about us? &nbsp;</td>
        <td align="left" height="30" valign="center" width="252"><select name="how_about_us" title="How did you hear about us?" id="how_about_us" style="font-size: 10px; width: 100px; font-family: arial;" valid="reqmenu"    >
            <option value="select" selected>Select</option>
			<?php $_from = $this->_tpl_vars['etel_hear_about_us']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
			<option value="<?php echo $this->_tpl_vars['key']; ?>
" ><?php echo $this->_tpl_vars['item']; ?>
</option>
			<?php endforeach; endif; unset($_from); ?>

          </select>
          <script language="javascript">
											  document.getElementById('how_about_us').value = '<?php echo $_POST['how_about_us']; ?>
';
											  </script>
        </td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">If reseller/others specify &nbsp; details&nbsp;&nbsp;</td>
        <td align="left" height="30" width="252"><input name="reseller" title="Reseller" id="reseller" style="font-size: 10px; width: 100px; font-family: arial;" value="<?php echo $_POST['reseller']; ?>
" maxlength="75"></td>
      </tr>
      <tr>
        <td class="bodytext" align="right" height="30" valign="center" width="404">Time Zone&nbsp;</td>
        <td align="left" height="30" width="252"><select name="cd_timezone" title="Time Zone" style="width:252;" valid='reqmenu'>
            <option value="" selected>Select a Time Zone</option>
			<?php $_from = $this->_tpl_vars['etel_timezone']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
			<option value="<?php echo $this->_tpl_vars['key']; ?>
" ><?php echo $this->_tpl_vars['item']; ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
			</select></td>
      </tr>
      <tr>
        <td colspan="2" align="center" height="30" valign="center" width="404"><div align="center">
            <input class="Button" value="Submit" name="addcompany" type="submit">
          </div></td>
      </tr>
    </tbody>
  </table>
</form>
<script>setupForm(document.getElementById('Frmcompany'));</script>