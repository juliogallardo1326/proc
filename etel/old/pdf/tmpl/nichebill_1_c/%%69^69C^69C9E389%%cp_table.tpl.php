<?php /* Smarty version 2.6.9, created on 2007-07-03 14:27:42
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
<form id="<?php echo $this->_tpl_vars['formName']; ?>
" name="<?php echo $this->_tpl_vars['formName']; ?>
" action="<?php echo $this->_tpl_vars['redir']; ?>
" onSubmit="return validateForm(this);" method="<?php echo $this->_tpl_vars['method']; ?>
" enctype="<?php echo $this->_tpl_vars['enctype']; ?>
">
<table width="550" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="titlelarge"><u><?php echo $this->_tpl_vars['header']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
              </tr>
              <tr>
                <td><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
img/spacer.gif" width="500" height="8"></td>
              </tr>
              <tr>
                <td align="center">
                  <table width="550" border="0" cellpadding="0" cellspacing="0">
				   <tr>                  
			        <td colspan="2">                     
                      <tr align="center" valign="middle" height="15"> 
                        <td style="height:15;" align="left" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/table_corners_01_1x2.gif"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
img/table_corners_01_1x1.gif"></td>
                        <td style="height:15;" align="right" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/table_corners_01_1x2.gif"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
img/table_corners_01_1x4.gif"></td>
                    </tr>      
                      <tr align="center" valign="middle"> 
                        <td colspan="2"> <?php echo $this->_tpl_vars['message']; ?>
</td>
                    </tr>
					
					<?php if ($this->_tpl_vars['isback'] || $this->_tpl_vars['issubmit']): ?>
<tr><td height="50" colspan="4" align="center" valign="center">
<br>
<input type="image" 
<?php if ($this->_tpl_vars['isback']): ?>
src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/back.jpg"
<?php endif; ?>
<?php if ($this->_tpl_vars['issubmit']): ?>
src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/submit.jpg"
<?php endif; ?>
></input>
</td></tr>
<?php endif; ?>
					            
<?php if ($this->_tpl_vars['footer_message']): ?><tr style=" font-size:8; text-align:right; "><td colspan="2"><?php echo $this->_tpl_vars['footer_message']; ?>
</td></tr><?php endif; ?>
                      <tr align="center" valign="middle" height="15"> 
                        <td style="height:15;" align="left" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/table_corners_01_1x2.gif"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
img/table_corners_02_1x1.gif"></td>
                        <td style="height:15;" align="right" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/table_corners_01_1x2.gif"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
img/table_corners_02_1x4.gif"></td>
                    </tr>        
                  </table></td>
              </tr>
            </table>
			</form>


<script language="javascript">
setupForm(document.getElementById('<?php echo $this->_tpl_vars['formName']; ?>
'));
</script>