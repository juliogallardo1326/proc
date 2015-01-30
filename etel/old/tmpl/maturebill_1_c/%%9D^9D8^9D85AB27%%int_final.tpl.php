<?php /* Smarty version 2.6.9, created on 2005-12-28 10:41:39
         compiled from int_final.tpl */ ?>
  <?php if ($this->_tpl_vars['gkard_used']): ?>
		<table width="100%" height="200"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td class="normaltext" valign="middle" height="50"><?php echo $this->_config[0]['vars']['OP_CardNumber']; ?>
</td>
      <td height="50" valign="middle" class="normaltext"><?php echo $this->_tpl_vars['td_gkard']; ?>
</td>
      <td rowspan="3" valign="middle" class="normaltext"><a href="https://www.MatureBill.com"><img src='<?php echo $this->_tpl_vars['tempdir']; ?>
images/gkard_credit.gif' alt='' border='0'></a></td>
    </tr>
    <tr>
      <td height="50" valign="middle" class="normaltext"><?php echo $this->_config[0]['vars']['OP_GKardNumber']; ?>
</td>
      <td height="50" valign="middle" class="normaltext"><?php echo $this->_tpl_vars['td_ccNumber']; ?>
</td>
    </tr>
    <tr>
      <td height="100" colspan="2" valign="middle" class="normaltext"><?php echo $this->_config[0]['vars']['OP_GkardReUse']; ?>
</td>
      </tr>
</table>
  <?php endif; ?>
<form name="Frmname" action="<?php echo $this->_tpl_vars['Return_Url']; ?>
" method="post">
		  <div align="center">
		  <table width="600" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">
                <strong><?php if ($this->_tpl_vars['livemode']):  echo $this->_config[0]['vars']['OP_LiveModeMessage'];  echo $this->_tpl_vars['Email'];  else:  echo $this->_config[0]['vars']['OP_TestModeMessage'];  endif; ?></strong> </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
			<?php if (0): ?>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2"><?php echo $this->_config[0]['vars']['OP_RedirectingTo']; ?>
 <a href="javascript:Frmname.submit()"><?php echo $this->_tpl_vars['Return_Url']; ?>
</a> ... <label id="timer">10</label>&nbsp;</td>
            </tr>
 		    <?php endif; ?>
            <tr>
              <td>&nbsp;</td>
              <td></td>
              <td><input type="submit" name="Submit" value="<?php echo $this->_config[0]['vars']['GL_Continue']; ?>
"></td>
            </tr>
          </table>
		<?php echo $this->_tpl_vars['PostedVariables']; ?>

          </div>
</form>
		<script language="JavaScript">
		<?php if ($this->_tpl_vars['OrderPageSettings'] == 'autoforward'): ?>
		document.Frmname.submit();
		//time = 3;
		//document.getElementById('timer').firstChild.nodeValue = time;
		//setInterval("if(time>0) time--;document.getElementById('timer').firstChild.nodeValue = time+' seconds'",1000);
		//setTimeout("document.Frmname.submit()", time*1000);
		<?php endif; ?>
		</script>
<!--End Main-->