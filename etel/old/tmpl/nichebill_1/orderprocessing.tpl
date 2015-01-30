{config_load file="lang/eng/language.conf" section="OrderPage"}

{if $mt_language != 'eng'}{config_load file="lang/$mt_language/language.conf" section="OrderPage"}{/if}

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>{#OP_PaymentPage#}</title>
<meta http-equiv="Content-Type" content="text/html; charset={#GL_Charset#}">
{literal}
<style>
  .navigation { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; font-weight:bolder; color:#000066; }
  .regular1 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; text-align:justify; }
  .regular2 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:13px; font-weight:bolder; text-align:center; }
  .tabletop { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; color:#FFFFFF; font-weight:bolder; }
  .tableside { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; color:#FFFFFF; }
  .tableside2 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:11px; color:#FFFFFF; }
  .tabledigit { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:11px; }
  .titlelarge { font-family:Arial Black,Verdana,Helvetica,sans-serif; font-size:16px; color:#000000; }
  .formfield { height:20px; width:100px; }
  .formfield2 { height:20px; width:160px; }
  .red { color:#CC3333; }
 </style>
{/literal}
<script language="javascript" src="{$rootdir}/scripts/general.js"></script>
<script language="javascript" src="{$rootdir}/scripts/creditcard.js"></script>
<script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
<script language="javascript" src="{$rootdir}/scripts/prototype.js"></script>
{literal}
<script language="JavaScript" type="text/JavaScript">
<!--

function MM_preloadimages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadimages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
int = 0;
red = false;
function blinkit()
{
	if(int == 10)
	{
		clearInterval(tblink);
		return;
	}
	else
	{		
		$('b1').style.backgroundColor = (red ? "#CC3333" : "");
		$('b1').style.color = (red ? "#FFFFFF" : "");
		red = !red;
		int++;
	}
	
}

function blinkIt()
{
	tblink = setInterval("blinkit()",500);
}



function showPayType(val)
{
	if(val == "check")
	{
		$('checkDisp').style.display = 'block';
		$('creditDisp').style.display = 'none';
	}
	else if(val == "credit")
	{
		$('checkDisp').style.display = 'none';
		$('creditDisp').style.display = 'block';
	}
}

function setDescriptor(val)
{
	if(val == "Visa")
		$('cardtypedesc').innerHTML = '{/literal}{$bill_des_visa}{literal}';
	else
		$('cardtypedesc').innerHTML = '{/literal}{$bill_des_master}{literal}';
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
	for(j=0;j<document.MyForm.paymenttype.length;j++)
	{
		if(document.MyForm.paymenttype[j].checked)
			paytype = document.MyForm.paymenttype[j].value;
	}
	var missingText = "You are missing the following REQUIRED information\n\n";
	var fields = new Array('firstname','lastname','email','telephone',{/literal}{if $isPasswordManagement}'td_username','td_password',{/if}{literal}'address','city','zipcode','country','state');
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
				case "state":
				missingText += "Your Billing State\n";
				break;
				case "country":
				missingText += "Your Billing Country\n";
				break;
			}
		}
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
	if(missingText.length > 60)
	{
		alert(missingText);
		return false;
	}
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

		$('zipcode').src='zipcode';

		$('state').disabled = false;

		$('td_bank_number').src='phone';

		

	}

	else

	{

		$('zipcode').src='req';

		$('state').disabled = true;

		$('td_bank_number').src='';

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
</head>

<body bgcolor="#FFFFFF"  text="#000066" link="#006699" vlink="#006699" alink="#006699" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="blinkIt()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td background="{$tempdir}images/order_01.gif"><table width="740" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1"><img src="{$tempdir}images/order_01.gif" width="1" height="94"></td>
          <td width="149"><img src="{$tempdir}images/order_02.jpg" width="149" height="94"></td>
          <td width="148"><img src="{$tempdir}images/order_03.gif" width="148" height="94"></td>
          <td width="417"><table width="417" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="{$tempdir}images/order_04.gif" width="417" height="39"></td>
              </tr>
              <tr>
                <td height="37" align="right" bgcolor="#99CCFF" class="tabledigit">NicheBill 
                  Web Payment Service is the designated internet payment processor 
                  and <br>
                  anti-fraud prevention system for &quot;{$cs_URL}&quot;</td>
              </tr>
              <tr>
                <td><img src="{$tempdir}images/order_07.gif" width="417" height="18"></td>
              </tr>
            </table></td>
          <td width="25"><img src="{$tempdir}images/order_05.gif" width="25" height="94"></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><hr width="630" size="1"></td>
  </tr>
  <tr> 
    <td align="center">
	  <span class="regular2">&nbsp;<br>
      Product Description:</span><span class="regular1"> You are asking to order 
      {$Description} at a price of USD ${$InitialAmount}</span>{if $errormsg}
	  <br>
	  <br>
	  <span class="red">Transaction Failed, Error Returned Was: {$errormsg}</span>
	  {/if}
	  </td>
  </tr>
  <tr>
    <td align="center"><img src="{$tempdir}images/spacer.gif" width="660" height="25"></td>
  </tr>
</table>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE">
  <form method="post" name="MyForm" onSubmit="return checkIt()">
  <input type="hidden" name="amt" id="amt" value="{$InitialAmount}">
  <input type="hidden" name="amount" id="amount" value="{$InitialAmount}">
    <tr> 
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
	<tr>
	  <td colspan="2" align="center">
	    <table width="150">
		  <tr>
		    <td class="regular2">
			  Select Payment Type:
			</td>
		  </tr>
		  {$payType}
		</table> 
	  </td>
	</tr>
    <tr height="280"> 
      <td width="330" rowspan="2" align="center" valign="top"> 
	    <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tr align="left"> 
            <td colspan="3" class="regular2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User 
              Information</td>
          </tr>
          <tr align="center"> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#OP_FirstLastName#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="firstname" type="text" class="formfield2" value="{$str_firstname}">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#OP_FirstLastName#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="lastname" type="text" class="formfield2" value="{$str_lastname}">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_YourEmail#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="email" type="text" class="formfield2" value="{$str_emailaddress}">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_PhoneNumber#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="telephone" type="text" class="formfield2" value="{$str_phonenumber}">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="15"></td>
          </tr>
{if $isPasswordManagement}
		  <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_ChooseUser#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="td_username" type="text" class="formfield2" value="{$str_username}">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_ChoosePass#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="td_password" type="password" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="15"></td>
          </tr>
{/if}
        </table>
        <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tr align="left"> 
            <td colspan="3" class="regular2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User 
              Billing Address</td>
          </tr>
          <tr align="center"> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_Address#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="address" type="text" class="formfield2" value="{$str_address}">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_City#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="city" type="text" class="formfield2" value="{$str_city}">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_Zipcode#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="zipcode" type="text" class="formfield2" value="{$str_zipcode}">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_Country#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <select name="country" class="formfield2" onChange="updatevalid(this);updateCountry(this);">
              {$opt_Countrys}
              </select> </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#GL_State#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <select name="state" style="formfield2">
              {$opt_States}
              </select> </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="30"></td>
          </tr>
        </table>
	  </td>
      <td width="330" align="center" valign="top">

	    <table width="300" border="0" cellspacing="0" height="180" cellpadding="0" id="creditDisp" {if !$hascredit} style="display:none" {/if}>
          <tr align="left"> 
            <td colspan="3" class="regular2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Credit 
              Card Details</td>
          </tr>
          <tr align="center"> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
		  
		  <tr> 
            <td width="90" align="right" class="tabledigit">{#OP_CardType#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> 
    		  <select size="1" id="cardtype" name="cardtype" onChange="setDescriptor(this.value)" title="reqmenu" >
                <option value="Visa" selected>Visa</option>
                <option value="Master">Master Card</option>
              </select>
            </td>
          </tr>
		  <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">{#OP_CardNumber#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="number" type="text" class="formfield2" onChange="setCCType(this);">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
		  <tr> 
            <td width="90" align="right" class="tabledigit">{#OP_ExpDate#}:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> {$expdate}</td>
          </tr>
		  
		  <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
		  
          <tr> 
            <td width="90" align="right" class="tabledigit">CVV2:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="cvv2" type="text"  size="3" class="formfield2">
            </td>
          </tr>
                   
          <tr> 
            <td colspan="3"><img src="{$tempdir}images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td colspan="3">
			  <table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="190" align="center"><img src="{$tempdir}images/cvv2.gif" width="175" height="110"></td>
                  <td width="110"> 
				    <table width="100" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr> 
                        <td align="center" class="tabledigit">Your Card ID or 
                          <strong>CVV2</strong> (credit verification value) is 
                          a 3-digit number found on the back of your card, used 
                          to help prevent fraudulent internet transactions.</td>
                      </tr>
                    </table>
				  </td>
                </tr>
              </table>
			</td>
          </tr>

        </table>

		<table width="300" border="0" height="180" cellspacing="0" cellpadding="0" id="checkDisp" {if $hascredit} style="display:none" {/if}>
		  <tr>
		    <td colspan="3" class="regular2">
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Bank Account Details
			</td>			
		  </tr>
		  <tr> 
            <td width="90" align="right" class="tabledigit">Bank Routing Number:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="routing" type="text" class="formfield2">
            </td>
          </tr>
		  <tr> 
            <td width="90" align="right" class="tabledigit">Account Number:</td>
            <td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="account" type="text" class="formfield2">
            </td>
          </tr>

		</table>
	  </td>
    </tr>
	<tr>
	  <td>
	  {if $isRecurring}
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
			<td class="tabledigit">{$RecurAmount}</td>
		  </tr>
		  <tr>
			<td class="tabledigit">{#OP_BilledEvery#}:</td>
			<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
			<td class="tabledigit">{$RecurDays}</td>
		  </tr>
		  <tr>
			<td class="tabledigit">{#OP_NextBillOn#}:</td>
			<td width="10"><img src="{$tempdir}images/spacer.gif" width="10" height="10"></td>
			<td class="tabledigit">{$NextDate}</td>
		  </tr>
		</table>
		{/if}
		{if $crosssales}
		<table width="300" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td align="center" style="background-color:#CC3333;color:#FFFFFF" class="regular2">
			  Additional Purchase
			</td>
		  </tr>
		  <tr>
		    <td class="tabledigit">
			  <input type="checkbox" name="crosssales" onClick="addCross(this)" {if $xcheck}checked{/if}> {$xsaledesc}
			</td>
		  </tr>
		</table>
		<input type="hidden" name="xsaleamt" id="xsaleamt" value="{$xsaleamt}">
		{/if}
	  </td>
	</tr>


    <tr> 
      <td colspan="2" align="center"><table width="580" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="tabledigit">
			<ul>
	          <li> {#OP_PleaseHavePatience#} </li>
	          <li> {#OP_YouWillBeCharged#} <strong>$<span id="tot2">{$TotalCharge}</span></strong><BR>{$CustomerFee} </li>
	          <li> {#OP_PurchaseWillAppear#}: &quot;<label id="cardtypedesc">{$bill_des_visa}</label>&quot;.</li>
	          {if $Subscription}<li>{#OP_CancelAnyTime#}</li>{/if}
	          <li> {#OP_FraudulentTransactions#}</li>
	          {if $Recurring} <li> {#OP_SubscriptionRenewed#}</li>{/if}
	          {if $Adult}<li> {#OP_AllSalesFinal#} </li>{/if}

        </ul></td>
          </tr>
          <tr> 
            <td><img src="{$tempdir}images/spacer.gif" width="580" height="9"></td>
          </tr>
          <tr> 
            <td align="center" class="tabledigit">By clicking the 'Secure purchase' 
              button, you agree to our <a href="#">Terms and Conditions</a>. Your 
              complete <a href="#">privacy</a> is assured.<br>
              To avoid multiple charges, press 'Secure purchase' only once. Authorization 
              may take a moment.</td>
          </tr>
          <tr> 
            <td><img src="{$tempdir}images/spacer.gif" width="580" height="20"></td>
          </tr>
          <tr>
            <td align="center"><input type="submit" name="Submit" value="Secure Purchase"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
  </form>
</table>
<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><img src="{$tempdir}images/spacer.gif" width="660" height="25"></td>
  </tr>
  <tr> 
    <td><hr width="630" size="1"></td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
</table>
</body>
</html>
