<?php /* Smarty version 2.6.2, created on 2006-10-22 17:50:09
         compiled from notifications/new_user.tpl.text */ ?>
A new user was just created for you in the system.

To start using the system, please load the URL below:
<?php echo $this->_tpl_vars['app_base_url']; ?>
index.php

----------------------------------------------------------------------
        Full Name: <?php echo $this->_tpl_vars['user']['usr_full_name']; ?>

    Email Address: <?php echo $this->_tpl_vars['user']['usr_email']; ?>
 
         Password: <?php echo $this->_tpl_vars['user']['usr_password']; ?>

Assigned Projects: <?php if (isset($this->_foreach['project'])) unset($this->_foreach['project']);
$this->_foreach['project']['name'] = 'project';
$this->_foreach['project']['total'] = count($_from = (array)$this->_tpl_vars['user']['projects']);
$this->_foreach['project']['show'] = $this->_foreach['project']['total'] > 0;
if ($this->_foreach['project']['show']):
$this->_foreach['project']['iteration'] = 0;
    foreach ($_from as $this->_tpl_vars['project']):
        $this->_foreach['project']['iteration']++;
        $this->_foreach['project']['first'] = ($this->_foreach['project']['iteration'] == 1);
        $this->_foreach['project']['last']  = ($this->_foreach['project']['iteration'] == $this->_foreach['project']['total']);
 if (! $this->_foreach['project']['first']): ?>                   <?php endif;  echo $this->_tpl_vars['project']['prj_title']; ?>
: <?php echo $this->_tpl_vars['project']['role']; ?>

<?php endforeach; unset($_from); endif; ?>
----------------------------------------------------------------------