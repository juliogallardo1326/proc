<?
function get_script_file($script_file,$data_array=NULL)
{	
	
	$script_content = @file_get_contents("plugins/scripts/$script_file");
	if(!$script_content) die();
	foreach($data_array as $key=>$data)
		$script_content = str_replace("{#$key#}",$data,$script_content);
		
	return $script_content;
}

$script_file = preg_replace('/[^a-zA-Z0-9_]/','',$_GET['script_file']);
unset($_GET['script_file']);
$script_contents = get_script_file($script_file,$_GET);

header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Transfer-Encoding: octet");
header("Content-Disposition: attachment; filename=\"".str_replace('_','.',$script_file)."\"");

echo $script_contents;

?>
