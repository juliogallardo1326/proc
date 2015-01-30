<?php /* Smarty version 2.6.2, created on 2006-11-28 09:31:35
         compiled from reports/customer_stats.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'reports/customer_stats.tpl.html', 49, false),array('function', 'html_select_date', 'reports/customer_stats.tpl.html', 57, false),array('function', 'cycle', 'reports/customer_stats.tpl.html', 159, false),array('modifier', 'floor', 'reports/customer_stats.tpl.html', 169, false),array('modifier', 'formatValue', 'reports/customer_stats.tpl.html', 179, false),array('modifier', 'count', 'reports/customer_stats.tpl.html', 269, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php if ($this->_tpl_vars['no_customer_integration'] == 1): ?>
    <span class="default">The current project does not have customer integration so this report can not be viewed.</span>
<?php else: ?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
" name="customer_stats_report">
<input type="hidden" name="cat" value="generate">
<script language="Javascript">
<?php echo '
function clearDate()
{
    
    var f = document.forms[\'customer_stats_report\'];
    
    f.elements["start[Year]"].selectedIndex = 0;
    f.elements["start[Month]"].selectedIndex = 0;
    f.elements["start[Day]"].selectedIndex = 0;
    
    f.elements["end[Year]"].selectedIndex = 0;
    f.elements["end[Month]"].selectedIndex = 0;
    f.elements["end[Day]"].selectedIndex = 0;
}
'; ?>

</script>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="3" class="default_white">
            <b>Customer Stats Report - <?php echo $this->_tpl_vars['project_name']; ?>
</b>
          </td>
        </tr>
        <tr>
          <td width="30%" class="default" align="center">
            <?php if ($this->_tpl_vars['has_support_levels'] == 1): ?><b>Support Level</b><?php endif; ?>
          </td>
          <td width="40%" class="default" align="center">
            <b>Date Range</b>
          </td>
          <td width="30%" class="default" align="center">
            <b>Sections to Display</b>
          </td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <?php if ($this->_tpl_vars['has_support_levels'] == 1): ?>
            <select name="support_level[]" size="5" multiple class="default">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['support_levels'],'selected' => $this->_tpl_vars['support_level']), $this);?>

            </select>
            <?php endif; ?>
          </td>
          <td align="center" valign="top">
            <table>
                <tr>
                    <td class="default" align="right">From</td>
                    <td align="center" NOWRAP><?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['start_date'],'prefix' => "",'field_array' => 'start','start_year' => "-2",'end_year' => "+1",'field_order' => 'YMD','month_format' => "%b",'year_empty' => 'year','month_empty' => 'mon','day_empty' => 'day','all_extra' => "class='default'"), $this);?>
</td>
                </tr>
                <tr>
                    <td class="default" align="right">To</td>
                    <td align="center" NOWRAP><?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['end_date'],'prefix' => "",'field_array' => 'end','start_year' => "-2",'end_year' => "+1",'field_order' => 'YMD','month_format' => "%b",'year_empty' => 'year','month_empty' => 'mon','day_empty' => 'day','all_extra' => "class='default'"), $this);?>
</td>
                </tr>
            </table>
            <input type="button" name="clear_date" value="Clear Date" onClick="clearDate()" class="shortcut">
          </td>
          <td align="center" valign="top">
            <select name="display_sections[]" size="5" multiple class="default">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['sections'],'selected' => $this->_tpl_vars['display_sections']), $this);?>

            </select>
          </td>
        </tr>
        <tr>
          <td class="default" align="center" valign="middle" >
            <b>Options</b>
          </td>
          <td align="left" valign="bottom" class="default" colspan="2" NOWRAP>
            <input type="checkbox" name="include_expired" value="1" <?php if ($this->_tpl_vars['include_expired'] == 1): ?>CHECKED<?php endif; ?>>
            <a id="link" class="link" href="javascript:void(null)" 
                            onClick="javascript:toggleCheckbox('customer_stats_report', 'include_expired');">Include expired contracts</a>
          </td>
        </tr>
        <tr>
          <td class="default" align="center" valign="top">
          <?php if ($this->_tpl_vars['current_role'] >= $this->_tpl_vars['roles']['manager']): ?>
            <b>Customer</b>
            <?php endif; ?>
          </td>
          <td align="center" valign="top" colspan="2">
            <?php if ($this->_tpl_vars['current_role'] >= $this->_tpl_vars['roles']['manager']): ?>
            <select name="customer[]" class="default">
                <option value="">All</option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['customers'],'selected' => $this->_tpl_vars['customer']), $this);?>

            </select>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td colspan="3" align="center">
            <input type="submit" name="cat" value="Generate" class="shortcut">
          </td>
        </tr>
        <tr>
          <td colspan="3" align="right" class="default">
            <a href="mailto:eventum-private@mysql.com?subject=Customer Stats Report">Feedback</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>

<?php if ($this->_tpl_vars['data'] != ''): ?>
<span class="default">
<span style="color: red">Red values indicate value is higher than the aggregate one.</span><br />
<span style="color: blue">Blue values indicate value is lower than the aggregate one.</span><br />
</span>

<?php echo $this->_tpl_vars['date_msg']; ?>

<?php if ($this->_tpl_vars['display']['customer_counts'] == 1 || $this->_tpl_vars['display']['issue_counts'] == 1 || $this->_tpl_vars['display']['email_counts'] == 1): ?>
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
        <table width="100%" bgcolor="#FFFFFF" border="0" cellspacing="1" cellpadding="2" align="center">
            <!-- header row -->
            <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                <th rowspan="2" class="default_white"><?php echo $this->_tpl_vars['row_label']; ?>
</th>
                <?php if ($this->_tpl_vars['display']['customer_counts'] == 1): ?><th colspan="3" class="default_white">Customers</th><?php endif; ?>
                <?php if ($this->_tpl_vars['display']['issue_counts'] == 1): ?><th colspan="4" class="default_white">Issues<sup>1</sup></th><?php endif; ?>
                <?php if ($this->_tpl_vars['display']['email_counts'] == 1): ?><th colspan="3" class="default_white">Emails by Customers<sup>2</sup></th>
                <th colspan="3" class="default_white">Emails by Staff<sup>3</sup></th><?php endif; ?>
            </tr>
            <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                <?php if ($this->_tpl_vars['display']['customer_counts'] == 1): ?>
                <th class="default_white">Count</th>
                <th class="default_white">Using CSC</th>
                <th class="default_white">Issues in CSC<br />(0/1-2/3-8/>8)</th>
                <?php endif; ?>
                
                <?php if ($this->_tpl_vars['display']['issue_counts'] == 1): ?>
                <th class="default_white">Tot</th>
                <th class="default_white">Avg</th>
                <th class="default_white">Med</th>
                <th class="default_white">Max</th>
                <?php endif; ?>
                
                <?php if ($this->_tpl_vars['display']['email_counts'] == 1): ?>
                <th class="default_white">Tot</th>
                <th class="default_white">Avg</th>
                <th class="default_white">Med</th>
                
                <th class="default_white">Tot</th>
                <th class="default_white">Avg.</th>
                <th class="default_white">Med</th>
                <?php endif; ?>
            </tr>
            <!-- end of header -->
            <?php if (isset($this->_sections['stats'])) unset($this->_sections['stats']);
$this->_sections['stats']['name'] = 'stats';
$this->_sections['stats']['loop'] = is_array($_loop=$this->_tpl_vars['data']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['stats']['show'] = true;
$this->_sections['stats']['max'] = $this->_sections['stats']['loop'];
$this->_sections['stats']['step'] = 1;
$this->_sections['stats']['start'] = $this->_sections['stats']['step'] > 0 ? 0 : $this->_sections['stats']['loop']-1;
if ($this->_sections['stats']['show']) {
    $this->_sections['stats']['total'] = $this->_sections['stats']['loop'];
    if ($this->_sections['stats']['total'] == 0)
        $this->_sections['stats']['show'] = false;
} else
    $this->_sections['stats']['total'] = 0;
if ($this->_sections['stats']['show']):

            for ($this->_sections['stats']['index'] = $this->_sections['stats']['start'], $this->_sections['stats']['iteration'] = 1;
                 $this->_sections['stats']['iteration'] <= $this->_sections['stats']['total'];
                 $this->_sections['stats']['index'] += $this->_sections['stats']['step'], $this->_sections['stats']['iteration']++):
$this->_sections['stats']['rownum'] = $this->_sections['stats']['iteration'];
$this->_sections['stats']['index_prev'] = $this->_sections['stats']['index'] - $this->_sections['stats']['step'];
$this->_sections['stats']['index_next'] = $this->_sections['stats']['index'] + $this->_sections['stats']['step'];
$this->_sections['stats']['first']      = ($this->_sections['stats']['iteration'] == 1);
$this->_sections['stats']['last']       = ($this->_sections['stats']['iteration'] == $this->_sections['stats']['total']);
?>
            <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

            <tr bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                <?php $this->assign('row_span', 1); ?>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo $this->_tpl_vars['data'][$this->_sections['stats']['index']]['title']; ?>
</td>
                
                <!-- Customer counts -->
                <?php if ($this->_tpl_vars['display']['customer_counts'] == 1): ?>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo $this->_tpl_vars['data'][$this->_sections['stats']['index']]['customer_counts']['customer_count']; ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo $this->_tpl_vars['data'][$this->_sections['stats']['index']]['customer_counts']['active']; ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
">
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['customer_counts']['inactive'])) ? $this->_run_mod_handler('floor', true, $_tmp) : floor($_tmp)); ?>
% /
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['customer_counts']['activity']['low'])) ? $this->_run_mod_handler('floor', true, $_tmp) : floor($_tmp)); ?>
% /
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['customer_counts']['activity']['medium'])) ? $this->_run_mod_handler('floor', true, $_tmp) : floor($_tmp)); ?>
% /
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['customer_counts']['activity']['high'])) ? $this->_run_mod_handler('floor', true, $_tmp) : floor($_tmp)); ?>
%
                </td>
                <?php endif; ?>
                
                <!-- Issue Counts -->
                <?php if ($this->_tpl_vars['display']['issue_counts'] == 1): ?>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo $this->_tpl_vars['data'][$this->_sections['stats']['index']]['issue_counts']['total']; ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['issue_counts']['avg'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['issue_counts']['avg'], 2) : formatValue($_tmp, $this->_tpl_vars['data']['0']['issue_counts']['avg'], 2)); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['issue_counts']['median'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['issue_counts']['median'], 2) : formatValue($_tmp, $this->_tpl_vars['data']['0']['issue_counts']['median'], 2)); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['issue_counts']['max'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['issue_counts']['max'], 2) : formatValue($_tmp, $this->_tpl_vars['data']['0']['issue_counts']['max'], 2)); ?>
</td>
                <?php endif; ?>
                
                <!-- Customer Action Counts -->
                <?php if ($this->_tpl_vars['display']['email_counts'] == 1): ?>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo $this->_tpl_vars['data'][$this->_sections['stats']['index']]['email_counts']['customer']['total']; ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['email_counts']['customer']['avg'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['email_counts']['customer']['avg'], 2) : formatValue($_tmp, $this->_tpl_vars['data']['0']['email_counts']['customer']['avg'], 2)); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['email_counts']['customer']['median'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['email_counts']['customer']['median'], 2) : formatValue($_tmp, $this->_tpl_vars['data']['0']['email_counts']['customer']['median'], 2)); ?>
</td>
                
                <!-- Developer Action Counts -->
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo $this->_tpl_vars['data'][$this->_sections['stats']['index']]['email_counts']['developer']['total']; ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['email_counts']['developer']['avg'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['email_counts']['developer']['avg'], 2) : formatValue($_tmp, $this->_tpl_vars['data']['0']['email_counts']['developer']['avg'], 2)); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['email_counts']['developer']['median'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['email_counts']['developer']['median'], 2) : formatValue($_tmp, $this->_tpl_vars['data']['0']['email_counts']['developer']['median'], 2)); ?>
</td>
                <?php endif; ?>
            </tr>
            <?php endfor; endif; ?>
        </table>
    </td>
  </tr>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']['time_stats'] == 1): ?>
<br />
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
        <table width="100%" bgcolor="#FFFFFF" border="0" cellspacing="1" cellpadding="2" align="center">
            <!-- header row -->
            <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                <th rowspan="2" class="default_white"><?php echo $this->_tpl_vars['row_label']; ?>
</th>
                <?php if ($this->_tpl_vars['display']['time_stats'] == 1): ?>
                <th colspan="4" class="default_white" >Time To First Response</th>
                <th colspan="4" class="default_white" >Time To Close<sup>4</sup></th>
                <?php endif; ?>
            </tr>
            <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                <?php if ($this->_tpl_vars['display']['time_stats'] == 1): ?>
                <th class="default_white">Min</th>
                <th class="default_white">Avg</th>
                <th class="default_white">Med</th>
                <th class="default_white">Max</th>
                
                <th class="default_white">Min</th>
                <th class="default_white">Avg</th>
                <th class="default_white">Med</th>
                <th class="default_white">Max</th>
                <?php endif; ?>
            </tr>
            <!-- end of header -->
            <?php if (isset($this->_sections['stats'])) unset($this->_sections['stats']);
$this->_sections['stats']['name'] = 'stats';
$this->_sections['stats']['loop'] = is_array($_loop=$this->_tpl_vars['data']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['stats']['show'] = true;
$this->_sections['stats']['max'] = $this->_sections['stats']['loop'];
$this->_sections['stats']['step'] = 1;
$this->_sections['stats']['start'] = $this->_sections['stats']['step'] > 0 ? 0 : $this->_sections['stats']['loop']-1;
if ($this->_sections['stats']['show']) {
    $this->_sections['stats']['total'] = $this->_sections['stats']['loop'];
    if ($this->_sections['stats']['total'] == 0)
        $this->_sections['stats']['show'] = false;
} else
    $this->_sections['stats']['total'] = 0;
if ($this->_sections['stats']['show']):

            for ($this->_sections['stats']['index'] = $this->_sections['stats']['start'], $this->_sections['stats']['iteration'] = 1;
                 $this->_sections['stats']['iteration'] <= $this->_sections['stats']['total'];
                 $this->_sections['stats']['index'] += $this->_sections['stats']['step'], $this->_sections['stats']['iteration']++):
$this->_sections['stats']['rownum'] = $this->_sections['stats']['iteration'];
$this->_sections['stats']['index_prev'] = $this->_sections['stats']['index'] - $this->_sections['stats']['step'];
$this->_sections['stats']['index_next'] = $this->_sections['stats']['index'] + $this->_sections['stats']['step'];
$this->_sections['stats']['first']      = ($this->_sections['stats']['iteration'] == 1);
$this->_sections['stats']['last']       = ($this->_sections['stats']['iteration'] == $this->_sections['stats']['total']);
?>
            <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

            <?php $this->assign('row_span', 1); ?>
            <tr bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo $this->_tpl_vars['data'][$this->_sections['stats']['index']]['title']; ?>
</td>
                
                <?php if ($this->_tpl_vars['display']['time_stats'] == 1): ?>
                <!-- Time to First Response -->
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['min_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_first_response']['min'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['min']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_first_response']['min'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['min'])); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['avg_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_first_response']['avg'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['avg']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_first_response']['avg'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['avg'])); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['median_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_first_response']['median'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['median']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_first_response']['median'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['median'])); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['max_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_first_response']['max'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['max']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_first_response']['max'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_first_response']['max'])); ?>
</td>
                
                <!-- Time to Close -->
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['min_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_close']['min'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['min']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_close']['min'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['min'])); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['avg_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_close']['avg'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['avg']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_close']['avg'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['avg'])); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['median_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_close']['median'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['median']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_close']['median'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['median'])); ?>
</td>
                <td class="default" align="center" rowspan="<?php echo $this->_tpl_vars['row_span']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['max_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_close']['max'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['max']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_stats']['time_to_close']['max'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_stats']['time_to_close']['max'])); ?>
</td>
                <?php endif; ?>
            </tr>
            <?php endfor; endif; ?>
        </table>
    </td>
  </tr>
</table>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['display']['time_tracking'] == 1): ?>
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
        <table width="100%" bgcolor="#FFFFFF" border="0" cellspacing="1" cellpadding="2" align="center">
            <!-- header row -->
            <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                <th class="default_white" width="150" rowspan="2">Support Level</th>
                <?php $this->assign('col_span', count($this->_tpl_vars['time_tracking_categories'])); ?>
                <?php $this->assign('col_span', ($this->_tpl_vars['col_span']+1)); ?>
                <th class="default_white" colspan="<?php echo $this->_tpl_vars['col_span']; ?>
">Time Tracking<span style="vertical-align: super; font-size: 80%">5</span></th>
            </tr>
            <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                <th class="default_white" width="50">&nbsp;</th>
                <?php if (isset($this->_foreach['time_tracking'])) unset($this->_foreach['time_tracking']);
$this->_foreach['time_tracking']['name'] = 'time_tracking';
$this->_foreach['time_tracking']['total'] = count($_from = (array)$this->_tpl_vars['time_tracking_categories']);
$this->_foreach['time_tracking']['show'] = $this->_foreach['time_tracking']['total'] > 0;
if ($this->_foreach['time_tracking']['show']):
$this->_foreach['time_tracking']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['ttc_id'] => $this->_tpl_vars['ttc_title']):
        $this->_foreach['time_tracking']['iteration']++;
        $this->_foreach['time_tracking']['first'] = ($this->_foreach['time_tracking']['iteration'] == 1);
        $this->_foreach['time_tracking']['last']  = ($this->_foreach['time_tracking']['iteration'] == $this->_foreach['time_tracking']['total']);
?>
                <th class="default_white" width="150"><?php echo $this->_tpl_vars['ttc_title']; ?>
</th>
                <?php endforeach; unset($_from); endif; ?>
            </tr>
            <?php if (isset($this->_sections['stats'])) unset($this->_sections['stats']);
$this->_sections['stats']['name'] = 'stats';
$this->_sections['stats']['loop'] = is_array($_loop=$this->_tpl_vars['data']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['stats']['show'] = true;
$this->_sections['stats']['max'] = $this->_sections['stats']['loop'];
$this->_sections['stats']['step'] = 1;
$this->_sections['stats']['start'] = $this->_sections['stats']['step'] > 0 ? 0 : $this->_sections['stats']['loop']-1;
if ($this->_sections['stats']['show']) {
    $this->_sections['stats']['total'] = $this->_sections['stats']['loop'];
    if ($this->_sections['stats']['total'] == 0)
        $this->_sections['stats']['show'] = false;
} else
    $this->_sections['stats']['total'] = 0;
if ($this->_sections['stats']['show']):

            for ($this->_sections['stats']['index'] = $this->_sections['stats']['start'], $this->_sections['stats']['iteration'] = 1;
                 $this->_sections['stats']['iteration'] <= $this->_sections['stats']['total'];
                 $this->_sections['stats']['index'] += $this->_sections['stats']['step'], $this->_sections['stats']['iteration']++):
$this->_sections['stats']['rownum'] = $this->_sections['stats']['iteration'];
$this->_sections['stats']['index_prev'] = $this->_sections['stats']['index'] - $this->_sections['stats']['step'];
$this->_sections['stats']['index_next'] = $this->_sections['stats']['index'] + $this->_sections['stats']['step'];
$this->_sections['stats']['first']      = ($this->_sections['stats']['iteration'] == 1);
$this->_sections['stats']['last']       = ($this->_sections['stats']['iteration'] == $this->_sections['stats']['total']);
?>
            <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

            <tr bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                <td class="default" align="center" rowspan="3" nowrap><?php echo $this->_tpl_vars['data'][$this->_sections['stats']['index']]['title']; ?>
</td>
                <td class="default" align="center">Total</td>
                <?php if (isset($this->_foreach['time_tracking'])) unset($this->_foreach['time_tracking']);
$this->_foreach['time_tracking']['name'] = 'time_tracking';
$this->_foreach['time_tracking']['total'] = count($_from = (array)$this->_tpl_vars['time_tracking_categories']);
$this->_foreach['time_tracking']['show'] = $this->_foreach['time_tracking']['total'] > 0;
if ($this->_foreach['time_tracking']['show']):
$this->_foreach['time_tracking']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['ttc_id'] => $this->_tpl_vars['ttc_title']):
        $this->_foreach['time_tracking']['iteration']++;
        $this->_foreach['time_tracking']['first'] = ($this->_foreach['time_tracking']['iteration'] == 1);
        $this->_foreach['time_tracking']['last']  = ($this->_foreach['time_tracking']['iteration'] == $this->_foreach['time_tracking']['total']);
?>
                <td class="default" align="center">
                <?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['total_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_tracking'][$this->_tpl_vars['ttc_id']]['total'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['total']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_tracking'][$this->_tpl_vars['ttc_id']]['total'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['total'])); ?>

                </td>
                <?php endforeach; unset($_from); endif; ?>
            </tr>
            <tr bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                <td class="default" align="center">Avg</td>
                <?php if (isset($this->_foreach['time_tracking'])) unset($this->_foreach['time_tracking']);
$this->_foreach['time_tracking']['name'] = 'time_tracking';
$this->_foreach['time_tracking']['total'] = count($_from = (array)$this->_tpl_vars['time_tracking_categories']);
$this->_foreach['time_tracking']['show'] = $this->_foreach['time_tracking']['total'] > 0;
if ($this->_foreach['time_tracking']['show']):
$this->_foreach['time_tracking']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['ttc_id'] => $this->_tpl_vars['ttc_title']):
        $this->_foreach['time_tracking']['iteration']++;
        $this->_foreach['time_tracking']['first'] = ($this->_foreach['time_tracking']['iteration'] == 1);
        $this->_foreach['time_tracking']['last']  = ($this->_foreach['time_tracking']['iteration'] == $this->_foreach['time_tracking']['total']);
?>
                <td class="default" align="center">
                <?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['avg_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_tracking'][$this->_tpl_vars['ttc_id']]['avg'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['avg']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_tracking'][$this->_tpl_vars['ttc_id']]['avg'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['avg'])); ?>

                </td>
                <?php endforeach; unset($_from); endif; ?>
            </tr>
            <tr bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                <td class="default" align="center">Med</td>
                <?php if (isset($this->_foreach['time_tracking'])) unset($this->_foreach['time_tracking']);
$this->_foreach['time_tracking']['name'] = 'time_tracking';
$this->_foreach['time_tracking']['total'] = count($_from = (array)$this->_tpl_vars['time_tracking_categories']);
$this->_foreach['time_tracking']['show'] = $this->_foreach['time_tracking']['total'] > 0;
if ($this->_foreach['time_tracking']['show']):
$this->_foreach['time_tracking']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['ttc_id'] => $this->_tpl_vars['ttc_title']):
        $this->_foreach['time_tracking']['iteration']++;
        $this->_foreach['time_tracking']['first'] = ($this->_foreach['time_tracking']['iteration'] == 1);
        $this->_foreach['time_tracking']['last']  = ($this->_foreach['time_tracking']['iteration'] == $this->_foreach['time_tracking']['total']);
?>
                <td class="default" align="center">
                <?php echo ((is_array($_tmp=$this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['median_formatted'])) ? $this->_run_mod_handler('formatValue', true, $_tmp, $this->_tpl_vars['data']['0']['time_tracking'][$this->_tpl_vars['ttc_id']]['median'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['median']) : formatValue($_tmp, $this->_tpl_vars['data']['0']['time_tracking'][$this->_tpl_vars['ttc_id']]['median'], "", $this->_tpl_vars['data'][$this->_sections['stats']['index']]['time_tracking'][$this->_tpl_vars['ttc_id']]['median'])); ?>

                </td>
                <?php endforeach; unset($_from); endif; ?>
            </tr>
            <?php endfor; endif; ?>
        </table>
    </td>
  </tr>
</table>
<?php endif; ?>

<span class="default">
1. Refers to the number of issues in eventum for the given support level or customer.
    Average and median counts do not include customers who have never opened an issue.<br />
2. Refers to the number of emails sent by customers in eventum per issue. Does <b>not</b> include emails sent to general support mailbox.<br />
3. Refers to the number of emails sent by developers in eventum per issue. Does <b>not</b> include emails sent to general support mailbox.<br />
4. Date issue was opened - Date issue was closed for all closed issues.<br />
5. All time tracking information for the given support level or customer. 
        Issues without any time tracking data do not affect the average or median.<br />
</span>
<br />
<?php if (isset($this->_foreach['graphs'])) unset($this->_foreach['graphs']);
$this->_foreach['graphs']['name'] = 'graphs';
$this->_foreach['graphs']['total'] = count($_from = (array)$this->_tpl_vars['graphs']);
$this->_foreach['graphs']['show'] = $this->_foreach['graphs']['total'] > 0;
if ($this->_foreach['graphs']['show']):
$this->_foreach['graphs']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['graph_id'] => $this->_tpl_vars['graph']):
        $this->_foreach['graphs']['iteration']++;
        $this->_foreach['graphs']['first'] = ($this->_foreach['graphs']['iteration'] == 1);
        $this->_foreach['graphs']['last']  = ($this->_foreach['graphs']['iteration'] == $this->_foreach['graphs']['total']);
 echo $this->_tpl_vars['date_msg']; ?>

<div align="center"><img src="customer_stats_graph.php?graph_id=<?php echo $this->_tpl_vars['graph_id']; ?>
"></div><br />
<?php endforeach; unset($_from); endif; ?>

<?php endif;  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>