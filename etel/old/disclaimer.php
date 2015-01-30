<?php
	
	include("includes/sessioncheck.php");
	include("includes/header.php");
	$headerInclude="blank";
	include("includes/topheader.php"); 
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
	  <tr><td height="30"><span class="disctxhd">Our Disclaimer</span></td></tr>
	  <tr>
		<td><p align="justify"><span class="bentx"><img border="0" src="images/creditcard.jpg" align="right" hspace="4" vspace="4" width="109" height="150"><?=$_SESSION['gw_title']?> has built its reputation on making solutions come to life for merchants that in an every day scenario would be near impossible to process credit cards, as they are either a startup or do not have any financials or capital to back what they are looking to process.
		<?=$_SESSION['gw_title']?> has a zero tolerance for intestinal fraud caused by merchants. <?=$_SESSION['gw_title']?> has and will take the following actions against any type of fraudulent merchants who are looking to intentionally defraud the processor, bank, or consumer:
		</span></p><span class="bentx">
		<ol><li> Contact local and international authorities regarding the company's fraudulent practices.</li>
		<li> Immediately contact merchant processing bank who will then list the merchant's principles and company on the terminated merchant file (TMF LIST).</li>
		<li> Contact our collection agencies.</li>
		<li> Immediate legal action against the merchant for damages, not limited to: jointly pursuing class action lawsuit with the merchants customers who were defrauded intentionally by the merchant.</li>
		</ol></span><p align="justify"><span class="bentx"><img border="0" src="images/discpic1.jpg" align="left" width="80" height="99">
          In closing, <?=$_SESSION['gw_title']?> would like to make it very clear to any merchant applying for an international merchant account, that there is ZERO TAULERANCE FOR FRAUD INTENSIONALLY CAUSED BY MERCHANTS. 
		If your company in any way shape or form is looking to process your credit card transactions in a fraudulent manner, you are wasting your time. 
		With the vigorous fraud prevention steps involved from start to finish which verifies your customer's information and their decision to purchase a product, service, or membership, it is near impossible to bill any transactions fraudulently through the <?=$_SESSION['gw_title']?> Gateway. 
		For those merchants who wish to conduct business in a non-fraudulent manner, welcome to <?=$_SESSION['gw_title']?>
		</span></p>
		</td>
	  </tr>
	  <tr><td align="center" valign="middle" width="100%" height="40"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>
  <?php if($_SESSION["sessionlogin_type"] == "tele") { ?>
  &nbsp;&nbsp;<a href="achpayment.php"><img border="0" src="images/continue.gif"></a>
 <?php } else {?>
  &nbsp;&nbsp;<a href="application_aci.php"><img border="0" src="images/continue.gif"></a>
 <?php } ?>
	  </td></tr>
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
