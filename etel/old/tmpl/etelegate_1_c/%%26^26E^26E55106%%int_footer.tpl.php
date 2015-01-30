<?php /* Smarty version 2.6.9, created on 2006-06-20 02:47:14
         compiled from int_footer.tpl */ ?>
<!--Footer-->
<div align="center" style="display:none; height:300;" id="processMessage"><br>
  <br>
  <br>
  <br>
  <img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/transactionWait.gif"></div>
<table  width="650"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60%" bgcolor="#009999"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" alt="sp" width="20" height="4"></td>
    <td align="right" bgcolor="#009999"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" alt="sp" width="20" height="4"></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="style1">
  <?php if ($this->_tpl_vars['ShowSubmitButton']): ?>
  <?php if ($this->_tpl_vars['gkard_support']): ?>
  <tr>
    <td height="20" align="center"><?php echo $this->_config[0]['vars']['OP_GkardUsed']; ?>
</td>
  </tr>
  <?php endif; ?>
  <tr>
    <td height="20" align="center"><?php echo $this->_config[0]['vars']['OP_PleaseSubmitOnce']; ?>
</td>
  </tr>
  <tr>
    <td width="100%" align="center" height="23" valign="center"><input type="image" name="add" id="formSubmit" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/submit.jpg" onClick="submitOrder(document.getElementById('processingFrm'))">
      </input>
    </td>
  </tr>
  <?php endif; ?>
  <tr>
    <td align="center"><?php echo $this->_tpl_vars['custom_text']; ?>
<BR></td>
  </tr>
  <tr>
    <td align="center"><?php echo $this->_config[0]['vars']['OP_CSMessage']; ?>
</td>
  </tr>
  <tr>
    <td align="center" style="color:#00CCFF; font-size:10px;"><br />
      Red Door International Marketing Ing., Unit 110 Alpha Bldg. Subic International Hotel Rizal cor. Sta. Rita Road, Subic Bay Freeport<BR />
	Olongapo City Philippines 2200</td>
  </tr>
</table>

</td>
</tr>
</table>
<!--End Footer-->