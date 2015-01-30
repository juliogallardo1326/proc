<?php
	include("includes/sessioncheck.php");
	require_once("includes/dbconnection.php");
	include("includes/header.php");
	$headerInclude="blank";
	include("includes/topheader.php"); 
	$gatewayid=(isset($HTTP_GET_VARS["gatewayId"])?$HTTP_GET_VARS["gatewayId"]:"");
	$str_UserId = isset($HTTP_SESSION_VARS["sessionlogin"])?trim($HTTP_SESSION_VARS["sessionlogin"]):"";

	$str_qry = "select merchant_contract_agree from cs_companydetails where userId = $str_UserId";
	$str_contract="";
	$str_agree="yes";
if (!$sql_select_val = mysql_query($str_qry,$cnn_cs)) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}else {
	$confirm ="checked";
	if(mysql_result($sql_select_val,0,0)=="Yes") {
		$confirm ="checked";
	} else if (mysql_result($sql_select_val,0,0)=="No") {
		$confirm ="";
	}
}
$qry_selectdetails="Select companyname,address,city,zipcode,country,bank_sort_code,company_bank,other_company_bank,bank_address,BICcode,VATnumber,registrationNo from cs_companydetails where userId = $str_UserId";
if(!($rst_selectdetails=mysql_query($qry_selectdetails,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
$qry_select="Select * from cs_gatewaydocument where gateway_id=$gatewayid and contenttype='merchantcontract'";
if(!($rst_select=mysql_query($qry_select,$cnn_cs)))
{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}
$i_count = mysql_num_rows($rst_select);
if($i_count!=0)
$str_contract = mysql_result($rst_select,0,1);
if($str_contract=="")
{
$str_agree="no";
$str_contract="Contact your administrator for merchant contract details";
}
else
{
$str_companyname=mysql_result($rst_selectdetails,0,0);
$str_street=mysql_result($rst_selectdetails,0,1);
$str_city=mysql_result($rst_selectdetails,0,2);
$str_post=mysql_result($rst_selectdetails,0,3);
$str_country=mysql_result($rst_selectdetails,0,4);
$str_iban=mysql_result($rst_selectdetails,0,5);
$str_bank=mysql_result($rst_selectdetails,0,6);
if($str_bank=="other")
$str_bank=mysql_result($rst_selectdetails,0,7);
$str_bankaddress=mysql_result($rst_selectdetails,0,8);
$str_biccode=mysql_result($rst_selectdetails,0,9);
$str_vatnumber=mysql_result($rst_selectdetails,0,10);
$str_registrationno=mysql_result($rst_selectdetails,0,11);
$str_contract = str_replace("[COMPANY NAME]","Company name :".$str_companyname,$str_contract);
$str_contract = str_replace("[STREET]","Steet         :".$str_street,$str_contract);
$str_contract = str_replace("[CITY]","City            :".$str_city,$str_contract);
$str_contract = str_replace("[POST CODE]","Post code  :".$str_post,$str_contract);
$str_contract = str_replace("[COUNTRY]","Country     :".$str_country,$str_contract);
$str_contract = str_replace("[IBAN Code.:]","IBAN code   :".$str_iban,$str_contract);
$str_contract = str_replace("[Name of bank:]","Name of bank   :".$str_bank,$str_contract);
$str_contract = str_replace("[Address of Bank:]","Address of bank :".$str_bankaddress,$str_contract);
$str_contract=str_replace("[VAT NO (if EC Country)]","Vat No    :".$str_vatnumber,$str_contract);
$str_contract=str_replace("[Companies No.:]","Companies No    :".$str_registrationno,$str_contract);
$str_contract=str_replace("[BIC Code:]","BIC Code     :".$str_biccode,$str_contract);
$str_contract = str_replace("[date]",date("m-d-y"),$str_contract);
}
?>

<form name="merchant_contract" method="get" action="integrate.php?type=testMode">	
	
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
      <td width="83%" valign="top" align="center"  height="333">
    	&nbsp;
			<table border="0" cellpadding="0" cellspacing="0" width="85%" class="disbd">
            	<tr>
              <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
              <img border="0" src="images/spacer.gif" width="1" height="1">
              </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
            </tr>
			<tr><td><table border="0" cellpadding="0" cellspacing="0" width="85%"  align="center"><tr><td align="center"><?php echo($str_contract); ?></td></tr></table>
			</td></tr>
			<tr><?php if($str_agree=="yes"){?>
                  <td height="31"  valign="middle"><span class="intx1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" name="agree_contract" value="Yes" <?=$confirm?>>
                    &nbsp;I Agree With The Merchant Contract.</span></td><?php } else{?>
					<td height="31"  valign="middle"><span class="intx1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<?php } ?>
			</tr>
			<tr><td align="center" valign="middle" height="40" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>&nbsp;&nbsp;<input type="image" border="0" src='images/continue.gif'></td></tr>
			</table>
			</td>
			</tr>
			</table>
   </form>         