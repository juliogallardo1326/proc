<?
$pageConfig['Title'] = 'Support System';
$pageConfig['HideToDo'] = true;
include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
require_once('includes/header.php');
?>
<iframe src="ev/login.php" width="1000" height="800" frameborder="0" > </iframe>

<?php
include("includes/footer.php");
?>
