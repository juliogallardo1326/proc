<?php /* Smarty version 2.6.9, created on 2006-08-01 15:24:58
         compiled from int_orderapproval.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'int_orderapproval.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "lang/eng/language.conf",'section' => 'OrderPage'), $this);?>


<?php if ($this->_tpl_vars['mt_language'] != 'eng'):  echo smarty_function_config_load(array('file' => "lang/".($this->_tpl_vars['mt_language'])."/language.conf",'section' => 'OrderPage'), $this); endif; ?>

<form method="<?php echo $this->_tpl_vars['form_get_post']; ?>
" action="<?php echo $this->_tpl_vars['str_returnurl']; ?>
" name="MyForm">
<table width="100%" cellspacing="0">
  <tr>
    <td align="center"><b><?php if ($this->_tpl_vars['cond_istest']):  echo $this->_config[0]['vars']['OP_TestModeMessage'];  else:  echo $this->_config[0]['vars']['OP_LiveModeMessage'];  echo $this->_tpl_vars['str_emailaddress'];  endif; ?></b></td>
  </tr>
  <tr>
    <td align="center"><input type="submit" name="Submit" value="<?php echo $this->_config[0]['vars']['GL_Continue']; ?>
"></td>
  </tr>
</table>
<?php echo $this->_tpl_vars['str_posted_variables']; ?>

</form>