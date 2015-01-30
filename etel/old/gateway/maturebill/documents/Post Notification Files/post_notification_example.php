<?
//variables sent
$ip						= $_POST['IPADDRESS'];
$subscription_id		= $_POST['subscription_id'])
$mt_reference_number	= $_POST['trans_id'];
$date					= $_POST['TRANSACTIONDATE'];
$dollar_amount			= $_POST['amount'];
$email					= $_POST['Ecom_ReceiptTo_Online_Email'];
$rebill_date			= $_POST['REBILLDATEEXT']
$first_name				= $_POST['Ecom_BillTo_Postal_Name_First'];
$last_name				= $_POST['Ecom_BillTo_Postal_Name_Last'];
$user_address			= $_POST['Ecom_BillTo_Postal_Street_Line1'];
$country_code			= $_POST['Ecom_BillTo_Postal_CountryCode']
$city					= $_POST['Ecom_BillTo_Postal_City'];
$state					= $_POST['Ecom_BillTo_Postal_StateProv'];
$zip_code				= $_POST['Ecom_BillTo_Postal_PostalCode'];
$country_code			= $_POST['Ecom_BillTo_Postal_CountryCode'];
$city					= $_POST['Ecom_BillTo_Postal_City'];
$state					= $_POST['Ecom_BillTo_Postal_StateProv'];
$username				= $_POST['USERNAME'];//not sent if declined
$password				= $_POST['PASSWORD'];//not sent if declined

//approved and what not codes
$event_id				= $_POST['event_id'];
$approved				= $_POST['APPROVED'];
$pending				= $_POST['PENDING'];

if($approved == 1)
{
	//do new sign up transaction/subscription stuff
}elseif($approved == 0 AND $pending ==1)
{
	//do pending new transaction/subscription stuff, will post approved or declined in about a week
}elseif($approved == 0 AND $pending ==0)
{
	//do declined new transaction/subscription stuff
}else{
//rebill events
  switch ($event_id)
  {
    case 2:
      $status= "rebill_success";
      break;
    case 4:
      $status= "canceled";//do not remove access until expiration
      break;
    case 8:
      $status= "refund";
      break;
    case 16:
      $status= "chargeback";
      break;
    case 128:
      $status= "expired";
      break;
    case 32:
      $status= "revoke";
      break;

    }
    //do rebill/cancelation stuff
}
?>