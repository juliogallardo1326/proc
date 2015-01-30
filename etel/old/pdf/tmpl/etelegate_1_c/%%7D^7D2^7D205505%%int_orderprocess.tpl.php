<?php /* Smarty version 2.6.9, created on 2007-06-04 14:00:13
         compiled from int_orderprocess.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'int_orderprocess.tpl', 1, false),array('modifier', 'replace', 'int_orderprocess.tpl', 356, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "lang/eng/language.conf",'section' => 'OrderPage'), $this);?>


<?php if ($this->_tpl_vars['mt_language'] != 'eng'):  echo smarty_function_config_load(array('file' => "lang/".($this->_tpl_vars['mt_language'])."/language.conf",'section' => 'OrderPage'), $this); endif; ?>

<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/general.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/creditcard.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/formvalid.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/prototype.js"></script>
<?php echo '
<script language="JavaScript" type="text/JavaScript">
<!--
'; ?>


<?php $_from = $this->_tpl_vars['availwallets']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['wallets']):
?>
	var wallet_<?php echo $this->_tpl_vars['wallets']['text']; ?>
 = 1;
<?php endforeach; endif; unset($_from); ?>

	var showval = new Array();
<?php $_from = $this->_tpl_vars['rad_banks']['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bank']):
?>
	showval[<?php echo $this->_tpl_vars['bank']['key']; ?>
] = "<?php echo $this->_tpl_vars['bank']['type']; ?>
";
<?php endforeach; endif; unset($_from); ?>

<?php echo '

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
	if(intr == 10 || !$(\'b1\'))
	{
		clearInterval(tblink);
		return;
	}
	else
	{		
		$(\'b1\').style.backgroundColor = (red ? "#CC3333" : "");
		$(\'b1\').style.color = (red ? "#FFFFFF" : "");
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
	$(\'checkDisp\').style.display = \'none\';
	$(\'visaDisp\').style.display = \'none\';
	$(\'jcbDisp\').style.display = \'none\';
	$(\'mastercardDisp\').style.display = \'none\';
	$(\'discoverDisp\').style.display = \'none\';
	$(\'walletDisp\').style.display = \'none\';
	$(\'additionalFundsDisp\').style.display = \'none\';
	$(\'additional_funds\').value = "";
	$(\'cardtype\').value = "";
	
	if(val != "")
	{
		type = showval[val];
		if(type!="")
		{
			$(\'cardtype\').value = type;
			$(type+\'Disp\').style.display = \'block\';
			$(\'chkbx_\'+val).checked = true;
			
			
			eval("blah = isdefined(\'wallet\' + val) ? $(wallet_" + val + "): 0;");
			if(blah)
				$(\'additionalFundsDisp\').style.display = \'block\';
		}
	}
	
}

function isdefined( variable)
{
    return (typeof(window[variable]) == "undefined")?  false: true;
}

function setDescriptor(val)
{
		$(\'cardtypedesc\').innerHTML = val;
}

function addCross(obj)
{
	tamt = parseFloat($(\'tot2\').innerHTML);
	iamt = parseFloat($(\'amt\').value);
	xamt = parseFloat($(\'xsaleamt\').value);
	vamt = parseFloat($(\'tot3\').innerHTML);
	
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
	$(\'tot2\').innerHTML = tot.toFixed(2);
	ntot = $(\'amount\').value = atot.toFixed(2);
	$(\'tot3\').innerHTML = ntot;
}

function checkIt()
{
	paytype = $(\'cardtype\').value;
	
	var missingText = "You are missing the following REQUIRED information\\n\\n";
	var fields = new Array(\'firstname\',\'lastname\',\'email\',\'telephone\',';  if ($this->_tpl_vars['cond_ispasswordmanagement']): ?>'td_username','td_password',<?php endif;  echo '\'address\',\'city\',\'zipcode\',\'country\');
	if(paytype != "wallet")
	for(i=0;i<fields.length;i++)
	{
		if(document.MyForm[fields[i]].value == "" || document.MyForm[fields[i]].value == "select")
		{
			switch(fields[i])
			{
				case "firstname":
				missingText += "Your First Name\\n";
				break;
				case "lastname":
				missingText += "Your Last Name\\n";
				break;
				case "email":
				missingText += "Your Email Address\\n";
				break;
				case "telephone":
				missingText += "Your Telephone Number\\n";
				break;
				case "td_username":
				missingText += "Your Chosen Username\\n";
				break;
				case "td_password":
				missingText += "Your Chosen Password\\n";
				break;
				case "address":
				missingText += "Your Billing Address\\n";
				break;
				case "city":
				missingText += "Your Billing City\\n";
				break;
				case "zipcode":
				missingText += "Your Billing Zipcode\\n";
				break;
				case "country":
				missingText += "Your Billing Country\\n";
				break;
			}
		}
	}
	if(paytype != "wallet")

	if(document.MyForm.state.value == "select" && document.MyForm.country.value == "US")
	{
		missingText += "Your Billing State\\n";
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
					missingText += "Your Credit Card Type\\n";
					break;
					case "number":
					missingText += "Your Credit Card Number\\n";
					break;
					case "cvv2":
					missingText += "Your Card\'s CVV2 Code\\n";
					break;
					case "mm":
					missingText += "Your Card\'s Expiration Month\\n";
					break;
					case "yyyy":
					missingText += "Your Card\'s Expiration Year\\n";
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
					missingText += "Your Bank Routing Number\\n";
					break;
					case "account":
					missingText += "Your Bank Account Number\\n";
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
					missingText += "Your Wallet ID (Same as your email address)\\n";
					break;
					case "wallet_pass":
					missingText += "Your Wallet Password\\n";
					break;
				}
			}
		}
	}
	'; ?>

	<?php if ($this->_tpl_vars['cond_ispasswordmanagement']): ?>
	<?php echo '
	var upass = /[A-Za-z0-9-]+$/i;
	if(document.MyForm.td_username.value.length < 5)
		missingText += "Your Username Must Be At Least 5 Characters Long\\n";
	if(!upass.test(document.MyForm.td_username.value))
		missingText += "Your Username May Only Contain Alphanumeric And Dash Characters\\n";
	if(document.MyForm.td_password.value.length < 5)
		missingText += "Your Password Must Be At Least 5 Characters Long\\n";
	if(!upass.test(document.MyForm.td_password.value))
		missingText += "Your Password May Only Contain Alphanumeric And Dash Characters\\n";
	
	'; ?>

	<?php endif; ?>
	<?php echo '
	var email = /^[A-Za-z0-9]+([_\\.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_\\.-][A-Za-z0-9]+)*\\.([A-Za-z]){2,4}$/i;
	if(!email.test(document.MyForm.email.value))
		missingText += "Your Email Address Appears To Be Invalid, Please Enter A Valid Email Address\\n";
	if(missingText.length > 60)
	{
		alert(missingText);
		return false;
	}
	$(\'on\').style.display = "none";
	$(\'off\').style.display = "block";
	return true;
}


function setCCType(objValue)

{            

    objValue.value = objValue.value.replace(new RegExp (\' \', \'gi\'), \'\');

    objValue.value = objValue.value.replace(new RegExp (\'-\', \'gi\'), \'\');

	

	if (!isValidCreditCard(objValue.value)) return;

	typeOfCard = typeOfCard(objValue.value);

	setDescriptor(typeOfCard);

}

function updateCountry(obj)
{
	if(obj.value == "US") 
	{
		$(\'state\').disabled = false;
		/*for(i=1;i<3;i++)
			$(\'st\'+i).style.display = "none";
		*/
	}
	else
	{
		$(\'state\').disabled = true;
		/*for(i=1;i<3;i++)
			$(\'st\'+i).style.display = "block";
		*/
	}

}



