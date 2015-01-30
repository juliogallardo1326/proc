<?php /* Smarty version 2.6.2, created on 2006-10-30 08:23:22
         compiled from signup.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('extra_title' => 'Account Signup')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br /><br />
<?php if ($this->_tpl_vars['app_setup']['open_signup'] != 'enabled'): ?>
<center>
<span class="default">
<b>Sorry, but this feature has been disabled by the administrator.</b>
<br /><br />
<a class="link" href="javascript:history.go(-1);">Go Back</a>
</span>
</center>
<?php else:  echo '
<script language="JavaScript">
<!--
function validateForm(f)
{
    if (isWhitespace(f.full_name.value)) {
        alert(\'Please enter your full name.\');
        selectField(f, \'full_name\');
        return false;
    }
    if (isWhitespace(f.email.value)) {
        alert(\'Please enter your email address.\');
        selectField(f, \'email\');
        return false;
    }
    if (!isEmail(f.email.value)) {
        alert(\'Please enter a valid email address.\');
        selectField(f, \'email\');
        return false;
    }
    if (isWhitespace(f.passwd.value)) {
        alert(\'Please enter your password.\');
        selectField(f, \'passwd\');
        return false;
    }
    return true;
}
//-->
</script>
'; ?>

<form name="signup_form" onSubmit="javascript:return validateForm(this);" method="post" action="signup.php">
<input type="hidden" name="cat" value="signup">
<table align="center" width="500" border="0" cellspacing="0" cellpadding="1" bgcolor="#000000">
  <tr>
    <td>
      <table bgcolor="#006486" width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td colspan="2" align="center">
            <h2 style="color: white;">Account Signup</h2>
            <hr size="1" noshade color="white">
          </td>
        </tr>
        <?php if ($this->_tpl_vars['signup_result'] != ''): ?>
        <tr>
          <td colspan="2" align="center" class="error">
            <b>
            <?php if ($this->_tpl_vars['signup_result'] == -1): ?>
              Error: An error occurred while trying to run your query.
            <?php elseif ($this->_tpl_vars['signup_result'] == -2): ?>
              Error: The email address specified is already associated with 
              an user in the system.
            <?php elseif ($this->_tpl_vars['signup_result'] == 1): ?>
              Thank you, your account creation request was processed 
              successfully. For security reasons a confirmation email 
              was sent to the provided email address with instructions
              on how to confirm your request and activate your account.
            <?php endif; ?>
            </b>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['signup_result'] != 1): ?>
        <tr>
          <td align="right" width="130" class="default_white"><b><u>F</u>ull Name:</b></td>
          <td width="75%">
            <input accessKey="f" class="default" type="text" name="full_name" size="50" maxlength="255">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'full_name')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td align="right" width="130" class="default_white"><b><u>E</u>mail Address:</b></td>
          <td width="75%">
            <input accessKey="e" class="default" type="text" name="email" size="50" maxlength="255">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'email')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td align="right" width="130" class="default_white"><b><u>P</u>assword:</b></td>
          <td width="75%">
            <input accessKey="p" class="default" type="password" name="passwd" size="20" maxlength="32">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'passwd')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input class="button" type="submit" value="Create Account">
          </td>
        </tr>
        <?php endif; ?>
        <tr align="center">
          <td colspan="2" class="default_white">
            <a class="white_link" href="index.php">Back to Login Form</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php echo '
<script language="JavaScript">
<!--
window.onload = setFocus;
function setFocus()
{
    if (document.signup_form.full_name != null) {
        document.signup_form.full_name.focus();
    }
}
//-->
</script>
'; ?>

<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>