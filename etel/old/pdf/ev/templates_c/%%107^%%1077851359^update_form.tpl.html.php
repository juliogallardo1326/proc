<?php /* Smarty version 2.6.2, created on 2006-10-20 02:38:30
         compiled from update_form.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'update_form.tpl.html', 114, false),array('modifier', 'replace', 'update_form.tpl.html', 138, false),array('modifier', 'date_format', 'update_form.tpl.html', 206, false),array('modifier', 'substr', 'update_form.tpl.html', 210, false),array('modifier', 'escape', 'update_form.tpl.html', 314, false),array('function', 'html_options', 'update_form.tpl.html', 120, false),array('function', 'html_select_date', 'update_form.tpl.html', 216, false),)), $this); ?>

<?php if ($this->_tpl_vars['update_result']): ?>
<br />
<table width="500" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td class="default">
            <?php if ($this->_tpl_vars['update_result'] == -1): ?>
              <b>Sorry, an error happened while trying to run your query.</b>
            <?php elseif ($this->_tpl_vars['update_result'] == 1): ?>
              <b>Thank you, issue #<?php echo $_POST['issue_id']; ?>
 was updated successfully.
              <?php if ($this->_tpl_vars['has_duplicates'] == 'yes'): ?>
                Also, all issues that are marked as duplicates from this one were updated as well.
              <?php endif; ?>
              <br /><br />
              <a href="view.php?id=<?php echo $_POST['issue_id']; ?>
" class="link">Return to Issue #<?php echo $_POST['issue_id']; ?>
 Details Page</a>
            <?php endif; ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php else:  echo '
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script language="JavaScript" src="js/overlib_mini.js"></script>
<script language="JavaScript">
<!--
function openHistory(issue_id)
{
    var features = \'width=420,height=400,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
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
function validateForm(f)
{
    if (isWhitespace(f.summary.value)) {
        alert(\'Please enter the summary for this issue.\');
        selectField(f, \'summary\');
        return false;
    }
    if (isWhitespace(f.description.value)) {
        alert(\'Please enter the description for this issue.\');
        selectField(f, \'description\');
        return false;
    }
    if ((f.percent_complete.value != \'\') && ((f.percent_complete.value < 0) || (f.percent_complete.value > 100))) {
        alert(\'Percentage complete should be between 0 and 100\');
        selectField(f, \'percent_complete\');
        return false;
    }
    '; ?>

    <?php if ($this->_tpl_vars['allow_unassigned_issues'] != 'yes' && $this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
    <?php echo '
    if (!hasOneSelected(f, \'assignments[]\')) {
        alert(\'Please select an assignment for this issue\');
        selectField(f, \'assignments[]\');
        return false;
    }
    '; ?>

    <?php endif; ?>
    <?php echo '
    return true;
}
//-->
</script>
'; ?>


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

<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
<form onSubmit="javascript:return validateForm(this);" name="update_form" method="post" action="update.php">
<input type="hidden" name="cat" value="update">
<input type="hidden" name="issue_id" value="<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
">
<input type="hidden" name="resolution" value="<?php echo $this->_tpl_vars['issue']['iss_res_id']; ?>
">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default" nowrap>
            <b>Update Issue Overview</b> (ID: <a href="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
" class="link"><?php echo $this->_tpl_vars['issue']['iss_id']; ?>
</a>)
          </td>
          <td colspan="2" align="right" class="default">
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
            [ <a class="link" title="edit the authorized repliers list for this issue" href="javascript:void(null);" onClick="javascript:openAuthorizedReplier(<?php echo $_GET['id']; ?>
);">Edit Authorized Replier List</a> ]
            [ <a class="link" href="javascript:void(null);" onClick="javascript:openNotification(<?php echo $_GET['id']; ?>
);">Edit Notification List</a> ]
            <?php endif; ?>
            [ <a class="link" href="javascript:void(null);" onClick="javascript:openHistory(<?php echo $_GET['id']; ?>
);">History of Changes</a> ]
          </td>
        </tr>
        <tr>
          <?php if (count($this->_tpl_vars['categories']) > 0): ?>
          <td width="120" nowrap bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Category:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select class="default" name="category">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['categories'],'selected' => $this->_tpl_vars['issue']['iss_prc_id']), $this);?>

            </select>
          </td>
          <?php else: ?>
          <input type="hidden" name="category" value="<?php echo $this->_tpl_vars['issue']['iss_prc_id']; ?>
">
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" rowspan="2">
            <b>Status:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
" rowspan="2">
            <select class="default" name="status">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['status'],'selected' => $this->_tpl_vars['issue']['iss_sta_id']), $this);?>

            </select>
          </td>
          <?php endif; ?>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
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
          <?php if (count($this->_tpl_vars['categories']) > 0): ?>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Status:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['issue']['status_color']; ?>
">
            <select class="default" name="status">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['status'],'selected' => $this->_tpl_vars['issue']['iss_sta_id']), $this);?>

            </select>
          </td>
          <?php endif; ?>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Submitted Date:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['iss_created_date']; ?>

          </td>
        </tr>
        <tr>
          <td <?php if ($this->_tpl_vars['current_role'] < $this->_tpl_vars['roles']['standard_user']): ?>rowspan="2"<?php endif; ?> width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Priority:</b>
          </td>
          <td <?php if ($this->_tpl_vars['current_role'] < $this->_tpl_vars['roles']['standard_user']): ?>rowspan="2"<?php endif; ?> bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select class="default" name="priority">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['priorities'],'selected' => $this->_tpl_vars['issue']['iss_pri_id']), $this);?>

            </select>
          </td>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Update Date:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['iss_updated_date']; ?>

          </td>
        </tr>
        <tr>
          <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <nobr><b>Associated Issues:</b></nobr>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select size="4" multiple name="associated_issues[]" class="default" onChange="showSelections('update_form', 'associated_issues[]')">
              <?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['issues'],'output' => $this->_tpl_vars['issues'],'selected' => $this->_tpl_vars['issue']['associated_issues']), $this);?>

            </select>
            <?php if (! ( $this->_tpl_vars['os']['mac'] && $this->_tpl_vars['browser']['ie'] )): ?><a title="lookup issues by their summaries" href="javascript:void(null);" onClick="return overlib(getOverlibContents('<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lookup_layer.tpl.html", 'smarty_include_vars' => array('list' => $this->_tpl_vars['assoc_issues'],'multiple' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>', 'update_form', 'associated_issues[]', true), STICKY, HEIGHT, 50, WIDTH, 250, BELOW, RIGHT, CLOSECOLOR, '#FFFFFF', FGCOLOR, '#FFFFFF', BGCOLOR, '#000000', CAPTION, 'Lookup Details', CLOSECLICK);" onMouseOut="javascript:nd();"><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/lookup.gif" border="0"></a><?php endif; ?>
            <div class="default" id="selection_associated_issues[]"></div>
          </td>
          <?php endif; ?>
          <td nowrap width="130" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Reporter:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <?php echo $this->_tpl_vars['issue']['reporter']; ?>

          </td>
        </tr>
        <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Expected Resolution Date:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" <?php if (count($this->_tpl_vars['releases']) == 0): ?>colspan="3"<?php endif; ?>>
                        <?php if ($this->_tpl_vars['issue']['iss_expected_resolution_date'] == ''): ?>
                <?php $this->assign('expected_resolution_date', '0000-00-00'); ?>
                <?php $this->assign('expected_resolution_start_year', ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y"))); ?>
            <?php else: ?>
                <?php $this->assign('expected_resolution_date', $this->_tpl_vars['issue']['iss_expected_resolution_date']); ?>
                                <?php if (((is_array($_tmp=$this->_tpl_vars['expected_resolution_date'])) ? $this->_run_mod_handler('substr', true, $_tmp, '0', '4') : substr($_tmp, '0', '4')) < ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y"))): ?>
                    <?php $this->assign('expected_resolution_start_year', ((is_array($_tmp=$this->_tpl_vars['expected_resolution_date'])) ? $this->_run_mod_handler('substr', true, $_tmp, '0', '4') : substr($_tmp, '0', '4'))); ?>
                <?php else: ?>
                    <?php $this->assign('expected_resolution_start_year', ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y"))); ?>
                <?php endif; ?>
            <?php endif; ?>
            <?php echo smarty_function_html_select_date(array('time' => $this->_tpl_vars['expected_resolution_date'],'field_array' => 'expected_resolution_date','prefix' => "",'start_year' => $this->_tpl_vars['expected_resolution_start_year'],'end_year' => "+1",'all_extra' => 'class="default"','year_empty' => "",'day_empty' => "",'month_empty' => "",'day_value_format' => '%02d'), $this);?>

          </td>
          <?php if (count($this->_tpl_vars['releases']) > 0): ?>
          <td nowrap width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Scheduled Release:</b>
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select name="release" class="default">
              <option value="0"></option>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['releases'],'selected' => $this->_tpl_vars['issue']['iss_pre_id']), $this);?>

            </select>
          </td>
          <?php endif; ?>
        </tr>
        <tr>
          <td width="120" nowrap bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Percentage Complete:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <input type="text" name="percent_complete" value="<?php echo $this->_tpl_vars['issue']['iss_percent_complete']; ?>
" size="2" class="default">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'percent_complete')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <span class="default">(0 - 100)</span>
          </td>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white" nowrap>
            <b>Estimated Dev. Time:</b>
          </td>
          <td width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" valign="top">
           <input type="text" name="estimated_dev_time" value="<?php echo $this->_tpl_vars['issue']['iss_dev_time']; ?>
" size="4" class="default">
           <span class="default">(in hours)</span>
          </td>
        </tr>
        <tr>
          <td <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer'] && count($this->_tpl_vars['groups']) > 0): ?>rowspan="2" <?php endif; ?>width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Assignment: <?php if ($this->_tpl_vars['allow_unassigned_issues'] != 'yes'): ?>*<?php endif; ?></b>
          </td>
          <td <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer'] && count($this->_tpl_vars['groups']) > 0): ?>rowspan="2" <?php endif; ?>bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <?php if ($this->_tpl_vars['issue']['has_inactive_users']): ?>
            <span class="default"><input type="radio" name="keep_assignments" checked value="yes"> <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('update_form', 'keep_assignments', 0);">Keep Current Assignments: <?php echo $this->_tpl_vars['issue']['assignments']; ?>
</a>
            <br />
            <input type="radio" name="keep_assignments" value="no"> <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('update_form', 'keep_assignments', 1);">Change Assignments:</a> </span><br />
            <?php else: ?>
            <input type="hidden" name="keep_assignments" value="no">
            <?php endif; ?>
            <select size="<?php if ($this->_tpl_vars['issue']['has_inactive_users']): ?>3<?php else: ?>4<?php endif; ?>" multiple class="default" name="assignments[]" onChange="javascript:showSelections('update_form', 'assignments[]');">
              <?php if ($this->_tpl_vars['issue']['has_inactive_users']): ?>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users']), $this);?>

              <?php else: ?>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['issue']['assigned_users']), $this);?>

              <?php endif; ?>
            </select><input type="button" class="shortcut" value="Clear Selections" onClick="javascript:clearSelectedOptions(getFormElement(this.form, 'assignments[]'));showSelections('update_form', 'assignments[]');"><br />
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lookup_field.tpl.html", 'smarty_include_vars' => array('lookup_field_name' => 'search','lookup_field_target' => "assignments[]",'callbacks' => "new Array('showSelections(\'update_form\', \'assignments[]\')')")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <div class="default" id="selection_assignments[]"><?php if ($this->_tpl_vars['issue']['assignments']): ?>Current Selections: <?php echo $this->_tpl_vars['issue']['assignments'];  endif; ?></div>
          </td>
          <td width="140" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
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
        </tr>
        <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer'] && count($this->_tpl_vars['groups']) > 0): ?>
        <tr>
            <td width="140" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" valign="middle" class="default_white" nowrap >
                <b>Group:</b>
            </td>
            <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" valign="middle" >
                <select class="default" name="group">
                <option value=""></option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['groups'],'selected' => $this->_tpl_vars['issue']['iss_grp_id']), $this);?>

                </select>
            </td>
        </tr>
        <?php else: ?>
            <input type="hidden" name="group" value="<?php echo $this->_tpl_vars['issue']['iss_grp_id']; ?>
">
        <?php endif; ?>
        <?php else: ?>
        <input type="hidden" name="keep_assignments" value="yes">
        <?php if (count($_from = (array)$this->_tpl_vars['issue']['associated_issues'])):
    foreach ($_from as $this->_tpl_vars['_issue_id'] => $this->_tpl_vars['_issue_summary']):
?>
        <input type="hidden" name="associated_issues[]" value="<?php echo $this->_tpl_vars['_issue_id']; ?>
">
        <?php endforeach; unset($_from); endif; ?>
        <input type="hidden" name="estimated_dev_time" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['iss_dev_time'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
        <input type="hidden" name="release" value="<?php echo $this->_tpl_vars['issue']['iss_pre_id']; ?>
">
        <input type="hidden" name="group" value="<?php echo $this->_tpl_vars['issue']['iss_grp_id']; ?>
">
        <?php endif; ?>
        <?php if (count($this->_tpl_vars['releases']) < 1): ?>
            <input type="hidden" name="release" value="<?php echo $this->_tpl_vars['issue']['iss_pre_id']; ?>
">
        <?php endif; ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Summary:</b>
          </td>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
">
            <input type="text" class="default" size="60" name="summary" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['iss_summary'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'summary')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Description:</b>
          </td>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" class="default">
            <textarea name="description" rows="20" style="width: 97%"><?php echo ((is_array($_tmp=$this->_tpl_vars['issue']['iss_description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'description')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
          </td>
        </tr>
        <?php if ($this->_tpl_vars['current_role'] >= $this->_tpl_vars['roles']['developer']): ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Private:</b>
          </td>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" class="default">
            <input type="radio" name="private" value="1" <?php if ($this->_tpl_vars['issue']['iss_private']): ?>checked<?php endif; ?>>
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('update_form', 'private', 0);">Yes</a>
            <input type="radio" name="private" value="0" <?php if (! $this->_tpl_vars['issue']['iss_private']): ?>checked<?php endif; ?>>
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('update_form', 'private', 1);">No</a>
          </td>
        </tr>
        <?php else: ?>
        <input type="hidden" name="trigger_reminders" value="<?php echo $this->_tpl_vars['issue']['iss_trigger_reminders']; ?>
">
        <?php endif; ?>
        <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['standard_user']): ?>
        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <b>Trigger Reminders:</b>
          </td>
          <td colspan="3" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" class="default">
            <input type="radio" name="trigger_reminders" value="1" <?php if ($this->_tpl_vars['issue']['iss_trigger_reminders']): ?>checked<?php endif; ?>>
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('update_form', 'trigger_reminders', 0);">Yes</a>
            <input type="radio" name="trigger_reminders" value="0" <?php if (! $this->_tpl_vars['issue']['iss_trigger_reminders']): ?>checked<?php endif; ?>>
            <a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('update_form', 'trigger_reminders', 1);">No</a>
          </td>
        </tr>
        <?php else: ?>
        <input type="hidden" name="trigger_reminders" value="<?php echo $this->_tpl_vars['issue']['iss_trigger_reminders']; ?>
">
        <?php endif; ?>
        <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
          <td colspan="4">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center">
                  <input class="button" type="submit" value="Update">&nbsp;
                  <input class="button" type="button" value="Cancel Update" onClick="javascript:history.go(-1);">&nbsp;
                  <input class="button" type="reset" value="Reset">
                  <?php if (( ! $this->_tpl_vars['issue']['sta_is_closed'] ) && $this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
                  &nbsp;<input class="button" type="button" value="Close Issue" onClick="javascript:window.location.href='close.php?id=<?php echo $this->_tpl_vars['issue']['iss_id']; ?>
';">
                  <?php endif; ?>
                </td>
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
