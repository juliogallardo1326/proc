<?php
	include("includes/sessioncheck.php");
	require_once("includes/dbconnection.php");
	require_once('includes/function.php');
	include("includes/header.php");
	$headerInclude="blank";
	include("includes/topheader.php"); 
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";
$setup_amount=0;
$strCompanyType = funcGetValueByQuery("select transaction_type from cs_companydetails where userId=$sessionlogin",$cnn_cs);
$setup_amount = funcGetValueByQuery("select setupfee from cs_setupfee where company_type_short='$strCompanyType'",$cnn_cs);
$setup_amount = number_format ($setup_amount,2,".",",");
?>
<link href="styles/text.css" rel="stylesheet" type="text/css">


	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
    <tr>
   		 <td width="100%" valign="top" align="center"><br>
<table border="0" cellpadding="0" cellspacing="0" width="85%" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
              <img border="0" src="images/spacer.gif" width="1" height="1">
              </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center">
              <table border="0" cellspacing="0" cellpadding="0" width="95%">
	  <tr><td height="30"><span class="disctxhd">Rates and Fees</span></td></tr>
	<tr>
		<td>
		<p align="justify"><span class="normaltext1"><b>SET UP FEE-Each new merchant 
                    will be required to pay a mandatory <?=$setup_amount?> USD setup fee. 
                    The fee will pay for the following:</b></span> 
<table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td width="50%">		<span class="bentx"><br><strong>
        <ol><li> Order Management software.</li><br><br>
		<li> IVR Set up.</li><br><br>
		<li> Custom Voice Verification set up.</li><br><br>
		<li> Shipping Account Management.</li><br><br>
		<li> Return letter Template.</li><br><br>
		<li> 24/7 e-merchant support.</li><br><br>
		<li> Account open.</li><br><br>
		</ol></strong></span></td>
            <td width="50%" valign="middle" align="center"><img border="0" src="images/fees.jpg" width="288" height="209"></td>
          </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" width="98%" align="center" class="ratebd">
          <tr>
            <td width="100%" class="bentx" bgcolor="#FBFCFD"><br>
            <ul><li type="disc"><strong>RATE</strong>-&nbsp;15% of the net sales processed will be charge to the merchant to process their payments.</li><br><br>
		<li type="disc"><strong>TRANSACTION FEE</strong>-&nbsp;$4.00 Includes: voice verification, fraud scrubbing, follow up letter, customer support, Up-to-date-Do Not Call list scrubbing on every transaction.</li><br><br>
		<li type="disc"><strong>PAYMENT PERIOD</strong>-&nbsp;Weekly/settled on Fridays. 2 week hold/Monday-Sunday.</li><br><br>
		<li type="disc"><strong>WIRE TRANSFER FEE</strong>-&nbsp;$100 USD for each settlement to merchant.</li><br><br>
		<li type="disc"><strong>CHARGEBACK FEE</strong>-&nbsp;A penalty of $35.00 will be charged to the merchant for every charge back/reversal.</li><br><br>
		                  <li type="disc"><strong>RESERVE</strong>-&nbsp;A 10% 
                            insurance deposit is created during first 180 days 
                            of merchants account Payment amounts that were drawn 
                            out as an insurance deposit will be transmitted to 
                            the customer's personal account in the Etelegate.com. 
                            system on the 181st day after its retention.</li>
		</ul>
            </td>
          </tr>
        </table>
		
    </td>
  </tr>
	  <tr><td align="center" valign="middle" width="100%" height="40"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>&nbsp;&nbsp;<a href="application_aci.php"><img border="0" src="images/continue.gif"></a></td></tr>
	</table>
              </td>
            </tr>
          </table><br>
	 </td>
  	</tr>
	</table>
<?php
	include("includes/footer.php");
?>
