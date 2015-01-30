<?php /* Smarty version 2.6.9, created on 2007-07-03 14:28:38
         compiled from cp_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', 'cp_header.tpl', 21, false),)), $this); ?>

<html><head>
<title><?php echo $this->_tpl_vars['gateway_title'];  echo $this->_tpl_vars['page_title']; ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php if ($this->_tpl_vars['autorefresh']): ?><meta content="<?php echo $this->_tpl_vars['autorefresh']; ?>
" http-equiv="refresh"><?php endif; ?>

<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/style.css" type="text/css" rel="stylesheet">
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/text.css" type="text/css" rel="stylesheet">
<link rel="icon" href="<?php echo $this->_tpl_vars['tempdir']; ?>
images/favicon.ico" />
<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['tempdir']; ?>
images/favicon.ico" />
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/formvalid.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/general.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/prototype.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--

var rootdir = '<?php echo $this->_tpl_vars['rootdir']; ?>
';
var tempdir = '<?php echo $this->_tpl_vars['tempdir']; ?>
';
var etel_full_name = '<?php echo $this->_tpl_vars['etel_full_name']; ?>
';
var etel_debug_mode = <?php echo ((is_array($_tmp=$this->_tpl_vars['etel_debug_mode'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
;

<?php echo '

function is_in_frame()
{
	return (window.self != window.top);
}

if(is_in_frame()) window.top.location.href=\'https://www.maturebill.com\';

function updateClock()
{
	if(document.getElementById(\'showcurtime\')) document.getElementById(\'showcurtime\').innerHTML = getCurDateTime();
}
setInterval("updateClock();",1000);

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

//-->
</script>
'; ?>

</head>
<body bgcolor="#CCCCCC" text="#000066" link="#006699" vlink="#006699" alink="#006699" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="766" height="128" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="3" bgcolor="#000033">&nbsp;</td>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="101" height="128" rowspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
img/index_01.png" width="101" height="128"></td>
          <td height="41" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/index_02.jpg">&nbsp;</td>
        </tr>
        <tr> 
          <td height="87" style="background-image:url(<?php echo $this->_tpl_vars['tempdir']; ?>
img/Image1.png); background-repeat:no-repeat"></td>
        </tr>
      </table> </td>
    <td width="3" bgcolor="#000033">&nbsp;</td>
  </tr>
  <tr> 
    <td width="3" bgcolor="#000033">&nbsp;</td>
    <td align="center" valign="top" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/index_30.gif"> 
      <table width="760" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="158" valign="top"> 
            <table height="225" width="158" border="0" cellspacing="0" cellpadding="0"  background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/Left1.gif">
<?php $this->assign('cnt', '16');  $_from = $this->_tpl_vars['main_header']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
 $this->assign('cnt', ($this->_tpl_vars['cnt']+1)); ?>
              <tr> 
                <td height="25" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/index_<?php echo $this->_tpl_vars['cnt']; ?>
.jpg" class="navigation"><a href="<?php echo $this->_tpl_vars['link']['href']; ?>
" class="a1"><font color="#000066"><?php echo $this->_tpl_vars['link']['text']; ?>
</font></a></td>
              </tr>
<?php endforeach; endif; unset($_from); ?>              
<tr> 
            <td height="25">&nbsp;</td>
              </tr>
              <tr> 
                <td height="25">&nbsp;</td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
            </table></td>
			
          <td width="602" align="center" valign="top" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/index_17.gif">
		  		  <table border="0" cellpadding="0" cellspacing="0" height="10" style="border-bottom-width:1; ">
		<?php if ($this->_tpl_vars['sub_header']['links']): ?>
		<tr>
		<?php $_from = $this->_tpl_vars['sub_header']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
			<td height="20" width="150" class="<?php if ($this->_tpl_vars['link']['selected'] == 1): ?>rgtselected<?php else: ?>blackrgt<?php endif; ?>" valign="middle" align="center" background="<?php echo $this->_tpl_vars['tempdir']; ?>
img/index_16b.jpg">
			<?php if ($this->_tpl_vars['link']['disabled'] != 1): ?><a href="<?php echo $this->_tpl_vars['link']['href']; ?>
" class="maintx"><?php echo $this->_tpl_vars['link']['text']; ?>
</a>
			<?php else: ?><span class="maindisabled" ><?php echo $this->_tpl_vars['link']['text']; ?>
</span><?php endif; ?>
			</td>
		<?php endforeach; endif; unset($_from); ?>
		</tr>
		<?php endif; ?>
		</table>
		<!--submenu ends-->
		
		<?php if ($this->_tpl_vars['display_todo_list']): ?>
		<div align="center">
            <table width="80%"  border="1" cellspacing="2" cellpadding="2">
      <tr>
        <th scope="col" class="todoListHeader">ToDo List:</th>
      </tr>
      <tr>
        <th scope="col" class="todoList" align="left"><PRE><?php echo $this->_tpl_vars['display_todo_list']; ?>
</PRE></th>
      </tr>
            </table>
	  </div>
            <?php else: ?>
			  <?php if ($this->_tpl_vars['display_stat_wait']): ?> 
		<div id="hidewait" align="center"><br><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/stats_wait.gif" width="355" height="33"></div>
			<?php endif; ?>
		<?php endif; ?>