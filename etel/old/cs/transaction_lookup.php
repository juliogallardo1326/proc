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

<?
if(empty($_POST) AND empty($_GET))
{
	echo display_menu();
}elseif(strlen($_REQUEST[action])==0)
{
	$results = $lookup->search($_REQUEST);
		$num_results =  count($results);

	if(is_array($results)){
		if($num_results>0){
			echo "<center><big><big>$num_results Purchases Found</big></big></center>";
			foreach ($results as $outer_key => $row) {			
				$ss_ID					=trim($row['ss_ID']);
				$reference_number		=$row['reference_number'];
				$transactionId			=$row['transactionId'];
				$ss_rebill_status		=$row['ss_rebill_status'];
				$transactionDate 		=date('l F d, Y', strtotime($row['transactionDate']));
				$amount			 		=$row['amount'];
				$productdescription 	=$row['productdescription'];	
				$cs_URL					=$row['cs_URL'];
				$ss_subscription_ID		=$row['ss_subscription_ID'];
								
				if($ss_ID != $last_ss_ID AND strlen($ss_ID) >0)
				{
?>
<h2>		
	<a href="<?=$URL ?>?action=view_subscription&ss_subscription_ID=<?=$ss_subscription_ID ?>"><?=$ss_subscription_ID . " Status: ". $ss_rebill_status?></a>
</h2>
<h3 class="pos_right">
	<?=strlen($productdescription)>0? "$productdescription" :"" ?> <br>FROM <?=$cs_URL ?>
</h3>
<?				
					$last_ss_ID= $ss_ID;
				}elseif(strlen(trim($ss_ID)) ==0){
?>
<h2>		
		<a href="<?=$URL . "?action=view_transaction&reference_number=$reference_number"?>"><?=$reference_number. " for $".  $amount?></a>
</h2>
<h3 class="pos_right">
		<?=strlen($productdescription)>0? "$productdescription" :"" ?> <br>FROM <?=$cs_URL ?>
</h3>
<?
				}				
				if($ss_ID !=0){
?>
<h2 class="pos_right">
<a href="<?=$URL . "?action=view_transaction&reference_number=$reference_number"?>"><?=$reference_number ?></a> Billed on:		<?=$transactionDate. " for $".  $amount?>
</h2>
<? 
				}
				$last_ss_ID = $ss_ID;
			}
			$dis_phone_number = 1;//done with the display of all of the resutls
		}else{
			$msg = "No results found";
			echo display_menu($msg);
		}
	}else{
			$msg = "Error; Not Enough Information";
			echo display_menu($msg);
	}
}elseif($_REQUEST[action]=="view_subscription"){
	$ss_subscription_ID= quote_smart($_REQUEST[ss_subscription_ID]);
	if($_SESSION['no_more_where'] != "true"){
		$where=	$_SESSION['where']. " AND sub.`ss_subscription_ID` = '$ss_subscription_ID'";
	}else{
		$where=	$_SESSION['where'];
	}
	$results = $lookup->find_transaction_query($where);
//	$row=mysql_fetch_array($results);
	foreach ($results as $outer_key => $row) {			
		$_SESSION['no_more_where'] = "true";
	
		$report_data["Customer Information"]["Name"] 					= $row['name'] ." ". $row['surname'];
		$report_data['Customer Information']['Address'] 				= $row['address'];
		$report_data['Customer Information']['Phone'] 					= $row['phonenumber'];
	
		$report_data['Payment Information']['Name on billing statement']= $row['bankname'];
		$report_data['Payment Information']['Last Billing'] 			= date('l F d, Y', strtotime($row['ss_last_rebill']));
		$report_data['Payment Information']['First Billing'] 			= date('l F d, Y', strtotime($row['ss_account_start_date']));
	
	
		if(strlen($row[td_username])>0)
		{
			$report_data['Subscription Information']['Username'] = $row[td_username];
			$report_data['Subscription Information']['Password'] = $row[td_password];
		}	
		$report_data['Subscription Information']['Status'] = $row[ss_rebill_status];
		if(strlen($row[td_username])>0)
		{
			$report_data['Merchant Information']['Phone Number'] = $row[cs_support_phone];
	
		}
		$report_data['Merchant Information']['Website URL'] = $row[cs_URL];
		$report_data['Merchant Information']['Email'] = $row[cs_support_email];
		$report_data['Subscription Information']['Subscription ID'] = $row[ss_subscription_ID];
	
		if($row[ss_rebill_status]=="active"){
			$report_data['Payment Information']['Will Rebill for '] 			= "$".$row['ss_rebill_amount '];
			$report_data['Payment Information']['Next Billing Date'] 		= date('l F d, Y', strtotime($row['ss_rebill_next_date']));
			$report_data['CANCEL SUBSCRIPTION']['Reason'] = 
				'<form  method="get" action "'.$URL.'" ><input type="hidden" name="action" value= "cancel"> <input type="hidden" name="ss_subscription_ID" value="'.$row[ss_subscription_ID].'">
	
				<select name="ss_rebill_status_text">
					<option value="Cant get in touch with Merchant (.Net Refund)">Cant get in touch with Merchant</option>
					<option value="Changed Mind (.Net Cancel)" selected="selected">Changed Mind</option>
					<option value="Fraudulent (.Net Cancel)">Fraudulent </option>
					<option value="Spouse (.Net Cancel)">Spouse</option>
					<option value="Did not recieve Product/Package (.Net Cancel)">Did not recieve Product/Package</option>
					<option value="Other (.Net Cancel)">Other</option>
				  </select>';
			$report_data['CANCEL SUBSCRIPTION']['If Other'] = 
				'<TEXTAREA cols="23" rows="3" name="ss_account_notes" WRAP=SOFT></TEXTAREA> <br>';
			$report_data['CANCEL SUBSCRIPTION'][' '] = 
						'<INPUT TYPE="submit" name="subscription_reason" VALUE="Deactivate Subscription"></form>';
		}else{
			$report_data['CANCEL SUBSCRIPTION']['Cancelation Refference'] = $row[cancel_refer_num];	
			$report_data['CANCEL SUBSCRIPTION']['Cancelation Date'] = date('l F d, Y', strtotime($row['cancellationDate']));	
	
		}
	}
	echo print_multdimension_report($report_data);
			$dis_phone_number = 1;}elseif($_REQUEST[action]=="view_transaction"){
	$reference_number= quote_smart($_REQUEST[reference_number]);
	if($_SESSION['no_more_where'] != "true"){
		$where=	$_SESSION['where']. " AND td.`reference_number` = '$reference_number'";
	}else{
		$where=	$_SESSION['where'];
	}
//	$results = $lookup->find_transaction_query($where);
//	$row=mysql_fetch_array($results);
	$results = $lookup->find_transaction_query($where);
	foreach ($results as $outer_key => $row) {			
		$_SESSION['no_more_where'] = "true";
	
		$report_data["Customer Information"]["Name"] 					= $row['name'] ." ". $row['surname'];
		$report_data['Customer Information']['Address'] 				= $row['address'];
		$report_data['Customer Information']['Phone'] 					= $row['phonenumber'];
		if($row[checkorcard]=="H"){
			$report_data['Payment Information']['Charge Type'] ="Credit Card";
		}elseif($row[checkorcard]=="C"){
			$report_data['Payment Information']['Charge Type'] ="Check";	
		}elseif($row[checkorcard]=="W"){
			$report_data['Payment Information']['Charge Type'] ="Wallet";	
		}
		$report_data['Payment Information']['Billed on'] 			= date('l F d, Y', strtotime($row['transactionDate']));
		$report_data['Payment Information']['Amount'] 				= "$". $row['amount'];
		$report_data['Payment Information']['Refference ID'] 			=$row['reference_number'];//productdescription
		$report_data['Payment Information']['Product Description'] 			=$row['productdescription'];//productdescription
	
		$report_data['User Information']['Username'] = $row[td_username];
		$report_data['User Information']['Password'] = $row[td_password];
	
		$report_data['Merchant Information']['Website URL'] = $row[cs_URL];
		$report_data['Merchant Information']['Phone Number'] = $row[cs_support_phone];
		$report_data['Merchant Information']['Email'] = $row[cs_support_email];
		$report_data['Subscription Information']['Subscription ID'] = $row[ss_subscription_ID];
	
		if(strlen(trim($row[note_id]))>0)
		{
			$report_data['REQUEST REFUND  FOR '.$reference_number]['Refund already requested'] = "&nbsp;".$row[customer_notes];
		}elseif(strlen(trim($row[note_id]))==0 AND ($row[ss_ID]=="" OR $row[transactionId]==$row[ss_transaction_id])){
			$report_data['REQUEST REFUND  FOR '.$reference_number]['Contact me with'] = 
				'<form  method="get" action "'.$URL.'" >
			<input type="hidden" name="action" value= "request_refund">
			<input type="hidden" name="reference_number" value="'.$reference_number.'">
	
			<select name="contactmethod">
				<option value="Email" selected>Email</option>
				<option value="Phone">Phone</option>
				<option value="Other">Other </option>
			</select>';
			
			
			$report_data['REQUEST REFUND  FOR '.$reference_number]['Reason (required)'] = 
				'<TEXTAREA cols="23" rows="3" NAME="customer_notes" WRAP=SOFT></TEXTAREA> </TEXTAREA>';
			$report_data['REQUEST REFUND  FOR '.$reference_number][' '] = 
						'<input  type="submit"  value="REQUEST REFUND"></form>';
		}
		echo print_multdimension_report($report_data);
				$dis_phone_number = 1;
	}
}elseif($_REQUEST[action]=="request_refund"){
	$reference_number= quote_smart($_REQUEST['reference_number']);
	$customer_notes = quote_smart(trim($_REQUEST['customer_notes']));
	$contactmethod = quote_smart($_REQUEST['contactmethod']);
	
	if($_SESSION['no_more_where'] != "true"){
		$where=	$_SESSION['where']. " AND sub.`ss_subscription_ID` = '$ss_subscription_ID'";
	}else{
		$where=	$_SESSION['where'];
	}

///////////////////////////////////bad
//	$results = $lookup->find_transaction_query($where);
//	$row=mysql_fetch_array($results);	
///////////////////////////////////bad

	$results = $lookup->find_transaction_query($where);
	foreach ($results as $outer_key => $row) {			
		$_SESSION['no_more_where'] = "true";
		
		$transactionId = $row['transactionId'];
		$sql="INSERT INTO `cs_callnotes` (`transaction_id` , `call_date_time` , `service_notes` , `cancel_status` , `customer_notes` , `solved` , `is_bill_date_changed` , `call_duration` , `customer_service_id` , `prev_bill_date` , `dnc`, `cn_type`, `cn_contactmethod` )
				VALUES ('$transactionId', NOW() , 'User Requests Refund', '', '$customer_notes', '' , '', '', '', '', '', 'refundrequest', '$contactmethod');";
		sql_query_write("$sql");
		
		$ss_subscription_ID = $row['ss_subscription_ID'];
		if($row[ss_rebill_status]=="active")
		{
			subscription_cancel($ss_subscription_ID,NULL,$ss_account_notes,$ss_rebill_status_text);
		}
		echo "<big><big><big><center>Processing...</big></big></big></center>";		
	}
}elseif($_REQUEST[action]=="cancel"){
	$reference_number= quote_smart($_REQUEST['reference_number']);
	$ss_account_notes = quote_smart(trim($_REQUEST['ss_account_notes']));
	$ss_rebill_status_text = quote_smart($_REQUEST['ss_rebill_status_text']);
	if($_SESSION['no_more_where'] != "true"){
		$where=	$_SESSION['where']. " AND td.`reference_number` = '$reference_number'";
	}else{
		$where=	$_SESSION['where'];
	}
	$results = $lookup->find_transaction_query($where);
	foreach ($results as $outer_key => $row) {			
		$_SESSION['no_more_where'] = "true";
		
		$ss_subscription_ID = $row['ss_subscription_ID'];
		if($row[ss_rebill_status]=="active" AND strlen($ss_subscription_ID)>0)
		{
			subscription_cancel($ss_subscription_ID,NULL,$ss_account_notes,$ss_rebill_status_text);
		}else{
			echo "error";
		}
		echo "<big><big><big><center>Processing...</big></big></big></center>";
	}
}

