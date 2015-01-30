<?php /* Smarty version 2.6.2, created on 2006-10-19 16:47:26
         compiled from lookup_field.tpl.html */ ?>

<input class="lookup_field" name="<?php echo $this->_tpl_vars['lookup_field_name']; ?>
" type="text" size="24" tabindex="<?php echo $this->_tpl_vars['tabindex']; ?>
"
    value="paste or start typing here" 
    onBlur="javascript:this.value='paste or start typing here';" 
    onFocus="javascript:this.value='';" 
    onKeyUp="javascript:lookupField(this.form, this, '<?php echo $this->_tpl_vars['lookup_field_target']; ?>
', <?php if ($this->_tpl_vars['callbacks'] != ""):  echo $this->_tpl_vars['callbacks'];  else: ?>null<?php endif; ?>);">