<?php

$svr = $_SERVER["PATH_TRANSLATED"];
$path_parts = pathinfo($svr); 
$str_current_path = $path_parts["dirname"];

if(!($file = fopen("$str_current_path/Temp_doc/test.pdf", "r")))
{
	print("Can not open file");
	exit();
}	
$content = fread($file, filesize("$str_current_path/Temp_doc/test.pdf"));
//$content = explode("\r\n", $content);
$content = str_replace("Company", "JK", $content);
$content .= "adff";
fclose($file);
print($content);

function pdf_replace( $pattern, $replacement, $string )
{
$len = strlen( $pattern );
$regexp = '';
for ( $i = 0; $i<$len; $i++ )
{
$regexp .= $pattern[$i];
if ($i<$len-1)
$regexp .= "(\)\-{0,1}[0-9]*\(){0,1}";
}
return ereg_replace ( $regexp, $replacement, $string );
}

?>