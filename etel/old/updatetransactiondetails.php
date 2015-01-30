<?php
require_once("includes/dbconnection.php");
$qry_userid="select distinct(userId) from cs_transactiondetails";
if(!$rst_userid=mysql_query($qry_userid,$cnn_cs))
{
print("<br>Can not execute select query");
		exit();
}
else
{

$i_num=mysql_num_rows($rst_userid);
for($i_loop=1;$i_loop<=$i_num;$i_loop++)
{
	$rst_id=mysql_fetch_array($rst_userid);
	$userid=$rst_id[0];
	$qry_rate="select reseller_discount_rate ,total_discount_rate, chargeback ,credit,transactionfee ,reserve,merchant_discount_rate ,total_trans_fees,reseller_trans_fees ,discountrate,merchant_trans_fees from cs_companydetails where userId='$userid'";
	if(!$rst_rate=mysql_query($qry_rate,$cnn_cs))
	{
		print("<br>Can not execute select query");
		exit();	
	}
	else
	{
		$rst_ratedetails=mysql_fetch_array($rst_rate);
		$resellerdisrate=$rst_ratedetails[0];
		$totdiscrate=$rst_ratedetails[1];
		$chargeback=$rst_ratedetails[2];
		$credit=$rst_ratedetails[3];
		$transfee=$rst_ratedetails[4];
		$reserve=$rst_ratedetails[5];
		$merchantdisrate=$rst_ratedetails[6];
		$totaltransfee=$rst_ratedetails[7];
		$resellertransfee=$rst_ratedetails[8];
		$discountrate=$rst_ratedetails[9];
		$merchanttransfee=$rst_ratedetails[10];
		$qry_update="update cs_transactiondetails set r_reseller_discount_rate='$resellerdisrate',r_total_discount_rate='$totdiscrate',r_chargeback='$chargeback',r_credit='$credit',r_transactionfee='$transfee',r_reserve='$reserve',r_merchant_discount_rate='$merchantdisrate',r_total_trans_fees='$totaltransfee',r_reseller_trans_fees='$resellertransfee',r_discountrate='$discountrate',r_merchant_trans_fees='$merchanttransfee' where userId=$userid";
		if(! $rst_update=mysql_query($qry_update,$cnn_cs))
		{
		print("Cannot execute update query");
		exit();
		}
	}
}
}
 ?>