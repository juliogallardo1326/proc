<?php
require_once("includes/dbconnection.php");

$Sql_select_qrt = "Select count(*),phonenumber from cs_transactiondetails group by phonenumber";

$show_select_Sql= mysql_query($Sql_select_qrt);
while($show_select =  mysql_fetch_array($show_select_Sql))
{
	if ($show_select[0]>1){
		
		$sql_phone = "Select transactionId,name,surname,phonenumber,checkorcard,amount,userid,passStatus,status,cancelstatus,voiceAuthorizationno,transactionDate from cs_transactiondetails where phonenumber='$show_select[1]'";
		$show_sql_phone = mysql_query($sql_phone);
		while($show_selectsql_phone =  mysql_fetch_array($show_sql_phone))
		{
			echo "Transaction Id - $show_selectsql_phone[0]<br>";
			echo "First Name - $show_selectsql_phone[1]<br>";
			echo "Last Name - $show_selectsql_phone[2]<br>";
			echo "Telephone #- $show_selectsql_phone[3]<br>";
			if($show_selectsql_phone[4] == "C"){
				$Corc = "Check";
			}else {
				$Corc = "Credit Card";
			}
			echo "Check or Card - $Corc<br>";
			echo "Amount - $show_selectsql_phone[5]<br>";
		//	echo "UserID - $show_selectsql_phone[6]<br>";
			if($show_selectsql_phone[7]=="PA") {
				$voicestat="Passed";
			}else if($show_selectsql_phone[7]=="PE"){
				$voicestat="Pending";
			}else if($show_selectsql_phone[7]=="ND"){
				$voicestat="Cancelled";
			}else if($show_selectsql_phone[7]=="NP"){
				$voicestat="Not Passed";
			}
			echo "Voice Status - $voicestat<br>";
			
			if($show_selectsql_phone[8]=="P") {
				$stat = "";
			}else if($show_selectsql_phone[8]=="A"){
				$stat = "Approved";
			}else if($show_selectsql_phone[8]=="D"){
				$stat = "Declined";
			}
			echo "Status - $stat<br>";
			if($show_selectsql_phone[9]=="Y") {
				$cancel ="Cancelled";
			} else{
				$cancel = "";
			}
			echo "Cancelled - $cancel<br>";
			echo "Voice Authorization # - $show_selectsql_phone[10]<br>";
			echo "Transaction Date - $show_selectsql_phone[11]<br><br>";
		}
	}
}
?>