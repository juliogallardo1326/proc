<?


function HLSQL($Query)
{
	$Query=preg_replace("/(\s{1})(JOIN)/", "\\1\nJOIN", $Query);
	$Query=preg_replace("/([^a-zA-Z]{1})([\d0-9]+)/", "\\1<span style=\"color:6666CC\">\\2</span>", $Query);

	$Query=preg_replace("/('[^']+')/", "<span style=\"color:FF9900\">\\1</span>", $Query);
	$Query=preg_replace("/(#.*\n)/", "<span style=\"color:999999\">\\1</span>", $Query);
	$Query=preg_replace("/FROM[\s+](\S+)\s/", "FROM <span style=\"color:339900;text-decoration:underline\">\\1</span> ", $Query);
	
	$Query=preg_replace("/JOIN[\s+](\S+)\s/", "JOIN <span style=\"color:339900;text-decoration:underline\">\\1</span> ", $Query);
	$Query=preg_replace("/(COUNT|SUM)\(([^\)]+)\)/", "<span style=\"color:FF0000;font-weight:bold\">\\1</span>(\\2)", $Query);


	$Query=preg_replace("/\s{1}(FROM|JOIN|GROUP|WHERE|ORDER|LIMIT|AND|ON)\s{1}/", " <span style=\"color:0000FF;font-weight:bold;\">\\1</span> ", $Query);
	$Query=preg_replace("/\s{1}(BY|OR|AS|IN|BETWEEN)\s{1}/", " <span style=\"color:000000;font-weight:bold;\">\\1</span> ", $Query);
	$Query=preg_replace("/(SELECT)/", " <span style=\"color:0000FF;font-weight:bold;\">\\1</span> ", $Query);
	$Query=preg_replace("/\(/", "<span style=\"font-weight:bold;\">(</span>", $Query);
	$Query=preg_replace("/\)/", "<span style=\"font-weight:bold;\">)</span>", $Query);


	return "<p style=\"color:000000; font-size:12px;\">".nl2br($Query)."</p>";
}

?>