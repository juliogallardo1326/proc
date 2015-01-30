<?php
require_once("includes/dbconnection.php");
require_once('includes/function.php');
$count="1";
$tr_id 			= (isset($HTTP_GET_VARS['tr_id'])?trim($HTTP_GET_VARS['tr_id']):"");
$tr_amount 		= (isset($HTTP_GET_VARS['tr_amount'])?trim($HTTP_GET_VARS['tr_amount']):"");
$scandorder_id	= (isset($HTTP_GET_VARS['scandorder_id'])?trim($HTTP_GET_VARS['scandorder_id']):"");
$tr_success 	= (isset($HTTP_GET_VARS['tr_success'])?trim($HTTP_GET_VARS['tr_success']):"");
$tr_result 		= (isset($HTTP_GET_VARS['tr_result'])?trim($HTTP_GET_VARS['tr_result']):"");
$checksum 		= (isset($HTTP_GET_VARS['checksum'])?trim($HTTP_GET_VARS['checksum']):"");
	
$str	=	$scandorder_id.$tr_id.$tr_amount."v7iTT5yq6_66eQ".$tr_success."  ".$tr_result ;

$qry ="select transactionId from cs_scanorder where transactionId ='$tr_id '";
$reslut =mysql_query($qry,$cnn_cs);
$count =mysql_num_rows($reslut);
if( $count==0){
	$qry="insert into cs_scanorder (transactionId,amount,transactionStatus,declineReason,checkSum,scanOrderId)values ('$tr_id','$tr_amount ','$tr_success ','$tr_result','$checksum','$scandorder_id')";
	$res= mysql_query($qry,$cnn_cs);
}
?>
<script> 
window.close();
</script>
