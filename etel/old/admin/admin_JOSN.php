<?php
if(!$_REQUEST['debug']) $etel_debug_mode=0;
$pageConfig['SingleViewAllowed'] = true;
$pageConfig['AllowBank'] = true;
$pageConfig['HideHeader'] = true;
$rootdir = "../";

require_once("includes/sessioncheck.php");
require_once("includes/header.php");

$data = JSON_get_data($_REQUEST);
$json = new Services_JSON();
if(!$etel_debug_mode) unset($data['json_query']);
if($data['output'])
	$output = $data['output'];
else
	$output = $json->encode($data);
etelDie('<PRE>'.wordwrap($output,90,'<br />',true).'</PRE>');
print($output);
?>