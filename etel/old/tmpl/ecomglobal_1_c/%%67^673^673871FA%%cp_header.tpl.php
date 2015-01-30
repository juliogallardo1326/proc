<?php /* Smarty version 2.6.9, created on 2005-06-14 20:52:57
         compiled from cp_header.tpl */ ?>

<html>
<head>
<title>:: Payment Gateway ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/style.css" type="text/css" rel="stylesheet">
<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/text.css" type="text/css" rel="stylesheet">
<?php echo '
<script language="JavaScript" type="text/JavaScript">
<!--

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

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
self.setInterval(\'show_time()\', 1000);
function show_time() {
	var time_per
	var x = new Array("Sunday", "Monday", "Tuesday");
  	var x = x.concat("Wednesday","Thursday", "Friday");
  	var x = x.concat("Saturday");
	var z = new Array("January","February","March","April","May");
  	var z = z.concat("June","July","August","September");
  	var z = z.concat("October","November","December");
	var today_datetime = new Date();
	var today_month = z[today_datetime.getMonth()];
	var today_date = today_datetime.getDate();
	var today_day  = x[today_datetime.getDay()];
	var today_year = today_datetime.getYear();
	if (today_year < 110) today_year += 1900;
	var today_hours = today_datetime.getHours();
	var today_mins = today_datetime.getMinutes();
	var today_secs = today_datetime.getSeconds();
	if(today_secs < 10) {
		today_secs = "0"+ today_secs ;
	}
	if(today_mins < 10){
		today_mins ="0"+ today_mins;
	}
	if(today_hours >= 12) {
		time_per ="PM";
	} else {
		time_per ="AM";
	}
	if(today_hours > 12){
		today_hours = today_hours - 12;
	}
	if(today_hours < 10) {
		today_hours ="0"+ today_hours;
	}
	if(today_hours == "00") {
		today_hours = "12";
	}
	
	var mytime = today_month +" "+ today_date +", "+ today_year +" "+ today_day +" "+ today_hours +":"+ today_mins +":"+ today_secs+" "+ time_per;
	// document.form_timer.curr_time.value=mytime;
// document.form_timer.curr_time.value=mytime;
	//time.innerHTML = "<font style=\'font-face:verdana;font-weight:bold;Color:#448A99;\'>"+mytime+"</font>";
}
//-->
</script>
'; ?>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0"  marginheight="0" onLoad="MM_preloadImages('../images/bug1.gif')">
<!--header-->

<table border="0" cellpadding="0" cellspacing="0" width="100%" height="125" class="bdbtm">
<tr>
<td valign="top" align="left" bgcolor="#ffffff" width="35%">&nbsp;<img alt='' border='0' src='<?php echo $this->_tpl_vars['tempdir']; ?>
images/ecomlogo.gif'></td>
<td bgcolor="#FFFFFF" width="65%" valign="top" align="right" nowrap>

</td>
</tr>
</table>

</font></td></tr></table>
</div>

<!--header ends here-->
<!--top menu-->
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="27
" bgcolor="#FFFFFF">
  <td background="<?php echo $this->_tpl_vars['tempdir']; ?>
images/midbg.gif" align="center">
<img alt="" border="0" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/break.gif" height="27">
<?php $_from = $this->_tpl_vars['main_header']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
<a href="<?php echo $this->_tpl_vars['rootdir'];  echo $this->_tpl_vars['link']['href']; ?>
">
<img border="0" src="<?php echo $this->_tpl_vars['tempdir'];  echo $this->_tpl_vars['link']['img']; ?>
"  height="27">
</a>
<img border="0" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/break.gif"  height="27">
<?php endforeach; endif; unset($_from); ?>
</td>
</tr> 
</table>
<!--topmenu ends-->

		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="images/menubtmbg.gif"><img alt="" src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="80%" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<?php $_from = $this->_tpl_vars['sub_header']['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
			<td height="20" class="blackrgt" valign="middle" align="center"><a href="<?php echo $this->_tpl_vars['link']['href']; ?>
" class="maintx"><?php echo $this->_tpl_vars['link']['text']; ?>
</a></td>
		<?php endforeach; endif; unset($_from); ?>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
		<?php if ($this->_tpl_vars['display_stat_wait']): ?>
<div id="hidewait" align="center"><br><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/stats_wait.gif" width="355" height="33"></div>
		<?php endif; ?>