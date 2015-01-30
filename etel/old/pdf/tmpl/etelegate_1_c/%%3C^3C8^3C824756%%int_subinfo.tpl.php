<?php /* Smarty version 2.6.9, created on 2006-06-04 23:46:48
         compiled from int_subinfo.tpl */ ?>

  <tr>
    <td width="60%" height="30" align="left" valign="middle" class="style1">
	        <ul>
          <li> <strong><?php echo $this->_config[0]['vars']['OP_PleaseHavePatience']; ?>
</strong> </li>
          <li> <?php echo $this->_config[0]['vars']['OP_YouWillBeCharged']; ?>
 <strong>$<?php echo $this->_tpl_vars['TotalCharge']; ?>
</strong><BR><?php echo $this->_tpl_vars['CustomerFee']; ?>
 </li>
          <li> <?php echo $this->_config[0]['vars']['OP_PurchaseWillAppear']; ?>
: &quot;<label id="bill_desc2"><?php echo $this->_tpl_vars['bill_des_visa']; ?>
</label>
            &quot;.</li>
          <?php if ($this->_tpl_vars['Subscription']): ?><li><?php echo $this->_config[0]['vars']['OP_CancelAnyTime']; ?>
</li><?php endif; ?>
          <li> <?php echo $this->_config[0]['vars']['OP_FraudulentTransactions']; ?>
</li>
          <?php if ($this->_tpl_vars['Recurring']): ?> <li> <?php echo $this->_config[0]['vars']['OP_SubscriptionRenewed']; ?>
</li><?php endif; ?>
          <?php if ($this->_tpl_vars['Adult']): ?><li> <?php echo $this->_config[0]['vars']['OP_AllSalesFinal']; ?>
 </li><?php endif; ?>
    </ul>    </td>
    <td width="40%" height="30" align="right" valign="bottom" class="style1"><?php echo $this->_tpl_vars['HackerSafe']; ?>
 </td>
  </tr>