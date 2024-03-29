<?php /* Smarty version 2.6.2, created on 2006-10-20 02:14:58
         compiled from attachments.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'attachments.tpl.html', 41, false),array('modifier', 'escape', 'attachments.tpl.html', 66, false),array('modifier', 'capitalize', 'attachments.tpl.html', 79, false),array('function', 'get_innerhtml', 'attachments.tpl.html', 45, false),array('function', 'get_display_style', 'attachments.tpl.html', 52, false),array('function', 'cycle', 'attachments.tpl.html', 62, false),)), $this); ?>

<?php echo '
<script language="JavaScript">
<!--
function deleteAttachment(file_id)
{
    if (!confirm(\'This action will permanently delete the selected attachment.\')) {
        return false;
    } else {
        var features = \'width=420,height=200,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
        var popupWin = window.open(\'popup.php?cat=delete_attachment&id=\' + file_id, \'_popup\', features);
        popupWin.focus();
    }
}
function deleteAttachmentFile(file_id)
{
    if (!confirm(\'This action will permanently delete the selected file.\')) {
        return false;
    } else {
        var features = \'width=420,height=200,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
        var popupWin = window.open(\'popup.php?cat=delete_file&id=\' + file_id, \'_popup\', features);
        popupWin.focus();
    }
}
function uploadFile(issue_id)
{
    var features = \'width=600,height=300,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var popupWin = window.open(\'file_upload.php?iss_id=\' + issue_id, \'file_upload_\' + issue_id, features);
    popupWin.focus();
}
//-->
</script>
'; ?>

<br />
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td width="100%">
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td class="default" nowrap>
            <b>Attached Files (<?php echo count($this->_tpl_vars['files']); ?>
)</b>
          </td>
          <td align="right" class="default">
            <?php if ($this->_tpl_vars['browser']['ie5up'] || $this->_tpl_vars['browser']['ns6up'] || $this->_tpl_vars['browser']['gecko'] || $this->_tpl_vars['browser']['safari']): ?>
            [ <a id="attachments_link" class="link" href="javascript:void(null);" onClick="javascript:toggleVisibility('attachments');"><?php echo smarty_function_get_innerhtml(array('element_name' => 'attachments'), $this);?>
</a> ]
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <table width="100%" cellpadding="2" cellspacing="1">
              <tr id="attachments1" <?php echo smarty_function_get_display_style(array('element_name' => 'attachments'), $this);?>
>
                <td class="default_white" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">Files</td>
                <td class="default_white" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">Owner</td>
                <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
                <td class="default_white" bgcolor="<?php echo $this->_tpl_vars['internal_color']; ?>
" align="center">Status</td>
                <?php endif; ?>
                <td class="default_white" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">Date</td>
                <td class="default_white" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">Description</td>
              </tr>
              <?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
              <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

              <tr id="attachments<?php echo $this->_sections['i']['iteration']+1; ?>
" <?php echo smarty_function_get_display_style(array('element_name' => 'attachments'), $this);?>
 bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                <td class="default">
                  <?php if (isset($this->_sections['y'])) unset($this->_sections['y']);
$this->_sections['y']['name'] = 'y';
$this->_sections['y']['loop'] = is_array($_loop=$this->_tpl_vars['files'][$this->_sections['i']['index']]['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['y']['show'] = true;
$this->_sections['y']['max'] = $this->_sections['y']['loop'];
$this->_sections['y']['step'] = 1;
$this->_sections['y']['start'] = $this->_sections['y']['step'] > 0 ? 0 : $this->_sections['y']['loop']-1;
if ($this->_sections['y']['show']) {
    $this->_sections['y']['total'] = $this->_sections['y']['loop'];
    if ($this->_sections['y']['total'] == 0)
        $this->_sections['y']['show'] = false;
} else
    $this->_sections['y']['total'] = 0;
if ($this->_sections['y']['show']):

            for ($this->_sections['y']['index'] = $this->_sections['y']['start'], $this->_sections['y']['iteration'] = 1;
                 $this->_sections['y']['iteration'] <= $this->_sections['y']['total'];
                 $this->_sections['y']['index'] += $this->_sections['y']['step'], $this->_sections['y']['iteration']++):
$this->_sections['y']['rownum'] = $this->_sections['y']['iteration'];
$this->_sections['y']['index_prev'] = $this->_sections['y']['index'] - $this->_sections['y']['step'];
$this->_sections['y']['index_next'] = $this->_sections['y']['index'] + $this->_sections['y']['step'];
$this->_sections['y']['first']      = ($this->_sections['y']['iteration'] == 1);
$this->_sections['y']['last']       = ($this->_sections['y']['iteration'] == $this->_sections['y']['total']);
?>
                  <a title="download file (<?php echo ((is_array($_tmp=$this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 - <?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_filesize']; ?>
)" href="download.php?cat=attachment&id=<?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_id']; ?>
"><img width="17" height="17" src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/file.gif" border="0"></a>
                  <a class="link" title="download file (<?php echo ((is_array($_tmp=$this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 - <?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_filesize']; ?>
)" href="download.php?cat=attachment&id=<?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a> (<?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_filesize']; ?>
)
                  <?php if ($this->_tpl_vars['current_user_id'] == $this->_tpl_vars['files'][$this->_sections['i']['index']]['iat_usr_id']): ?><a class="link" title="delete file" href="javascript:void(null);" onClick="javascript:deleteAttachmentFile(<?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['files'][$this->_sections['y']['index']]['iaf_id']; ?>
);">delete</a><?php endif; ?>
                  <br />
                  <?php endfor; endif; ?>
                </td>
                <td class="default" width="15%" nowrap>
                  <?php echo ((is_array($_tmp=$this->_tpl_vars['files'][$this->_sections['i']['index']]['usr_full_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

                  <?php if ($this->_tpl_vars['current_user_id'] == $this->_tpl_vars['files'][$this->_sections['i']['index']]['iat_usr_id']): ?>[ <a class="link" title="delete attachment" href="javascript:void(null);" onClick="javascript:deleteAttachment(<?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['iat_id']; ?>
);">delete</a> ]<?php endif; ?>
                </td>
                <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
                <td class="default" width="5%" align="center">
                  <?php if ($this->_tpl_vars['files'][$this->_sections['i']['index']]['iat_status'] == 'internal'): ?>
                    <span style="color: red;"><?php echo ((is_array($_tmp=$this->_tpl_vars['files'][$this->_sections['i']['index']]['iat_status'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</span>
                  <?php else: ?>
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['files'][$this->_sections['i']['index']]['iat_status'])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>

                  <?php endif; ?>
                </td>
                <?php endif; ?>
                <td class="default" width="15%" nowrap><?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['iat_created_date']; ?>
</td>
                <td class="default" width="45%"><?php echo $this->_tpl_vars['files'][$this->_sections['i']['index']]['iat_description']; ?>
</td>
              </tr>
              <?php endfor; else: ?>
              <tr id="attachments2" <?php echo smarty_function_get_display_style(array('element_name' => 'attachments'), $this);?>
>
                <td colspan="<?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>5<?php else: ?>4<?php endif; ?>" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                  <i>No attachments could be found.</i>
                </td>
              </tr>
              <?php endif; ?>
              <form>
              <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['viewer']): ?>
              <tr>
                <td colspan="<?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>5<?php else: ?>4<?php endif; ?>" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="center">
                  <input type="button" class="button" value="Upload File" onClick="javascript:uploadFile(<?php echo $_GET['id']; ?>
);">
                </td>
              </tr>
              <?php endif; ?>
              </form>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
