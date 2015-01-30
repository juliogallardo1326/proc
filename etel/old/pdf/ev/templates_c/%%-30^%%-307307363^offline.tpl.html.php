<?php /* Smarty version 2.6.2, created on 2007-01-10 14:53:57
         compiled from offline.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />
<br />

<table width="400" bgcolor="#003366" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td>
      <table bgcolor="#FFFFFF" width="100%" cellspacing="1" cellpadding="2" border="0">
        <tr>
          <td><img src="<?php echo $this->_tpl_vars['rel_url']; ?>
images/icons/error.gif" hspace="2" vspace="2" border="0" align="left"></td>
          <td width="100%" class="default"><span style="font-weight: bold; font-size: 160%; color: red;">Database Error:</span></td>
        </tr>
        <tr>
          <td colspan="2" class="default">
            <br />
            <b>
            <?php if ($this->_tpl_vars['error_type'] == 'db'): ?>
            There seems to be a problem connecting to the database server
            specified in your configuration file. Please contact your
            local system administrator for further assistance.
            <?php elseif ($this->_tpl_vars['error_type'] == 'table'): ?>
            There seems to be a problem finding the required database
            tables in the database server specified in your
            configuration file. Please contact your local system 
            administrator for further assistance.
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