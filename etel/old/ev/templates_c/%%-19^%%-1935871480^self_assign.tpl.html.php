<?php /* Smarty version 2.6.2, created on 2006-10-23 12:51:06
         compiled from self_assign.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'self_assign.tpl.html', 36, false),array('modifier', 'join', 'self_assign.tpl.html', 36, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('extra_title' => $this->_tpl_vars['extra_title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['self_assign_result']): ?>
  <br />
  <center>
  <span class="default">
  <?php if ($this->_tpl_vars['self_assign_result'] == -1): ?>
    <b>An error occurred while trying to run your query</b>
  <?php elseif ($this->_tpl_vars['self_assign_result'] == 1): ?>
    <b>Thank you, the issue #<?php echo $this->_tpl_vars['issue_id']; ?>
 has been updated.</b>
  <?php endif; ?>  </span>
  </center>
  <script language="JavaScript">
  <!--
  <?php if ($this->_tpl_vars['current_user_prefs']['close_popup_windows'] == '1'): ?>
  setTimeout('closeAndRefresh()', 2000);
  <?php endif; ?>
  //-->
  </script>
  <br />
  <?php if (! $this->_tpl_vars['current_user_prefs']['close_popup_windows']): ?>
  <center>
    <span class="default"><a class="link" href="javascript:void(null);" onClick="javascript:closeAndRefresh();">Continue</a></span>
  </center>
  <?php endif;  else: ?>
<form name="assign_form" method="post" action="self_assign.php">
<input type="hidden" name="iss_id" value="<?php echo $this->_tpl_vars['issue_id']; ?>
">
<table align="center" width="100%" cellpadding="3">
  <tr>
    <td>
      <table width="100%" cellspacing="0" cellpadding="2" border="0">
        <tr>
          <td colspan="2" class="default" style="font-weight: bold; color: red;" align="center">
            <span style="font-size: 140%">WARNING</span><br />
            The following user<?php if (count($this->_tpl_vars['assigned_user']) > 0): ?>s are<?php else: ?> is<?php endif; ?> already assigned to this issue.<br /><?php echo ((is_array($_tmp=",")) ? $this->_run_mod_handler('join', true, $_tmp, $this->_tpl_vars['assigned_users']) : join($_tmp, $this->_tpl_vars['assigned_users'])); ?>

          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" align="right">
            <input type="radio" name="target" value="replace" checked>
          </td>
          <td class="default" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
">
            <b><a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('assign_form', 'target', 0);">
            Replace current assignee<?php if (count($this->_tpl_vars['assigned_user']) > 0): ?>s<?php endif; ?> with Myself.</a></b>
          </td>
        </tr>
        <tr>
          <td bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" align="right" valign="top">
            <input type="radio" name="target" value="add">
          </td>
          <td bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
" class="default">
            <b><a id="link" class="link" href="javascript:void(null);" onClick="javascript:checkRadio('assign_form', 'target', 1);">
            Add Myself to list of assignees.</a></b>
          </td>
        </tr>
        <tr>
          <td colspan="2" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" align="right">
            <input type="submit" value="Continue &gt;&gt;" class="button">
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>