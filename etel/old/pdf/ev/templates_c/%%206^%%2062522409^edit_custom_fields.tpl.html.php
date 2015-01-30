<?php /* Smarty version 2.6.2, created on 2006-10-19 16:53:45
         compiled from edit_custom_fields.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_select_date', 'edit_custom_fields.tpl.html', 29, false),array('function', 'html_options', 'edit_custom_fields.tpl.html', 38, false),array('modifier', 'count', 'edit_custom_fields.tpl.html', 41, false),array('modifier', 'escape', 'edit_custom_fields.tpl.html', 51, false),)), $this); ?>
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
 $this->assign('fld_id', $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']);  $this->assign('custom_field_id', $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']);  if ($this->_tpl_vars['form_type'] == 'report'): ?>
  <?php $this->assign('cf_required', $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_report_form_required']);  elseif ($this->_tpl_vars['form_type'] == 'anonymous'): ?>
  <?php $this->assign('cf_required', $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_anonymous_form_required']);  endif; ?>
<tr>
  <td width="150" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" class="default_white">
    <b><?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_title']; ?>
:<?php if ($this->_tpl_vars['cf_required']): ?> *<?php endif; ?></b>
    <?php if ($this->_tpl_vars['cf_required']): ?>
    <script language="JavaScript">
    <!--
    custom_fields[custom_fields.length] = new Option('custom_fields[<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
]<?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>[]<?php endif; ?>', '<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_title']; ?>
');
    required_custom_fields[required_custom_fields.length] = new Option('custom_fields[<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
]<?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>[]<?php endif; ?>', <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>'multiple'<?php elseif ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'combo'): ?>'combo'<?php elseif ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'date'): ?>'date'<?php else: ?>'whitespace'<?php endif; ?>);
    //-->
    </script>
    <?php endif; ?>
  </td>
  <td bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
    <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'text'): ?>
    <input id="custom_field_<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
" class="default" type="text" name="custom_fields[<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
]" maxlength="255" size="50" tabindex="<?php echo $this->_tpl_vars['tabindex']++; ?>
" value="<?php echo $_REQUEST['custom_fields'][$this->_tpl_vars['fld_id']]; ?>
">
    <?php elseif ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'textarea'): ?>
    <textarea id="custom_field_<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
" name="custom_fields[<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
]" rows="10" cols="60" tabindex="<?php echo $this->_tpl_vars['tabindex']++; ?>
"><?php echo $_REQUEST['custom_fields'][$this->_tpl_vars['fld_id']]; ?>
</textarea>
    <?php elseif ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'date'): ?>
    <?php echo smarty_function_html_select_date(array('field_array' => "custom_fields[".($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id'])."]",'prefix' => '','all_extra' => "class=\"default\"",'month_empty' => '','time' => '--','display_years' => false,'display_days' => false,'month_extra' => "id=\"custom_field_".($this->_tpl_vars['custom_field_id'])."_month\" tabindex=\"".($this->_tpl_vars['tabindex']++)."\""), $this);?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "custom_fields[".($this->_tpl_vars['custom_field_id'])."][Month]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php echo smarty_function_html_select_date(array('field_array' => "custom_fields[".($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id'])."]",'prefix' => '','all_extra' => "class=\"default\"",'day_empty' => '','time' => '--','display_months' => false,'display_years' => false,'day_value_format' => "%02d",'day_extra' => "id=\"custom_field_".($this->_tpl_vars['custom_field_id'])."_day\" tabindex=\"".($this->_tpl_vars['tabindex']++)."\""), $this);?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "custom_fields[".($this->_tpl_vars['custom_field_id'])."][Day]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php echo smarty_function_html_select_date(array('field_array' => "custom_fields[".($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id'])."]",'prefix' => '','all_extra' => "class=\"default\"",'year_empty' => '','time' => '--','display_months' => false,'display_days' => false,'start_year' => -1,'end_year' => "+2",'year_extra' => "id=\"custom_field_".($this->_tpl_vars['custom_field_id'])."_year\" tabindex=\"".($this->_tpl_vars['tabindex']++)."\""), $this);?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "custom_fields[".($this->_tpl_vars['custom_field_id'])."][Year]")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php else: ?>
    <select id="custom_field_<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
" <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>multiple size="3"<?php endif; ?> class="default" name="custom_fields[<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
]<?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>[]<?php endif; ?>" tabindex="<?php echo $this->_tpl_vars['tabindex']++; ?>
">
      <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] != 'multiple'): ?><option value="-1">Please choose an option</option><?php endif; ?>
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['field_options'],'selected' => $_REQUEST['custom_fields'][$this->_tpl_vars['fld_id']]), $this);?>

    </select>
    <?php endif; ?>
    <?php if (count($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['dynamic_options']) > 0): ?>
    <script>custom_field_init_dynamic_options(<?php echo $this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_id']; ?>
);</script>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_type'] == 'multiple'): ?>
      <?php $this->assign('custom_field_sufix', "[]"); ?>
    <?php else: ?>
      <?php $this->assign('custom_field_sufix', ""); ?>
    <?php endif; ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => "custom_fields[".($this->_tpl_vars['custom_field_id'])."]".($this->_tpl_vars['custom_field_sufix']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php if ($this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_description'] != ""): ?>
    <span class="small_default">(<?php echo ((is_array($_tmp=$this->_tpl_vars['custom_fields'][$this->_sections['i']['index']]['fld_description'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
)</span>
    <?php endif; ?>
  </td>
</tr>
<?php endfor; endif; ?>