if($dis_phone_number)
	echo "<center><big><big>For other assistance call <br>1-800-923-8168</big></big></center>";


?>
</body>


	
	
	
<?	
//////////////////////////////functions ///////////////////////////////////////
function print_multdimension_report($report_data){
?>
<table style="border: 1px solid black;" dwcopytype="CopyTableCell" align="center" cellpadding="2" cellspacing="0" width="95%">
	<tbody>
<?
	foreach ($report_data as $outer_key => $single_array) {
		foreach ($single_array as $inner_key => $value) {
			if($outer_key!=$last_outer_key AND strlen($report_data[$outer_key][$inner_key])>0){
?>
		<tr align="center" bgcolor="#3d8287" valign="middle">
			<td colspan="2" class="tdbdr">
				<font color="#ffffff" face="Verdana, Arial, Helvetica, sans-serif"  size="2">
					<strong>
						<?=$outer_key ?>
					</strong>
				</font>
			</td>
		</tr>
<?
			}if(strlen($report_data[$outer_key][$inner_key])>0){
?>
		<tr>
			<td class="tdbdr1" align="right" valign="middle" width="50%">
				<font color="#000000" face="Verdana, Arial, Helvetica, sans-serif" size="2">
					<?=$inner_key ?> <?= strlen(trim($inner_key))>0? ":" : " " ?> 
				</font>
			</td>
			<td class="tdbdr" valign="middle" width="50%">
				<font color="#001188">
					&nbsp; 	<?=$report_data[$outer_key][$inner_key] ?>
				</font>
			</td>
    </tr>
<?		
			}
			$last_outer_key= $outer_key;
		}
	}
	echo"</tbody></table>";
	
}


