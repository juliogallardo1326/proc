<?php /* Smarty version 2.6.2, created on 2006-10-23 00:17:03
         compiled from notifications/new_auto_created_issue.tpl.text */ ?>
<?php if ($this->_tpl_vars['sender_name']): ?>
Dear <?php echo $this->_tpl_vars['sender_name']; ?>
,

<?php endif; ?>
This is an automated message sent at your request from <?php echo $this->_tpl_vars['app_title']; ?>
.

We received a message from you and for your convenience, we created 
an issue that will be used by our staff to handle your message.

        Date: <?php echo $this->_tpl_vars['email']['date']; ?>

        From: <?php echo $this->_tpl_vars['email']['from']; ?>

     Subject: <?php echo $this->_tpl_vars['email']['subject']; ?>


<?php if ($this->_tpl_vars['sender_can_access'] == 1): ?>
To view more details of this issue, or to update it, please visit the 
following URL:
<?php echo $this->_tpl_vars['app_base_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['data']['iss_id']; ?>

<?php endif; ?>

     Issue #: <?php echo $this->_tpl_vars['data']['iss_id']; ?>

     Summary: <?php echo $this->_tpl_vars['data']['iss_summary']; ?>

    Priority: <?php echo $this->_tpl_vars['data']['pri_title']; ?>

   Submitted: <?php echo $this->_tpl_vars['data']['iss_created_date']; ?>


<?php if ($this->_tpl_vars['sender_can_access'] == 1): ?>
Please Note: If you do not wish to receive any future email 
notifications from <?php echo $this->_tpl_vars['app_title']; ?>
, please change your account preferences by 
visiting the URL below:
<?php echo $this->_tpl_vars['app_base_url']; ?>
preferences.php
<?php endif; ?>