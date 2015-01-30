<?php /* Smarty version 2.6.2, created on 2006-10-23 22:22:51
         compiled from post_note.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'post_note.tpl.html', 55, false),array('modifier', 'escape', 'post_note.tpl.html', 86, false),array('function', 'html_options', 'post_note.tpl.html', 121, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['post_result'] != ''): ?>
<br />
<center>
  <span class="default">
<?php if ($this->_tpl_vars['post_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php else: ?>
  <b>Thank you, the internal note was posted successfully.</b>
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
  <?php endif;  else:  echo '
<script language="JavaScript">
<!--
function validate(f)
{
    if (isWhitespace(f.title.value)) {
        alert(\'Please enter the title of this note.\');
        selectField(f, \'title\');
        return false;
    }
    if (isWhitespace(f.note.value)) {
        alert(\'Please enter the message body of this note.\');
        selectField(f, \'note\');
        return false;
    }
    return true;
}
function clearExtraRecipients()
{
    var f = getForm(\'post_note_form\');
    f.elements[\'note_cc[]\'].selectedIndex = -1;
    showSelections(\'post_note_form\', \'note_cc[]\');
}
var old_message = \'\';
function setSignature(f)
{
'; ?>

    var signature = "<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['current_user_prefs']['email_signature'])) ? $this->_run_mod_handler('replace', true, $_tmp, '"', '\"') : smarty_modifier_replace($_tmp, '"', '\"')))) ? $this->_run_mod_handler('replace', true, $_tmp, "\r", "") : smarty_modifier_replace($_tmp, "\r", "")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", '\n') : smarty_modifier_replace($_tmp, "\n", '\n')); ?>
";
<?php echo '
    if (f.add_email_signature.checked) {
        old_message = f.note.value;
        f.note.value += "\\n";
        f.note.value += signature;
    } else {
        f.note.value = old_message;
    }
} 
//-->
</script>
'; ?>

<form onSubmit="javascript:return validate(this);" name="post_note_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
<input type="hidden" name="cat" value="post_note">
<input type="hidden" name="parent_id" value="<?php echo $this->_tpl_vars['parent_note_id']; ?>
">
<input type="hidden" name="issue_id" value="<?php echo $this->_tpl_vars['issue_id']; ?>
">
<table align="center" width="100%" cellpadding="3">
  <tr>
    <td>
      <table width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default">
            <b>Post New Internal Note</b>
          </td>
        </tr>
        <tr>
          <td width="140" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>From:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <b><?php echo ((is_array($_tmp=$this->_tpl_vars['from'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b>
          </td>
        </tr>
        <tr>
          <td width="140" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>Recipients:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            Issue #<?php echo $this->_tpl_vars['issue_id']; ?>
 Notification List (Members: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['subscribers']['staff'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<", "&lt;") : smarty_modifier_replace($_tmp, "<", "&lt;")))) ? $this->_run_mod_handler('replace', true, $_tmp, ">", "&gt;") : smarty_modifier_replace($_tmp, ">", "&gt;")); ?>
)
          </td>
        </tr>
        <tr>
          <td width="140" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>Title: *</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <input type="text" name="title" class="default" size="50" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['reply_subject'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'title')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" colspan="2">
            <textarea name="note" rows="16" style="width: 97%"><?php echo ((is_array($_tmp=$this->_tpl_vars['note']['not_body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  if ($this->_tpl_vars['current_user_prefs']['auto_append_note_sig'] == 'yes'): ?>


<?php echo ((is_array($_tmp=$this->_tpl_vars['current_user_prefs']['email_signature'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?></textarea>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'note')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="140" class="default_white" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
">
            <b>Extra Note Recipients:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select class="default" size="4" multiple name="note_cc[]" onChange="javascript:showSelections('post_note_form', 'note_cc[]');">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users']), $this);?>

            </select><input class="button" type="button" value="Clear Selections" onClick="javascript:clearExtraRecipients();"><br />
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lookup_field.tpl.html", 'smarty_include_vars' => array('lookup_field_name' => 'search','lookup_field_target' => "note_cc[]",'callbacks' => "new Array('showSelections(\'post_note_form\', \'note_cc[]\')')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <div class="default" id="selection_note_cc[]"></div>
          </td>
        </tr>
        <tr>
          <td width="140" class="default_white" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
">
            <b>Add Extra Recipients To Notification List?</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <input type="radio" name="add_extra_recipients" value="yes"> <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('post_note_form', 'add_extra_recipients', 0);">Yes</a>&nbsp;&nbsp;
            <input type="radio" name="add_extra_recipients" value="no" checked> <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('post_note_form', 'add_extra_recipients', 1);">No</a>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
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
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>Time Spent:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <input type="text" size="5" name="time_spent" class="default">
            <select name="time_category" class="default">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['time_categories'],'selected' => $this->_tpl_vars['note_category_id']), $this);?>

            </select>
            <span class="small_default"><i>(in minutes)</i></span>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'time_spent')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td align="center">
                  <input name="main_submit_button" class="button" type="submit" value="Post Internal Note">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input class="button" type="button" value="Cancel" onClick="javascript:window.close();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <?php if ($this->_tpl_vars['current_user_prefs']['email_signature'] != "" && $this->_tpl_vars['current_user_prefs']['auto_append_note_sig'] != 'yes'): ?>
                <td align="right" class="default_white" width="10%">
                  <nobr>
                  <input type="checkbox" name="add_email_signature" value="yes" onClick="javascript:setSignature(this.form);">
                  <a id="white_link" class="white_link" href="javascript:void(null);" onClick="javascript:toggleCheckbox('post_note_form', 'add_email_signature');setSignature(getForm('post_note_form'));">Add Email Signature</a>
                  &nbsp;&nbsp;
                  </nobr>
                </td>
                <?php endif; ?>
              </tr>
            </table>
          </td>
        </tr>
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
<?php if ($this->_tpl_vars['parent_note_id'] || $_GET['cat'] == 'reply'):  echo '
<script language="JavaScript">
<!--
window.onload = focusMessageBox;
function focusMessageBox()
{
    var f = getForm(\'post_note_form\');
    f.note.focus();
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