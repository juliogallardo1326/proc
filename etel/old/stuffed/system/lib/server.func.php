<?
//-----------------------------------------
function server_soft($isapache=true){
	$s = $_SERVER['SERVER_SOFTWARE'];
	if($isapache) return strpos($s, 'Apache')!==FALSE;
	if(strpos($s, 'IIS')!==FALSE) return 'IIS';	
	if(strpos($s, '/')!==FALSE){ 
		$s = explode('/', $s); 
		return $s[0];
	}	
	return $s;
}
//-----------------------------------------
function server_version(){
	$s = $_SERVER['SERVER_SOFTWARE'];
	if(strpos($s, ' ')!==FALSE){ 
		$s = explode(' ', $s); 
		$s = $s[0];
	}	
	if(strpos($s, '/')!==FALSE){ 
		$s = explode('/', $s); 
		return $s[1];
	}	
	return $s;
}
//-----------------------------------------
function mod_rewrite_on(){
	if(!function_exists('apache_get_modules')) return false;
	$m = @apache_get_modules();
	return array_search('mod_rewrite', $m)!==FALSE;
}
//-----------------------------------------
?>