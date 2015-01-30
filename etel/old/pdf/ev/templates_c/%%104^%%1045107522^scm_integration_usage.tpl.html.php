<?php /* Smarty version 2.6.2, created on 2006-10-25 15:28:33
         compiled from help/scm_integration_usage.tpl.html */ ?>

<h4>Usage Examples</h4>
<span class="default">
An integration script will need to be installed in your CVS root 
repository in order to send a message to Eventum whenever changes are
committed to the repository. This message will then be processed by
Eventum and the changes to the appropriate files will be associated
with existing issue mentioned in your commit message.
<br /><br />
So to examplify its use, whenever the users are ready to commit the
changes to the CVS repository, they will add a special string to
specify which issue this is related to. The following would be a
good example of its use:
<br /><br />
<b>[prompt]$ cvs -q commit -m "Adding form validation as requested (issue: 13)" form.php</b>
<br /><br />
You may also use 'bug' to specify the issue ID - whichever you are more
comfortable with.
<br /><br />
This command will be parsed by the CVS integration script (provided to
you and available in %eventum_path%/misc/scm/process_cvs_commits.php) and it
will notify Eventum that these changes are to be associated with issue
#13.
<br /><br />
</span>