function submitOrder(thisform)

{

	var validated = submitform(thisform);



	if(validated)

	{

		thisform.style.display=\'none\';

		$(\'processMessage\').style.display=\'block\';

		$(\'formSubmit\').disabled=true;

		

	}

	return validated;



}


</script>
'; ?>

<div align="center" style="display:none; height:300;" id="off">
	<br>
	<br>
	<br>
	<br>
	<img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/transactionWait.gif">
</div>

<div id="on">

	<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr> 
			<td align="center">
				<hr width="630" size="1">

				<span class="regular2">
				<br>
				<?php echo $this->_config[0]['vars']['OP_ProdDesc']; ?>
</span><span class="regular1"> <?php echo $this->_config[0]['vars']['OP_ProdDesc2']; ?>
 
				"<?php echo ((is_array($_tmp=$this->_tpl_vars['str_description'])) ? $this->_run_mod_handler('replace', true, $_tmp, '&lt;BR>', ' ') : smarty_modifier_replace($_tmp, '&lt;BR>', ' ')); ?>
" <?php echo $this->_config[0]['vars']['OP_ProdDesc3']; ?>
 $<?php echo $this->_tpl_vars['flt_initialamount']; ?>
</span>
				<?php if ($this->_tpl_vars['str_errormsg']): ?>
					<br>
					<br>
					<span class="red"><?php echo $this->_config[0]['vars']['OP_TransFailed']; ?>
<br> <?php echo $this->_tpl_vars['str_errormsg']; ?>
</span>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['cond_istest']): ?>
					<br>
					<span class="red"><?php echo $this->_config[0]['vars']['OP_Testing']; ?>
</span>
				<?php endif; ?>
				<img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="660" height="25">
			</td>
		</tr>
	</table>

	
	<form method="post" name="orderform" id="orderform" action="OrderProcessing.php" onSubmit="return checkIt()">
	<input type="hidden" name="cardtype" id="cardtype" value="">
	<input type="hidden" name="amt" id="amt" value="<?php echo $this->_tpl_vars['flt_initialamount']; ?>
">
	<input type="hidden" name="amount" id="amount" value="<?php echo $this->_tpl_vars['flt_initialamount']; ?>
">
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
							<?php echo $this->_config[0]['vars']['OP_PayType']; ?>
:<BR />
							<?php $_from = $this->_tpl_vars['rad_banks']['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payType']):
