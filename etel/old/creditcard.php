<?php 
//******************************************************************//
//  This file is part of the Zerone Consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone Consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
//creditcard.php:	The page functions for entering the creditcard details.
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/function1.php';
require_once( 'includes/function.php');
$headerInclude="transactions";
include 'includes/header.php';

$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$sessionCompanyUser =isset($HTTP_SESSION_VARS["sessionCompanyUser"])?$HTTP_SESSION_VARS["sessionCompanyUser"]:"";
$sessionService =isset($HTTP_SESSION_VARS["sessionService"])?$HTTP_SESSION_VARS["sessionService"]:"";
$sessionServiceUser =isset($HTTP_SESSION_VARS["sessionServiceUser"])?$HTTP_SESSION_VARS["sessionServiceUser"]:"";

$str_company_id = $sessionlogin;

$bank_Creditcard="";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
if($sessionAdmin!="" && $companyInfo['block_virtualterminal']==0)
{


	$i_company_id = $str_company_id;
	
	$qry_details="SELECT * FROM `{$database["database_main"]}`.`cs_company_sites` WHERE `cs_gatewayId` = ".$_SESSION["gw_id"]." AND `cs_company_id` = '$i_company_id' AND cs_hide = '0'";	
	$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

	if(mysql_num_rows($rst_details)<=0)
	{
		$msgdisplay = "Please register a website before processing a virtual terminal transaction.";
		message($msgdisplay,$msgdisplay,"Register a Website");
		toLog('error','customer', "Customer has no available websites in ".basename(__FILE__)." on Line ". __LINE__." $msgdisplay", $companyid);
		exit();
	}

	while($site = mysql_fetch_assoc($rst_details))
	{
		$siteList.= "<option value='".$site['cs_ID']."' ".($site['cs_ID']==$siteID?"selected":"").">".str_replace('http://','',$site['cs_URL'])."</option>";
	}
	
	$str_currency = func_get_cardcurrency('Master',$i_company_id,$cnn_cs);
	$mastercurrency=$str_currency;
	$visacurrency=func_get_cardcurrency('Visa',$i_company_id,$cnn_cs);
	$sender =$_SESSION['gw_emails_sales'];
	$yearval=date("Y");
	$monthval=date("m");
	$dateval=date("d");
	$hr=date("G");
	$mn=date("i");
	$tt=date("A");
	
	$str_current_date = func_get_current_date();
	$i_to_year = substr($str_current_date,0,4);
	$i_to_month = substr($str_current_date,5,2);
	$i_to_day = substr($str_current_date,8,2);
	
	/*$i_to_day = date("d");
	$i_to_month = date("m");
	$i_to_year = date("Y");*/

	//$dateToEnter="$yearval-$monthval-$dateval";
	$curr_date = func_get_current_date_time();
	$dateToEnter = func_get_date_time_12hr($curr_date); //EST Time.
	$tran_login_type="";
	if($i_company_id !=""){
		$sql_trans_type = "Select transaction_type,bank_Creditcard  from cs_companydetails where userid=$i_company_id";
		if($show_trans_show = mysql_query($sql_trans_type)) {
			if($show_val = mysql_fetch_array($show_trans_show)) {
				$tran_login_type = $show_val[0];
				$bank_Creditcard= $show_val[1];
			}
				
		}
	}
	if ($tran_login_type == "tele") {
		$i_to_day = date("d",mktime(0,0,0,$i_to_month,$i_to_day + 1,$i_to_year));
		$i_to_month = date("m",mktime(0,0,0,$i_to_month,$i_to_day + 1,$i_to_year));
		$i_to_year = date("Y",mktime(0,0,0,$i_to_month,$i_to_day + 1,$i_to_year));

	}
	$domain = getRealIp(); 
	
?>
<script language="javascript" src="../scripts/general.js"></script>
<script language="javascript" src="../scripts/creditcard.js"></script>
<script language="javascript" src="../scripts/formvalid.js"></script>
<script>
function func_gercurrency(check){
	if (check=='set'){
		var currency = document.creditcardFrm.cardtype.value;
		if(currency=='Master')
		{
		<?php $str_currency = $mastercurrency; ?>
			//document.getElementById('txt_amount').firstChild.nodeValue='(<?=$str_currency?>)';
			//document.creditcardFrm.txt_rebill.value='(<?=$str_currency?>)';
			//document.creditcardFrm.currency_code.value=	'<?=$str_currency?>';
		}
		else if(currency=='Visa')
		{
		<?php $str_currency = $visacurrency ?>
		//document.getElementById('txt_amount').firstChild.nodeValue='(<?=$str_currency?>)';
		//document.creditcardFrm.txt_rebill.value='(<?=$str_currency?>)';
		//document.creditcardFrm.currency_code.value=	'<?=$str_currency?>';
		}
	}
	else {
		<?php $str_currency = $mastercurrency; ?>
	}
}
function yyyysel(){
	document.write('<select name="yyyy" style="font-family:verdana;font-size:10px;WIDTH: 60px" onChange="updatevalid(this)" title="reqmenu" >')
	document.write('<OPTION value="select">year</option>') 
	var str
		for (var i = 2005; i <=2012;  i++){
		
			str=str + '<option value=' + (i) + ' >' +   (i)  + '</option>'
			
		}
	document.write(str)
	document.write ('</select>&nbsp;')
}
function mmsel(){
	document.write('<select name="mm" style="font-family:verdana;font-size:10px;WIDTH: 50px" onChange="updatevalid(this)" title="reqmenu"  >')
	document.write('<OPTION value="select">mm</option>') 
	var str
		for (var i = 0; i <=11;  i++){
		
			str=str + '<option value=' + (i+1) + ' >' +    (i+1)  + '</option>'
			
		}
	document.write(str)
	document.write ('</select>&nbsp;')
	yyyysel()
}

function setCCType(objValue)
{            
    objValue.value = objValue.value.replace(new RegExp (' ', 'gi'), '');
    objValue.value = objValue.value.replace(new RegExp ('-', 'gi'), '');
	
	if (!isValidCreditCard(objValue.value)) return;
	typeofCard = typeOfCard(objValue.value);
	if (typeofCard == "VISA") document.getElementById('cardtype').value = "Visa";
	else if (typeofCard == "MASTERCARD") document.getElementById('cardtype').value = "Master";
	else if (typeofCard == "AMEX") alert("Sorry, we do not take Amex Cards");
	else if (typeofCard == "DISCOVER") alert("Sorry, we do not take Discover Cards");
	else alert(typeofCard);
}
function updateCountry(obj)
{
	if(obj.value == "US") 
	{
		document.getElementById('zipcode').src='zipcode';
		document.getElementById('state').disabled = false;
	}
	else
	{
		document.getElementById('zipcode').src='alphanumeric';
		document.getElementById('state').disabled = true;
	}
}
function delay(gap){ /* gap is in millisecs */
	var then,now; then=new Date().getTime();
	now=then;
	while((now-then)<gap)
	{
		now=new Date().getTime();
	}
}

function submitOrder(thisform)
{
	var validated = submitform(thisform);

	if(validated)
	{
		thisform.style.display='none';
		document.getElementById('processMessage').style.display='block';
	}
	return validated;

}

</script>
<script language="javascript">

function mmsel(){
	document.write('<select name="mm" style="font-family:verdana;font-size:10px;WIDTH: 50px" >')
	document.write('<OPTION  value="">mm</option>') 
	var str
		for (var i = 0; i <=11;  i++){
		
			str=str + '<option value=' + (i+1) + ' >' +    (i+1)  + '</option>'
			
		}
	document.write(str)
	document.write ('</select>&nbsp;')
	yyyysel()
}
function yyyysel(){
	document.write('<select name="yyyy" style="font-family:verdana;font-size:10px;WIDTH: 60px" >')
	document.write('<OPTION  value="">year</option>') 
	var str
		for (var i = 2004; i <=2025;  i++){
		
			str=str + '<option value=' + (i) + ' >' +   (i)  + '</option>'
			
		}
	document.write(str)
	document.write ('</select>&nbsp;')
}

function clearRecurDate()
{
	var obj_form = document.creditcardFrm;
	for(i=0;i<obj_form.recurdatemode.length;i++)
	{
		if(obj_form.recurdatemode[i].checked)
		{
			obj_form.recurdatemode[i].checked = false;
			break;
		}
	}
}
</script>
<style>
.tdbdr{border-bottom:1px solid black;}
.tdbdr1{border-bottom:1px solid black;border-right:1px solid black;}
.tdbdr2{border-right:1px solid black;}
.style1 {font-size: 10px;}
</style>
<form action="creditcardfb.php" method="post" name="creditcardFrm" id="creditcardFrm" onsubmit="return submitOrder(this)"> 
  <table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%"> 
    <tr> 
      <td width="100%" valign="top" align="left"> <table border="0" cellpadding="0" cellspacing="0" width="750" height="544" align="center" > 
          <tr> 
            <td width="100%" height="494" valign="top" align="left"> <table width="100%" height="165"  align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF"> 
                <tr> 
                  <td height="11" valign="top" align="left" width="19">&nbsp;</td> 
                  <td valign="top" align="left" width="652"  height="11"><img border="0" src="../images/cbg.jpg" width="1" height="2"></td> 
                  <td height="11" valign="top" align="left" width="28">&nbsp;</td> 
                </tr> 
                <tr> 
                  <td height="167" valign="top" align="left" width="19">&nbsp;</td> 
                  <td height="167" valign="top" align="left" width="100%" > <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid black"> 
                      <tr align="center" valign="middle" bgcolor="#78B6C2"> 
                        <td colspan="2" class="tdbdr" height="20"><span class="subhd"><strong>Customer Information</strong></span></td> 
                      </tr> 
                      <tr> 
                        <td width="50%" align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Test Mode </font><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"> Transaction : </font></td> 
                        <td width="50%" valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input name="testmode" type="checkbox" id="testmode" <?=($_POST['testmode']?"checked":"")?> value="Test" > 
                          <span class="style1">Check this box for Test Mode </span> </font></td> 
                      </tr> 
                      <tr> 
                        <td width="50%" align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">First Name : </font></td> 
                        <td width="50%" valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="firstname" value="<?=$_POST['firstname']?>" size="19" maxlength="75" src="req" > 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Last Name :</font></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="lastname" value="<?=$_POST['lastname']?>" size="19" maxlength="75" src="req"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Address: </font><br></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="address" value="<?=$_POST['address']?>" size="45" maxlength="100"  src="req"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">City :</font></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="city" value="<?=$_POST['city']?>" size="35" maxlength="50" src="req"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Country :</font></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <select name="country" id="country" style="width:135px;font-height:10px;font-face:verdana" onchange="updateCountry(this);" title="reqmenu"> 
                            <? 
if($str_country=="")
{
	$str_country = $_POST['country'];
	if (!$str_country) $str_country="United States";
}
func_get_country_select($str_country);
?> 
                          </select> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">State :</font></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <select name="state" id="state" style="width:100px;font-height:10px;font-face:verdana;" title="reqmenu"> 
                            <?=func_get_state_select($_POST['state']) ?> 
                          </select> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Other State:</font></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input name="otherstate" value="<?=$_POST['otherstate']?>" type="text" size="25"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Zip code :</font></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input name="zipcode" value="<?=$_POST['zipcode']?>" id="zipcode" type="text" size="15" maxlength="15" src="zipcode"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Phone : </font></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" value="<?=$_POST['telephone']?>" name="telephone" size="15" maxlength="15" src="req"> 
                          </font></td> 
                      </tr> 
                      <!-- Changed By midhun on 3/6/2004 starts here
					 <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">An 
                          email confirmation of&nbsp;&nbsp;<br>
                          this order will be sent to </font> : </font></td>
                        <td class="tdbdr">&nbsp;&nbsp;<font color="#001188"> 
                          <input type="text" name="email" size="40" maxlength="100">
                          </font></td>
                      </tr>
					  --> 
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Customer's email address</font> : </font></td> 
                        <td class="tdbdr">&nbsp;<font color="#001188"> 
                          <input type="text" name="email" value="<?=$_POST['email']?>" size="40" maxlength="100" src="req"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Confirm email address</font> : </font></td> 
                        <td class="tdbdr">&nbsp;<font color="#001188"> 
                          <input type="text" name="cfrm_email" value="<?=$_POST['cfrm_email']?>" size="40" maxlength="100" src="req"> 
                          </font></td> 
                      </tr> 
                      <!-- Changed By midhun on 3/6/2004 ends here--> 
                      <tr bgcolor="#78B6C2"> 
                        <td colspan="2" align="center" valign="middle" class="tdbdr" height="20"><span class="subhd"><strong>Payment Information</strong></span></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Website :</font></td> 
                        <td valign="middle" class="tdbdr"> &nbsp; 
                          <select name="selectSite" id="selectSite" title="reqmenu"> 
                            <option value="select">- Select -</option> 
                            <?=$siteList?> 
                          </select> </td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card Number :</font></td> 
                        <td valign="middle" class="tdbdr"><font color="#001188"> &nbsp; 
                          <input type="text" name="number" size="17" maxlength="16" src="creditcard"> 
                          </font><font size="1" face="Verdana"><a href="#" onClick='javascript:window.open("../images/creditcard.gif","","width=500,height=350")' class="link">CVV2</a></font><font color="#001188"> 
                          <input type="text" name="cvv2" size="3" maxlength="3" src="minlen|2"> 
                          </font> </td> 
                      </tr> 
                      <?php if ($bank_Creditcard == 3 ) {?> 
                      <?php }?> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Card Type : </font></td> 
                        <td valign="middle" class="tdbdr">&nbsp; 
                          <select size="1" name="cardtype" style="font-size: 8pt; font-family: Verdana" onChange="func_gercurrency('set')" title="reqmenu"> 
                            <option value="Master">Master Card</option> 
                            <option value="Visa">Visa</option> 
                          </select></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Expiration Date :</font></td> 
                        <td valign="middle" class="tdbdr">&nbsp; 
                          <script>
					  mmsel();
					  </script></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Amount of Money :</font></td> 
                        <td valign="middle" class="tdbdr"><font color="#000000">&nbsp; 
                          <input type="text" name="amount" size="15" maxlength="50" src="req"> 
                          <input type="text" name="txt_amount" style="border='0px'" disabled value="USD" src="req"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing Date :</font></td> 
                        <td valign="middle" class="tdbdr">&nbsp; 
                          <!--<font color="#001188">&nbsp;<input type="text" name="setbilldate" size="20">--> 
                          <select name="opt_bill_month" class="lineborderselect" style="font-size:10px" title="reqmenu"> 
                            <?php func_fill_month($i_to_month); ?> 
                          </select> 
                          <select name="opt_bill_day" class="lineborderselect" style="font-size:10px" title="reqmenu"> 
                            <?php func_fill_day($i_to_day); ?> 
                          </select> 
                          <select name="opt_bill_year" class="lineborderselect" style="font-size:10px" title="reqmenu"> 
                            <?php func_fill_year($i_to_year); ?> 
                          </select> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Product Description :</font></font></td> 
                        <td class="tdbdr">&nbsp;<font color="#001188"> 
                          <input type="text" name="productdescription" value="<?=$_POST['productdescription']?>" size="30" maxlength="200" src="req"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" class="tdbdr1"><font size="2" face="Verdana" color="#000000"><font face="Verdana, Arial, Helvetica, sans-serif">Bank Phone Number </font><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"> :</font></font></td> 
                        <td class="tdbdr">&nbsp;<font color="#001188"> 
                          <input name="td_bank_number" type="text" value="<?=$_POST['td_bank_number']?>" id="td_bank_number" size="30" maxlength="200" src="phone"> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Date/Time :</font></td> 
                        <td valign="middle" class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp; 
                          <?=$dateToEnter?> 
                          </font></td> 
                      </tr> 
                      <tr> 
                        <td align="right" valign="middle" class="tdbdr1" height="30"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP Address :</font> </td> 
                        <td valign="middle"  class="tdbdr"><font size="1" face="Verdana" color="#000000">&nbsp; 
                          <?=$domain?> 
                          <img src="../images/mastercard.jpg" width="30" height="18" border="0"> <img src="../images/visa.jpg" width="30" height="18" border="0"></font></td> 
                      </tr> 
                      <input type="hidden" name="domain1" value="<?=$domain?>" > 
                      <input type="hidden" name="currency_code" value="<?=$str_currency?>" > 
                    </table></td> 
                  <td height="2" valign="top" align="left" width="1" style="border-right:1px solid white"></td> 
                </tr> 
              </table> 
              <br> </td> 
          </tr> 
          <tr> 
            <td width="100%" valign="top" align="left"> <table border="0" cellpadding="0" cellspacing="0" width="100%"> 
                <tr> 
                  <td width="100%" align="center" height="23" valign="center"> <input name="add" type="image" src="images/submit.jpg" width="49" height="20"> 
                    </input> </td> 
                </tr> 
              </table></td> 
          </tr> 
          <tr> 
            <td>&nbsp;</td> 
          </tr> 
        </table> 
        <input type="hidden" name="hid_company_id" value="<?php print($i_company_id); ?>"> </td> 
    </tr> 
  </table> 
  </tr> 
  </table> 
  </td> 
  </tr> 
  </table> 
</form> 
<div align="center" style="display:none; height:500;" id="processMessage"><br> 

  <img src="http://www.etelegate.com/images/transactionWait.gif"></div> 
<script language="javascript">
<!--

	setupForm(document.getElementById('creditcardFrm'));
	updateCountry(document.getElementById('country'));
-->
</script> 
<?php
include("includes/footer.php");
}	
?> 