function display_menu($msg="")
{
if(strlen($msg)>0)
	$menu = "<center><big><big>$msg</big></big></center>";
	
$menu .=
'
<table>
<tr><td>
<form name="FrmName1"  method="post"> 
	<table style="border: 1px solid rgb(55, 108, 132);" align="left" cellpadding="0" cellspacing="0" width="400">
		<tbody>
			<tr>
				<td>
					<table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%">
						<tbody>
							<tr>
								<td style="border-bottom: 1px solid rgb(55, 108, 132);" align="left" bgcolor="#3d8287">
									<font style="font-family: verdana; font-size: 12px; color: white; font-weight: bold;">
										Search by User Information (2 of 4 fields required)
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
										Reference Number
									</font>
								</td>
								<td width="50%" align="center">
									<font style="font-family: verdana; font-size: 11px; color: black;">
										Phone Number
									</font>
								</td>
							</tr>
							<tr>
								<td align="center">
									<input name="reference_number" class="TextBox" type="text">
								</td>
								<td align="center" valign="middle">
									<input name="phonenumber" class="TextBox" type="text"> 
								</td>
							</tr>
							<tr>
								<td width="50%" align="center">
									<font style="font-family: verdana; font-size: 11px; color: black;">
										Subscription ID
									</font>
								</td>
								<td width="50%" align="center">
									<font style="font-family: verdana; font-size: 11px; color: black;">
										Email Address
									</font>
								</td>
							</tr>
							<tr>
								<td align="center">
									<input name="subscription_ID" class="TextBox" type="text">
								</td>
								<td align="center">
									<input name="email" class="TextBox" type="text">
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
<form name="FrmName2"  method="post"> 
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
										and Email Address
									</font>
								</td>
							</tr>
							<tr>
								<td align="center">
									<input name="CCnumber" class="TextBox" type="text">
								</td>
								<td align="center" valign="middle">
									<input name="email" class="TextBox" type="text"> 
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
<form name="FrmName3"  method="post"> 
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
										and Bank Routing Number
									</font>
								</td>
							</tr>
							<tr>
								<td align="center">
									<input name="bankaccountnumber" class="TextBox" type="text">
								</td>
								<td align="center" valign="middle">
									<input name="bankroutingcode" class="TextBox" type="text"> 
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
';
return($menu);
}
?>