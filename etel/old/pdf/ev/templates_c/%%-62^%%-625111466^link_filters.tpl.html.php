<?php /* Smarty version 2.6.2, created on 2006-10-21 11:03:24
         compiled from manage/link_filters.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'manage/link_filters.tpl.html', 88, false),array('modifier', 'join', 'manage/link_filters.tpl.html', 179, false),array('function', 'html_options', 'manage/link_filters.tpl.html', 116, false),array('function', 'cycle', 'manage/link_filters.tpl.html', 161, false),)), $this); ?>
<?php echo '
<script language="Javascript">
function validateForm(f)
{
    if (isWhitespace(f.pattern.value)) {
        alert(\'Please enter a pattern.\');
        selectField(f, \'pattern\');
        return false;
    }
    if (f.replacement.value == \'\') {
        alert(\'Please enter a replacement value.\');
        selectField(f, \'replacement\');
        return false;
    }
    if (!hasOneSelected(f, \'projects[]\')) {
        alert(\'Please select projects this link filter should be active for.\');
        selectField(f, \'projects[]\');
        return false;
    }
    if (!hasOneSelected(f, \'usr_role\')) {
        alert(\'Please select the minimum user role that should be able to see this link filter.\');
        selectField(f, \'usr_role\');
        return false;
    }
    return true;
}

function checkDelete(f)
{
    if (!hasOneChecked(f, \'items[]\')) {
      alert(\'Please select at least one link filter.\');
      return false;
    }
    if (!confirm(\'WARNING: This action will remove the selected link filters permanently.\\nPlease click OK to confirm.\')) {
      return false;
    } else {
      return true;
    }
}
</script>
'; ?>

<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1">
    <tr>
        <td>
            <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2">
                <form name="link_filter_form" onSubmit="javascript:return validateForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
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
                        <b>Manage Link Filters</b> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help_link.tpl.html", 'smarty_include_vars' => array('topic' => 'link_filters')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    </td>
                </tr>
                <?php if ($this->_tpl_vars['result'] != ""): ?>
                <tr>
                    <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
                    <?php if ($_POST['cat'] == 'new'): ?>
                        <?php if ($this->_tpl_vars['result'] == -1): ?>
                            An error occurred while trying to add the new link filter.
                        <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                            Thank you, the link filter was added successfully.
                        <?php endif; ?>
                    <?php elseif ($_POST['cat'] == 'update'): ?>
                        <?php if ($this->_tpl_vars['result'] == -1): ?>
                            An error occurred while trying to update the link filter.
                        <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                            Thank you, the link filter was updated successfully.
                        <?php endif; ?>
                    <?php elseif ($_POST['cat'] == 'delete'): ?>
                        <?php if ($this->_tpl_vars['result'] == -1): ?>
                            An error occurred while trying to delete the link filter.
                        <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                            Thank you, the link filter was deleted successfully.
                        <?php endif; ?>
                    <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                        <b>Pattern: *</b>
                    </td>
                    <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                        <input type="text" name="pattern" size="40" class="default" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['lfi_pattern'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'pattern')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                        <b>Replacement: *</b>
                    </td>
                    <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                        <input type="text" name="replacement" size="40" class="default" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['lfi_replacement'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'replacement')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                        <b>Description:</b>
                    </td>
                    <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                        <input type="text" name="description" size="50" class="default" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['lfi_description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'description')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    </td>
                </tr>
                <tr>
                    <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
                        <nobr><b>Assigned Projects: *</b></nobr>
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
                        <b>Minimum User Role: *</b>
                    </td>
                    <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="80%">
                        <select name="usr_role" class="default">
                            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_roles'],'selected' => $this->_tpl_vars['info']['lfi_usr_role']), $this);?>

                        </select>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'usr_role')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                        <?php if ($_GET['cat'] == 'edit'): ?>
                        <input class="button" type="submit" value="Update Link Filter">
                        <?php else: ?>
                        <input class="button" type="submit" value="Create Link Filter">
                        <?php endif; ?>
                        <input class="button" type="reset" value="Reset">
                    </td>
                </tr>
                </form>
                <tr>
                    <td colspan="2" class="default">
                        <b>Existing Link Filters</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table border="0" width="100%" cellpadding="1" cellspacing="1">
                            <form name="delete_link_filters" onSubmit="javascript:return checkDelete(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
                            <tr>
                                <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" nowrap><input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');"></td>
                                <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Pattern</b></td>
                                <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Replacement</b></td>
                                <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Description</b></td>
                                <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Minimum Role</b></td>
                                <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;<b>Projects</b></td>
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
                                    <input type="checkbox" name="items[]" value="<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['lfi_id']; ?>
" <?php if ($this->_sections['i']['total'] == 0): ?>disabled<?php endif; ?>>
                                </td>
                                <td width="20%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                                    &nbsp;<a class="link" href="<?php echo $_SERVER['PHP_SELF']; ?>
?cat=edit&id=<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['lfi_id']; ?>
" title="update this entry"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['lfi_pattern'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
                                </td>
                                <td width="20%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                                    &nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['i']['index']]['lfi_replacement'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

                                </td>
                                <td width="20%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                                    &nbsp;<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['lfi_description']; ?>

                                </td>
                                <td width="20%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                                    &nbsp;<?php echo $this->_tpl_vars['list'][$this->_sections['i']['index']]['min_usr_role_name']; ?>

                                </td>
                                <td width="20%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default">
                                    &nbsp;<?php echo ((is_array($_tmp=", ")) ? $this->_run_mod_handler('join', true, $_tmp, $this->_tpl_vars['list'][$this->_sections['i']['index']]['project_names']) : join($_tmp, $this->_tpl_vars['list'][$this->_sections['i']['index']]['project_names'])); ?>

                                </td>
                            </tr>
                            <?php endfor; else: ?>
                            <tr>
                                <td colspan="6" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                                    <i>No link filters could be found.</i>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="6" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td width="4" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                                                <input type="button" value="All" class="shortcut" onClick="javascript:toggleSelectAll(this.form, 'items[]');">
                                                <input type="hidden" name="cat" value="delete">
                                            </td>
                                            <td colspan="4" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                                                <input type="submit" value="Delete" class="button">
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
</table>