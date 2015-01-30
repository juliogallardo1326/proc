<?php /* Smarty version 2.6.2, created on 2006-10-19 23:56:20
         compiled from popup.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />
<center>
  <span class="default">
<?php if ($this->_tpl_vars['note_add_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['note_add_result'] == -2): ?>
  <b>Please enter the note text on the input box below.</b>
<?php elseif ($this->_tpl_vars['note_add_result'] == 1): ?>
  <b>Thank you, the new note was created and associated with the issue below.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['note_delete_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['note_delete_result'] == -2): ?>
  <b>You do not have permission to delete this note.</b>
<?php elseif ($this->_tpl_vars['note_delete_result'] == 1): ?>
  <b>Thank you, the note was removed successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['time_delete_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['time_delete_result'] == 1): ?>
  <b>Thank you, the time tracking entry was removed successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['bulk_update_result'] == 1): ?>
  <b>Thank you, the selected issues were updated successfully.</b>
<?php elseif ($this->_tpl_vars['bulk_update_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['set_initial_impact_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['set_initial_impact_result'] == 1): ?>
  <b>Thank you, the inital impact analysis was set successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['add_requirement_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['add_requirement_result'] == 1): ?>
  <b>Thank you, the new requirement was added successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['set_impact_requirement_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['set_impact_requirement_result'] == 1): ?>
  <b>Thank you, the impact analysis was set successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['requirement_delete_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['requirement_delete_result'] == 1): ?>
  <b>Thank you, the selected requirements were removed successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['save_filter_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['save_filter_result'] == 1): ?>
  <b>Thank you, the custom filter was saved successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['delete_filter_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['delete_filter_result'] == 1): ?>
  <b>Thank you, the selected custom filters were removed successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['remove_association_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['remove_association_result'] == 1): ?>
  <b>Thank you, the association to the selected emails were removed successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['remove_attachment_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['remove_attachment_result'] == -2): ?>
  <b>You do not have the permission to remove this attachment.</b>
<?php elseif ($this->_tpl_vars['remove_attachment_result'] == 1): ?>
  <b>Thank you, the attachment was removed successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['remove_file_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['remove_file_result'] == -2): ?>
  <b>You do not have the permission to remove this file.</b>
<?php elseif ($this->_tpl_vars['remove_file_result'] == 1): ?>
  <b>Thank you, the file was removed successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['remove_checkin_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['remove_checkin_result'] == 1): ?>
  <b>Thank you, the selected checkin information entries were removed successfully.</b>
<?php endif; ?>


<?php if ($this->_tpl_vars['remove_email_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['remove_email_result'] == 1): ?>
  <b>Thank you, the emails were marked as removed successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['clear_duplicate_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['clear_duplicate_result'] == 1): ?>
  <b>Thank you, the current issue is no longer marked as a duplicate.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['delete_phone_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['delete_phone_result'] == -2): ?>
  <b>You do not have permission to remove this phone support entry.</b>
<?php elseif ($this->_tpl_vars['delete_phone_result'] == 1): ?>
  <b>Thank you, the phone support entry was removed successfully.</b>
<?php elseif ($this->_tpl_vars['delete_phone_result'] == 2): ?>
  <b>Thank you, the phone support entry was removed successfully.<br />
  The associated time tracking entry was also deleted.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['new_status_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['new_status_result'] == 1): ?>
  <b>Thank you, the issue was updated successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['unassign_result'] == -1): ?>
  <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['unassign_result'] == -2): ?>
  <b>Error: the issue is already unassigned.</b>
<?php elseif ($this->_tpl_vars['unassign_result'] == 1): ?>
  <b>Thank you, the issue was unassigned successfully.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['authorize_reply_result'] == -1): ?>
  <b>Error: you are already authorized to send emails in this issue.</b>
<?php elseif ($this->_tpl_vars['authorize_reply_result'] == 1): ?>
  <b>Thank you, you are now authorized to send emails in this issue.</b>
<?php endif; ?>

<?php if ($this->_tpl_vars['remove_quarantine_result'] == -1): ?>
    <b>An error occurred while trying to run your query</b>
<?php elseif ($this->_tpl_vars['remove_quarantine_result'] == 1): ?>
  <b>Thank you, this issue was removed from quarantine.</b>
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
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>