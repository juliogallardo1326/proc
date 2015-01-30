<table class='invoice' width='100%'>
	{if $Profit.Title}
   <tr class='infoSection'>
     <td colspan="2">{$Profit.Title}</td>
   </tr>
   {/if}
   <tr class='infoHeader'>
     <td>Revenue</td><td>${$Profit.Revenue.Total.Amount|formatMoney} ({$Profit.Revenue.Total.Count|intval})</td>
   </tr>
   {foreach from=$Profit.Revenue key=key item=type}	
   {if $key != 'Total'}   
   <tr class='infoSubSection row{cycle values="1,2"}'>
	 <td>{$key}</td> <td>${$type.Amount|formatMoney} ({$type.Count|intval})</td>
   </tr>
   {/if}   
   {/foreach}
   <tr class='infoHeader'>
     <td>Deductions</td><td>${$Profit.Deductions.Total.Amount|formatMoney} ({$Profit.Deductions.Total.Count|intval})</td>
   </tr>
   {foreach from=$Profit.Deductions key=key item=type}
   {if $key != 'Total'}   	   
   <tr class='infoSubSection row{cycle values="1,2"}'>
	 <td>{$key}</td> <td>${$type.Amount|formatMoney} ({$type.Count|intval})</td>
   </tr>
   {/if}   
   {/foreach}
   <tr class='infoHeader'>
     <td>Total Profit </td>
     <td>${$Profit.Total.Amount|formatMoney} ({$Profit.Total.Count|intval})</td>
   </tr>
	{if $Profit.Notes}
   <tr class='infoSubSection row1'>
     <td colspan="2">{$Profit.Notes}</td>
   </tr>
   {/if}
 </table>