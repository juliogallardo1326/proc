<?php
	require_once 'DB.php';
	require_once 'config.php';
	$dsn = 'mysql://shinyw2_spider:spider@localhost/shinyw2_spider';
	$options = array(
		'debug'       => 2,
		'portability' => DB_PORTABILITY_ALL,
	);

	$db =& DB::connect($dsn, $options);

	if (PEAR::isError($db)) {
		die($db->getMessage());
	}
	
	$page = file_get_contents('http://www.starblast.org');
	
	print_r(get_urls($page));
	
	
	
	
	function get_urls($string) {
		$regexp = '/'."(href|src|url)[\\s]*=\"?[^ #\">]+\"?(?=[ #>])?".'/';
		$regexp = '/'."<a[\s]+[^>]*?href[\\s]*=[\"']?[^ >]+(?=[ #>])[\"']?>([^<]+|.*?)?<\/a>".'/';
		preg_match_all ($regexp, $string, &$matches);
		$ret = $matches;
		return $ret;
	};
?>
