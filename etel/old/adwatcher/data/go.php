<?php
// URL to your tracker.php file
$path 	= "http://www.advertwatcher.com/v2/client/tracker.php" . $_SERVER['QUERY_STRING'];
header("Location: $path");
?>