<?php /* Smarty version 2.6.2, created on 2006-10-19 16:52:11
         compiled from manage/custom_fields.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'manage/custom_fields.tpl.html', 230, false),array('function', 'cycle', 'manage/custom_fields.tpl.html', 395, false),)), $this); ?>

      <table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr>
          <td>
            <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
              <?php echo '
              <script language="JavaScript">
              <!--
              var editing_option_id = -1;
              function validateForm(f)
              {
                  if (isWhitespace(f.title.value)) {
                      alert(\'Please enter the title of this custom field.\');
                      selectField(f, \'title\');
                      return false;
                  }
                  if (!hasOneSelected(f, \'projects[]\')) {
                      alert(\'Please assign the appropriate projects for this custom field.\');
                      selectField(f, \'projects[]\');
                      return false;
                  }
                  // warn if they have de-selected a project
                  var selections = getSelectedItems(getFormElement(f, \'projects[]\'));
                  var removed_projects = \'\';
                  for (var i=0; i < selected_projects.length; i++) {
                      var is_still_selected = false;
                      for (var j=0; j < selections.length; j++) {
                          if (selections[j].value == selected_projects[i].value) {
                              is_still_selected = true;
                          }
                      }
                      if (is_still_selected == false) {
                          if (removed_projects.length > 0) {
                              removed_projects += \', \';
                          }
                          removed_projects += "\'" + selected_projects[i].text + "\'";
                      }
                  }
                  if (removed_projects.length > 0) {
                      var answer = confirm(\'WARNING: You have removed project(s) \' + removed_projects + \' from the list \' +
                         \'of associated projects. This will remove all data for this field from the selected project(s). \' +
                         \'Do you want to continue?\');
                      if (answer == false) {
                          return false;
                      }
                  }
                  if ((f.field_type[2].checked) || (f.field_type[3].checked)) {
                      // select all of the options in the select box
                      selectAllOptions(f, \'field_options[]\');
                  }
                  return true;
              }
              function addFieldOption(f)
              {
                  var value = f.new_value.value;
                  if (isWhitespace(value)) {
                      alert(\'Please enter the new value for the combo box.\');
                      f.new_value.value = \'\';
                      f.new_value.focus();
                      return false;
                  }
                  var field = getFormElement(f, \'field_options[]\');
                  var current_length = field.options.length;
                  if (current_length == 1) {
                      if (field.options[0].value == -1) {
                          removeFieldOption(f, true);
                      }
                  }
                  // check for an existing option with the same value
                  for (var i = 0; i < field.options.length; i++) {
                      if (field.options[i].text == value) {
                          alert(\'The specified value already exists in the list of options.\');
                          f.new_value.focus();
                          return false;
                      }
                  }
                  current_length = field.options.length;
                  field.options[current_length] = new Option(value, \'new:\' + value);
                  f.new_value.value = \'\';
                  f.new_value.focus();
              }
              function parseParameters(value)
              {
                  value = value.substring(value.indexOf(\':\')+1);
                  var id = value.substring(0, value.indexOf(\':\'));
                  var text = value.substring(value.indexOf(\':\')+1);
                  return new Option(text, id);
              }
              function updateFieldOption(f)
              {
                  if (isWhitespace(f.new_value.value)) {
                      alert(\'Please enter the updated value.\');
                      f.new_value.value = \'\';
                      f.new_value.focus();
                      return false;
                  }
                  var field = getFormElement(f, \'field_options[]\');
                  for (var i = 0; i < field.options.length; i++) {
                      if (field.options[i].value == editing_option_id) {
                          var params = parseParameters(field.options[i].value);
                          field.options[i].value = \'existing:\' + params.value + \':\' + f.new_value.value;
                          field.options[i].text = f.new_value.value;
                          f.new_value.value = \'\';
                          f.update_button.disabled = true;
                      }
                  }
              }
              function editFieldOption(f)
              {
                  var options = getSelectedItems(getFormElement(f, \'field_options[]\'));
                  if (options.length == 0) {
                      alert(\'Please select an option from the list.\');
                      return false;
                  }
                  editing_option_id = options[0].value;
                  f.new_value.value = options[0].text;
                  f.new_value.focus();
                  f.update_button.disabled = false;
              }
              function removeFieldOption(f, delete_first)
              {
                  if (delete_first != null) {
                      var remove = new Array(\'-1\');
                  } else {
                      var options = getSelectedItems(getFormElement(f, \'field_options[]\'));
                      if (options.length == 0) {
                          alert(\'Please select an option from the list.\');
                          return false;
                      }
                      var remove = new Array();
                      for (var i = 0; i < options.length; i++) {
                          remove[remove.length] = options[i].value;
                      }
                  }
                  for (var i = 0; i < remove.length; i++) {
                      removeOptionByValue(f, \'field_options[]\', remove[i]);
                  }
                  var field = getFormElement(f, \'field_options[]\');
                  if ((delete_first == null) && (field.options.length == 0)) {
                      field.options[0] = new Option(\'enter a new option above\', \'-1\');
                  }
              }
              function toggleCustomOptionsField(show_field)
              {
                  var f = getForm(\'custom_field_form\');
                  f.new_value.disabled = show_field;
                  var field = getFormElement(f, \'field_options[]\');
                  field.disabled = show_field;
                  f.add_button.disabled = show_field;
                  f.remove_button.disabled = show_field;
                  if (f.edit_button) {
                      f.edit_button.disabled = show_field;
                  }
                  return true;
              }
              function checkRequiredFields()
              {
                  var f = getForm(\'custom_field_form\');
                  f.report_form_required.disabled = !(f.report_form.checked);
                  if (f.report_form_required.disabled) {
                      f.report_form_required.checked = false;
                  }
                  f.anon_form_required.disabled = !(f.anon_form.checked);
                  if (f.anon_form_required.disabled) {
                      f.anon_form_required.checked = false;
                  }
              }
              '; ?>

              var selected_projects = new Array();
              <?php if (count($_from = (array)$this->_tpl_vars['info']['projects'])):
    foreach ($_from as $this->_tpl_vars['prj_id']):
?>
              selected_projects[selected_projects.length] = new Option("<?php echo $this->_tpl_vars['project_list'][$this->_tpl_vars['prj_id']]; ?>
", <?php echo $this->_tpl_vars['prj_id']; ?>
);
              <?php endforeach; unset($_from); endif; ?>
              //-->
              </script>
              <form name="custom_field_form" onSubmit="javascript:return validateForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
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
                  <b>Manage Custom Fields</b>
                </td>
              </tr>
              <?php if ($this->_tpl_vars['result'] != ""): ?>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
                  <?php if ($_POST['cat'] == 'new'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to add the new custom field.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the custom field was added successfully.
                    <?php endif; ?>
                  <?php elseif ($_POST['cat'] == 'update'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to update the custom field information.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the custom field was updated successfully.
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Title:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="text" name="title" size="40" class="default" value="<?php echo $this->_tpl_vars['info']['fld_title']; ?>
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
                  <b>Short Description:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="text" name="description" size="30" class="default" value="<?php echo $this->_tpl_vars['info']['fld_description']; ?>
">
                  <span class="small_default">(it will show up by the side of the field)</span>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Assigned Projects:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <select name="projects[]" multiple size="3" class="default">
                  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['project_list'],'selected' => $this->_tpl_vars['info']['projects']), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "projects[]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Target Forms:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <span class="default">
                  <input type="checkbox" name="report_form" value="1" <?php if ($this->_tpl_vars['info']['fld_report_form'] == 1): ?>checked<?php endif; ?> onclick="checkRequiredFields();"> <a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('custom_field_form', 'report_form', 0);checkRequiredFields();">Report Form</a><br />
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="report_form_required" value="1" <?php if ($this->_tpl_vars['info']['fld_report_form_required'] == 1): ?>checked<?php endif; ?>> <a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('custom_field_form', 'report_form_required', 0);">Required Field</a><br />
                  <input type="checkbox" name="anon_form" value="1" <?php if ($this->_tpl_vars['info']['fld_anonymous_form'] == 1): ?>checked<?php endif; ?> onclick="checkRequiredFields();"> <a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('custom_field_form', 'anon_form', 0);checkRequiredFields();">Anonymous Form</a><br />
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="anon_form_required" value="1" <?php if ($this->_tpl_vars['info']['fld_anonymous_form_required'] == 1): ?>checked<?php endif; ?>> <a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('custom_field_form', 'anon_form_required', 0);">Required Field</a>
                  </span>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Display on List Issues Page:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <span class="default">
                  <input type="radio" name="list_display" value="1" <?php if ($this->_tpl_vars['info']['fld_list_display'] == 1): ?>checked<?php endif; ?>>
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('custom_field_form', 'list_display', 0);">Yes</a>
                  <input type="radio" name="list_display" value="0" <?php if ($this->_tpl_vars['info']['fld_list_display'] != 1): ?>checked<?php endif; ?>>
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('custom_field_form', 'list_display', 1);">No</a>
                  </span>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Field Type:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
                  <input type="radio" name="field_type" value="text" <?php if ($this->_tpl_vars['info']['fld_type'] == 'text'): ?>checked<?php endif; ?> onClick="javascript:toggleCustomOptionsField(true);">
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('custom_field_form', 'field_type', 0);toggleCustomOptionsField(true);">Text Input</a>&nbsp;
                  <input type="radio" name="field_type" value="textarea" <?php if ($this->_tpl_vars['info']['fld_type'] == 'textarea'): ?>checked<?php endif; ?> onClick="javascript:toggleCustomOptionsField(true);">
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('custom_field_form', 'field_type', 1);toggleCustomOptionsField(true);">Textarea</a>&nbsp;
                  <input type="radio" name="field_type" value="combo" <?php if ($this->_tpl_vars['info']['fld_type'] == 'combo'): ?>checked<?php endif; ?> onClick="javascript:toggleCustomOptionsField(false);">
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('custom_field_form', 'field_type', 2);toggleCustomOptionsField(false);">Combo Box</a>&nbsp;
                  <input type="radio" name="field_type" value="multiple" <?php if ($this->_tpl_vars['info']['fld_type'] == 'multiple'): ?>checked<?php endif; ?> onClick="javascript:toggleCustomOptionsField(false);">
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('custom_field_form', 'field_type', 3);toggleCustomOptionsField(false);">Multiple Combo Box</a>
                  <input type="radio" name="field_type" value="date" <?php if ($this->_tpl_vars['info']['fld_type'] == 'date'): ?>checked<?php endif; ?> onClick="javascript:toggleCustomOptionsField(false);">
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('custom_field_form', 'field_type', 4);toggleCustomOptionsField(true);">Date</a>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Field Options:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <table bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                      <td rowspan="2"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/blank.gif" width="30" height="1"></td>
                      <td valign="top">
                        <span class="default"><b>Set available options:</b></span><br />
                        <input class="default" type="text" name="new_value" size="26"><input class="shortcut" name="add_button" type="button" value="Add" onClick="javascript:addFieldOption(this.form);"><?php if ($_GET['cat'] == 'edit'): ?><input class="shortcut" name="update_button" type="button" value="Update Value" disabled onClick="javascript:updateFieldOption(this.form);"><?php endif; ?><br />
                      </td>
                      <td rowspan="3" class="default">
                        &nbsp;&nbsp;&nbsp;&nbsp;<b>OR</b>&nbsp;&nbsp;&nbsp;&nbsp;
                      </td>
                      <td valign="top">
                        <span class="default"><b>Choose Custom Field Backend:</b></span><br />
                        <select name="custom_field_backend" class="default">
                          <option value="" label="Please select a backend">Please select a backend</option>
                          <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['backend_list'],'selected' => $this->_tpl_vars['info']['fld_backend']), $this);?>

                        </select>
                      </td>
                      <td rowspan="3"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/blank.gif" width="30" height="1"></td>
                    </tr>
                    <tr>
                      <td>
                        <table border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>
                              <select name="field_options[]" multiple size="3" class="default">
                              <?php if ($this->_tpl_vars['info']['field_options'] == "" || $this->_tpl_vars['info']['fld_backend'] != ''): ?>
                                <option value="-1">enter a new option above</option>
                              <?php else: ?>
                                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['info']['field_options']), $this);?>

                              <?php endif; ?>
                              </select>
                            </td>
                            <td valign="top">
                              <?php if ($_GET['cat'] == 'edit'): ?>
                              <input class="shortcut" type="button" name="edit_button" value="Edit Option" onClick="javascript:editFieldOption(this.form);"><br />
                              <?php endif; ?>
                              <input class="shortcut" type="button" name="remove_button" value="Remove" onClick="javascript:removeFieldOption(this.form);">
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Minimum Role:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <select name="min_role" class="default">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_roles'],'selected' => $this->_tpl_vars['info']['fld_min_role']), $this);?>

                  </select>
                </td>
              </tr>
              <tr>
                <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Rank:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="text" name="rank" value="<?php echo $this->_tpl_vars['info']['fld_rank']; ?>
" size="3" class="default">
                </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                  <?php if ($_GET['cat'] == 'edit'): ?>
                  <input class="button" type="submit" value="Update Custom Field">
                  <?php else: ?>
                  <input class="button" type="submit" value="Create Custom Field">
                  <?php endif; ?>
                  <input class="button" type="reset" value="Reset">
                </td>
              </tr>
              </form>
              <tr>
                <td colspan="2" class="default">
                  <b>Existing Custom Fields:</b>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <script language="JavaScript">
                  <!--
                  <?php echo '
                  function checkDelete(f)
                  {
                      if (!hasOneChecked(f, \'items[]\')) {
                          alert(\'Please select at least one of the custom fields.\');
                          return false;
                      }
                      if (!confirm(\'This action will permanently remove the selected custom fields.\')) {
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
                      <td nowrap bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Rank</b></td>
                      <td width="15%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Title</b></td>
                      <td width="20%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Assigned Projects</b></td>
                      <td width="10%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Min. Role</b></td>
                      <td width="5%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Type</b></td>
                      <td width="50%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Options</b></td>
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
                        <input type="checkbox" name="items[]" value="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_id']; ?>
" <?php if ($this->_sections['i']['total'] == 0): ?>disabled<?php endif; ?>>
                      </td>
                      <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default" nowrap>
                        &nbsp;<a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=change_rank&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_id']; ?>
&direction=1" title="move field down"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/desc.gif" border="0"></a> <?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_rank']; ?>

                        <a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=change_rank&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_id']; ?>
&direction=-1" title="move field up"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/asc.gif" border="0"></a>
                      </td>
                      <td width="15%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_id']; ?>
" title="update this entry"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_title']; ?>
</a>
                      </td>
                      <td width="20%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['projects']; ?>

                      </td>
                      <td width="10%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['min_role_name']; ?>

                      </td>
                      <td width="5%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        <nobr>&nbsp;<?php if ($this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_type'] == 'combo'): ?>Combo Box<?php elseif ($this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>Multiple Combo Box<?php elseif ($this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_type'] == 'textarea'): ?>Textarea<?php elseif ($this->_tpl_vars['list'][$this->_sections['i']['index']]['fld_type'] == 'date'): ?>Date<?php else: ?>Text Input<?php endif; ?></nobr>
                      </td>
                      <td width="50%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['field_options']; ?>

                      </td>
                    </tr>
                    <?php endfor; else: ?>
                    <tr>
                      <td colspan="7" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                        <i>No custom fields could be found.</i>
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td width="4" align="center" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                        <input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');">
                      </td>
                      <td colspan="6" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
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
      <?php echo '
      <script language="JavaScript">
      <!--
      window.onload = setCustomOptionsField;
      function setCustomOptionsField()
      {
          var f = getForm(\'custom_field_form\');
          var field1 = getFormElement(f, \'field_type\', 0);
          var field2 = getFormElement(f, \'field_type\', 1);
          var field3 = getFormElement(f, \'field_type\', 4);
          if (field1.checked || field2.checked || field3.checked) {
              toggleCustomOptionsField(true);
          } else {
              toggleCustomOptionsField(false);
          }
          checkRequiredFields();
      }
      //-->
      </script>
      '; ?>

