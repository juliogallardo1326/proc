<?php
	header("Content-type: application/php");
	header("Content-Disposition: attachment; filename=cancelrequest.csv");
	if(!($file = fopen("csv/cancelrequest.csv", "r")))
	{
		print("Can not open file");
		exit();
	}	
	$content = fread($file, filesize("csv/cancelrequest.csv"));
	$content = explode("\r\n", $content);
	fclose($file);
	$file_content = "";
	for($i=0;$i<count($content);$i++)
	{
		$file_content .= $content[$i];
	}
	print($file_content);
?>