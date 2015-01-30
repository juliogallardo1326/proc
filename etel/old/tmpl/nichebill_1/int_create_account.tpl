{config_load file="lang/eng/language.conf" section="OrderPage"}
{config_load file="lang/$mt_language/language.conf" section="OrderPage"}
<!--Header-->
<html>
<head>
<TITLE>{#OP_PaymentPage#}</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset={#GL_Charset#}">
    <script language="javascript" src="{$rootdir}/scripts/general.js"></script>
    <script language="javascript" src="{$rootdir}/scripts/creditcard.js"></script>
    <script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
	<link href="{$tempdir}styles/orderpage.css" type="text/css" rel="stylesheet">
{literal}
<script>
function yyyysel(){
	document.write('<select name="yyyy" style="font-family:verdana;font-size:10px;WIDTH: 60px" onChange="updatevalid(this)" title="reqmenu" >')
	document.write('<OPTION value="select">{/literal}{#GL_Year#}{literal}</option>') 
	var str
		for (var i = 2005; i <=2012;  i++){
		
			str=str + '<option value=' + (i) + ' >' +   (i)  + '</option>'
			
		}
	document.write(str)
	document.write ('</select>&nbsp;')
}
function mmsel(){
	document.write('<select name="mm" style="font-family:verdana;font-size:10px;WIDTH: 50px" onChange="updatevalid(this)" title="reqmenu"  >')
	document.write('<OPTION value="select">{/literal}{#GL_Month#}{literal}</option>') 
	var str
		for (var i = 0; i <=11;  i++){
		
			str=str + '<option value=' + (i+1) + ' >' +    (i+1)  + '</option>'
			
		}
	document.write(str)
	document.write ('</select>&nbsp;')
	yyyysel()
}
function setDescriptor(typeOfCard)
{
	if (typeOfCard.toUpperCase() == "VISA") 
	{
		document.getElementById('cardtype').value = "Visa";
		document.getElementById('bill_desc').innerHTML = "{/literal}{$bill_des_visa}{literal}";
		document.getElementById('bill_desc2').innerHTML = "{/literal}{$bill_des_visa}{literal}";
	}
	else if (typeOfCard.toUpperCase() == "MASTER") 
	{
		document.getElementById('cardtype').value = "Master";
		document.getElementById('bill_desc').innerHTML = "{/literal}{$bill_des_master}{literal}";
		document.getElementById('bill_desc2').innerHTML = "{/literal}{$bill_des_master}{literal}";
	}
	else alert("{/literal}{#OP_SorryWeDoNotTake#} "+typeOfCard+" {#GL_Cards#}{literal}");

}
function setCCType(objValue)
{            
    objValue.value = objValue.value.replace(new RegExp (' ', 'gi'), '');
    objValue.value = objValue.value.replace(new RegExp ('-', 'gi'), '');
	
	if (!isValidCreditCard(objValue.value)) return;
	typeOfCard = typeOfCard(objValue.value);
	setDescriptor(typeOfCard);
}
function updateCountry(obj)
{
	if(obj.value == "US") 
	{
		document.getElementById('zipcode').src='zipcode';
		document.getElementById('state').disabled = false;
		document.getElementById('td_bank_number').src='phone';
		
	}
	else
	{
		document.getElementById('zipcode').src='req';
		document.getElementById('state').disabled = true;
		document.getElementById('td_bank_number').src='';
	}
}

function submitOrder(thisform)
{
	var validated = submitform(thisform);

	if(validated)
	{
		thisform.style.display='none';
		document.getElementById('processMessage').style.display='block';
		document.getElementById('formSubmit').disabled=true;
		
	}
	return validated;

}

</script>
{/literal}

</head>
<body dir="LTR"  bgcolor="#F5F5F5">
<table border="0" cellpadding="0" cellspacing="0" width="650" align="center">
<tr>
  <td width="100%" valign="top" align="left"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="60%">
		
		<a href="https://www.NicheBill.com"><img src='{$tempdir}images/index_01.gif' alt='' border='0'></a></td>
        <td align="right">

		
		<img src="{$tempdir}/images/visa.jpg" alt="v" width="30" height="18">&nbsp;&nbsp;<img border="0" src="{$tempdir}/images/mastercard.jpg">&nbsp;<br>
          <font size="2" face="Verdana"> </font>&nbsp;&nbsp;&nbsp; </td>
      </tr>
    </table>

  <tr>
	<td align="center">{$accountMsg}</td>
  </tr>
</table>
{if !$accountCreated}
<form id="processingFrm" action="" method="post" onsubmit="return submitOrder(this)">
<input name="submitForm" type="hidden" id="submitForm" value="ProcessAccount">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="10" colspan="2" valign="middle" class="normaltext"><span class="large">{if !$edit_mode}{#OP_AccountInformation#}{else}{#OP_AccountInformationUpdate#}{/if}</span></td>
      </tr>
      <tr>
        <td bgcolor="#009999"><img src="https://www.NicheBill.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
        <td align="right" bgcolor="#009999"><img src="https://www.NicheBill.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
      </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td width="60%" align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
        
        <tr>
          <td colspan="2" valign="top" class="fieldname">{#GL_EmailWarning#}<BR></td>
          </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_YourEmail#}</td>
          <td><input type="text" id="email" name="email" size="32" maxlength="100" value="{$POST.email}" {if $edit_mode} disabled{/if}   src="email">
				
          </td>
        </tr>
		{if !$edit_mode}
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_ConfirmEmail#}</td>
          <td><input type="text" name="emailconfirm" id="emailconfirm" size="32" value="{$POST.email}" maxlength="100"    src="confirm|email">
          </td>
        </tr>
		{/if}
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_ChoosePass#} </td>
          <td><input name="td_password" type="password" id="td_password" size="30" maxlength="30"    src="req">
            </td>
        </tr>
		<tr>
          <td width="40%" valign="top" class="fieldname">{#OP_FirstLastName#}</td>
          <td><input type="text" name="firstname" size="19" maxlength="75" value="{$POST.firstname}"     src="req">
            <input type="text" name="lastname" size="19" maxlength="75" value="{$POST.lastname}"     src="req">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_Address#}</td>
          <td><input type="text" name="address" size="32" maxlength="100" value="{$POST.address}"     src="req">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_Address#} #2</td>
          <td><input type="text" name="address2" size="32" maxlength="100" value="{$POST.address2}" >
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_Country#}</td>
          <td><select name="country" id="country" style="font-size:11px;width:180px;font-height:10px;font-face:verdana;" onChange="updatevalid(this);updateCountry(this);" title="reqmenu" >
            <option value="US" selected>United States</option>
              {$opt_Countrys}
             
            </select>
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_City#}</td>
          <td><input type="text" name="city" size="32" maxlength="50" value="{$POST.city}"    src="req">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_State#}</td>
          <td><select name="state" id="state" style="width:140px;font-height:10px;font-face:verdana;" title="reqmenu"    >
              {$opt_States}
            </select>
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_Zipcode#}</td>
          <td><input name="zipcode" type="text" id="zipcode"     value="{$POST.zipcode}" size="15" maxlength="15" src="zipcode">
          </td>
        </tr>
        <tr>
          <td width="40%" valign="top" class="fieldname">{#GL_PhoneNumber#}</td>
          <td><input type="text" name="telephone" size="15" maxlength="15" value="{$POST.telephone}"     src="req"></td>
        </tr>
      </table></td>
    <td width="40%" align="right" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
      <tr>
        <td width="36%" valign="top" class="fieldname1"><span class="fieldname">{#OP_CardNumber#}</span></td>
        <td class="style1"><input type="text" name="number" size="17" maxlength="16"   onChange="setCCType(this);"  src="creditcard">
&nbsp;<br>
      {#OP_CCExample#}<a href="#" class="style1" onClick='javascript:window.open("https://www.NicheBill.com/images/creditcard.gif","","width=500,height=350")' ></a><span class="fieldname1"><a href="#" class="style1" onClick='javascript:window.open("https://www.NicheBill.com/images/creditcard.gif","","width=500,height=350")' ><br>
      CVV2</a>&nbsp;
      <input type="text" name="cvv2" size="3" maxlength="3"     src="minlen|2">
    </span></td>
      </tr>
      <tr>
        <td width="36%" valign="top" class="fieldname1"><span class="fieldname">{#OP_CardType#}</span></td>
        <td><select size="1" id="cardtype" name="cardtype" style="font-size: 8pt; font-family: Verdana" onChange="setDescriptor(this.value)" title="reqmenu" >
            <option value="Visa" selected>Visa</option>
            <option value="Master">Master Card</option>
          </select>
        </td>
      </tr>
      <tr>
        <td width="36%" valign="top" class="fieldname1"><span class="fieldname">{#OP_ExpDate#}</span></td>
        <td><script> mmsel(); </script></td>
      </tr>
      <tr>
        <td width="36%" valign="top" class="fieldname1"><span class="fieldname">{#OP_BankPhone#}</span></td>
        <td class="style1"><input name="td_bank_number" type="text" id="td_bank_number" value="{$POST.td_bank_num}"     size="17" src="phone">
            <br>
      {#OP_BankPhoneFound#}</td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="javascript">
	setupForm(document.getElementById('processingFrm'));
	updateCountry(document.getElementById('country'));
</script>
	<!--End Main-->
</form>	
{else}
{/if}