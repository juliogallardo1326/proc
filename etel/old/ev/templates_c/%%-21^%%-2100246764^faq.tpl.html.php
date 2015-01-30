<?php /* Smarty version 2.6.2, created on 2006-10-19 16:55:05
         compiled from faq.tpl.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'faq.tpl.html', 13, false),array('function', 'cycle', 'faq.tpl.html', 65, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array('extra_title' => 'Internal FAQ')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['faq'] == ''):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "navigation.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['faq'] != ''): ?>
  <?php if ($this->_tpl_vars['faq'] == -1): ?>
  <span class="default"><p><b>Error: You are not allowed to view the requested FAQ entry.</b></p></span>
  <?php else: ?>
  <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
    <tr>
      <td width="100%">
        <table width="100%" border="0" cellpadding="3" cellspacing="0">
          <tr>
            <td class="default_white" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['faq']['faq_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
          </tr>
          <tr>
            <td class="default" bgcolor="<?php echo $this->_tpl_vars['dark_color']; ?>
">
              <p><i>Last updated: <?php echo $this->_tpl_vars['faq']['faq_updated_date']; ?>
</i></p>
              <?php echo $this->_tpl_vars['faq']['message']; ?>

            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <?php endif; ?> 
  <?php if (! $this->_tpl_vars['current_user_prefs']['close_popup_windows']): ?>
  <center>
    <span class="default"><a class="link" href="javascript:void(null);" onClick="javascript:window.close();">Close Window</a></span>
  </center>
  <?php endif;  else: ?>
<table width="700" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top">
      <?php echo '
      <script language="JavaScript">
      <!--
      function openFAQEntry(faq_id)
      {
          var features = \'width=740,height=580,top=30,left=30,resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no\';
          var faqWin = window.open(\'faq.php?id=\' + faq_id, \'_faq\' + faq_id, features);
          faqWin.focus();
      }
      //-->
      </script>
      '; ?>

      <table width="100%" bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr>
          <td width="100%">
            <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="4">
              <tr>
                <td>
                  <span class="default"><b>Article Entries:</b></span>
                </td>
              </tr>
              <tr>
                <td>
                  <table width="100%" border="0" cellpadding="3" cellspacing="1">
                    <tr bgcolor="<?php echo $this->_tpl_vars['cell_color']; ?>
">
                      <td class="default_white"><b>Title</b></td>
                      <td class="default_white"><b>Last Updated Date</b></td>
                    </tr>
                    <?php if (isset($this->_sections['i'])) unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['faqs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
                    <?php echo smarty_function_cycle(array('values' => $this->_tpl_vars['cycle'],'assign' => 'row_color'), $this);?>

                    <tr bgcolor="<?php echo $this->_tpl_vars['row_color']; ?>
">
                      <td class="default"><b><a class="link" href="javascript:void(null);" onClick="javascript:openFAQEntry(<?php echo $this->_tpl_vars['faqs'][$this->_sections['i']['index']]['faq_id']; ?>
);" title="read faq entry"><?php echo $this->_tpl_vars['faqs'][$this->_sections['i']['index']]['faq_title']; ?>
</a></b></td>
                      <td class="default"><?php echo $this->_tpl_vars['faqs'][$this->_sections['i']['index']]['faq_updated_date']; ?>
</td>
                    </tr>
                    <?php endfor; endif; ?>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <br />
    </td>
  </tr>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['faq'] == ''):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "app_info.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>