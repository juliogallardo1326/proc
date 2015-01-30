<?php
include '../includes/dbconnection.php';
include '../includes/function2.php';
require_once( '../includes/function.php');
$imonth =isset($HTTP_POST_VARS["cbo_from_month"])?$HTTP_POST_VARS["cbo_from_month"]:"";

$iyear =isset($HTTP_POST_VARS["cbo_from_year"])?$HTTP_POST_VARS["cbo_from_year"]:"";
$iday =isset($HTTP_POST_VARS["day"])?$HTTP_POST_VARS["day"]:"";
$icount=isset($HTTP_POST_VARS["hid_totalcount"])?$HTTP_POST_VARS["hid_totalcount"]:"";
for($iloop=1;$iloop<=$icount;$iloop++)
{	//echo "approval$iloop";
	
	$str_approvalstatus=isset($HTTP_POST_VARS['approval'.$iloop])?quote_smart($HTTP_POST_VARS['approval'.$iloop]):"";
	if($str_approvalstatus!="")
	{
	$str_invoiceid=isset($HTTP_POST_VARS['hid_invoiceid'.$iloop])?quote_smart($HTTP_POST_VARS['hid_invoiceid'.$iloop]):"";
	//echo $str_invoiceid."yy";
	$qry_update="select adminApproved from cs_invoicedetails where invoiceId='$str_invoiceid'";
	if(! $rst_approved=mysql_query($qry_update,$cnn_cs))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	$str_approved= $rst_approved[0];
	if($str_approved!=$str_approvalstatus)
	{
		func_update_single_field('cs_invoicedetails','adminApproved',$str_approvalstatus,'invoiceId',$str_invoiceid,$cnn_cs);
		$approvedstatusdate=func_get_current_date_time();
		func_update_single_field('cs_invoicedetails','approveddate',$approvedstatusdate,'invoiceId',$str_invoiceid,$cnn_cs);
	}
	
	}
	
}
?>
<html>
	<body onLoad="document.updating.submit()">
	<form name="updating" action="viewdetails.php"  method="POST">
	<input type='hidden' name='cbo_from_month' value='<?= $imonth?>'>
	<input type='hidden' name='cbo_from_year' value='<?= $iyear?>'>
	<input type='hidden' name='day' value='<?= $iday?>'>
	</form>
	</body>
</html>