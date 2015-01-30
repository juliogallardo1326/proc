<?
$gateway_db_select = 3;
$etel_debug_mode = 0;
$etel_disable_https = 1;
require_once("angel.class.php");

$angel = new angel_class();

$_REQUEST = array_merge($_POST,$_GET);

$vars = $angel->angel_log->get_session($_REQUEST['CallGUID']);

$angel->working_vars = NULL;

if($vars != NULL) $angel->working_vars = unserialize($vars);

echo $angel->angel_load_page($_REQUEST);

$angel->angel_log->store_session($_REQUEST['CallGUID'],serialize($angel->working_vars));

?>