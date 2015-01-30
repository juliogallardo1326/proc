<?php
$d=date('d');
$timestart=microtime();

//$op=system ("\\mysql\bin\mysqldump.exe dbs_companysetup > ..\projects\cmpny.$d.sql",$ret);
 $op=system ('/user/bin/mysqldump mysql | gzip > sat.gz',$ret);
 echo $op; 
$timeend=microtime();
//$op=system ('\\mysql\bin\show databases;',$ret);
$total=$timeend-$timestart;
echo $total;

?>
