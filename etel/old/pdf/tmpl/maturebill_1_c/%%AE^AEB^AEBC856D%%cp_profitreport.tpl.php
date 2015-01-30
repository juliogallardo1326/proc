<?php /* Smarty version 2.6.9, created on 2007-04-11 22:27:44
         compiled from cp_profitreport.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'formatMoney', 'cp_profitreport.tpl', 8, false),array('modifier', 'intval', 'cp_profitreport.tpl', 8, false),array('function', 'cycle', 'cp_profitreport.tpl', 12, false),)), $this); ?>
<table class='invoice' width='100%'>
	<?php if ($this->_tpl_vars['Profit']['Title']): ?>
   <tr class='infoSection'>
     <td colspan="2"><a <?php if ($this->_tpl_vars['Profit']['Link']): ?> href='<?php echo $this->_tpl_vars['Profit']['Link']; ?>
'<?php endif; ?>><?php echo $this->_tpl_vars['Profit']['Title']; ?>
</a></td>
   </tr>
   <?php endif; ?>
   <tr class='infoHeader'>
     <td>Revenue</td><td><a <?php if ($this->_tpl_vars['Profit']['Revenue']['Link']): ?> href='<?php echo $this->_tpl_vars['Profit']['Revenue']['Link']; ?>
'<?php endif; ?> >$<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Revenue']['Total']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Revenue']['Total']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</a></td>
   </tr>
   <?php $_from = $this->_tpl_vars['Profit']['Revenue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['type']):
?>	
   <?php if ($this->_tpl_vars['key'] != 'Total'): ?>   
   <tr class='infoSubSection row<?php echo smarty_function_cycle(array('values' => "1,2"), $this);?>
'>
	 <td><?php echo $this->_tpl_vars['key'];  if ($this->_tpl_vars['type']['Comments']): ?><br /><span class="small"><?php echo $this->_tpl_vars['type']['Comments']; ?>
</span><?php endif; ?></td> <td><a <?php if ($this->_tpl_vars['type']['Link']): ?> href='<?php echo $this->_tpl_vars['type']['Link']; ?>
'<?php endif; ?> >$<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</a></td>
   </tr>
   <?php endif; ?>   
   <?php endforeach; endif; unset($_from); ?>
   <tr class='infoHeader'>
     <td>Deductions</td><td><a <?php if ($this->_tpl_vars['Profit']['Deductions']['Link']): ?> href='<?php echo $this->_tpl_vars['Profit']['Deductions']['Link']; ?>
'<?php endif; ?> >$<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Deductions']['Total']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Deductions']['Total']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</a></td>
   </tr>
   <?php $_from = $this->_tpl_vars['Profit']['Deductions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['type']):
?>
   <?php if ($this->_tpl_vars['key'] != 'Total'): ?>   	   
   <tr class='infoSubSection row<?php echo smarty_function_cycle(array('values' => "1,2"), $this);?>
'>
	 <td><?php echo $this->_tpl_vars['key'];  if ($this->_tpl_vars['type']['Comments']): ?><br /><span class="small"><?php echo $this->_tpl_vars['type']['Comments']; ?>
</span><?php endif; ?></td> <td><a <?php if ($this->_tpl_vars['type']['Link']): ?> href='<?php echo $this->_tpl_vars['type']['Link']; ?>
'<?php endif; ?> >$<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</a></td>
   </tr>
   <?php endif; ?>   
   <?php endforeach; endif; unset($_from); ?>
   <tr class='infoHeader'>
     <td>Total Profit </td>
     <td><a <?php if ($this->_tpl_vars['Profit']['Total']['Link']): ?> href='<?php echo $this->_tpl_vars['Profit']['Total']['Link']; ?>
'<?php endif; ?> >$<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Total']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Total']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</a></td>
   </tr>
	<?php if ($this->_tpl_vars['Profit']['Notes']): ?>
   <tr class='infoSubSection row1'>
     <td colspan="2"><?php echo $this->_tpl_vars['Profit']['Notes']; ?>
</td>
   </tr>
   <?php endif; ?>
 </table>