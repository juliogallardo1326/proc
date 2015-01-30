<?php /* Smarty version 2.6.2, created on 2006-11-02 12:34:19
         compiled from reports/recent_activity.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'reports/recent_activity.tpl.html', 37, false),array('function', 'html_select_date', 'reports/recent_activity.tpl.html', 57, false),array('function', 'cycle', 'reports/recent_activity.tpl.html', 142, false),array('modifier', 'default', 'reports/recent_activity.tpl.html', 46, false),array('modifier', 'count', 'reports/recent_activity.tpl.html', 118, false),array('modifier', 'nl2br', 'reports/recent_activity.tpl.html', 155, false),array('modifier', 'htmlspecialchars', 'reports/recent_activity.tpl.html', 195, false),array('modifier', 'escape', 'reports/recent_activity.tpl.html', 250, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('extra_title' => 'Recent Activity')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script language="javascript">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../js/httpclient.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../js/expandable_cell.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>
<br />
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
" name="recent_activity">
<input type="hidden" name="cat" value="generate">
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="2" class="default_white">
            <b>Recent Activity Report</b>
          </td>
        </tr>
        <tr>
          <td width="120" class="default">
            <b>Report Type:</b>
          </td>
          <td width="200" class="default" NOWRAP>
            <input type="radio" name="report_type" value="recent" class="default" <?php if ($_REQUEST['report_type'] != 'range'): ?>checked<?php endif; ?> onClick="changeType('recent');">
                <a id="link" class="link" href="javascript:void(null)"
                            onClick="javascript:checkRadio('recent_activity', 'report_type', 0);changeType('recent');">Recent</a>&nbsp;
            <input type="radio" name="report_type" value="range" <?php if ($_REQUEST['report_type'] == 'range'): ?>CHECKED<?php endif; ?> onClick="changeType('range');">
                <a id="link" class="link" href="javascript:void(null)"
                            onClick="javascript:checkRadio('recent_activity', 'report_type', 1);changeType('range');">Date Range</a>&nbsp;
          </td>
        </tr>
        <tr>
          <td width="120" class="default">
            <b>Activity Type:</b>
          </td>
          <td width="200">
            <select name="activity_types[]" size="5" multiple class="default">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['type_list'],'selected' => $this->_tpl_vars['activity_types']), $this);?>

            </select>
          </td>
        </tr>
        <tr id="recent_row">
          <td width="120" class="default">
            <b>Activity in Past:</b>
          </td>
          <td width="200">
            <input type="text" size="3" name="amount" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['amount'])) ? $this->_run_mod_handler('default', true, $_tmp, 36) : smarty_modifier_default($_tmp, 36)); ?>
" class="default" style="text-align: right">&nbsp;
            <select name="unit" class="default">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['units'],'selected' => $this->_tpl_vars['unit']), $this);?>

            </select>
          </td>
        </tr>
        <tr id="start_row">
          <td width="120" class="default">
            <b>Start:</b>
          </td>
          <td width="200">
            <?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['start_date'],'prefix' => "",'field_array' => 'start','start_year' => "-2",'end_year' => "+1",'field_order' => 'YMD','month_format' => "%b",'all_extra' => "class='default'"), $this);?>

          </td>
        </tr>
        <tr id="end_row">
          <td width="120" class="default">
            <b>End:</b>
          </td>
          <td width="200">
            <?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['end_date'],'prefix' => "",'field_array' => 'end','start_year' => "-2",'end_year' => "+1",'field_order' => 'YMD','month_format' => "%b",'all_extra' => "class='default'"), $this);?>

          </td>
        </tr>
        <tr>
          <td width="120" class="default">
            <b>Developer:</b>
          </td>
          <td width="200">
            <select class="default" name="developer">
                <option value="" label="All">All</option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['developer']), $this);?>

            </select>
          </td>
        </tr>
        <tr>
          <td width="120" class="default">
            <b>Sort Order:</b>
          </td>
          <td width="200">
            <select class="default" name="sort_order">
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
<script language="JavaScript">
<?php echo '
function changeType(type) {
    if (type == \'range\') {
        document.getElementById(\'recent_row\').style.display = \'none\';
        document.getElementById(\'start_row\').style.display = getDisplayStyle();
        document.getElementById(\'end_row\').style.display = getDisplayStyle();
    } else {
        document.getElementById(\'recent_row\').style.display = getDisplayStyle();
        document.getElementById(\'start_row\').style.display = \'none\';
        document.getElementById(\'end_row\').style.display = \'none\';
    }
}
'; ?>


changeType('<?php echo $_REQUEST['report_type']; ?>
');
</script>

<?php if (count($this->_tpl_vars['data']) > 0):  if ($this->_tpl_vars['data']['phone'] != ''): ?>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="<?php if ($this->_tpl_vars['has_customer_integration']): ?>8<?php else: ?>7<?php endif; ?>" class="default_white">
            <b>Recent Phone Calls</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" NOWRAP><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/buttons.tpl.html", 'smarty_include_vars' => array('remote_func' => 'getPhoneSupport','ec_id' => 'phone')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Issue ID</td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Customer</td>
          <?php endif; ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Date</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Developer</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Type</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Line</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Description</td>
        </tr>
        <?php if (count($_from = (array)$this->_tpl_vars['data']['phone'])):
    foreach ($_from as $this->_tpl_vars['row']):
?>
        <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

        <tr>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" NOWRAP><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/buttons.tpl.html", 'smarty_include_vars' => array('ec_id' => 'phone','list_id' => $this->_tpl_vars['row']['phs_id'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row']['sta_color']; ?>
" align="right">
            <a target="_blank" href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['row']['phs_iss_id']; ?>
" class="link"><?php echo $this->_tpl_vars['row']['phs_iss_id']; ?>
</a>
          </td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['customer']; ?>
</td>
          <?php endif; ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" nowrap><?php echo $this->_tpl_vars['row']['date']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['usr_full_name']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['phs_type']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['phc_title']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['phs_description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
        </tr>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/body.tpl.html", 'smarty_include_vars' => array('ec_id' => 'phone','list_id' => $this->_tpl_vars['row']['phs_id'],'colspan' => '8')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endforeach; unset($_from); else: ?>
        <tr>
            <td colspan="8" class="default" align="center" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
"><i>No Phone Calls Found</i></td>
        </tr>
        <?php endif; ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['data']['note'] != ''): ?>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="<?php if ($this->_tpl_vars['has_customer_integration']): ?>6<?php else: ?>5<?php endif; ?>" class="default_white">
            <b>Recent Notes</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" nowrap><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/buttons.tpl.html", 'smarty_include_vars' => array('remote_func' => 'getNote','ec_id' => 'note')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Issue ID</td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Customer</td>
          <?php endif; ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Posted Date</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">User</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Title</td>
        </tr>
        <?php if (count($_from = (array)$this->_tpl_vars['data']['note'])):
    foreach ($_from as $this->_tpl_vars['row']):
?>
        <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

        <tr>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" NOWRAP><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/buttons.tpl.html", 'smarty_include_vars' => array('ec_id' => 'note','list_id' => $this->_tpl_vars['row']['not_id'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row']['sta_color']; ?>
" align="right">
            <a target="_blank" href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['row']['not_iss_id']; ?>
" class="link" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['iss_summary'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><?php echo $this->_tpl_vars['row']['not_iss_id']; ?>
</a>
          </td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['customer']; ?>
</td>
          <?php endif; ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" nowrap><?php echo $this->_tpl_vars['row']['date']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['usr_full_name']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['not_title'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
        </tr>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/body.tpl.html", 'smarty_include_vars' => array('ec_id' => 'note','list_id' => $this->_tpl_vars['row']['not_id'],'colspan' => '6')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endforeach; unset($_from); else: ?>
        <tr>
            <td colspan="6" class="default" align="center" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
"><i>No Notes Found</i></td>
        </tr>
        <?php endif; ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['data']['email'] != ''): ?>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="<?php if ($this->_tpl_vars['has_customer_integration']): ?>7<?php else: ?>6<?php endif; ?>" class="default_white">
            <b>Recent Emails</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" align="center" NOWRAP><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/buttons.tpl.html", 'smarty_include_vars' => array('ec_id' => 'email','remote_func' => 'getEmail')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" nowrap>Issue ID</td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Customer</td>
          <?php endif; ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">From</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">To</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Date</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Subject</td>
        </tr>
        <?php if (count($_from = (array)$this->_tpl_vars['data']['email'])):
    foreach ($_from as $this->_tpl_vars['row']):
?>
        <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

        <tr>
          <td align="center" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" NOWRAP align="center">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/buttons.tpl.html", 'smarty_include_vars' => array('ec_id' => 'email','list_id' => $this->_tpl_vars['row']['composite_id'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row']['sta_color']; ?>
" align="right">
            <a target="_blank" href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['row']['sup_iss_id']; ?>
" class="link" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['iss_summary'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><?php echo $this->_tpl_vars['row']['sup_iss_id']; ?>
</a>
          </td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['customer']; ?>
</td>
          <?php endif; ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['sup_from'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
            <?php if ($this->_tpl_vars['row']['sup_to'] == ""): ?>
              <i>sent to notification list</i>
            <?php else: ?>
              <?php echo ((is_array($_tmp=$this->_tpl_vars['row']['sup_to'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

            <?php endif; ?>
          </td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" nowrap><?php echo $this->_tpl_vars['row']['date']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['sup_subject'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
        </tr>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/body.tpl.html", 'smarty_include_vars' => array('ec_id' => 'email','list_id' => $this->_tpl_vars['row']['composite_id'],'colspan' => 7,'row_color' => $this->_tpl_vars['row_color'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endforeach; unset($_from); else: ?>
        <tr>
            <td colspan="7" class="default" align="center" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
"><i>No Emails Found</i></td>
        </tr>
        <?php endif; ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['data']['draft'] != ''): ?>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="<?php if ($this->_tpl_vars['has_customer_integration']): ?>8<?php else: ?>7<?php endif; ?>" class="default_white">
            <b>Recent Drafts</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" NOWRAP><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/buttons.tpl.html", 'smarty_include_vars' => array('remote_func' => 'getDraft','ec_id' => 'draft')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Issue ID</td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Customer</td>
          <?php endif; ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Status</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">From</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">To</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Date</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Subject</td>
        </tr>
        <?php if (count($_from = (array)$this->_tpl_vars['data']['draft'])):
    foreach ($_from as $this->_tpl_vars['row']):
?>
        <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

        <tr <?php if ($this->_tpl_vars['row']['emd_status'] != 'pending'): ?>style="text-decoration: line-through;"<?php endif; ?>>
          <td class="default" NOWRAP bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/buttons.tpl.html", 'smarty_include_vars' => array('ec_id' => 'draft','list_id' => $this->_tpl_vars['row']['emd_id'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row']['sta_color']; ?>
" align="right">
            <a target="_blank" href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['row']['emd_iss_id']; ?>
" class="link"><?php echo $this->_tpl_vars['row']['emd_iss_id']; ?>
</a>
          </td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['customer']; ?>
</td>
          <?php endif; ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['emd_status']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['from'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['to'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
          </td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" nowrap><?php echo $this->_tpl_vars['row']['date']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['emd_subject'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
        </tr>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "expandable_cell/body.tpl.html", 'smarty_include_vars' => array('ec_id' => 'draft','list_id' => $this->_tpl_vars['row']['emd_id'],'colspan' => 8)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endforeach; unset($_from); else: ?>
        <tr>
            <td colspan="8" class="default" align="center" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
"><i>No Drafts Found</i></td>
        </tr>
        <?php endif; ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['data']['time'] != ''): ?>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="<?php if ($this->_tpl_vars['has_customer_integration']): ?>7<?php else: ?>6<?php endif; ?>" class="default_white">
            <b>Recent Time Entries</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Issue ID</td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Customer</td>
          <?php endif; ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Date of Work</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">User</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Time Spent</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Category</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Summary</td>
        </tr>
        <?php if (count($_from = (array)$this->_tpl_vars['data']['time'])):
    foreach ($_from as $this->_tpl_vars['row']):
?>
        <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

        <tr>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row']['sta_color']; ?>
" align="right">
            <a target="_blank" href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['row']['ttr_iss_id']; ?>
" class="link" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']['iss_summary'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : htmlspecialchars($_tmp)); ?>
"><?php echo $this->_tpl_vars['row']['ttr_iss_id']; ?>
</a>
          </td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['customer']; ?>
</td>
          <?php endif; ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" nowrap><?php echo $this->_tpl_vars['row']['date']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['usr_full_name']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['time_spent']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['ttc_title']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['ttr_summary'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
        </tr>
        <?php endforeach; unset($_from); else: ?>
        <tr>
            <td colspan="7" class="default" align="center" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
"><i>No Time Entries Found</i></td>
        </tr>
        <?php endif; ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['data']['reminder'] != ''): ?>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="<?php if ($this->_tpl_vars['has_customer_integration']): ?>4<?php else: ?>3<?php endif; ?>" class="default_white">
            <b>Recent Reminder Actions</b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Issue ID</td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Customer</td>
          <?php endif; ?>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Date Triggered</td>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">Title</td>
        </tr>
        <?php if (count($_from = (array)$this->_tpl_vars['data']['reminder'])):
    foreach ($_from as $this->_tpl_vars['row']):
?>
        <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

        <tr>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row']['sta_color']; ?>
" align="right">
            <a target="_blank" href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['row']['rmh_iss_id']; ?>
" class="link"><?php echo $this->_tpl_vars['row']['rmh_iss_id']; ?>
</a>
          </td>
          <?php if ($this->_tpl_vars['has_customer_integration']): ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['customer']; ?>
</td>
          <?php endif; ?>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
" nowrap><?php echo $this->_tpl_vars['row']['date']; ?>
</td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
"><?php echo $this->_tpl_vars['row']['rma_title']; ?>
</td>
        <?php endforeach; unset($_from); else: ?>
        <tr>
            <td colspan="7" class="default" align="center" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
"><i>No Reminder Entries Found</i></td>
        </tr>
        <?php endif; ?>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endif;  endif; ?>
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>