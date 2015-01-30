<?php 
require_once("includes/dbconnection.php");
include 'includes/function2.php';
require_once('includes/function.php');

$no_fields=0;
$qry_empty_reference="SELECT userId,ReferenceNumber FROM cs_companydetails ";
$rst_select = mysql_query($qry_empty_reference,$cnn_cs);
$i_count=mysql_num_rows($rst_select);
$updateSuccess="";
$a=0;
//echo($i_count."<BR>");
	for($i=0;$i<$i_count;$i++){
			
			$resultSet=mysql_fetch_array($rst_select);
			$userid=$resultSet['userId'];
			$user_reference_number=$resultSet['ReferenceNumber'];
			
			if($user_reference_number==""){
				//echo($transaction_no."<BR>");
				$a=$a+1;
				$ref_num=func_User_Ref_No($userid);
			  	$updateSuccess=func_update_single_field('cs_companydetails','ReferenceNumber',$ref_num,'userId',$userid,$cnn_cs);
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