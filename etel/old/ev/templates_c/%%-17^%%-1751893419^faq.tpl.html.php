<?php /* Smarty version 2.6.2, created on 2006-10-20 02:13:08
         compiled from manage/faq.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'manage/faq.tpl.html', 101, false),array('function', 'cycle', 'manage/faq.tpl.html', 194, false),array('modifier', 'escape', 'manage/faq.tpl.html', 133, false),)), $this); ?>

      <table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr>
          <td>
            <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
              <script language="JavaScript">
              <!--
              var url = '<?php echo $_SERVER['PHP_SELF']; ?>
';
              var id = '<?php echo $_GET['id']; ?>
';
              <?php echo '
              function populateComboBox(f)
              {
                  if (id == \'\') {
                      url += \'?prj_id=\' + getSelectedOption(f, \'project\');
                  } else {
                      url += \'?cat=edit&id=\' + id + \'&prj_id=\' + getSelectedOption(f, \'project\');
                  }
                  window.location.href = url;
              }
              function validateForm(f)
              {
                  if (getSelectedOption(f, \'project\') == -1) {
                      selectField(f, \'project\');
                      alert(\'Please choose the project for this FAQ entry.\');
                      return false;
                  }
                  var field = getFormElement(f, \'support_levels[]\');
                  if (field) {
                      if (!hasOneSelected(f, \'support_levels[]\')) {
                          selectField(f, \'support_levels[]\');
                          alert(\'Please assign the appropriate support levels for this FAQ entry.\');
                          return false;
                      }
                  }
                  if (isWhitespace(f.rank.value)) {
                      selectField(f, \'rank\');
                      alert(\'Please enter the rank of this FAQ entry.\');
                      return false;
                  }
                  if (!isNumberOnly(f.rank.value)) {
                      selectField(f, \'rank\');
                      alert(\'Please enter a number for the rank of this FAQ entry.\');
                      return false;
                  }
                  if (isWhitespace(f.title.value)) {
                      selectField(f, \'title\');
                      alert(\'Please enter the title of this FAQ entry.\');
                      return false;
                  }
                  return true;
              }
              //-->
              </script>
              '; ?>

              <form name="news_form" onSubmit="javascript:return validateForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
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
                  <b>Manage Internal FAQ</b>
                </td>
              </tr>
              <?php if ($this->_tpl_vars['result'] != ""): ?>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
                  <?php if ($_POST['cat'] == 'new'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to add the FAQ entry.
                    <?php elseif ($this->_tpl_vars['result'] == -2): ?>
                      Please enter the title for this FAQ entry.
                    <?php elseif ($this->_tpl_vars['result'] == -3): ?>
                      Please enter the message for this FAQ entry.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the FAQ entry was added successfully.
                    <?php endif; ?>
                  <?php elseif ($_POST['cat'] == 'update'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to update the FAQ entry information.
                    <?php elseif ($this->_tpl_vars['result'] == -2): ?>
                      Please enter the title for this FAQ entry.
                    <?php elseif ($this->_tpl_vars['result'] == -3): ?>
                      Please enter the message for this FAQ entry.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the FAQ entry was updated successfully.
                    <?php endif; ?>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Project:</b>
                </td>
                <td width="80%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <select name="project" class="default" onChange="javascript:populateComboBox(this.form);">
                    <option value="-1">Please choose an option</option>
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['project_list'],'selected' => $this->_tpl_vars['info']['faq_prj_id']), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'project')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <?php if ($this->_tpl_vars['backend_uses_support_levels']): ?>
              <tr>
                <td width="140" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <nobr><b>Assigned Support<br />Levels:</b></nobr>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <select name="support_levels[]" class="default" size="4" multiple>
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['support_levels'],'selected' => $this->_tpl_vars['info']['support_levels']), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "support_levels[]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td width="140" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Rank:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="text" size="5" class="default" name="rank" value="<?php echo $this->_tpl_vars['info']['faq_rank']; ?>
">
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'rank')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="140" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Title:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="text" name="title" size="40" class="default" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['faq_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'title')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td width="140" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                  <b>Message:</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <textarea name="message" cols="50" rows="10"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['faq_message'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'message')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                  <?php if ($_GET['cat'] == 'edit'): ?>
                  <input class="button" type="submit" value="Update FAQ Entry">
                  <?php else: ?>
                  <input class="button" type="submit" value="Create FAQ Entry">
                  <?php endif; ?>
                  <input class="button" type="reset" value="Reset">
                </td>
              </tr>
              </form>
              <tr>
                <td colspan="2" class="default">
                  <b>Existing Internal FAQ Entries:</b>
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
                          alert(\'Please select at least one of the FAQ entries.\');
                          return false;
                      }
                      if (!confirm(\'This action will permanently remove the selected FAQ entries.\')) {
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
" class="default_white" align="center">&nbsp;<b>Rank</b>&nbsp;</td>
                      <td width="<?php if ($this->_tpl_vars['backend_uses_support_levels']): ?>50%<?php else: ?>100%<?php endif; ?>" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Title</b></td>
                      <?php if ($this->_tpl_vars['backend_uses_support_levels']): ?>
                      <td width="50%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Support Levels</b></td>
                      <?php endif; ?>
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
" align="center"><input type="checkbox" name="items[]" value="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['faq_id']; ?>
"></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default" align="center" nowrap>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=change_rank&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['faq_id']; ?>
&rank=desc"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/desc.gif" border="0"></a> <?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['faq_rank']; ?>

                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=change_rank&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['faq_id']; ?>
&rank=asc"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/asc.gif" border="0"></a>
                      </td>
                      <td width="50%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['faq_id']; ?>
" title="update this entry"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['faq_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
                      </td>
                      <?php if ($this->_tpl_vars['backend_uses_support_levels']): ?>
                      <td width="50%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['support_levels'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

                      </td>
                      <?php endif; ?>
                    </tr>
                    <?php endfor; else: ?>
                    <tr>
                      <td colspan="<?php if ($this->_tpl_vars['backend_uses_support_levels']): ?>4<?php else: ?>3<?php endif; ?>" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                        <i>No FAQ entries could be found.</i>
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td width="4" align="center" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                        <input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');">
                      </td>
                      <td colspan="<?php if ($this->_tpl_vars['backend_uses_support_levels']): ?>3<?php else: ?>2<?php endif; ?>" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
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