?>
							<input type="radio" onClick="showPayType('<?php echo $this->_tpl_vars['payType']['key']; ?>
','<?php echo $this->_tpl_vars['payType']['bdesc']; ?>
')" name="bank_id" value="<?php echo $this->_tpl_vars['payType']['key']; ?>
" id="chkbx_<?php echo $this->_tpl_vars['payType']['key']; ?>
" <?php echo $this->_tpl_vars['payType']['checked']; ?>
 <?php echo $this->_tpl_vars['payType']['disabled']; ?>
 />
							<img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/order_<?php echo $this->_tpl_vars['payType']['type']; ?>
.gif">
							<?php echo $this->_tpl_vars['payType']['text']; ?>
<br>
							<?php endforeach; endif; unset($_from); ?>
							</div>
						</td>
					</tr>
			  </table> 
			</td>
		</tr>
		<tr height="280"> 
			<td width="330" rowspan="2" align="center" valign="top"> 

				<?php if ($this->_tpl_vars['cond_ispasswordmanagement']): ?>
				<table width="300" border="0" cellspacing="0" cellpadding="0">
					<tr align="left"> 
						<td colspan="3" class="regular2">Account Information</td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_ChooseUser']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<input name="td_username" type="text" class="formfield2" value="<?php echo $this->_tpl_vars['str_username']; ?>
">            
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_ChoosePass']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200">
							<input name="td_password" type="password" class="formfield2" value="<?php echo $this->_tpl_vars['str_password']; ?>
">
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="15"></td>
					</tr>
				</table>
				<?php endif; ?>
				<table width="300" border="0" cellspacing="0" cellpadding="0" id="dispBilling">
					<tr align="left"> 
						<td colspan="3" class="regular2">User Information</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_FirstLastName']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<input name="firstname" type="text" class="formfield2" value="<?php echo $this->_tpl_vars['str_firstname']; ?>
">
							<input name="lastname" type="text" class="formfield2" value="<?php echo $this->_tpl_vars['str_lastname']; ?>
" />            
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_YourEmail']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<input name="email" type="text" class="formfield2" value="<?php echo $this->_tpl_vars['str_emailaddress']; ?>
">            
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_PhoneNumber']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<input name="telephone" type="text" class="formfield2" value="<?php echo $this->_tpl_vars['str_phonenumber']; ?>
">
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="15"></td>
					</tr>
					<tr align="left"> 
						<td colspan="3" class="regular2">User Billing Address</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7" /></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_Address']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="address" type="text" class="formfield2" value="<?php echo $this->_tpl_vars['str_address']; ?>
">            </td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_City']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="city" type="text" class="formfield2" value="<?php echo $this->_tpl_vars['str_city']; ?>
">            </td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_Zipcode']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="zipcode" type="text" class="formfield2" value="<?php echo $this->_tpl_vars['str_zipcode']; ?>
">            </td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_Country']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<select name="country" id="country" class="formfield2" onChange="updatevalid(this);updateCountry(this);">
								<?php echo $this->_tpl_vars['opt_Countrys']; ?>

							</select>
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_State']; ?>
</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200">
							<select name="state" class="formfield2" id="state">
								<?php echo $this->_tpl_vars['opt_States']; ?>

							</select>
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['GL_OtherState']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"><input name="otherstate" type="text" value="<?php echo $this->_tpl_vars['str_otherstate']; ?>
" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="30"></td>
					</tr>
				</table>
			</td>
			<td width="330" align="center" valign="top">

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="discoverDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2"><?php echo $this->_config[0]['vars']['OP_CreditCardTitle']; ?>
</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CardType']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"><b>Discover Card</b></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CardNumber']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"><input name="discover_number" type="text" class="formfield2" onBlur="setCCType(this);"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_ExpDate']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <select name="discover_mm"><?php echo $this->_tpl_vars['expmonth']; ?>
</select><select name="discover_yyyy"><?php echo $this->_tpl_vars['expyear']; ?>
</select></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_BankPhone']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="discover_td_bank_number" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">CID:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="discover_cvv2" type="text"  size="4" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr> 
									<td width="190" align="center"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/cvv2.gif" width="175" height="110"></td>
									<td width="110" align="center" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_cidExpl']; ?>
