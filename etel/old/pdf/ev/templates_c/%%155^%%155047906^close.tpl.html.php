<?php /* Smarty version 2.6.2, created on 2006-10-25 00:11:40
         compiled from close.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'close.tpl.html', 115, false),array('function', 'cycle', 'close.tpl.html', 176, false),array('modifier', 'count', 'close.tpl.html', 121, false),array('modifier', 'default', 'close.tpl.html', 152, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('extra_title' => $this->_tpl_vars['extra_title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "navigation.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />
<?php if ($this->_tpl_vars['close_result'] != ""): ?>
<table width="500" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td class="default">
            <?php if ($this->_tpl_vars['close_result'] == -1): ?>
            <b>Sorry, an error happened while trying to run your query.</b>
            <?php elseif ($this->_tpl_vars['close_result'] == 1): ?>
            <b>Thank you, the issue was closed successfully. Please choose
            from one of the options below:</b>
            <ul>
              <li><a href="view.php?id=<?php echo $_POST['issue_id']; ?>
" class="link">Open the Issue Details Page</a></li>
              <li><a href="list.php" class="link">Open the Issue Listing Page</a></li>
              <?php if ($this->_tpl_vars['app_setup']['support_email'] == 'enabled' && $this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['viewer']): ?>
              <li><a href="emails.php" class="link">Open the Emails Listing Page</a></li>
              <?php endif; ?>
            </ul>
            <?php endif; ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php else:  echo '
<script language="JavaScript">
<!--
var has_per_incident_contract = false;

function validateClose(f)
{
    if (getSelectedOption(f, \'status\') == -1) {
        selectField(f, \'status\');
        alert(\'Please choose the new status for this issue.\');
        return false;
    }
    if (isWhitespace(f.reason.value)) {
        selectField(f, \'reason\');
        alert(\'Please enter the reason for closing this issue.\');
        return false;
    }

    if (!isWhitespace(f.time_spent.value)) {
        if (!isNumberOnly(f.time_spent.value)) {
            selectField(f, \'time_spent\');
            alert(\'Please enter integers (or floating point numbers) on the time spent field.\');
            return false;
        }
        if (f.category.options[f.category.selectedIndex].value == \'\') {
            selectField(f, \'category\');
            alert(\'Please choose the time tracking category for this new entry.\');
            return false;
        }
    }

    if (has_per_incident_contract) {
        elements = getForm(\'close_form\');
        has_checked_incident = false;
        for (i = 0; i < elements.length; i++) {
            if (elements[i].name.substr(0, 6) == \'redeem\') {
                if (elements[i].checked == true) {
                    has_checked_incident = true;
                }
            }
        }
        if (has_checked_incident == false) {
            return confirm(\'This customer has a per incident contract. You have chosen not to redeem any incidents. Press \\\'OK\\\' \' +
             \'to confirm or \\\'Cancel\\\' to revise.\');
        }
    }
    return true;
}

function toggleNotificationList()
{
    var f = getForm(\'close_form\');

    var cell = getPageElement(\'reason_cell\');

    if (f.notification_list[1].checked) {
        cell.style.background = "';  echo $this->_tpl_vars['cell_color'];  echo '";
    } else {
        cell.style.background = "';  echo $this->_tpl_vars['internal_color'];  echo '";
    }
}
//-->
</script>
'; ?>

<table width="80%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
<form name="close_form" onSubmit="javascript:return validateClose(this);" method="post" action="close.php">
<input type="hidden" name="cat" value="close">
<input type="hidden" name="issue_id" value="<?php echo $_GET['id']; ?>
">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default" nowrap>
            <b>Close Issue</b> (Issue ID: <?php echo $_GET['id']; ?>
)
          </td>
        </tr>
        <tr>
          <td width="160" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Status: *</b><br />
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select class="default" name="status">
              <!--option value="-1">Please choose a status</optio-->
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['statuses']), $this);?>

            </select>
            <script language="Javascript">selectOnlyValidOption(document.forms['close_form'].elements['status']);</script>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'status')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <?php if (count($this->_tpl_vars['resolutions']) > 0): ?>
        <tr>
          <td width="160" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Resolution:</b><br />
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select class="default" name="resolution">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['resolutions']), $this);?>

            </select>
          </td>
        </tr>
        <?php else: ?>
            <input type="hidden" name="resolution" value="">
        <?php endif; ?>
        <tr>
          <td width="160" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Send Notification About Issue Being Closed?</b><br />
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <input type="radio" name="send_notification" checked value="1">
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('close_form', 'send_notification', 0);">Yes</a>&nbsp;&nbsp;
            <input type="radio" name="send_notification" value="0">
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('close_form', 'send_notification', 1);">No</a>
          </td>
        </tr>
        <tr>
          <td width="160" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Send Notification To:</b><br />
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <input id="notification_internal" type="radio" name="notification_list" checked value="internal" onchange="toggleNotificationList()">
            <label for="notification_internal">Internal Users (<?php echo ((is_array($_tmp=@$this->_tpl_vars['notification_list_internal'])) ? $this->_run_mod_handler('default', true, $_tmp, "<i>None</i>") : smarty_modifier_default($_tmp, "<i>None</i>")); ?>
)</label><br />
            <input id="notification_all" type="radio" name="notification_list" value="all" onchange="toggleNotificationList()">
            <label for="notification_all">All (<?php echo ((is_array($_tmp=@$this->_tpl_vars['notification_list_all'])) ? $this->_run_mod_handler('default', true, $_tmp, "<i>None</i>") : smarty_modifier_default($_tmp, "<i>None</i>")); ?>
)</label>
          </td>
        </tr>
        <tr>
          <td width="160" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white" id="reason_cell">
            <b>Reason for closing issue: *</b><br />
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <textarea name="reason" rows="8" style="width: 97%"></textarea>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'reason')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <?php if (count($this->_tpl_vars['incident_details']) > 0): ?>
        <script>
        has_per_incident_contract = true;
        </script>
        <tr>
          <td width="160" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>Incident Types to Redeem: </b><br />
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php if (count($_from = (array)$this->_tpl_vars['incident_details'])):
    foreach ($_from as $this->_tpl_vars['type_id'] => $this->_tpl_vars['type_details']):
?>
              <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

              <?php if ($this->_tpl_vars['res'] == ''): ?><input type="checkbox" name="redeem[<?php echo $this->_tpl_vars['type_id']; ?>
]" value="1" <?php if ($this->_tpl_vars['redeemed'][$this->_tpl_vars['type_id']]['is_redeemed'] == 1): ?>checked<?php endif; ?>><?php endif; ?>
              <a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('close_form', 'redeem[<?php echo $this->_tpl_vars['type_id']; ?>
]', 0);"><?php echo $this->_tpl_vars['type_details']['title']; ?>
 (Total: <?php echo $this->_tpl_vars['type_details']['total']; ?>
; Left: <?php echo $this->_tpl_vars['type_details']['total']-$this->_tpl_vars['type_details']['redeemed']; ?>
)</a><br />
            <?php endforeach; unset($_from); endif; ?>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td width="160" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>Time Spent: </b><br />
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <input class="default" type="text" size="5" name="time_spent" class="default"> <span class="default">(in minutes)</span><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'time_spent')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="160" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>Time Category: </b><br />
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
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
        <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
          <td colspan="2">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td><input class="button" type="button" value="&lt;&lt; Back" onClick="javascript:history.go(-1);"></td>
                <td width="100%" align="center"><input class="button" type="submit" value="Close Issue"></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</form>
</table>
<?php endif; ?>
<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "app_info.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>