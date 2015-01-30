<?php

include ("Portfolio.php");
die();

include ("includes/sessioncheck.php");
$headerInclude="merchant";
include("includes/header.php");
require_once("../includes/function.php");
include("includes/message.php");
require_once("../includes/completion.php");

foreach($etel_completion_array as $key=>$data)
{
	$cd_completion_options .="<option value='$key' style='".$data['style']."'  >".$data['txt']."</option>\n";
}
	
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";

if($resellerLogin!="")
{
$merchant_type= isset($HTTP_GET_VARS['merchant_type'])?quote_smart($HTTP_GET_VARS['merchant_type']):"";
if($merchant_type =="") {
	$qrt_select_allcompany="Select distinct userId, companyname from cs_companydetails where reseller_id=$resellerLogin and activeuser=0";
} else if($merchant_type =="A") {
	$qrt_select_allcompany="Select distinct userId, companyname from cs_companydetails where reseller_id=$resellerLogin and activeuser=0 ";
} else {
	$qrt_select_allcompany="Select distinct userId, companyname from cs_companydetails where reseller_id=$resellerLogin and activeuser=0 and transaction_type='$merchant_type'";
}

?>
<script>
function submitMerchant() {
	document.FrmMerchant.action="selectMerchant.php";
	document.FrmMerchant.submit();
}
function Displaycompany(){
	if(document.FrmMerchant.merchant_type.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.FrmMerchant.merchant_type.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.FrmMerchant.merchant_type.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = 0;
	document.getElementById('activename').selectedIndex = 0;
	document.getElementById('nonactivename').selectedIndex = 0;
}
function validation() {
	if(document.FrmMerchant.nonactive_merchants.value=="") {
		alert("Please select a company");
		return false;
	} else {
		return true;
	}
}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" >
  <tr><td width="83%" valign="top" align="center"  >
<br>	
<form action="viewApplicationStatus.php" method="GET" name="FrmMerchant" onsubmit="return validation();" >
	<table width="50%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
    <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">View Reseller</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
	<tr>
	<td class="lgnbd" colspan="5">
	  <table height="100%" width="100%" cellspacing="0" cellpadding="0"><tr><td  width="100%" valign="center" align="center">
		  <table width="400" border="0" cellpadding="0"  height="100"><br>
		  <tr>
			<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Merchant Type &nbsp;</font></td>
			<td align="left" height="30" width="250">&nbsp;<select name="merchant_type" style="font-family:arial;font-size:10px;width:140px" onChange="submitMerchant();" >
				<option value="A">All Companies</option>
				<option value="ecom">General Ecommerce</option>
				<option value="trvl">Travel</option>
				<option value="phrm">Pharmacy</option>
				<option value="game">Gaming</option>
				<option value="adlt">Adult</option>
				<option value="tele">Telemarketing</option>
				<!--option value="crds">Card swipe</option-->
			</select>
			<script>
			script_doc = "<?=$merchant_type?>";
			if(script_doc=="") {
				document.FrmMerchant.merchant_type.value="A";
			} else {
				document.FrmMerchant.merchant_type.value='<?=$merchant_type?>';
			}	
			</script>
				
			</td>
		  </tr>
		  <tr><td colspan="2" width="100%">
		  <div id="nonactive" style="display:Yes">
		  <table>
		  <tr>
			<td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Select 
			  Companies &nbsp;</font></td>
			<td align="left" height="30" width="250">&nbsp;<select id="nonactivename" name="nonactive_merchants" style="font-family:arial;font-size:10px;width:140px">
<?php			func_multiselect_transaction($qrt_select_allcompany); ?>						
			</select>
			</td>
		  </tr>
		  <tr>
            <td height="30" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Company Status :</font> </td>
            <td><select name="cd_completion" id="cd_completion" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="func_fillcompanyname();">
                <option value="-2" selected >Any Status</option>
                <?=$cd_completion_options?>
            </select></td>
		    </tr>
		  </table>
		  </div>
		  </td></tr>
		  <tr>
			<td align="center" valign="center" height="30" colspan="2"> 
			  <input type="image" name="view"  src="../images/view.jpg"></td>
		  </tr>
		</table>
	  </td></tr>
	  </table>
	  <br>
	  </td>
      </tr>
	<tr>
	<td width="1%"><img src="../images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img src="../images/menubtmright.gif"></td>
	</tr>
    </table>
	</form>
    </td>
     </tr>
</table>
<?php
}
include("includes/footer.php");
?>