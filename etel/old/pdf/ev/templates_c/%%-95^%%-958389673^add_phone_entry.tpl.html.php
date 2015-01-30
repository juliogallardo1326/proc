<?php /* Smarty version 2.6.2, created on 2006-10-20 20:57:41
         compiled from add_phone_entry.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_select_date', 'add_phone_entry.tpl.html', 74, false),array('function', 'html_select_time', 'add_phone_entry.tpl.html', 75, false),array('function', 'html_options', 'add_phone_entry.tpl.html', 83, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('extra_title' => "Issue #".($this->_tpl_vars['issue_id'])." - Add Phone Entry")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['add_phone_result'] != ''): ?>
    <br />
    <center>
        <span class="default"><b>
    <?php if ($this->_tpl_vars['add_phone_result'] == -1): ?>
        An error occurred while trying to run your query
    <?php elseif ($this->_tpl_vars['add_phone_result'] == 1): ?>
        Thank you, the phone entry was added successfully.
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
function validatePhoneSupportForm(f)
{
    if (!isValidDate(f, \'date\')) {
        alert(\'Please select a valid date for when the phone call took place.\');
        return false;
    }
    if ((isWhitespace(f.call_length.value)) || (!isNumberOnly(f.call_length.value))) {
        selectField(f, \'call_length\');
        alert(\'Please enter integers (or floating point numbers) on the time spent field.\');
        return false;
    }
    if (isWhitespace(f.description.value)) {
        selectField(f, \'description\');
        alert(\'Please enter the description for this new phone support entry.\');
        return false;
    }
    if (getSelectedOption(f, \'phone_category\') == -1) {
        selectField(f, \'phone_category\');
        alert(\'Please choose the category for this new phone support entry.\');
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
      <form name="add_phone_form" onSubmit="javascript:return validatePhoneSupportForm(this);" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
      <input type="hidden" name="cat" value="add_phone">
      <input type="hidden" name="issue_id" value="<?php echo $this->_tpl_vars['issue_id']; ?>
">
        <tr>
          <td>
            <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2">
              <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
              <tr>
                <td colspan="2" class="default"><b>Record Phone Call:</b></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Date of Call:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%">
                  <?php echo smarty_function_html_select_date(array('start_year' => '-1','day_value_format' => '%02d','field_array' => 'date','prefix' => '','all_extra' => ' class="default"'), $this);?>
&nbsp;
                  <?php echo smarty_function_html_select_time(array('minute_interval' => 5,'field_array' => 'date','prefix' => '','all_extra' => ' class="default"','display_seconds' => false), $this);?>

                  <a href="javascript:void(null);" onClick="javascript:updateTimeFields('add_phone_form', 'date[Year]', 'date[Month]', 'date[Day]', 'date[Hour]', 'date[Minute]');"><img src="images/icons/refresh.gif" border="0"></a>
                </td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Reason:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%">
                  <select name="phone_category" class="default">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['phone_categories'],'selected' => 5), $this);?>

                  </select>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'phone_category')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Call From:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%">
                  <input type="text" class="default" name="from_lname" value="last name" onFocus="javascript:if (this.value == 'last name') this.value='';"><span class="default">,</span>
                  <input type="text" class="default" name="from_fname" value="first name" onFocus="javascript:if (this.value == 'first name') this.value='';">
                </td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Call To:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%">
                  <input type="text" class="default" name="to_lname" value="last name" onFocus="javascript:if (this.value == 'last name') this.value='';"><span class="default">,</span>
                  <input type="text" class="default" name="to_fname" value="first name" onFocus="javascript:if (this.value == 'first name') this.value='';">
                </td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Type:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%">
                  <select class="default" name="type">
                    <option value="incoming">Incoming</option>
                    <option value="outgoing">Outgoing</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Customer Phone Number:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%">
                  <input type="text" size="20" maxlength="32" name="phone_number" class="default">
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'phone_number')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                  <select class="default" name="phone_type">
                    <option value="office">Office</option>
                    <option value="home">Home</option>
                    <option value="mobile">Mobile</option>
                    <option value="temp">Temp Number</option>
                    <option value="other">Other</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Time Spent:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" width="100%"><input type="text" size="5" name="call_length" class="default"> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'call_length')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <span class="small_default"><i>(in minutes)</i></span></td>
              </tr>
              <tr>
                <td bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" width="190" nowrap><b>Description:</b></td>
                <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
                  <textarea name="description" rows="8" style="width: 97%"></textarea>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'description')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" align="center">
                  <input type="submit" value="Save Phone Call" class="button">
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
updateTimeFields('add_phone_form', 'date[Year]', 'date[Month]', 'date[Day]', 'date[Hour]', 'date[Minute]');
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