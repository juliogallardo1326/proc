<?php /* Smarty version 2.6.2, created on 2006-10-21 20:34:48
         compiled from manage/projects.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manage/projects.tpl.html', 96, false),array('modifier', 'capitalize', 'manage/projects.tpl.html', 264, false),array('function', 'html_options', 'manage/projects.tpl.html', 118, false),array('function', 'cycle', 'manage/projects.tpl.html', 257, false),)), $this); ?>

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
                  checkLeadSelection(f);
                  if (isWhitespace(f.title.value)) {
                      selectField(f, \'title\');
                      alert(\'Please enter the title of this project.\');
                      return false;
                  }
                  if (!hasOneSelected(f, \'users[]\')) {
                      selectField(f, \'users[]\');
                      alert(\'Please assign the users for this project.\');
                      return false;
                  }
                  if (!hasOneSelected(f, \'statuses[]\')) {
                      selectField(f, \'statuses[]\');
                      alert(\'Please assign the statuses for this project.\');
                      return false;
                  }
                  // the selected initial status should be one of the selected assigned statuses
                  initial_status = getSelectedOption(f, \'initial_status\');
                  assigned_statuses = getFormElement(f, \'statuses[]\');
                  var found = 0;
                  for (var i = 0; i < assigned_statuses.options.length; i++) {
                      if ((assigned_statuses.options[i].selected) && (initial_status == assigned_statuses.options[i].value)) {
                          found = 1;
                      }
                  }
                  if (!found) {
                      selectField(f, \'initial_status\');
                      alert(\'Please choose the initial status from one of the assigned statuses of this project.\');
                      return false;
                  }
                  if (!isEmail(f.outgoing_sender_email.value)) {
                      selectField(f, \'outgoing_sender_email\');
                      alert(\'Please enter a valid outgoing sender address for this project.\');
                      return false;
                  }
                  return true;
              }
              function checkLeadSelection(f)
              {
                  var selection = f.lead_usr_id.options[f.lead_usr_id.selectedIndex].value;
                  selectOption(f, \'users[]\', selection);
              }
              //-->
              </script>
              '; ?>

              <form name="project_form" onSubmit="javascript:return validateForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
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
                  <b>Manage Projects</b>
                </td>
              </tr>
              <?php if ($this->_tpl_vars['result'] != ""): ?>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
                  <?php if ($_POST['cat'] == 'new'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to add the new project.
                    <?php elseif ($this->_tpl_vars['result'] == -2): ?>
                      Please enter the title for this new project.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the project was added successfully.
                    <?php endif; ?>
                  <?php elseif ($_POST['cat'] == 'update'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to update the project information.
                    <?php elseif ($this->_tpl_vars['result'] == -2): ?>
                      Please enter the title for this project.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the project was updated successfully.
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Title: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <input type="text" name="title" size="40" class="default" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['prj_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'title')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Status: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <select name="status" class="default">
                    <option value="active" <?php if ($this->_tpl_vars['info']['prj_status'] == 'active'): ?>selected<?php endif; ?>>Active</option>
                    <option value="archived" <?php if ($this->_tpl_vars['info']['prj_status'] == 'archived'): ?>selected<?php endif; ?>>Archived</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Customer Integration Backend: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <select name="customer_backend" class="default">
                    <option value="">No Customer Integration</option>
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['customer_backends'],'selected' => $this->_tpl_vars['info']['prj_customer_backend']), $this);?>

                  </select>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Workflow Backend: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <select name="workflow_backend" class="default">
                    <option value="">No Workflow Management</option>
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['workflow_backends'],'selected' => $this->_tpl_vars['info']['prj_workflow_backend']), $this);?>

                  </select>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Project Lead: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <select name="lead_usr_id" class="default" onChange="javascript:checkLeadSelection(this.form);">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_options'],'selected' => $this->_tpl_vars['info']['prj_lead_usr_id']), $this);?>

                  </select>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Statuses: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <select name="statuses[]" multiple size="3" class="default">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['status_options'],'selected' => $this->_tpl_vars['info']['assigned_statuses']), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "statuses[]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Initial Status for New Issues: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <select name="initial_status" class="default">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['status_options'],'selected' => $this->_tpl_vars['info']['prj_initial_sta_id']), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'initial_status')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Outgoing Email Sender Name:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <input type="text" name="outgoing_sender_name" size="40" class="default" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['prj_outgoing_sender_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Outgoing Email Sender Address: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                  <input type="text" name="outgoing_sender_email" size="40" class="default" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['prj_outgoing_sender_email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'outgoing_sender_email')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="120" nowrap bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Remote Invocation:</b>
                </td>
                <td width="80%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
                  <input type="radio" name="remote_invocation" value="enabled" <?php if ($this->_tpl_vars['info']['prj_remote_invocation'] == 'enabled'): ?>checked<?php endif; ?>> 
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('project_form', 'remote_invocation', 0);">Enabled</a>&nbsp;&nbsp;
                  <input type="radio" name="remote_invocation" value="disabled" <?php if ($this->_tpl_vars['info']['prj_remote_invocation'] != 'enabled'): ?>checked<?php endif; ?>> 
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('project_form', 'remote_invocation', 1);">Disabled</a>
                </td>
              </tr>
              <tr>
                <td width="120" nowrap bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Segregate Reporters:</b> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help_link.tpl.html", 'smarty_include_vars' => array('topic' => 'segregate_reporter')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
                <td width="80%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
                  <input type="radio" name="segregate_reporter" value="1" <?php if ($this->_tpl_vars['info']['prj_segregate_reporter'] == 1): ?>checked<?php endif; ?>> 
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('project_form', 'segregate_reporter', 0);">Yes</a>&nbsp;&nbsp;
                  <input type="radio" name="segregate_reporter" value="0" <?php if ($this->_tpl_vars['info']['prj_segregate_reporter'] != 1): ?>checked<?php endif; ?>> 
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('project_form', 'segregate_reporter', 1);">No</a>
                </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                  <?php if ($_GET['cat'] == 'edit'): ?>
                  <input class="button" type="submit" value="Update Project">
                  <?php else: ?>
                  <input class="button" type="submit" value="Create Project">
                  <?php endif; ?>
                  <input class="button" type="reset" value="Reset">
                </td>
              </tr>
              </form>
              <tr>
                <td colspan="2" class="default">
                  <b>Existing Projects:</b>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <?php echo '
                  <script language="JavaScript">
                  <!--
                  function checkDelete(f)
                  {
                      var total_selected = getTotalCheckboxesChecked(f, \'items[]\');
                      var total = getTotalCheckboxes(f, \'items[]\');
                      if (total == total_selected) {
                          alert(\'You cannot remove all of the projects in the system.\');
                          return false;
                      }
                      if (!hasOneChecked(f, \'items[]\')) {
                          alert(\'Please select at least one of the projects.\');
                          return false;
                      }
                      if (!confirm(\'WARNING: This action will remove the selected projects permanently.\\nIt will remove all of its associated entries as well (issues, notes, attachments,\\netc), so please click OK to confirm.\')) {
                          return false;
                      } else {
                          return true;
                      }
                  }
                  //-->
                  </script>
                  '; ?>

                  <table border="0" width="100%" cellpadding="1" cellspacing="1">
                    <form onSubmit="javascript:return checkDelete(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
                    <input type="hidden" name="cat" value="delete">
                    <tr>
                      <td width="4" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" nowrap><input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');"></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Title</b></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Project Lead</b></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Status</b></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" nowrap class="default_white" align="center">&nbsp;<b>Actions</b></td>
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
                      <td width="4" align="center" nowrap bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><input type="checkbox" name="items[]" value="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
"></td>
                      <td width="30%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
" title="update this entry"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_title']; ?>
</a>
                      </td>
                      <td width="20%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">&nbsp;<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['usr_full_name']; ?>
</td>
                      <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_status'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</td>
                      <td width="30%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" nowrap class="default">
                        <ul>
                          <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/releases.php?prj_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
" class="link">Edit Releases</a></li>
                          <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/categories.php?prj_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
" class="link">Edit Categories</a></li>
                          <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/priorities.php?prj_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
" class="link">Edit Priorities</a></li>
                          <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/phone_categories.php?prj_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
" class="link">Edit Phone Support Categories</a></li>
                          <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/anonymous.php?prj_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
" class="link">Anonymous Reporting</a></li>
                          <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/field_display.php?prj_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
" class="link">Edit Fields to Display</a></li>
                          <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/column_display.php?prj_id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['prj_id']; ?>
" class="link">Edit Columns to Display</a></li>
                        </ul>
                      </td>
                    </tr>
                    <?php endfor; else: ?>
                    <tr>
                      <td colspan="5" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                        <i>No projects could be found.</i>
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td width="4" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                        <input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');">
                      </td>
                      <td colspan="4" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                        <input type="submit" value="Delete" class="button">
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
