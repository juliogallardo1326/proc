<?php
die();
require_once('../includes/sp_db.php');
require_once('../includes/ISPY.php');
$spy = new ISPY();
$spy->parseAllSites();
?>