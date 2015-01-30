	<!--Main-->

<form id="processingFrm" action="FinalProcessing.php" method="post" onsubmit="return submitOrder(this)">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="60%" align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
        <tr>
          <td width="40%" valign="top" class="fieldname">First Name / Last Name</td>
          <td><input type="text" name="firstname" size="19" maxlength="75" value="{$str_firstname}"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
            <input type="text" name="lastname" size="19" maxlength="75" value="{$str_lastname}"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
          </td>
        </tr>
        {if $isPasswordManagement}
        <tr>
          <td width="25%" valign="top" class="fieldname">Choose UserName </td>
          <td><input name="td_username" type="text" id="td_username" value="{$str_username}" size="30" maxlength="30"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
            <strong><span class="terms"> </span></strong></td>
        </tr>
        <tr>
          <td width="25%" valign="top" class="fieldname">Choose Password </td>
          <td><input name="td_password" type="password" id="td_password" value="{$td_password}" size="30" maxlength="30" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
            <strong><span class="terms"> </span></strong></td>
        </tr>
        {/if}
        <tr>
          <td width="40%" valign="top" class="fieldname">Address</td>
          <td><input type="text" name="address" size="37" maxlength="100" value="{$str_address}"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">Country</td>

          <td><select name="country" id="country" style="font-size:11px;width:180px;font-height:10px;font-face:verdana;" onChange="updatevalid(this);updateCountry(this);" title="reqmenu" >
              {$opt_Countrys}
            </select>
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">City</td>
          <td><input type="text" name="city" size="35" maxlength="50" value="{$str_city}" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="req">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">State</td>
          <td><select name="state" id="state" style="width:140px;font-height:10px;font-face:verdana;" title="reqmenu"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)">
              {$opt_States}
            </select>
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">Other State</td>
          <td><input name="otherstate" type="text" value="{$str_otherstate}" size="25">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">Zipcode</td>
          <td><input name="zipcode" type="text" id="zipcode"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="{$str_zipcode}" size="15" maxlength="15" src="zipcode">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">Phone Number</td>
          <td><input type="text" name="telephone" size="15" maxlength="15" value="{$str_phonenumber}"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="num"></td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">Your email address</td>
          <td><input type="text" id="email" name="email" size="37" maxlength="100" value="{$str_emailaddress}" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="email">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">Confirm email address</td>
          <td><input type="text" name="emailconfirm" id="emailconfirm" size="37" value="{$str_emailaddress}" maxlength="100" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="confirm|email">
          </td>
        </tr>
        <tr>
          <td valign="top" class="fieldname">Billing Descriptor</td>
          <td><font size="1" face="Verdana">
            {$bill_des}
            </font></td>
        </tr>
        <tr>
          <td valign="top" class="fieldname">Your IP Address</td>
          <td><font size="1" face="Verdana">
            {$str_ipaddress}
            </font></td>
        </tr>
      </table></td>
    <td width="40%" align="right" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
        <tr>
          <td width="36%" valign="top" class="fieldname1">Card Number</td>
          <td class="style1"><input type="text" name="number" size="17" maxlength="16"  onFocus="updatevalid(this);" onChange="updatevalid(this);setCCType(this);func_gercurrency('set');" onKeyUp="updatevalid(this)" src="creditcard">
&nbsp;<br>
            No spaces or dashes <br>
            (Example: 4444333322221111)<a href="#" class="style1" onClick='javascript:window.open("https://www.etelegate.com/images/creditcard.gif","","width=500,height=350")' ></a></span><span class="fieldname1"><a href="#" class="style1" onClick='javascript:window.open("https://www.etelegate.com/images/creditcard.gif","","width=500,height=350")' ><br>
            CVV2</a>&nbsp;
            <input type="text" name="cvv2" size="3" maxlength="3"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="minlen|2">
          </td>
        </tr>
        <tr>
          <td width="36%" valign="top" class="fieldname1">Card Type</td>
          <td><select size="1" id="cardtype" name="cardtype" style="font-size: 8pt; font-family: Verdana" onChange="updatevalid(this)" title="reqmenu" >
              <option value="select" selected>- Select One -</option>
              <option value="Master">Master Card</option>
              <option value="Visa">Visa</option>
            </select>
          </td>
        </tr>
        <tr>
          <td width="36%" valign="top" class="fieldname1">Expiration Date</td>
          <td><script> mmsel(); </script></td>
        </tr>
        <tr>
          <td width="36%" valign="top" class="fieldname1">Bank Phone Number </td>
          <td class="style1"><input name="td_bank_number" type="text" id="td_bank_number"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" size="17" src="phone">
            <br>
            This number is found on the back of your credit card.</td>
        </tr>
        <tr>
          <td width="36%" valign="top" class="fieldname1">Amount charged today</td>
          <td valign="middle" class="normaltext"><font size="2" face="Verdana" color="#000000">
			{$TotalCharge}
            <label name="txt_amount" id="txt_amount">(USD)</label>
            </font></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="60%" height="30" align="left" valign="middle" class="style1">
	{$Bullets}
    </td>
    <td width="40%" height="30" align="right" valign="bottom" class="style1">{$HackerSafe} </td>
  </tr>
</table>
<script language="javascript">
	setupForm(document.getElementById('processingFrm'));
	updateCountry(document.getElementById('country'));
</script>
	<!--End Main-->
</form>	