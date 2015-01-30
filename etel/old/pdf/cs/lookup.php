<?
/*written by rob wultsch on or 6/15/2306
This page is intended to get called by an iframe on phpmyfaq.
If someone should screw up the faq pages that run the iframe,  run:
UPDATE `faq_faqdata` SET `content` = '<P ALIGN=center><IFRAME SRC="../transaction_lookup.php" WIDTH=400 HEIGHT=400 FRAMEBORDER=0></IFRAME></P>' WHERE `id` =19 AND CONVERT( `lang` USING utf8 ) = 'en' LIMIT 1 ;

For fake transaction to run against:
SELECT cs_transactiondetails.`transactionId` , cs_transactiondetails.`transactionDate` , cs_transactiondetails.`email` , cs_transactiondetails.`reference_number` , cs_subscription.`ss_ID`
FROM `cs_transactiondetails` , `cs_subscription`
WHERE cs_subscription.`ss_ID` = cs_transactiondetails.`td_ss_ID` 
*/
$etel_debug_mode= 0;
include '../includes/dbconnection.php';
require_once("lookup.class.php");
$lookup = new lookup_class();

$URL =str_replace( strstr($_SERVER["REQUEST_URI"],'?'),"",$_SERVER["REQUEST_URI"]);
 

?>
<html>
<head>
<?
if($_REQUEST[action]=="request_refund" OR $_REQUEST[action]=="cancel")
{
	echo '<META HTTP-EQUIV="refresh" CONTENT="3;URL='.$_SERVER['HTTP_REFERER'] .'\">';
}
?> 
<style type="text/css">
h2.pos_right
{
position:relative;
left:23px
}
h3.pos_right
{
position:relative;
left:10px
}

</style>
    <title>Transaction Search</title>
</head>
<body>

<table>
<tr><td>
<form name="FrmName1"  method="post" action="viewSubscription.php"> 
	<table style="border: 1px solid rgb(55, 108, 132);" align="left" cellpadding="0" cellspacing="0" width="400">
		<tbody>
			<tr>
				<td>
					<table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td style="border-bottom: 1px solid rgb(55, 108, 132);" align="left" bgcolor="#3d8287">
									<font style="font-family: verdana; font-size: 12px; color: white; font-weight: bold;">
										Search by Subscription Info (2 of 3 fields required)
									</font> 
								</td>
							</tr>
						</tbody>
					</table>
					<table align="center" border="0" width="100%">
						<tbody>
							<tr>
							  <td colspan="2" align="center"><font style="font-family: verdana; font-size: 11px; color: black;"> Subscription ID </font> </td>
							</tr>
							<tr>
							  <td colspan="2" align="center"><input name="subscription_ID" class="TextBox" type="text" value="<?=$_REQUEST['subscription_ID']?>">                              </td>
							</tr>
							<tr>
							  <td width="50%" align="center"><font style="font-family: verdana; font-size: 11px; color: black;"> Email Address </font> </td>
								<td width="50%" align="center"><font style="font-family: verdana; font-size: 11px; color: black;"> Phone Number </font> </td>
							</tr>
							<tr>
							  <td align="center"><input name="sub_email" class="TextBox" type="text" value="<?=$_REQUEST['sub_email']?>">                              </td>
								<td align="center" valign="middle"><input name="sub_phone" class="TextBox" type="text" value="<?=$_REQUEST['sub_phone']?>">                                </td>
							</tr>          
							<tr>
								<td colspan="2" align="center" height="30" valign="middle">
									<input name="Submit Transaction" value="Find Subscription" class="Button" type="submit" >								</td>
							 </tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</form> 
