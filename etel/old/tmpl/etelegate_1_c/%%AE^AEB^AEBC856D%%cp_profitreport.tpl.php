<?php /* Smarty version 2.6.9, created on 2006-12-05 09:30:09
         compiled from cp_profitreport.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'formatMoney', 'cp_profitreport.tpl', 3, false),array('modifier', 'intval', 'cp_profitreport.tpl', 3, false),array('function', 'cycle', 'cp_profitreport.tpl', 7, false),)), $this); ?>
<table class='invoice' width='100%'>
   <tr class='infoHeader'>
     <td>Revenue</td><td>$<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Revenue']['Total']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Revenue']['Total']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</td>
   </tr>
   <?php $_from = $this->_tpl_vars['Profit']['Revenue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['type']):
?>	
   <?php if ($this->_tpl_vars['key'] != 'Total'): ?>   
   <tr class='infoSubSection row<?php echo smarty_function_cycle(array('values' => "1,2"), $this);?>
'>
	 <td><?php echo $this->_tpl_vars['key']; ?>
</td> <td>$<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</td>
   </tr>
   <?php endif; ?>   
   <?php endforeach; endif; unset($_from); ?>
   <tr class='infoHeader'>
     <td>Deductions</td><td>$<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Deductions']['Total']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Deductions']['Total']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</td>
   </tr>
   <?php $_from = $this->_tpl_vars['Profit']['Deductions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['type']):
?>
   <?php if ($this->_tpl_vars['key'] != 'Total'): ?>   	   
   <tr class='infoSubSection row<?php echo smarty_function_cycle(array('values' => "1,2"), $this);?>
'>
	 <td><?php echo $this->_tpl_vars['key']; ?>
</td> <td>$<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['type']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</td>
   </tr>
   <?php endif; ?>   
   <?php endforeach; endif; unset($_from); ?>
   <tr class='infoHeader'>
     <td>Total Profit </td>
     <td>$<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Total']['Amount'])) ? $this->_run_mod_handler('formatMoney', true, $_tmp) : formatMoney($_tmp)); ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['Profit']['Total']['Count'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
)</td>
   </tr>
 </table>