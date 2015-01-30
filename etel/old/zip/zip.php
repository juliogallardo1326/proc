<?php
$d=date('d');
$timestart=microtime();

$op=system ("/usr/bin/mysqldump -u root -pWSD%780= dbs_companysetup | gzip > /var/www/html/abish.gz",$ret);
//$op=system ("WSD%780=");
// $op=system ('/user/bin/mysqldump mysql | gzip > sat.gz',$ret);
 echo $op; 
 echo $ret;
 //print "system ('\\mysql\bin\mysqldump mysql >./cmpny.$d.sql',$ret)";
//$timeend=microtime();
//$op=system ('\\mysql\bin\show databases;',$ret);
//$total=$timeend-$timestart;
//echo $total;

?>
