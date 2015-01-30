<table class='invoice' width='100%'>
	{if $Profit.Title}
   <tr class='infoSection'>
     <td colspan="2"><a {if $Profit.Link} href='{$Profit.Link}'{/if}>{$Profit.Title}</a></td>
   </tr>
   {/if}
   <tr class='infoHeader'>
     <td>Revenue</td><td><a {if $Profit.Revenue.Link} href='{$Profit.Revenue.Link}'{/if} >${$Profit.Revenue.Total.Amount|formatMoney} ({$Profit.Revenue.Total.Count|intval})</a></td>
   </tr>
   {foreach from=$Profit.Revenue key=key item=type}	
   {if $key != 'Total'}   
   <tr class='infoSubSection row{cycle values="1,2"}'>
	 <td>{$key}{if $type.Comments}<br /><span class="small">{$type.Comments}</span>{/if}</td> <td><a {if $type.Link} href='{$type.Link}'{/if} >${$type.Amount|formatMoney} ({$type.Count|intval})</a></td>
   </tr>
   {/if}   
   {/foreach}
   <tr class='infoHeader'>
     <td>Deductions</td><td><a {if $Profit.Deductions.Link} href='{$Profit.Deductions.Link}'{/if} >${$Profit.Deductions.Total.Amount|formatMoney} ({$Profit.Deductions.Total.Count|intval})</a></td>
   </tr>
   {foreach from=$Profit.Deductions key=key item=type}
   {if $key != 'Total'}   	   
   <tr class='infoSubSection row{cycle values="1,2"}'>
	 <td>{$key}{if $type.Comments}<br /><span class="small">{$type.Comments}</span>{/if}</td> <td><a {if $type.Link} href='{$type.Link}'{/if} >${$type.Amount|formatMoney} ({$type.Count|intval})</a></td>
   </tr>
   {/if}   
   {/foreach}
   <tr class='infoHeader'>
     <td>Total Profit </td>
     <td><a {if $Profit.Total.Link} href='{$Profit.Total.Link}'{/if} >${$Profit.Total.Amount|formatMoney} ({$Profit.Total.Count|intval})</a></td>
   </tr>
	{if $Profit.Notes}
   <tr class='infoSubSection row1'>
     <td colspan="2">{$Profit.Notes}</td>
   </tr>
   {/if}
 </table>