
<html><head>
<title>{$gateway_title}{$page_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
{if $autorefresh}<meta content="{$autorefresh}" http-equiv="refresh">{/if}

<link href="{$tempdir}styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="{$tempdir}styles/style.css" type="text/css" rel="stylesheet">
<link href="{$tempdir}styles/text.css" type="text/css" rel="stylesheet">
<link rel="icon" href="{$tempdir}images/favicon.ico" />
<link rel="shortcut icon" href="{$tempdir}images/favicon.ico" />
<script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
<script language="javascript" src="{$rootdir}/scripts/general.js"></script>
<script language="javascript" src="{$rootdir}/scripts/prototype.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--

var rootdir = '{$rootdir}';
var tempdir = '{$tempdir}';
var etel_full_name = '{$etel_full_name}';
var etel_debug_mode = {$etel_debug_mode|intval};

{literal}

function is_in_frame()
{
	return (window.self != window.top);
}

if(is_in_frame()) window.top.location.href='https://www.maturebill.com';

function updateClock()
{
	if(document.getElementById('showcurtime')) document.getElementById('showcurtime').innerHTML = getCurDateTime();
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
{/literal}
</head>
<body bgcolor="#CCCCCC" text="#000066" link="#006699" vlink="#006699" alink="#006699" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="766" height="128" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="3" bgcolor="#000033">&nbsp;</td>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="101" height="128" rowspan="3"><img src="{$tempdir}img/index_01.png" width="101" height="128"></td>
          <td height="41" background="{$tempdir}img/index_02.jpg">&nbsp;</td>
        </tr>
        <tr> 
          <td height="87" style="background-image:url({$tempdir}img/Image1.png); background-repeat:no-repeat"></td>
        </tr>
      </table> </td>
    <td width="3" bgcolor="#000033">&nbsp;</td>
  </tr>
  <tr> 
    <td width="3" bgcolor="#000033">&nbsp;</td>
    <td align="center" valign="top" background="{$tempdir}img/index_30.gif"> 
      <table width="760" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="158" valign="top"> 
            <table height="225" width="158" border="0" cellspacing="0" cellpadding="0"  background="{$tempdir}img/Left1.gif">
{assign var="cnt" value="16"}
{foreach from=$main_header.links item=link}
{assign var="cnt" value="`$cnt+1`"}
              <tr> 
                <td height="25" background="{$tempdir}img/index_{$cnt}.jpg" class="navigation"><a href="{$link.href}" class="a1"><font color="#000066">{$link.text}</font></a></td>
              </tr>
{/foreach}              
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
			
          <td width="602" align="center" valign="top" background="{$tempdir}img/index_17.gif">
		  		  <table border="0" cellpadding="0" cellspacing="0" height="10" style="border-bottom-width:1; ">
		{if $sub_header.links}
		<tr>
		{foreach from=$sub_header.links item=link}
			<td height="20" width="150" class="{if $link.selected==1}rgtselected{else}blackrgt{/if}" valign="middle" align="center" background="{$tempdir}img/index_16b.jpg">
			{if $link.disabled!=1}<a href="{$link.href}" class="maintx">{$link.text}</a>
			{else}<span class="maindisabled" >{$link.text}</span>{/if}
			</td>
		{/foreach}
		</tr>
		{/if}
		</table>
		<!--submenu ends-->
		
		{if $display_todo_list}
		<div align="center">
            <table width="80%"  border="1" cellspacing="2" cellpadding="2">
      <tr>
        <th scope="col" class="todoListHeader">ToDo List:</th>
      </tr>
      <tr>
        <th scope="col" class="todoList" align="left"><PRE>{$display_todo_list}</PRE></th>
      </tr>
            </table>
	  </div>
            {else}
			  {if $display_stat_wait} 
		<div id="hidewait" align="center"><br><img src="{$tempdir}images/stats_wait.gif" width="355" height="33"></div>
			{/if}
		{/if}