
  <tr>
    <td width="60%" height="30" align="left" valign="middle" class="style1">
	        <ul>
          <li> {#OP_PleaseHavePatience#} </li>
          <li> {#OP_YouWillBeCharged#} <strong>${$TotalCharge}</strong><BR>{$CustomerFee} </li>
          <li> {#OP_PurchaseWillAppear#}: &quot;<label id="bill_desc2">{$bill_des_visa}</label>&quot;.</li>
          {if $Subscription}<li>{#OP_CancelAnyTime#}</li>{/if}
          <li> {#OP_FraudulentTransactions#}</li>
          {if $Recurring} <li> {#OP_SubscriptionRenewed#}</li>{/if}
          {if $Adult}<li> {#OP_AllSalesFinal#} </li>{/if}
        </ul>    </td>
    <td width="40%" height="30" align="right" valign="bottom" class="style1">{$HackerSafe} </td>
  </tr>