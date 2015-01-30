<?php /* Smarty version 2.6.2, created on 2006-10-25 11:20:56
         compiled from custom_fields.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'get_innerhtml', 'custom_fields.tpl.html', 41, false),array('function', 'cycle', 'custom_fields.tpl.html', 49, false),array('function', 'get_display_style', 'custom_fields.tpl.html', 50, false),array('modifier', 'count', 'custom_fields.tpl.html', 41, false),array('modifier', 'escape', 'custom_fields.tpl.html', 56, false),array('modifier', 'activateLinks', 'custom_fields.tpl.html', 56, false),array('modifier', 'nl2br', 'custom_fields.tpl.html', 56, false),array('modifier', 'formatCustomValue', 'custom_fields.tpl.html', 58, false),)), $this); ?>

<script language="JavaScript">
<!--
var reporter_email = '<?php echo $this->_tpl_vars['issue']['reporter_email']; ?>
';
<?php echo '

function td_showtrans(ref)
{
	var trans_frame_td = getPageElement(\'trans_frame_td\');
	var trans_frame = getPageElement(\'trans_frame\');
	if(trans_frame_td.style.display!=\'\')
	{
		trans_frame_td.style.display=\'\';
		trans_frame.src=ref;
	}
	else
		trans_frame_td.style.display=\'none\';
}
				
function updateCustomFields(issue_id)
{
    var features = \'width=560,height=460,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
    var customWin = window.open(\'custom_fields.php?issue_id=\' + issue_id + \'&reporter_email=\'+reporter_email, \'_custom_fields\', features);
    customWin.focus();
}
'; ?>

//-->
</script>
<br />
<table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
  <tr>
    <td width="100%">
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td class="default" nowrap>
            <b>Custom Fields</b>
          </td>
          <td align="right" class="default">
            <?php if ($this->_tpl_vars['browser']['ie5up'] || $this->_tpl_vars['browser']['ns6up'] || $this->_tpl_vars['browser']['gecko'] || $this->_tpl_vars['browser']['safari'] || $this->_tpl_vars['browser']['opera5up']): ?>
            [ <a id="custom_fields_link" class="link" href="javascript:void(null);" onClick="javascript:toggleVisibility('custom_fields');"><?php echo smarty_function_get_innerhtml(array('element_name' => 'custom_fields','total' => count($this->_tpl_vars['custom_fields'])), $this);?>
</a> ]
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <table width="100%" cellpadding="2" cellspacing="1">
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

              <tr id="custom_fields<?php echo $this->_sections['i']['iteration']; ?>
" <?php echo smarty_function_get_display_style(array('element_name' => 'custom_fields','total' => count($this->_tpl_vars['custom_fields'])), $this);?>
 <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['hide_when_no_options'] == 1 && $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['icf_value'] == ''): ?>style="display: none"<?php endif; ?> valign="top">
                <td bgcolor="<?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_min_role'] > $this->_tpl_vars['roles']['customer']):  echo $this->_tpl_vars['internal_color'];  else:  echo $this->_tpl_vars['cell_color'];  endif; ?>" class="default_white">
                  <nobr><b><?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_title']; ?>
:</b>&nbsp;</nobr>
                </td>
                <td class="default" width="100%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                  <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'textarea'): ?>
                    <?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['icf_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('activateLinks', true, $_tmp, 'link') : Link_Filter::activateLinks($_tmp, 'link')))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

                  <?php else: ?>
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['icf_value'])) ? $this->_run_mod_handler('formatCustomValue', true, $_tmp, $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id'], $_GET['id'], true) : Custom_Field::formatValue($_tmp, $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id'], $_GET['id'], true)); ?>

                  <?php endif; ?>
                </td>
              </tr>
              <?php endfor; else: ?>
              <tr id="custom_fields1" <?php echo smarty_function_get_display_style(array('element_name' => 'custom_fields','total' => count($this->_tpl_vars['custom_fields'])), $this);?>
>
                <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="center" class="default">
                  <i>No custom fields could be found.</i>
                </td>
              </tr>
              <?php endif; ?>
              <tr id="trans_frame_td" style="display: none" valign="top">
                <td bgcolor="<?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_min_role'] > $this->_tpl_vars['roles']['customer']):  echo $this->_tpl_vars['internal_color'];  else:  echo $this->_tpl_vars['cell_color'];  endif; ?>" class="default_white">
                  <nobr><b>Transaction Info:</b>&nbsp;</nobr>
                </td>
                <td class="default" width="100%" bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
				<iframe id='trans_frame' frameborder='0' style='background-color:#FFFFFF;' src='' width='100%' height='800px' ></iframe>
                </td>
              </tr>
              <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['reporter'] && $this->_tpl_vars['custom_fields'] != ""): ?>
              <tr>
                <td align="center" colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                  <input class="button" type="button" value="Update" onClick="javascript:updateCustomFields(<?php echo $_GET['id']; ?>
);">
                </td>
              </tr>
              <?php endif; ?>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  </form>
</table>
