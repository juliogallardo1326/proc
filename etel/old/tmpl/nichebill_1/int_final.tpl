  {if $gkard_used}
		<table width="100%" height="200"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td class="normaltext" valign="middle" height="50">{#OP_CardNumber#}</td>
      <td height="50" valign="middle" class="normaltext">{$td_gkard}</td>
      <td rowspan="3" valign="middle" class="normaltext"><a href="https://www.NicheBill.com"><img src='{$tempdir}images/gkard_credit.gif' alt='' border='0'></a></td>
    </tr>
    <tr>
      <td height="50" valign="middle" class="normaltext">{#OP_GKardNumber#}</td>
      <td height="50" valign="middle" class="normaltext">{$td_ccNumber}</td>
    </tr>
    <tr>
      <td height="100" colspan="2" valign="middle" class="normaltext">{#OP_GkardReUse#}</td>
      </tr>
</table>
  {/if }
<form name="Frmname" action="{$Return_Url}" method="post">
		  <div align="center">
		  <table width="600" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">
                <strong>{if $livemode}{#OP_LiveModeMessage#}{$Email}{else}{#OP_TestModeMessage#}{/if}</strong> </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
			{if 0}
            <tr>
              <td>&nbsp;</td>
              <td colspan="2">{#OP_RedirectingTo#} <a href="javascript:Frmname.submit()">{$Return_Url}</a> ... <label id="timer">10</label>&nbsp;</td>
            </tr>
 		    {/if }
            <tr>
              <td>&nbsp;</td>
              <td></td>
              <td><input type="submit" name="Submit" value="{#GL_Continue#}"></td>
            </tr>
          </table>
		{$PostedVariables}
          </div>
</form>
		<script language="JavaScript">
		{if $OrderPageSettings=='autoforward'}
		document.Frmname.submit();
		//time = 3;
		//document.getElementById('timer').firstChild.nodeValue = time;
		//setInterval("if(time>0) time--;document.getElementById('timer').firstChild.nodeValue = time+' seconds'",1000);
		//setTimeout("document.Frmname.submit()", time*1000);
		{/if}
		</script>
<!--End Main-->
