{config_load file="lang/eng/language.conf" section="OrderPage"}

{if $mt_language != 'eng'}{config_load file="lang/$mt_language/language.conf" section="OrderPage"}{/if}

<script language="javascript" src="{$rootdir}/scripts/general.js"></script>
<script language="javascript" src="{$rootdir}/scripts/creditcard.js"></script>
<script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
<script language="javascript" src="{$rootdir}/scripts/prototype.js"></script>
{literal}
<script language="JavaScript" type="text/JavaScript">
<!--
{/literal}

{foreach from=$availwallets item=wallets}
	var wallet_{$wallets.text} = 1;
{/foreach}

	var showval = new Array();
{foreach from=$rad_banks.types item=bank}
	showval[{$bank.key}] = "{$bank.type}";
{/foreach}

{literal}

function MM_preloadimages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadimages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
intr = 0;
red = false;
function blinkit()
{
	if(intr == 10 || !$('b1'))
	{
		clearInterval(tblink);
		return;
	}
	else
	{		
		$('b1').style.backgroundColor = (red ? "#CC3333" : "");
		$('b1').style.color = (red ? "#FFFFFF" : "");
		red = !red;
		intr++;
	}
	
}

function blinkIt()
{
	tblink = setInterval("blinkit()",500);
}



function showPayType(val,bdesc)
{
	setDescriptor(bdesc);
	$('checkDisp').style.display = 'none';
	$('visaDisp').style.display = 'none';
	$('jcbDisp').style.display = 'none';
	$('mastercardDisp').style.display = 'none';
	$('discoverDisp').style.display = 'none';
	$('walletDisp').style.display = 'none';
	$('additionalFundsDisp').style.display = 'none';
	$('additional_funds').value = "";
	$('cardtype').value = "";
	
	if(val != "")
	{
		type = showval[val];
		if(type!="")
		{
			$('cardtype').value = type;
			$(type+'Disp').style.display = 'block';
			$('chkbx_'+val).checked = true;
			
			
			eval("blah = isdefined('wallet' + val) ? $(wallet_" + val + "): 0;");
			if(blah)
				$('additionalFundsDisp').style.display = 'block';
		}
	}
	
}

function isdefined( variable)
{
    return (typeof(window[variable]) == "undefined")?  false: true;
}

function setDescriptor(val)
{
		$('cardtypedesc').innerHTML = val;
}

function addCross(obj)
{
	tamt = parseFloat($('tot2').innerHTML);
	iamt = parseFloat($('amt').value);
	xamt = parseFloat($('xsaleamt').value);
	vamt = parseFloat($('tot3').innerHTML);
	
	if(obj.checked)
	{	
		tot = xamt + tamt;
		atot = iamt + xamt;		
	}
	else
	{
		tot = tamt - xamt;
		atot = vamt - xamt;
	}
	$('tot2').innerHTML = tot.toFixed(2);
	ntot = $('amount').value = atot.toFixed(2);
	$('tot3').innerHTML = ntot;
}

