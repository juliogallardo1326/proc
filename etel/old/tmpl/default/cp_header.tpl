
<html><head>
<title>:: Payment Gateway ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
{if $autorefresh}<meta content="{$autorefresh}" http-equiv="refresh">{/if}

<link rel="icon" href="{$tempdir}images/favicon.ico" />
<link rel="shortcut icon" href="{$tempdir}images/favicon.ico" />
<link href="{$tempdir}styles/comp_set.css" type="text/css" rel="stylesheet">
<link href="{$tempdir}styles/style.css" type="text/css" rel="stylesheet">
<link href="{$tempdir}styles/text.css" type="text/css" rel="stylesheet">
<script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
<script language="javascript" src="{$rootdir}/scripts/general.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--


{if $autorefresh} setTimeout("window.location.reload();",{$autorefresh*1000});{/if}
{literal}

function is_in_frame()
{
	return (window.self != window.top);
}

if(is_in_frame()) window.top.location.href='https://www.etelegate.com';

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
<body leftmargin="0" topmargin="0" marginwidth="0"  marginheight="0">
<table align="center" border="1" bordercolor="#cccccc" cellpadding="0" cellspacing="0" width="778">
  <tr>
    <td width="782"> 
      <table cellpadding="0" cellspacing="0" width="778">
        <tr> 
          <td colspan="2" width="100%"><table cellpadding="0" cellspacing="0" width="100%">
              <tr> 
                <td width="44%"><img src="{$tempdir}images/index_01.gif" height="72" width="339"></td>
                <td align="right" valign="middle"><img src="{$tempdir}images/Control_Panel.jpg" height="45" width="278"><strong><br>
                  Call us 24/7 at 1-800-676-0127 <br>
                  <span style="font-size:12px;">or Call us Direct at 213-291-9051</span></strong></td>
              </tr>
              
            </tbody></table></td>
        </tr>
		{if $allowautorefresh}<form action="" id="frm_autorefresh" method="post">{$etel_postback_____}<input value="{if $autorefresh}-1{else}1{/if}" name="autorefresh" type="hidden">{/if}
        <tr height="20"> 
          <td width="950" align="left" valign="bottom" background="{$tempdir}images/cp_08.gif" >
			<label class="refresh" id="showcurtime">Getting Date/Time...</label>		  
		  </td>
          <td width="950" align="right" valign="bottom" background="{$tempdir}images/cp_08.gif" >
		  {if $allowautorefresh}<a class="refresh" href="javascript:document.getElementById('frm_autorefresh').submit()" onClick="">Turn Auto Refresh {if $autorefresh}Off{else}On{/if} </a>{/if}
		  </td>
        </tr>
		{if $allowautorefresh}</form>{/if}
        <tr> 
          <td colspan="2"><table bgcolor="#ffffff" border="1" cellpadding="0" cellspacing="0" width="100%">
              <tr> 
                <td bgcolor="#f2f2f2" valign="top" width="19%"> 
				{if $main_header.links }
                  <table bgcolor="#f2f2f2" border="0" width="100%">
                    
{foreach from=$main_header.links item=link}
                      <tr> 
                        <td>&nbsp;</td>
                        <td><a href="{$link.href}" class="a1">{$link.text}</a></td>
                      </tr>
{/foreach}
                    </tbody>
                  </table>
				{/if }
                </td>
                <td width="81%" valign="top">


		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="{$tempdir}images/menubtmbg.gif"><img alt="" src="{$tempdir}images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#f2f2f2" height="25" class="blackbd" valign="middle" align="center">
		  <table border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		{foreach from=$sub_header.links item=link}
			<td height="20" width="150" class="{if $link.selected==1}rgtselected{else}blackrgt{/if}" valign="middle" align="center">
			{if $link.disabled!=1}<a href="{$link.href}" class="maintx">{$link.text}</a>
			{else}<span class="maindisabled" >{$link.text}</span>{/if}
			</td>
		{/foreach}
		</tr>
		</table>
		</td>
		</tr>
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