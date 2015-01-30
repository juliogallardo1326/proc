<?php


$etel_disable_https = 1;
chdir("..");
$gateway_db_select = 3;
include("includes/dbconnection.php");

$batch_path = "admin/batch";

$ftp_array = 
array( 
	array(
	'name'=>'Amerinet Reconciliation','folder'=>'amerinet','domain'=>'securelink02.telcash.com', 'port'=>22, 'user'=>'Etelegate', 'pass'=>'2t1aalt5'
	)
);


if(!is_dir("$batch_path"))
 mkdir("$batch_path",0700);
 
if(!is_dir("$batch_path/inc/"))
 mkdir("$batch_path/inc/",0700);


foreach($ftp_array as $ftp)
{
	$log = "Ftp Synchronization for ".$ftp['name'].":\n";
	$ftp_full = $ftp['user'].":".$ftp['pass']."@".$ftp['domain'].":".$ftp['port'];
	$log .= " Connecting to ".$ftp_full.".\n";
	
	$conn_id = ssh2_connect($ftp['domain'],$ftp['port']);
	if($conn_id) 
	{
		$login_result = ssh2_auth_password($conn_id, $ftp['user'], $ftp['pass']);
		$sftp = ssh2_sftp($conn_id);
		
		$dirlist = opendir("ssh2.sftp://".$ftp_full."/");
          
        while( ($file = readdir($dirlist)) !== false)
        	$file_list[] = $file;
		
		foreach($file_list as $filename)
		{
			$log .= " Found ".$filename.".\n";
			if($filename != '..' && $filename != '.')
			{
				$filenamepath = "$batch_path/inc/".$ftp['folder']."/".$filename;
				if(!is_dir("$batch_path/inc/".$ftp['folder']."/"))
				 mkdir("$batch_path/inc/".$ftp['folder']."/",0700);
				if(!file_exists($filenamepath))
				{
					if(!file_exists($filenamepath.'.done'))
					{
						copy("ssh2.sftp://".$ftp_full."/".$filename, $filenamepath);
						$log .= "  Wrote ".$filenamepath.".\n";
					}
					else
						$log .= "  File Already Processed: ".$filenamepath.".done.\n";
				}
				else
					$log .= "  File Exists: ".$filenamepath.".\n";
				
			}
			else
				$log .= "  Ignored ".$filename.".\n";
		}
	}
	else $log .= " Failed to Connect.\n";
	
	toLog('misc','system', $log);
}






?>