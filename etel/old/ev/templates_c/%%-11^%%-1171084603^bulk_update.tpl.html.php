<?php /* Smarty version 2.6.2, created on 2006-10-26 03:22:57
         compiled from bulk_update.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_display_style', 'bulk_update.tpl.html', 1, false),array('function', 'html_options', 'bulk_update.tpl.html', 34, false),array('modifier', 'count', 'bulk_update.tpl.html', 14, false),)), $this); ?>
<table id="bulk_update1" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center" <?php echo smarty_function_get_display_style(array('element_name' => 'bulk_update'), $this);?>
>
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="1" cellpadding="4">
        <tr>
          <td bgcolor="#FFFFFF" class="default" colspan="3">
            <b>Bulk Update Tool</b>
          </td>
        </tr>
        <tr>
          <?php $this->assign('colspan', 2); ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Assignment</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Status</td>
          <?php if (count($this->_tpl_vars['releases']) > 0): ?>
          <?php $this->assign('colspan', $this->_tpl_vars['colspan']+1); ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Release</td>
          <?php endif; ?>
          <?php if (count($this->_tpl_vars['priorities']) > 0): ?>
          <?php $this->assign('colspan', $this->_tpl_vars['colspan']+1); ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Priority</td>
          <?php endif; ?>
          <?php if (count($this->_tpl_vars['categories']) > 0): ?>
          <?php $this->assign('colspan', $this->_tpl_vars['colspan']+1); ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Category</td>
          <?php endif; ?>
          <?php if (count($this->_tpl_vars['active_projects']) > 0): ?>
          <?php $this->assign('colspan', $this->_tpl_vars['colspan']+1); ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Project</td>
          <?php endif; ?>
        </tr>
        <tr>
          <td>
              <select name="users[]" class="default" size="5" multiple>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users']), $this);?>

              </select>
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "users[]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
          <td>
              <select name="status" class="default">
                <option value=""></option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['open_status']), $this);?>

              </select>
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'status')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
          <?php if (count($this->_tpl_vars['releases']) > 0): ?>
          <td>
              <select name="release" class="default">
                <option value=""></option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['available_releases']), $this);?>

              </select>
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'release')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
          <?php endif; ?>
          <?php if (count($this->_tpl_vars['priorities']) > 0): ?>
          <td>
              <select name="priority" class="default">
                <option value=""></option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['priorities']), $this);?>

              </select>
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'priority')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
          <?php endif; ?>
          <?php if (count($this->_tpl_vars['categories']) > 0): ?>
          <td>
              <select name="category" class="default">
                <option value=""></option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['categories']), $this);?>

              </select>
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'category')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
          <?php endif; ?>
          <?php if (count($this->_tpl_vars['active_projects']) > 0): ?>
          <td>
              <select name="project" class="default">
                <option value=""></option>
				  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['active_projects']), $this);?>

              </select>
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'project')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
          <?php endif; ?>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="<?php echo $this->_tpl_vars['colspan']; ?>
" align="center">
            <input type="button" value="Bulk Update" onclick="bulkUpdate()" class="button">
            <input type="button" value="Reset" class="button" onclick="resetBulkUpdate()">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>