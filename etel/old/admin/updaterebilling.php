<?php

include("includes/sessioncheck.php");
$headerInclude="transactions";
$count = (isset($HTTP_GET_VARS["count"])?quote_smart($HTTP_GET_VARS["count"]):"");
if ($count!=""){
for($i=1;$i<=$count;$i++){
$ischecked = (isset($HTTP_GET_VARS["check".$i])?quote_smart($HTTP_GET_VARS["check".$i]):"0");
$trans_id=$ischecked;
if ($ischecked!=0){
$str_qry="update cs_rebillingdetails set recur_times =-1 where rebill_transactionid =$trans_id";
//echo $str_qry."<BR>";
$qry_res= mysql_query($str_qry);
}//chkd==1
}
}//cnt!=0
header ("Location:rebillinglist.php");
?>