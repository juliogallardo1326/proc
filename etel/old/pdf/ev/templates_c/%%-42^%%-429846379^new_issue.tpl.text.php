<?php /* Smarty version 2.6.2, created on 2006-10-22 03:01:19
         compiled from notifications/new_issue.tpl.text */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'notifications/new_issue.tpl.text', 27, false),)), $this); ?>
This is an automated message sent at your request from <?php echo $this->_tpl_vars['app_title']; ?>
.

A new issue was just created in the system.

To view more details of this issue, or to update it, please visit the 
following URL:
<?php echo $this->_tpl_vars['app_base_url']; ?>
view.php?id=<?php echo $this->_tpl_vars['data']['iss_id']; ?>


----------------------------------------------------------------------
                ID: <?php echo $this->_tpl_vars['data']['iss_id']; ?>

           Summary: <?php echo $this->_tpl_vars['data']['iss_summary']; ?>
 
           Project: <?php echo $this->_tpl_vars['data']['prj_title']; ?>
 
       Reported By: <?php echo $this->_tpl_vars['data']['reporter']; ?>

        Assignment: <?php echo $this->_tpl_vars['data']['assignments']; ?>

          Priority: <?php echo $this->_tpl_vars['data']['pri_title']; ?>

       Description:
----------------------------------------------------------------------
<?php echo $this->_tpl_vars['data']['iss_original_description']; ?>

----------------------------------------------------------------------

Issue Details
----------------------------------------------------------------------
<?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['data']['custom_fields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
 echo $this->_tpl_vars['data']['custom_fields'][$this->_sections['i']['index']]['fld_title']; ?>
: <?php echo $this->_tpl_vars['data']['custom_fields'][$this->_sections['i']['index']]['icf_value']; ?>

<?php endfor; endif; ?>
----------------------------------------------------------------------
<?php if (count($this->_tpl_vars['data']['attachments']) > 0): ?>

Attachments
----------------------------------------------------------------------
<?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['data']['attachments']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
      Files: <?php echo $this->_tpl_vars['data']['attachments'][$this->_sections['i']['index']]['files'][0]['iaf_filename']; ?>

<?php if (isset($this->_sections['files'])) unset($this->_sections['files']);
$this->_sections['files']['name'] = 'files';
$this->_sections['files']['loop'] = is_array($_loop=$this->_tpl_vars['data']['attachments'][$this->_sections['i']['index']]['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['files']['start'] = (int)1;
$this->_sections['files']['show'] = true;
$this->_sections['files']['max'] = $this->_sections['files']['loop'];
$this->_sections['files']['step'] = 1;
if ($this->_sections['files']['start'] < 0)
    $this->_sections['files']['start'] = max($this->_sections['files']['step'] > 0 ? 0 : -1, $this->_sections['files']['loop'] + $this->_sections['files']['start']);
else
    $this->_sections['files']['start'] = min($this->_sections['files']['start'], $this->_sections['files']['step'] > 0 ? $this->_sections['files']['loop'] : $this->_sections['files']['loop']-1);
if ($this->_sections['files']['show']) {
    $this->_sections['files']['total'] = min(ceil(($this->_sections['files']['step'] > 0 ? $this->_sections['files']['loop'] - $this->_sections['files']['start'] : $this->_sections['files']['start']+1)/abs($this->_sections['files']['step'])), $this->_sections['files']['max']);
    if ($this->_sections['files']['total'] == 0)
        $this->_sections['files']['show'] = false;
} else
    $this->_sections['files']['total'] = 0;
if ($this->_sections['files']['show']):

            for ($this->_sections['files']['index'] = $this->_sections['files']['start'], $this->_sections['files']['iteration'] = 1;
                 $this->_sections['files']['iteration'] <= $this->_sections['files']['total'];
                 $this->_sections['files']['index'] += $this->_sections['files']['step'], $this->_sections['files']['iteration']++):
$this->_sections['files']['rownum'] = $this->_sections['files']['iteration'];
$this->_sections['files']['index_prev'] = $this->_sections['files']['index'] - $this->_sections['files']['step'];
$this->_sections['files']['index_next'] = $this->_sections['files']['index'] + $this->_sections['files']['step'];
$this->_sections['files']['first']      = ($this->_sections['files']['iteration'] == 1);
$this->_sections['files']['last']       = ($this->_sections['files']['iteration'] == $this->_sections['files']['total']);
?>
             <?php echo $this->_tpl_vars['data']['attachments'][$this->_sections['i']['index']]['files'][$this->_sections['files']['index']]['iaf_filename']; ?>

<?php endfor; endif; ?>
Description: <?php echo $this->_tpl_vars['data']['attachments'][$this->_sections['i']['index']]['iat_description']; ?>

----------------------------------------------------------------------
<?php endfor; endif;  endif; ?>

Please Note: If you do not wish to receive any future email 
notifications from <?php echo $this->_tpl_vars['app_title']; ?>
, please change your account preferences by 
visiting the URL below:
<?php echo $this->_tpl_vars['app_base_url']; ?>
preferences.php
