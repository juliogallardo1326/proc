<?php /* Smarty version 2.6.2, created on 2006-10-30 12:10:31
         compiled from confirm.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />
<br />

<table width="400" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <?php if ($this->_tpl_vars['confirm_result'] != 1): ?>
        <tr>
          <td><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/icons/error.gif" hspace="2" vspace="2" border="0" align="left"></td>
          <td width="100%" class="default">
            <span style="font-weight: bold; font-size: 160%; color: red;"><?php if ($_GET['cat'] == 'password'): ?>Password Confirmation<?php else: ?>Account Creation<?php endif; ?> Error:</span>
          </td>
        </tr>
        <?php else: ?>
        <tr>
          <td colspan="2" width="100%" class="default"><span style="font-weight: bold; font-size: 160%; color: blue;">Password Confirmation Success!</span></td>
        </tr>
        <?php endif; ?>
        <tr>
          <td colspan="2" class="default">
            <br />
            <b>
            <?php if ($this->_tpl_vars['confirm_result'] == -1): ?>
            The provided trial account email address could not be 
            confirmed. Please contact the local Technical Support staff for 
            further assistance.
            <?php elseif ($this->_tpl_vars['confirm_result'] == -2): ?>
            The provided trial account email address could not be
            found. Please contact the local Technical Support staff for
            further assistance.
            <?php elseif ($this->_tpl_vars['confirm_result'] == -3): ?>
            The provided trial account encrypted hash could not be 
            authenticated. Please contact the local Technical
            Support staff for further assistance.
            <?php elseif ($this->_tpl_vars['confirm_result'] == 1): ?>
            Thank you, your request for a new password was confirmed successfully. You should receive an email with your new password shortly.<br /><br />
            <center><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
index.php?email=<?php echo $this->_tpl_vars['email']; ?>
" class="link">Back to Login Form</a></center>
            <?php endif; ?>
            </b>
            <br />
            <br />
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>