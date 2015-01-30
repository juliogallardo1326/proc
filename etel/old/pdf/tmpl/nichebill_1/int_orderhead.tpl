{config_load file="lang/eng/language.conf" section="OrderPage"}

{if $mt_language != 'eng'}{config_load file="lang/$mt_language/language.conf" section="OrderPage"}{/if}


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>{#OP_PaymentPage#}</title>
<meta http-equiv="Content-Type" content="text/html; charset={#GL_Charset#}">
{literal}
<style>
  .navigation { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; font-weight:bolder; color:#000066; }
  .regular1 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; text-align:justify; }
  .regular2 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:13px; font-weight:bolder; text-align:center; }
  .tabletop { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; color:#FFFFFF; font-weight:bolder; }
  .tableside { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; color:#FFFFFF; }
  .tableside2 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:11px; color:#FFFFFF; }
  .tabledigit { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:11px; }
  .titlelarge { font-family:Arial Black,Verdana,Helvetica,sans-serif; font-size:16px; color:#000000; }
  .formfield { height:20px; width:100px; }
  .formfield2 { height:20px; width:160px; }
  .red { color:#CC3333; }
 </style>
{/literal}

</head>

<body bgcolor="#FFFFFF"  text="#000066" link="#006699" vlink="#006699" alink="#006699" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="blinkIt(); $('Submit').disabled=false;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td background="{$tempdir}images/order_01.gif"><table width="740" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1"><img src="{$tempdir}images/order_01.gif" width="1" height="94"></td>
          <td width="149"><img src="{$tempdir}images/order_02.jpg" width="149" height="94"></td>
          <td width="148"><img src="{$tempdir}images/order_03.gif" width="148" height="94"></td>
          <td width="417"><table width="417" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="{$tempdir}images/order_04.gif" width="417" height="39"></td>
              </tr>
              <tr>
                <td height="37" align="right" bgcolor="#99CCFF" class="tabledigit">{#OP_SiteHead#} &quot;{$cs_URL}&quot;</td>
              </tr>
              <tr>
                <td><img src="{$tempdir}images/order_07.gif" width="417" height="18"></td>
              </tr>
            </table></td>
          <td width="25"><img src="{$tempdir}images/order_05.gif" width="25" height="94"></td>
        </tr>
      </table></td>
  </tr>
</table>
{$body}
<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><img src="{$tempdir}images/spacer.gif" width="660" height="25"></td>
  </tr>
  <tr> 
    <td><hr width="630" size="1"></td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
</table>
</div>
</body>
</html>
