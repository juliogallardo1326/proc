<?php /* Smarty version 2.6.9, created on 2005-12-09 02:45:29
         compiled from int_entry.tpl */ ?>
<!--Main-->

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60%" bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
    <td align="right" bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="20" colspan="2" align="right" valign="bottom"><?php echo $this->_config[0]['vars']['GL_Language']; ?>
: </td>
  </tr>
  <tr>
    <td colspan="2" align="right"><form id="frm_language" action="" method="post">
        <?php echo $this->_tpl_vars['str_posted_variables']; ?>

        <select name="mt_language" id="mt_language" onChange="document.getElementById('frm_language').submit()">
          <option value="eng">English</option>
          <option value="spa">Spanish</option>
          <option value="fre">French</option>
          <option value="ger">German</option>
          <option value="ita">Italian</option>
          <option value="por">Portuguese</option>
          <option value="kor">Korean</option>
        </select>
        <script language="javascript">
	document.getElementById('mt_language').value = '<?php echo $this->_tpl_vars['mt_language']; ?>
';
	 </script>
    </form></td>
  </tr>
  <form name="Frmname" action="<?php echo $this->_tpl_vars['rootdir']; ?>
/secure/PaymentProcessing.php" method="post">
  <tr>
    <td height="20" class="fieldname" align="left" style="font-size:24px; font-weight:bold"><?php echo $this->_config[0]['vars']['GL_Step']; ?>
 #1</td>
  </tr>
  <tr>
    <td align="center" class="fieldname" style="font-size:16px; font-weight:bold"><br>
      <?php echo $this->_config[0]['vars']['OP_PaymentType']; ?>
:</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
		  <p>
          <input name="ProcessingMode" id="ProcessingMode" value="" type="hidden">
          <input name="mt_language" value="<?php echo $this->_tpl_vars['mt_language']; ?>
" type="hidden">
		  
        <?php if ($this->_tpl_vars['cd_orderpage_useracount']): ?>
</p>
		  <p><?php echo $this->_config[0]['vars']['OP_UserAccount_Msg']; ?>
 <a target="_blank" href="<?php echo $this->_tpl_vars['AccountSignupPage']; ?>
"><?php echo $this->_config[0]['vars']['GL_SignUp']; ?>
</a></p>
		  <p>
          <input name="Submit" value="<?php echo $this->_config[0]['vars']['OP_UserAccount']; ?>
" type="button" onClick="document.getElementById('ProcessingMode').value='UserAccount';this.form.submit();" style="font-size:24px; font-weight:bold">
        </p>
		  <p><?php echo $this->_config[0]['vars']['OP_NotUserAccount_Msg']; ?>
 </p>
		  <p><?php endif; ?>
		  <?php if ($this->_tpl_vars['cs_creditcards']): ?>
	      </p>
		  <p>
          <input name="Submit" value="<?php echo $this->_config[0]['vars']['OP_CreditCardTitle']; ?>
" type="button" onClick="document.getElementById('ProcessingMode').value='Credit';this.form.submit();" style="font-size:24px; font-weight:bold">
        </p>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['cs_echeck']): ?>
        <p>
          <input name="Submit" value="<?php echo $this->_config[0]['vars']['OP_CheckTitle']; ?>
" type="button" onClick="document.getElementById('ProcessingMode').value='Check';this.form.submit();" style="font-size:24px; font-weight:bold">
          <br>
        <?php echo $this->_config[0]['vars']['OP_US_CANADA']; ?>
</p>
        <?php endif;  if ($this->_tpl_vars['cs_web900']): ?>
        <p>
          <input name="Submit" value="<?php echo $this->_config[0]['vars']['OP_Web900Title']; ?>
" type="button" onClick="document.getElementById('ProcessingMode').value='Web900';this.form.submit();" style="font-size:24px; font-weight:bold">
          <br>
          <?php echo $this->_config[0]['vars']['OP_US_ONLY']; ?>

        </p>
	  <?php endif; ?></td>
  </tr>
  </form>
</table>
<!--End Main-->