<?php
require_once( '../includes/function.php');
include '../includes/dbconnection.php';
include '../includes/function2.php'; 
$invoiceid =isset($HTTP_POST_VARS["invoiceid"])?$HTTP_POST_VARS["invoiceid"]:"";
 $appamount =isset($HTTP_POST_VARS["txt_appamt"])?$HTTP_POST_VARS["txt_appamt"]:"";
$decamt =isset($HTTP_POST_VARS["txt_decamt"])?$HTTP_POST_VARS["txt_decamt"]:"";
 $creditamt =isset($HTTP_POST_VARS["txt_creamt"])?$HTTP_POST_VARS["txt_creamt"]:"";
$chargeamt =isset($HTTP_POST_VARS["txt_charamt"])?$HTTP_POST_VARS["txt_charamt"]:"";
 $cancelamt =isset($HTTP_POST_VARS["txt_cancamt"])?$HTTP_POST_VARS["txt_cancamt"]:"";
$discamt =isset($HTTP_POST_VARS["txt_disamt"])?$HTTP_POST_VARS["txt_disamt"]:"";
$tranamt=isset($HTTP_POST_VARS["txt_tranamt"])?$HTTP_POST_VARS["txt_tranamt"]:"";
$reserveamt =isset($HTTP_POST_VARS["txt_resamt"])?$HTTP_POST_VARS["txt_resamt"]:"";
$creditrate=isset($HTTP_POST_VARS["txt_crerate"])?$HTTP_POST_VARS["txt_crerate"]:"";
$chargerate =isset($HTTP_POST_VARS["txt_charrate"])?$HTTP_POST_VARS["txt_charrate"]:"";
$disrate =isset($HTTP_POST_VARS["txt_disrate"])?$HTTP_POST_VARS["txt_disrate"]:"";
$transrate=isset($HTTP_POST_VARS["txt_tranrate"])?$HTTP_POST_VARS["txt_tranrate"]:"";
$reserverate=isset($HTTP_POST_VARS["txt_resrate"])?$HTTP_POST_VARS["txt_resrate"]:"";
  $miscadd =isset($HTTP_POST_VARS["txt_miscadd"])?$HTTP_POST_VARS["txt_miscadd"]:"";
 $miscsub =isset($HTTP_POST_VARS["txt_miscsub"])?$HTTP_POST_VARS["txt_miscsub"]:"";
$miscaddreason =isset($HTTP_POST_VARS["txt_miscadddisc"])?$HTTP_POST_VARS["txt_miscadddisc"]:"";
$miscsubreason =isset($HTTP_POST_VARS["txt_miscsubdesc"])?$HTTP_POST_VARS["txt_miscsubdesc"]:"";
 $premiscadd =isset($HTTP_POST_VARS["hid_miscadd"])?$HTTP_POST_VARS["hid_miscadd"]:"";
 $premiscsub=isset($HTTP_POST_VARS["hid_miscsub"])?$HTTP_POST_VARS["hid_miscsub"]:"";
  $pevnetamount=isset($HTTP_POST_VARS["hid_prevnet"])?$HTTP_POST_VARS["hid_prevnet"]:"";
 $netamt=isset($HTTP_POST_VARS["hid_netamt"])?$HTTP_POST_VARS["hid_netamt"]:"";
 $userid=isset($HTTP_POST_VARS["hid_userid"])?$HTTP_POST_VARS["hid_userid"]:"";
 $wirefee=isset($HTTP_POST_VARS["txt_wirefee"])?$HTTP_POST_VARS["txt_wirefee"]:"";
 $approvedstatusdate=isset($HTTP_POST_VARS["hid_gendate"])?$HTTP_POST_VARS["hid_gendate"]:"";
 //modified
 $rejcancelledamt=isset($HTTP_POST_VARS["txt_rejcancamt"])?$HTTP_POST_VARS["txt_rejcancamt"]:"";
 $rejtransfee=isset($HTTP_POST_VARS["txt_rejtransfee"])?$HTTP_POST_VARS["txt_rejtransfee"]:"";
 $rejcreditamt=isset($HTTP_POST_VARS["txt_rejcreditamt"])?$HTTP_POST_VARS["txt_rejcreditamt"]:"";
 $rejchargeback=isset($HTTP_POST_VARS["txt_rejchargebackamt"])?$HTTP_POST_VARS["txt_rejchargebackamt"]:"";
 //
 $rejtotal=$rejcancelledamt+$rejtransfee+$rejcreditamt+$rejchargeback;
 $miscsub+=$premiscsub;
 $miscadd+=$premiscadd;
$deduction=$chargeamt+$creditamt+$discamt+$tranamt+$reserveamt+$cancelamt+$miscsub;
 $netamount=$appamount-$deduction+$miscadd+$rejtotal;


		if($pevnetamount < 500){
			 $netamount +=$pevnetamount;
			
		}//if net amount greater than 500 deduce the wire fee from netamount else make the wire fee zero.
		if($netamount>=500){
		if($wirefee=="" or $wirefee==0 ){
			$wirefee=50;
			}
			$netamount-=$wirefee;
			
		}
		else
		$wirefee=0;
		$qry_update="Update cs_invoicedetails set approvedAmt='$appamount',declinedAmt='$decamt',credit='$creditamt',chargeBack='$chargeamt',creditAmt='$cancelamt',discount='$discamt',transactionFee='$tranamt',reserveFee='$reserveamt',miscadd='$miscadd',miscsub='$miscsub',miscadd_disc='$miscaddreason',miscsub_disc='$miscsubreason',totalDeductions='$deduction',netAmount='$netamount',wirefee=$wirefee,reject_amt=$rejcancelledamt,reject_creditamt=$rejcreditamt,reject_chargebackamt=$rejchargeback,reject_transfee=$rejtransfee where invoiceId ='$invoiceid'";
if(! $rst_update=mysql_query($qry_update,$cnn_cs))
{
print(mysql_errno().": ".mysql_error()."<BR>");
	print("Cannot execute update query");
	exit();
}

if	($netamount <500)
{
//if the previous net amount is greater than 500 then the new net amount will as such added to the the transaction else the diff in the net amount will be added
if($netamount!=$netamt){
if(	$netamt >500)
{	
	$diffamt=$netamount;
}
else
{
	$diffamt=$netamount - $netamt;
}
  $qry_select="Select netAmount,invoiceId from cs_invoicedetails where  generateddate >' $approvedstatusdate'  and userId ='$userid'  ";

if(!$rst_invoice = mysql_query($qry_select,$cnn_cs))
{
	print("Cannot execute query");
	exit();
}

else
{
	$num=mysql_num_rows($rst_invoice);
	
	for($iloop=1;$iloop<=$num;$iloop++)
	{
		$rst_amount=mysql_fetch_array($rst_invoice);
		$futnetamount=$rst_amount[0];
		 $invoiceid=$rst_amount[1];
		$futnetamount+=$diffamt;
		func_update_single_field('cs_invoicedetails','netAmount',$futnetamount,'invoiceId',$invoiceid,$cnn_cs);
		if($futnetamount >500)
		{
			break;
		}
		
	}
	
}

}
}	
//exit();

?>
<html>
<head>
<title>
<?=$_SESSION['gw_title']?>
</title>
<script type="text/javascript">
function closewindow()
{

window.opener.location.reload();
  self.close()
}
</script>
</head>
<body onLoad="closewindow()">

</body>
</html>