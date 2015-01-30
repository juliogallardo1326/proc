<?php /* Smarty version 2.6.2, created on 2006-10-19 13:39:08
         compiled from setup.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'setup.tpl.html', 213, false),array('modifier', 'escape', 'setup.tpl.html', 363, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('application_title' => 'Eventum Installation')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<script language="JavaScript">
<!--
function validateForm(f)
{
    if (isWhitespace(f.hostname.value)) {
        selectField(f, \'hostname\');
        alert(\'Please enter the hostname for the server of this installation of Eventum.\');
        return false;
    }
    if (isWhitespace(f.relative_url.value)) {
        selectField(f, \'relative_url\');
        alert(\'Please enter the relative URL of this installation of Eventum.\');
        return false;
    }
    if (isWhitespace(f.path.value)) {
        selectField(f, \'path\');
        alert(\'Please enter the full path in the server of this installation of Eventum.\');
        return false;
    }
    if (isWhitespace(f.db_hostname.value)) {
        selectField(f, \'db_hostname\');
        alert(\'Please enter the database hostname for this installation of Eventum.\');
        return false;
    }
    if (isWhitespace(f.db_name.value)) {
        selectField(f, \'db_name\');
        alert(\'Please enter the database name for this installation of Eventum.\');
        return false;
    }
    if (isWhitespace(f.db_username.value)) {
        selectField(f, \'db_username\');
        alert(\'Please enter the database username for this installation of Eventum.\');
        return false;
    }
    if (f.alternate_user.checked) {
        if (isWhitespace(f.eventum_user.value)) {
            selectField(f, \'eventum_user\');
            alert(\'Please enter the alternate username for this installation of Eventum.\');
            return false;
        }
    }
    
    var field = getFormElement(f, \'setup[smtp][from]\');
    if (isWhitespace(field.value)) {
        selectField(f, \'setup[smtp][from]\');
        alert(\'Please enter the sender address that will be used for all outgoing notification emails.\');
        return false;
    }
    if (!isEmail(field.value)) {
        selectField(f, \'setup[smtp][from]\');
        alert(\'Please enter a valid email address for the sender address.\');
        return false;
    }
    field = getFormElement(f, \'setup[smtp][host]\');
    if (isWhitespace(field.value)) {
        selectField(f, \'setup[smtp][host]\');
        alert(\'Please enter the SMTP server hostname.\');
        return false;
    }
    field = getFormElement(f, \'setup[smtp][port]\');
    if ((isWhitespace(field.value)) || (!isNumberOnly(field.value))) {
        selectField(f, \'setup[smtp][port]\');
        alert(\'Please enter the SMTP server port number.\');
        return false;
    }
    var field1 = getFormElement(f, \'setup[smtp][auth]\', 0);
    var field2 = getFormElement(f, \'setup[smtp][auth]\', 1);
    if ((!field1.checked) && (!field2.checked)) {
        alert(\'Please indicate whether the SMTP server requires authentication or not.\');
        return false;
    }
    if (field1.checked) {
      field = getFormElement(f, \'setup[smtp][username]\');
      if (isWhitespace(field.value)) {
          selectField(f, \'setup[smtp][username]\');
          alert(\'Please enter the SMTP server username.\');
          return false;
      }
      field = getFormElement(f, \'setup[smtp][password]\');
      if (isWhitespace(field.value)) {
          selectField(f, \'setup[smtp][password]\');
          alert(\'Please enter the SMTP server password.\');
          return false;
      }
    }
    return true;
}
function toggleAlternateUserFields()
{
    var f = getForm(\'install_form\');
    var element = getPageElement(\'alternate_user_row\');
    if (f.alternate_user.checked) {
        element.style.display = \'\';
    } else {
        element.style.display = \'none\';
    }
}

function disableAuthFields(f, bool)
{
  if (bool) {
      var bgcolor = \'#CCCCCC\';
  } else {
      var bgcolor = \'#FFFFFF\';
  }
  var field = getFormElement(f, \'setup[smtp][username]\');
  field.disabled = bool;
  field.style.backgroundColor = bgcolor;
  field = getFormElement(f, \'setup[smtp][password]\');
  field.disabled = bool;
  field.style.backgroundColor = bgcolor;
}
//-->
</script>
'; ?>


<?php if ($this->_tpl_vars['result'] != '' && $this->_tpl_vars['result'] != 'success'): ?>
<br />
<table width="400" bgcolor="#003366" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/icons/error.gif" hspace="2" vspace="2" border="0" align="left"></td>
          <td width="100%" class="default"><span style="font-weight: bold; font-size: 160%; color: red;">An Error Was Found</span></td>
        </tr>
        <tr>
          <td colspan="2" class="default">
            <br />
            <b>Details: <?php echo $this->_tpl_vars['result']; ?>
</b>
            <br /><br />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['result'] == 'success'): ?>
<br />
<table width="400" bgcolor="#003366" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td width="100%" class="default"><span style="font-weight: bold; font-size: 160%;">Success!</span></td>
        </tr>
        <tr>
          <td class="default">
            <br />
            <b>Thank You, Eventum is now properly setup and ready to be used. Open the following URL to login on it for the first time:</b>
            <br />
            <a class="link" href="<?php if ($_POST['is_ssl'] == 'yes'): ?>https://<?php else: ?>http://<?php endif;  echo $_POST['hostname'];  echo $_POST['relative_url']; ?>
"><?php if ($_POST['is_ssl'] == 'yes'): ?>https://<?php else: ?>http://<?php endif;  echo $_POST['hostname'];  echo $_POST['relative_url']; ?>
</a>
            <br /><br />
            Email Address: admin@example.com (literally)<br />
            Password: admin<br />
            <br />
            <b>NOTE: For security reasons it is highly recommended that the default password be changed as soon as possible.
            <br /><br />
            <hr size="1" noshade color="#000000">
            Remember to protect your 'setup' directory (like changing its permissions) to prevent anyone else 
            from changing your existing Eventum configuration.<br /><br />
            In order to check if your permissions are setup correctly visit the <a class="link" href="check_permissions.php">Check Permissions</a> page.
            <?php if (! $this->_tpl_vars['is_imap_enabled']): ?>
            <br /><br />
            <hr size="1" noshade color="#000000">
            WARNING: If you want to use the email integration features to download messages saved on a IMAP/POP3 server, you will need to
            enable the IMAP extension in your PHP.INI configuration file. See the PHP manual for more details.
            <?php endif; ?>
            </b>
            <br /><br />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php else: ?>
<br />
<?php if ($this->_tpl_vars['phpversion'] >= 5): ?>
<table align="center" width="600" bgcolor="red">
<tr>
<td align="center" class="default" bgcolor="white">
<span style="font-weight: bold; font-size: 160%; color: red;">Warning!</span><br />You are running PHP version <?php echo $this->_tpl_vars['phpversion']; ?>
. While all effort has been made to ensure eventum works correctly with
PHP 5 and greater, it has not been thoroughly tested and may not work properly. <br /><br />
Please report any problems you find to eventum-users@lists.mysql.com.
</td></tr>
</table>
<br />
<?php endif; ?>
<table width="600" bgcolor="#000000" border="0" cellspacing="0" cellpadding="1" align="center">
<form name="install_form" action="<?php echo $_SERVER['PHP_SELF']; ?>
" method="post" onSubmit="javascript:return validateForm(this);">
<input type="hidden" name="cat" value="install">
  <tr>
    <td>
      <table bgcolor="#CCCCCC" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" align="center">
            <h1>Eventum Installation</h1>
            <hr size="1" noshade color="#000000">
          </td>
        </tr>
        <tr>
          <td width="180" class="default" align="right">
            <b>Server Hostname: *</b>
          </td>
          <td>
            <?php $this->assign('tabindex', '1'); ?>
            <input type="text" name="hostname" value="<?php echo ((is_array($_tmp=@$_POST['hostname'])) ? $this->_run_mod_handler('default', true, $_tmp, @$_SERVER['HTTP_HOST']) : smarty_modifier_default($_tmp, @$_SERVER['HTTP_HOST'])); ?>
" class="default" size="30" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'hostname')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <input type="checkbox" name="is_ssl" value="yes" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" <?php if ($this->_tpl_vars['ssl_mode'] == 'enabled'): ?>checked<?php endif; ?>> <span class="default"><b><a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('install_form', 'is_ssl');">SSL Server</a></b></span>
          </td>
        </tr>
        <tr>
          <td width="180" class="default" align="right">
            <b>Eventum Relative URL: *</b>
          </td>
          <td>
            <input type="text" name="relative_url" value="<?php echo ((is_array($_tmp=@$_POST['rel_url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['rel_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['rel_url'])); ?>
" class="default" size="30" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'relative_url')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="180" class="default" align="right">
            <b>Installation Path: *</b>
          </td>
          <td>
            <input type="text" name="path" value="<?php echo ((is_array($_tmp=@$_POST['path'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['installation_path']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['installation_path'])); ?>
" class="default" size="50" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'path')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="180" class="default" align="right">
            <nobr>&nbsp;<b>MySQL Server Hostname: *</b></nobr>
          </td>
          <td>
            <input type="text" name="db_hostname" class="default" size="30" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" value="<?php echo $_POST['db_hostname']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'db_hostname')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="180" class="default" align="right">
            <b>MySQL Database: *</b>
          </td>
          <td>
            <input type="text" name="db_name" class="default" size="30" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" value="<?php echo $_POST['db_name']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'db_name')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <input type="checkbox" name="create_db" value="yes" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" <?php if ($_POST['create_db'] == 'yes'): ?>checked<?php endif; ?>> <span class="default"><b><a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('install_form', 'create_db');">Create Database</a></b></span>
          </td>
        </tr>
        <tr>
          <td width="180" class="default" align="right">
            <b>MySQL Table Prefix:</b>
          </td>
          <td>
            <input type="text" name="db_table_prefix" value="<?php echo ((is_array($_tmp=@$_POST['db_table_prefix'])) ? $this->_run_mod_handler('default', true, $_tmp, 'eventum_') : smarty_modifier_default($_tmp, 'eventum_')); ?>
" class="default" size="30" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
          </td>
        </tr>
        <tr>
          <td colspan="2" class="default" align="center">
            <input type="checkbox" name="drop_tables" value="yes" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" <?php if ($_POST['drop_tables'] == 'yes'): ?>checked<?php endif; ?>> <b><a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('install_form', 'drop_tables');">Drop Tables If They Already Exist</a></b>
          </td>
        </tr>
        <tr>
          <td width="180" class="default" align="right">
            <b>MySQL Username: *</b>
          </td>
          <td>
            <input type="text" name="db_username" class="default" size="20" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" value="<?php echo $_POST['db_username']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'db_username')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <span class="small_default"><i>(<b>Note:</b> This user requires permission to create and drop tables in the specified database.<br />This value is used only for these installation procedures, and is not saved if you provide a separate user below.)</i></span>
          </td>
        </tr>
        <tr>
          <td width="180" class="default" align="right">
            <b>MySQL Password:</b>
          </td>
          <td>
            <input type="password" name="db_password" class="default" size="20" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" value="<?php echo $_POST['db_password']; ?>
">
          </td>
        </tr>
        <tr>
          <td colspan="2" class="default" align="center">
            <input type="checkbox" name="alternate_user" value="yes" onClick="javascript:toggleAlternateUserFields();"  tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" <?php if ($_POST['alternate_user'] == 'yes'): ?>checked<?php endif; ?>> <b><a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('install_form', 'alternate_user');toggleAlternateUserFields();">Use a Separate MySQL User for Normal Eventum Use</a></b>
          </td>
        </tr>
        <tr id="alternate_user_row">
          <td colspan="2" align="center">
            <table>
              <tr>
                <td>
                  <table width="300" cellpadding="1" cellspacing="0" bgcolor="white" border="0">
                    <tr>
                      <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#C0C0C0">
                          <tr>
                            <td colspan="2" class="default">
                              <b>Enter the details below:</b>
                            </td>
                          </tr>
                          <tr>
                            <td class="default" align="right">
                              <nobr>&nbsp;<b>Username: *</b>&nbsp;</nobr>
                            </td>
                            <td>
                              <nobr><input type="text" class="default" name="eventum_user" size="20" value="<?php echo $_POST['eventum_user']; ?>
" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">&nbsp;</nobr>
                              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'eventum_user')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                            </td>
                          </tr>
                          <tr>
                            <td class="default" align="right">
                              <nobr>&nbsp;<b>Password:</b>&nbsp;</nobr>
                            </td>
                            <td>
                              <nobr><input type="password" class="default" name="eventum_password" size="20" value="<?php echo $_POST['eventum_password']; ?>
" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">&nbsp;</nobr>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2" class="default" align="center">
                              <input type="checkbox" name="create_user" value="yes" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
" <?php if ($_POST['create_user'] == 'yes'): ?>checked<?php endif; ?>> <b><a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('install_form', 'create_user');">Create User and Permissions</a></b>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="default" align="right">
            &nbsp;
          </td>
        </tr>
        <tr>
          <td class="default" align="center" colspan="2">
            <h2><b>SMTP Configuration</b></h2>
            <hr size="1" noshade color="#000000">
          </td>
        </tr>
        <tr>
          <td colspan="2" class="small_default" align="center">
            <b>Note:</b> The SMTP (outgoing mail) configuration is needed to make sure emails are properly sent when creating new users/projects.
            &nbsp;
            <hr size="1" noshade color="#000000">
          </td>
        </tr>
        <tr>
          <td width="100" class="default" align="right">
            <b>Sender: *</b>
          </td>
          <td width="80%">
            <input type="text" class="default" name="setup[smtp][from]" size="30" value="<?php echo ((is_array($_tmp=$_REQUEST['setup']['smtp']['from'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "setup[smtp][from]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <span class="small_default"><i>(must be a valid email address)</i></span>
          </td>
        </tr>
        <tr>
          <td width="100" class="default" align="right">
            <b>Hostname: *</b>
          </td>
          <td width="80%">
            <input type="text" class="default" name="setup[smtp][host]" size="30" value="<?php echo ((is_array($_tmp=$_REQUEST['setup']['smtp']['host'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "setup[smtp][host]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="100" class="default" align="right">
            <b>Port: *</b>
          </td>
          <td width="80%">
            <input type="text" class="default" name="setup[smtp][port]" size="5" value="<?php echo $_REQUEST['setup']['smtp']['port']; ?>
" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "setup[smtp][port]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="100" class="default" align="right">
            <b>Requires Authentication?&nbsp;</b>
          </td>
          <td width="80%" class="default">
            <input type="radio" name="setup[smtp][auth]" value="1" <?php if ($_REQUEST['setup']['smtp']['auth'] == 1): ?>checked<?php endif; ?> onClick="javascript:disableAuthFields(this.form, false);" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
"> 
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('install_form', 'setup[smtp][auth]', 0);disableAuthFields(getForm('install_form'), false);">Yes</a>&nbsp;&nbsp;
            <input type="radio" name="setup[smtp][auth]" value="0" <?php if ($_REQUEST['setup']['smtp']['auth'] != 1): ?>checked<?php endif; ?> onClick="javascript:disableAuthFields(this.form, true);" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
"> 
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('install_form', 'setup[smtp][auth]', 1);disableAuthFields(getForm('install_form'), true);">No</a>
          </td>
        </tr>
        <tr>
          <td width="100" class="default" align="right">
            <b>Username:&nbsp;</b>
          </td>
          <td width="80%">
            <input type="text" class="default" name="setup[smtp][username]" size="20" value="<?php echo ((is_array($_tmp=$_REQUEST['setup']['smtp']['username'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "setup[smtp][username]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="100" class="default" align="right">
            <b>Password:&nbsp;</b>
          </td>
          <td width="80%">
            <input type="password" class="default" name="setup[smtp][password]" size="20" value="<?php echo ((is_array($_tmp=$_REQUEST['setup']['smtp']['password'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "setup[smtp][password]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="#666666" align="right">
            <input style="font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 90%;" type="submit" value="Start Installation &gt;&gt;" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
">
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" class="default">
      <b>* Required Fields</b>
    </td>
  </tr>
</form>
</table>

<?php echo '
<script language="JavaScript">
<!--
window.onload = setFocus;
function setFocus()
{
    document.install_form.hostname.focus();
    toggleAlternateUserFields();
'; ?>

    <?php if ($_REQUEST['setup']['smtp']['auth'] != 1): ?>
    disableAuthFields(getForm('install_form'), true);
    <?php endif;  echo '
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