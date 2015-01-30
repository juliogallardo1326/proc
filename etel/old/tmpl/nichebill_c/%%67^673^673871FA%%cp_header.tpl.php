<?php /* Smarty version 2.6.9, created on 2007-04-28 18:08:16
         compiled from cp_header.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $this->_tpl_vars['gateway_title']; ?>
</title>
<meta http-equiv="Content-Type" content="text/php; charset=iso-8859-1">
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/style.css" rel="stylesheet" type="text/css">
</head>
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/formvalid.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['tempdir']; ?>
/scripts/prototype.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['tempdir']; ?>
/scripts/general.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['tempdir']; ?>
/scripts/src/scriptaculous.js"></script>
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/pngfix.js"></script>
<![endif]-->
<script language="javascript">

if(is_in_frame()) window.top.location.href='https://www.etelegate.com';
var rootdir = '<?php echo $this->_tpl_vars['rootdir']; ?>
';
var tempdir = '<?php echo $this->_tpl_vars['tempdir']; ?>
';
</script>
<body leftmargin="0" topmargin="0"  marginheight="0" marginwidth="0" class="BackEndPage">
    <!--Header End -->
	  <!-- Begin Main -->
	  
<?php if (! $this->_tpl_vars['hide_header']): ?>
<div align="left" style="height:100%;">
 <div class="Table">
   <div class="CallUsPhone">Live Support<br /> 1-800-123-4567</div>
   <div class="ControlPanel">Control Panel<?php if ($this->_tpl_vars['page_title']): ?> <br> <?php echo $this->_tpl_vars['page_title'];  endif; ?></div>
   <div class="Row1x1_ext">
     <div class="Row1x1">
	<?php $_from = $this->_tpl_vars['main_header']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?><div class="SubMenuButton"><a href="<?php echo $this->_tpl_vars['link']['href']; ?>
"><?php echo $this->_tpl_vars['link']['text']; ?>
</a></div> <?php endforeach; endif; unset($_from); ?>
	
   
     </div>
   </div>
   <table class="Row2x1" cellspacing="0" cellpadding="0">
   <tr><td class="Menu">
	 <?php $_from = $this->_tpl_vars['sub_header']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
	 <div class="MenuButton"><a href="<?php echo $this->_tpl_vars['link']['href']; ?>
"><?php echo $this->_tpl_vars['link']['text']; ?>
</a></div>
	 <?php endforeach; endif; unset($_from); ?>
   </td>
   <td class="MainContent">
	
	 <?php if ($this->_tpl_vars['display_todo_list']): ?>
	 <div align="center">
	  <table width="60%"  border="1" cellspacing="2" cellpadding="2">
	   <tr>
	    <th scope="col" class="todoListHeader">Information:</th>
	   </tr>
	   <tr>
	    <th scope="col" class="todoList" align="left"><PRE><?php echo $this->_tpl_vars['display_todo_list']; ?>
</PRE></th>
	   </tr>
	  </table>
	 </div>
	 <?php endif; ?>
			
<?php endif; ?>