<?php

chdir("..");
$gateway_db_select = 3;
include("includes/dbconnection.php");

$batch_path = "admin/batch/inc/amerinet/";

$dirlist = opendir($batch_path);
  
while( ($file = readdir($dirlist)) !== false)
	$file_list[] = $file;

$log .= " Reconciliation for Amerinet.\n";
	
foreach($file_list as $filename)
{
	$approve_affected_rows=0;
	$return_affected_rows=0;
	if($filename != '..' && $filename != '.')
	{
	
		$type = '';
		if(strpos($filename,'F.txt')) $type = 'Shippable';
		if(strpos($filename,'A.txt')) $type = 'Submits';
		if(strpos($filename,'R.txt')) $type = 'Returns';
		if(strpos($filename,'.done')) $type = '';
		
		if($type)
		{
			$log .= " Found $type:".$filename.".\n";
			$filenamepath = $batch_path.$filename;
			
			
			switch($type)
			{
				case "Shippable":
					$handle = fopen($filenamepath, "r");	
					while (($data = fgetcsv($handle, 1000, ",",'"')) !== FALSE) {
						$sql = NULL;
						//print_r($data); die();
						//Array ( [0] => SHIPITEM [1] => 05/15/2006 [2] => 8090 [3] => 140331294 [4] => CD18A9DC1DC7C86 [5] => [6] => BARNETT [7] => DAVID [8] => 1 [9] => 26260659 [10] => 9.95 [11] => 0 [12] => [13] => [14] => [15] => [16] => )
						if($data[0]=='SHIPITEM')
						{
							$sql = "update cs_transactiondetails set ";
							$sql.= "billingDate ='".date('Y-m-d',strtotime($data[1]))."',"; //Shippable Date
							//$sql.= "='".$data[2]."'"; //Star Code
							$sql.= "td_bank_transaction_id='".$data[3]."', "; //Transaction ID
							//$sql.= "='".$data[4]."'"; //Userdef
							//$sql.= "='".$data[5]."'"; //Pin Number
							//$sql.= "='".$data[6]."'"; //Last Name
							//$sql.= "='".$data[7]."'"; //First Name
							//$sql.= "='".$data[8]."'"; //Current Cycle
							//$sql.= "='".$data[9]."'"; //Debit ID
							//$sql.= "='".$data[10]."'"; //Amount
							//$sql.= "='".$data[11]."'"; //Address Verified
							$sql.= "status='A' "; //Approval
		
							$sql.= "Where reference_number='".$data[4]."' and cardtype = 'Check' Limit 1";
							
							$result = sql_query_read($sql) or dieLog($sql);
							$success = mysql_affected_rows();
							if($success) $approve_affected_rows++;
						}
					}
					rename($filenamepath,$filenamepath.'.done');
					break;
				case "Submits":
					$handle = fopen($filenamepath, "r");
					//while (($data = fgetcsv($handle, 1000, ",",'"')) !== FALSE) {
					//	
					//}
					break;
				case "Returns":
					$handle = fopen($filenamepath, "r");	
					while (($data = fgetcsv($handle, 1000, ",",'"')) !== FALSE) {
						$sql = NULL;
						//print_r($data); die();
						//Array ( [0] => 05/10/2006 [1] => 7074 [2] => D [3] => R10 [4] => 19.95 [5] => HIBBARD [6] => ANDREW [7] => 139207092 [8] => 179146623 [9] => [10] => F [11] => F [12] => F [13] => )
	
						$sql = "update cs_transactiondetails set ";
						$sql.= "billingDate ='".date('Y-m-d',strtotime($data[0]))."',"; //Return Date
						//$sql.= "='".$data[1]."'"; //Star Code
						//$sql.= "='".$data[2]."'"; //Debit / Credit
						$sql.= "td_process_msg='".$data[3]."',"; //Return Reason
						//$sql.= "='".$data[4]."'"; //Amount
						//$sql.= "='".$data[5]."'"; //Last Name
						//$sql.= "='".$data[6]."'"; //First Name
						$sql.= "td_bank_transaction_id='".$data[7]."',"; //Transaction ID
						//$sql.= "='".$data[8]."'"; //Userdef
						//$sql.= "='".$data[9]."'"; //Pin Number
						//$sql.= "='".$data[10]."'"; //Duplicate
						//$sql.= "='".$data[11]."'"; //Late
						//$sql.= "='".$data[12]."'"; //Mismatch
						//$sql.= "='".$data[13]."'"; //Correction Data / Comments
						$sql.= "status='D' "; //Approval
	
						$sql.= "Where reference_number='".$data[8]."' and cardtype = 'Check' Limit 1";
						$result = sql_query_read($sql) or dieLog(mysql_error(). "~".$sql);
						$success = mysql_affected_rows();
						if($success) $return_affected_rows++;
					}
					rename($filenamepath,$filenamepath.'.done');
					break;
	
			}
			$log .= " ($return_affected_rows) Returns, ($approve_affected_rows) Approves.\n";
		}
	}
}
toLog('misc','system', $log);
?>