<?php

chdir("..");
$gateway_db_select = 3;
//$etel_debug_mode = 1;
$etel_disable_https = 1;
include("includes/dbconnection.php");
$timelimit = 60;
$time = time();
for($i=0;$i<=20;$i++)
{
	$found = 0;
	$sleep = 10;
	$result = sql_query_read('SHOW PROCESSLIST ');
	while($list = mysql_fetch_assoc($result))
	{
		preg_match('/\/\* TIMEOUT=([0-9]*) \*\//', $list['Info'], $matches);
		$limit = intval($matches[1]);
		if($limit>=5 || (intval($list['Time'])>=300 && strpos(strtolower($list['Info']),'select') !== false))
		{
			if(intval($list['Time'])>=$limit)
			{
				$sql = "KILL ".$list['Id']."\n";
				sql_query_write($sql);
				echo $sql;
				$found = 1;
			}
			$sleep = 1;
		}
	}
	if(!$found) echo date('G:i:s').": No Processes to Kill<br>";
	flush();
	if($time+$timelimit < time()) die();
	sleep($sleep);
	
}
?>