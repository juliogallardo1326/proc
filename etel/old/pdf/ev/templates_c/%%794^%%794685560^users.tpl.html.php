<?php /* Smarty version 2.6.2, created on 2006-10-19 14:42:05
         compiled from manage/users.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'manage/users.tpl.html', 40, false),array('modifier', 'escape', 'manage/users.tpl.html', 173, false),array('modifier', 'capitalize', 'manage/users.tpl.html', 243, false),array('function', 'html_options', 'manage/users.tpl.html', 139, false),array('function', 'cycle', 'manage/users.tpl.html', 226, false),)), $this); ?>

      <table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr>
          <td>
            <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
              <?php echo '
              <script language="JavaScript">
              <!--
              function validateForm(f)
              {
                  if (isWhitespace(f.email.value)) {
                      alert(\'Please enter the email of this user.\');
                      selectField(f, \'email\');
                      return false;
                  }
                  if (!isEmail(f.email.value)) {
                      alert(\'Please enter a valid email address.\');
                      selectField(f, \'email\');
                      return false;
                  }
                  if (f.cat.value == \'update\') {
                      if ((!isWhitespace(f.password.value)) && (f.password.value.length < 6)) {
                          alert(\'Please enter a password of at least 6 characters.\');
                          selectField(f, \'password\');
                          return false;
                      }
                  } else {
                      if ((isWhitespace(f.password.value)) || (f.password.value.length < 6)) {
                          alert(\'Please enter a password of at least 6 characters.\');
                          selectField(f, \'password\');
                          return false;
                      }
                  }
                  if (isWhitespace(f.full_name.value)) {
                      alert(\'Please enter the full name of this user.\');
                      selectField(f, \'full_name\');
                      return false;
                  }
                  var has_project_selected = false;
                  var projects = new Array(';  echo count($this->_tpl_vars['project_list']);  echo ');
                  '; ?>

                  <?php if (isset($this->_foreach['projects'])) unset($this->_foreach['projects']);
$this->_foreach['projects']['name'] = 'projects';
$this->_foreach['projects']['total'] = count($_from = (array)$this->_tpl_vars['project_list']);
$this->_foreach['projects']['show'] = $this->_foreach['projects']['total'] > 0;
if ($this->_foreach['projects']['show']):
$this->_foreach['projects']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['prj_id'] => $this->_tpl_vars['prj_title']):
        $this->_foreach['projects']['iteration']++;
        $this->_foreach['projects']['first'] = ($this->_foreach['projects']['iteration'] == 1);
        $this->_foreach['projects']['last']  = ($this->_foreach['projects']['iteration'] == $this->_foreach['projects']['total']);
?>
                  projects[<?php echo $this->_foreach['projects']['iteration']-1; ?>
] = <?php echo $this->_tpl_vars['prj_id']; ?>
;
                  <?php endforeach; unset($_from); endif; ?>
                  <?php echo '
                  for (i = 0; i < projects.length; i++) {
                    if ((getFormElement(getForm(\'user_form\'), \'role[\' + projects[i] + \']\').selectedIndex > 0) ||
                        (getFormElement(getForm(\'user_form\'), \'role[\' + projects[i] + \']\').type == \'hidden\' &&
                        getFormElement(getForm(\'user_form\'), \'role[\' + projects[i] + \']\').value != \'\')) {
                        has_project_selected = true;
                    }
                  }
                  if (!has_project_selected) {
                      alert(\'Please assign the appropriate projects for this user.\');
                      selectField(f, \'projects[]\');
                      return false;
                  }
                  return true;
              }
              //-->
              </script>
              '; ?>

              <form name="user_form" onSubmit="javascript:return validateForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
              <?php if ($_GET['cat'] == 'edit'): ?>
              <input type="hidden" name="cat" value="update">
              <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>
">
              <?php else: ?>
              <input type="hidden" name="cat" value="new">
              <?php endif; ?>
              <tr>
                <td colspan="2" class="default">
                  <b>Manage Users</b>
                </td>
              </tr>
              <?php if ($this->_tpl_vars['result'] != ""): ?>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
                  <?php if ($_POST['cat'] == 'new'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to add the new user.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the user was added successfully.
                    <?php endif; ?>
                  <?php elseif ($_POST['cat'] == 'update'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to update the user information.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the user was updated successfully.
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Email Address:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="text" name="email" size="40" class="default" value="<?php echo $this->_tpl_vars['info']['usr_email']; ?>
">
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'email')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Password:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="password" name="password" size="20" class="default">
                  <?php if ($_GET['cat'] == 'edit'): ?>
                  <span class="default">(leave empty to keep the current password)</span>
                  <?php endif; ?>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'password')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Full Name:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="text" name="full_name" size="40" class="default" value="<?php echo $this->_tpl_vars['info']['usr_full_name']; ?>
">
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'full_name')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <nobr><b>Assigned Projects and Roles:</b></nobr>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <table border="0">
                    <?php if (count($_from = (array)$this->_tpl_vars['project_list'])):
    foreach ($_from as $this->_tpl_vars['prj_id'] => $this->_tpl_vars['prj_name']):
?>
                    <tr>
                      <td class="default"><?php echo $this->_tpl_vars['prj_name']; ?>
:</td>
                      <td>
                        <?php if ($this->_tpl_vars['info']['roles'][$this->_tpl_vars['prj_id']]['pru_role'] == $this->_tpl_vars['roles']['customer']): ?>
                        <span class="default">Customer</span>
                        <input type="hidden" name="role[<?php echo $this->_tpl_vars['prj_id']; ?>
]" value="<?php echo $this->_tpl_vars['roles']['customer']; ?>
">
                        <?php else: ?>
                        <select name="role[<?php echo $this->_tpl_vars['prj_id']; ?>
]" class="default"  <?php if ($this->_tpl_vars['current_role'] < $this->_tpl_vars['info']['roles'][$this->_tpl_vars['prj_id']]['pru_role']): ?>disabled<?php endif; ?>>
                        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['project_roles'][$this->_tpl_vars['prj_id']],'selected' => $this->_tpl_vars['info']['roles'][$this->_tpl_vars['prj_id']]['pru_role']), $this);?>

                        </select>
                        <?php if ($this->_tpl_vars['current_role'] < $this->_tpl_vars['info']['roles'][$this->_tpl_vars['prj_id']]['pru_role']): ?><input type="hidden" name="role[<?php echo $this->_tpl_vars['prj_id']; ?>
]" value="<?php echo $this->_tpl_vars['info']['roles'][$this->_tpl_vars['prj_id']]['pru_role']; ?>
"><?php endif; ?>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "role[".($this->_tpl_vars['prj_id'])."]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; unset($_from); endif; ?>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                  <?php if ($_GET['cat'] == 'edit'): ?>
                  <input class="button" type="submit" value="Update User">
                  <?php else: ?>
                  <input class="button" type="submit" value="Create User">
                  <?php endif; ?>
                  <input class="button" type="reset" value="Reset">
                </td>
              </tr>
              </form>
              <tr>
                <td colspan="2" class="default">
                  <b>Existing Users:</b>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <script language="JavaScript">
                  <!--
                  var active_users = new Array();
                  <?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                  <?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_status'] == 'active'): ?>
                  active_users[<?php echo $this->_sections['i']['index']; ?>
] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
';
                  <?php endif; ?>
                  <?php endfor; endif; ?>
                  var page_url = '<?php echo $_SERVER['PHP_SELF']; ?>
';
                  <?php echo '
                  function checkDelete(f)
                  {
                      var total_selected = getTotalCheckboxesChecked(f, \'items[]\');
                      var total = getTotalCheckboxes(f, \'items[]\');
                      if (getSelectedOption(f, \'status\') == \'inactive\') {
                          if (active_users.length < 2) {
                              alert(\'You cannot change the status of the only active user left in the system.\');
                              return false;
                          }
                          if (total == total_selected) {
                              alert(\'You cannot inactivate all of the users in the system.\');
                              return false;
                          }
                      }
                      if (!hasOneChecked(f, \'items[]\')) {
                          alert(\'Please select at least one of the users.\');
                          return false;
                      }
                      if (!confirm(\'This action will change the status of the selected users.\')) {
                          return false;
                      } else {
                          return true;
                      }
                  }
                  function showCustomerUsers(f)
                  {
                      var field = getFormElement(f, \'show_customers\', 0);
                      if (field.checked) {
                          window.location.href = page_url + "?" + replaceParam(window.location.href, \'show_customers\', \'1\');
                      } else {
                          window.location.href = page_url + "?" + replaceParam(window.location.href, \'show_customers\', \'0\');
                      }
                  }
                  //-->
                  </script>
                  '; ?>

                  <table border="0" width="100%" cellpadding="1" cellspacing="1">
                    <form name="change_status_form" onSubmit="javascript:return checkDelete(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
                    <input type="hidden" name="cat" value="change_status">
                    <tr>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" nowrap><input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');"></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Full Name</b></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Role</b></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Email Address</b></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Status</b></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Group</b></td>
                    </tr>
                    <?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
                    <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

                    <tr>
                      <td width="4" nowrap bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" align="center">
                        <input type="checkbox" name="items[]" value="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_id']; ?>
" <?php if ($this->_sections['i']['total'] == 0): ?>disabled<?php endif; ?>>
                      </td>
                      <td width="15%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_id']; ?>
" title="update this entry"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_full_name']; ?>
</a>
                      </td>
                      <td width="15%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default" nowrap>
                        <?php if (count($_from = (array)$this->_tpl_vars['list'][$this->_sections['i']['index']]['roles'])):
    foreach ($_from as $this->_tpl_vars['role_data']):
?>
                          &nbsp;<?php echo $this->_tpl_vars['role_data']['prj_title']; ?>
: <?php echo $this->_tpl_vars['role_data']['role']; ?>
<br />
                        <?php endforeach; unset($_from); endif; ?>
                      </td>
                      <td width="35%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<a href="mailto:<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_email']; ?>
" class="link" title="send email to <?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_email']; ?>
"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_email']; ?>
</a>
                      </td>
                      <td width="20%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_status'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>

                      </td>
                      <td width="15%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['group_name']; ?>

                      </td>
                    </tr>
                    <?php endfor; else: ?>
                    <tr>
                      <td colspan="6" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                        <i>No users could be found.</i>
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td colspan="6" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td>
                              <input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');">
                              <input type="submit" value="Update Status &gt;&gt;" class="button">
                              <select name="status" class="default">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                              </select>
                            </td>
                            <td align="right" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                              <input type="checkbox" id="show_customers" name="show_customers" value="yes" <?php if ($_GET['show_customers'] == 1): ?>checked<?php endif; ?> onClick="javascript:showCustomerUsers(this.form);">
                              <label for="show_customers">Show Customers</label>&nbsp;
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    </form>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
