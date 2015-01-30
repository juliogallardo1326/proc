<?
$etel_debug_mode= 1;

require_once("ivr.class.php");
require_once("lookup.class.php");
include '../includes/dbconnection.php';

$tran = new lookup_class();
$trans = "Hello World";
//$trans = $tran->compliant_details("2003-07-01 00:00:00","2006-07-31 23:59:59");
echo "<pre>";
print_r($trans);
echo "</pre>";

?>