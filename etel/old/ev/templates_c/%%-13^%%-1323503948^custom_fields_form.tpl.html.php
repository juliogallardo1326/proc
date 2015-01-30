<?php /* Smarty version 2.6.2, created on 2006-10-21 07:25:58
         compiled from custom_fields_form.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'custom_fields_form.tpl.html', 54, false),array('function', 'html_select_date', 'custom_fields_form.tpl.html', 71, false),array('function', 'html_options', 'custom_fields_form.tpl.html', 75, false),array('modifier', 'escape', 'custom_fields_form.tpl.html', 62, false),array('modifier', 'count', 'custom_fields_form.tpl.html', 81, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['update_result']): ?>
  <br />
  <center>
  <span class="default">
  <?php if ($this->_tpl_vars['update_result'] == -1): ?>
    <b>An error occurred while trying to run your query</b>
  <?php elseif ($this->_tpl_vars['update_result'] == 1): ?>
    <b>Thank you, the custom field values were updated successfully.</b>
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
<?php echo '
var required_custom_fields = new Array();
function validateForm(f)
{
    checkRequiredCustomFields(f, required_custom_fields);
}
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "js/httpclient.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
//-->
</script>
<script language="JavaScript" src="js/dynamic_custom_field.js.php?iss_id=<?php echo $_GET['issue_id']; ?>
"></script>
<form name="custom_field_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
" onSubmit="javascript:return checkFormSubmission(this, 'validateForm');">
<input type="hidden" name="cat" value="update_values">
<input type="hidden" name="issue_id" value="<?php echo $_GET['issue_id']; ?>
">
<table align="center" width="100%" cellpadding="3">
  <tr>
    <td>
      <table width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default">
            <b>Update Issue Details</b>
          </td>
        </tr>
        <?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['custom_fields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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

        <tr>
          <td width="120" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
            <nobr><b><?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_title']; ?>
:</b>&nbsp;</nobr>
          </td>
          <td width="100%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
            <input type="hidden" name="fld_id[]" value="<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
">
            <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'text'): ?>
            <input id="custom_field_<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
"  class="default" type="text" name="custom_fields[<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
]" maxlength="255" size="50" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['icf_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
            <?php elseif ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'textarea'): ?>
            <textarea name="custom_fields[<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
]" rows="10" cols="60"><?php echo ((is_array($_tmp=$this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['icf_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
            <?php elseif ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'date'): ?>
            <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['icf_value'] == ''): ?>
            <?php $this->assign('date_value', '--'); ?>
            <?php else: ?>
            <?php $this->assign('date_value', $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['icf_value']); ?>
            <?php endif; ?>
            <?php echo smarty_function_html_select_date(array('field_array' => "custom_fields[".($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id'])."]",'prefix' => '','all_extra' => "class=\"default\"",'year_empty' => '','month_empty' => '','day_empty' => '','time' => $this->_tpl_vars['date_value'],'start_year' => -1,'end_year' => "+2",'day_value_format' => "%02d"), $this);?>

            <?php else: ?>
            <select id="custom_field_<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
" class="default" name="custom_fields[<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
]<?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>[]<?php endif; ?>" <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>multiple size="3"<?php endif; ?>>
              <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] != 'multiple'): ?><option value="-1">Please choose an option</option><?php endif; ?>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['field_options'],'selected' => $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['selected_cfo_id']), $this);?>

            </select>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_description'] != ""): ?>
            <span class="small_default">(<?php echo ((is_array($_tmp=$this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
)</span>
            <?php endif; ?>
            <?php if (count($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['dynamic_options']) > 0): ?>
            <script>custom_field_init_dynamic_options(<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
);</script>
            <?php endif; ?>
          </td>
        </tr>
		<?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['showTransactionLookup']): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['showTransactionLookup'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
        <?php endfor; else: ?>
        <tr>
          <td align="center" class="default" colspan="2" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
">
            <b>No custom field could be found.</b>
          </td>
        </tr>
        <?php endif; ?>
        <tr>
          <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
            <input class="button" type="submit" value="Update Values">&nbsp;&nbsp;
            <input class="button" type="button" value="Close" onClick="javascript:window.close();">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>