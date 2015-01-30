<?php
	header("Content-type: application/pdf");
	header("Content-Disposition: attachment; filename=invoice.pdf");
	$str_file_name = (isset($HTTP_GET_VARS["filename"])?Trim($HTTP_GET_VARS["filename"]):"");
	if(!($file = fopen("Temp_doc/$str_file_name", "r")))
	{
		print("Can not open file");
		exit();
	}	
	$content = fread($file, filesize("Temp_doc/$str_file_name"));
	$content = explode("\r\n", $content);
	fclose($file);
	$file_content = "";
	for($i=0;$i<count($content);$i++)
	{
		$file_content .= $content[$i];
	}
	print($file_content);

?>