</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="mastercardDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2"><?php echo $this->_config[0]['vars']['OP_CreditCardTitle']; ?>
</td>
					</tr>	
					<tr align="center"> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CardType']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"><b>Mastercard</b></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CardNumber']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="mastercard_number" type="text" class="formfield2" onBlur="setCCType(this);"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_ExpDate']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <select name="mastercard_mm"><?php echo $this->_tpl_vars['expmonth']; ?>
</select><select name="mastercard_yyyy"><?php echo $this->_tpl_vars['expyear']; ?>
</select></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_BankPhone']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="mastercard_td_bank_number" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">CVV2:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="mastercard_cvv2" type="text"  size="4" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr> 
									<td width="190" align="center"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/cvv2.gif" width="175" height="110"></td>
									<td width="110" align="center" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_cvvExpl']; ?>
</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="visaDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2"><?php echo $this->_config[0]['vars']['OP_CreditCardTitle']; ?>
</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CardType']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"><b>Visa</b></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CardNumber']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="visa_number" type="text" class="formfield2" onBlur="setCCType(this);"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_ExpDate']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <select name="visa_mm"><?php echo $this->_tpl_vars['expmonth']; ?>
</select><select name="visa_yyyy"><?php echo $this->_tpl_vars['expyear']; ?>
</select></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_BankPhone']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="visa_td_bank_number" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">CVV2:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="visa_cvv2" type="text"  size="4" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr> 
									<td width="190" align="center"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/cvv2.gif" width="175" height="110"></td>
									<td width="110" align="center" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_cvvExpl']; ?>
</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="jcbDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2"><?php echo $this->_config[0]['vars']['OP_CreditCardTitle']; ?>
</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CardType']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"><b>JCB</b></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CardNumber']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="jcb_number" type="text" class="formfield2" onBlur="setCCType(this);"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_ExpDate']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <select name="jcb_mm"><?php echo $this->_tpl_vars['expmonth']; ?>
</select><select name="jcb_yyyy"><?php echo $this->_tpl_vars['expyear']; ?>
</select></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_BankPhone']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="jcb_td_bank_number" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit">CVV2:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="jcb_cvv2" type="text"  size="4" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3">
							<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr> 
									<td width="190" align="center"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/cvv2.gif" width="175" height="110"></td>
									<td width="110" align="center" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_cvvExpl']; ?>
</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="checkDisp" style="display:none">
					<tr>
						<td colspan="3" class="regular2"><?php echo $this->_config[0]['vars']['OP_CheckTitle']; ?>
</td>			
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_RoutingNumber']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="routing" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_AccountNumber']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="account" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_AccountName']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="bankname" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>		  
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_CheckType']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<select name="checktype">
								<option value="P">Personal</option>
								<option value="C">Company</option>
								<option value="S">Savings</option>
							</select>
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/order_check_large.gif" width="300" height="130"></td>
					</tr>
				</table>

				<table width="300" border="0" cellspacing="0" cellpadding="0" id="walletDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2"><?php echo $this->_config[0]['vars']['OP_Wallet']; ?>
</td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_Wallet_ID']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="wallet_id" type="text" class="formfield2"></td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"><?php echo $this->_config[0]['vars']['OP_Wallet_Password']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> <input name="wallet_pass" type="password" class="formfield2"></td>
					</tr>
					<tr align="center"> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
				</table>
				<table width="300" border="0" cellspacing="0" cellpadding="0" id="additionalFundsDisp" style="display:none">
					<tr align="left"> 
						<td colspan="3" class="regular2"><?php echo $this->_config[0]['vars']['OP_Wallet_Additional_Funds']; ?>
</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
					<tr> 
						<td width="90" align="right" class="tabledigit"></td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td width="200"> 
							<select name="additional_funds" id="additional_funds">	
								<option value=""><?php echo $this->_config[0]['vars']['OP_Wallet_No_Thank_You']; ?>
