<?php /* Smarty version 2.6.2, created on 2006-12-10 21:57:00
         compiled from view_form.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'view_form.tpl.html', 253, false),array('modifier', 'replace', 'view_form.tpl.html', 267, false),array('modifier', 'default', 'view_form.tpl.html', 348, false),array('modifier', 'count', 'view_form.tpl.html', 373, false),array('modifier', 'activateLinks', 'view_form.tpl.html', 445, false),array('function', 'get_display_style', 'view_form.tpl.html', 445, false),array('function', 'html_options', 'view_form.tpl.html', 531, false),)), $this); ?>

<script language="JavaScript">
<!--
var ema_id = '<?php echo $this->_tpl_vars['ema_id']; ?>
';
<?php echo '
function openHistory(issue_id)
{
    var features = \'width=520,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'history.php?iss_id=\' + issue_id, \'_impact\', features);
    popupWin.focus();
}
function openNotification(issue_id)
{
    var features = \'width=440,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'notification.php?iss_id=\' + issue_id, \'_notification\', features);
    popupWin.focus();
}
function openAuthorizedReplier(issue_id)
{
    var features = \'width=440,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'authorized_replier.php?iss_id=\' + issue_id, \'_replier\', features);
    popupWin.focus();
}
function signupAsAuthorizedReplier(issue_id)
{
    var features = \'width=420,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'popup.php?cat=authorize_reply&iss_id=\' + issue_id, \'_authorizeReply\', features);
    popupWin.focus();
}
function selfAssign(issue_id)
{
    var features = \'width=420,height=150,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'self_assign.php?iss_id=\' + issue_id, \'_selfAssign\', features);
    popupWin.focus();
}
function mercAssign(issue_id)
{
    var features = \'width=420,height=150,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'self_assign.php?iss_id=\' + issue_id + \'&assigntomerc=1\', \'_mercAssign\', features);
    popupWin.focus();
}
function unassign(issue_id)
{
    var features = \'width=420,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'popup.php?cat=unassign&iss_id=\' + issue_id, \'_unassign\', features);
    popupWin.focus();
}
function replyIssue(issue_id,selfassign)
{
	if(selfassign)
		selfAssign(issue_id);
    var features = \'width=740,height=580,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'send.php?cat=reply&ema_id=\' + ema_id + \'&issue_id=\' + issue_id, \'_replyIssue\' + issue_id, features);
    popupWin.focus();
}
function clearDuplicateStatus(issue_id)
{
    var features = \'width=420,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'popup.php?cat=clear_duplicate&iss_id=\' + issue_id, \'_clearDuplicate\', features);
    popupWin.focus();
}
function changeIssueStatus(f, issue_id, current_status_id)
{
    var new_status = getSelectedOption(f, \'new_status\');
    if (new_status == current_status_id) {
        selectField(f, \'new_status\');
        alert(\'Please select the new status for this issue.\');
    } else {
        var features = \'width=420,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
        var popupWin = window.open(\'popup.php?cat=new_status&iss_id=\' + issue_id + \'&new_sta_id=\' + new_status, \'_newStatus\', features);
        popupWin.focus();
    }
}
function changeIssueProject(f, issue_id )
{
    var new_project = getSelectedOption(f, \'new_project\');
	var features = \'width=420,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
	var popupWin = window.open(\'popup.php?cat=new_project&iss_id=\' + issue_id + \'&iss_prj_id=\' + new_project, \'_newProject\', features);
	popupWin.focus();
}
function changeIssueCategory(f, issue_id)
{
    var new_category = getSelectedOption(f, \'new_category\');
	var features = \'width=420,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
	var popupWin = window.open(\'popup.php?cat=new_category&iss_id=\' + issue_id + \'&iss_prc_id=\' + new_category, \'_newCategory\', features);
	popupWin.focus();
}
function editIncidentRedemption(issue_id)
{
    var features = \'width=300,height=300,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'redeem_incident.php?iss_id=\' + issue_id, \'_flagIncident\', features);
    popupWin.focus();
}
function removeQuarantine(issue_id)
{
    var features = \'width=420,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'popup.php?cat=remove_quarantine&iss_id=\' + issue_id, \'_removeQuarantine\', features);
    popupWin.focus();
}
function validateForm(f)
{
    // if no emails accounts are setup, don\'t display confirmation message.
    if ((';  echo $this->_tpl_vars['current_role']; ?>
 < <?php echo $this->_tpl_vars['roles']['developer'];  echo ') && (ema_id != \'\') && !confirm(\'NOTE: If you need to send new information regarding this issue, please use \\nthe EMAIL related buttons available at the bottom of the screen.\')) {
        return false;
    }
    return true;
}
function collapseDescription()
{
    if (isElementVisible(getPageElement(\'description1\'))) {
        changeVisibility(\'description_hidden\', false);
    } else {
        changeVisibility(\'description_hidden\', true);
    }
}
//-->
</script>
'; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="default">
      <?php if ($this->_tpl_vars['previous_issue']): ?>
      &nbsp;<a class="link" title="previous issue on your current active filter" href="view.php?id=<?php echo $this->_tpl_vars['previous_issue']; ?>
">&lt;&lt; Previous Issue</a>
      <?php endif; ?>
    </td>
    <td class="default" align="right">
      <?php if ($this->_tpl_vars['next_issue']): ?>
      <a class="link" title="next issue on your current active filter" href="view.php?id=<?php echo $this->_tpl_vars['next_issue']; ?>
">Next Issue &gt;&gt;</a>&nbsp;
      <?php endif; ?>
    </td>
  </tr>
  <tr>
    <td colspan="2"><img height="10" src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/blank.gif"></td>
  </tr>
</table>

<?php if ($this->_tpl_vars['quarantine']['iqu_status'] > 0): ?>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/icons/error.gif" hspace="2" vspace="2" border="0" align="left"></td>
          <td width="100%" align="center">
            <span class="default">
            <span style="font-weight: bold; font-size: 160%; color: red;">
                This Issue is Currently Quarantined
            </span>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/".($this->_tpl_vars['customer_backend_name'])."/quarantine.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer'] && $this->_tpl_vars['quarantine']['iqu_expiration'] != ''): ?>
                Quarantine expires in <?php echo $this->_tpl_vars['quarantine']['time_till_expiration']; ?>
<br />
            <?php endif; ?>
            Please see the <a class="link" href="faq.php">FAQ</a> for information regarding quarantined issues.
            </span>
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['developer']): ?>
            <br /><br />
            <input class="button" type="button" name="remove_quarantine" value="Remove Quarantine" onClick="removeQuarantine(<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
)">
            <?php endif; ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['project_auto_switched'] == 1): ?>
<center>
  <span class="banner_red">
    Note: Project automatically switched to '<?php echo $this->_tpl_vars['current_project_name']; ?>
' from '<?php echo $this->_tpl_vars['old_project']; ?>
'.
  </span>
</center>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['issue']['iss_private'] == 1): ?>
<table bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td width="100%" align="center" class="default" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <b>Note: </b>This issue is marked private. Only Managers, the reporter and users assigned to the issue can view it.
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
<?php endif; ?>

<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="0" align="center" style="padding-left: 1;padding-right: 1;padding-top: 1;padding-bottom: 0">
<form method="get" action="update.php" onSubmit="javascript:return validateForm(this);">
<input type="hidden" name="id" value="<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default" nowrap>
            <b>Issue Overview</b> (ID: <a href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
" title="view issue details" class="link"><?php echo $this->_tpl_vars['issue']['iss_id']; ?>
</a>)
          </td>
          <td colspan="2" align="right" class="default">
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
            [ <a class="link" title="edit the authorized repliers list for this issue" href="javascript:void(null);" onClick="javascript:openAuthorizedReplier(<?php echo $_GET['id']; ?>
);">Edit Authorized Replier List</a> ]
            [ <a class="link" title="edit the notification list for this issue" href="javascript:void(null);" onClick="javascript:openNotification(<?php echo $_GET['id']; ?>
);">Edit Notification List</a> ]
            <?php endif; ?>
            [ <a class="link" title="view the full history of changes on this issue" href="javascript:void(null);" onClick="javascript:openHistory(<?php echo $_GET['id']; ?>
);">History of Changes</a> ]
          </td>
        </tr>
        <?php if ($this->_tpl_vars['has_customer_integration'] && $this->_tpl_vars['issue']['iss_customer_id']): ?>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Customer:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['customer_info']['customer_name']; ?>

            (<a href="#customer_details" class="link">Complete Details</a>)
          </td>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Customer Contract:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            Support Level: <?php echo $this->_tpl_vars['issue']['customer_info']['support_level']; ?>

            <?php if ($this->_tpl_vars['issue']['customer_info']['support_options']): ?>
            <br />
            Support Options: <?php echo $this->_tpl_vars['issue']['customer_info']['support_options']; ?>

            <?php endif; ?>
            <?php if ($this->_tpl_vars['issue']['customer_info']['is_per_incident']): ?>
              <br />
              Redeemed Incident Types:
              <?php if (isset($this->_foreach['incident_loop'])) unset($this->_foreach['incident_loop']);$this->_foreach['incident_loop']['name'] = 'incident_loop';$this->_foreach['incident_loop']['total'] = count($_from = (array)$this->_tpl_vars['issue']['redeemed_incidents']);$this->_foreach['incident_loop']['show'] = $this->_foreach['incident_loop']['total'] > 0;if ($this->_foreach['incident_loop']['show']):$this->_foreach['incident_loop']['iteration'] = 0;foreach ($_from as $this->_tpl_vars['incident_details']):$this->_foreach['incident_loop']['iteration']++;$this->_foreach['incident_loop']['first'] = ($this->_foreach['incident_loop']['iteration'] == 1);$this->_foreach['incident_loop']['last']  = ($this->_foreach['incident_loop']['iteration'] == $this->_foreach['incident_loop']['total']); if ($this->_tpl_vars['incident_details']['is_redeemed'] == 1):  if (! $this->_foreach['incident_loop']['first']): ?>, <?php endif;  echo $this->_tpl_vars['incident_details']['title'];  $this->assign('has_redeemed_incident', 1);  endif;  endforeach; unset($_from); endif; ?>
              <?php if ($this->_tpl_vars['has_redeemed_incident'] != 1): ?><i>None</i><?php endif; ?>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
                    <?php if ($this->_tpl_vars['show_category'] == 1): ?>
          <td width="150" nowrap bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Category:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['prc_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

          </td>
          <?php else: ?>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" rowspan="2">
            <b>Status:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default" rowspan="2">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['sta_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

          </td>
          <?php endif; ?>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" valign="top" class="default_white" nowrap>
            <b>Notification List:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" valign="top" class="default">
            <?php if ($this->_tpl_vars['subscribers']['staff'] != ''): ?>Staff: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['subscribers']['staff'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<", "&lt;") : smarty_modifier_replace($_tmp, "<", "&lt;")))) ? $this->_run_mod_handler('replace', true, $_tmp, ">", "&gt;") : smarty_modifier_replace($_tmp, ">", "&gt;"));  endif; ?>
            <?php if ($this->_tpl_vars['subscribers']['staff'] != '' && $this->_tpl_vars['subscribers']['customers'] != ''): ?><br /><?php endif; ?>
            <?php if ($this->_tpl_vars['subscribers']['customers'] != ''): ?>Other: <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['subscribers']['customers'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<", "&lt;") : smarty_modifier_replace($_tmp, "<", "&lt;")))) ? $this->_run_mod_handler('replace', true, $_tmp, ">", "&gt;") : smarty_modifier_replace($_tmp, ">", "&gt;"));  endif; ?>
          </td>
        </tr>
        <tr>
          <?php if ($this->_tpl_vars['show_category'] == 1): ?>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Status:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" class="default">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['sta_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

          </td>
          <?php endif; ?>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Submitted Date:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['iss_created_date']; ?>

          </td>
        </tr>
        <tr>
          <td <?php if ($this->_tpl_vars['current_role'] == $this->_tpl_vars['roles']['customer'] || $this->_tpl_vars['show_releases'] == 0): ?>rowspan="2"<?php endif; ?> width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Priority:</b>
          </td>
          <td <?php if ($this->_tpl_vars['current_role'] == $this->_tpl_vars['roles']['customer'] || $this->_tpl_vars['show_releases'] == 0): ?>rowspan="2"<?php endif; ?> bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['pri_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

          </td>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Last Updated Date:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['iss_updated_date']; ?>

          </td>
        </tr>
        <tr>
          <?php if ($this->_tpl_vars['current_role'] != $this->_tpl_vars['roles']['customer'] && $this->_tpl_vars['show_releases'] == 1): ?>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <nobr><b>Scheduled Release:</b>&nbsp;</nobr>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['pre_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

          </td>
          <?php endif; ?>
          <td nowrap width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Associated Issues:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['issue']['associated_issues_details']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
              <a href="view.php?id=<?php echo $this->_tpl_vars['issue']['associated_issues_details'][$this->_sections['i']['index']]['associated_issue']; ?>" title="issue #<?php echo $this->_tpl_vars['issue']['associated_issues_details'][$this->_sections['i']['index']]['associated_issue']; ?> (<?php echo $this->_tpl_vars['issue']['associated_issues_details'][$this->_sections['i']['index']]['current_status']; ?>) - <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['associated_issues_details'][$this->_sections['i']['index']]['associated_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>" class="<?php if ($this->_tpl_vars['issue']['associated_issues_details'][$this->_sections['i']['index']]['is_closed']): ?>closed_<?php endif; ?>link">#<?php echo $this->_tpl_vars['issue']['associated_issues_details'][$this->_sections['i']['index']]['associated_issue']; ?></a><?php if (! $this->_sections['i']['last']): ?>,<?php endif; ?>
            <?php endfor; else: ?>
              <i>No issues associated</i>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Resolution:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['iss_resolution']; ?>

          </td>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Expected Resolution Date:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php if ($this->_tpl_vars['issue']['iss_expected_resolution_date'] == 0): ?>
            <i>No resolution date given</i>
            <?php else: ?>
            <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['iss_expected_resolution_date'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Percentage Complete:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo ((is_array($_tmp=@$this->_tpl_vars['issue']['iss_percent_complete'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
%
          </td>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Estimated Dev. Time:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['iss_dev_time']; ?>

            <?php if ($this->_tpl_vars['issue']['iss_dev_time'] != ''): ?> hours<?php endif; ?>
          </td>
        </tr>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Reporter:</b>
          </td>
          <td width="50%" <?php if ($this->_tpl_vars['current_role'] <= $this->_tpl_vars['roles']['customer']): ?>colspan="3" <?php endif; ?>bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['reporter']; ?>

          </td>
          <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>Duplicates:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php if ($this->_tpl_vars['issue']['iss_duplicated_iss_id']): ?>
            Duplicate of: <a href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['issue']['iss_duplicated_iss_id']; ?>
" title="issue #<?php echo $this->_tpl_vars['issue']['iss_duplicated_iss_id']; ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['duplicated_issue']['current_status'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
) - <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['duplicated_issue']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" class="<?php if ($this->_tpl_vars['issue']['duplicated_issue']['is_closed']): ?>closed_<?php endif; ?>link">#<?php echo $this->_tpl_vars['issue']['iss_duplicated_iss_id']; ?>
</a>
            <?php endif; ?>
            <?php if (count($this->_tpl_vars['issue']['duplicates_details']) > 0): ?>
              <?php if ($this->_tpl_vars['issue']['iss_duplicated_iss_id']): ?><br /><?php endif; ?>
              Duplicated by:
              <?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['issue']['duplicates_details']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                <a href="<?php echo $this->_tpl_vars['rel_url']; ?>view.php?id=<?php echo $this->_tpl_vars['issue']['duplicates_details'][$this->_sections['i']['index']]['issue_id']; ?>" title="issue #<?php echo $this->_tpl_vars['issue']['duplicates_details'][$this->_sections['i']['index']]['issue_id']; ?> (<?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['duplicates_details'][$this->_sections['i']['index']]['current_status'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>) - <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['duplicates_details'][$this->_sections['i']['index']]['title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>" class="<?php if ($this->_tpl_vars['issue']['duplicates_details'][$this->_sections['i']['index']]['is_closed']): ?>closed_<?php endif; ?>link">#<?php echo $this->_tpl_vars['issue']['duplicates_details'][$this->_sections['i']['index']]['issue_id']; ?></a><?php if (! $this->_sections['i']['last']): ?>, <?php endif; ?>
              <?php endfor; endif; ?>
            <?php endif; ?>
          </td>
          <?php endif; ?>
        </tr>
        <tr>
          <td <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer'] && count($this->_tpl_vars['groups']) > 0): ?>rowspan="2"<?php endif; ?> width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Assignment:</b>
          </td>
          <td width="50%" <?php if ($this->_tpl_vars['current_role'] <= $this->_tpl_vars['roles']['customer']): ?>colspan="3" <?php elseif (count($this->_tpl_vars['groups']) > 0): ?>rowspan="2"<?php endif; ?> bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['assignments']; ?>

          </td>
          <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" valign="top" class="default_white" nowrap>
            <b>Authorized Repliers:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" valign="top" class="default">
            <?php if (count($this->_tpl_vars['issue']['authorized_repliers']['users']) > 0): ?>
                Staff:
                <?php if (isset($this->_sections['replier'])) unset($this->_sections['replier']);
$this->_sections['replier']['name'] = 'replier';
$this->_sections['replier']['loop'] = is_array($_loop=$this->_tpl_vars['issue']['authorized_repliers']['users']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['replier']['show'] = true;
$this->_sections['replier']['max'] = $this->_sections['replier']['loop'];
$this->_sections['replier']['step'] = 1;
$this->_sections['replier']['start'] = $this->_sections['replier']['step'] > 0 ? 0 : $this->_sections['replier']['loop']-1;
if ($this->_sections['replier']['show']) {
    $this->_sections['replier']['total'] = $this->_sections['replier']['loop'];
    if ($this->_sections['replier']['total'] == 0)
        $this->_sections['replier']['show'] = false;
} else
    $this->_sections['replier']['total'] = 0;
if ($this->_sections['replier']['show']):

            for ($this->_sections['replier']['index'] = $this->_sections['replier']['start'], $this->_sections['replier']['iteration'] = 1;
                 $this->_sections['replier']['iteration'] <= $this->_sections['replier']['total'];
                 $this->_sections['replier']['index'] += $this->_sections['replier']['step'], $this->_sections['replier']['iteration']++):
$this->_sections['replier']['rownum'] = $this->_sections['replier']['iteration'];
$this->_sections['replier']['index_prev'] = $this->_sections['replier']['index'] - $this->_sections['replier']['step'];
$this->_sections['replier']['index_next'] = $this->_sections['replier']['index'] + $this->_sections['replier']['step'];
$this->_sections['replier']['first']      = ($this->_sections['replier']['iteration'] == 1);
$this->_sections['replier']['last']       = ($this->_sections['replier']['iteration'] == $this->_sections['replier']['total']);
?>
                    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['issue']['authorized_repliers']['users'][$this->_sections['replier']['index']]['replier'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<", "&lt;") : smarty_modifier_replace($_tmp, "<", "&lt;")))) ? $this->_run_mod_handler('replace', true, $_tmp, ">", "&gt;") : smarty_modifier_replace($_tmp, ">", "&gt;"));  if ($this->_sections['replier']['last'] != 1): ?>,&nbsp;<?php endif; ?>
                <?php endfor; endif; ?>
                <br />
            <?php endif; ?>
            <?php if (count($this->_tpl_vars['issue']['authorized_repliers']['other']) > 0): ?>
                Other:
                <?php if (isset($this->_sections['replier'])) unset($this->_sections['replier']);
$this->_sections['replier']['name'] = 'replier';
$this->_sections['replier']['loop'] = is_array($_loop=$this->_tpl_vars['issue']['authorized_repliers']['other']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['replier']['show'] = true;
$this->_sections['replier']['max'] = $this->_sections['replier']['loop'];
$this->_sections['replier']['step'] = 1;
$this->_sections['replier']['start'] = $this->_sections['replier']['step'] > 0 ? 0 : $this->_sections['replier']['loop']-1;
if ($this->_sections['replier']['show']) {
    $this->_sections['replier']['total'] = $this->_sections['replier']['loop'];
    if ($this->_sections['replier']['total'] == 0)
        $this->_sections['replier']['show'] = false;
} else
    $this->_sections['replier']['total'] = 0;
if ($this->_sections['replier']['show']):

            for ($this->_sections['replier']['index'] = $this->_sections['replier']['start'], $this->_sections['replier']['iteration'] = 1;
                 $this->_sections['replier']['iteration'] <= $this->_sections['replier']['total'];
                 $this->_sections['replier']['index'] += $this->_sections['replier']['step'], $this->_sections['replier']['iteration']++):
$this->_sections['replier']['rownum'] = $this->_sections['replier']['iteration'];
$this->_sections['replier']['index_prev'] = $this->_sections['replier']['index'] - $this->_sections['replier']['step'];
$this->_sections['replier']['index_next'] = $this->_sections['replier']['index'] + $this->_sections['replier']['step'];
$this->_sections['replier']['first']      = ($this->_sections['replier']['iteration'] == 1);
$this->_sections['replier']['last']       = ($this->_sections['replier']['iteration'] == $this->_sections['replier']['total']);
?>
                    <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['issue']['authorized_repliers']['other'][$this->_sections['replier']['index']]['replier'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<", "&lt;") : smarty_modifier_replace($_tmp, "<", "&lt;")))) ? $this->_run_mod_handler('replace', true, $_tmp, ">", "&gt;") : smarty_modifier_replace($_tmp, ">", "&gt;"));  if ($this->_sections['replier']['last'] != 1): ?>,&nbsp;<?php endif; ?>
                <?php endfor; endif; ?>
            <?php endif; ?>
          </td>
          <?php endif; ?>
        </tr>
                <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer'] && count($this->_tpl_vars['groups']) > 0): ?>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" class="default_white">
            <b>Group:</b>
          </td>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['group']['grp_name']; ?>

          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Summary:</b><img src="images/blank.gif" height="1" width="150">
          </td>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" class="default">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['iss_summary'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

          </td>
        </tr>
        <tr>
          <td align="left" valign="top" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" width="150">
            <span class="default_white"><b>Initial Description:</b></span><br />
            <img src="images/blank.gif" height="1" width="150">
          </td>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" id="issue_description" class="default">
            <span id="description1" <?php echo smarty_function_get_display_style(array('element_name' => 'description'), $this);?>
><?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['iss_description'])) ? $this->_run_mod_handler('activateLinks', true, $_tmp, 'link') : Link_Filter::activateLinks($_tmp, 'link')); ?>
</span>
            <span id="description_hidden" style="display: none"><i>Description is currently collapsed. <a class="link" href="javascript:void(null);" onClick="javascript:toggleVisibility('description');collapseDescription();">Click to expand.</a></i></span>
            <?php echo '
            <script>
            collapseDescription();
            </script>
            '; ?>

          </td>
        </tr>
		<tr>
		  <td align="left" valign="top" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" width="150"> 
		  <span class="default_white"><b>Last Response:</b></span><br />
          </td>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" class="default"><span style="white-space:pre" <?php echo smarty_function_get_display_style(array('element_name' => 'description'), $this);?>
><?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['last_seb_body'])) ? $this->_run_mod_handler('activateLinks', true, $_tmp, 'link') : Link_Filter::activateLinks($_tmp, 'link')); ?>
</span>
          </td>
		</tr>
      </table>
    </td>
  </tr>
</table>
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="0" align="center" style="padding-left: 1;padding-right: 1;padding-top: 0;padding-bottom: 1">
    <tr>
      <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0" style="padding-top: 0px">
        <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['reporter']): ?>
        <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
          <td colspan="4">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <?php if ($this->_tpl_vars['current_role'] >= $this->_tpl_vars['roles']['developer']): ?>
                <td nowrap>
                  <?php if ($this->_tpl_vars['is_user_assigned'] == 1): ?>
                  <?php if ($this->_tpl_vars['allow_unassigned_issues'] == 'yes' || count($this->_tpl_vars['issue']['assigned_users']) > 1): ?>
                  <input class="button" type="button" value="Unassign Issue" onClick="javascript:unassign(<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
);">&nbsp;
                  <?php endif; ?>
                  <?php else: ?>
                  <input class="button" type="button" value="Assign Issue To Myself" onClick="javascript:selfAssign(<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
);">&nbsp;

                  <?php endif; ?>
                </td>
                <?php endif; ?>
                <td width="100%" align="center">
                  <input class="button" type="submit" value="Update Issue">
                  <?php if ($this->_tpl_vars['current_role'] >= $this->_tpl_vars['roles']['standard_user'] && $this->_tpl_vars['app_setup']['support_email'] == 'enabled' && $this->_tpl_vars['ema_id'] != ''): ?>
                  &nbsp;<input class="button" type="button" value="Reply" onClick="javascript:replyIssue(<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
,<?php if (count($this->_tpl_vars['issue']['assigned_users']) == 0): ?>true<?php else: ?>false<?php endif; ?>);">
                  <?php if ($this->_tpl_vars['issue']['last_sup_id']): ?> &nbsp;<input class="button" type="button" value="Reply to Last Email" onClick="javascript:reply(<?php echo $this->_tpl_vars['issue']['ema_id']; ?>
,<?php echo $this->_tpl_vars['issue']['last_sup_id']; ?>
);"><?php endif; ?> 
                  <?php endif; ?>
                </td>
                <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
                <td nowrap>
                  <nobr>
                  <?php if (! $this->_tpl_vars['issue']['sta_is_closed']): ?>
                    <?php if ($this->_tpl_vars['issue']['duplicates'] == ''): ?>
                      <?php if ($this->_tpl_vars['issue']['iss_duplicated_iss_id']): ?>
                      <input class="button" type="button" value="Clear Duplicate Status" onClick="javascript:clearDuplicateStatus(<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
);">
                      <?php else: ?>
                      <input class="button" type="button" value="Mark as Duplicate" onClick="javascript:window.location.href='duplicate.php?id=<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
';">
                      <?php endif; ?>
                    <?php endif; ?>
                    <input class="button" type="button" value="Close Issue" onClick="javascript:window.location.href='close.php?id=<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
';">
                  <?php endif; ?>
                  </nobr>
                </td>
                <?php endif; ?>
              </tr>
            </table>
          </td>
        </tr>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
        <tr bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
">
          <td colspan="4" align="right">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td width="25%">
                  <?php if ($this->_tpl_vars['is_user_authorized'] != 1): ?>
                  <input type="button" value="Signup as Authorized Replier" class="button" onClick="javascript:signupAsAuthorizedReplier(<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
);">
                  <?php endif; ?>
                  <?php if ($this->_tpl_vars['issue']['customer_info']['is_per_incident']): ?>
                    <input type="button" value="Edit Incident Redemption" class="button" onClick="javascript:editIncidentRedemption(<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
);">
                  <?php endif; ?>
                </td>
                <td width="25%" align="left">
                  <?php if ($this->_tpl_vars['statuses'] != '' && $this->_tpl_vars['current_role'] >= $this->_tpl_vars['roles']['developer']): ?>
                  <input type="button" value="Change Project To &gt;" class="button" onClick="javascript:changeIssueProject(this.form, '<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
');">
                  <select class="default" name="new_project">
              			<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['active_projects'],'selected' => $this->_tpl_vars['current_project']), $this);?>

                  </select>
                  <?php endif; ?>
                </td>
                <td width="25%" align="left">
                  <?php if ($this->_tpl_vars['statuses'] != '' && $this->_tpl_vars['current_role'] >= $this->_tpl_vars['roles']['developer']): ?>
                  <input type="button" value="Change Category To &gt;" class="button" onClick="javascript:changeIssueCategory(this.form, '<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
');">
                  <select class="default" name="new_category">
              		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['categories'],'selected' => $this->_tpl_vars['issue']['iss_prc_id']), $this);?>

                  </select>
                  <?php endif; ?>
                </td>
                <td width="25%" align="right">
                  <?php if ($this->_tpl_vars['statuses'] != ''): ?>
                  <input type="button" value="Change Status To &gt;" class="button" onClick="javascript:changeIssueStatus(this.form, '<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
', '<?php echo $this->_tpl_vars['issue']['iss_sta_id']; ?>
');">
                  <select class="default" name="new_status">
                    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['statuses'],'selected' => $this->_tpl_vars['issue']['iss_sta_id']), $this);?>

                  </select>
                  <?php endif; ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <?php endif; ?>
      </table>
    </td>
  </tr>
</form>
</table>
