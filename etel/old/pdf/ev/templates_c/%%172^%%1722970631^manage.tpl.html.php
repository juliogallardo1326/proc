<?php /* Smarty version 2.6.2, created on 2006-10-19 14:41:56
         compiled from manage/manage.tpl.html */ ?>

<?php if ($this->_tpl_vars['show_not_allowed_msg']): ?>
<center>
<span class="default">
<b>Sorry, but you do not have the required permission level to access this screen.</b>
<br /><br />
<a class="link" href="javascript:history.go(-1);">Go Back</a>
</span>
</center>
<?php else: ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top">
      <table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr>
          <td width="100%">
            <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="4">
              <?php if ($this->_tpl_vars['show_setup_links']): ?>
              <tr>
                <td>
                  <span class="default"><b>Configuration:</b></span>
                </td>
              </tr>
              <tr>
                <td nowrap class="default">
                  <ul>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/general.php" class="link">General Setup</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/email_accounts.php" class="link">Manage Email Accounts</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/custom_fields.php" class="link">Manage Custom Fields</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/customize_listing.php" class="link">Customize Issue Listing Screen</a></li>
                  </ul>
                </td>
              </tr>
              <?php endif; ?>
              <tr>
                <td>
                  <span class="default"><b>Areas:</b></span>
                </td>
              </tr>
              <tr>
                <td nowrap class="default">
                  <ul>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/faq.php" class="link">Manage Internal FAQ</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/round_robin.php" class="link">Manage Round Robin Assignments</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/news.php" class="link">Manage News</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/reminders.php" class="link">Manage Issue Reminders</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/account_managers.php" class="link">Manage Customer Account Managers</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/customer_notes.php" class="link">Manage Customer Quick Notes</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/statuses.php" class="link">Manage Statuses</a></li>
                    <li>
                      <a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/projects.php" class="link">Manage Projects</a>
                      <ul>
                        <li>Add / Edit Releases</li>
                        <li>Add / Edit Categories</li>
                        <li>Add / Edit Priorities</li>
                        <li>Add / Edit Phone Support Categories</li>
                        <li>Anonymous Reporting Options</li>
                        <li>Edit Fields to Display</li>
                        <li>Edit Columns to Display</li>
                      </ul>
                    </li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/users.php" class="link">Manage Users</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/groups.php" class="link">Manage Groups</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/time_tracking.php" class="link">Manage Time Tracking Categories</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/resolution.php" class="link">Manage Issue Resolutions</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/email_responses.php" class="link">Manage Canned Email Responses</a></li>
                    <li><a href="<?php echo $this->_tpl_vars['rel_url']; ?>
manage/link_filters.php" class="link">Manage Link Filters</a></li>
                  </ul>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
    <td>
      &nbsp;
    </td>
    <td width="100%" valign="top">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "manage/".($this->_tpl_vars['type']).".tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </td>
  </tr>
</table>
<?php endif; ?>
