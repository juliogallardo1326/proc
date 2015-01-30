<?php /* Smarty version 2.6.2, created on 2007-06-01 13:37:30
         compiled from navigation.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'navigation.tpl.html', 8, false),array('modifier', 'escape', 'navigation.tpl.html', 45, false),array('function', 'html_options', 'navigation.tpl.html', 83, false),)), $this); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
        <tr>
          <td class="default_white">
          <b><?php echo ((is_array($_tmp=@$this->_tpl_vars['app_setup']['tool_caption'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['application_title']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['application_title'])); ?>
</b></td>
          <td align="right" class="default_white">
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['developer']): ?>
			(<a title="logout from <?php echo ((is_array($_tmp=@$this->_tpl_vars['app_setup']['tool_caption'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['application_title']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['application_title'])); ?>
" href="<?php echo $this->_tpl_vars['rel_url']; ?>
logout.php" class="white_link">Logout</a>)            
			<a title="manage the application settings, users, projects, etc" href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/projects.php" class="white_link">Administration</a>&nbsp;|
            <?php endif; ?>
            <?php if ($this->_tpl_vars['current_role'] >= $this->_tpl_vars['roles']['reporter']): ?>
            <a href="<?php echo $this->_tpl_vars['rel_url']; ?>
new.php" title="create a new issue" class="white_link"><strong>Create Issue</strong></a>&nbsp;|
            <?php endif; ?>
            <a href="<?php echo $this->_tpl_vars['rel_url']; ?>
list.php" title="list the issues stored in the system" class="white_link"><strong>List Issues</strong></a>&nbsp;|
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
            <a title="get access to advanced search parameters" href="<?php echo $this->_tpl_vars['rel_url']; ?>
adv_search.php" class="white_link">Advanced Search</a>&nbsp;|
            <?php endif; ?>
            <?php if ($this->_tpl_vars['app_setup']['support_email'] == 'enabled' && $this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
            <a title="list available emails" href="<?php echo $this->_tpl_vars['rel_url']; ?>
emails.php" class="white_link">Associate Emails</a>&nbsp;|
            <?php endif; ?>
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['customer']): ?>
            <a title="list all issues assigned to you" href="<?php echo $this->_tpl_vars['rel_url']; ?>
list.php?view=my_assignments" class="white_link">My Assignments</a>&nbsp;|
            <?php endif; ?>
            <a title="general statistics" href="<?php echo $this->_tpl_vars['rel_url']; ?>
main.php" class="white_link">Stats</a>&nbsp;|
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['developer']): ?>
            <a title="reporting system" href="<?php echo $this->_tpl_vars['rel_url']; ?>
reports/index.php" class="white_link">Reports</a>&nbsp;|
            <?php endif; ?>
            <a title="internal faq" href="<?php echo $this->_tpl_vars['rel_url']; ?>
faq.php" class="white_link"> FAQ</a>&nbsp;|
            <?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['developer']): ?>
            <a title="help" href="<?php echo $this->_tpl_vars['rel_url']; ?>
help.php" class="white_link"> Help</a>&nbsp;
			<?php endif; ?>         </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
        <tr>
          <?php if ($this->_tpl_vars['current_role'] == $this->_tpl_vars['roles']['customer']): ?>
          <td nowrap width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <b>Project: <?php echo ((is_array($_tmp=$this->_tpl_vars['current_project_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b>
          </td>
          <?php else: ?>
          <?php echo '
          <script language="JavaScript">
          <!--
          function setCurrentProject()
          {
              var features = \'width=420,height=200,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
              var projWin = window.open(\'\', \'_active_project\', features);
              projWin.focus();
              return true;
          }
          function validateIssueID(f)
          {
              f.id.value = f.id.value.replace(/[^\\d]/g, \'\');
              if (isNumberOnly(f.id.value)) {
                  return true;
              } else {
                  selectField(f, \'id\');
                  alert(\'Please enter a valid issue ID.\');
                  return false;
              }
          }
          function changeClockStatus()
          {
          '; ?>

              var features = 'width=420,height=200,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no';
              var clockWin = window.open('<?php echo $this->_tpl_vars['rel_url']; ?>
clock_status.php', '_clock_status', features);
              clockWin.focus();
          <?php echo '
          }
          //-->
          </script>
          '; ?>

          <form onSubmit="javascript:return setCurrentProject();" target="_active_project" method="post" action="<?php echo $this->_tpl_vars['rel_url']; ?>
switch.php<?php if ($this->_tpl_vars['is_frame'] == 'yes'): ?>?is_frame=yes<?php endif; ?>">
          <td nowrap width="50%" bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <select name="current_project" class="shortcut">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['active_projects'],'selected' => $this->_tpl_vars['current_project']), $this);?>

            </select>
            <input type="submit" class="shortcut" value="Switch">
          </td>
          </form>
          <?php endif; ?>
          <td width="50%" nowrap bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" class="default">
            <b><?php echo $this->_tpl_vars['current_role_name']; ?>
: <?php echo $this->_tpl_vars['current_full_name'];  if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['standard_user']): ?> [CLOCKED <?php if ($this->_tpl_vars['is_current_user_clocked_in']): ?>IN<?php else: ?>OUT<?php endif; ?>]<?php endif; ?></b>
            (<a title="modify your account details and preferences" href="<?php echo $this->_tpl_vars['rel_url']; ?>
preferences.php" class="link">Preferences</a><?php if ($this->_tpl_vars['current_role'] > $this->_tpl_vars['roles']['standard_user']): ?>
            <a title="change your account clocked-in status" href="javascript:void(null);" onClick="javascript:changeClockStatus();" class="link">Clock <?php if ($this->_tpl_vars['is_current_user_clocked_in']): ?>Out<?php else: ?>In<?php endif; ?></a><?php endif; ?>)
          </td>
          <form method="get" action="<?php echo $this->_tpl_vars['rel_url']; ?>
list.php">
          <td width="5%" nowrap bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
">
            <?php if ($this->_tpl_vars['current_role'] != $this->_tpl_vars['roles']['customer']): ?>
            <label for="search" accesskey="3"></label>
            <input type="text" id="search" name="keywords" value="keywords" size="15"
              onBlur="javascript:if (this.value == '') this.value = 'keywords';" onFocus="javascript:if (this.value == 'keywords') this.value='';" class="shortcut">
            <input type="submit" value="Search" class="shortcut">
            <?php endif; ?>
          </td>
          </form>
          <form onSubmit="javascript:return validateIssueID(this);" method="get" action="<?php echo $this->_tpl_vars['rel_url']; ?>
view.php">
          <td width="2%" nowrap bgcolor="<?php echo $this->_tpl_vars['light_color']; ?>
" align="right">
            <label for="shortcut" accesskey="4"></label>
            <input type="text" id="shortcut" name="id" value="id #"
              onBlur="javascript:if (this.value == '') this.value = 'id #';" onFocus="javascript:if (this.value == 'id #') this.value='';" size="5" class="shortcut">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "error_icon.tpl.html", 'smarty_include_vars' => array('field' => 'id')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <input type="submit" value="Go" class="shortcut">
          </td>
          </form>
        </tr>
      </table>
    </td>
  </tr>
</table>

<?php if ($this->_tpl_vars['show_line'] != 'no'): ?>
<hr size="1" noshade color="<?php echo $this->_tpl_vars['cell_color']; ?>
">
<?php endif; ?>