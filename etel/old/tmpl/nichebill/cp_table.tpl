{if $printable}
<table border="1">
<tr><td>{$header}</td></tr>
<tr><td>{$message}</td></tr>
</table>

{else}

<script language="javascript">
var redir = '{$redir}';
function submitform()
	{literal}
	{
	{/literal}
	
	
		if (redir != '') document.getElementById('{$formName}').submit();
		else window.history.back();
	{literal}
	}
	{/literal}
</script>
<form id="{$formName}" name="{$formName}" action="{$redir}" onSubmit="return validateForm(this);" method="{$method}" enctype="{$enctype}">
<table border="0" cellpadding="0" cellspacing="0" class="GeneralTable">

<tr>
<td class="T1x1"></td>
<td class="T1x2">{$header}</td>
<td class="T1x3"></td>
</tr>

<tr>
<td class="T2x1"></td>
<td class="T2x2">
{$message}

{if $isback || $issubmit}
<br>
<div style="text-align:center; width:100%">
<input type="image" 
{if $isback}
src="{$tempdir}images/table/back.png"
{/if}
{if $issubmit}
src="{$tempdir}images/table/submit.png"
{/if}
></input>
</div>
{/if}
{if $footer_message}<div style=" font-size:8; text-align:right; width:100%; ">{$footer_message}</div>{/if}

</td>
<td class="T2x3"></td>
</tr>

<tr>
<td class="T3x1"></td>
<td class="T3x2"></td>
<td class="T3x3"></td>
</tr>

</table>
</form>
<script language="javascript">
setupForm(document.getElementById('{$formName}'));
</script>

{/if}