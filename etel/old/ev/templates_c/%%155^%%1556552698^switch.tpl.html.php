<?php /* Smarty version 2.6.2, created on 2006-10-19 17:28:21
         compiled from switch.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />
<center>
  <span class="default">
  <b>Thank you, your current selected project was changed successfully.</b>
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


<?php echo '
if (url.indexOf(\'view.php\' + \'?\') != -1 || url.indexOf(\'update.php\' + \'?\') != -1) {
    url = \'main.php\';
}
'; ?>


function refreshParent()
{
    <?php if ($_GET['is_frame'] == 'yes'): ?>
    opener.parent.location.href = url;
    <?php else: ?>
    opener.location.href = url;
    <?php endif; ?>
    window.close();
}

<?php if ($this->_tpl_vars['current_user_prefs']['close_popup_windows'] == '1'): ?>
setTimeout('refreshParent()', 2000);
<?php endif; ?>
//-->
</script>
<br />
<?php if (! $this->_tpl_vars['current_user_prefs']['close_popup_windows']): ?>
<center>
  <span class="default"><a class="link" href="javascript:void(null);" onClick="javascript:refreshParent();">Continue</a></span>
</center>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>