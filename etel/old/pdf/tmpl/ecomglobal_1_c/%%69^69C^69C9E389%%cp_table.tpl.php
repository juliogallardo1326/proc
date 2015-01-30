<?php /* Smarty version 2.6.9, created on 2005-06-17 14:28:18
         compiled from cp_table.tpl */ ?>
<script language="javascript">
var redir = '<?php echo $this->_tpl_vars['redir']; ?>
';
<?php echo '
function submitform()
{


	if (redir != \'\') document.getElementById(\'msgform\').submit();
	else window.history.back();
}
'; ?>

</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
<td width="83%" valign="top" align="center">&nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="50%" >
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
<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5" style="border-style:groove; border-width:2" >
<?php if ($this->_tpl_vars['redir']): ?><form id="msgform" action="<?php echo $this->_tpl_vars['redir']; ?>
" onSubmit="submitform()" method="post"><?php endif; ?>
<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
<table width='400' border='0' cellpadding='0' >
<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php echo $this->_tpl_vars['message']; ?>
</font>
</td></tr></table>
</td></tr>
<tr><td height="50" valign="center" align="center">
<br>
<input type="image" 
<?php if ($this->_tpl_vars['isback']): ?>
src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/back.jpg"
<?php else: ?>
src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/submit.jpg"
<?php endif; ?>
></input>

</td></tr></table>
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
</td></tr>
</table>
</td></tr>
</table>
