<?php /* Smarty version 2.6.2, created on 2007-01-19 14:30:03
         compiled from clock_status.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />
<center>
  <span class="default">
  <?php if ($this->_tpl_vars['result'] == 1): ?>
  <b>Thank you, your account clocked-in status was changed successfully.</b>
  <?php elseif ($this->_tpl_vars['result'] == -1): ?>
  <b>An error was found while trying to change your account clocked-in status.</b>
  <?php endif; ?>
  </span>
</center>

<script language="JavaScript">
<!--
<?php if ($_GET['is_frame'] == 'yes'): ?>
var url = opener.parent.location.href;
<?php else: ?>
var url = opener.location.href;
<?php endif; ?>

var email_list_page = '/emails.php';
<?php echo '
if (url.indexOf(email_list_page + \'?\') != -1) {
    url = url.substring(0, url.indexOf(email_list_page + \'?\') + email_list_page.length);
}
'; ?>


var list_page = '/list.php';
<?php echo '
if (url.indexOf(list_page + \'?\') != -1) {
    url = url.substring(0, url.indexOf(list_page + \'?\') + list_page.length);
}
'; ?>


<?php if ($_GET['is_frame'] == 'yes'): ?>
opener.parent.location.href = url;
<?php else: ?>
opener.location.href = url;
<?php endif; ?>
setTimeout('window.close()', 2000);
//-->
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>