<?php /* Smarty version 2.6.2, created on 2006-10-20 00:25:17
         compiled from manage/column_display.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'manage/column_display.tpl.html', 48, false),array('function', 'html_options', 'manage/column_display.tpl.html', 60, false),)), $this); ?>
<?php if ($this->_tpl_vars['prj_id'] == ''): ?>
    <span class="default">
    This page can only be accessed in relation to a project. Please go to the project page and choose 
    "Edit Fields to Display" to access this page.<br />
    <a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/projects.php">Manage Projects</a>
    </span>
<?php else: ?>
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <form name="column_display_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
        <input type="hidden" name="cat" value="save">
        <input type="hidden" name="page" value="<?php echo $this->_tpl_vars['page']; ?>
">
        <input type="hidden" name="prj_id" value="<?php echo $this->_tpl_vars['prj_id']; ?>
">
        <tr>
          <td class="default" nowrap colspan="2">
            <b>Manage Columns to Display</b> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "help_link.tpl.html", 'smarty_include_vars' => array('topic' => 'column_display')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
          <td class="default" align="right">
            (Current Project: <?php echo $this->_tpl_vars['project_name']; ?>
)
          </td>
        </tr>
        <?php if ($this->_tpl_vars['result'] != ""): ?>
        <tr>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" class="error">
              <?php if ($this->_tpl_vars['result'] == -1): ?>
                An error occurred while trying to save columns to display.
              <?php elseif ($this->_tpl_vars['result'] == 1): ?>
                Thank you, columns to display was saved successfully.
              <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" align="center" width="40%">
            <b>Column Name</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" align="center" width="20%">
            <b>Minimum Role</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" align="center" width="40%">
            <b>Order</b>
          </td>
        </tr>
        <?php $this->assign('default_rank', 1); ?>
        <?php if (count($_from = (array)$this->_tpl_vars['available'])):
    foreach ($_from as $this->_tpl_vars['field_name'] => $this->_tpl_vars['column']):
?>
        <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" class="default" align="center" width="40%">
            <?php echo $this->_tpl_vars['column']['title']; ?>

          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" align="center" width="20%">
            <?php if ($this->_tpl_vars['selected'][$this->_tpl_vars['field_name']]['min_role'] == ''): ?>
              <?php $this->assign('selected_role', $this->_tpl_vars['column']['default_role']); ?>
            <?php else: ?>
              <?php $this->assign('selected_role', $this->_tpl_vars['selected'][$this->_tpl_vars['field_name']]['min_role']); ?>
            <?php endif; ?>
            <select name="min_role[<?php echo $this->_tpl_vars['field_name']; ?>
]" class="default">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['user_roles'],'selected' => $this->_tpl_vars['selected_role']), $this);?>

            </select>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" align="center" width="40%">
            <?php if ($this->_tpl_vars['selected'][$this->_tpl_vars['field_name']]['rank'] == ''): ?>
              <?php $this->assign('selected_rank', $this->_tpl_vars['default_rank']); ?>
            <?php else: ?>
              <?php $this->assign('selected_rank', $this->_tpl_vars['selected'][$this->_tpl_vars['field_name']]['rank']); ?>
            <?php endif; ?>
            <select name="rank[<?php echo $this->_tpl_vars['field_name']; ?>
]" class="default">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['ranks'],'selected' => $this->_tpl_vars['selected_rank']), $this);?>

            </select>
          </td>
        </tr>
        <?php $this->assign('default_rank', $this->_tpl_vars['default_rank']+1); ?>
        <?php endforeach; unset($_from); endif; ?>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center" colspan="3">
            <input type="submit" name="save" value="Save" class="button">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php endif; ?>