<?php /* Smarty version 2.6.9, created on 2006-10-20 02:54:51
         compiled from cp_header.tpl */ ?>

<html><head>
<title>:: Payment Gateway ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php if ($this->_tpl_vars['autorefresh']): ?><meta content="<?php echo $this->_tpl_vars['autorefresh']; ?>
" http-equiv="refresh"><?php endif; ?>

<link rel="icon" href="<?php echo $this->_tpl_vars['tempdir']; ?>
images/favicon.ico" />
<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['tempdir']; ?>
images/favicon.ico" />
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/style.css" type="text/css" rel="stylesheet">
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/text.css" type="text/css" rel="stylesheet">
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/formvalid.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/general.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--


<?php if ($this->_tpl_vars['autorefresh']): ?> setTimeout("window.location.reload();",<?php echo $this->_tpl_vars['autorefresh']*1000; ?>
);<?php endif; ?>
<?php echo '

function is_in_frame()
{
	return (window.self != window.top);
}

if(is_in_frame()) window.top.location.href=\'https://www.etelegate.com\';

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
<body leftmargin="0" topmargin="0" marginwidth="0"  marginheight="0">
<table align="center" border="1" bordercolor="#cccccc" cellpadding="0" cellspacing="0" width="778">
  <tr>
    <td width="782"> 
      <table cellpadding="0" cellspacing="0" width="778">
        <tr> 
          <td colspan="2" width="100%"><table cellpadding="0" cellspacing="0" width="100%">
              <tr> 
                <td width="44%"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/index_01.gif" height="72" width="339"></td>
                <td align="right" valign="middle"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/Control_Panel.jpg" height="45" width="278"><strong><br>
                  Call us 24/7 at 1-800-676-0127 <br>
                  <span style="font-size:12px;">or Call us Direct at 213-291-9051</span></strong></td>
              </tr>
              
            </tbody></table></td>
        </tr>
		<?php if ($this->_tpl_vars['allowautorefresh']): ?><form action="" id="frm_autorefresh" method="post"><?php echo $this->_tpl_vars['etel_postback_____']; ?>
<input value="<?php if ($this->_tpl_vars['autorefresh']): ?>-1<?php else: ?>1<?php endif; ?>" name="autorefresh" type="hidden"><?php endif; ?>
        <tr height="20"> 
          <td width="950" align="left" valign="bottom" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/cp_08.gif" >
			<label class="refresh" id="showcurtime">Getting Date/Time...</label>		  
		  </td>
          <td width="950" align="right" valign="bottom" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/cp_08.gif" >
		  <?php if ($this->_tpl_vars['allowautorefresh']): ?><a class="refresh" href="javascript:document.getElementById('frm_autorefresh').submit()" onClick="">Turn Auto Refresh <?php if ($this->_tpl_vars['autorefresh']): ?>Off<?php else: ?>On<?php endif; ?> </a><?php endif; ?>
		  </td>
        </tr>
		<?php if ($this->_tpl_vars['allowautorefresh']): ?></form><?php endif; ?>
        <tr> 
          <td colspan="2"><table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="100%">
              <tr> 
                <td bgcolor="#f2f2f2" valign="top" width="19%"> 
				<?php if ($this->_tpl_vars['main_header']['links']): ?>
                  <table bgcolor="#f2f2f2" border="0" width="100%">
                    
<?php $_from = $this->_tpl_vars['main_header']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
                      <tr> 
                        <td>&nbsp;</td>
                        <td><a href="<?php echo $this->_tpl_vars['link']['href']; ?>
" class="a1"><?php echo $this->_tpl_vars['link']['text']; ?>
</a></td>
                      </tr>
<?php endforeach; endif; unset($_from); ?>
                    </tbody>
                  </table>
				<?php endif; ?>
                </td>
                <td width="81%" valign="top">


		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/menubtmbg.gif"><img alt="" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#f2f2f2" height="25" class="blackbd" valign="middle" align="center">
		  <table border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<?php $_from = $this->_tpl_vars['sub_header']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
			<td height="20" width="150" class="<?php if ($this->_tpl_vars['link']['selected'] == 1): ?>rgtselected<?php else: ?>blackrgt<?php endif; ?>" valign="middle" align="center">
			<?php if ($this->_tpl_vars['link']['disabled'] != 1): ?><a href="<?php echo $this->_tpl_vars['link']['href']; ?>
" class="maintx"><?php echo $this->_tpl_vars['link']['text']; ?>
</a>
			<?php else: ?><span class="maindisabled" ><?php echo $this->_tpl_vars['link']['text']; ?>
</span><?php endif; ?>
			</td>
		<?php endforeach; endif; unset($_from); ?>
		</tr>
		</table>
		</td>
		</tr>
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