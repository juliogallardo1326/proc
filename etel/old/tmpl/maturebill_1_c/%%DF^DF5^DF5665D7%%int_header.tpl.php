<?php /* Smarty version 2.6.9, created on 2006-03-28 23:29:28
         compiled from int_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'int_header.tpl', 1, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "lang/eng/language.conf",'section' => 'OrderPage'), $this);?>

<?php if ($this->_tpl_vars['mt_language'] != 'eng'):  echo smarty_function_config_load(array('file' => "lang/".($this->_tpl_vars['mt_language'])."/language.conf",'section' => 'OrderPage'), $this); endif; ?>
<!--Header-->
<html>
<head>
<TITLE><?php echo $this->_config[0]['vars']['OP_PaymentPage']; ?>
</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_config[0]['vars']['GL_Charset']; ?>
">
    <script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/general.js"></script>
    <script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/creditcard.js"></script>
    <script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/formvalid.js"></script>
    <script language="javascript" src="<?php echo $this->_tpl_vars['rootdir']; ?>
/scripts/prototype.js"></script>
	<link href="<?php echo $this->_tpl_vars['tempdir']; ?>
styles/orderpage.css" type="text/css" rel="stylesheet">
<?php echo '
<script>
function yyyysel(){
	document.write(\'<select name="yyyy" style="font-family:verdana;font-size:10px;WIDTH: 60px" onChange="updatevalid(this)" title="reqmenu" >\')
	document.write(\'<OPTION value="select">';  echo $this->_config[0]['vars']['GL_Year'];  echo '</option>\') 
	var str
		for (var i = 2005; i <=2012;  i++){
		
			str=str + \'<option value=\' + (i) + \' >\' +   (i)  + \'</option>\'
			
		}
	document.write(str)
	document.write (\'</select>&nbsp;\')
}
function mmsel(){
	document.write(\'<select name="mm" style="font-family:verdana;font-size:10px;WIDTH: 50px" onChange="updatevalid(this)" title="reqmenu"  >\')
	document.write(\'<OPTION value="select">';  echo $this->_config[0]['vars']['GL_Month'];  echo '</option>\') 
	var str
		for (var i = 0; i <=11;  i++){
		
			str=str + \'<option value=\' + (i+1) + \' >\' +    (i+1)  + \'</option>\'
			
		}
	document.write(str)
	document.write (\'</select>&nbsp;\')
	yyyysel()
}
function setDescriptor(typeOfCard)
{
	if (typeOfCard.toUpperCase() == "VISA") 
	{
		document.getElementById(\'cardtype\').value = "Visa";
		document.getElementById(\'bill_desc\').innerHTML = "';  echo $this->_tpl_vars['bill_des_visa'];  echo '";
		document.getElementById(\'bill_desc2\').innerHTML = "';  echo $this->_tpl_vars['bill_des_visa'];  echo '";
	}
	else if (typeOfCard.toUpperCase() == "MASTER") 
	{
		document.getElementById(\'cardtype\').value = "Master";
		document.getElementById(\'bill_desc\').innerHTML = "';  echo $this->_tpl_vars['bill_des_master'];  echo '";
		document.getElementById(\'bill_desc2\').innerHTML = "';  echo $this->_tpl_vars['bill_des_master'];  echo '";
	}
	else alert("';  echo $this->_config[0]['vars']['OP_SorryWeDoNotTake']; ?>
 "+typeOfCard+" <?php echo $this->_config[0]['vars']['GL_Cards'];  echo '");

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
		document.getElementById(\'zipcode\').src=\'zipcode\';
		document.getElementById(\'state\').disabled = false;
		document.getElementById(\'td_bank_number\').src=\'phone\';
		
	}
	else
	{
		document.getElementById(\'zipcode\').src=\'req\';
		document.getElementById(\'state\').disabled = true;
		document.getElementById(\'td_bank_number\').src=\'\';
	}
}

function submitOrder(thisform)
{
	var validated = submitform(thisform);

	if(validated)
	{
		thisform.style.display=\'none\';
		document.getElementById(\'processMessage\').style.display=\'block\';
		document.getElementById(\'formSubmit\').disabled=true;
		
	}
	return validated;

}

</script>
'; ?>


</head>
<body dir="LTR"  bgcolor="#F5F5F5">
<table border="0" cellpadding="0" cellspacing="0" width="650" align="center">
<tr>
  <td width="100%" valign="top" align="left"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="60%">
		
		<?php if (! mt_hide_logo): ?>
		<a href="https://www.MatureBill.com"><img src='<?php echo $this->_tpl_vars['tempdir']; ?>
images/index_01.gif' alt='' border='0'></a><?php endif; ?></td>
        <td align="right">

		
		<img src="<?php echo $this->_tpl_vars['tempdir']; ?>
/images/visa.jpg" alt="v" width="30" height="18">&nbsp;&nbsp;<img border="0" src="<?php echo $this->_tpl_vars['tempdir']; ?>
/images/mastercard.jpg">&nbsp;<br>
          <font size="2" face="Verdana"> </font>&nbsp;&nbsp;&nbsp; </td>
      </tr>
    </table>
	<?php if ($this->_tpl_vars['ShowPaymentInfo']): ?>
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="10" align="left" valign="top" class="normaltext"><span class="large"><?php echo $this->_config[0]['vars']['OP_ProductInformation']; ?>

                <?php echo $this->_tpl_vars['TestMode']; ?>

                </span></td>
            </tr>
            <tr>
              <td bgcolor="#009999"><img src="https://www.MatureBill.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
            </tr>
            <tr>
              <td width="85%" align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">
                  <tr>
                    <td colspan="2" align="right" valign="top"> 
					<form id="frm_language" action="" method="post">
	   <span  class="fieldname1"><?php echo $this->_tpl_vars['str_posted_variables'];  echo $this->_config[0]['vars']['GL_Language']; ?>
:</span>
	  <select name="mt_language" id="mt_language" onChange="document.getElementById('frm_language').submit()">
	    <option value="eng">English</option>
	    <option value="spa">Spanish</option>
	    <option value="fre">French</option>
	    <option value="ger">German</option>
	    <option value="ita">Italian</option>
	    <option value="por">Portuguese</option>
        <option value="kor">Korean</option>
	  </select>
	  <script language="javascript">
	document.getElementById('mt_language').value = '<?php echo $this->_tpl_vars['mt_language']; ?>
';
	 </script>
                    </form></td>
                  </tr>                  <tr>
                    <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> <?php echo $this->_config[0]['vars']['GL_Website']; ?>
: </font></span></div></td>
                    <td width="50%">&nbsp;
                      <?php echo $this->_tpl_vars['URL']; ?>

                    </td>
                  </tr>
                  <?php if ($this->_tpl_vars['Description']): ?>
                  <tr>
                    <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> <?php echo $this->_config[0]['vars']['GL_ProductName']; ?>
: </font></span></div></td>
                    <td width="50%">&nbsp;
                      <?php echo $this->_tpl_vars['Description']; ?>

                    </td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                    <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> <?php echo $this->_config[0]['vars']['OP_ProductInformation']; ?>
: </font></span></div></td>
                    <td width="50%">&nbsp;
                      <?php echo $this->_tpl_vars['ProdDescription']; ?>

                    </td>
                  </tr>
                  <?php if ($this->_tpl_vars['isSubAccount']): ?>
                  <tr>
                    <?php if ($this->_tpl_vars['isSubscription']): ?>
                    <td width="50%" align="right" valign="top"><div align="right"><span class="style5"><font size="2" face="Verdana"> Transaction Detail:</font></span>
                        <table width="200" border="2">
                          <tr bgcolor="#B9FDCF">
                            <td width="82"><span class="style1"><?php echo $this->_config[0]['vars']['OP_Amount']; ?>
:</span></td>
                            <td width="100"><span class="style1"><strong>
                              <?php echo $this->_tpl_vars['InitialAmount']; ?>
</strong>
                              </span></td>
                          </tr>
                          
                          <tr>
                            <td><span class="style1"><?php echo $this->_config[0]['vars']['OP_Period']; ?>
</span></td>
                            <td><span class="style1"><strong>
                              <?php echo $this->_tpl_vars['TrialDays']; ?>
</strong>
                              </span></td>
                          </tr>
                        </table>
                        <span class="style1"><font size="2" face="Verdana"> </font></span></div></td>
                  <?php endif; ?>
                  <?php if ($this->_tpl_vars['isRecurring']): ?>
                    <td width="50%"><font size="1" face="Verdana" color="#000000"><span class="style5"><font size="2" face="Verdana"><?php echo $this->_config[0]['vars']['OP_RecurBilling']; ?>
: </font></span></font>
                      <table width="200" border="2">
                        <tr bgcolor="#B9FDCF">
                          <td width="100"><span class="style1"><?php echo $this->_config[0]['vars']['OP_Amount']; ?>
:</span></td>
                          <td><span class="style1"><strong>
                            <?php echo $this->_tpl_vars['RecurAmount']; ?>

                            </strong> </span></td>
                        </tr>
                        <tr>
                          <td><span class="style1"><?php echo $this->_config[0]['vars']['OP_BilledEvery']; ?>
 </span></td>
                          <td><span class="style1">
                            <?php echo $this->_tpl_vars['RecurDays']; ?>

                            </span></td>
                        </tr>
                        <tr>
                          <td><span class="style1"><?php echo $this->_config[0]['vars']['OP_NextBillOn']; ?>
 </span></td>
                          <td><span class="style1">
                            <?php echo $this->_tpl_vars['NextDate']; ?>

                            </span> </td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr align="left">
                    <td colspan="2" valign="top"></td>
                    <?php endif; ?>
                  </tr>
                  <?php endif; ?>
                </table></td>
            </tr>
            <tr>
              <td align="center"><?php echo $this->_config[0]['vars']['OP_TotalCharge']; ?>
: $<strong><?php echo $this->_tpl_vars['TotalCharge']; ?>

                </strong> </td>
            </tr>
          </table></td>
      </tr>
<?php if ($this->_tpl_vars['gkard_support']): ?>
      <tr>
        <td class="normaltext" valign="middle" height="10"><span class="large"><?php echo $this->_config[0]['vars']['OP_MemberServicesPaymentOption']; ?>
</span><BR><?php echo $this->_config[0]['vars']['OP_GKardSignUp']; ?>
</td>
        <td class="normaltext" valign="middle" height="10"><img src='<?php echo $this->_tpl_vars['tempdir']; ?>
images/gkard_credit.gif' alt='' border='0'></td>
      </tr>
<?php endif; ?>

    </table>
	<?php endif; ?>
    <!--End Header-->