<?php /* Smarty version 2.6.9, created on 2005-12-10 03:09:25
         compiled from int_account.tpl */ ?>
	<!--Main-->

<form id="processingFrm" action="<?php echo $this->_tpl_vars['rootdir']; ?>
/secure/FinalProcessing.php" method="post" onsubmit="return submitOrder(this)">

  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan='2'  height="10" valign="middle" class="normaltext">&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2'  width="60%" bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
    </tr>
  <tr>
    <td height="20" class="fieldname" align="left" style="font-size:24px; font-weight:bold"><?php echo $this->_config[0]['vars']['GL_Step']; ?>
 #2</td>
  </tr>
    <tr>
      <td colspan='2'  class="fieldname" align="center" style="font-size:16px; font-weight:bold">      <?php echo $this->_config[0]['vars']['OP_ChargeAccount']; ?>
:</td>
    </tr>
    <tr >
      <td colspan='2' align="center" style="font-size:16px; font-weight:bold"><table width="400" border="1">
          <tr>
            <td class="fieldname"><?php echo $this->_config[0]['vars']['GL_YourEmail']; ?>
</td>
            <td><input name="login_ca_email" type="text" value="<?php echo $this->_tpl_vars['login_ca_email']; ?>
" size="35" src="email"></td>
          </tr>
          <tr>
            <td class="fieldname"><?php echo $this->_config[0]['vars']['GL_YourPass']; ?>
</td>
            <td><input name="login_ca_password" type="password" value="<?php echo $this->_tpl_vars['login_ca_password']; ?>
" src="minlen|6"></td>
          </tr>
		  
          <?php if ($this->_tpl_vars['isPasswordManagement']): ?>
          <tr align="center">
            <td colspan="2" class="fieldname"><?php echo $this->_config[0]['vars']['OP_EnterSubscriptionInfo']; ?>
:</td>
          </tr>
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
            <td><input name="td_password" type="password" id="td_password" value="<?php echo $this->_tpl_vars['td_password']; ?>
" size="30" maxlength="30"    src="req">
              <strong><span class="terms"> </span></strong></td>
          </tr>
          <?php endif; ?>
      </table>
      <p> <font class="text" face="Verdana,Arial,Times New I2"><font size="2"><b>Forgot Password?</b></font></font></p></td>
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