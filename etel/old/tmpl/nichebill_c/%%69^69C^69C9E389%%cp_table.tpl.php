<?php /* Smarty version 2.6.9, created on 2007-04-19 18:34:31
         compiled from cp_table.tpl */ ?>
<?php if ($this->_tpl_vars['printable']): ?>
<table border="1">
<tr><td><?php echo $this->_tpl_vars['header']; ?>
</td></tr>
<tr><td><?php echo $this->_tpl_vars['message']; ?>
</td></tr>
</table>

<?php else: ?>

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
<form id="<?php echo $this->_tpl_vars['formName']; ?>
" name="<?php echo $this->_tpl_vars['formName']; ?>
" action="<?php echo $this->_tpl_vars['redir']; ?>
" onSubmit="return validateForm(this);" method="<?php echo $this->_tpl_vars['method']; ?>
" enctype="<?php echo $this->_tpl_vars['enctype']; ?>
">
<table border="0" cellpadding="0" cellspacing="0" class="GeneralTable">

<tr>
<td class="T1x1"></td>
<td class="T1x2"><?php echo $this->_tpl_vars['header']; ?>
</td>
<td class="T1x3"></td>
</tr>

<tr>
<td class="T2x1"></td>
<td class="T2x2">
<?php echo $this->_tpl_vars['message']; ?>


<?php if ($this->_tpl_vars['isback'] || $this->_tpl_vars['issubmit']): ?>
<br>
<div style="text-align:center; width:100%">
<input type="image" 
<?php if ($this->_tpl_vars['isback']): ?>
src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/table/back.png"
<?php endif; ?>
<?php if ($this->_tpl_vars['issubmit']): ?>
src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/table/submit.png"
<?php endif; ?>
></input>
</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['footer_message']): ?><div style=" font-size:8; text-align:right; width:100%; "><?php echo $this->_tpl_vars['footer_message']; ?>
</div><?php endif; ?>

</td>
<td class="T2x3"></td>
</tr>

<tr>
<td class="T3x1"></td>
<td class="T3x2"></td>
<td class="T3x3"></td>
</tr>

</table>
</form>
<script language="javascript">
setupForm(document.getElementById('<?php echo $this->_tpl_vars['formName']; ?>
'));
</script>

<?php endif; ?>