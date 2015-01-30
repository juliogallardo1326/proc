<?php /* Smarty version 2.6.2, created on 2006-10-26 22:15:58
         compiled from notifications/closed.tpl.text */ ?>
This is an automated message sent at your request from <?php echo $this->_tpl_vars['app_title']; ?>
.

This issue was just closed by <?php echo $this->_tpl_vars['data']['closer_name'];  if ($this->_tpl_vars['data']['reason'] != ''): ?>
 with the message: <?php echo $this->_tpl_vars['data']['reason'];  endif; ?>.

To view more details of this issue, or to update it, please visit the
following URL:
<?php echo $this->_tpl_vars['app_base_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['data']['iss_id']; ?>


----------------------------------------------------------------------
               ID: <?php echo $this->_tpl_vars['data']['iss_id']; ?>

          Summary: <?php echo $this->_tpl_vars['data']['iss_summary']; ?>

           Status: <?php echo $this->_tpl_vars['data']['sta_title']; ?>

          Project: <?php echo $this->_tpl_vars['data']['prj_title']; ?>

      Reported By: <?php echo $this->_tpl_vars['data']['usr_full_name']; ?>

         Priority: <?php echo $this->_tpl_vars['data']['pri_title']; ?>

      Description:
----------------------------------------------------------------------
<?php echo $this->_tpl_vars['data']['iss_description']; ?>

----------------------------------------------------------------------