</option>
								<option value="5">$5.00</option>
								<option value="10">$10.00</option>
								<option value="15">$15.00</option>
								<option value="20">$20.00</option>
							</select>
						</td>
					</tr>
					<tr> 
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="300" height="7"></td>
					</tr>
				</table>

			</td>
		</tr>
		<tr>
			<td>
				<?php if ($this->_tpl_vars['cond_isrecurring']): ?>
				<table width="300" height="100" border="0" cellspacing="0" cellpadding="0">		  
					<tr>
						<td colspan="3"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="5"></td>
					</tr>
					<tr>
						<td colspan="3" align="center" class="regular2" id="b1"><?php echo $this->_config[0]['vars']['OP_RecurBilling']; ?>
</td>
					</tr>
					<tr>
						<td class="tabledigit"><?php echo $this->_config[0]['vars']['OP_Amount']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td class="tabledigit"><?php echo $this->_tpl_vars['flt_recuramount']; ?>
</td>
					</tr>
					<tr>
						<td class="tabledigit"><?php echo $this->_config[0]['vars']['OP_BilledEvery']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td class="tabledigit"><?php echo $this->_tpl_vars['str_recurdays']; ?>
 days</td>
					</tr>
					<tr>
						<td class="tabledigit"><?php echo $this->_config[0]['vars']['OP_NextBillOn']; ?>
:</td>
						<td width="10"><img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="10" height="10"></td>
						<td class="tabledigit"><?php echo $this->_tpl_vars['str_nextdate']; ?>
</td>
					</tr>
				</table>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['cond_crosssales']): ?>
				<table width="300" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center" style="background-color:#CC3333;color:#FFFFFF" class="regular2"><?php echo $this->_config[0]['vars']['OP_AddOrder']; ?>
</td>
					</tr>
					<tr>
						<td class="tabledigit">
							<input type="checkbox" name="crosssales" onClick="addCross(this)" <?php if ($this->_tpl_vars['cond_xcheck']): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['str_xsaledesc']; ?>

						</td>
					</tr>
				</table>
				<input type="hidden" name="xsaleamt" id="xsaleamt" value="<?php echo $this->_tpl_vars['flt_xsaleamt']; ?>
">
				<?php endif; ?>
			</td>
		</tr>
		<tr> 
			<td colspan="2" align="center">
				<br>
				<table width="580" border="0" cellspacing="0" cellpadding="0">
					<tr> 
						<td class="tabledigit">
							<ul>
								<li> <?php echo $this->_config[0]['vars']['OP_PleaseHavePatience']; ?>
 </li>
								<li> <?php echo $this->_config[0]['vars']['OP_YouWillBeCharged']; ?>
 <strong>$<span id="tot2"><?php echo $this->_tpl_vars['flt_totalcharge']; ?>
</span></strong><BR><?php echo $this->_tpl_vars['str_customerfee']; ?>
 </li>
								<li> <?php echo $this->_config[0]['vars']['OP_PurchaseWillAppear']; ?>
: &quot;<label id="cardtypedesc"><?php echo $this->_tpl_vars['str_bill_des_visa']; ?>
</label>&quot;.</li>
							<?php if ($this->_tpl_vars['cond_issubscription']): ?>
								<li><?php echo $this->_config[0]['vars']['OP_CancelAnyTime']; ?>
</li>
							<?php endif; ?>
								<li> <?php echo $this->_config[0]['vars']['OP_FraudulentTransactions']; ?>
</li>
							<?php if ($this->_tpl_vars['cond_recurring']): ?> 
								<li> <?php echo $this->_config[0]['vars']['OP_SubscriptionRenewed']; ?>
</li>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['cond_adult']): ?>
								<li> <?php echo $this->_config[0]['vars']['OP_AllSalesFinal']; ?>
 </li>
							<?php endif; ?>
							</ul>

							<center>
							<img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="580" height="9"><BR />
							<?php echo $this->_config[0]['vars']['OP_AgreeTerms']; ?>
<BR />
							<img src="<?php echo $this->_tpl_vars['tempdir']; ?>
images/spacer.gif" width="580" height="20">
							</center>
						</td>
					</tr>

  <tr>
    <td class="tabledigit" align="right" ><?php echo $this->_tpl_vars['HackerSafe']; ?>
</td>
  </tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<center>
<input type="submit" id="Submit" value="<?php echo $this->_config[0]['vars']['OP_Purchase']; ?>
" onClick="getElementById('orderform').submit(); this.disabled=true;" disabled>

</center>
			<script>showPayType('<?php echo $this->_tpl_vars['rad_banks']['selected']; ?>
','<?php echo $this->_tpl_vars['rad_banks']['bdesc']; ?>
');</script>