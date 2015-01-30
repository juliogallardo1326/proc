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
<table width="550" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="titlelarge"><u>{$header}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
              </tr>
              <tr>
                <td><img src="{$tempdir}img/spacer.gif" width="500" height="8"></td>
              </tr>
              <tr>
                <td align="center">
                  <table width="550" border="0" cellpadding="0" cellspacing="0">
				   <tr>                  
			        <td colspan="2">                     
                      <tr align="center" valign="middle" height="15"> 
                        <td style="height:15;" align="left" background="{$tempdir}img/table_corners_01_1x2.gif"><img src="{$tempdir}img/table_corners_01_1x1.gif"></td>
                        <td style="height:15;" align="right" background="{$tempdir}img/table_corners_01_1x2.gif"><img src="{$tempdir}img/table_corners_01_1x4.gif"></td>
                    </tr>      
                      <tr align="center" valign="middle"> 
                        <td colspan="2"> {$message}</td>
                    </tr>
					
					{if $isback || $issubmit}
<tr><td height="50" colspan="4" align="center" valign="center">
<br>
<input type="image" 
{if $isback}
src="{$tempdir}images/back.jpg"
{/if}
{if $issubmit}
src="{$tempdir}images/submit.jpg"
{/if}
></input>
</td></tr>
{/if}
					            
{if $footer_message}<tr style=" font-size:8; text-align:right; "><td colspan="2">{$footer_message}</td></tr>{/if}
                      <tr align="center" valign="middle" height="15"> 
                        <td style="height:15;" align="left" background="{$tempdir}img/table_corners_01_1x2.gif"><img src="{$tempdir}img/table_corners_02_1x1.gif"></td>
                        <td style="height:15;" align="right" background="{$tempdir}img/table_corners_01_1x2.gif"><img src="{$tempdir}img/table_corners_02_1x4.gif"></td>
                    </tr>        
                  </table></td>
              </tr>
            </table>
			</form>


<script language="javascript">
setupForm(document.getElementById('{$formName}'));
</script>