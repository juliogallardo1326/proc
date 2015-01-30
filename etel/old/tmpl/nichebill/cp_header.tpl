<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>{$gateway_title}</title>
<meta http-equiv="Content-Type" content="text/php; charset=iso-8859-1">
<link href="{$tempdir}styles/style.css" rel="stylesheet" type="text/css">
</head>
<script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
<script language="javascript" src="{$tempdir}/scripts/prototype.js"></script>
<script language="javascript" src="{$tempdir}/scripts/general.js"></script>
<script language="javascript" src="{$tempdir}/scripts/src/scriptaculous.js"></script>
<!--[if lt IE 7.]>
<script defer type="text/javascript" src="{$rootdir}/scripts/pngfix.js"></script>
<![endif]-->
<script language="javascript">

if(is_in_frame()) window.top.location.href='https://www.nichebill.com';
var rootdir = '{$rootdir}';
var tempdir = '{$tempdir}';
</script>
<body leftmargin="0" topmargin="0"  marginheight="0" marginwidth="0" class="BackEndPage">
    <!--Header End -->
	  <!-- Begin Main -->
	  
{if !$hide_header}
<div align="left" style="height:100%;">
 <div class="Table">
   <div class="Header"></div>
   <!--div class="CallUsPhone">Live Support<br /> 1-800-123-4567</div-->
   <div class="ControlPanel">Control Panel{if $page_title} <br> {$page_title}{/if}</div>
   <div class="Menu"> &nbsp; | &nbsp; 
	{foreach from=$main_header.links item=link}<a href="{$link.href}">{$link.text}</a> &nbsp; | &nbsp; {/foreach}	
   </div>
   <div class="SubMenu"> &nbsp; | &nbsp; 
	 {foreach from=$sub_header.links item=link}
	 <a href="{$link.href}">{$link.text}</a> &nbsp; | &nbsp; 
	 {/foreach}
   </div>
   <div class="MainContent">
	
	 {if $display_todo_list }
	 <div align="center">
	  <table width="60%"  border="1" cellspacing="2" cellpadding="2">
	   <tr>
	    <th scope="col" class="todoListHeader">Information:</th>
	   </tr>
	   <tr>
	    <th scope="col" class="todoList" align="left"><PRE>{$display_todo_list}</PRE></th>
	   </tr>
	  </table>
	 </div>
	 {/if}
			
{/if}