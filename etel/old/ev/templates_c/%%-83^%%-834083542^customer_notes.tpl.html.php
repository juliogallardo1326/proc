<?php /* Smarty version 2.6.2, created on 2006-10-21 11:04:39
         compiled from manage/customer_notes.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'manage/customer_notes.tpl.html', 75, false),array('function', 'cycle', 'manage/customer_notes.tpl.html', 145, false),array('modifier', 'escape', 'manage/customer_notes.tpl.html', 149, false),array('modifier', 'nl2br', 'manage/customer_notes.tpl.html', 152, false),)), $this); ?>
  <table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
    <tr>
      <td>
        <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
          <script language="JavaScript">
          <!--
          var url = '<?php echo $_SERVER['PHP_SELF']; ?>
';
          var cno_id = '<?php echo $_GET['id']; ?>
';
          <?php echo '
          function populateCustomerComboBox(f)
          {
              if (cno_id == \'\') {
                  url += \'?prj_id=\' + getSelectedOption(f, \'project\');
              } else {
                  url += \'?cat=edit&id=\' + cno_id + \'&prj_id=\' + getSelectedOption(f, \'project\');
              }
              window.location.href = url;
          }
          function validateForm(f)
          {
              if (getSelectedOption(f, \'customer\') == \'\') {
                  alert(\'Please choose the customer for this new note.\');
                  selectField(f, \'customer\');
                  return false;
              }
              return true;
          }
          //-->
          </script>
          '; ?>

          <form name="release_form" onSubmit="javascript:return validateForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
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
              <b>Manage Customer Quick Notes</b>
            </td>
          </tr>
          <?php if ($this->_tpl_vars['result'] != ""): ?>
          <tr>
            <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
              <?php if ($_POST['cat'] == 'new'): ?>
                <?php if ($this->_tpl_vars['result'] == -1): ?>
                  An error occurred while trying to add the new note.
                <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                  Thank you, the note was added successfully.
                <?php endif; ?>
              <?php elseif ($_POST['cat'] == 'update'): ?>
                <?php if ($this->_tpl_vars['result'] == -1): ?>
                  An error occurred while trying to update the note.
                <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                  Thank you, the note was updated successfully.
                <?php endif; ?>
              <?php elseif ($_POST['cat'] == 'delete'): ?>
                <?php if ($this->_tpl_vars['result'] == -1): ?>
                  An error occurred while trying to delete the note.
                <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                  Thank you, the note was deleted successfully.
                <?php endif; ?>
              <?php endif; ?>
            </td>
          </tr>
          <?php endif; ?>
          <tr>
            <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
              <b>Project:</b>
            </td>
            <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
              <select name="project" class="default" onChange="javascript:populateCustomerComboBox(this.form);">
                <option value="-1">Please choose an option</option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['project_list'],'selected' => $this->_tpl_vars['info']['cno_prj_id']), $this);?>

              </select>
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'project')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </td>
          </tr>
          <tr>
            <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
              <b>Customer:</b>
            </td>
            <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select name="customer" class="default">
                <option value="">Please choose a customer</option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['customers'],'selected' => $this->_tpl_vars['info']['cno_customer_id']), $this);?>

            </select>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'customer')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </td>
          </tr>
          <tr>
            <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
              <b>Note:</b>
            </td>
            <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
              <textarea name="note" cols="40" rows="5"><?php echo $this->_tpl_vars['info']['cno_note']; ?>
</textarea>
            </td>
          </tr>
          <tr>
            <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
              <?php if ($_GET['cat'] == 'edit'): ?>
              <input class="button" type="submit" value="Update Note">
              <?php else: ?>
              <input class="button" type="submit" value="Create Note">
              <?php endif; ?>
              <input class="button" type="reset" value="Reset">
            </td>
          </tr>
          </form>
          <tr>
            <td colspan="2" class="default">
              <b>Existing Customer Quick Notes:</b>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <?php echo '
              <script language="JavaScript">
              <!--
              function checkDelete(f)
              {
                  if (!hasOneChecked(f, \'items[]\')) {
                      alert(\'Please select at least one of the notes.\');
                      return false;
                  }
                  if (!confirm(\'This action will permanently remove the selected entries.\')) {
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
                  <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" nowrap><input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');"></td>
                  <td width="50%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Customer</b></td>
                  <td width="50%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Note</b></td>
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
                  <td nowrap bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" align="center"><input type="checkbox" name="items[]" value="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['cno_id']; ?>
"></td>
                  <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                    <a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['cno_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['customer_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
                  </td>
                  <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['cno_note'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

                  </td>
                </tr>
                <?php endfor; else: ?>
                <tr>
                  <td colspan="4" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                    <i>No notes could be found.</i>
                  </td>
                </tr>
                <?php endif; ?>
                <tr>
                  <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                    <input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');">
                  </td>
                  <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
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
