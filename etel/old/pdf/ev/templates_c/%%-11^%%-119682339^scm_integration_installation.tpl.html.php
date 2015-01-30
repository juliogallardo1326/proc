<?php /* Smarty version 2.6.2, created on 2006-10-20 00:49:37
         compiled from help/scm_integration_installation.tpl.html */ ?>

<h4>Installation Instructions</h4>
<span class="default">
The process_commits.pl script, which is available in the misc 
sub-directory in your Eventum installation directory, will need to be 
installed in your CVSROOT CVS module by following the procedure below:
<br /><br />
The first thing to do is to checkout the CVSROOT module from your CVS
repository:
<br /><br />
<b>$ cvs -d %CVS REPOSITORY HERE% checkout CVSROOT</b>
<br /><br />
The command above will checkout and create the CVSROOT directory that
you will need to work with. Next, open the <b>loginfo</b> file and
add the following line:
<br /><br />
<?php echo '
<b>ALL /usr/local/bin/php -q %repository path%/CVSROOT/process_cvs_commits.php $USER %{sVv}</b>
'; ?>

<br /><br />
Replace %repository path% by the appropriate absolute path in your
CVS server, such as /home/username/repository for instance. Also make
sure to put the appropriate path to your Perl binary.
<br /><br />
You may also turn the parsing of commit messages for just a single CVS
module by substituting the 'ALL' in the line above to the appropriate
CVS module name, as in:
<br /><br />
<?php echo '
<b>%cvs module name% /usr/local/bin/php -q %repository path%/CVSROOT/process_cvs_commits.php $USER %{sVv}</b>
'; ?>

<br /><br />
The last step of this installation process is to login into the CVS
server and copy the process_cvs_commits.php script into the CVSROOT 
directory. Make sure you give the appropriate permissions to the 
script.
<br /><br />
</span>