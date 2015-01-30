<?php /* Smarty version 2.6.2, created on 2006-10-19 17:14:22
         compiled from manage/issue_auto_creation.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'manage/issue_auto_creation.tpl.html', 19, false),array('function', 'html_options', 'manage/issue_auto_creation.tpl.html', 118, false),)), $this); ?>

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
                  var field1 = getFormElement(f, \'issue_auto_creation\', 0);
                  var field2 = getFormElement(f, \'issue_auto_creation\', 1);
                  if ((!field1.checked) && (!field2.checked)) {
                      alert(\'Please choose whether the issue auto creation feature should be allowed or not for this email account\');
                      return false;
                  }
                  if (field1.checked) {
                      '; ?>

                      <?php if (count($this->_tpl_vars['cats']) > 0): ?>
                      <?php echo '
                      var field = getFormElement(f, \'options[category]\');
                      if (field.selectedIndex == 0) {
                          selectField(f, \'options[category]\');
                          alert(\'Please choose the default category.\');
                          return false;
                      }
                      '; ?>

                      <?php endif; ?>
                      <?php echo '
                      field = getFormElement(f, \'options[priority]\');
                      if (field.selectedIndex == 0) {
                          selectField(f, \'options[priority]\');
                          alert(\'Please choose the default priority.\');
                          return false;
                      }
                  }
                  return true;
              }
              function disableFields(f, bool)
              {
                  if (bool) {
                      var bgcolor = \'#CCCCCC\';
                  } else {
                      var bgcolor = \'#FFFFFF\';
                  }
                  var field = getFormElement(f, \'options[only_known_customers]\', 0);
                  if (field) {
                      field.disabled = bool;
                      field = getFormElement(f, \'options[only_known_customers]\', 1);
                      field.disabled = bool;
                  }
                  '; ?>

                  <?php if (count($this->_tpl_vars['cats']) > 0): ?>
                  <?php echo '
                  field = getFormElement(f, \'options[category]\');
                  field.disabled = bool;
                  field.style.backgroundColor = bgcolor;
                  '; ?>

                  <?php endif; ?>
                  <?php echo '
                  field = getFormElement(f, \'options[priority]\');
                  field.disabled = bool;
                  field.style.backgroundColor = bgcolor;
                  field = getFormElement(f, \'options[users][]\');
                  field.disabled = bool;
                  field.style.backgroundColor = bgcolor;
              }
              //-->
              </script>
              '; ?>

              <form name="auto_creation_form" onSubmit="javascript:return validateForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
              <input type="hidden" name="cat" value="update">
              <input type="hidden" name="ema_id" value="<?php echo $this->_tpl_vars['ema_id']; ?>
">
              <tr>
                <td colspan="2" class="default">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="default"><b>Auto-Creation of Issues</b></td>
                      <td align="right" class="default">(Associated Project: <?php echo $this->_tpl_vars['prj_title']; ?>
)</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td width="130" nowrap bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Auto-Creation of Issues: *</b>
                </td>
                <td width="80%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
                  <input type="radio" name="issue_auto_creation" value="enabled" <?php if ($this->_tpl_vars['info']['ema_issue_auto_creation'] == 'enabled'): ?>checked<?php endif; ?> onClick="javascript:disableFields(getForm('auto_creation_form'), false);"> 
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('auto_creation_form', 'issue_auto_creation', 0);disableFields(getForm('auto_creation_form'), false);">Enabled</a>&nbsp;&nbsp;
                  <input type="radio" name="issue_auto_creation" value="disabled" <?php if ($this->_tpl_vars['info']['ema_issue_auto_creation'] == 'disabled'): ?>checked<?php endif; ?> onClick="javascript:disableFields(getForm('auto_creation_form'), true);"> 
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('auto_creation_form', 'issue_auto_creation', 1);disableFields(getForm('auto_creation_form'), true);">Disabled</a>
                </td>
              </tr>
              <?php if ($this->_tpl_vars['uses_customer_integration']): ?>
              <tr>
                <td width="130" nowrap bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Only for Known Customers? *</b>
                </td>
                <td width="80%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
                  <input type="radio" name="options[only_known_customers]" value="yes" <?php if ($this->_tpl_vars['options']['only_known_customers'] == 'yes'): ?>checked<?php endif; ?>> 
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('auto_creation_form', 'options[only_known_customers]', 0);">Yes</a>&nbsp;&nbsp;
                  <input type="radio" name="options[only_known_customers]" value="no" <?php if ($this->_tpl_vars['options']['only_known_customers'] == 'no'): ?>checked<?php endif; ?>> 
                  <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('auto_creation_form', 'options[only_known_customers]', 1);">No</a>
                </td>
              </tr>
              <?php else: ?>
              <input type="hidden" name="options[only_known_customers]" value="no">
              <?php endif; ?>
              <?php if (count($this->_tpl_vars['cats']) > 0): ?>
              <tr>
                <td width="130" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Default Category: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <select name="options[category]" class="default" tabindex="2">
                    <option value="-1">Please choose a category</option>
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['cats'],'selected' => $this->_tpl_vars['options']['category']), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "options[category]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td width="130" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Default Priority: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <select name="options[priority]" class="default" tabindex="3">
                    <option value="-1">Please choose a priority</option>
                    <?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['priorities']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <option value="<?php echo $this->_tpl_vars['priorities'][$this->_sections['i']['index']]['pri_id']; ?>
" <?php if ($this->_tpl_vars['priorities'][$this->_sections['i']['index']]['pri_id'] == $this->_tpl_vars['options']['priority']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['priorities'][$this->_sections['i']['index']]['pri_title']; ?>
</option>
                    <?php endfor; endif; ?>
                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "options[priority]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Assignment:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <select name="options[users][]" multiple size="3" class="default" tabindex="4">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['options']['users']), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "options[users][]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                  <input class="button" type="submit" value="Update Setup">
                  <input class="button" type="reset" value="Reset">
                </td>
              </tr>
              </form>
            </table>
          </td>
        </tr>
      </table>
      <?php echo '
      <script language="JavaScript">
      <!--
      window.onload = setDisabledFields;
      function setDisabledFields()
      {
          var f = getForm(\'auto_creation_form\');
          var field1 = getFormElement(f, \'issue_auto_creation\', 0);
          if (field1.checked) {
              disableFields(f, false);
          } else {
              field1 = getFormElement(f, \'issue_auto_creation\', 1);
              field1.checked = true;
              disableFields(f, true);
          }
      }
      //-->
      </script>
      '; ?>

