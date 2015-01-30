<?php /* Smarty version 2.6.9, created on 2007-03-28 15:20:36
         compiled from cp_calendar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'cp_calendar.tpl', 5, false),array('function', 'html_options', 'cp_calendar.tpl', 12, false),)), $this); ?>
<table class='invoice' width='700px'>
<?php if ($this->_tpl_vars['Calendar']['Notes']): ?>
  <tr >
    <td colspan="7">
	  <?php echo ((is_array($_tmp=$this->_tpl_vars['Calendar']['Notes'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

    </td>
  </tr>
<?php endif; ?>
  <tr >
    <td colspan="7"><select name='SelectMonth' onchange="this.form.submit()">
        
<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['DateOptions']['Values'],'output' => $this->_tpl_vars['DateOptions']['Names'],'selected' => $this->_tpl_vars['DateOptions']['Selected']), $this);?>


      </select>
		<label class="rowhighlight" style="padding:4px;"><?php echo $this->_tpl_vars['Calendar']['PayDayInfo']['Schedule']; ?>
 &nbsp;</label>
    </td>
  </tr>
  <tr class='infoHeader'>
    <td style="width:100px">Sunday</td>
    <td style="width:100px">Monday</td>
    <td style="width:100px">Tuesday</td>
    <td style="width:100px">Wednesday</td>
    <td style="width:100px">Thursday</td>
    <td style="width:100px">Friday</td>
    <td style="width:100px">Saturday</td>
  </tr>
 <?php $_from = $this->_tpl_vars['Calendar']['Week']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['Week']):
?>
  <tr class='infoSubSection row2' style="height:60px;vertical-align:top;"> 
   <?php $_from = $this->_tpl_vars['Week']['Day']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['Day']):
?>
    <td <?php if ($this->_tpl_vars['Day']['CurMonth']):  if ($this->_tpl_vars['Day']['PayDay']): ?>class="rowhighlight"<?php else: ?>class="row1"<?php endif;  else: ?>style="color:#888888"<?php endif; ?> onclick="document.location.href='#<?php echo $this->_tpl_vars['Day']['Date']; ?>
'">
	<div class="<?php if ($this->_tpl_vars['Day']['CurMonth']): ?>row0<?php endif; ?>" align="left"><?php echo $this->_tpl_vars['Day']['Num']; ?>
</div>
      <?php echo $this->_tpl_vars['Day']['Text']; ?>
 
	</td>
   <?php endforeach; endif; unset($_from); ?> 
  </tr>
 <?php endforeach; endif; unset($_from); ?>
</table>