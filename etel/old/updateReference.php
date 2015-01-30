<?php 
require_once("includes/dbconnection.php");
include 'includes/function2.php';
require_once('includes/function.php');
$no_fields=0;
$qry_empty_reference="SELECT transactionId,reference_number FROM cs_transactiondetails ";
$rst_select = mysql_query($qry_empty_reference,$cnn_cs);
$i_count=mysql_num_rows($rst_select);
$updateSuccess="";
$a=0;
//echo($i_count."<BR>");
	for($i=0;$i<$i_count;$i++){
			
			$resultSet=mysql_fetch_array($rst_select);
			$transaction_no=$resultSet['transactionId'];
			$reference_number=$resultSet['reference_number'];
			
			if($reference_number==""){
				//echo($transaction_no."<BR>");
				$a=$a+1;
				$ref_num=func_Trans_Ref_No($transaction_no);
			  	$updateSuccess=func_update_single_field('cs_transactiondetails','reference_number',$ref_num,'transactionId',$transaction_no,$cnn_cs);
				if($updateSuccess==0){
					echo("cannot update"."<BR>");
					exit();
				}
			}	
   	}
	if($updateSuccess==1){
		echo($a."   Transactions are updated in the table"."<BR>");
	}else{
		echo("NO REFERENCE FIELDS ARE EMPTY TO UPDATE");
	}
	 

?>