<?php /* Smarty version 2.6.2, created on 2006-12-10 22:59:02
         compiled from send.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'send.tpl.html', 61, false),array('modifier', 'escape', 'send.tpl.html', 200, false),array('function', 'html_options', 'send.tpl.html', 254, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['send_result'] != '' && $_POST['form_stays'] != 1): ?>
<br />
<center>
  <span class="default">
<?php if ($this->_tpl_vars['send_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['send_result'] == -2): ?>
  <b>Sorry, but the email could not be queued. This might be related to problems with your SMTP account settings. 
  Please contact the administrator of this application for further assistance.</b>
<?php elseif ($this->_tpl_vars['send_result'] == 1): ?>
  <b>Thank you, the email was queued to be sent successfully.</b>
<?php endif; ?>
  </span>
</center>
<script language="JavaScript">
<!--
<?php if ($this->_tpl_vars['current_user_prefs']['close_popup_windows']): ?>
setTimeout('closeAndRefresh()', 2000);
<?php endif; ?>
//-->
</script>
<br />
<?php if (! $this->_tpl_vars['current_user_prefs']['close_popup_windows']): ?>
  <center>
    <span class="default"><a class="link" href="javascript:void(null);" onClick="javascript:closeAndRefresh();">Continue</a></span>
  </center>
<?php endif;  elseif ($this->_tpl_vars['draft_result'] != ''): ?>
<br />
<center>
  <span class="default">
<?php if ($this->_tpl_vars['draft_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['draft_result'] == 1): ?>
  <b>Thank you, the email message was saved as a draft successfully.</b>
<?php endif; ?>
  </span>
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
<?php endif;  else: ?>
<script language="JavaScript">
<!--
checkWindowClose('If you close this window, you will lose your message');

var contact_list = new Array();
<?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['assoc_emails']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
contact_list[contact_list.length] = '<?php echo ((is_array($_tmp=$this->_tpl_vars['assoc_emails'][$this->_sections['i']['index']])) ? $this->_run_mod_handler('replace', true, $_tmp, "'", "\\'") : smarty_modifier_replace($_tmp, "'", "\\'")); ?>
';
<?php endfor; endif; ?>

var email_responses = new Array();
<?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['js_canned_responses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
email_responses[<?php echo $this->_tpl_vars['js_canned_responses'][$this->_sections['i']['index']]['ere_id']; ?>
] = "<?php echo $this->_tpl_vars['js_canned_responses'][$this->_sections['i']['index']]['ere_response_body']; ?>
";
<?php endfor; endif;  echo '
function validate(f)
{
    if (f.to != null) {
        if (isWhitespace(f.to.value)) {
            alert(\'Please enter the recipient of this email.\');
            selectField(f, \'to\');
            return false;
        }
    }
    if (isWhitespace(f.subject.value)) {
        alert(\'Please enter the subject of this email.\');
        selectField(f, \'subject\');
        return false;
    }
    if (isWhitespace(f.message.value)) {
        alert(\'Please enter the message body of this email.\');
        selectField(f, \'message\');
        return false;
    }
'; ?>

<?php if ($this->_tpl_vars['current_role'] < $this->_tpl_vars['roles']['customer'] && $this->_tpl_vars['issue_id'] != 0): ?>
    <?php if (! $this->_tpl_vars['can_send_email']): ?>
    var warning_msg = "WARNING: You are not assigned to this issue so your email will be blocked. Your blocked email will be converted ";
    warning_msg += "to a note that can be recovered later.\n\nFor more information, please see the topic \"email blocking\" in help.";
    <?php elseif ($this->_tpl_vars['has_customer_integration']): ?>
    var warning_msg = "WARNING: This email will be sent to all names on this issue's Notification List, including CUSTOMERS.\n";
    warning_msg += "If you want the CUSTOMER to receive your message now, press OK.\n";
    warning_msg += "Otherwise, to return to your editing window, press CANCEL.";
    <?php else: ?>
    var warning_msg = "WARNING: This email will be sent to all names on this issue's Notification List.\n";
    warning_msg += "If you want all users to receive your message now, press OK.\n";
    warning_msg += "Otherwise, to return to your editing window, press CANCEL.";
    <?php endif;  echo '
    if (!confirm(warning_msg)) {
        return false;
    } else {
        checkWindowClose(false);
        return true;
    }
'; ?>

<?php else: ?>
    checkWindowClose(false);
    return true;
<?php endif;  echo '
}
function setResponseBody(f)
{
    var response_id = getSelectedOption(f, \'email_response\');
    if (email_responses[response_id]) {
        f.message.value = email_responses[response_id]+"\\n"+f.message.value;
    }
}
function saveDraft(f)
{
    checkWindowClose(false);
    f.cat.value = \'save_draft\';
    f.submit();
}
function updateDraft(f)
{
    checkWindowClose(false);
    f.cat.value = \'update_draft\';
    f.submit();
}
var old_message = \'\';
function setSignature(f)
{
'; ?>

    var signature = "<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['current_user_prefs']['email_signature'])) ? $this->_run_mod_handler('replace', true, $_tmp, '"', '\"') : smarty_modifier_replace($_tmp, '"', '\"')))) ? $this->_run_mod_handler('replace', true, $_tmp, "\r", "") : smarty_modifier_replace($_tmp, "\r", "")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", '\n') : smarty_modifier_replace($_tmp, "\n", '\n')); ?>
";
<?php echo '
    if (f.add_email_signature.checked) {
        old_message = f.message.value;
        f.message.value += "\\n";
        f.message.value += signature;
    } else {
        f.message.value = old_message;
    }
} 
//-->
</script>
'; ?>

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript" src="js/autocomplete.js"></script>
<form onSubmit="javascript:return validate(this);" name="send_email_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
<input type="hidden" name="cat" value="send_email">
<input type="hidden" name="parent_id" value="<?php echo $this->_tpl_vars['parent_email_id']; ?>
">
<input type="hidden" name="ema_id" value="<?php echo $this->_tpl_vars['ema_id']; ?>
">
<input type="hidden" name="issue_id" value="<?php echo $this->_tpl_vars['issue_id']; ?>
">
<?php if ($_GET['cat'] == 'view_draft'): ?>
<input type="hidden" name="draft_id" value="<?php echo $this->_tpl_vars['draft_id']; ?>
">
<?php endif;  if ($this->_tpl_vars['draft_status'] == 'sent'): ?>
    <br /><center class="banner_red"><img src="images/icons/error.gif"> Warning: This draft has already been sent. You cannot resend it.</center>
<?php elseif ($this->_tpl_vars['draft_status'] == 'edited'): ?>
    <br /><center class="banner_red"><img src="images/icons/error.gif"> Warning: This draft has already been edited. You cannot send or edit it.</center>
<?php endif; ?>
<table align="center" width="100%" cellpadding="3">
  <tr>
    <td>
      <table width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default">
            <?php if ($_GET['cat'] == 'create_draft'): ?>
                <b>Create Draft</b>
            <?php else: ?>
                <b>Send Email</b>
            <?php endif; ?>
          </td>
        </tr>
        <?php if ($this->_tpl_vars['send_result'] != ""): ?>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" colspan="2" class="error" align="center">
            <?php if ($this->_tpl_vars['send_result'] == -1): ?>
              <b>An error occurred while trying to run your query</b>
            <?php elseif ($this->_tpl_vars['send_result'] == -2): ?>
              <b>Sorry, but the email could not be sent. This might be related to problems with your SMTP account settings. 
              Please contact the administrator of this application for assistance.</b>
            <?php elseif ($this->_tpl_vars['send_result'] == 1): ?>
              <b>Thank you, the email was sent successfully.</b>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>From:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <input type="hidden" name="from" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['from'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
            <b><?php echo ((is_array($_tmp=$this->_tpl_vars['from'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>To: *</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <?php if ($this->_tpl_vars['issue_id'] == ''): ?>
                <input type="text" name="to" class="default" size="50" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email']['sup_from'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" onKeyUp="javascript:autoComplete(this, contact_list);">
                <?php if (! ( $this->_tpl_vars['os']['mac'] && $this->_tpl_vars['browser']['ie'] )): ?><a href="javascript:void(null);" onClick="return overlib(getFillInput('<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lookup_layer.tpl.html", 'smarty_include_vars' => array('list' => $this->_tpl_vars['assoc_users'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>', 'send_email_form', 'to'), STICKY, HEIGHT, 50, WIDTH, 160, BELOW, LEFT, CLOSECOLOR, '#FFFFFF', FGCOLOR, '#FFFFFF', BGCOLOR, '#333333', CAPTION, 'Lookup Details', CLOSECLICK);" onMouseOut="javascript:nd();"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/lookup.gif" border="0"></a><?php endif; ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'to')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php else: ?>
                <span class="default">Issue #<?php echo $this->_tpl_vars['issue_id']; ?>
 Notification List (Members: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['subscribers']['staff'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<", "&lt;") : smarty_modifier_replace($_tmp, "<", "&lt;")))) ? $this->_run_mod_handler('replace', true, $_tmp, ">", "&gt;") : smarty_modifier_replace($_tmp, ">", "&gt;"));  if ($this->_tpl_vars['subscribers']['customers'] != ''): ?>, <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['subscribers']['customers'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<", "&lt;") : smarty_modifier_replace($_tmp, "<", "&lt;")))) ? $this->_run_mod_handler('replace', true, $_tmp, ">", "&gt;") : smarty_modifier_replace($_tmp, ">", "&gt;"));  endif; ?>)</span>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Cc:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <input type="text" name="cc" class="default" size="50" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email']['cc'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['read_only'] == 1): ?>readonly<?php endif; ?>>
            <?php if (! ( $this->_tpl_vars['os']['mac'] && $this->_tpl_vars['browser']['ie'] )): ?><a href="javascript:void(null);" onClick="return overlib(getFillInput('<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lookup_layer.tpl.html", 'smarty_include_vars' => array('list' => $this->_tpl_vars['assoc_users'],'multiple' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>', 'send_email_form', 'cc'), STICKY, HEIGHT, 50, WIDTH, 160, BELOW, LEFT, CLOSECOLOR, '#FFFFFF', FGCOLOR, '#FFFFFF', BGCOLOR, '#333333', CAPTION, 'Lookup Details', CLOSECLICK);" onMouseOut="javascript:nd();"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/lookup.gif" border="0"></a><?php endif; ?>
          </td>
        </tr>
        <?php if ($this->_tpl_vars['issue_id'] && $this->_tpl_vars['current_role'] != $this->_tpl_vars['roles']['customer'] && $this->_tpl_vars['read_only'] != 1): ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">&nbsp;
            
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <input type="checkbox" name="add_unknown" value="yes">
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('send_email_form', 'add_unknown');">Add Unknown Recipients to Issue Notification List</a>
          </td>
        </tr>
        <?php else: ?>
        <input type="hidden" name="add_unknown" value="no">
        <?php endif; ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Subject: *</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <input type="text" name="subject" class="default" size="50" value="<?php if ($_GET['cat'] == 'view_draft'):  echo ((is_array($_tmp=$this->_tpl_vars['email']['sup_subject'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  else:  echo ((is_array($_tmp=$this->_tpl_vars['email']['reply_subject'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?>" <?php if ($this->_tpl_vars['read_only'] == 1): ?>readonly<?php endif; ?>>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'subject')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" colspan="2">
            <?php if ($this->_tpl_vars['current_role'] != $this->_tpl_vars['roles']['customer'] && $this->_tpl_vars['canned_responses'] != '' && $this->_tpl_vars['read_only'] != 1): ?>
            <span class="default"><b>Canned Responses:</b></span>
            <select name="email_response" class="default">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['canned_responses']), $this);?>

            </select>&nbsp;<input type="button" class="shortcut" value="Use Canned Response" onClick="javascript:setResponseBody(this.form);"><br />
            <?php endif; ?>
            <textarea name="message" rows="15" style="width: 97%" <?php if ($this->_tpl_vars['read_only'] == 1): ?>readonly<?php endif; ?>><?php if ($this->_tpl_vars['current_user_prefs']['auto_append_sig'] == 'yes' && $this->_tpl_vars['body_has_sig_already'] != 1): ?>


<?php echo ((is_array($_tmp=$this->_tpl_vars['current_user_prefs']['email_signature'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif;  echo ((is_array($_tmp=$this->_tpl_vars['email']['seb_body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'message')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <?php if ($this->_tpl_vars['issue_id'] && $this->_tpl_vars['current_role'] != $this->_tpl_vars['roles']['customer'] && $this->_tpl_vars['read_only'] != 1): ?>
            <?php if ($this->_tpl_vars['hide_email_buttons'] != 'yes'): ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>New Status for Issue #<?php echo $this->_tpl_vars['issue_id']; ?>
:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select name="new_status" class="default">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['statuses'],'selected' => $this->_tpl_vars['current_issue_status']), $this);?>

            </select>
          </td>
        </tr>
            <?php endif; ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Time Spent:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <input type="text" size="5" name="time_spent" class="default"> <span class="small_default"><i>(in minutes)</i></span>
          </td>
        </tr>
        <?php elseif ($this->_tpl_vars['issue_id'] && $this->_tpl_vars['current_role'] == $this->_tpl_vars['roles']['customer']): ?>
        <input type="hidden" name="new_status" value="<?php echo $this->_tpl_vars['new_status']; ?>
">
        <?php endif; ?>
        <?php if ($this->_tpl_vars['hide_email_buttons'] != 'yes'): ?>
        <tr>
          <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td align="center">
                  <?php if ($this->_tpl_vars['read_only'] != 1): ?>
                  <input class="button" type="submit" value="Send Email">&nbsp;&nbsp;
                  <input class="button" type="button" value="Reset" onClick="javascript:resetForm(this.form);">&nbsp;&nbsp;
                  <?php endif; ?>
                  <input class="button" type="button" value="Cancel" onClick="javascript:confirmCloseWindow();">
                </td>
                <?php if ($this->_tpl_vars['app_setup']['spell_checker'] == 'enabled'): ?>
                <td align="center" width="150">
                  <input class="button" type="button" value="Check Spelling" onClick="javascript:checkSpelling('send_email_form', 'message');">
                </td>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['current_user_prefs']['email_signature'] != "" && $this->_tpl_vars['current_user_prefs']['auto_append_sig'] != 'yes'): ?>
                <td class="default_white" align="right" width="150">
                  <nobr>
                  <input type="checkbox" name="add_email_signature" value="yes" onClick="javascript:setSignature(this.form);">
                  <a id="white_link" class="white_link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('send_email_form', 'add_email_signature');setSignature(getForm('send_email_form'));">Add Email Signature</a>
                  &nbsp;&nbsp;
                  </nobr>
                </td>
                <?php endif; ?>
              </tr>
            </table>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['issue_id'] && $this->_tpl_vars['current_role'] != $this->_tpl_vars['roles']['customer']): ?>
        <tr>
          <td bgcolor="<?php if ($this->_tpl_vars['hide_email_buttons'] == 'yes'):  echo $this->_tpl_vars['cell_color'];  else:  echo $this->_tpl_vars['dark_color'];  endif; ?>" colspan="2">
            <?php if ($this->_tpl_vars['read_only'] != 1): ?>
            <?php if ($_GET['cat'] == 'view_draft'): ?>
            <input type="button" class="button" value="Save Draft Changes" onClick="javascript:updateDraft(this.form);">
            <?php else: ?>
            <input type="button" class="button" value="Save as Draft" onClick="javascript:saveDraft(this.form);">
            <?php endif; ?>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td colspan="2" class="default">
            <b>* Required fields</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>

<?php if ($this->_tpl_vars['parent_email_id'] || $_GET['cat'] == 'reply'):  echo '
<script language="JavaScript">
<!--
window.onload = focusMessageBox;
function focusMessageBox()
{
    var f = getForm(\'send_email_form\');
    f.message.focus();
}
//-->
</script>
'; ?>

<?php endif; ?>

<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>