function checkIt()
{
	paytype = $('cardtype').value;
	
	var missingText = "You are missing the following REQUIRED information\n\n";
	var fields = new Array('firstname','lastname','email','telephone',{/literal}{if $cond_ispasswordmanagement}'td_username','td_password',{/if}{literal}'address','city','zipcode','country');
	if(paytype != "wallet")
	for(i=0;i<fields.length;i++)
	{
		if(document.MyForm[fields[i]].value == "" || document.MyForm[fields[i]].value == "select")
		{
			switch(fields[i])
			{
				case "firstname":
				missingText += "Your First Name\n";
				break;
				case "lastname":
				missingText += "Your Last Name\n";
				break;
				case "email":
				missingText += "Your Email Address\n";
				break;
				case "telephone":
				missingText += "Your Telephone Number\n";
				break;
				case "td_username":
				missingText += "Your Chosen Username\n";
				break;
				case "td_password":
				missingText += "Your Chosen Password\n";
				break;
				case "address":
				missingText += "Your Billing Address\n";
				break;
				case "city":
				missingText += "Your Billing City\n";
				break;
				case "zipcode":
				missingText += "Your Billing Zipcode\n";
				break;
				case "country":
				missingText += "Your Billing Country\n";
				break;
			}
		}
	}
	if(paytype != "wallet")

	if(document.MyForm.state.value == "select" && document.MyForm.country.value == "US")
	{
		missingText += "Your Billing State\n";
	}
	if(paytype == "credit")
	{
		var credArr = new Array("cardtype","number","cvv2","mm","yyyy");
		for(b=0;b<credArr.length;b++)
		{
			if(document.MyForm[credArr[b]].value == "")
			{
				switch(credArr[b])
				{
					case "cardtype":
					missingText += "Your Credit Card Type\n";
					break;
					case "number":
					missingText += "Your Credit Card Number\n";
					break;
					case "cvv2":
					missingText += "Your Card's CVV2 Code\n";
					break;
					case "mm":
					missingText += "Your Card's Expiration Month\n";
					break;
					case "yyyy":
					missingText += "Your Card's Expiration Year\n";
					break;
				}
			}
		}
	}
	else if(paytype == "check")
	{
		var checkArr = new Array("routing","account");
		for(c=0;c<checkArr.length;c++)
		{
			if(document.MyForm[checkArr[c]].value == "")
			{
				switch(checkArr[c])
				{
					case "routing":
					missingText += "Your Bank Routing Number\n";
					break;
					case "account":
					missingText += "Your Bank Account Number\n";
					break;
				}
			}
		}
	}
	else if(paytype == "wallet")
	{
		var checkArr = new Array("wallet_id","wallet_pass");
		for(c=0;c<checkArr.length;c++)
		{
			if(document.MyForm[checkArr[c]].value == "")
			{
				switch(checkArr[c])
				{
					case "wallet_id":
					missingText += "Your Wallet ID (Same as your email address)\n";
					break;
					case "wallet_pass":
					missingText += "Your Wallet Password\n";
					break;
				}
			}
		}
	}
	{/literal}
	{if $cond_ispasswordmanagement}
	{literal}
	var upass = /[A-Za-z0-9-]+$/i;
	if(document.MyForm.td_username.value.length < 5)
		missingText += "Your Username Must Be At Least 5 Characters Long\n";
	if(!upass.test(document.MyForm.td_username.value))
		missingText += "Your Username May Only Contain Alphanumeric And Dash Characters\n";
	if(document.MyForm.td_password.value.length < 5)
		missingText += "Your Password Must Be At Least 5 Characters Long\n";
	if(!upass.test(document.MyForm.td_password.value))
		missingText += "Your Password May Only Contain Alphanumeric And Dash Characters\n";
	
	{/literal}
	{/if}
	{literal}
	var email = /^[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*\.([A-Za-z]){2,4}$/i;
	if(!email.test(document.MyForm.email.value))
		missingText += "Your Email Address Appears To Be Invalid, Please Enter A Valid Email Address\n";
	if(missingText.length > 60)
	{
		alert(missingText);
		return false;
	}
	$('on').style.display = "none";
	$('off').style.display = "block";
	return true;
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
		$('state').disabled = false;
		/*for(i=1;i<3;i++)
			$('st'+i).style.display = "none";
		*/
	}
	else
	{
		$('state').disabled = true;
		/*for(i=1;i<3;i++)
			$('st'+i).style.display = "block";
		*/
	}

}



function submitOrder(thisform)

{

	var validated = submitform(thisform);



	if(validated)

	{

		thisform.style.display='none';

		$('processMessage').style.display='block';

		$('formSubmit').disabled=true;

		

	}

	return validated;



}


</script>
{/literal}
<div align="center" style="display:none; height:300;" id="off">
	<br>
	<br>
	<br>
	<br>
	<img src="{$tempdir}images/transactionWait.gif">
</div>

