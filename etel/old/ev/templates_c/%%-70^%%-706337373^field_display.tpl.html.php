<?php /* Smarty version 2.6.2, created on 2006-10-19 19:01:15
         compiled from manage/field_display.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'manage/field_display.tpl.html', 30, false),array('function', 'html_options', 'manage/field_display.tpl.html', 37, false),)), $this); ?>
<?php if ($this->_tpl_vars['prj_id'] == ''): ?>
    <span class="default">
    This page can only be accessed in relation to a project. Please go to the project page and choose 
    "Edit Fields to Display" to access this page.<br />
    <a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/projects.php">Manage Projects</a>
    </span>
<?php else: ?>
<form name="display_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
?prj_id=<?php echo $this->_tpl_vars['prj_id']; ?>
">
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
    <tr>
        <td>
            <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
                <tr>
                    <td class="default" colspan="2">
                        <b>Edit Fields to Display</b> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help_link.tpl.html", 'smarty_include_vars' => array('topic' => 'field_display')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    </td>
                </tr>
                <?php if ($this->_tpl_vars['result'] != ""): ?>
                <tr>
                    <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
                      <?php if ($this->_tpl_vars['result'] == -1): ?>
                        An error occurred while trying to update field display settings.
                      <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                        Thank you, field display settings were updated successfully.
                      <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if (count($_from = (array)$this->_tpl_vars['fields'])):
    foreach ($_from as $this->_tpl_vars['field_name'] => $this->_tpl_vars['field_title']):
?>
                <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

                <tr>
                    <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="default_white" width="150">
                        <?php echo $this->_tpl_vars['field_title']; ?>
 Field:
                    </td>
                    <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                        <select name="min_role[<?php echo $this->_tpl_vars['field_name']; ?>
]" class="default">
                            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_roles'],'selected' => $this->_tpl_vars['display_settings'][$this->_tpl_vars['field_name']]), $this);?>

                        </select>
                    </td>
                </tr>
                <?php endforeach; unset($_from); endif; ?>
                <tr>
                <tr>
                    <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="2">
                        <input class="button" type="submit" value="Set Display Preferences">
                        <input class="button" type="reset" value="Reset">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</form>
<?php endif; ?>
