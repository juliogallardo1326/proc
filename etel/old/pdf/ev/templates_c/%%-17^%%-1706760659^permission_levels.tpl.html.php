<?php /* Smarty version 2.6.2, created on 2006-10-25 15:29:47
         compiled from help/permission_levels.tpl.html */ ?>

<h4>User Permission Levels</h4>
<span class="default">
The following is a brief overview of the available user permission levels 
in Eventum:
<br /><br />
<b>Viewer</b> - Allowed to view all issues on the projects associated to 
this user; cannot create new issues or edit existing issues.
<br /><br />
<b>Reporter</b> - Allowed to view all issues on the projects associated to 
this user; Allowed to create new issues and to send emails on existing
issues.
<br /><br />
<b>Customer</b> - This is a special permission level reserved for the Customer
Integration API, which allows you to integrate Eventum with your CRM database. 
When this feature is enabled, this type of user can only access issues associated
with their own customer. Allowed to create new issues, update and send emails
to existing issues.
<br /><br />
<b>Standard User</b> - Allowed to view all issues on the projects associated to
this user; Allowed to create new issues, update existing issues, and to send
emails and notes to existing issues.
<br /><br />
<b>Developer</b> - Similar in every way to the above permission level, but 
this extra level allows you to segregate users who will deal with issues, and
overall normal staff users who do not handle issues themselves.
<br /><br />
<b>Manager</b> - Allowed to view all issues on the projects associated to
this user; Allowed to create new issues, update existing issues, and to send
emails and notes to existing issues. Also, this type of user is also allowed on
the special administration section of Eventum to tweak most project-level 
features and options.
<br /><br />
<b>Administrator</b> - This type of user has full access to Eventum, including
the low level configuration parameters available through the administration
interface.
<br /><br />
</span>