</td></tr>
<tr><td>
<form name="FrmName1"  method="post" action="viewTransaction.php"> 
	<table style="border: 1px solid rgb(55, 108, 132);" align="left" cellpadding="0" cellspacing="0" width="400">
		<tbody>
			<tr>
				<td>
					<table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td style="border-bottom: 1px solid rgb(55, 108, 132);" align="left" bgcolor="#3d8287">
									<font style="font-family: verdana; font-size: 12px; color: white; font-weight: bold;">
										Search by User Information (2 of 3 fields required)
									</font> 
								</td>
							</tr>
						</tbody>
					</table>
					<table align="center" border="0" width="100%">
						<tbody>
							<tr>
								<td colspan="2" align="center">
									<font style="font-family: verdana; font-size: 11px; color: black;">
										Reference Number									</font>								</td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input name="reference_number" class="TextBox" type="text" value="<?=$_REQUEST['reference_number']?>">								</td>
							</tr>
							<tr>
							  <td width="50%" align="center"><font style="font-family: verdana; font-size: 11px; color: black;"> Email Address </font> </td>
								<td width="50%" align="center"><font style="font-family: verdana; font-size: 11px; color: black;"> Phone Number </font> </td>
							</tr>
							<tr>
							  <td align="center"><input name="email" class="TextBox" type="text" value="<?=$_REQUEST['email']?>">                              </td>
								<td align="center" valign="middle"><input name="phonenumber" class="TextBox" type="text" value="<?=$_REQUEST['phonenumber']?>">                                </td>
							</tr>          
							<tr>
								<td colspan="2" align="center" height="30" valign="middle">
									<input name="Submit Transaction" value="Find Transactions" class="Button" type="submit" >								</td>
							 </tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</form> 
</td></tr><tr><td>
<form name="FrmName2"  method="post" action="viewTransaction.php"> 
	<table style="border: 1px solid rgb(55, 108, 132);" align="left" cellpadding="0" cellspacing="0" width="400">
		<tbody>
			<tr>
				<td>
					<table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td style="border-bottom: 1px solid rgb(55, 108, 132);" align="left" bgcolor="#3d8287">
									<font style="font-family: verdana; font-size: 12px; color: white; font-weight: bold;">
										Search by Credit Card (both fields required)
									</font> 
								</td>
							</tr>
						</tbody>
					</table>
					<table align="center" border="0" width="100%">
						<tbody>
							<tr>
								<td width="50%" align="center">
									<font style="font-family: verdana; font-size: 11px; color: black;">
										Credit Card Number
									</font>
								</td>
								<td width="50%" align="center">
									<font style="font-family: verdana; font-size: 11px; color: black;">
									    Email Address									</font>								</td>
							</tr>
							<tr>
								<td align="center">
									<input name="CCnumber" class="TextBox" type="password" value="<?=$_REQUEST['CCnumber']?>">
								</td>
								<td align="center" valign="middle">
									<input name="email" class="TextBox" type="text" value="<?=$_REQUEST['email']?>"> 
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center" height="30" valign="middle">
									<input name="Submit Transaction" value="Find Transactions" class="Button" type="submit">
								</td>
							 </tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</form> 
</td></tr><tr><td>
<form name="FrmName3"  method="post" action="viewTransaction.php"> 
	<table style="border: 1px solid rgb(55, 108, 132);" align="left" cellpadding="0" cellspacing="0" width="400">
		<tbody>
			<tr>
				<td>
					<table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td style="border-bottom: 1px solid rgb(55, 108, 132);" align="left" bgcolor="#3d8287">
									<font style="font-family: verdana; font-size: 12px; color: white; font-weight: bold;">
										Search by Checking Account (both fields required)
									</font> 
								</td>
							</tr>
						</tbody>
					</table>
					<table align="center" border="0" width="100%">
						<tbody>
							<tr>
								<td width="50%" align="center">
									<font style="font-family: verdana; font-size: 11px; color: black;">
										Account Number
									</font>
								</td>
								<td width="50%" align="center">
									<font style="font-family: verdana; font-size: 11px; color: black;">
									    Bank Routing Number									</font>								</td>
							</tr>
							<tr>
								<td align="center">
									<input name="bankaccountnumber" class="TextBox" type="password" value="<?=$_REQUEST['bankaccountnumber']?>">
								</td>
								<td align="center" valign="middle">
									<input name="bankroutingcode" class="TextBox" type="text" value="<?=$_REQUEST['bankroutingcode']?>"> 
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center" height="30" valign="middle">
									<input name="Submit Transaction" value="Find Transactions" class="Button" type="submit">
								</td>
							 </tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</form> 
</td></tr>
</table>
</body>
</html>