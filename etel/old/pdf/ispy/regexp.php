<?php

	
	$page = file_get_contents('http://www.starblast.org');
	
	print_r(get_urls($page));
	
	
	
	
	function get_urls($string) {
		$regexp = '/'."<a[\s]+[^>]*(href|src|url)[\\s]*=[\\s]*\"?([^ #\">]+)((?=[ >#])|(\"))[^>]*>?(.*?)<\/a>".'/';
		preg_match_all ($regexp, $string, &$matches);
		$ret = $matches;
		return $ret;
	};
?>