<div id="on">

	<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr> 
			<td align="center">
				<hr width="630" size="1">

				<span class="regular2">
				<br>
				{#OP_ProdDesc#}</span><span class="regular1"> {#OP_ProdDesc2#} 
				"{$str_description}" {#OP_ProdDesc3#} ${$flt_initialamount}</span>
				{if $str_errormsg}
					<br>
					<br>
					<span class="red">{#OP_TransFailed#}<br> {$str_errormsg}</span>
				{/if}
				{if $cond_istest}
					<br>
					<span class="red">{#OP_Testing#}</span>
				{/if}
				<img src="{$tempdir}images/spacer.gif" width="660" height="25">
			</td>
		</tr>
	</table>

	
	<form method="post" name="orderform" id="orderform" action="OrderProcessing.php" onSubmit="return checkIt()">
	<input type="hidden" name="cardtype" id="cardtype" value="">
	<input type="hidden" name="amt" id="amt" value="{$flt_initialamount}">
	<input type="hidden" name="amount" id="amount" value="{$flt_initialamount}">
	<input type="hidden" name="submit_form" id="submit_form" value="1">
	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE">
		<tr> 
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<table width="700" align="center">
					<tr>
						<td width="250">&nbsp;</td>
						<td width="450" class="regular2">
							<div align='left'>
							{#OP_PayType#}:<BR />
							{foreach from=$rad_banks.types item=payType}
							<input type="radio" onClick="showPayType('{$payType.key}','{$payType.bdesc}')" name="bank_id" value="{$payType.key}" id="chkbx_{$payType.key}" {$payType.checked} {$payType.disabled} />
							<img src="{$tempdir}images/order_{$payType.type}.gif">
							{$payType.text}<br>
							{/foreach}
							</div>
						</td>
					</tr>
			  </table> 
			</td>
		</tr>
		<tr height="280"> 
			<td width="330" rowspan="2" align="center" valign="top"> 

				{if $cond_ispasswordmanagement}
				<table width="300" border="0" cellspacing="0" cellpadding="0">
					<tr align="left"> 
						<td colspan="3" class="regular2">Account Information</td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_ChooseUser#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<input name="td_username" type="text" class="formfield2" value="{$str_username}">            
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_ChoosePass#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200">
							<input name="td_password" type="password" class="formfield2" value="{$str_password}">
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="15"></td>
					</tr>
				</table>
				{/if}
				<table width="300" border="0" cellspacing="0" cellpadding="0" id="dispBilling">
					<tr align="left"> 
						<td colspan="3" class="regular2">User Information</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_FirstLastName#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<input name="firstname" type="text" class="formfield2" value="{$str_firstname}">
							<input name="lastname" type="text" class="formfield2" value="{$str_lastname}" />            
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_YourEmail#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<input name="email" type="text" class="formfield2" value="{$str_emailaddress}">            
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_PhoneNumber#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<input name="telephone" type="text" class="formfield2" value="{$str_phonenumber}">
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="15"></td>
					</tr>
					<tr align="left"> 
						<td colspan="3" class="regular2">User Billing Address</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7" /></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_Address#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="address" type="text" class="formfield2" value="{$str_address}">            </td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_City#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="city" type="text" class="formfield2" value="{$str_city}">            </td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_Zipcode#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="zipcode" type="text" class="formfield2" value="{$str_zipcode}">            </td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_Country#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<select name="country" id="country" class="formfield2" onChange="updatevalid(this);updateCountry(this);">
								{$opt_Countrys}
							</select>
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_State#}</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200">
							<select name="state" class="formfield2" id="state">
								{$opt_States}
							</select>
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#GL_OtherState#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"><input name="otherstate" type="text" value="{$str_otherstate}" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="30"></td>
					</tr>
				</table>
			</td>
			<td width="330" align="center" valign="top">

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="discoverDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2">{#OP_CreditCardTitle#}</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CardType#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"><b>Discover Card</b></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CardNumber#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"><input name="discover_number" type="text" class="formfield2" onBlur="setCCType(this);"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_ExpDate#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <select name="discover_mm">{$expmonth}</select><select name="discover_yyyy">{$expyear}</select></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_BankPhone#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="discover_td_bank_number" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">CID:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="discover_cvv2" type="text"  size="4" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr> 
									<td width="190" align="center"><img src="{$tempdir}images/cvv2.gif" width="175" height="110"></td>
									<td width="110" align="center" class="tabledigit">{#OP_cidExpl#}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="mastercardDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2">{#OP_CreditCardTitle#}</td>
					</tr>	
					<tr align="center"> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CardType#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"><b>Mastercard</b></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CardNumber#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="mastercard_number" type="text" class="formfield2" onBlur="setCCType(this);"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_ExpDate#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <select name="mastercard_mm">{$expmonth}</select><select name="mastercard_yyyy">{$expyear}</select></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_BankPhone#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="mastercard_td_bank_number" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">CVV2:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="mastercard_cvv2" type="text"  size="4" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr> 
									<td width="190" align="center"><img src="{$tempdir}images/cvv2.gif" width="175" height="110"></td>
									<td width="110" align="center" class="tabledigit">{#OP_cvvExpl#}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="visaDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2">{#OP_CreditCardTitle#}</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CardType#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"><b>Visa</b></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CardNumber#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="visa_number" type="text" class="formfield2" onBlur="setCCType(this);"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_ExpDate#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <select name="visa_mm">{$expmonth}</select><select name="visa_yyyy">{$expyear}</select></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_BankPhone#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="visa_td_bank_number" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">CVV2:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="visa_cvv2" type="text"  size="4" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr> 
									<td width="190" align="center"><img src="{$tempdir}images/cvv2.gif" width="175" height="110"></td>
									<td width="110" align="center" class="tabledigit">{#OP_cvvExpl#}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="jcbDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2">{#OP_CreditCardTitle#}</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CardType#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"><b>JCB</b></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CardNumber#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="jcb_number" type="text" class="formfield2" onBlur="setCCType(this);"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_ExpDate#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <select name="jcb_mm">{$expmonth}</select><select name="jcb_yyyy">{$expyear}</select></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_BankPhone#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="jcb_td_bank_number" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">CVV2:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="jcb_cvv2" type="text"  size="4" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr> 
									<td width="190" align="center"><img src="{$tempdir}images/cvv2.gif" width="175" height="110"></td>
									<td width="110" align="center" class="tabledigit">{#OP_cvvExpl#}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="checkDisp" style="display:none">
					<tr>
						<td colspan="3" class="regular2">{#OP_CheckTitle#}</td>			
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_RoutingNumber#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="routing" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_AccountNumber#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="account" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_AccountName#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="bankname" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>		  
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_CheckType#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<select name="checktype">
								<option value="P">Personal</option>
								<option value="C">Company</option>
								<option value="S">Savings</option>
							</select>
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/order_check_large.gif" width="300" height="130"></td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="walletDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2">{#OP_Wallet#}</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_Wallet_ID#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="wallet_id" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">{#OP_Wallet_Password#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="wallet_pass" type="password" class="formfield2"></td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
				</table>
				<table width="300" border="0" cellspacing="0" cellpadding="0" id="additionalFundsDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2">{#OP_Wallet_Additional_Funds#}</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"></td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<select name="additional_funds" id="additional_funds">	
								<option value="">{#OP_Wallet_No_Thank_You#}</option>
								<option value="5">$5.00</option>
								<option value="10">$10.00</option>
								<option value="15">$15.00</option>
								<option value="20">$20.00</option>
							</select>
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
					</tr>
				</table>

			</td>
		</tr>
		<tr>
			<td>
				{if $cond_isrecurring}
				<table width="300" height="100" border="0" cellspacing="0" cellpadding="0">		  
					<tr>
						<td colspan="3"><img src="{$tempdir}images/spacer.gif" width="10" height="5"></td>
					</tr>
					<tr>
						<td colspan="3" align="center" class="regular2" id="b1">{#OP_RecurBilling#}</td>
					</tr>
					<tr>
						<td class="tabledigit">{#OP_Amount#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td class="tabledigit">{$flt_recuramount}</td>
					</tr>
					<tr>
						<td class="tabledigit">{#OP_BilledEvery#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td class="tabledigit">{$str_recurdays} days</td>
					</tr>
					<tr>
						<td class="tabledigit">{#OP_NextBillOn#}:</td>
						<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
						<td class="tabledigit">{$str_nextdate}</td>
					</tr>
				</table>
				{/if}
				{if $cond_crosssales}
				<table width="300" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center" style="background-color:#CC3333;color:#FFFFFF" class="regular2">{#OP_AddOrder#}</td>
					</tr>
					<tr>
						<td class="tabledigit">
							<input type="checkbox" name="crosssales" onClick="addCross(this)" {if $cond_xcheck}checked{/if}> {$str_xsaledesc}
						</td>
					</tr>
				</table>
				<input type="hidden" name="xsaleamt" id="xsaleamt" value="{$flt_xsaleamt}">
				{/if}
			</td>
		</tr>
		<tr> 
			<td colspan="2" align="center">
				<br>
				<table width="580" border="0" cellspacing="0" cellpadding="0">
					<tr> 
						<td class="tabledigit">
							<ul>
								<li> {#OP_PleaseHavePatience#} </li>
								<li> {#OP_YouWillBeCharged#} <strong>$<span id="tot2">{$flt_totalcharge}</span></strong><BR>{$str_customerfee} </li>
								<li> {#OP_PurchaseWillAppear#}: &quot;<label id="cardtypedesc">{$str_bill_des_visa}</label>&quot;.</li>
							{if $cond_issubscription}
								<li>{#OP_CancelAnyTime#}</li>
							{/if}
								<li> {#OP_FraudulentTransactions#}</li>
							{if $cond_recurring} 
								<li> {#OP_SubscriptionRenewed#}</li>
							{/if}
							{if $cond_adult}
								<li> {#OP_AllSalesFinal#} </li>
							{/if}
							</ul>

							<center>
							<img src="{$tempdir}images/spacer.gif" width="580" height="9"><BR />
							{#OP_AgreeTerms#}<BR />
							<img src="{$tempdir}images/spacer.gif" width="580" height="20">
							</center>
						</td>
					</tr>

  <tr>
    <td class="tabledigit" align="right" >{$HackerSafe}</td>
  </tr>
				</table>
			</tr>
		</td>
	</table>
</form>
<center>
<input type="submit" id="Submit" value="{#OP_Purchase#}" onClick="getElementById('orderform').submit(); this.disabled=true;" disabled>

</center>
			<script>showPayType('{$rad_banks.selected}','{$rad_banks.bdesc}');</script>