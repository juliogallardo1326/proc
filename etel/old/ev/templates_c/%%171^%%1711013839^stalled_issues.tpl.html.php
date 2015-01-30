<?php /* Smarty version 2.6.2, created on 2006-11-02 11:07:41
         compiled from reports/stalled_issues.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_select_date', 'reports/stalled_issues.tpl.html', 21, false),array('function', 'html_options', 'reports/stalled_issues.tpl.html', 32, false),array('modifier', 'escape', 'reports/stalled_issues.tpl.html', 69, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('extra_title' => 'Stalled Issues Report')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
" name="recent_activity">
<input type="hidden" name="cat" value="generate">
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center" width="400">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="4" class="default_white">
            <b>Stalled Issues Report</b>
          </td>
        </tr>
        <tr id="start_row">
          <td width="40%" class="default" align="center" nowrap colspan="2">
            <b>Show Issues with no Response Between</b>
          </td>
        </tr>
        <tr id="start_row">
          <td width="600%" nowrap colspan="2" align="center">
            <?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['after_date'],'prefix' => "",'field_array' => 'after','start_year' => "-2",'end_year' => "+1",'field_order' => 'YMD','month_format' => "%b",'all_extra' => "class='default'"), $this);?>

             <span class="default"> and </span>
            <?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['before_date'],'prefix' => "",'field_array' => 'before','start_year' => "-2",'end_year' => "+1",'field_order' => 'YMD','month_format' => "%b",'all_extra' => "class='default'"), $this);?>

          </td>
        </tr>
        <tr>
          <td width="30%" class="default" align="center">
            <b>Developers:</b>
          </td>
          <td width="70%">
            <select class="default" name="developers[]" multiple size="5" style="width: 100%">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['developers']), $this);?>

            </select>
          </td>
        </tr>
        <tr>
          <td width="30%" class="default" align="center">
            <b>Status:</b>
          </td>
          <td width="70%">
            <select class="default" name="status[]" multiple size="5" style="width: 100%">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['status_list'],'selected' => $this->_tpl_vars['status']), $this);?>

            </select>
          </td>
        </tr>
        <tr>
          <td class="default" align="center">
            <b>Sort Order:</b>
          </td>
          <td>
            <select class="default" name="sort_order" style="width: 100%">
                <option value="ASC" label="Ascending" <?php if ($_REQUEST['sort_order'] != 'DESC'): ?>selected<?php endif; ?>>Ascending</option>
                <option value="DESC" label="Descending" <?php if ($_REQUEST['sort_order'] == 'DESC'): ?>selected<?php endif; ?>>Descending</option>
            </select>
          </td>
        </tr>
        <tr>
          <td align="center" colspan="2">
            <input type="submit" value="Generate" class="shortcut">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>

<?php if (count($_from = (array)$this->_tpl_vars['data'])):
    foreach ($_from as $this->_tpl_vars['user_full_name'] => $this->_tpl_vars['assigned_issues']):
?>
<h4><?php echo ((is_array($_tmp=$this->_tpl_vars['user_full_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</h4>
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
          <td rowspan="2" align="center" class="default_white">Issue ID</td>
          <td rowspan="2" align="center" class="default_white">Summary</td>
          <td rowspan="2" align="center" class="default_white">Status</td>
          <td rowspan="2" align="center" class="default_white">Time Spent</td>
          <td rowspan="2" align="center" class="default_white">Created</td>
          <td rowspan="2" align="center" class="default_white">Last Response</td>
          <td colspan="2" align="center" class="default_white">Days and Hours Since</td>
        </tr>
        <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
          <td align="center" class="default_white">Last Update</td>
          <td align="center" class="default_white">Last Outgoing Msg</td>
        </tr>
        <?php if (count($_from = (array)$this->_tpl_vars['assigned_issues'])):
    foreach ($_from as $this->_tpl_vars['issue_id'] => $this->_tpl_vars['issue']):
?>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default" align="center"><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['issue_id']; ?>
" class="link" title="view issue details"><?php echo $this->_tpl_vars['issue_id']; ?>
</a></td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default"><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['issue_id']; ?>
" class="link" title="view issue details"><?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['iss_summary'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a></td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default" align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['sta_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default" align="center"><?php echo $this->_tpl_vars['issue']['time_spent']; ?>
</td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default" align="center"><?php echo $this->_tpl_vars['issue']['iss_created_date']; ?>
</td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default" align="center"><?php echo $this->_tpl_vars['issue']['iss_last_response_date']; ?>
</td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default" align="center"><?php echo $this->_tpl_vars['issue']['last_update']; ?>
</td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default" align="center"><?php echo $this->_tpl_vars['issue']['last_email_response']; ?>
</td>
        </tr>
        <?php endforeach; unset($_from); endif; ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endforeach; unset($_from); endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>