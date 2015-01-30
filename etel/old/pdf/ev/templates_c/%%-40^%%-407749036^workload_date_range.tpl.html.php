<?php /* Smarty version 2.6.2, created on 2007-03-05 07:44:49
         compiled from reports/workload_date_range.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'reports/workload_date_range.tpl.html', 22, false),array('function', 'html_select_date', 'reports/workload_date_range.tpl.html', 42, false),array('function', 'cycle', 'reports/workload_date_range.tpl.html', 123, false),array('modifier', 'round', 'reports/workload_date_range.tpl.html', 127, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<form action="<?php echo $_SERVER['PHP_SELF']; ?>
" name="workload_report" method="post">
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center" width="400">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="2" class="default_white">
            <b>Workload by Date Range Report</b>
          </td>
        </tr>
        <tr>
        </tr>
        <tr>
          <td class="default" align="right">
            <b>Type:</b>
          </td>
          <td align="left">
            &nbsp;&nbsp;
            <select name="type" class="default" onChange="setIntervals('')">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['types'],'selected' => $this->_tpl_vars['type']), $this);?>

            </select>
          </td>
        </tr>
        <tr>
          <td class="default" align="right">
            <b>Interval:</b>
          </td>
          <td align="left">
            &nbsp;&nbsp;
            <select name="interval" class="default">
            </select>
          </td>
        </tr>
        <tr>
          <td class="default" align="right">
            <b>Start:</b>
          </td>
          <td align="left">
            &nbsp;&nbsp;
            <?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['start_date'],'prefix' => "",'field_array' => 'start','start_year' => "-2",'end_year' => "+1",'field_order' => 'YMD','month_format' => "%b",'all_extra' => "class='default'"), $this);?>

          </td>
        </tr>
        <tr>
          <td class="default" align="right">
            <b>End:</b>
          </td>
          <td align="left">
            &nbsp;&nbsp;
            <?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['end_date'],'prefix' => "",'field_array' => 'end','start_year' => "-2",'end_year' => "+1",'field_order' => 'YMD','month_format' => "%b",'all_extra' => "class='default'"), $this);?>

          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="submit" name="submit" value="Generate" class="shortcut">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<div class="default" style="color: red" align="center">
    Warning: Some type and interval options, combined with large <br />
    date ranges can produce extremely large graphs.
</div>
</form>
<script language="Javascript">
<?php echo '
var f = document.forms["workload_report"];
var options = new Array(2);
options[0] = new Array(3);// individual
options[0][0] = new Option("Day", "day");
options[0][1] = new Option("Week", "week");
options[0][2] = new Option("Month", "month")

options[1] = new Array(4);// aggregate
options[1][0] = new Option("Day of Week", "dow");
options[1][1] = new Option("Week", "week");
options[1][2] = new Option("Day of Month", "dom")
options[1][2] = new Option("Month", "month")


function setIntervals(selectedValue)
{
    f.interval.length = options[f.type.selectedIndex].length;
    for (i = 0; i < options[f.type.selectedIndex].length; i++) {
        f.interval.options[i] = options[f.type.selectedIndex][i];
        if (options[f.type.selectedIndex][i].value == selectedValue) {
            f.interval.options[i].selected = true;
        }
    }
}
'; ?>

setIntervals('<?php echo $this->_tpl_vars['interval']; ?>
');
</script>
<?php if ($this->_tpl_vars['data'] != ''): ?>
<center>
<h3>Project: <?php echo $this->_tpl_vars['current_project_name']; ?>
</h3>
</center>
<table width="400" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
          <th class="default_white">
            &nbsp;
          </th>
          <th class="default_white">
            Total
          </th>
          <th class="default_white">
            Avg
          </th>
          <th class="default_white">
            Med
          </th>
          <th class="default_white">
            Max
          </th>
        </tr>
        <?php if (isset($this->_foreach['workload'])) unset($this->_foreach['workload']);
$this->_foreach['workload']['name'] = 'workload';
$this->_foreach['workload']['total'] = count($_from = (array)$this->_tpl_vars['data']);
$this->_foreach['workload']['show'] = $this->_foreach['workload']['total'] > 0;
if ($this->_foreach['workload']['show']):
$this->_foreach['workload']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['loop_name'] => $this->_tpl_vars['row']):
        $this->_foreach['workload']['iteration']++;
        $this->_foreach['workload']['first'] = ($this->_foreach['workload']['iteration'] == 1);
        $this->_foreach['workload']['last']  = ($this->_foreach['workload']['iteration'] == $this->_foreach['workload']['total']);
?>
        <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

        <tr bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
          <td align="center" class="default"><?php echo $this->_tpl_vars['loop_name']; ?>
</td>
          <td align="center" class="default"><?php echo $this->_tpl_vars['row']['stats']['total']; ?>
</td>
          <td align="center" class="default"><?php echo ((is_array($_tmp=$this->_tpl_vars['row']['stats']['avg'])) ? $this->_run_mod_handler('round', true, $_tmp, 2) : round($_tmp, 2)); ?>
</td>
          <td align="center" class="default"><?php echo $this->_tpl_vars['row']['stats']['median']; ?>
</td>
          <td align="center" class="default"><?php echo $this->_tpl_vars['row']['stats']['max']; ?>
</td>
        </tr>
        <?php endforeach; unset($_from); endif; ?>
      </table>
    </td>
  </tr>
</table>
<div class="default" align="center">
Avg/Med/Max Issues/Emails
<?php if ($this->_tpl_vars['interval'] == 'day'): ?>
    per day
<?php elseif ($this->_tpl_vars['interval'] == 'week'): ?>
    per week
<?php elseif ($this->_tpl_vars['interval'] == 'month'): ?>
    per month
<?php elseif ($this->_tpl_vars['interval'] == 'dow'): ?>
    per day of week
<?php endif; ?>
for <?php echo $this->_tpl_vars['start_date']; ?>
 through <?php echo $this->_tpl_vars['end_date']; ?>
.
</div>
<br />
<div align="center">
    <img src="workload_date_range_graph.php?graph=issue&interval=<?php echo $this->_tpl_vars['interval']; ?>
&start_date=<?php echo $this->_tpl_vars['start_date']; ?>
&end_date=<?php echo $this->_tpl_vars['end_date']; ?>
"><br /><br />
    <img src="workload_date_range_graph.php?graph=issue&type=pie&interval=<?php echo $this->_tpl_vars['interval']; ?>
&start_date=<?php echo $this->_tpl_vars['start_date']; ?>
&end_date=<?php echo $this->_tpl_vars['end_date']; ?>
"><br /><br />
    <img src="workload_date_range_graph.php?graph=email&interval=<?php echo $this->_tpl_vars['interval']; ?>
&start_date=<?php echo $this->_tpl_vars['start_date']; ?>
&end_date=<?php echo $this->_tpl_vars['end_date']; ?>
"><br /><br />
    <img src="workload_date_range_graph.php?graph=email&type=pie&interval=<?php echo $this->_tpl_vars['interval']; ?>
&start_date=<?php echo $this->_tpl_vars['start_date']; ?>
&end_date=<?php echo $this->_tpl_vars['end_date']; ?>
">
</div>
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>