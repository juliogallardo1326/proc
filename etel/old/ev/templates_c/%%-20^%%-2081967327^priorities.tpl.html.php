<?php /* Smarty version 2.6.2, created on 2006-10-19 17:18:20
         compiled from manage/priorities.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'manage/priorities.tpl.html', 129, false),)), $this); ?>

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
                  if (isWhitespace(f.title.value)) {
                      selectField(f, \'title\');
                      alert(\'Please enter the title of this priority\');
                      return false;
                  }
                  if (isWhitespace(f.rank.value)) {
                      selectField(f, \'rank\');
                      alert(\'Please enter the rank of this priority\');
                      return false;
                  }
                  return true;
              }
              //-->
              </script>
              '; ?>

              <form name="priority_form" onSubmit="javascript:return validateForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
              <input type="hidden" name="prj_id" value="<?php echo $this->_tpl_vars['project']['prj_id']; ?>
">
              <?php if ($_GET['cat'] == 'edit'): ?>
              <input type="hidden" name="cat" value="update">
              <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>
">
              <?php else: ?>
              <input type="hidden" name="cat" value="new">
              <?php endif; ?>
              <tr>
                <td class="default" nowrap>
                  <b>Manage Priorities</b>
                </td>
                <td class="default" align="right">
                  (Current Project: <?php echo $this->_tpl_vars['project']['prj_title']; ?>
)
                </td>
              </tr>
              <?php if ($this->_tpl_vars['result'] != ""): ?>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
                  <?php if ($_POST['cat'] == 'new'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to add the new priority.
                    <?php elseif ($this->_tpl_vars['result'] == -2): ?>
                      Please enter the title for this new priority.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the priority was added successfully.
                    <?php endif; ?>
                  <?php elseif ($_POST['cat'] == 'update'): ?>
                    <?php if ($this->_tpl_vars['result'] == -1): ?>
                      An error occurred while trying to update the priority information.
                    <?php elseif ($this->_tpl_vars['result'] == -2): ?>
                      Please enter the title for this priority.
                    <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                      Thank you, the priority was updated successfully.
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
">
                  <input type="text" name="title" size="40" class="default" value="<?php echo $this->_tpl_vars['info']['pri_title']; ?>
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
                  <b>Rank: *</b>
                </td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <input type="text" name="rank" size="5" class="default" value="<?php echo $this->_tpl_vars['info']['pri_rank']; ?>
">
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'rank')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                  <?php if ($_GET['cat'] == 'edit'): ?>
                  <input class="button" type="submit" value="Update Priority">
                  <?php else: ?>
                  <input class="button" type="submit" value="Create Priority">
                  <?php endif; ?>
                  <input class="button" type="reset" value="Reset">
                </td>
              </tr>
              </form>
              <tr>
                <td colspan="2" class="default">
                  <b>Existing Priorities:</b>
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
                          alert(\'Please select at least one of the priorities.\');
                          return false;
                      }
                      if (!confirm(\'This action will remove the selected entries.\')) {
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
                    <input type="hidden" name="prj_id" value="<?php echo $this->_tpl_vars['project']['prj_id']; ?>
">
                    <input type="hidden" name="cat" value="delete">
                    <tr>
                      <td width="4" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" nowrap><input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');"></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" align="center">&nbsp;<b>Rank</b>&nbsp;</td>
                      <td width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Title</b></td>
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
" align="center"><input type="checkbox" name="items[]" value="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['pri_id']; ?>
"></td>
                      <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default" align="center" nowrap>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=change_rank&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['pri_id']; ?>
&prj_id=<?php echo $this->_tpl_vars['project']['prj_id']; ?>
&rank=desc"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/desc.gif" border="0"></a> <?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['pri_rank']; ?>

                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=change_rank&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['pri_id']; ?>
&prj_id=<?php echo $this->_tpl_vars['project']['prj_id']; ?>
&rank=asc"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/asc.gif" border="0"></a>
                      </td>
                      <td width="100%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                        &nbsp;<a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['pri_id']; ?>
&prj_id=<?php echo $this->_tpl_vars['project']['prj_id']; ?>
" title="update this entry"><?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['pri_title']; ?>
</a>
                      </td>
                    </tr>
                    <?php endfor; else: ?>
                    <tr>
                      <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                        <i>No priorities could be found.</i>
                      </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                      <td width="4" align="center" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                        <input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');">
                      </td>
                      <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" colspan="2">
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
