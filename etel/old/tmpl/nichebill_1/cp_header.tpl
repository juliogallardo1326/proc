<html><head>
<title>:: Payment Gateway ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
{if $autorefresh}<meta content="{$autorefresh}" http-equiv="refresh">{/if}

<link href="style/index.css" type="text/css" rel="stylesheet">
<link rel="icon" href="images/favicon.ico" />
<link rel="shortcut icon" href="images/favicon.ico" />
<script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
<script language="javascript" src="{$rootdir}/scripts/general.js"></script>
<script language="javascript" src="{$rootdir}/scripts/prototype.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--

var rootdir = '{$rootdir}';
var tempdir = '';
var etel_full_name = '{$etel_full_name}';
var etel_debug_mode = {$etel_debug_mode|intval};

{literal}

function is_in_frame()
{
	return (window.self != window.top);
}

if(is_in_frame()) window.top.location.href='https://www.NicheBill.com';

function updateClock()
{
	if(document.getElementById('showcurtime')) document.getElementById('showcurtime').innerHTML = getCurDateTime();
}
setInterval("updateClock();",1000);

{/literal}
//-->
</script>
</head>
<body>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tbody><tr>
    <td><table align="center" border="0" cellpadding="0" cellspacing="0" width="969">
      <tbody><tr>
        <td valign="top" width="592"><img src="img/logo.jpg" alt="NicheBill" class="logo"></td>
      </tr>
      <tr>
        <td valign="bottom" width="592"><table align="right" border="0" cellpadding="0" cellspacing="0" width="80%">
        </table></td>
      </tr>
    </tbody></table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table border="0" cellpadding="0" cellspacing="0" width="80%">
      <tbody><tr>
        <td style="width: 159px;" valign="top"><table align="center" border="0" cellpadding="0" cellspacing="0" width="139">
          <tbody><tr>
            <td><img src="img/bluetop.gif" alt="" width="139" height="10"></td>
          </tr>
          <tr class="blueline1">
            <td><table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
                <tbody>
{foreach from=$main_header.links item=link}
                <tr>
                  <td><a href="{$link.href}" class="leftLink">{$link.text}</a></td>
                </tr>
                <tr>
                  <td class="dot"></td>
                </tr>
{/foreach}  
            </tbody></table></td>
          </tr>
          <tr>
            <td valign="top"><img src="img/bluebottom.gif" alt="" width="139" height="9"></td>
          </tr>
        </tbody></table></td>
        <td class="" style="padding-left: 10px;" valign="top">
{if 0}
<body bgcolor="#CCCCCC" text="#000066" link="#006699" vlink="#006699" alink="#006699" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="766" height="128" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="3" bgcolor="#000033">&nbsp;</td>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="101" height="128" rowspan="3"><img src="img/index_01.png" width="101" height="128"></td>
          <td height="41" background="img/index_02.jpg">&nbsp;</td>
        </tr>
        <tr> 
          <td height="87" style="background-image:url(img/Image1.png); background-repeat:no-repeat"></td>
        </tr>
      </table> </td>
    <td width="3" bgcolor="#000033">&nbsp;</td>
  </tr>
  <tr> 
    <td width="3" bgcolor="#000033">&nbsp;</td>
    <td align="center" valign="top" background="img/index_30.gif"> 
      <table width="760" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="158" valign="top"> 
            <table height="225" width="158" border="0" cellspacing="0" cellpadding="0"  background="img/Left1.gif">
{assign var="cnt" value="16"}
{foreach from=$main_header.links item=link}
{assign var="cnt" value="`$cnt+1`"}
              <tr> 
                <td height="25" background="img/index_{$cnt}.jpg" class="navigation"><a href="{$link.href}" class="a1"><font color="#000066">{$link.text}</font></a></td>
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
            </table>
            
            
		  		  <table border="0" cellpadding="0" cellspacing="0" height="10" style="border-bottom-width:1; ">
		{if $sub_header.links}
		<tr>
		{foreach from=$sub_header.links item=link}
			<td height="20" width="150" class="{if $link.selected==1}rgtselected{else}blackrgt{/if}" valign="middle" align="center" background="img/index_16b.jpg">
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
		<div id="hidewait" align="center"><br><img src="images/stats_wait.gif" width="355" height="33"></div>
			{/if}
		{/if}
{/if}