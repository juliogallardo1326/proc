<?php /* Smarty version 2.6.2, created on 2006-10-20 02:38:30
         compiled from update.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "navigation.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['issue'] == ""): ?>
  <table width="300" align="center">
    <tr>
      <td>
        &nbsp;<span class="default"><b>Error: The issue could not be found.</b>
        <br /><br />
        &nbsp;<a class="link" href="javascript:history.go(-1);">Go Back</a></span>
      </td>
    </tr>
  </table>
<?php elseif ($this->_tpl_vars['auth_customer'] == 'denied'): ?>
  <table width="500" align="center">
    <tr>
      <td>
        &nbsp;<span class="default"><b>Sorry, you do not have the required privileges to view this issue.</b>
        <br /><br />
        &nbsp;<a class="link" href="javascript:history.go(-1);">Go Back</a></span>
      </td>
    </tr>
  </table>
<?php else: ?>
  <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['reporter']): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "update_form.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php else: ?>
  <center>
<span class="default">
<b>Sorry, but you do not have the required permission level to access this screen.</b>
<br /><br />
<a class="link" href="javascript:history.go(-1);">Go Back</a>
</span>
</center>
  <?php endif;  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "app_info.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>