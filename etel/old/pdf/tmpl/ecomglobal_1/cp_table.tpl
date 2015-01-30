<script language="javascript">
var redir = '{$redir}';
{literal}
function submitform()
{


	if (redir != '') document.getElementById('msgform').submit();
	else window.history.back();
}
{/literal}
</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
<tr>
<td width="83%" valign="top" align="center">&nbsp;
<table border="0" cellpadding="0" cellspacing="0" width="50%" >
<tr>
<td height="22" align="left" valign="top" width="1%" background="{$tempdir}images/menucenterbg.gif" nowrap><img border="0" src="{$tempdir}images/menutopleft.gif" width="8" height="22"></td>
<td height="22" align="center" valign="middle" width="50%" background="{$tempdir}images/menucenterbg.gif" ><span class="whitehd">{$header}</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="{$tempdir}images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" background="{$tempdir}images/menutoprightbg.gif" ><img alt="" src="{$tempdir}images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="{$tempdir}images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="{$tempdir}images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5" style="border-style:groove; border-width:2" >
{if $redir}<form id="msgform" action="{$redir}" onSubmit="submitform()" method="post">{/if}
<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
<table width='400' border='0' cellpadding='0' >
<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'>{$message}</font>
</td></tr></table>
</td></tr>
<tr><td height="50" valign="center" align="center">
<br>
<input type="image" 
{if $isback}
src="{$tempdir}images/back.jpg"
{else}
src="{$tempdir}images/submit.jpg"
{/if}
></input>

</td></tr></table>
<tr>
<td width="1%"><img src="{$tempdir}images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="{$tempdir}images/menubtmcenter.gif"><img border="0" src="{$tempdir}images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="{$tempdir}images/menubtmright.gif"></td>
</tr>
</form>
</td></tr>
</table>
</td></tr>
</table>

