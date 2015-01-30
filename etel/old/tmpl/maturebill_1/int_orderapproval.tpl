{config_load file="lang/eng/language.conf" section="OrderPage"}

{if $mt_language != 'eng'}{config_load file="lang/$mt_language/language.conf" section="OrderPage"}{/if}

<form method="{$form_get_post}" action="{$str_returnurl}" name="MyForm">
<table width="100%" cellspacing="0">
  <tr>
    <td align="center"><b>{if $cond_istest}{#OP_TestModeMessage#}{else}{#OP_LiveModeMessage#}{$str_emailaddress}{/if}</b></td>
  </tr>
  <tr>
    <td align="center"><input type="submit" name="Submit" value="{#GL_Continue#}"></td>
  </tr>
</table>
{$str_posted_variables}
</form>