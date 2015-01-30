<table class='invoice' width='700px'>
{if $Calendar.Notes}
  <tr >
    <td colspan="7">
	  {$Calendar.Notes|nl2br}
    </td>
  </tr>
{/if}
  <tr >
    <td colspan="7"><select name='SelectMonth' onchange="this.form.submit()">
        
{html_options values=$DateOptions.Values output=$DateOptions.Names selected=$DateOptions.Selected}

      </select>
		<label class="rowhighlight" style="padding:4px;">{$Calendar.PayDayInfo.Schedule} &nbsp;</label>
    </td>
  </tr>
  <tr class='infoHeader'>
    <td style="width:100px">Sunday</td>
    <td style="width:100px">Monday</td>
    <td style="width:100px">Tuesday</td>
    <td style="width:100px">Wednesday</td>
    <td style="width:100px">Thursday</td>
    <td style="width:100px">Friday</td>
    <td style="width:100px">Saturday</td>
  </tr>
 {foreach from=$Calendar.Week key=key item=Week}
  <tr class='infoSubSection row2' style="height:60px;vertical-align:top;"> 
   {foreach from=$Week.Day key=key item=Day}
    <td {if $Day.CurMonth}{if $Day.PayDay}class="rowhighlight"{else}class="row1"{/if}{else}style="color:#888888"{/if} onclick="document.location.href='#{$Day.Date}'">
	<div class="{if $Day.CurMonth}row0{/if}" align="left">{$Day.Num}</div>
      {$Day.Text} 
	</td>
   {/foreach} 
  </tr>
 {/foreach}
</table>
