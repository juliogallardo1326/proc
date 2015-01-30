<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//

include 'includes/sessioncheckuser.php';
include('includes/dbconnection.php');
$headerInclude="home";	

	$iShopNumber		= $HTTP_GET_VARS["SHOP_NUMBER"];
	$selectBankUpdates = "Select * from cs_scanorder where transactionId = $iShopNumber";
	if(!($run_Select_Qry = mysql_query($selectBankUpdates))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if (mysql_num_rows($run_Select_Qry) == 0) {
		$strMessage = "<center><br><img src='images/progressbar.gif'><br><h3>Please wait. Transaction in Progress....</center>";
	} else {
		header("Location:scan_result.php?SHOP_NUMBER=$iShopNumber");
	}
include 'includes/header.php';
include 'includes/topheader.php';

?>
<title>Display result</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV=Refresh CONTENT="8; URL=display_scan_result.php?SHOP_NUMBER=<?=$iShopNumber?>"> 
</head>
<body>
	<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr><td width="83%" valign="top" align="center"  height="333">&nbsp;
	<table border="0" cellpadding="0" cellspacing="0" width="600" height="70%" class="disbd">
		<tr>
		  <td width="100%" valign="top" align="center" bgcolor="#448A99" height="20">
		  <img border="0" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		 <tr>
		  <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		 <tr>
		  <td width="100%" valign="middle" align="left" height="35" class="disctxhd">&nbsp; Message</td>
		</tr>
		<tr>
		 <td width="100%" valign="top" align="center">
		 <table width="500" border="0" cellpadding="0"  height="150" >
		  <tr><td width="500" height="60" align="center" valign="center" bgcolor="#F7F9FB" class="ratebd"><span class="intx"><?=$strMessage?></span></td></tr>
		  </table>
		  </td>
		</tr>
        </table>
		</td>
     </tr>
</table>
</body>
<?php 
include 'includes/footer.php';
?>
