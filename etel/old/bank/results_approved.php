

<html><head><title>PHP Test</title></head>
<body bgcolor="#FFFFFF">
<table width="450" border="0" height="81" bordercolor="#000000" cellspacing="0" align="center">
  <tr> 
    <td colspan="2"> 
      <div align="center"><font class="normalwht"><span class="tablehead"><b>Transaction 
        Results</b></span></font></div>
    </td>
  </tr>
  <tr> 
    <td colspan="2" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="center"><b><font color="#006600" size="3" face="Arial, Helvetica, sans-serif">Approved!</font><font color="#006600" size="2"> 
        </font></b></div>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right" class="tabledata">Merchant Id:</div>
    </td>
    <td class="tabledata" width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
      <? print $HTTP_GET_VARS['pg_merchant_id']; ?>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right">Transaction Type:</div>
    </td>
    <td class="tabledata" width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
      <? print $HTTP_GET_VARS['pg_transaction_type']; ?>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right">First Name</div>
    </td>
    <td class="tabledata" width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
      <? print $HTTP_GET_VARS['ecom_billto_postal_name_first']; ?>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right">Last Name:</div>
    </td>
    <td class="tabledata" width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
      <? print $HTTP_GET_VARS['ecom_billto_postal_name_last']; ?>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right">Response Type:</div>
    </td>
    <td class="tabledata" width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <? print $HTTP_GET_VARS['pg_response_type']; ?>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right">Response Code</div>
    </td>
    <td class="tabledata" width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
      <? print $HTTP_GET_VARS['pg_response_code']; ?>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right">Response Description</div>
    </td>
    <td class="tabledata" width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
      <? print $HTTP_GET_VARS['pg_response_description']; ?>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right">Authorization Code</div>
    </td>
    <td class="tabledata" width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
      <? print $HTTP_GET_VARS['pg_authorization_code']; ?>
    </td>
  </tr>
  <tr> 
    <td width="41%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="right"><b class="tabledata">Total:</b></div>
    </td>
    <td width="59%" bgcolor="#FFFFFF" bordercolor="#FFFFFF"><span class="tabledata">$ 
      <? print $HTTP_GET_VARS['pg_total_amount']; ?>
      </span></td>
  </tr>
  <tr> 
    <td colspan="2" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="center" class="tabledata">Transaction Trace Number</div>
    </td>
  </tr>
  <tr> 
    <td colspan="2" height="2" bgcolor="#FFFFFF" bordercolor="#FFFFFF"> 
      <div align="center" class="tabledata"></div>
      <div align="center" class="tabledata"> 
        <? print $HTTP_GET_VARS['pg_trace_number']; ?>
      </div>
    </td>
  </tr>
</table>
</body></html> 

