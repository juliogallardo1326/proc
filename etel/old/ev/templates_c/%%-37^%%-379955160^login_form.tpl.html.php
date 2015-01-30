<?php /* Smarty version 2.6.2, created on 2006-10-19 13:42:56
         compiled from login_form.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'login_form.tpl.html', 22, false),array('modifier', 'default', 'login_form.tpl.html', 30, false),)), $this); ?>

<?php echo '
<script language="JavaScript">
<!--
function validateForm(f)
{
    if (isWhitespace(f.email.value)) {
        errors[errors.length] = new Option(\'Email Address\', \'email\');
    }
    if (isWhitespace(f.passwd.value)) {
        errors[errors.length] = new Option(\'Password\', \'passwd\');
    }
    if (errors.length > 0) {
        return false;
    }
}
//-->
</script>
'; ?>

<form name="login_form" onSubmit="javascript:return checkFormSubmission(this, 'validateForm');" method="post" action="login.php">
<input type="hidden" name="cat" value="login">
<input type="hidden" name="url" value="<?php echo ((is_array($_tmp=$_GET['url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
<table align="center" width="400" border="0" cellspacing="0" cellpadding="1" bgcolor="#FFFFFF">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td colspan="2" align="center">
            <br /><br />
            <h3><?php echo ((is_array($_tmp=@$this->_tpl_vars['app_setup']['tool_caption'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['application_title']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['application_title'])); ?>
 - Login</h3>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table align="center" width="400" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td colspan="2" bgcolor="#006486"><img src="images/blank.gif" width="1" height="5"></td>
        </tr>
        <?php if ($_GET['err'] != 0): ?>
        <tr>
          <td colspan="2" align="center" class="error" bgcolor="#006486">
            <b>
            <?php if ($_GET['err'] == 1): ?>
              Error: Please provide your email address.
            <?php elseif ($_GET['err'] == 2): ?>
              Error: Please provide your password.
            <?php elseif ($_GET['err'] == 3 || $_GET['err'] == 4): ?>
              Error: The email address / password combination could not be found in the system.
            <?php elseif ($_GET['err'] == 5): ?>
              Your session has expired. Please login again to continue.
            <?php elseif ($_GET['err'] == 6): ?>
              Thank you, you are now logged out of <?php echo $this->_tpl_vars['application_title']; ?>
.
            <?php elseif ($_GET['err'] == 7): ?>
              Error: Your user status is currently set as inactive. Please 
              contact your local system administrator for further information.
            <?php elseif ($_GET['err'] == 8): ?>
              Thank you, your account is now active and ready to be 
              used. Use the form below to login.
            <?php elseif ($_GET['err'] == 9): ?>
              Error: Your user status is currently set as pending. This 
              means that you still need to confirm your account 
              creation request. Please contact your local system 
              administrator for further information.
            <?php elseif ($_GET['err'] == 11): ?>
              Error: Cookies support seem to be disabled in your browser. Please enable this feature and try again.
            <?php elseif ($_GET['err'] == 12): ?>
              Error: In order for <?php echo $this->_tpl_vars['application_title']; ?>
 to work properly, you must enable cookie support in your browser. Please login 
              again and accept all cookies coming from it.
            <?php endif; ?>
            </b>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td align="right" width="40%" class="default_white" bgcolor="#006486"><b><u>E</u>mail Address:</b></td>
          <td width="60%" bgcolor="#006486">
            <input accessKey="e" class="default" type="text" name="email" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['email'])) ? $this->_run_mod_handler('default', true, $_tmp, @$_GET['email']) : smarty_modifier_default($_tmp, @$_GET['email'])))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" size="30">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'email')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td align="right" width="40%" class="default_white" bgcolor="#006486"><b><u>P</u>assword:</b></td>
          <td width="60%" bgcolor="#006486">
            <input accessKey="p" class="default" type="password" name="passwd" size="20" maxlength="32">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'passwd')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr align="center">
          <td colspan="2" bgcolor="#006486">
            <input type="submit" name="Submit" value="Login" class="button">
          </td>
        </tr>
        <tr>
          <td colspan="2" class="default_white" align="center" bgcolor="#006486">
            <label for="remember_login" accesskey="r"></label>
            <input type="checkbox" id="remember_login" name="remember_login" value="1"> <b><a id="white_link" class="white_link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('login_form', 'remember_login');"><u>R</u>emember Login</a></b>
          </td>
        </tr>
        <tr align="center">
          <td colspan="2" class="default_white" bgcolor="#006486">
            <a class="white_link" href="forgot_password.php<?php if ($_GET['email'] != ""): ?>?email=<?php echo ((is_array($_tmp=$_GET['email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?>">I Forgot My Password</a>&nbsp;&nbsp;
            <?php if ($this->_tpl_vars['app_setup']['open_signup'] == 'enabled'): ?><a class="white_link" href="signup.php">Signup for an Account</a><?php endif; ?>
          </td>
        </tr>
        <tr>
          <td bgcolor="#006486" colspan="2" align="center" class="default_white">
            <b>* Requires support for cookies and <br />javascript in your browser</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php if ($this->_tpl_vars['anonymous_post']): ?>
<br />
<table width="400" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td class="default">
            <b>NOTE: You may report issues without the need to login by using the following URL:</b>
            <br /><br />
            <a href="<?php echo $this->_tpl_vars['app_base_url']; ?>
post.php" class="link"><?php echo $this->_tpl_vars['app_base_url']; ?>
post.php</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php endif;  echo '
<script language="JavaScript">
<!--
window.onload = setFocus;
function setFocus()
{
    if (!isWhitespace(document.login_form.email.value)) {
        document.login_form.passwd.focus();
    } else {
        document.login_form.email.focus();
    }
}
//-->
</script>
'; ?>

