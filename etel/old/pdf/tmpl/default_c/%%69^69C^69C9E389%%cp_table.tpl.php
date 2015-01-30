<?php /* Smarty version 2.6.9, created on 2006-03-05 21:15:46
         compiled from cp_table.tpl */ ?>
<script language="javascript">
var redir = '<?php echo $this->_tpl_vars['redir']; ?>
';
function submitform()
	<?php echo '
	{
	'; ?>

	
	
		if (redir != '') document.getElementById('<?php echo $this->_tpl_vars['formName']; ?>
').submit();
		else window.history.back();
	<?php echo '
	}
	'; ?>

</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" >
<tr>
<td width="83%" valign="top" align="center">&nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="<?php echo $this->_tpl_vars['width']; ?>
" >
<tr>
<td height="22" align="left" valign="top" width="1%" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menucenterbg.gif" nowrap><img border="0" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menucenterbg.gif" ><span class="whitehd"><?php echo $this->_tpl_vars['header']; ?>
</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menutoprightbg.gif" ><img alt="" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5" style="border-style:groove; border-width:2;" >
<form id="<?php echo $this->_tpl_vars['formName']; ?>
" name="<?php echo $this->_tpl_vars['formName']; ?>
" action="<?php echo $this->_tpl_vars['redir']; ?>
" onSubmit="return validateForm(this);" method="post" enctype="multipart/form-data">
<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
<table border='0' cellpadding='0' width="100%" >
<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php echo $this->_tpl_vars['message']; ?>
</font>
</td></tr></table>
</td></tr>
<?php if ($this->_tpl_vars['isback'] || $this->_tpl_vars['issubmit']): ?>
<tr><td height="50" valign="center" align="center">
<br>
<input type="image" 
<?php if ($this->_tpl_vars['isback']): ?>
src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/back.jpg"
<?php endif;  if ($this->_tpl_vars['issubmit']): ?>
src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/submit.jpg"
<?php endif; ?>
></input>

</td></tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['footer_message']): ?><tr style=" font-size:8; text-align:right; "><td><?php echo $this->_tpl_vars['footer_message']; ?>
</td></tr><?php endif; ?>
</table>

<tr>
<td width="1%"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menubtmcenter.gif"><img border="0" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menubtmright.gif"></td>
</tr>
</form>
<script language="javascript">
setupForm(document.getElementById('<?php echo $this->_tpl_vars['formName']; ?>
'));
</script>
</td>
</tr>
</table>
</td></tr>
</table>
