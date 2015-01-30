<?


function etel_serialize($arr,$compress=false)
{
	if(!is_array($arr)) dieLog("Cannot serialize scalar value $arr");
	$serialized = serialize($arr);
	if($compress)
	{
		$serialized_c = gzcompress($serialized);
		if($serialized_c!=false) $serialized = $serialized_c; //dieLog("Failed to Compress $serialized_c");
	}
	return $serialized;
}

function etel_unserialize($serialized,$compress=false)
{
	if($compress)
	{
		$serialized_c = gzuncompress($serialized);
		if($serialized_c!=false) $serialized = $serialized_c; //dieLog("Failed to Decompress $serialized_c");
	}
	$arr = unserialize($serialized);
	
	if($serialized && !is_array($arr)) dieLog("Cannot unserialize string $serialized");
	return $arr;
}

function getRealIp() {
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
   $ip = getenv("HTTP_CLIENT_IP");

   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
   $ip = getenv("HTTP_X_FORWARDED_FOR");

   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
   $ip = getenv("REMOTE_ADDR");

   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
   $ip = $_SERVER['REMOTE_ADDR'];

   else
   $ip = "unknown";

   return($ip);
}

function isProxy()
{
	if (getenv("HTTP_X_FORWARDED_FOR")) 
	{ 
		return true;
	}
	return false;
}

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

	
function sql_query_read($sql,$info=array())
{
	global $etel_query_info;
	global $etel_debug_mode;
	global $cnn_cs;

	if(!isset($etel_query_info)) $etel_query_info = array('results'=>array());
	
	if($etel_debug_mode)
	{
		if(preg_match('/(^INSERT|^UPDATE|^DELETE|^REPLACE)/i',$sql)) 
		{
			$debug_array = debug_backtrace ();
			$debug_info= "\n";
			foreach($debug_array[0] as $key=>$data)
				$debug_info.= $key.":".$data."\n";
					
			dieLog("QUERY READ-ONLY ERROR: $debug_info SQL=$sql");
		}
	}
	$time = microtime_float();
	$clean_sql = strtolower(str_replace(array("\n", "\r", "\t", " "),"", $sql));

	$timeout = intval($info['TimeOut']);
	$comment = '';
	if($timeout) $comment = "/* TIMEOUT=$timeout */";
	$res = mysql_query($comment.$sql,$cnn_cs);
	//$etel_query_info['cache'][md5($clean_sql)] = $res;
	if($etel_debug_mode)
	{
		$duration = microtime_float() - $time;
		if(!$info['HideDebug'] && sizeof($etel_query_info['results'])<30) $etel_query_info['results'][] = array('sql'=>$sql,'duration'=>$duration,'error'=>mysql_error());
	}
	return $res;
}

function sql_query_write($sql,$info=array())
{
	global $etel_query_info;
	global $etel_debug_mode;
	global $curUserInfo;
	global $cnn_cs;
	
	if($curUserInfo['en_access'][ACCESS_READ_ONLY]) $sql = "set @a=1";
	
	if($etel_debug_mode)
	{
		if(preg_match('/(^SELECT)/i',$sql)) 
		{
			$debug_array = debug_backtrace ();
			$debug_info= "\n";
			foreach($debug_array[0] as $key=>$data)
				$debug_info.= $key.":".$data."\n";
					
			dieLog("QUERY READ-ONLY ERROR: $debug_info SQL=$sql");
		}
	}
	$time = microtime_float();
	
	$res = mysql_query($sql,$cnn_cs);
	
	if($etel_debug_mode)
	{
		$duration = microtime_float() - $time;
		if(!$info['HideDebug'] && sizeof($etel_query_info['results'])<30) $etel_query_info['results'][] = array('sql'=>$sql,'duration'=>$duration,'error'=>mysql_error());
	}
	return $res;
}


?>