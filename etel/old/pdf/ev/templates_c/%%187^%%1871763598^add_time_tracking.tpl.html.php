<?php /* Smarty version 2.6.2, created on 2006-10-20 06:52:12
         compiled from add_time_tracking.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'add_time_tracking.tpl.html', 80, false),array('function', 'html_select_date', 'add_time_tracking.tpl.html', 92, false),array('function', 'html_select_time', 'add_time_tracking.tpl.html', 93, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('extra_title' => "Issue #".($this->_tpl_vars['issue_id'])." - Add Time Tracking")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['time_add_result'] != ''): ?>
    <br />
    <center>
    <span class="default"><b>
    <?php if ($this->_tpl_vars['time_add_result'] == -1): ?>
        An error occurred while trying to run your query
    <?php elseif ($this->_tpl_vars['time_add_result'] == 1): ?>
        Thank you, the time tracking entry was added successfully.
    <?php endif; ?>
    </b></span>
    </center>
    <script language="JavaScript">
    <!--
    <?php if ($this->_tpl_vars['current_user_prefs']['close_popup_windows'] == '1'): ?>
    setTimeout('closeAndRefresh()', 2000);
    <?php endif; ?>
    //-->
    </script>
    <br />
    <?php if (! $this->_tpl_vars['current_user_prefs']['close_popup_windows']): ?>
    <center>
      <span class="default"><a class="link" href="javascript:void(null);" onClick="javascript:closeAndRefresh();">Continue</a></span>
    </center>
    <?php endif;  else:  echo '
<script language="JavaScript">
<!--
function validateTimeForm(f)
{
    if (isWhitespace(f.summary.value)) {
        selectField(f, \'summary\');
        alert(\'Please enter the summary for this new time tracking entry.\');
        return false;
    }
    if (f.category.options[f.category.selectedIndex].value == \'\') {
        selectField(f, \'category\');
        alert(\'Please choose the time tracking category for this new entry.\');
        return false;
    }
    if ((isWhitespace(f.time_spent.value)) || (!isNumberOnly(f.time_spent.value))) {
        selectField(f, \'time_spent\');
        alert(\'Please enter integers (or floating point numbers) on the time spent field.\');
        return false;
    }
    if (!isValidDate(f, \'date\')) {
        alert(\'Please select a valid date of work.\');
        return false;
    }
    return true;
}
//-->
</script>
'; ?>

<table align="center" width="100%" cellpadding="3">
  <tr>
    <td>
      <table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="0" align="center">
      <form name="add_time_form" onSubmit="javascript:return validateTimeForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
      <input type="hidden" name="cat" value="add_time">
      <input type="hidden" name="issue_id" value="<?php echo $this->_tpl_vars['issue_id']; ?>
">
        <tr>
          <td width="100%">
            <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2">
              <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['viewer']): ?>
              <tr>
                <td colspan="2" class="default"><b>Record Time Worked:</b></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Summary:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%"><input class="default" type="text" name="summary" size="40"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'summary')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Category:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%">
                  <select name="category" class="default">
                    <option value="">Please choose a category</option>
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['time_categories']), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'category')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Time Spent:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%"><input class="default" type="text" size="5" name="time_spent" class="default"> <span class="default">(in minutes)</span><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'time_spent')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Date of Work:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%">
                  <?php echo smarty_function_html_select_date(array('start_year' => '-1','day_value_format' => '%02d','field_array' => 'date','prefix' => '','all_extra' => ' class="default"'), $this);?>
&nbsp;
                  <?php echo smarty_function_html_select_time(array('minute_interval' => 5,'field_array' => 'date','prefix' => '','all_extra' => ' class="default"','display_seconds' => false), $this);?>

                  <a href="javascript:void(null);" onClick="javascript:updateTimeFields('add_time_form', 'date[Year]', 'date[Month]', 'date[Day]', 'date[Hour]', 'date[Minute]');"><img src="images/icons/refresh.gif" border="0"></a>
                </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" align="center" width="100%" nowrap>
                  <input type="submit" value="Add Time Entry" class="button">
                </td>
              </tr>
              <?php endif; ?>
            </table>
          </td>
        </tr>
      </form>
      </table>
    </td>
  </tr>
</table>
<script language="JavaScript">
<!--
updateTimeFields('add_time_form', 'date[Year]', 'date[Month]', 'date[Day]', 'date[Hour]', 'date[Minute]');
//-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "app_info.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>